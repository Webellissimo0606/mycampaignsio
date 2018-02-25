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

class Wordpress extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('auth/analyze_model');        
    }

    public function index() {
        if( ! $this->ci_auth->is_logged_in() ) {
            redirect( site_url('auth/login') );
        }
        elseif( $this->ci_auth->is_logged_in( false ) ){
            // logged in, not activated
            redirect('/auth/sendactivation/');
        }
        else {
            /* logged in */

            $domain_id = $this->uri->segment(3);
            $user_id = $this->ci_auth->get_user_id();

            $is_user_domain = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $domain_id );

            if( ! $is_user_domain ){
                redirect('/auth/home');
            }

            $domain_details = $this->analyze_model->getDomain( $domain_id );
            if( ! $domain_details || empty( $domain_details ) ){
                redirect('/auth/home');
            }
            
            $domain_details = $domain_details[0];

            $session_user_data = $this->session->get_userdata();

            $session_user_data['domainId'] = $domain_details->id;
            $session_user_data['monitorMalware'] = $domain_details->monitorMalware;
            $session_user_data['adminURL'] = $domain_details->adminURL;
            $session_user_data['adminUsername'] = $domain_details->adminUsername;
            $session_user_data['domainUrl'] = $domain_details->domain_name;
            $session_user_data['gaAccount'] = $domain_details->ga_account;
            $session_user_data['connectToGoogle'] = $domain_details->connectToGoogle;
            $session_user_data['monitorOnPageIssues'] = $domain_details->monitorOnPageIssues;
            $session_user_data['domainHost'] = $domain_details->host;
            $session_user_data['webmaster'] = $domain_details->webmaster;
            $session_user_data['search_analytics'] = $domain_details->search_analytics;

            $this->session->set_userdata( $session_user_data );


            if( ! function_exists('enqueue_html_stylesheets') ){
				require_once FCPATH . 'app/helpers/campaigns-io/functions_helper.php';
			}
			
			if( ! function_exists('WP_Request_Access') ){
				require_once FCPATH . 'app/helpers/campaigns-io/classes_helper.php';
			}

            $site = array( 
                'id' => $domain_id,
                'data' => get_site_by_id( $domain_id, $this->ci_auth->ci ),
            );
            
            $wp_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
            $updates_info = $wp_access->updates( $site, 'all', false, true);
            $updates_info['core'] = isset( $updates_info['core'] ) ? $updates_info['core'] : array();
            $updates_info['plugins'] = isset( $updates_info['plugins'] ) ? $updates_info['plugins'] : array();
            $updates_info['themes'] = isset( $updates_info['themes'] ) ? $updates_info['themes'] : array();

            $this->load->view(get_template_directory() . '/wordpress', array('wp_updates_info' => $updates_info) );
        }

    }
}

/* End of file auth.php */
/* Location: ./controllers/home.php */
