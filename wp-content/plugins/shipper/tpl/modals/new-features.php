<?php
/**
 * Shipper modal template: whats new page
 *
 * @since v1.2.1
 *
 * @package shipper
 */

?>
<div class="sui-modal sui-modal-md">
	<div
		role="dialog"
		id="shipper-new-features"
		class="sui-modal-content sui-content-fade-in"
		aria-modal="true"
		aria-labelledby="shipper-new-features-title"
		aria-describedby="shipper-new-features-description">
			<div class="sui-box">
				<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
					<?php echo wp_kses_post( Shipper_Helper_Assets::get_custom_hero_image_markup() ); ?>
					<button class="sui-button-icon sui-button-float--right shipper-cancel" data-modal-close="shipper-new-features">
						<i class="sui-icon-close sui-md" aria-hidden="true"></i>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close', 'shipper' ); ?></span>
					</button>

					<h3 id="shipper-new-features-title" class="sui-box-title sui-lg">
						<?php echo esc_html( $title ); ?>
					</h3>

					<p id="shipper-new-features-description" class="shipper-description">
						<?php echo wp_kses_post( $description ); ?>
					</p>
				</div>

				<div class="sui-box-body">
					<ul class="shipper-features">
						<?php foreach ( $features as $feature ) : ?>
							<li>
								<p class="title shipper-description">
									<?php echo esc_html( $feature['title'] ); ?>
								</p>

								<p class="shipper-description">
									<?php echo esc_html( $feature['description'] ); ?>
								<p>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

			<div class="sui-box-footer sui-flatten sui-content-center">
					<button
						id="shipper-got-it"
						role="button"
						class="sui-button"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'shipper_new_features_modal_closed' ) ); ?>"
					>
						<?php esc_html_e( 'GOT IT', 'shipper' ); ?>
					</button>
				</div>
			</div>
	</div>
</div>