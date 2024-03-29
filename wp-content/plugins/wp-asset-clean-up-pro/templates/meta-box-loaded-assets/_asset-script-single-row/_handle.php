<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

// [wpacu_pro]
use WpAssetCleanUp\Main;

if ( ! isset($assetLocationChanged) ) {
    exit; // no direct access
}
// [/wpacu_pro]

if ( ! isset($data, $isCoreFile, $hideCoreFiles, $jqueryIconHtmlHandle, $childHandles) ) {
	exit; // no direct access
}
?>
<div class="wpacu_handle">
	<label for="script_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"> <?php _e('Handle:', 'wp-asset-clean-up'); ?> <strong><span style="color: green;"><?php echo esc_attr($data['row']['obj']->handle); ?></span></strong> <?php if (in_array($data['row']['obj']->handle, array('jquery', 'jquery-core', 'jquery-migrate'))) { echo '&nbsp;'.$jqueryIconHtmlHandle; } ?></label>
	&nbsp;<em>* JavaScript (.js)</em>
    <?php
    if ($data['row']['obj']->handle === 'swiper') {
        ?>
        &#10230; <span style="color: #ccc;" class="dashicons dashicons-text-page"></span> <a href="https://assetcleanup.com/docs/?p=737" target="_blank" rel="noopener noreferrer">Read more</a>
        <?php
    }

    if ($isCoreFile && ! $hideCoreFiles) { ?>
		<span class="dashicons dashicons-wordpress-alt wordpress-core-file"><span class="wpacu-tooltip">WordPress Core File<br />Not sure if needed or not? In this case, it's better to leave it loaded to avoid breaking the website.</span></span>
		<?php
	}

	if (isset($data['load_exceptions_debug']['scripts']) && in_array($data['row']['obj']->handle, $data['load_exceptions_debug']['scripts'])) {
		// '/?wpacu_load_js=' was used and has the handle within its value
	    echo '&nbsp; <span style="color: green; font-style: italic;"><strong>Load Exception:</strong> This handle is loading for you on this page as requested via the "wpacu_load_js" value from the current page URL (for debugging purposes).</span>';
    } elseif (isset($data['current_debug']['scripts']) && in_array($data['row']['obj']->handle, $data['current_debug']['scripts'])) {
		// '/?wpacu_unload_js=' was used and has the handle within its value
	    echo '&nbsp; <span style="color: #cc0000; font-style: italic;"><strong>Unload Exception:</strong> This handle is unloaded for you on this page as requested via the "wpacu_unload_js" value from the current page URL (for debugging purposes).</span>';
	}

	// Any conditions set such as "IE" or "lt IE 8"?
    $dataRowExtra = (array)$data['row']['obj']->extra;
    // Notify the user the assets load only on Internet Explorer
	if ( isset( $dataRowExtra['conditional'] ) && $dataRowExtra['conditional'] && strpos( $dataRowExtra['conditional'], 'IE' ) !== false ) {
        echo '&nbsp;&nbsp;<span><img style="vertical-align: middle;" width="25" height="25" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-ie.svg" alt="" title="Microsoft / Public domain" />&nbsp;<span style="font-weight: 400; color: #1C87CF;">Loads only in Internet Explorer based on the following condition:</span> <em> if '.$dataRowExtra['conditional'].'</em></span>';
    }

    // [wpacu_pro]
    if ($assetLocationChanged) {
	    $data['row']['at_least_one_rule_set'] = true;
    ?>
		<div style="display: inline-block; color: #004567; font-style: italic; font-size: 90%; font-weight: 600; margin-left: 15px;">
			<span class="dashicons dashicons-info" style="font-size: 19px; line-height: normal;"></span> <?php _e('This file has its initial location changed.', 'wp-asset-clean-up'); ?>
		</div>
		<?php
	}
	// [/wpacu_pro]
	?>
</div>
<?php
$ignoreChild = (isset($data['ignore_child']['scripts'][$data['row']['obj']->handle]) && $data['ignore_child']['scripts'][$data['row']['obj']->handle]);
if ($ignoreChild) { $data['row']['at_least_one_rule_set'] = true; }

// If not 'jquery-migrate' show it
// If it's 'jquery-migrate' and has more than one "child" (apart from jQuery) show it
$showDependencyNotice = ($data['row']['obj']->handle === 'jquery-migrate' && count($childHandles) > 1) || ($data['row']['obj']->handle !== 'jquery-migrate');

if (! empty($childHandles) && $showDependencyNotice) {
?>
	<div class="wpacu_dependency_notice_area">
        <?php
        if ($data['row']['obj']->handle === 'jquery-migrate') {
            ?>
            <em style="font-size: 85%;">Special Case: If jQuery Migrate is marked for unload (which often is good if you don't need it), its official "child" (as it's mentioned in the WordPress core), jQuery, will not be unloaded. However, if there are other JS scripts from the plugins or the theme that are linked to jQuery Migrate, then it's better to keep it loaded.</em>
            <?php
        }
        ?>
        <em style="font-size: 85%;">
            <span style="color: #0073aa; width: 19px; height: 19px; vertical-align: middle;" class="dashicons dashicons-info"></span>
            There are JS "children" files depending on this file. By unloading it, the following will also be unloaded:
            <span style="color: green; font-weight: 400;">
            <?php
            $childHandlesOutput = '';
            foreach ($childHandles as $childHandle) {
                $childHandleText = $childHandle;
                $title = '';
                $color = 'green';
                if (in_array($childHandle, $data['unloaded_js_handles'])) {
                    $color = '#cc0000';
                    $title = esc_html__('This JS handle is already unloaded.', 'wp-asset-clean-up');
                }
                $childHandlesOutput .= '<a title="'.$title.'" style="color:'.$color.';font-weight:300;" href="#wpacu_script_row_'.$childHandle.'">'.$childHandle.'</a>, ';
            }
            echo trim($childHandlesOutput, ', ');
            ?>
            </span>
        </em>
        <div class="wpacu_hide_if_handle_row_contracted">
            <label for="script_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>_ignore_children">
                &#10230; <input id="script_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>_ignore_children"
                                type="checkbox"
                                <?php if ($ignoreChild) { ?>checked="checked"<?php } ?>
                                name="<?php echo WPACU_FORM_ASSETS_POST_KEY; ?>[scripts][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>][ignore_child]"
                                value="1" /> <small><?php _e('Ignore dependency rule and keep the "children" loaded', 'wp-asset-clean-up'); ?>
                <?php if (in_array($data['row']['obj']->handle, Main::instance()->keepChildrenLoadedForHandles['js'])) { echo '(recommended)'; } ?>
                </small>
            </label>
        </div>
	</div>
<?php
} elseif ($ignoreChild) {
	// Keep the option enabled in case ignoring other dependencies was already chosen in a different page (e.g. in some pages a handle can have a dependency, in others it might not have any)
	?>
    <input type="hidden" name="<?php echo WPACU_FORM_ASSETS_POST_KEY; ?>[scripts][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>][ignore_child]" value="1" />
	<?php
}
