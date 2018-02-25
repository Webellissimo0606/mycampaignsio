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

class Seoreportingtask_model extends CI_Model
{
   
    private $table_name = 'seo_reporting_task';

    public function __construct()
    {
        
    }

    function getTaskByUserId($userId, $filter = '')
    {
        if ($filter != '') {
            $sql = "select t.*,p.*,p.id as project_id,t.id as task_id from seo_reporting_task t join seo_reporting_project p on p.id=t.project_id join seo_reporting_tasklog tl on tl.task_id=t.id
            where p.user_id='".$userId."' and tl.status='".$filter."' order by t.modified desc";
        } else {
            $sql = "select t.*,p.*,p.id as project_id,t.id as task_id from seo_reporting_task t join seo_reporting_project p on p.id=t.project_id 
            where p.user_id='".$userId."' order by t.modified desc";
        }
        $query   = $this->db->query($sql);
        $result = $query->result_array($query);
        return $result;
    }

    public function getTaskByProjectId($projectId)
    {
    	$this->db->flush_cache();
    	$this->db->select('*');
    	$this->db->from($this->table_name);
    	$this->db->where('project_id=', $projectId);
    	$this->db->order_by('id', 'desc');
    	$query = $this->db->get();
    	$rows  = $query->result_array();
    	if ($rows) {
    	    return $rows;
    	} else {
    	    return false;
    	}
    }

    public function getTaskByTaskId($taskId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('id=', $taskId);
        $query = $this->db->get();
        $rows  = $query->row_array();
        if ($rows) {
            return $rows;
        } else {
            return false;
        }        
    }
}