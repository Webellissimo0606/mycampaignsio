<?php
class Accounts_Model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('campaigns-io/functions');
	}

	function get_accounts( $id ) {
		return $this->db->get_where( 'accounts', array( 'id' => $id ) )->row_array();
	}

	function get_all_accounts() {
		return $this->db->where( 'company_id', $_SESSION['company_id'] )->get_where( 'accounts', array( '' ) )->result_array();
	}

	function account_add( $params ) {
		$this->db->insert( 'accounts', $params );
		$account = $this->db->insert_id();
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('addedanewaccount').' <a href="/accounts/account/' . $account . '"> '.lang('account').'-' . $account . '</a>' ),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id']
		) );
		return $this->db->insert_id();
		
	}

	function updateaccount( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'accounts', $params );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('updated').' <a href="/accounts/account/' . $id . '"> '.lang('account').'-' . $id . '</a>' ),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id']
		) );
	}

	function delete_account( $id ) {
		$response = $this->db->delete( 'accounts', array( 'id' => $id ) );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('deleted').' '.lang('account').'-' . $id . '' ),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id'],
		) );
	}
}