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

		add_action( "pronamic_payment_status_update_{$slug}", array( __CLASS__, 'status_update' ), 10, 2 );

		add_action( "it_exchange_print_{$slug}_wizard_settings", array( __CLASS__, 'wizard_settings' ) );

		// Filters
		add_filter( "pronamic_payment_source_text_{$slug}", array( __CLASS__, 'source_text' ), 10, 2 );

		add_filter( "it_exchange_get_{$slug}_make_payment_button", array( __CLASS__, 'make_payment_button' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Register settings
	 */
	public static function register_settings() {

		add_settings_section(
			self::OPTION_GROUP, // id
			__( 'iDEAL Gateway Settings', 'pronamic_ideal' ), // title
			'__return_false', // callback
			self::OPTION_GROUP // page
		);

		add_settings_field(
			self::BUTTON_TITLE_OPTION_KEY, // id
			__( 'Title', 'pronamic_ideal' ), // title
			array( __CLASS__, 'input_text' ), // callback
			self::OPTION_GROUP, // page
			self::OPTION_GROUP, // section
			array(
				'label_for' => self::BUTTON_TITLE_OPTION_KEY,
				'classes'   => array( 'regular-text' ),
				'default'   => self::get_gateway_button_title(),
			) // args
		);

		add_settings_field(
			self::CONFIGURATION_OPTION_KEY, // id
			__( 'iDEAL Configuration', 'pronamic_ideal' ), // title
			array( __CLASS__, 'input_select' ), // callback
			self::OPTION_GROUP, // page
			self::OPTION_GROUP, // section
			array(
				'label_for' => self::CONFIGURATION_OPTION_KEY,
				'options'   => Pronamic_WordPress_IDeal_IDeal::get_config_select_options(),
			) // args
		);

		register_setting( self::OPTION_GROUP, self::BUTTON_TITLE_OPTION_KEY );
		register_setting( self::OPTION_GROUP, self::CONFIGURATION_OPTION_KEY );
	}

	/**
	 * Input text
	 *
	 * @param array $args
	 */
	public static function input_text( $args ) {
		$name = $args['label_for'];

		$classes = array();
		if ( isset( $args['classes'] ) ) {
			$classes = $args['classes'];
		}

		$default = '';
		if ( isset( $args['default'] ) ) {
			$default = $args['default'];
		}

		printf(
			'<input name="%s" id="%s" type="text" class="%s" value="%s" />',
			esc_attr( $name ),
			esc_attr( $name ),
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( get_option( $name, $default ) )
		);
	}

	/**
	 * Input select
	 *
	 * @param array $args
	 */
	public static function input_select( $args ) {
		$name = $args['label_for'];

		$classes = array();
		if ( isset( $args['classes'] ) ) {
			$classes = $args['classes'];
		}

		$options = array();
		if ( isset( $args['options'] ) ) {
			$options = $args['options'];
		}

		printf(
			'<select name="%s" id="%s" class="%s">',
			esc_attr( $name ),
			esc_attr( $name ),
			esc_attr( implode( ' ', $classes ) )
		);

		$current_value = get_option( $name );

		foreach ( $options as $option_key => $option ) {

			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $option_key ),
				selected( $option_key, $current_value, false ),
				esc_attr( $option )
			);
		}

		echo '</select>';
	}

	/**
	 * Gateway settings
	 */
	public static function settings() {

		include Pronamic_WordPress_IDeal_Plugin::$dirname . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'exchange' . DIRECTORY_SEPARATOR . 'settings.php';
	}

	//////////////////////////////////////////////////

	/**
	 *
	 */
	public static function wizard_settings() {

		echo '<div class="field pronamic-ideal-wizard">hallo</div>';
	}

	//////////////////////////////////////////////////

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

		// Prepare transaction data
		$unique_hash        = it_exchange_create_unique_hash();
		$current_customer   = it_exchange_get_current_customer();
		$transaction_object = it_exchange_generate_transaction_object();

		if ( ! $transaction_object instanceof stdClass ) {

			return;
		}

		it_exchange_add_transient_transaction( self::$slug, $unique_hash, $current_customer->ID, $transaction_object );

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
	 * Update the status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 * @param bool                 $can_redirect (optional, defaults to false)
	 */
	public static function status_update( Pronamic_Pay_Payment $payment, $can_redirect = false ) {

		// Create empty payment data object to be able to get the URLs
		$empty_data = new Pronamic_Exchange_PaymentData( 0, new stdClass() );

		switch ( $payment->get_status() ) {

			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
				$url = $empty_data->get_cancel_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
				$url = $empty_data->get_error_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
				$url = $empty_data->get_error_url();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:

				$transient_transaction = it_exchange_get_transient_transaction( self::$slug, $payment->get_source_id() );

				// Create transaction
				$transaction_id = it_exchange_add_transaction(
					self::$slug,
					$payment->get_source_id(),
					Pronamic_Exchange_Exchange::ORDER_STATUS_PAID,
					$transient_transaction['customer_id'],
					$transient_transaction['transaction_object']
				);

				// A transaction ID is numeric on success
				if ( ! is_numeric( $transaction_id ) ) {
					$url = $empty_data->get_error_url();

					break;
				}

				$data = new Pronamic_Exchange_PaymentData( $transaction_id, new stdClass() );

				$url = $data->get_success_url();

				it_exchange_empty_shopping_cart();

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:

			default:

				$url = $empty_data->get_normal_return_url();

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
	 *
	 * @param string                  $text
	 * @param Pronamic_WP_Pay_Payment $payment
	 *
	 * @return string $text
	 */
	public static function source_text( $text, Pronamic_WP_Pay_Payment $payment ) {

		$text  = '';
		$text .= __( 'iThemes Exchange', 'pronamic_ideal' ) . '<br />';
		$text .= sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->source_id );

		return $text;
	}
}