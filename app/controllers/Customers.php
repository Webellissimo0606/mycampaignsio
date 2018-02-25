<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if( ! class_exists('CIUIS_Controller') ){
	require_once 'CIUIS_Controller.php';
}

class Customers extends CIUIS_Controller {

	function index() {
		$data['title'] = lang('customers');
		$data['mst'] = $this->Report_Model->mst(); // Total Customer Count
		$data['tks'] = $this->Report_Model->tks(); // Total Customer Count by Company
		$data['tbm'] = $this->Report_Model->tbm(); // Total Customer Count by Individual
		$data['yms'] = $this->Report_Model->yms(); // Total New Customer Count
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb('Dashboard', 'panel');
		$this->breadcrumb->add_crumb('Customers', 'customers');
		$this->breadcrumb->add_crumb('Tüm Customers');
		$data['customers'] = $this->Customers_Model->get_all_customers();
		// Notification Data //
		$data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
		$data['newnotification'] = $this->Notifications_Model->newnotification();
		$data['readnotification'] = $this->Notifications_Model->readnotification();
		$data['notifications'] = $this->Notifications_Model->get_all_notifications();
		$data['events'] = $this->Events_Model->get_all_events();
		$data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
		$data['todaypayments'] = $this->Payments_Model->todaypayments();
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$data['customersjson'] = json_encode($this->Customers_Model->search_json_customer());
		$data['logs'] = $this->Logs_Model->panel_last_logs();
		$data['countries'] = $this->db->order_by("id", "asc")->get('countries')->result_array();
		$this->load->view(get_template_directory() . '/crm/customers/index', $data);
	}

	function corporate() {
		$data['title'] = lang('corporatecustomers');
		$data['mst'] = $this->Report_Model->mst(); // Total Customer Count
		$data['tks'] = $this->Report_Model->tks(); // Total Customer Count by Company
		$data['tbm'] = $this->Report_Model->tbm(); // Total Customer Count by Individual
		$data['yms'] = $this->Report_Model->yms(); // Total New Customer Count
		$data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
		$data['newnotification'] = $this->Notifications_Model->newnotification();
		$data['readnotification'] = $this->Notifications_Model->readnotification();
		$data['notifications'] = $this->Notifications_Model->get_all_notifications();
		$data['events'] = $this->Events_Model->get_all_events();
		$data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
		$data['todaypayments'] = $this->Payments_Model->todaypayments();
		$data['customers'] = $this->Customers_Model->get_corporate_customers();
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$data['countries'] = $this->db->order_by("id", "asc")->get('countries')->result_array();
		/*print_r($data['yms']);die();*/
		$this->load->view(get_template_directory() . '/crm/customers/index', $data);
	}

	function individual() {
		$data['title'] = lang('individualcustomers');
		$data['mst'] = $this->Report_Model->mst(); // Total Customer Count
		$data['tks'] = $this->Report_Model->tks(); // Total Customer Count by Company
		$data['tbm'] = $this->Report_Model->tbm(); // Total Customer Count by Individual
		$data['yms'] = $this->Report_Model->yms(); // Total New Customer Count
		$data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
		$data['newnotification'] = $this->Notifications_Model->newnotification();
		$data['readnotification'] = $this->Notifications_Model->readnotification();
		$data['notifications'] = $this->Notifications_Model->get_all_notifications();
		$data['events'] = $this->Events_Model->get_all_events();
		$data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
		$data['todaypayments'] = $this->Payments_Model->todaypayments();
		$data['customers'] = $this->Customers_Model->get_individual_customers();
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$data['countries'] = $this->db->order_by("id", "asc")->get('countries')->result_array();
		$this->load->view(get_template_directory() . '/crm/customers/index', $data);
	}

	function newcustomers() {
		$data['title'] = lang('newcustomers');
		$data['mst'] = $this->Report_Model->mst(); // Total Customer Count
		$data['tks'] = $this->Report_Model->tks(); // Total Customer Count by Company
		$data['tbm'] = $this->Report_Model->tbm(); // Total Customer Count by Individual
		$data['yms'] = $this->Report_Model->yms(); // Total New Customer Count
		$data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
		$data['newnotification'] = $this->Notifications_Model->newnotification();
		$data['readnotification'] = $this->Notifications_Model->readnotification();
		$data['notifications'] = $this->Notifications_Model->get_all_notifications();
		$data['events'] = $this->Events_Model->get_all_events();
		$data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
		$data['todaypayments'] = $this->Payments_Model->todaypayments();
		$data['customers'] = $this->Customers_Model->get_new_customers();
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$data['countries'] = $this->db->order_by("id", "asc")->get('countries')->result_array();
		$this->load->view(get_template_directory() . '/crm/customers/index', $data);
	}

	function mycustomers() {
		$data['title'] = lang('customers');
		$data['mst'] = $this->Report_Model->mst(); // Total Customer Count
		$data['tks'] = $this->Report_Model->tks(); // Total Customer Count by Company
		$data['tbm'] = $this->Report_Model->tbm(); // Total Customer Count by Individual
		$data['yms'] = $this->Report_Model->yms(); // Total New Customer Count
		$data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
		$data['newnotification'] = $this->Notifications_Model->newnotification();
		$data['readnotification'] = $this->Notifications_Model->readnotification();
		$data['notifications'] = $this->Notifications_Model->get_all_notifications();
		$data['events'] = $this->Events_Model->get_all_events();
		$data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
		$data['todaypayments'] = $this->Payments_Model->todaypayments();
		$data['customers'] = $this->Customers_Model->get_my_customers();
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();
		$data['countries'] = $this->db->order_by("id", "asc")->get('countries')->result_array();
		$this->load->view(get_template_directory() . '/crm/customers/index', $data);
	}

	/* ADD NEW CUSTOMER */

	function add() {
		$data['title'] = lang('addcustomer');
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb('Dashboard', 'panel');
		$this->breadcrumb->add_crumb('Customers', '../customers');
		$this->breadcrumb->add_crumb('Firma Add');
		$data['countries'] = $this->db->order_by("id", "asc")->get('countries')->result_array();
		if (isset($_POST) && count($_POST) > 0) {
			$params = array(
				'company_id' => $this->session->company_id,
				'datecreated' => date('Y-m-d H:i:s'),
				'type' => $this->input->post('type'),
				'companyname' => $this->input->post('companyname'),
				'namesurname' => $this->input->post('namesurname'),
				'socialsecuritynumber' => $this->input->post('socialsecuritynumber'),
				'companyexecutive' => $this->input->post('companyexecutive'),
				'companyaddress' => $this->input->post('companyaddress'),
				'companyphone' => $this->input->post('companyphone'),
				'companyemail' => $this->input->post('companyemail'),
				'companyfax' => $this->input->post('companyfax'),
				'companyweb' => $this->input->post('companyweb'),
				'taxoffice' => $this->input->post('taxoffice'),
				'taxnumber' => $this->input->post('taxnumber'),
				'countryid' => $this->input->post('countryid'),
				'customerstate' => $this->input->post('customerstate'),
				'customercity' => $this->input->post('customercity'),
				'customertown' => $this->input->post('customertown'),
				'zipcode' => $this->input->post('zipcode'),
				'staffid' => $this->session->userdata('logged_in_staff_id'),
				'status' => $this->input->post('status'),
			);
			$customers_id = $this->Customers_Model->add_customers($params);
			$this->session->set_flashdata('ntf1', '' . lang('customeradded') . '');
			redirect('customers/index');
		} else {
			$data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
			$data['newnotification'] = $this->Notifications_Model->newnotification();
			$data['readnotification'] = $this->Notifications_Model->readnotification();
			$data['notifications'] = $this->Notifications_Model->get_all_notifications();
			$data['settings'] = $this->Settings_Model->get_settings_ciuis();
			$this->load->view(get_template_directory() . '/crm/customers/add', $data);
		}
	}

	/* UPDATE CUSTOMER INFORMATIONS */

	function customer($id) {
		$data['title'] = lang('customer');
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb('Dashboard', 'panel');
		$this->breadcrumb->add_crumb('Customers', '../customers');
		$this->breadcrumb->add_crumb('Firma Detayı');
		$customers = $this->Customers_Model->get_customers($id);
		$data['logs'] = $this->Logs_Model->staffmember_log();
		$data['events'] = $this->Events_Model->get_all_events();
		$data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
		$data['todaypayments'] = $this->Payments_Model->todaypayments();
		$data['companylog'] = $this->db->get_where('logs', array('customerid' => $id))->result_array();
		$data['contacts'] = $this->db->get_where('contacts', array('customerid' => $id))->result_array();
		$data['invoices'] = $this->db->get_where('invoices', array('customerid' => $id))->result_array();
		$data['proposals'] = $this->db->get_where('proposals', array('relation' => $id, 'relation_type' => 'customer'))->result_array();
		$data['all_staff'] = $this->Staff_Model->get_all_staff();
		$data['reminders'] = $this->db->select('*,staff.staffname as reminderstaff,staff.staffavatar as staffpicture,reminders.id as id ')->join('staff', 'reminders.staff = staff.id', 'left')->get_where('reminders', array('relation' => $customers['id'], 'relation_type' => 'customer'))->result_array();
		$data['sales'] = $this->db->get_where('sales', array('customerid' => $id))->result_array();
		$data['payments'] = $this->db->get_where('payments', array('customerid' => $id, 'transactiontype' => 0))->result_array();
		$data['tickets'] = $this->db->get_where('tickets', array('customerid' => $id))->result_array();
		$data['countries'] = $this->db->order_by("id", "asc")->get('countries')->result_array();
		$data['notes'] = $this->db->select('*,staff.staffname as notestaff,notes.id as id ')->join('staff', 'notes.addedfrom = staff.id', 'left')->get_where('notes', array('relation' => $id, 'relation_type' => 'customer'))->result_array();
		if (isset($customers['id'])) {
			if (isset($_POST) && count($_POST) > 0) {
				$params = array(
					'type' => $this->input->post('type'),
					'companyname' => $this->input->post('companyname'),
					'namesurname' => $this->input->post('namesurname'),
					'socialsecuritynumber' => $this->input->post('socialsecuritynumber'),
					'companyexecutive' => $this->input->post('companyexecutive'),
					'companyaddress' => $this->input->post('companyaddress'),
					'companyphone' => $this->input->post('companyphone'),
					'companyemail' => $this->input->post('companyemail'),
					'companyfax' => $this->input->post('companyfax'),
					'companyweb' => $this->input->post('companyweb'),
					'taxoffice' => $this->input->post('taxoffice'),
					'taxnumber' => $this->input->post('taxnumber'),
					'countryid' => $this->input->post('countryid'),
					'customerstate' => $this->input->post('customerstate'),
					'customercity' => $this->input->post('customercity'),
					'customertown' => $this->input->post('customertown'),
					'zipcode' => $this->input->post('zipcode'),
					'staffid' => $this->session->userdata('logged_in_staff_id'),
					'risk' => $this->input->post('risk'),
					'status' => $this->input->post('status'),
				);
				$this->session->set_flashdata('ntf3', '<span class="text-black"><b>' . lang('customerupdated') . '</b></span>');
				$this->Customers_Model->update_customers($id, $params);
				redirect('customers/customer/' . $id . '');
			} else {
				$data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
				$data['ycr'] = $this->Report_Model->ycr();
				$data['newnotification'] = $this->Notifications_Model->newnotification();
				$data['readnotification'] = $this->Notifications_Model->readnotification();
				$data['notifications'] = $this->Notifications_Model->get_all_notifications();
				$data['customers'] = $this->Customers_Model->get_customers($id);
				$data['customer_annual_sales_chart'] = $this->Customers_Model->customer_annual_sales_chart($id);
				$data['settings'] = $this->Settings_Model->get_settings_ciuis();
				$this->load->view(get_template_directory() . '/crm/customers/customer', $data);
			}
		} else {
			show_error('Eror');
		}

	}

	function contactadd() {
		if ($this->Contacts_Model->isDuplicate($this->input->post('email'))) {
			$this->session->set_flashdata('ntf4', 'Contact email already exists');
			redirect('customers/customer/' . $this->input->post('customerid') . '');
		} else {
			if (isset($_POST) && count($_POST) > 0) {
				$params = array(
					'name' => $this->input->post('name'),
					'surname' => $this->input->post('surname'),
					'phone' => $this->input->post('phone'),
					'intercom' => $this->input->post('intercom'),
					'mobile' => $this->input->post('mobile'),
					'email' => $this->input->post('email'),
					'address' => $this->input->post('address'),
					'skype' => $this->input->post('skype'),
					'linkedin' => $this->input->post('linkedin'),
					'customerid' => $this->input->post('customerid'),
					'position' => $this->input->post('position'),
					'primary' => $this->input->post('primary'),
					'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
				);
				$contacts_id = $this->Contacts_Model->contactadd($params);
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
					'name' => $this->session->userdata('staffname'),
					'password' => $this->input->post('password'),
					'email' => $this->input->post('email'),
					'loginlink' => '' . base_url('customer/login') . '',
				);
				$body = $this->load->view(get_template_directory() . '/crm/email/accountinfo.php', $data, TRUE);
				$this->email->initialize($config);
				$this->email->set_newline("\r\n");
				$this->email->set_mailtype("html");
				$this->email->from($sender); // change it to yours
				$this->email->to($this->input->post('email')); // change it to yours
				$this->email->subject('Your Login Informations');
				$this->email->message($body);
				if ($this->email->send()) {
					$this->session->set_flashdata('ntf1', '' . $message = sprintf(lang('addedcontacts'), $this->input->post('name')) . '');
					redirect('customers/customer/' . $this->input->post('customerid') . '');
				} else {

					$this->session->set_flashdata('ntf3', '' . $message = sprintf(lang('addedcontactsbut'), $this->input->post('name')) . '');
					redirect('customers/customer/' . $this->input->post('customerid') . '');
				}

			}
		}
	}

	function updatecontact($id) {
		$contacts = $this->Contacts_Model->get_contacts($id);
		if (isset($contacts['id'])) {
			if (isset($_POST) && count($_POST) > 0) {
				$params = array(
					'name' => $this->input->post('name'),
					'surname' => $this->input->post('surname'),
					'phone' => $this->input->post('phone'),
					'intercom' => $this->input->post('intercom'),
					'mobile' => $this->input->post('mobile'),
					'email' => $this->input->post('email'),
					'address' => $this->input->post('address'),
					'skype' => $this->input->post('skype'),
					'linkedin' => $this->input->post('linkedin'),
					'position' => $this->input->post('position'),
				);

				$this->Contacts_Model->update_contacts($id, $params);
				$this->session->set_flashdata('ntf1', ' (' . $this->input->post('name') . ') ' . lang('contactupdated') . '');
				redirect('customers/customer/' . $contacts['customerid'] . '');
			} else {
				$data['contacts'] = $this->Contacts_Model->get_contacts($id);
			}
		} else {
			show_error('The contacts you are trying to edit does not exist.');
		}

	}

	function changecontactpassword($id) {
		$contacts = $this->Contacts_Model->get_contacts($id);
		if (isset($contacts['id'])) {
			if (isset($_POST) && count($_POST) > 0) {
				$params = array(
					'password' => password_hash($this->input->post('contactnewpassword'), PASSWORD_BCRYPT),
				);
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
					'name' => $this->session->userdata('staffname'),
					'password' => $this->input->post('contactnewpassword'),
					'email' => $contacts['email'],
					'loginlink' => '' . base_url('customer/login') . '',
				);
				$body = $this->load->view(get_template_directory() . '/crm/email/passwordchanged.php', $data, TRUE);
				$this->email->initialize($config);
				$this->email->set_newline("\r\n");
				$this->email->set_mailtype("html");
				$this->email->from($sender); // change it to yours
				$this->email->to($contacts['email']); // change it to yours
				$this->email->subject('Your Password Changed');
				$this->email->message($body);
				$this->email->send();
				/////////////
				//LOG
				$customer = $contacts['customerid'];
				$staffname = $this->session->staffname;
				$contactname = $contacts['name'];
				$contactsurname = $contacts['surname'];
				$loggedinuserid = $this->session->logged_in_staff_id;
				$this->db->insert('logs', array(
					'date' => date('Y-m-d H:i:s'),
					'detail' => ('' . $message = sprintf(lang('changedpassword'), $staffname, $contactname, $contactsurname) . ''),
					'staffid' => $loggedinuserid,
					'customerid' => $customer,
					'company_id' => $_SESSION['company_id'],
				));
				$this->Contacts_Model->update_contacts($id, $params);
				$this->session->set_flashdata('ntf1', ' ' . $contacts['name'] . ' ' . lang('passwordchanged') . '');
				redirect('customers/customer/' . $contacts['customerid'] . '');
			} else {
				$data['contacts'] = $this->Contacts_Model->get_contacts($id);
			}
		} else {
			show_error('The contacts you are trying to edit does not exist.');
		}

	}
	function addreminder() {
		if (isset($_POST) && count($_POST) > 0) {
			$params = array(
				'description' => $this->input->post('description'),
				'relation' => $this->input->post('relation'),
				'relation_type' => 'customer',
				'staff' => $this->input->post('staff'),
				'addedfrom' => $this->session->userdata('logged_in_staff_id'),
				'date' => $this->input->post('date'),
			);
			$notes = $this->Trivia_Model->add_reminder($params);
			$this->session->set_flashdata('ntf1', '' . lang('reminderadded') . '');
			redirect('customers/customer/' . $this->input->post('relation') . '');
		} else {
			redirect('leads/index');
		}
	}

	// DELETE CUSTOMER

	function remove($id) {
		$customers = $this->Customers_Model->get_customers($id);
		if (isset($customers['id'])) {
			$this->Customers_Model->delete_customers($id);
			redirect('customers/index');
		} else {
			show_error('Customer not deleted');
		}

	}

	function customers_json() {
		$customers = $this->Customers_Model->get_all_customers();
		header('Content-Type: application/json');
		echo json_encode($customers);
	}

	function customers_arama_json() {
		$veriler = $this->Customers_Model->search_json_customer();
		echo json_encode($veriler);

	}
}