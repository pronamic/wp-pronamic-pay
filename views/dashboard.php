<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder columns-2">
			<div id="postbox-container-1" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Status', 'twinfield' ); ?></span></h3>
	
						<div class="inside">
							
						</div>
					</div>
				</div>
			</div>
			
			<div id="postbox-container-2" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Pronamic News', 'pronamic_ideal' ); ?></span></h3>
	
						<div class="inside">
							<?php 
	
							wp_widget_rss_output( 'http://feeds.feedburner.com/pronamic', array(
								'link'  => __( 'http://www.pronamic.eu/', 'pronamic_ideal' ),
								'url'   => 'http://feeds.feedburner.com/pronamic',
								'title' => __( 'Pronamic News', 'pronamic_ideal' ),
								'items' => 5
							) );
							
							?>
						</div>
					</div>
				</div>
			</div>
	
			<div class="clear"></div>
		</div>
	</div>

	<?php include 'pronamic.php'; ?>
</div>