<?php 

$post_id = get_the_ID();

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
	<tr>
		<th scope="row">
			<?php _e( 'Source', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$source    = get_post_meta( $post_id, '_pronamic_payment_source', true );
			$source_id = get_post_meta( $post_id, '_pronamic_payment_source_id', true );
			
			$text = $source . '<br />' . $source_id;
			
			// $text = apply_filters( 'pronamic_ideal_source_column_' . $source, $text, $post );
			// $text = apply_filters( 'pronamic_ideal_source_column', $text, $post );
			
			echo $text;

			?>
		</td>
	</tr>
</table>