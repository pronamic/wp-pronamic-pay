<?php
/**
 * Functions
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Subscriptions\Subscription;

/**
 * Get payment by specified post ID.
 *
 * @param int|string $post_id A payment post ID.
 * @return Payment
 */
function get_pronamic_payment( $post_id ) {
	$payment = new Payment( $post_id );

	return $payment;
}

/**
 * Get payment by specified meta key and value.
 *
 * @param string $meta_key   The meta key to query for.
 * @param string $meta_value The Meta value to query for.
 * @return Payment|null
 */
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

/**
 * Get payments by specified meta key and value.
 *
 * @param string $meta_key   The meta key to query for.
 * @param string $meta_value The Meta value to query for.
 * @return array
 */
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

/**
 * Get payment by the specified purchase ID.
 *
 * @param string $purchase_id The purchase ID to query for.
 * @return Payment|null
 */
function get_pronamic_payment_by_purchase_id( $purchase_id ) {
	return get_pronamic_payment_by_meta( '_pronamic_payment_purchase_id', $purchase_id );
}

/**
 * Get payment by the specified transaction ID.
 *
 * @param string $transaction_id The transaction ID to query for.
 * @param string $entrance_code  The entrance code to query for.
 * @return Payment|null
 */
function get_pronamic_payment_by_transaction_id( $transaction_id, $entrance_code = null ) {
	return get_pronamic_payment_by_meta( '_pronamic_payment_transaction_id', $transaction_id );
}

/**
 * Get subscription by the specified post ID.
 *
 * @param int $post_id A subscription post ID.
 * @return Subscription
 */
function get_pronamic_subscription( $post_id ) {
	if ( empty( $post_id ) ) {
		return;
	}

	$subscription = new Subscription( $post_id );

	if ( ! isset( $subscription->post ) ) {
		return;
	}

	return $subscription;
}

/**
 * Get subscription by the specified meta key and value.
 *
 * @param string $meta_key   The meta key to query for.
 * @param string $meta_value The Meta value to query for.
 * @return Subscription|null
 */
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

/**
 * Bind the global providers and gateways together.
 */
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
 * Let to num function.
 *
 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
 *
 * @see https://github.com/woothemes/woocommerce/blob/v2.0.20/woocommerce-core-functions.php#L1779
 * @access public
 * @param string $size A php.ini notation for nubmer to convert to an integer.
 * @return int
 */
function pronamic_pay_let_to_num( $size ) {
	$l   = substr( $size, -1 );
	$ret = substr( $size, 0, -1 );

	switch ( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
			// no break.
		case 'T':
			$ret *= 1024;
			// no break.
		case 'G':
			$ret *= 1024;
			// no break.
		case 'M':
			$ret *= 1024;
			// no break.
		case 'K':
			$ret *= 1024;
			// no break.
	}

	return $ret;
}

/**
 * Return the thousand separator.
 *
 * @return string
 */
function pronamic_pay_get_thousands_separator() {
	global $wp_locale;

	// Seperator.
	$separator = get_option( 'pronamic_pay_thousands_sep' );

	// WordPress.
	if ( false === $separator ) {
		// WordPress locale number format was introduced in WordPress version 2.3.
		// @see https://github.com/WordPress/WordPress/blob/2.3/wp-includes/locale.php#L90-L100.
		$separator = $wp_locale->number_format['thousands_sep'];
	}

	return $separator;
}

/**
 * Return the decimal separator.
 *
 * @return string
 */
function pronamic_pay_get_decimal_separator() {
	global $wp_locale;

	// Seperator.
	$separator = get_option( 'pronamic_pay_decimal_sep' );

	// WordPress.
	if ( false === $separator ) {
		// WordPress locale number format was introduced in WordPress version 2.3.
		// @see https://github.com/WordPress/WordPress/blob/2.3/wp-includes/locale.php#L90-L100.
		$separator = $wp_locale->number_format['decimal_point'];
	}

	return $separator ? $separator : '.';
}

/**
 * Pronamic Pay get page ID.
 *
 * @see https://github.com/woothemes/woocommerce/blob/v2.0.16/woocommerce-core-functions.php#L344
 *
 * @param string $page Pronamic Pay page identifier slug.
 * @return int
 */
function pronamic_pay_get_page_id( $page ) {
	$option = sprintf( 'pronamic_pay_%s_page_id', $page );

	return get_option( $option, -1 );
}

/**
 * Helper function to update post meta data.
 *
 * @see http://codex.wordpress.org/Function_Reference/update_post_meta
 * @param int   $post_id The post ID to update the specified meta data for.
 * @param array $data    The data array with meta keys/values.
 */
function pronamic_pay_update_post_meta_data( $post_id, array $data ) {
	/*
	 * Post meta values are passed through the stripslashes() function
	 * upon being stored, so you will need to be careful when passing
	 * in values such as JSON that might include \ escaped characters.
	 */
	$data = wp_slash( $data );

	// Meta.
	foreach ( $data as $key => $value ) {
		if ( isset( $value ) && '' !== $value ) {
			update_post_meta( $post_id, $key, $value );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}
}

