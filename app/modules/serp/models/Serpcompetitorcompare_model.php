<?php 
class Serpcompetitorcompare_model extends CI_Model{

	public function getserpcompetitorByDomains($domain1, $domain2){
		$this->db->select('*');
		$this->db->from('serp_competitor_compare');
		$this->db->where('domain1=', $domain1);
		$this->db->where('domain2=', $domain2);
		$this->db->limit("1");	
		$query = $this->db->get();
		return $query ? $query->row_array() : false;
	}
}	