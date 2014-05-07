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

	//////////////////////////////////////////////////

	/**
	 * Get an subscription by an subscription ID
	 *
	 * @param int $subscription_id
	 */
	public static function get_subscription( $subscription_id ) {
		$subscription = null;

		// @see https://github.com/pronamic-wpmudev/membership-premium/blob/3.5.1.3/classes/Membership/Factory.php#L76
		if ( method_exists( 'Membership_Plugin', 'factory' ) ) {
			$factory = Membership_Plugin::factory();

			// @see https://github.com/pronamic-wpmudev/membership-premium/blob/3.5.1.3/classes/Membership/Factory.php#L108
			$subscription = $factory->get_subscription( $subscription_id );
		} elseif ( class_exists( 'M_Subscription' ) ) {
			// @see https://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.subscription.php#L26
			$subscription = new M_Subscription( $subscription_id );
		}

		return $subscription;
	}


	/**
	 * Get an membership by an user ID
	 *
	 * @param int $user_id
	 */
	public static function get_membership( $user_id ) {
		$membership = null;

		// @see https://github.com/pronamic-wpmudev/membership-premium/blob/3.5.1.3/classes/Membership/Factory.php#L76
		if ( method_exists( 'Membership_Plugin', 'factory' ) ) {
			$factory = Membership_Plugin::factory();

			// @see https://github.com/pronamic-wpmudev/membership-premium/blob/3.5.1.3/classes/Membership/Factory.php#L76
			$membership = $factory->get_member( $user_id );
		} elseif ( class_exists( 'M_Membership' ) ) {
			// @see https://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.membership.php#L18
			$membership = new M_Membership( $user_id );
		}

		return $membership;
	}
}
