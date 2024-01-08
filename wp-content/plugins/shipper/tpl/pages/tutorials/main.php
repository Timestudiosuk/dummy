<?php
/**
 * Shipper tutorials page templates: main page
 *
 * @since v1.2.6
 * @package shipper
 */

?>

<div id="shipper-page-tutorials" class="<?php echo esc_attr( Shipper_Helper_Assets::get_page_class( 'tutorials' ) ); ?>" >

	<?php $this->render( 'pages/header' ); ?>

	<div class="sui-header">
		<h1 class="sui-header-title"><?php esc_html_e( 'Tutorials', 'shipper' ); ?></h1>
		<?php $this->render( 'pages/tutorials/view-all' ); ?>
	</div>

	<div class="sui-box">
		<div class="sui-box-header">
			<h3 class="sui-box-title">
				<?php esc_html_e( 'Shipper Tutorials', 'shipper' ); ?>
			</h3>
		</div>
		<div class="sui-box-body">
			<div class="sui-row">
			<?php foreach ( $tutorials as $index => $tutorial ) : ?>

				<?php if ( 0 !== $index && 0 === $index % 4 ) : ?>
					</div>
					<div class="sui-row">
				<?php endif; ?>
					<div class="sui-col-lg-3 sui-col-md-6 sui-col-sm-6 shipper-tutorial-post">
					<?php
					$tpl->render(
						'pages/tutorials/card',
						array(
							'tutorial' => $tutorial,
							'index'    => $index,
						)
					);
					?>

				</div>
			<?php endforeach; ?>
			</div>
		</div>
	</div>

	<?php
	$this->render( 'pages/footer' );
	?>
</div>