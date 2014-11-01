<?php
class ControllerModuleBlogMostViewed extends Controller {
	protected function index($setting) {
		$this->language->load('module/blog_mostviewed');
 
      	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_views'] = $this->language->get('text_views');
		
		$this->load->model('blog/article');
		
		$this->load->model('tool/image');

		$this->data['articles'] = array();

		$results = $this->model_blog_article->getPopularArticles($setting['limit']);
		
		if ($setting['text_limit'] > 0) {
			$text_limit = $setting['text_limit'];
		} else {
			$text_limit = 50;
		}
		
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}
			
			if (($this->config->get('config_blog_review_status'))and($result['article_review']==1)) {
				$rating = (int)$result['rating'];
			} else {
				$rating = false;
			}
							
			$this->data['articles'][] = array(
				'article_id' => $result['article_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $text_limit) . '',
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'viewed'      => $result['viewed'],
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('blog/article', 'article_id=' . $result['article_id']),
			);
		}

		if ((file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_mostviewed.tpl'))and (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_mostviewed_middle.tpl')))     {
			$this->template = $this->config->get('config_template') . '/template/module/blog_mostviewed.tpl';
			
			if (($setting['position']=='content_top') or ($setting['position']=='content_bottom'))  {$this->template = $this->config->get('config_template') . '/template/module/blog_mostviewed_middle.tpl';};
			
		
		} else {
			$this->template = 'default/template/module/blog_mostviewed.tpl';
		}

		$this->render();
	}
}
?>