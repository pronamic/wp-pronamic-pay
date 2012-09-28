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
		add_filter( 'action_hook_espresso_display_gateway_settings',       array( __CLASS__, 'displayGatewaySettings' ) );

		add_action( 'action_hook_espresso_display_onsite_payment_header',  'espresso_display_onsite_payment_header' );
		add_action( 'action_hook_espresso_display_onsite_payment_footer',  'espresso_display_onsite_payment_footer' );
		add_action( 'action_hook_espresso_display_onsite_payment_gateway', array( __CLASS__, 'displayGateway' ) );

		add_action( 'pronamic_ideal_status_update', array( __CLASS__, 'updateStatus' ), 10, 2 );
		
		add_filter( 'pronamic_ideal_source_column_event-espresso', array( __CLASS__, 'sourceColumn' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Display gateway
	 */
	public static function displayGateway( $data ) {
		$configuration_id = get_option( 'pronamic_ideal_event_espresso_configuration_id' );

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

		$data_proxy = new Pronamic_EventEspresso_IDeal_IDealDataProxy( $data );

		$html = Pronamic_WordPress_IDeal_IDeal::getHtmlForm( $data_proxy, $configuration, false );

		?>
		<div class="event-display-boxes">
			<h3 class="payment_header">
				<?php _e( 'iDEAL', 'pronamic_ideal' ); ?>
			</h3>

			<?php echo $html; ?>
		</div>
		<?php
	}

	//////////////////////////////////////////////////

	/**
	 * Display gateway settings
	 */
	public static function displayGatewaySettings() {
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
		$configurationId = get_option( 'pronamic_ideal_event_espresso_configuration_id' );

		if(isset($_POST['pronamic_ideal_event_espresso_configuration_id'])) {
			$configurationId = filter_input(INPUT_POST, 'pronamic_ideal_event_espresso_configuration_id', FILTER_VALIDATE_INT);

			update_option('pronamic_ideal_event_espresso_configuration_id', $configurationId);
		}
		
		// Active
		$isActive = array_key_exists('pronamic_ideal', $active_gateways);

		// Postbox style
		$postbox_style = '';
		if(!$isActive) {
			$postbox_style = 'closed';
		}

		// Configurations
    	$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();
    	$configurationOptions = array('' => __('&mdash; Select configuration &mdash;', 'pronamic_ideal'));
    	foreach($configurations as $configuration) {
    		$configurationOptions[$configuration->getId()] = $configuration->getName();
    	}
		
		$url = add_query_arg('page', 'payment_gateways', admin_url('admin.php'));

		?>
		<div class="metabox-holder">
			<div class="postbox <?php echo $postbox_style; ?>">
				<div title="<?php esc_attr_e('Click to toggle', 'pronamic_ideal'); ?>" class="handlediv"><br /></div>
				<h3 class="hndle">
					<?php _e('Pronamic iDEAL', 'pronamic_ideal'); ?>
				</h3>
				<div class="inside">
					<div class="padding">
						<ul>
							<?php if($isActive): ?>

							<li class="red_alert pointer" onclick="location.href='<?php echo add_query_arg('deactivate_pronamic_ideal', true, $url); ?>';" style="width:30%;">
								<strong><?php _e('Deactivate Pronamic iDEAL?', 'pronamic_ideal'); ?></strong>
							</li>

							<form method="post" action="">
								<table width="99%" border="0" cellspacing="5" cellpadding="5">
									<tr>
										<td valign="top">
											<ul>
												<li>
													<label for="pronamic_ideal_configuration_field">
														<?php _e('Configuration', 'pronamic_ideal'); ?>
													</label>
													
													<br />
	
													<select id="pronamic_ideal_configuration_field" name="pronamic_ideal_event_espresso_configuration_id">
														<?php 
														
														foreach($configurationOptions as $value => $label) {
															printf(
																'<option value="%s" %s>%s</option>',  
																esc_attr($value) , 
																selected($configurationId, $value, false) ,
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
							
								<?php submit_button(__('Update Settings', 'pronamic_ideal')); ?>
							</form>

							<?php else: ?>

							<li class="green_alert pointer" onclick="location.href='<?php echo add_query_arg('activate_pronamic_ideal', true, $url); ?>';" style="width:30%;">
								<strong><?php _e('Activate Pronamic iDEAL?', 'pronamic_ideal'); ?></strong>
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
	public static function updateStatus( Pronamic_WordPress_IDeal_Payment $payment, $can_redirect = false ) {
		if($payment->getSource() == self::SLUG) {
			$id = $payment->getSourceId();
			$transaction = $payment->transaction;
			$status = $transaction->getStatus();

			$data = Pronamic_EventEspresso_EventEspresso::getPaymentDataByAttendeeId($id);
			$dataProxy = new Pronamic_EventEspresso_IDeal_IDealDataProxy($data);

			$url = $dataProxy->getNormalReturnUrl();

			switch($status) {
				case Pronamic_IDeal_Transaction::STATUS_CANCELLED:
					
					break;
				case Pronamic_IDeal_Transaction::STATUS_EXPIRED:
					
					break;
				case Pronamic_IDeal_Transaction::STATUS_FAILURE:
					
					break;
				case Pronamic_IDeal_Transaction::STATUS_SUCCESS:
	            	$data['payment_status'] = Pronamic_EventEspresso_EventEspresso::PAYMENT_STATUS_COMPLETED;

					break;
				case Pronamic_IDeal_Transaction::STATUS_OPEN:

					break;
				default:
					
					break;
			}
			
			$data = apply_filters( 'filter_hook_espresso_update_attendee_payment_data_in_db', $data );
				
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
	public static function sourceColumn( $text, $payment ) {
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
