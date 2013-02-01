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
		add_action( 'cp_action_gateway_values',     array( __CLASS__, 'gateway_values' ) );
		add_action( 'cp_action_payment_method',     array( __CLASS__, 'payment_method' ) );
		add_action( 'cp_action_gateway',            array( __CLASS__, 'gateway_process' ) );

		add_action( 'template_redirect', array( __CLASS__, 'process_gateway' ) );

		add_action( 'pronamic_ideal_status_update', array( __CLASS__, 'update_status' ), 10, 2 );
		
		add_filter( 'pronamic_ideal_source_column_classipress', array( __CLASS__, 'source_column' ), 10, 2 );
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
				'options' => Pronamic_WordPress_IDeal_IDeal::get_configurations_select_options(),
				'id'      => $app_abbr . '_pronamic_ideal_configuration_id'
			),
            array(
            	'type'    => 'tabend',
				'id'      => ''
			)
        );
	}

	//////////////////////////////////////////////////
	
	/**
	 * Add the option to the payment drop-down list on checkout
	 */
	public static function payment_method() {
	    global $app_abbr, $gateway_name;

	    if ( get_option( $app_abbr . '_pronamic_ideal_enable' ) == 'yes' ) {
	        echo '<option value="pronamic_ideal">' . __( 'iDEAL', 'pronamic_ideal' ) . '</option>';
	    }
	}

	//////////////////////////////////////////////////

	/**
	 * Process gateway
	 */
	public static function process_gateway() {
		global $gateway_name, $app_abbr, $post_url;

		if ( isset( $_POST['classipress_pronamic_ideal'] ) ) {
			$configuration_id = get_option( $app_abbr . '_pronamic_ideal_configuration_id' );

			$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );
	
			$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );
				
			if ( $gateway ) {
				$data = new Pronamic_ClassiPress_IDeal_IDealDataProxy( $_POST );
					
				Pronamic_WordPress_IDeal_IDeal::start( $configuration, $gateway, $data );
					
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
		global $gateway_name, $app_abbr, $post_url;

		// if gateway wasn't selected then exit
		if ( $order_values['cp_payment_method'] != 'pronamic_ideal' ) { 
			return;
		}

		// prepare and add transaction entry
		if ( file_exists( TEMPLATEPATH . '/includes/gateways/process.php' ) ) {
			require_once TEMPLATEPATH . '/includes/gateways/process.php';

			$transaction_entry = cp_prepare_transaction_entry( $order_values );
			if ( $transaction_entry ) {
        		$transaction_id = cp_add_transaction_entry( $transaction_entry );
			}
		}

		// display iDEAL form
		$data = new Pronamic_ClassiPress_IDeal_IDealDataProxy( $order_values );

		$configuration_id = get_option( $app_abbr . '_pronamic_ideal_configuration_id' );

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );

		if ( $gateway ) {
			if ( $gateway->is_html_form() ) {
				Pronamic_WordPress_IDeal_IDeal::start( $configuration, $gateway, $data );

				echo $gateway->get_form_html( $auto_submit = true );
			}
			
			if ( $gateway->is_http_redirect() ) {
				// Hide the checkout page container HTML element
				echo '<style type="text/css">.thankyou center { display: none; }</style>';

				?>
				<form method="post" action="">
					<?php 

					echo Pronamic_IDeal_IDeal::htmlHiddenFields( $order_values );

					echo $gateway->get_input_html();
					
					?>

					<p>
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
	public static function update_status( Pronamic_WordPress_IDeal_Payment $payment, $can_redirect = false ) {
		if ( $payment->getSource() == self::SLUG ) {
			$id = $payment->getSourceId();

			$order = Pronamic_ClassiPress_ClassiPress::get_order_by_id( $id );
			$data  = new Pronamic_ClassiPress_IDeal_IDealDataProxy( $order );

			$url = $data->getNormalReturnUrl();

			switch ( $payment->status ) {
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
						
					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
						
					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
						
					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
					Pronamic_ClassiPress_ClassiPress::process_membership_order( $order );
					Pronamic_ClassiPress_ClassiPress::process_ad_order( $id );
		            	
	            	$url = $data->getSuccessUrl();

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
	public static function source_column( $text, $payment ) {
		$text  = '';

		$text .= __( 'ClassiPress', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $payment->getSourceId() ),
			sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->getSourceId() ) 
		);

		return $text;
	}
}
