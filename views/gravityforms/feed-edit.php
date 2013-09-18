<?php 

/*

if ( empty( $_POST ) ) {
	$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );
} else {
	$id = filter_input( INPUT_POST, 'pronamic_ideal_gf_id', FILTER_SANITIZE_STRING );
}

$feed = Pronamic_GravityForms_IDeal_FeedsRepository::getFeedById( $id, false );
if ( $feed == null ) {
	$feed = new Pronamic_GravityForms_IDeal_Feed();
}

$updated = null;
if ( ! empty( $_POST ) && check_admin_referer( 'pronamic_ideal_save_gf_feed', 'pronamic_ideal_nonce' ) ) {
	$feed->formId = filter_input( INPUT_POST, 'gf_ideal_form_id', FILTER_SANITIZE_STRING );
	
	$configurationId = filter_input( INPUT_POST, 'gf_ideal_configuration_id', FILTER_SANITIZE_STRING );
	$feed->setIDealConfiguration( Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configurationId ) );

	$feed->transactionDescription = filter_input( INPUT_POST, 'gf_ideal_transaction_description', FILTER_SANITIZE_STRING );
	
	$selected_notifications_parent = filter_input( INPUT_POST, 'gf_ideal_selected_notifications_parent', FILTER_VALIDATE_BOOLEAN );
	
	if ( true === $selected_notifications_parent ) {
		$selected_notifications = ( isset( $_POST['gf_ideal_selected_notifications'] ) ? $_POST['gf_ideal_selected_notifications'] : array() );
		
		if ( ! empty( $selected_notifications ) ) {
			$feed->removeDelayNotifications();
			
			foreach ( $selected_notifications as $selected_notification ) {
				$feed->addDelayNotification( $selected_notification );
			}
		} else {
			$feed->removeDelayNotifications();
		}
	} else {
		$feed->removeDelayNotifications();
	}
	
	$feed->delayAdminNotification = filter_input( INPUT_POST, 'gf_ideal_delay_admin_notification', FILTER_VALIDATE_BOOLEAN );
	$feed->delayUserNotification  = filter_input( INPUT_POST, 'gf_ideal_delay_user_notification', FILTER_VALIDATE_BOOLEAN );
	$feed->delayPostCreation      = filter_input( INPUT_POST, 'gf_ideal_delay_post_creation', FILTER_VALIDATE_BOOLEAN );

	$feed->conditionEnabled  = filter_input( INPUT_POST, 'gf_ideal_condition_enabled', FILTER_VALIDATE_BOOLEAN );
	$feed->conditionFieldId  = filter_input( INPUT_POST, 'gf_ideal_condition_field_id', FILTER_SANITIZE_STRING );
	$feed->conditionOperator = filter_input( INPUT_POST, 'gf_ideal_condition_operator', FILTER_SANITIZE_STRING );
	$feed->conditionValue    = filter_input( INPUT_POST, 'gf_ideal_condition_value', FILTER_SANITIZE_STRING );

	$feed->userRoleFieldId = filter_input( INPUT_POST, 'gf_ideal_user_role_field_id', FILTER_SANITIZE_STRING );

	// Links
	$links = filter_input( INPUT_POST, 'gf_ideal_links', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );

	foreach ( $links as $name => $data ) {
		$link = new stdClass();

		if ( isset( $data['type'] ) ) {
			$link->type = $data['type'];
		}

		$link->pageId = $data['page_id'];
		$link->url    = $data['url'];

		$feed->setLink( $name, $link );
	}

	// Fields
	$feed->fields = filter_input( INPUT_POST, 'gf_ideal_fields', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );

	// Update
	$updated = Pronamic_GravityForms_IDeal_FeedsRepository::updateFeed( $feed );
}

*/

$post_id = get_the_ID();

?>

<table class="form-table">
	<tr>
		<th scope="row">
			<label for="gf_ideal_form_id">
				<?php _e( 'Gravity Form', 'pronamic_ideal' ); ?>
			</label>
		</th>
		<td>
			<?php 

			$form_id = get_post_meta( $post_id, '_pronamic_pay_gf_form_id', true );
			$forms = RGFormsModel::get_forms();
			$data = RGFormsModel::get_form_meta( $post_id );

			?>

			<input id="gf_ideal_gravity_form" name="gf_ideal_gravity_form" value="<?php echo esc_attr( json_encode( $data ) ); ?>" type="hidden" />
			<input id="gf_ideal_feed" name="gf_ideal_feed" value="<?php echo esc_attr( json_encode( $feed ) ); ?>" type="hidden" />
				
			<select id="gf_ideal_form_id" name="gf_ideal_form_id">
				<option value=""><?php _e( '&mdash; Select a form &mdash;', 'pronamic_ideal' ); ?></option>

				<?php foreach ( $forms as $form ) : ?>

					<option value="<?php echo $form->id; ?>" <?php selected( $form_id, $form->id ); ?>>
						<?php echo esc_html( $form->title ); ?>
					</option>

				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="gf_ideal_form_id">
				<?php _e( 'Configuration', 'pronamic_ideal' ); ?>
			</label>
		</th>
		<td>
			<?php 
			
			$configuration_id = get_post_meta( $post_id, '_pronamic_pay_gf_configuration_id', true );
			
			?>
			<select id="gf_ideal_configuration_id" name="gf_ideal_configuration_id">
				<option value=""><?php _e( '&mdash; Select configuration &mdash; ', 'pronamic_ideal' ); ?></option>

				<?php

				$configurations = get_posts( array(
					'post_type' => 'pronamic_gateway',
					'nopaging'  => true
				) );

				foreach ( $configurations as $configuration ) : ?>

					<option value="<?php echo $configuration->ID; ?>" <?php selected( $configuration_id, $configuration->ID ); ?>>
						<?php echo get_the_title( $configuration->ID ); ?>
					</option>

				<?php endforeach; ?>

			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="gf_ideal_transaction_description">
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

			<input id="gf_ideal_transaction_description" name="gf_ideal_transaction_description" value="<?php echo esc_attr( $transaction_description ); ?>" type="text" class="regular-text" />

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
			<?php _e( 'Options', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php 

			$delay_notification_ids   = get_post_meta( $post_id, '_pronamic_pay_gf_delay_notification_ids', true );
			$delay_admin_motification = get_post_meta( $post_id, '_pronamic_pay_gf_delay_admin_motification', true );
			$delay_users_motification = get_post_meta( $post_id, '_pronamic_pay_gf_delay_users_motification', true );
			$delay_post_creation      = get_post_meta( $post_id, '_pronamic_pay_gf_delay_post_creation', true );

			?>

			<?php if ( version_compare( GFCommon::$version, '1.7', '>=' ) ) : ?>
			
				<input name="gf_ideal_selected_notifications_parent" type="checkbox" class="gf_ideal_delay_notifications" value="1" id="gf_ideal_delay_notifications" <?php checked( ! empty( $delay_notification_ids ) ); ?>/>

				<label for="gf_ideal_delay_notifications">
					<?php _e( 'Send notifications only when payment is received.', 'pronamic_ideal' ); ?>
				</label>

				<ul class="gf_ideal_delay_notification_holder" style="margin-left:28px;<?php if ( empty( $delay_notification_ids ) ) : ?> display:none; <?php endif; ?>">
					
					<?php if ( ! empty( $delay_notification_ids ) ) : ?>
					
						<?php $form = RGFormsModel::get_form_meta( $feed->form_id ); ?>

						<?php if ( is_array( $form['notifications'] ) && ! empty( $form['notifications'] ) ) : ?>
					
							<?php foreach ( $form['notifications'] as $notification ) : ?>
								<li>
									<input id="gf_ideal_selected_notification_<?php echo $notification['id']; ?>" type="checkbox" value="<?php echo $notification['id']; ?>" name="gf_ideal_selected_notifications[]" <?php checked ( in_array( $notification['id'], $delay_notification_ids ) ); ?> />
									<label class="inline" for="gf_ideal_selected_notification_<?php echo $notification['id']; ?>"><?php echo $notification['name']; ?></label>
								</li>
							<?php endforeach; ?>
					
						<?php endif; ?>

					<?php endif; ?>

					<?php if ( empty( $delay_notification_ids ) ) : ?>
						<li><img src="<?php echo plugins_url( 'images/loading.gif', Pronamic_WordPress_IDeal_Plugin::$file ); ?>" /></li>
					<?php endif;?>
				</ul>

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
						<input type="checkbox" name="gf_ideal_delay_post_creation" id="gf_ideal_delay_post_creation" value="true" <?php checked( $delay_post_creation ); ?> />

						<label for="gf_ideal_delay_post_creation">
							<?php _e( 'Create post only when payment is received.', 'pronamic_ideal' ); ?>
						</label>
					</li>
				</ul>
				
			<?php endif; ?>

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
				<input id="gf_ideal_condition_enabled" name="gf_ideal_condition_enabled" value="true" type="checkbox" <?php checked( $condition_enabled ); ?> />

				<label for="gf_ideal_condition_enabled">
					<?php _e( 'Enable', 'pronamic_ideal' ); ?>
				</label>
			</div>

			<div id="gf_ideal_condition_config">
				<?php _e( 'Send to gateway if ', 'pronamic_ideal' ); ?>

				<select id="gf_ideal_condition_field_id" name="gf_ideal_condition_field_id">

				</select>

				<?php 
				
				$operators = array(
					'' => '',
					Pronamic_GravityForms_GravityForms::OPERATOR_IS     => __( 'is', 'pronamic_ideal' ),
					Pronamic_GravityForms_GravityForms::OPERATOR_IS_NOT => __( 'is not', 'pronamic_ideal' ) 
				);
				
				?>
				<select id="gf_ideal_condition_operator" name="gf_ideal_condition_operator">
					<?php foreach ( $operators as $value => $label ) : ?>

						<option value="<?php echo $value; ?>" <?php selected( $condition_operator, $value ); ?>>
							<?php echo $label; ?>
						</option>
					
					<?php endforeach; ?>
				</select>

				<select id="gf_ideal_condition_value" name="gf_ideal_condition_value">
					
				</select>
			</div>

			<div id="gf_ideal_condition_message">
				<span class="description"><?php _e( 'To create a condition, your form must have a drop down, checkbox or multiple choice field.', 'pronamic_ideal' ); ?></span>
			</div>
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
				<select id="gf_ideal_fields_<?php echo $name; ?>" name="gf_ideal_fields[<?php echo $name; ?>]" data-gateway-field-name="<?php echo $name; ?>" class="field-select">
					
				</select>
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

	$fields = array(
		Pronamic_GravityForms_IDeal_Feed::LINK_OPEN    => __( 'Open', 'pronamic_ideal' ),
		Pronamic_GravityForms_IDeal_Feed::LINK_SUCCESS => __( 'Success', 'pronamic_ideal' ),
		Pronamic_GravityForms_IDeal_Feed::LINK_CANCEL  => __( 'Cancel', 'pronamic_ideal' ),
		Pronamic_GravityForms_IDeal_Feed::LINK_ERROR   => __( 'Error', 'pronamic_ideal' ),
		Pronamic_GravityForms_IDeal_Feed::LINK_EXPIRED => __( 'Expired', 'pronamic_ideal' )
	);

	foreach ( $fields as $name => $label ) : ?>

		<tr>
			<?php 

			$link    = $links[$name];
			$type    = null;
			$page_id = null;
			$url     = null;
			
			if ( $link != null ) {
				if ( isset( $link->type ) ) {
					$type = $link->type;
				}

				if ( isset( $link->pageId ) ) {
					$page_id = $link->pageId;
				}

				if ( isset( $link->url ) ) {
					$url = $link->url;
				}
			}
			
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
						<input type="radio" name="gf_ideal_links[<?php echo $name; ?>][type]" id="gf_ideal_link_<?php echo $name; ?>_page" value="page" <?php checked( $type, 'page' ); ?> />
						<?php _e( 'Page:', 'pronamic_ideal' ); ?>
					</label> 
					
					<?php 

					wp_dropdown_pages( array(
						'selected'         => $page_id, 
						'name'             => 'gf_ideal_links[' . $name . '][page_id]' , 
						'show_option_none' => __( '&mdash; Select &mdash;', 'pronamic_ideal' )
					) );
					
					?> 

					<br />

					<label>
						<input type="radio" name="gf_ideal_links[<?php echo $name; ?>][type]" id="gf_ideal_link_<?php echo $name; ?>_url" value="url" <?php checked( $type, 'url' ); ?> />
						<?php _e( 'URL:', 'pronamic_ideal' ); ?>
					</label> <input type="text" name="gf_ideal_links[<?php echo $name; ?>][url]" value="<?php echo esc_attr( $url ); ?>" class="regular-text" /> 
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
			<select id="gf_ideal_user_role_field_id" name="gf_ideal_user_role_field_id">
				
			</select>
		</td>
	</tr>
</table>