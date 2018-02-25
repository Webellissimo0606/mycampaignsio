<?php
class WP_Request_Access{

    private $session;
    private $ci_auth;

    private $site = array( 'id' => null, 'url' => null, 'admin' => array( 'url' => null, 'username' => null ) );

    private $endpoints_url = array(
        'ping' => 'wp-json/wp-management-controller/ping',
        'entry' => 'wp-json/wp-management-controller/entry',
        'authorize' => 'wp-json/wp-management-controller/authorize',
        'tokens' => 'wp-json/wp-management-controller/tokens',
        'refresh_tokens' => 'wp-json/wp-management-controller/refresh_tokens',
        'login' => 'wp-json/wp-management-controller/login',
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
        'request_client_tokens_security_arg' => array( 'name' => null, 'expires' => null ),
        'request_ping_security_arg' => array( 'name' => null, 'expires' => null ),
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
            'request_ping_security_arg' => array(
                'name' => 'request_ping_security_arg['.$this->site['id'].']',
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
        // exit;

        if( 200 !== $response['status'] ){

            if( 'login' === $this->get_session('action_on_authorization_complete') && isset( $args['site_id'] ) ){
                $_SESSION[ 'wp_site_login_unreachable_' . $args['site_id'] ] = 1;
                redirect( base_url( 'domains/' . $args['site_id'] . '/wordpress/unreachable' ) );
                exit;
            }

            return array(
                'error' => 'unreachable',
                'msg' => 'Website is not accessible or plugin "WordPress Multisite Controller" is not activated.',
                'response' => $response
            );
        }

        $results = validate_api_response( $response['result'] );

        // print_r( $results );
        // exit;
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

        redirect( $url . '?signature=' . $send_signature . '&package=' . $send_package );
        exit;
    }

    private function access_login(){
        $params = array(
            'action' => 'login',
            'site_id' => $this->site['id'],
            'request_url' => $this->endpoints_url['login'],
            'access_token' => $this->tokens['access'],
            'username' => $this->site['admin']['username'],
            'invalid_redirect' => site_url( 'domains/' . $this->site['id'] . '/wordpress/invalid-access/' )
        );

        // @note: Replaced by following lines... Changed the type of request that handles logn proccess.
        // $this->send_redirection_request( $this->endpoints_url['login'], $params, true );
        
        $response = $this->send_post_request( $this->endpoints_url['login'], $params, false );

        // TODO: Improve following lines.
        if( false === $response['error'] && isset( $response['redirect'] ) ){
            redirect( $response['redirect'] );
            exit;
        }
        else if( 'invalid-access-token' === $response['error'] ){

            $this->refresh_authorization();

            $params['access_token'] = $this->tokens['access'];

            $response = $this->send_post_request( $this->endpoints_url['login'], $params, false );

            if( false === $response['error'] && isset( $response['redirect'] ) ){
                redirect( $response['redirect'] );
                exit;
            }
        }

        exit;
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
            'invalid_redirect' => site_url( 'domains/' . $this->site['id'] . '/wordpress/invalid-access/' )
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
                    print_r( json_encode( $results ) );
                    exit;
                    break;
                case 'invalid-access':
                    $site_id = isset($results['site_id']) && $results['site_id'] ? $results['site_id'] : null;
                    $request_url = isset( $results['request_url'] ) && $results['request_url'] ? $results['request_url'] : null;
                    $request_action = isset( $results['request_action'] ) && $results['request_action'] ? $results['request_action'] : null;

                    if( $this->site['id'] !== $site_id || $this->endpoints_url['updates'] !== $request_url || ( 'updates' !== $request_action && 'updates-2' !== $request_action ) ){
                        // @note: Can be triggered only if something changes the flow of data between server and WP site, 
                        // so we don't care to handle the error. Is more secure not to continue further the process.
                        print_r( 'Error - WordPress Updates - Invalid response' );
                        exit;
                    }

                    if( 'updates-2' === $request_action ){
                        print_r( 'Error - WordPress Updates - Requests loop' );
                        exit;
                    }

                    $this->request_tokens();

                    if( $return_values ){
                        return $this->request_updates( $updates_type, $count_only, '-2', $return_values );
                    }

                    $this->request_updates( $updates_type, $count_only, '-2', $return_values ); // Add prefix into action name to avoid infinity loop in case of error.
                    exit;
            }

            print_r( 'Error - WordPress Updates - Invalid error response' );
            exit;

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
            // 'invalid_redirect' => site_url( 'auth/site/viewsite_invalid_access' )
            'invalid_redirect' => site_url( 'domains/' . $this->site['id'] . '/wordpress/invalid-access/' )
        );
        return $this->send_post_request( $this->endpoints_url['update_now'], $params, false );
    }

    private function on_authorization_begin($action_on_complete = null){
        if( null !== $action_on_complete ){
            $this->set_session('action_on_authorization_complete', $action_on_complete);
        }
    }

    private function save_tokens(){

        if( ! isset( $this->tokens['access'] ) || ! $this->tokens['access'] || '' === $this->tokens['access'] ){
            return false;
        }

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
        
        $client_creds = $this->request( 'client_credentials' );

        if( isset( $client_creds['error'] ) ){
            switch( $client_creds['error'] ){
                case 'invalid-ping':
                    $_SESSION[ 'wp_site_login_unreachable_' . $this->site['id'] ] = 1;
                    redirect( base_url( 'domains/' . $this->site['id'] . '/wordpress/unreachable' ) );
                    exit;
            }
        }

        $this->client = $client_creds;

        if ( null === $this->client['id'] || null === $this->client['secret'] ) {
            print_r( 'Error - Authorization request - Invalid client' );
            return false;
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

        // Validate Admin Login URL and WordPress plugin endpoints accessibility.
        if( ! $this->ping_site() ){
            return array( 'error' => 'invalid-ping' );
        }

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
            // @note: Can be triggered only if something changes the flow of data between server and WP site, 
            // so we don't care to handle the error. Is more secure not to continue further the process.
            print_r("ERROR 1!!!");
            exit;
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
            // @note: Can be triggered only if something changes the flow of data between server and WP site, 
            // so we don't care to handle the error. Is more secure not to continue further the process.
            print_r("ERROR 2!!!");
            exit;
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
            // @note: Can be triggered only if something changes the flow of data between server and WP site, 
            // so we don't care to handle the error. Is more secure not to continue further the process.
            print_r("ERROR 3!!!");
            exit;
        }

        $this->delete_session('request_client_tokens_security_arg');

        $access_token = isset( $results['access'] ) && '' !== trim( $results['access'] ) ? $results['access'] : null;
        $refresh_token = isset( $results['refresh'] ) && '' !== trim( $results['refresh'] ) ? $results['refresh'] : null;
        $refresh_expires = isset( $results['refresh_expires'] ) && 0 < $results['refresh_expires'] && time() < $results['refresh_expires'] ? $results['refresh_expires'] : null;

        if( null === $access_token || null === $refresh_token || null === $refresh_expires ){
            print_r( 'Error - Client request - Invalid response' );
            exit;
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
            // @note: Can be triggered only if something changes the flow of data between server and WP site, 
            // so we don't care to handle the error. Is more secure not to continue further the process.
            print_r("ERROR 4!!!");
            exit;
        }

        $this->delete_session('request_client_tokens_security_arg');

        $error = isset( $results['error'] ) && '' !== trim( $results['error'] ) ? $results['error'] : null;

        if( 
            'invalid-client' === $error 
            || ( 'invalid-refresh-credentials' === $error || 12 === $error )    // @note: '12' is the 'invalid-refresh-credentials' in older response implementation.
            || ( 'empty-refresh-credentials' === $error || 11 === $error )    // @note: '11' is 'empty-refresh-credentials' in older response implementation.
        ){

            $this->new_authorization();

            return array(
                'access' => $this->tokens['access'],
                'refresh' => $this->tokens['refresh'],
                'refresh_expires' => $this->tokens['refresh_expires']
            );
        }
        else if( 'unreachable' === $error ){
            echo isset( $results['msg'] ) && '' !== trim( $results['msg'] ) ? $results['msg'] : 'Error: Unreachable';
            return false;
        }
        else{

            $access_token = isset( $results['access'] ) && '' !== trim( $results['access'] ) ? $results['access'] : null;
            $refresh_token = isset( $results['refresh'] ) && '' !== trim( $results['refresh'] ) ? $results['refresh'] : null;
            $refresh_expires = isset( $results['refresh_expires'] ) && 0 < $results['refresh_expires'] && time() < $results['refresh_expires'] ? $results['refresh_expires'] : null;

            if( null === $access_token || null === $refresh_token || null === $refresh_expires ){
                echo 'Error - Invalid response!';
                return false;
            }
        }

        // var_dump( date( "Y-m-d G:i:s", $refresh_expires ) );
        // var_dump( date( "Y-m-d G:i:s", $refresh_expires - 60 ) );
        // var_dump( date( "Y-m-d G:i:s", time() ) );
        // exit;
        
        return array(
            'access' => $access_token,
            'refresh' => $refresh_token,
            'refresh_expires' => date( "Y-m-d G:i:s", $refresh_expires - 60 ),  // Dicrease expiration time by 60 seconds, to have some more time in any case need (eg. calculations).
        );
    }

    public function request_tokens($action_on_complete = null){
        $this->on_authorization_begin($action_on_complete);
        $authorization = $this->new_authorization();
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

    protected function set_site_tokens($wp_site){
        if( time() >= strtotime( $wp_site['data']->access_expires ) ){
            if( $wp_site['data']->refresh_token ){
                $this->refresh_tokens($wp_site);    
            }
            else{
                $this->request_tokens($wp_site);
            }
        }
        else{
            $this->tokens = array(
                'access' => $wp_site['data']->access_token,
                'refresh' => $wp_site['data']->refresh_token,
                'refresh_expires' => $wp_site['data']->access_expires
            );
        }
    }

    public function ping_site(){

        // Validate Admin Login URL.

        if( ! $this->site['admin']['url'] ){
            return 0;
        }

        $ping_login_url = execute_curl_post( $this->site['admin']['url'] );

        if( ! $ping_login_url || ! isset( $ping_login_url['status'] ) ){
            return 0;
        }

        // @note: For security reasons the WordPress site could response with a 406 status instead of 200. We use it as a valid status and if finally is not, the process will fail later.
        if( 200 !== $ping_login_url['status'] && 406 !== $ping_login_url['status'] ){
            return 0;
        }

        // Validate WordPress plugin endpoints accessibility.

        $security_arg = sha1( uniqid( mt_rand(), true ) );
        
        $this->set_session( 'request_ping_security_arg', $security_arg );

        $url = $this->endpoints_url['ping'];
        
        $url_params = array(
            'site_id' => $this->site['id'],
            'security_arg' => $security_arg
        );

        $results = $this->send_post_request( $url, $url_params );

        if( $results['security_arg'] !== $_SESSION['request_ping_security_arg[' . $results['site_id'] . ']'] ){
            return 0;
        }

        $this->delete_session('request_ping_security_arg');

        return 1;

    }

    public function login($wp_site){
        $this->set_site_tokens($wp_site);
        $this->access_login();
    }

    public function updates($wp_site, $updates_type, $count_only, $return_values = false){
        $this->set_site_tokens($wp_site);
        if( $return_values ){
            return $this->request_updates($updates_type, $count_only, '', $return_values);
        }
        $this->request_updates($updates_type, $count_only, '', $return_values);
    }

    public function update_now( $wp_site, $updates ){
        $this->set_site_tokens($wp_site);
        return $this->request_update_now($updates);
    }
}