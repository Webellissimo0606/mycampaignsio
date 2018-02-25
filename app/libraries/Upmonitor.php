<?php
/**
 * CIMembership
 *
 * @package        Libraries
 * @author        1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license        http://opensource.org/licenses/MIT    MIT License
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Upmonitor
{
    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('session');
        $this->ci->load->database();
        $this->salt = 'X9WX^YvmY!5]\LnD';
    }

    public function createuser($vars)
    {

        $vars1 = array('username' => 'admin', 'password' => 'aenaiCu8epo1');
        $ch    = curl_init('http://api.upmonitor.io/api/v1/authenticate/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $token  = json_decode($result);
        $token  = $token['token'];

        
        $vars['password'] = sha1($vars['username'].$this->salt);

        $ch = curl_init('http://api.upmonitor.io/api/v1/users/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token ' . $token,
            'Content-Type: application/json', //  52c8e3fd6a4acffb41fcc2a448f287709261dbab
            'Content-Length: ' . strlen($vars))
        );

        $result = curl_exec($ch);

        return $result;

    }

    public function create_site($user_id, $token, $username, $password, $url, $keywords)
    {
		if($token == '' || $token == null) {
           $vars = array('username' => $username,
               'password'               => sha1($username.$this->salt));

           $ch = curl_init('http://api.upmonitor.io/api/v1/authenticate/');
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
           curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           $result = curl_exec($ch);
           $token  = json_decode($result); 
           $token = $token['token'];
        }

        $domainname   = explode('.', parse_url($url, PHP_URL_HOST));
        $keywords     = json_decode($keywords);
        $keywordArray = array();
		
        if ($keywords) {
            $keywordArray[0] = stripslashes($keywords->header);
            $keywordArray[1] = stripslashes($keywords->body);
            $keywordArray[2] = stripslashes($keywords->footer);
        }
        $varss = array('user' => (int) $user_id,
            'name'                => isset( $domainname[1] ) ? $domainname[1] : $domainname[0],
            'url'                 => $url,
            'parameters'          => array(
                'checks' => array(
                    array(
                        'module'     => 'http',
                        'locations'  => array("EU", "ASIA", "US"),
                        'interval'   => intval($keywords->frequency),
                        'parameters' => array(
                            'status'   => 200,
                            'keywords' => $keywordArray,
                        )),
                )),
        );

        /*    print_r($keywords);
        print_r($keywords->header);
        print_r($keywords->body);
        print_r($keywords->footer);
        exit();*/

        /*    $varss = array('user' => (int) $user_id,
        'name' => 'pradeep',
        'url' => 'http://www.pradeep.com',
        'parameters' => 'test123'
        );*/

        $data_string = json_encode($varss);
		
        $ch = curl_init('http://api.upmonitor.io/api/v1/sites/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token ' . $token,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        return $result;

    }

    public function handshake($method = "", $process = "", $vars = "", $accesstoken = "", $param = "")
    {

        $ch = curl_init('http://api.upmonitor.io/api/v1/users/');
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  */

        /*$url="http://api.upmonitor.io/api/v1/".$process."/".$param ;
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
    return  $server_output ;*/

    }

    public function authenticate($id, $username, $password)
    {
        $vars    = array('username' => $username, 'password' => $password);
        $process = "authenticate";
        $data    = $this->handshake("POST", $process, $vars);
        return $data;
    }

    public function deauthenticate()
    {
        $vars = array('username' => 'admin',
            'password'               => 'aenaiCu8epo1');

        $ch = curl_init('http://api.upmonitor.io/api/v1/authenticate/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $token  = json_decode($result, true);
        $ch     = curl_init('http://api.upmonitor.io/api/v1/deauthenticate/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token ' . $token['token'],
            'Content-Type: application/json', //  5e9dc4ca03960ef8fb8249090ae1f4f39c472efc
        )
        );
        $result = curl_exec($ch);

    }

    public function ping()
    {

        $vars        = array();
        $process     = "ping";
        $accesstoken = "5e9dc4ca03960ef8fb8249090ae1f4f39c472efc";
        $data        = $this->handshake("GET", $process, $vars, $accesstoken);
        return $data;

    }

    
    public function update_site($user_id,$token, $username, $password, $url, $keywords, $uptime_site_id)
    {
        if($token == '' || $token == null) {
           $vars = array('username' => $username,
               'password'               => sha1($username.$this->salt));

           $ch = curl_init('http://api.upmonitor.io/api/v1/authenticate/');
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
           curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           $result = curl_exec($ch);
           $token  = json_decode($result); 
           $token = $token['token'];
        }
        
        $domainname   = explode('.', parse_url($url, PHP_URL_HOST));
        $keywords     = json_decode($keywords);
        $keywordArray = array();
        if ($keywords) {
            $keywordArray[0] = stripslashes($keywords->header);
            $keywordArray[1] = stripslashes($keywords->body);
            $keywordArray[2] = stripslashes($keywords->footer);
        }
        $varss = array('user' => (int) $user_id,
            'name'                => $domainname[1],
            'url'                 => $url,
            'parameters'          => array(
                'checks' => array(
                    array(
                        'module'     => 'http',
                        'locations'  => array("EU", "ASIA", "US"),
                        'interval'   => intval($keywords->frequency),
                        'parameters' => array(
                            'status'   => 200,
                            'keywords' => $keywordArray,
                        )),
                )),
        );

        $data_string = json_encode($varss);
        $ch = curl_init('http://api.upmonitor.io/api/v1/sites/'.$uptime_site_id.'/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token ' . $token,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );


        $result = curl_exec($ch);
        return $result;

    }


    /*function createsites($vars)
    {

    $process = "sites";
    $data = $this->handshake("POST",$process,$vars);
    return $data;
    }*/

    public function updatesites($id)
    {

    }

    public function deletesites($id)
    {

    }

/*    function createuser($vars)
{
$process = "users";
$accesstoken = "5e9dc4ca03960ef8fb8249090ae1f4f39c472efc";
$data = $this->handshake("POST",$process,$vars,$accesstoken);
return $data;
}*/

}
