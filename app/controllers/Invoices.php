<?php
defined('BASEPATH') or exit('No direct script access allowed');

if( ! class_exists('CIUIS_Controller') ){
    require_once 'CIUIS_Controller.php';
}

class Invoices extends CIUIS_Controller
{
    public function index()
    {
        $data['title'] = lang('invoices');
        $this->load->library('breadcrumb');
        $this->breadcrumb->clear();
        $this->breadcrumb->add_crumb('Dashboard', 'panel');
        $this->breadcrumb->add_crumb('Invoices', 'invoices');
        $this->breadcrumb->add_crumb('Tüm Invoices');
        $data['akt'] = $this->Report_Model->akt();
        $data['oak'] = $this->Report_Model->oak();
        $data['off'] = $this->Report_Model->pff();
        $data['ycr'] = $this->Report_Model->ycr();
        $data['ofv'] = $this->Report_Model->ofv();
        $data['oft'] = $this->Report_Model->oft();
        $data['vgf'] = $this->Report_Model->vgf();
        $data['tfa'] = $this->Report_Model->tfa();
        $data['pfs'] = $this->Report_Model->pfs();
        $data['otf'] = $this->Report_Model->otf();
        $data['tef'] = $this->Report_Model->tef();
        $data['vdf'] = $this->Report_Model->vdf();
        $data['fam'] = $this->Report_Model->fam();
        $data['ofy'] = ($data['tfa'] > 0 ? number_format(($data['tef'] * 100) / $data['tfa']) : 0);
        $data['ofx'] = ($data['tfa'] > 0 ? number_format(($data['otf'] * 100) / $data['tfa']) : 0);
        $data['vgy'] = ($data['tfa'] > 0 ? number_format(($data['vdf'] * 100) / $data['tfa']) : 0);
        $data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
        $data['newnotification'] = $this->Notifications_Model->newnotification();
        $data['readnotification'] = $this->Notifications_Model->readnotification();
        $data['notifications'] = $this->Notifications_Model->get_all_notifications();
        $data['logs'] = $this->Logs_Model->panel_last_logs();
        $data['events'] = $this->Events_Model->get_all_events();
        $data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
        $data['todaypayments'] = $this->Payments_Model->todaypayments();
        $data['invoices'] = $this->Invoices_Model->get_all_invoices();
        $data['tum_products'] = $this->Products_Model->get_all_products();
        $data['all_customers'] = $this->Customers_Model->get_all_customers();
        $data['settings'] = $this->Settings_Model->get_settings_ciuis();
        $this->load->view(get_template_directory() . '/crm/invoices/index', $data);
    }

    public function listele_json()
    {
        $result = $invoice_bilgileri_json = $this->Invoices_Model->get_all_invoices();
        echo json_encode($result);
    }

    public function add()
    {
        $data['title'] = lang('newinvoice');
        $this->breadcrumb->clear();
        $this->breadcrumb->add_crumb('Dashboard', 'panel');
        $this->breadcrumb->add_crumb('Invoices', '../invoices');
        $this->breadcrumb->add_crumb('Invoice Add');
        $data['fu'] = $this->db->get_where('invoiceitems')->result_array();
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'company_id' => $_SESSION['company_id'],
                'no' => ($this->input->post('no'))? $this->input->post('no'):null,
                'series' => ($this->input->post('series'))? $this->input->post('series'):null,
                'customerid' => $this->input->post('customerid'),
                'orderid' =>($this->input->post('orderid'))? $this->input->post('orderid'):null,
                'staffid' => $this->input->post('staffid'),
                'datecreated' => ($this->input->post('datecreated'))? _pdate($this->input->post('datecreated')):null,
                'duedate' =>($this->input->post('duedate'))? _pdate($this->input->post('duedate')):null,
                'not' => $this->input->post('not'),
                'datesend' => _pdate($this->input->post('datesend')),
                'date_payment' => _pdate($this->input->post('date_payment')),
                'statusid' => $this->input->post('statusid'),
                'total_sub' => $this->input->post('total_sub'),
                'total_discount' => $this->input->post('total_discount'),
                'sub_discount' => $this->input->post('sub_discount'),
                'sub_discount_type' => $this->input->post('sub_discount_type'),
                'total_sub_discount' => $this->input->post('total_sub_discount'),
                'sub_discount_status' => $this->input->post('sub_discount_status'),
                'total_vat' => $this->input->post('total_vat'),
                'total' => $this->input->post('total'),
                'notetitle' => $this->input->post('notetitle'),
            );
            $invoices_id = $this->Invoices_Model->invoice_add($params);
            redirect('invoices/index');
        } else {
            $data['products'] = $this->Products_Model->get_all_products();
            $data['all_customers'] = $this->Customers_Model->get_all_customers();
            $data['all_accounts'] = $this->Accounts_Model->get_all_accounts();
            $data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
            $data['newnotification'] = $this->Notifications_Model->newnotification();
            $data['readnotification'] = $this->Notifications_Model->readnotification();
            $data['notifications'] = $this->Notifications_Model->get_all_notifications();
            $data['events'] = $this->Events_Model->get_all_events();
            $data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
            $data['todaypayments'] = $this->Payments_Model->todaypayments();
            $data['settings'] = $this->Settings_Model->get_settings_ciuis();
            $data['logs'] = $this->Logs_Model->panel_last_logs();
            $this->load->view(get_template_directory() . '/crm/invoices/add', $data);
        }
    }
    public function get_json_items()
    {
        $area_id = $this->input->post('area');
        $urunfiyat = $this->Products_Model->get_all_products();
        echo json_encode($urunfiyat);
    }

    public function edit($id)
    {
        $data['title'] = lang('updateinvoicetitle');
        $invoices = $this->Invoices_Model->get_invoices($id);
        $data['invoiceitems'] = $this->db->select('*,products.productname as name,invoiceitems.id as id ')->join('products', 'invoiceitems.in[product_id] = products.id', 'left')->get_where('invoiceitems', array('invoiceid' => $id))->result_array();
        $data['payments'] = $this->db->select('*,accounts.name as accountname,payments.id as id ')->join('accounts', 'payments.accountid = accounts.id', 'left')->get_where('payments', array('invoiceid' => $id))->result_array();
        $data['fatop'] = $this->Invoices_Model->get_invoice_productsi_art($id);
        $data['tadtu'] = $this->Invoices_Model->get_invoice_tahsil_edilen($id);
        if (isset($invoices['id'])) {
            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'no' => $this->input->post('no'),
                    'series' => $this->input->post('series'),
                    'customerid' => $this->input->post('customerid'),
                    'orderid' => $this->input->post('orderid'),
                    'staffid' => $this->input->post('staffid'),
                    'datecreated' => _pdate($this->input->post('datecreated')),
                    'duedate' => _pdate($this->input->post('duedate')),
                    'not' => $this->input->post('not'),
                    'datesend' => _pdate($this->input->post('datesend')),
                    'date_payment' => _pdate($this->input->post('date_payment')),
                    'statusid' => $this->input->post('statusid'),
                    'total_sub' => $this->input->post('total_sub'),
                    'total_discount' => $this->input->post('total_discount'),
                    'sub_discount' => $this->input->post('sub_discount'),
                    'sub_discount_type' => $this->input->post('sub_discount_type'),
                    'total_sub_discount' => $this->input->post('total_sub_discount'),
                    'sub_discount_status' => $this->input->post('sub_discount_status'),
                    'total_vat' => $this->input->post('total_vat'),
                    'total' => $this->input->post('total'),
                    'notetitle' => $this->input->post('notetitle'),
                );
                $this->session->set_flashdata('ntf1', 'Invoice ' . $id . ' Updated!');
                $this->Invoices_Model->update_invoices($id, $params);
                redirect('invoices/edit/' . $id . '');
            } else {
                $data['invoices'] = $this->Invoices_Model->get_invoices($id);
                $data['products'] = $this->Products_Model->get_all_products();
                $data['accounts'] = $this->Accounts_Model->get_all_accounts();
                $data['all_customers'] = $this->Customers_Model->get_all_customers();
                $data['all_accounts'] = $this->Accounts_Model->get_all_accounts();
                $data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
                $data['newnotification'] = $this->Notifications_Model->newnotification();
                $data['readnotification'] = $this->Notifications_Model->readnotification();
                $data['notifications'] = $this->Notifications_Model->get_all_notifications();
                $data['events'] = $this->Events_Model->get_all_events();
                $data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
                $data['todaypayments'] = $this->Payments_Model->todaypayments();
                $data['settings'] = $this->Settings_Model->get_settings_ciuis();
                $this->load->view(get_template_directory() . '/crm/invoices/edit', $data);
            }
        } else {
            $this->session->set_flashdata('ntf3', '' . $id . lang('invoiceediterror'));
        }
    }

    //function updateinvoiceitemsingle() {
    //	$this->Invoices_Model->updateinvoiceitemsingleinline( $column, $lastvalue, $id );
    //}

    public function invoice($id)
    {
        $data['title'] = lang('invoice');
        $this->breadcrumb->clear();
        $this->breadcrumb->add_crumb('Dashboard', 'panel');
        $this->breadcrumb->add_crumb('Invoices', 'invoices');
        $this->breadcrumb->add_crumb('Invoice Detayı');
        $data['notifications'] = $this->Notifications_Model->get_all_notifications();
        $data['newnotification'] = $this->Notifications_Model->newnotification();
        $data['readnotification'] = $this->Notifications_Model->readnotification();
        $data['invoices'] = $this->Invoices_Model->get_invoice_detail($id);
        $data['tbs'] = $this->db->count_all('notifications', array('markread' => ('0')));
        $data['newnotification'] = $this->Notifications_Model->newnotification();
        $data['readnotification'] = $this->Notifications_Model->readnotification();
        $data['notifications'] = $this->Notifications_Model->get_all_notifications();
        $data['events'] = $this->Events_Model->get_all_events();
        $data['overdueinvoices'] = $this->Invoices_Model->overdueinvoices();
        $data['todaypayments'] = $this->Payments_Model->todaypayments();
        $data['settings'] = $this->Settings_Model->get_settings_ciuis();
        $data['accounts'] = $this->Accounts_Model->get_all_accounts();
        $invoices = $this->Invoices_Model->get_invoices($id);
        $data['invoiceitems'] = $this->db->select('*,products.productname as name,invoiceitems.id as id ')->join('products', 'invoiceitems.in[product_id] = products.id', 'left')->get_where('invoiceitems', array('invoiceid' => $id))->result_array();
        $data['payments'] = $this->db->select('*,accounts.name as accountname,payments.id as id ')->join('accounts', 'payments.accountid = accounts.id', 'left')->get_where('payments', array('invoiceid' => $id))->result_array();
        $data['fatop'] = $this->Invoices_Model->get_invoice_productsi_art($id);
        $data['tadtu'] = $this->Invoices_Model->get_invoice_tahsil_edilen($id);
        $this->load->view(get_template_directory() . '/crm/invoices/invoice', $data);
    }

    public function status_1($id)
    {
        $invoices = $this->Invoices_Model->get_invoices($id);
        $invoices = $this->Invoices_Model->status_1($id);
        if (isset($invoices['id'])) {
            $this->Invoices_Model->status_1($id);
        }
        $this->session->set_flashdata('ntf3', '' . $id . lang('statuschanged'));
        redirect('invoices/index');
    }

    public function status_2($id)
    {
        $invoices = $this->Invoices_Model->get_invoices($id);
        $invoices = $this->Invoices_Model->status_2($id);
        if (isset($invoices['id'])) {
            $this->Invoices_Model->status_2($id);
        }
        $this->session->set_flashdata('ntf3', '' . $id . lang('statuschanged'));
        redirect('invoices/index');
    }

    public function status_3($id)
    {
        $invoices = $this->Invoices_Model->get_invoices($id);
        $invoices = $this->Invoices_Model->status_3($id);
        if (isset($invoices['id'])) {
            $this->Invoices_Model->status_3($id);
        }
        $this->session->set_flashdata('ntf3', '' . $id . lang('statuschanged'));
        redirect('invoices/index');
    }

    public function status_4($id)
    {
        $invoices = $this->Invoices_Model->get_invoices($id);
        $invoices = $this->Invoices_Model->status_4($id);
        if (isset($invoices['id'])) {
            $this->Invoices_Model->status_4($id);
        }
        $this->session->set_flashdata('ntf3', '' . $id . lang('statuschanged'));
        redirect('invoices/index');
    }

    public function status_5($id)
    {
        $invoices = $this->Invoices_Model->get_invoices($id);
        $invoices = $this->Invoices_Model->status_5($id);
        if (isset($invoices['id'])) {
            $this->Invoices_Model->status_5($id);
        }
        $this->session->set_flashdata('ntf3', '' . $id . lang('statuschanged'));
        redirect('invoices/index');
    }

    public function status_6($id)
    {
        $invoices = $this->Invoices_Model->get_invoices($id);
        $invoices = $this->Invoices_Model->status_6($id);
        if (isset($invoices['id'])) {
            $this->Invoices_Model->status_6($id);
        }
        $this->session->set_flashdata('ntf3', '' . $id . lang('statuschanged'));
        redirect('invoices/index');
    }

    public function pdf($id)
    {
        $ind = $this->Invoices_Model->get_invoice_detail($id);
        $data['title'] = '' . lang('invoiceprefix') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT) . '';
        $this->load->library('Pdf');
        $obj_pdf = new TCPDF('I', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
        $data['invoices'] = $this->Invoices_Model->get_invoice_detail($id);
        $data['settings'] = $this->Settings_Model->get_settings_ciuis();
        $data['invoiceitems'] = $this->db->select('*,products.productname as urun,invoiceitems.id as id ')->join('products', 'invoiceitems.in[product_id] = products.id', 'left')->get_where('invoiceitems', array('invoiceid' => $id))->result_array();
        $this->load->view(get_template_directory() . '/crm/invoices/invoice_pdf', $data);
    }

    public function print_($id)
    {
        $ind = $this->Invoices_Model->get_invoice_detail($id);
        $data['title'] = '' . lang('invoiceprefix') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT) . '';
        $this->load->library('Pdf');
        $obj_pdf = new TCPDF('I', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
        $data['invoices'] = $this->Invoices_Model->get_invoice_detail($id);
        $data['settings'] = $this->Settings_Model->get_settings_ciuis();
        $data['invoiceitems'] = $this->db->select('*,products.productname as urun,invoiceitems.id as id ')->join('products', 'invoiceitems.in[product_id] = products.id', 'left')->get_where('invoiceitems', array('invoiceid' => $id))->result_array();
        $this->load->view(get_template_directory() . '/crm/invoices/invoice_print', $data);
    }

    public function download($id)
    {
        $ind = $this->Invoices_Model->get_invoice_detail($id);
        $data['title'] = '' . lang('invoiceprefix') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT) . '';
        $this->load->library('Pdf');
        $data['invoices'] = $this->Invoices_Model->get_invoice_detail($id);
        $data['settings'] = $this->Settings_Model->get_settings_ciuis();
        $data['invoiceitems'] = $this->db->select('*,products.productname as urun,invoiceitems.id as id ')->join('products', 'invoiceitems.in[product_id] = products.id', 'left')->get_where('invoiceitems', array('invoiceid' => $id))->result_array();
        $this->load->view(get_template_directory() . '/crm/invoices/invoice_download', $data);
    }

    public function share($id)
    {
        $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffimage,customers.companyname as customer,customers.namesurname as individual,customers.companyaddress as customeraddress,customers.companyemail as customeremail,customers.type as type,invoicestatus.name as statusname, invoices.id as id ');
        $this->db->join('customers', 'invoices.customerid = customers.id', 'left');
        $this->db->join('invoicestatus', 'invoices.statusid = invoicestatus.id', 'left');
        $this->db->join('staff', 'invoices.staffid = staff.id', 'left');
        $inv = $this->db->get_where('invoices', array('invoices.id' => $id))->row_array();
        $data['invoices'] = $this->Invoices_Model->get_invoice_detail($id);
        $data['settings'] = $this->Settings_Model->get_settings_ciuis();
        // SEND EMAIL SETTINGS
        $setconfig = $this->Settings_Model->get_settings_ciuis();
        $this->load->library('email');
        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $setconfig['smtphost'];
        $config['smtp_user'] = $setconfig['smtpusername'];
        $config['smtp_pass'] = $setconfig['smtppassoword'];
        $config['smtp_port'] = $setconfig['smtpport'];
        $sender = $setconfig['sendermail'];
        switch ($inv['type']) {
        case '0':
            $invcustomer = $inv['customer'];
            break;
        case '1':
            $invcustomer = $inv['individual'];
            break;
        }
        $data = array(
            'customer' => $invcustomer,
            'customermail' => $inv['companyemail'],
            'invoicelink' => '' . base_url('share/invoice/' . $id . '') . '',
        );
        $body = $this->load->view(get_template_directory() . '/crm/email/invoices/sendinvoice.php', $data, true);
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype("html");
        $this->email->from($sender); // change it to yours
        $this->email->to($inv['companyemail']); // change it to yours
        $this->email->subject('Your Invoice Details');
        $this->email->message($body);
        if ($this->email->send()) {
            $response = $this->db->where('id', $id)->update('invoices', array('datesend' => date('Y-m-d H:i:s')));
            $this->session->set_flashdata('ntf1', '<b>' . lang('sendmailcustomer') . '</b>');
            redirect('invoices/invoice/' . $id . '');
        } else {
            $this->session->set_flashdata('ntf4', '<b>' . lang('sendmailcustomereror') . '</b>');
            redirect('invoices/invoice/' . $id . '');
        }
    }

    public function cancelled()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'invoiceid' => $_POST['invoiceid'],
                'statusid' => $_POST['statusid'],
            );
            $tickets = $this->Invoices_Model->cancelled();
        }
    }

    public function remove($id)
    {
        $invoices = $this->Invoices_Model->get_invoices($id);
        if (isset($invoices['id'])) {
            $this->session->set_flashdata('ntf4', lang('invoicedeleted'));
            $this->Invoices_Model->delete_invoices($id);
            redirect('invoices/index');
        } else {
            show_error('The invoices you are trying to delete does not exist.');
        }
    }
    public function removeitem()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'invitemid' => $_POST['invitemid'],
            );
            $response = $this->db->delete('invoiceitems', array('id' => $_POST['invitemid']));
        }
    }
}
