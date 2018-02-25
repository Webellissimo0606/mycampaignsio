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

class Auth extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
	}

	function index()
	{
		if (!$this->ci_auth->is_logged_in()) {	
				redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			
			if($this->ci_auth->canDo('login_to_frontend')) {
					redirect(site_url('auth/profile/'));
			} else {
				redirect(site_url('/admin/login'));
			}
			
		}
	}

	/**
	 * Login user on the site
	 *
	 * @return void
	 */

	function login()
	{
		if ($this->ci_auth->is_logged_in()) { /* logged in*/
			
			if($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/auth/profile'));
			} else {
				redirect(site_url('/admin/login'));
			}
			
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { /* logged in, not activated */
			
			redirect('/auth/sendactivation/');
			
		} else {
			
			$data['login_by_username'] = ($this->config->item('login_by_username') AND
					$this->config->item('use_username'));
			$data['login_by_email'] = $this->config->item('login_by_email');
			$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts') AND
					($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}
			
			$data['use_recaptcha'] = $this->config->item('use_recaptcha');
			
			if ($this->ci_auth->is_max_login_attempts_exceeded($login)) {
				if ($data['use_recaptcha'])
					$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|xss_clean|required|callback__check_recaptcha');
				else
					$this->form_validation->set_rules('captcha', 'Captcha', 'trim|xss_clean|required|callback__check_captcha');
			}
			
			$data['errors'] = array();
			if ($this->form_validation->run()) { /* validation ok */
				if ($this->ci_auth->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('remember'),
						$data['login_by_username'],
						$data['login_by_email'])) {								/* success */
						redirect(site_url('/auth/profile'));
				} else {
					$errors = $this->ci_auth->get_error_message();
					if (isset($errors['banned'])) {								/* banned user */
						$this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);
					} elseif (isset($errors['not_activated'])) {				/* not activated user */
						redirect('/auth/sendactivation/');
					} else {													/*  fail */
						foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
					}
				}
			} else { $data['errors'] =  (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }
			
			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');

			$data['show_captcha'] = FALSE;
			if ($this->ci_auth->is_max_login_attempts_exceeded($login)) {
				$data['show_captcha'] = TRUE;
				if ($data['use_recaptcha']) {
				} else {
					$data['captcha_html'] = $this->_create_captcha();
					$data['captcha'] = array(
						'name'	=> 'captcha',
						'placeholder'	=> 'Captcha',
						'class' => 'required form-control',
						'id'	=> 'captcha',
						'maxlength'	=> 8,
					);
				}
			}

			$data['seo_title'] = 'Login';
			
			$data['remember'] = array(
				'name'	=> 'remember',
				'id'	=> 'remember',
				'value'	=> 1,
				'class' => 'styled',
			);

			$data['login'] = array(
				'name'	=> 'login',
				'id'	=> 'login',
				'class' => 'required form-control',
				'placeholder'	=> 'Username',
				'value' => set_value('login'),
				'maxlength'	=> 80,
				'size'	=> 30,
			);

			$data['password'] = array(
				'class' => 'required form-control',
				'placeholder'	=> 'Password',
				'name'	=> 'password',
				'id'	=> 'password',
				'size'	=> 30,
			);

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning pull-right',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-menu2"></i> Sign in'
			);

			$this->load->view(get_template_directory().'/login', $data);
		}
	}

	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	function register()
	{
		if ($this->ci_auth->is_logged_in()) { /* logged in*/
		
			if($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/auth/profile'));
			} else {
				redirect(site_url('/admin/login'));
			}

		} elseif ($this->ci_auth->is_logged_in(FALSE)) { /* logged in, not activated */

			redirect('/auth/sendactivation/');

		} elseif (!$this->config->item('allow_registration')) {	// registration is off
			
			$this->_show_message($this->lang->line('auth_message_registration_disabled'));

		} else {

			$this->form_validation->set_rules('first_name', 'First name', 'trim|required|xss_clean|min_length[3]|max_length[10]');
			$this->form_validation->set_rules('last_name', 'Last name', 'trim|required|xss_clean|min_length[1]|max_length[10]');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|xss_clean|min_length[3]|max_length[20]');
			$this->form_validation->set_rules('country', 'Country', 'trim|xss_clean|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('company', 'Company', 'trim|xss_clean|min_length[3]|max_length[20]');
			$this->form_validation->set_rules('website', 'Website', 'trim|xss_clean|min_length[3]|max_length[50]');
			$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean|min_length[3]|max_length[100]');

			$use_username = $this->config->item('use_username');
			if ($use_username) {
				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length').']|max_length['.$this->config->item('username_max_length').']|alpha_dash');
			}

			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length').']|max_length['.$this->config->item('password_max_length').']|alpha_dash');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
			$captcha_registration	= $this->config->item('captcha_registration');
			$use_recaptcha			= $this->config->item('use_recaptcha');

			if ($captcha_registration) {
				
				if ($use_recaptcha) {
					$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Captcha', 'trim|xss_clean|required|callback__check_captcha');
				}
			}

			$data['errors'] = array();
			$email_activation = $this->config->item('email_activation');
			if ($this->form_validation->run()) { // validation ok
			
			$user_data['username'] = $use_username?$this->form_validation->set_value('username'):'';
			$user_data['email'] = $this->form_validation->set_value('email');
			$user_data['password'] = $this->form_validation->set_value('password');
			$email_activation = $this->config->item('email_activation');
			if($email_activation) { $user_data['activated'] = 0; } else { $user_data['activated'] = 1; }
			$user_data['user_role'] = $this->config->item('new_user_group');
			
			$user_profile_data['first_name'] = $this->form_validation->set_value('first_name');
			$user_profile_data['last_name'] = $this->form_validation->set_value('last_name');
			
				if (!is_null($data = $this->ci_auth->create_user($user_data,$user_profile_data))) {									// success

					$data['site_name'] = $this->config->item('website_name');
					
					if ($email_activation) {									// send "activate" email
						$data['activation_period'] = $this->config->item('email_activation_expire') / 3600;
				
						$this->_send_email('activate', $data['email'], $data);
						unset($data['password']); // Clear password (just for any case)
						$this->_show_message($this->lang->line('auth_message_registration_completed_1'));
					} else {

						if ($this->config->item('email_account_details')) {	// send "welcome" email
							$this->_send_email('welcome', $data['email'], $data);
						}

						unset($data['password']); // Clear password (just for any case)
						$this->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
					}

				} else {
					$errors = $this->ci_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}  else { $data['errors'] =  (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }
			
			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');

			$data['use_recaptcha'] = $this->config->item('use_recaptcha');

			$data['seo_title'] = 'Register';

			$data['first_name'] = array(
				'name'	=> 'first_name',
				'id'	=> 'first_name',
				'class' => 'required form-control',
				'placeholder'	=> 'First name',
				'value' => set_value('first_name'),
				'maxlength'	=> 80,
				'size'	=> 30,
			);
			
			$data['last_name'] = array(
				'name'	=> 'last_name',
				'id'	=> 'last_name',
				'class' => 'required form-control',
				'placeholder'	=> 'Last name',
				'value' => set_value('last_name'),
				'maxlength'	=> 80,
				'size'	=> 30,
			);
			
			$data['username'] = array(
				'name'	=> 'username',
				'id'	=> 'username',
				'class' => 'required form-control',
				'placeholder'	=> 'Username',
				'value' => set_value('username'),
				'maxlength'	=> $this->config->item('username_max_length'),
				'size'	=> 30,
			);

			$data['email'] = array(
				'name'	=> 'email',
				'id'	=> 'email',
				'class' => 'required form-control',
				'placeholder'	=> 'Email address',
				'value' => set_value('email'),
				'maxlength'	=> 80,
				'size'	=> 30,
			);

			$data['password'] = array(
				'class' => 'required form-control',
				'placeholder'	=> 'Password',
				'name'	=> 'password',
				'id'	=> 'password',
				'size'	=> 30,
			);

			$data['confirm_password'] = array(
				'class' => 'required form-control',
				'placeholder'	=> 'Confirm Password',
				'name'	=> 'confirm_password',
				'id'	=> 'confirm_password',
				'size'	=> 30,
			);
			
			$data['show_captcha'] = TRUE;
			if ($data['use_recaptcha']) {
			} else {
				$data['captcha_html'] = $this->_create_captcha();
				$data['captcha'] = array(
					'name'	=> 'captcha',
					'placeholder'	=> 'Captcha',
					'class' => 'required form-control',
					'id'	=> 'captcha',
					'maxlength'	=> 8,
				);
			}
		
			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning pull-right',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-menu2"></i> Register'
			);
			$this->load->view(get_template_directory().'/register', $data);
		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */

	function logout()
	{
		// set all user data to empty
		$this->session->set_userdata(array('facebook_id' => '', 
											'twitter_id' => '', 
											'google_id' => '',
											'linkedin_id' => '',
											'github_id' => '',
											'instagram_id' => '',
											'microsoft_id' => '',
											'envato_id',
											'paypal_id',
											'yandex_id',
											'oauth2state' => '',
											'facebook_access_token' => '',
											'google_access_token' => '',
											'linkedin_access_token' => '',
											'github_access_token' => '',
											'instagram_access_token' => '',
											'microsoft_access_token' => '',
											'envato_access_token' => '',
											'paypal_access_token' => '',
											'yandex_access_token'));
		$this->ci_auth->logout();
		$this->_show_message($this->lang->line('auth_message_logged_out'));
	}

	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	function sendactivation()
	{
		if ($this->ci_auth->is_logged_in()) { /* logged in*/
		
			if($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/auth/profile'));
			} else {
				redirect(site_url('/admin/login'));
			}

		} else {
			
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
			$data['errors'] = array();
			if ($this->form_validation->run()) {								// validation ok
				
				if (!is_null($data = $this->ci_auth->resend_activation_email($this->form_validation->set_value('email')))) {// success
					$data['site_name']	= $this->config->item('website_name');
					$data['activation_period'] = $this->config->item('email_activation_expire') / 3600;
					$this->_send_email('activate', $data['email'], $data);
					$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));
				} else {
					$errors = $this->ci_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			} else { $data['errors'] = (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }

			$data['seo_title'] = 'Send Activation Link';

			$data['email'] = array(
				'name'	=> 'email',
				'id'	=> 'email',
				'class' => 'required form-control',
				'placeholder'	=> 'Email address',
				'value' => set_value('email'),
				'maxlength'	=> 80,
				'size'	=> 30,
			);

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-mail2"></i> Send Activation mail'
			);

			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');
			
			$this->load->view(get_template_directory().'/sendactivation', $data);
		}
	}

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function activate()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			if($this->ci_auth->canDo('login_to_frontend')) {
					redirect(site_url('/profile/'));
			} else {
				redirect(site_url('/admin/login'));
			}
		}
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Activate user
		if ($this->ci_auth->activate_user($user_id, $new_email_key)) {		// success
			$this->ci_auth->logout();
			$this->_show_message($this->lang->line('auth_message_activation_completed'));
		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_activation_failed'));
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	function forgotpassword()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			if($this->ci_auth->canDo('login_to_frontend')) {
					redirect(site_url('/profile/'));
			} else {
				redirect(site_url('/admin/login'));
			}
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$captcha_forgetpassword	= $this->config->item('captcha_forgetpassword');
			$use_recaptcha			= $this->config->item('use_recaptcha');

			$this->form_validation->set_rules('login', 'Email or login', 'trim|required|xss_clean');
			
			if ($captcha_forgetpassword) {
				
				if ($use_recaptcha) {
					$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Captcha', 'trim|xss_clean|required|callback__check_captcha');
				}
			}

			$data['errors'] = array();
			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->ci_auth->forgotpassword($this->form_validation->set_value('login')))) {
					$data['site_name'] = $this->config->item('website_name');

					// Send email with password activation link
					$this->_send_email('forgot_password', $data['email'], $data);
					$this->_show_message($this->lang->line('auth_message_new_password_sent'));
				} else {
					$errors = $this->ci_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			} else { $data['errors'] = (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }
			
			$data['captcha_forgetpassword']	= $this->config->item('captcha_forgetpassword');
			$data['use_recaptcha']			= $this->config->item('use_recaptcha');
			
			$data['seo_title'] = 'Forgot password';
			
			if ($this->config->item('use_username')) {
				$placeholder = 'Email or Username';
			} else {
				$placeholder = 'Email';
			}
			
			$data['login'] = array(
				'name'	=> 'login',
				'id'	=> 'login',
				'class' => 'required form-control',
				'placeholder'	=> $placeholder,
				'value' => set_value('login'),
				'maxlength'	=> 80,
				'size'	=> 30,
			);

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-mail2"></i> Reset Password'
			);
			if ($data['use_recaptcha']) {
			} else {
				$data['captcha_html'] = $this->_create_captcha();
				$data['captcha'] = array(
					'name'	=> 'captcha',
					'placeholder'	=> 'Captcha',
					'class' => 'required form-control',
					'id'	=> 'captcha',
					'maxlength'	=> 8,
				);
			}
			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');
			
			$this->load->view(get_template_directory().'/forgotpassword', $data);
		}
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function resetpassword()
	{
		$user_id		= $this->uri->segment(3);
		$new_pass_key	= $this->uri->segment(4);
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length').']|max_length['.$this->config->item('password_max_length').']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');
		$data['errors'] = array();

		if ($this->form_validation->run()) {								// validation ok
			
			if (!is_null($data = $this->ci_auth->reset_password(
					$user_id, $new_pass_key,
					$this->form_validation->set_value('new_password')))) {	// success
				$data['site_name'] = $this->config->item('website_name');
				// Send email with new password
				$this->_send_email('reset_password', $data['email'], $data);
				$this->_show_message($this->lang->line('auth_message_new_password_activated'));
			} else {														// fail
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}

		} else {
			
			$data['errors'] = (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors');
			// Try to activate user by password key (if not activated yet)
			if ($this->config->item('email_activation')) {
				$this->ci_auth->activate_user($user_id, $new_pass_key, FALSE);
			}

			if (!$this->ci_auth->can_reset_password($user_id, $new_pass_key)) {
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		}
			$data['new_password'] = array(
				'name'	=> 'new_password',
				'id'	=> 'new_password',
				'class' => 'required form-control',
				'placeholder'	=> 'New password',
				'maxlength'	=> $this->config->item('password_max_length'),
				'size'	=> 30,
			);
			
			$data['confirm_new_password'] = array(
				'name'	=> 'confirm_new_password',
				'id'	=> 'confirm_new_password',
				'class' => 'required form-control',
				'placeholder'	=> 'Re-enter password',
				'maxlength'	=> $this->config->item('password_max_length'),
				'size'	=> 30,
			);

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-mail2"></i> Change Password'
			);
		$data['message'] = $this->session->flashdata('message');
		$data['success'] = $this->session->flashdata('success');

		$this->load->view(get_template_directory().'/resetpassword', $data);
	}


	/**
	 * Retrieve_username by email address
	 *
	 * @return void
	 */
	function retrieveusername()
	{
		if ($this->ci_auth->is_logged_in()) { /* logged in*/
		
			if($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/auth/profile'));
			} else {
				redirect(site_url('/admin/login'));
			}

		} else {
			
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
			
			$captcha_retrieveusername	= $this->config->item('captcha_retrieveusername');
			$use_recaptcha			= $this->config->item('use_recaptcha');
			if ($captcha_retrieveusername) {
				
				if ($use_recaptcha) {
					$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Captcha', 'trim|xss_clean|required|callback__check_captcha');
				}
			}
			
			$data['errors'] = array();
			if ($this->form_validation->run()) {								// validation ok
				
				if (!is_null($data = $this->ci_auth->retrieve_username($this->form_validation->set_value('email')))) {// success
					$data['site_name']	= $this->config->item('website_name');
					$this->_send_email('retrieveusername', $data['email'], $data);
					$this->_show_message(sprintf($this->lang->line('auth_message_retrieve_username_email_sent'), $data['email']));
				} else {
					$errors = $this->ci_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			} else { $data['errors'] = (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }
			
			$data['captcha_retrieveusername']	= $this->config->item('captcha_retrieveusername');
			$data['use_recaptcha']			= $this->config->item('use_recaptcha');
			
			$data['seo_title'] = 'Retrieve username';

			$data['email'] = array(
				'name'	=> 'email',
				'id'	=> 'email',
				'class' => 'required form-control',
				'placeholder'	=> 'Email address',
				'value' => set_value('email'),
				'maxlength'	=> 80,
				'size'	=> 30,
			);

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-mail2"></i> Retrieve username'
			);

			if ($data['use_recaptcha']) {
			} else {
				$data['captcha_html'] = $this->_create_captcha();
				$data['captcha'] = array(
					'name'	=> 'captcha',
					'placeholder'	=> 'Captcha',
					'class' => 'required form-control',
					'id'	=> 'captcha',
					'maxlength'	=> 8,
				);
			}
			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');
			
			$this->load->view(get_template_directory().'/retrieveusername', $data);
		}
	}

	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	function _show_message($message)
	{
		$this->session->set_flashdata('message', $message);
		redirect('/auth/login');
	}

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->set_mailtype("html");
		$this->email->from($this->config->item('webmaster_email'), $this->config->item('website_name'));
		$this->email->reply_to($this->config->item('webmaster_email'), $this->config->item('website_name'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name')));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}

	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	function _create_captcha()
	{
		$this->load->helper('captcha');
		$cap = create_captcha(array(
			'img_path'		=> './'.$this->config->item('captcha_path'),
			'img_url'		=> base_url().$this->config->item('captcha_path'),
			'font_path'		=> './'.$this->config->item('captcha_fonts_path'),
			'font_size'		=> $this->config->item('captcha_font_size'),
			'img_width'		=> $this->config->item('captcha_width'),
			'img_height'	=> $this->config->item('captcha_height'),
			'show_grid'		=> $this->config->item('captcha_grid'),
			'expiration'	=> $this->config->item('captcha_expire'),
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		));
		return $cap['image'];
	}



	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	function _check_captcha($code)
	{
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');
		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $this->config->item('captcha_expire')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;
		} elseif (($this->config->item('captcha_case_sensitive') AND
				$code != $word) OR
				strtolower($code) != strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha($grecaptcha)
	{
		$this->load->library('google_recaptcha');
		$gcaptcha				= trim($grecaptcha);
		if(!empty($gcaptcha) && isset($gcaptcha)) {
		$siteKey = $this->config->item('recaptcha_sitekey');
		$secret = $this->config->item('recaptcha_secretkey');
		$lang = 'en';
		$userIp=$this->input->ip_address();
		$response = $this->google_recaptcha->getCurlData( $secret, $gcaptcha, $userIp );
		$status= json_decode($response, true);
			if($status['success']){
				return TRUE;
			} else {
				$this->form_validation->set_message('_check_recaptcha', $this->lang->line('recaptcha_validation_failed'));
				return FALSE;
			}
		}
	}
	
}

/* End of file auth.php */
/* Location: ./controllers/auth.php */