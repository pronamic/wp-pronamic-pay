<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<h3>
		<?php _e( 'Supported Extensions', 'pronamic_ideal' ); ?>
	</h3>

	<?php 
	
	$extensions = array(
		// Plugins
		'easy-digital-downloads' => array(
			'name'               => __( 'Easy Digital Downloads', 'pronamic_ideal' ),
			'url'                => 'https://easydigitaldownloads.com/',
			'wp_org_url'         => 'http://wordpress.org/plugins/easy-digital-downloads/',
			'github_url'         => 'https://github.com/easydigitaldownloads/Easy-Digital-Downloads',
			'active'             => Pronamic_EasyDigitalDownloads_EasyDigitalDownloads::is_active(),
			'requires_at_least'  => '1.8.6',
			'tested_up_to'       => '1.8.6',
			'author'             => __( 'Pippin Williamson', 'pronamic_ideal' ),
			'author_url'         => 'http://pippinsplugins.com/',
		),
		'event-espresso'         => array(
			'name'               => __( 'Event Espresso', 'pronamic_ideal' ),
			'url'                => 'http://eventespresso.com/',
			'github_url'         => 'https://github.com/eventespresso/event-espresso-core',
			'active'             => Pronamic_EventEspresso_EventEspresso::is_active(),
			'requires_at_least'  => '3.1.24',
			'tested_up_to'       => '3.1.35.P',
		),
		'event-espresso-free'    => array(
			'name'               => __( 'Event Espresso Lite', 'pronamic_ideal' ),
			'url'                => 'http://eventespresso.com/',
			'github_url'         => 'https://github.com/eventespresso/event-espresso-core',
			'wp_org_url'         => 'http://wordpress.org/extend/plugins/event-espresso-free/',
			'active'             => Pronamic_EventEspresso_EventEspresso::is_active(),
			'requires_at_least'  => '3.1.29.1.L',
			'tested_up_to'       => '3.1.35.L',
		),
		'gravityforms'           => array(
			'name'               => __( 'Gravity Forms', 'pronamic_ideal' ),
			'url'                => 'http://www.gravityforms.com/',
			'active'             => Pronamic_GravityForms_GravityForms::is_active(),
			'requires_at_least'  => '1.6',
			'tested_up_to'       => '1.8',
			'author'             => __( 'Rocketgenius', 'pronamic_ideal' ),
			'author_url'         => 'http://www.rocketgenius.com/',
		),
		'ithemes-exchange'       => array(
			'name'               => __( 'iThemes Exchange', 'pronamic_ideal' ),
			'url'                => 'http://ithemes.com/exchange/',
			'github_url'         => 'https://github.com/wp-plugins/ithemes-exchange',
			'wp_org_url'         => 'http://wordpress.org/extend/plugins/ithemes-exchange/',
			'active'             => Pronamic_IThemesExchange_IThemesExchange::is_active(),
			'requires_at_least'  => '1.7.14',
			'tested_up_to'       => '1.7.16',
			'author'             => __( 'iThemes', 'pronamic_ideal' ),
			'author_url'         => 'http://ithemes.com/',
		),
		'jigoshop'               => array(
			'name'               => __( 'Jigoshop', 'pronamic_ideal' ),
			'url'                => 'http://jigoshop.com/',
			'wp_org_url'         => 'http://wordpress.org/extend/plugins/jigoshop/',
			'github_url'         => 'https://github.com/jigoshop/jigoshop',
			'active'             => Pronamic_Jigoshop_Jigoshop::is_active(),
			'requires_at_least'  => '1.1',
			'tested_up_to'       => '1.8',
			'author'             => __( 'Jigowatt', 'pronamic_ideal' ),
			'author_url'         => 'http://jigowatt.co.uk/',
		),
		'membership'             => array(
			'name'               => __( 'Membership', 'pronamic_ideal' ),
			'url'                => 'http://wordpress.org/plugins/membership/',
			'wp_org_url'         => 'http://wordpress.org/plugins/membership/',
			'active'             => Pronamic_Membership_Membership::is_active(),
			'requires_at_least'  => '3.4.4.1',
			'tested_up_to'       => '3.4.4.1',
			'author'             => __( 'WPMUDEV.org', 'pronamic_ideal' ),
			'author_url'         => 'http://www.wpmudev.org/',
		),
		'membership-premium'     => array(
			'name'               => __( 'Membership Premium', 'pronamic_ideal' ),
			'url'                => 'http://premium.wpmudev.org/project/membership/',
			'active'             => Pronamic_Membership_Membership::is_active(),
			'requires_at_least'  => '3.4.6',
			'tested_up_to'       => '3.4.6',
			'author'             => __( 'WPMUDEV.org', 'pronamic_ideal' ),
			'author_url'         => 'http://www.wpmudev.org/',
		),
		's2member'               => array(
			'name'               => __( 's2Member®', 'pronamic_ideal' ),
			'url'                => 'http://www.s2member.com/',
			'wp_org_url'         => 'http://wordpress.org/plugins/membership/',
			'github_url'         => 'https://github.com/WebSharks/s2Member',
			'active'             => Pronamic_S2Member_S2Member::is_active(),
			'requires_at_least'  => '130816',
			'tested_up_to'       => '140105',
			'author'             => __( 'WebSharks, Inc.', 'pronamic_ideal' ),
			'author_url'         => 'http://www.websharks-inc.com/',
		),
		'shopp'                  => array(
			'name'               => __( 'Shopp', 'pronamic_ideal' ),
			'url'                => 'https://shopplugin.net/',
			'github_url'         => 'https://github.com/ingenesis/shopp',
			'active'             => Pronamic_Shopp_Shopp::is_active(),
			'requires_at_least'  => '1.1',
			'tested_up_to'       => '1.3.1',
			'author'             => __( 'Ingenesis Limited', 'pronamic_ideal' ),
			'author_url'         => 'http://ingenesis.net/',
		),
		'woocommerce'            => array(
			'name'               => __( 'WooCommerce', 'pronamic_ideal' ),
			'url'                => 'http://www.woothemes.com/woocommerce/',
			'github_url'         => 'https://github.com/woothemes/woocommerce',
			'wp_org_url'         => 'http://wordpress.org/extend/plugins/woocommerce/',
			'active'             => Pronamic_WooCommerce_WooCommerce::is_active(),
			'requires_at_least'  => '2.0.0',
			'tested_up_to'       => '2.1.5',
			'author'             => __( 'WooThemes', 'pronamic_ideal' ),
			'author_url'         => 'http://www.woothemes.com/',
		),
		'wp-e-commerce'          => array(
			'name'               => __( 'WP e-Commerce', 'pronamic_ideal' ),
			'url'                => 'http://getshopped.org/',
			'wp_org_url'         => 'http://wordpress.org/extend/plugins/wp-e-commerce/',
			'github_url'         => 'https://github.com/wp-e-commerce/WP-e-Commerce',
			'active'             => Pronamic_WPeCommerce_WPeCommerce::is_active(),
			'requires_at_least'  => '3.8.12.1',
			'tested_up_to'       => '3.8.12.1',
			'author'             => __( 'Instinct Entertainment', 'pronamic_ideal' ),
			'author_url'         => 'http://instinct.co.nz/',
		),
		// Themes
		'classipress'            => array(
			'name'               => __( 'ClassiPress', 'pronamic_ideal' ),
			'url'                => 'http://www.appthemes.com/themes/classipress/',
			'active'             => Pronamic_ClassiPress_ClassiPress::is_active(),
			'requires_at_least'  => '3.3.1',
			'tested_up_to'       => '3.3.3',
			'author'             => __( 'AppThemes', 'pronamic_ideal' ),
			'author_url'         => 'http://www.appthemes.com/',
		),
		'jobroller'              => array(
			'name'               => __( 'JobRoller', 'pronamic_ideal' ),
			'url'                => 'http://www.appthemes.com/themes/jobroller/',
			'active'             => Pronamic_JobRoller_JobRoller::is_active(),
			'tested_up_to'       => '1.7.1',
			'author'             => __( 'AppThemes', 'pronamic_ideal' ),
			'author_url'         => 'http://www.appthemes.com/',
		),
	);
	
	include 'extensions-wp-admin.php';

	if ( filter_has_var( INPUT_GET, 'markdown' ) ) : ?>
	
		<h4><?php _e( 'Markdown', 'pronamic_ideal' ); ?></h4>
		
		<?php 
		
		ob_start();
		
		include 'extensions-readme-md.php';
		
		$markdown = ob_get_clean();

		?>
		
		<textarea cols="60" rows="25"><?php echo esc_textarea( $markdown ); ?></textarea>
	
	<?php endif; ?>

	<?php include 'pronamic.php'; ?>
</div>