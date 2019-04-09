<?php
/**
 * Providers
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

global $pronamic_pay_providers;

$pronamic_pay_providers = array(
	'abnamro'           => array(
		'name' => 'ABN AMRO',
		'url'  => 'https://www.abnamro.nl/',
	),
	'adyen'             => array(
		'name' => 'Adyen',
		'url'  => 'https://www.adyen.com/',
	),
	'buckaroo'          => array(
		'name' => 'Buckaroo',
		'url'  => 'https://www.buckaroo.nl/',
	),
	'deutschebank'      => array(
		'name' => 'Deutsche Bank',
		'url'  => 'https://deutschebank.nl/',
	),
	'dutchpaymentgroup' => array(
		'name' => 'Dutch Payment Group',
		'url'  => 'http://www.dutchpaymentgroup.com/',
	),
	'easy-ideal'        => array(
		'name'          => 'Easy iDeal',
		'url'           => 'http://www.easy-ideal.com/',
		'affiliate_url' => 'https://www.qantanipayments.com/backoffice/signup/easyideal/step1/pronamic/',
	),
	'ems'               => array(
		'name' => 'European Merchant Services',
		'url'  => 'https://emspay.nl/',
	),
	'fibonacciorange'   => array(
		'name' => 'Fibonacci ORANGE',
		'url'  => 'http://www.fibonacciorange.com/',
	),
	'icepay'            => array(
		'name' => 'ICEPAY',
		'url'  => 'https://icepay.nl/',
	),
	'ideal-simulator'   => array(
		'name' => 'iDEAL Simulator',
		'url'  => 'https://www.ideal-simulator.nl/',
	),
	'ing'               => array(
		'name' => 'ING',
		'url'  => 'https://www.ing.nl/',
	),
	'mollie'            => array(
		'name' => 'Mollie',
		'url'  => 'https://www.mollie.nl/',
	),
	'multisafepay'      => array(
		'name' => 'MultiSafepay',
		'url'  => 'https://www.multisafepay.com/',
	),
	'nocks'             => array(
		'name' => 'Nocks',
		'url'  => 'https://www.nocks.com/',
	),
	'ogone'             => array(
		'name' => 'Ingenico/Ogone',
		'url'  => 'https://payment-services.ingenico.com/',
	),
	'pay_nl'            => array(
		'name' => 'Pay.nl',
		'url'  => 'https://www.pay.nl/',
	),
	'rabobank'          => array(
		'name' => 'Rabobank',
		'url'  => 'https://www.rabobank.nl/',
	),
	'sisow'             => array(
		'name'          => 'Sisow',
		'url'           => 'https://www.sisow.nl/',
		'affiliate_url' => 'https://www.sisow.nl/Sisow/iDeal/Aanmelden.aspx?r=120872',
	),
	'targetpay'         => array(
		'name'          => 'TargetPay',
		'url'           => 'https://www.targetpay.com/',
		'affiliate_url' => 'https://www.targetpay.com/quickreg/85315',
	),
);
