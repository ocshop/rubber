<?php
class ControllerModuleFeaturedwb extends Controller {
	protected function index($setting) {
		$this->language->load('module/featuredwb');
  		if (isset($this->request->get['path'])) {			$path = '';					$parts = explode('_', (string)$this->request->get['path']);					foreach ($parts as $path_id) {				if (!$path) {					$path = $path_id;				} else {					$path .= '_' . $path_id;				}													$category_info = $this->model_catalog_category->getCategory($path_id);													}							$category_id = array_pop($parts);						$results = $this->model_catalog_product->getProductRelated_by_category($category_id, $setting['limit']);		} else {			$category_id = 0;		} 		//Manufacturer		if (isset($this->request->get['manufacturer_id'])) {			$manufacturer_id = $this->request->get['manufacturer_id'];						$results = $this->model_catalog_product->getProductRelated_by_manufacturer ($manufacturer_id, $setting['limit']);		} else {			$manufacturer_id = 0;		} 																//Manufacturer  				      	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');					

		$this->data['products'] = array();

		if (isset($results)) {
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}
			
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}
					
			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}	
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
			
			$stickers = $this->getStickers($result['product_id']) ;
							
			$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
				'rating'     => $rating,
				'sticker'     => $stickers,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}				}

		if ((file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featuredwb.tpl'))and (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featuredwb_middle.tpl')))     {
			$this->template = $this->config->get('config_template') . '/template/module/featuredwb.tpl';						if (($setting['position']=='content_top') or ($setting['position']=='content_bottom'))  {$this->template = $this->config->get('config_template') . '/template/module/featuredwb_middle.tpl';};					
		} else {
			$this->template = 'default/template/module/featuredwb.tpl';
		}

		$this->render();
	}
	
	private function getStickers($product_id) {
	
 	$stickers = $this->model_catalog_product->getProductStickerbyProductId($product_id) ;	
		
		if (!$stickers) {
			return;
		}
		
		$this->data['stickers'] = array();
		
		foreach ($stickers as $sticker) {
			$this->data['stickers'][] = array(
				'position' => $sticker['position'],
				'image'    => HTTP_SERVER . 'image/' . $sticker['image']
			);		
		}

	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/stickers.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/stickers.tpl';
		} else {
			$this->template = 'default/template/product/stickers.tpl';
		}
	
		return $this->render();
	
	}
}
?>