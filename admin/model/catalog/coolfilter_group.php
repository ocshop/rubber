<?php 
class ModelCatalogCoolfilterGroup extends Model {
	public function addcoolfilterGroup($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coolfilter_group` SET sort_order = '" . (int)$data['sort_order'] . "'");
		
		$coolfilter_group_id = $this->db->getLastId();
		
		foreach ($data['coolfilter_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "coolfilter_group_description` SET coolfilter_group_id = '" . (int)$coolfilter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		if (isset($data['category_id'])) {
			foreach ($data['category_id'] as $category_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "coolfilter_group_to_category` SET coolfilter_group_id = '" . (int)$coolfilter_group_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
	}

	public function editcoolfilterGroup($coolfilter_group_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "coolfilter_group` SET sort_order = '" . (int)$data['sort_order'] . "' WHERE coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coolfilter_group_description` WHERE coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");

		foreach ($data['coolfilter_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "coolfilter_group_description` SET coolfilter_group_id = '" . (int)$coolfilter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "coolfilter_group_to_category WHERE coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
		
		if (isset($data['category_id'])) {
			foreach ($data['category_id'] as $category_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "coolfilter_group_to_category` SET coolfilter_group_id = '" . (int)$coolfilter_group_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
	}
	
	public function deletecoolfilterGroup($coolfilter_group_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coolfilter_group` WHERE coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coolfilter_group_description` WHERE coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coolfilter_group_to_category` WHERE coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
	}
		
	public function getcoolfilterGroup($coolfilter_group_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coolfilter_group` WHERE coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
		
		return $query->row;
	}
		
	public function getcoolfilterGroups($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "coolfilter_group` ag LEFT JOIN `" . DB_PREFIX . "coolfilter_group_description` agd ON (ag.coolfilter_group_id = agd.coolfilter_group_id) WHERE agd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
		$sort_data = array(
			'agd.name',
			'ag.sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY agd.name";	
		}	
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
		
		$group_data = array();
		
		foreach ($query->rows as $group) {
			
			$category_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "coolfilter_group_to_category` cotc ON (c.category_id = cotc.category_id) LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (cd.category_id = cotc.category_id) WHERE cotc.coolfilter_group_id = '" . (int)$group['coolfilter_group_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			$group_data[] = array(
				'coolfilter_group_id' => $group['coolfilter_group_id'],
				'name'            => $group['name'],
				'categories'      => $category_query->rows,
				'sort_order'      => $group['sort_order']
			);
			
			
		}
		
		return $group_data;
	}
	
	
	public function getcoolfilterGroupDescriptions($coolfilter_group_id) {
		$coolfilter_group_data = array();
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coolfilter_group_description` WHERE coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
		
		foreach ($query->rows as $result) {
			$coolfilter_group_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $coolfilter_group_data;
	}
	
	public function getGroupCategories($coolfilter_group_id) {
		$categories_id = array();

		$query = $this->db->query("SELECT c.category_id AS category_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "coolfilter_group_to_category cotc ON (c.category_id = cotc.category_id) WHERE cotc.coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");

		foreach ($query->rows as $result) {
			$categories_id[] = $result['category_id'];
		}

		return $categories_id;
	}
	
	public function getTotalcoolfilterGroups() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coolfilter_group`");
		
		return $query->row['total'];
	}	
}
?>