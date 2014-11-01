<?php 
class ControllerProductReviews extends Controller { 	
	public function index() { 
    	$this->language->load('product/reviews');
		
		$this->load->model('catalog/product');

		$this->load->model('catalog/reviews');
		
		$this->load->model('tool/image');
		
  		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$limit = $this->config->get('config_catalog_limit');
				    	
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);

		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
					
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/reviews', $url),
      		'separator' => $this->language->get('text_separator')
   		);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
   
		$this->data['text_empty'] = $this->language->get('text_empty');

		$this->data['reviews'] = array();

		$reviews_total = $this->model_catalog_reviews->getTotalReviews();
			
		$results = $this->model_catalog_reviews->getReviews(($page - 1) * $limit, $limit);
			
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
       				$prod_thumb = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				}
				$product_id = $product['product_id'];
    			$prod_name = $product['name'];
    			$prod_model = $product['model'];
    			$prod_href = $this->url->link('product/product', 'product_id=' . $product['product_id']);
				$stickers = $this->getStickers($product['product_id']) ;
			}

			$this->data['reviews'][] = array(
				'review_id'   => $result['review_id'],
				'rating'      => $rating,
                'description' => $result['text'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'author'      => $result['author'],
				'product_id'  => $product_id,
  				'prod_thumb'  => $prod_thumb,
  				'prod_name'   => $prod_name,
				'sticker'     => $stickers,
  				'prod_model'  => $prod_model,
  				'prod_href'   => $prod_href
			);
		}

		$pagination = new Pagination();
		$pagination->total = $reviews_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/reviews', '&page={page}');
			
		$this->data['pagination'] = $pagination->render();
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/reviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/reviews.tpl';
		} else {
			$this->template = 'default/template/product/reviews.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
	
		$this->response->setOutput($this->render());			
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