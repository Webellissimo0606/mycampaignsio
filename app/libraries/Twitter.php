<?php 
/**
 * CIMembership
 * 
 * @package		Libraries
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH. '/third_party/Socialauth/Twitter/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter 
{
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('phpass');
		$this->ci->load->library('session');
		$this->ci->load->database();
		$this->ci->load->model('auth/users');
		$this->ci->load->model('auth/usergroups_model');
		$this->callback = site_url('auth/oauth/twcallback');
	}

	function getUser()
	{
		if($this->ci->session->userdata('tw_access_token') && $this->ci->session->userdata('tw_access_token_secret'))
		{
			$this->twconnect = new TwitterOAuth($this->ci->config->item('tw_consumer_key'), $this->ci->config->item('tw_consumer_secret'),$this->ci->session->userdata('tw_access_token'),$this->ci->session->userdata('tw_access_token_secret'));
			$tuser = $this->twconnect->get("account/verify_credentials");

			/* If HTTP response is 200 continue otherwise send to connect page to retry */
			if (200 == $this->twconnect->getLastHttpCode()) {
			 
			  /* The user has been verified and the access tokens can be saved for future use */
			  return ($tuser);
			} else {
				
				/* Remove no longer needed request tokens */
				$this->ci->session->unset_userdata('tw_access_token');
				$this->ci->session->unset_userdata('tw_access_token_secret');
				
				/* Save HTTP status for error dialog on connnect page.*/
				$this->getUser();
			}	
		} else {
			
			/* Build TwitterOAuth object with client credentials. */
			$this->twoauth = new TwitterOAuth($this->ci->config->item('tw_consumer_key'), $this->ci->config->item('tw_consumer_secret'));
			
			/* Get temporary credentials. */
			$request_token = $this->twoauth->oauth('oauth/request_token', array('oauth_callback' => $this->callback));

			/* Save temporary credentials to session. */
			$this->ci->session->set_userdata('tw_oauth_token', $request_token['oauth_token']);
			$this->ci->session->set_userdata('tw_oauth_token_secret', $request_token['oauth_token_secret']);

			/* If last connection failed don't display authorization link. */
			switch ($this->twoauth->getLastHttpCode()) {
				case 200:
					/* Build authorize URL and redirect user to Twitter. */
					$url = $this->twoauth->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));
					redirect($url);
					break;
				default:
					/* Show notification if something went wrong. */
					//echo 'Could not connect to Twitter. Refresh the page or try again later.'; exit;
					redirect('auth/socialauth/logout');
			}
		}
	}

	function twcallback() 
	{
		/* If the oauth_token is old redirect to the connect page. */
		if (isset($_REQUEST['oauth_token']) && $this->ci->session->userdata('tw_oauth_token') !== $_REQUEST['oauth_token']) {
			$this->ci->session->set_userdata('tw_oauth_status', 'oldtoken');
			redirect('auth/socialauth/logout');
		}

		if (! isset($_REQUEST['oauth_token'])) {
			$this->ci->session->set_flashdata('errors','Login with twitter - access denied by the user');
			redirect(site_url('auth/login'));
		}

		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$this->twconnection = new TwitterOAuth($this->ci->config->item('tw_consumer_key'), $this->ci->config->item('tw_consumer_secret'),$this->ci->session->userdata('tw_oauth_token'),$this->ci->session->userdata('tw_oauth_token_secret'));

		/* Request access tokens from twitter */
		$access_token = $this->twconnection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));

		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$this->ci->session->set_userdata('tw_access_token', $access_token['oauth_token']);
		$this->ci->session->set_userdata('tw_access_token_secret', $access_token['oauth_token_secret']);

		/* Remove no longer needed request tokens */
		$this->ci->session->unset_userdata('tw_oauth_token');
		$this->ci->session->unset_userdata('tw_oauth_token_secret');

		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $this->twconnection->getLastHttpCode()) {
		  
		  /* The user has been verified and the access tokens can be saved for future use */
		  return ($access_token['user_id']);
		} else {

		  /* Save HTTP status for error dialog on connnect page.*/
			redirect('auth/socialauth/logout');
		}	
	}

	function getUserProfile()
	{
		$this->twconnect = new TwitterOAuth($this->ci->config->item('tw_consumer_key'), $this->ci->config->item('tw_consumer_secret'),$this->ci->session->userdata('tw_access_token'),$this->ci->session->userdata('tw_access_token_secret'));
		$tuser = $this->twconnect->get("account/verify_credentials");
		
		/*$adata = $this->twconnect->get("users/show", array("user_id" => $tuser->id));
		$tuser ->userlocation = $adata->location;*/
		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $this->twconnect->getLastHttpCode()) {
		  /* The user has been verified and the access tokens can be saved for future use */
		  return ($tuser);
		} else {

		  /* Save HTTP status for error dialog on connnect page.*/
			redirect('auth/socialauth/logout');
		}	
	}
}