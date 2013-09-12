<?php

class Pronamic_WP_Pay_Configuration extends Pronamic_Pay_Configuration {
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

	/**
	 * Construct and initialize payment object
	 * 
	 * @param int $post_id
	 */
	public function __construct( $post_id ) {
		$this->id   = $post_id;
		$this->post = get_post( $post_id );
	}
}
