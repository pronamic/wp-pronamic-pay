<?php 

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

$payment = Pronamic_WordPress_IDeal_PaymentsRepository::getPaymentById($id);

$update = null;

if(isset($_POST['status-request']) && $payment != null) {
	$transaction = $payment->transaction;
	$status = $transaction->getStatus();

	if(!in_array($status, array(Pronamic_IDeal_Transaction::STATUS_OPEN, null), true)) {
		$update = sprintf(__('The payment status is: %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), IDeal::translateStatus($status));
	}
}

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Payment', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<?php if($update): ?>
	
	<div class="updated inline below-h2">
		<p><?php echo $update; ?></p>
	</div>

	<?php endif; ?>

	<?php if($payment != null): ?>

	<form method="post" action="">
		<h3>
			<?php _e('General', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>
	
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Purchase ID', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $payment->getId(); ?>
				</td>
			</tr>
		</table>
	
		<?php if($transaction = $payment->transaction): ?>
	
		<h3>
			<?php _e('Transaction', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>
	
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Transaction ID', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getId(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Description', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getDescription(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Amount', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getAmount(); ?>
					<?php echo $transaction->getCurrency(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Expiration Period', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getExpirationPeriod(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Status', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo Pronamic_WordPress_IDeal_IDeal::translateStatus($transaction->getStatus()); ?>
				</td>
			</tr>
		</table>
	
		<h3>
			<?php _e('Consumer', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>
		
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Name', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getConsumerName(); ?>
				</td>
			</tr>		
			<tr>
				<th scope="row">
					<?php _e('Account Number', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getConsumerAccountNumber(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('City', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getConsumerCity(); ?>
				</td>
			</tr>
		</table>
	
		<?php endif; ?>

		<?php if($configuration = $payment->configuration): ?>
	
		<h3>
			<?php _e('Configuration', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>
	
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Configuration', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<a href="<?php echo Pronamic_WordPress_IDeal_Admin::getConfigurationEditLink($configuration->getId()); ?>">
						<?php echo $configuration->getName(); ?>
					</a>
				</td>
			</tr>
		</table>
		
		<?php endif; ?>
	
		<h3>
			<?php _e('Source', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
		</h3>
		
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Name', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $payment->getSource(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('ID', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $payment->getSourceId(); ?>
				</td>
			</tr>
		</table>

		<?php 
		
		submit_button(
			__('Status Request', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			'secondary' ,
			'status-request'
		);

		?>
	</form>

	<?php endif; ?>
</div>