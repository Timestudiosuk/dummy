<?php
/*
 * No direct access to this file
 */

use WpAssetCleanUp\AssetsManager;

if (! isset($data)) {
	exit;
}

// Is managing the assets within the Dashboard allowed globally or for the current admin?
if ( ! AssetsManager::instance()->currentUserCanViewAssetsList() ) {
	?>
	<div class="wpacu-error" style="padding: 10px;">
		<?php echo sprintf(esc_html__('Only the administrators listed here can manage CSS/JS assets: %s"Settings" &#10141; "Plugin Usage Preferences" &#10141; "Allow managing assets to:"%s. If you believe you should have access to managing CSS/JS assets, you can add yourself to that list.', 'wp-asset-clean-up'), '<a target="_blank" href="'.esc_url(admin_url('admin.php?page=wpassetcleanup_settings&wpacu_selected_tab_area=wpacu-setting-plugin-usage-settings')).'">', '</a>'); ?></div>
	<?php
	$data['dashboard_edit_not_allowed'] = true;
}

if ($data['wpacu_settings']['dashboard_show'] != 1) {
	?>
    <div class="wpacu-error" style="padding: 10px; margin-left: 0;"><?php echo sprintf(esc_html__('As "Manage in the Dashboard?" is not enabled in %s"Settings" &#187; "Plugin Usage Preferences"%s, you can not manage the assets from the Dashboard.', 'wp-asset-clean-up'), '<a target="_blank" href="'.esc_url(admin_url('admin.php?page=wpassetcleanup_settings&wpacu_selected_tab_area=wpacu-setting-plugin-usage-settings')).'">', '</a>'); ?></div>
	<?php
	$data['dashboard_edit_not_allowed'] = true;
}
