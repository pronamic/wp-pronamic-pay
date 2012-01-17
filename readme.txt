=== Pronamic iDEAL ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, gravity, forms, form, payment, woocommerce, woothemes, shopp, rabobank, friesland bank, ing, mollie
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: beta-0.8.3

The Pronamic iDEAL plugin allows you to easily offer the iDEAL payment method within your 
WordPress website.

== Description ==

*	Easy installation and configuration
*	Automatic updates

= Gravity Forms =

The Pronamic iDEAL plugin is an Add-On for the Gravity Forms form builder which allows you
to add the iDEAL payment method to your Gravity Forms.

= WooCommerce =

The Pronamic iDEAL plugin is an payment gateway for the [WooCommerce e-commerce plugin](http://wordpress.org/extend/plugins/woocommerce/).

= Jigoshop =

The Pronamic iDEAL plugin is an payment gateway for the [Jigoshop e-commerce plugin](http://wordpress.org/extend/plugins/jigoshop/).

= Shopp =

The Pronamic iDEAL plugin is an payment gateway for the [Shopp plugin](http://shopplugin.net/).

= Banks and variants =

*	ANB AMRO
	*	iDEAL Easy
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
2.	WooCommerce - Settings - Payment Gateways
3.	WooCommerce - Wootique theme - Checkout
4.	Gravity Forms form edit page(iDEAL advanced)
5.	Gravity Forms form edit page(iDEAL advanced)
6.	Gravity Forms feed edit page
7.	Feeds overview page
8.	Payments overview page
9.	Gravity Forms form on site with iDEAL feed
10.	Gravity Forms frontend issuer drop down


== Changelog ==

= todo =
*	Fix the pagination while searching payments 
*	Check the Mollie tests for the lite variant
*	Gravity Forms bind fields for iDEAL Easy
*	Add bank images and options to show issuer list with radiobuttons
*	Add notice like: "Gebruiker is doorgestuurd naar iDEAL. Controleer de status van de transactie via het iDEAL Dashboard voordat de levering plaatsvindt."
*	Add notice like: "Gebruiker is succesvol terug na iDEAL"
*	Comments and no-index by default disable in page generator
*	Status change payment e-mail notification in request of
	*	Henk Valk - YH Webdesign <webdesign@yourhosting.nl>
	*	Jan Egbert Krikken - Eisma Media Groep <j.krikken@eisma.nl>

= beta-0.8.4 =
*	Shopp - Fixed fatal error in Shopp 1.2RC1, the $registry variable in the Settings class is declared as private in version 1.2RC1
*	Gravity Forms - Added link to Gravity Forms entry / lead details on the iDEAL payments page
*	Shopp - Improved the not paid check for the Shopp iDEAL gateway module, now also works with 1.2+
*	Shopp - Changed function for store front JavaScript from 'sanitize_title_with_dashes' to 'sanitize_key'
*	Shopp - Changed the 'shopp_order_success' action functions, in 1.2+ the purchase parameter was removed
*	Fixed notice and bug while generating security certificates and keys
*	Added mandatory tests for the iDEAL advanced variants

= beta-0.8.3 =
*	Fixed notice wp_register_style was called incorrectly in admin
*	Fixed notice undefined variable: nl2br in the Gravity Forms Add-On
*	Fixed issue in WooCommerce with building the iDEAL basic succes URL, Google Analytics e-commerce tracking was not possible
*	Fixed issue with the purchase ID passing through to iDEAL
*	Added extra description to the hash key field on the iDEAL configuration edit page 
*	Removed the maxlength="32" attribute from the Gravity Forms iDEAL feed transaction description field
*	Now it is also possible to search on the amount of an payment
*	Moved the return from iDEAL routine form the 'parse_query' routine to the 'template_redirect' routine
*	Improved the 'pronamic_ideal_return' and 'pronamic_ideal_status_update' routines with an 'can redirect' paramter
*	Improved the status update of WooCommerce and Jigoshop orders after an failure status update (from expired to failed)
*	Improved the scheduling of status requests of iDEAL advanced payments
*	Fixed the notice if no status pages or URL's are configured in an Gravity Forms iDEAL feed

= beta-0.8.2 =
*	Replaced the DateTime::getTimestamp() call (PHP 5 >= 5.3.0) with DateTime::format('U') (PHP 5 >= 5.2.0)
*	Removed the addItem function from the iDEAL basic class and added an extra items class
*	Improved the way we load iDEAL items in the Gravity Forms iDEAL Add-On
*	Improved the Shopp add_storefrontjs script to hide / show the iDEAL fields
*	Added the ABN AMRO iDEAL Easy variant
*	Improved and fixed the WooCommerce iDEAL gateway, the status of orders is now set to pending iDEAL payment
*	Moved all the documentation files to an external server, plugin is now much smaller (from 20 MB to 3 MB)

= beta-0.8.1 =
*	Fixed an issue with the WooCommerce iDEAL gateway, the order status is now updated
*	Improved the payment status update of the Shopp and Gravity Forms add-ons

= beta-0.8 =
*	Fixed an issue with the success, cancel and error URL's in the iDEAL lite variant gateway for WooCommerce
*	Added support for the [Shopp plugin](http://shopplugin.net/)
*	Added search box on the payments page so you can search on transaction ID, consumer name, account number and city
*	Adjusted the default expiration date modifier from +1 hour to +30 minutes

= beta-0.7.2 =
*	Fixed an issue with character set in the iDEAL lite variant with the decoding of HTML entities
*	Added the current time to the iDEAL settings page so users can easily check the server time
*	Improved the expiration date of the iDEAL lite variant, now uses UTC timezone
*	Changed the iDEAL date format, the Z stands for the timezone offset and should not be the Z character
*	Changed the database column width of the entrance code to 40 instead of 32
*	For WooCommerce iDEAL Lite payment we now only add one total item, otherwise the cart discount amount is an issue

= beta-0.7.1 =
*	Fixed issue with loading JavaScripts on the Gravity Forms edit / new form page
*	Added some extra data length checks for iDEAL lite payments
*	Added an extra role iDEAL Administrator, so you can outsource the configuration of iDEAL
*	Added extra capabilities so you can easily grant users to the iDEAL admin pages
*	Fixed bug with ordering the iDEAL payment by date
*	Added an pages generator to easily create pages for each iDEAL payment status

= beta-0.7 =
*	Added support for the WooCommerce WordPress plugin
*	Improved the payments repository class
*	Improved the payments overview page (now with pagination)
*	Improved the loading of the admin JavaScripts and stylesheets

= beta-0.6.2 =
*	Gravity Forms iDEAL Lite button is now translatable
*	For iDEAL Lite you can now easily run the mandatory tests
*	Added an private key and certificate generator

= beta-0.6.1 =
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

