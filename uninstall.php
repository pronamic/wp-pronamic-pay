<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

//////////////////////////////////////////////////
// Delete tables
//////////////////////////////////////////////////

$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pronamic_ideal_configurations" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pronamic_ideal_payments" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rg_ideal_feeds" );

//////////////////////////////////////////////////
// Delete posts
//////////////////////////////////////////////////

$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'pronamic_gateway';" );
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'pronamic_payment';" );
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'pronamic_pay_gf';" );

$wpdb->query( 'DELETE FROM wp_postmeta WHERE post_id NOT IN ( SELECT ID FROM wp_posts );' );

//////////////////////////////////////////////////
// Delete general options
//////////////////////////////////////////////////

delete_option( 'pronamic_pay_version' );
delete_option( 'pronamic_pay_db_version' );

//////////////////////////////////////////////////
// Delete exntesion options
//////////////////////////////////////////////////

// Event Espresso
delete_option( 'pronamic_pay_ideal_event_espreso_config_id' );

// Gravity Forms
// There are options for Gravity Forms

// Jigoshop
// Set default Jigoshop variables, load them form the WordPress options
delete_option( 'pronamic_pay_ideal_jigoshop_enabled' );
delete_option( 'pronamic_pay_ideal_jigoshop_title' );
delete_option( 'pronamic_pay_ideal_jigoshop_description' );
delete_option( 'pronamic_pay_ideal_jigoshop_config_id' );

// Membership
delete_option( 'pronamic_pay_ideal_membership_config_id' );

// s2Member®
delete_option( 'pronamic_pay_ideal_s2member_config_id' );

// Shopp
// Shopp options are stored in the Shopp meta table, we don't touch this

// WooCommerce
delete_option( 'woocommerce_pronamic_pay_ideal_settings' );

// WP e-Commerce
delete_option( 'pronamic_pay_ideal_wpsc_config_id' );

// ClassiPress
delete_option( '' );

// JobRoller
delete_option( '' );

//////////////////////////////////////////////////
// Delete legacy options
//////////////////////////////////////////////////

// General
delete_option( 'pronamic_ideal_version' );
delete_option( 'pronamic_ideal_key' );
delete_option( 'gf_ideal_version' );

// Event Espresso
delete_option( 'pronamic_ideal_event_espresso_configuration_id' );

// Jigoshop
delete_option( 'jigoshop_pronamic_ideal_enabled' );
delete_option( 'jigoshop_pronamic_ideal_title' );
delete_option( 'jigoshop_pronamic_ideal_description' );
delete_option( 'jigoshop_pronamic_ideal_configuration_id' );

// Membership
delete_option( 'pronamic_ideal_membership_enabled' );
delete_option( 'pronamic_ideal_membership_chosen_configuration' );

// s2Member®
delete_option( 'pronamic_ideal_s2member_enabled' );
delete_option( 'pronamic_ideal_s2member_chosen_configuration' );

// WooCommerce
delete_option( 'woocommerce_pronamic_ideal_settings' );

// WP e-Commerce
delete_option( 'pronamic_ideal_wpsc_configuration_id' );
