<?php

/**
 * Title: Exchange iDEAL Add-On
 * Description:
 * Copyright Copyright (c) 2005 - 2014
 * Company: Pronamic
 * @author Stefan Boonstra
 * @version 1.0
 */
class Pronamic_Exchange_IDeal_AddOn {

	/**
	 * The add-on's slug.
	 *
	 * @var string
	 */
	public static $slug = 'pronamic-ideal';

	/**
	 * Options group.
	 *
	 * @const string
	 */
	const OPTION_GROUP = 'pronamic_exchange_ideal_addon';

	/**
	 * The option key that stores the configuration ID.
	 *
	 * @const string
	 */
	const CONFIGURATION_OPTION_KEY = 'pronamic_exchange_ideal_addon_configuration';

	/**
	 * The option key that stores the iDEAL payment button text.
	 *
	 * @const string
	 */
	const BUTTON_TITLE_OPTION_KEY = 'pronamic_exchange_ideal_addon_button_title';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {

		add_action( 'it_exchange_register_addons', array( __CLASS__, 'init' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() {

		$slug = self::$slug;

		$options = array(
			'name'              => __( 'iDEAL', 'pronamic_ideal' ),
			'description'       => __( 'Adds the ability for users to checkout with iDEAL.', 'pronamic_ideal' ),
			'author'            => 'Pronamic',
			'author_url'        => 'http://www.pronamic.eu/wordpress-plugins/pronamic-ideal/',
			'icon'              => plugins_url( 'images/icon-50x50.png', Pronamic_WordPress_IDeal_Plugin::$file ),
			'file'              => Pronamic_WordPress_IDeal_Plugin::$dirname . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'exchange' . DIRECTORY_SEPARATOR . 'add-on.php',
			'category'          => 'transaction-methods',
			'supports'          => array( 'transaction_status' => true ),
			'settings-callback' => array( __CLASS__, 'settings' ),
		);

		it_exchange_register_addon( $slug, $options );

		// Actions
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );

		add_action( 'template_redirect', array( __CLASS__, 'process_payment' ), 11 );

		// Filters
		add_filter( "pronamic_payment_source_text_{$slug}", array( __CLASS__, 'source_text' ), 10, 2 );

		add_filter( "it_exchange_get_{$slug}_make_payment_button", array( __CLASS__, 'make_payment_button' ) );
	}

	//////////////////////////////////////////////////
	// Settings
	//////////////////////////////////////////////////

	/**
	 * Register settings
	 */
	public static function register_settings() {

		register_setting( self::OPTION_GROUP, self::BUTTON_TITLE_OPTION_KEY );
		register_setting( self::OPTION_GROUP, self::CONFIGURATION_OPTION_KEY );
	}

	/**
	 * Gateway settings
	 */
	public static function settings() {

		$data = new stdClass();

		$data->title                 = self::get_gateway_button_title();
		$data->current_configuration = self::get_gateway_configuration_id();
		$data->configurations        = Pronamic_WordPress_IDeal_IDeal::get_config_select_options();

		include Pronamic_WordPress_IDeal_Plugin::$dirname . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'exchange' . DIRECTORY_SEPARATOR . 'settings.php';
	}

	/**
	 * Get the iDEAL gateway title.
	 *
	 * @return string $button_title
	 */
	public static function get_gateway_button_title() {

		return get_option( self::BUTTON_TITLE_OPTION_KEY, __( 'Pay with iDEAL', 'pronamic_ideal' ) );
	}

	/**
	 * Get the iDEAL gateway configuration ID.
	 *
	 * @return string $configuration_id
	 */
	public static function get_gateway_configuration_id() {

		return get_option( self::CONFIGURATION_OPTION_KEY, 0 );
	}

	//////////////////////////////////////////////////

	/**
	 * Build the iDEAL payment form.
	 */
	public static function make_payment_button() {

		$payment_form = '';

		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( self::get_gateway_configuration_id() );

		if ( $gateway ) {

			$payment_form .= '<form action="' . it_exchange_get_page_url( 'transaction' ) . '" method="post">';
			$payment_form .=     '<input type="hidden" name="it-exchange-transaction-method" value="' . self::$slug . '" />';
			$payment_form .=     $gateway->get_input_html();
			$payment_form .=     wp_nonce_field( 'pronamic-ideal-checkout', '_pronamic_ideal_nonce', true, false );
			$payment_form .=     '<input type="submit" name="pronamic_ideal_process_payment" value="' . self::get_gateway_button_title() . '" />';
			$payment_form .= '</form>';
		}

		return $payment_form;
	}

	//////////////////////////////////////////////////

	/**
	 * Check if an iDEAL payment needs to be processed.
	 */
	public static function process_payment() {

		$do_process_payment = filter_input( INPUT_POST, 'pronamic_ideal_process_payment', FILTER_SANITIZE_STRING );

		if ( strlen( $do_process_payment ) <= 0 ) {

			return;
		}

		$unique_hash        = it_exchange_create_unique_hash();
		$current_customer   = it_exchange_get_current_customer();
		$transaction_object = it_exchange_generate_transaction_object();

		if ( $transaction_object === false ) {

			return;
		}

		it_exchange_add_transient_transaction( self::$slug, $unique_hash, $current_customer, $transaction_object );

		$configuration_id = self::get_gateway_configuration_id();

		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration_id );

		if ( $gateway ) {

			$data = new Pronamic_Exchange_PaymentData( $unique_hash, $transaction_object );

			$payment = Pronamic_WordPress_IDeal_IDeal::start( $configuration_id, $gateway, $data );

			$gateway->redirect( $payment );

			exit;
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Source column
	 *
	 * @param string                  $text
	 * @param Pronamic_WP_Pay_Payment $payment
	 *
	 * @return string $text
	 */
	public static function source_text( $text, Pronamic_WP_Pay_Payment $payment ) {

		$text  = '';
		$text .= __( 'iThemes Exchange', 'pronamic_ideal' ) . '<br />';
		$text .= sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $payment->source_id ),
			sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->source_id )
		);

		return $text;
	}
}