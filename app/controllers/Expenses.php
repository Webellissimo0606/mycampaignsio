<?php
defined('BASEPATH')or exit('No direct script access allowed');
class Expenses extends CIUIS_Controller
{
    public function index()
    {
        $this->breadcrumb->clear();
        $this->breadcrumb->add_crumb('Dashboard', 'panel');
        $this->breadcrumb->add_crumb('Products', 'products');
        $this->breadcrumb->add_crumb('Tüm Products');
        $data[ 'title' ] = lang('expenses');
        $data[ 'tbs' ] = $this->db->count_all('notifications', array( 'markread' => ('0') ));
        $data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
        $data[ 'expenses' ] = $this->Expenses_Model->get_all_expenses();
        $data[ 'all_staff' ] = $this->Staff_Model->get_all_staff();
        $data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
        $data[ 'all_accounts' ] = $this->Accounts_Model->get_all_accounts();
        $data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
        $data[ 'expensecat' ] = $this->Expenses_Model->get_all_expensecat();
        $this->load->view(get_template_directory() . '/crm/expenses/index', $data);
    }

    public function add()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'company_id' => $_SESSION['company_id'],
                'expensecategoryid' => $this->input->post('expensecategoryid'),
                'staffid' => $this->input->post('staffid'),
                'customerid' => ($this->input->post('customerid'))?$this->input->post('customerid'):null,
                'accountid' => $this->input->post('accountid'),
                'title' => $this->input->post('title'),
                'date' => _phdate($this->input->post('date')),
                'dateadded' => $this->input->post('dateadded'),
                'amount' => $this->input->post('amount'),
                'description' => $this->input->post('description'),
            );
            $expenses_id = $this->Expenses_Model->add_expenses($params);
            redirect('expenses/index');
        } else {
            $data[ 'all_expensecat' ] = $this->Expenses_Model->get_all_expensecat();
            $data[ 'all_staff' ] = $this->Staff_model->get_all_staff();
            $data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
            $data[ 'all_accounts' ] = $this->Accounts_model->get_all_accounts();
            $this->load->view(get_template_directory() . '/crm/expenses/add', $data);
        }
    }

    public function edit($id)
    {
        // check if the expenses exists before trying to edit it
        $data[ 'expenses' ] = $this->Expenses_Model->get_expenses($id);

        if (isset($data[ 'expenses' ][ 'id' ])) {
            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'expensecategoryid' => $this->input->post('expensecategoryid'),
                    'staffid' => $this->input->post('staffid'),
                    'customerid' => $this->input->post('customerid'),
                    'accountid' => $this->input->post('accountid'),
                    'title' => $this->input->post('title'),
                    'date' => _phdate($this->input->post('date')),
                    'dateadded' => $this->input->post('dateadded'),
                    'amount' => $this->input->post('amount'),
                    'description' => $this->input->post('description'),
                );
                $this->Expenses_Model->update_expenses($id, $params);
                redirect('expenses/index');
            } else {
                $data[ 'all_expensecat' ] = $this->Expenses_Model->get_all_expensecat();
                $data[ 'all_staff' ] = $this->Staff_model->get_all_staff();
                $this->load->view(get_template_directory() . '/crm/expenses/edit', $data);
            }
        } else {
            show_error('The expenses you are trying to edit does not exist.');
        }
    }

    public function receipt($id)
    {
        $this->breadcrumb->clear();
        $this->breadcrumb->add_crumb('Dashboard', 'panel');
        $this->breadcrumb->add_crumb('Products', 'products');
        $this->breadcrumb->add_crumb('Tüm Products');
        $data[ 'title' ] = lang('expense');
        $data[ 'tbs' ] = $this->db->count_all('notifications', array( 'markread' => ('0') ));
        $data[ 'newnotification' ] = $this->Notifications_Model->newnotification();
        $data[ 'expenses' ] = $this->Expenses_Model->get_all_expenses();
        $data[ 'all_staff' ] = $this->Staff_Model->get_all_staff();
        $data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
        $data[ 'all_accounts' ] = $this->Accounts_Model->get_all_accounts();
        $data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
        $data[ 'expensecat' ] = $this->Expenses_Model->get_all_expensecat();
        $data[ 'expenses' ] = $this->Expenses_Model->get_expenses($id);
        $data[ 'expensesdata' ] = $this->Expenses_Model->get_all_expenses();
        if (isset($data[ 'expenses' ][ 'id' ])) {
            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'expensecategoryid' => $this->input->post('expensecategoryid'),
                    'staffid' => $this->input->post('staffid'),
                    'customerid' => $this->input->post('customerid'),
                    'accountid' => $this->input->post('accountid'),
                    'title' => $this->input->post('title'),
                    'date' => _pdate($this->input->post('date')),
                    'dateadded' => $this->input->post('dateadded'),
                    'amount' => $this->input->post('amount'),
                    'description' => $this->input->post('description'),
                );
                $this->Expenses_Model->update_expenses($id, $params);
                redirect('expenses/index');
            } else {
                $data[ 'all_expensecat' ] = $this->Expenses_Model->get_all_expensecat();
                $data[ 'all_staff' ] = $this->Staff_Model->get_all_staff();
                $this->load->view(get_template_directory() . '/crm/expenses/receipt', $data);
            }
        } else {
            show_error('The expenses you are trying to edit does not exist.');
        }
    }

    public function convertinvoice($id)
    {
        $expenses = $this->Expenses_Model->get_expenses($id);
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'staffid' => $expenses[ 'staffid' ],
                'customerid' => $expenses[ 'customerid' ],
                'datecreated' => date('Y-m-d H:i:s'),
                'statusid' => 3,
                'total' => $expenses[ 'amount' ],
                'expenseid' => $id,
                'total_sub' => $expenses[ 'amount' ],
            );
            $this->db->insert('invoices', $params);
            $invoice = $this->db->insert_id();
            $this->db->insert('invoiceitems', array(
                'invoiceid' => $invoice,
                'in[name]' => $expenses[ 'title' ],
                'in[total]' => $expenses[ 'amount' ],
                'in[price]' => $expenses[ 'amount' ],
                'in[amount]' => 1,
                'in[unit]' => 'Unit',
                'in[description]' => $expenses[ 'desc' ],
            ));
            $loggedinuserid = $this->session->logged_in_staff_id;
            $this->db->insert($this->db->dbprefix . 'sales', array(
            'invoiceid' => '' . $invoice . '',
            'status' => 3,
            'staffid' => $loggedinuserid,
            'customerid' => $expenses[ 'customerid' ],
            'total' => $expenses[ 'amount' ],
            'date' => date('Y-m-d H:i:s')
        ));
            $staffname = $this->session->staffname;
            $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => (''),
            'detail' => ('' . $message = sprintf(lang('expensetoinvoicelog'), $staffname, $expenses[ 'id' ]) . ''),
            'staffid' => $loggedinuserid,
            'customerid' => $expenses[ 'customerid' ],
        ));
            $response = $this->db->where('id', $id)->update('expenses', array( 'invoiceid' => $invoice ));
            redirect('invoices/edit/' . $invoice . '');
        }
    }

    public function remove($id)
    {
        $expenses = $this->Expenses_Model->get_expenses($id);
        // check if the expenses exists before trying to delete it
        if (isset($expenses[ 'id' ])) {
            $this->Expenses_Model->delete_expenses($id);
            redirect('expenses/index');
        } else {
            show_error('The expenses you are trying to delete does not exist.');
        }
    }

    public function addcategory()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'company_id'=>$_SESSION['company_id']
            );
            $expensecategory_id = $this->Expenses_Model->add_expensecategory($params);
            redirect('expenses/index');
        } else {
            redirect('expenses/index');
        }
    }

    public function editcategory($id)
    {
        $data[ 'expensecategory' ] = $this->Expenses_Model->get_expensecategory($id);
        if (isset($data[ 'expensecategory' ][ 'id' ])) {
            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                );
                $this->Expenses_Model->update_expensecategory($id, $params);
                redirect('expenses/index');
            } else {
                redirect('expenses/index');
            }
        } else {
            show_error('The expensecategory you are trying to edit does not exist.');
        }
    }

    public function removecategory($id)
    {
        $expensecategory = $this->Expenses_Model->get_expensecategory($id);
        if (isset($expensecategory[ 'id' ])) {
            $this->Expenses_Model->delete_expensecategory($id);
            redirect('expenses/index');
        } else {
            show_error('The expensecategory you are trying to delete does not exist.');
        }
    }
}
