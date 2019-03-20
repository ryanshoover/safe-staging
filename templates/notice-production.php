<?php
/**
 * Renders the Staging site notice in the admin
 *
 * @package safe-staging
 */

namespace SafeStaging;

?>
<div class="notice notice-safe-staging-production notice-warning">
	<p>
		<?php esc_html_e( 'This is a production site. Any changes will immediately go live.', 'safe-staging' ); ?>
	</p>
</div>
