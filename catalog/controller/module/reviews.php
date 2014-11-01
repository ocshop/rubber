<?php
class ControllerModuleReviews extends Controller {
	protected function index($setting) {
		$this->language->load('module/reviews');

		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		
		$this->load->model('module/reviews');

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
			$results = $this->model_module_reviews->getLatestReviews($limit);
		} else {
			$results = $this->model_module_reviews->getRandomReviews($limit);
		}

		foreach ($results as $result) {
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

   			$product_id = false;
   			$product = false;
   			$prod_thumb = false;
   			$prod_name = false;
   			$prod_model = false;
   			$prod_href = false;
			$stickers = false;
			
			if ($result['product_id']) {
				$product = $this->model_catalog_product->getProduct($result['product_id']);
				if ($product['image']) {
       				$prod_thumb = $this->model_tool_image->resize($product['image'], $setting['image_width'], $setting['image_height']);
				}
				$product_id = $product['product_id'];
    			$prod_name = $product['name'];
    			$prod_model = $product['model'];
    			$prod_href = $this->url->link('product/product', 'product_id=' . $product['product_id']);
				$stickers = $this->getStickers($product['product_id']) ;
			}

			$this->data['reviews'][] = array(
				'review_id'   => $result['review_id'],
				'rating'      => $result['rating'],
                'description' => mb_substr($result['text'], 0, $text_limit,'utf-8') . '..',
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'        => $this->url->link('product/product', 'product_id=' . $product['product_id']),
				'author'      => $result['author'],
				'product_id'  => $product_id,
  				'prod_thumb'  => $prod_thumb,
  				'prod_name'   => $prod_name,
				'sticker'     => $stickers,
  				'prod_model'  => $prod_model,
  				'prod_href'   => $prod_href
			);
		}

		$this->data['link_all_reviews'] = $this->url->link('product/reviews', 'page=1');
		$this->data['text_all_reviews'] = $this->language->get('text_all_reviews');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/reviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/reviews.tpl';
		} else {
			$this->template = 'default/template/module/reviews.tpl';
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