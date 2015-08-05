<?php

/**
 * Execute changes made in Pronamic Pay 3.3.0
 *
 * @see https://github.com/WordPress/WordPress/blob/3.5.1/wp-admin/includes/upgrade.php#L413
 * @since 3.3.0
 */

$license_md5 = get_option( 'pronamic_pay_license_key' );

$url = add_query_arg( 'license', $license_md5, 'http://api.pronamic.eu/licenses/convert-md5/1.0/' );

$result = wp_remote_get( $url );

if ( 200 === wp_remote_retrieve_response_code( $result ) ) {
	$body = wp_remote_retrieve_body( $result );

	if ( 32 === strlen( $body ) ) {
		update_option( 'pronamic_pay_license_key', $body );
	}
}
