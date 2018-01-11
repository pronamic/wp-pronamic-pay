<?php

namespace Pronamic\WordPress\Pay\Forms;

/**
 * Title: WordPress iDEAL shortcodes
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class FormShortcode {
	/**
	 * Constructs and initializes an post types object
	 */
	public function __construct() {
		add_shortcode( 'pronamic_payment_form', array( $this, 'shortcode_form' ) );

		add_action( 'init', array( $this, 'shortcode_ui_register' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Shortcode form
	 *
	 * @see https://github.com/WordImpress/Give/blob/1.1/includes/shortcodes.php#L39-L65
	 * @see https://github.com/WordImpress/Give/blob/1.1/includes/forms/template.php#L18-L140
	 */
	public function shortcode_form( $atts ) {
		$atts = shortcode_atts( array(
			'id' => null,
		), $atts, 'pronamic_payment_form' );

		$id = $atts['id'];

		return pronamic_pay_get_form( $id );
	}

	//////////////////////////////////////////////////

	/**
	 * Shortcode user interface register
	 */
	public function shortcode_ui_register() {
		if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
			return;
		}

		shortcode_ui_register_for_shortcode(
			'pronamic_payment_form',
			array(
				// Display label. String. Required.
				'label' => __( 'Payment Form', 'pronamic_ideal' ),

				// Icon/attachment for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
				'listItemImage' => 'dashicons-money',

				// Available shortcode attributes and default values. Required. Array.
				// Attribute model expects 'attr', 'type' and 'label'
				// Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
				'attrs' => array(

					array(
						'label'    => __( 'Select Payment Form', 'pronamic_ideal' ),
						'attr'     => 'id',
						'type'     => 'post_select',
						'query'    => array(
							'post_type' => 'pronamic_pay_form',
						),
						'multiple' => false,
					),

				),

			)
		);
	}
}
