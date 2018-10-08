<?php
/**
 * Plugin Name: Pronamic Pay
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-ideal/
 * Description: The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.
 *
 * Version: 5.4.2
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

	wp_die(
		wp_kses(
			$message,
			array(
				'a' => array(
					'href'   => true,
					'target' => true,
				),
			)
		)
	);
}

if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	register_activation_hook( __FILE__, 'pronamic_pay_block_activation' );

	add_action( 'admin_init', 'pronamic_pay_deactivate' );

	return;
}

/**
 * Autoload.
 */
if ( ! defined( 'PRONAMIC_PAY_DEBUG' ) ) {
	define( 'PRONAMIC_PAY_DEBUG', false );
}

if ( PRONAMIC_PAY_DEBUG ) {
	foreach ( glob( __DIR__ . '/repositories/wp-pay/*/vendor/composer/autoload_files.php' ) as $file ) {
		$files = require $file;

		foreach ( $files as $identifier => $path ) {
			if ( ! empty( $GLOBALS['__composer_autoload_files'][ $identifier ] ) ) {
				continue;
			}

			require $path;

			$GLOBALS['__composer_autoload_files'][ $identifier ] = true;
		}
	}
}

$loader = require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

if ( PRONAMIC_PAY_DEBUG ) {
	foreach ( glob( __DIR__ . '/repositories/*/*/composer.json' ) as $file ) {
		$content = file_get_contents( $file );

		$object = json_decode( $content );

		if ( ! isset( $object->autoload ) ) {
			continue;
		}

		foreach ( $object->autoload as $type => $map ) {
			if ( 'psr-4' !== $type ) {
				continue;
			}

			foreach ( $map as $prefix => $path ) {
				$loader->addPsr4( $prefix, dirname( $file ) . '/' . $path, true );
			}
		}
	}
}

/**
 * Bootstrap.
 */
\Pronamic\WordPress\Pay\Plugin::instance(
	array(
		'file'       => __FILE__,
		'version'    => '5.4.2',
		'gateways'   => \Pronamic\WordPress\Pay\pronamic_pay_gateway_integrations(),
		'extensions' => array(
			'\Pronamic\WordPress\Pay\Extensions\Charitable\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\Give\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\WooCommerce\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\GravityForms\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\Shopp\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\Jigoshop\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\WPeCommerce\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\ClassiPress\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\EventEspressoLegacy\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\EventEspresso\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\AppThemes\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\S2Member\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\Membership\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\EasyDigitalDownloads\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\IThemesExchange\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\MemberPress\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\FormidableForms\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\RestrictContentPro\Extension::bootstrap',
			'\Pronamic\WordPress\Pay\Extensions\NinjaForms\Extension::bootstrap',
		),
	)
);

/**
 * Backward compatibility.
 */
global $pronamic_ideal;

$pronamic_ideal = pronamic_pay_plugin();
