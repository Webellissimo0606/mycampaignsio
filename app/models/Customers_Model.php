<?php
class Customers_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	// Get Customer
	function get_customers($id) {
		$this->db->select('*,countries.shortname as country, customers.id as id ');
		$this->db->join('countries', 'customers.countryid = countries.id', 'left');
		$this->db->where('customers.company_id = ' . $_SESSION['company_id']);
		return $this->db->get_where('customers', array('customers.id' => $id))->row_array();
	}
	function get_customer_by_email($email) {
		$this->db->select('*,countries.shortname as country, customers.id as id ');
		$this->db->join('countries', 'customers.countryid = countries.id', 'left');
		$this->db->where('customers.company_id = ' . $_SESSION['company_id']);
		return $this->db->get_where('customers', array('customers.companyemail' => $email))->row_array();
	}

	// Get All Customers
	function get_all_customers() {
		$this->db->select('*,countries.shortname as country,countries.isocode as isocode, customers.id as id ');
		$this->db->join('countries', 'customers.countryid = countries.id', 'left');
		$this->db->where('customers.company_id = ' . $_SESSION['company_id']);
		return $this->db->get_where('customers', array(''))->result_array();
	}

	function get_corporate_customers() {
		$this->db->select('*,countries.shortname as country,countries.isocode as isocode, customers.id as id ');
		$this->db->join('countries', 'customers.countryid = countries.id', 'left');
		$this->db->where('customers.company_id = ' . $_SESSION['company_id']);
		return $this->db->get_where('customers', array('type' => 0))->result_array();
	}

	function get_individual_customers() {
		$this->db->select('*,countries.shortname as country,countries.isocode as isocode, customers.id as id ');
		$this->db->join('countries', 'customers.countryid = countries.id', 'left');
		$this->db->where('customers.company_id = ' . $_SESSION['company_id']);
		return $this->db->get_where('customers', array('type' => 1))->result_array();
	}
	function get_my_customers() {
		$this->db->select('*,countries.shortname as country,countries.isocode as isocode, customers.id as id ');
		$this->db->join('countries', 'customers.countryid = countries.id', 'left');
		$this->db->where('customers.company_id = ' . $_SESSION['company_id']);
		return $this->db->get_where('customers', array('staffid' => $this->session->userdata('logged_in_staff_id')))->result_array();
	}

	function get_new_customers() {
		$this->db->select('*,countries.shortname as country,countries.isocode as isocode, customers.id as id ');
		$this->db->join('countries', 'customers.countryid = countries.id', 'left');
		// $this->db->from( 'customers' )/*->where( 'MONTH(datecreated) = MONTH(CURRENT_DATE)' )*/->where('WEEK(datecreated) = WEEK(CURRENT_DATE)');
		$this->db->from('customers')->where('WEEK(datecreated) = WEEK(CURRENT_DATE)');
		$this->db->where('customers.company_id = ' . $_SESSION['company_id']);
		$query = $this->db->get();
		$newcustomer = $query->result_array();
		return $newcustomer;
	}
	// ADD NEW CUSTOMER
	function add_customers($params) {
		$this->db->insert('customers', $params);
		//LOG
		$customer = $this->db->insert_id();
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert('logs', array(
			'date' => date('Y-m-d H:i:s'),
			'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('addedacustomer') . ' <a href="/customers/customer/' . $customer . '">' . lang('customer') . '-' . $customer . '</a>'),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id'],

		));
		return $this->db->insert_id();
	}

	// UPDATE CUSTOMER
	function update_customers($id, $params) {
		$this->db->where('id', $id);
		$response = $this->db->update('customers', $params);
	}

	// DELETE CUSTOMER
	function delete_customers($id) {
		$response = $this->db->delete('invoices', array('customerid' => $id));
		$response = $this->db->delete('contacts', array('customerid' => $id));
		$response = $this->db->delete('payments', array('customerid' => $id));
		$response = $this->db->delete('expenses', array('customerid' => $id));
		$response = $this->db->delete('expenses', array('customerid' => $id));
		$response = $this->db->delete('logs', array('customerid' => $id));
		$response = $this->db->delete('notifications', array('customerid' => $id));
		$response = $this->db->delete('projects', array('customerid' => $id));
		$response = $this->db->delete('tickets', array('customerid' => $id));
		$response = $this->db->delete('sales', array('customerid' => $id));
		$response = $this->db->delete('customers', array('id' => $id));
		// LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert('logs', array(
			'date' => date('Y-m-d H:i:s'),
			'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('deleted') . ' ' . lang('customer') . '-' . $id . ''),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id'],

		));
	}
	// CUSTOMER GRAPH
	function customer_annual_sales_chart($id) {
		$totalsales = array();
		$i = 0;
		for ($MO = 1; $MO <= 12; $MO++) {
			$this->db->select('total');
			$this->db->from('sales');
			$this->db->where('MONTH(sales.date)', $MO);
			$this->db->where('customerid = ' . $id . '');
			$balances = $this->db->get()->result_array();
			if (!isset($totalsales[$MO])) {
				$totalsales[$i] = array();
			}
			if (count($balances) > 0) {
				foreach ($balances as $balance) {
					$totalsales[$i][] = $balance['total'];
				}
			} else {
				$totalsales[$i][] = 0;
			}
			$totalsales[$i] = array_sum($totalsales[$i]);
			$i++;
		}
		return json_encode($totalsales);
	}
	public

	function search_json_customer() {
		$this->db->select('id customer,type customertype,companyname company,namesurname individual,');
		$this->db->from('customers');
		return $this->db->get()->result();
	}
}