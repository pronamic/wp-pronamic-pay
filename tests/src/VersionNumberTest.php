<?php
/**
 * Version number test
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

use WP_UnitTestCase;

/**
 * Version number test
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class VersionNumberTest extends WP_UnitTestCase {
	/**
	 * Setup version number test.
	 */
	public function setUp() {
		parent::setUp();

		$this->plugin_dir = realpath( __DIR__ . '/../..' );

		$this->package_file = $this->plugin_dir . '/package.json';
		$this->readme_file  = $this->plugin_dir . '/readme.txt';
		$this->plugin_file  = $this->plugin_dir . '/pronamic-ideal.php';

		$this->package = json_decode( file_get_contents( $this->package_file ) );
	}

	/**
	 * Test readme.txt file for stable tag version number.
	 */
	public function test_readme_txt() {
		$readme_txt_lines = file( $this->readme_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );

		$search_string = sprintf( 'Stable tag: %s', $this->package->version );

		$this->assertContains( $search_string, $readme_txt_lines );
	}

	/**
	 * Test plugin header for version number.
	 */
	public function test_plugin_header() {
		$data = get_plugin_data( $this->plugin_file );

		$this->assertEquals( $this->package->version, $data['Version'] );
	}

	/**
	 * Test plugin object for version number.
	 */
	public function test_plugin_version() {
		$this->assertEquals( $this->package->version, pronamic_pay_plugin()->get_version() );
	}
}
