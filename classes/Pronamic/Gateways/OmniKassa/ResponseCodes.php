<?php

/**
 * Title: OmniKassa response codes
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_OmniKassa_ResponseCodes {
	/**
	 * Transaction successful.
	 *
	 * @var string
	 */
	const TRANSACTION_SUCCES = '00';

	/**
	 * Credit card authorisation limit exceeded. Contact the Support Team Rabo OmniKassa.
	 *
	 * @var string
	 */
	const AUTHORIZATION_LIMIT = '02';

	/**
	 * Invalid merchant contract.
	 *
	 * @var string
	 */
	const INVALID_MERCHANT_CONTRACT = '03';

	/**
	 * Refused.
	 *
	 * @var string
	 */
	const AUTHORIZATION_REFUSED = '05';

	/**
	 * Invalid transaction. Check the fields in the payment request.
	 *
	 * @var string
	 */
	const INVALID_TRANSACTION = '12';

	/**
	 * Invalid credit card number, invalid card security code, invalid card (MasterCard) or invalid Card Verification Value (MasterCard or Visa).
	 *
	 * @var string
	 */
	const INVALID_CARD_NUMBER = '14';

	/**
	 * Cancellation of payment by user.
	 *
	 * @var string
	 */
	const CANCELLATION_OF_PAYMENT = '17';

	/**
	 * Invalid status.
	 *
	 * @var string
	 */
	const INVALID_STATUS = '24';

	/**
	 * Transaction not found in database.
	 *
	 * @var string
	 */
	const TRANSACTION_NOT_FOUND_IN_DATABASE = '25';

	/**
	 * Invalid format.
	 *
	 * @var string
	 */
	const INVALID_FORMAT = '30';

	/**
	 * Fraud suspicion.
	 *
	 * @var string
	 */
	const FRAUD_SUSPICION = '34';

	/**
	 * Operation not allowed for this merchant/webshop.
	 *
	 * @var string
	 */
	const OPERATION_NOT_ALLOWED = '40';

	/**
	 * Awaiting status report.
	 *
	 * @var string
	 */
	const PENDING_TRANSACTION = '60';

	/**
	 * Security problem detected. Transaction terminated.
	 *
	 * @var string
	 */
	const SECURITY_BREACH_DETECTED = '63';

	/**
	 * Maximum number of attempts to enter credit card number (3) exceeded.
	 *
	 * @var string
	 */
	const NUMBER_ATTEMPT_EXCEEDED = '75';

	/**
	 * Rabo OmniKassa server temporarily unavailable.
	 *
	 * @var string
	 */
	const ACQUIRER_SERVER_TEMPORARILY_UNAVAILABLE = '90';

	/**
	 * Duplicate transaction.
	 *
	 * @var string
	 */
	const DUPLICATE_TRANSACTION = '94';

	/**
	 * Time period expired. Transaction refused.
	 *
	 * @var string
	 */
	const REQUEST_TIMEOUT = '97';

	/**
	 * Payment page temporarily unavailable.
	 *
	 * @var string
	 */
	const PAYMENT_PAGE_TEMPORARILY_UNAVAILABLE = '99';

	/////////////////////////////////////////////////

	/**
	 * Transform an OmniKassa response code to an more global status
	 *
	 * @see page 30 http://pronamic.nl/wp-content/uploads/2013/10/integratiehandleiding_rabo_omnikassa_en_versie_5_0_juni_2013_10_29451215.pdf
	 *
	 * @param string $response_code
	 */
	public static function transform( $response_code ) {
		switch ( $response_code ) {
			case self::TRANSACTION_SUCCES:
				return Pronamic_Pay_Gateways_IDeal_Statuses::SUCCESS;
			case self::AUTHORIZATION_LIMIT:
			case self::AUTHORIZATION_REFUSED:
				return Pronamic_Pay_Gateways_IDeal_Statuses::FAILURE;
			case self::CANCELLATION_OF_PAYMENT:
				return Pronamic_Pay_Gateways_IDeal_Statuses::CANCELLED;
			case self::PENDING_TRANSACTION:
				return Pronamic_Pay_Gateways_IDeal_Statuses::OPEN;
			case self::REQUEST_TIMEOUT:
				return Pronamic_Pay_Gateways_IDeal_Statuses::EXPIRED;
		}
	}
}
