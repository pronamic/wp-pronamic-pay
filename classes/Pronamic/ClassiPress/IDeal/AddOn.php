<?php 

/**
 * Title: ClassiPress iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_ClassiPress_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'classipress';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_action( 'appthemes_init',           array( __CLASS__, 'appthemes_init' ) );
		
		/*
		 * We have to add this action on bootstrap, because we can't
		 * deterime earlier we are dealing with ClassiPress
		 */
		if ( is_admin() ) {
			add_action( 'cp_action_gateway_values', array( __CLASS__, 'gateway_values' ) );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function appthemes_init() {
		global $app_theme;

		if ( $app_theme == 'ClassiPress' ) {
			add_action( 'cp_action_payment_method',     array( __CLASS__, 'payment_method' ) );
			add_action( 'cp_action_gateway',            array( __CLASS__, 'gateway_process' ) );

			add_action( 'template_redirect', array( __CLASS__, 'process_gateway' ) );

			$slug = self::SLUG;

			add_action( "pronamic_payment_status_update_$slug", array( __CLASS__, 'update_status' ), 10, 2 );
			add_filter( "pronamic_payment_source_text_$slug",   array( __CLASS__, 'source_text' ), 10, 2 );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Gateway value
	 */
	public static function gateway_values() {
		global $app_abbr;

		// Gateway values
		global $action_gateway_values;

		$action_gateway_values = array(
			// Tab Start
			array(
				'type'    => 'tab',
				'tabname' => __( 'iDEAL', 'pronamic_ideal' ),
				'id'      => ''
			),
			// Title
			array(
				'type'    => 'title',
				'name'    => __( 'iDEAL Options', 'pronamic_ideal' ),
				'id'      => ''
			),
			// Logo/Picture
			array(
				'type'    => 'logo',
				'name'    => sprintf( '<img src="%s" alt="" />', plugins_url( 'images/icon-32x32.png', Pronamic_WordPress_IDeal_Plugin::$file ) ),
				'id'      => ''
			),
            // Select Box
            array(
            	'type'    => 'select',
				'name'    => __( 'Enable iDEAL', 'pronamic_ideal' ),
				'options' => array(
					'yes' => __( 'Yes', 'pronamic_ideal' ),
					'no'  => __( 'No', 'pronamic_ideal' )
				) , 
				'id'      => $app_abbr . '_pronamic_ideal_enable'
			),
			// Select Box
			array(
				'type'    => 'select',
				'name'    => __( 'iDEAL Configuration', 'pronamic_ideal' ),
				'options' => Pronamic_WordPress_IDeal_IDeal::get_config_select_options(),
				'id'      => $app_abbr . '_pronamic_ideal_config_id'
			),
            array(
            	'type'    => 'tabend',
				'id'      => ''
			)
        );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the config
	 * 
	 * @return Pronamic_WordPress_IDeal_Configuration
	 */
	private function get_gateway() {
		global $app_abbr;
		
		$config_id = get_option( $app_abbr . '_pronamic_ideal_config_id' );
		
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );
		
		return $gateway;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Add the option to the payment drop-down list on checkout
	 */
	public static function payment_method() {
	    global $app_abbr;

	    if ( get_option( $app_abbr . '_pronamic_ideal_enable' ) == 'yes' ) {
	        echo '<option value="pronamic_ideal">' . __( 'iDEAL', 'pronamic_ideal' ) . '</option>';
	    }
	}

	//////////////////////////////////////////////////

	/**
	 * Process gateway
	 */
	public static function process_gateway() {
		if ( isset( $_POST['classipress_pronamic_ideal'] ) ) {
			global $app_abbr;
			
			$config_id = get_option( $app_abbr . '_pronamic_ideal_config_id' );
			
			$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );
				
			if ( $gateway ) {
				$id = filter_input( INPUT_POST, 'oid', FILTER_SANITIZE_STRING );
				
				$order = Pronamic_ClassiPress_ClassiPress::get_order_by_id( $id );

				$data = new Pronamic_ClassiPress_IDeal_IDealDataProxy( $order );
					
				Pronamic_WordPress_IDeal_IDeal::start( $config_id, $gateway, $data );
					
				if ( $gateway->is_http_redirect() ) {
					$gateway->redirect();
				}
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Process gateway
	 */
	public static function gateway_process( $order_values ) {
		// If gateway wasn't selected then immediately return
		if ( $order_values['cp_payment_method'] != 'pronamic_ideal' ) { 
			return;
		}

		// Add transaction entry
		$transaction_id = Pronamic_ClassiPress_ClassiPress::add_transaction_entry( $order_values );

		// Handle gateway
		global $app_abbr;
		
		$config_id = get_option( $app_abbr . '_pronamic_ideal_config_id' );
		
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );

		if ( $gateway ) {
			$data = new Pronamic_ClassiPress_IDeal_IDealDataProxy( $order_values );

			if ( $gateway->is_html_form() ) {
				Pronamic_WordPress_IDeal_IDeal::start( $config_id, $gateway, $data );

				echo $gateway->get_form_html( $auto_submit = true );
			}
			
			if ( $gateway->is_http_redirect() ) {
				// Hide the checkout page container HTML element
				echo '<style type="text/css">.thankyou center { display: none; }</style>';

				?>
				<form class="form_step" method="post" action="">
					<?php 

					echo Pronamic_IDeal_IDeal::htmlHiddenFields( array(
						'cp_payment_method'  => 'pronamic_ideal',
						'oid'                => $data->get_order_id()
					) );

					echo $gateway->get_input_html();
					
					?>

					<p class="btn1">
						<?php

						printf(
							'<input class="ideal-button" type="submit" name="classipress_pronamic_ideal" value="%s" />',
							__( 'Pay with iDEAL', 'pronamic_ideal' )
						);
					
						?>
					</p>
				</form>
				<?php
			}
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Update lead status of the specified payment
	 * 
	 * @param string $payment
	 */
	public static function update_status( Pronamic_Pay_Payment $payment, $can_redirect = false ) {
		if ( $payment->getSource() == self::SLUG ) {
			$id = $payment->getSourceId();

			$order = Pronamic_ClassiPress_ClassiPress::get_order_by_id( $id );

			$data  = new Pronamic_ClassiPress_IDeal_IDealDataProxy( $order );

			$url = $data->get_normal_return_url();

			switch ( $payment->status ) {
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
						
					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
						
					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
						
					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
					if ( ! Pronamic_ClassiPress_Order::is_completed( $order ) ) {
						Pronamic_ClassiPress_ClassiPress::process_ad_order( $id );

						Pronamic_ClassiPress_ClassiPress::process_membership_order( $order );

						Pronamic_ClassiPress_ClassiPress::update_payment_status_by_txn_id( $id, Pronamic_ClassiPress_PaymentStatuses::COMPLETED );
					}

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
	}

	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function source_text( $text, Pronamic_WP_Pay_Payment $payment ) {
		$text  = '';

		$text .= __( 'ClassiPress', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			// get_edit_post_link( $payment->getSourceId() ),
			add_query_arg( 'page', 'transactions', admin_url( 'admin.php' ) ),
			sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->source_id ) 
		);

		return $text;
	}
}
