<?php 

$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentById( $id );

$update = null;

if ( isset( $_POST['status-request'] ) && $payment != null ) {
	$transaction = $payment->transaction;
	$status = $transaction->getStatus();

	if ( ! in_array( $status, array( Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN, null ), true ) ) {
		$update = sprintf( __( 'The payment status is: %s', 'pronamic_ideal' ), Pronamic_WordPress_IDeal_IDeal::translateStatus( $status ) );
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
						<?php echo $payment->getId(); ?>
					</td>
				</tr>
			</table>
		
			<?php if($transaction = $payment->transaction): ?>
		
			<h3>
				<?php _e('Transaction', 'pronamic_ideal'); ?>
			</h3>
		
			<table class="form-table">
				<tr>
					<th scope="row">
						<?php _e( 'Purchase ID', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $transaction->getPurchaseId(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Transaction ID', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $transaction->getId(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Description', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $transaction->getDescription(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Amount', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $transaction->getAmount(); ?>
						<?php echo $transaction->getCurrency(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Expiration Period', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $transaction->getExpirationPeriod(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Status', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo Pronamic_WordPress_IDeal_IDeal::translateStatus( $transaction->getStatus() ); ?>
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
						<?php echo $transaction->getConsumerName(); ?>
					</td>
				</tr>		
				<tr>
					<th scope="row">
						<?php _e( 'Account Number', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $transaction->getConsumerAccountNumber(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'City', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $transaction->getConsumerCity(); ?>
					</td>
				</tr>
			</table>
		
			<?php endif; ?>
	
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
						<?php echo $payment->getSource(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'ID', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $payment->getSourceId(); ?>
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