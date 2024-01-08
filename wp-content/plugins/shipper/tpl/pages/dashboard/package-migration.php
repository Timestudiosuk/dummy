<?php
/**
 * Shipper dashboard page templates: package migration page
 *
 * @since v1.1.4
 * @package shipper
 */

?>

<div class="sui-box dashboard-package-migration">
	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<i class="sui-icon-zip" aria-hidden="true"></i>
			<?php esc_html_e( 'Package Migration', 'shipper' ); ?>
		</h2>
	</div>

	<div class="sui-box-body">
		<p>
			<?php esc_html_e( 'Download and upload a package of your site onto any server (local or live) and be migrated in a matter of minutes.', 'shipper' ); ?>
		</p>

		<?php if ( $is_package_migration && $has_package ) : ?>
			<div class="sui-field-list sui-flushed package-migration-list">
				<div class="sui-field-list-body">
					<div class="sui-field-list-item">
						<label class="sui-field-list-item-label">
							<strong><?php echo esc_html( $package_name ); ?></strong>
							<p>
								<?php echo esc_html( $last_migration ); ?>
							</p>
						</label>

						<span class="sui-tag"><?php echo esc_html( $formatted_package_size ); ?></span>

						<a
							href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-packages' ) ); ?>"
							class="sui-button-icon sui-tooltip sui-tooltip-top-right"
							data-tooltip="<?php esc_html_e( 'Download and install package', 'shipper' ); ?>"
						>
							<i class="sui-icon-arrow-right" aria-hidden="true"></i>
						</a>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div>
			<a
				href="<?php echo esc_url( network_admin_url( 'admin.php?page=shipper-packages&start=true' ) ); ?>"
				class="sui-button sui-button-primary shipper-new-packages <?php echo $package_migration_in_progress ? esc_attr( 'sui-button-onload-text' ) : ''; ?>"
			>
				<?php if ( ! $package_migration_in_progress ) : ?>
					<i class="sui-icon-plus" aria-hidden="true"></i>
					<?php esc_attr_e( 'Create Package', 'shipper' ); ?>
				<?php else : ?>
					<span class="sui-button-text-onload">
						<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
						<?php esc_attr_e( 'Creating Package', 'shipper' ); ?>
					</span>
				<?php endif; ?>
			</a>
		</div>
	</div>
</div>