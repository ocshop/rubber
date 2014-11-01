<?php
class ControllerShippingXshipping extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/xshipping');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('xshipping', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['tab_rate'] = $this->language->get('tab_rate');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_free'] = $this->language->get('entry_free');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_cost'] = $this->language->get('entry_cost');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/xshipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/xshipping', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		
		for($i=1;$i<=12;$i++)
		 {
				if (isset($this->request->post['xshipping_cost'.$i])) {
					$this->data['xshipping_cost'.$i] = $this->request->post['xshipping_cost'.$i];
				} else {
					$this->data['xshipping_cost'.$i] = $this->config->get('xshipping_cost'.$i);
				}
				
				if (isset($this->request->post['xshipping_name'.$i])) {
					$this->data['xshipping_name'.$i] = $this->request->post['xshipping_name'.$i];
				} else {
					$this->data['xshipping_name'.$i] = $this->config->get('xshipping_name'.$i);
				}
				
				if (isset($this->request->post['xshipping_free'.$i])) {
					$this->data['xshipping_free'.$i] = $this->request->post['xshipping_free'.$i];
				} else {
					$this->data['xshipping_free'.$i] = $this->config->get('xshipping_free'.$i);
				}
		
				if (isset($this->request->post['xshipping_tax_class_id'.$i])) {
					$this->data['xshipping_tax_class_id'.$i] = $this->request->post['xshipping_tax_class_id'.$i];
				} else {
					$this->data['xshipping_tax_class_id'.$i] = $this->config->get('xshipping_tax_class_id'.$i);
				}
		
				if (isset($this->request->post['xshipping_geo_zone_id'.$i])) {
					$this->data['xshipping_geo_zone_id'.$i] = $this->request->post['xshipping_geo_zone_id'.$i];
				} else {
					$this->data['xshipping_geo_zone_id'.$i] = $this->config->get('xshipping_geo_zone_id'.$i);
				}
				
				if (isset($this->request->post['xshipping_status'.$i])) {
					$this->data['xshipping_status'.$i] = $this->request->post['xshipping_status'.$i];
				} else {
					$this->data['xshipping_status'.$i] = $this->config->get('xshipping_status'.$i);
				}
				
				if (isset($this->request->post['xshipping_sort_order'.$i])) {
					$this->data['xshipping_sort_order'.$i] = $this->request->post['xshipping_sort_order'.$i];
				} else {
					$this->data['xshipping_sort_order'.$i] = $this->config->get('xshipping_sort_order'.$i);
				}
		 }	
		 
		 if (isset($this->request->post['xshipping_status'])) {
					$this->data['xshipping_status'] = $this->request->post['xshipping_status'];
				} else {
					$this->data['xshipping_status'] = $this->config->get('xshipping_status');
				}
		if (isset($this->request->post['xshipping_sort_order'])) {
					$this->data['xshipping_sort_order'] = $this->request->post['xshipping_sort_order'];
				} else {
					$this->data['xshipping_sort_order'] = $this->config->get('xshipping_sort_order');
				}						

		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
								
		$this->template = 'shipping/xshipping.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/xshipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>