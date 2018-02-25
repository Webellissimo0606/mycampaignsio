<?php
function get_username_encrypt_key(){
	return 'CVNTRY%^UI$#TGDFJYULKC%^*%^&$^@#$RVGDFGH';
}

function is_client_authorized( $id = 0, $siteData = array() ) {

    $id = (int) $id;

    if( 0 < $id ){
    
        if( empty ( $siteData ) ){
            $siteData = get_site_by_id( $id );
        }

        if( '' !== $siteData->client_id && '' !== $siteData->client_secret && '' !== $siteData->client_id && '' !== $siteData->access_token && '' !== $siteData->refresh_token && '' !== $siteData->access_expires ){

            if( strtotime( $siteData->access_expires ) > time() ){
                return 'ok';
            }
            else{
                return 'refresh';
            }
        }
    }
    
    return false;
}

function authorize_client( $siteId = 0, $ci_instance = false ) {
    if( 0 < $siteId && $ci_instance ){
        set_cookie( 'get_tokens_after_client_data', 'yes', 30 );
        request_client_data( $siteId, $ci_instance );
    }
}

function request_client_data( $siteId = 0, $ci_instance = false ) {

    $siteId = (int) $siteId;

    if( 0 < $siteId && $ci_instance ){

        $siteDetails = get_site_by_id( $siteId, $ci_instance );

        if ( !empty( $siteDetails ) ) {

            $encryptedUsername = simple_crypt( get_username_encrypt_key(), $siteDetails->adminUsername, 'encrypt');

            $extra_security_param = sha1(uniqid(mt_rand(), true));
            set_cookie( 'client_data_security_param', $extra_security_param, 30 );

            $url = add_url_scheme( $siteDetails->adminURL );
            $url .= '?username=' . $encryptedUsername;
            $url .= '&request_client=1';
            $url .= '&redirect_url=' . site_url("auth/site/retrieve_client_data_page/" . $siteDetails->id . '/success/' . $extra_security_param);

            redirect($url);
        }
    }
}

function refresh_access_token( $id = 0, $siteData = array(), $redirect_url = false, $ci_instance = false ) {
    $id = (int) $id;

    if( 0 < $id && $ci_instance ){
    
        if( empty ( $siteData ) ){
            $siteData = get_site_by_id( $id, $ci_instance );
        }

        $request_options = array(
            'client_id'     => $siteData->client_id,
            'client_secret' => $siteData->client_secret,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $siteData->refresh_token,
        );

        $exe_ret = execute_curl_post( $siteData->domain_name . '/oauth/token', $request_options );
        $result = $exe_ret['result'];
        $httpStatus = $exe_ret['status'];


        // TODO: Is this ok  (the removal of '400 === $httpStatus' )?
        if( /* 400 === $httpStatus && */ isset( $result['error'] ) ){
            // NOTE: Don't really need.
            /*if( 'unsupported_grant_type' === $data->error ){
                authorize_client( $id, $ci_instance );
            }
            elseif( 'invalid_grant' === $data->error ){
                authorize_client( $id, $ci_instance );
            }*/
            authorize_client( $id, $ci_instance );
        }
        elseif( 200 === $httpStatus && ! empty( $result ) ){

            save_auth_tokens( $id, $result, $redirect_url, $ci_instance );
        }

    }
}

function request_client_code_and_tokens( $siteId = 0, $ci_instance = false  ) {

    $siteId = (int) $siteId;

    if( 0 < $siteId && $ci_instance ){

        $siteData = get_site_by_id( $siteId, $ci_instance );

        if ( ! empty( $siteData ) ){

            $clientId = $siteData->client_id;

            if( null !== $clientId && '' !== $clientId ){
                
                $encryptedUsername = simple_crypt( get_username_encrypt_key(), $siteData->adminUsername, 'encrypt');

                $extra_security_param = sha1(uniqid(mt_rand(), true));
                set_cookie( 'code_tokens_security_param', $extra_security_param, 30 );

                $url = add_url_scheme( remove_last_slash( $siteData->domain_name ) ) ;
                $url .= '/oauth/authorize/' ;
                $url .= '?response_type=code';
                $url .= '&login=1';
                $url .= '&client_id=' . $clientId;
                $url .= '&username=' . $encryptedUsername;
                $url .= '&redirect_uri=' . site_url('auth/site/retrieve_client_code_and_tokens_page/' . $siteId . '/' . $extra_security_param);

                redirect( $url );
            }
        }
    }
}

function save_auth_tokens( $siteId = 0, $data = array(), $redirect_url = false, $ci_instance = false ) {

    $siteId = (int) $siteId;

    if( 0 < $siteId && ! empty( $data ) && $ci_instance ){

        $updateData = array(
            'access_token'      => $data['access_token'],
            'refresh_token'     => $data['refresh_token'],
            'access_expires'    => date( "Y-m-d G:i:s", time() + $data['expires_in'] - 60 ),  // Dicrease expiration date by 60 seconds, just to have some time if need (eg. for calculations)
        );
        
        update_auth_data( $updateData, $siteId, $ci_instance );

        $redirect_after_auth = $redirect_url ? $redirect_url : get_cookie( 'redirect_after_authorize' );

        if( $redirect_after_auth ){

            delete_cookie( 'redirect_after_authorize' );

            $params = parse_url( $redirect_after_auth, PHP_URL_QUERY );
            $redirect_after_auth .= $params ? '&' : '?';
            $redirect_after_auth .= 'access_token=' . $data['access_token'];

            redirect( $redirect_after_auth );
        }
    }
}

function update_client_data($data, $site_id, $ci_instance = false) {
	if( $ci_instance ){
		$domains_table = $ci_instance->config->item('db_table_prefix') . 'domains';
		$ci_instance->db->set('client_id', $data['client_id']);
	    $ci_instance->db->set('client_secret', $data['client_secret']);
	    $ci_instance->db->where('id', $site_id);
	    $ci_instance->db->update( $domains_table );
	    return $ci_instance->db->affected_rows() > 0;
	}
	else{
		return false;
	}
}

function update_auth_data($data, $site_id, $ci_instance = false) {

	if( $ci_instance ){
		$domains_table = $ci_instance->config->item('db_table_prefix') . 'domains';
	    $ci_instance->db->set('access_token', $data['access_token']);
	    $ci_instance->db->set('refresh_token', $data['refresh_token']);
	    $ci_instance->db->set('access_expires', $data['access_expires']);
	    $ci_instance->db->where('id', $site_id);
	    $ci_instance->db->update( $domains_table );
	    return $ci_instance->db->affected_rows() > 0;
    }
	else{
		return false;
	}
}

function execute_curl_get( $url = '' ){
    $ret = array();
    if( '' !== $url ){
        $curl = curl_init( $url );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $ret['result'] = json_decode( curl_exec($curl), true );
        $ret['status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    }
    return $ret;
}

function execute_curl_post( $url = '', $fields = array() ){
    $ret = array();
    if( '' !== $url ){
        $curl = curl_init( $url );
        curl_setopt($curl, CURLOPT_POST, true);
        if( ! empty( $fields ) ){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields );
        }
        curl_setopt($curl, CURLOPT_REFERER, site_url());
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $ret['result'] = json_decode( curl_exec($curl), true );
        $ret['status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    }
    return $ret;
}

/* General Site Functions */

function get_site_by_id( $id = '', $ci_instance = false ) {
    $id = is_null($id) ? 0 : (int) $id;
    if( 0 < $id ){
        if( ! $ci_instance ){
            $ci_instance =& get_instance();
        }
    	$domains_table = $ci_instance->config->item('db_table_prefix') . 'domains';
        $ci_instance->db->where('id', $id);
        $query = $ci_instance->db->get( $domains_table );
        if ($query->num_rows() == 1){
            return $query->row();
        }
    }
    return null;
}

function get_user_domains( $user_id = '', $db_instance = false ){
    $user_domains = array();
    if( ! is_null( $user_id ) ){
        if( ! $db_instance ){
            $ci_instance =& get_instance();
            $db_instance = $ci_instance->db;
        }
        $db_instance->select('*');
        $db_instance->from('domains');
        $db_instance->where('userid', $user_id );
        $query = $db_instance->get();
        foreach ($query->result() as $row){
            $user_domains[] = $row;
        }
    }
    return $user_domains;
}

function get_backup_tasks( $task_id = false ) {

    $backup_tasks = array();
    $CI =& get_instance();
    $CI->load->database(); 
    $CI->db->select('*');
    $CI->db->from('backups_tasks');
    if( $task_id ){
        $CI->db->where('id', $task_id);
    }
    else{
        $CI->db->order_by('created', 'DESC');
    }
    $query = $CI->db->get();
    foreach ($query->result() as $row){
        $backup_tasks[] = $row;
    }
    return $backup_tasks;
}

function get_backup_settings( $domain_id = false ) {
    $backup_settings = array();
    $CI =& get_instance();
    $CI->load->database(); 
    $CI->db->select('*');
    $CI->db->from('backups_settings');
    if( $domain_id ){
        $CI->db->where('domain_id', $domain_id);
    }
    else{
        $CI->db->order_by('domain_id', 'DESC');
    }
    $query = $CI->db->get();
    foreach ($query->result() as $row){
        $backup_settings[] = $row;
    }
    return $backup_settings;
}

function delete_backup_task( $task_id = false ){

    if( $task_id ){
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->where('id', $task_id);
        $CI->db->delete('backups_tasks');
        return true;
    }

    return false;
}

/* General Functions */

function add_url_scheme( $url='', $scheme = 'http://' ) {
    if( '' !== $url ){
        $url = parse_url( $url, PHP_URL_SCHEME ) === null ? $scheme . $url : $url;
    }
    return $url;
}

function remove_last_slash( $str = '' ){

    return rtrim( rtrim( $str, '/' ), '\\' );
}
