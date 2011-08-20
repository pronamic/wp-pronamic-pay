<?php 

namespace Pronamic\WordPress\IDeal;

use Pronamic\IDeal\Transaction;

$payments = PaymentsRepository::getPayments();

?>
<div class="wrap">
	<?php screen_icon(Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Payments', Plugin::TEXT_DOMAIN); ?>
	</h2>

	<form method="post" action="">
		<div class="tablenav top">
			<div class="alignleft actions">
				<select name="action">
					<option value="-1" selected="selected"><?php _e('Bulk Actions', Plugin::TEXT_DOMAIN); ?></option>
					<option value="delete"><?php _e('Delete', Plugin::TEXT_DOMAIN); ?></option>
				</select>

				<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', Plugin::TEXT_DOMAIN); ?>"  />
			</div>
		</div>

		<table cellspacing="0" class="widefat fixed">

			<?php foreach(array('thead', 'tfoot') as $tag): ?>

			<<?php echo $tag; ?>>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
					<th scope="col" class="manage-column"><?php _e('Transaction ID', Plugin::TEXT_DOMAIN) ?></th>
					<th scope="col" class="manage-column"><?php _e('Date', Plugin::TEXT_DOMAIN) ?></th>
					<th scope="col" class="manage-column"><?php _e('Description', Plugin::TEXT_DOMAIN) ?></th>
					<th scope="col" class="manage-column"><?php _e('Consumer', Plugin::TEXT_DOMAIN) ?></th>
					<th scope="col" class="manage-column"><?php _e('Amount', Plugin::TEXT_DOMAIN) ?></th>
					<th scope="col" class="manage-column"><?php _e('Source', Plugin::TEXT_DOMAIN) ?></th>
					<th scope="col" class="manage-column"><?php _e('Status', Plugin::TEXT_DOMAIN) ?></th>
				</tr>
			</<?php echo $tag; ?>>

			<?php endforeach; ?>

			<tbody>
				<?php foreach($payments as $payment): ?>

				<tr>
					<?php $transaction = $payment->transaction; ?>
					<th scope="row" class="check-column">
						<input type="checkbox" name="payments[]" value="<?php echo $payment->getId(); ?>"/>
					</th>
					<td>
						<?php 
						
						$detailsLink = Admin::getPaymentDetailsLink($payment->getId()); 

						?>
						<a href="<?php echo $detailsLink; ?>" title="<?php _e('Details', Plugin::TEXT_DOMAIN); ?>">
							<?php echo $transaction->getId(); ?>
						</a>
					</td>
					<td>
						<?php 

						$date = $payment->getDate();

						$timezone = get_option('timezone_string');
						if($timezone) {
							$date = clone $date;
							$date->setTimezone(new \DateTimeZone($timezone));
						}
						
						echo $date->format('d-m-Y @ H:i'); 
						
						?>
					</td>
					<td>
						<?php echo $transaction->getDescription(); ?>
					</td>
					<td>
						<?php echo $transaction->getConsumerName(); ?><br />
						<?php echo $transaction->getConsumerAccountNumber(); ?><br />
						<?php echo $transaction->getConsumerCity(); ?>
					</td>
					<td>
						<?php echo $transaction->getAmount(); ?>
						<?php echo $transaction->getCurrency(); ?>
					</td>
					<td>
						<?php 
						
						$source = $payment->getSource() . '<br />' . $payment->getSourceId();
						
						echo apply_filters('pronamic_ideal_source_column', $source);
						
						?>
					</td>
					<td>
						<?php echo IDeal::translateStatus($transaction->getStatus()); ?>
					</td>
				</tr>

				<?php endforeach; ?>
			</tbody>
		</table>
	</form>
</div>