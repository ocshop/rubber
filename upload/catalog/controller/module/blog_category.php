<?php  
class ControllerModuleBlogCategory extends Controller {
	protected function index($setting) {
		$this->language->load('module/blog_category');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->get['blid'])) {
			$parts = explode('_', (string)$this->request->get['blid']);
		} else {
			$parts = array();
		}
		
		if (isset($parts[0])) {
			$this->data['news_id'] = $parts[0];
		} else {
			$this->data['news_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
							
		$this->load->model('blog/news');

		$this->load->model('blog/article');

		$this->data['categories'] = array();

		$categories = $this->model_blog_news->getCategories(0);

		foreach ($categories as $news) {
			$total = $this->model_blog_article->getTotalArticles(array('filter_news_id'  => $news['news_id']));

			$children_data = array();

			$children = $this->model_blog_news->getCategories($news['news_id']);

			foreach ($children as $child) {
				$data = array(
					'filter_news_id'  => $child['news_id'],
					'filter_sub_news' => true
				);

				$article_total = $this->model_blog_article->getTotalArticles($data);

				$total += $article_total;

				$children_data[] = array(
					'news_id' => $child['news_id'],
					'name'        => $child['name'] . ($this->config->get('config_blog_article_count') ? ' (' . $article_total . ')' : ''),
					'href'        => $this->url->link('blog/news', 'blid=' . $news['news_id'] . '_' . $child['news_id'])	
				);		
			}

			$this->data['categories'][] = array(
				'news_id' => $news['news_id'],
				'name'        => $news['name'] . ($this->config->get('config_blog_article_count') ? ' (' . $total . ')' : ''),
				'children'    => $children_data,
				'href'        => $this->url->link('blog/news', 'blid=' . $news['news_id'])
			);	
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/blog_category.tpl';
		} else {
			$this->template = 'default/template/module/blog_category.tpl';
		}
		
		$this->render();
  	}

}
?>