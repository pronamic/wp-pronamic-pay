<h3><?php esc_html_e( 'Payment Gateways', 'pronamic_ideal' ); ?></h3>

<?php

global $pronamic_pay_providers;
global $pronamic_pay_gateways;

bind_providers_and_gateways();

$gateways = $pronamic_pay_gateways;

?>

<table class="wp-list-table widefat" cellspacing="0">
	<thead>
		<tr>
			<th scope="col"><?php esc_html_e( 'Provider', 'pronamic_ideal' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Name', 'pronamic_ideal' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php foreach ( $gateways as $gateway ) : ?>

			<tr>
				<td>
					<?php if ( isset( $gateway['provider'], $pronamic_pay_providers[ $gateway['provider'] ] ) ) : ?>

						<?php $provider = $pronamic_pay_providers[ $gateway['provider'] ]; ?>

						<?php if ( isset( $provider['url'] ) ) : ?>

							<a href="<?php echo esc_attr( $provider['url'] ); ?>">
								<?php echo esc_html( $provider['name'] ); ?>
							</a>

						<?php endif; ?>

					<?php endif; ?>
				</td>
				<td>
					<?php echo esc_html( $gateway['name'] ); ?>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
