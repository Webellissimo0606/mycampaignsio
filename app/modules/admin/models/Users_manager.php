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

class Users_manager extends CI_Model
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

	function count_users() {
		$this->db->select($this->table_name.'.id as uid,'.$this->table_name.'.*, '.$this->groups_table_name.'.*,'.$this->profile_table_name.'.*');
		$this->db->from($this->table_name);
		$this->db->join($this->groups_table_name, $this->table_name.'.gid = '.$this->groups_table_name.'.id');
		$this->db->join($this->profile_table_name, $this->table_name.'.id = '.$this->profile_table_name.'.user_id');
		$query = $this->db->get();
		return count($query->result());
	}

	/**
	 * List all user groups
	 *
	 */
	function count_user_groups() {
		$query = $this->db->get($this->groups_table_name);
		return count($query->result());
	}

	function user_roles_list() {
		$this->db->select('id, name');
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$query = $this->db->get($this->groups_table_name);
		foreach($query->result_array() as $row){
		$return[$row['id']] = $row['name'];
		}
		return $return;
	}

}