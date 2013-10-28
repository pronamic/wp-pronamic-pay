<?php

/**
 * Title: WP e-Commerce iDEAL merchant
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WPeCommerce_IDeal_IDealMerchant extends wpsc_merchant {
	/**
	 * Construct and initialize an Pronamic iDEAL merchant class
	 */
	public function __construct( $purchase_id = null, $is_receiving = false ) {
		parent::__construct( $purchase_id, $is_receiving );

		$this->name = __( 'Pronamic iDEAL', 'pronamic_ideal' );
	}

	//////////////////////////////////////////////////

	/**
	 * Construct value array specific data array
	 */
	public function construct_value_array() {
		// No specific data for this merchant
		return array( );
	}

	/**
	 * Submit to gateway
	 */
	public function submit() {
		$config_id = get_option( Pronamic_WPeCommerce_IDeal_AddOn::OPTION_CONFIG_ID );

		// Set process to 'order_received' (2)
		// @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-includes/merchant.class.php#L301
		// @see http://plugins.trac.wordpress.org/browser/wp-e-commerce/tags/3.8.7.6.2/wpsc-core/wpsc-functions.php#L115
		$this->set_purchase_processed_by_purchid( Pronamic_WPeCommerce_WPeCommerce::PURCHASE_STATUS_ORDER_RECEIVED );

		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );

		if ( $gateway ) {
			$data = new Pronamic_WP_Pay_WPeCommerce_PaymentData( $this );

			$payment = Pronamic_WordPress_IDeal_IDeal::start( $config_id, $gateway, $data );

			update_post_meta( $payment->get_id(), '_pronamic_payment_wpsc_purchase_id', $data->get_purchase_id() );
			update_post_meta( $payment->get_id(), '_pronamic_payment_wpsc_session_id', $data->get_session_id() );

			$error = $gateway->get_error();
			
			if ( is_wp_error( $error ) ) {
				// @todo what todo?
			} else {
				$gateway->redirect( $payment );
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Admin configuration form
	 */
	public static function admin_config_form() {
		$html = '';

		$html .= '<tr>';
		$html .= '	<td class="wpsc_CC_details">';
		$html .= '		' . __( 'Configuration', 'pronamic_ideal' );
		$html .= '	</td>';
		$html .= '	<td>';
		$html .= Pronamic_WordPress_IDeal_Admin::dropdown_configs( array( 
			'name' => Pronamic_WPeCommerce_IDeal_AddOn::OPTION_CONFIG_ID,
			'echo' => false
		) );
		$html .= '	</td>';
		$html .= '</tr>';

		return $html;
	}

	/**
	 * Admin config submit
	 */
	public static function admin_config_submit() {
		$name = Pronamic_WPeCommerce_IDeal_AddOn::OPTION_CONFIG_ID;

		if ( filter_has_var( INPUT_POST, $name ) ) {
			$config_id = filter_input( INPUT_POST, $name, FILTER_SANITIZE_STRING );

			update_option( $name, $config_id );
		}

		return true;
	}
}
