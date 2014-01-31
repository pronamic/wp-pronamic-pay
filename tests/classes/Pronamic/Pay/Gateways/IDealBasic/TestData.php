<?php

class Pronamic_Pay_Gateways_IDealBasic_TestData extends WP_UnitTestCase {
	function test_description() {
		// Alphanumerical
		$not_alphanumerical = '!@#$%ˆ&*()_+';

		$allowed_description =  'Example hashcode';
		$description = $allowed_description . $not_alphanumerical;

		$ideal_basic = new Pronamic_Gateways_IDealBasic_IDealBasic();
		$ideal_basic->setDescription( $description );
		
		$result   = $ideal_basic->get_description();
		$expected = $allowed_description;

		$this->assertEquals( $expected, $result );
	}
}
