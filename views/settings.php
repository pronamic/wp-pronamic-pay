<?php 

namespace Pronamic\WordPress\IDeal;

?>
<div class="wrap">
	<?php screen_icon(Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Settings', Plugin::TEXT_DOMAIN); ?>
	</h2>

	<form action="" method="post">
		<?php wp_nonce_field('pronamic_ideal_save_settings', 'pronamic_ideal_nonce'); ?>

		<h3>
			<?php _e('General Settings', Plugin::TEXT_DOMAIN); ?>
		</h3>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="pronamic_ideal_license_key">
						<?php _e('Support License Key', Plugin::TEXT_DOMAIN); ?>
					</label>
				</th>
				<td>
	                <input id="pronamic_ideal_license_key" name="pronamic_ideal_license_key" value="" class="regular-text" type="password" />

					<span class="description">
						<br />
						<?php _e('The license key is used for access to automatic upgrades and support.', Plugin::TEXT_DOMAIN); ?>
					</span>
				</td>
			</tr>
		</table>

		<h3>
			<?php _e('Installation Status', Plugin::TEXT_DOMAIN); ?>
		</h3>

		<?php global $wpdb; ?>

		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('PHP Version', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td class="column-version">
	                <?php echo phpversion(); ?>
				</td>
				<td>
					<?php 
					
					if(version_compare(phpversion(), '5.3', '>')) {
						echo '&#10003;';
					} else {
						_e('Pronamic iDEAL requires PHP 5.3 or above.', Plugin::TEXT_DOMAIN);
					}
					
					?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('MySQL Version', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td class="column-version">
	                <?php echo $wpdb->db_version(); ?>
				</td>
				<td>
					<?php 
					
					if(version_compare($wpdb->db_version(), '5', '>')) {
						echo '&#10003;';
					} else {
						_e('Pronamic iDEAL requires  MySQL 5 or above.', Plugin::TEXT_DOMAIN);
					}
					
					?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('WordPress Version', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td class="column-version">
	                <?php echo get_bloginfo('version'); ?>
				</td>
				<td>
					<?php 
					
					if(version_compare(get_bloginfo('version'), '3.2', '>')) {
						echo '&#10003;';
					} else {
						_e('Pronamic iDEAL requires  WordPress 3.2 or above.', Plugin::TEXT_DOMAIN);
					}
					
					?>
				</td>
			</tr>
		</table>

		<?php 
		
		submit_button(
			empty($configuration->id) ? __('Save', Plugin::TEXT_DOMAIN) : __('Update', Plugin::TEXT_DOMAIN) , 
			'primary' 
		);

		?>
	</form>
</div>