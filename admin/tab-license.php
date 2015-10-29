<h2><?php esc_html_e( 'License Information', 'pronamic_ideal' ); ?></h2>

<table class="form-table">
	<tr>
		<th scope="row">
			<?php esc_html_e( 'License Status', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$status = get_option( 'pronamic_pay_license_status' );

			switch ( $status ) {
				case 'valid':
					esc_html_e( 'Valid', 'pronamic_ideal' );

					break;
				case 'invalid':
					esc_html_e( 'Invalid', 'pronamic_ideal' );

					break;
				case 'site_inactive':
					esc_html_e( 'Site Inactive', 'pronamic_ideal' );

					break;
				default :
					echo esc_html( $status );

					break;
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Next License Check', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$timestamp = wp_next_scheduled( 'pronamic_pay_license_check_event' );

			if ( $timestamp ) {
				$timestamp = get_date_from_gmt( date( 'Y-m-d H:i:s', $timestamp ), 'U' );

				echo esc_html( date_i18n( 'D j M Y H:i:s', $timestamp ) );
			} else {
				esc_html_e( 'Not scheduled', 'pronamic_ideal' );
			}

			?>
		</td>
	</tr>
</table>
