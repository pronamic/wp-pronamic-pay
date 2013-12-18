<?php

/**
 * Title: WooCommerce iDEAL gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WooCommerce_IDeal_IDealGateway extends WC_Payment_Gateway {
	/**
	 * The unique ID of this payment gateway
	 * 
	 * @var string
	 */
	const ID = 'pronamic_pay_ideal';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an iDEAL gateway
	 */
    public function __construct() { 
		$this->id           = self::ID;
		$this->method_title = __( 'Pronamic iDEAL', 'pronamic_ideal' );

		// The iDEAL payment gateway has an issuer select field in case of the iDEAL advanced variant
		// @see https://github.com/woothemes/woocommerce/blob/v1.6.6/classes/gateways/class-wc-payment-gateway.php#L24
		$this->has_fields = true;
		
		// Load the form fields
		$this->init_form_fields();
		
		// Load the settings.
		$this->init_settings();
		
		// Define user set variables
		$this->icon                = $this->get_option( 'icon' );
		$this->title               = $this->get_option( 'title' );
		$this->description         = $this->get_option( 'description' );
		$this->config_id           = $this->get_option( 'config_id' );
		$this->payment_description = $this->get_option( 'payment_description' );
		
		// Actions
		$update_action = 'woocommerce_update_options_payment_gateways_' . $this->id;
		if ( Pronamic_WooCommerce_WooCommerce::version_compare( '2.0.0', '<' ) ) {
			$update_action = 'woocommerce_update_options_payment_gateways';
		}

		add_action( $update_action, array( $this, 'process_admin_options' ) );

		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
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
				'default'     => __( 'iDEAL', 'pronamic_ideal' ),
			),
			'description' => array(
				'title'       => __( 'Description', 'pronamic_ideal' ),
				'type'        => 'textarea',
				'description' => $description_prefix . __( 'Give the customer instructions for paying via iDEAL, and let them know that their order won\'t be shipping until the money is received.', 'pronamic_ideal' ),
				'default'     => __( 'With iDEAL you can easily pay online in the secure environment of your own bank.', 'pronamic_ideal' ),
			),
			'icon'        => array(
				'title'       => __( 'Icon', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' =>
					$description_prefix . 
					__( 'This controls the icon which the user sees during checkout.', 'pronamic_ideal' ) . '<br />' .
					sprintf( __( 'Default: <code>%s</code>.', 'pronamic_ideal' ), plugins_url( 'images/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file ) ),
				'default'     => plugins_url( 'images/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file ),
			),
			'config_id'   => array(
				'title'       => __( 'Configuration', 'pronamic_ideal' ),
				'type'        => 'select',
				'default'     => '',
				'options'     => Pronamic_WordPress_IDeal_IDeal::get_config_select_options(), 
			),
    		'payment' => array(
    			'title'       => __( 'Payment Options', 'pronamic_ideal' ),
    			'type'        => 'title',
    			'description' => '',
    		),
			'payment_description' => array(
				'title'       => __( 'Payment Description', 'pronamic_ideal' ),
				'type'        => 'text',
				'description' => 
					$description_prefix . 
					__( 'This controls the payment description.', 'pronamic_ideal' ) . '<br />' .
					sprintf( __( 'Default: <code>%s</code>.', 'pronamic_ideal' ), Pronamic_WooCommerce_PaymentData::get_default_description() ) . '<br />' .
					sprintf( __( 'Tags: %s', 'pronamic_ideal' ), sprintf( '<code>%s</code> <code>%s</code> <code>%s</code>', '{order_number}', '{order_date}', '{blogname}' ) ),
				'default'     => Pronamic_WooCommerce_PaymentData::get_default_description(),
			),
		);
    }

	//////////////////////////////////////////////////
    
	/**
	 * Admin Panel Options 
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 */
	public function admin_options() {
    	?>
    	<h3>
    		<?php _e( 'Pronamic iDEAL', 'pronamic_ideal' ); ?>
    	</h3>
    	
    	<table class="form-table">
    		<?php $this->generate_settings_html(); ?>
		</table>
    	<?php
    }

	//////////////////////////////////////////////////

    /**
	 * Payment fields
	 * 
	 * @see https://github.com/woothemes/woocommerce/blob/v1.6.6/templates/checkout/form-pay.php#L66
	 */
	function payment_fields() {
		// @see https://github.com/woothemes/woocommerce/blob/v1.6.6/classes/gateways/class-wc-payment-gateway.php#L181
		parent::payment_fields();

		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $this->config_id );

		if ( $gateway ) {
			echo $gateway->get_input_html();
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Receipt page
	 */
	function receipt_page( $order_id ) {
		$order = new WC_Order( $order_id );

		$data = new Pronamic_WooCommerce_PaymentData( $order, $this->payment_description );

		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $this->config_id );

		if ( $gateway ) {
			$payment = Pronamic_WordPress_IDeal_IDeal::start( $this->config_id, $gateway, $data );

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
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $this->config_id );
		
		$return = false;

		if ( $gateway ) {
			if ( $gateway->is_http_redirect() ) {
				$return = $this->process_gateway_http_redirect( $order, $gateway );
			}

			if ( $gateway->is_html_form() ) {
				$return = $this->process_gateway_html_form( $order );
			}
		}

		// Mark as pending (we're awaiting the payment)
		$order->update_status( $new_status_slug, $note );

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
			'redirect'	=> add_query_arg( 
				array(
					'order' => $order->id, 
					'key'   => $order->order_key
				),
				get_permalink( woocommerce_get_page_id( 'pay' ) )
			)
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

		$data = new Pronamic_WooCommerce_PaymentData( $order, $this->payment_description );

		$payment = Pronamic_WordPress_IDeal_IDeal::start( $this->config_id, $gateway, $data );

		$error = $gateway->get_error();

		if ( is_wp_error( $error ) ) {
			$woocommerce->add_error( Pronamic_WordPress_IDeal_IDeal::get_default_error_message() );

			foreach ( $error->get_error_messages() As $message ) {
				$woocommerce->add_error( $message );
			}

			// @see https://github.com/woothemes/woocommerce/blob/v1.6.6/woocommerce-functions.php#L518
			return array(
				'result' 	=> 'failed'
			);
		} else {
	    	$url = $payment->get_action_url();
	
			return array(
				'result' 	=> 'success',
				'redirect'	=> $url
			);
		}
    }
}
