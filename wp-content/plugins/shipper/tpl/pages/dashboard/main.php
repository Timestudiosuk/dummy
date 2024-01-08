<?php
/**
 * Shipper dashboard page templates: main page
 *
 * @since v1.1.4
 * @package shipper
 */

?>

<div class="<?php echo esc_attr( Shipper_Helper_Assets::get_page_class( 'dashboard' ) ); ?>" >

	<?php $this->render( 'pages/header' ); ?>

	<div class="sui-header">
		<h1 class="sui-header-title"><?php esc_html_e( 'Dashboard', 'shipper' ); ?></h1>
		<?php $this->render( 'pages/dashboard/view-docs' ); ?>
	</div>

	<?php
	$this->render( 'modals/check/new-features' );
	$this->render( 'pages/dashboard/summary', $args );

	if ( ! empty( $args['tutorials'] ) ) {
		$this->render( 'pages/dashboard/tutorials', $args );
	}

	$this->render( 'pages/dashboard/others', $args );
	$this->render( 'pages/footer' );
	?>
</div>