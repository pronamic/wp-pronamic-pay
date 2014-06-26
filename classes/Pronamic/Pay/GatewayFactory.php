<?php

class Pronamic_Pay_GatewayFactory {
	private static $gateways = array();

	public static function register( $config_class, $gateway_class ) {
		self::$gateways[ $config_class ] = $gateway_class;
	}

	public static function create( Pronamic_WP_Pay_GatewayConfig $config ) {
		$gateway = null;

		$config_class = get_class( $config );

		if ( isset( self::$gateways[ $config_class ] ) ) {
			$gateway_class = self::$gateways[ $config_class ];

			if ( class_exists( $gateway_class ) ) {
				$gateway = new $gateway_class( $config );
			}
		}

		return $gateway;
	}
}
