<?php

/**
 * CIMembership
 *
 * @package        Modules
 * @author        1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license        http://opensource.org/licenses/MIT    MIT License
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Dashboard extends CIUIS_Controller {

	public function index() {
		$data['tht'] = $this->Report_Model->tht();
		$data['settings'] = $this->Settings_Model->get_settings_ciuis();

		$data['salesThisMonth'] = $this->Report_Model->salesThisMonth();

		$data['invoices'] = $this->Report_Model->upcomingInvoices();
		$data[ 'incomings_vs_outgoins' ] = json_encode( $this->Report_Model->incomings_vs_outgoins() );
		$data[ 'invoice_chart_by_status' ] = json_encode( $this->Report_Model->invoice_chart_by_status() );
		$this->load->view(get_template_directory() . '/crm/dashboard/businessoverview', $data);

	}

	function customer_monthly_increase_chart( $month ) {
		echo json_encode( $this->Report_Model->customer_monthly_increase_chart( $month ) );
	}
}

/* End of file auth.php */
/* Location: ./controllers/home.php */
