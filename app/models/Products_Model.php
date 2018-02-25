<?php

class Products_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('campaigns-io/functions');
	}

	function get_products( $id ) {
		return $this->db->get_where( 'products', array( 'id' => $id ) )->row_array();
	}

	function get_all_products() {
		return $this->db->where( 'products.company_id =' . $_SESSION['company_id'] )->get_where( 'products', array( '' ) )->result_array();
	}

	function getallproductsjson() {
		$this->db->select( 'id id,code code,productname label,sale_price sale_price,vat vat' );
		$this->db->from( 'products' );
		$this->db->where( 'products.company_id =' . $_SESSION['company_id'] );
		return $this->db->get()->result();
	}

	function add_products( $params ) {
		$this->db->insert( 'products', $params );
		$product = $this->db->insert_id();
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('addedanewproduct').' <a href="/products/product/' . $product . '"> '.lang('product').'' . $product . '</a>' ),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id']
		) );
		return $this->db->insert_id();
	}

	function update_products( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update('products', $params );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('updated').' <a href="/products/product/' . $id . '"> '.lang('product').'' . $id . '</a>' ),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id']
		) );
		
	}
	function delete_products( $id ) {
		$response = $this->db->delete( 'products', array( 'id' => $id ) );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('deleted').' '.lang('product').'-' . $id . '' ),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id']
		) );
	}
}