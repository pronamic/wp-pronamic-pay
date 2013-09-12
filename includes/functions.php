<?php

function get_pronamic_payment( $post_id ) {
	$payment = new Pronamic_WP_Pay_Payment( $post_id );

	return $payment;
}

function get_pronamic_gateway_configuration( $post_id ) {
	$configuration = new Pronamic_WP_Pay_Configuration( $post_id );

	return $configuration;
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
	", $purchase_id );

	$post_id = $wpdb->get_var( $db_query );

	if ( $post_id ) {
		$payment = new Pronamic_WP_Pay_Payment( $post_id );
	}

	return $payment;
}
