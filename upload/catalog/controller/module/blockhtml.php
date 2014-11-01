<?php  
class ControllerModuleBlockHtml extends Controller {
	protected function index($setting) {
		
		$this->data['show_title'] = $setting['header'][$this->config->get('config_language_id')] ? true : false;
    	$this->data['title'] = html_entity_decode($setting['title'][$this->config->get('config_language_id')],  ENT_QUOTES, 'UTF-8');
    	
		$this->data['html'] = html_entity_decode($setting['html'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blockhtml.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/blockhtml.tpl';
		} else {
			$this->template = 'default/template/module/blockhtml.tpl';
		}
		
		$this->render();
	}
}
?>