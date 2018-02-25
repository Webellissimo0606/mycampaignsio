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

class Backlinkdomain_model extends CI_Model
{

    private $table_name = 'backlink_domain';

    public function insert($data)
    {
        //check for duplicate
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('domain', $data['domain']);
        $this->db->where('user_id', $data['user_id']);
        $query = $this->db->get();
        if ($query) {
        	$res = $query->result_array();
        	if(!$res){
        		$this->db->flush_cache();
        		$this->db->insert($this->table_name, $data);
        		return true;	
        	}else {
        		return false;
        	}
            
        } else {
            return false;
        }
        //

    }

    public function getbacklinkdomains($link_domain_id=0, $user_id)
    {
    	$query1 = "select backlink_domain_id from link_page where link_domain_id ='".$link_domain_id."'";
    	$query1 = $this->db->query($query1);
    	if ($query1) {
    		$res = $query1->result_array();
          	$temp = array();
    		foreach($res as $r){
    			$temp[] = $r['backlink_domain_id'];
    		}
    		$res = implode(',', $temp);
    	}else{
    		$res = 0;
    	}
        if($res) {
            $query = "select * from backlink_domain where user_id='".$user_id."' and id not in (".$res.")";    
        }else{
            $query = "select * from backlink_domain where user_id='".$user_id."'";    
        }
    	
    	$query = $this->db->query($query);
    	if ($query) {
    		$res = $query->result_array();
    		return $res;
    	} else {
    		return false;
    	}
    }

}
