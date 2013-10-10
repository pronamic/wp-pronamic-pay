<?php

function get_pronamic_pay_gf_by_form_id( $form_id ) {
	global $wpdb;
	
	$pay_gf = null;

	$db_query = $wpdb->prepare( "
		SELECT
			post_id
		FROM
			$wpdb->postmeta
		WHERE
			meta_key = '_pronamic_pay_gf_form_id'
				AND
			meta_value = %s
			;
	", $form_id );

	$post_id = $wpdb->get_var( $db_query );
	
	if ( $post_id ) {
		$pay_gf = get_post( $post_id );
	}
	
	return $pay_gf;
}
