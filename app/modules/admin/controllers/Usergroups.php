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

class Usergroups extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
		$this->load->model('auth/usergroups_model');
	}

	function index()
	{
		$this->usergroups();
	}


	/**
	 * List all users / Except super admin
	 *
	 */
	 function usergroups()
	 {
		 
		 
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('/admin/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
		redirect('/auth/sendactivation/');
		} else { /* logged in */
		
		if($this->ci_auth->canDo('access_backend')) {
			if($this->ci_auth->canDo('view_user_groups')) {

				$usergroups=$this->usergroups_model->usergroups(); 
				$data['usergroups'] = $usergroups;
				foreach($usergroups as $key => $usergroup) {
					
					if($usergroup->id==1) { 
						$data['usergroups'][$key]->canEdit=0;
						$data['usergroups'][$key]->canDelete=0; 
					} else if($usergroup->id==2) {
						if($this->ci_auth->is_superadmin()) {
							if($this->ci_auth->canDo('edit_user_groups')) {
							$data['usergroups'][$key]->canEdit=1;
							}
						} else {
							if($this->ci_auth->canDo('edit_user_groups')) {
							$data['usergroups'][$key]->canEdit=0;
							}
						}
						$data['usergroups'][$key]->canDelete=0; 
					} else {
						if($this->ci_auth->canDo('edit_user_groups')) {
							$data['usergroups'][$key]->canEdit=1; 
						} else {
							$data['usergroups'][$key]->canEdit=0; 
						}
						if($this->ci_auth->canDo('delete_user_groups')) {
							$data['usergroups'][$key]->canDelete=1; 
						} else {
							$data['usergroups'][$key]->canDelete=0; 
						}
					}
					
				}
				$data['errors'] =  (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
				$data['success'] =  $this->session->flashdata('success');
				$this->load->view(get_theme_directory().'/usergroups', $data);
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
	 * New User Group
	 *
	 */
	 function newusergroup()
	 {
		 
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('/admin/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
		redirect('/auth/sendactivation/');
		} else { /* logged in */
		
		if($this->ci_auth->canDo('access_backend')) {
			if($this->ci_auth->canDo('edit_user_groups')) {
		
				$data['seo_title'] = 'New User Group'; 
				
				$this->form_validation->set_rules('group_name', 'Group Name', 'trim|required|xss_clean');
				$this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
		
				if ($this->form_validation->run()) {
					$usergroupdata=array('name'=>$this->form_validation->set_value('group_name'),'status'=>$this->form_validation->set_value('status'),'permissions'=>json_encode($this->input->post('permission')));
					$insUserGroup=$this->usergroups_model->newusergroup($usergroupdata); 
					if($insUserGroup) {
						$this->session->set_flashdata('success', 'New User group added successfully');
						redirect(site_url('admin/usergroups/editusergroup/'.$insUserGroup));
					} else {
						$this->session->set_flashdata('errors', 'There is a problem while create new user group.');
					}
					
				} 
				$data['errors'] =  (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
				$data['success'] =  $this->session->flashdata('success');
				if($this->ci_auth->is_superadmin() || $this->ci_auth->is_admin()) { 
					$data['show_delete_user']=1;
					$data['show_delete_user_group']=1;
				} else {
					$data['show_delete_user']=0;
					$data['show_delete_user_group']=0;
				}
				$group_permissions = json_decode($this->input->post('permission'));
				$data['group_name'] = array(
					'name'	=> 'group_name',
					'id'	=> 'group_name',
					'class' => 'required form-control',
					'placeholder'	=> 'Group Name',
					'value' =>  $this->form_validation->set_value('group_name'),
					'maxlength'	=> 80,
					'size'	=> 30,
				);
				
		
				/* User Permissions*/
				if(isset($group_permissions->access_backend) && $group_permissions->access_backend==1) { $access_backend=TRUE; } else { $access_backend=FALSE; }
				$data['access_backend'] = array(
					'name'        => 'permission[access_backend]',
					'id'          => 'access_backend',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $access_backend,
					'style'       => '',
				);
				if(isset($group_permissions->view_users) && $group_permissions->view_users==1) { $view_users=TRUE; } else { $view_users=FALSE; }
				$data['view_users'] = array(
					'name'        => 'permission[view_users]',
					'id'          => 'view_users',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $view_users,
					'style'       => '',
				);
				if(isset($group_permissions->impersonate_users) && $group_permissions->impersonate_users==1) { $impersonate_users=TRUE; } else { $impersonate_users=FALSE; }
				$data['impersonate_users'] = array(
					'name'        => 'permission[impersonate_users]',
					'id'          => 'impersonate_users',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $impersonate_users,
					'style'       => '',
				);
				if(isset($group_permissions->edit_users) && $group_permissions->edit_users==1) { $edit_users=TRUE; } else { $edit_users=FALSE; }
				$data['edit_users'] = array(
					'name'        => 'permission[edit_users]',
					'id'          => 'edit_users',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $edit_users,
					'style'       => '',
				);
				if(isset($group_permissions->delete_users) && $group_permissions->delete_users==1) { $delete_users=TRUE; } else { $delete_users=FALSE; }
				$data['delete_users'] = array(
					'name'        => 'permission[delete_users]',
					'id'          => 'delete_users',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $delete_users,
					'style'       => '',
				);
				if(isset($group_permissions->view_user_groups) && $group_permissions->view_user_groups==1) { $view_user_groups=TRUE; } else { $view_user_groups=FALSE; }
				$data['view_user_groups'] = array(
					'name'        => 'permission[view_user_groups]',
					'id'          => 'view_user_groups',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $view_user_groups,
					'style'       => '',
				);
				if(isset($group_permissions->edit_user_groups) && $group_permissions->edit_user_groups==1) { $edit_user_groups=TRUE; } else { $edit_user_groups=FALSE; }
				$data['edit_user_groups'] = array(
					'name'        => 'permission[edit_user_groups]',
					'id'          => 'edit_user_groups',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $edit_user_groups,
					'style'       => '',
				);
				if(isset($group_permissions->delete_user_groups) && $group_permissions->delete_user_groups==1) { $delete_user_groups=TRUE; } else { $delete_user_groups=FALSE; }
				$data['delete_user_groups'] = array(
					'name'        => 'permission[delete_user_groups]',
					'id'          => 'delete_user_groups',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $delete_user_groups,
					'style'       => '',
				);
				if(isset($group_permissions->general_settings) && $group_permissions->general_settings==1) { $general_settings=TRUE; } else { $general_settings=FALSE; }
				$data['general_settings'] = array(
					'name'        => 'permission[general_settings]',
					'id'          => 'general_settings',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $general_settings,
					'style'       => '',
				);
				if(isset($group_permissions->login_to_frontend) && $group_permissions->login_to_frontend==1) { $login_to_frontend=TRUE; } else { $login_to_frontend=FALSE; }
				$data['login_to_frontend'] = array(
					'name'        => 'permission[login_to_frontend]',
					'id'          => 'login_to_frontend',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $login_to_frontend,
					'style'       => '',
				);
				
				$this->load->view(get_theme_directory().'/newusergroup', $data);
			} else  {
				$this->session->set_flashdata('errors', 'You dont have permission to create user groups.');
				redirect(site_url('/admin/usergroups'));
			}
		} else {
				$this->session->set_flashdata('errors', 'You dont have permission to access the admin part of the site.');
				redirect(site_url('auth/profile/'));
		}
		
		}		
	 }

	
	/**
	 * Edit User Group
	 *
	 */
	 function editusergroup($group_id)
	 {
		 
		 
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('/admin/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
		redirect('/auth/sendactivation/');
		} else { /* logged in */
		
		if($this->ci_auth->canDo('access_backend')) {
			if($this->ci_auth->canDo('edit_user_groups')) {
				
				$c_user_group = $this->ci_auth->usergroup_id();
				
				if($group_id==$c_user_group) {
					$this->session->set_flashdata('errors', 'You cant edit your group'); 
					redirect(site_url('admin/usergroups/'));
				}

				if(!isset($group_id) || $group_id=='') { 
					$this->session->set_flashdata('errors', 'User group not exists'); 
					redirect(site_url('admin/usergroups/newusergroup/'));
				} else if($group_id==1) {
					$this->session->set_flashdata('errors', 'Cant change the Super Administrator settings'); 
					redirect(site_url('admin/usergroups/'));
				} else if($group_id==2) {
					if(!$this->ci_auth->is_superadmin()) {
						$this->session->set_flashdata('errors', 'You don"t have permission to edit this user group'); 
						redirect(site_url('admin/usergroups/'));
					}     
				}				
				
				$data['seo_title'] = 'Edit User Group'; 
				
				$this->form_validation->set_rules('group_name', 'Group Name', 'trim|required|xss_clean');
				$this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
		
				if ($this->form_validation->run()) {
					$usergroupdata=array('name'=>$this->form_validation->set_value('group_name'),'status'=>$this->form_validation->set_value('status'),'permissions'=>json_encode($this->input->post('permission')));
					$group_id=$this->input->post('group_id');
					$updtUserGroup=$this->usergroups_model->updateusergroup($usergroupdata, $group_id); 
					if($updtUserGroup) {
						$this->session->set_flashdata('success', 'User group data updated successfully');
						redirect(site_url('admin/usergroups/editusergroup/'.$updtUserGroup));
					} else {
						$this->session->set_flashdata('errors', 'There is a problem while update the user group.');
					}
					
				} 
				$data['errors'] =  (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
				$data['success'] =  $this->session->flashdata('success');
				if($this->ci_auth->is_superadmin() || $this->ci_auth->is_admin()) { 
					$data['show_delete_user']=1;
					$data['show_delete_user_group']=1;
				} else {
					$data['show_delete_user']=0;
					$data['show_delete_user_group']=0;
				}
				$group_data=$this->usergroups_model->groupdata_by_id($group_id); 
				$data['group_status'] = $group_data->status;
				$data['group_id'] = $group_data->id;
				$group_permissions = json_decode($group_data->permissions);
				$data['group_name'] = array(
					'name'	=> 'group_name',
					'id'	=> 'group_name',
					'class' => 'required form-control',
					'placeholder'	=> 'Group Name',
					'value' =>  $group_data->name,
					'maxlength'	=> 80,
					'size'	=> 30,
				);
		
				/* User Permissions*/
				if(isset($group_permissions->access_backend) && $group_permissions->access_backend==1) { $access_backend=TRUE; } else { $access_backend=FALSE; }
				$data['access_backend'] = array(
					'name'        => 'permission[access_backend]',
					'id'          => 'access_backend',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $access_backend,
					'style'       => '',
				);
				if(isset($group_permissions->view_users) && $group_permissions->view_users==1) { $view_users=TRUE; } else { $view_users=FALSE; }
				$data['view_users'] = array(
					'name'        => 'permission[view_users]',
					'id'          => 'view_users',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $view_users,
					'style'       => '',
				);
				if(isset($group_permissions->impersonate_users) && $group_permissions->impersonate_users==1) { $impersonate_users=TRUE; } else { $impersonate_users=FALSE; }
				$data['impersonate_users'] = array(
					'name'        => 'permission[impersonate_users]',
					'id'          => 'impersonate_users',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $impersonate_users,
					'style'       => '',
				);
				if(isset($group_permissions->edit_users) && $group_permissions->edit_users==1) { $edit_users=TRUE; } else { $edit_users=FALSE; }
				$data['edit_users'] = array(
					'name'        => 'permission[edit_users]',
					'id'          => 'edit_users',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $edit_users,
					'style'       => '',
				);
				if(isset($group_permissions->delete_users) && $group_permissions->delete_users==1) { $delete_users=TRUE; } else { $delete_users=FALSE; }
				$data['delete_users'] = array(
					'name'        => 'permission[delete_users]',
					'id'          => 'delete_users',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $delete_users,
					'style'       => '',
				);
				if(isset($group_permissions->view_user_groups) && $group_permissions->view_user_groups==1) { $view_user_groups=TRUE; } else { $view_user_groups=FALSE; }
				$data['view_user_groups'] = array(
					'name'        => 'permission[view_user_groups]',
					'id'          => 'view_user_groups',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $view_user_groups,
					'style'       => '',
				);
				if(isset($group_permissions->edit_user_groups) && $group_permissions->edit_user_groups==1) { $edit_user_groups=TRUE; } else { $edit_user_groups=FALSE; }
				$data['edit_user_groups'] = array(
					'name'        => 'permission[edit_user_groups]',
					'id'          => 'edit_user_groups',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $edit_user_groups,
					'style'       => '',
				);
				if(isset($group_permissions->delete_user_groups) && $group_permissions->delete_user_groups==1) { $delete_user_groups=TRUE; } else { $delete_user_groups=FALSE; }
				$data['delete_user_groups'] = array(
					'name'        => 'permission[delete_user_groups]',
					'id'          => 'delete_user_groups',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $delete_user_groups,
					'style'       => '',
				);
				if(isset($group_permissions->general_settings) && $group_permissions->general_settings==1) { $general_settings=TRUE; } else { $general_settings=FALSE; }
				$data['general_settings'] = array(
					'name'        => 'permission[general_settings]',
					'id'          => 'general_settings',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $general_settings,
					'style'       => '',
				);
				if(isset($group_permissions->login_to_frontend) && $group_permissions->login_to_frontend==1) { $login_to_frontend=TRUE; } else { $login_to_frontend=FALSE; }
				$data['login_to_frontend'] = array(
					'name'        => 'permission[login_to_frontend]',
					'id'          => 'login_to_frontend',
					'class'		=> 'styled',
					'value'       => '1',
					'checked'     => $login_to_frontend,
					'style'       => '',
				);
		
				
				
				
				$this->load->view(get_theme_directory().'/editusergroup', $data);
			} else  {
				$this->session->set_flashdata('errors', 'You dont have permission to edit user groups.');
				redirect(site_url('/admin/usergroups'));
			}
		} else {
				$this->session->set_flashdata('errors', 'You dont have permission to access the admin part of the site.');
				redirect(site_url('auth/profile/'));
		}
		
		}		
				
	 }
	 
	/**
	 * Delete User Group
	 *
	 */
	 
	 function deleteusergroup($group_id) 
	 {
		 
		 
		if (!$this->ci_auth->is_logged_in()) {	
			redirect(site_url('/admin/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
		redirect('/auth/sendactivation/');
		} else { /* logged in */
		
		if($this->ci_auth->canDo('access_backend')) {
			if($this->ci_auth->canDo('delete_user_groups')) {
				
				if(!isset($group_id) || $group_id=='') { 
					$this->session->set_flashdata('errors', 'User group does not exists'); 
					redirect(site_url('admin/usergroups/'));
				} else if($group_id==1 || $group_id==2) {
					$this->session->set_flashdata('errors', 'Cant delete the Super Administrator / Administrator group'); 
					redirect(site_url('admin/usergroups/'));
				}
				
				$deleteUserGroup=$this->usergroups_model->deleteusergroup($group_id); 
				
				if($deleteUserGroup) {
					$this->session->set_flashdata('success', 'User group deleted successfully');
				} else {
					$this->session->set_flashdata('errors', 'There is a problem while delete the user group.');
				}
				redirect(site_url('admin/usergroups/'));
			} else  {
				$this->session->set_flashdata('errors', 'You dont have permission to delete user groups.');
				redirect(site_url('/admin/dashboard'));
			}
		} else {
				$this->session->set_flashdata('errors', 'You dont have permission to access the admin part of the site.');
				redirect(site_url('auth/profile/'));
		}
		
		}		
		
	 }

}

/* End of file Usergroups.php */
/* Location: ./modules/admin/controllers/Usergroups.php */