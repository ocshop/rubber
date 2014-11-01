<?php
class ControllerModuleBlogFeatured extends Controller {
	protected function index($setting) {
		$this->language->load('module/blog_featured'); 

      	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_views'] = $this->language->get('text_views');
		
		$this->load->model('blog/article'); 
		
		$this->load->model('tool/image');

		$this->data['articles'] = array();

		$articles = explode(',', $this->config->get('blog_featured_article'));		

		if (empty($setting['limit'])) {
			$setting['limit'] = 5;
		}
		
		if ($setting['text_limit'] > 0) {
			$text_limit = $setting['text_limit'];
		} else {
			$text_limit = 50;
		}
		
		$articles = array_slice($articles, 0, (int)$setting['limit']);
		
		foreach ($articles as $article_id) {
			$article_info = $this->model_blog_article->getArticle($article_id);
			
			if ($article_info) {
				if ($article_info['image']) {
					$image = $this->model_tool_image->resize($article_info['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = false;
				}
				
				if (($this->config->get('config_blog_review_status'))and($article_info['article_review']==1)) {
					$rating = $article_info['rating'];
				} else {
					$rating = false;
				}
					
				$this->data['articles'][] = array(
					'article_id' => $article_info['article_id'],
					'thumb'   	 => $image,
					'name'    	 => $article_info['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8')), 0, $text_limit) . '',
					'date_added'  => date($this->language->get('date_format_short'), strtotime($article_info['date_added'])),
				    'viewed'      => $article_info['viewed'],
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$article_info['reviews']),
					'href'    	 => $this->url->link('blog/article', 'article_id=' . $article_info['article_id']),
				);
			}
		}

		if ((file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_featured.tpl'))and (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_featured_middle.tpl')))     {
			$this->template = $this->config->get('config_template') . '/template/module/blog_featured.tpl';
			
			if (($setting['position']=='content_top') or ($setting['position']=='content_bottom'))  {$this->template = $this->config->get('config_template') . '/template/module/blog_featured_middle.tpl';};
			
		
		} else {
			$this->template = 'default/template/module/blog_featured.tpl';
		}

		$this->render();
	}
}
?>