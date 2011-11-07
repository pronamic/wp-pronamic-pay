<?php 

function pronamic_ideal_create_pages($pages, $parent = null) {
	foreach($pages as $page) {
		$post = array(
			'post_title' => $page['post_title'] ,
			'post_name' => $page['post_title'] ,
			'post_content' => $page['post_content'] ,  
			'post_status' => 'publish' , 
			'post_type' => 'page' 
		);
		
		if(isset($parent)) {
			$post['post_parent'] = $parent;
		}

		$result = wp_insert_post($post, true);
		
		if(!is_wp_error($result)) {
			if(isset($page['children'])) {
				pronamic_ideal_create_pages($page['children'], $result);
			}
		}
	}
}

if(!empty($_POST) && check_admin_referer('pronamic_ideal_pages_generator', 'pronamic_ideal_nonce')) {
	pronamic_ideal_create_pages($_POST['pronamic_ideal_pages']);
}

?>
<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Pages Generator', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<p>
		<?php _e('This page allows you to easily create pages for each iDEAL payment status.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</p>

	<form action="" method="post">
		<?php wp_nonce_field('pronamic_ideal_pages_generator', 'pronamic_ideal_nonce'); ?>

		<?php 
		
		$pages = array(
			'ideal' => array(
				'post_title' => __('iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
				'post_name' => __('ideal', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
				'post_content' => '' ,  
				'children' => array(
					'error' => array(
						'post_title' => __('iDEAL payment error', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
						'post_name' => __('error', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
						'post_content' => __('<p>Unfortunately an error has occurred during your iDEAL payment.</p>', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN)
					) , 
					'cancel' => array(
						'post_title' => __('iDEAL payment canceled', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
						'post_name' => __('cancelled', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
						'post_content' => __('<p>You canceled the iDEAL payment.</p>', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN)
					) , 
					'unknown' => array(
						'post_title' => __('iDEAL payment unknown', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
						'post_name' => __('unknown', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
						'post_content' => __('<p>The status of your iDEAL payment is unknown.</p>', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN)
					) , 
					'expired' => array(
						'post_title' => __('iDEAL payment expired', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
						'post_name' => __('expired', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
						'post_content' => __('<p>Unfortunately your iDEAL payment session has expired.</p>', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN)
					) , 
					'completed' => array(
						'post_title' => __('iDEAL payment completed', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
						'post_name' => __('completed', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ,
						'post_content' => __('<p>The payment process is successfully completed.</p>', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN)
					)
				)
			)
		);
		
		function pronamic_ideal_pages($pages, $namePrefix, $level = 0) {
			?>
			<ul style="padding-left: <?php echo $level * 25; ?>px">
	
				<?php foreach($pages as $i => $page): ?>
	
				<li>
					<?php $name = $namePrefix . '[' . $i . ']'; ?>

					<h3><?php echo $page['post_title']; ?></h3>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="pronamic_ideal_page_<?php echo $i; ?>_post_title">
									<?php _e('Title', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
								</label>
							</th>
							<td>
				                <input id="pronamic_ideal_page_<?php echo $i; ?>_post_title" name="<?php echo $name; ?>[post_title]" value="<?php echo $page['post_title']; ?>" type="text" class="regular-text" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pronamic_ideal_page_<?php echo $i; ?>_post_name">
									<?php _e('Slug', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
								</label>
							</th>
							<td>
				                <input id="pronamic_ideal_page_<?php echo $i; ?>_post_name" name="<?php echo $name; ?>[post_name]" value="<?php echo $page['post_name']; ?>" type="text" class="regular-text" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pronamic_ideal_page_<?php echo $i; ?>_post_content">
									<?php _e('Content', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
								</label>
							</th>
							<td>
				                <textarea id="pronamic_ideal_page_<?php echo $i; ?>_post_content" name="<?php echo $name; ?>[post_content]" rows="2" cols="60"><?php echo $page['post_content']; ?></textarea>
							</td>
						</tr>
					</table>
	
					<?php 
					
					if(isset($page['children'])) {
						pronamic_ideal_pages($page['children'], $name . '[children]', $level + 1);
					} 
					
					?>				
				</li>
			
				<?php endforeach; ?>
	
			</ul>
			<?php
		}
		
		pronamic_ideal_pages($pages, 'pronamic_ideal_pages');

		submit_button(
			__('Generate Pages', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) , 
			'primary' ,
			'create_pages'
		);

		?>
	</form>
</div>