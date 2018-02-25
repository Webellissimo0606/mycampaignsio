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

class Serpdomainkeywords_model extends CI_Model
{

    public $_table = 'serp_domain_keywords';

    public function insertSerpDomainKeywords($domainId, $engineId, $response)
    {
        $this->db->flush_cache();
        $data['domain_id']        = $domainId;
        $data['search_engine_id'] = $engineId;
        $data['result']           = $response;
        $data['created']          = date('Y-m-d H:i:s');
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    public function getDataExistByDate($created, $engineId)
    {
        $this->db->flush_cache();
        $this->db->select('id');
        $this->db->from('serp_domain_keywords as sdk');
        $this->db->where('sdk.created=', $created);
        $this->db->where('sdk.search_engine_id=', $engineId);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query) {
            $result = $query->row_array();
        } else {
            return false;
        }
    }

}
