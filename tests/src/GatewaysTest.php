<?php
/**
 * Gateways test.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

/**
 * Gateways test.
 *
 * @author Reüel van der Steege
 * @version 1.0.0
 */
namespace Pronamic\WordPress\Pay;

class GatewaysTest extends \PHPUnit_Framework_TestCase {
	/**
	 * Test for gateway integrations array.
	 */
	public function test_gateway_integrations_type() {
		$integrations = pronamic_pay_gateway_integrations();

		$is_array = is_array( $integrations );

		$this->assertTrue( $is_array );
	}

	/**
	 * Test for valid gateway integrations.
	 *
	 * @param string|array $gateway Fully qualified gateway class name or array with class name and callback.
	 *
	 * @dataProvider gateways_provider
	 * @depends test_gateway_integrations_type
	 */
	public function test_valid_gateway_integrations( $gateway ) {
		// Assert type.
		$valid_types = array( 'string', 'array' );

		$this->assertContains( gettype( $gateway ), $valid_types );

		// Assert required array keys and callback closure.
		if ( is_array( $gateway ) ) {
			$this->assertArrayHasKey( 'class', $gateway );
			$this->assertArrayHasKey( 'callback', $gateway );

			$actual = ( isset( $gateway['callback'] ) ? $gateway['callback'] : null );

			$this->assertInstanceOf( 'Closure', $actual );
		}
	}

	/**
	 * Test gateway integrations callbacks.
	 *
	 * @param string|array $gateway Fully qualified gateway class name or array with class name and callback.
	 *
	 * @dataProvider gateways_provider
	 * @depends test_gateway_integrations_type
	 */
	public function test_gateway_integrations_callback( $gateway ) {
		if ( ! is_array( $gateway ) ) {
			return;
		}

		if ( ! isset( $gateway['class'], $gateway['callback'] ) ) {
			return;
		}

		$integration = new $gateway['class']();

		$this->assertInstanceOf( __NAMESPACE__ . '\Gateways\Common\AbstractIntegration', $integration );

		call_user_func( $gateway['callback'], $integration );

		$this->assertNotEmpty( $integration->get_id() );
		$this->assertNotEmpty( $integration->get_name() );
	}

	/**
	 * Gateways data provider.
	 *
	 * @return array
	 */
	public function gateways_provider() {
		$data = array();

		$gateways = pronamic_pay_gateway_integrations();

		foreach ( $gateways as $gateway ) {
			$data[] = array( $gateway );
		}

		return $data;
	}
}
