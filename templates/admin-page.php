<?php
/**
 * Renders the Admin Settings page
 *
 * @package wc-settings
 */

namespace SafeStaging;

?>
<div class="wrap">
	<h1><?php esc_html_e( 'Safe Staging', 'safe-staging' ); ?></h1>
	<form method="post" action="options.php">
		<?php settings_fields( SLUG ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Production URL', 'safe-staging' ); ?>
				</th>
				<td>
					<input
						type="url"
						class="regular-text code"
						name="<?php echo esc_attr( OPT_PROD_URL ); ?>"
						value="<?php echo esc_attr( production_url() ); ?>"
					>
				</td>
			</tr>

		</table>
		<?php submit_button(); ?>
	</form>
</div>
