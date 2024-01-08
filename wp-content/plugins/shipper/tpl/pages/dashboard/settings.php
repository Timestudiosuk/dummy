<?php
/**
 * Shipper dashboard page templates: settings page
 *
 * @since v1.1.4
 * @package shipper
 */

?>

<div class="sui-box dashboard-settings">
	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<i class="sui-icon-settings-slider-control" aria-hidden="true"></i>
			<?php esc_html_e( 'Settings', 'shipper' ); ?>
		</h2>
	</div>

	<div class="sui-box-body no-padding-bottom">
		<p>
			<?php esc_html_e( 'Configure your settings for the available migration methods', 'shipper' ); ?>
		</p>

		<div class="sui-field-list sui-flushed no-border dashboard-settings-list">
			<div class="sui-field-list-body">
				<div class="sui-field-list-item">
					<label class="sui-field-list-item-label">
						<strong>
							<i class="sui-icon-cloud title" aria-hidden="true"></i>
							<?php esc_html_e( 'API Migration', 'shipper' ); ?>
						</strong>
					</label>

					<a
						href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-settings&tool=migration' ) ); ?>"
						class="sui-button-icon sui-tooltip"
						data-tooltip="<?php esc_html_e( 'Configure', 'shipper' ); ?>"
					>
						<i class="sui-icon-widget-settings-config" aria-hidden="true"></i>
					</a>
				</div>

				<div class="sui-field-list-item">
					<label class="sui-field-list-item-label">
						<strong>
							<i class="sui-icon-zip title" aria-hidden="true"></i>
							<?php esc_html_e( 'Package Migration', 'shipper' ); ?>
						</strong>
					</label>

					<a
						href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-packages&tool=settings' ) ); ?>"
						class="sui-button-icon sui-tooltip"
						data-tooltip="<?php esc_html_e( 'Configure', 'shipper' ); ?>"
					>
						<i class="sui-icon-widget-settings-config" aria-hidden="true"></i>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="sui-box-footer">
		<a href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-settings' ) ); ?>" class="sui-button sui-button-ghost">
			<i class="sui-icon-wrench-tool" aria-hidden="true"></i>
			<?php esc_attr_e( 'All Settings', 'shipper' ); ?>
		</a>
	</div>
</div>