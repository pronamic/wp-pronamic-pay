<?php 

if(empty($_POST)) {
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
} else {
	$id = filter_input(INPUT_POST, 'pronamic_ideal_configuration_id', FILTER_SANITIZE_STRING);
}

$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($id);
if($configuration == null) {
	$configuration = new Pronamic_WordPress_IDeal_Configuration();
}

$update = null;
$error = null;

if(!empty($_POST) && check_admin_referer('pronamic_ideal_save_configuration', 'pronamic_ideal_nonce')) {
	$variantId = filter_input(INPUT_POST, 'pronamic_ideal_variant_id', FILTER_SANITIZE_STRING);
	$variant = Pronamic_WordPress_IDeal_ConfigurationsRepository::getVariantById($variantId);
	
	$configuration->setVariant($variant);
	$configuration->merchantId = filter_input(INPUT_POST, 'pronamic_ideal_merchant_id', FILTER_SANITIZE_STRING);
	$configuration->subId = filter_input(INPUT_POST, 'pronamic_ideal_sub_id', FILTER_SANITIZE_STRING);
	$configuration->mode = filter_input(INPUT_POST, 'pronamic_ideal_mode', FILTER_SANITIZE_STRING);

	// Basic
	$configuration->hashKey = filter_input(INPUT_POST, 'pronamic_ideal_hash_key', FILTER_SANITIZE_STRING);
	
	// Advanced
	if($_FILES['pronamic_ideal_private_key']['error'] == UPLOAD_ERR_OK) {
		$configuration->privateKey = file_get_contents($_FILES['pronamic_ideal_private_key']['tmp_name']);
	}

	$configuration->privateKeyPassword = filter_input(INPUT_POST, 'pronamic_ideal_private_key_password', FILTER_SANITIZE_STRING);

	if($_FILES['pronamic_ideal_private_certificate']['error'] == UPLOAD_ERR_OK) {
		$configuration->privateCertificate = file_get_contents($_FILES['pronamic_ideal_private_certificate']['tmp_name']);
	}

	// Update
	$updated = Pronamic_WordPress_IDeal_ConfigurationsRepository::updateConfiguration($configuration);

	// Transient
	Pronamic_WordPress_IDeal_IDeal::deleteConfigurationTransient($configuration);

	if($updated) {
		$update = sprintf(
			__('Configuration updated, %s.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			sprintf('<a href="%s">', Pronamic_WordPress_IDeal_Admin::getConfigurationsLink()) . __('back to overview', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) . '</a>'
		);
	}
}

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Configuration', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<?php if($update): ?>
	
	<div class="updated inline below-h2">
		<p><?php echo $update; ?></p>
	</div>

	<?php endif; ?>

	<?php if($error): ?>
	
	<div class="error inline below-h2">
		<p><?php echo $error; ?></p>
	</div>

	<?php endif; ?>

	<form id="pronamic-ideal-configration-editor" enctype="multipart/form-data" action="" method="post">
		<?php wp_nonce_field('pronamic_ideal_save_configuration', 'pronamic_ideal_nonce'); ?>
		<input name="pronamic_ideal_configuration_id" value="<?php echo esc_attr($configuration->getId()); ?>" type="hidden" />

		<h3>
			<?php _e('General', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="pronamic_ideal_variant_id">
						<?php _e('Variant', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
					<?php $variantId = $configuration->getVariant() == null ? '' : $configuration->getVariant()->getId(); ?>
	                <select id="pronamic_ideal_variant_id" name="pronamic_ideal_variant_id">
	                	<option value=""></option>
	                	<?php foreach(Pronamic_WordPress_IDeal_ConfigurationsRepository::getProviders() as $provider): ?>
						<optgroup label="<?php echo $provider->getName(); ?>">
							<?php foreach($provider->getVariants() as $variant): ?>
							<option data-ideal-method="<?php echo $variant->getMethod(); ?>" value="<?php echo $variant->getId(); ?>" <?php selected($variantId, $variant->getId()); ?>><?php echo $variant->getName(); ?></option>
							<?php endforeach; ?>
						</optgroup>
						<?php endforeach; ?>
	                </select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="pronamic_ideal_merchant_id">
						<?php _e('Merchant ID', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
	                <input id="pronamic_ideal_merchant_id" name="pronamic_ideal_merchant_id" value="<?php echo $configuration->merchantId; ?>" type="text" />

					<span class="description">
						<br />
						<?php _e('You receive the merchant ID (also known as: acceptant ID) from your iDEAL provider.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="pronamic_ideal_sub_id">
						<?php _e('Sub ID', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
	                <input id="pronamic_ideal_sub_id" name="pronamic_ideal_sub_id" value="<?php echo $configuration->subId; ?>" type="text" />

					<span class="description">
						<br />
						<?php printf(__('You receive the sub ID from your iDEAL provider, the default is: %s.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), 0); ?>
					</span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="pronamic_ideal_mode">
						<?php _e('Mode', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<?php _e('Mode', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</legend>
					
						<p>		
							<label>
								<input type="radio" value="<?php echo Pronamic_IDeal_IDeal::MODE_LIVE; ?>" name="pronamic_ideal_mode" <?php checked($configuration->mode, Pronamic_IDeal_IDeal::MODE_LIVE); ?> />
								<?php _e('Live', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
							</label><br />
			
							<label>
								<input type="radio" value="<?php echo Pronamic_IDeal_IDeal::MODE_TEST; ?>" name="pronamic_ideal_mode" <?php checked($configuration->mode, Pronamic_IDeal_IDeal::MODE_TEST); ?> />
								<?php _e('Test', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
							</label>
						</p>
					</fieldset>
				</td>
			</tr>
		</table>

		<div class="extra-settings method-basic">
			<h3>
				<?php _e('Basic', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
			</h3>
	
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_hash_key">
							<?php _e('Hash Key', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</label>
					</th>
					<td>
						<input id="pronamic_ideal_hash_key" name="pronamic_ideal_hash_key" value="<?php echo $configuration->hashKey; ?>" type="text" />
					</td>
				</tr>
			</table>
		</div>

		<div class="extra-settings method-advanced">
			<h3>
				<?php _e('Advanced', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
			</h3>
	
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_private_key_password">
							<?php _e('Private Key Password', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</label>
					</th>
					<td> 
						<input id="pronamic_ideal_private_key_password" name="pronamic_ideal_private_key_password" value="<?php echo $configuration->privateKeyPassword; ?>" type="text" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_private_key">
							<?php _e('Private Key', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</label>
					</th>
					<td>
						<pre class="security-data"><?php echo $configuration->privateKey; ?></pre>
						<br />
						<input id="pronamic_ideal_private_key" name="pronamic_ideal_private_key" type="file" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_ideal_private_certificate">
							<?php _e('Private Certificate', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
						</label>
					</th>
					<td>
						<pre class="security-data"><?php echo $configuration->privateCertificate; ?></pre>
						<br />
						<?php 
						
						if(!empty($configuration->privateCertificate)) {
							$fingerprint = Pronamic_IDeal_Security::getShaFingerprint($configuration->privateCertificate);
							$fingerprint = str_split($fingerprint, 2);
							$fingerprint = implode(':', $fingerprint);
						
							echo sprintf(__('SHA Fingerprint: %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $fingerprint), '<br />';
						}
	
						?>
						<input id="pronamic_ideal_private_certificate" name="pronamic_ideal_private_certificate" type="file" />
					</td>
				</tr>
			</table>
		</div>

		<?php 
		
		submit_button(
			empty($configuration->id) ? __('Save', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) : __('Update', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			'primary' 
		);

		?>
	</form>
</div>