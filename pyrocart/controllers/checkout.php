<?php

class checkout extends Public_Controller
{

	/**
	 * Constructor method
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::Public_Controller();
		$this->config->load('paypal_constants');
		$this->load->model('orders_m');
                $this->load->model('checkout_m');
	}

	function index() {

	}// end function
        
    function do_direct_payment_receive()
		{

			//$this->orders_m->process_order();
			//redirect('products/cart/show_cart');


			$API_UserName=$this->config->item('API_USERNAME');

			$API_Password=$this->config->item('API_PASSWORD');

			$API_Signature=$this->config->item('API_SIGNATURE');

			$API_Endpoint =$this->config->item('API_ENDPOINT');

			$subject = $this->config->item('SUBJECT');


			$this->load->model('paypal_m');


			$paymentType =urlencode( 'Sale');
			$firstName =urlencode( $_POST['firstName']);
			$lastName =urlencode( $_POST['lastName']);
			$creditCardType =urlencode( $_POST['creditCardType']);
			$creditCardNumber = urlencode($_POST['creditCardNumber']);
			$expDateMonth =urlencode( $_POST['expDateMonth']);

			// Month must be padded with leading zero
			$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);

			$expDateYear =urlencode( $_POST['expDateYear']);
			$cvv2Number = urlencode($_POST['cvv2Number']);
			$address1 = urlencode($_POST['address1']);
			$address2 = urlencode($_POST['address2']);
			$city = urlencode($_POST['city']);
			$state =urlencode( $_POST['state']);
			$zip = urlencode($_POST['zip']);

			///////// please fix this when live
			$amount =urlencode($_POST['amount']);// urlencode('1.00');//urlencode($_POST['amount']);
			//$currencyCode=urlencode($_POST['currency']);
			$currencyCode="USD";
			$paymentType=urlencode('Sale');

			/* Construct the request string that will be sent to PayPal.
			   The variable $nvpstr contains all the variables and is a
			   name value pair string with & as a delimiter */
			$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".         $padDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
			"&ZIP=$zip&COUNTRYCODE=US&CURRENCYCODE=$currencyCode";

			$getAuthModeFromConstantFile = true;
			//$getAuthModeFromConstantFile = false;
			$nvpHeader = "";

			if(!$getAuthModeFromConstantFile) {
				//$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
				//$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
				$AuthMode = "THIRDPARTY"; //Partner's API Credential and Merchant Email as Subject are required.
			} else {
				if(!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature) && !empty($subject)) {
					$AuthMode = "THIRDPARTY";
				}else if(!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature)) {
					$AuthMode = "3TOKEN";
				}else if(!empty($subject)) {
					$AuthMode = "FIRSTPARTY";
				}
			}

			switch($AuthMode) {

				case "3TOKEN" :
						$nvpHeader = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature);
						break;
				case "FIRSTPARTY" :
						$nvpHeader = "&SUBJECT=".urlencode($subject);
						break;
				case "THIRDPARTY" :
						$nvpHeader = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature)."&SUBJECT=".urlencode($subject);
						break;

			}

			$nvpstr = $nvpHeader.$nvpstr;

			/* Make the API call to PayPal, using API signature.
			   The API response is stored in an associative array called $resArray */
			$httpParsedResponseAr = $this->paypal_m->PPHttpPost('DoDirectPayment', $nvpstr);

			/* Display the API response back to the browser.
			   If the response from PayPal was a success, display the response parameters'
			   If the response was an error, display the errors received using APIError.php.
			   */
			$ack = strtoupper($httpParsedResponseAr["ACK"]);
			$this->data->httpParsedResponseAr = $httpParsedResponseAr;

			if($ack=='SUCCESS'){

				$this->template->build('paypal/success',$this->data);
				// order process is done here
				// inserting order to backend table for order management
				$this->orders_m->process_order();

			}
			else{

				$this->template->build('paypal/apierror',$this->data);
			}


		}

} //end class

/* End of file checkout.php */
/* Location: ./system/application/controllers/checkout.php */