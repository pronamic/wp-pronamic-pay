<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2>
		<?php _e( 'iDEAL Status', 'pronamic_ideal' ); ?>
	</h2>

	<p>
		<?php _e( 'The iDEAL-status.nl webservice monitors the following status of the various iDEAL banks:', 'pronamic_ideal' ); ?>
	</p>

	<?php 
	
	$url = 'http://www.ideal-status.nl/static/issuers_current.json';

	$response = wp_remote_get( $url );

	$status_data = null;

	if ( ! is_wp_error( $response ) ) {
		if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
			$body = wp_remote_retrieve_body( $response );
			
			$data = json_decode( $body );

			if ( $data !== false ) {
				$status_data = $data;
			}
		}
	}

	if ( $status_data ): ?>
	
		<table>
			<thead>
				<tr>
					<th scope="col">
						<?php _e( 'Issuer Name', 'pronamic_ideal' ); ?>
					</th>
					<th scope="col">
						<?php _e( 'Issuer ID', 'pronamic_ideal' ); ?>
					</th>
					<th scope="col">
						<?php _e( 'Date', 'pronamic_ideal' ); ?>
					</th>
					<th scope="col">
						<?php _e( 'Success', 'pronamic_ideal' ); ?>
					</th>
					<th scope="col">
						<?php _e( 'Failure', 'pronamic_ideal' ); ?>
					</th>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach ( $status_data as $status ): ?>

					<tr>
						<td>
							<?php echo $status->issuer_name; ?>
						</td>
						<td>
							<?php echo $status->issuer_id; ?>
						</td>
						<td>
							<?php echo $status->datetime; ?>
						</td>
						<td>
							<?php echo number_format( $status->rate_success * 100, 1, ',', '.' ); ?>%
						</td>
						<td>
							<?php echo number_format( $status->rate_failure * 100, 1, ',', '.' ); ?>%
						</td>
					</tr>
				
				<?php endforeach; ?>
			</tbody>
		</table>
	
	<?php endif; ?>
</div>