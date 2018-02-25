<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Contacts extends CIUIS_Controller {
	function index() {
		$data[ 'contacts' ] = $this->Contacts_Model->get_all_contacts();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'contacts/index', $data );
	}

	function add() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'name' => $this->input->post( 'name' ),
				'surname' => $this->input->post( 'surname' ),
				'phone' => $this->input->post( 'phone' ),
				'intercom' => $this->input->post( 'intercom' ),
				'mobile' => $this->input->post( 'mobile' ),
				'email' => $this->input->post( 'email' ),
				'address' => $this->input->post( 'address' ),
				'skype' => $this->input->post( 'skype' ),
				'linkedin' => $this->input->post( 'linkedin' ),
				'customerid' => $this->input->post( 'customerid' ),
				'position' => $this->input->post( 'position' ),
			);
			$contacts_id = $this->Contacts_Model->add_contacts( $params );
			redirect( 'contacts/index' );
		} else {
			$this->load->model( 'Customers_model' );
			$data[ 'all_customers' ] = $this->Customers_model->get_all_customers();
			$this->load->view( 'contacts/add', $data );
		}
	}

	function edit( $id ) {
		$contacts = $this->Contacts_Model->get_contacts( $id );
		if ( isset( $contacts[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'name' => $this->input->post( 'name' ),
					'surname' => $this->input->post( 'surname' ),
					'phone' => $this->input->post( 'phone' ),
					'intercom' => $this->input->post( 'intercom' ),
					'mobile' => $this->input->post( 'mobile' ),
					'email' => $this->input->post( 'email' ),
					'address' => $this->input->post( 'address' ),
					'skype' => $this->input->post( 'skype' ),
					'linkedin' => $this->input->post( 'linkedin' ),
					'customerid' => $this->input->post( 'customerid' ),
					'position' => $this->input->post( 'position' ),
				);
				$this->Contacts_Model->update_contacts( $id, $params );
				redirect( 'contacts/index' );
			} else {
				$data[ 'contacts' ] = $this->Contacts_Model->get_contacts( $id );
				$this->load->model( 'Customers_model' );
				$data[ 'all_customers' ] = $this->Customers_model->get_all_customers();
				$this->load->view( 'contacts/edit', $data );
			}
		} else
			show_error( 'The contacts you are trying to edit does not exist.' );
	}

	function remove( $id ) {
		$contacts = $this->Contacts_Model->get_contacts( $id );
		if ( isset( $contacts[ 'id' ] ) ) {
			$this->Contacts_Model->delete_contacts( $id );
			$this->session->set_flashdata( 'ntf4', lang('contactdeleted') );
			redirect( 'customers/customer/' . $contacts[ 'customerid' ] . '' );
		} else
			show_error( 'The contacts you are trying to delete does not exist.' );
	}

}