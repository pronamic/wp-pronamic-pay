<?php

class Pronamic_WP_Pay_Gateways_ConfigProvider {
	private static $factories = array();

	public static function register( $name, $class_name ) {
		self::$factories[$name] = $class_name;
	}

	public static function get_config( $name, $post_id ) {
		$config = null;

		if ( isset( self::$factories[$name] ) ) {
			$class_name = self::$factories[$name];

			if ( class_exists( $class_name ) ) {
				// Fix - Parse error: syntax error, unexpected T_PAAMAYIM_NEKUDOTAYIM
				if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
					$config = $class_name::get_config( $post_id );
				} else {
					$config = call_user_func( array( $class_name, 'get_config' ), $post_id );
				}
			}
		}

		return $config;
	}
}
