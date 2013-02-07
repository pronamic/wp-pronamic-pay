<?php

/**
 * Title: TargetPay response codes
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_TargetPay_ResponseCodes {
	/**
	 * OK
	 * 
	 * @var string
	 */
	const OK = '000000';

	/**
	 * Transaction has not been completed, try again later
	 * 
	 * @var string
	 */
	const TRANSACTION_NOT_COMPLETED = ' TP0010';

	/**
	 * Transaction has been cancelled
	 * 
	 * @var string
	 */
	const TRANSACTION_CANCLLED = 'TP0011';

	/**
	 * Transaction has expired (max. 10 minutes)
	 *
	 * @var string
	 */
	const TRANSACTION_EXPIRED = 'TP0012';

	/**
	 * The transaction could not be processed
	 *
	 * @var string
	 */
	const TRANSACTION_NOT_PROCESSED = 'TP0013';

	/**
	 * Already used
	 *
	 * @var string
	 */
	const ALREADY_USED = 'TP0014';

	/**
	 * Layoutcode not entered
	 *
	 * @var string
	 */
	const LAYOUTCODE_NOT_ENTERED = 'TP0020';

	/**
	 * Tansaction ID not entered
	 *
	 * @var string
	 */
	const TRANSACTION_ID_NOT_ENTERED = 'TP0021';

	/**
	 * No transaction found with this ID
	 * 
	 * @var string
	 */
	const TRANSACTION_NOT_FOUND = 'TP0022';

	/**
	 * Layoutcode does not match this transaction
	 *
	 * @var string
	 */
	const LAYOUCODE_NOT_MATCH_TRANSACTION = 'TP0023';
}
