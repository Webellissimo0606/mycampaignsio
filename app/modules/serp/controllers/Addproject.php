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
class Addproject extends CI_Controller
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
				$data['projectlist'] = $this->projectadd->getproject();
				
				$data['errors'] = array();
							
					$user_id = $this->ci_auth->get_user_id();
					$data['message'] = $this->session->flashdata('message');
					$data['success'] = $this->session->flashdata('success');


				$data['seo_title'] = 'Add Project';
				
				$data['name'] = array(
					'name'	=> 'name',
					'id'	=> 'name',
					'class' => 'form-control',
					'placeholder'	=> 'Name',
					'value' =>  '',
				);
				
				$data['title'] = array(
					'name'	=> 'title',
					'id'	=> 'title',
					'class' => 'form-control',
					'placeholder'	=> 'Title',
					'value' =>  '',
				);

				$data['website'] = array(
					'name'	=> 'website',
					'id'	=> 'website',
					'class' => 'form-control',
					'placeholder'	=> 'Website',
					'value' =>  '',
				);
				
				$data['description'] = array(
					'name'	=> 'description',
					'id'	=> 'description',
					'class' => 'form-control',
					'placeholder'	=> 'Description',
					'value' =>  '',
				);
				
				$data['keywords'] = array(
					'name'	=> 'keywords',
					'id'	=> 'keywords',
					'class' => 'form-control',
					'placeholder'	=> 'Keywords',
					'value' =>  '',
				);
				
				$data['submit'] = array(
					'name' => 'button',
					'class' => 'btn btn-warning',
					'id' => 'button',
					'value' => 'true',
					'type' => 'Submit',
					'content' => '<i class="icon-menu2"></i> Add Project'
				);
				
				$this->load->view(get_template_directory().'addproject', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
			}
		}
		
	
	function insertproject()
	{
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			if($this->ci_auth->canDo('login_to_frontend')) {
				$user_id = $this->ci_auth->get_user_id();
				$this->form_validation->set_rules('name', 'Name', 'trim|required');
				$this->form_validation->set_rules('title', 'Title', 'trim|required');
				$this->form_validation->set_rules('website', 'Website', 'trim|required|xss_clean|min_length[3]|max_length[50]');
				$this->form_validation->set_rules('keywords', 'Keywords', 'trim|required|xss_clean|min_length[3]|max_length[100]');
				//$this->form_validation->set_rules('enginee', 'Search Enginee', 'trim|required|xss_clean|min_length[2]|max_length[20]');

				$data['errors'] = array();
				if ($this->form_validation->run()) { // validation ok
				
					$user_id = $this->ci_auth->get_user_id();
					
						$curl = curl_init();
						curl_setopt ($curl, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=WEBSITE&action=createWebsite&name=".$this->input->post('name')."&url=".$this->input->post('website')."&user_id=".$user_id."&title=".$this->input->post('title')."&description=".$this->input->post('description')."&keyword=".$this->input->post('keywords')."&status=1");
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						$result = curl_exec ($curl);	
						$jsonresult = ''.$result.'';
						curl_close ($curl);
						$string = json_decode($jsonresult);						
						if($string->response == "Error"){
									
									$processfail = 'This Website Already Exsistes';
									$data['errors'] = $processfail;
									$data['message'] = $this->session->flashdata('message');
									$data['success'] = $this->session->flashdata('success');
									$data['seo_title'] = 'Add Project';
									$data['projectlist'] = $this->projectadd->getproject();
									$data['name'] = array(
										'name'	=> 'name',
										'id'	=> 'name',
										'class' => 'form-control',
										'placeholder'	=> 'Name',
										'value' =>  '',
									);
									
									$data['title'] = array(
										'name'	=> 'title',
										'id'	=> 'title',
										'class' => 'form-control',
										'placeholder'	=> 'Title',
										'value' =>  '',
									);

									$data['website'] = array(
										'name'	=> 'website',
										'id'	=> 'website',
										'class' => 'form-control',
										'placeholder'	=> 'Website',
										'value' =>  '',
									);
									
									$data['description'] = array(
										'name'	=> 'description',
										'id'	=> 'description',
										'class' => 'form-control',
										'placeholder'	=> 'Description',
										'value' =>  '',
									);
									
									$data['keywords'] = array(
										'name'	=> 'keywords',
										'id'	=> 'keywords',
										'class' => 'form-control',
										'placeholder'	=> 'Keywords',
										'value' =>  '',
									);
									
									$data['submit'] = array(
										'name' => 'button',
										'class' => 'btn btn-warning',
										'id' => 'button',
										'value' => 'true',
										'type' => 'Submit',
										'content' => '<i class="icon-menu2"></i> Add Project'
									);
									
									$this->load->view(get_template_directory().'addproject', $data); 
						} else {
							$website_id = $string->website_id;
							$insert_project = $this->projectadd->insertproject($user_id, $this->input->post('name'),$this->input->post('title'),$this->input->post('description'),$this->input->post('website'),$this->input->post('keywords'),$this->input->post('enginee'),$website_id);
							if($insert_project) {								
								if (strpos($this->input->post('keywords'),',') !== false) {
									$exkey = explode(',',$this->input->post('keywords'));
									for($i=0;$i<count($exkey);$i++){
									
										$curl = curl_init();
										curl_setopt ($curl, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=KEYWORD&action=createKeyword&name=".$exkey[$i]."&website_id=".$website_id."&user_id=1&searchengines=".$this->input->post('enginee')."&status=1");
										curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
										$result = curl_exec ($curl);	
										$jsonresult = ''.$result.'';
										curl_close ($curl);
										$string = json_decode($jsonresult);
										$keywordid = $string->keyword_id;
										$insert_projectkey = $this->projectadd->insertprojectkey($keywordid,$exkey[$i],$website_id);
										
									}
								} else {
										$curl = curl_init();
										curl_setopt ($curl, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=KEYWORD&action=createKeyword&name=".$this->input->post('keywords')."&website_id=".$website_id."&user_id=1&searchengines=".$this->input->post('enginee')."&status=1");
										curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
										$result = curl_exec ($curl);	
										$jsonresult = ''.$result.'';
										curl_close ($curl);
										$string = json_decode($jsonresult);
										$keywordid = $string->keyword_id;
										$insert_projectkey = $this->projectadd->insertprojectkey($keywordid,$this->input->post('keywords'),$website_id);
									
								}
								$prosuccess = 'Project Added Successfully';
								$this->session->set_flashdata('success', $prosuccess);
								redirect(site_url('serp/addproject')); 
							} else {
								$processfail = 'Project Added Failed';
								$this->session->set_flashdata('errors', $processfail);
								redirect(site_url('serp/addproject')); 
							}
					}
				}  else { $data['errors'] =  (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }
				
				$data['message'] = $this->session->flashdata('message');
				$data['success'] = $this->session->flashdata('success');
				$data['seo_title'] = 'Add Project';
				$data['projectlist'] = $this->projectadd->getproject();
							$data['name'] = array(
								'name'	=> 'name',
								'id'	=> 'name',
								'class' => 'form-control',
								'placeholder'	=> 'Name',
								'value' =>  '',
							);
							
							$data['title'] = array(
								'name'	=> 'title',
								'id'	=> 'title',
								'class' => 'form-control',
								'placeholder'	=> 'Title',
								'value' =>  '',
							);

							$data['website'] = array(
								'name'	=> 'website',
								'id'	=> 'website',
								'class' => 'form-control',
								'placeholder'	=> 'Website',
								'value' =>  '',
							);
							
							$data['description'] = array(
								'name'	=> 'description',
								'id'	=> 'description',
								'class' => 'form-control',
								'placeholder'	=> 'Description',
								'value' =>  '',
							);
							
							$data['keywords'] = array(
								'name'	=> 'keywords',
								'id'	=> 'keywords',
								'class' => 'form-control',
								'placeholder'	=> 'Keywords',
								'value' =>  '',
							);
							
							$data['submit'] = array(
								'name' => 'button',
								'class' => 'btn btn-warning',
								'id' => 'button',
								'value' => 'true',
								'type' => 'Submit',
								'content' => '<i class="icon-menu2"></i> Add Project'
							);
				
				$this->load->view(get_template_directory().'addproject', $data); 
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}
	
	function deleteproject($id){
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			if($this->ci_auth->canDo('login_to_frontend')) {
				$data['errors'] = array();
				
				$projectdata = $this->projectadd->getprojectdata($id);
				$prodata = $projectdata[0];
				$websiteid = $prodata->websiteid;
				$delete_project = $this->projectadd->deleteproject($id);
					if($delete_project) { 					
						$curldel = curl_init();
						curl_setopt ($curldel, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=WEBSITE&action=deleteWebsite&id=".$websiteid."");
						curl_setopt($curldel, CURLOPT_RETURNTRANSFER, 1);
						$result = curl_exec ($curldel);	
						$jsonresult = ''.$result.'';
						curl_close ($curldel);
						$string = json_decode($jsonresult);						
						$prosuccess = 'Project Deleted Successfully';
						$this->session->set_flashdata('success', $prosuccess);
						redirect(site_url('serp/addproject')); 
					} else {
						$processfail = 'Project Deleted Failed';
						$this->session->set_flashdata('errors', $processfail);
						redirect(site_url('serp/addproject')); 
					}
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}
	
	
	function editproject($id)
	{
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			if($this->ci_auth->canDo('login_to_frontend')) {
				$data['projectlist'] = $this->projectadd->getproject();
				$projectdata = $this->projectadd->getprojectdata($id);
				$prodata = $projectdata[0];
				
				$data['seo_title'] = 'Edit Project';

				$data['name'] = array(
								'name'	=> 'name',
								'id'	=> 'name',
								'class' => 'form-control',
								'placeholder'	=> 'Name',
								'value' =>  $prodata->name,
							);
							
							$data['title'] = array(
								'name'	=> 'title',
								'id'	=> 'title',
								'class' => 'form-control',
								'placeholder'	=> 'Title',
								'value' =>  $prodata->title,
							);
							
							$data['website'] = array(
							'name'	=> 'website',
							'id'	=> 'website',
							'class' => 'form-control',
							'placeholder'	=> 'Website',
							'value' =>  $prodata->vWebsite,
							);
						
							$data['description'] = array(
										'name'	=> 'title',
										'id'	=> 'title',
										'class' => 'form-control',
										'placeholder'	=> 'Title',
										'value' =>  $prodata->description,
									);
				
				$data['enginee'] = $prodata->vEnginee;
				$data['projectid'] = $id;
				$data['websiteid'] = $prodata->websiteid;
				
				$data['submit'] = array(
					'name' => 'button',
					'class' => 'btn btn-warning',
					'id' => 'button',
					'value' => 'true',
					'type' => 'Submit',
					'content' => '<i class="icon-menu2"></i> Add Project'
				);
				
				$this->load->view(get_template_directory().'editproject', $data); 
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}
	
	
	function updateproject()
	{
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			if($this->ci_auth->canDo('login_to_frontend')) {
				$user_id = $this->ci_auth->get_user_id();
				$this->form_validation->set_rules('name', 'Name', 'trim|required');
				$this->form_validation->set_rules('title', 'Title', 'trim|required');				
				$this->form_validation->set_rules('website', 'Website', 'trim|required|xss_clean|min_length[3]|max_length[50]');
				$this->form_validation->set_rules('keywords', 'Keywords', 'trim|required|xss_clean|min_length[3]|max_length[100]');
				//$this->form_validation->set_rules('enginee', 'Search Enginee', 'trim|required|xss_clean|min_length[2]|max_length[20]');

				$data['errors'] = array();
				if ($this->form_validation->run()) { // validation ok
				
					$user_id = $this->ci_auth->get_user_id();
						$id= $this->input->post('projectid');
						$curl = curl_init();
						curl_setopt ($curl, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=WEBSITE&action=updateWebsite&id=".$this->input->post('websiteid')."&name=".$this->input->post('name')."&url=".$this->input->post('website')."&user_id=".$user_id."&title=".$this->input->post('title')."&description=".$this->input->post('description')."&keyword=".$this->input->post('keywords')."&status=1");
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						$result = curl_exec ($curl);	
						$jsonresult = ''.$result.'';
						curl_close ($curl);
						$string = json_decode($jsonresult);
						
						$string = json_decode($jsonresult);						
						if($string->response == "Error"){
									
									$processfail = 'This Website Already Exsistes';
									$data['errors'] = $processfail;
									$data['message'] = $this->session->flashdata('message');
									$data['success'] = $this->session->flashdata('success');
									
									$data['seo_title'] = 'Edit Project';
											$data['projectlist'] = $this->projectadd->getproject();
											$projectdata = $this->projectadd->getprojectdata($this->input->post('projectid'));
											$prodata = $projectdata[0];
											
											$data['name'] = array(
												'name'	=> 'name',
												'id'	=> 'name',
												'class' => 'form-control',
												'placeholder'	=> 'Name',
												'value' =>  $prodata->name,
											);
											
											$data['title'] = array(
												'name'	=> 'title',
												'id'	=> 'title',
												'class' => 'form-control',
												'placeholder'	=> 'Title',
												'value' =>  $prodata->title,
											);
											$data['website'] = array(
												'name'	=> 'website',
												'id'	=> 'website',
												'class' => 'form-control',
												'placeholder'	=> 'Website',
												'value' =>  $this->input->post('website'),
											);
											
											$data['description'] = array(
												'name'	=> 'title',
												'id'	=> 'title',
												'class' => 'form-control',
												'placeholder'	=> 'Title',
												'value' =>  $prodata->description,
											);
											
											
											$data['enginee'] = $prodata->vEnginee;
											$data['projectid'] = $id;
											$data['websiteid'] = $prodata->websiteid;
				
											
											$data['submit'] = array(
												'name' => 'button',
												'class' => 'btn btn-warning',
												'id' => 'button',
												'value' => 'true',
												'type' => 'Submit',
												'content' => '<i class="icon-menu2"></i> Add Project'
											);
											
											$this->load->view(get_template_directory().'editproject', $data);

									} else {
								$website_id = $this->input->post('websiteid');
								$update_project = $this->projectadd->updateproject($user_id, $this->input->post('name'),$this->input->post('title'),$this->input->post('description'),$website_id,$this->input->post('website'),$this->input->post('enginee'),$this->input->post('projectid'));
								if($update_project) { 
									$prosuccess = 'Project Updated Successfully';
									$this->session->set_flashdata('success', $prosuccess);
									redirect(site_url('serp/addproject')); 
								} else {
									$processfail = 'Project Udated Failed';
									$this->session->set_flashdata('errors', $processfail);
									redirect(site_url('serp/addproject')); 
								}
						}
				}  else { $data['errors'] =  (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }
				
				$id = $this->input->post('projectid');
				$data['message'] = $this->session->flashdata('message');
				$data['success'] = $this->session->flashdata('success');
				$data['seo_title'] = 'Edit Project';
				$data['projectlist'] = $this->projectadd->getproject();
				$projectdata = $this->projectadd->getprojectdata($id);
				$prodata = $projectdata[0];
				$data['name'] = array(
								'name'	=> 'name',
								'id'	=> 'name',
								'class' => 'form-control',
								'placeholder'	=> 'Name',
								'value' =>  $prodata->name,
							);
							
							$data['title'] = array(
								'name'	=> 'title',
								'id'	=> 'title',
								'class' => 'form-control',
								'placeholder'	=> 'Title',
								'value' =>  $prodata->title,
							);
				$data['website'] = array(
					'name'	=> 'website',
					'id'	=> 'website',
					'class' => 'form-control',
					'placeholder'	=> 'Website',
					'value' =>  $this->input->post('website'),
				);
				
				$data['description'] = array(
								'name'	=> 'title',
								'id'	=> 'title',
								'class' => 'form-control',
								'placeholder'	=> 'Title',
								'value' =>  $prodata->description,
							);
				
				$data['enginee'] = $prodata->vEnginee;
				$data['projectid'] = $id;
				$data['websiteid'] = $prodata->websiteid;
				
				
				$data['submit'] = array(
					'name' => 'button',
					'class' => 'btn btn-warning',
					'id' => 'button',
					'value' => 'true',
					'type' => 'Submit',
					'content' => '<i class="icon-menu2"></i> Add Project'
				);
				
				$this->load->view(get_template_directory().'editproject', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}
	
	
	function addkeyword($id,$websiteid)
	{
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			
			if($this->ci_auth->canDo('login_to_frontend')) {
			
				$user_id = $this->ci_auth->get_user_id();
				$data['keywordlist'] = $this->projectadd->getkeyword($websiteid);
					
				$projectdata = $this->projectadd->getprojectdata($id);
				
				$prodata = $projectdata[0];
				
				$data['websitename'] =  $prodata->vWebsite;
				$data['errors'] = array();
							
					$user_id = $this->ci_auth->get_user_id();
					$data['message'] = $this->session->flashdata('message');
					$data['success'] = $this->session->flashdata('success');


				$data['seo_title'] = 'Add Keywords';
				
				$data['keyword'] = array(
					'name'	=> 'keyword',
					'id'	=> 'keyword',
					'class' => 'form-control',
					'placeholder'	=> 'keyword',
					'value' =>  '',
				);
				$data['id'] = $id;
				$data['websiteid'] = $websiteid;
				$data['engineid'] = $prodata->vEnginee;
				
				$data['submit'] = array(
					'name' => 'button',
					'class' => 'btn btn-warning',
					'id' => 'button',
					'value' => 'true',
					'type' => 'Submit',
					'content' => '<i class="icon-menu2"></i> Add Keyword'
				);
				
				$this->load->view(get_template_directory().'addkeyword', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
			}
		}
	
	
	function insertkeyword()
	{
		
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			if($this->ci_auth->canDo('login_to_frontend')) {
				$user_id = $this->ci_auth->get_user_id();
				$this->form_validation->set_rules('keyword', 'Keyword', 'trim|required');
				
				$data['errors'] = array();
				if ($this->form_validation->run()) { // validation ok
				
					$user_id = $this->ci_auth->get_user_id();
					
										$curl = curl_init();
										curl_setopt ($curl, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=KEYWORD&action=createKeyword&name=".$this->input->post('keyword')."&website_id=".$this->input->post('websiteid')."&user_id=1&searchengines=".$this->input->post('searchengineid')."&status=1");
										curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
										$result = curl_exec ($curl);	
										$jsonresult = ''.$result.'';
										curl_close ($curl);
										$string = json_decode($jsonresult);										
															
						if($string->response == "Error"){
									
									$processfail = 'This Keyword Already Exsistes';
									$data['errors'] = $processfail;
									$data['message'] = $this->session->flashdata('message');
									$data['success'] = $this->session->flashdata('success');
									$data['seo_title'] = 'Add Keyword';
									$data['keywordlist'] = $this->projectadd->getkeyword($this->input->post('websiteid'));				
									$projectdata = $this->projectadd->getprojectdata($this->input->post('projectid'));										
									$prodata = $projectdata[0];										
									$data['websitename'] =  $prodata->vWebsite;
									$data['keyword'] = array(
										'name'	=> 'keyword',
										'id'	=> 'keyword',
										'class' => 'form-control',
										'placeholder'	=> 'Keyword',
										'value' =>  '',
									);
									
									$data['id'] = $this->input->post('projectid');
									$data['websiteid'] = $this->input->post('websiteid');
									$data['engineid'] = $this->input->post('searchengineid');
									
									$data['submit'] = array(
										'name' => 'button',
										'class' => 'btn btn-warning',
										'id' => 'button',
										'value' => 'true',
										'type' => 'Submit',
										'content' => '<i class="icon-menu2"></i> Add Project'
									);
									
									$this->load->view(get_template_directory().'addkeyword', $data); 
						} else {
							$keywordid = $string->keyword_id;
							$insert_projectkey = $this->projectadd->insertprojectkey($keywordid,$this->input->post('keyword'),$this->input->post('websiteid'));
							$prosuccess = 'keyword Added Successfully';
							$this->session->set_flashdata('success', $prosuccess);
							redirect(site_url('serp/addproject/addkeyword/'.$this->input->post('projectid').'/'.$this->input->post('websiteid').'')); 
							
						}
				}  else { $data['errors'] =  (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }
				
									
									$data['message'] = $this->session->flashdata('message');
									$data['success'] = $this->session->flashdata('success');
									$data['seo_title'] = 'Add Keyword';
									$data['keywordlist'] = $this->projectadd->getkeyword($this->input->post('websiteid'));				
									$projectdata = $this->projectadd->getprojectdata($this->input->post('projectid'));										
									$prodata = $projectdata[0];										
									$data['websitename'] =  $prodata->vWebsite;
									$data['keyword'] = array(
										'name'	=> 'keyword',
										'id'	=> 'keyword',
										'class' => 'form-control',
										'placeholder'	=> 'Keyword',
										'value' =>  '',
									);
									
									$data['id'] = $this->input->post('projectid');
									$data['websiteid'] = $this->input->post('websiteid');
									$data['engineid'] = $this->input->post('searchengineid');
									
									$data['submit'] = array(
										'name' => 'button',
										'class' => 'btn btn-warning',
										'id' => 'button',
										'value' => 'true',
										'type' => 'Submit',
										'content' => '<i class="icon-menu2"></i> Add Project'
									);
									
									$this->load->view(get_template_directory().'addkeyword', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}
	
	function deletekeyword($keywordid,$websiteid,$projectid){
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			if($this->ci_auth->canDo('login_to_frontend')) {
				$data['errors'] = array();
				
				$delete_keyword = $this->projectadd->deletekey($keywordid,$websiteid);
					if($delete_keyword) { 					
						$curldel = curl_init();
						curl_setopt ($curldel, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=KEYWORD&action=deleteKeyword&id=".$keywordid."");
						curl_setopt($curldel, CURLOPT_RETURNTRANSFER, 1);
						$result = curl_exec ($curldel);	
						$jsonresult = ''.$result.'';
						curl_close ($curldel);
						$string = json_decode($jsonresult);						
						$prosuccess = 'Keyword Deleted Successfully';
						$this->session->set_flashdata('success', $prosuccess);
						redirect(site_url('serp/addproject/addkeyword/'.$projectid.'/'.$websiteid));
						
					} else {
						$processfail = 'Keyword Deleted Failed';
						$this->session->set_flashdata('errors', $processfail);
						redirect(site_url('serp/addproject/addkeyword/'.$projectid.'/'.$websiteid));
					}
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}
	
	function editkeyword($keywordid,$websiteid,$id)
	{
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			
			if($this->ci_auth->canDo('login_to_frontend')) {
			
				$user_id = $this->ci_auth->get_user_id();
				$data['keywordlist'] = $this->projectadd->getkeyword($websiteid);
					
				$projectdata = $this->projectadd->getprojectdata($id);
				
				$prodata = $projectdata[0];
				
				$data['websitename'] =  $prodata->vWebsite;
				$keydata = $this->projectadd->getkeyworddata($keywordid,$websiteid);
				
				$keydatas = $keydata[0];
				$data['errors'] = array();
							
					$user_id = $this->ci_auth->get_user_id();
					$data['message'] = $this->session->flashdata('message');
					$data['success'] = $this->session->flashdata('success');


				$data['seo_title'] = 'Edit Keywords';
				
				$data['keyword'] = array(
					'name'	=> 'keyword',
					'id'	=> 'keyword',
					'class' => 'form-control',
					'placeholder'	=> 'keyword',
					'value' =>  $keydatas->keywordname,
				);
				$data['id'] = $id;
				$data['websiteid'] = $websiteid;
				$data['engineid'] = $prodata->vEnginee;
				$data['keywordid'] = $keywordid;
				
				$data['submit'] = array(
					'name' => 'button',
					'class' => 'btn btn-warning',
					'id' => 'button',
					'value' => 'true',
					'type' => 'Submit',
					'content' => '<i class="icon-menu2"></i> Update Keyword'
				);
				
				$this->load->view(get_template_directory().'editkeyword', $data);
				
			} else {
				redirect(site_url('/admin/login'));
			}
			}
		}
		
		
		function updatekeyword()
	{
		
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) { // logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
			if($this->ci_auth->canDo('login_to_frontend')) {
				$user_id = $this->ci_auth->get_user_id();
				$this->form_validation->set_rules('keyword', 'Keyword', 'trim|required');
				
				$data['errors'] = array();
				if ($this->form_validation->run()) { // validation ok
				
					$user_id = $this->ci_auth->get_user_id();
					
										$curl = curl_init();
										curl_setopt ($curl, CURLOPT_URL, "http://campaigns.io/keywords/api/api.php?SP_API_KEY=46b795020a5afc85639ea8fba5e69ad4&API_SECRET=Dania2905&category=KEYWORD&action=updateKeyword&id=".$this->input->post('keywordid')."&name=".$this->input->post('keyword')."&website_id=".$this->input->post('websiteid')."&user_id=1&searchengines=".$this->input->post('searchengineid')."&status=1");
										curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
										$result = curl_exec ($curl);	
										$jsonresult = ''.$result.'';
										curl_close ($curl);
										$string = json_decode($jsonresult);										
															
						if($string->response == "Error"){
									
									$processfail = 'This Keyword Already Exsistes';
									$data['errors'] = $processfail;
									$data['message'] = $this->session->flashdata('message');
									$data['success'] = $this->session->flashdata('success');
									$data['seo_title'] = 'Edit Keyword';
									$data['keywordlist'] = $this->projectadd->getkeyword($this->input->post('websiteid'));				
									$projectdata = $this->projectadd->getprojectdata($this->input->post('projectid'));										
									$prodata = $projectdata[0];										
									$data['websitename'] =  $prodata->vWebsite;
									
									$keydata = $this->projectadd->getkeyworddata($keywordid,$websiteid);				
									$keydatas = $keydata[0];
									$data['errors'] = array();
									$data['keyword'] = array(
										'name'	=> 'keyword',
										'id'	=> 'keyword',
										'class' => 'form-control',
										'placeholder'	=> 'Keyword',
										'value' =>  $keydatas->keywordname,
									);
									
									$data['id'] = $this->input->post('projectid');
									$data['websiteid'] = $this->input->post('websiteid');
									$data['engineid'] = $this->input->post('searchengineid');
									$data['keywordid'] = $this->input->post('keywordid');
									
									$data['submit'] = array(
										'name' => 'button',
										'class' => 'btn btn-warning',
										'id' => 'button',
										'value' => 'true',
										'type' => 'Submit',
										'content' => '<i class="icon-menu2"></i> Add Project'
									);
									
									$this->load->view(get_template_directory().'editkeyword', $data); 
						} else {
							$keywordid = $this->input->post('keywordid');
							$insert_projectkey = $this->projectadd->updateprojectkey($keywordid,$this->input->post('keyword'),$this->input->post('websiteid'));
							$prosuccess = 'keyword Updated Successfully';
							$this->session->set_flashdata('success', $prosuccess);
							redirect(site_url('serp/addproject/addkeyword/'.$this->input->post('projectid').'/'.$this->input->post('websiteid').'')); 
							
						}
				}  else { $data['errors'] =  (validation_errors()) ? validation_errors() :  $this->session->flashdata('errors'); }
				
									
									$data['message'] = $this->session->flashdata('message');
									$data['success'] = $this->session->flashdata('success');
									$data['seo_title'] = 'Edit Keyword';
									$data['keywordlist'] = $this->projectadd->getkeyword($this->input->post('websiteid'));				
									$projectdata = $this->projectadd->getprojectdata($this->input->post('projectid'));										
									$prodata = $projectdata[0];										
									$data['websitename'] =  $prodata->vWebsite;
									$keydata = $this->projectadd->getkeyworddata($this->input->post('keywordid'),$this->input->post('websiteid'));				
									$keydatas = $keydata[0];
									$data['keyword'] = array(
										'name'	=> 'keyword',
										'id'	=> 'keyword',
										'class' => 'form-control',
										'placeholder'	=> 'Keyword',
										'value' =>  $keydatas->keywordname,
									);
									
									$data['id'] = $this->input->post('projectid');
									$data['websiteid'] = $this->input->post('websiteid');
									$data['engineid'] = $this->input->post('searchengineid');
									$data['keywordid'] = $this->input->post('keywordid');
									
									$data['submit'] = array(
										'name' => 'button',
										'class' => 'btn btn-warning',
										'id' => 'button',
										'value' => 'true',
										'type' => 'Submit',
										'content' => '<i class="icon-menu2"></i> Add Project'
									);
									
									$this->load->view(get_template_directory().'editkeyword', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}
	
}
/* End of file profile.php */
/* Location: ./application/controllers/profile.php */
