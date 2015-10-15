<h3><?php esc_html_e( 'Tools', 'pronamic_ideal' ); ?></h3>

<p>
	<?php esc_html_e( 'On the tools page you can find some additional features and information.', 'pronamic_ideal' ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'System Status', 'pronamic_ideal' ); ?></strong><br />
	<?php esc_html_e( 'The system status page gives you some basic information about your WordPress installation which come in handy if you are having issues.', 'pronamic_ideal' ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'iDEAL Status', 'pronamic_ideal' ); ?></strong><br />
	<?php esc_html_e( 'The iDEAL status page gives you information about the iDEAL status of the various banks who offer iDEAL.', 'pronamic_ideal' ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'Pages Generator', 'pronamic_ideal' ); ?></strong><br />
	<?php esc_html_e( 'With the pages generator tool you can easily generate some optional payment status pages.', 'pronamic_ideal' ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'Gateways', 'pronamic_ideal' ); ?></strong><br />
	<?php esc_html_e( 'The gateways page gives you an overview of all the gateways that er supported by the Pronamic iDEAL plugin.', 'pronamic_ideal' ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'Extensions', 'pronamic_ideal' ); ?></strong><br />
	<?php esc_html_e( 'The extensions page gives you an overview of all the extensions that er supported by the Pronamic iDEAL plugin.', 'pronamic_ideal' ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'Methods', 'pronamic_ideal' ); ?></strong><br />
	<?php esc_html_e( 'The methods page gives you an overview of all the methods that er supported by the Pronamic iDEAL plugin.', 'pronamic_ideal' ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'Documentation', 'pronamic_ideal' ); ?></strong><br />
	<?php esc_html_e( 'The documentation page has an overview of all kind of documentation files from different payment providers.', 'pronamic_ideal' ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'Brand', 'pronamic_ideal' ); ?></strong><br />
	<?php esc_html_e( 'The brand page has some additional images and banners which you could add to your WordPress website.', 'pronamic_ideal' ); ?>
</p>

<p>
	<strong><?php esc_html_e( 'License', 'pronamic_ideal' ); ?></strong><br />
	<?php esc_html_e( 'The license page gives you additional information about your license and license key.', 'pronamic_ideal' ); ?>
</p>

<div class="wp-pointer-buttons pp-pointer-buttons">
	<a href="<?php echo esc_attr( add_query_arg( 'page', 'pronamic_pay_settings', admin_url( 'admin.php' ) ) ); ?>" class="button-secondary pp-pointer-button-prev"><?php esc_html_e( 'Previous', 'pronamic_ideal' ); ?></a>

	<span class="pp-pointer-buttons-right">
		<a href="<?php echo esc_attr( $this->get_close_url() ); ?>" class="button-secondary pp-pointer-button-close"><?php esc_html_e( 'Close', 'pronamic_ideal' ); ?></a>
	</span>
</div>
