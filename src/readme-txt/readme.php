<?php

header( 'Content-Type: text/plain' );

$data = file_get_contents( __DIR__ . '/../../package.json' );
$pkg  = json_decode( $data );

?>
=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, gravity, forms, form, payment, woocommerce, woothemes, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart, classipress, appthemes
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-ideal&source=wp-plugin-readme-txt
Requires at least: 4.7
Tested up to: 4.9
Requires PHP: 5.3
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

