<?php

/**
 * Title: WooCommerce
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WooCommerce_WooCommerce {
	/**
	 * Order status pending
	 *
	 * @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.4/admin/woocommerce-admin-install.php#L309
	 * @var string
	 */
	const ORDER_STATUS_PENDING = 'pending';

	/**
	 * Order status failed
	 *
	 * @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.4/admin/woocommerce-admin-install.php#L310
	 * @var string
	 */
	const ORDER_STATUS_FAILED = 'failed';

	/**
	 * Order status on-hold
	 *
	 * @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.4/admin/woocommerce-admin-install.php#L311
	 * @var string
	 */
	const ORDER_STATUS_ON_HOLD = 'on-hold';

	/**
	 * Order status processing
	 *
	 * @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.4/admin/woocommerce-admin-install.php#L312
	 * @var string
	 */
	const ORDER_STATUS_PROCESSING = 'processing';

	/**
	 * Order status completed
	 *
	 * @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.4/admin/woocommerce-admin-install.php#L313
	 * @var string
	 */
	const ORDER_STATUS_COMPLETED = 'completed';

	/**
	 * Order status refunded
	 *
	 * @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.4/admin/woocommerce-admin-install.php#L314
	 * @var string
	 */
	const ORDER_STATUS_REFUNDED = 'refunded';

	/**
	 * Order status cancelled
	 *
	 * @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.4/admin/woocommerce-admin-install.php#L315
	 * @var string
	 */
	const ORDER_STATUS_CANCELLED = 'cancelled';

	//////////////////////////////////////////////////

	/**
	 * Check if WooCommerce is active (Automattic/developer style)
	 *
	 * @see https://github.com/jigoshop/jigoshop/blob/1.8/jigoshop.php#L45
	 * @see https://github.com/Automattic/developer/blob/1.1.2/developer.php#L73
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return defined( 'WOOCOMMERCE_VERSION' );
	}

	//////////////////////////////////////////////////

	/**
	 * Version compare
	 *
	 * @param string $version
	 * @param string $operator
	 */
	public static function version_compare( $version, $operator ) {
		$result = true;

		// @see https://github.com/woothemes/woocommerce/blob/v1.6.6/woocommerce.php#L140
		if ( defined( 'WOOCOMMERCE_VERSION' ) ) {
			$result = version_compare( WOOCOMMERCE_VERSION, $version, $operator );
		}

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Get WooCommerce date format
	 *
	 * @return string
	 */
	public static function get_date_format() {
		if ( function_exists( 'woocommerce_date_format' ) ) {
			// @see https://github.com/woothemes/woocommerce/blob/v2.0.20/woocommerce-core-functions.php#L2169
			return woocommerce_date_format();
		} else {
			return get_option( 'date_format' );
		}
	}
}
