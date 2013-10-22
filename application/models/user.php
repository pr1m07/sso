<?php
/**
 * User Model Class
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */

class User extends CI_Model {
	
	function create_user($data){
		$query = $this->db->insert('users',$data);
		return $this->db->insert_id();
	}

	function update_user($userID,$data){
		$this->db->where('userID', $userID);
		$this->db->update('users',$data);
	}

	function delete_user($userID){
		$this->db->where('userID',$userID);
		$this->db->delete('users');
	}
	
	function login($username,$password){
		$where = array(
			'username' => $username,
			'password' => $this->aes->encrypt($password)
		);
		$this->db->select()->from('users')->where($where);
		$query = $this->db->get();
		return $query->first_row('array');
	}

	function user_exists($uid){
		$this->db->select()->from('users')->where('uid',$uid);
		$query = $this->db->get();
		return $query->first_row('array');
	}

	function get_user($userID) {
		$this->db->select()->from('users')->where('userID',$userID);
		$query = $this->db->get();
		return $query->first_row();
	}

	function get_users() {
		$this->db->select()->from('users')->order_by('userID','asec');
		$query = $this->db->get();
		return $query->result_array();
	}

	function get_users_count(){
		$this->db->select()->from('users');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_social_keys($name){
		$this->db->select('data')->from('settings')->like('name',$name);
		$query = $this->db->get();
		return $query->result();
	}

	function get_settings(){
		$this->db->select('data')->from('settings');
		$query = $this->db->get();
		return $query->result_array();
	}

	function update_settings($name,$row,$data){
		$this->db->where('name', $name);
		$this->db->update('settings', array($row=>$data));
	}
}


?>