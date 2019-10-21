<?php
/**
 * Plugin Name: Pronamic Pay
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-ideal/
 * Description: The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.
 *
 * Version: 5.8.0
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
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

/**
 * Autoload.
 */
if ( ! defined( 'PRONAMIC_PAY_DEBUG' ) ) {
	define( 'PRONAMIC_PAY_DEBUG', false );
}

if ( PRONAMIC_PAY_DEBUG ) {
	foreach ( glob( __DIR__ . '/repositories/wp-pay/*/vendor/composer/autoload_files.php' ) as $file ) {
		$files = require $file;

		foreach ( $files as $identifier => $filepath ) {
			if ( ! empty( $GLOBALS['__composer_autoload_files'][ $identifier ] ) ) {
				continue;
			}

			require $filepath;

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

		foreach ( $object->autoload as $autoload_type => $classmap ) {
			if ( 'psr-4' !== $autoload_type ) {
				continue;
			}

			foreach ( $classmap as $prefix => $filepath ) {
				$loader->addPsr4( $prefix, dirname( $file ) . '/' . $filepath, true );
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
		'options'    => array(
			'about_page_file' => __DIR__ . '/admin/page-about.php',
		),
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
			'\Pronamic\WordPress\Pay\Extensions\NinjaForms\Extension::bootstrap',
		),
	)
);

add_filter(
	'pronamic_pay_plugin_integrations',
	function( $integrations ) {
		// Restrict Content Pro.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\RestrictContentPro\Extension(
			array(
				/**
				 * Requirements.
				 *
				 * @link https://github.com/dsawardekar/wp-requirements
				 * @link https://github.com/afragen/wp-dependency-installer
				 * @link https://github.com/wearerequired/wp-requirements-check
				 * @link https://github.com/ultraleettech/wp-requirements-checker
				 */
				'requirements' => array(
					array(
						'type'              => 'php',
						'requires_at_least' => '5.6.20',
					),
					array(
						'type'              => 'php-ext',
						'name'              => 'Internationalization',
						'slug'              => 'intl',
						'is_active'         => function() {
							return \extension_loaded( 'intl' );
						}
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
						}
					),
				),
			)
		);

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
				'id'               => 'abnamro-ideal-zelfbouw-v3',
				'name'             => 'ABN AMRO - iDEAL Zelfbouw (v3)',
				'provider'         => 'abnamro',
				'url'              => 'https://abnamro.ideal-payment.de/',
				'product_url'      => 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/',
				'dashboard_url'    => array(
					'test' => 'https://abnamro-test.ideal-payment.de/',
					'live' => 'https://abnamro.ideal-payment.de/',
				),
				'aquirer_url'      => 'https://abnamro.ideal-payment.de/ideal/iDEALv3',
				'aquirer_test_url' => 'https://abnamro-test.ideal-payment.de/ideal/iDEALv3',
				'certificates'     => array(),
			)
		);

		// Buckaroo.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration();

		// Deutsche Bank - iDEAL Expert (v3).
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'               => 'deutschebank-ideal-expert-v3',
				'name'             => 'Deutsche Bank - iDEAL Expert (v3)',
				'provider'         => 'deutschebank',
				'product_url'      => 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html',
				'dashboard_url'    => array(
					'test' => 'https://myideal.test.db.com/',
					'live' => 'https://myideal.db.com/',
				),
				'aquirer_url'      => 'https://myideal.db.com/ideal/iDealv3',
				'aquirer_test_url' => null,
				'certificates'     => array(),
			)
		);

		// EMS - eCommerce.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration();

		// Fibonacci ORANGE.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration(
			array(
				'id'          => 'fibonacciorange',
				'name'        => 'Fibonacci ORANGE',
				'provider'    => 'fibonacciorange',
				'product_url' => 'http://www.fibonacciorange.nl/',
			)
		);

		// ICEPAY.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration();

		// iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3).
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'               => 'ideal-simulator-ideal-advanced-v3',
				'name'             => 'iDEAL Simulator - iDEAL Professional / Advanced',
				'provider'         => 'ideal-simulator',
				'product_url'      => 'https://www.ideal-checkout.nl/support/ideal-simulator',
				'aquirer_url'      => 'https://www.ideal-checkout.nl/simulator/',
				'aquirer_test_url' => null,
				'certificates'     => array(),
			)
		);

		// ING - iDEAL Basic.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'               => 'ing-ideal-basic',
				'name'             => 'ING - iDEAL Basic',
				'provider'         => 'ing',
				'product_url'      => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'       => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-basic-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url'    => array(
					'test' => 'https://idealtest.secure-ing.com/',
					'live' => 'https://ideal.secure-ing.com/',
				),
				'aquirer_url'      => 'https://ideal.secure-ing.com/ideal/mpiPayInitIng.do',
				'aquirer_test_url' => 'https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do',
			)
		);

		// ING - iDEAL Advanced (v3).
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'               => 'ing-ideal-advanced-v3',
				'name'             => 'ING - iDEAL Advanced (v3)',
				'provider'         => 'ing',
				'product_url'      => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
				'manual_url'       => __( 'https://www.pronamic.eu/support/how-to-connect-ing-ideal-advanced-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url'    => array(
					'test' => 'https://idealtest.secure-ing.com/',
					'live' => 'https://ideal.secure-ing.com/',
				),
				'aquirer_url'      => 'https://ideal.secure-ing.com/ideal/iDEALv3',
				'aquirer_test_url' => 'https://idealtest.secure-ing.com/ideal/iDEALv3',
				'certificates'     => array(),
			)
		);

		// ING - Kassa Compleet.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet\Integration();

		// Mollie.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration();

		// Mollie - iDEAL.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\MollieIDeal\Integration();

		// Mollie - iDEAL Basic.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'               => 'mollie-ideal-basic',
				'name'             => 'Mollie - iDEAL Basic',
				'provider'         => 'mollie',
				'dashboard_url'    => 'http://www.mollie.nl/beheer/',
				'deprecated'       => true,
				'aquirer_url'      => 'https://secure.mollie.nl/xml/idealAcquirer/lite/',
				'aquirer_test_url' => 'https://secure.mollie.nl/xml/idealAcquirer/testmode/lite/',
			)
		);

		// MultiSafePay - Connect.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\MultiSafepay\Integration();

		// Nocks.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Nocks\Integration();

		// Ingenico - DirectLink.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\DirectLink\Integration();

		// Ingenico - OrderStandard.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration();

		// Rabobank - OmniKassa.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\OmniKassa\Integration();

		// Rabobank - OmniKassa 2.0.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration();

		// Pay.nl.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\PayNL\Integration();

		// Rabobank - iDEAL Professional (v3).
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
			array(
				'id'               => 'rabobank-ideal-professional-v3',
				'name'             => 'Rabobank - iDEAL Professional (v3)',
				'provider'         => 'rabobank',
				'product_url'      => 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/',
				'manual_url'       => __( 'https://www.pronamic.eu/support/how-to-connect-rabobank-ideal-professional-v3-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
				'dashboard_url'    => array(
					'test' => 'https://idealtest.rabobank.nl/',
					'live' => 'https://ideal.rabobank.nl/',
				),
				'aquirer_url'      => 'https://ideal.rabobank.nl/ideal/iDEALv3',
				'aquirer_test_url' => 'https://idealtest.rabobank.nl/ideal/iDEALv3',
				'certificates'     => array(),
			)
		);

		// Sisow.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\Sisow\Integration();

		// Sisow - iDEAL Basic.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
			array(
				'id'               => 'sisow-ideal-basic',
				'name'             => 'Sisow - iDEAL Basic',
				'provider'         => 'sisow',
				'url'              => 'https://www.sisow.nl/',
				'dashboard_url'    => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'deprecated'       => true,
				'aquirer_url'      => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx',
				'aquirer_test_url' => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx/test',
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
