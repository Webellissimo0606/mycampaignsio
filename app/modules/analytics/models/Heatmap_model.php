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

class Heatmap_model extends CI_Model {

    private $table_name = 'heatmaps';

    function __construct() {
        parent::__construct();
    }

    public function getAllByUserId($userId, $domainId)
    {
    	$this->db->flush_cache();
    	$this->db->select('*');
    	$this->db->from($this->table_name);
        $this->db->where('user_id=', $userId);
    	$this->db->where('domain_id=', $domainId);
    	$query = $this->db->get();
    	if($query) {
    		return $query->result_array();
    	} else {
    		return false;
    	}
    }

    public function getFirstByDomainId($domainId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('domain_id=', $domainId);
        $query = $this->db->get();
        if($query) {
            return $query->row_array();
        } else {
            return false;
        }       
    }

    public function getById($id)
    {
    	$this->db->flush_cache();
    	$this->db->select('*');
    	$this->db->from($this->table_name);
    	$this->db->where('id=', $id);
    	$query = $this->db->get();
    	if($query) {
    		return $query->row_array();
    	} else {
    		return false;
    	}
    }
 }   