<?php
class Report_Model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('campaigns-io/functions');
	}

	function newreminder() {
		$this->db->from('reminders')->where('date <= CURDATE() AND staff = "' . $this->session->userdata('logged_in_staff_id') . '" AND isnotified != 1');
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}
    //Sales this month
	function salesThisMonth() {
		$this->db->select_sum('in[total]');
		$this->db->from('invoiceitems');
		$this->db->join('invoices', 'invoices.id = invoiceitems.invoiceid');
		$this->db->where('in[product_id] > 0');
		$this->db->where('invoices.company_id = ' . $_SESSION['company_id']);
		$this->db->where('MONTH(datecreated) = MONTH(CURRENT_DATE)');
		$array = json_decode(json_encode($this->db->get()->row()),true);
		return $array['in[total]'];
	}
	//Upcoming Invoices
	function upcomingInvoices() {
		$this->db->select('customers.namesurname , invoices.customerid , invoices.statusid , invoices.id, invoices.duedate, invoices.datecreated , invoiceitems.in[total] , invoicestatus.name');
		$this->db->from('invoices');
		$this->db->join('customers', 'customers.id = invoices.customerid');
		$this->db->join('invoiceitems', 'invoiceitems.invoiceid = invoices.id');
		$this->db->join('invoicestatus', 'invoicestatus.id = invoices.statusid');
		$this->db->where('invoices.company_id = ' . $_SESSION['company_id']);
		$this->db->where('WEEK(duedate) = WEEK(CURRENT_DATE)');
		$array = json_decode(json_encode($this->db->get()->result_array()),true);
		return $array;
	}
	// ACCOUNTS
	function tht() {
		$this->db->select_sum('amount');
		$this->db->from('payments');
		$this->db->where('transactiontype = 0');
		$this->db->where('company_id = ' . $_SESSION['company_id']);
		return $this->db->get()->row()->amount;
	}
	// CASH FLOW
	// ONLY THIS WEEK
	function put() {
		$this->db->select_sum('amount');
		$this->db->from('payments');
		$this->db->where('WEEK(date) = WEEK(CURRENT_DATE)');
		return $this->db->get()->row()->amount;
	}

	function pay() {
		$this->db->select_sum('amount');
		$this->db->from('payments');
		$this->db->where('WEEK(date) = WEEK(CURRENT_DATE) AND transactiontype = 0');
		return $this->db->get()->row()->amount;
	}
	// ONLY THIS WEEK
	function exp() {
		$this->db->select_sum('amount');
		$this->db->from('payments');
		$this->db->where('WEEK(date) = WEEK(CURRENT_DATE) AND transactiontype = 1');
		return $this->db->get()->row()->amount;
	}
	// ONLY THIS WEEK
	function totalpaym() {
		$this->db->from('payments')->where('WEEK(date) = WEEK(CURRENT_DATE)');
		$query = $this->db->get();
		$totalpaym = $query->num_rows();
		return $totalpaym;
	}
	// ONLY THIS WEEK
	function incomings() {
		$this->db->from('payments')->where('WEEK(date) = WEEK(CURRENT_DATE) AND transactiontype = 0 ');
		$query = $this->db->get();
		$incomings = $query->num_rows();
		return $incomings;
	}
	// ONLY THIS WEEK
	function outgoings() {
		$this->db->from('payments')->where('WEEK(date) = WEEK(CURRENT_DATE) AND transactiontype = 1 ');
		$query = $this->db->get();
		$outgoings = $query->num_rows();
		return $outgoings;
	}
	/////////////////////////////////////////////////////////////////

	// CUSTOMER FUNCTIONS
	function mst() {
		// Total Customer Count
		$this->db->from('customers')->where('company_id', $_SESSION['company_id']);
		$query = $this->db->get();
		$mst = $query->num_rows();
		return $mst;
	}

	function tks() {
		// corporate customers type 0
		$this->db->from('customers')->where('company_id', $_SESSION['company_id'])->where('type', 0);
		$query = $this->db->get();
		$tks = $query->num_rows();
		return $tks;
	}

	function tbm() {
		// individual customers type 1
		$this->db->from('customers')->where('company_id', $_SESSION['company_id'])->where('type', 1);
		$query = $this->db->get();
		$tbm = $query->num_rows();
		return $tbm;
	}

	function yms() {
		// new customer count
		$this->db->from('customers')->where('company_id', $_SESSION['company_id'])->where('WEEK(datecreated) = WEEK(CURRENT_DATE)');
		$query = $this->db->get();
		$yms = $query->num_rows();
		return $yms;
	}
	//PROPOSAL FUNCTIONS
	//AND COUNT BY PROPOSAL STATUSES
	//LIKE :'122'

	function tpc() {
		$this->db->from('proposals')->where('company_id', $_SESSION['company_id']);
		$query = $this->db->get();
		$tfa = $query->num_rows();
		return $tfa;
	}

	function dpc() {
		$this->db->from('proposals')->where('company_id', $_SESSION['company_id'])->where('statusid', 1);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function spc() {
		$this->db->from('proposals')->where('company_id', $_SESSION['company_id'])->where('statusid', 2);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function opc() {
		$this->db->from('proposals')->where('company_id', $_SESSION['company_id'])->where('statusid', 3);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function rpc() {
		$this->db->from('proposals')->where('company_id', $_SESSION['company_id'])->where('statusid', 4);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function pdc() {
		$this->db->from('proposals')->where('company_id', $_SESSION['company_id'])->where('statusid', 5);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function pac() {
		$this->db->from('proposals')->where('company_id', $_SESSION['company_id'])->where('statusid', 6);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	//TICKET FUNCTIONS
	//AND COUNT BY TICKET STATUSES
	//LIKE :'122'

	function ttc() {
		$this->db->from('tickets')->where('company_id', $_SESSION['company_id']);
		$query = $this->db->get();
		$tfa = $query->num_rows();
		return $tfa;
	}

	function twt() {
		$this->db->from('tickets')->where('company_id', $_SESSION['company_id'])->where('WEEK(date) = WEEK(date)');
		$query = $this->db->get();
		$tfa = $query->num_rows();
		return $tfa;
	}

	function otc() {
		$this->db->from('tickets')->where('company_id', $_SESSION['company_id'])->where('statusid', 1);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function ipc() {
		$this->db->from('tickets')->where('company_id', $_SESSION['company_id'])->where('statusid', 2);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function atc() {
		$this->db->from('tickets')->where('company_id', $_SESSION['company_id'])->where('statusid', 3);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function ctc() {
		$this->db->from('tickets')->where('company_id', $_SESSION['company_id'])->where('statusid', 4);
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	// Converted Leads Count // by staff
	function clc() {
		$this->db->from('leads');
		$this->db->where('company_id', $_SESSION['company_id'])->where('converted_date != "NULL" AND assigned = ' . $this->session->userdata('logged_in_staff_id') . '');
		$query = $this->db->get();
		$clc = $query->num_rows();
		return $clc;
	}

	function mlc() {
		$this->db->from('leads');
		$this->db->where('company_id', $_SESSION['company_id'])->where('assigned = ' . $this->session->userdata('logged_in_staff_id') . '');
		$query = $this->db->get();
		$mlc = $query->num_rows();
		return $mlc;
	}
	// Closed Tickets Count // by staff
	function mct() {
		$this->db->from('tickets');
		$this->db->where('company_id', $_SESSION['company_id'])->where('statusid = 4 AND staffid = ' . $this->session->userdata('logged_in_staff_id') . '');
		$query = $this->db->get();
		$clc = $query->num_rows();
		return $clc;
	}

	function mtt() {
		$this->db->from('tickets');
		$this->db->where('company_id', $_SESSION['company_id'])->where('staffid = ' . $this->session->userdata('logged_in_staff_id') . '');
		$query = $this->db->get();
		$mlc = $query->num_rows();
		return $mlc;
	}

	function ues() {
		$monday_this_week = date('Y-m-d', strtotime('monday next week'));
		$sunday_this_week = date('Y-m-d', strtotime('sunday next week'));
		$this->db->where('(start BETWEEN "' . $monday_this_week . '" AND "' . $sunday_this_week . '")');
		$this->db->where('company_id', $_SESSION['company_id'])->where('(staffid = ' . $this->session->userdata('logged_in_staff_id') . ' OR public = 1)');
		return $this->db->count_all_results('events');
	}

	function myc() {
		$this->db->from('customers');
		$this->db->where('company_id', $_SESSION['company_id'])->where('staffid = ' . $this->session->userdata('logged_in_staff_id') . '');
		$query = $this->db->get();
		$myc = $query->num_rows();
		return $myc;
	}

	// Total number of notifications

	function tbs() {
		// New notification counts
		return $this->db->get_where('notifications', array('markread' => 0, 'staffid' => $this->session->userdata('logged_in_staff_id')))->num_rows();
	}

	function bkt() {
		$this->db->select_sum('total');
		$this->db->from('sales');
		$this->db->where('DATE(date) = CURDATE() AND status != "1"');
		return $this->db->get()->row()->total;
	}

	function ogt() {
		$this->db->select_sum('total');
		$this->db->from('sales');
		$this->db->where('DAY(date) = DAY(CURRENT_DATE - INTERVAL 1 DAY) AND status != "1"');
		return $this->db->get()->row()->total;
	}

	function bht() {
		$this->db->select_sum('total');
		$this->db->from('sales');
		$this->db->where('WEEK(date) = WEEK(CURRENT_DATE) AND status != "1"');
		return $this->db->get()->row()->total;
	}

	function ohc() {
		$this->db->select_sum('total');
		$this->db->from('sales');
		$this->db->where('WEEK(date) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK) AND status != "1"');
		return $this->db->get()->row()->total;
	}

	function mex() {
		// Monthly Expenses
		$this->db->select_sum('amount');
		$this->db->from('expenses');
		$this->db->where('MONTH(date) = MONTH(CURRENT_DATE)');
		return $this->db->get()->row()->amount;
	}

	function pme() {
		$this->db->select_sum('amount');
		$this->db->from('expenses');
		$this->db->where('MONTH(date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
		return $this->db->get()->row()->amount;
	}

	function akt() {
		// MONTHLY EARN
		$this->db->select_sum('total');
		$this->db->from('sales');
		$this->db->where('MONTH(date) = MONTH(CURRENT_DATE) AND status != "1"');
		return $this->db->get()->row()->total;
	}

	function oak() {
		// LAST MOUNTH EARN
		$this->db->select_sum('total');
		$this->db->from('sales');
		$this->db->where('MONTH(date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND status != "1"');
		return $this->db->get()->row()->total;
	}

	function ycr() {
		// YEARLY EARN
		$this->db->select_sum('total');
		$this->db->from('sales');
		$this->db->where('YEAR(date) = YEAR(CURRENT_DATE) AND status != "1"');
		return $this->db->get()->row()->total;
	}

	function oyc() {
		// LAST YEAR EARN
		$this->db->select_sum('total');
		$this->db->from('sales');
		$this->db->where('YEAR(date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR) AND status != "1"');
		return $this->db->get()->row()->total;

	}
	// INVOICES TOTAL BY STATUS

	function pff() {
		$this->db->select_sum('total');
		$this->db->from('invoices');
		$this->db->where('company_id', $_SESSION['company_id'])->where('statusid', 1);
		return $this->db->get()->row()->total;
	}

	function fam() {
		$this->db->select_sum('total');
		$this->db->from('invoices')->where('company_id', $_SESSION['company_id']);
		return $this->db->get()->row()->total;
	}

	function ofv() {
		$this->db->select_sum('total');
		$this->db->from('invoices');
		$this->db->where('company_id', $_SESSION['company_id'])->where('statusid = 2');
		return $this->db->get()->row()->total;
	}

	function oft() {
		$this->db->select_sum('total');
		$this->db->from('invoices');
		$this->db->where('company_id', $_SESSION['company_id'])->where('statusid = 3 AND CURDATE() <= duedate');
		return $this->db->get()->row()->total;
	}

	function vgf() {
		$this->db->select_sum('total');
		$this->db->from('invoices');
		$this->db->where('company_id', $_SESSION['company_id'])->where('CURDATE() >= duedate AND duedate != "0000.00.00" AND statusid != "4" AND statusid != "2"');
		return $this->db->get()->row()->total;
	}

	// INVOICES COUNT BY STATUS
	function tfa() {
		$this->db->from('invoices')->where('company_id', $_SESSION['company_id']);
		$query = $this->db->get();
		$tfa = $query->num_rows();
		return $tfa;
	}

	function pfs() {
		$this->db->from('invoices')->where('company_id', $_SESSION['company_id'])->where('statusid', 1);
		$query = $this->db->get();
		$pfs = $query->num_rows();
		return $pfs;
	}

	function otf() {
		$this->db->from('invoices')->where('company_id', $_SESSION['company_id'])->where('statusid', 2);
		$query = $this->db->get();
		$otf = $query->num_rows();
		return $otf;
	}

	function tef() {
		$this->db->from('invoices')->where('company_id', $_SESSION['company_id'])->where('statusid = 3 AND CURDATE() <= duedate');
		$query = $this->db->get();
		$tef = $query->num_rows();
		return $tef;
	}

	function vdf() {
		$this->db->from('invoices')->where('company_id', $_SESSION['company_id'])->where('CURDATE() >= duedate AND duedate != "0000.00.00" AND statusid != "4" AND statusid != "2"');
		$query = $this->db->get();
		$vdf = $query->num_rows();
		return $vdf;
	}

	function tcl() {
		$this->db->from('leads')->where('company_id', $_SESSION['company_id'])->where('converted_date != "NULL"');
		$query = $this->db->get();
		$tcl = $query->num_rows();
		return $tcl;
	}

	function tll() {
		$this->db->from('leads')->where('company_id', $_SESSION['company_id'])->where('lost = 1');
		$query = $this->db->get();
		$tcl = $query->num_rows();
		return $tcl;
	}

	function tjl() {
		$this->db->from('leads')->where('company_id', $_SESSION['company_id'])->where('junk = 1');
		$query = $this->db->get();
		$tcl = $query->num_rows();
		return $tcl;
	}

	function monthly_sales_graph() {
		$totalsales = array();
		$i = 0;
		for ($mon = 1; $mon <= 12; $mon++) {
			$this->db->select('total');
			$this->db->from('sales');
			$this->db->where('MONTH(sales.date)', $mon);
			$sales_m = $this->db->get()->result_array();
			if (!isset($totalsales[$mon])) {
				$totalsales[$i] = array();
			}
			if (count($sales_m) > 0) {
				foreach ($sales_m as $earn) {
					$totalsales[$i][] = $earn['total'];
				}
			} else {
				$totalsales[$i][] = 0;
			}
			$totalsales[$i] = array_sum($totalsales[$i]);
			$i++;
		}
		return json_encode($totalsales);
	}

	function a1() {
		$totalsales = array();
		$i = 0;
		for ($week = 1; $week <= 12; $week++) {
			$this->db->select('amount');
			$this->db->from('expenses');
			$this->db->where('company_id', $_SESSION['company_id'])->where('MONTH(expenses.date)', $week);
			$sales_m = $this->db->get()->result_array();
			if (!isset($totalsales[$week])) {
				$totalsales[$i] = array();
			}
			if (count($sales_m) > 0) {
				foreach ($sales_m as $earn) {
					$totalsales[$i][] = $earn['amount'];
				}
			} else {
				$totalsales[$i][] = 0;
			}
			$totalsales[$i] = array_sum($totalsales[$i]);
			$i++;
		}
		return json_encode($totalsales);
	}

	public function weekly_sales_chart() {
		$allsales = array();
		$this->db->select('sales.total,sales.date');
		$this->db->from('sales');
		$this->db->where('CAST(sales.date as DATE) >= "' . date('Y-m-d', strtotime('monday this week')) . '" AND CAST(sales.date as DATE) <= "' . date('Y-m-d', strtotime('sunday this week')) . '"');
		$allsales[] = $this->db->get()->result_array();
		$graphic = array(
			'labels' => weekdays(),
			'datasets' => array(
				array(
					'type' => 'bar',
					'backgroundColor' => '#C7CBD5',
					'hoverBackgroundColor' => '#ffe8a8',
					'hoverBorderColor' => '#f5f5f5',
					'data' => array(0, 0, 0, 0, 0, 0, 0),
				),
				array(
					'type' => 'line',
					'backgroundColor' => '#C7CBD5',
					'hoverBackgroundColor' => '#ffe8a8',
					'hoverBorderColor' => '#f5f5f5',
					'data' => array(0, 0, 0, 0, 0, 0, 0),
				),
			),
		);
		for ($i = 0; $i < count($allsales); $i++) {
			foreach ($allsales[$i] as $salesc) {
				$salesday = date('l', strtotime($salesc['date']));
				$x = 0;
				foreach (weekdays_git() as $dayc) {
					if ($salesday == $dayc) {
						$graphic['datasets'][$i]['data'][$x] += $salesc['total'];
					}
					$x++;
				}
			}
		}
		return $graphic;
	}

	public function invoice_chart_by_status() {
		$statuslar = $this->db->get_where('invoicestatus', array(''))->result_array();
		$colors = ciuis_colors();
		$graphic = array(
			'labels' => array(),
			'datasets' => array(),
		);
		$_data = array();
		$_data['data'] = array();
		$_data['backgroundColor'] = array();
		$_data['hoverBackgroundColor'] = array();
		$i = 0;
		foreach ($statuslar as $status) {
			$this->db->where('statusid', $status['id']);
			array_push($graphic['labels'], $status['name']);
			array_push($_data['backgroundColor'], $status['color']);
			array_push($_data['hoverBackgroundColor'], ciuis_set_color($status['color'], -20));
			array_push($_data['data'], $this->db->count_all_results('invoices'));
			$i++;
		}
		$graphic['datasets'][] = $_data;
		$graphic['datasets'][0]['label'] = 'Invoice Status';
		return $graphic;
	}

	public function top_selling_staff_chart() {
		$this->load->model('Staff_Model');
		$staff = $this->Staff_Model->get_all_staff();
		$colors = 'rgb(235, 235, 235)';
		$graphic = array(
			'labels' => array(),
			'datasets' => array(),
		);
		$_data = array();
		$_data['data'] = array();
		$_data['backgroundColor'] = array();
		$_data['hoverBackgroundColor'] = array();
		$i = 0;
		foreach ($staff as $staffmember) {
			$this->db->where('staffid', $staffmember['id']);
			array_push($graphic['labels'], $staffmember['staffname']);
			array_push($_data['backgroundColor'], $colors);
			array_push($_data['hoverBackgroundColor'], ciuis_set_color($colors, -90));
			array_push($_data['data'], $this->db->count_all_results('sales'));

			$i++;
		}
		$graphic['datasets'][] = $_data;
		$graphic['datasets'][0]['label'] = 'Staff';
		return $graphic;
	}

	public function weekly_sales_chart_report() {
		$allsales = array();
		$this->db->select('sales.total,sales.date');
		$this->db->from('sales');
		$this->db->where('CAST(sales.date as DATE) >= "' . date('Y-m-d', strtotime('monday this week')) . '" AND CAST(sales.date as DATE) <= "' . date('Y-m-d', strtotime('sunday this week')) . '"');
		$allsales[] = $this->db->get()->result_array();
		$graphic = array(
			'labels' => weekdays(),
			'datasets' => array(
				array(
					'type' => 'line',
					'backgroundColor' => '#fff',
					'borderWidth' => '1',
					'hoverBackgroundColor' => '#ffe8a8',
					'hoverBorderColor' => '#f5f5f5',
					'data' => array(0, 0, 0, 0, 0, 0, 0),
				),
			),
		);
		for ($i = 0; $i < count($allsales); $i++) {
			foreach ($allsales[$i] as $salesc) {
				$salesday = date('l', strtotime($salesc['date']));
				$x = 0;
				foreach (weekdays_git() as $dayc) {
					if ($salesday == $dayc) {
						$graphic['datasets'][$i]['data'][$x] += $salesc['total'];
					}
					$x++;
				}
			}
		}
		return $graphic;
	}

	function weekly_expenses() {
		$allexpenses = array();
		$this->db->select('expenses.amount,expenses.date');
		$this->db->from('expenses');
		$this->db->where('company_id', $_SESSION['company_id']);
		$this->db->where('CAST(expenses.date as DATE) >= "' . date('Y-m-d', strtotime('monday this week')) . '" AND CAST(expenses.date as DATE) <= "' . date('Y-m-d', strtotime('sunday this week')) . '"');
		$allexpenses[] = $this->db->get()->result_array();
		$graphic = array('name' => 'Expenses', 'marker' => array('lineColor' => '#ffbc00', 'fillColor' => 'white'), 'data' => array(0, 0, 0, 0, 0, 0, 0));
		for ($i = 0; $i < count($allexpenses); $i++) {
			foreach ($allexpenses[$i] as $expensesc) {
				$expensesday = date('l', strtotime($expensesc['date']));
				$x = 0;
				foreach (weekdays_git() as $dayc) {
					if ($expensesday == $dayc) {
						$graphic['data'][$i] += $expensesc['amount'];
					}
					$x++;
				}
			}
		}
		return $graphic;
	}

	public function customer_monthly_increase_chart($month) {
		$grp = $this->db->query('select datecreated from customers where MONTH(datecreated) = ' . $month . '')->result_array();
		$month_d = array();
		$data = array();
		for ($d = 1; $d <= 31; $d++) {
			$timec = mktime(12, 0, 0, $month, $d, date('Y'));
			if (date('m', $timec) == $month) {
				$month_d[] = _date(date('Y-m-d', $timec));
				$data[] = 0;
			}
		}
		$graphic = array(
			'labels' => $month_d,
			'datasets' => array(
				array(
					'label' => 'Customers',
					'backgroundColor' => '#ffbc00',
					'borderColor' => '#ffbc00',
					'borderWidth' => 1,
					'tension' => false,
					'data' => $data,
				),
			),
		);
		foreach ($grp as $customer) {
			$i = 0;
			foreach ($graphic['labels'] as $date) {
				if (_date($customer['datecreated']) == $date) {
					$graphic['datasets'][0]['data'][$i]++;
				}
				$i++;
			}
		}
		return $graphic;
	}

	public function incomings_vs_outgoins($currentyear = '') {
		$allmonths = array();
		$outgoings = array();
		$incomings = array();
		$i = 0;
		if (!is_numeric($currentyear)) {
			$currentyear = date('Y');
		}
		for ($m = 1; $m <= 12; $m++) {
			array_push($allmonths, date('F', mktime(0, 0, 0, $m, 1)));
			$this->db->select('amount')->from('expenses')->where('MONTH(date)', $m)->where('YEAR(date)', $currentyear);
			$expenses = $this->db->get()->result_array();
			if (!isset($outgoings[$i])) {
				$outgoings[$i] = array();
			}
			if (count($expenses) > 0) {
				foreach ($expenses as $expense) {
					$total = $expense['amount'];
					$outgoings[$i][] = $total;
				}
			} else {
				$outgoings[$i][] = 0;
			}
			$outgoings[$i] = array_sum($outgoings[$i]);
			$this->db->select('amount');
			$this->db->from('payments');
			$this->db->join('invoices', 'invoices.id = payments.invoiceid');
			$this->db->where('MONTH(payments.date)', $m);
			$this->db->where('YEAR(payments.date)', $currentyear);
			$payments = $this->db->get()->result_array();
			if (!isset($incomings[$m])) {
				$incomings[$i] = array();
			}
			if (count($payments) > 0) {
				foreach ($payments as $payment) {
					$incomings[$i][] = $payment['amount'];
				}
			} else {
				$incomings[$i][] = 0;
			}
			$incomings[$i] = array_sum($incomings[$i]);
			$i++;
		}
		$graph = array(
			'labels' => $allmonths,
			'datasets' => array(
				array(
					'label' => 'Incomings',
					'backgroundColor' => 'rgba(0, 0, 0, 0)',
					'borderColor' => "rgba(255, 188, 0, 0.83)",
					'borderWidth' => 2,
					'tension' => false,
					'data' => $incomings,
				),
				array(
					'label' => 'Expenses',
					'backgroundColor' => 'rgba(0, 0, 0, 0)',
					'borderColor' => "#7d7d7d",
					'borderWidth' => 2,
					'tension' => false,
					'data' => $outgoings,
				),
			),
		);

		return $graph;
	}

	function expenses_by_categories() {
		$this->load->model('Expenses_Model');
		$expensecategories = $this->Expenses_Model->get_all_expensecat();
		$colors = 'rgba(255, 188, 0, 0.83)';
		$graphic = array(
			'labels' => array(),
			'datasets' => array(),
		);
		$_data = array();
		$_data['data'] = array();
		$_data['backgroundColor'] = array();
		$_data['hoverBackgroundColor'] = array();
		$i = 0;
		foreach ($expensecategories as $expensecategory) {
			$this->db->where('expensecategoryid', $expensecategory['id']);
			array_push($graphic['labels'], $expensecategory['name']);
			array_push($_data['backgroundColor'], $colors);
			array_push($_data['data'], $this->db->count_all_results('expenses'));

			$i++;
		}
		$graphic['datasets'][] = $_data;
		$graphic['datasets'][0]['label'] = 'Category';
		return $graphic;
	}
}