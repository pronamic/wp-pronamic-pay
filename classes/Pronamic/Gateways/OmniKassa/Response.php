<?php

/**
 * Title: OmniKassa response
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_OmniKassa_Response {
	/**
	 * Amount
	 * 
	 * @var string N12
	 */
	private $amount;

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

	/**
	 * Order ID
	 * 
	 * @var string AN32
	 */
	private $orderId;

	/**
	 * Response code
	 * 
	 * @var string N2
	 */
	private $responseCode;

	/**
	 * Transaction date time
	 * 
	 * @var DateTime
	 */
	private $transactionDateTime;

	/**
	 * Authorisation ID
	 * 
	 * @var string
	 */
	private $authorisationId;

	/**
	 * Payment mean brand
	 * 
	 * @var string
	 */
	private $paymentMeanType;

	/**
	 * Payment mean brand
	 * 
	 * @var string
	 */
	private $paymentMeanBrand;

	/**
	 * Complementary code
	 * 
	 * @var string
	 */
	private $complementaryCode;

	/**
	 * Masked pan
	 * 
	 * @var string
	 */
	private $maskedPan;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initalize an OmniKassa response object
	 */
	public function __construct() {
		
	}
}
