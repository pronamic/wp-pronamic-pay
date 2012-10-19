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
	const ID = 'pronamic_ideal';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an iDEAL gateway
	 */
    public function __construct() { 
		$this->id           = self::ID;
		$this->method_title = __( 'Pronamic iDEAL', 'pronamic_ideal' );
		$this->icon         = plugins_url( 'images/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file );

		// The iDEAL payment gateway has an issuer select field in case of the iDEAL advanced variant
		$this->has_fields = false;
		
		// Load the form fields
		$this->init_form_fields();
		
		// Load the settings.
		$this->init_settings();
		
		// Define user set variables
		$this->title            = $this->settings['title'];
		$this->description      = $this->settings['description'];
		$this->configuration_id = $this->settings['configuration_id'];
		
		// Actions
		add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );

		add_action( 'woocommerce_receipt_' . self::ID, array( $this, 'receipt_page' ) );
    } 

	/**
     * Initialise form fields
     */
    function init_form_fields() {
    	$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();
    	$configuration_options = array('' => __('&mdash; Select configuration &mdash;', 'pronamic_ideal'));

    	foreach ( $configurations as $configuration ) {
    		$configuration_options[$configuration->getId()] = $configuration->getName();
    	}
    
    	$this->form_fields = array(
    		'enabled'          => array(
				'title'   => __( 'Enable/Disable', 'pronamic_ideal' ) , 
				'type'    => 'checkbox' , 
				'label'   => __( 'Enable iDEAL', 'pronamic_ideal' ) , 
				'default' => 'yes' 
			) ,  
			'title'            => array(
				'title'       => __( 'Title', 'pronamic_ideal' ) , 
				'type'        => 'text' , 
				'description' => '<br />' . __( 'This controls the title which the user sees during checkout.', 'pronamic_ideal' ) , 
				'default'     => __( 'iDEAL', 'pronamic_ideal' ) 
			) , 
			'description'      => array(
				'title'       => __( 'Description', 'pronamic_ideal' ) , 
				'type'        => 'textarea' , 
				'description' => '' . __( 'Give the customer instructions for paying via iDEAL, and let them know that their order won\'t be shipping until the money is received.', 'pronamic_ideal' ) , 
				'default'     => __( 'With iDEAL you can easily pay online in the secure environment of your own bank.', 'pronamic_ideal' )
			) , 
			'configuration_id' => array(
				'title'       => __( 'Configuration', 'pronamic_ideal' ) , 
				'type'        => 'select' , 
				'description' => '<br />' . __( 'Select an iDEAL configuration.', 'pronamic_ideal' ) , 
				'default'     => '' , 
				'options'     => $configuration_options 
			)
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
	 */
	function payment_fields() {
		if ( $this->description ) {
			echo wpautop( wptexturize( $this->description ) );
		}

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $this->configuration_id );
		if ( $configuration !== null ) {
			$variant = $configuration->getVariant();

			if ( $variant !== null && $variant->getMethod() == Pronamic_IDeal_IDeal::METHOD_ADVANCED) {
				$lists = Pronamic_WordPress_IDeal_IDeal::getTransientIssuersLists( $configuration );
				
				if ( $lists ) {
					?>
					<p class="pronamic_ideal_issuer">
						<label for="pronamic_ideal_issuer_id">
							<?php _e( 'Choose your bank', 'pronamic_ideal' ); ?>
						</label>
						
						<?php echo Pronamic_IDeal_HTML_Helper::issuersSelect( 'pronamic_ideal_issuer_id', $lists ); ?>
					</p>
					<?php 
				} elseif ( $error = Pronamic_WordPress_IDeal_IDeal::getError() ) {
					?>
					<div class="woocommerce_error">
						<?php echo $error->getConsumerMessage(); ?>
					</div>
					<?php
				} else {
					?>
					<div class="woocommerce_error">
						<?php echo __( 'Paying with iDEAL is not possible. Please try again later or pay another way.', 'pronamic_ideal' ); ?>
					</div>
					<?php 
				}
			}
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Receipt page
	 */
	function receipt_page( $order_id ) {
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $this->configuration_id );
		
		$order = new WC_Order( $order_id );
		
		$data_proxy = new Pronamic_WooCommerce_IDeal_IDealDataProxy( $order );

		$html = Pronamic_WordPress_IDeal_IDeal::getHtmlForm( $data_proxy, $configuration );
		
		echo $html;
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
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $this->configuration_id );
		if ( $configuration !== null ) {
			$variant = $configuration->getVariant();
	
			if ( $variant !== null ) {
				switch ( $variant->getMethod() ) {
					case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
						$return = $this->process_ideal_advanced_payment( $order, $configuration, $variant );
						
						break;
					case Pronamic_IDeal_IDeal::METHOD_EASY:
					case Pronamic_IDeal_IDeal::METHOD_BASIC:
						// E-mail
						// $mailer = $woocommerce->mailer();
						// $mailer->new_order( $order_id );

						$note = self::get_check_payment_note( $order, $configuration );

						self::mail_check_payment( $order, $note );
					default: 
						$return = $this->process_ideal_payment( $order, $configuration, $variant );

						break;
				}
			}
		}

		// Mark as pending (we're awaiting the payment)
		$order->update_status( $new_status_slug, $note );

		// Reduce stock levels
		$order->reduce_order_stock();

		// Empty cart
		$woocommerce->cart->empty_cart();

		// Empty awaiting payment session
		unset( $_SESSION['order_awaiting_payment'] );

		// Return
		return $return;
    }

    /**
     * Get check payment note
     * 
     * @param WC_Order $order
     * @param Pronamic_WordPress_IDeal_Configuration $configuration
     */
    private static function get_check_payment_note( $order, $configuration ) {
		// $editOrderLink = get_edit_post_link($order->id);
		// get_edit_post_link() will not work, has permissions check for current user
		$edit_order_link = add_query_arg( 
			array(
				'post'   => $order->id, 
				'action' => 'edit' 
			),
			admin_url('post.php')
		);

		$note = sprintf(
			__( 'Check the payment of order #%s in your <a href="%s">iDEAL dashboard</a> and <a href="%s">update the status of the order</a>.', 'pronamic_ideal' ) , 
			$order->id , 
			esc_attr( $configuration->getDashboardUrl() ) , 
			esc_attr( $edit_order_link )
		);

		return $note;
    }

    /**
     * Mail the new order e-mail recipient
     * 
     * @param WC_Order $order
     * @param Pronamic_WordPress_IDeal_Configuration $configuration
     */
    private function mail_check_payment( $order, $note ) {
		global $woocommerce;
		
		// E-mail
		$mailer = $woocommerce->mailer();

		$message = $mailer->wrap_message( 
			__( 'Check iDEAL payment', 'pronamic_ideal' ),
			$note
		);
		
		// Send the mail
		woocommerce_mail(
			get_option( 'woocommerce_new_order_email_recipient' ), 
			sprintf(
				__( 'Check iDEAL payment for order #%s', 'pronamic_ideal' ) , 
				$order->id
			), 
			$message
		);
    }

	//////////////////////////////////////////////////
    
    /**
     * Process iDEAL payment
     * 
     * @param WC_Order $order
     * @param Pronamic_WordPress_IDeal_Configuration $configuration
     * @param Pronamic_IDeal_Variant $variant
     * @return array
     */
    private function process_ideal_payment( $order, $configuration, $variant ) {
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
     * @param Pronamic_WordPress_IDeal_Configuration $configuration
     * @param Pronamic_IDeal_Variant $variant
     * @return array
     */
    private function process_ideal_advanced_payment( $order, $configuration, $variant ) {
		$data_proxy = new Pronamic_WooCommerce_IDeal_IDealDataProxy( $order );

    	$issuer_id = filter_input( INPUT_POST, 'pronamic_ideal_issuer_id', FILTER_SANITIZE_STRING );

		$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentBySource( $data_proxy->getSource(), $data_proxy->getOrderId() );
    	
		if ( $payment == null ) {
			$transaction = new Pronamic_IDeal_Transaction();
			$transaction->setAmount( $data_proxy->getAmount() ); 
			$transaction->setCurrency( $data_proxy->getCurrencyAlphabeticCode() );
			$transaction->setExpirationPeriod( 'PT1H' );
			$transaction->setLanguage( $data_proxy->getLanguageIso639Code() );
			$transaction->setEntranceCode( uniqid() );
			$transaction->setDescription( $data_proxy->getDescription() );
			$transaction->setPurchaseId( $data_proxy->getOrderId() );
	
			$payment = new Pronamic_WordPress_IDeal_Payment();
			$payment->configuration = $configuration;
			$payment->transaction = $transaction;
			$payment->setSource( $data_proxy->getSource(), $data_proxy->getOrderId() );
	
			$updated = Pronamic_WordPress_IDeal_PaymentsRepository::updatePayment( $payment );
    	}

		$url = Pronamic_WordPress_IDeal_IDeal::handleTransaction( $issuer_id, $payment, $variant );

		return array(
			'result' 	=> 'success',
			'redirect'	=> $url
		);
    }
}
