<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
if( ! class_exists('CIUIS_Controller') ){
	require_once 'CIUIS_Controller.php';
}
class Tickets extends CIUIS_Controller {
	public

	function index() {
		$data[ 'title' ] = lang( 'tickets' );
		$data[ 'ticketstatustitle' ] = lang('alltickets');
		$data[ 'tickets' ] = $this->Tickets_Model->get_all_tickets();
		$data[ 'ttc' ] = $this->Report_Model->ttc();
		$data[ 'otc' ] = $this->Report_Model->otc();
		$data[ 'ipc' ] = $this->Report_Model->ipc();
		$data[ 'atc' ] = $this->Report_Model->atc();
		$data[ 'ctc' ] = $this->Report_Model->ctc();
		$data[ 'ysy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'otc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'bsy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ipc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'twy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'atc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'iey' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ctc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( get_template_directory() . '/crm/tickets/index', $data );
	}

	function open() {
		$data[ 'title' ] = lang( 'open' );
		$data[ 'ticketstatustitle' ] = lang('opentickets');
		$data[ 'tickets' ] = $this->Tickets_Model->get_all_open_tickets();
		$data[ 'ttc' ] = $this->Report_Model->ttc();
		$data[ 'otc' ] = $this->Report_Model->otc();
		$data[ 'ipc' ] = $this->Report_Model->ipc();
		$data[ 'atc' ] = $this->Report_Model->atc();
		$data[ 'ctc' ] = $this->Report_Model->ctc();
		$data[ 'ysy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'otc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'bsy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ipc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'twy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'atc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'iey' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ctc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( get_template_directory() . '/crm/tickets/index', $data );
	}

	function inprogress() {
		$data[ 'title' ] = lang('inprogresstickets');
		$data[ 'ticketstatustitle' ] = lang('inprogresstickets');
		$data[ 'tickets' ] = $this->Tickets_Model->get_all_inprogress_tickets();
		$data[ 'ttc' ] = $this->Report_Model->ttc();
		$data[ 'otc' ] = $this->Report_Model->otc();
		$data[ 'ipc' ] = $this->Report_Model->ipc();
		$data[ 'atc' ] = $this->Report_Model->atc();
		$data[ 'ctc' ] = $this->Report_Model->ctc();
		$data[ 'ysy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'otc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'bsy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ipc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'twy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'atc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'iey' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ctc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( get_template_directory() . '/crm/tickets/index', $data );
	}

	function answered() {
		$data[ 'title' ] = lang('answeredtickets');
		$data[ 'ticketstatustitle' ] = lang('answeredtickets');
		$data[ 'tickets' ] = $this->Tickets_Model->get_all_answered_tickets();
		$data[ 'ttc' ] = $this->Report_Model->ttc();
		$data[ 'otc' ] = $this->Report_Model->otc();
		$data[ 'ipc' ] = $this->Report_Model->ipc();
		$data[ 'atc' ] = $this->Report_Model->atc();
		$data[ 'ctc' ] = $this->Report_Model->ctc();
		$data[ 'ysy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'otc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'bsy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ipc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'twy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'atc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'iey' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ctc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( get_template_directory() . '/crm/tickets/index', $data );
	}

	function closed() {
		$data[ 'title' ] = lang('closedtickets');
		$data[ 'ticketstatustitle' ] = lang('closedtickets');
		$data[ 'tickets' ] = $this->Tickets_Model->get_all_closed_tickets();
		$data[ 'ttc' ] = $this->Report_Model->ttc();
		$data[ 'otc' ] = $this->Report_Model->otc();
		$data[ 'ipc' ] = $this->Report_Model->ipc();
		$data[ 'atc' ] = $this->Report_Model->atc();
		$data[ 'ctc' ] = $this->Report_Model->ctc();
		$data[ 'ysy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'otc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'bsy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ipc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'twy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'atc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'iey' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ctc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( get_template_directory() . '/crm/tickets/index', $data );
	}


	public

	function add() {
		$data[ 'title' ] = lang('addticket');
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb( 'Dashboard', 'panel' );
		$this->breadcrumb->add_crumb( 'Tickets', '../tickets' );
		$this->breadcrumb->add_crumb( 'Add Ticket' );
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$config[ 'upload_path' ] = './uploads/ticket_attachments/';
			$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt';
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'attachment' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$params = array(
				'company_id' => $this->input->$_SESSION['company_id'],
				'contactid' => $this->input->post( 'contactid' ),
				'customerid' => $this->input->post( 'customerid' ),
				'department' => $this->input->post( 'department' ),
				'priority' => $this->input->post( 'priority' ),
				'statusid' => 1,
				'subject' => $this->input->post( 'subject' ),
				'message' => $this->input->post( 'message' ),
				'attachment' => $image_data[ 'file_name' ],
				'date' => date( " Y.m.d H:i:s " ),
			);
			$this->session->set_flashdata( 'ntf1', lang('ticketadded') );
			$tickets_id = $this->Tickets_Model->add_tickets( $params );
			redirect( 'tickets/index' );
		} else {
			$this->load->model( 'Products_Model' );
			$data[ 'all_products' ] = $this->Products_Model->get_all_products();
			$this->load->model( 'Customers_Model' );
			$data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
			$this->load->model( 'Staff_Model' );
			$data[ 'all_staff' ] = $this->Staff_Model->get_all_staff();
			$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
			$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
			$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
			$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$this->load->view( get_template_directory() . '/crm/tickets/add', $data );
		}
	}

	function ticket( $id ) {
		$data[ 'title' ] = lang( 'ticket' );
		$data[ 'ticketstatustitle' ] = lang('alltickets');
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb( 'Dashboard', 'panel' );
		$this->breadcrumb->add_crumb( 'Tickets', '../tickets' );
		$this->breadcrumb->add_crumb( 'Ticket Detail' );
		$ticket = $this->Tickets_Model->get_tickets( $id );
		$data[ 'ttc' ] = $this->Report_Model->ttc();
		$data[ 'otc' ] = $this->Report_Model->otc();
		$data[ 'ipc' ] = $this->Report_Model->ipc();
		$data[ 'atc' ] = $this->Report_Model->atc();
		$data[ 'ctc' ] = $this->Report_Model->ctc();
		$data[ 'ysy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'otc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'bsy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ipc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'twy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'atc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'iey' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ctc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
		$data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
		$data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'dtickets' ] = $this->Tickets_Model->get_all_tickets();
		$data[ 'ticket' ] = $this->Tickets_Model->get_tickets( $id );
		$data[ 'all_staff' ] = $this->Staff_Model->get_all_staff();
		$this->load->view( get_template_directory() . '/crm/tickets/ticket', $data );
	}

	function assignstaff() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'ticketid' => $this->input->post( 'ticketid' ),
				'staffid' => $this->input->post( 'staffid' ),
			);
			$staffid = $this->Tickets_Model->assignstaff( $params );
			redirect( 'tickets/ticket/' . $this->input->post( 'ticketid' ) . '' );
		}
	}
	public

	function addreply() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$config[ 'upload_path' ] = './uploads/ticket_attachments/';
			$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt';
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'attachment' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$params = array(
				'ticketid' => $this->input->post( 'ticketid' ),
				'staffid' => $this->input->post( 'staffid' ),
				'contactid' => $this->input->post( 'contactid' ),
				'date' => $this->input->post( 'date' ),
				'name' => $this->input->post( 'name' ),
				'message' => $this->input->post( 'message' ),
				'attachment' => $image_data[ 'file_name' ],
			);
			// SEND EMAIL SETTINGS
			$contactinfo = $this->Contacts_Model->getUserInfo($this->input->post( 'contactid' ));
			$setconfig = $this->Settings_Model->get_settings_ciuis();
			$this->load->library( 'email' );
			$config = array();
			$config[ 'protocol' ] = 'smtp';
			$config[ 'smtp_host' ] = $setconfig['smtphost'];
			$config[ 'smtp_user' ] = $setconfig['smtpusername'];
			$config[ 'smtp_pass' ] = $setconfig['smtppassoword'];
			$config[ 'smtp_port' ] = $setconfig['smtpport'];
			$sender = $setconfig['sendermail'];
			$data = array(
				'name' => $this->session->userdata('staffname'),
				'ticketlink' => '' . base_url( 'customer/ticket/' . $this->input->post( 'ticketid' ) . '' ) . ''
			);
			$body = $this->load->view( get_template_directory() . '/crm/email/ticket.php', $data, TRUE );
			$this->email->initialize( $config );
			$this->email->set_newline( "\r\n" );
			$this->email->set_mailtype( "html" );
			$this->email->from( $sender ); // change it to yours
			$this->email->to( $contactinfo->email ); // change it to yours
			$this->email->subject( 'Your Ticket Replied' );
			$this->email->message( $body );
			$this->email->send();
			/////////////
			$replyid = $this->Tickets_Model->add_reply( $params );
			redirect( 'tickets/ticket/' . $this->input->post( 'ticketid' ) . '' );
		}
	}
	function chancestatus() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'statusid' => $_POST['statusid'],
				'ticketid' => $_POST['ticketid'],
			
			);
			$tickets = $this->Tickets_Model->chancestatus();
		}
	}
		
	function remove( $id ) {
		$this->session->set_flashdata( 'ntf4', lang('ticketdeleted') );
		$tickets = $this->Tickets_Model->get_tickets( $id );
		if ( isset( $tickets[ 'id' ] ) ) {
			$this->Tickets_Model->delete_tickets( $id );
			redirect( 'tickets/index' );
		} else
			show_error( 'Eror' );
	}
}