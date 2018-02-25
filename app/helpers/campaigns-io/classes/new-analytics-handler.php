<?php
// Based on controller 'analytics/analytics'.
class New_Analytics_Handler {

    private $user_id;
    private $domain_id;
    private $g_client;
    private $db;
    private $config;
    private $analyze_model;

    function __construct( $db, $config, $analyze_model ){
        $this->db = $db;
        $this->config = $config;
        $this->analyze_model = $analyze_model;
    }

    private function google_client(){

        if( ! $this->g_client ){

            $client_id = $this->config->config['google_oauth']['client_id'];
            $client_secret = $this->config->config['google_oauth']['client_secret'];
            $redirect_uri = $this->config->config['google_oauth']['redirect_uri'];
            $simple_api_key = $this->config->config['google_oauth']['api_key'];

            $this->g_client = new Google_Client( array(
              'developer_key' => $simple_api_key,
              'application_name' => "PHP Google OAuth Login Example",
              'client_id' => $client_id,
              'client_secret' => $client_secret,
              'redirect_uri' => $redirect_uri,
              'access_type' => 'offline',
              'approval_prompt' => 'force',
            ) );
        }

        // print_r( $this->g_client );

        return $this->g_client;
    }

    private function getUserIdByGaCode($gaCode) {
        $this->db->flush_cache();
        $this->db->select( '*' );
        $this->db->from( 'user_ga_account' );
        $this->db->where( 'ga_code=', $gaCode );
        $query = $this->db->get();
        return $query ? $query->row_array() : false;
    }

    private function getUserIdByAccountId($id) {
        $this->db->flush_cache();
        $this->db->select( '*' );
        $this->db->from( 'user_ga_account' );
        $this->db->where( 'id=', $id );
        $query = $this->db->get();
        return $query ? $query->row_array() : false;
    }

    private function getUserAuthData($user_id) {
        $this->db->select('*');
        $this->db->where( 'user_auth.user_id=', $user_id );
        $this->db->from( 'user_auth' );
        $query = $this->db->get();
        return $query->result();
    }

    private function updateUserAuthData($id, $data) {
        $this->db->where( 'user_id', $id );
        return $this->db->update( 'user_auth', $data ) ? true : NULL;
    }

    private function get_authenticated_user($userid) {

        // TODO: Need to check for users, parent user id...?

        $user = array();

        $client = $this->google_client();

        if ( $client->getAccessToken() ) {
            $_SESSION['token'] = $client->getAccessToken();
            return true;
        }
        else {
            $result = $this->getUserAuthData($userid);
            $user = (array) $result;

            if ( count($user) > 0 ) {

                if ( $client->isAccessTokenExpired() ) {

                    $token = $client->refreshToken( $user[0]->refresh_token );
                    $_SESSION['token'] = $client->getAccessToken();
                    $auth_data['access_token'] = $_SESSION['token']['access_token'];
                    $auth_data['token_type'] = $_SESSION['token']['token_type'];
                    $auth_data['expires_in'] = $_SESSION['token']['expires_in'];
                    $auth_data['created'] = $_SESSION['token']['created'];
                    $update = $this->updateUserAuthData($userid, $auth_data);

                    if ($update) {
                        return true;
                    }
                }
                else {
                    return true;
                }
            }
            else {
                // TODO: Probably don't need the following lines.
                if (isset($_GET['code'])) {
                    
                    $client->authenticate($_GET['code']);
                    
                    $_SESSION['token'] = $client->getAccessToken();
                    
                    if (isset($_SESSION['token'])) {
                        $client->setAccessToken($_SESSION['token']);
                    }

                    $auth_data['user_id'] = $userid;
                    $auth_data['access_token'] = $_SESSION['token']['access_token'];
                    $auth_data['token_type'] = $_SESSION['token']['token_type'];
                    $auth_data['expires_in'] = $_SESSION['token']['expires_in'];
                    $auth_data['refresh_token'] = $_SESSION['token']['refresh_token'];
                    $auth_data['created'] = $_SESSION['token']['created'];
                    
                    $insert = $this->analytics_model->insertUserAuthData($auth_data);
                    
                    if ($insert) {
                        $access_token = $_SESSION['token']['access_token'];
                        return true;
                    }
                }
            }
        }
    }

    public function search_engine_keywords_data( $domain_url, $user_id, $start_date = null, $end_date = null, $search_type = null, $search_dimensions = null ){

        $return = array();

        $start_date = $start_date ? $start_date : date( 'Y-m-d', strtotime( '-7 days', time() ) );  // By default, request data for last week.
        $end_date = $end_date ? $end_date : date('Y-m-d');
        $search_type = $search_type ? $search_type : 'web';
        $search_dimensions = $search_dimensions && is_array( $search_dimensions ) && ! empty( $search_dimensions ) ? $search_dimensions : array('query');

        $client = $this->google_client();

        if ( ! $client || ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $service = new Google_Service_Webmasters( $client );

        $request = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();        
        $request->setStartDate( $start_date );
        $request->setEndDate( $end_date );
        $request->setDimensions( $search_dimensions );
        $request->setSearchType( $search_type );

        $query = $service->searchanalytics->query( $domain_url, $request );

        if( ! empty( $query->rows ) ) {
            foreach ( $query->rows as $val ) {
                $return[ $val->keys[0] ] = array(
                    'ctr' => round($val->ctr, 2),
                    'clicks' => $val->clicks,
                    'position' => round($val->position),
                    'impressions' => $val->impressions
                );
            }
        }

        return $return;
    }

    public function get_google_search_engine_keywords( $user_id, $domainId, $domain_url, $days ){
        $end_date = date( 'Y-m-d' );
        $start_date = date( 'Y-m-d', strtotime( '-' . $days . ' days', time() ) );
        return $this->search_engine_keywords_data( $domain_url, $user_id, $start_date, $end_date, 'web', array('query') );
    }


    public function get_google_analytics_traffic_info_date_range($user_id, $domainId, $startDate, $endDate) {


        $return = array(
            'status' => 'error',
            'payload' => null,
        );

        $client = $this->google_client();

        if ( ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $domainId );

        $google_account_id = (int) $domainDetails['ga_account'];

        if( 0 < $google_account_id ){

            $google_account_data = $this->getUserIdByAccountId( $domainDetails['ga_account'] );

            if( ! $google_account_data || ! isset( $google_account_data['ga_code'] ) || ! $google_account_data['ga_code'] ){
                
                $return['msg'] = 'Invalid ga user account';
                return $return;
            }
        }
        else{
            $return['msg'] = 'No google account';
            return $return;
        }
        
        if( $google_account_data['ga_code'] && $google_account_data['webpropertyid'] ){

            $this->get_authenticated_user( $google_account_data['user_id'] );
            
            $analytics = new Google_Service_Analytics( $client );

            $profiles = $analytics->management_profiles->listManagementProfiles( $google_account_data['ga_code'], '~all' );

            $items = $profiles->getItems();

            if ( 0 < count( $items ) ) {

                $profileId =  $items[0]->getId();
             
                // Add Analytics View ID, prefixed with "ga:"
                $analyticsViewId = 'ga:' . $profileId;
                
                $metrics = 'ga:visits';
                
                $data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ) );

                $items = $data->getRows();

                if( $items ) {

                    $paypload = array();
                
                    foreach( $items as $item ){
                        $paypload[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
                    }
                
                    $return['status'] = 'success';
                    $return['payload'] = array_reverse( $paypload );
                }
                else{
                    $return['msg'] = 'No ga views items';
                }
            }
            else{
                $return['msg'] = 'No ga views (profiles) found for this user.';
            }
        }
        else{
            $return['status'] = 'success';
            $return['msg'] = "Has not selected domain's google account";
        }

        return $return;
        
    }

    public function get_google_analytics_traffic_info( $user_id, $domainId, $days, $end_date='' ){

        $return = array(
            'status' => 'error',
            'payload' => null,
        );

        $client = $this->google_client();

        if ( ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $domainId );

        $google_account_id = (int) $domainDetails['ga_account'];

        if( 0 < $google_account_id ){

            $google_account_data = $this->getUserIdByAccountId( $domainDetails['ga_account'] );

            if( ! $google_account_data || ! isset( $google_account_data['ga_code'] ) || ! $google_account_data['ga_code'] ){
                
                $return['msg'] = 'Invalid ga user account';
                return $return;
            }
        }
        else{
            $return['msg'] = 'No google account';
            return $return;
        }
        
        if( $google_account_data['ga_code'] && $google_account_data['webpropertyid'] ){

            $this->get_authenticated_user( $google_account_data['user_id'] );
            
            $analytics = new Google_Service_Analytics( $client );

            $profiles = $analytics->management_profiles->listManagementProfiles( $google_account_data['ga_code'], '~all' );

            $items = $profiles->getItems();

            if ( 0 < count( $items ) ) {

                $profileId =  $items[0]->getId();
             
                // Add Analytics View ID, prefixed with "ga:"
                $analyticsViewId = 'ga:' . $profileId;
                $startDate = date( 'Y-m-d', strtotime( '-' . $days . ' days', time() ) );
                if($end_date) {
                    $endDate = $end_date;
                } else {
                    $endDate = date( 'Y-m-d' );
                }
                
                $metrics = 'ga:visits';
                $data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ) );

                $items = $data->getRows();

                if( $items ) {

                    $paypload = array();
                
                    foreach( $items as $item ){
                        $paypload[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
                    }
                
                    $return['status'] = 'success';
                    $return['payload'] = array_reverse( $paypload );
                }
                else{
                    $return['msg'] = 'No ga views items';
                }
            }
            else{
                $return['msg'] = 'No ga views (profiles) found for this user.';
            }
        }
        else{
            $return['status'] = 'success';
            $return['msg'] = "Has not selected domain's google account";
        }

        return $return;
    }

    public function getGaDetailByDomain($domain) {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('user_ga_account');
        $this->db->where('websiteurl', $domain);
        $query = $this->db->get();
        return $query ? $query->row_array() : false;
    }

    public function get_transactions( $user_id, $domain_data, $user_ga_account_model, $days = 30 ){

        $client = $this->google_client();

        if ( ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $domain_url  = strtr( $domain_data->domain_name, array( 'http://' => '', 'https://' => '', 'www.' => '', '/' => '' ) );
        
        $property = $user_ga_account_model->getGaDetailByDomain( $domain_url );
        
        $web_property_id = $property['webpropertyid'] ? $property['webpropertyid'] : '~all';

        $return = array(
            'status' => 'error',
            'payload' => array(
                'ecommercestats' => array(
                    'total_sale' => null,
                    'sales_value' => null,
                    'average_order' => null,
                    'currency' => null
                )
            )
        );

        try{
            $analytics = new Google_Service_Analytics( $client );
            $profiles = $analytics->management_profiles->listManagementProfiles( $domain_data->ga_account, $web_property_id );
            if ( 0 < count( $profiles->getItems() ) ) {
                
                $items = $profiles->getItems();

                // Return the first view (profile) ID.
                $profileId =  $items[0]->getId();

                // Add Analytics View ID, prefixed with "ga:"
                $analytics_view_id = 'ga:' . $profileId;
                $start_date = date( 'Y-m-d', strtotime( '-' . $days . ' days', time() ) );
                $end_date = date( 'Y-m-d' );
                $metrics = 'ga:transactions,ga:transactionRevenue,ga:transactionsPerSession';
                
                $data = $analytics->data_ga->get( $analytics_view_id, $start_date, $end_date, $metrics, array( 'dimensions' => 'ga:currencyCode' ) );
                $items = $data->getRows();

                if( $items ) {
                    $res = array(
                        'status' => 'success',
                        'payload' => array(
                            'ecommercestats' => array(
                                'total_sale' => number_format( $items[1][1], 2 ),
                                'sales_value' => number_format( $items[1][2], 2 ),
                                'average_order' => number_format( $items[1][3], 2 ),
                                'currency' => $items[1][0]
                            )
                        )
                    );
                }
            }
        }
        catch( Exception $e ){}

        return $return;
    }

    public function get_product_data( $user_id, $domain_data, $user_ga_account_model, $days = 30 ){

        $client = $this->google_client();

        if ( ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $domain_url  = strtr( $domain_data->domain_name, array( 'http://' => '', 'https://' => '', 'www.' => '', '/' => '' ) );
        
        $property = $user_ga_account_model->getGaDetailByDomain( $domain_url );
        
        $web_property_id = $property['webpropertyid'] ? $property['webpropertyid'] : '~all';

        $return = array();

        try{

            $analytics = new Google_Service_Analytics( $client );
            $profiles = $analytics->management_profiles->listManagementProfiles( $domain_data->ga_account, $web_property_id );

            if ( 0 < count( $profiles->getItems() ) ) {
                $items = $profiles->getItems();

                // Return the first view (profile) ID.
                $profileId =  $items[0]->getId();

                // Add Analytics View ID, prefixed with "ga:"
                $analytics_view_id = 'ga:' . $profileId;
                $start_date = date( 'Y-m-d', strtotime( '-' . $days . ' days', time() ) );
                $end_date = date( 'Y-m-d' );
                $metrics = 'ga:transactionRevenue,ga:itemQuantity,ga:transactions,ga:transactionsPerSession';
                
                $data = $analytics->data_ga->get( $analytics_view_id, $start_date, $end_date, $metrics, array( 'dimensions' => 'ga:productName' ) );
                
                $return = $data->getRows();
            }
        }
        catch( Exception $e ){}

        return $return;

    }

    private function request_google_analytics_info($user_id, $domainId, $request_args, $request_type = null, $days = 30, $metrics = 'ga:visits'){

        $return = array( 'status' => 'error' );

        $client = $this->google_client();

        if ( ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $domainId );

        $google_account_id = (int) $domainDetails['ga_account'];

        if( 0 < $google_account_id ){

            $google_account_data = $this->getUserIdByAccountId( $domainDetails['ga_account'] );

            if( ! $google_account_data || ! isset( $google_account_data['ga_code'] ) || ! $google_account_data['ga_code'] ){
                
                $return['msg'] = 'Invalid ga user account';
                return $return;
            }
        }
        else{
            $return['msg'] = 'No google account';
            return $return;
        }

        if( $google_account_data['ga_code'] && $google_account_data['webpropertyid'] ){

            if ( ! $client->getAccessToken() ) {
                $this->get_authenticated_user( $google_account_data['user_id'] );
            }
            
            $analytics = new Google_Service_Analytics( $client );
            $profiles = $analytics->management_profiles->listManagementProfiles( $google_account_data['ga_code'], $google_account_data['webpropertyid'] );
        
            $items = $profiles->getItems();

            if ( 0 < count( $items ) ) {

                // Return the first view (profile) ID.
                $profileId =  $items[0]->getId();

                // Add Analytics View ID, prefixed with "ga:"
                $analyticsViewId = 'ga:' . $profileId;
                $startDate = date('Y-m-d', strtotime('-' . ( (int) $days ) . ' days', time() ) );
                $endDate = date('Y-m-d');

                $data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, $request_args );
                $items = $data->getRows();

                if( $items ) {
                    $return['status'] = 'success';
                    switch( $request_type ){
                        case 'visits_number':
                            $return['clicks'] = $items[0][0];
                            break;
                        case 'visits':
                            $return['payload'] = array(
                                'totalUniqueVisitors' => $items[0][1],
                                'totalVisitors' => $items[0][2],
                                'totalPagePerVisit' => $items[0][0],
                            );
                            break;
                        case 'top_country':
                            $items = array_slice( $items, 0, 15 );
                            $topcountries = array();
                            foreach( $items as $key => $item ){
                                $topcountries[$key]['label'] = $item[0];
                                $topcountries[$key]['nb_visits'] = $item[1];
                            }
                            $return['payload'] = array( 'topcountries' => $topcountries );
                            break;
                        case 'visit_sources':
                            $items = array_slice( $items, 0, 15 );
                            $sites = array();
                            foreach( $items as $key => $item ){
                                $sites[$key]['label'] = $item[0];
                                $sites[$key]['nb_visits'] = $item[1];
                            }
                            $return['payload'] = array( 'sites' => $sites );
                            break;
                        case 'total_visits':
                            $totalVisitsGraph = array();
                            foreach( $items as $item ){
                                $totalVisitsGraph[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
                            }
                            $return['payload'] = array( 'totalVisitsGraph' => array_reverse( $totalVisitsGraph ) );
                            break;
                        case 'unique_visits':
                            $uniqueVisitorsGraph = array();
                            foreach( $items as $item ){
                                $uniqueVisitorsGraph[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
                            }
                            $return['payload'] = array( 'uniqueVisitorsGraph' => array_reverse( $uniqueVisitorsGraph ) );
                            break;
                        case 'page_per_visit':
                            $totalPagePerVisitGraph = array();
                            foreach( $items as $item ){
                                $totalPagePerVisitGraph[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
                            }
                            $return['payload'] = array( 'totalPagePerVisitGraph' => array_reverse( $totalPagePerVisitGraph ) );
                            break;
                        case 'referrer_visits':
                            $direct_total = 0;
                            $organic_total = 0;
                            $referral_total = 0;
                            foreach( $items as $key => $item ){
                                $label = '';
                                if( preg_match( '/organic/i', $item[0] ) ){
                                    $label = 'organic';
                                    $organic_total += $item[1];
                                }
                                if(preg_match('/none/i', $item[0])){
                                    $label = 'direct';
                                    $direct_total += $item[1];
                                }
                                if( preg_match( '/referral/i', $item[0] ) ){
                                    $label = 'referral';
                                    $referral_total += $item[1];
                                }
                            }
                            $return['payload'] = array(
                                'referrervisit' => array(
                                    array( 'label' => 'direct','nb_visits' => $direct_total ),
                                    array( 'label' => 'referral','nb_visits' => $referral_total),
                                    array( 'label' => 'organic','nb_visits' => $organic_total ),
                                )
                            );

                            $startDate = date('Y-m-d',strtotime('-365 days', time()));
                            $endDate = date('Y-m-d');
                            $metrics = 'ga:visits';
                            $data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, array( 'dimensions' => 'ga:sourceMedium,ga:date', 'sort' => '-ga:date' ) );

                            $items = $data->getRows();

                            if($items) {

                                $res = array();
                                $organic_total = 0;
                                $referral_total = 0;
                                $direct_total = 0;
                                foreach($items as $key=>$item){
                                    $label = '';
                                    if(preg_match('/organic/i', $item[0])){
                                        $label = 'organic';
                                        $res[date('Y-m-01',strtotime($item[1]))]['organic']+=$item[2];
                                    }
                                    if(preg_match('/none/i', $item[0])){
                                        $label = 'direct';
                                        $res[date('Y-m-01',strtotime($item[1]))]['direct']+=$item[2];
                                    }
                                    if(preg_match('/referral/i', $item[0])){
                                        $label = 'referral';
                                        $res[date('Y-m-01',strtotime($item[1]))]['referral']+=$item[2];
                                    }
                                }

                                $referrervisitgraph = array();
                                foreach($res as $key=>$temp){
                                    $referrervisitgraph[$key][0] = array('label'=>'direct', 'nb_visits'=>$temp['direct']);
                                    $referrervisitgraph[$key][1] = array('label'=>'organic', 'nb_visits'=>$temp['organic']);
                                    $referrervisitgraph[$key][2] = array('label'=>'referral', 'nb_visits'=>$temp['referral']);
                                }

                                $return['payload']['referrervisitgraph'] = array_reverse( $referrervisitgraph );
                            }
                            else {
                                $return['status'] = 'error';
                                $return['payload']['referrervisitgraph'] = null;
                            }
                            break;
                    }
                }
                else{
                    $return['msg'] = 'No ga views items';
                }
            }
            else{
                $return['msg'] = 'No ga views (profiles) found for this user.';
            }
        }
        else{
            $return['status'] = 'success';
            $return['msg'] = "Has not selected domain's google account";
        }

        return $return;
    }

    private function request_google_analytics_info_date_range($user_id, $domainId, $request_args, $request_type = null, $startDate,$endDate, $metrics = 'ga:visits'){

        $return = array( 'status' => 'error' );

        $client = $this->google_client();

        if ( ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $domainId );

        $google_account_id = (int) $domainDetails['ga_account'];

        if( 0 < $google_account_id ){

            $google_account_data = $this->getUserIdByAccountId( $domainDetails['ga_account'] );

            if( ! $google_account_data || ! isset( $google_account_data['ga_code'] ) || ! $google_account_data['ga_code'] ){
                
                $return['msg'] = 'Invalid ga user account';
                return $return;
            }
        }
        else{
            $return['msg'] = 'No google account';
            return $return;
        }

        if( $google_account_data['ga_code'] && $google_account_data['webpropertyid'] ){

            if ( ! $client->getAccessToken() ) {
                $this->get_authenticated_user( $google_account_data['user_id'] );
            }
            
            $analytics = new Google_Service_Analytics( $client );
            $profiles = $analytics->management_profiles->listManagementProfiles( $google_account_data['ga_code'], $google_account_data['webpropertyid'] );
        
            $items = $profiles->getItems();

            if ( 0 < count( $items ) ) {

                // Return the first view (profile) ID.
                $profileId =  $items[0]->getId();

                // Add Analytics View ID, prefixed with "ga:"
                $analyticsViewId = 'ga:' . $profileId;
                $data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, $request_args );
                $items = $data->getRows();

                if( $items ) {
                    $return['status'] = 'success';
                    switch( $request_type ){
                        case 'visits_number':
                            $return['clicks'] = $items[0][0];
                            break;
                        case 'visits':
                            $return['payload'] = array(
                                'totalUniqueVisitors' => $items[0][1],
                                'totalVisitors' => $items[0][2],
                                'totalPagePerVisit' => $items[0][0],
                            );
                            break;
                        case 'top_country':
                            $items = array_slice( $items, 0, 15 );
                            $topcountries = array();
                            foreach( $items as $key => $item ){
                                $topcountries[$key]['label'] = $item[0];
                                $topcountries[$key]['nb_visits'] = $item[1];
                            }
                            $return['payload'] = array( 'topcountries' => $topcountries );
                            break;
                        case 'visit_sources':
                            $items = array_slice( $items, 0, 15 );
                            $sites = array();
                            foreach( $items as $key => $item ){
                                $sites[$key]['label'] = $item[0];
                                $sites[$key]['nb_visits'] = $item[1];
                            }
                            $return['payload'] = array( 'sites' => $sites );
                            break;
                        case 'total_visits':
                            $totalVisitsGraph = array();
                            foreach( $items as $item ){
                                $totalVisitsGraph[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
                            }
                            $return['payload'] = array( 'totalVisitsGraph' => array_reverse( $totalVisitsGraph ) );
                            break;
                        case 'unique_visits':
                            $uniqueVisitorsGraph = array();
                            foreach( $items as $item ){
                                $uniqueVisitorsGraph[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
                            }
                            $return['payload'] = array( 'uniqueVisitorsGraph' => array_reverse( $uniqueVisitorsGraph ) );
                            break;
                        case 'page_per_visit':
                            $totalPagePerVisitGraph = array();
                            foreach( $items as $item ){
                                $totalPagePerVisitGraph[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
                            }
                            $return['payload'] = array( 'totalPagePerVisitGraph' => array_reverse( $totalPagePerVisitGraph ) );
                            break;
                        case 'referrer_visits':
                            $direct_total = 0;
                            $organic_total = 0;
                            $referral_total = 0;
                            foreach( $items as $key => $item ){
                                $label = '';
                                if( preg_match( '/organic/i', $item[0] ) ){
                                    $label = 'organic';
                                    $organic_total += $item[1];
                                }
                                if(preg_match('/none/i', $item[0])){
                                    $label = 'direct';
                                    $direct_total += $item[1];
                                }
                                if( preg_match( '/referral/i', $item[0] ) ){
                                    $label = 'referral';
                                    $referral_total += $item[1];
                                }
                            }
                            $return['payload'] = array(
                                'referrervisit' => array(
                                    array( 'label' => 'direct','nb_visits' => $direct_total ),
                                    array( 'label' => 'referral','nb_visits' => $referral_total),
                                    array( 'label' => 'organic','nb_visits' => $organic_total ),
                                )
                            );

                            $metrics = 'ga:visits';
                            $data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, array( 'dimensions' => 'ga:sourceMedium,ga:date', 'sort' => '-ga:date' ) );

                            $items = $data->getRows();

                            if($items) {

                                $res = array();
                                $organic_total = 0;
                                $referral_total = 0;
                                $direct_total = 0;
                                foreach($items as $key=>$item){
                                    $label = '';
                                    if(preg_match('/organic/i', $item[0])){
                                        $label = 'organic';
                                        $res[date('Y-m-01',strtotime($item[1]))]['organic']+=$item[2];
                                    }
                                    if(preg_match('/none/i', $item[0])){
                                        $label = 'direct';
                                        $res[date('Y-m-01',strtotime($item[1]))]['direct']+=$item[2];
                                    }
                                    if(preg_match('/referral/i', $item[0])){
                                        $label = 'referral';
                                        $res[date('Y-m-01',strtotime($item[1]))]['referral']+=$item[2];
                                    }
                                }

                                $referrervisitgraph = array();
                                foreach($res as $key=>$temp){
                                    $referrervisitgraph[$key][0] = array('label'=>'direct', 'nb_visits'=>$temp['direct']);
                                    $referrervisitgraph[$key][1] = array('label'=>'organic', 'nb_visits'=>$temp['organic']);
                                    $referrervisitgraph[$key][2] = array('label'=>'referral', 'nb_visits'=>$temp['referral']);
                                }

                                $return['payload']['referrervisitgraph'] = array_reverse( $referrervisitgraph );
                            }
                            else {
                                $return['status'] = 'error';
                                $return['payload']['referrervisitgraph'] = null;
                            }
                            break;
                    }
                }
                else{
                    $return['msg'] = 'No ga views items';
                }
            }
            else{
                $return['msg'] = 'No ga views (profiles) found for this user.';
            }
        }
        else{
            $return['status'] = 'success';
            $return['msg'] = "Has not selected domain's google account";
        }

        return $return;
    }

    public function get_visits( $user_id, $domainId, $days = 30 ){
        return $this->request_google_analytics_info( $user_id, $domainId, array(), 'visits', $days, 'ga:pageviews,ga:visitors,ga:visits' );
    }
   

    public function get_visits_number( $user_id, $domainId, $days = 30 ){
        return $this->request_google_analytics_info( $user_id, $domainId, array(), 'visits_number', $days, 'ga:visits' );
    }

    public function get_top_countries( $user_id, $domainId, $days = 30 ){
        return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:country', 'sort' => '-ga:visits' ), 'top_country', $days );
    }

    public function get_visit_sources( $user_id, $domainId, $days = 30 ){
        return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:source', 'sort' => '-ga:visits' ), 'visit_sources', $days );
    }

    public function get_total_visits( $user_id, $domainId, $days = 30 ){
        return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ), 'total_visits', $days, 'ga:visits' );
    }
    public function get_total_visits_date_range( $user_id, $domainId, $startDate, $endDate ){
        return $this->request_google_analytics_info_date_range( $user_id, $domainId, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ), 'total_visits', $startDate, $endDate, 'ga:visits' );
    }

    public function get_unique_visits( $user_id, $domainId, $days = 30 ){
        return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ), 'unique_visits', $days, 'ga:visitors' );
    }

    public function get_page_per_visit( $user_id, $domainId, $days = 30 ){
        return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ), 'page_per_visit', $days, 'ga:pageviews' );
    }

    public function get_referrer_visits( $user_id, $domainId, $days = 30 ){
        return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:sourceMedium', 'sort' => '-ga:visits' ), 'referrer_visits', $days );   
    }

    public function get_search_console_data( $user_id, $domain_url, $days = 30 ){

        $startDate = date( 'Y-m-d', strtotime( '-' . $days . ' days', time() ) );
        $endDate = date( 'Y-m-d' );

        $client = $this->google_client();

        if ( ! $client || ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $service = new Google_Service_Webmasters( $client );

        $d = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();

        $d->setStartDate( $startDate );
        $d->setEndDate( $endDate );
        $d->setDimensions( array('date', 'page') );
        $d->setSearchType('web');

        $date = $service->searchanalytics->query( $domain_url, $d );

        $return = array(
            'ctr' => 0,
            'clicks' => 0,
            'impressions' => 0,
            'count'=> 1
        );

        if( $date->rows && is_array( $date->rows ) ){
            foreach ($date->rows as $key => $val) {
                $return['ctr'] += $val->ctr;
                $return['clicks'] += $val->clicks;
                $return['impressions'] += $val->impressions;
                $return['count']++;
            }
        }

        return $return;
    }

    public function get_pages_visibility( $user_id, $domain_url, $days = 30 ){

        $startDate = date( 'Y-m-d', strtotime( '-' . $days . ' days', time() ) );
        $endDate = date( 'Y-m-d' );

        $client = $this->google_client();

        if ( ! $client || ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $service = new Google_Service_Webmasters( $client );

        $d = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();

        $d->setStartDate( $startDate );
        $d->setEndDate( $endDate );
        $d->setDimensions( array('page') );
        $d->setSearchType('web');

        $pages = $service->searchanalytics->query( $domain_url, $d );

        $return = array();

        if( $pages->rows && is_array( $pages->rows ) ){
            foreach ($pages->rows as $key => $val) {
                $return[] = array(
                    'page' => $val['keys'][0],
                    'ctr' => $val['ctr'],
                    'clicks' => $val['clicks'],
                    'position' => $val['position'],
                    'impressions' => $val['impressions']
                );
            }
        }

        return $return;
    }

    public function get_domain_referrers( $user_id, $domainId, $days = 30 ){

        $return = array();

        $client = $this->google_client();

        if ( ! $client->getAccessToken() ) {
            $this->get_authenticated_user( $user_id );
        }

        $client = $this->google_client();

        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $domainId );

        $google_account_id = (int) $domainDetails['ga_account'];

        if( 0 < $google_account_id ){

            $google_account_data = $this->getUserIdByAccountId( $domainDetails['ga_account'] );

            if( ! $google_account_data || ! isset( $google_account_data['ga_code'] ) || ! $google_account_data['ga_code'] ){
                
                $return['msg'] = 'Invalid ga user account';
                return $return;
            }
        }
        else{
            $return['msg'] = 'No google account';
            return $return;
        }
        
        if( $google_account_data['ga_code'] && $google_account_data['webpropertyid'] ){

            $this->get_authenticated_user( $google_account_data['user_id'] );
            
            $analytics = new Google_Service_Analytics( $client );

            $profiles = $analytics->management_profiles->listManagementProfiles( $google_account_data['ga_code'], '~all' );

            $items = $profiles->getItems();

            if ( 0 < count( $items ) ) {

                $profileId =  $items[0]->getId();
             
                // Add Analytics View ID, prefixed with "ga:"
                $analyticsViewId = 'ga:' . $profileId;
                $startDate = date( 'Y-m-d', strtotime( '-' . $days . ' days', time() ) );
                $endDate = date( 'Y-m-d' );
                $metrics = 'ga:hits';

                $data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, 
                    array( 'dimensions' => 'ga:fullReferrer,ga:date' , 'sort' => '-ga:date' )
                );

                $items = $data->getRows();

                if( $items ) {
                
                    foreach( $items as $item ){

                        if( '(direct)' === $item[0] ){ 
                            continue;
                        }
                        
                        $return[] = array(
                            'refferer' => $item[0],
                            'date' => date( 'Y-m-d', strtotime( $item[1] ) ),
                            'clicks' => (int) $item[2],
                        );
                    }
                }
            }
        }

        return $return;
    }
}