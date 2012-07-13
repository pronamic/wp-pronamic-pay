=== Pronamic iDEAL ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, gravity, forms, form, payment, woocommerce, woothemes, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart, classipress, appthemes
Donate link: http://pronamic.eu/donate/?for=wp-plugin-pronamic-ideal&source=wp-plugin-readme-txt
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: beta-0.10.1

The Pronamic iDEAL plugin allows you to easily offer the iDEAL payment method within your 
WordPress website.

== Description ==

*	Easy installation and configuration
*	Automatic updates

= Gravity Forms =

The Pronamic iDEAL plugin contains the IDEAL Add-On for the 
[Gravity Forms plugin](http://www.gravityforms.com/) which allows 
you to add the iDEAL payment method to your Gravity Forms.

= WooCommerce =

The Pronamic iDEAL plugin contains the iDEAL payment gateway for the 
[WooCommerce e-commerce plugin](http://wordpress.org/extend/plugins/woocommerce/) 
from [WooThemes](http://www.woothemes.com/).

= Jigoshop =

The Pronamic iDEAL plugin contains the iDEAL payment gateway for the 
[Jigoshop e-commerce plugin](http://wordpress.org/extend/plugins/jigoshop/) 
from [Jigowatt](http://jigowatt.co.uk/).

= WP e-Commerce =

The Pronamic iDEAL plugin contains the iDEAL payment gateway for the 
[WP e-Commerce plugin](http://wordpress.org/extend/plugins/wp-e-commerce/) 
from [getShopped.org](http://getshopped.org/).

= Shopp =

The Pronamic iDEAL plugin contains the iDEAL payment gateway for the 
[Shopp plugin](http://shopplugin.net/).

= ClassiPress =

The Pronamic iDEAL plugin contains the iDEAL payment gateway for the 
[ClassiPress theme](http://www.appthemes.com/themes/classipress/).

= Event Espresso =

The Pronamic iDEAL plugin contains the iDEAL payment gateway for the 
[Event Espresso plugin](http://eventespresso.com/).

= Banks and variants =

*	ABN AMRO
	*	iDEAL Easy
	*	iDEAL Only Kassa
	*	iDEAL Internetkassa
	*	iDEAL Hosted
	*	iDEAL Zelfbouw (zonder kassa)
*	Fortis Bank
	*	iDEAL Hosted
	*	iDEAL Internet Kassa (NEOS Solutions)
	*	iDEAL Integrated
*	Friesland Bank
	*	iDEAL Zakelijk
	*	iDEAL Zakelijk Plus
*	iDEAL Simulator
	*	iDEAL Lite / Basic
	*	iDEAL Professional / Advanced / Zelfbouw
*	ING
	*	iDEAL Basic
	*	iDEAL Internet Kassa (The Way You Pay (TWYP))
	*	iDEAL Advanced
*	Mollie
	*	iDEAL Basic
	*	iDEAL Advanced
*	Ogone
	*	Ogone
*	Rabobank
	*	iDEAL Lite
	*	iDEAL Internetkassa
	*	OmniKassa
	*	iDEAL  Professional
*	Sisow
	*	iDEAL Basic
	*	iDEAL Advanced

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


== Are there any known plugin conflicts? ==

Unfortunately WordPress is notorious for conflicts between themes and plugins. It is unavoidable as you have no control over what other plugins and themes do. While we do take steps to avoid conflicts as best we can, we have no control over other plugins or themes.

As conflicts are found we will update this list. If you discover a conflict with a another plugin, please notify us.

Here is a list of known plugin conflicts:

*	**WordPress HTTPS**
	The WordPress HTTPS can conflict with the OmniKassa payment method. It can 
	cause invalid signature errors. The WordPress HTTPS plugin parses the complete 
	output of an WordPress website and changes 'http' URLs to 'https' URLs, this 
	results in OmniKassa data that no longer matches the signature.


== Todo ==
*	Fix the pagination while searching payments 
*	Check the Mollie tests for the lite variant
*	Gravity Forms bind fields for iDEAL Easy
*	Add bank images and options to show issuer list with radiobuttons
*	No-index by default enabled in page generator, check third party SEO plugins
*	Status change payment e-mail notification in request of
	*	Henk Valk - YH Webdesign <webdesign@yourhosting.nl>
	*	Jan Egbert Krikken - Eisma Media Groep <j.krikken@eisma.nl>
*	Let visitors pay later
*	Make simulator payments free
*	MultiSafePay - http://www.multisafepay.com/
*	TargetPay.com - https://www.targetpay.com/
*	http://www.buckaroo.nl/zakelijk/partners/plugins.aspx
*	IcePay
*	Do something with no configuratin selected in WooCommerce, show error on checkout
*	Maybe add support for [Fast Secure Contact Form](http://wordpress.org/extend/plugins/si-contact-form/)
	*	Also see the topic wordpress.org/support/topic/plugin-pronamic-ideal-ideal-by-mollie-for-gravity-forms
*	Check the IDEAL feed for Gravity Forms in combination with an redirection to URL or page confirmation
*	Add the option to use Mollie profile keys
*	Check Array string bug with Mollie and an subId and certificates 


== Changelog ==

= beta-0.10.1 =
*	Fix - OmniKassa configuration could net input hash key 

= beta-0.10 =
*	Tweak - Added extra check in loading certificates files from the iDEAL XML file
*	Shopp - Fix - Purchases with discount payments fix
*	Tweak - Added ABN AMRO Bank : Parameter Cookbook documentation link
*	Feature - Added support for the iDEAL Internet Kassa of Ogone

= beta-0.9.9 =
*	Shopp - Test - Checked the 1.2.2 changelog (https://shopplugin.net/blog/shopp-1-2-1-release-notes/)
*	Shopp - Fix - Shopp is not showing the new payments settings after saving, bug in Shopp
*	Tweak - Changed home_url() to site_url() for retrieving license information
*	Event Espresso - Tweak - Improved the documentation of some functions and constants
*	Event Espresso - Fix - Removed debug information from choose payment option page 
*	Jigoshop - Tweak - Return visitor to view order page after expired payment
*	WP e-Commerce - Test - Version 3.8.8.2 and 3.8.8.3
*	WP e-Commerce - Tweak - Improved the return, cancel, success and error URL's
*	Shopp - Tweak - Improved the return, cancel, success and error URL's
*	Shopp - Tweak - Improved the automatic status update of purchases
*	WordPress - Test - Version 3.4.1

= beta-0.9.8 =
*	Shopp - Fix - The 'selected' parameter in module settings UI render drop-down menu function is sometimes type sensitive
*	Shopp - Fix - Added wrapper code to JavaScript so $ will work for calling jQuery
*	Event Espresso - Added support for the Event Espresso plugin

= beta-0.9.7 =
*	OmniKassa - Added the "Zo werkt het aanvragen en aansluiten van de Rabo OmniKassa" PDF file to the documentation page
*	OmniKassa - Added an easy interface to execute the five iDEAL simulation transactions
*	Jigoshop - Updated the HTML options table head of the IDEAL gateway to the default Jigoshop format
*	iDEAL Advanced - Fixed an issue with an empty WordPress WPLANG constant, causing field generating error: language. Parameter '' has less than 2 characters
*	Sisow - Added the "Sisow - Pronamic iDEAL" PDF file to the documentation page
*	Gravity Forms - Improved the determination of the status URL
*	Sisow - Added support for "Sisow - iDEAL Basic" variant
*	Sisow - Added support for "Sisow - iDEAL Advanced" variant
*	Gravity Forms - Display error code if somehting is going wrong with the iDEAL Advanced variant
*	Shopp - Added 'keyed' => true parameter to the module settings UI render drop-down menu function
*	Tweak - Removed the utility function remove query arguments from URL, no longer used
*	Tweak - Improved the utility class and the retrieval of the ISO 639 and ISO 3166 values
*	Tweak - Improved the iDEAL Basic test page, the WordPress language value is now used 
*	Gravity Forms - Fix - Removed the esc_js() function from the AJAX redirection JavaScript function

= beta-0.9.6 =
*	Gravity Forms - Fixed bug with hidden conditional field, ignore iDEAL

= beta-0.9.5 =
*	Gravity Forms - Added extra constants for the payment statuses
*	Gravity Forms - Improved the way we update the entry payment status
*	WooCommerce - Added WooCommerce utility base class
*	Jigoshop - Added Jigoshop utility base class
*	OmniKassa - Changed the use of the site_url() function, now called with an slash

= beta-0.9.4 =
*	Fixed issue with the use of the [dbDelta](http://codex.wordpress.org/Creating_Tables_with_Plugins) function wich was causing "WordPress database error: [Multiple primary key defined]" errors
*	Fixed check on (un)paid Shopp 1.2+ purchases, in some way this was mixed up.
*	Gravity Forms - Added field type title for the issuer drop down field
*	Gravity Forms - Changed Dutch translation of "Issuer Drop Down" field to "Banken uitschuifkeuzelijst"
*	Gravity Forms - Fixed redirecting issue with an AJAX enabled Gravity Forms form
*	ClassiPress - Added experimental iDEAL gateway

= beta-0.9.3 =
*	Added some icons for the iDEAL banks to create a nicer issuer select element
*	Added exprimental support for ClassiPress
*	Added Gravity Forms delay admin notification option
*	Added Gravity Forms delay user notification option
*	Added Gravity Forms delay post creation option

= beta-0.9.2 =
*	Fixed line delimters wich was causing unexpected T_CLASS error in classes/Pronamic/WPeCommerce/IDeal/AddOn.php 
*	Added an array_filter() to the optional OmniKassa data fields
*	Fixed an issue in the Shopp Add-On caused by an Shopp bug (see ticket https://shopp.lighthouseapp.com/projects/47561/tickets/1536-shoppcheckoutoffline-instructions-tag-seemingly-absent-from-12)
*	Added support plugins section to the settings page

= beta-0.9.1 =
*	Added an overview of the registered hashing algorithms and check for sha1 algorithm
*	Fixed plugins_url() function call in the WP e-Commerce Add-On
*	Fixed retrieving succes URL in the Gravity Forms Add-On from an iDEAL feed 
*	Fixed edit order link in check iDEAL payment e-mail in the WooCommerce Add-On
*	Added check for unavailable or removed iDEAL variant in Gravity Forms iDEAL feed editor

= beta-0.9 =
*	Added an data proxy class, all add-ons are now optimized
*	Added OmniKassa support for all add-ons
*	Added support for the WP e-Commerce plugin
*	Improved the redirection if returned from an iDEAL advanced payment to WooComnmerce
*	Changed the text domain from 'pronamic-ideal' to 'pronamic_ideal'
*	Replaced all references to class constant TEXT_DOMAIN to an string

= beta-0.8.6 =
*	Added documentation for the Rabobank OmniKassa payment method
*	Added documentation for the ABN AMRO iDEAL Only Kassa payment method
*	WooCommerce iDEAL payment orders now get the status "on-hold" instead of "pending" 
*	Changed WooCommerce class 'woocommerce_order' to 'WC_Order'
*	Changed WooCommerce class 'woocommerce_payment_gateway' to 'WC_Payment_Gateway'
*	Replaced get_permalink(get_option('woocommerce_pay_page_id')) with get_permalink(woocommerce_get_page_id('pay'))
*	WooCommerce iDEAL Easy and Basic payments send an check payment mail and add note to order

= beta-0.8.5 =
*	Fixed an nonce check wich was causing a lot "Are you sure you want to do this?" notices
*	Added an uninstall hook wich will delete all extra database tables and options
*	Removed the custom made uninstall block from the iDEAL configurations page
*	Fixed database errors / unexpected output while installing the plugin
*	Pages generator will now generate pages with comments closed by default

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

