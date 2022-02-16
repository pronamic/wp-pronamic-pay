<?php
/**
 * Plugin Name: Pronamic Pay
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-pay/
 * Description: The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.
 *
 * Version: 8.1.0
 * Requires at least: 5.2
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
				'manual_url'             => null,
				/**
				 * Affiliate link.
				 *
				 * @link https://restrictcontentpro.com/affiliates/
				 * @link https://restrictcontentpro.com/affiliate-agreement/
				 * @todo Request a Restrict Content Pro affiliate link.
				 */
				'affiliate_url'          => null,
				/**
				 * Requirements.
				 *
				 * @link https://github.com/dsawardekar/wp-requirements
				 * @link https://github.com/afragen/wp-dependency-installer
				 * @link https://github.com/wearerequired/wp-requirements-check
				 * @link https://github.com/ultraleettech/wp-requirements-checker
				 * @link https://waclawjacek.com/check-wordpress-plugin-dependencies/
				 * @link https://github.com/xwp/wp-plugin-dependencies
				 * @link https://wordpress.org/plugins/plugin-dependencies/
				 * @link https://github.com/joshbetz/wp-plugin-dependencies
				 * @link https://github.com/jrfnl/wp-known-plugin-dependencies
				 * @link https://github.com/xwp/wp-plugin-dependencies/issues/34
				 * @link https://github.com/WordPress/WordPress/blob/master/wp-includes/class.wp-dependencies.php
				 * @link https://github.com/WordPress/WordPress/blob/master/wp-includes/class-wp-dependency.php
				 * @link https://github.com/Yoast/yoast-acf-analysis/blob/2.3.0/inc/ac-yoast-seo-acf-content-analysis.php#L30-L32
				 * @link https://github.com/Yoast/yoast-acf-analysis/blob/2.3.0/inc/requirements.php
				 */
				'requirements'           => array(
					array(
						'type'              => 'php',
						'requires_at_least' => '5.6.20',
					),
					array(
						'type'      => 'php-ext',
						'name'      => 'Internationalization',
						'slug'      => 'intl',
						'is_active' => function() {
							return \extension_loaded( 'intl' );
						},
					),
					array(
						'type'              => 'wp',
						'requires_at_least' => '4.7',
					),
					array(
						'type'              => 'wp-plugin',
						'name'              => 'Restrict Content Pro',
						'slug'              => 'restrict-content-pro',
						'uri'               => 'https://restrictcontentpro.com/',
						'requires_at_least' => '3.0.0',
						'tested_up_to'      => '3.1.2',
						'is_active'         => function() {
							return \defined( 'RCP_PLUGIN_VERSION' );
						},
						'get_version'       => function() {
							if ( \defined( 'RCP_PLUGIN_VERSION' ) ) {
								return RCP_PLUGIN_VERSION;
							}
						},
					),
				),
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
			case 'deutschebank-ideal-expert-v3':
				return ( 'test' === $mode ) ? 'deutschebank-ideal-expert-test' : 'deutschebank-ideal-expert';
			case 'ing-ideal-advanced-v3':
				return ( 'test' === $mode ) ? 'ing-ideal-advanced-test' : 'ing-ideal-advanced';
			case 'ing-ideal-advanced-2022':
				return ( 'test' === $mode ) ? 'ing-ideal-advanced-2022-sandbox' : 'ing-ideal-advanced-2022-production';
			case 'rabobank-ideal-professional-v3':
				return ( 'test' === $mode ) ? 'rabobank-ideal-professional-test' : 'rabobank-ideal-professional';
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
				'url'           => 'https://abnamro.ideal-payment.de/',
				'product_url'   => 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/',
				'dashboard_url' => 'https://abnamro.ideal-payment.de/',
				'acquirer_url'  => 'https://abnamro.ideal-payment.de/ideal/iDEALv3',
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
				'url'           => 'https://abnamro.ideal-payment.de/',
				'product_url'   => 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/',
				'dashboard_url' => 'https://abnamro-test.ideal-payment.de/',
				'acquirer_url'  => 'https://abnamro-test.ideal-payment.de/ideal/iDEALv3',
				'certificates'  => array(
					__DIR__ . '/certificates/abnamro-2017-01-26-2022-01-25.cer',
					__DIR__ . '/certificates/abnamro-2021-10-01-2026-09-30.cer',
				),
			)
		);

		// Buckaroo.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration();

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
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration();

		// ICEPAY.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration();

		// iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3).
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
				'id'                => 'ing-ideal-basic',
				'name'              => 'ING - iDEAL Basic',
				'provider'          => 'ing',
				'product_url'       => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'        => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-basic-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url'     => array(
					'test' => 'https://idealtest.secure-ing.com/',
					'live' => 'https://ideal.secure-ing.com/',
				),
				'acquirer_url'      => 'https://ideal.secure-ing.com/ideal/mpiPayInitIng.do',
				'acquirer_test_url' => 'https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do',
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

		// Mollie - iDEAL Basic.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'            => 'mollie-ideal-basic',
				'name'          => 'Mollie - iDEAL Basic',
				'mode'          => 'live',
				'provider'      => 'mollie',
				'dashboard_url' => 'https://www.mollie.com/dashboard/',
				'deprecated'    => true,
				'acquirer_url'  => 'https://secure.mollie.nl/xml/idealAcquirer/lite/',
			)
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'            => 'mollie-ideal-basic',
				'name'          => 'Mollie - iDEAL Basic - Test',
				'mode'          => 'test',
				'provider'      => 'mollie',
				'dashboard_url' => 'https://www.mollie.com/dashboard/',
				'deprecated'    => true,
				'acquirer_url'  => 'https://secure.mollie.nl/xml/idealAcquirer/testmode/lite/',
			)
		);

		// MultiSafePay - Connect.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\MultiSafepay\Integration();

		// Ingenico - DirectLink.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\DirectLink\Integration();

		// Ingenico - OrderStandard.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration();

		// Rabobank - OmniKassa 2.0.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration();

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
