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

class Settings extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
		$this->load->library('my_form_validation');
		$this->load->model('settings_model');
	}

	function index()
	{
		$this->general();
	}


	/**
	 * Genaeral settings
	 *
	 */
	function general()
	{
		 
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('/admin/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
		
		if($this->ci_auth->canDo('access_backend')) {
			if($this->ci_auth->canDo('general_settings')) {
				$data['seo_pagetitle'] = 'Social Login Settings';
				$task = $this->input->post('task');

				$this->form_validation->set_rules('task', 'Task', 'required|trim|xss_clean');
				
				$this->form_validation->set_rules('website_name', 'Website name', 'required|trim|xss_clean');
				$this->form_validation->set_rules('webmaster_email', 'Webmaster email', 'required|trim|valid_email');
			
				if ($this->form_validation->run() && (isset($task) && $task=='settings.update')) {
					
					$general_settings = array(
						'website_name'=>$this->input->post('website_name'),
						'webmaster_email'=>$this->input->post('webmaster_email'),
						'captcha_registration'=>$this->input->post('captcha_registration'),
						'captcha_forgetpassword'=>$this->input->post('captcha_forgetpassword'),
						'captcha_retrieveusername'=>$this->input->post('captcha_retrieveusername'),
						'use_recaptcha'=>$this->input->post('use_recaptcha'),
						'recaptcha_sitekey'=>$this->input->post('recaptcha_sitekey'),
						'recaptcha_secretkey'=>$this->input->post('recaptcha_secretkey'),
						'new_user_group'=>$this->input->post('new_user_group'),
					);
					$update_settings = $this->settings_model->update_config($general_settings);
					if($update_settings) {
						$this->session->set_flashdata('success', 'General settings saved successfully');
						redirect(site_url('admin/settings/general'));
					} else { 
						$this->session->set_flashdata('errors', 'There is a problem while save the settings');
						redirect(site_url('admin/settings/general'));
					}
				} else { $data['errors'] =  (validation_errors()) ? validation_errors() : $this->session->flashdata('errors'); }
				$data['success'] =  $this->session->flashdata('success');

				$settings = $this->settings_model->get_all(); 
				/* Check the logged in user can create users with posted user role*/
				$user_roles =  $this->users->user_roles_list();
				foreach($user_roles as $key => $value) {
					if($key==1) {
						if($this->ci_auth->is_superadmin()) {
							$data['user_roles'][$key]=$value;
						}
					} else if($key==2) {
						if($this->ci_auth->is_superadmin() || $this->ci_auth->is_admin()) {
							$data['user_roles'][$key]=$value;
						}
					} else {
					$data['user_roles'][$key]=$value;
					}
				}
	
				$data['gsettings'] = $settings;
				$data['website_name'] = array(
					'name'	=> 'website_name',
					'id'	=> 'website_name',
					'class' => 'form-control required',
					'placeholder'	=> 'Website Name',
					'value' =>  set_value('website_name')?set_value('website_name'):$settings->website_name,
				);
				$data['webmaster_email'] = array(
					'name'	=> 'webmaster_email',
					'id'	=> 'webmaster_email',
					'class' => 'form-control required',
					'placeholder'	=> 'Webmaster email',
					'value' =>  set_value('webmaster_email')?set_value('webmaster_email'):$settings->webmaster_email,
				);
				$data['recaptcha_sitekey'] = array(
					'name'	=> 'recaptcha_sitekey',
					'id'	=> 'recaptcha_sitekey',
					'class' => 'form-control',
					'placeholder'	=> 'Recaptcha Site key',
					'value' =>  set_value('recaptcha_sitekey')?set_value('recaptcha_sitekey'):$settings->recaptcha_sitekey,
				);
				$data['recaptcha_secretkey'] = array(
					'name'	=> 'recaptcha_secretkey',
					'id'	=> 'recaptcha_secretkey',
					'class' => 'form-control',
					'placeholder'	=> 'Recaptcha Secret key',
					'value' =>  set_value('recaptcha_secretkey')?set_value('recaptcha_secretkey'):$settings->recaptcha_secretkey,
				);

				

				$this->load->view(get_theme_directory().'/general_settings', $data);
			} else  {
				$this->session->set_flashdata('errors', 'You dont have permission to access this part of the site.');
				redirect(site_url('/admin/dashboard'));
			}
		} else {
				$this->session->set_flashdata('errors', 'You dont have permission to access the admin part of the site.');
				redirect(site_url('auth/profile/'));
		}
		
		}
	 
	 }
	 
	/**
	 * Social Login settings
	 *
	 */
	 function social()
	 {
		 
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('/admin/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
		
		if($this->ci_auth->canDo('access_backend')) {
			if($this->ci_auth->canDo('general_settings')) {
				$data['seo_pagetitle'] = 'Social Login Settings';
				$task = $this->input->post('task');

				$facebook_app_id = $this->input->post('facebook_app_id');
				$facebook_app_secret = $this->input->post('facebook_app_secret');
				$tw_consumer_key = $this->input->post('tw_consumer_key');
				$tw_consumer_secret = $this->input->post('tw_consumer_secret');
				$google_app_id = $this->input->post('google_app_id');
				$google_app_secret = $this->input->post('google_app_secret');
				$linkedin_client_id = $this->input->post('linkedin_client_id');
				$linkedin_client_secret = $this->input->post('linkedin_client_secret');
				$github_client_id = $this->input->post('github_client_id');
				$github_client_secret = $this->input->post('github_client_secret');
				$instagram_client_id = $this->input->post('instagram_client_id');
				$instagram_client_secret = $this->input->post('instagram_client_secret');
				$microsoft_client_id = $this->input->post('microsoft_client_id');
				$microsoft_client_secret = $this->input->post('microsoft_client_secret');
				$envato_client_id = $this->input->post('envato_client_id');
				$envato_client_secret = $this->input->post('envato_client_secret');
				$paypal_client_id = $this->input->post('paypal_client_id');
				$paypal_client_secret = $this->input->post('paypal_client_secret');
				$yandex_client_id = $this->input->post('yandex_client_id');
				$yandex_client_secret = $this->input->post('yandex_client_secret');
				$bitbucket_key = $this->input->post('bitbucket_key');
				$bitbucket_secret = $this->input->post('bitbucket_secret');

				$this->form_validation->set_rules('task', 'Task', 'required|trim|xss_clean');
				
				if(isset($facebook_app_id) && $facebook_app_id!='') {
					$this->form_validation->set_rules('facebook_app_id', 'Facebook App ID', 'trim|xss_clean');
				}
				if ($this->form_validation->run() && (isset($task) && $task=='settings.update')) {
					
					$social_data=array(
						'enable_facebook'=>$this->input->post('enable_facebook'),
						'enable_twitter'=>$this->input->post('enable_twitter'),
						'enable_gplus'=>$this->input->post('enable_gplus'),
						'enable_linkedin'=>$this->input->post('enable_linkedin'),
						'enable_github'=>$this->input->post('enable_github'),
						'enable_instagram'=>$this->input->post('enable_instagram'),
						'enable_microsoft'=>$this->input->post('enable_microsoft'),
						'enable_envato'=>$this->input->post('enable_envato'),
						'enable_paypal'=>$this->input->post('enable_paypal'),
						'enable_yandex'=>$this->input->post('enable_yandex'),
						'enable_bitbucket'=>$this->input->post('enable_bitbucket'),
						'facebook_app_id'=>$this->input->post('facebook_app_id'),
						'facebook_app_secret'=>$this->input->post('facebook_app_secret'),
						'tw_consumer_key'=>$this->input->post('tw_consumer_key'),
						'tw_consumer_secret'=>$this->input->post('tw_consumer_secret'),
						'google_app_id'=>$this->input->post('google_app_id'),
						'google_app_secret'=>$this->input->post('google_app_secret'),
						'linkedin_client_id'=>$this->input->post('linkedin_client_id'),
						'linkedin_client_secret'=>$this->input->post('linkedin_client_secret'),
						'github_client_id'=>$this->input->post('github_client_id'),
						'github_client_secret'=>$this->input->post('github_client_secret'),
						'instagram_client_id'=>$this->input->post('instagram_client_id'),
						'instagram_client_secret'=>$this->input->post('instagram_client_secret'),
						'microsoft_client_id'=>$this->input->post('microsoft_client_id'),
						'microsoft_client_secret'=>$this->input->post('microsoft_client_secret'),
						'envato_client_id'=>$this->input->post('envato_client_id'),
						'envato_client_secret'=>$this->input->post('envato_client_secret'),
						'paypal_client_id'=>$this->input->post('paypal_client_id'),
						'paypal_client_secret'=>$this->input->post('paypal_client_secret'),
						'yandex_client_id'=>$this->input->post('yandex_client_id'),
						'yandex_client_secret'=>$this->input->post('yandex_client_secret'),
						'bitbucket_key'=>$this->input->post('bitbucket_key'),
						'bitbucket_secret'=>$this->input->post('bitbucket_secret'),
					);
					$update_settings = $this->settings_model->update_config($social_data);
					if($update_settings) {
						$this->session->set_flashdata('success', 'Social Login details are updated successfully');
						redirect(site_url('admin/settings/social'));
					} else { 
						$this->session->set_flashdata('errors', 'There is a problem while update the social login settings');
						redirect(site_url('admin/settings/social'));
					}
				}
				$social = $this->settings_model->get_all();
				$data['social_data'] = $social;
				$data['facebook_app_id'] = array(
					'name'	=> 'facebook_app_id',
					'id'	=> 'facebook_app_id',
					'class' => 'form-control',
					'placeholder'	=> 'App ID',
					'value' =>  set_value('facebook_app_id')?set_value('facebook_app_id'):$social->facebook_app_id,
				);
				$data['facebook_app_secret'] = array(
					'name'	=> 'facebook_app_secret',
					'id'	=> 'facebook_app_secret',
					'class' => 'form-control',
					'placeholder'	=> 'App Secret',
					'value' =>  set_value('facebook_app_secret')?set_value('facebook_app_secret'):$social->facebook_app_secret,
				);
				$data['tw_consumer_key'] = array(
					'name'	=> 'tw_consumer_key',
					'id'	=> 'tw_consumer_key',
					'class' => 'form-control',
					'placeholder'	=> 'Consumer Key (API Key)',
					'value' =>  set_value('tw_consumer_key')?set_value('tw_consumer_key'):$social->tw_consumer_key,
				);
				$data['tw_consumer_secret'] = array(
					'name'	=> 'tw_consumer_secret',
					'id'	=> 'tw_consumer_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Consumer Secret (API Secret)',
					'value' =>  set_value('tw_consumer_secret')?set_value('tw_consumer_secret'):$social->tw_consumer_secret,
				);
				$data['google_app_id'] = array(
					'name'	=> 'google_app_id',
					'id'	=> 'google_app_id',
					'class' => 'form-control',
					'placeholder'	=> 'Client ID',
					'value' =>  set_value('google_app_id')?set_value('google_app_id'):$social->google_app_id,
				);
				$data['google_app_secret'] = array(
					'name'	=> 'google_app_secret',
					'id'	=> 'google_app_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Client secret',
					'value' =>  set_value('google_app_secret')?set_value('google_app_secret'):$social->google_app_secret,
				);
				
				$data['linkedin_client_id'] = array(
					'name'	=> 'linkedin_client_id',
					'id'	=> 'linkedin_client_id',
					'class' => 'form-control',
					'placeholder'	=> 'Client ID',
					'value' =>  set_value('linkedin_client_id')?set_value('linkedin_client_id'):$social->linkedin_client_id,
				);
				
				$data['linkedin_client_secret'] = array(
					'name'	=> 'linkedin_client_secret',
					'id'	=> 'linkedin_client_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Client Secret',
					'value' =>  set_value('linkedin_client_secret')?set_value('linkedin_client_secret'):$social->linkedin_client_secret,
				);
				$data['github_client_id'] = array(
					'name'	=> 'github_client_id',
					'id'	=> 'github_client_id',
					'class' => 'form-control',
					'placeholder'	=> 'Client ID',
					'value' =>  set_value('github_client_id')?set_value('github_client_id'):$social->github_client_id,
				);
				
				$data['github_client_secret'] = array(
					'name'	=> 'github_client_secret',
					'id'	=> 'github_client_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Client Secret',
					'value' =>  set_value('github_client_secret')?set_value('github_client_secret'):$social->github_client_secret,
				);
				$data['instagram_client_id'] = array(
					'name'	=> 'instagram_client_id',
					'id'	=> 'instagram_client_id',
					'class' => 'form-control',
					'placeholder'	=> 'Client ID',
					'value' =>  set_value('instagram_client_id')?set_value('instagram_client_id'):$social->instagram_client_id,
				);
				
				$data['instagram_client_secret'] = array(
					'name'	=> 'instagram_client_secret',
					'id'	=> 'instagram_client_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Client Secret',
					'value' =>  set_value('instagram_client_secret')?set_value('instagram_client_secret'):$social->instagram_client_secret,
				);
				$data['microsoft_client_id'] = array(
					'name'	=> 'microsoft_client_id',
					'id'	=> 'microsoft_client_id',
					'class' => 'form-control',
					'placeholder'	=> 'Client ID',
					'value' =>  set_value('microsoft_client_id')?set_value('microsoft_client_id'):$social->microsoft_client_id,
				);
				
				$data['microsoft_client_secret'] = array(
					'name'	=> 'microsoft_client_secret',
					'id'	=> 'microsoft_client_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Client Secret',
					'value' =>  set_value('microsoft_client_secret')?set_value('microsoft_client_secret'):$social->microsoft_client_secret,
				);
				
				$data['envato_client_id'] = array(
					'name'	=> 'envato_client_id',
					'id'	=> 'envato_client_id',
					'class' => 'form-control',
					'placeholder'	=> 'Client ID',
					'value' =>  set_value('envato_client_id')?set_value('envato_client_id'):$social->envato_client_id,
				);
				
				$data['envato_client_secret'] = array(
					'name'	=> 'envato_client_secret',
					'id'	=> 'envato_client_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Client Secret',
					'value' =>  set_value('envato_client_secret')?set_value('envato_client_secret'):$social->envato_client_secret,
				);
				$data['paypal_client_id'] = array(
					'name'	=> 'paypal_client_id',
					'id'	=> 'paypal_client_id',
					'class' => 'form-control',
					'placeholder'	=> 'Client ID',
					'value' =>  set_value('paypal_client_id')?set_value('paypal_client_id'):$social->paypal_client_id,
				);
				
				$data['paypal_client_secret'] = array(
					'name'	=> 'paypal_client_secret',
					'id'	=> 'paypal_client_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Client Secret',
					'value' =>  set_value('paypal_client_secret')?set_value('paypal_client_secret'):$social->paypal_client_secret,
				);
				$data['yandex_client_id'] = array(
					'name'	=> 'yandex_client_id',
					'id'	=> 'yandex_client_id',
					'class' => 'form-control',
					'placeholder'	=> 'Client ID',
					'value' =>  set_value('yandex_client_id')?set_value('yandex_client_id'):$social->yandex_client_id,
				);
				
				$data['yandex_client_secret'] = array(
					'name'	=> 'yandex_client_secret',
					'id'	=> 'yandex_client_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Client Secret',
					'value' =>  set_value('yandex_client_secret')?set_value('yandex_client_secret'):$social->yandex_client_secret,
				);
				$data['bitbucket_key'] = array(
					'name'	=> 'bitbucket_key',
					'id'	=> 'bitbucket_key',
					'class' => 'form-control',
					'placeholder'	=> 'Consumer Key',
					'value' =>  set_value('bitbucket_key')?set_value('bitbucket_key'):$social->bitbucket_key,
				);
				
				$data['bitbucket_secret'] = array(
					'name'	=> 'bitbucket_secret',
					'id'	=> 'bitbucket_secret',
					'class' => 'form-control',
					'placeholder'	=> 'Consumer Secret',
					'value' =>  set_value('bitbucket_secret')?set_value('bitbucket_secret'):$social->bitbucket_secret,
				);
				
				$data['errors'] =  $this->session->flashdata('errors');
				$data['success'] =  $this->session->flashdata('success');

				$this->load->view(get_theme_directory().'/social_settings', $data);
			} else  {
				$this->session->set_flashdata('errors', 'You dont have permission to access this part of the site.');
				redirect(site_url('/admin/dashboard'));
			}
		} else {
				$this->session->set_flashdata('errors', 'You dont have permission to access the admin part of the site.');
				redirect(site_url('auth/profile/'));
		}
		
		}
	 
	 }
	 
}

/* End of file Settings.php */
/* Location: ./modules/admin/controllers/Settings.php */