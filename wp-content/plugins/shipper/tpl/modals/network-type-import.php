<?php
/**
 * Shipper model templates: network type import
 *
 * @package shipper
 */

$model = new Shipper_Model_Stored_Migration();
?>
<p class="sui-block-content-center">
	<?php
	echo wp_kses_post(
		sprintf(
			/* translators: %s: destination site url.*/
			__( '<strong>%s</strong> is a multisite network, choose which subsite you want to import as a single site.', 'shipper' ),
			untrailingslashit( shipper_get_protocol_agnostic( $model->get_destination( true ), true ) )
		)
	);
	?>
</p>
<div class="sui-form-field">
	<label class="sui-label"><?php esc_html_e( 'Choose Subsite', 'shipper' ); ?></label>
	<select class="sui-select" id="all-sites" name="site_id">
		<?php foreach ( $sites as $site ) : ?>
			<option value="<?php echo esc_attr( $site['blog_id'] ); ?>"><?php echo esc_url( $site['domain'] . rtrim( $site['path'], '/' ) ); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="hidden" name="network_mode" value="subsite">
</div>
<button type="button" class="sui-button shipper-update-networktype pull-right">
	<?php esc_html_e( 'Next', 'shipper' ); ?>
</button>
<div class="clearfix"></div>