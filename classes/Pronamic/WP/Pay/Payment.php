<?php

class Pronamic_WP_Pay_Payment extends Pronamic_Pay_Payment {
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
		$this->source    = get_post_meta( $post_id, '_pronamic_payment_source', true );
		$this->source_id = get_post_meta( $post_id, '_pronamic_payment_source_id', true );
	}

	//////////////////////////////////////////////////

	public function get_transaction_id() {
		return get_post_meta( $this->id, '_pronamic_payment_transaction_id', true );
	}

	//////////////////////////////////////////////////

	/**
	 * Source text
	 * 
	 * @return string
	 */
	public function get_source_text() {
		$text = $this->source . '<br />' . $this->source_id;
			
		$text = apply_filters( 'pronamic_payment_source_text_' . $this->source, $text, $this );
		$text = apply_filters( 'pronamic_payment_source_text', $text, $this );
	
		return $text;
	}
}
