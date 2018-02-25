<?php
/**
 * CIMembership
 *
 * @package        Modules
 * @author        1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license        http://opensource.org/licenses/MIT    MIT License
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Serpkeywordinfo_model extends CI_Model
{

    public $_table = 'serp_keyword_info';

    public function insertSerpKeywordInfo($domainId, $engineId, $result)
    {
    	$this->db->flush_cache();
    	$data['domain_id']        = $domainId;
    	$data['search_engine_id'] = $searchEngineId;
    	$data['result']           = $response;
    	$data['created']          = date('Y-m-d H:i:s');
    	$this->db->insert($this->_table, $data);
    	return $this->db->insert_id();
    }

    public function checkKeywordInfoExist($created, $engineId)
    {
        $this->db->flush_cache();
        $this->db->select('id');
        $this->db->from('serp_keyword_info as ski');
        $this->db->where('ski.created=', $created);
        $this->db->where('ski.search_engine_id=', $engineId);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query) {
            $result = $query->row_array();
        } else {
            return false;
        }
    }

    
    

}