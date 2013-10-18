<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<form action="options.php" method="post">
		<?php settings_fields( 'pronamic_pay' ); ?>

		<?php do_settings_sections( 'pronamic_pay' ); ?>

		<?php submit_button(); ?>
	</form>

	<?php include 'pronamic.php'; ?>
</div>