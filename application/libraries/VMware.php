<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Keystone Class
 *
 * Work with remote servers via cURL
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */

class VMware
{
	private $response = '';
	private $url;

	function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', 'VMware Class Initialized');
	}
	
	public function auth($url='', $username, $organization, $password, $admin_token, $cID, $admin='')
	{	
		$this->CI->curl->create($url.'api/session');
		$this->CI->curl->http_header('Accept:application/*+xml;version=5.5');
		$this->CI->curl->http_header('x-vcloud-authorization:' . $admin_token);
		$this->CI->curl->option(CURLOPT_SSL_VERIFYPEER, false);
		//$this->CI->curl->option(CURLOPT_FAILONERROR, FALSE);
		$this->CI->curl->option(CURLOPT_HEADER, true);
		$response = $this->CI->curl->execute();
		//$this->CI->curl->debug();
		
		if($this->on_error($response)==true || $admin_token == '')
		{
			$this->CI->curl->create($url.'api/sessions');
			$this->CI->curl->http_header('Accept:application/*+xml;version=5.5');
			
			if($admin){
				$this->CI->curl->option(CURLOPT_USERPWD, "$username@$organization:$password");
			} else {
				// this works only if not SAML user
				//$org = $this->CI->db->select('user_tenant')->from('clouds')->where('cID',$cID)->get()->row();
				//$this->CI->curl->option(CURLOPT_USERPWD, "$username@$org->user_tenant:$password");
			}
			$this->CI->curl->option(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			$this->CI->curl->option(CURLOPT_HEADER, true);
			$this->CI->curl->option(CURLOPT_POST,true);
			//$this->CI->curl->option(CURLOPT_HTTPHEADER, array("Authorization: Basic " . base64_encode($username . "@System:" . $password)));
			$this->CI->curl->option(CURLOPT_SSL_VERIFYPEER, false);
			$response = $this->CI->curl->execute();
			
			preg_match('/x-vcloud-authorization: (.*)/',$response, $token);
			preg_match('/urn:vcloud:user:(.*)" user=/',$response, $ucID);
			
			if($admin){
				$this->store_credentials_in_db(trim($token[1]),$cID);
			} else {
				// this works only if not SAML user
				//$this->store_user_credentials_in_db(trim($token[1]),$ucID[1]);	
			}
			// if added cos of SAML implementation
			if($admin)				
				return trim($token[1]);
		} else {		
			if($admin)
				$token = $this->load_credentials_from_db($cID);
			else {
				// this works only if not SAML user
				//preg_match('/urn:vcloud:user:(.*)" user=/',$response, $ucID);
				//$token = $this->load_user_credentials_from_db($ucID[1]);
			}
				
			return $token;
		}
	}
	
	public function create_user($url='', $admin_token, $user)
	{			
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<vcloud:User xmlns:vcloud="http://www.vmware.com/vcloud/v1.5" name="'.$user['name'].'" operationKey="operationKey">
			<vcloud:IsEnabled>'.$user['enabled'].'</vcloud:IsEnabled>
			<vcloud:IsLocked>false</vcloud:IsLocked>
			<vcloud:IsExternal>false</vcloud:IsExternal>
			<vcloud:ProviderType>SAML</vcloud:ProviderType>
			<vcloud:StoredVmQuota>10</vcloud:StoredVmQuota>
			<vcloud:DeployedVmQuota>5</vcloud:DeployedVmQuota>
			<vcloud:Role href="'.$url.'api/admin/role/1bf4457f-a253-3cf1-b163-f319f1a31802" name="vApp Author" type="application/vnd.vmware.admin.role+xml" />
			<vcloud:Password>'.$user['password'].'</vcloud:Password>
		</vcloud:User>';
		
		$this->CI->curl->create($url.'api/admin/org/'.$user['organization'].'/users');
		$this->CI->curl->option(CURLOPT_SSL_VERIFYPEER, false);
		$this->CI->curl->option(CURLOPT_POST,true);
		$this->CI->curl->option(CURLOPT_POSTFIELDS, $xml);
		$this->CI->curl->option(CURLOPT_FAILONERROR, FALSE);
		// debug
		//$this->CI->curl->option(CURLOPT_HEADER, true);
		//$this->CI->curl->option(CURLINFO_HEADER_OUT, true);
		//$this->CI->curl->option(CURLOPT_VERBOSE, true);
		$this->CI->curl->http_header('Accept:application/*+xml;version=5.5');
		$this->CI->curl->http_header('x-vcloud-authorization:' . $admin_token);
		$this->CI->curl->http_header('Content-Type: application/vnd.vmware.admin.user+xml; charset=UTF-8');
		
		$response = $this->CI->curl->execute();
			
		if($this->check_error($response))
			return false;
		
		$xml = new SimpleXmlElement($response);
		$cuID = end(array_filter(explode("/", $xml->Link['href'])));
		
		//$this->CI->curl->debug();
		return $cuID;
	}
	
	public function get_group_id($url='', $admin_token)
	{
		$this->CI->curl->create($url.'api/org/');
		$this->CI->curl->http_header('Accept: application/*+xml;version=5.5');
		$this->CI->curl->http_header('x-vcloud-authorization:' . $admin_token);
		$this->CI->curl->option(CURLOPT_SSL_VERIFYPEER, false);
		$response = $this->CI->curl->execute();
		
		if($this->check_error($response))
			return false;
		
		$xml = new SimpleXmlElement($response);
		$organization = end(array_filter(explode("/", $xml->Org['href'])));
		
		return $organization;
	}
	
	public function delete_user($url='', $admin_token, $user)
	{
		$this->CI->curl->create($url. 'api/admin/user/'.$user);
		$this->CI->curl->http_header('Accept:application/*+xml;version=5.5');
		$this->CI->curl->http_header('x-vcloud-authorization:' . $admin_token);
		$this->CI->curl->option(CURLOPT_CUSTOMREQUEST, 'DELETE');
		$this->CI->curl->option(CURLOPT_SSL_VERIFYPEER, false);
		$response = $this->CI->curl->execute();
		//$this->CI->curl->debug();
		
		if($this->check_error($response))
			return false;
		
		return $response->user;
	}
	
	public function user_exists($url='', $admin_token, $username)
	{
		if(!$this->get_user_id($url,$admin_token,$username)){
			return false;
		} else 
			return true;
	}
	
	public function get_user($url='', $admin_token, $username)
	{
		$this->CI->curl->create($url.'api/admin/user/'.$this->get_user_id($url,$admin_token,$username));
		$this->CI->curl->http_header('Accept: application/*+xml;version=5.5');
		$this->CI->curl->http_header('x-vcloud-authorization:' . $admin_token);
		$this->CI->curl->option(CURLOPT_SSL_VERIFYPEER, false);
		$response = $this->CI->curl->execute();
		//$this->CI->curl->debug();
		
		$xml = new SimpleXmlElement($response);
		
		$this->CI =& get_instance();
		$ucID = end(array_filter(explode("/", $xml->Link['href'])));
		$organization = $this->CI->db->select('tenant')->from('permissions')->where('ucID',$ucID)->get()->row();
		
		$response = (object) array( 'user' => (object) array( 
					'name' => (string)$xml['name'],
					'role' => (string)$xml->Role['name'],
					'StoredVmQuota' => (string)$xml->StoredVmQuota,
					'DeployedVmQuota' => (string)$xml->DeployedVmQuota,
					'id' =>  $ucID,
					'tenantId' => $organization->tenant,
					'enabled' => 1
					));
							
		return $response;
	}
	
	public function get_user_id($url='', $admin_token, $username)
	{
		$this->CI->curl->create($url.'api/admin/users/query');
		$this->CI->curl->http_header('Accept: application/*+xml;version=5.5');
		$this->CI->curl->http_header('x-vcloud-authorization:' . $admin_token);
		$this->CI->curl->option(CURLOPT_SSL_VERIFYPEER, false);
		$response = $this->CI->curl->execute();
		//$this->CI->curl->debug();
		
		if($this->check_error($response))
			return false;
		
		$xml = new SimpleXmlElement($response);
		//$username = 'test'; // testing only
		foreach($xml->UserRecord as $user){
			if($user['name']==$username)
				return end(array_filter(explode("/", $user['href'])));
		}
	}
	
	function store_credentials_in_db($token,$cID){
	
		try{
			$this->CI =& get_instance();
			$this->CI->db->where('cID',$cID)->set('admin_token',$token)->update('clouds');
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
	}
	
	function load_credentials_from_db($cID){
	
		try {
			$this->CI =& get_instance();
			$token = $this->CI->db->select('admin_token')->from('clouds')->where('cID',$cID)->get()->row();
			
			return $token->admin_token;
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
	}
	
	function store_user_credentials_in_db($token, $ucID){
	
		try{
			$this->CI =& get_instance();
			$this->CI->db->where('ucID',$ucID)->set('token',$token)->update('permissions');
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
	}
	
	function load_user_credentials_from_db($ucID){
	
		try {
			$this->CI =& get_instance();
			$token = $this->CI->db->select('token')->from('permissions')->where('ucID',$ucID)->get()->row();
			
			return $token;
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
	}
/*

	public function delete_user($url='', $admin_token, $user)
	{
		$this->CI->curl->create($url. 'users/'.$user);
		$this->CI->curl->http_header('X-Auth-Token:' . $admin_token);
		$this->CI->curl->option(CURLOPT_CUSTOMREQUEST, 'DELETE');
		$response = $this->CI->curl->execute();

		if($this->check_error($response))
			return false;
		
		return $response->user;
	}

*/
	public function on_error($response)
	{	
		$this->CI->curl->error_code;
		$error = $this->CI->curl->error_string;

		if(!empty($error)){
			if($this->CI->curl->info['http_code']=='404'){
				return $this->CI->curl->info['http_code'];
			} else {
				return true;
			}
		}
	}
	
	public function check_error($response)
	{	
		$this->CI->curl->error_code;
		$error = $this->CI->curl->error_string;

		if(!empty($error)){
			if($this->CI->curl->info['http_code']=='404'){
				return $this->CI->curl->info['http_code'];
			} else {
				show_error('The requested URL returned error: ' . $this->CI->curl->info['http_code']);
				return true;
			}
		}
	}
/*
	
	public function get_metadata($cID)
	{

		try {
			$this->CI =& get_instance();
			$metadata = $this->CI->db->select('metadata')->from('clouds')->where('cID',$cID)->get()->row();
			
			$metadata = $this->convert_xml_to_metadata($metadata->metadata);
			
			return $metadata;
			
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}

	}
	
	function convert_xml_to_metadata($xmldata)
	{
		$config = SimpleSAML_Configuration::getInstance();
	
		if($xmldata) {
			$xmldata = htmlspecialchars_decode($xmldata);
			
			SimpleSAML_Utilities::validateXMLDocument($xmldata, 'saml-meta');
			$entities = SimpleSAML_Metadata_SAMLParser::parseDescriptorsString($xmldata);
	
			foreach($entities as &$entity) {
				$entity = array(
					'shib13-sp-remote' => $entity->getMetadata1xSP(),
					'shib13-idp-remote' => $entity->getMetadata1xIdP(),
					'saml20-sp-remote' => $entity->getMetadata20SP(),
					'saml20-idp-remote' => $entity->getMetadata20IdP(),
					);
		
			}
			
			$output = array( $entity['saml20-sp-remote']['entityid'] => $entity['saml20-sp-remote']);
		
		} else {
			$xmldata = '';
			$output = array();
		}
	
	return $output;		
	}

*/
	public function login($url, $cID)
	{		
		
		$as = new SimpleSAML_Auth_Simple('example-static');
		$as->requireAuth();
		
		$attributes = $as->getAttributes();
		//$this->get_metadata($cID);

		$this->CI->session->set_userdata('cID',$cID);
		
		//$config = SimpleSAML_Configuration::getInstance();
		//$session = SimpleSAML_Session::getInstance();
		//echo "<pre>"; print_r($session); echo "</pre>"; die();
		
		redirect($url);
	}

}