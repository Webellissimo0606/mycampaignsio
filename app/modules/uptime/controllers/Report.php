<?php
if ( ! defined( 'BASEPATH' ) ) {
    exit('No direct script access allowed');
}

if( ! class_exists('CampaignIo_Pages_Authorized') ){
    include FCPATH . 'app/modules/auth/controllers/CampaignIo_Pages_Authorized.php';
}

class Report extends CampaignIo_Pages_Authorized {

	public function __construct() {
		parent::__construct();
		$this->load->model('auth/analyze_model');
		$this->load->model('auth/uptimestats_model');
	}

	function index() {

		$days = $this->input->get('d');
		$days = $days ? (int) $days : $days;

		$this->page_data['days_options'] = array(
            1 => array( 'label' => 'Last 24 hours' ),
            7 => array( 'label' => 'Last 7 days' ),
            30 => array( 'label' => 'Last 30 days' ),
            365 => array( 'label' => 'Last year' )
        );

        $days = in_array( $days, array_keys( $this->page_data['days_options'] ), true ) ? $days : 1;	// @note: One day by default...?

        foreach ($this->page_data['days_options'] as $key => $val) {
        	$this->page_data['days_options'][$key]['days'] = $key;
        	$this->page_data['days_options'][$key]['active'] = $key === $days;
        }

		$userId = $this->page_data['user']['id'];

		$slowest_domain = $this->uptimestats_model->getSlowestDomain( $userId );
		$fastest_domain = $this->uptimestats_model->getFastestDomain( $userId );

		// $total_domains = $this->analyze_model->getTotalDomainsByUserId( $userId );

		$total_up_domains = $this->analyze_model->getTotalUpdomainsByUserId( $userId );
		$total_down_domains = $this->analyze_model->getTotalDownDomainsByUserId( $userId );
		$total_no_stat_domain = $this->analyze_model->getTotalNoStatsDomainsByUserId( $userId );

		$uptime_data = array(
			'active_time_period_tab' => '1',
			'days' => $days,
			'uptime' => $this->uptimestats_model->getUptimeByUserId( $userId, $days ),
			'downtime' => $this->uptimestats_model->getDowntimeByUserId( $userId, $days ),
			'nostats' => $this->uptimestats_model->getNostatsDomainByUserId( $userId ),
			// 'totaldomains' => $total_domains['totalDomain'],
			'totalupdomains' => $total_up_domains['totalUpdomains'],
			'totaldowndomains' => $total_down_domains['totalDownDomain'],
			'totalnostatsdomains' => $total_no_stat_domain['totalNoStatDomain'],
			// 'latestdowntime' => $this->uptimestats_model->getLatestDownDomainByUserId( $userId ),
			'fastest_domain' => $fastest_domain[0]['fastest'] ? round( $fastest_domain[0]['fastest'] / 1000, 3 ) : 0,
			'slowest_domain' => $slowest_domain[0]['slowest'] ? round( $slowest_domain[0]['slowest'] / 1000, 3 ) : 0,
			'uptime1daypercentage' => $this->analyze_model->uptimePercentageInDaysOverall( 1, $userId ),
			'uptime7daypercentage' => $this->analyze_model->uptimePercentageInDaysOverall( 7, $userId ),
			'uptime30daypercentage' => $this->analyze_model->uptimePercentageInDaysOverall( 30, $userId ),
			/*'downtime30daypercentage' => $this->uptimestats_model->downtimePercentageInDaysOverall( 30, $userId ),*/
			'totaltime' => $this->uptimestats_model->getTotalUptimesByUserId( $userId, $days )
		);

		$uptime_data['uptime1daypercentage'] = null === $uptime_data['uptime1daypercentage'] ? '-' : round( $uptime_data['uptime1daypercentage'], 1 ) . '%';
		$uptime_data['uptime7daypercentage'] = null === $uptime_data['uptime7daypercentage'] ? '-' : round( $uptime_data['uptime7daypercentage'], 1 ) . '%';
		$uptime_data['uptime30daypercentage'] = null === $uptime_data['uptime30daypercentage'] ? '-' : round( $uptime_data['uptime30daypercentage'], 1 ) . '%';

        $this->page_data['current_page'] = 'uptime-report';
        $this->page_data['uptime_data'] = $uptime_data;

        $this->display_page( $this->page_data['current_page'] );
	}
}
