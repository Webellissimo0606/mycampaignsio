<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if( ! class_exists('CIUIS_Controller') ){
	require_once 'CIUIS_Controller.php';
}
class Payments extends CIUIS_Controller {
	function add() {

		$amount = $this->input->post('amount');
		$invoicetotal = $this->input->post('invoicetotal');
		if ($amount > $invoicetotal) {
			$this->session->set_flashdata('ntf3', '<span class="text-black"><b>' . lang('paymentamounthigh') . '</b></span>');
			redirect('invoices/edit/' . $this->input->post('invoiceid') . '');
		} else {
			if (isset($_POST) && count($_POST) > 0) {
				$params = array(
					'invoiceid' => $this->input->post('invoiceid'),
					'amount' => $amount,
					'accountid' => $this->input->post('accountid'),
					'date' => _pdate($this->input->post('date')),
					'not' => $this->input->post('not'),
					'attachment' => $this->input->post('attachment'),
					'customerid' => $this->input->post('customerid'),
					'staffid' => $this->input->post('staffid'),
				);
				$payments = $this->Payments_Model->addpayment($params);
				redirect('invoices/edit/' . $this->input->post('invoiceid') . '');
			}
		}
	}
}