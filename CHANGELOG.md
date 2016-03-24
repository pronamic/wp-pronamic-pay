# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased][unreleased]

## [4.0.0] - 2016-03-24

### Added
- Added experimental support for Give.
- Added tabs to the gateway configration edit page.
- Added return URL to admin payment detail page.
- Added redirect URL to admin payment detail page.

### Changed
- Updated WordPress pay Gravity Forms library to version 1.4.5.
  - Added support for merge tag 'pronamic_payment_id'.
  - Added ability to use Gravity Forms confirmations (with merge tag support) as payment status page.
- Updated WordPress pay Membership library to version 1.0.4.
  - Always use Membership gateway live mode.
  - Display gateway error messages.
  - Added support for issuer input field.
  - Added support for button image URL and description to iDEAL gateway.
- Updated WordPress pay Jigoshop library to version 1.0.2.
  - Removed status code from redirect in update_status.
- Updated WordPress pay Jigoshop library to version 1.0.1.
  - Removed status code from redirect in status_update.
- Updated WordPress pay WooCommerce library to version 1.1.7.
  - Redirect to payment options instead of 'Order received' if payment is not yet completed.
  - Implemented new payment redirect URL filter.
  - Use the global default config as the WooCommerce default config.
- Updated WordPress pay Formidable Forms library to version 1.0.1.
  - Added support for transaction description.
- Updated WordPress pay Easy Digital Downloads library to version 1.2.3.
  - Tested Easy Digital Downloads version 2.5.9.
  - Set global WordPress gateway config as default config in gateways.
  - Use new redirect URL filter.
  - Return to checkout if there is no gateway found.
- Updated WordPress pay Charitable library to version 1.0.3.
  - Changed the default return URL to the campaign URL.
  - Use new redirect URL filter.
- Updated WordPress pay OmniKassa library to version 1.1.7.
  - Added advanced 'Order ID' setting.
- Updated WordPress pay ING Kassa Compleet library to version 1.0.3.
  - Added webhook listener.
  - Added scheduled events to check transaction status.
- Updated WordPress pay Buckaroo library to version 1.2.3.
  - Updated gateway settings and add support for 'brq_excludedservices' parameter.
  - Added advanced setting for 'brq_invoicenumber' parameter.
- Updated WordPress pay Pay.nl library to version 1.1.4.
  - Added scheduled transaction status request.
- Updated WordPress pay Ogone library to version 1.2.8.
  - Use UTF-8 URL when blog charset is UTF-8.
- Updated WordPress pay Mollie library to version 1.1.5.
  - Added support for bank transfer and direct debit payment methods.
- Updated WordPress pay iDEAL Basic library to version 1.1.4.
  - Fixed typo 'xml_notifaction' in listener parameter.
- Updated WordPress pay iDEAL Advanced v3 library to version 1.1.4.
  - Updated gateway settings, including private key and certificate generation.
  - Added error details to error message.

## [3.9.0] - 2016-03-02

### Added
- Added experimental support for Formidable Forms.
- Added support for new Qantani platform via Mollie.

### Removed
- Removed support for deprecated 'iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw' integration.
- Removed support for deprecated 'Mollie - iDEAL Advanced' integration.
- Removed support for deprecated 'Sisow - iDEAL Advanced' integration.

## [3.8.9] - 2016-02-12

### Changed
- Do not show 'Add New' button in admin for pronamic_payment post type.

### Fixed
- Updated WordPress pay ABN AMRO - iDEAL Easy library to version 1.0.1.
  - Extend from the Ingenico/Ogone Easy config class.
- Updated WordPress pay Gravity Forms library to version 1.4.2.
  - Renamed 'iDEAL Fields' to 'Payment Fields' since it's more then iDEAL.
  - Fixed typo `sprint` to `sprintf`.
- Updated WordPress pay ClassiPress library to version 1.0.1.
  - Removed status code from redirect in update_status.
- Updated WordPress pay iThemes Exchange library to version 1.1.1.
  - WordPress Coding Standards optimizations.
  - Added escaping functions to improve security.
  - Changed h2 element to h1 element on admin settings page.
  - Add support for payment methods, improve error reporting.
  - Added order ID to payment description.
  - Removed status code from redirect in status_update.
- Updated WordPress pay Shopp library to version 1.0.3.
  - WordPress Coding Standards optimizations.
  - Added support for payment methods, improved error reporting.
  - Removed status code from redirect in status_update.
- Updated WordPress pay WP eCommerce library to version 1.0.2.
  - WordPress Coding Standards optimizations.
  - Removed status code from redirect in status_update.
  - Added Pronamic gateway, whith payment method selector in plugin settings.
  - iDEAL gateway now uses the iDEAL payment method.

## [3.8.8] - 2016-02-11

### Fixed
- Updated WordPress pay Sisow library to version 1.1.5.
  - Use iDEAL payment method also if none set in issuer field.
- Updated WordPress pay s2Member library to version 1.2.2.
  - Fixed 'Notice: Undefined index: orderID'.
  - Fixed password not included in registration confirmation.
  - Added support for payment method in shortcode.
  - Removed status code from redirect in status_update.
- Updated WordPress pay Event Espresso library to version 1.1.5.
  - Fix only first payment updates EE transaction.
  - Set default payment method to iDEAL if required.
  - Added iDEAL gateway and payment method.
  - Removed status code from redirect in status_update.
- Updated WordPress pay Event Espresso (legacy 3.1) library to version 1.0.2.
  - Removed status code from redirect.

## [3.8.7] - 2016-02-10

### Changed
- Updated WordPress pay Ogone library to version 1.2.6.
  - Use PARAMPLUS for the payment ID.
- Updated WordPress pay Sisow library to version 1.1.4.
  - Set default payment method to iDEAL if none set.

## [3.8.6] - 2016-02-05

### Fixed
- Updated WordPress pay ABN AMRO - iDEAL Zelfbouw - v3 library to version 1.0.2.
  - Switched test and production URL's in config classes.

## [3.8.5] - 2016-02-05

### Fixed
- Updated WordPress pay ABN AMRO - iDEAL Zelfbouw - v3 library to version 1.0.1.
  - Make sure to use the iDEAL Advanced v3 config class.
- Updated WordPress pay Deutsche Bank - iDEAL Expert - v3 library to version 1.0.1.
  - Make sure to use the iDEAL Advanced v3 config class.

## [3.8.4] - 2016-02-05

### Removed
- Removed the deprecated Rabobank - iDEAL Lite library.
- Removed the deprecated Rabobank - Rabo iDEAL Kassa library.
- Removed the deprecated Rabobank - iDEAL Professional library.
- Removed the deprecated PayDutch library.
- Removed the deprecated NEOS - Internet Kassa library.
- Removed the deprecated ING - iDEAL Advanced library.
- Removed the deprecated Friesland Bank - iDEAL Zakelijk library.
- Removed the deprecated Friesland Bank - iDEAL Zakelijk Plus library.
- Removed the deprecated Friesland Bank - iDEAL Zakelijk Plus - v3 library.
- Removed the deprecated Fortis Bank - iDEAL Integrated library.
- Removed the deprecated Fortis Bank - iDEAL Internet Kassa library.
- Removed the deprecated Fortis Bank - iDEAL Hosted library.
- Removed the deprecated ABN AMRO - iDEAL Zelfbouw library.
- Removed the deprecated ABN AMRO - iDEAL Hosted library.

### Fixed
- Updated WordPress pay Sisow library to version 1.1.3.
  - Fixed 'Fatal error: Call to a member function set_payment_method() on null'.
- Updated WordPress pay Gravity Forms library to version 1.4.1.
  - Fixed 'Warning: Invalid argument supplied for foreach() on line 200'.
- Fixed 'Fatal error: Function name must be a string in admin/meta-box-gateway-config.php on line 285.'

## [3.8.3] - 2016-02-04

### Fixed
- Updated WordPress pay core library to version 1.3.3.
  - Readded the MiniTix payment method constant for backwards compatibility.
- Updated WordPress pay Easy Digital Downloads library to version 1.2.2.
  - Removed discontinued MiniTix gateway.
  - Removed status code from redirect in status_update.
- Updated WordPress pay Charitable library to version 1.0.1.
  - Removed discontinued MiniTix gateway

## [3.8.2] - 2016-02-03

### Fixed
- Updated WordPress pay ING Kassa Compleet library to version 1.0.1.
  - Fixed fatal eror 'Can't use function return value in write context'.

## [3.8.1] - 2016-02-02

### Fixed
- Updated WordPress pay Membership library to version 1.0.3.
  - Fixed fatal eror 'Can't use function return value in write context'.

### Changed
- Updated WordPress pay WooCommerce library to version 1.1.6.
  - Added support for WooCommerce Deposits plugin.

## [3.8.0] - 2016-02-02

### Changed
- Removed discontinued MiniTix gateway.
- Updated WordPress pay Buckaroo library to version 1.2.0.
  - Renamed namespace prefix from 'class Pronamic_WP_Pay_Buckaroo_' to 'Pronamic_WP_Pay_Gateways_Buckaroo_'.

## [3.7.3] - 2015-10-19

### Fixed
- Fixed Fatal error: Call to a member function get_input_fields() on null on payment form without an valid gateway configuration.

### Changed
- Updated WordPress pay Ogone library to version 1.2.4.
  - Fixed Strict standards: Declaration of Pronamic_WP_Pay_Gateways_Ogone_OrderStandardEasy_Gateway should be compatible with Pronamic_WP_Pay_Gateway::start().
- Updated WordPress pay TargetPay library to version 1.0.4.
  - Fixed some issues on the TargetPay library.
  - Fixed Strict standards: Declaration of Pronamic_WP_Pay_Gateways_TargetPay_Gateway::start().
- Updated WordPress pay Gravity Forms library to version 1.3.2.
  - Fix missing issuer dropdown in form editor and front end for feeds with condition enabled.
  - No longer use an custom query to get the pay Gravity Forms posts.
  - Added an extra parameter to retrieve payments feed with an gateway with iDEAL issuers.
  - No longer redirect with 303 status code.
- Updated WordPress pay WooCommerce library to version 1.1.5.
  - Removed status code 303 from redirect.

## [3.7.2] - 2015-10-19

### Changed
- Also show payments post with the post status 'publish' on the WordPress admin payments page.
- Updated WordPress pay ICEPAY library to version 1.2.4.
  - Fixed fatal error with wrong constant usage.
- Updated WordPress pay Qantani library to version 1.0.5.
  - Fixed strict comparison issue on XML status element.
- Updated WordPress pay Easy Digital Downloads library to version 1.2.1.
  - Set the payment method to use before getting the gateway inputs.

### Fixed
- Fixed NOTICE: Undefined property: Pronamic_WP_Pay_LicenseManager::$response.

## [3.7.1] - 2015-10-15

### Changed
- Updated WordPress pay core library to version 1.2.2.
  - Add payment method 'Bank transfer'.

## [3.7.0] - 2015-10-15
- Fix transaction status checking event not 'renewed'.
- Prevent redirects from within extensions if doing cron, so that events get sheduled in check_status.

### Changed
- Updated WordPress pay Buckaroo library to version 1.1.2.
  - Fix incorrect signature due to slashes in data.
- Updated WordPress pay ICEPAY library to version 1.2.2.
  - Make sure to use language and country values from payment data object.
- Updated WordPress pay Mollie library to version 1.1.2.
  - Add support for direct iDEAL payment method.
- Updated WordPress pay MultiSafepay Connect library to version 1.2.3.
  - Add support for bank transfer as payment method.
- Updated WordPress pay OmniKassa library to version 1.1.3.
  - Add locale helper to prevent sending unsupported language codes.
  - Add filter pronamic_pay_omnikassa_payment_mean_brand_list.
  - Handle response NUMBER_ATTEMPT_EXCEEDED as failure status.
- Updated WordPress pay TargetPay library to version 1.0.3.
  - Add scheduled transaction status request.
- Updated WordPress pay Event Espresso library to version 1.1.2.
  - Fix sending multiple notifcations.
- Updated WordPress pay Gravity Forms library to version 1.3.1.
  - Add support for multiple payment feeds with conditions per form.
  - Only use visible issuer dropdowns (allows conditional logic on issuer dropdown field.
- Updated WordPress pay s2Member library to version 1.2.1.
  - Fix incorrect period naming.
- Updated WordPress pay WooCommerce library to version 1.1.3.
  - Order note "iDEAL payment [status]" now includes the gateway title, instead of "iDEAL".
  - Add DirectDebitGateway.
  - Add bank transfer gateway.
- Updated WordPress pay Ogone library to version 1.2.2.
  - Added support for the Ogone TP parameter.
- Updated WordPress pay Ogone library to version 1.2.3.
  - Added support for the direct payment method credit card.
  - Added support for the direct payment method iDEAL.

## [3.6.6] - 2015-06-29

### Changed
- Updated WordPress pay Gravity Forms library to version 1.3.0.
  - Added support for Gravity Forms AWeber Add-On version 2.2.1.
  - Added support for Gravity Forms Campaign Monitor Add-On version 3.3.2.
  - Added support for Gravity Forms MailChimp Add-On version 3.6.3.
- Updated WordPress pay Membership library to version 1.0.1.
  - Fixed WordPress callback for the admin settings section.

## [3.6.5] - 2015-06-15

### Changed
- Improved support for the W3 Total Cache plugin.

### Fixed
- Fix JobRoller class name on admin tab 'Extensions'.

## [3.6.4] - 2015-05-26

### Changed
- Updated WordPress pay AppThemes library to version 1.0.0.
- Updated WordPress pay ClassiPress library to version 1.0.0.
- Updated WordPress pay iThemes Exchange library to version 1.1.0.
- Updated WordPress pay JobRoller library to version 1.0.0.
- Updated WordPress pay Membership library to version 1.0.0.
- Updated WordPress pay WP e-Commerce library to version 1.0.0.
- Updated WordPress pay Gravity Forms library to version 1.2.4.
  - Only process payments if amount is higher than zero.
- Updated WordPress pay Sisow library to version 1.1.0.
  - Added support for Shop ID.
- Updated WordPress pay Shopp library to version 1.0.2.
  - Added missing file GatewayModule.php for Shopp < 1.3 support.
- Minified all images.

## [3.6.3] - 2015-05-08
- No longer include all development Composer packages.
- Updated WordPress pay MultiSafepay Connect library to version 1.2.1.
  - Fix fatal error due to undefined var $result.

## [3.6.2] - 2015-05-06
- Updated WordPress pay core library to version 1.2.1.
  - Added XML utility class.
- Updated WordPress pay WooCommerce library to version 1.1.2.
  - Added general Pronamic gateway so the iDEAL gateway can be used for iDEAL only.
- Updated WordPress pay s2Member library to version 1.2.0.
  - Added experimental support for `ccaps` in shortcode.
  - Added settings field for the signup confirmation email message.
  - Added HTML admin views from the Pronamic iDEAL plugin.
- Updated WordPress pay MultiSafepay Connect library to version 1.2.0.
  - Added support for direct transaction request for iDEAL.

## [3.6.1] - 2015-04-02
- Updated WordPress pay Event Espresso library to version 1.1.1.
  - Updated WordPress pay core library to version 1.2.0.
  - No longer parse HTML input fields but use the new get_output_fields() function.
  - Added workaround for strange behaviour with 2 config select options.
- Updated WordPress pay Gravity Forms library to version 1.2.3.
  - Entry with payment status 'Paid' are now also seen as 'approved'.
  - Use entry ID as default transaction description.
  - WordPress Coding Standards optimizations.
- Updated WordPress pay Qantani library to version 1.0.2.
  - No longer disable SSL verify.

## [3.6.1] - 2015-04-02
- Tweak - Updated WordPress pay Easy Digital Downloads library to version 1.1.0.
  - Added Credit Card gateway.
  - Added Direct Debit gateway.
  - Added iDEAL gateway.
  - Added MiniTix gateway.
  - Added Bancontact/Mister Cash gateway.
  - Added SOFORT Banking gateway.
  - Added gateway setting for the checkout label.
  - Only show transaction ID if set.
  - Added pending payment note with link to payment post.
  - Tested on Easy Digital Downloads version 2.3.
- Tweak - Updated WordPress pay Event Espresso library to version 1.1.0.
  - Added experimental support for Event Espresso 4.6 (or higher).

## [3.5.2] - 2015-03-09
- Tweak - Updated WordPress pay iDEAL library to version 1.1.0.
  - Added an utlity class wich can create purchase ID's.
- Tweak - Updated WordPress pay iDEAL Basic library to version 1.1.0.
  - Improved support for user defined purchase ID's.
- Tweak - Updated WordPress pay iDEAL Advanced v3 library to version 1.1.0.
  - Improved support for user defined purchase ID's.
- Tweak - Updated WordPress pay iDEAL Advanced library to version 1.1.0.
  - Improved support for user defined purchase ID's.

## [3.5.1] - 2015-03-03
- Tweak - Updated WordPress pay core library to version 1.1.0.
- Tweak - Updated WordPress pay Buckaroo library to version 1.1.0.
- Tweak - Updated WordPress pay MultiSafepay library to version 1.1.0.
- Tweak - Updated WordPress pay MultiSafepay Connect library to version 1.1.0.
- Tweak - Updated WordPress pay Ogone library to version 1.2.0.
- Tweak - Updated WordPress pay Pay.nl library to version 1.1.0.
- Tweak - Updated WordPress pay Gravity Forms library to version 1.2.2.

## [3.5.0] - 2015-02-19
- Tweak - Updated WordPress pay ICEPAY library to version 1.2.0.
- Tweak - Added context to the ICEPAY admin labels and inmproved Dutch translations.
- Tweak - Changed the ICEPAY login URL's to the new portal login URL's.
- Tweak - Updated WordPress pay core library to version 1.0.1.
- Tweak - Updated WordPress pay Mollie library to version 1.1.0.
- Tweak - Updated Wordpress pay WooCommerce library to version 1.1.0.
- Tweak - Updated Wordpress pay Ogone library to version 1.1.0.
- Tweak - Updated Wordpress pay OmniKassa library to version 1.1.0.
- Tweak - Added obsoleted text to deprecated gateways.

## [3.4.2] - 2015-02-16
- Tweak - Updated WordPress pay Event Espresso library to version 1.0.2.

## [3.4.1] - 2015-02-13
- Tweak - Updated WordPress pay Gravity Forms library to version 1.2.1.

## [3.4.0] - 2015-02-12
- Tweak - Updated WordPress pay s2Member library to version 1.1.0.
- Tweak - Updated WordPress pay Gravity Forms library to version 1.2.0.
- Tweak - Updated WordPress pay Shopp library to version 1.0.0.

## [3.3.3] - 2015-02-12
- Tweak - Updated WordPress pay iDEAL Basic library to version 1.0.1.
- Tweak - Updated WordPress pay iThemes Exchange library to version 1.0.1.

## [3.3.2] - 2015-01-27
- Tweak - Updated WordPress pay WooCommerce library to version 1.0.2.
- Tweak - Updated WordPress pay Event Espresso library to version 1.0.1.

## [3.3.1] - 2015-01-02
- Tweak - Updated WordPress pay Gravity Forms library to version 1.0.1.

## [3.3.0] - 2014-12-30
- Feature - Event Espresso 4 - Added expirmental support for Event Espresso 4.
- Tweak - Event Espresso 3 - Improved support for Event Espresso 3.
- Tweak - Adeed WordPress pay Gravity Forms library version 1.0.0.

## [3.2.4] - 2014-12-19
- Tweak - Updated WordPress pay WooCommerce library to version 1.0.1.
- Test - WordPress - Tested up to version 4.1.

## [3.2.3] - 2014-12-16
- Tweak - Updated WordPress pay Pay.nl library to version 1.0.2.

## [3.2.2] - 2014-12-15
- Tweak - Updated WordPress pay Sisow library to version 1.0.1.
- Tweak - Updated WordPress pay Pay.nl library to version 1.0.1.

## [3.2.1] - 2014-12-10
- Tweak - Sisow - Improved status update handling.
- Tweak - TargetPay - No longer disable SSL verify.
- Tweak - Ogone - Improved retrieving request data.
- Refactor - Rewritten the admin class.

## [3.2.0] - 2014-12-10
- Feature - WooCommerce - Credit Card - Added support for Buckaroo.
- Feature - WooCommerce - Bancontact/Mister Cash - Added support for Sisow.
- Tweak - Sisow - Sanitize Sisow 'purchaseid' and 'entrancecode' parameters.
- Tweak - Buckaroo - Renamed Buckaroo gateway from "Buckaroo - iDEAL" to "Buckaroo - HTML".
- Tweak - Sisow - Renamed Sisow gateway from "Sisow - iDEAL" to "Sisow".
- Tweak - WooCommerce - Auto submit gateways HTML forms on WooCommerce receipt page.

## [3.1.5] - 2014-11-18
- Fix - iDEAL Basic - Fixed fatal error - Call to undefined function Pronamic_WP_Pay_Gateways_IDealBasic_Items(). 
- Fix - iDEAL Basic - Fixed fatal error - Call to undefined method Pronamic_WP_Pay_Gateways_IDealBasic_Item::getNumber().

## [3.1.4] - 2014-11-17
- Fix - iDEAL Advanced v3 - Fixed fatal error with iDEAL Advanced v3 gateway.

## [3.1.3] - 2014-11-14
- Tweak - Pay.nl - Improved error handling.
- Fix - Ogone - Fixed fatal error with Ogone order standard easy gateway.

## [3.1.2] - 2014-11-12
- Fix - iDEAL Basic - Fixed issue with not loading iDEAL Basic gateway.
- Tweak - Added some missing text domains in translation functions.
- Tweak - Easy Digital Downloads - Fixed text domain 'edd' to 'pronamic_ideal'.

## [3.1.1] - 2014-11-12
- Fix - Fatal error: Call to undefined method Pronamic_WP_Pay_Plugin::set_roles.

## [3.1.0] - 2014-11-12
- Feature - WooCommerce - Added MiniTix gateway for OmniKassa.
- Feature - WooCommerce - Added Credit Card gateway for OmniKassa and Mollie.
- Tweak - WooCommerce - Adjusted default payment gateway icons.
- Tweak - Moved all gateway libraries to https://github.com/wp-pay-gateways.
- Tweak - Mollie - Improved support for the Mollie webhook feature.
- Tweak - For a lot of gateways we no longer disable SSL verify.
- Feature - Ogone - Added configuration field for the PARAMVAR field.

## [3.0.0] - 2014-10-31
- Feature - Pay.nl - Added basic support for the Pay.nl payment provider.
- Tweak - Mollie - Added the 'locale' parameter in the create payment request.

## [2.9.4] - 2014-10-20
- Feature - s2Member - Added support for 'button_text' attribute in the `[pronamic_ideal_s2member]` shortcode.
- Test - s2Member - Tested up to version 141007.
- Tweak - Gravity Forms - Improved support for delay AWeber, Campaign Monitor and MailChimp subscription.

## [2.9.3] - 
- Feature - Added an Ogone configuration field for the ORDERID parameter.

## [2.9.2] - 2014-10-07
- Tweak - Payment note comments are always ignored in comment queries.
- Feature - Easy Digital Downloads - Added support for Bancontact/Mister Cash icon.
- Tweak - ICEPAY - Added support for http:// and https://.
- Tweak - Ogone - Moved Ogone gateway to it's own repository (https://github.com/wp-pay-gateways/ogone).
- Tweak - Ogone - Updated the Ogone calculations parameters for SHA-IN and SHA-OUT.
- Feature - Ogone - Add payment notes on Ogone payment status requests.
- Feature - Ogone - Added Ogone Direct HTTP server-to-server request URL fields.
- Tweak - Use the global payemnt status pages as backup.
- Fix - Jigoshop - Fixed support for Jigoshop version 1.12.
- Test - Jigoshop - Tested up to version 1.12.
- Tweak - Jigoshop - Moved Jigoshop extension to it's own repository (https://github.com/wp-pay-extensions/jigoshop).

## [2.9.1] - 2014-09-18
- Fix - OmniKassa - Fixed fatal error.

## [2.9.0] - 2014-09-18
- Tweak - Easy Digital Downloads - Display gateway errors.
- Feature - Easy Digital Downloads - Added support for iDEAL payment icon.
- Test - Easy Digital Downloads - Tested up to version 2.1.3.
- Tweak - Qantani - Truncate description longer then 30 characters.
- Tweak - Initialize post types on 'init' action priority 0 to fix a notice.
- Tweak - Show 'No logs found.' when no payment logs are found in the logs meta box.
- Tweak - Gravity Forms AWeber Add-On - Improved support for delayed subscriptions.

## [2.8.9] - 2014-09-09
- Test - WordPress - Tested up to version 4.0.
- Test - Gravity Forms - Tested up to version 1.8.13.
- Fix - Sisow - Improved support for Gravity Forms without issuer dropdowns.
- Tweak - Added payment status to the WordPress admin payment details page.
- Tweak - Gravity Forms - Show add-on options only if they are available.

## [2.8.8] - 2014-09-09
- Fix - WooCommerce - Fix fatal error: Class 'Pronamic_WooCommerce_WooCommerce' not found.
- Fix - Shopp - Fix fatal error: Call to undefined method Shopp::resession().

## [2.8.7] - 2014-08-25
- Fix - OmniKassa - Fixed fatal error loading response codes class.
- Fix - Mollie - Fixed fatal error loading config class.

## [2.8.6] - 2014-08-25
- Fix - OmniKassa - Fixed link to the OmniKassa test environment.
- Feature - WooCommerce - Added extra gateway for the Mister Cash payment method.
- Tweak - PayDutch - Improved support for test and production environments.
- Tweak - No longer create custom database tables for payments and gateway configurations.

## [2.8.5] - 2014-07-24
- Fix - Membership - Check if the Membership Premium function 'membership_get_current_coupon' exists.

## [2.8.4] - 2014-07-22
- Feature - Buckaroo - Added support for Buckaroo Push URI.

## [2.8.3] - 2014-07-21
- Tweak - Gravity Forms - Improved support for concept or trashed Gravity Forms payment feeds.
- Fix - Jigoshop - Added support for Jigoshop version 1.9.3.
- Fix - Mollie - Fixed an status error in the deprecated Mollie iDEAL gateway.

## [2.8.2] - 2014-07-15
- Tweak - Removed dot files.
- Tweak - TargetPay - Fixed TargetPay tests.

## [2.8.1] - 2014-07-14
- Tweak - Use Composer and Packagist for some of the payment gateways libraries.
- Tweak - TargetPay - Simplified TargetPay status update code.

## [2.8.0] - 2014-06-24
- Feature - Added support for the Ogone post-sale request.
- Feature - Added support for the Gravity Forms AWeber Add-On.
- Test - Shopp - Tested up to version 1.3.4.

## [2.7.7] - 2014-06-16
- Tweak - WooCommerce - Use the new endpoint URL's for gateways (http://docs.woothemes.com/document/woocommerce-endpoints-2-1/).
- Tweak - WooCommerce - Improved backwards compatibility for WooCommerce < 2.1.0.

## [2.7.6] - 2014-06-16
- Fix - ICEPAY - Removed the issuer field from the gateway.
- Documentation - WooCommerce - Increased requires at least version up to 2.1.0.

## [2.7.5] - 2014-05-14
- Fix - Buckaroo - Fixed parameter sorting with mixed characther return.

## [2.7.4] - 2014-05-14
- Tweak - WPMU DEV Membership - Improved loading activated gateways.
- Tweak - WPMU DEV Membership - Display errors if they occur.
- Tweak - Mollie - Improved handling of errors.
- Fix - Gravity Forms - Fixed bug with disabling payment feed condition.
- Fix - WP e-Commerce - Retrieving cart data bug.

## [2.7.3] - 2014-05-07
- Tweak - ICEPAY - Update to API library version 2.4.
- Tweak - Simplified WordPress admin menu iDEAL.

## [2.7.2] - 2014-05-02
- Tweak - Gravity Forms - Fixed some notices and improved saving of payment feed data.

## [2.7.1] - 2014-04-30
- Tweak - WPMU DEV Membership - Make sure the gateway is active check will succeed.
- Test - WPMU DEV Membership - Tested up to version 3.5.1.3.
- Test - Gravity Forms Campaign Monitor Add-On - Tested up to version 2.5.1.
- Test - Gravity Forms MailChimp Add-On - Tested up to version 2.4.1.
- Test - Gravity Forms User Registration Add-On - Tested up to version 1.8.
- Tweak - Marked the gateway "ABN AMRO - iDEAL Hosted" as deprecated.
- Tweak - Added the gateway "ABN AMRO - Internetkassa" for completeness.
- Feature - Vantage - Added support for the Vantage theme from AppThemes.

## [2.7.0] - 2014-04-26
- Tweak - WordPress Coding Standards optimizations thansk to PHP CodeSniffer.
- Tweak - Easy Digital Downloads - Fixed an fatal error (public $payment_id).
- Tweak - WPMU DEV Membership - Use Pronamic capability for iDEAL settings page.
- Tweak - Moved everything from the 'WordPress' namespace to the 'WP' namespace.
- Tweak - Improved the private certifcate generate command.

## [2.6.9] - 2014-04-17
- Tweak - s2Member - Require email for not logged in users.
- Tweak - Membership Premium - Added support for version 3.5.1.2.
- Tweak - Buckaroo - Don't generate an one transaction ID, we now use the 'brq_payment' return value from Buckaroo.
- Test - WordPress - Tested up to version 3.9.

## [2.6.8] - 2014-04-03
- Feature - Added support for the [Paytor](http://www.paytor.com/) provider/gateway.
- Tweak - Grayed out deprecated gateways.
- Tweak - Improved backwards compatibility for Gravity Forms 1.6.

## [2.6.7] - 2014-03-24
- Tweak - WPMU DEV Membership - Fixed error after update to Membership 5.3+, we don't support 3.5+ yet.

## [2.6.6] - 2014-03-21
- Tweak - ICEPAY - Use the 'OrderID' in the listener so the correct payment will be updated.
- Fix - Buckaroo - Fixed support for & charachter in blog name for WooCommerce payment description.
- Test - WooCommerce - Tested up to version 2.1.5.
- Test - ClassiPress - Tested up to version 3.3.3.
- Tweak - AppThemes - Process the order/gateway also in the 'template_redirect' hook, to improve some redirect issues.
- Tweak - ABN AMRO - iDEAL Only Kassa - Updated the dashboard URL's to the new BackOffice version URL's.
- Fix - s2Member - Fixed Fatal error: Call to a member function get_level() on a non-object.
- Test - s2Member - Tested up to version 140105.

## [2.6.5] - 2014-03-07
- Tweak - WooCommerce - Improved the {"result":"failure","messages":"","refresh":"false"} error.

## [2.6.4] - 2014-03-05
- Fix - Gravity Forms - Fixed JavaScript error while editing an payment form post.

## [2.6.3] - 2014-02-28
- Tweak - WooCommerce - Improved usage of WooCommerce gateway/order return URL.
- Test - WooCommerce - Tested up to version 2.1.3.

## [2.6.2] - 2014-02-27
- Tweak - s2Member - Only update user if payment status is changed from 'unknown' to 'succes' to prevent double updates.
- Tweak - Did a lot of small adjustments to make this plugin faster.
- Tweak - Automated some tasks with Grunt (PHPLint, JSHint, PHPUnit, makepot.php).
- Test - WooCommerce - Tested up to version 2.1.2.

## [2.6.1] - 2014-02-05
- Tweak - Reduced transient name length to avoid "data too long for column 'option_name'" errors.
- Tweak - Membership - Added support for coupon codes.
- Tweak - Membership - After successful payment redirect user to the "Registration completed page". 
- Tweak - Easy Digital Downloads - Don't display gateway input fieldset if the gateway doesn't require input.

## [2.6.0] - 2014-01-31
- Feature - iThemes Exchange - Added support for the [iThemes Exchange](http://wordpress.org/plugins/ithemes-exchange/) plugin.
- Tweak - Easy Digital Downloads - Improved support for the Pronamic iDEAL gateway.
- Fix - iDEAL Basic - Sisow iDEAL Basic returned error TA3260 when the payment description contained a hash tag.
- Fix - Easy Digital Downloads - Customers weren't redirected to the payment provider upon payment.

## [2.5.0] - 2014-01-23
- Tweak - Rabobank - OmniKassa - Moved listener on payment status up in the WordPress code flow.
- Documentation - Rabobank - OmniKassa - Added OmniKassa documentation v6.0.
- Feature - MultiSafepay - Added support the MultiSafepay gateway.
- Fix - Gravity Forms - Improved support for payment feed description with quotes.
- Feature - Gravity Forms - Added an 'User Registration Delay' setting for the Gravity Forms User Registration Add-On.

## [2.4.3] - 2014-01-16
- Feature - Gravity Forms - Added an entry ID prefix field to the payment feed.

## [2.4.2] - 2014-01-15
- Fix - Buckaroo - When a payment's invoice number was set to "null" the payment would fail.
- Test - Buckaroo - Added unit testing for Buckaroo security methods.
- Feature - Buckaroo - Payment return requests are now logged as a payment note. 

## [2.4.1] - 2014-01-10
- Fix - WordPress.org readme.txt.

## [2.4.0] - 2014-01-10
- Feature - Easy Digital Downloads - Added support for the [Easy Digital Downloads](http://wordpress.org/plugins/easy-digital-downloads/) plugin.
- Fix - s2Member - A probable bug in the s2Member AddOn could cause a user not to be able to upgrade their account after an EOT.
- Fix - s2Member - When no EOT date was set, a successful payment would set the new EOT date on the date of tomorrow regardless of the period paid for.
- Fix - Event Espresso - Gave a warning message when trying to pay, saying you would lose your payment data when leaving the page.
- Tweak - WooCommerce - Improved support for WooCommerce Sequential Order Numbers.
- Tweak - Shopp - Improved support for Shopp 1.3.
- Test - Shopp - Tested up to version 1.3.

## [2.3.1] - 2013-12-20
- Tweak - Gravity Forms - Improved the form check in the Gravity Forms payment processor.

## [2.3.0] - 2013-12-19
- Feature - Added support for the Ogone - DirectLink - 3-D Secure feature.
- Feature - Added payment date the payment details admin meta box.
- Feature - WooCommerce - Added an option field for the gateway icon URL.
- Feature - Mollie - Added support for the new universal Mollie API.
- Feature - Mollie - Added some Mollie badges on the WordPress admin "Branding" page.
- Feature - Mister Cash - Added three Misther Cash icons on the WordPress admin "Branding" page.
- Tweak - Improved support for WordPress lower then 3.6 by adding the wp_slash() function.
- Tweak - Membership - Improved support for "Popup registration form" form type.
- Fix - Membership - Pass in the correct subscription ID in the Membership create_subscription() function.
- Tweak - Gravity Forms - Improved usability for delaying notifications.
- Fix - s2Member - Remove end of time user option if subscription period is lifetime.
- Fix - s2Member - Calculate new end of time based on the previous end of time.
- Test - s2Member - Tested up to version 131126.

## [2.2.4] - 2013-12-12
- Tweak - s2Member - Added a period option to the s2Member iDEAL buttons addon to be able to subscribe for a lifetime.
- Tweak - Membership - Improved activating of the iDEAL gateway.
- Tweak - Jigoshop - Added workaround for the wp_safe_redirect() non AJAX issue to the admin URL.
- Test - WordPress tested up to 3.8.
- Test - ClassiPress tested up to 3.3.1.
- Fix - AppThemes - Fatal error: Class 'Pronamic_WordPress_IDeal_ConfigurationsRepository' not found in classes\Pronamic\AppThemes\IDeal\IDealGateway.php on line 54.
- Fix - AppThemes - Fatal error: Call to undefined method Pronamic_WP_Pay_Payment::getSource() in classes/Pronamic/AppThemes/IDeal/AddOn.php on line 52.
- Tweak - WooCommerce - Strict Standards: call_user_func_array() expects parameter 1 to be a valid callback, non-static method Pronamic_WooCommerce_IDeal_AddOn::payment_gateways() should not be called statically.
- Tweak - Strict Standards: Declaration of Pronamic_Gateways_IDealAdvanced_XML_ErrorResponseMessage::parse() should be compatible with Pronamic_Gateways_IDealAdvanced_XML_ResponseMessage::parse(SimpleXMLElement $xml, Pronamic_Gateways_IDealAdvanced_XML_ResponseMessage $message).
- Tweak - Jigoshop - Strict Standards: call_user_func_array() expects parameter 1 to be a valid callback, non-static method Pronamic_Jigoshop_IDeal_AddOn::payment_gateways() should not be called statically.
- Tweak - Jigoshop - Deprecated: Assigning the return value of new by reference is deprecated in classes/Pronamic/Jigoshop/IDeal/IDealGateway.php on line 153.
- Tweak - Jigoshop - Deprecated: Assigning the return value of new by reference is deprecated in classes/Pronamic/Jigoshop/IDeal/IDealGateway.php on line 172.

## [2.2.3] - 2013-11-28
- Tweak - Gravity Forms - Added an processor class wich handles payment forms.
- Fix - Gravity Forms - Improved support for AJAX driven forms.
- Fix - Warning: addslashes() expects parameter 1 to be string, array given.
- Fix - Fatal error: Class Pronamic_Gateways_Ogone_XML_OrderResponseParser cannot extend from interface Pronamic_Gateways_IDealAdvancedV3_XML_Parser.
- Fix - Strict Standards: Non-static method Pronamic_WPeCommerce_IDeal_AddOn::advanced_inputs() should not be called statically.

## [2.2.2] - 2013-11-26
- Fix - Strict Standards: Declaration of 'function' should be compatible with 'function'.
- Tweak - Added support for slashes in the gateway configuration meta values.
- Tweak - Added support for '(' and ')' charachters in private key and certificate commands.
- Tweak - Sisow - Improved support for 'callback' and 'notify' requests to ensure Google Analytics e-commerce tracking.
- Tweak - Shopp - Improved status update. 

## [2.2.1] - 2013-11-22
- Tweak - Added cURL version to system status page (for cURL bug in v7.31.0 http://sourceforge.net/p/curl/bugs/1249/).
- Tweak - Ogone DirectLink - Converted pass phrase and password config fields to password fields.
- Tweak - Ogone DirectLink - Use UTF-8 URL's when WordPress charset is set to UTF-8.
- Fix - Ogone DirectLink - Fixed the API URL's in production mode.
- Fix - ABN AMRO - iDEAL Zelfbouw - v3 - Fixed the gateway URL's.

## [2.2.0] - 2013-11-19
- Feature - WooCommerce - Added support for payment description with WooCommerce tags like {order_number} and {blogname}.
- Tweak - ICEPAY - Use payment ID for the order ID field to prevent "Duplicate IC_OrderID" errors.

## [2.1.0] - 2013-11-14
- Tweak - Added character set to the system status page.
- Tweak - Gravity Forms - Improved delay notifiations function after succesfull payment.
- Tweak - Gravity Forms - Added support for Campaign Monitor Subscription Delay.
- Tweak - Gravity Forms - Added support for MailChimp Subscription Delay.
- Tweak - ABN AMRO - iDEAL Easy - Improved support for mulitple payments for same order.
- Tweak - Ogone - DirectLink - Improved payment status update.

## [2.0.7] - 2013-11-06
- Tweak - Sisow - Use order ID as purchase ID if not empty.
- Tweak - Event Espresso - Improved support for e-mail notifications after payment.
- Tweak - iDEAL Advanced v3 - Limit the Directory Request requests.
- Tweak - ICEPAY - Limit the get supported issuers calls.
- Tweak - Qantani - Limit the get banks calls.
- Tweak - Rabobank OmniKassa - Improved upgrade script to convert key version from 1.0 to 2.0.
- Tweak - Ogone DirectLink - Show Ogone error when nc_error is not empty.
- Test - Event Espresso - Tested up to 3.1.35.P.
- Fix - Ogone DirectLink - Added support for Ogone hashing algorithm.
- Fix - Ogone OrderStandard - Improved upgrade function to convert SHA IN and OUT pass phrases.
- Fix - Strict Standards: Non-static method Pronamic_Gateways_IDealBasic_Listener::listen() should not be called statically.
- Fix - Strict Standards: Non-static method Pronamic_Gateways_OmniKassa_Listener::listen() should not be called statically.
- Fix - Strict Standards: Non-static method Pronamic_Gateways_Icepay_Listener::listen() should not be called statically.

## [2.0.6] - 2013-10-30
- Fix - Rabobank OmniKassa - Fixed status update listener.

## [2.0.5] - 2013-10-30
- Fix - Ogone DirectLink - Fatal error: Call to a member function set_transaction_id() on a non-object.
- Fix - Rabobank OmniKassa - Fixed status update listener.
- Tweak - ICEPAY - Improved error handling.

## [2.0.4] - 2013-10-30
- Fix - Gravity Forms - Fatal error: Call to undefined method Pronamic_Pay_Gateway::get_transaction_id().
- Tweak - Improved upgrade function to convert custom tables to custom post types.

## [2.0.3] - 2013-10-28
- Fix - ClassiPress - Improved support for HTML gateways.
- Fix - Jigoshop - Improved support for HTML gateways.
- Fix - WooCommerce - Improved support for HTML gateways.
- Fix - iDEAL Advanced v3 - Improved status update.
- Test - ClassiPress tested up to 3.3.1.

## [2.0.2] - 2013-10-28
- Fix - Improved support for PHP 5.2 (Parse error: syntax error, unexpected T_PAAMAYIM_NEKUDOTAYIM)

## [2.0.1] - 2013-10-28
- Fix - Fixed an issue saving Pronamic iDEAL settings.
- Fix - WooCommerce issue on iDEAL checkout.
- Fix - Jigoshop issue on iDEAL checkout.
- Localization - Danish translation by Pronamic.
- Test - Jigoshop tested up to 1.8.
- Test - WooCommerce tested up to 2.0.18.
- Test - s2Member tested up to 131026.

## [2.0.0] - 2013-10-28
- Refactor - Converted configurations to posts (custom post type).
- Refactor - Converted payments to posts (custom post type).
- Refactor - Converted Gravity Forms pay feeds to posts (custom post type).
- Refactor - Rewritten all gateways, configurations, update status functions and more.
- Refactor - Settings pages now use the WordPress settings API.
- Refactor - s2Member iDEAL gateway rewritten.
- Refactor - Membership iDEAL gateway rewritten.
- Refactor - WP e-Commerce iDEAL gateway rewritten.
- Refactor - WordPress Coding Standards optimizations.
- Feature - Added support for the "Deutsche Bank - iDEAL via Ogone" gateway.
- Feature - Added support for the "Ogone - DirectLink" gateway.
- Feature - Added support for the "Dutch Payment Group - PayDutch" gateway. 
- Feature - Extended the iDEAL Advanced v3 private key and certifcate generate commands.
- Feature - Added log/note/comment system to payments using WordPress comment system.
- Feature - Added an dashboard page - latest payments, Pronamic news and more.
- Feature - Added an system status - supported extensions, versions, build status and more.
- Feature - Added settings for global return pages.
- Tweak - Added support for iDEAL Advanced v3 on PHP 5.2 (thanks to ING).
- Tweak - Display ICEPAY return URL's in readonly fields.
- Tweak - Adjusted Ogone dashboard URL's to the new dashboard URL's.
- Tweak - Added support for Ogone hash algorithms (SHA-1, SHA-256 and SHA-512).
- Tweak - Added more unit testing for gateways and XML parsing.
- Localization - Added POT file and use makepot.php

## [1.3.4] - 2013-10-10
- Improved support for cancelled payments in WooCommerce

## [1.3.3] - 2013-10-07
- Fixed bug Fatal error: Class 'Pronamic_WordPress_IDeal_IDealTestDataProxy' not found 
- Added support for the 'Deutsche Bank - iDEAL via Ogone' variant
- Added check on required OpenSSL version 0.9.8 with SHA256 support
- Improved support for Event Espresso 3.1, added iDEAL logo

## [1.3.2] - 2013-09-06
- Updated to Icepay API library v2.3.0

## [1.3.1] - 2013-08-01
- Fixed deprecated notice in Jigoshop Add-On (Assigning the return value of new by reference)

## [1.3.0] - 2013-07-29
- Added support for the Qantani iDEAL payment provider

## [1.2.11] - 2013-07-24
- Fixed saving of private key and certificate

## [1.2.10] - 2013-06-03
- Configuration editor - Removed double private key password field
- iDEAL Advanced v3 - Improved error handling signing documents

## [1.2.9] - 2013-05-31
- Sisow - Added support for Sisow REST API
- Gravity Forms - Improved send notifications after payment
- Configuration editor - Extended v3 with private key and certificate commands

## [1.2.8] - 2013-05-28
- ICEPAY - Added support for the ICEPAY payment provider
- Gravity Forms - Fixed send notifications after payment for Gravity Forms 1.7.2+
- Event Espresso - Fixed double e-mail notifications
- TargetPay - Added support for customer info in callback (direct debit)

## [1.2.7] - 2013-05-07
- Membership - Improved the check for an active iDEAl gateway
- Mollie - Enabled feedback support for the Mollie gateway
- Cleaned up the configuration editor and add support for certificate info with iDEAL v3
- s2Member - Improved support for providers wich support an description (Sisow)
- WooCommerce - Improved the check payment e-mail note by using get_order_number()
- Return URL's - Improved use of site_url() and home_url() functions
- Buckaroo - Added support for the Buckaroo payment provider (thanks to Marcel Snoeck)
- iDEAL Easy - Improved return handling
- s2Member - Login is no longer required to pay with iDEAL

## [1.2.6] - 2013-03-25
- s2Member - Added support for the s2Member plugin
- Membership from WPMUDEV.org - Added support for the Membership from WPMUDEV.org plugin
- Mollie - Use transient for issuers/banks list
- Jigoshop - Improved order status check, prevent multiple stock reducing with OmniKassa

## [1.2.5] - 2013-03-04
- iDEAL Advanced - Improved handling of parsing response messages
- TargetPay - Improved handling of payment status return information
- TargetPay - No longer verify SSL
- WordPress - Tested up to version 3.5.1
- Event Espresso - Improved the handling of sending e-mails
- Gravity Forms - Fullfill order callback is no called only once
- Mollie - Improved error handling
- Pages generator pages now have by default no index (WordPress SEO by Yoast)

## [1.2.4] - 2013-02-04
- ClassiPress - Improved URL redirect if payment status was not successful

## [1.2.3] - 2013-02-02
- Event Espresso - Improved support for gateways wich have input fields
- ClassiPress - Improved support for gateways wich have input fields
- Shopp - Fixed issue with gateways with an issuer input field
- WooCommerce - Fixed issue with no description and gateway input fields
- Display certificate valid from and to values on the confiugration editor

## [1.2.2] - 2013-01-27
- Fix - Fatal error on saving settings

## [1.2.1] - 2013-01-22
- WordPress Coding Standards optimizations
- Performance optimizations
- Mollie no longer verify SSL, didn't work on all servers

## [1.2] - 2013-01-15
- Added support for TargetPay iDEAL API
- Added support for Mollie iDEAL API
- InternetKassa - Improved handling of signature IN and OUT creating
- Jigoshop - Improved backwards compatibilty for v1.2 or lower
- OmniKassa - Fixed issue with key version error in admin tests page

## [1.1.1] - 2012-12-21
- OmniKassa - Fixed version key issue
- Jigoshop - Improved backwards compatibilty for v1.2 or lower
- Improved the configurations selector
- Added ID column to the configurations overview table  

## [1.1] - 2012-12-17
- Added support for iDEAL Advanced version 3.3
- Added support for ABN AMRO - iDEAL Zelfbouw
- Added status page powered by http://www.ideal-status.nl/
- Abstracted the gateways classes
- WooCommerce - Added support for [Sequential Order Numbers Pro](http://wcdocs.woothemes.com/user-guide/extensions/functionality/sequential-order-numbers/#add-compatibility)
- OmniKassa - Added key version field in configuration editor
- Jigoshop - Updated the iDEAL gateway settings section
- ClassiPress - Added support for HTTP redirect gateways

## [1.0] - 2012-09-21
- First official release, removed the 'beta' label.
- Added an 'Branding' page for easy adding iDEAL banners.
- Added English documentation for changes in v3.3.1.
- ClassiPress - Tweak - Improved support for the ClassiPress theme.

## [beta-0.11.1] - 2012-08-09
- WP e-Commerce - Tweak - Improved the support for iDEAL Advanced variants.

## [beta-0.11.0] - 2012-07-24
- Tweak - Improved support for the iDEAL Easy variant, this variant requires an PSP ID
- Shopp - Fix - Force auth only for the iDEAL gateway
- OmniKassa - Removed the optional parameter 'customerLanguage', was giving "Ongeldige waarde  : customerLanguage=NL" error
- OmniKassa - Added documentation "Integration guide Rabo OmniKassa – Version 2.0.1 April 2012"
- Gravity Forms - Added custom merge tags for payment status, date, amount and transaction id

## [beta-0.10.1] - 2012-07-13
- Fix - OmniKassa configuration could net input hash key 

## [beta-0.10] - 2012-07-11
- Tweak - Added extra check in loading certificates files from the iDEAL XML file
- Shopp - Fix - Purchases with discount payments fix
- Tweak - Added ABN AMRO Bank : Parameter Cookbook documentation link
- Feature - Added support for the iDEAL Internet Kassa of Ogone

## [beta-0.9.9] - 2012-06-28
- Shopp - Test - Checked the 1.2.2 changelog (https://shopplugin.net/blog/shopp-1-2-1-release-notes/)
- Shopp - Fix - Shopp is not showing the new payments settings after saving, bug in Shopp
- Tweak - Changed home_url() to site_url() for retrieving license information
- Event Espresso - Tweak - Improved the documentation of some functions and constants
- Event Espresso - Fix - Removed debug information from choose payment option page 
- Jigoshop - Tweak - Return visitor to view order page after expired payment
- WP e-Commerce - Test - Version 3.8.8.2 and 3.8.8.3
- WP e-Commerce - Tweak - Improved the return, cancel, success and error URL's
- Shopp - Tweak - Improved the return, cancel, success and error URL's
- Shopp - Tweak - Improved the automatic status update of purchases
- WordPress - Test - Version 3.4.1

## [beta-0.9.8] - 2012-06-11
- Shopp - Fix - The 'selected' parameter in module settings UI render drop-down menu function is sometimes type sensitive
- Shopp - Fix - Added wrapper code to JavaScript so $ will work for calling jQuery
- Event Espresso - Added support for the Event Espresso plugin

## [beta-0.9.7] - 2012-06-07
- OmniKassa - Added the "Zo werkt het aanvragen en aansluiten van de Rabo OmniKassa" PDF file to the documentation page
- OmniKassa - Added an easy interface to execute the five iDEAL simulation transactions
- Jigoshop - Updated the HTML options table head of the IDEAL gateway to the default Jigoshop format
- iDEAL Advanced - Fixed an issue with an empty WordPress WPLANG constant, causing field generating error: language. Parameter '' has less than 2 characters
- Sisow - Added the "Sisow - Pronamic iDEAL" PDF file to the documentation page
- Gravity Forms - Improved the determination of the status URL
- Sisow - Added support for "Sisow - iDEAL Basic" variant
- Sisow - Added support for "Sisow - iDEAL Advanced" variant
- Gravity Forms - Display error code if somehting is going wrong with the iDEAL Advanced variant
- Shopp - Added 'keyed' => true parameter to the module settings UI render drop-down menu function
- Tweak - Removed the utility function remove query arguments from URL, no longer used
- Tweak - Improved the utility class and the retrieval of the ISO 639 and ISO 3166 values
- Tweak - Improved the iDEAL Basic test page, the WordPress language value is now used 
- Gravity Forms - Fix - Removed the esc_js() function from the AJAX redirection JavaScript function

## [beta-0.9.6] - 2012-05-10
- Gravity Forms - Fixed bug with hidden conditional field, ignore iDEAL

## [beta-0.9.5] - 2012-05-10
- Gravity Forms - Added extra constants for the payment statuses
- Gravity Forms - Improved the way we update the entry payment status
- WooCommerce - Added WooCommerce utility base class
- Jigoshop - Added Jigoshop utility base class
- OmniKassa - Changed the use of the site_url() function, now called with an slash

## [beta-0.9.4] - 2012-04-27
- Fixed issue with the use of the [dbDelta](http://codex.wordpress.org/Creating_Tables_with_Plugins) function wich was causing "WordPress database error: [Multiple primary key defined]" errors
- Fixed check on (un)paid Shopp 1.2+ purchases, in some way this was mixed up.
- Gravity Forms - Added field type title for the issuer drop down field
- Gravity Forms - Changed Dutch translation of "Issuer Drop Down" field to "Banken uitschuifkeuzelijst"
- Gravity Forms - Fixed redirecting issue with an AJAX enabled Gravity Forms form
- ClassiPress - Added experimental iDEAL gateway

## [beta-0.9.3] - 2012-04-17
- Added some icons for the iDEAL banks to create a nicer issuer select element
- Added exprimental support for ClassiPress
- Added Gravity Forms delay admin notification option
- Added Gravity Forms delay user notification option
- Added Gravity Forms delay post creation option

## [beta-0.9.2] - 2012-04-05
- Fixed line delimters wich was causing unexpected T_CLASS error in classes/Pronamic/WPeCommerce/IDeal/AddOn.php 
- Added an array_filter() to the optional OmniKassa data fields
- Fixed an issue in the Shopp Add-On caused by an Shopp bug (see ticket https://shopp.lighthouseapp.com/projects/47561/tickets/1536-shoppcheckoutoffline-instructions-tag-seemingly-absent-from-12)
- Added support plugins section to the settings page

## [beta-0.9.1] - 2012-03-30
- Added an overview of the registered hashing algorithms and check for sha1 algorithm
- Fixed plugins_url() function call in the WP e-Commerce Add-On
- Fixed retrieving succes URL in the Gravity Forms Add-On from an iDEAL feed 
- Fixed edit order link in check iDEAL payment e-mail in the WooCommerce Add-On
- Added check for unavailable or removed iDEAL variant in Gravity Forms iDEAL feed editor

## [beta-0.9] - 2012-03-22
- Added an data proxy class, all add-ons are now optimized
- Added OmniKassa support for all add-ons
- Added support for the WP e-Commerce plugin
- Improved the redirection if returned from an iDEAL advanced payment to WooComnmerce
- Changed the text domain from 'pronamic-ideal' to 'pronamic_ideal'
- Replaced all references to class constant TEXT_DOMAIN to an string

## [beta-0.8.6] - 2012-02-17
- Added documentation for the Rabobank OmniKassa payment method
- Added documentation for the ABN AMRO iDEAL Only Kassa payment method
- WooCommerce iDEAL payment orders now get the status "on-hold" instead of "pending" 
- Changed WooCommerce class 'woocommerce_order' to 'WC_Order'
- Changed WooCommerce class 'woocommerce_payment_gateway' to 'WC_Payment_Gateway'
- Replaced get_permalink(get_option('woocommerce_pay_page_id')) with get_permalink(woocommerce_get_page_id('pay'))
- WooCommerce iDEAL Easy and Basic payments send an check payment mail and add note to order

## [beta-0.8.5] - 2012-01-18
- Fixed an nonce check wich was causing a lot "Are you sure you want to do this?" notices
- Added an uninstall hook wich will delete all extra database tables and options
- Removed the custom made uninstall block from the iDEAL configurations page
- Fixed database errors / unexpected output while installing the plugin
- Pages generator will now generate pages with comments closed by default

## [beta-0.8.4] - 2012-01-17
- Shopp - Fixed fatal error in Shopp 1.2RC1, the $registry variable in the Settings class is declared as private in version 1.2RC1
- Gravity Forms - Added link to Gravity Forms entry / lead details on the iDEAL payments page
- Shopp - Improved the not paid check for the Shopp iDEAL gateway module, now also works with 1.2+
- Shopp - Changed function for store front JavaScript from 'sanitize_title_with_dashes' to 'sanitize_key'
- Shopp - Changed the 'shopp_order_success' action functions, in 1.2+ the purchase parameter was removed
- Fixed notice and bug while generating security certificates and keys
- Added mandatory tests for the iDEAL advanced variants

## [beta-0.8.3] - 2012-01-04
- Fixed notice wp_register_style was called incorrectly in admin
- Fixed notice undefined variable: nl2br in the Gravity Forms Add-On
- Fixed issue in WooCommerce with building the iDEAL basic succes URL, Google Analytics e-commerce tracking was not possible
- Fixed issue with the purchase ID passing through to iDEAL
- Added extra description to the hash key field on the iDEAL configuration edit page 
- Removed the maxlength="32" attribute from the Gravity Forms iDEAL feed transaction description field
- Now it is also possible to search on the amount of an payment
- Moved the return from iDEAL routine form the 'parse_query' routine to the 'template_redirect' routine
- Improved the 'pronamic_ideal_return' and 'pronamic_ideal_status_update' routines with an 'can redirect' paramter
- Improved the status update of WooCommerce and Jigoshop orders after an failure status update (from expired to failed)
- Improved the scheduling of status requests of iDEAL advanced payments
- Fixed the notice if no status pages or URL's are configured in an Gravity Forms iDEAL feed

## [beta-0.8.2] - 2011-12-12
- Replaced the DateTime::getTimestamp() call (PHP 5 >= 5.3.0) with DateTime::format('U') (PHP 5 >= 5.2.0)
- Removed the addItem function from the iDEAL basic class and added an extra items class
- Improved the way we load iDEAL items in the Gravity Forms iDEAL Add-On
- Improved the Shopp add_storefrontjs script to hide / show the iDEAL fields
- Added the ABN AMRO iDEAL Easy variant
- Improved and fixed the WooCommerce iDEAL gateway, the status of orders is now set to pending iDEAL payment
- Moved all the documentation files to an external server, plugin is now much smaller (from 20 MB to 3 MB)

## [beta-0.8.1] - 2011-11-17
- Fixed an issue with the WooCommerce iDEAL gateway, the order status is now updated
- Improved the payment status update of the Shopp and Gravity Forms add-ons

## [beta-0.8] - 2011-11-10
- Fixed an issue with the success, cancel and error URL's in the iDEAL lite variant gateway for WooCommerce
- Added support for the [Shopp plugin](http://shopplugin.net/)
- Added search box on the payments page so you can search on transaction ID, consumer name, account number and city
- Adjusted the default expiration date modifier from +1 hour to +30 minutes

## [beta-0.7.2] - 2011-11-07
- Fixed an issue with character set in the iDEAL lite variant with the decoding of HTML entities
- Added the current time to the iDEAL settings page so users can easily check the server time
- Improved the expiration date of the iDEAL lite variant, now uses UTC timezone
- Changed the iDEAL date format, the Z stands for the timezone offset and should not be the Z character
- Changed the database column width of the entrance code to 40 instead of 32
- For WooCommerce iDEAL Lite payment we now only add one total item, otherwise the cart discount amount is an issue

## [beta-0.7.1] - 2011-10-31
- Fixed issue with loading JavaScripts on the Gravity Forms edit / new form page
- Added some extra data length checks for iDEAL lite payments
- Added an extra role iDEAL Administrator, so you can outsource the configuration of iDEAL
- Added extra capabilities so you can easily grant users to the iDEAL admin pages
- Fixed bug with ordering the iDEAL payment by date
- Added an pages generator to easily create pages for each iDEAL payment status

## [beta-0.7] - 2011-10-21
- Added support for the WooCommerce WordPress plugin
- Improved the payments repository class
- Improved the payments overview page (now with pagination)
- Improved the loading of the admin JavaScripts and stylesheets

## [beta-0.6.2] - 2011-09-15
- Gravity Forms iDEAL Lite button is now translatable
- For iDEAL Lite you can now easily run the mandatory tests
- Added an private key and certificate generator

## [beta-0.6.1] - 2011-09-15
- Improved the calculation of the Gravity Forms price fields and the total amount 

## [beta-0.6] - 2011-09-15
- Added and activated some extra iDEAL banks / variants
- Enabled the Transient API for retrieving the issuers lists, was temporary disabled for debugging

## [beta-0.5] - 2011-09-07
- Fixed some security issues

## [beta-0.4] - 2011-09-07
- Improved the retrieving of license information from the license provider
- Added some extra admin CSS styling like an iDEAL screen icon

## [beta-0.3] - 2011-09-06
- Improved the Gravity Forms confirmation message when an iDEAL basic payment is executed, now working correct with AJAX
- Improved the iDEAL configuration editor and the Gravity Forms iDEAL feed editor, display only necessary input fields
- Fixed the Rabobank iDEAL payment server URLs in the ideal.xml configuration file

## [beta-0.2] - 2011-08-30
- Removed all PHP 5.3+ namespace declarations, the plugin should now be compatible with PHP 5.2+
- Fixed the link to the payment detail page in WordPress admin
- Fixed the link on the payment detail page to the iDEAL configuration page
- Fixed redirection problem with AJAX enabled Gravity Forms  

## [beta-0.1] - 2011-08-20
- Issuers list transient is now deleted after updating an iDEAL configuration
- Added the issuers list to the iDEAL configuration tests page
- The Gravity Forms iDEAL Issuer Drop Down remembers 
- Description and entrance code are automatically truncated on the maximum length
- Added better checks on the output of the OpenSSL functions to prefend PHP warnings
- Use the generic hash() function instead of the md5() and sha1() functions
- Added transaction description to the GravityForms iDEAL feed
- Fixed bug with retrieving feeds with no form or configuration attached (inner join - left join)
- Fixed issue with saving new iDEAL configurations and GravityForms iDEAL feeds
- Added wp_nonce_field() and check_admin_referer() functions to the forms to avoid security exploits
- Improved the feeds repository and the feed model
- Initial release

[unreleased]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.0.0...HEAD
[4.0.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.9.0...4.0.0
[3.9.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.9...3.9.0
[3.8.9]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.8...3.8.9
[3.8.8]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.7...3.8.8
[3.8.7]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.6...3.8.7
[3.8.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.5...3.8.6
[3.8.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.4...3.8.5
[3.8.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.3...3.8.4
[3.8.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.2...3.8.3
[3.8.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.1...3.8.2
[3.8.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.8.0...3.8.1
[3.8.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.7.3...3.8.0
[3.7.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.7.2...3.7.3
[3.7.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.7.1...3.7.2
[3.7.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.7.0...3.7.1
[3.7.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.6.6...3.7.0
[3.6.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.6.5...3.6.6
[3.6.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.6.4...3.6.5
[3.6.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.6.3...3.6.4
[3.6.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.6.2...3.6.3
[3.6.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.6.1...3.6.2
[3.6.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.6.1...3.6.1
[3.6.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.5.2...3.6.1
[3.5.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.5.1...3.5.2
[3.5.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.5.0...3.5.1
[3.5.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.4.2...3.5.0
[3.4.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.4.1...3.4.2
[3.4.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.4.0...3.4.1
[3.4.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.3.3...3.4.0
[3.3.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.3.2...3.3.3
[3.3.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.3.1...3.3.2
[3.3.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.3.0...3.3.1
[3.3.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.2.4...3.3.0
[3.2.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.2.3...3.2.4
[3.2.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.2.2...3.2.3
[3.2.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.2.1...3.2.2
[3.2.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.2.0...3.2.1
[3.2.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.1.5...3.2.0
[3.1.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.1.4...3.1.5
[3.1.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.1.3...3.1.4
[3.1.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.1.2...3.1.3
[3.1.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.1.1...3.1.2
[3.1.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.1.0...3.1.1
[3.1.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/3.0.0...3.1.0
[3.0.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.9.4...3.0.0
[2.9.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.9.3...2.9.4
[2.9.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.9.2...2.9.3
[2.9.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.9.1...2.9.2
[2.9.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.9.0...2.9.1
[2.9.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.9...2.9.0
[2.8.9]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.8...2.8.9
[2.8.8]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.7...2.8.8
[2.8.7]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.6...2.8.7
[2.8.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.5...2.8.6
[2.8.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.4...2.8.5
[2.8.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.3...2.8.4
[2.8.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.2...2.8.3
[2.8.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.1...2.8.2
[2.8.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.8.0...2.8.1
[2.8.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.7.7...2.8.0
[2.7.7]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.7.6...2.7.7
[2.7.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.7.5...2.7.6
[2.7.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.7.4...2.7.5
[2.7.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.7.3...2.7.4
[2.7.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.7.2...2.7.3
[2.7.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.7.1...2.7.2
[2.7.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.7.0...2.7.1
[2.7.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.9...2.7.0
[2.6.9]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.8...2.6.9
[2.6.8]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.7...2.6.8
[2.6.7]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.6...2.6.7
[2.6.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.5...2.6.6
[2.6.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.4...2.6.5
[2.6.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.3...2.6.4
[2.6.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.2...2.6.3
[2.6.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.1...2.6.2
[2.6.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.6.0...2.6.1
[2.6.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.5.0...2.6.0
[2.5.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.4.3...2.5.0
[2.4.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.4.2...2.4.3
[2.4.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.4.1...2.4.2
[2.4.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.4.0...2.4.1
[2.4.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.3.1...2.4.0
[2.3.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.3.0...2.3.1
[2.3.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.2.4...2.3.0
[2.2.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.2.3...2.2.4
[2.2.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.2.2...2.2.3
[2.2.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.2.1...2.2.2
[2.2.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.2.0...2.2.1
[2.2.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.0.7...2.1.0
[2.0.7]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.0.6...2.0.7
[2.0.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.0.5...2.0.6
[2.0.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.0.4...2.0.5
[2.0.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.0.3...2.0.4
[2.0.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.3.4...2.0.0
[1.3.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.3.3...1.3.4
[1.3.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.3.2...1.3.3
[1.3.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.3.1...1.3.2
[1.3.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.11...1.3.0
[1.2.11]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.10...1.2.11
[1.2.10]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.9...1.2.10
[1.2.9]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.8...1.2.9
[1.2.8]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.7...1.2.8
[1.2.7]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.6...1.2.7
[1.2.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.5...1.2.6
[1.2.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.4...1.2.5
[1.2.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.3...1.2.4
[1.2.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.2...1.2.3
[1.2.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.2...1.2.1
[1.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.1.1...1.2
[1.1.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.1...1.1.1
[1.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/1.0...1.1
[1.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.11.1...1.0
[beta-0.11.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.11.0...beta-0.11.1
[beta-0.11.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.10.1...beta-0.11.0
[beta-0.10.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.10...beta-0.10.1
[beta-0.10]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9.9...beta-0.10
[beta-0.9.9]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9.8...beta-0.9.9
[beta-0.9.8]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9.7...beta-0.9.8
[beta-0.9.7]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9.6...beta-0.9.7
[beta-0.9.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9.5...beta-0.9.6
[beta-0.9.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9.4...beta-0.9.5
[beta-0.9.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9.3...beta-0.9.4
[beta-0.9.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9.2...beta-0.9.3
[beta-0.9.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9.1...beta-0.9.2
[beta-0.9.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.9...beta-0.9.1
[beta-0.9]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.8.6...beta-0.9
[beta-0.8.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.8.5...beta-0.8.6
[beta-0.8.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.8.4...beta-0.8.5
[beta-0.8.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.8.3...beta-0.8.4
[beta-0.8.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.8.2...beta-0.8.3
[beta-0.8.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.8.1...beta-0.8.2
[beta-0.8.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.8...beta-0.8.1
[beta-0.8]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.7.2...beta-0.8
[beta-0.7.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.7.1...beta-0.7.2
[beta-0.7.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.7...beta-0.7.1
[beta-0.7]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.6.2...beta-0.7
[beta-0.6.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.6.1...beta-0.6.2
[beta-0.6.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.6...beta-0.6.1
[beta-0.6]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.5...beta-0.6
[beta-0.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.4...beta-0.5
[beta-0.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.3...beta-0.4
[beta-0.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.2...beta-0.3
[beta-0.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/beta-0.1...beta-0.2
