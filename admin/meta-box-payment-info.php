<?php

$post_id = get_the_ID();

$payment = get_pronamic_payment( $post_id );

?>
<table class="form-table">
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Date', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php the_time( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ) ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'ID', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( $post_id ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Description', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( get_post_meta( $post_id, '_pronamic_payment_description', true ) ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo esc_html( get_post_meta( $post_id, '_pronamic_payment_currency', true ) );
			echo ' ';
			echo esc_html( get_post_meta( $post_id, '_pronamic_payment_amount', true ) );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Transaction ID', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( get_post_meta( $post_id, '_pronamic_payment_transaction_id', true ) ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Payment Method', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$method = get_post_meta( $post_id, '_pronamic_payment_method', true );

			$name = Pronamic_WP_Pay_PaymentMethods::get_name( $method );

			echo esc_html( $name );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Action URL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$url = get_post_meta( $post_id, '_pronamic_payment_action_url', true );

			printf(
				'<a href="%s" target="_blank">%s</a>',
				esc_attr( $url ),
				esc_html( $url )
			);

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Return URL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$url = $payment->get_return_url();

			printf(
				'<a href="%s">%s</a>',
				esc_attr( $url ),
				esc_html( $url )
			);

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Redirect URL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$url = $payment->get_return_redirect_url();

			printf(
				'<a href="%s">%s</a>',
				esc_attr( $url ),
				esc_html( $url )
			);

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Status', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$status_object = get_post_status_object( get_post_status( $post_id ) );

			if ( isset( $status_object, $status_object->label ) ) {
				echo esc_html( $status_object->label );
			} else {
				echo '—';
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Email', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo esc_html( get_post_meta( $post_id, '_pronamic_payment_email', true ) );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Consumer', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_name', true ) );
			echo '<br />';
			echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_account_number', true ) );
			echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_iban', true ) );
			echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_bic', true ) );
			echo '<br />';
			echo esc_html( get_post_meta( $post_id, '_pronamic_payment_consumer_city', true ) );

			?>
		</td>
	</tr>
</table>
