<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Share extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model( 'Settings_Model' );
		define('LANG', $this->Settings_Model->get_crm_lang());
		define('currency', $this->Settings_Model->get_currency());
        $this->lang->load(LANG, LANG);
		$this->load->model( 'Invoices_Model' );
		$this->load->model( 'Proposals_Model' );
		$this->load->model( 'Report_Model' );
	}
	public
	function invoice($id) {
		$data[ 'title' ] = 'INV-'.$id.' Detail';
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb( 'Dashboard', 'panel' );
		$this->breadcrumb->add_crumb( 'Invoices', 'invoices' );
		$this->breadcrumb->add_crumb( 'Invoice Details' );
		$this->load->model( 'Invoices_Model' );
		$this->load->model( 'Settings_Model' );
		$data[ 'invoices' ] = $this->Invoices_Model->get_invoice_detail( $id );
		$data[ 'invoiceitems' ] = $this->db->select( '*,products.productname as name,invoiceitems.id as id ' )->join( 'products', 'invoiceitems.in[product_id] = products.id', 'left' )->get_where( 'invoiceitems', array( 'invoiceid' => $id ) )->result_array();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'invoices/shareinvoice', $data );
	}
	function proposal($id) {
		$data[ 'title' ] = 'PRO-'.$id.' Detail';
		$this->breadcrumb->clear();
		$this->breadcrumb->add_crumb( 'Dashboard', 'panel' );
		$this->breadcrumb->add_crumb( 'Invoices', 'invoices' );
		$this->breadcrumb->add_crumb( 'Invoice Details' );
		$this->load->model( 'Proposals_Model' );
		$this->load->model( 'Settings_Model' );
		$pro = $this->Proposals_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'proposals' ] = $this->Proposals_Model->get_proposals( $id, $rel_type );
		$data[ 'proposalitems' ] = $this->db->select( '*,products.productname as urun,proposalitems.id as id ' )->join( 'products', 'proposalitems.in[product_id] = products.id', 'left' )->get_where( 'proposalitems', array( 'proposalid' => $id ) )->result_array();
		$data[ 'comments' ] = $this->db->get_where( 'comments', array( 'relation' => $id, 'relation_type' => 'proposal' ) )->result_array();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'proposals/shareproposal', $data );
	}
	function pdf( $id ) {
		$ind = $this->Invoices_Model->get_invoice_detail($id);
		$data['title'] = ''.lang('invoiceprefix').'-'.str_pad($id, 6, '0', STR_PAD_LEFT).'';
		$this->load->library( 'Pdf' );
		$data[ 'invoices' ] = $this->Invoices_Model->get_invoice_detail( $id );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'invoiceitems' ] = $this->db->select( '*,products.productname as urun,invoiceitems.id as id ' )->join( 'products', 'invoiceitems.in[product_id] = products.id', 'left' )->get_where( 'invoiceitems', array( 'invoiceid' => $id ) )->result_array();
		$this->load->view( 'invoices/invoice_pdf', $data );
	}
	function pdf_proposal( $id ) {
		$pro = $this->Proposals_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'proposals' ] = $this->Proposals_Model->get_proposals( $id, $rel_type );
		$data[ 'title' ] = '' . lang( 'proposalprefix' ) . '-' . str_pad( $id, 6, '0', STR_PAD_LEFT ) . '';
		$this->load->library( 'Pdf' );
		$obj_pdf = new TCPDF( 'I', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'proposalitems' ] = $this->db->select( '*,products.productname as urun,proposalitems.id as id ' )->join( 'products', 'proposalitems.in[product_id] = products.id', 'left' )->get_where( 'proposalitems', array( 'proposalid' => $id ) )->result_array();
		$this->load->view( 'proposals/proposal_pdf', $data );
	}
	function customercomment() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'content' => $this->input->post( 'content' ),
				'relation' => $this->input->post( 'relation' ),
				'relation_type' => 'proposal',
				'staffid' => $this->session->userdata( 'logged_in_staff_id' ),
				'dateadded' => date( 'Y-m-d H:i:s' ),
			);
			$action = $this->db->insert( 'comments', $params );
			$proposals = $this->Proposals_Model->get_pro_rel_type($this->input->post( 'relation' ));
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<div class="text"><span class="user-name">' . ( '' . $message = sprintf( lang( 'newcommentforproposal' ), str_pad( $proposals[ 'id' ], 6, '0', STR_PAD_LEFT ) ) . '' ) . '</span></div>' ),
				'staffid' => $proposals['assigned'],
				'perres' => 'customer_avatar_comment.png',
				'target' => '' . base_url( 'proposals/proposal/' . $proposals[ 'id' ] . '' ) . ''
			) );
			$this->session->set_flashdata( 'ntf1', '' . lang( 'commentadded' ) . '' );
			redirect( 'share/proposal/' . $this->input->post( 'relation' ) . '' );
		} else {
			redirect( 'proposals/index' );
		}
	}
	function markasproposal() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'proposal' => $_POST[ 'proposal' ],
				'statusid' => $_POST[ 'statusid' ],
			);
			if($_POST['statusid'] == 5){$notificationmessage = lang('proposaldeclined');}
			if($_POST['statusid'] == 6){$notificationmessage = lang('proposalaccepted');}
			$proposals = $this->Proposals_Model->get_proposal($_POST[ 'proposal' ]);
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<div class="text"><span class="user-name">' . ( '' . $message = sprintf($notificationmessage, str_pad( $proposals[ 'id' ], 6, '0', STR_PAD_LEFT ) ) . '' ) . '</span></div>' ),
				'staffid' => $proposals['assigned'],
				'perres' => 'customer_avatar_comment.png',
				'target' => '' . base_url( 'proposals/proposal/' . $proposals[ 'id' ] . '' ) . ''
			) );
			$actionpro = $this->Proposals_Model->markas();
		}
	}

}