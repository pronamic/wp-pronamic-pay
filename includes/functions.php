<?php

function get_pronamic_payment( $post_id ) {
	$payment = new Pronamic_WP_Pay_Payment( $post_id );

	return $payment;
}

function get_pronamic_pay_gateway_config( $post_id ) {
	$config = new Pronamic_WP_Pay_Config( $post_id );

	return $config;
}

function get_pronamic_payment_by_purchase_id( $purchase_id ) {
	global $wpdb;
	
	$payment = null;

	$db_query = $wpdb->prepare( "
		SELECT
			post_id
		FROM
			$wpdb->postmeta
		WHERE
			meta_key = '_pronamic_payment_purchase_id'
				AND
			meta_value = %s
			;
	", $purchase_id );

	$post_id = $wpdb->get_var( $db_query );
	
	if ( $post_id ) {
		$payment = new Pronamic_WP_Pay_Payment( $post_id );
	}
	
	return $payment;
}

function get_pronamic_payment_by_transaction_id( $transaction_id, $entrance_code = null ) {
	global $wpdb;

	$payment = null;

	$db_query = $wpdb->prepare( "
		SELECT
			post_id
		FROM
			$wpdb->postmeta
		WHERE
			meta_key = '_pronamic_payment_transaction_id'
				AND
			meta_value = %s
		;
	", $transaction_id );

	$post_id = $wpdb->get_var( $db_query );

	if ( $post_id ) {
		$payment = new Pronamic_WP_Pay_Payment( $post_id );
	}

	return $payment;
}

function bind_providers_and_gateways() {
	global $pronamic_pay_providers;
	global $pronamic_pay_gateways;
	
	foreach ( $pronamic_pay_gateways as $id => $gateway ) {
		if ( isset( $pronamic_pay_providers[$gateway['provider']] ) ) {
			$provider =& $pronamic_pay_providers[$gateway['provider']];
	
			if ( ! isset( $provider['gateways'] ) ) {
				$provider['gateways'] = array();
			}
	
			$provider['gateways'][$id] = $gateway;
		}
	}
}

/**
 * let_to_num function.
 * @see https://github.com/woothemes/woocommerce/blob/v2.0.20/woocommerce-core-functions.php#L1779
 *
 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
 *
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
		case 'T':
			$ret *= 1024;
		case 'G':
			$ret *= 1024;
		case 'M':
			$ret *= 1024;
		case 'K':
			$ret *= 1024;
	}

	return $ret;
}
