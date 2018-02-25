<?php

/**
 * Viewsite
 *
 * @package Modules
 * @author        
 * @copyright 
 * @license
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Site extends CI_Controller {

    private $domains_db_table = null;

    private $editDashboard = array( 
        'hasAccess' => false,
        'deniedMsg' => false,
        'deniedRedirect' => false,
    );

    private $cantEditDashboard_ms = null;

    public function __construct() {
        
        parent::__construct();

        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('auth/analyze_model');

        $this->domains_db_table = $this->ci_auth->ci->config->item('db_table_prefix') . 'domains';

        // Initialization of the site's dashboard access privileges.
        if ($this->ci_auth->canDo('login_to_frontend')) {
            if ($this->ci_auth->canDo('access_backend')) {
                
                if ($this->ci_auth->canDo('view_users')) {
                    $this->editDashboard['hasAccess'] = true;
                }
            }
            else{
                $this->editDashboard['deniedMsg'] = "You don't have permission to access this part of the site.";
                $this->editDashboard['deniedRedirect'] = site_url('auth/profile/');
            }
        }
        else{
            $this->editDashboard['deniedMsg'] = "You don't have permission to access this part of the site.";
            $this->editDashboard['deniedRedirect'] = site_url('auth/profile/');    
        }
    }

    /*
     * ==================== Pages ====================
     */
    
    public function index() {
        
        redirect(site_url('/admin/dashboard'));
    }

    public function viewsite() {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        }
        elseif ($this->ci_auth->is_logged_in(false)) {
            // logged in, not activated
            redirect('/auth/sendactivation/');
        }
        else {
            
            $id = $this->uri->segment(4);
            $id = $id ? (int)$id : '';

            //check if the site belongs to user let him access
            if( ! $this->editDashboard['hasAccess'] ){
                $userId = $this->ci_auth->get_user_id();
                $existForUser = $this->analyze_model->getDomainByUserIdAndDomainId($userId, $id);
                if($existForUser){
                    $this->editDashboard['hasAccess'] = true;
                }
                else{
                    $this->editDashboard['deniedMsg'] = "You don't have permission to access this part of the site.";
                    $this->editDashboard['deniedRedirect'] = site_url('auth/profile/');
                }
            }
          
            if( $this->editDashboard['hasAccess'] ){
                
                if( $id != '' && !empty( $id ) ) {

                    $siteData = get_site_by_id( $id, $this->ci_auth->ci );
                    $encryptedUsername = simple_crypt( get_username_encrypt_key(), $siteData->adminUsername, 'encrypt');
                    $dashboardUrl = add_url_scheme( $siteData->adminURL );
                    $params = parse_url( $dashboardUrl, PHP_URL_QUERY );
                    $dashboardUrl .= $params ? '&' : '?';
                    $dashboardUrl .= 'login=1&access_dashboard=1&username=' . $encryptedUsername;

                    $isAuthorizedClient = is_client_authorized( $id, $siteData );
                    if( 'ok' === $isAuthorizedClient ){
                        $dashboardUrl .= '&access_token=' . $siteData->access_token;
                        redirect( $dashboardUrl );
                    }
                    else{
                        set_cookie( 'redirect_after_authorize', $dashboardUrl, 120 );

                        if ( 'refresh' === $isAuthorizedClient && !empty( $siteData->refresh_token ) ) {
                            refresh_access_token( $id, $siteData, $dashboardUrl, $this->ci_auth->ci );
                        }
                        else{
                            authorize_client( $id, $this->ci_auth->ci );
                        }
                    }
                }
            }
            else{
                $this->session->set_flashdata('errors', $this->editDashboard['deniedMsg']);
                redirect( $this->editDashboard['deniedRedirect'] );
            }
        }
    }

    /*
     * Get sites available upgrades.
     */
    public function upgrades() {

        $siteId = $this->uri->segment(4);
        $siteId = $siteId ? (int) $siteId : 0;

        $upgradeType = $this->uri->segment(5);
        $upgradeType = $upgradeType ? $upgradeType : null;

        if( $siteId > 0 ){

            $siteData = get_site_by_id( $siteId, $this->ci_auth->ci );
            $siteDomain = add_url_scheme( remove_last_slash( $siteData->domain_name ) );
            $isAuthorizedClient = is_client_authorized( $siteId, $siteData );

            if( 'ok' === $isAuthorizedClient ){

                $api_pre = '/wp-json/wp-controller';
                $api_post = '/upgrades';

                switch($upgradeType) {
                    case 'all':    // All available upgrades.
                        // NOTE: Doesn't need more arguments in URL.
                        break;
                    case 'core':    // Available WP core upgrade.
                        $api_post .= '/core';
                        break;
                    case 'themes':    // Available themes upgrades.
                        $api_post .= '/themes';
                        break;
                    case 'plugins':    // Available plugins upgrades.
                        $api_post .= '/plugins';
                        break;
                    default:
                        $api_post = null;
                        break;
                }

                if( null !== $api_post ){

                    $apiURL = $siteDomain . $api_pre . $api_post . '?access_token=' . $siteData->access_token;
                    
                    $exe_ret = execute_curl_get( $apiURL );
                    $result = $exe_ret['result'];
                    $httpStatus = $exe_ret['status'];

                    if( 403 === $httpStatus && isset( $result['code'] ) && 'refresh_access_tokens' === $result['code'] ){

                        $updateData = array(
                            'access_token'      => '',
                            'refresh_token'     => '',
                            'access_expires'    => '',
                        );
                            
                        update_auth_data( $updateData, $siteId, $this->ci_auth->ci );

                        set_cookie( 'redirect_after_authorize', current_url(), 120 );
                        refresh_access_token( $siteId, array(), current_url(), $this->ci_auth->ci );

                    }
                    elseif( 200 !== $httpStatus ){
                        $result = array( 'error' => 1, 'message' => 'N/A', 'code' => 'http-status', 'res' => $result );
                    }
                }
                else{
                    $result = array( 'error' => 1, 'message' => 'N/A', 'code' => 'url-structure' );
                }

            }
            else{

                set_cookie( 'redirect_after_authorize', current_url(), 120 );

                if( 'refresh' === $isAuthorizedClient ){
                    refresh_access_token( $siteId, $siteData, current_url(), $this->ci_auth->ci );
                }
                else{
                    authorize_client( $siteId, $this->ci_auth->ci );
                }
            }
        }
        else{
            $result = array( 'error' => 1, 'message' => 'N/A', 'code' => 'site-id' );
        }

        header('Content-Type: application/json');
        print_r( json_encode( $result ) );
    }

    /*
     * Update sites WP core, themes, plugins.
     */
    public function update() {
        
        $siteId = $this->uri->segment(4);
        $siteId = $siteId ? (int)$siteId : 0;

        $updateType = $this->uri->segment(5);
        $updateType = $updateType ? $updateType : null;

        $singleUpdateSlug = $this->uri->segment(6);
        $singleUpdateSlug = $singleUpdateSlug ? $singleUpdateSlug : null;

        if( $siteId > 0 ){

            $siteData = get_site_by_id( $siteId, $this->ci_auth->ci );
            $siteDomain = add_url_scheme( remove_last_slash( $siteData->domain_name ) );
            $isAuthorizedClient = is_client_authorized( $siteId, $siteData );

            if( 'ok' === $isAuthorizedClient ){

                $api_pre = '/wp-json/wp-controller';
                $api_post = '/update';

                switch( $updateType ){
                    case 'all':
                        $api_post .= '/all';
                        break;
                    case 'themes':  // All themes.
                        $api_post .= '/themes/all';
                        break;
                    case 'plugins':  // All plugins.
                        $api_post .= '/plugins/all';
                        break;
                    case 'core':    // WP core.
                        $api_post .= '/core';
                        break;
                    case 'theme':   // Single theme.
                        if( null !== $singleUpdateSlug ){
                            $api_post .= '/themes/'.$singleUpdateSlug;
                        }
                        else{
                            $api_post = null;
                        }
                        break;
                    case 'plugin':   // Single plugin.
                        if( null !== $singleUpdateSlug ){
                            $api_post .= '/plugins/'.$singleUpdateSlug;
                        }
                        else{
                            $api_post = null;
                        }
                        break;
                    case null:
                        // NOTE: Hasn't set update type.
                        $api_post = null;
                        break;
                }

                if( null !== $api_post ){

                    $apiURL = $siteDomain . $api_pre . $api_post . '?access_token=' . $siteData->access_token;

                    $exe_ret = execute_curl_post( $apiURL );
                    $result = $exe_ret['result'];
                    $httpStatus = $exe_ret['status'];

                    if( 200 !== $httpStatus ){
                        $result = array( 'error' => 1, 'message' => 'N/A', 'code' => 'http-status' );
                    }
                }
                else{
                    $result = array( 'error' => 1, 'message' => 'N/A', 'code' => 'url-structure' );
                }

            }
            else{
                
                set_cookie( 'redirect_after_authorize', current_url(), 120 );

                if( 'refresh' === $isAuthorizedClient ){
                    refresh_access_token( $siteId, $siteData, current_url(), $this->ci_auth->ci );
                }
                else{
                    authorize_client( $siteId, $this->ci_auth->ci );
                }
            }
            
        }
        else{
            $result = array( 'error' => 1, 'message' => 'N/A', 'code' => 'site-id' );
        }

        header('Content-Type: application/json');
        print_r( json_encode( $result ) );
    }

    public function summarycounts() {

        // Get all sites
                
        $userid  = $this->ci_auth->get_user_id();
        $domains = $this->analyze_model->getDomainDetails($userid);

        $return = array(
            'core'  => 0,
            'themes'  => 0,
            'plugins'  => 0,
        );

        if( $domains && ! empty( $domains ) ){

            foreach ( $domains as $key => $val ) {

                if( '' !== $val->adminURL && '' !== $val->adminUsername ){

                    $isAuthorizedClient = is_client_authorized( $val->id );

                    if( 'ok' === $isAuthorizedClient ){

                        $apiURL = add_url_scheme( remove_last_slash( $this->ci_auth->ci->config->config['base_url'] ) );
                        $apiURL .= '/auth/site/upgrades/' . $val->id . '/all/?access_token=' . $val->access_token;

                        $exe_ret = execute_curl_get( $apiURL );
                        $result = $exe_ret['result'];
                        $httpStatus = $exe_ret['status'];

                        if( $result && ! empty( $result ) && 200 === $httpStatus ){

                            if( isset( $result['core'] ) && $result['core'] ){
                                $return['core']++;
                            }

                            if( isset( $result['themes'] ) && ! empty( $result['themes'] ) ){
                                $return['themes'] += count( $result['themes'] );
                            }

                            if( isset( $result['plugins'] ) && ! empty( $result['plugins'] ) ){
                                $return['plugins'] += count( $result['plugins'] );
                            }

                        }
                    }
                    else{

                        set_cookie( 'redirect_after_authorize', current_url(), 120 );

                        if( 'refresh' === $isAuthorizedClient ){
                            refresh_access_token( $val->id, array(), current_url(), $this->ci_auth->ci );
                        }
                        else{
                            authorize_client( $val->id, $this->ci_auth->ci );
                        }

                    }

                }

            }
        }

        echo json_encode( $return );
    }

    /*
     * Get client data (client_id, client_secret) from oAuth Server.
     */
    public function retrieve_client_data_page() {

        $id = $this->uri->segment(4);
        $status = $this->uri->segment(5);
        $security_param = $this->uri->segment(6);
        $clientid = $this->uri->segment(7);
        $clientsecret = $this->uri->segment(8);

        $id = $id ? (int) $id : 0;
        $status = $status ? $status : null;
        $security_param = $security_param ? $security_param : null;
        $clientid = $clientid ? $clientid : null;
        $clientsecret = $clientsecret ? $clientsecret : null;

        $client_data_security_param = get_cookie( 'client_data_security_param' );

        if( 0 < $id && 0 <= strpos( $status, 'success' ) && null !== $security_param && 0 <= strpos( $security_param, $client_data_security_param ) ){

            delete_cookie( 'client_data_security_param' );

            // Check if site exists
            $siteData = get_site_by_id( $id, $this->ci_auth->ci );

            if ( ! empty( $siteData ) && $clientid != null && $clientsecret != null ) {

                $clientData = array( 
                    'client_id' => $clientid, 
                    'client_secret' => $clientsecret,
                );

                update_client_data( $clientData, $id, $this->ci_auth->ci );

                $redirect_after_clData = get_cookie( 'get_tokens_after_client_data' );

                if( $redirect_after_clData && 'yes' === $redirect_after_clData ){
                    delete_cookie( 'get_tokens_after_client_data' );
                    request_client_code_and_tokens( $id, $this->ci_auth->ci );
                }                
            }
        }
    }    

    /*
     * Get client code and request tokens from oAuth Server.
     */
    public function retrieve_client_code_and_tokens_page() {

        $siteId = $this->uri->segment(4);
        $siteId = $siteId ? (int) $siteId : 0;

        $security_param = $this->uri->segment(5);
        $security_param = $security_param ? $security_param : null;

        $code_tokens_security_param = get_cookie( 'code_tokens_security_param' );

        if( null !== $security_param && 
            0 <= strpos( $security_param, $code_tokens_security_param ) && 
            isset( $_GET['code'] ) && 
            '' !== $_GET['code'] &&
            0 < $siteId 
        ){            
            $siteData = get_site_by_id( $siteId, $this->ci_auth->ci );

            $request_options = array(
                'client_id'     => $siteData->client_id,
                'redirect_uri'  => site_url( 'auth/site/retrieve_client_code_and_tokens_page/' . $siteId . '/' . $code_tokens_security_param ), // NOTE: Redirection MUST be (in) the same page.
                'client_secret' => $siteData->client_secret,
                'code'          => $_GET['code'],
                'grant_type'    => 'authorization_code'
            );

            $exe_ret = execute_curl_post( $siteData->domain_name . '/oauth/token', $request_options );
            $result = $exe_ret['result'];
            $httpStatus = $exe_ret['status'];

            delete_cookie( 'code_tokens_security_param' );

            if( 400 === $httpStatus && isset($result['error']) && 'invalid_grant' === $result['error'] ){
                print_r( $result );
                exit;
            }

            if( 200 === $httpStatus && ! empty( $result ) ){
                save_auth_tokens( $siteId, $result, false, $this->ci_auth->ci );
            }
        }
        elseif( isset( $_GET['error'] ) && '' !== $_GET['error'] ){

            if( isset( $_GET['error_description'] ) && '' !== $_GET['error_description'] ){
                print_r( $_GET['error_description'] );
            }
            else{

                print_r('Error on retrieving client credentials.');
            }
        }
    }
     
    /*
     * ==================== Helpers ====================
     */
}
