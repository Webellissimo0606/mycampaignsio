<?php

/**
 * CIMembership
 * 
 * @package		Modules
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usergaaccount_model extends CI_Model {

    private $table_name = 'user_ga_account';

    public function getUserIdByGaCode($gaCode)
    {
    	$this->db->flush_cache();
    	$this->db->select('*');
    	$this->db->from($this->table_name);
    	$this->db->where('ga_code=',$gaCode);
    	$query = $this->db->get();
    	if($query){
    		return $query->row_array();
    	}else{
    		return false;
    	}

    }

    public function getGaDetailByDomain($domain)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('websiteurl',$domain);
        $query = $this->db->get();
        if($query){
            return $query->row_array();
        }else{
            return false;
        }        
    }

}