<?php

/**
 * Execute changes made in Pronamic iDEAL 1.4.0
 *
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/upgrade.php#L413
 * @since 1.4.0
 */
function orbis_ideal_upgrade_140() {
	global $wpdb;

	/*
	UPDATE wp_pronamic_ideal_payments SET post_id = null;
	UPDATE wp_options SET option_value = 0 WHERE option_name = 'pronamic_ideal_db_version';
	DELETE FROM wp_posts WHERE post_type = 'pronamic_payment';
	DELETE FROM wp_postmeta WHERE post_id NOT IN ( SELECT ID FROM wp_posts );
	*/ 
	$payments_table = $wpdb->prefix . 'pronamic_ideal_payments';

	$query = "
		SELECT
			*
		FROM
			$payments_table
		WHERE
			post_id IS NULL
		;
	";

	$payments = $wpdb->get_results( $query );

	foreach ( $payments as $payment ) {
		// Post
		$post = array(
			'post_title'    => sprintf( __( 'Payment %d', 'pronamic_ideal' ), $payment->id ),
			'post_date_gmt' => $payment->date_gmt,
			'post_type'     => 'pronamic_payment',
			'post_status'   => 'publish'
		);
		
		$post_id = wp_insert_post( $post );

		if ( $post_id ) {
			// Meta 
			$meta = array(
				'_pronamic_payment_purchase_id'             => $payment->purchase_id,
				'_pronamic_payment_currency'                => $payment->currency,
				'_pronamic_payment_amount'                  => $payment->amount,
				'_pronamic_payment_expiration_period'       => $payment->expiration_period,
				'_pronamic_payment_language'                => $payment->language,
				'_pronamic_payment_entrance_code'           => $payment->entrance_code,
				'_pronamic_payment_description'             => $payment->description,
				'_pronamic_payment_consumer_name'           => $payment->consumer_name,
				'_pronamic_payment_consumer_account_number' => $payment->consumer_account_number,
				'_pronamic_payment_consumer_iban'           => $payment->consumer_iban,
				'_pronamic_payment_consumer_bic'            => $payment->consumer_bic,
				'_pronamic_payment_consumer_city'           => $payment->consumer_city,
				'_pronamic_payment_status'                  => $payment->status,
				'_pronamic_payment_source'                  => $payment->source,
				'_pronamic_payment_source_id'               => $payment->source_id,
				'_pronamic_payment_email'                   => $payment->email,
			);
			
			foreach ( $meta as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}
		
			$wpdb->update( $payments_table, array( 'post_id' => $post_id ), array( 'id' => $payment->id ), '%d', '%d' );
		}
	}
}
