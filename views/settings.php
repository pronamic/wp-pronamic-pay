<?php 

if(!empty($_POST) && check_admin_referer('pronamic_ideal_save_settings', 'pronamic_ideal_nonce')) {
	$key = filter_input(INPUT_POST, 'pronamic_ideal_key', FILTER_SANITIZE_STRING);

	Pronamic_WordPress_IDeal_Plugin::setKey($key);
}

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Settings', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<form action="" method="post">
		<?php wp_nonce_field('pronamic_ideal_save_settings', 'pronamic_ideal_nonce'); ?>

		<h3>
			<?php _e('General Settings', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="pronamic_ideal_key">
						<?php _e('Support License Key', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
	                <input id="pronamic_ideal_key" name="pronamic_ideal_key" value="<?php echo Pronamic_WordPress_IDeal_Plugin::getKey(); ?>" class="regular-text" type="password" />

					<?php if(Pronamic_WordPress_IDeal_Plugin::hasValidKey()): ?>
					&#10003;
					<?php endif; ?>

					<span class="description">
						<br />
						<?php _e('The license key is used for access to automatic upgrades and support.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</span>
				</td>
			</tr>
		</table>

		<h3>
			<?php _e('Installation Status', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>

		<?php global $wpdb; ?>

		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('PHP Version', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td class="column-version">
	                <?php echo phpversion(); ?>
				</td>
				<td>
					<?php 
					
					if(version_compare(phpversion(), '5.2', '>')) {
						echo '&#10003;';
					} else {
						_e('Pronamic iDEAL requires PHP 5.2 or above.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
					}
					
					?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('MySQL Version', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td class="column-version">
	                <?php echo $wpdb->db_version(); ?>
				</td>
				<td>
					<?php 

					if(version_compare($wpdb->db_version(), '5', '>')) {
						echo '&#10003;';
					} else {
						_e('Pronamic iDEAL requires MySQL 5 or above.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
					}

					?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('WordPress Version', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td class="column-version">
	                <?php echo get_bloginfo('version'); ?>
				</td>
				<td>
					<?php 
					
					if(version_compare(get_bloginfo('version'), '3.2', '>')) {
						echo '&#10003;';
					} else {
						_e('Pronamic iDEAL requires WordPress 3.2 or above.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
					}
					
					?>
				</td>
			</tr>
		</table>

		<?php 
		
		submit_button(
			empty($configuration->id) ? __('Save', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) : __('Update', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			'primary' 
		);

		?>
	</form>
</div>