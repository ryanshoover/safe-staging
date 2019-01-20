<?php
/**
 * Safe Staging
 *
 * @package     safe-staging
 * @author      ryanshoover
 * @license     Proprietary
 *
 * @wordpress-plugin
 * Plugin Name: Safe Staging
 * Plugin URI:  https://hoover.ws
 * Description: Disables user activities on a staging site
 * Version:     0.1.0
 * Author:      ryanshoover
 * Author URI:  https://ryan.hoover.ws
 * Text Domain: safe-staging
 * License:     GPL2
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
	$file_name           = str_replace( [ '\\', '_' ], [ '/', '-' ], strtolower( substr( $class, strlen( __NAMESPACE__ ) + 1 ) ) ) . '.php';
	$class_file_name     = preg_replace( '/([\w-]+)\.php/', 'class-$1.php', $file_name );
	$interface_file_name = preg_replace( '/([\w-]+)\.php/', 'interface-$1.php', $file_name );
	$class_file          = __DIR__ . '/inc/' . $class_file_name;
	$interface_file      = __DIR__ . '/inc/' . $interface_file_name;
	if ( file_exists( $class_file ) ) {
		require_once $class_file;
	} elseif ( file_exists( $interface_file ) ) {
		require_once $interface_file;
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
 * @return string Production URL from site options.
 */
function production_url() {
	$url = get_option( OPT_PROD_URL );

	// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
	return base64_decode( $url );
}
