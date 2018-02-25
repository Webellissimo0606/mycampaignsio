<?php
include_once 'functions/html-elements.php';
include_once 'functions/html-components.php';
include_once 'functions/login-register-forms.php';

function format_user_fullname( $firstname = '', $lastname = '' ){
    $fullname = $firstname ? $firstname : '';
    $fullname .= $lastname ? ' ' . $lastname : '';
    return '' !== $fullname ? $fullname : '--';
}

function enqueue_html_stylesheets( $styles = array() ){
    if( ! empty( $styles ) ){
        foreach ( $styles as $key => $style ) {
            $link = '<link ';       
            foreach ($style as $attr => $val) { $link .= $attr . '="' . $val . '" '; }      
            $link .= '/>';
            echo $link;
        }
    }
}

function enqueue_html_scripts( $scripts = array() ){
    if( ! empty( $scripts ) ){
        foreach ( $scripts as $key => $style ) {
            $link = '<script ';
            foreach ($style as $attr => $val) { $link .= $attr . '="' . $val . '" '; }
            $link .= '></script>';
            echo $link;
        }
    }   
}

if( ! function_exists('user_profile_data') ){
    function user_profile_data( $db_ins = null, $user_id = 0 ){
        
        $user_id = (int) $user_id;
        $user_id = $user_id ? $user_id : -1;
        
        $query  = $db_ins->query( "SELECT * FROM user_profiles WHERE user_id='" . $user_id . "' LIMIT 1" );
        $return = $query->row();

        $query  = $db_ins->query( "SELECT email FROM users WHERE id='" . $user_id . "' LIMIT 1" );
        $email_row = $query->row_array();
        $email = isset( $email_row['email'] ) ? $email_row['email'] : null;

        $return->email = $email;

        return $return;
    }
}

// Based on controller method 'auth/analyze/getkeywordreport'.
function just_get_keyword_report( $analyze_model, $user_id, $domain_id ){
    return array(
        'avg_position' => $analyze_model->getAveragePosition($user_id, $domain_id),
        'keyword_changes' => $analyze_model->getKeywordChangeFromWeeks($user_id, $domain_id),
        'position' => $analyze_model->getKeywordPositionStats($user_id, $domain_id),
        'total_keywords' => $analyze_model->getTotalKeywords($user_id, $domain_id)
    );
}

// Based on controller method 'auth/uptimestats'.
function just_domain_uptime_stats( $domainId, $analyze_model ){

    $domain = $analyze_model->getDomain($domainId);
    $data['domain']                = $domain[0];
    $data['uptime1daypercentage']  = $analyze_model->uptimePercentageInDays($domainId, 1);
    $data['uptime7daypercentage']  = $analyze_model->uptimePercentageInDays($domainId, 7);
    $data['uptime30daypercentage'] = $analyze_model->uptimePercentageInDays($domainId, 30);
    $data['totaluptimehours']      = $analyze_model->get_domain_uptime($domainId);
    $data['uptimedaystats']        = $analyze_model->uptimeDayStatsByDomainId($domainId);
    $data['current_date']          = date('nS M Y');

    return $data;
}

// Based on controller method 'auth/uptimestats'.
function just_gtmetrix_data( $domain_id, $domain_url = null, $analyze_model, $ci_auth, $session, $force_update = false ){
	
	$return['status'] = false;
	$return['metrix'] = null;
	
	if ( $domain_url ) {
		
		$results = $force_update ? null : $analyze_model->get_gtme( $domain_url );
		
		if ( $force_update || ! $results ) {
			
			$data = array();
			$user_id = $ci_auth->get_user_id();
			$date = date('Y-m-d H:i:s');
			$test = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
			
			$testid = $test->test( array( 'url' => $domain_url ) );

			$test->get_results();
			$gtmetrix = $test->results();
			$data['image'] = $gtmetrix['report_url'] . "/screenshot.jpg";
			$data['datas'] = json_encode( $gtmetrix );
			$results = $analyze_model->update_gtmet_domain( $data, $domain_url );

			$count = array();
			$count['user_id'] = $ci_auth->get_user_id();
			$count['domain_id'] = $domain_id;
			$count['api_requested'] = date('Y-m-d H:i:s');
			$user = 0;
			$result_count = $analyze_model->update_gtmet_count( $count, $user );

			$results = $analyze_model->get_gtme( $domain_url );
		}

		if ($results) {
			$return['status'] = true;
			$return['metrix'] = $results->datas;
			$return['date']   = date_format( date_create( $results->created ), "l, F d, Y H:i:s A" );
		}
	}
	
	return $return;
}

if( ! function_exists('gravatar_thumb') ){
    function gravatar_thumb($email = null, $size = 56){
        if( null === $email){ return ''; }
        
        $default_thumb = site_url().'uploads/images/profile.jpg';
        
        // Alternative default thumb.
        // $default_thumb = base_url() . 'themes/site/default/new-ui/img/user-thumb.png';
        
        return "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($default_thumb)."&s=".$size;
    }
}

function domain_stats_curl_request( $url, $args ){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($args));
    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($args));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return ! empty( $result ) ? json_decode( $result, true ) : array();
}

function format_bytes($bytes=0) {

    $sizes = array('Bytes', 'KB', 'MB', 'GB', 'TB');
    $ret = array( 'val' => null, 'unit' => null );
    
    if ( 0 === $bytes ){
        $ret = array( 'val' => 0, 'unit' => 'Byte' );
    }
    else{
        $i = (int) ( floor( log( $bytes ) / log(1024) ) );
        $ret = array(
            'val' => round( $bytes / pow(1024, $i), 2 ),
            'unit' => $sizes[$i]
        );
    }    
    return $ret;
}

function get_grade($score){
    $grade = '';
    if ($score > 89) {
        $grade = 'A';
    } else if ($score > 79 && $score <= 89) {
        $grade = 'B';
    } else if ($score > 69 && $score <= 79) {
        $grade = 'C';
    } else if ($score > 59 && $score <= 69) {
        $grade = 'D';
    } else if ($score > 49 && $score <= 59) {
        $grade = 'E';
    } else if ($score > 39 && $score <= 49) {
        $grade = 'F';
    } else if ($score > 29 && $score <= 39) {
        $grade = 'G';
    } else if ($score > 19 && $score <= 29) {
        $grade = 'H';
    } else if ($score > 9 && $score <= 19) {
        $grade = 'I';
    } else {
        $grade = 'J';
    }
    return $grade;
}

/* ============================== // ============================== */

function validate_api_response($args){

    $package = RSA_Handler::decrypt( base64_decode( $args['package'] ) );
    $signature = base64_decode( $args['signature'] );
    $verified_signature =  RSA_Handler::verify( $package, $signature );

    if( ! $verified_signature ){
        return array(
            'error' => true, 
            'msg' => 'Unexpected response'
        );
    }

    $package = json_decode( $package, true );
    
    return $package;
}

/* ============================== // ============================== */

function save_new_uptime( $db, $libraries, $models, $user_id, $domain_id, $domain_url, $uptime_keywords ) {

    $keywords_json = ! empty( $uptime_keywords ) ? json_encode( $uptime_keywords ) : '';

    // Get upmonitor existing user id.
    $up_monitor = $models['analyze']->get_upmon( $user_id );

    $exists_valid_up_monitor = $up_monitor && ! empty( $up_monitor ) && isset( $up_monitor[0] ) && isset( $up_monitor[0]->upmon_id ) ? $up_monitor[0]->upmon_id : 0;

    if ( ! $exists_valid_up_monitor ) {

        $user_info = $models['analyze']->getusersinfo( $user_id );

        $us_id = json_decode( $this->upmonitor->createuser( array(
            'username' => $user_info[0]->username,
            'password' => $user_info[0]->password,
            'email'    => $user_info[0]->email,
            'groups'   => array( 'Subscribers' ),
        ) ) );
        
        $models['analyze']->save_upmon( $this->session->userdata( 'user_id' ), array( 'upmon_id' => $us_id->id ) );

        $up_monitor = $models['analyze']->get_upmon( $user_id );        
    }

    $upm_site = json_decode( 
        $libraries['upmonitor']->create_site(
            $up_monitor[0]->upmon_id,
            $up_monitor[0]->uptime_token,
            $up_monitor[0]->username,
            $up_monitor[0]->password,
            $domain_url,
            $keywords_json
        )
    );

    if( ! isset( $upm_site->id ) ){
        // TODO: Something went wrong.
        return false;
    }
    else{
        $db->flush_cache();
        $insert = $db->insert(
            'uptime',
            array(
                'userid' => $user_id,
                'domain_id' => $domain_id,
                'keyword' => $keywords_json,
                'up_time_id' => $upm_site->id,
                'created' => date('Y-m-d H:i:s')
            )
        );

        return $insert;
    }
}

function save_edit_uptime( $db, $user_id, $domain_id, $uptime_keywords ) {
    $db->flush_cache();
    $db->where( 'userid', $user_id );
    $db->where( 'domain_id', $domain_id );
    $update = $db->update(
        'uptime',
        array(
            'keyword' => ! empty( $uptime_keywords ) ? json_encode( $uptime_keywords ) : '' 
        )
    );
    return $update;
}

function is_registered_domain( $db, $url ){
    $db->flush_cache();
    $db->select( '*' );
    $db->from( 'domains' );
    $db->where( 'domain_name', $url );
    $db->limit( 1 );
    $query = $db->get();
    return $query->row_array();
}

function user_parent_user_id( $db = null, $user_id = 0 ){
    $parent_id = 0;
    $user_id = (int) $user_id;
    if( $db && $user_id ){
        $db->flush_cache();
        $db->select( "parent_id" );
        $db->from( "users" );
        $db->where( "id=", $user_id );
        $db->limit( 1 );
        $query = $db->get();
        $parent_user = $query->row_array();
        $parent_id = $parent_user && isset( $parent_user['parent_id'] ) && $parent_user['parent_id'] ? $parent_user['parent_id'] : $parent_id;
    }
    return $parent_id;
}