<?php
/**
 * Shipper package settings templates: be safe-mode item title template
 *
 * @since v1.2.4
 * @package shipper
 */

?>
<div class="shipper-settings-item-title">
	<span class="sui-settings-label">
		<?php esc_html_e( 'Safe Mode', 'shipper' ); ?>
	</span>
</div>
<div class="shipper-settings-item-summary">
	<span class="sui-description">
		<?php esc_html_e( 'Due to the lack of processing power, Package Migration can fail on a constrained server.', 'shipper' ); ?>
	</span>
</div>