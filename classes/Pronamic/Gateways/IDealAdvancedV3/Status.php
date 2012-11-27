<?php

/**
 * Title: Transaction
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_IDealAdvancedV3_Status {
	/**
	 * Status indicator for success
	 * 
	 * @var string
	 */
	const STATUS_SUCCESS = 'Success';

	/**
	 * Status indicator for cancelled
	 * 
	 * @var string
	 */
	const STATUS_CANCELLED = 'Cancelled';
	
	/**
	 * Status indicator for expired
	 * 
	 * @var string
	 */
	const STATUS_EXPIRED = 'Expired';
	
	/**
	 * Status indicator for failure
	 * 
	 * @var string
	 */
	const STATUS_FAILURE = 'Failure';
	
	/**
	 * Status indicator for open
	 * 
	 * @var string
	 */
	const STATUS_OPEN = 'Open';
}
