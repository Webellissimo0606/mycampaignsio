<?php




// TODO: Delete file, doesn't used.




if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Domain extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->model('auth/analyze_model');
        $this->load->model('auth/groups_model');
    }

    public function index() {

        redirect( base_url('domains') );
        exit;

        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {

                $domains = $this->analyze_model->getAllDomainsByUserId($this->ci_auth->get_user_id());
                $return  = $domains;
                if ($domains) {
                    foreach ($domains as $key => $domain) {

                        $keywordexist                 = $this->analyze_model->checkKeywordExistByDomainId($domain['domain_id']);
                        $return[$key]['keywordexist'] = $keywordexist;

                        $groups = $this->groups_model->getGroupsByDomainId($domain['domain_id']);
                        if ($groups) {
                            $groupinfo = array();
                            foreach ($groups as $group) {
                                $groupinfo[] = $this->groups_model->getGroupByGroupId($group);
                            }
                            $return[$key]['groups'] = $groupinfo;
                        } else {
                            $return[$key]['groups'] = 'No group';
                        }

                        $users = $this->analyze_model->getSubusersByDomainIdAndParentId($domain['domain_id'], $this->ci_auth->get_user_id());
                        if ($users) {
                            $info = array();
                            foreach ($users as $user) {
                                $info[] = $this->analyze_model->getuserprofile($user);
                            }
                            $return[$key]['users'] = $info;
                        } else {
                            $return[$key]['users'] = 'Own';
                        }
                    }
                }

                $data['domains'] = $return;

                $this->load->view(get_template_directory() . 'domain', $data);
            } else {
                redirect(site_url('/admin/login'));
            }
        }

    }
}
