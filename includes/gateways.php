<?php

global $pronamic_pay_gateways;

// @todo http://quickpay.net/
// @todo http://www.cardgate.com/

$pronamic_pay_gateways = array(
	// ABN AMRO
	'abnamro-ideal-easy' => array(
		'provider' => 'abnamro',
		'gateway'  => 'ogone_orderstandard_easy',
		'name'     => 'ABN AMRO - iDEAL Easy',
		'url'      => 'https://internetkassa.abnamro.nl/',
		'test'     => array(
			'payment_server_url' => 'https://internetkassa.abnamro.nl/ncol/test/orderstandard.asp',
		),
		'live'     => array(
			'payment_server_url' => 'https://internetkassa.abnamro.nl/ncol/prod/orderstandard.asp',
		),
	),
	'abnamro-ideal-hosted' => array(
		'provider'   => 'abnamro',
		'gateway'    => 'ideal_basic',
		'name'       => 'ABN AMRO - iDEAL Hosted',
		'test'       => array(
			'payment_server_url' => 'https://abnamro-test.ideal-payment.de/ideal/mpiPayInitFortis.do',
			'dashboard_url'      => 'https://abnamro-test.ideal-payment.de/',
		),
		'live'       => array(
			'payment_server_url' => 'https://abnamro.ideal-payment.de/ideal/mpiPayInitFortis.do',
			'dashboard_url'      => 'https://abnamro.ideal-payment.de/',
		),
		'deprecated' => true,
	),
	'abnamro-ideal-only-kassa' => array(
		'provider' => 'abnamro',
		'gateway'  => 'ogone_orderstandard',
		'name'     => 'ABN AMRO - iDEAL Only Kassa',
		'url'      => 'https://internetkassa.abnamro.nl/',
		'test'     => array(
			'payment_server_url' => 'https://internetkassa.abnamro.nl/ncol/test/orderstandard.asp',
			'dashboard_url'      => 'https://internetkassa.abnamro.nl/ncol/test/backoffice',
		),
		'live'     => array(
			'payment_server_url' => 'https://internetkassa.abnamro.nl/ncol/prod/orderstandard.asp',
			'dashboard_url'      => 'https://internetkassa.abnamro.nl/ncol/prod/backoffice',
		),
	),
	'abnamro-internetkassa' => array(
		'provider' => 'abnamro',
		'gateway'  => 'ogone_orderstandard',
		'name'     => 'ABN AMRO - Internetkassa',
		'url'      => 'https://internetkassa.abnamro.nl/',
		'test'     => array(
			'payment_server_url' => 'https://internetkassa.abnamro.nl/ncol/test/orderstandard.asp',
			'dashboard_url'      => 'https://internetkassa.abnamro.nl/ncol/test/backoffice',
		),
		'live'     => array(
			'payment_server_url' => 'https://internetkassa.abnamro.nl/ncol/prod/orderstandard.asp',
			'dashboard_url'      => 'https://internetkassa.abnamro.nl/ncol/prod/backoffice',
		),
	),
	'abnamro-ideal-zelfbouw' => array(
		'provider' => 'abnamro',
		'gateway'  => 'ideal_advanced',
		'name'     => 'ABN AMRO - iDEAL Zelfbouw',
		'url'      => 'https://abnamro.ideal-payment.de/',
		'test'     => array(
			'payment_server_url'      => 'https://abnamro-test.ideal-payment.de/ideal/iDeal',
			'directory_request_url'   => 'https://itt.idealdesk.com/ITTEmulatorAcquirer/Directory.aspx',
			'transaction_request_url' => 'https://itt.idealdesk.com/ITTEmulatorAcquirer/Transaction.aspx',
			'status_request_url'      => 'https://itt.idealdesk.com/ITTEmulatorAcquirer/Status.aspx',
			'dashboard_url'           => 'https://abnamro-test.ideal-payment.de/',
		),
		'live'     => array(
			'payment_server_url'      => 'https://abnamro.ideal-payment.de/ideal/iDeal',
			'directory_request_url'   => 'https://idealm.abnamro.nl/nl/issuerInformation/getIssuerInformation.xml',
			'transaction_request_url' => 'https://idealm.abnamro.nl/nl/acquirerTrxRegistration/getAcquirerTrxRegistration.xml',
			'status_request_url'      => 'https://idealm.abnamro.nl/nl/acquirerStatusInquiry/getAcquirerStatusInquiry.xml',
			'dashboard_url'           => 'https://abnamro.ideal-payment.de/',
		),
		'certificates' => array(
			'certificates/abnamro-ideal-zelfbouw/abnamro.integrated.cer'
		),
		'deprecated' => true,
	),
	'abnamro-ideal-zelfbouw-v3' => array(
		'provider' => 'abnamro',
		'gateway'  => 'ideal_advanced_v3',
		'name'     => 'ABN AMRO - iDEAL Zelfbouw - v3',
		'url'      => 'https://abnamro.ideal-payment.de/',
		'test'     => array(
			'payment_server_url'      => 'https://abnamro-test.ideal-payment.de/ideal/iDEALv3',
			'dashboard_url'           => 'https://abnamro-test.ideal-payment.de/',
		),
		'live'     => array(
			'payment_server_url'      => 'https://abnamro.ideal-payment.de/ideal/iDEALv3',
			'dashboard_url'           => 'https://abnamro.ideal-payment.de/',
		),
		'certificates' => array(
			'certificates/abnamro-ideal-zelfbouw/abnamro.integrated.cer'
		),
	),
	/*
	// Adyen
	'adyen' => array(
		'provider' => 'adyen',
		'gateway'  => 'adyen',
		'name'     => 'Adyen',
		'url'      => 'http://www.adyen.com/',
		'test'     => array(
			'payment_server_url' => 'https://test.adyen.com/hpp/pay.shtml',
			'dashboard_url'      => 'https://ca-test.adyen.com/',
		),
		'live'     => array(
			'payment_server_url' => 'https://live.adyen.com/hpp/pay.shtml',
			'dashboard_url'      => 'https://ca-live.adyen.com/',
		),
	),
	*/
	// Buckaroo
	'buckaroo' => array(
		'provider'      => 'buckaroo',
		'gateway'       => 'buckaroo',
		'name'          => 'Buckaroo - HTML',
		'url'           => 'https://payment.buckaroo.nl/',
		'dashboard_url' => 'https://payment.buckaroo.nl/',
		'test'          => array(
			'payment_server_url' => 'https://testcheckout.buckaroo.nl/html/',
		),
		'live'          => array(
			'payment_server_url' => 'https://checkout.buckaroo.nl/html/',
		),
	),
	// Deutsche Bank - iDEAL Expert - v3
	'deutschebank-ideal-via-ogone' => array(
		'provider' => 'deutschebank',
		'gateway'  => 'ogone_orderstandard_easy',
		'name'     => 'Deutsche Bank - iDEAL via Ogone',
		'test'     => array(
			'payment_server_url' => 'https://secure.ogone.com/ncol/test/orderstandard.asp',
		),
		'live'     => array(
			'payment_server_url' => 'https://secure.ogone.com/ncol/prod/orderstandard.asp',
		),
	),
	'deutschebank-ideal-expert-v3' => array(
		'provider' => 'deutschebank',
		'gateway'  => 'ideal_advanced_v3',
		'name'     => 'Deutsche Bank - iDEAL Expert - v3',
		'url'      => 'https://ideal.db.com/',
		'test'     => array(
			'payment_server_url' => 'https://myideal.db.com/ideal/iDealv3',
			'dashboard_url'      => 'https://myidealtest.db.com/',
		),
		'live'     => array(
			'payment_server_url' => 'https://myideal.db.com/ideal/iDealv3',
			'dashboard_url'      => 'https://myideal.db.com/',
		),
		'certificates' => array(
			'certificates/deutschebank-ideal-expert-v3/?.cer',
		),
	),
	// Dutch Payment Group
	'paydutch' => array(
		'provider'      => 'dutchpaymentgroup',
		'gateway'       => 'paydutch',
		'name'          => 'PayDutch',
		'url'           => 'http://www.paydutch.nl/',
		'dashboard_url' => 'https://www.paydutch.nl/paydutchmanager/',
		'test'          => array(
			'payment_server_url' => 'https://www.paydutch.nl/api/processreq.aspx',
		),
		'live'     => array(
			'payment_server_url' => 'https://www.paydutch.nl/api/processreq.aspx',
		),
	),
	// Easy iDeal
	'easy-ideal' => array(
		'provider'      => 'easy-ideal',
		'gateway'       => 'qantani',
		'name'          => 'Easy iDeal - iDEAL',
		'url'           => 'http://www.easy-ideal.com/',
		'dashboard_url' => 'https://www.qantanipayments.com/backoffice/login/',
	),
	// Fortis Bank
	'fortis-bank-ideal-hosted' => array(
		'disabled' => true,
		'provider' => 'fortis-bank',
		'gateway'  => 'ideal_basic',
		'name'     => 'Fortis Bank - iDEAL Hosted',
		'deprecated' => true,
	),
	'fortis-bank-internetkassa' => array(
		'provider' => 'fortis-bank',
		'gateway'  => 'ogone_orderstandard',
		'name'     => 'Fortis Bank - iDEAL Internet Kassa',
		'test'     => array(
			'payment_server_url' => 'https://www.secure.neos-solution.com/ncol/test/orderstandard.asp',
			'dashboard_url'      => 'https://www.secure.neos-solution.com/ncol/test/admin_NEOS.asp',
		),
		'live'     => array(
			'payment_server_url' => 'https://www.secure.neos-solution.com/ncol/prod/orderstandard.asp',
			'dashboard_url'      => 'https://www.secure.neos-solution.com/ncol/prod/admin_NEOS.asp',
		),
		'deprecated' => true,
	),
	'fortis-bank-ideal-integrated' => array(
		'provider' => 'fortis-bank',
		'gateway'  => 'ideal_advanced',
		'name'     => 'Fortis Bank - iDEAL Integrated',
		'test'     => array(
			'payment_server_url' => 'https://acquirer-ideal.test.nl.fortis.com/ideal/iDeal',
		),
		'live'     => array(
			'payment_server_url' => 'https://acquirer-ideal.bank.nl.fortis.com/ideal/iDeal',
		),
		'certificates' => array(
			'certificates/fortis-bank-ideal-integrated/fortisbank-0.cer',
		),
		'deprecated' => true,
	),
	// Friesland Bank
	'frieslandbank-ideal-zakelijk' => array(
		'provider' => 'frieslandbank',
		'gateway'  => 'ideal_basic',
		'name'     => 'Friesland Bank - iDEAL Zakelijk',
		'test'     => array(
			'payment_server_url' => 'https://testidealkassa.frieslandbank.nl/ideal/mpiPayInitFriesland.do',
			'dashboard_url'      => 'https://testidealkassa.frieslandbank.nl/',
		),
		'live'     => array(
			'payment_server_url' => 'https://idealkassa.frieslandbank.nl/ideal/mpiPayInitFriesland.do',
			'dashboard_url'      => 'https://idealkassa.frieslandbank.nl/',
		),
	),
	'frieslandbank-ideal-zakelijk-plus' => array(
		'provider' => 'frieslandbank',
		'gateway'  => 'ideal_advanced',
		'name'     => 'Friesland Bank - iDEAL Zakelijk Plus',
		'test'     => array(
			'payment_server_url' => 'https://testidealkassa.frieslandbank.nl/ideal/iDEAL',
			'dashboard_url'      => 'https://testidealkassa.frieslandbank.nl/',
		),
		'live'     => array(
			'payment_server_url' => 'https://idealkassa.frieslandbank.nl/ideal/iDEAL',
			'dashboard_url'      => 'https://idealkassa.frieslandbank.nl/',
		),
		'certificates' => array(
			'certificates/frieslandbank-ideal-zakelijke-plus/cert.cer',
		),
		'deprecated' => true,
	),
	'frieslandbank-ideal-zakelijk-plus-v3' => array(
		'provider' => 'frieslandbank',
		'gateway'  => 'ideal_advanced_v3',
		'name'     => 'Friesland Bank - iDEAL Zakelijk Plus - v3',
		'test'     => array(
			'payment_server_url' => 'https://testidealkassa.frieslandbank.nl/ideal/iDEALv3',
			'dashboard_url'      => 'https://testidealkassa.frieslandbank.nl/',
		),
		'live'     => array(
			'payment_server_url' => 'https://idealkassa.frieslandbank.nl/ideal/iDEALv3',
			'dashboard_url'      => 'https://idealkassa.frieslandbank.nl/',
		),
		'certificates' => array(
			'certificates/frieslandbank-ideal-zakelijk-plus-v3/frieslandbank.cer',
		),
	),
	// ICEPAY
	'icepay-ideal' => array(
		'provider'      => 'icepay',
		'gateway'       => 'icepay',
		'name'          => 'ICEPAY',
		'dashboard_url' => __( 'https://www.icepay.com/Merchant/EN/Reports/Transactions', 'pronamic_ideal' ),
	),
	// iDEAL Simulator
	'ideal-simulator-ideal-basic' => array(
		'provider' => 'ideal-simulator',
		'gateway'  => 'ideal_basic',
		'name'     => 'iDEAL Simulator - iDEAL Lite / Basic',
		'test'     => array(
			'payment_server_url' => 'https://www.ideal-simulator.nl/lite/',
		),
		'live'     => array(
			'payment_server_url' => 'https://www.ideal-simulator.nl/lite/',
		),
	),
	'ideal-simulator-ideal-advanced' => array(
		'provider' => 'ideal-simulator',
		'gateway'  => 'ideal_advanced',
		'name'     => 'iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw',
		'test'     => array(
			'payment_server_url' => 'https://www.ideal-simulator.nl/professional/',
		),
		'live'     => array(
			'payment_server_url' => 'https://www.ideal-simulator.nl/professional/',
		),
		'certificates' => array(
			'certificates/ideal-simulator-ideal-advanced/simulator.cer',
			'certificates/ideal-simulator-ideal-advanced/simulator0.cer',
		),
		'deprecated' => true,
	),
	'ideal-simulator-ideal-advanced-v3' => array(
		'provider' => 'ideal-simulator',
		'gateway'  => 'ideal_advanced_v3',
		'name'     => 'iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw - v3',
		'test'     => array(
			'payment_server_url' => 'https://www.ideal-checkout.nl/simulator/',
		),
		'live'     => array(
			'payment_server_url' => 'https://www.ideal-checkout.nl/simulator/',
		),
		'certificates' => array(
			'certificates/ideal-simulator-ideal-advanced-v3/simulator-2048.cer',
		),
	),
	// ING
	'ing-ideal-basic' => array(
		'provider' => 'ing',
		'gateway'  => 'ideal_basic',
		'name'     => 'ING - iDEAL Basic',
		'test'     => array(
			'payment_server_url' => 'https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do',
			'dashboard_url'      => 'https://idealtest.secure-ing.com/',
		),
		'live'     => array(
			'payment_server_url' => 'https://ideal.secure-ing.com/ideal/mpiPayInitIng.do',
			'dashboard_url'      => 'https://ideal.secure-ing.com/',
		),
	),
	'ing-ideal-internetkassa' => array(
		'provider' => 'ing',
		'gateway'  => 'ogone_orderstandard',
		'name'     => 'ING - iDEAL Internet Kassa',
		'test'     => array(
			'payment_server_url' => 'https://twyp.secure-ing.com/ncol/test/orderstandard.asp',
			'dashboard_url'      => 'https://twyp.secure-ing.com/ncol/test/admin_TWYP.asp',
		),
		'live'     => array(
			'payment_server_url' => 'https://twyp.secure-ing.com/ncol/prod/orderstandard.asp',
			'dashboard_url'      => 'https://twyp.secure-ing.com/ncol/prod/admin_TWYP.asp',
		),
	),
	'ing-ideal-advanced' => array(
		'provider' => 'ing',
		'gateway'  => 'ideal_advanced',
		'name'     => 'ING - iDEAL Advanced',
		'test'     => array(
			'payment_server_url' => 'https://idealtest.secure-ing.com/ideal/iDeal',
			'dashboard_url'      => 'https://idealtest.secure-ing.com/',
		),
		'live'     => array(
			'payment_server_url' => 'https://ideal.secure-ing.com/ideal/iDeal',
			'dashboard_url'      => 'https://ideal.secure-ing.com/',
		),
		'certificates' => array(
			'certificates/ing-ideal-advanced/ingbank.cer',
			'certificates/ing-ideal-advanced/ingbank-0.cer',
		),
		'deprecated' => true,
	),
	'ing-ideal-advanced-v3' => array(
		'provider' => 'ing',
		'gateway'  => 'ideal_advanced_v3',
		'name'     => 'ING - iDEAL Advanced - v3',
		'test'     => array(
			'payment_server_url' => 'https://idealtest.secure-ing.com/ideal/iDEALv3',
			'dashboard_url'      => 'https://idealtest.secure-ing.com/',
		),
		'live'     => array(
			'payment_server_url' => 'https://ideal.secure-ing.com/ideal/iDEALv3',
			'dashboard_url'      => 'https://ideal.secure-ing.com/',
		),
		'certificates' => array(
			'certificates/ing-ideal-advanced-v3/ingbank.cer',
		),
	),
	// Mollie
	'mollie' => array(
		'provider'      => 'mollie',
		'gateway'       => 'mollie',
		'name'          => 'Mollie',
		'dashboard_url' => 'http://www.mollie.nl/',
		'methods'       => array( 'ideal', 'mister_cash' ),
	),
	'mollie-ideal' => array(
		'provider'      => 'mollie',
		'gateway'       => 'mollie_ideal',
		'name'          => 'Mollie - iDEAL',
		'dashboard_url' => 'http://www.mollie.nl/',
		'deprecated' => true,
	),
	'mollie-ideal-basic' => array(
		'provider'      => 'mollie',
		'gateway'       => 'ideal_basic',
		'name'          => 'Mollie - iDEAL Basic',
		'dashboard_url' => 'http://www.mollie.nl/',
		'test'          => array(
			'payment_server_url' => 'https://secure.mollie.nl/xml/idealAcquirer/testmode/lite/',
		),
		'live'          => array(
			'payment_server_url' => 'https://secure.mollie.nl/xml/idealAcquirer/lite/',
		),
		'deprecated' => true,
	),
	'mollie-ideal-advanced' => array(
		'provider'      => 'mollie',
		'gateway'       => 'ideal_advanced',
		'name'          => 'Mollie - iDEAL Advanced',
		'dashboard_url' => 'http://www.mollie.nl/',
		'test'          => array(
			'payment_server_url' => 'https://secure.mollie.nl/xml/idealAcquirer/testmode/',
		),
		'live'          => array(
			'payment_server_url' => 'https://secure.mollie.nl/xml/idealAcquirer/',
		),
		'certificates' => array(
			'certificates/mollie-ideal-advanced/mollie.cer',
		),
		'deprecated' => true,
	),
	// MultiSafepay
	'multisafepay-connect' => array(
		'provider' => 'multisafepay',
		'gateway'  => 'multisafepay_connect',
		'name'     => 'MultiSafepay - Connect',
		'url'      => 'http://www.multisafepay.com/',
	),
	// NEOS
	'neos-internetkassa' => array(
		'provider' => 'neos',
		'gateway'  => 'ogone_orderstandard',
		'name'     => 'NEOS - Internet Kassa',
		'url'      => 'http://www.multisafepay.com/',
		'test'     => array(
			'payment_server_url' => 'https://www.secure.neos-solution.com/ncol/test/orderstandard.asp',
			'dashboard_url'      => 'https://www.secure.neos-solution.com/ncol/test/admin_NEOS.asp',
		),
		'live'     => array(
			'payment_server_url' => 'https://www.secure.neos-solution.com/ncol/prod/orderstandard.asp',
			'dashboard_url'      => 'https://www.secure.neos-solution.com/ncol/prod/admin_NEOS.asp',
		),
		'deprecated' => true,
	),
	// Ogone
	'ogone-easy' => array(
		'provider' => 'ogone',
		'gateway'  => 'ogone_orderstandard_easy',
		'name'     => 'Ogone - Easy',
		'url'      => 'https://secure.ogone.com/',
		'test'     => array(
			'payment_server_url' => 'https://secure.ogone.com/ncol/test/orderstandard.asp',
		),
		'live'     => array(
			'payment_server_url' => 'https://secure.ogone.com/ncol/prod/orderstandard.asp',
		),
	),
	'ogone-orderstandard' => array(
		'provider' => 'ogone',
		'gateway'  => 'ogone_orderstandard',
		'name'     => 'Ogone - OrderStandard',
		'url'      => 'https://secure.ogone.com/',
		'test'     => array(
			'payment_server_url' => 'https://secure.ogone.com/ncol/test/orderstandard.asp',
			'dashboard_url'      => 'https://secure.ogone.com/ncol/test/backoffice',
		),
		'live'     => array(
			'payment_server_url' => 'https://secure.ogone.com/ncol/prod/orderstandard.asp',
			'dashboard_url'      => 'https://secure.ogone.com/ncol/prod/backoffice',
		),
	),
	'ogone-directlink' => array(
		'provider' => 'ogone',
		'gateway'  => 'ogone_directlink',
		'name'     => 'Ogone - DirectLink',
		'url'      => 'https://secure.ogone.com/',
		'test'     => array(
			'payment_server_url' => 'https://secure.ogone.com/ncol/test/orderdirect.asp',
			'dashboard_url'      => 'https://secure.ogone.com/ncol/test/admin_ogone.asp',
		),
		'live'     => array(
			'payment_server_url' => 'https://secure.ogone.com/ncol/prod/orderdirect.asp',
			'dashboard_url'      => 'https://secure.ogone.com/ncol/prod/admin_ogone.asp',
		),
	),
	// Pay.nl
	'pay_nl' => array(
		'provider'      => 'pay_nl',
		'gateway'       => 'pay_nl',
		'name'          => 'Pay.nl',
		'dashboard_url' => 'https://www.pay.nl/',
	),
	// Paytor
	'paytor' => array(
		'provider'      => 'paytor',
		'gateway'       => 'mollie',
		'name'          => 'Paytor',
		'dashboard_url' => 'http://paytor.com/',
	),
	// Rabobank
	'rabobank-ideal-lite' => array(
		'provider' => 'rabobank',
		'gateway'  => 'ideal_basic',
		'name'     => 'Rabobank - iDEAL Lite',
		'test'     => array(
			'payment_server_url' => 'https://idealtest.rabobank.nl/ideal/mpiPayInitRabo.do',
			'dashboard_url'      => 'https://idealtest.rabobank.nl/',
		),
		'live'     => array(
			'payment_server_url' => 'https://ideal.rabobank.nl/ideal/mpiPayInitRabo.do',
			'dashboard_url'      => 'https://ideal.rabobank.nl/',
		),
	),
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
	'rabobank-omnikassa' => array(
		'provider'      => 'rabobank',
		'gateway'       => 'omnikassa',
		'name'          => 'Rabobank - OmniKassa',
		'dashboard_url' => 'https://dashboard.omnikassa.rabobank.nl/',
		'test'     => array(
			'payment_server_url' => 'https://payment-webinit.simu.omnikassa.rabobank.nl/paymentServlet',
			'download_url'       => 'https://download.omnikassa.rabobank.nl/',
		),
		'live'     => array(
			'payment_server_url' => 'https://payment-webinit.omnikassa.rabobank.nl/paymentServlet',
			'download_url'       => 'https://download.omnikassa.rabobank.nl/',
		),
		'methods'       => array( 'ideal', 'mister_cash' ),
	),
	'rabobank-ideal-professional' => array(
		'provider' => 'rabobank',
		'gateway'  => 'ideal_advanced',
		'name'     => 'Rabobank - iDEAL Professional',
		'test'     => array(
			'payment_server_url' => 'https://idealtest.rabobank.nl/ideal/iDeal',
			'dashboard_url'      => 'https://idealtest.rabobank.nl/',
		),
		'live'     => array(
			'payment_server_url' => 'https://ideal.rabobank.nl/ideal/iDeal',
			'dashboard_url'      => 'https://ideal.rabobank.nl/',
		),
		'certificates'           => array(
			'certificates/rabobank-ideal-professional/ideal_v3.cer',
			'certificates/rabobank-ideal-professional/ideal_v2.cer',
		),
		'deprecated' => true,
	),
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
	// Sisow
	'sisow-ideal' => array(
		'provider'      => 'sisow',
		'gateway'       => 'sisow',
		'name'          => 'Sisow',
		'dashboard_url' => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
	),
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
	'targetpay-ideal' => array(
		'provider'      => 'targetpay',
		'gateway'       => 'targetpay',
		'name'          => 'TargetPay - iDEAL',
		'dashboard_url' => 'https://www.targetpay.com/login',
	),
	// Qantani
	'qantani' => array(
		'provider'      => 'qantani',
		'gateway'       => 'qantani',
		'name'          => 'Qantani - iDEAL',
		'url'           => 'https://www.qantani.com/',
		'dashboard_url' => 'https://www.qantanipayments.com/backoffice/login/',
	),
);
