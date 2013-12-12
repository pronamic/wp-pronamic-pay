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
		<?php _e( 'Supported Extensions', 'pronamic_ideal' ); ?>
	</h3>

	<?php 
	
	$extensions = array(
		// Plugins
		'event-espresso'        => array(
			'name'              => __( 'Event Espresso', 'pronamic_ideal' ),
			'url'               => 'http://eventespresso.com/',
			'github_url'        => 'https://github.com/eventespresso/event-espresso-core',
			'active'            => Pronamic_EventEspresso_EventEspresso::is_active(),
			'requires_at_least' => '3.1.24',
			'tested_up_to'      => '3.1.35.P',
		),
		'event-espresso-free'   => array(
			'name'              => __( 'Event Espresso Lite', 'pronamic_ideal' ),
			'url'               => 'http://eventespresso.com/',
			'github_url'        => 'https://github.com/eventespresso/event-espresso-core',
			'wp_org_url'        => 'http://wordpress.org/extend/plugins/event-espresso-free/',
			'active'            => Pronamic_EventEspresso_EventEspresso::is_active(),
			'requires_at_least' => '3.1.29.1.L',
			'tested_up_to'      => '3.1.35.L',
		),			
		'gravityforms'          => array(
			'name'              => __( 'Gravity Forms', 'pronamic_ideal' ),
			'url'               => 'http://www.gravityforms.com/',
			'active'            => Pronamic_GravityForms_GravityForms::is_active(),
			'requires_at_least' => '1.6',
			'tested_up_to'      => '1.7.11',
			'author'            => __( 'Rocketgenius', 'pronamic_ideal' ),
			'author_url'        => 'http://www.rocketgenius.com/',
		),
		'jigoshop'              => array(
			'name'              => __( 'Jigoshop', 'pronamic_ideal' ),
			'url'               => 'http://jigoshop.com/',
			'wp_org_url'        => 'http://wordpress.org/extend/plugins/jigoshop/',
			'github_url'        => 'https://github.com/jigoshop/jigoshop',
			'active'            => Pronamic_Jigoshop_Jigoshop::is_active(),
			'requires_at_least' => '1.1',
			'tested_up_to'      => '1.8',
			'author'            => __( 'Jigowatt', 'pronamic_ideal' ),
			'author_url'        => 'http://jigowatt.co.uk/',
		),
		'membership'            => array(
			'name'              => __( 'Membership', 'pronamic_ideal' ),
			'url'               => 'http://wordpress.org/plugins/membership/',
			'wp_org_url'        => 'http://wordpress.org/plugins/membership/',
			'active'            => Pronamic_Membership_Membership::is_active(),
			'requires_at_least' => '3.4.4.1',
			'tested_up_to'      => '3.4.4.1',
			'author'            => __( 'WPMUDEV.org', 'pronamic_ideal' ),
			'author_url'        => 'http://www.wpmudev.org/',
		),
		'membership-premium'    => array(
			'name'              => __( 'Membership Premium', 'pronamic_ideal' ),
			'url'               => 'http://premium.wpmudev.org/project/membership/',
			'active'            => Pronamic_Membership_Membership::is_active(),
			'requires_at_least' => '3.4.6',
			'tested_up_to'      => '3.4.6',
			'author'            => __( 'WPMUDEV.org', 'pronamic_ideal' ),
			'author_url'        => 'http://www.wpmudev.org/',
		),
		's2member'              => array(
			'name'              => __( 's2Member®', 'pronamic_ideal' ),
			'url'               => 'http://www.s2member.com/',
			'wp_org_url'        => 'http://wordpress.org/plugins/membership/',
			'github_url'        => 'https://github.com/WebSharks/s2Member',
			'active'            => Pronamic_S2Member_S2Member::is_active(),
			'requires_at_least' => '130816',
			'tested_up_to'      => '131026',
			'author'            => __( 'WebSharks, Inc.', 'pronamic_ideal' ),
			'author_url'        => 'http://www.websharks-inc.com/',
		),
		'shopp'                 => array(
			'name'              => __( 'Shopp', 'pronamic_ideal' ),
			'url'               => 'https://shopplugin.net/',
			'github_url'        => 'https://github.com/ingenesis/shopp',
			'active'            => Pronamic_Shopp_Shopp::is_active(),
			'requires_at_least' => '1.1',
			'tested_up_to'      => '1.2.9',
			'author'            => __( 'Ingenesis Limited', 'pronamic_ideal' ),
			'author_url'        => 'http://ingenesis.net/',
		),
		'woocommerce'           => array(
			'name'              => __( 'WooCommerce', 'pronamic_ideal' ),
			'url'               => 'http://www.woothemes.com/woocommerce/',
			'github_url'        => 'https://github.com/woothemes/woocommerce',
			'wp_org_url'        => 'http://wordpress.org/extend/plugins/woocommerce/',
			'active'            => Pronamic_WooCommerce_WooCommerce::is_active(),
			'requires_at_least' => '2.0.0',
			'tested_up_to'      => '2.0.18',
			'author'            => __( 'WooThemes', 'pronamic_ideal' ),
			'author_url'        => 'http://www.woothemes.com/',
		),
		'wp-e-commerce'         => array(
			'name'              => __( 'WP e-Commerce', 'pronamic_ideal' ),
			'url'               => 'http://getshopped.org/',
			'wp_org_url'        => 'http://wordpress.org/extend/plugins/wp-e-commerce/',
			'github_url'        => 'https://github.com/wp-e-commerce/WP-e-Commerce',
			'active'            => Pronamic_WPeCommerce_WPeCommerce::is_active(),
			'requires_at_least' => '3.8.12.1',
			'tested_up_to'      => '3.8.12.1',
			'author'            => __( 'Instinct Entertainment', 'pronamic_ideal' ),
			'author_url'        => 'http://instinct.co.nz/',
		),
		// Themes
		'classipress'           => array(
			'name'              => __( 'ClassiPress', 'pronamic_ideal' ),
			'url'               => 'http://www.appthemes.com/themes/classipress/',
			'active'            => Pronamic_ClassiPress_ClassiPress::is_active(),
			'requires_at_least' => '3.3.1',
			'tested_up_to'      => '3.3.1',
			'author'            => __( 'AppThemes', 'pronamic_ideal' ),
			'author_url'        => 'http://www.appthemes.com/',
		),
		'jobroller'             => array(
			'name'              => __( 'JobRoller', 'pronamic_ideal' ),
			'url'               => 'http://www.appthemes.com/themes/jobroller/',
			'active'            => Pronamic_JobRoller_JobRoller::is_active(),
			'tested_up_to'      => '1.7.1',
			'author'            => __( 'AppThemes', 'pronamic_ideal' ),
			'author_url'        => 'http://www.appthemes.com/',
		),
	);
	
	?>

	<table class="wp-list-table widefat">
		<thead>
			<tr>
				<th scope="col">
					<?php _e( 'Name', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php _e( 'Author', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php _e( 'WordPress.org', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php _e( 'GitHub', 'pronamic_ideal' ); ?>
				</th>
				<th scope="col">
					<?php _e( 'Requires at least', 'pronamic_ideal' ); ?>
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

			<?php foreach ( $extensions as $extension ) : ?>
	
				<tr>
					<td>
						<a href="<?php echo esc_attr( $extension['url'] ); ?>" target="_blank">
							<?php echo esc_html( $extension['name'] ); ?>
						</a>
					</td>
					<td>
						<?php
						
						if ( isset( $extension['author'], $extension['author_url'] ) ) {
							printf(
								'<a href="%s" target="_blank">%s</a>',
								esc_attr( $extension['author_url'] ),
								esc_html( $extension['author'] )
							);
						}
						
						?>
					</td>
					<td>
						<?php
						
						if ( isset( $extension['wp_org_url'] ) ) {
							printf(
								'<a href="%s" target="_blank">%s</a>',
								esc_attr( $extension['wp_org_url'] ),
								esc_html( $extension['wp_org_url'] )
							);
						}
						
						?>
					</td>
					<td>
						<?php
						
						if ( isset( $extension['github_url'] ) ) {
							printf(
								'<a href="%s" target="_blank">%s</a>',
								esc_attr( $extension['github_url'] ),
								esc_html( $extension['github_url'] )
							);
						}
						
						?>
					</td>
					<td>
		                <?php
						
						if ( isset( $extension['requires_at_least'] ) ) {
							echo $extension['requires_at_least'];
						}
		                
		                ?>
					</td>
					<td>
		                <?php echo esc_html( $extension['tested_up_to'] ); ?>
					</td>
					<td>
						<?php if ( $extension['active'] ) : ?>
							&#10003;
						<?php endif; ?>
					</td>
				</tr>
			
			<?php endforeach; ?>

	</table>

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