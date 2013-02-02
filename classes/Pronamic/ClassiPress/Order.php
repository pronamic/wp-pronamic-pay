<?php 

/**
 * Title: Order
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_ClassiPress_Order {
	/**
	 * Check if the order info is about an advertisement
	 * 
	 * @param array $order_info
	 * @return boolean
	 */	
	public static function is_advertisement( array $order_info ) {
		return isset( $order_info['ad_id'] ) && ! empty( $order_info['ad_id'] );
	}

	//////////////////////////////////////////////////

	/**
	 * Check if the order info is about an package
	 *
	 * @param array $order_info
	 * @return boolean
	 */
	public static function is_package( array $order_info ) {
		return ! self::is_advertisement( $order_info );
	}
}
