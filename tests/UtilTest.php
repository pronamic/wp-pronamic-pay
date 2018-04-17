<?php

namespace Pronamic\WordPress\Pay;

use stdClass;
use WP_UnitTestCase;

/**
 * Title: WordPress utility test
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class UtilTest extends WP_UnitTestCase {
	public function test_to_string_boolean() {
		$boolean = false;

		$result = Util::boolean_to_string( $boolean );

		$this->assertEquals( 'false', $result );
	}

	/**
	 * Test format price.
	 *
	 * @dataProvider format_price_matrix_provider
	 */
	public function test_format_price( $amount, $currency, $expected ) {
		$formatted = Util::format_price( $amount );

		$this->assertEquals( $expected, $formatted );
	}

	public function format_price_matrix_provider() {
		return array(
			array( '', null, null ),
			array( false, null, null ),
			array( array(), null, null ),
			array( new stdClass(), null, null ),
			array( 0, null, '€ 0,00' ),
			array( 123, null, '€ 123,00' ),
			array( 123456, null, '€ 123.456,00' ),
		);
	}
}
