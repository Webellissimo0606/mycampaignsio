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

class Serporganickeyword_model extends CI_Model
{

    public $_table = 'serp_organic_keyword';

    public function getSerpOrganiKeywordByDomain($domain)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('domain=', $domain);
        $this->db->limit("1");
        $query = $this->db->get();
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }

    }

}
