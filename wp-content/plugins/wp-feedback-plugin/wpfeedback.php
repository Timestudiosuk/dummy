<?php
/**
 * Plugin Name: Atarim - Client Interface Plugin
 * Plugin URI: https://atarim.io/
 * Description: Atarim is a client-focused agency management platform to help simplify collaboration and get things done fast.
 *
 * Version: 2.8
 * Requires at least: 5.0
 *
 * Author: Atarim
 * Author URI: https://atarim.io/
 *
 * Text Domain: atarim
 * Domain Path: /languages/
 *
 * License: GPL-3.0-or-later
 *
 *
 * @author    Atarim <support@atarim.io>
 * @copyright 2021 Atarim
 * @license   GPL-3.0-or-later
 * @package   Atarim
 */
/**
 * If this file is called directly, abort.
 * */
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

if (!defined('WPINC')) {
    die;
}

if (!defined('WPF_PLUGIN_NAME'))
    define('WPF_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('WPF_PLUGIN_DIR'))
    define('WPF_PLUGIN_DIR', plugin_dir_path(__FILE__));

if (!defined('WPF_PLUGIN_URL'))
    define('WPF_PLUGIN_URL', plugin_dir_url(__FILE__));

if (!defined('WPF_VERSION'))
    define('WPF_VERSION', '2.8');

//if (is_multisite()) {
//    $site_url = network_site_url();
//} else {
    $site_url = site_url();
// }

define('SCOPER_ALL_UPLOADS_EDITABLE ', true);


if (!defined('WPF_SITE_URL'))
    define('WPF_SITE_URL', $site_url);

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define('WPF_EDD_SL_STORE_URL', 'https://atarim.io/'); // IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system
// the download ID. This is the ID of your product in EDD and should match the download ID visible in your Downloads list (see example below)
define('WPF_EDD_SL_ITEM_ID', get_option('wpf_prod_id')); // IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system
define('WPF_EDD_FALLBACK_URL', 'https://verify.wpfeedback.co/');

// site urls
define('WPF_MAIN_SITE_URL', 'https://atarim.io');
define('WPF_APP_SITE_URL', 'https://app.atarim.io');
define('WPF_LEARN_SITE_URL', 'https://academy.atarim.io');
// define('WPF_LEARN_SITE_URL', 'https://learn.wpfeedback.co');

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook(__FILE__, array('WP_Feedback', 'activate'));
register_deactivation_hook(__FILE__, array('WP_Feedback', 'deactivate'));

// add_action('init', 'get_site_data'); // init call of site settings data
// //add_action('init', 'get_user_data'); // init call of site user data
// add_action('init', 'get_notify_users'); // init call of site notify users
// add_action('init', 'get_site_filter_data'); // init call of site filter data

/* Turned Off on v2.1.0 */
//add_action('init', 'get_notif_sitedata_filterdata'); // init call of site data

/**
 * Create the admin menu.
 */
/*
 * This function is used to register the admin menu for the Atarim.
 *
 * @input NULL
 * @return NULL
 */
add_action('admin_menu', 'wp_feedback_admin_menu');

function wp_feedback_admin_menu() {
    global $current_user;
    $wpf_powered_by = get_site_data_by_key('wpfeedback_powered_by');

    $selected_roles = get_site_data_by_key('wpf_selcted_role');
    $selected_roles = explode(',', $selected_roles);

    /* if ( is_multisite() ) {
      $main_menu_id =  'wpfeedback_page_task';
      }
      else{
      $main_menu_id =  'wp_feedback';
      } */
    $main_menu_id = 'wpfeedback_page_tasks';

    if (array_intersect($current_user->roles, $selected_roles) || current_user_can('administrator')) {
	$wpf_user_type = wpf_user_type();

	$badge = '';
	if ($wpf_powered_by == 'yes') {
		$wpf_main_menu_label = __('Collaborate', 'wpfeedback');
		$wpf_main_menu_icon = WPF_PLUGIN_URL . 'images/atarim-whitelabel.svg';
		// echo "<script>jQuery_WPF(document).ready(function () {jQuery_WPF('.toplevel_page_wpfeedback_page_tasks .wp-menu-image img').addClass('whitelabel_icon');});</script>";
		// echo "<script>document.getElementsByClassName('toplevel_page_wpfeedback_page_tasks')[0].getElementsByClassName('wp-menu-image')[0].getElementsByTagName('img')[0].classList.add('whitelabel_icon');</script>";
		// echo "<script>document.getElementsByClassName('toplevel_page_wpfeedback_page_tasks')[0].getElementsByClassName('wp-menu-image')[0].classList.add('whitelabel_icon');</script>";
		// echo "<script>document.body.onload=function(){document.getElementsByClassName('toplevel_page_wpfeedback_page_tasks')[0].getElementsByClassName('wp-menu-image')[0].style.paddingTop = '20px !important'; console.log('MESSAGE: loaded')}</script>";
		// echo "<script>jQuery_WPF('.toplevel_page_wpfeedback_page_tasks .wp-menu-image img').css('margin-top','2px !important');</script>";//<style>.toplevel_page_wpfeedback_page_tasks .wp-menu-image img{padding:0px !important;}</style>";
	} else {
		$wpf_main_menu_label = __('Collaborate', 'wpfeedback');
		$wpf_main_menu_icon = WPF_PLUGIN_URL . 'images/atarim_favicon_white.svg';
	}
	add_menu_page(
		__($wpf_main_menu_label, 'wpfeedback'), __($wpf_main_menu_label, 'wpfeedback') . $badge, 'read', $main_menu_id, $main_menu_id, $wpf_main_menu_icon, 80
	);
	add_submenu_page(
		$main_menu_id, __('Tasks Center', 'wpfeedback'), __('Tasks Center', 'wpfeedback'), 'read', 'wpfeedback_page_tasks', 'wpfeedback_page_tasks'
	);
	add_submenu_page(
		$main_menu_id, __('Graphics', 'wpfeedback'), __('Graphics', 'wpfeedback'), 'read', 'wpfeedback_page_graphics', 'wpfeedback_page_graphics'
	);
	if ($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') )) {
	    add_submenu_page(
		    $main_menu_id, __('Settings', 'wpfeedback'), __('Settings', 'wpfeedback'), 'read', 'wpfeedback_page_settings', 'wpfeedback_page_settings'
	    );
	}
	if ($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') )) {
	    add_submenu_page(
		    $main_menu_id, __('Permissions', 'wpfeedback'), __('Permissions', 'wpfeedback'), 'read', 'wpfeedback_page_permissions', 'wpfeedback_page_permissions'
	    );
	}
	if ($wpf_user_type == 'advisor') {
	    add_submenu_page(
		    $main_menu_id, __('Integrations', 'wpfeedback'), __('Integrations', 'wpfeedback'), 'read', 'wpfeedback_page_integrate', 'wpfeedback_page_integrate'
	    );
	}

	if ($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') )) {
	    /*add_submenu_page(
		    $main_menu_id, __('Support', 'wpfeedback'), __('Support', 'wpfeedback'), 'read', WPF_LEARN_SITE_URL.'/support-reachout/?siteurl='. urlencode(site_url()).'&fullname='.$current_user->user_nicename.'&eaddress='.$current_user->user_email
	    );*/
        add_submenu_page(
		    $main_menu_id, __('Support', 'wpfeedback'), __('Support', 'wpfeedback'), 'read', 'https://atarim.io/support-reachout'
	    );
	    add_submenu_page(
		    $main_menu_id, __('Upgrade', 'wpfeedback'), __('Upgrade', 'wpfeedback'), 'read', WPF_MAIN_SITE_URL.'/upgrade'
	    );
	}
    }
}

/*
 * This function is used to set the link for the "Settings" menu item.
 *
 * @input Array
 * @return Array
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpf_setting_action_links');

function wpf_setting_action_links($links) {
    $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=wpfeedback_page_settings&wpf_setting=1')) . '">' . __('Settings', 'wpfeedback') . '</a>';
    return $links;
}

/*
 * This function is used used to include the page-settings template for the settings menu if the initial onboarding is already or include wpf_backend_initial_setup if not.
 *
 * @input NULL
 * @return NULL
 */

function wpfeedback_page_settings() {
    global $current_user;
    $initial_setup = get_site_data_by_key("wpf_initial_setup_complete");
    if ($initial_setup != 'yes') {
	require_once(WPF_PLUGIN_DIR . 'inc/admin/wpf_backend_initial_setup.php');
    } else {
	require_once(WPF_PLUGIN_DIR . 'inc/admin/page-settings.php');
    }
}

/*
 * This function is used used to include the page-settings template for the tasks menu.
 *
 * @input NULL
 * @return NULL
 */

function wpfeedback_page_tasks() {
    global $current_user;
    require_once(WPF_PLUGIN_DIR . 'inc/admin/page-settings.php');
}

/*
 * This function is used used to include the page-settings template for the integration menu.
 *
 * @input NULL
 * @return NULL
 */

function wpfeedback_page_integrate() {
    global $current_user;
    require_once(WPF_PLUGIN_DIR . 'inc/admin/page-settings.php');
}

/*
 * This function is used used to include the page-settings template for the support menu.
 *
 * @input NULL
 * @return NULL
 */

function wpfeedback_page_support() {
    global $current_user;
    require_once(WPF_PLUGIN_DIR . 'inc/admin/page-settings.php');
}

/*
 * This function is used used to include the page-settings-permissions template for the Permissions menu.
 *
 * @input NULL
 * @return NULL
 */

function wpfeedback_page_permissions() {
    global $current_user;
    require_once(WPF_PLUGIN_DIR . 'inc/admin/page-settings-permissions.php');
}

/*
 * This function is used used to include the page-settings-graphics template for the graphics menu.
 *
 * @input NULL
 * @return NULL
 */

function wpfeedback_page_graphics() {
    global $current_user;
    require_once(WPF_PLUGIN_DIR . 'inc/admin/page-settings-graphics.php');
}

/*
 * Require admin functionality
 */
require_once(WPF_PLUGIN_DIR . 'inc/wpf_ajax_functions.php');
require_once(WPF_PLUGIN_DIR . 'inc/wpf_function.php');
require_once(WPF_PLUGIN_DIR . 'inc/wpf_email_notifications.php');
require_once(WPF_PLUGIN_DIR . 'inc/wpf_admin_functions.php');
require_once(WPF_PLUGIN_DIR . 'inc/admin/wpf_admin_function.php');
require_once(WPF_PLUGIN_DIR . 'inc/wpf_api.php');

if (!class_exists('EDD_SL_Plugin_Updater')) {
    // load our custom updater if it doesn't already exist
    include(dirname(__FILE__) . '/inc/EDD_SL_Plugin_Updater.php');
}

$wpf_image_cache = WPF_PLUGIN_DIR . "cache/";
if (!file_exists($wpf_image_cache)) {
    mkdir($wpf_image_cache, 0777, true);
}

// retrieve our license key from the DB
$wpf_license_key = trim(get_option('wpf_license_key'));
$wpf_decry_key = wpf_crypt_key($wpf_license_key, 'd');
// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater(WPF_EDD_SL_STORE_URL, __FILE__, array(
    'version' => WPF_VERSION, // current version number
    'license' => $wpf_decry_key, // license key (used get_option above to retrieve from DB)
    'item_id' => WPF_EDD_SL_ITEM_ID, // id of this plugin
    'author' => 'Ace Digital London', // author of this plugin
    'url' => WPF_SITE_URL,
    'beta' => false // set to true if you wish customers to receive update notifications of beta releases
	));

add_action('init','new_license_activation');
function new_license_activation(){

    if(get_option('wpf_prod_id')==''){
        update_option('wpf_license','invalid','no');
    }
//if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
    /*
     * Verify license
     */
    /*$wpf_license_key = get_option('wpf_license_key');
    $wpf_license = get_option('wpf_license');
    $date_now = date("Y-m-d", strtotime("+3 day"));
    $wpf_check_license_date = get_option('wpf_check_license_date');

    if ($date_now > $wpf_check_license_date || $wpf_check_license_date == '') {
	update_option('wpf_check_license_date', $date_now, 'no');
	if ($wpf_license == 'valid') {
	    $outputObject = wpf_license_key_check_item($wpf_license_key);
	    if ($outputObject['executed'] == 1) {
		if ($outputObject['license'] == 'valid') {
		    update_option('wpf_license', $outputObject['license'], 'no');
		    update_option('wpf_license_expires', $outputObject['expires'], 'no');
		    if (!get_option('wpf_decr_key')) {
			update_option('wpf_decr_key', $outputObject['payment_id'], 'no');
			update_option('wpf_decr_checksum', $outputObject['checksum'], 'no');
			$wpf_crypt_key = wpf_crypt_key($wpf_license_key, 'e');
			update_option('wpf_license_key', $wpf_crypt_key, 'no');
		    }
		} else {
		    update_option('wpf_license', $outputObject['license'], 'no');
		}
	    }
	}
    }

    if ($wpf_license == 'site_inactive' && $wpf_license_key != '') {
	$outputObject = wpf_license_key_license_item($wpf_license_key);
	if ($outputObject['executed'] == 1) {
	    if ($outputObject['license'] == 'valid') {
		update_option('wpf_license', $outputObject['license'], 'no');
		update_option('wpf_license_expires', $outputObject['expires'], 'no');
		if (!get_option('wpf_decr_key')) {
		    update_option('wpf_decr_key', $outputObject['payment_id'], 'no');
		    update_option('wpf_decr_checksum', $outputObject['checksum'], 'no');
		    $wpf_crypt_key = wpf_crypt_key($wpf_license_key, 'e');
		    update_option('wpf_license_key', $wpf_crypt_key, 'no');
		}
	    }
	}
    }*/

//    update_default_site_data();

/*New license activation*/
    if(isset($_GET['atarim_response']) && isset($_GET['expires'])) {

        delete_option('wpf_decr_checksum');
        delete_option('wpf_decr_key');
        delete_option('wpf_license_expires');
        delete_option('wpf_license');
        delete_option('wpf_prod_id');
        delete_option('wpf_license_key');

        $wpf_license_key=base64_decode($_GET['license_key']);
        
        update_option('wpf_license', base64_decode($_GET['atarim_response']));
        update_option('wpf_license_expires', base64_decode($_GET['expires']), 'no');
        
        update_option('wpf_prod_id', base64_decode($_GET['prod_id']), 'no');

        $decr=update_option('wpf_decr_key', base64_decode($_GET['payment_id']));
        //if($decr){
        $checksu=update_option('wpf_decr_checksum', base64_decode($_GET['checksum']), 'no');
        //}
        //if($checksu){
        $wpf_crypt_key = wpf_crypt_key($wpf_license_key, 'e');
        update_option('wpf_license_key', $wpf_crypt_key, 'no');
        //}
        update_option('wpf_site_id', base64_decode($_GET['wpf_site_id']), 'no');
        do_action('wpf_initial_sync',$wpf_license_key);
        syncUsers();
        get_notif_sitedata_filterdata();
        syncPages();
    }

    // When Enable Global Settings is on, the site data will be fetched by the API
    $wpf_global_settings = get_option('wpf_global_settings');
    if ( $wpf_global_settings === "yes" ) {
        get_site_data();
    }
}

/*
 * This function is used for add/update
 * user default site data
 */
function update_default_site_data() {
    $options = [];
    if (get_site_data_by_key('wpf_tab_permission_user_client') == '') {
	//global $current_user,$wpdb;

	array_push($options, ['name' => 'enabled_wpfeedback', 'value' => 'yes']);
	array_push($options, ['name' => 'wpfeedback_color', 'value' => '002157']);
	array_push($options, ['name' => 'wpf_selcted_role', 'value' => 'administrator']);
	array_push($options, ['name' => 'wpf_website_developer', 'value' => get_current_user_id()]);
	array_push($options, ['name' => 'wpf_show_front_stikers', 'value' => 'yes']);
	array_push($options, ['name' => 'wpf_customisations_client', 'value' => 'Client (Website Owner)']);
	array_push($options, ['name' => 'wpf_customisations_webmaster', 'value' => 'Webmaster']);
	array_push($options, ['name' => 'wpf_customisations_others', 'value' => 'Others']);
	array_push($options, ['name' => 'wpf_from_email', 'value' => get_option('admin_email')]);
    // array_push($options, ['name' => 'wpf_from_email_mode', 'value' => 'yes']);

    	array_push($options, ['name' => 'wpf_tab_permission_user_client', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_user_webmaster', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_user_others', 'value' => 'yes']);

    	array_push($options, ['name' => 'wpf_tab_permission_priority_client', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_priority_webmaster', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_priority_others', 'value' => 'yes']);

    	array_push($options, ['name' => 'wpf_tab_permission_status_client', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_status_webmaster', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_status_others', 'value' => 'yes']);

    	array_push($options, ['name' => 'wpf_tab_permission_screenshot_client', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_screenshot_webmaster', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_screenshot_others', 'value' => 'yes']);

    	array_push($options, ['name' => 'wpf_tab_permission_information_client', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_information_webmaster', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_information_others', 'value' => 'yes']);

    	array_push($options, ['name' => 'wpf_tab_permission_delete_task_client', 'value' => 'no']);
    	array_push($options, ['name' => 'wpf_tab_permission_delete_task_webmaster', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_delete_task_others', 'value' => 'no']);
    }

    if (get_site_data_by_key('wpf_tab_permission_user_guest') == '') {

    	array_push($options, ['name' => 'wpf_tab_permission_user_guest', 'value' => 'no']);
    	array_push($options, ['name' => 'wpf_tab_permission_priority_guest', 'value' => 'no']);
    	array_push($options, ['name' => 'wpf_tab_permission_status_guest', 'value' => 'no']);
    	array_push($options, ['name' => 'wpf_tab_permission_screenshot_guest', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_information_guest', 'value' => 'yes']);
    	array_push($options, ['name' => 'wpf_tab_permission_delete_task_guest', 'value' => 'no']);
    }

    if (get_site_data_by_key('wpf_tab_auto_screenshot_task_client') == '') {
    	array_push($options, ['name' => 'wpf_tab_auto_screenshot_task_client', 'value' => 'yes']);
    }

    if (get_site_data_by_key('wpf_tab_auto_screenshot_task_webmaster') == '') {
    	array_push($options, ['name' => 'wpf_tab_auto_screenshot_task_webmaster', 'value' => 'yes']);
    }

    if (get_site_data_by_key('wpf_tab_auto_screenshot_task_others') == '') {
    	array_push($options, ['name' => 'wpf_tab_auto_screenshot_task_others', 'value' => 'yes']);
    }

    if (get_site_data_by_key('wpf_tab_auto_screenshot_task_guest') == '') {
    	array_push($options, ['name' => 'wpf_tab_auto_screenshot_task_guest', 'value' => 'yes']);
    }

    if(!empty($options)) {
	update_site_data($options);
    }
}

/*
 * This function is used to detect if the page builder is initialized on the current running page and deregister the Atarim of found running.
 *
 * @input NULL
 * @return NULL
 */
add_action('wp_enqueue_scripts', 'wpfeedback_add_stylesheet_frontend');

function wpfeedback_add_stylesheet_frontend() {
    $wpf_check_page_builder_active = wpf_check_page_builder_active();
    /* =====Start Check customize.php==== */
    if ($wpf_check_page_builder_active == 0) {
	if (is_customize_preview()) {
	    $wpf_check_page_builder_active = 1;
	} else {
	    $wpf_check_page_builder_active = 0;
	}
    }
    /* =====END check customize.php==== */
    $enabled_wpfeedback = wpf_check_if_enable();
    $wpf_enabled = get_site_data_by_key('enabled_wpfeedback');
    $is_site_archived = get_site_data_by_key('wpf_site_archived');
    if ( $wpf_enabled == 'yes' && (!$is_site_archived) ) {


	if (!is_user_logged_in()) {
            /* Show the login modal only when 'wpf_login' is present => v2.0.9, v2.1.0 */
            if ( (get_query_var('is_graphic_page')) || (!empty($_GET['wpf_login'])) ):
	            wp_register_style('wpf_login_style', WPF_PLUGIN_URL . 'css/wpf-login.css', false, strtotime("now"));
                wp_enqueue_style('wpf_login_style');
            endif;
	}

	if (get_query_var('is_graphic_page')) {
	    wp_register_style('wpf-graphics-front-style', WPF_PLUGIN_URL . 'css/graphics-front.css', false, strtotime("now"));
	    wp_enqueue_style('wpf-graphics-front-style');
	}

            /* Show the login modal only when 'wpf_login' is present => v2.0.9, v2.1.0 */
        if ( (get_query_var('is_graphic_page')) || (!empty($_GET['wpf_login'])) ):
	            wp_register_script('wpf-ajax-login', WPF_PLUGIN_URL . 'js/wpf-ajax-login.js', array(), strtotime("now"), true);
	            wp_enqueue_script('wpf-ajax-login');
	        endif;

	wp_localize_script('wpf-ajax-login', 'wpf_ajax_login_object', array(
	    'ajaxurl' => admin_url('admin-ajax.php'),
	    'wpf_reconnect_icon' => WPF_PLUGIN_URL . 'images/wpf_reconnect.png',
	    'redirecturl' => home_url(),
	    'loadingmessage' => __('Sending user info, please wait...')
	));
    }
    if ( (get_query_var('is_graphic_page')) || ($enabled_wpfeedback == 1 && !$is_site_archived) ) {

	wp_register_style('wpf_wpf-icons', WPF_PLUGIN_URL . 'css/wpf-icons.css', false, strtotime("now"));
	wp_enqueue_style('wpf_wpf-icons');

/* 	wp_register_style('wpf_wpfb-front_script', WPF_PLUGIN_URL . 'css/wpfb-front.css', false, strtotime("now"));
	wp_enqueue_style('wpf_wpfb-front_script'); */

	wp_register_style('wpf_wpf-common', WPF_PLUGIN_URL . 'css/wpf-common.css', false, strtotime("now"));
	wp_enqueue_style('wpf_wpf-common');

	wp_register_script('wpf_jquery_script', WPF_PLUGIN_URL . 'js/jquery3.5.1.js', array(), WPF_VERSION, true);
	wp_enqueue_script('wpf_jquery_script');

	wp_register_style('wpf_bootstrap_script', WPF_PLUGIN_URL . 'css/bootstrap.min.css', false, "xxx");
	wp_enqueue_style('wpf_bootstrap_script');

	/* wp_register_script( 'pickr', 'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js', null, null, true );
	wp_enqueue_script('pickr');

	wp_register_style( 'pickr_monolith', 'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css' );
	wp_enqueue_style('pickr_monolith'); */
	if ($wpf_check_page_builder_active == 0) {

	    wp_register_script('wpf_jquery_ui_script', WPF_PLUGIN_URL . 'js/jquery-ui.js', array(), WPF_VERSION, true);
	    wp_enqueue_script('wpf_jquery_ui_script');

	    wp_register_script('wpf_touch_mouse_script', WPF_PLUGIN_URL . 'js/jquery.ui.mouse.min.js', array(), WPF_VERSION, true);
	    wp_enqueue_script('wpf_touch_mouse_script');

	    wp_register_script('wpf_touch_punch_script', WPF_PLUGIN_URL . 'js/jquery.ui.touch-punch.js', array(), WPF_VERSION, true);
	    wp_enqueue_script('wpf_touch_punch_script');


	    wp_register_script('wpf_browser_info_script', WPF_PLUGIN_URL . 'js/wpf_browser_info.js', array(), WPF_VERSION, true);
	    wp_enqueue_script('wpf_browser_info_script');

	    if (get_query_var('is_graphic_page')) {
		wp_register_script('wpf-graphics-front-script', WPF_PLUGIN_URL . 'js/app_graphics.js', array(), strtotime("now"), true);
		wp_enqueue_script('wpf-graphics-front-script');
		wp_enqueue_media();
	    } else {
            wp_register_script('wpf_app_script', WPF_PLUGIN_URL . 'js/app.js', array(), strtotime("now"), true);
            wp_enqueue_script('wpf_app_script');
            $wpf_user_type = wpf_user_type();
            wp_localize_script( 'wpf_app_script', 'logged_user', array( 'current_user' => $wpf_user_type ) );

            $wpf_get_user_type = esc_attr(wpf_user_type());
            $wpf_new_task = isset($_GET['wpf-task']) ? true : false;
            if($wpf_new_task && !get_option('wpf_app_auto_task')) {
                update_option('wpf_app_auto_task', true);
                $wpf_app_auto_task = true;
                $wpf_new_task = true;
            }else {
                $wpf_app_auto_task = false;
                $wpf_new_task = false;
            }
            $wpf_frontend_user = ( isset($_GET['wpf-user-flow']) || isset($_GET['wpf-existing-user-flow']) ) ? true : false;
            wp_localize_script( 'wpf_app_script', 'wpf_app_script_object', array( 'ajaxurl' => admin_url('admin-ajax.php'), 'wpf_app_auto_task' => $wpf_app_auto_task, 'wpf_new_task' => $wpf_new_task, 'wpf_frontend_user' => $wpf_frontend_user));
	    }

	    wp_register_script('wpf_html2canvas_script', WPF_PLUGIN_URL . 'js/html2canvas.js', array(), WPF_VERSION, true);
	    wp_enqueue_script('wpf_html2canvas_script');

	    wp_register_script('wpf_popper_script', WPF_PLUGIN_URL . 'js/popper.min.js', array(), WPF_VERSION, true);
	    wp_enqueue_script('wpf_popper_script');

	    wp_register_script('wpf_custompopover_script', WPF_PLUGIN_URL . 'js/custompopover.js', array(), WPF_VERSION, true);
	    wp_enqueue_script('wpf_custompopover_script');

	    wp_register_script('wpf_selectoroverlay_script', WPF_PLUGIN_URL . 'js/selectoroverlay.js', array(), WPF_VERSION, true);
	    wp_enqueue_script('wpf_selectoroverlay_script');

	    wp_register_script('wpf_common_functions', WPF_PLUGIN_URL . 'js/wpf_common_functions.js', array(), strtotime("now"), true);
	    wp_enqueue_script('wpf_common_functions');

	    wp_register_script('wpf_xyposition_script', WPF_PLUGIN_URL . 'js/xyposition.js', array(), WPF_VERSION, true);
	    wp_enqueue_script('wpf_xyposition_script');

	    wp_register_script('wpf_bootstrap_script', WPF_PLUGIN_URL . 'js/bootstrap.min.js', array(), WPF_VERSION, true);
	    //if(! defined( 'AVADA_VERSION' )){
	    wp_enqueue_script('wpf_bootstrap_script');
	    //}
	}
    }
}

// Prevent collision with the WordPress jQuery
add_filter('script_loader_tag', 'add_attributes_to_script', 10, 3); 
function add_attributes_to_script( $tag, $handle, $src ) {
	if ( 'wpf_jquery_script' === $handle ) {
		if ( wp_script_is( 'jquery', 'enqueued' ))
			$tag = '<script>var jQuery_WPF = jQuery;</script>';
		else {
			return;
		}
	} 
	return $tag;
}



/*
 * This function is used to create the security nonce every time a user requests the Atarim.
 *
 * @input NULL
 * @return String
 */

function wpf_wp_create_nonce() {
    global $post;
    $wpf_allow_guest = get_site_data_by_key('wpf_allow_guest');
    if (is_user_logged_in() || $wpf_allow_guest == 'yes') {
	$wpf_nonce = wp_create_nonce('wpfeedback-script-nonce');
	return $wpf_nonce;
    }
    if (get_query_var('is_graphic_page') && !is_user_logged_in() && $wpf_allow_guest != 'yes') {
	$wpf_nonce = wp_create_nonce('wpfeedback-script-nonce');
	return $wpf_nonce;
    }
}

/* ==========All Java script for Admin footer========= */
/*
 * This function is used to initial the Atarim and all related variables on the backend.
 *
 * @input NULL
 * @return NULL
 */
if(isset($_GET['page'])){
	add_action('admin_footer', 'wpf_backed_scripts');
}
function wpf_backed_scripts() {
    global $wpdb, $post, $current_user; //for this example only :)
    $author_id = $current_user->ID;
    $wpf_user_type = wpf_user_type();

    $currnet_user_information = wpf_get_current_user_information();
    $current_role = $currnet_user_information['role'];
    // $current_user_name = $currnet_user_information['display_name'];
    // $current_user_name = (!empty($currnet_user_information['first_name'])) ? $currnet_user_information['first_name'] . ' ' . $currnet_user_information['last_name'] : $currnet_user_information['display_name'];
    $current_user_name = $currnet_user_information['display_name'];
    $current_user_id = $currnet_user_information['user_id'];
    $wpf_website_builder = get_site_data_by_key('wpf_website_developer');
    if ($current_user_name == 'Guest') {
	$wpf_website_client = get_site_data_by_key('wpf_website_client');
	$wpf_current_role = 'guest';
	if ($wpf_website_client) {
	    $wpf_website_client_info = get_userdata($wpf_website_client);
	    if ($wpf_website_client_info) {
		if ($wpf_website_client_info->display_name == '') {
		    $current_user_name = $wpf_website_client_info->user_nicename;
		} else {
		    $current_user_name = $wpf_website_client_info->display_name;
		}
	    }
	}
    }
    $current_user_name = addslashes($current_user_name);
    $wpf_show_front_stikers = get_site_data_by_key('wpf_show_front_stikers');

    $unix_time_now= time();

    $wpf_check_atarim_server=get_option('atarim_server_down_check');

    if ($unix_time_now > $wpf_check_atarim_server) {
        update_option('atarim_server_down','false','no');
    }

    $atarim_server_down=get_option('atarim_server_down');

    $wpfb_users = do_shortcode('[wpf_user_list_front]');
    $wpf_all_pages = wpf_get_page_list();
    $ajax_url = admin_url('admin-ajax.php');
    $plugin_url = WPF_PLUGIN_URL;
    $wpf_comment_time = date('d-m-Y H:i', current_time('timestamp', 0));
    $wpf_nonce = wpf_wp_create_nonce();
    $sound_file = esc_url(plugins_url('images/wpf-screenshot-sound.mp3', __FILE__));

    $comment_count = get_last_task_id();

    echo "<script>var wpf_nonce='$wpf_nonce',wpf_comment_time='$wpf_comment_time',wpf_all_pages ='$wpf_all_pages', current_role='$current_role', wpf_current_role='$wpf_user_type', current_user_name='$current_user_name', current_user_id='$current_user_id', wpf_website_builder='$wpf_website_builder', wpfb_users = '$wpfb_users',  ajaxurl = '$ajax_url', wpf_screenshot_sound = '$sound_file', plugin_url = '$plugin_url', comment_count='$comment_count', wpf_show_front_stikers='$wpf_show_front_stikers', atarim_server_down='$atarim_server_down';</script>";

    if (isset($_REQUEST['page'])) {
        if ($_REQUEST['page'] == 'wpfeedback_page_settings' || $_REQUEST['page'] == 'wpfeedback_page_tasks' || $_REQUEST['page'] == 'wpfeedback_page_integrate' || $_REQUEST['page'] == 'wpfeedback_page_upgrade' || $_REQUEST['page'] == 'wpfeedback_page_support' || $_REQUEST['page'] == 'wpfeedback_page_permissions' || $_REQUEST['page'] == 'wpfeedback_page_graphics') {
            ?>
            <script type='text/javascript'>
                var current_task = 0;
                var current_user_id = "<?php echo $author_id; ?>";
                var wpf_user_type = "<?php echo $wpf_user_type; ?>";

                function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
                var regexS = "[\\?&]" + name + "=([^&#]*)";
                var regex = new RegExp(regexS);
                var results = regex.exec(window.location.href);
                if (results == null)
                    return "";
                else
                    return decodeURIComponent(results[1].replace(/\+/g, " "));
                }

                /*
                 * wpf task filter code
                 */
                function wp_feedback_filter() {
                var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                var task_types = [];
                var task_title = jQuery('#wpf_tasks #wpf_search_title').val();
                var task_types_meta = [];
                jQuery.each(jQuery("#wpf_filter_form input[name='task_types']:checked"), function () {
                    task_types.push(jQuery(this).val());
                });
                var selected_task_types_values = task_types.join(",");

                var is_internal = 0;
                jQuery.each(jQuery("#wpf_filter_form input[name='task_types_meta']:checked"), function (index, element) {
                    if ( jQuery(element).attr('id') === 'wpf_task_type_internal' ) {
                        is_internal = 1;
                    } else {
                        task_types_meta.push(jQuery(this).val());
                    }
                });
                var selected_task_types_meta_values = task_types_meta.join(",");

                var task_status = [];
                jQuery.each(jQuery("#wpf_filter_form input[name='task_status']:checked"), function () {
                    task_status.push(jQuery(this).val());
                });
                //alert("My task status are: " + task_status.join(","));
                var selected_task_status_values = task_status.join(",");

                var task_priority = [];
                jQuery.each(jQuery("#wpf_filter_form input[name='task_priority']:checked"), function () {
                    task_priority.push(jQuery(this).val());
                });
                // alert("My task urgency are: " + task_priority.join(","));
                var selected_task_priority_values = task_priority.join(",");

                var author_list = [];
                jQuery.each(jQuery("#wpf_filter_form input[name='author_list']:checked"), function () {
                    author_list.push(jQuery(this).val());
                });
                // alert("My task urgency are: " + task_priority.join(","));
                var selected_author_list_values = author_list.join(",");
                //if(selected_task_status_values || selected_task_priority_values || selected_author_list_values){

                var wpf_display_all_taskmeta_tasktab = jQuery('#wpf_display_all_taskmeta_tasktab').prop("checked") ? 1 : 0;

                console.log(is_internal, task_types_meta);

                jQuery.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data: {
                    action: "wpfeedback_get_post_list_ajax",
                    wpf_nonce: wpf_nonce,
                    task_title: task_title,
                    task_types: selected_task_types_values, task_types_meta: selected_task_types_meta_values,
                    task_status: selected_task_status_values,
                    task_priority: selected_task_priority_values,
                    author_list: selected_author_list_values,
                    internal: is_internal
                    },
                    beforeSend: function () {
                    jQuery('.wpf_loader_admin').show();
                    },
                    success: function (data) {
                    //Comment
                    jQuery('#wpf_display_all_taskmeta_tasktab').prop('checked', false);
                    jQuery('.wpf_loader_admin').hide();
                    jQuery('.wpf_tasks_col .wpf_tasks-list').html(data);
                    if (document.getElementById('wpf_task_bulk_tab').checked) {
                        jQuery('.wpf_task_num_top').hide();
                        jQuery('#wpf_task_all_tab').removeClass('active');
                        jQuery('ul#all_wpf_list li .wpf_task_id').addClass('wpf_active');
                        jQuery('ul#all_wpf_list #wpf_bulk_select_task_checkbox').addClass('wpf_active');
                        jQuery('#wpf_bulk_select_task_checkbox').show();
                    }
                    /*console.log(data);*/

                    if (wpf_display_all_taskmeta_tasktab == 1) {
                        jQuery('ul#all_wpf_list li div.wpf_task_meta').addClass('wpf_active');
                        jQuery('#wpf_display_all_taskmeta_tasktab').prop("checked", true);
                    }
                    }
                });
                // }
                }
                var internal_icon_html='<span class="wpf_chevron_wrapper"><i class="gg-chevron-double-left"></i></span>';
                function get_wpf_message_form(comment_post_ID, curren_user_id,is_internal) {
                    let internal_button='';
                    if(wpf_current_role=='advisor'){
                        if(is_internal=='1'){
                            internal_class="wpf_is_internal";
                            internal_button='<button class="wpf_mark_internal wpf_mark_internal_task_center '+internal_class+'" data-id="'+comment_post_ID+'"><i class="gg-chevron-double-left"></i><span class="wpf_tooltiptext">wpf_tooltip text</span></button>';
                        }else{
                            internal_class="";
                            internal_button='<button class="wpf_mark_internal wpf_mark_internal_task_center '+internal_class+'" data-id="'+comment_post_ID+'"><i class="gg-chevron-double-left"></i><span class="wpf_tooltiptext">wpf_tooltip text</span></button>';
                        }
                    }
                    var html = '<div id="wpf_chat_box"><form action="" method="post" id="wpf_form" class="comment-form" enctype="multipart/form-data"><p class="comment-form-comment"><textarea placeholder="' + wpf_comment_box_placeholder + '" id="wpf_comment" name="comment" maxlength="65525" required="required"></textarea><input type="hidden" name="comment_post_ID" value="' + comment_post_ID + '" id="comment_post_ID">  <input type="hidden" name="curren_user_id" value="' + curren_user_id + '" id="curren_user_id"><p class="form-submit chat_button">'+internal_button+'<input name="submit" type="button" id="send_chat" onclick="send_chat_message()" class="submit wpf_button submit" value="' + wpf_send_message_text + '"><a href="javascript:void(0)" class="wpf_upload_button wpf_button" onchange="wpf_upload_file_admin(' + comment_post_ID + ');"><input type="file" name="wpf_uploadfile" id="wpf_uploadfile" data-elemid="' + comment_post_ID + '" class="wpf_uploadfile"><i class="gg-attachment"></i></a></p><p id="wpf_upload_error" class="wpf_hide">You are trying to upload an invalid filetype <br> Allowd File Types: JPG, PNG, GIF, PDF, DOC, DOCX and XLSX</p></form></div></div>';
                    return html;
                }
                function send_chat_message() {
                jQuery("#get_masg_loader").show();
                jQuery(".get_masg_loader").show();
                var wpf_comment = jQuery('#wpf_comment').val();
                var post_id = jQuery('#comment_post_ID').val();
                var author_id = "<?php echo $author_id; ?>";
                var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                var task_notify_users = [];
                jQuery.each(jQuery('#wpf_attributes_content input[name="author_list_task"]:checked'), function () {
                    task_notify_users.push(jQuery(this).val());
                });
                task_notify_users = task_notify_users.join(",");

                if (jQuery('#wpf_comment').val().trim().length > 0) {
                    jQuery.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data: {
                        action: "insert_wpf_comment_func",
                        wpf_nonce: wpf_nonce,
                        post_id: post_id,
                        author_id: author_id,
                        task_notify_users: task_notify_users,
                        wpf_comment: wpf_comment
                    },
                    beforeSend: function () {
                        jQuery('.wpf_loader_admin').show();
                    },
                    success: function (data) {
                            console.log(data);
                        try {
                            const responseData = JSON.parse(data);
                            console.log(responseData);
                            if ( responseData['limit'] === true ) {
                                jQuery(".wpf_locked_modal_container").show();
                                return;
                            }
                        } catch(ex){}
                        
                        jQuery('.wpf_loader_admin').hide();
                        jQuery("#wpf_not_found").remove();
                        //jQuery("#tag_post").remove();
                        jQuery("#tag_post").html('');
                        data = URLify(data);
                        if (jQuery('#wpf_message_list li').length == 0) {
                        jQuery('ul#wpf_message_list').html(data);
                        } else {
                        jQuery('ul#wpf_message_list li:last').after(data);
                        }
                        jQuery("#wpf_comment").val("");
                        jQuery("#addcart_loader").fadeOut();
                        jQuery("#get_masg_loader").hide();
                        jQuery(".get_masg_loader").hide();
                        jQuery('#wpf_message_content').animate({scrollTop: jQuery('#wpf_message_content').prop("scrollHeight")}, 2000);
                        if (jQuery("#task_task_status_attr").val() == 'complete') {
                        jQuery("#task_task_status_attr").val("open");
                        var obj = document.getElementById("task_task_status_attr");
                        task_status_changed(obj);
                        }
                    }
                    })
                } else {
                    jQuery("#get_masg_loader").hide();
                    jQuery('ul#wpf_message_list').animate({scrollTop: jQuery("ul#wpf_message_list li").last().offset().top}, 1000);
                    jQuery("#wpf_comment").focus();
                    jQuery("#get_masg_loader").hide();
                }
                }
jQuery(document).on('click','.wpf_mark_internal_task_center',function(e){
    e.preventDefault();
    let id=jQuery(this).data('id');
    if(jQuery(this).hasClass('wpf_is_internal')){
        mark_internal_task_center(id,'0');    
    }else{
        mark_internal_task_center(id,'1');
    }
})
        function mark_internal_task_center(id,internal) {
        var task_info = [];
    
        var task_notify_users = [];
        var task_comment = jQuery_WPF('#comment-'+id).val();
        jQuery_WPF.each(jQuery_WPF('input[name=author_list_'+id+']:checked'), function(){
            task_notify_users.push(jQuery_WPF(this).val());
        });
        //task_notify_users =task_notify_users.join(",");
    
        task_info['task_id'] = id;
        task_info['internal']=internal;
    
        var task_info_obj = jQuery_WPF.extend({}, task_info);
    
        var task_info_obj = jQuery_WPF.extend({}, task_info);
        jQuery_WPF.ajax({
            method : "POST",
            url : ajaxurl,
            data : {action: "wpfb_mark_as_internal",wpf_nonce:wpf_nonce,task_info:task_info_obj},
            beforeSend: function(){
                // console.log('.wpf_loader_'+id);
                jQuery_WPF('.wpf_loader_admin').show();
            },
            success : function(data){
                if(internal=='1'){
                    jQuery_WPF('.wpf_mark_internal_task_center').addClass('wpf_is_internal');
                    jQuery_WPF('#wpf-task-'+id).addClass('wpfb-internal');
                    jQuery_WPF('#wpf-task-'+id).find('.wpf_task_num_top').append(internal_icon_html);
                }else{
                    jQuery_WPF('.wpf_mark_internal_task_center').removeClass('wpf_is_internal');
                    jQuery_WPF('#wpf-task-'+id).removeClass('wpfb-internal');
                    jQuery_WPF('#wpf-task-'+id).find('.wpf_task_num_top').find('.wpf_chevron_wrapper').remove();
                }
                jQuery_WPF('.wpf_loader_admin').hide();
            }
        });
        }

                function task_status_changed(sel) {
                var task_info = [];
                var task_notify_users = [];
                    // console.log(tss+"-"+prr);
                jQuery.each(jQuery('#wpf_attributes_content input[name="author_list_task"]:checked'), function () {
                    task_notify_users.push(jQuery(this).val());
                });

                let selected_priority = jQuery('#task_task_priority_attr').val();
                task_notify_users = task_notify_users.join(",");

                task_info['task_id'] = current_task;
                task_info['task_status'] = sel.value;
                task_info['task_notify_users'] = task_notify_users;
                var wpf_task_id = jQuery('#wpf_task_details .wpf_task_num_top').text()

                var task_info_obj = jQuery.extend({}, task_info);
                let sticker_permission = wpf_tab_permission_display_stickers;
                let task_id_permission = wpf_tab_permission_display_task_id;
                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {action: "wpfb_set_task_status", wpf_nonce: wpf_nonce, task_info: task_info_obj},
                    beforeSend: function () {
                    jQuery('.wpf_loader_admin').show();
                    },
                    success: function (data) {
                    // alert(data);wpf_get_page_list
                    let display_span = '';
                    let custom_class = '';
                    if (sticker_permission == 'yes') {
                        display_span = '<span class="' + selected_priority + '_custom"></span>';
                        custom_class = task_info['task_status'] + '_custom';
                    }
                    if(task_info['task_status']=="open"){
                        var news="Open";
                    }
                    if(task_info['task_status']=="in-progress"){
                        var news="In Progress";
                    }
                    if(task_info['task_status']=="pending-review"){
                        var news="Pending Review";
                    }
                    if(task_info['task_status']=="complete"){
                        var news="Complete";
                    }

                    if(tss=="open"){
                        var olss="Open";
                    }
                    if(tss=="in-progress"){
                        var olss="In Progress";
                    }
                    if(tss=="pending-review"){
                        var olss="Pending Review";
                    }
                    if(tss=="complete"){
                        var olss="Complete";
                    }
                    jQuery("#wpf_message_list").append('<li class="  not_chat_author is_info  " title="1 second ago"><level class="wpf-author"> <span>1 second ago</span></level><p class="task_text">'+current_user_name+' marked as <span class="taskStatusMsg">'+news+'</span> from '+olss+'</p></li>');
                    jQuery("#wpf-task-" + current_task + " .wpf_task_label .task_status").removeClass().addClass("task_status wpf_" + sel.value);
                    tss=task_info['task_status'];
                    jQuery('.wpf_loader_admin').hide();
                    jQuery('#wpf-task-' + current_task).data('task_status', sel.value);

                    var view_id=jQuery(document).find("#wpf_"+current_task).attr("data-disp-id");

                    if (sel.value == 'complete') {
                        jQuery('#all_wpf_list .post_' + current_task).addClass('complete');

                        let display_check_mark = '';
                        if (task_id_permission == false) {
                        display_check_mark = '<i class="gg-check"></i>';
                        } else {
                        display_check_mark = view_id//wpf_task_id;
                        }

                        jQuery('#all_wpf_list .post_' + current_task + ' .wpf_task_num_top').html(display_check_mark);
                        jQuery('#wpf_task_details .wpf_task_num_top').html(display_span + view_id);
                        jQuery('#wpf_task_details .wpf_task_num_top').removeAttr('class').addClass('wpf_task_num_top ' + custom_class);

                        jQuery('#all_wpf_list .post_' + current_task + ' .wpf_chat_top .wpf_task_num_top').html(display_span + view_id);
                        jQuery('#all_wpf_list .post_' + current_task + ' .wpf_chat_top .wpf_task_num_top').removeAttr('class').addClass('wpf_task_num_top ' + custom_class);

                        jQuery('#all_wpf_list li.post_' + current_task).removeClass('open').removeClass('complete').removeClass('pending-review').removeClass('in-progress').addClass(task_info['task_status']).addClass('active').addClass('wpf_list').addClass(selected_priority);

                    } else {
                        jQuery('#all_wpf_list .post_' + current_task).removeClass('complete');
                        jQuery('#all_wpf_list .post_' + current_task + ' .wpf_task_num_top').html(view_id);
                        jQuery('#wpf_task_details .wpf_task_num_top').html(display_span + view_id);
                        jQuery('#wpf_task_details .wpf_task_num_top').removeAttr('class').addClass('wpf_task_num_top ' + custom_class);

                        jQuery('#all_wpf_list .post_' + current_task + ' .wpf_chat_top .wpf_task_num_top').html(display_span + view_id);
                        jQuery('#all_wpf_list .post_' + current_task + ' .wpf_chat_top .wpf_task_num_top').removeAttr('class').addClass('wpf_task_num_top ' + custom_class);
                        jQuery('#all_wpf_list li.post_' + current_task).removeClass('open').removeClass('complete').removeClass('pending-review').removeClass('in-progress').addClass(task_info['task_status']).addClass('active').addClass('wpf_list').addClass(selected_priority);
                    }
                    }
                });
                }


                function task_priority_changed(sel) {
                // alert(sel.value);
                var task_info = [];
                var task_priority = sel.value;


                task_info['task_id'] = current_task;
                task_info['task_priority'] = task_priority;

                var task_info_obj = jQuery.extend({}, task_info);
                let sticker_permission = wpf_tab_permission_display_stickers;
                jQuery.ajax({
                    method: "POST",
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    data: {action: "wpfb_set_task_priority", wpf_nonce: wpf_nonce, task_info: task_info_obj},
                    beforeSend: function () {
                    jQuery('.wpf_loader_admin').show();
                    },
                    success: function (data) {

                    let custom_class = '';
                    if (sticker_permission == 'yes') {
                        custom_class = sel.value + '_custom';
                    }

                    if(task_priority=="low"){
                        var news="Low";
                    }
                    if(task_priority=="medium"){
                        var news="Medium";
                    }
                    if(task_priority=="high"){
                        var news="High";
                    }
                    if(task_priority=="critical"){
                        var news="Critical";
                    }

                    if(prr=="low"){
                        var olss="Low";
                    }
                    if(prr=="medium"){
                        var olss="Medium";
                    }
                    if(prr=="high"){
                        var olss="High";
                    }
                    if(prr=="critical"){
                        var olss="Critical";
                    }
                    jQuery("#wpf_message_list").append('<li class="  not_chat_author is_info  " title="1 second ago"><level class="wpf-author"> <span>1 second ago</span></level><p class="task_text">'+current_user_name+' marked as <span class="taskStatusMsg">'+news+'</span> from '+olss+'</p></li>');
                    prr=task_priority;
                    // alert(data);
                    jQuery("#wpf-task-" + current_task + " .wpf_task_label .task_priority").removeClass().addClass("task_priority wpf_" + sel.value);

                    jQuery('.wpf_loader_admin').hide();

                    jQuery('#wpf-task-' + current_task).data('task_priority', sel.value);
                    // jQuery('#wpf-task-' + current_task).data('task_priority', sel.value);

                    jQuery('#all_wpf_list .post_' + current_task + ' .wpf_chat_top .wpf_task_num_top span').removeAttr('class').addClass(custom_class);
                    jQuery('#wpf_task_details .wpf_task_num_top span').removeAttr('class').addClass(custom_class);

                    jQuery('#all_wpf_list li.post_' + current_task).removeClass('low').removeClass('high').removeClass('critical').removeClass('medium').addClass(task_info['task_priority']).addClass('active').addClass('wpf_list');
                    }
                });
                }

                function update_notify_user(user_id) {
                var task_info = [];
                var task_notify_users = [];

                jQuery.each(jQuery('#wpf_attributes_content input[name="author_list_task"]:checked'), function () {
                    task_notify_users.push(jQuery(this).val());
                });
                task_notify_users = task_notify_users.join(",");

                task_info['task_id'] = current_task;
                task_info['task_notify_users'] = task_notify_users;

                var task_info_obj = jQuery.extend({}, task_info);

                jQuery.ajax({
                    method: "POST",
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    data: {action: "wpfb_set_task_notify_users", wpf_nonce: wpf_nonce, task_info: task_info_obj},
                    beforeSend: function () {
                    jQuery('.wpf_loader_admin').show();
                    },
                    success: function (data) {
                    // alert(data);
                    jQuery('.wpf_loader_admin').hide();
                    jQuery('#wpf-task-' + current_task).data('task_notify_users', task_notify_users);
                    }
                });
                }
                var tss;
                var prr;
                //get chat based on WPF post select
                function get_wpf_chat(obj, tg, author) {
                    console.log(jQuery(obj), author);
                    jQuery("#wpf_edit_title").show();
                    jQuery("#wpf_task_tabs_container").show();
                    jQuery("#wpf_edit_title_box").hide();
                    jQuery("#wpf_title_val").val();
                    var post_id = jQuery(obj).data("postid");
                    var view_id = jQuery(obj).data("disp-id");

                    if (tg === undefined) {
                        tg = false;
                    }
                    jQuery("ul#all_wpf_list li.wpf_list").removeClass('active');
                    jQuery(obj).parent().addClass('active');
                    //alert(jQuery(obj).data("postid"));
                    //var post_author_id = <?php echo $author_id; ?>;
                    var post_author_id = jQuery(obj).data('uid');
                    var task_is_internal = jQuery('#wpf-task-'+post_id).hasClass('wpfb-internal');
                    var post_task_type = jQuery(obj).data('task_type');
                    var post_task_status = jQuery(obj).data('wpf_task_status');
                    var post_task_no = jQuery(obj).data("task_no");
                    var task_status = jQuery(obj).data("task_status");
                    var task_page_url = jQuery(obj).data("task_page_url");
                    var wpf_task_screenshot = jQuery(obj).data("wpf_task_screenshot");

                    var task_page_title = jQuery(obj).data("task_page_title");
                    var task_config_author_name = jQuery(obj).data("task_config_author_name");
                    var task_author_name = jQuery(obj).data("task_author_name");

                    let sticker_permission = new_global_sticker_permission;
                    let title_permission = new_global_task_id_permission;
                    var task_config_author_res = jQuery(obj).data("task_config_author_res");
                    var task_config_author_browser = jQuery(obj).data("task_config_author_browser");
                    var task_config_author_browserversion = jQuery(obj).data("task_config_author_browserversion");
                    // var task_config_author_name = jQuery(obj).data("task_config_author_name");
                    var task_notify_users = jQuery(obj).data("task_notify_users");

                    var task_priority = jQuery(obj).data("task_priority");
                    var click = 'yes';
                    var additional_info_html = '<p><span class="wpf_task_ad_info_title">' + wpf_resolution + '</span> ' +''+ task_config_author_res + '</p><p><span class="wpf_task_ad_info_title">' + wpf_browser + '</span> ' + task_config_author_browser + ' ' + task_config_author_browserversion + '</p><p><span class="wpf_task_ad_info_title">' + wpf_user_name + '</span> ' + task_author_name + '</p><p><span class="wpf_task_ad_info_title">' + wpf_task_id + '</span> ' + post_id + '</p>';
                    jQuery.ajax({
                        method: "POST",
                        url: ajaxurl,
                        data: {
                        action: "list_wpf_comment_func",
                        wpf_nonce: wpf_nonce,
                        post_id: post_id,
                        post_author_id: post_author_id,
                        click: click
                        },
                        beforeSend: function () {
                        jQuery('.wpf_loader_admin').show();
                        },
                        success: function (data) {
                        onload_wpfb_tasks = JSON.parse(data);
                    if(onload_wpfb_tasks != null && onload_wpfb_tasks != "null") {
                        current_task = post_id;
                        wpf_tag_autocomplete(document.getElementById("wpf_tags"), wpf_all_tags);
                        jQuery('.wpf_loader_admin').hide();
                        jQuery("#wpf_not_found").remove();
                        // console.log(task_status);
                        jQuery("#get_masg_loader").hide();
                        let display_span = '';
                        let custom_class = '';
                        if (sticker_permission == 'yes') {
                        display_span = '<span class="' + task_priority + '_custom"></span> ';
                        custom_class = task_status + '_custom';
                        }

                        // console.log(obj);
                        let task_count = '';
                        if (title_permission == 'yes') {
                        task_count = view_id;//post_task_no;
                        } else {
                        task_count = '<i class="gg-check"></i>';
                        }

                        let task_label = '';
                        if (task_status == 'complete') {
                        task_label = task_count;
                        } else {
                        task_label = view_id;//post_task_no;
                        }

                    
                        if ( author ) {
                            task_config_author_name_parts = task_config_author_name.split(' ');
                            task_config_author_name_parts[1] = author;
                            task_config_author_name = task_config_author_name_parts.join(' ');
                        }

                        jQuery("div#wpf_task_details .wpf_task_num_top").html(display_span + task_label);
                        jQuery('#wpf_task_details .wpf_task_num_top').removeClass('complete');
                        jQuery('#wpf_task_details .wpf_task_num_top').removeAttr('class').addClass('wpf_task_num_top ' + task_status + ' ' + custom_class);
                        jQuery("div#wpf_task_details .wpf_task_title_top").html(task_page_title);
                        jQuery("div#wpf_task_details .wpf_task_details_top").html(task_config_author_name);
                        jQuery("div#wpf_attributes_content #additional_information").html(additional_info_html);
                        if (current_user_id == post_author_id || wpf_user_type == 'advisor') {
                        jQuery('#wpf_delete_task_container').html('<a href="javascript:void(0)" class="wpf_task_delete_btn"><i class="gg-trash"></i> ' + wpf_delete_ticket + '</a><p class="wpf_hide" id="wpf_task_delete">' + wpf_delete_conform_text2 + ' <a href="javascript:void(0);" class="wpf_task_delete" data-taskid=' + post_id + ' data-elemid=' + post_task_no + '>' + wpf_yes + '</a></p>');
                        } else {
                        jQuery('#wpf_delete_task_container').html('');
                            }
                            tss=task_status;
                            prr=task_priority;
                        jQuery("#task_task_status_attr").val(task_status);
                        jQuery("#task_task_priority_attr").val(task_priority);

                        var wpf_page_url = task_page_url;
                        if (wpf_page_url && post_task_status == 'wpf_admin') {
                        var wpf_page_url_with_and = wpf_page_url.split('&')[1];
                        var wpf_page_url_question = wpf_page_url.split('?')[1];
                        if (wpf_page_url_with_and) {
                            var saperater = '&';
                        }
                        if (wpf_page_url_question) {
                            var saperater = '&';
                        } else {
                            var saperater = '?';
                        }
                        } else {
                        var saperater = '?';
                        }
                        if (wpf_task_screenshot == '') {
                        wpf_open_tab('wpf_message_content');
                        }

                        if (post_task_type == 'general') {
                        jQuery("#wpfb_attr_task_page_link").attr("href", task_page_url + saperater + "wpf_general_taskid=" + post_id);
                        } else if (post_task_type == 'email') { //!email
                            jQuery("#wpfb_attr_task_page_link").attr("href", task_page_url + saperater + "wpf_general_taskid=" + post_id);                    
                        } else if (post_task_type == 'graphics') {
                        wpf_open_tab('wpf_message_content');
                        jQuery("#wpfb_attr_task_page_link").attr("href", task_page_url + "&wpf_taskid=" + post_task_no);
                        } else {
                        jQuery("#wpfb_attr_task_page_link").attr("href", task_page_url + saperater + "wpf_taskid=" + post_task_no);
                        }


                        if (typeof task_notify_users == 'string') {
                        var task_notify_users_arr = task_notify_users.split(',');
                        } else {
                        var task_notify_users_arr = [task_notify_users.toString()];
                        }
                        jQuery('#wpf_attributes_content input[name="author_list_task"]').each(function () {
                        jQuery(this).prop('checked', false);
                        });
                        jQuery('#wpf_attributes_content input[name="author_list_task"]').each(function () {
                        if (jQuery.inArray(this.value, task_notify_users_arr) != '-1') {
                            jQuery(this).prop('checked', true);
                        }
                        });

                        chat_form = get_wpf_message_form(post_id, post_author_id,task_is_internal);
                        jQuery('#wpf_message_form').html(chat_form);
                        if (onload_wpfb_tasks.data == 0) {
                        chat_form = get_wpf_message_form(post_id, post_author_id,task_is_internal);
                        jQuery('#wpf_message_form').html(chat_form);
                        } else {
                        var chat_form = get_wpf_message_form(post_id, post_author_id,task_is_internal);
                        jQuery('#wpf_message_form').html(chat_form);

                        // do not convert link to URl where AWS links are present
                        if ( onload_wpfb_tasks.data.search(/s3.us-east-2.amazonaws.com/) < 0 ) {
                            onload_wpfb_tasks.data = onload_wpfb_tasks.data;
                        }
                        jQuery('ul#wpf_message_list').html(onload_wpfb_tasks.data);

                        // jQuery('ul#wpf_message_list').html(URLify(onload_wpfb_tasks.data));

                        jQuery('#wpf_task_screenshot').attr('src', wpf_task_screenshot);
                        jQuery('#wpf_task_screenshot_link').attr('href', wpf_task_screenshot);
                        jQuery('#all_tag_list').html(onload_wpfb_tasks.wpf_tags);
                        }
                        jQuery('#wpf_message_content').animate({scrollTop: jQuery('#wpf_message_content').prop("scrollHeight")}, 2000);
                    }
                    }
                    });
                }

                jQuery(document).ready(function ($) {
                var wpfeedback_page = getParameterByName('page');
                if (wpfeedback_page == "wpfeedback_page_tasks") {
                jQuery("button.wpf_tab_item.wpf_tasks").trigger('click');
                }
                if (wpfeedback_page == "wpfeedback_page_settings") {
                jQuery("button.wpf_tab_item.wpf_settings").trigger('click');
                }
                if (wpfeedback_page == "wpfeedback_page_integrate") {
                jQuery("button.wpf_tab_item.wpf_addons").trigger('click');
                }
                if (wpfeedback_page == "wpfeedback_page_support") {
                jQuery("button.wpf_tab_item.wpf_support").trigger('click');
                }
                if (wpfeedback_page == "wpfeedback_page_permissions") {
                jQuery("button.wpf_tab_item.wpf_misc").trigger('click');
                }
                if (wpfeedback_page == "wpfeedback_page_graphics") {
                jQuery("button.wpf_tab_item.wpf_graphics").trigger('click');
                }
                });
            </script>
            <?php
        }
    }
}

/*
 * This function is used to initial the Atarim and all related variables on the frontend.
 *
 * @input NULL
 * @return NULL
 */

function show_wpf_comment_button() {
    global $wpdb, $wp_query, $post;
    $wpf_current_page_url = "";
    $disable_for_admin = 0;
    $currnet_user_information = wpf_get_current_user_information();
    $current_role = $currnet_user_information['role'];
    // $current_user_name = $currnet_user_information['display_name'];
    // $current_user_name = (!empty($currnet_user_information['first_name'])) ? $currnet_user_information['first_name'] : $currnet_user_information['display_name']; // $currnet_user_information['display_name'];
    $current_user_name = $currnet_user_information['display_name'];
    $current_user_id = $currnet_user_information['user_id'];
    $wpf_website_builder = get_site_data_by_key('wpf_website_developer');
    if ($current_user_name == 'Guest') {
	$wpf_website_client = get_site_data_by_key('wpf_website_client');
	$wpf_current_role = 'guest';
	if ($wpf_website_client) {
	    $wpf_website_client_info = get_userdata($wpf_website_client);
	    if ($wpf_website_client_info) {
		if ($wpf_website_client_info->display_name == '') {
		    $current_user_name = $wpf_website_client_info->user_nicename;
		} else {
		    $current_user_name = $wpf_website_client_info->display_name;
		}
	    }
	}
    } else {
	$wpf_current_role = wpf_user_type();
    }
    $current_user_name = addslashes($current_user_name);

    $selected_roles = get_site_data_by_key('wpf_selcted_role');
    $selected_roles = explode(',', $selected_roles);

    if ($wpf_current_role == 'advisor') {
	$wpf_tab_permission_user = get_site_data_by_key('wpf_tab_permission_user_webmaster');
	$wpf_tab_permission_priority = get_site_data_by_key('wpf_tab_permission_priority_webmaster');
	$wpf_tab_permission_status = get_site_data_by_key('wpf_tab_permission_status_webmaster');
	$wpf_tab_permission_screenshot = get_site_data_by_key('wpf_tab_permission_screenshot_webmaster');
	$wpf_tab_permission_information = get_site_data_by_key('wpf_tab_permission_information_webmaster');
	$wpf_tab_permission_delete_task = get_site_data_by_key('wpf_tab_permission_delete_task_webmaster');
	$wpf_tab_permission_auto_screenshot = get_site_data_by_key('wpf_tab_auto_screenshot_task_webmaster');
	$wpf_tab_permission_display_stickers = (get_site_data_by_key('wpf_tab_permission_display_stickers_webmaster') != 'no') ? 'yes' : 'no';
	$wpf_tab_permission_display_task_id = (get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no') ? 'yes' : 'no';
	$wpf_tab_permission_keyboard_shortcut = (get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_webmaster') != 'no') ? 'yes' : 'no'; /* v2.1.0 */
    } elseif ($wpf_current_role == 'king') {
	$wpf_tab_permission_user = get_site_data_by_key('wpf_tab_permission_user_client');
	$wpf_tab_permission_priority = get_site_data_by_key('wpf_tab_permission_priority_client');
	$wpf_tab_permission_status = get_site_data_by_key('wpf_tab_permission_status_client');
	$wpf_tab_permission_screenshot = get_site_data_by_key('wpf_tab_permission_screenshot_client');
	$wpf_tab_permission_information = get_site_data_by_key('wpf_tab_permission_information_client');
	$wpf_tab_permission_delete_task = get_site_data_by_key('wpf_tab_permission_delete_task_client');
	$wpf_tab_permission_auto_screenshot = get_site_data_by_key('wpf_tab_auto_screenshot_task_client');
	$wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_client');
	$wpf_tab_permission_keyboard_shortcut = get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_client'); /* v2.1.0 */
	$wpf_tab_permission_display_task_id = (get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no') ? 'yes' : 'no';
    } elseif ($wpf_current_role == 'council') {
	$wpf_tab_permission_user = get_site_data_by_key('wpf_tab_permission_user_others');
	$wpf_tab_permission_priority = get_site_data_by_key('wpf_tab_permission_priority_others');
	$wpf_tab_permission_status = get_site_data_by_key('wpf_tab_permission_status_others');
	$wpf_tab_permission_screenshot = get_site_data_by_key('wpf_tab_permission_screenshot_others');
	$wpf_tab_permission_information = get_site_data_by_key('wpf_tab_permission_information_others');
	$wpf_tab_permission_delete_task = get_site_data_by_key('wpf_tab_permission_delete_task_others');
	$wpf_tab_permission_auto_screenshot = get_site_data_by_key('wpf_tab_auto_screenshot_task_others');
	$wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_others');
	$wpf_tab_permission_keyboard_shortcut = get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_others'); /* v2.1.0 */
	$wpf_tab_permission_display_task_id = (get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no') ? 'yes' : 'no';
    } else {
	$wpf_tab_permission_user = get_site_data_by_key('wpf_tab_permission_user_guest');
	$wpf_tab_permission_priority = get_site_data_by_key('wpf_tab_permission_priority_guest');
	$wpf_tab_permission_status = get_site_data_by_key('wpf_tab_permission_status_guest');
	$wpf_tab_permission_screenshot = get_site_data_by_key('wpf_tab_permission_screenshot_guest');
	$wpf_tab_permission_information = get_site_data_by_key('wpf_tab_permission_information_guest');
	$wpf_tab_permission_delete_task = get_site_data_by_key('wpf_tab_permission_delete_task_guest');
	$wpf_tab_permission_auto_screenshot = get_site_data_by_key('wpf_tab_auto_screenshot_task_guest');
	$wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_guest');
	$wpf_tab_permission_keyboard_shortcut = get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_guest'); /* v2.1.0 */
	$wpf_tab_permission_display_task_id = (get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no') ? 'yes' : 'no';
    }

    $wpf_disable_for_admin = get_site_data_by_key('wpf_disable_for_admin');
    if ($wpf_disable_for_admin == 'yes' && $current_role == 'administrator') {
	$disable_for_admin = 1;
    } else {
	$disable_for_admin = 0;
    }

    $current_page_id = get_the_ID();
    if ($current_page_id == '') {
	if (isset($wp_query->post->ID)) {
	    $current_page_id = $wp_query->post->ID;
	}
    }

    $current_page_title = addslashes(get_the_title($current_page_id));

    $page_type = "default";

    if (class_exists('WooCommerce')) {

	if (is_category()) {
	    $page_type = "archive";
	    $category = get_queried_object();
	    $current_page_id = $category->term_id;
	    $current_page_url = get_category_link($current_page_id);
	    $current_page_title = addslashes(get_cat_name($current_page_id));
	} else if (is_archive() && (!is_shop()) && (!is_category())) {
	    if (!is_wp_error(get_term_link(get_query_var('term'), get_query_var('taxonomy')))) {
		$current_page_url = get_term_link(get_query_var('term'), get_query_var('taxonomy'));
	    } else {
		$current_page_url = "";
	    }
	} else if (is_shop()) {
	    $current_page_url = get_permalink(woocommerce_get_page_id('shop'));
	} else if (is_home()) {
	    $current_page_url = get_permalink(get_option('page_for_posts'));
	} else {
	    $current_page_url = get_permalink($current_page_id);
	}
    } else {
	if (is_category()) {
	    $page_type = "archive";
	    $category = get_queried_object();
	    $current_page_id = $category->term_id;
	    $current_page_url = get_category_link($current_page_id);
	    $current_page_title = addslashes(get_cat_name($current_page_id));
	} else if (is_tag()) { // tag archieve page
	    $category = get_queried_object();
	    $current_page_id = $category->term_id;
	    $current_page_url = get_category_link($current_page_id);
	    $current_page_title = $category->name;
	} else if (is_tax()) { // taxonomy archieve page
	    $category = get_queried_object();
	    $current_page_id = $category->term_id;
	    $current_page_url = get_category_link($current_page_id);
	    $current_page_title = $category->name;
	} else if (is_home()) {
	    if (get_query_var('is_graphic_page')) { // for graphic page
		$current_page_id = '';
		$current_page_title = '';
		$current_page_url = '';
	    } else {
		$current_page_url = get_permalink(get_option('page_for_posts'));
	    }
	} else if (is_archive() && (!is_category())) {
	    $current_page_url = "";
	} else {
	    $current_page_url = get_permalink($current_page_id);
	}
    }

    //fallback if URL is not in the database
    $fallback_link = 0;
    if ($current_page_id == '' || $current_page_id == 0 || $current_page_url == "") {
	$fallback_link = 1;
	$current_page_id = 0;
	$current_page_url == "";
    }

    $wpf_show_front_stikers = get_site_data_by_key('wpf_show_front_stikers');

    $unix_time_now= time();

    $wpf_check_atarim_server=get_option('atarim_server_down_check');

    if ($unix_time_now > $wpf_check_atarim_server) {
        update_option('atarim_server_down','false','no');
    }

    $atarim_server_down=get_option('atarim_server_down');

    $wpfb_users = do_shortcode('[wpf_user_list_front]');
    //$wpfb_users = $li_html;
    $ajax_url = admin_url('admin-ajax.php');
    $plugin_url = WPF_PLUGIN_URL;

    $sound_file = esc_url(plugins_url('images/wpf-screenshot-sound.mp3', __FILE__));

    $wpf_tag_enter_img = esc_url(plugins_url('images/enter.png', __FILE__));

    $bubble_and_db_id=get_last_task_id(true);
	$comment_count = $bubble_and_db_id['Dbid'];
	$bubble_comment_count = $bubble_and_db_id['Bubbleid'];

    $wpf_check_page_builder_active = wpf_check_page_builder_active();

    /* =====Start Check customize.php==== */
    if ($wpf_check_page_builder_active == 0) {
	if (is_customize_preview()) {
	    $wpf_check_page_builder_active = 1;
	} else {
	    $wpf_check_page_builder_active = 0;
	}
    }
    /* =====END check customize.php==== */

    /* =====Start filter sidebar HTML Structure==== */
    if (get_query_var('is_graphic_page') && $wpf_show_front_stikers == 'yes') {
	$checkbox_checked = "checked";
    } else {
	$checkbox_checked = "";
    }
    $wpf_active = wpf_check_if_enable();
    $is_site_archived = get_site_data_by_key('wpf_site_archived');
    $backend_btn = '';
    $wpf_go_to_cloud_dashboard_btn_tab = '';
    if ($current_user_id > 0) {
	if ($wpf_current_role == 'advisor') {
	    $wpf_go_to_cloud_dashboard_btn_tab = '<a href="' . WPF_APP_SITE_URL . '/login" target="_blank" class="wpf_filter_tab_btn cloud_dashboard_btn" title="' . __("Atarim Dashboard", "wpfeedback") . '">'.get_wpf_icon().'</a>';
	}
	$sidebar_col = "wpf_col3";
	$backend_btn = ' <button class="wpf_tab_sidebar wpf_backend"  onclick="openWPFTab(\'wpf_backend\')" >' . __('Backend', 'wpfeedback') . '</button>';

	if (get_query_var('is_graphic_page')) {
	    $wpf_current_page_url = site_url() . '/graphic?id=' . $_GET['id']. '?wpf_login=1';
	    ;
	} else {
	    $wpf_current_page_url = get_permalink() . '?wpf_login=1';
	}
    } else {
	$sidebar_col = "wpf_col2";
    }

    $wpf_nonce = wpf_wp_create_nonce();
    $wpf_admin_bar = 0;
    if (is_admin_bar_showing()) {
	$wpf_admin_bar = 1;
    }

    $restrict_plugin=get_option('restrict_plugin');

    if ($wpf_active == 1 && $wpf_check_page_builder_active == 0 && (!$is_site_archived)) {
        require_once(WPF_PLUGIN_DIR . 'inc/wpf_popup_string.php');
        echo "<style>li#wp-admin-bar-wpfeedback_admin_bar {display: none !important;}</style>";
        /* v2.1.0 */
        if ($current_page_id == 0) {
            echo "<script>var fallback_link_check='$fallback_link',page_type='$page_type',wpf_tag_enter_img='$wpf_tag_enter_img',disable_for_admin='$disable_for_admin',wpf_nonce='$wpf_nonce', current_role='$current_role', wpf_current_role='$wpf_current_role', current_user_name='$current_user_name', current_user_id='$current_user_id', wpf_website_builder='$wpf_website_builder', wpfb_users = '$wpfb_users',  ajaxurl = '$ajax_url', current_page_url = window.location.href.split('?')[0], current_page_title = '$current_page_title', current_page_id = '$current_page_id', wpf_screenshot_sound = '$sound_file', plugin_url = '$plugin_url', comment_count='$comment_count', bubble_comment_count='$bubble_comment_count', wpf_show_front_stikers='$wpf_show_front_stikers', wpf_tab_permission_user='$wpf_tab_permission_user', wpf_tab_permission_priority='$wpf_tab_permission_priority', wpf_tab_permission_status='$wpf_tab_permission_status', wpf_tab_permission_screenshot='$wpf_tab_permission_screenshot', wpf_tab_permission_information='$wpf_tab_permission_information', wpf_tab_permission_delete_task='$wpf_tab_permission_delete_task',wpf_tab_permission_auto_screenshot='$wpf_tab_permission_auto_screenshot', wpf_admin_bar=$wpf_admin_bar,wpf_tab_permission_display_stickers='$wpf_tab_permission_display_stickers', wpf_tab_permission_display_task_id = '$wpf_tab_permission_display_task_id', wpf_tab_permission_keyboard_shortcut = '$wpf_tab_permission_keyboard_shortcut',restrict_plugin='$restrict_plugin',atarim_server_down='$atarim_server_down';</script>";
        } else {
            echo "<script>var fallback_link_check='$fallback_link',page_type='$page_type',wpf_tag_enter_img='$wpf_tag_enter_img',disable_for_admin='$disable_for_admin',wpf_nonce='$wpf_nonce', current_role='$current_role', wpf_current_role='$wpf_current_role', current_user_name='$current_user_name', current_user_id='$current_user_id', wpf_website_builder='$wpf_website_builder', wpfb_users = '$wpfb_users',  ajaxurl = '$ajax_url', current_page_url = '$current_page_url', current_page_title = '$current_page_title', current_page_id = '$current_page_id', wpf_screenshot_sound = '$sound_file', plugin_url = '$plugin_url', comment_count='$comment_count', bubble_comment_count='$bubble_comment_count', wpf_show_front_stikers='$wpf_show_front_stikers', wpf_tab_permission_user='$wpf_tab_permission_user', wpf_tab_permission_priority='$wpf_tab_permission_priority', wpf_tab_permission_status='$wpf_tab_permission_status', wpf_tab_permission_screenshot='$wpf_tab_permission_screenshot', wpf_tab_permission_information='$wpf_tab_permission_information', wpf_tab_permission_delete_task='$wpf_tab_permission_delete_task',wpf_tab_permission_auto_screenshot='$wpf_tab_permission_auto_screenshot', wpf_admin_bar=$wpf_admin_bar,wpf_tab_permission_display_stickers='$wpf_tab_permission_display_stickers',wpf_tab_permission_display_task_id = '$wpf_tab_permission_display_task_id', wpf_tab_permission_keyboard_shortcut = '$wpf_tab_permission_keyboard_shortcut',restrict_plugin='$restrict_plugin',atarim_server_down='$atarim_server_down';</script>";
        }
        $wpf_sidebar_closeicon = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 357 357" enable-background="new 0 0 357 357" xml:space="preserve"><g><g id="close"><polygon fill="#F5325C" points="357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3 214.2,178.5 "/></g></g></svg>';
        if ($disable_for_admin == 0) {
            if (get_query_var('is_graphic_page')) {
            $wpf_sidebar_style = "";
            $wpf_sidebar_active = "wpf_graphics active";
            } else {
            $wpf_sidebar_active = "";
            $wpf_sidebar_style = "opacity: 0; margin-right: -380px";
            }

           /* if (!session_id()) {
            session_start();
            }*/
            //r($_SESSION);
            $wpf_site_id = get_option('wpf_site_id');
            $bottom_panel_db=get_option('bottom_panel');
            $bottom_style = "";
            $bottom_button_style = 'bottom: -67px;';
            if(isset($bottom_panel_db) && $bottom_panel_db == '0') {
            $bottom_button_style = 'bottom: 3px;';
            }

            /* ================filter Tabs Content HTML================ */
            $wpf_task_status_filter_btn = '<div id="wpf_filter_taskstatus" class=""><label class="wpf_filter_title">' . get_wpf_status_icon() . ' ' . __('Filter by Status:', 'wpfeedback') . '</label>' . wp_feedback_get_texonomy_filter("task_status") . '</div>';

            $wpf_task_priority_filter_btn = '<div id="wpf_filter_taskpriority" class=""><label class="wpf_filter_title">' . get_wpf_priority_icon() . ' ' . __("Filter by Priority:", "wpfeedback") . '</label>' . wp_feedback_get_texonomy_filter("task_priority") . '</div>';

            /* If compact mode enabled, load it and don't load the bottom bar => v2.1.0 */
            $enable_compact_mode = get_site_data_by_key('wpf_enabled_compact_mode');
            if ( is_feature_enabled( 'bottom_bar_enabled' ) && $enable_compact_mode !== 'yes' ) {

                $wpf_sidebar_header = '';
                if (get_query_var('is_graphic_page')) {
                    $wpf_sidebar_header = '<div class="wpf_sidebar_header ' . $sidebar_col . '">
                                            <!-- =================Top Tabs================-->
                                                <div class="top_tabs">
                                                    <button class="wpf_tab_sidebar wpf_thispage wpf_active" onclick="openWPFTab(\'wpf_thispage\')" >' . __('This Page', 'wpfeedback') . '</button>
                                                </div>
                                                <div id="sidebar_filters">
                                                    <ul class="icons-block">
                                                        <li class="status"> 
                                                            <a class="wpf_filter_tab_btn_bottom wpf_btm_withside" data-tag="wpf_task_status_filter_btn" href="javascript:void(0);" title="'.__("Filter By Status", "wpfeedback").'" id="wpf_filter_btn_bottom_status">'
                                                    . get_wpf_status_icon().'
                                                            </a>
                                                        </li>
                                                        <li class="priority"> <a id="wpf_filter_btn_bottom_priority" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_task_priority_filter_btn" title="'.__("Filter By Priority", "wpfeedback").'">'. get_wpf_priority_icon().'</a></li>
                                                        <li class="search">
                                                            <a id="wpf_filter_btn_bottom_search" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_search_filter_btn" title="'.__("Search by task title", "wpfeedback").'">                           
                                                              
                                                                <svg class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                                                    <g id="surface1">
                                                                        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li> 
                                                    </ul>                      
                                                </div>             
                                            </div>';
                } else {
                    $wpf_sidebar_header = '<div class="wpf_sidebar_header ' . $sidebar_col . '">
                                            <!-- =================Top Tabs================-->
                                                <div class="top_tabs">
                                                    <button class="wpf_tab_sidebar wpf_thispage wpf_active" onclick="openWPFTab(\'wpf_thispage\')" >' . __('This Page', 'wpfeedback') . '</button>
                                                    <button class="wpf_tab_sidebar wpf_allpages"  onclick="openWPFTab(\'wpf_allpages\')" >' . __('All Pages', 'wpfeedback') . '</button>' . $backend_btn . '<span id="close_sidebar" class="close_sidebar" onclick="expand_sidebar()">' . $wpf_sidebar_closeicon . '</span>' . '
                                                </div>
                                                <div id="sidebar_filters">
                                                    <ul class="icons-block">
                                                        <li class="status"> 
                                                            <a class="wpf_filter_tab_btn_bottom wpf_btm_withside" data-tag="wpf_task_status_filter_btn" href="javascript:void(0);" title="'.__("Filter By Status", "wpfeedback").'" id="wpf_filter_btn_bottom_status">'
                                                    . get_wpf_status_icon().'
                                                            </a>
                                                        </li>
                                                        <li class="priority"> <a id="wpf_filter_btn_bottom_priority" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_task_priority_filter_btn" title="'.__("Filter By Priority", "wpfeedback").'">'. get_wpf_priority_icon().'</a></li>
                                                        <li class="search">
                                                            <a id="wpf_filter_btn_bottom_search" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_search_filter_btn" title="'.__("Search by task title", "wpfeedback").'">                           
                                                              
                                                                <svg class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                                                    <g id="surface1">
                                                                        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li> 
                                                    </ul>                      
                                                </div>             
                                            </div>';
                }

                $bottom_bar_html = '<div id="wpf_already_comment" class="wpf_hide"><div class="wpf_notice_title">' . __("Task already exist for this element.", "wpfeedback") . '</div><div class="wpf_notice_text">' . __("Write your message in the existing thread. <br>Here, we opened it for you.", "wpfeedback") . '</div></div><div id="pushed_to_media" class="wpf_hide"><div class="wpf_notice_title">' . __("Pushed to Media Folder.", "wpfeedback") . '</div><div class="wpf_notice_text">' . __("The file was added to the website's media folder, you can now use it from the there.", "wpfeedback") . '</div></div><div id="wpf_reconnecting_task" class="wpf_hide" style="display: none;"><div class="wpf_notice_title">' . __("Remapping task....", "wpfeedback") . '</div><div class="wpf_notice_text">' . __("Give it a few seconds. <br>Then, refresh the page to see the task in the new position.", "wpfeedback") . '</div></div><div id="wpf_reconnecting_enabled" class="wpf_hide" style="display: none;"><div class="wpf_notice_title">' . __("Remap task", "wpfeedback") . '</div><div class="wpf_notice_text">' . __("Place the task anywhere on the page to pinpoint the location of the request.", "wpfeedback") . '</div></div><div id="wpf_launcher" data-html2canvas-ignore="true" ><div class="wpf_launch_buttons" style="'.$bottom_button_style.'"><div class="wpf_start_comment"><a href="javascript:enable_comment();" title="' . __('Click to give your feedback!', 'wpfeedback') . '" data-placement="left" class="comment_btn"><i class="gg-math-plus"></i></a></div>
                <div class="wpf_expand"><a href="javascript:expand_bottom_bar()" id="wpf_expand_btn" title="' . __("Panel", "wpfeedback") . '"><img title="Panel" src="'. get_wpf_favicon().'" style="width:65px" /></a></div></div>
                <div class="wpf_sidebar_container ' . $wpf_sidebar_active . '" style="' . $wpf_sidebar_style . '";>
                '. $wpf_sidebar_header .'
                <div class="filter_ui_content">
                        <div id="wpf_side_filter">
                            <div class="wpf_list wpf_hide" id="wpf_task_status_filter_btn">' . $wpf_task_status_filter_btn . '</div>
                            <div class="wpf_list wpf_hide" id="wpf_task_priority_filter_btn">' . $wpf_task_priority_filter_btn . '</div>
                            <div class="wpf_list wpf_hide" id="wpf_search_filter_btn"><div id="sidebar_search" class="wpf_search_box"><span class="wpf_search_box">
                            <svg onclick="hide_search_from_sidebar()" class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                <g id="surface1">
                                    <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                </g>
                            </svg>
                            <input onkeyup="wp_feedback_cat_filter(event, this)" type="text" name="wpf_search_title" class="wpf_search_title" value="" id="wpf_search_title" placeholder="'.__("Search by task title", "wpfeedback").'"></span></div></div>
                        </div>                        
                    </div> 
                <div class="wpf_sidebar_content">
                <div class="wpf_sidebar_loader wpf_hide"></div>                
                <div id="wpf_thispage" class="wpf_thispage_tab wpf_container wpf_active_filter"><!--<div class="custom_today">today</div>--><ul id="wpf_thispage_container_today"></ul><!--<div class="custom_yesterday">yesterday</div> --> <ul id="wpf_thispage_container_yesterday"></ul><!-- <div class="custom_Weekly">Weekly</div> --> <ul id="wpf_thispage_container_this_week"></ul><!-- <div class="custom_this_month">This Month</div> --> <ul id="wpf_thispage_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_thispage_container_year"></ul> <!-- <div class="custom_other">Other</div> --> <ul id="wpf_thispage_container_other"></ul></div>
    
                <div id="wpf_allpages" class="wpf_allpages_tab wpf_container" style="display:none";><!--<div class="custom_today">today</div>--><ul id="wpf_allpages_container_today"></ul><!--<div class="custom_yesterday">yesterday</div> --> <ul id="wpf_allpages_container_yesterday"></ul><!-- <div class="custom_Weekly">Weekly</div> --> <ul id="wpf_allpages_container_this_week"></ul><!-- <div class="custom_this_month">This Month</div> --> <ul id="wpf_allpages_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_allpages_container_year"></ul> <!-- <div class="custom_other">Other</div> --> <ul id="wpf_allpages_container_other"></ul></div>
    
                 <div id="wpf_backend" class="wpf_backend_tab wpf_container" style="display:none";><!--<div class="custom_today">today</div>--><ul id="wpf_backend_container_today"></ul><!--<div class="custom_yesterday">yesterday</div> --> <ul id="wpf_backend_container_yesterday"></ul><!-- <div class="custom_Weekly">Weekly</div> --> <ul id="wpf_backend_container_this_week"></ul><!-- <div class="custom_this_month">This Month</div> --> <ul id="wpf_backend_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_backend_container_year"></ul> <!-- <div class="custom_other">Other</div> --> <ul id="wpf_backend_container_other"></ul></div>
                </div>
                </div>'.generate_bottom_part_html().'
                </div>';
            } else {
                $wpf_graphics_page_class = '';
                $wpf_sidebar_header = '';
                if (get_query_var('is_graphic_page')) {
                    $wpf_graphics_page_class = 'graphic_page_compact_buttons';
                    $wpf_sidebar_header = '<div class="wpf_sidebar_header ' . $sidebar_col . '">
                                            <!-- =================Top Tabs================-->
                                                <div class="top_tabs">
                                                    <button class="wpf_tab_sidebar wpf_thispage wpf_active" onclick="openWPFTab(\'wpf_thispage\')" >' . __('This Page', 'wpfeedback') . '</button>
                                                </div>
                                                <div id="sidebar_filters">
                                                    <ul class="icons-block">
                                                        <li class="status"> 
                                                            <a class="wpf_filter_tab_btn_bottom wpf_btm_withside" data-tag="wpf_task_status_filter_btn" href="javascript:void(0);" title="'.__("Filter By Status", "wpfeedback").'" id="wpf_filter_btn_bottom_status">'
                        . get_wpf_status_icon().'
                                                            </a>
                                                        </li>
                                                        <li class="priority"> <a id="wpf_filter_btn_bottom_priority" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_task_priority_filter_btn" title="'.__("Filter By Priority", "wpfeedback").'">'. get_wpf_priority_icon().'</a></li>
                                                        <li class="search">
                                                            <a id="wpf_filter_btn_bottom_search" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_search_filter_btn" title="'.__("Search by task title", "wpfeedback").'">                            
                                                                <svg class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                                                    <g id="surface1">
                                                                        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li> 
                                                    </ul>                      
                                                </div> 
                                            </div>';
                } else {
                    $wpf_sidebar_header = '<div class="wpf_sidebar_header ' . $sidebar_col . '">
                                            <!-- =================Top Tabs================-->
                                                <div class="top_tabs">
                                                    <button class="wpf_tab_sidebar wpf_thispage wpf_active" onclick="openWPFTab(\'wpf_thispage\')" >' . __('This Page', 'wpfeedback') . '</button>
                                                    <button class="wpf_tab_sidebar wpf_allpages"  onclick="openWPFTab(\'wpf_allpages\')" >' . __('All Pages', 'wpfeedback') . '</button>' . $backend_btn . '<span id="close_sidebar" class="close_sidebar" onclick="expand_compact_sidebar()">' . $wpf_sidebar_closeicon . '</span>' . '
                                                </div>
                                                <div id="sidebar_filters">
                                                    <ul class="icons-block">
                                                        <li class="status"> 
                                                            <a class="wpf_filter_tab_btn_bottom wpf_btm_withside" data-tag="wpf_task_status_filter_btn" href="javascript:void(0);" title="Filter By Status" id="wpf_filter_btn_bottom_status">'
                                                    . get_wpf_status_icon().'
                                                            </a>
                                                        </li>
                                                        <li class="priority"> <a id="wpf_filter_btn_bottom_priority" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_task_priority_filter_btn" title="Filter By Priority">'. get_wpf_priority_icon().'</a></li>
                                                        <li class="search">
                                                            <a id="wpf_filter_btn_bottom_search" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_search_filter_btn" title="'.__("Search by task title", "wpfeedback").'">                            
                                                                <svg class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                                                    <g id="surface1">
                                                                        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li> 
                                                    </ul>                      
                                                </div> 
                                            </div>';
                }
                $bottom_bar_html = '<div id="wpf_launcher" class="wpf-compact-launcher" data-html2canvas-ignore="true" style="user-select: auto;">                                        
                                            
                                            <div id="wpf_launch_buttons_wrapper">                                            
                                                 <div class="wpf_launch_buttons ' . $wpf_graphics_page_class . '" style="user-select: auto; left: -56px;">     
                                                    <div class="wpf_start_comment" style="user-select: auto;">
                                                        <a href="javascript:enable_comment();" title="Click to leave a comment" data-placement="left" class="comment_btn" style="user-select: auto; cursor: pointer;">
                                                            <i class="gg-math-plus" style="user-select: auto;"></i>
                                                        </a>
                                                    </div>
                    
                                                    <div class="wpf_expand" style="user-select: auto;">
                                                        <a href="javascript:expand_compact_sidebar()" id="wpf_expand_btn" title="Collaboration Sidebar" style="user-select: auto; cursor: pointer;" class="tasks-btn">
                                                            <img src="' . get_wpf_favicon() . '" />
                                                        </a>
                                                    </div>
                                                    
                                                    <div class="wpf_general_comment">
                                                        <a class="wpf_green_btn wpf_general_btn wpf_comment_mode_general_task active_comment" id="wpf_comment_mode_general_task" href="javascript:void(0)" onclick="wpf_new_general_task(0)" title="Click to create a generic request" style="user-select: auto; cursor: crosshair;">
                                                            '. get_wpf_exclamation_icon() .'
                                                            <span style="user-select: auto;">'. __('General', 'wpfeedback') .'</span>
                                                        </a>
                                                    </div>
                                                </div>			                                
			                                </div>
			                            
			                            <div class="wpf_sidebar_container ' . $wpf_sidebar_active . '" style="' . $wpf_sidebar_style . '";>
                                            ' . $wpf_sidebar_header . '
                                            <div class="filter_ui_content">
                                                    <div id="wpf_side_filter">
                                                        <div class="wpf_list wpf_hide" id="wpf_task_status_filter_btn">' . $wpf_task_status_filter_btn . '</div>
                                                        <div class="wpf_list wpf_hide" id="wpf_task_priority_filter_btn">' . $wpf_task_priority_filter_btn . '</div>
                                                        <div class="wpf_list wpf_hide" id="wpf_search_filter_btn"><div id="sidebar_search" class="wpf_search_box"><span class="wpf_search_box">                                                       
                                                        <svg onclick="hide_search_from_sidebar()" class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                                            <g id="surface1">
                                                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                                            </g>
                                                        </svg>
                                                        <input onkeyup="wp_feedback_cat_filter(event, this)" type="text" name="wpf_search_title" class="wpf_search_title" value="" id="wpf_search_title" placeholder="'.__("Search by task title", "wpfeedback").'"></span></div></div>                                                     
                                                    </div>                        
                                            </div>                                             
                                            <div class="wpf_sidebar_content">
                                            <div class="wpf_sidebar_loader wpf_hide"></div>
                                            <div id="wpf_thispage" class="wpf_thispage_tab wpf_container wpf_active_filter"><!--<div class="custom_today">today</div>--><ul id="wpf_thispage_container_today"></ul><!--<div class="custom_yesterday">yesterday</div> --> <ul id="wpf_thispage_container_yesterday"></ul><!-- <div class="custom_Weekly">Weekly</div> --> <ul id="wpf_thispage_container_this_week"></ul><!-- <div class="custom_this_month">This Month</div> --> <ul id="wpf_thispage_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_thispage_container_year"></ul> <!-- <div class="custom_other">Other</div> --> <ul id="wpf_thispage_container_other"></ul></div>
                                
                                            <div id="wpf_allpages" class="wpf_allpages_tab wpf_container" style="display:none";><!--<div class="custom_today">today</div>--><ul id="wpf_allpages_container_today"></ul><!--<div class="custom_yesterday">yesterday</div> --> <ul id="wpf_allpages_container_yesterday"></ul><!-- <div class="custom_Weekly">Weekly</div> --> <ul id="wpf_allpages_container_this_week"></ul><!-- <div class="custom_this_month">This Month</div> --> <ul id="wpf_allpages_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_allpages_container_year"></ul> <!-- <div class="custom_other">Other</div> --> <ul id="wpf_allpages_container_other"></ul></div>
                                
                                             <div id="wpf_backend" class="wpf_backend_tab wpf_container" style="display:none";><!--<div class="custom_today">today</div>--><ul id="wpf_backend_container_today"></ul><!--<div class="custom_yesterday">yesterday</div> --> <ul id="wpf_backend_container_yesterday"></ul><!-- <div class="custom_Weekly">Weekly</div> --> <ul id="wpf_backend_container_this_week"></ul><!-- <div class="custom_this_month">This Month</div> --> <ul id="wpf_backend_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_backend_container_year"></ul> <!-- <div class="custom_other">Other</div> --> <ul id="wpf_backend_container_other"></ul></div>
                                            </div>
                                        </div>'.generate_side_part_html().'
                                    </div>';
            }

            echo $bottom_bar_html;
            $wpf_get_user_type = get_user_meta($current_user_id, 'wpf_user_initial_setup', true);

            if ($wpf_get_user_type == '' && $current_user_id && in_array($current_role, $selected_roles)) {

                $wpf_get_user_typpe = get_user_meta($current_user_id, 'wpf_user_initial_setup', true);
                $wpf_get_user_type = esc_attr(wpf_user_type());
                $wpf_user_flow = isset($_GET['wpf-user-flow']) ? true : false;
                if(!$wpf_get_user_type) delete_option('wpf_app_user_flow');
                if(isset($_GET['wpf-user-flow']) && !get_option('wpf_app_user_flow')) {
                    update_option('wpf_app_user_flow', true);
                    $wpf_app_user_flow = true;
                    $wpf_user_flow = true;
                }elseif(isset($_GET['wpf-existing-user-flow'])) {
                    $wpf_app_user_flow = false;
                    $wpf_user_flow = false;
                }else {
                    $wpf_app_user_flow = true;
                    $wpf_user_flow = true;
                }
                if ($wpf_get_user_typpe == '' && $current_user_id && in_array($current_role, $selected_roles) && ($wpf_app_user_flow && $wpf_user_flow)) {
                    require_once(WPF_PLUGIN_DIR . 'inc/frontend/wpf_frontend_initial_setup.php');
                }
            }
            require_once(WPF_PLUGIN_DIR . 'inc/frontend/wpf_general_task_modal.php');
            require_once(WPF_PLUGIN_DIR . 'inc/frontend/wpf_approve_page_modal.php');
            require_once(WPF_PLUGIN_DIR . 'inc/frontend/wpf_responsive_page_modal.php');
            require_once(WPF_PLUGIN_DIR . 'inc/frontend/wpf_restrictions_modal.php');
        }
    }
    $wpf_enabled = get_site_data_by_key('enabled_wpfeedback');

    if ( !is_user_logged_in() && ($wpf_enabled == 'yes' && (!$is_site_archived)) ) {
	require_once(WPF_PLUGIN_DIR . 'inc/frontend/wpf_login_modal.php');
    }
    // require_once(WPF_PLUGIN_DIR . 'inc/frontend/wpf_curl_error_modal.php');
}

add_action('wp_footer', 'show_wpf_comment_button');

function wpf_check_permission() {

    $currnet_user_information = wpf_get_current_user_information();
    $current_role = $currnet_user_information['role'];
    // $current_user_name = $currnet_user_information['display_name'];
    // $current_user_name = (!empty($currnet_user_information['first_name'])) ? $currnet_user_information['first_name'] : $currnet_user_information['display_name']; // $currnet_user_information['display_name'];
    $current_user_name = $currnet_user_information['display_name'];
    $current_user_id = $currnet_user_information['user_id'];
    $wpf_website_builder = get_site_data_by_key('wpf_website_developer');
    if ($current_user_name == 'Guest') {
	$wpf_website_client = get_site_data_by_key('wpf_website_client');
	$wpf_current_role = 'guest';
	if ($wpf_website_client) {
	    $wpf_website_client_info = get_userdata($wpf_website_client);
	    if ($wpf_website_client_info) {
		if ($wpf_website_client_info->display_name == '') {
		    $current_user_name = $wpf_website_client_info->user_nicename;
		} else {
		    $current_user_name = $wpf_website_client_info->display_name;
		}
	    }
	}
    } else {
	$wpf_current_role = wpf_user_type();
    }
    $current_user_name = addslashes($current_user_name);

    if ($wpf_current_role == 'advisor') {
	$wpf_tab_permission_display_stickers = (get_site_data_by_key('wpf_tab_permission_display_stickers_webmaster') != 'no') ? 'yes' : 'no';
	$wpf_tab_permission_display_task_id = (get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no') ? 'yes' : 'no';
    } elseif ($wpf_current_role == 'king') {
	$wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_client');
	$wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_client');
    } elseif ($wpf_current_role == 'council') {
	$wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_others');
	$wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_others');
    } else {
	$wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_guest');
	$wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_guest');
    }
}

function add_sticker_permission_to_head() {
    $currnet_user_information = wpf_get_current_user_information();
    $current_role = $currnet_user_information['role'];
    // $current_user_name = $currnet_user_information['display_name'];
    // $current_user_name = (!empty($currnet_user_information['first_name'])) ? $currnet_user_information['first_name'] : $currnet_user_information['display_name']; // $currnet_user_information['display_name'];
    $current_user_name = $currnet_user_information['display_name'];
    $current_user_id = $currnet_user_information['user_id'];
    $wpf_website_builder = get_site_data_by_key('wpf_website_developer');
    if ($current_user_name == 'Guest') {
	$wpf_website_client = get_site_data_by_key('wpf_website_client');
	$wpf_current_role = 'guest';
	if ($wpf_website_client) {
	    $wpf_website_client_info = get_userdata($wpf_website_client);
	    if ($wpf_website_client_info) {
		if ($wpf_website_client_info->display_name == '') {
		    $current_user_name = $wpf_website_client_info->user_nicename;
		} else {
		    $current_user_name = $wpf_website_client_info->display_name;
		}
	    }
	}
    } else {
	$wpf_current_role = wpf_user_type();
    }
    $current_user_name = addslashes($current_user_name);

    if ($wpf_current_role == 'advisor') {
	$wpf_tab_permission_display_stickers = (get_site_data_by_key('wpf_tab_permission_display_stickers_webmaster') != 'no') ? 'yes' : 'no';
	$wpf_tab_permission_display_task_id = (get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no') ? 'yes' : 'no';
    } elseif ($wpf_current_role == 'king') {
	$wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_client');
	$wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_client');
    } elseif ($wpf_current_role == 'council') {
	$wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_others');
	$wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_others');
    } else {
	$wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_guest');
	$wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_guest');
    }

    echo '<script>var new_global_sticker_permission = "' . $wpf_tab_permission_display_stickers . '", new_global_task_id_permission = "' . $wpf_tab_permission_display_task_id . '"</script>';
}

add_filter('admin_head', 'add_sticker_permission_to_head');

// Remove wpfeedback CPT page in menu
/*
 * This function is used to remove the backend menu for the wpfeedback post type so they are not accessible by backend users.
 *
 * @input NULL
 * @return NULL
 */
function wpf_disable_comments_admin_menu() {
    remove_menu_page('edit.php?post_type=wpfeedback');
}

add_action('admin_menu', 'wpf_disable_comments_admin_menu');

/* Remove 'wpfeedback' comment type in admin side */
/*
 * This function is used to disabled the Atarim features on Atarim pages.
 *
 * @input NULL
 * @return NULL
 */
add_action('pre_get_comments', 'wpf_exclude_comments');

function wpf_exclude_comments($query) {
    if ($query->query_vars['type'] !== 'wp_feedback') {
	$query->query_vars['type__not_in'] = array_merge((array) $query->query_vars['type__not_in'], array('wp_feedback'));
    }
}

/*
 * This function is used to set the redirect of Upgrade menu item.
 *
 * @input NULL
 * @return NULL
 */
add_action('admin_head', 'wpf_upgrade_menu_page_redirect');

function wpf_upgrade_menu_page_redirect() {
    $wpf_license = get_option('wpf_license');
    $wpf_user_type = wpf_user_type();
    if ($wpf_license != 'valid') {
	?>
	<style type="text/css">
	    div#wpf_tasks {
		position: relative;
	    }
	</style>
    <?php } if ($wpf_user_type == 'advisor') { ?>
	<script type="text/javascript">
	    jQuery(document).ready(function ($) {
		jQuery('#toplevel_page_wp_feedback ul li').last().find('a').attr('target', '_blank');
	    });
	</script>
	<?php
    }
}

require_once(WPF_PLUGIN_DIR . 'inc/wpf_class.php');

/*
 * This function is used to redirect the users to the settings page on the activation of the plugin.
 *
 * @input String
 * @return Redirect
 */

function wpf_activation_redirect($plugin) {
    if ($plugin == plugin_basename(__FILE__)) {
	$url = admin_url('admin.php?page=wpfeedback_page_settings');
	wp_redirect($url);
	exit;
    }
}

add_action('activated_plugin', 'wpf_activation_redirect', 10, 1);

/*
 * This function is used to detect if the page builder is active on the current running page.
 *
 * @input NULL
 * @return Boolean
 */

function wpf_check_page_builder_active() {
    $page_builder = 0;
    /* ========Check Divi editor Active======== */
    if (isset($_GET['et_fb']) || (is_admin() && function_exists('et_pb_is_pagebuilder_used') && et_pb_is_pagebuilder_used())) {
	$page_builder = 1;
    }else if(isset($_GET['page'])) {
    	if($_GET['page']=='et_theme_builder'){
			$page_builder = 1;
		}
	}
    /* ------Check wpbeaver editor Active------- */ else if (class_exists('FLBuilderModel') && FLBuilderModel::is_builder_active()) {
	$page_builder = 1;
    }
    /* ========Check brizy editor Active======== */ else if (isset($_GET['brizy-edit']) || isset($_GET['brizy-edit-iframe']) || isset($_GET['brizy_post'])) {
	$page_builder = 1;
    }
    /* =======Check oxygen editor Active======== */ else if (isset($_GET['ct_builder']) || isset($_GET['ct_template'])) {
	$page_builder = 1;
    }
    /* =======Check Cornerstone editor Active======== */ else if (isset($_POST['cs_preview_state'])) {
	$page_builder = 1;
    }
    /* ------Check Visual Composer Active======== */ else if (isset($_GET['vc_editable'])) {
	$page_builder = 1;
    }
    /* ------Check elementor editor Active======== */ else if (defined('ELEMENTOR_VERSION')) {
	if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
	    $page_builder = 1;
	} else {
	    $page_builder = 0;
	}
    } else if (is_customize_preview()) {
	$page_builder = 1;
    } else {
	$page_builder = 0;
    }
    return $page_builder;
}

// Load plugin text domain
add_action('init', 'wpf_load_plugin_textdomain', 10);

/**
 * Load the plugin text domain for translation.
 *
 */
/*
 * This function is used to identify the selected language of the current user and load the translations.
 *
 * @input NULL
 * @return NULL
 */
function wpf_load_plugin_textdomain() {
    $domain = 'wpfeedback';
    if (is_user_logged_in()) {
	$get_locale = get_user_locale($user_id = 0);
    } else {
	$get_locale = get_locale();
    }
    $locale = apply_filters('plugin_locale', $get_locale, $domain);
    load_textdomain($domain, trailingslashit(WPF_PLUGIN_DIR . '/languages/') . $domain . '-' . $locale . '.mo');
    load_plugin_textdomain($domain, FALSE, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
}

/*
 * This function is used to register the template for the graphics post.
 *
 * @input String
 * @return String
 */

//function wpf_graphics_post_template($page_template) {
//    global $post;
//    if (isset($post->post_type)) {
//	if ('wpf_graphics' === $post->post_type) {
//	    $page_template = dirname(__FILE__) . '/graphics/wpf_graphics.php';
//	}
//    }
//    return $page_template;
//}
//
//add_filter('template_include', 'wpf_graphics_post_template', 9999);

/*
 * This function is used to identify if certain themes/plugins are so that jquery UI can be de registered.
 *
 * @input NULL
 * @return Boolean
 */

function wpf_remove_ui_script() {
    $response = 1;
    if (function_exists('get_field')) {
	$response = 0;
    }
    if (class_exists('PMXI_Plugin')) {
	$response = 0;
    }
    if (class_exists('Avada')) {
	$response = 0;
    }
    if (class_exists('WooCommerce')) {
	$response = 0;
    }
    if (class_exists('The7_Less_Functions')) {
	$response = 0;
    }
    if (class_exists('iHomefinderAutoloader')) {
	$response = 0;
    }
    return $response;
}

// Commented to sort ticket #3051510 to allow elementor adding the admin bar button
// remove_action( 'wp_body_open', 'wp_admin_bar_render', 0 );

/*
 * function is used to get last task no
 */
function get_last_task_id($returnBubbleId=false) {
    $url = WPF_CRM_API . 'wp-api/site/taskCount';

    $sendarr = array();
    $sendarr["wpf_site_id"] = get_option('wpf_site_id');
    $sendtocloud = json_encode($sendarr);
    $response = wpf_send_remote_post($url, $sendtocloud);
    $last_id = 1;
    $bubble_id = 1;
    if(isset($response['data'])) {
	$last_id = $response['data'] + 1;
	$bubble_id = $response['sitetaskid'] + 1;
    }
	if($returnBubbleId==true){
		$res=array();
		$res['Dbid']=$last_id;
		$res['Bubbleid']=$bubble_id;
		return $res;
	}
    return $last_id;
}


/*
 * function is used to get site settings data
 * and stored in session
 */

function get_site_data() {

    /*if (!session_id()) {
	session_start();
    }*/
	$ret=0;
    if(!is_user_logged_in())
        {
    		if(!get_option('enabled_wpfeedback')=='yes'){
    			$ret=1;
    		}else{
    			if(!get_option('wpf_allow_guest')=='yes'){
    				$ret=1;
    			} else{
    				$ret = 0;
    			}
    		}
    	}

    	if($ret==1){
    		return;
    	}

    if (defined('DOING_AJAX') && DOING_AJAX) { /* it's an Ajax call */
    } elseif(get_option('wpf_license') != 'valid') {
    	//clear_session();
	} else {
       $wpf_site_id = get_option('wpf_site_id');
       $args = array(
	   'wpf_site_id' => $wpf_site_id
       );
       $url = WPF_CRM_API.'get-site-data';
       $sendtocloud = json_encode($args);
       $res_data = wpf_send_remote_post($url,$sendtocloud);
//       re($res_data);
       if(isset($res_data['status']) && $res_data['status'] == '200' && isset($res_data['data'])){
	   $site_data = $res_data['data'];

        foreach ( $site_data as $key=>$sdata ) {
            if ( ($sdata==0 || !empty($sdata)) && ($key !='wpf_license' )) {
                update_option($key, $sdata, 'no');
            }
        }

	   //$_SESSION['site_'.$wpf_site_id]['site_data'] = $site_data;
       } else {
	   //if(isset($_SESSION['site_'.$wpf_site_id]["site_data"])){
	       //unset($_SESSION['site_'.$wpf_site_id]["site_data"]);
	   //}
       }
    }
}

/*
 * function is used to get site notify user
 * and stored in session
 */
function get_notify_users() {

    /*if (!session_id()) {
	session_start();
    }*/
	$ret=0;
    if(!is_user_logged_in())
        {
    		if(!get_option('enabled_wpfeedback')=='yes'){
    			$ret=1;
    		}else{
    			if(!get_option('wpf_allow_guest')=='yes'){
    				$ret=1;
    			} else{
    				$ret = 0;
    			}
    		}
    	}

    	if($ret==1){
    		return;
    	}

    if (defined('DOING_AJAX') && DOING_AJAX) { /* it's an Ajax call */
    } elseif(get_option('wpf_license') != 'valid') {
    	//clear_session();
    } else {

	$wpf_site_id = get_option('wpf_site_id');
        $args = array(
	    'wpf_site_id' => $wpf_site_id
        );

        $url = WPF_CRM_API.'wp-api/wpfuser/getNotifiedUsers';
        $sendtocloud = json_encode($args);
        $filterData = wpf_send_remote_post($url,$sendtocloud);

        if(isset($filterData['status']) && $filterData['status'] == '200'){
            $notify_users = $filterData['data'];

            /*if(isset($_SESSION['site_'.$wpf_site_id]["notify_users"])){
                unset($_SESSION['site_'.$wpf_site_id]["notify_users"]);
            }*/

            //~
            if ( !empty($notify_users) )
			    update_option('notify_users',$notify_users,"no");
            else
                update_option('notify_users', '',"no");

            //$_SESSION['site_'.$wpf_site_id]['notify_users'] = $notify_users;
        }else{
            /*if(isset($_SESSION['site_'.$wpf_site_id]["notify_users"])){
                unset($_SESSION['site_'.$wpf_site_id]["notify_users"]);
            }*/
        }
    }
}

/* function is used to get notify user, site data, filter data
combination of 3 CURL requests into one:-
get-wp-filter-data

get-site-data

wp-api/wpfuser/getNotifiedUsers
*/
function get_notif_sitedata_filterdata() {

    /*if (!session_id()) {
	session_start();
    }*/
	$ret=0;
    if(!is_user_logged_in())
        {
    		if(!get_option('enabled_wpfeedback')=='yes'){
    			$ret=1;
    		}else{
    			if(!get_option('wpf_allow_guest')=='yes'){
    				$ret=1;
    			} else{
    				$ret = 0;
    			}
    		}
    	}

    	if($ret==1){
    		return;
    	}

    if(get_option('wpf_license') != 'valid') {
    	//clear_session();
    } else {

	$wpf_site_id = get_option('wpf_site_id');
        $args = array(
	    'wpf_site_id' => $wpf_site_id
        );

        $url = WPF_CRM_API.'wp-api/site/get-meta-data';
        $sendtocloud = json_encode($args);
        $allData = wpf_send_remote_post($url,$sendtocloud);

//         echo json_encode($allData); die;


        if(isset($allData['status']) && $allData['status'] == '200'){
            $notify_users = $allData['data']['getNotifiedUsers']['data'];
            $res_data = $allData['data']['get-site-data'];
            $fil_data = $allData['data']['wp-filter-data'];
            $restrict_plugin = $allData['data']['limit'];
            $wpf_user_plan = $allData['data']['plan'];

            if ( !empty($notify_users) ) {
                update_option('notify_users', $notify_users, "no");
            }

			//
            if ( !empty($fil_data['data']) ) {
                update_option('filter_data', $fil_data['data'], 'no');
            }

			// update the limit
            update_option('restrict_plugin', $restrict_plugin, 'no');

			if(isset($res_data['status']) && $res_data['status'] == '200' && isset($res_data['data'])){
				$site_data = $res_data['data'];

                /* ---- UPDATE BY SHAWN ON VERSION 2.0.9 ---- */
                foreach ( $site_data as $key=>$sdata ) {
                    if ( ($sdata==0 || !empty($sdata)) && ($key !='wpf_license' )) {
                        update_option($key, $sdata, 'no');
                    }
                }

                // override old data by checking the old API URL => 2.1.1
                if ( !empty($site_data) ) {
                    $pattern = '/api.wpfeedback.co/';

                    // check for the logo
                    if (preg_match($pattern, $site_data['wpfeedback_logo']) != false) {
                        update_option('wpfeedback_logo', 'https://api.atarim.io/Atarim.svg', 'no');
                    }

                    // check for the fav icon
                    if (preg_match($pattern, $site_data['wpfeedback_favicon']) != false) {
                        update_option('wpfeedback_favicon', 'https://api.atarim.io/atarim_icon.svg', 'no');
                    }
                }

                // add the site archive settings
                if ( !empty($allData['site_archived']) && $allData['site_archived']!==0 ) {
                    update_option('wpf_site_archived', 0, 'no');
                } elseif ( !empty($allData['site_archived']) ) {
                    update_option('wpf_site_archived', 0, 'no');
                }
			}

            // update the plan data
            update_option( 'wpf_user_plan', serialize( $wpf_user_plan ), 'no' );
        }
    }
}

/*
 * function is used to get site settings data
 * and stored in session
 */

function get_user_data() {

    if (!session_id()) {
	session_start();
    }

    if (defined('DOING_AJAX') && DOING_AJAX) { /* it's an Ajax call */
    } elseif(get_option('wpf_license') != 'valid') {
    	//clear_session();
	} else {
	$wpf_site_id = get_option('wpf_site_id');

	if (is_user_logged_in()) {
	    $userid = get_current_user_id();
	    $args = array(
		'wpf_site_id' => $wpf_site_id,
		'wpf_user_id' => $userid
	    );
	    $url = WPF_CRM_API . 'wp-api/wpfuser/getWpfUser';
	    $sendtocloud = json_encode($args);
	    $res_data = wpf_send_remote_post($url, $sendtocloud);
	    if (isset($res_data['status']) && $res_data['status'] == '200' && isset($res_data['data'])) {
		$user_data = $res_data['data'];

		if (isset($_SESSION['site_'.$wpf_site_id]["user_data"])) {
		    unset($_SESSION['site_'.$wpf_site_id]["user_data"]);
		}

		$_SESSION['site_'.$wpf_site_id]['user_data'] = $user_data;
	    } else {
		if (isset($_SESSION['site_'.$wpf_site_id]["user_data"])) {
		    unset($_SESSION['site_'.$wpf_site_id]["user_data"]);
		}
	    }
	} else {
	    if (isset($_SESSION['site_'.$wpf_site_id]["user_data"])) {
		unset($_SESSION['site_'.$wpf_site_id]["user_data"]);
	    }
	}
    }
}

/*
 * function is used to get site filter
 * data and store in session
 */

function get_site_filter_data() {

    /*if (!session_id()) {
	session_start();
    }*/
	$ret=0;
    if(!is_user_logged_in())
        {
    		if(!get_option('enabled_wpfeedback')=='yes'){
    			$ret=1;
    		}else{
    			if(!get_option('wpf_allow_guest')=='yes'){
    				$ret=1;
    			} else{
    				$ret = 0;
    			}
    		}
    	}

    	if($ret==1){
    		return;
    	}

    if (defined('DOING_AJAX') && DOING_AJAX) { /* it's an Ajax call */
    } elseif(get_option('wpf_license') != 'valid') {
    	//$wpf_site_id = get_option('wpf_site_id');
    	//unset($_SESSION['site_'.$wpf_site_id]["filter_data"]);
	} else {

	$wpf_site_id = get_option('wpf_site_id');
	$url = WPF_CRM_API . 'wp-api/site/get-wp-filter-data';
	$sendarr = array();
	$sendarr["wpf_site_id"] = $wpf_site_id;
	$sendtocloud = json_encode($sendarr);
	$response = wpf_send_remote_post($url, $sendtocloud);
//	re($response);
	if(isset($response['status']) && $response['status'] == '1') {
	    /*if(isset($_SESSION['site_'.$wpf_site_id]["filter_data"])){
                unset($_SESSION['site_'.$wpf_site_id]["filter_data"]);
            }*/
			update_option('filter_data',$response['data'],'no');

	    //$_SESSION['site_'.$wpf_site_id]['filter_data'] = $response['data'];
	} else {
	    /*if(isset($_SESSION['site_'.$wpf_site_id]["filter_data"])){
                unset($_SESSION['site_'.$wpf_site_id]["filter_data"]);
            }*/
	}
    }
}

/*
 * function is used to clear the session
 */
function clear_session() {
    $wpf_site_id = get_option('wpf_site_id');
    if (isset($_SESSION['site_' . $wpf_site_id]["site_data"])) {
	unset($_SESSION['site_' . $wpf_site_id]["site_data"]);
    }

    if (isset($_SESSION['site_' . $wpf_site_id]["notify_users"])) {
	unset($_SESSION['site_' . $wpf_site_id]["notify_users"]);
    }

    if (isset($_SESSION['site_' . $wpf_site_id]["user_data"])) {
	unset($_SESSION['site_' . $wpf_site_id]["user_data"]);
    }
}

/*
 * function is used to get site settings data by key
 */

function get_site_data_by_key($key) {
    /*if (!session_id()) {
        session_start();
    }
    $wpf_site_id = get_option('wpf_site_id');*/

    $str = '';
    /*if(isset($_SESSION['site_'.$wpf_site_id]["site_data"][$key])){
        $str = $_SESSION['site_'.$wpf_site_id]["site_data"][$key];
    }*/
	$str=get_option($key);

//	echo $key . "=" . $str . '<br>';

    return $str;
}

function get_site_data_by_key_api($option_name) {
    $args = array(
	'wpf_site_id' => get_option('wpf_site_id'),
	'option_name' => $option_name
    );
    $url = WPF_CRM_API.'get-site-data-by-key';
    $sendtocloud = json_encode($args);
    $res_data = wpf_send_remote_post($url,$sendtocloud);
    $str = '';
    if($res_data['status'] == '200' && isset($res_data['data'])){
	$str = $res_data['data'];
    }
    return $str;
}
//get_site_data_by_key_api('wpf_tab_permission_user_client');
/*
 * function is used to update site settings data
 */

function update_site_data($options) {

    $args = array(
        'wpf_site_id' => get_option('wpf_site_id'),
        'options' => $options
	);
    $url = WPF_CRM_API.'update-site-data';
    $sendtocloud = json_encode($args);
    $myposts = wpf_send_remote_post($url,$sendtocloud);
    if($myposts['status'] == 200){
		get_notif_sitedata_filterdata();
        return 1;
    }else{
        return 0;
    }
}

/*
 * register new route for graphic page
 */
add_action('init', 'wpf_graphic_add_rewrite_rule');
function wpf_graphic_add_rewrite_rule(){
    add_rewrite_rule('^collaborate/graphic?','index.php?is_graphic_page=1','top');
    flush_rewrite_rules();
}

add_action('query_vars','wpf_graphic_set_query_var');
function wpf_graphic_set_query_var($vars) {
    array_push($vars, 'is_graphic_page');
    return $vars;
}

add_filter('template_include', 'wpf_graphic_include_template', 1000, 1);
function wpf_graphic_include_template($template){
    if(get_query_var('is_graphic_page')){
	$new_template = WPF_PLUGIN_DIR.'/graphics/wpf_graphics.php';
        if(file_exists($new_template))
            $template = $new_template;
    }
    return $template;
}

function get_task_time_type($date) {
    $current = strtotime(date('Y-m-d'));
    $datediff = $date - $current;
    $difference = floor($datediff / (60 * 60 * 24));
    if ($difference == 0) {
	return 'today';
    } else if ($difference > 1) {
		return 'Future Date';
    } else if ($difference > 0) {
		return 'tomorrow';
    } else if ($difference < -1) {
		return 'Long Back';
    } else {
		return 'yesterday';
    }
}


function formatTimeString($time) {
    //$str_time = date("Y-m-d H:i:sP", $timeStamp);
    //$time = strtotime($str_time);
    //return date('Y-m-d H:i:s', $time);
    $monday = strtotime("last monday");
    $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
    $sunday = strtotime(date("Y-m-d 23:59:59",$monday)." +6 days");
    $this_week_sd = strtotime(date("Y-m-d 00:00:01",$monday)." -7 days");
    $this_week_ed = strtotime(date("Y-m-d 23:59:59",$sunday))." -7 days";

    $number_of_days_in_this_month = date('t');
    $first_day_this_month = strtotime(date('Y-m-01 00:00:01')." -${number_of_days_in_this_month} days"); // hard-coded '01' for first day
    $last_day_this_month  = strtotime(date('Y-m-t 23:59:59')." -${number_of_days_in_this_month} days");

    //return $time;
    $first_day_of_year = strtotime('first day of january this year');
    $last_day_of_year = strtotime('last day of december this year');

    if ($time >= strtotime(date('Y-m-d 00:00:01')) && $time <= strtotime(date('Y-m-d 23:59:59'))) {
        return "today";
    }


    if ($time >= strtotime(date("Y-m-d 00:00:01")." -1 days") && $time <= strtotime(date('Y-m-d 23:59:59')." -1 days")) {
        return 'yesterday';
    }

    // echo $time . ', ' . $this_week_sd . ',' . $this_week_ed;  die;

    if ($time >= $this_week_sd && $time <= $this_week_ed) {
        return "this_week";
    }

    if($time >= $first_day_this_month && $time <= $last_day_this_month) {
        return "this_month";
    }

    if($time >= $first_day_of_year && $time <= $last_day_of_year) {
        return "year";
    }

    return "other";

}

/*
 * Move the site data to wpf when
 * plugin 2.0 updated
 */
 //add_action('init', 'move_all_old_data_api_call');
 function move_all_old_data_api_call(){
    if(isset($_GET['move_wpf_data']) && $_GET['move_wpf_data'] == 1) {

//	$is_wpf_data_moved = get_option('is_wpf_data_moved');
//	if($is_wpf_data_moved != 'yes') {
	    //do_action('wpf_move_all_data');
//	}
	echo "Data moved!!";
	exit;
    }
 }

/*add_action('upgrader_process_complete', function( $upgrader_object, $options ) {

    // The path to our plugin's main file
    $our_plugin = plugin_basename(__FILE__);
    // If an update has taken place and the updated type is plugins and the plugins element exists
    if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset($options['plugins']) && !empty($options['plugins'])) {
	// Iterate through the plugins being updated and check if ours is there
	foreach ($options['plugins'] as $plugin) {
	    if ($plugin == $our_plugin) {
		$is_wpf_data_moved = get_option('is_wpf_data_moved');
		if($is_wpf_data_moved != 'yes') {
		    do_action('wpf_move_all_data');
		}
	    }
	}
    }
}, 10, 2);*/

function prefix_plugin_update_message( $data, $response ) {
	if( isset( $data['upgrade_notice'] ) ) {
		printf(
			'<div class="update-message">%s</div>',
			wpautop( $data['upgrade_notice'] )
		);
	}
}
add_action( 'atarim-client-interface-plugin/wpfeedback.php', 'prefix_plugin_update_message', 10, 2 );

function prefix_plugin_update_message_fallback( $data, $response ) {
	if(!function_exists('prefix_plugin_update_message')){
		printf(
			'<div class="update-message"><p><strong>%s</strong></p></div>',
			__( 'Version 2.0 is a major update. You should take a backup before you upgrade.', 'text-domain' )
		);
	}
}
add_action( 'atarim-client-interface-plugin/wpfeedback.php', 'prefix_plugin_update_message_fallback', 10, 2 );

/*
 * This function is used to register the taxonomy "Task Status" on the website where it is installed.
 *
 * @input NULL
 * @return NULL
 */
if (!function_exists('wp_feedback_task_status_taxonomy')) {

	// Register Task status Custom Taxonomy
		function wp_feedback_task_status_taxonomy()
		{
	
			$labels = array(
				'name' => _x('Task status', 'Taxonomy General Name', 'wp_feedback'),
				'singular_name' => _x('Task status', 'Taxonomy Singular Name', 'wp_feedback'),
				'menu_name' => __('Task status', 'wp_feedback'),
				'all_items' => __('All Task status', 'wp_feedback'),
				'parent_item' => __('Parent Item', 'wp_feedback'),
				'parent_item_colon' => __('Parent Item:', 'wp_feedback'),
				'new_item_name' => __('New Task status', 'wp_feedback'),
				'add_new_item' => __('New Task status', 'wp_feedback'),
				'edit_item' => __('Edit Task status', 'wp_feedback'),
				'update_item' => __('Update Task status', 'wp_feedback'),
				'view_item' => __('View Task status', 'wp_feedback'),
				'separate_items_with_commas' => __('Separate items with commas', 'wp_feedback'),
				'add_or_remove_items' => __('Add or remove Task status', 'wp_feedback'),
				'choose_from_most_used' => __('Choose from the most used', 'wp_feedback'),
				'popular_items' => __('Popular Task status', 'wp_feedback'),
				'search_items' => __('Search Task status', 'wp_feedback'),
				'not_found' => __('Not Found Task status', 'wp_feedback'),
				'no_terms' => __('No Task status', 'wp_feedback'),
				'items_list' => __('Task status list', 'wp_feedback'),
				'items_list_navigation' => __('Task status list navigation', 'wp_feedback'),
			);
			$args = array(
				'labels' => $labels,
				'hierarchical' => true,
				'public' => false,
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => false,
				'show_tagcloud' => true,
			);
			register_taxonomy('task_status', array('wpfeedback'), $args);
	
		}
	
		add_action('init', 'wp_feedback_task_status_taxonomy', 0);
	
	}
	
	/*
	 * This function is used to register the taxonomy "Task Urgency" on the website where it is installed.
	 *
	 * @input NULL
	 * @return NULL
	 */
	if (!function_exists('wp_feedback_task_priority_taxonomy')) {
	
	// Register Task urgency Custom Taxonomy
		function wp_feedback_task_priority_taxonomy()
		{
	
			$labels = array(
				'name' => _x('Task urgency', 'Taxonomy General Name', 'wp_feedback'),
				'singular_name' => _x('Task urgency', 'Taxonomy Singular Name', 'wp_feedback'),
				'menu_name' => __('Task urgency', 'wp_feedback'),
				'all_items' => __('All Task urgency', 'wp_feedback'),
				'parent_item' => __('Parent Item', 'wp_feedback'),
				'parent_item_colon' => __('Parent Item:', 'wp_feedback'),
				'new_item_name' => __('New Task urgency', 'wp_feedback'),
				'add_new_item' => __('New Task urgency', 'wp_feedback'),
				'edit_item' => __('Edit Task urgency', 'wp_feedback'),
				'update_item' => __('Update Task urgency', 'wp_feedback'),
				'view_item' => __('View Task urgency', 'wp_feedback'),
				'separate_items_with_commas' => __('Separate items with commas', 'wp_feedback'),
				'add_or_remove_items' => __('Add or remove Task urgency', 'wp_feedback'),
				'choose_from_most_used' => __('Choose from the most used', 'wp_feedback'),
				'popular_items' => __('Popular Task urgency', 'wp_feedback'),
				'search_items' => __('Search Task urgency', 'wp_feedback'),
				'not_found' => __('Not Found Task urgency', 'wp_feedback'),
				'no_terms' => __('No Task urgency', 'wp_feedback'),
				'items_list' => __('Task urgency list', 'wp_feedback'),
				'items_list_navigation' => __('Task urgency list navigation', 'wp_feedback'),
			);
			$args = array(
				'labels' => $labels,
				'hierarchical' => true,
				'public' => false,
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => false,
				'show_tagcloud' => true,
			);
			register_taxonomy('task_priority', array('wpfeedback'), $args);
	
		}
	
		add_action('init', 'wp_feedback_task_priority_taxonomy', 0);
	
	}
	
	/*
	 * This function is used to register the terms for taxonomy "Task Priority" on the website where it is installed.
	 *
	 * @input NULL
	 * @return NULL
	 */
	if (!function_exists('wp_feedback_register_task_priority_terms')) {
		function wp_feedback_register_task_priority_terms()
		{
			$taxonomy = 'task_priority';
			$terms = array(
				'0' => array(
					'name' => 'Low',
					'slug' => 'low',
					'description' => '',
				),
				'1' => array(
					'name' => 'Medium',
					'slug' => 'medium',
					'description' => '',
				),
				'2' => array(
					'name' => 'High',
					'slug' => 'high',
					'description' => '',
				),
				'3' => array(
					'name' => 'Critical',
					'slug' => 'critical',
					'description' => '',
				),
			);
	
			foreach ($terms as $term_key => $term) {
				if (!term_exists($term['slug'], 'task_priority')) {
					wp_insert_term(
						$term['name'],
						$taxonomy,
						array(
							'description' => $term['description'],
							'slug' => $term['slug'],
						)
					);
				}
				//unset( $term );
			}
	
		}
	}
	add_action('wp_loaded', 'wp_feedback_register_task_priority_terms', 0);
	
	/*
	 * This function is used to register the terms for taxonomy "Task Status" on the website where it is installed.
	 *
	 * @input NULL
	 * @return NULL
	 */
	if (!function_exists('wp_feedback_register_task_status_terms')) {
		function wp_feedback_register_task_status_terms()
		{
			$taxonomy = 'task_status';
			$terms = array(
				'0' => array(
					'name' => 'Open',
					'slug' => 'open',
					'description' => '',
				),
				'1' => array(
					'name' => 'In Progress',
					'slug' => 'in-progress',
					'description' => '',
				),
				'2' => array(
					'name' => 'Pending Review',
					'slug' => 'pending-review',
					'description' => '',
				),
				'3' => array(
					'name' => 'Complete',
					'slug' => 'complete',
					'description' => '',
				),
			);
	
			foreach ($terms as $term_key => $term) {
				if (!term_exists($term['slug'], 'task_status')) {
					wp_insert_term(
						$term['name'],
						$taxonomy,
						array(
							'description' => $term['description'],
							'slug' => $term['slug'],
						)
					);
				}
				//unset( $term );
			}
	
		}
	}
	add_action('wp_loaded', 'wp_feedback_register_task_status_terms', 0);
	/*
	 * This function is used to show notice if the license is not active.
	 *
	 * @input NULL
	 * @return NULL
	 */
	/*
	 * This function is used to show notice if the license is not active.
	 *
	 * @input NULL
	 * @return NULL
	 */
	function licence_invalid_notice(){
		if(get_option('wpf_license')!='valid' && (wpf_user_type() === 'advisor')){
		 echo '<div class="notice notice-warning wpf_admin_notice">
			 <div class="wpf_admin_notice_icon"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080">
<defs>
<style>
  .cls-1 {
	fill: #fff;
  }

  .cls-2 {
	fill: #052055;
  }
</style>
</defs>
<title>Atarim Logo Inverted</title>
<g>
<g>
  <polygon class="cls-1" points="937.344 785.955 746.1 856.215 851.972 1060.257 1080 1059.991 937.344 785.955"/>
  <polygon class="cls-1" points="539.938 19.669 0 1059.991 228.152 1059.991 539.873 458.766 652.263 675.369 843.507 605.108 539.938 19.669"/>
</g>
<polygon class="cls-2" points="227.659 1060.331 373.967 778.521 1055.074 519.371 227.659 1060.331"/>
</g>
</svg></div>
			 <div class="wpf_admin_notice_content"><div class="wpf_admin_notice_title">Welcome to Atarim 👋</div>
			 Please activate your license to continue using the platform.
			 <p class="admin_notice_footer"><i>* This notice is shown to you as the Webmaster.</i></p>
			 </div>
			 <div class="wpf_admin_notice_button_col"><a class="wpf_admin_notice_button" href="'. admin_url() .'admin.php?page=wpfeedback_page_permissions"><span class="dashicons dashicons dashicons-update"></span> Activate & Connect</a></div>
		 </div>';
		}
}
add_action('admin_notices', 'licence_invalid_notice');


/**
 * This notice will show on the admin when wpf_site_archived = 1 on the wp_options table
 */
function site_archived_notice()
{
    ?>
    <?php if ( get_site_data_by_key('wpf_site_archived') && (wpf_user_type() === 'advisor') ): ?>
        <div class="notice notice-warning wpf_admin_notice">
            <div class="wpf_admin_notice_icon"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080">
                    <defs>
                        <style>
                            .cls-1 {
                                fill: #fff;
                            }

                            .cls-2 {
                                fill: #052055;
                            }
                        </style>
                    </defs>
                    <title>Atarim Logo Inverted</title>
                    <g>
                        <g>
                            <polygon class="cls-1" points="937.344 785.955 746.1 856.215 851.972 1060.257 1080 1059.991 937.344 785.955"/>
                            <polygon class="cls-1" points="539.938 19.669 0 1059.991 228.152 1059.991 539.873 458.766 652.263 675.369 843.507 605.108 539.938 19.669"/>
                        </g>
                        <polygon class="cls-2" points="227.659 1060.331 373.967 778.521 1055.074 519.371 227.659 1060.331"/>
                    </g>
                </svg>
            </div>
            <div class="wpf_admin_notice_content">
                Atarim is disabled because this website has been archived on the Agency Dashboard. To re-enable the plugin,
                please go to the <a href="https://app.atarim.io/websites" target=_blank >Websites</a> screen in your Agency
                Dashboard and <strong>unarchive this website</strong>
                <p class="admin_notice_footer"><i>* This notice is shown to you as the Webmaster.</i></p>
            </div>
        </div>
    <?php endif; ?>
    <?php
}
add_action('admin_notices', 'site_archived_notice');