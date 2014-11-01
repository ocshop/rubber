<?php 
class ControllerProducttestimonial extends Controller {
	
	public function index() {  
    	$this->language->load('product/testimonial');
		
		$this->load->model('catalog/testimonial');

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', '', 'SSL'),
      		'separator' => false
   		);
		
		$url = '';
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				$url .= '&page=' . $this->request->get['page'];
			} else { 
				$page = 1;
		}	
		
		$filter = '';
		
		$this->data['active'] = 'all';
		
		$this->data['single'] = 1;
		
		$testimonial_total = 1;
		
		$testimonial_total = $this->model_catalog_testimonial->getTotalTestimonials();
		$this->data['total'] = $testimonial_total;
		
		if (!isset($this->request->get['testimonial_id']) ){
			$this->data['total_good'] = $this->model_catalog_testimonial->getTotalTestimonials('good');
			$this->data['total_bad'] = $this->model_catalog_testimonial->getTotalTestimonials('bad');
			$this->data['single'] = 0;
		}
		
		if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
				if ($this->request->get['filter'] == 'good') {
					$this->data['active'] = 'good';
					$testimonial_total = $this->data['total_good'];
				} elseif ($this->request->get['filter'] == 'bad') {
					$this->data['active'] = 'bad';
					$testimonial_total = $this->data['total_bad'];
				};
				
				$filter = $this->request->get['filter'];
				
		}
		

	  		$this->document->SetTitle ($this->language->get('heading_title'));

	   		$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('product/testimonial', '', 'SSL'),
	      		'separator' => $this->language->get('text_separator')
	   		);

						
      		$this->data['heading_title'] = $this->language->get('heading_title');
      		$this->data['text_auteur'] = $this->language->get('text_auteur');
      		$this->data['text_city'] = $this->language->get('text_city');
      		$this->data['button_continue'] = $this->language->get('button_continue');
      		$this->data['showall'] = $this->language->get('text_showall');
      		$this->data['write'] = $this->language->get('text_write');
      		$this->data['text_average'] = $this->language->get('text_average');
      		$this->data['text_stars'] = $this->language->get('text_stars');
      		$this->data['text_no_rating'] = $this->language->get('text_no_rating');
			
			$this->data['text_rating_all'] = $this->language->get('text_rating_all');
			$this->data['text_rating_good'] = $this->language->get('text_rating_good');
			$this->data['text_rating_bad'] = $this->language->get('text_rating_bad');
			
			$this->data['continue'] = $this->url->link('common/home', '', 'SSL');
			
			$this->data['showall_url'] = $this->url->link('product/testimonial'); 	
			$this->data['good'] = $this->url->link('product/testimonial', '&filter=good'); 	
			$this->data['bad'] = $this->url->link('product/testimonial', '&filter=bad'); 

			$this->page_limit = $this->config->get('testimonial_all_page_limit');

			$this->data['testimonials'] = array();
			
			if ( isset($this->request->get['testimonial_id']) ){
				$results = $this->model_catalog_testimonial->getTestimonial($this->request->get['testimonial_id']);
			}
			else{
				$results = $this->model_catalog_testimonial->getTestimonials(($page - 1) * $this->page_limit, $this->page_limit, false, $filter);
			}
			
			foreach ($results as $result) {
				
				$this->data['testimonials'][] = array(
					'name'			=> $result['name'],
					'title'    		=> $result['title'],
					'rating'		=> $result['rating'],
					'description'	=> $result['description'],
					'city'			=> $result['city'],
					'date_added'	=> date("H:i:s m-d-Y", strtotime($result['date_added'])) 
				);
			}
			
				$this->data['write_url'] = $this->url->link('product/isitestimonial', '', 'SSL'); 	
			
			if ( isset($this->request->get['testimonial_id']) ){
				$this->data['showall_url'] = $this->url->link('product/testimonial', '', 'SSL'); 	
			}
			else{
				$pagination = new Pagination();
				$pagination->total = $testimonial_total;
				$pagination->page = $page;
				$pagination->limit = $this->page_limit; 
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('product/testimonial', '&page={page}', 'SSL');
				$this->data['pagination'] = $pagination->render();				

			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/testimonial.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/testimonial.tpl';
			} else {
				$this->template = 'default/template/product/testimonial.tpl';
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
}
?>