<?php
class Invoices_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_invoices($id)
    {
        $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.companyname as customer,customers.namesurname as individual,customers.companyaddress as customeraddress,invoicestatus.name as statusname, invoices.id as id ');
        $this->db->join('customers', 'invoices.customerid = customers.id', 'left');
        $this->db->join('invoicestatus', 'invoices.statusid = invoicestatus.id', 'left');
        $this->db->join('staff', 'invoices.staffid = staff.id', 'left');
        return $this->db->get_where('invoices', array('invoices.id' => $id))->row_array();
    }
    public function get_invoiceByNumber($number)
    {
        $this->db->select('*');
        return $this->db->get_where('invoices', array('invoices.no' => $number))->row_array();
    }
    public function get_invoiceitems($id)
    {
        return $this->db->get_where('invoiceitems', array('id' => $id))->row_array();
    }
    // GET ALL INVOICES
    public function get_all_invoices()
    {
        $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.companyname as customer,customers.namesurname as individual,customers.companyaddress as customeraddress,invoicestatus.name as statusname, invoices.id as id ');
        $this->db->join('customers', 'invoices.customerid = customers.id', 'left');
        $this->db->join('invoicestatus', 'invoices.statusid = invoicestatus.id', 'left');
        $this->db->join('staff', 'invoices.staffid = staff.id', 'left');
        $this->db->order_by("invoices.id", "desc");
        $this->db->where('invoices.company_id = ' . $_SESSION['company_id']);
        return $this->db->get_where('invoices', array(''))->result_array();
    }

    public function dueinvoices()
    {
        $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.companyname as customer,customers.namesurname as individual,customers.companyaddress as customeraddress,customers.companyemail as customeremail,customers.type as type,invoicestatus.name as statusname, invoices.id as id ');
        $this->db->join('customers', 'invoices.customerid = customers.id', 'left');
        $this->db->join('invoicestatus', 'invoices.statusid = invoicestatus.id', 'left');
        $this->db->join('staff', 'invoices.staffid = staff.id', 'left');
        return $this->db->get_where('invoices', array('DATE(duedate)' => date('Y-m-d')))->result_array();
    }

    public function overdueinvoices()
    {
        $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.companyname as customer,customers.namesurname as individual,customers.companyaddress as customeraddress,customers.companyemail as customeremail,customers.type as type,invoicestatus.name as statusname, invoices.id as id ');
        $this->db->join('customers', 'invoices.customerid = customers.id', 'left');
        $this->db->join('invoicestatus', 'invoices.statusid = invoicestatus.id', 'left');
        $this->db->join('staff', 'invoices.staffid = staff.id', 'left');
        $this->db->where('CURDATE() > duedate AND duedate != "0000.00.00" AND statusid != "4" AND statusid != "2"');
        return $this->db->get('invoices')->result_array();
    }
    // GET INVOICE DETAILS
    public function get_invoice_detail($id)
    {
        $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.companyname as customer,customers.namesurname as individualindividual,customers.companyaddress as customeraddress,invoicestatus.name as statusname, invoices.id as id ');
        $this->db->join('customers', 'invoices.customerid = customers.id', 'left');
        $this->db->join('invoicestatus', 'invoices.statusid = invoicestatus.id', 'left');
        $this->db->join('staff', 'invoices.staffid = staff.id', 'left');
        return $this->db->get_where('invoices', array('invoices.id' => $id))->row_array();
    }
    public function get_invoicestatusid($status)
    {
        $this->db->select('id');
        $this->db->from('invoicestatus');
        $this->db->where('name = "' . $status . '" ');
        return $this->db->get()->row_array();
    }
    public function get_invoice_productsi_art($id)
    {
        $this->db->select_sum('in[total]');
        $this->db->from('invoiceitems');
        $this->db->where('(invoiceid = ' . $id . ') ');
        return $this->db->get();
    }

    public function get_invoice_tahsil_edilen($id)
    {
        $this->db->select_sum('amount');
        $this->db->from('payments');
        $this->db->where('(invoiceid = ' . $id . ') ');
        return $this->db->get();
    }
    // CHANCE INVOCE STATUS

    public function status_1($id)
    {
        $response = $this->db->where('id', $id)->update('invoices', array('statusid' => ('1')));
        $response = $this->db->update('sales', array('invoiceid' => $id, 'status' => '1'));
    }

    public function status_2($id)
    {
        $response = $this->db->where('id', $id)->update('invoices', array('statusid' => ('2')));
        $response = $this->db->update('sales', array('invoiceid' => $id, 'status' => '2'));
    }

    public function status_3($id)
    {
        $response = $this->db->where('id', $id)->update('invoices', array('statusid' => ('3')));
        $response = $this->db->update('sales', array('invoiceid' => $id, 'status' => '3'));
    }
    // ADD INVOICE
    public function invoice_add($params)
    {
        $params['datesend']=       $params['proposalid']=$params['date_payment']=null;
        
        $this->db->insert('invoices', $params);
        $invoice = $this->db->insert_id();
        if ($this->input->post('statusid') == 2) {
            $loggedinuserid = $this->session->logged_in_staff_id;
            $this->db->insert('payments', array(
                'transactiontype' => 0,
                'invoiceid' => $invoice,
                'staffid' => $loggedinuserid,
                'amount' => $this->input->post('total'),
                'customerid' => $this->input->post('customerid'),
                'accountid' => $this->input->post('accountid'),
                'not' => 'Paymet for <a href="' . base_url('invoices/invoice/' . $invoice . '') . '">' . lang('invoiceprefix') . '' . $invoice . '</a>',
                'date' => _pdate($this->input->post('date_payment'))
                
            ));
        }
        // MULTIPLE INVOICE ITEMS POST
        $countitem = count($this->input->post('in[product_id]'));
        $countitem = $countitem - 1;
        for ($i = 0; $i < $countitem; $i++) {
            $iteminfo[] = array(
                'invoiceid' => $invoice,
                'company_id'=>$_SESSION['company_id'],
                'in[itemid]' => $this->input->post('in[itemid]')[$i],
                'in[name]' => $this->input->post('in[name]')[$i],
                'in[code]' => $this->input->post('in[code]')[$i],
                'in[product_id]' => ($this->input->post('in[product_id]')[$i])?$this->input->post('in[product_id]')[$i]:null,
                'in[description]' => $this->input->post('in[description]')[$i],
                'in[amount]' => $this->input->post('in[amount]')[$i],
                'in[unit]' => $this->input->post('in[unit]')[$i],
                'in[price]' => $this->input->post('in[pricepost]')[$i],
                'in[discount_rate]' => $this->input->post('in[discount_rate]')[$i],
                'in[price_discounted]' => $this->input->post('in[price_discounted]')[$i],
                'in[discount_type]' => $this->input->post('in[discount_type]')[$i],
                'in[discount_rate_status]' => $this->input->post('in[discount_rate_status]')[$i],
                'in[vat]' =>str_replace(",", '', $this->input->post('in[vat]')[$i]),
                'in[total_vat]' => str_replace(",", '', $this->input->post('in[total_vat]')[$i]),
                'in[total]' => str_replace(",", '', $this->input->post('in[total]')[$i]),
            );
        }
        $this->db->insert_batch('invoiceitems', $iteminfo);
        //LOG
        $staffname = $this->session->staffname;
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('added') . ' <a href="/invoices/invoice/' . $invoice . '">' . lang('invoiceprefix') . '-' . $invoice . '</a>.'),
            'staffid' => $loggedinuserid,
            'customerid' => $this->input->post('customerid'),
            'company_id' => $_SESSION['company_id'],
        ));
        //NOTIFICATION
        $staffname = $this->session->staffname;
        $staffavatar = $this->session->staffavatar;
        $this->db->insert('notifications', array(
            'date' => date('Y-m-d H:i:s'),
             'staffid' => $loggedinuserid,
            'company_id' => $_SESSION['company_id'],
            'detail' => ('<div class="text"><span class="user-name">' . $staffname . '</span> ' . lang('isaddedanewinvoice') . '</div>'),
            'customerid' => $this->input->post('customerid'),
            'perres' => $staffavatar,
            'target' => '' . base_url('customer/invoice/' . $invoice . '') . '',
        ));
        //--------------------------------------------------------------------------------------
        $this->db->insert($this->db->dbprefix . 'sales', array(
            'invoiceid' => '' . $invoice . '',
            'company_id' => $_SESSION['company_id'],
            'status' => $this->input->post('statusid'),
            'staffid' => $loggedinuserid,
            'customerid' => $this->input->post('customerid'),
            'total' => $this->input->post('total'),
            'date' => date('Y-m-d H:i:s'),
        ));
        //----------------------------------------------------------------------------------------
        return $this->db->insert_id();
    }
    // UPDATE INVOCE
    public function update_invoices($id, $params)
    {
        $this->db->where('id', $id);
        $invoice = $id;
        $response = $this->db->update('invoices', $params);
        $response = $this->db->delete('invoiceitems', array('invoiceid' => $id));
        // MULTIPLE INVOICE ITEMS ADD
        $countitem = count($this->input->post('in[itemid]')); // in[newitem yapabilirsin]
        $countitem = $countitem - 1;
        for ($i = 0; $i < $countitem; $i++) {
            $newitem[] = array(
                'invoiceid' => $invoice,
                'in[name]' => $this->input->post('in[name]')[$i],
                'in[code]' => $this->input->post('in[code]')[$i],
                'in[product_id]' => ($this->input->post('in[product_id]')[$i])?$this->input->post('in[product_id]')[$i]:null,
                'in[description]' => $this->input->post('in[description]')[$i],
                'in[amount]' => $this->input->post('in[amount]')[$i],
                'in[unit]' => $this->input->post('in[unit]')[$i],
                'in[price]' => $this->input->post('in[pricepost]')[$i],
                'in[discount_rate]' => $this->input->post('in[discount_rate]')[$i],
                'in[price_discounted]' => $this->input->post('in[price_discounted]')[$i],
                'in[discount_type]' => $this->input->post('in[discount_type]')[$i],
                'in[discount_rate_status]' => $this->input->post('in[discount_rate_status]')[$i],
                'in[vat]' =>str_replace(",", '', $this->input->post('in[vat]')[$i]),
                'in[total_vat]' => str_replace(",", '', $this->input->post('in[total_vat]')[$i]),
                'in[total]' => str_replace(",", '', $this->input->post('in[total]')[$i]),
            );
        }
        $this->db->insert_batch('invoiceitems', $newitem);
        // UPDATE SALES INFORMATIONS
        $response = $this->db->where('invoiceid', $id)->update('sales', array(
            'status' => $this->input->post('statusid'),
            'staffid' => $this->input->post('staffid'),
            'customerid' => $this->input->post('customerid'),
            'total' => $this->input->post('total'),
        ));
        //LOG
        $staffname = $this->session->staffname;
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('updated') . ' <a href="/invoices/invoice/' . $id . '">' . lang('invoiceprefix') . '-' . $id . '</a>.'),
            'staffid' => $loggedinuserid,
            'customerid' => $this->input->post('customerid'),
            'company_id' => $_SESSION['company_id'],
        ));
        //NOTIFICATION
        $staffname = $this->session->staffname;
        $staffavatar = $this->session->staffavatar;
        $this->db->insert('notifications', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<div class="text"><span class="user-name">' . $staffname . '</span> ' . lang('uptdatedinvoice') . '</div>'),
            'customerid' => $this->input->post('customerid'),
            'perres' => $staffavatar,
            'target' => '' . base_url('customer/invoice/' . $invoice . '') . '',
            'company_id' => $_SESSION['company_id'],

        ));
        if ($response) {
            return "Invoice Updated.";
        } else {
            return "There was a problem during the update.";
        }
    }
    //INVOICE DELETE
    public function delete_invoices($id)
    {
        $response = $this->db->delete('invoices', array('id' => $id));
        $response = $this->db->delete('invoiceitems', array('invoiceid' => $id));
        $response = $this->db->delete('payments', array('invoiceid' => $id));
        $response = $this->db->delete('sales', array('invoiceid' => $id));
        $response = $this->db->where('invoiceid', $id)->update('expenses', array('invoiceid' => null));
        // LOG
        $staffname = $this->session->staffname;
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('deleted') . ' ' . lang('invoiceprefix') . '-' . $id . ''),
            'staffid' => $loggedinuserid,
            'company_id' => $_SESSION['company_id'],
        ));
    }

    // function updateinvoiceitemsingleinline( $column, $lastvalue, $id ) {
    //	$column = $this->input->post( 'column' );
    //	$lastvalue = $this->input->post( 'lastvalue' );
    //	$id = $this->input->post( 'id' );
    //	$result = $this->db->where( 'id', $id )->update( 'invoiceitems', array(
    //		'' . $column . '' => '' . $lastvalue . ''
    //	) );
    //}

    public function cancelled()
    {
        $response = $this->db->where('id', $_POST['invoiceid'])->update('invoices', array('statusid' => $_POST['statusid']));
        $response = $this->db->delete('sales', array('invoiceid' => $_POST['invoiceid']));
    }

    public function deleteinvoiceitem($id)
    {
        $response = $this->db->delete('invoiceitems', array('id' => $id));
    }

    public function get_invoice_year()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM invoices ORDER BY year DESC')->result_array();
    }
}
