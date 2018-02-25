<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Settings extends CIUIS_Controller
{
    public function edit($settingname)
    {
        $data['title'] = 'Settings';
        $data['settings'] = $this->Settings_Model->get_settings($settingname);
        $data['accounts'] = $this->Accounts_Model->get_all_accounts();

        $settings = $this->Settings_Model->get_settings($settingname);
        if (isset($data['settings']['settingname'])) {
            if (isset($_POST) && count($_POST) > 0) {
                $config['upload_path'] = './uploads/ciuis_settings/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);
                $this->upload->do_upload('logo');
                $data_upload_files = $this->upload->data();
                if ($settings['logo'] == null) {
                    $image_data = $this->upload->data();
                } else {
                    $image_data['file_name'] = $this->input->post('logo');
                }
                $params = array(
                    // Company Settings
                    'crm_name' => $this->input->post('crm_name'),
                    'company_id' => $_SESSION['company_id'],
                    'company' => $this->input->post('company'),
                    'email' => $this->input->post('email'),
                    'city' => $this->input->post('city'),
                    'town' => $this->input->post('town'),
                    'state' => $this->input->post('state'),
                    'countryid' => $this->input->post('countryid'),
                    'postcode' => $this->input->post('postcode'),
                    'phone' => $this->input->post('phone'),
                    'fax' => $this->input->post('fax'),
                    'vatnumber' => $this->input->post('vatnumber'),
                    'taxoffice' => $this->input->post('taxoffice'),

                    //Financial Settings
                    'currencyid' => $this->input->post('currencyid'),
                    'unitseparator' => $this->input->post('unitseparator'),
                    'termtitle' => $this->input->post('termtitle'),
                    'termdescription' => $this->input->post('termdescription'),
                    'currencyposition' => $this->input->post('currencyposition'),

                    // Date Time Format Settings
                    'dateformat' => $this->input->post('dateformat'),
                    'languageid' => $this->input->post('languageid'),
                    'default_timezone' => $this->input->post('default_timezone'),

                    // E-Mail Settings
                    'smtphost' => $this->input->post('smtphost'),
                    'smtpport' => $this->input->post('smtpport'),
                    'emailcharset' => $this->input->post('emailcharset'),
                    'smtpusername' => $this->input->post('smtpusername'),
                    'smtppassoword' => $this->input->post('smtppassoword'),
                    'sendermail' => $this->input->post('sendermail'),

                    //Other Settings
                    'accepted_files_formats' => $this->input->post('accepted_files_formats'),
                    'allowed_ip_adresses' => $this->input->post('allowed_ip_adresses'),
                    'pushState' => $this->input->post('pushState'),
                    'voicenotification' => $this->input->post('voicenotification'),
                    'logo' => $image_data['file_name'],

                    'whmcs_url' => $this->input->post('whmcs_url'),
                    'whmcs_identifier' => $this->input->post('whmcs_identifier'),
                    'whmcs_secret' => $this->input->post('whmcs_secret'),
'whmcsholdingaccount' => $this->input->post('whmcsholdingaccount'),
                    //Paypal Settings
                    'paypalenable' => $this->input->post('paypalenable'),
                    'paypalemail' => $this->input->post('paypalemail'),
                    'paypalsandbox' => $this->input->post('paypalsandbox'),
                    'paypalcurrency' => $this->input->post('paypalcurrency'),

                );
                $this->session->set_flashdata('ntf1', '<b>' . lang('settingsupdated') . '</b>');
                $this->Settings_Model->update_settings($settingname, $params);
                redirect('settings/edit/ciuis');
            } else {
                if ($this->session->userdata('admin')) {
                    $data['settings'] = $this->Settings_Model->get_settings($settingname);
                    $data['currencies'] = $this->Settings_Model->get_currencies();
                    $data['languages'] = $this->Settings_Model->get_languages();
                    $data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
                    $data['newnotification'] = $this->Notifications_Model->newnotification();
                    $data['readnotification'] = $this->Notifications_Model->readnotification();
                    $data['notifications'] = $this->Notifications_Model->get_all_notifications();
                    $data['logs'] = $this->Logs_Model->panel_last_logs();
                    $data['events'] = $this->Events_Model->get_all_events();
                    $data['settings'] = $this->Settings_Model->get_settings_ciuis();
                    $data['countries'] = $this->db->order_by("id", "asc")->get('countries')->result_array();
                    $this->load->view(get_template_directory() . '/crm/settings/edit', $data);
                }
            }
        } else {
            $this->session->set_flashdata('ntf4', 'A problem?');
        }
    }

    public function removelogo($settingname)
    {
        $settings = $this->Settings_Model->get_settings($settingname);
        if (isset($settings['settingname'])) {
            $this->Settings_Model->delete_logo($settingname);
            redirect('settings/edit/' . $settings['settingname'] . '');
        } else {
            show_error('The staff you are trying to delete does not exist.');
        }
    }
    public function addcurrency()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'name' => $_POST['name'],
                'symbol' => $_POST['symbol'],
                'company_id' => $_SESSION['company_id'],
            );
            $this->db->insert('currencies', $params);
            $data['insert_id'] = $this->db->insert_id();
            return json_encode($data);
        }
    }
    public function addlanguage()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'langcode' => $_POST['langcode'],
                'name' => $_POST['name'],
                'foldername' => $_POST['foldername'],
                'company_id' => $_SESSION['company_id'],
            );
            $this->db->insert('languages', $params);
            $data['insert_id'] = $this->db->insert_id();
            return json_encode($data);
        }
    }
}
