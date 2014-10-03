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

	//////////////////////////////////////////////////

	/**
	 * Update entry
	 *
	 * @param array $entry
	 */
	public static function update_entry( $entry ) {
		/*
		 * GFFormsModel::update_lead() is no longer in use since version 1.8.8! Instead use GFAPI::update_entry().
		 *
		 * @see https://github.com/gravityforms/gravityforms/blob/1.8.13/forms_model.php#L587-L624
		 * @see https://github.com/gravityforms/gravityforms/blob/1.8.13/includes/api.php#L495-L654
		 * @see https://github.com/gravityforms/gravityforms/blob/1.8.7.11/forms_model.php#L587-L621
		 */
		if ( method_exists( 'GFAPI', 'update_entry' ) ) {
			GFAPI::update_entry( $entry );
		} elseif ( method_exists( 'GFFormsModel', 'update_lead' ) ) {
			GFFormsModel::update_lead( $entry );
		}
	}
}
