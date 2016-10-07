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

	$post_status = null;

	switch ( $payment->status ) {
		case Pronamic_WP_Pay_Statuses::CANCELLED :
			$post_status = 'payment_cancelled';

			break;
		case Pronamic_WP_Pay_Statuses::EXPIRED :
			$post_status = 'payment_expired';

			break;
		case Pronamic_WP_Pay_Statuses::FAILURE :
			$post_status = 'payment_failed';

			break;
		case Pronamic_WP_Pay_Statuses::OPEN :
			$post_status = 'payment_pending';

			break;
		case Pronamic_WP_Pay_Statuses::SUCCESS :
			$post_status = 'payment_completed';

			break;
	}

	if ( null !== $post_status ) {
		wp_update_post( array(
			'ID'          => $payment->post->ID,
			'post_status' => $post_status,
		) );
	}
}
