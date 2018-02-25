<?php 
/**
 * CIMembership
 * 
 * @package		Libraries
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

class Ci_auth
{
	private $error = array();

	function __construct()
	{
		session_start();
		$this->ci =& get_instance();
		$this->ci->load->library('phpass');
		$this->ci->load->library('session');
		$this->ci->load->database();
		$this->ci->load->model('auth/users');
		$this->ci->load->model('auth/usergroups_model');
		$this->ci->load->model('admin/users_manager');

		// Try to autologin
		$this->autologin();
	}

	/**
	 * Login user automatically if he/she provides correct autologin verification
	 *
	 * @return	void
	 */
	private function autologin()
	{
		if (!$this->is_logged_in() AND !$this->is_logged_in(FALSE)) {			// not logged in (as any user)

			$this->ci->load->helper('cookie');
			if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name'), TRUE)) {

				$data = unserialize($cookie);

				if (isset($data['key']) AND isset($data['user_id'])) {

					$this->ci->load->model('auth/user_autologin');
					if (!is_null($user = $this->ci->user_autologin->get($data['user_id'], md5($data['key'])))) {

						// Login user
						$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->username,
								'parent_id'	=> $user->parent_id,
								'status'	=> STATUS_ACTIVATED,
						));

						// Renew users cookie to prevent it from expiring
						set_cookie(array(
								'name' 		=> $this->ci->config->item('autologin_cookie_name'),
								'value'		=> $cookie,
								'expire'	=> $this->ci->config->item('autologin_cookie_life'),
						));

						$this->ci->users->update_login_info(
								$user->id,
								$this->ci->config->item('login_record_ip'),
								$this->ci->config->item('login_record_time'));
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	/**
	 * Check if login attempts exceeded max login attempts (specified in config)
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_max_login_attempts_exceeded($login)
	{
		if ($this->ci->config->item('login_count_attempts')) {
			$this->ci->load->model('auth/login_attempts');
			return $this->ci->login_attempts->get_attempts_num($this->ci->input->ip_address(), $login)
					>= $this->ci->config->item('login_max_attempts');
		}
		return FALSE;
	}

	/**
	 * Increase number of attempts for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function increase_login_attempt($login)
	{
		if ($this->ci->config->item('login_count_attempts')) {
			if (!$this->is_max_login_attempts_exceeded($login)) {
				$this->ci->load->model('auth/login_attempts');
				$this->ci->login_attempts->increase_attempt($this->ci->input->ip_address(), $login);
			}
		}
	}


	/**
	 * Clear all attempt records for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function clear_login_attempts($login)
	{
		if ($this->ci->config->item('login_count_attempts')) {
			$this->ci->load->model('auth/login_attempts');
			$this->ci->login_attempts->clear_attempts(
					$this->ci->input->ip_address(),
					$login,
					$this->ci->config->item('login_attempt_expire'));
		}
	}

	/**
	 * Check if user logged in. Also test if user is activated or not.
	 *
	 * @param	bool
	 * @return	bool
	 */
	function is_logged_in($activated = TRUE)
	{

		return $this->ci->session->userdata('status') === ($activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);
	}


	/**
	 * Get user_id
	 *
	 * @return	string
	 */
	function get_user_id()
	{
		return $this->ci->session->userdata('user_id');
	}

	/**
	 * Get username
	 *
	 * @return	string
	 */
	function get_username()
	{
		return $this->ci->session->userdata('username');
	}
	
	/**
	 * Get name
	 *
	 * @return	string
	 */
	function username()
	{
		$user_id = $this->get_user_id();
		$user_data = $this->ci->users->user_data($user_id);

		if( ! empty( $user_data ) ){
			return $user_data[0]->first_name.' '.$user_data[0]->last_name;
		}

		return '';
		
	}
	
	/**
	 * Get name
	 *
	 * @return	string
	 */
	function useremail()
	{
		$user_id = $this->get_user_id();
		$user_data = $this->ci->users->user_data($user_id);
		/* There is an issue while get the admin avatar image - fixed on v1.3.1
		$name = $user_data[0]->first_name.' '.$user_data[0]->email;
		return $name;*/
		return $user_data[0]->email;
	}
	/**
	 * Get user group name
	 *
	 * @return	string
	 */
	function usergroup()
	{
		$user_id = $this->get_user_id();
		$user_data = $this->ci->users->groupname_by_user_id($user_id);
		return $user_data;
		
	}
	
	/**
	 * Get user group id
	 *
	 * @return	ID
	 */
	function usergroup_id()
	{
		$user_id = $this->get_user_id();
		$user_data = $this->ci->users->group_by_user_id($user_id);
		return $user_data;
		
	}

	/**
	 * Login user on the site. Return TRUE if login is successful
	 * (user exists and activated, password is correct), otherwise FALSE.
	 *
	 * @param	string	(username or email or both depending on settings in config file)
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function login($login, $password, $remember, $login_by_username, $login_by_email, $fromapi = false)
	{
		if ((strlen($login) > 0) AND (strlen($password) > 0)) {

			// Which function to use to login (based on config)
			if ($login_by_username AND $login_by_email) {
				$get_user_func = 'get_user_by_login';
			} else if ($login_by_username) {
				$get_user_func = 'get_user_by_username';
			} else {
				$get_user_func = 'get_user_by_email';
			}

			if (!is_null($user = $this->ci->users->$get_user_func($login))) {	// login ok

				$passwordTrue = false;
				if($fromapi == true){
					if($password == $user->password){
						$passwordTrue = true;
					}else{
						$passwordTrue = false;
					}
				}else {
					$passwordTrue = $this->ci->phpass->check_password($password, $user->password);	
				}
				

				// Does password match hash in database?
				if ($passwordTrue) {		// password ok

					if ($user->banned == 1) {									// fail - banned
						$this->error = array('banned' => $user->ban_reason);

					} else {
						$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->username,
								'parent_id'	=> $user->parent_id,
								'status'	=> ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
						));

						if ($user->activated == 0) {							// fail - not activated
							$this->error = array('not_activated' => '');

						} else {												// success
							if ($remember) {
								$this->create_autologin($user->id);
							}

							$this->clear_login_attempts($login);

							$this->ci->users->update_login_info(
									$user->id,
									$this->ci->config->item('login_record_ip'),
									$this->ci->config->item('login_record_time'));
							return TRUE;
						}
					}
				} else {														// fail - wrong password
					$this->increase_login_attempt($login);
					$this->error = array('password' => 'auth_incorrect_password');
				}
			} else {															// fail - wrong login
				$this->increase_login_attempt($login);
				$this->error = array('login' => 'auth_incorrect_login');
			}
		}
		return FALSE;
	}

	/**
	 * Check if user logged in. Also test if user is activated or not.
	 *
	 * @param	bool
	 * @return	bool
	 */
	function canDo($key)
	{ 
		$user_id = $this->get_user_id();
		$user_group_id = $this->ci->users->group_by_user_id($user_id);
		$group_data=$this->ci->usergroups_model->groupdata_by_id($user_group_id);
		if(!empty($group_data)) { /* if group exists */
			if($group_data->status==1) { /* if group is active */
				$g_permissions=json_decode($group_data->permissions);
				if(!empty($g_permissions)) { /* if group has permissions */ 
					if(isset($g_permissions->$key) && $g_permissions->$key==1) { /* check permission with key  */ 
						return TRUE;
					} else {
						return FALSE;
					}
				} else {  /* if group permission is empty  */ 
					return FALSE;
				}
			} else { /* if group is inactive */ 
				return FALSE;
			}	
		} else { /* if group not exists */ return FALSE; }
	}
	
	public function is_superadmin() {
		$user_id = $this->get_user_id();
		$user_group_id = $this->ci->users->group_by_user_id($user_id);
		if($user_group_id==1) { return TRUE; } else {  return FALSE; }
	}
	
	public function is_admin() {
		$user_id = $this->get_user_id();
		$user_group_id = $this->ci->users->group_by_user_id($user_id);
		if($user_group_id==2) { return TRUE; } else {  return FALSE; }
	}

	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_autologin($user_id)
	{
		$this->ci->load->helper('cookie');
		$key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);

		$this->ci->load->model('auth/user_autologin');
		$this->ci->user_autologin->purge($user_id);

		if ($this->ci->user_autologin->set($user_id, md5($key))) {
			set_cookie(array(
					'name' 		=> $this->ci->config->item('autologin_cookie_name'),
					'value'		=> serialize(array('user_id' => $user_id, 'key' => $key)),
					'expire'	=> $this->ci->config->item('autologin_cookie_life'),
			));
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Clear user's autologin data
	 *
	 * @return	void
	 */
	private function delete_autologin()
	{
		$this->ci->load->helper('cookie');
		if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name'), TRUE)) {

			$data = unserialize($cookie);

			$this->ci->load->model('auth/user_autologin');
			$this->ci->user_autologin->delete($data['user_id'], md5($data['key']));

			delete_cookie($this->ci->config->item('autologin_cookie_name'));
		}
	}

	/**
	 * Logout user from the site
	 *
	 * @return	void
	 */
	function logout()
	{
		$this->delete_autologin();
		$this->ci->session->set_userdata(array('user_id' => '', 'username' => '', 'status' => ''));
		
		$user_data = $this->ci->session->all_userdata();

		foreach ($user_data as $key => $value) {
            $this->ci->session->unset_userdata($key);
        }
        $this->ci->session->unset_userdata('temp_imp_session');
		// Destroy and Create the session
		$this->ci->session->sess_destroy();
		session_start();
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->ci->session->sess_create();
		}
		else
		{
			$this->ci->session->sess_regenerate(TRUE);
		}
		return TRUE;	
	}

	function impersonate($user_id)
	{

		//will need to set the new cookie for user and store old cookie for user
		$this->ci =& get_instance();

		$this->ci->load->helper('cookie');
		$key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);

		$currentsession = $this->ci->session->get_userdata();
		$user_data = $this->ci->session->all_userdata();

		foreach ($user_data as $key => $value) {
			if($key!='__ci_last_regenerate' && $key != 'user_id' && $key!='username' && $key!='status'){
				$this->ci->session->unset_userdata($key);	
			}
            
        }	
		$this->ci->session->unset_userdata('temp_imp_session');
		$this->ci->session->set_userdata('temp_imp_session',$this->ci->session->userdata());
		

		$this->ci->load->model('auth/user_autologin');
		
		if (!is_null($user = $this->ci->user_autologin->get($user_id, md5($key)))) {

			// Login user
			$this->ci->session->set_userdata(array(
					'user_id'	=> $user->id,
					'username'	=> $user->username,
					'status'	=> '1',
					'parent_id' => $user->parent_id
			));

			// Renew users cookie to prevent it from expiring
			set_cookie(array(
					'name' 		=> $this->ci->config->item('autologin_cookie_name'),
					'value'		=> $cookie,
					'expire'	=> $this->ci->config->item('autologin_cookie_life'),
			));

			$this->ci->users->update_login_info(
					$user->id,
					$this->ci->config->item('login_record_ip'),
					$this->ci->config->item('login_record_time'));
			
		}
		
		// return FALSE;
		$this->ci->load->model('auth/analyze_model');
		$userinfo = $this->ci->analyze_model->getusersinfo($user_id);
		$data['__ci_last_regenerate'] = time();
		$data['user_id'] = $userinfo[0]->id;
		$data['username'] = $userinfo[0]->username; 
		$data['parent_id'] = $userinfo[0]->parent_id; 
		$data['status'] = '1';

		$this->ci->session->set_userdata($data);
		

		redirect(site_url('/auth/home'));

	}

	function logbackin()
	{

		$this->ci =& get_instance();
		$tempsession = $this->ci->session->userdata('temp_imp_session');
		
		
		$this->ci->load->helper('cookie');
		$key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);

		$this->ci->load->model('auth/user_autologin');
		
		if (!is_null($user = $this->ci->user_autologin->get($tempsession['user_id'], md5($key)))) {

			// Login user
			$this->ci->session->set_userdata(array(
					'user_id'	=> $user->id,
					'username'	=> $user->username,
					'parent_id'	=> $user->parent_id,
					'status'	=> '1',
			));

			// Renew users cookie to prevent it from expiring
			set_cookie(array(
					'name' 		=> $this->ci->config->item('autologin_cookie_name'),
					'value'		=> $cookie,
					'expire'	=> $this->ci->config->item('autologin_cookie_life'),
			));

			$this->ci->users->update_login_info(
					$user->id,
					$this->ci->config->item('login_record_ip'),
					$this->ci->config->item('login_record_time'));
			
		}
		
		// return FALSE;
		$this->ci->load->model('auth/analyze_model');
		$userinfo = $this->ci->analyze_model->getusersinfo($tempsession['user_id']);
		$data['__ci_last_regenerate'] = time();
		$data['user_id'] = $userinfo[0]->id;
		$data['username'] = $userinfo[0]->username; 
		$data['parent_id'] = $userinfo[0]->parent_id; 
		$data['status'] = '1';
		$this->ci->session->set_userdata($data);
		$this->ci->session->unset_userdata('temp_imp_session');
		redirect(site_url('/admin/dashboard'));


	}

	function isimpersonate()
	{
		$this->ci =& get_instance();
		if($this->ci->session->userdata('temp_imp_session')){
			return true;
		}else{
			return false;
		}
	}



	/**
	 * Get error message.
	 * Can be invoked after any failed operation such as login or register.
	 *
	 * @return	string
	 */
	function get_error_message()
	{
		return $this->error;
	}

	/**
	 * Admin Section
	 *
	 * @return	void
	 */


	function create_user($user_data, $user_profile_data)
	{
		$username = $user_data['username'];
		$email = $user_data['email'];
		$password = $user_data['password'];
		$activated = $user_data['activated'];
		if($activated==1) {
		$email_activation = 0;
		} else {
		$email_activation = 1;
		}

		if ((strlen($username) > 0) AND !$this->ci->users->is_username_available($username)) {
			$this->error = array('username' => 'auth_username_in_use');

		} elseif (!$this->ci->users->is_email_available($email)) {
			$this->error = array('email' => 'auth_email_in_use');

		} else {
			// Hash password using phpass
			$hashed_password = $this->ci->phpass->hash_password($password);
	
			$data = array(
				'username'	=> $username,
				'password'	=> $hashed_password,
				'email'		=> $email,
				'activated'		=> $user_data['activated'],
				'gid'		=> $user_data['user_role'],
				'last_ip'	=> $this->ci->input->ip_address(),
			);

			//get the group id from session
			$groupId = $this->usergroup_id();
			if($groupId == 3){
				$data['parent_id'] = $this->get_user_id();
			}else{
				$data['parent_id'] = null;
			}

			if ($email_activation) {
				$data['new_email_key'] = md5(rand().microtime());
			}
			
			if (!is_null($res = $this->ci->users->create_user($data, $user_profile_data))) {
				$data['user_id'] = $res['user_id'];
				$data['password'] = $password;
				unset($data['last_ip']);
				return $data;
			}
		}
		return true;
	}
	
	function social_create_user($username, $email, $password, $email_activation) {
		if ((strlen($username) > 0) AND !$this->ci->users->is_username_available($username)) {
			$this->error = array('username' => 'auth_username_in_use');

		} elseif (!$this->ci->users->is_email_available($email)) {
			$this->error = array('email' => 'auth_email_in_use');

		} else {
			// Hash password using phpass
			$hashed_password = $this->ci->phpass->hash_password($password);

			$data = array(
				'username'	=> $username,
				'password'	=> $hashed_password,
				'email'		=> $email,
				'last_ip'	=> $this->ci->input->ip_address(),
			);

			if ($email_activation) {
				$data['new_email_key'] = md5(rand().microtime());
			}
			if (!is_null($res = $this->ci->users->social_create_user($data, !$email_activation))) {
				$data['user_id'] = $res['user_id'];
				$data['password'] = $password;
				unset($data['last_ip']);
				return $data;
			}
		}
		return NULL;
	}
	
	function edituser($user_id, $user_data, $user_profile_data) {
		if(isset($user_data['password']) && $user_data['password']!='') { $user_data['password'] = $this->ci->phpass->hash_password($user_data['password']); }
		$account_data = $this->ci->users->edit_useraccount($user_id, $user_data);
		if($account_data) {
			$profile_data = $this->ci->users->edit_userprofile($user_id , $user_profile_data);
			if($profile_data) { return $user_id; } else { return FALSE; }
		} else { return FALSE; }
		
	}
	
	/**
	 * Activate user using given key
	 *
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function activate_user($user_id, $activation_key, $activate_by_email = TRUE)
	{
		$this->ci->users->purge_na($this->ci->config->item('email_activation_expire'));

		if ((strlen($user_id) > 0) AND (strlen($activation_key) > 0)) {
			return $this->ci->users->activate_user($user_id, $activation_key, $activate_by_email);
		}
		return FALSE;
	}

	/**
	 * Change email for activation and return some data about user:
	 * user_id, username, email, new_email_key.
	 * Can be called for not activated users only.
	 *
	 * @param	string
	 * @return	array
	 */
	function change_email($email)
	{
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, FALSE))) {

			$data = array(
				'user_id'	=> $user_id,
				'username'	=> $user->username,
				'email'		=> $email,
			);
			if (strtolower($user->email) == strtolower($email)) {		// leave activation key as is
				$data['new_email_key'] = $user->new_email_key;
				return $data;

			} elseif ($this->ci->users->is_email_available($email)) {
				$data['new_email_key'] = md5(rand().microtime());
				$this->ci->users->set_new_email($user_id, $email, $data['new_email_key'], FALSE);
				return $data;

			} else {
				$this->error = array('email' => 'auth_email_in_use');
			}
		}
		return NULL;
	}
	
	function resend_activation_email($email)
	{
		$email = strtolower($email);
		$user = $this->ci->users->get_user_by_email($email);
		if(!empty($user)) {
			if($user->activated==1) {
				$this->error = array('email' => 'user_already_activated');
				return NULL;
			} else {
			$data['new_email_key'] = md5(rand().microtime());
			$user_id = $user->id;
			$this->ci->users->set_new_email($user_id, $email, $data['new_email_key'], FALSE);
			return $data;
			}
		}
		
		$this->error = array('email' => 'user_account_not_found');
		return NULL;
		
	}
	
	/**
	 * Check user can login to frontend
	 *
	 * Check with user id
	 *
	 * return true/false
	 */
	function canUserdo($user_id, $key)
	{ 
		$user_group_id = $this->ci->users->group_by_user_id($user_id);
		$group_data=$this->ci->usergroups_model->groupdata_by_id($user_group_id);
		if(!empty($group_data)) { /* if group exists */
			if($group_data->status==1) { /* if group is active */
				$g_permissions=json_decode($group_data->permissions);
				if(!empty($g_permissions)) { /* if group has permissions */ 
					if(isset($g_permissions->$key) && $g_permissions->$key==1) { /* check permission with key  */ 
						return TRUE;
					} else {
						return FALSE;
					}
				} else {  /* if group permission is empty  */ 
					return FALSE;
				}
			} else { /* if group is inactive */ 
				return FALSE;
			}	
		} else { /* if group not exists */ return FALSE; }
	}

	/**
	 * Set new password key for user and return some data about user:
	 * user_id, username, email, new_pass_key.
	 * The password key can be used to verify user when resetting his/her password.
	 *
	 * @param	string
	 * @return	array
	 */
	function forgotpassword($login)
	{
		if (strlen($login) > 0) {
			if (!is_null($user = $this->ci->users->get_user_by_login($login))) {
				$login_to_frontend = $this->canUserdo($user->id, 'login_to_frontend');
				
				if(!$login_to_frontend) {
					$this->error = array('login' => 'auth_account_cant_login_to_frontend');
					return NULL;
				}

				$data = array(
					'user_id'		=> $user->id,
					'username'		=> $user->username,
					'email'			=> $user->email,
					'new_pass_key'	=> md5(rand().microtime()),
				);

				$this->ci->users->set_password_key($user->id, $data['new_pass_key']);
				return $data;

			} else {
				$this->error = array('login' => 'auth_incorrect_email_or_username');
			}
		}
		return NULL;
	}
	

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function can_reset_password($user_id, $new_pass_key)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_pass_key) > 0)) {
			return $this->ci->users->can_reset_password(
				$user_id,
				$new_pass_key,
				$this->ci->config->item('forgot_password_expire'));
		}
		return FALSE;
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user)
	 * and return some data about it: user_id, username, new_password, email.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass_key, $new_password)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_pass_key) > 0) AND (strlen($new_password) > 0)) {

			if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

				// Hash password using phpass
				$hashed_password = $this->ci->phpass->hash_password($new_password);

				if ($this->ci->users->reset_password(
						$user_id,
						$hashed_password,
						$new_pass_key,
						$this->ci->config->item('forgot_password_expire'))) {	// success

					// Clear all user's autologins
					$this->ci->load->model('auth/user_autologin');
					$this->ci->user_autologin->clear($user->id);

					return array(
						'user_id'		=> $user_id,
						'username'		=> $user->username,
						'email'			=> $user->email,
						'new_password'	=> $new_password,
					);
				}
			}
		}
		return NULL;
	}
	
	function retrieve_username($login)
	{
		if (strlen($login) > 0) {
			if (!is_null($user = $this->ci->users->get_user_by_login($login))) {
				
				$login_to_frontend = $this->canUserdo($user->id, 'login_to_frontend');
				
				if(!$login_to_frontend) {
					$this->error = array('login' => 'auth_account_cant_login_to_frontend');
					return NULL;
				}

				$data = array(
					'username'		=> $user->username,
					'email'			=> $user->email,
				);

				return $data;

			} else {
				$this->error = array('login' => 'auth_incorrect_email_or_username');
			}
		}
		return NULL;
	}
	
	/**
	 * Count users on the site
	 * return the count.
	 *
	 */
	 function count_users()
	 {
		$users_count = $this->ci->users_manager->count_users();
		return $users_count?$users_count:0;
	 }
	
	/**
	 * Count user groups on the site
	 * return the count.
	 *
	 */
	 function count_user_groups()
	 {
		$usergroups_count = $this->ci->users_manager->count_user_groups();
		return $usergroups_count?$usergroups_count:0;
	 }

	 function hash_password($password)
	 {
	 	return $this->ci->phpass->hash_password($password);
	 }

}



/* End of file Ci_auth.php */
/* Location: ./application/libraries/Ci_auth.php */