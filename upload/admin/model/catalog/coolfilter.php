<?php

class ModelCatalogCoolfilter extends Model {

  public function addOption($data) {

    $this->db->query("INSERT INTO `" . DB_PREFIX . "category_option` SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

    $option_id = $this->db->getLastId();

    foreach ($data['category_option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_option_description` SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
    
	
	if (isset($data['coolfilter_group_id'])) {
		foreach ($data['coolfilter_group_id'] as $coolfilter_group_id) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "coolfilter_group_option_to_coolfilter_group` SET option_id = '" . (int)$option_id . "', coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
		}
	}
	
	
    if (isset($data['type'])) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "type_option` SET option_id = '" . (int)$option_id . "', type_index = '" . $this->db->escape($data['type']) . "'");
	}
    
    if (isset($data['style'])) {
		if (isset($data['type']) && $data['type'] == 'price') {
			$data['style'] = 'slider';
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "style_option` SET option_id = '" . (int)$option_id . "', style_id = '" . $this->db->escape($data['style']) . "'");
	}

    $this->cache->delete('option');
  }

  public function editOption($option_id, $data) {

    $this->db->query("UPDATE `" . DB_PREFIX . "category_option` SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE option_id = '" . (int)$option_id . "'");

    $this->db->query("DELETE FROM `" . DB_PREFIX . "category_option_description` WHERE option_id = '" . (int)$option_id . "'");

    foreach ($data['category_option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_option_description` SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
	}
	
	$this->db->query("DELETE FROM `" . DB_PREFIX . "coolfilter_group_option_to_coolfilter_group` WHERE option_id = '" . (int)$option_id . "'");

    if (isset($data['coolfilter_group_id'])) {
		foreach ($data['coolfilter_group_id'] as $coolfilter_group_id) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "coolfilter_group_option_to_coolfilter_group` SET option_id = '" . (int)$option_id . "', coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
		}
	}
    
    $this->db->query("DELETE FROM `" . DB_PREFIX . "type_option` WHERE option_id = '" . (int)$option_id . "'");
    
    if (isset($data['type'])) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "type_option` SET option_id = '" . (int)$option_id . "', type_index = '" . $this->db->escape($data['type']) . "'");
	}
    
    $this->db->query("DELETE FROM `" . DB_PREFIX . "style_option` WHERE option_id = '" . (int)$option_id . "'");
    
    if (isset($data['style'])) {
		if (isset($data['type']) && $data['type'] == 'price') {
			$data['style'] = 'slider';
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "style_option` SET option_id = '" . (int)$option_id . "', style_id = '" . $this->db->escape($data['style']) . "'");
	}
    
    $this->cache->delete('option');
  }
  
  public function getOptionsForTypes()
  {
	$options_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` co LEFT JOIN `" . DB_PREFIX . "option_description` cod ON (co.option_id = cod.option_id) WHERE cod.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY sort_order");
	
	$options = array();
	
    foreach ($options_query->rows as $option) {
		$options['option_' . $option['option_id']] = array('value' => $option['name']);
	}
	
	return $options;
  }
  
  public function getAttributesForTypes()
  {
	
	$attributes_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute` co LEFT JOIN `" . DB_PREFIX . "attribute_description` cod ON (co.attribute_id = cod.attribute_id) WHERE cod.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY sort_order");
	
	$attributes = array();
	
    foreach ($attributes_query->rows as $attribute) {
		$attributes['attribute_' . $attribute['attribute_id']] = array('value' => $attribute['name']);
	}
	
	return $attributes;
  }
  
    public function getParameteresForTypes()
  {
	
	$parameteres_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group` co LEFT JOIN `" . DB_PREFIX . "filter_group_description` cod ON (co.filter_group_id = cod.filter_group_id) WHERE cod.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY sort_order");
	
	$parameteres = array();
	
    foreach ($parameteres_query->rows as $parameter) {
		$attributes['parametere_' . $parameter['filter_group_id']] = array('value' => $parameter['name']);
	}
	
	return $attributes;
  }
  
  public function getOptionTypes($option_id) {
  
    $query = $this->db->query("SELECT type_index FROM `" . DB_PREFIX . "type_option` WHERE option_id = '" . $this->db->escape($option_id) . "'");
    $row = $query->row;
	
    return (isset($row['type_index']) ? $row['type_index'] : array());
    
  }
      
  public function getOptionStyles($option_id) {
  
    $query = $this->db->query("SELECT style_id FROM `" . DB_PREFIX . "style_option` WHERE option_id = '" . (int)$option_id . "'");
    $row = $query->row;
	
	return (isset($row['style_id']) ? $row['style_id'] : array());
    
  }


  public function deleteOption($option_id) {
    $this->db->query("DELETE FROM `" . DB_PREFIX . "category_option` WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM `" . DB_PREFIX . "category_option_description` WHERE option_id = '" . (int)$option_id . "'");
	$this->db->query("DELETE FROM `" . DB_PREFIX . "coolfilter_group_option_to_coolfilter_group` WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM `" . DB_PREFIX . "style_option` WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM `" . DB_PREFIX . "type_option` WHERE option_id = '" . (int)$option_id . "'");
    $this->cache->delete('option');
  }

  public function getOption($option_id) {
    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_option` co LEFT JOIN `" . DB_PREFIX . "category_option_description` cod ON (co.option_id = cod.option_id) WHERE co.option_id = '" . (int)$option_id . "' ORDER BY co.sort_order");

    return $query->row;
  }

  public function getTotalcoolfiltersBycoolfilterGroupId($coolfilter_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coolfilter_group_option_to_coolfilter_group` WHERE coolfilter_group_id = '" . (int)$coolfilter_group_id . "'");
		
		return $query->row['total'];
  }	
  
   
  
  public function getOptioncoolfilterGroups($option_id) {
   $coolfilter_group_id = array();

   $query = $this->db->query("SELECT c.coolfilter_group_id AS coolfilter_group_id FROM `" . DB_PREFIX . "coolfilter_group` c LEFT JOIN `" . DB_PREFIX . "coolfilter_group_option_to_coolfilter_group` cotc ON (c.coolfilter_group_id = cotc.coolfilter_group_id) WHERE cotc.option_id = '" . (int)$option_id . "'");

   foreach ($query->rows as $result) {
	   $coolfilter_group_id[] = $result['coolfilter_group_id'];
	 }

   return $coolfilter_group_id;
  }


	public function getOptionDescriptions($option_id) {
		$option_description_data = array();

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_option_description` WHERE option_id = '" . (int)$option_id . "'");

		foreach ($query->rows as $result) {
			$option_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $option_description_data;
	}

  public function getOptions() {
    $option_data = array();

    $option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_option` co LEFT JOIN `" . DB_PREFIX . "category_option_description` cod ON (co.option_id = cod.option_id) WHERE cod.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY sort_order");

    foreach ($option_query->rows as $option) {
	  
	  $coolfilter_group_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coolfilter_group` c LEFT JOIN `" . DB_PREFIX . "coolfilter_group_option_to_coolfilter_group` cotc ON (c.coolfilter_group_id = cotc.coolfilter_group_id) LEFT JOIN `" . DB_PREFIX . "coolfilter_group_description` cd ON (cd.coolfilter_group_id = cotc.coolfilter_group_id) WHERE cotc.option_id = '" . (int)$option['option_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	  
	  
	  $option_type_style_query = $this->db->query("SELECT t1.type_index,t2.style_id FROM `" . DB_PREFIX . "type_option` as t1, `" . DB_PREFIX . "style_option` as t2 WHERE t1.option_id = t2.option_id AND t1.option_id = '" . (int)$option['option_id'] . "'");
	
	  $option_type_style = $option_type_style_query->row;
	
      $option_data[] = array(
        'option_id'     => $option['option_id'],
        'name'          => $option['name'],
		'style'			=> $option_type_style['style_id'],
		'type'			=> $option_type_style['type_index'],
		'coolfilter_group'  => $coolfilter_group_query->rows,
        'sort_order'    => $option['sort_order'],
        'status'        => $option['status']
      );
    }

    return $option_data;
  }


  public function getTotalOptions() {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "category_option`");

    return $query->row['total'];
  }



  public function showTable($table) {
  $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "'");
      if ($query->num_rows) {
		return TRUE;
    } else {
		return FALSE;
    }
  }
  // Install coolfilter tables
  
  
  public function createTables() {
  
		$sql = "
      CREATE TABLE IF NOT EXISTS `category_option` (
        `option_id` int(10) NOT NULL auto_increment,
        `status` int(1) default '0',
        `sort_order` int(10) default '0',
        PRIMARY KEY  (`option_id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

      CREATE TABLE IF NOT EXISTS `category_option_description` (
        `option_id` int(10) NOT NULL,
        `language_id` int(10) NOT NULL,
        `name` varchar(127) NOT NULL,
        PRIMARY KEY  (`option_id`,`language_id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
      
	  CREATE TABLE IF NOT EXISTS `coolfilter_group_option_to_coolfilter_group` (
        `option_id` int(11) NOT NULL,
        `coolfilter_group_id` int(11) NOT NULL,
        PRIMARY KEY  (`coolfilter_group_id`,`option_id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
	  
	  CREATE TABLE IF NOT EXISTS `coolfilter_group_to_category` (
        `coolfilter_group_id` int(11) NOT NULL,
        `category_id` int(11) NOT NULL,
        PRIMARY KEY  (`coolfilter_group_id`,`category_id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
	  
      CREATE TABLE IF NOT EXISTS `type_option` (
        `option_id` int(11) NOT NULL,
        `type_index` varchar(250) NOT NULL,
        PRIMARY KEY  (`type_index`,`option_id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
      
      CREATE TABLE IF NOT EXISTS `style_option` (
        `option_id` int(11) NOT NULL,
        `style_id` varchar(250) NOT NULL,
        PRIMARY KEY  (`style_id`,`option_id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
		
	  CREATE TABLE IF NOT EXISTS `coolfilter_group` (
	    `coolfilter_group_id` int(11) NOT NULL auto_increment,
	    `sort_order` int(3) NOT NULL,
	    PRIMARY KEY  (`coolfilter_group_id`)
	  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

	  CREATE TABLE IF NOT EXISTS `coolfilter_group_description` (
	    `coolfilter_group_id` int(11) NOT NULL,
	    `language_id` int(11) NOT NULL,
	    `name` varchar(64) NOT NULL,
	    PRIMARY KEY  (`coolfilter_group_id`,`language_id`)
	  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

		
    ";

		$query = '';

		foreach(explode(';', $sql) as $line) {
			$query = str_replace("CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX, trim($line));
			if ($query != '') {
 			  $this->db->query($query);
      }
			$query = '';
		}

		if ($this->showTable(DB_PREFIX.'category_option')) {
          return TRUE;
        } else {
          return FALSE;
        }
	}
    
    public function deleteTables() {

		$sql = "
          DROP TABLE IF EXISTS `category_option`;

          DROP TABLE IF EXISTS `category_option_description`;
		  
		  DROP TABLE IF EXISTS `coolfilter_group_option_to_coolfilter_group`;
		  
		  DROP TABLE IF EXISTS `coolfilter_group_to_category`;
          
          DROP TABLE IF EXISTS `type_option`;
          
          DROP TABLE IF EXISTS `style_option`;
		  
          DROP TABLE IF EXISTS `coolfilter_group`;
		  
          DROP TABLE IF EXISTS `coolfilter_group_description`;
    ";

		$query = '';

		foreach(explode(';', $sql) as $line) {
			$query = str_replace("DROP TABLE IF EXISTS `", "DROP TABLE IF EXISTS `" . DB_PREFIX, trim($line));
			if ($query != '') {
 			  $this->db->query($query);
      }
			$query = '';
		}

		if ($this->showTable(DB_PREFIX.'category_option')) {
          return FALSE;
        } else {
          return TRUE;
        }
	}
    
    
}

?>