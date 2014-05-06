<table class="wp-list-table widefat">
	<thead>
		<tr>
			<th scope="col">
				<?php _e( 'Name', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php _e( 'Website', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php _e( 'Issuers', 'pronamic_ideal' ); ?>
			</th>
		</tr>
	</thead>

	<tbody>

		<?php foreach ( $methods as $method ) : ?>

			<tr>
				<td>
					<?php echo esc_html( $method['name'] ); ?>
				</td>
				<td>
					<?php

					if ( isset( $method['url'] ) ) {
						printf(
							'<a href="%s" target="_blank">%s</a>',
							esc_attr( $method['url'] ),
							esc_html( $method['url'] )
						);
					}

					?>
				</td>
				<td>
					<?php

					if ( isset( $method['issuers'] ) ) {
						echo implode( ', ', $method['issuers'] );
					}

					?>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
