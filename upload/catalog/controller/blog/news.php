<?php 
class ControllerBlogNews extends Controller {  
	public function index() { 
		$this->language->load('blog/news');
		
		$this->load->model('blog/news');
		
		$this->load->model('blog/article');
		
		
		$this->load->model('tool/image'); 
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
			$this->document->setRobots('noindex,follow');
		} else {
			$sort = 'p.date_added';
		}
		

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
			$this->document->setRobots('noindex,follow');
		} else { 
			$page = 1;
		}	
							
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
			$this->document->setRobots('noindex,follow');
		} else {
			$limit = $this->config->get('config_blog_catalog_limit');
		}
					
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);	
			
		if (isset($this->request->get['blid'])) {
			$blid = '';
		
			$parts = explode('_', (string)$this->request->get['blid']);
			
			$news_id = (int)array_pop($parts);
		
			foreach ($parts as $blid_id) {
				if (!$blid) {
					$blid = (int)$blid_id;
				} else {
					$blid .= '_' . (int)$blid_id;
				}
									
				$news_info = $this->model_blog_news->getCategory($blid_id);
				
				if ($news_info && ($blid_id != $news_id)) {
	       			$this->data['breadcrumbs'][] = array(
   	    				'text'      => $news_info['name'],
						'href'      => $this->url->link('blog/news', 'blid=' . $blid),
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}		
		
			
		} else {
			$news_id = 0;
		}

		$news_info = $this->model_blog_news->getCategory($news_id);
	
		if ($news_info) {
			if ($news_info['seo_title']) {
		  		$this->document->setTitle($news_info['seo_title']);
			} else {
		  		$this->document->setTitle($news_info['name']);
			}

			$this->document->setDescription($news_info['meta_description']);
			$this->document->setKeywords($news_info['meta_keyword']);
			$this->data['review_status'] = $this->config->get('config_blog_review_status');
			
			 if ($news_info['seo_h1']) {
				$this->data['heading_title'] = $news_info['seo_h1'];
				} else {
				$this->data['heading_title'] = $news_info['name'];
			}
			
			if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css')) {
				$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css');
			} else {
				$this->document->addStyle('catalog/view/theme/default/stylesheet/blog.css');
			}
			
			$this->document->addScript('catalog/view/javascript/jquery/jail/jail.min.js');
			$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
			
			$this->data['text_refine'] = $this->language->get('text_refine');
			$this->data['text_views'] = $this->language->get('text_views');
			$this->data['text_empty'] = $this->language->get('text_empty');			
			$this->data['text_display'] = $this->language->get('text_display');
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_grid'] = $this->language->get('text_grid');
			$this->data['text_sort'] = $this->language->get('text_sort');
			$this->data['text_limit'] = $this->language->get('text_limit');
			
			$this->data['text_sort_by'] = $this->language->get('text_sort_by');
			$this->data['text_sort_name'] = $this->language->get('text_sort_name');
			$this->data['text_sort_date'] = $this->language->get('text_sort_date');
			$this->data['text_sort_rated'] = $this->language->get('text_sort_rated');
			$this->data['text_sort_viewed'] = $this->language->get('text_sort_viewed');
					
			$this->data['button_more'] = $this->language->get('button_more');
			$this->data['button_continue'] = $this->language->get('button_continue');
					
			if ($news_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($news_info['image'], $this->config->get('config_blog_image_category_width'), $this->config->get('config_blog_image_category_height'));
			} else {
				$this->data['thumb'] = '';
			}
									
			$this->data['description'] = html_entity_decode($news_info['description'], ENT_QUOTES, 'UTF-8');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}


			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
								
			$this->data['categories'] = array();
			
			$results = $this->model_blog_news->getCategories($news_id);
			
			
			foreach ($results as $result) {
				$data = array(
					'filter_news_id'  => $result['news_id'],
					'filter_sub_news' => true
				);
				
				$article_total = $this->model_blog_article->getTotalArticles($data);				
				
				$this->data['categories'][] = array(
					'name'  => $result['name'] . ($this->config->get('config_blog_article_count') ? ' (' . $article_total . ')' : ''),
					'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '_' . $result['news_id'] . $url)
				);
			}
			
			$this->data['articles'] = array();
			
			$data = array(
				'filter_news_id' => $news_id, 
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);
					
			$article_total = $this->model_blog_article->getTotalArticles($data); 
			
			$results = $this->model_blog_article->getArticles($data);
			
		
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_blog_image_article_width'), $this->config->get('config_blog_image_article_height'));
				} else {
					$image = false;
				}
				
				if ($result['image']) {
					$image_gallery = $this->model_tool_image->resize($result['image'], $this->config->get('config_blog_image_gallery_width'), $this->config->get('config_blog_image_gallery_height'));
				} else {
					$image_gallery = false;
				}
							
				
			if (($this->config->get('config_blog_review_status'))and($result['article_review']==1)) {
		
					$rating = (int)$result['rating'];

				} else {
					$rating = false;
				}
								
				$this->data['articles'][] = array(
					'article_id'  => $result['article_id'],
					'thumb'       => $image,
					'thumb_gallery'  => $image_gallery,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 500) . '..',
					'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'viewed'      => $result['viewed'],
					'rating'      => $rating,
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('blog/article', 'blid=' . $this->request->get['blid'] . '&article_id=' . $result['article_id'])
				);
			}
			
			$url = '';
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
							
			$this->data['sorts'] = array();
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '&sort=p.sort_order&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '&sort=pd.name&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '&sort=pd.name&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_asc'),
				'value' => 'p.date_added-ASC',
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '&sort=p.date_added&order=ASC' . $url)
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_desc'),
				'value' => 'p.date_added-DESC',
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '&sort=p.date_added&order=DESC' . $url)
			); 
			
			if ($this->config->get('config_blog_review_status')) {
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '&sort=rating&order=DESC' . $url)
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '&sort=rating&order=ASC' . $url)
				);
			}
			
			//ocshop sort viewed
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_viewed_asc'),
				'value' => 'p.viewed-ASC',
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '&sort=p.viewed&order=ASC' . $url)
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_viewed_desc'),
				'value' => 'p.viewed-DESC',
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . '&sort=p.viewed&order=DESC' . $url)
			); 
			//ocshop sort viewed
			
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['limits'] = array();
			
			$this->data['limits'][] = array(
				'text'  => $this->config->get('config_blog_catalog_limit'),
				'value' => $this->config->get('config_blog_catalog_limit'),
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . $url . '&limit=' . $this->config->get('config_blog_catalog_limit'))
			);
						
			$this->data['limits'][] = array(
				'text'  => 25,
				'value' => 25,
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . $url . '&limit=25')
			);
			
			$this->data['limits'][] = array(
				'text'  => 50,
				'value' => 50,
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . $url . '&limit=50')
			);

			$this->data['limits'][] = array(
				'text'  => 75,
				'value' => 75,
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . $url . '&limit=75')
			);
			
			$this->data['limits'][] = array(
				'text'  => 100,
				'value' => 100,
				'href'  => $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . $url . '&limit=100')
			);
						
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
					
			$pagination = new Pagination();
			$pagination->total = $article_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('blog/news', 'blid=' . $this->request->get['blid'] . $url . '&page={page}');
		
			$this->data['pagination'] = $pagination->render();
			//ocshop
			$this->data['article_total'] = $article_total;
			//ocshop
		
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
		
			$this->data['continue'] = $this->url->link('common/home');
			
			
			if ($news_info['top'] == 0) {
					$tempurl='news';
					} else{
					$tempurl='news_gallery';
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/' . $tempurl . '.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/blog/' . $tempurl . '.tpl';
					
			} else {
				$this->template = 'default/template/blog/news.tpl';
			}

		
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
				
			$this->response->setOutput($this->render());										
    	} else {
			$url = '';
			
			if (isset($this->request->get['blid'])) {
				$url .= '&blid=' . $this->request->get['blid'];
			}
									
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('blog/news', $url),
				'separator' => $this->language->get('text_separator')
			);
				
			$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
					
			$this->response->setOutput($this->render());
		}
  	}
}
?>