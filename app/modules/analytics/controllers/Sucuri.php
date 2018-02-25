<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sucuri extends CI_Controller
{
    public function securityscan()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $this->load->view(get_template_directory() . '/securityscan', $data);
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

}
