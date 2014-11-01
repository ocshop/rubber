<?php 
class ControllerBlogLatest extends Controller { 	
	public function index() { 
	
		$this->language->load('blog/latest');

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

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/blog.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/blog.css');
		}
		
		$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/jail/jail.min.js');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$url = '';

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

		$this->data['heading_title'] = $this->language->get('heading_title');

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
			
		$this->load->model('blog/news');	
		
		
		if (isset($this->request->get['blid'])) {
			$blid = '';
				
			foreach (explode('_', $this->request->get['blid']) as $path_id) {
				if (!$blid) {
					$blid = $path_id;
				} else {
					$blid .= '_' . $path_id;
				}
				
				$news_info = $this->model_blog_news->getCategory($path_id);
				
				if ($news_info) {
					$this->data['breadcrumbs'][] = array(
						'text'      => $news_info['name'],
						'href'      => $this->url->link('blog/latest', 'blid=' . $blid),
						'separator' => $this->language->get('text_separator')
					);
				}
			}
		}

		$this->data['articles'] = array();
			
			$data = array(
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);
					
			$article_total = $this->model_blog_article->getTotalArticles($data); 
			
			$results = $this->model_blog_article->getArticles($data);
			
			$this->data['review_status'] = $this->config->get('config_blog_review_status');
			

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
					'href'        => $this->url->link('blog/article',  '&article_id=' . $result['article_id'])
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
				'href'  => $this->url->link('blog/latest', 'blid=' . '&sort=p.sort_order&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('blog/latest', 'blid=' . '&sort=pd.name&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('blog/latest', 'blid=' . '&sort=pd.name&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_asc'),
				'value' => 'p.date_added-ASC',
				'href'  => $this->url->link('blog/latest',  '&sort=p.date_added&order=ASC' . $url)
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_desc'),
				'value' => 'p.date_added-DESC',
				'href'  => $this->url->link('blog/latest', '&sort=p.date_added&order=DESC' . $url)
			); 
			
			if ($this->config->get('config_blog_review_status')) {
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('blog/latest',  '&sort=rating&order=DESC' . $url)
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('blog/latest',  '&sort=rating&order=ASC' . $url)
				);
			}

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_viewed_desc'),
				'value' => 'p.viewed-DESC',
				'href'  => $this->url->link('blog/latest',  '&sort=p.viewed&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_viewed_asc'),
				'value' => 'p.viewed-ASC',
				'href'  => $this->url->link('blog/latest',  '&sort=p.viewed&order=ASC' . $url)
			); 
			
			$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$this->data['limits'] = array();

		$limits = array_unique(array($this->config->get('config_catalog_limit'), 25, 50, 75, 100));

		sort($limits);

		foreach($limits as $value){
			$this->data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('blog/article', $url . '&limit=' . $value)
			);
		}

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
		$pagination->url = $this->url->link('blog/latest', $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['article_total'] = $article_total;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/latest.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/blog/latest.tpl';
		} else {
			$this->template = 'default/template/blog/latest.tpl';
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
?>