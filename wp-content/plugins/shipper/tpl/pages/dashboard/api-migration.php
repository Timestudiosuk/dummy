<?php
/**
 * Shipper dashboard page templates: api migration page
 *
 * @since v1.1.4
 * @package shipper
 */

$is_in_progress     = ! empty( $migration_in_progress['is_active'] );
$export_in_progress = $is_in_progress && ! empty( $migration_in_progress['type'] ) && 'export' === $migration_in_progress['type'];
$import_in_progress = $is_in_progress && ! empty( $migration_in_progress['type'] ) && 'import' === $migration_in_progress['type'];
?>

<div class="sui-box dashboard-api-migration">
	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<i class="sui-icon-cloud" aria-hidden="true"></i>
			<?php esc_html_e( 'API Migration', 'shipper' ); ?>
		</h2>
	</div>

	<div class="sui-box-body no-padding-bottom">
		<p>
			<?php esc_html_e( 'API migrations transfer everything directly to your new site using a super secure API. It only takes a few clicks and we handle everything for you.', 'shipper' ); ?>
		</p>

		<div class="sui-field-list sui-flushed no-border dashboard-api-migration-list">
			<div class="sui-field-list-body">
				<div class="sui-field-list-item">
					<label class="sui-field-list-item-label">
						<strong><?php esc_html_e( 'Export', 'shipper' ); ?></strong>
						<p>
							<?php esc_html_e( 'Migrate this WordPress site to another server.', 'shipper' ); ?>
						</p>
					</label>

					<a
						href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-api&type=export' ) ); ?>"
						class="sui-button sui-button-primary shipper-type-export
						<?php echo $is_in_progress ? esc_attr( 'sui-button-onload-text' ) : ''; ?>
						<?php echo $export_in_progress ? esc_attr( 'shipper-migration-in-progress' ) : ''; ?>"
					>
						<?php if ( $export_in_progress ) : ?>
							<span class="sui-button-text-onload">
								<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								<?php esc_attr_e( 'Migration in progress', 'shipper' ); ?>
							</span>
						<?php else : ?>
							<?php esc_attr_e( 'Begin Migration', 'shipper' ); ?>
						<?php endif; ?>
					</a>
				</div>

				<div class="sui-field-list-item">
					<label class="sui-field-list-item-label">
						<strong><?php esc_html_e( 'Import', 'shipper' ); ?></strong>
						<p>
							<?php esc_html_e( 'Overwrite this website by importing another existing site.', 'shipper' ); ?>
						</p>
					</label>

					<a
						href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-api&type=import' ) ); ?>"
						class="sui-button sui-button-primary shipper-type-import
						<?php echo $is_in_progress ? esc_attr( 'sui-button-onload-text' ) : ''; ?>
						<?php echo $import_in_progress ? esc_attr( 'shipper-migration-in-progress' ) : ''; ?>"
					>
						<?php if ( $import_in_progress ) : ?>
							<span class="sui-button-text-onload">
								<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								<?php esc_attr_e( 'Migration in progress', 'shipper' ); ?>
							</span>
						<?php else : ?>
							<?php esc_attr_e( 'Begin Migration', 'shipper' ); ?>
						<?php endif; ?>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="sui-box-footer shipper-content-center">
		<p>
			<?php esc_html_e( 'Sites migrated via the API will need to be connected to The Hub first.', 'shipper' ); ?>
		</p>
	</div>
</div>