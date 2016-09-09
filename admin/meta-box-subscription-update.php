<?php

$states = Pronamic_WP_Pay_Plugin::get_subscription_states();

?>
<div class="pronamic-pay-inner">
	<p>
		<label for="pronamic-subscription-status">Status:</span>&nbsp;
		<select id="pronamic-subscription-status" name="post_status" class="medium-text">
			<?php

			foreach ( $states as $status => $label ) {
				printf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $status ),
					selected( $status, $post->post_status, false ),
					esc_html( $label )
				);
			}

			?>
		</select>
	</p>
</div>

<div class="pronamic-pay-major-actions">
	<div class="pronamic-pay-action">
		<?php

		wp_nonce_field( 'pronamic_subscription_update', 'pronamic_subscription_nonce' );

		submit_button(
			__( 'Update', 'pronamic_ideal' ),
			'primary',
			'pronamic_subscription_update',
			false
		);

		?>
	</div>

	<div class="clear"></div>
</div>
