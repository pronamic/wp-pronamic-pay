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

	/**
	 * Option for config ID
	 * 
	 * @var string
	 */
	const OPTION_CONFIG_ID = 'pronamic_pay_ideal_event_espreso_config_id';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		if ( Pronamic_EventEspresso_EventEspresso::is_active() ) {
			add_action( 'init', array( __CLASS__, 'init' ) );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Initiliaze
	 */
	public static function init() {
		add_filter( 'action_hook_espresso_display_gateway_settings', array( __CLASS__, 'display_gateway_settings' ) );

		add_action( 'action_hook_espresso_display_onsite_payment_header', 'espresso_display_onsite_payment_header' );
		add_action( 'action_hook_espresso_display_onsite_payment_footer', 'espresso_display_onsite_payment_footer' );
		add_action( 'action_hook_espresso_display_onsite_payment_gateway', array( __CLASS__, 'display_gateway' ) );

		add_filter( 'filter_hook_espresso_transactions_get_attendee_id', array( __CLASS__, 'transactions_get_attendee_id' ) );

		add_action( 'template_redirect', array( __CLASS__, 'process_gateway' ) );
		
		$slug = self::SLUG;

		add_action( "pronamic_payment_status_update_$slug", array( __CLASS__, 'update_status' ), 10, 2 );
		add_filter( "pronamic_payment_source_text_$slug",   array( __CLASS__, 'source_text' ), 10, 2 );

		// Fix fatal error since Event Espresso 3.1.29.1.P
		if ( defined( 'EVENT_ESPRESSO_GATEWAY_DIR' ) ) {
			$gateway_dir  = EVENT_ESPRESSO_GATEWAY_DIR . 'pronamic_ideal';
			$gateway_init = $gateway_dir . '/init.php';

			if ( ! is_readable( $gateway_init ) ) {
				$created = wp_mkdir_p( $gateway_dir );
				
				if ( $created ) {
					touch( $gateway_init );
				}
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Process gateway
	 */
	public static function process_gateway() {
		if ( filter_has_var( INPUT_POST, 'event_espresso_pronamic_ideal' ) ) {
			$config_id = get_option( self::OPTION_CONFIG_ID );

			$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );
			
			if ( $gateway ) {
				$payment_data = array(
					'attendee_id' => apply_filters( 'filter_hook_espresso_transactions_get_attendee_id', '' )
				);

				$data = new Pronamic_WP_Pay_EventEspresso_PaymentData( $payment_data );
			
				$payment = Pronamic_WordPress_IDeal_IDeal::start( $config_id, $gateway, $data );

				$error = $gateway->get_error();

				if ( is_wp_error( $error ) ) {
					Pronamic_WordPress_IDeal_IDeal::render_errors( $error );
				} else {
					$gateway->redirect( $payment );
				}
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Display gateway
	 */
	public static function display_gateway( $payment_data ) {
		$config_id = get_option( self::OPTION_CONFIG_ID );

		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );

		if ( $gateway ) {
			$data = new Pronamic_WP_Pay_EventEspresso_PaymentData( $payment_data );

			?>
			<div id="pronamic-payment-option-dv" class="payment-option-dv">
				<a id="pronamic-payment-option-lnk" class="pronamic-option-lnk display-the-hidden" rel="pronamic-payment-option-form" style="cursor:pointer;">
					<?php 
					
					printf(
						'<img alt="%s" src="%s" />',
						esc_attr__( 'Pay with iDEAL', 'pronamic_ideal' ),
						esc_attr( plugins_url( 'images/ideal.nl/iDEAL-Payoff-2-klein.gif', Pronamic_WordPress_IDeal_Plugin::$file ) )
					);
					
					?>
				</a>

				<div id="pronamic-payment-option-form-dv" class="hide-if-js">
					<h3 class="payment_header">
						<?php _e( 'iDEAL', 'pronamic_ideal' ); ?>
					</h3>
	
					<div class="event_espresso_form_wrapper">
						<form method="post" action="<?php echo esc_attr( $data->get_notify_url() ); ?>">
							<?php 
		
							echo $gateway->get_input_html();
							
							?>
		
							<p>
								<?php
		
								printf(
									'<input class="ideal-button allow-leave-page" type="submit" name="event_espresso_pronamic_ideal" value="%s" />',
									__( 'Pay with iDEAL', 'pronamic_ideal' )
								);
							
								?>
							</p>
						</form>
					</div>

					<p class="choose-diff-pay-option-pg">
						<a class="hide-the-displayed" rel="pronamic-payment-option-form" style="cursor:pointer;"><?php _e( 'Choose a different payment option', 'pronamic_ideal' ); ?></a>
					</p>
				</div>
			</div>

			<?php
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Transaction get attendee ID
	 * 
	 * @return string
	 */
	public static function transactions_get_attendee_id() {
		return filter_input( INPUT_GET, 'attendee_id', FILTER_SANITIZE_STRING );	
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

		// Config 
		$config_id = get_option( self::OPTION_CONFIG_ID );

		if ( filter_has_var( INPUT_POST, self::OPTION_CONFIG_ID ) ) {
			$config_id = filter_input( INPUT_POST, self::OPTION_CONFIG_ID, FILTER_VALIDATE_INT );

			update_option( self::OPTION_CONFIG_ID, $config_id );
		}
		
		// Active
		$is_active = array_key_exists( 'pronamic_ideal', $active_gateways );

		// Postbox style
		$postbox_style = '';
		if ( ! $is_active ) {
			$postbox_style = 'closed';
		}

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
														<label for="pronamic_pay_ideal_event_espresso_config_id">
															<?php _e( 'Configuration', 'pronamic_ideal' ); ?>
														</label>
														
														<br />
														
														<?php 
														
														Pronamic_WordPress_IDeal_Admin::dropdown_configs( array(
															'name'     => self::OPTION_CONFIG_ID,
															'selected' => $config_id
														) );
														
														?>
														
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
	public static function update_status( Pronamic_Pay_Payment $payment, $can_redirect = false ) {
		$id = $payment->get_source_id();

		$payment_data = Pronamic_EventEspresso_EventEspresso::get_payment_data_by_attendee_id( $id );

		$data = new Pronamic_WP_Pay_EventEspresso_PaymentData( $payment_data );

		$url = $data->get_normal_return_url();

		switch ( $payment->get_status() ) {
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
				
				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
				
				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
				
				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
            	$payment_data['payment_status'] = Pronamic_EventEspresso_EventEspresso::PAYMENT_STATUS_COMPLETED;
            	$payment_data['txn_type']       = __( 'iDEAL', 'pronamic_ideal' );
            	$payment_data['txn_id']         = $payment->transaction_id;

				break;
			case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:

				break;
			default:
				
				break;
		}
		
		Pronamic_EventEspresso_EventEspresso::update_payment( $payment_data );
		Pronamic_EventEspresso_EventEspresso::email_after_payment( $payment_data );

		if ( $can_redirect ) {
			wp_redirect( $url, 303 );

			exit;
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function source_text( $text, Pronamic_Pay_Payment $payment ) {
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
			sprintf( __( 'Attendee #%s', 'pronamic_ideal' ), $payment->get_source_id() )
		);

		return $text;
	}
}
