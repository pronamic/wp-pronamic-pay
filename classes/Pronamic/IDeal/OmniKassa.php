<?php

/**
 * Title: OmniKassa
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_OmniKassa extends Pronamic_IDeal_IDeal {
	/**
	 * Interface version HP 1.0
	 * 
	 * @var string
	 */
	const INTERFACE_VERSION_HP_1_0 = 'HP_1.0';

	/**
	 * Hash algorithm SHA256 indicator
	 * 
	 * @var string
	 */
	const HASH_ALGORITHM_SHA256 = 'sha256';

	//////////////////////////////////////////////////

	const RESPONSE_CODE_TRANSACTION_SUCCES = '00';
	const RESPONSE_CODE_AUTHORIZATION_LIMIT = '02';
	const RESPONSE_CODE_INVALID_MERCHANT_CONTRACT = '03';
	const RESPONSE_CODE_AUTHORIZATION_REFUSED = '05';
	const RESPONSE_CODE_INVALID_TRANSACTION = '12';
	const RESPONSE_CODE_INVALID_CARD_NUMBER = '14';
	const RESPONSE_CODE_CANCELLATION_OF_PAYMENT = '17';
	const RESPONSE_CODE_INVALID_STATUS = '24';
	const RESPONSE_CODE_TRANSACTION_NOT_FOUND_IN_DATABASE = '25';
	const RESPONSE_CODE_INVALID_FORMAT = '30';
	const RESPONSE_CODE_FRAUD_SUSPICION = '34';
	const RESPONSE_CODE_OPERATION_NOT_ALLOWED = '40';
	const RESPONSE_CODE_PENDING_TRANSACTION = '60';
	const RESPONSE_CODE_SECURITY_BREACH_DETECTED = '63';
	const RESPONSE_CODE_NUMBER_ATTEMPT_EXCEEDED = '75';
	const RESPONSE_CODE_ACQUIRER_SERVER_TEMPORARILY_UNAVAILABLE = '90';
	const RESPONSE_CODE_DUPLICATE_TRANSACTION = '94';
	const RESPONSE_CODE_REQUEST_TIMEOUT = '97';
	const RESPONSE_CODE_PAYMENT_PAGE_TEMPORARILY_UNAVAILABLE = '99';

	//////////////////////////////////////////////////

	/**
	 * The payment server URL
	 * 
	 * @var string
	 */
	private $paymentServerUrl;

	//////////////////////////////////////////////////

	/**
	 * The interface version
	 * 
	 * @var string
	 */
	private $interfaceVersion;

	//////////////////////////////////////////////////

	/**
	 * Currency code in ISO 4217-Numeric codification
	 * 
	 * @doc http://en.wikipedia.org/wiki/ISO_4217
	 * @doc http://www.iso.org/iso/support/faqs/faqs_widely_used_standards/widely_used_standards_other/currency_codes/currency_codes_list-1.htm
	 * 
	 * @var string N3
	 */
	private $currencyNumericCode;

	/**
	 * Merchant ID
	 * 
	 * @var string N15
	 */
	private $merchantId;

	/**
	 * Normal return URL
	 * 
	 * @var string ANS512 url
	 */
	private $normalReturnUrl;

	/**
	 * Amount
	 * 
	 * @var string N12
	 */
	private $amount;

	/**
	 * Transaction reference
	 * 
	 * @var string AN35
	 */
	private $transactionReference;

	/**
	 * Key version
	 * 
	 * @var string N10
	 */
	private $keyVersion;

	//////////////////////////////////////////////////

	/**
	 * Automatic response URL
	 * 
	 * @var string ANS512 url
	 */
	private $automaticResponseUrl;

	/**
	 * Customer language in ISO 639‐1 Alpha2
	 * 
	 * @doc http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
	 * @var string A2
	 */
	private $customerLanguage;

	/**
	 * Payment mean brand list
	 * 
	 * @var array
	 */
	private $paymentMeanBrandList;

	/**
	 * Order ID
	 * 
	 * @var string AN32
	 */
	private $orderId;

	/**
	 * Expiration date
	 * 
	 * @var DateTime
	 */
	private $expirationDate;

	//////////////////////////////////////////////////

	/**
	 * Secret key
	 * 
	 * @var string
	 */
	private $secretKey;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initalize an OmniKassa object
	 */
	public function __construct() {
		$this->paymentMeanBrandList = array();

		$this->setInterfaceVersion(self::INTERFACE_VERSION_HP_1_0);
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
	 * Get interface version
	 * 
	 * @return string
	 */
	public function getInterfaceVersion() {
		return $this->interfaceVersion;
	}

	/**
	 * Set interface version
	 * 
	 * @param string $interfaceVersion
	 */
	public function setInterfaceVersion($interfaceVersion) {
		$this->interfaceVersion = $interfaceVersion;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the currency numeric code
	 * 
	 * @return string currency numeric code
	 */
	public function getCurrencyNumericCode() {
		return $this->currencyNumericCode;
	}

	/**
	 * Set the currency code
	 * 
	 * @param string $currencyCode
	 */
	public function setCurrencyNumericCode($currencyNumericCode) {
		$this->currencyNumericCode = $currencyNumericCode;
	}

	//////////////////////////////////////////////////

	/**
	 * Get merchant ID
	 * 
	 * @return string
	 */
	public function getMerchantId() {
		return $this->merchantId;
	}

	/**
	 * Set the merchant ID
	 * 
	 * @param string $merchantdId
	 */
	public function setMerchantId($merchantId) {
		$this->merchantId = $merchantId;
	}

	//////////////////////////////////////////////////

	/**
	 * Get normal return URL
	 * 
	 * @return string
	 */
	public function getNormalReturnUrl() {
		return $this->normalReturnUrl;
	}

	/**
	 * Set the normal return URL
	 * 
	 * LET OP! De URL mag geen parameters bevatten.
	 * 
	 * @param string $normalReturnUrl
	 */
	public function setNormalReturnUrl($normalReturnUrl) {
		$this->normalReturnUrl = $normalReturnUrl;
	}

	//////////////////////////////////////////////////

	/**
	 * Get amount
	 * 
	 * @return float
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Get formmated amount
	 * 
	 * @return int
	 */
	public function getFormattedAmount() {
		return Pronamic_IDeal_IDeal::formatPrice($this->amount);
	}

	/**
	 * Set amount
	 * 
	 * @param float $amount
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
	}

	//////////////////////////////////////////////////

	/**
	 * Get transaction reference
	 * 
	 * @return string
	 */
	public function getTransactionReference() {
		return $this->transactionReference;
	}

	/**
	 * Set transaction reference
	 * AN..max35 (AN = Alphanumeric, free text)
	 * 
	 * @param string $transactionReference
	 */
	public function setTransactionReference($transactionReference) {
		$this->transactionReference = substr($transactionReference, 0, 35);
	}

	//////////////////////////////////////////////////

	/**
	 * Get key version
	 * 
	 * @return string
	 */
	public function getKeyVersion() {
		return $this->keyVersion;
	}

	/**
	 * Set key version
	 * 
	 * @param string $keyVersion
	 */
	public function setKeyVersion($keyVersion) {
		$this->keyVersion = $keyVersion;
	}

	//////////////////////////////////////////////////

	/**
	 * Get automatic response URL
	 * 
	 * @return string
	 */
	public function getAutomaticResponseUrl() {
		return $this->automaticResponseUrl;
	}

	/**
	 * Set automatic response URL
	 * 
	 * LET OP! De URL mag geen parameters bevatten.
	 * 
	 * @param string $automaticResponseUrl
	 */
	public function setAutomaticResponseUrl($automaticResponseUrl) {
		$this->automaticResponseUrl = $automaticResponseUrl;
	}

	//////////////////////////////////////////////////

	/**
	 * Get customer language
	 * 
	 * @return string
	 */
	public function getCustomerLanguage() {
		return $this->customerLanguage;
	}

	/**
	 * Set customer language
	 * 
	 * @param string $customerLanguage
	 */
	public function setCustomerLanguage($customerLanguage) {
		$this->customerLanguage = $customerLanguage;
	}

	//////////////////////////////////////////////////

	/**
	 * Add the specified payment mean brand to the payment mean brand list
	 * 
	 * @param string $paymentMeanBrand
	 */
	public function addPaymentMeanBrand($paymentMeanBrand) {
		$this->paymentMeanBrandList[] = $paymentMeanBrand;
	}

	/**
	 * Get payment mean brand list
	 * 
	 * @return string ANS128 listString comma separated list
	 */
	public function getPaymentMeanBrandList() {
		return implode(', ', $this->paymentMeanBrandList);
	}

	//////////////////////////////////////////////////

	/**
	 * Get order ID
	 * 
	 * @return string
	 */
	public function getOrderId() {
		return $this->orderId;
	}

	/**
	 * Set order ID
	 * 
	 * @param string $orderId
	 */
	public function setOrderId($orderId) {
		$this->orderId = $orderId;
	}

	//////////////////////////////////////////////////

	/**
	 * Get expiration date
	 * 
	 * @return DateTime
	 */
	public function getExpirationDate() {
		return $this->expirationDate;
	}

	/**
	 * Get expiration date
	 * 
	 * @return string
	 */
	public function getFormattedExpirationDate() {
		$result = null;

		if($this->expirationDate != null) {
			$result = $this->expirationDate->format(DATE_ISO8601);
		}

		return $result;
	}

	/**
	 * Set expiration date
	 * 
	 * @param DateTime $expirationDate
	 */
	public function setExpirationDate(DateTime $expirationDate = null) {
		$this->expirationDate = $expirationDate;
	}

	//////////////////////////////////////////////////

	/**
	 * Get data
	 * 
	 * @return string
	 */
	public function getData() {
		$expirationDate = $this->getExpirationDate();

		// Payment Request - required fields
		$requiredFields = array( 
			'currencyCode' => $this->getCurrencyNumericCode() ,
			'merchantId' => $this->getMerchantId() ,  
			'normalReturnUrl' => $this->getNormalReturnUrl() , 
			'amount' => $this->getFormattedAmount() , 
			'transactionReference' => $this->getTransactionReference() ,
			'keyVersion' => $this->getkeyVersion()
		);
		
		// Payment request - optional fields
		$optionalFields = array(
			'automaticResponseUrl' => $this->getAutomaticResponseUrl() , 
			'customerLanguage' => $this->getCustomerLanguage() , 
			'paymentMeanBrandList' => $this->getPaymentMeanBrandList() ,
			'orderId' => $this->getOrderId() ,  
			'expirationDate' => $this->getFormattedExpirationDate()
		);

		// @see http://briancray.com/2009/04/25/remove-null-values-php-arrays/
		$optionalFields = array_filter($optionalFields);

		// Data
		$data = $requiredFields + $optionalFields;

		return self::createPipedString($data);
	}

	//////////////////////////////////////////////////

	/**
	 * Get secret key
	 * 
	 * @return string
	 */
	public function getSecretKey() {
		return $this->secretKey;
	}

	/**
	 * Set secret key
	 * 
	 * @return string
	 */
	public function setSecretKey($secretKey) {
		$this->secretKey = $secretKey;
	}

	//////////////////////////////////////////////////

	/**
	 * Get seal
	 * 
	 * @return string
	 */
	public function getSeal() {
		$data = $this->getData();
		$secretKey = $this->getSecretKey();

		return self::computeSeal($data, $secretKey);
	}

	/**
	 * Compute seal
	 * 
	 * @param string $data
	 * @param string $secretKey
	 */
	public static function computeSeal($data, $secretKey) {
		$value = $data . $secretKey;
		$value = utf8_encode($value);

		return hash(self::HASH_ALGORITHM_SHA256, $value);
	}

	//////////////////////////////////////////////////

	/**
	 * Get HTML fields
	 * 
	 * @return string
	 */
	public function getHtmlFields() {
		$html  = '';

		$html .= sprintf('<input type="hidden" name="Data" value="%s" />', $this->getData());
		$html .= sprintf('<input type="hidden" name="InterfaceVersion" value="%s" />', $this->getInterfaceVersion());
		$html .= sprintf('<input type="hidden" name="Seal" value="%s" />', $this->getSeal());
		
		return $html;
	}

	//////////////////////////////////////////////////

	/**
	 * Create an piped string for the specified data array
	 * 
	 * @param array $data
	 * @return string
	 */
	public static function createPipedString(array $data) {
		/*
		$pairs = array();

		foreach($data as $key => $value) {
			if(!empty($value)) {
				$pairs[] = $key . '=' . $value;
			}
		}

		$pipedString = implode('|', $pairs);

		return $pipedString;
		*/

		// @see http://core.trac.wordpress.org/browser/tags/3.3.1/wp-includes/functions.php#L1385
		return _http_build_query($data, null, '|', '', false);
	}

	/**
	 * Parse piped string
	 * 
	 * @param string $string
	 * @return array
	 */
	public static function parsePipedString($string) {
		$data = array();

		$pairs = explode('|', $string);
		foreach($pairs as $pair) {
			list($key, $value) = explode('=', $pair);

			$data[$key] = $value;
		}

		return $data;
	}

	//////////////////////////////////////////////////

	public function getResponseCodeDescription() {
		return array(
			'00' => 'Transaction success, authorization accepted' , 
			'02' => 'Please call the bank because the authorization limit on the card has been exceeded' , 
			'03' => 'Invalid merchant contract' , 
			'05' => 'Do not honor, authorization refused' , 
			'12' => 'Invalid transaction, check the parameters sent in the request' , 
			'14' => 'Invalid card number or invalid Card Security Code or Card (for MasterCard) or invalid Card Verification Value (for Visa/MAESTRO)' , 
			'17' => 'Cancellation of payment by the end user' , 
			'24' => 'Invalid status' , 
			'25' => 'Transaction not found in database' , 
			'30' => 'Invalid format' , 
			'34' => 'Fraud suspicion' , 
			'40' => 'Operation not allowed to this Merchant' , 
			'60' => 'Pending transaction' , 
			'63' => 'Security breach detected, transaction stopped' , 
			'75' => 'The number of attempts to enter the card number has been exceeded (three tries exhausted)' , 
			'90' => 'Acquirer server temporarily unavailable' , 
			'94' => 'Duplicate transaction' , 
			'97' => 'Request time-out; transaction refused' , 
			'99' => 'Payment page temporarily unavailable' 
		);
	}
}
