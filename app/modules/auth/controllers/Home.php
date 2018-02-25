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

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load_models();
        $this->load->helper('campaigns-io/functions');
    }

    protected function validate_user_access() {
        if ( ! $this->ci_auth->is_logged_in() ) {
            redirect( base_url('auth/login') );
            exit;
        }
        else if( $this->ci_auth->is_logged_in( false ) ) {
            redirect( base_url( 'auth/sendactivation' ) );
            exit;
        }
    }

    protected function load_models() {
        $this->load->model('auth/analyze_model');
        $this->load->model('auth/groups_model');
        $this->load->model('auth/uptimestats_model');
        $this->load->model('uptime/uptimeincident_model');
    }

    public function index() {
        
        $this->validate_user_access();

        $domain_id = $this->uri->segment(3);
        $domain_id = $domain_id ? (int) $domain_id : '';
        
        $data['domainid'] = $domain_id;

        $currentSubuserId = isset( $_SESSION['currentsubuser'] ) ? $_SESSION['currentsubuser'] : 0;
        $currentSubuserId = $currentSubuserId && '' !== $currentSubuserId ? (int) $currentSubuserId : 0;
        $currentSubuserId = $currentSubuserId ? $currentSubuserId : $this->ci_auth->get_user_id();

        $groupId = isset( $_SESSION['currentgroup'] ) ? $_SESSION['currentgroup'] : 0;
        $groupId = $groupId && '' !== $groupId ? (int) $groupId : 0;
        
        if( $groupId ){
            $data['total_domains'] =  $this->groups_model->getTotalDomainsBygroupId( $groupId );
            $data['up_domains'] =  $this->groups_model->getTotalUpdomainsBygroupId( $groupId );
            $data['down_domains'] = $data['total_domains']['totalDomain'] - $data['up_domains']['totalUpdomains'];
        }
        else{
            $data['total_domains'] =  $this->analyze_model->getTotalDomainsByUserId( $currentSubuserId );
            $data['up_domains'] =  $this->analyze_model->getTotalUpdomainsByUserId( $currentSubuserId );
            $data['down_domains'] = $data['total_domains']['totalDomain'] - $data['up_domains']['totalUpdomains'];
        }

        $this->load->view( get_template_directory() . '/home_new', $data );
    }

    public function alldomains() {

        if ( ! $this->ci_auth->is_logged_in() ) {
            redirect( base_url('auth/login') );
            exit;
        }

        if ( $this->ci_auth->is_logged_in( false ) ) {  // logged in, not activated.            
            $return['status'] = false;
            $retun['domains'] = null;
        }
        else {
            
            $currentSubuserId = isset( $_SESSION['currentsubuser'] ) ? $_SESSION['currentsubuser'] : 0;
            $currentSubuserId = $currentSubuserId && '' !== $currentSubuserId ? (int) $currentSubuserId : 0;
            $currentSubuserId = $currentSubuserId ? $currentSubuserId : $this->ci_auth->get_user_id();

            $currentGroup = isset( $_SESSION['currentgroup'] ) ? $_SESSION['currentgroup'] : 0;
            $currentGroup = $currentGroup && '' !== $currentGroup ? (int) $currentGroup : 0;

            $domain = isset( $_POST['domain'] ) ? $_POST['domain'] : '';

            if( $currentGroup ){
                $domains = $this->groups_model->getGroupDetailByGroupId( $currentgroup, 7 );
            }
            else{
                $domains = $this->analyze_model->getDomainsByUserId( $currentSubuserId, $domain, 7, $_POST['page'] );
            }            
            
            $return = array();
            
            foreach( $domains as $key => $domain ){
                
                $return['domain'][$key] = $domain;

                $return['domain'][$key]['domain_name'] = strtr(
                    $domain['domain_name'],
                    array(
                        '/' => '',
                        'https://' => '',
                        'http://' => '',
                        'http://www.' => '',
                        'https://www.' => ''
                    )
                );
                
                // $return['domain'][$key]['uptimepercentage']  = $this->uptimestats_model->uptimePercentageInDays($domain['id'],1);
            }
            
            $return['status'] = $domains ? true : false;
        }

        echo json_encode( $return );
        die;
    }

    public function uptimestats() {

        $user_id = $this->ci_auth->is_logged_in() && ! $this->ci_auth->is_logged_in( false ) ? $this->ci_auth->get_user_id() : $this->input->post('userId');

        if( $user_id ){
            $uptime = $this->analyze_model->getUptimeDashboardStatByUserId( $user_id );
            $return['status'] = true;
            $return['uptime'] = $uptime;
        }
        else if( ! $this->ci_auth->is_logged_in() ){
            redirect( site_url('auth/login' ) );
            exit;
        }
        else if( $this->ci_auth->is_logged_in(false) ){ // Logged in, not activated.
            $return['status'] = false;
            $retun['uptime'] = null;
        }

        echo json_encode($return);
        die;
    }

    public function getresponsegraph() {

        $uptimedaystats = $this->uptimestats_model->uptimeDayStatsByUserId( $this->ci_auth->get_user_id() );
        
        if ($uptimedaystats) {
            $data['uptimestats'] = $uptimedaystats;
            $return['payload'] = $data;
            $return['type'] = 'success';
        }
        else {
            $data['uptimestats'] = null;
            $return['type'] = 'error';
            $return['payload'] = null;
        }

        echo json_encode($return);
        die;
    }

    public function incidentreport() {

        $incidents = $this->uptimeincident_model->getDownIncidentsByUserId( $this->ci_auth->get_user_id() );
        
        if( $incidents ) {
            
            $temp = array();

            foreach( $incidents as $key => $incident ){
                $temp[$key] = $incident;

                $domain = strtr( $incident['domain_name'], array( 'https://' => '', 'http://' => '', 'www.' => '', '/' => '' ) );
                $temp[$key]['domain_name'] = $domain;

                $date1 = $incident['downtime'];
                $date2 = $incident['uptime'];

                $diff = $date2 ? abs( strtotime( $date2 ) - strtotime( $date1 ) ) : '-';

                $temp[$key]['totaloutagetime'] = '-' !== $diff ? gmdate( 'H:i:s', $diff ) : '-';
            }

            $return['payload'] = $temp;
            $return['type'] = 'success';
        }
        else {
            $return['payload'] = null;
            $return['type'] = 'error';
        }

        echo json_encode( $return );
        die;
    }
}

/* End of file auth.php */
/* Location: ./controllers/home.php */
