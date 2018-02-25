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
class Profile extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
		$this->load->library('my_form_validation');
		$this->load->model('auth/user_model');
        $this->load->model('auth/analyze_model');
	}

	/* User Profile Page */
	function index()
	{
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			if($this->ci_auth->canDo('login_to_frontend')) {
				$user_id = $this->ci_auth->get_user_id();
				$data['errors'] = $this->session->flashdata('errors');
				$data['message'] = $this->session->flashdata('message');
				$data['success'] = $this->session->flashdata('success');
				$user_profile = $this->user_model->get_user($user_id);
				$data['profile'] = $user_profile[0];
				$data['seo_title'] = 'Profile';
				$this->load->view(get_template_directory().'profile', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}
	
	
	function editprofile()
	{
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			if($this->ci_auth->canDo('login_to_frontend')) {
				$user_id = $this->ci_auth->get_user_id();
				
				$this->form_validation->set_rules('first_name', 'First name', 'trim|required|xss_clean|min_length[3]|max_length[10]');
				$this->form_validation->set_rules('last_name', 'Last name', 'trim|required|xss_clean|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('phone', 'Phone', 'trim|xss_clean|min_length[3]|max_length[20]');
				$this->form_validation->set_rules('company', 'Company', 'trim|xss_clean|min_length[3]|max_length[20]');
				$this->form_validation->set_rules('country', 'Country', 'trim|xss_clean|min_length[2]|max_length[20]');
				$this->form_validation->set_rules('website', 'Website', 'trim|xss_clean|min_length[3]|max_length[50]');
				$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean|min_length[3]|max_length[100]');

				$use_username = $this->config->item('use_username');
				if ($use_username) {
					$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length').']|max_length['.$this->config->item('username_max_length').']|alpha_dash|edit_unique[users.username.'.$user_id.']');
				}
				$password = $this->input->post('password');
				if(isset($password) && $password!='') { 
					$this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|min_length['.$this->config->item('password_min_length').']|max_length['.$this->config->item('password_max_length').']|alpha_dash');
					$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|xss_clean|matches[password]');
				}

				$data['errors'] = array();
				if ($this->form_validation->run()) { // validation ok
				
					$user_id = $this->ci_auth->get_user_id();
				if(isset($password) && $password!='') { 
					$user_data=array(
						'username'=>$this->input->post('username'),
						'password'=>$this->input->post('password'),
					);
				} else {
					$user_data=array(
						'username'=>$this->input->post('username'),
					);
				}
					
					$user_profile_data=array(
						'first_name'=>$this->input->post('first_name'),
						'last_name'=>$this->input->post('last_name'),
						'phone'=>$this->input->post('phone'),
						'company'=>$this->input->post('company'),
						'country'=>$this->input->post('country'),
						'website'=>$this->input->post('website'),
						'address'=>$this->input->post('address'),
						'pushbullet_api'=>$this->input->post('pushbulletapi'),
					);

					$update_user = $this->ci_auth->edituser($user_id, $user_data, $user_profile_data);
					if($update_user) { 
						$this->session->set_flashdata('success', $this->lang->line('account_information_updated'));
						redirect(site_url('auth/profile')); 
					} else {
						$this->session->set_flashdata('errors', $this->lang->line('account_information_update_failed'));
						redirect(site_url('auth/profile/editprofile')); 
					}
				}  else { $data['errors'] =  (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }
				
				$data['message'] = $this->session->flashdata('message');
				$data['success'] = $this->session->flashdata('success');


				$data['seo_title'] = 'Edit profile';

				$user_profile = $this->user_model->get_user($user_id);
				$profile = $user_profile[0];
				$data['profile'] = $user_profile[0];
				$data['first_name'] = array(
					'name'	=> 'first_name',
					'id'	=> 'first_name',
					'class' => 'required form-control',
					'placeholder'	=> 'First name',
					'value' => $profile->first_name,
					'maxlength'	=> 80,
					'size'	=> 30,
				);
				
				$data['last_name'] = array(
					'name'	=> 'last_name',
					'id'	=> 'last_name',
					'class' => 'required form-control',
					'placeholder'	=> 'Last name',
					'value' => $profile->last_name,
					'maxlength'	=> 80,
					'size'	=> 30,
				);
				
				$data['username'] = array(
					'name'	=> 'username',
					'id'	=> 'username',
					'class' => 'required form-control',
					'placeholder'	=> 'Username',
					'value' => $profile->username,
					'maxlength'	=> $this->config->item('username_max_length'),
					'size'	=> 30,
				);

				$data['email'] = array(
					'name'	=> 'email',
					'id'	=> 'email',
					'class' => 'form-control',
					'placeholder'	=> 'Email address',
					'value' => $profile->email,
					'maxlength'	=> 80,
					'disabled'	=> 'disabled',
					'size'	=> 30,
				);
	
				$data['password'] = array(
					'class' => 'form-control',
					'placeholder'	=> 'Password',
					'name'	=> 'password',
					'id'	=> 'password',
					'size'	=> 30,
				);

				$data['confirm_password'] = array(
					'class' => 'form-control',
					'placeholder'	=> 'Confirm Password',
					'name'	=> 'confirm_password',
					'id'	=> 'confirm_password',
					'size'	=> 30,
				);

				$data['phone'] = array(
					'name'	=> 'phone',
					'id'	=> 'phone',
					'class' => 'form-control',
					'placeholder'	=> 'Phone',
					'value' =>  $profile->phone,
				);
				$data['company'] = array(
					'name'	=> 'company',
					'id'	=> 'company',
					'class' => 'form-control',
					'placeholder'	=> 'Company',
					'value' =>  $profile->company,
				);
				$data['address'] = array(
					'name'	=> 'address',
					'id'	=> 'address',
					'class' => 'form-control',
					'placeholder'	=> 'Address',
					'value' =>  $profile->address,
				);
				$data['website'] = array(
					'name'	=> 'website',
					'id'	=> 'website',
					'class' => 'form-control',
					'placeholder'	=> 'Website',
					'value' =>  $profile->website,
				);
				$data['pushbulletapi'] = array(
					'name'	=> 'pushbulletapi',
					'id'	=> 'pushbulletapi',
					'class' => 'form-control',
					'placeholder'	=> 'Pushbullet api',
					'value' =>  $profile->pushbullet_api,
				);
				$data['submit'] = array(
					'name' => 'button',
					'class' => 'btn btn-warning',
					'id' => 'button',
					'value' => 'true',
					'type' => 'Submit',
					'content' => '<i class="icon-menu2"></i> Update profile'
				);
				$this->load->view(get_template_directory().'editprofile', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}
	
	function mysites() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('/auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {      // logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			if ($this->ci_auth->canDo('login_to_frontend')) {
				$data['assigned_sites'] = $this->analyze_model->getClientSites($this->session->userdata('user_id'));
				$this->load->view(get_template_directory() . 'mysites', $data);
			} else {
				$this->session->set_flashdata('errors', 'You dont have permission to access this part of the site.');
				redirect(site_url('auth/profile/'));
			}
		}
	}
}
/* End of file profile.php */
/* Location: ./application/controllers/profile.php */
