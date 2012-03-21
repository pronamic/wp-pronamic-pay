<?php 

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

$deleted = null;
if($action == 'delete') {
	$ids = filter_input(INPUT_POST, 'feeds', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
	$numberToDelete = count($ids);

	$deleted = Pronamic_GravityForms_IDeal_FeedsRepository::deleteFeeds($ids);
}

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if($action == 'delete') {
	$ids = array(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING));
	$numberToDelete = count($ids);

	$deleted = Pronamic_GravityForms_IDeal_FeedsRepository::deleteFeeds($ids);	
}

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php 
		
		_e('iDEAL Feeds', 'pronamic_ideal'); 

		if(true): ?>

		<a class="button add-new-h2" href="<?php echo Pronamic_GravityForms_IDeal_AddOn::getEditFeedLink(); ?>">
			<?php _e('Add New', 'pronamic_ideal'); ?>
		</a>

		<?php endif; ?>
	</h2>

	<?php if($deleted): ?>
	
	<div class="updated inline below-h2">
		<p>
			<?php echo _n('Feed deleted.', 'Feeds deleted.', $numberToDelete, 'pronamic_ideal'); ?>
		</p>
	</div>

	<?php endif; ?>

	<form method="post" action="">
		<div class="tablenav top">
			<div class="alignleft actions">
				<select name="action">
					<option value="-1" selected="selected"><?php _e('Bulk Actions', 'pronamic_ideal'); ?></option>
					<option value="delete"><?php _e('Delete', 'pronamic_ideal'); ?></option>
				</select>

				<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'pronamic_ideal'); ?>"  />
			</div>
		</div>

		<table cellspacing="0" class="widefat fixed">

			<?php foreach(array('thead', 'tfoot') as $tag): ?>

			<<?php echo $tag; ?>>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
					<th scope="col" id="active" class="manage-column check-column"></th>
					<th scope="col" class="manage-column"><?php _e('Form', 'pronamic_ideal') ?></th>
					<th scope="col" class="manage-column"><?php _e('iDEAL Configuration', 'pronamic_ideal') ?></th>
					<th scope="col" class="manage-column"><?php _e('Transaction Description', 'pronamic_ideal') ?></th>
					<th scope="col" class="manage-column"><?php _e('Condition', 'pronamic_ideal') ?></th>
				</tr>
			</<?php echo $tag; ?>>

			<?php endforeach; ?>

			<tbody>

				<?php if(false): ?>

				<tr>
					<td colspan="3" style="padding:20px;">
						<?php 

						printf(
							__('To get started, please configure your %s.', 'pronamic_ideal') , 
							sprintf(
								'<a href="admin.php?page=gf_settings&addon=iDEAL">%s</a>', 
								__('iDEAL Settings', 'pronamic_ideal')
							)
						); 

						?>
					</td>
				</tr>

				<?php else: ?>

				<?php foreach(Pronamic_GravityForms_IDeal_FeedsRepository::getFeeds() as $feed): ?>

				<tr>
					<th scope="row" class="check-column">
						<input type="checkbox" name="feeds[]" value="<?php echo $feed->id; ?>"/>
					</th>
					<td>
						<?php $title = $feed->isActive ? __('Active', 'pronamic_ideal') : __('Inactive', 'pronamic_ideal'); ?>
						<img src="<?php echo plugins_url('images/active1.png', Pronamic_WordPress_IDeal_Plugin::$file); ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" />
					</td>
					<td>
						<?php 
						
						$editLink = Pronamic_GravityForms_IDeal_AddOn::getEditFeedLink($feed->id); 
						$deleteLink = Pronamic_GravityForms_IDeal_AddOn::getDeleteFeedLink($feed->id);

						?>

						<a href="<?php echo $editLink; ?>" title="<?php _e('Edit', 'pronamic_ideal'); ?>">
							<?php echo $feed->title; ?>
						</a>

						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo $editLink; ?>" title="<?php _e('Edit', 'pronamic_ideal'); ?>">
									<?php _e('Edit', 'pronamic_ideal'); ?>
								</a> |
							</span>
							<span class="trash">
								<a href="<?php echo $deleteLink; ?>" title="<?php _e('Delete', 'pronamic_ideal'); ?>">
									<?php _e('Delete', 'pronamic_ideal'); ?>
								</a>
							</span>
						</div>
					</td>
					<td>
						<?php if($configuration = $feed->getIDealConfiguration()): ?>
						<a href="<?php echo Pronamic_WordPress_IDeal_Admin::getConfigurationEditLink($configuration->getId()); ?>">
							<?php echo $configuration->getName(); ?>
						</a>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $feed->transactionDescription; ?>
					</td>
					<td>
						<?php echo $feed->conditionEnabled ? __('Enabled', 'pronamic_ideal') :  __('Disabled', 'pronamic_ideal'); ?>
					</td>
				</tr>

				<?php endforeach; ?>

				<?php endif; ?>
			</tbody>
		</table>
	</form>

	<?php 
	
	$results = null;
	if(!empty($_POST) && check_admin_referer('pronamic_ideal_search_lead', 'pronamic_ideal_nonce')) {
		global $wpdb;

		$id = filter_input(INPUT_POST, 's', FILTER_SANITIZE_STRING);

		$results = $wpdb->get_results($wpdb->prepare('SELECT * FROM wp_rg_lead WHERE id = %s', $id));
	}

	?>
	<form method="post" action="">
		<?php wp_nonce_field('pronamic_ideal_search_lead', 'pronamic_ideal_nonce'); ?>

		<h3><?php _e('Search Lead', 'pronamic_ideal'); ?></h3>

		<p>
			<label for="post-search-input" class="screen-reader-text"><?php _e('Search Lead', 'pronamic_ideal'); ?></label>

			<input type="text" name="s" id="post-search-input" />

			<input type="submit" value="<?php _e('Search Lead', 'pronamic_ideal'); ?>" class="button" id="search-submit" />
		</p>

		<?php if($results): ?>
		
		<ul>
			<?php foreach($results as $result): ?>

			<?php 
			
			$url = add_query_arg(array(
				'page' => 'gf_entries' , 
				'view' => 'entry' , 
				'id' => $result->form_id , 
				'lid' => $result->id
			), 'admin.php');

			?>
			
			<li>
				<?php echo $result->id; ?>. 
				<a href="<?php echo $url; ?>"><?php echo $result->date_created; ?></a>
			</li>
			
			<?php endforeach; ?>
		</ul>
		
		<?php endif; ?>
	</form>
</div>