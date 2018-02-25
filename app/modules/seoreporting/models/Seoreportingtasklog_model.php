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

class Seoreportingtasklog_model extends CI_Model
{

    private $table_name = 'seo_reporting_tasklog';

    public function __construct()
    {

    }

    public function getLatestJobByJobId($jobId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('task_id=', $jobId);
        $this->db->order_by('id', 'desc');
        $this->db->limit('1');
        $query = $this->db->get();
        $rows  = $query->row_array();
        if ($rows) {
            return $rows;
        } else {
            return false;
        }
    }

    public function getTaskLogById($id)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('id=', $id);
        $this->db->limit('1');
        $query = $this->db->get();
        $rows  = $query->row_array();
        if ($rows) {
            return $rows;
        } else {
            return false;
        }   
    }

    

}
