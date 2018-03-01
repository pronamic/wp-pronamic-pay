<?php
/**
 * Form Post Type
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Forms
 */

namespace Pronamic\WordPress\Pay\Forms;

/**
 * Form Post Type
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class FormPostType {
	/**
	 * Post type.
	 *
	 * @var string
	 */
	const POST_TYPE = 'pronamic_pay_form';

	/**
	 * Amount method input only.
	 *
	 * @var string
	 */
	const AMOUNT_METHOD_INPUT_ONLY = 'input_only';

	/**
	 * Amount method choices only.
	 *
	 * @var string
	 */
	const AMOUNT_METHOD_CHOICES_ONLY = 'choices_only';

	/**
	 * Amount method choices and input.
	 *
	 * @var string
	 */
	const AMOUNT_METHOD_CHOICES_AND_INPUT = 'choices_and_input';

	/**
	 * Constructs and initializes an admin form post type object.
	 */
	public function __construct() {
		/**
		 * Priotiry of the initial post types function should be set to < 10.
		 *
		 * @see https://core.trac.wordpress.org/ticket/28488.
		 * @see https://core.trac.wordpress.org/changeset/29318.
		 *
		 * @see https://github.com/WordPress/WordPress/blob/4.0/wp-includes/post.php#L167.
		 */
		add_action( 'init', array( $this, 'register_post_type' ), 0 ); // Highest priority.

		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', array( $this, 'edit_columns' ) );

		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		/*
		 * Add meta box, we use priority 9 to make sure it loads before Yoast SEO meta box.
		 * @see https://github.com/Yoast/wordpress-seo/blob/2.3.4/admin/class-metabox.php#L20.
		 */
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 9 );

		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_post' ) );

		add_action( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ) );
	}

	/**
	 * Register post type.
	 */
	public function register_post_type() {
		register_post_type(
			self::POST_TYPE, array(
				'label'              => __( 'Payment Forms', 'pronamic_ideal' ),
				'labels'             => array(
					'name'                  => __( 'Payment Forms', 'pronamic_ideal' ),
					'singular_name'         => __( 'Payment Form', 'pronamic_ideal' ),
					'add_new'               => __( 'Add New', 'pronamic_ideal' ),
					'add_new_item'          => __( 'Add New Payment Form', 'pronamic_ideal' ),
					'edit_item'             => __( 'Edit Payment Form', 'pronamic_ideal' ),
					'new_item'              => __( 'New Payment Form', 'pronamic_ideal' ),
					'all_items'             => __( 'All Payment Forms', 'pronamic_ideal' ),
					'view_item'             => __( 'View Payment Form', 'pronamic_ideal' ),
					'search_items'          => __( 'Search Payment Forms', 'pronamic_ideal' ),
					'not_found'             => __( 'No payment forms found.', 'pronamic_ideal' ),
					'not_found_in_trash'    => __( 'No payment forms found in Trash.', 'pronamic_ideal' ),
					'menu_name'             => __( 'Payment Forms', 'pronamic_ideal' ),
					'filter_items_list'     => __( 'Filter payment forms list', 'pronamic_ideal' ),
					'items_list_navigation' => __( 'Payment forms list navigation', 'pronamic_ideal' ),
					'items_list'            => __( 'Payment forms list', 'pronamic_ideal' ),
				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_nav_menus'  => true,
				'show_in_menu'       => false,
				'show_in_admin_bar'  => false,
				'supports'           => array(
					'title',
					'revisions',
				),
				'rewrite'            => array(
					'slug' => _x( 'payment-forms', 'slug', 'pronamic_ideal' ),
				),
				'query_var'          => false,
				'capabilities'       => FormPostType::get_capabilities(),
				'map_meta_cap'       => true,
			)
		);
	}

	/**
	 * Edit columns.
	 *
	 * @param array $columns Edit columns.
	 * @return array
	 */
	public function edit_columns( $columns ) {
		$columns = array(
			'cb'                              => '<input type="checkbox" />',
			'title'                           => __( 'Title', 'pronamic_ideal' ),
			'pronamic_payment_form_gateway'   => __( 'Gateway', 'pronamic_ideal' ),
			'pronamic_payment_form_payments'  => __( 'Payments', 'pronamic_ideal' ),
			'pronamic_payment_form_earnings'  => __( 'Earnings', 'pronamic_ideal' ),
			'pronamic_payment_form_shortcode' => __( 'Shortcode', 'pronamic_ideal' ),
			'date'                            => __( 'Date', 'pronamic_ideal' ),
		);

		return $columns;
	}

	/**
	 * Custom columns.
	 *
	 * @param string $column  Column.
	 * @param string $post_id Post ID.
	 */
	public function custom_columns( $column, $post_id ) {
		global $post;

		switch ( $column ) {
			case 'pronamic_payment_form_gateway':
				$config_id = get_post_meta( $post_id, '_pronamic_payment_form_config_id', true );

				if ( ! empty( $config_id ) ) {
					echo get_the_title( $config_id );
				} else {
					echo '—';
				}

				break;
			case 'pronamic_payment_form_payments':
				global $wpdb;

				$query = $wpdb->prepare(
					"
					SELECT
						COUNT( post.ID ) AS value
					FROM
						$wpdb->posts AS post
							LEFT JOIN
						$wpdb->postmeta AS meta_amount
								ON post.ID = meta_amount.post_id AND meta_amount.meta_key = '_pronamic_payment_amount'
							LEFT JOIN
						$wpdb->postmeta AS meta_source
								ON post.ID = meta_source.post_id AND meta_source.meta_key = '_pronamic_payment_source'
							LEFT JOIN
						$wpdb->postmeta AS meta_source_id
								ON post.ID = meta_source_id.post_id AND meta_source_id.meta_key = '_pronamic_payment_source_id'
					WHERE
						post.post_type = 'pronamic_payment'
							AND
						post.post_status = 'payment_completed'
							AND
						meta_source.meta_value = 'payment_form'
							AND
						meta_source_id.meta_value = %s
					GROUP BY
						post.ID
					;",
					$post_id
				);

				$value = $wpdb->get_var( $query ); // WPCS: unprepared SQL ok.

				echo esc_html( number_format_i18n( $value ) );

				break;
			case 'pronamic_payment_form_earnings':
				global $wpdb;

				$query = $wpdb->prepare(
					"
					SELECT
						SUM( meta_amount.meta_value ) AS value
					FROM
						$wpdb->posts AS post
							LEFT JOIN
						$wpdb->postmeta AS meta_amount
								ON post.ID = meta_amount.post_id AND meta_amount.meta_key = '_pronamic_payment_amount'
							LEFT JOIN
						$wpdb->postmeta AS meta_source
								ON post.ID = meta_source.post_id AND meta_source.meta_key = '_pronamic_payment_source'
							LEFT JOIN
						$wpdb->postmeta AS meta_source_id
								ON post.ID = meta_source_id.post_id AND meta_source_id.meta_key = '_pronamic_payment_source_id'
					WHERE
						post.post_type = 'pronamic_payment'
							AND
						post.post_status = 'payment_completed'
							AND
						meta_source.meta_value = 'payment_form'
							AND
						meta_source_id.meta_value = %s
					GROUP BY
						post.ID
					;",
					$post_id
				);

				$value = $wpdb->get_var( $query ); // WPCS: unprepared SQL ok.

				echo esc_html( \Pronamic\WordPress\Pay\Util::format_price( $value ) );

				break;
			case 'pronamic_payment_form_shortcode':
				printf(
					'<input onclick="this.setSelectionRange( 0, this.value.length )" type="text" class="pronamic-pay-shortcode-input" readonly="" value="%s" />',
					esc_attr( $this->get_shortcode( $post_id ) )
				);

				break;
		}
	}

	/**
	 * Add meta boxes.
	 *
	 * @param string $post_type Post Type.
	 */
	public function add_meta_boxes( $post_type ) {
		if ( self::POST_TYPE === $post_type ) {
			add_meta_box(
				'pronamic_payment_form_options',
				__( 'Form Options', 'pronamic_ideal' ),
				array( $this, 'meta_box_form_options' ),
				$post_type,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Pronamic Pay gateway config meta box.
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function meta_box_form_options( $post ) {
		include \Pronamic\WordPress\Pay\Plugin::$dirname . '/admin/meta-box-form-options.php';
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_post( $post_id ) {
		// Check if our nonce is set.
		if ( ! filter_has_var( INPUT_POST, 'pronamic_pay_nonce' ) ) {
			return $post_id;
		}

		$nonce = filter_input( INPUT_POST, 'pronamic_pay_nonce', FILTER_SANITIZE_STRING );

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'pronamic_pay_save_form_options' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// OK, its safe for us to save the data now.
		$definition = array(
			// General.
			'_pronamic_payment_form_config_id'      => FILTER_SANITIZE_NUMBER_INT,
			'_pronamic_payment_form_button_text'    => FILTER_SANITIZE_STRING,
			'_pronamic_payment_form_amount_method'  => FILTER_SANITIZE_STRING,
			'_pronamic_payment_form_amount_choices' => array(
				'flags' => FILTER_REQUIRE_ARRAY,
			),
		);

		$data = filter_input_array( INPUT_POST, $definition );

		// Convert amount choices to cents.
		if ( isset( $data['_pronamic_payment_form_amount_choices'] ) ) {
			foreach ( $data['_pronamic_payment_form_amount_choices'] as $i => $amount ) {
				$amount = \Pronamic\WordPress\Pay\Core\Util::string_to_amount( $amount );

				$data['_pronamic_payment_form_amount_choices'][ $i ] = \Pronamic\WordPress\Pay\Core\Util::amount_to_cents( $amount );
			}

			// Remove empty choices.
			$data['_pronamic_payment_form_amount_choices'] = array_filter( $data['_pronamic_payment_form_amount_choices'] );
		}

		// Update post meta data.
		pronamic_pay_update_post_meta_data( $post_id, $data );
	}

	/**
	 * Get shortcode of the specified form post ID.
	 *
	 * @param string $post_id Post ID.
	 * @return string
	 */
	private function get_shortcode( $post_id = null ) {
		$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

		$shortcode = sprintf( '[pronamic_payment_form id="%s"]', esc_attr( $post_id ) );

		return $shortcode;
	}

	/**
	 * Post submit box miscellaneous actions.
	 */
	public function post_submitbox_misc_actions() {
		if ( self::POST_TYPE !== get_post_type() ) {
			return false;
		}

		?>
<div class="misc-pub-section">
	<label for="pronamic-pay-shortcode"><?php esc_html_e( 'Shortcode:', 'pronamic_ideal' ); ?></label>

	<input id="pronamic-pay-shortcode" class="pronamic-pay-shortcode-input" onClick="this.setSelectionRange( 0, this.value.length )" type="text" class="shortcode-input" readonly value="<?php echo esc_attr( $this->get_shortcode() ); ?>" />
</div>
<?php
	}

	/**
	 * Get capabilities for this post type.
	 *
	 * @return array
	 */
	public static function get_capabilities() {
		return array(
			'edit_post'              => 'edit_form',
			'read_post'              => 'read_form',
			'delete_post'            => 'delete_form',
			'edit_posts'             => 'edit_forms',
			'edit_others_posts'      => 'edit_others_forms',
			'publish_posts'          => 'publish_forms',
			'read_private_posts'     => 'read_private_forms',
			'read'                   => 'read',
			'delete_posts'           => 'delete_forms',
			'delete_private_posts'   => 'delete_private_forms',
			'delete_published_posts' => 'delete_published_forms',
			'delete_others_posts'    => 'delete_others_forms',
			'edit_private_posts'     => 'edit_private_forms',
			'edit_published_posts'   => 'edit_published_forms',
			'create_posts'           => 'create_forms',
		);
	}
}
