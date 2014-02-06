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


class Keystone
{
	private $response = '';
	private $url;
	
	function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', 'Keystone Class Initialized');
	}

	public function get_user($url='', $admin_token, $user)
	{	
		$this->CI->curl->create($url.'users/?name='.$user);
		$this->CI->curl->http_header('X-Auth-Token:' . $admin_token);
		$this->CI->curl->http_header('Content-type: application/json');
		$response = json_decode($this->CI->curl->execute());

		if($this->check_error($response))
			return false;

		return $response;
	}

	//curl -i http://193.138.1.205:35357/v2.0/users/ -X POST -H "Content-Type: application/json" -H "X-Auth-Token: atoken" -d 
	//'{"user":{"name":"primoz","description":"sso class user","email":"primoz@e5.ijs.si","password":"test123","tenantId":"68f2638ce047454d86ff3dc9e421d4a7","enabled":true}}'

	public function create_user($url='', $admin_token, $user)
	{	
		$this->CI->curl->create($url. 'users');
		$this->CI->curl->http_header('X-Auth-Token:' . $admin_token);
		$this->CI->curl->http_header('Content-type: application/json');
		$this->CI->curl->option(CURLOPT_POSTFIELDS,$user);
		$response = json_decode($this->CI->curl->execute());
		
		if($this->check_error($response))
			return false;
		
		return $response->user;
	}

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

	public function get_tenant($url='', $admin_token, $tenant)
	{	
		$this->CI->curl->create($url.'tenants/?name='.$tenant);
		$this->CI->curl->http_header('X-Auth-Token:'. $admin_token);
		$response = json_decode($this->CI->curl->execute());

		if($this->check_error($response))
			return false;

		return $response->tenant;
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

	public function login($url, $user, $pass){
		
		/*$data = array(
			'username' => $user,
			'password' => $pass,
			'csrfmiddlewaretoken' => 'tUPvlkrhOAGYClw67eeaad45gvA6eMpN'
		);

		$headers = array(
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*"."/*;q=0.8",
			"Accept-Language: sl,en-gb;q=0.7,en;q=0.3",
			"Accept-Encoding: gzip, deflate",
			"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
			"Keep-Alive: timeout=5, max=100",
			"Connection: keep-alive"
			);

		$this->CI->curl->create($url);
		$this->CI->curl->option(CURLOPT_POST,true);
		$this->CI->curl->option(CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:24.0) Gecko/20100101 Firefox/24.0');
		$this->CI->curl->option(CURLINFO_HEADER_OUT, true);
		$this->CI->curl->option(CURLOPT_HTTPHEADER, $headers);
		$this->CI->curl->option(CURLOPT_POSTFIELDS,$data);
		//$this->CI->curl->option(CURLOPT_USERPWD,$user.':'.$pass);
		$response = $this->CI->curl->execute();

		if($this->check_error($response))
			return false;
		
		return $response;*/

		$remote = file_get_contents($url);
		
		$remote = str_replace("\"", "'", $remote);
		preg_match_all("/<input .*hidden.*value='(.*?)'/", $remote, $matches, PREG_OFFSET_CAPTURE);
		
		$csrfmiddlewaretoken = $matches[1][0][0];
		$id_region = $matches[1][1][0];
		
		// openstack views.py set attribute => setattr(request, '_dont_enforce_csrf_checks', True)
		
		$form = '<html>
		<head>
		<script type="text/javascript">
    	function loadit() {
    	document.forms["myform"].submit();
	    }
    	</script>
		</head><body onload="loadit()">
		 <form id="myform" style="display:none" autocomplete="on" class="" action="'.$url.'/" method="POST"  >
		 	<!--input type="hidden" name="csrfmiddlewaretoken" value="'.$csrfmiddlewaretoken.'" /-->
		 	<input id="id_region" name="region" type="hidden" value="'.$id_region.'" />
		 	<input id="id_username" name="username" type="text" value="'.$user.'" />
    		<input id="id_password" name="password" type="password" value="'.$pass.'" />
    		<button type="submit" class="btn btn-primary pull-right">Sign In</button>
    	</form>
		</body></html>
		';
		
		return $form;

	}

}