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
}
