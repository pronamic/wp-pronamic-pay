<?php
/**
 * Plugin Name: Pronamic Pay
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-ideal/
 * Description: The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.
 *
 * Version: 5.0.1
 * Requires at least: 4.7
 *
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 *
 * Text Domain: pronamic_ideal
 * Domain Path: /languages/
 *
 * License: GPL-3.0-or-later
 *
 * GitHub URI: https://github.com/pronamic/wp-pronamic-ideal
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

/**
 * Dependency-checking.
 */
function pronamic_pay_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * Function to block activation of the plugin.
 */
function pronamic_pay_block_activation() {
	$message = sprintf(
		/* translators: 1: http://www.wpupdatephp.com/update/, 2: _blank */
		__( 'Unfortunately the Pronamic Pay plugin will no longer work correctly in PHP versions older than 5.3. Read more information about <a href="%1$s" target="%2$s">how you can update</a>.', 'pronamic_ideal' ),
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
	register_activation_hook( __FILE__, 'pronamic_pay_block_activation' );

	add_action( 'admin_init', 'pronamic_pay_deactivate' );

	return;
}

/**
 * Autoload.
 */
$loader = require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

if ( ! defined( 'PRONAMIC_PAY_DEBUG' ) ) {
	define( 'PRONAMIC_PAY_DEBUG', false );
}

if ( PRONAMIC_PAY_DEBUG ) {
	foreach ( glob( __DIR__ . '/repositories/*/*/composer.json' ) as $file ) {
		$content = file_get_contents( $file );

		$object = json_decode( $content );

		if ( isset( $object->autoload ) ) {
			foreach ( $object->autoload as $type => $map ) {
				if ( 'psr-4' === $type ) {
					foreach ( $map as $prefix => $path ) {
						$loader->addPsr4( $prefix, dirname( $file ) . '/' . $path, true );
					}
				}
			}
		}
	}
}

/**
 * Bootstrap.
 */
\Pronamic\WordPress\Pay\Plugin::instance( __FILE__ );

/**
 * Pronamic Pay plugin.
 *
 * @return \Pronamic\WordPress\Pay\Plugin
 */
function pronamic_pay_plugin() {
	return \Pronamic\WordPress\Pay\Plugin::instance();
}

/**
 * Backward compatibility.
 */
global $pronamic_ideal;

$pronamic_ideal = pronamic_pay_plugin();
