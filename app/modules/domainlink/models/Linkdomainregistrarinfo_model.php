<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Linkdomainregistrarinfo_model extends CI_Model
{
    private $table_name = 'link_domain_registrar_info';


    public function getInfoByDomainId($domainId)
    {
    	$this->db->flush_cache();
    	$this->db->select('*');
    	$this->db->from($this->table_name);
    	$this->db->where('link_domain_id', $domainId);
    	$query = $this->db->get();
    	if($query){
    		$result = $query->row_array();
    		return nl2br($result['registrar_info']);
    	} else {
    		$result = false;
    	}
    	return $result;

    }
 }   