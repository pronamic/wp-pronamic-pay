<?php

/**
 * Update subscription post.
 *
 * @param Pronamic_WP_Pay_Subscription $subscription
 */
function pronamic_wp_pay_update_subscription( $subscription ) {
	if ( ! $subscription ) {
		return;
	}

	$post_id = $subscription->get_id();

	// Meta
	$prefix = '_pronamic_subscription_';

	$meta = array_merge(
		array(
			'transaction_id'          => $subscription->get_transaction_id(),
			'status'                  => $subscription->get_status(),
		),
		$subscription->meta
	);

	foreach ( $meta as $key => $value ) {
		if ( ! empty( $value ) ) {
			$meta_key = $prefix . $key;

			update_post_meta( $post_id, $meta_key, $value );
		}
	}

	$status = get_post_meta( $post_id, '_pronamic_subscription_status', true );

	$post_status = null;

	switch ( $status ) {
		case Pronamic_WP_Pay_Statuses::OPEN :
			$post_status = 'subscr_pending';

			break;
		case Pronamic_WP_Pay_Statuses::CANCELLED :
			$post_status = 'subscr_cancelled';

			break;
		case Pronamic_WP_Pay_Statuses::EXPIRED :
			$post_status = 'subscr_expired';

			break;
		case Pronamic_WP_Pay_Statuses::FAILURE :
			$post_status = 'subscr_failed';

			break;
		case Pronamic_WP_Pay_Statuses::SUCCESS :
			$post_status = 'subscr_active';

			break;
		case Pronamic_WP_Pay_Statuses::COMPLETED :
			$post_status = 'subscr_completed';

			break;
	}

	if ( null !== $post_status ) {
		wp_update_post( array(
			'ID'          => $subscription->post->ID,
			'post_status' => $post_status,
		) );
	}
}
