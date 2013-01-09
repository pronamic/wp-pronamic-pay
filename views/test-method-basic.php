<?php 

$gateway = new Pronamic_Gateways_IDealBasic_Gateway( $configuration );

?>

<h3>
	<?php _e( 'Mandatory Tests', 'pronamic_ideal' ); ?>
</h3>

<table class="wp-list-table widefat" style="width: auto;" cellspacing="0">
	<thead>
		<tr>
			<th scope="col">
				<?php _e( 'Order', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php _e( 'Expected result if integration is correct', 'pronamic_ideal' ); ?>
			</th>
			<th scope="col">
				<?php _e( 'Actions', 'pronamic_ideal' ); ?>
			</th>
		</tr>
	</thead>
	
	<?php 
	
	$test_cases = array(
		1 => array(
			'amount' => 1,
			'result' => 'Success'
		),
		2 => array(
			'amount' => 2,
			'result' => 'Cancelled'
		),
		3 => array(
			'amount' => 3,
			'result' => 'Expired'
		),
		4 => array(
			'amount' => 4,
			'result' => 'Open'
		),
		5 => array(
			'amount' => 5,
			'result' => 'Failure'
		),
		7 => array(
			'amount' => 7,
			'result' => 'SO1000 Failure in system'
		),
	);
	
	?>

	<tbody>

		<?php foreach ( $test_cases as $test_case => $data ): ?>

			<tr>
				<td>
					<?php 
					
					printf( 
						__( 'Transaction with <code>amount</code> = %s:', 'pronamic_ideal' ), 
						Pronamic_Gateways_IDealAdvancedV3_IDeal::format_amount( $data['amount'] )
					);
					
					?>
				</td>
				<td>
					<?php echo $data['result']; ?>
				</td>
				<td>
					<?php

					$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test_case );
					
					$gateway->start( $data );

					$name = sprintf( __( 'Test', 'pronamic_ideal' ) );

					?>
					<form method="post" action="<?php echo esc_attr( $gateway->get_action_url() ); ?>" target="_blank" style="display: inline">
						<?php 
					
						echo $gateway->get_output_html();
					
						submit_button( $name, 'secondary', 'submit', false ); 
					
						?>
					</form>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>