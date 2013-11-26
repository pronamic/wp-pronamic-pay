<?php
/*
Plugin Name: Pronamic iDEAL
Plugin URI: http://www.pronamic.eu/wordpress-plugins/pronamic-ideal/
Description: The Pronamic iDEAL plugin allows you to easily add the iDEAL payment method to your WordPress website.

Version: 2.2.2
Requires at least: 3.0

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: pronamic_ideal
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-ideal
*/

if ( function_exists( 'spl_autoload_register' ) ) {

	function pronamic_ideal_autoload( $name ) {
		$name = str_replace( '\\', DIRECTORY_SEPARATOR, $name );
		$name = str_replace( '_', DIRECTORY_SEPARATOR, $name );

		$file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';

		if ( is_file( $file ) ) {
			require_once $file;
		}
	}

	spl_autoload_register( 'pronamic_ideal_autoload' );

	Pronamic_WordPress_IDeal_Plugin::bootstrap( __FILE__ );
}
