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

class Linkpage_model extends CI_Model
{

    private $table_name = 'link_page';

    public function __construct()
    {
        parent::__construct();
    }
    public function insert($data)
    {
        $data['link_domain_id'] = $data['link_domain_id'];
        $data['backlink_domain_id'] = $data['backlink_domain_id'];
        $data['link'] = $data['link'];
        $data['keyword'] = $data['keyword'];
        $data['keyword_added_date'] = ($data['date'])?$data['date']:null;
        $data['position'] = ($data['position'])?$data['position']:null;
        $data['created'] = date('Y-m-d H:i:s');
    	$this->db->insert($this->table_name, $data);
    }

    public function getLinkPageByDomainId($domainId)
    {
        $query = "select lp.link_domain_id, lp.keyword,lp.keyword_added_date,lp.position, lp.backlink_domain_id, lp.link, lp.created,lp.id as link_page_id,bd.domain as backlink_domain,ld.domain as domain from link_page as lp join link_building_domain as ld on ld.id=lp.link_domain_id join backlink_domain as bd on bd.id=lp.backlink_domain_id where lp.link_domain_id='".$domainId."'";
        $query = $this->db->query($query);
        if ($query) {
            $res = $query->result_array();
            return $res;
        } else {
            return false;
        }
       
    }

    public function getLinkPageByPageId($id)
    {
       $query = "select lp.keyword,lp.position,lp.keyword_added_date,lp.link_domain_id, lp.backlink_domain_id, lp.link, lp.created,lp.id as link_page_id,bd.domain as backlink_domain,ld.domain as domain from link_page as lp join link_building_domain as ld on ld.id=lp.link_domain_id join backlink_domain as bd on bd.id=lp.backlink_domain_id where lp.id='".$id."'";
       $query = $this->db->query($query);
       if ($query) {
           $res = $query->row_array();
           return $res;
       } else {
           return false;
       } 
    }

    public function checkBackLinkExist($domain, $backlink_id)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('link_domain_id', $domain);
        $this->db->where('backlink_domain_id', $backlink_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }       
    }

    public function checkLinkPageDomain($link_page, $domainId)
    {
    	$this->db->select('*');
    	$this->db->from($this->table_name);
    	$this->db->where('link_domain_id', $domainId);
    	$this->db->where('link', $link_page);
    	$this->db->limit(1);
    	$query = $this->db->get();
    	if ($query) {
    		return $query->row_array();
    	} else {
    		return false;
    	}		
    }
    


    public function delete($domainId, $pageId)
    {
      	$this->db->flush_cache();
        $this->db->where('id', $pageId);
    	$this->db->where('link_domain_id', $domainId);
    	$this->db->delete($this->table_name);
    }
}
