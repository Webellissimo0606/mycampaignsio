<?php
class Serpdomainhistory_model extends CI_Model {

    public $_table = 'serp_domain_history';

    public function getSerpDomainHistoryByDomain( $domain ) {
        $query = "SELECT * FROM " . $this->_table . " WHERE domain LIKE '%" . $domain . "%';";
        $results = $this->db->query( $query );
        return $results ? $results->result_array() : false;
    }

    public function getMaxAndMinVisibilityAndKeywordByDomain( $domain ) {
        $query = "SELECT MAX( visible ) AS maxvisible, MIN( visible ) AS minvisible, MAX( keywords ) AS maxkeywords, MIN( keywords ) AS minkeywords FROM " . $this->_table . " WHERE domain = '" . $domain . "'";
        $results = $this->db->query( $query );
        return $results ? $results->row_array() : false;
    }
}
