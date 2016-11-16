<?php

/**
 * Title: WordPress gateway post type
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.8.0
 * @since ?
 */
class Pronamic_WP_Pay_GatewayPostType {
	/**
	 * Post type
	 */
	const POST_TYPE = 'pronamic_gateway';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes a gateway post type object
	 */
	public function __construct() {
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'maybe_set_default_gateway' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe set the default gateway.
	 *
	 * @param int $post_id
	 */
	public function maybe_set_default_gateway( $post_id ) {
		// Don't set the default gateway if the post is not published.
		if ( 'publish' !== get_post_status( $post_id ) ) {
			return;
		}

		// Don't set the default gateway if there is already a published gateway set.
		$config_id = get_option( 'pronamic_pay_config_id' );

		if ( ! empty( $config_id ) && 'publish' === get_post_status( $config_id ) ) {
			return;
		}

		// Update
		update_option( 'pronamic_pay_config_id', $post_id );
	}
}
