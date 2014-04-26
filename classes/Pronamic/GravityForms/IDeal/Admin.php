<?php

/**
 * Title: Gravity Forms iDEAL Add-On admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_GravityForms_IDeal_Admin {
	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		// Actions
		add_action( 'admin_init',               array( __CLASS__, 'maybe_redirect_to_entry' ) );

		// Filters
		add_filter( 'gform_addon_navigation',   array( __CLASS__, 'addon_navigation' ) );

		add_filter( 'gform_entry_info',         array( __CLASS__, 'entry_info' ), 10, 2 );

		add_filter( 'gform_custom_merge_tags',  array( __CLASS__, 'custom_merge_tags' ), 10 );

		// Actions - AJAX
		add_action( 'wp_ajax_gf_get_form_data', array( __CLASS__, 'ajax_get_form_data' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Gravity Forms addon navigation
	 *
	 * @param $menus array with addon menu items
	 * @return array
	 */
	public static function addon_navigation( $menus ) {
		$menus[] = array(
			'name'       => 'edit.php?post_type=pronamic_pay_gf',
			'label'      => __( 'iDEAL', 'pronamic_ideal' ),
			'callback'   => null,
			'permission' => 'gravityforms_ideal',
		);

		return $menus;
	}

	//////////////////////////////////////////////////

	/**
	 * Render entry info of the specified form and lead
	 *
	 * @param string $form_id
	 * @param array $lead
	 */
	public static function entry_info( $form_id, $lead ) {
		$payment_id = gform_get_meta( $lead['id'], 'pronamic_payment_id' );

		if ( $payment_id ) {
			printf(
				'<a href="%s">%s</a>',
				get_edit_post_link( $payment_id ),
				get_the_title( $payment_id )
			);
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Custom merge tags
	 */
	public static function custom_merge_tags( $merge_tags ) {
		$merge_tags[] = array(
			'label' => __( 'Payment Status', 'pronamic_ideal' ),
			'tag'   => '{payment_status}',
		);

		$merge_tags[] = array(
			'label' => __( 'Payment Date', 'pronamic_ideal' ),
			'tag'   => '{payment_date}',
		);

		$merge_tags[] = array(
			'label' => __( 'Transaction Id', 'pronamic_ideal' ),
			'tag'   => '{transaction_id}',
		);

		$merge_tags[] = array(
			'label' => __( 'Payment Amount', 'pronamic_ideal' ),
			'tag'   => '{payment_amount}',
		);

		return $merge_tags;
	}

	//////////////////////////////////////////////////

	/**
	 * Maybed redirect to Gravity Forms entry
	 */
	public static function maybe_redirect_to_entry() {
		if ( filter_has_var( INPUT_GET, 'pronamic_gf_lid' ) ) {
			$lead_id = filter_input( INPUT_GET, 'pronamic_gf_lid', FILTER_SANITIZE_STRING );

			$lead = RGFormsModel::get_lead( $lead_id );

			if ( ! empty($lead ) ) {
				$url = add_query_arg( array(
					'page' => 'gf_entries',
					'view' => 'entry',
					'id'   => $lead['form_id'],
					'lid'  => $lead_id,
				),  admin_url('admin.php') );

				wp_redirect( $url, 303 );

				exit;
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Handle AJAX request get form data
	 */
	public static function ajax_get_form_data() {
		$form_id = filter_input( INPUT_GET, 'formId', FILTER_SANITIZE_STRING );

		$result = new stdClass();
		$result->success = true;
		$result->data    = RGFormsModel::get_form_meta( $form_id );

		// Output
		header( 'Content-Type: application/json' );

		echo json_encode( $result );

		die();
	}
}
