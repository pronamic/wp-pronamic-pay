<?php

use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Subscriptions\Subscription;

function get_pronamic_payment( $post_id ) {
	$payment = new Payment( $post_id );

	return $payment;
}

function get_pronamic_payment_by_meta( $meta_key, $meta_value ) {
	global $wpdb;

	$payment = null;

	$db_query = $wpdb->prepare( "
		SELECT
			post_id
		FROM
			$wpdb->postmeta
		WHERE
			meta_key = %s
				AND
			meta_value = %s
			;
	", $meta_key, $meta_value );

	$post_id = $wpdb->get_var( $db_query ); // WPCS: unprepared SQL ok.

	if ( $post_id ) {
		$payment = new Payment( $post_id );
	}

	return $payment;
}

function get_pronamic_payments_by_meta( $meta_key, $meta_value ) {
	global $wpdb;

	$payments = array();

	$db_query = $wpdb->prepare( "
		SELECT
			post_id
		FROM
			$wpdb->postmeta
		WHERE
			meta_key = %s
				AND
			meta_value = %s
		ORDER BY
			meta_id ASC
			;
	", $meta_key, $meta_value );

	$results = $wpdb->get_results( $db_query ); // WPCS: unprepared SQL ok.

	foreach ( $results as $result ) {
		$payments[] = new Payment( $result->post_id );
	}

	return $payments;
}

function get_pronamic_payment_by_purchase_id( $purchase_id ) {
	return get_pronamic_payment_by_meta( '_pronamic_payment_purchase_id', $purchase_id );
}

function get_pronamic_payment_by_transaction_id( $transaction_id, $entrance_code = null ) {
	return get_pronamic_payment_by_meta( '_pronamic_payment_transaction_id', $transaction_id );
}

function get_pronamic_subscription( $post_id ) {
	$subscription = new Subscription( $post_id );

	return $subscription;
}

function get_pronamic_subscription_by_meta( $meta_key, $meta_value ) {
	global $wpdb;

	$subscription = null;

	$db_query = $wpdb->prepare( "
		SELECT
			post_id
		FROM
			$wpdb->postmeta
		WHERE
			meta_key = %s
				AND
			meta_value = %s
			;
	", $meta_key, $meta_value );

	$post_id = $wpdb->get_var( $db_query ); // WPCS: unprepared SQL ok.

	if ( $post_id ) {
		$subscription = new Subscription( $post_id );
	}

	return $subscription;
}

function bind_providers_and_gateways() {
	global $pronamic_pay_providers;

	global $pronamic_ideal;

	foreach ( $pronamic_ideal->gateway_integrations as $integration ) {
		if ( isset( $pronamic_pay_providers[ $integration->provider ] ) ) {
			$provider =& $pronamic_pay_providers[ $integration->provider ];

			if ( ! isset( $provider['integrations'] ) ) {
				$provider['integrations'] = array();
			}

			$provider['integrations'][] = $integration;
		}
	}
}

/**
 * Let to num function
 *
 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
 *
 * @see https://github.com/woothemes/woocommerce/blob/v2.0.20/woocommerce-core-functions.php#L1779
 * @access public
 * @param $size
 * @return int
 */
function pronamic_pay_let_to_num( $size ) {
	$l   = substr( $size, -1 );
	$ret = substr( $size, 0, -1 );

	switch ( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
			// no break
		case 'T':
			$ret *= 1024;
			// no break
		case 'G':
			$ret *= 1024;
			// no break
		case 'M':
			$ret *= 1024;
			// no break
		case 'K':
			$ret *= 1024;
			// no break
	}

	return $ret;
}

/**
 * Return the thousand separator
 *
 * @return string
 */
function pronamic_pay_get_thousands_separator() {
	global $wp_locale;

	// Seperator
	$separator = get_option( 'pronamic_pay_thousands_sep' );

	// WordPress
	if ( false === $separator ) {
		// WordPress locale number format was introduced in WordPress version 2.3
		// @see https://github.com/WordPress/WordPress/blob/2.3/wp-includes/locale.php#L90-L100
		$separator = $wp_locale->number_format['thousands_sep'];
	}

	return $separator;
}

/**
 * Return the decimal separator
 *
 * @return string
 */
function pronamic_pay_get_decimal_separator() {
	global $wp_locale;

	// Seperator
	$separator = get_option( 'pronamic_pay_decimal_sep' );

	// WordPress
	if ( false === $separator ) {
		// WordPress locale number format was introduced in WordPress version 2.3
		// @see https://github.com/WordPress/WordPress/blob/2.3/wp-includes/locale.php#L90-L100
		$separator = $wp_locale->number_format['decimal_point'];
	}

	return $separator ? $separator : '.';
}
