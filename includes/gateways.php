<?php

global $pronamic_pay_gateways;

// @todo http://quickpay.net/
// @todo http://www.cardgate.com/

$pronamic_pay_gateways = array(
	// https://github.com/wp-pay-gateways/rabobank-ideal-professional-v3
	'rabobank-ideal-professional-v3' => array(
		'provider' => 'rabobank',
		'gateway'  => 'ideal_advanced_v3',
		'name'     => 'Rabobank - iDEAL Professional - v3',
		'test'     => array(
			'payment_server_url' => 'https://idealtest.rabobank.nl/ideal/iDEALv3',
			'dashboard_url'      => 'https://idealtest.rabobank.nl/',
		),
		'live'     => array(
			'payment_server_url' => 'https://ideal.rabobank.nl/ideal/iDEALv3',
			'dashboard_url'      => 'https://ideal.rabobank.nl/',
		),
		'certificates'           => array(
			'certificates/rabobank-ideal-professional-v3/rabobank.cer',
		),
	),
	// https://github.com/wp-pay-gateways/rabobank-ideal-kassa
	'rabobank-ideal-kassa' => array(
		'provider' => 'rabobank',
		'gateway'  => 'ogone_orderstandard',
		'name'     => 'Rabobank - Rabo iDEAL Kassa',
		'url'      => 'http://i-kassa.rabobank.nl/',
		'test'     => array(
			'payment_server_url' => 'https://i-kassa.rabobank.nl/rik/test/orderstandard.asp',
			'dashboard_url'      => 'https://i-kassa.rabobank.nl/rik/test/admin_RIK.asp',
		),
		'live'     => array(
			'payment_server_url' => 'https://i-kassa.rabobank.nl/rik/prod/orderstandard.asp',
			'dashboard_url'      => 'https://i-kassa.rabobank.nl/rik/prod/admin_RIK.asp',
		),
	),
	// Sisow
	// https://github.com/wp-pay-gateways/sisow
	'sisow-ideal' => array(
		'provider'      => 'sisow',
		'gateway'       => 'sisow',
		'name'          => 'Sisow',
		'dashboard_url' => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
	),
	// https://github.com/wp-pay-gateways/sisow-ideal-basic
	'sisow-ideal-basic' => array(
		'provider'      => 'sisow',
		'gateway'       => 'ideal_basic',
		'name'          => 'Sisow - iDEAL Basic',
		'dashboard_url' => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
		'test'          => array(
			'payment_server_url' => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx/test',
		),
		'live'          => array(
			'payment_server_url' => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx',
		),
		'deprecated' => true,
	),
	// https://github.com/wp-pay-gateways/sisow-ideal-advanced
	'sisow-ideal-advanced' => array(
		'provider'      => 'sisow',
		'gateway'       => 'ideal_advanced',
		'name'          => 'Sisow - iDEAL Advanced',
		'dashboard_url' => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
		'test'          => array(
			'payment_server_url' => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx/test',
		),
		'live'          => array(
			'payment_server_url' => 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx',
		),
		'certificates'  => array(
			'certificates/sisow-ideal-advanced/sisowideal.cer',
		),
		'deprecated' => true,
	),
	// TargetPay
	// https://github.com/wp-pay-gateways/targetpay
	'targetpay-ideal' => array(
		'provider'      => 'targetpay',
		'gateway'       => 'targetpay',
		'name'          => 'TargetPay - iDEAL',
		'dashboard_url' => 'https://www.targetpay.com/login',
	),
	// Qantani
	// https://github.com/wp-pay-gateways/qantani
	'qantani' => array(
		'provider'      => 'qantani',
		'gateway'       => 'qantani',
		'name'          => 'Qantani - iDEAL',
		'url'           => 'https://www.qantani.com/',
		'dashboard_url' => 'https://www.qantanipayments.com/backoffice/login/',
	),
);
