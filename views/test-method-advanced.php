<h3>
	<?php _e( 'Retrieve Issuers Lists', 'pronamic_ideal' ); ?>
</h3>

<?php 

$lists = Pronamic_WordPress_IDeal_IDeal::getIssuersLists( $configuration );

if ( $lists ): ?>
	
	<ul>
		<?php foreach ( $lists as $name => $list ): ?>
			<li>
				<strong><?php echo $name; ?></strong>

				<?php if ( $list ): ?>
					<ul>
						<?php foreach ( $list as $issuer ): ?>
							<li>
								<?php echo $issuer->getName(); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>

	</ul>
	
	<h3>
		<?php _e( 'Mandatory Tests', 'pronamic_ideal' ); ?>
	</h3>
	
	<form method="post" action="" target="_blank">
		<?php 
		
		wp_nonce_field( 'test', 'pronamic_ideal_nonce' );
	
		echo Pronamic_IDeal_HTML_Helper::issuersSelect( 'pronamic_ideal_issuer_id', $lists );
		
		foreach ( array( 1, 2, 3, 4, 5, 7 ) as $test_case ) {
			$name = sprintf( __( 'Test Case %s', 'pronamic_ideal' ), $test_case );
			
			submit_button( $name, 'secondary', 'test[' . $test_case . ']', false );
		}
		
		?>
	</form>

<?php elseif ( $error = Pronamic_WordPress_IDeal_IDeal::getError() ): ?>

	<div class="error inline below-h2">
		<dl>
			<dt><?php _e( 'Code', 'pronamic_ideal' ); ?></dt>
			<dd><?php echo $error->getCode(); ?></dd>
	
			<dt><?php _e( 'Message', 'pronamic_ideal' ); ?></dt>
			<dd><?php echo $error->getMessage(); ?></dd>
	
			<dt><?php _e( 'Detail', 'pronamic_ideal' ); ?></dt>
			<dd><?php echo $error->getDetail(); ?></dd>
	
			<dt><?php _e( 'Consumer Message', 'pronamic_ideal' ); ?></dt>
			<dd><?php echo $error->getConsumerMessage(); ?></dd>
		</dl>
	</div>

<?php endif; ?>