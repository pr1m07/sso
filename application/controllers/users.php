<?php
/**
 * Users Class
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */

class Users extends CI_Controller {
	
	function Users() {
		parent::__construct();
		$this->load->model('user');
		$this->load->model('cloud');
    }



    function index(){
    	if($this->session->userdata('user_type')!='admin'){
			redirect(base_url().'users/dashboard');
		}
    	$data['users'] = $this->user->get_users();

		$this->load->view('users/user_index', $data);
    }

    function dashboard(){

    	if($this->session->userdata('logged_in')!=TRUE){
			redirect(base_url().'users/login');
		}

		$userID = $this->session->userdata('userID');
		$data['clouds'] = $this->cloud->get_cloud_by_user($userID);
    	$this->load->view('users/dashboard',$data);
    }

	function auth($cID,$userID){

		$user = $this->user->get_user($userID);
		$cloud = $this->cloud->get_cloud($cID);
		
		$password = $this->aes->decrypt($user->password);
		
		if($cloud['type']=="OpenStack"){
			$response = $this->keystone->login($cloud['dashboard'],$user->username,$password);
		} else {
			$response = $this->vmware->login($cloud['dashboard'],$cID);
		}
		
		print_r($response);
	}

   	function session($provider) {

        $this->load->helper('url_helper');

		$keys = $this->user->get_social_keys($provider);

		$provider_type = $provider;

        $provider = $this->oauth2->provider($provider, array(
            'id' => $keys[0]->data,
            'secret' => $keys[1]->data,
        ));

        if ( ! $this->input->get('code'))
        {
            // By sending no options it'll come back here
            $provider->authorize();
        }
        else
        {
            try
            {
                $token = $provider->access($_GET['code']);
                $outh_user = $provider->get_user_info($token);

                $user = $this->user->user_exists($outh_user['uid']);

                if(!$user){
					echo "<pre>"; print_r($outh_user); echo "</pre>";
                	if($provider_type == 'facebook' || $provider_type == 'google' || $provider_type == 'github'){
                		$first_name = $provider_type == 'github' ? $outh_user['name'] : $outh_user['first_name'];
                		$last_name = $provider_type == 'github' ? '' : $outh_user['last_name'];
                		$image = $provider_type == 'github' ? '' : $outh_user['image'];
                		$data = array(
                			'email' => $outh_user['email'],
                			'username' => $outh_user['nickname'],
                			'first_name' => $first_name,
                			'last_name' => $last_name,
                			'user_type' => 'user',
                			'uid' => $outh_user['uid'],
                			'service' => $provider_type,
                			'avatar' => $image
                			);

                		$userid = $this->user->create_user($data);
                		$this->session->set_userdata('userID',$userid);
                		$this->session->set_userdata('username',$data['username']);
                		$this->session->set_userdata('user_type',$data['user_type']);
                		$this->session->set_userdata('avatar',$data['avatar']);
                		$this->session->set_userdata('logged_in',TRUE);
                		redirect(base_url().'users/dashboard');	
                	}

				} else {
					$this->session->set_userdata('userID',$user['userID']);
					$this->session->set_userdata('username',$user['username']);
					$this->session->set_userdata('user_type',$user['user_type']);
					$this->session->set_userdata('avatar',$data['avatar']);
					$this->session->set_userdata('logged_in',TRUE);
					if($user['user_type']=='admin')
						redirect(base_url().'admin/index');
					else
						redirect(base_url().'users/dashboard');
				}
				/*
                echo "<pre>Tokens: ";
                var_dump($token);

                echo "\n\nUser Info: ";
                var_dump($outh_user);
                */
            }

            catch (OAuth2_Exception $e)
            {
                show_error('That didnt work: '.$e);
            }

        }
    }
	
	function login(){

		if($this->session->userdata('logged_in')==TRUE){
			redirect(base_url().'users/dashboard');
		}

		$data['error']=0;

		$data['settings'] = $this->user->get_settings();

		if($_POST){
			
			//$username = $_POST['username'];
			$username = $this->input->post('username',true);
			$password = $this->input->post('password', true);
			$user = $this->user->login($username,$password);
			if(!$user){
				$data['error']=1;
			} else {
				$this->session->set_userdata('userID',$user['userID']);
				$this->session->set_userdata('username',$user['username']);
				$this->session->set_userdata('user_type',$user['user_type']);
				$this->session->set_userdata('avatar',$user['avatar']);
				$this->session->set_userdata('logged_in',TRUE);
				if($user['user_type']=='admin')
					redirect(base_url().'admin/index');
				else
					redirect(base_url().'users/dashboard');
			}
		}
		
		$this->load->view('users/login',$data);
	}
	
	function logout(){
		$this->session->sess_destroy();
		redirect(base_url());
	}

	function delete($userID){
		if($this->session->userdata('user_type')!='admin'){
			redirect(base_url().'users/dashboard');
		}
		$this->user->delete_user($userID);
		redirect(base_url().'users/');
	}
	
	function register(){
		$data['errors'] = '';
		$data['settings'] = $this->user->get_settings();
		if($_POST){
			
			$config = array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'trim|required|min_length[3]|is_unique[users.username]'
				),
				array(
					'field' => 'first_name',
					'label' => 'First Name',
					'rules' => 'trim|required|min_length[2]'
				),
				array(
					'field' => 'last_name',
					'label' => 'Last Name',
					'rules' => 'trim|required|min_length[2]'
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[5]' 
				),
				array(
					'field' => 'password2',
					'label' => 'Repeat password',
					'rules' => 'trim|required|min_length[5]|matches[password]' 
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|is_unique[users.email]|valid_email' 
				)
			);
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($config);

			if($this->form_validation->run()==false){
				$data['errors'] = validation_errors();
			} else {		
				$data = array(
					'email' => $this->input->post('email',true),
					'username' => $this->input->post('username',true),
					'first_name' => $this->input->post('first_name',true),
					'last_name' => $this->input->post('last_name',true),
					'password' => $this->aes->encrypt($this->input->post('password',true)),
					'service' => 'local',
					'user_type' => 'user'
				);
				$userid = $this->user->create_user($data);
				$this->session->set_userdata('userID',$userid);
				$this->session->set_userdata('username',$data['username']);
				$this->session->set_userdata('user_type',$data['user_type']);
				$this->session->set_userdata('logged_in',TRUE);
				redirect(base_url().'users/dashboard');	
			}
		}
		$this->load->view('users/register',$data);
	}

	function edituser($userID){
		if($this->session->userdata('user_type')!='admin'){
			redirect(base_url().'users/dashboard');
		}
		$data['success'] = 0;
		$data['errors'] = '';
		if($_POST){
			
			$config = array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'trim|required|min_length[3]|[users.username]'
				),
				array(
					'field' => 'first_name',
					'label' => 'First Name',
					'rules' => 'trim|required|min_length[2]'
				),
				array(
					'field' => 'last_name',
					'label' => 'Last Name',
					'rules' => 'trim|required|min_length[2]'
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|min_length[5]' 
				),
				array(
					'field' => 'password2',
					'label' => 'Repeat password',
					'rules' => 'trim|min_length[5]|matches[password]' 
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|valid_email' 
				)
			);
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($config);

			if($this->form_validation->run()==false){
				$data['errors'] = validation_errors();
			} else {
				$user = $this->user->get_user($userID);
				if($this->input->post('password',true)=='') {
					$password = $user->password;
				} else {
					$password = $this->aes->encrypt($this->input->post('password',true));
				}
				$data = array(
					'email' => $this->input->post('email',true),
					'username' => $this->input->post('username',true),
					'first_name' => $this->input->post('first_name',true),
					'last_name' => $this->input->post('last_name',true),
					'password' => $password,
					'user_type' => $this->input->post('type',true),
					'avatar' => $this->input->post('avatar',true)
				);
				$this->user->update_user($userID,$data);
				$data['success'] = 1;
				$data['errors'] = '';
			}
		}
		$data['user'] = $this->user->get_user($userID);
		$this->load->view('users/edit_user',$data);
	}

	function editprofile($userID){
		if($this->session->userdata('userID')!=$userID){
			redirect(base_url().'users/dashboard');
		}
		$data['success'] = 0;
		$data['errors'] = '';
		if($_POST){
			
			$config = array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'trim|required|min_length[3]|[users.username]'
				),
				array(
					'field' => 'first_name',
					'label' => 'First Name',
					'rules' => 'trim|required|min_length[2]'
				),
				array(
					'field' => 'last_name',
					'label' => 'Last Name',
					'rules' => 'trim|required|min_length[2]'
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|min_length[5]' 
				),
				array(
					'field' => 'password2',
					'label' => 'Repeat password',
					'rules' => 'trim|min_length[5]|matches[password]' 
				)
			);
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($config);

			if($this->form_validation->run()==false){
				$data['errors'] = validation_errors();
			} else {
				$user = $this->user->get_user($userID);
				if($this->input->post('password',true)=='') {
					$password = $user->password;
				} else {
					$password = $this->aes->encrypt($this->input->post('password',true));
				}
				$data = array(
					'username' => $this->input->post('username',true),
					'first_name' => $this->input->post('first_name',true),
					'last_name' => $this->input->post('last_name',true),
					'password' => $password,
					'avatar' => $this->input->post('avatar',true)
				);
				$this->session->set_userdata('avatar',$this->input->post('avatar',true));
				$this->user->update_user($userID,$data);
				$data['success'] = 1;
				$data['errors'] = '';
			}
		}
		$data['user'] = $this->user->get_user($userID);
		$this->load->view('users/edit_profile',$data);
	}

	function profile($userID){
		$data['user'] = $this->user->get_user($userID);
		$this->load->view('users/user_profile',$data);
	}

	function new_user(){
		if($this->session->userdata('user_type')!='admin'){
			redirect(base_url().'users/dashboard');
		}
		$data['errors'] = '';
		if($_POST){
			
			$config = array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'trim|required|min_length[3]|is_unique[users.username]'
				),
				array(
					'field' => 'first_name',
					'label' => 'First Name',
					'rules' => 'trim|required|min_length[2]'
				),
				array(
					'field' => 'last_name',
					'label' => 'Last Name',
					'rules' => 'trim|required|min_length[2]'
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[5]' 
				),
				array(
					'field' => 'password2',
					'label' => 'Repeat password',
					'rules' => 'trim|required|min_length[5]|matches[password]' 
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|is_unique[users.email]|valid_email' 
				)
			);
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($config);

			if($this->input->post('type', true)==0){
				$type = "user"; 
			}

			if($this->form_validation->run()==false){
				$data['errors'] = validation_errors();
			} else {		
				$data = array(
					'email' => $this->input->post('email',true),
					'username' => $this->input->post('username',true),
					'first_name' => $this->input->post('first_name',true),
					'last_name' => $this->input->post('last_name',true),
					'password' => $this->aes->encrypt($this->input->post('password',true)),
					'service' => 'local',
					'user_type' => $type
				);
				$userid = $this->user->create_user($data);
				redirect(base_url().'users');	
			}
		}
		$this->load->view('users/new_user',$data);
	}

}


?>