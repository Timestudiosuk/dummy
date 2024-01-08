<?php
/**
 * Shipper modal templates: site selection, connecting to wpmudev template
 *
 * @since 1.2.1
 * @package shipper
 */

$assets = new Shipper_Helper_Assets();
?>
	<figure class="sui-box-logo" aria-hidden="true">
		<img
			src="<?php echo esc_url( $assets->get_asset( 'img/wpmudev.png' ) ); ?>"
			srcset="<?php echo esc_url( $assets->get_asset( 'img/wpmudev@2x.png' ) ); ?> 2x"
		>
	</figure>

	<button class="sui-button-icon sui-button-float--right shipper-cancel">
		<i class="sui-icon-close sui-md" aria-hidden="true"></i>
		<span class="sui-screen-reader-text">
				<?php esc_attr_e( 'Close the modal', 'shipper' ); ?>
			</span>
	</button>

	<h3 class="sui-box-title sui-lg">
		<?php esc_html_e( 'Checking Connection', 'shipper' ); ?>
	</h3>

	<p class="shipper-description">
		<?php esc_html_e( 'Please wait a few moments while we are checking the account connection…' ); ?>
	</p>

	<div class="shipper-connecting-wpmudev">
		<div class="shipper-box">
			<i class="sui-icon-shipper-anchor" aria-hidden="true"></i>
			<p><?php esc_html_e( 'Shipper', 'shipper' ); ?></p>
		</div>
		<div class="shipper-connecting-bar"></div>
		<div class="wpmudev-box">
			<i class="sui-icon-wpmudev-logo" aria-hidden="true"></i>
			<p><?php esc_html_e( 'WPMU DEV', 'shipper' ); ?></p>
		</div>
	</div>
</div>