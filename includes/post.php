<?php

function pronamic_pay_get_form( $id ) {
	$file = plugin_dir_path( Pronamic_WP_Pay_Plugin::$file ) . 'templates/form.php';

	ob_start();

	include $file;

	$output = ob_get_clean();

	return $output;
}

/**
 * Helper function to update post meta data
 *
 * @see http://codex.wordpress.org/Function_Reference/update_post_meta
 * @param int $post_id
 * @param array $data
 */
function pronamic_pay_update_post_meta_data( $post_id, array $data ) {
	/*
	 * Post meta values are passed through the stripslashes() function
	 * upon being stored, so you will need to be careful when passing
	 * in values such as JSON that might include \ escaped characters.
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
