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

class Business extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('auth/analyze_model');
		$this->load->model('auth/groups_model');
	}

	public function index() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			redirect('/auth/sendactivation/');
		} else {
			/* logged in */
			$id = $this->uri->segment(3);
			$id = $id ? (int) $id : '';
			$data['domainid'] = $id;

			if (isset($_SESSION['currentsubuser']) && $_SESSION['currentsubuser'] != '' && $_SESSION['currentsubuser'] != 0) {
				$userId = $_SESSION['currentsubuser'];
			} else {
				$userId = $this->ci_auth->get_user_id();
			}

			if (isset($_SESSION['currentgroup']) && $_SESSION['currentgroup'] && $_SESSION['currentgroup'] != 0) {
				$groupId = $_SESSION['currentgroup'];
				$data['total_domains'] = $this->groups_model->getTotalDomainsBygroupId($groupId);
				$data['up_domains'] = $this->groups_model->getTotalUpdomainsBygroupId($groupId);
				$data['down_domains'] = $data['total_domains']['totalDomain'] - $data['up_domains']['totalUpdomains'];

			} else {
				$data['total_domains'] = $this->analyze_model->getTotalDomainsByUserId($userId);
				$data['up_domains'] = $this->analyze_model->getTotalUpdomainsByUserId($userId);
				$data['down_domains'] = $data['total_domains']['totalDomain'] - $data['up_domains']['totalUpdomains'];
			}

			$this->load->view(get_template_directory() . '/new-ui/crm/businessoverview', $data);

		}
	}

	public function alldomains() {

		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			$return['status'] = false;
			$retun['domains'] = null;
		} else {
			if (isset($_POST['domain'])) {
				$domain = $_POST['domain'];
			} else {
				$domain = '';
			}
			if (isset($_SESSION['currentsubuser']) && $_SESSION['currentsubuser']) {
				if ($_SESSION['currentsubuser'] != 0) {
					$currentsubuser = $_SESSION['currentsubuser'];
				} else {
					$currentsubuser = $this->ci_auth->get_user_id();
				}
			} else {
				$currentsubuser = $this->ci_auth->get_user_id();
			}
			$domains = $this->analyze_model->getDomainsByUserId($currentsubuser, $domain, 7);

			if (isset($_SESSION['currentgroup']) && $_SESSION['currentgroup'] && $_SESSION['currentgroup'] != 0) {
				$currentgroup = $_SESSION['currentgroup'];
				$domains = $this->groups_model->getGroupDetailByGroupId($currentgroup, 7);

			} else {
				// $currentsubuser = $this->ci_auth->get_user_id();
				$domains = $this->analyze_model->getDomainsByUserId($currentsubuser, $domain, 7);
			}

			$return = array();

			foreach ($domains as $key => $domain) {
				$return['domain'][$key] = $domain;
				$return['domain'][$key]['domain_name'] = strtr($domain['domain_name'], array('https://www.' => '', 'http://www.' => '', 'http://' => '', 'https://' => '', '/' => ''));
				// $return['domain'][$key]['uptimepercentage']  = $this->uptimestats_model->uptimePercentageInDays($domain['id'],1);
			}
			if ($domains) {
				$return['status'] = true;
			} else {
				$return['status'] = false;
			}

		}
		echo json_encode($return);die;
	}

	public function uptimestats() {
		if (!$this->ci_auth->is_logged_in()) {
			redirect(site_url('auth/login'));
		} elseif ($this->ci_auth->is_logged_in(false)) {
			// logged in, not activated
			$return['status'] = false;
			$retun['uptime'] = null;
		} else {
			//getting all domains of user
			/* logged in */
			$uptime = $this->analyze_model->getUptimeDashboardStatByUserId($this->ci_auth->get_user_id());
			$return['status'] = true;
			$return['uptime'] = $uptime;

		}
		echo json_encode($return);die;

	}

	// public function getresponsegraph()
	// {
	//     $uptimedaystats = $this->uptimestats_model->uptimeDayStatsByUserId($this->ci_auth->get_user_id());
	//     if ($uptimedaystats) {
	//        $data['uptimestats'] = $uptimedaystats;
	//        $return['payload'] = $data;
	//        $return['type'] = 'success';
	//     } else {
	//         $data['uptimestats'] = null;
	//         $return['type'] = 'error';
	//         $return['payload'] = null;
	//     }
	//     echo json_encode($return);die;
	// }

	public function incidentreport() {
		$incidents = $this->uptimeincident_model->getDownIncidentsByUserId($this->ci_auth->get_user_id());
		if ($incidents) {
			$temp = array();
			foreach ($incidents as $key => $incident) {
				$temp[$key] = $incident;
				$domain = strtr($incident['domain_name'], array('https://' => '', 'http://' => '', 'www.' => '', '/' => ''));
				$temp[$key]['domain_name'] = $domain;
				$date1 = $incident['downtime'];
				$date2 = $incident['uptime'];
				if ($date2) {
					$diff = abs(strtotime($date2) - strtotime($date1));
					$diff = ($diff);
				} else {
					$diff = "-";
				}
				if ($diff != '-') {
					$temp[$key]['totaloutagetime'] = gmdate('H:i:s', $diff);
				} else {
					$temp[$key]['totaloutagetime'] = '-';
				}

			}

			$return['payload'] = $temp;
			$return['type'] = 'success';
		} else {
			$return['payload'] = null;
			$return['type'] = 'error';
		}
		echo json_encode($return);die;
	}

}

/* End of file auth.php */
/* Location: ./controllers/home.php */
