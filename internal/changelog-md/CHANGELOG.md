# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased] - 2015-08-21

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

## [test] - 2015-02-12
- 
- 

