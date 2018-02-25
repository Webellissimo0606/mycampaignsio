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

class Oauth2 extends CI_Controller 
{	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('users');
		$this->load->library('facebook');
		$this->load->library('twitter');
		$this->load->library('google');
		$this->load->library('linkedin');
		$this->load->library('github');
		$this->load->library('instagram');
		$this->load->library('google');
	}

	function index() 
	{
		redirect(site_url('auth'));
	}

	function facebook()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$fb_user = $this->facebook->getUser();
			if(($fb_user))
			{
				$this->session->set_userdata('facebook_id', $fb_user->getId());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('facebook_id' => $fb_user->getId()), 'facebook_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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

	function fbcallback()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$facebook_user = $this->facebook->getUser();
			if(($facebook_user))
			{
				$this->session->set_userdata('facebook_id', $facebook_user->getId());
				$this->session->set_userdata('user_email', $facebook_user->getEmail());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('facebook_id' => $facebook_user->getId()), 'facebook_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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

	function google()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$ggle_user = $this->google->getUser();
			if(($ggle_user))
			{
				$this->session->set_userdata('google_id', $ggle_user->getId());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('google_id' => $ggle_user->getId()), 'google_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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

	function googlecallback()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$google_user = $this->google->getUser();
			if(($google_user))
			{
				$this->session->set_userdata('google_id', $google_user->getId());
				$this->session->set_userdata('user_email', $google_user->getEmail());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('google_id' => $google_user->getId()), 'google_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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

	function linkedin()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$ln_user = $this->linkedin->getUser();
			if(($ln_user))
			{
				$this->session->set_userdata('linkedin_id', $ln_user->getId());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('linkedin_id' => $ln_user->getId()), 'linkedin_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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

	function lncallback()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$linkedin_user = $this->linkedin->getUser();
			if(($linkedin_user))
			{
				$this->session->set_userdata('linkedin_id', $linkedin_user->getId());
				$this->session->set_userdata('user_email', $linkedin_user->getEmail());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('linkedin_id' => $linkedin_user->getId()), 'linkedin_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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

	function github()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$git_user = $this->github->getUser();
			if(($git_user))
			{
				$this->session->set_userdata('github_id', $git_user->getId());
				$this->session->set_userdata('user_email', $git_user->getEmail());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('github_id' => $git_user->getId()), 'github_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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

	function gitcallback()
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$github_user = $this->github->getUser();
			if(($github_user))
			{
				$this->session->set_userdata('github_id', $github_user->getId());
				$this->session->set_userdata('user_email', $github_user->getEmail());

				// now see if the user exists with the given twitter id	
				$user = $this->user_model->get_user_by_sm(array('github_id' => $github_user->getId()), 'github_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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

	function instagram() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$insta_user = $this->instagram->getUser();
			if(($insta_user))
			{
				$this->session->set_userdata('instagram_id', $insta_user->getId());

				// now see if the user exists with the given insta id	
				$user = $this->user_model->get_user_by_sm(array('instagram_id' => $insta_user->getId()), 'instagram_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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

	function instacallback() 
	{
		if ($this->ci_auth->is_logged_in()) {									// logged in
			redirect('auth/profile');
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$instagram_user = $this->instagram->getUser();
			if(($instagram_user))
			{
				$this->session->set_userdata('instagram_id', $instagram_user->getId());

				// now see if the user exists with the given insta id	
				$user = $this->user_model->get_user_by_sm(array('instagram_id' => $instagram_user->getId()), 'instagram_id');
				if( sizeof($user) == 0 ) 
				{ 
					redirect(site_url('auth/oauth2/sociallogin')); 
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
	
	// called when user logs in via social for the first time
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
					'value' => set_value('username'),
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

				/*
				 * We now must create a new user with a random password in order
				 * to insert this user and also into user profile table with user id
				 */
				$password = $this->generate_password(9, 8);
				$data = $this->ci_auth->social_create_user($username, $email, $password, false);
				$user_id = $data['user_id'];
				if( $this->session->userdata('facebook_id'))
				{ 
					$user_profile = $this->facebook->getUserProfile();
					if($user_profile) {
					$first_name = $user_profile->getFirstname();
					$last_name =  $user_profile->getLastname();
					$locale = $user_profile->getLocale();
					$country = explode('_', $locale);
					if(isset($user_profile->toArray()['work'])) {
					$industry =  $user_profile->toArray()['work'][0]['employer']['name'];
					} else { $industry = ''; }
					if(isset($user_profile->toArray()['website'])) {
						$url = $user_profile->toArray()['website'];
					} else {
						$url = $user_profile->getLink();
					}
					if(isset($user_profile->toArray()['location'])) {
					$address =  $user_profile->toArray()['location']['name'];
					} else {
						$address = '';
					}
					$user_data = array(
						'facebook_id' => $this->session->userdata('facebook_id'),
						'first_name' => $first_name,
						'last_name' => $last_name,
						'phone' => '',
						'company' => $industry,
						'country' => $country[1],
						'website' => $url,
						'address' => $address,
					);			
					$user_status = $this->users->create_profile($user_id, $user_data);
					} else { redirect(site_url('auth/login')); }
				}
				else if( $this->session->userdata('linkedin_id'))
				{
					$user_profile = $this->linkedin->getUserProfile();
					if($user_profile) {
					$first_name = $user_profile->getFirstname();
					$last_name =  $user_profile->getLastname();
					$industry =  $user_profile->toArray()['industry'];
					$country =  $user_profile->toArray()['location']['country']['code'];
					$url =  $user_profile->getUrl();
					$address =  $user_profile->toArray()['location']['name'];
					$user_data = array(
						'linkedin_id' => $this->session->userdata('linkedin_id'),
						'first_name' => $first_name,
						'last_name' => $last_name,
						'phone' => '',
						'company' => $industry,
						'country' => $country,
						'website' => $url,
						'address' => $address,
					);			
					$user_status = $this->users->create_profile($user_id, $user_data);
					} else { redirect(site_url('auth/login')); }
				}
				else if( $this->session->userdata('github_id'))
				{
					$user_profile = $this->github->getUserProfile();
					if($user_profile) {
					$fullname = $user_profile->getName();
					$name = explode(' ', $fullname);
					if(!isset($name[1]) || empty($name[1])) { $name[1]= $name[0]; }
					$industry =  $user_profile->toArray()['company'];
					$country =  $user_profile->toArray()['location'];
					$url =  $user_profile->toArray()['blog']?$user_profile->toArray()['blog']:$user_profile->toArray()['html_url'];
					$address =  $user_profile->toArray()['location'];
					$user_data = array(
						'github_id' => $this->session->userdata('github_id'),
						'first_name' => $name[0],
						'last_name' => $name[1],
						'phone' => '',
						'company' => $industry,
						'country' => $country,
						'website' => $url,
						'address' => $address,
					);			
					$user_status = $this->users->create_profile($user_id, $user_data);
					} else { redirect(site_url('auth/login')); }
				}
				else if( $this->session->userdata('instagram_id'))
				{
					$user_profile = $this->instagram->getUserProfile();
					if($user_profile) {
					$fullname = $user_profile->toArray()['data']['full_name'];
					if(isset($fullname) && !empty($fullname)) { $name = explode(' ', $fullname); }
					else { $username = $user_profile->toArray()['data']['username']; $name = explode(' ', $username); }
					if(!isset($name[1]) || empty($name[1])) { $name[1]= $name[0]; }
					$industry =  '';
					$country =  '';
					$url =  $user_profile->toArray()['data']['website'];
					$address = '';
					$user_data = array(
						'instagram_id' => $this->session->userdata('instagram_id'),
						'first_name' => $name[0],
						'last_name' => $name[1],
						'phone' => '',
						'company' => $industry,
						'country' => $country,
						'website' => $url,
						'address' => $address,
					);			
					$user_status = $this->users->create_profile($user_id, $user_data);
					} else { redirect(site_url('auth/login')); }
				}
				else if( $this->session->userdata('google_id'))
				{
					$user_profile = $this->google->getUserProfile();
					if($user_profile) {
					$first_name = $user_profile->getFirstName();
					$last_name = $user_profile->getLastName();
					if(isset($user_profile->toArray()['url'])){ $website = $user_profile->toArray()['url']; } else { $website = ''; }
					if(isset($user_profile->toArray()['organizations']['name'])){ $industry = $user_profile->toArray()['organizations']['name']; } else { $industry = ''; }
					$country1='';
					$user_data = array(
						'google_id' => $this->session->userdata('google_id'),
						'first_name' => $first_name,
						'last_name' => $last_name,
						'phone' => '',
						'company' => $industry,
						'country' => $country1,
						'website' => $website,
						'address' => $country1,
					);			
					$user_status = $this->users->create_profile($user_id, $user_data);
					} else { redirect(site_url('auth/login')); }
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

	// a logout function for 3rd party
	function logout()
	{
		// set all user data to empty
		$this->session->set_userdata(array('facebook_id' => '', 
										   'twitter_id' => '', 
										   'google_id' => '',
										   'google_open_id' => '',
										   'yahoo_open_id' => '',
										   'google_access_token' => ''));
		redirect('auth/logout');
	}

	// function to validate the email input field
	function email_check($email)
	{
		$user = $this->users->get_user_by_email($email);
		if ( sizeof($user) > 0) 
		{
			$this->form_validation->set_message('email_check', 'This %s is already registered.');
			return false;
		}
		else { return true; }
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
			} 
			else 
			{
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}
}
/* End of file Oauth2.php */
/* Location: ./controllers/Oauth2.php */