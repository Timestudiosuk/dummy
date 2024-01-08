<?php
/**
 * Shipper migration exclusion filters.
 *
 * @since v1.2.6
 * @package shipper
 */

$button = isset( $_GET['type'] ) // phpcs:ignore
	? __( 'Next', 'shipper' )
	: __( 'BUILD PACKAGE', 'shipper' );
?>
<div class="sui-accordion shipper-exclusion-accordion">
	<div class="sui-accordion-item">
		<div class="sui-accordion-item-header">
			<div class="sui-accordion-item-title">
				<span aria-hidden="true" class="sui-icon-warning-alert"></span>
				<?php esc_html_e( 'How to apply the Exclusion filters?', 'shipper' ); ?>
			</div>

			<div class="sui-accordion-col-auto">
				<button class="sui-button-icon sui-accordion-open-indicator" aria-label="Open item">
					<span class="sui-icon-chevron-down" aria-hidden="true"></span>
				</button>
			</div>
		</div>
		<div class="sui-accordion-item-body">
			<div class="sui-box">
				<div class="sui-box-body">
					<div class="sui-description">
						<?php
						esc_html_e( 'By default, the export process includes the entire selected website, but depending on your goal, you may not want to migrate every file associated with a site. You can use filters to exclude specific files.', 'shipper' );
						?>
					</div>

					<div class="shipper-exclusion-lists">
						<div class="shipper-exclusion-list">
							<span class="sui-icon-arrow-right" aria-hidden="true"></span>
							<div>
								<p>
									<?php
									echo wp_kses_post(
										sprintf(
											/* translators: %1$s: button title */
											__( 'If you wish to <strong>migrate your entire site</strong>, click %1$s to run the pre-flight check', 'shipper' ),
											$button
										)
									);
									?>
								</p>
							</div>
						</div>

						<div class="shipper-exclusion-list">
							<span class="sui-icon-arrow-right" aria-hidden="true"></span>

							<div>
								<p>
									<?php
									echo wp_kses_post(
										__( 'You can exclude <strong>specific files</strong> from being migrated using the “File Exclusion Filter” tool. There are shortcut buttons available that, when clicked, will add the correct path for the Themes, Plugins, or Uploads folders to the exclusion list. You can also add the path to the folders, files, extensions, or directories that you don\'t want to include in your migration:', 'shipper' )
									);
									?>
								</p>

								<ul>
									<li><?php echo wp_kses_post( __( '<strong>.zip</strong> will exclude all files with the ZIP extension.', 'shipper' ) ); ?></li>
									<li><?php echo wp_kses_post( __( '<strong>/entire-folder/</strong> will exclude the folder with this specific name.', 'shipper' ) ); ?></li>
									<li><?php echo wp_kses_post( __( '<strong>/folder/file.zip</strong> will exclude this specific file in this specific folder.', 'shipper' ) ); ?></li>
									<li><?php echo wp_kses_post( __( '<strong>/folder/*.txt</strong> will exclude all files with the TXT extension at the end, but only in the directory named FOLDER.', 'shipper' ) ); ?></li>
									<li><?php echo wp_kses_post( __( '<strong>/folder/sub-folder/*.*</strong> will exclude all files with any extension in the directory  SUB-FOLDER which is a subdirectory of FOLDER, but other directories inside FOLDER/SUB-FOLDER will not be affected.', 'shipper' ) ); ?></li>
									<li><?php echo wp_kses_post( __( '<strong>*/IgnoreDir/*.*</strong> will exclude all files inside any directory called IGNOREDIR.', 'shipper' ) ); ?></li>
								</ul>
							</div>
						</div>

						<div class="shipper-exclusion-list">
							<span class="sui-icon-arrow-right" aria-hidden="true"></span>
							<p>
								<?php
								echo wp_kses_post(
									__( 'Select the <strong>Database</strong> tab to access a series of dropdown menus that allow you to selectively exclude tables.', 'shipper' )
								);
								?>
							</p>
						</div>

						<div class="shipper-exclusion-list">
							<span class="sui-icon-arrow-right" aria-hidden="true"></span>
							<div>
								<p>
									<?php
									echo wp_kses_post(
										__( 'Select the <strong>Advanced</strong> tab to access a list of options that allow you to exclude some seldom-used files maintained by WordPress. These include:', 'shipper' )
									);
									?>
								</p>

								<ul>
									<li><?php esc_html_e( 'Exclude spam comments', 'shipper' ); ?></li>
									<li><?php esc_html_e( 'Exclude post revisions', 'shipper' ); ?></li>
									<li><?php esc_html_e( 'Exclude inactive themes', 'shipper' ); ?></li>
									<li><?php esc_html_e( 'Exclude inactive plugins', 'shipper' ); ?></li>
								</ul>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>
</div>