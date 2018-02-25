<?php
/**
 * CIMembership
 *
 * @package		Modules
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Users extends CI_Model {
	private $table_name = 'users'; // user accounts
	private $profile_table_name = 'user_profiles'; // user profiles
	private $groups_table_name = 'users_groups'; // user roles

	function __construct() {
		parent::__construct();
		$ci = &get_instance();
		$this->table_name = $ci->config->item('db_table_prefix') . $this->table_name;
		$this->profile_table_name = $ci->config->item('db_table_prefix') . $this->profile_table_name;
		$this->groups_table_name = $ci->config->item('db_table_prefix') . $this->groups_table_name;
	}

	function listallusers() {
		$this->db->select($this->table_name . '.id as uid,' . $this->table_name . '.*, ' . $this->groups_table_name . '.*,' . $this->profile_table_name . '.*');
		$this->db->from($this->table_name);
		$this->db->join($this->groups_table_name, $this->table_name . '.gid = ' . $this->groups_table_name . '.id');
		$this->db->join($this->profile_table_name, $this->table_name . '.id = ' . $this->profile_table_name . '.user_id');
		$query = $this->db->get();
		return ($query->result());
	}

	/**
	 * List all user groups
	 *
	 */
	function usergroups() {
		$query = $this->db->get($this->groups_table_name);
		return $query->result();
	}

	function user_roles_list() {
		$this->db->select('id, name');
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$query = $this->db->get($this->groups_table_name);
		foreach ($query->result_array() as $row) {
			$return[$row['id']] = $row['name'];
		}
		return $return;
	}

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_id($user_id, $activated) {
		$this->db->where('id', $user_id);
		$this->db->where('activated', $activated ? 1 : 0);
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return $query->row();
		}

		return NULL;
	}

	/**
	 * Get user record by login (username or email)
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login($login) {
		$this->db->where('LOWER(username)=', strtolower($login));
		$this->db->or_where('LOWER(email)=', strtolower($login));
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return $query->row();
		}

		return NULL;
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username) {
		$this->db->where('LOWER(username)=', strtolower($username));
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return $query->row();
		}

		return NULL;
	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email) {
		$this->db->where('LOWER(email)=', strtolower($email));
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return $query->row();
		}

		return NULL;
	}

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username) {
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));
		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email) {
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		$this->db->or_where('LOWER(new_email)=', strtolower($email));
		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Admin Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data, $user_profile_data) {
		$data['created'] = date('Y-m-d H:i:s');
		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();
			$this->create_profile($user_id, $user_profile_data);
			return array('user_id' => $user_id);
		}
		return NULL;
	}

	/**
	 * Register User
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function register($data, $activated = TRUE) {
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;
		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();
			if ($activated) {
				$this->create_profile($user_id);
			}

			return array('user_id' => $user_id);
		}
		return NULL;
	}
	/**
	 * Register User
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function addMasterStaff($params, $activated = TRUE) {
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;
		$this->db->insert('staff', $params);
		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();
			if ($activated) {
				$this->create_profile($user_id);
			}

			return array('user_id' => $user_id);
		}
		return NULL;
	}
	/**
	 * Activate user if activation key is valid.
	 * Can be called for not activated users only.
	 *
	 * @param	int
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function activate_user($user_id, $activation_key, $activate_by_email) {
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		if ($activate_by_email) {
			$this->db->where('new_email_key', $activation_key);
		} else {
			$this->db->where('new_password_key', $activation_key);
		}
		$this->db->where('activated', 0);
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			$this->db->set('activated', 1);
			$this->db->set('new_email_key', NULL);
			$this->db->where('id', $user_id);
			$this->db->update($this->table_name);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	function purge_na($expire_period = 172800) {
		$this->db->where('activated', 0);
		$this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
		$this->db->delete($this->table_name);
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	function delete_user($user_id) {
		$this->db->where('id', $user_id);
		$this->db->delete($this->table_name);
		if ($this->db->affected_rows() > 0) {
			$this->delete_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Set new password key for user.
	 * This key can be used for authentication when resetting user's password.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set_password_key($user_id, $new_pass_key) {
		$this->db->set('new_password_key', $new_pass_key);
		$this->db->set('new_password_requested', date('Y-m-d H:i:s', time()));
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function can_reset_password($user_id, $new_pass_key, $expire_period = 900) {
		$this->db->select('new_password_requested');
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		//$this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(new_password_requested, '%Y-%m-%d %H:%i%s')) >", time() - $expire_period);
		$query = $this->db->get($this->table_name);
		$result = $query->result();
		if (!empty($result)) {
			$requested_time = ($result[0]->new_password_requested);
			if (strtotime($requested_time) > time() - $expire_period) {
				return TRUE;
			} else {
				return FALSE;
			}

		} else {
			return FALSE;
		}
		return FALSE;
	}

	/**
	 * Change user password if password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900) {
		$this->db->select('new_password_requested');
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		//$this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(new_password_requested, '%Y-%m-%d %H:%i%s')) >", time() - $expire_period);
		$query = $this->db->get($this->table_name);
		$result = $query->result();
		if (!empty($result)) {
			$requested_time = ($result[0]->new_password_requested);
			if (strtotime($requested_time) > time() - $expire_period) {
				/* set new password */
				$this->db->set('password', $new_pass);
				$this->db->set('new_password_key', NULL);
				$this->db->set('new_password_requested', NULL);
				$this->db->where('id', $user_id);
				$this->db->where('new_password_key', $new_pass_key);
				//$this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);
				$this->db->update($this->table_name);
				return $this->db->affected_rows() > 0;

			} else {
				return FALSE;
			}

		} else {
			return FALSE;
		}
		return FALSE;

	}

	/**
	 * Change user password
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function change_password($user_id, $new_pass) {
		$this->db->set('password', $new_pass);
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function set_new_email($user_id, $new_email, $new_email_key, $activated) {
		$this->db->set($activated ? 'new_email' : 'email', $new_email);
		$this->db->set('new_email_key', $new_email_key);
		$this->db->where('id', $user_id);
		$this->db->where('activated', $activated ? 1 : 0);
		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Activate new email (replace old email with new one) if activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key) {
		$this->db->set('email', 'new_email', FALSE);
		$this->db->set('new_email', NULL);
		$this->db->set('new_email_key', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_email_key', $new_email_key);
		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time) {
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);
		if ($record_ip) {
			$this->db->set('last_ip', $this->input->ip_address());
		}

		if ($record_time) {
			$this->db->set('last_login', date('Y-m-d H:i:s'));
		}

		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
	}

	/**
	 * Ban user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function ban_user($user_id, $reason = NULL) {
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned' => 1,
			'ban_reason' => $reason,
		));
	}

	/**
	 * Unban user
	 *
	 * @param	int
	 * @return	void
	 */
	function unban_user($user_id) {
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned' => 0,
			'ban_reason' => NULL,
		));
	}

	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	function create_profile($user_id, $user_profile_data) {
		$user_profile_data['user_id'] = $user_id;
		$this->db->insert($this->profile_table_name, $user_profile_data);
		$user_profile_id = $this->db->insert_id();
		if (isset($user_profile_id)) {return TRUE;}
		return FALSE;
	}

	/**
	 * Delete user profile
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_profile($user_id) {
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->profile_table_name);
	}

	/**
	 * Get group id by user id
	 *
	 * @param	int
	 * @return	void
	 */
	public function group_by_user_id($user_id) {
		$this->db->where('id=', $user_id);
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			return $query->row()->gid;
		}

		return NULL;
	}

	public function groupname_by_user_id($user_id) {
		$this->db->where('id=', $user_id);
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) {
			$gid = $query->row()->gid;
			$this->db->select('id, name');
			$this->db->where('status', 1);
			$this->db->where('id', $gid);
			$query = $this->db->get($this->groups_table_name);
			return $query->row()->name;
		}
		return NULL;
	}

	public function user_data($user_id) {
		$this->db->select('*');
		$this->db->where($this->table_name . '.id=', $user_id);
		$this->db->from($this->table_name);
		$this->db->join($this->profile_table_name, $this->table_name . '.id = ' . $this->profile_table_name . '.user_id');
		$query = $this->db->get();
		return ($query->result());
	}

	public function edit_useraccount($user_id, $user_data) {
		if ($user_id) {
			$this->db->where('id', $user_id);
			$this->db->update($this->table_name, $user_data);
			return TRUE;
		}
		return FALSE;
	}

	public function edit_userprofile($user_id, $user_profile_data) {
		if ($user_id) {
			$this->db->where('user_id', $user_id);
			$this->db->update($this->profile_table_name, $user_profile_data);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function social_create_user($data, $activated = TRUE) {
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;
		$data['gid'] = $this->config->item('new_user_group');
		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();

			return array('user_id' => $user_id);
		}
		return NULL;
	}
	function get_client_users($user_group_id) {
		$trimmed_id = trim($user_group_id);
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('gid', $trimmed_id);
		$query = $this->db->get();
		return $query->result();
	}
}
/* End of file users.php */
/* Location: ./models/users.php */