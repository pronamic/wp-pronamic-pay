<?php
/**
 * Redirect via HTML
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />

		<title><?php esc_html_e( 'Redirecting…', 'pronamic_ideal' ); ?></title>

		<?php wp_print_styles( 'pronamic-pay-redirect' ); ?>

		<script>
		// Break out of iframe.
		if ( window.top.location !== window.location ) {
			window.top.location = window.location;
		}
		</script>
	</head>

	<?php

	$auto_submit = true;

	if ( PRONAMIC_PAY_DEBUG ) {
		$auto_submit = false;
	}

	$onload = $auto_submit ? 'document.forms[0].submit();' : '';

	?>

	<body onload="<?php esc_attr( $onload ); ?>">
		<div class="pronamic-pay-redirect-page">
			<div class="pronamic-pay-redirect-container">
				<h1><?php esc_html_e( 'Redirecting…', 'pronamic_ideal' ); ?></h1>

				<p>
					<?php esc_html_e( 'You will be automatically redirected to the online payment environment.', 'pronamic_ideal' ); ?>
				</p>

				<p>
					<?php esc_html_e( 'Please click the button below if you are not automatically redirected.', 'pronamic_ideal' ); ?>
				</p>

				<?php

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->get_form_html( $payment, $auto_submit );

				?>
			</div>
		</div>
	</body>
</html>
