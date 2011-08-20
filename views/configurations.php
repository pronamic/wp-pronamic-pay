<?php 

namespace Pronamic\WordPress\IDeal;

?>
<div class="wrap">
	<?php screen_icon(Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Configurations', Plugin::TEXT_DOMAIN);

		if(true): ?>

		<a class="button add-new-h2" href="<?php echo Admin::getConfigurationEditLink(); ?>">
			<?php _e('Add New', Plugin::TEXT_DOMAIN); ?>
		</a>

		<?php endif; ?>
	</h2>

	<?php include 'configurations-form.php'; ?>
	
	<?php include 'uninstall-form.php'; ?>
</div>