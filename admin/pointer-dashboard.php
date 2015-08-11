<h3><?php esc_html_e( 'Dashboard', 'pronamic_ideal' ); ?></h3>

<p>
	<?php esc_html_e( 'The Pronamic iDEAL dashboard.', 'pronamic_ideal' ); ?>
</p>

<div class="wp-pointer-buttons pp-pointer-buttons">
	<span class="pp-pointer-buttons-right">
		<a href="<?php echo esc_attr( add_query_arg( 'post_type', 'pronamic_payment', admin_url( 'edit.php' ) ) ); ?>" class="button-primary pp-pointer-button-next"><?php _e( 'Next', 'pronamic_ideal' ); ?></a>

		<a href="<?php echo esc_attr( $this->get_close_url() ); ?>" class="button-secondary pp-pointer-button-close"><?php _e( 'Close', 'pronamic_ideal' ); ?></a>
	</span>
</div>
