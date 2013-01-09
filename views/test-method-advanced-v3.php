<?php 

$gateway = new Pronamic_Gateways_IDealAdvancedV3_Gateway( $configuration );

?>

<h3>
	<?php _e( 'Mandatory Tests', 'pronamic_ideal' ); ?>
</h3>

<form method="post" action="" target="_blank">
	<?php wp_nonce_field( 'test_ideal_advanced_v3', 'pronamic_ideal_nonce' ); ?>
	
	<div class="tablenav top">
		<?php echo $gateway->get_input_html(); ?>
	</div>

	<?php if ( $gateway->has_error() ) : ?>
	
		<div class="error">
			<?php 
			
			$error = $gateway->get_error(); 

			foreach ( $error->get_error_codes() as $code ) {
				if ( $code == 'ideal_advanced_v3_error' ) {
					$ideal_error = $error->get_error_data( $code );
					
					if ( $ideal_error instanceof Pronamic_Gateways_IDealAdvancedV3_Error ) : ?>
					
						<dl>
							<dt><?php _e( 'Code', 'pronamic_ideal' ); ?></dt>
							<dd><?php echo $ideal_error->get_code(); ?></dd>

							<dt><?php _e( 'Message', 'pronamic_ideal' ); ?></dt>
							<dd><?php echo $ideal_error->get_message(); ?></dd>

							<dt><?php _e( 'Detail', 'pronamic_ideal' ); ?></dt>
							<dd><?php echo $ideal_error->get_detail(); ?></dd>

							<dt><?php _e( 'Suggested Action', 'pronamic_ideal' ); ?></dt>
							<dd><?php echo $ideal_error->get_suggested_action(); ?></dd>

							<dt><?php _e( 'Consumer Message', 'pronamic_ideal' ); ?></dt>
							<dd><?php echo $ideal_error->get_consumer_message(); ?></dd>
						</dl>
					
					<?php endif;

				}
			}
			
			?>
		</div>

	<?php endif; ?>

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

						$name = sprintf( __( 'Test', 'pronamic_ideal' ) );

						submit_button( $name, 'secondary', 'test_ideal_advanced_v3[' . $test_case . ']', false ); 

						?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
</form>