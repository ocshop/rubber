<?php
class ModelModuleBlogReviews extends Model {		
	public function getLatestReviews($limit = 5) {
		$query = $this->db->query("SELECT r.review_article_id, r.author, r.rating, r.text, p.article_id, pd.name, p.image, r.date_added FROM " . DB_PREFIX . "review_article r LEFT JOIN " . DB_PREFIX . "article p ON (r.article_id = p.article_id) LEFT JOIN " . DB_PREFIX . "article_description pd ON (p.article_id = pd.article_id) LEFT JOIN " . DB_PREFIX . "article_to_store p2s ON (p.article_id = p2s.article_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$limit);
		
		return $query->rows;
	}
	
	public function getRandomReviews($limit = 5) {
		$query = $this->db->query("SELECT r.review_article_id, r.author, r.rating, r.text, p.article_id, pd.name, p.image, r.date_added FROM " . DB_PREFIX . "review_article r LEFT JOIN " . DB_PREFIX . "article p ON (r.article_id = p.article_id) LEFT JOIN " . DB_PREFIX . "article_description pd ON (p.article_id = pd.article_id) LEFT JOIN " . DB_PREFIX . "article_to_store p2s ON (p.article_id = p2s.article_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY RAND() LIMIT " . (int)$limit);
		
		return $query->rows;
	}
}
?>