<?php

class Pronamic_Pay_GatewayFactory {
	private static $gateways = array();

	public static function register( $name, $class_name ) {
		self::$gateways[$name] = $class_name;
	}

	public static function create( $name, Pronamic_Pay_Configuration $configuration ) {
		$gateway = null;

		if ( isset( self::$gateways[$name] ) ) {
			$class_name = self::$gateways[$name];
			
			if ( class_exists( $class_name ) ) {
				$gateway = new $class_name( $configuration );
			}
		}
		
		return $gateway;
	}
}
