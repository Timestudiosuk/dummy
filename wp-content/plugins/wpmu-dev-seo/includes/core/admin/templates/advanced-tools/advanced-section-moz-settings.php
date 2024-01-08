<?php
/**
 * Moz API deactivation settings.
 *
 * @package SmartCrawl
 */

?>

<form method="post" class="wds-form">

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label" for="wds-default-redirection-type">
			<?php esc_html_e( 'Deactivate', 'wds' ); ?>
		</label>
		<p class="sui-description">
			<?php esc_html_e( 'No longer need MOZ? Deactivate MOZ here. This will also reset your MOZ credentials.', 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<button type="submit" name="deactivate-moz-component" class="sui-button-ghost sui-button">
			<span>
				<span class="sui-icon-power-on-off" aria-hidden="true"></span>
				<?php esc_html_e( 'Deactivate', 'wds' ); ?>
			</span>
		</button>

		<input type="hidden" name="_wp_http_referer" value="<?php echo esc_url( add_query_arg( 'tab', 'tab_moz', remove_query_arg( '_wp_http_referer' ) ) ); ?>">
		<?php $this->settings_fields( $_view['option_name'] ); ?>
	</div>
</div>

</form>