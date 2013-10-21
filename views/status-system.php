<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<h3>
		<?php _e( 'License Information', 'pronamic_ideal' ); ?>
	</h3>

	<?php $license_info = Pronamic_WordPress_IDeal_Plugin::get_license_info(); ?>

	<?php if ( empty( $license_info ) ) : ?>

		<p>
			<?php _e( 'No license information available.', 'pronamic_ideal' ); ?>
		</p>

	<?php else : ?>
	
		<table class="form-table">
	
			<?php if ( isset( $license_info->activationDate ) ) : ?>
	
				<tr>
					<th scope="row">
						<?php _e( 'Activation Date', 'pronamic_ideal' ); ?>
					</th>
					<td>
		                 <?php echo mysql2date( __( 'Y/m/d g:i:s A', 'pronamic_ideal' ), $license_info->activationDate ); ?>
					</td>
				</tr>
	
			<?php endif; ?>
	
			<?php if( isset( $license_info->expirationDate ) ): ?>
	
				<tr>
					<th scope="row">
						<?php _e( 'Expiration Date', 'pronamic_ideal' ); ?>
					</th>
					<td>
		                 <?php echo mysql2date( __( 'Y/m/d g:i:s A', 'pronamic_ideal' ), $license_info->expirationDate ); ?>
					</td>
				</tr>
	
			<?php endif; ?>
	
		</table>
	
	<?php endif; ?>

	<h3>
		<?php _e( 'Supported Plugins', 'pronamic_ideal' ); ?>
	</h3>

	<?php 
	
	$plugins = array(
		'event-espresso'      => array(
			'name'         => __( 'Event Espresso', 'pronamic_ideal' ),
			'url'          => 'http://eventespresso.com/',
			'active'       => Pronamic_EventEspresso_EventEspresso::is_active(),
			'tested_up_to' => '3.1.24'
		),
		'event-espresso-free' => array(
			'name'         => __( 'Event Espresso Lite', 'pronamic_ideal' ),
			'url'          => 'http://wordpress.org/extend/plugins/event-espresso-free/',
			'active'       => Pronamic_EventEspresso_EventEspresso::is_active(),
			'tested_up_to' => '3.1.29'
		),			
		'gravityforms'        => array(
			'name'         => __( 'Gravity Forms', 'pronamic_ideal' ),
			'url'          => 'http://www.gravityforms.com/',
			'active'       => Pronamic_GravityForms_GravityForms::is_active(),
			'tested_up_to' => '1.6'
		),
		'jigoshop'            => array(
			'name'         => __( 'Jigoshop', 'pronamic_ideal' ),
			'url'          => 'http://wordpress.org/extend/plugins/jigoshop/',
			'active'       => Pronamic_Jigoshop_Jigoshop::is_active(),
			'tested_up_to' => '1.1'
		),
		'shopp'               => array(
			'name'         => __( 'Shopp', 'pronamic_ideal' ),
			'url'          => 'https://shopplugin.net/',
			'active'       => Pronamic_Shopp_Shopp::is_active(),
			'tested_up_to' => '1.1'
		),
		'woocommerce'         => array(
			'name'         => __( 'WooCommerce', 'pronamic_ideal' ),
			'url'          => 'http://wordpress.org/extend/plugins/woocommerce/',
			'active'       => Pronamic_WooCommerce_WooCommerce::is_active(),
			'tested_up_to' => '1.4'
		),
		'wp-e-commerce'       => array(
			'name'         => __( 'WP e-Commerce', 'pronamic_ideal' ),
			'url'          => 'http://wordpress.org/extend/plugins/wp-e-commerce/',
			'active'       => Pronamic_WPeCommerce_WPeCommerce::is_active(),
			'tested_up_to' => '3.8'
		)
	);
	
	?>

	<table class="wp-list-table widefat" style="width: auto;">
		<thead>
			<tr>
				<th scope="col">
					<?php _e( 'Plugin', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php _e( 'Tested up to', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php _e( 'Active', 'pronamic_ideal' ); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>

			<?php foreach ( $plugins as $plugin ) : ?>
	
				<tr>
					<td>
						<a href="<?php esc_attr( $plugin['url'] ); ?>" target="_blank">
							<?php echo esc_html( $plugin['name'] ); ?>
						</a>
					</td>
					<td>
		                <?php echo esc_html( $plugin['tested_up_to'] ); ?>
					</td>
					<td>
						<?php if ( $plugin['active'] ) : ?>
							&#10003;
						<?php endif; ?>
					</td>
				</tr>
			
			<?php endforeach; ?>

	</table>

	<h3>
		<?php _e( 'Installation Status', 'pronamic_ideal' ); ?>
	</h3>

	<table class="form-table">
		<tr>
			<th scope="row">
				<?php _e( 'Site URL', 'pronamic_ideal' ); ?>
			</th>
			<td class="column-version">
                <?php echo site_url(); ?>
			</td>
			<td>
				&#10003;
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Home URL', 'pronamic_ideal' ); ?>
			</th>
			<td class="column-version">
                <?php echo home_url(); ?>
			</td>
			<td>
				&#10003;
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'PHP Version', 'pronamic_ideal' ); ?>
			</th>
			<td class="column-version">
                <?php echo phpversion(); ?>
			</td>
			<td>
				<?php 

				if ( version_compare( phpversion(), '5.2', '>' ) ) {
					echo '&#10003;';
				} else {
					_e( 'Pronamic iDEAL requires PHP 5.2 or above.', 'pronamic_ideal' );
				}

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'MySQL Version', 'pronamic_ideal' ); ?>
			</th>
			<td class="column-version">
                <?php 

                global $wpdb;

                echo $wpdb->db_version();

                ?>
			</td>
			<td>
				<?php 

				if ( version_compare( $wpdb->db_version(), '5', '>' ) ) {
					echo '&#10003;';
				} else {
					_e( 'Pronamic iDEAL requires MySQL 5 or above.', 'pronamic_ideal' );
				}

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'WordPress Version', 'pronamic_ideal' ); ?>
			</th>
			<td class="column-version">
                <?php echo get_bloginfo( 'version' ); ?>
			</td>
			<td>
				<?php 

				if ( version_compare( get_bloginfo( 'version' ), '3.2', '>' ) ) {
					echo '&#10003;';
				} else {
					_e( 'Pronamic iDEAL requires WordPress 3.2 or above.', 'pronamic_ideal' );
				}

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Time', 'pronamic_ideal' ); ?>
			</th>
			<td class="column-version">
                <?php echo date( __( 'Y/m/d g:i:s A', 'pronamic_ideal' ) ); ?><br />
                <?php echo date( Pronamic_IDeal_IDeal::DATE_FORMAT ); ?>
			</td>
			<td>
				&#10003;
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'OpenSSL', 'pronamic_ideal' ); ?>
			</th>
			<td class="column-version">
				<?php 

				if ( defined( 'OPENSSL_VERSION_TEXT' ) ) {
					echo OPENSSL_VERSION_TEXT;
				}
				
				// @see https://www.openssl.org/docs/crypto/OPENSSL_VERSION_NUMBER.html
				$version_required = 0x000908000;

				?>
			</td>
			<td>
				<?php 

				if ( version_compare( OPENSSL_VERSION_NUMBER, 0x000908000, '>' ) ) {
					echo '&#10003;';
				} else {
					_e( 'Pronamic iDEAL requires OpenSSL 0.9.8 or above.', 'pronamic_ideal' );
				}
				
				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Registered Hashing Algorithms', 'pronamic_ideal' ); ?>
			</th>
			<td class="column-version">
				<?php 
				
				$algorithms = hash_algos();
				
				echo implode( ', ', $algorithms );
				
				?>
			</td>
			<td>
				<?php 
				
				if ( in_array( 'sha1', $algorithms ) ) {
					echo '&#10003;';
				} else {
					_e( 'Pronamic iDEAL requires the "sha1" hashing algorithm.', 'pronamic_ideal' );
				}
				
				?>
			</td>
		</tr>
	</table>

	<?php include 'pronamic.php'; ?>
</div>