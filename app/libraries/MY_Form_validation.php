<?php 
/**
 * CIMembership
 * 
 * @package		Libraries
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation 
{
	protected $ci;
	
	function __construct() {
		parent::__construct();
		$this->ci =& get_instance();
	}
	
	function edit_unique($value, $params)
	{
		$this->ci->form_validation->set_message('edit_unique', "This %s is already in use!");
		list($table, $field, $current_id) = explode(".", $params);
		$result = $this->ci->db->where($field, $value)->get($table)->row();
		return ($result && $result->id != $current_id) ? FALSE : TRUE;
	}
	
}

