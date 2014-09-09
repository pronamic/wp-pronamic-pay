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
