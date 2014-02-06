<?php
/**
 * Cloud Class
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */

class Clouds extends CI_Controller {
	
	function Clouds() {
		parent::__construct();
		$this->load->model('user');
		$this->load->model('cloud');
    }
    
    function cloud($cID){

    	if($this->session->userdata('logged_in')!=TRUE){
			redirect(base_url().'users/login');
		}
		
		$user = $this->user->get_user($this->session->userdata('userID'));
		$data['cuser'] = (object) array ( 'user' => (object) $this->cloud->get_user_by_cloud($cID));
		
		$password = $this->aes->decrypt($user->password);
		
		$data['cloud'] = $this->cloud->get_cloud($cID);
		$data['virtual_machines'] = $this->cloud->get_vms_by_user($cID);

		if($data['cloud']['type']=="OpenStack"){
			//$data['cuser'] = $this->keystone->get_user($data['cloud']['endpoint'],$data['cloud']['admin_token'],$this->session->userdata('username'));
			//echo "<pre>"; print_r($data['cuser']); echo "</pre>";
			$this->openstack->auth($user->username, $password, $data['cloud']['endpoint'],$data['cuser']->user->ucID);	
		} else {
			//$this->vmware->auth($data['cloud']['endpoint'],$data['cloud']['admin_user'],$data['cloud']['user_tenant'],$data['cloud']['admin_pass'],$data['cloud']['admin_token'],'');
			//$data['cuser'] = $this->vmware->get_user($data['cloud']['endpoint'],$data['cloud']['admin_token'],$user->username);
			//echo "<pre>"; print_r($data['cuser']); echo "</pre>";
			$this->vmware->auth($data['cloud']['endpoint'], $user->username, $data['cuser']->user->tenant, $password, $data['cuser']->user->token,$data['cuser']->user->cID,false);
		}
		$this->load->view('clouds/cloud',$data);
	}
	
	function new_vm($cID){
	
		if($this->session->userdata('logged_in')!=TRUE){
			redirect(base_url().'users/login');
		}
		
		$data['success'] = "";
		if($_POST){

			$name = $this->input->post('name', true);
			$image = $this->input->post('image', true);
			$flavor = $this->input->post('flavor', true);
		
			$response = $this->openstack->new_server($name,$image,$flavor,$cID);
			
			$vmdata = array(
						'name' => $name,
						'cID' => $cID,
						'userID' => $this->session->userdata('userID'),
						'rvmID' => $response->server->id,
						'image' => $image,
						'flavor' => $flavor,
						'pass' => $response->server->adminPass
						);
			
			$this->cloud->add_vm($vmdata);
			
			$data['success'] = "Virtual machine <strong>" .$name. "</strong>  created successfully. Your temporary admin password is: <strong>" . $response->server->adminPass . "</strong>";
			$this->load->view('clouds/new_vm_success',$data);
		} else {
			$cloud = $this->cloud->get_cloud($cID);
		
			$imlist = $this->openstack->list_images($cID);
			$flist = $this->openstack->list_flavor($cID);
					
			$data['cloud'] = $cloud;
			$data['imlist'] = $imlist;
			$data['flist'] = $flist;
			
			$this->load->view('clouds/new_vm',$data);
		}
	}

}


?>