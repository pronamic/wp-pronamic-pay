<?php
/**
 * Plugin Name: Pronamic Pay
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-ideal/
 * Description: The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.
 *
 * Version: 5.6.2
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
		'version'    => '5.6.2',
		'gateways'   => array(
			// ABN AMRO - iDEAL Zelfbouw (v3).
			new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
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
			),
			// Buckaroo.
			new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration(),
			// Deutsche Bank - iDEAL via Ogone.
			new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandardEasy\Integration(
				array(
					'id'          => 'deutschebank-ideal-via-ogone',
					'name'        => 'Deutsche Bank - iDEAL via Ogone',
					'provider'    => 'deutschebank',
					'product_url' => 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html',
				)
			),
			// Deutsche Bank - iDEAL Expert (v3).
			new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
				array(
					'id'            => 'deutschebank-ideal-expert-v3',
					'name'          => 'Deutsche Bank - iDEAL Expert (v3)',
					'provider'      => 'deutschebank',
					'product_url'   => 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html',
					'dashboard_url' => array(
						'test' => 'https://myideal.test.db.com/',
						'live' => 'https://myideal.db.com/',
					),
					'aquirer_url'      => 'https://myideal.db.com/ideal/iDealv3',
					'aquirer_test_url' => null,
					'certificates'     => array(),
				)
			),
			// EMS - eCommerce.
			new \Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration(),
			// Fibonacci ORANGE.
			new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration(
				array(
					'id'          => 'fibonacciorange',
					'name'        => 'Fibonacci ORANGE',
					'provider'    => 'fibonacciorange',
					'product_url' => 'http://www.fibonacciorange.nl/',
				)
			),
			// ICEPAY.
			new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration(),
			// iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3).
			new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
				array(
					'id'               => 'ideal-simulator-ideal-advanced-v3',
					'name'             => 'iDEAL Simulator - iDEAL Professional / Advanced',
					'provider'         => 'ideal-simulator',
					'product_url'      => 'https://www.ideal-checkout.nl/support/ideal-simulator',
					'aquirer_url'      => 'https://www.ideal-checkout.nl/simulator/',
					'aquirer_test_url' => null,
					'certificates'     => array(),
				)
			),
			// ING - iDEAL Basic.
			new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
				array(
					'id'            => 'ing-ideal-basic',
					'name'          => 'ING - iDEAL Basic',					
					'provider'      => 'ing',
					'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
					'dashboard_url' => array(
						'test' => 'https://idealtest.secure-ing.com/',
						'live' => 'https://ideal.secure-ing.com/',
					),
					'aquirer_url'      => 'https://ideal.secure-ing.com/ideal/mpiPayInitIng.do',
					'aquirer_test_url' => 'https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do',
				)
			),
			// ING - iDEAL Advanced (v3).
			new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
				array(
					'id'            => 'ing-ideal-advanced-v3',
					'name'          => 'ING - iDEAL Advanced (v3)',
					'provider'      => 'ing',
					'product_url'   => 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/',
					'dashboard_url' => array(
						'test' => 'https://idealtest.secure-ing.com/',
						'live' => 'https://ideal.secure-ing.com/',
					),
					'aquirer_url'      => 'https://ideal.secure-ing.com/ideal/iDEALv3',
					'aquirer_test_url' => 'https://idealtest.secure-ing.com/ideal/iDEALv3',
					'certificates'     => array(),
				)
			),
			// ING - Kassa Compleet.
			new \Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet\Integration(),
			// Mollie.
			new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration(),
			// Mollie - iDEAL.
			new \Pronamic\WordPress\Pay\Gateways\MollieIDeal\Integration(),
			// Mollie - iDEAL Basic.
			new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
				array(
					'id'               => 'mollie-ideal-basic',
					'name'             => 'Mollie - iDEAL Basic',
					'provider'         => 'mollie',
					'dashboard_url'    => 'http://www.mollie.nl/beheer/',
					'deprecated'       => true,
					'aquirer_url'      => 'https://secure.mollie.nl/xml/idealAcquirer/lite/',
					'aquirer_test_url' => 'https://secure.mollie.nl/xml/idealAcquirer/testmode/lite/',
				)
			),
			// MultiSafePay - Connect.
			new \Pronamic\WordPress\Pay\Gateways\MultiSafepay\Integration(),
			// Nocks.
			new \Pronamic\WordPress\Pay\Gateways\Nocks\Integration(),
			// Ingenico - DirectLink.
			new \Pronamic\WordPress\Pay\Gateways\Ingenico\DirectLink\Integration(),
			// Ingenico - OrderStandard.
			new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration(),
			// Rabobank - OmniKassa.
			new \Pronamic\WordPress\Pay\Gateways\OmniKassa\Integration(),
			// Rabobank - OmniKassa 2.0.
			new \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration(),
			// Pay.nl.
			new \Pronamic\WordPress\Pay\Gateways\PayNL\Integration(),
			// Rabobank - iDEAL Professional (v3).
			new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration(
				array(
					'id'               => 'rabobank-ideal-professional-v3',
					'name'             => 'Rabobank - iDEAL Professional (v3)',
					'provider'         => 'rabobank',
					'product_url'      => 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/',
					'dashboard_url'    => array(
						'test' => 'https://idealtest.rabobank.nl/',
						'live' => 'https://ideal.rabobank.nl/',
					),
					'aquirer_url'      => 'https://ideal.rabobank.nl/ideal/iDEALv3',
					'aquirer_test_url' => 'https://idealtest.rabobank.nl/ideal/iDEALv3',
					'certificates'     => array(),
				)
			),
			// Sisow.
			new \Pronamic\WordPress\Pay\Gateways\Sisow\Integration(),
			// Sisow - iDEAL Basic.
			new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration(
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
			),
			// TargetPay.
			new \Pronamic\WordPress\Pay\Gateways\TargetPay\Integration(),
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
