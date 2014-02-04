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

			if ( method_exists( $class_name, 'get_config' ) ) {
				// Backward compatibility PHP 5.2
				// $config = $class_name::get_config( $post_id );
				$config = call_user_func( array( $class_name, 'get_config' ), $post_id );

				$config->id = $post_id;
			}
		}

		return $config;
	}
}
