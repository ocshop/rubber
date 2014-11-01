<?php
class ControllerCatalogCoolfilter extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/coolfilter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/coolfilter');
        $this->data['language_id'] = $this->config->get('config_language_id');
		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/coolfilter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/coolfilter');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		  $this->model_catalog_coolfilter->addOption($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/coolfilter&token=' . $this->session->data['token']);
		}
		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/coolfilter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/coolfilter');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_coolfilter->editOption($this->request->get['option_id'], $this->request->post);
 			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/coolfilter&token=' . $this->session->data['token']);
		}
		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/coolfilter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/coolfilter');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $option_id) {
				$this->model_catalog_coolfilter->deleteOption($option_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/coolfilter&token=' . $this->session->data['token']);
		}
		$this->getList();
	}

	private function getList() {

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/coolfilter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=catalog/coolfilter/insert&token=' . $this->session->data['token'];
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=catalog/coolfilter/delete&token=' . $this->session->data['token'];

    $this->data['options'] = array();

		$results = $this->model_catalog_coolfilter->getOptions();
		
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/coolfilter/update&token=' . $this->session->data['token'] . '&option_id=' . $result['option_id']
			);
			
			$this->data['options'][] = array(
				'option_id'     => $result['option_id'],
				'name'          => $result['name'],
				'style'			=> $this->getStyleName($result['style']),
				'type'			=> $this->getTypeName($result['type']),
				'coolfilter_group'	=> $result['coolfilter_group'],
				'sort_order'    => $result['sort_order'],
				'selected'      => isset($this->request->post['selected']) && in_array($result['option_id'], $this->request->post['selected']),
				'status'        => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'action'        => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_style'] = $this->language->get('column_style');
		$this->data['column_coolfilter_groups'] = $this->language->get('column_coolfilter_groups');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['button_fast_edit'] = $this->language->get('button_fast_edit');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->template = 'catalog/coolfilter_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->document->setTitle($this->language->get('heading_title'));
        $this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_delete'] = $this->language->get('text_delete');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_add_value'] = $this->language->get('text_add_value');
		$this->data['text_value'] = $this->language->get('text_value');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		
		$this->data['entry_style'] = $this->language->get('entry_style');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_values'] = $this->language->get('entry_values');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_type'] = $this->language->get('entry_type');
        $this->data['entry_style'] = $this->language->get('entry_style');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['entry_coolfilter_groups'] = $this->language->get('entry_coolfilter_groups');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

        $this->data['tab_general'] = $this->language->get('tab_general');
        $this->data['tab_data'] = $this->language->get('tab_data');
		
		$this->data['text_prices'] = $this->language->get('text_prices');
		$this->data['text_manufacters'] = $this->language->get('text_manufacters');
		$this->data['text_options'] = $this->language->get('text_options');
		$this->data['text_attributes'] = $this->language->get('text_attributes');
		$this->data['text_parameteres'] = $this->language->get('text_parameteres');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}


		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/coolfilter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['option_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/coolfilter/insert&token=' . $this->session->data['token'];
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/coolfilter/update&token=' . $this->session->data['token'] . '&option_id=' . $this->request->get['option_id'];
		}

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=catalog/coolfilter&token=' . $this->session->data['token'];

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();


    if (isset($this->request->get['option_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      $option_info = $this->model_catalog_coolfilter->getOption($this->request->get['option_id']);
    }

    if (isset($this->request->post['name'])) {
      $this->data['name'] = $this->request->post['name'];
    } elseif (isset($option_info['option_id'])) {
      $this->data['name'] = $this->model_catalog_coolfilter->getOptionDescriptions($option_info['option_id']);
    } else {
      $this->data['name'] = array();
    }
	
	
    if (isset($this->request->post['types'])) {
      $this->data['option_types'] = $this->request->post['types'];
    } elseif (isset($option_info['option_id'])) {
      $this->data['option_types'] = $this->model_catalog_coolfilter->getOptionTypes($this->request->get['option_id']);
    } else {
      $this->data['option_types'] = array();
    }
	
    if (isset($this->request->post['styles'])) {
      $this->data['option_styles'] = $this->request->post['style'];
    } elseif (isset($option_info)) {
      $this->data['option_styles'] = $this->model_catalog_coolfilter->getOptionStyles($this->request->get['option_id']);
    } else {
		$this->data['option_styles'] = array();
	}
	
    if (isset($this->request->post['sort_order'])) {
      $this->data['sort_order'] = $this->request->post['sort_order'];
    } elseif (isset($option_info['sort_order'])) {
      $this->data['sort_order'] = $option_info['sort_order'];
    } else {
      $this->data['sort_order'] = 0;
    }

    if (isset($this->request->post['status'])) {
      $this->data['status'] = $this->request->post['status'];
    } elseif (isset($option_info['status'])) {
      $this->data['status'] = $option_info['status'];
    } else {
      $this->data['status'] = 1;
    }

	
	$this->load->model('catalog/coolfilter_group');
	$this->data['coolfilter_groups'] = $this->model_catalog_coolfilter_group->getcoolfilterGroups();
	
	if (isset($this->request->post['option_coolfilter_group'])) {
      $this->data['option_coolfilter_group'] = $this->request->post['option_coolfilter_group'];
    } elseif (isset($option_info)) {
      $this->data['option_coolfilter_group'] = $this->model_catalog_coolfilter->getOptioncoolfilterGroups($this->request->get['option_id']);
    } else {
      $this->data['option_coolfilter_group'] = array();
    }
	
	$this->data['type_groups'][] = array('value' => 'prices',
										 'text'  => 'text_prices');
	$this->data['type_groups'][] = array('value' => 'manufacters',
										 'text'  => 'text_manufacters');  
	$this->data['type_groups'][] = array('value' => 'options',
										 'text'  => 'text_options');	
	$this->data['type_groups'][] = array('value' => 'attributes',
										 'text'  => 'text_attributes');	
	$this->data['type_groups'][] = array('value' => 'parameteres',
										 'text'  => 'text_parameteres');											 
									   
	
	$this->data['types'] = $this->getTypes();						  
    $this->data['styles'] = $this->getStyles();
	unset($this->data['styles']['slider']);

		$this->template = 'catalog/coolfilter_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	
	private function getStyleName($index)
	{
		
		$styles_list = $this->getStyles();
		
		return $styles_list[$index];
	
	}
	
	
	private function getStyles()
	{
		$styles['slider'] = $this->language->get('style_slider');
		$styles['list'] = $this->language->get('style_list');
		$styles['checkbox'] = $this->language->get('style_checkbox');
		$styles['image'] = $this->language->get('style_images');
		$styles['select'] = $this->language->get('style_selected');
		return $styles;
  
	}
	
	private function getTypeName($index)
	{
		
		$key = explode("_", $index);
		$key_name = $key[0].'s';
		$type_list = $this->getTypes();
		
		return $type_list[$key_name][$index]['value'];
	
	}
	
	 private function getTypes()
	 {

		$types['prices']['price'] = array('value' => $this->language->get('text_prices'));
		
		$types['manufacters']['manufacter'] = array('value' => $this->language->get('text_manufacters'));

		$typesOptions['options'] = array();
		$typesOptions['options'] = $this->model_catalog_coolfilter->getOptionsForTypes();
		$types =  array_merge_recursive($types, $typesOptions);
		
		$typesAttributes['attributes'] = array();
		$typesAttributes['attributes'] = $this->model_catalog_coolfilter->getAttributesForTypes();
		$types =  array_merge_recursive($types, $typesAttributes);
		
		
		$typesParameteres['parameteres'] = array();
		$typesParameteres['parameteres'] = $this->model_catalog_coolfilter->getParameteresForTypes();
		$types =  array_merge_recursive($types, $typesParameteres);
		
		//$types['parameteres']['parametere'] = array('value' => $this->language->get('text_parameteres'));
		
		return $types;
	  
	 }
	 

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/coolfilter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/coolfilter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>