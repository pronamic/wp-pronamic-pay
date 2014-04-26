<table class="wp-list-table widefat" cellspacing="0">
	<thead>
		<tr>
			<th scope="col"><?php _e( 'Provider', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php _e( 'Name', 'pronamic_ideal' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php foreach ( $gateways as $gateway ): ?>

			<tr>
				<td>
					<?php if ( isset( $gateway['provider'], $pronamic_pay_providers[ $gateway['provider'] ] ) ): ?>
						<?php $provider = $pronamic_pay_providers[ $gateway['provider'] ]; ?>
						<a href="<?php echo $provider['url']; ?>">
							<?php echo $provider['name']; ?>
						</a>
					<?php endif; ?>
				</td>
				<td>
					<?php echo $gateway['name']; ?>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
