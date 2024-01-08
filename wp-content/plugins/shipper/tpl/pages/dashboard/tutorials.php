<?php
/**
 * Shipper dashboard page templates: tutorials page
 *
 * @since v1.1.6
 * @package shipper
 */

?>

<div class="sui-box" id="dashboard-tutorials">
	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<?php esc_html_e( 'Tutorials', 'shipper' ); ?>
		</h2>

		<div class="sui-actions-right">
			<?php if ( Shipper_Helper_Assets::has_docs_links() ) { ?>
				<a href="https://wpmudev.com/blog/tutorials/tutorial-category/shipper-pro/?utm_source=shipper&utm_medium=plugin&utm_campaign=shipper_tutorials_alltutorials" target="_blank" class="sui-button sui-button-ghost shipper-view-all">
					<span class="sui-icon-open-new-window" aria-hidden="true"></span>
					<?php esc_html_e( 'View All', 'shipper' ); ?>
				</a>

				<button
					data-tooltip="<?php esc_html_e( 'Hide Tutorials', 'shipper' ); ?>"
					aria-label="<?php esc_html_e( 'Hide Tutorials', 'shipper' ); ?>"
					class="sui-button-icon sui-tooltip hide-shipper-tutorials"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'hide-tutorials' ) ); ?>"
				>
					<span class="sui-icon-close"></span>
				</button>
			<?php } ?>
		</div>
	</div>

	<div class="sui-box-body">
		<div class="sui-row">
			<?php foreach ( $tutorials as $tutorial ) : ?>
				<div class="sui-col-lg-3 sui-col-md-6 sui-col-sm-6 shipper-tutorial-item">
					<div class="shipper-tutorial-title">
						<img src="<?php echo esc_url( $tutorial->image_url ); ?>">
						<div class="shipper-tutorial-title-text">
							<small>
								<a target="_blank" href="<?php echo esc_url( $tutorial->url ); ?>">
									<?php echo wp_kses_post( $tutorial->title ); ?>
								</a>
							</small>

							<p class="sui-description">
								<span class="sui-icon-clock"></span>
								<?php echo esc_html( $tutorial->read_time ); ?>
							</p>
						</div>
					</div>
					<p class="sui-description shipper-tutorial-desc">
						<?php echo wp_kses_post( $tutorial->excerpt ); ?>
					</p>

					<p class="shipper-tutorial-read">
						<small>
							<a target="_blank" href="<?php echo esc_url( $tutorial->url ); ?>">
								<?php esc_html_e( 'Read article', 'shipper' ); ?>
							</a>
						</small>
					</p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>