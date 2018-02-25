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

class Oauth extends CI_Controller 
{	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('users');
		$this->load->library('twitter');
		$this->load->library('microsoft');
		$this->load->library('envato');
		$this->load->library('paypal');
		$this->load->library('yandex');
		$this->load->library('bitbucket');
	}

	function index() 
	{
		redirect(site_url('auth'));
	}

	function twitter() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$tw_user = $this->twitter->getUser();
			if( isset($tw_user))
			{
				$this->session->set_userdata('twitter_id', $tw_user->id);
				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('twitter_id' => $tw_user->id), 'twitter_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				} else {

					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'), 'refresh');	
				}			
			} else { 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function twcallback() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$twitter_user = $this->twitter->twcallback();
			if( isset($twitter_user))
			{
				$this->session->set_userdata('twitter_id', $twitter_user);

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('twitter_id' => $twitter_user), 'twitter_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				} else {

					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			} else { 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function microsoft() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$ms_user = $this->microsoft->getUser();
			if(($ms_user))
			{
				$this->session->set_userdata('microsoft_id', $ms_user->getId());
				$this->session->set_userdata('user_email', $ms_user->getEmail());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('microsoft_id' => $ms_user->getId()), 'microsoft_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				} else {

					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function mscallback() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$microsoft_user = $this->microsoft->getUser();
			if(($microsoft_user))
			{
				$this->session->set_userdata('microsoft_id', $microsoft_user->getId());
				$this->session->set_userdata('user_email', $microsoft_user->getEmail());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('microsoft_id' => $microsoft_user->getId()), 'microsoft_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				}
				else
				{
					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function envato() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$eto_user = $this->envato->getUser();
			if(($eto_user))
			{
				$this->session->set_userdata('envato_id', $eto_user->getId());
				$this->session->set_userdata('username', $eto_user->getUsername());
				$this->session->set_userdata('user_email', $eto_user->getEmail());

				// now see if the user exists with the given envato id	
				$user = $this->user_model->get_user_by_sm(array('envato_id' => $eto_user->getId()), 'envato_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				}
				else
				{
					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function envatocallback() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$envato_user = $this->envato->getUser();
			if(($envato_user))
			{
				$this->session->set_userdata('envato_id', $envato_user->getId());
				$this->session->set_userdata('username', $envato_user->getUsername());
				$this->session->set_userdata('user_email', $envato_user->getEmail());

				// now see if the user exists with the given envato id	
				$user = $this->user_model->get_user_by_sm(array('envato_id' => $envato_user->getId()), 'envato_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				}
				else
				{
					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function paypal() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$pp_user = $this->paypal->getUser();
			if(($pp_user))
			{
				$this->session->set_userdata('paypal_id', $pp_user->getId());
				$this->session->set_userdata('user_email', $pp_user->getEmail());

				// now see if the user exists with the given envato id	
				$user = $this->user_model->get_user_by_sm(array('paypal_id' => $pp_user->getId()), 'paypal_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				}
				else
				{
					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function paypalcallback() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$paypal_user = $this->paypal->getUser();
			if(($paypal_user))
			{
				$this->session->set_userdata('paypal_id', $paypal_user->getId());
				$this->session->set_userdata('user_email', $paypal_user->getEmail());

				// now see if the user exists with the given envato id	
				$user = $this->user_model->get_user_by_sm(array('paypal_id' => $paypal_user->getId()), 'paypal_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				}
				else
				{
					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function yandex() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$yndx_user = $this->yandex->getUser();
			if(($yndx_user))
			{
				$this->session->set_userdata('yandex_id', $yndx_user->getId());
				$this->session->set_userdata('username', $yndx_user->getUsername());
				$this->session->set_userdata('user_email', $yndx_user->getEmail());

				// now see if the user exists with the given envato id	
				$user = $this->user_model->get_user_by_sm(array('yandex_id' => $yndx_user->getId()), 'yandex_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				}
				else
				{
					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function yandexcallback() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$yandex_user = $this->yandex->getUser();
			if(($yandex_user))
			{
				$this->session->set_userdata('yandex_id', $yandex_user->getId());
				$this->session->set_userdata('username', $yandex_user->getUsername());
				$this->session->set_userdata('user_email', $yandex_user->getEmail());

				// now see if the user exists with the given envato id	
				$user = $this->user_model->get_user_by_sm(array('yandex_id' => $yandex_user->getId()), 'yandex_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				}
				else
				{

					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function bitbucket() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$bb_user = $this->bitbucket->getUser();
			if(($bb_user))
			{
				$this->session->set_userdata('bitbucket_id', $bb_user->getId());
				$this->session->set_userdata('username', $bb_user->getUsername());

				// now see if the user exists with the given bitbucket id	
				$user = $this->user_model->get_user_by_sm(array('bitbucket_id' => $bb_user->getId()), 'bitbucket_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				} else {

					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	function bbcallback() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$bitbucket_user = $this->bitbucket->getUser();
			if(($bitbucket_user))
			{
				$this->session->set_userdata('bitbucket_id', $bitbucket_user->getId());
				$this->session->set_userdata('username', $bitbucket_user->getUsername());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('bitbucket_id' => $bitbucket_user->getId()), 'bitbucket_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth/sociallogin')); 
				}
				else
				{
					
					$this->session->set_userdata(array(	'user_id' => $user[0]->id, 'username' => $user[0]->username,
														'status' => ($user[0]->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED));
					$this->users->update_login_info( $user[0]->id, $this->config->item('login_record_ip'), $this->config->item('login_record_time'));
					redirect(site_url('auth/profile'));	
				}			
			}
			else 
			{ 
				redirect(site_url('auth/login')); 
			}
		}
	}

	// called when user logs in via facebook/twitter for the first time
	function sociallogin()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {

			// load validation library and rules
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length['.$this->config->item('username_min_length').']|callback_username_check');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check');

			// Run the validation
			if ($this->form_validation->run() == false ) 
			{
				$data['username'] = array(
					'name'	=> 'username',
					'id'	=> 'username',
					'class' => 'required form-control',
					'placeholder'	=> 'Username',
					'value' => set_value('username')?set_value('username'):$this->session->userdata('username'),
					'maxlength'	=> 80,
					'size'	=> 30,
				);

				$data['email'] = array(
					'name'	=> 'email',
					'id'	=> 'email',
					'class' => 'required form-control',
					'placeholder'	=> 'Email Address',
					'value' => set_value('email')?set_value('email'):$this->session->userdata('user_email'),
					'maxlength'	=> 80,
					'size'	=> 30,
				);

				$data['submit'] = array(
					'name' => 'button',
					'class' => 'btn btn-warning pull-left',
					'id' => 'button',
					'value' => 'true',
					'type' => 'Submit',
					'content' => '<i class="icon-menu2"></i> Sign in'
				);
				$data['errors'] = (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors');
				$this->load->view(get_template_directory().'socialauth', $data);
			}
			else
			{
				$username   =   $this->db->escape_str($this->input->post('username'));
				$email      =   $this->db->escape_str($this->input->post('email'));

				$password = $this->generate_password(9, 8);
				$data = $this->ci_auth->social_create_user($username, $email, $password, false);
				$user_id = $data['user_id'];

				if( $this->session->userdata('twitter_id'))
				{
					$user_profile = $this->twitter->getUserProfile();
					$name = explode(' ', $user_profile->name);
					if(isset($user_profile->url)){ $website = $user_profile->url; } else { $website = ''; }
					if(isset($user_profile->location)){ $address = $user_profile->location; } else { $address = ''; }
					$country = explode('_', $user_profile->time_zone);
					if(empty($country[1])) { $country1='';} else {$country1 = $country[1]; }
					$user_data = array(
						'twitter_id' => $this->session->userdata('twitter_id'),
						'first_name' => $name[0],
						'last_name' => $name[1],
						'phone' => '',
						'company' => '',
						'country' => $country1,
						'website' => $website,
						'address' => $address,
					);			
					$user_status = $this->users->create_profile($user_id, $user_data);
				} else if( $this->session->userdata('microsoft_id')) {
					$user_profile = $this->microsoft->getUserProfile();
					$firstname = $user_profile->getFirstname();
					$lastname = $user_profile->getLastname();
					$website = $user_profile->getUrls();
					$locale = $user_profile->toArray()['locale'];
					$country = explode('_', $locale);
					if(!isset($country[1]) || empty($country[1])) { $country[1]=''; }
					if(isset($user_profile->toArray()['work'][0]['employer']['name']) || !empty($user_profile->toArray()['work'][0]['employer']['name'])) { 
					$company = $user_profile->toArray()['work'][0]['employer']['name'];  } else { $company = ''; }
					$user_data = array(
						'microsoft_id' => $this->session->userdata('microsoft_id'),
						'first_name' => $firstname,
						'last_name' => $lastname,
						'phone' => '',
						'company' => $company,
						'country' => $country[1],
						'website' => $website,
						'address' => $country[1],
					);	
					$user_status = $this->users->create_profile($user_id, $user_data);
				} else if( $this->session->userdata('envato_id')) {
					$user_profile = $this->envato->getUserProfile();
					$firstname = $user_profile->getFirstname();
					$lastname = $user_profile->getLastname();
					$website = $user_profile->getLink();
					$country = $user_profile->getCountry();
					$user_data = array(
						'envato_id' => $this->session->userdata('envato_id'),
						'first_name' => $firstname,
						'last_name' => $lastname,
						'phone' => '',
						'company' => '',
						'country' => $country,
						'website' => $website,
						'address' => $country,
					);			

					$user_status = $this->users->create_profile($user_id, $user_data);
				} else if( $this->session->userdata('paypal_id')) {
					$user_profile = $this->paypal->getUserProfile();
					$firstname = $user_profile->getFirstname();
					$lastname = $user_profile->getLastname();
					$website = '';
					$phone = $user_profile->getPhone();
					$country = $user_profile->getCountry();
					$address = $user_profile->getAddress();
					$user_data = array(
						'paypal_id' => $this->session->userdata('paypal_id'),
						'first_name' => $firstname,
						'last_name' => $lastname,
						'phone' => $phone,
						'company' => '',
						'country' => $country,
						'website' => $website,
						'address' => $address['region'].', '.$address['street_address'],
					);
					$user_status = $this->users->create_profile($user_id, $user_data);
				} else if( $this->session->userdata('yandex_id')) {
					$user_profile = $this->yandex->getUserProfile();
					$firstname = $user_profile->getFirstname();
					$lastname = $user_profile->getLastname();
					$user_data = array(
						'yandex_id' => $this->session->userdata('yandex_id'),
						'first_name' => $firstname,
						'last_name' => $lastname,
						'phone' => '',
						'company' => '',
						'country' => '',
						'website' => '',
						'address' => '',
					);
					$user_status = $this->users->create_profile($user_id, $user_data);
				} else if( $this->session->userdata('bitbucket_id')) {
					$user_profile = $this->bitbucket->getUserProfile();
					$full_name = $user_profile->getName();
					$full_name_arr = explode(' ', $full_name);
					$firstname = $full_name_arr[0];
					$lastname =  $full_name_arr[1]? $full_name_arr[1]:'';
					$address = $user_profile->getLocation();
					$user_data = array(
						'bitbucket_id' => $this->session->userdata('bitbucket_id'),
						'first_name' => $firstname,
						'last_name' => $lastname,
						'phone' => '',
						'company' => '',
						'country' => '',
						'website' => '',
						'address' => $address,
					);
					$user_status = $this->users->create_profile($user_id, $user_data);
				}
				if($user_status) {			

					// user login
					$this->ci_auth->login($email, $password, false, false, true);
					redirect(site_url('auth/profile'));
				} else {
					redirect(site_url('auth/login'));
				}
			}
		}
	}
	
	// function to validate the email input field
	function email_check($email)
	{
		$user = $this->users->get_user_by_email($email);
		if ( sizeof($user) > 0) 
		{
			$this->form_validation->set_message('email_check', 'This %s is already registered.');
			return false;
		} else { return true; }
	}

	function username_check($username)
	{
		$user = $this->users->get_user_by_username($username);
		if ( sizeof($user) > 0) 
		{
			$this->form_validation->set_message('username_check', 'This %s is already registered.');
			return false;
		}
		else { return true; }
	}

	// generates a random password for the user
	function generate_password($length=9, $strength=0) 
	{
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) { $consonants .= 'BDGHJLMNPQRSTVWXZ'; }
		if ($strength & 2) { $vowels .= "AEUY"; }
		if ($strength & 4) { $consonants .= '23456789'; }
		if ($strength & 8) { $consonants .= '@#$%'; }
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) 
		{
			if ($alt == 1) 
			{
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}
}
/* End of file Oauth.php */
/* Location: ./controllers/Oauth.php */