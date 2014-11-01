<?php
class ControllerModuleTestimonial extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/testimonial');

		$this->document->SetTitle( $this->language->get('heading_title'));

		$this->data['testimonial_version'] = "1.5.4 (OpenCart 1.5.x)";
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('testimonial', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_bedwords'] = $this->language->get('entry_bedwords');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['text_required_field'] = $this->language->get('text_required_field');
		$this->data['text_help'] = $this->language->get('text_help');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_edit_testimonials'] = $this->language->get('text_edit_testimonials');

		$this->data['tab_module'] = $this->language->get('tab_module');

		if (isset($this->request->post['testimonial_title'])) {
			$this->data['testimonial_title'] = $this->request->post['testimonial_title'];
		} else {
			$this->data['testimonial_title'] = $this->config->get('testimonial_title');
		}

		if (isset($this->request->post['testimonial_module'])) {
			$this->data['modules'] = $this->request->post['testimonial_module'];
		} elseif ($this->config->get('testimonial_module')) { 
			$this->data['modules'] = $this->config->get('testimonial_module');
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_character_limit'] = $this->language->get('entry_character_limit');
		$this->data['entry_admin_approved'] = $this->language->get('entry_admin_approved');
		$this->data['entry_default_rating'] = $this->language->get('entry_default_rating');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_bad'] = $this->language->get('entry_bad');
		$this->data['entry_random'] = $this->language->get('entry_random');
		$this->data['entry_send_to_admin'] = $this->language->get('entry_send_to_admin');
		$this->data['entry_all_page_limit'] = $this->language->get('entry_all_page_limit');


		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_badwords'] = $this->language->get('entry_badwords');
		$this->data['entry_blockedip'] = $this->language->get('entry_blockedip');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['token'] = $this->session->data['token'];

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/testimonial', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		

		$this->data['action'] = $this->url->link('module/testimonial', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['edit_testimonials_path'] = $this->url->link('catalog/testimonial', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['testimonial_module'])) {
			$this->data['modules'] = $this->request->post['testimonial_module'];
		} elseif ($this->config->get('testimonial_module')) { 
			$this->data['modules'] = $this->config->get('testimonial_module');
		}

		if (isset($this->request->post['testimonial_admin_approved'])) {
			$this->data['testimonial_admin_approved'] = $this->request->post['testimonial_admin_approved'];
		} else {
			$this->data['testimonial_admin_approved'] = $this->config->get('testimonial_admin_approved');
		}

		if (isset($this->request->post['testimonial_send_to_admin'])) {
			$this->data['testimonial_send_to_admin'] = $this->request->post['testimonial_send_to_admin'];
		} else {
			$this->data['testimonial_send_to_admin'] = $this->config->get('testimonial_send_to_admin');
		}

		if (isset($this->request->post['testimonial_all_page_limit'])) {
			$this->data['testimonial_all_page_limit'] = $this->request->post['testimonial_all_page_limit'];
		} else {
			$this->data['testimonial_all_page_limit'] = $this->config->get('testimonial_all_page_limit');
		}


		if (isset($this->request->post['testimonial_default_rating'])) {
			$this->data['testimonial_default_rating'] = $this->request->post['testimonial_default_rating'];
		} else {
			$this->data['testimonial_default_rating'] = $this->config->get('testimonial_default_rating');
		}


		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();


		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();


		$this->template = 'module/testimonial.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		//$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/testimonial')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}


	public function install() {
		$this->load->model('catalog/testimonial');
		$this->model_catalog_testimonial->createDatabaseTables();
	}

	public function uninstall() {

		$this->load->model('catalog/testimonial');
		$this->model_catalog_testimonial->dropDatabaseTables();
	}
}
?>