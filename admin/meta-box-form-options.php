<?php wp_nonce_field( 'pronamic_pay_save_form_options', 'pronamic_pay_nonce' ); ?>

<table class="form-table">
	<tr>
		<th scope="row">
			<label for="_pronamic_pay_gf_config_id">
				<?php esc_html_e( 'Configuration', 'pronamic_ideal' ); ?>
			</label>
		</th>
		<td>
			<?php

			$config_id = get_post_meta( $post->ID, '_pronamic_pay_form_config_id', true );

			Pronamic_WP_Pay_Admin::dropdown_configs( array(
				'name'     => '_pronamic_pay_form_config_id',
				'selected' => $config_id,
			) );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="_pronamic_pay_button_text">
				<?php esc_html_e( 'Button Text', 'pronamic_ideal' ); ?>
			</label>
		</th>
		<td>
			<?php $button_text = get_post_meta( $post->ID, '_pronamic_pay_button_text', true ); ?>

			<input class="regular-text" type="text" name="_pronamic_pay_button_text" value="<?php echo esc_attr( $button_text ); ?>" placeholder="<?php esc_attr_e( 'Pay Now', 'pronamic_ideal' ); ?>" />
		</td>
	</tr>
</table>
