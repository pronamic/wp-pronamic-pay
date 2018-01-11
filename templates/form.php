<?php

global $pronamic_pay_errors;

$config_id = get_post_meta( $id, '_pronamic_payment_form_config_id', true );

$button_text = get_post_meta( $id, '_pronamic_payment_form_button_text', true );
$button_text = empty( $button_text ) ? __( 'Pay Now', 'pronamic_ideal' ) : $button_text;

$amount_method  = get_post_meta( $id, '_pronamic_payment_form_amount_method', true );
$amount_choices = get_post_meta( $id, '_pronamic_payment_form_amount_choices', true );

$methods_with_choices = array(
	\Pronamic\WordPress\Pay\Forms\FormPostType::AMOUNT_METHOD_CHOICES_ONLY,
	\Pronamic\WordPress\Pay\Forms\FormPostType::AMOUNT_METHOD_CHOICES_AND_INPUT,
);

$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $config_id );

$amount_value = '';

if ( filter_has_var( INPUT_GET, 'amount' ) ) {
	$amount_value = filter_input( INPUT_GET, 'amount', FILTER_SANITIZE_STRING );
}

if ( $gateway ) : ?>

	<div class="pronamic-pay-form-wrap">

		<?php if ( ! is_singular( 'pronamic_pay_form' ) ) : ?>

			<h2 class="pronamic-pay-form-title"><?php echo esc_html( get_the_title( $id ) ); ?></h2>

		<?php endif; ?>

		<form id="pronamic-pay-form-<?php echo esc_attr( $id ); ?>" class="pronamic-pay-form" method="post">
			<?php if ( in_array( $amount_method, $methods_with_choices, true ) ) : ?>

			<fieldset>
				<legend><?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?></legend>

			<?php endif; ?>

				<div class="pronamic-pay-amount pronamic-pay-form-row-wide">
					<?php if ( in_array( $amount_method, $methods_with_choices, true ) ) : ?>

							<?php foreach ( $amount_choices as $amount ) : ?>

								<?php

								$input_id = 'pronamic-pay-amount-' . esc_attr( $amount );

								$decimals = ( $amount % 100 > 0 ? 2 : 0 );

								$amount_formatted = number_format( ( $amount / 100 ), $decimals, pronamic_pay_get_decimal_separator(), pronamic_pay_get_thousands_separator() );

								?>

								<div>
									<input class="pronamic-pay-amount-input pronamic-pay-input" id="<?php echo esc_attr( $input_id ); ?>" name="pronamic_pay_amount" type="radio" required="required" value="<?php echo esc_attr( $amount ); ?>" />
									<label for="<?php echo esc_attr( $input_id ); ?>">
										<span class="pronamic-pay-currency-symbol pronamic-pay-currency-position-before">€</span>
										<span class="pronamic-pay-amount-value"><?php echo esc_html( $amount_formatted ); ?></span>
									</label>
								</div>

							<?php endforeach; ?>

							<?php if ( \Pronamic\WordPress\Pay\Forms\FormPostType::AMOUNT_METHOD_CHOICES_AND_INPUT === $amount_method ) : ?>

								<div>
									<input class="pronamic-pay-amount-input pronamic-pay-input" id="pronamic-pay-amount-other" name="pronamic_pay_amount" type="radio" required="required" value="other" />
									<label for="pronamic-pay-amount-other">
										<span class="pronamic-pay-currency-symbol pronamic-pay-currency-position-before">€</span>
										<input class="pronamic-pay-amount-input pronamic-pay-input" id="pronamic-pay-amount" name="pronamic_pay_amount_other" type="text" placeholder="" autocomplete="off" value="<?php echo esc_attr( $amount_value ); ?>" />
									</label>
								</div>

							<?php endif; ?>

					<?php endif; ?>

					<?php if ( \Pronamic\WordPress\Pay\Forms\FormPostType::AMOUNT_METHOD_INPUT_ONLY === $amount_method ) : ?>

						<span class="pronamic-pay-currency-symbol pronamic-pay-currency-position-before">€</span>
						<input class="pronamic-pay-amount-input pronamic-pay-input" id="pronamic-pay-amount" name="pronamic_pay_amount" type="text" placeholder="" autocomplete="off" value="<?php echo esc_attr( $amount_value ); ?>" />

					<?php endif; ?>
				</div>

			<?php if ( in_array( $amount_method, $methods_with_choices, true ) ) : ?>

			</fieldset>

			<?php endif; ?>

			<fieldset>
				<legend><?php esc_html_e( 'Personal Info', 'pronamic_ideal' ); ?></legend>

				<p class="pronamic-pay-form-row pronamic-pay-form-row-first">
					<label class="pronamic-pay-label" for="pronamic-pay-first-name">
						<?php esc_html_e( 'First Name', 'pronamic_ideal' ); ?> <span class="pronamic-pay-required-indicator">*</span>
					</label>

					<input class="pronamic-pay-input pronamic-pay-required" type="text" name="pronamic_pay_first_name" placeholder="<?php esc_attr_e( 'First Name', 'pronamic_ideal' ); ?>" id="pronamic-pay-first-name" required="required" value="" />
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

					<input class="pronamic-pay-input required" type="email" name="pronamic_pay_email" placeholder="<?php esc_attr_e( 'Email Address', 'pronamic_ideal' ); ?>" id="pronamic-pay-email" required="required" value="" />
				</p>
			</fieldset>

			<?php

			if ( $gateway->payment_method_is_required() ) {

				$gateway->set_payment_method( Pronamic_WP_Pay_PaymentMethods::IDEAL );

			}

			$fields = $gateway->get_input_fields();

			?>

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

<?php endif; ?>
