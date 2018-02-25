<?php
/**
 * CIMembership
 * 
 * @package		Hooks
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Configuration
{
	function Config_data()
	{
		$ci =& get_instance();
		$pre = array();
		$pr = $ci->ciconfig->get_all()->result();     
		foreach($pr as $p)
		{
			$pre[addslashes($p->option_name)] = addslashes($p->option_value);
		}      
		
		$ci->cimconfig = (object) $pre; 
		$ci->config->load('cimembership');
	}
}
?>