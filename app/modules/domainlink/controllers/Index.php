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
class Index extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library('form_validation');
        $this->load->model('domainlink/linkbuildingdomain_model');
        $this->load->model('domainlink/linkpage_model');
        $this->load->model('domainlink/backlinkdomain_model');
        $this->load->model('domainlink/linkdomainindex_model');
        $this->load->model('domainlink/linkdomainregistrarinfo_model');
        $this->load->library('session');
        $this->load->library('whois');
        $this->load->helper('campaigns-io/functions');
    }

    public function adddomain()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data['domain']   = $this->input->post('domain');
                $data['user_id']  = $this->ci_auth->get_user_id();
                $data['owner']  = $this->input->post('owner');
                $data['created']  = date('Y-m-d H:i:s');
                $data['modified'] = date('Y-m-d H:i:s');

                $exist = $this->linkbuildingdomain_model->checkDomainExistByUserId($this->ci_auth->get_user_id(), $data['domain']);
                if (!$exist) {
                    $insert = $this->linkbuildingdomain_model->insert($data);
                    $this->session->set_flashdata('success_msg', "Domain added successfully");

                    //checking the index status 
                    $google_status = $this->checkIndexByGoogle($data['domain']);
                    $bing_status = $this->checkIndexByBing($data['domain']);
                    $arr = array();
                    $arr['link_domain_id'] = $insert;
                    $arr['bing'] = ($bing_status == 0)?0:1;
                    $arr['google'] = ($google_status == 0)?0:1;
                    $arr['created'] = date('Y-m-d H:i:s'); 
                    $this->linkdomainindex_model->insert($arr);

                    //checking registrar info
                    $domain_temp = strtr($data['domain'], array('www.'=>'','https://'=>'','http://'=>''));
                    $whois = $this->whois->LookupDomain($domain_temp);
                    $arr = array();
                    $arr['link_domain_id'] = $insert;
                    $arr['registrar_info'] = $whois;
                    $arr['created'] = date('Y-m-d H:i:s');
                    $this->db->insert('link_domain_registrar_info', $arr);


                } else {
                    //show error message
                    $this->session->set_flashdata('error_msg', "Domain already exist");
                }
                redirect(site_url('/listlinkdomain'));
            } else {
                $this->load->view(get_template_directory() . '/add_link_domain', $data);
            }
        }
    }

    public function listdomain()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            $user_id              = $this->ci_auth->get_user_id();
            $result               = $this->linkbuildingdomain_model->getDomainByUserId($user_id);
            $data['link_domains'] = $result;
            $this->load->view(get_template_directory() . '/list_link_domain', $data);
        }
    }

    public function deletedomain()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            $user_id   = $this->ci_auth->get_user_id();
            $domain_id = $this->uri->segment(2);
            $result    = $this->linkbuildingdomain_model->deleteDomain($user_id, $domain_id);
            $this->session->set_flashdata('success_msg', "Domain has been deleted successfully");
            redirect(site_url('/listlinkdomain'));

        }
    }

    public function addlinkpage()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                
                $data['link_domain_id']     = $this->input->post('link_domain_id');
                $data['link']               = $this->input->post('link');
                $data['backlink_domain_id'] = $this->input->post('backlink_domain_id');
                $data['keyword'] =            $this->input->post('keyword');
                $data['keyword_added_date'] =  $this->input->post('date');
                $data['position'] =  $this->input->post('position');
                $data['created']            = date('Y-m-d H:i:s');
                $data['modified']           = date('Y-m-d H:i:s');
                //check if the link belongs to domain
                $domainExist = $this->linkbuildingdomain_model->checkDomainExistByUserId($this->ci_auth->get_user_id(), $this->input->post('domain_id'));
                if (!preg_match("/" . preg_quote($domainExist['domain'], '/') . "/i", $data['link'])) {
                    //error link does not belong to this domain
                    $this->session->set_flashdata('error_msg', " Page url does'nt match domain url");
                } else {

                    $exist = $this->linkpage_model->checkBackLinkExist($data['link_domain_id'], $data['backlink_domain_id']);
                    if ($exist) {
                        //error link already exist
                        $this->session->set_flashdata('error_msg', "Backlink already exist for this domain");
                    } else {
                        $result               = $this->linkpage_model->insert($data);
                        $data['link_domains'] = $result;
                        $this->session->set_flashdata('success_msg', "Page url added successfully");
                        redirect(site_url('/listlinkpage/' . $data['link_domain_id']));
                    }
                }
                redirect(site_url('/listlinkpage/' . $data['link_domain_id']));
            } else {

                $data['domains'] = $this->linkbuildingdomain_model->getDomainByUserId($this->ci_auth->get_user_id());
                $this->load->view(get_template_directory() . '/add_link_page', $data);
            }
        }
    }

    public function editlinkpage()
    {

        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $data['link_domain_id']     = $this->input->post('link_domain_id');
                $data['link']               = $this->input->post('link');
                $data['backlink_domain_id'] = $this->input->post('backlink_domain_id');
                $data['keyword'] =            $this->input->post('keyword');
                $data['keyword_added_date'] =  $this->input->post('date');
                $data['position'] =  $this->input->post('position');
                $data['created']            = date('Y-m-d H:i:s');
                $data['modified']           = date('Y-m-d H:i:s');
                //check if the link belongs to domain
                $domainExist = $this->linkbuildingdomain_model->checkDomainExistByUserId($this->ci_auth->get_user_id(), $this->input->post('domain_id'));
                if (!preg_match("/" . preg_quote($domainExist['domain'], '/') . "/i", $data['link'])) {
                    //error link does not belong to this domain
                    $this->session->set_flashdata('error_msg', " Page url does'nt match domain url");
                } else {
                        $result     = $this->db->update('link_page', $data, array('id'=>$this->input->post('page_id')));
                        $this->session->set_flashdata('success_msg', "Page updated added successfully");
                        redirect(site_url('/listlinkpage/' . $data['link_domain_id']));
                }
                redirect(site_url('/listlinkpage/' . $data['link_domain_id']));
            } else {
                $domainId = $this->uri->segment('2');
                $pageId = $this->uri->segment('3');
                $data['domains'] = $this->linkbuildingdomain_model->getDomainByUserId($this->ci_auth->get_user_id());
                $data['page'] = $this->linkpage_model->getLinkPageByPageId($pageId);
                $data['domainId'] = $domainId;
                $data['pageId'] = $pageId;

                $this->load->view(get_template_directory() . '/edit_link_page', $data);
            }
        }
        
    }

    public function editdomain()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $id = $this->input->post('domain_id');
                $data['domain']   = $this->input->post('domain');
                $data['user_id']  = $this->ci_auth->get_user_id();
                $data['owner']  = $this->input->post('owner');
                $data['modified'] = date('Y-m-d H:i:s');
                $this->db->update('link_building_domain', $data, array('id'=>$id));
                if ($id) {
                    $this->session->set_flashdata('success_msg', "Domain updated successfully");
                
                    //checking the index status 
                    $google_status = $this->checkIndexByGoogle($data['domain']);
                    $bing_status = $this->checkIndexByBing($data['domain']);
                    $arr = array();
                    
                    $arr['bing'] = ($bing_status == 0)?0:1;
                    $arr['google'] = ($google_status == 0)?0:1;
                    $arr['created'] = date('Y-m-d H:i:s'); 
                    $this->db->update('link_domain_index',$arr, array('link_domain_id'=>$id));

                    //checking registrar info
                    $domain_temp = strtr($data['domain'], array('www.'=>'','https://'=>'','http://'=>''));
                    $whois = $this->whois->LookupDomain($domain_temp);
                    $arr = array();
                    $arr['registrar_info'] = $whois;
                    $arr['created'] = date('Y-m-d H:i:s');
                    $this->db->update('link_domain_registrar_info', $arr, array('link_domain_id'=>$id));


                } else {
                    //show error message
                    $this->session->set_flashdata('error_msg', "Domain already exist");
                }
                redirect(site_url('/listlinkdomain'));
            } else {
                $data['domain_id'] = $this->uri->segment('2');
                $domain = $this->linkbuildingdomain_model->getDomainInfoByDomainId($data['domain_id']);
                $data['domain'] = $domain;
                $this->load->view(get_template_directory() . '/edit_link_domain', $data);
            }
        }
    }

    public function listlinkpage()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            $domain_id = $this->uri->segment('2');
            $result    = $this->linkpage_model->getLinkPageByDomainId($domain_id);

            $data['link_pages'] = $result;
            $this->load->view(get_template_directory() . '/list_link_page', $data);
        }
    }

    public function deletelinkpage()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {

            $link_domain_id = $this->uri->segment('2');
            $link_page_id   = $this->uri->segment('3');
            $result         = $this->linkpage_model->delete($link_domain_id, $link_page_id);
            $this->session->set_flashdata('success_msg', "Page url deleted successfully");
            redirect(site_url('/listlinkpage/' . $link_domain_id));
        }
    }

    public function addbacklinkdomain()
    {
        $data['domain']  = $this->input->post('backlink_domain');
        $data['user_id'] = $this->ci_auth->get_user_id();
        $data['created'] = date('Y-m-d H:i:s');
        $res             = $this->backlinkdomain_model->insert($data);
        $return          = array();
        if ($res == true) {
            $return['status'] = true;
        } else {
            $return['status'] = false;
            $return['msg']    = 'Client has already been added';
        }
        echo json_encode($return);die;
    }

    public function getbacklinkdomains()
    {
        $link_domain_id = $this->input->post('backlink_domain');
        $user_id        = $this->ci_auth->get_user_id();
        //get all backlink domain not exist in link page
        $client_domains = $this->backlinkdomain_model->getbacklinkdomains($link_domain_id, $user_id);
        if ($client_domains) {
            $return['status']  = true;
            $return['payload'] = $client_domains;
        } else {
            $return['status'] = false;
        }
        echo json_encode($return);die;

    }

    private function checkIndexByGoogle($url)
    {
        $url = 'http://webcache.googleusercontent.com/search?q=cache:' . urlencode($url);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Chrome 10');
        $res = curl_exec($ch);


        if (!curl_exec($ch)) {
            // var_dump('failed');
            return 0;
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($code == 200) {
            return 1;
        }else{
            return 0;
        }

    }

    private function checkIndexByBing($uri)
    {
        $uri  = trim(str_ireplace('http://', '', $uri));
        $uri  = trim(str_ireplace('http', '', $uri));
        $url  = 'http://www.bing.com/search?q=site%3A' . urlencode($uri) . '&go=&qs=n&sk=&form=QBLH&mkt=en-WW';
        $data = $this->file_get_contents_curl($url);
        if (strpos($data, 'sb_count') !== false) {
            return 1;
        } else {
            return 0;
        }
    }

    private function file_get_contents_curl($url, $referer = "", $ua = "Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.7 (KHTML, like Gecko) Ubuntu/10.04 Chromium/7.0.514.0 Chrome/7.0.514.0 Safari/534.7")
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Set curl to return the data instead of printing it to the browser.
        if ($referer != "") {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        } else {
            curl_setopt($ch, CURLOPT_REFERER, $url);
        }
        //curl_setopt($ch, CURLOPT_URL, $url);
        if ($ua != "") {
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        } else {
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    private function between($string, $start, $end)
    {
        $string = " " . $string;
        $ini    = strpos($string, $start);

        if ($ini == 0) {
            return "";
        }

        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public function getregistrarinfo()
    {
        $domainId = $this->input->post('domainId');
        $info = $this->linkdomainregistrarinfo_model->getInfoByDomainId($domainId);
        if($info) {
            $return['status'] = true;
            $return['payload'] = $info;
        } else {
            $return['status'] = false;
        }
        echo json_encode($return);die;

    }

}
