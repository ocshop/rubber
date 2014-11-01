<?php
class ModelBlogArticle extends Model {
	public function addArticle($data) {
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "article SET article_review = '" . (int)$data['article_review'] . "', status = '" . (int)$data['status'] . "', gstatus = '" . (int)$data['gstatus'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");
		
		$article_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "article SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE article_id = '" . (int)$article_id . "'");
		}
		
		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "'");
		}
		
		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_store SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['article_image'])) {
			foreach ($data['article_image'] as $article_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_image SET article_id = '" . (int)$article_id . "', image = '" . $this->db->escape(html_entity_decode($article_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$article_image['sort_order'] . "'");
			}
		}
		
		if (isset($data['article_download'])) {
			foreach ($data['article_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_download SET article_id = '" . (int)$article_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		if (isset($data['article_news'])) {
			foreach ($data['article_news'] as $news_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_news SET article_id = '" . (int)$article_id . "', news_id = '" . (int)$news_id . "'");
			}
		}
		
		if (isset($data['main_news_id']) && $data['main_news_id'] > 0) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_news WHERE article_id = '" . (int)$article_id . "' AND news_id = '" . (int)$data['main_news_id'] . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_news SET article_id = '" . (int)$article_id . "', news_id = '" . (int)$data['main_news_id'] . "', main_news = 1");
		} elseif (isset($data['article_news'][0])) {
			$this->db->query("UPDATE " . DB_PREFIX . "article_to_news SET main_news = 1 WHERE article_id = '" . (int)$article_id . "' AND news_id = '" . (int)$data['article_news'][0] . "'");
		}

		if (isset($data['article_related'])) {
			foreach ($data['article_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$article_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$related_id . "' AND related_id = '" . (int)$article_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$related_id . "', related_id = '" . (int)$article_id . "'");
			}
		}
		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related_product WHERE article_id = '" . (int)$article_id . "' AND product_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related_product SET article_id = '" . (int)$article_id . "', product_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related_product WHERE article_id = '" . (int)$related_id . "' AND product_id = '" . (int)$article_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related_product SET article_id = '" . (int)$related_id . "', product_id = '" . (int)$article_id . "'");
			}
		}

		

		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_layout SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
						
		$this->cache->delete('article');
	}
	
	public function editArticle($article_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "article SET article_review = '" . (int)$data['article_review'] . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', gstatus = '" . (int)$data['gstatus'] . "', date_modified = NOW() WHERE article_id = '" . (int)$article_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "article SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE article_id = '" . (int)$article_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_store WHERE article_id = '" . (int)$article_id . "'");

		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_store SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_image WHERE article_id = '" . (int)$article_id . "'");
		
		if (isset($data['article_image'])) {
			foreach ($data['article_image'] as $article_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_image SET article_id = '" . (int)$article_id . "', image = '" . $this->db->escape(html_entity_decode($article_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$article_image['sort_order'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_download WHERE article_id = '" . (int)$article_id . "'");
		
		if (isset($data['article_download'])) {
			foreach ($data['article_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_download SET article_id = '" . (int)$article_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_news WHERE article_id = '" . (int)$article_id . "'");
		
		if (isset($data['article_news'])) {
			foreach ($data['article_news'] as $news_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_news SET article_id = '" . (int)$article_id . "', news_id = '" . (int)$news_id . "'");
			}		
		}

		if (isset($data['main_news_id']) && $data['main_news_id'] > 0) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_news WHERE article_id = '" . (int)$article_id . "' AND news_id = '" . (int)$data['main_news_id'] . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_news SET article_id = '" . (int)$article_id . "', news_id = '" . (int)$data['main_news_id'] . "', main_news = 1");
		} elseif (isset($data['article_news'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "article_to_news SET main_news = 1 WHERE article_id = '" . (int)$article_id . "' AND news_id = '" . (int)$data['article_news'][0] . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE related_id = '" . (int)$article_id . "'");

		if (isset($data['article_related'])) {
			foreach ($data['article_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$article_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$related_id . "' AND related_id = '" . (int)$article_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related SET article_id = '" . (int)$related_id . "', related_id = '" . (int)$article_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related_product WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related_product WHERE product_id = '" . (int)$article_id . "'");
		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related_product WHERE article_id = '" . (int)$article_id . "' AND product_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related_product SET article_id = '" . (int)$article_id . "', product_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "article_related_product WHERE article_id = '" . (int)$related_id . "' AND product_id = '" . (int)$article_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "article_related_product SET article_id = '" . (int)$related_id . "', product_id = '" . (int)$article_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");

		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "article_to_layout SET article_id = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
						
		$this->cache->delete('article');
	}
	
	
	public function copyArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX . "article_description pd ON (p.article_id = pd.article_id) WHERE p.article_id = '" . (int)$article_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			$data = array();
			
			$data = $query->row;

			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';
			$data['article_review'] = '0';
						
			$data = array_merge($data, array('article_description' => $this->getArticleDescriptions($article_id)));			
			
			$data = array_merge($data, array('article_image' => $this->getArticleImages($article_id)));
			
			$data['article_image'] = array();
			
			$results = $this->getArticleImages($article_id);
			
			foreach ($results as $result) {
				$data['article_image'][] = $result['image'];
			}
						
			$data = array_merge($data, array('article_related' => $this->getArticleRelated($article_id)));
			$data = array_merge($data, array('article_related_product' => $this->getArticleRelated($product_id)));
			$data = array_merge($data, array('article_news' => $this->getArticleCategories($article_id)));
			$data = array_merge($data, array('article_download' => $this->getArticleDownloads($article_id)));
			$data = array_merge($data, array('article_layout' => $this->getArticleLayouts($article_id)));
			$data = array_merge($data, array('article_store' => $this->getArticleStores($article_id)));
			
			$this->addArticle($data);
		}
	}
	
	public function deleteArticle($article_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "article WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_image WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related WHERE related_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related_product WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_related_product WHERE product_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_news WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_download WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_store WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review_article WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id. "'");
		
		$this->cache->delete('article');
	}
	
	public function getArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "') AS keyword FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX . "article_description pd ON (p.article_id = pd.article_id) WHERE p.article_id = '" . (int)$article_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				
		return $query->row;
	}
	
	public function getArticles($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX . "article_description pd ON (p.article_id = pd.article_id)";
			
			if (!empty($data['filter_news_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "article_to_news p2c ON (p.article_id = p2c.article_id)";			
			}
					
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
			
			if (!empty($data['filter_name'])) {
			
			
			
				$sql .= " AND REPLACE (LCASE(pd.name),' ','') LIKE '%" . $this->db->escape(utf8_strtolower(str_replace(' ','',($data['filter_name'])))) . "%'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}
					
			if (!empty($data['filter_news_id'])) {
				if (!empty($data['filter_sub_news'])) {
					$implode_data = array();
					
					$implode_data[] = "news_id = '" . (int)$data['filter_news_id'] . "'";
					
					$this->load->model('blog/news');
					
					$categories = $this->model_blog_news->getCategories($data['filter_news_id']);
					
					foreach ($categories as $news) {
						$implode_data[] = "p2c.news_id = '" . (int)$news['news_id'] . "'";
					}
					
					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				} else {
					$sql .= " AND p2c.news_id = '" . (int)$data['filter_news_id'] . "'";
				}
			}
			
		if (isset($data['gstatus'])) {
			$sql .= " AND gstatus = '" . (int)$data['gstatus'] . "'";
		}
			
			
			$sql .= " GROUP BY p.article_id";
						
			$sort_data = array(
				'pd.name',
				'p.status',
				'p.sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY pd.name";	
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
		
			return $query->rows;
		} else {
			//$article_data = $this->cache->get('article.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));
		
			if (!$article_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX . "article_description pd ON (p.article_id = pd.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.name ASC");
	
				$article_data = $query->rows;
			
				//$this->cache->set('article.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $article_data);
			}	
	
			return $article_data;
		}
	}
	
	public function getArticlesByCategoryId($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX . "article_description pd ON (p.article_id = pd.article_id) LEFT JOIN " . DB_PREFIX . "article_to_news p2c ON (p.article_id = p2c.article_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.news_id = '" . (int)$news_id . "' ORDER BY pd.name ASC");
								  
		return $query->rows;
	} 
	
	public function getArticleDescriptions($article_id) {
		$article_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
			$article_description_data[$result['language_id']] = array(
				'seo_title'        => $result['seo_title'],
				'seo_h1'           => $result['seo_h1'],
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description']
			);
		}
		
		return $article_description_data;
	}

	public function getArticleImages($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_image WHERE article_id = '" . (int)$article_id . "'");
		
		return $query->rows;
	}
	
	public function getArticleDownloads($article_id) {
		$article_download_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_download WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
			$article_download_data[] = $result['download_id'];
		}
		
		return $article_download_data;
	}

	public function getArticleStores($article_id) {
		$article_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_store WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_store_data[] = $result['store_id'];
		}
		
		return $article_store_data;
	}

	public function getArticleLayouts($article_id) {
		$article_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_layout WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
			$article_layout_data[$result['store_id']] = $result['layoz ut_id'];
		}
		
		return $article_layout_data;
	}
		
	public function getArticleCategories($article_id) {
		$article_news_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_news WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
			$article_news_data[] = $result['news_id'];
		}

		return $article_news_data;
	}
	
	public function getArticleGstatus($article_id) {
		$article_news_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_to_news WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
				
			$article_news_data[] = $result['news_id'];
			$sql = $this->db->query("SELECT top FROM " . DB_PREFIX . "news WHERE news_id = '" . $result['news_id'] . "'");
			$article_g= $sql->row;
			if ($article_g['top']== 1) {
				return true;
			};
		}

		return false;
	}
	
	
	

	public function getArticleMainCategoryId($article_id) {
		$query = $this->db->query("SELECT news_id FROM " . DB_PREFIX . "article_to_news WHERE article_id = '" . (int)$article_id . "' AND main_news = '1' LIMIT 1");

		return ($query->num_rows ? (int)$query->row['news_id'] : 0);
	}
	
	public function getArticleRelated($article_id) {
		$article_related_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_related WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
			$article_related_data[] = $result['related_id'];
		}
		
		return $article_related_data;
	}
	
	public function getArticleRelatedProduct($article_id) {
		$article_related_product = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_related_product WHERE article_id = '" . (int)$article_id . "'");
		
		foreach ($query->rows as $result) {
			$article_related_product[] = $result['product_id'];
		}
		
		return $article_related_product;
	}
	
	public function getTotalArticles($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.article_id) AS total FROM " . DB_PREFIX . "article p LEFT JOIN " . DB_PREFIX . "article_description pd ON (p.article_id = pd.article_id)";

		if (!empty($data['filter_news_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "article_to_news p2c ON (p.article_id = p2c.article_id)";			
		}
		 
		if (isset($data['gstatus'])) {
			$sql .= " WHERE gstatus = '" . (int)$data['gstatus'] . "'";
		}
		
		 			
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		
		if (isset($data['filter_status'])) {
			$sql .= " AND status = '" . (int)$data['filter_status'] . "'";
		}
		
		
		
		
		if (!empty($data['filter_news_id'])) {
			if (!empty($data['filter_sub_news'])) {
				$implode_data = array();
				
				$implode_data[] = "p2c.news_id = '" . (int)$data['filter_news_id'] . "'";
				
				$this->load->model('blog/news');
				
				$categories = $this->model_blog_news->getCategories($data['filter_news_id']);
				
				foreach ($categories as $news) {
					$implode_data[] = "p2c.news_id = '" . (int)$news['news_id'] . "'";
				}
				
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
			} else {
				$sql .= " AND p2c.news_id = '" . (int)$data['filter_news_id'] . "'";
			}
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	

	public function getTotalArticlesByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article_to_download WHERE download_id = '" . (int)$download_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalArticlesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
	
}
?>