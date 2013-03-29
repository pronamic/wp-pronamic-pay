<?php

/**
 * Title: iDEAL Internet Kassa statuses constants
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 * @see http://pronamic.nl/wp-content/uploads/2012/11/ABN-AMRO-List-of-the-payment-statuses-and-error-codes.pdf
 */
class Pronamic_Gateways_Buckaroo_Statuses {
	/**
	 * Incomplete or invalid 
	 * @var int
	 */
	const INCOMPLETE_OR_INVALID = 0;

	/**
	 * Cancelled by client
	 * @var int
	 */
	const CANCELLED_BY_CLIENT = 890;

	/**
	 * Authorization refused
	 * @var int
	 */
	const AUTHORIZATION_REFUSED = 690;

	//////////////////////////////////////////////////

	/**
	 * Order stored
	 * @var int
	 */
	const ORDER_STORED = 4;

	/**
	 * Stored waiting external result
	 * @var int
	 */
	const STORED_WAITING_EXTERNAL_RESULT = 40;

	/**
	 * Waiting client payment
	 * @var int
	 */
	const WAITING_CLIENT_PAYMENT = 792;

	//////////////////////////////////////////////////

	/**
	 * Authorized
	 * @var int
	 */
	const AUTHORIZED = 5;

	/**
	 * Authorized waiting external result
	 * @var int
	 */
	const AUHTORIZED_WAITING_EXTERNAL_RESULT = 791;

	/**
	 * Authorization waiting
	 * @var int
	 */
	const AUTHORIZATION_WAITING = 51;

	/**
	 * Authorization not known
	 * @var int
	 */
	const AUTHORIZATION_NOT_KNOWN = 52;

	/**
	 * Stand-by
	 * @var int
	 */
	const STAND_BY = 55;

	/**
	 * OK with scheduled payments
	 * @var int
	 */
	const OK_WITH_SCHEDULED_PAYMENTS = 56;

	/**
	 * Error in scheduled payments
	 * @var int
	 */
	const ERROR_IN_SCHEDULED_PAYMENTS = 57;

	/**
	 * Authoriz. to get manually
	 * @var int
	 */
	const AUHORIZ_TO_GET_MANUALLY = 59;

	//////////////////////////////////////////////////

	/**
	 * Authorized and cancelled
	 * @var int
	 */
	const AUTHORIZED_AND_CANCELLED = 6;

	/**
	 * Author. deletion waiting
	 * @var int
	 */
	const AUTHOR_DELETION_WAITING = 61;

	/**
	 * Author. deletion uncertain
	 * @var int
	 */
	const AUTHOR_DELETION_UNCERTAIN = 62;

	/**
	 * Author. deletion refused
	 * @var int
	 */
	const AUTHOR_DELETION_REFUSED = 63;

	/**
	 * Authorized and cancelled
	 * @var int
	 */
	const AUTHORIZED_AND_CANCELLED_64 = 64;

	//////////////////////////////////////////////////

	/**
	 * Payment deleted
	 * @var int
	 */
	const PAYMENT_DELETED = 7;

	/**
	 * Payment deletion pending
	 * @var int
	 */
	const PAYMENT_DELETION_PENDING = 71;

	/**
	 * Payment deletion uncertain
	 * @var int
	 */
	const PAYMENT_DELETION_UNCERTAIN = 72;

	/**
	 * Payment deletion refused
	 * @var int
	 */
	const PAYMENT_DELETION_REFUSED = 73;

	/**
	 * Payment deleted
	 * @var int
	 */
	const PAYMENT_DELETED_74 = 74;

	/**
	 * Deletion processed by merchant
	 * @var int
	 */
	const DELETION_PROCESSED_BY_MERCHANT = 891;

	//////////////////////////////////////////////////

	/**
	 * Refund
	 * @var int
	 */
	const REFUND = 8;

	/**
	 * Refund pending
	 * @var int
	 */
	const REFUND_PENDING = 81;

	/**
	 * Refund uncertain
	 * @var int
	 */
	const REFUND_UNCERTAIN = 82;

	/**
	 * Refund refused
	 * @var int
	 */
	const REFUND_REFUSED = 83;

	/**
	 * Payment declined by the acquirer
	 * @var int
	 */
	const PAYMENT_DECLIEND_BY_THE_ACQUIRER = 84;

	/**
	 * Refund processed by merchant
	 * @var int
	 */
	const REFUND_PROCESSED_BY_MERCHANT = 85;

	//////////////////////////////////////////////////

	/**
	 * Payment requested
	 * @var int
	 */
	const PAYMENT_REQUESTED = 9;

	/**
	 * Payment processing
	 * @var int
	 */
	const PAYMENT_PROCESSING = 91;

	/**
	 * Payment uncertain
	 * @var int
	 */
	const PAYMENT_UNCERTAIN = 92;

	/**
	 * Payment refused
	 * @var int
	 */
	const PAYMENT_REFUSED = 490;

	/**
	 * Refund declined by the acquirer
	 * @var int
	 */
	const REFUND_DECLINED_BY_THE_ACQUIRER = 94;

	/**
	 * Payment processed by merchant
	 * @var int
	 */
	const PAYMENT_PROCESSED_BY_MERCHANT = 190;

	/**
	 * Being processed
	 * @var int
	 */
	const BEING_PROCESSED = 99;
}
