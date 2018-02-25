<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Heatmaps extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('analytics/heatmap_model');
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library('form_validation');
    }
    public function index()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // logged in, not activated
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domain_data = $this->session->get_userdata();
                $domain_id      = $domain_data['domainId'];
                //getting the first heatmap of domain
                $data['firstdomain'] = $this->heatmap_model->getFirstByDomainId($domain_id);

                $data['heatmaps'] = $this->heatmap_model->getAllByUserId($this->ci_auth->get_user_id(), $domain_id);
                $this->load->view(get_template_directory() . '/list_heatmaps', $data);
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }
    public function add()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // logged in, not activated
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                if ($_POST) {
                    $this->db->flush_cache();
                    $array              = array();
                    $domain_data = $this->session->get_userdata();
                    $domain_id      = $domain_data['domainId'];
                    $array['user_id']   = $this->ci_auth->get_user_id();
                    $array['domain_id']    = $domain_id;
                    $array['embed_url'] = $this->input->post('embed_url');
                    $array['page_name'] = $this->input->post('page_name');
                    $array['created']   = date('Y-m-d H:i:s');
                    $array['modified']  = date('Y-m-d H:i:s');
                    $this->db->insert('heatmaps', $array);
                    redirect(site_url('/heatmaps'));
                } else {
                    $this->load->view(get_template_directory() . '/add_heatmap');
                }
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }
    public function edit()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // logged in, not activated
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                if ($_POST) {
                    $domain_data = $this->session->get_userdata();
                    $domain_id      = $domain_data['domainId'];
                    $heatmap_id         = $_POST['id'];
                    $array['domain_id']    = $domain_id;
                    $array['page_name']    = $this->input->post('page_name');
                    $array['embed_url'] = $this->input->post('embed_url');
                    $array['modified']  = date('Y-m-d H:i:s');
                    $this->db->update('heatmaps', $array, array('id' => $heatmap_id));
                    redirect(site_url('/heatmaps'));
                } else {
                    $heatmap_id      = $this->uri->segment('3');
                    $data['heatmap'] = $this->heatmap_model->getById($heatmap_id);
                    $this->load->view(get_template_directory() . '/edit_heatmap', $data);
                }
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }
    public function show()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // logged in, not activated
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domain_data = $this->session->get_userdata();
                $domain_id      = $domain_data['domainId'];    
                //getting all the pages of domain
                $data['heatmaps'] = $this->heatmap_model->getAllByUserId($this->ci_auth->get_user_id(), $domain_id);
                $heatmap_id      = $this->uri->segment('2');
                if(!$heatmap_id) {
                    //getting the first id
                    $current = $this->heatmap_model->getFirstByDomainId($domain_id);
                    if($current){
                        $heatmap_id = $current['id'];
                    } else {
                        //redirect to add page
                        redirect(site_url('/heatmaps/add'));
                    }
                } 
                $data['current_id'] = $heatmap_id;    
                
                
                $data['heatmap'] = $this->heatmap_model->getById($heatmap_id);
                $this->load->view(get_template_directory() . '/show_heatmap', $data);
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }
    public function delete()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // logged in, not activated
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $heatmap_id = $this->uri->segment('3');
                $this->db->delete('heatmaps', array('id' => $heatmap_id));
                redirect(site_url('/heatmaps'));
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }
}
