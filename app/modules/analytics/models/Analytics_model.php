<?php

/**
 * CIMembership
 * 
 * @package		Modules
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analytics_model extends CI_Model {

    private $table_name = 'user_auth';

    function __construct() {
        parent::__construct();
//        $DB1 = $this->load->database('analytics', TRUE);
    }

    // get user by their social media id


    public function getUserAuthData($user_id) {
//        $DB1 = $this->load->database('analytics', TRUE);
        $this->db->select('*');
        $this->db->where($this->table_name . '.user_id=', $user_id);
        $this->db->from($this->table_name);
        $query = $this->db->get();
        return ($query->result());
    }

    public function insertUserAuthData($data) {
//        $DB1 = $this->load->database('analytics', TRUE);
        if ($this->db->insert($this->table_name, $data)) {
            $id = $this->db->insert_id();
            return array('id' => $id);
        }
        return NULL;
    }

    function updateUserAuthData($id, $data) {
//        $DB1 = $this->load->database('analytics', TRUE);
        $this->db->where('user_id', $id);
        if ($this->db->update($this->table_name, $data)) {
           
        return true;
        }
        return NULL;
       
    }

}

?>