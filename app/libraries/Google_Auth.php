<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
ini_set('include_path', 'C:\wamp\www\analytics\google_api\src');
set_include_path(APPPATH . 'third_party/google_api/src');
require_once APPPATH . 'third_party/google_api/vendor/autoload.php';
require_once APPPATH . 'third_party/google_api/src/Google/Client.php';

class Google_Auth extends Google_Client {

    function __construct($params = array()) {
       $this->ci = &get_instance();
        $this->ci->load->library('phpass');
        $this->ci->load->library('session');
        $this->ci->load->database();
        $this->ci->load->model('analytics/analytics_model');
        $this->ci->load->config('configuration', TRUE);
        $this->ci->load->library('session');
        $this->ci->client = new Google_Client();
        $client_id = $this->ci->config->config['google_oauth']['client_id'];
        $client_secret = $this->ci->config->config['google_oauth']['client_secret'];
        $this->redirect_uri = $this->ci->config->config['google_oauth']['redirect_uri'];
        $redirect_uri = $this->redirect_uri;
        $this->simple_api_key = $this->ci->config->config['google_oauth']['api_key'];
        $simple_api_key = $this->simple_api_key;
        $this->ci->client->setApplicationName("PHP Google OAuth Login Example");
        $this->ci->client->setClientId($client_id);
        $this->ci->client->setClientSecret($client_secret);
        $this->ci->client->setRedirectUri($redirect_uri);
        $this->ci->client->setDeveloperKey($simple_api_key);
        $this->ci->client->setAccessType('offline');
        $this->ci->client->setApprovalPrompt('force');
        $this->ci->client->addScope("https://www.googleapis.com/auth/analytics.readonly https://www.googleapis.com/auth/analytics https://www.googleapis.com/auth/analytics.edit https://www.googleapis.com/auth/analytics.manage.users https://www.googleapis.com/auth/analytics.manage.users.readonly");
    }
    
    public function getUserAuthenticated($userid) {
        $user = array();
        $client = $this->ci->client;
        if ($client->getAccessToken()) {
            $_SESSION['token'] = $client->getAccessToken();
            return true;
        } else {
            $result = $this->ci->analytics_model->getUserAuthData($userid);
            $user = (array) $result;
            if (count($user) > 0) {
                if ($client->isAccessTokenExpired()) {
                    $token = $client->refreshToken($user[0]->refresh_token);

                    $_SESSION['token'] = $client->getAccessToken();
                    $auth_data['access_token'] = $_SESSION['token']['access_token'];
                    $auth_data['token_type'] = $_SESSION['token']['token_type'];
                    $auth_data['expires_in'] = $_SESSION['token']['expires_in'];
                    $auth_data['created'] = $_SESSION['token']['created'];
                    $update = $this->ci->analytics_model->updateUserAuthData($userid, $auth_data);
                    if ($update) {
                        return true;
                    }
                } else {
                    return true;
                }
            } else {
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
                    $insert = $this->ci->analytics_model->insertUserAuthData($auth_data);
                    if ($insert) {
                      
                        $access_token = $_SESSION['token']['access_token'];
                        return true;
                    }
                }
            }
        }
    }

}

?>