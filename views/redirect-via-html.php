<?php

global $pronamic_ideal;

use Pronamic\WordPress\Pay\Plugin;

$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

wp_register_style(
	'pronamic-pay-redirect',
	plugins_url( 'css/redirect' . $min . '.css', Plugin::$file ),
	array(),
	$pronamic_ideal->get_version()
);

?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />

		<title><?php esc_html_e( 'Redirecting…', 'pronamic_ideal' ); ?></title>

		<?php wp_print_styles( 'pronamic-pay-redirect' ); ?>
	</head>

	<?php

	$auto_submit = true;

	if ( defined( 'PRONAMIC_PAY_DEBUG' ) && PRONAMIC_PAY_DEBUG ) {
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

				echo $this->get_form_html( $payment, $auto_submit ); // WPCS: XSS ok.

				?>
			</div>
		</div>
	</body>
</html>
