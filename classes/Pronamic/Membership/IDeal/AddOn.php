<?php

/**
 * Title: WPMU Membership iDEAL add-on
 * Copyright: Pronamic (c) 2005 - 2013
 * Company: Pronamic
 * @author Leon Rowland <leon@rowland.nl>
 * @since 1.2.6
 */
class Pronamic_Membership_IDeal_Addon {

	public static function bootstrap() {
		if ( function_exists( 'M_get_membership_active' ) && 'no' != M_get_membership_active() ) {
			self::load();
		}
	}

	public static function load() {
		// Register Bridge Settings
		new Pronamic_Membership_Bridge_Settings();

		// Register the Gateway Class
		new Pronamic_Membership_IDeal_IDealGateway();
		M_register_gateway( 'ideal', 'Pronamic_Membership_IDeal_IDealGateway' );

		add_action( 'pronamic_ideal_status_update', array( __CLASS__, 'status_update' ) );
	}

	public static function record_transaction( $user_id, $sub_id, $amount, $currency, $timestamp, $paypal_ID, $status, $note ) {

		global $wpdb;

		$subscription_transaction_table = membership_db_prefix( $wpdb, 'subscription_transaction' );

		$data = array( );
		$data[ 'transaction_subscription_ID' ] = $sub_id;
		$data[ 'transaction_user_ID' ] = $user_id;
		$data[ 'transaction_paypal_ID' ] = $paypal_ID;
		$data[ 'transaction_stamp' ] = $timestamp;
		$data[ 'transaction_currency' ] = $currency;
		$data[ 'transaction_status' ] = $status;
		$data[ 'transaction_total_amount' ] = (int) round( $amount * 100 );
		$data[ 'transaction_note' ] = $note;
		$data[ 'transaction_gateway' ] = 'ideal';

		$existing_id = $wpdb->get_var( $wpdb->prepare( "SELECT transaction_ID FROM {$subscription_transaction_table} WHERE transaction_paypal_ID = %s LIMIT 1", $paypal_ID ) );

		if ( ! empty( $existing_id ) ) {
			// Update
			$wpdb->update( $subscription_transaction_table, $data, array( 'transaction_ID' => $existing_id ) );
		} else {
			// Insert
			$wpdb->insert( $subscription_transaction_table, $data );
		}
	}

	public static function status_update( Pronamic_WordPress_IDeal_Payment $payment, $can_redirect = false ) {
		if ( 'membership' == $payment->getSource() ) {
			// Get the raw source id
			$data_id = $payment->getSourceId();

			// Split the order id to get an array of userid, subscriptionid and time
			$data = explode( '$', $data_id );

			// Prepared data
			$user_id = $data[ 0 ];
			$sub_id = $data[ 1 ];
			$time = $data[ 2 ];

			switch ( $payment->status ) {
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
					self::record_transaction( $user_id, $sub_id, $payment->amount, 'EUR', $time, $data_id, 'Success', '' );

					$member = new M_Membership( $user_id );
					if ( $member ) {
						$member->create_subscription( $sub_id, 'ideal' );
					}

					do_action( 'membership_payment_processed', $user_id, $sub_id, $payment->amount, 'EUR', $data_id );
					break;
			}
		}
	}

	public function encrypt_data( $subscription_id, $pricing, $user_id ) {
		return sha1( $subscription_id . $pricing . $user_id . AUTH_SALT );
	}

}