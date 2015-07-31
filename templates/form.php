<div class="pronamic-pay-form-wrap">
	<h2 class="pronamic-pay-form-title"><?php echo esc_html( get_the_title( $id ) ); ?></h2>

	<form id="pronamic-pay-form-<?php echo esc_attr( $id ); ?>" class="pronamic-pay-form" method="post">
		<div class="pronamic-pay-amount pronamic-pay-form-row-wide">
			<span class="pronamic-pay-currency-symbol pronamic-pay-currency-position-before">€</span>
			<input class="pronamic-pay-text-input" id="pronamic-pay-amount" name="pronamic_pay_amount" type="text" placeholder="" value="0,00" required="" autocomplete="off" style="cursor: auto;" />
		</div>

		<fieldset>
			<legend>Personal Info</legend>

			<p class="pronamic-pay-form-row pronamic-pay-form-row-first">
				<label class="pronamic-pay-label" for="pronamic-pay-first-name">
					First Name <span class="pronamic-pay-required-indicator">*</span>
				</label>

				<input class="pronamic-pay-input pronamic-pay-required" type="text" name="pronamic_pay_first_name" placeholder="First name" id="pronamic-pay-first-name" value="" />
			</p>

			<p class="pronamic-pay-form-row pronamic-pay-form-row-last">
				<label class="pronamic-pay-label" for="pronamic-pay-last-name">
					Last Name
				</label>

				<input class="pronamic-pay-input" type="text" name="pronamic_pay_last_name" id="pronamic-pay-last-name" placeholder="Last name" value="" />
			</p>

			<p class="pronamic-pay-form-row pronamic-pay-form-row-wide">
				<label class="pronamic-pay-label" for="pronamic-pay-email">
					Email Address
					<span class="pronamic-pay-required-indicator">*</span>
				</label>

				<input class="pronamic-pay-input required" type="email" name="pronamic_pay_email" placeholder="Email address" id="pronamic-pay-email" value="" />
			</p>
		</fieldset>

		<div class="pronamic-pay-submit-button-wrap pronamic-pay-clearfix">
			<input type="submit" class="give-submit give-btn" id="give-purchase-button" name="give-purchase" value="Donate Now" />
		</div>
	</form>
</div>
