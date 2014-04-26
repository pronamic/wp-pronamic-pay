<?php

/**
 * Title: Mollie payment request
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Mollie_PaymentRequest {
	public $amount;

	public $description;

	public $method;

	public $redirect_url;

	public $meta_data;

	/////////////////////////////////////////////////

	public function __construct() {

	}

	/////////////////////////////////////////////////

	public function get_array() {
		$array = array(
			'amount'      => number_format( $this->amount, 2, '.', '' ),
			'description' => $this->description,
			'method'      => $this->method,
			'redirectUrl' => $this->redirect_url,
			'metadata'    => $this->meta_data,
		);

		// Array filter will remove values NULL, FALSE and empty strings ('')
		$array = array_filter( $array );

		return $array;
	}
}
