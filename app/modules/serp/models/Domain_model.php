<?php
/**
 * CIMembership
 * 
 * @package		Modules
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Domain_model extends CI_Model 
{

	function __construct()
	{
            parent::__construct();
	    $this->domain_table = 'domains';
	    $this->serpTable               = 'serp';
	    $this->serpHistory             = 'serp_history';
	    $this->searchEngineTable       = 'search_engine';
	    $this->keywordTable            = 'keywords_master';
	    $this->localSearchKeywordTable = 'domain_local_keyword_search';
	    $this->test_table = 'test_master';
        $this->load->model('auth/analyze_model');
        $this->salt = 'X9WX^YvmY!5]\LnD';
	}
	public function create($data)
	{
	   $query = $this->db->insert($this->domain_table,$data);	
	   return $query;
	}
	public function insertdata($data)
	{
		$query = $this->db->insert($this->test_table,$data);	
	   	return $query;
	}
	function getDomain($domainId) {
	    $this->db->select('*');
	    $this->db->from($this->domain_table);
	    $this->db->where('id', $domainId);
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
	}

	public function getDownTimeByDomainId($domainId)
	{
		$this->db->select('i.downtime,d.server_status');
		$this->db->from($this->domain_table." as d");
		$this->db->join("uptime_incidents AS i", "i.domain_id=d.id", "left");
		$this->db->where('d.id=',$domainId);
		$this->db->order_by('i.downtime','desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row_array();
	}

	function getDomainByDomainIdAndUserId($domainId, $userId)
	{
	    $this->db->select('*');
	    $this->db->from($this->domain_table." as d");
	    $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
	    $this->db->where('ud.user_id=',$userId);
	    $this->db->where('d.id=',$domainId);
	    $query = $this->db->get();
	    return $query->row_array();

	}

	function stopDomain($user, $domainId)
	{
	    //domainDetails
	    $domainDetails = $this->getDomain($domainId);
	    //uptime details
	    $uptimeDetails = $this->analyze_model->getUptimeByDomainId($domainId);
	    if($uptimeDetails['up_time_id']){
	        $vars = array('username' => $user[0]->username,
	            'password'               => sha1($user[0]->username.$this->salt));
	        $ch = curl_init('http://api.upmonitor.io/api/v1/authenticate/');
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        $result   = curl_exec($ch);
	        $token    = json_decode($result, true);
	        if (isset($token['token'])) {
	        	$token    = $token['token'];
	        	$data_string = '';
	        	$ch = curl_init('http://api.upmonitor.io/api/v1/sites/'.$uptimeDetails['up_time_id']);
	        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	        	// curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        	    'Authorization: Token ' . $token,
	        	    'Content-Type: application/json',
	        	    'Content-Length: ' . strlen($data_string))  
	        	);
	        	$result = curl_exec($ch);
	        	$result = json_decode($result, true);
	        }
	    }
	    //delete from uptime table
	    $this->db->flush_cache();
	    $this->db->where('domain_id=',$domainId);
	    $this->db->delete('uptime');
	}

	function deleteDomain($user, $domainId)
	{
	    //domainDetails
	    $domainDetails = $this->getDomain($domainId);

	    //uptime details
	    $uptimeDetails = $this->analyze_model->getUptimeByDomainId($domainId);
	    if($uptimeDetails['up_time_id']){
	        $vars = array('username' => $user[0]->username,
	            'password'               => sha1($user[0]->username.$this->salt));

	        $ch = curl_init('http://api.upmonitor.io/api/v1/authenticate/');
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        $result   = curl_exec($ch);
	        $token    = json_decode($result, true);
	        if (isset($token['token'])) {
	        	$token    = $token['token'];

	        	$data_string = '';

	        	$ch = curl_init('http://api.upmonitor.io/api/v1/sites/'.$uptimeDetails['up_time_id']);

	        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	        	// curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        	    'Authorization: Token ' . $token,
	        	    'Content-Type: application/json',
	        	    'Content-Length: ' . strlen($data_string))  
	        	);
	        	$result = curl_exec($ch);
	        	$result = json_decode($result, true);
	        }
	    }
	 
	    //delete from domain table
	    $this->db->flush_cache();
	    $this->db->where('id=',$domainId);
	    $this->db->delete($this->domain_table);
	    //delete from keywords table
	    $this->db->flush_cache();
	    $this->db->where('domain_id=',$domainId);
	    $this->db->delete($this->keywordTable);
	    //delete from search engine table
	    $this->db->flush_cache();
	    $this->db->where('domain_id=',$domainId);
	    $this->db->delete($this->searchEngineTable);
	    //delete from serp table
	    $this->db->flush_cache();
	    $this->db->where('domain_id=',$domainId);
	    $this->db->delete($this->serpTable);
	    //delete from serp history table
	    $this->db->flush_cache();
	    $this->db->where('domain_id=',$domainId);
	    $this->db->delete($this->serpHistory);
	    //delete from uptime table
	    $this->db->flush_cache();
	    $this->db->where('domain_id=',$domainId);
	    $this->db->delete('uptime');
	    //delete from uptime stats
		$this->db->flush_cache();
	    $this->db->where('domain_id=',$domainId);
	    $this->db->delete('uptime_stats');
	    //DELETE from user domain
	    $this->db->flush_cache();
	    $this->db->where('domain_id=',$domainId);
	    $this->db->delete('user_domain');


	    $test        = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
	    $url_to_test = $domainDetails[0]->domain_name;
	    $testid = $test->test(array(
	        'url' => $url_to_test,
	    ));
	    $test->delete();

	    //delete from gtmetrix
	    $this->db->where('url=',$domainDetails[0]->domain_name);
	    $this->db->delete('gtmetrix');

	    //delete from gtmetrix api count
	    $this->db->where('domain_id=',$domainId);
	    $this->db->delete('gtmetrix_api_count');



	}

	
}
?>
