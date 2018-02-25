<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analyze extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library('form_validation');
        $this->load->library('my_form_validation');
        $this->load->model('auth/user_model');
        $this->load->model('auth/analyze_model');
        $this->load->library('session');
    }

    public function index() {
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
                $user_profile = $this->user_model->get_user($user_id);
                $data['profile'] = $user_profile[0];
                $data['seo_title'] = 'Analyze';

                $sessionDomain = $this->session->userdata('domainId');
                if (!$sessionDomain) {
                   $domain = $this->analyze_model->getLastDomainByUserId($user_id);
                   $domain['domain_name'] = parse_url($domain['domain_name'], PHP_URL_HOST);

                   if ($domain) {
                       $selectedDomain = array("domainId" => $domain['id'],
                           "monitorMalware"                   => $domain['monitorMalware'],
                           "adminURL"                         => $domain['adminURL'],
                           "adminUsername"                    => $domain['adminUsername'],
                           "domainUrl"                        => $domain['domain_name'],
                           "gaAccount"                        => $domain['ga_account'],
                           "connectToGoogle"                  => $domain['connectToGoogle'],
                           "monitorOnPageIssues"              => $domain['monitorOnPageIssues'],
                           "domainHost"                       => $domain['host'],
                           "webmaster"                        => $domain['webmaster'],
                           "search_analytics"                 => $domain['search_analytics']);
                       $this->session->set_userdata($selectedDomain);
                   }     
                }
                $monitor = $this->session->userdata('monitorOnPageIssues');
                if ($monitor == 0) {
                   $this->load->view(get_template_directory() . 'analyze_noreport', $report);
                } else {
                    $dareboost_report = array();


                    $dareboost_report = (array) $this->analyze_model->checkDomainReport($this->session->userdata('domainUrl'));

                    if (count($dareboost_report) > 0) {
                        $dareboost = $dareboost_report[0];
                        $current_date = date('Y-m-d');
                        $date = date('Y-m-d', strtotime($dareboost->created . '+ 7 days'));

                        if ($current_date == $date) {
                            $result1 = $this->get_google_page_speed_result($this->session->userdata('domainUrl'));

                            // $result1 = $this->session->userdata('result1');

                            $report = array();
                            if (!empty($result1)) {
                                if ($result1['status'] == 200) {
                                    do {
                                        $params1 = array(
                                            "token" => "56b0e1de0cf2409e7760f005",
                                            "reportId" => $result1['reportId']
//                                    "reportId" => '56925f490cf24b6379fb3613'
                                        );


                                        $param1 = json_encode($params1);

                                        $ch = curl_init();

                                        curl_setopt($ch, CURLOPT_URL, "https://www.dareboost.com/api/0.1/analysis/report");
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $param1);
                                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'Content-Type: application/json',
                                            'Content-Length: ' . strlen($param1))
                                        );
                                        $report = curl_exec($ch);
                                        curl_close($ch);
                                        $report = json_decode($report, true);
                                    } while ($report['status'] != 200);


                                    if ($report['status'] == 200) {
                                        $report_data['user_id'] = $user_id;
                                        $report_data['report_id'] = $result1['reportId'];
//                                $report_data['report_id'] = '56925f490cf24b6379fb3613';
                                        $report_data['token'] = '56b0e1de0cf2409e7760f005';
                                        $report_data['website_url'] = $report['report']['url'];
                                        $report_data['weight'] = $report['report']['summary']['weight'] / 1000;
                                        $report_data['load_time'] = $report['report']['summary']['loadTime'] / 1000;
                                        $this->analyze_model->update_analyze_data($dareboost->id, $report_data);
                                    }
                                }

                                $this->load->view(get_template_directory() . 'analyze_new', $report);
                                //$report = json_decode($report, true);
                            } else {
                                $this->load->view(get_template_directory() . 'analyze_new', $data);
                            }
                        } else {

                            do {
                                $params1 = array(
                                    "token" => "56b0e1de0cf2409e7760f005",
                                    "reportId" => $dareboost->report_id
//                                    "reportId" => '56925f490cf24b6379fb3613'
                                );


                                $param1 = json_encode($params1);

                                $ch = curl_init();

                                curl_setopt($ch, CURLOPT_URL, "https://www.dareboost.com/api/0.1/analysis/report");
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $param1);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                    'Content-Type: application/json',
                                    'Content-Length: ' . strlen($param1))
                                );
                                $report = curl_exec($ch);
                                curl_close($ch);
                                $report = json_decode($report, true);
                            } while ($report['status'] != 200);
                            $this->load->view(get_template_directory() . 'analyze_new', $report);
                        }
                    } else {
                        $result1 = $this->get_google_page_speed_result($this->session->userdata('domainUrl'));

                        // $result1 = $this->session->userdata('result1');

                        $report = array();
                        if (!empty($result1)) {
                            if ($result1['status'] == 200) {
                                do {
                                    $params1 = array(
                                        "token" => "56b0e1de0cf2409e7760f005",
                                        "reportId" => $result1['reportId']
//                                    "reportId" => '56925f490cf24b6379fb3613'
                                    );


                                    $param1 = json_encode($params1);

                                    $ch = curl_init();

                                    curl_setopt($ch, CURLOPT_URL, "https://www.dareboost.com/api/0.1/analysis/report");
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $param1);
                                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                        'Content-Type: application/json',
                                        'Content-Length: ' . strlen($param1))
                                    );
                                    $report = curl_exec($ch);
                                    curl_close($ch);
                                    $report = json_decode($report, true);
                                } while ($report['status'] != 200);


                                if ($report['status'] == 200) {
                                    $report_data['user_id'] = $user_id;
                                    $report_data['report_id'] = $result1['reportId'];
//                                $report_data['report_id'] = '56925f490cf24b6379fb3613';
                                    $report_data['token'] = '56b0e1de0cf2409e7760f005';
                                    $report_data['website_url'] = $report['report']['url'];
                                    $report_data['weight'] = $report['report']['summary']['weight'] / 1000;
                                    $report_data['load_time'] = $report['report']['summary']['loadTime'] / 1000;
                                    $this->analyze_model->insert_analyze_data($report_data);
                                    $this->session->unset_userdata('result1');
                                }
                            }

                            $this->load->view(get_template_directory() . 'analyze_new', $report);
                            //$report = json_decode($report, true);
                        } else {
                            $this->load->view(get_template_directory() . 'analyze_new', $data);
                        }
                    }
                }
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function get_google_page_speed_result($url) {

        $params = array(
            "token" => "56b0e1de0cf2409e7760f005",
            "url" => $url
        );

        $param = json_encode($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.dareboost.com/api/0.1/analysis/launch");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($param))
        );
        $results = curl_exec($ch);


        $result = json_decode($results, true);

        return $result;
    }

    public function ajax_analyze_data() {
        // die($_POST['url']);
        $results = '';
        $report = array();
        $url = $this->input->post('url');
        $user_id = $this->ci_auth->get_user_id();
        $monitor = $this->session->userdata('monitorOnPageIssues');
        $dareboost_report = array();
        $dareboost_report = (array) $this->analyze_model->checkDomainReport($this->session->userdata('domainUrl'));

        if ($monitor == 1) {

            $weekbeforedate =  date('Y-m-d', strtotime('-2 week', time()));
            if (count($dareboost_report) > 0 && strtotime($dareboost_report[0]->created) > strtotime($weekbeforedate) ) {
                $report = $dareboost_report[0];
            } else {
                if ($results == '') {
                    $params = array(
                        "token" => "56b0e1de0cf2409e7760f005",
                        "url" => $url
                    );

                    $param = json_encode($params);

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, "https://www.dareboost.com/api/0.1/analysis/launch");
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($param))
                    );
                    $results = curl_exec($ch);
                    curl_close($ch);
                }
                $result = json_decode($results, true);
                if (!empty($result)) {
                    $report = $this->fetchReportData($result['reportId']);
                    if ($report['status'] == 200) {
                        $query = "delete from `analyze` where website_url='".$this->session->userdata('domainUrl')."'";
                        $this->db->query($query);

                        $report_data['user_id'] = $user_id;
                        $report_data['report_id'] = $result['reportId'];
                        $report_data['token'] = '56b0e1de0cf2409e7760f005';
                        $report_data['website_url'] = $report['report']['url'];
                        $report_data['weight'] = $report['report']['summary']['weight'] / 1000;
                        $report_data['load_time'] = $report['report']['summary']['loadTime'];
                        $report_data['score'] = $report['report']['summary']['score'];
                        $report_data['performance_timings'] = serialize($report['report']['performanceTimings']);
                        $this->analyze_model->insert_analyze_data($report_data);
                    }
                }
            }
        }
        if (!empty($report)) {
            $dareboost_report = (array) $this->analyze_model->checkDomainReport($this->session->userdata('domainUrl'));
              $report = $dareboost_report[0];  
            // if ($report['status'] == 200) {
                $score = $report->score;
                $performance_timing = $report->performance_timings;
                $load_time = $report->load_time;
                echo json_encode(array('msg' => 'success', 'score' => $score, 'performance_timing' => unserialize($performance_timing), 'load_time' => $load_time));
            // }
        } else {
            echo json_encode(array('msg' => 'error'));
        }
        die();
    }

    function fetchReportData($reportId) {

        $report = array();
        $count = 1;
        do {
            $params1 = array(
                "token" => "56b0e1de0cf2409e7760f005",
                "reportId" => $reportId
//                        "reportId" => '56925f490cf24b6379fb3613'
            );

            $param1 = json_encode($params1);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://www.dareboost.com/api/0.1/analysis/report");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($param1))
            );
            $report = curl_exec($ch);
            curl_close($ch);
            $report = json_decode($report, true);
            $count++;
       // } while ($report['status'] != 200 && $count<2);
         } while ($report['status'] != 200);
        // 
        return $report;
    }

    function getkeywordreport()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // logged in, not activated
            redirect('/auth/sendactivation/');
        } else {
            $userid    = $this->ci_auth->get_user_id();
            $domain_id = $this->input->post('domain_id');
            $data = array();
            $data['avg_position']    = $this->analyze_model->getAveragePosition($userid, $domain_id);
            $data['keyword_changes'] = $this->analyze_model->getKeywordChangeFromWeeks($userid, $domain_id);
            $data['position']        = $this->analyze_model->getKeywordPositionStats($userid, $domain_id);
            $data['total_keywords']  = $this->analyze_model->getTotalKeywords($userid, $domain_id);
            echo json_encode($data);die;
        }   
    }

}
