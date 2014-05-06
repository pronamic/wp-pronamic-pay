<h3><?php _e( 'License Information', 'pronamic_ideal' ); ?></h3>

<?php $license_info = Pronamic_WP_Pay_Plugin::get_license_info(); ?>

<?php if ( empty( $license_info ) ) : ?>

	<p>
		<?php _e( 'No license information available.', 'pronamic_ideal' ); ?>
	</p>

<?php else : ?>

	<table class="form-table">

		<?php if ( isset( $license_info->activationDate ) ) : ?>

			<tr>
				<th scope="row">
					<?php _e( 'Activation Date', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<?php echo mysql2date( __( 'Y/m/d g:i:s A', 'pronamic_ideal' ), $license_info->activationDate ); ?>
				</td>
			</tr>

		<?php endif; ?>

		<?php if ( isset( $license_info->expirationDate ) ) : ?>

			<tr>
				<th scope="row">
					<?php _e( 'Expiration Date', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<?php echo mysql2date( __( 'Y/m/d g:i:s A', 'pronamic_ideal' ), $license_info->expirationDate ); ?>
				</td>
			</tr>

		<?php endif; ?>

	</table>

<?php endif; ?>
