<?php
/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @author     Frank Angermann
 *
 * This class provides the authentication on the developers server. The Authorization header of the incoming call is checked and vaidated against the authentication
 * defined. If this is not successful, the call dos not come from junaio.
 **/

class Junaio {

	/**
	Check the authorization header of the incoming call against the junaio authentication.
	@return Boolean true if successful, false if not
	*/
	public static function checkAuthentication() {

		$_HEADERS = getallheaders();
		
		// Check if authorization header is there
		if (!isset($_HEADERS['Authorization'])) {
			return FALSE;
		}
		$sAuthentication = $_HEADERS['Authorization'];

		// Check if authorization header is of "junaio" type
		if(strpos($sAuthentication, 'junaio') != 0) {
			return FALSE;
		}

		// Check date header
		$sDate = $_HEADERS['Date'];
		$iParsedDate = strtotime($sDate);
		$iNow = time();
		if($iParsedDate < $iNow - AUTH_DATE_TOLERANCE || $iParsedDate > $iNow + AUTH_DATE_TOLERANCE) {
			// Header is outdated
			return FALSE;
		}

		// Prepare signature variables
		$aTokens = explode(' ', $sAuthentication);
		if (!isset($aTokens[1]) || trim($aTokens[1]) == '') {
			// No signature string there
			return FALSE;
		}
		$sRequestSignature = base64_decode(trim($aTokens[1]));

		// Build server request signature
		$sServerRequestSignature = sha1(
			JUNAIO_KEY . sha1(
				JUNAIO_KEY .
				$_SERVER['REQUEST_METHOD'] . "\n" .
				$_SERVER['REQUEST_URI'] . "\n" .
				'Date: ' . $sDate . "\n"
			)
		);

		// Compare request signature
		if(strcmp($sRequestSignature, $sServerRequestSignature) !== 0) {
			// Incorrect authentication
			return FALSE;
		} else {
			return TRUE;
		}
	}
}

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
           else if (substr($name, 0, 14) == 'REDIRECT_HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 14)))))] = $value;
           } 
       }
       return $headers;
    }
}