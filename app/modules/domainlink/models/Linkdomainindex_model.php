<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Linkdomainindex_model extends CI_Model
{
	private $table_name = 'link_domain_index';

	public function insert($data)
	{
		$this->db->flush_cache();
		$this->db->insert($this->table_name, $data);
	}
}	