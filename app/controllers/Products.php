<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
if( ! class_exists('CIUIS_Controller') ){
	require_once 'CIUIS_Controller.php';
}
class Products extends CIUIS_Controller {

	public

	function index() {
		$data[ 'title' ] = 'Products';
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb( 'Dashboard', 'panel' );
		$this->breadcrumb->add_crumb( 'Products', 'products' );
		$this->breadcrumb->add_crumb( 'Tüm Products' );
		$data[ 'title' ] = 'Products';
		$data[ 'products' ] = $this->Products_Model->get_all_products();
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( get_template_directory() . '/crm/products/index', $data );
	}
	public

	function getallproductsjson() {
		$data = $this->Products_Model->getallproductsjson();
		echo json_encode( $data );

	}

	function add() {
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb( 'Dashboard', 'panel' );
		$this->breadcrumb->add_crumb( 'Products', '../products' );
		$this->breadcrumb->add_crumb( 'Ürün Add' );
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'company_id' => $_SESSION['company_id'],
				'code' => $this->input->post( 'code' ),
				'productname' => $this->input->post( 'productname' ),
				'description' => $this->input->post( 'description' ),
				'productimage' => $this->input->post( 'productimage' ),
				'purchase_price' => $this->input->post( 'purchase_price' ),
				'sale_price' => $this->input->post( 'sale_price' ),
				'stock' => $this->input->post( 'stock' ),
				'vat' => $this->input->post( 'vat' ),
				'status' => $this->input->post( 'status' ),
			);
			$products_id = $this->Products_Model->add_products( $params );
			redirect( 'products/index' );
		} else {
			$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
			$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
			$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
			$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
			$this->load->view( get_template_directory() . '/crm/products/add', $data );
		}
	}
	function edit( $id ) {
		// check if the expenses exists before trying to edit it
		$data[ 'products' ] = $this->Products_Model->get_products( $id );

		if ( isset( $data[ 'products' ][ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'code' => $this->input->post( 'code' ),
					'productname' => $this->input->post( 'productname' ),
					'description' => $this->input->post( 'description' ),
					'productimage' => $this->input->post( 'productimage' ),
					'purchase_price' => $this->input->post( 'purchase_price' ),
					'sale_price' => $this->input->post( 'sale_price' ),
					'stock' => $this->input->post( 'stock' ),
					'vat' => $this->input->post( 'vat' ),
					'status' => $this->input->post( 'status' ),
				);
				$this->session->set_flashdata( 'ntf1', '<span><b>' . lang( 'productupdated' ) . '</b></span>' );
				$this->Products_Model->update_products($id, $params );
				redirect( 'products/product/'.$id.'' );
			} else {
				$data[ 'products' ] = $this->Products_Model->get_products( $id );
				$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
				$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
				$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
				$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
				$this->load->view( get_template_directory() . '/crm/products/edit', $data );
			}
		} else
			show_error( 'The expenses you are trying to edit does not exist.' );
	}

	function product( $id ) {
		$data[ 'title' ] = 'Product';
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb( 'Dashboard', 'panel' );
		$this->breadcrumb->add_crumb( 'Products', '../products' );
		$this->breadcrumb->add_crumb( 'Ürün Detayı' );
		$product = $this->Products_Model->get_products( $id );
		$data[ 'product' ] = $this->Products_Model->get_products( $id );
		$data[ 'tsp' ] = $this->db->from( 'invoiceitems' )->where( 'in[product_id] = ' . $id . '' )->get()->num_rows();
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$this->load->view( get_template_directory() . '/crm/products/product', $data );
	}

	function remove( $id ) {
		$products = $this->Products_Model->get_products( $id );
		if ( isset( $products[ 'id' ] ) ) {
			$this->Products_Model->delete_products( $id );
			redirect( 'products/index' );
		} else
			show_error( 'The products you are trying to delete does not exist.' );
	}
}