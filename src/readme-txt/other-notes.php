== Are there any known plugin conflicts? ==

Unfortunately WordPress is notorious for conflicts between themes and plugins. It is unavoidable as you have no control over what other plugins and themes do. While we do take steps to avoid conflicts as best we can, we have no control over other plugins or themes.

As conflicts are found we will update this list. If you discover a conflict with a another plugin, please notify us.

Here is a list of known plugin conflicts:

=== WPML ===

The [WPML](https://wpml.org/) plugin(s) can conflict with multiple gateways. A lot of the gateways use `home_url( '/' )` to retrieve the WordPress home URL. The WPML plugins hooks in to this function to change the home URL to the correct language URL. This can result in incorrect checksums, signatures and hashes.

=== WordPress HTTPS ===

The [WordPress HTTPS](https://wordpress.org/plugins/wordpress-https/) can conflict with the OmniKassa payment method. It can cause invalid signature errors. The WordPress HTTPS plugin parses the complete output of an WordPress website and changes 'http' URLs to 'https' URLs, this  results in OmniKassa data that no longer matches the signature.
