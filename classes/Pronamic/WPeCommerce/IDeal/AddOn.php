<?php

/**
 * Title: WP eCommerce IDeal Addon
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WPeCommerce_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'wp-e-commerce';

	//////////////////////////////////////////////////
	
	/**
	 * Bootstrap
	 */
	public static function bootstrap(){
		// Add gateway to gateways
		add_filter( 'wpsc_merchants_modules',               array( __CLASS__, 'merchants_modules' ) );

		$slug = self::SLUG;

		// Update payment status when returned from iDEAL
		add_action( "pronamic_payment_status_update_$slug", array( __CLASS__, 'status_update' ), 10, 2 );

		// Source Column
		add_filter( "pronamic_payment_source_text_$slug",   array( __CLASS__, 'source_text' ), 10, 2 );
	}

	//////////////////////////////////////////////////
	
	/**
	 * Merchants modules
	 * 
	 * @param array $gateways
	 */
	public static function merchants_modules( $gateways ) {
		global $nzshpcrt_gateways, $num, $wpsc_gateways, $gateway_checkout_form_fields;

		$gateways[] = array(
			'name'                   => __( 'Pronamic iDEAL', 'pronamic_ideal' ),
			'api_version'            => 2.0,
			'image'                  => plugins_url( '/images/icon-32x32.png', Pronamic_WordPress_IDeal_Plugin::$file ),
			'class_name'             => 'Pronamic_WPeCommerce_IDeal_IDealMerchant',
			'has_recurring_billing'  => false,
			'wp_admin_cannot_cancel' => false,
			'display_name'           => __( 'iDEAL', 'pronamic_ideal' ),
			'requirements'           => array(
				'php_version'   => 5.0, 
				'extra_modules' => array()
			) ,
			'form'                   => 'pronamic_ideal_wpsc_merchant_form', 
			'submit_function'        => 'pronamic_ideal_wpsc_merchant_submit_function', 
			// this may be legacy, not yet decided
			'internalname'           => 'wpsc_merchant_pronamic_ideal'
		);

		$gateway_checkout_form_fields['wpsc_merchant_pronamic_ideal'] = self::advanced_inputs();

		return $gateways;
	}

	/**
	 * Advanced inputs
	 * 
	 * @return string
	 */
	private function advanced_inputs() {
		$output = '';

		$config_id = get_option( 'pronamic_ideal_wpsc_config_id' );

		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );

		if ( $gateway ) {
			$output = $gateway->get_input_html();
		}
	
		return $output;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Update lead status of the specified payment
	 * 
	 * @param Pronamic_Pay_Payment $payment
	 */
	public static function status_update( Pronamic_Pay_Payment $payment, $can_redirect = false ) {
		$merchant = new Pronamic_WPeCommerce_IDeal_IDealMerchant( $payment->get_source_id() );
		$data = new Pronamic_WP_Pay_WPeCommerce_PaymentData( $merchant );

		$url = $data->get_normal_return_url();

		switch ( $payment->status ) {
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
				$merchant->set_purchase_processed_by_purchid( Pronamic_WPeCommerce_WPeCommerce::PURCHASE_STATUS_INCOMPLETE_SALE );
				// $merchant->set_transaction_details( $payment->transaction->getId(), Pronamic_WPeCommerce_WPeCommerce::PURCHASE_STATUS_INCOMPLETE_SALE );

                $url = $data->get_cancel_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
				/*
				 * Transactions results
				 * 
				 * @see https://github.com/wp-e-commerce/WP-e-Commerce/blob/v3.8.9.5/wpsc-merchants/paypal-pro.merchant.php#L303
				 */
				$session_id = get_post_meta( $payment->id, '_pronamic_payment_wpsc_session_id', true );
				
				transaction_results( $session_id );

            	$merchant->set_purchase_processed_by_purchid( Pronamic_WPeCommerce_WPeCommerce::PURCHASE_STATUS_ACCEPTED_PAYMENT );

                $url = $data->get_success_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:

				break;
			default:

				break;
		}
		
		if ( $can_redirect ) {
			wp_redirect( $url, 303 );

			exit;
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function source_text( $text, Pronamic_WP_Pay_Payment $payment ) {
		$text  = '';

		$text .= __( 'WP e-Commerce', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			add_query_arg( array(
				'page'           => 'wpsc-sales-logs', 
				'purchaselog_id' => $payment->get_source_id()
			), admin_url( 'index.php' ) ),
			sprintf( __( 'Purchase #%s', 'pronamic_ideal' ), $payment->get_source_id() )
		);

		return $text;
	}
}
