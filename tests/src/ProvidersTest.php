<?php
/**
 * Providers test.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

/**
 * Plugin payment providers test.
 *
 * @author  Reüel van der Steege
 * @version 5.4.0
 * @since   5.4.0
 */
class ProvidersTest extends \PHPUnit_Framework_TestCase {
	/**
	 * Setup.
	 */
	public function setUp() {
		require plugin_dir_path( Plugin::$file ) . 'includes/providers.php';
	}

	/**
	 * Test for providers array.
	 */
	public function test_providers_type() {
		global $pronamic_pay_providers;

		$this->assertInternalType( 'array', $pronamic_pay_providers );
	}

	/**
	 * Test for valid providers.
	 *
	 * @param array $provider Provider.
	 *
	 * @dataProvider providers_data_provider
	 * @depends test_providers_type
	 */
	public function test_valid_providers( $provider ) {
		// Assert type.
		$this->assertInternalType( 'array', $provider );

		// Assert requires array keys.
		if ( is_array( $provider ) ) {
			$this->assertArrayHasKey( 'name', $provider );
			$this->assertArrayHasKey( 'url', $provider );
		}
	}

	/**
	 * Providers data matrix.
	 *
	 * @return array
	 */
	public function providers_data_provider() {
		global $pronamic_pay_providers;

		$providers = array();

		if ( is_array( $pronamic_pay_providers ) ) {
			foreach ( $pronamic_pay_providers as $provider ) {
				$providers[] = array( $provider );
			}
		}

		return $providers;
	}
}
