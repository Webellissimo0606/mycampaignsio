<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

use Automattic\WooCommerce\Client;  //call woocommerce api class

include 'CampaignIo_Pages.php';

class CampaignIo_Pages_Authorized extends CampaignIo_Pages {

    private $requests_cache;

	public function __construct() {

    	parent::__construct();

    	$this->validate_user_access();

    	$this->load_models();
    	$this->load_helpers();
    	$this->load_libraries();

    	$this->load_user_data();

        $this->requests_cache = new Cache_Domains_External_Requests( $this->db );

    	$this->page_data['domain'] = array( 'id' => 0 );
    }

	protected function validate_user_access(){
		if ( ! $this->ci_auth->is_logged_in() ) {
            redirect( base_url('auth/login') );
            exit;
        }
        else if( $this->ci_auth->is_logged_in( false ) ) {
            redirect( base_url( 'auth/sendactivation' ) );
            exit;
        }
	}

    protected function validate_user_domain_access( $domain_id, $user_id ){

        $user_query = "SELECT id FROM domains WHERE id=". $domain_id . " AND userid=" . $user_id . " LIMIT 1";
        $user_query = $this->db->query( $user_query );

        if( ! empty( $user_query->result() ) ){
            return true;
        }

        $assigned_user_query = "SELECT d.id FROM domains AS d JOIN user_domain AS ud ON ud.domain_id=d.id WHERE ud.user_id=" . $user_id . " AND d.id=". $domain_id . " LIMIT 1";
        $assigned_user_query = $this->db->query( $assigned_user_query );

        if( ! empty( $assigned_user_query->result() ) ){
            return true;
        }

        redirect( base_url( 'domains' ) );
        exit;
    }

    protected function validate_user_heatmap_access( $heatmap_id, $user_id ){

        $user_query = "SELECT id FROM heatmaps WHERE id=". $heatmap_id . " AND user_id=" . $user_id . " LIMIT 1";
        $user_query = $this->db->query( $user_query );

        if( ! empty( $user_query->result() ) ){
            return true;
        }

        redirect( base_url( 'domains' ) );
        exit;
    }

	protected function load_models(){
		parent::load_models();
		$this->load->model('auth/analyze_model');
		$this->load->model('auth/groups_model');
		$this->load->model('serp/domain_model');
	}

	protected function load_helpers(){
		parent::load_helpers();
        $this->load->helper( array('form', 'url') );
	}

    protected function load_libraries(){
		parent::load_libraries();
        $this->load->library('upmonitor');
        $this->load->library('form_validation');
		$this->load->library('Google_Auth', '', 'Google_Authenticate');
	}

	protected function load_user_data(){
		
		$user_session_data = $this->session->get_userdata();
    	$user_profile_data = user_profile_data( $this->db, $user_session_data['user_id'] );

        // Check if has parent user.
        $this->db->flush_cache();
        $this->db->select( "parent_id" );
        $this->db->from( "users as u" );
        $this->db->where( "id=", $user_session_data['user_id'] );
        $this->db->limit(1);

        $query = $this->db->get();
        $parentDetail = $query->row_array();
        if($parentDetail['parent_id'] != null) {
           $user_id = $parentDetail['parent_id']; 
        }
        
		$this->page_data['user'] = array(
    		'id' => (int) $user_session_data['user_id'],
            'parent_id' => user_parent_user_id( $this->db, $user_session_data['user_id'] ),
    		'email' => $user_profile_data->email,
    		'gravatar'=> gravatar_thumb( $user_profile_data->email, 112),
    		'domains' => array()
    	);

        $this->page_data['user']['fullname'] = format_user_fullname( $user_profile_data->first_name, $user_profile_data->last_name );
	}

    protected function user_domains_list($user_id){
        $this->db->flush_cache();
        $query = $this->db->query("SELECT d.*,ud.user_id as userid FROM domains d join user_domain ud on ud.domain_id=d.id WHERE ud.user_id=" . $user_id . "");
        return $query->result();
    }

    private function domain_form_data( $domain_id = 0, $user_id = 0 ){

        $data = array(
            'domain_name' => '',
            'monitor_website_uptime' => 0,
            'is_ecommerce' => 0,
            'page_header' => '',
            'page_body' => '',
            'page_footer' => '',
            'frequency' => 5,
            'groups' => array(),
            'subusers' => array(),
            'ga_account' => 0,
            'adminURL' => 'http://',
            'adminUsername' => '',
            'monitor_malware' => 0,
            'connect_to_google' => 0,
            'crawl_error_webmaster' => 0,
            'search_query_webmaster' => 0,
            'include_mobile_search' => 0,
            'keywords' => '',
            'engines' => '',
        );

        if( isset( $_SESSION['domain_form_error'] ) && isset( $_SESSION['domain_form_error']['post_data'] ) ){

            $post_data = $_SESSION['domain_form_error']['post_data'];

            foreach ($data as $key => $val) {
                if( isset( $post_data[$key] ) ){
                    switch( $key ){
                        case 'is_ecommerce':
                        case 'monitor_website_uptime':
                        case 'ga_account':
                        case 'monitor_malware':
                        case 'connect_to_google':
                        case 'crawl_error_webmaster':
                        case 'search_query_webmaster':
                        case 'include_mobile_search':
                            $data[$key] = (int) $post_data[$key];
                            break;
                        default:
                            $data[$key] = $post_data[$key];
                    }
                }
            }

            if( isset( $_SESSION['domain_form_error']['error_msg'] ) ){
                $data['submission_failed_html'] = $_SESSION['domain_form_error']['error_msg'];
            }

            unset( $_SESSION['domain_form_error'] );
        }
        else{

            $domain_id = (int) $domain_id;

            if( 0 < $domain_id ){

                $default_frequency = 5;
            
                $domain_details = $this->analyze_model->getDomain( $domain_id );
                $domain_details = $domain_details[0];            

                $data['domain_name'] = $domain_details->domain_name;
                $data['is_ecommerce'] = (int)  $domain_details->is_ecommerce;
                $data['groups'] = $this->groups_model->getGroupsByDomainId( $domain_id );
                $data['subusers'] = $this->analyze_model->getSubusersByDomainIdAndParentId( $domain_id, $user_id );
                $data['monitor_website_uptime'] = (int)  $domain_details->monitorUptime;
                $data['ga_account'] = (int) $domain_details->ga_account;
                $data['adminURL'] = $domain_details->adminURL;
                $data['adminUsername'] = $domain_details->adminUsername;
                $data['monitor_malware'] = (int) $domain_details->monitorMalware;
                $data['connect_to_google'] = (int) $domain_details->connectToGoogle;
                $data['crawl_error_webmaster'] = (int) $domain_details->webmaster;
                $data['search_query_webmaster'] = (int) $domain_details->search_analytics;
                $data['include_mobile_search'] = (int) $domain_details->mobile_search;

                $uptime = $this->analyze_model->getUptimeByDomainId( $domain_id );
                $uptime_keyword = isset( $uptime['keyword'] ) && ! empty( $uptime['keyword'] ) ? json_decode( $uptime['keyword'] , true ) : array();

                $data['page_header'] = isset( $uptime_keyword['header'] ) ? htmlspecialchars( $uptime_keyword['header'] ) : $data['page_header'];
                $data['page_body'] = isset( $uptime_keyword['body'] ) ? htmlspecialchars( $uptime_keyword['body'] ) : $data['page_body'];
                $data['page_footer'] = isset( $uptime_keyword['footer'] ) ? htmlspecialchars( $uptime_keyword['footer'] ) : $data['page_footer'];
                $data['frequency'] = isset( $uptime_keyword['frequency'] ) ? (int) $uptime_keyword['frequency'] : $data['frequency'];

                $keywords = $this->analyze_model->getkeywordByDomainId( $domain_id );
                if( ! empty( $keywords ) ){
                    foreach ( $keywords as $key ) {
                        $data['keywords'] .= $key['name'] . "\r\n";
                    }
                }

                $domain_search_engines = $this->analyze_model->getSearchEngineByDomainId( $domain_id );
                foreach( $domain_search_engines as $key => $val ){
                    $data['engines'][] = $val['name'];
                }
            }
        }

        return $data;
    }

    private function domain_form_available_values( $user_id ){

        $return_values = array(
            'frequencies' => array(),
            'subusers_values' => array(),
            'group_values' => array(),
            'google_acounts' => array( 0 => 'Select' ),
            'search_engines' => array(
                'en-us' => 'google.com',
                'en-uk' => 'google.co.uk',
                'en-ca' => 'google.co.ca',
                'en-au' => 'google.co.au'
            ),
        );

        $frequenciesInSeconds = array( 1, 5, 15,30, 60, 120, 360, 720, 1440 );
        $google_accounts = domain_stats_curl_request( base_url( 'analytics/analytics/getUserGAAccounts' ), array( 'userId' => $user_id ) );
        $google_accounts = $google_accounts ? $google_accounts : array();
        $user_groups = $this->groups_model->getGroupsByUserId( $user_id );
        $user_subusers = $this->analyze_model->getallsubuserbyuserId( $user_id );

        foreach ($frequenciesInSeconds as $freq) {
            if( 60 > $freq ){
                $return_values['frequencies'][$freq] = $freq . ' min';
            }
            else if( 60 === $freq ){
                $return_values['frequencies'][$freq] = ( $freq / 60 ) . ' hour';
            }
            else{
                $return_values['frequencies'][$freq] = ( $freq / 60 ) . ' hours';
            }
        }

        foreach( $google_accounts as $x => $ga ){
            foreach($ga as $k => $v){
                $return_values['google_acounts'][$k] = $v;
            }
        }

        foreach( $user_groups as $group ){
            $return_values['group_values'][ $group['id'] ] = $group['group_name'];
        }
        
        foreach( $user_subusers as $subuser ){
            $return_values['subusers_values'][ $subuser['id'] ] = $subuser['first_name'] . ' ' . $subuser['last_name'];
        }

        return $return_values;
    }

    private function set_form_validation_rules( $form_name ){
        switch($form_name){
            case 'domain-save':
                $form_config = array(
                    array(
                        'field' => 'domain_name',
                        'label' => 'Domain name',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'adminURL',
                        'label' => 'Admin URL',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'adminUsername',
                        'label' => 'Admin Username',
                        'rules' => 'required'
                    ), 
                );
                $this->form_validation->set_rules( $form_config );
                break;
        }
    }

    private function validate_form_data( $form_name, $form_data ){
        switch( $form_name ){
            case 'domain-save':

                $this->set_form_validation_rules( $form_name );

                if( false === $this->form_validation->run() ){

                    $error_msg_html = '<div class="msg msg-success">';
                    $error_msg_html .= validation_errors();
                    $error_msg_html .= '<button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>';
                    $error_msg_html .= '</div>';

                    $domain_id = isset( $form_data['domain_id'] ) ? (int) $form_data['domain_id'] : 0;

                    $_SESSION['domain_form_error'] = array(
                        'post_data' => $form_data,
                        'error_msg' => $error_msg_html,
                    );

                    if( 0 === $domain_id ){
                        redirect( base_url( 'domains/add' ) );
                    }
                    else{
                        redirect( base_url( 'domains/' . $domain_id . '/edit' ) );
                    }
                    exit;
                }

                break;
        }
    }

    /* =============== // PAGES =============== */

    public function home(){ // @note: Not implemented yet.
        $this->display_page('home');
    }

    public function profile(){ // @note: Not implemented yet.
    	$this->page_data['document_data']['title'] = 'Profile - ' . $this->page_data['document_data']['title'];
        $this->display_page('profile');
    }

    public function profile_edit(){ // @note: Not implemented yet.
    	$this->page_data['document_data']['title'] = 'Edit Profile - ' . $this->page_data['document_data']['title'];
        $this->display_page('profile-edit');
    }

    public function domains(){

        $this->page_data['user']['domains'] = $this->user_domains_list( $this->page_data['user']['id'] );

        foreach ($this->page_data['user']['domains'] as $key => $val) {
            $this->page_data['user']['domains'][$key]->keywordexist = $this->analyze_model->checkKeywordExistByDomainId( $val->id );
        }

    	$this->page_data['document_data']['title'] = 'Domains - ' . $this->page_data['document_data']['title'];
        $this->display_page('domains');
    }

    public function domains_save(){

        $p = $_POST;

        $user_id = $this->page_data['user']['id'];
        $domain_id = isset( $p['domain_id'] ) ? (int) $p['domain_id'] : 0;
        
        if( $domain_id ){ // Doesn't need when save new domains.
            $this->validate_user_domain_access( $domain_id, $user_id );
        }

        $this->validate_form_data( 'domain-save', $p );

        $s = array();   // Values to save.

        $s['domain_name'] = $p['domain_name'];
        $s['domain_name'] = ( false === strpos( $s['domain_name'], '://' ) ? 'http://' : '' ) . $s['domain_name'];

        $is_new_domain = 0 === $domain_id;

        if( $is_new_domain ){
            // Check if domain already exists.
            if( is_registered_domain( $this->db, $s['domain_name'] ) ){
                // TODO: Handle failed process.
                // echo json_encode( array( 'msg' => 'Sorry, the domain you are trying to add already exist', 'type' => 'error' ) );
				echo "Domain already exists.<br/>";
                echo '<a href="' . base_url('domains/add') . '" title="">Back</a>';
				die();
            }
        }
        
        /* Step 1 fields -------------------------------------------------------------------------------- */

        $default_frequency = 5;

        $s['monitor_website_uptime'] = isset( $p['monitor_website_uptime'] ) ? (int) $p['monitor_website_uptime'] : 0;
        $s['is_ecommerce'] = isset( $p['is_ecommerce'] ) ? (int) $p['is_ecommerce'] : 0;
        $s['page_header'] = isset ($p['page_header'] ) ? trim( $p['page_header'] ) : '';
        $s['page_body'] = isset ($p['page_body'] ) ? trim( $p['page_body'] ) : '';
        $s['page_footer'] = isset ($p['page_footer'] ) ? trim( $p['page_footer'] ) : '';
        $s['frequency'] = isset( $p['frequency'] ) ? (int) $p['frequency'] : $default_frequency;
        $s['subusers'] = isset( $p['subusers'] ) ? $p['subusers'] : array();
        $s['groups'] = isset( $p['groups'] ) ? $p['groups'] : array();


        /* Step 2 fields -------------------------------------------------------------------------------- */

        $s['adminURL'] = $p['adminURL'];
        $s['adminUsername'] = $p['adminUsername'];
        $s['connect_to_google'] = isset( $p['connect_to_google'] ) ? (int) $p['connect_to_google'] : 0;
        // Disable connect to google if user is unauthorized google client.
        // $s['connect_to_google'] = $s['connect_to_google'] && null === $this->analyze_model->getUserOAuthDetails( $user_id ) ? 0 : $s['connect_to_google'];
        
        $s['monitor_malware'] = isset( $p['monitor_malware'] ) ? (int) $p['monitor_malware'] : 0;
        $s['crawl_error_webmaster'] = isset( $p['crawl_error_webmaster'] ) ? (int) $p['crawl_error_webmaster'] : 0;
        $s['search_query_webmaster'] = isset( $p['search_query_webmaster'] ) ? (int) $p['search_query_webmaster'] : 0;
        $s['ga_account'] = isset( $p['ga_account'] ) ? (int) $p['ga_account'] : 0;

        /* Step 3 fields -------------------------------------------------------------------------------- */

        $s['engines'] = isset( $p['engines'] ) ? $p['engines'] : array();
        $s['keywords'] = isset ($p['keywords'] ) ? array_map('trim', preg_split( '/[\n\r]+/', $p['keywords'] ) ) : array();
        $s['include_mobile_search'] = isset( $p['include_mobile_search'] ) ? (int) $p['include_mobile_search'] : 0;

        $towns = array();  // NOTE: Doesn't exists any relative option/setting.;
        $domain_save_data = array(
            'userid' => $user_id,
            'domain_name' => $s['domain_name'],
            'monitorOnPageIssues' => 0,         // NOTE: Doesn't exists any relative option/setting.
            'mobile_search' => $s['include_mobile_search'],
            'local_search' => 0,                // NOTE: Doesn't exists any relative option/setting.
            'is_ecommerce' => $s['is_ecommerce'],
            'ga_account' => $s['ga_account'],
            'monitorMalware' => $s['monitor_malware'],
            'monitorUptime' => $s['monitor_website_uptime'],
            'adminURL' => $s['adminURL'],
            'adminUsername' => $s['adminUsername'],
            'webmaster' => $s['crawl_error_webmaster'],
            'search_analytics' => $s['search_query_webmaster'],
            'connectToGoogle' => $s['connect_to_google'],
        );

        if( $is_new_domain ){
            $domain_id = $this->analyze_model->update_domains( $domain_save_data, '' );
        }
        else{
            
            if( ! $domain_save_data['monitorUptime'] ){
                $domain_save_data['server_status'] = NULL;
            }
            
            $domain_id = $this->analyze_model->update_domains( $domain_save_data, $domain_id );
        }

        if( ! $domain_id ){
            $return['msg'] = 'Invalid domain ID';
            echo json_encode( $return );
            die();
        }

        
        /* Step 1 fields ================================================================================ */

        // Adding subusers to domain.
        $this->analyze_model->assignDomainToSubuser( $domain_id, $s['subusers'], $user_id );
        
        // Adding domain to groups.
        $this->groups_model->assignDomainToGroup( $domain_id, $s['groups'] );

        if ( $s['monitor_website_uptime'] ) {

            $uptime_keywords = array(
                'header' => $s['page_header'],
                'body' => $s['page_body'],
                'footer' => $s['page_footer'],
                'frequency' => $s['frequency'],
            );

            $new_uptime = $is_new_domain ? 1 : ! $this->analyze_model->getUptimeByDomainId( $domain_id );

            if ( $new_uptime ) {
                save_new_uptime( 
                    $this->db,     // Database object.
                    array( 'upmonitor' => $this->upmonitor ), // CI libaries.
                    array( 'analyze' => $this->analyze_model ), // CI models.
                    $user_id,
                    $domain_id,
                    $s['domain_name'],
                    $uptime_keywords
                );
            }
            else {
                save_edit_uptime( $this->db, $user_id, $domain_id, $uptime_keywords );
            }
        }
        else if( ! $is_new_domain ) {

            $parent_id = user_parent_user_id( $this->db, $user_id );

            // Stop domain.
            $this->domain_model->stopDomain( $this->analyze_model->getusersinfo( $parent_id ? $parent_id : $user_id ), $domain_id );
        }


        /* Step 3 fields ================================================================================ */

        if( ! $is_new_domain ){
            // Remove domain's old engines from DB.
            $this->db->where( "domain_id=", $domain_id );
            $this->db->delete( "search_engine" );
        }

        // Save domain's Search Engines.
        if( ! empty( $s['engines'] ) ){
            
            foreach ( $s['engines'] as $key => $engine ){
                
                // Save Search Engine.
                $this->analyze_model->insertEngine( array( 'name' => $engine, 'domain_id' => $domain_id, 'user_id' => $user_id ) );

                if( 1 === $s['include_mobile_search'] ){
                    // Save Search Engine for Mobiles.
                    $this->analyze_model->insertEngine( array( 'name' => $engine . '-mobile', 'domain_id' => $domain_id, 'user_id' => $user_id ) );
                }
            }
        }

        if( ! $is_new_domain ){
            // Remove domain's old keywords from DB.
            $old_keywords = $this->analyze_model->getkeywordByDomainId( $domain_id );
            if( ! empty( $old_keywords ) ){
                foreach($old_keywords as $k => $v){
                    $this->analyze_model->deleteKeyword( $domain_id, $v['name'], $user_id );
                }
            }
        }

        // Save domain's new keywords.
        if( ! empty( $s['keywords'] ) ){
            foreach ( $s['keywords'] as $keyword ){
                if( '' === $keyword ){ break; }
                // Save Keyword.
                $this->analyze_model->insertkeyword( array( 'name' => $keyword, 'domain_id' => $domain_id, 'user_id' => $user_id ) );
            }
        }


        /* Other fields ================================================================================ */

        if( ! $is_new_domain ){
            // Remove domain's old local search data from DB.
            $this->db->where( "domain_id", $domain_id );
            $this->db->delete( "domain_local_keyword_search" );
        }

        // Adding local search towns.
        if ( $domain_save_data['local_search'] && ! empty( $towns ) ) {
            foreach ( $towns as $town ) {
                $this->analyze_model->insertLocalSearchTown( $town, $domain_id, $user_id );
            }
        }

        /***********************************************************************************************************************************/
        /***********************************************************************************************************************************/
        /***********************************************************************************************************************************/
        /***********************************************************************************************************************************/
        /***********************************************************************************************************************************/

        // @note: Following lines probably don't need but keep them (at the moment) in order to avoid conflicts with previous implementation.

        $domains = $this->analyze_model->getDomain( $domain_id );

        if( ! empty( $domains) ){
            foreach ( $domains as $key => $domain ) {
                $domains[$key]->host = parse_url( $domain->domain_name, PHP_URL_HOST );
            }
        }

        $this->session->set_userdata( 
            array(
                "domainId" => $domains[0]->id,
                "monitorMalware" => $domains[0]->monitorMalware,
                "adminURL" => $domains[0]->adminURL,
                "adminUsername" => $domains[0]->adminUsername,
                "domainUrl" => $domains[0]->domain_name,
                "gaAccount" => $domains[0]->ga_account,
                "connectToGoogle" => $domains[0]->connectToGoogle,
                "monitorOnPageIssues" => $domains[0]->monitorOnPageIssues,
                "domainHost" => $domains[0]->host,
                "webmaster" => $domains[0]->webmaster,
                "search_analytics" => $domains[0]->search_analytics
            )
        );

        // echo json_encode(
        //     array(
        //         'error' => 0,
        //         'msg' => 'success',
        //         'domainURL' => $selectedDomain['domainUrl'],
        //         'domainHost' => $selectedDomain['domainHost'],
        //         'webmaster' => $selectedDomain['webmaster'],
        //         'search_analytics' => $selectedDomain['search_analytics'],
        //         'domainId' => $selectedDomain['domainId']
        //     )
        // );

        /***********************************************************************************************************************************/
        /***********************************************************************************************************************************/
        /***********************************************************************************************************************************/
        /***********************************************************************************************************************************/
        /***********************************************************************************************************************************/

        redirect( base_url( 'domains/' . $domain_id ) );
        exit;
    }

    public function domains_add(){

        $this->page_data['domain']['id'] = 0;
        $this->page_data['domain']['form_data'] = $this->domain_form_data(0, $this->page_data['user']['id']);
        
        // Available options values.
        $this->page_data['available'] = $this->domain_form_available_values( $this->page_data['user']['id'] );

        $this->page_data['google_client_id'] = $this->config->config['google_oauth']['client_id'];
        $this->page_data['google_oauth_redirect_uri'] = $this->config->config['google_oauth']['redirect_uri'];        

    	$this->page_data['document_data']['title'] = 'Add New Domain - ' . $this->page_data['document_data']['title'];
        $this->display_page('domains-add');
    }
    
    public function domains_assign(){ // @note: Not implemented yet.
        $this->page_data['document_data']['title'] = 'Assign Domains - ' . $this->page_data['document_data']['title'];
        $this->display_page('domains-assign');
    }

    public function single_domain(){
        
        $domain_id = (int) $this->uri->segment(2);
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );
        
        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $enabled_monitor_uptime = (int) $domain_data->monitorUptime;

        $domain_url = $domain_data->domain_name;

        /* // Disabled on PIWIK removal.
        $piwik_handler = new New_Piwik_Handler( $this->ci, $this->analyze_model );
        $piwik = $piwik_handler->getsearchengineclicks( $domain_id );
        unset( $piwik_handler );*/

        $gtmetrix_data = array(
            'loaded' => false,
            'metrix' => array(),
            'date' => ''
        );

        $gtmetrix = just_gtmetrix_data( $domain_id, $domain_url, $this->analyze_model, $this->ci_auth, $this->session, false );

        if( isset( $gtmetrix['status'] ) && $gtmetrix['status'] && 
            isset( $gtmetrix['metrix'] ) && ! empty( $gtmetrix['metrix'] ) &&
            isset( $gtmetrix['date'] ) && ! empty( $gtmetrix['date'] ) ){   // Ensure data structure.
            $gtmetrix_data = array(
                'loaded' => true,
                'metrix' => json_decode( $gtmetrix['metrix'], true ),
                'date' => $gtmetrix['date'],
            );
        }

        $week_clicks = array( 'status' => null, 'clicks' => null );
        $month_clicks = array( 'status' => null, 'clicks' => null );
        $half_month_traffic = array( 'status' => null, 'payload' => array() );
        $search_console = array(
            'average_ctr' => 'n/a',
            'clicks' => 'n/a',
            'impressions' => 'n/a'
        );

        $force_refresh_data = isset( $_GET['refresh'] ) ? true : false;

        $cached_statistics = $force_refresh_data ? array( 'value' => null ) : $this->requests_cache->get_value( $domain_id, 'statistics-overview', true );

        if( $cached_statistics['value'] ){

            if( (int) $domain_data->connectToGoogle ){
                $week_clicks = $cached_statistics['value']['week_clicks'];
                $half_month_traffic = $cached_statistics['value']['half_month_traffic'];
                $month_clicks = $cached_statistics['value']['month_clicks'];
                $search_console = $cached_statistics['value']['search_console'] ? $cached_statistics['value']['search_console'] : $search_console;
            }

            $wp_updates = $cached_statistics['value']['wp_updates'];
            $keywords_data = $cached_statistics['value']['keywords_data'];
            $domain_down_time = $cached_statistics['value']['domain_down_time'];
            $uptimestats_response = $enabled_monitor_uptime ? $cached_statistics['value']['uptimestats_response'] : array();

            $this->page_data['page_data_last_update'] = $cached_statistics['date'];
            $this->page_data['page_data_update_now_link'] = base_url('domains/' . $domain_id . '?refresh');
        }
        else if( ! $force_refresh_data ){

            // @note: If is not requested to refresh data, display the page without values ("n/a") and let user to click on button "CHECK NOW". Otherwise, the page could take long time to load without the user understand the reason of delay.

            if( (int) $domain_data->connectToGoogle ){
                $week_clicks = array( 'status'=> null );
                $half_month_traffic = array( 'status'=> null );
                $month_clicks = array( 'status'=> null );
            }

            $wp_updates = array();
            $wp_updates['core'] = 'n/a';
            $wp_updates['plugins'] = 'n/a';
            $wp_updates['themes'] = 'n/a';

            $keywords_data = array();
            $domain_down_time = array();
            $uptimestats_response = array();

            $this->page_data['page_data_last_update'] = 'N/A';
            $this->page_data['page_data_update_now_link'] = base_url('domains/' . $domain_id . '?refresh');
        }
        else{

            if( (int) $domain_data->connectToGoogle ){

                try{
                    $new_analytics_handler = new New_Analytics_Handler( $this->db, $this->config, $this->analyze_model );
                    $half_month_traffic = $new_analytics_handler->get_google_analytics_traffic_info( $user_id, $domain_id, 15 );
                    $week_clicks = $new_analytics_handler->get_visits_number( $user_id, $domain_id, 7 );
                    $month_clicks = $new_analytics_handler->get_visits_number( $user_id, $domain_id, 30 );
                    $search_console_data = $new_analytics_handler->get_search_console_data( $user_id, $domain_url, 30 );

                    $search_console = array(
                        'average_ctr' => round( $search_console_data['ctr'] / $search_console_data['count'], 2 ),
                        'clicks' => $search_console_data['clicks'],
                        'impressions' => $search_console_data['impressions']
                    );

                    unset( $new_analytics_handler );
                }
                catch(Exception $e){}
            }

            $wp_access = new WP_Request_Access( array( 'id' => $domain_id, 'data' => $domain_data ), $this->session, $this->ci_auth );

            $wp_updates = $wp_access->updates( array( 'id' => $domain_id, 'data' => $domain_data ), 'all', true, true);

            unset( $wp_access );

            $wp_updates['core'] = isset( $wp_updates['core'] ) && ! is_nan( $wp_updates['core'] ) ? $wp_updates['core'] : 'n/a';
            $wp_updates['plugins'] = isset( $wp_updates['plugins'] ) && ! is_nan( $wp_updates['plugins'] ) ? $wp_updates['plugins'] : 'n/a';
            $wp_updates['themes'] = isset( $wp_updates['themes'] ) && ! is_nan( $wp_updates['themes'] ) ? $wp_updates['themes'] : 'n/a';

            $keywords_data = just_get_keyword_report( $this->analyze_model, $user_id, $domain_id );

            $domain_down_time = $this->domain_model->getDownTimeByDomainId( $domain_id );

            $uptimestats_response = $enabled_monitor_uptime ? just_domain_uptime_stats( $domain_id, $this->analyze_model ) : array();

            $this->requests_cache->set_value( $domain_id, 'statistics-overview', array(
                'week_clicks' => $week_clicks,
                'month_clicks' => $month_clicks,
                'half_month_traffic' => $half_month_traffic,
                'search_console' => $search_console,
                'wp_updates' => $wp_updates,
                'keywords_data' => $keywords_data,
                'domain_down_time' => $domain_down_time,
                'uptimestats_response' => $uptimestats_response,
            ) );

            if( $force_refresh_data ){
                redirect( base_url('domains/' . $domain_id ) );
                exit;
            }

            $this->page_data['page_data_last_update'] = null;
            $this->page_data['page_data_update_now_link'] = null;
        }

        $this->page_data['domain']['gtmetrix'] = $gtmetrix_data;
        $this->page_data['domain']['gtmetrix_rescan_url'] = base_url( 'domains/' . $domain_id . '/gtmetrix-rescan' );

        $this->page_data['domain']['seo'] = array(
        	'keywords' => array(
        		'changes' => 
        			isset( $keywords_data['keyword_changes'] ) ? $keywords_data['keyword_changes']  : 
	        		array( 'positive' => 'n/a', 'negative' => 'n/a', 'nochange' => 'n/a' ),
	        	'position' =>
	        		isset( $keywords_data['position'] ) ? $keywords_data['position'] : 
	        		array( 'top5' => 'n/a', 'top10' => 'n/a', 'top20' => 'n/a', 'top50' => 'n/a' ),
        	),
            'week_clicks' => 'success' === $week_clicks['status'] && null !== $week_clicks['clicks'] ? $week_clicks['clicks'] : 'n/a',
        	'month_clicks' => 'success' === $month_clicks['status'] && null !== $month_clicks['clicks'] ? $month_clicks['clicks'] : 'n/a',
        );

        $this->page_data['domain']['search_console'] = $search_console;
        
        $this->page_data['domain']['id'] = $domain_id;
        $this->page_data['domain']['wp_updates'] = $wp_updates;
        $this->page_data['domain']['down_time'] = $domain_down_time;
        $this->page_data['domain']['down_time']['server_status'] = strtolower( $this->page_data['domain']['down_time']['server_status'] );

        $uptime_data = array( 'one_day' => null, 'seven_days' => null, 'thirty_days' => null );
		$uptime_performance = array();

		if( $uptimestats_response && ! empty( $uptimestats_response ) ){

			if( isset( $uptimestats_response['uptimedaystats'] ) && ! empty( $uptimestats_response['uptimedaystats'] ) ){
				foreach ($uptimestats_response['uptimedaystats'] as $key => $val) {
					$uptime_performance[] = array(
						'completed_on' => $val['completed_on'],
						'load_time' => $val['load_time'],
					);
				}
			}

			if( isset( $uptimestats_response['uptime1daypercentage'] ) ){
				$uptime_data['one_day'] = round( $uptimestats_response['uptime1daypercentage'], 1 );
			}

			if( isset( $uptimestats_response['uptime7daypercentage'] ) ){
				$uptime_data['seven_days'] = round( $uptimestats_response['uptime7daypercentage'], 1 );
			}

			if( isset( $uptimestats_response['uptime30daypercentage'] ) ){
				$uptime_data['thirty_days'] = round( $uptimestats_response['uptime30daypercentage'], 1 );
			}
		}

		$this->page_data['domain']['up_time'] = array(
        	'performance' => $uptime_performance,
        	'data' => $uptime_data,
        );

        $this->page_data['domain']['enabled_monitor_uptime'] = $enabled_monitor_uptime;

        $this->page_data['domain']['traffic'] = array(
        	'payload' => 'success' === $half_month_traffic['status'] && ! empty( $half_month_traffic['payload'] ) ? $half_month_traffic['payload'] : array(),
        );

        $this->page_data['domain']['url'] = array(
        	'site' => $domain_url,
        	'analytics' => base_url( 'domains/' . $domain_id . '/analytics' ),
            'wp_login' => base_url( 'domains/' . $domain_id . '/wordpress/login' ),
        	'wp_dashboard' => base_url( 'domains/' . $domain_id . '/wordpress' )
        );

        $this->page_data['user']['domains'] = $this->user_domains_list( $user_id );

        $this->page_data['document_data']['title'] = 'Domain - ' . $this->page_data['document_data']['title'];
        $this->display_page('single-domain');


        //@note: Pull api data from ecommerce site and save in database 
        $this->woo_api_hook($domain_id);


    }

    public function single_domain_edit(){

        $domain_id = (int) $this->uri->segment(2);
        $this->page_data['domain']['id'] = $domain_id;

        // Confirm that user owns domain.
        $this->validate_user_domain_access( $domain_id, $this->page_data['user']['id'] );

        $this->page_data['domain']['form_data'] = $this->domain_form_data( $domain_id, $this->page_data['user']['id'] );

        // Available options values.
        $this->page_data['available'] = $this->domain_form_available_values( $this->page_data['user']['id'] );

    	$this->page_data['google_client_id'] = $this->config->config['google_oauth']['client_id'];
    	$this->page_data['google_oauth_redirect_uri'] = $this->config->config['google_oauth']['redirect_uri'];

        $this->page_data['document_data']['title'] = 'Edit Domain - ' . $this->page_data['document_data']['title'];
        $this->display_page('single-domain-edit');
    }

    public function single_domain_delete(){
        
        $domain_id = (int) $this->uri->segment(2);
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        $this->db->flush_cache();

        // Remove domain's external requests cache.
        $this->db->where( "domain_id=", $domain_id );
        $this->db->delete( "domains_requests_cache" );

        // Remove domain from subusers.
        $this->db->where('domain_id', $domain_id);
        $this->db->delete('user_domain');
        
        // Remove domain from groups.
        $this->db->where('domain_id', $domain_id);
        $this->db->delete('domain_groups');

        // Remove domain's uptime'.
        $this->db->where('domain_id', $domain_id);
        $this->db->delete('uptime');

        // Remove domain's search engines.
        $this->db->where( "domain_id=", $domain_id );
        $this->db->delete( "search_engine" );

        // Remove domain keywords.
        $keywords = $this->analyze_model->getkeywordByDomainId( $domain_id );
        if( ! empty( $keywords ) ){
            foreach( $keywords as $k => $v ){
                $this->analyze_model->deleteKeyword( $domain_id, $v['name'], $user_id );
            }
        }

        // Remove domain's local search data.
        $this->db->where( "domain_id", $domain_id );
        $this->db->delete( "domain_local_keyword_search" );

        // Remove domain's data from SERP datatbase tables.
        $this->db->where( "domain_id", $domain_id );
        $this->db->delete( "serp" );

        $this->db->where( "domain_id", $domain_id );
        $this->db->delete( "serp_history" );

        $this->db->where( "domain_id", $domain_id );
        $this->db->delete( "serp_phrase_match" );

        $this->db->where( "domain_id", $domain_id );
        $this->db->delete( "serp_domain_keywords" );

        // User parent user id.
        $parent_id = user_parent_user_id( $this->db, $user_id );
        
        $user_info = $this->analyze_model->getusersinfo( $parent_id ? $parent_id : $user_id );

        // Remove domain.
        $this->domain_model->deleteDomain( $user_info, $domain_id );

        redirect( base_url('domains') );
        exit;
    }

    public function single_domain_serps(){

        $domain_id = (int) $this->uri->segment(2);
        
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        $theme_url = base_url() . 'frontend/site/default/new-ui/';

        $this->page_data['default_values'] = array(
            "graph_keyword" => "n-o-n-e",
            "graph_time" => "n-o-n-e",
            "graph_engine" => "n-o-n-e",
        );

        $this->page_data['available_values'] = array(
            'search_engines' => array(),
            'graph_keyword' => array(
                "n-o-n-e" => "-- Choose Keyword --",
            ),
            'graph_time' => array(
                "n-o-n-e" => "-- Choose Time Period --",
                "all" => "All time",
                "7" => "Last week",
                "15" => "Last two weeks",
                "30" => "Last month"
            ),
            'graph_engine' => array(
                "n-o-n-e" => "-- Choose Search Engine --",
            ),
        );

        $all_search_engines = array(
            'en-us' => array(
                'val' => 'Google.com',
                'title' => 'GOOGLE US',
                'flag' => $theme_url . 'img/flag-us.png'
            ),
            'en-uk' => array(
                'val' => 'Google.co.uk',
                'title' => 'GOOGLE UK',
                'flag' => $theme_url . 'img/flag-uk.png'
            ),
            'en-ca' => array(
                'val' => 'Google.co.ca',
                'title' => 'GOOGLE CA',
                'flag' => $theme_url . 'img/flag-ca.png'
            ),
            'en-au' => array(
                'val' => 'Google.co.au',
                'title' => 'GOOGLE AU',
                'flag' => $theme_url . 'img/flag-au.png'
            )
        );

        $domain_search_engines = $this->analyze_model->getSearchEngineByDomainId( $domain_id );

        foreach ($domain_search_engines as $key => $val) {

            if( $all_search_engines[ $val['name'] ] ){

                $this->page_data['available_values']['search_engines'][ $val['name'] ] = $all_search_engines[ $val['name'] ];

                // TODO: Do following lines need ...?
                /*$mobile = $all_search_engines[ $val['name'] ];
                $mobile['val'] = $mobile['val'] . ' (mobile)';
                $mobile['title'] = $mobile['title'] . ' (mobile)';
                $this->page_data['available_values']["search_engines"][ $val['name'] . "-mobile" ] = $mobile;*/

                $this->page_data['available_values']["graph_engine"][ $val['name'] ] = $all_search_engines[ $val['name'] ]['val'];
                $this->page_data['available_values']["graph_engine"][ $val['name'] . "-mobile" ] = $all_search_engines[ $val['name'] ]['val']." (mobile)";
            }
        }

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $this->page_data['current_page'] = 'single-domain-serps';

        $this->page_data['domain']['id'] = $domain_id;
        $this->page_data['domain']['url'] = $domain_data->domain_name;

        $this->page_data['user']['domains'] = $this->user_domains_list( $user_id );

        $active_settings_tab = '1';
        if( isset( $_COOKIE['campaigns-io'] ) && isset( $_COOKIE['campaigns-io']['content-tabs'] ) && isset( $_COOKIE['campaigns-io']['content-tabs']['serps-tabs'] ) ){
            $active_settings_tab = $_COOKIE['campaigns-io']['content-tabs']['serps-tabs'];
        }

        $serps_data = array(
            'keywords' => array(),
            'edit_keywords' => $this->analyze_model->getkeywordByDomainId( $domain_id ),
        );

        $serps_data['edit_keywords'] = $serps_data['edit_keywords'] ? $serps_data['edit_keywords'] : array();

        $saved_keywords =  array();

        if( ! empty( $serps_data['edit_keywords'] ) ){
            foreach ( $serps_data['edit_keywords'] as $key ) {
                $saved_keywords[] = $key['name'];
                $this->page_data['available_values']["graph_keyword"][ $key['name'] ] = $key['name'];
            }
        }

        $force_refresh_data = isset( $_GET['refresh'] ) ? true : false;
        
        $cached_data = $force_refresh_data ? array( 'value' => null ) : $this->requests_cache->get_value( $domain_id, 'serps-data', true );

        /*if( $cached_data['value'] ){

            $serps_data['clicks'] = $cached_data['value']['clicks'];
            $serps_data['impressions'] = $cached_data['value']['impressions'];

            $this->page_data['page_data_last_update'] = $cached_analytics['date'];
            $this->page_data['page_data_update_now_link'] = base_url('domains/' . $domain_id . '/serps/?refresh');
        }
        else if( ! $force_refresh_data ){

            $this->page_data['page_data_last_update'] = 'N/A';
            $this->page_data['page_data_update_now_link'] = base_url('domains/' . $domain_id . '/serps/?refresh');
        }
        else{*/

            try{
                $new_analytics_handler = new New_Analytics_Handler( $this->db, $this->config, $this->analyze_model );
                $search_engine_keywords = $new_analytics_handler->get_google_search_engine_keywords( $user_id, $domain_id, $this->page_data['domain']['url'], 30 );

                if( ! empty( $search_engine_keywords ) ){
                    foreach ($search_engine_keywords as $key => $val) {
                        if( ! in_array( $key, $saved_keywords, true ) ){
                            
                            $serps_data['keywords'][] = array(
                                'keyword' => $key,
                                'ctr' => $val['ctr'],
                                'clicks' => $val['clicks'],
                                'position' => $val['position'],
                                'impressions' => $val['impressions'],
                            );
                        }
                    }
                }
            }
            catch (Exception $e) {
                // echo 'Caught exception: ',  $e->getMessage(), "\n";
            }

            /*$this->requests_cache->set_value( $domain_id, 'serps-data', $serps_data );

            if( $force_refresh_data ){
                redirect( base_url('domains/' . $domain_id . '/serps') );
                exit;
            }*/

            $this->page_data['page_data_last_update'] = null;
            $this->page_data['page_data_update_now_link'] = null;
        /*}*/

        $serps = array(
            'report_url' => base_url('domains/' . $domain_id . '/serps/report'),
            'report_html' => serp_report( $this->db, $this->analyze_model, $this->page_data['domain'], $user_id, '', true ),
            'keywords' => $serps_data['keywords'],
            'keyword_overall_url' => base_url('domains/' . $domain_id . '/serps/keyword/overall'),
            'keyword_remove_url' => base_url('domains/' . $domain_id . '/serps/keyword/delete'),
            'keyword_add_url' => base_url('domains/' . $domain_id . '/serps/keyword/add'),
            'keyword_add_webmaster_tool_url' => base_url('domains/' . $domain_id . '/serps/keyword/add/webmaster'),
            'filter_keywords_url' => base_url('domains/' . $domain_id . '/serps/filter-keywords'), 
            'edit_keywords' => $serps_data['edit_keywords'],
            'active_settings_tab' => $active_settings_tab
        );

        $this->page_data['serps'] = $serps;

        $keys_filters = array(
            'available' => array(
                'search_dimensions' => array(
                    "query" => "Queries",
                    "page" => "Pages",
                    "country" => "Countries",
                    "device" => "Devices"
                ),
                'search_types' => array(
                    "web" => "Web",
                    "image" => "Image",
                    "video" => "Video",
                ),
            ),
            'selected' => array(
                'start_date' => date( 'Y-m-d', strtotime( '-7 days', time() ) ),
                'end_date' =>  date( 'Y-m-d' ),
                'search_dimensions' => 'web',
                'search_types' => 'query',
            ),
        );

        $this->page_data['keys_filters'] = $keys_filters;

        $this->page_data['document_data']['title'] = 'Domain SERPS - ' . $this->page_data['document_data']['title'];
        $this->display_page('single-domain-serps');
    }

    public function single_domain_serps_report(){

        // Allow only post requests.
        if( 'POST' !== $_SERVER['REQUEST_METHOD'] ){
            redirect( base_url('domains') );
            exit;
        }

        $user_id = $this->page_data['user']['id'];
        $domain_id = (int) $this->uri->segment(2);
        $this->validate_user_domain_access( $domain_id, $user_id );

        $return = array( 'error' => 0, 'html'  => '' );

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $engine = $this->input->post( 'engine', '' );

        $this->page_data['domain']['id'] = $domain_id;
        $this->page_data['domain']['url'] = $domain_data->domain_name;
        $return['html'] = serp_report( $this->db, $this->analyze_model, $this->page_data['domain'], $user_id, $engine, true );

        print_r( json_encode( $return ) );
        die();
    }

    public function single_domain_serps_keyword_overall(){

        // Allow only post requests.
        if( 'POST' !== $_SERVER['REQUEST_METHOD'] ){
            redirect( base_url('domains') );
            exit;
        }

        $user_id = $this->page_data['user']['id'];
        $domain_id = (int) $this->uri->segment(2);
        $this->validate_user_domain_access( $domain_id, $user_id );

        $return = array( 'error' => 0 );

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $engine = $this->input->post( 'engine', '' );
        $keyword = $this->input->post( 'keyword', '' );
        $date = $this->input->post( 'time', '' );

        $overview = $this->analyze_model->serpoverview( $domain_id, $engine, $keyword, $date );

        $return['series']   = $overview['series'];
        $return['category'] = $overview['category'];
        
        print_r( json_encode( $return ) );
        die();
    }

    public function single_domain_serps_keyword_add(){

        // Allow only post requests.
        if( 'POST' !== $_SERVER['REQUEST_METHOD'] ){
            redirect( base_url('domains') );
            exit;
        }

        $user_id = $this->page_data['user']['id'];
        $domain_id = (int) $this->uri->segment(2);        
        $this->validate_user_domain_access( $domain_id, $user_id );

        $return = array( 'error' => 0 );

        $keywords = $this->input->post( 'keywords', array() );

        // Adding keywords to domain.
        $search_engines = $this->analyze_model->getSearchEngineByDomainId( $domain_id );

        // Check if has parent user.
        $this->db->flush_cache();
        $this->db->select("parent_id");
        $this->db->from('users as u');
        $this->db->where("id=", $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        $parentDetail = $query->row_array();

        $parent_user_id = null !== $parentDetail['parent_id'] ? $parentDetail['parent_id'] : null;

        if( $search_engines ) {

            $return['saved_keywords'] = array();

            foreach ( $keywords as $keyword ) {
                if ( '' !== $keyword ) {

                    $insert = array(
                        'user_id' => $parent_user_id ? $parent_user_id : $user_id,
                        'domain_id' => $domain_id,
                        'name' => trim( strtolower( $keyword ) ),
                        'create_at' => date('Y-m-d H:i:s'),
                    );

                    $keyword_id = $this->analyze_model->insertkeyword( $insert );
                    if( $keyword_id ){
                        $return['saved_keywords'][] = $insert['name'];
                    }
                }
            }
        }

        $edit_keywords = $this->analyze_model->getkeywordByDomainId( $domain_id );
        $edit_keywords = $edit_keywords ? $edit_keywords : array();
        $return['new_table_html'] = serp_edit_keywords_table( $edit_keywords, true );

        print_r( json_encode( $return ) );
        die();
    }

    public function single_domain_serps_webmaster_keyword_add(){

        // Allow only post requests.
        if( 'POST' !== $_SERVER['REQUEST_METHOD'] ){
            redirect( base_url('domains') );
            exit;
        }

        $user_id = $this->page_data['user']['id'];
        $domain_id = (int) $this->uri->segment(2);        
        $this->validate_user_domain_access( $domain_id, $user_id );

        $return = array( 'error' => 0 );

        $keywords = $this->input->post( 'add_keyword', array() );

        if ( empty( $keywords ) ) {
            $return['error'] = 1;
            $return['type'] = 'empty-keywords';
        }
        else{
            // Check if has parent user.
            $this->db->flush_cache();
            $this->db->select("parent_id");
            $this->db->from('users as u');
            $this->db->where("id=", $user_id);
            $this->db->limit(1);
            $query = $this->db->get();
            $parentDetail = $query->row_array();
            $parent_id = null !== $parentDetail['parent_id'] ? $parentDetail['parent_id'] : null;

            $keyword_data = array(
                'domain_id' => $domain_id,
                'create_at' => date('Y-m-d H:i:s'),
                'user_id' => $parent_id ? $parent_id : $user_id
            );

            foreach( $keywords as $keyword ){
                $keyword_data['name'] = trim(strtolower($keyword));
                $keyword_data['type'] = 'GWT';
                $this->analyze_model->insertkeyword($keyword_data);
            }
        }

        print_r( json_encode( $return ) );
        die();
    }

    public function single_domain_serps_keyword_delete(){

        // Allow only post requests.
        if( 'POST' !== $_SERVER['REQUEST_METHOD'] ){
            redirect( base_url('domains') );
            exit;
        }

        $user_id = $this->page_data['user']['id'];
        $domain_id = (int) $this->uri->segment(2);        
        $this->validate_user_domain_access( $domain_id, $user_id );

        $return = array( 'error' => 0 );

        $keywords = $this->input->post( 'keywords', array() );
        $keywords = ! is_array( $keywords ) ? array( $keywords ) : $keywords;

        if( ! empty( $keywords ) ){
            foreach( $keywords as $key ) {
                $this->analyze_model->deleteKeyword( $domain_id, $key, $user_id);
            }
        }

        $edit_keywords = $this->analyze_model->getkeywordByDomainId( $domain_id );
        $edit_keywords = $edit_keywords ? $edit_keywords : array();
        $return['new_table_html'] = serp_edit_keywords_table( $edit_keywords, true );

        print_r( json_encode( $return ) );
        die();
    }

    public function single_domain_serps_filter_keywords(){

        $return = array( 'error' => 0, 'values' => null, 'html' => null );

        /*$return['values'] = array(
            "MOBILE" => array(
                "ctr" => 0.04,
                "clicks" => 151,
                "position" => 9,
                "impressions" => 3890
            ),
            "DESKTOP" => array(
                "ctr" => 0.01,
                "clicks" => 84,
                "position" => 33,
                "impressions" => 6051
            ),
            "TABLET" => array(
                "ctr" => 0.05,
                "clicks" => 22,
                "position" => 6,
                "impressions" => 474
            ) 
        );

        $keywords_data = array();

        if( $return['values'] && ! empty( $return['values'] ) ){
            $keywords_data_count = 0;
            foreach( $return['values'] as $k => $v ){
                $keywords_data[ $keywords_data_count ] = $v;
                $keywords_data[ $keywords_data_count ]['keyword'] = $k;
                $keywords_data_count++;
            }
        }

        $return['html'] = serp_keywords_table_html( $keywords_data );

        print_r( json_encode( $return ) );
        die();*/

        // Allow only post requests.
        if( 'POST' !== $_SERVER['REQUEST_METHOD'] ){
            redirect( base_url('domains') );
            exit;
        }

        $user_id = $this->page_data['user']['id'];
        $domain_id = (int) $this->uri->segment(2);
        $this->validate_user_domain_access( $domain_id, $user_id ); // TODO: In case of fail, shouldn't redirect but "die()".

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $domain_url = $domain_data->domain_name;

        $keywords_data = array();

        try{

            $new_analytics_handler = new New_Analytics_Handler( $this->db, $this->config, $this->analyze_model );

            $filtered_keywords = $new_analytics_handler->search_engine_keywords_data(
                $domain_url,
                $user_id,
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['search_type'],
                array( $_POST['search_dimension'] )
            );

            if( $filtered_keywords && ! empty( $filtered_keywords ) ){
                $keywords_data_count = 0;
                foreach( $filtered_keywords as $k => $v ){
                    $keywords_data[ $keywords_data_count ] = $v;
                    $keywords_data[ $keywords_data_count ]['keyword'] = $k;
                    $keywords_data_count++;
                }
            }

            // TODO: Doesn't really need.
            // $return['values'] = $filtered_keywords;
            
        }
        catch (Exception $e) {
            // echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        $return['html'] = serp_keywords_table_html( $keywords_data, $_POST['search_dimension'] );

        print_r( json_encode( $return ) );
        die();
    }

    public function single_domain_heatmaps(){

        $this->load->model('analytics/heatmap_model');

        $domain_id = (int) $this->uri->segment(2);
        
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        $heatmap_id = (int) $this->uri->segment(4);

        if( 0 === $heatmap_id ){
            // @note: In case is not set (through $_GET parameter in URL) a heatmap id, use the first of domain's heatmaps list.
            $sql = "SELECT id FROM heatmaps WHERE user_id=" . $user_id . " AND domain_id=" . $domain_id . " LIMIT 1";
            $query = $this->db->query( $sql );
            $query = $query ? $query->row_array() : $query;
            $heatmap_id = isset( $query['id'] ) ? (int) $query['id'] : $heatmap_id;
        }

        if( 0 !== $heatmap_id ){
            $this->validate_user_heatmap_access( $heatmap_id, $user_id );
        }

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $this->page_data['current_page'] = 'single-domain-heatmaps';

        $this->page_data['domain']['id'] = $domain_id;
        $this->page_data['domain']['url'] = $domain_data->domain_name;

        $this->page_data['user']['domains'] = $this->user_domains_list( $user_id );

        $this->page_data['heatmaps'] = array(
            'current' => array(
                'id' => $heatmap_id,
                'data' => 0 < $heatmap_id ? $this->heatmap_model->getById( $heatmap_id ) : array()
            ),
            'available' => $this->heatmap_model->getAllByUserId( $user_id, $domain_id ),
            'list_url' => base_url( 'domains/' . $domain_id . '/heatmaps/list' )
        );

        $this->page_data['document_data']['title'] = 'Domain Heatmaps - ' . $this->page_data['document_data']['title'];
        $this->display_page( $this->page_data['current_page'] );
    }

    public function single_domain_heatmaps_add(){

        $this->load->model('analytics/heatmap_model');

        $user_id = $this->page_data['user']['id'];

        $domain_id = (int) $this->uri->segment(2);
        
        $this->validate_user_domain_access( $domain_id, $user_id );

        if( isset( $_POST ) && 
            isset( $_POST['hotjar_page_name'] ) && '' !== trim( $_POST['hotjar_page_name'] ) && 
            isset( $_POST['hotjar_url'] ) && '' !== trim( $_POST['hotjar_url'] ) ){
            
            // @note: Save updated data.
            $this->db->query( "INSERT INTO heatmaps (user_id, embed_url, page_name, domain_id, created, modified) VALUES(" . $user_id . ", '" . $_POST['hotjar_url'] . "', '" . $_POST['hotjar_page_name'] . "', " . $domain_id . ", NOW(), NOW() )" );

            // @note: Redirect in domain's heatmaps page.
            redirect( base_url( 'domains/' . $domain_id . '/heatmaps/' . $heatmap_id ) );
            exit;
        }

        $this->page_data['current_page'] = 'single-domain-heatmaps-add';
        $this->page_data['domain']['id'] = $domain_id;


        $this->page_data['document_data']['title'] = 'Add Domain Heatmap - ' . $this->page_data['document_data']['title'];
        $this->display_page( $this->page_data['current_page'] );
    }

    public function single_domain_heatmaps_edit(){

        $this->load->model('analytics/heatmap_model');

        $user_id = $this->page_data['user']['id'];

        $domain_id = (int) $this->uri->segment(2);
        $heatmap_id = (int) $this->uri->segment(4);
        
        $this->validate_user_domain_access( $domain_id, $user_id );
        $this->validate_user_heatmap_access( $heatmap_id, $user_id );

        if( isset( $_POST ) && 
            isset( $_POST['hotjar_page_name'] ) && '' !== trim( $_POST['hotjar_page_name'] ) && 
            isset( $_POST['hotjar_url'] ) && '' !== trim( $_POST['hotjar_url'] ) ){
            
            // @note: Save updated data.
            $this->db->query( "UPDATE heatmaps SET page_name='" . $_POST['hotjar_page_name'] . "', embed_url='" . $_POST['hotjar_url'] . "', modified=NOW() WHERE id=" . $heatmap_id );

            // @note: Redirect in heatmap's page.
            redirect( base_url( 'domains/' . $domain_id . '/heatmaps/' . $heatmap_id ) );
            exit;
        }

        $this->page_data['current_page'] = 'single-domain-heatmaps-edit';
        $this->page_data['domain']['id'] = $domain_id;

        $this->page_data['heatmap'] = array(
            'id' => $heatmap_id,
            'data' => $this->heatmap_model->getById( $heatmap_id )
        );

        $this->page_data['document_data']['title'] = 'Edit Domain Heatmap - ' . $this->page_data['document_data']['title'];
        $this->display_page( $this->page_data['current_page'] );
    }

    public function single_domain_heatmaps_delete(){

        $this->load->model('analytics/heatmap_model');

        $user_id = $this->page_data['user']['id'];

        $domain_id = (int) $this->uri->segment(2);
        $heatmap_id = (int) $this->uri->segment(4);
        
        $this->validate_user_domain_access( $domain_id, $user_id );
        $this->validate_user_heatmap_access( $heatmap_id, $user_id );

        $this->db->delete( 'heatmaps', array('id' => $heatmap_id ) );
        
        redirect( base_url( 'domains/' . $domain_id . '/heatmaps' ) );

        exit;
    }

    public function single_domain_heatmaps_list(){

        $this->load->model('analytics/heatmap_model');

        $domain_id = (int) $this->uri->segment(2);
        
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $this->page_data['current_page'] = 'single-domain-heatmaps-list';

        $this->page_data['domain']['id'] = $domain_id;
        $this->page_data['domain']['url'] = $domain_data->domain_name;

        $this->page_data['user']['domains'] = $this->user_domains_list( $user_id );

        $this->page_data['heatmaps'] = array(
            'url' => base_url( 'domains/' . $domain_id . '/heatmaps' ),
            'list_data' => $this->heatmap_model->getAllByUserId( $user_id, $domain_id )
        );

        $this->page_data['document_data']['title'] = 'Domain Heatmaps List - ' . $this->page_data['document_data']['title'];
        $this->display_page( $this->page_data['current_page'] );
    }

    public function single_domain_research(){

        $this->load->model('serp/serporganickeyword_model');
        $this->load->model('serp/serpcompetitororganicsearch_model');
        $this->load->model('serp/serpdomain_model');
        $this->load->model('serp/serpdomainhistory_model');
        $this->load->model('serp/serpaddkeywords_model');

        $domain_id = (int) $this->uri->segment(2);
        
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $this->page_data['current_page'] = 'single-domain-research';

        $this->page_data['domain']['id'] = $domain_id;
        $this->page_data['domain']['url'] = $domain_data->domain_name;

        $this->page_data['user']['domains'] = $this->user_domains_list( $user_id );

        $domain_url = strtr( $this->page_data['domain']['url'], array( 'http://' => '', 'https://' => '', 'www.' => '' ) );

        $overview_info = array();
        $organic_keywords = array();
        $pages_visibility = array();
        $referrers_links = array();
        $ads_keywords = array();
        $keyword_position_distribution = array(0,0,0,0,0,0,0);
        $competitors_trend_data = array();
        $competitors_graph_data = array();
        $trends_data = array(
            'dates' => array(),
            'visibility' => array(
                'results' => array(),
                'max_key' => 'n/a',
                'min_key' => 'n/a',
                'max_key_date' => 'n/a',
                'min_key_date' => 'n/a'
            ),
            'keywords' => array(
                'results' => array(),
                'max_key' => 'n/a',
                'min_key' => 'n/a',
                'max_key_date' => 'n/a',
                'min_key_date' => 'n/a'
            ),
        );

        $found_serp_api_error = false;

        /* ==================== // OVERVIEW INFO ==================== */

        $overview_info = $this->serpdomain_model->getDomainInfoByDomain( $domain_url );

        if ( ! $overview_info || strtotime( "+7 day", strtotime( $overview_info['created'] ) ) < time() ) {
            
            $url = 'http://api.serpstat.com/v3/domain_info?query=' . $domain_url . '&se=g_uk&token=' . $this->ci->config->config['serpstat']['token'];

            $result = json_decode( file_get_contents( $url ), true );
            
            if ( isset( $result['status_msg'] ) && 'OK' === $result['status_msg'] ) {
                
                $this->db->flush_cache();

                $this->db->insert( 'serp_domain_info', array(
                    'domain' => $domain,
                    'visible' => $result['result']['visible'],
                    'keywords' => $result['result']['keywords'],
                    'traff' => $result['result']['traff'],
                    'visible_dynamic' => $result['result']['visible_dynamic'],
                    'keywords_dynamic' => $result['result']['keywords_dynamic'],
                    'traff_dynamic' => $result['result']['traff_dynamic'],
                    'date' => $result['result']['date'],
                    'prev_date' => $result['result']['prev_date'],
                    'new_keywords' => $result['result']['new_keywords'],
                    'out_keywords' => $result['result']['out_keywords'],
                    'rised_keywords' => $result['result']['rised_keywords'],
                    'down_keywords' => $result['result']['down_keywords'],
                    'ad_keywords' => $result['result']['ad_keywords'],
                    'ads' => $result['result']['result']['ads'],
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s')
                ));

                $overview_info = $this->serpdomain_model->getDomainInfoByDomain( $domain_url );
            }
            else{
                $found_serp_api_error = true;
            }
        }

        /* ==================== OVERVIEW INFO // ==================== */


        /* ==================== // OVERVIEW ORGANIC KEYWORDS ==================== */
        
        $organic_keywords  = $this->serporganickeyword_model->getSerpOrganiKeywordByDomain( $domain_url );
        
        if ( ! $found_serp_api_error && ( ! $organic_keywords || strtotime("+7 day", strtotime( $organic_keywords['created'] ) ) < time() ) ) {
            
            $url = 'http://api.serpstat.com/v3/domain_keywords?query=' . $domain_url . '&se=g_uk&token=' . $this->ci->config->config['serpstat']['token'];
            $result = json_decode( file_get_contents( $url ), true );

            if ( isset( $result['status_msg'] ) && 'OK' === $result['status_msg'] ) {

                $this->db->flush_cache();            

                $this->db->insert( 'serp_organic_keyword', array(
                    'domain' => $domain_url,
                    'result' => json_encode( $result['result']['hits'] ),
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s')
                ));

                $organic_keywords = $this->serporganickeyword_model->getSerpOrganiKeywordByDomain( $domain_url );
            }
            else{
                $found_serp_api_error = true;
            }
        }

        /* ==================== OVERVIEW ORGANIC KEYWORDS // ==================== */


        /* ==================== // OVERVIEW ADS KEYWORDS ==================== */

        $ads_keywords = $this->serpaddkeywords_model->getAdKeywordsByDomain( $domain_url );

        if ( ! $found_serp_api_error && ( ! $ads_keywords || strtotime( "+7 day", strtotime( $ads_keywords[0]['created'] ) ) < time() ) ) {

            $url = 'http://api.serpstat.com/v3/ad_keywords?query=' . $domain_url . '&se=g_uk&token=' . $this->ci->config->config['serpstat']['token'];
            $result = json_decode( file_get_contents( $url ), true );

            if ( isset( $result['status_msg'] ) && 'OK' === $result['status_msg'] ) {

                $this->db->query( "DELETE FROM serp_ad_keywords WHERE domain = '" . $domain_url . "'");
                
                $this->db->flush_cache();
                
                foreach( $result['result'] as $res ){                
                    $this->db->insert('serp_ad_keywords', array(
                        'domain' => $domain_url,
                        'region_queries_count' => $res['region_queries_count'],
                        'region_queries_count_wide' => $res['region_queries_count_wide'],
                        'keyword' => $res['keyword'],
                        'title' => $res['title'],
                        'url' => $res['url'],
                        'text' => $res['text'],
                        'found_results' => $res['found_results'],
                        'url_crc' => $res['url_crc'],
                        'crc' => $res['crc'],
                        'cost' => $res['cost'],
                        'concurrency' => $res['concurrency'],
                        'position' => $res['position'],
                        'keyword_id' => $res['keyword_id'],
                        'subdomain' => $res['subdomain'],
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    ));
                }

                $ads_keywords = $this->serpaddkeywords_model->getAdKeywordsByDomain( $domain_url );
            }
            else{
                $found_serp_api_error = true;
            }
        }

        /* ==================== OVERVIEW ADS KEYWORDS // ==================== */


        /* ==================== // OVERVIEW KEYWORD POSITION DISTRIBUTION ==================== */

        $serp = $this->analyze_model->getSerpResult( $domain_id );

        if( $serp ) {

            foreach($serp as $s){

                if( 1 === $s['position'] ) {
                    $keyword_position_distribution[0]++;
                }
                else if( 2 <= $s['position'] && 3 >= $s['position'] ) {
                    $keyword_position_distribution[1]++;
                }
                else if( 4 <= $s['position'] && 5 >= $s['position'] ) {
                    $keyword_position_distribution[2]++;
                }
                else if( 6 <= $s['position'] && 10 >= $s['position'] ) {
                    $keyword_position_distribution[3]++;
                }
                else if( 11 <= $s['position'] && 20 >= $s['position'] ) {
                    $keyword_position_distribution[4]++;
                }
                else if( 21 <= $s['position'] && 50 >= $s['position'] ) {
                    $keyword_position_distribution[5]++;
                }
                else if( 51 <= $s['position'] && 100 >= $s['position'] ) {
                    $keyword_position_distribution[6]++;
                }
            }
        }
        
        /* ==================== OVERVIEW KEYWORD POSITION DISTRIBUTION // ==================== */
        

        /* ==================== // OVERVIEW SUBDOMAINS ==================== */

        // TODO: ... 

        /* ==================== OVERVIEW SUBDOMAINS // ==================== */


        /* ==================== // OVERVIEW PAGES VISIBILITY & REFFERER LINKS ==================== */

        try{
            
            $new_analytics_handler = new New_Analytics_Handler( $this->db, $this->config, $this->analyze_model );
            
            $pages_visibility = $new_analytics_handler->get_pages_visibility( $user_id, $domain_data->domain_name );

            $referrers_links = $new_analytics_handler->get_domain_referrers( $user_id, $domain_id );

        } catch(Exception $e){}

        /* ==================== OVERVIEW PAGES VISIBILITY & REFFERER LINKS // ==================== */


        /* ==================== // OVERVIEW COMPETITORS TREND ==================== */

        $competitors_trend_data = $this->serpcompetitororganicsearch_model->getcompetitorOrganicSearchByDomain( $domain_url );

        if ( ! $found_serp_api_error && ( ! $competitors_trend_data || strtotime( "+7 day", strtotime( $competitors_trend_data['created'] ) ) < time() ) ) {

            $url = 'http://api.serpstat.com/v3/competitors?query=' . $domain_url . '&se=g_uk&token=' . $this->ci->config->config['serpstat']['token'];
            $result = json_decode( file_get_contents( $url ), true );
            
            if ( isset( $result['status_msg'] ) && 'OK' === $result['status_msg'] ) {
                $this->db->flush_cache();
                $this->db->insert( 'serp_competitor_organic_search', array(
                    'domain' => $domain_url,
                    'result' => json_encode( $result['result'] ),
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s')
                ));
            }
            else{
                $found_serp_api_error = true;
            }

            $competitors_trend_data = $this->serpcompetitororganicsearch_model->getcompetitorOrganicSearchByDomain( $domain_url );
        }

        $competitors_trend = isset( $competitors_trend_data['result'] ) ? array_slice( json_decode( $competitors_trend_data['result'], true ), 0, 20 ) : array();

        /* ==================== OVERVIEW COMPETITORS TREND // ==================== */


        /* ==================== // OVERVIEW COMPETITORS GRAPH ==================== */

        if( ! empty( $competitors_trend ) ){
            foreach( $competitors_trend as $k => $v ){
                $competitors_graph_data[] = array(
                    'x' => $v['keywords'],
                    'y' => $v['visible'],
                    'domain' => $v['domain']
                );
            }
        }

        /* ==================== OVERVIEW COMPETITORS GRAPH // ==================== */


        /* ==================== // OVERVIEW TRENDS ==================== */

        $visibility_and_keywords_trend = $this->serpdomainhistory_model->getSerpDomainHistoryByDomain( $domain_url );
        
        if (! $found_serp_api_error && ( ! $visibility_and_keywords_trend || strtotime( "+7 day", strtotime( $visibility_and_keywords_trend[0]['created'] ) ) < time() ) ) {

            $url = 'http://api.serpstat.com/v3/domain_history?query=' . $domain_url . '&se=g_uk&token=' . $this->ci->config->config['serpstat']['token'];
            $result = json_decode( file_get_contents( $url ), true );

            if ( isset( $result['status_msg'] ) && 'OK' === $result['status_msg'] ) {
                
                $this->db->query("DELETE FROM serp_domain_history WHERE domain='" . $domain_url . "'");

                $this->db->flush_cache();
                
                foreach( $result['result'] as $res ){
                
                    $this->db->insert( 'serp_domain_history', array(
                        'domain' => $domain_url,
                        'visible_static' => $res['visible_static'],
                        'date' => $res['date'],
                        'ads' => $res['ads'],
                        'new_keywords' => $res['new_keywords'],
                        'rised_keywords' => $res['rised_keywords'],
                        'down_keywords' => $res['down_keywords'],
                        'visible' => $res['visible'],
                        'ad_keywords' => $res['ad_keywords'],
                        'traff' => $res['traff'],
                        'out_keywords' => $res['out_keywords'],
                        'keywords' => $res['keywords'],
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    ));
                }

                $visibility_and_keywords_trend = $this->serpdomainhistory_model->getSerpDomainHistoryByDomain( $domain_url );
            }
            else{
                $found_serp_api_error = true;
            }
        }

        if( ! empty( $visibility_and_keywords_trend ) ){

            $trends_data['visibility']['max_key'] = 0;
            $trends_data['visibility']['min_key'] = 99999999;
            $trends_data['keywords']['max_key'] = 0;
            $trends_data['keywords']['min_key'] = 99999999;

            for($i=0; $i<count( $visibility_and_keywords_trend ); $i++){

                $trend = $visibility_and_keywords_trend[$i];

                $trends_data['dates'][] = $trend['date'];
                $trends_data['visibility']['results'][] = $trend['visible'];
                $trends_data['keywords']['results'][] = $trend['keywords'];

                if( $trends_data['keywords']['max_key'] < $trend['keywords'] ){
                    $trends_data['keywords']['max_key'] = $trend['keywords'];
                    $trends_data['keywords']['max_key_date'] = $trend['date'];
                }

                if( $trends_data['keywords']['min_key'] > $trend['keywords'] ){
                    $trends_data['keywords']['min_key'] = $trend['keywords'];
                    $trends_data['keywords']['min_key_date'] = $trend['date'];
                }

                if( $trends_data['visibility']['max_key'] < $trend['visible'] ){
                    $trends_data['visibility']['max_key'] = $trend['visible'];
                    $trends_data['visibility']['max_key_date'] = $trend['date'];
                }

                if( $trends_data['visibility']['min_key'] > $trend['visible'] ){
                    $trends_data['visibility']['min_key'] = $trend['visible'];
                    $trends_data['visibility']['min_key_date'] = $trend['date'];
                }
            }
        }

        /* ==================== OVERVIEW TRENDS // ==================== */

        
        /* ==================== // OVERVIEW BACKLINKS OVERVIEW ==================== */

        

        /* ==================== OVERVIEW BACKLINKS OVERVIEW // ==================== */



        /* ==================== // OVERVIEW NEW BACKLINKS ==================== */



        /* ==================== OVERVIEW NEW BACKLINKS // ==================== */

        /* ==================== // POSITIONS ==================== */

        $this->page_data['positions']['serp_report_html'] = serp_report( $this->db, $this->analyze_model, $this->page_data['domain'], $user_id, '', true );

        /* ==================== DOMAIN VS DOMAIN // ==================== */

        $this->page_data['domain_compare_url'] = base_url( 'domains/' . $domain_id . '/research/compare' );

        /* ==================== DOMAIN VS DOMAIN // ==================== */

        $theme_url = base_url() . 'frontend/site/default/new-ui/';

        $this->page_data['available_values'] = array(
            'search_engines' => array()
        );

        $all_search_engines = array(
            'en-us' => array(
                'val' => 'Google.com',
                'title' => 'GOOGLE US',
                'flag' => $theme_url . 'img/flag-us.png'
            ),
            'en-uk' => array(
                'val' => 'Google.co.uk',
                'title' => 'GOOGLE UK',
                'flag' => $theme_url . 'img/flag-uk.png'
            ),
            'en-ca' => array(
                'val' => 'Google.co.ca',
                'title' => 'GOOGLE CA',
                'flag' => $theme_url . 'img/flag-ca.png'
            ),
            'en-au' => array(
                'val' => 'Google.co.au',
                'title' => 'GOOGLE AU',
                'flag' => $theme_url . 'img/flag-au.png'
            )
        );

        $domain_search_engines = $this->analyze_model->getSearchEngineByDomainId( $domain_id );

        foreach ( $domain_search_engines as $key => $val ) {
            if( $all_search_engines[ $val['name'] ] ){
                $this->page_data['available_values']['search_engines'][ $val['name'] ] = $all_search_engines[ $val['name'] ];
                $this->page_data['available_values']["graph_engine"][ $val['name'] ] = $all_search_engines[ $val['name'] ]['val'];
                $this->page_data['available_values']["graph_engine"][ $val['name'] . "-mobile" ] = $all_search_engines[ $val['name'] ]['val']." (mobile)";
            }
        }

        $this->page_data['overview'] = array(
            'info' => array(
                'visibility' => $overview_info['visible'] ? $overview_info['visible'] : 'n/a',
                'traffic' => $overview_info['traff'] ? $overview_info['traff'] : 'n/a',
                'organic_keywords' => $overview_info['out_keywords'] ? $overview_info['out_keywords'] : 'n/a',
                'ppc' => $overview_info['ad_keywords'] ? $overview_info['ad_keywords'] : 'n/a'
            ),
            'organic_keywords' => isset( $organic_keywords['result'] ) ? array_slice( json_decode( $organic_keywords['result'], true ), 0, 20 ) : array(),
            'ads_keywords' => $ads_keywords,
            'keyword_position_distribution' => json_encode( $keyword_position_distribution ),
            'subdomains' => array(),    // TODO: Data...
            'pages_visibility' => $pages_visibility,
            'competitors_trend' => $competitors_trend,
            'competitors_graph_data' => json_encode( $competitors_graph_data ),
            'trends' => array(
                'dates' => $trends_data['dates'],
                'visibility' => array(
                    'max_trend_date' => $trends_data['visibility']['max_key_date'],
                    'min_trend_date' => $trends_data['visibility']['min_key_date'],
                    'max_trend' => $trends_data['visibility']['max_key'],
                    'min_trend' => $trends_data['visibility']['min_key'],
                    'results' => $trends_data['visibility']['results']
                ),
                'keywords' => array(
                    'max_trend_date' => $trends_data['keywords']['max_key_date'],
                    'min_trend_date' => $trends_data['keywords']['min_key_date'],
                    'max_trend' => $trends_data['keywords']['max_key'],
                    'min_trend' => $trends_data['keywords']['min_key'],
                    'results' => $trends_data['keywords']['results']
                ),
            ),
            // NOTE: Replaced, both of them, with 'referrers_links'.
            /*'backlinks_overview' => array()
            'new_backlinks' => array() */
            'referrers_links' => $referrers_links
        );

        $this->page_data['document_data']['title'] = 'Domain Research - ' . $this->page_data['document_data']['title'];
        $this->display_page( $this->page_data['current_page'] );
    }

    public function single_domain_compare(){

        // Allow only post requests.
        if( 'POST' !== $_SERVER['REQUEST_METHOD'] ){
            redirect( base_url('domains') );
            exit;
        }

        $this->load->model('serp/serpcompetitorcompare_model');

        $domain_id = (int) $this->uri->segment(2);
        
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        $return = array();

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $compare_domain_url = $this->input->post( 'url' );
        /*$compare_domain_url = 'markbarnesphotography.co.uk';*/

        $domain_url = trim( strtr( $domain_data->domain_name, array( 'http://' => '', 'https://' => '', 'www.' => '' ) ) );
        $compare_domain_url = trim( strtr( $compare_domain_url, array( 'http://' => '', 'https://' => '', 'www.' => '' ) ) );

        if( empty( $compare_domain_url ) ){
            $return['html'] = "Invalid domain URL";
        }
        else{

            $res = $this->serpcompetitorcompare_model->getserpcompetitorByDomains( $domain_url, $compare_domain_url );

            if ( ! $res || strtotime("+7 day", strtotime( $res['created'] ) ) < time() ) {

                $insert_id = 0;
                
                $res = array(
                    'domain1' => $domain_url,
                    'domain2' => $compare_domain_url,
                    'domain3' => 'N/A',
                    'domain1_total' => 0,
                    'domain2_total' => 0,
                    'domain3_total' => 0,
                    'domain1_unique' => 0,
                    'domain2_unique' => 0,
                    'domain3_unique' => 0,
                    'result' => array(),
                    'total_common_keyword' => 0,
                );

                $token = $this->ci->config->config['serpstat']['token'];

                $url = 'http://api.serpstat.com/v3/domains_intersection?query=' . $domain_url . ',' . $compare_domain_url . '&se=g_uk&token=' . $token;
                
                $result = json_decode( file_get_contents( $url ), true );
                
                if ( isset( $result['status_msg'] ) && 'OK' === $result['status_msg'] ) {
                    $arr = array(
                        'domain1' => $domain_url,
                        'domain2' => $compare_domain_url,
                        'total_common_keyword' => $result['result']['total'],
                        'result' => $result['result']['hits'],
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    );
                    $this->db->flush_cache();
                    $this->db->insert( 'serp_competitor_compare', $arr );
                    $insert_id = $this->db->insert_id();
                    $res['total_common_keyword'] = $result['result']['total'];
                }

                if( $insert_id ){

                    $url = 'http://api.serpstat.com/v3/domains_uniq_keywords?query=' . $domain_url . '&minus_domain=' . $compare_domain_url . '&se=g_uk&token=' . $token;

                    $result = json_decode( file_get_contents( $url ), true );
                    
                    if ( isset( $result['status_msg'] ) && 'OK' === $result['status_msg'] ) {
                        $arr['domain1_unique'] = $result['result']['total'];
                        $arr['domain1_total']  = $result['result']['total'] + $arr['total_common_keyword'];
                        $this->db->flush_cache();
                        $this->db->update( 'serp_competitor_compare', $res, array( 'id' => $insert_id ) );
                    }

                    $url = 'http://api.serpstat.com/v3/domains_uniq_keywords?query=' . $compare_domain_url . '&minus_domain=' . $domain_url . '&se=g_uk&token=' . $token;
                    
                    $result = json_decode( file_get_contents( $url ), true );

                    if ( isset( $result['status_msg'] ) && 'OK' === $result['status_msg'] ) {
                        $arr['domain2_unique'] = $result['result']['total'];
                        $arr['domain2_total'] = $result['result']['total'] + $arr['total_common_keyword'];
                        $this->db->flush_cache();
                        $this->db->update( 'serp_competitor_compare', $res, array( 'id' => $insert_id ) );
                    }

                    $res = $this->serpcompetitorcompare_model->getserpcompetitorByDomains( $domain_url, $compare_domain_url );
                }
            }

            $res['result'] = is_array( $res['result'] ) ? $res['result'] : json_decode( $res['result'], true );

            ob_start();
            domains_competition_results( $res );
            $return['html'] = ob_get_contents();
            ob_end_clean();
        }

        $return = json_encode( $return );
        
        print_r( $return );

        die();
    }

    public function single_domain_e_commerce(){

        
        $this->load->model( 'analytics/usergaaccount_model' );

        $this->load->model( 'auth/analyze_model' );


        $domain_id = (int) $this->uri->segment(2);
        
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $this->page_data['current_page'] = 'single-domain-e-commerce';

        $this->page_data['domain']['id'] = $domain_id;
        $this->page_data['domain']['url'] = $domain_data->domain_name;

        $this->page_data['user']['domains'] = $this->user_domains_list( $user_id );

        $valid_days_filters = array(1, 7, 15, 30);

        $this->page_data['available_days_filter_options'] = array(
            "1" => "Today",
            "7" => "Last week",
            "15" => "Last two weeks",
            "30" => "Last month"
        );

        $this->page_data['current_days_filter'] = (int) $this->input->get('d');
        $this->page_data['current_days_filter'] = in_array( $this->page_data['current_days_filter'], $valid_days_filters, true ) ? $this->page_data['current_days_filter'] : 30;

        $new_analytics_handler = new New_Analytics_Handler( $this->db, $this->config, $this->analyze_model );

        $traffic = $new_analytics_handler->get_google_analytics_traffic_info( $user_id, $domain_id, $this->page_data['current_days_filter'] );
        $total_traffic = 0;
        if($traffic['status'] == 'success') {
            
            foreach($traffic['payload'] as $tr){
                $total_traffic+=$tr;
            }
        }
        $this->page_data['ecom_data']['total_traffic'] = $total_traffic;
        

        $this->page_data['product_data'] = $new_analytics_handler->get_product_data( $user_id, $domain_data, $this->usergaaccount_model, $this->page_data['current_days_filter'] );



        $average_sales =  $this->analyze_model->get_woo_average_order_by_day($this->page_data['current_days_filter']);
        $this->page_data['ecom_data']['average_sales'] = number_format($average_sales['average_sales']);

        $average_sales_previous =  $this->analyze_model->get_woo_average_order_date_range(date('Y-m-d'), 60);
        $this->page_data['ecom_data']['average_sales_previous'] = number_format($average_sales_previous['average_sales']);



        $total_transactions = $this->analyze_model->get_woo_total_transactions_by_day($this->page_data['current_days_filter']);
        $this->page_data['ecom_data']['total_transactions'] = $total_transactions['total_transactions'];

        $total_sales =  $this->analyze_model->get_woo_total_sales_by_day($this->page_data['current_days_filter']);
        $this->page_data['ecom_data']['total_sales'] = number_format($total_sales['total_sales'],2);

        $this->page_data['ecom_data']['total_refunds'] = $this->analyze_model->get_woo_total_refunds($this->page_data['current_days_filter']);

        $last_month = $this->analyze_model->get_woo_last_month();
        $this->page_data['ecom_data']['last_month'] = $last_month['total_sales'];


        $current_month = $this->analyze_model->get_woo_current_month();
        $this->page_data['ecom_data']['current_month'] = $current_month['total_sales'];

        $total_shipping = $this->analyze_model->get_woo_shipping_total($this->page_data['current_days_filter']);
        $this->page_data['ecom_data']['total_shipping'] = number_format($total_shipping['total_shipping'],2);


        $transactions_data = $new_analytics_handler->get_transactions( $user_id, $domain_data, $this->usergaaccount_model, $this->page_data['current_days_filter'] );


        $currency = null !== $transactions_data['payload']['ecommercestats']['currency'] ? $transactions_data['payload']['ecommercestats']['currency'] . ' ' : '';

        $this->page_data['transactions'] = array(
            // 'abandoned_cart_value' => $currency + data.payload.abandonedcartstats.revenue,
            'abandoned_cart_value' => 'n/a',
            'total_sales_summary' => null !== $transactions_data['payload']['ecommercestats']['total_sale'] ? $transactions_data['payload']['ecommercestats']['total_sale'] : 'n/a',
            'total_sales_value' => null !== $transactions_data['payload']['ecommercestats']['sales_value'] ? $currency . $transactions_data['payload']['ecommercestats']['sales_value'] : 'n/a',
            'average_order' => null !== $transactions_data['payload']['ecommercestats']['average_order'] ?  $currency . $transactions_data['payload']['ecommercestats']['average_order'] : 'n/a'
        );

        $this->page_data['document_data']['title'] = 'Domain E-Commerce - ' . $this->page_data['document_data']['title'];
        $this->display_page( $this->page_data['current_page'] );
    }

    public function single_domain_gtmetrix_rescan(){

        // Allow only post requests.
        if( 'POST' !== $_SERVER['REQUEST_METHOD'] ){
            redirect( base_url('domains') );
            exit;
        }

        $user_id = $this->page_data['user']['id'];
        $domain_id = (int) $this->uri->segment(2);        
        $this->validate_user_domain_access( $domain_id, $user_id );

        $return = array( 'error' => 0 );

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $domain_url = $domain_data->domain_name;

        $gtmetrix_data = array(
            'loaded' => false,
            'metrix' => array(),
            'date' => ''
        );

        $gtmetrix = just_gtmetrix_data( $domain_id, $domain_url, $this->analyze_model, $this->ci_auth, $this->session, true );

        if( isset( $gtmetrix['status'] ) && $gtmetrix['status'] && 
            isset( $gtmetrix['metrix'] ) && ! empty( $gtmetrix['metrix'] ) &&
            isset( $gtmetrix['date'] ) && ! empty( $gtmetrix['date'] ) ){   // Ensure data structure.
            $gtmetrix_data = array(
                'loaded' => true,
                'metrix' => json_decode( $gtmetrix['metrix'], true ),
                'date' => $gtmetrix['date'],
            );
        }

        ob_start();
        single_domain_gtmetrix_content( $domain_url, $gtmetrix_data );
        $return['html'] = ob_get_contents();
        ob_end_clean();

        print_r( json_encode( $return ) );
        die();
    }

    public function single_domain_analytics(){

    	$domain_id = (int) $this->uri->segment(2);
        
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $domain_url = $domain_data->domain_name;

        $referrer_visits = array();
        $referrer_visits_graph_array = array();
        $referrer_visits_summary = 0;
        $referrer_visits_array = array();

        if( (int) $domain_data->connectToGoogle ){
            
            $force_refresh_data = isset( $_GET['refresh'] ) ? true : false;

            $cached_analytics = $force_refresh_data ? array( 'value' => null ) : $this->requests_cache->get_value( $domain_id, 'analytics', true );

            if( $cached_analytics['value'] ){
                $visits = $cached_analytics['value']['visits'];
                $top_countries = $cached_analytics['value']['top_countries'];
                $top_sources = $cached_analytics['value']['top_sources'];
                $total_visits = $cached_analytics['value']['total_visits'];
                $unique_visits = $cached_analytics['value']['unique_visits'];
                $page_per_visit = $cached_analytics['value']['page_per_visit'];
                $referrer_visits_data = $cached_analytics['value']['referrer_visits_data'];

                $this->page_data['page_data_last_update'] = $cached_analytics['date'];
                $this->page_data['page_data_update_now_link'] = base_url('domains/' . $domain_id . '/analytics/?refresh');
            }
            else if( ! $force_refresh_data ){

                // @note: If is not requested to refresh data, display the page without values ("n/a") and let user to click on button "CHECK NOW". Otherwise, the page could take long time to load without the user understand the reason of delay.

                $visits = array('status' => null);
                $top_countries = array('status' => null);
                $top_sources = array('status' => null);
                $total_visits = array('status' => null);
                $unique_visits = array('status' => null);
                $page_per_visit = array('status' => null);
                $referrer_visits_data = array('status' => null);

                $this->page_data['page_data_last_update'] = 'N/A';
                $this->page_data['page_data_update_now_link'] = base_url('domains/' . $domain_id . '/analytics/?refresh');
            }
            else{

                $new_analytics_handler = new New_Analytics_Handler( $this->db, $this->config, $this->analyze_model );

                $visits = $new_analytics_handler->get_visits( $user_id, $domain_id );

                $top_countries = $new_analytics_handler->get_top_countries( $user_id, $domain_id );
                $top_sources = $new_analytics_handler->get_visit_sources( $user_id, $domain_id );
                $total_visits = $new_analytics_handler->get_total_visits( $user_id, $domain_id );
                $unique_visits = $new_analytics_handler->get_unique_visits( $user_id, $domain_id );
                $page_per_visit = $new_analytics_handler->get_page_per_visit( $user_id, $domain_id );
                $referrer_visits_data = $new_analytics_handler->get_referrer_visits( $user_id, $domain_id );

                $this->requests_cache->set_value( $domain_id, 'analytics', array(
                    'visits' => $visits,
                    'top_countries' => $top_countries,
                    'top_sources' => $top_sources,
                    'total_visits' => $total_visits,
                    'unique_visits' => $unique_visits,
                    'page_per_visit' => $page_per_visit,
                    'referrer_visits_data' => $referrer_visits_data
                ) );

                if( $force_refresh_data ){
                    redirect( base_url('domains/' . $domain_id . '/analytics') );
                    exit;
                }

                $this->page_data['page_data_last_update'] = null;
                $this->page_data['page_data_update_now_link'] = null;
            }

            $visits = is_array( $visits ) && isset( $visits['payload'] ) ? $visits['payload'] : array();
        }
        else{
            $visits = array();
            $top_countries = array('status' => 'disabled');
            $top_sources = array('status' => 'disabled');
            $total_visits = array('status' => 'disabled');
            $unique_visits = array('status' => 'disabled');
            $page_per_visit = array('status' => 'disabled');
            $referrer_visits_data = array('status' => 'disabled');

            $this->page_data['page_data_disabled_ga'] = true;
            $this->page_data['page_data_edit_settings_link'] = base_url('domains/' . $domain_id . '/edit');
        }

		$this->page_data['domain']['summ_total'] = array(
		  'unique_visitors' => isset( $visits["totalUniqueVisitors"] ) && $visits["totalUniqueVisitors"] ? $visits["totalUniqueVisitors"] : 'n/a',
		  'visitors' => isset( $visits["totalVisitors"] ) && $visits["totalVisitors"] ? $visits["totalVisitors"] : 'n/a',
		  'page_views' => isset( $visits["totalPagePerVisit"] ) && $visits["totalPagePerVisit"] ? $visits["totalPagePerVisit"] : 'n/a',
		);

		$this->page_data['domain']['top_countries'] = 'success' === $top_countries['status'] ? $top_countries['payload']['topcountries'] : array();
		$this->page_data['domain']['top_sources'] = 'success' === $top_sources['status'] ? $top_sources['payload']['sites'] : array();
		$this->page_data['domain']['total_visits'] = 'success' === $total_visits['status'] ? $total_visits['payload']['totalVisitsGraph'] : array();
		$this->page_data['domain']['unique_visits'] = 'success' === $unique_visits['status'] ? $unique_visits['payload']['uniqueVisitorsGraph'] : array();
		$this->page_data['domain']['page_per_visit'] = 'success' === $page_per_visit['status'] ? $page_per_visit['payload']['totalPagePerVisitGraph'] : array();

        if( 'success' === $referrer_visits_data['status'] ){
            $referrer_visits = $referrer_visits_data['payload']['referrervisit'];
            $referrer_visits_graph_array = $referrer_visits_data['payload']['referrervisitgraph'];
        }

        foreach ($referrer_visits as $key => $val) {
            $referrer_visits_summary += $val['nb_visits'];
        }

        foreach ($referrer_visits as $key => $val) {
            $referrer_visits_array[] = array( 'x' => $val['label'], 'y' => round( ( $val['nb_visits'] * 100 ) / $referrer_visits_summary , 2 ) ) ;
        }

		$this->page_data['domain']['referrer_visits_array'] = $referrer_visits_array;		
		$this->page_data['domain']['referrer_visits_graph_array'] = $referrer_visits_graph_array;

		$this->page_data['domain']['id'] = $domain_id;

        $this->page_data['user']['domains'] = $this->user_domains_list( $user_id );
		
        $this->page_data['document_data']['title'] = 'Domain Analytics - ' . $this->page_data['document_data']['title'];
        $this->display_page('single-domain-analytics');
    }

    public function single_domain_wordpress(){
        
        $domain_id = (int) $this->uri->segment(2);
        $user_id = $this->page_data['user']['id'];
        
        $this->validate_user_domain_access( $domain_id, $user_id );

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];

        $domain_url = $domain_data->domain_name;

        $force_refresh_data = isset( $_GET['refresh'] ) ? true : false;

        $cached_wp_updates = $force_refresh_data ? array( 'value' => null ) :  $this->requests_cache->get_value( $domain_id, 'wp-updates-all', true );

        if( $cached_wp_updates['value'] ){

            $wp_updates = $cached_wp_updates['value'];

            $this->page_data['page_data_last_update'] = $cached_wp_updates['date'];
            $this->page_data['page_data_update_now_link'] = base_url('domains/' . $domain_id . '/wordpress/?refresh');
        }
        else if( ! $force_refresh_data ){

            // @note: If is not requested to refresh data, display the page without values ("n/a") and let user to click on button "CHECK NOW". Otherwise, the page could take long time to load without the user understand the reason of delay.

            $wp_updates = array();

            $this->page_data['page_data_last_update'] = 'N/A';
            $this->page_data['page_data_update_now_link'] = base_url('domains/' . $domain_id . '/wordpress/?refresh');
        }
        else{
            $wp_access = new WP_Request_Access( array( 'id' => $domain_id, 'data' => $domain_data ), $this->session, $this->ci_auth );
            $wp_updates = $wp_access->updates( array( 'id' => $domain_id, 'data' => $domain_data ), 'all', false, true);
            unset( $wp_access );

            $this->requests_cache->set_value( $domain_id, 'wp-updates-all', $wp_updates );

            if( $force_refresh_data ){
                redirect( base_url('domains/' . $domain_id . '/wordpress') );
                exit;
            }

            $this->page_data['page_data_last_update'] = null;
            $this->page_data['page_data_update_now_link'] = null;
        }

        $wp_updates['core'] = isset( $wp_updates['core'] ) && is_array( $wp_updates['core'] ) ? $wp_updates['core'] : array();
	    $wp_updates['plugins'] = isset( $wp_updates['plugins'] ) && is_array( $wp_updates['plugins'] ) ? $wp_updates['plugins'] : array();
	    $wp_updates['themes'] = isset( $wp_updates['themes'] ) && is_array( $wp_updates['themes'] ) ? $wp_updates['themes'] : array();

        $wp_updates['can_update_core'] = isset( $wp_updates['can_update_core'] ) ? $wp_updates['can_update_core'] : 0;
        $wp_updates['can_update_themes'] = isset( $wp_updates['can_update_themes'] ) ? $wp_updates['can_update_themes'] : 0;
        $wp_updates['can_update_plugins'] = isset( $wp_updates['can_update_plugins'] ) ? $wp_updates['can_update_plugins'] : 0;

	    $this->page_data['domain']['id'] = $domain_id;
        $this->page_data['domain']['wp_updates'] = $wp_updates;

        $this->page_data['domain']['url'] = array(
            'wp_login' => base_url( 'domains/' . $domain_id . '/wordpress/login' ),
        );

        $this->page_data['user']['domains'] = $this->user_domains_list( $user_id );

        $this->page_data['document_data']['title'] = 'Domain WordPress - ' . $this->page_data['document_data']['title'];
        $this->display_page('single-domain-wordpress');
    }

    public function single_domain_wordpress_ping(){

        $domain_id = (int) $this->uri->segment(2);
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        $site = array( 
            'id' => $domain_id,
            'data' => get_site_by_id( $domain_id, $this->ci_auth->ci ),
        );

        $is_authorized = is_client_authorized( $site['id'], $site['data'] );

        $all_ok = 'ok' === $is_authorized ? 1 : 0 ;

        if( ! $all_ok ){

            $wp_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
            $all_ok = $wp_access->ping_site();

            if( 1 !== (int) $all_ok ){
                $_SESSION[ 'wp-site-login-unreachable-' . $domain_id ] = 1;
            }
        }

        print_r( $all_ok );
        die();
    }

    public function single_domain_wordpress_login(){

        $domain_id = (int) $this->uri->segment(2);
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        // Initialize session values.
        $_SESSION[ 'wp-site-login-action-' . $domain_id ] = $domain_id . '-' . $user_id;

        unset( $_SESSION[ 'wp-site-login-unreachable-' . $domain_id ] );

        $domain_details = $this->analyze_model->getDomain( $domain_id );
        $domain_details = $domain_details[0];

        $this->page_data['domain']['id'] = $domain_id;
        $this->page_data['domain']['name'] = $domain_details->domain_name;
        $this->page_data['domain']['adminUsername'] = $domain_details->adminUsername;

        $this->page_data['wp_ping_url'] = base_url('domains/' . $domain_id . '/wordpress/ping');
        $this->page_data['wp_unreachable_url'] = base_url('domains/' . $domain_id . '/wordpress/unreachable');
        $this->page_data['wp_login_action_url'] = base_url('domains/' . $domain_id . '/wordpress/login/action');

        $this->page_data['current_page'] = 'single-domain-wordpress-login';

        $site = array( 
            'id' => $domain_id,
            'data' => get_site_by_id( $domain_id, $this->ci_auth->ci ),
        );

        $is_authorized = is_client_authorized( $site['id'], $site['data'] );

        $this->page_document_data( $this->page_data['current_page'] );

        $this->page_data['login_request_data'] = array(
            'is_authorized' => is_client_authorized( $site['id'], $site['data'] ),
            'site' => $site,
            'this_session' => $this->session,
            'this_ci_auth' => $this->ci_auth,
        );

        $this->load->view( "campaigns-io/templates/html-header" , $this->page_data );
        $this->load->view( 'campaigns-io/pages/' . $this->page_data['current_page'], $this->page_data);
        $this->load->view( 'campaigns-io/templates/html-footer', $this->page_data );
    }

    public function single_domain_wordpress_login_action(){

        $domain_id = (int) $this->uri->segment(2);
        $user_id = $this->page_data['user']['id'];

        $this->validate_user_domain_access( $domain_id, $user_id );

        if( ! isset( $_SESSION[ 'wp-site-login-action-' . $domain_id ] ) || $_SESSION[ 'wp-site-login-action-' . $domain_id ] !== $domain_id . '-' . $user_id ){
            redirect( base_url('domains') );
            exit;
        }

        unset( $_SESSION[ 'wp-site-login-action-' . $domain_id ] );

        $site = array( 
            'id' => $domain_id,
            'data' => get_site_by_id( $domain_id, $this->ci_auth->ci ),
        );

        $is_authorized = is_client_authorized( $site['id'], $site['data'] );

        if( 'ok' === $is_authorized ){
            $wp_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
            $wp_access->login( $site );
        }
        else{
            $wp_auth_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
            if( 'refresh' === $is_authorized && ! empty( $site['data']->refresh_token ) ){
                $wp_auth_acce