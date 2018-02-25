<?php
/**
 * CIMembership
 * 
 * @package		Modules
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ciconfig extends CI_Model
{
	private $table_name			= 'options';			// user accounts
	
	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
		$this->table_name			= $ci->config->item('db_table_prefix').$this->table_name;
		
		$pre = array();
		$pr = $this->db->get($this->table_name)->result();  
		foreach($pr as $p)
		{
			$pre[addslashes($p->option_name)] = addslashes($p->option_value);
		}      
		
		$ci->cimconfig = (object) $pre; 
		$ci->config->load('cimembership');

	}
	
	public function get_all()
	{
		return $this->db->get($this->table_name);
	}
}