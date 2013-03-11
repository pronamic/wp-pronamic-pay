<?php

class Pronamic_Membership_Bridge_Settings {

	public function __construct() {
		add_action( 'init', array( $this, 'save_options_page' ) );

		add_action( 'membership_add_menu_items_after_gateways', array( $this, 'menu_item' ) );
	}

	public function menu_item() {
		add_submenu_page(
			'membership',
			__( 'Pronamic iDEAL Options', 'pronamic_ideal' ),
			__( 'iDEAL Options', 'pronamic_ideal' ),
			'membershipadmin',
			'pronamic-ideal-membership-options',
			array( $this, 'view_options_page' )
		);
	}

	public function view_options_page() {
		$nonce = wp_nonce_field( 'pronamic-ideal-membership-options', 'pronamic-ideal-membership-options-nonce', true, false );

		$g = get_option( 'membership_activated_gateways' );
		var_dump($g);

		$configurations = Pronamic_WordPress_IDeal_IDeal::get_configurations_select_options();

		$pronamic_ideal_membership_enabled = get_option( 'pronamic_ideal_membership_enabled' );
		$pronamic_ideal_membership_chosen_configuration = get_option( 'pronamic_ideal_membership_chosen_configuration' );

		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php echo get_admin_page_title(); ?></h2>
			<form action="" method="POST">
				<?php echo $nonce; ?>
				<table class="form-table">
					<tr>
						<th><?php _e( 'Enabled/Disable', 'pronamic_ideal' ); ?></th>
						<td>
							<input type="checkbox" name="pronamic_ideal_membership_enabled" value="1" <?php checked( $pronamic_ideal_membership_enabled, 1 ); ?> />
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Configuration', 'pronamic_ideal' ); ?></th>
						<td>
							<select name="pronamic_ideal_membership_chosen_configuration">
								<?php foreach ( $configurations as $value => $name ) : ?>
									<option value='<?php echo $value; ?>' <?php selected( $pronamic_ideal_membership_chosen_configuration, $value ); ?>><?php echo $name; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php

	}

	public function save_options_page() {
		if ( ! isset( $_POST['pronamic-ideal-membership-options-nonce' ] ) )
			return;

		if ( ! wp_verify_nonce( $_POST['pronamic-ideal-membership-options-nonce'], 'pronamic-ideal-membership-options' ) )
			return;

		$pronamic_ideal_membership_enabled = filter_input( INPUT_POST, 'pronamic_ideal_membership_enabled' );
		$pronamic_ideal_membership_chosen_configuration = filter_input( INPUT_POST, 'pronamic_ideal_membership_chosen_configuration' );

		update_option( 'pronamic_ideal_membership_enabled', $pronamic_ideal_membership_enabled );
		update_option( 'pronamic_ideal_membership_chosen_configuration', $pronamic_ideal_membership_chosen_configuration );

		// Bridge settings
		$active_gateways = get_option( 'membership_activated_gateways', array() );

		if ( 1 == $pronamic_ideal_membership_enabled ) {
			$active_gateways[] = 'ideal';
		} else {
			$found = array_search( 'ideal', $active_gateways );
			if ( false !== $found ) {
				unset( $active_gateways[$found] );
			}
		}
		
		update_option( 'membership_activated_gateways', array_unique( $active_gateways ) );
	}
}
