<?php

class Logs_Model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('campaigns-io/functions');
	}

	function get_logs( $date ) {

		return $this->db->get_where( 'logs', array( 'date' => $date ) )->row_array();
	}
	function getlog_json() {
		$this->db->select( 'detail detail,date date' );
		$this->db->from( 'logs' );
		$this->db->where( 'logs.company_id = '.$_SESSION['company_id'] );
		return $this->db->get()->result();
	}


	function get_all_logs() {

		$this->db->select( '*,,staff.staffname as staffmembername, staff.staffavatar as staffimage, logs.date as date ' );
		$this->db->join( 'staff', 'logs.staffid = staff.id', 'left' );
		$this->db->order_by( "date", "desc" );
		$this->db->where( 'logs.company_id = '.$_SESSION['company_id'] );
		return $this->db->get_where( 'logs', array( '' ) )->result_array();
	}

	function panel_last_logs() {

		$this->db->select( '*,,staff.staffname as staffmembername, staff.staffavatar as staffimage, logs.date as date ' );
		$this->db->join( 'staff', 'logs.staffid = staff.id', 'left' );
		$this->db->order_by( "date", "desc" );
		$this->db->where( 'logs.company_id = '.$_SESSION['company_id'] );
		return $this->db->get_where( 'logs', array( '' ) )->result_array();
	}

	function staffmember_log() {

		$this->db->select( '*,,staff.staffname as staffmembername, staff.staffavatar as staffimage, logs.date as date ' );
		$this->db->join( 'staff', 'logs.staffid = staff.id', 'left' );
		$this->db->limit( 5 );
		$this->db->order_by( "date", "desc" );
		$this->db->where( 'logs.company_id = '.$_SESSION['company_id'] );
		return $this->db->get_where( 'logs', array( 'staffid' => $this->session->userdata( 'logged_in_staff_id' ) ) )->result_array();
	}

	function delete_logs( $date ) {
		$response = $this->db->delete( 'logs', array( 'date' => $date ) );
		if ( $response ) {
			return "Sipariş silindi";
		} else {
			return "Siparişi silme hatası";
		}
	}
	
}