<h3><?php esc_html_e( 'Payments', 'pronamic_ideal' ); ?></h3>

<p>
	<?php esc_html_e( 'Here you can find an overview of all the payment initiated on your WordPres site.', 'pronamic_ideal' ); ?>
</p>

<div class="wp-pointer-buttons pp-pointer-buttons">
	<a href="<?php echo esc_attr( add_query_arg( 'page', 'pronamic_ideal', admin_url( 'edit.php' ) ) ); ?>" class="button-secondary pp-pointer-button-prev"><?php esc_html_e( 'Previous', 'pronamic_ideal' ); ?></a>

	<span class="pp-pointer-buttons-right">
		<a href="<?php echo esc_attr( add_query_arg( 'post_type', 'pronamic_gateway', admin_url( 'edit.php' ) ) ); ?>" class="button-primary pp-pointer-button-next"><?php esc_html_e( 'Next', 'pronamic_ideal' ); ?></a>

		<a href="<?php echo esc_attr( $this->get_close_url() ); ?>" class="button-secondary pp-pointer-button-close"><?php esc_html_e( 'Close', 'pronamic_ideal' ); ?></a>
	</span>
</div>
