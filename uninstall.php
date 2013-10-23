<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

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

$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN ( SELECT ID FROM wp_posts );" );

//////////////////////////////////////////////////
// Delete general options
//////////////////////////////////////////////////



//////////////////////////////////////////////////
// Delete exntesion options
//////////////////////////////////////////////////

// Event Espresso 
delete_option( '' );

// Gravity Forms
// There are options for Gravity Forms

// Jigoshop 
delete_option( '' );

// Membership 
delete_option( '' );

// s2Member® 
delete_option( '' );

// Shopp
// Shopp options are stored in the Shopp meta table, we don't touch this

// WooCommerce
delete_option( '' );

// WP e-Commerce
delete_option( '' );

// ClassiPress
delete_option( '' );

// JobRoller
delete_option( '' );

//////////////////////////////////////////////////
// Delete legacy options
//////////////////////////////////////////////////

delete_option( 'pronamic_ideal_version' );
delete_option( 'pronamic_ideal_db_version' );
delete_option( 'pronamic_ideal_key' );
delete_option( 'gf_ideal_version' );
