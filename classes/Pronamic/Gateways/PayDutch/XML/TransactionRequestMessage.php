<?php

/**
 * Title: Transaction request XML message
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_XML_TransactionRequestMessage extends Pronamic_Gateways_PayDutch_XML_RequestMessage {
	const TYPE = 'transaction';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a directory request message
	 */
	public function __construct( $transaction_request ) {
		parent::__construct( self::TYPE );

		$this->transaction_request = $transaction_request;
	}

	//////////////////////////////////////////////////

	/**
	 * Get document
	 *
	 * @see Pronamic_Gateways_IDealAdvanced_XML_RequestMessage::getDocument()
	 */
	public function get_document() {
		$document = parent::get_document();

		// Transaction request
		$element = self::add_element( $document, $document->documentElement, 'transactionreq' );
		self::add_elements( $document, $element, array(
			'username'    => $this->transaction_request->username,
			'password'    => $this->transaction_request->password,
			'reference'   => $this->transaction_request->reference,
			'description' => $this->transaction_request->description,
			'amount'      => Pronamic_Gateways_PayDutch_PayDutch::format_amount( $this->transaction_request->amount ),
			'methodcode'  => $this->transaction_request->method_code,
			'issuerid'    => $this->transaction_request->issuer_id,
			'test'        => Pronamic_WP_Util::to_string_boolean( $this->transaction_request->test ),
			'successurl'  => $this->transaction_request->success_url,
			'failurl'     => $this->transaction_request->fail_url,
		) );

		// Return
		return $document;
	}
}
