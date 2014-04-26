<?php

if ( ! function_exists( 'wp_slash' ) ) {
	/**
	 * Add slashes to a string or array of strings.
	 *
	 * This should be used when preparing data for core API that expects slashed data.
	 * This should not be used to escape data going directly into an SQL query.
	 *
	 * @since 3.6.0
	 *
	 * @param string|array $value String or array of strings to slash.
	 * @return string|array Slashed $value
	 */
	function wp_slash( $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $k => $v ) {
				if ( is_array( $v ) ) {
					$value[ $k ] = wp_slash( $v );
				} else {
					$value[ $k ] = addslashes( $v );
				}
			}
		} else {
			$value = addslashes( $value );
		}

		return $value;
	}
}
