<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Backups extends CI_Controller {

    private $domains_db_table = null;
    private $api_pre = '/wp-json/wp-controller';

    public function __construct() {
        parent::__construct();

        if( ! $this->ci_auth->is_logged_in() ) {
            /* not logged in */
            redirect( site_url('auth/login') );
        }
        elseif( $this->ci_auth->is_logged_in(false) ) {
            /* logged in, not activated */
            redirect('/auth/sendactivation/');
        }
        else {
            /* logged in */
            $this->domains_db_table = $this->ci_auth->ci->config->item('db_table_prefix') . 'domains';

            $this->load->helper('cookie');
            $this->load->helper(array('form','url'));
            $this->load->helper('security');
            $this->load->library(array('session', 'form_validation'));

            $this->load->helper('campaigns-io/functions');
        }
    }

    public function index() {
        $this->load->view(get_template_directory() . '/backup_new');
    }

    /*
     * ==================== Pages ====================
     */
    
    public function settings() {
        
        $domain_id = $this->uri->segment(4);
        $domain_id = $domain_id ? (int) $domain_id : 0;

        if( 0 < $domain_id ){

            $domain_data = get_site_by_id( $domain_id, $this->ci_auth->ci );

            if ( ! empty( $domain_data ) ) {

                if( isset( $_POST['submit_backup_settings'] ) ){
                    $this->save_backup_settings( $domain_data );
                }
                else{
                    $page_data = array(
                        'domain_data' => $domain_data,
                    );

                    $this->load->view(get_template_directory() . 'backups-settings', $page_data );
                }
            }
        }
    }

    public function tasks() {

        $task_page_type = $this->uri->segment(4);

        if( ! in_array( strtolower( $task_page_type ), array('create', 'edit', 'list', 'delete') ) ){
            exit('Invalid backup task page');
        }

        $user_data = $this->session->get_userdata();

        if( 'delete' === $task_page_type){

            $domain_id = $this->uri->segment(5);
            $task_id = $this->uri->segment(6);

            if( $domain_id && $task_id ){

                $isAuthorizedClient = is_client_authorized( $domain_id );

                if( 'ok' === $isAuthorizedClient ){

                    $api_post = '/backup/task/remove';

                    $siteData = get_site_by_id( $domain_id, $this->ci_auth->ci );
                    $siteDomain = add_url_scheme( remove_last_slash( $siteData->domain_name ) );

                    $apiURL = $siteDomain . $this->api_pre . $api_post . '?access_token=' . $siteData->access_token;

                    $request_options = array(
                        'tid' => $task_id,
                    );

                    $exe_ret = execute_curl_post( $apiURL, $request_options );
                    $result = $exe_ret['result'];
                    $httpStatus = $exe_ret['status'];
                    
                    if( 404 === $httpStatus && 'rest_no_route' === $result['code'] ){
                    
                    }
                    elseif( 403 === $httpStatus && 'refresh_access_tokens' === $result['code'] ){
                        $updateData = array(
                            'access_token'      => '',
                            'refresh_token'     => '',
                            'access_expires'    => '',
                        );                            
                        update_auth_data( $updateData, $domain_id, $this->ci_auth->ci );
                        set_cookie( 'redirect_after_authorize', current_url(), 120 );
                        refresh_access_token( $domain_id, $siteData, current_url(), $this->ci_auth->ci );
                    }
                    elseif( 200 === $httpStatus && 'success' === $result['status'] ){

                        if( delete_backup_task( $task_id ) ){
                            $this->session->set_flashdata('backup_tasks_form_msg','<div class="alert alert-success backups-page-msg" role="alert"><i class="fa fa-check-circle msg-icon"></i>Backup task deleted successfully! <i class="fa fa-close close-alert"></i></div>');
                        }
                        else{
                            $this->session->set_flashdata('backup_tasks_form_msg','<div class="alert alert-error backups-page-msg" role="alert"><i class="fa fa-times-circle msg-icon"></i>An error occured on backup task removal. Please try again later. <i class="fa fa-close close-alert"></i></div>');
                        }
                        
                        redirect( site_url( '/auth/wordpress' ) );
                    }
                }
                else{

                    set_cookie( 'redirect_after_authorize', current_url(), 120 );

                    if( 'refresh' === $isAuthorizedClient ){
                        refresh_access_token( $domain_id, $siteData, current_url(), $this->ci_auth->ci );
                    }
                    else{
                        authorize_client( $domain_id, $this->ci_auth->ci );
                    }
                }
            }
            else{
                die("Missing task info");
            }

        }
        else if( 'list' !== $task_page_type ){

            $task_id = 'create' === $task_page_type ? 0 : $this->uri->segment(5);

            $page_data = array(
                'task_id' => $task_id,
                'task_data' => array(),
                'user_domains' => get_user_domains( $user_data['user_id'], $this->db )
            );
            
            if( isset( $_POST['submit_backup_task'] ) ){
                $this->save_backup_task_settings( $page_data );
            }
            else{

                // Redirect to create/edit backup task page.

                $this->load->view( get_template_directory() . 'backups-tasks-edits', $page_data );
            }
        }
        else{

            // Display saved backup tasks, in json format.
            
            $user_domains = get_user_domains( $user_data['user_id'], $this->db );

            $return = array();
            $cnt = 0;

            if( ! empty( $user_domains ) ){

                $api_post = '/backup/task/list';

                foreach ($user_domains as $key => $val) {
                    
                    $siteData = get_site_by_id( $val->id, $this->ci_auth->ci );
                    $siteDomain = add_url_scheme( remove_last_slash( $val->domain_name ) );

                    $isAuthorizedClient = is_client_authorized( $val->id, $val );

                    if( 'ok' === $isAuthorizedClient ){

                        $apiURL = $siteDomain . $this->api_pre . $api_post . '?access_token=' . $siteData->access_token;
                        
                        $exe_ret = execute_curl_get( $apiURL );
                        $result = $exe_ret['result'];
                        $httpStatus = $exe_ret['status'];

                        if( 404 === $httpStatus && 'rest_no_route' === $result['code'] ){
                        }
                        elseif( 403 === $httpStatus && 'refresh_access_tokens' === $result['code'] ){
                            $updateData = array(
                                'access_token'      => '',
                                'refresh_token'     => '',
                                'access_expires'    => '',
                            );                            
                            update_auth_data( $updateData, $val->id, $this->ci_auth->ci );
                            set_cookie( 'redirect_after_authorize', current_url(), 120 );
                            refresh_access_token( $val->id, $siteData, current_url(), $this->ci_auth->ci );
                        }
                        elseif( 200 === $httpStatus && 'success' === $result['status'] && 0 === $result['error'] ){
                            $return[$cnt] = $result;
                            $return[$cnt]['url'] = $val->domain_name;
                            $return[$cnt]['admin_url'] = $val->adminURL;
                            $cnt++;
                        }
                    }
                    else{

                        set_cookie( 'redirect_after_authorize', current_url(), 120 );

                        if( 'refresh' === $isAuthorizedClient ){
                            refresh_access_token( $val->id, $siteData, current_url(), $this->ci_auth->ci );
                        }
                        else{
                            authorize_client( $val->id, $this->ci_auth->ci );
                        }
                    }
                }
            }

            header('Content-Type: application/json');
            print_r( json_encode( $return ) );
        }        
    }

    /*
     * ==================== API requests ====================
     */
    
    public function completed() {

        $user_data = $this->session->get_userdata();
        $user_domains = get_user_domains( $user_data['user_id'], $this->db );

        $domain_id = (int) $this->uri->segment(4);
        if( 0 < $domain_id ){
            $domain_id_data = array();
            foreach( $user_domains as $k => $v ){
                if( empty( $domain_id_data ) && (int) $v->id === $domain_id ){
                    $domain_id_data = $v;
                    break;
                }
            }   
            if( !empty( $domain_id_data ) ){
                $user_domains = array( $domain_id_data );
            }
        }

        $return = array();
        $cnt = 0;

        if( ! empty( $user_domains ) ){

            $api_post = '/backup/list';

            foreach ($user_domains as $key => $val) {
                
                $siteData = get_site_by_id( $val->id, $this->ci_auth->ci );
                $siteDomain = add_url_scheme( remove_last_slash( $val->domain_name ) );

                $isAuthorizedClient = is_client_authorized( $val->id, $val );

                if( 'ok' === $isAuthorizedClient ){

                    $apiURL = $siteDomain . $this->api_pre . $api_post . '?access_token=' . $siteData->access_token;

                    $exe_ret = execute_curl_get( $apiURL );
                    $result = $exe_ret['result'];
                    $httpStatus = $exe_ret['status'];

                    if( 404 === $httpStatus && 'rest_no_route' === $result['code'] ){

                    }
                    elseif( 403 === $httpStatus && 'refresh_access_tokens' === $result['code'] ){
                        $updateData = array(
                            'access_token'      => '',
                            'refresh_token'     => '',
                            'access_expires'    => '',
                        );                            
                        update_auth_data( $updateData, $val->id, $this->ci_auth->ci );
                        set_cookie( 'redirect_after_authorize', current_url(), 120 );
                        refresh_access_token( $val->id, $siteData, current_url(), $this->ci_auth->ci );
                    }
                    elseif( 200 === $httpStatus && 'success' === $result['status'] && 0 === $result['error'] ){

                        if( !empty ( $result['data'] ) ){
                            foreach ( $result['data'] as $aa => $bb ) {
                                $return[$cnt] = $bb;
                                $return[$cnt]['url'] = $val->domain_name;
                                $return[$cnt]['admin_url'] = $val->adminURL;
                                $return[$cnt]['domain_id'] = $val->id;
                                $cnt++;
                            }
                        }
                    }
                }
                else{
                    set_cookie( 'redirect_after_authorize', current_url(), 120 );

                    if( 'refresh' === $isAuthorizedClient ){
                        refresh_access_token( $val->id, $siteData, current_url(), $this->ci_auth->ci );
                    }
                    else{
                        authorize_client( $val->id, $this->ci_auth->ci );
                    }
                }

            }
        }

        if( ! empty( $return ) ){
            uasort( $return, array( $this, 'sort_backups_by_creation_date' ) ); 
            $return = array_values( $return );
        }

        header('Content-Type: application/json');
        print_r( json_encode( $return ) );
    }

    public function execute() {

        $return = array();

        $domain_id = (int) $this->uri->segment(4);

        if( 0 < $domain_id ){

            $domain_data = get_site_by_id( $domain_id, $this->ci_auth->ci );
            
            if ( ! empty( $domain_data ) ) {

                $api_post = '/backup/exe';
                
                $isAuthorizedClient = is_client_authorized( $domain_data->id, $domain_data );

                $siteDomain = add_url_scheme( remove_last_slash( $domain_data->domain_name ) );

                $isAuthorizedClient = is_client_authorized( $domain_data->id, $domain_data );

                if( 'ok' === $isAuthorizedClient ){

                    $apiURL = $siteDomain . $this->api_pre . $api_post;

                    $domain_backup_settings = get_backup_settings( $domain_id );

                    if( ! empty( $domain_backup_settings ) ){
                        
                        $domain_backup_settings = $domain_backup_settings[0];

                        $request_options = array(
                            'btype' =>  $domain_backup_settings->backup_type,
                            'exclude_folders' =>  $domain_backup_settings->exclude_folders,
                            'exclude_files' =>  $domain_backup_settings->exclude_files,
                        );
                    }
                    else{
                        $request_options = array(
                            'btype' =>  'db-only'
                        );
                    }

                    $exe_ret = execute_curl_post( $apiURL, $request_options );
                    $result = $exe_ret['result'];
                    $httpStatus = $exe_ret['status'];

                    if( 404 === $httpStatus && isset( $result['code'] ) && 'rest_no_route' === $result['code'] ){
                        $return = array( 'error' => 1, 'message' => 'N/A', 'code' => 'rest-no-route' );
                    }
                    elseif( 403 === $httpStatus && 'refresh_access_tokens' === $result['code'] ){
                        $updateData = array(
                            'access_token'      => '',
                            'refresh_token'     => '',
                            'access_expires'    => '',
                        );
                            
                        update_auth_data( $updateData, $domain_data->id, $this->ci_auth->ci );
                        set_cookie( 'redirect_after_authorize', current_url(), 120 );
                        refresh_access_token( $domain_data->id, $domain_data, current_url(), $this->ci_auth->ci );
                    }
                        elseif( 200 === $httpStatus && isset( $result['status'] ) && 'success' === $result['status'] ){
                        $return = $result;
                        $return['url'] = $domain_data->domain_name;
                        $return['admin_url'] = $domain_data->adminURL;
                    }
                }
                else{

                    set_cookie( 'redirect_after_authorize', current_url(), 120 );

                    if( 'refresh' === $isAuthorizedClient ){
                        refresh_access_token( $domain_data->id, $domain_data, current_url(), $this->ci_auth->ci );
                    }
                    else{
                        authorize_client( $domain_data->id, $this->ci_auth->ci );
                    }
                }
            }
            else{
                $return = array( 'error' => 1, 'message' => 'N/A', 'code' => 'invalid-domain' );
            }
        }

        header('Content-Type: application/json');
        print_r( json_encode( $return ) );
    }

    public function restore() {

        $return = array();

        $domain_id = $this->uri->segment(4);
        $domain_id = (int) $domain_id;

        $backup_id = $this->uri->segment(5);
        $backup_id = $backup_id;

        if( 0 < $domain_id ){

            if( 0 < $backup_id ){

                $api_post = '/backup/restore';
                $domain_data = get_site_by_id( $domain_id, $this->ci_auth->ci );
                
                $isAuthorizedClient = is_client_authorized( $domain_data->id, $domain_data );

                if( 'ok' === $isAuthorizedClient ){

                    $siteDomain = add_url_scheme( remove_last_slash( $domain_data->domain_name ) );
                    
                    $apiURL = $siteDomain . $this->api_pre . $api_post;

                    $request_options = array(
                        'bid' =>  $backup_id
                    );

                    $exe_ret = execute_curl_post( $apiURL, $request_options );
                    $result = $exe_ret['result'];
                    $httpStatus = $exe_ret['status'];

                    if( 404 === $httpStatus && 'rest_no_route' === $result['code'] ){
                        $return = array( 'error' => 1, 'message' => 'N/A', 'code' => 'rest-no-route' );
                    }
                    elseif( 403 === $httpStatus && 'refresh_access_tokens' === $result['code'] ){
                        $updateData = array(
                            'access_token'      => '',
                            'refresh_token'     => '',
                            'access_expires'    => '',
                        );
                            
                        update_auth_data( $updateData, $domain_data->id, $this->ci_auth->ci );
                        set_cookie( 'redirect_after_authorize', current_url(), 120 );
                        refresh_access_token( $domain_data->id, $domain_data, current_url(), $this->ci_auth->ci );
                    }
                    elseif( 200 === $httpStatus && 'success' === $result['status'] ){
                        $return = $result;
                        $return['url'] = $domain_data->domain_name;
                        $return['admin_url'] = $domain_data->adminURL;
                    }
                }
                else{

                    set_cookie( 'redirect_after_authorize', current_url(), 120 );

                    if( 'refresh' === $isAuthorizedClient ){
                        refresh_access_token( $domain_data->id, $domain_data, current_url(), $this->ci_auth->ci );
                    }
                    else{
                        authorize_client( $domain_data->id, $this->ci_auth->ci );
                    }
                }

            }
            else{
                $return = array( 'error' => 1, 'message' => 'N/A', 'code' => 'invalid-backup' );
            }
        }
        else{
            $return = array( 'error' => 1, 'message' => 'N/A', 'code' => 'invalid-domain' );
        }

        header('Content-Type: application/json');
        print_r( json_encode( $return ) );
    }

    public function delete() {

        $return = array();

        $domain_id = $this->uri->segment(4);
        $domain_id = (int) $domain_id;
        
        $backup_id = $this->uri->segment(5);
        $backup_id = $backup_id;

        if( 0 < $domain_id ){

            if( 0 < $backup_id ){

                $api_post = '/backup/remove';

                $domain_data = get_site_by_id( $domain_id, $this->ci_auth->ci );

                $isAuthorizedClient = is_client_authorized( $domain_data->id, $domain_data );
                
                if( 'ok' === $isAuthorizedClient ){

                    $siteDomain = add_url_scheme( remove_last_slash( $domain_data->domain_name ) );

                    $apiURL = $siteDomain . $this->api_pre . $api_post;

                    $request_options = array(
                        'bid' =>  $backup_id
                    );

                    $exe_ret = execute_curl_post( $apiURL, $request_options );
                    $result = $exe_ret['result'];
                    $httpStatus = $exe_ret['status'];

                    if( 404 === $httpStatus && 'rest_no_route' === $result['code'] ){
                        $return = array( 'error' => 1, 'message' => 'N/A', 'code' => 'rest-no-route' );
                    }
                    elseif( 403 === $httpStatus && 'refresh_access_tokens' === $result['code'] ){
                        $updateData = array(
                            'access_token'      => '',
                            'refresh_token'     => '',
                            'access_expires'    => '',
                        );
                            
                        update_auth_data( $updateData, $domain_data->id, $this->ci_auth->ci );
                        set_cookie( 'redirect_after_authorize', current_url(), 120 );
                        refresh_access_token( $domain_data->id, $domain_data, current_url(), $this->ci_auth->ci );
                    }
                    elseif( 200 === $httpStatus && 'success' === $result['status'] ){
                        $return = $result;
                        $return['url'] = $domain_data->domain_name;
                        $return['admin_url'] = $domain_data->adminURL;
                    }
                }
                else{

                    set_cookie( 'redirect_after_authorize', current_url(), 120 );

                    if( 'refresh' === $isAuthorizedClient ){
                        refresh_access_token( $domain_data->id, $domain_data, current_url(), $this->ci_auth->ci );
                    }
                    else{
                        authorize_client( $domain_data->id, $this->ci_auth->ci );
                    }
                }

            }
            else{
                $return = array( 'error' => 1, 'message' => 'N/A', 'code' => 'invalid-backup' );
            }
        }
        else{
            $return = array( 'error' => 1, 'message' => 'N/A', 'code' => 'invalid-domain' );
        }

        header('Content-Type: application/json');
        print_r( json_encode( $return ) );
    }
     
    /*
     * ==================== Helpers ====================
     */

    private function sort_backups_by_creation_date( $a, $b, $asc = false ){
        return $asc ? ( strtotime( $a['created'] ) - strtotime( $b['created'] ) ) : ( strtotime( $b['created'] ) - strtotime( $a['created'] ) );
    }

    private function edit_site_backup_task( $create = false, $domain_id = false, $task_id = false, $task_title = false, $task_backup_type = false, $task_schedule = false, $exclude_folders = '', $exclude_files = ''  ) {

        if( $domain_id && $task_title && $task_backup_type && $task_schedule ){
            
            $api_pre = '/wp-json/wp-controller';

            $request_options = array(
                'task_id' => $task_id,
                'name' =>  $task_title,
                'type' =>  $task_backup_type,
                'schedule' =>  $task_schedule,
                'exclude_folders' => $exclude_folders,
                'exclude_files' => $exclude_files,
            );

            if( $create ){
                // CREATE TASK.
                $api_post = '/backup/task/create';
            }
            else{
                // EDIT TASK.
                $api_post = '/backup/task/edit';
            }

            $siteData = get_site_by_id( $domain_id, $this->ci_auth->ci );
            $siteDomain = add_url_scheme( remove_last_slash( $siteData->domain_name ) );
            $isAuthorizedClient = is_client_authorized( $domain_id, $siteData );

            if( 'ok' === $isAuthorizedClient ){

                $apiURL = $siteDomain . $api_pre . $api_post . '?access_token=' . $siteData->access_token;

                $exe_ret = execute_curl_post( $apiURL, $request_options );
                $result = $exe_ret['result'];
                $httpStatus = $exe_ret['status'];

                if( 404 === $httpStatus && 'rest_no_route' === $result['code'] ){
                
                }
                elseif( 403 === $httpStatus && 'refresh_access_tokens' === $result['code'] ){
                    $updateData = array(
                        'access_token'      => '',
                        'refresh_token'     => '',
                        'access_expires'    => '',
                    );                            
                    update_auth_data( $updateData, $domain_id, $this->ci_auth->ci );
                    set_cookie( 'redirect_after_authorize', current_url(), 120 );
                    refresh_access_token( $domain_id, $siteData, current_url(), $this->ci_auth->ci );
                }
                elseif( 200 === $httpStatus && 'success' === $result['status'] && 0 === $result['error'] ){
                    redirect( site_url( '/auth/wordpress' ) );
                }
            }
            else{
                
                set_cookie( 'redirect_after_authorize', current_url(), 120 );

                if( 'refresh' === $isAuthorizedClient ){
                    refresh_access_token( $domain_id, $siteData, current_url(), $this->ci_auth->ci );
                }
                else{
                    authorize_client( $domain_id, $this->ci_auth->ci );
                }
            }
        }
        else{
            die('Missing task data.');
        }
    }

    private function save_backup_settings( $domain_data = array() ) { 
        if( ! empty( $domain_data ) ){

            $this->form_validation->set_rules('backupType', 'Type', 'callback_validate_backup_type');
            $this->form_validation->set_rules('backupExcludeFolders', 'Exclude Folders', 'trim|xss_clean');
            $this->form_validation->set_rules('backupExcludeFiles', 'Exclude Files', 'trim|xss_clean');

            if( false === $this->form_validation->run() ){
                $this->load->view(get_template_directory() . 'backups-settings', array( 'domain_data' => $domain_data ) );
            }
            else{

                $data = array(
                    'domain_id' => (int) $domain_data->id,
                    'backup_type' => $this->input->post( 'backupType' ),
                    'exclude_folders' => $this->input->post( 'backupExcludeFolders' ),
                    'exclude_files' => $this->input->post( 'backupExcludeFiles' ),
                    'modified'  => date('Y-m-d H:i:s'),
                );

                $exists = $this->db->where_in(`domain_id`, (int) $domain_data->id)->get( 'backups_settings' )->result();

                if( $exists ){
                    $this->db->where('domain_id', (int) $domain_data->id );
                    $exe = $this->db->update( 'backups_settings', $data );
                }
                else{
                    $exe = $this->db->insert( 'backups_settings', $data );
                }

                if( $exe ) {
                    $this->session->set_flashdata('backup_settings_form_msg','<div class="alert alert-success backups-page-msg" role="alert"><i class="fa fa-check-circle msg-icon"></i>Backup settings for domain "' . $domain_data->domain_name. '" saved successfully! <i class="fa fa-close close-alert"></i></div>');
                }
                else{
                    $this->session->set_flashdata('backup_settings_form_msg','<div class="alert alert-danger backups-page-msg" role="alert"><i class="fa fa-times-circle msg-icon"></i>An error occurred on saving backup settings. Please try again later. <i class="fa fa-close close-alert"></i></div>');
                }

                redirect( site_url( '/auth/wordpress' ) );
            }
        }
        else{

            $this->load->view(get_template_directory() . 'backups-settings', array( 'domain_data' => $domain_data ) );
        }
    }

    private function save_backup_task_settings( $page_data = array() ) {

        if( ! empty( $page_data ) ){

            $this->form_validation->set_rules('backupTaskDomain', 'Domain', 'trim|required|xss_clean|callback_validate_task_domain');
            $this->form_validation->set_rules('backupTaskFrequency', 'Execute once a', 'trim|required|callback_validate_task_schedule');
            $this->form_validation->set_rules('backupTaskTitle', 'Task title', 'trim|required|xss_clean');
            $this->form_validation->set_rules('backupType', 'Type', 'callback_validate_backup_type');
            $this->form_validation->set_rules('backupExcludeFolders', 'Exclude Folders', 'trim|xss_clean');
            $this->form_validation->set_rules('backupExcludeFiles', 'Exclude Files', 'trim|xss_clean');

            if( false === $this->form_validation->run() ){
                
                $this->load->view( get_template_directory() . 'backups-tasks-edits', $page_data );
            }
            else{

                $task_id = $page_data['task_id'];

                $data_to_save = date('Y-m-d H:i:s');

                $data = array(
                    'domain_id' => (int) $this->input->post( 'backupTaskDomain' ),
                    'title' => $this->input->post( 'backupTaskTitle' ),
                    'backup_type' => $this->input->post( 'backupType' ),
                    'exe_frequency' => $this->input->post( 'backupTaskFrequency' ),
                    'exclude_folders' => $this->input->post( 'backupExcludeFolders' ),
                    'exclude_files' => $this->input->post( 'backupExcludeFiles' ),
                    'modified'  => $data_to_save,
                );

                if( 0 === (int) $task_id ){
                    
                    // CREATE NEW TASK
                    
                    $data['created'] = $data_to_save;
                    $exe = $this->db->insert( 'backups_tasks', $data );
                    $task_id = $this->db->insert_id();
                    $create = true;
                }
                else{
                    
                    // UPDATE TASK
                    
                    $this->db->where('id', $task_id);
                    $exe = $this->db->update( 'backups_tasks', $data );
                    $create = false;
                }

                if( $exe ) {
                    $this->session->set_flashdata('backup_tasks_form_msg','<div class="alert alert-success backups-page-msg" role="alert"><i class="fa fa-check-circle msg-icon"></i>Backup task "' . $this->input->post( 'backupTaskTitle' ) . '" saved successfully! <i class="fa fa-close close-alert"></i></div>');

                    $this->edit_site_backup_task( $create, $data['domain_id'], $task_id, $data['title'], $data['backup_type'], $data['exe_frequency'], $data['exclude_folders'], $data['exclude_files'] );
                }
                else{

                    $this->session->set_flashdata('backup_tasks_form_msg','<div class="alert alert-danger backups-page-msg" role="alert"><i class="fa fa-times-circle msg-icon"></i>An error occurred on saving backup task. Please try again later. <i class="fa fa-close close-alert"></i></div>');

                    redirect( site_url( '/auth/wordpress' ) );
                }
            }
        }
        else{

            $this->load->view( get_template_directory() . 'backups-tasks-edits', $page_data );
        }
    }

    public function validate_task_domain( $domain ) {
        if( (int) $domain <= 0 ){
            $this->form_validation->set_message('validate_task_domain', "Select a domain for this task");
            return false;
        }

        return true;
    }

    public function validate_backup_type( $backupType ) {

        if( ! in_array( $backupType, array( 'db-only', 'files-db' ) ) ){
            $this->form_validation->set_message('validate_backup_type', "Select a valid backup type");
            return false;
        }

        return true;
    }

    public function validate_task_schedule( $value ) {

        if( ! in_array( $value, array( 'daily', 'monthly', 'weekly' ) ) ){
            $this->form_validation->set_message('validate_backup_type', "Select a valid value");
            return false;
        }

        return true;
    }

}