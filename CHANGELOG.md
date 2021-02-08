# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased][unreleased]

## [6.6.4] - 2021-02-08

### Fixed
- Updated WordPress pay Mollie library to version 2.2.2.
  - Fixed "Error validating `/locale`: The property `locale` is required" on some status update (https://github.com/mollie/api-documentation/pull/731).
- Updated WordPress pay MemberPress library to version 2.2.3.
  - Fixed showing payment method specific input fields.

### Changed
- Updated WordPress pay Payvision library to version 1.1.0.
  - Added transaction description.
  - Added advanced purchase ID setting.

## [6.6.3] - 2021-01-21

### Changed
- Updated WordPress pay core library to version 2.6.2.
  - Happy 2021.
  - Added debug mode setting.
  - Improved setting `utm_nooverride` parameter in redirect URL.
- Updated WordPress pay OmniKassa 2.0 library to version 2.3.1.
  - Updated check for response object in client request.
- Updated WordPress pay Formidable Forms library to version 2.2.1.
  - Fixed using undefined variable.
  - Removed debug code.
- Updated WordPress pay Ninja Forms library to version 1.4.0.
  - Added gateway configuration setting to form action.

## [6.6.2] - 2021-01-19

### Fixed
- Updated WordPress pay Event Espresso (legacy) library to version 2.3.2.
  - Fixed using unknown classes.

## [6.6.1] - 2021-01-18

### Changed
- Updated WordPress pay core library to version 2.6.1.
  - Added support for recurring payments with Apple Pay.
- Updated WordPress pay Mollie library to version 2.2.1.
  - Added support for first payment with regular iDEAL/Bancontact/Sofort payment methods.
  - Added support for recurring payments with Apple Pay.
  - Added 'Change Payment State' URL to Mollie payment admin page.
  - Chargebacks now update subscriptions status to 'On hold' (needs manual review).
- Updated WordPress pay MultiSafepay library to version 2.1.2.
  - Added support for In3 payment method.
  - Added partial support for Santander 'Betaal per maand' payment method.
- Updated WordPress pay Payvision library to version 1.0.1.
  - Added business ID to gateway ID column in payments overview.
- Updated WordPress pay Event Espresso (legacy) library to version 2.3.1.
  - Fixed syntax errors.
- Updated WordPress pay MemberPress library to version 2.2.2.
  - Added support for recurring payments with Apple Pay.
  - Updated payment method icons to use wp-pay/logos library.

## [6.6.0] - 2021-01-14

### Changed
- Updated WordPress pay core library to version 2.6.0.
  - Payment Gateway Referral Exclusions in Google Analytics.
  - Added Santander payment method.
  - Ask for confirmation before manually cancelling a subscription.
  - Redirect to new 'Subscription Canceled' status page after cancelling subscriptions.
  - Fixed updating subscription dates on next period payment creation.
  - Only add user agent in payment info meta box if not empty.
  - Added feature to manually start the next subscription payment.
- Updated WordPress pay Charitable library to version 2.2.1.
  - Improved donation total amount value retrieval.
  - Improved user data support, set adress line 2 and country code.
- Updated WordPress pay Contact Form 7 to version 1.0.3.
  - Fix redirecting when scripts are disabled through `wpcf7_load_js` filter.
- Updated WordPress pay Formidable Forms to version 2.2.0.
  - Simplified icon hover style.
  - Updated form action icon.
  - Added support for form settings redirect success URL.
- Updated WordPress pay Ninja Forms to version 1.3.0.
  - Fixed notice payment redirect URL.
- Updated WordPress pay Restrict Content Pro to version 2.3.1.
  - Renew inactive membership on successful (retry) payment.
  - Fix not using checkout label setting.
- Updated WordPress pay s2Member to version 2.2.1.
  - Prevent updating eot if (retry) payment period end date is (before) current eot time.
  - Fix using removed payment data class and multiple status update actions.
  - Fix setting subscription next payment date for new subscriptions (removes payment data class).
- Updated WordPress pay WooCommerce to version 2.2.1.
  - Updated logo library to version 1.6.3 for new iDEAL logo.
  - Start subscription payment through subscription module instead of plugin.
  - Move info message up on thank you page.
  - Add Santander payment method.

## [6.5.1] - 2020-11-19

### Fixed
- Updated WordPress pay core library to version 2.5.1.
  - Fixed always setting payment customer details.
  - Fixed setting currency in payment lines amount.
- Updated WordPress pay Gravity Forms library to version 2.5.1.
  - Updated getting subscription from payment period.

### Changed
- Updated WordPress pay Adyen library to version 1.2.1.
  - Removed unused configuration to store card details.

## [6.5.0] - 2020-11-18

### Added
- Added support for Payvision gateway (requires Basic license).

### Fixed
- Updated WordPress pay iDEAL Advanced library to version 2.1.3.
  - Fix regression in payment status retrieval.

### Removed
- Removed deprecated Fibonacci ORANGE gateway.
- Removed deprecated Mollie iDEAL gateway.
- Removed deprecated Nocks gateway.
- Removed deprecated Rabobank OmniKassa gateway.

## [6.4.1] - 2020-11-10

### Fixed
- Updated WordPress pay iDEAL Advanced library to version 2.1.2.
  - Fixed acquirer URL.
- Updated WordPress pay iDEAL Basic library to version 2.1.2.
  - Fixed acquirer URL.

## [6.4.0] - 2020-11-09

### Changed
- Updated WordPress pay core library to version 2.5.0.
  - Added support for subscription phases.
  - Added support for Przelewy24 payment method.
  - Improved data stores, reuse data from memory.
  - Catch money parser exceptions in blocks.
  - Introduced some traits for the DRY principle.
  - Payments can be linked to multiple subscription periods.
  - Improved support for subscription alignment and proration.
  - Added REST API endpoint for subscription phases.
  - Removed `$subscription->get_total_amount()` in favor of getting amount from phases.
  - Removed ability to manually change subscription amount for now.
  - No longer start recurring payments for expired subscriptions.
- Updated WordPress pay Adyen library to version 1.2.0.
  - Added REST route permission callbacks.
- Updated WordPress pay Mollie library to version 2.2.0.
  - Added Przelewy24 payment method.
  - Added REST route permission callback.
  - Improved determining customer if previously used customer has been removed at Mollie.
  - Fixed filtering next payment delivery date.
  - Fixed incorrect check for failed payment bank reason detail.
- Updated WordPress pay Nocks library to version 2.2.0.
  - Deprecated gateway as Nocks no longer exists (https://guldenbites.com/2020/05/15/nocks-announcement/).
- Updated WordPress pay OmniKassa 2.0 library to version 2.3.0.
  - Switched to REST API for webhook.
  - Catch input JSON validation exception in webhook listener.
- Updated WordPress pay Pay.nl library to version 2.1.1.
  - Limited first and last name to 32 characters.
- Updated WordPress pay Charitable library to version 2.1.3.
  - Improved getting user data from donation.
- Updated WordPress pay Contact Form 7 library to version 1.0.2.
  - Fixed getting amount from free text value.
- Updated WordPress pay Formidable Forms library to version 2.1.4.
  - Improved error handling on payment start.
  - Fixed incorrect amount when using product fields.
- Updated WordPress pay Gravity Forms library to version 2.5.0.
  - Changed 'Frequency' to 'Number of Periods' in payment feed subscription settings.
  - Changed 'Synchronized payment date' to 'Fixed Subscription Period' in payment feed subscription settings.
  - Places Euro symbol left of amount in Gravity Forms currency when using Dutch language.
  - Added Dutch address notation for Gravity Forms.
  - Added support for new subscription phases and periods.
  - Fixed unselected options in payment method selector after processing conditional logic.
- Updated WordPress pay MemberPress library to version 2.2.0.
  - Added Przelewy24 payment method.
  - Added support for new subscription phases and periods.
  - Added support for trials and (prorated) upgrades/downgrade.
  - Set Pronamic Pay subscription on hold if non-recurring payment fails.
- Updated WordPress pay Restrict Content Pro library to version 2.3.0.
  - Changed setting the next payment date 1 day earlier, to prevent temporary membership expirations.
  - No longer mark Pronamic Pay subscriptions as expired when a Restrict Content Pro membership expires.
  - Added support for new subscription phases and periods.
  - Added support for trials to credit card and direct debit methods.
  - Added support for payment fees.
- Updated WordPress pay s2Member library to version 2.2.0.
  - Added support for new subscription phases and periods.
  - Fixed processing list servers for recurring payments.
- Updated WordPress pay WooCommerce library to version 2.2.0.
  - Updated iDEAL logo.
  - Added Przelewy24 payment method.
  - Added support for new subscription phases and periods.
  - Fixed incorrect 'Awaiting payment' order note for recurring payments in some cases.
  - Fixed using default payment description if setting is empty.

## [6.3.2] - 2020-08-05

### Fixed
- Updated WordPress pay MemberPress library to version 2.1.3.
  - Fixed reactivating cancelled MemberPress subscription when pending recurring payment completes.
- Updated WordPress pay WooCommerce library to version 2.1.4.
  - Fixed possible error on WooCommerce products admin page.

## [6.3.1] - 2020-07-23

### Fixed
- Updated WordPress pay core library to version 2.4.1.
  - Added email address as fallback for customer name in payments and subscriptions overview and details.
  - Fixed using deprecated `email` and `customer_name` properties.
- Updated WordPress pay Restrict Content Pro library to version 2.2.3.
  - Fixed possible 'Fatal error: Call to a member function `get_id()` on null'.
- Updated WordPress pay s2Member library to version 2.1.3.
  - Fixed creating empty subscriptions.
- Updated WordPress pay WooCommerce library to version 2.1.3.
  - Fixed compatibility with WooCommerce EU VAT Number plugin.

## [6.3.0] - 2020-07-08

### Added
- Added support for Contact Form 7 plugin (requires Basic license).

### Changed
- Updated WordPress pay core library to version 2.4.0.
  - Added support for customer company name.
  - Added support for updating subscription mandate.
  - Added support for VAT number (validation via VIES).
  - Added `get_pronamic_subscriptions_by_source()` function.
  - Fixed possible duplicate payment on upgrade if pending recurring payment exists.
  - Fixed updating subscription status to 'On Hold' only if subscription is not already active, when processing first payment.
  - Improved subscription date calculations.
  - Updated admin tour.
- Updated WordPress Money library to version 1.2.5.
  - Added support for parsing negative amounts and `5,-` notation for amounts without minor units.
  - Updated currency symbols.
- Updated WordPress pay Adyen library to version 1.1.2.
  - Fixed possible conflicting payments caused by double clicking submit button.
  - Removed empty meta data from payment request JSON.
- Updated WordPress pay Mollie library to version 2.1.4.
  - Added filter `pronamic_pay_mollie_payment_metadata` for Mollie payment metadata.
  - Added support for updating subscription mandate.
- Updated WordPress pay Ingenico library to version 2.1.1.
  - Added exception for Ingenico error when retrieving order status.
- Updated WordPress pay OmniKassa 2.0 library to version 2.2.4.
  - Switched to new endpoint at `/order/server/api/v2/order`.
  - Removed obsolete update of payment transaction ID.
- Updated WordPress pay Easy Digital Downloads library to version 2.1.2.
  - Added support for company name and VAT number from the custom Pronamic EDD plugins.
  - Fixed registering `cancelled` post status for use in EDD payments table view filters.
- Updated WordPress pay Gravity Forms library to version 2.4.1.
  - Added support for company name and VAT number.
  - Improved Gravity Forms 2.5 beta compatibility.
- Updated WordPress pay Restrict Content Pro library to version 2.2.2.
  - Added support for subscription frequency.
  - Fixed using existing subscription for membership.
  - Fixed expiring membership if first payment expires but subscription is already active.
- Updated WordPress pay WooCommerce library to version 2.1.2.

## [6.2.0] - 2020-06-03

### Changed
- Updated WordPress pay core library to version 2.3.2.
  - Add support for new fundraising add-on (requires Pro license).
  - Add payment origin post ID.
  - Add 'Pronamic Pay' block category.
  - Fix subscriptions without next payment date.
  - Fix incorrect formatted amount in payment form block.
- Updated WordPress pay Mollie library to version 2.1.3.
  - Add support for Mollie payment billing email and filter `pronamic_pay_mollie_payment_billing_email`.
- Updated WordPress pay OmniKassa 2.0 library to version 2.2.3.
  - Fix incorrect payments order when handling order status notifications.
- Updated WordPress pay Charitable library to version 2.1.2.
  - Add telephone number to payment data.
  - Fix error handling.
- Updated WordPress pay Gravity Forms library to version 2.4.0.
  - Add filter `pronamic_pay_gravityforms_delay_actions` for delayed actions.
  - Fix empty formatted amount in entry notes if value is `0`.
- Updated WordPress pay MultiSafepay library to version 2.1.1.
- Updated WordPress pay Formidable Forms library to version 2.1.3.
- Updated WordPress pay s2Member library to version 2.1.2.

## [6.1.2] - 2020-04-20

### Fixed
- Updated WordPress pay Buckaroo library to version 2.1.1.
  - Fixed HTML entities in payment description resulting in invalid signature error.
- Updated WordPress pay EMS e-Commerce; library to version 2.1.1.
  - Fixed incorrect default tag in description of Order ID settings field.
- Updated WordPress pay Gravity Forms library to version 2.3.1.
  - Fixed PHP notices and warnings.
  - Use integration version number for scripts and styles.
- Updated WordPress pay MemberPress library to version 2.1.2.
  - Fixed setting `complete` transaction status to `pending` again on free downgrade.

### Changed
- Updated WordPress pay Adyen library to version 1.1.1.
  - Fixed not using billing address country code on drop-in payment redirect page.
  - Added support for payment metadata via `pronamic_pay_adyen_payment_metadata` filter.
  - Added advanced gateway configuration setting for `merchantOrderReference` parameter.
  - Added browser information to payment request.
  - Removed shopper reference from payment request.
  - Removed payment status request from drop-in gateway supported features.
- Updated WordPress pay OmniKassa 2.0 library to version 2.2.2.
  - Improved webhook handling if multiple gateway configurations exist.
- Updated WordPress pay Formidable Forms library to version 2.1.2.
  - Updated settings description for delaying email notifications.

## [6.1.1] - 2020-04-06

### Fixed
- Updated deployment script.

## [6.1.0] - 2020-04-06

### Fixed
- Updated WordPress pay Charitable library to version 2.1.1.
  - Fixed incorrect currency symbol filter.
- Updated WordPress pay MemberPress library to version 2.1.1.
  - Fixed "PHP Warning: call_user_func() expects parameter 1 to be a valid callback".

### Changed
- Updated WordPress pay core library to version 2.3.1.
  - Added optional `$args` parameter to `get_pronamic_payment_by_meta()` function.
  - Added active plugin integrations to Site Health debug fields.
  - Fixed unnecessarily showing upgrade button in new installations.
- Updated WordPress pay Mollie library to version 2.1.2.
  - Fixed install issues on some specific WordPress installations.
  - Add initial Apple Pay support.
- Updated WordPress pay OmniKassa 2.0 library to version 2.2.1.
  - Improved webhook handling if multiple payments exist with same merchant order ID.
- Updated WordPress pay Easy Digital Downloads library to version 2.1.1.
  - Improved tax support for Easy Digital Downloads 3.0.
- Updated WordPress pay Gravity Forms library to version 2.3.0.
  - Added payment feed fields settings to auto detect first visible field of type in entry.
  - Added `pronamic_pay_again_url` merge tag, which can be used to pre-populate form after failed payment.
  - Fixed "Warning: Invalid argument supplied for foreach()" in calculation variables select.
  - Improved payment feed conditions with support for all fields and multiple rules.
  - Improved forms list performance.
- Updated WordPress pay Ninja Forms library to version 1.2.0.
  - Added payment status page action settings.
  - Updated action redirect to use payment redirect URL.
- Updated WordPress pay Restrict Content Pro library to version 2.2.1.
  - Improved CLI commands.
  - Improved 2.1.6 upgrade.
- Updated WordPress pay Event Espresso library to version 2.2.1.
- Updated WordPress pay Event Espresso (legacy) library to version 2.2.1.
- Updated WordPress pay Formidable Forms library to version 2.1.1.
- Updated WordPress pay Give library to version 2.1.1.
- Updated WordPress pay s2Member library to version 2.1.1.
- Updated WordPress pay WooCommerce library to version 2.1.1.
- Updated WordPress pay WP eCommerce library to version 2.1.1.

## [6.0.2] - 2020-03-26

### Fixed
- Updated WordPress pay iDEAL Advanced v3 library to version 2.1.1.
  - Fix incomplete gateway settings.
- Updated WordPress pay iDEAL Basic library to version 2.1.1.
  - Fix incomplete gateway settings.

## [6.0.1] - 2020-03-19

### Fixed
- Updated WordPress pay Mollie library to version 2.1.1.
  - Force a specific collate to fix "Illegal mix of collations" error.

## [6.0.0] - 2020-03-19

### Changed
- Updated WordPress pay core library to version 2.3.0.
  - Added Google Pay support.
  - Added Apple Pay payment method.
  - Added support for payment failure reason.
  - Added input fields for consumer bank details name and IBAN.
  - Simplify recurrence details in subscription info meta box.
  - Fixed setting initials if no first and last name are given.
  - Abstracted plugin and gateway integration classes.
- Updated WordPress pay Easy Digital Downloads library to version 2.1.0.
  - Update integration setup with dependencies support.
  - Set Easy Digital Downloads payment status to 'cancelled' in case of a cancelled payment.
  - Extend `Extension` class from `AbstractPluginIntegration`.
- Updated WordPress pay Gravity Forms library to version 2.2.0.
  - Added consumer bank details name and IBAN field settings.
  - Fixed adding payment line for shipping costs only when shipping field is being used.
  - Fixed dynamically setting selected payment method.
  - Fixed feed activation toggle.
  - Improved field visibility check with entry.
  - Improved payment methods field choices in field input (fixes compatibility with `Gravity Forms Entries in Excel` plugin).
  - Extension extends abstract plugin integration with dependency.
- Updated WordPress pay Ninja Forms library to version 1.1.0.
  - Fix incorrect selected payment method in payment methods fields when editing entry.
- Updated WordPress pay WooCommerce library to version 2.1.0.
  - Update integration setup with dependencies support.
  - Use SVG icons.
  - Add Apple Pay payment method.
  - Extension extends \Pronamic\WordPress\Pay\AbstractPluginIntegration.
  - Added Google Pay support.
- Updated WordPress pay Adyen library to version 1.1.0.
  - Fixed unnecessarily showing additional payment details screen in some cases.
  - Only create controllers and actions when dependencies are met.
  - Added Google Pay support.
  - Added Apple Pay support.
- Updated WordPress pay ICEPAY library to version 2.1.0.
  - Fixed "$result is always a sub-type of Icepay_Result".
- Updated WordPress pay Mollie library to version 2.1.0.
  - Added custom tables for Mollie profiles, customers and WordPress users.
  - Added experimental CLI integration.
  - Moved webhook logic to REST API.
  - Improved WordPress user profile Mollie section.
  - Added WordPress admin dashboard page for Mollie customers.
  - Added support for one-off SEPA Direct Debit payment method.
  - Added support for payment failure reason.

## [5.9.0] - 2020-02-03

### Changed
- Updated WordPress pay core library to version 2.2.7.
  - Added Google Analytics e-commerce `pronamic_pay_google_analytics_ecommerce_item_name` and `pronamic_pay_google_analytics_ecommerce_item_category` filters.
  - Improved error handling for manual payment status check.
  - Updated custom gender and date of birth input fields.
  - Clean post cache to prevent duplicate status updates.
  - Fixed duplicate payment for recurring payment.
- Updated WordPress pay Easy Digital Downloads library to version 2.0.7.
  - Improved custom input fields HTML markup and validation.
- Updated WordPress pay Gravity Forms library to version 2.1.15.
  - Only prorate subscription amount when form field has been set for recurring amount.
  - Fixed incorrect currency with multicurrency add-on.
  - Fixed subscription start with zero interval days.
- Updated WordPress pay Adyen library to version 1.0.6.
  - Added support for Drop-in integration (requires 'Origin Key' in gateway settings).
  - Added application info support.
- Updated WordPress pay MultiSafepay library to version 2.0.6.
  - Improved error handling.
- Updated Pronamic WordPress Money library to version 1.2.4.

### Fixed
- Updated WordPress pay Charitable library to version 2.0.4.
  - Fixed processing decimal input amounts.
- Updated WordPress pay MemberPress library to version 2.0.13.
  - Explicitly set transaction expiry date.
- Updated WordPress pay Restrict Content Pro library to version 2.1.7.
  - Fixed possible 'Fatal error: Call to a member function `get_user_id()` on boolean' in updater.
- Updated WordPress pay Mollie library to version 2.0.10.
  - Fixed notice 'Not Found - No customer exists with token cst_XXXXXXXXXX' in some cases.

### Removed
- Removed AppThemes integration.
- Removed ClassiPress integration.
- Removed iThemes Exchange integration (plugin is now Ninja Shop and not supported anymore).
- Removed Jigoshop integration (plugin not under active development anymore).
- Removed Shopp integration (plugin not under active development anymore).
- Removed WPMU DEV Membership integration (plugin has been retired; see https://premium.wpmudev.org/retiring-our-legacy-plugins/).

## [5.8.1] - 2020-01-08

### Changed
- Updated WordPress pay core library to version 2.2.6.
  - Added filter `pronamic_payment_gateway_configuration_id` for payment gateway configuration ID.
  - Added filter `pronamic_pay_return_should_redirect` to move return checks to gateway integrations.
  - Added Polylang home URL support in payment return URL.
  - Added user display name in payment info meta box.
  - Added consumer and bank transfer bank details.
  - Added support for payment expiry date.
  - Added support for gateway manual URL.
  - Added new dependencies system.
  - Added new upgrades system.
  - Fixed incorrect day of month for yearly recurring payments when using synchronized payment date.
  - Fixed not starting recurring payments for gateways which don't support recurring payments.
  - Fixed default payment method in form processor if required.
  - Fixed empty dashboard widgets for untranslated languages.
  - Fixed submit button for manual subscription renewal.
  - Fixed duplicate currency symbol in payment forms.
  - Fixed stylesheet on payment redirect.
  - Improved payment methods tab in gateway settings.
  - Improved updating active payment methods.
  - Improved error handling with exceptions.
  - Improved update routine.
  - Set subscription status 'On hold' for cancelled and expired payments.
  - Do not auto update subscription status when status is 'On hold'.
  - Renamed 'Expiry Date' to 'Paid up to' in subscription info meta box.
- Updated WordPress pay Adyen library to version 1.0.5.
  - Added Site Health test for HTTP authorization header.
  - Added URL to manual in gateway settings.
  - Added shopper email to payment request.
  - Improved support for PHP 5.6.
- Updated WordPress pay ING Kassa Compleet library to version 2.0.3.
  - Added support for payments without method specified.
  - Improved bank transfer payment instructions.
- Updated WordPress pay ICEPAY library to version 2.0.6.
  - Fixed processing ICEPAY postback.
- Updated WordPress pay Mollie library to version 2.0.9.
  - Added advanced setting for bank transfer due date days.
  - Added bank transfer recipient details to payment.
  - Removed Bitcoin payment method (not supported by Mollie anymore).
- Updated WordPress pay OmniKassa 2.0 library to version 2.1.10.
  - Added address fields validation.
- Updated WordPress pay Sisow library to version 2.0.4.
  - Added support for new `pronamic_pay_return_should_redirect` filter for notify and callback processing.
  - Improved status updates for payments without transaction ID (i.e. iDEAL QR and iDEAL without issuer).
  - Improved getting active payment methods for account.
- Updated WordPress pay Easy Digital Downloads library to version 2.0.6.
  - Added payment line ID support with variable price ID.
  - Improved output HTML to match Easy Digital Downloads.
- Updated WordPress pay Give library to version 2.0.4.
  - Updated gateway settings.
- Updated WordPress pay Gravity Forms library to version 2.1.14.
  - Added merge tags for bank transfer recipient details.
  - Added notice about subscription frequency being in addition to the first payment.
  - Fixed synchronized payment date monthday and month settings.
  - Improved payment method field creation.
- Updated WordPress pay Restrict Content Pro library to version 2.1.6.
  - Added support for new dependencies system.
  - Added support for new upgrades system.
  - Added upgrade script for payment and subscriptions source.
- Updated WordPress pay AppThemes library to version 2.0.4.
- Updated WordPress pay Buckaroo library to version 2.0.4.
- Updated WordPress pay Charitable library to version 2.0.3.
- Updated WordPress pay ClassiPress library to version 2.0.3.
- Updated WordPress pay EMS e-Commerce library to version 2.0.4.
- Updated WordPress pay Event Espresso library to version 2.1.3.
- Updated WordPress pay Event Espresso (legacy) library to version 2.1.2.
- Updated WordPress pay Formidable Forms library to version 2.0.4.
- Updated WordPress pay iDEAL Advanced v3 library to version 2.0.5.
- Updated WordPress pay iDEAL Basic library to version 2.0.5.
- Updated WordPress pay iThemes Exchange library to version 2.0.3.
- Updated WordPress pay Jigoshop library to version 2.0.4.
- Updated WordPress pay MemberPress library to version 2.0.12.
- Updated WordPress pay MultiSafepay library to version 2.0.5.
- Updated WordPress pay Ninja Forms library to version 1.0.3.
- Updated WordPress pay Nocks library to version 2.0.3.
- Updated WordPress pay Ogone library to version 2.0.4.
- Updated WordPress pay Pay.nl library to version 2.0.4.
- Updated WordPress pay s2Member library to version 2.0.5.
- Updated WordPress pay Shopp library to version 2.0.3.
- Updated WordPress pay TargetPay library to version 2.0.3.
- Updated WordPress pay WooCommerce library to version 2.0.10.
- Updated WordPress pay WP eCommerce library to version 2.0.4.
- Updated WordPress pay WPMU DEV Membership library to version 2.0.4.

## [5.8.0] - 2019-10-08

### Changed
- Updated WordPress pay core library to version 2.2.4.
  - Updated `viison/address-splitter` library to version `0.3.3`.
  - Move tools to site health debug information and status tests.
  - Read plugin version from plugin file header.
  - Catch money parser exception for test payments.
  - Sepereated `Statuses` class in `PaymentStatus` and `SubscriptionStatus` class.
  - Require `edit_payments` capability for payments related meta boxes on dashboard page.
  - Set menu page capability to minimum required capability based on submenu pages.
  - Only redirect to about page if not already viewed.
  - Removed Google +1 button.
  - Order payments by ascending date (fixes last payment as result in `Subscription::get_first_payment()`).
  - Added new WordPress Pay icon.
  - Added start, end, expiry, next payment (delivery) date to payment/subscription JSON.
  - Introduced a custom REST API route for payments and subscriptions.
  - Fixed handling settings field `filter` array.
  - Catch and handle error when parsing input value to money object fails (i.e. empty string).
  - Improved getting first subscription payment.
- Updated WordPress pay Adyen library to version 1.0.4.
  - Improved some exception messages.
- Updated WordPress pay ICEPAY library to version 2.0.5.
  - Added support for Klarna (Directebank) payment method.
  - Update ICEPAY library version from 2.4.0 to 2.5.3.
- Updated WordPress pay iDEAL Basic library to version 2.0.4.
  - Fixed setting `deprecated` based on passed arguments.
- Updated WordPress pay Mollie library to version 2.0.8.
  - Added response data to error for unexpected response code.
  - Moved next payment delivery date filter from gateway to integration class.
  - Throw exception when Mollie response is not what we expect.
- Updated WordPress pay OmniKassa 2.0 library to version 2.1.9.
  - Use line 1 as street if address splitting failed (i.e. no house number given).
  - Improved support for merchantOrderId = AN (Strictly)..Max 24 field.
- Updated WordPress pay Gravity Forms library to version 2.1.12.
  - Improved RTL support in 'Synchronized payment date' settings fields.
  - Fixed loading extension in multisite when plugin is network activated and Gravity Forms is activated per site.
- Updated WordPress pay MemberPress library to version 2.0.11.
  - Fixed showing lifetime columns on MemberPress subscriptions page if plugin is loaded before MemberPress.
- Updated WordPress pay Restrict Content Pro library to version 2.1.5.
  - Restrict Content Pro 3.0 is required.
  - Renew membership during `pronamic_pay_new_payment` routine and update membership expiration date and status on cancelled/expired/failed payment status update.
- Updated WordPress pay s2Member library to version 2.0.4.
  - Send user first and last name to list servers.
  - Added s2Member plugin dependency.
  - Added support for list server opt-in.
- Updated WordPress pay WooCommerce library to version 2.0.9.
  - Only update order status if order payment method is a WordPress Pay gateway.
  - No longer disable 'Direct Debit' gateways when WooCommerce subscriptions is active and cart has no subscriptions [read more](https://github.com/wp-pay-extensions/woocommerce#conditional-payment-gateways).
  - Changed redirect URL for cancelled and expired payments from cancel order to order pay URL.
  - Allow payment gateway selection for order pay URL.

## [5.7.4] - 2019-09-02

### Fixed
- Updated WordPress pay Gravity Forms library to version 2.1.11.
  - Fix entry payment fulfillment.
- Updated WordPress pay MemberPress library to version 2.0.10.
  - Fix error "`DatePeriod::__construct()`: The recurrence count '0' is invalid. Needs to be > 0".

## [5.7.3] - 2019-08-30

### Fixed
- Updated WordPress pay Sisow library to version 2.0.3.
  - Fix possible error with tax request parameters.
- Updated WordPress pay iDEAL Advanced v3 library to version 2.0.4.
  - Removed 'Show details...' toggle link in settings.

## [5.7.2] - 2019-08-30

### Fixed
- Updated WordPress pay core library to version 2.2.3.
  - Fix not loading gateways.

## [5.7.1] - 2019-08-30

### Fixed
- Updated WordPress pay core library to version 2.2.2.
  - Improved backwards compatibility for `pronamic_pay_gateways` filter.
- Updated WordPress pay Gravity Forms library to version 2.1.10.
  - Fix possible error with subscriptions "Uncaught Exception: DatePeriod::__construct(): This constructor accepts either...".
  - Improve GF Nested Forms compatibility.
- Updated WordPress pay WooCommerce library to version 2.0.8.
  - Fix error "`DatePeriod::__construct()`: The recurrence count '0' is invalid. Needs to be > 0".

## [5.7.0] - 2019-08-26

### Changed
- Updated WordPress pay Formidable Forms library to version 2.0.3.
  - Improved Formidable Forms v4 compatibility.
- Updated WordPress pay MemberPress library to version 2.0.9.
  - Fix incorrect subscription frequency.
  - No longer start up follow-up payments for paused subscriptions.
- Updated WordPress pay Restrict Content Pro library to version 2.1.4.
  - Fixed support for Restrict Content Pro 3.0.
- Updated WordPress pay Adyen library to version 1.0.2.
  - Set country from billing address.
  - Added action `pronamic_pay_adyen_checkout_head`.
  - Added `pronamic_pay_adyen_config_object` filter and improved documentation.
- Updated WordPress pay ICEPAY library to version 2.0.4.
  - Force language `NL` for unsupported languages (i.e. `EN` for iDEAL).
  - Only force language if payment method is set.
- Updated WordPress pay Sisow library to version 2.0.2.
  - Get available payment methods for merchant from Sisow account.
  - Transform status `Reversed` to WordPress Pay status `Refunded`.
- Updated WordPress pay Nocks library to version 2.0.2.
  - Do not use removed `set_slug()` method.
- Updated WordPress pay Mollie library to version 2.0.2.
  - Updated to Mollie API v2, with multicurrency support.
  - Added EPS payment method.
  - Added filter for subscription 'Next Payment Delivery Date'.

### Removed
- Removed Paytor integration, still supported via the Mollie gateway. For more information see https://www.wp-pay.org/paytor-disappeared-now-part-of-mollie/.
- Removed Qantani (new platform) integration, still supported via the Mollie gateway.
- Removed Postcode.nl integration, for more information see https://github.com/wp-pay-gateways/postcode-ideal/blob/master/DEPRECATED.md.

## [5.6.2] - 2019-05-15

### Fixed
- Updated WordPress pay Adyen library from 1.0.0 to version 1.0.1.
  - Remove path from origin URL in payment session request.
  - Fix API live URL prefix setting not saved.
- Updated WordPress pay ICEPAY library from 2.0.2 to version 2.0.3.
  - Set country from billing address.
- Updated WordPress pay Easy Digital Downloads library from 2.0.3 to version 2.0.4.
  - Improve emptying cart for completed payments.
- Updated WordPress pay Formidable Forms library from 2.0.1 to version 2.0.2.
  - Improve support for AJAX enabled forms.
- Updated WordPress pay Gravity Forms library from 2.1.7 to version 2.1.8.
  - Fix payment method field options deselected when saving from form settings subviews.
  - Update entry payment status when subscription is manually activated.
  - Disable asynchronous feed processing for delayed actions.
- Updated WordPress pay MemberPress library from 2.0.7 to version 2.0.8.
  - Fix subscription source ID bug.
  - Add more payment method icons.
  - Add capabilities to Direct Debit Bancontact/iDEAL/Sofort gateways.
- Updated WordPress pay s2Member library from 2.0.1 to version 2.0.2.
  - Set subscription `total amount` instead of `amount`.

## [5.6.1] - 2019-04-15

### Fixed
- Updated WordPress pay Ninja Forms library from 1.0.0 to version 1.0.1.
  - Fix form builder not loading due to removed 'pricing' field type section since Ninja Forms 3.4.6.
  - Workaround Ninja Forms not passing plugin default currency setting correctly.
- Updated WordPress pay WooCommerce library from 2.0.5 to version 2.0.6.
  - Fix accidentally adding 'Pronamic' to checkout button text.
  - Fix fatal error in checkout settings with WooCommerce Subscriptions.
  - Fix incorrectly filtering available checkout gateways with WooCommerce Subscriptions.

## [5.6.0] - 2019-04-01

### Changed
- Updated WordPress pay core library from 2.1.5 to version 2.1.6.
  - Updated Tippy.js to version 3.4.1.
  - Introduced a `$payment->get_edit_payment_url()` function to easy retrieve the edit payment URL.
  - Introduced a `$payment->get_status_label()` function to retrieve easier a user friendly (translated) status label.
  - Renamed status check event to `pronamic_pay_payment_status_check` without seconds argument and with different delays for recurring payments.
  - Added space between HTML attributes when converting from array.
  - Allow transaction ID to be null.
  - Retrieving payments will now check on payment post type.
  - Introduced Country, HouseNumber and Region classes.
  - Simplify payment redirect (Ogone DirectLink answer moved to gateway).
  - Added key query argument to pay redirect URL.
  - Link recurring icon to subscription post edit.
  - Add support for payment redirect with custom views.
  - Register style pronamic-pay-redirect in plugin.
  - Removed ABN AMRO iDEAL Easy, iDEAL Only Kassa and Internetkassa gateways.
  - Keep main admin menu item active when editing payments/subscriptions/gateways/forms.
  - Added pronamic_pay_gateways filter.
  - Show Adyen and EMS gateway IDs in custom column.
  - Fixed empty admin reports.
- Updated WordPress pay Easy Digital Downloads library from 2.0.2 to version 2.0.3.
  - Always empty cart for completed payments.
  - Simplified adding gateways and payment method icons.
  - Fixed "The call to edd_record_gateway_error() has too many arguments" error.
- Updated WordPress pay WooCommerce library from 2.0.4 to version 2.0.5.
  - Improved order notes and payment status updates.
  - Added/updated gateway icons.
  - More DRY gateway setup.
- Updated WordPress pay WP eCommerce library from 2.0.1 to version 2.0.2.
  - Added support for additional payment data like customer, adresses, payment lines, etc.
  - Added gateway for AfterPay, Bancontact, Bank Transfer, Credit Card, Focum, Giropay, Maestro, PayPal and SOFORT.
- Updated WordPress pay Buckaroo library from 2.0.1 to version 2.0.2.
  - Improved Buckaroo push response handling when payment was not found.
  - Added missing PHP namespace alias usage Pronamic\WordPress\Pay\Core\Server.
- Updated WordPress pay Ingenico library from 2.0.1 to version 2.0.2.
  - Moved custom payment redirect from plugin to gateway.
  - Make use of payment `get_pay_redirect_url()` method.
  - Added initial support for Ogone alias creation.
  - Added HTML/CSS classes to TP parameter settings field.

## [5.5.5] - 2019-02-04

### Added
- Added Handelsbanken issuer icons.

### Fixed
- Updated WordPress pay core library from 2.1.4 to version 2.1.5.
  - Fixed fatal error PaymentInfo expecting taxed money.
  - Improved responsive admin tables for payments and subscriptions.
- Updated WordPress pay Gravity Forms library from 2.1.6 to version 2.1.7.
  - Fixed empty country code for unknown countries.
- Updated WordPress pay MemberPress library from 2.0.6 to version 2.0.7.
  - Fixed "Given country code not ISO 3166-1 alpha-2 value".
- Updated WordPress pay OmniKassa 2.0 library from 2.1.5 to version 2.1.6.
  - Removed workaround for order item name length, Rabobank has resolved the issue.
- Updated WordPress pay Pay.nl library from 2.0.1 to version 2.0.2.
  - Fixed error 'invalid paymentProfileId' if no payment method was specified.

## [5.5.4] - 2019-01-24

### Changed
- Updated WordPress pay core library from 2.1.3 to version 2.1.4.
- Updated WordPress pay MemberPress library from 2.0.5 to version 2.0.6.
  - Fixed fatal error Gateway not found when processing status updates.
- Updated WordPress pay Restrict Content Pro library from 2.1.2 to version 2.1.3.
  - Use taxed money object for subscripption amount.
- Updated WordPress pay ICEPAY library from 2.0.1 to version 2.0.2.
  - Improved setting required country and language.
- Updated WordPress pay OmniKassa 2.0 library from 2.1.4 to version 2.1.5.
  - Workaround for OmniKassa 2.0 bug in order item name length.

## [5.5.3] - 2019-01-22

### Changed
- Updated WordPress pay core library from 2.1.2 to version 2.1.3.
  - Fixed empty payment and subscription customer names.
  - Fixed missing user ID in payment customer.
  - Updated storing payments and subscriptions.
  - Allow manual subscription renewal also for gateways which support auto renewal.
- Updated WordPress pay Gravity Forms library from 2.1.5 to version 2.1.6.
  - Fixed fatal error in Gravity Forms recurring payments if plugin is not activated.
  - Fixed issue with prorating amount when synchronized payment dates are not enabled.
  - Enabled placeholder setting for issuers and payment methods field.
  - Added extra field map options.
  - Added support for payment lines.
- Updated WordPress pay Jigoshop library from 2.0.1 to version 2.0.2.
  - Fixed "Fatal error: Uncaught Error: Call to undefined method jigoshop::get_option()".
- Updated WordPress pay MemberPress library from 2.0.4 to version 2.0.5.
  - Added admin Pronamic subscription column to MemberPress subscriptions overview.
  - Updated payment and subscription creation.
- Updated WordPress pay Restrict Content Pro library from 2.1.1 to version 2.1.2.
  - Added support for subscription cancellation.
  - Update member auto renewal setting for first payment too.
  - Use Restrict Content Pro success page return URL only for successful payments.
  - Prevent using direct debit recurring payment methods for non-expiring subscriptions.
- Updated WordPress pay Mollie library from 2.0.5 to version 2.0.6.
  - Name is not required anymore when creating a new Mollie customer.
- Updated WordPress pay OmniKassa 2.0 library from 2.1.3 to version 2.1.4.
  - Workaround for OmniKassa 2.0 bug in order item names with special characters.

## [5.5.2] - 2019-01-03

### Changed
- Updated WordPress pay core library from 2.1.1 to version 2.1.2.
  - Fixed empty payments and subscriptions list tables with 'All' filter since WordPress 5.0.2.
- Updated WordPress pay OmniKassa 2.0 library from 2.1.2 to version 2.1.3.
  - Improved error handling.

## [5.5.1] - 2018-12-19

### Changed
- Updated WordPress pay core library from 2.1.0 to version 2.1.1.
  - Fixed incomplete payment customer from legacy meta.
- Updated WordPress pay OmniKassa 2.0 library from 2.1.1 to version 2.1.2.
  - Limit order item name to 50 characters.
- Updated WordPress pay WooCommerce library from 2.0.2 to version 2.0.4.
  - Fixed WooCommerce admin products table not listing all products.
  - Improved retrieving WooCommerce checkout fields.

## [5.5.0] - 2018-12-17

### Changed
- Updated WordPress pay core library from 2.0.8 to version 2.1.0.
  - Store payment data as JSON.
  - Added support for payment lines.
  - Added support for customer data in payment.
  - Added support for billing and shipping address in payment.
  - Added support for AfterPay anc Capayable payment methods.
  - Added new WordPress 5.0 post type labels.
  - Removed unused payment processing status.
  - Updated Tippy.js to version 3.3.0.
- Updated WordPress pay AppThemes library from 2.0.1 to version 2.0.2.
- Updated WordPress pay Charitable library from 2.0.0 to version 2.0.1.
- Updated WordPress pay ClassiPress library from 2.0.0 to version 2.0.1.
- Updated WordPress pay Easy Digital Downloads library from 2.0.1 to version 2.0.2.
  - Added support for payment lines.
  - Added Billink and Capayable gateways.
- Updated WordPress pay Event Espresso (legacy) library from 2.0.0 to version 2.1.0.
  - Fixed processing status updates.
  - Added source information filters.
- Updated WordPress pay Formidable Forms library from 2.0.0 to version 2.0.1.
- Updated WordPress pay Give library from 2.0.0 to version 2.0.1.
  - Fixed using default gateway setting.
- Updated WordPress pay Gravity Forms library from 2.1.3 to version 2.1.5.
  - Fixed unintended use of synchronized payment date setting for fixed intervals.
  - Fixed delayed feed action for Gravity Forms Zapier add-on.
  - Fixed fatal error when sending renewal notices.
  - Fixed delayed feed actions for free payments.
- Updated WordPress pay iThemes Exchange library from 2.0.0 to version 2.0.1.
- Updated WordPress pay Jigoshop library from 2.0.0 to version 2.0.1.
- Updated WordPress pay MemberPress library from 2.0.3 to version 2.0.4.
  - Added support for trials with the same length as subscription period.
  - Improve upgrading/downgrading subscriptions.
- Updated WordPress pay Membership library from 2.0.1 to version 2.0.2.
- Updated WordPress pay Restrict Content Pro library from 2.1.0 to version 2.1.1.
  - Use correct initial amount.
  - Fixed duplicate renewal.
- Updated WordPress pay s2Member library from 2.0.0 to version 2.0.1.
  - Renamed menu item from 'iDEAL' to 'Pay'.
- Updated WordPress pay Shopp library from 2.0.0 to version 2.0.1.
- Updated WordPress pay WooCommerce library from 2.0.1 to version 2.0.2.
  - Added AfterPay, Capayable, Focum and Klarna Pay Later payment methods.
  - Renamed Capayable to new brand name In3.
  - Added support for payment lines, shipping, billing and customer data.
- Updated WordPress pay WP e-Commerce library from 2.0.0 to version 2.0.1.
- Updated WordPress pay Buckaroo library from 2.0.0 to version 2.0.1.
- Updated WordPress pay Shopp library from 2.0.0 to version 2.0.1.
- Updated WordPress pay EMS e-Commerce library from 2.0.0 to version 2.0.1.
  - Fix using advanced order ID setting.
- Updated WordPress pay ICEPAY library from 2.0.0 to version 2.0.1.
  - Fixed "Fatal error: Uncaught Exception: MerchantID not valid" in test meta box.
- Updated WordPress pay iDEAL Advanced v3 library from 2.0.1 to version 2.0.2.
- Updated WordPress pay iDEAL Basic library from 2.0.0 to version 2.0.1.
- Updated WordPress pay ING Kassa Compleet library from 2.0.0 to version 2.0.1.
- Updated WordPress pay Mollie library from 2.0.4 to version 2.0.5.
  - Set gateway mode based on API key.
- Updated WordPress pay Mollie iDEAL library from 2.0.0 to version 2.0.1.
- Updated WordPress pay MultiSafepay library from 2.0.2 to version 2.0.3.
- Updated WordPress pay Nocks library from 2.0.0 to version 2.0.1.
- Updated WordPress pay Ogone library from 2.0.0 to version 2.0.1.
- Updated WordPress pay OmniKassa library from 2.0.0 to version 2.0.1.
  - Marked library as deprecated.
- Updated WordPress pay OmniKassa 2.0 library from 2.0.4 to version 2.1.1.
  - Added support for payment lines, shipping, billing and customer data.
  - Improved signature handling.
- Updated WordPress pay Pay.nl library from 2.0.0 to version 2.0.1.
  - Added support for payment lines, shipping, billing and customer data.
  - Added support for AfterPay, Focum, In3 and Klarna Pay Later.
- Updated WordPress pay Sisow library from 2.0.0 to version 2.0.1.
  - Added support for payment lines, shipping, billing and customer data.
  - Added support for Billink and Capayable.
- Updated WordPress pay TargetPay library from 2.0.0 to version 2.0.1.

## [5.4.2] - 2018-09-28

### Changed
- Updated WordPress pay core library from 2.0.7 to version 2.0.8.
  - Updated Tippy.js from 2.6.0 to 3.0.2.

### Fixed
- Updated WordPress pay OmniKassa 2 library from 2.0.3 to version 2.0.4.
  - Remove unused `use` statements.
- Updated WordPress pay Event Espresso library from 2.1.0 to version 2.1.1.
  - Use updated iDEAL gateway class name.
  - Use cards icon as default icon for Pronamic payment method too.
- Updated WordPress pay Gravity Forms library from 2.1.2 to version 2.1.3.
  - Trigger events for field on change.

## [5.4.1] - 2018-09-17

### Fixed
- Updated WordPress pay OmniKassa 2 library from 2.0.2 to version 2.0.3.
  - Fixed - Fatal error: Cannot use Pronamic\WordPress\Pay\Core\Gateway as Gateway because the name is already in use.

## [5.4.0] - 2018-09-14

### Added
- Added support for Ninja Forms.

### Changed
- Updated WordPress pay core library from 2.0.4 to version 2.0.6.
  - Set default status of new payments to 'Open'.
  - Improved support for local float values.
  - Updated Tippy.js from version 2.5.4 to 2.6.0.
- Updated Pronamic WordPress DateTime library from 1.0.1 to version 1.0.2.
  - Fixed issue on PHP 5.6 or lower with empty timezone in `create_from_format` function calls.
- Updated WordPress pay Gravity Forms library from 2.1.1 to version 2.1.2.
  - Improved support for addons with delayed payment integration support.
  - Improved support for delayed Gravity Flow workflows.
- Updated WordPress pay MemberPress library from 2.0.1 to version 2.0.3.
  - Create a 'confirmed' 'subscription_confirmation' transaction for a grace period of 15 days.
  - Added error message on registration form for failed payment.
- Updated WordPress pay Restrict Content Pro library from 2.0.2 to version 2.1.0.
  - Complete rewrite of library.

## [5.3.0] - 2018-08-28

### Changed
- Updated WordPress pay core library to version 2.0.3.
  - New payments with amount equal to 0 (or empty) will now directly get the completed status.
  - Use PHP BCMath library for money calculations when available.
  - Use pronamic/wp-money library to parse money strings.
  - Added Maestro to list of payment methods.
- Updated WordPress pay OmniKassa 2 library to version 2.0.2.
  - Improved webhook handler functions and logging.
  - Improved return URL request handler functions and logging.
  - Store OmniKassa 2.0 merchant order ID in the payment.
- Updated WordPress pay Gravity Forms library to version 2.0.2.
  - Added support for synchronized subscription payment dates.
  - Changed Entry ID prefix field to a Order ID field.
  - Set conditional logic dependency for fields used in payment feed conditions.
  - Added Pronamic subscription amount merge tag `{pronamic_subscription_amount}`.
  - Added support for duplicating payment feeds.
  - Added custom display mode field setting.
  - Improved handel delay actions support.
  - Removed support for "Gravity Forms User Registration Add-On" version < 3.0.
  - The `add_pending_payment` action is no longer triggered for entries without pending payments.
- Updated WordPress pay Easy Digital Downloads library to version 2.0.1.
  - Added fallback to the default Pronamic Pay configuration ID.
  - Prefixed the Pronamic gateways with 'Pronamic - '.
  - Added new payment URL for Easy Digital Downloads version 3.0+.
- Updated WordPress pay Restrict Content Pro library to version 2.0.2.
  - Improved subscription upgrades.
- Updated WordPress pay Mollie library to version 2.0.4.
  - Do not allow .local TLD in webhook URL.
  - Added missing `failed` status.
  - Improved the way we create and handle Mollie customers.
- Updated Pronamic WordPress DateTime library to version 1.0.1.
  - Improved support for timezones.
- Updated Pronamic WordPress Money library to version 1.1.0.
  - Added a money parser class.

## [5.2.0] - 2018-06-21

### Added
- Added support for WordPress core privacy export and erasure feature.

### Changed
- Updated Tippy.js library to version 2.5.3.

## [5.1.0] - 2018-06-04

### Added
- MemberPress - Improved support for MemberPress.

### Fixed
- AppThems - Improve Hirebee theme compatibility.
- Gravity Forms - Fixed using merge tag as order ID.
- Membership - Fixed fatal error "Uncaught Exception: DateInterval::__construct(): Unknown or bad format (P)".
- Mollie - Fixed setting issuer for iDEAL payment method.

## [5.0.1] - 2018-05-16

### Fixed
- Updated WordPress pay iDEAL Advanced v3 library to version 2.0.1.
  - Fixed "Fatal error: Uncaught Error: Call to a member function get_amount() on float".

## [5.0.0] - 2018-05-16

### Changed
- Switched to PHP namespaces.
- Updated WordPress pay ING Kassa Compleet library to version 2.0.
  - Added support for Payconiq payment method.
  - Fixed payment redirect for bank transfer payment method.
- Updated WordPress pay Gravity Forms library to version 2.0.
  - Set Gravity Forms add-on capabilities `gravityforms_pronamic_pay` and  `gravityforms_pronamic_pay_uninstall`.
- Updated WordPress pay Restrict Content Pro library to version 2.0.
  - Add PayPal payment method.
  - Add Direct Debit (mandate via Sofort) payment method.
- Updated WordPress pay WooCommerce library to version 2.0.
  - Add specific payment methods only if activated by configuration.
  - Add Direct Debit (mandate via Sofort) payment method.

## [4.7.0] - 2017-12-12

### Added
- Added WordPress pay OmniKassa 2.0 library version 1.0.0.
- Added WordPress pay Restrict Content Pro library version 1.0.0.

### Changed
- Updated WordPress pay Buckaroo library to version 1.2.9.
  - Added support for PayPal payment method.
- Updated WordPress pay ICEPAY library to version 1.3.1.
  - Set payment success and error return URLs.
- Updated WordPress pay iDEAL Advanced v3 library to version 1.1.11.
  - Fix for a incorrect implementation at https://www.ideal-checkout.nl/simulator/.
  - Some acquirers only accept fingerprints in uppercase.
  - Updated WordPress Coding Standards.
- Updated WordPress pay Mollie library to version 1.1.15.
  - Added support for payment method Direct Debit (mandate via Bancontact).
  - No longer create new Mollie customer during recurring (not first) payments.
  - Update payment consumer BIC from Mollie payment details.
  - Update payment consumer name with Mollie payment card holder name.
  - Cancel subscriptions if first payment fails, to prevent future reactivation when a vailid customer ID becomes available.
  - Update subscription status on payment start only if it's not a recurring payment for a cancelled subscription.
- Updated WordPress pay MultiSafepay library to version 2.0.1.
  - Added support for first and last name.
- Updated WordPress pay Pay.nl library to version 1.1.8.
  - Set transaction description.
- Updated WordPress pay Sisow library to version 1.2.3.
  - Added support for bunq payment method.
- Updated WordPress pay TargetPay library to version 1.1.1.
  - WordPress Coding Standards optimizations.
- Updated WordPress pay Charitable library to version 1.1.3.
  - Implemented `get_first_name()` and `get_last_name()`.
  - Use default gateway if no configuration has been set.
- Updated WordPress pay Easy Digital Downloads library to version 1.2.7.
  - Implemented `get_first_name()` and `get_last_name()`.
- Updated WordPress pay Give library to version 1.0.6.
  - Implemented `get_first_name()` and `get_last_name()`.
- Updated WordPress pay Gravity Forms library to version 1.6.7.
  - Implemented `get_first_name()` and `get_last_name()`.
  - Fix possible PHP notices for undefined index `id`.
  - Added support for delaying Sliced Invoices feed processing.
  - Filter payment method choices if not in form editor.
  - Added support for delaying Moneybird feed processing.
  - Simplified merge tag replacement.
- Updated WordPress pay iThemes Exchange library to version 1.1.5.
  - Implemented `get_first_name()` and `get_last_name()`.
- Updated WordPress pay Jigoshop library to version 1.0.6.
  - Implemented `get_first_name()` and `get_last_name()`.
- Updated WordPress pay MemberPress library to version 1.0.5.
  - Added Pronamic gateway.
  - Fixed MemberPress v1.3.18 redirect URL compatibility.
  - Added Bitcoin and PayPal gateways.
  - Updated iDEAL and PayPal icons.
- Updated WordPress pay Membership library to version 1.0.8.
  - Implemented `get_first_name()` and `get_last_name()`.
- Updated WordPress pay s2Member library to version 1.2.7.
  - Add support for recurring payments.
- Updated WordPress pay Shopp library to version 1.0.7.
  - Implemented `get_first_name()` and `get_last_name()`.
- Updated WordPress pay WooCommerce library to version 1.2.8.
  - Added credit card payment fields.
  - Added bunq gateway.
  - Implemented `get_first_name()` and `get_last_name()`.
  - Added `Direct Debit (mandate via Bancontact)` gateway.
  - Added a few `order_button_text` labels.
  - Updated subscription payment data.
  - Set subscription payment method on renewal to account for changed payment method.
  - Improved WooCommerce 3.0 compatibility.
  - Added gateway support for amount and date changes.
  - Clear subscription next payment date on gateway error during payment processing.
- Updated WordPress pay WP eCommerce library to version 1.0.5.
  - Implemented `get_first_name()` and `get_last_name()`.

### Removed
- Removed support for old Qantani platform.

## [4.6.0] - 2017-05-01

### Changed
- Changed plugin name from 'Pronamic iDEAL' to 'Pronamic Pay'.
- Use the new bulk actions WordPress 4.7 filter and remove the edit bulk option.
- Use new register WordPress 4.7 setting feature.
- Added `get_user_id()` to payment data for usage as payment `post_author`.
- Updated WordPress pay Buckaroo library to version 1.2.8.
  - Use custom payment ID field in transaction request/response instead of invoice number.
- Updated WordPress pay EMS e-Commerce library to version 1.0.4.
  - Added missing Bancontact payment method transformation.
  - Added leap of faith payment method support.
- Updated WordPress pay Mollie library to version 1.1.14.
  - Set payment status to `Failed` too if `mollie_error` occurs.
- Updated WordPress pay ING Kassa Compleet library to version 1.0.7.
  - Fixed issuer not set if payment method is not empty.
  - Improved error handling for inactive payment methods.
  - Make payment method required.
- Updated WordPress pay Rabobank - iDEAL Professional - v3 library to version 1.0.2.
  - Added new signing certificate.

## [4.5.5] - 2017-04-18

### Changed
- Don't use global post for WP_Query posts traversal, fix for WooCommerce save order empty address details.
- Updated WordPress pay WooCommerce library to version 1.2.6.
  - Improved support for WooCommerce 3.0.

## [4.5.4] - 2017-04-11

### Changed
- Added PayPal config select options.
- Added Sisow to credit card and Sofort config select options.
- Updated WordPress pay Buckaroo library to version 1.2.7.
  - Use `brq_push` parameter for the Buckaroo Push URL.
- Updated WordPress pay iDEAL Advanced v3 library to version 1.1.10.
  - Removed surrounding quotes from subject, these are already added by `escapeshellarg()`.
- Updated WordPress pay ING - iDEAL Advanced - v3 library to version 1.0.3.
  - Added new signing certificate.
- Updated WordPress pay ING - Kassa Compleet library to version 1.0.6.
  - Only set iDEAL payment method if none set yet.
  - Added two extra payment methods.
- Updated WordPress pay OmniKassa library to version 1.2.3.
  - Fixed incorrect seal calculations.
- Updated WordPress pay Sisow library to version 1.2.2.
  - Added support for PayPal, Sofort and 'leap of faith' payment methods.
- Updated WordPress pay Gravity Forms library to version 1.6.5.
  - Fulfill order with payment status 'Paid'.
  - Prevent sending delayed notification twice.

## [4.5.3] - 2017-03-15

### Changed
- Fixed subscription title link in meta payment subscription.
- Removed amount input `required` attribute with choices.
- Added credit card methods to payment.
- Added Ogone DirectLink to credit card config select options.
- Added `ems-e-commerce` to Bancontact config select options.
- Sanitize instead of validate user input in payment form data.
- Increase delay for first scheduled status check from 30s to 15m.
- Updated WordPress pay core library to version 1.3.12.
  - Make sure payment methods are stored as array in transient.
- Updated WordPress pay EMS e-Commerce library to version 1.0.3.
  - Set decimal and group separators for `chargetotal` parameter according to specs.
  - Added support for Bancontact payment method.
  - No longer filter storename and shared secret setting fields.
- Updated WordPress pay Mollie library to version 1.1.13.
  - Return null if the payment method variable is not a scalar type to fix “Warning: Illegal offset type in isset or empty” error.
  - No longer check if $payment_method is a empty string, the compare on the mandate method is enough.
  - Set default payment method to null in `has_valid_mandate` function.
  - Improved getting the first valid mandate date time.
  - Ignore valid mandates for first payments.
- Updated WordPress pay Ogone library to version 1.3.4.
  - Only set credit card data if we have it.
- Updated WordPress pay OmniKassa library to version 1.2.2.
  - Set payment transaction ID to transaction reference (e.g. for payment notes and merge tags).
  - Added support for Maestro payment method.
  - Default order ID uses payment ID in `format_string()`.
- Updated WordPress pay Gravity Forms library to version 1.6.4.
  - Updated new feed URL link in payment fields.
  - Only load the payment methods field if Gravity Forms version is > 1.9.19.
  - Simplified loading and setting up the Gravity Forms extension with a early return.
  - Fixed 'Warning: Missing argument 3 for gf_apply_filters()'.
  - Added support for delaying ActiveCampaign subscriptions.
  - Use version compare helper to prevent fatal errors.
- Updated WordPress pay ClassiPress library to version 1.0.4.
  - Fixed “Fatal error: Uncaught Error: Using $this when not in object context”.
- Updated WordPress pay WooCommerce library to version 1.2.5.
  - Don't set subscriptions 'on hold' due to delay in direct debit status update.
  - Removed gateway description about valid mandate, as these mandates are no longer in use.

## [4.5.2] - 2017-02-13

### Changed
- Updated WordPress pay Gravity Forms library to version 1.6.2.
  - No longer check on the payment feed post ID, a empty payment feed post ID is allowed when creating new payment feeds.
  - Auto enable new payment feeds.
  - Make `is_active` backwards compatible when getting feeds.
  - Added support for No Conflict Mode.

## [4.5.1] - 2017-02-09

### Changed
- Updated WordPress pay Gravity Forms library to version 1.6.1.
  - Only check admin referer for payment feeds and not when saving/testing configurations.

## [4.5.0] - 2017-02-08

### Changed
- Updated WordPress pay core library to version 1.3.11.
  - Added new constant for the KBC/CBC Payment Button payment method.
  - Added new constant for the Belfius Direct Net payment method.
- Updated WordPress pay EMS e-Commerce Gateway library to version 1.0.2.
  - Make sure always the same transaction date time is used.
  - Make sure to not encode quotes.
- Updated WordPress pay ICEPAY library to version 1.3.0.
  - Added order ID setting.
- Updated WordPress pay Mollie library to version 1.1.12.
  - Enabled support for more Mollie payment methods.
  - Auto renew invalid customer IDs.
  - Only update subscription status for subscriptions.
  - Added filter for payment provider URL.
  - Removed deprecated MISTER_CASH from the get_supported_payment_methods function.
- Updated WordPress pay Charitable library to version 1.1.1.
  - Added filter for payment source URL and description.
  - Added process_donation() method to make sure Pronamic gateway works correctly.
- Updated WordPress pay ClassiPress library to version 1.0.3.
  - Updated dev libraries.
  - Simplified adding hooks.
  - Added filter for payment source description and URL.
  - Added a extra filter for the payment redirect URL.
  - Always redirect to the pay redirect URL.
- Updated WordPress pay Easy Digital Downloads library to version 1.2.6.
  - Added Bank Transfer gateway.
  - Added Bitcoin gateway.
  - Added filter for payment source description and URL.
  - Changed to class functions.
  - Added new icons for Bitcoin and Soft.
- Updated WordPress pay Event Espresso library to version 1.1.6.
  - Added filter for payment source description and URL.
- Updated WordPress pay Formidable Forms library to version 1.0.2.
  - Added filter for payment source link and description.
- Updated WordPress pay Give library to version 1.0.5.
  - Added filter for payment source description and URL.
- Updated WordPress pay Gravity Forms library to version 1.6.0.
  - Added support for subscriptions.
  - Added temporary pay feeds moved notice.
  - Added filter function for the payment source description.
  - Added filter for source URL.
- Updated WordPress pay iThemes Exchange library to version 1.1.4.
  - Added filter for payment source description.
- Updated WordPress pay Jigoshop library to version 1.0.5.
  - Added filter for payment source description and URL.
  - Simplified gateway by always redirecting to the pay URL.
- Updated WordPress pay MemberPress library to version 1.0.4.
  - Use MeprUtils class for sending transaction notices (Zendesk #10084).
  - No longer echo invoice in payment redirect function.
  - Added filter payment source description and URL.
  - Use credit card alias instead of Sofort in credit card gateway.
- Updated WordPress pay Membership library to version 1.0.7.
  - Added filter for payment source description and URL.
- Updated WordPress pay s2Member library to version 1.2.6.
  - Added filter payment source description.
- Updated WordPress pay Shopp library to version 1.0.6.
  - Added filter for payment source description and URL.
- Updated WordPress pay WooCommerce library to version 1.2.4.
  - Added KBC/CBC Payment Button gateway.
  - Added Belfius Direct Net gateway.
  - Added filter for payment source description and URL.
- Updated WordPress pay WP eCommerce library to version 1.0.4.
  - Added filter for payment source description and URL.

## [4.4.4] - 2016-11-16

### Added
- Added support for Maestro payment method with OmniKassa.
- Updated WordPress pay core library to version 1.3.10.
  - Added new constant for the Maestro payment method.
- Added custom post updated messages for payment post type.

### Changed
- Improved and simplified SASS/CSS for WordPress admin elements.
- Changed Mastercard icon.
- Improved support for setting default gateway on WP-CLI.
- Simplified `format_string()` replacements.
- Updated WordPress pay iDEAL Advanced v3 library to version 1.1.9.
  - Simplified settings fields.
- Updated WordPress pay Mollie library to version 1.3.3.
  - Improved Client class, DRY improvements.
  - Added constants for some extra methods.
- Updated WordPress pay OmniKassa library to version 1.2.1.
  - Added support for Maestro payment method.
  - Default order ID uses payment ID in `format_string()`.
- Updated WordPress pay Charitable library to version 1.1.0.
  - Updated gateway system to Charitable version 1.3+.
- Updated WordPress pay Gravity Forms library to version 1.5.2.
  - Simplified CSS for WordPress admin elements.
  - Enabled choice values for payment methods field.
- Updated WordPress pay WooCommerce library to version 1.2.2.
  - Added Maestro gateway.
  - Filter gateway description to show mandate notice also when description is empty.

### Fixed
- Updated WordPress pay Give library to version 1.0.4.
  - Display payment input fields also if guest donations are not allowed and registraton and/or login forms are displayed.

### Removed
- Removed own definition of the `wp_slash` function, this plugin requires already WordPress 4.3 or higher, this function is part of WordPress since 3.6.
- Updated WordPress pay Ogone library to version 1.3.3.
  - Removed specific ABN AMRO iDEAL Easy PSPID test description.

## [4.4.3] - 2016-11-02

### Changed
- Updated WordPress pay MultiSafepay library to version 2.0.0.

## [4.4.2] - 2016-11-02

### Fixed
- Fix "Fatal error: Can't inherit abstract function Pronamic_Pay_PaymentDataInterface::get_subscription_id() (previously declared abstract in Pronamic_Pay_AbstractPaymentData)".
- Check if subscription id is empty instead of an empty string (can be null too).

## [4.4.1] - 2016-10-28

### Fixed
- Fixed styling of recurring icon in the WordPress admin payments overview page.
- Fixed `format_price` function if a non float value is passed in.
- Updated WordPress pay iDEAL Advanced v3 library to version 1.1.8.
  - Fixed zero days private certificate validity in OpenSSL command.

### Changed
- Changed version number in `wp_register_style` and `wp_register_script` function calls.
- Simplified the status icons CSS and related code.
- Updated WordPress pay Gravity Forms library to version 1.5.1.
  - Changed Gravity Forms admin menu item text 'iDEAL' to 'Payment Feeds'.
  - Changed text 'Payment Form(s)' to 'Payment Feed(s)'.

## [4.4.0] - 2016-10-27

### Added
- Added experimental support for subscriptions / recurring payments (WooCommerce Subscriptions).
- Added check status button on the WordPress admin edit payment page.
- Added custom capabilities for the custom post types.

### Changed
- Changed WordPress admin menu name from 'iDEAL' to 'Pay'.
- Changed WordPress admin menu icon from iDEAL icon to dash icon 'money'.
- Updated WordPress pay core library to version 1.3.9.
- Updated WordPress pay ABN AMRO - iDEAL Easy library to version 1.0.4.
- Updated WordPress pay ABN AMRO - iDEAL Zelfbouw - v3 library to version 1.0.5.
- Updated WordPress pay ABN AMRO - Internetkassa library to version 1.0.2.
- Updated WordPress pay Buckaroo library to version 1.2.6.
  - Fixed unable to use payment method 'All available methods'.
  - Added new Bancontact constant.
  - Fixed `Fatal error: Call to undefined method Pronamic_WP_Pay_Gateways_Buckaroo_Client::get_error().`
- Updated WordPress pay Deutsche Bank - iDEAL Expert - v3 library to version 1.0.3.
- Updated WordPress pay Deutsche Bank - iDEAL via Ogone library to version 1.0.2.
- Updated WordPress pay EMS e-Commerce library to version 1.0.1.
  - Added transaction feedback status setting.
  - Fixed - Too many arguments for function "__".
- Updated WordPress pay ICEPAY library to version 1.2.9.
  - Added support for new Bancontact constant.
- Updated WordPress pay iDEAL library to version 1.1.5.
- Updated WordPress pay iDEAL Advanced v3 library to version 1.1.7.
- Updated WordPress pay iDEAL Basic library to version 1.1.6.
- Updated WordPress pay ING - iDEAL Advanced - v3 library to version 1.0.2.
- Updated WordPress pay ING Kassa Compleet library to version 1.0.5.
- Updated WordPress pay Mollie library to version 1.1.9.
  - Fixed wrong char in switch statement.
  - Added support for new Bancontact constant.
  - Use seperate customer IDs for test and live mode.
- Updated WordPress pay Mollie iDEAL library to version 1.0.7.
- Updated WordPress pay MultiSafepay Connect library to version 1.3.0.
  - Improved error reporting.
- Updated WordPress pay Ingenico/Ogone library to version 1.3.2.
  - Added `payment_status_request` feature support.
  - Updated SHA-IN parameters list from ingenico.com.
  - Updated SHA-OUT parameters list from ingenico.com.
  - Removed schedule status check event, this will be part of the Pronamic iDEAL plugin.
  - Use new `$payment->format_string()` function, and remove util function.
  - Added support for new Bancontact constant.
  - Fixed method `get_default_form_action_url()` visibility.
  - Added support for form action URL for OrderStandard Easy.
- Updated WordPress pay OmniKassa library to version 1.2.0.
- Updated WordPress pay Pay.nl library to version 1.1.7.
  - Added `payment_status_request` feature support.
  - Fixed "urlencode should only be used when dealing with legacy applications, rawurlencode() should now be used instead".
  - Removed schedule status check event, this will be part of the Pronamic iDEAL plugin.
  - Added end user name and e-mail address to transaction.
  - Added support new Bancontact constant.
  - Don't schedule `pronamic_ideal_check_transaction_status` event on transaction error.
- Updated WordPress pay Qantani library to version 1.1.0.
- Updated WordPress pay Sisow library to version 1.2.1.
  - Only send status check if transaction ID is not empty.
  - Added feature support payment_status_request.
  - Added support for new Bancontact constant.
- Updated WordPress pay AppThemes library to version 1.0.5.
- Updated WordPress pay Charitable library to version 1.0.5.
  - Added cancel URL.
  - Added Pronamic gateway usage clarification
  - Added transaction description setting.
  - Make use of new Bancontact label and constant.
  - Ensure that the filter returns a value to avoid breaking other Charitable extensions that implement their own custom templates for certain form fields.
- Updated WordPress pay Easy Digital Downloads library to version 1.2.5.
- Updated WordPress pay Event Espresso library to version 1.1.5.
  - Use payment redirect URL.
  - Added help text with available tags.
  - Added support for custom transaction descriptions.
- Updated WordPress pay Give library to version 1.0.3.
  - Use 'donation' instead of 'transaction' in transaction description.
  - Added Pronamic gateway usage clarification.
  - Added transaction description setting.
  - Use new Bancontact label and constant.
- Updated WordPress pay Gravity Forms library to version 1.5.0.
  - Implemented the new pronamic_payment_redirect_url filter and added some early returns.
  - Fixed deprecated usage of GFUserData.
  - Refactored custom payment fields.
- Updated WordPress pay MemberPress library to version 1.0.3.
  - Added membership slug to thank you page URL.
  - Maybe cancel old subscriptions and send notices.
  - Make use of new Bancontact label and constant.
  - Use MemberPress transaction number in 'Thank you' redirect instead of payment source ID.
- Updated WordPress pay Membership library to version 1.0.6.
- Updated WordPress pay s2Member library to version 1.2.5.
  - Added support for payment method in shortcode.
- Updated WordPress pay WooCommerce library to version 1.2.1.
  - Added experimental support for WooCommerce Subscriptions / recurring payments.
  - Restore compatibility with WooCommerce versions < 2.2.0.
  - Switched to new Bancontact logo.
  - Added Bitcoin gateway.
- Updated WordPress pay WP eCommerce library to version 1.3.9.

## [4.3.0] - 2016-07-06

### Added
- Added WordPress pay EMS e-Commerce Gateway library version 1.0.0.

### Changed
- Updated WordPress pay iDEAL Advanced library to version 1.1.6.
  - Adjusted check on required distinguished name keys/values.
  - Added some early returns + escapeshellarg calls.
- Updated WordPress pay Mollie library to version 1.1.8.
  - Added PayPal to gateway methods transformations.
  - Fixed undefined variable `$user_id`.
- Updated WordPress pay Ogone library to version 1.3.1.
  - Get payment ID from request data.
- Updated WordPress pay iThemes Exchange library to version 1.1.3.
  - Fixed Membership add-on support.
- Updated WordPress pay Jigoshop library to version 1.0.4.
  - Use iDEAL payment method when payment method is required, but not set.
- Updated WordPress pay Gravity Forms library to version 1.4.8.
  - Added support for filtering payment data with `gform_post_save`.

## [4.2.3] - 2016-06-14

### Added
- Updated WordPress pay Sisow library to version 1.2.0.
  - Add support for bank transfer payment method

### Fixed
- Updated WordPress pay OmniKassa library to version 1.1.9.
- Updated WordPress pay Buckaroo library to version 1.0.4.

## [4.2.2] - 2016-06-10

### Fixed
- Updated WordPress pay Sisow library to version 1.1.9.

## [4.2.1] - 2016-06-09

### Fixed
- Updated WordPress pay AppThemes library to version 1.0.4.

## [4.2.0] - 2016-06-08

### Changed
- Updated WordPress pay core library to version 1.0.3.
  - Added PayPal payment method constant.
  - Simplified the gateway payment start function.
  - Added new constant for Bancontact payment method.
  - Fixed text domain for translations.
- Updated WordPress pay ICEPAY library to version 1.2.8.
- Updated WordPress pay iDEAL library to version 1.1.4.
- Updated WordPress pay iDEAL Advanced v3 library to version 1.1.5.
- Updated WordPress pay iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw - v3 library to version 1.0.2.
  - Changed payment URL from `https://www.ideal-simulator.nl/professional/` to `https://www.ideal-checkout.nl/simulator/`.
- Updated WordPress pay ING Kassa Compleet library to version 1.0.4.
- Updated WordPress pay Mollie library to version 1.1.6.
  - Added support for Mollie Checkout.
  - Reduced the use of else expressions.
  - Added WordPress payment method to Mollie method transform function.
  - Added readonly Mollie user profile fields.
  - Simplified the gateway payment start function.
- Updated WordPress pay MultiSafepay Connect library to version 1.2.9.
- Updated WordPress pay Ogone library to version 1.3.0.
- Updated WordPress pay OmniKassa library to version 1.1.8.
- Updated WordPress pay Pay.nl library to version 1.1.6.
- Updated WordPress pay Qantani library to version 1.0.9.
- Updated WordPress pay Sisow library to version 1.1.8.
- Updated WordPress pay TargetPay library to version 1.0.9.
- Updated WordPress pay AppThemes library to version 1.0.3.
  - Added Bancontact gateway.
  - Added Bank Transfer gateway.
  - Added Credit Card gateway.
  - Added Direct Debit gateway.
  - Added SOFORT Banking gateway.
- Updated WordPress pay Give library to version 1.0.2.
- Updated WordPress pay Gravity Forms library to version 1.4.7.
  - Set link type to confirmation if set and no URL or page have been set.
  - Cleaned up feed config (tabs, descriptions, tooltips, update confirmations if form changes).
  - Added icon and 'Add new' link to payment addon settings page.
  - Added Merge Tag button to transaction description field (without AJAX form change support).
  - Switched to use of `GF_Field` class.
  - Fixed text domain, `pronamic-ideal` is `pronamic_ideal`.
- Updated WordPress pay MemberPress library to version 1.0.2.
  - Added support for gateway input fields.
  - Added a iDEAL icon to the iDEAL gateway.
  - Only use MeprTransaction object in payment data constructor, remove unused variable `$product`.
- Updated WordPress pay WooCommerce library to version 1.2.0.
  - Added PayPal gateway.

## [4.1.1] - 2016-05-06

### Changed
- Show gateway errors on the edit gateway configuration admin page.
- Updated WordPress pay core library to version 1.3.6.
- Updated WordPress pay Pay.nl library to version 1.1.5.

## [4.1.0] - 2016-04-13

### Added
- Added support for bulk action check payment status.

### Changed
- Use HTTPS in Facebook iframe source URL
- Updated WordPress pay ABN AMRO - iDEAL Easy library to version 1.0.3.
- Updated WordPress pay Buckaroo library to version 1.2.4.
  - Added support for iDEAL issuer.
- Updated WordPress pay ING - iDEAL Advanced - v3 library to version 1.0.1.
- Updated WordPress pay MultiSafepay Connect library to version 1.2.8.
- Updated WordPress pay Ogone library to version 1.2.8.
  - Added support for custom Ogone e-Commerce form action URL.
  - Renamed OrderStandard to 'e-Commerce'.
- Updated WordPress pay AppThemes library to version 1.0.2.
- Updated WordPress pay Charitable library to version 1.0.4.
- Updated WordPress pay ClassiPress library to version 1.0.2.
- Updated WordPress pay Easy Digital Downloads library to version 1.2.4.
- Updated WordPress pay Event Espresso library to version 1.1.4.
- Updated WordPress pay Event Espresso (legacy) library to version 1.0.3.
- Updated WordPress pay Give library to version 1.1.4.
  - Improved error handling.
  - Refactored class construct gateways.
  - Empty unused get URL functions.
- Updated WordPress pay Gravity Forms library to version 1.4.6.
- Updated WordPress pay iThemes Exchange library to version 1.1.2.
- Updated WordPress pay Jigoshop library to version 1.0.3.
- Updated WordPress pay JobRoller library to version 1.0.1.
- Updated WordPress pay MemberPress library to version 1.0.1.
  - Implemented new redirect system.
  - No longer use camelCase for payment data.
  - Redirect to payment form action if payment was unsuccessful.
  - Fixed number of arguments passed to send_product_welcome_notices().
- Updated WordPress pay Membership library to version 1.0.5.
  - Update URLs in payment data.
  - Extend iDEAL gateway from Pronamic gateway and only use status_update() in Extension.php
  - No longer use camelCase for payment data.
- Updated WordPress pay s2Member library to version 1.2.3.
- Updated WordPress pay Shopp library to version 1.0.4.
- Updated WordPress pay WooCommerce library to version 1.1.8.
  - Check existence of WC_Order::has_status() to support older versions of WooCommerce.
  - No longer use camelCase for payment data.
  - Add clarification to Pronamic gateway with difference compared to regular payment method specific gateways.
  - Fix adding 'Awaiting payment' order note if order status is already pending.
- Updated WordPress pay WP eCommerce library to version 1.0.3.

### Removed
- Removed support for deprecated 'ING - iDEAL Internet Kassa' integration.
- Removed 'bold' font weight from active tab item.
- Removed unused class Pronamic_WP_Pay_PaymentInputData.
- Removed border on div.extra-settings for smaller displays.

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

[unreleased]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.6.4...HEAD
[6.6.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.6.3...6.6.4
[6.6.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.6.2...6.6.3
[6.6.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.6.1...6.6.2
[6.6.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.6.0...6.6.1
[6.6.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.5.1...6.6.0
[6.5.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.5.0...6.5.1
[6.5.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.4.1...6.5.0
[6.4.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.4.0...6.4.1
[6.4.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.3.2...6.4.0
[6.3.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.3.1...6.3.2
[6.3.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.3.0...6.3.1
[6.3.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.2.0...6.3.0
[6.2.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.1.2...6.2.0
[6.1.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.1.1...6.1.2
[6.1.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.1.0...6.1.1
[6.1.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.0.2...6.1.0
[6.0.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.0.1...6.0.2
[6.0.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/6.0.0...6.0.1
[6.0.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.9.0...6.0.0
[5.9.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.8.1...5.9.0
[5.8.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.8.0...5.8.1
[5.8.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.7.4...5.8.0
[5.7.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.7.3...5.7.4
[5.7.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.7.2...5.7.3
[5.7.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.7.1...5.7.2
[5.7.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.7.0...5.7.1
[5.7.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.6.2...5.7.0
[5.6.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.6.1...5.6.2
[5.6.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.6.0...5.6.1
[5.6.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.5.5...5.6.0
[5.5.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.5.4...5.5.5
[5.5.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.5.3...5.5.4
[5.5.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.5.2...5.5.3
[5.5.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.5.1...5.5.2
[5.5.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.5.0...5.5.1
[5.5.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.4.2...5.5.0
[5.4.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.4.1...5.4.2
[5.4.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.4.0...5.4.1
[5.4.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.3.0...5.4.0
[5.3.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.2.0...5.3.0
[5.2.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.1.0...5.2.0
[5.1.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.0.1...5.1.0
[5.0.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/5.0.0...5.0.1
[5.0.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.7.0...5.0.0
[4.7.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.6.0...4.7.0
[4.6.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.5.5...4.6.0
[4.5.5]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.5.4...4.5.5
[4.5.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.5.3...4.5.4
[4.5.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.5.2...4.5.3
[4.5.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.5.1...4.5.2
[4.5.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.5.0...4.5.1
[4.5.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.4.4...4.5.0
[4.4.4]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.4.3...4.4.4
[4.4.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.4.2...4.4.3
[4.4.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.4.1...4.4.2
[4.4.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.4.0...4.4.1
[4.4.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.3.0...4.4.0
[4.3.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.2.3...4.3.0
[4.2.3]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.2.2...4.2.3
[4.2.2]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.2.1...4.2.2
[4.2.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.2.0...4.2.1
[4.2.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.1.1...4.2.0
[4.1.1]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.1.0...4.1.1
[4.1.0]: https://github.com/pronamic/wp-pronamic-ideal/compare/4.0.0...4.1.0
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
