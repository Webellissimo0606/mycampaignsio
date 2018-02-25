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

class Sites_assigned extends CI_Model {

    private $table_name = 'sites_assigned';

    function __construct() {
        parent::__construct();
        $ci = & get_instance();
        $this->table_name = $ci->config->item('db_table_prefix') . $this->table_name;
    }

    function save_client_users($data) {
        $user_id = $data['user_id'];
        $site_ids = $data['site_id'];
        if (!empty($site_ids)) {
            foreach ($site_ids as $site_id) {
                $insert_data = array();
                $insert_data['client_user_id'] = $user_id;
                $insert_data['site_id'] = $site_id;
                $this->db->select('client_user_id');
                $this->db->select('site_id');
                $this->db->where('client_user_id', $user_id);
                $this->db->where('site_id', $site_id);
                $query = $this->db->get($this->table_name);
                if (!$query->num_rows() > 0) {
                    $this->db->insert($this->table_name, $insert_data);
                    $status = 1;
                } else {
                    $status = 0;
                }
            }
            return $status;
        }
    }

}

?>