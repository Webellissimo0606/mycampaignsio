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

class Login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
	}

	function index()
	{
		$this->login();
	}

	function login()
	{
		if ($this->ci_auth->is_logged_in()) { /* logged in*/
			
			if($this->ci_auth->canDo('access_backend')) {
				redirect(site_url('/admin/dashboard'));
			} else {
				$this->session->set_flashdata('errors', 'You dont have permission to access the admin part of the site.');
				redirect(site_url('auth/profile/'));
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

			if ($this->form_validation->run()) {								/* validation ok */
				if ($this->ci_auth->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('remember'),
						$data['login_by_username'],
						$data['login_by_email'])) {								/* success */
						redirect(site_url('/admin/dashboard'));

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
			$this->load->view(get_theme_directory().'/login', $data);
		}
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


	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	function _show_message($message)
	{
		$this->session->set_flashdata('message', $message);
		redirect('/admin/login');
	}

}

/* End of file Login.php */
/* Location: ./modules/admin/controllers/auth.php */