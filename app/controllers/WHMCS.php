<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class WHMCS extends CIUIS_Controller {
	public $settings = [];
	function __construct() {
		parent::__construct();
		// $this->load->library('paypal_lib');
		// $this->load->model('Invoices_Model');
	}

	function index() {
		// if (!$this->input->is_cli_request()) {
		// 	echo "greet my only be accessed from the command line";
		// 	return;
		// }
	
		$allcompanies = $this->Settings_Model->get_all_settings('ciuis');
		foreach ($allcompanies as $key => $company) {
			$this->settings = $company;
			if (isset($this->settings['whmcs_url']) && !empty($this->settings['whmcs_url'])) {
				$_SESSION['company_id'] = $this->settings['company_id'];
				$this->syncClients();

			}
		}

	}
	function syncClients() {
		echo "Starting Client Synchronization" . PHP_EOL;
		$data = $this->callAPI(['action' => 'GetClients']);
		if ($data['numreturned'] > 0) {
			foreach ($data['clients']['client'] as $key => $client) {
				$customer = $this->Customers_Model->get_customer_by_email($client['email']);
				if (empty($customer)) {
					$params = array(
						'company_id' => $this->settings['company_id'],
						'staffid' => $this->settings['company_id'],
						'datecreated' => date('Y-m-d H:i:s'),
						'type' => 1,
						'companyname' => $client['companyname'],
						'namesurname' => $client['lastname'] . " " . $client['lastname'],
						'companyemail' => $client['email'],
						'datecreated' => $client['datecreated'],
						'status' => ($client['status'] == "Active") ? 1 : 0,
					);
					$customers_id = $this->Customers_Model->add_customers($params);
				} else {

					$params = array(
						'company_id' => $this->settings['company_id'],
						'staffid' => $this->settings['company_id'],
						'datecreated' => date('Y-m-d H:i:s'),
						'type' => 1,
						'companyname' => $client['companyname'],
						'namesurname' => $client['lastname'] . " " . $client['lastname'],
						'companyemail' => $client['email'],
						'status' => ($client['status'] == "Active") ? 1 : 0,
					);
					$customers_id = $customer['id'];
					$this->Customers_Model->update_customers($customers_id, $params);
				}
				$this->syncInvoices($client['id'], $customers_id);
			}
		}
	}
	function syncInvoices($clientid, $customers_id) {
		// echo "Synchronizing Invoices for client " . $clientid . PHP_EOL;
		$end = false;
		$limitstart = 0;
		$limitnum = 100;
		$page = 0;
		while (!$end) {
			$limitstart = $page * $limitnum;
			$invoices = $this->callAPI(['action' => 'GetInvoices', 'userid' => $clientid, 'limitnum' => $limitnum, 'limitstart' => $limitstart]);
			if ($invoices['numreturned'] == 0) {
				$end = true;
				break;
			} else {
				foreach ($invoices['invoices']['invoice'] as $key => $invoice) {
					$invoice['invoicenum'] = ($invoice['invoicenum']) ? $invoice['invoicenum'] : $invoice['id'];
					$crminvoice = $this->Invoices_Model->get_invoiceByNumber($invoice['invoicenum']);
					$invoicestatus = $this->Invoices_Model->get_invoicestatusid($invoice['status']);
					if ($crminvoice) {
						$params = array(
							'company_id' => $this->settings['company_id'],
							'no' => $invoice['invoicenum'],
							'series' => "",
							'customerid' => $customers_id,
							'orderid' => 0,
							'staffid' => $this->settings['company_id'],
							'datecreated' => _pdate($invoice['date']),
							'duedate' => _pdate($invoice['duedate']),
							'datesend' => _pdate($invoice['date']),
							'date_payment' => _pdate($invoice['datepaid']),
							'statusid' => $invoicestatus['id'],
							'total_sub' => $invoice['subtotal'],
							'total_vat' => ($invoice['tax'] + $invoice['tax2']),
							'total' => $invoice['total'],
							'notetitle' => $invoice['notes'],
						);
						// $invoices_id = $this->Invoices_Model->update_invoices($crminvoice['id'], $params);
						$this->db->where('id', $crminvoice['id']);
						$invoice = $crminvoice['id'];
						$response = $this->db->update('invoices', $params);
					} else {
						$params = array(
							'company_id' => $this->settings['company_id'],
							'no' => $invoice['invoicenum'],
							'series' => "",
							'customerid' => $customers_id,
							'orderid' => 0,
							'staffid' => $this->settings['company_id'],
							'datecreated' => _pdate($invoice['date']),
							'duedate' => _pdate($invoice['duedate']),
							'datesend' => _pdate($invoice['date']),
							'date_payment' => _pdate($invoice['datepaid']),
							'statusid' => $invoicestatus['id'],
							'total_sub' => $invoice['subtotal'],
							'total_vat' => ($invoice['tax'] + $invoice['tax2']),
							'total' => $invoice['total'],
							'notetitle' => $invoice['notes'],
						);
						$this->db->insert('invoices', $params);
						$invoices_id = $this->db->insert_id();
						$this->syncTransactions($invoice['id'], $invoices_id, $customers_id);
					}

				}
			}
			$page++;
			if ($page == 6) {
				break;
			}

		}
	}
	function syncTransactions($invoiceid, $invoices_id, $customerid) {
		$transactions = $this->callAPI(['action' => 'GetTransactions', 'invoiceid' => $invoiceid]);

		if ($transactions['numreturned'] > 0) {
			foreach ($transactions['transactions']['transaction'] as $key => $transaction) {
				$amount = $transaction['amountin'];
				$params = array(
					'invoiceid' => $invoices_id,
					'amount' => $amount,
					'accountid' => $this->settings['whmcsholdingaccount'],
					'date' => _pdate($transaction['date']),
					'customerid' => $customerid,
					'staffid' => $this->settings['company_id'],
				);
				$payments = $this->Payments_Model->addpayment($params);

			}
		}

	}
	function callAPI($params) {
		$whmcsUrl = $this->settings['whmcs_url'];

		$username = $this->settings['whmcs_identifier'];
		$password = $this->settings['whmcs_secret'];

		$postfields = array(
			'username' => $username,
			'password' => $password,
			'responsetype' => 'json',
		);
		$postfields = array_merge($params, $postfields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $whmcsUrl . 'includes/api.php');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
		$response = curl_exec($ch);
		if (curl_error($ch)) {
			// die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
		}
		curl_close($ch);

// Decode response
		$jsonData = json_decode($response, true);

// Dump array structure for inspection
		return $jsonData;

	}

}