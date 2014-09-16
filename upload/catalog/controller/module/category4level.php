<?php  
class ControllerModuleCategory4level extends Controller {
	protected function index($setting) {
		$this->language->load('module/category4level');

		$this->data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
		
		if (isset($parts[2])) {
			$this->data['child_id2'] = $parts[2];
		} else {
			$this->data['child_id2'] = 0;
		}		
		
		if (isset($parts[3])) {
			$this->data['child_id3'] = $parts[3];
		} else {
			$this->data['child_id3'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			$total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $category['category_id']));

			$children_data = array();

			$children = $this->model_catalog_category->getCategories($category['category_id']);

			foreach ($children as $child) {
			
			$children2_data = array();
			$children2 = $this->model_catalog_category->getCategories($child['category_id']);
			
				foreach ($children2 as $child2) {
				
					$children3_data = array();
					$children3 = $this->model_catalog_category->getCategories($child2['category_id']);
					
						foreach ($children3 as $child3) {
						
							$data3 = array(
								'filter_category_id'  => $child3['category_id'],
								'filter_sub_category' => true
							);				
							
							$product_total3 = $this->model_catalog_product->getTotalProducts($data3);

							

							$children3_data[] = array(
								'category_id' => $child3['category_id'],
								'name'        => $child3['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total3 . ')' : ''),
								'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']. '_' . $child2['category_id']. '_' . $child3['category_id'])	
							);		
						
						
						}
					
				
				
					$data2 = array(
						'filter_category_id'  => $child2['category_id'],
						'filter_sub_category' => true
					);				
					
					$product_total2 = $this->model_catalog_product->getTotalProducts($data2);

					

					$children2_data[] = array(
						'category_id' => $child2['category_id'],
						'name'        => $child2['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total2 . ')' : ''),
						'children3'    => $children3_data,
						'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']. '_' . $child2['category_id'])	
					);		
				
				
				}
			
			
				$data = array(
					'filter_category_id'  => $child['category_id'],
					'filter_sub_category' => true
				);

				$product_total = $this->model_catalog_product->getTotalProducts($data);

				$total += $product_total;

				$children_data[] = array(
					'category_id' => $child['category_id'],
					'name'        => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
					'children2'    => $children2_data,
					'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
				);		
			}

			$this->data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $total . ')' : ''),
				'children'    => $children_data,
				'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
			);	
		}
		//print_r ($this->data['categories']);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category4level.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category4level.tpl';
		} else {
			$this->template = 'default/template/module/category4level.tpl';
		}

		$this->render();
	}
}
?>