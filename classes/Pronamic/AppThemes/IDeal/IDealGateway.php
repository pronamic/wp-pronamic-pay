<?php 

/**
 * Title: AppThemes iDEAL Gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_AppThemes_IDeal_IDealGateway extends APP_Gateway {
	/**
	 * Constructs and initialize the iDEAL gateway for AppThemes
	 */
	public function __construct() {
		parent::__construct( 'pronamic_ideal', array(
			'dropdown'  =>  __( 'iDEAL', 'pronamic_ideal' ),
			'admin'     =>  __( 'Pronamic iDEAL', 'pronamic_ideal' ),
			'recurring' => false,
		) );
	}

	//////////////////////////////////////////////////

	/**
	 * Returns an array representing the form to output for admin configuration
	 */
	public function form() {
		$form_values = array(
			array(
				'title'   => __( 'Configuration', 'pronamic_ideal' ),
				'type'    => 'select',
				'name'    => 'configuration_id',
				'choices' => Pronamic_WordPress_IDeal_IDeal::get_configurations_select_options() 
			),
		);
		
		$return_array = array(
			'title'   => __( 'General Information', 'pronamic_ideal' ),
			'fields'  => $form_values
		);

		return $return_array;
	}

	//////////////////////////////////////////////////

	/**
	 * Processes a payment using this Gateway
	 */
	public function process( $order, $options ) {
		if ( isset( $options['configuration_id'] ) ) {
			$configuration_id = $options['configuration_id'];
			$configuration    = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

			$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );
			
			if ( $gateway ) {
				$data = new Pronamic_WP_Pay_AppThemes_PaymentData( $order );

				if ( filter_has_var( INPUT_POST, 'classipress_pronamic_ideal' ) ) {
					Pronamic_WordPress_IDeal_IDeal::start( $configuration, $gateway, $data );
					
					$error = $gateway->get_error();
					
					if ( is_wp_error( $error ) ) {
						foreach ( $error->get_error_messages() As $message ) {
							echo $message;
						}
					} else {
						$gateway->redirect();
					}
				} else {
					?>
					<form method="post" action="">
						<?php echo $gateway->get_input_html(); ?>
	
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
	}
}
