<?php
/**
 * Admin View: Notice - License
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$class = ( 'valid' === $data->license ) ? 'updated' : 'error';

?>
<div class="<?php echo esc_attr( $class ); ?>">
	<p>
		<strong><?php esc_html_e( 'Pronamic Pay', 'pronamic_ideal' ); ?></strong> —
		<?php

		if ( 'valid' === $data->license ) {
			esc_html_e( 'You succesfully activated your website.', 'pronamic_ideal' );
		} elseif ( 'invalid' === $data->license && 0 === $data->activations_left ) {
			echo wp_kses(
				__( 'This license does not have any activations left. Maybe you have to deactivate your license on a local/staging server. This can be done on your <a href="http://www.pronamic.eu/" target="_blank">Pronamic.eu account</a>.', 'pronamic_ideal' ),
				array(
					'a' => array(
						'href'   => true,
						'target' => true,
					),
				)
			);
		} else {
			esc_html_e( 'There was a problem activating your license key, please try again or contact support.', 'pronamic_ideal' );
		}

		?>
	</p>
</div>
