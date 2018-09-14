<?php
/**
 * Tab New
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Recurring payments for MemberPress', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

			esc_html_e(
				'We do our best to bring recurring payments support to as many plugins as possible. After plugins such
as Gravity Forms and WooCommerce Subscriptions, this functionality can now also be used with MemberPress. The plugin
provides the ability to offer memberships to your visitors and the subscription amount can now be charged automatically.
Recurring payments are only supported with payment provider Mollie for now.',
				'pronamic_ideal'
			);

			?>
		</p>
	</div>

	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2018/05/pronamic-pay-memberpress-recurring.jpg" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2018/05/pronamic-pay-google-analytics-ecommerce-tracking.jpg" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Google Analytics e-commerce tracking', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

			esc_html_e(
				'It is now possible to set a Google Analytics tracking ID in the plugin settings. A
completed payment will automatically be send to Google (in live mode). The payment will be linked to
the session of the visitor, which allows you to use visitor revenue in Google Analytics reports.',
				'pronamic_ideal'
			);

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Better subscriptions', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e(
	'Pronamic Pay includes support for subscriptions to receive recurring payments. This version includes
improvements based on user feedback. Switching between subscriptions with WooCommerce
Subscriptions is now supported for example, and payment dates information has been extended and is more reliable.',
	'pronamic_ideal'
);

			?>
		</p>
	</div>

	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2017/12/pronamic-pay-subscriptions-dates.jpg" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2017/12/pronamic-pay-restrictcontentpro.jpg" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Restrict Content Pro', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

			esc_html_e(
				'Restrict Content Pro lets you sell memberships to provide access to your content. In this
version of Pronamic Pay we introduce support for this plugin. Recurring payments can be used, so subscription payments
are processed automatically when they are due for renewal. Recurring payments are now also
supported with s2Member (new shortcodes can be created with the included generator).',
				'pronamic_ideal'
			);

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Refreshed payments overview', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e(
	'The payments list got a visual refresh. Most noticeable are the new status icons and updated default columns
to make the overview a lot cleaner and easier to use. Additional columns can be displayed by enabling them in the
"Screen Options" at the right top of the page.',
	'pronamic_ideal'
);

			?>
		</p>
	</div>

	<div class="col">
		<img src="https://www.pronamic.nl/wp-content/uploads/2016/10/Pronamic-iDEAL-vernieuwd-betalingsoverzicht.png" />
	</div>
</div>
