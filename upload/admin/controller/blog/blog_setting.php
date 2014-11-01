<?php
class ControllerBlogBlogSetting extends Controller {
	private $error = array();
 
	public function index() {
		$this->load->language('blog/blog_setting'); 

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('blogconfig', $this->request->post);

			
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('blog/blog_setting', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_select'] = $this->language->get('text_select');
		
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_items'] = $this->language->get('text_items');
		$this->data['text_article'] = $this->language->get('text_article');
		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
 		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');	
		
		$this->data['entry_blog_catalog_limit'] = $this->language->get('entry_blog_catalog_limit');
		$this->data['entry_blog_image_popup'] = $this->language->get('entry_blog_image_popup');
		$this->data['entry_blog_admin_limit'] = $this->language->get('entry_blog_admin_limit');
		$this->data['entry_blog_header_menu'] = $this->language->get('entry_blog_header_menu');
		$this->data['entry_blog_article_count'] = $this->language->get('entry_blog_article_count');
		$this->data['entry_blog_review'] = $this->language->get('entry_blog_review');
		$this->data['entry_blog_download'] = $this->language->get('entry_blog_download');
		
		$this->data['entry_blog_image_category'] = $this->language->get('entry_blog_image_category');
		$this->data['entry_blog_image_article'] = $this->language->get('entry_blog_image_article');
		$this->data['entry_blog_image_gallery'] = $this->language->get('entry_blog_image_gallery');
		$this->data['entry_blog_image_additional'] = $this->language->get('entry_blog_image_additional');
		$this->data['entry_blog_image_related'] = $this->language->get('entry_blog_image_related');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_option'] = $this->language->get('tab_option');
		$this->data['tab_image'] = $this->language->get('tab_image');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
				
 		if (isset($this->error['blog_image_category'])) {
			$this->data['error_blog_image_category'] = $this->error['blog_image_category'];
		} else {
			$this->data['error_blog_image_category'] = '';
		}
		
 		if (isset($this->error['image_blog_popup'])) {
			$this->data['error_blog_image_popup'] = $this->error['image_blog_popup'];
		} else {
			$this->data['error_blog_image_popup'] = '';
		}
		
 		if (isset($this->error['blog_image_article'])) {
			$this->data['error_blog_image_article'] = $this->error['blog_image_article'];
		} else {
			$this->data['error_blog_image_article'] = '';
		}
		
		if (isset($this->error['blog_image_gallery'])) {
			$this->data['error_blog_image_gallery'] = $this->error['blog_image_gallery'];
		} else {
			$this->data['error_blog_image_gallery'] = '';
		}
		
 		if (isset($this->error['blog_image_additional'])) {
			$this->data['error_blog_image_additional'] = $this->error['blog_image_additional'];
		} else {
			$this->data['error_blog_image_additional'] = '';
		}	
		
 		if (isset($this->error['blog_image_related'])) {
			$this->data['error_blog_image_related'] = $this->error['blog_image_related'];
		} else {
			$this->data['error_blog_image_related'] = '';
		}
				
		if (isset($this->error['error_filename'])) {
			$this->data['error_error_filename'] = $this->error['error_filename'];
		} else {
			$this->data['error_error_filename'] = '';
		}		
		
		if (isset($this->error['blog_catalog_limit'])) {
			$this->data['error_blog_catalog_limit'] = $this->error['blog_catalog_limit'];
		} else {
			$this->data['error_blog_catalog_limit'] = '';
		}
		
		if (isset($this->error['blog_admin_limit'])) {
			$this->data['error_blog_admin_limit'] = $this->error['blog_admin_limit'];
		} else {
			$this->data['error_blog_admin_limit'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('blog/blog_setting', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action'] = $this->url->link('blog/blog_setting', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
	
		if (isset($this->request->post['config_blog_catalog_limit'])) {
			$this->data['config_blog_catalog_limit'] = $this->request->post['config_blog_catalog_limit'];
		} else {
			$this->data['config_blog_catalog_limit'] = $this->config->get('config_blog_catalog_limit');
		}	
						
		if (isset($this->request->post['config_blog_admin_limit'])) {
			$this->data['config_blog_admin_limit'] = $this->request->post['config_blog_admin_limit'];
		} else {
			$this->data['config_blog_admin_limit'] = $this->config->get('config_blog_admin_limit');
		}
		
		if (isset($this->request->post['config_blog_header_menu'])) {
			$this->data['config_blog_header_menu'] = $this->request->post['config_blog_header_menu'];
		} else {
			$this->data['config_blog_header_menu'] = $this->config->get('config_blog_header_menu');
		}
		
		if (isset($this->request->post['config_blog_article_count'])) {
			$this->data['config_blog_article_count'] = $this->request->post['config_blog_article_count'];
		} else {
			$this->data['config_blog_article_count'] = $this->config->get('config_blog_article_count');
		}
				
		if (isset($this->request->post['config_blog_review_status'])) {
			$this->data['config_blog_review_status'] = $this->request->post['config_blog_review_status'];
		} else {
			$this->data['config_blog_review_status'] = $this->config->get('config_blog_review_status');
		}
		
		if (isset($this->request->post['config_blog_download'])) {
			$this->data['config_blog_download'] = $this->request->post['config_blog_download'];
		} else {
			$this->data['config_blog_download'] = $this->config->get('config_blog_download');
		}
		
				
		$this->load->model('tool/image');

		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->post['config_blog_image_category_width'])) {
			$this->data['config_blog_image_category_width'] = $this->request->post['config_blog_image_category_width'];
		} else {
			$this->data['config_blog_image_category_width'] = $this->config->get('config_blog_image_category_width');
		}
		
		if (isset($this->request->post['config_blog_image_category_height'])) {
			$this->data['config_blog_image_category_height'] = $this->request->post['config_blog_image_category_height'];
		} else {
			$this->data['config_blog_image_category_height'] = $this->config->get('config_blog_image_category_height');
		}
		
		if (isset($this->request->post['config_blog_image_popup_width'])) {
			$this->data['config_blog_image_popup_width'] = $this->request->post['config_blog_image_popup_width'];
		} else {
			$this->data['config_blog_image_popup_width'] = $this->config->get('config_blog_image_popup_width');
		}
		
		if (isset($this->request->post['config_blog_image_popup_height'])) {
			$this->data['config_blog_image_popup_height'] = $this->request->post['config_blog_image_popup_height'];
		} else {
			$this->data['config_blog_image_popup_height'] = $this->config->get('config_blog_image_popup_height');
		}
		
		if (isset($this->request->post['config_blog_image_article_width'])) {
			$this->data['config_blog_image_article_width'] = $this->request->post['config_blog_image_article_width'];
		} else {
			$this->data['config_blog_image_article_width'] = $this->config->get('config_blog_image_article_width');
		}
		
		if (isset($this->request->post['config_blog_image_article_height'])) {
			$this->data['config_blog_image_article_height'] = $this->request->post['config_blog_image_article_height'];
		} else {
			$this->data['config_blog_image_article_height'] = $this->config->get('config_blog_image_article_height');
		}
		
		if (isset($this->request->post['config_blog_image_gallery_width'])) {
			$this->data['config_blog_image_gallery_width'] = $this->request->post['config_blog_image_gallery_width'];
		} else {
			$this->data['config_blog_image_gallery_width'] = $this->config->get('config_blog_image_gallery_width');
		}
		
		if (isset($this->request->post['config_blog_image_gallery_height'])) {
			$this->data['config_blog_image_gallery_height'] = $this->request->post['config_blog_image_gallery_height'];
		} else {
			$this->data['config_blog_image_gallery_height'] = $this->config->get('config_blog_image_gallery_height');
		}

		if (isset($this->request->post['config_blog_image_additional_width'])) {
			$this->data['config_blog_image_additional_width'] = $this->request->post['config_blog_image_additional_width'];
		} else {
			$this->data['config_blog_image_additional_width'] = $this->config->get('config_blog_image_additional_width');
		}
		
		if (isset($this->request->post['config_blog_image_additional_height'])) {
			$this->data['config_blog_image_additional_height'] = $this->request->post['config_blog_image_additional_height'];
		} else {
			$this->data['config_blog_image_additional_height'] = $this->config->get('config_blog_image_additional_height');
		}
		
		if (isset($this->request->post['config_blog_image_related_width'])) {
			$this->data['config_blog_image_related_width'] = $this->request->post['config_blog_image_related_width'];
		} else {
			$this->data['config_blog_image_related_width'] = $this->config->get('config_blog_image_related_width');
		}
		
		if (isset($this->request->post['config_blog_image_related_height'])) {
			$this->data['config_blog_image_related_height'] = $this->request->post['config_blog_image_related_height'];
		} else {
			$this->data['config_blog_image_related_height'] = $this->config->get('config_blog_image_related_height');
		}

		$this->template = 'blog/blog_setting.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

								
		if (!$this->request->post['config_blog_image_category_width'] || !$this->request->post['config_blog_image_category_height']) {
			$this->error['blog_image_category'] = $this->language->get('error_blog_image_category');
		} 
		
		if (!$this->request->post['config_blog_image_article_width'] || !$this->request->post['config_blog_image_article_height']) {
			$this->error['blog_image_article'] = $this->language->get('error_blog_image_article');
		}
		
		if (!$this->request->post['config_blog_image_gallery_width'] || !$this->request->post['config_blog_image_gallery_height']) {
			$this->error['blog_image_gallery'] = $this->language->get('error_blog_image_gallery');
		}
				
		if (!$this->request->post['config_blog_image_additional_width'] || !$this->request->post['config_blog_image_additional_height']) {
			$this->error['blog_image_additional'] = $this->language->get('error_blog_image_additional');
		}
		
		if (!$this->request->post['config_blog_image_related_width'] || !$this->request->post['config_blog_image_related_height']) {
			$this->error['blog_image_related'] = $this->language->get('error_blog_image_related');
		}
		
		if (!$this->request->post['config_blog_admin_limit']) {
			$this->error['blog_admin_limit'] = $this->language->get('error_limit');
		}
		
		if (!$this->request->post['config_blog_catalog_limit']) {
			$this->error['blog_catalog_limit'] = $this->language->get('error_limit');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
			
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
}
?>