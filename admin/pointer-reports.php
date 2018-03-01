<?php
/**
 * Pointer Reports
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<h3><?php esc_html_e( 'Reports', 'pronamic_ideal' ); ?></h3>

<p>
	<?php esc_html_e( 'The Pronamic Pay reports page shows you an graph of all the payments of this year.', 'pronamic_ideal' ); ?>
	<?php esc_html_e( 'You can see the number of succesfull payments and the total amount of pending, succesfull, cancelled and failed payments.', 'pronamic_ideal' ); ?>
</p>

<div class="wp-pointer-buttons pp-pointer-buttons">
	<a href="<?php echo esc_attr( add_query_arg( 'page', 'pronamic_pay_tools', admin_url( 'admin.php' ) ) ); ?>" class="button-secondary pp-pointer-button-prev"><?php esc_html_e( 'Previous', 'pronamic_ideal' ); ?></a>

	<span class="pp-pointer-buttons-right">
		<a href="<?php echo esc_attr( add_query_arg( 'page', 'pronamic_pay_tools', admin_url( 'admin.php' ) ) ); ?>" class="button-primary pp-pointer-button-next"><?php esc_html_e( 'Next', 'pronamic_ideal' ); ?></a>

		<a href="<?php echo esc_attr( $this->get_close_url() ); ?>" class="button-secondary pp-pointer-button-close"><?php esc_html_e( 'Close', 'pronamic_ideal' ); ?></a>
	</span>
</div>
