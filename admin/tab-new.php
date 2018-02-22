<?php
/**
 * Tab new
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Rabobank OmniKassa 2.0', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e(
	'Rabobank has introduced a new version of the OmniKassa payment platform. Pronamic Pay
now supports the new OmniKassa. To receive payments with your OmniKassa 2.0 account, create a new configuration via Pay »
Configurations and update the configuration in the payment method settings.', 'pronamic_ideal'
);

			?>
		</p>
	</div>

	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2017/12/pronamic-pay-omnikassa-2_0.jpg" />
	</div>
</div>


<div class="feature-section two-col">
	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2017/12/pronamic-pay-subscriptions-dates.jpg" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Better subscriptions', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e(
	'Pronamic Pay includes support for subscriptions to receive recurring payments. This version includes
improvements based on user feedback. Switching between subscriptions with WooCommerce
Subscriptions is now supported for example, and payment dates information has been extended and is more reliable.', 'pronamic_ideal'
);

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Restrict Content Pro', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

			esc_html_e(
				'Restrict Content Pro lets you sell memberships to provide access to your content. In this
version of Pronamic Pay we introduce support for this plugin. Recurring payments can be used, so subscription payments
are processed automatically when they are due for renewal. Recurring payments are now also
supported with s2Member (new shortcodes can be created with the included generator).', 'pronamic_ideal'
			);

			?>
		</p>
	</div>

	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2017/12/pronamic-pay-restrictcontentpro.jpg" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2017/12/pronamic-pay-gravityforms-delay-actions.jpg" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Gravity Forms + Moneybird', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e(
	'The Gravity Forms Moneybird Add-On plugin makes it possible to send invoices and estimates through a
Moneybird account for Gravity Forms entries. With this version of Pronamic Pay it is possible to delay the creation of
invoices and estimates until the payment has been received. Delaying these actions is now also possible for the Sliced
Invoices plugin.', 'pronamic_ideal'
);

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Check status of pending payments', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e(
	'We always put a lot of effort in under the hood improvements to make automatic payment status updates as
reliable as possible, but it might still occur that the payment status is pending for a prolonged period of time. With
supported payment providers, you can now manually check the payment status. The number of pending payments is
mentioned in the admin menu item badge.', 'pronamic_ideal'
);

			?>
		</p>
	</div>

	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2016/10/Pronamic-iDEAL-betalingsstatus-controleren.png" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2016/10/Pronamic-iDEAL-vernieuwd-betalingsoverzicht.png" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Refreshed payments overview', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e(
	'The payments list got a visual refresh. Most noticeable are the new status icons and updated default columns
to make the overview a lot cleaner and easier to use. Additional columns can be displayed by enabling them in the
"Screen Options" at the right top of the page.', 'pronamic_ideal'
);

			?>
		</p>
	</div>
</div>
