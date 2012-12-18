<?php 

$deleted = null;

$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
if ( $action == 'delete' ) {
	$ids = filter_input( INPUT_POST, 'configurations', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
	$number_to_delete = count( $ids );

	$deleted = Pronamic_WordPress_IDeal_ConfigurationsRepository::deleteConfigurations( $ids );
}

$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
if ( $action == 'delete' ) {
	$ids = array( filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING ) );
	$number_to_delete = count( $ids );

	$deleted = Pronamic_WordPress_IDeal_ConfigurationsRepository::deleteConfigurations( $ids );	
}

?>

<?php if ( $deleted ) : ?>

	<div class="updated inline below-h2">
		<p>
			<?php echo _n( 'Configuration deleted.', 'Configurations deleted.', $number_to_delete, 'pronamic_ideal' ); ?>
		</p>
	</div>

<?php endif; ?>

<form method="post" action="">
	<div class="tablenav top">
		<div class="alignleft actions">
			<select name="action">
				<option value="-1" selected="selected"><?php _e( 'Bulk Actions', 'pronamic_ideal' ); ?></option>
				<option value="delete"><?php _e( 'Delete', 'pronamic_ideal' ); ?></option>
			</select>

			<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e( 'Apply', 'pronamic_ideal' ); ?>"  />
		</div>
	</div>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach ( array( 'thead', 'tfoot' ) as $tag ) : ?>

			<<?php echo $tag; ?>>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
					<th scope="col" class="manage-column" style="width: 2em;"><?php _e('ID', 'pronamic_ideal' ); ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Name', 'pronamic_ideal' ); ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Merchant ID', 'pronamic_ideal' ); ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Sub ID', 'pronamic_ideal' ); ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Mode', 'pronamic_ideal' ); ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Dashboard', 'pronamic_ideal' ); ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Number Payments', 'pronamic_ideal' ); ?></th>
				</tr>
			</<?php echo $tag; ?>>

		<?php endforeach; ?>

		<tbody>

			<?php foreach ( Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations() as $configuration ) : ?>
	
				<tr>
					<th scope="row" class="check-column">
						<input type="checkbox" name="configurations[]" value="<?php echo $configuration->getId(); ?>"/>
					</th>
					<td style="width: 2em;">
						<?php echo $configuration->getId(); ?>
					</td>
					<td>
						<?php 
						
						$edit_link   = Pronamic_WordPress_IDeal_Admin::getConfigurationEditLink( $configuration->getId() );
						$tests_link  = Pronamic_WordPress_IDeal_Admin::getConfigurationTestsLink( $configuration->getId() );
						$delete_link = Pronamic_WordPress_IDeal_Admin::getConfigurationDeleteLink( $configuration->getId() );
	
						?>
	
						<a href="<?php echo $edit_link; ?>" title="<?php _e( 'Edit', 'pronamic_ideal' ); ?>">
							<?php echo $configuration->getName(); ?>
						</a>
	
						<div class="row-actions">
							<span class="edit">
								<a href="<?php echo $edit_link; ?>" title="<?php _e( 'Edit', 'pronamic_ideal' ); ?>">
									<?php _e( 'Edit', 'pronamic_ideal'); ?>
								</a> |
							</span>
							<span class="tests">
								<a href="<?php echo $tests_link; ?>" title="<?php _e( 'Tests', 'pronamic_ideal' ); ?>">
									<?php _e( 'Tests', 'pronamic_ideal'); ?>
								</a> |
							</span>
							<span class="trash">
								<a href="<?php echo $delete_link; ?>" title="<?php _e( 'Delete', 'pronamic_ideal' ); ?>">
									<?php _e( 'Delete', 'pronamic_ideal' ); ?>
								</a>
							</span>
						</div>
					</td>
					<td>
						<?php echo $configuration->getMerchantId(); ?>
						<?php echo $configuration->pspId; ?>
					</td>
					<td>
						<?php echo $configuration->getSubId(); ?>
					</td>
					<td>
						<?php echo $configuration->mode; ?>
					</td>
					<td>
						<?php if ( $url = $configuration->getDashboardUrl() ) : ?>
							<a href="<?php echo $url; ?>" title="<?php _e( 'Dashboard', 'pronamic_ideal' ); ?>">
								<?php _e( 'Dashboard', 'pronamic_ideal' ); ?>
							</a>
						<?php else: ?>
							<?php _e( 'Not available', 'pronamic_ideal' ); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ( isset( $configuration->numberPayments ) ) : ?>
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