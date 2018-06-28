<?php
/**
 * Gateway integrations.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

/**
 * Gateway integrations.
 *
 * @return array
 */
function pronamic_pay_gateway_integrations() {
	// ABN AMRO iDEAL Easy.
	$abn_amro_ideal_easy = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandardEasy\Integration();
	$abn_amro_ideal_easy->set_id( 'abnamro-ideal-easy' );
	$abn_amro_ideal_easy->set_name( 'ABN AMRO - iDEAL Easy' );
	$abn_amro_ideal_easy->url           = 'https://internetkassa.abnamro.nl/';
	$abn_amro_ideal_easy->product_url   = 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/';
	$abn_amro_ideal_easy->dashboard_url = 'https://internetkassa.abnamro.nl/';
	$abn_amro_ideal_easy->provider      = 'abnamro';

	// ABN AMRO - iDEAL Only Kassa.
	$abn_amro_ideal_only_kassa = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration();
	$abn_amro_ideal_only_kassa->set_id( 'abnamro-ideal-only-kassa' );
	$abn_amro_ideal_only_kassa->set_name( 'ABN AMRO - iDEAL Only Kassa' );
	$abn_amro_ideal_only_kassa->url           = 'https://internetkassa.abnamro.nl/';
	$abn_amro_ideal_only_kassa->product_url   = 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/';
	$abn_amro_ideal_only_kassa->dashboard_url = 'https://internetkassa.abnamro.nl/';
	$abn_amro_ideal_only_kassa->provider      = 'abnamro';

	// ABN AMRO - Internetkassa.
	$abn_amro_internetkassa = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration();
	$abn_amro_internetkassa->set_id( 'abnamro-internetkassa' );
	$abn_amro_internetkassa->set_name( 'ABN AMRO - Internetkassa' );
	$abn_amro_internetkassa->url           = 'https://internetkassa.abnamro.nl/';
	$abn_amro_internetkassa->product_url   = 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/';
	$abn_amro_internetkassa->dashboard_url = 'https://internetkassa.abnamro.nl/';
	$abn_amro_internetkassa->provider      = 'abnamro';

	// ABN AMRO - iDEAL Zelfbouw (v3).
	$abn_amro_ideal_zelfbouw_v3 = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration();
	$abn_amro_ideal_zelfbouw_v3->set_id( 'abnamro-ideal-zelfbouw-v3' );
	$abn_amro_ideal_zelfbouw_v3->set_name( 'ABN AMRO - iDEAL Zelfbouw (v3)' );
	$abn_amro_ideal_zelfbouw_v3->url           = 'https://abnamro.ideal-payment.de/';
	$abn_amro_ideal_zelfbouw_v3->product_url   = 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/';
	$abn_amro_ideal_zelfbouw_v3->dashboard_url = array(
		'test' => 'https://abnamro-test.ideal-payment.de/',
		'live' => 'https://abnamro.ideal-payment.de/',
	);
	$abn_amro_ideal_zelfbouw_v3->provider      = 'abnamro';

	// Deutsche Bank - iDEAL via Ogone.
	$deutsche_bank_ideal_ogone = new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandardEasy\Integration();
	$deutsche_bank_ideal_ogone->set_id( 'deutschebank-ideal-via-ogone' );
	$deutsche_bank_ideal_ogone->set_name( 'Deutsche Bank - iDEAL via Ogone' );
	$deutsche_bank_ideal_ogone->product_url = 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html';
	$deutsche_bank_ideal_ogone->provider    = 'deutschebank';

	// Deutsche Bank - iDEAL Expert (v3).
	$deutsche_bank_ideal_expert_v3 = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration();
	$deutsche_bank_ideal_expert_v3->set_id( 'deutschebank-ideal-expert-v3' );
	$deutsche_bank_ideal_expert_v3->set_name( 'Deutsche Bank - iDEAL Expert (v3)' );
	$deutsche_bank_ideal_expert_v3->product_url   = 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html';
	$deutsche_bank_ideal_expert_v3->dashboard_url = array(
		'test' => 'https://myideal.test.db.com/',
		'live' => 'https://myideal.db.com/',
	);
	$deutsche_bank_ideal_expert_v3->provider      = 'deutschebank';

	// Fibonacci ORANGE.
	$fibonacci_orange = new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration();
	$fibonacci_orange->set_id( 'fibonacciorange' );
	$fibonacci_orange->set_name( 'Fibonacci ORANGE' );
	$fibonacci_orange->product_url = 'http://www.fibonacciorange.nl/';
	$fibonacci_orange->provider    = 'fibonacciorange';

	// iDEAL Simulator - iDEAL Lite / Basic.
	$ideal_simulator_ideal_basic = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration();
	$ideal_simulator_ideal_basic->set_id( 'ideal-simulator-ideal-basic' );
	$ideal_simulator_ideal_basic->set_name( 'iDEAL Simulator - iDEAL Lite / Basic' );
	$ideal_simulator_ideal_basic->provider = 'ideal-simulator';

	// iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3).
	$ideal_simulator_ideal_advanced_v3 = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration();
	$ideal_simulator_ideal_advanced_v3->set_id( 'ideal-simulator-ideal-advanced-v3' );
	$ideal_simulator_ideal_advanced_v3->set_name( 'iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3)' );
	$ideal_simulator_ideal_advanced_v3->provider    = 'ideal-simulator';
	$ideal_simulator_ideal_advanced_v3->product_url = 'https://www.ideal-checkout.nl/support/ideal-simulator';

	// ING - iDEAL Basic.
	$ing_ideal_basic = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration();
	$ing_ideal_basic->set_id( 'ing-ideal-basic' );
	$ing_ideal_basic->set_name( 'ING - iDEAL Basic' );
	$ing_ideal_basic->provider      = 'ing';
	$ing_ideal_basic->product_url   = 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/';
	$ing_ideal_basic->dashboard_url = array(
		'test' => 'https://idealtest.secure-ing.com/',
		'live' => 'https://ideal.secure-ing.com/',
	);

	// ING - iDEAL Advanced (v3).
	$ing_ideal_advanced_v3 = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration();
	$ing_ideal_advanced_v3->set_id( 'ing-ideal-advanced-v3' );
	$ing_ideal_advanced_v3->set_name( 'ING - iDEAL Advanced (v3)' );
	$ing_ideal_advanced_v3->provider      = 'ing';
	$ing_ideal_advanced_v3->product_url   = 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/';
	$ing_ideal_advanced_v3->dashboard_url = array(
		'test' => 'https://idealtest.secure-ing.com/',
		'live' => 'https://ideal.secure-ing.com/',
	);

	// Mollie - iDEAL Basic.
	$mollie_ideal_basic = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration();
	$mollie_ideal_basic->set_id( 'mollie-ideal-basic' );
	$mollie_ideal_basic->set_name( 'Mollie - iDEAL Basic' );
	$mollie_ideal_basic->dashboard_url = 'http://www.mollie.nl/beheer/';
	$mollie_ideal_basic->provider      = 'mollie';
	$mollie_ideal_basic->deprecated    = true;

	// Paytor.
	$paytor = new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration();
	$paytor->set_id( 'paytor' );
	$paytor->set_name( 'Paytor' );
	$paytor->url         = 'http://paytor.com/';
	$paytor->product_url = 'http://paytor.com/';
	$paytor->provider    = 'paytor';

	// Postcode iDEAL.
	$postcode_ideal = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration();
	$postcode_ideal->set_id( 'postcode-ideal' );
	$postcode_ideal->set_name( 'Postcode iDEAL' );
	$postcode_ideal->provider      = 'postcode.nl';
	$postcode_ideal->product_url   = 'https://services.postcode.nl/ideal';
	$postcode_ideal->dashboard_url = 'https://services.postcode.nl/ideal';

	// Qantani (new platform).
	$qantani_mollie = new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration();
	$qantani_mollie->set_id( 'qantani-mollie' );
	$qantani_mollie->set_name( __( 'Qantani (new platform)', 'pronamic_ideal' ) );
	$qantani_mollie->url           = 'https://www.qantani.com/';
	$qantani_mollie->product_url   = 'https://www.qantani.com/tarieven/';
	$qantani_mollie->dashboard_url = 'https://www.qantani.eu/';
	$qantani_mollie->provider      = 'qantani';

	// Rabobank - iDEAL Professional (v3).
	$rabobank_ideal_professional_v3 = new \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Integration();
	$rabobank_ideal_professional_v3->set_id( 'rabobank-ideal-professional-v3' );
	$rabobank_ideal_professional_v3->set_name( 'Rabobank - iDEAL Professional (v3)' );
	$rabobank_ideal_professional_v3->provider      = 'rabobank';
	$rabobank_ideal_professional_v3->product_url   = 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/';
	$rabobank_ideal_professional_v3->dashboard_url = array(
		'test' => 'https://idealtest.rabobank.nl/',
		'live' => 'https://ideal.rabobank.nl/',
	);

	// Sisow - iDEAL Basic.
	$sisow_ideal_basic = new \Pronamic\WordPress\Pay\Gateways\IDealBasic\Integration();
	$sisow_ideal_basic->set_id( 'sisow-ideal-basic' );
	$sisow_ideal_basic->set_name( 'Sisow - iDEAL Basic' );
	$sisow_ideal_basic->url           = 'https://www.sisow.nl/';
	$sisow_ideal_basic->dashboard_url = 'https://www.sisow.nl/Sisow/iDeal/Login.aspx';
	$sisow_ideal_basic->provider      = 'sisow';
	$sisow_ideal_basic->deprecated    = true;

	return array(
		$abn_amro_ideal_easy,
		$abn_amro_ideal_only_kassa,
		$abn_amro_internetkassa,
		$abn_amro_ideal_zelfbouw_v3,
		new \Pronamic\WordPress\Pay\Gateways\Buckaroo\Integration(),
		$deutsche_bank_ideal_ogone,
		$deutsche_bank_ideal_expert_v3,
		new \Pronamic\WordPress\Pay\Gateways\EMS\ECommerce\Integration(),
		$fibonacci_orange,
		new \Pronamic\WordPress\Pay\Gateways\Icepay\Integration(),
		$ideal_simulator_ideal_basic,
		$ideal_simulator_ideal_advanced_v3,
		$ing_ideal_basic,
		$ing_ideal_advanced_v3,
		new \Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet\Integration(),
		new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration(),
		new \Pronamic\WordPress\Pay\Gateways\MollieIDeal\Integration(),
		$mollie_ideal_basic,
		new \Pronamic\WordPress\Pay\Gateways\MultiSafepay\Connect\Integration(),
		new \Pronamic\WordPress\Pay\Gateways\Nocks\Integration(),
		new \Pronamic\WordPress\Pay\Gateways\Ingenico\DirectLink\Integration(),
		new \Pronamic\WordPress\Pay\Gateways\Ingenico\OrderStandard\Integration(),
		new \Pronamic\WordPress\Pay\Gateways\OmniKassa\Integration(),
		new \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration(),
		new \Pronamic\WordPress\Pay\Gateways\PayNL\Integration(),
		$paytor,
		$postcode_ideal,
		$qantani_mollie,
		$rabobank_ideal_professional_v3,
		new \Pronamic\WordPress\Pay\Gateways\Sisow\Integration(),
		$sisow_ideal_basic,
		new \Pronamic\WordPress\Pay\Gateways\TargetPay\Integration(),
	);
}
