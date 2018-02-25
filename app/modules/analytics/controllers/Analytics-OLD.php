<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analytics extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library('form_validation');
        $this->load->library('my_form_validation');
        $this->load->model('auth/user_model');
        $this->load->model('analytics/analytics_model');
        $this->load->model('auth/analyze_model');
        $this->load->model('analytics/usergaaccount_model');
        $this->load->config('configuration', TRUE);
        $this->load->library('session');
        $this->load->library('Google_API');
        $this->client = new Google_Client();
        $client_id = $this->config->config['google_oauth']['client_id'];
        $client_secret = $this->config->config['google_oauth']['client_secret'];
        $this->redirect_uri = $this->config->config['google_oauth']['redirect_uri'];
        $redirect_uri = $this->redirect_uri;
        $this->simple_api_key = $this->config->config['google_oauth']['api_key'];
        $simple_api_key = $this->simple_api_key;
        $this->client->setApplicationName("PHP Google OAuth Login Example");
        $this->client->setClientId($client_id);
        $this->client->setClientSecret($client_secret);
        $this->client->setRedirectUri($redirect_uri);
        $this->client->setDeveloperKey($simple_api_key);
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');
        $this->client->addScope("https://www.googleapis.com/auth/analytics.readonly https://www.googleapis.com/auth/analytics https://www.googleapis.com/auth/analytics.edit https://www.googleapis.com/auth/analytics.manage.users https://www.googleapis.com/auth/analytics.manage.users.readonly");
        $this->ci = &get_instance();
    }

    public function index() {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(FALSE)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {

                $user_id = $this->ci_auth->get_user_id();
                //check if has parent user
                // $this->db->flush_cache();
                // $this->db->select("parent_id");
                // $this->db->from('users as u');
                // $this->db->where("id=", $user_id);
                // $this->db->limit(1);
                // $query      = $this->db->get();
                // $parentDetail = $query->row_array();
                // if($parentDetail['parent_id'] != null) {
                //    $user_id = $parentDetail['parent_id']; 
                // }
                $data['errors'] = $this->session->flashdata('errors');
                $data['message'] = $this->session->flashdata('message');
                $data['success'] = $this->session->flashdata('success');
                $connectToGoogle = $this->session->userdata('connectToGoogle');
                $data['connectToGoogle'] = $connectToGoogle;
                $user_auth = $this->analytics_model->getUserAuthData($user_id);
                $endDate = date('Y-m-d');
                $startDate = date('Y-m-d', strtotime('1 month ago'));
                $data['startDate'] = $startDate;
                $data['endDate'] = $endDate;
                if (empty($user_auth)) {
                    $auth = $this->getUserAuthenticated($user_id);
                }
                if ($connectToGoogle == 0) {

                    $data['noAccess'] = "You don't have access to this page.";
                    $this->load->view(get_template_directory() . 'analytics', $data);
                } else {
                    $auth = $this->getUserAuthenticated($user_id);
                    if (!$auth) {
                        $client = $this->client;
                        $authUrl = $client->createAuthUrl();
                        // die($authUrl);
                        $data['authUrl'] = $authUrl;
                        $data['loggedIn'] = false;

//                    redirect(site_url('/analytics/analytics/settings'));
//                    $this->load->view(get_template_directory() . 'analytics', $data);
                        redirect(site_url('/auth/add_project'));
                    } else {

                        $data['loggedIn'] = true;
                        $data['accessToken'] = $_SESSION['token']['access_token'];

                        $this->load->view(get_template_directory() . 'analytics_new', $data);
                    }
                }
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function settings() {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(FALSE)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $user_id = $this->ci_auth->get_user_id();
                $data['errors'] = $this->session->flashdata('errors');
                $data['message'] = $this->session->flashdata('message');
                $data['success'] = $this->session->flashdata('success');

                $client = $this->client;
                $authUrl = $client->createAuthUrl();
                // die($authUrl);
                $data['authUrl'] = $authUrl;
                $this->load->view(get_template_directory() . 'settings', $data);
                // $this->load->view(get_template_directory() . 'settings');
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function getUserAuthenticated($userid) {
        $user = array();

        $client = $this->client;
        if ($client->getAccessToken()) {
            $_SESSION['token'] = $client->getAccessToken();
            return true;
        } else {
            $result = $this->analytics_model->getUserAuthData($userid);
            $user = (array) $result;
            if (count($user) > 0) {

                if ($client->isAccessTokenExpired()) {

                    $token = $client->refreshToken($user[0]->refresh_token);
                    $_SESSION['token'] = $client->getAccessToken();
                    $auth_data['access_token'] = $_SESSION['token']['access_token'];
                    $auth_data['token_type'] = $_SESSION['token']['token_type'];
                    $auth_data['expires_in'] = $_SESSION['token']['expires_in'];
                    $auth_data['created'] = $_SESSION['token']['created'];
                    $update = $this->analytics_model->updateUserAuthData($userid, $auth_data);
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
                    $insert = $this->analytics_model->insertUserAuthData($auth_data);
                    if ($insert) {

                        $access_token = $_SESSION['token']['access_token'];
                        return true;
                    }
                }
            }
        }
    }

    public function addClientId() {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(FALSE)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $user_id = $this->ci_auth->get_user_id();

                $data['errors'] = $this->session->flashdata('errors');
                $data['message'] = $this->session->flashdata('message');
                $data['success'] = $this->session->flashdata('success');
                $client_id = '880327336203-7clktmd0r08q6424ebhtgqksnvv2ug3c.apps.googleusercontent.com';
                $client_secret = '4lNaReX7eUKH-azsngD-yPEu';
                $redirect_uri = 'http://my.campaigns.io/campaigns/analytics/analytics';
                $simple_api_key = 'AIzaSyC3Fjd-QwoaDLaGZQJrOacYCOJovFsZaAg';
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function getPiwikAnalytics()
    {
        $domainId = $this->input->post('id');
        $days = $this->input->post('days');
        $domainDetail = $this->analyze_model->getDomain($domainId);
        if ($domainDetail[0]->piwik_site_id > 0) {
           $apiUrl        = $this->ci->config->config['piwik']['api_url'];
           $token_auth = $this->ci->config->config['piwik']['auth_token'];
           // we call the REST API and request the 100 first keywords for the last month for the idsite=7
           $url = "http://stats.campaigns.io/";
           $url .= "?module=API&method=VisitsSummary.getVisits";
           $url .= "&idSite=".$domainDetail[0]->piwik_site_id."&period=day&date=last".$days;
           $url .= "&format=JSON&filter_limit=20";
           $url .= "&token_auth=$token_auth";
           $fetched = file_get_contents($url);   
           $response['type'] = 'success';
           $response['payload'] = json_decode($fetched,true);
        } else {
            $response['type'] = 'error';
            $response['msg'] = 'Piwik not activated for this domain';
            $response['payload'] = null;
        }
        echo json_encode($response);die;
        
    }

    public function getGoogleAnalytics()
    {
        $days = $this->input->post('days');
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        $domainId = $this->input->post('id');
        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
        if (!$client->getAccessToken()) {
            $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
            if ($ga_res) {
              $this->getUserAuthenticated($ga_res['user_id']);  
            }
            
        }
        
        $analytics = new Google_Service_Analytics($client);
        $profiles = $analytics->management_profiles
                  ->listManagementProfiles($domainDetails['ga_account'], '~all');

        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();

          $profileId =  $items[0]->getId();
         
          // Add Analytics View ID, prefixed with "ga:"
          $analyticsViewId    = 'ga:'.$profileId;
          $startDate          = date('Y-m-d',strtotime('-'.$days.' days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:visits';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
              'dimensions'    => 'ga:date',
              'sort'          => '-ga:date',
          ));
          
          $items = $data->getRows();
          if($items) {
              $res = array();
              foreach($items as $item){
                  $return[date('Y-m-d', strtotime($item[0]))] = (int)$item[1];
              }
              $res['type'] = 'success';
              $res['payload'] = array_reverse($return);
          } else {
              $res['type'] = 'error';
              $res['payload'] = null;
          }
          
          echo json_encode($res);die;
        } else {
             $res['type'] = 'error';
             $res['payload'] = null;
             echo json_encode($res);die;
        }    

         echo json_encode($response);die;
        
    }


    public function getweeklytraffic()
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        $domainId = $this->input->post('id');
        $domainDetail = $this->analyze_model->getDomain($domainId);
        if ($domainDetail[0]->piwik_site_id > 0) {
           $apiUrl        = $this->ci->config->config['piwik']['api_url'];
           $token_auth = $this->ci->config->config['piwik']['auth_token'];
           // we call the REST API and request the 100 first keywords for the last month for the idsite=7
           $url = "http://stats.campaigns.io/";
           $url .= "?module=API&method=VisitsSummary.getVisits";
           $url .= "&idSite=".$domainDetail[0]->piwik_site_id."&period=week&date=today";
           $url .= "&format=JSON&filter_limit=20";
           $url .= "&token_auth=$token_auth";
           $fetched = file_get_contents($url, false, stream_context_create($arrContextOptions));   
           $response['type'] = 'success';
           $response['payload'] = json_decode($fetched,true);
        } else {
            $response['type'] = 'error';
            $response['msg'] = 'Piwik not activated for this domain';
            $response['payload'] = null;
        }
        echo json_encode($response);die;
    }


    public function getWeeklyGoogleTraffic()
    {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        $domainId = $this->input->post('id');
        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
        if (!$client->getAccessToken()) {
            $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
            if ($ga_res) {
              $this->getUserAuthenticated($ga_res['user_id']);  
            }
        }
   
        $analytics = new Google_Service_Analytics($client);
        $profiles = $analytics->management_profiles
                  ->listManagementProfiles($domainDetails['ga_account'], '~all');

        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();

          $profileId =  $items[0]->getId();
         
          // Add Analytics View ID, prefixed with "ga:"
          $analyticsViewId    = 'ga:'.$profileId;
          $startDate          = date('Y-m-d',strtotime('-7 days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:visits';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
              'dimensions'    => 'ga:date',
              // 'sort'          => '-ga:date',
          ));
          
          $items = $data->getRows();

          if($items) {
              $res = array();
              foreach($items as $item){
                  $return+= (int)$item[1];
              }
              $res['type'] = 'success';
              $res['payload']['value'] = $return;
          } else {
              $res['type'] = 'error';
              $res['payload'] = 0;
          }
          
          echo json_encode($res);die;
        } else {
             $res['type'] = 'error';
              $res['payload'] = 0;
              echo json_encode($res);die;
        }    

         echo json_encode($response);die;
    }

    public function getUserAccounts() {
        $url = $this->input->post('url');
        $accountId = $this->session->userdata('gaAccount');
        $client = $this->client;
        if ($accountId > 0) {
            $user_id = $this->ci_auth->get_user_id();
            //check if has parent user
            $this->db->flush_cache();
            $this->db->select("parent_id");
            $this->db->from('users as u');
            $this->db->where("id=", $user_id);
            $this->db->limit(1);
            $query      = $this->db->get();
            $parentDetail = $query->row_array();
            if($parentDetail['parent_id'] != null) {
               $user_id = $parentDetail['parent_id']; 
            }

            $user = array();
            if (!$client->getAccessToken()) {
                $this->getUserAuthenticated($user_id);
            }

            do {
                $result = $this->analytics_model->getUserAuthData($user_id);
                $user = (array) $result;
            } while (!$user);

            $access_token = $user[0]->access_token;
            $this->session->set_userdata('accToken', $access_token);


            $properties_url = file_get_contents("https://www.googleapis.com/analytics/v3/management/accounts/" . $accountId . "/webproperties?access_token=" . $access_token);

            $properties_data = json_decode($properties_url);
            $properties = array();

            foreach ($properties_data->items as $properties_dat) {
                if (parse_url($properties_dat->websiteUrl, PHP_URL_HOST) == parse_url($url, PHP_URL_HOST)) {


                    $properties = array('id' => $properties_dat->id, 'name' => $properties_dat->name, 'websiteUrl' => $properties_dat->websiteUrl, 'internalWebPropertyId' => $properties_dat->internalWebPropertyId, 'accountId' => $properties_dat->accountId);

                    break;
                }
            }


            $views = array();
            if (!empty($properties)) {

                if ($properties['id']) {
                    $views_url = file_get_contents("https://www.googleapis.com/analytics/v3/management/accounts/" . $properties['accountId'] . "/webproperties/" . $properties['id'] . "/profiles?access_token=" . $_SESSION['token']['access_token']);
                    $views_data = json_decode($views_url);
                }
            } else {
                $views_data = array();
            }
//         

            echo json_encode(array('accToken' => $access_token, 'views_data' => $views_data));
        } else {
            echo json_encode(array('views_data' => array()));
        }
        die();
//        return $accounts;
    }

    public function getUserGAAccounts() {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        //check if has parent user
        // $this->db->flush_cache();
        // $this->db->select("parent_id");
        // $this->db->from('users as u');
        // $this->db->where("id=", $user_id);
        // $this->db->limit(1);
        // $query      = $this->db->get();
        // $parentDetail = $query->row_array();
        $accounts = array();
        // if($parentDetail['parent_id'] != null) {
        //    $user_id = $parentDetail['parent_id']; 
        //    $user = array();
        //    if (!$client->getAccessToken()) {
        //        $this->getUserAuthenticated($user_id);
        //    }
        //    do {
        //        $result = $this->analytics_model->getUserAuthData($user_id);
        //        $user = (array) $result;
        //    } while (!$user);


        //    $access_token = $user[0]->access_token;
        //    if($access_token) {
        //      $accounts_url = file_get_contents("https://www.googleapis.com/analytics/v3/management/accounts?access_token=" . $access_token);
        //      $man_accounts = json_decode($accounts_url);
        //      foreach ($man_accounts->items as $man_account) {

        //          $accounts[] = array('id' => $man_account->id, 'name' => $man_account->name);
        //      }
        //    }
        // }
        $user = array();
        if (!$client->getAccessToken()) {
            $this->getUserAuthenticated($user_id);
        }
        do {
            $result = $this->analytics_model->getUserAuthData($user_id);
            $user = (array) $result;
        } while (!$user);


        $access_token = $user[0]->access_token;
        if($access_token) {
          $accounts_url = file_get_contents("https://www.googleapis.com/analytics/v3/management/accounts?access_token=" . $access_token);
          $man_accounts = json_decode($accounts_url);

          if($man_accounts) {
            $this->db->flush_cache();
            $this->db->where('user_id=', $this->ci_auth->get_user_id());
            $this->db->delete('user_ga_account');
          }
          foreach ($man_accounts->items as $man_account) {
            
            if( !preg_match('/add/i', $man_account->name)){
                // $accounts[] = array('id' => $man_account->id, 'name' => $man_account->name);

                $acc = file_get_contents("https://www.googleapis.com/analytics/v3/management/accounts/".$man_account->id."/webproperties?access_token=" . $access_token);
                $acc = json_decode($acc);
                foreach($acc->items as $a){
                  $accounts[] = array('id'=>$a->accountId, 'name'=>$a->name);
                  //inserting in user ga table
                  $this->db->flush_cache();
                  $array = array();
                  $array['user_id'] = $this->ci_auth->get_user_id();
                  $array['ga_code'] = $a->accountId;
                  $array['name'] = $a->name;
                  $array['webpropertyid'] = $a->internalWebPropertyId;
                  $array['websiteurl'] = $a->websiteUrl;
                  $array['defaultprofileid'] = $a->defaultProfileId;
                  $array['created'] = date('Y-m-d H:i:s');
                  $this->db->insert('user_ga_account', $array);
                }
            }
          }
        }
       

        echo json_encode($accounts);
        die();
    }

    public function getCrawlErrors() {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        //check if has parent user
        // $this->db->flush_cache();
        // $this->db->select("parent_id");
        // $this->db->from('users as u');
        // $this->db->where("id=", $user_id);
        // $this->db->limit(1);
        // $query      = $this->db->get();
        // $parentDetail = $query->row_array();
        // if($parentDetail['parent_id'] != null) {
        //    $user_id = $parentDetail['parent_id']; 
        // }
        $user = array();
        $url = $this->input->post('url');
        //get domain correct name
        $this->db->select('*');
        $this->db->from('domains');
        $this->db->like('domain_name', $url, '%');
        $this->db->limit(1);
        $query          = $this->db->get();
        $domainRes = $query->row_array();

        if (!$client->getAccessToken()) {
            $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainRes['ga_account']);
            if ($ga_res) {
              $this->getUserAuthenticated($ga_res['user_id']);  
            }
        }
        
        $url = urlencode($domainRes['domain_name']);
        $apiKey = $this->simple_api_key;
        do {
            $result = $this->analytics_model->getUserAuthData($ga_res['user_id']);
            $user = (array) $result;
        } while (!$user);
        $access_token = $user[0]->access_token;
        $err_count = file_get_contents("https://www.googleapis.com/webmasters/v3/sites/" .$url. "/urlCrawlErrorsCounts/query?access_token=" . $access_token);
        $error_count = json_decode($err_count);
        echo json_encode(array('accessToken' => $access_token, 'api_data' => $error_count));
        die();
    }

    public function searchAnalytics() {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(FALSE)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $data['errors'] = $this->session->flashdata('errors');
                $data['message'] = $this->session->flashdata('message');
                $data['success'] = $this->session->flashdata('success');
                $client = $this->client;
                $user_id = $this->ci_auth->get_user_id();
                //check if has parent user
                $this->db->flush_cache();
                $this->db->select("parent_id");
                $this->db->from('users as u');
                $this->db->where("id=", $user_id);
                $this->db->limit(1);
                $query      = $this->db->get();
                $parentDetail = $query->row_array();
                if($parentDetail['parent_id'] != null) {
                   $user_id = $parentDetail['parent_id']; 
                }
                $user = array();
                if (!$client->getAccessToken()) {
                    $this->getUserAuthenticated($user_id);
                }

                try {
                    $weburl = $this->session->userdata('domainHost');
                    //getting the domain name
                    $this->db->flush_cache();
                    $this->db->like('domain_name', $weburl);
                    $this->db->from('domains');
                    $this->db->limit(1);
                    $query        = $this->db->get();
                    $domainDetails = $query->row_array();
                    $weburl = $domainDetails['domain_name'];
                    

                    $service = new Google_Service_Webmasters($client);



                    $_SESSION['access_token'] = $client->getAccessToken();

                    $q = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
                    $endDate = date('Y-m-d');


                    $startDate = date('Y-m-d', strtotime('1 month ago'));
                    $data['startDate'] = $startDate;
                    $data['endDate'] = $endDate;
                    $q->setStartDate($startDate);
                    $q->setEndDate($endDate);
                    $q->setDimensions(['query']);
                    $q->setSearchType('web');

                    $data['query'] = $service->searchanalytics->query($weburl, $q);


                    $d = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();

                    $d->setStartDate($startDate);
                    $d->setEndDate($endDate);
                    $d->setDimensions(['date']);
                    $d->setSearchType('web');
                    $data['date'] = $service->searchanalytics->query($weburl, $d);
                } catch (Google_Service_Exception $e) {
                    $data['query'] = array();
                    $data['date'] = array();
                }

                $queries = array();
                if (!empty($data['query'])) {
                    foreach ($data['query']->rows as $key => $value) {
                        $ctr = $value->ctr * 100;

                        $queries[] = array($value->keys[0], $value->clicks, $value->impressions, (float) number_format($ctr, 2, ".", "") . '%', (float) number_format($value->position, 1, ".", ""));
                    }
                }
                $data['queries'] = $queries;
                $this->load->view(get_template_directory() . 'searchanalytics_new', $data);
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function searchAnalyticsByDimensionType() {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        //check if has parent user
        $this->db->flush_cache();
        $this->db->select("parent_id");
        $this->db->from('users as u');
        $this->db->where("id=", $user_id);
        $this->db->limit(1);
        $query      = $this->db->get();
        $parentDetail = $query->row_array();
        if($parentDetail['parent_id'] != null) {
           $user_id = $parentDetail['parent_id']; 
        }
        if (!$client->getAccessToken()) {
            $this->getUserAuthenticated($user_id);
        }

        try {


            $weburl = $this->session->userdata('domainHost');
            //getting the domain name
            $this->db->flush_cache();
            $this->db->like('domain_name', $weburl);
            $this->db->from('domains');
            $this->db->limit(1);
            $query        = $this->db->get();
            $domainDetails = $query->row_array();
            $weburl = $domainDetails['domain_name'];

            $service = new Google_Service_Webmasters($client);



            $_SESSION['access_token'] = $client->getAccessToken();

            $q = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $dimension = $this->input->post('dimension');
            $searchtype = $this->input->post('searchType');
            $endDate = $this->input->post('endDate');
            $startDate = $this->input->post('startDate');
            $q->setStartDate($startDate);
            $q->setEndDate($endDate);
            $q->setDimensions([$dimension]);
            $q->setSearchType($searchtype);
            $query = $service->searchanalytics->query($weburl, $q);


            $d = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();

            $d->setStartDate($startDate);
            $d->setEndDate($endDate);
            $d->setDimensions(['date']);
            $d->setSearchType($searchtype);
            $date = $service->searchanalytics->query($weburl, $d);
            $queries = array();
            foreach ($query->rows as $key => $value) {
                $ctr = $value->ctr * 100;

                $queries[] = array($value->keys[0], $value->clicks, $value->impressions, (float) number_format($ctr, 2, ".", "") . '%', (float) number_format($value->position, 1, ".", ""));
            }
            echo json_encode(array('query' => $queries, 'date' => $date->rows));
        } catch (Google_Service_Exception $e) {
            echo json_encode(array('query' => array(), 'date' => array()));
        }

        die();
    }

    function getSerpKeywords() {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        //check if has parent user
        $this->db->flush_cache();
        $this->db->select("parent_id");
        $this->db->from('users as u');
        $this->db->where("id=", $user_id);
        $this->db->limit(1);
        $query      = $this->db->get();
        $parentDetail = $query->row_array();
        if($parentDetail['parent_id'] != null) {
           $user_id = $parentDetail['parent_id']; 
        }
        if (!$client->getAccessToken()) {
            $this->getUserAuthenticated($user_id);
        }
        $domain_data = $this->session->get_userdata();
        $domain_id = $domain_data['domainId'];
        $editKeywords = $this->analyze_model->getkeywordByDomainId($domain_id);

        $saved_keywords = array();
        foreach ($editKeywords as $editKeyword) {
            $saved_keywords[] = $editKeyword['name'];
        }
        $keywords = array();
        try {
            $weburl = $this->session->userdata('domainHost');
            //getting the domain name
            $this->db->flush_cache();
            $this->db->like('domain_name', $weburl);
            $this->db->from('domains');
            $this->db->limit(1);
            $query        = $this->db->get();
            $domainDetails = $query->row_array();
            $weburl = $domainDetails['domain_name'];
            $service = new Google_Service_Webmasters($client);
            $endDate = date('Y-m-d');
            $startDate = date('Y-m-d', strtotime('1 month ago'));
            $q = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $q->setStartDate($startDate);
            $q->setEndDate($endDate);
            $q->setDimensions(['query']);
            $q->setSearchType('web');
            $query = $service->searchanalytics->query($weburl, $q);
            if (!empty($query->rows)) {
                foreach ($query->rows as $val) {
                    if (!empty($saved_keywords)) {
                        if (!(in_array($val->keys[0], $saved_keywords))) {
                            $keyword['keyword'] = $val->keys[0];
                            $keyword['clicks'] = $val->clicks;
                            $keyword['impressions'] = $val->impressions;
                            $keywords[] = array($val->keys[0], $val->clicks, $val->impressions, '<input type="checkbox" name="add_keyword[]" value="' . $val->keys[0] . '" class="add_keyword">');
                        }
                    } else {
                        $keyword['keyword'] = $val->keys[0];
                        $keyword['clicks'] = $val->clicks;
                        $keyword['impressions'] = $val->impressions;
                        $keywords[] = array($val->keys[0], $val->clicks, $val->impressions, '<input type="checkbox" name="add_keyword[]" value="' . $val->keys[0] . '" class="add_keyword">');
                    }
                }
            }
        } catch (Google_Service_Exception $e) {
            
        }
        echo json_encode($keywords);
        die();
    }

    function getQueriesByDefault() {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        //check if has parent user
        $this->db->flush_cache();
        $this->db->select("parent_id");
        $this->db->from('users as u');
        $this->db->where("id=", $user_id);
        $this->db->limit(1);
        $query      = $this->db->get();
        $parentDetail = $query->row_array();
        if($parentDetail['parent_id'] != null) {
           $user_id = $parentDetail['parent_id']; 
        }


        if (!$client->getAccessToken()) {
            $this->getUserAuthenticated($user_id);
        }
        $domain_data = $this->session->get_userdata();
        $domain_id = $domain_data['domainId'];
        $editKeywords = $this->analyze_model->getkeywordByDomainId($domain_id);

        $saved_keywords = array();
        foreach ($editKeywords as $editKeyword) {
            $saved_keywords[] = $editKeyword['name'];
        }
        $keywords = array();
        try {
            $weburl = $this->session->userdata('domainHost');
            //getting the domain name
            $this->db->flush_cache();
            $this->db->like('domain_name', $weburl);
            $this->db->from('domains');
            $this->db->limit(1);
            $query        = $this->db->get();
            $domainDetails = $query->row_array();
            $weburl = $domainDetails['domain_name'];


            $service = new Google_Service_Webmasters($client);
            $endDate = date('Y-m-d');
            $startDate = date('Y-m-d', strtotime('1 month ago'));
            $q = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $q->setStartDate($startDate);
            $q->setEndDate($endDate);
            $q->setDimensions(['query']);
            $q->setSearchType('web');
            $query = $service->searchanalytics->query($weburl, $q);
            if (!empty($query->rows)) {

                $queries = array();
                foreach ($query->rows as $key => $value) {
                    $ctr = $value->ctr * 100;

                    $queries[] = array($value->keys[0], $value->clicks, $value->impressions, (float) number_format($ctr, 2, ".", "") . '%', (float) number_format($value->position, 1, ".", ""));
                }
            }
        } catch (Google_Service_Exception $e) {
            $queries = array();
        }
        echo json_encode($queries);
        die();
    }

    public function getSearchAnalyticsGraphforDashboard() {

        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        if (!$client->getAccessToken()) {
            $this->getUserAuthenticated($user_id);
        }
        try {
            $weburl = $this->session->userdata('domainHost');
            //getting the domain name
            $this->db->flush_cache();
            $this->db->like('domain_name', $weburl);
            $this->db->from('domains');
            $this->db->limit(1);
            $query        = $this->db->get();
            $domainDetails = $query->row_array();
            $weburl = $domainDetails['domain_name'];

            $service = new Google_Service_Webmasters($client);
            $endDate = date('Y-m-d');
            $startDate = date('Y-m-d', strtotime('1 month ago'));
            $q = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $q->setStartDate($startDate);
            $q->setEndDate($endDate);
            $q->setDimensions(['query']);
            $q->setSearchType('web');
            $query = $service->searchanalytics->query($weburl, $q);


            $d->setStartDate($startDate);
            $d->setEndDate($endDate);
            $d->setDimensions(['date']);
            $d->setSearchType('web');
            $data = $service->searchanalytics->query($weburl, $d);
        } catch (Google_Service_Exception $e) {

            $data = array();
        }

        echo json_encode($data->rows);
        die();
    }

    public function totalvisits()
    {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        $domainId = $this->input->post('domainId');
        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);

        if (!$client->getAccessToken()) {

            $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
            if ($ga_res) {
              $this->getUserAuthenticated($ga_res['user_id']);  
            }
            
        }
        //getting web property
        $domain  = strtr($domainDetails['domain_name'], array('http://'=>'','https://'=>'','www.'=>'','/'=>''));
        $property = $this->usergaaccount_model->getGaDetailByDomain($domain);
        if ($property['webpropertyid']) {
            $webpropertyid = $property['webpropertyid'];
        }else{
            $webpropertyid = '~all';
        } 

        $analytics = new Google_Service_Analytics($client);
        $profiles = $analytics->management_profiles
                  ->listManagementProfiles($domainDetails['ga_account'], $webpropertyid);
        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();

          // Return the first view (profile) ID.
          $profileId =  $items[0]->getId();
         
          // Add Analytics View ID, prefixed with "ga:"
          $analyticsViewId    = 'ga:'.$profileId;
          $startDate          = date('Y-m-d',strtotime('-30 days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:visits';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
              'dimensions'    => 'ga:date',
              // 'filters'       => 'ga:pagePath==/url/to/product/',
              'sort'          => '-ga:date',
          ));
          
          $items = $data->getRows();
          if($items) {
              $res = array();
              foreach($items as $item){
                  $return[date('Y-m-d', strtotime($item[0]))] = (int)$item[1];
              }
              $res['status'] = 'success';
              $res['payload']['totalVisitsGraph'] = array_reverse($return);
          } else {
              $res['status'] = 'error';
              $res['payload']['totalVisitsGraph'] = null;
          }
          
          echo json_encode($res);die;
        } else {
          throw new Exception('No views (profiles) found for this user.');
        }          

    }

    public function uniquevisitors()
    {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        $domainId = $this->input->post('domainId');
        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
        if (!$client->getAccessToken()) {
            $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
            if ($ga_res) {
              $this->getUserAuthenticated($ga_res['user_id']);  
            }
            
        }
        //getting web property
        $domain  = strtr($domainDetails['domain_name'], array('http://'=>'','https://'=>'','www.'=>'','/'=>''));
        $property = $this->usergaaccount_model->getGaDetailByDomain($domain);
        if ($property['webpropertyid']) {
            $webpropertyid = $property['webpropertyid'];
        }else{
            $webpropertyid = '~all';
        } 

        $analytics = new Google_Service_Analytics($client);
        $profiles = $analytics->management_profiles
                  ->listManagementProfiles($domainDetails['ga_account'], $webpropertyid);

        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();

          // Return the first view (profile) ID.
          $profileId =  $items[0]->getId();
         
          // Add Analytics View ID, prefixed with "ga:"
          $analyticsViewId    = 'ga:'.$profileId;
          $startDate          = date('Y-m-d',strtotime('-30 days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:visitors';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
              'dimensions'    => 'ga:date',
              // 'filters'       => 'ga:pagePath==/url/to/product/',
              'sort'          => '-ga:date',
          ));
          
          $items = $data->getRows();
          if($items) {
              $res = array();
              foreach($items as $item){
                  $return[date('Y-m-d', strtotime($item[0]))] = (int)$item[1];
              }
              $res['status'] = 'success';
              $res['payload']['uniqueVisitorsGraph'] = array_reverse($return);
          } else {
              $res['status'] = 'error';
              $res['payload']['uniqueVisitorsGraph'] = null;
          }
          
          echo json_encode($res);die;
        } else {
          throw new Exception('No views (profiles) found for this user.');
        }          

    }

    public function pagepervisit()
    {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        $domainId = $this->input->post('domainId');
        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
        if (!$client->getAccessToken()) {
          $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
          if ($ga_res) {
            $this->getUserAuthenticated($ga_res['user_id']);
          }
        }

        //getting web property
        $domain  = strtr($domainDetails['domain_name'], array('http://'=>'','https://'=>'','www.'=>'','/'=>''));
        $property = $this->usergaaccount_model->getGaDetailByDomain($domain);
        if ($property['webpropertyid']) {
            $webpropertyid = $property['webpropertyid'];
        }else{
            $webpropertyid = '~all';
        } 

        $analytics = new Google_Service_Analytics($client);
        $profiles = $analytics->management_profiles
                  ->listManagementProfiles($domainDetails['ga_account'], $webpropertyid);

        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();

          // Return the first view (profile) ID.
          $profileId =  $items[0]->getId();
         
          // Add Analytics View ID, prefixed with "ga:"
          $analyticsViewId    = 'ga:'.$profileId;
          $startDate          = date('Y-m-d',strtotime('-30 days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:pageviews';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
              'dimensions'    => 'ga:date',
              // 'filters'       => 'ga:pagePath==/url/to/product/',
              'sort'          => '-ga:date',
          ));
          
          $items = $data->getRows();

          if($items) {
              $res = array();
              foreach($items as $item){
                  $return[date('Y-m-d', strtotime($item[0]))] = (int)$item[1];
              }
              // print_r($return);die;
              $res['status'] = 'success';
              $res['payload']['totalPagePerVisitGraph'] = array_reverse($return);
          } else {
              $res['status'] = 'error';
              $res['payload']['totalPagePerVisitGraph'] = null;
          }
          
          echo json_encode($res);die;
        } else {
          throw new Exception('No views (profiles) found for this user.');
        } 
    }

    public function topcountry()
    {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        $domainId = $this->input->post('domainId');
        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
        if (!$client->getAccessToken()) {
            $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
           if($ga_res) {
             $this->getUserAuthenticated($ga_res['user_id']); 
           }
        }
        //getting web property
        $domain  = strtr($domainDetails['domain_name'], array('http://'=>'','https://'=>'','www.'=>'','/'=>''));
        $property = $this->usergaaccount_model->getGaDetailByDomain($domain);
        if ($property['webpropertyid']) {
            $webpropertyid = $property['webpropertyid'];
        }else{
            $webpropertyid = '~all';
        } 
        $analytics = new Google_Service_Analytics($client);
        $profiles = $analytics->management_profiles
                  ->listManagementProfiles($domainDetails['ga_account'], $webpropertyid);

        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();

          // Return the first view (profile) ID.
          $profileId =  $items[0]->getId();
         
          // Add Analytics View ID, prefixed with "ga:"
          $analyticsViewId    = 'ga:'.$profileId;
          $startDate          = date('Y-m-d',strtotime('-30 days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:visits';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
              'dimensions'    => 'ga:country',
              // 'filters'       => 'ga:pagePath==/url/to/product/',
              'sort'          => '-ga:visits',
          ));
          
          $items = $data->getRows();

          if($items) {
              $res = array();
              $return = array();
              $items = array_slice($items, 0, 15);
              foreach($items as $key=>$item){
                  $return[$key]['label'] = $item[0];
                  $return[$key]['nb_visits'] = $item[1];
              }
              $res['status'] = 'success';
              $res['payload']['topcountries'] = $return;
          } else {
              $res['status'] = 'error';
              $res['payload']['topcountries'] = null;
          }
          
          echo json_encode($res);die;
        } else {
          throw new Exception('No views (profiles) found for this user.');
        } 
    }

    public function visitsources()
    {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        $domainId = $this->input->post('domainId');
        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
        if (!$client->getAccessToken()) {
            $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
           if($ga_res) {
             $this->getUserAuthenticated($ga_res['user_id']); 
           }
        }
        //getting web property
        $domain  = strtr($domainDetails['domain_name'], array('http://'=>'','https://'=>'','www.'=>'','/'=>''));
        $property = $this->usergaaccount_model->getGaDetailByDomain($domain);
        if ($property['webpropertyid']) {
            $webpropertyid = $property['webpropertyid'];
        }else{
            $webpropertyid = '~all';
        } 

        $analytics = new Google_Service_Analytics($client);
        $profiles = $analytics->management_profiles
                  ->listManagementProfiles($domainDetails['ga_account'], $webpropertyid);

        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();

          // Return the first view (profile) ID.
          $profileId =  $items[0]->getId();
         
          // Add Analytics View ID, prefixed with "ga:"
          $analyticsViewId    = 'ga:'.$profileId;
          $startDate          = date('Y-m-d',strtotime('-30 days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:visits';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
              'dimensions'    => 'ga:source',
              // 'filters'       => 'ga:pagePath==/url/to/product/',
              'sort'          => '-ga:visits',
          ));
          
          $items = $data->getRows();

          if($items) {
              $res = array();
              $return = array();
              $items = array_slice($items, 0, 15);
              foreach($items as $key=>$item){
                  $return[$key]['label'] = $item[0];
                  $return[$key]['nb_visits'] = $item[1];
              }
              $res['status'] = 'success';
              $res['payload']['sites'] = $return;
          } else {
              $res['status'] = 'error';
              $res['payload']['sites'] = null;
          }
          
          echo json_encode($res);die;
        } else {
          throw new Exception('No views (profiles) found for this user.');
        } 
    }

    public function referrervisits()
    {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        $domainId = $this->input->post('domainId');
        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
        if (!$client->getAccessToken()) {
            $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
            if ($ga_res) {
              $this->getUserAuthenticated($ga_res['user_id']);
            }
        }
        //getting web property
        $domain  = strtr($domainDetails['domain_name'], array('http://'=>'','https://'=>'','www.'=>'','/'=>''));
        $property = $this->usergaaccount_model->getGaDetailByDomain($domain);
        if ($property['webpropertyid']) {
            $webpropertyid = $property['webpropertyid'];
        }else{
            $webpropertyid = '~all';
        } 

        $analytics = new Google_Service_Analytics($client);
        $profiles = $analytics->management_profiles
                  ->listManagementProfiles($domainDetails['ga_account'], $webpropertyid);

        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();

          // Return the first view (profile) ID.
          $profileId =  $items[0]->getId();
         
          // Add Analytics View ID, prefixed with "ga:"
          $analyticsViewId    = 'ga:'.$profileId;
          $startDate          = date('Y-m-d',strtotime('-30 days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:visits';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
              'dimensions'    => 'ga:sourceMedium',
              // 'filters'       => 'ga:pagePath==/url/to/product/',
              'sort'          => '-ga:visits',
          ));
          
          $items = $data->getRows();

          if($items) {
              $res = array();
              $return = array();
              $organic_total = 0;
              $referral_total = 0;
              $direct_total = 0;
              foreach($items as $key=>$item){
                  $label = '';
                  if(preg_match('/organic/i', $item[0])){
                    $label = 'organic';
                    $organic_total+=$item[1];
                  }
                  if(preg_match('/none/i', $item[0])){
                    $label = 'direct';
                    $direct_total+=$item[1];
                  }
                  if(preg_match('/referral/i', $item[0])){
                    $label = 'referral';
                    $referral_total+=$item[1];
                  }
                  
              }
              $return[] = array('label'=>'direct','nb_visits'=>$direct_total);
              $return[] = array('label'=>'referral','nb_visits'=>$referral_total);
              $return[] = array('label'=>'organic','nb_visits'=>$organic_total);
              $res['status'] = 'success';
              $res['payload']['referrervisit'] = $return;
          } else {
              $res['status'] = 'error';
              $res['payload']['referrervisit'] = null;
          }

          $startDate          = date('Y-m-d',strtotime('-365 days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:visits';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
              'dimensions'    => 'ga:sourceMedium,ga:date',
              // 'filters'       => 'ga:pagePath==/url/to/product/',
              'sort'          => '-ga:date',
          ));
          
          $items = $data->getRows();
          
          if($items) {
              $return = array();
              $organic_total = 0;
              $referral_total = 0;
              $direct_total = 0;
              foreach($items as $key=>$item){
                  $label = '';
                  if(preg_match('/organic/i', $item[0])){
                    $label = 'organic';
                    $return[date('Y-m-01',strtotime($item[1]))]['organic']+=$item[2];
                  }
                  if(preg_match('/none/i', $item[0])){
                    $label = 'direct';
                    $return[date('Y-m-01',strtotime($item[1]))]['direct']+=$item[2];
                    
                  }
                  if(preg_match('/referral/i', $item[0])){
                    $label = 'referral';
                    $return[date('Y-m-01',strtotime($item[1]))]['referral']+=$item[2];
                  }

              }
              // $return[date('Y-m-d',strtotime($item[1]))] = array('label'=>'direct','nb_visits'=>$direct_total);
              $ret = array();
              foreach($return as $key=>$temp){
                $ret[$key][0] = array('label'=>'direct', 'nb_visits'=>$temp['direct']);
                $ret[$key][1] = array('label'=>'organic', 'nb_visits'=>$temp['organic']);
                $ret[$key][2] = array('label'=>'referral', 'nb_visits'=>$temp['referral']);
              }
              
              
              $res['status'] = 'success';
              $res['payload']['referrervisitgraph'] = array_reverse($ret);
          } else {
              $res['status'] = 'error';
              $res['payload']['referrervisitgraph'] = null;
          }

          
          echo json_encode($res);die;
        } else {
          throw new Exception('No views (profiles) found for this user.');
        } 
    }

    public function getvisits()
    {
        $client = $this->client;
        $user_id = $this->ci_auth->get_user_id();
        $domainId = $this->input->post('domainId');
        $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
        
        if (!$client->getAccessToken()) {
            $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
            if ($ga_res) {
              $this->getUserAuthenticated($ga_res['user_id']);
            }
        }
        //getting web property
        $domain  = strtr($domainDetails['domain_name'], array('http://'=>'','https://'=>'','www.'=>'','/'=>''));
        $property = $this->usergaaccount_model->getGaDetailByDomain($domain);
        if ($property['webpropertyid']) {
            $webpropertyid = $property['webpropertyid'];
        }else{
            $webpropertyid = '~all';
        } 

        $analytics = new Google_Service_Analytics($client);
        // print_r($domainDetails['ga_account']);die;
        $profiles = $analytics->management_profiles
                  ->listManagementProfiles($domainDetails['ga_account'], $webpropertyid);
        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();

          // Return the first view (profile) ID.
          $profileId =  $items[0]->getId();
         
          // Add Analytics View ID, prefixed with "ga:"
          $analyticsViewId    = 'ga:'.$profileId;
          $startDate          = date('Y-m-d',strtotime('-30 days', time()));
          $endDate            = date('Y-m-d');
          $metrics            = 'ga:pageviews,ga:visitors,ga:visits';
          $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
            // 'dimensions'    => 'ga:date'
            ));
          
          $items = $data->getRows();
          if($items) {
              $res = array();
              $return = array();
              
              $res['status'] = 'success';
              $res['payload']['totalUniqueVisitors'] = $items[0][1];
              $res['payload']['totalVisitors'] = $items[0][2];
              $res['payload']['totalPagePerVisit'] = $items[0][0];
          } else {
              $res['status'] = 'error';
              $res['payload']['sites'] = null;
          }
          
          echo json_encode($res);die;
        } else {
          throw new Exception('No views (profiles) found for this user.');
        } 
    }

    public function ecommercesummarystats()
    {

      $client = $this->client;
      $user_id = $this->ci_auth->get_user_id();
      $domainId = $this->input->post('domainId');
      $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
      
      if (!$client->getAccessToken()) {
          $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
          if ($ga_res) {
            $this->getUserAuthenticated($ga_res['user_id']);
          }
      }
      //getting web property
      $domain  = strtr($domainDetails['domain_name'], array('http://'=>'','https://'=>'','www.'=>'','/'=>''));
      $property = $this->usergaaccount_model->getGaDetailByDomain($domain);
      if ($property['webpropertyid']) {
          $webpropertyid = $property['webpropertyid'];
      }else{
          $webpropertyid = '~all';
      } 
      
      $analytics = new Google_Service_Analytics($client);
      // print_r($domainDetails['ga_account']);die;
      $profiles = $analytics->management_profiles
                ->listManagementProfiles($domainDetails['ga_account'], $webpropertyid);
      if (count($profiles->getItems()) > 0) {
        $items = $profiles->getItems();

        // Return the first view (profile) ID.
        $profileId =  $items[0]->getId();
       
        // Add Analytics View ID, prefixed with "ga:"
        $analyticsViewId    = 'ga:'.$profileId;
        $startDate          = date('Y-m-d',strtotime('-30 days', time()));
        $endDate            = date('Y-m-d');
        $metrics            = 'ga:transactions,ga:transactionRevenue,ga:transactionsPerSession';
        $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
          'dimensions'    => 'ga:currencyCode'
          ));
        
        $items = $data->getRows();
        if($items) {
            $res = array();
            $return = array();
            
            $res['status'] = 'success';
            $res['payload']['ecommercestats']['total_sale'] = number_format($items[1][1],2);
            $res['payload']['ecommercestats']['sales_value'] = number_format($items[1][2],2);
            $res['payload']['ecommercestats']['average_order'] = number_format($items[1][3],2);
            $res['payload']['ecommercestats']['currency'] = $items[1][0];
        } else {
            $res['status'] = 'error';
            $res['payload']['ecommercestats']['total_sale'] = null;
            $res['payload']['ecommercestats']['sales_value'] = null;
            $res['payload']['ecommercestats']['average_order'] = null;
            $res['payload']['ecommercestats']['currency'] = null;
        }
        
        echo json_encode($res);die;
      } else {
        throw new Exception('No views (profiles) found for this user.');
      } 
      
    }

    public function ecommerceproductdata()
    {
      $client = $this->client;
      $user_id = $this->ci_auth->get_user_id();
      $domainId = $this->input->post('domainId');
      $domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($user_id,$domainId);
      
      if (!$client->getAccessToken()) {
          $ga_res = $this->usergaaccount_model->getUserIdByGaCode($domainDetails['ga_account']);
          if ($ga_res) {
            $this->getUserAuthenticated($ga_res['user_id']);
          }
      }
      //getting web property
      $domain  = strtr($domainDetails['domain_name'], array('http://'=>'','https://'=>'','www.'=>'','/'=>''));
      $property = $this->usergaaccount_model->getGaDetailByDomain($domain);
      if ($property['webpropertyid']) {
          $webpropertyid = $property['webpropertyid'];
      }else{
          $webpropertyid = '~all';
      } 
      
      $analytics = new Google_Service_Analytics($client);
      // print_r($domainDetails['ga_account']);die;
      $profiles = $analytics->management_profiles
                ->listManagementProfiles($domainDetails['ga_account'], $webpropertyid);
      if (count($profiles->getItems()) > 0) {
        $items = $profiles->getItems();

        // Return the first view (profile) ID.
        $profileId =  $items[0]->getId();
       
        // Add Analytics View ID, prefixed with "ga:"
        $analyticsViewId    = 'ga:'.$profileId;
        $startDate          = date('Y-m-d',strtotime('-30 days', time()));
        $endDate            = date('Y-m-d');
        $metrics            = 'ga:transactionRevenue,ga:itemQuantity,ga:transactions,ga:transactionsPerSession';
        $data = $analytics->data_ga->get($analyticsViewId, $startDate, $endDate, $metrics, array(
          'dimensions'    => 'ga:productName'
          ));
        
        $items = $data->getRows();
        print_r($items);die;
        if($items) {
            $res = array();
            $return = array();
            
            $res['status'] = 'success';
            $res['payload']['ecommercestats']['total_sale'] = $items[1][1];
            $res['payload']['ecommercestats']['sales_value'] = $items[1][2];
            $res['payload']['ecommercestats']['average_order'] = $items[1][3];
            $res['payload']['ecommercestats']['currency'] = $items[1][0];
        } else {
            $res['status'] = 'error';
            $res['payload']['ecommercestats']['total_sale'] = null;
            $res['payload']['ecommercestats']['sales_value'] = null;
            $res['payload']['ecommercestats']['average_order'] = null;
            $res['payload']['ecommercestats']['currency'] = null;
        }
        
        echo json_encode($res);die;
      } else {
        throw new Exception('No views (profiles) found for this user.');
      }  
    }


}
