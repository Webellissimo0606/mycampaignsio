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

class Serpphrasematch extends CI_Model 
{
	
	public $_table = 'serp_phrase_match';

	public function insertSerpPhraseMatch($domainId, $response)
	{
		$this->db->flush_cache();
        $data['domain_id']    = $domainId;
        $data['result']       = $response;
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
	}

}