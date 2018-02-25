<?php
class Expenses_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /* Get Expenses by ID */
    public function get_expenses($id)
    {
        $this->db->select('*,customers.companyname as customer,customers.type as type,customers.namesurname as individual,accounts.name as account,expensecat.name as category,staff.staffname as staff,expenses.description as desc, expenses.id as id');
        $this->db->join('customers', 'expenses.customerid = customers.id', 'left');
        $this->db->join('accounts', 'expenses.accountid = accounts.id', 'left');
        $this->db->join('expensecat', 'expenses.expensecategoryid = expensecat.id', 'left');
        $this->db->join('staff', 'expenses.staffid = staff.id', 'left');
        return $this->db->get_where('expenses', array( 'expenses.id' => $id ))->row_array();
    }

    // All Expenses Count
    public function get_all_expenses_count()
    {
        $this->db->from('expenses');
        $this->db->where('expenses.company_id =' . $_SESSION['company_id']);
        return $this->db->count_all_results();
    }
    
    /* Get All Expenses */
    public function get_all_expenses()
    {
        $this->db->select('*,customers.companyname as customer,customers.type as type,customers.namesurname as individual,expensecat.name as category,staff.staffname as staff,expenses.description as desc, expenses.id as id');
        $this->db->join('customers', 'expenses.customerid = customers.id', 'left');
        $this->db->join('expensecat', 'expenses.expensecategoryid = expensecat.id', 'left');
        $this->db->join('staff', 'expenses.staffid = staff.id', 'left');
        $this->db->where('expenses.company_id =' . $_SESSION['company_id']);
        return $this->db->get('expenses')->result_array();
    }

    // Function to add new expenses
    public function add_expenses($params)
    {
        $this->db->insert('expenses', $params);
        $expense = $this->db->insert_id();
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('payments', array(
            'transactiontype' => 1,
            'expenseid' => $expense,
            'staffid' => $loggedinuserid,
            'amount' => $this->input->post('amount'),
            'accountid' => $this->input->post('accountid'),
            'customerid' => ($this->input->post('customerid'))?$this->input->post('customerid'):null,
            'not' => 'Outgoings for <a href="' . base_url('expenses/receipt/' . $expense . '') . '">EXP-' . $expense . '</a>',
            'date' => _pdate($this->input->post('date')),
        ));
        //LOG
        $staffname = $this->session->staffname;
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('addedanewexpense').' <a href="/expenses/receipt/' . $expense . '">'.lang('expenseprefix').'-' . $expense . '</a>.'),
            'staffid' => $loggedinuserid,
            'customerid' => $this->input->post('customerid'),
            'company_id' => $_SESSION['company_id']
        ));
        return $this->db->insert_id();
    }

    // Function to update expenses
    public function update_expenses($id, $params)
    {
        $this->db->where('id', $id);
        $response = $this->db->update('expenses', $params);
        $response = $this->db->where('expenseid', $id)->update('payments', array(
            'transactiontype' => 2,
            'amount' => $this->input->post('amount'),
            'accountid' => $this->input->post('accountid'),
            'customerid' => $this->input->post('customerid'),
            'not' => 'Payment for <a href="' . base_url('expenses/edit/' . $id . '') . '">EXP-' . $id . '</a>',
            'date' => _pdate($this->input->post('date')),
        ));
        $loggedinuserid = $this->session->logged_in_staff_id;
        $staffname = $this->session->staffname;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('updated').' <a href="/expenses/receipt/' . $id . '">'.lang('expenseprefix').'-' . $id . '</a>.'),
            'staffid' => $loggedinuserid,
            'customerid' => $this->input->post('customerid'),
            'company_id' => $_SESSION['company_id']
        ));
    }

    // Function to delete expenses
    public function delete_expenses($id)
    {
        $response = $this->db->delete('expenses', array( 'id' => $id ));
        $response = $this->db->delete('payments', array( 'expenseid' => $id ));
        $response = $this->db->delete('sales', array( 'invoiceid' => $id ));
        // LOG
        $staffname = $this->session->staffname;
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('deleted').' '.lang('expenseprefix').'-' . $id . ''),
            'staffid' => $loggedinuserid,
            'company_id' => $_SESSION['company_id']
        ));
    }

    public function get_expensecategory($id)
    {
        return $this->db->get_where('expensecat', array( 'id' => $id ))->row_array();
    }

    /* Get All Expense Categories */
    public function get_all_expensecat()
    {
        $this->db->order_by('id', 'desc');
        return $this->db->get('expensecat')->result_array();
    }

    /* Add Expense Category */
    public function add_expensecategory($params)
    {
        $this->db->insert('expensecat', $params);
        return $this->db->insert_id();
    }

    /* Update Expense Category */
    public function update_expensecategory($id, $params)
    {
        $this->db->where('id', $id);
        return $this->db->update('expensecat', $params);
    }

    /* Delete Expense Category */
    public function delete_expensecategory($id)
    {
        return $this->db->delete('expensecat', array( 'id' => $id ));
    }
}
