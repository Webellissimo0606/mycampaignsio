<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Calendar extends CIUIS_Controller {
	function index() {
		$data[ 'title' ] = 'Calendar';
		$data[ 'logs' ] = $this->Logs_Model->get_all_logs();
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( get_template_directory() . '/crm/calendar/index', $data );
	}

	function get_Events() {
		$data = $this->Events_Model->get_events_json();
		echo json_encode( $data );

	}
	function addevent() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'company_id' =>$_SESSION['company_id'],
				'title' => $_POST['title'],
				'public' => $_POST['publievent'],
				'detail' => $_POST['detail'],
				'start' => $_POST['start'],
				'end' => $_POST['end'],
				'staffid' => $this->session->userdata('logged_in_staff_id'),
				'staffname' => $this->session->userdata('staffname'),
			);
			$todos = $this->Events_Model->add_event( $params );
		}
	}
	function remove( $id ) {
		$events = $this->Events_Model->remove( $id );
		if ( isset( $events[ 'id' ] ) ) {
			$this->Events_Model->remove( $id );
			
		} else
			$this->session->set_flashdata( 'ntf4', lang('eventdeleted') );
			redirect( 'calendar/index' );
	}
}