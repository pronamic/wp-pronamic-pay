<?php

class Pronamic_WP_Pay_Gateways_ConfigProvider {
	private static $factories = array();

	public static function register( $name, $class_name ) {
		self::$factories[ $name ] = $class_name;
	}

	public static function get_config( $name, $post_id ) {
		$config = null;

		if ( isset( self::$factories[ $name ] ) ) {
			$class_name = self::$factories[ $name ];

			if ( class_exists( $class_name ) ) {
				$factory = new $class_name();

				if ( $factory instanceof Pronamic_WP_Pay_GatewayConfigFactory ) {
					$config = $factory->get_config( $post_id );

					$config->id = $post_id;
				}
			}
		}

		return $config;
	}
}
