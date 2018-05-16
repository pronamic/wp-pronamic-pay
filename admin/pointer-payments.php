<?php
/**
 * Pointer Payments
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<h3><?php esc_html_e( 'Payments', 'pronamic_ideal' ); ?></h3>

<p>
	<?php esc_html_e( 'On the payments page you can find an overview off all the payments initiated on your WordPress site.', 'pronamic_ideal' ); ?>
	<?php esc_html_e( 'You can easily filter the payments by status or use the search field to search for specific payments.', 'pronamic_ideal' ); ?>
	<?php esc_html_e( 'We advice you to regular check the pending payments on this overview and adjust the status of these payments manual if needed.', 'pronamic_ideal' ); ?>
</p>

<div class="wp-pointer-buttons pp-pointer-buttons">
	<a href="<?php echo esc_attr( add_query_arg( 'page', 'pronamic_ideal', admin_url( 'edit.php' ) ) ); ?>" class="button-secondary pp-pointer-button-prev"><?php esc_html_e( 'Previous', 'pronamic_ideal' ); ?></a>

	<span class="pp-pointer-buttons-right">
		<a href="<?php echo esc_attr( add_query_arg( 'post_type', 'pronamic_gateway', admin_url( 'edit.php' ) ) ); ?>" class="button-primary pp-pointer-button-next"><?php esc_html_e( 'Next', 'pronamic_ideal' ); ?></a>

		<a href="<?php echo esc_attr( $this->get_close_url() ); ?>" class="button-secondary pp-pointer-button-close"><?php esc_html_e( 'Close', 'pronamic_ideal' ); ?></a>
	</span>
</div>
