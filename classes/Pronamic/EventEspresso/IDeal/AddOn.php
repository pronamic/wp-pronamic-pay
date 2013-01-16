<?php 

/**
 * Title: Event Espresso iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_EventEspresso_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'event-espresso';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_filter( 'action_hook_espresso_display_gateway_settings',       array( __CLASS__, 'display_gateway_settings' ) );

		add_action( 'action_hook_espresso_display_onsite_payment_header',  'espresso_display_onsite_payment_header' );
		add_action( 'action_hook_espresso_display_onsite_payment_footer',  'espresso_display_onsite_payment_footer' );
		add_action( 'action_hook_espresso_display_onsite_payment_gateway', array( __CLASS__, 'display_gateway' ) );

		add_action( 'pronamic_ideal_status_update',                array( __CLASS__, 'update_status' ), 10, 2 );
		
		add_filter( 'pronamic_ideal_source_column_event-espresso', array( __CLASS__, 'source_column' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Display gateway
	 */
	public static function display_gateway( $payment_data ) {
		$configuration_id = get_option( 'pronamic_ideal_event_espresso_configuration_id' );

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );
	
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );

		if ( $gateway ) {
			$data = new Pronamic_EventEspresso_IDeal_IDealDataProxy( $payment_data );

			Pronamic_WordPress_IDeal_IDeal::start( $configuration, $gateway, $data );

			?>
			<div class="event-display-boxes">
				<h3 class="payment_header">
					<?php _e( 'iDEAL', 'pronamic_ideal' ); ?>
				</h3>
	
				<?php echo $gateway->get_form_html( ); ?>
			</div>
			<?php
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Display gateway settings
	 */
	public static function display_gateway_settings() {
		global $espresso_premium, $active_gateways;

		// Handle request
		if ( isset( $_REQUEST['activate_pronamic_ideal'] ) ) {
			$active_gateways['pronamic_ideal'] = dirname( __FILE__ );

			update_option( 'event_espresso_active_gateways', $active_gateways );
		}

		if ( isset( $_REQUEST['deactivate_pronamic_ideal'] ) ) {
			unset( $active_gateways['pronamic_ideal'] );

			update_option( 'event_espresso_active_gateways', $active_gateways );
		}

		// Configuration 
		$configuration_id = get_option( 'pronamic_ideal_event_espresso_configuration_id' );

		if ( isset( $_POST['pronamic_ideal_event_espresso_configuration_id'] ) ) {
			$configuration_id = filter_input( INPUT_POST, 'pronamic_ideal_event_espresso_configuration_id', FILTER_VALIDATE_INT );

			update_option( 'pronamic_ideal_event_espresso_configuration_id', $configuration_id );
		}
		
		// Active
		$is_active = array_key_exists( 'pronamic_ideal', $active_gateways );

		// Postbox style
		$postbox_style = '';
		if ( ! $is_active ) {
			$postbox_style = 'closed';
		}

		// Configurations
    	$configuration_options = Pronamic_WordPress_IDeal_IDeal::get_configurations_select_options();

    	// URL
		$url = add_query_arg( 'page', 'payment_gateways', admin_url( 'admin.php' ) );

		?>
		<div class="metabox-holder">
			<div class="postbox <?php echo $postbox_style; ?>">
				<div title="<?php esc_attr_e( 'Click to toggle', 'pronamic_ideal' ); ?>" class="handlediv"><br /></div>

				<h3 class="hndle">
					<?php _e( 'Pronamic iDEAL', 'pronamic_ideal' ); ?>
				</h3>

				<div class="inside">
					<div class="padding">
						<ul>
							<?php if ( $is_active ) : ?>
	
								<li class="red_alert pointer" onclick="location.href='<?php echo add_query_arg( 'deactivate_pronamic_ideal', true, $url ); ?>';" style="width:30%;">
									<strong><?php _e( 'Deactivate Pronamic iDEAL?', 'pronamic_ideal' ); ?></strong>
								</li>
	
								<form method="post" action="">
									<table width="99%" border="0" cellspacing="5" cellpadding="5">
										<tr>
											<td valign="top">
												<ul>
													<li>
														<label for="pronamic_ideal_configuration_field">
															<?php _e( 'Configuration', 'pronamic_ideal' ); ?>
														</label>
														
														<br />
		
														<select id="pronamic_ideal_configuration_field" name="pronamic_ideal_event_espresso_configuration_id">
															<?php 
															
															foreach ( $configuration_options as $value => $label ) {
																printf(
																	'<option value="%s" %s>%s</option>',
																	esc_attr( $value ),
																	selected( $configuration_id, $value, false),
																	$label
																);
															}
															
															?>
														</select>
														
														<br />
													</li>
												</ul>
											</td>
										</tr>
									</table>
								
									<?php submit_button( __( 'Update Settings', 'pronamic_ideal' ) ); ?>
								</form>

							<?php else : ?>

								<li class="green_alert pointer" onclick="location.href='<?php echo add_query_arg( 'activate_pronamic_ideal', true, $url ); ?>';" style="width:30%;">
									<strong><?php _e( 'Activate Pronamic iDEAL?', 'pronamic_ideal' ); ?></strong>
								</li>

							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
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
			$status = $payment->status;

			$payment_data = Pronamic_EventEspresso_EventEspresso::get_payment_data_by_attendee_id( $id );
			$data = new Pronamic_EventEspresso_IDeal_IDealDataProxy( $payment_data );

			$url = $data->getNormalReturnUrl();

			switch($status) {
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
					
					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
					
					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
					
					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
	            	$payment_data['payment_status'] = Pronamic_EventEspresso_EventEspresso::PAYMENT_STATUS_COMPLETED;

					break;
				case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:

					break;
				default:
					
					break;
			}
			
			$payment_data = apply_filters( 'filter_hook_espresso_update_attendee_payment_data_in_db', $payment_data );
				
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
		$url = add_query_arg( array(
			'page'                => 'events' ,
			'event_admin_reports' => 'event_list_attendees' , 
			'all_a'               => 'true'
		), admin_url( 'admin.php' ) );

		$text  = '';

		$text .= __( 'Event Espresso', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf( 
			'<a href="%s">%s</a>', 
			esc_attr( $url ),
			sprintf( __( 'Attendee #%s', 'pronamic_ideal' ), $payment->getSourceId() )
		);

		return $text;
	}
}
