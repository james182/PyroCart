<?php

class Paypal_m extends CI_Model{

   public function __construct()
	{
		// Call the parent's constructor
		parent::__construct();
    }

	function PPHttpPost($methodName_, $nvpStr_) {
	// Set up your API credentials, PayPal end point, and API version.
	//$environment = 'live'; 										//live or sandbox
	$environment = 'sandbox';
	$API_UserName  =  urlencode('tareq._1278301111_biz_api1.gmail.com'); //urlencode('myemail.myemail.com');			//paypal api username
	$API_Password  =  urlencode('1278301120');//urlencode('ABCDEFGHIJKLMNOP'); //paypal api password
	$API_Signature =  urlencode('AiPC9BjkCyDFQXbSkoZcgqH3hpacAov82Y96NugguljJSSsXi3XNZwyG');//urlencode('AbcdEfghijkLmNoPqrStuVwXYz');	//paypal api signature

	if ($environment == 'live')
		$subenvi = '';
	else
		$subenvi = $environment.'.';

	$API_Endpoint = 'https://api-3t.'.$subenvi.'paypal.com/nvp';
	$version = urlencode('51.0'); 								//paypal version

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;

	} // end function

} //end class

/* End of file paypal.php model */
/* Location: ./system/application/models/paypal.php */
?>