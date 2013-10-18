<?php

/**
 * Pronamic Pay get page ID
 * 
 * @see https://github.com/woothemes/woocommerce/blob/v2.0.16/woocommerce-core-functions.php#L344
 * 
 * @param string $page
 * @return int
 */
function pronamic_pay_get_page_id( $page ) {
	$option = sprintf( 'pronamic_pay_%s_page_id', $page );

	return get_option( $option, false );
}
