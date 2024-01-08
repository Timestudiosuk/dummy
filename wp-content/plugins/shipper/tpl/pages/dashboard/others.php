<?php
/**
 * Shipper dashboard page templates: other page
 *
 * @since v1.1.4
 * @package shipper
 */

?>

<div class="shipper-dashboard-others sui-row">
	<div class="sui-col-md-6">
		<?php $this->render( 'pages/dashboard/package-migration', $args ); ?>
		<?php $this->render( 'pages/dashboard/tools' ); ?>
	</div>
	<div class="sui-col-md-6">
		<?php $this->render( 'pages/dashboard/api-migration', $args ); ?>
		<?php $this->render( 'pages/dashboard/settings' ); ?>
	</div>
</div>