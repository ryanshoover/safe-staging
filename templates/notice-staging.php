<?php
/**
 * Renders the Staging site notice in the admin
 *
 * @package safe-staging
 */

namespace SafeStaging;

?>
<div class="notice notice-safe-staging-staging notice-info">
	<p>
		<?php esc_html_e( 'This is a staging or local site. Production features are disabled.', 'safe-staging' ); ?>
	</p>
</div>
