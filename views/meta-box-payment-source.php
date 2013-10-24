<?php 

$post_id = get_the_ID();

$payment = get_pronamic_payment( $post_id );

?>
<table class="form-table">
	<tr>
		<th scope="row">
			<?php _e( 'Source', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo $payment->get_source_text(); ?>
		</td>
	</tr>

	<?php if ( $payment->get_source() == 's2member' ) : ?>
	
		<tr>
			<th scope="row">
				<?php _e( 'Period', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo get_post_meta( $payment->get_id(), '_pronamic_payment_s2member_period', true ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Level', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo get_post_meta( $payment->get_id(), '_pronamic_payment_s2member_level', true ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php if ( $payment->get_source() == 'wp-e-commerce' ) : ?>
	
		<tr>
			<th scope="row">
				<?php _e( 'Purchase ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo get_post_meta( $payment->get_id(), '_pronamic_payment_wpsc_purchase_id', true ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Session ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo get_post_meta( $payment->get_id(), '_pronamic_payment_wpsc_session_id', true ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php if ( $payment->get_source() == 'membership' ) : ?>
	
		<tr>
			<th scope="row">
				<?php _e( 'User ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo get_post_meta( $payment->get_id(), '_pronamic_payment_membership_user_id', true ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Subscription ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo get_post_meta( $payment->get_id(), '_pronamic_payment_membership_subscription_id', true ); ?>
			</td>
		</tr>

	<?php endif; ?>
</table>