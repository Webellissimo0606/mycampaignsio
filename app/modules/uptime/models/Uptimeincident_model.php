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

class Uptimeincident_model extends CI_Model {

    

    function __construct() {
        parent::__construct();
    }

    public function findLastTimeUpDomain($domainId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('uptime_incidents');
        $this->db->where('domain_id =', $domainId);
        $this->db->order_by('created_at', 'desc');
        $this->db->limit("1");
        $query = $this->db->get();
        return ($query->row_array());
    }

    public function getDownIncidentsByDomainId($domainId)
    {
        $query = "select * from uptime_incidents ui join domains as d on d.id=ui.domain_id where
        ui.downtime is not null and ui.domain_id='".$domainId."' order by ui.domain_id
        ";
        $query = $this->db->query($query);
        if ($query) {
           $downtime = $query->result_array();
            return $downtime;     
        } else {
            return false;
        }
        
        
    }

    public function getDownIncidentsByUserId($userId)
    {
        $query = "select * from uptime_incidents ui join domains d on d.id=ui.domain_id join user_domain ud on ud.domain_id=ui.domain_id where ui.downtime is not null and ud.user_id='".$userId."' and d.server_status='DOWN' group by d.id order by downtime desc  limit 20";
        $query = $this->db->query($query);
        if ($query) {
           $downtime = $query->result_array();     
        } else {
            $downtime = false;
        }
        return $downtime;
    }

    public function getOverallIncidentsByUserId($days, $userId)
    {
        $date   = date('Y-m-d', strtotime('-' . $days . ' day', time()));
        $currentDate   = date('Y-m-d', strtotime('-0 day', time()));

        // $query = "select count(*) as overalloutage from uptime_incidents ui join domains d on d.id=ui.domain_id join user_domain ud on ud.domain_id=ui.domain_id where (ui.downtime is not null and ud.user_id='".$userId."' and date(ui.updated_at) <='".$currentDate."' and date(ui.updated_at)>='".$date."'   ) or (ui.downtime is not null and ui.uptime is null and ud.user_id='".$userId."')";
        $query = "select count(*) as overalloutage from uptime_incidents ui join domains d on d.id=ui.domain_id join user_domain ud on ud.domain_id=ui.domain_id where (ui.downtime is not null and ud.user_id='".$userId."' and date(ui.updated_at) <='".$currentDate."' and date(ui.updated_at)>='".$date."'   )";
        $query = $this->db->query($query);
        if ($query) {
           return $query->row_array();     
        } else {
            return false;
        }
        
    }
    public function getOverallIncidentsByUserIdMonthly($days, $userId)
    {
        $date   = date('Y-m-d', strtotime('-' . $days . ' day', time()));
        $date1   = date('Y-m-d', strtotime('-0 day', time()));
        $query = "select count(*) as overalloutage from uptime_incidents ui join domains d on d.id=ui.domain_id join user_domain ud on ud.domain_id=d.id where (ui.downtime is not null and ud.user_id='".$userId."' and date(ui.updated_at) >='".$date1."' and date(ui.updated_date <= '".$date."')) or (ui.downtime is not null and ui.uptime is null and ud.user_id='".$userId."')";
        $query = $this->db->query($query);
        if ($query) {
           return $query->row_array();     
        } else {
            return false;
        }
    }

    public function countIncidentsByDomainId($domainId, $days)
    {
      
        $date   = date('Y-m-d', strtotime('-' . $days . ' day', time()));
        $query = "select count(*) as incidents,  (SUM(TIMESTAMPDIFF(SECOND,COALESCE(downtime,0),COALESCE(uptime,CURRENT_TIMESTAMP)))) AS totaloutagetime from uptime_incidents ui where 
        (ui.domain_id='".$domainId."' and ui.downtime is not null and date(ui.updated_at) = '".$date."') or (ui.downtime is not null and ui.uptime is null and ui.domain_id='".$domainId."')
        ";
        $query = $this->db->query($query);
        if ($query) {
           return $query->row_array();     
        } else {
            return false;
        }
    }

}

?>