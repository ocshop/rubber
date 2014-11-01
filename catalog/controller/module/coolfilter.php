<?php
class ControllerModuleCoolfilter extends Controller {
	protected function index($setting) {
		$this->language->load('module/coolfilter');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_apply'] = $this->language->get('text_apply');
        $this->data['text_reset_coolfilter'] = $this->language->get('text_reset_coolfilter');
		$this->data['count_enabled'] = $this->config->get('count_enabled');
		$this->data['currency_symbol_left'] = $this->currency->getSymbolLeft($this->currency->getCode());
		$this->data['currency_symbol_right'] = $this->currency->getSymbolRight($this->currency->getCode());
		$this->data['count_symbols'] = $this->currency->getDecimalPlace($this->currency->getCode());
		
		$this->load->model('catalog/coolfilter');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/coolfilter.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/coolfilter.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/coolfilter.css');
		}
		
		// Получить id группы фильтра
		$coolfilter_group_id = $setting['coolfilter_group_id'];
		
		// Получить id категории
		if (isset($this->request->get['path'])) {
			$parts = explode('_', $this->request->get['path']);
			$categorie_id = end($parts);
		} 
		else {
			$categorie_id = 0;
		}
		
		// Получить все фильтры данной группы для данной категории
		$coolfilter_group_options = $this->model_catalog_coolfilter->getOptionBycoolfilterGroupsId($coolfilter_group_id, $categorie_id);
		
		
	

		if (!empty($coolfilter_group_options)) {
		
			// Выбрать уникальные типы фильтров
			// Для уменьшения количества циклов, в цикл добвалена реструктуризация ключей массива фильтров по индекску
			$coolfilter_types = array();
			$coolfilter_group_options_by_index = array();
		
			foreach ($coolfilter_group_options as $coolfilter_group_option) {
				$coolfilter_types_parts = explode('_', $coolfilter_group_option['type_index']);
				$coolfilter_types[reset($coolfilter_types_parts)][$coolfilter_group_option['type_index']] = end($coolfilter_types_parts);
				// Реструктуризация
				$coolfilter_group_options_by_index[$coolfilter_group_option['type_index']] = $coolfilter_group_option;
			}
			
				
			
			$categories_id[] = $categorie_id; 
			$categories_id = array_merge_recursive($categories_id, $this->model_catalog_coolfilter->getChildrenCategorie($categorie_id));
			
		
			// Получить соотвествия для фильтров в данной категории	
			$coolfilterItemNames = $this->getcoolfilterItemNames($coolfilter_types, $categories_id);
			
	
			
			// Передача данных фильтра в представление
			$this->data['coolfilters'] = array_merge_recursive($coolfilter_group_options_by_index, $coolfilterItemNames);
			
			
			
			// Сортировка данных фильтра
			uasort($this->data['coolfilters'], array('ControllerModulecoolfilter', 'sortcoolfilters'));
			
				
		

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/coolfilter.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/coolfilter.tpl';
			} 
			else {
				$this->template = 'default/template/module/coolfilter.tpl';
			}
				
			$this->render();
		}
	}

	// Сортировка фильтров по значению sort_order, указанному в системе
	static private function sortcoolfilters($array_first, $array_second) {

	if ($array_first['sort_order'] == $array_second['sort_order']) {

		return 0; 
	}
	

	return ($array_first['sort_order'] < $array_second['sort_order']) ? -1 : 1; 

	}
	
	private function getUrl() {
		
		// Получение переменных GET запроса, для формирования ссылки на фильтр
		$url = '';

		if (isset($this->request->get['sort'])) {
		  $url .= '&sort=' . htmlspecialchars($this->request->get['sort']);
		}

		if (isset($this->request->get['order'])) {
		  $url .= '&order=' . htmlspecialchars($this->request->get['order']);
		}
		
		if (isset($this->request->get['limit'])) {
		  $url .= '&limit=' . (int)$this->request->get['limit'];
		}
		
	
	}

	// Получение имен всех фильтров для данной категории
	private function getcoolfilterItemNames($coolfilter_types, $categories_id) {
		
		$coolfilterItemNames = array();
		$categories_id_to_string = implode(",", $categories_id);
		
			
		
		if (isset($coolfilter_types['price'])) {
			$price_data = $this->getDataForcoolfilter($coolfilter_types['price'], $categories_id_to_string, 'price');
			
			$price_data['price']['coolfilters'][0]['value'] = floor($this->currency->format($price_data['price']['coolfilters'][0]['value'], '', '', false));
			$price_data['price']['coolfilters'][1]['value'] = ceil($this->currency->format($price_data['price']['coolfilters'][1]['value'], '', '', false));
			
			$coolfilterItemNames += $price_data;
			
		}
		
		if (isset($coolfilter_types['manufacter'])) {
			$coolfilterItemNames += $this->getDataForcoolfilter($coolfilter_types['manufacter'], $categories_id_to_string, 'manufacter');
		}
		
		if (isset($coolfilter_types['option'])) {
			$coolfilterItemNames += $this->getDataForcoolfilter($coolfilter_types['option'], $categories_id_to_string, 'option');
		}
		
		if (isset($coolfilter_types['attribute'])) {
			$coolfilterItemNames += $this->getDataForcoolfilter($coolfilter_types['attribute'], $categories_id_to_string, 'attribute');
		}
		
		if (isset($coolfilter_types['parametere'])) {
			$coolfilterItemNames += $this->getDataForcoolfilter($coolfilter_types['parametere'], $categories_id_to_string, 'parametere');
			
		}
		
		return $coolfilterItemNames; 

	}
	
	private function getDataForcoolfilter($coolfilter_types, $categories_id_to_string, $type) {

		$coolfilterItemNames = array();
		$url = $this->getUrl();
		
	
		
		$no_image = $this->model_tool_image->resize('no_image.jpg', 20, 20);
		$method_name = 'get' . ucfirst($type) . 'ItemNames';
		
		$item_names = array();
		
		
		if ($type == 'option' || $type == 'attribute' || $type == 'parametere') {
			$coolfilter_options_to_string = implode(",", $coolfilter_types);
			$item_names = $this->model_catalog_coolfilter->$method_name($coolfilter_options_to_string, $categories_id_to_string);
		} else {
			$item_names = $this->model_catalog_coolfilter->$method_name($categories_id_to_string);
		}
		

		
		foreach ($item_names as $item_name) {
			
			if ($type == 'option' || $type == 'attribute' || $type == 'parametere') {
				$index = $type . '_' . $item_name['id'];
				$option_key = mb_substr($type, 0, 1) . '_' . $item_name['id'];
			} else {
				$index = $type;
				$option_key = mb_substr($type, 0, 1);
			}
			
		
			
			$data = $this->getDataForcoolfilterValue($url, $option_key, $item_name['value']);
			
			
			if (isset($item_name['image']) && !empty($item_name['image'])) {
				$image = $this->model_tool_image->resize($item_name['image'], 20, 20);
			} else {
				$image = $no_image;
			}
			
				
			$coolfilterItemNames[$index]['coolfilters'][] = array('name' => $item_name['name'],
				'href'       => $data['href'],
				'value'		 => $item_name['value'],
				'key'		 => $option_key,
				'active'     => $data['active'],
				'count'      => $data['count'],
				'view_count' => ($data['count'] != '') ? '(' . $data['count'] . ')' : '',
				'image'      => $image );
		}
	
	
		
		return $coolfilterItemNames;
	
	}
	
	private function getDataForcoolfilterValue($url, $option_key, $option_value) {
	
		$href = '';
		if(isset($this->request->get['path']))
			$path = htmlspecialchars($this->request->get['path']);
		else
			$path = 0;
		$href .= 'path=' . $path;
		
		$get_coolfilter = '';
		if (isset($this->request->get['coolfilter']))
			$get_coolfilter = htmlspecialchars($this->request->get['coolfilter']);
		
		$coolfilter = $this->getcoolfilterURLParams($get_coolfilter, $option_key, $option_value);
		if(!empty($coolfilter))
			$href .= '&coolfilter=' . $coolfilter;
			
		$href .= $url;
	
		
		$data['href'] = $this->url->link('product/category', $href);
		// Проверка выбран ли фильтр
		$data['active'] = (strlen($get_coolfilter) > strlen($coolfilter)) ? true : false;
		
		$categories_id = explode("_", $path);
		
		$data_for_query['filter_category_id'] = end($categories_id);
		
		
		// Количество товаров в категории для фильтра
		$data['count'] = '';
		
		if ($this->config->get('count_enabled')) {
		
			if (!$data['active']) {
				$coolfilter_count = preg_replace('/(' . $option_key . ':)([^;]+)/i', '${1}' . $option_value, $coolfilter);
				$data_for_query['coolfilter'] = $coolfilter_count;
				$data['count'] = $this->model_catalog_product->getTotalProducts($data_for_query);
			}
			else {
				$coolfilter_count = preg_replace('/(' . $option_key . ':)([^;]+)/i', '${1}' . $option_value, $get_coolfilter);
				$data_for_query['coolfilter'] = $coolfilter_count;
				$data['count'] = $this->model_catalog_product->getTotalProducts($data_for_query);
			
			}
		
		}


		return $data;

	}


	// Получение уже существующих параметров фильтра
	private function getcoolfilterURLParams($coolfilter = 0, $option_key, $option_value) {
			
	
		$sep_par = ';'; // разделитель пар опций -> значений: opt1:val1,val2,val3;opt2:val1,val2,val3 ...
		$sep_opt = ':'; // разделитель внутри пары опция -> значения: opt1:val1,val2,val3 ...
		$sep_val = ','; // разделитель для параметров опции: val1,val2,val3 ...
		$out = '';
		
		if ($coolfilter) {
		
			$matches = explode($sep_par, $coolfilter);
			$values = array();
			$data = array();
			$options = array();
			
			$checkFliterKey = false;
			
			foreach ($matches as $option) {
				$data = explode($sep_opt, $option);
				if ($data[0] == $option_key) {
					$checkFliterKey = true;
					$values = explode($sep_val, $data[1]);
					if (!in_array($option_value, $values)) { 
						$values[] = $option_value;
					} 
					else {
						unset($values[array_search($option_value, $values)]);
					}
					$data[1] = implode($sep_val, $values);
				}
				if (!empty($data[1]))
					$options[] = implode($sep_opt, $data);
			}
			if (!$checkFliterKey) {
				$options[] = $option_key . $sep_opt . $option_value;
			}
			$out = implode($sep_par, $options);

		}
		else {
			$out .= $option_key . $sep_opt . $option_value; 
		}

		return $out;
	}
}
?>