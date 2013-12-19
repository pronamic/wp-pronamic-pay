<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<h3><?php _e( 'Icons', 'pronamic_ideal' ); ?></h3>

	<?php 
	
	$icons = array(
		// iDEAL
		array(
			'title'  => __( 'iDEAL', 'pronamic_ideal' ),
			'width'  => 16,
			'height' => 16,
			'url'    => plugins_url( 'images/ideal/icon-16x16.png', Pronamic_WordPress_IDeal_Plugin::$file ),
		),
		array(
			'title'  => __( 'iDEAL', 'pronamic_ideal' ),
			'width'  => 24,
			'height' => 24,
			'url'    => plugins_url( 'images/ideal/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file ),
		),
		array(
			'title'  => __( 'iDEAL', 'pronamic_ideal' ),
			'width'  => 32,
			'height' => 32,
			'url'    => plugins_url( 'images/ideal/icon-32x32.png', Pronamic_WordPress_IDeal_Plugin::$file ),
		),
		// OmniKassa
		array(
			'title'  => __( 'OmniKassa', 'pronamic_ideal' ),
			'width'  => 16,
			'height' => 16,
			'url'    => plugins_url( 'images/omnikassa/icon-16x16.png', Pronamic_WordPress_IDeal_Plugin::$file ),
		),
		array(
			'title'  => __( 'OmniKassa', 'pronamic_ideal' ),
			'width'  => 24,
			'height' => 24,
			'url'    => plugins_url( 'images/omnikassa/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file ),
		),
		array(
			'title'  => __( 'OmniKassa', 'pronamic_ideal' ),
			'width'  => 32,
			'height' => 32,
			'url'    => plugins_url( 'images/omnikassa/icon-32x32.png', Pronamic_WordPress_IDeal_Plugin::$file ),
		),
		// Mister Cash
		array(
			'title'  => __( 'Mister Cash', 'pronamic_ideal' ),
			'width'  => 16,
			'height' => 16,
			'url'    => plugins_url( 'images/mistercash/icon-16x16.png', Pronamic_WordPress_IDeal_Plugin::$file ),
		),
		array(
			'title'  => __( 'Mister Cash', 'pronamic_ideal' ),
			'width'  => 24,
			'height' => 24,
			'url'    => plugins_url( 'images/mistercash/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file ),
		),
		array(
			'title'  => __( 'Mister Cash', 'pronamic_ideal' ),
			'width'  => 32,
			'height' => 32,
			'url'    => plugins_url( 'images/mistercash/icon-32x32.png', Pronamic_WordPress_IDeal_Plugin::$file ),
		),
	);
	
	?>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach ( array( 'thead', 'tfoot' ) as $tag ): ?>

			<<?php echo $tag; ?>>
				<tr>
					<th scope="col" class="manage-column"><?php _e( 'Title', 'pronamic_ideal' ) ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Size', 'pronamic_ideal' ) ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Image', 'pronamic_ideal' ) ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Code', 'pronamic_ideal' ) ?></th>
				</tr>
			</<?php echo $tag; ?>>

		<?php endforeach; ?>

		<tbody>

			<?php foreach ( $icons as $icon ): ?>

				<tr>
					<td>
						<?php echo $icon['title']; ?>
					</td>
					<td>
						<?php 
						
						echo $icon['width'], '&times;', $icon['height'];
						
						?>
					</td>
					<td>
						<?php 
						
						printf(
							'<img src="%s" alt="" />',
							esc_attr( $icon['url'] )
						);
						
						?>
					</td>
					<td>
						<textarea class="regular-text code" cols="30" rows="3"><?php echo esc_textarea( $icon['url'] ); ?></textarea>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

	<h3><?php _e( 'Banners', 'pronamic_ideal' ); ?></h3>

	<?php 
	
	$images = array(
		array(
			'title'  => __( 'Banner 234&times;100', 'pronamic_ideal' ),
			'width'  => 234,
			'height' => 100,
			'file'   => 'html/ideal/banner-234x100-campaign.html',
		),
		array(
			'title'  => __( 'Banner 234&times;100 (without campaign statements)', 'pronamic_ideal' ),
			'width'  => 234,
			'height' => 100,
			'file'   => 'html/ideal/banner-234x100.html',
		),
		array(
			'title'  => __( 'Banner 234&times;60', 'pronamic_ideal' ),
			'width'  => 234,
			'height' => 60,
			'file'   => 'html/ideal/banner-234x60-campaign.html',
		),
		array(
			'title'  => __( 'Banner 234&times;60 (without campaign statements)', 'pronamic_ideal' ),
			'width'  => 234,
			'height' => 60,
			'file'   => 'html/ideal/banner-234x60.html',
		),
		array(
			'title'  => __( 'Banner 155&times;100', 'pronamic_ideal' ),
			'width'  => 155,
			'height' => 100,
			'file'   => 'html/ideal/banner-155x100-campaign.html',
		),
		array(
			'title'  => __( 'Banner 155&times;100 (without campaign statements)', 'pronamic_ideal' ),
			'width'  => 155,
			'height' => 100,
			'file'   => 'html/ideal/banner-155x100.html',
		),

		array(
			'title'  => __( 'Banner 155&times;55', 'pronamic_ideal' ),
			'width'  => 155,
			'height' => 55,
			'file'   => 'html/ideal/banner-155x55-campaign.html',
		),
		array(
			'title'  => __( 'Banner 155&times;55 (without campaign statements)', 'pronamic_ideal' ),
			'width'  => 155,
			'height' => 55,
			'file'   => 'html/ideal/banner-155x55.html',
		),
		array(
			'title'  => __( 'Banner 100&times;100', 'pronamic_ideal' ),
			'width'  => 100,
			'height' => 100,
			'file'   => 'html/ideal/banner-100x100-campaign.html',
		),
		array(
			'title'  => __( 'Banner 100&times;100 (without campaign statements)', 'pronamic_ideal' ),
			'width'  => 100,
			'height' => 100,
			'file'   => 'html/ideal/banner-100x100.html',
		),
		array(
			'title'  => __( 'Mollie bage - Powered by', 'pronamic_ideal' ),
			'width'  => 111,
			'height' => 68,
			'file'   => 'html/mollie/badge-powered-small.html',
		),
		array(
			'title'  => __( 'Mollie bage - Powered by', 'pronamic_ideal' ),
			'width'  => 135,
			'height' => 87,
			'file'   => 'html/mollie/badge-powered-medium.html',
		),
		array(
			'title'  => __( 'Mollie bage - Powered by', 'pronamic_ideal' ),
			'width'  => 155,
			'height' => 101,
			'file'   => 'html/mollie/badge-powered-big.html',
		),
		array(
			'title'  => __( 'Mollie bage - Betaling groen', 'pronamic_ideal' ),
			'width'  => 60,
			'height' => 74,
			'file'   => 'html/mollie/badge-betaling-groen-small.html',
		),
		array(
			'title'  => __( 'Mollie bage - Betaling groen', 'pronamic_ideal' ),
			'width'  => 70,
			'height' => 87,
			'file'   => 'html/mollie/badge-betaling-groen-medium.html',
		),
		array(
			'title'  => __( 'Mollie bage - Betaling groen', 'pronamic_ideal' ),
			'width'  => 80,
			'height' => 100,
			'file'   => 'html/mollie/badge-betaling-groen-big.html',
		),
	);

	?>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach ( array( 'thead', 'tfoot' ) as $tag ): ?>

			<<?php echo $tag; ?>>
				<tr>
					<th scope="col" class="manage-column"><?php _e( 'Title', 'pronamic_ideal' ) ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Size', 'pronamic_ideal' ) ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Image', 'pronamic_ideal' ) ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Code', 'pronamic_ideal' ) ?></th>
				</tr>
			</<?php echo $tag; ?>>

		<?php endforeach; ?>

		<tbody>

			<?php foreach ( $images as $image ): ?>

				<tr>
					<?php 
					
					$code = '';

					if ( isset( $image['code'] ) ) {
						$code = $image['code'];
					} elseif( isset( $image['file'] ) ) {
						$filename = plugin_dir_path( Pronamic_WordPress_IDeal_Plugin::$file ) . $image['file'];
						
						$code = file_get_contents( $filename );
					}

					?>
					<td>
						<?php echo $image['title']; ?>
					</td>
					<td>
						<?php 
						
						echo $image['width'], '&times;', $image['height'];
						
						?>
					</td>
					<td>
						<?php echo $code; ?>
					</td>
					<td>
						<textarea class="regular-text code" cols="30" rows="3"><?php echo esc_textarea( $code ); ?></textarea>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
	
	<?php include 'pronamic.php'; ?>
</div>