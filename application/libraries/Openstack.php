<?php
/**
 * CodeIgniter Openstack Class
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */

include('opencloud/php-opencloud.php');
require('vendor/autoload.php');
use OpenCloud\Compute\Constants\ServerState;

class Openstack
{

	private $client;
	private $endpoint = "";
    private $credentials = array('username' => 'foo', 'password' => 'bar', 'tenantName' => 'demo');
	
	function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', 'cURL Class Initialized');
		
		$this->client = new \OpenCloud\OpenStack($this->endpoint, $this->credentials);
	}

	public function auth($username,$password,$endpoint, $ucID=''){
	
		$client = clone $this->client;
		
		//$client->getLogger()->setEnabled(true);
			
		$this->username = $username;
		$this->password = $password;
		$this->endpoint = $endpoint;

		$this->credentials = array(
                'username' => $this->username,
                'password' => $this->password,
                'tenantName' => 'demo'
            );
            
		try {
			$client = new \OpenCloud\OpenStack($this->endpoint,$this->credentials);
			
			//$client->authenticate();
			
			$credentials = $client->ExportCredentials();
			$this->store_credentials_in_cache($credentials,$ucID);
			
		} catch (\Guzzle\Http\Exception\BadResponseException $e) {
			
			$responseBody = (string) $e->getResponse()->getBody();
			$statusCode   = $e->getResponse()->getStatusCode();
			$headers      = $e->getResponse()->getHeaderLines();
			
			echo sprintf("Status: %s\nBody: %s\nHeaders: %s", $statusCode, $responseBody, implode(', ', $headers));
		}
	}
	
	public function list_images($cID){
	
		$client = clone $this->client;
		
		try {
			$credentials = $this->load_credentials_from_cache($cID);
			$client->ImportCredentials($credentials);
			
			$compute = $client->computeService('nova','RegionOne');
			//echo "<pre>"; print_r($compute); echo "</pre>";
			
			$imlist = $compute->ImageList(FALSE);
			//echo "<pre>"; print_r($imlist); echo "</pre>";

			return $imlist;
			
			/* Debug */
			//foreach($imlist as $image)
			//	printf("Image: %s id=%s\n", $image->name, $image->id);			
		
		} catch (\Guzzle\Http\Exception\BadResponseException $e) {
			
			$responseBody = (string) $e->getResponse()->getBody();
			$statusCode   = $e->getResponse()->getStatusCode();
			$headers      = $e->getResponse()->getHeaderLines();
			
			echo sprintf('Status: %s\nBody: %s\nHeaders: %s', $statusCode, $responseBody, implode(', ', $headers));
		}
	}
	
	public function list_flavor($cID){
	
		$client = clone $this->client;
		
		try {
			$credentials = $this->load_credentials_from_cache($cID);
			$client->ImportCredentials($credentials);
			
			$compute = $client->computeService('nova','RegionOne');
			
			$flist = $compute->FlavorList();
			
			return $flist;
			
			/* Debug */
			//foreach($flist as $flavor)
			//	printf("Image: %s id=%s\n", $flavor->name, $flavor->id);			
		
		} catch (\Guzzle\Http\Exception\BadResponseException $e) {
			
			$responseBody = (string) $e->getResponse()->getBody();
			$statusCode   = $e->getResponse()->getStatusCode();
			$headers      = $e->getResponse()->getHeaderLines();
			
			echo sprintf('Status: %s\nBody: %s\nHeaders: %s', $statusCode, $responseBody, implode(', ', $headers));
		}
	}
	
	public function new_server($name,$image,$flavor,$cID){
		$client = clone $this->client;
		
		try {
			$credentials = $this->load_credentials_from_cache($cID);
			$client->ImportCredentials($credentials);
			
			$compute = $client->computeService('nova','RegionOne');
			
			$server = $compute->Server();
			
			$response = $server->Create(array(
								'name' => $name,
								'flavor' => $compute->Flavor($flavor),
								'image' => $compute->Image($image)));
								
			
			//$server->waitFor(ServerState::ACTIVE, 600, $callback);
			$json = $response->getBody();
			return json_decode($json);
		
		} catch (\Guzzle\Http\Exception\BadResponseException $e) {
			
			$responseBody = (string) $e->getResponse()->getBody();
			$statusCode   = $e->getResponse()->getStatusCode();
			$headers      = $e->getResponse()->getHeaderLines();
			
			echo sprintf('Status: %s\nBody: %s\nHeaders: %s', $statusCode, $responseBody, implode(', ', $headers));
		}
		
	}
	
	public function delete_server($cID,$rmvID){
		$client = clone $this->client;
		
		try {
			$credentials = $this->load_credentials_from_cache($cID);
			$client->ImportCredentials($credentials);
			
			$compute = $client->computeService('nova','RegionOne');
			
			$server = $compute->server($rvmID);
			$server->delete();
		
		} catch (\Guzzle\Http\Exception\BadResponseException $e) {
			
			$responseBody = (string) $e->getResponse()->getBody();
			$statusCode   = $e->getResponse()->getStatusCode();
			$headers      = $e->getResponse()->getHeaderLines();
			
			echo sprintf('Status: %s\nBody: %s\nHeaders: %s', $statusCode, $responseBody, implode(', ', $headers));
		}
		
	}
	
	function store_credentials_in_cache($credentials,$ucID){
	
		try{
			$this->CI =& get_instance();
			$cred = serialize($credentials);
			$this->CI->db->where('ucID',$ucID)->set('token',$cred)->update('permissions');
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
	}
	
	function load_credentials_from_cache($cID){
	
		try {
			$this->CI =& get_instance();
			//$cred = $this->CI->db->select('token')->from('permissions')->where('userID',$this->CI->session->userdata('userID'))->get()->row();
			$cred = $this->CI->db->select('token')->from('permissions')->where('cID',$cID)->get()->row();
			
			$credentials = unserialize($cred->token);
			return $credentials;
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
	}
}