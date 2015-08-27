<?php

header( 'Content-Type: text/plain' );

$data   = file_get_contents( __DIR__ . '/../plugin.json' );
$plugin = json_decode( $data );

?>
=== <?php echo $plugin->name; ?> ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, gravity, forms, form, payment, woocommerce, woothemes, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart, classipress, appthemes
Donate link: http://www.pronamic.eu/donate/?for=wp-plugin-pronamic-ideal&source=wp-plugin-readme-txt
Requires at least: 3.6
Tested up to: 4.2.2
Stable tag: <?php echo $plugin->version; ?>

<?php include 'description-short.php'; ?>

== Description ==

<?php include 'description-long.php'; ?>


== Installation ==

<?php include 'installation.php'; ?>


== Screenshots ==

<?php include 'screenshots.php'; ?>


<?php include 'other-notes.php'; ?>


== Changelog ==

<?php include 'changelog.php'; ?>


== Links ==

<?php include 'links.php'; ?>


== Pronamic plugins ==

<?php include 'plugins.php'; ?>

