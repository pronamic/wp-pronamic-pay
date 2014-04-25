<?php

class Pronamic_WP_Pay_Config {
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
		$this->gateway_id = get_post_meta( $post_id, '_pronamic_gateway_id', true );
	}
}
