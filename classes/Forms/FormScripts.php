<?php
/**
 * Form Scripts
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Forms
 */

namespace Pronamic\WordPress\Pay\Forms;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Form Scripts
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class FormScripts {
	/**
	 * Plugin.
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initalize an form scripts object.
	 *
	 * @param Plugin $plugin Plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		add_action( 'wp_enqueue_scripts', array( $this, 'register' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Register.
	 */
	public function register() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style(
			'pronamic-pay-forms',
			plugins_url( 'css/forms' . $min . '.css', $this->plugin->get_file() ),
			array(),
			$this->plugin->get_version()
		);
	}

	/**
	 * Enqueue.
	 *
	 * @see https://mikejolley.com/2013/12/02/sensible-script-enqueuing-shortcodes/
	 * @see http://wordpress.stackexchange.com/questions/165754/enqueue-scripts-styles-when-shortcode-is-present
	 */
	public function enqueue() {
		if (
			has_shortcode( get_post_field( 'post_content' ), 'pronamic_payment_form' )
				||
			is_singular( 'pronamic_pay_form' )
		) {
			wp_enqueue_style( 'pronamic-pay-forms' );
		}
	}
}
