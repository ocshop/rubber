<?php
class ControllerModuleFeaturedarticle extends Controller {
	protected function index($setting) {
		$this->language->load('module/featuredarticle');
		$this->load->model('blog/article');
  		if (isset($this->request->get['path'])) {			$path = '';			$parts = explode('_', (string)$this->request->get['path']);			foreach ($parts as $path_id) {				if (!$path) {					$path = $path_id;				} else {					$path .= '_' . $path_id;				}				$category_info = $this->model_catalog_category->getCategory($path_id);			}					$category_id = array_pop($parts);						$results = $this->model_blog_article->getArticleRelated_by_category($category_id, $setting['limit']);		} else {			$category_id = 0;		}				//Manufacturer
				if (isset($this->request->get['manufacturer_id'])) {			$manufacturer_id = $this->request->get['manufacturer_id'];						$results = $this->model_blog_article->getArticleRelated_by_manufacturer($manufacturer_id, $setting['limit']);		} else {			$manufacturer_id = 0;		} 				//Manufacturer      	$this->data['heading_title'] = $this->language->get('heading_title');		
		$this->load->model('blog/article');		
		$this->load->model('tool/image');	

		$this->data['articles'] = array();
		
		$articles = explode(',', $this->config->get('featuredarticle'));		

		if (empty($setting['limit'])) {
			$setting['limit'] = 5;
		}
		
		if ($setting['text_limit'] > 0) {
			$text_limit = $setting['text_limit'];
		} else {
			$text_limit = 50;
		}

		if (isset($results)) {
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
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
		}				}

		if ((file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featuredarticle.tpl'))and (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featuredarticle_middle.tpl')))     {
			$this->template = $this->config->get('config_template') . '/template/module/featuredarticle.tpl';						if (($setting['position']=='content_top') or ($setting['position']=='content_bottom'))  {$this->template = $this->config->get('config_template') . '/template/module/featuredarticle_middle.tpl';};					
		} else {
			$this->template = 'default/template/module/featuredarticle.tpl';
		}

		$this->render();
	}
}
?>