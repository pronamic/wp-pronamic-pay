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
	/**
	 * Post type
	 */
	const POST_TYPE = 'pronamic_pay_form';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an admin form post type object
	 */
	public function __construct() {
		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', array( $this, 'edit_columns' ) );

		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_post' ) );

		add_action( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ) );
	}

	//////////////////////////////////////////////////

	public function edit_columns( $columns ) {
		$columns = array(
			'cb'                              => '<input type="checkbox" />',
			'title'                           => __( 'Title', 'pronamic_ideal' ),
			'pronamic_payment_form_gateway'   => __( 'Gateway', 'pronamic_ideal' ),
			'pronamic_payment_form_shortcode' => __( 'Shortcode', 'pronamic_ideal' ),
			'date'                            => __( 'Date', 'pronamic_ideal' ),
		);

		return $columns;
	}

	public function custom_columns( $column, $post_id ) {
		global $post;

		switch ( $column ) {
			case 'pronamic_payment_form_gateway':
				$config_id = get_post_meta( $post_id, '_pronamic_pay_form_config_id', true );

				if ( ! empty( $config_id ) ) {
					echo get_the_title( $config_id );
				} else {
					echo '—';
				}

				break;
			case 'pronamic_payment_form_shortcode':
				printf(
					'<input onclick="this.setSelectionRange( 0, this.value.length )" type="text" class="pronamic-pay-shortcode-input" readonly="" value="%s" />',
					esc_attr( $this->get_shortcode( $post_id ) )
				);

				break;
		}
	}

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes( $post_type ) {
		if ( self::POST_TYPE === $post_type ) {
			add_meta_box(
				'pronamic_payment_form_options',
				__( 'Form Options', 'pronamic_ideal' ),
				array( $this, 'meta_box_form_options' ),
				$post_type,
				'normal',
				'high'
			);
		}
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

		/* OK, its safe for us to save the data now. */
		$definition = array(
			// General
			'_pronamic_payment_form_config_id'   => FILTER_SANITIZE_STRING,
			'_pronamic_payment_form_button_text' => FILTER_SANITIZE_STRING,
		);

		$data = filter_input_array( INPUT_POST, $definition );

		// Update post meta data
		pronamic_pay_update_post_meta_data( $post_id, $data );
	}
	//////////////////////////////////////////////////

	private function get_shortcode( $post_id ) {
		$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

		$shortcode = sprintf( '[pronamic_pay_form id="%s"]', esc_attr( $post_id ) );

		return $shortcode;
	}

	//////////////////////////////////////////////////

	public function post_submitbox_misc_actions() {
		if ( self::POST_TYPE !== get_post_type() ) {
			return false;
		}

		?>
<div class="shortcode-wrap box-sizing">
	<label><?php esc_html_e( 'Shortcode:', 'pronamic_ideal' ); ?></label>

	<input onClick="this.setSelectionRange( 0, this.value.length )" type="text" class="shortcode-input" readonly value="<?php echo esc_attr( $this->get_shortcode() ); ?>">
</div><?php
	}
}
