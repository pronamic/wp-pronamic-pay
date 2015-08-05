<?php


/**
 * Shortcode form
 *
 * @see https://github.com/WordImpress/Give/blob/1.1/includes/shortcodes.php#L39-L65
 * @see https://github.com/WordImpress/Give/blob/1.1/includes/forms/template.php#L18-L140
 */
function pronamic_pay_shortcode_form( $atts ) {
	$atts = shortcode_atts( array(
		'id' => null,
	), $atts, 'pronamic_payment_form' );

	$id = $atts['id'];

	return pronamic_pay_get_form( $id );
}

add_shortcode( 'pronamic_payment_form', 'pronamic_pay_shortcode_form' );


add_action( 'init', function() {

	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		add_action( 'admin_notices', function(){
			if ( current_user_can( 'activate_plugins' ) ) {
				echo '<div class="error message"><p>Shortcode UI plugin must be active for Shortcode UI Example plugin to function.</p></div>';
			}
		});
		return;
	}

	/**
	 * Register a UI for the Shortcode.
	 * Pass the shortcode tag (string)
	 * and an array or args.
	 */
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
					'query'    => array( 'post_type' => 'pronamic_pay_form' ),
					'multiple' => false,
				),

			),

		)
	);

} );

function pronamic_pay_get_form( $id ) {
	$file = plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'templates/form.php';

	ob_start();

	include $file;

	$output = ob_get_clean();

	return $output;
}

/**
 * Pay form content
 */
function pronamic_pay_form_the_content( $content ) {
	if ( is_singular( 'pronamic_pay_form' ) && 'pronamic_pay_form' == get_post_type() ) {
		$content .= pronamic_pay_get_form( get_the_ID() );
	}

	return $content;
}

add_filter( 'the_content', 'pronamic_pay_form_the_content' );

/**
 * Helper function to update post meta data
 *
 * @param int $post_id
 * @param array $data
 */
function pronamic_pay_update_post_meta_data( $post_id, array $data ) {
	/*
	 * Post meta values are passed through the stripslashes() function
	 * upon being stored, so you will need to be careful when passing
	 * in values (such as JSON) that might include \ escaped characters.
	 *
	 * @see http://codex.wordpress.org/Function_Reference/update_post_meta
	 */
	$data = wp_slash( $data );

	// Meta
	foreach ( $data as $key => $value ) {
		if ( isset( $value ) && '' !== $value ) {
			update_post_meta( $post_id, $key, $value );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}
}
