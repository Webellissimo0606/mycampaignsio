<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if( ! class_exists('CIUIS_Controller') ){
	require_once 'CIUIS_Controller.php';
}

class Customer extends CIUISCUSTOMER_Controller {

	public $inactive;
	public $roles;

	function __construct() {
		parent::__construct();
		$this->load->model('Settings_Model');
		define('LANG', $this->Settings_Model->get_crm_lang());
		$this->lang->load(LANG, LANG);
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
		$this->load->model('Customer_Model');
		$this->load->model('Contacts_Model');
		$this->load->model('Settings_Model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->inactive = $this->config->item('inactive');
		$this->roles = $this->config->item('roles');
	}

	function index() {
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			$data['title'] = 'Ciuis™ CRM Customer Area';
			$data['ycr'] = $this->Report_Model->ycr();
			$data['customerdebt'] = $this->Customer_Model->customerdebt();
			$data['settings'] = $this->Settings_Model->get_settings_ciuis();
			$data['customer_annual_sales_chart'] = $this->Customer_Model->customer_annual_sales_chart();
			$data['totalticket'] = $this->db->from('tickets')->where('customerid = ' . $_SESSION['customerid'] . '')->get()->num_rows();
			$data['totalinvoices'] = $this->db->from('invoices')->where('customerid = ' . $_SESSION['customerid'] . '')->get()->num_rows();
			$data['totalproposals'] = $this->db->from('proposals')->where('relation = ' . $_SESSION['customerid'] . ' AND relation_type = "customer"')->get()->num_rows();
			$data['totalcontact'] = $this->db->from('contacts')->where('customerid = ' . $_SESSION['customerid'] . '')->get()->num_rows();
			$data['totalpayment'] = $this->db->from('payments')->where('customerid = ' . $_SESSION['customerid'] . '')->get()->num_rows();
			$this->load->view(get_template_directory() . '/crm/customer/inc/header', $data);
			$this->load->view(get_template_directory() . '/crm/customer/area', $data);

		} else {
			redirect('customer/login');
		}
	}

	function markread() {
		$this->Customer_Model->markread();
	}

	function invoices() {

		$data['title'] = 'Ciuis™ CRM Customer Invoices';
		$data['invoices'] = $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.companyname as customer,customers.namesurname as birey,customers.companyaddress as customeraddress,invoicestatus.name as statusname, invoices.id as id ')->join('customers', 'invoices.customerid = customers.id', 'left')->join('invoicestatus', 'invoices.statusid = invoicestatus.id', 'left')->join('staff', 'invoices.staffid = staff.id', 'left')->get_where('invoices', array('customerid' => $_SESSION['customerid']))->result_array();
		//Detaylar
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view(get_template_directory() . '/crm/customer/invoices/index', $data);

	}
	function proposals() {
		$data['title'] = 'Ciuis™ CRM Customer Invoices';
		$data['proposals'] = $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.companyname as customer,customers.companyemail as toemail,customers.namesurname as individual,customers.companyaddress as toaddress, proposals.id as id ')->join('customers', 'proposals.relation = customers.id', 'left')->join('staff', 'proposals.assigned = staff.id', 'left')->get_where('proposals', array('relation' => $_SESSION['customerid'], 'relation_type' => 'customer'))->result_array();
		//Detaylar
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view(get_template_directory() . '/crm/customer/proposals/index', $data);

	}
	function invoice($id) {
		$data['title'] = 'Ciuis™ CRM Customer Invoices';
		$data['invoices'] = $this->Invoices_Model->get_invoices($id);
		$data['invoiceitems'] = $this->db->select('*,products.productname as name,invoiceitems.id as id ')->join('products', 'invoiceitems.in[product_id] = products.id', 'left')->get_where('invoiceitems', array('invoiceid' => $id))->result_array();
		$data['payments'] = $this->db->select('*,accounts.name as accountname,payments.id as id ')->join('accounts', 'payments.accountid = accounts.id', 'left')->get_where('payments', array('invoiceid' => $id))->result_array();
		//Detaylar
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view(get_template_directory() . '/crm/customer/inc/header', $data);
		$this->load->view(get_template_directory() . '/crm/customer/invoices/invoice', $data);
		$this->load->view(get_template_directory() . '/crm/inc/footer_table');
	}

	function invoice_pdf($id) {
		$ind = $this->Invoices_Model->get_invoice_detail($id);
		$data['title'] = '' . lang('invoiceprefix') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT) . '';
		$this->load->library('Pdf');
		$obj_pdf = new TCPDF('I', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
		$data['invoices'] = $this->Invoices_Model->get_invoice_detail($id);
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$data['invoiceitems'] = $this->db->select('*,products.productname as urun,invoiceitems.id as id ')->join('products', 'invoiceitems.in[product_id] = products.id', 'left')->get_where('invoiceitems', array('invoiceid' => $id))->result_array();
		$this->load->view(get_template_directory() . '/crm/invoices/invoice_pdf', $data);
	}

	function tickets() {
		$data['title'] = 'Ciuis™ CRM Customer Invoices';
		$data['ttc'] = $this->Customer_Model->ttc();
		$data['otc'] = $this->Customer_Model->otc();
		$data['ipc'] = $this->Customer_Model->ipc();
		$data['atc'] = $this->Customer_Model->atc();
		$data['ctc'] = $this->Customer_Model->ctc();
		$data['ysy'] = ($data['ttc'] > 0 ? number_format(($data['otc'] * 100) / $data['ttc']) : 0);
		$data['bsy'] = ($data['ttc'] > 0 ? number_format(($data['ipc'] * 100) / $data['ttc']) : 0);
		$data['twy'] = ($data['ttc'] > 0 ? number_format(($data['atc'] * 100) / $data['ttc']) : 0);
		$data['iey'] = ($data['ttc'] > 0 ? number_format(($data['ctc'] * 100) / $data['ttc']) : 0);
		$data['tickets'] = $this->db->select('*,customers.type as type,customers.companyname as companyname,customers.namesurname as namesurname,departments.name as department,staff.staffname as staffmembername,contacts.name as contactname,contacts.surname as contactsurname,tickets.staffid as stid, tickets.id as id ')->join('contacts', 'tickets.contactid = contacts.id', 'left')->join('customers', 'contacts.customerid = customers.id', 'left')->join('departments', 'tickets.department = departments.id', 'left')->join('staff', 'tickets.staffid = staff.id', 'left')->get_where('tickets', array('contactid' => $_SESSION['contact_id']))->result_array();
		$data['departments'] = $this->db->get_where('departments', array(''))->result_array();
		//Detaylar
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view(get_template_directory() . '/crm/customer/inc/header', $data);
		$this->load->view(get_template_directory() . '/crm/customer/tickets/index', $data);

	}

	function addticket() {
		if (isset($_POST) && count($_POST) > 0) {
			$config['upload_path'] = './uploads/ticket_attachments/';
			$config['allowed_types'] = 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt';
			$this->load->library('upload', $config);
			$this->upload->do_upload('attachment');
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$params = array(
				'contactid' => $_SESSION['contact_id'],
				'customerid' => $_SESSION['customerid'],
				'email' => $_SESSION['email'],
				'department' => $this->input->post('department'),
				'priority' => $this->input->post('priority'),
				'statusid' => 1,
				'subject' => $this->input->post('subject'),
				'message' => $this->input->post('message'),
				'attachment' => $image_data['file_name'],
				'date' => date(" Y.m.d H:i:s "),
			);
			$this->session->set_flashdata('ntf1', 'Ticket added');
			$tickets_id = $this->Customer_Model->add_tickets($params);
			redirect('customer/tickets');
		}
	}

	function ticket($id) {
		$data['title'] = 'Ciuis™ CRM Customer Ticket ' . $id . '';
		$data['ticketstatustitle'] = 'All Tickets';
		$data['ttc'] = $this->Customer_Model->ttc();
		$data['otc'] = $this->Customer_Model->otc();
		$data['ipc'] = $this->Customer_Model->ipc();
		$data['atc'] = $this->Customer_Model->atc();
		$data['ctc'] = $this->Customer_Model->ctc();
		$data['ysy'] = ($data['ttc'] > 0 ? number_format(($data['otc'] * 100) / $data['ttc']) : 0);
		$data['bsy'] = ($data['ttc'] > 0 ? number_format(($data['ipc'] * 100) / $data['ttc']) : 0);
		$data['twy'] = ($data['ttc'] > 0 ? number_format(($data['atc'] * 100) / $data['ttc']) : 0);
		$data['iey'] = ($data['ttc'] > 0 ? number_format(($data['ctc'] * 100) / $data['ttc']) : 0);
		$data['ticket'] = $this->Tickets_Model->get_tickets($id);
		$data['dtickets'] = $this->db->select('*,customers.type as type,customers.companyname as companyname,customers.namesurname as namesurname,departments.name as department,staff.staffname as staffmembername,contacts.name as contactname,contacts.surname as contactsurname,tickets.staffid as stid, tickets.id as id ')->join('contacts', 'tickets.contactid = contacts.id', 'left')->join('customers', 'contacts.customerid = customers.id', 'left')->join('departments', 'tickets.department = departments.id', 'left')->join('staff', 'tickets.staffid = staff.id', 'left')->get_where('tickets', array('contactid' => $_SESSION['contact_id']))->result_array();
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view(get_template_directory() . '/crm/customer/inc/header', $data);
		$this->load->view(get_template_directory() . '/crm/customer/tickets/ticket', $data);
	}

	function addreply() {
		if (isset($_POST) && count($_POST) > 0) {
			$config['upload_path'] = './uploads/ticket_attachments/';
			$config['allowed_types'] = 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt';
			$this->load->library('upload', $config);
			$this->upload->do_upload('attachment');
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$params = array(
				'ticketid' => $this->input->post('ticketid'),
				'staffid' => $this->input->post('staffid'),
				'contactid' => $_SESSION['contact_id'],
				'date' => date(" Y.m.d H:i:s "),
				'name' => $_SESSION['name'],
				'message' => $this->input->post('message'),
				'attachment' => $image_data['file_name'],
			);
			// SEND EMAIL SETTINGS
			$staffid = $this->input->post('staffid');
			$staffinfo = $this->Staff_Model->getUserInfo($this->input->post('staffid'));
			$setconfig = $this->Settings_Model->get_settings_ciuis();
			$this->load->library('email');
			$config = array();
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = $setconfig['smtphost'];
			$config['smtp_user'] = $setconfig['smtpusername'];
			$config['smtp_pass'] = $setconfig['smtppassoword'];
			$config['smtp_port'] = $setconfig['smtpport'];
			$sender = $setconfig['sendermail'];
			$data = array(
				'name' => $_SESSION['name'],
				'ticketlink' => '' . base_url('tickets/ticket/' . $this->input->post('ticketid') . '') . '',
			);
			$body = $this->load->view(get_template_directory() . '/crm/email/ticket.php', $data, TRUE);
			$this->email->initialize($config);
			$this->email->set_newline("\r\n");
			$this->email->set_mailtype("html");
			$this->email->from($sender); // change it to yours
			$this->email->to($staffinfo->email); // change it to yours
			$this->email->subject('Customer Replied Ticket');
			$this->email->message($body);
			$this->email->send();
			/////////////
			$replyid = $this->Tickets_Model->add_reply_contact($params);
			redirect('customer/ticket/' . $this->input->post('ticketid') . '');
		}
	}

	function login() {
		redirect('auth/login');
		$data = new stdClass();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == false) {
			$this->load->view(get_template_directory() . '/crm/customer/login');
		} else {
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			if ($this->Customer_Model->resolve_user_login($email, $password)) {
				$contact_id = $this->Customer_Model->get_contact_id_from_email($email);
				$user = $this->Customer_Model->get_user($contact_id);
				$_SESSION['contact_id'] = (int) $user->id;
				$_SESSION['customerid'] = (int) $user->customerid;
				$_SESSION['name'] = (string) $user->name;
				$_SESSION['surname'] = (string) $user->surname;
				$_SESSION['email'] = (string) $user->email;
				$_SESSION['logged_in'] = (bool) true;
				redirect('customer/index');
			} else {
				$data->error = 'Wrong email or password.';
				$this->load->view(get_template_directory() . '/crm/customer/login', $data);

			}
		}
	}

	function logout() {
		$data = new stdClass();
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			foreach ($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			}
			redirect('customer');
		} else {
			redirect('/');
		}
	}
	public

	function forgot() {

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view(get_template_directory() . '/crm/customer/forgot');
		} else {
			$email = $this->input->post('email');
			$clean = $this->security->xss_clean($email);
			$userInfo = $this->Contacts_Model->getUserInfoByEmail($clean);

			if (!$userInfo) {
				$this->session->set_flashdata('ntf4', lang('customercanffindmail'));
				redirect(site_url() . 'customer/login');
			}

			if ($userInfo->inactive != $this->inactive[1]) {
				//if inactive is not approved
				$this->session->set_flashdata('ntf4', lang('customerinactiveaccount'));
				redirect(site_url() . 'customer/login');
			}

			//build token

			$token = $this->Contacts_Model->insertToken($userInfo->id);
			$qstring = $this->base64url_encode($token);
			$url = site_url() . 'customer/reset_password/token/' . $qstring;
			$link = '<a href="' . $url . '">' . $url . '</a>';
			// SEND EMAIL SETTINGS
			$setconfig = $this->Settings_Model->get_settings_ciuis();
			$this->load->library('email');
			$config = array();
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = $setconfig['smtphost'];
			$config['smtp_user'] = $setconfig['smtpusername'];
			$config['smtp_pass'] = $setconfig['smtppassoword'];
			$config['smtp_port'] = $setconfig['smtpport'];
			$sender = $setconfig['sendermail'];
			$data = array(
				'name' => $userInfo->name,
				'email' => $userInfo->email,
				'link' => $url,
			);
			$body = $this->load->view(get_template_directory() . '/crm/email/reset_password.php', $data, TRUE);
			$this->email->initialize($config);
			$this->email->set_newline("\r\n");
			$this->email->set_mailtype("html");
			$this->email->from($sender); // change it to yours
			$this->email->to($userInfo->email); // change it to yours
			$this->email->subject('Reset Your Password');
			$this->email->message($body);
			$this->email->send();
			$this->session->set_flashdata('ntf1', '<b>' . lang('customerpasswordsend') . '</b>');
			redirect('customer/login');

		}

	}

	public

	function reset_password() {
		$token = $this->base64url_decode($this->uri->segment(4));
		$cleanToken = $this->security->xss_clean($token);

		$user_info = $this->Contacts_Model->isTokenValid($cleanToken); //either false or array();

		if (!$user_info) {
			$this->session->set_flashdata('ntf1', lang('tokenexpired'));
			redirect(site_url() . 'customer/login');
		}
		$data = array(
			'firstName' => $user_info->name,
			'email' => $user_info->email,
			//                'contact_id'=>$user_info->id,
			'token' => $this->base64url_encode($token),
		);

		$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view(get_template_directory() . '/crm/customer/reset_password', $data);
		} else {

			$post = $this->input->post(NULL, TRUE);
			$cleanPost = $this->security->xss_clean($post);
			$hashed = password_hash($cleanPost['password'], PASSWORD_BCRYPT);
			$cleanPost['password'] = $hashed;
			$cleanPost['contact_id'] = $user_info->id;
			unset($cleanPost['passconf']);
			if (!$this->Contacts_Model->updatePassword($cleanPost)) {
				$this->session->set_flashdata('ntf1', lang('problemupdatepassword'));
			} else {
				$this->session->set_flashdata('ntf1', lang('passwordupdated'));
			}
			redirect(site_url() . 'customer/login');
		}
	}

	public

	function base64url_encode($data) {
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	public

	function base64url_decode($data) {
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
}