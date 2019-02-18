<?php
/**
 * Uninstall the plugin.
 *
 * @package safe-staging
 */

namespace SafeStaging;

// If uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

delete_option( OPT_PROD_URL );

global $wpdb;

$wpdb->delete(
	$wpdb->usermeta,
	[
		'meta_key' => SLUG . '_notice_setup_dismiss',
	]
);
