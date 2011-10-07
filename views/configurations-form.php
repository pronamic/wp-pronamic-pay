<?php 

$deleted = null;

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if($action == 'delete') {
	$ids = filter_input(INPUT_POST, 'configurations', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
	$numberToDelete = count($ids);

	$deleted = Pronamic_WordPress_IDeal_ConfigurationsRepository::deleteConfigurations($ids);
}

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if($action == 'delete') {
	$ids = array(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING));
	$numberToDelete = count($ids);

	$deleted = Pronamic_WordPress_IDeal_ConfigurationsRepository::deleteConfigurations($ids);	
}

?>

<?php if($deleted): ?>

<div class="updated inline below-h2">
	<p>
		<?php echo _n('Configuration deleted.', 'Configurations deleted.', $numberToDelete, Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</p>
</div>

<?php endif; ?>

<form method="post" action="">
	<div class="tablenav top">
		<div class="alignleft actions">
			<select name="action">
				<option value="-1" selected="selected"><?php _e('Bulk Actions', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></option>
				<option value="delete"><?php _e('Delete', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?></option>
			</select>

			<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>"  />
		</div>
	</div>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach(array('thead', 'tfoot') as $tag): ?>

		<<?php echo $tag; ?>>
			<tr>
				<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
				<th scope="col" class="manage-column"><?php _e('Name', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Merchant ID', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Sub ID', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Mode', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Dashboard', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
				<th scope="col" class="manage-column"><?php _e('Number Payments', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?></th>
			</tr>
		</<?php echo $tag; ?>>

		<?php endforeach; ?>

		<tbody>

			<?php foreach(Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations() as $configuration): ?>

			<tr>
				<th scope="row" class="check-column">
					<input type="checkbox" name="configurations[]" value="<?php echo $configuration->getId(); ?>"/>
				</th>
				<td>
					<?php 
					
					$editLink = Pronamic_WordPress_IDeal_Admin::getConfigurationEditLink($configuration->getId()); 
					$deleteLink = Pronamic_WordPress_IDeal_Admin::getConfigurationDeleteLink($configuration->getId());
					$testsLink = Pronamic_WordPress_IDeal_Admin::getConfigurationTestsLink($configuration->getId());

					?>

					<a href="<?php echo $editLink; ?>" title="<?php _e('Edit', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>">
						<?php echo $configuration->getName(); ?>
					</a>

					<div class="row-actions">
						<span class="edit">
							<a href="<?php echo $editLink; ?>" title="<?php _e('Edit', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>">
								<?php _e('Edit', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
							</a> |
						</span>
						<span class="tests">
							<a href="<?php echo $testsLink; ?>" title="<?php _e('Tests', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>">
								<?php _e('Tests', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
							</a> |
						</span>
						<span class="trash">
							<a href="<?php echo $deleteLink; ?>" title="<?php _e('Delete', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>">
								<?php _e('Delete', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
							</a>
						</span>
					</div>
				</td>
				<td>
					<?php echo $configuration->getMerchantId(); ?>
				</td>
				<td>
					<?php echo $configuration->getSubId(); ?>
				</td>
				<td>
					<?php echo $configuration->mode; ?>
				</td>
				<td>
					<?php if($url = $configuration->getDashboardUrl()): ?>
					<a href="<?php echo $url; ?>" title="<?php _e('Dashboard', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>">
						<?php _e('Dashboard', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					</a>
					<?php else: ?>
					<?php _e('Not available', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if(isset($configuration->numberPayments)): ?>
					<a href="<?php echo Pronamic_WordPress_IDeal_Admin::getPaymentsLink(); ?>">
						<?php echo $configuration->numberPayments; ?>
					</a>
					<?php endif; ?>
				</td>
			</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
</form>