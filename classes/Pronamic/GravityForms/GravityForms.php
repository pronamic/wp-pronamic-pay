<?php 

namespace Pronamic\GravityForms;

/**
 * Title: Gravity Forms
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class GravityForms {
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
}
