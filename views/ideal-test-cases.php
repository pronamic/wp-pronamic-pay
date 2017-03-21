<table class="wp-list-table widefat" style="width: auto;" cellspacing="0">
	<thead>
		<tr>
			<th scope="col">
				<?php esc_html_e( 'Order', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php esc_html_e( 'Expected result if integration is correct', 'pronamic_ideal' ); ?>
			</th>
		</tr>
	</thead>

	<?php

	$test_cases = array(
		1 => array(
			'amount' => 1,
			'result' => 'Success',
		),
		2 => array(
			'amount' => 2,
			'result' => 'Cancelled',
		),
		3 => array(
			'amount' => 3,
			'result' => 'Expired',
		),
		4 => array(
			'amount' => 4,
			'result' => 'Open',
		),
		5 => array(
			'amount' => 5,
			'result' => 'Failure',
		),
		7 => array(
			'amount' => 7,
			'result' => 'SO1000 Failure in system',
		),
	);

	?>

	<tbody>

		<?php foreach ( $test_cases as $test_case => $data ) : ?>

			<tr>
				<td>
					<?php

					echo wp_kses(
						sprintf(
							/* translators: %s: formatted amount */
							__( 'Transaction with <code>amount</code> = %s:', 'pronamic_ideal' ),
							esc_html( Pronamic_WP_Pay_Gateways_IDealAdvancedV3_IDeal::format_amount( $data['amount'] ) )
						),
						array(
							'code' => array(),
						)
					);

					?>
				</td>
				<td>
					<?php echo esc_html( $data['result'] ); ?>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
