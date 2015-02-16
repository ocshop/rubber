<?php
class ModelCatalogProduct extends Model {

	private $NOW;

	public function __construct($registry) {
		$this->NOW = date('Y-m-d H:i') . ':00';
		parent::__construct($registry);
	}

	private $FOUND_ROWS;

	public function getFoundProducts() {
		return $this->FOUND_ROWS;
	}
	
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getProduct($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < '" . $this->NOW . "') AND (pd2.date_end = '0000-00-00' OR pd2.date_end > '" . $this->NOW . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'seo_title'        => $query->row['seo_title'],
				'seo_h1'           => $query->row['seo_h1'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'description_mini' => $query->row['description_mini'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
			if (!empty($data['coolfilter'])) {
				$coolfilter = $data['coolfilter'];
			} else {
				$coolfilter = 0;
			}
			
	// Start coolfilter
			$currency_value = $this->currency->getValue();
			$tax_rate = $this->tax->getTax(100, 9);
			
			$sql_add_table_prices = '';
			$sql_where_prices = '';
			$sql_where_manufacteurs = '';
			$sql_add_table_options = '';
			$sql_where_options = '';
			$sql_add_table_attributes = '';
			$sql_where_attributes = '';
			//standart filter
			$sql_add_table_parameters = '';
			$sql_where_parameters = '';
			//standart filter
			if ($coolfilter) {
			
			  foreach (explode(';', $coolfilter) as $option) {
				$values = explode(':', $option);
								
				if ($values[0] == 'm' && preg_match('/^[\d\|]*$/', $values[1])) {
				
					$values[1] = explode("|", $values[1]);
					$values[1] =  implode(",", $values[1]);
					$sql_where_manufacteurs = ' AND p.manufacturer_id IN (' . $this->db->escape($values[1]) . ') ';
				}
				if (preg_match('/o_\d+/', $values[0]) && preg_match('/^[\d\|]*$/', $values[1])) {
						
					$values[1] = str_replace('|', ',', $values[1]);
					
					$option_id = $this->db->escape($values[0]);
					$sql_add_table_options .= ' LEFT JOIN ' . DB_PREFIX . 'product_option_value pov' . $option_id . ' ON (p.product_id = pov' . $option_id . '.product_id)';
					
					$sql_where_options .= ' AND pov' . $option_id . '.option_value_id IN (' . $this->db->escape($values[1]) .') AND (pov' . $option_id . '.subtract=0 OR pov' . $option_id . '.subtract=1 AND pov' . $option_id . '.quantity > 0)';
				}
				

				if (preg_match('/a_\d+/', $values[0])) {
				
				
				
					$attribute_id = $this->db->escape($values[0]);
					$sql_add_table_attributes .= " LEFT JOIN " . DB_PREFIX . "product_attribute atr" . $attribute_id . " ON (p.product_id = atr" . $attribute_id . ".product_id)";
					$get_id = explode("_", $values[0]);
					
					$values[1] = explode("|", $values[1]);
					for ($i = 0; $i < count($values[1]); $i++) {
						$values[1][$i] = $this->db->escape($values[1][$i]);
					}
					
					$values[1] = "'" . implode("','", $values[1]) . "'";
					
					$sql_where_attributes .= " AND (atr" . $attribute_id . ".language_id = '" . (int)$this->config->get('config_language_id') . "' AND atr" . $attribute_id . ".attribute_id = '" . (int)$get_id[1] . "' AND atr" . $attribute_id . ".text IN (" . $values[1] . "))";
				}
				
				if ($values[0] == 'p') {
			
					$values[1] = explode(",", $values[1]);
					if (!isset($values[1][0])) {
						$values[1][0] = 0;
					} else {
						$values[1][0] /= $currency_value;
					}
					
					if (!isset($values[1][1])) {
						$values[1][1] = 9999999999;
					} else {
						$values[1][1] /= $currency_value;
					}
					
					for ($i = 0; $i < 2; $i++) {
						$values[1][$i] = $this->db->escape($values[1][$i]);
					}
					
					if (!empty($data['coolfilter_category_id'])) {
						$category_id = ' AND ct.category_id IN (' . $data['coolfilter_category_id'] . ')';
					} else {
						$category_id = '';
					}
				
					$sql_add_table_prices .= " LEFT JOIN (SELECT pr.product_id, pr.price, (SELECT pd2.price FROM `" . DB_PREFIX . "product_discount` pd2 WHERE pd2.product_id = pr.product_id AND pd2.customer_group_id = '" . $customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))  ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) discount, (SELECT ps.price FROM `" . DB_PREFIX . "product_special` ps WHERE ps.product_id = pr.product_id AND ps.customer_group_id = '" . $customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) special FROM `" . DB_PREFIX . "product` pr LEFT JOIN `" . DB_PREFIX . "product_to_category` ct ON (pr.product_id = ct.product_id) WHERE pr.status = '1'" . $category_id . ") tb ON (p.product_id = tb.product_id)";
					$sql_where_prices .= "AND tb.price >= '" . $values[1][0] . "' AND (tb.discount IS NULL OR tb.discount >= '" . $values[1][0] . "') AND (tb.special IS NULL OR tb.special >= '" . $values[1][0] . "') AND tb.price <= '" . $values[1][1] . "' AND (tb.discount IS NULL OR tb.discount <= '" . $values[1][1] . "') AND (tb.special IS NULL OR tb.special <= '" . $values[1][1] . "')";
				}
				//standart filter
					//if (preg_match('/p_\d+/', $values[0])) {
			
					if (preg_match('/p_\d+/', $values[0]) && preg_match('/^[\d\|]*$/', $values[1])) {
					
					
					$parameter_id = $this->db->escape($values[0]);
					
					
					$sql_add_table_parameters .= " LEFT JOIN " . DB_PREFIX . "product_filter par" . $parameter_id . " ON (p.product_id = par" . $parameter_id . ".product_id) LEFT JOIN  " . DB_PREFIX . "filter_description fd" . $parameter_id . "  ON (par" . $parameter_id . ".filter_id = fd" . $parameter_id . ".filter_id) ";
					$get_id = explode("_", $values[0]);
					
					$values[1] = explode("|", $values[1]);
					for ($i = 0; $i < count($values[1]); $i++) {
						$values[1][$i] = $this->db->escape($values[1][$i]);
						
							
					}
								
					$values[1] = "'" . implode("','", $values[1]) . "'";
						
					$sql_where_parameters .= " AND (fd" . $parameter_id . ".language_id = '" . (int)$this->config->get('config_language_id') . "' AND par" . $parameter_id . ".filter_id IN (" . $values[1] . "))";
					}
				//standart filter
				}
			  }
			

			// End coolfilter

		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special"; 

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
		
		// start coolfilter
		$sql .= $sql_add_table_options;
		$sql .= $sql_add_table_attributes;
		$sql .= $sql_add_table_prices;
		$sql .= $sql_add_table_parameters;
			// End coolfilter	

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
			}	

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
			}
		}	

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}	

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		$sql .= $sql_where_manufacteurs;
		$sql .= $sql_where_attributes;
		$sql .= $sql_where_options;
		$sql .= $sql_where_prices; //print_r($sql);
		//standart filter
		$sql .= $sql_where_parameters; //print_r($sql);
		//standart filter

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}
	
	public function getProductSticker($product_id) {
		$product_sticker_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_sticker WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_sticker_data[] = $result['sticker_id'];
		}

		return $product_sticker_data;
	}	
/*
	public function getProductBenefit($product_id) {
		$product_benefit_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_benefit WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_sticker_data[] = $result['sticker_id'];
		}

		return $product_sticker_data;
	}
*/
	//ocshop benefits
	public function getProductBenefitsbyProductId($product_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_benefit p2b LEFT JOIN " . DB_PREFIX . "benefit b ON (p2b.benefit_id = b.benefit_id) LEFT JOIN " . DB_PREFIX . "benefit_description bd ON (p2b.benefit_id = bd.benefit_id) WHERE product_id = '" . (int)$product_id . "' AND bd.language_id = '" . (int)$this->config->get('config_language_id')."'");

		return $query->rows;
	}	
	//ocshop benefits
	
	public function getProductStickerbyProductId($product_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_sticker p2s LEFT JOIN " . DB_PREFIX . "sticker ps ON (p2s.sticker_id = ps.sticker_id) WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}
	
	public function getProductSpecials($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "')) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $customer_group_id . '.' . (int)$limit);

		if (!$product_data) { 
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}
	
	
	public function getLatest($data = array()) {
	
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$key = 'product.latestp' . md5(serialize($data)) . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id;
				
		$product_data = $this->cache->get($key);
		$product_data = '';
	
		if (!$product_data) { 
		
			$this->load->model('catalog/product');
		
			$sql = "SELECT * FROM (SELECT p.product_id, p.sort_order, p.model, pd.name, p.quantity, p.price, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < '" . $this->NOW . "') AND (pd2.date_end = '0000-00-00' OR pd2.date_end > '" . $this->NOW . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,  (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') .  "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY p.date_added DESC";
			
			$sql .= " LIMIT  0," . (int)$data['max'];
			
		$sql .= ") p ORDER BY ";
		
		$sort_data = array(
			'pd.name',
			'quantity',
			'ps.price',
			'rating',
			'p.sort_order',
			'p.model'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name') {
				$sql .= " LCASE('name')";
			} elseif ($data['sort'] == 'ps.price') {
				$sql .= " (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " " . $data['sort'];
			}
		} else {
			$sql .= " sort_order";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(name) DESC";
		} else {
			$sql .= " ASC, LCASE(name) ASC";
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
		 	 
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}

			$this->cache->set($key, $product_data);
		}
				
		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = array();

		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit);

		if (!$product_data) { 
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) { 		
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}
	
	public function getBestSellers($data) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$product_data = $this->cache->get('product.bestsellers.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit']);

		$product_data = null;
		
		if (!$product_data) { 
			$product_data = array();

		$sql = "SELECT DISTINCT p.product_id FROM (SELECT p.product_id, p.sort_order, p.price, p.model, 
		(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < '" . $this->NOW . "') AND (pd2.date_end = '0000-00-00' OR pd2.date_end > '" . $this->NOW . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
		(SELECT ps.price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,  
		(SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, 
	 COUNT(op.product_id)  AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT 0, " . (int)$data['max'];
			
		$sql .= ") p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '".  (int)$this->config->get('config_language_id') ."') ORDER BY ";
		
		$sort_data = array(
			'pd.name',
			'quantity',
			'ps.price',
			'rating',
			'p.sort_order',
			'p.model'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name') {
				$sql .= " LCASE('pd.name')";
			} elseif ($data['sort'] == 'ps.price') {
				$sql .= " (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " " . $data['sort'];
			}
		} else {
			$sql .= " sort_order";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(name) DESC";
		} else {
			$sql .= " ASC, LCASE(name) ASC";
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

			foreach ($query->rows as $result) { 		
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestsellers.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit'], $product_data);
		}

		return $product_data;
	}	
	
	public function getTotalBestSellers($data) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$total = $this->cache->get('product.totalbestsellers.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit']);

		$total = null;
		
		if (!$total) { 
			$total = array();

		$sql = "SELECT COUNT(total) as total FROM (SELECT COUNT(DISTINCT op.product_id)  AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT 0, " . (int)$data['max'].") bp";
			

			$query = $this->db->query($sql);
		
			$total = $query->row['total'];

			$this->cache->set('product.totalbestsellers.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit'], $total);
		}

		return $total;
	}
	
	
	public function getMostViewed($data) {

		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$product_data = $this->cache->get('product.mostviewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit']);

		$product_data = null;
		
		if (!$product_data) { 
			$product_data = array();

		$sql = "SELECT * FROM (SELECT p.product_id, 
		(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id 
		AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 
		WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' 
		AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < '" . $this->NOW . "') 
		AND (pd2.date_end = '0000-00-00' OR pd2.date_end > '" . $this->NOW . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
		(SELECT price FROM " . DB_PREFIX . "product_special ps 
		WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' 
		AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "')) 
		ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, p.sort_order, p.viewed, p.price, p.model"; 
		
		$sql .= " FROM " . DB_PREFIX . "product p  	
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE p.status = '1' AND p.date_available <= '" . $this->NOW . "' 
		AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
		GROUP BY p.product_id  ORDER by p.viewed DESC  LIMIT 0, " . (int)$data['max'];

		$sql .= ") p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '".  (int)$this->config->get('config_language_id') ."') ORDER BY ";
	
		$sort_data = array(
			'pd.name',
			'quantity',
			'ps.price',
			'rating',
			'p.sort_order',
			'p.model',
			'p.viewed'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name') {
				$sql .= " LCASE('pd.name')";
			} elseif ($data['sort'] == 'ps.price') {
				$sql .= " (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " " . $data['sort'];
			}
		} else {
			$sql .= " p.sort_order";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(name) DESC";
		} else {
			$sql .= " ASC, LCASE(name) ASC";
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

			foreach ($query->rows as $result) { 		
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.mostviewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit'], $product_data);
		}

		return $product_data;
	}	
	
	public function getTotalMostViewed($data) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$total = $this->cache->get('product.totalmostviewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit']);

		$total = null;
		
		if (!$total) { 
			$total = array();

		$sql = "SELECT COUNT(mv.product) as total 
		FROM (SELECT COUNT(DISTINCT p.product_id)  AS total FROM " . DB_PREFIX . " product` p 
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' 
		AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
		GROUP BY op.product_id ORDER BY p.viewed DESC LIMIT 0, " . (int)$data['max'].") mv";
			

			$query = $this->db->query($sql);
		
			$total = $query->row['total'];

			$this->cache->set('product.totalmostviewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit'], $total);
		}

		return $total;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']		 	
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);			
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();

				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}

				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option_value_data,
					'required'          => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);				
			}
		}

		return $product_option_data;
	}

	public function getProductDiscounts($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < '" . $this->NOW . "') AND (date_end = '0000-00-00' OR date_end > '" . $this->NOW . "')) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;		
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) { 
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}
	
	public function getProductRelated2($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related2 pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		foreach ($query->rows as $result) { 
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}
		
		return $product_data;
	}
	
	public function getArticleRelated($product_id) {
		$article_data = array();
		$this->load->model('blog/article');
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_related_product np LEFT JOIN " . DB_PREFIX . "article p ON (np.article_id = p.article_id) LEFT JOIN " . DB_PREFIX . "article_to_store p2s ON (p.article_id = p2s.article_id) WHERE np.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		foreach ($query->rows as $result) { 
			$article_data[$result['article_id']] = $this->model_blog_article->getArticle($result['article_id']);
		}

		return $article_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return false;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}	

	public function getTotalProducts($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
				
		// Start coolfilter
		if (!empty($data['coolfilter'])) {
			$coolfilter = $data['coolfilter'];
		} else {
			$coolfilter = 0;
		}
		
		
			$currency_value = $this->currency->getValue();
			
			$sql_add_table_prices = '';
			$sql_where_prices = '';
			$sql_where_manufacteurs = '';
			$sql_add_table_options = '';
			$sql_where_options = '';
			$sql_add_table_attributes = '';
			$sql_where_attributes = '';
			//standart filter
			$sql_add_table_parameters  = '';
			$sql_where_parameters  = '';
			//standart filter
			
				if ($coolfilter) {
			
			  foreach (explode(';', $coolfilter) as $option) {
				$values = explode(':', $option);
								
				if ($values[0] == 'm' && preg_match('/^[\d\|]*$/', $values[1])) {
				
					$values[1] = explode("|", $values[1]);
					$values[1] =  implode(",", $values[1]);
					$sql_where_manufacteurs = ' AND p.manufacturer_id IN (' . $this->db->escape($values[1]) . ') ';
				}
				if (preg_match('/o_\d+/', $values[0]) && preg_match('/^[\d\|]*$/', $values[1])) {
						
					$values[1] = str_replace('|', ',', $values[1]);
					
					$option_id = $this->db->escape($values[0]);
					$sql_add_table_options .= ' LEFT JOIN ' . DB_PREFIX . 'product_option_value pov' . $option_id . ' ON (p.product_id = pov' . $option_id . '.product_id)';
					
					$sql_where_options .= ' AND pov' . $option_id . '.option_value_id IN (' . $this->db->escape($values[1]) .') AND (pov' . $option_id . '.subtract=0 OR pov' . $option_id . '.subtract=1 AND pov' . $option_id . '.quantity > 0)';
				}
				

				if (preg_match('/a_\d+/', $values[0])) {
				
				
				
					$attribute_id = $this->db->escape($values[0]);
					$sql_add_table_attributes .= " LEFT JOIN " . DB_PREFIX . "product_attribute atr" . $attribute_id . " ON (p.product_id = atr" . $attribute_id . ".product_id)";
					$get_id = explode("_", $values[0]);
					
					$values[1] = explode("|", $values[1]);
					for ($i = 0; $i < count($values[1]); $i++) {
						$values[1][$i] = $this->db->escape($values[1][$i]);
					}
					
					$values[1] = "'" . implode("','", $values[1]) . "'";
					
					$sql_where_attributes .= " AND (atr" . $attribute_id . ".language_id = '" . (int)$this->config->get('config_language_id') . "' AND atr" . $attribute_id . ".attribute_id = '" . (int)$get_id[1] . "' AND atr" . $attribute_id . ".text IN (" . $values[1] . "))";
				}
				
				if ($values[0] == 'p') {
			
					$values[1] = explode(",", $values[1]);
					if (!isset($values[1][0])) {
						$values[1][0] = 0;
					} else {
						$values[1][0] /= $currency_value;
					}
					
					if (!isset($values[1][1])) {
						$values[1][1] = 9999999999;
					} else {
						$values[1][1] /= $currency_value;
					}
					
					for ($i = 0; $i < 2; $i++) {
						$values[1][$i] = $this->db->escape($values[1][$i]);
					}
					
					if (!empty($data['coolfilter_category_id'])) {
						$category_id = ' AND ct.category_id IN (' . $data['coolfilter_category_id'] . ')';
					} else {
						$category_id = '';
					}
				
					$sql_add_table_prices .= " LEFT JOIN (SELECT pr.product_id, pr.price, (SELECT pd2.price FROM `" . DB_PREFIX . "product_discount` pd2 WHERE pd2.product_id = pr.product_id AND pd2.customer_group_id = '" . $customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))  ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) discount, (SELECT ps.price FROM `" . DB_PREFIX . "product_special` ps WHERE ps.product_id = pr.product_id AND ps.customer_group_id = '" . $customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) special FROM `" . DB_PREFIX . "product` pr LEFT JOIN `" . DB_PREFIX . "product_to_category` ct ON (pr.product_id = ct.product_id) WHERE pr.status = '1'" . $category_id . ") tb ON (p.product_id = tb.product_id)";
					$sql_where_prices .= "AND tb.price >= '" . $values[1][0] . "' AND (tb.discount IS NULL OR tb.discount >= '" . $values[1][0] . "') AND (tb.special IS NULL OR tb.special >= '" . $values[1][0] . "') AND tb.price <= '" . $values[1][1] . "' AND (tb.discount IS NULL OR tb.discount <= '" . $values[1][1] . "') AND (tb.special IS NULL OR tb.special <= '" . $values[1][1] . "')";
				}
				//standart filter
					//if (preg_match('/p_\d+/', $values[0])) {
			
					if (preg_match('/p_\d+/', $values[0]) && preg_match('/^[\d\|]*$/', $values[1])) {
					
					
					$parameter_id = $this->db->escape($values[0]);
					
					
					$sql_add_table_parameters .= " LEFT JOIN " . DB_PREFIX . "product_filter par" . $parameter_id . " ON (p.product_id = par" . $parameter_id . ".product_id) LEFT JOIN  " . DB_PREFIX . "filter_description fd" . $parameter_id . "  ON (par" . $parameter_id . ".filter_id = fd" . $parameter_id . ".filter_id) ";
					$get_id = explode("_", $values[0]);
					
					$values[1] = explode("|", $values[1]);
					for ($i = 0; $i < count($values[1]); $i++) {
						$values[1][$i] = $this->db->escape($values[1][$i]);
						
							
					}
								
					$values[1] = "'" . implode("','", $values[1]) . "'";
						
					$sql_where_parameters .= " AND (fd" . $parameter_id . ".language_id = '" . (int)$this->config->get('config_language_id') . "' AND par" . $parameter_id . ".filter_id IN (" . $values[1] . "))";
					}
				//standart filter
				}
			  }

			// End coolfilter

		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total"; 

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
		
		// start coolfilter
		$sql .= $sql_add_table_options;
		$sql .= $sql_add_table_attributes;
		$sql .= $sql_add_table_prices;
		// End coolfilter	
		//standart filter
		$sql .= $sql_add_table_parameters;
		//standart filter		

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
			}	

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}	

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";				
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		$sql .= $sql_where_manufacteurs;
			$sql .= $sql_where_attributes;
			$sql .= $sql_where_options;
			$sql .= $sql_where_prices; //print_r($sql.'<br><br>');
			//standart filter
			$sql .= $sql_where_parameters;
			//standart filter

			
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfiles($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		return $this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "product_profile` `pp` JOIN `" . DB_PREFIX . "profile_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`profile_id` = `pp`.`profile_id` JOIN `" . DB_PREFIX . "profile` `p` ON `p`.`profile_id` = `pd`.`profile_id` WHERE `product_id` = " . (int)$product_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$customer_group_id . " ORDER BY `sort_order` ASC")->rows;

	}

	public function getProfile($product_id, $profile_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "profile` `p` JOIN `" . DB_PREFIX . "product_profile` `pp` ON `pp`.`profile_id` = `p`.`profile_id` AND `pp`.`product_id` = " . (int)$product_id . " WHERE `pp`.`profile_id` = " . (int)$profile_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$customer_group_id)->row;
	}
	
	public function getProductRelated_by_category($category_id, $limit) {

		$product_data = array();
				
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related_wb pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.category_id = '" . (int)$category_id . "' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT " . (int)$limit); 

		foreach ($query->rows as $result) { 
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}
		return $product_data;
	}
	
	public function getProductRelated_by_manufacturer($manufacturer_id, $limit) {

		$product_data = array();
				
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related_mn pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.manufacturer_id = '" . (int)$manufacturer_id . "' AND p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT " . (int)$limit); 

		foreach ($query->rows as $result) { 
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}
		return $product_data;
	}

	public function getTotalProductSpecials() {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "'))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}
}
?>