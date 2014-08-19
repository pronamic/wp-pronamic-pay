<?php

/**
 * Title: WooCommerce iDEAL gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WooCommerce_Pay_Gateway extends WC_Payment_Gateway {
	/**
	 * The unique ID of this payment gateway
	 *
	 * @var string
	 */
	const ID = 'pronamic_pay_ideal';
	
	/**
	 * The payment method
	 * 
	 * @var string
	 */
	protected $payment_method;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an iDEAL gateway
	 */
	public function __construct() {
		// Load the form fields
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Define user set variables
		$this->icon                = $this->get_pronamic_option( 'icon' );
		$this->title               = $this->get_pronamic_option( 'title' );
		$this->description         = $this->get_pronamic_option( 'description' );
		$this->config_id           = $this->get_pronamic_option( 'config_id' );
		$this->payment_description = $this->get_pronamic_option( 'payment_description' );

		// Actions
		$update_action = 'woocommerce_update_options_payment_gateways_' . $this->id;
		if ( Pronamic_WooCommerce_WooCommerce::version_compare( '2.0.0', '<' ) ) {
			$update_action = 'woocommerce_update_options_payment_gateways';
		}

		add_action( $update_action, array( $this, 'process_admin_options' ) );

		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
	}

	/**
     * Get Pronamic option
     *
     * The WooCommerce settings API only have an 'get_option' function in
     * WooCommerce version 2 or higher.
     *
     * @see https://github.com/woothemes/woocommerce/blob/v2.0.0/classes/abstracts/abstract-wc-settings-api.php#L130
     *
     * @param string $name
     */
	public function get_pronamic_option( $key ) {
		$value = false;

		if ( method_exists( $this, 'get_option' ) ) {
			$value = parent::get_option( $key );
		} elseif ( isset( $this->settings[ $key ] ) ) {
			$value = $this->settings[ $key ];
		}

		return $value;
	}

	/**
     * Initialise form fields
     */
	function init_form_fields() {
		$description_prefix = '';
		if ( Pronamic_WooCommerce_WooCommerce::version_compare( '2.0.0', '<' ) ) {
			$description_prefix = '<br />';
		}

		$this->form_fields = array(
			'enabled'     => array(
				'title'   => __( 'Enable/Disable', 'pronamic_ideal' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable iDEAL', 'pronamic_ideal' ),
				'default' => 'yes',
			),
			'title'       => array(
				'title'       => __( 'Title', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => $description_prefix . __( 'This controls the title which the user sees during checkout.', 'pronamic_ideal' ),
				'default'     => $this->method_title,
			),
			'description' => array(
				'title'       => __( 'Description', 'pronamic_ideal' ),
				'type'        => 'textarea',
				'description' => $description_prefix . sprintf(
					__( 'Give the customer instructions for paying via %s, and let them know that their order won\'t be shipping until the money is received.', 'pronamic_ideal' ),
					$this->method_title
				),
				'default'     => __( 'With iDEAL you can easily pay online in the secure environment of your own bank.', 'pronamic_ideal' ),
			),
			'icon'        => array(
				'title'       => __( 'Icon', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => sprintf(
					'%s%s<br />%s',
					$description_prefix,
					__( 'This controls the icon which the user sees during checkout.', 'pronamic_ideal' ),
					sprintf( __( 'Default: <code>%s</code>.', 'pronamic_ideal' ), plugins_url( 'images/icon-24x24.png', Pronamic_WP_Pay_Plugin::$file ) )
				),
				'default'     => plugins_url( 'images/icon-24x24.png', Pronamic_WP_Pay_Plugin::$file ),
			),
			'config_id'   => array(
				'title'       => __( 'Configuration', 'pronamic_ideal' ),
				'type'        => 'select',
				'default'     => '',
				'options'     => Pronamic_WP_Pay_Plugin::get_config_select_options( $this->payment_method ),
			),
			'payment' => array(
				'title'       => __( 'Payment Options', 'pronamic_ideal' ),
				'type'        => 'title',
				'description' => '',
			),
			'payment_description' => array(
				'title'       => __( 'Payment Description', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => sprintf(
					'%s%s<br />%s<br />%s',
					$description_prefix,
					__( 'This controls the payment description.', 'pronamic_ideal' ),
					sprintf( __( 'Default: <code>%s</code>.', 'pronamic_ideal' ), Pronamic_WooCommerce_PaymentData::get_default_description() ),
					sprintf( __( 'Tags: %s', 'pronamic_ideal' ), sprintf( '<code>%s</code> <code>%s</code> <code>%s</code>', '{order_number}', '{order_date}', '{blogname}' ) )
				),
				'default'     => Pronamic_WooCommerce_PaymentData::get_default_description(),
			),
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/v1.0/classes/gateways/gateway.class.php#L72-L80
	 * @see https://github.com/woothemes/woocommerce/blob/v1.2/classes/gateways/gateway.class.php#L96-L104
	 * @see https://github.com/woothemes/woocommerce/blob/v1.3/classes/woocommerce_settings_api.class.php#L18-L26
	 * @see https://github.com/woothemes/woocommerce/blob/v1.3.2/classes/woocommerce_settings_api.class.php#L18-L26
	 * @see https://github.com/woothemes/woocommerce/blob/v1.4/classes/class-wc-settings-api.php#L18-L31
	 * @see https://github.com/woothemes/woocommerce/blob/v1.5/classes/class-wc-settings-api.php#L18-L32
	 * 
	 * @since WooCommerce version 1.4 the admin_options() function has an default implementation.
	 */
	public function admin_options() {
		parent::admin_options();
	}

	//////////////////////////////////////////////////

	/**
	 * Receipt page
	 */
	function receipt_page( $order_id ) {
		$order = new WC_Order( $order_id );

		$data = new Pronamic_WooCommerce_PaymentData( $order, $this, $this->payment_description );

		$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $this->config_id );

		if ( $gateway ) {
			$payment = Pronamic_WP_Pay_Plugin::start( $this->config_id, $gateway, $data, $this->payment_method );

			echo $gateway->get_form_html( $payment );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Process the payment and return the result
	 *
	 * @param string $order_id
	 */
	function process_payment( $order_id ) {
		global $woocommerce;

		$order = new WC_Order( $order_id );

		// Update status
		$new_status_slug = Pronamic_WooCommerce_WooCommerce::ORDER_STATUS_PENDING;
		$note = __( 'Awaiting iDEAL payment.', 'pronamic_ideal' );

		// Do specifiek iDEAL variant processing
		$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $this->config_id );

		$return = false;

		if ( $gateway ) {
			if ( $gateway->is_http_redirect() ) {
				$return = $this->process_gateway_http_redirect( $order, $gateway );
			}

			if ( $gateway->is_html_form() ) {
				$return = $this->process_gateway_html_form( $order );
			}
		}

		if ( $return ) {
			// Mark as pending (we're awaiting the payment)
			$order->update_status( $new_status_slug, $note );
		} else {
			Pronamic_WooCommerce_WooCommerce::add_notice( Pronamic_WP_Pay_Plugin::get_default_error_message(), 'error' );

			if ( is_admin() && empty( $this->config_id ) ) {
				// @see https://github.com/woothemes/woocommerce/blob/v2.1.5/includes/admin/settings/class-wc-settings-page.php#L66
				$notice = sprintf(
					__( 'You have to select an iDEAL configuration on the <a href="%s">WooCommerce checkout settings page</a>.', 'pronamic_ideal' ),
					add_query_arg( array(
						'page'    => 'wc-settings',
						'tab'     => 'checkout',
						'section' => sanitize_title( __CLASS__ ),
					), admin_url( 'admin.php' ) )
				);

				Pronamic_WooCommerce_WooCommerce::add_notice( $notice, 'error' );
			}
		}

		// Return
		return $return;
	}

	//////////////////////////////////////////////////

	/**
     * Process iDEAL payment
     *
     * @param WC_Order $order
     *
     * @return array
     */
	private function process_gateway_html_form( $order ) {
		// Return pay page redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> Pronamic_WooCommerce_WooCommerce::get_order_pay_url( $order ),
		);
	}

	/**
     * Process iDEAL advanced payment
     *
     * @param WC_Order $order
     * @return array
     */
	private function process_gateway_http_redirect( $order, $gateway ) {
		global $woocommerce;

		$data = new Pronamic_WooCommerce_PaymentData( $order, $this, $this->payment_description );

		$payment = Pronamic_WP_Pay_Plugin::start( $this->config_id, $gateway, $data, $this->payment_method );

		$error = $gateway->get_error();

		if ( is_wp_error( $error ) ) {
			Pronamic_WooCommerce_WooCommerce::add_notice( Pronamic_WP_Pay_Plugin::get_default_error_message(), 'error' );

			foreach ( $error->get_error_messages() As $message ) {
				Pronamic_WooCommerce_WooCommerce::add_notice( $message, 'error' );
			}

			// @see https://github.com/woothemes/woocommerce/blob/v1.6.6/woocommerce-functions.php#L518
			// @see https://github.com/woothemes/woocommerce/blob/v2.1.5/includes/class-wc-checkout.php#L669
			return array(
				'result' 	=> 'failure',
			);
		} else {
			$url = $payment->get_action_url();

			return array(
				'result' 	=> 'success',
				'redirect'	=> $url,
			);
		}
	}
}
