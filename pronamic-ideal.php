<?php
/*
Plugin Name: Pronamic iDEAL
Plugin URI: http://www.happywp.com/plugins/pronamic-ideal/
Description: The Pronamic iDEAL plugin allows you to easily add the iDEAL payment method to your WordPress website.

Version: 2.8.5
Requires at least: 3.6

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: pronamic_ideal
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-ideal
*/

/**
 * Composer autoload
 */
$autoload_file = null;

if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	$autoload_file = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
} elseif ( version_compare( PHP_VERSION, '5.2', '>=' ) ) {
	$autoload_file = plugin_dir_path( __FILE__ ) . 'vendor/autoload_52.php';
}

if ( isset( $autoload_file ) && is_readable( $autoload_file ) ) {
	require_once $autoload_file;
}

/**
 * Plugin autoload
 */
if ( function_exists( 'spl_autoload_register' ) ) {

	function pronamic_ideal_autoload( $name ) {
		$name = str_replace( '\\', '/', $name );
		$name = str_replace( '_', '/', $name );

		$file = plugin_dir_path( __FILE__ ) . 'classes/' . $name . '.php';

		if ( is_file( $file ) ) {
			require_once $file;
		}
	}

	spl_autoload_register( 'pronamic_ideal_autoload' );

	Pronamic_WP_Pay_Plugin::bootstrap( __FILE__ );
}
