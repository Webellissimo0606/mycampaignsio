<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

if( ! class_exists('CIUIS_Controller') ){
	require_once 'CIUIS_Controller.php';
}

class Panel extends CIUIS_Controller {
	function index() {
		$data[ 'title' ] = 'Ciuis™ CRM';
		$data[ 'mex' ] = $this->Report_Model->mex();
		$data[ 'pme' ] = $this->Report_Model->pme();
		$data[ 'bkt' ] = $this->Report_Model->bkt();
		$data[ 'bht' ] = $this->Report_Model->bht();
		$data[ 'ogt' ] = $this->Report_Model->ogt();
		$data[ 'ohc' ] = $this->Report_Model->ohc();
		$data[ 'otc' ] = $this->Report_Model->otc();
		$data[ 'ycr' ] = $this->Report_Model->ycr();
		$data[ 'oyc' ] = $this->Report_Model->oyc();
		$data[ 'oft' ] = $this->Report_Model->oft();
		$data[ 'tef' ] = $this->Report_Model->tef();
		$data[ 'vgf' ] = $this->Report_Model->vgf();
		$data[ 'tbs' ] = $this->Report_Model->tbs();
		$data[ 'akt' ] = $this->Report_Model->akt();
		$data[ 'oak' ] = $this->Report_Model->oak();
		$data[ 'tfa' ] = $this->Report_Model->tfa();
		$data[ 'yms' ] = $this->Report_Model->yms();
		$data[ 'ttc' ] = $this->Report_Model->ttc();
		$data[ 'otc' ] = $this->Report_Model->otc();
		$data[ 'ipc' ] = $this->Report_Model->ipc();
		$data[ 'atc' ] = $this->Report_Model->atc();
		$data[ 'ctc' ] = $this->Report_Model->ctc();
		$data[ 'put' ] = $this->Report_Model->put();
		$data[ 'pay' ] = $this->Report_Model->pay();
		$data[ 'exp' ] = $this->Report_Model->exp();
		$data[ 'twt' ] = $this->Report_Model->twt();
		$data[ 'clc' ] = $this->Report_Model->clc();
		$data[ 'mlc' ] = $this->Report_Model->mlc();
		$data[ 'mtt' ] = $this->Report_Model->mtt();
		$data[ 'mct' ] = $this->Report_Model->mct();
		$data[ 'ues' ] = $this->Report_Model->ues();
		$data[ 'myc' ] = $this->Report_Model->myc();
		$data[ 'totalpaym' ] = $this->Report_Model->totalpaym();
		$data[ 'incomings' ] = $this->Report_Model->incomings();
		$data[ 'outgoings' ] = $this->Report_Model->outgoings();
		$data[ 'ysy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'otc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'bsy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ipc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'twy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'atc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'iey' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ctc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'ofy' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'tef' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
		$data[ 'clp' ] = ( $data[ 'mlc' ] > 0 ? number_format( ( $data[ 'clc' ] * 100 ) / $data[ 'mlc' ] ) : 0 );
		$data[ 'mtp' ] = ( $data[ 'mtt' ] > 0 ? number_format( ( $data[ 'mct' ] * 100 ) / $data[ 'mtt' ] ) : 0 );
		$data[ 'inp' ] = ( $data[ 'put' ] > 0 ? number_format( ( $data[ 'pay' ] * 100 ) / $data[ 'put' ] ) : 0 );
		$data[ 'ogp' ] = ( $data[ 'put' ] > 0 ? number_format( ( $data[ 'exp' ] * 100 ) / $data[ 'put' ] ) : 0 );
		$data[ 'weekly_sales_chart' ] = json_encode( $this->Report_Model->weekly_sales_chart() );
		$data[ 'weekly_expenses_chart' ] = json_encode( $this->Report_Model->weekly_expenses() );
		$data[ 'monthly_expense_graph' ] = $this->Report_Model->a1();
		$data[ 'monthly_sales_graph' ] = $this->Report_Model->monthly_sales_graph();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view(get_template_directory() . '/crm/panel/index', $data );
	}

}