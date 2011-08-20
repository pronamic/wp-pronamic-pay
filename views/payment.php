<?php 

namespace Pronamic\WordPress\IDeal;

use Pronamic\IDeal\Transaction;

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

$payment = PaymentsRepository::getPaymentById($id);

$update = null;

if(isset($_POST['status-request']) && $payment != null) {
	$transaction = $payment->transaction;
	$status = $transaction->getStatus();

	if(!in_array($status, array(Transaction::STATUS_OPEN, null), true)) {
		$update = sprintf(__('The payment status is: %s', Plugin::TEXT_DOMAIN), IDeal::translateStatus($status));
	}
}

?>
<div class="wrap">
	<?php screen_icon(Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Payment', Plugin::TEXT_DOMAIN); ?>
	</h2>

	<?php if($update): ?>
	
	<div class="updated inline below-h2">
		<p><?php echo $update; ?></p>
	</div>

	<?php endif; ?>

	<?php if($payment != null): ?>

	<form method="post" action="">
		<h3>
			<?php _e('General', Plugin::TEXT_DOMAIN); ?>
		</h3>
	
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Purchase ID', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $payment->getId(); ?>
				</td>
			</tr>
		</table>
	
		<?php if($transaction = $payment->transaction): ?>
	
		<h3>
			<?php _e('Transaction', Plugin::TEXT_DOMAIN); ?>
		</h3>
	
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Transaction ID', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getId(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Description', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getDescription(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Amount', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getAmount(); ?>
					<?php echo $transaction->getCurrency(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Expiration Period', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getExpirationPeriod(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Status', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo IDeal::translateStatus($transaction->getStatus()); ?>
				</td>
			</tr>
		</table>
	
		<h3>
			<?php _e('Consumer', Plugin::TEXT_DOMAIN); ?>
		</h3>
		
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Name', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getConsumerName(); ?>
				</td>
			</tr>		
			<tr>
				<th scope="row">
					<?php _e('Account Number', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getConsumerAccountNumber(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('City', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $transaction->getConsumerCity(); ?>
				</td>
			</tr>
		</table>
	
		<?php endif; ?>

		<?php if($configuration = $payment->configuration): ?>
	
		<h3>
			<?php _e('Configuration', Plugin::TEXT_DOMAIN); ?>
		</h3>
	
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Configuration', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<a href="<?php Admin::getConfigurationEditLink($configuration->getId()); ?>">
						<?php echo $configuration->getName(); ?>
					</a>
				</td>
			</tr>
		</table>
		
		<?php endif; ?>
	
		<h3>
			<?php _e('Source', Plugin::TEXT_DOMAIN); ?>
		</h3>
		
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e('Name', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $payment->getSource(); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('ID', Plugin::TEXT_DOMAIN); ?>
				</th>
				<td>
					<?php echo $payment->getSourceId(); ?>
				</td>
			</tr>
		</table>

		<?php 
		
		submit_button(
			__('Status Request', Plugin::TEXT_DOMAIN) , 
			'secondary' ,
			'status-request'
		);

		?>
	</form>

	<?php endif; ?>
</div>