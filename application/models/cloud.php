<?php
/**
 * Cloud Model Class
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */

class Cloud extends CI_Model {
	
	function __construct()
	{
		# code...
	}

	function get_clouds($userID) {
		$this->db->select('clouds.*,users.username', FALSE)
			->from('clouds')
			->where('clouds.userID',$userID)
			->join('users', 'users.userID=clouds.userID','left')
			->order_by('cID','desc');
		
		$query = $this->db->get();
		return $query->result_array();
	}

	function get_clouds_count(){
		$this->db->select()->from('clouds');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function insert_cloud($data) {
		$this->db->insert('clouds', $data);
		return $this->db->insert_id();
	}

	function get_cloud($cID){
		$this->db->select()->from('clouds')->where('cID',$cID);
		$query = $this->db->get();
		return $query->first_row('array');
	}

	function update_cloud($cID,$data){
		$this->db->where('cID',$cID);
		$this->db->update('clouds',$data);
	}

	function delete_cloud($cID){
		$this->db->where('cID',$cID);
		$this->db->delete('clouds');
	}

	function add_user($data){
		$this->db->insert('permissions',$data);
	}

	function delete_user($ucID){
		$this->db->where('ucID',$ucID);
		$this->db->delete('permissions');
	}

	function user_exists($cID,$userID){
		$this->db->select()->from('permissions')
			->where('useriD',$userID)
			->where('cID',$cID);
		$query = $this->db->get();
		return $query->first_row('array');
	}

	function get_cloud_by_user($userID){
		$this->db->select('permissions.*,clouds.*')
			->from('clouds')
			->where('permissions.userID',$userID)
			->join('permissions','clouds.cID=permissions.cID','left');

		$query = $this->db->get();
		return $query->result_array();
	}


	function get_users_by_cloud($cID) {
		$this->db->select('permissions.*,users.*')
			->from('permissions')
			->where('cID',$cID)
			->join('users','users.userID=permissions.userID','left')
			->order_by('pID','asec');
		
		$query = $this->db->get();
		return $query->result_array();
	}

}

?>