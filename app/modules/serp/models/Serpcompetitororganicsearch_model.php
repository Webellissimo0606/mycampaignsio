<?php 
class Serpcompetitororganicsearch_model extends CI_Model 
{

	 public $_table = 'serp_competitor_organic_search';
	public function getcompetitorOrganicSearchByDomain($domain)
	{
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('domain=', $domain);
		$this->db->limit("1");	
		$query = $this->db->get();
		if ($query) {
			return $query->row_array();
		}else{
			return false;
		}

	}

}	