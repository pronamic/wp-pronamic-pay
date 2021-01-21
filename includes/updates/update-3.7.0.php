<?php
/**
 * Update 3.7.0
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

/**
 * Execute changes made in Pronamic Pay 3.7.0
 *
 * @link https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/upgrade.php#L413
 * @link https://github.com/woothemes/woocommerce/blob/2.3.13/includes/updates/woocommerce-update-2.2.php
 * @since 3.7.0
 */

global $wpdb;

// States
$states = array(
	''          => 'payment_pending',
	'Success'   => 'payment_completed',
	'Cancelled' => 'payment_cancelled',
	'Expired'   => 'payment_expired',
	'Failure'   => 'payment_failed',
	'Open'      => 'payment_pending',
);

foreach ( $states as $meta_value => $post_status ) {
	$condition = empty( $meta_value ) ? 'IS NULL' : '= %s';

	$query = "
		UPDATE
			$wpdb->posts AS post
				LEFT JOIN
			$wpdb->postmeta AS meta_status
					ON post.ID = meta_status.post_id AND meta_status.meta_key = '_pronamic_payment_status'
		SET
			post.post_status = %s
		WHERE
			post.post_type = 'pronamic_payment'
				AND
			post.post_status = 'publish'
				AND
			meta_status.meta_value $condition
		;
	";

	$query = $wpdb->prepare( $query, $post_status, $meta_value ); // WPCS: unprepared SQL ok.

	$wpdb->query( $query ); // WPCS: unprepared SQL ok.
}
