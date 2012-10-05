<?php 

/**
 * Title: WP e-Commerce
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WPeCommerce_WPeCommerce {
	/**
	 * Purche status
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-core/wpsc-functions.php#L115
	 * @var int
	 */
	const PURCHASE_STATUS_INCOMPLETE_SALE = 1;

	/**
	 * Purche status
	 *  
	 * @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-core/wpsc-functions.php#L115
	 * @var int
	 */
	const PURCHASE_STATUS_ORDER_RECEIVED = 2;

	/**
	 * Purche status
	 *  
	 * @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-core/wpsc-functions.php#L115
	 * @var int
	 */
	const PURCHASE_STATUS_ACCEPTED_PAYMENT = 3;

	/**
	 * Purche status
	 *  
	 * @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-core/wpsc-functions.php#L115
	 * @var int
	 */
	const PURCHASE_STATUS_JOB_DISPATCHED = 4;

	/**
	 * Purche status
	 *  
	 * @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-core/wpsc-functions.php#L115
	 * @var int
	 */
	const PURCHASE_STATUS_CLOSED_ORDER = 5;

	/**
	 * Purche status
	 *  
	 * @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-core/wpsc-functions.php#L115
	 * @var int
	 */
	const PURCHASE_STATUS_DECLINED_PAYMENT = 6;
}
