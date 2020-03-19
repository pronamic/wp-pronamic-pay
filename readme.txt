=== Pronamic Pay ===
Contributors: pronamic, remcotolsma 
Tags: ideal, bank, payment, gravity forms, forms, payment, woocommerce, recurring-payments, shopp, rabobank, friesland bank, ing, mollie, omnikassa, wpsc, wpecommerce, commerce, e-commerce, cart
Donate link: https://www.pronamic.eu/donate/?for=wp-plugin-pronamic-ideal&source=wp-plugin-readme-txt
Requires at least: 4.7
Tested up to: 5.4
Requires PHP: 5.6
Stable tag: 6.0.1

The Pronamic Pay plugin adds payment methods like iDEAL, Bancontact, credit card and more to your WordPress site for a variety of payment providers.


== Description ==

Pronamic Pay is the best plugin available to accept payments on your site with support for payment methods like iDEAL (Netherlands), Bancontact (Belgium), Sofort (Europe) and credit card, among others. Easily add the configuration details of your payment service provider account and enable the payment method in one of the supported e-commerce plugins. With over 200,000 downloads, the plugin has proven itself as a reliable WordPress solution to use for your payments.

= Key Benefits =

*   Supports a wide variety of payment providers.
*   Seamless integration with popular e-commerce and form builder plugins.
*   Automatically updates payment status of orders in WordPress.
*   Easily manage (multiple) payment provider configurations.
*   Continually updated to support the latest e-commerce plugins.
*   Built-in generation of required security certificates.
*   Works with all popular WordPress e-commerce plugins.
*   Recurring payments support for Mollie.
*   Reliable payment solution, with over 200,000 downloads.

= Supported WordPress e-commerce plugins =

*	[Charitable](https://www.wpcharitable.com/)
*	[Easy Digital Downloads](https://easydigitaldownloads.com/)
*	[Event Espresso 3](https://eventespresso.com/)
*	[Event Espresso 3 Lite](https://eventespresso.com/)
*	[Event Espresso 4](https://eventespresso.com/)
*	[Event Espresso 4 Decaf](https://eventespresso.com/)
*	[Formidable Forms](https://formidableforms.com/)
*	[Give](https://givewp.com/)
*	[Gravity Forms](https://www.gravityforms.com/)
*	[Gravity Forms AWeber Add-On](https://www.gravityforms.com/add-ons/aweber/)
*	[Gravity Forms Campaign Monitor Add-On](https://www.gravityforms.com/add-ons/campaign-monitor/)
*	[Gravity Forms MailChimp Add-On](https://www.gravityforms.com/add-ons/mailchimp/)
*	[Gravity Forms User Registration Add-On](https://www.gravityforms.com/add-ons/user-registration/)
*	[Gravity Forms Zapier Add-On](https://www.gravityforms.com/add-ons/zapier/)
*	[MemberPress](https://www.memberpress.com/)
*	[Ninja Forms](https://ninjaforms.com/)
*	[Restrict Content Pro](https://restrictcontentpro.com/)
*	[s2Member®](https://s2member.com/)
*	[WooCommerce](https://woocommerce.com/)
*	[WP e-Commerce](https://wpecommerce.org/)

= Supported payment providers =

*	ABN AMRO - iDEAL Zelfbouw (v3)
*	Adyen
*	Buckaroo - HTML
*	Deutsche Bank - iDEAL Expert (v3)
*	EMS - e-Commerce
*	Fibonacci ORANGE
*	ICEPAY
*	iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3)
*	ING - iDEAL Basic
*	ING - iDEAL Advanced (v3)
*	ING - Kassa Compleet
*	Mollie
*	MultiSafepay - Connect
*	Nocks
*	Ingenico/Ogone - DirectLink
*	Ingenico/Ogone - OrderStandard
*	Pay.nl
*	Rabobank - OmniKassa 2.0
*	Rabobank - iDEAL Professional (v3)
*	Sisow
*	TargetPay - iDEAL

= Premium payment providers =

*	Adyen

Premium payment providers require a [Pro license](https://www.pronamic.eu/plugins/pronamic-ideal/).


== Installation ==

= Requirements =

The Pronamic Pay plugin extends WordPress extensions with payment methods such as iDEAL, Bancontact, Sofort and credit cards. To offer the payment methods to the vistors of your WordPress website you also require one of these e-commerce or form builder extensions.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of Pronamic Pay, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “Pronamic Pay” and click Search Plugins. Once you’ve found the plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.


== Screenshots ==

1. Payment provider configurations
2. Edit configuration
3. Built-in payment forms
4. Edit payment form
5. Payment form sample
6. Payments
7. Subscriptions
8. Reports
9. Settings
10. Gravity Forms - Form editor
11. Gravity Forms - Feed editor
12. Gravity Forms - Sample payment form
13. WooCommerce - Checkout settings
14. WooCommerce - Checkout form
15. WooCommerce - Checkout with credit card and valid mandate (mandates work with payment provider Mollie, subscriptions require WooCommerce Subscriptions)
16. s2Member - Button generator
17. Getting started


== Are there any known plugin conflicts? ==

Unfortunately WordPress is notorious for conflicts between themes and plugins. It is unavoidable as you have no control over what other plugins and themes do. While we do take steps to avoid conflicts as best we can, we have no control over other plugins or themes.

As conflicts are found we will update this list. If you discover a conflict with a another plugin, please notify us.

Here is a list of known plugin conflicts:

=== WPML ===

The [WPML](https://wpml.org/) plugin(s) can conflict with multiple gateways. A lot of the gateways use `home_url( '/' )` to retrieve the WordPress home URL. The WPML plugins hooks in to this function to change the home URL to the correct language URL. This can result in incorrect checksums, signatures and hashes.

=== WordPress HTTPS ===

The [WordPress HTTPS](https://wordpress.org/plugins/wordpress-https/) can conflict with the OmniKassa payment method. It can cause invalid signature errors. The WordPress HTTPS plugin parses the complete output of an WordPress website and changes 'http' URLs to 'https' URLs, this  results in OmniKassa data that no longer matches the signature.


== Changelog ==

= 6.0.1 - 2020-03-19 =
*	Updated WordPress pay Mollie library to version 2.1.1.
	*	Force a specific collate to fix "Illegal mix of collations" error.

= 6.0.0 - 2020-03-19 =
*	Updated WordPress pay core library to version 2.3.0.
	*	Added Google Pay support.
	*	Added Apple Pay payment method.
	*	Added support for payment failure reason.
	*	Added input fields for consumer bank details name and IBAN.
	*	Simplify recurrence details in subscription info meta box.
	*	Fixed setting initials if no first and last name are given.
	*	Abstracted plugin and gateway integration classes.
*	Updated WordPress pay Easy Digital Downloads library to version 2.1.0.
	*	Update integration setup with dependencies support.
	*	Set Easy Digital Downloads payment status to 'cancelled' in case of a cancelled payment.
	*	Extend `Extension` class from `AbstractPluginIntegration`.
*	Updated WordPress pay Gravity Forms library to version 2.2.0.
	*	Added consumer bank details name and IBAN field settings.
	*	Fixed adding payment line for shipping costs only when shipping field is being used.
	*	Fixed dynamically setting selected payment method.
	*	Fixed feed activation toggle.
	*	Improved field visibility check with entry.
	*	Improved payment methods field choices in field input (fixes compatibility with `Gravity Forms Entries in Excel` plugin).
	*	Extension extends abstract plugin integration with dependency.
*	Updated WordPress pay Ninja Forms library to version 1.1.0.
	*	Fix incorrect selected payment method in payment methods fields when editing entry.
*	Updated WordPress pay WooCommerce library to version 2.1.0.
	*	Update integration setup with dependencies support.
	*	Use SVG icons.
	*	Add Apple Pay payment method.
	*	Extension extends \Pronamic\WordPress\Pay\AbstractPluginIntegration.
	*	Added Google Pay support.
*	Updated WordPress pay Adyen library to version 1.1.0.
	*	Fixed unnecessarily showing additional payment details screen in some cases.
	*	Only create controllers and actions when dependencies are met.
	*	Added Google Pay support.
	*	Added Apple Pay support.
*	Updated WordPress pay ICEPAY library to version 2.1.0.
	*	Fixed "$result is always a sub-type of Icepay_Result".
*	Updated WordPress pay Mollie library to version 2.1.0.
	*	Added custom tables for Mollie profiles, customers and WordPress users.
	*	Added experimental CLI integration.
	*	Moved webhook logic to REST API.
	*	Improved WordPress user profile Mollie section.
	*	Added WordPress admin dashboard page for Mollie customers.
	*	Added support for one-off SEPA Direct Debit payment method.
	*	Added support for payment failure reason.

= 5.9.0 - 2020-02-03 =
*	Updated WordPress pay core library to version 2.2.7.
	*	Added Google Analytics e-commerce `pronamic_pay_google_analytics_ecommerce_item_name` and `pronamic_pay_google_analytics_ecommerce_item_category` filters.
	*	Improved error handling for manual payment status check.
	*	Updated custom gender and date of birth input fields.
	*	Clean post cache to prevent duplicate status updates.
	*	Fixed duplicate payment for recurring payment.
*	Updated WordPress pay Easy Digital Downloads library to version 2.0.7.
	*	Improved custom input fields HTML markup and validation.
*	Updated WordPress pay Gravity Forms library to version 2.1.15.
	*	Only prorate subscription amount when form field has been set for recurring amount.
	*	Fixed incorrect currency with multicurrency add-on.
	*	Fixed subscription start with zero interval days.
*	Updated WordPress pay Adyen library to version 1.0.6.
	*	Added support for Drop-in integration (requires 'Origin Key' in gateway settings).
	*	Added application info support.
*	Updated WordPress pay MultiSafepay library to version 2.0.6.
	*	Improved error handling.
*	Updated Pronamic WordPress Money library to version 1.2.4.
*	Updated WordPress pay Charitable library to version 2.0.4.
	*	Fixed processing decimal input amounts.
*	Updated WordPress pay MemberPress library to version 2.0.13.
	*	Explicitly set transaction expiry date.
*	Updated WordPress pay Restrict Content Pro library to version 2.1.7.
	*	Fixed possible 'Fatal error: Call to a member function `get_user_id()` on boolean' in updater.
*	Updated WordPress pay Mollie library to version 2.0.10.
	*	Fixed notice 'Not Found - No customer exists with token cst_XXXXXXXXXX' in some cases.
*	Removed AppThemes integration.
*	Removed ClassiPress integration.
*	Removed iThemes Exchange integration (plugin is now Ninja Shop and not supported anymore).
*	Removed Jigoshop integration (plugin not under active development anymore).
*	Removed Shopp integration (plugin not under active development anymore).
*	Removed WPMU DEV Membership integration (plugin has been retired; see https://premium.wpmudev.org/retiring-our-legacy-plugins/).

= 5.8.1 - 2020-01-08 =
*	Updated WordPress pay core library to version 2.2.6.
	*	Added filter `pronamic_payment_gateway_configuration_id` for payment gateway configuration ID.
	*	Added filter `pronamic_pay_return_should_redirect` to move return checks to gateway integrations.
	*	Added Polylang home URL support in payment return URL.
	*	Added user display name in payment info meta box.
	*	Added consumer and bank transfer bank details.
	*	Added support for payment expiry date.
	*	Added support for gateway manual URL.
	*	Added new dependencies system.
	*	Added new upgrades system.
	*	Fixed incorrect day of month for yearly recurring payments when using synchronized payment date.
	*	Fixed not starting recurring payments for gateways which don't support recurring payments.
	*	Fixed default payment method in form processor if required.
	*	Fixed empty dashboard widgets for untranslated languages.
	*	Fixed submit button for manual subscription renewal.
	*	Fixed duplicate currency symbol in payment forms.
	*	Fixed stylesheet on payment redirect.
	*	Improved payment methods tab in gateway settings.
	*	Improved updating active payment methods.
	*	Improved error handling with exceptions.
	*	Improved update routine.
	*	Set subscription status 'On hold' for cancelled and expired payments.
	*	Do not auto update subscription status when status is 'On hold'.
	*	Renamed 'Expiry Date' to 'Paid up to' in subscription info meta box.
*	Updated WordPress pay Adyen library to version 1.0.5.
	*	Added Site Health test for HTTP authorization header.
	*	Added URL to manual in gateway settings.
	*	Added shopper email to payment request.
	*	Improved support for PHP 5.6.
*	Updated WordPress pay ING Kassa Compleet library to version 2.0.3.
	*	Added support for payments without method specified.
	*	Improved bank transfer payment instructions.
*	Updated WordPress pay ICEPAY library to version 2.0.6.
	*	Fixed processing ICEPAY postback.
*	Updated WordPress pay Mollie library to version 2.0.9.
	*	Added advanced setting for bank transfer due date days.
	*	Added bank transfer recipient details to payment.
	*	Removed Bitcoin payment method (not supported by Mollie anymore).
*	Updated WordPress pay OmniKassa 2.0 library to version 2.1.10.
	*	Added address fields validation.
*	Updated WordPress pay Sisow library to version 2.0.4.
	*	Added support for new `pronamic_pay_return_should_redirect` filter for notify and callback processing.
	*	Improved status updates for payments without transaction ID (i.e. iDEAL QR and iDEAL without issuer).
	*	Improved getting active payment methods for account.
*	Updated WordPress pay Easy Digital Downloads library to version 2.0.6.
	*	Added payment line ID support with variable price ID.
	*	Improved output HTML to match Easy Digital Downloads.
*	Updated WordPress pay Give library to version 2.0.4.
	*	Updated gateway settings.
*	Updated WordPress pay Gravity Forms library to version 2.1.14.
	*	Added merge tags for bank transfer recipient details.
	*	Added notice about subscription frequency being in addition to the first payment.
	*	Fixed synchronized payment date monthday and month settings.
	*	Improved payment method field creation.
*	Updated WordPress pay Restrict Content Pro library to version 2.1.6.
	*	Added support for new dependencies system.
	*	Added support for new upgrades system.
	*	Added upgrade script for payment and subscriptions source.
*	Updated WordPress pay AppThemes library to version 2.0.4.
*	Updated WordPress pay Buckaroo library to version 2.0.4.
*	Updated WordPress pay Charitable library to version 2.0.3.
*	Updated WordPress pay ClassiPress library to version 2.0.3.
*	Updated WordPress pay EMS e-Commerce library to version 2.0.4.
*	Updated WordPress pay Event Espresso library to version 2.1.3.
*	Updated WordPress pay Event Espresso (legacy) library to version 2.1.2.
*	Updated WordPress pay Formidable Forms library to version 2.0.4.
*	Updated WordPress pay iDEAL Advanced v3 library to version 2.0.5.
*	Updated WordPress pay iDEAL Basic library to version 2.0.5.
*	Updated WordPress pay iThemes Exchange library to version 2.0.3.
*	Updated WordPress pay Jigoshop library to version 2.0.4.
*	Updated WordPress pay MemberPress library to version 2.0.12.
*	Updated WordPress pay MultiSafepay library to version 2.0.5.
*	Updated WordPress pay Ninja Forms library to version 1.0.3.
*	Updated WordPress pay Nocks library to version 2.0.3.
*	Updated WordPress pay Ogone library to version 2.0.4.
*	Updated WordPress pay Pay.nl library to version 2.0.4.
*	Updated WordPress pay s2Member library to version 2.0.5.
*	Updated WordPress pay Shopp library to version 2.0.3.
*	Updated WordPress pay TargetPay library to version 2.0.3.
*	Updated WordPress pay WooCommerce library to version 2.0.10.
*	Updated WordPress pay WP eCommerce library to version 2.0.4.
*	Updated WordPress pay WPMU DEV Membership library to version 2.0.4.

= 5.8.0 - 2019-10-08 =
*	Updated WordPress pay core library to version 2.2.4.
	*	Updated `viison/address-splitter` library to version `0.3.3`.
	*	Move tools to site health debug information and status tests.
	*	Read plugin version from plugin file header.
	*	Catch money parser exception for test payments.
	*	Sepereated `Statuses` class in `PaymentStatus` and `SubscriptionStatus` class.
	*	Require `edit_payments` capability for payments related meta boxes on dashboard page.
	*	Set menu page capability to minimum required capability based on submenu pages.
	*	Only redirect to about page if not already viewed.
	*	Removed Google +1 button.
	*	Order payments by ascending date (fixes last payment as result in `Subscription::get_first_payment()`).
	*	Added new WordPress Pay icon.
	*	Added start, end, expiry, next payment (delivery) date to payment/subscription JSON.
	*	Introduced a custom REST API route for payments and subscriptions.
	*	Fixed handling settings field `filter` array.
	*	Catch and handle error when parsing input value to money object fails (i.e. empty string).
	*	Improved getting first subscription payment.
*	Updated WordPress pay Adyen library to version 1.0.4.
	*	Improved some exception messages.
*	Updated WordPress pay ICEPAY library to version 2.0.5.
	*	Added support for Klarna (Directebank) payment method.
	*	Update ICEPAY library version from 2.4.0 to 2.5.3.
*	Updated WordPress pay iDEAL Basic library to version 2.0.4.
	*	Fixed setting `deprecated` based on passed arguments.
*	Updated WordPress pay Mollie library to version 2.0.8.
	*	Added response data to error for unexpected response code.
	*	Moved next payment delivery date filter from gateway to integration class.
	*	Throw exception when Mollie response is not what we expect.
*	Updated WordPress pay OmniKassa 2.0 library to version 2.1.9.
	*	Use line 1 as street if address splitting failed (i.e. no house number given).
	*	Improved support for merchantOrderId = AN (Strictly)..Max 24 field.
*	Updated WordPress pay Gravity Forms library to version 2.1.12.
	*	Improved RTL support in 'Synchronized payment date' settings fields.
	*	Fixed loading extension in multisite when plugin is network activated and Gravity Forms is activated per site.
*	Updated WordPress pay MemberPress library to version 2.0.11.
	*	Fixed showing lifetime columns on MemberPress subscriptions page if plugin is loaded before MemberPress.
*	Updated WordPress pay Restrict Content Pro library to version 2.1.5.
	*	Restrict Content Pro 3.0 is required.
	*	Renew membership during `pronamic_pay_new_payment` routine and update membership expiration date and status on cancelled/expired/failed payment status update.
*	Updated WordPress pay s2Member library to version 2.0.4.
	*	Send user first and last name to list servers.
	*	Added s2Member plugin dependency.
	*	Added support for list server opt-in.
*	Updated WordPress pay WooCommerce library to version 2.0.9.
	*	Only update order status if order payment method is a WordPress Pay gateway.
	*	No longer disable 'Direct Debit' gateways when WooCommerce subscriptions is active and cart has no subscriptions [read more](https://github.com/wp-pay-extensions/woocommerce#conditional-payment-gateways).
	*	Changed redirect URL for cancelled and expired payments from cancel order to order pay URL.
	*	Allow payment gateway selection for order pay URL.

[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-ideal/changelog/)

== Links ==

*	[Pronamic](https://www.pronamic.eu/)
*	[Remco Tolsma](https://www.remcotolsma.nl/)
