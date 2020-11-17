<?php
/**
 * Plugin Name: Pronamic Pay
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-ideal/
 * Description: The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.
 *
 * Version: 6.4.1
 * Requires at least: 4.7
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
 * GitHub URI: https://github.com/pronamic/wp-pronamic-ideal
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
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
		'file'    => __FILE__,
		'options' => array(
			'about_page_file' => __DIR__ . '/admin/page-about.php',
		),
	)
);

add_filter(
	'pronamic_pay_plugin_integrations',
	function( $integrations ) {
		// Charitable.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\Charitable\Extension();

		// Easy Digital Downloads.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\EasyDigitalDownloads\Extension();

		// Event Espresso.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\EventEspressoLegacy\Extension();
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
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\MemberPress\Extension();

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

		// s2Member.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\S2Member\Extension();

		// WP e-Commerce.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\WPeCommerce\Extension();

		// Return integrations.
		return $integrations;
	}
);

add_filter(
	'pronamic_pay_gateways',
	function( $gateways ) {
		// ABN AMRO - iDEAL Zelfbouw (v3).
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'                => 'abnamro-ideal-zelfbouw-v3',
				'name'              => 'ABN AMRO - iDEAL Zelfbouw (v3)',
				'provider'          => 'abnamro',
				'url'               => 'https://abnamro.ideal-payment.de/',
				'product_url'       => 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/',
				'dashboard_url'     => array(
					'test' => 'https://abnamro-test.ideal-payment.de/',
					'live' => 'https://abnamro.ideal-payment.de/',
				),
				'acquirer_url'      => 'https://abnamro.ideal-payment.de/ideal/iDEALv3',
				'acquirer_test_url' => 'https://abnamro-test.ideal-payment.de/ideal/iDEALv3',
				'certificates'      => array(),
			)
		);

		// Buckaroo.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration();

		// Deutsche Bank - iDEAL Expert (v3).
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'                => 'deutschebank-ideal-expert-v3',
				'name'              => 'Deutsche Bank - iDEAL Expert (v3)',
				'provider'          => 'deutschebank',
				'product_url'       => 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html',
				'dashboard_url'     => array(
					'test' => 'https://myideal.test.db.com/',
					'live' => 'https://myideal.db.com/',
				),
				'acquirer_url'      => 'https://myideal.db.com/ideal/iDealv3',
				'acquirer_test_url' => null,
				'certificates'      => array(),
			)
		);

		// EMS - eCommerce.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration();

		// ICEPAY.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration();

		// iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3).
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'                => 'ideal-simulator-ideal-advanced-v3',
				'name'              => 'iDEAL Simulator - iDEAL Professional / Advanced',
				'provider'          => 'ideal-simulator',
				'product_url'       => 'https://www.ideal-checkout.nl/support/ideal-simulator',
				'acquirer_url'      => 'https://www.ideal-checkout.nl/simulator/',
				'acquirer_test_url' => null,
				'certificates'      => array(),
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

		// ING - iDEAL Advanced (v3).
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'                => 'ing-ideal-advanced-v3',
				'name'              => 'ING - iDEAL Advanced (v3)',
				'provider'          => 'ing',
				'product_url'       => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'        => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-advanced-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url'     => array(
					'test' => 'https://idealtest.secure-ing.com/',
					'live' => 'https://ideal.secure-ing.com/',
				),
				'acquirer_url'      => 'https://ideal.secure-ing.com/ideal/iDEALv3',
				'acquirer_test_url' => 'https://idealtest.secure-ing.com/ideal/iDEALv3',
				'certificates'      => array(),
			)
		);

		// ING - Kassa Compleet.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet\Integration();

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
				'id'                => 'mollie-ideal-basic',
				'name'              => 'Mollie - iDEAL Basic',
				'provider'          => 'mollie',
				'dashboard_url'     => 'http://www.mollie.nl/beheer/',
				'deprecated'        => true,
				'acquirer_url'      => 'https://secure.mollie.nl/xml/idealAcquirer/lite/',
				'acquirer_test_url' => 'https://secure.mollie.nl/xml/idealAcquirer/testmode/lite/',
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

		// Rabobank - iDEAL Professional (v3).
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'                => 'rabobank-ideal-professional-v3',
				'name'              => 'Rabobank - iDEAL Professional (v3)',
				'provider'          => 'rabobank',
				'product_url'       => 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/',
				'manual_url'        => __( 'https://www.pronamic.eu/support/how-to-connect-rabobank-ideal-professional-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url'     => array(
					'test' => 'https://idealtest.rabobank.nl/',
					'live' => 'https://ideal.rabobank.nl/',
				),
				'acquirer_url'      => 'https://ideal.rabobank.nl/ideal/iDEALv3',
				'acquirer_test_url' => 'https://idealtest.rabobank.nl/ideal/iDEALv3',
				'certificates'      => array(),
			)
		);

		// Sisow.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Sisow\Integration();

		// Sisow - iDEAL Basic.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'                => 'sisow-ideal-basic',
				'name'              => 'Sisow - iDEAL Basic',
				'provider'          => 'sisow',
				'url'               => 'https://www.sisow.nl/',
				'dashboard_url'     => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'deprecated'        => true,
				'acquirer_url'      => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx',
				'acquirer_test_url' => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx/test',
			)
		);

		// TargetPay.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\TargetPay\Integration();

		// Return gateways.
		return $gateways;
	}
);

/**
 * Backward compatibility.
 */
global $pronamic_ideal;

$pronamic_ideal = pronamic_pay_plugin();
