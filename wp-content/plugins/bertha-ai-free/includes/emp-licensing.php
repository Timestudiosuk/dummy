<?php

if (!class_exists('EDD_SL_Plugin_Updater')) {
    include(dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php');
}

function WEB_ACE_DASHBOARD_plugin_updater()
{

    // retrieve our license key from the DB
    $license_key = trim(get_option('WEB_ACE_DASHBOARD_license_key'));

    // setup the updater
    $edd_updater = new EDD_SL_Plugin_Updater(BTHAI_STORE_URL, BTHAI_FILE, array(
            'version' => BTHAI_VERSION,                // current version number
            'license' => $license_key,        // license key (used get_option above to retrieve from DB)
            'item_name' => BTHAI_ITEM_NAME,    // name of this plugin
            'item_id' => BTHAI_ITEM_ID,    // name of this plugin
            'author' => BTHAI_AUTHOR_NAME,  // author of this plugin
            'beta' => false
        )
    );

}

add_action('admin_init', 'WEB_ACE_DASHBOARD_plugin_updater', 0);



function WEB_ACE_DASHBOARD_has_license()
{
    $status = get_option('WEB_ACE_DASHBOARD_license_status', false);

    return ($status && $status == 'valid');
}
