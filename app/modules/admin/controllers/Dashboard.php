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

class Dashboard extends CI_Controller
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
				redirect(site_url('/admin/login'));
		} elseif ($this->ci_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/sendactivation/');
		} else { /* logged in */
		
			if($this->ci_auth->canDo('access_backend')) {
				$data['seo_pagetitle'] = 'Dashboard';
				$data['errors'] =  $this->session->flashdata('errors');
				$data['success'] =  $this->session->flashdata('success');
				$this->load->view(get_theme_directory().'/dashboard', $data);
			} else {
				$this->session->set_flashdata('errors', 'You dont have permission to access the admin part of the site.');
				redirect(site_url('auth/profile/'));
			}

		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		$this->ci_auth->logout();
		$this->_show_message($this->lang->line('auth_message_logged_out'));
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
		redirect('/admin/');
	}

}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */