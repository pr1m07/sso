<?php
/**
 * SAML 2.0 remote SP metadata for simpleSAMLphp.
 *
 * See: http://simplesamlphp.org/docs/trunk/simplesamlphp-reference-sp-remote
 * 
 * Modified by:
 * @author     Primož Cigoj
 * @copyright  (c) 2013 E5 IJS
 *
 */
 
define('BASEPATH',true);
$c = (object) unserialize($_COOKIE['ci_session']);

include_once($_SERVER["DOCUMENT_ROOT"] . '/application/config/database.php');

$DB = new mysqli( $db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database'] );

if ($DB->connect_error) {
    die('Connect Error (' . $DB->connect_errno . ') ' . $DB->connect_error);
}

$result = $DB->query(sprintf("SELECT metadata FROM clouds WHERE cID='%s'",mysql_real_escape_string($c->cID)));
$metadata = $result->fetch_row();

$metadata = convert_metadata($metadata[0]);

//debug_print_backtrace();

$DB->close();


function convert_metadata($xmldata)
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


/*

vCloud static example...

$metadata['https://193.138.1.230:443/cloud/org/test/saml/metadata/alias/vcd'] = array (
  'entityid' => 'https://193.138.1.230:443/cloud/org/test/saml/metadata/alias/vcd',
  'contacts' => 
  array (
  ),
  'metadata-set' => 'saml20-sp-remote',
  'AssertionConsumerService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://193.138.1.230:443/cloud/org/test/saml/SSO/alias/vcd',
      'index' => 0,
      'isDefault' => true,
    ),
    1 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:profiles:holder-of-key:SSO:browser',
      'Location' => 'https://193.138.1.230:443/cloud/org/test/saml/HoKSSO/alias/vcd',
      'index' => 1,
    ),
  ),
  'SingleLogoutService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://193.138.1.230:443/cloud/org/test/saml/SingleLogout/alias/vcd',
    ),
    1 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://193.138.1.230:443/cloud/org/test/saml/SingleLogout/alias/vcd',
    ),
    2 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
      'Location' => 'https://193.138.1.230:443/cloud/org/test/saml/SingleLogout/alias/vcd',
    ),
  ),
  'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
  'keys' => 
  array (
    0 => 
    array (
      'encryption' => false,
      'signing' => true,
      'type' => 'X509Certificate',
      'X509Certificate' => 'MIIB3TCCAUagAwIBAgIEQ9cg1jANBgkqhkiG9w0BAQUFADAzMTEwLwYDVQQDEyh2Q2xvdWQgRGly
ZWN0b3Igb3JnYW5pemF0aW9uIENlcnRpZmljYXRlMB4XDTE0MDEyNTE4MDQ1MVoXDTE1MDEyNTE4
MDQ1MVowMzExMC8GA1UEAxModkNsb3VkIERpcmVjdG9yIG9yZ2FuaXphdGlvbiBDZXJ0aWZpY2F0
ZTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAgFfSDrptYSwadcfzmyXViiBWc/2Z8IRMSnIk
DDPiyKnlXnblQnLb38SzoCToHZ9pEufza41bP0r3m8X5oypyt08c0ru9w9LS8t6JBD4AD8BxIVm5
3oHAAUVIvaczPuAnQqtKTqs5D85Rf0rUPeEcORUyda3yvomXgJKtWhLw/y0CAwEAATANBgkqhkiG
9w0BAQUFAAOBgQB6zQM8OO5LvUUQAMWucA8zNfmBWZH2t0C+VPOFmg9AnBxKa9ib3CrMhhgZ1Mrq
lRHoV81RboH80YtWgsyirDmD48ISNpLJ4+/HCnLsVSLp+Y27CDaIUuco0rL5TPevjf2+M0AIIIx8
YOyfOFAknue0l80gdh7d9mXrSbfNmOt4kQ==',
    ),
    1 => 
    array (
      'encryption' => true,
      'signing' => false,
      'type' => 'X509Certificate',
      'X509Certificate' => 'MIIB3TCCAUagAwIBAgIEQ9cg1jANBgkqhkiG9w0BAQUFADAzMTEwLwYDVQQDEyh2Q2xvdWQgRGly
ZWN0b3Igb3JnYW5pemF0aW9uIENlcnRpZmljYXRlMB4XDTE0MDEyNTE4MDQ1MVoXDTE1MDEyNTE4
MDQ1MVowMzExMC8GA1UEAxModkNsb3VkIERpcmVjdG9yIG9yZ2FuaXphdGlvbiBDZXJ0aWZpY2F0
ZTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAgFfSDrptYSwadcfzmyXViiBWc/2Z8IRMSnIk
DDPiyKnlXnblQnLb38SzoCToHZ9pEufza41bP0r3m8X5oypyt08c0ru9w9LS8t6JBD4AD8BxIVm5
3oHAAUVIvaczPuAnQqtKTqs5D85Rf0rUPeEcORUyda3yvomXgJKtWhLw/y0CAwEAATANBgkqhkiG
9w0BAQUFAAOBgQB6zQM8OO5LvUUQAMWucA8zNfmBWZH2t0C+VPOFmg9AnBxKa9ib3CrMhhgZ1Mrq
lRHoV81RboH80YtWgsyirDmD48ISNpLJ4+/HCnLsVSLp+Y27CDaIUuco0rL5TPevjf2+M0AIIIx8
YOyfOFAknue0l80gdh7d9mXrSbfNmOt4kQ==',
    ),
  ),
  'validate.authnrequest' => true,
  'saml20.sign.assertion' => true,
);
*/

/*
 * Example simpleSAMLphp SAML 2.0 SP
 */
$metadata['https://saml2sp.example.org'] = array(
	'AssertionConsumerService' => 'https://saml2sp.example.org/simplesaml/module.php/saml/sp/saml2-acs.php/default-sp',
	'SingleLogoutService' => 'https://saml2sp.example.org/simplesaml/module.php/saml/sp/saml2-logout.php/default-sp',
);

/*
 * This example shows an example config that works with Google Apps for education.
 * What is important is that you have an attribute in your IdP that maps to the local part of the email address
 * at Google Apps. In example, if your google account is foo.com, and you have a user that has an email john@foo.com, then you
 * must set the simplesaml.nameidattribute to be the name of an attribute that for this user has the value of 'john'.
 */
$metadata['google.com'] = array(
	'AssertionConsumerService' => 'https://www.google.com/a/g.feide.no/acs',
	'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
	'simplesaml.nameidattribute' => 'uid',
	'simplesaml.attributes' => FALSE,
);
