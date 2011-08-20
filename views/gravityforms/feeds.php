<?php 

namespace Pronamic\GravityForms\IDeal;

use Pronamic\WordPress\IDeal\Plugin;
use Pronamic\WordPress\IDeal\Admin;

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

$deleted = null;
if($action == 'delete') {
	$ids = filter_input(INPUT_POST, 'feeds', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
	$numberToDelete = count($ids);

	$deleted = FeedsRepository::deleteFeeds($ids);
}

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if($action == 'delete') {
	$ids = array(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING));
	$numberToDelete = count($ids);

	$deleted = FeedsRepository::deleteFeeds($ids);	
}

?>
<div class="wrap">
	<?php screen_icon(Plugin::SLUG); ?>

	<h2>
		<?php 
		
		_e('iDEAL Feeds', Plugin::TEXT_DOMAIN); 

		if(true): ?>

		<a class="button add-new-h2" href="<?php echo AddOn::getEditFeedLink(); ?>">
			<?php _e('Add New', Plugin::TEXT_DOMAIN); ?>
		</a>

		<?php endif; ?>
	</h2>

	<?php if($deleted): ?>
	
	<div class="updated inline below-h2">
		<p>
			<?php echo _n('Feed deleted.', 'Feeds deleted.', $numberToDelete, Plugin::TEXT_DOMAIN); ?>
		</p>
	</div>

	<?php endif; ?>

	<form method="post" action="">
		<div class="tablenav top">
			<div class="alignleft actions">
				<select name="action">
					<option value="-1" selected="selected"><?php _e('Bulk Actions', Plugin::TEXT_DOMAIN); ?></option>
					<option value="delete"><?php _e('Delete', Plugin::TEXT_DOMAIN); ?></option>
				</select>

				<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', Plugin::TEXT_DOMAIN); ?>"  />
			</div>
		</div>

		<table cellspacing="0" class="widefat fixed">

			<?php foreach(array('thead', 'tfoot') as $tag): ?>

			<<?php echo $tag; ?>>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
					<th scope="col" id="active" class="manage-column check-column"></th>
					<th scope="col" class="manage-column"><?php _e('Form', Plugin::TEXT_DOMAIN) ?></th>
					<th scope="col" class="manage-column"><?php _e('iDEAL Configuration', Plugin::TEXT_DOMAIN) ?></th>
					<th scope="col" class="manage-column"><?php _e('Transaction Description', Plugin::TEXT_DOMAIN) ?></th>
					<th scope="col" class="manage-column"><?php _e('Condition', Plugin::TEXT_DOMAIN) ?></th>
				</tr>
			</<?php echo $tag; ?>>

			<?php endforeach; ?>

			<tbody>

				<?php if(false): ?>

				<tr>
					<td colspan="3" style="padding:20px;">
						<?php 

						printf(
							__('To get started, please configure your %s.', Plugin::TEXT_DOMAIN) , 
							sprintf(
								'<a href="admin.php?page=gf_settings&addon=iDEAL">%s</a>', 
								__('iDEAL Settings', Plugin::TEXT_DOMAIN)
							)
						); 

						?>
					</td>
				</tr>

				<?php else: ?>

				<?php foreach(FeedsRepository::getFeeds() as $feed): ?>

				<tr>
					<th scope="row" class="check-column">
						<input type="checkbox" name="feeds[]" value="<?php echo $feed->id; ?>"/>
					</th>
					<td>
						<?php $title = $feed->isActive ? __('Active', Plugin::TEXT_DOMAIN) : __('Inactive', Plugin::TEXT_DOMAIN); ?>
						<img src="<?php echo plugins_url('images/active1.png', Plugin::$file); ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" />
					</td>
					<td>
						<?php 
						
						$editLink = AddOn::getEditFeedLink($feed->id); 
						$deleteLink = AddOn::getDeleteFeedLink($feed->id);

						?>

						<a href="<?php echo $editLink; ?>" title="<?php _e('Edit', Plugin::TEXT_DOMAIN); ?>">
							<?php echo $feed->title; ?>
						</a>

						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo $editLink; ?>" title="<?php _e('Edit', Plugin::TEXT_DOMAIN); ?>">
									<?php _e('Edit', Plugin::TEXT_DOMAIN); ?>
								</a> |
							</span>
							<span class="trash">
								<a href="<?php echo $deleteLink; ?>" title="<?php _e('Delete', Plugin::TEXT_DOMAIN); ?>">
									<?php _e('Delete', Plugin::TEXT_DOMAIN); ?>
								</a>
							</span>
						</div>
					</td>
					<td>
						<?php if($configuration = $feed->getIDealConfiguration()): ?>
						<a href="<?php echo Admin::getConfigurationEditLink($configuration->getId()); ?>">
							<?php echo $configuration->getName(); ?>
						</a>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $feed->transactionDescription; ?>
					</td>
					<td>
						<?php echo $feed->conditionEnabled ? __('Enabled', Plugin::TEXT_DOMAIN) :  __('Disabled', Plugin::TEXT_DOMAIN); ?>
					</td>
				</tr>

				<?php endforeach; ?>

				<?php endif; ?>
			</tbody>
		</table>
	</form>

	<?php 
	
	if(!empty($_POST) && check_admin_referer('pronamic_ideal_search_lead', 'pronamic_ideal_nonce')) {
		global $wpdb;

		$id = filter_input(INPUT_POST, 's', FILTER_SANITIZE_STRING);

		$results = $wpdb->get_results($wpdb->prepare('SELECT * FROM wp_rg_lead WHERE id = %s', $id));
	}

	?>
	<form method="post" action="">
		<?php wp_nonce_field('pronamic_ideal_search_lead', 'pronamic_ideal_nonce'); ?>

		<h3><?php _e('Search Lead', Plugin::TEXT_DOMAIN); ?></h3>

		<p>
			<label for="post-search-input" class="screen-reader-text"><?php _e('Search Lead', Plugin::TEXT_DOMAIN); ?></label>

			<input type="text" name="s" id="post-search-input" />

			<input type="submit" value="<?php _e('Search Lead', Plugin::TEXT_DOMAIN); ?>" class="button" id="search-submit" />
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