<?php
/**
 * Shipper network-type modal
 *
 * @package shipper
 */

$modal_id              = 'shipper-network-type';
$modal_class           = sprintf(
	'%s %s',
	sanitize_html_class( 'shipper-network-type' ),
	sanitize_html_class( $modal_id )
);
$arguments['modal_id'] = $modal_id;
$data                  = wp_unslash( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce is already checked.
$type                  = isset( $data['type'] ) ? $data['type'] : 'export'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- this is not WordPress gl
$title                 = esc_html__( 'Migration Type', 'shipper' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- this is not WordPress global variable
if ( 'import' === $type ) {
	$title = esc_html__( 'Choose Subsite', 'shipper' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- this is not WordPress global variable
}
?>

<div class="sui-modal sui-modal-md">
	<div
		role="dialog"
		id="<?php echo esc_attr( $modal_id ); ?>"
		class="sui-modal-content sui-content-fade-in <?php echo esc_attr( $modal_class ); ?>"
		aria-modal="true"
		aria-labelledby="<?php echo esc_attr( $modal_id ); ?>-title"
		aria-describedby="<?php echo esc_attr( $modal_id ); ?>-description"
	>
		<div class="sui-box" role="document">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
				<h3 class="sui-box-title sui-lg">
					<?php echo esc_html( $title ); ?>
				</h3>

				<button class="sui-button-icon sui-button-float--right sui-modal-close">
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this modal window', 'shipper' ); ?></span>
				</button>
			</div>
			<div class="sui-box-body sui-box-body-slim">
				<div class="shipper-content">
					<div class="shipper-content-inside">
						<?php
						if ( 'export' === $type ) {
							$this->render( 'modals/network-type-export', array( 'sites' => $sites ) );
						} elseif ( 'import' === $type ) {
							$this->render( 'modals/network-type-import', array( 'sites' => $sites ) );
						}
						?>
					</div>
					<?php echo wp_kses_post( Shipper_Helper_Assets::get_custom_hero_image_markup() ); ?>
				</div>
			</div>
		</div>
	</div>
</div>