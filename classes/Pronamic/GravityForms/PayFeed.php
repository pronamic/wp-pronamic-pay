<?php

class Pronamic_GravityForms_PayFeed {
	/**
	 * The payment (post) ID.
	 * 
	 * @var int
	 */
	public $id;

	/**
	 * The payment post object
	 */
	public $post;

	//////////////////////////////////////////////////

	/**
	 * Construct and initialize payment object
	 * 
	 * @param int $post_id
	 */
	public function __construct( $post_id ) {
		$this->id   = $post_id;
		$this->post = get_post( $post_id );

		// Load
		$this->config_id               = get_post_meta( $post_id, '_pronamic_pay_gf_config_id', true );

		$this->transaction_description = get_post_meta( $post_id, '_pronamic_pay_gf_transaction_description', true );

		$this->condition_field_id      = get_post_meta( $post_id, '_pronamic_pay_gf_condition_field_id', true );
		$this->condition_operator      = get_post_meta( $post_id, '_pronamic_pay_gf_condition_operator', true );
		$this->condition_value         = get_post_meta( $post_id, '_pronamic_pay_gf_condition_value', true );
		
		$ids = get_post_meta( $post_id, '_pronamic_pay_gf_delay_notification_ids', true );
		$this->delay_notification_ids  = is_array( $ids ) ? $ids : array();

		$fields = get_post_meta( $post_id, '_pronamic_pay_gf_fields', true );
		$this->fields                  = is_array( $fields ) ? $fields : array();

		$this->user_role_field_id      = get_post_meta( $post_id, '_pronamic_pay_gf_user_role_field_id', true );
	}
}
