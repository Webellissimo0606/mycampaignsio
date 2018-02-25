<?php
class Events_Model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('campaigns-io/functions');
	}

	public function get_events_json() {
		$this->db->select( 'id id,title title,detail activitydetail,staffid staffmember,staffname stafname,start start,end end,color color' );
		$this->db->from( 'events' );
		$this->db->where( 'events.company_id', $_SESSION['company_id'] );
		if($this->session->userdata( 'admin' ) != 1){
			$this->db->where( 'public = "true" OR staffid = ' . $this->session->userdata( 'logged_in_staff_id' ) . '' );
		}
		return $this->db->get()->result();
	}

	function get_all_events() {

		$this->db->select( '*,,staff.staffname as staffmembername, staff.staffavatar as staffimage, events.id as id ' );
		$this->db->join( 'staff', 'events.staffid = staff.id', 'left' );
		$this->db->limit( 3 );
		$this->db->where( 'events.company_id', $_SESSION['company_id'] );
		return $this->db->get_where( 'events', array( 'DATE(start)' => date( 'Y-m-d' ) ) )->result_array();
	}
	
	function add_event( $params ) {
		$this->db->insert( 'events', $params );
		return $this->db->insert_id();
	}
	
	function remove( $id ) {
		$response = $this->db->delete( 'events', array( 'id' => $id ) );
	}
}