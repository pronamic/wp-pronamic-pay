<?php
/**
 * Gateway Post Type
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

/**
 * Title: WordPress gateway post type
 *
 * @author Remco Tolsma
 * @version 3.8.0
 * @since ?
 */
class GatewayPostType {
	/**
	 * Post type.
	 *
	 * @var string
	 */
	const POST_TYPE = 'pronamic_gateway';

	/**
	 * Constructs and initializes a gateway post type object.
	 */
	public function __construct() {
		/**
		 * Priotiry of the initial post types function should be set to < 10.
		 *
		 * @see https://core.trac.wordpress.org/ticket/28488
		 * @see https://core.trac.wordpress.org/changeset/29318
		 *
		 * @see https://github.com/WordPress/WordPress/blob/4.0/wp-includes/post.php#L167
		 */
		add_action( 'init', array( $this, 'register_gateway_post_type' ), 0 ); // Highest priority.

		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'maybe_set_default_gateway' ) );
	}

	/**
	 * Register post types.
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.6.1/wp-includes/post.php#L1277-L1300
	 */
	public function register_gateway_post_type() {
		register_post_type(
			'pronamic_gateway', array(
				'label'              => __( 'Payment Gateway Configurations', 'pronamic_ideal' ),
				'labels'             => array(
					'name'                  => __( 'Payment Gateway Configurations', 'pronamic_ideal' ),
					'singular_name'         => __( 'Payment Gateway Configuration', 'pronamic_ideal' ),
					'add_new'               => __( 'Add New', 'pronamic_ideal' ),
					'add_new_item'          => __( 'Add New Payment Gateway Configuration', 'pronamic_ideal' ),
					'edit_item'             => __( 'Edit Payment Gateway Configuration', 'pronamic_ideal' ),
					'new_item'              => __( 'New Payment Gateway Configuration', 'pronamic_ideal' ),
					'all_items'             => __( 'All Payment Gateway Configurations', 'pronamic_ideal' ),
					'view_item'             => __( 'View Payment Gateway Configuration', 'pronamic_ideal' ),
					'search_items'          => __( 'Search Payment Gateway Configurations', 'pronamic_ideal' ),
					'not_found'             => __( 'No payment gateway configurations found.', 'pronamic_ideal' ),
					'not_found_in_trash'    => __( 'No payment gateway configurations found in Trash.', 'pronamic_ideal' ),
					'menu_name'             => __( 'Configurations', 'pronamic_ideal' ),
					'filter_items_list'     => __( 'Filter payment gateway configurations list', 'pronamic_ideal' ),
					'items_list_navigation' => __( 'Payment gateway configurations list navigation', 'pronamic_ideal' ),
					'items_list'            => __( 'Payment gateway configurations list', 'pronamic_ideal' ),
				),
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_nav_menus'  => false,
				'show_in_menu'       => false,
				'show_in_admin_bar'  => false,
				'hierarchical'       => true,
				'supports'           => array(
					'title',
					'revisions',
				),
				'rewrite'            => false,
				'query_var'          => false,
				'capabilities'       => self::get_capabilities(),
				// Don't map meta capabilities since we only use the `manage_options` capability for this post type.
				'map_meta_cap'       => false,
			)
		);
	}

	/**
	 * Maybe set the default gateway.
	 *
	 * @param int $post_id Post ID.
	 */
	public function maybe_set_default_gateway( $post_id ) {
		// Don't set the default gateway if the post is not published.
		if ( 'publish' !== get_post_status( $post_id ) ) {
			return;
		}

		// Don't set the default gateway if there is already a published gateway set.
		$config_id = get_option( 'pronamic_pay_config_id' );

		if ( ! empty( $config_id ) && 'publish' === get_post_status( $config_id ) ) {
			return;
		}

		// Update.
		update_option( 'pronamic_pay_config_id', $post_id );
	}

	/**
	 * Get capabilities for this post type.
	 *
	 * @return array
	 */
	public static function get_capabilities() {
		return array(
			'edit_post'              => 'manage_options',
			'read_post'              => 'manage_options',
			'delete_post'            => 'manage_options',
			'edit_posts'             => 'manage_options',
			'edit_others_posts'      => 'manage_options',
			'publish_posts'          => 'manage_options',
			'read_private_posts'     => 'manage_options',
			'read'                   => 'manage_options',
			'delete_posts'           => 'manage_options',
			'delete_private_posts'   => 'manage_options',
			'delete_published_posts' => 'manage_options',
			'delete_others_posts'    => 'manage_options',
			'edit_private_posts'     => 'manage_options',
			'edit_published_posts'   => 'manage_options',
			'create_posts'           => 'manage_options',
		);
	}
}
