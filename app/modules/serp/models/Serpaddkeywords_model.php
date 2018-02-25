<?php 
class Serpaddkeywords_model extends CI_Model {
	
	public $_table = 'serp_ad_keywords';
	
	public function getAdKeywordsByDomain($domain) {
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('domain=', $domain);
		$this->db->limit("1");	
		$query = $this->db->get();
		return $query ? $query->result_array() : false;
	}
}	