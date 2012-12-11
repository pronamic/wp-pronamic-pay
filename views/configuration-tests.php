<?php 

$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );

$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $id );

?>
<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2>
		<?php _e( 'iDEAL Tests', 'pronamic_ideal' ); ?>
	</h2>

	<?php if($configuration == null): ?>

		<p>
			<?php printf( __( 'We could not find any feed with the ID "%s".', 'pronamic_ideal' ), $id ); ?>
		</p>

	<?php else: ?>

		<div>
			<h3>
				<?php _e( 'Info', 'pronamic_ideal' ); ?>
			</h3>
	
			<table class="form-table">
				<tr>
					<th scope="row">
						<?php _e( 'ID', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $configuration->getId(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'Name', 'pronamic_ideal' ); ?>
					</th>
					<td>
						<?php echo $configuration->getName(); ?>
					</td>
				</tr>
			</table>
		</div>
	
		<?php 
		
		$variant = $configuration->getVariant();
		
		if ( !empty( $variant ) ) {
			switch ( $variant->getMethod() ) {
				case 'easy':
					include 'test-method-easy.php';
					break;
				case 'basic':
					include 'test-method-basic.php';
					break;
				case 'internetkassa':
					include 'test-method-internetkassa.php';
					break;
				case 'omnikassa':
					include 'test-method-omnikassa.php';
					break;
				case 'advanced':
					include 'test-method-advanced.php';
					break;
				case 'advanced_v3':
					include 'test-method-advanced-v3.php';
					break;
				case 'targetpay':
					include 'test-method-targetpay.php';
					break;
					
			}
		}
	
		?>
	
	<?php endif; ?>
</div>