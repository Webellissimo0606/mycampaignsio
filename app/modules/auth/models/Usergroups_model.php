<?php
/**
 * CIMembership
 * 
 * @package		Modules
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usergroups_model extends CI_Model
{
	private $table_name			= 'users';			// user accounts
	private $profile_table_name	= 'user_profiles';	// user profiles
	private $groups_table_name	= 'users_groups';	// user roles

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this->table_name			= $ci->config->item('db_table_prefix').$this->table_name;
		$this->profile_table_name	= $ci->config->item('db_table_prefix').$this->profile_table_name;
		$this->groups_table_name	= $ci->config->item('db_table_prefix').$this->groups_table_name;
	}
	
	/**
	 * List all user groups
	 *
	 */
	function usergroups() {
		$query = $this->db->get($this->groups_table_name);
		return $query->result();
	}
	
	/**
	 * Add New User group
	 *
	 */
	 function newusergroup($data) {
		$data['created_at'] = date('Y-m-d H:i:s');
		if ($this->db->insert($this->groups_table_name, $data)) {
			$user_group_id = $this->db->insert_id();
			if(isset($user_group_id) && $user_group_id!='') { 
			return $user_group_id; 
			} else { return false; }
		}
		return NULL;
	 }
	 
	 /*
	 * Get group data by ID
	 */
	 function groupdata_by_id($group_id) {
		$this->db->where('id=', $group_id);

		$query = $this->db->get($this->groups_table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	 }


	
	/**
	 * Edit User group
	 *
	 */
	 function updateusergroup($data, $group_id) {
		 if(!isset($group_id) || $group_id=='') { return false; }
		$data['last_updated'] = date('Y-m-d H:i:s');
		$this->db->where('id=', $group_id);
		if ($this->db->update($this->groups_table_name, $data)) {
			$affected_rows = $this->db->affected_rows();
			if($affected_rows==1) { 
			return $group_id; 
			} else { return false; }
		}
		return NULL;
	 }
	 
	 /**
	 * Delete User group
	 */
	 function deleteusergroup($group_id) 
	 {
		if(!isset($group_id) || $group_id=='') { return false; }
		
		/* Delete users / users profile in this user group */
		$this->db->query('DELETE u.*, p.* FROM '.$this->table_name.' u LEFT JOIN '.$this->profile_table_name.' p ON u.id = p.user_id WHERE u.gid = '.$group_id);
		
		$this->db->where('id', $group_id);
		$this->db->delete($this->groups_table_name);
		if ($this->db->affected_rows() > 0) {
			return TRUE;
		}
		return FALSE;
	 }
	 function getusergroupsbyname($name){
        $trimmed_name=trim($name);
            $this->db->select('*');
               $this->db->from($this->groups_table_name);    
               $this->db->where('name', $trimmed_name);
               $query = $this->db->get();               
               return $query->row();
         }
        function getusergroupbyid($id){
            $trimmed_id=trim($id);
            $this->db->select('*');
            $this->db->from($this->groups_table_name);    
            $this->db->where('id', $trimmed_id);
            $query = $this->db->get();               
            return $query->row();
        }
	 
}

/* End of file usergroups_model.php */
/* Location: ./models/usergroups_model.php */