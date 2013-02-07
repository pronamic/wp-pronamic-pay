<?php

class Pronamic_S2Member_Bridge_Settings {

    public function __construct() {

        add_action( 'admin_menu', array( $this, 'menu' ) );
        add_action( 'init', array( $this, 'save_options_page' ) );

    }

    public function menu() {
        $parent_slug = apply_filters("ws_plugin__s2member_during_add_admin_options_menu_slug", "ws-plugin--s2member-start" );

        if(apply_filters("ws_plugin__s2member_during_add_admin_options_add_divider_6", true, get_defined_vars())) /* Divider. */
            add_submenu_page($parent_slug, "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px; background:#CCCCCC;"></span>', "create_users", "#");

        add_submenu_page(
            $parent_slug,
            __( 'Pronamic iDeal Options', 'pronamic_ideal' ),
            __( 'iDeal Options', 'pronamic_ideal' ),
            "create_users",
            'pronamic-ideal-s2member-options',
            array( $this, 'view_options_page' )
        );
    }

    public function view_options_page() {

        // Generate nonce field
        $nonce = wp_nonce_field( 'pronamic-ideal-s2member-options', 'pronamic-ideal-s2member-options-nonce', true, false );

        // Get all configurations
        $configurations = Pronamic_WordPress_IDeal_IDeal::get_configurations_select_options();

        // Get existing options
        $pronamic_ideal_s2member_enabled                = get_option( 'pronamic_ideal_s2member_enabled' );
        $pronamic_ideal_s2member_chosen_configuration   = get_option( 'pronamic_ideal_s2member_chosen_configuration' );
        ?>

        <div class="wrap">
            <?php screen_icon(); ?>
            <h2><?php echo get_admin_page_title(); ?></h2>
            <form action="" method="POST">
                <?php echo $nonce; ?>
                <table class="form-table">
                    <tr>
                        <th><?php _e( 'Enable/Disable', 'pronamic_ideal' ); ?></th>
                        <td>
                            <input type="checkbox" name="pronamic_ideal_s2member_enabled" value="1" <?php if ( $pronamic_ideal_s2member_enabled == 1 ) : ?> checked="checked" <?php endif;?> />
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e( 'Configuration', 'pronamic_ideal' ); ?></th>
                        <td>
                            <select name="pronamic_ideal_s2member_chosen_configuration">
                                <?php foreach ( $configurations as $value => $name ) : ?>
                                    <?php if ( $value == $pronamic_ideal_s2member_chosen_configuration ) : ?>
                                        <option value="<?php echo $value; ?>" selected="selected"><?php echo $name; ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo $value; ?>"><?php echo $name; ?></option>
                                    <?php endif;?>
                                    
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

        if ( ! isset( $_POST['pronamic-ideal-s2member-options-nonce'] ) )
            return;

        if ( ! wp_verify_nonce( $_POST['pronamic-ideal-s2member-options-nonce'], 'pronamic-ideal-s2member-options') )
            return;

        // Clean options
        $pronamic_ideal_s2member_enabled = filter_input( INPUT_POST, 'pronamic_ideal_s2member_enabled', FILTER_VALIDATE_INT );
        $pronamic_ideal_s2member_chosen_configuration = filter_input( INPUT_POST, 'pronamic_ideal_s2member_chosen_configuration', FILTER_VALIDATE_INT );

        // Save options
        update_option( 'pronamic_ideal_s2member_enabled', $pronamic_ideal_s2member_enabled );
        update_option( 'pronamic_ideal_s2member_chosen_configuration', $pronamic_ideal_s2member_chosen_configuration );

        return;
    }
}