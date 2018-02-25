<?php
class Tickets_Model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('campaigns-io/functions');
	}

	/* Get Tickets */

	function get_tickets( $id ) {
		$this->db->select( '*,customers.type as type,customers.companyname as companyname,customers.namesurname as namesurname,departments.name as department,staff.staffname as staffmembername,contacts.name as contactname,contacts.surname as contactsurname,tickets.staffid as stid, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contactid = contacts.id', 'left' );
		$this->db->join( 'customers', 'contacts.customerid = customers.id', 'left' );
		$this->db->join( 'departments', 'tickets.department = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staffid = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.id' => $id ) )->row_array();
	}

	/* Get All Tickets */
	function get_all_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contactid = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staffid = staff.id', 'left' );
		return $this->db->order_by( 'date desc, priority desc' )->order_by( "date", "desc" )->get_where( 'tickets', array() )->result_array();
	}

	function get_all_open_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contactid = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staffid = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.statusid' => 1 ) )->result_array();
	}

	function get_all_inprogress_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contactid = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staffid = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.statusid' => 2 ) )->result_array();
	}

	function get_all_answered_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contactid = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staffid = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.statusid' => 3 ) )->result_array();
	}

	function get_all_closed_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contactid = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staffid = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.statusid' => 4 ) )->result_array();
	}

	function add_tickets( $params ) {
		$this->db->insert( 'tickets', $params );
		$ticket = $this->db->insert_id();
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('added').' <a href="/tickets/ticket/' . $ticket . '"> ' . $ticket . '</a>' ),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id']
		) );
		return $this->db->insert_id();
	}

	function add_reply( $params ) {
		$this->db->insert( 'ticketreplies', $params );
		$ticket = $this->input->post( 'ticketid' );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('replied').' <a href="/tickets/ticket/' . $ticket . '"> '.lang('ticket').'-' . $ticket . '</a>' ),
			'staffid' => $loggedinuserid,
            'company_id' => $_SESSION['company_id']
		) );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$staffavatar = $this->session->staffavatar;
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<div class="text"><span class="user-name">' . $staffname . '</span> replied ' . lang( 'ticket' ) . '-' . $this->input->post( 'ticketid' ) . '</div>' ),
			'contactid' => $this->input->post( 'contactid' ),
			'perres' => $staffavatar,
			'target' => '' . base_url( 'customer/ticket/' . $this->input->post( 'ticketid' ) . '' ) . ''
		) );
		$response = $this->db->where( 'id', $this->input->post( 'ticketid' ) )->update( 'tickets', array(
			'statusid' => 3,
			'lastreply' => date( "Y.m.d H:i:s " ),
			'staffid' => $loggedinuserid,
		) );
		return $this->db->insert_id();
	}

	function add_reply_contact( $params ) {
		$this->db->insert( 'ticketreplies', $params );
		$contact = $_SESSION[ 'name' ];
		$contactavatar = 'n-img.png';
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<div class="text"><span class="user-name">' . $contact . '</span> replied ' . lang( 'ticket' ) . '-' . $this->input->post( 'ticketid' ) . '</div>' ),
			'perres' => $contactavatar,
			'staffid' => $this->input->post( 'staffid' ),
			'target' => '' . base_url( 'tickets/ticket/' . $this->input->post( 'ticketid' ) . '' ) . ''
		) );
		$response = $this->db->where( 'id', $this->input->post( 'ticketid' ) )->update( 'tickets', array(
			'statusid' => 1,
			'lastreply' => date( "Y.m.d H:i:s " ),
		) );
		return $this->db->insert_id();
	}

	function update_tickets( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'tickets', $params );
	}

	function assignstaff( $params ) {
		$response = $this->db->where( 'id', $this->input->post( 'ticketid' ) )->update( 'tickets', array(
			'staffid' => $this->input->post( 'staffid' ),
		) );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$staffavatar = $this->session->staffavatar;
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<div class="text"><span class="user-name">' . $staffname . '</span> assigned you a ' . lang( 'ticket' ) . '-' . $this->input->post( 'ticketid' ) . '</div>' ),
			'staffid' => $this->input->post( 'staffid' ),
			'perres' => $staffavatar,
			'target' => '' . base_url( 'tickets/ticket/' . $this->input->post( 'ticketid' ) . '' ) . ''
		) );
	}

	function chancestatus() {
		$response = $this->db->where( 'id', $_POST['ticketid'] )->update( 'tickets', array( 'statusid' => $_POST['statusid'] ) );
	}
	
	function delete_tickets( $id ) {
		$response = $this->db->delete( 'tickets', array( 'id' => $id ) );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $message = sprintf( lang( 'xdeletedxticket' ), $staffname,$id ) . '' ),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id']
		) );
	}
	
	public function weekly_ticket_stats() {
		$this->db->where( 'CAST(date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week', strtotime( 'last sunday' ) ) ) . '" AND CAST(date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week', strtotime( 'last sunday' ) ) ) . '"' );
		$tickets = $this->db->get( 'tickets' )->result_array();
		$chart = array(
			'labels' => get_weekdays(),
			'datasets' => array(
				array(
					'label' => 'Weekly Ticket Report',
					'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
					'borderColor' => '#c53da9',
					'borderWidth' => 1,
					'tension' => false,
					'data' => array(
						0,
						0,
						0,
						0,
						0,
						0,
						0
					)
				)
			)
		);
		foreach ( $tickets as $ticket ) {
			$ticket_day = date( 'l', strtotime( $ticket[ 'date' ] ) );
			$i = 0;
			foreach ( get_weekdays_original() as $day ) {
				if ( $ticket_day == $day ) {
					$chart[ 'datasets' ][ 0 ][ 'data' ][ $i ]++;
				}
				$i++;
			}
		}
		return $chart;
	}

}
