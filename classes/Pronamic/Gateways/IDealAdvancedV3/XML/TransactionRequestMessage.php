<?php

/**
 * Title: iDEAL transaction request XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_XML_TransactionRequestMessage extends Pronamic_Gateways_IDealAdvancedV3_XML_RequestMessage {
	/**
	 * The document element name
	 * 
	 * @var string
	 */
	const NAME = 'AcquirerTrxReq';

	//////////////////////////////////////////////////

	public $issuer;

	public $transaction;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an transaction request message
	 */
	public function __construct() {
		parent::__construct( self::NAME );
	}

	//////////////////////////////////////////////////

	public function get_document() {
		$document = parent::get_document();
		
		// Root
		$root = $document->documentElement;

		// Issuer
		$issuer = $this->issuer;

		$element = self::add_element( $document, $document->documentElement, 'Issuer' );
		self::add_element( $document, $element, 'issuerID', $issuer->get_id() );

		// Merchant
		$merchant = $this->get_merchant();

		$element = self::add_element( $document, $document->documentElement, 'Merchant' );
		self::add_element( $document, $element, 'merchantID', $merchant->get_id() );
		self::add_element( $document, $element, 'subID', $merchant->get_sub_id() );
		self::add_element( $document, $element, 'merchantReturnURL', $merchant->get_return_url() );

		// Transaction
		$transaction = $this->transaction;

		$element = self::add_element( $document, $document->documentElement, 'Transaction' );
		self::add_element( $document, $element, 'purchaseID', $transaction->getPurchaseId() );
		self::add_element( $document, $element, 'amount', Pronamic_Gateways_IDealAdvancedV3_IDeal::format_amount( $transaction->getAmount() ) );
		self::add_element( $document, $element, 'currency', $transaction->getCurrency() );
		self::add_element( $document, $element, 'expirationPeriod', $transaction->getExpirationPeriod() );
		self::add_element( $document, $element, 'language', $transaction->getLanguage() );
		self::add_element( $document, $element, 'description', $transaction->getDescription() );
		self::add_element( $document, $element, 'entranceCode', $transaction->getEntranceCode() );

		// Return
		return $document;
	}
}
