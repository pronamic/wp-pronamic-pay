<?php

/**
 * Title: Buckaroo statuses constants
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_Buckaroo_Statuses {
	/**
	 * Payment success
	 * @var int
	 */
	const PAYMENT_SUCCESS = 190;

	/**
	 * Payment failure
	 * @var int
	 */
	const PAYMENT_FAILURE = 490;

	/**
	 * Validation error
	 * @var int
	 */
	const VALIDATION_FAILURE = 491;

	/**
	 * Technical error
	 * @var int
	 */
	const TECHNICAL_ERROR = 492;

	/**
	 * Payment rejected
	 * @var int
	 */
	const PAYMENT_REJECTED = 690;

	/**
	 * Waiting for user input
	 * @var int
	 */
	const WAITING_FOR_USER_INPUT = 790;

	/**
	 * Waiting for processor
	 * @var int
	 */
	const WAITING_FOR_PROCESSOR = 791;

	/**
	 * Waiting on consumer action (e.g.: initiate money transfer)
	 * @var int
	 */
	const WAITING_ON_CONSUMER_ACTION = 792;

	/**
	 * Payment on hold (e.g. waiting for sufficient balance)
	 * @var int
	 */
	const PAYMENT_ON_HOLD = 793;

	/**
	 * Cancelled by consumer
	 * @var int
	 */
	const CANCELLED_BY_CONSUMER = 890;

	/**
	 * Cancelled by merchant
	 * @var int
	 */
	const CANCELLED_BY_MERCHANT = 891;

	/////////////////////////////////////////////////

	/**
	 * Transform an Buckaroo state to an more global status
	 *
	 * @param string $status
	 */
	public static function transform( $status_code ) {
		switch ( $status_code ) {
			case Pronamic_Gateways_Buckaroo_Statuses::PAYMENT_SUCCESS:
				return Pronamic_Pay_Gateways_IDeal_Statuses::SUCCESS;
			case Pronamic_Gateways_Buckaroo_Statuses::PAYMENT_FAILURE:
			case Pronamic_Gateways_Buckaroo_Statuses::VALIDATION_FAILURE:
			case Pronamic_Gateways_Buckaroo_Statuses::TECHNICAL_ERROR:
			case Pronamic_Gateways_Buckaroo_Statuses::PAYMENT_REJECTED:
				return Pronamic_Pay_Gateways_IDeal_Statuses::FAILURE;
			case Pronamic_Gateways_Buckaroo_Statuses::WAITING_FOR_USER_INPUT:
			case Pronamic_Gateways_Buckaroo_Statuses::WAITING_FOR_PROCESSOR:
			case Pronamic_Gateways_Buckaroo_Statuses::WAITING_ON_CONSUMER_ACTION:
			case Pronamic_Gateways_Buckaroo_Statuses::PAYMENT_ON_HOLD:
				return Pronamic_Pay_Gateways_IDeal_Statuses::OPEN;
			case Pronamic_Gateways_Buckaroo_Statuses::CANCELLED_BY_CONSUMER:
			case Pronamic_Gateways_Buckaroo_Statuses::CANCELLED_BY_MERCHANT:
				return Pronamic_Pay_Gateways_IDeal_Statuses::CANCELLED;
		}
	}
}
