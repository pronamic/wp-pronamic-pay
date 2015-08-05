<?php

/**
 * Execute changes made in Pronamic Pay 3.7.0
 *
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/upgrade.php#L413
 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/updates/woocommerce-update-2.2.php
 * @since 3.7.0
 */

global $wpdb;

// Payments pending
$query = "
	UPDATE
		$wpdb->posts AS post
			LEFT JOIN
		$wpdb->postmeta AS meta_status
				ON post.ID = meta_status.post_id AND meta_status.meta_key = '_pronamic_payment_status'
	SET
		post.post_status = 'payment_pending'
	WHERE
		post.post_type = 'pronamic_payment'
			AND
		post.post_status = 'publish'
			AND
		meta_status.meta_value IS NULL
	;
";

$wpdb->query( $query );

// Payments success
$query = "
	UPDATE
		$wpdb->posts AS post
			LEFT JOIN
		$wpdb->postmeta AS meta_status
				ON post.ID = meta_status.post_id AND meta_status.meta_key = '_pronamic_payment_status'
	SET
		post.post_status = 'payment_completed'
	WHERE
		post.post_type = 'pronamic_payment'
			AND
		post.post_status = 'publish'
			AND
		meta_status.meta_value = 'Success'
	;
";

$wpdb->query( $query );
