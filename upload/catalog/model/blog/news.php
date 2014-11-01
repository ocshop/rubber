<?php
class ModelBlogNews extends Model {
	public function getCategory($news_id) {
		return $this->getCategories((int)$news_id, 'by_id');
	}

	public function getCategories($id = 0, $type = 'by_parent') {
		static $data = null;

		if ($data === null) {
			$data = array();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news c LEFT JOIN " . DB_PREFIX . "news_description cd ON (c.news_id = cd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store c2s ON (c.news_id = c2s.news_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1' ORDER BY c.parent_id, c.sort_order, cd.name");

			foreach ($query->rows as $row) {
				$data['by_id'][$row['news_id']] = $row;
				$data['by_parent'][$row['parent_id']][] = $row;
			}
		}

		return ((isset($data[$type]) && isset($data[$type][$id])) ? $data[$type][$id] : array());
	}

	public function getCategoriesByParentId($news_id) {
		$news_data = array();

		$categories = $this->getCategories((int)$news_id);

		foreach ($categories as $news) {
			$news_data[] = $news['news_id'];

			$children = $this->getCategoriesByParentId($news['news_id']);

			if ($children) {
				$news_data = array_merge($children, $news_data);
			}
		}

		return $news_data;
	}

	public function getCategoryLayoutId($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_layout WHERE news_id = '" . (int)$news_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_news');
		}
	}

	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		return count($this->getCategories((int)$parent_id));
	}
}
?>