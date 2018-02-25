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
require_once('phpass-0.3/PasswordHash.php');

class Phpass 
{
	
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('session');
		$this->ci->load->database();
		$this->ci->load->model('auth/users');
	}


	function hash_password ( $password )
	{
		$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength'),
				$this->ci->config->item('phpass_hash_portable'));
		$hashed_password = $hasher->HashPassword($password);
		return $hashed_password;
	}
	
	function check_password ( $password, $db_password )
	{
		$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength'),
				$this->ci->config->item('phpass_hash_portable'));
		if ($hasher->CheckPassword($password, $db_password)) {
			return TRUE;
		}  else {
			return FALSE;
		}
	}
	
}
/* End of file Phpass.php */
/* Location: ./application/libraries/Phpass.php */