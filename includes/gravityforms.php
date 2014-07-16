<?php

function get_pronamic_gf_pay_feed_by_form_id( $form_id ) {
	global $wpdb;

	$pay_gf = null;

	$db_query = $wpdb->prepare( "
		SELECT
			ID
		FROM
			$wpdb->posts
				LEFT JOIN
			$wpdb->postmeta
					ON (
				ID = post_ID
					AND
				meta_key = '_pronamic_pay_gf_form_id'
			)
		WHERE
			post_status = 'publish'
				AND
			post_type = 'pronamic_pay_gf'
				AND
			meta_value = %s
		;
	", $form_id );

	$post_id = $wpdb->get_var( $db_query );

	if ( $post_id ) {
		$pay_gf = new Pronamic_GravityForms_PayFeed( $post_id );
	}

	return $pay_gf;
}

function get_pronamic_pay_gf_form_title( $form_id ) {
	$title = null;

	global $pronamic_pay_gf_form_titles;

	if ( ! isset( $pronamic_pay_gf_form_titles ) ) {
		global $wpdb;

		$form_table_name = RGFormsModel::get_form_table_name();

		$query = "SELECT id, title FROM $form_table_name WHERE is_active;";

		$pronamic_pay_gf_form_titles = $wpdb->get_results( $query, OBJECT_K );
	}

	if ( isset( $pronamic_pay_gf_form_titles[ $form_id ] ) ) {
		$title = $pronamic_pay_gf_form_titles[ $form_id ]->title;
	}

	return $title;
}
