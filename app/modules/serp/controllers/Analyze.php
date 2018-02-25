<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Analyze extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library('form_validation');
        $this->load->library('my_form_validation');
        $this->load->model('auth/user_model');
        $this->load->model('auth/analyze_model');
    }

    public function index()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $user_id = $this->ci_auth->get_user_id();

                $data['errors']    = $this->session->flashdata('errors');
                $data['message']   = $this->session->flashdata('message');
                $data['success']   = $this->session->flashdata('success');
                $user_profile      = $this->user_model->get_user($user_id);
                $data['profile']   = $user_profile[0];
                $data['seo_title'] = 'Analyze';
                $this->load->view(get_template_directory() . 'analyze', $data);
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function get_google_page_speed_result()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $user_id = $this->ci_auth->get_user_id();

                $data['errors']    = $this->session->flashdata('errors');
                $data['message']   = $this->session->flashdata('message');
                $data['success']   = $this->session->flashdata('success');
                $user_profile      = $this->user_model->get_user($user_id);
                $data['profile']   = $user_profile[0];
                $data['seo_title'] = 'Analyze Report';

                $results = '';
                $myKEY   = "AIzaSyAQMHjjRGo7jEgyF6JzI9wUvUEhonV2TXA";
                $url     = $this->input->post('ana_url');
                $url_req = 'https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=' . $url . '&key=' . $myKEY;
                if (function_exists('file_get_contents')) {
                    $results = @file_get_contents($url_req);
                }
                if ($results == '') {
                    $ch      = curl_init();
                    $timeout = 60;
                    curl_setopt($ch, CURLOPT_URL, $url_req);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    $results = curl_exec($ch);
                    curl_close($ch);
                }

                $this->session->set_userdata('reports', json_decode($results, true));
                redirect(site_url('auth/analyze'));

            } else {
                redirect(site_url('/admin/login'));
            }
        }

    }

    public function getoverallvisibility()
    {

        $data        = array();
        $domain_data = $this->session->get_userdata();
        if ($this->ci_auth->canDo('login_to_frontend') && !empty($domain_data)) {
            if (!empty($domain_data)) {
                $domain_id          = $domain_data['domainId'];
                $searchengine       = $_POST['searchengine'];
                $keyword            = $_POST['keyword'];
                $date               = $_POST['date'];
                $result             = $this->analyze_model->serpoverview($domain_id, $searchengine, $keyword, $date);
                $return['type']     = 'success';
                $return['series']   = $result['series'];
                $return['category'] = $result['category'];
                echo json_encode($return);die;

            } else {
                redirect(site_url('auth/add_project'));
            }
        } else {
            redirect(site_url('auth/login'));
        }

    }

}
