=== Pronamic iDEAL ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, gravity, forms, form, payment, rabobank, friesland bank, ing, mollie
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: beta-0.6.1

The Pronamic iDEAL plugin allows you to easily offer the iDEAL payment method within your 
WordPress website.

== Description ==

*	Easy installation and configuration
*	Automatic updates

= Gravity Forms =

The Pronamic iDEAL plugin is an Add-On for the Gravity Forms form builder which allows you
to add the iDEAL payment method to your Gravity Forms.

= Banks and variants =

*	Friesland Bank
	*	iDEAL Zakelijk
	*	iDEAL Zakelijk Plus
*	ING
	*	iDEAL Basic
	*	iDEAL Advanced
*	Rabobank
	*	iDEAL Lite
	*	iDEAL Professional
*	Mollie
	*	iDEAL Lite/Basic
	*	iDEAL Professional/Advanced
*	iDEAL Simulator
	*	iDEAL Lite / Basic
	*	iDEAL Professional / Advanced / Zelfbouw

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your 
WordPress installation and then activate the Plugin from Plugins page.


== Screenshots ==

1.	Configuration edit page
2.	Gravity Forms form edit page(iDEAL advanced)
3.	Gravity Forms form edit page(iDEAL advanced)
4.	Gravity Forms feed edit page
5.	Feeds overview page
6.	Payments overview page
7.	Gravity Forms form on site with iDEAL feed
8.	Gravity Forms frontend issuer drop down

== Changelog ==

= beta-0.6.1
*	Improved the calculation of the Gravity Forms price fields and the total amount 

= beta-0.6 =
*	Added and activated some extra iDEAL banks / variants
*	Enabled the Transient API for retrieving the issuers lists, was temporary disabled for debugging

= beta-0.5 =
*	Fixed some security issues

= beta-0.4 =
*	Improved the retrieving of license information from the license provider
*	Added some extra admin CSS styling like an iDEAL screen icon

= beta-0.3 =
*	Improved the Gravity Forms confirmation message when an iDEAL basic payment is executed, now working correct with AJAX
*	Improved the iDEAL configuration editor and the Gravity Forms iDEAL feed editor, display only necessary input fields
*	Fixed the Rabobank iDEAL payment server URLs in the ideal.xml configuration file

= beta-0.2 =
*	Removed all PHP 5.3+ namespace declarations, the plugin should now be compatible with PHP 5.2+
*	Fixed the link to the payment detail page in WordPress admin
*	Fixed the link on the payment detail page to the iDEAL configuration page
*	Fixed redirection problem with AJAX enabled Gravity Forms  

= beta-0.1 =
*	Issuers list transient is now deleted after updating an iDEAL configuration
*	Added the issuers list to the iDEAL configuration tests page
*	The Gravity Forms iDEAL Issuer Drop Down remembers 
*	Description and entrance code are automatically truncated on the maximum length
*	Added better checks on the output of the OpenSSL functions to prefend PHP warnings
*	Use the generic hash() function instead of the md5() and sha1() functions
*	Added transaction description to the GravityForms iDEAL feed
*	Fixed bug with retrieving feeds with no form or configuration attached (inner join - left join)
*	Fixed issue with saving new iDEAL configurations and GravityForms iDEAL feeds
*	Added wp_nonce_field() and check_admin_referer() functions to the forms to avoid security exploits
*	Improved the feeds repository and the feed model
*	Initial release


== Links ==

*	[Pronamic](http://pronamic.eu/)
*	[Remco Tolsma](http://remcotolsma.nl/)
*	[Online styleguide van iDEAL](http://huisstijl.idealdesk.com/) 
*	[iDEAL Professional - SSL Certificaten](http://www.ideal-simulator.nl/ideal-professional-ssl-certificaten.html)
*	[Markdown's Syntax Documentation][markdown syntax]

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
		"Markdown is what the parser uses to process much of the readme file"


== Pronamic plugins ==

*	[Pronamic Google Maps](http://wordpress.org/extend/plugins/pronamic-google-maps/)
*	[Gravity Forms (nl)](http://wordpress.org/extend/plugins/gravityforms-nl/)
*	[Pronamic Page Widget](http://wordpress.org/extend/plugins/pronamic-page-widget/)
*	[Pronamic Page Teasers](http://wordpress.org/extend/plugins/pronamic-page-teasers/)
*	[Maildit](http://wordpress.org/extend/plugins/maildit/)
*	[Pronamic Framework](http://wordpress.org/extend/plugins/pronamic-framework/)
*	[Pronamic iDEAL](http://wordpress.org/extend/plugins/pronamic-ideal/)

