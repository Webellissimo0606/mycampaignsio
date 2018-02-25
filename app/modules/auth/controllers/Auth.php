<?php

/**
 * CIMembership
 *
 * @package        Modules
 * @author        1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license        http://opensource.org/licenses/MIT    MIT License
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Auth extends CI_Controller {

	public $api_key = 'pnEbwmLjA8mxcNyRVSeZ';

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
		$this->load->model('auth/analyze_model');
		$this->load->model('serp/domain_model');
		$this->load->model('auth/groups_model');
		$this->load->model('auth/user_model');
		$this->load->model('Staff_Model');
		$this->load->library('upmonitor');
		$this->load->library('Google_Auth', '', 'Google_Authenticate');
		$this->load->library('gtmetrix');
		$this->load->library('general');
		$this->ci = &get_instance();
	}

	public function index() {
		
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			/* logged in */

			if ($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('auth/profile'));
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}

	public function adduptime($post, $ins_site_id) {

		$url = $post['domain'];

		if (false === strpos($url, '://')) {
			$url = 'http://' . $url;
		}

		$parse = parse_url($url);
		//$parse['host']
		$keywords = array();

		if ((isset($post['page_header'])) || (isset($post['page_header'])) || (isset($post['page_header']))) {
			$keywords = array(
				'header' => $post['page_header'],
				'body' => $post['page_body'],
				'footer' => $post['page_footer'],
				'frequency' => $data['frequency'],
			);
		}

		$json_keywords = json_encode($keywords);

		// get upmonitor existing user id

		$is_upmon = $this->analyze_model->get_upmon($this->ci_auth->get_user_id());
		$is_api = $is_upmon[0]->upmon_id;

		if (0 !== $is_api) {

			$upm_siteid = $this->upmonitor->create_site($is_api, $is_upmon[0]->uptime_token, $is_upmon[0]->username, $is_upmon[0]->password, $url, $json_keywords);

			$upm_siteid = json_decode($upm_siteid);
			$upm_id = array();
			$upm_id['up_time_id '] = $upm_siteid->id;
			$data['up_time_id'] = $upm_siteid->id;
			$data['userid'] = $this->ci_auth->get_user_id();
			$data['domain_id'] = $ins_site_id;
			if ($json_keywords) {
				$data['keyword'] = $json_keywords;
			} else {
				$data['keyword'] = '';
			}

			$result = $this->analyze_model->save_uptime($ins_site_id, $data);

		} else {

			$user_data = $this->analyze_model->getusersinfo($this->ci_auth->get_user_id());

			$vars = array(
				'username' => $user_data[0]->username,
				'password' => $user_data[0]->password,
				'email' => $user_data[0]->email,
				// 'first_name' => $user_profile_data['first_name'],
				// 'last_name' => $user_profile_data['last_name'],
				'groups' => array('Subscribers'),
			);

			$vars = json_encode($vars);

			$us_id = $this->upmonitor->createuser($vars);
			$us_id = json_decode($us_id);
			$upmonid = array();
			$upmonid['upmon_id'] = $us_id->id;
			$this->analyze_model->save_upmon($this->session->userdata('user_id'), $upmonid);

			$is_upmon = $this->analyze_model->get_upmon($this->ci_auth->get_user_id());
			$is_api = $is_upmon[0]->upmon_id;
			$upm_siteid = $this->upmonitor->create_site($is_api, $is_upmon[0]->username, $is_upmon[0]->password, $url, $json_keywords);

			$upm_siteid = json_decode($upm_siteid);
			$upm_id = array();
			$upm_id['up_time_id '] = $upm_siteid->id;
			$data['up_time_id'] = $upm_siteid->id;
			$data['userid'] = $this->ci_auth->get_user_id();
			$data['domain_id'] = $ins_site_id;
			if ($json_keywords) {
				$data['keyword'] = $json_keywords;
			} else {
				$data['keyword'] = '';
			}
			$result = $this->analyze_model->save_uptime($ins_site_id, $data);
		}
		// end upmonitor existing user id
	}

	public function add_project() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			/* logged in */

			if ($this->ci_auth->canDo('login_to_frontend')) {
				if ($_POST) {

					$url = $_POST['domain_name'];

					if (false === strpos($url, '://')) {
						$url = 'http://' . $url;
					}

					$parse = parse_url($url);
					//$parse['host']
					$keywords = array();

					if ((isset($_POST['page_header'])) || (isset($_POST['page_header'])) || (isset($_POST['page_header']))) {
						$keywords = array(
							'header' => $_POST['page_header'],
							'body' => $_POST['page_body'],
							'footer' => $_POST['page_footer'],
							'frquency' => $_POST['frequency'],
						);
					}

					$json_keywords = json_encode($keywords);
					$keywords = serialize($keywords);
					$vars = array(
						'domain_name' => $url,
						'keyword' => $keywords,
						'userid' => $this->ci_auth->get_user_id(),
						'created' => date('y-m-d h:i:s', time()),
					);

					$ins_site_id = $this->analyze_model->add_project($vars);

					// get upmonitor existing user id

					$is_upmon = $this->analyze_model->get_upmon($this->ci_auth->get_user_id());
					$is_api = $is_upmon[0]->upmon_id;

					if ($is_api != 0) {

						$upm_siteid = $this->upmonitor->create_site($is_api, $is_upmon[0]->username, $is_upmon[0]->password, $url, $json_keywords);

						$upm_siteid = json_decode($upm_siteid);
						$upm_id = array();
						$upm_id['up_time_id '] = $upm_siteid->id;
						$result = $this->analyze_model->save_uptime($ins_site_id, $upm_id);
						echo "<script>alert('" . $result . "')</script>";
					} else {

						$user_data = $this->analyze_model->getusersinfo($this->ci_auth->get_user_id());

						$vars = array(
							'username' => $user_data[0]->username,
							'password' => $user_data[0]->password,
							'email' => $user_data[0]->email,
							// 'first_name' => $user_profile_data['first_name'],
							// 'last_name' => $user_profile_data['last_name'],
							'groups' => array('Subscribers'),
						);

						$vars = json_encode($vars);

						$us_id = $this->upmonitor->createuser($vars);
						$us_id = json_decode($us_id);
						$upmonid = array();
						$upmonid['upmon_id'] = $us_id->id;
						$this->analyze_model->save_upmon($this->session->userdata('user_id'), $upmonid);

						$is_upmon = $this->analyze_model->get_upmon($this->ci_auth->get_user_id());
						$is_api = $is_upmon[0]->upmon_id;
						$upm_siteid = $this->upmonitor->create_site($is_api, $is_upmon[0]->uptime_token, $is_upmon[0]->username, $is_upmon[0]->password, $url, $json_keywords);

						$upm_siteid = json_decode($upm_siteid);
						$upm_id = array();
						$upm_id['up_time_id '] = $upm_siteid->id;
						$result = $this->analyze_model->save_uptime($ins_site_id, $upm_id);
						echo "<script>alert('" . $result . "')</script>";
					}

					// end upmonitor existing user id
				}
				$this->load->config('configuration', true);
				$userid = $this->ci_auth->get_user_id();
				$data['client_id'] = $this->config->config['google_oauth']['client_id'];
				$data['redirect_uri'] = $this->config->config['google_oauth']['redirect_uri'];
				$data['domain_settings'] = $this->analyze_model->getDomainDetails($userid);
				$user_auth = $this->analyze_model->getUserOAuthDetails($userid);
				$google_connect = 0;
				if ($user_auth) {
					$google_connect = 1;
				}
				$data['google_connect'] = $google_connect;
				//getting all the subusers
				$subusers = $this->analyze_model->getallsubuserbyuserId($userid);
				$data['subusers'] = $subusers;

				//getting all groups of ussers
				$groups = $this->groups_model->getGroupsByUserId($userid);
				$data['groups'] = $groups;

				$this->load->view(get_template_directory() . '/add_project_new', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}

	public function edit_project() {

		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			/* logged in */

			if ($this->ci_auth->canDo('login_to_frontend')) {
				$this->load->config('configuration', true);

				$userid = $this->ci_auth->get_user_id();

				$domainId = $this->uri->segment(3);

				// TODO: Remove endpoint.
				redirect( base_url( 'domains/' . $domainId . '/edit' ) );
		        exit;




				//getting the domain details
				$domainDetails = $this->analyze_model->getDomainByUserIdAndDomainId($userid, $domainId);
				$data['domainDetails'] = $domainDetails;

				//getting uptime
				$uptime = $this->analyze_model->getUptimeByDomainId($domainId);
				$uptime['keyword'] = json_decode($uptime['keyword'], true);
				$data['uptimeDetails'] = $uptime;

				//getting search engine
				$searchEngine = $this->analyze_model->getSearchEngineByDomainId($domainId);
				$data['searchEngine'] = $searchEngine;

				//getting keywords
				$keywords = $this->analyze_model->getkeywordByDomainId($domainId);
				$keyword = '';
				foreach ($keywords as $km) {
					$keyword .= $km['name'] . "\r\n";
				}
				$data['keywordsDetail'] = $keyword;
				//getting local search towns
				$localTowns = $this->analyze_model->getLocalSearchTownsByDomainId($domainId);
				$data['localTownsDetail'] = $localTowns;
				// print_r($localTowns);die;

				$data['client_id'] = $this->config->config['google_oauth']['client_id'];
				$data['redirect_uri'] = $this->config->config['google_oauth']['redirect_uri'];
				$data['domain_settings'] = $this->analyze_model->getDomainDetails($userid);
				$user_auth = $this->analyze_model->getUserOAuthDetails($userid);
				$google_connect = 0;
				if ($user_auth) {
					$google_connect = 1;
				}
				$data['google_connect'] = $google_connect;
				$data['domainId'] = $domainId;

				//getting all the subusers
				$subusers = $this->analyze_model->getallsubuserbyuserId($userid);
				$data['subusers'] = $subusers;

				//getting all groups of ussers
				$groups = $this->groups_model->getGroupsByUserId($userid);
				$data['groups'] = $groups;

				//getting all subuser of the domain
				$data['domainSubusers'] = $this->analyze_model->getSubusersByDomainIdAndParentId($domainId, $userid);
				//getting all groups of domain
				$data['domainGroups'] = $this->groups_model->getGroupsByDomainId($domainId);

				$this->load->view(get_template_directory() . '/edit_project', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}

	public function getResult_Immediate() {
		$data = array();
		$data1 = array();
		/* $data['test'] = '';
        foreach ($_POST as $key => $value) {
            $data['test'] .= $key .'= '.$value;
            $data['test'] .= ' , ';
        } */
		//$this->domain_model->insertdata($data);
		$requestId = $this->input->get('requestId');
		//$keyword_id = $this->input->get('keyword_id');
		// $search_engine_id = $this->input->get('search_engine_id');
		@list($user_id, $domain_id, $keyword_id, $search_engine_id) = explode(':', $requestId);
		$auth_token = '44E7hMC1UcYVoUVpsx7U';
		$domain_result = $this->analyze_model->getDomain($domain_id);
		$response = $_POST['json_callback']; //file_get_contents('php://input');
		//$response = $_POST['Results'];
		if ($response != '' && !empty($domain_result)) {
			$domain_url = $domain_result[0]->domain_name;
			$json_callback = $response; //json_decode($response)->json_callback;
			$file = $json_callback . '&auth_token=' . $auth_token;
			$result = file_get_contents($file);
			if ($result != '' && !empty($domain_result)) {
				$data['position'] = 0;
				$data['user_id'] = $user_id;
				$data['domain_id'] = $domain_id;
				$data['keyword'] = json_decode($result)->keyword;
				if (json_decode($result)->mobile == 1) {
					$data['local'] = json_decode($result)->locale . '-mobile';
				} else {
					$data['local'] = json_decode($result)->locale;
				}
				$data['search_engine'] = json_decode($result)->engine;

				$data['search_engine_id'] = $search_engine_id;
				$data['keyword_id'] = $keyword_id;
				$serp = json_decode($result)->serp;

				foreach ($serp as $key => $value) {
					$domain_url_res = $value->href;
					if ($this->compareUrls($domain_url, $domain_url_res)) {
						$data['position'] = $key;
						break;
					}
				}
				$fileds = array('id', 'user_id', 'domain_id', 'search_engine_id', 'keyword_id', 'search_engine', 'local', 'keyword', 'position', 'created_date', 'updated_date');
				$match = array('domain_id' => $data['domain_id'], 'keyword_id' => $data['keyword_id'], 'search_engine_id' => $data['search_engine_id']);
				$last_serp = $this->analyze_model->getlastSerp($fileds, $match, '', '=');

				if (!empty($last_serp)) {
					$serp_id = $last_serp[0]['id'];
					$history['user_id'] = $last_serp[0]['user_id'];
					$history['domain_id'] = $last_serp[0]['domain_id'];
					$history['search_engine_id'] = $last_serp[0]['search_engine_id'];
					$history['keyword_id'] = $last_serp[0]['keyword_id'];
					$history['search_engine'] = $last_serp[0]['search_engine'];
					$history['local'] = $last_serp[0]['local'];
					$history['keyword'] = $last_serp[0]['keyword'];
					$history['position'] = $last_serp[0]['position'];
					$history['compare_date'] = $last_serp[0]['updated_date'];
					$history['created_date'] = date('Y-m-d H:i:s');

					$query = "select id from serp_history where domain_id='" . $data['domain_id'] . "' and date(created_date)='" . date('Y-m-d') . "' limit 1";
					$query = $this->db->query($query);
					$result = $query->row_array();
					if (!$result) {
						$userinfo = $this->analyze_model->getUserInfoByDomainId($data['domain_id']);
						//sending notification to push bullet
						$userProfile = $this->analyze_model->getuserprofile($userinfo['id']);
						// if ($userProfile['pushbullet_api']) {
						//     Pushbullet\Connection::setCurlCallback(function ($curl) {
						//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
						//     });
						//     $domainDetails = $this->analyze_model->getDomain($data['domain_id']);
						//     $pb            = new Pushbullet\Pushbullet($userProfile['pushbullet_api']);
						//     $pb->allDevices()->pushNote('Keywords update notification', "Keywords updated for " . date('d M Y') . " for " . $domainDetails[0]->domain_name . " For more detailed analysis, please visit http://app.campaigns.io/auth/dashboard.html");
						// }
					}
					$update = $this->analyze_model->updateSerp($data, $serp_id);
					$this->analyze_model->insertHistory($history);

				} else {
					$query = "select id from serp where domain_id='" . $data['domain_id'] . "' and date(created_date)='" . date('Y-m-d') . "' limit 1";
					$query = $this->db->query($query);
					$result = $query->row_array();
					if (!$result) {
						$userinfo = $this->analyze_model->getUserInfoByDomainId($data['domain_id']);
						//sending notification to push bullet
						$userProfile = $this->analyze_model->getuserprofile($userinfo['id']);
						// if ($userProfile['pushbullet_api']) {
						//     Pushbullet\Connection::setCurlCallback(function ($curl) {
						//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
						//     });
						//     $pb            = new Pushbullet\Pushbullet($userProfile['pushbullet_api']);
						//     $domainDetails = $this->analyze_model->getDomain($data['domain_id']);
						//     $pb->allDevices()->pushNote('Keywords update notification', "Keywords updated for " . date('d M Y') . " for " . $domainDetails[0]->domain_name . " For more detailed analysis, please visit http://app.campaigns.io/auth/dashboard.html");
						// }
					}
					$data['created_date'] = date('Y-m-d H:i:s');
					$this->analyze_model->insertdata($data);

				}

			}
		}
	}

	public function exportkeywordcsv($domainid) {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			/* logged in */
			if ($this->ci_auth->canDo('login_to_frontend')) {
				$userId = $this->ci_auth->get_user_id();
				$result = $this->analyze_model->exportKeywordCsv($domainid, $userId);

				$domainDetails = $this->analyze_model->getDomain($domainid);
				if ($result) {
					$this->general->exportToCSV($result, parse_url($domainDetails[0]->domain_name, PHP_URL_HOST) . "_serp_keywords.csv");
				} else {
					return false;
				}
			}
		}
	}

	public function get_domains() {
		$userid = $this->ci_auth->get_user_id();
		$domains = $this->analyze_model->getDomainDetails($userid);

		foreach ($domains as $key => $domain) {
			$domains[$key]->host = parse_url($domain->domain_name, PHP_URL_HOST);
		}

		echo json_encode($domains);
		die();
	}

	public function set_selected_domain() {

		$selectedDomain = array(
			"domainId" => $this->input->post('domain_id'),
			"monitorMalware" => $this->input->post('monitorMalware'),
			"adminURL" => $this->input->post('adminURL'),
			"adminUsername" => $this->input->post('adminUsername'),
			"domainUrl" => $this->input->post('domain_url'),
			"gaAccount" => $this->input->post('ga_account'),
			"connectToGoogle" => $this->input->post('connectToGoogle'),
			"monitorOnPageIssues" => $this->input->post('monitorOnPageIssues'),
			"domainHost" => $this->input->post('domain_host'),
			"webmaster" => $this->input->post('webmaster'),
			"search_analytics" => $this->input->post('search_analytics'),
		);

		$this->session->set_userdata($selectedDomain);

		echo json_encode("success");
		die();
	}

	public function viewSerpReport() {
		$data = array();
		$domain_data = $this->session->get_userdata();
		if ($this->ci_auth->canDo('login_to_frontend') && !empty($domain_data)) {
			if (!empty($domain_data)) {
				$domain_id = $domain_data['domainId'];
				$search_engine_id = $this->input->post('searchengineId', '');
				$data['result'] = $this->analyze_model->getSerpResult($domain_id, $search_engine_id);
				//getting the max date for current domain
				$this->db->flush_cache();
				//getting different search engines
				$this->db->select("max(updated_date) as updated_date");
				$this->db->from('serp');
				$this->db->where("domain_id=", $domain_id);
				$query = $this->db->get();
				$date_result = $query->row_array();

				if ($date_result['updated_date']) {
					$data['show_date'] = 'as of ' . date('d M Y', strtotime($date_result['updated_date']));
				} else {
					$data['show_date'] = '';
				}

				$data['domain_id'] = $domain_id;
				$userid = $this->ci_auth->get_user_id();
				$data['keywordstats'] = $this->analyze_model->getKeywordPositionComparitiveStats($userid, $domain_id, $search_engine_id);
				$data['domain_data'] = $domain_data;

				$client = $this->client;
				$user_id = $this->ci_auth->get_user_id();

				$data['webmaster_keywords'] = $webKeywords;

				echo $this->load->view(get_template_directory() . '/view_serp_report', $data, true);

			} else {
				// redirect(site_url('auth/add_project'));
			}
		} else {
			// redirect(site_url('auth/login'));
		}
	}

	public function viewSerp() {
		$data = array();
		$domain_data = $this->session->get_userdata();
		if ($this->ci_auth->canDo('login_to_frontend') && !empty($domain_data)) {
			if (!empty($domain_data)) {
				$domain_id = $domain_data['domainId'];
				//getting search engines of domain
				$searchengines = $this->analyze_model->getSearchEngineByDomainId($domain_id);
				$data['searchengines'] = $searchengines;

				$data['domain_id'] = $domain_id;
				$userid = $this->ci_auth->get_user_id();
				//getting all keywords of a domain
				$editKeywords = $this->analyze_model->getkeywordByDomainId($domain_id);
				$data['editKeywords'] = $editKeywords;
				$data['domain_data'] = $domain_data;
				$saved_keywords = array();
				foreach ($editKeywords as $editKeyword) {
					$saved_keywords[] = $editKeyword['name'];
				}

				$client = $this->client;
				$user_id = $this->ci_auth->get_user_id();
				if (!$client->getAccessToken()) {
					$this->Google_Authenticate->getUserAuthenticated($user_id);
				}
				$webKeywords = array();
				try {
					$weburl = $this->session->userdata('domainUrl');
					$this->db->flush_cache();
					$this->db->select('*');
					$this->db->from('domains');
					$this->db->like('domain_name', $weburl, '%');
					$this->db->limit(1);
					$query = $this->db->get();
					$domainRes = $query->row_array();
					$weburl = $domainRes['domain_name'];

					$service = new Google_Service_Webmasters($client);
					$endDate = date('Y-m-d');
					$startDate = date('Y-m-d', strtotime('1 month ago'));
					$q = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
					$q->setStartDate($startDate);
					$q->setEndDate($endDate);
					$q->setDimensions(['query']);
					$q->setSearchType('web');
					$query = $service->searchanalytics->query($weburl, $q);

					$keywords = array();

					///change
					if (!empty($query->rows)) {
						foreach ($query->rows as $val) {
							if (!empty($saved_keywords)) {
								if (!(in_array($val->keys[0], $saved_keywords))) {
									$keyword['keyword'] = $val->keys[0];
									$keyword['clicks'] = $val->clicks;
									$keyword['impressions'] = $val->impressions;
									$keywords[] = $keyword;
								}
							} else {
								$keyword['keyword'] = $val->keys[0];
								$keyword['clicks'] = $val->clicks;
								$keyword['impressions'] = $val->impressions;
								$keywords[] = $keyword;
							}
						}
					}

					if (!empty($saved_keywords)) {
						if (!empty($keywords)) {
							foreach ($keywords as $keyword) {
								if (!(in_array($keyword, $saved_keywords))) {
									$webKeywords[] = $keyword;
								}
							}
						}
					} else {
						$webKeywords = $keywords;
					}
				} catch (Google_Service_Exception $e) {

				}
				$data['webmaster_keywords'] = $webKeywords;
				// $this->load->view(get_template_directory() . '/new-ui/view_serp_new', $data);
				$this->load->view(get_template_directory() . '/new-ui/view_serp_new_TEST_OLD', $data);
			} else {
				redirect(site_url('auth/add_project'));
			}
		} else {
			redirect(site_url('auth/login'));
		}
    }
    
    public function viewSerpNEW(){

        if ( ! $this->ci_auth->is_logged_in() ) {
            redirect( base_url( 'auth/login' ) );
            exit;
        }
        elseif( $this->ci_auth->is_logged_in( false ) ){
            // logged in, not activated
            redirect( base_url( 'auth/sendactivation' ) );
            exit;
        }
        
        $domain_id = (int) $this->uri->segment(3);
        
        if( 0 >= $domain_id ){
            redirect( base_url( 'domains' ) );
            exit;
        }

        $user_session_data = $this->session->get_userdata();
        $user_id = (int) $user_session_data['user_id'];
        
        if( ! $this->domain_model->getDomainByDomainIdAndUserId( $domain_id, $user_id ) ){
            redirect( base_url( 'domains' ) );
        }

        /* ========================= // BEGIN - COPIED FROM NEW PAGES IMPLEMENTATION ========================= */
        $this->load->helper('campaigns-io/functions');
        $this->load->helper('campaigns-io/classes');
        
        /* --------------------------------------------------------------------------------------------------- */
        
        $page_data = array(
            'view_filename'=> null,
            'document_data' => array(
                'title' => 'Campaigns.io',
                'styles' => array(),
                'top_scripts' => array(),
                'bottom_scripts' => array(),
            ),
            'url' => array(
                'home' => null,
                'logo' => null,
            ),
            'current_page' => false,
            'collapsed_sidebar' => false,
            'collapsed_author_nav' => false,
        );
        
        /* --------------------------------------------------------------------------------------------------- */

        $user_profile_data = user_profile_data( $this->db, $user_id );
        $page_data['user'] = array(
            'id' => $user_id,
            'email' => $user_session_data['email'],
            'gravatar'=> gravatar_thumb( $user_session_data['email'], 112),
            'domains' => array()
        );
        
        $page_data['user']['fullname'] = $user_profile_data->first_name ? $user_profile_data->first_name : '';
        $page_data['user']['fullname'] .= $user_profile_data->last_name ? ' ' . $user_profile_data->last_name : '';
        $page_data['user']['fullname'] = '' !== $page_data['user']['fullname'] ? $page_data['user']['fullname'] : '--';

        /* --------------------------------------------------------------------------------------------------- */
        
        $theme_url = base_url() . 'frontend/site/default/new-ui/';
        $page_data['url']['home'] = base_url() . 'auth/home';
        $page_data['url']['logo'] = $theme_url . 'img/campaigns-io-logo.png';
        
        if( isset( $_COOKIE['campaigns-io']['collapse-sidebar'] ) ){
            $page_data['collapsed_sidebar'] = 1 === (int) $_COOKIE['campaigns-io']['collapse-sidebar'];
        }
        
        if( isset( $_COOKIE['campaigns-io']['collapse-author-nav'] ) ){			
			// NOTE: Replaced to keep users navigation menu collapsed on pages load.
			// $page_data['collapsed_author_nav'] = 1 === (int) $_COOKIE['campaigns-io']['collapse-author-nav'];
					
			$page_data['collapsed_author_nav'] = 1;
        }

        /* ========================== END - COPIED FROM NEW PAGES IMPLEMENTATION // ========================== */
        
        /* ========================= // BEGIN - FROM OLDER SERP PAGE IMPLEMENTATION ========================= */
        $client = $this->client;
        
        if ( ! $client->getAccessToken() ) {
            $this->Google_Authenticate->getUserAuthenticated( $user_id, 1 );
        }
        
        // Get search engines of domain.
        $data['searchengines'] = $this->analyze_model->getSearchEngineByDomainId( $domain_id );
        
        // Get all keywords of a domain.
        $data['editKeywords'] = $this->analyze_model->getkeywordByDomainId( $domain_id );
        $saved_keywords = array();
        
        if( empty( $data['editKeywords'] ) ){
            foreach ($data['editKeywords'] as $editKeyword) {
                $saved_keywords[] = $editKeyword['name'];
            }
        }

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $domain_data = $domain_data[0];
        $domain_url = $domain_data->domain_name;
        
        $webKeywords = array();
        try {
            $q = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $q->setStartDate( date('Y-m-d', strtotime('1 month ago') ) );
            $q->setEndDate( date('Y-m-d') );
            $q->setDimensions( ['query']);
            $q->setSearchType('web');
            
            $service = new Google_Service_Webmasters( $client );
            $query = $service->searchanalytics->query( $domain_url, $q );
            $keywords = array();

            if ( ! empty( $query->rows ) ) {
                foreach ( $query->rows as $val ) {
                    $add_keyword = empty( $saved_keywords ) || ! in_array( $val->keys[0], $saved_keywords );
                    if ( $add_keyword ) {
                        $keywords[] = array(
                            'keyword' => $val->keys[0],
                            'clicks' => $val->clicks,
                            'impressions' => $val->impressions,
                        );
                    }
                }
            }
            
            if( ! empty( $saved_keywords ) && ! empty( $keywords ) ) {
                foreach ($keywords as $key) {
                    if ( ! in_array( $key, $saved_keywords ) ) {
                        $webKeywords[] = $key;
                    }
                }
            }
            else {
                $webKeywords = $keywords;
            }

        } catch (Google_Service_Exception $e) {}
        
        $data['webmaster_keywords'] = $webKeywords;
        
        /* ========================== BEGIN - FROM OLDER SERP PAGE IMPLEMENTATION // ========================== */
        
        $db_query = "SELECT * FROM user_domain ud JOIN domains d ON d.id=ud.domain_id WHERE ud.user_id='" . $user_id . "' ORDER BY d.id DESC;";
        $results = $this->db->query( $db_query );
        
        $page_data['user']['domains'] = $results->result();
        $page_data['document_data']['title'] = 'SERPS - ' . $page_data['document_data']['title'];
        $page_data['current_page'] = 'view-serps';
        $page_data['domain']['id'] = $domain_id;

        $this->load->view( get_template_directory() . '/new-ui/parts/html-top' , $page_data );
        $this->load->view( "campaigns-io/templates/page-top" , $page_data );
        $this->load->view( get_template_directory() . '/new-ui/view_serp_new', $page_data );
        $this->load->view( "campaigns-io/templates/page-bottom" , $page_data );
        $this->load->view( get_template_directory() . '/new-ui/parts/html-bottom' , $page_data );
    }

	public function keyword_research() {
		$data = array();
		$domain_data = $this->session->get_userdata();
		if ($this->ci_auth->canDo('login_to_frontend') && !empty($domain_data)) {
			if (!empty($domain_data)) {
				$domain_id = $domain_data['domainId'];
				//getting search engines of domain
				$searchengines = $this->analyze_model->getSearchEngineByDomainId($domain_id);
				$data['searchengines'] = $searchengines;
				$data['domain_id'] = $domain_id;
				$data['domain_data'] = $domain_data;
				$this->load->view(get_template_directory() . '/keyword_research', $data);
			} else {
				redirect(site_url('auth/add_project'));
			}
		} else {
			redirect(site_url('auth/login'));
		}
	}

	public function addWebMasterKeywords() {
		if ($this->ci_auth->canDo('login_to_frontend')) {
			$domain_data = $this->session->get_userdata();
			$user_id = $this->ci_auth->get_user_id();
			//check if has parent user
			$this->db->flush_cache();
			$this->db->select("parent_id");
			$this->db->from('users as u');
			$this->db->where("id=", $user_id);
			$this->db->limit(1);
			$query = $this->db->get();
			$parentDetail = $query->row_array();
			if ($parentDetail['parent_id'] != null) {
				$user_id = $parentDetail['parent_id'];
			}
			if (!empty($domain_data)) {
				$keywords = array();
				$keyword_data['domain_id'] = $domain_data['domainId'];
				$keyword_data['create_at'] = date('Y-m-d H:i:s');
				$keyword_data['user_id'] = $user_id;
				$keywords = $this->input->post('add_keyword');

				//getting total search engines
				$searchengines = $this->analyze_model->getSearchEngineByDomainId($domain_data['domainId']);
				$totalSearchengines = count($searchengines);
				if (!empty($keywords)) {
					foreach ($keywords as $keyword) {
						// foreach ($searchengines as $engines) {
						$keyword_data['name'] = trim(strtolower($keyword));
						$keyword_data['type'] = 'GWT';
						$this->analyze_model->insertkeyword($keyword_data);
						// }
					}
				}
				$this->session->set_flashdata('type', 'success');
				$this->session->set_flashdata('msg', 'Keywords added to serp queue successfully');
				redirect(site_url('auth/viewSerp'));
			} else {
				redirect(site_url('auth/add_project'));
			}
		} else {
			redirect(site_url('auth/login'));
		}
	}

	public function addDomain() {

		$array = array();
		$keyword_data = array();
		$engine_data = array();
		$array['userid'] = $this->ci_auth->get_user_id();
		if (!preg_match('/http/i', $this->input->post('domain'))) {
			$array['domain_name'] = 'http://' . $this->input->post('domain');
		} else {
			$array['domain_name'] = $this->input->post('domain');
		}

		$this->db->flush_cache();
		$this->db->select('*');
		$this->db->from('domains');
		$this->db->where('domain_name', $array['domain_name']);
		$this->db->limit(1);
		$query = $this->db->get();
		$domainExist = $query->row_array();
		if ($domainExist) {
			// echo json_encode(array('msg' => 'Sorry, domain you are trying to add already exist', 'type' => 'error'));
			echo "Domain already exists.<br/>";
			echo '<a href="' . base_url('domains/add') . '" title="">Back</a>';
			die();
		}
		$array['monitorOnPageIssues'] = $this->input->post('monitor');
		// Code Added by Mannan
		//$array['keyword'] = $this->input->post('keywords');
		//$array['searchEngine'] = $this->input->post('engines');
		$keywords = explode(PHP_EOL, $this->input->post('keywords'));
		//adding engines
		$engines = array('g_uk');
		foreach ($engines as $eng) {
			if ($this->input->post('mobile_search') == 1) {
				array_push($engines, $eng . '-mobile');
			}
		}

		$array['mobile_search'] = $this->input->post('mobile_search');
		$array['local_search'] = $this->input->post('local_search');
		$array['is_ecommerce'] = $this->input->post('is_ecommerce');
		// End
		$connected = $this->analyze_model->getUserOAuthDetails($array['userid']);
		if ($connected != null) {
			$array['connectToGoogle'] = 1;
		} else {
			$array['connectToGoogle'] = $this->input->post('connectToGoogle');
		}
		$array['ga_account'] = $this->input->post('ga_account');
		$array['monitorMalware'] = $this->input->post('monitorMalware');
		$array['adminURL'] = $this->input->post('adminURL');
		$array['adminUsername'] = $this->input->post('adminUsername');
		$array['monitorUptime'] = $this->input->post('monitorUptime');
		$array['webmaster'] = $this->input->post('webmaster');
		$array['search_analytics'] = $this->input->post('search_analytics');
		$domain_id = $this->analyze_model->update_domains($array);
		$array['domain_id'] = $domain_id;
		$keyword_data['domain_id'] = $domain_id;
		$keyword_data['user_id'] = $array['userid'];
		$engine_data['domain_id'] = $domain_id;
		$engine_data['user_id'] = $array['userid'];

		//adding the local search towns
		if ($array['local_search'] == 1) {
			$towns = $this->input->post('towns');
			if ($towns) {
				foreach ($towns as $town) {
					$this->analyze_model->insertLocalSearchTown($town, $domainId, $array['userid']);
				}
			}

		}

		// Code Added by Mannan
		if ($domain_id != null) {
			foreach ($engines as $key => $engine) {
				$engine_data['name'] = $engine;
				$array['locale'] = $engine;
				$array['search_engine_id'] = $this->analyze_model->insertEngine($engine_data);
				foreach ($keywords as $keys => $keyword) {
					if ($keyword != '') {
						$keyword_data['name'] = $keyword;
						$array['keyword'] = trim(strtolower($keyword));
						$array['keyword_id'] = $this->analyze_model->insertkeyword($keyword_data);
						if (preg_match('/mobile/i', $engine)) {
							$array['mobile_search'] = 1;
						} else {
							$array['mobile_search'] = 0;
						}
					}

				}
			}
		}
		// End
		if ($domain_id != null) {
			$domains = $this->analyze_model->getDomain($domain_id);
			foreach ($domains as $key => $domain) {
				$domains[$key]->host = parse_url($domain->domain_name, PHP_URL_HOST);
			}
			if ($this->input->post('monitorUptime') == 1) {
				$this->adduptime($_POST, $domain_id);
			}

			//adding subusers to domain
			$subusers = $_POST['subusers'];
			if ($subusers) {
				$this->analyze_model->assignDomainToSubuser($domain_id, $subusers, $this->ci_auth->get_user_id());
			}
			//adding domain to groups
			$groups = $_POST['groups'];
			if ($groups) {
				$this->groups_model->assignDomainToGroup($domain_id, $groups);
			}

			$selectedDomain = array("domainId" => $domains[0]->id,
				"monitorMalware" => $domains[0]->monitorMalware,
				"adminURL" => $domains[0]->adminURL,
				"adminUsername" => $domains[0]->adminUsername,
				"domainUrl" => $domains[0]->domain_name,
				"gaAccount" => $domains[0]->ga_account,
				"connectToGoogle" => $domains[0]->connectToGoogle,
				"monitorOnPageIssues" => $domains[0]->monitorOnPageIssues,
				"domainHost" => $domains[0]->host,
				"webmaster" => $domains[0]->webmaster,
				"search_analytics" => $domains[0]->search_analytics);
			$this->session->set_userdata($selectedDomain);

			/* ************ GT Metrix Request ************ */

			$date = date('Y-m-d H:i:s');
			// if (is_writable("gtmetrix_logs.txt")) {
			//     $myfile      = fopen("gtmetrix_logs.txt", "w");
			//     $test        = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
			//     $url_to_test = $domains[0]->domain_name;
			//     $testid = $test->test(array(
			//         'url' => $url_to_test,
			//     ));
			//     $test->get_results();
			//     $gtmetrix      = $test->results();
			//     $data['image'] = $gtmetrix['report_url'] . "/screenshot.jpg";
			//     $data['datas'] = json_encode($gtmetrix);
			//     $results = $this->analyze_model->update_gtmet_domain($data, $url_to_test);
			//     $count                  = array();
			//     $count['user_id']       = $this->ci_auth->get_user_id();
			//     $count['domain_id']     = $domain_id;
			//     $count['api_requested'] = date('Y-m-d H:i:s');
			//     $user                   = 0;
			//     $result_count           = $this->analyze_model->update_gtmet_count($count, $user);
			// }

			//adding to piwik
			// $this->addPiwik($domain_id, $this->ci_auth->get_user_id(), $array['is_ecommerce']);

		}

		// End
		echo json_encode(array('msg' => 'success', 'domainURL' => $selectedDomain['domainUrl'], 'domainHost' => $selectedDomain['domainHost'], 'webmaster' => $selectedDomain['webmaster'], 'search_analytics' => $selectedDomain['search_analytics'], 'domainId' => $selectedDomain['domainId'], 'type' => 'success'));
		die();
	}

	private function updatePiwik($domainId, $userId, $isEcommerce = false) {
		$domainDetails = $this->analyze_model->getDomain($domainId);
		$userDetails = $this->analyze_model->getusersinfo($userId);
		$apiUrl = $this->ci->config->config['piwik']['api_url'];
		$token_auth = $this->ci->config->config['piwik']['auth_token'];

		if ($domainDetails[0]->piwik_site_id != null && $domainDetails[0]->piwik_site_id > 0) {
			$url = $apiUrl;
			$url .= "?module=API&method=SitesManager.updateSite";
			if ($isEcommerce == true) {
				$url .= "&idSite=" . $domainDetails[0]->piwik_site_id . "&ecommerce=1&siteSearch=1";
			} else {
				$url .= "&idSite=" . $domainDetails[0]->piwik_site_id . "&ecommerce=0&siteSearch=1";
			}

			$url .= "&format=PHP";
			$url .= "&token_auth=$token_auth";
			$fetched = file_get_contents($url);
			$siteId = unserialize($fetched);
		} else {
			$userDetails = $this->analyze_model->getusersinfo($userId);
			$apiUrl = $this->ci->config->config['piwik']['api_url'];

			$token_auth = $this->ci->config->config['piwik']['auth_token'];
			$url = $apiUrl;
			$url .= "?module=API&method=UsersManager.userExists";
			$url .= "&userLogin=" . $userDetails[0]->username;
			$url .= "&format=PHP";
			$url .= "&token_auth=$token_auth";
			$fetched = file_get_contents($url);
			$userExists = unserialize($fetched);
			$siteName = strtr($domainDetails[0]->domain_name, array('www.' => '', 'http://' => '', 'https://' => '', '/' => ''));
			if ($userExists == 1) {

				//getting the site detail
				$url = $apiUrl;
				$url .= "?module=API&method=SitesManager.getSitesIdFromSiteUrl";
				$url .= "&url=" . urlencode($domainDetails[0]->domain_name);
				$url .= "&format=PHP";
				$url .= "&token_auth=$token_auth";
				$fetched = file_get_contents($url);
				$siteDetail = unserialize($fetched);

				if (!$siteDetail) {
					//add site
					$url = $apiUrl;
					$url .= "?module=API&method=SitesManager.addSite";
					if ($isEcommerce == true) {
						$url .= "&siteName=" . $siteName . "&urls=" . urlencode($domainDetails[0]->domain_name) . "&ecommerce=1&siteSearch=1";
					} else {
						$url .= "&siteName=" . $siteName . "&urls=" . urlencode($domainDetails[0]->domain_name) . "&ecommerce=0&siteSearch=1";
					}

					$url .= "&format=PHP";
					$url .= "&token_auth=$token_auth";
					$fetched = file_get_contents($url);
					$siteId = unserialize($fetched);
				} else {
					$siteId = $siteDetail[0]['idsite'];
				}
				//updating the piwik site id to domain table
				$this->db->flush_cache();
				$data = array();
				$data['piwik_site_id'] = $siteId;
				$this->db->where('id', $domainDetails[0]->id);
				$this->db->update('domains', $data);

				//give access to user
				$url = "https://stats.campaigns.io";
				$url .= "?module=API&method=UsersManager.setUserAccess";
				$url .= "&userLogin=" . $userDetails[0]->username . "&access=admin&idSites=" . $siteId;
				$url .= "&format=PHP";
				$url .= "&token_auth=$token_auth";

				$fetched = file_get_contents($url);
				$addSite = unserialize($fetched);
			} else {
				//add user
				$url = $apiUrl;
				$url .= "?module=API&method=UsersManager.addUser";
				$url .= "&userLogin=" . $userDetails[0]->username . "&password=" . urlencode($userDetails[0]->password) . "&email=" . $userDetails[0]->email;
				$url .= "&format=PHP";
				$url .= "&token_auth=$token_auth";
				$fetched = file_get_contents($url);
				$userAdded = unserialize($fetched);
				if ($userAdded['result'] == 'success') {

					//getting the site detail
					$url = $apiUrl;
					$url .= "?module=API&method=SitesManager.getSitesIdFromSiteUrl";
					$url .= "&url=" . urlencode($domainDetails[0]->domain_name);
					$url .= "&format=PHP";
					$url .= "&token_auth=$token_auth";
					$fetched = file_get_contents($url);
					$siteDetail = unserialize($fetched);

					if (!$siteDetail) {
						//add site
						$url = "https://stats.campaigns.io";
						$url .= "?module=API&method=SitesManager.addSite";
						if ($isEcommerce == true) {
							$url .= "siteName=" . $siteName . "&urls=" . urlencode($domainDetails[0]->domain_name) . "&ecommerce=1&siteSearch=1";
						} else {
							$url .= "siteName=" . $siteName . "&urls=" . urlencode($domainDetails[0]->domain_name) . "&ecommerce=0&siteSearch=1";
						}

						$url .= "&format=PHP";
						$url .= "&token_auth=$token_auth";
						$fetched = file_get_contents($url);
						$siteId = unserialize($fetched);

					} else {
						$siteId = $siteDetail[0]['idsite'];
					}
					//updating the piwik site id to domain table
					$this->db->flush_cache();
					$data = array();
					$data['piwik_site_id'] = $siteId;
					$this->db->where('id', $domainDetails[0]->id);
					$this->db->update('domains', $data);

					$url = $apiUrl;
					$url .= "?module=API&method=UsersManager.setUserAccess";
					$url .= "&userLogin=" . $userDetails[0]->username . "&access=admin&idSites=" . $siteId;
					$url .= "&format=PHP";
					$url .= "&token_auth=$token_auth";
					$fetched = file_get_contents($url);
					unserialize($fetched);
				}

			}
		}
		return true;
	}

	private function addPiwik($domainId, $userId, $isEcommerce = false) {
		$domainDetails = $this->analyze_model->getDomain($domainId);
		$userDetails = $this->analyze_model->getusersinfo($userId);
		$apiUrl = $this->ci->config->config['piwik']['api_url'];

		$token_auth = $this->ci->config->config['piwik']['auth_token'];
		$url = $apiUrl;
		$url .= "?module=API&method=UsersManager.userExists";
		$url .= "&userLogin=" . $userDetails[0]->username;
		$url .= "&format=PHP";
		$url .= "&token_auth=$token_auth";
		$fetched = file_get_contents($url);
		$userExists = unserialize($fetched);
		$siteName = strtr($domainDetails[0]->domain_name, array('www.' => '', 'http://' => '', 'https://' => '', '/' => ''));
		if ($userExists == 1) {

			//getting the site detail
			$url = $apiUrl;
			$url .= "?module=API&method=SitesManager.getSitesIdFromSiteUrl";
			$url .= "&url=" . urlencode($domainDetails[0]->domain_name);
			$url .= "&format=PHP";
			$url .= "&token_auth=$token_auth";
			$fetched = file_get_contents($url);
			$siteDetail = unserialize($fetched);

			if (!$siteDetail) {
				//add site
				$url = $apiUrl;
				$url .= "?module=API&method=SitesManager.addSite";
				if ($isEcommerce == true) {
					$url .= "&siteName=" . $siteName . "&urls=" . urlencode($domainDetails[0]->domain_name) . "&ecommerce=1&siteSearch=1";
				} else {
					$url .= "&siteName=" . $siteName . "&urls=" . urlencode($domainDetails[0]->domain_name) . "&ecommerce=0&siteSearch=1";
				}

				$url .= "&format=PHP";
				$url .= "&token_auth=$token_auth";

				$fetched = file_get_contents($url);
				$siteId = unserialize($fetched);
			} else {
				$siteId = $siteDetail[0]['idsite'];
			}
			//updating the piwik site id to domain table
			$this->db->flush_cache();
			$data = array();
			$data['piwik_site_id'] = $siteId;
			$this->db->where('id', $domainDetails[0]->id);
			$this->db->update('domains', $data);

			//give access to user
			$url = "https://stats.campaigns.io";
			$url .= "?module=API&method=UsersManager.setUserAccess";
			$url .= "&userLogin=" . $userDetails[0]->username . "&access=admin&idSites=" . $siteId;
			$url .= "&format=PHP";
			$url .= "&token_auth=$token_auth";

			$fetched = file_get_contents($url);
			$addSite = unserialize($fetched);
		} else {
			//add user
			$url = $apiUrl;
			$url .= "?module=API&method=UsersManager.addUser";
			$url .= "&userLogin=" . $userDetails[0]->username . "&password=" . urlencode($userDetails[0]->password) . "&email=" . $userDetails[0]->email;
			$url .= "&format=PHP";
			$url .= "&token_auth=$token_auth";
			$fetched = file_get_contents($url);
			$userAdded = unserialize($fetched);
			if ($userAdded['result'] == 'success') {

				//getting the site detail
				$url = $apiUrl;
				$url .= "?module=API&method=SitesManager.getSitesIdFromSiteUrl";
				$url .= "&url=" . urlencode($domainDetails[0]->domain_name);
				$url .= "&format=PHP";
				$url .= "&token_auth=$token_auth";
				$fetched = file_get_contents($url);
				$siteDetail = unserialize($fetched);

				if (!$siteDetail) {
					//add site
					$url = "https://stats.campaigns.io";
					$url .= "?module=API&method=SitesManager.addSite";
					if ($isEcommerce == true) {
						$url .= "&siteName=" . $siteName . "&urls=" . urlencode($domainDetails[0]->domain_name) . "&ecommerce=1&siteSearch=1";
					} else {
						$url .= "&siteName=" . $siteName . "&urls=" . urlencode($domainDetails[0]->domain_name) . "&ecommerce=0&siteSearch=1";
					}

					$url .= "&format=PHP";
					$url .= "&token_auth=$token_auth";
					$fetched = file_get_contents($url);
					$siteId = unserialize($fetched);

				} else {
					$siteId = $siteDetail[0]['idsite'];
				}
				//updating the piwik site id to domain table
				$this->db->flush_cache();
				$data = array();
				$data['piwik_site_id'] = $siteId;
				$this->db->where('id', $domainDetails[0]->id);
				$this->db->update('domains', $data);

				$url = $apiUrl;
				$url .= "?module=API&method=UsersManager.setUserAccess";
				$url .= "&userLogin=" . $userDetails[0]->username . "&access=admin&idSites=" . $siteId;
				$url .= "&format=PHP";
				$url .= "&token_auth=$token_auth";
				$fetched = file_get_contents($url);
				unserialize($fetched);
			}

		}
		return true;
	}

	public function deletekeyword() {
		$keywords = array();
		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$keywords[] = $this->input->get('keyword');
		} else {
			$keywords = $this->input->post('del_keyword');
		}

		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			/* logged in */
			if ($this->ci_auth->canDo('login_to_frontend')) {

				$userId = $this->ci_auth->get_user_id();
				$domain_data = $this->session->get_userdata();
				$domainId = $domain_data['domainId'];
				foreach ($keywords as $keyword) {
					$this->analyze_model->deleteKeyword($domainId, $keyword, $userId);
				}
				$this->session->set_flashdata('type', 'success');
				$this->session->set_flashdata('msg', 'Keywords deleted successfully');
				redirect(site_url('/auth/viewSerp'));
			}
		}
	}

	public function addkeyword() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			/* logged in */
			if ($this->ci_auth->canDo('login_to_frontend')) {
				$domain_data = $this->session->get_userdata();
				$domainId = $domain_data['domainId'];
				if ($this->input->server('REQUEST_METHOD') == 'POST') {

					$keywords = explode(PHP_EOL, ($this->input->post('keywords')));

					// $keywords = explode(PHP_EOL, $this->input->post('keywords'));

					//adding keywords to domain
					$array = array();
					$searchengines = $this->analyze_model->getSearchEngineByDomainId($domainId);
					$user_id = $this->ci_auth->get_user_id();
					//check if has parent user
					$this->db->flush_cache();
					$this->db->select("parent_id");
					$this->db->from('users as u');
					$this->db->where("id=", $user_id);
					$this->db->limit(1);
					$query = $this->db->get();
					$parentDetail = $query->row_array();
					if ($parentDetail['parent_id'] != null) {
						$user_id = $parentDetail['parent_id'];
					}
					if ($searchengines) {
						// foreach ($searchengines as $search) {
						foreach ($keywords as $keyword) {
							if ($keyword != '') {
								$array['user_id'] = $user_id;
								$array['domain_id'] = $domainId;
								$array['name'] = trim(strtolower($keyword));
								$array['create_at'] = date('Y-m-d H:i:s');
								$keywordid = $this->analyze_model->insertkeyword($array);
							}
						}
						// }
					}

					if ($keywordid) {

						$this->session->set_flashdata('type', 'success');
						$this->session->set_flashdata('msg', 'Keywords are added to domain');

					} else {
						$this->session->set_flashdata('type', 'error');
						$this->session->set_flashdata('msg', 'Sorry keywords could not be added');
					}
					redirect(site_url('/auth/viewSerp'));
				}
			}
		}
	}

	public function dashboard($setDomainId = '') {
		$setDomainId = $this->uri->segment(3);

		// TODO: Remove endpoint.
		redirect( base_url( 'domains/' . $setDomainId ) );
        exit;

		$domain_data = $this->session->get_userdata();

		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			/* logged in */
			if ($this->ci_auth->canDo('login_to_frontend')) {

				$data = array();
				$domain_id = '';
				$userid = $this->ci_auth->get_user_id();
				$domains = $this->analyze_model->getDomainDetails($userid);

				if (empty($domains)) {
					redirect(site_url('/auth/add_project'));
				}
				$setdomains = array();
				foreach ($domains as $key => $domain) {
					$domains[$key]->host = parse_url($domain->domain_name, PHP_URL_HOST);
					if ($setDomainId) {
						if ($domain->id == $setDomainId) {
							$setdomains = $domain;
						}
					}
				}
				if ($setDomainId && $setdomains) {
					$domain_id = $setdomains->id;
					$selectedDomain = array(
                        "domainId" => $setdomains->id,
						"monitorMalware" => $setdomains->monitorMalware,
						"adminURL" => $setdomains->adminURL,
						"adminUsername" => $setdomains->adminUsername,
						"domainUrl" => $setdomains->domain_name,
						"gaAccount" => $setdomains->ga_account,
						"connectToGoogle" => $setdomains->connectToGoogle,
						"monitorOnPageIssues" => $setdomains->monitorOnPageIssues,
						"domainHost" => $setdomains->host,
						"webmaster" => $setdomains->webmaster,
                        "search_analytics" => $setdomains->search_analytics
                    );

                    $this->session->set_userdata($selectedDomain);
                    
                    if( ! function_exists('enqueue_html_stylesheets') ){
                        require_once FCPATH . 'app/helpers/campaigns-io/functions_helper.php';
                    }
                    
                    if( ! function_exists('WP_Request_Access') ){
                        require_once FCPATH . 'app/helpers/campaigns-io/classes_helper.php';
                    }

                    $site = array(
                    'id' => $domain_id,
                    'data' => get_site_by_id( $domain_id, $this->ci_auth->ci ),
                    );
                    
                    $wp_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
                    $updates_info = $wp_access->updates( $site, 'all', true, true);
                    $updates_info['core'] = isset( $updates_info['core'] ) && ! is_nan( $updates_info['core'] ) ? $updates_info['core'] : 'n/a';
                    $updates_info['plugins'] = isset( $updates_info['plugins'] ) && ! is_nan( $updates_info['plugins'] ) ? $updates_info['plugins'] : 'n/a';
                    $updates_info['themes'] = isset( $updates_info['themes'] ) && ! is_nan( $updates_info['themes'] ) ? $updates_info['themes'] : 'n/a';
                    $data['wp_updates_info'] = $updates_info;

				} else if (!$this->session->userdata('domainHost')) {

					$domain_id = $domains[0]->id;
					$selectedDomain = array("domainId" => $domains[0]->id,
						"monitorMalware" => $domains[0]->monitorMalware,
						"adminURL" => $domains[0]->adminURL,
						"adminUsername" => $domains[0]->adminUsername,
						"domainUrl" => $domains[0]->domain_name,
						"gaAccount" => $domains[0]->ga_account,
						"connectToGoogle" => $domains[0]->connectToGoogle,
						"monitorOnPageIssues" => $domains[0]->monitorOnPageIssues,
						"domainHost" => $domains[0]->host,
						"webmaster" => $domains[0]->webmaster,
						"search_analytics" => $domains[0]->search_analytics);
					$this->session->set_userdata($selectedDomain);
				} else {
					$domain_data = $this->session->get_userdata();
					$domain_id = $domain_data['domainId'];
					redirect(site_url('/auth/dashboard/'));
					exit;
				}
				//getting the downtime is domain is down
				$downTime = $this->domain_model->getDownTimeByDomainId($domain_id);

				$data['downTime'] = $downTime;
				$data['domain_id'] = $domain_id;
				$data['avg_position'] = $this->analyze_model->getAveragePosition($userid, $domain_id);
				$data['keyword_changes'] = $this->analyze_model->getKeywordChangeFromWeeks($userid, $domain_id);
				$data['position'] = $this->analyze_model->getKeywordPositionStats($userid, $domain_id);
				$data['total_keywords'] = $this->analyze_model->getTotalKeywords($userid, $domain_id);
				$this->load->view(get_template_directory() . '/dashboard_new', $data);
			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}

	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	public function login() {
		if ($this->ci_auth->is_logged_in()) {
			/* logged in */

			if ($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/auth/profile'));
			} else {
				redirect(site_url('/admin/login'));
			}
		} elseif ($this->ci_auth->is_logged_in(false)) {
			/* logged in, not activated */

			redirect('/auth/sendactivation/');
		} else {

			$data['login_by_username'] = ($this->config->item('login_by_username') and
				$this->config->item('use_username'));
			$data['login_by_email'] = $this->config->item('login_by_email');
			$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts') and ($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}

			$data['use_recaptcha'] = $this->config->item('use_recaptcha');

			if ($this->ci_auth->is_max_login_attempts_exceeded($login)) {
				if ($data['use_recaptcha']) {
					$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Captcha', 'trim|xss_clean|required|callback__check_captcha');
				}
			}

			$data['errors'] = array();
			if ($this->form_validation->run()) {
				/* validation ok */
				if ($this->ci_auth->login(
					$this->form_validation->set_value('login'), $this->form_validation->set_value('password'), $this->form_validation->set_value('remember'), $data['login_by_username'], $data['login_by_email'])) {
					/* success */

					$session = $this->session->get_userdata();
                    $userinfo = $this->analyze_model->getusersinfo($session['user_id']);
                    $session['email'] = $userinfo[0]->email;
                    $staffinfo = $this->Staff_Model->getUserInfoByEmail($session['email']);
                    
					$this->session->set_userdata(array(
						'logged_in_staff_id' => $staffinfo->id,
						'company_id' => $staffinfo->company_id,
						'staffname' => $staffinfo->staffname,
						'email' => $staffinfo->email,
						'root' => $staffinfo->root,
						'language' => $staffinfo->language,
						'admin' => $staffinfo->admin,
						'staffmember' => $staffinfo->staffmember,
						'staffavatar' => $staffinfo->staffavatar,
						'LoginOK' => true,
					));

					//check number of domains of the user
					$totalDomains = $this->analyze_model->getTotalDomainsByUserId($session['user_id']);
					if ($totalDomains['totalDomain'] == 1) {
						$singleDomain = $this->analyze_model->getSingleDomainByUserId($session['user_id']);
						redirect(site_url('/auth/dashboard/' . $singleDomain['id']));
					} else {
						redirect(site_url('/auth/home'));
					}

				} else {
					$errors = $this->ci_auth->get_error_message();
					if (isset($errors['banned'])) {
						/* banned user */
						$this->_show_message($this->lang->line('auth_message_banned') . ' ' . $errors['banned']);
					} elseif (isset($errors['not_activated'])) {
						/* not activated user */
						redirect('/auth/sendactivation/');
					} else {
						/*  fail */
						foreach ($errors as $k => $v) {
							$data['errors'][$k] = $this->lang->line($v);
						}
					}
				}
			} else {
				$data['errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
			}

			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');

			$data['show_captcha'] = false;
			if ($this->ci_auth->is_max_login_attempts_exceeded($login)) {
				$data['show_captcha'] = true;
				if ($data['use_recaptcha']) {

				} else {
					$data['captcha_html'] = $this->_create_captcha();
					$data['captcha'] = array(
						'name' => 'captcha',
						'placeholder' => 'Captcha',
						'class' => 'required form-control',
						'id' => 'captcha',
						'maxlength' => 8,
					);
				}
			}

			$data['seo_title'] = 'Login';

			$data['remember'] = array(
				'name' => 'remember',
				'id' => 'remember',
				'value' => 1,
				'class' => 'styled',
			);

			$data['login'] = array(
				'name' => 'login',
				'id' => 'login',
				'class' => 'required form-control',
				'placeholder' => 'Username',
				'value' => set_value('login'),
				'maxlength' => 80,
				'size' => 30,
			);

			$data['password'] = array(
				'class' => 'required form-control',
				'placeholder' => 'Password',
				'name' => 'password',
				'id' => 'password',
				'size' => 30,
			);

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning pull-right',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-menu2"></i> Sign in',
			);

			$this->load->view(get_template_directory() . '/login', $data);
		}
	}

	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	public function register() {
		if ($this->ci_auth->is_logged_in()) {
			/* logged in */

			if ($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/auth/profile'));
			} else {
				redirect(site_url('/admin/login'));
			}
		} elseif ($this->ci_auth->is_logged_in(false)) {
			/* logged in, not activated */

			redirect('/auth/sendactivation/');
		} elseif (!$this->config->item('allow_registration')) {
			// registration is off
			$this->_show_message($this->lang->line('auth_message_registration_disabled'));
		} else {

			$this->form_validation->set_rules('first_name', 'First name', 'trim|required|xss_clean|min_length[3]|max_length[10]');
			$this->form_validation->set_rules('last_name', 'Last name', 'trim|required|xss_clean|min_length[1]|max_length[10]');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|xss_clean|min_length[3]|max_length[20]');
			$this->form_validation->set_rules('country', 'Country', 'trim|xss_clean|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('company', 'Company', 'trim|xss_clean|min_length[3]|max_length[20]');
			$this->form_validation->set_rules('website', 'Website', 'trim|xss_clean|min_length[3]|max_length[50]');
			$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean|min_length[3]|max_length[100]');

			$use_username = $this->config->item('use_username');
			if ($use_username) {
				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length[' . $this->config->item('username_min_length') . ']|alpha_dash');
			}

			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length[' . $this->config->item('password_min_length') . ']|max_length[' . $this->config->item('password_max_length') . ']|alpha_dash');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
			$captcha_registration = $this->config->item('captcha_registration');
			$use_recaptcha = $this->config->item('use_recaptcha');

			if ($captcha_registration) {

				if ($use_recaptcha) {
					$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Captcha', 'trim|xss_clean|required|callback__check_captcha');
				}
			}

			$data['errors'] = array();
			$email_activation = $this->config->item('email_activation');
			if ($this->form_validation->run()) {
				// validation ok
				$user_data['username'] = $use_username ? $this->form_validation->set_value('username') : '';
				$user_data['email'] = $this->form_validation->set_value('email');
				$user_data['password'] = $this->form_validation->set_value('password');
				$email_activation = $this->config->item('email_activation');
				if ($email_activation) {
					$user_data['activated'] = 0;
				} else {
					$user_data['activated'] = 1;
				}
				$user_data['user_role'] = $this->config->item('new_user_group');

				$user_profile_data['first_name'] = $this->form_validation->set_value('first_name');
				$user_profile_data['last_name'] = $this->form_validation->set_value('last_name');

				if (!is_null($data = $this->ci_auth->create_user($user_data, $user_profile_data))) {

					$params = array(
						'language' => 'english',
						'company_id' => $data['user_id'],
						'staffname' => $this->input->post('first_name') . " " . $this->input->post('last_name'),
						'departmentid' => 0,
						'email' => $this->input->post('email'),
						'password' => md5($this->input->post('password')),
						'admin' => 1,
					);
					$staff_id = $this->Staff_Model->add_staff($params);
					//generating api for users
					//inserting the api key for user
					$this->db->flush_cache();
					$array = array();
					$array['api_key'] = $data['id'] . $this->randomString(6);
					$this->db->where('id', $data['id']);
					$this->db->update('users', $array);

					// success
					$data['site_name'] = $this->config->item('website_name');

					if ($email_activation) {

						// send "activate" email
						$data['activation_period'] = $this->config->item('email_activation_expire') / 3600;

						$this->_send_email('activate', $data['email'], $data);

						//unset($data['password']); // Clear password (just for any case)
						$this->_show_message('Please check your email for verfication');
						redirect(site_url('auth/login'));

					} else {

						if ($this->config->item('email_account_details')) {
							// send "welcome" email
							$this->_send_email('welcome', $data['email'], $data);
						}

						// unset($data['password']); // Clear password (just for any case)
						$this->_show_message('You have registered successfully');
						redirect(site_url('auth/login'));

					}
				} else {

					$errors = $this->ci_auth->get_error_message();
					foreach ($errors as $k => $v) {
						$data['errors'][$k] = $this->lang->line($v);
					}
				}
			} else {
				$data['errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
			}

			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');

			$data['use_recaptcha'] = $this->config->item('use_recaptcha');

			$data['seo_title'] = 'Register';

			$data['first_name'] = array(
				'name' => 'first_name',
				'id' => 'first_name',
				'class' => 'required form-control',
				'placeholder' => 'First name',
				'value' => set_value('first_name'),
				'maxlength' => 80,
				'size' => 30,
			);

			$data['last_name'] = array(
				'name' => 'last_name',
				'id' => 'last_name',
				'class' => 'required form-control',
				'placeholder' => 'Last name',
				'value' => set_value('last_name'),
				'maxlength' => 80,
				'size' => 30,
			);

			$data['username'] = array(
				'name' => 'username',
				'id' => 'username',
				'class' => 'required form-control',
				'placeholder' => 'Username',
				'value' => set_value('username'),

			);

			$data['email'] = array(
				'name' => 'email',
				'id' => 'email',
				'class' => 'required form-control',
				'placeholder' => 'Email address',
				'value' => set_value('email'),
				'maxlength' => 80,
				'size' => 30,
			);

			$data['password'] = array(
				'class' => 'required form-control',
				'placeholder' => 'Password',
				'name' => 'password',
				'id' => 'password',
				'size' => 30,
			);

			$data['confirm_password'] = array(
				'class' => 'required form-control',
				'placeholder' => 'Confirm Password',
				'name' => 'confirm_password',
				'id' => 'confirm_password',
				'size' => 30,
			);

			$data['show_captcha'] = true;
			if ($data['use_recaptcha']) {

			} else {
				$data['captcha_html'] = $this->_create_captcha();
				$data['captcha'] = array(
					'name' => 'captcha',
					'placeholder' => 'Captcha',
					'class' => 'required form-control',
					'id' => 'captcha',
					'maxlength' => 8,
				);
			}

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning pull-right',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-menu2"></i> Register',
			);
			$this->load->view(get_template_directory() . '/register', $data);
		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	public function logout() {
		// set all user data to empty
		$this->session->set_userdata(array('facebook_id' => '',
			'twitter_id' => '',
			'google_id' => '',
			'linkedin_id' => '',
			'github_id' => '',
			'instagram_id' => '',
			'microsoft_id' => '',
			'envato_id',
			'paypal_id',
			'yandex_id',
			'oauth2state' => '',
			'facebook_access_token' => '',
			'google_access_token' => '',
			'linkedin_access_token' => '',
			'github_access_token' => '',
			'instagram_access_token' => '',
			'microsoft_access_token' => '',
			'envato_access_token' => '',
			'paypal_access_token' => '',
			'yandex_access_token'));
		$this->ci_auth->logout();
		$this->_show_message($this->lang->line('auth_message_logged_out'));
	}

	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	public function sendactivation() {
		if ($this->ci_auth->is_logged_in()) {
			/* logged in */

			if ($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/auth/profile'));
			} else {
				redirect(site_url('/admin/login'));
			}
		} else {

			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
			$data['errors'] = array();
			if ($this->form_validation->run()) {
				// validation ok
				if (!is_null($data = $this->ci_auth->resend_activation_email($this->form_validation->set_value('email')))) {
					// success
					$data['site_name'] = $this->config->item('website_name');
					$data['activation_period'] = $this->config->item('email_activation_expire') / 3600;
					$this->_send_email('activate', $data['email'], $data);
					$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));
				} else {
					$errors = $this->ci_auth->get_error_message();
					foreach ($errors as $k => $v) {
						$data['errors'][$k] = $this->lang->line($v);
					}
				}
			} else {
				$data['errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
			}

			$data['seo_title'] = 'Send Activation Link';

			$data['email'] = array(
				'name' => 'email',
				'id' => 'email',
				'class' => 'required form-control',
				'placeholder' => 'Email address',
				'value' => set_value('email'),
				'maxlength' => 80,
				'size' => 30,
			);

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-mail2"></i> Send Activation mail',
			);

			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');

			$this->load->view(get_template_directory() . '/sendactivation', $data);
		}
	}

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	public function activate() {
		if ($this->ci_auth->is_logged_in()) {
			// logged in
			if ($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/profile/'));
			} else {
				redirect(site_url('/admin/login'));
			}
		}
		$user_id = $this->uri->segment(3);
		$new_email_key = $this->uri->segment(4);

		// Activate user
		if ($this->ci_auth->activate_user($user_id, $new_email_key)) {
			// success
			$this->ci_auth->logout();
			$this->_show_message($this->lang->line('auth_message_activation_completed'));
		} else {
			// fail
			$this->_show_message($this->lang->line('auth_message_activation_failed'));
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	public function forgotpassword() {
		if ($this->ci_auth->is_logged_in()) {
			// logged in
			if ($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/profile/'));
			} else {
				redirect(site_url('/admin/login'));
			}
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			$captcha_forgetpassword = $this->config->item('captcha_forgetpassword');
			$use_recaptcha = $this->config->item('use_recaptcha');

			$this->form_validation->set_rules('login', 'Email or login', 'trim|required|xss_clean');

			if ($captcha_forgetpassword) {

				if ($use_recaptcha) {
					$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Captcha', 'trim|xss_clean|required|callback__check_captcha');
				}
			}

			$data['errors'] = array();
			if ($this->form_validation->run()) {
				// validation ok
				if (!is_null($data = $this->ci_auth->forgotpassword($this->form_validation->set_value('login')))) {
					$data['site_name'] = $this->config->item('website_name');

					// Send email with password activation link
					$this->_send_email('forgot_password', $data['email'], $data);
					$this->_show_message($this->lang->line('auth_message_new_password_sent'));
				} else {
					$errors = $this->ci_auth->get_error_message();
					foreach ($errors as $k => $v) {
						$data['errors'][$k] = $this->lang->line($v);
					}
				}
			} else {
				$data['errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
			}

			$data['captcha_forgetpassword'] = $this->config->item('captcha_forgetpassword');
			$data['use_recaptcha'] = $this->config->item('use_recaptcha');

			$data['seo_title'] = 'Forgot password';

			if ($this->config->item('use_username')) {
				$placeholder = 'Email or Username';
			} else {
				$placeholder = 'Email';
			}

			$data['login'] = array(
				'name' => 'login',
				'id' => 'login',
				'class' => 'required form-control',
				'placeholder' => $placeholder,
				'value' => set_value('login'),
				'maxlength' => 80,
				'size' => 30,
			);

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-mail2"></i> Reset Password',
			);
			if ($data['use_recaptcha']) {

			} else {
				$data['captcha_html'] = $this->_create_captcha();
				$data['captcha'] = array(
					'name' => 'captcha',
					'placeholder' => 'Captcha',
					'class' => 'required form-control',
					'id' => 'captcha',
					'maxlength' => 8,
				);
			}
			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');

			$this->load->view(get_template_directory() . '/forgotpassword', $data);
		}
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	public function resetpassword() {
		$user_id = $this->uri->segment(3);
		$new_pass_key = $this->uri->segment(4);
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length[' . $this->config->item('password_min_length') . ']|max_length[' . $this->config->item('password_max_length') . ']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');
		$data['errors'] = array();

		if ($this->form_validation->run()) {
			// validation ok
			if (!is_null($data = $this->ci_auth->reset_password(
				$user_id, $new_pass_key, $this->form_validation->set_value('new_password')))) {
				// success
				$data['site_name'] = $this->config->item('website_name');
				// Send email with new password
				$this->_send_email('reset_password', $data['email'], $data);
				$this->_show_message($this->lang->line('auth_message_new_password_activated'));
			} else {
				// fail
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		} else {

			$data['errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
			// Try to activate user by password key (if not activated yet)
			if ($this->config->item('email_activation')) {
				$this->ci_auth->activate_user($user_id, $new_pass_key, false);
			}

			if (!$this->ci_auth->can_reset_password($user_id, $new_pass_key)) {
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		}
		$data['new_password'] = array(
			'name' => 'new_password',
			'id' => 'new_password',
			'class' => 'required form-control',
			'placeholder' => 'New password',
			'maxlength' => $this->config->item('password_max_length'),
			'size' => 30,
		);

		$data['confirm_new_password'] = array(
			'name' => 'confirm_new_password',
			'id' => 'confirm_new_password',
			'class' => 'required form-control',
			'placeholder' => 'Re-enter password',
			'maxlength' => $this->config->item('password_max_length'),
			'size' => 30,
		);

		$data['submit'] = array(
			'name' => 'button',
			'class' => 'btn btn-warning',
			'id' => 'button',
			'value' => 'true',
			'type' => 'Submit',
			'content' => '<i class="icon-mail2"></i> Change Password',
		);
		$data['message'] = $this->session->flashdata('message');
		$data['success'] = $this->session->flashdata('success');

		$this->load->view(get_template_directory() . '/resetpassword', $data);
	}

	/**
	 * Retrieve_username by email address
	 *
	 * @return void
	 */
	public function retrieveusername() {
		if ($this->ci_auth->is_logged_in()) {
			/* logged in */

			if ($this->ci_auth->canDo('login_to_frontend')) {
				redirect(site_url('/auth/profile'));
			} else {
				redirect(site_url('/admin/login'));
			}
		} else {

			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$captcha_retrieveusername = $this->config->item('captcha_retrieveusername');
			$use_recaptcha = $this->config->item('use_recaptcha');
			if ($captcha_retrieveusername) {

				if ($use_recaptcha) {
					$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Captcha', 'trim|xss_clean|required|callback__check_captcha');
				}
			}

			$data['errors'] = array();
			if ($this->form_validation->run()) {
				// validation ok
				if (!is_null($data = $this->ci_auth->retrieve_username($this->form_validation->set_value('email')))) {
					// success
					$data['site_name'] = $this->config->item('website_name');
					$this->_send_email('retrieveusername', $data['email'], $data);
					$this->_show_message(sprintf($this->lang->line('auth_message_retrieve_username_email_sent'), $data['email']));
				} else {
					$errors = $this->ci_auth->get_error_message();
					foreach ($errors as $k => $v) {
						$data['errors'][$k] = $this->lang->line($v);
					}
				}
			} else {
				$data['errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
			}

			$data['captcha_retrieveusername'] = $this->config->item('captcha_retrieveusername');
			$data['use_recaptcha'] = $this->config->item('use_recaptcha');

			$data['seo_title'] = 'Retrieve username';

			$data['email'] = array(
				'name' => 'email',
				'id' => 'email',
				'class' => 'required form-control',
				'placeholder' => 'Email address',
				'value' => set_value('email'),
				'maxlength' => 80,
				'size' => 30,
			);

			$data['submit'] = array(
				'name' => 'button',
				'class' => 'btn btn-warning',
				'id' => 'button',
				'value' => 'true',
				'type' => 'Submit',
				'content' => '<i class="icon-mail2"></i> Retrieve username',
			);

			if ($data['use_recaptcha']) {

			} else {
				$data['captcha_html'] = $this->_create_captcha();
				$data['captcha'] = array(
					'name' => 'captcha',
					'placeholder' => 'Captcha',
					'class' => 'required form-control',
					'id' => 'captcha',
					'maxlength' => 8,
				);
			}
			$data['message'] = $this->session->flashdata('message');
			$data['success'] = $this->session->flashdata('success');

			$this->load->view(get_template_directory() . '/retrieveusername', $data);
		}
	}

	/**
	 * Show info message
	 *
	 * @param    string
	 * @return    void
	 */
	public function _show_message($message) {
		$this->session->set_flashdata('message', $message);
		redirect(site_url('auth/login'));
	}

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param    string
	 * @param    string
	 * @param    array
	 * @return    void
	 */
	public function _send_email($type, $email, &$data) {
		$this->load->library('email');
		$this->email->set_mailtype("html");
		$this->email->from('info@campaigns.io', 'Campaigns.io');
		$this->email->reply_to('info@campaigns.io', 'Campaigns.io');
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_' . $type), $this->config->item('website_name')));
		$this->email->message($this->load->view('email/' . $type . '-html', $data, true));
		$this->email->set_alt_message($this->load->view('email/' . $type . '-txt', $data, true));
		$this->email->send();
	}

	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return    string
	 */
	public function _create_captcha() {
		$this->load->helper('captcha');
		$cap = create_captcha(array(
			'img_path' => './' . $this->config->item('captcha_path'),
			'img_url' => base_url() . $this->config->item('captcha_path'),
			'font_path' => './' . $this->config->item('captcha_fonts_path'),
			'font_size' => $this->config->item('captcha_font_size'),
			'img_width' => $this->config->item('captcha_width'),
			'img_height' => $this->config->item('captcha_height'),
			'show_grid' => $this->config->item('captcha_grid'),
			'expiration' => $this->config->item('captcha_expire'),
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
			'captcha_word' => $cap['word'],
			'captcha_time' => $cap['time'],
		));
		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param    string
	 * @return    bool
	 */
	public function _check_captcha($code) {
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');
		list($usec, $sec) = explode(" ", microtime());
		$now = ((float) $usec + (float) $sec);

		if ($now - $time > $this->config->item('captcha_expire')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return false;
		} elseif (($this->config->item('captcha_case_sensitive') and
			$code != $word) or
			strtolower($code) != strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return false;
		}
		return true;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return    bool
	 */
	public function _check_recaptcha($grecaptcha) {
		$this->load->library('google_recaptcha');
		$gcaptcha = trim($grecaptcha);
		if (!empty($gcaptcha) && isset($gcaptcha)) {
			$siteKey = $this->config->item('recaptcha_sitekey');
			$secret = $this->config->item('recaptcha_secretkey');
			$lang = 'en';
			$userIp = $this->input->ip_address();
			$response = $this->google_recaptcha->getCurlData($secret, $gcaptcha, $userIp);
			$status = json_decode($response, true);
			if ($status['success']) {
				return true;
			} else {
				$this->form_validation->set_message('_check_recaptcha', $this->lang->line('recaptcha_validation_failed'));
				return false;
			}
		}
	}

	public function compareUrls($a, $b) {
		$a = parse_url($a, PHP_URL_HOST);
		$b = parse_url($b, PHP_URL_HOST);

		return $this->trimWord($a) === $this->trimWord($b);
	}

	public function trimWord($str) {
		if (stripos($str, 'www.') === 0) {
			return substr($str, 4);
		}
		return $str;
	}

	public function gtmetrix_cron() {
		$date = date('Y-m-d H:i:s');
		$myfile = fopen("gtmetrix_logs.txt", "w") or die("Unable to open file!");

		$results = $this->analyze_model->gt_domain_gtmet();

		foreach ($results as $result) {

			$domainres = $this->analyze_model->getGtMetrixByDomainId($result->domain_name);
			$newTime = date('Y-m-d H:i:s', strtotime('+2 weeks', strtotime($domainres['created'])));
			if ($newTime <= date('Y-m-d H:i:s')) {
				$test = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
				$url_to_test = $result->domain_name;
				// fwrite($myfile, $date . "----------------------------------/n");
				// $txt = "Testing $url_to_test \r\n";
				// fwrite($myfile, $txt);
				$testid = $test->test(array(
					'url' => $url_to_test,
				));
				// if ($testid) {
				//     $txt = "Test started with " . $testid . " \r\n";
				//     fwrite($myfile, $txt);
				// } else {
				//     die("Test failed: " . $test->error() . " \r\n");
				//     $txt = "Test failed: " . $test->error() . " \r\n";
				//     fwrite($myfile, $txt);
				// }

				// $txt = "Waiting for test to finish \r\n";
				// fwrite($myfile, $txt);
				$test->get_results();

				// if ($test->error()) {
				//     die($test->error());
				//     $txt = $test->error() . " \r\n";
				//     fwrite($myfile, $txt);
				// }

				$gtmetrix = $test->results();
				if ($gtmetrix) {
					$data['image'] = $gtmetrix['report_url'] . "/screenshot.jpg";
					$data['datas'] = json_encode($gtmetrix);
				}

				// $txt           = "Done " . $url_to_test . " \r\n";
				// fwrite($myfile, $txt);
				$results = $this->analyze_model->update_gtmet_domain($data, $url_to_test);

				// $txt = $results . " \r\n";
				// fwrite($myfile, $txt);

				$count = array();
				$count['user_id'] = $result->userid;
				$count['domain_id'] = $result->id;
				$count['api_requested'] = date('Y-m-d H:i:s');
				$user = 0;
				$result_count = $this->analyze_model->update_gtmet_count($count, $user);
			}

		}
		fclose($myfile);
	}

	public function gtmetrix() {
		$return['status'] = false;
		$return['metrix'] = null;
		if (isset($_POST['gtmetrix'])) {
			$results = $this->analyze_model->get_gtme($_POST['gtmetrix']);
			if (!$results) {
				$data = array();
				$user_id = $this->ci_auth->get_user_id();
				$date = date('Y-m-d H:i:s');
				$test = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
				$url_to_test = $_POST['gtmetrix'];
				$testid = $test->test(array(
					'url' => $url_to_test,
				));
				$test->get_results();
				$gtmetrix = $test->results();
				$data['image'] = $gtmetrix['report_url'] . "/screenshot.jpg";
				$data['datas'] = json_encode($gtmetrix);
				$results = $this->analyze_model->update_gtmet_domain($data, $url_to_test);

				$count = array();
				$count['user_id'] = $this->ci_auth->get_user_id();
				$count['domain_id'] = $this->session->userdata('domainId');
				$count['api_requested'] = date('Y-m-d H:i:s');
				$user = 0;
				$result_count = $this->analyze_model->update_gtmet_count($count, $user);

				$results = $this->analyze_model->get_gtme($_POST['gtmetrix']);
			} else {
				$url = $_POST['gtmetrix'];
				$results = $this->analyze_model->get_gtme($url);
			}
			if ($results) {
				$return['status'] = true;
				$return['metrix'] = $results->datas;
				$date = date_create($results->created);
				$return['date'] = date_format($date, "l, F d, Y H:i:s A");
			}
		}
		echo json_encode($return);die;
	}

	public function refreshGTMetrixCount() {
		if (isset($_POST['domainId'])) {
			$credits = 0;
			$data = array();
			$domainId = $_POST['domainId'];
			$user_id = $this->ci_auth->get_user_id();
			$check = $this->analyze_model->check_gtmet_count($user_id);

			if ($check) {
				$credits_left = $check[0]->credits - $check[0]->count;
				if ($credits_left > 0) {
					$credits = 1;
					$date = date('Y-m-d H:i:s');
					$myfile = fopen("gtmetrix_logs.txt", "w") or die("Unable to open file!");

					$test = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
					$url_to_test = $this->session->userdata('domainUrl');
					// fwrite($myfile, $date . "----------------------------------/n");
					// $txt = "Testing $url_to_test \r\n";
					// fwrite($myfile, $txt);
					$testid = $test->test(array(
						'url' => $url_to_test,
					));
					// if ($testid) {
					//     $txt = "Test started with " . $testid . " \r\n";
					//     fwrite($myfile, $txt);
					// } else {
					//     die("Test failed: " . $test->error() . " \r\n");
					//     $txt = "Test failed: " . $test->error() . " \r\n";
					//     fwrite($myfile, $txt);
					// }

					// $txt = "Waiting for test to finish \r\n";
					// fwrite($myfile, $txt);
					$test->get_results();

					// if ($test->error()) {
					//     die($test->error());
					//     $txt = $test->error() . " \r\n";
					//     fwrite($myfile, $txt);
					// }

					$gtmetrix = $test->results();
					$data['image'] = $gtmetrix['report_url'] . "/screenshot.jpg";
					$data['datas'] = json_encode($gtmetrix);
					$txt = "Done " . $url_to_test . " \r\n";
					fwrite($myfile, $txt);
					$results = $this->analyze_model->update_gtmet_domain($data, $url_to_test);

					$txt = $results . " \r\n";
					fwrite($myfile, $txt);

					$count = array();
					$count['user_id'] = $this->ci_auth->get_user_id();
					$count['domain_id'] = $this->session->userdata('domainId');
					$count['api_requested'] = date('Y-m-d H:i:s');
					$user = 1;
					$result_count = $this->analyze_model->update_gtmet_count($count, $user);

					$url = $this->session->userdata('domainUrl');
					$results = $this->analyze_model->get_gtme($url);
					$data = json_decode($results[0]->datas);
				} else {
					$credits = 0;
				}
			}
			echo json_encode(array('datas' => $data, 'credits' => $credits));
			die();
		}
	}

	public function search_domain() {
		$domain = $this->input->post("search_text");
		if (!empty($domains)) {
			$domains = $this->analyze_model->getSearchedDomain($domain);
			foreach ($domains as $key => $domain) {
				$domains[$key]->host = parse_url($domain->domain_name, PHP_URL_HOST);
			}
			echo json_encode($domains);
		} else {
			echo json_encode('null');
		}

		die();
	}

	public function uptimestats() {

		$domainId = $this->input->post('domain_id');
		//domain info
		$domainDetails = $this->analyze_model->getDomain($domainId);

		$data['domain'] = $domainDetails[0];
		$data['uptime1daypercentage'] = $this->analyze_model->uptimePercentageInDays($domainId, 1);
		$data['uptime7daypercentage'] = $this->analyze_model->uptimePercentageInDays($domainId, 7);
		$data['uptime30daypercentage'] = $this->analyze_model->uptimePercentageInDays($domainId, 30);
		$data['totaluptimehours'] = $this->analyze_model->totalUptimeByDomainId($domainId);
		$data['uptimedaystats'] = $this->analyze_model->uptimeDayStatsByDomainId($domainId);
		$data['current_date'] = date('nS M Y');
		echo json_encode($data);die;
	}

	public function uptimestatoverall() {
		$data['uptime1daypercentage'] = $this->analyze_model->uptimePercentageInDaysOverall($this->ci_auth->get_user_id(), 1);
		$data['uptime7daypercentage'] = $this->analyze_model->uptimePercentageInDaysOverall($this->ci_auth->get_user_id(), 7);
		$data['uptime30daypercentage'] = $this->analyze_model->uptimePercentageInDaysOverall($this->ci_auth->get_user_id(), 30);
		$data['totaluptimehours'] = $this->analyze_model->totalUptimeByUserId($this->ci_auth->get_user_id());
		$data['uptimedaystats'] = $this->analyze_model->uptimeDayStatsByUserId($this->ci_auth->get_user_id());
		$data['current_date'] = date('d M Y');
		echo json_encode($data);
		die;
	}

	public function logbackin() {
		$this->ci_auth->logbackin();
	}

	public function editDomain_OLDER() {

		$data = $this->input->post('data');

		$domainId = (int) $data['domainId'];

		$array = array();
		$keyword_data = array();
		$engine_data = array();
		$array['userid'] = $this->ci_auth->get_user_id();
		if (!preg_match('/http/i', $this->input->post('domain'))) {
			$array['domain_name'] = 'http://' . $this->input->post('domain');
		} else {
			$array['domain_name'] = $this->input->post('domain');
		}

		$array['monitorOnPageIssues'] = $this->input->post('monitor');
		// Code Added by Mannan
		//$array['keyword'] = $this->input->post('keywords');
		//$array['searchEngine'] = $this->input->post('engines');
		$keywords = explode(PHP_EOL, $this->input->post('keywords'));
		//adding engines
		$engines = array('g_uk');
		foreach ($engines as $eng) {
			if ($this->input->post('mobile_search') == 1) {
				array_push($engines, $eng . '-mobile');
			}
		}

		$array['mobile_search'] = $this->input->post('mobile_search');
		$array['local_search'] = $this->input->post('local_search');
		$array['is_ecommerce'] = $this->input->post('is_ecommerce');
		// End
		$connected = $this->analyze_model->getUserOAuthDetails($array['userid']);
		if ($connected != null) {
			$array['connectToGoogle'] = 1;
		} else {
			$array['connectToGoogle'] = $this->input->post('connectToGoogle');
		}
		$array['ga_account'] = $this->input->post('ga_account');
		$array['monitorMalware'] = $this->input->post('monitorMalware');
		$array['adminURL'] = $this->input->post('adminURL');
		$array['adminUsername'] = $this->input->post('adminUsername');
		$array['webmaster'] = $this->input->post('webmaster');
		$array['search_analytics'] = $this->input->post('search_analytics');

		print_r($array);
		die();

		$domain_id = $this->analyze_model->update_domains($array, $domainId);
		$array['domain_id'] = $domain_id;
		$keyword_data['domain_id'] = $domain_id;
		$keyword_data['user_id'] = $array['userid'];
		$engine_data['domain_id'] = $domain_id;
		$engine_data['user_id'] = $array['userid'];

		//adding the local search towns
		if ($array['local_search'] == 1) {
			$towns = $this->input->post('towns');
			if ($towns) {
				foreach ($towns as $town) {
					$this->analyze_model->insertLocalSearchTown($town, $domainId, $array['userid']);
				}
			}

		}
		// Code Added by Mannan
		if ($domain_id != null) {
			foreach ($engines as $key => $engine) {
				$engine_data['name'] = $engine;
				$array['locale'] = $engine;
				$array['search_engine_id'] = $this->analyze_model->insertEngine($engine_data);
				foreach ($keywords as $keys => $keyword) {
					if ($keyword != '') {
						$keyword_data['name'] = $keyword;
						$array['keyword'] = trim(strtolower($keyword));
						$array['keyword_id'] = $this->analyze_model->insertkeyword($keyword_data);
						if (preg_match('/mobile/i', $engine)) {
							$array['mobile_search'] = 1;
						} else {
							$array['mobile_search'] = 0;
						}
					}

				}
			}
		}
		// End
		if ($domain_id != null) {
			$domains = $this->analyze_model->getDomain($domain_id);
			foreach ($domains as $key => $domain) {
				$domains[$key]->host = parse_url($domain->domain_name, PHP_URL_HOST);
			}
			if ($this->input->post('monitorUptime') == 1) {
				//checking if uptime exist
				$exist = $this->analyze_model->getUptimeByDomainId($domain_id);
				if ($exist) {
					$this->updateUptime($_POST, $domain_id);
				} else {
					$this->adduptime($_POST, $domain_id);
				}
			} else {
				$user_id = $this->ci_auth->get_user_id();
				//check if has parent user
				$this->db->flush_cache();
				$this->db->select("parent_id");
				$this->db->from('users as u');
				$this->db->where("id=", $user_id);
				$this->db->limit(1);
				$query = $this->db->get();
				$parentDetail = $query->row_array();
				if ($parentDetail['parent_id'] != null) {
					$user_id = $parentDetail['parent_id'];
				}
				//userdetails
				$userinfo = $this->analyze_model->getusersinfo($user_id);
				//delete domain
				$this->domain_model->stopDomain($userinfo, $domain_id);
			}

			//adding subusers to domain
			$subusers = $_POST['subusers'];
			if ($subusers) {
				$this->analyze_model->assignDomainToSubuser($domain_id, $subusers, $this->ci_auth->get_user_id());

			}
			//adding domain to groups
			$groups = $_POST['groups'];
			if ($groups) {
				$this->groups_model->assignDomainToGroup($domain_id, $groups);
			}

			$selectedDomain = array("domainId" => $domains[0]->id,
				"monitorMalware" => $domains[0]->monitorMalware,
				"adminURL" => $domains[0]->adminURL,
				"adminUsername" => $domains[0]->adminUsername,
				"domainUrl" => $domains[0]->domain_name,
				"gaAccount" => $domains[0]->ga_account,
				"connectToGoogle" => $domains[0]->connectToGoogle,
				"monitorOnPageIssues" => $domains[0]->monitorOnPageIssues,
				"domainHost" => $domains[0]->host,
				"webmaster" => $domains[0]->webmaster,
				"search_analytics" => $domains[0]->search_analytics);
			$this->session->set_userdata($selectedDomain);

			/* ************ GT Metrix Request ************ */

			$date = date('Y-m-d H:i:s');
			// if (is_writable("gtmetrix_logs.txt")) {
			//     $myfile      = fopen("gtmetrix_logs.txt", "w");
			//     $test        = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
			//     $url_to_test = $domains[0]->domain_name;
			//     $testid = $test->test(array(
			//         'url' => $url_to_test,
			//     ));
			//     $test->get_results();
			//     $gtmetrix      = $test->results();
			//     if($gtmetrix){
			//         $data['image'] = $gtmetrix['report_url'] . "/screenshot.jpg";
			//         $data['datas'] = json_encode($gtmetrix);
			//         $results = $this->analyze_model->update_gtmet_domain($data, $url_to_test);
			//     }
			//     $count                  = array();
			//     $count['user_id']       = $this->ci_auth->get_user_id();
			//     $count['domain_id']     = $domain_id;
			//     $count['api_requested'] = date('Y-m-d H:i:s');
			//     $user                   = 0;
			//     $result_count           = $this->analyze_model->update_gtmet_count($count, $user);
			// }
			//update to piwik
			// $this->updatePiwik($domain_id, $this->ci_auth->get_user_id(), $array['is_ecommerce']);
		}
		// End
		echo json_encode(array('msg' => 'success', 'domainURL' => $selectedDomain['domainUrl'], 'domainHost' => $selectedDomain['domainHost'], 'webmaster' => $selectedDomain['webmaster'], 'search_analytics' => $selectedDomain['search_analytics'], 'domainId' => $selectedDomain['domainId']));
		die();
	}

	public function editDomain() {

		$return = array('error' => 1);

		$pdata = $this->input->post('data');

		$domainId = (int) $pdata['domainId'];

		// $keywords$keywords = explode(PHP_EOL, $pdata['keywords']);
		$keywords = preg_split('/[\n\r]+/', $pdata['keywords']);
		if (is_array($keywords) && !empty($keywords)) {
			$keywords = array_reverse($keywords);
		}

		$array = array();
		$keyword_data = array();
		$engine_data = array();

		$user_id = $this->ci_auth->get_user_id();
        $array['domain_name'] = ( ! preg_match('/http/i', $pdata['domain']) ? 'http://' : '' ) . $pdata['domain'];
        $array['monitorOnPageIssues'] = $pdata['monitor']; // NOTE: Missing relative option/setting.
        $array['mobile_search'] = $pdata['mobile_search'];
        $array['local_search'] = $pdata['local_search'];
        $array['is_ecommerce'] = $pdata['is_ecommerce'];
        $array['ga_account'] = $pdata['ga_account'];
        $array['monitorMalware'] = (int) $pdata['monitorMalware'];
        $array['monitorUptime'] = $pdata['monitorUptime'];
        $array['adminURL'] = $pdata['adminURL'];
        $array['adminUsername'] = $pdata['adminUsername'];
        $array['webmaster'] = (int) $pdata['webmaster'];
        $array['search_analytics'] = (int) $pdata['search_analytics'];
        
        // $array['connectToGoogle'] = null === $connected ? $pdata['connectToGoogle'] : 1;
        $array['connectToGoogle'] = (int) $pdata['connectToGoogle'];
        
        if( $array['connectToGoogle'] ){
            $connected = $this->analyze_model->getUserOAuthDetails( $user_id );
            if( null === $connected ){
                $array['connectToGoogle'] = 0;
            }
        }
        
        $domain_id = $this->analyze_model->update_domains( $array, $domainId );
        
        if( null === $domain_id ){
            $return['msg'] = 'Invalid domain ID';
            echo json_encode( $return );
            die();
        }
        
        $keyword_data['domain_id'] = $domain_id;
        $keyword_data['user_id'] = $user_id;
        
        $engine_data['domain_id'] = $domain_id;
        $engine_data['user_id'] = $user_id;
        
        // Adding local search towns.
        if ( 1 === $array['local_search'] ) {
            $towns = $pdata['towns'];
            if ( ! empty( $towns) ) {
                foreach ($towns as $town) {
                    $this->analyze_model->insertLocalSearchTown( $town, $domainId, $user_id );
                }
            }
		}

		// Delete old keywords from DB.
		$old_keywords = $this->analyze_model->getkeywordByDomainId( $domain_id );
        if( ! empty( $old_keywords ) ){
            foreach($old_keywords as $k => $v){
                $this->analyze_model->deleteKeyword( $domain_id, $v['name'], $user_id );
            }
        }

        // Adding search engines.
        $engines = ! empty( $pdata['engines'] ) ? $pdata['engines'] : array();

        if( ! empty( $engines ) ){
            foreach ($engines as $eng) {
                if ( 1 === (int) $pdata['mobile_search'] ) {
                    array_push($engines, $eng . '-mobile');
                }
            }
        }

        foreach ($engines as $key => $engine) {
            
            $engine_data['name'] = $engine;
            $this->analyze_model->insertEngine( $engine_data );

            if( ! empty( $keywords ) ){
                foreach ( $keywords as $keys => $keyword) {
                    $keyword = trim($keyword);
                    if ( '' !== $keyword ) {
                        $keyword_data['name'] = $keyword;
                        $this->analyze_model->insertkeyword( $keyword_data );
                    }
                }
            }
        }

		foreach ($engines as $key => $engine) {

			$engine_data['name'] = $engine;

			$array['locale'] = $engine;
			$array['search_engine_id'] = $this->analyze_model->insertEngine($engine_data);

			if (!empty($keywords)) {
				foreach ($keywords as $keys => $keyword) {
					$keyword = trim($keyword);
					if ('' !== $keyword) {
						$keyword_data['name'] = $keyword;
						$array['keyword'] = trim(strtolower($keyword));
						$array['keyword_id'] = $this->analyze_model->insertkeyword($keyword_data);
						$array['mobile_search'] = preg_match('/mobile/i', $engine) ? 1 : 0;
					}
				}
			}
		}

		$domains = $this->analyze_model->getDomain($domain_id);
		if (!empty($domains)) {
			foreach ($domains as $key => $domain) {
				$domains[$key]->host = parse_url($domain->domain_name, PHP_URL_HOST);
			}
		}

		if (1 === (int) $pdata['monitorUptime']) {
			//checking if uptime exist
			$exist = $this->analyze_model->getUptimeByDomainId($domain_id);
			if ($exist) {
				$this->updateUptime($pdata, $domain_id);
			} else {
				$this->adduptime($pdata, $domain_id);
			}
		} else {

			$user_id = $this->ci_auth->get_user_id();

			//check if has parent user
			$this->db->flush_cache();
			$this->db->select("parent_id");
			$this->db->from('users as u');
			$this->db->where("id=", $user_id);
			$this->db->limit(1);
			$query = $this->db->get();
			$parentDetail = $query->row_array();

			// Inherit parent ID.
			$user_id = null !== $parentDetail['parent_id'] ? $parentDetail['parent_id'] : $user_id;

			// User details.
			$userinfo = $this->analyze_model->getusersinfo($user_id);

			// Delete domain.
			$this->domain_model->stopDomain($userinfo, $domain_id);
		}

		// Adding subusers to domain.
		$this->analyze_model->assignDomainToSubuser($domain_id, $pdata['subusers'], $this->ci_auth->get_user_id());

		// Adding domain to groups.
		$this->groups_model->assignDomainToGroup($domain_id, $pdata['groups']);

		$selectedDomain = array(
			"domainId" => $domains[0]->id,
			"monitorMalware" => $domains[0]->monitorMalware,
			"adminURL" => $domains[0]->adminURL,
			"adminUsername" => $domains[0]->adminUsername,
			"domainUrl" => $domains[0]->domain_name,
			"gaAccount" => $domains[0]->ga_account,
			"connectToGoogle" => $domains[0]->connectToGoogle,
			"monitorOnPageIssues" => $domains[0]->monitorOnPageIssues,
			"domainHost" => $domains[0]->host,
			"webmaster" => $domains[0]->webmaster,
			"search_analytics" => $domains[0]->search_analytics,
		);

		$this->session->set_userdata($selectedDomain);

		/* ************ GT Metrix Request ************ */

		// $date = date('Y-m-d H:i:s');

		// if (is_writable("gtmetrix_logs.txt")) {
		//     $myfile      = fopen("gtmetrix_logs.txt", "w");
		//     $test        = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
		//     $url_to_test = $domains[0]->domain_name;
		//     $testid = $test->test(array(
		//         'url' => $url_to_test,
		//     ));
		//     $test->get_results();
		//     $gtmetrix      = $test->results();
		//     if($gtmetrix){
		//         $data['image'] = $gtmetrix['report_url'] . "/screenshot.jpg";
		//         $data['datas'] = json_encode($gtmetrix);
		//         $results = $this->analyze_model->update_gtmet_domain($data, $url_to_test);
		//     }
		//     $count                  = array();
		//     $count['user_id']       = $this->ci_auth->get_user_id();
		//     $count['domain_id']     = $domain_id;
		//     $count['api_requested'] = date('Y-m-d H:i:s');
		//     $user                   = 0;
		//     $result_count           = $this->analyze_model->update_gtmet_count($count, $user);
		// }
		//update to piwik
		// $this->updatePiwik($domain_id, $this->ci_auth->get_user_id(), $array['is_ecommerce']);

		echo json_encode(
			array(
				'error' => 0,
				'msg' => 'success',
				'domainURL' => $selectedDomain['domainUrl'],
				'domainHost' => $selectedDomain['domainHost'],
				'webmaster' => $selectedDomain['webmaster'],
				'search_analytics' => $selectedDomain['search_analytics'],
				'domainId' => $selectedDomain['domainId'],
			)
		);

		die();
	}

	public function deletedomain($domainId) {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			/* logged in */

			if ($this->ci_auth->canDo('login_to_frontend')) {

				$domainExist = $this->domain_model->getDomainByDomainIdAndUserId($domainId, $this->ci_auth->get_user_id());

				if ($domainExist) {

					$user_id = $this->ci_auth->get_user_id();
					//check if has parent user
					$this->db->flush_cache();
					$this->db->select("parent_id");
					$this->db->from('users as u');
					$this->db->where("id=", $user_id);
					$this->db->limit(1);
					$query = $this->db->get();
					$parentDetail = $query->row_array();
					if ($parentDetail['parent_id'] != null) {
						$user_id = $parentDetail['parent_id'];
					}

					//userdetails
					$userinfo = $this->analyze_model->getusersinfo($user_id);

					//delete domain
					$this->domain_model->deleteDomain($userinfo, $domainId);
					$this->session->set_flashdata('type', 'success');
					$this->session->set_flashdata('msg', 'Domain deleted successfully');

					redirect(site_url('/auth/home/'));

				} else {
					//domain does not belong to username
					$this->session->set_flashdata('type', 'error');
					$this->session->set_flashdata('msg', 'Sorry, you are not authorized to access this domain');
					redirect(site_url('/auth/home/'));
				}

			} else {
				redirect(site_url('/admin/login'));
			}
		}
	}

	public function updateUptime($data, $ins_site_id) {

		$url = $data['domain'];

		if (false === strpos($url, '://')) {
			$url = 'http://' . $url;
		}

		$parse = parse_url($url);
		//$parse['host']
		$keywords = array();

		if ((isset($data['page_header'])) || (isset($data['page_header'])) || (isset($data['page_header']))) {
			$keywords = array(
				'header' => $data['page_header'],
				'body' => $data['page_body'],
				'footer' => $data['page_footer'],
				'frequency' => $data['frequency'],
			);
		}

		$json_keywords = json_encode($keywords);
		$data = array();
		// get upmonitor existing user id
		$userid = $this->ci_auth->get_user_id();
		$userinfo = $this->analyze_model->getusersinfo($userid);
		$uptime = $this->analyze_model->getUptimeByDomainId($ins_site_id);

		if ($userinfo && $uptime) {
			$upm_siteid = $this->upmonitor->update_site($userinfo[0]->upmon_id, $userinfo[0]->uptime_token, $userinfo[0]->username, $userinfo[0]->password, $url, $json_keywords, $uptime['up_time_id']);
			$upm_siteid = json_decode($upm_siteid);
			if ($json_keywords) {
				$data['keyword'] = $json_keywords;
			} else {
				$data['keyword'] = '';
			}
			$this->db->flush_cache();
			$this->db->where('domain_id', $ins_site_id);
			$result = $this->db->update('uptime', $data);

			return $upm_siteid;

		} else {
			return true;
		}
	}

	public function addsubuser() {
		if ($_POST) {
			$this->analyze_model->addSubUser($_POST, $this->ci_auth->get_user_id());
			redirect(site_url('/listsubuser'));
		} else {
			if (!$this->ci_auth->is_logged_in()) {
				redirect(site_url('auth/login'));
			} elseif ($this->ci_auth->is_logged_in(false)) {
				// logged in, not activated
				redirect('/auth/sendactivation/');
			} else {
				if ($this->ci_auth->canDo('login_to_frontend')) {
					$this->load->view(get_template_directory() . '/addsubuser');

				}
			}
		}
	}

	public function deletesubuser() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			if ($this->ci_auth->canDo('login_to_frontend')) {
				$subuserid = $this->uri->segment(2);
				$this->analyze_model->deleteUser($subuserid, $this->ci_auth->get_user_id());
			}
			redirect(site_url('/listsubuser'));
		}
	}

	public function editsubuser() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			if ($_POST) {
				$this->analyze_model->updatesubuser($_POST, $this->ci_auth->get_user_id());
				redirect(site_url('/listsubuser'));

			} else {
				//getting the user details
				$subuserid = $this->uri->segment(2);
				$userinfo = $this->analyze_model->getsubuserBySubuserId($subuserid, $this->ci_auth->get_user_id());
				$data['user'] = $userinfo;
				$this->load->view(get_template_directory() . '/editsubuser', $data);
			}
		}
	}

	public function listsubuser() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			//getting the user details
			$userinfo = $this->analyze_model->getallsubuserbyuserId($this->ci_auth->get_user_id());
			//getting the domain assigned to subuser
			if ($userinfo) {
				$return = array();
				foreach ($userinfo as $key => $user) {
					$return[$key] = $user;
					$domains = $this->analyze_model->getTotalDomainsByUserId($user['id']);
					$return[$key]['total_domains'] = $domains['totalDomain'];
				}
				$userinfo = $return;
			}
			$data['userlist'] = $userinfo;
			$this->load->view(get_template_directory() . '/listsubuser', $data);
		}
	}

	/*public function addclient()
        {
            // print_r('sdfds');
            // die();
            if ($_POST) {
                $this->analyze_model->addclient($_POST, $this->ci_auth->get_user_id());
                redirect(site_url('/listclient'));
            } else {
                if (!$this->ci_auth->is_logged_in()) {
                    redirect(site_url('auth/login'));
                } elseif ($this->ci_auth->is_logged_in(false)) {
                    // logged in, not activated
                    redirect('/auth/sendactivation/');
                } else {
                    if ($this->ci_auth->canDo('login_to_frontend')) {
                        $this->load->view(get_template_directory() . 'new-ui/addclient');

                    }
                }
            }
        }

        public function listclient()
        {
            if (!$this->ci_auth->is_logged_in()) {
                redirect(site_url('auth/login'));
            } elseif ($this->ci_auth->is_logged_in(false)) {
                // logged in, not activated
                redirect('/auth/sendactivation/');
            } else {
                //getting the user details
                $userinfo = $this->analyze_model->getallclientbyclientId($this->ci_auth->get_user_id());
                //getting the domain assigned to subuser
                if ($userinfo) {
                    $return = array();
                    foreach ($userinfo as $key => $user) {
                        $return[$key]                  = $user;
                        $domains                       = $this->analyze_model->getTotalDomainsByUserId($user['id']);
                        $return[$key]['total_domains'] = $domains['totalDomain'];
                    }
                    $userinfo = $return;
                }
                $data['clientlist'] = $userinfo;
                $this->load->view(get_template_directory() . 'new-ui/listclient', $data);
            }

        }

        public function editclient()
        {

            if (!$this->ci_auth->is_logged_in()) {
                redirect(site_url('auth/login'));
            } elseif ($this->ci_auth->is_logged_in(false)) {
                // logged in, not activated
                redirect('/auth/sendactivation/');
            } else {
                if ($_POST) {

                    $this->analyze_model->updateclient($_POST, $this->ci_auth->get_user_id());
                    redirect(site_url('listclient'));

                } else {
                    //getting the user details
                    $clientid    = $this->uri->segment(2);
                    $userinfo     = $this->analyze_model->getclientbyclientId($clientid, $this->ci_auth->get_user_id());
                    $data['client'] = $userinfo;
                    $this->load->view(get_template_directory() . 'new-ui/editclient', $data);
                }
            }

        }

        public function deleteclient()
        {
            if (!$this->ci_auth->is_logged_in()) {
                redirect(site_url('auth/login'));
            } elseif ($this->ci_auth->is_logged_in(false)) {
                // logged in, not activated
                redirect('/auth/sendactivation/');
            } else {
                if ($this->ci_auth->canDo('login_to_frontend')) {
                    $clientid = $this->uri->segment(2);
                    $this->analyze_model->deleteClient($clientid, $this->ci_auth->get_user_id());
                }
                redirect(site_url('/listclient'));
            }
        }
	*/

	public function assigndomain() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {

			if ($_POST) {
				$domains = $_POST['domain'];
				$subuserid = ($_POST['user']) ? $_POST['user'] : 0;
				$groupid = $_POST['group'];
				$this->analyze_model->assigndomains($domains, $subuserid, $groupid);
				redirect('/assigndomain/' . $subuserid . '/' . $groupid);
			} else {
				$subuserid = $this->uri->segment(2);
				$groupid = $this->uri->segment(3);
				if ($subuserid) {
					$subuserdomains = $this->analyze_model->getAllDomainsBySubuserId($subuserid);
				} else {
					$subuserdomains = array();
				}
				if ($groupid) {
					$groupdomains = $this->groups_model->getDomainsIdsByGroupId($groupid);
				} else {
					$groupdomains = array();
				}

				$data['subuserdomains'] = $subuserdomains;
				$data['groupdomains'] = $groupdomains;
				$data['subuserid'] = $subuserid;
				$data['groupid'] = $groupid;
				$alldomains = $this->analyze_model->getAllDomainsByUserId($this->ci_auth->get_user_id());
				$data['domains'] = $alldomains;
				//getting all subusers
				$allsubusers = $this->analyze_model->getallsubuserbyuserId($this->ci_auth->get_user_id());
				$data['subusers'] = $allsubusers;
				$data['groups'] = $this->groups_model->getGroupsByUserId($this->ci_auth->get_user_id());
				$this->load->view(get_template_directory() . '/assigndomains', $data);
			}

		}
	}

	public function listgroups() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			//getting the user details
			$groups = $this->groups_model->getGroupsByUserId($this->ci_auth->get_user_id());
			$data['groups'] = $groups;
			$this->load->view(get_template_directory() . '/listgroups', $data);
		}
	}

	public function addgroups() {
		if ($_POST) {
			$this->groups_model->addGroups($_POST, $this->ci_auth->get_user_id());
			redirect(site_url('/listgroups'));
		} else {
			if (!$this->ci_auth->is_logged_in()) {
				redirect(site_url('auth/login'));
			} elseif ($this->ci_auth->is_logged_in(false)) {
				// logged in, not activated
				redirect('/auth/sendactivation/');
			} else {
				if ($this->ci_auth->canDo('login_to_frontend')) {
					$this->load->view(get_template_directory() . '/addgroups');

				}
			}
		}
	}

	public function deletegroups() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			if ($this->ci_auth->canDo('login_to_frontend')) {
				$groupid = $this->uri->segment(2);
				$this->groups_model->deleteGroups($groupid, $this->ci_auth->get_user_id());
			}
			redirect(site_url('/listgroups'));
		}

	}

	public function editgroups() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			if ($_POST) {
				$this->groups_model->updategroups($_POST);
				redirect(site_url('/listgroups'));

			} else {
				//getting the user details
				$groupid = $this->uri->segment(2);
				$groupdetail = $this->groups_model->getGroupByGroupId($groupid);
				$data['groupdetail'] = $groupdetail;
				$this->load->view(get_template_directory() . '/editgroups', $data);
			}
		}
	}

	public function setcurrentuser() {
		$id = $this->uri->segment(4);
		$_SESSION['currentsubuser'] = $id;
		$_SESSION['currentgroup'] = 0;
		redirect(site_url('auth/home'));
	}

	public function setcurrentgroup() {
		$id = $this->uri->segment(4);
		$_SESSION['currentgroup'] = $id;
		$_SESSION['currentsubuser'] = 0;
		redirect(site_url('auth/home'));
	}

	public function test() {
		$this->addPiwik(16, $this->ci_auth->get_user_id(), false);
	}

	public function randomString($length = 7) {
		$str = "";
		$characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}

	public function sso() {
		$api_key = trim($this->uri->segment(2));
		$user = $this->analyze_model->getUserByApiKey($api_key);
		if ($user) {

			if ($this->ci_auth->login($user['email'], $user['password'], true, false, 1, 1)) {
				/* success */

				$session = $this->session->get_userdata();
				$userinfo = $this->analyze_model->getusersinfo($session['user_id']);
				$session['email'] = $userinfo[0]->email;
				$this->session->set_userdata($session);
				//check number of domains of the user
				$totalDomains = $this->analyze_model->getTotalDomainsByUserId($session['user_id']);
				if ($totalDomains['totalDomain'] == 1) {
					$singleDomain = $this->analyze_model->getSingleDomainByUserId($session['user_id']);
					redirect(site_url('/auth/dashboard/' . $singleDomain['id']));
				} else {
					redirect(site_url('/auth/home'));
				}

			}
		} else {
			//invalid sso redirect to wordpress login
		}
	}

	public function createuser() {
		if ($_POST) {
			$api_key = $_POST['api_key'];
			//checking the api key if valid
			if ($api_key == $this->api_key) {
				$apiKey = $this->analyze_model->createUser($_POST);
				$return['status'] = true;
				$return['msg'] = 'User has been created successfully';
				$return['api_key'] = $apiKey;

			} else {
				$return['status'] = false;
				$return['msg'] = 'Invalid api key';
			}

		} else {
			$return['status'] = false;
			$return['msg'] = 'Invalid api key';
		}
		echo json_encode($return);die;
	}

}
