<?php

namespace Pronamic\WordPress\Pay\Forms;

/**
 * Title: WordPress iDEAL form scripts
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class FormScripts {
	/**
	 * Constructs and initalize an form scripts object.
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
			plugins_url( 'css/forms' . $min . '.css', \Pronamic_WP_Pay_Plugin::$file ),
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
		$post = get_post();

		if (
			is_object( $post ) && has_shortcode( $post->post_content, 'pronamic_payment_form' )
				||
			is_singular( 'pronamic_pay_form' )
		) {
			wp_enqueue_style( 'pronamic-pay-forms' );
		}
	}
}
