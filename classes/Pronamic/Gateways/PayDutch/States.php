<?php

/**
 * Title: PayDutch states
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Gateways_PayDutch_States {
	/**
	 * Payment methods state 'Register'
	 * 
	 * Payment registered, consumer initiated link.
	 * 
	 * @var string
	 */
	const REGISTER = 'Register';

	/**
	 * Payment methods state 'Processing'
	 * 
	 * Payment in process, consumer is paying at the moment.
	 * 
	 * @var string
	 */
	const PROCESSING = 'Processing';

	/**
	 * Payment methods state 'Income'
	 * 
	 * Consumer paid successfully to DPG account.
	 * 
	 * @var string
	 */
	const INCOME = 'Income';

	/**
	 * Payment methods state 'Assemble'
	 * 
	 * After the contractual period the payments are going to be assembled.
	 * 
	 * @var string
	 */
	const ASSEMBLE = 'Assemble';

	/**
	 * Payment methods state 'Payout'
	 * 
	 * The assembled payments are set ready for payout to the merchants account.
	 * 
	 * @var string
	 */
	const PAYOUT = 'Payout';

	/**
	 * Payment methods state 'Success'
	 * 
	 * Payout confirmed by the Bank Statement
	 * 
	 * @var string
	 */
	const SUCCESS = 'Success';

	/**
	 * Payment methods state 'Cancelled'
	 * 
	 * Consumer cancelled the payment.
	 * 
	 * @var string
	 */
	const CANCELLED = 'Cancelled';

	/**
	 * Payment methods state 'Failed'
	 * 
	 * Failed payment.
	 * 
	 * @var string
	 */
	const FAILED = 'Failed';
}
