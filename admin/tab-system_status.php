<h3><?php esc_html_e( 'Installation Status', 'pronamic_ideal' ); ?></h3>

<table class="pronamic-pay-status-table widefat">
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Site URL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( site_url() ); ?>
		</td>
		<td>
			&#10003;
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Home URL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( home_url() ); ?>
		</td>
		<td>
			&#10003;
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'PHP Version', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( phpversion() ); ?>
		</td>
		<td>
			<?php

			if ( version_compare( phpversion(), '5.2', '>' ) ) {
				echo '&#10003;';
			} else {
				esc_html_e( 'Pronamic iDEAL requires PHP 5.2 or above.', 'pronamic_ideal' );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'MySQL Version', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			global $wpdb;

			echo esc_html( $wpdb->db_version() );

			?>
		</td>
		<td>
			<?php

			if ( version_compare( $wpdb->db_version(), '5', '>' ) ) {
				echo '&#10003;';
			} else {
				esc_html_e( 'Pronamic iDEAL requires MySQL 5 or above.', 'pronamic_ideal' );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'WordPress Version', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( get_bloginfo( 'version' ) ); ?>
		</td>
		<td>
			<?php

			if ( version_compare( get_bloginfo( 'version' ), '3.2', '>' ) ) {
				echo '&#10003;';
			} else {
				esc_html_e( 'Pronamic iDEAL requires WordPress 3.2 or above.', 'pronamic_ideal' );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'WP Memory Limit', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$memory = pronamic_pay_let_to_num( WP_MEMORY_LIMIT );

			echo esc_html( size_format( $memory ) );

			?>
		</td>
		<td>
			<?php

			if ( $memory > 67108864 ) { // 64 MB
				echo '&#10003;';
			} else {
				echo wp_kses(
					sprintf(
						__( 'We recommend setting memory to at least 64MB. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'pronamic_ideal' ),
						esc_attr( 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' )
					),
					array(
						'a' => array(
							'href'   => true,
							'target' => true,
						),
					)
				);
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Character Set', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php bloginfo( 'charset' ); ?>
		</td>
		<td>
			<?php

			// @see http://codex.wordpress.org/Function_Reference/bloginfo#Show_Character_Set
			if ( 0 === strcasecmp( get_bloginfo( 'charset' ), 'UTF-8' ) ) {
				echo '&#10003;';
			} else {
				esc_html_e( 'Pronamic iDEAL advices to set the character encoding to UTF-8.', 'pronamic_ideal' );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Time', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( date( __( 'Y/m/d g:i:s A', 'pronamic_ideal' ) ) ); ?><br />
			<?php echo esc_html( date( Pronamic_IDeal_IDeal::DATE_FORMAT ) ); ?>
		</td>
		<td>
			&#10003;
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'cURL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			if ( function_exists( 'curl_version' ) ) {
				// @codingStandardsIgnoreStart
				// Using cURL functions is highly discouraged within VIP context
				// We only use this cURL function for on the system status page
				$version = curl_version();
				// @codingStandardsIgnoreEnd

				if ( isset( $version['version'] ) ) {
					echo esc_html( $version['version'] );
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
			<?php esc_html_e( 'OpenSSL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			if ( defined( 'OPENSSL_VERSION_TEXT' ) ) {
				echo esc_html( OPENSSL_VERSION_TEXT );
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
				esc_html_e( 'Pronamic iDEAL requires OpenSSL 0.9.8 or above.', 'pronamic_ideal' );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Registered Hashing Algorithms', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$algorithms = hash_algos();

			echo esc_html( implode( ', ', $algorithms ) );

			?>
		</td>
		<td>
			<?php

			if ( in_array( 'sha1', $algorithms ) ) {
				echo '&#10003;';
			} else {
				esc_html_e( 'Pronamic iDEAL requires the "sha1" hashing algorithm.', 'pronamic_ideal' );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Travis CI build status', 'pronamic_ideal' ); ?>
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
