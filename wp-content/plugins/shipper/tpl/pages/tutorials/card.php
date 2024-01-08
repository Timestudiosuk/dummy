<?php
/**
 * Shipper tutorials page templates: card
 *
 * @since v1.2.6
 * @package shipper
 */

?>

<div
	tabindex="0"
	role="link"
	class="shipper-tutorial-post-wrapper"
	aria-label="
	<?php
	echo esc_html(
		/* translators: %s: read the article. */
		sprintf( __( 'Read article %s', 'shipper' ), $tutorial->title )
	);
	?>
	"
	data-href="<?php echo esc_url( $tutorial->url ); ?>"
	data-tutorial="<?php echo esc_attr( $index ); ?>"
>

	<div class="shipper-tutorial-post-img">
		<img src="<?php echo esc_url( $tutorial->image_url ); ?>" alt="<?php echo esc_attr( $tutorial->title ); ?>">
	</div>

	<div class="shipper-tutorial-post-body">
		<small class="no-margin-bottom shipper-tutorial-post-body-title">
			<?php echo esc_html( $tutorial->title ); ?>
		</small>

		<p class="sui-description shipper-tutorial-post-body-desc">
			<?php echo wp_kses_post( $tutorial->excerpt ); ?>
		</p>

		<footer class="shipper-tutorial-post-body-footer">
			<div class="shipper-read-more">
				<a tabindex="-1" target="_blank" href="<?php echo esc_url( $tutorial->url ); ?>">
					<?php esc_html_e( 'Read article', 'shipper' ); ?>
				</a>
			</div>
			<div class="shipper-time">
				<span class="sui-icon-clock" aria-hidden="true"></span>
				<?php echo esc_html( $tutorial->read_time ); ?>
			</div>
		</footer>
	</div>
</div>