<?php

global $pronamic_pay_errors;

$config_id = get_post_meta( $id, '_pronamic_pay_form_config_id', true );

$button_text = get_post_meta( $id, '_pronamic_pay_button_text', true );
$button_text = empty( $button_text ) ? __( 'Pay Now', 'pronamic_ideal' ) : $button_text;

$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $config_id );

?>
<div class="pronamic-pay-form-wrap">
	<h2 class="pronamic-pay-form-title"><?php echo esc_html( get_the_title( $id ) ); ?></h2>

	<form id="pronamic-pay-form-<?php echo esc_attr( $id ); ?>" class="pronamic-pay-form" method="post">
		<div class="pronamic-pay-amount pronamic-pay-form-row-wide">
			<span class="pronamic-pay-currency-symbol pronamic-pay-currency-position-before">€</span>
			<input class="pronamic-pay-amount-input pronamic-pay-input" id="pronamic-pay-amount" name="pronamic_pay_amount" type="text" placeholder="" value="<?php echo esc_attr( number_format_i18n( 0 ) ); ?>" required="required" autocomplete="off" />
		</div>

		<fieldset>
			<legend><?php esc_html_e( 'Personal Info', 'pronamic_ideal' ); ?></legend>

			<p class="pronamic-pay-form-row pronamic-pay-form-row-first">
				<label class="pronamic-pay-label" for="pronamic-pay-first-name">
					<?php esc_html_e( 'First Name', 'pronamic_ideal' ); ?> <span class="pronamic-pay-required-indicator">*</span>
				</label>

				<input class="pronamic-pay-input pronamic-pay-required" type="text" name="pronamic_pay_first_name" placeholder="<?php esc_attr_e( 'First Name', 'pronamic_ideal' ); ?>" id="pronamic-pay-first-name" value="" />
			</p>

			<p class="pronamic-pay-form-row pronamic-pay-form-row-last">
				<label class="pronamic-pay-label" for="pronamic-pay-last-name">
					<?php esc_html_e( 'Last Name', 'pronamic_ideal' ); ?>
				</label>

				<input class="pronamic-pay-input" type="text" name="pronamic_pay_last_name" id="pronamic-pay-last-name" placeholder="<?php esc_attr_e( 'Last Name', 'pronamic_ideal' ); ?>" value="" />
			</p>

			<p class="pronamic-pay-form-row pronamic-pay-form-row-wide">
				<label class="pronamic-pay-label" for="pronamic-pay-email">
					<?php esc_html_e( 'Email Address', 'pronamic_ideal' ); ?>
					<span class="pronamic-pay-required-indicator">*</span>
				</label>

				<input class="pronamic-pay-input required" type="email" name="pronamic_pay_email" placeholder="<?php esc_attr_e( 'Email Address', 'pronamic_ideal' ); ?>" id="pronamic-pay-email" value="" />
			</p>
		</fieldset>

		<?php $fields = $gateway->get_input_fields(); ?>

		<?php if ( ! empty( $fields ) ) : ?>

			<fieldset>
				<legend><?php esc_html_e( 'Payment Info', 'pronamic_ideal' ); ?></legend>

				<?php foreach ( $fields as $i => $field ) : ?>

					<p class="pronamic-pay-form-row pronamic-pay-form-row-wide">
						<label class="pronamic-pay-label" for="<?php echo esc_attr( $field['id'] ); ?>">
							<?php echo esc_html( $field['label'] ); ?>
							<span class="pronamic-pay-required-indicator">*</span>
						</label>

						<?php if ( 'select' === $field['type'] ) : ?>

							<select id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>">
								<?php echo Pronamic_WP_HTML_Helper::select_options_grouped( $field['choices'] ); ?>
							</select>

						<?php endif; ?>
					</p>

				<?php endforeach; ?>

			</fieldset>

		<?php endif; ?>

		<?php if ( ! empty( $pronamic_pay_errors ) ) : ?>

			<div class="pronamic-pay-errors">

				<?php foreach ( $pronamic_pay_errors as $error ) : ?>

					<p class="pronamic-pay-error">
						<strong><?php esc_html_e( 'Error', 'pronamic_ideal' ); ?></strong>: <?php echo esc_html( $error ); ?>
					</p>

				<?php endforeach; ?>

			</div>

		<?php endif; ?>

		<div class="pronamic-pay-submit-button-wrap pronamic-pay-clearfix">
			<?php wp_nonce_field( 'pronamic_pay', 'pronamic_pay_nonce' ); ?>

			<input type="hidden" name="pronamic_pay_form_id" value="<?php echo esc_attr( $id ); ?>" />

			<input type="submit" class="pronamic-pay-submit pronamic-pay-btn" id="pronamic-pay-purchase-button" name="pronamic_pay" value="<?php echo esc_attr( $button_text ); ?>" />
		</div>
	</form>
</div>
