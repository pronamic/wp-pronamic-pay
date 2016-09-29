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
		<h3><?php esc_html_e( 'Simple Payment Forms', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'It is now possible to create simple payment forms.
These forms have a fixed design and fixed fields.
Each form has a payment amount, name and e-mail address field.
That way, users can easily make available payment forms.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>

	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/forms.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/reports.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Payments Reports', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'Under iDEAL » Reports you can find a graph with all payments of this year.
The number of open, successful, canceled, expired and failed payments are grouped.
That way you get a good idea of the number of payments and their status.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Badge Pending Payments', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'We noticed that many iDEAL users have problems with the feedback of the payment status.
We have added an badge to give users more insight in payments with the pending status.
Through this badge you can immediately see how many payments have the pending status.
We advise users to monitor these payments periodically.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>

	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/badge.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/filter.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Payment Status Filter', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'It is now also possible to view only the payments to a specific payment status.
So you can now easily see a list of payments with the status pending.
That way you have a better understanding of your payments and their status.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<h3><?php esc_html_e( 'Edit Payment Status', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'The payment status of payments can also be updated manually.
Handy in case your payment provider payment status has not communicated well or the Pronamic iDEAL this plugin could not retrieve the status.
That way you can always keep empty the list of payments pending.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>

	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/edit-payment-status.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>
</div>

<div class="feature-section two-col">
	<div class="col">
		<img src="<?php echo esc_attr( plugins_url( 'images/new/tour.png', Pronamic_WP_Pay_Plugin::$file ) ); ?>" />
	</div>

	<div class="col">
		<h3><?php esc_html_e( 'Tour', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

esc_html_e( 'For new users, we have added a simle tour functionality to the Pronamic iDEAL plugin.
This tour shows the main pages of the Pronamic iDEAL plugin.
That way you can quickly find your way around the Pronamic iDEAL plugin.'
, 'pronamic_ideal' );

			?>
		</p>
	</div>
</div>
