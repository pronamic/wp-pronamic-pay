<?php 

$post_id = get_the_ID();

$form_id = get_post_meta( $post_id, '_pronamic_pay_gf_form_id', true );

$form_meta = RGFormsModel::get_form_meta( $form_id );

$feed = new stdClass();
$feed->transactionDescription = get_post_meta( $post_id, '_pronamic_pay_gf_transaction_description', true );
$feed->conditionFieldId       = get_post_meta( $post_id, '_pronamic_pay_gf_condition_field_id', true );
$feed->conditionOperator      = get_post_meta( $post_id, '_pronamic_pay_gf_condition_operator', true );
$feed->conditionValue         = get_post_meta( $post_id, '_pronamic_pay_gf_condition_value', true );
$feed->delayNotificationIds   = get_post_meta( $post_id, '_pronamic_pay_gf_delay_notification_ids', true );
$feed->fields                 = get_post_meta( $post_id, '_pronamic_pay_gf_fields', true );
$feed->userRoleFieldId        = get_post_meta( $post_id, '_pronamic_pay_gf_user_role_field_id', true );

?>

<div id="gf-ideal-feed-editor">
	<?php wp_nonce_field( 'pronamic_pay_save_pay_gf', 'pronamic_pay_nonce' ); ?>
	
	<input id="gf_ideal_gravity_form" name="gf_ideal_gravity_form" value="<?php echo esc_attr( json_encode( $form_meta ) ); ?>" type="hidden" />
	<input id="gf_ideal_feed" name="gf_ideal_feed" value="<?php echo esc_attr( json_encode( $feed ) ); ?>" type="hidden" />

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="_pronamic_pay_gf_form_id">
					<?php _e( 'Gravity Form', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<select id="_pronamic_pay_gf_form_id" name="_pronamic_pay_gf_form_id">
					<option value=""><?php _e( '&mdash; Select a form &mdash;', 'pronamic_ideal' ); ?></option>
	
					<?php foreach ( RGFormsModel::get_forms() as $form ) : ?>
	
						<option value="<?php echo $form->id; ?>" <?php selected( $form_id, $form->id ); ?>>
							<?php echo esc_html( $form->title ); ?>
						</option>
	
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="_pronamic_pay_gf_config_id">
					<?php _e( 'Configuration', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<?php 
				
				$config_id = get_post_meta( $post_id, '_pronamic_pay_gf_config_id', true );

				Pronamic_WordPress_IDeal_Admin::dropdown_configs( array(
					'name'     => '_pronamic_pay_gf_config_id',
					'selected' => $config_id
				) );
				
				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="_pronamic_pay_gf_order_id">
					<?php _e( 'Order ID Prefix', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<?php 
	
				$order_id_prefix = get_post_meta( $post_id, '_pronamic_pay_gf_order_id_prefix', true );
	
				?>
				<input id="_pronamic_pay_gf_order_id_prefix" name="_pronamic_pay_gf_order_id_prefix" value="<?php echo esc_attr( $order_id_prefix ); ?>" type="text" class="regular-text " />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="_pronamic_pay_gf_transaction_description">
					<?php _e( 'Transaction Description', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<?php 
	
				$transaction_description = get_post_meta( $post_id, '_pronamic_pay_gf_transaction_description', true );
	
				?>
				<?php if ( false ) : ?>
					<div>
						<?php GFCommon::insert_variables( array(), 'gf_ideal_transaction_description', true, '', ' ' ); ?>
					</div>
				<?php endif; ?>
	
				<input id="_pronamic_pay_gf_transaction_description" name="_pronamic_pay_gf_transaction_description" value="<?php echo esc_attr( $transaction_description ); ?>" type="text" class="regular-text" />
	
				<span class="description">
					<br />
					<?php _e( 'Maximum number of charachters is 32, you should also consider the use of variables Gravity Forms. An generated description that is longer than 32 characters will be automatically truncated.', 'pronamic_ideal' ); ?>
					<br />
					<?php _e( 'Merge Tag Examples: Entry Id = <code>{entry_id}</code>, Form Id = <code>{form_id}</code>, Form Title = <code>{form_title}</code>', 'pronamic_ideal' ); ?>
				</span>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="gf_ideal_condition_enabled">
					<?php _e( 'Condition', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<?php

				$condition_enabled  = get_post_meta( $post_id, '_pronamic_pay_gf_condition_enabled', true );
				$condition_field_id = get_post_meta( $post_id, '_pronamic_pay_gf_condition_field_id', true );
				$condition_operator = get_post_meta( $post_id, '_pronamic_pay_gf_condition_operator', true );
				$condition_value    = get_post_meta( $post_id, '_pronamic_pay_gf_condition_value', true );
	
				?>
				<div>
					<input id="gf_ideal_condition_enabled" name="_pronamic_pay_gf_condition_enabled" value="true" type="checkbox" <?php checked( $condition_enabled ); ?> />
	
					<label for="gf_ideal_condition_enabled">
						<?php _e( 'Enable', 'pronamic_ideal' ); ?>
					</label>
				</div>
	
				<div id="gf_ideal_condition_config">
					<?php 
					
					// Select field
					$select_field = '<select id="gf_ideal_condition_field_id" name="_pronamic_pay_gf_condition_field_id"></select>';
						
					// Select operator
					$select_operator = '<select id="gf_ideal_condition_operator" name="_pronamic_pay_gf_condition_operator">';
						
					$operators = array(
						'' => '',
						Pronamic_GravityForms_GravityForms::OPERATOR_IS     => __( 'is', 'pronamic_ideal' ),
						Pronamic_GravityForms_GravityForms::OPERATOR_IS_NOT => __( 'is not', 'pronamic_ideal' ),
					);

					foreach ( $operators as $value => $label ) {
						$select_operator .= sprintf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $value ),
							selected( $condition_operator, $value, false ),
							esc_html( $label )
						);
					}
					
					$select_operator .= '</select>';

					// Select value
					$select_value = '<select id="gf_ideal_condition_value" name="_pronamic_pay_gf_condition_value"></select>';
					
					// Print
					printf(
						__( 'Send to gateway if %s %s %s', 'pronamic_ideal' ),
						$select_field,
						$select_operator,
						$select_value
					);
					
					?>
				</div>
	
				<div id="gf_ideal_condition_message">
					<span class="description"><?php _e( 'To create a condition, your form must have a drop down, checkbox or multiple choice field.', 'pronamic_ideal' ); ?></span>
				</div>
			</td>
		</tr>
	</table>
	
	<h4><?php _e( 'Delay', 'pronamic_ideal' ); ?></h4>
	
	<table class="form-table">
		<tr>
			<th scope="row">
				<?php _e( 'Send Notifications Delay', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php 
	
				$delay_notification_ids   = get_post_meta( $post_id, '_pronamic_pay_gf_delay_notification_ids', true );
				if ( ! is_array( $delay_notification_ids ) ) {
					$delay_notification_ids = array();
				}

				$delay_admin_motification = get_post_meta( $post_id, '_pronamic_pay_gf_delay_admin_motification', true );
				$delay_users_motification = get_post_meta( $post_id, '_pronamic_pay_gf_delay_users_motification', true );
	
				?>
	
				<?php if ( version_compare( GFCommon::$version, '1.7', '>=' ) ) : ?>
				
					<input name="gf_ideal_selected_notifications_parent" type="checkbox" class="gf_ideal_delay_notifications" value="1" id="gf_ideal_delay_notifications" <?php checked( ! empty( $delay_notification_ids ) ); ?>/>
	
					<label for="gf_ideal_delay_notifications">
						<?php _e( 'Send notifications only when payment is received.', 'pronamic_ideal' ); ?>
					</label>

					<div class="pronamic-pay-gf-notifications">

						<?php 
						
						$notifications = array();
						if ( isset( $form_meta['notifications'] ) && is_array( $form_meta['notifications'] ) ) {
							$notifications = $form_meta['notifications'];
						}

						if ( ! empty( $notifications ) ) {
							printf( '<ul>' ); 
							
							foreach ( $notifications as $notification ) {
								$id = $notification['id'];

								printf( '<li>' );

								printf(
									 '<input id="%s" type="checkbox" value="%s" name="_pronamic_pay_gf_delay_notification_ids[]" %s />',
									esc_attr( 'pronamic-pay-gf-notification-' . $id ),
									esc_attr( $id ),
									checked( in_array( $id, $delay_notification_ids ), true, false )
								);
								
								printf( ' ' );

								printf(
									'<label class="inline" for="%s">%s</label>',
									esc_attr( 'pronamic-pay-gf-notification-' . $id ),
									$notification['name']
								);

								printf( '</li>' );
							}
							
							printf( '</ul>' );
						}
						
						?>
					</div>
	
				<?php else : ?>
	
					<ul>
						<li id="gf_ideal_delay_admin_notification_item">
							<input type="checkbox" name="gf_ideal_delay_admin_notification" id="gf_ideal_delay_admin_notification" value="true" <?php checked( $delay_admin_motification ); ?> />
	
							<label for="gf_ideal_delay_admin_notification">
								<?php _e( 'Send admin notification only when payment is received.', 'pronamic_ideal' ); ?>
							</label>
						</li>
						<li id="gf_ideal_delay_user_notification_item">
							<input type="checkbox" name="gf_ideal_delay_user_notification" id="gf_ideal_delay_user_notification" value="true" <?php checked( $delay_users_motification ); ?> />
	
							<label for="gf_ideal_delay_user_notification">
								<?php _e( 'Send user notification only when payment is received.', 'pronamic_ideal' ); ?>
							</label>
						</li>
						<li id="gf_ideal_delay_post_creation_item">

						</li>
					</ul>
					
				<?php endif; ?>
	
			</td>
		</tr>
		<tr>
			<?php 

			$delay_post_creation = get_post_meta( $post_id, '_pronamic_pay_gf_delay_post_creation', true );
			
			?>
			<th scope="row">
				<?php _e( 'Create Post Delay', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<input type="checkbox" name="_pronamic_pay_gf_delay_post_creation" id="_pronamic_pay_gf_delay_post_creation" value="true" <?php checked( $delay_post_creation ); ?> />
	
				<label for="_pronamic_pay_gf_delay_post_creation">
					<?php _e( 'Create post only when payment is received.', 'pronamic_ideal' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<?php 

			$delay_campaignmonitor_subscription = get_post_meta( $post_id, '_pronamic_pay_gf_delay_campaignmonitor_subscription', true );
			
			?>
			<th scope="row">
				<?php _e( 'Campaign Monitor Subscription Delay', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<input type="checkbox" name="_pronamic_pay_gf_delay_campaignmonitor_subscription" id="_pronamic_pay_gf_delay_campaignmonitor_subscription" value="true" <?php checked( $delay_campaignmonitor_subscription ); ?> />
	
				<label for="_pronamic_pay_gf_delay_campaignmonitor_subscription">
					<?php _e( 'Subscribe user to Campaign Monitor only when payment is received.', 'pronamic_ideal' ); ?>
				</label>
			</td>
		</tr> 
		<tr>
			<?php 

			$delay_mailchimp_subscription = get_post_meta( $post_id, '_pronamic_pay_gf_delay_mailchimp_subscription', true );
			
			?>
			<th scope="row">
				<?php _e( 'MailChimp Subscription Delay', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<input type="checkbox" name="_pronamic_pay_gf_delay_mailchimp_subscription" id="_pronamic_pay_gf_delay_mailchimp_subscription" value="true" <?php checked( $delay_mailchimp_subscription ); ?> />
	
				<label for="_pronamic_pay_gf_delay_mailchimp_subscription">
					<?php _e( 'Subscribe user to MailChimp only when payment is received.', 'pronamic_ideal' ); ?>
				</label>
			</td>
		</tr> 
	</table>
	
	<h4>
		<?php _e( 'Fields', 'pronamic_ideal' ); ?>
	</h4>
	
	<?php 
	
	$fields = array(
		'first_name' => __( 'First Name', 'pronamic_ideal' ),
		'last_name'  => __( 'Last Name', 'pronamic_ideal' ),
		'email'      => __( 'Email', 'pronamic_ideal' ),
		'address1'   => __( 'Address', 'pronamic_ideal' ),
		'address2'   => __( 'Address 2', 'pronamic_ideal' ),
		'city'       => __( 'City', 'pronamic_ideal' ),
		'state'      => __( 'State', 'pronamic_ideal' ),
		'zip'        => __( 'Zip', 'pronamic_ideal' ),
		'country'    => __( 'Country', 'pronamic_ideal' ),
	);
	
	?>
	
	<table class="form-table">
		
		<?php foreach ( $fields as $name => $label ) : ?>
		
			<tr>
				<th scope="row">
					<?php echo $label; ?>
				</th>
				<td>
					<?php 
					
					printf(
						'<select id="%s" name="%s" data-gateway-field-name="%s" class="field-select"><select>',
						esc_attr( 'gf_ideal_fields_' . $name ),
						esc_attr( '_pronamic_pay_gf_fields[' . $name . ']' ),
						esc_attr( $name )
					);
					
					?>
				</td>
			</tr>
		
		<?php endforeach; ?>
	
	</table>
	
	<h4>
		<?php _e( 'Status Links', 'pronamic_ideal' ); ?>
	</h4>
	
	<table class="form-table">
		<?php 
		
		$links = get_post_meta( $post_id, '_pronamic_pay_gf_links', true );
		$links = is_array( $links ) ? $links : array();

		$fields = array(
			Pronamic_WP_Pay_GravityForms_Links::OPEN    => __( 'Open', 'pronamic_ideal' ),
			Pronamic_WP_Pay_GravityForms_Links::SUCCESS => __( 'Success', 'pronamic_ideal' ),
			Pronamic_WP_Pay_GravityForms_Links::CANCEL  => __( 'Cancel', 'pronamic_ideal' ),
			Pronamic_WP_Pay_GravityForms_Links::ERROR   => __( 'Error', 'pronamic_ideal' ),
			Pronamic_WP_Pay_GravityForms_Links::EXPIRED => __( 'Expired', 'pronamic_ideal' ),
		);
	
		foreach ( $fields as $name => $label ) : ?>
	
			<tr>
				<?php

				$type    = @$links[$name]['type'];
				$page_id = @$links[$name]['page_id'];
				$url     = @$links[$name]['url'];

				?>
				<th scope="row">
					<label for="gf_ideal_link_<?php echo $name; ?>_page">
						<?php echo $label; ?>
					</label>
				</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php echo $label; ?></span>
						</legend>
	
						<label>
							<input type="radio" name="_pronamic_pay_gf_links[<?php echo $name; ?>][type]" id="gf_ideal_link_<?php echo $name; ?>_page" value="page" <?php checked( $type, 'page' ); ?> />
							<?php _e( 'Page:', 'pronamic_ideal' ); ?>
						</label> 
						
						<?php 

						wp_dropdown_pages( array(
							'selected'         => $page_id, 
							'name'             => '_pronamic_pay_gf_links[' . $name . '][page_id]' , 
							'show_option_none' => __( '&mdash; Select &mdash;', 'pronamic_ideal' )
						) );
						
						?> 
	
						<br />
	
						<label>
							<input type="radio" name="_pronamic_pay_gf_links[<?php echo $name; ?>][type]" id="gf_ideal_link_<?php echo $name; ?>_url" value="url" <?php checked( $type, 'url' ); ?> />
							<?php _e( 'URL:', 'pronamic_ideal' ); ?>
						</label> <input type="text" name="_pronamic_pay_gf_links[<?php echo $name; ?>][url]" value="<?php echo esc_attr( $url ); ?>" class="regular-text" /> 
					</fieldset>
				<td>
			</tr>
	
		<?php endforeach; ?>
	</table>
	
	<h4>
		<?php _e( 'Advanced', 'pronamic_ideal' ); ?>
	</h4>
	
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="gf_ideal_user_role_field_id">
					<?php _e( 'Update user role', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<select id="gf_ideal_user_role_field_id" name="_pronamic_pay_gf_user_role_field_id">
					
				</select>
			</td>
		</tr>
	</table>
</div>