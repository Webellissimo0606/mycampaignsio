<?php
class Notifications_Model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('campaigns-io/functions');
	}
	
	function get_notifications( $id ) {
		return $this->db->get_where( 'notifications', array( 'id' => $id ) )->row_array();
	}

	function get_all_notifications() {
		$this->db->select( '*,,staff.staffname as staffmembername, staff.staffavatar as staffimage,notifications.public as public,notifications.staffid as staffid,notifications.contactid as contactid,notifications.customerid as customerid, notifications.id as notifyid ' );
		$this->db->join( 'staff', 'notifications.staffid = staff.id', 'left' );
		$this->db->from( 'notifications' );
		$this->db->order_by( "notifyid", "desc" );
		$this->db->where( 'public = "1" OR staffid = ' . $this->session->userdata( 'logged_in_staff_id' ) . '' );
		$query = $this->db->get();
		$ybs = $query->result_array();
		return $ybs;
	}

	function markread() // Ajax Function
	{
		if ( isset( $_POST[ 'notificationid' ] ) ) {
			$notificationid = $_POST[ 'notificationid' ];
			$response = $this->db->where( 'id', $notificationid )->update( 'notifications', array( 'markread' => ( '1' ) ) );
		}
	}

	function readnotification() {
		$new = $this->db->get_where( 'notifications', array( 'markread' => 1,'staffid' => $this->session->userdata('logged_in_staff_id')))->result();
		if ( $new ) {
			return '-unread';
		}
	}

	function newnotification() {
		$new = $this->db->get_where( 'notifications', array( 'markread' => 0,'staffid' => $this->session->userdata('logged_in_staff_id')))->result();
		if ( $new) {
			return 'indicator';
		}
	}
}