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

	/**
	 * Issuer
	 *
	 * @var Pronamic_Gateways_IDealAdvancedV3_Issuer
	 */
	public $issuer;

	/**
	 * Transaction
	 *
	 * @var Pronamic_Gateways_IDealAdvancedV3_Transaction
	 */
	public $transaction;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an transaction request message
	 */
	public function __construct() {
		parent::__construct( self::NAME );
	}

	//////////////////////////////////////////////////

	/**
	 * Get document
	 *
	 * @see Pronamic_Gateways_IDealAdvancedV3_XML_RequestMessage::get_document()
	 */
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
		self::add_elements( $document, $element, array(
			'merchantID'        => $merchant->get_id(),
			'subID'             => $merchant->get_sub_id(),
			'merchantReturnURL' => $merchant->get_return_url()
		) );

		// Transaction
		$transaction = $this->transaction;

		$element = self::add_element( $document, $document->documentElement, 'Transaction' );
		self::add_elements( $document, $element, array(
			'purchaseID'       => $transaction->get_purchase_id(),
			'amount'           => Pronamic_Gateways_IDealAdvancedV3_IDeal::format_amount( $transaction->get_amount() ),
			'currency'         => $transaction->get_currency(),
			'expirationPeriod' => $transaction->get_expiration_period(),
			'language'         => $transaction->get_language(),
			'description'      => $transaction->get_description(),
			'entranceCode'     => $transaction->get_entrance_code()
		) );

		// Return
		return $document;
	}
}
