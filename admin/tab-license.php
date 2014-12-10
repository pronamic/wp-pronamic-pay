<h3><?php _e( 'License Information', 'pronamic_ideal' ); ?></h3>

<table class="form-table">
	<tr>
		<th scope="row">
			<?php _e( 'License Status', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$status = get_option( 'pronamic_pay_license_status' );

			switch ( $status ) {
				case 'valid':
					_e( 'Valid', 'pronamic_ideal' );

					break;
				case 'invalid':
					_e( 'Invalid', 'pronamic_ideal' );

					break;
				case 'site_inactive':
					_e( 'Site Inactive', 'pronamic_ideal' );

					break;
				default :
					echo $status;

					break;
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Next License Check', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$timestamp = wp_next_scheduled( 'pronamic_pay_license_check_event' );

			if ( $timestamp ) {
				$timestamp = get_date_from_gmt( date( 'Y-m-d H:i:s', $timestamp ), 'U' );

				echo date_i18n( 'D j M Y H:i:s', $timestamp );
			} else {
				_e( 'Not scheduled', 'pronamic_ideal' );
			}

			?>
		</td>
	</tr>
</table>
