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
		<h3><?php esc_html_e( 'Menu name and icon', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'Most of our users already know that Pronamic iDEAL supports other payment methods besides iDEAL. With this
release we therefore changed the name of the menu item from "iDEAL" to "Pay". The icon has been changed to the WordPress
pay icon instead of the iDEAL logo. These changes make the WordPress admin menu look cleaner and it is clearer that the
plugin is not only for iDEAL payments anymore.', 'pronamic_ideal' );

			?>
		</p>
	</div>

	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/admin-menu-name-icon.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>
</div>


<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/recurring.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Recurring payments', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'Recurring payments are now supported with the payment provider Mollie. A mandate which makes automatic
recurring payments possible, can be created with a first payment via either iDEAL or credit card by using the new
subscriptions feature. Recurring payments are recognizable by the repeater icon in the payments overview.',
'pronamic_ideal' );

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Subscriptions', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'Subscriptions are now supported and have a dedicated admin page to track which subscriptions are created
and to view related payments and their status. The new `Direct Debit (mandate via iDEAL)` and credit card payment methods
can be used with WooCommerce Subscriptions to set up automatically recurring payments for subscriptions.', 'pronamic_ideal' );

			?>
		</p>
	</div>

	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/subscriptions.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/payment-status-check-ingenico.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Payment status requests for Ingenico', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'The Pronamic iDEAL plugin processes payment status update that are sent to your website by the payment provider.
But we can also request the payment status automatically for you. Ingenico (previously Ogone) is now added to the list
of payment providers which support this feature (iDEAL Advanced/Professional, ING Kassa Compleet, Mollie, Pay.nl and
Targetpay). Make sure to add API user credentials to your Ingenico configuration to benefit.', 'pronamic_ideal' );

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Check status of pending payments', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'We always put a lot of effort in under the hood improvements to make automatic payment status updates as
reliable as possible, but it might still occur that the payment status is pending for a prolonged period of time. With
supported payment providers, you can now manually check the payment status. The number of pending payments is
mentioned in the admin menu item badge.', 'pronamic_ideal' );

			?>
		</p>
	</div>

	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/payment-status-check.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/payments.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Refreshed payments overview', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'The payments list got a visual refresh. Most noticeable are the new status icons and updated default columns
to make the overview a lot cleaner and easier to use. Additional columns can be displayed by enabling them in the
"Screen Options" at the right top of the page.', 'pronamic_ideal' );

			?>
		</p>
	</div>
</div>
