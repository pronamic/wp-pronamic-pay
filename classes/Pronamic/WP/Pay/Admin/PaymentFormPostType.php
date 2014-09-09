<?php

/**
 * Title: WordPress admin payment form post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_Admin_PaymentFormPostType {
	public function __construct() {
		add_filter( 'manage_edit-pronamic_pay_gf_columns', array( $this, 'edit_columns' ) );

		add_action( 'manage_pronamic_pay_gf_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	public function edit_columns( $columns ) {
		$columns = array(
			'cb'                                      => '<input type="checkbox" />',
			'title'                                   => __( 'Title', 'pronamic_ideal' ),
			'pronamic_pay_gf_form'                    => __( 'Form', 'pronamic_ideal' ),
			'pronamic_pay_gf_config'                  => __( 'Configuration', 'pronamic_ideal' ),
			'pronamic_pay_gf_transaction_description' => __( 'Transaction Description', 'pronamic_ideal' ),
			'date'                                    => __( 'Date', 'pronamic_ideal' ),
		);

		return $columns;
	}

	public function custom_columns( $column, $post_id ) {
		global $post;

		switch ( $column ) {
			case 'pronamic_pay_gf_form':
				$form_id = get_post_meta( $post_id, '_pronamic_pay_gf_form_id', true );

				if ( ! empty( $form_id ) ) {
					printf(
						'<a href="%s">%s</a>',
						add_query_arg( array(
							'page' => 'gf_edit_forms',
							'id'   => $form_id,
						), admin_url( 'admin.php' ) ),
						get_pronamic_pay_gf_form_title( $form_id )
					);
				} else {
					echo '—';
				}

				break;
			case 'pronamic_pay_gf_config':
				$config_id = get_post_meta( $post_id, '_pronamic_pay_gf_config_id', true );

				if ( ! empty( $config_id ) ) {
					echo get_the_title( $config_id );
				} else {
					echo '—';
				}

				break;
			case 'pronamic_pay_gf_transaction_description':
				echo get_post_meta( $post_id, '_pronamic_pay_gf_transaction_description', true );

				break;
		}
	}

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'pronamic_pay_gf',
			__( 'Configuration', 'pronamic_ideal' ),
			array( $this, 'meta_box_config' ),
			'pronamic_pay_gf',
			'normal',
			'high'
		);
	}

	/**
	 * Pronamic Pay gateway config meta box
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_config( $post ) {
		include plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'admin/gravityforms/meta-box-config.php';
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
		if ( ! wp_verify_nonce( $nonce, 'pronamic_pay_save_pay_gf' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
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
			'_pronamic_pay_gf_form_id'                            => 'sanitize_text_field',
			'_pronamic_pay_gf_config_id'                          => 'sanitize_text_field',
			'_pronamic_pay_gf_entry_id_prefix'                    => 'sanitize_text_field',
			'_pronamic_pay_gf_transaction_description'            => 'sanitize_text_field',
			'_pronamic_pay_gf_condition_enabled'                  => FILTER_VALIDATE_BOOLEAN,
			'_pronamic_pay_gf_condition_field_id'                 => 'sanitize_text_field',
			'_pronamic_pay_gf_condition_operator'                 => 'sanitize_text_field',
			'_pronamic_pay_gf_condition_value'                    => 'sanitize_text_field',
			'_pronamic_pay_gf_delay_admin_notification'           => FILTER_VALIDATE_BOOLEAN,
			'_pronamic_pay_gf_delay_user_notification'            => FILTER_VALIDATE_BOOLEAN,
			'_pronamic_pay_gf_delay_notification_ids'             => array(
				'filter'    => FILTER_SANITIZE_STRING,
				'flags'     => FILTER_REQUIRE_ARRAY,
			),
			'_pronamic_pay_gf_delay_post_creation'                => FILTER_VALIDATE_BOOLEAN,
			'_pronamic_pay_gf_fields'                             => array(
				'filter'    => FILTER_SANITIZE_STRING,
				'flags'     => FILTER_REQUIRE_ARRAY,
			),
			'_pronamic_pay_gf_links' => array(
				'filter'    => FILTER_SANITIZE_STRING,
				'flags'     => FILTER_REQUIRE_ARRAY,
				),
			'_pronamic_pay_gf_user_role_field_id'                 => 'sanitize_text_field',
		);

		if ( class_exists( 'GFAWeber' ) ) {
			$definition['_pronamic_pay_gf_delay_aweber_subscription'] = FILTER_VALIDATE_BOOLEAN;
		}

		if ( class_exists( 'GFCampaignMonitor' ) ) {
			$definition['_pronamic_pay_gf_delay_campaignmonitor_subscription'] = FILTER_VALIDATE_BOOLEAN;
		}

		if ( class_exists( 'GFMailChimp' ) ) {
			$definition['_pronamic_pay_gf_delay_mailchimp_subscription'] = FILTER_VALIDATE_BOOLEAN;
		}

		if ( class_exists( 'GFUser' ) ) {
			$definition['_pronamic_pay_gf_delay_user_registration'] = FILTER_VALIDATE_BOOLEAN;
		}

		foreach ( $definition as $meta_key => $function ) {
			$meta_value = null;

			if ( 'sanitize_text_field' == $function ) {
				if ( isset( $_POST[ $meta_key ] ) ) {
					$meta_value = sanitize_text_field( $_POST[ $meta_key ] );
				}
			} else {
				$filter  = $function;
				$options = null;

				if ( is_array( $function ) && isset( $function['filter'] ) ) {
					$filter  = $function['filter'];
					$options = $function;
				}

				$meta_value = filter_input( INPUT_POST, $meta_key, $filter, $options );
			}

			if ( isset( $meta_value ) && '' != $meta_value ) {
				update_post_meta( $post_id, $meta_key, $meta_value );
			} else {
				delete_post_meta( $post_id, $meta_key );
			}
		}
	}
}
