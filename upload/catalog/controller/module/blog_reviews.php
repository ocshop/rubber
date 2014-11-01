<?php
class ControllerModuleBlogReviews extends Controller {
	protected function index($setting) {
		$this->language->load('module/blog_reviews');

		$this->load->model('blog/article');
		
		$this->load->model('tool/image');
		
		$this->load->model('module/blog_reviews');
		
		$this->data['text_views'] = $this->language->get('text_views');
		if (strlen($setting['header'][$this->config->get('config_language_id')]) > 0){
      		$this->data['header'] = $setting['header'][$this->config->get('config_language_id')];
		} else {
      		$this->data['header'] = false;
		}
		
		$this->data['reviews'] = array();

		if ($setting['limit'] > 0) {
			$limit = $setting['limit'];
		} else {
			$limit = 4;
		}
		
		if ($setting['text_limit'] > 0) {
			$text_limit = $setting['text_limit'];
		} else {
			$text_limit = 50;
		}
		
		if ($setting['type'] == 'latest') {
			$results = $this->model_module_blog_reviews->getLatestReviews($limit);
		} else {
			$results = $this->model_module_blog_reviews->getRandomReviews($limit);
		}

		foreach ($results as $result) {
			if ($this->config->get('config_review_article_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

   			$article_id = false;
   			$article = false;
   			$articl_thumb = false;
   			$articl_name = false;
			$articl_viewed = false;
   			$articl_href = false;
			
			if ($result['article_id']) {
				$article = $this->model_blog_article->getArticle($result['article_id']);
				if ($article['image']) {
       				$articl_thumb = $this->model_tool_image->resize($article['image'], $setting['image_width'], $setting['image_height']);
				}
				$article_id = $article['article_id'];
    			$articl_name = $article['name'];
				$articl_viewed = $article['viewed'];
    			$articl_href = $this->url->link('blog/article', 'article_id=' . $article['article_id']);
			}

			$this->data['reviews'][] = array(
				'review_id'   => $result['review_article_id'],
				'rating'      => $result['rating'],
                'description' => mb_substr($result['text'], 0, $text_limit,'utf-8') . '',
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'        => $this->url->link('blog/article', 'article_id=' . $article['article_id']),
				'author'      => $result['author'],
				'article_id'  => $article_id,
  				'articl_thumb'  => $articl_thumb,
  				'articl_name'   => $articl_name,
				'viewed'      => $articl_viewed,
  				'articl_href'   => $articl_href
			);
		}

		if ((file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_reviews.tpl'))and (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_reviews_middle.tpl')))     {
			$this->template = $this->config->get('config_template') . '/template/module/blog_reviews.tpl';
			
			if (($setting['position']=='content_top') or ($setting['position']=='content_bottom'))  {$this->template = $this->config->get('config_template') . '/template/module/blog_reviews_middle.tpl';};
			
		
		} else {
			$this->template = 'default/template/module/blog_reviews.tpl';
		}

		$this->render();
	}
}
?>