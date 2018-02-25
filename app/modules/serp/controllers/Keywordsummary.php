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
class Keywordsummary extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
		$this->load->library('my_form_validation');		
		$this->load->model('serp/projectadd');
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
				$data['projectlist'] = $this->projectadd->getprojectnames($user_id);
				$projectdata = $this->projectadd->getprojectnames($user_id);
				$prodata = $projectdata[0];
				$data['errors'] = array();
							
					$user_id = $this->ci_auth->get_user_id();
					$data['message'] = $this->session->flashdata('message');
					$data['success'] = $this->session->flashdata('success');
					$data['seo_title'] = 'Keywords Position Summary';
					$data['selwebsite'] = $prodata->websiteid;
					$data['fromdate'] = date('Y-m-d');
					$data['todate'] = date('Y-m-d');
					
					$data['submit'] = array(
					'name' => 'button',
					'class' => 'btn btn-warning',
					'id' => 'button',
					'value' => 'true',
					'type' => 'Submit',
					'content' => '<i class="icon-menu2"></i> Search'
					);
				
					$curl = curl_init();
						curl_setopt ($curl, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=KEYWORD&action=getReportByWebsiteId&id=".$prodata->websiteid."&from_time=".date("Y-m-d")."&to_time=".date("Y-m-d")."");
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						$result = curl_exec ($curl);	
						$jsonresult = ''.$result.'';
						curl_close ($curl);
						$string = json_decode($jsonresult);
						$data['repdata'] = $string;
				
				$this->load->view(get_template_directory().'keywordsummary', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
			}
		}
		
		
		function search()
	{
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			
			if($this->ci_auth->canDo('login_to_frontend')) {
			
				$user_id = $this->ci_auth->get_user_id();
				$data['projectlist'] = $this->projectadd->getprojectnames($user_id);
				$projectdata = $this->projectadd->getprojectnames($user_id);
				$prodata = $projectdata[0];
				$data['errors'] = array();
							
					$user_id = $this->ci_auth->get_user_id();
					$data['message'] = $this->session->flashdata('message');
					$data['success'] = $this->session->flashdata('success');
					$data['seo_title'] = 'Keywords Position Summary';
					
					$data['selwebsite'] = $this->input->post('website');
					$data['fromdate'] = $this->input->post('fromdate');
					$data['todate'] = $this->input->post('todate');
					
					$data['submit'] = array(
					'name' => 'button',
					'class' => 'btn btn-warning',
					'id' => 'button',
					'value' => 'true',
					'type' => 'Submit',
					'content' => '<i class="icon-menu2"></i> Search'
					);
				
					$curl = curl_init();
					curl_setopt ($curl, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=KEYWORD&action=getReportByWebsiteId&id=".$this->input->post('website')."&from_time=".$this->input->post('fromdate')."&to_time=".$this->input->post('todate')."");
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					$result = curl_exec ($curl);	
					$jsonresult = ''.$result.'';
					curl_close ($curl);
					$string = json_decode($jsonresult);
					$data['repdata'] = $string;
				
					$this->load->view(get_template_directory().'keywordsummary', $data);
			} else {
					redirect(site_url('/admin/login'));
			}
			}
		}
		
	
}
/* End of file profile.php */
/* Location: ./application/controllers/profile.php */