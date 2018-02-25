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

class Serpdomain_model extends CI_Model
{

    public $_table = 'serp_domain_info';

    public function insertSerpDomainInfo($domainId, $searchEngineId, $response, $status)
    {
        $this->db->flush_cache();
        $data['domain_id']        = $domainId;
        $data['search_engine_id'] = $searchEngineId;
        $data['status_msg']       = $status;
        $data['result']           = $response;
        $data['created']          = date('Y-m-d H:i:s');
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    public function getDomainInfoByDomain($domain) {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('domain=', $domain);
        $this->db->order_by("modified", "DESC");
        $this->db->limit("1");
        $query = $this->db->get();
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

}
