<?php
/**
 * Shipper dashboard page templates: tools page
 *
 * @since v1.1.4
 * @package shipper
 */

$download_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=shipper_download_log' ), 'shipper_log_download' );
?>

<div class="sui-box dashboard-tools">
	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<i class="sui-icon-wrench-tool" aria-hidden="true"></i>
			Tools
		</h2>
	</div>

	<div class="sui-box-body no-padding-bottom">
		<p>
			<?php esc_html_e( 'Keep an eye on your migrations and system information.', 'shipper' ); ?>
		</p>

		<div class="sui-field-list sui-flushed no-border dashboard-tools-list">
			<div class="sui-field-list-body">
				<div class="sui-field-list-item">
					<label class="sui-field-list-item-label">
						<strong>
							<i class="sui-icon-clipboard-notes title" aria-hidden="true"></i>
							<?php esc_html_e( 'Migration logs', 'shipper' ); ?>
						</strong>
					</label>

					<a
						href="<?php echo esc_url( $download_url ); ?>"
						class="sui-button-icon sui-tooltip sui-margin-right"
						data-tooltip="<?php esc_attr_e( 'Download logs', 'shipper' ); ?>"
					>
						<i class="sui-icon-download" aria-hidden="true"></i>
					</a>

					<a
						href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-tools&tool=logs' ) ); ?>"
						class="sui-button-icon sui-tooltip"
						data-tooltip="<?php esc_attr_e( 'View', 'shipper' ); ?>"
					>
						<i class="sui-icon-eye" aria-hidden="true"></i>
					</a>
				</div>

				<div class="sui-field-list-item">
					<label class="sui-field-list-item-label">
						<strong>
							<i class="sui-icon-lightbulb title" aria-hidden="true"></i>
							<?php esc_html_e( 'System Information', 'shipper' ); ?>
						</strong>
					</label>

					<a
						href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-tools&tool=sysinfo' ) ); ?>"
						class="sui-button-icon sui-tooltip"
						data-tooltip="<?php esc_attr_e( 'View', 'shipper' ); ?>"
					>
						<i class="sui-icon-eye" aria-hidden="true"></i>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="sui-box-footer">
		<a href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-tools' ) ); ?>" class="sui-button sui-button-ghost">
			<i class="sui-icon-eye" aria-hidden="true"></i>
			<?php esc_attr_e( 'View All', 'shipper' ); ?>
		</a>
	</div>
</div>