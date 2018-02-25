<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );
class Invoiceitems_Model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('campaigns-io/functions');
	}
	
	function get_all_invoiceitems() {
		$this->db->select( "*" );
		$this->db->from( 'invoiceitems' );
		$query = $this->db->get();
		return $query->result();
		$num_data_returned = $query->num_rows;
		if ( $num_data_returned < 1 ) {
			echo "There is no data in the database";
			exit();
		}
	}
}