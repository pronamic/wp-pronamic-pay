<?php

/**
 * Title: iDEAL transaction request XML message
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvanced_XML_TransactionRequestMessage extends Pronamic_Gateways_IDealAdvanced_XML_RequestMessage {
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

	public function getDocument() {
		$document = parent::getDocument();
		
		// Root
		$root = $document->documentElement;

		// Issuer
		$issuer = $this->issuer;

		$element = self::addElement( $document, $document->documentElement, 'Issuer' );
		self::addElement( $document, $element, 'issuerID', $issuer->getId() );

		// Merchant
		$merchant = $this->getMerchant();

		$element = self::addElement( $document, $document->documentElement, 'Merchant' );
		self::addElement( $document, $element, 'merchantID', $merchant->id );
		self::addElement( $document, $element, 'subID', $merchant->subId );
		self::addElement( $document, $element, 'authentication', $merchant->authentication );
		self::addElement( $document, $element, 'token', $merchant->token );
		self::addElement( $document, $element, 'tokenCode', $merchant->tokenCode );
		self::addElement( $document, $element, 'merchantReturnURL', $merchant->returnUrl );

		// Transaction
		$transaction = $this->transaction;

		$element = self::addElement( $document, $document->documentElement, 'Transaction' );
		self::addElement( $document, $element, 'purchaseID', $transaction->getPurchaseId() );
		self::addElement( $document, $element, 'amount', Pronamic_WordPress_Util::amount_to_cents( $transaction->get_amount() ) );
		self::addElement( $document, $element, 'currency', $transaction->getCurrency() );
		self::addElement( $document, $element, 'expirationPeriod', $transaction->getExpirationPeriod() );
		self::addElement( $document, $element, 'language', $transaction->getLanguage() );
		self::addElement( $document, $element, 'description', $transaction->get_description() );
		self::addElement( $document, $element, 'entranceCode', $transaction->getEntranceCode() );

		// Return
		return $document;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the sign values for this transaction request message
	 * 
	 * @return array
	 */
	public function getSignValues() {
		return array(
			$this->getCreateDate()->format( Pronamic_IDeal_IDeal::DATE_FORMAT ), 
			$this->issuer->getId(),
			$this->getMerchant()->id, 
			$this->getMerchant()->subId, 
			$this->getMerchant()->returnUrl, 
			$this->transaction->getPurchaseId(),
			Pronamic_WordPress_Util::amount_to_cents( $this->transaction->get_amount() ),
			$this->transaction->getCurrency(),
			$this->transaction->getLanguage(),
			$this->transaction->get_description(),
			$this->transaction->getEntranceCode()
		);
	}
}
