<?php
/**
 * Shipper templates: view all menu action
 *
 * @since v1.1.6
 * @package shipper
 */

?>

<div class="sui-actions-right">
	<?php if ( Shipper_Helper_Assets::has_docs_links() ) { ?>
		<a href="https://wpmudev.com/blog/tutorials/tutorial-category/shipper-pro/?utm_source=shipper&utm_medium=plugin&utm_campaign=shipper_tutorials_alltutorials" target="_blank" class="sui-button sui-button-ghost">
			<span class="sui-icon-open-new-window" aria-hidden="true"></span>
			<?php esc_html_e( 'View All', 'shipper' ); ?>
		</a>
	<?php } ?>
</div>