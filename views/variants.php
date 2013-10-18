<?php 

global $pronamic_pay_providers;
global $pronamic_pay_gateways;

?>
<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2>
		<?php _e( 'iDEAL Variants', 'pronamic_ideal' ); ?>
	</h2>

	<table class="wp-list-table widefat" cellspacing="0">

		<?php foreach ( array( 'thead', 'tfoot' ) as $tag ): ?>

			<<?php echo $tag; ?>>
				<tr>
					<th scope="col" class="manage-column"><?php _e( 'Provider', 'pronamic_ideal' ); ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Name', 'pronamic_ideal' ); ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Gateway', 'pronamic_ideal' );  ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Dashboard', 'pronamic_ideal' ); ?></th>
				</tr>
			</<?php echo $tag; ?>>

		<?php endforeach; ?>

		<tbody>

			<?php foreach ( $pronamic_pay_gateways as $gateway ): ?>

				<tr>
					<td>
						<?php if ( isset( $gateway['provider'], $pronamic_pay_providers[$gateway['provider']] ) ): ?>
							<?php $provider = $pronamic_pay_providers[$gateway['provider']]; ?>
							<a href="<?php echo $provider['url']; ?>">
								<?php echo $provider['name']; ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $gateway['name']; ?>
					</td>
					<td>
						<?php 
						
						switch ( $gateway['gateway'] ) {
							case Pronamic_IDeal_IDeal::METHOD_EASY:
								_e( 'Easy', 'pronamic_ideal' );
								break;
							case Pronamic_IDeal_IDeal::METHOD_BASIC:
								_e( 'Basic', 'pronamic_ideal' );
								break;
							case Pronamic_IDeal_IDeal::METHOD_INTERNETKASSA:
								_e( 'Internetkassa', 'pronamic_ideal' );
								break;
							case Pronamic_IDeal_IDeal::METHOD_OMNIKASSA:
								_e( 'OmniKassa', 'pronamic_ideal' );
								break;
							case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
								_e( 'Advanced', 'pronamic_ideal' );
								break;
							case 'advanced_v3':
								_e( 'Advanced v3', 'pronamic_ideal' );
								break;
							case 'targetpay':
								echo 'TargetPay';
								break;
							default:
								_e( 'Unknown', 'pronamic_ideal' );
								break;
						}
						
						?>
					</td>
					<td>
						
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

	<?php include 'pronamic.php'; ?>
</div>