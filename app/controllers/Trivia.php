<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Trivia extends CIUIS_Controller {

	function index() {
		echo 'Trivia';
	}
	function addtodo() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'description' => $_POST['tododetail'],
				'staffid' => $this->session->userdata('logged_in_staff_id'),
				'date' => date( 'Y-m-d H:i:s' ),
			);
			$this->db->insert( 'todo', $params );
			$data['insert_id'] = $this->db->insert_id();;
			return json_encode($data);
		}
	}
	function donetodo() {
		$this->Trivia_Model->donetodo();
	}
	function removetodo() {
		$this->Trivia_Model->removetodo();
	}
	function addnote() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'relation_type' => $_POST['relation_type'],
				'relation' => $_POST['relation'],
				'description' => $_POST['description'],
				'addedfrom' => $this->session->userdata('logged_in_staff_id'),
				'dateadded' => date( 'Y-m-d H:i:s' ),
			);
			$this->db->insert( 'notes', $params );
			$data['insert_id'] = $this->db->insert_id();;
			echo json_encode($data);
		}
	}
	function removenote() {
		$this->Trivia_Model->removenote();
	}
	function removereminder() {
		$this->Trivia_Model->removereminder();
	}
	function markreadreminder($reminderid) {
		if ( isset( $_POST[ 'reminderid' ] ) ) {
			$reminderid = $_POST[ 'reminderid' ];
			$response = $this->db->where( 'id', $reminderid )->update( 'reminders', array( 'isnotified' => ( '1' ) ) );
		}
	}
}