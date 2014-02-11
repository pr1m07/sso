<?php
/**
 * Admin Class
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */
class Admin extends CI_Controller {
	
	function Admin() {
		parent::__construct();
		$this->load->model('user');
		$this->load->model('cloud');
	}

	function index(){
		//$this->openstack->auth('admin','admin','http://192.168.2.137:5000/v2.0/');

		if($this->session->userdata('user_type')!='admin'){
			redirect(base_url().'users/dashboard');
		}
		$userID = $this->session->userdata('userID');
		$data['clouds'] = $this->cloud->get_clouds($userID);

		$this->load->view('admin/cloud_index', $data);
	}

	function cloud($cID){

		$data['errors'] = "";
		$data['success'] = "";

		$data['cloud'] = $this->cloud->get_cloud($cID);
		$data['users'] = $this->cloud->get_users_by_cloud($cID);

		$data['cinfo'] = array();
		
		foreach ($data['users'] as $key => $value) {
			$data['cinfo'][$value['userID']] = ($this->viewuser($cID,$value['username']));
		}

		$this->load->view('admin/cloud',$data);
	}

	function new_cloud(){
		if($this->session->userdata('logged_in')!=TRUE){
			redirect(base_url().'users/login');
		}
		
		$type = $this->input->post('type', true);

		if($_POST){

			$data = array(
				'userID' => $this->session->userdata('userID'),
				'type' => $this->input->post('type', true),
				'name' => $this->input->post('name', true),
				'endpoint' => $this->input->post('endpoint', true),
				'dashboard' => $this->input->post('dashboard', true),
				'admin_user' => ($type == "OpenStack" ? $this->input->post('admin_user_os', true) : $this->input->post('admin_user', true)),
				'admin_pass' => ($type == "OpenStack" ? $this->input->post('admin_pass_os', true) : $this->input->post('admin_pass', true)),
				'admin_token' => ($type == "OpenStack" ? $this->input->post('admin_token_os', true) : $this->input->post('admin_token', true)),
				'user_tenant' => ($type == "OpenStack" ? $this->input->post('user_tenant_os', true) : $this->input->post('user_tenant', true)),
				'metadata' => $_POST['metadata'],
				'active' => $this->input->post('active', true)
			);
			
			$cID = $this->cloud->insert_cloud($data);
			
			if($this->input->post('type', true) == "VMware"){
				$cloud = $this->cloud->get_cloud($cID);
				$this->vmware->auth($cloud['endpoint'],$cloud['admin_user'],$cloud['user_tenant'],$cloud['admin_pass'],'',$cID,true);
			}
			
			redirect(base_url().'admin/index');
		} else 
			$this->load->view('admin/new_cloud');
	}

	function editcloud($cID){	
		if($this->session->userdata('logged_in')!=TRUE){
			redirect(base_url().'users/login');
		}	
		
		$type = $this->input->post('type', true);
				
		// updating cloud settings
		$data['success'] = 0;
		if($_POST){
			$data_cloud = array(
				'userID' => $this->session->userdata('userID'),
				'type' => $this->input->post('type', true),
				'name' => $this->input->post('name', true),
				'endpoint' => $this->input->post('endpoint', true),
				'dashboard' => $this->input->post('dashboard', true),
				'admin_user' => ($type == "OpenStack" ? $this->input->post('admin_user_os', true) : $this->input->post('admin_user', true)),
				'admin_pass' => ($type == "OpenStack" ? $this->input->post('admin_pass_os', true) : $this->input->post('admin_pass', true)),
				'admin_token' => ($type == "OpenStack" ? $this->input->post('admin_token_os', true) : $this->input->post('admin_token', true)),
				'user_tenant' => ($type == "OpenStack" ? $this->input->post('user_tenant_os', true) : $this->input->post('user_tenant', true)),
				'metadata' => $_POST['metadata'],
				'active' => $this->input->post('active', true)
			);
			//echo "<pre>"; print_r($data_cloud['metadata']); die(); echo "</pre>";
			$this->cloud->update_cloud($cID,$data_cloud);
			
			if($this->input->post('type', true) == "VMware"){
				$cloud = $this->cloud->get_cloud($cID);
				$this->vmware->auth($cloud['endpoint'],$cloud['admin_user'],$cloud['user_tenant'],$cloud['admin_pass'],'',$cID,true);
			}
			
			$data['success'] = 1;			
		}

		$data['cloud'] = $this->cloud->get_cloud($cID);

		$this->load->view('admin/edit_cloud',$data);
	}

	function deletecloud($cID){
		if($this->session->userdata('logged_in')!=TRUE){
			redirect(base_url().'users/login');
		}
		$user_type = $this->session->userdata('user_type');
		if($user_type != 'admin'){
			redirect(base_url().'users/login');
		}
		
		$this->cloud->delete_cloud($cID);
		redirect(base_url().'admin');
	}
/*
	
	function auth_cloud($cID){
		$cloud = $this->cloud->get_cloud($cID);
			
		$token = $this->vmware->auth($cloud['endpoint'],$cloud['admin_user'],$cloud['user_tenant'],$cloud['admin_pass']);
		$data_token = array( 'admin_token' => $token );
		$this->cloud->store_token($cID,$data_token);
	}

*/
	function settings(){	
		if($this->session->userdata('logged_in')!=TRUE){
			redirect(base_url().'users/login');
		}

		$data['success'] = 0;

		if($_POST){
			$this->user->update_settings('facebook_app_id', 'data', $this->input->post('fb_id', true));
			$this->user->update_settings('facebook_app_secret_key', 'data', $this->input->post('fb_key', true));
			$this->user->update_settings('github_client_id', 'data', $this->input->post('git_id', true));
			$this->user->update_settings('github_client_secret_key', 'data', $this->input->post('git_key', true));
			$this->user->update_settings('google_client_id', 'data', $this->input->post('go_id', true));
			$this->user->update_settings('google_client_secret_id', 'data', $this->input->post('go_key', true));
			$data['success'] = 1;
		}
		$data['settings'] = $this->user->get_settings();
		$this->load->view('admin/settings',$data);
	}

	function linkuser($cID){
		if($this->session->userdata('user_type')!='admin'){
			redirect(base_url().'admin');
		}
    	$data['users'] = $this->user->get_users();

		$data['cloud'] = $this->cloud->get_cloud($cID);

		$this->load->view('admin/link_user',$data);
	}

	function generate_user_password($length=10){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr(str_shuffle($chars),0,$length);

		return $password;
	}
	
	function adduservm($cID,$userID){
		$local_user = $this->user->get_user($userID);
		$cloud = $this->cloud->get_cloud($cID);

		$data['success'] = "";
		$data['errors'] = "";

		$data['cloud'] = $this->cloud->get_cloud($cID);
		$data['users'] = $this->cloud->get_users_by_cloud($cID);

		$this->vmware->auth($cloud['endpoint'],$cloud['admin_user'],$cloud['user_tenant'],$cloud['admin_pass'],$cloud['admin_token'],$cID,true);

		$organization = $this->vmware->get_group_id($cloud['endpoint'],$cloud['admin_token']);


		$cuser = $this->vmware->user_exists($cloud['endpoint'],$cloud['admin_token'],$local_user->username);
		$luser = $this->cloud->user_exists($cID,$userID);

		if(!$luser){
			if($cuser) {
				$data['errors'] = "Username <strong>" . $local_user->username . "</strong> already exist in <strong>" .$cloud['name'] . "</strong> cloud. Please choose different username for this local user.";
				$this->load->view('admin/cloud',$data);
			} else {
				
				if($local_user->password==NULL){
					$password = $this->generate_user_password();
				} else {
					$password = $this->aes->decrypt($local_user->password);
				}

				$user = array( 
					'name' => $local_user->username,
					'fullname' => 'sso class user',
					'email' => $local_user->email,
					'password' => $password,
					'organization' => $organization,
					'enabled' => true
				);
								
				$ucID = $this->vmware->create_user($cloud['endpoint'],$cloud['admin_token'],$user);

				
				$cloud_user = array(
						'userID' => $userID, 
						'cID' => $cID,
						'ucID' => $ucID,
						'tenant' => $organization,
						'enabled' => 1
						);

				$this->cloud->add_user($cloud_user);


				$data['cloud'] = $this->cloud->get_cloud($cID);
				$data['users'] = $this->cloud->get_users_by_cloud($cID);
				$data['success'] = "User was successfuly added to the cloud";
				$this->load->view('admin/cloud',$data);
			}
		} else {
			$data['errors'] = "User <strong>" . $local_user->username . "</strong> already exists in <strong>" .$cloud['name'] . "</strong> cloud." ;
			$this->load->view('admin/cloud',$data);
		}


	}


	function adduseros($cID,$userID){
		$local_user = $this->user->get_user($userID);
		$cloud = $this->cloud->get_cloud($cID);

		$data['success'] = "";
		$data['errors'] = "";

		$data['cloud'] = $this->cloud->get_cloud($cID);
		$data['users'] = $this->cloud->get_users_by_cloud($cID);

		$tenant = $this->keystone->get_tenant($cloud['endpoint'],$cloud['admin_token'],$cloud['user_tenant']);

		$cuser = $this->keystone->get_user($cloud['endpoint'],$cloud['admin_token'],$local_user->username);

		$luser = $this->cloud->user_exists($cID,$userID);

		if(!$luser){
			if($cuser) {
				$data['errors'] = "Username <strong>" . $local_user->username . "</strong> already exist in <strong>" .$cloud['name'] . "</strong> cloud. Please choose different username for this local user.";
				$this->load->view('admin/cloud',$data);
			} else {
				
				if($local_user->password==NULL){
					$password = $this->generate_user_password();
				} else {
					$password = $this->aes->decrypt($local_user->password);
				}
				
				$user = array( 'user' => 
					array(
						'name' => $local_user->username,
						'description' => 'sso class user',
						'email' => $local_user->email,
						'password' => $password,
						'tenantId' => $tenant->id,
						'enabled' => True
						)
				);

				$response = $this->keystone->create_user($cloud['endpoint'],$cloud['admin_token'],json_encode($user));
				
				$cloud_user = array(
						'userID' => $userID, 
						'cID' => $cID,
						'ucID' => $response->id,
						'tenant' => $response->tenantId,
						'enabled' => $response->enabled
						);

				$this->cloud->add_user($cloud_user);

				$data['cloud'] = $this->cloud->get_cloud($cID);
				$data['users'] = $this->cloud->get_users_by_cloud($cID);
				$data['success'] = "User was successfuly added to the cloud";
				$this->load->view('admin/cloud',$data);
			}
		} else {
			$data['errors'] = "User <strong>" . $local_user->username . "</strong> already exists in <strong>" .$cloud['name'] . "</strong> cloud." ;
			$this->load->view('admin/cloud',$data);
		}

	}

	function viewuser($cID,$user){
		$cloud = $this->cloud->get_cloud($cID);
		
		if($cloud['type'] == "OpenStack"){
			$response = $this->keystone->get_user($cloud['endpoint'],$cloud['admin_token'],$user);
		} elseif($cloud['type'] == "VMware") {
			$response = $this->vmware->get_user($cloud['endpoint'],$cloud['admin_token'],$user);
		}

		return $response;
	}

	function deleteuser($cID,$ucID){
		$cloud = $this->cloud->get_cloud($cID);
		$this->cloud->delete_user($ucID);
		
		if($cloud['type'] == 'OpenStack')
			$response = $this->keystone->delete_user($cloud['endpoint'],$cloud['admin_token'],$ucID);
		else
			$response = $this->vmware->delete_user($cloud['endpoint'],$cloud['admin_token'],$ucID);

		redirect(base_url().'admin/cloud/'.$cID);
	}
	
	function auth($cID,$userID){

		$user = $this->user->get_user($userID);
		$cloud = $this->cloud->get_cloud($cID);

		if($cloud['type']=="OpenStack"){
			$response = $this->keystone->login($cloud['dashboard'],$user->username,$cloud['admin_pass']);
		} else {
			$response = $this->vmware->login($cloud['dashboard'],$cID);
		}
		
		print_r($response);
	}
}
?>