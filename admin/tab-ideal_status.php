<h2><?php esc_html_e( 'iDEAL Status', 'pronamic_ideal' ); ?></h2>

<p>
	<?php

	echo wp_kses(
		sprintf(
			__( 'The <a href="%s" target="_blank">iDEAL-status.nl</a> webservice monitors the following status of the various iDEAL banks:', 'pronamic_ideal' ),
			'http://www.ideal-status.nl/'
		),
		array(
			'a' => array(
				'href'   => true,
				'target' => true,
			),
		)
	);

	?>
</p>

<?php

$url = 'http://www.ideal-status.nl/static/issuers_current.json';

$response = wp_remote_get( $url );

$status_data = null;

if ( ! is_wp_error( $response ) ) {
	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
		$body = wp_remote_retrieve_body( $response );

		$data = json_decode( $body );

		if ( false !== $data ) {
			$status_data = $data;
		}
	}
}

if ( $status_data ) : ?>

	<table class="wp-list-table widefat" style="width: auto;">
		<thead>
			<tr>
				<th scope="col">
					<?php esc_html_e( 'Issuer Name', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php esc_html_e( 'Issuer ID', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php esc_html_e( 'Date', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php esc_html_e( 'Success', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php esc_html_e( 'Failure', 'pronamic_ideal' ); ?>
				</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $status_data as $status ) : ?>

				<tr>
					<td>
						<?php echo esc_html( $status->issuer_name ); ?>
					</td>
					<td>
						<?php echo esc_html( $status->issuer_id ); ?>
					</td>
					<td>
						<?php echo esc_html( $status->datetime ); ?>
					</td>
					<td>
						<?php echo esc_html( number_format( $status->rate_success * 100, 1, ',', '.' ) ); ?>%
					</td>
					<td>
						<?php echo esc_html( number_format( $status->rate_failure * 100, 1, ',', '.' ) ); ?>%
					</td>
				</tr>

			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif; ?>
