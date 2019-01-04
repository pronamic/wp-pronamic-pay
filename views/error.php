<?php
/**
 * Error
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( is_wp_error( $error ) ) : ?>

	<div class="error">
		<?php

		foreach ( $error->get_error_codes() as $code ) {
			?>
			<dl>
				<dt><?php esc_html_e( 'Code', 'pronamic_ideal' ); ?></dt>
				<dd><?php echo esc_html( $code ); ?></dd>

				<dt><?php esc_html_e( 'Message', 'pronamic_ideal' ); ?></dt>
				<dd><?php echo esc_html( $error->get_error_message( $code ) ); ?></dd>
			</dl>

			<?php

			if ( 'ideal_advanced_v3_error' === $code ) {
				$ideal_error = $error->get_error_data( $code );

				if ( $ideal_error instanceof \Pronamic\WordPress\Pay\Gateways\IDealAdvancedV3\Error ) :

					?>

					<dl>
						<dt><?php esc_html_e( 'Code', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo esc_html( $ideal_error->get_code() ); ?></dd>

						<dt><?php esc_html_e( 'Message', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo esc_html( $ideal_error->get_message() ); ?></dd>

						<dt><?php esc_html_e( 'Detail', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo esc_html( $ideal_error->get_detail() ); ?></dd>

						<dt><?php esc_html_e( 'Suggested Action', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo esc_html( $ideal_error->get_suggested_action() ); ?></dd>

						<dt><?php esc_html_e( 'Consumer Message', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo esc_html( $ideal_error->get_consumer_message() ); ?></dd>
					</dl>

					<?php

				endif;
			}
		}

		?>
	</div>

<?php endif; ?>
