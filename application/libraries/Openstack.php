<?php
/**
 * CodeIgniter Openstack Class
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */

include('opencloud/php-opencloud.php');

class Openstack
{
	
	function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', 'cURL Class Initialized');
	}

	public function auth($username,$password,$endpoint){
		$this->os_username = $username;
		$this->os_password = $password;
		$this->os_endpoint = $endpoint;

		try {
			$this->conn = new \OpenCloud\OpenStack($this->os_endpoint, array(
                'username' => $this->os_username,
                'password' => $this->os_password
            ));
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
		print_r($this->conn->ExportCredentials());
	}
}



