<?php
/**
 * Gateway integrations.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

use Pronamic\WordPress\Pay\Gateways\Common\AbstractIntegration;

/**
 * Gateway integrations.
 *
 * @return array
 */
function pronamic_pay_gateway_integrations() {
	return array(
		// ABN AMRO - iDEAL Zelfbouw (v3).
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'abnamro-ideal-zelfbouw-v3' );
				$integration->set_name( 'ABN AMRO - iDEAL Zelfbouw (v3)' );

				$integration->provider      = 'abnamro';
				$integration->url           = 'https://abnamro.ideal-payment.de/';
				$integration->product_url   = 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/';
				$integration->dashboard_url = array(
					'test' => 'https://abnamro-test.ideal-payment.de/',
					'live' => 'https://abnamro.ideal-payment.de/',
				);
			},
		),

		// Buckaroo.
		'\Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration',

		// Deutsche Bank - iDEAL via Ogone.
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandardEasy\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'deutschebank-ideal-via-ogone' );
				$integration->set_name( 'Deutsche Bank - iDEAL via Ogone' );

				$integration->provider    = 'deutschebank';
				$integration->product_url = 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html';
			},
		),

		// Deutsche Bank - iDEAL Expert (v3).
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'deutschebank-ideal-expert-v3' );
				$integration->set_name( 'Deutsche Bank - iDEAL Expert (v3)' );

				$integration->provider      = 'deutschebank';
				$integration->product_url   = 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html';
				$integration->dashboard_url = array(
					'test' => 'https://myideal.test.db.com/',
					'live' => 'https://myideal.db.com/',
				);
			},
		),

		// EMS - eCommerce.
		'\Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration',

		// Fibonacci ORANGE.
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\Icepay\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'fibonacciorange' );
				$integration->set_name( 'Fibonacci ORANGE' );

				$integration->provider    = 'fibonacciorange';
				$integration->product_url = 'http://www.fibonacciorange.nl/';
			},
		),

		// ICEPAY.
		'\Pronamic\WordPress\Pay\Gateways\Icepay\Integration',

		// iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3).
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'ideal-simulator-ideal-advanced-v3' );
				$integration->set_name( 'iDEAL Simulator - iDEAL Professional / Advanced' );

				$integration->provider    = 'ideal-simulator';
				$integration->product_url = 'https://www.ideal-checkout.nl/support/ideal-simulator';
			},
		),

		// ING - iDEAL Basic.
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'ing-ideal-basic' );
				$integration->set_name( 'ING - iDEAL Basic' );

				$integration->provider      = 'ing';
				$integration->product_url   = 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/';
				$integration->dashboard_url = array(
					'test' => 'https://idealtest.secure-ing.com/',
					'live' => 'https://ideal.secure-ing.com/',
				);
			},
		),

		// ING - iDEAL Advanced (v3).
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'ing-ideal-advanced-v3' );
				$integration->set_name( 'ING - iDEAL Advanced (v3)' );

				$integration->provider      = 'ing';
				$integration->product_url   = 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/';
				$integration->dashboard_url = array(
					'test' => 'https://idealtest.secure-ing.com/',
					'live' => 'https://ideal.secure-ing.com/',
				);
			},
		),

		// ING - Kassa Compleet.
		'\Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet\Integration',

		// Mollie.
		'\Pronamic\WordPress\Pay\Gateways\Mollie\Integration',

		// Mollie - iDEAL.
		'\Pronamic\WordPress\Pay\Gateways\MollieIDeal\Integration',

		// Mollie - iDEAL Basic.
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'mollie-ideal-basic' );
				$integration->set_name( 'Mollie - iDEAL Basic' );

				$integration->provider      = 'mollie';
				$integration->dashboard_url = 'http://www.mollie.nl/beheer/';
				$integration->deprecated    = true;
			},
		),

		// MultiSafePay - Connect.
		'\Pronamic\WordPress\Pay\Gateways\MultiSafepay\Integration',

		// Nocks.
		'\Pronamic\WordPress\Pay\Gateways\Nocks\Integration',

		// Ingenico - DirectLink.
		'\Pronamic\WordPress\Pay\Gateways\Ingenico\DirectLink\Integration',

		// Ingenico - OrderStandard.
		'\Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration',

		// Rabobank - OmniKassa.
		'\Pronamic\WordPress\Pay\Gateways\OmniKassa\Integration',

		// Rabobank - OmniKassa 2.0.
		'\Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration',

		// Pay.nl.
		'\Pronamic\WordPress\Pay\Gateways\PayNL\Integration',

		// Rabobank - iDEAL Professional (v3).
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'rabobank-ideal-professional-v3' );
				$integration->set_name( 'Rabobank - iDEAL Professional (v3)' );

				$integration->provider      = 'rabobank';
				$integration->product_url   = 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/';
				$integration->dashboard_url = array(
					'test' => 'https://idealtest.rabobank.nl/',
					'live' => 'https://ideal.rabobank.nl/',
				);
			},
		),

		// Sisow.
		'\Pronamic\WordPress\Pay\Gateways\Sisow\Integration',

		// Sisow - iDEAL Basic.
		array(
			'class'    => '\Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration',
			'callback' => function( AbstractIntegration $integration ) {
				$integration->set_id( 'sisow-ideal-basic' );
				$integration->set_name( 'Sisow - iDEAL Basic' );

				$integration->provider      = 'sisow';
				$integration->url           = 'https://www.sisow.nl/';
				$integration->dashboard_url = 'https://www.sisow.nl/Sisow/iDeal/Login.aspx';
				$integration->deprecated    = true;
			},
		),

		// TargetPay.
		'\Pronamic\WordPress\Pay\Gateways\TargetPay\Integration',
	);
}
