<?php

/**
 * Title: WordPress admin gateway post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Pronamic_WP_Pay_Admin_FormPostType {
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'save_post' ) );

		add_action( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ) );
	}

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'pronamic_payment_form_options',
			__( 'Form Options', 'pronamic_ideal' ),
			array( $this, 'meta_box_form_options' ),
			'pronamic_pay_form',
			'normal',
			'high'
		);
	}

	/**
	 * Pronamic Pay gateway config meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_form_options( $post ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/admin/meta-box-form-options.php';
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_post( $post_id ) {
		// Check if our nonce is set.
		if ( ! filter_has_var( INPUT_POST, 'pronamic_pay_nonce' ) ) {
			return $post_id;
		}

		$nonce = filter_input( INPUT_POST, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING );

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'pronamic_pay_save_form_options' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' === get_post_type( $post_id ) ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, its safe for us to save the data now. */
		$definition = array(
			// General
			'_pronamic_pay_form_config_id' => FILTER_SANITIZE_STRING,
			'_pronamic_pay_button_text'    => FILTER_SANITIZE_STRING,
		);

		$data = filter_input_array( INPUT_POST, $definition );

		// Update post meta data
		pronamic_pay_update_post_meta_data( $post_id, $data );
	}

	//////////////////////////////////////////////////

	public function post_submitbox_misc_actions() {
		if ( 'pronamic_pay_form' !== get_post_type() ) {
			return false;
		}

		// Shortcode column with select all input
		$shortcode = sprintf( '[pronamic_pay_form id="%s"]', esc_attr( get_the_ID() ) );

		?>
<div class="shortcode-wrap box-sizing">
	<label><?php esc_html_e( 'Shortcode:', 'pronamic_ideal' ); ?></label>

	<input onClick="this.setSelectionRange( 0, this.value.length )" type="text" class="shortcode-input" readonly value="<?php echo esc_attr( $shortcode ); ?>">
</div><?php
	}
}
