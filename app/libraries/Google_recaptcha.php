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
class Google_recaptcha 
{
	function getCurlData ( $secret, $gcaptcha, $userIp )
	{
		$url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$gcaptcha."&remoteip=".$userIp;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
}

/* End of file Googlecaptcha.php */
/* Location: ./application/libraries/Googlecaptcha.php */

