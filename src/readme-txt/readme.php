<?php

header( 'Content-Type: text/plain' );

$data = file_get_contents( __DIR__ . '/../../package.json' );
$pkg  = json_decode( $data );

?>
=== Pronamic iDEAL ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, gravity, forms, form, payment, woocommerce, woothemes, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart, classipress, appthemes
Donate link: http://www.pronamic.eu/donate/?for=wp-plugin-pronamic-ideal&source=wp-plugin-readme-txt
Requires at least: 3.6
Tested up to: 4.3.1
Stable tag: <?php echo $pkg->version, "\r\n"; ?>

<?php include __DIR__ . '/../general/description-short.php'; ?>


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

<?php include __DIR__ . '/../general/links.php'; ?>


== Pronamic plugins ==

<?php include __DIR__ . '/../general/plugins.php'; ?>

