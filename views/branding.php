<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2>
		<?php _e( 'iDEAL Branding', 'pronamic_ideal' ); ?>
	</h2>

	<h3>
		<?php _e( 'Banners', 'pronamic_ideal' ); ?>
	</h3>

	<?php 
	
	$images = array(
		array(
			'title' => __( 'Banner 234&times;100', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=234.100" target="_blank">
	<img src="http://ideal.nl/img/234x100.gif" width="234" height="100" border="0" />
</a>
HTML
		),
		array(
			'title' => __( 'Banner 234&times;100 (without campaign statements)', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=234.100&c=no" target="_blank">
   <img src="http://ideal.nl/img/234x100no.gif" width="234" height="100" border="0" />
</a>
HTML
		),

		array(
			'title' => __( 'Banner 234&times;60', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=234.60" target="_blank">
   <img src="http://ideal.nl/img/234x60.gif" width="234" height="60" border="0" />
</a>
HTML
		),
		array(
			'title' => __( 'Banner 234&times;60 (without campaign statements)', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=234.60&c=no" target="_blank">
   <img src="http://ideal.nl/img/234x60no.gif" width="234" height="60" border="0" />
</a>
HTML
		),

		array(
			'title' => __( 'Banner 155&times;100', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=155.100" target="_blank">
   <img src="http://ideal.nl/img/155x100.gif" width="155" height="100" border="0" />
</a>
HTML
		),
		array(
			'title' => __( 'Banner 155&times;100 (without campaign statements)', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=155.100&c=no" target="_blank">
   <img src="http://ideal.nl/img/155x100no.gif" width="155" height="100" border="0" />
</a>
HTML
		),

		array(
			'title' => __( 'Banner 155&times;55', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=155.55" target="_blank">
   <img src="http://ideal.nl/img/155x55.gif" width="155" height="55" border="0" />
</a>
HTML
		),
		array(
			'title' => __( 'Banner 155&times;55 (without campaign statements)', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=155.55&c=no" target="_blank">
   <img src="http://ideal.nl/img/155x55no.gif" width="155" height="55" border="0" />
</a>
HTML
		),

		array(
			'title' => __( 'Banner 100&times;100', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=100.100" target="_blank">
   <img src="http://ideal.nl/img/100x100.gif" width="100" height="100" border="0" />
</a>
HTML
		),
		array(
			'title' => __( 'Banner 100&times;100 (without campaign statements)', 'pronamic_ideal' ),
			'code' => <<<HTML
<a href="http://ideal.nl/consument/incoming/?o=100.100&c=no" target="_blank">
   <img src="http://ideal.nl/img/100x100no.gif" width="100" height="100" border="0" />
</a>
HTML
		),
	);
	
	?>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach ( array( 'thead', 'tfoot' ) as $tag ): ?>

			<<?php echo $tag; ?>>
				<tr>
					<th scope="col" class="manage-column"><?php _e( 'Title', 'pronamic_ideal' ) ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Image', 'pronamic_ideal' ) ?></th>
					<th scope="col" class="manage-column"><?php _e( 'Code', 'pronamic_ideal' ) ?></th>
				</tr>
			</<?php echo $tag; ?>>

		<?php endforeach; ?>

		<tbody>

			<?php foreach ( $images as $image ): ?>

				<tr>
					<td>
						<?php echo $image['title']; ?>
					</td>
					<td>
						<?php echo $image['code']; ?>
					</td>
					<td>
						<textarea class="regular-text code" cols="60" rows="3"><?php echo esc_textarea( $image['code'] ); ?></textarea>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
	
	<?php include 'pronamic.php'; ?>
</div>