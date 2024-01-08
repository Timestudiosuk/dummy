<?php
/**
 * Shipper modal template partials: network type export.
 *
 * @package shipper
 */

$site = Shipper_Helper_MS::get_all_sites();
$site = ! empty( $site[0] ) ? $site[0] : false;
?>

<p class="sui-block-content-center">
	<?php
		esc_html_e( 'Do you want to export this whole multisite network to another multisite network, or one of the subsites to a single site installation?', 'shipper' );
	?>
</p>
<label class="sui-label"><?php esc_html_e( 'Choose Migration Type', 'shipper' ); ?></label>
<div class="sui-side-tabs">
	<div class="sui-tabs-menu">
		<label for="whole_network" class="sui-tab-item active">
			<input type="radio" value="whole_network" name="network_mode" id="whole_network" data-tab-menu="whole-network">
			<?php esc_html_e( 'Whole Network', 'shipper' ); ?>
		</label>
		<label for="subsite" class="sui-tab-item">
			<input type="radio" value="subsite" name="network_mode" data-tab-menu="subsite-box" id="subsite">
			<?php esc_html_e( 'Subsite to Single site', 'shipper' ); ?>
		</label>
	</div>
	<div class="sui-tabs-content">
		<div role="tabpanel" tabindex="0" class="sui-tab-content sui-tab-boxed" id="whole-network" data-tab-content="whole-network">
			<div class="sui-notice sui-notice-info">
				<div class="sui-notice-content">
					<div class="sui-notice-message">
						<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
						<p>
							<?php esc_html_e( 'Note that this option will not merge the exported network with the destination network. It will migrate the whole multisite and overwrite the entire destination network.', 'shipper' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>

		<div role="tabpanel" tabindex="0" class="sui-tab-content sui-tab-boxed" id="subsite-box" data-tab-content="subsite-box">
			<label for="shipper-custom-prefix" class="sui-label"><?php esc_html_e( 'Choose Subsite', 'shipper' ); ?></label>

			<select
				class="sui-select"
				id="all-sites"
				name="site_id"
				data-theme="search"
				data-search="true"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'shipper_search_sub_site' ) ); ?>"
			>
				<?php if ( $site ) : ?>
					<option value="<?php echo esc_attr( $site->blog_id ); ?>"><?php echo esc_url( $site->domain . rtrim( $site->path, '/' ) ); ?></option>
				<?php endif; ?>
			</select>
		</div>
	</div>
</div>

<button type="button" class="sui-button shipper-update-networktype pull-right">
	<?php esc_html_e( 'Next', 'shipper' ); ?>
</button>
<div class="clearfix"></div>