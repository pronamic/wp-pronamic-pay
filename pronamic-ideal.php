<?php
/**
 * Plugin Name: Pronamic Pay
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-pay/
 * Description: The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.
 *
 * Version: 8.1.0
 * Requires at least: 5.2
 * Requires PHP: 7.4
 *
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 *
 * Text Domain: pronamic_ideal
 * Domain Path: /languages/
 *
 * Provides: wp-pay/core
 *
 * License: GPL-3.0-or-later
 *
 * GitHub URI: https://github.com/pronamic/wp-pronamic-pay
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

/**
 * Autoload.
 */
if ( ! defined( 'PRONAMIC_PAY_DEBUG' ) ) {
	define( 'PRONAMIC_PAY_DEBUG', false );
}

$autoload_before = __DIR__ . '/src/autoload-before.php';

if ( is_readable( $autoload_before ) ) {
	require $autoload_before;
}

$loader = require __DIR__ . '/vendor/autoload.php';

$autoload_after = __DIR__ . '/src/autoload-after.php';

if ( is_readable( $autoload_after ) ) {
	require $autoload_after;
}

/**
 * Bootstrap.
 */
\Pronamic\WordPress\Pay\Plugin::instance(
	array(
		'file'             => __FILE__,
		'options'          => array(
			'about_page_file' => __DIR__ . '/admin/page-about.php',
		),
		'action_scheduler' => __DIR__ . '/packages/action-scheduler/action-scheduler.php',
	)
);

\Pronamic\WordPress\Pay\Updater::instance( function( $plugin ) {
	return \in_array(
		$plugin['Name'],
		array(
			'Pronamic Pay Adyen Add-On',
			'Pronamic Pay Contact Form 7 Add-On',
			'Pronamic Pay DigiWallet Add-On',
			'Pronamic Pay Fundraising Add-On',
			'Pronamic Pay Restrict Content Pro Add-On',
			'Pronamic Pay PayPal Add-On',
			'Pronamic Pay Payvision Add-On',
		),
		true
	);
} );

add_filter(
	'pronamic_pay_removed_extension_notifications',
	function( $notifications ) {
		$notifications[] = new \Pronamic\WordPress\Pay\Admin\AdminNotification(
			'removed-extension-active-event-espresso-legacy',
			\__( 'Event Espresso 3', 'pronamic_ideal' ),
			\defined( '\EVENT_ESPRESSO_VERSION' ) && \version_compare( \EVENT_ESPRESSO_VERSION, '4.0.0', '<' ),
			'8'
		);

		$notifications[] = new \Pronamic\WordPress\Pay\Admin\AdminNotification(
			'removed-extension-active-s2member',
			\__( 's2Member', 'pronamic_ideal' ),
			\defined( '\WS_PLUGIN__S2MEMBER_VERSION' ),
			'8'
		);

		$notifications[] = new \Pronamic\WordPress\Pay\Admin\AdminNotification(
			'removed-extension-active-wp-e-commerce',
			\__( 'WP eCommerce', 'pronamic_ideal' ),
			\class_exists( '\WP_eCommerce' ),
			'8'
		);

		return $notifications;
	}
);

add_filter(
	'pronamic_pay_plugin_integrations',
	function( $integrations ) {
		// Charitable.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\Charitable\Extension();

		// Easy Digital Downloads.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\EasyDigitalDownloads\Extension();

		// Event Espresso.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\EventEspresso\Extension();

		// Give.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\Give\Extension();

		// WooCommerce.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\WooCommerce\Extension();

		// Gravity Forms.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\GravityForms\Extension();

		// FormidableForms.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\FormidableForms\Extension();

		// MemberPress.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\MemberPress\Extension(
			array(
				'slug'                   => 'memberpress',
				'db_version_option_name' => 'pronamic_pay_memberpress_db_version',
				'name'                   => 'MemberPress',
			)
		);

		// NinjaForms.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\NinjaForms\Extension();

		// Restrict Content Pro.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\RestrictContentPro\Extension(
			array(
				'slug'                   => 'restrict-content-pro',
				'db_version_option_name' => 'pronamic_pay_restrictcontentpro_db_version',
				'name'                   => 'Restrict Content Pro',
			)
		);

		// Return integrations.
		return $integrations;
	}
);

add_filter(
	'get_post_metadata',
	function( $value, $post_id, $meta_key, $single ) {
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
			case 'payvision':
				return ( 'test' === $mode ) ? 'payvision-staging' : 'payvision';
			case 'rabobank-ideal-professional-v3':
				return ( 'test' === $mode ) ? 'rabobank-ideal-professional-test' : 'rabobank-ideal-professional';
			case 'rabobank-omnikassa-2':
				return ( 'test' === $mode ) ? 'rabobank-omnikassa-2-sandbox' : 'rabobank-omnikassa-2';
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
	function( $gateways ) {
		// ABN AMRO - iDEAL Zelfbouw.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'abnamro-ideal-zelfbouw',
				'name'          => 'ABN AMRO - iDEAL Zelfbouw',
				'mode'          => 'live',
				'provider'      => 'abnamro',
				'url'           => 'https://ecommerce.abnamro.nl/',
				'product_url'   => 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/',
				'dashboard_url' => 'https://ecommerce.abnamro.nl/',
				'acquirer_url'  => 'https://ecommerce.abnamro.nl/ideal/iDEALv3',
				'certificates'  => array(
					__DIR__ . '/certificates/abnamro-2017-01-26-2022-01-25.cer',
					__DIR__ . '/certificates/abnamro-2021-10-01-2026-09-30.cer',
				),
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'abnamro-ideal-zelfbouw-test',
				'name'          => 'ABN AMRO - iDEAL Zelfbouw - Test',
				'mode'          => 'test',
				'provider'      => 'abnamro',
				'url'           => 'https://ecommerce-test.abnamro.nl/',
				'product_url'   => 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/',
				'dashboard_url' => 'https://ecommerce-test.abnamro.nl/',
				'acquirer_url'  => 'https://ecommerce-test.abnamro.nl/ideal/iDEALv3',
				'certificates'  => array(
					__DIR__ . '/certificates/abnamro-2017-01-26-2022-01-25.cer',
					__DIR__ . '/certificates/abnamro-2021-10-01-2026-09-30.cer',
				),
			)
		);

		// Buckaroo.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration(
			array(
				'id'   => 'buckaroo',
				'name' => 'Buckaroo',
				'mode' => 'live',
				'host' => 'checkout.buckaroo.nl',
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration(
			array(
				'id'   => 'buckaroo-test',
				'name' => 'Buckaroo - Test',
				'mode' => 'test',
				'host' => 'testcheckout.buckaroo.nl',
			)
		);

		// Deutsche Bank - iDEAL Expert.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'deutschebank-ideal-expert',
				'name'          => 'Deutsche Bank - iDEAL Expert',
				'mode'          => 'live',
				'provider'      => 'deutschebank',
				'product_url'   => 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html',
				'dashboard_url' => 'https://myideal.db.com/',
				'acquirer_url'  => 'https://myideal.db.com/ideal/iDealv3',
				'certificates'  => array(),
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'deutschebank-ideal-expert-test',
				'name'          => 'Deutsche Bank - iDEAL Expert - Test',
				'mode'          => 'test',
				'provider'      => 'deutschebank',
				'product_url'   => 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html',
				'dashboard_url' => 'https://myideal.test.db.com/',
				'acquirer_url'  => 'https://myideal.test.db.com/ideal/iDealv3',
				'certificates'  => array(),
			)
		);

		// EMS - eCommerce.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration(
			array(
				'id'            => 'ems-ecommerce',
				'name'          => 'EMS e-Commerce',
				'mode'          => 'live',
				'action_url'    => 'https://www.ipg-online.com/connect/gateway/processing',
				'dashboard_url' => 'https://www.ipg-online.com/vt/login',
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration(
			array(
				'id'            => 'ems-ecommerce-test',
				'name'          => 'EMS e-Commerce - Test',
				'mode'          => 'test',
				'action_url'    => 'https://test.ipg-online.com/connect/gateway/processing',
				'dashboard_url' => 'https://test.ipg-online.com/vt/login',
			)
		);

		// ICEPAY.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration();

		// iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'           => 'ideal-simulator-ideal-advanced-v3',
				'name'         => 'iDEAL Simulator - iDEAL Professional / Advanced',
				'mode'         => 'test',
				'provider'     => 'ideal-simulator',
				'product_url'  => 'https://www.ideal-checkout.nl/support/ideal-simulator',
				'acquirer_url' => 'https://www.ideal-checkout.nl/simulator/',
				'certificates' => array(
					__DIR__ . '/certificates/ideal-checkout-2019-02-27-2024-02-26.cer',
				),
			)
		);

		// ING - iDEAL Basic.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'            => 'ing-ideal-basic',
				'name'          => 'ING - iDEAL Basic',
				'mode'          => 'live',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-basic-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://ideal.secure-ing.com/',
				'acquirer_url'  => 'https://ideal.secure-ing.com/ideal/mpiPayInitIng.do',
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'            => 'ing-ideal-basic-test',
				'name'          => 'ING - iDEAL Basic - Test',
				'mode'          => 'test',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-basic-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://idealtest.secure-ing.com/',
				'acquirer_url'  => 'https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do',
			)
		);

		// ING - iDEAL Advanced.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'ing-ideal-advanced',
				'name'          => 'ING - iDEAL Advanced - Old platform',
				'mode'          => 'live',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-advanced-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://ideal.secure-ing.com/',
				'acquirer_url'  => 'https://ideal.secure-ing.com/ideal/iDEALv3',
				'certificates'  => array(
					__DIR__ . '/certificates/ing-2017-01-26-2022-01-25.cer',
					__DIR__ . '/certificates/ing-2021-10-01-2016-09-30.cer',
				),
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'ing-ideal-advanced-test',
				'name'          => 'ING - iDEAL Advanced - Old platform - Test',
				'mode'          => 'test',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-advanced-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://idealtest.secure-ing.com/',
				'acquirer_url'  => 'https://idealtest.secure-ing.com/ideal/iDEALv3',
				'certificates'  => array(
					__DIR__ . '/certificates/ing-2017-01-26-2022-01-25.cer',
					__DIR__ . '/certificates/ing-2021-10-01-2016-09-30.cer',
				),
			)
		);

		// ING - iDEAL Advanced - New platform - Production.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'ing-ideal-advanced-2022-production',
				'name'          => 'ING - iDEAL Advanced - New platform - Production',
				'mode'          => 'live',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-advanced-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://ideal-portal.ing.nl/',
				'acquirer_url'  => 'https://ideal-acquiring.ing.nl/ideal/iDEALv3',
				'certificates'  => array(
					__DIR__ . '/certificates/ing-new-2020-03-04-2025-01-17.cer',
				),
			)
		);

		// ING - iDEAL Advanced - New platform - Sandbox.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'ing-ideal-advanced-2022-sandbox',
				'name'          => 'ING - iDEAL Advanced - New platform - Sandbox',
				'mode'          => 'test',
				'provider'      => 'ing',
				'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'    => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-advanced-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://sandbox.ideal-portal.ing.nl/',
				'acquirer_url'  => 'https://sandbox.ideal-acquiring.ing.nl/ideal/iDEALv3',
				'certificates'  => array(
					__DIR__ . '/certificates/ing-new-sandbox-2020-03-04-2025-01-17.cer',
				),
			)
		);

		// Mollie.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration(
			array(
				'register_url'           => 'https://www.mollie.com/nl/signup/665327',
				'manual_url'             => \__( 'https://www.pronamic.eu/support/how-to-connect-mollie-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'version_option_name'    => 'pronamic_pay_mollie_version',
				'db_version_option_name' => 'pronamic_pay_mollie_db_version',
			)
		);

		// MultiSafePay - Connect.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\MultiSafepay\Integration(
			array(
				'id'      => 'multisafepay-connect',
				'name'    => 'MultiSafepay - Connect',
				'mode'    => 'live',
				'api_url' => 'https://api.multisafepay.com/ewx/',
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\MultiSafepay\Integration(
			array(
				'id'      => 'multisafepay-connect-test',
				'name'    => 'MultiSafepay - Connect - Test',
				'mode'    => 'test',
				'api_url' => 'https://testapi.multisafepay.com/ewx/',
			)
		);

		// Ingenico.
		$is_utf8 = strcasecmp( get_bloginfo( 'charset' ), 'UTF-8' ) === 0;

		// Ingenico - DirectLink.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\DirectLink\Integration(
			array(
				'id'      => 'ingenico-directlink',
				'name'    => 'Ingenico/Ogone - DirectLink',
				'mode'    => 'live',
				'api_url' => $is_utf8 ? 'https://secure.ogone.com/ncol/prod/orderdirect_utf8.asp' : 'https://secure.ogone.com/ncol/prod/orderdirect.asp',
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\DirectLink\Integration(
			array(
				'id'      => 'ingenico-directlink-test',
				'name'    => 'Ingenico/Ogone - DirectLink - Test',
				'mode'    => 'test',
				'api_url' => $is_utf8 ? 'https://secure.ogone.com/ncol/test/orderdirect_utf8.asp' : 'https://secure.ogone.com/ncol/test/orderdirect.asp',
			)
		);

		// Ingenico - OrderStandard.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration(
			array(
				'id'               => 'ingenico-orderstandard',
				'name'             => 'Ingenico/Ogone - e-Commerce',
				'mode'             => 'live',
				'action_url'       => $is_utf8 ? 'https://secure.ogone.com/ncol/prod/orderstandard_utf8.asp' : 'https://secure.ogone.com/ncol/prod/orderstandard.asp',
				'direct_query_url' => 'https://secure.ogone.com/ncol/prod/querydirect.asp',
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration(
			array(
				'id'               => 'ingenico-orderstandard-test',
				'name'             => 'Ingenico/Ogone - e-Commerce - Test',
				'mode'             => 'test',
				'action_url'       => $is_utf8 ? 'https://secure.ogone.com/ncol/test/orderstandard_utf8.asp' : 'https://secure.ogone.com/ncol/test/orderstandard.asp',
				'direct_query_url' => 'https://secure.ogone.com/ncol/test/querydirect.asp',
			)
		);

		// Rabobank - OmniKassa 2.0.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration(
			array(
				'id'      => 'rabobank-omnikassa-2',
				'name'    => 'Rabobank - OmniKassa 2.0',
				'mode'    => 'live',
				'api_url' => 'https://betalen.rabobank.nl/omnikassa-api/',
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration(
			array(
				'id'      => 'rabobank-omnikassa-2-sandbox',
				'name'    => 'Rabobank - OmniKassa 2.0 - Sandbox',
				'mode'    => 'test',
				'api_url' => 'https://betalen.rabobank.nl/omnikassa-api-sandbox/',
			)
		);

		// Pay.nl.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\PayNL\Integration();

		// Rabobank - iDEAL Professional.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'rabobank-ideal-professional',
				'name'          => 'Rabobank - iDEAL Professional',
				'mode'          => 'live',
				'provider'      => 'rabobank',
				'product_url'   => 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/',
				'manual_url'    => __( 'https://www.pronamic.eu/support/how-to-connect-rabobank-ideal-professional-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://ideal.rabobank.nl/',
				'acquirer_url'  => 'https://ideal.rabobank.nl/ideal/iDEALv3',
				'certificates'  => array(
					__DIR__ . '/certificates/rabobank-2017-01-26-2022-01-25.cer',
					__DIR__ . '/certificates/rabobank-2021-10-01-2026-09-30.cer',
				),
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'            => 'rabobank-ideal-professional-test',
				'name'          => 'Rabobank - iDEAL Professional - Test',
				'mode'          => 'test',
				'provider'      => 'rabobank',
				'product_url'   => 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/',
				'manual_url'    => __( 'https://www.pronamic.eu/support/how-to-connect-rabobank-ideal-professional-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://idealtest.rabobank.nl/',
				'acquirer_url'  => 'https://idealtest.rabobank.nl/ideal/iDEALv3',
				'certificates'  => array(
					__DIR__ . '/certificates/rabobank-2017-01-26-2022-01-25.cer',
					__DIR__ . '/certificates/rabobank-2021-10-01-2026-09-30.cer',
				),
			)
		);

		// Sisow.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Sisow\Integration();

		// Sisow - iDEAL Basic.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'            => 'sisow-ideal-basic',
				'name'          => 'Sisow - iDEAL Basic',
				'mode'          => 'live',
				'provider'      => 'sisow',
				'url'           => 'https://www.sisow.nl/',
				'dashboard_url' => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'deprecated'    => true,
				'acquirer_url'  => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx',
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'            => 'sisow-ideal-basic-test',
				'name'          => 'Sisow - iDEAL Basic - Test',
				'mode'          => 'test',
				'provider'      => 'sisow',
				'url'           => 'https://www.sisow.nl/',
				'dashboard_url' => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'deprecated'    => true,
				'acquirer_url'  => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx/test',
			)
		);

		// TargetPay.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\DigiWallet\Integration(
			array(
				'id'            => 'targetpay-ideal',
				'name'          => 'TargetPay',
				'product_url'   => \__( 'https://www.targetpay.com/info/ideal?setlang=en', 'pronamic_ideal' ),
				'dashboard_url' => 'https://www.targetpay.com/login',
				'provider'      => 'targetpay',
				'manual_url'    => \__( 'https://www.pronamic.eu/support/how-to-connect-targetpay-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'deprecated'    => true,
				'meta_key_rtlo' => 'targetpay_layoutcode',
			)
		);

		// Return gateways.
		return $gateways;
	}
);

/**
 * Backward compatibility.
 */
global $pronamic_ideal;

$pronamic_ideal = pronamic_pay_plugin();
