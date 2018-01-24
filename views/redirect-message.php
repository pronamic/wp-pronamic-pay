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

		<title><?php esc_html_e( 'Payment notice', 'pronamic_ideal' ); ?></title>

		<?php wp_print_styles( 'pronamic-pay-redirect' ); ?>
	</head>

	<body>
		<div class="pronamic-pay-redirect-page">
			<div class="pronamic-pay-redirect-container alignleft">
				<p>
					<?php

					echo wpautop( $redirect_message ); //xss ok

					?>
				</p>
			</div>
		</div>
	</body>
</html>
