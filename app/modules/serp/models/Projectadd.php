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

class Projectadd extends CI_Model 
{
	// get user by their social media id
	function insertproject($userid,$name,$title,$description,$website,$keywords,$enginee,$websiteid)
	{
		$query = $this->db->query("insert into project (iUserId,name,title,description,vWebsite,vKeywords,vEnginee,websiteid,vRecordDate,vUpdateDate) values('".$userid."','".$name."','".$title."','".$description."','".$website."','".$keywords."','".$enginee."','".$websiteid."','".date('d-m-Y')."','".date('d-m-Y')."')");
		return 1;
	}
	
	function insertprojectkey($keywordid,$keywords,$websiteid)
	{
		$query = $this->db->query("insert into keywords (keywordid,keywordname,websiteid) values('".$keywordid."','".$keywords."','".$websiteid."')");
		return 1;
	}
	
	function updateprojectkey($keywordid,$keywords,$websiteid)
	{
		
		$query = $this->db->query("UPDATE keywords SET keywordname = '".$keywords."' WHERE keywordid='".$keywordid."' AND websiteid='".$websiteid."'");
		return 1;
	}
	
	
	
	function getproject(){
		$query = $this->db->query("SELECT * FROM project WHERE iStatus='0'");
		return $query->result();
	}
	
	function getprojectnames($userid){
		$query = $this->db->query("SELECT * FROM project WHERE iStatus='0' AND iUserId='".$userid."'");
		return $query->result();
	}
	
	function getkeyword($id){
		$query = $this->db->query("SELECT * FROM keywords WHERE websiteid='".$id."'");
		return $query->result();
	}
	
	
	function deleteproject($id){
		$query = $this->db->query("UPDATE project SET iStatus = '1' WHERE iProjectId='".$id."'");
		return 1;
	}
	
	function deletekey($keywordid,$websiteid){
		$query = $this->db->query("DELETE FROM keywords WHERE keywordid = '".$keywordid."' AND websiteid = '".$websiteid."'");			
		return 1;
	}
	
	function getprojectdata($id){
		$query = $this->db->query("SELECT * FROM project WHERE iProjectId='".$id."'");
		return $query->result();
	}
	
	function getkeyworddata($id,$websiteid){
		$query = $this->db->query("SELECT * FROM keywords WHERE keywordid='".$id."' AND websiteid='".$websiteid."'");
		return $query->result();
	}
	
	function updateproject($userid,$name,$title,$description,$websiteid,$website,$keywords,$enginee,$projectid){
		$query = $this->db->query("UPDATE project SET name = '".$name."',title = '".$title."',description = '".$description."',vWebsite = '".$website."',vKeywords = '".$keywords."', vEnginee = '".$enginee."', iUserId = '".$userid."' WHERE iProjectId='".$projectid."'");
		return 1;
	}

	// Returns user by its email
	function get_user_by_email($email)
	{
		$query = $this->db->query("SELECT * FROM users u, user_profiles up WHERE u.email='$email' and u.id = up.user_id");
		return $query->result();
	}
	
	function get_user_by_username($username)
	{
		$query = $this->db->query("SELECT * FROM users u, user_profiles up WHERE u.username='$username' and u.id = up.user_id");
		return $query->result();
	}
	// a generic update method for user profile
	function update_user_profile($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('user_profiles', $data); 
	}

	// return the user given the id
	function get_user($user_id)
	{
		$this->db->select("u.*, up.*");
		$this->db->from("users AS u");
		$this->db->join("user_profiles AS up", "u.id=up.user_id");
		$this->db->where("up.user_id", $user_id);
		$query = $this->db->get();
		return $query->result();
	}
}
?>