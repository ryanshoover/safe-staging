<?php
/**
 * Renders the setup site notice in the admin
 *
 * @package safe-staging
 */

namespace SafeStaging;

?>
<div class="notice notice-safe-staging-setup notice-info is-dismissible">
	<p>
		<?php _e( 'Configure Safe Staging to protect your staging sites from accidental emails and orders.', 'safe-staging' ); ?>
		&nbsp;&nbsp;
		<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'options-general.php?page=' . SLUG ) ); ?>">Configure</a>
	</p>
</div>

<script>
$( '.notice-safe-staging-setup' ).click( function() {
	$.post(
		ajaxurl,
		{
			action: 'safe-staging-setup-dismiss',
		}
	);
} );
</script>
