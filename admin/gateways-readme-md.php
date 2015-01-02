<table>
	<thead>
		<tr>
			<th scope="col"><?php _ex( 'Provider', 'readme.md', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php _ex( 'Name', 'readme.md', 'pronamic_ideal' ); ?></th>
		</tr>
	</thead>

	<tbody>
<?php foreach ( $gateways as $gateway ) : ?>
		<tr>
			<td><?php

			if ( isset( $gateway['provider'], $pronamic_pay_providers[ $gateway['provider'] ] ) ) {
				$provider = $pronamic_pay_providers[ $gateway['provider'] ];

				if ( isset( $provider['url'] ) ) {
					printf(
						'<a href="%s">%s</a>',
						esc_attr( $provider['url'] ),
						esc_html( $provider['name'] )
					);
				} else {
					echo esc_html( $provider['name'] );
				}
			}

			?></td>
			<td><?php echo $gateway['name']; ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
