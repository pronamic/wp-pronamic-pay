<?php 

$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentById( $id );

$update = null;

if ( isset( $_POST['status-request'] ) && $payment != null ) {
	$status = $payment->status;

	if ( ! in_array( $status, array( Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN, null ), true ) ) {
		$update = sprintf( __( 'The payment status is: %s', 'pronamic_ideal' ), Pronamic_WordPress_IDeal_IDeal::translate_status( $status ) );
	}
}

?>
<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2>
		<?php _e( 'iDEAL Payment', 'pronamic_ideal' ); ?>
	</h2>

	<?php if ( $update ) : ?>
	
		<div class="updated inline below-h2">
			<p><?php echo $update; ?></p>
		</div>

	<?php endif; ?>

	<?php if ( $payment != null ) : ?>
	
		<form method="post" action="">
			<h3>
				<?php _e( 'General', 'pronamic_ideal' ); ?>
			</h3>
		
			<table class="form-table">
				<tr>
					<th scope="row">
						<?php _e( 'ID', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->id; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Purchase ID', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->purchase_id; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Transaction ID', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->transaction_id; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Description', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->description; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Amount', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->amount; ?>
						<?php echo $payment->currency; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Expiration Period', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->expiration_period; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Status', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo Pronamic_WordPress_IDeal_IDeal::translate_status( $payment->status ); ?>
					</td>
				</tr>
			</table>
		
			<h3>
				<?php _e( 'Consumer', 'pronamic_ideal' ); ?>
			</h3>
			
			<table class="form-table">
				<tr>
					<th scope="row">
						<?php _e( 'Name', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->consumer_name; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Email', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->email; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Account Number', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->consumer_account_number; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'IBAN', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->consumer_iban; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'BIC', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->consumer_bic; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'City', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->consumer_city; ?>
					</td>
				</tr>
			</table>
	
			<?php if ( $configuration = $payment->configuration ) : ?>
		
				<h3>
					<?php _e( 'Configuration', 'pronamic_ideal' ); ?>
				</h3>
			
				<table class="form-table">
					<tr>
						<th scope="row">
							<?php _e( 'Configuration', 'pronamic_ideal' ); ?>
						</th>
						<td>
							<a href="<?php echo Pronamic_WordPress_IDeal_Admin::getConfigurationEditLink( $configuration->getId() ); ?>">
								<?php echo $configuration->getName(); ?>
							</a>
						</td>
					</tr>
				</table>
			
			<?php endif; ?>
		
			<h3>
				<?php _e( 'Source', 'pronamic_ideal' ); ?>
			</h3>
			
			<table class="form-table">
				<tr>
					<th scope="row">
						<?php _e( 'Name', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->source; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'ID', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->source_id; ?>
					</td>
				</tr>
			</table>
	
			<?php 
			
			submit_button(
				__('Status Request', 'pronamic_ideal'),
				'secondary',
				'status-request'
			);
	
			?>
		</form>

	<?php endif; ?>
</div>