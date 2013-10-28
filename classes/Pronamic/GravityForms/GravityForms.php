<?php 

/**
 * Title: Gravity Forms
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_GravityForms_GravityForms {
	/**
	 * Lead propery payment status
	 * 
	 * @var string
	 */
	const LEAD_PROPERTY_PAYMENT_STATUS = 'payment_status';
	
	/**
	 * Lead propery payment amount
	 * 
	 * @var string
	 */
	const LEAD_PROPERTY_PAYMENT_AMOUNT = 'payment_amount';
	
	/**
	 * Lead propery payment date
	 * 
	 * @var string
	 */
	const LEAD_PROPERTY_PAYMENT_DATE = 'payment_date';
	
	/**
	 * Lead propery transaction ID
	 * 
	 * @var string
	 */
	const LEAD_PROPERTY_TRANSACTION_ID = 'transaction_id';
	
	/**
	 * Lead propery transaction type
	 * 
	 * @var string
	 */
	const LEAD_PROPERTY_TRANSACTION_TYPE = 'transaction_type';

	/**
	 * Lead property created by
	 * 
	 * @var string
	 */
	const LEAD_PROPERY_CREATED_BY = 'created_by';

	//////////////////////////////////////////////////

	/**
	 * Payment status processing
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_PROCESSING = 'Processing';

	/**
	 * Payment status active
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_ACTIVE = 'Active';

	/**
	 * Payment status cancelled
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_CANCELLED = 'Cancelled';

	/**
	 * Payment status expired
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_EXPIRED = 'Expired';

	/**
	 * Payment status failed
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_FAILED = 'Failed';

	/**
	 * Payment status reversed
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_REVERSED = 'Reversed';

	/**
	 * Payment status approved
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_APPROVED = 'Approved';

	/**
	 * Payment status denied
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_DENIED = 'Denied';

	/**
	 * Payment status pending
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_PENDING = 'Pending';

	/**
	 * Payment status refunded
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_REFUNDED = 'Refunded';

	/**
	 * Payment status voided
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_VOIDED = 'Voided';

	//////////////////////////////////////////////////

	/**
	 * Indicator for an payment transaction type
	 * 
	 * @var int
	 */
	const TRANSACTION_TYPE_PAYMENT = 1;

	/**
	 * Indicator for an subscription transaction type
	 * 
	 * @var int
	 */
	const TRANSACTION_TYPE_SUBSCRIPTION = 2;

	//////////////////////////////////////////////////

	/**
	 * Operator is
	 * 
	 * @var string
	 */
	const OPERATOR_IS = '=';

	/**
	 * Operator is not
	 * 
	 * @var string
	 */
	const OPERATOR_IS_NOT = '!=';

	//////////////////////////////////////////////////

	/**
	 * Check if Gravity Forms is active (Automattic/developer style)
	 *
	 * @see https://bitbucket.org/Pronamic/gravityforms/src/42773f75ad7ad9ac9c31ce149510ff825e4aa01f/gravityforms.php?at=1.7.8#cl-95
	 * @see https://github.com/Automattic/developer/blob/1.1.2/developer.php#L73
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return class_exists( 'GFForms' );
	}
}
