<?php 

/**
 * Title: Gravity Forms iDEAL entry
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_GravityForms_IDeal_Entry {
	/**
	 * Check if the specified entry payment is approved
	 * 
	 * @param array $entry
	 * @return boolean true if payment is approvied, false otherwise
	 */
	public static function is_payment_approved( array $entry ) {
		$approved = false;
		
		if ( isset( $entry[Pronamic_GravityForms_LeadProperties::PAYMENT_STATUS] ) ) {
			$payment_status = $entry[Pronamic_GravityForms_LeadProperties::PAYMENT_STATUS];

			$approved = ( $payment_status == Pronamic_GravityForms_PaymentStatuses::APPROVED );
		}
		
		return $approved;
	}
}
