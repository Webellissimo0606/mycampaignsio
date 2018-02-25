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

    // private $domains_db_table = null;

    private $editDashboard = array( 
        'hasAccess' => false,
        'deniedMsg' => false,
        'deniedRedirect' => false,
    );

    public function __construct() {
        
        parent::__construct();

        // $this->load->helper('cookie');
        $this->load->helper('url');
        // $this->load->helper('date');
        $this->load->model('auth/analyze_model');

        // $this->domains_db_table = $this->ci_auth->ci->config->item('db_table_prefix') . 'domains';

        // Initialization of the site's dashboard access privileges.
        if ($this->ci_auth->canDo('login_to_frontend')) {
            if ($this->ci_auth->canDo('access_backend')) {
                $this->editDashboard['hasAccess'] = $this->ci_auth->canDo('view_users') ? true : $this->editDashboard['hasAccess'];
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

        if( ! function_exists('enqueue_html_stylesheets') ){
			require_once FCPATH . 'app/helpers/campaigns-io/functions_helper.php';
		}
    }    

    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
    /* ++++++++++++++++++++++++++++++++++++++++ PAGES ++++++++++++++++++++++++++++++++++++++++ */
    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

    public function index() { redirect( site_url('/auth/home') ); exit; }

    public function viewsite(){

        $site_id = (int) $this->uri->segment(4);

        // TODO: Remove endpoint.
        redirect( base_url( 'domains/' . $site_id . '/wordpress/login' ) );
        exit;

        $this->validate_view_site();        
        $this->validate_view_site_author($site_id);
        
        $site = array( 
            'id' => $site_id,
            'data' => get_site_by_id( $site_id, $this->ci_auth->ci ),
        );

        $is_authorized = is_client_authorized( $site['id'], $site['data'] );

        if( 'ok' === $is_authorized ){
            $wp_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
            $wp_access->login( $site );
        }
        else{
            $wp_auth_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
            if( 'refresh' === $is_authorized && ! empty( $site['data']->refresh_token ) ){
                $wp_auth_access->refresh_tokens( $site, 'login' );
            }
            else{
                $wp_auth_access->request_tokens( 'login' );
            }
        }
    }

    public function viewsite_invalid_access(){

        $package = isset( $_GET['package'] ) && '' !== trim($_GET['package']) ? $_GET['package'] : null;
        $signature = isset( $_GET['signature'] ) && '' !== trim($_GET['signature']) ? $_GET['signature'] : null;

        if( null === $package ||  null === $signature ){
            die('Invalid request-response');
        }

        $data = validate_api_response( array( 'package' => $package, 'signature' => $signature ) );

        $site_id = isset($data['site_id']) && $data['site_id'] ? $data['site_id'] : null;
        $request_url = isset( $data['request_url'] ) && $data['request_url'] ? $data['request_url'] : null;
        $request_action = isset( $data['request_action'] ) && $data['request_action'] ? $data['request_action'] : null;
        $error = isset($data['error']) && $data['error'] ? $data['error'] : null;

        if( null === $site_id || null === $request_url || null === $error ){
            die('Invalid request-response arguments');
        }

        switch( $error ){
            case 'invalid-request': die('Invalid request'); exit;
            case 'invalid-user':    die('Invalid username'); exit;
            case 'invalid-access':  // When user hasn't valid tokens or website hasn't saved any token value before ( eg. after plugin (re-) activation ).
                $this->validate_view_site();                
                $this->validate_view_site_author( $site_id );

                $site = array( 
                    'id' => $site_id,
                    'data' => get_site_by_id( $site_id, $this->ci_auth->ci ),
                );

                $wp_auth_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
                $wp_auth_access->request_tokens( $request_action );
                exit;
        }

        die('Invalid error type');
    }

    public function updates(){

        $site_id = (int) $this->uri->segment(4);

        if( isset( $_GET['user_id'] ) ){
            $user_id = (int) $_GET['user_id'];
            $domain_details = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $site_id );
            if( ! $domain_details ){
                die();
            }
        }
        else{
            $this->validate_view_site();
            $this->validate_view_site_author( $site_id );
        }

        $valid_updates_types = array('all', 'core', 'themes', 'plugins');

        $updates_type = $this->uri->segment(5);

        if( 'count' === $updates_type ){
            $updates_type = 'all';
            $count_only = true;
        }
        else{
            $count_only = 'count' === $this->uri->segment(6);   
        }

        if( ! in_array( $updates_type, $valid_updates_types, true ) ){
            $updates_type = 'all';
        }

        $site = array( 
            'id' => $site_id,
            'data' => get_site_by_id( $site_id, $this->ci_auth->ci ),
        );

        $wp_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
        $wp_access->updates( $site, $updates_type, $count_only );

        // var_dump( $site_id );
        // var_dump( $updates_type );
        // var_dump( $count_only );

        // $siteId = $this->uri->segment(4);

        die();
    }

    public function update_now(){

        $site_id = (int) $this->uri->segment(4);

        $request = array();

        if( isset( $_POST['plugin'] ) ){
            $request['plugins'] = $_POST['plugin'];
        }

        if( isset( $_POST['theme'] ) ){
            $request['themes'] = $_POST['theme'];
        }

        if( isset( $_POST['core'] ) ){
            $request['core'] = (int) $_POST['core'] ? 1 : 0;
        }

        $site = array(
            'id' => $site_id,
            'data' => get_site_by_id( $site_id, $this->ci_auth->ci ),
        );

        $wp_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );

        $results = $wp_access->update_now( $site, $request );

        print_r( json_encode( $results ) );
        die();
    }

    /*
     * Get sites available upgrades.
     */
    /*public function upgrades() {

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
    }*/

    /*
     * Update sites WP core, themes, plugins.
     */
    /*public function update() {
        
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
    }*/

    /*public function summarycounts() {

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
    }*/

    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
    /* ++++++++++++++++++++++++++++++++++++ PAGES HELPERS ++++++++++++++++++++++++++++++++++++ */
    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

    private function validate_view_site(){
        if ( ! $this->ci_auth->is_logged_in() ) {
            redirect( site_url('auth/login') );
        }
        elseif ( $this->ci_auth->is_logged_in(false) ) {
            redirect('/auth/sendactivation/');  // Logged in, not activated.
        }
    }

    private function validate_view_site_author( $site_id = 0 ){

        if( 0 >= $site_id ){
            $this->editDashboard['deniedMsg'] = "Invalid site ID.";
            $this->editDashboard['deniedRedirect'] = site_url('auth/profile/');
            $this->session->set_flashdata('errors', $this->editDashboard['deniedMsg']);
            redirect( $this->editDashboard['deniedRedirect'] );
        }

        if( ! $this->editDashboard['hasAccess'] ){
        
            $user_id = $this->ci_auth->get_user_id();
            $is_user_site = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $site_id );
        
            if( $is_user_site ){
                $this->editDashboard['hasAccess'] = true;
            }
            else{
                $this->editDashboard['deniedMsg'] = "You don't have permission to access this part of the site.";
                $this->editDashboard['deniedRedirect'] = site_url('auth/profile/');
                $this->session->set_flashdata('errors', $this->editDashboard['deniedMsg']);
                redirect( $this->editDashboard['deniedRedirect'] );
            }        
        }
    }
}
