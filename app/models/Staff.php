<?php
defined('BASEPATH')or exit('No direct script access allowed');
class Staff extends CIUIS_Controller
{
    public function index()
    {
        $data[ 'title' ] = 'Staff';
        $this->breadcrumb->clear();
        $this->breadcrumb->add_crumb('Dashboard', 'panel');
        $this->breadcrumb->add_crumb('Staff', 'staff');
        $this->breadcrumb->add_crumb('All Sataff');
        $data[ 'title' ] = 'Staff';
        $data[ 'staff' ] = $this->Staff_Model->get_all_staff();
        $data[ 'tbs' ] = $this->db->count_all('notifications', array( 'markread' => ('0') ));
        $data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
        $data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
        $data[ 'events' ] = $this->Events_Model->get_all_events();
        $data[ 'overdueinvoices' ] = $this->Invoices_Model->overdueinvoices();
        $data[ 'todaypayments' ] = $this->Payments_Model->todaypayments();
        $data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
        $data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
        $data[ 'logs' ] = $this->Logs_Model->get_all_logs();
        $data[ 'departments' ] = $this->Settings_Model->get_departments();
        $this->load->view(get_template_directory() . '/crm/staff/index', $data);
    }

    public function add()
    {
        if ($this->Staff_Model->isDuplicate($this->input->post('email'))) {
            $this->session->set_flashdata('ntf4', lang('staffemailalreadyexists'));
            redirect('staff/add/');
        } else {
            $data[ 'title' ] = 'Add Staff';
            $this->breadcrumb->clear();
            $this->breadcrumb->add_crumb('Dashboard', 'panel');
            $this->breadcrumb->add_crumb('Staff', '../staff');
            $this->breadcrumb->add_crumb('All Sataff');
            if (isset($_POST) && count($_POST) > 0) {
                //Staff Image Upload
                $config[ 'upload_path' ] = './uploads/staffavatars/';
                $config[ 'allowed_types' ] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);
                $this->upload->do_upload('staffavatar');
                $data_upload_files = $this->upload->data();
                $image_data = $this->upload->data();
                $params = array(
                    'company_id' => $_SESSION['company_id'],
                    'language' => $this->input->post('language'),
                    'staffname' => $this->input->post('staffname'),
                    /*'staffavatar' => $image_data[ 'file_name' ],*/
                    'staffavatar' => $_FILES[ 'staffavatar' ]['name'],
                    'departmentid' => $this->input->post('departmentid'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'email' => $this->input->post('email'),
                    'password' => md5($this->input->post('password')),
                    'birthday' => $this->input->post('birthday'),
                    'root' => $this->input->post('root'),
                    'admin' => $this->input->post('admin'),
                    'staffmember' => $this->input->post('staffmember'),
                    'inactive' => $this->input->post('inactive'),
                    'permissionid' => ($this->input->post('permissionid'))?$this->input->post('permissionid'):null,
                );
                $staff_id = $this->Staff_Model->add_staff($params);
                // SEND EMAIL SETTINGS
                $setconfig = $this->Settings_Model->get_settings_ciuis();
                $this->load->library('email');
                $config = array();
                $config[ 'protocol' ] = 'smtp';
                $config[ 'smtp_host' ] = $setconfig[ 'smtphost' ];
                $config[ 'smtp_user' ] = $setconfig[ 'smtpusername' ];
                $config[ 'smtp_pass' ] = $setconfig[ 'smtppassoword' ];
                $config[ 'smtp_port' ] = $setconfig[ 'smtpport' ];
                $sender = $setconfig[ 'sendermail' ];
                $data = array(
                    'name' => $this->session->userdata('staffname'),
                    'password' => $this->input->post('password'),
                    'email' => $this->input->post('email'),
                    'loginlink' => '' . base_url('login') . ''
                );
                $body = $this->load->view(get_template_directory() . '/crm/email/accountinfo.php', $data, true);
                $this->email->initialize($config);
                $this->email->set_newline("\r\n");
                $this->email->set_mailtype("html");
                $this->email->from($sender); // change it to yours
                $this->email->to($this->input->post('email')); // change it to yours
                $this->email->subject('Your Login Informations');
                $this->email->message($body);
                
                /////////////
                if ($this->email->send()) {
                    $this->session->set_flashdata('ntf1', '' . $message = sprintf(lang('addedstaff'), $this->input->post('staffname')) . '');
                    redirect('staff/index');
                } else {
                    $this->session->set_flashdata('ntf3', '' . $message = sprintf(lang('addedstaffbut'), $this->input->post('staffname')) . '');
                    redirect('staff/index');
                }
            } else {
                $data[ 'tbs' ] = $this->db->count_all('notifications', array( 'markread' => ('0') ));
                $data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
                $data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
                $data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
                $data[ 'events' ] = $this->Events_Model->get_all_events();
                $data[ 'overdueinvoices' ] = $this->Invoices_Model->overdueinvoices();
                $data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
                $data[ 'languages' ] = $this->Settings_Model->get_languages();
                $data[ 'departments' ] = $this->Settings_Model->get_departments();
                $this->load->view(get_template_directory() . '/crm/staff/add', $data);
            }
        }
    }

    public function staffmember($id)
    {
        $data[ 'title' ] = 'Staff Profile';
        $data[ 'ycr' ] = $this->Report_Model->ycr();
        $staff = $this->Staff_Model->get_staff($id);
        $data[ 'logs' ] = $this->Logs_Model->staffmember_log();
        $data[ 'staffsalesgraph' ] = $this->Staff_Model->staffsalesgraph($id);
        $data[ 'staff' ] = $this->Staff_Model->get_staff($id);
        $data[ 'havecustomers' ] = $this->Staff_Model->staffmember_customer($id);
        $data[ 'havetickets' ] = $this->Staff_Model->staffmember_tickets($id);
        $data[ 'tbs' ] = $this->db->count_all('notifications', array( 'markread' => ('0') ));
        $data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
        $data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
        $data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
        $data[ 'events' ] = $this->Events_Model->get_all_events();
        $data[ 'overdueinvoices' ] = $this->Invoices_Model->overdueinvoices();
        $data[ 'todaypayments' ] = $this->Payments_Model->todaypayments();
        $data[ 'staffmembersales' ] = $this->Staff_Model->staffmembersales($id);
        $data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
        $data[ 'invoices' ] = $this->db->get_where('invoices', array( 'staffid' => $id ))->result_array();
        $data[ 'tickets' ] = $this->db->get_where('tickets', array( 'staffid' => $id ))->result_array();
        $this->load->view(get_template_directory() . '/crm/staff/staffmember', $data);
    }

    public function edit($id)
    {
        $data[ 'title' ] = 'Edit Staff';
        $staff = $this->Staff_Model->get_staff($id);
        if (isset($staff[ 'id' ])) {
            if (isset($_POST) && count($_POST) > 0) {
                $config[ 'upload_path' ] = './uploads/staffavatars/';
                $config[ 'allowed_types' ] = 'gif|jpg|png|jpeg';
                $this->load->helper('form');
                $this->load->library('upload', $config);
                $this->upload->do_upload('staffavatar');
                $data_upload_files = $this->upload->data();
                /*print_r($_FILES[ 'staffavatar' ]['name']);
                die();*/
                if ($staff[ 'staffavatar' ] == null) {
                    $image_data = $this->upload->data();
                } else {
                    $image_data[ 'file_name' ] = $this->input->post('staffavatar');
                }
                $params = array(
                    'language' => $this->input->post('language'),
                    'staffname' => $this->input->post('staffname'),
                    /*'staffavatar' => $image_data[ 'file_name' ],*/
                    'staffavatar' => $_FILES[ 'staffavatar' ]['name'],
                    'departmentid' => $this->input->post('departmentid'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'email' => $this->input->post('email'),
                    'birthday' => $this->input->post('birthday'),
                    'root' => $this->input->post('root'),
                    'admin' => $this->input->post('admin'),
                    'staffmember' => $this->input->post('staffmember'),
                    'inactive' => $this->input->post('inactive'),
                );
                $this->Staff_Model->update_staff($id, $params);
                redirect('staff/index');
            } else {
                $data[ 'tbs' ] = $this->db->count_all('notifications', array( 'markread' => ('0') ));
                $data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
                $data[ 'readnotification' ] = $this->Notifications_Model->readnotification();
                $data[ 'notifications' ] = $this->Notifications_Model->get_all_notifications();
                $data[ 'events' ] = $this->Events_Model->get_all_events();
                $data[ 'overdueinvoices' ] = $this->Invoices_Model->overdueinvoices();
                $data[ 'staff' ] = $this->Staff_Model->get_staff($id);
                $data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
                $data[ 'languages' ] = $this->Settings_Model->get_languages();
                $data[ 'departments' ] = $this->Settings_Model->get_departments();
                $this->load->view(get_template_directory() . '/crm/staff/edit', $data);
            }
        } else {
            show_error('Eror');
        }
    }

    public function remove($id)
    {
        $staff = $this->Staff_Model->get_staff($id);
        // check if the staff exists before trying to delete it
        if (isset($staff[ 'id' ])) {
            $this->Staff_Model->delete_staff($id);
            redirect('staff/index');
        } else {
            show_error('The staff you are trying to delete does not exist.');
        }
    }

    public function removestaffavatar($id)
    {
        $staff = $this->Staff_Model->get_staff($id);
        if (isset($staff[ 'id' ])) {
            $this->Staff_Model->delete_avatar($id);
            redirect('staff/edit/' . $staff[ 'id' ] . '');
        } else {
            show_error('The staff you are trying to delete does not exist.');
        }
    }

    public function adddepartment()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'company_id' => $_SESSION['company_id'],
                'name' => $this->input->post('name'),
            );
            $expensecategory_id = $this->Settings_Model->add_department($params);
            redirect('staff/index');
        } else {
            redirect('staff/index');
        }
    }
    public function updatedepartment($id)
    {
        $departments = $this->Settings_Model->get_department($id);
        if (isset($departments[ 'id' ])) {
            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'name' => $this->input->post('name'),
                );
                $this->session->set_flashdata('ntf1', '<span><b>'.lang('departmentupdated').'</b></span>');
                $this->Settings_Model->update_department($id, $params);
                redirect('staff/index/');
            }
        } else {
            show_error('The products you are trying to edit does not exist.');
        }
    }
    public function removedepartment($id)
    {
        $departments = $this->Settings_Model->get_department($id);
        if (isset($departments[ 'id' ])) {
            $this->Settings_Model->delete_department($id);
            redirect('staff/index');
        } else {
            show_error('The staff you are trying to delete does not exist.');
        }
    }

    public function changestaffpassword($id)
    {
        $staff = $this->Staff_Model->get_staff($id);
        if (isset($staff[ 'id' ])) {
            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'password' => md5($this->input->post('staffnewpassword')),
                );
                // SEND EMAIL SETTINGS
                $setconfig = $this->Settings_Model->get_settings_ciuis();
                $this->load->library('email');
                $config = array();
                $config[ 'protocol' ] = 'smtp';
                $config[ 'smtp_host' ] = $setconfig[ 'smtphost' ];
                $config[ 'smtp_user' ] = $setconfig[ 'smtpusername' ];
                $config[ 'smtp_pass' ] = $setconfig[ 'smtppassoword' ];
                $config[ 'smtp_port' ] = $setconfig[ 'smtpport' ];
                $sender = $setconfig[ 'sendermail' ];
                $data = array(
                    'name' => $this->session->userdata('staffname'),
                    'password' => $this->input->post('staffnewpassword'),
                    'email' => $staff[ 'email' ],
                    'loginlink' => '' . base_url('login') . ''
                );
                $body = $this->load->view(get_template_directory() . '/crm/email/passwordchanged.php', $data, true);
                $this->email->initialize($config);
                $this->email->set_newline("\r\n");
                $this->email->set_mailtype("html");
                $this->email->from($sender); // change it to yours
                $this->email->to($staff[ 'email' ]); // change it to yours
                $this->email->subject('Your Password Changed');
                $this->email->message($body);
                if ($this->email->send()) {
                    $staffname1 = $this->session->staffname;
                    $staffname2 = $staff[ 'staffname' ];
                    $loggedinuserid = $this->session->logged_in_staff_id;
                    $this->db->insert('logs', array(
                    'date' => date('Y-m-d H:i:s'),
                    'detail' => ('' . $message = sprintf(lang('changedstaffpassword'), $staffname1, $staffname2) . ''),
                    'staffid' => $loggedinuserid,
                ));
                    $this->Staff_Model->update_staff($id, $params);
                    $this->session->set_flashdata('ntf1', ' ' . $staff[ 'staffname' ] . ' ' . lang('passwordchanged') . '');
                    redirect('staff/edit/' . $staff[ 'id' ] . '');
                } else {
                    /////////////
                    //LOG
                    $staffname1 = $this->session->staffname;
                    $staffname2 = $staff[ 'staffname' ];
                    $loggedinuserid = $this->session->logged_in_staff_id;
                    $this->db->insert('logs', array(
                    'date' => date('Y-m-d H:i:s'),
                    'detail' => ('' . $message = sprintf(lang('changedstaffpassword'), $staffname1, $staffname2) . ''),
                    'staffid' => $loggedinuserid,
                ));
                    $this->Staff_Model->update_staff($id, $params);
                    $this->session->set_flashdata('ntf4', ' ' . $staff[ 'staffname' ] . ' ' . lang('passwordchangedbut') . '');
                    redirect('staff/edit/' . $staff[ 'id' ] . '');
                }
            } else {
                $data[ 'staff' ] = $this->Staff_Model->get_staff($id);
            }
        } else {
            show_error('The contacts you are trying to edit does not exist.');
        }
    }
}
