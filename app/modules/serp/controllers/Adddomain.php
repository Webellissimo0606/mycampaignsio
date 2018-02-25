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
require_once APPPATH. 'libraries/GoogleTracker.php';
class Adddomain extends CI_Controller
{
	private $ci;
	function __construct()
	{
		parent::__construct();
		//$ci =& get_instance();
		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
		$this->load->library('my_form_validation');
		$this->load->model('serp/domain_model');		
		//$this->load->model('serp/projectadd');
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
				$data['errors'] = array();		
				$user_id = $this->ci_auth->get_user_id();
				$data['message'] = $this->session->flashdata('message');
				$data['success'] = $this->session->flashdata('success');
				$data['seo_title'] = 'Add Domain';	
				$this->load->view(get_template_directory().'addDomain', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
			}
		}
		
	
	function insertDomain()
	{
	    $this->load->library('../modules/serp/controllers/googleTracker.php');
            /*$this->googleTracker = new GoogleTracker(array ('lsp'), 'adventuretime.wikia.com', 50);
	    $this->googleTracker->run();
            print_r($this->googleTracker->get_results());
            echo "================<br>";
            print_r($this->googleTracker->get_debug_info());*/
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$data['errors'] = array();		
			if($this->ci_auth->canDo('login_to_frontend')) {
				$user_id = $this->ci_auth->get_user_id();
				$this->form_validation->set_rules('domain_name', 'domain_name', 'trim|required');
				if ($this->form_validation->run()) { 
				$domain['domain_name'] = $this->input->post('domain_name');
			        $domain['user_id'] = $user_id;
			        $domain['created_date'] = date('Y-m-d H:i:s');
				$result = $this->domain_model->create($domain);
					if($result)
					{
					   $prosuccess = 'Domain Added Successfully';
					   $this->session->set_flashdata('success', $prosuccess);
					   redirect('/serp/adddomain/');
					}
					else
					{
					   $processfail = 'Project Added Failed';
	      				   $this->session->set_flashdata('errors', $processfail);
					   redirect('/serp/adddomain/');
					}
				}
				else
				{
					$data['errors'] =  (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors');
					$this->session->set_flashdata('errors', $data);
					//$this->load->view(get_template_directory().'addDomain', $data);
					redirect('/serp/adddomain/');
				}
			}
		}
	}
	function editDomain()
	{

	}
	function deleteDomain(){
		
	}
	function updateDomain()
	{
		
	}
	function listDomain()
	{
		
	}
	
}

/* End of file profile.php */
/* Location: ./application/controllers/profile.php */
