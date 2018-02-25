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

class Settings_model extends CI_Model
{
	private $table_name			= 'options';			// user accounts

	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
		$this->table_name			= $ci->config->item('db_table_prefix').$this->table_name;
	}
	
	 public function get_all()
	 {
	   $pr = $this->db->get($this->table_name)->result();
		foreach($pr as $p)
		{
			$pre[addslashes($p->option_name)] = addslashes($p->option_value);
		}      
        return (object) $pre; 
	 }
	 
	public function update_config($data)
	{
		$success = true;
		foreach($data as $key=>$value)
		{
			if(!$this->save($key,$value))
			{
				$success=false;
				break;  
			}
		}
		return $success;
	}
	 
	public function save($key,$value)
	{
		$config_data=array(
			'option_name'=>$key,
			'option_value'=>$value
		);
		$this->db->where('option_name', $key);
		return $this->db->update($this->table_name,$config_data); 
	}
}