<?php 

/**
 * Title: Membership
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WPMUDEV_Membership_Membership {
	/**
	 * Check if Membership is active (Automattic/developer style)
	 *
	 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membership.php
	 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.membership.php#L5
	 * @see https://github.com/Automattic/developer/blob/1.1.2/developer.php#L73
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return class_exists( 'M_Membership' );
	}

	/**
	 * Check if the Membership pricing array is free
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/gateways/gateway.paypalexpress.php#L578
	 * 
	 * @param array $pricing
	 */
	public static function is_pricing_free( $pricing ) {
		$free = true;
		
		if ( is_array( $pricing ) ) {
			foreach ( $pricing as $key => $price ) {
				if ( isset( $price['amount'] ) && $price['amount'] > 0 ) {
					$free = false;
				}
			}
		}
		
		return $free;
	}
}
