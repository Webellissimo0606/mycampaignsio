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

class Groups_model extends CI_Model
{

    private $table_name = 'groups';

    public function __construct()
    {

    }

    public function getGroupsByDomainId($domainId)
    {
        $this->db->flush_cache();
        $this->db->select('g.id');
        $this->db->from("groups g");
        $this->db->join("domain_groups AS dg", "dg.group_id=g.id");
        $this->db->where('dg.domain_id', $domainId);
        $query = $this->db->get();
        if($query){
            $result = $query->result_array();
            $return = array();
            foreach ($result as $res) {
                $return[] = $res['id'];
            }
            return $return;
        }else{
            return array();
        }
    }

    public function getGroupsByUserId($userId)
    {
        $this->db->flush_cache();
        $this->db->from("groups as g");
        $this->db->where('g.user_id', $userId);
        $this->db->select('*');
        $query = $this->db->get();
        if($query){
            return $query->result_array();
        }else{
            return false;
        }
    }

    public function assignDomainToGroup($domainId, $groups)
    {
        $this->db->flush_cache();
        $this->db->where('domain_id', $domainId);
        $this->db->delete('domain_groups');
        if( ! empty($groups) ){
            foreach ($groups as $group) {
                $this->db->flush_cache();
                $data = array();
                $data['group_id']   = $group;
                $data['domain_id'] = $domainId;
                $data['created']   = date('Y-m-d H:i:s');
                $this->db->insert('domain_groups', $data);
            }
        }
    }

    public function deleteGroups($groupId, $userId)
    {
        $this->db->flush_cache();
        $this->db->where('user_id=', $userId);
        $this->db->where('id=', $groupId);
        $this->db->delete('groups');

    }

    public function addGroups($data, $userId)
    {
        $this->db->flush_cache();
        $array = array();
        $array['group_name'] = $data['group_name'];
        $array['user_id'] = $userId;
        $array['created'] = date('Y-m-d H:i:s');
        $this->db->insert('groups', $array);
    }

    public function getGroupByGroupId($groupId)
    {
        $this->db->flush_cache();
        $this->db->select("g.*");
        $this->db->from('groups as g');
        $this->db->where('g.id', $groupId);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }  
    }

    public function getGroupDetailByGroupId($groupId, $limit = 7, $page = '')
    {
        $this->db->flush_cache();
        $this->db->select("count(*) as total_rows");
        $this->db->from('groups as g');
        $this->db->join("domain_groups AS dg", "dg.group_id=g.id");
        $this->db->join("domains AS d", "d.id=dg.domain_id");
        $this->db->where('g.id', $groupId);
        $query = $this->db->get();
        if ($query) {
           $total = $query->row_array(); 
        } else {
            $total = 0;
        }

        $this->db->flush_cache();
        $this->db->select("d.*,dg.domain_id,g.user_id");
        $this->db->from('groups as g');
        $this->db->join("domain_groups AS dg", "dg.group_id=g.id");
        $this->db->join("domains AS d", "d.id=dg.domain_id");
        $this->db->where('g.id', $groupId);
        $this->db->select('d.*');
        if ($limit != '') {
            $offset = ($page - 1) * $limit;
            $this->db->limit($limit,$offset);
        } else {
            $this->db->limit(7);
        }
        $query = $this->db->get();
        if ($query) {
           $result =  $query->result_array(); 
           $return = array();
           foreach($result as $key=>$res){
                $return[$key] = $res;
                $return[$key]['currentpage'] = $page;
                $return[$key]['offset'] = $offset;
                $return[$key]['total_rows'] = $total;

           }
           return $return;
        } else {
            return false;
        }

        

    }

    public function getDomainsByGroupId($groupId)
    {
        $this->db->flush_cache();
        $this->db->from("groups as g");

        $this->db->where('g.id', $groupId);
        $this->db->select('*');
        $query = $this->db->get();
        if($query){
           return $query->result_array();     
        }else{
            return false;
        }
        

    }

    public function getDomainsIdsByGroupId($groupId)
    {
        $this->db->flush_cache();
        $this->db->from("groups as g");
        $this->db->join("domain_groups AS dg", "dg.group_id=g.id");
        $this->db->where('g.id', $groupId);
        $this->db->select('*');
        $query = $this->db->get();
        if($query){
           $return  = array();
           $result = $query->result_array();     
           foreach($result as $res){
            $return[] = $res['domain_id'];
           }
           return $return;
        }else{
            return false;
        }
        

    }



    public function updategroups($data)
    {
        $this->db->flush_cache();
        $array = array();
        $array['group_name'] = $data['group_name'];
        $array['modified'] = date('Y-m-d H:i:s');
        $this->db->where('id', $data['groupid']);
        $this->db->update('groups', $array);
    }

    public function getTotalDomainsBygroupId($groupId)
    {
         $this->db->flush_cache();   
         $this->db->from('groups as g');
         $this->db->join("domain_groups AS dg", "dg.group_id=g.id");
         $this->db->select('count(dg.id) as totalDomain');
         $this->db->where("g.id",$groupId);
         $query = $this->db->get();
         if($query){
            return $query->row_array();   
         }else{
            return false;
         }
         


    }
    public function getTotalUpdomainsBygroupId($groupId)
    {
         $this->db->flush_cache();   
         $this->db->from('groups as g');
         $this->db->join("domain_groups AS dg", "dg.group_id=g.id");
         $this->db->join("domains AS d", "d.id=dg.domain_id");
         $this->db->select('count(dg.id) as totalUpdomains');
         $this->db->where("g.id",$groupId);
         $this->db->where("d.server_status",'UP');
         $query = $this->db->get();
         if($query){
            return $query->row_array();   
         }else{
            return false;
         }
         


    }





}