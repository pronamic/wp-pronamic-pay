<?php 

global $wp_list_table;

$wp_list_table->prepare_items();

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Payments', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<?php $wp_list_table->views(); ?>

	<form method="post" action="">
		<?php $wp_list_table->display(); ?>
	</form>

	<br class="clear" />
</div>