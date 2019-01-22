<?php
/**
 * Pointer Gateways
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<h3><?php esc_html_e( 'Configurations', 'pronamic_ideal' ); ?></h3>

<p>
	<?php esc_html_e( 'Here you can find an overview of all the gateway configurations on your WordPress site.', 'pronamic_ideal' ); ?>
	<?php esc_html_e( 'To use the Pronamic Pay plugin you have to add an payment gateway configuration.', 'pronamic_ideal' ); ?>
	<?php esc_html_e( 'Normally you get the required configuration information from your payment provider.', 'pronamic_ideal' ); ?>
</p>

<div class="wp-pointer-buttons pp-pointer-buttons">
	<a href="<?php echo esc_attr( add_query_arg( 'post_type', 'pronamic_payment', admin_url( 'edit.php' ) ) ); ?>" class="button-secondary pp-pointer-button-prev"><?php esc_html_e( 'Previous', 'pronamic_ideal' ); ?></a>

	<span class="pp-pointer-buttons-right">
		<a href="<?php echo esc_attr( add_query_arg( 'post_type', 'pronamic_pay_form', admin_url( 'edit.php' ) ) ); ?>" class="button-primary pp-pointer-button-next"><?php esc_html_e( 'Next', 'pronamic_ideal' ); ?></a>

		<a href="<?php echo esc_attr( $this->get_close_url() ); ?>" class="button-secondary pp-pointer-button-close"><?php esc_html_e( 'Close', 'pronamic_ideal' ); ?></a>
	</span>
</div>
