<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter AES Class
 *
 * AES encryption
 *
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */

class AES
{

	private $CI;
	private $encryption_key	= '';
	private $key = '';
	private $_mcrypt_exists = FALSE;
	private $data;

	function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', 'AES Class Initialized');
		$this->_mcrypt_exists = ( ! function_exists('mcrypt_encrypt')) ? FALSE : TRUE;
	}

	function get_key($key = '')
	{
		if ($key == '')
		{
			if ($this->encryption_key != '')
			{
				return $this->encryption_key;
			}

			$CI =& get_instance();
			$key = $CI->config->item('encryption_key');

			if ($key == FALSE)
			{
				show_error('In order to use the encryption class requires that you set an encryption key in your config file.');
			}
		}

		return $key;
	}

	public function encrypt($data,$key = '') 
	{
		$key = $this->get_key($key);
		if ($this->_mcrypt_exists === TRUE) {
			if (!empty($data)) {
				return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
			} else {
				throw new Exception('Unable to encrypt, no data set.');
			}
		} else {
			throw new Exception('Mcrypt library does not exist!');
		}
	}

	public function decrypt($data, $key = '') 
	{
		$key = $this->get_key($key);
		if ($this->_mcrypt_exists === TRUE) {
			if (!empty($data)) {
				return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
			} else {
				throw new Exception('Unable to decrypt, no data set.');
			}
		} else {
			throw new Exception('Mcrypt library does not exist!');
		}
	}

}