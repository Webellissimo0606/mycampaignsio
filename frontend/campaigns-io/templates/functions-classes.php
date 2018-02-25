<?php
// Based on controller 'analytics/piwik'.
// class New_Piwik_Handler {

// 	private $ci;
// 	private $analyze_model;

// 	function __construct( $ci, $analyze_model ){
// 		$this->ci = $ci;
// 		$this->analyze_model = $analyze_model;
// 	}

// 	private function getPiwikResults( $urlParam, $limit = '' ) {
//         $apiUrl = $this->ci->config->config['piwik']['api_url'];
//         $token_auth = $this->ci->config->config['piwik']['auth_token'];
//         $url = $apiUrl . $urlParam . "&token_auth=" . $token_auth . "&format=JSON&filter_limit=" . ( $limit ? 40 : (int) $limit );
//         $arrContextOptions = array( "ssl" => array( "verify_peer" => false, "verify_peer_name" => false, ) );
//         $fetched = file_get_contents( $url, false, stream_context_create( $arrContextOptions ) );
//         $result = json_decode( $fetched, true );
//         return $result;
//     }

// 	public function getsearchengineclicks( $domainId ) {

// 		$return = array( 'status' => 'error', 'clicks' => null );

// 		$domain = $this->analyze_model->getDomain( $domainId );

// 		if ( 0 < $domain[0]->piwik_site_id ) {
			
// 			$totalClicks = 0;

// 			// Getting total visitors graph.
// 			$url = "?module=API&method=Referrers.getSearchEngines&idSite=";
// 			$url .= $domain[0]->piwik_site_id;
// 			$url .= "&period=month&date=today&showColumns=label,nb_visits";
// 			$response = $this->getPiwikResults ($url );

// 			if ( $response && isset( $response['result'] ) && 'error' !== $response['result'] ){
// 				$return['status'] = 'success';
// 				foreach($response as $res) {
// 					$totalClicks += $res['nb_visits'];
// 				}
// 			}
// 			else{
// 				$return['msg'] = $response;
// 			}

// 			$return['clicks'] = $totalClicks;
// 		}
// 		else {
// 			$return['msg'] = 'Piwik data not available';
// 		}
// 		return $return;
//     }
// }

// // Based on controller 'analytics/analytics'.
// class New_Analytics_Handler {

//     private $user_id;
//     private $domain_id;
// 	private $g_client;
// 	private $db;
// 	private $config;
// 	private $analyze_model;

// 	function __construct( $db, $config, $analyze_model ){
// 		$this->db = $db;
// 		$this->config = $config;
// 		$this->analyze_model = $analyze_model;
// 	}

// 	private function google_client(){

// 		if( ! $this->g_client ){

// 			$client_id = $this->config->config['google_oauth']['client_id'];
// 	        $client_secret = $this->config->config['google_oauth']['client_secret'];
// 	        $redirect_uri = $this->config->config['google_oauth']['redirect_uri'];
// 	        $simple_api_key = $this->config->config['google_oauth']['api_key'];

// 			$this->g_client = new Google_Client( array(
// 	          'developer_key' => $simple_api_key,
// 	          'application_name' => "PHP Google OAuth Login Example",
// 	          'client_id' => $client_id,
// 	          'client_secret' => $client_secret,
// 	          'redirect_uri' => $redirect_uri,
// 	          'access_type' => 'offline',
// 	          'approval_prompt' => 'force',
// 	        ) );
// 		}

// 		return $this->g_client;
// 	}

// 	private function getUserIdByGaCode($gaCode) {
//     	$this->db->flush_cache();
//     	$this->db->select( '*' );
//     	$this->db->from( 'user_ga_account' );
//     	$this->db->where( 'ga_code=', $gaCode );
//     	$query = $this->db->get();
//     	return $query ? $query->row_array() : false;
//     }

//     private function getUserAuthData($user_id) {
//         $this->db->select('*');
//         $this->db->where( 'user_auth.user_id=', $user_id );
//         $this->db->from( 'user_auth' );
//         $query = $this->db->get();
//         return $query->result();
//     }

//     private function updateUserAuthData($id, $data) {
//         $this->db->where( 'user_id', $id );
//         return $this->db->update( 'user_auth', $data ) ? true : NULL;
//     }

//     private function get_authenticated_user($userid) {

//         $user = array();

//         $client = $this->google_client();

//         if ( $client->getAccessToken() ) {
//             $_SESSION['token'] = $client->getAccessToken();
//             return true;
//         }
//         else {
//             $result = $this->getUserAuthData($userid);
//             $user = (array) $result;
            
//             if ( count($user) > 0 ) {

//                 if ($client->isAccessTokenExpired()) {

//                     $token = $client->refreshToken($user[0]->refresh_token);
//                     $_SESSION['token'] = $client->getAccessToken();
//                     $auth_data['access_token'] = $_SESSION['token']['access_token'];
//                     $auth_data['token_type'] = $_SESSION['token']['token_type'];
//                     $auth_data['expires_in'] = $_SESSION['token']['expires_in'];
//                     $auth_data['created'] = $_SESSION['token']['created'];
//                     $update = $this->updateUserAuthData($userid, $auth_data);
//                     if ($update) {
//                         return true;
//                     }
//                 }
//                 else {
//                     return true;
//                 }
//             }
//             else {
//                 if (isset($_GET['code'])) {
//                     $client->authenticate($_GET['code']);
//                     $_SESSION['token'] = $client->getAccessToken();
//                     if (isset($_SESSION['token'])) {
//                         $client->setAccessToken($_SESSION['token']);
//                     }
//                     $auth_data['user_id'] = $userid;
//                     $auth_data['access_token'] = $_SESSION['token']['access_token'];
//                     $auth_data['token_type'] = $_SESSION['token']['token_type'];
//                     $auth_data['expires_in'] = $_SESSION['token']['expires_in'];
//                     $auth_data['refresh_token'] = $_SESSION['token']['refresh_token'];
//                     $auth_data['created'] = $_SESSION['token']['created'];
//                     $insert = $this->analytics_model->insertUserAuthData($auth_data);
//                     if ($insert) {

//                         $access_token = $_SESSION['token']['access_token'];
//                         return true;
//                     }
//                 }
//             }
//         }
//     }

// 	public function get_google_analytics( $user_id, $domainId, $days ){

// 		$return = array(
// 			'status' => 'error',
// 			'payload' => null,
// 		);

//         $client = $this->google_client();
//         $domain = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $domainId );

//         if ( ! $client->getAccessToken() ) {

//         	$domain_ga_account = (int) $domain['ga_account'];
            
//             if( 0 < $domain_ga_account ){
	            
// 	            $ga_res = $this->getUserIdByGaCode( $domain['ga_account'] );
	            
//                 if( $ga_res ){
// 	            	$this->get_authenticated_user( $ga_res['user_id'] );
// 	            }
// 	            else{
// 	            	$return['msg'] = 'Invalid ga user account';
// 	            	return $return;
// 	            }
// 	        }
// 	        else{
// 	        	$return['msg'] = 'No ga account';
// 	        	return $return;
// 	        }
//         }
        
//         $analytics = new Google_Service_Analytics( $client );
//         $profiles = $analytics->management_profiles->listManagementProfiles( $domain['ga_account'], '~all' );

//         $items = $profiles->getItems();

//         if ( 0 < count( $items ) ) {

//           	$profileId =  $items[0]->getId();
         
//           	// Add Analytics View ID, prefixed with "ga:"
//           	$analyticsViewId = 'ga:' . $profileId;
//           	$startDate = date( 'Y-m-d', strtotime( '-' . $days . ' days', time() ) );
//           	$endDate = date( 'Y-m-d' );
//           	$metrics = 'ga:visits';
//           	$data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ) );
          
//           	$items = $data->getRows();

//           	if( $items ) {

//           		$paypload = array();
            
//             	foreach( $items as $item ){
//             		$paypload[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
//             	}
            
//             	$res['status'] = 'success';
//             	$res['payload'] = array_reverse( $paypload );
//           	}
//           	else{
//           		$return['msg'] = 'No ga views items';
//           	}
//         }
//         else{
//             $return['msg'] = 'No ga views (profiles) found for this user.';
//         }

//         return $return;
// 	}

//     public function getGaDetailByDomain($domain) {
//         $this->db->flush_cache();
//         $this->db->select('*');
//         $this->db->from('user_ga_account');
//         $this->db->where('websiteurl', $domain);
//         $query = $this->db->get();
//         return $query ? $query->row_array() : false;
//     }

//     private function request_google_analytics_info($user_id, $domainId, $request_args, $request_type = null, $metrics = 'ga:visits'){

//         $return = array( 'status' => 'error'/*, 'payload' => array()*/ );
//         $client = $this->google_client();
//         $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId( $user_id, $domainId );

//         // var_dump( $user_id );
//         // echo "<br/>";
//         // var_dump( $domainId );
//         // echo "<br/>";
//         // var_dump( $domain );
//         // echo "<br/>";

//         if ( ! $client->getAccessToken() ) {
            
//             $domain_ga_account = (int) $domainDetails['ga_account'];

//             // var_dump( $domain_ga_account );
//             // echo "<br/>";

//             if( 0 < $domain_ga_account ){
//                 $ga_res = $this->getUserIdByGaCode( $domainDetails['ga_account'] );
//                 if( $ga_res ){
//                     $this->get_authenticated_user( $ga_res['user_id'] );
//                 }
//                 else{
//                     $return['msg'] = 'Invalid ga user account';
//                     return $return;
//                 }
//             }
//             else{
//                 $return['msg'] = 'No ga account';
//                 return $return;
//             }
//         }

//         // Getting web property.
//         $domain  = strtr( $domainDetails['domain_name'], array( 'http://'=>'', 'https://' => '', 'www.' => '','/' => '' ) );
//         $property = $this->getGaDetailByDomain( $domain );

//         $webpropertyid = $property['webpropertyid'] ? $property['webpropertyid'] : '~all';

//         $analytics = new Google_Service_Analytics( $client );

//         $profiles = $analytics->management_profiles->listManagementProfiles( $domainDetails['ga_account'], $webpropertyid );
        
//         $items = $profiles->getItems();

//         if ( 0 < count( $items ) ) {

//             // Return the first view (profile) ID.
//             $profileId =  $items[0]->getId();

//             // Add Analytics View ID, prefixed with "ga:"
//             $analyticsViewId = 'ga:'.$profileId;
//             $startDate = date('Y-m-d', strtotime('-30 days', time()));
//             $endDate = date('Y-m-d');

//             $data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, $request_args );
//             $items = $data->getRows();

//             if( $items ) {
//                 $return['status'] = 'success';
//                 switch( $request_type ){
//                     case 'visits':
//                         $return['payload'] = array(
//                             'totalUniqueVisitors' => $items[0][1],
//                             'totalVisitors' => $items[0][2],
//                             'totalPagePerVisit' => $items[0][0],
//                         );
//                         break;
//                     case 'top_country':
//                         $items = array_slice( $items, 0, 15 );
//                         $topcountries = array();
//                         foreach( $items as $key => $item ){
//                             $topcountries[$key]['label'] = $item[0];
//                             $topcountries[$key]['nb_visits'] = $item[1];
//                         }
//                         $return['payload'] = array( 'topcountries' => $topcountries );
//                         break;
//                     case 'visit_sources':
//                         $items = array_slice( $items, 0, 15 );
//                         $sites = array();
//                         foreach( $items as $key => $item ){
//                             $sites[$key]['label'] = $item[0];
//                             $sites[$key]['nb_visits'] = $item[1];
//                         }
//                         $return['payload'] = array( 'sites' => $sites );
//                         break;
//                     case 'total_visits':
//                         $totalVisitsGraph = array();
//                         foreach( $items as $item ){
//                             $totalVisitsGraph[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
//                         }
//                         $return['payload'] = array( 'totalVisitsGraph' => array_reverse( $totalVisitsGraph ) );
//                         break;
//                     case 'unique_visits':
//                         $uniqueVisitorsGraph = array();
//                         foreach( $items as $item ){
//                             $uniqueVisitorsGraph[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
//                         }
//                         $return['payload'] = array( 'uniqueVisitorsGraph' => array_reverse( $uniqueVisitorsGraph ) );
//                         break;
//                     case 'page_per_visit':
//                         $totalPagePerVisitGraph = array();
//                         foreach( $items as $item ){
//                             $totalPagePerVisitGraph[ date( 'Y-m-d', strtotime( $item[0] ) ) ] = (int) $item[1];
//                         }
//                         $return['payload'] = array( 'totalPagePerVisitGraph' => array_reverse( $totalPagePerVisitGraph ) );
//                         break;
//                     case 'referrer_visits':
//                         $direct_total = 0;
//                         $organic_total = 0;
//                         $referral_total = 0;
//                         foreach( $items as $key => $item ){
//                             $label = '';
//                             if( preg_match( '/organic/i', $item[0] ) ){
//                                 $label = 'organic';
//                                 $organic_total += $item[1];
//                             }
//                             if(preg_match('/none/i', $item[0])){
//                                 $label = 'direct';
//                                 $direct_total += $item[1];
//                             }
//                             if( preg_match( '/referral/i', $item[0] ) ){
//                                 $label = 'referral';
//                                 $referral_total += $item[1];
//                             }
//                         }
//                         $return['payload'] = array(
//                             'referrervisit' => array(
//                                 array( 'label' => 'direct','nb_visits' => $direct_total ),
//                                 array( 'label' => 'referral','nb_visits' => $referral_total),
//                                 array( 'label' => 'organic','nb_visits' => $organic_total ),
//                             )
//                         );

//                         $startDate = date('Y-m-d',strtotime('-365 days', time()));
//                         $endDate = date('Y-m-d');
//                         $metrics = 'ga:visits';
//                         $data = $analytics->data_ga->get( $analyticsViewId, $startDate, $endDate, $metrics, array( 'dimensions' => 'ga:sourceMedium,ga:date', 'sort' => '-ga:date' ) );

//                         $items = $data->getRows();

//                         if($items) {

//                             $res = array();
//                             $organic_total = 0;
//                             $referral_total = 0;
//                             $direct_total = 0;
//                             foreach($items as $key=>$item){
//                                 $label = '';
//                                 if(preg_match('/organic/i', $item[0])){
//                                     $label = 'organic';
//                                     $res[date('Y-m-01',strtotime($item[1]))]['organic']+=$item[2];
//                                 }
//                                 if(preg_match('/none/i', $item[0])){
//                                     $label = 'direct';
//                                     $res[date('Y-m-01',strtotime($item[1]))]['direct']+=$item[2];
//                                 }
//                                 if(preg_match('/referral/i', $item[0])){
//                                     $label = 'referral';
//                                     $res[date('Y-m-01',strtotime($item[1]))]['referral']+=$item[2];
//                                 }
//                             }

//                             $referrervisitgraph = array();
//                             foreach($res as $key=>$temp){
//                                 $referrervisitgraph[$key][0] = array('label'=>'direct', 'nb_visits'=>$temp['direct']);
//                                 $referrervisitgraph[$key][1] = array('label'=>'organic', 'nb_visits'=>$temp['organic']);
//                                 $referrervisitgraph[$key][2] = array('label'=>'referral', 'nb_visits'=>$temp['referral']);
//                             }

//                             $return['payload']['referrervisitgraph'] = array_reverse( $referrervisitgraph );
//                         }
//                         else {
//                             $return['status'] = 'error';
//                             $return['payload']['referrervisitgraph'] = null;
//                         }
//                         break;
//                 }
//             }
//             else{
//                 $return['msg'] = 'No ga views items';
//             }
//         }
//         else{
//             $return['msg'] = 'No ga views (profiles) found for this user.';
//         }
//         return $return;
//     }

//     public function get_visits( $user_id, $domainId ){
//         return $this->request_google_analytics_info( $user_id, $domainId, array(), 'visits', 'ga:pageviews,ga:visitors,ga:visits' );
//     }

//     public function get_top_countries( $user_id, $domainId ){
//         return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:country', 'sort' => '-ga:visits' ), 'top_country' );
//     }

//     public function get_visit_sources( $user_id, $domainId ){
//         return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:source', 'sort' => '-ga:visits' ), 'visit_sources' );
//     }

//     public function get_total_visits( $user_id, $domainId ){
//         return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ), 'total_visits', 'ga:visits' );
//     }

//     public function get_unique_visits( $user_id, $domainId ){
//         return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ), 'unique_visits', 'ga:visitors' );
//     }

//     public function get_page_per_visit( $user_id, $domainId ){
//         return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:date', 'sort' => '-ga:date' ), 'page_per_visit', 'ga:pageviews' );
//     }

//     public function get_referrer_visits( $user_id, $domainId ){
//         return $this->request_google_analytics_info( $user_id, $domainId, array( 'dimensions' => 'ga:sourceMedium', 'sort' => '-ga:visits' ), 'referrer_visits' );   
//     }
// }

// Based on controller method 'auth/analyze/getkeywordreport'.
// function just_get_keyword_report( $analyze_model, $user_id, $domain_id ){
//     return array(
//         'avg_position' => $analyze_model->getAveragePosition($user_id, $domain_id),
//         'keyword_changes' => $analyze_model->getKeywordChangeFromWeeks($user_id, $domain_id),
//         'position' => $analyze_model->getKeywordPositionStats($user_id, $domain_id),
//         'total_keywords' => $analyze_model->getTotalKeywords($user_id, $domain_id)
//     );
// }

// // Based on controller method 'auth/uptimestats'.
// function just_domain_uptime_stats( $domainId, $analyze_model ){
//     $domain = $analyze_model->getDomain($domainId);
//     $data['domain']                = $domain[0];
//     $data['uptime1daypercentage']  = $analyze_model->uptimePercentageInDays($domainId, 1);
//     $data['uptime7daypercentage']  = $analyze_model->uptimePercentageInDays($domainId, 7);
//     $data['uptime30daypercentage'] = $analyze_model->uptimePercentageInDays($domainId, 30);
//     $data['totaluptimehours']      = $analyze_model->totalUptimeByDomainId($domainId);
//     $data['uptimedaystats']        = $analyze_model->uptimeDayStatsByDomainId($domainId);
//     $data['current_date']          = date('nS M Y');
//     return $data;
// }

// // Based on controller method 'auth/uptimestats'.
// function just_gtmetrix_data( $gtmetrix = null, $analyze_model, $ci_auth, $session ){
	
// 	$return['status'] = false;
// 	$return['metrix'] = null;
	
// 	if ( $gtmetrix ) {
		
// 		$results = $analyze_model->get_gtme( $gtmetrix );
		
// 		if ( ! $results ) {
			
// 			$data = array();
// 			$user_id = $ci_auth->get_user_id();
// 			$date = date('Y-m-d H:i:s');
// 			$test = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
// 			$url_to_test = $gtmetrix;
			
// 			$testid = $test->test( array( 'url' => $url_to_test ) );

// 			$test->get_results();
// 			$gtmetrix = $test->results();
// 			$data['image'] = $gtmetrix['report_url'] . "/screenshot.jpg";
// 			$data['datas'] = json_encode( $gtmetrix );
// 			$results = $analyze_model->update_gtmet_domain( $data, $url_to_test );

// 			$count = array();
// 			$count['user_id'] = $ci_auth->get_user_id();
// 			$count['domain_id'] = $session->userdata('domainId');
// 			$count['api_requested'] = date('Y-m-d H:i:s');
// 			$user = 0;
// 			$result_count = $analyze_model->update_gtmet_count( $count, $user );

// 			$results = $analyze_model->get_gtme( $gtmetrix );
// 		}
// 		else {
// 			$url = $gtmetrix;
// 			$results = $analyze_model->get_gtme( $url );
// 		}

// 		if ($results) {
// 			$return['status'] = true;
// 			$return['metrix'] = $results->datas;
// 			$return['date']   = date_format( date_create( $results->created ), "l, F d, Y H:i:s A" );
// 		}
// 	}
	
// 	return $return;
// }

// Based on controller method 'auth/gtmetrix'.
/*function login_form_messages( $errors = array(), $success = '', $message = '', $break_top = true ){

    if( ! empty( $errors ) ) { ?>
        <?php if( $break_top ){ ?><br/><?php } ?>
        <div class="msg msg-danger"> <?php
            foreach($errors as $error) {
                echo 0 === strpos(trim($error), "<p") ? $error : '<p>' . $error . '</p>';
            } ?>
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div> <?php
    }

    if( '' !== $success ) { ?>
        <?php if( $break_top ){ ?><br/><?php } ?>
        <div class="msg msg-success" role="alert">
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo 0 === strpos(trim($success), "<p") ? $success : '<p>' . $success . '</p>'; ?>
        </div> <?php
    }

    if( '' !== $message ) { ?>
        <?php if( $break_top ){ ?><br/><?php } ?>
        <div class="msg msg-success" role="alert">
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo 0 === strpos(trim($message), "<p") ? $message : '<p>' . $message . '</p>'; ?>
        </div> <?php
    }
}*/

/*function login_form_captcha($use_recaptcha = false, $config = null, $captcha_html = '', $captcha = ''){

    if( null === $config ){ return; }

    // Google Recaptcha Part.
    if ( null !== $use_recaptcha && $use_recaptcha ) { ?>
        <div class="g-recaptcha-wrapper">
            <div class="g-recaptcha" data-size="normal" data-sitekey="<?php echo $config->item('recaptcha_sitekey'); ?>" style="transform:scale(0.88); transform-origin:0; -webkit-transform:scale(0.88); transform:scale(0.88); -webkit-transform-origin:0 0; transform-origin:0 0; margin-left:5.5%;">
            </div>
        </div><?php
    }
    else { ?>
        <div>
            <label>Enter the code as it appears:</label>
            <?php echo $captcha_html; ?>
        </div>
        <div>
            <label>Confirmation Code</label>
            <?php echo form_input($captcha); ?>
        </div> <?php
    }
}*/

// function user_profile_data($db_ins = null, $user_id = 0){
//     $user_id = (int) $user_id;
//     $user_id = $user_id ? $user_id : -1;
//     $query  = $db_ins->query( "SELECT * FROM user_profiles WHERE user_id='" . $user_id . "' LIMIT 1" );
//     return $query->row();
// }

// function gravatar_thumb($email = null, $size = 56){
//     if( null === $email){ return ''; }
    
//     $default_thumb = site_url().'uploads/images/profile.jpg';
    
//     // Alternative default thumb.
//     // $default_thumb = base_url() . 'frontend/site/default/new-ui/img/user-thumb.png';
    
//     return "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($default_thumb)."&s=".$size;
// }

/*function checkbox_component($name='', $checked=false){ 
    $id = '' !== $name ? 'id-' . $name : 'tmp-id-' . substr(uniqid(), -4);
    ?>
    <div class="optio-check-component">
        <input id="<?php echo $id; ?>" class="optio-check" type="checkbox" name="<?php echo $name; ?>" <?php echo $checked ? 'checked' : ''; ?> value="1"/>
        <label for="<?php echo $id; ?>" class="optio-check-btn"></label>
    </div> <?php
}*/

// function domain_stats_curl_request( $url, $args ){
//     $ch = curl_init();
//     curl_setopt($ch,CURLOPT_URL, $url);
//     curl_setopt($ch,CURLOPT_POST, count($args));
//     curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($args));
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//     curl_setopt($ch, CURLOPT_HEADER, 0);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     $result = curl_exec($ch);
//     curl_close($ch);

//     return ! empty( $result ) ? json_decode( $result, true ) : array();
// }

// function format_bytes($bytes=0) {

//     $sizes = array('Bytes', 'KB', 'MB', 'GB', 'TB');
//     $ret = array( 'val' => null, 'unit' => null );
    
//     if ( 0 === $bytes ){
//         $ret = array( 'val' => 0, 'unit' => 'Byte' );
//     }
//     else{
//         $i = (int) ( floor( log( $bytes ) / log(1024) ) );
//         $ret = array(
//             'val' => round( $bytes / pow(1024, $i), 2 ),
//             'unit' => $sizes[$i]
//         );
//     }    
//     return $ret;
// }

// function get_grade($score){
//     $grade = '';
//     if ($score > 89) {
//         $grade = 'A';
//     } else if ($score > 79 && $score <= 89) {
//         $grade = 'B';
//     } else if ($score > 69 && $score <= 79) {
//         $grade = 'C';
//     } else if ($score > 59 && $score <= 69) {
//         $grade = 'D';
//     } else if ($score > 49 && $score <= 59) {
//         $grade = 'E';
//     } else if ($score > 39 && $score <= 49) {
//         $grade = 'F';
//     } else if ($score > 29 && $score <= 39) {
//         $grade = 'G';
//     } else if ($score > 19 && $score <= 29) {
//         $grade = 'H';
//     } else if ($score > 9 && $score <= 19) {
//         $grade = 'I';
//     } else {
//         $grade = 'J';
//     }
//     return $grade;
// }







/* ============================== // ============================== */

/*function validate_api_response($args){

    $package = RSA_Handler::decrypt( base64_decode( $args['package'] ) );
    $signature = base64_decode( $args['signature'] );
    $verified_signature =  RSA_Handler::verify( $package, $signature );

    if( ! $verified_signature ){
        die('Invalid response data');   // TODO: ....
    }

    $package = json_decode( $package, true );
    
    return $package;
}*/

/*class RSA_Handler{

    private static $rsa;
    private static $public_key;
    private static $private_key;

    private static function init(){
        if( null !== self::$rsa && null !== self::$public_key && null !== self::$private_key ){ return; }
        if( ! class_exists('Crypt_RSA', false) ){ require_once APPPATH. 'third_party/phpseclib/Crypt/RSA.php'; }
        
        // require APPPATH . 'modules/auth/controlles/';

        // self::$private_key = file_get_contents( dirname(__FILE__) . '/private_key_1');
        // self::$public_key = file_get_contents( dirname(__FILE__) . '/public_key_2');

        self::$private_key = file_get_contents( APPPATH . 'modules/auth/controllers/private_key_1');
        self::$public_key = file_get_contents( APPPATH . 'modules/auth/controllers/public_key_2');

        self::$rsa = new Crypt_RSA();
        self::$rsa->setHash("sha256");
        self::$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);

        // Creates and display in screen new apir keys. Only for debugging.
        // $a = self::$rsa->createKey();
        // echo '<textarea cols="80" rows="20">' . $a['privatekey'] . '</textarea>';
        // echo '<textarea cols="80" rows="20">' . $a['publickey'] . '</textarea>';
        // die();
    }

    public static function sign($package){
        self::init();
        self::$rsa->loadKey( self::$private_key ); 
        return base64_encode( self::$rsa->sign($package) );
    }

    public static function verify($package, $signature){
        self::init();
        self::$rsa->loadKey( self::$public_key );
        $signature = base64_decode($signature);
        return self::$rsa->verify($package, $signature);
    }

    public static function encrypt($package){
        self::init();
        self::$rsa->loadKey( self::$public_key );
        return base64_encode( self::$rsa->encrypt($package) );
    }

    public static function decrypt($package){
        self::init();
        self::$rsa->loadKey( self::$private_key ); 
        return self::$rsa->decrypt(base64_decode($package));
    }
}

class WP_Request_Access{

    private $session;
    private $ci_auth;

    private $site = array( 'id' => null, 'url' => null, 'admin' => array( 'url' => null, 'username' => null ) );

    private $endpoints_url = array(
        'entry' => 'wp-json/wp-management-controller/entry',
        'authorize' => 'wp-json/wp-management-controller/authorize',
        'tokens' => 'wp-json/wp-management-controller/tokens',
        'refresh_tokens' => 'wp-json/wp-management-controller/refresh_tokens',
        'access' => 'wp-json/wp-management-controller/access',
        'updates' => 'wp-json/wp-management-controller/updates',
        'update_now' => 'wp-json/wp-management-controller/update_now',
    );

    private $tokens = array(
        'access' => null,
        'refresh' => null,
        'refresh_expires' => null
    );

    private $sessions_options = array(
        'action_on_authorization_complete' => array( 'name' => null, 'expires' => null ),
        'request_tokens_on_client_authorization_response' => array( 'name' => null, 'expires' => null ),
        'request_client_authorization_security_arg' => array( 'name' => null, 'expires' => null ),
        'request_client_tokens_security_arg' => array( 'name' => null, 'expires' => null )
    );

    private $client = array(
        'id' => null,
        'secret' => null,
    );

    private $code = null;

    function __construct( $wp_site = null, $session = null, $ci_auth = null ){

        $this->session = $session;
        $this->ci_auth = $ci_auth;
        
        $this->site = array(
            'id' => $wp_site['id'],
            'url' => $wp_site['data']->domain_name,
            'admin' => array(
                'url' => $wp_site['data']->adminURL,
                'username' => $wp_site['data']->adminUsername
            )
        );

        // $this->site['admin']['username'] = 'admin';                 // TODO: REMOVE IT! Only for debbuging.
        // $this->site['url'] = 'http://localhost/wp-testing-env/';   // TODO: REMOVE IT! Only for debbuging.

        foreach ($this->endpoints_url as $key => $val) {
            $this->endpoints_url[$key] = add_url_scheme( rtrim( rtrim( $this->site['url'], "\/" ), "\\" ) . '/' . $val );
        }

        $this->sessions_options = array(
            'action_on_authorization_complete' => array(
                'name' => 'action_on_authorization_complete['.$this->site['id'].']',
                'expires' => 120,
            ),
            'request_tokens_on_client_authorization_response' => array(
                'name' => 'request_tokens_on_client_authorization_response['.$this->site['id'].']',
                'expires' => 30,
            ),
            'request_client_authorization_security_arg' => array(
                'name' => 'request_client_authorization_security_arg['.$this->site['id'].']',
                'expires' => 30,
            ),
            'request_client_tokens_security_arg' => array(
                'name' => 'request_client_tokens_security_arg['.$this->site['id'].']',
                'expires' => 30,
            ),
        );
    }

    private function set_session( $id, $value ){
        $this->session->set_tempdata( $this->sessions_options[$id]['name'], $value, $this->sessions_options[$id]['expires'] );
    }

    private function get_session( $id ){
        return $_SESSION[ $this->sessions_options[$id]['name'] ];
    }

    private function delete_session( $id ){
        unset( $_SESSION[ $this->sessions_options[$id]['name'] ] );
    }

    private function delete_all_sessions(){
        foreach ($this->sessions_options as $val) {
            unset( $_SESSION[ $val['name'] ] );
        }
    }

    private function send_post_request( $url, $args, $inc_security_arg = true ){

        $package_plaintext = json_encode( $args );
        $send_signature = base64_encode( RSA_Handler::sign( $package_plaintext ) );
        $send_package = base64_encode( RSA_Handler::encrypt( $package_plaintext ) );

        $curl_args = array(
            'signature' => $send_signature,
            'package' => $send_package
        );

        $response = execute_curl_post( $url, $curl_args );

        // print_r( $url );
        // echo '<br/><br/>';
        // print_r( $args );
        // echo '<br/><br/>';
        // print_r( $curl_args );
        // echo '<br/><br/>';
        // print_r( $response );
        // var_dump( $url . '?' . http_build_query($args) );
        // die();

        if( 200 !== $response['status'] ){
            return array(
                'error' => 'unreachable',
                'msg' => 'Website is not accessible or plugin "WordPress Multisite Controller" is not activated.',
            );
            // die('Website is not accessible or plugin "WordPress Multisite Controller" is not activated.');
        }

        $results = validate_api_response( $response['result'] );

        // print_r( $results );
        // die();
        // echo '++++++++++++++++++++++++++++++++++++++++++++';
        // var_dump( date( "Y-m-d G:i:s", $results['refresh_expires'] ) );
        // var_dump( date( "Y-m-d G:i:s", time() ) );

        if( $inc_security_arg ){
            $results['site_id'] = isset($results['site_id']) &&  $results['site_id'] ? (int) $results['site_id'] : 0;
            $results['security_arg'] = isset($results['security_arg']) && $results['security_arg'] ? $results['security_arg'] : null;
            $results['security_arg'] = null !== $results['security_arg'] && strpos( $results['security_arg'], '.html' ) === strlen($results['security_arg']) - 5 ? substr($results['security_arg'], 0, -5) : $results['security_arg'];
        }

        return $results;
    }

    private function send_redirection_request( $url, $args ){

        $package_plaintext = json_encode( $args );
        $send_signature = base64_encode( RSA_Handler::sign( $package_plaintext ) );
        $send_package = base64_encode( RSA_Handler::encrypt( $package_plaintext ) );

        $curl_args = array(
            'signature' => $send_signature,
            'package' => $send_package
        );

        redirect( $url . '?signature=' . $send_signature . '&package=' . $send_package );
        exit;
    }

    private function access_login(){
        $params = array(
            'action' => 'login',
            'site_id' => $this->site['id'],
            'request_url' => $this->endpoints_url['access'],
            'access_token' => $this->tokens['access'],
            'username' => $this->site['admin']['username'],
            'invalid_redirect' => site_url( 'auth/site/viewsite_invalid_access' )
        );

        $this->send_redirection_request( $this->endpoints_url['access'], $params );
    }

    private function request_updates($updates_type, $count_only, $action_postfix = '', $return_values = false){
        
        $params = array(
            'action' => 'updates' . $action_postfix,
            'type' => $updates_type,
            'count' => 1 === (int) $count_only ? 'true' : 'false',
            'site_id' => $this->site['id'],
            'request_url' => $this->endpoints_url['updates'],
            'access_token' => $this->tokens['access'],
            'username' => $this->site['admin']['username'],
            'invalid_redirect' => site_url( 'auth/site/viewsite_invalid_access' )
        );

        $results = $this->send_post_request( $this->endpoints_url['updates'], $params, false );

        if( isset( $results['error'] ) ){

            $error = isset($results['error']) && $results['error'] ? $results['error'] : null;         

            switch($error){
                case 'unreachable':
                    if( $return_values ){
                        return $results;
                    }

                    header('Content-Type: application/json');
                    print_r( json_encode($results) );
                    exit;
                    break;
                case 'invalid-access':
                    $site_id = isset($results['site_id']) && $results['site_id'] ? $results['site_id'] : null;
                    $request_url = isset( $results['request_url'] ) && $results['request_url'] ? $results['request_url'] : null;
                    $request_action = isset( $results['request_action'] ) && $results['request_action'] ? $results['request_action'] : null;

                    if( $this->site['id'] !== $site_id || $this->endpoints_url['updates'] !== $request_url || ( 'updates' !== $request_action && 'updates-2' !== $request_action ) ){
                        die('Error - Updates request #1');
                    }

                    $this->request_tokens();
                    if( 'updates-2' === $request_action ){
                        die('Error - Updates request #2');
                    }

                    if( $return_values ){
                        return $this->request_updates( $updates_type, $count_only, '-2', $return_values );
                    }

                    $this->request_updates( $updates_type, $count_only, '-2', $return_values ); // Add prefix into action name to avoid infinity loop in case of error.
                    exit;
            }

            die('Error - Updates request #3');

        }
        else{

            if( $return_values ){
                return $results;
            }

            header('Content-Type: application/json');
            print_r( json_encode($results) );
            exit;
        }
    }

    private function request_update_now( $updates = array() ){
        $params = array(
            'action' => 'update_now',
            'data' => $updates,
            'site_id' => $this->site['id'],
            'request_url' => $this->endpoints_url['update_now'],
            'access_token' => $this->tokens['access'],
            'username' => $this->site['admin']['username'],
            'invalid_redirect' => site_url( 'auth/site/viewsite_invalid_access' )
        );
        return $this->send_post_request( $this->endpoints_url['update_now'], $params, false );
    }

    private function on_authorization_begin($action_on_complete = null){
        if( null !== $action_on_complete ){
            $this->set_session('action_on_authorization_complete', $action_on_complete);
        }
    }

    private function save_tokens(){

        $updateData = array(
            'client_id'      => $this->client['id'],
            'client_secret'      => $this->client['secret'],
            'access_token'      => $this->tokens['access'],
            'refresh_token'     => $this->tokens['refresh'],
            'access_expires'    => $this->tokens['refresh_expires']
        );

        update_auth_data( $updateData, $this->site['id'], $this->ci_auth->ci );
    }

    private function on_authorization_complete(){

        $action = $this->get_session('action_on_authorization_complete');

        $this->delete_all_sessions();

        switch( $action ){
            case 'login':
                $this->access_login();
                break;
        }
    }

    private function new_authorization(){
        
        $this->client = $this->request( 'client_credentials' );

        if ( null === $this->client['id'] || null === $this->client['secret'] ) {
            die('Client ERROR');    // TODO: ...!
        }

        update_client_data( array('client_id'=> $this->client['id'], 'client_secret' => $this->client['secret'] ), $this->site['id'], $this->ci_auth->ci );

        if( 'yes' === $this->get_session('request_tokens_on_client_authorization_response') ){
            
            $this->delete_session('request_tokens_on_client_authorization_response');
            
            $this->code = $this->request( 'client_code' );
            $this->tokens = null !== $this->code ? $this->request( 'client_tokens' ) : $this->tokens;
        }
    }

    private function refresh_authorization(){
        $this->tokens = $this->request( 'client_refresh_tokens' );
        $this->save_tokens();
    }

    private function request( $type ){
        switch($type){
            case 'client_credentials':
                return $this->get_client_credentials();
                break;
            case 'client_code':
                return $this->get_client_code();
                break;
            case 'client_tokens':
                return $this->get_client_tokens();
                break;
            case 'client_refresh_tokens':
                return $this->get_client_refresh_tokens();
                break;
        }
    }

    private function get_client_credentials(){

        $this->set_session('request_tokens_on_client_authorization_response', 'yes');
        
        $security_arg = sha1( uniqid( mt_rand(), true ) );
        
        $this->set_session('request_client_authorization_security_arg', $security_arg);

        $url = $this->endpoints_url['entry'];
        
        $url_params = array(
            'site_id' => $this->site['id'],
            'security_arg' => $security_arg,
            'username' => $this->site['admin']['username'],
        );

        $results = $this->send_post_request( $url, $url_params );

        if( $results['security_arg'] !== $_SESSION['request_client_authorization_security_arg[' . $results['site_id'] . ']'] ){
            die("ERROR 1!!!");   // TODO: Handle error...!
        }

        $this->delete_session('request_client_authorization_security_arg');

        return array(
            'id' => isset($results['client_id']) && '' !== trim($results['client_id']) ? $results['client_id'] : null,
            'secret' => isset($results['client_secret']) && '' !== trim($results['client_secret']) ? $results['client_secret'] : null,
        );
    }

    private function get_client_code(){

        $security_arg = sha1(uniqid(mt_rand(), true));
        $this->set_session('request_client_tokens_security_arg', $security_arg);

        $url = $this->endpoints_url['authorize'];
        
        $url_params = array(
            'site_id' => $this->site['id'],
            'security_arg' => $security_arg,
            'client_id' => $this->client['id']
        );

       $results = $this->send_post_request( $url, $url_params );

        if( $results['security_arg'] !== $_SESSION['request_client_tokens_security_arg[' . $results['site_id'] . ']'] ){
            die("ERROR 2!!!");    // TODO: Handle error...!
        }

        return isset( $results['code'] ) &&  $results['code'] ? $results['code'] : null;
    }

    private function get_client_tokens(){

        $results = $this->send_post_request( $this->endpoints_url['tokens'], array(
            'site_id' => $this->site['id'],
            'security_arg' => $this->get_session('request_client_tokens_security_arg'),
            'code' => $this->code,
            'client_id' => $this->client['id'],
            'client_secret' => $this->client['secret'],
        ));

        if( $results['security_arg'] !== $_SESSION['request_client_tokens_security_arg[' . $results['site_id'] . ']'] ){
            die("ERROR 3!!!");    // TODO: Handle error...!
        }

        $this->delete_session('request_client_tokens_security_arg');

        $access_token = isset( $results['access'] ) && '' !== trim( $results['access'] ) ? $results['access'] : null;
        $refresh_token = isset( $results['refresh'] ) && '' !== trim( $results['refresh'] ) ? $results['refresh'] : null;
        $refresh_expires = isset( $results['refresh_expires'] ) && 0 < $results['refresh_expires'] && time() < $results['refresh_expires'] ? $results['refresh_expires'] : null;

        if( null === $access_token || null === $refresh_token || null === $refresh_expires ){
            // var_dump($access_token);
            // var_dump($refresh_token);
            // var_dump($refresh_expires);
            die('Error tkn!'); // TODO: Handle error...!
        }

        $refresh_expires = $refresh_expires - 60;   // Dicrease expiration time by 60 seconds, to have some more time in any case need (eg. calculations).
        $current_time = time();
        $refresh_expires = $refresh_expires > $current_time ? $refresh_expires : $current_time;

        return array(
            'access' => $access_token,
            'refresh' => $refresh_token,
            'refresh_expires' => date( "Y-m-d G:i:s", $refresh_expires ),
        );
    }

    private function get_client_refresh_tokens(){

        $security_arg = sha1(uniqid(mt_rand(), true));
        $this->set_session('request_client_tokens_security_arg', $security_arg);    // TODO: Used twice same session name.

        $results = $this->send_post_request( $this->endpoints_url['refresh_tokens'], array(
            'site_id' => $this->site['id'],
            'security_arg' => $security_arg,
            'client_id' => $this->client['id'],
            'client_secret' => $this->client['secret'],
            'refresh_token' => $this->tokens['refresh'],
        ));

         if( $results['security_arg'] !== $_SESSION['request_client_tokens_security_arg[' . $results['site_id'] . ']'] ){
            die("ERROR+++");    // TODO: Handle error...!
        }

        $this->delete_session('request_client_tokens_security_arg');

        $error = isset( $results['error'] ) && '' !== trim( $results['error'] ) ? $results['error'] : null;

        if( 'invalid-client' === $error ){
            
            $this->new_authorization();

            return array(
                'access' => $this->tokens['access'],
                'refresh' => $this->tokens['refresh'],
                'refresh_expires' => $this->tokens['refresh_expires']
            );
        }
        else if( 'unreachable' === $error ){
            if( isset( $results['msg'] ) && '' !== trim( $results['msg'] ) ){
                die( $results['msg'] );
            }
            die('Error: Unreachable');
        }
        else{

            $access_token = isset( $results['access'] ) && '' !== trim( $results['access'] ) ? $results['access'] : null;
            $refresh_token = isset( $results['refresh'] ) && '' !== trim( $results['refresh'] ) ? $results['refresh'] : null;
            $refresh_expires = isset( $results['refresh_expires'] ) && 0 < $results['refresh_expires'] && time() < $results['refresh_expires'] ? $results['refresh_expires'] : null;

            if( null === $access_token || null === $refresh_token || null === $refresh_expires ){
                die('Error - Invalid response!'); // TODO: Handle error...!
            }
        }
        
        return array(
            'access' => $access_token,
            'refresh' => $refresh_token,
            'refresh_expires' => date( "Y-m-d G:i:s", $refresh_expires - 60 ),  // Dicrease expiration time by 60 seconds, to have some more time in any case need (eg. calculations).
        );
    }

    public function request_tokens($action_on_complete = null){
        $this->on_authorization_begin($action_on_complete);
        $this->new_authorization();
        $this->save_tokens();
        $this->on_authorization_complete();
    }

    public function refresh_tokens($wp_site, $action_on_complete = null){

        $this->client = array(
            'id' => $wp_site['data']->client_id,
            'secret' => $wp_site['data']->client_secret,
        );

        $this->tokens = array(
            'access' => $wp_site['data']->access_token,
            'refresh' => $wp_site['data']->refresh_token,
            'refresh_expires' => $wp_site['data']->access_expires
        );

        $this->on_authorization_begin($action_on_complete);
        $this->refresh_authorization();
        $this->on_authorization_complete();
    }

    public function login($wp_site){
        $this->tokens = array(
            'access' => $wp_site['data']->access_token,
            'refresh' => $wp_site['data']->refresh_token,
            'refresh_expires' => $wp_site['data']->access_expires
        );
        $this->access_login();
    }

    public function updates($wp_site, $updates_type, $count_only, $return_values = false){
        $this->tokens = array(
            'access' => $wp_site['data']->access_token,
            'refresh' => $wp_site['data']->refresh_token,
            'refresh_expires' => $wp_site['data']->access_expires
        );

        if( $return_values ){
            return $this->request_updates($updates_type, $count_only, '', $return_values);
        }
        $this->request_updates($updates_type, $count_only, '', $return_values);
    }

    public function update_now( $wp_site, $updates ){
        $this->tokens = array(
            'access' => $wp_site['data']->access_token,
            'refresh' => $wp_site['data']->refresh_token,
            'refresh_expires' => $wp_site['data']->access_expires
        );
        return $this->request_update_now($updates);
    }
}*/

/* ============================== // ============================== */