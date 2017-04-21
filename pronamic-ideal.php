<?php
/*
Plugin Name: Pronamic iDEAL
Plugin URI: https://www.pronamic.eu/plugins/pronamic-ideal/
Description: The Pronamic iDEAL plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.

Version: 4.5.5
Requires at least: 4.7

Author: Pronamic
Author URI: https://www.pronamic.eu/

Text Domain: pronamic_ideal
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-ideal
*/

/**
 * Dependency-checking
 */
function pronamic_ideal_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

function pronamic_ideal_block_activation() {
	$message = sprintf(
		/* translators: 1: http://www.wpupdatephp.com/update/, 2: _blank */
		__( 'Unfortunately the Pronamic iDEAL plugin will no longer work correctly in PHP versions older than 5.3. Read more information about <a href="%1$s" target="%2$s">how you can update</a>.', 'pronamic_ideal' ),
		esc_attr__( 'http://www.wpupdatephp.com/update/', 'pronamic_ideal' ),
		esc_attr( '_blank' )
	);

	wp_die( wp_kses( $message, array(
		'a' => array(
			'href'   => true,
			'target' => true,
		),
	) ) );
}

if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	register_activation_hook( __FILE__, 'pronamic_ideal_block_activation' );

	add_action( 'admin_init', 'pronamic_ideal_deactivate' );

	return;
}

/**
 * Autoload
 */
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Bootstrap
 */
global $pronamic_ideal;

$pronamic_ideal = Pronamic_WP_Pay_Plugin::bootstrap( __FILE__ );
