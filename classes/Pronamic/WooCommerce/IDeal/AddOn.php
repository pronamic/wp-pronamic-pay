<?php 

/**
 * Title: WooCommerce iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WooCommerce_IDeal_AddOn {
	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_filter('woocommerce_payment_gateways', array(__CLASS__, 'addGateway'));
	}

	//////////////////////////////////////////////////

	/**
	 * Checks if Shopp is supported
	 * 
	 * @return true if Shopp is supported, false otherwise
	 */
	public static function isWooCommerceSupported() {
		return defined('WOOCOMMERCE_VERSION');
	}

	//////////////////////////////////////////////////

	/**
	 * Add the gateway to WooCommerce
	 */
	function addGateway($methods) {
		$methods[] = 'Pronamic_WooCommerce_IDeal_IDealGateway';

		return $methods;
	}
}
