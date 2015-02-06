<h3><?php _e( 'Supported Extensions', 'pronamic_ideal' ); ?></h3>

<?php

$extensions = array(
	// Plugins
	'easy-digital-downloads' => array(
		'name'               => __( 'Easy Digital Downloads', 'pronamic_ideal' ),
		'url'                => 'https://easydigitaldownloads.com/',
		'wp_org_url'         => 'http://wordpress.org/plugins/easy-digital-downloads/',
		'github_url'         => 'https://github.com/easydigitaldownloads/Easy-Digital-Downloads',
		'active'             => Pronamic_WP_Pay_Extensions_EDD_EasyDigitalDownloads::is_active(),
		'requires_at_least'  => '1.8.6',
		'tested_up_to'       => '2.1.3',
		'author'             => __( 'Pippin Williamson', 'pronamic_ideal' ),
		'author_url'         => 'http://pippinsplugins.com/',
	),
	'event-espresso-3'       => array(
		'name'               => __( 'Event Espresso 3', 'pronamic_ideal' ),
		'url'                => 'http://eventespresso.com/',
		'github_url'         => 'https://github.com/eventespresso/event-espresso-legacy',
		'active'             => Pronamic_WP_Pay_Extensions_EventEspressoLegacy_EventEspresso::is_active(),
		'requires_at_least'  => '3.1.24',
		'tested_up_to'       => '3.1.35.P',
	),
	'event-espresso-free'    => array(
		'name'               => __( 'Event Espresso 3 Lite', 'pronamic_ideal' ),
		'url'                => 'http://eventespresso.com/',
		'github_url'         => 'https://github.com/eventespresso/event-espresso-core',
		'wp_org_url'         => 'http://wordpress.org/plugins/event-espresso-free/',
		'active'             => Pronamic_WP_Pay_Extensions_EventEspressoLegacy_EventEspresso::is_active(),
		'requires_at_least'  => '3.1.29.1.L',
		'tested_up_to'       => '3.1.35.L',
	),
	'event-espresso-4'       => array(
		'name'               => __( 'Event Espresso 4', 'pronamic_ideal' ),
		'url'                => 'http://eventespresso.com/',
		'github_url'         => 'https://github.com/eventespresso/event-espresso-core',
		'active'             => Pronamic_WP_Pay_Extensions_EventEspresso_Extension::is_active(),
		'requires_at_least'  => '4.4.7.p',
		'tested_up_to'       => '4.4.7.p',
	),
	'event-espresso-decaf'    => array(
		'name'               => __( 'Event Espresso 4 Decaf', 'pronamic_ideal' ),
		'url'                => 'http://eventespresso.com/',
		'github_url'         => 'https://github.com/eventespresso/event-espresso-core',
		'wp_org_url'         => 'https://wordpress.org/plugins/event-espresso-decaf/',
		'active'             => Pronamic_WP_Pay_Extensions_EventEspresso_Extension::is_active(),
		'requires_at_least'  => '4.4.4.decaf',
		'tested_up_to'       => '4.4.4.decaf',
	),
	'gravityforms'           => array(
		'name'               => __( 'Gravity Forms', 'pronamic_ideal' ),
		'url'                => 'http://www.gravityforms.com/',
		'github_url'         => 'https://github.com/gravityforms/gravityforms',
		'active'             => Pronamic_WP_Pay_Extensions_GravityForms_GravityForms::is_active(),
		'requires_at_least'  => '1.6',
		'tested_up_to'       => '1.8.13',
		'author'             => __( 'Rocketgenius', 'pronamic_ideal' ),
		'author_url'         => 'http://www.rocketgenius.com/',
	),
	'gravityformsaweber'     => array(
		'name'               => __( 'Gravity Forms AWeber Add-On', 'pronamic_ideal' ),
		'url'                => 'http://www.gravityforms.com/add-ons/aweber/',
		'github_url'         => 'https://github.com/gravityforms/gravityformsaweber',
		'requires_at_least'  => '1.4.2',
		'tested_up_to'       => '1.4.2',
		'author'             => __( 'Rocketgenius', 'pronamic_ideal' ),
		'author_url'         => 'http://www.rocketgenius.com/',
	),
	'gravityformscampaignmonitor' => array(
		'name'               => __( 'Gravity Forms Campaign Monitor Add-On', 'pronamic_ideal' ),
		'url'                => 'http://www.gravityforms.com/add-ons/campaign-monitor/',
		'github_url'         => 'https://github.com/gravityforms/gravityformscampaignmonitor',
		'requires_at_least'  => '2.5.1',
		'tested_up_to'       => '2.5.1',
		'author'             => __( 'Rocketgenius', 'pronamic_ideal' ),
		'author_url'         => 'http://www.rocketgenius.com/',
	),
	'gravityformsmailchimp'  => array(
		'name'               => __( 'Gravity Forms MailChimp Add-On', 'pronamic_ideal' ),
		'url'                => 'http://www.gravityforms.com/add-ons/mailchimp/',
		'github_url'         => 'https://github.com/gravityforms/gravityformsmailchimp',
		'requires_at_least'  => '2.4.1',
		'tested_up_to'       => '2.4.1',
		'author'             => __( 'Rocketgenius', 'pronamic_ideal' ),
		'author_url'         => 'http://www.rocketgenius.com/',
	),
	'gravityformsuserregistration' => array(
		'name'               => __( 'Gravity Forms User Registration Add-On', 'pronamic_ideal' ),
		'url'                => 'http://www.gravityforms.com/add-ons/user-registration/',
		'github_url'         => 'https://github.com/gravityforms/gravityformsmailchimp',
		'requires_at_least'  => '1.8',
		'tested_up_to'       => '1.8',
		'author'             => __( 'Rocketgenius', 'pronamic_ideal' ),
		'author_url'         => 'http://www.rocketgenius.com/',
	),
	'gravityformszapier'     => array(
		'name'               => __( 'Gravity Forms Zapier Add-On', 'pronamic_ideal' ),
		'url'                => 'http://www.gravityforms.com/add-ons/zapier/',
		'github_url'         => 'https://github.com/gravityforms/gravityformszapier',
		'requires_at_least'  => '1.4.2',
		'tested_up_to'       => '1.4.2',
		'author'             => __( 'Rocketgenius', 'pronamic_ideal' ),
		'author_url'         => 'http://www.rocketgenius.com/',
	),
	'ithemes-exchange'       => array(
		'name'               => __( 'iThemes Exchange', 'pronamic_ideal' ),
		'url'                => 'http://ithemes.com/exchange/',
		'github_url'         => 'https://github.com/wp-plugins/ithemes-exchange',
		'wp_org_url'         => 'http://wordpress.org/plugins/ithemes-exchange/',
		'active'             => Pronamic_WP_Pay_Extensions_IThemesExchange_IThemesExchange::is_active(),
		'requires_at_least'  => '1.7.14',
		'tested_up_to'       => '1.7.16',
		'author'             => __( 'iThemes', 'pronamic_ideal' ),
		'author_url'         => 'http://ithemes.com/',
	),
	'jigoshop'               => array(
		'name'               => __( 'Jigoshop', 'pronamic_ideal' ),
		'url'                => 'http://jigoshop.com/',
		'wp_org_url'         => 'http://wordpress.org/plugins/jigoshop/',
		'github_url'         => 'https://github.com/jigoshop/jigoshop',
		'active'             => Pronamic_WP_Pay_Extensions_Jigoshop_Jigoshop::is_active(),
		'requires_at_least'  => '1.1',
		'tested_up_to'       => '1.12',
		'author'             => __( 'Jigowatt', 'pronamic_ideal' ),
		'author_url'         => 'http://jigowatt.co.uk/',
	),
	'membership'             => array(
		'name'               => __( 'Membership', 'pronamic_ideal' ),
		'url'                => 'http://wordpress.org/plugins/membership/',
		'wp_org_url'         => 'http://wordpress.org/plugins/membership/',
		'active'             => Pronamic_WPMUDEV_Membership_Membership::is_active(),
		'requires_at_least'  => '3.4.4.1',
		'tested_up_to'       => '3.4.4.1',
		'author'             => __( 'WPMUDEV.org', 'pronamic_ideal' ),
		'author_url'         => 'http://www.wpmudev.org/',
	),
	'membership-premium'     => array(
		'name'               => __( 'Membership Premium', 'pronamic_ideal' ),
		'url'                => 'https://premium.wpmudev.org/project/membership/',
		'github_url'         => 'https://github.com/pronamic-wpmudev/membership-premium',
		'active'             => Pronamic_WPMUDEV_Membership_Membership::is_active(),
		'requires_at_least'  => '3.4.6',
		'tested_up_to'       => '3.5.1.3',
		'author'             => __( 'WPMUDEV.org', 'pronamic_ideal' ),
		'author_url'         => 'http://www.wpmudev.org/',
	),
	's2member'               => array(
		'name'               => __( 's2Member®', 'pronamic_ideal' ),
		'url'                => 'http://www.s2member.com/',
		'wp_org_url'         => 'http://wordpress.org/plugins/s2member/',
		'github_url'         => 'https://github.com/WebSharks/s2Member',
		'active'             => Pronamic_WP_Pay_Extensions_S2Member_S2Member::is_active(),
		'requires_at_least'  => '130816',
		'tested_up_to'       => '141007',
		'author'             => __( 'WebSharks, Inc.', 'pronamic_ideal' ),
		'author_url'         => 'http://www.websharks-inc.com/',
	),
	'shopp'                  => array(
		'name'               => __( 'Shopp', 'pronamic_ideal' ),
		'url'                => 'https://shopplugin.net/',
		'wp_org_url'         => 'http://wordpress.org/plugins/shopp/',
		'github_url'         => 'https://github.com/ingenesis/shopp',
		'active'             => Pronamic_WP_Pay_Extensions_Shopp_Shopp::is_active(),
		'requires_at_least'  => '1.1',
		'tested_up_to'       => '1.3.5',
		'author'             => __( 'Ingenesis Limited', 'pronamic_ideal' ),
		'author_url'         => 'http://ingenesis.net/',
	),
	'woocommerce'            => array(
		'name'               => __( 'WooCommerce', 'pronamic_ideal' ),
		'url'                => 'http://www.woothemes.com/woocommerce/',
		'github_url'         => 'https://github.com/woothemes/woocommerce',
		'wp_org_url'         => 'http://wordpress.org/plugins/woocommerce/',
		'active'             => Pronamic_WP_Pay_Extensions_WooCommerce_WooCommerce::is_active(),
		'requires_at_least'  => '2.1.0',
		'tested_up_to'       => '2.1.5',
		'author'             => __( 'WooThemes', 'pronamic_ideal' ),
		'author_url'         => 'http://www.woothemes.com/',
	),
	'wp-e-commerce'          => array(
		'name'               => __( 'WP e-Commerce', 'pronamic_ideal' ),
		'url'                => 'http://getshopped.org/',
		'wp_org_url'         => 'http://wordpress.org/plugins/wp-e-commerce/',
		'github_url'         => 'https://github.com/wp-e-commerce/WP-e-Commerce',
		'active'             => Pronamic_WPeCommerce_WPeCommerce::is_active(),
		'requires_at_least'  => '3.8.12.1',
		'tested_up_to'       => '3.8.14',
		'author'             => __( 'Instinct Entertainment', 'pronamic_ideal' ),
		'author_url'         => 'http://instinct.co.nz/',
	),
	'appthemer-crowdfunding' => array(
		'name'               => __( 'Crowdfunding by Astoundify', 'pronamic_ideal' ),
		'wp_org_url'         => 'http://wordpress.org/plugins/appthemer-crowdfunding/',
		'github_url'         => 'https://github.com/astoundify/crowdfunding/',
		'requires_at_least'  => '1.8.2',
		'tested_up_to'       => '1.8.2',
		'author'             => __( 'Astoundify', 'pronamic_ideal' ),
		'author_url'         => 'http://www.astoundify.com/',
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
	'vantage'                => array(
		'name'               => __( 'Vantage', 'pronamic_ideal' ),
		'url'                => 'http://www.appthemes.com/themes/vantage/',
		'tested_up_to'       => '1.3.2',
		'author'             => __( 'AppThemes', 'pronamic_ideal' ),
		'author_url'         => 'http://www.appthemes.com/',
	),
	'campaignify'            => array(
		'name'               => __( 'Campaignify', 'pronamic_ideal' ),
		'url'                => 'http://www.astoundify.com/',
		'tested_up_to'       => '1.5',
		'author'             => __( 'Astoundify', 'pronamic_ideal' ),
		'author_url'         => 'http://www.astoundify.com/',
	),
);

include 'extensions-wp-admin.php';

$output = array(
	'readme-md'  => 'extensions-readme-md.php',
	'readme-txt' => 'extensions-readme-txt.php',
);

foreach ( $output as $name => $file ) {
	if ( filter_has_var( INPUT_GET, $name ) ) : ?>

		<h4><?php _e( 'Markdown', 'pronamic_ideal' ); ?></h4>

		<?php

		ob_start();

		include $file;

		$markdown = ob_get_clean();

		?>

		<textarea cols="60" rows="25"><?php echo esc_textarea( $markdown ); ?></textarea>

	<?php endif;
}
