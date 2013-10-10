<?php 

// Get all configurations
$configurations = Pronamic_WordPress_IDeal_IDeal::get_configurations_select_options();

// Get existing options
$pronamic_ideal_s2member_enabled                = get_option( 'pronamic_ideal_s2member_enabled' );
$pronamic_ideal_s2member_chosen_configuration   = get_option( 'pronamic_ideal_s2member_chosen_configuration' );

?>
<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<form action="" method="post">
		<?php wp_nonce_field( 'pronamic-ideal-s2member-options', 'pronamic-ideal-s2member-options-nonce' ); ?>

		<table class="form-table">
			<tr>
				<th>
					<?php _e( 'Enable/Disable', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<input type="checkbox" name="pronamic_ideal_s2member_enabled" value="1" <?php checked( $pronamic_ideal_s2member_enabled ); ?> />
				</td>
			</tr>
			<tr>
				<th>
					<?php _e( 'Configuration', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<select name="pronamic_ideal_s2member_chosen_configuration">
						<?php 
						
						foreach ( $configurations as $value => $name ) {
							printf(
								'<option value="%s" %s>%s</option>',
								esc_attr( $value ),
								selected( $value, $pronamic_ideal_s2member_chosen_configuration, false ),
								esc_html( $name )
							);
						}
						
						?>
					</select>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>
</div>