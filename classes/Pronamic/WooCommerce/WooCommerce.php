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

	//////////////////////////////////////////////////

	/**
	 * Get order pay URL for backwards compatibility
	 *
	 * @param WC_Order $order
	 * @return string the pay URL
	 */
	public static function get_order_pay_url( $order ) {
		$url = null;

		if ( method_exists( $order, 'get_checkout_payment_url' ) ) {
			// WooCommerce >= 2.1
			// @see http://docs.woothemes.com/document/woocommerce-endpoints-2-1/
			// @see https://github.com/woothemes/woocommerce/blob/v2.1.0/includes/class-wc-order.php#L1057-L1079
			$url = $order->get_checkout_payment_url( true );
		} else {
			// WooCommerce < 2.1
			$url = add_query_arg(
					array(
							'order' => $order->id,
							'key'   => $order->order_key,
					),
					get_permalink( woocommerce_get_page_id( 'pay' ) )
			);
		}

		return $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Add notice
	 *
	 * @param string $notice
	 */
	public static function add_notice( $message, $type = 'success' ) {
		global $woocommerce;

		if ( function_exists( 'wc_add_notice' ) ) {
			// @see https://github.com/woothemes/woocommerce/blob/v2.1.0/includes/wc-notice-functions.php#L54-L71
			wc_add_notice( $message, $type );
		} elseif ( 'error' == $type && method_exists( $woocommerce, 'add_error' ) ) {
			// @see https://github.com/woothemes/woocommerce/blob/v2.0.0/woocommerce.php#L1429-L1438
			// @see https://github.com/woothemes/woocommerce/blob/v2.1.0/woocommerce.php#L797-L804
			$woocommerce->add_error( $message );
		} elseif ( method_exists( $woocommerce, 'add_message' ) ) {
			// @see https://github.com/woothemes/woocommerce/blob/v2.0.0/woocommerce.php#L1441-L1450
			// @see https://github.com/woothemes/woocommerce/blob/v2.1.0/woocommerce.php#L806-L813
			$woocommerce->add_message( $message );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Order has status
	 *
	 * @param WC_Order $order
	 * @param string|array $status
	 */
	public static function order_has_status( $order, $status ) {
		$has_status = false;

		if ( method_exists( $order, 'has_status' ) ) {
			$has_status = $order->has_status( $status );
		} else {
			if ( is_array( $status ) ) {
				$has_status = in_array( $order->status, $status );
			} else {
				$has_status = ( $order->status == $status );
			}
		}

		return $has_status;
	}
}
