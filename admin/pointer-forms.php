<?php
/**
 * Pointer Forms
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<h3><?php esc_html_e( 'Payment Forms', 'pronamic_ideal' ); ?></h3>

<p>
	<?php esc_html_e( 'On the payment forms page you can add, edit or delete simple payment forms.', 'pronamic_ideal' ); ?>
	<?php esc_html_e( 'Currently it’s not possible to adjust the form fields or styling of these forms.', 'pronamic_ideal' ); ?>
	<?php

	echo wp_kses(
		sprintf(
			/* translators: 1: Gravity Forms link, 2: _blank */
			__( 'For more advanced payment forms we advice you to use the <a href="%1$s" target="%2$s">“Gravity Forms” plugin</a>.', 'pronamic_ideal' ),
			esc_attr( 'https://www.pronamic.nl/go/gravity-forms/' ),
			esc_attr( '_blank' )
		),
		array(
			'a' => array(
				'href'   => true,
				'target' => true,
			),
		)
	);

	?>
</p>

<div class="wp-pointer-buttons pp-pointer-buttons">
	<a href="<?php echo esc_attr( add_query_arg( 'post_type', 'pronamic_gateway', admin_url( 'edit.php' ) ) ); ?>" class="button-secondary pp-pointer-button-prev"><?php esc_html_e( 'Previous', 'pronamic_ideal' ); ?></a>

	<span class="pp-pointer-buttons-right">
		<a href="<?php echo esc_attr( add_query_arg( 'page', 'pronamic_pay_reports', admin_url( 'admin.php' ) ) ); ?>" class="button-primary pp-pointer-button-next"><?php esc_html_e( 'Next', 'pronamic_ideal' ); ?></a>

		<a href="<?php echo esc_attr( $this->get_close_url() ); ?>" class="button-secondary pp-pointer-button-close"><?php esc_html_e( 'Close', 'pronamic_ideal' ); ?></a>
	</span>
</div>
