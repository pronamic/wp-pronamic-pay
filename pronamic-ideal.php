<?php
/**
 * Plugin Name: Pronamic Pay
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-pay/
 * Description: The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.
 *
 * Version: 9.20.0
 * Requires at least: 5.9
 * Requires PHP: 8.2
 *
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 *
 * Text Domain: pronamic-ideal
 * Domain Path: /languages/
 *
 * Provides: wp-pay/core
 *
 * License: GPL-2.0-or-later
 *
 * GitHub URI: https://github.com/pronamic/wp-pronamic-pay
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2026 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'PRONAMIC_PAY_DEBUG' ) ) {
	define( 'PRONAMIC_PAY_DEBUG', false );
}

/**
 * Autoload.
 */
require_once __DIR__ . '/vendor/autoload_packages.php';

/**
 * Bootstrap.
 */
add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain( 'pronamic-ideal', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
);

\Pronamic\WordPress\Pay\Plugin::instance(
	[
		'file'                 => __FILE__,
		'options'              => [
			'about_page_file' => __DIR__ . '/admin/page-about.php',
		],
		'action_scheduler'     => __DIR__ . '/packages/woocommerce/action-scheduler/action-scheduler.php',
		'pronamic_service_url' => 'https://api.wp-pay.org/wp-json/pronamic-pay/v1/payments',
	]
);

\Pronamic\WordPress\Pay\LicenseManager::instance();

\Pronamic\WordPress\Updater\Plugin::instance()->setup();

\Pronamic\PronamicPayAdminReports\Plugin::instance()->setup();

/**
 * Plugin dependencies.
 *
 * @link https://make.wordpress.org/core/2024/03/05/introducing-plugin-dependencies-in-wordpress-6-5/
 * @link https://github.com/pronamic/wp-pronamic-pay-adyen/issues/25
 */
add_filter(
	'wp_plugin_dependencies_slug',
	function ( $slug ) {
		if ( 'pronamic-ideal' === $slug ) {
			return dirname( __DIR__ );
		}

		return $slug;
	}
);

add_filter(
	'pronamic_pay_modules',
	function ( $modules ) {
		$modules[] = 'forms';
		$modules[] = 'subscriptions';

		return $modules;
	}
);

add_filter(
	'pronamic_pay_plugin_integrations',
	function ( $integrations ) {
		$classes = [
			\Pronamic\WordPress\Pay\Forms\Integration::class,
			\Pronamic\WordPress\Pay\Extensions\Charitable\Extension::class,
			\Pronamic\WordPress\Pay\Extensions\EasyDigitalDownloads\Extension::class,
			\Pronamic\WordPress\Pay\Extensions\EventEspresso\Extension::class,
			\Pronamic\WordPress\Pay\Extensions\Give\Extension::class,
			\Pronamic\WordPress\Pay\Extensions\WooCommerce\Extension::class,
			\Pronamic\WordPress\Pay\Extensions\GravityForms\Extension::class,
			\Pronamic\WordPress\Pay\Extensions\FormidableForms\Extension::class,
			\Pronamic\WordPress\Pay\Extensions\MemberPress\Extension::class,
			\Pronamic\WordPress\Pay\Extensions\NinjaForms\Extension::class,
			\Pronamic\WordPress\Pay\Extensions\RestrictContent\Extension::class,
		];

		foreach ( $classes as $class ) {
			if ( ! array_key_exists( $class, $integrations ) ) {
				$integrations[ $class ] = new $class();
			}
		}

		return $integrations;
	}
);

add_filter(
	'get_post_metadata',
	function ( $value, $post_id, $meta_key, $single ) {
		static $filter = true;

		if ( false === $filter ) {
			return $value;
		}

		if ( '_pronamic_gateway_id' !== $meta_key ) {
			return $value;
		}

		$filter = false;

		$value = get_post_meta( $post_id, $meta_key, $single );

		$filter = true;

		$mode = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		switch ( $value ) {
			case 'adyen':
				return ( 'test' === $mode ) ? 'adyen-test' : 'adyen';
			case 'buckaroo':
				return ( 'test' === $mode ) ? 'buckaroo-test' : 'buckaroo';
			case 'ems-ecommerce':
				return ( 'test' === $mode ) ? 'ems-ecommerce-test' : 'ems-ecommerce';
			case 'multisafepay-connect':
				return ( 'test' === $mode ) ? 'multisafepay-connect-test' : 'multisafepay-connect';
			case 'paypal':
				return ( 'test' === $mode ) ? 'paypal-sandbox' : 'paypal';
			case 'rabobank-omnikassa-2':
				return ( 'test' === $mode ) ? 'rabobank-omnikassa-2-sandbox' : 'rabobank-omnikassa-2';
		}

		return $value;
	},
	10,
	4
);

add_filter(
	'pronamic_pay_gateways',
	function ( $gateways ) {
		// Buckaroo.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration(
			[
				'id'   => 'buckaroo',
				'name' => 'Buckaroo',
				'mode' => 'live',
				'host' => 'checkout.buckaroo.nl',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration(
			[
				'id'   => 'buckaroo-test',
				'name' => 'Buckaroo - Test',
				'mode' => 'test',
				'host' => 'testcheckout.buckaroo.nl',
			]
		);

		// EMS - eCommerce.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration(
			[
				'id'            => 'ems-ecommerce',
				'name'          => 'EMS e-Commerce',
				'mode'          => 'live',
				'action_url'    => 'https://www.ipg-online.com/connect/gateway/processing',
				'dashboard_url' => 'https://www.ipg-online.com/vt/login',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration(
			[
				'id'            => 'ems-ecommerce-test',
				'name'          => 'EMS e-Commerce - Test',
				'mode'          => 'test',
				'action_url'    => 'https://test.ipg-online.com/connect/gateway/processing',
				'dashboard_url' => 'https://test.ipg-online.com/vt/login',
			]
		);

		// ING Checkout.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\PayNL\Integration(
			[
				'id'       => 'ing-checkout',
				'name'     => 'ING - ING Checkout',
				'mode'     => 'live',
				'provider' => 'ing',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\PayNL\Integration(
			[
				'id'       => 'ing-checkout-test',
				'name'     => 'ING - ING Checkout - Test',
				'mode'     => 'test',
				'provider' => 'ing',
			]
		);

		// Mollie.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration(
			[
				'manual_url' => 'https://www.pronamicpay.com/en/manuals/how-to-connect-mollie-to-wordpress-with-pronamic-pay/',
			]
		);

		// MultiSafePay - Connect.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\MultiSafepay\Integration(
			[
				'id'      => 'multisafepay-connect',
				'name'    => 'MultiSafepay - Connect',
				'mode'    => 'live',
				'api_url' => 'https://api.multisafepay.com/ewx/',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\MultiSafepay\Integration(
			[
				'id'      => 'multisafepay-connect-test',
				'name'    => 'MultiSafepay - Connect - Test',
				'mode'    => 'test',
				'api_url' => 'https://testapi.multisafepay.com/ewx/',
			]
		);

		// Rabobank - Rabo Smart Pay.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration(
			[
				'id'      => 'rabobank-omnikassa-2',
				'name'    => 'Rabobank - Rabo Smart Pay',
				'mode'    => 'live',
				'api_url' => 'https://betalen.rabobank.nl/omnikassa-api/',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration(
			[
				'id'      => 'rabobank-omnikassa-2-sandbox',
				'name'    => 'Rabobank - Rabo Smart Pay - Sandbox',
				'mode'    => 'test',
				'api_url' => 'https://betalen.rabobank.nl/omnikassa-api-sandbox/',
			]
		);

		// Pay.nl.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\PayNL\Integration();

		// Sisow.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration(
			[
				'id'                   => 'sisow-buckaroo',
				'name'                 => 'Sisow via Buckaroo',
				'mode'                 => 'live',
				'host'                 => 'checkout.buckaroo.nl',
				'meta_key_website_key' => 'sisow_merchant_id',
				'meta_key_secret_key'  => 'sisow_merchant_key',
				'deprecated'           => true,
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration(
			[
				'id'                   => 'sisow-buckaroo-test',
				'name'                 => 'Sisow via Buckaroo - Test',
				'mode'                 => 'test',
				'host'                 => 'testcheckout.buckaroo.nl',
				'meta_key_website_key' => 'sisow_merchant_id',
				'meta_key_secret_key'  => 'sisow_merchant_key',
				'deprecated'           => true,
			]
		);

		return $gateways;
	}
);
