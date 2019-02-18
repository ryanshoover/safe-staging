<?php
/**
 * Safe Staging
 *
 * @package     safe-staging
 * @author      ryanshoover
 * @license     GPL3
 *
 * @wordpress-plugin
 * Plugin Name: Safe Staging
 * Plugin URI:  https://github.com/ryanshoover/safe-staging
 * Description: Disables user activities on a staging site
 * Version:     0.2.3
 * Author:      ryanshoover
 * Author URI:  https://ryan.hoover.ws
 * Text Domain: safe-staging
 * License:     GPL3
 */

namespace SafeStaging;

/**
 * Autoloader function
 *
 * @param string $class Name of the class to load.
 */
function autoload ( $class ) {
	if ( strncmp( __NAMESPACE__, $class, strlen( __NAMESPACE__ ) ) !== 0 ) {
		return;
	}
	$file_name = str_replace( [ '\\', '_' ], [ '/', '-' ], strtolower( substr( $class, strlen( __NAMESPACE__ ) + 1 ) ) ) . '.php';
	$file_name = preg_replace( '/([\w-]+)\.php/', 'class-$1.php', $file_name );
	$file      = __DIR__ . '/inc/' . $file_name;
	if ( realpath( $file ) === $file && file_exists( $file ) ) {
		require_once $file;
	}
}

spl_autoload_register( __NAMESPACE__ . '\autoload' );

define( __NAMESPACE__ . '\PATH', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\URL', plugin_dir_url( __FILE__ ) );
define( __NAMESPACE__ . '\SLUG', 'safe-staging' );
define( __NAMESPACE__ . '\OPT_PROD_URL', SLUG . '_production_url' );

add_action(
	'plugins_loaded',
	function() {
		Protection::hooks();
		Notices::hooks();

		if ( is_admin() ) {
			Admin_Page::init();
		}
	}
);

/**
 * Get the production URL.
 *
 * Returns the production URL from the site option after decoding.
 *
 * @return string Production URL from site options.
 */
function production_url() {
	$url = get_option( OPT_PROD_URL );

	// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
	$url = base64_decode( $url );

	return apply_filters( 'safe_staging_production_url', $url );
}
