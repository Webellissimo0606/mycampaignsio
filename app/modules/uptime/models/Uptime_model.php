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

class Uptime_model extends CI_Model {

    private $table_name = 'users';
    private $profile_table_name = 'user_profiles';

    function __construct() {
        parent::__construct();
        $DB1 = $this->load->database('uptime', TRUE);
    }

    // get user by their social media id
    function insert_user_data($insert_data) {
        $DB1 = $this->load->database('uptime', TRUE);
        $insert_data['created'] = date('Y-m-d H:i:s');
        //print_r($data);
        $sql = "INSERT INTO users (user_id,username,created,loggedin_Id,password) " .
                "VALUES (" .
                $insert_data['user_id'] .
                ",'" .
               $insert_data['username'] .
                 "','" .
               $insert_data['created'] .
                "'," .
                $insert_data['loggedin_Id'] .
                ",'" .
               $insert_data['password'] .
                "')";
        if ($DB1->query($sql)) {
            $user_id = $insert_data['user_id'];

            return $user_id;
        }
        return NULL;
    }
    
     function add_site($insert_data) {
        $DB1 = $this->load->database('uptime', TRUE);
        $insert_data['created'] = date('Y-m-d H:i:s');
        //print_r($data);
        $sql = "INSERT INTO sites (user_id,name,url,loggedin_Id,response,created) " .
                "VALUES (" .
                $insert_data['user_id'] .
                ",'" .
               $insert_data['name'] .
                 "','" .
               $insert_data['url'] .
                "'," .
                $insert_data['loggedin_Id'] .
                ",'" .
               $insert_data['response'] .
                "','" .
               $insert_data['created'] .
                "')";
        if ($DB1->query($sql)) {
            $id = $DB1->insert_id();
            $DB1->select('*');
		$DB1->where('sites.id=', $id);
		$DB1->from('sites');
		$query = $DB1->get();
		return ($query->result());

          
        }
        return NULL;
    }
    
    public function user_data($user_id) 
	{
		$this->db->select('*');
		$this->db->where($this->table_name.'.id=', $user_id);
		$this->db->from($this->table_name);
		$this->db->join($this->profile_table_name, $this->table_name.'.id = '.$this->profile_table_name.'.user_id');
		$query = $this->db->get();
		return ($query->result());
	}
        
         public function get_user_data($user_id) 
	{
             $DB1 = $this->load->database('uptime', TRUE);
		$DB1->select('*');
		$DB1->where($this->table_name.'.loggedin_Id=', $user_id);
		$DB1->from($this->table_name);
		$query = $DB1->get();
		return ($query->result());
	}

    


}

?>