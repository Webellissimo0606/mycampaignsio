<?php
class Settings_Model extends CI_Model {
	function get_settings($settingname) {
		$this->db->select('*,countries.shortname as country,languages.name as language, currencies.name as currencyname,currencies.symbol as currency,settings.settingname as settingname ');
		$this->db->join('languages', 'settings.languageid = languages.foldername', 'left');
		$this->db->join('currencies', 'settings.currencyid = currencies.id', 'left');
		$this->db->join('countries', 'settings.countryid = countries.id', 'left');
		$this->db->where('settings.company_id', $_SESSION['company_id']);
		return $this->db->get_where('settings', array('settingname' => $settingname))->row_array();
	}
	function get_all_settings($settingname) {
		$this->db->select('*,countries.shortname as country,languages.name as language, currencies.name as currencyname,currencies.symbol as currency,settings.settingname as settingname ');
		$this->db->join('languages', 'settings.languageid = languages.foldername', 'left');
		$this->db->join('currencies', 'settings.currencyid = currencies.id', 'left');
		$this->db->join('countries', 'settings.countryid = countries.id', 'left');
		return $this->db->get_where('settings', array('settingname' => $settingname))->result_array();
	}
	function get_settings_ciuis() {
		/*debug($_SESSION);*/
		$this->db->select('*,countries.shortname as country,languages.name as language, currencies.name as currencyname,currencies.symbol as currency,settings.settingname as settingname ');
		$this->db->join('languages', 'settings.languageid = languages.foldername', 'left');
		$this->db->join('currencies', 'settings.currencyid = currencies.id', 'left');
		$this->db->join('countries', 'settings.countryid = countries.id', 'left');
		$this->db->where('settings.company_id', $_SESSION['company_id']);
		return $this->db->get_where('settings', array('settingname' === 'ciuis'))->row_array();
	}

	function update_settings($settingname, $params) {

		$this->db->where('settingname', $settingname);
		$this->db->where('settings.company_id', $_SESSION['company_id']);
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->logged_in_staff_id;
		$this->db->insert('logs', array(
			'date' => date('Y-m-d H:i:s'),
			'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('updatedsettings') . ''),
			'staffid' => $loggedinuserid,
			'company_id' => $_SESSION['company_id'],
		));
//count company id
		$query = $this->db->get_where('settings', array('company_id' => $_SESSION['company_id']));
		$count_company_id = $query->num_rows();

		if ($count_company_id > 0) {
			$response = $this->db->where('company_id', $_SESSION['company_id'])->update('settings', $params);
		} else {
			$response = $this->db->insert('settings', $params);
		}

	}

	function get_currencies() {

		return $this->db->get_where('currencies', array('company_id' => $_SESSION['company_id']))->result_array();
	}
	function get_languages() {
		return $this->db->get_where('languages', array('company_id' => $_SESSION['company_id']))->result_array();
	}
	function get_department($id) {
		return $this->db->get_where('departments', array('id' => $id))->row_array();
	}

	function get_departments() {
		$this->db->where('company_id', $_SESSION['company_id']);
		return $this->db->get_where('departments', array(''))->result_array();
	}
	function add_department($params) {
		$this->db->insert('departments', $params);
		return $this->db->insert_id();
	}
	function update_department($id, $params) {
		$this->db->where('id', $id);
		$response = $this->db->update('departments', $params);
	}
	function delete_department($id) {
		$response = $this->db->delete('departments', array('id' => $id));
	}

	function delete_logo($settingname) {
		$response = $this->db->where('settingname', $settingname)->update('settings', array('logo' => ''));
	}
	function get_crm_lang() {
		$this->db->limit(1, 0);
		$query = $this->db->get('settings');
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->languageid;
		}
	}
	function get_currency() {
		$this->db->limit(1, 0);
		$query = $this->db->where('company_id', $_SESSION['company_id'])->get('settings');
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$currencyid = $row->currencyid;
		}
		$this->db->limit(1, 0);
		$query = $this->db->get_where('currencies', array('id' => $currencyid));
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->symbol;
		}
	}
	public function load_config() {
		$this->db->limit(1, 0);
		$query = $this->db->get('settings');
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row;
		} else {
			return FALSE;
		}
	}
}
