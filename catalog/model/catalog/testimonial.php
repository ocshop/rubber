<?php
class ModelCatalogTestimonial extends Model {
	public function getTestimonial($testimonial_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "testimonial t LEFT JOIN " . DB_PREFIX . "testimonial_description td ON (t.testimonial_id = td.testimonial_id) WHERE t.testimonial_id = '" . (int)$testimonial_id . "' AND td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1'");
	
		return $query->rows;
	}
	
	public function getTestimonials($start = 0, $limit = 20, $random = false, $filter = false) {
	
	$insert = '';
	
	if ($filter == 'good') {
		$insert = " AND t.rating >= '3' ";
	};
	
	if ($filter == 'bad') {
		$insert = " AND t.rating < '3' ";
	};
				
		
		if ($random == false)
		  $sql = "SELECT * FROM " . DB_PREFIX . "testimonial t LEFT JOIN " . DB_PREFIX . "testimonial_description td ON (t.testimonial_id = td.testimonial_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "'". $insert ." AND t.status = '1' ORDER BY t.date_added DESC LIMIT " . (int)$start . "," . (int)$limit;
		else
		  $sql = "SELECT * FROM " . DB_PREFIX . "testimonial t LEFT JOIN " . DB_PREFIX . "testimonial_description td ON (t.testimonial_id = td.testimonial_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1' ORDER BY RAND() LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalTestimonials($filter = '') {
	
		$insert = '';
		
		if ($filter == 'good') {
			$insert = " AND t.rating >= '3' ";
		};
		
		if ($filter == 'bad') {
			$insert = " AND t.rating < '3' ";
		};
	
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "testimonial t LEFT JOIN " . DB_PREFIX . "testimonial_description td ON (t.testimonial_id = td.testimonial_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1' ". $insert);
			
		return $query->row['total'];
	}
	
	
	public function addTestimonial($data, $status) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "testimonial SET status = '".$status."', rating = '".$this->db->escape($data['rating'])."', name='".$this->db->escape($data['name'])."', city = '".$this->db->escape($data['city'])."', email='".$this->db->escape($data['email'])."', date_added = NOW()");

		$testimonial_id = $this->db->getLastId(); 
		
		$results = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name"); 

		foreach ($results->rows as $result) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "testimonial_description SET testimonial_id = '" . (int)$testimonial_id . "', language_id = '".(int)$result['language_id']."', title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "'");
		}

			
	}
}
?>