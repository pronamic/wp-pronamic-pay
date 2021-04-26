<?php
/**
 * Tab New
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="feature-section two-col has-2-columns is-fullwidth">
	<div class="col column">
		<h3><?php esc_html_e( 'New Logo', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

			esc_html_e(
				'With the newest 5.8 version of Pronamic Pay we’re introducing a totally new brand identity. Just as our Pronamic logo we’re using the letter ‘P’, from Pay, as a recognition point. Our new colors yellow and blue are supporting the sector. Yellow stands for prosperity and gold. Blue stands for safety and our Dutch roots.',
				'pronamic_ideal'
			);

			?>
		</p>

		<p>
			<?php

			esc_html_e(
				'With this new identity we are trying to create a uniform and recognizable brand for the future. Our new brand logo and identity is visible when you visit our WordPress plugin page. Also, after updating to version 5.8, a new WordPress admin icon is shown.',
				'pronamic_ideal'
			);

			?>
		</p>
	</div>

	<div class="col column">
		<img src="<?php echo esc_url( __( 'https://www.wp-pay.org/wp-content/uploads/2019/10/pronamic-pay-5.8-update-banner-en.png', 'pronamic_ideal' ) ); ?>" alt="" />
	</div>
</div>

<div class="feature-section two-col has-2-columns is-fullwidth">
	<div class="col column">
		<img src="https://www.wp-pay.org/wp-content/uploads/2019/10/pronamic-pay-admin-site-health.png" alt="" />
	</div>

	<div class="col column">
		<h3><?php esc_html_e( 'WordPress Health Status', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

			esc_html_e(
				'WordPress version 5.2 introduced the WordPress Health Status page. We’ve migrated our Pronamic Pay status page and combined it with the new WordPress Health Status page. You can find the new page in your dashboard under ‘Tools’ » ‘Site Health’.',
				'pronamic_ideal'
			);

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col has-2-columns is-fullwidth">
	<div class="col column">
		<h3><?php esc_html_e( 'Rabobank - OmniKassa 2.0', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

			esc_html_e(
				'We’ve enhanced the ‘Order ID’ reference that our plugin communicates and gives back to OmniKassa 2.0. The length of the payment reference is increased from 10 to 24 characters. Our plugin monitor reads the given ‘Order ID’ and checks if it answers the OmniKassa 2.0 demands. The reference field may only contain alphanumeric characters (a-z, A-Z, 0-9). Other characters, for example ‘.’, ‘@’, ‘_’, are not allowed.',
				'pronamic_ideal'
			);

			?>
		</p>
	</div>

	<div class="col column">
		<img src="https://www.wp-pay.org/wp-content/uploads/2019/10/pronamic-pay-rabobank-omnikassa-2-order-id-24-chars.png" alt="" />
	</div>
</div>

<div class="feature-section two-col has-2-columns is-fullwidth">
	<div class="col column">
		<img src="https://www.wp-pay.org/wp-content/uploads/2019/10/pronamic-pay-gravity-forms-support.png" alt="" />
	</div>

	<div class="col column">
		<h3><?php esc_html_e( 'Gravity Forms', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

			esc_html_e(
				'Support for the Gravity Forms plugin within a WordPress multisite is enhanced. Our Gravity Forms extension is not loaded if Gravity Forms is not activated in one of the network sites. We’ve also updated the Gravity Forms internationalisation. The text strings in our extension are now better translatable, even if your WordPress site uses a Right-to-Left text direction.',
				'pronamic_ideal'
			);

			?>
		</p>
	</div>
</div>

<div class="feature-section two-col has-2-columns is-fullwidth">
	<div class="col column">
		<h3><?php esc_html_e( 'Restrict Content Pro', 'pronamic_ideal' ); ?></h3>

		<p>
			<?php

			esc_html_e(
				'The team of Sandhills Development, the developers behind Restrict Content Pro are very active. Version 3.0 of their plugin is released in february 2019. With this 3.0 release we needed to update our support for the plugin. Sadly some new features in Restrict Content Pro 3.0 weren’t backwards compatible. Therefore we needed to rewrite some elements of our extension. The biggest improvement within Restrict Content Pro 3.0 regards the membership renewal process. After a recurring payment is created a membership is instantly extended. Previously this would only happen after a payment was successful, this process could take up to 5 days. Now if a payment fails or expires the membership will temporarily stop and will get the ‘pending’ status.',
				'pronamic_ideal'
			);

			?>
		</p>
	</div>

	<div class="col column">
		<img src="https://www.wp-pay.org/wp-content/uploads/2019/10/pronamic-pay-restrict-content-pro-3-support.png" alt="" />
	</div>
</div>
