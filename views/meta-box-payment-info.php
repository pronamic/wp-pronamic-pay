<?php 

$post_id = get_the_ID();

$payment = get_pronamic_payment( $post_id );

?>
<table class="form-table">
	<tr>
		<th scope="row">
			<?php _e( 'ID', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo $post_id; ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Description', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo get_post_meta( $post_id, '_pronamic_payment_description', true ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Amount', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo get_post_meta( $post_id, '_pronamic_payment_currency', true );
			echo ' ';
			echo get_post_meta( $post_id, '_pronamic_payment_amount', true );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Transaction ID', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo get_post_meta( $post_id, '_pronamic_payment_transaction_id', true ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Authentication URL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php
			
			$url = get_post_meta( $post_id, '_pronamic_payment_authentication_url', true );

			printf(
				'<a href="%s" target="_blank">%s</a>',
				$url,
				$url
			);
			
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Email', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo get_post_meta( $post_id, '_pronamic_payment_email', true );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Consumer', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo get_post_meta( $post_id, '_pronamic_payment_consumer_name', true );
			echo '<br />';
			echo get_post_meta( $post_id, '_pronamic_payment_consumer_account_number', true );
			echo get_post_meta( $post_id, '_pronamic_payment_consumer_iban', true );
			echo get_post_meta( $post_id, '_pronamic_payment_consumer_bic', true );
			echo '<br />';
			echo get_post_meta( $post_id, '_pronamic_payment_consumer_city', true );

			?>
		</td>
	</tr>
</table>