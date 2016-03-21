<?php

/**
 * Title: WordPress utility test
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_UtilTest extends PHPUnit_Framework_TestCase {
	public function test_to_string_boolean() {
		$boolean = false;

		$result = Pronamic_WP_Util::to_string_boolean( $boolean );

		 $this->assertEquals( 'false', $result );
	}
}
