<?php

/**
 * Title: iDEAL Mollie statuses constants
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 * @see https://www.mollie.nl/support/documentatie/betaaldiensten/ideal/en/
 */
class Pronamic_Gateways_Mollie_Statuses {
	/**
	 * Success
	 * 
	 * @var string
	 */
	const SUCCESS = 'Success';

	/**
	 * Cancelled
	 * 
	 * @var string
	 */
	const CANCELLED = 'Cancelled';

	/**
	 * Expired
	 * 
	 * @var string
	 */
	const EXPIRED = 'Expired';

	/**
	 * Failure
	 * 
	 * @var string
	 */
	const FAILURE = 'Failure';

	/**
	 * Checked before
	 * 
	 * @var string
	 */
	const CHECKED_BEFORE = 'CheckedBefore';
}
