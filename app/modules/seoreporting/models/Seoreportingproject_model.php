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

class Seoreportingproject_model extends CI_Model
{
   
    private $table_name = 'seo_reporting_project';

    public function __construct()
    {
        
    }
    public function getProjectById($projectId) 
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('id=', $projectId);
        $rows = $this->db->get();
        if($rows){
            return $rows->row_array();
        }else{
            return false;
        }
    }
    public function getProjectsByUserId($userId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('user_id=', $userId);
        $this->db->where('status!=', 'DELETED');
        $rows = $this->db->get();
        if($rows){
            return $rows->result_array();
        }else{
            return false;
        }
    }
}