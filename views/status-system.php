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
		<?php _e( 'Installation Status', 'pronamic_ideal' ); ?>
	</h3>

	<table class="pronamic-pay-status-table widefat">
		<tr>
			<th scope="row">
				<?php _e( 'Site URL', 'pronamic_ideal' ); ?>
			</th>
			<td>
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
			<td>
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
			<td>
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
			<td>
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
			<td>
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
				<?php _e( 'WP Memory Limit', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php 
				
				$memory = pronamic_pay_let_to_num( WP_MEMORY_LIMIT );
				
				echo size_format( $memory );
				
				?>
			</td>
			<td>
				<?php 
				
				if ( $memory > 67108864 ) { // 64 MB
					echo '&#10003;';
				} else {
					printf(
						__( 'We recommend setting memory to at least 64MB. See: <a href="%s">Increasing memory allocated to PHP</a>', 'pronamic_ideal' ),
						'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP'
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Character Set', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php bloginfo( 'charset' ); ?>
			</td>
			<td>
				<?php 

				// @see http://codex.wordpress.org/Function_Reference/bloginfo#Show_Character_Set
				if ( strcasecmp( get_bloginfo( 'charset' ), 'UTF-8' ) == 0 ) {
					echo '&#10003;';
				} else {
					_e( 'Pronamic iDEAL advices to set the character encoding to UTF-8.', 'pronamic_ideal' );
				}

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Time', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo date( __( 'Y/m/d g:i:s A', 'pronamic_ideal' ) ); ?><br />
				<?php echo date( Pronamic_IDeal_IDeal::DATE_FORMAT ); ?>
			</td>
			<td>
				&#10003;
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'cURL', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				if ( function_exists( 'curl_version' ) ) {						
					$version = curl_version(); 

					if ( isset( $version['version'] ) ) {
						echo $version['version'];
					}
				}

				?>
			</td>
			<td>
				&#10003;
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'OpenSSL', 'pronamic_ideal' ); ?>
			</th>
			<td>
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
			<td>
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
		<tr>
			<th scope="row">
				<?php _e( 'Travis CI build status', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php 
				
				global $pronamic_pay_version;
				
				$url = add_query_arg( 'branch', $pronamic_pay_version, 'https://travis-ci.org/pronamic/wp-pronamic-ideal.png' );
				
				?>
				<a href="https://travis-ci.org/pronamic/wp-pronamic-ideal">
					<img src="<?php echo esc_attr( $url ); ?>" alt="" />
				</a>
			</td>
			<td>
				
			</td>
		</tr>
	</table>

	<?php include 'pronamic.php'; ?>
</div>