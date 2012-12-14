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
		// Filters
		add_filter( 'gform_addon_navigation',   array( __CLASS__, 'addon_navigation' ) );

		add_filter( 'gform_entry_info',         array( __CLASS__, 'entry_info' ), 10, 3 );

		add_filter( 'gform_custom_merge_tags',  array( __CLASS__, 'custom_merge_tags' ), 10 );

		// Actions
		add_action( 'admin_init',               array( __CLASS__, 'maybe_redirect_to_entry' ) );

		// Actions - AJAX
		add_action( 'wp_ajax_gf_get_form_data', array( __CLASS__, 'ajax_get_form_data' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Create the admin menu
	 */
	public static function addon_navigation( $menus ) {
		$menus[] = array(
			'name'       => 'gf_pronamic_ideal',
			'label'      => __( 'iDEAL', 'pronamic_ideal' ),
			'callback'   =>  array( __CLASS__, 'page' ),
			'permission' => 'gravityforms_ideal'
		);

        return $menus;
	}

	//////////////////////////////////////////////////

	/**
	 * Render entry info of the specified form and lead
	 * 
	 * @param string $form_id
	 * @param array $lead
	 * @todo check third parameter
	 */
	public static function entry_info( $form_id, $lead ) {
		if ( false ):

			_e( 'iDEAL', 'pronamic_ideal' ); ?>: 
			<a href="#" target="_blank">transaction 1</a>
			<br /><br /><?php
		
		endif;
	}

	//////////////////////////////////////////////////

	/**
	 * Custom merge tags
	 */
	public static function custom_merge_tags( $merge_tags ) {
		$merge_tags[] = array(
			'label' => __( 'Payment Status', 'pronamic_ideal' ), 
			'tag'   => '{payment_status}'
		);

		$merge_tags[] = array(
			'label' => __( 'Payment Date', 'pronamic_ideal' ),
			'tag'   => '{payment_date}'
		);

		$merge_tags[] = array(
			'label' => __( 'Transaction Id', 'pronamic_ideal' ),
			'tag'   => '{transaction_id}'
		);

		$merge_tags[] = array(
			'label' => __( 'Payment Amount', 'pronamic_ideal' ),
			'tag'   => '{payment_amount}'
		);

		return $merge_tags;
	}

	//////////////////////////////////////////////////

	/**
	 * Page
	 */
	public static function page() {
		$entry_id = filter_input( INPUT_GET, 'lid', FILTER_SANITIZE_STRING );

		if ( ! empty( $entry_id ) ) {
			
		}

		$view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRING );
		
		switch( $view ) {
			case 'edit':
				return self::page_feed_edit();
			default:
				return self::page_feeds();
		}
	}
	
	/**
	 * Page list
	 */
	public static function page_feeds() {
		return Pronamic_WordPress_IDeal_Admin::renderView( 'gravityforms/feeds' );
	}
	
	/**
	 * Page edit
	 */
	public static function page_feed_edit() {
		return Pronamic_WordPress_IDeal_Admin::renderView( 'gravityforms/feed-edit' );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the edit link for the specfied feed id
	 * 
	 * @param string $id
	 * @return string url
	 */
	public static function get_edit_feed_link( $id = null ) {
		$link = 'admin.php';
		$link = add_query_arg( 'page', 'gf_pronamic_ideal', $link );
		$link = add_query_arg( 'view', 'edit', $link );

		if ( $id != null ) {
			$link = add_query_arg( 'id', $id, $link );
		}

		return $link;
	}

	/**
	 * Get the delete link for the specfied feed id
	 * 
	 * @param string $id
	 * @return string url
	 */
	public static function get_delete_feed_link( $id = null ) {
		$link = 'admin.php';
		$link = add_query_arg( 'page', 'gf_pronamic_ideal', $link );
		$link = add_query_arg( 'action', 'delete', $link );

		if ( $id != null ) {
			$link = add_query_arg( 'id', $id, $link );
		}

		return $link;
	}

	//////////////////////////////////////////////////

	/**
	 * Maybed redirect to Gravity Forms entry
	 */
	public static function maybe_redirect_to_entry() {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( $page == 'gf_pronamic_ideal' ) {
			$lead_id = filter_input( INPUT_GET, 'lid', FILTER_SANITIZE_STRING );

			if ( ! empty( $lead_id ) ) {
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
	}

	//////////////////////////////////////////////////
	
	/**
	 * Handle AJAX request get form data
	 */
	public static function ajax_get_form_data() {
		$formId = filter_input( INPUT_GET, 'formId', FILTER_SANITIZE_STRING );
		
		$result = new stdClass();
		$result->success = true;
		$result->data    = RGFormsModel::get_form_meta( $formId );

		// Output
		header( 'Content-Type: application/json' );

		echo json_encode( $result );

		die();
	}
}
