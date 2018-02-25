<?php
defined('BASEPATH') or exit('No direct script access allowed');
if( ! class_exists('CIUIS_Controller') ){
    require_once 'CIUIS_Controller.php';
}
class Accounts extends CIUIS_Controller
{
    public function index()
    {
        $data['accounts'] = $this->Accounts_Model->get_all_accounts();
        $data['title'] = lang('accounts');
        $data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
        $data['newnotification'] = $this->Notifications_Model->newnotification();
        $data['readnotification'] = $this->Notifications_Model->readnotification();
        $data['notifications'] = $this->Notifications_Model->get_all_notifications();
        $data['events'] = $this->Events_Model->get_all_events();
        $data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
        $data['todaypayments'] = $this->Payments_Model->todaypayments();
        $data['logs'] = $this->Logs_Model->panel_last_logs();
        $data['tht'] = $this->Report_Model->tht();
        $data['settings'] = $this->Settings_Model->get_settings_ciuis();
        $this->load->view(get_template_directory() . '/crm/accounts/index', $data);
    }

    public function add()
    {
        if (isset($_POST) && count($_POST) > 0) {
            foreach ($_POST as $key => $value) {
                if (empty($value)) {
                    $_POST[$key]=null;
                }
            }

            $params = array(
                'company_id' => $_SESSION['company_id'],
                'name' => $this->input->post('name'),
                'type' => $this->input->post('type'),
                'bankname' => $this->input->post('bankname'),
                'branchbank' => $this->input->post('branchbank'),
                'account' => $this->input->post('account'),
                'iban' => $this->input->post('iban'),
                'status' => $this->input->post('status')
            );

            $params['type'] = $params['type'] ? $params['type'] : 0;
            $params['account'] = $params['account'] ? $params['account'] : 0;
            $params['status'] = $params['status'] ? $params['status'] : 0;

            $this->session->set_flashdata('ntf1', '<b>' . lang('accountadded') . '</b>');

            $accountsid = $this->Accounts_Model->account_add($params);
            redirect('accounts/index');
        }
    }

    public function update($id)
    {
        $data['accounts'] = $this->Accounts_Model->get_accounts($id);
        if (isset($data['accounts']['id'])) {
            foreach ($_POST as $key => $value) {
                if (empty($value)) {
                    $_POST[$key]=null;
                }
            }

            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'name' => $this->input->post('name'),
                    'bankname' => $this->input->post('bankname'),
                    'branchbank' => $this->input->post('branchbank'),
                    'account' =>($this->input->post('account'))?$this->input->post('account'):0,
                    'iban' => $this->input->post('iban'),
                    'status' => $this->input->post('status'),
                );
                $this->Accounts_Model->updateaccount($id, $params);
                redirect('accounts/account/' . $id . '');
            } else {
                $this->load->view(get_template_directory() . '/crm/accounts/', $data);
            }
        } else {
            show_error('The expenses you are trying to edit does not exist.');
        }
    }

    public function account($id)
    {
        $data['title'] = lang('account');
        $accounts = $this->Accounts_Model->get_accounts($id);
        $data['payments'] = $this->db->select('*')->get_where('payments', array('accountid' => $id))->result_array();
        $data['accounts'] = $this->Accounts_Model->get_accounts($id);
        $data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
        $data['newnotification'] = $this->Notifications_Model->newnotification();
        $data['readnotification'] = $this->Notifications_Model->readnotification();
        $data['notifications'] = $this->Notifications_Model->get_all_notifications();
        $data['events'] = $this->Events_Model->get_all_events();
        $data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
        $data['todaypayments'] = $this->Payments_Model->todaypayments();
        $data['settings'] = $this->Settings_Model->get_settings_ciuis();
        $data['logs'] = $this->Logs_Model->get_all_logs();
        $this->load->view(get_template_directory() . '/crm/accounts/account', $data);
    }

    public function remove($id)
    {
        $accounts = $this->Accounts_Model->get_accounts($id);
        if (isset($accounts['id'])) {
            $this->Accounts_Model->delete_account($id);
            redirect('accounts/index');
        }
    }
}
