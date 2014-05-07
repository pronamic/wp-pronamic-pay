<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<form action="options.php" method="post">
		<?php settings_fields( 'pronamic_pay_s2member' ); ?>

		<?php do_settings_sections( 'pronamic_pay_s2member' ); ?>

		<?php submit_button(); ?>
	</form>
</div>
