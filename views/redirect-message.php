<?php
/**
 * Redirect message
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />

		<title><?php esc_html_e( 'Payment notice', 'pronamic_ideal' ); ?></title>

		<?php wp_print_styles( 'pronamic-pay-redirect' ); ?>
	</head>

	<body>
		<div class="pronamic-pay-redirect-page">
			<div class="pronamic-pay-redirect-container">
				<div class="pp-page-section-container">
					<div class="pp-page-section-wrapper">
						<p>
							<?php

							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo wpautop( $redirect_message );

							?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
