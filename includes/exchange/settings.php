<?php if ( isset( $data ) && $data instanceof stdClass ) : ?>

<div class="wrap">
	<h2><?php _e( 'iDEAL Gateway Settings', 'pronamic_ideal' ); ?></h2>

	<div class="it-exchange-return-to-addons">
		<p>
			<a href="<?php echo remove_query_arg( 'add-on-settings' ); ?>">&larr; <?php _e( 'Back to Add-ons', 'pronamic_ideal' ); ?></a>
		</p>
	</div>

	<form action="options.php" method="post">

		<?php settings_fields( Pronamic_Exchange_IDeal_AddOn::OPTION_GROUP ); ?>

		<table class="form-table">
			<tbody>

			<tr>
				<th>
					<label for="<?php echo Pronamic_Exchange_IDeal_AddOn::TITLE_OPTION_KEY; ?>">
						<?php _e( 'Title', 'pronamic_ideal' ); ?>
					</label>
				</th>

				<td>
					<input
						type="text"
						id="<?php echo Pronamic_Exchange_IDeal_AddOn::TITLE_OPTION_KEY; ?>"
						name="<?php echo Pronamic_Exchange_IDeal_AddOn::TITLE_OPTION_KEY; ?>"
					    value="<?php echo $data->title; ?>"
				    />
				</td>
			</tr>

			<tr>
				<th>
					<label for="<?php echo Pronamic_Exchange_IDeal_AddOn::CONFIGURATION_OPTION_KEY; ?>">
						<?php _e( 'iDEAL Configuration', 'pronamic_ideal' ); ?>
					</label>
				</th>

				<td>
					<select id="<?php echo Pronamic_Exchange_IDeal_AddOn::CONFIGURATION_OPTION_KEY; ?>" name="<?php echo Pronamic_Exchange_IDeal_AddOn::CONFIGURATION_OPTION_KEY; ?>">

						<?php foreach ( $data->configurations as $configuration_key => $configuration ) : ?>

						<option value="<?php echo $configuration_key; ?>" <?php selected( $configuration_key, $data->current_configuration ); ?>>
							<?php echo esc_attr( $configuration ); ?>
						</option>

						<?php endforeach; ?>

					</select>
				</td>
			</tr>

			</tbody>
		</table>

		<?php submit_button(); ?>

	</form>
</div>

<?php endif; ?>