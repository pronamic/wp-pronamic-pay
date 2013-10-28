<?php

class Pronamic_Pay_TestGatewayFactory extends WP_UnitTestCase {
	function test_gateway_factory() {
		Pronamic_Pay_GatewayFactory::register( 'Pronamic_Gateways_Buckaroo_Config', 'Pronamic_Gateways_Buckaroo_Gateway' );
		
		$config = new Pronamic_Gateways_Buckaroo_Config();
		
		$gateway = Pronamic_Pay_GatewayFactory::create( $config );

		$this->assertInstanceOf( 'Pronamic_Gateways_Buckaroo_Gateway', $gateway );
	}
}
