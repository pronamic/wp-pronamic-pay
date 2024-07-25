<?php
/**
 * Plugin Name: Pronamic Pay
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-pay/
 * Description: The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.
 *
 * Version: 9.11.0
 * Requires at least: 5.9
 * Requires PHP: 8.1
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
 * @copyright 2005-2024 Pronamic
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
			case 'abnamro-ideal-zelfbouw-v3':
				return ( 'test' === $mode ) ? 'abnamro-ideal-zelfbouw-test' : 'abnamro-ideal-zelfbouw';
			case 'adyen':
				return ( 'test' === $mode ) ? 'adyen-test' : 'adyen';
			case 'buckaroo':
				return ( 'test' === $mode ) ? 'buckaroo-test' : 'buckaroo';
			case 'deutschebank-ideal-expert-v3':
				return ( 'test' === $mode ) ? 'deutschebank-ideal-expert-test' : 'deutschebank-ideal-expert';
			case 'ems-ecommerce':
				return ( 'test' === $mode ) ? 'ems-ecommerce-test' : 'ems-ecommerce';
			case 'ing-ideal-advanced-v3':
				return ( 'test' === $mode ) ? 'ing-ideal-advanced-test' : 'ing-ideal-advanced';
			case 'ing-ideal-advanced-2022':
				return ( 'test' === $mode ) ? 'ing-ideal-advanced-2022-sandbox' : 'ing-ideal-advanced-2022-production';
			case 'ing-ideal-basic':
				return ( 'test' === $mode ) ? 'ing-ideal-basic-test' : 'ing-ideal-basic';
			case 'multisafepay-connect':
				return ( 'test' === $mode ) ? 'multisafepay-connect-test' : 'multisafepay-connect';
			case 'ogone-directlink':
				return ( 'test' === $mode ) ? 'ingenico-directlink-test' : 'ingenico-directlink';
			case 'ogone-orderstandard':
				return ( 'test' === $mode ) ? 'ingenico-orderstandard-test' : 'ingenico-orderstandard';
			case 'paypal':
				return ( 'test' === $mode ) ? 'paypal-sandbox' : 'paypal';
			case 'rabobank-ideal-professional-v3':
				return ( 'test' === $mode ) ? 'rabobank-ideal-professional-test' : 'rabobank-ideal-professional';
			case 'rabobank-omnikassa-2':
				return ( 'test' === $mode ) ? 'rabobank-omnikassa-2-sandbox' : 'rabobank-omnikassa-2';
			case 'sisow-ideal':
				$sisow_test_mode = get_post_meta( $post_id, '_pronamic_gateway_sisow_test_mode', true );

				return ( 'test' === $mode || '' !== $sisow_test_mode ) ? 'sisow-buckaroo-test' : 'sisow-buckaroo';
			case 'sisow-ideal-basic':
				return ( 'test' === $mode ) ? 'sisow-ideal-basic-test' : 'sisow-ideal-basic';
		}

		return $value;
	},
	10,
	4
);

add_filter(
	'pronamic_pay_gateways',
	function ( $gateways ) {
		// ABN AMRO - iDEAL Zelfbouw.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'abnamro-ideal-zelfbouw',
				'name'          => 'ABN AMRO - iDEAL Zelfbouw',
				'mode'          => 'live',
				'provider'      => 'abnamro',
				'url'           => 'https://ecommerce.abnamro.nl/',
				'product_url'   => 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/',
				'dashboard_url' => 'https://ecommerce.abnamro.nl/',
				'acquirer_url'  => 'https://ecommerce.abnamro.nl/ideal/iDEALv3',
				'certificates'  => [
					__DIR__ . '/certificates/abnamro-2021-10-01-2026-09-30.cer',
				],
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'abnamro-ideal-zelfbouw-test',
				'name'          => 'ABN AMRO - iDEAL Zelfbouw - Test',
				'mode'          => 'test',
				'provider'      => 'abnamro',
				'url'           => 'https://ecommerce-test.abnamro.nl/',
				'product_url'   => 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/',
				'dashboard_url' => 'https://ecommerce-test.abnamro.nl/',
				'acquirer_url'  => 'https://ecommerce-test.abnamro.nl/ideal/iDEALv3',
				'certificates'  => [
					__DIR__ . '/certificates/abnamro-2021-10-01-2026-09-30.cer',
				],
			]
		);

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

		// Deutsche Bank - iDEAL Expert.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'deutschebank-ideal-expert',
				'name'          => 'Deutsche Bank - iDEAL Expert',
				'mode'          => 'live',
				'provider'      => 'deutschebank',
				'product_url'   => 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html',
				'dashboard_url' => 'https://myideal.db.com/',
				'acquirer_url'  => 'https://myideal.db.com/ideal/iDealv3',
				'certificates'  => [],
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'deutschebank-ideal-expert-test',
				'name'          => 'Deutsche Bank - iDEAL Expert - Test',
				'mode'          => 'test',
				'provider'      => 'deutschebank',
				'product_url'   => 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html',
				'dashboard_url' => 'https://myideal.test.db.com/',
				'acquirer_url'  => 'https://myideal.test.db.com/ideal/iDealv3',
				'certificates'  => [],
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

		// ICEPAY.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration();

		// iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'           => 'ideal-simulator-ideal-advanced-v3',
				'name'         => 'iDEAL Simulator - iDEAL Professional / Advanced',
				'mode'         => 'test',
				'provider'     => 'ideal-simulator',
				'product_url'  => 'https://www.ideal-checkout.nl/support/ideal-simulator',
				'acquirer_url' => 'https://www.ideal-checkout.nl/simulator/',
				'certificates' => [
					__DIR__ . '/certificates/ideal-checkout-2019-02-27-2024-02-26.cer',
				],
			]
		);

		// ING - iDEAL Basic.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			[
				'id'            => 'ing-ideal-basic',
				'name'          => 'ING - iDEAL Basic',
				'mode'          => 'live',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamicpay.com/en/manuals/how-to-connect-ing-ideal-basic-to-wordpress-with-pronamic-pay/', 'pronamic-ideal' ),
				'dashboard_url' => 'https://ideal.secure-ing.com/',
				'acquirer_url'  => 'https://ideal.secure-ing.com/ideal/mpiPayInitIng.do',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			[
				'id'            => 'ing-ideal-basic-test',
				'name'          => 'ING - iDEAL Basic - Test',
				'mode'          => 'test',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamicpay.com/en/manuals/how-to-connect-ing-ideal-basic-to-wordpress-with-pronamic-pay/', 'pronamic-ideal' ),
				'dashboard_url' => 'https://idealtest.secure-ing.com/',
				'acquirer_url'  => 'https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do',
			]
		);

		// ING - iDEAL Advanced.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'ing-ideal-advanced',
				'name'          => 'ING - iDEAL Advanced - Old platform',
				'mode'          => 'live',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamicpay.com/en/manuals/how-to-connect-ing-ideal-advanced-v3-to-wordpress-with-pronamic-pay/', 'pronamic-ideal' ),
				'dashboard_url' => 'https://ideal.secure-ing.com/',
				'acquirer_url'  => 'https://ideal.secure-ing.com/ideal/iDEALv3',
				'certificates'  => [],
				'deprecated'    => true,
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'ing-ideal-advanced-test',
				'name'          => 'ING - iDEAL Advanced - Old platform - Test',
				'mode'          => 'test',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamicpay.com/en/manuals/how-to-connect-ing-ideal-advanced-v3-to-wordpress-with-pronamic-pay/', 'pronamic-ideal' ),
				'dashboard_url' => 'https://idealtest.secure-ing.com/',
				'acquirer_url'  => 'https://idealtest.secure-ing.com/ideal/iDEALv3',
				'certificates'  => [],
				'deprecated'    => true,
			]
		);

		// ING - iDEAL Advanced - New platform - Production.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'ing-ideal-advanced-2022-production',
				'name'          => 'ING - iDEAL Advanced - New platform - Production',
				'mode'          => 'live',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamicpay.com/en/manuals/how-to-connect-ing-ideal-advanced-v3-to-wordpress-with-pronamic-pay/', 'pronamic-ideal' ),
				'dashboard_url' => 'https://ideal-portal.ing.nl/',
				'acquirer_url'  => 'https://ideal-acquiring.ing.nl/ideal/iDEALv3',
				'certificates'  => [
					__DIR__ . '/certificates/ing-new-2020-03-04-2025-01-17.cer',
				],
			]
		);

		// ING - iDEAL Advanced - New platform - Sandbox.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'ing-ideal-advanced-2022-sandbox',
				'name'          => 'ING - iDEAL Advanced - New platform - Sandbox',
				'mode'          => 'test',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamicpay.com/en/manuals/how-to-connect-ing-ideal-advanced-v3-to-wordpress-with-pronamic-pay/', 'pronamic-ideal' ),
				'dashboard_url' => 'https://sandbox.ideal-portal.ing.nl/',
				'acquirer_url'  => 'https://sandbox.ideal-acquiring.ing.nl/ideal/iDEALv3',
				'certificates'  => [
					__DIR__ . '/certificates/ing-new-sandbox-2020-03-04-2025-01-17.cer',
				],
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
				'manual_url' => \__( 'https://www.pronamicpay.com/en/manuals/how-to-connect-mollie-to-wordpress-with-pronamic-pay/', 'pronamic-ideal' ),
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

		// Ingenico.
		$is_utf8 = strcasecmp( get_bloginfo( 'charset' ), 'UTF-8' ) === 0;

		// Ingenico - OrderStandard.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration(
			[
				'id'               => 'ingenico-orderstandard',
				'name'             => 'Ingenico/Ogone - e-Commerce',
				'mode'             => 'live',
				'action_url'       => $is_utf8 ? 'https://secure.ogone.com/ncol/prod/orderstandard_utf8.asp' : 'https://secure.ogone.com/ncol/prod/orderstandard.asp',
				'direct_query_url' => 'https://secure.ogone.com/ncol/prod/querydirect.asp',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration(
			[
				'id'               => 'ingenico-orderstandard-test',
				'name'             => 'Ingenico/Ogone - e-Commerce - Test',
				'mode'             => 'test',
				'action_url'       => $is_utf8 ? 'https://secure.ogone.com/ncol/test/orderstandard_utf8.asp' : 'https://secure.ogone.com/ncol/test/orderstandard.asp',
				'direct_query_url' => 'https://secure.ogone.com/ncol/test/querydirect.asp',
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

		// Rabobank - iDEAL Professional.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'rabobank-ideal-professional',
				'name'          => 'Rabobank - iDEAL Professional',
				'mode'          => 'live',
				'provider'      => 'rabobank',
				'product_url'   => 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/',
				'manual_url'    => __( 'https://www.pronamicpay.com/en/manuals/how-to-connect-rabobank-ideal-professional-v3-to-wordpress-with-pronamic-pay/', 'pronamic-ideal' ),
				'dashboard_url' => 'https://ideal.rabobank.nl/',
				'acquirer_url'  => 'https://ideal.rabobank.nl/ideal/iDEALv3',
				'certificates'  => [
					__DIR__ . '/certificates/rabobank-2021-10-01-2026-09-30.cer',
				],
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			[
				'id'            => 'rabobank-ideal-professional-test',
				'name'          => 'Rabobank - iDEAL Professional - Test',
				'mode'          => 'test',
				'provider'      => 'rabobank',
				'product_url'   => 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/',
				'manual_url'    => __( 'https://www.pronamicpay.com/en/manuals/how-to-connect-rabobank-ideal-professional-v3-to-wordpress-with-pronamic-pay/', 'pronamic-ideal' ),
				'dashboard_url' => 'https://idealtest.rabobank.nl/',
				'acquirer_url'  => 'https://idealtest.rabobank.nl/ideal/iDEALv3',
				'certificates'  => [
					__DIR__ . '/certificates/rabobank-2021-10-01-2026-09-30.cer',
				],
			]
		);

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

		// Sisow - iDEAL Basic.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			[
				'id'            => 'sisow-ideal-basic',
				'name'          => 'Sisow - iDEAL Basic',
				'mode'          => 'live',
				'provider'      => 'sisow',
				'url'           => 'https://www.sisow.nl/',
				'dashboard_url' => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'deprecated'    => true,
				'acquirer_url'  => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			[
				'id'            => 'sisow-ideal-basic-test',
				'name'          => 'Sisow - iDEAL Basic - Test',
				'mode'          => 'test',
				'provider'      => 'sisow',
				'url'           => 'https://www.sisow.nl/',
				'dashboard_url' => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'deprecated'    => true,
				'acquirer_url'  => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx/test',
			]
		);

		// Return gateways.
		return $gateways;
	}
);
