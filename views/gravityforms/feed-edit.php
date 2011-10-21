<?php 

if(empty($_POST)) {
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
} else {
	$id = filter_input(INPUT_POST, 'pronamic_ideal_gf_id', FILTER_SANITIZE_STRING);
}

$feed = Pronamic_GravityForms_IDeal_FeedsRepository::getFeedById($id, false);
if($feed == null) {
	$feed = new Pronamic_GravityForms_IDeal_Feed();
}

$updated = null;
if(!empty($_POST) && check_admin_referer('pronamic_ideal_save_gf_feed', 'pronamic_ideal_nonce')) {
	$feed->formId = filter_input(INPUT_POST, 'gf_ideal_form_id', FILTER_SANITIZE_STRING);
	
	$configurationId = filter_input(INPUT_POST, 'gf_ideal_configuration_id', FILTER_SANITIZE_STRING);
	$feed->setIDealConfiguration(Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($configurationId));

	$feed->transactionDescription = filter_input(INPUT_POST, 'gf_ideal_transaction_description', FILTER_SANITIZE_STRING);
	
	$feed->conditionEnabled = filter_input(INPUT_POST, 'gf_ideal_condition_enabled', FILTER_VALIDATE_BOOLEAN);
	$feed->conditionFieldId = filter_input(INPUT_POST, 'gf_ideal_condition_field_id', FILTER_SANITIZE_STRING);
	$feed->conditionOperator = filter_input(INPUT_POST, 'gf_ideal_condition_operator', FILTER_SANITIZE_STRING);
	$feed->conditionValue = filter_input(INPUT_POST, 'gf_ideal_condition_value', FILTER_SANITIZE_STRING);

	$feed->userRoleFieldId = filter_input(INPUT_POST, 'gf_ideal_user_role_field_id', FILTER_SANITIZE_STRING);

	$links = filter_input(INPUT_POST, 'gf_ideal_links', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

	foreach($links as $name => $data) {
		$link = new stdClass();

		if(isset($data['type'])) {
			$link->type = $data['type'];
		}

		$link->pageId = $data['page_id'];
		$link->url = $data['url'];

		$feed->setLink($name, $link);
	}

	$updated = Pronamic_GravityForms_IDeal_FeedsRepository::updateFeed($feed);
}

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Feed', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<?php if($updated): ?>
	
	<div class="updated inline below-h2">
		<p>
			<?php 
			
			printf(
				__('Feed updated, %s.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
				'<a href="?page=gf_pronamic_ideal">' . __('back to list', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) . '</a>'
			);

			?>
		</p>
	</div>

	<?php endif; ?>

	<form id="gf-ideal-feed-editor" method="post" action="">
		<?php wp_nonce_field('pronamic_ideal_save_gf_feed', 'pronamic_ideal_nonce'); ?>
		<input name="pronamic_ideal_gf_id" value="<?php echo esc_attr($feed->getId()); ?>" type="hidden" />

		<h3>
			<?php _e('General', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="gf_ideal_form_id">
						<?php _e('Gravity Form', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
					<?php $data = RGFormsModel::get_form_meta($feed->formId); ?>

					<input id="gf_ideal_gravity_form" name="gf_ideal_gravity_form" value="<?php echo esc_attr(json_encode($data)); ?>" type="hidden" />
					<input id="gf_ideal_feed" name="gf_ideal_feed" value="<?php echo esc_attr(json_encode($feed)); ?>" type="hidden" />
					
	                <select id="gf_ideal_form_id" name="gf_ideal_form_id">
	                    <option value=""><?php _e('&mdash; Select a form &mdash;', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></option>

	                    <?php foreach(Pronamic_GravityForms_IDeal_FeedsRepository::getAvailableForms($feed->formId) as $form): ?>

						<option value="<?php echo $form->id; ?>" <?php selected($feed->formId, $form->id); ?>>
							<?php echo esc_html($form->title) ?>
						</option>
	
						<?php endforeach; ?>
	                </select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="gf_ideal_configuration_id">
						<?php _e('iDEAL Configuration', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
					<?php $configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations(); ?>
					
					<?php if(empty($configurations)):

					printf(
						__('No iDEAL configurations found, %s.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
						sprintf(
							'<a href="%s">%s</a>' , 
							Pronamic_WordPress_IDeal_Admin::getConfigurationEditLink() ,
							__('create an iDEAL configuration', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN)
						)
					);
					
					else: ?>

					<?php $iDealConfigurationId = $feed->getIDealConfiguration() == null ? '' : $feed->getIDealConfiguration()->getId(); ?>

	                <select id="gf_ideal_configuration_id" name="gf_ideal_configuration_id">
	                    <option value=""><?php _e('&mdash; Select configuration &mdash; ', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></option>
	                    <?php foreach($configurations as $configuration): ?>
						<option data-ideal-method="<?php echo $configuration->getVariant()->getMethod(); ?>" value="<?php echo $configuration->getId(); ?>" <?php selected($iDealConfigurationId, $configuration->getId()); ?>>
							<?php echo esc_html($configuration->getName()); ?>
						</option>
						<?php endforeach; ?>
	                </select>

					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="gf_ideal_transaction_description">
						<?php _e('Transaction Description', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
					<?php if(false): ?>
					<div>
						<?php GFCommon::insert_variables(array(), 'gf_ideal_transaction_description', true, '', ' '); ?>
					</div>
					<?php endif; ?>

					<input id="gf_ideal_transaction_description" name="gf_ideal_transaction_description" value="<?php echo esc_attr($feed->transactionDescription); ?>" maxlength="32" type="text" class="regular-text" />

					<span class="description">
						<br />
						<?php _e('Maximum number of charachters is 32, you should also consider the use of variables Gravity Forms. An generated description that is longer than 32 characters will be automatically truncated.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="gf_ideal_condition_enabled">
						<?php _e('iDEAL Condition', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
					<div>
						<input id="gf_ideal_condition_enabled" name="gf_ideal_condition_enabled" value="true" type="checkbox" <?php checked($feed->conditionEnabled); ?> />
	
						<label for="gf_ideal_condition_enabled">
							<?php _e('Enable', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</label>
					</div>

					<div id="gf_ideal_condition_config">
						<?php _e('Send to iDEAL if ', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	
	              		<select id="gf_ideal_condition_field_id" name="gf_ideal_condition_field_id">
	
						</select>
	
						<?php 
						
						$operators = array(
							'' => '' , 
							Pronamic_GravityForms_GravityForms::OPERATOR_IS => __('is', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
							Pronamic_GravityForms_GravityForms::OPERATOR_IS_NOT => __('is not', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) 
						);
						
						?>
	              		<select id="gf_ideal_condition_operator" name="gf_ideal_condition_operator">
	              			<?php foreach($operators as $value => $label): ?>

							<option value="<?php echo $value; ?>" <?php selected($feed->conditionOperator, $value); ?>>
								<?php echo $label; ?>
							</option>
							
							<?php endforeach; ?>
						</select>
	
						<select id="gf_ideal_condition_value" name="gf_ideal_condition_value">
							
						</select>
					</div>

					<div id="gf_ideal_condition_message">
						<span class="description"><?php _e('To create a condition, your form must have a drop down, checkbox or multiple choice field.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></span>
					</div>
				</td>
			</tr>                                   
		</table>
		
		<h3>
			<?php _e('Status Links', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>

		<table class="form-table">
			<?php 
			
			$fields = array(
				Pronamic_GravityForms_IDeal_Feed::LINK_OPEN => __('Open', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
				Pronamic_GravityForms_IDeal_Feed::LINK_SUCCESS => __('Success', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
				Pronamic_GravityForms_IDeal_Feed::LINK_CANCEL => __('Cancel', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
				Pronamic_GravityForms_IDeal_Feed::LINK_ERROR => __('Error', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
				Pronamic_GravityForms_IDeal_Feed::LINK_EXPIRED => __('Expired', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) 
			);
			
			foreach($fields as $name => $label): ?>

			<tr>
				<?php 
				
				$link = $feed->getLink($name);
				$type = null;
				$pageId = null;
				$url = null;
				
				if($link != null) {
					if(isset($link->type)) {
						$type = $link->type;
					}
					if(isset($link->pageId)) {
						$pageId = $link->pageId;
					}
					if(isset($link->url)) {
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
							<input type="radio" name="gf_ideal_links[<?php echo $name; ?>][type]" id="gf_ideal_link_<?php echo $name; ?>_page" value="page" <?php checked($type, 'page'); ?> />
							<?php _e('Page:', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</label> 
						
						<?php 

						wp_dropdown_pages(array(
							'selected' => $pageId , 
							'name' => 'gf_ideal_links[' . $name . '][page_id]' , 
							'show_option_none' => __('&mdash; Select &mdash;', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN)
						));
						
						?> 

						<br />

						<label>
							<input type="radio" name="gf_ideal_links[<?php echo $name; ?>][type]" id="gf_ideal_link_<?php echo $name; ?>_url" value="url" <?php checked($type, 'url'); ?> />
							<?php _e('URL:', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</label> <input type="text" name="gf_ideal_links[<?php echo $name; ?>][url]" value="<?php echo esc_attr($url); ?>" class="regular-text" /> 
					</fieldset>
				<td>
			</tr>

			<?php endforeach; ?>
		</table>

		<div class="extra-settings method-advanced">
			<h3>
				<?php _e('Advanced', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
			</h3>
	
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="gf_ideal_user_role_field_id">
							<?php _e('Update user role', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</label>
					</th>
					<td>
						<select id="gf_ideal_user_role_field_id" name="gf_ideal_user_role_field_id">
							
						</select>
					</td>
				</tr>
			</table>
		</div>

		<?php submit_button(empty($feed->id) ? __('Save', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) : __('Update', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN)); ?>
	</form>
</div>