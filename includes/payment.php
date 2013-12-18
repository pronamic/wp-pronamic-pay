<?php

function pronamic_wp_pay_update_payment( Pronamic_WP_Pay_Payment $payment ) {
	$post_id = $payment->get_id();

	// Meta
	$prefix = '_pronamic_payment_';

	$meta = array_merge(
		array(
			'transaction_id'          => $payment->get_transaction_id(),
			'action_url'              => $payment->get_action_url(),
			'status'                  => $payment->status,
			'consumer_name'           => $payment->consumer_name,
			'consumer_account_number' => $payment->consumer_account_number,
			'consumer_iban'           => $payment->consumer_iban,
			'consumer_bic'            => $payment->consumer_bic,
			'consumer_city'           => $payment->consumer_city,
		),
		$payment->meta
	);

	foreach ( $meta as $key => $value ) {
		if ( ! empty( $value ) ) {
			$meta_key = $prefix . $key;

			update_post_meta( $post_id, $meta_key, $value );
		}
	}
}
