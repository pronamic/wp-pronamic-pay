<?php

class Pronamic_Gateways_Ogone_SecurityTest extends WP_UnitTestCase {
	function test_get_calculations_parameters_in() {
		$parameters = Pronamic_Gateways_Ogone_Security::get_calculations_parameters_in();
		
		$this->assertContains( Pronamic_Gateways_Ogone_Parameters::AMOUNT, $parameters );
	}

	function test_get_calculations_parameters_out() {
		$parameters = Pronamic_Gateways_Ogone_Security::get_calculations_parameters_out();
		
		$this->assertContains( Pronamic_Gateways_Ogone_Parameters::NC_ERROR, $parameters );
	}
}
