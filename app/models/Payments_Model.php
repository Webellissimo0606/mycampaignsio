<?php
class Payments_Model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('campaigns-io/functions');
	}

	function addpayment($params) {
		$params['company_id'] = $_SESSION['company_id'];
		$this->db->insert('payments', $params);
		$amount = $this->input->post('amount');
		$invoicetotal = $this->input->post('invoicetotal');
		if ($amount == $invoicetotal) {
			$response = $this->db->where('id', $this->input->post('invoiceid'))->update('invoices', array('statusid' => 2, 'duedate' => ''));
		} else {
			$response = $this->db->where('id', $this->input->post('invoiceid'))->update('invoices', array('statusid' => 3));
		}
		return $this->db->insert_id();
	}

	function todaypayments() {
		return $this->db->get_where('payments', array('DATE(date)' => date('Y-m-d')))->result_array();
	}
}