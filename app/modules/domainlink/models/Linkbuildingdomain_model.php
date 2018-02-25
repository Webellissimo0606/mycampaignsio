<?php

/**
 * CIMembership
 *
 * @package        Modules
 * @author        1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license        http://opensource.org/licenses/MIT    MIT License
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Linkbuildingdomain_model extends CI_Model
{

    private $table_name = 'link_building_domain';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth/analyze_model');
    }

    public function insert($data)
    {
    	$this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    public function getDomainByUserId($userId)
    {
    	//get the user info
    	$userinfo = $this->analyze_model->getusersinfo($userId);
		$this->db->select('lbd.id, lbd.domain, lbd.user_id, lbd.created, ldi.bing, ldi.google, lbd.owner');
    	if ($userinfo[0]->gid != 1) {
    		$this->db->where($this->table_name . '.user_id=', $user_id);	
    	} 
    	$this->db->from($this->table_name.' as lbd');
        $this->db->join('link_domain_index as ldi','ldi.link_domain_id=lbd.id', 'left');
    	$query = $this->db->get();
    	if ($query) {
    		return $query->result_array();	
    	} else {
    		return false;
    	}
    }

    public function checkDomainExistByUserId($userId, $domain)
    {
        $userinfo = $this->analyze_model->getusersinfo($userId);
        $this->db->flush_cache();
    	$this->db->select('*');
    	$this->db->where($this->table_name . '.user_id=', $userId);
    	//get the user info
    	// if($userinfo[0]->gid != 1) {
    		$this->db->where($this->table_name . '.domain=', $domain);	
    	// }
    	$this->db->from($this->table_name);
    	$query = $this->db->get();
    	if ($query) {
         	return $query->row_array();	
    	} else {	
    		return false;
    	}	
    }

    public function deleteDomain($userId, $domainId)
    {
    	$this->db->flush_cache();
    	$this->db->where('id', $domainId);
    	//get the user info
    	$userinfo = $this->analyze_model->getusersinfo($userId);
      	if ($userinfo[0]->gid != 1) {
    		$this->db->where('user_id', $userId);	
    	}
        $this->db->where('id', $domainId);
      	$this->db->delete($this->table_name);
    }

    public function getDomainInfoByDomainId($domainId)
    {
        $this->db->flush_cache();
        $this->db->where('id',$domainId);
        $this->db->select('*');
        $this->db->from($this->table_name);
        $query = $this->db->get();
        if($query) {
            return $query->row_array();
        } else {
            return false;
        }

    }

}
