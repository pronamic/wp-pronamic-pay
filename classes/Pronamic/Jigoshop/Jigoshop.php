<?php 

/**
 * Title: Jigoshop
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Jigoshop_Jigoshop {
	/**
	 * Order status pending
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L269
	 * @var string
	 */
	const ORDER_STATUS_PENDING = 'pending';

	/**
	 * Order status on-hold
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L270
	 * @var string
	 */
	const ORDER_STATUS_ON_HOLD = 'on-hold';

	/**
	 * Order status processing
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L271
	 * @var string
	 */
	const ORDER_STATUS_PROCESSING = 'processing';

	/**
	 * Order status completed
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L272
	 * @var string
	 */
	const ORDER_STATUS_COMPLETED = 'completed';

	/**
	 * Order status refunded
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L273
	 * @var string
	 */
	const ORDER_STATUS_REFUNDED = 'refunded';

	/**
	 * Order status cancelled
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/jigoshop/tags/1.2.1/admin/jigoshop-install.php#L274
	 * @var string
	 */
	const ORDER_STATUS_CANCELLED = 'cancelled';
}
