<?php
if ( ! defined( 'BASEPATH' ) ) {
    exit('No direct script access allowed');
}

if( ! class_exists('CampaignIo_Pages_Authorized') ){
    include FCPATH . 'app/modules/auth/controllers/CampaignIo_Pages_Authorized.php';
}

class Statusreport extends CampaignIo_Pages_Authorized {

    public function __construct() {
        parent::__construct();
        $this->load->model('auth/uptimestats_model');
    }

    public function index() {
        
        $days = $this->input->get('d');
        $days = $days ? (int) $days : $days;

        $this->page_data['days_options'] = array(
            1 => array( 'label' => 'Last 24 hours' ),
            7 => array( 'label' => 'Last 7 days' ),
            30 => array( 'label' => 'Last 30 days' ),
            365 => array( 'label' => 'Last year' )
        );

        $days = in_array( $days, array_keys( $this->page_data['days_options'] ), true ) ? $days : 1;    // @note: One day by default...?

        foreach ($this->page_data['days_options'] as $key => $val) {
            $this->page_data['days_options'][$key]['days'] = $key;
            $this->page_data['days_options'][$key]['active'] = $key === $days;
        }

        $uptime_data = array(
            'days' => $days,
            'uptime' => $this->uptimestats_model->getUptimeByUserId( $this->ci_auth->get_user_id(), $days ),
            'downtime' => $this->uptimestats_model->getDowntimeByUserId( $this->ci_auth->get_user_id(), $days ),
            'totaltime' => $this->uptimestats_model->getTotalUptimesByUserId( $this->ci_auth->get_user_id(), $days )
        );

        $this->page_data['uptime_data'] = $uptime_data;
        $this->page_data['current_page'] = 'uptime-status-report';

        $this->display_page( $this->page_data['current_page'] );

    }
}
