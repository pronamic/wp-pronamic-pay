=== Pronamic iDEAL ===
Contributors: pronamic, remcotolsma 
Tags: ideal, rabobank, friesland bank, bank, payment, gravity forms, gravity, forms, form, payment, ideal
Requires at least: 3.0
Tested up to: 3.0
Stable tag: 1.1

Integrates iDEAL in to WordPress

== Description ==

...


== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your 
WordPress installation and then activate the Plugin from Plugins page.


== Screenshots ==



== Changelog ==

= ? =
*	Issuers list transient is now deleted after updating an iDEAL configuration
*	Added the issuers list to the iDEAL configuration tests page
*	The Gravity Forms iDEAL Issuer Drop Down remembers 
*	Description and entrance code are automatically truncated on the maximum length
*	Added better checks on the output of the OpenSSL functions to prefend PHP warnings
*	Use the generic hash() function instead of the md5() and sha1() functions

= 1.1 =
*	Added transaction description to the GravityForms iDEAL feed
*	Fixed bug with retrieving feeds with no form or configuration attached (inner join - left join)
*	Fixed issue with saving new iDEAL configurations and GravityForms iDEAL feeds
*	Added wp_nonce_field() and check_admin_referer() functions to the forms to avoid security exploits
*	Improved the feeds repository and the feed model

= 1.0 =
*	Initial release


== Links ==

*	[Pronamic](http://pronamic.eu/)
*	[Remco Tolsma](http://remcotolsma.nl/)
*	[Online styleguide van iDEAL](http://huisstijl.idealdesk.com/) 
*	[iDEAL Professional - SSL Certificaten](http://www.ideal-simulator.nl/ideal-professional-ssl-certificaten.html)
*	[Markdown's Syntax Documentation][markdown syntax]

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
		"Markdown is what the parser uses to process much of the readme file"