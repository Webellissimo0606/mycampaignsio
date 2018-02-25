<?php

/**
 * CIMembership
 * 
 * @package		Libraries
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Uptime extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library('form_validation');
        $this->load->library('my_form_validation');
        $this->load->model('uptime/uptime_model');
        $this->load->library('upmonitor');
        $this->load->library('session');
    }

    function index() {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(FALSE)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $user_id = $this->ci_auth->get_user_id();

                $this->load->view(get_template_directory() . 'uptime');
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    function createuseruptime() {

        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(FALSE)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $user_id = $this->ci_auth->get_user_id();
                $user_data = $this->uptime_model->user_data($user_id);
                $result = array();
                $var = array("username" => $user_data[0]->username, "password" => $this->session->userdata('uptime'), "email" => $user_data[0]->email, "groups" => array('Subscribers'));
                $vars = json_encode($var);
                $result = $this->createuser($vars);
                $result = json_decode($result, true);
                if (!empty($result)) {
                    if (isset($result['id']) && !empty($result['id'])) {
                        $check = array();
                        $check = $this->uptime_model->get_user_data($this->ci_auth->get_user_id());
                        if (count($check) == 0) {
                            $insert_data['user_id'] = $result['id'];
                            $insert_data['username'] = $result['username'];
                            $insert_data['password'] = $result['password'];
                            $insert_data['loggedin_Id'] = $this->ci_auth->get_user_id();
                            $user_id = $this->uptime_model->insert_user_data($insert_data);
                            redirect(site_url('/auth/profile'));
                        }
                    }
                   
                } else {

                    redirect(site_url('/auth/profile'));
                }
                redirect(site_url('/auth/profile'));
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    function uptime() {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(FALSE)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $this->load->view(get_template_directory() . 'uptime');
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    function createuser($vars) {
        $ch = curl_init('http://soulseekah.no-ip.biz:5000/api/v1/users/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token 5e9dc4ca03960ef8fb8249090ae1f4f39c472efc',
            'Content-Type: application/json', //  5e9dc4ca03960ef8fb8249090ae1f4f39c472efc                                         
            'Content-Length: ' . strlen($vars))
        );

        $result = curl_exec($ch);
        return $result;
    }

    function addsiteuptime() {
        $results = array();
        $user_id = $this->ci_auth->get_user_id();
        $user_data = $this->uptime_model->get_user_data($user_id);
        $keywords = explode(",", $this->input->post('site_keywords'));
        $result = $this->create_site($user_data[0]->user_id, $user_data[0]->username, $user_data[0]->password, $this->input->post('site_url'), $keywords, str_replace(' ', '_', $this->input->post('site_name')));
        $results = json_decode($result, true);
        if (!empty($results)) {
            if (isset($results['id'])) {
                $insert_data['user_id'] = $results['user'];
                $insert_data['name'] = $results['name'];
                $insert_data['url'] = $results['url'];
                $insert_data['loggedin_Id'] = $this->ci_auth->get_user_id();
                $insert_data['response'] = $result;
                $data = $this->uptime_model->add_site($insert_data);


                redirect(site_url('/uptime/uptime'));
            }
        }
    }

    function create_site($user_id, $username, $password, $url, $keywords, $name) {
        //echo '<pre>';print_r($name);echo '</pre>';die();
        $vars = array('username' => $username,
            'password' => $password);

        $ch = curl_init('http://soulseekah.no-ip.biz:5000/api/v1/authenticate/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $token = json_decode($result);

        $varss = array('user' => (int) $user_id,
            'name' => (string) $name,
            'keywords' => $keywords,
            'url' => $url
        );

        /* 	print_r($vars);
          exit(); */
        $data_string = json_encode($varss);

        $ch = curl_init('http://soulseekah.no-ip.biz:5000/api/v1/sites/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token ' . $token->token,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        return $result;
    }

    function handshake($method = "", $process = "", $vars = "", $accesstoken = "", $param = "") {


        $ch = curl_init('http://soulseekah.no-ip.biz:5000/api/v1/users/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token 5e9dc4ca03960ef8fb8249090ae1f4f39c472efc',
            'Content-Type: application/json', //  5e9dc4ca03960ef8fb8249090ae1f4f39c472efc                                         
            'Content-Length: ' . strlen($vars))
        );

        $result = curl_exec($ch);
        return $result;
        /* $headers[] = 'Authorization: Token 5e9dc4ca03960ef8fb8249090ae1f4f39c472efc';
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); */


        /* $url="http://soulseekah.no-ip.biz:5000/api/v1/".$process."/".$param ;
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL,$url);

          if($method=="POST"){
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);
          //Post Fields
          }

          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          if($accesstoken != "")
          {
          $headers[] = 'Authorization: Token '.$accesstoken;
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          }

          $server_output = curl_exec ($ch);
          curl_close ($ch);
          return  $server_output ; */
    }

    function authenticate($id, $username, $password) {
        $vars = array('username' => $username, 'password' => $password);
        $process = "authenticate";
        $data = $this->handshake("POST", $process, $vars);
        return $data;
    }

    function deauthenticate() {
        
    }

    function ping() {

        $vars = array();
        $process = "ping";
        $accesstoken = "5e9dc4ca03960ef8fb8249090ae1f4f39c472efc";
        $data = $this->handshake("GET", $process, $vars, $accesstoken);
        return $data;
    }

    function getsites($id = "") {

        if ($id) {
            $vars = array();
            $process = "sites";
            $accesstoken = "5e9dc4ca03960ef8fb8249090ae1f4f39c472efc";
            $data = $this->handshake("GET", $process, $vars, $accesstoken, $id);
            return $data;
        } else {
            $vars = array();
            $process = "sites";
            $accesstoken = "5e9dc4ca03960ef8fb8249090ae1f4f39c472efc";
            $data = $this->handshake("GET", $process, $vars, $accesstoken);
            return $data;
        }
    }

    /* function createsites($vars)
      {

      $process = "sites";
      $data = $this->handshake("POST",$process,$vars);
      return $data;
      } */

    function updatesites($id) {
        
    }

    function deletesites($id) {
        
    }




    /* 	function createuser($vars)
      {
      $process = "users";
      $accesstoken = "5e9dc4ca03960ef8fb8249090ae1f4f39c472efc";
      $data = $this->handshake("POST",$process,$vars,$accesstoken);
      return $data;
      } */
}
