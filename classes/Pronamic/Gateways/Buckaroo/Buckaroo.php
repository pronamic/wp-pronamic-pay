<?php

/**
 * Title: iDEAL Internet Kassa gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Buckaroo {
	/**
	 * Indicator for hash algorithm SHA-1
	 * 
	 * @var string
	 */
	const HASH_ALGORITHM_SHA_1 = 'sha1';

	/**
	 * Indicator for hash algorithm SHA-256
	 * 
	 * @var string
	 */
	const HASH_ALGORITHM_SHA_256 = 'sha256';

	/**
	 * Indicator for hash algorithm SHA-512
	 * 
	 * @var string
	 */
	const HASH_ALGORITHM_SHA_512 = 'sha512';

	//////////////////////////////////////////////////

	/**
	 * The payment server URL 
	 * 
	 * @var string
	 */
	private $paymentServerUrl;

	//////////////////////////////////////////////////

	/**
	 * The amount
	 *
	 * @var int
	 */
	private $amount;

	//////////////////////////////////////////////////

	/**
	 * Signature parameters IN
	 * 
	 * @var array
	 */
	private $calculationsParametersIn;

	/**
	 * Signature parameters OUT
	 * 
	 * @var array
	 */
	private $calculationsParametersOut;

	//////////////////////////////////////////////////

	/**
	 * Fields
	 * 
	 * @var array
	 */
	private $fields;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL kassa object
	 */
	public function __construct() {
		$this->fields = array();

		$this->calculationsParametersIn  = array();
		$this->calculationsParametersOut = array();

		$this->hash_algorithm = self::HASH_ALGORITHM_SHA_1;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the payment server URL
	 *
	 * @return the payment server URL
	 */
	public function getPaymentServerUrl() {
		return $this->paymentServerUrl;
	}
	
	/**
	 * Set the payment server URL
	 *
	 * @param string $url an URL
	 */
	public function setPaymentServerUrl($url) {
		$this->paymentServerUrl = $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get hash algorithm
	 * 
	 * @return string
	 */
	public function get_hash_algorithm() {
		return $this->hash_algorithm;
	}

	/**
	 * Set hash algorithm
	 * 
	 * @param string $hashAlgorithm
	 */
	public function set_hash_algorithm( $hash_algorithm ) {
		$this->hash_algorithm = $hash_algorithm;
	}


 
	
	
	//////////////////////////////////////////////////
	// Calculation parameters
	//////////////////////////////////////////////////

	/**
	 * Get calculations parameters IN
	 * 
	 * @return array
	 */
	public function getCalculationsParametersIn() {
		return $this->calculationsParametersIn;
	}

	/**
	 * Set calculations parameters IN
	 * 
	 * @param array $parameters
	 */
	public function setCalculationsParametersIn(array $parameters) {
		$this->calculationsParametersIn = $parameters;
	}

	//////////////////////////////////////////////////

	/**
	 * Get calculations parameters OUT
	 * 
	 * @return array
	 */
	public function getCalculationsParametersOut() {
		return $this->calculationsParametersOut;
	}

	/**
	 * Set calculations parameters OUT
	 * 
	 * @param array $parameters
	 */
	public function setCalculationsParametersOut(array $parameters) {
		$this->calculationsParametersOut = $parameters;
	}

	//////////////////////////////////////////////////
	// Fields
	//////////////////////////////////////////////////

	/**
	 * Get field by the specifiek name
	 * 
	 * @param string $name
	 */
	public function getField($name) {
		$value = null;

		if(isset($this->fields[$name])) {
			$value = $this->fields[$name];
		}

		return $value;
	}

	/**
	 * Set field
	 * 
	 * @param string $name
	 * @param string $value
	 */
	public function setField($name, $value) {
		$this->fields[$name] = $value;
	}

	//////////////////////////////////////////////////
	// Fields helper functinos
	//////////////////////////////////////////////////

	/**
	 * Get the PSP id
	 *
	 * @return an PSP id
	 */
	// public function getPspId() {
  //		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::PSPID);
	// }

	/**
	 * Set the PSP id
	 * 
	 * Your affiliation name in our system, chosen by yourself when opening your account 
	 * with us. This is a unique identifier and can’t ever be changed. 
	 *
	 * @param string PSP id
	 */
// 	public function setPspId($pspId) {
// 		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::PSPID, $pspId);
// 	}

 //////////////////////////////////////////////////
  // Added for Buckaroo

	public function getMerchantId() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::MERCHANTID);
	}

	public function setMerchantId($merchantId) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::MERCHANTID, $merchantId);
	}



	public function gethashKey() {
	 // Buckaroo Debugging 
//	  echo " </br> <strong>Dit is het BuckaroohashKey function getHashKey  </strong> $hashKey </br>";
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::HASHKEY);
	}

	public function sethashKey($hashKey) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::HASHKEY, $hashKey);
	}
  	
	// End Added for Buckaroo
	//////////////////////////

	//////////////////////////////////////////////////

	/**
	 * Get the order id
	 *
	 * @return an order id
	 */
	public function getOrderId() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::ORDERID);
	}

	/**
	 * Set the order id
	 *
	 * @param string $orderId
	 */
	public function setOrderId($orderId) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::ORDERID, $orderId);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the language
	 *
	 * @return an language
	 */
	public function getLanguage() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::LANGUAGE);
	}

	/**
	 * Set the language
	 * 
	 * The format is "language_Country".
	 * The language value is based on ISO 639-1.
	 * The country value is based on ISO 3166-1.
	 *
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::LANGUAGE, $language);
	}

	//////////////////////////////////////////////////
	
	/**
	 * Get the currency
	 *
	 * @return string
	 */
	public function getCurrency() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::CURRENCY);
	}
	
	/**
	 * Set the currency
	 * 
	 * Currency of the amount in alphabetic ISO code as can be found on 
	 * http://www.currency-iso.org/iso_index/iso_tables/iso_tables_a1.htm
	 *
	 * @return string $currency
	 */
	public function setCurrency($currency) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::CURRENCY, $currency);
	}

	//////////////////////////////////////////////////

	/**
	 * Get the amount
	 * 
	 * @return float
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Set the amount
	 * 
	 * @param float $amount
	 */
	public function setAmount($amount) {
		$this->amount = $amount;

		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::AMOUNT, $amount);
	}

	//////////////////////////////////////////////////

	/**
	 * Get order description
	 * 
	 * The com field is sometimes transmitted to the acquirer (depending on the acquirer), 
	 * in order to be shown on the account statements of the merchant or the customer. 
	 * 
	 * @return string
	 */
	public function getOrderDescription() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::COM);
	}

	/**
	 * Set order description
	 * 
	 * The com field is sometimes transmitted to the acquirer (depending on the acquirer), 
	 * in order to be shown on the account statements of the merchant or the customer. 
	 * 
	 * @param string $description
	 */
	public function setOrderDescription($description) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::COM, $description);
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////

	/**
	 * Get accept URL
	 * 
	 * URL of the web page to show the customer when the payment is authorized. 
	 * 
	 * @reutnr string
	 */
	public function getAcceptUrl() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::ACCEPT_URL);
	}

	/**
	 * Set accept URL
	 * 
	 * URL of the web page to show the customer when the payment is authorized. 
	 * 
	 * @param string $url
	 */
	public function setAcceptUrl($url) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::ACCEPT_URL, $url);
	}

	//////////////////////////////////////////////////

	/**
	 * Get cancel URL
	 * 
	 * URL of the web page to show the customer when he cancels the payment. 
	 * 
	 * @return string
	 */
	public function getCancelUrl() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::CANCEL_URL);
	}

	/**
	 * Set cancel URL
	 * 
	 * URL of the web page to show the customer when he cancels the payment. 
	 * 
	 * @param string $url
	 */
	public function setCancelUrl($url) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::CANCEL_URL, $url);
	}

	//////////////////////////////////////////////////

	/**
	 * Get exception URL
	 * 
	 * URL of the web page to show the customer when the payment result is uncertain.
	 * 
	 * @return string
	 */
	public function getExceptionUrl() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::EXCEPTION_URL);
	}

	/**
	 * Set exception URL
	 * 
	 * URL of the web page to show the customer when the payment result is uncertain.
	 * 
	 * @param string $url
	 */
	public function setExceptionUrl($url) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::EXCEPTION_URL, $url);
	}

	//////////////////////////////////////////////////

	/**
	 * Get decline URL
	 * 
	 * URL of the web page to show the customer when the acquirer rejects the authorisation more 
	 * than the maximum of authorised tries (10 by default, but can be changed in the technical 
	 * information page). 
	 * 
	 * @return string
	 */
	public function getDeclineUrl() {
		return $this->getField(Pronamic_Gateways_Buckaroo_Parameters::DECLINE_URL);
	}

	/**
	 * Set decline URL
	 * 
	 * URL of the web page to show the customer when the acquirer rejects the authorisation more 
	 * than the maximum of authorised tries (10 by default, but can be changed in the technical 
	 * information page). 
	 * 
	 * @param string $url
	 */
	public function setDeclineUrl($url) {
		$this->setField(Pronamic_Gateways_Buckaroo_Parameters::DECLINE_URL, $url);
	}

	//////////////////////////////////////////////////
	// Signature functions
	//////////////////////////////////////////////////

	/**
	 * Get signature fields IN
	 * 
	 * @param array $fields
	 * @return array
	 */
	private function getSignatureFieldsIn($fields) {
		$calculationsParameters = array_flip( $this->calculationsParametersIn );

		return array_intersect_key( $fields, $calculationsParameters );
	}

	/**
	 * Get signature fields OUT
	 * 
	 * @param array $fields
	 * @return array
	 */
	private function getSignatureFieldsOut( $fields ) {
		$calculationsParameters = array_flip( $this->calculationsParametersOut );

		return array_intersect_key( $fields, $calculationsParameters );
	}

	//////////////////////////////////////////////////

	/**
	 * Get signature
	 * 
	 * @param array $fields
	 * @param string $passprahse
	 * @return string
	 */
	private function getSignature( $fields, $passprahse ) {
		// This string is constructed by concatenating the values of the fields sent with the order (sorted
		// alphabetically, in the format ‘parameter=value’), separated by a passphrase.		
		$string = '';

		// All parameters need to be put alphabetically
		ksort( $fields );

		// Loop
		foreach ( $fields as $name => $value ) {
			$value = (string) $value;

			// Use of empty will fail, value can be string '0'
			if ( strlen( $value ) > 0 ) {
				$name = strtoupper( $name );
		
				$string .= $name . '=' . $value . $passprahse;
			}
		}

		// Hash
		$result = hash( $this->hash_algorithm, $string );

		// String to uppercase
		$result = strtoupper( $result );

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Get signature IN
	 * 
	 * @return string
	 */
	public function getSignatureIn() {
		$fields = $this->getSignatureFieldsIn( $this->fields );

		return $this->getSignature( $fields, $this->getMerchantId() );
	}

	/**
	 * Get signature OUT
	 * 
	 * @param array $fields
	 */
	public function getSignatureOut( $fields ) {
		$fields = $this->getSignatureFieldsOut( $fields );

		return $this->getSignature( $fields, $this->getMerchantId() );
	}

	//////////////////////////////////////////////////

	
	
	public function getSignature( $data, $secreteKey ) {
		$string = '';

		$data = array_change_key_case( $data, CASE_LOWER );
		
		ksort( $data );
		
		foreach ( $data as $key => $value ) {
			$string .= $key . '=' . $value;
		}
		
		$string .= $secretKey;
		
		return hash( 'sha1', $string );
	}
	
	/**
	 * Get HTML fields
	 * 
	 * @return string
	 */
	public function getHtmlFields() {

    $postArray = array();
  	$arrayToSort = array();
  	$tmpKeyArray = array();
  	
  	$postArray ['Brq_websitekey'] = $this->gethashKey();
  	$postArray ['Brq_invoicenumber']  = $this->getOrderId(); 
		$postArray ['Brq_amount']  =  $this->getAmount(); 
		$postArray ['Brq_currency']  =  $this->getCurrency();
		$postArray ['Brq_culture']  =  $this->getLanguage();
		$postArray ['Brq_description']  =  $this->getOrderDescription(); 
		
		$postArray['Brq_payment_method'] = "ideal";
    $postArray['Brq_service_ideal_action'] = "Pay";


		$postArray ['Brq_return']  = $this->getAcceptUrl();
		$postArray ['Brq_returnreject']  = $this->getDeclineUrl();
		$postArray ['Brq_returnerror']  = $this->getExceptionUrl();
		$postArray ['Brq_returncancel']  = $this->getCancelUrl();
		
      
  	foreach($postArray as $key => $value){
            $arrayToSort[strtolower($key)] = $value;
            //store the original value in an array
            $tmpKeyArray[strtolower($key)] = $key;
            }
  	
  	ksort($arrayToSort);
  	$postArray = array();
  	
  	foreach($arrayToSort as $key =>$value){
    // switch the lowercase keys back to originals
    $key = $tmpKeyArray[$key];
    $postArray[$key] = $value;
    }
    
 
  	
  	//turn into string and add the secret key to the end
    $signatureString = '';
    foreach($postArray as $key => $value) {        
        $signatureString .= $key . '=' . $value;
       }
    
    $signatureString .= $this->getMerchantId();
//    echo "</br> complete signature String : $signatureString  </br>";
    
    $signature = SHA1($signatureString);

// add signature to array

    $postArray['brq_signature'] = $signature; 
    
    
		 return Pronamic_IDeal_IDeal::htmlHiddenFields( $postArray );
	}

	//////////////////////////////////////////////////

	/**
	 * Verify request
	 */
/*	public function verifyRequest( $data ) {
		$result = false;
		
		 echo "</br> <strong> Start verifyRequest data information </strong> </br>";
		 print_r ($data);
		 echo "</br> <strong> END verifyRequest data information </strong> </br>";
		$data = array_change_key_case( $data, CASE_UPPER );

		if ( isset( $data['brq_signature'] ) ) {
			$signature = $data['brq_signature'];

			$signatureOut = $this->getSignatureOut( $data );

			if ( strcasecmp( $signature, $signatureOut ) === 0 ) {
				$result = filter_var_array( $data, array(
					Pronamic_Gateways_Buckaroo_Parameters::ORDERID  => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::AMOUNT   => FILTER_VALIDATE_FLOAT, 
					Pronamic_Gateways_Buckaroo_Parameters::CURRENCY => FILTER_SANITIZE_STRING,
					'brq_payment'         => FILTER_SANITIZE_STRING, 
					'brq_statusmessage' => FILTER_SANITIZE_STRING, 
					'brq_statuscode'     => FILTER_VALIDATE_INT,
					'brq_SERVICE_ideal_consumerIBAN'     => FILTER_SANITIZE_STRING, 
					'brq_SERVICE_ideal_consumerIssuer'      => FILTER_SANITIZE_STRING, 
					'brq_signature'    => FILTER_SANITIZE_STRING 
				) );
			} 
		}
		
		 echo "</br> <strong> Start verifyRequest Result information </strong> </br>";
		 print_r ($result);
		 echo "</br> <strong> END verifyRequest Result information </strong> </br>";
		return $result;
	}
	 */
	
	//////////////////////////////////////////////////

	/**
	 * Verify request  Buckaroo
	 */
	public function verifyRequest( $data ) {
		$result = false;
		
		$signatureOut = ($data['brq_signature']);
     
		if ( isset( $data['brq_signature'] ) ) {
		
        unset($data['brq_signature']);
      
		    $sortableArray = $this->buckarooSort($data);
		    
		      //turn into string and add the secret key to the end
         $signatureString = '';
           foreach($sortableArray as $key => $value) {
                   $value = urldecode($value);
                   $signatureString .= $key . '=' . $value;
           }
        // Buckaroo Added Merchant ID to string to calculate signature
        $signatureString .= $this->getMerchantId();
            
    //return the SHA1 encoded string for comparison
       $signature = SHA1($signatureString);
      if ( strcasecmp( $signature, $signatureOut ) === 0 ) {
				$result = filter_var_array( $data, array(
					Pronamic_Gateways_Buckaroo_Parameters::ORDERID  => FILTER_SANITIZE_STRING,
					Pronamic_Gateways_Buckaroo_Parameters::AMOUNT   => FILTER_VALIDATE_FLOAT, 
					Pronamic_Gateways_Buckaroo_Parameters::CURRENCY => FILTER_SANITIZE_STRING,
					'brq_payment'         => FILTER_SANITIZE_STRING, 
					'brq_statusmessage' => FILTER_SANITIZE_STRING, 
					'brq_statuscode'     => FILTER_VALIDATE_INT,
					'brq_SERVICE_ideal_consumerIBAN'     => FILTER_SANITIZE_STRING, 
					'brq_SERVICE_ideal_consumerIssuer'      => FILTER_SANITIZE_STRING, 
					'brq_signature'    => FILTER_SANITIZE_STRING 
				) );
			} 
		}
		
//		 echo "</br> <strong> Start verifyRequest Result information </strong> </br>";
//		 print_r ($result);
//		 echo "</br> <strong> END verifyRequest Result information </strong> </br>";
		return $result;
	}
	
public function buckarooSort($array)
{
    $arrayToSort = array();
    $origArray = array();
    foreach ($array as $key => $value) {
        $arrayToSort[strtolower($key)] = $value;
    	//stores the original value in an array
        $origArray[strtolower($key)] = $key;
    }

    ksort($arrayToSort);

    $sortedArray = array();
    foreach($arrayToSort as $key => $value) {
    	//switch the lowercase keys back to their originals
        $key = $origArray[$key];
        $sortedArray[$key] = $value;
    }

    return $sortedArray;
}
}
