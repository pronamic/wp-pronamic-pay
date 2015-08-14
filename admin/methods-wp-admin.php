<table class="wp-list-table widefat">
	<col width="1" />

	<thead>
		<tr>
			<th scope="col"><?php esc_html_e( 'Icon', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Name', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Website', 'pronamic_ideal' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php foreach ( $methods as $method ) : ?>

			<tr>
				<td>
					<?php

					if ( isset( $method['icon'] ) ) {
						printf( '<img src="%s" alt="" />', esc_attr( $method['icon'] ) );
					}

					?>
				</td>
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
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
