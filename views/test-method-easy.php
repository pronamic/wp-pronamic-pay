<?php 

$gateway = new Pronamic_Gateways_IDealEasy_Gateway( $configuration );

?>
<h3>
	<?php _e( 'Tests', 'pronamic_ideal' ); ?>
</h3>

<?php foreach ( array( 2, 3, 4, 5, 1 ) as $test_case ): ?>
	
	<?php 

	$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), $test_case );
		
	$gateway->start( $data );

	$name = sprintf( __( 'Test &euro; %s', 'pronamic_ideal' ), $test_case );

	?>
	
	<form method="post" action="<?php echo esc_attr( $gateway->get_action_url() ); ?>" target="_blank" style="display: inline">
		<?php 
	
		echo $gateway->get_output_html();
	
		submit_button( $name, 'secondary', 'submit', false ); 
	
		?>
	</form>

<?php endforeach; ?>