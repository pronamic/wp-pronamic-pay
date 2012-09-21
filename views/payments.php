<?php 

global $wp_list_table;

$wp_list_table->prepare_items();

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Payments', 'pronamic_ideal'); ?>
	</h2>

	<?php $wp_list_table->views(); ?>

	<form method="post" action="">
		<?php $wp_list_table->search_box(__('Search Payments', 'pronamic_ideal'), 'payment'); ?>

		<?php $wp_list_table->display(); ?>
	</form>

	<?php include 'pronamic.php'; ?>
</div>