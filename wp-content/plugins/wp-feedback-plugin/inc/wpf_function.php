<?php
/*
 * wpf_functions.php
 * This file contains the helper functions called from across the plugin.
 */

/*
 * This function is used to get the checkbox of task status / priority from the terms on website.
 *
 * @input String
 * @return String
 */
if (!function_exists('wp_feedback_get_texonomy')) {
    function wp_feedback_get_texonomy($my_term)
    {
	
	/*if (!session_id()) {
	    session_start();
	}
	
	$wpf_site_id = get_option('wpf_site_id');*/
    $filter_data_db=get_option('filter_data')[$my_term];
	
	if(isset($filter_data_db) && !empty($filter_data_db)) {
	    echo '<ul class="wp_feedback_filter_checkbox">';
            foreach ($filter_data_db as $term) {
                if($term['label']=='In Progress'){
                    $term['label'] = 'In Prog';
                }elseif ($term['label']=='Pending Review'){
                    $term['label'] = 'Pending';
                }else{
                    $term['label'] = $term['label'];
                }
                echo '<li><input onclick="wp_feedback_filter()" type="checkbox" name="' . $my_term . '" value="' . $term['value'] . '" class="wp_feedback_task wpf_checkbox"  id="' . $term['value'] . '"/><label for="' . $term['value'] . '" class="wpf_checkbox_label">' . __($term['label'], 'wpfeedback') . '</label></li>';
            }
            echo '</ul>';
	}
    }
}

/*
 * This function is used to get all the roles allowed to use Atarim features.
 *
 * @input NULL
 * @return String
 */
add_shortcode('wpf_user_role_list','wp_feedback_get_user_role_list');
if (!function_exists('wp_feedback_get_user_role_list')) {
    function wp_feedback_get_user_role_list()
    {
        $editable_roles = get_editable_roles();
        return $editable_roles;
    }
}

/*
 * This function is used to get all the users (based on role) which are supposed to get notified. This is called in the Tasks Center for "Filters" section.
 *
 * @input NULL
 * @return String
 */
add_shortcode('wpf_user_list','wp_feedback_get_user_list');
if (!function_exists('wp_feedback_get_user_list')) {
    function wp_feedback_get_user_list()
    {
        /*if (!session_id()) {
            session_start();
        }*/

        //$wpf_site_id = get_option('wpf_site_id');
        $get_notif_user_db=get_option('notify_users');
        $notify_users = isset($get_notif_user_db) ? $get_notif_user_db : [];

        if ( is_array($notify_users) ) {
            if (count($notify_users) > 0) {
                echo '<ul class="wp_feedback_filter_checkbox user">';
                if (!empty($notify_users)) {
                    foreach ($notify_users as $user) {
                        $wpfusr = get_user_by('login', htmlspecialchars($user['label'], ENT_QUOTES, 'UTF-8'));
                        $wpfusrname = $wpfusr->display_name;
                        echo '<li><input onclick="wp_feedback_filter()"  type="checkbox" name="author_list" value="' . $user['value'] . '" class="wp_feedback_task wpf_checkbox" data-wp-username="' . $user['label'] . '"  id="user_' . $user['value'] . '" /><label for="user_' . $user['value'] . '" class="wpf_checkbox_label">' . $wpfusrname . '</label></li>';
                    }
                }
                echo '</ul>';
            }
        }
    }
}

/*
 * This function is used to get all the users (based on role) which are supposed to get notified. This is called in the Tasks Center for the "Notify Users" section.
 *
 * @input NULL
 * @return String
 */
add_shortcode('wpf_user_list_task','wp_feedback_get_user_list_task');
if (!function_exists('wp_feedback_get_user_list_task')) {
    function wp_feedback_get_user_list_task()
    {

        /*if (!session_id()) {
            session_start();
        }*/

        //$wpf_site_id = get_option('wpf_site_id');
        $get_notif_user_db=get_option('notify_users');
        $notify_users = isset($get_notif_user_db) ? $get_notif_user_db : [];

        if ( is_array($notify_users) ) {
            if (count($notify_users) > 0) {
                echo '<ul class="wp_feedback_filter_checkbox user">';
                if (!empty($notify_users)) {
                    foreach ($notify_users as $user) {
                        $wpfusr = get_user_by('login', htmlspecialchars($user['label'], ENT_QUOTES, 'UTF-8'));
                        $wpfusr_meta = get_user_meta( $wpfusr->ID );
                        $user_name = $wpfusr->display_name;
                        echo '<li><input type="checkbox" name="author_list_task" value="' . $user['value'] . '" class="wp_feedback_task wpf_checkbox" data-wp-username="' . $user['label'] . '" id="' . $user['value'] . '" onclick="update_notify_user(' . $user['value'] . ')" /><label for="' . $user['value'] . '" class="wpf_checkbox_label">' . $user_name . '</label></li>'; //!push
                        // echo '<li><input type="checkbox" name="author_list_task" value="' . $user['value'] . '" class="wp_feedback_task wpf_checkbox" data-wp-username="' . $user['label'] . '" id="' . $user['value'] . '" onclick="update_notify_user(' . $user['value'] . ')" /><label for="' . $user['value'] . '" class="wpf_checkbox_label">' . $wpfusr->display_name . '</label></li>'; //!push
                    }
                }
                echo '</ul>';
            }
        }
    }
}

/*
 * This function is used to get all the users (based on role) which are supposed to get notified. This is called in the frontend on the Tasks Popup.
 *
 * @input NULL
 * @return String
 */
add_shortcode('wpf_user_list_front','wp_feedback_get_user_list_front');
if (!function_exists('wp_feedback_get_user_list_front')) {
    function wp_feedback_get_user_list_front()
    {
        /*if (!session_id()) {
            session_start();
        }*/
	$response = [];
        //$wpf_site_id = get_option('wpf_site_id');
        $get_notif_user_db=get_option('notify_users');
        $notify_users = isset($get_notif_user_db) ? $get_notif_user_db : [];

        if ( is_array($notify_users) ) {
            if (count($notify_users) > 0) {
                if (!empty($notify_users)) {
                    foreach ($notify_users as $user) {
                        $userdetails = get_user_by('login', htmlspecialchars($user['label'], ENT_QUOTES, 'UTF-8'));
                        // print_r($userdetails);
                        $response[$user['value']] = array(
                            "id" => htmlspecialchars($userdetails->ID, ENT_QUOTES, 'UTF-8'), 
                            "username" => htmlspecialchars($user['label'], ENT_QUOTES, 'UTF-8'), 
                            "displayname" => htmlspecialchars($userdetails->display_name, ENT_QUOTES, 'UTF-8'), 
                            "first_name" => htmlspecialchars($userdetails->first_name, ENT_QUOTES, 'UTF-8'), 
                            "last_name" => htmlspecialchars($userdetails->last_name, ENT_QUOTES, 'UTF-8')
                        );
                    }
                }
            }
        }
        return json_encode($response);
    }
}
/*
 * This function is used to get emails of the users to be notified.
 *
 * @input String
 * @return JSON
 */
if (!function_exists('get_notify_users_emails')) {
    function get_notify_users_emails($id)
    {
        $send_to_arr = explode(',', $id);
        $current_user_id = get_current_user_id();
        if (($key = array_search($current_user_id, $send_to_arr)) !== false) {
            unset($send_to_arr[$key]);
        }
        $nofity_users=array();
        foreach($send_to_arr as $k){
            $user = get_user_by( 'id', $k );
            $nofity_users[]= $user->user_email;
        }
        $response=implode($nofity_users,",");

        return $response;
    }
}

/*
 * This function is used to verify if the license on the website is valid or not.
 *
 * @input String
 * @return JSON
 */
if (!function_exists('wpf_license_key_check_item')) {
    function wpf_license_key_check_item($wpf_license_key)
    {

        if(!get_option('wpf_decr_key')){
            $wpf_license_key=$wpf_license_key;
        }
        else{
            $wpf_license_key=wpf_crypt_key($wpf_license_key,'d');
        }
        $site_url = WPF_SITE_URL;
        $url = WPF_EDD_SL_STORE_URL."?edd_action=check_license&item_id=" . WPF_EDD_SL_ITEM_ID . "&license=$wpf_license_key&url=$site_url";
        $args = array(
            'timeout' => 20,
            'sslverify' => false
        );
        $output = wp_remote_get($url,$args);
        if ( !is_wp_error($output)){
        if($output['response']['code']!=200){
            $url = WPF_EDD_FALLBACK_URL."license.php?edd_action=check_license&item_id=" . WPF_EDD_SL_ITEM_ID . "&license=$wpf_license_key&url=$site_url";
            $output = wp_remote_get($url,$args);
        }
        $outputObject = json_decode($output['body']);
        if ($outputObject->license == 'valid') {
            if(isset($outputObject->wpf_site_id)){
                update_option('wpf_site_id',$outputObject->wpf_site_id);
            }
            $response = array('license' => $outputObject->license, 'expires' => $outputObject->expires, 'payment_id' => $outputObject->payment_id, 'checksum' => $outputObject->checksum, 'executed' => 1);
//            if(get_option('wpf_initial_sync')!=1 && get_option('wpf_initial_sync')!=2){
                do_action('wpf_initial_sync',$wpf_license_key);
//            }
        } else {
            $response = array('license' => $outputObject->license, 'expires' => '', 'executed' => 1);
        }
    }else{
        $response = array('license' => 'invalid', 'expires' => '', 'executed' => 1);
    }

        return $response;
    }
}

/*
 * This function is used to activate the license on the website.
 *
 * @input String
 * @return JSON
 */
if (!function_exists('wpf_license_key_license_item')) {
    function wpf_license_key_license_item($wpf_license_key)
    {
        $wpf_license_key_in = $wpf_license_key;
        if(!get_option('wpf_decr_key')){
            $wpf_license_key=$wpf_license_key;
        }
        else{
            $wpf_license_key=wpf_crypt_key($wpf_license_key,'d');
            if($wpf_license_key==''){
                $wpf_license_key = $wpf_license_key_in;
            }
        }
        $site_url = WPF_SITE_URL;
        $url = WPF_EDD_SL_STORE_URL."?edd_action=activate_license&item_id=" . WPF_EDD_SL_ITEM_ID . "&license=$wpf_license_key&url=$site_url";

        $args = array(
            'timeout' => 20,
            'sslverify' => false
        );
        $output = wp_remote_get($url,$args);
        if ( !is_wp_error($output)){
        if($output['response']['code']!=200){
            $url = WPF_EDD_FALLBACK_URL."license.php?edd_action=activate_license&item_id=" . WPF_EDD_SL_ITEM_ID . "&license=$wpf_license_key&url=$site_url";
            $output = wp_remote_get($url,$args);
        }
        $outputObject = json_decode($output['body']);
        if ($outputObject->license == 'valid') {
            update_option('wpf_site_id',$outputObject->wpf_site_id);
            $response = array('license' => $outputObject->license, 'expires' => $outputObject->expires, 'payment_id' => $outputObject->payment_id, 'checksum' => $outputObject->checksum, 'executed' => 1);

        } else {
            $response = array('license' => $outputObject->license, 'expires' => '', 'executed' => 1);
        }
    }else{
        $response = array('license' => 'invalid', 'expires' => '', 'executed' => 1);
    }
        return $response;
    }
}

/*
 * This function is used to save the Settings of the plugin when saved from the "Settings" tab.
 *
 * @input NULL
 * @return Redirect
 */
add_action( 'admin_post_save_wpfeedback_options', 'process_wpfeedback_options' );
//add_action('wp_ajax_site_settings_ajax', 'process_wpfeedback_options');
//add_action('wp_ajax_nopriv_site_settings_ajax', 'process_wpfeedback_options');

if (!function_exists('process_wpfeedback_options')) {
    function process_wpfeedback_options()
    {
	   $options = [];
        
        // Check that user has proper security level
        if (!current_user_can('manage_options'))
            wp_die('Not allowed');
	
	if (!empty($_FILES)) {
            foreach ($_FILES as $key => $file) {
                if (is_array($file)) {
                    $temp_wpf_file_name = $file["name"];
                    $temp_wpf_file_type = $file["type"];
		    // print_r($key);
                    $fname = explode(".", $temp_wpf_file_name);
                    $temp_name = $file['tmp_name'];
                    if(empty($temp_name)){
                        continue;
                    }
                    $data = file_get_contents($temp_name);
            // echo $file['name'];
            // exit();
                    $invalid = 0;
                    if (!in_array($temp_wpf_file_type, array('image/jpeg', 'image/png'))) {
                        $invalid = 1;
                    }

                    if ($invalid == 0) {
                        $base64_image = 'data:' . $temp_wpf_file_type . ';base64,' . base64_encode($data); 
                        if($key == 'wpf_logo_file'){
                            $options['logo']['image'] = $base64_image;
                            $options['logo']['file_name'] = str_replace(' ','_',trim($fname[0]));
                            $options['logo']['type'] = $temp_wpf_file_type;
                        }

                        if($key == 'wpf_favicon_file'){
                            $options['favicon']['image'] = $base64_image;
                            $options['favicon']['file_name'] = str_replace(' ','_',trim($fname[0]));
                            $options['favicon']['type'] = $temp_wpf_file_type;
                        }
                    }
                }
            }
        }

	$options['enabled_wpfeedback'] =  isset($_POST['enabled_wpfeedback']) ? 'yes' : 'no';
	$options['wpf_enabled_compact_mode'] =  isset($_POST['wpf_enabled_compact_mode']) ? 'yes' : 'no'; /* => v2.1.0 */
	$options['wpf_enable_clear_cache'] =  isset($_POST['wpf_enable_clear_cache']) ? 'yes' : 'no';
	$options['delete_data_wpfeedback'] =  isset($_POST['delete_data_wpfeedback']) ? 'yes' : 'no';
	$options['wpf_allow_backend_commenting'] =  isset($_POST['wpf_allow_backend_commenting']) ? 'yes' : 'no';
	$options['wpf_show_front_stikers'] =  isset($_POST['wpf_show_front_stikers']) ? 'yes' : 'no';
	$options['wpf_from_email'] =  $_POST['wpf_from_email'];
    // $options['wpf_from_email_mode'] =  isset($_POST['wpf_from_email_mode']) ? 'yes' : 'no';
	$options['wpfeedback_more_emails'] =  $_POST['wpfeedback_more_emails'];
	$options['wpfeedback_powered_by'] =  isset($_POST['wpfeedback_powered_by']) ? 'yes' : 'no';
	$options['wpfeedback_color'] =  $_POST['wpfeedback_color'];
	$options['wpf_powered_link'] =  $_POST['wpf_powered_link'];
	$options['wpfeedback_powered_by'] =  isset($_POST['wpfeedback_powered_by']) ? 'yes' : 'no';
	$options['wpf_every_new_task'] =  isset($_POST['wpf_every_new_task']) ? 'yes' : 'no';
	$options['wpf_every_new_comment'] =  isset($_POST['wpf_every_new_comment']) ? 'yes' : 'no';
	$options['wpf_every_new_complete'] =  isset($_POST['wpf_every_new_complete']) ? 'yes' : 'no';
	$options['wpf_every_status_change'] =  isset($_POST['wpf_every_status_change']) ? 'yes' : 'no';
	$options['wpf_daily_report'] =  isset($_POST['wpf_daily_report']) ? 'yes' : 'no';
	$options['wpf_weekly_report'] =  isset($_POST['wpf_weekly_report']) ? 'yes' : 'no';
	$options['wpf_auto_daily_report'] =  isset($_POST['wpf_auto_daily_report']) ? 'yes' : 'no';
	$options['wpf_auto_weekly_report'] =  isset($_POST['wpf_auto_weekly_report']) ? 'yes' : 'no';
	$options['wpf_tutorial_video'] = stripcslashes(htmlentities($_POST['wpf_tutorial_video']));
	$options['wpf_site_id'] = get_option('wpf_site_id');

	$parms = [];
	foreach ($options as $key => $value) {
	    array_push($parms, ['name' => $key,'value' => $value]);
	}
	update_site_data($parms);

	$wpf_report_register_types = array();
	if (isset($_POST['wpf_auto_daily_report'])) {
	    $wpf_report_register_types['daily'] = 'yes';
	} else {
	    $wpf_report_register_types['daily'] = 'no';
	}
	if (isset($_POST['wpf_auto_weekly_report'])) {
	    $wpf_report_register_types['weekly'] = 'yes';
	} else {
	    $wpf_report_register_types['weekly'] = 'no';
	}
	wpf_register_auto_reports_cron($wpf_report_register_types);

	/* load site data => v2.1.0  */
        //get_notif_sitedata_filterdata();

        // check_admin_referer( 'wpfeedback' );
        // Redirect the page to the configuration form that was
        wp_redirect(add_query_arg('page', 'wpfeedback_page_settings&wpf_setting=1', admin_url('admin.php')));
        exit;
    }
}
/*
 * This function is used to save the Permissions options of the plugin when saved from the "Permissions" tab.
 *
 * @input Array ( $_POST )
 * @return Redirect
 */
add_action( 'admin_post_save_wpfeedback_misc_options', 'process_wpfeedback_misc_options' );

if (!function_exists('process_wpfeedback_misc_options')) {
    function process_wpfeedback_misc_options()
    {	
        $options = [];
        if(isset($_POST['edd_license_deactivate'])){
            update_option('wpf_license','invalid','no');
        }
        if(isset($_POST)){
            $options['wpf_allow_guest'] =  isset($_POST['wpfeedback_guest_allowed']) ? $_POST['wpfeedback_guest_allowed'] : 'no';
            $options['wpf_disable_for_admin'] =  isset($_POST['wpfeedback_disable_for_admin']) ? $_POST['wpfeedback_disable_for_admin'] : 'no';
            $options['wpf_disable_for_app'] =  isset($_POST['wpfeedback_disable_for_app']) ? $_POST['wpfeedback_disable_for_app'] : 'no';
	    $wpfeedback_selected_roles = '';
            if (isset($_POST['wpfeedback_selcted_role'])) {
                $wpfeedback_selected_roles = implode(',', $_POST['wpfeedback_selcted_role']);
            }else{
                $wpfeedback_selected_roles="administrator";
            }
            update_option('wpf_selcted_role',$wpfeedback_selected_roles,'no');
            $options['wpf_selcted_role'] =  $wpfeedback_selected_roles;

            $options['wpf_customisations_client'] =  $_POST['wpf_customisations_client'];
            $options['wpf_customisations_webmaster'] =  $_POST['wpf_customisations_webmaster'];
            $options['wpf_customisations_others'] =  $_POST['wpf_customisations_others'];

            $options['wpf_website_client'] =  $_POST['wpf_website_client'];
            $options['wpf_website_developer'] =  $_POST['wpf_website_developer'];

            $options['wpf_tab_permission_user_client'] =  isset($_POST['wpf_tab_permission_user_client']) ? $_POST['wpf_tab_permission_user_client'] : 'no';
            $options['wpf_tab_permission_user_webmaster'] =  isset($_POST['wpf_tab_permission_user_webmaster']) ? $_POST['wpf_tab_permission_user_webmaster'] : 'no';
            $options['wpf_tab_permission_user_others'] =  isset($_POST['wpf_tab_permission_user_others']) ? $_POST['wpf_tab_permission_user_others'] : 'no';
            $options['wpf_tab_permission_user_guest'] =  isset($_POST['wpf_tab_permission_user_guest']) ? $_POST['wpf_tab_permission_user_guest'] : 'no';

            $options['wpf_tab_permission_priority_client'] =  isset($_POST['wpf_tab_permission_priority_client']) ? $_POST['wpf_tab_permission_priority_client'] : 'no';
            $options['wpf_tab_permission_priority_webmaster'] =  isset($_POST['wpf_tab_permission_priority_webmaster']) ? $_POST['wpf_tab_permission_priority_webmaster'] : 'no';
            $options['wpf_tab_permission_priority_others'] =  isset($_POST['wpf_tab_permission_priority_others']) ? $_POST['wpf_tab_permission_priority_others'] : 'no';
            $options['wpf_tab_permission_priority_guest'] =  isset($_POST['wpf_tab_permission_priority_guest']) ? $_POST['wpf_tab_permission_priority_guest'] : 'no';

            $options['wpf_tab_permission_status_client'] =  isset($_POST['wpf_tab_permission_status_client']) ? $_POST['wpf_tab_permission_status_client'] : 'no';
            $options['wpf_tab_permission_status_webmaster'] =  isset($_POST['wpf_tab_permission_status_webmaster']) ? $_POST['wpf_tab_permission_status_webmaster'] : 'no';
            $options['wpf_tab_permission_status_others'] =  isset($_POST['wpf_tab_permission_status_others']) ? $_POST['wpf_tab_permission_status_others'] : 'no';
            $options['wpf_tab_permission_status_guest'] =  isset($_POST['wpf_tab_permission_status_guest']) ? $_POST['wpf_tab_permission_status_guest'] : 'no';

            $options['wpf_tab_permission_screenshot_client'] =  isset($_POST['wpf_tab_permission_screenshot_client']) ? $_POST['wpf_tab_permission_screenshot_client'] : 'no';
            $options['wpf_tab_permission_screenshot_webmaster'] =  isset($_POST['wpf_tab_permission_screenshot_webmaster']) ? $_POST['wpf_tab_permission_screenshot_webmaster'] : 'no';
            $options['wpf_tab_permission_screenshot_others'] =  isset($_POST['wpf_tab_permission_screenshot_others']) ? $_POST['wpf_tab_permission_screenshot_others'] : 'no';
            $options['wpf_tab_permission_screenshot_guest'] =  isset($_POST['wpf_tab_permission_screenshot_guest']) ? $_POST['wpf_tab_permission_screenshot_guest'] : 'no';

            $options['wpf_tab_permission_information_client'] =  isset($_POST['wpf_tab_permission_information_client']) ? $_POST['wpf_tab_permission_information_client'] : 'no';
            $options['wpf_tab_permission_information_webmaster'] =  isset($_POST['wpf_tab_permission_information_webmaster']) ? $_POST['wpf_tab_permission_information_webmaster'] : 'no';
            $options['wpf_tab_permission_information_others'] =  isset($_POST['wpf_tab_permission_information_others']) ? $_POST['wpf_tab_permission_information_others'] : 'no';
            $options['wpf_tab_permission_information_guest'] =  isset($_POST['wpf_tab_permission_information_guest']) ? $_POST['wpf_tab_permission_information_guest'] : 'no';

            $options['wpf_tab_permission_delete_task_client'] =  isset($_POST['wpf_tab_permission_delete_task_client']) ? $_POST['wpf_tab_permission_delete_task_client'] : 'no';
            $options['wpf_tab_permission_delete_task_webmaster'] =  isset($_POST['wpf_tab_permission_delete_task_webmaster']) ? $_POST['wpf_tab_permission_delete_task_webmaster'] : 'no';
            $options['wpf_tab_permission_delete_task_others'] =  isset($_POST['wpf_tab_permission_delete_task_others']) ? $_POST['wpf_tab_permission_delete_task_others'] : 'no';
            $options['wpf_tab_permission_delete_task_guest'] =  isset($_POST['wpf_tab_permission_delete_task_guest']) ? $_POST['wpf_tab_permission_delete_task_guest'] : 'no';
            
            $options['wpf_tab_auto_screenshot_task_client'] =  isset($_POST['wpf_tab_auto_screenshot_task_client']) ? $_POST['wpf_tab_auto_screenshot_task_client'] : 'no';
            $options['wpf_tab_auto_screenshot_task_webmaster'] =  isset($_POST['wpf_tab_auto_screenshot_task_webmaster']) ? $_POST['wpf_tab_auto_screenshot_task_webmaster'] : 'no';
            $options['wpf_tab_auto_screenshot_task_others'] =  isset($_POST['wpf_tab_auto_screenshot_task_others']) ? $_POST['wpf_tab_auto_screenshot_task_others'] : 'no';
            $options['wpf_tab_auto_screenshot_task_guest'] =  isset($_POST['wpf_tab_auto_screenshot_task_guest']) ? $_POST['wpf_tab_auto_screenshot_task_guest'] : 'no';

            /* update settings for display stickers  */
            $webmaster_sticker = 'no';

            if(isset($_POST['wpf_tab_permission_display_stickers_webmaster']) && $_POST['wpf_tab_permission_display_stickers_webmaster'] == 'yes') {
                $webmaster_sticker = 'yes';
            }

            $options['wpf_tab_permission_display_stickers_client'] =  isset($_POST['wpf_tab_permission_display_stickers_client']) ? $_POST['wpf_tab_permission_display_stickers_client'] : 'no';
            $options['wpf_tab_permission_display_stickers_webmaster'] =  $webmaster_sticker;
            $options['wpf_tab_permission_display_stickers_others'] =  isset($_POST['wpf_tab_permission_display_stickers_others']) ? $_POST['wpf_tab_permission_display_stickers_others'] : 'no';
            $options['wpf_tab_permission_display_stickers_guest'] =  isset($_POST['wpf_tab_permission_display_stickers_guest']) ? $_POST['wpf_tab_permission_display_stickers_guest'] : 'no';
            
            $webmaster_taskid = 'no';

            if(isset($_POST['wpf_tab_permission_display_task_id_webmaster']) && $_POST['wpf_tab_permission_display_task_id_webmaster'] == 'yes') {
                $webmaster_taskid = 'yes';
            }
            $options['wpf_tab_permission_display_task_id_client'] =  isset($_POST['wpf_tab_permission_display_task_id_client']) ? $_POST['wpf_tab_permission_display_task_id_client'] : 'no';
            $options['wpf_tab_permission_display_task_id_webmaster'] =  $webmaster_taskid;
            $options['wpf_tab_permission_display_task_id_others'] =  isset($_POST['wpf_tab_permission_display_task_id_others']) ? $_POST['wpf_tab_permission_display_task_id_others'] : 'no';
            $options['wpf_tab_permission_display_task_id_guest'] =  isset($_POST['wpf_tab_permission_display_task_id_guest']) ? $_POST['wpf_tab_permission_display_task_id_guest'] : 'no';

            // => v2.1.0
            $keyboard_shortcut = 'no';

            if(isset($_POST['wpf_tab_permission_keyboard_shortcut_webmaster']) && $_POST['wpf_tab_permission_keyboard_shortcut_webmaster'] == 'yes') {
                $keyboard_shortcut = 'yes';
            }
            $options['wpf_tab_permission_keyboard_shortcut_client'] =  isset($_POST['wpf_tab_permission_keyboard_shortcut_client']) ? $_POST['wpf_tab_permission_keyboard_shortcut_client'] : 'no';
            $options['wpf_tab_permission_keyboard_shortcut_webmaster'] =  $keyboard_shortcut;
            $options['wpf_tab_permission_keyboard_shortcut_others'] =  isset($_POST['wpf_tab_permission_keyboard_shortcut_others']) ? $_POST['wpf_tab_permission_keyboard_shortcut_others'] : 'no';
            $options['wpf_tab_permission_keyboard_shortcut_guest'] =  isset($_POST['wpf_tab_permission_keyboard_shortcut_guest']) ? $_POST['wpf_tab_permission_keyboard_shortcut_guest'] : 'no';

            $parms1 = [];

            foreach ($options as $key => $value) {
                array_push($parms1, ['name' => $key,'value' => $value]);
            }
            update_site_data($parms1);
            syncUsers();

            if (isset($_POST['wpfeedback_licence_key']) && $_POST['wpfeedback_licence_key'] != "") {
                if ($_POST['wpfeedback_licence_key'] != '00000000000000000000000000000000') {
                    $wpf_license_key = $_POST['wpfeedback_licence_key']; 
                    $wpf_result = wpf_license_key_license_item($wpf_license_key);
                    if ($wpf_result['license'] == 'valid') {
                        $wpf_crypt_key = wpf_crypt_key($wpf_license_key,'e');
                        update_option('wpf_license_key', $wpf_crypt_key, 'no');
                        update_option('wpf_license', $wpf_result['license'], 'no');
                        update_option('wpf_license_expires', $wpf_result['expires'], 'no');
                        if(!get_option('wpf_decr_key')){
                            update_option('wpf_decr_key', $wpf_result['payment_id'],'no');
                            update_option('wpf_decr_checksum', $wpf_result['checksum'],'no');
                            $wpf_crypt_key = wpf_crypt_key($wpf_license_key,'e');
                            update_option('wpf_license_key',$wpf_crypt_key,'no');
                        }
//                        if(get_option('wpf_initial_sync')!=1 && get_option('wpf_initial_sync')!=2){
                            do_action('wpf_initial_sync',$wpf_license_key);
//                        }
                    } else {
                        update_option('wpf_license_key', $_POST['wpfeedback_licence_key'], 'no');
                        update_option('wpf_license', $wpf_result['license'], 'no');
                    }
                }
            }
        }

//        echo '<pre>'; print_r($options); die;

        /* load site data => v2.1.0  */
        //get_notif_sitedata_filterdata();

        wp_redirect(add_query_arg('page', 'wpfeedback_page_permissions', admin_url('admin.php')));
        exit;
    }
}

/*
 * This function is used to create a dropdown of the roles available in website on the "Permissions" tab for the selection.
 *
 * @input Boolean
 * @return String
 */
if (!function_exists('wpfeedback_dropdown_roles')) {
    function wpfeedback_dropdown_roles($selected = false)
    {

    
        global $wp_roles;
        $p = '';
        $r = '';
        //$editable_roles = get_editable_roles();
        $editable_roles = $wp_roles->get_names();
        $selected_roles = get_site_data_by_key('wpf_selcted_role');
        // For backwards compatibility
        if (is_string($selected_roles)) {
            $selected_roles = explode(',', $selected_roles);
            foreach ($editable_roles as $role => $details) {
//                $name = translate_user_role($details['name']);
                if (is_array($selected_roles) AND in_array($role, $selected_roles)) // preselect specified role
                    $p .= "\n\t<option selected='selected' value='" . esc_attr($role) . "'>$details</option>";
                else
                    $r .= "\n\t<option value='" . esc_attr($role) . "'>$details</option>";
            }
        }
        return $p . $r;
    }
}

/*
 * This function is used to get the title of the page based on the page ID. This function is not used currently.
 *
 * @input Int
 * @return String
 */
if (!function_exists('wpf_get_page_title')) {
    function wpf_get_page_title($post_id)
    {
        $get_page_id = get_post_meta($post_id, '_wpf_page_id', true);
        $page_title = get_the_title($get_page_id);
        return $page_title;
    }
}

/*
 * This function is used to get the listing of all the wpfeedback tasks.
 *
 * @input NULL
 * @return String
 */
if (!function_exists('wpfeedback_get_post_list')) {
    function wpfeedback_get_post_list()
    {
        $output = '';
        $args = array(
            'numberposts' => -1,
            'post_type' => 'wpfeedback',
            'orderby' => 'title',
            'orderby' => 'date',
            'order' => 'DESC',
            'task_center' => 1,
            'wpf_site_id' => get_option('wpf_site_id'),
        );


        /* START */
            $currnet_user_information = wpf_get_current_user_information();
            $current_role = $currnet_user_information['role'];
            // $current_user_name = $currnet_user_information['display_name'];
            $current_user_name = $currnet_user_information['display_name'];
            $current_user_id = $currnet_user_information['user_id'];
            $wpf_website_builder = get_site_data_by_key('wpf_website_developer') == 1 ? get_site_data_by_key('wpf_website_developer') : 0;
            if($current_user_name=='Guest'){
                $wpf_website_client = get_site_data_by_key('wpf_website_client') == 1 ? get_site_data_by_key('wpf_website_client') : 0;
                $wpf_current_role = 'guest';
                if($wpf_website_client){
                    $wpf_website_client_info = get_userdata($wpf_website_client);
                    if($wpf_website_client_info){
                        if($wpf_website_client_info->display_name==''){
                            $current_user_name = $wpf_website_client_info->user_nicename;
                        }
                        else{
                            $current_user_name = $wpf_website_client_info->display_name;
                        }
                    }
                }

            }
            else{
                $wpf_current_role = wpf_user_type();
            }
            $current_user_name = addslashes($current_user_name);

            if ($wpf_current_role == 'advisor') {
                $wpf_tab_permission_display_stickers = (get_site_data_by_key('wpf_tab_permission_display_stickers_webmaster') != 'no') ? 'yes' : 'no';
                $wpf_tab_permission_display_task_id = (get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no') ? 'yes' : 'no';
            } elseif ($wpf_current_role == 'king') {
                $wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_client') == 'yes' ? 'yes' : 'no';
                $wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_client') == 'yes' ? 'yes' : 'no';
            } elseif ($wpf_current_role == 'council') {
                $wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_others') == 'yes' ? 'yes' : 'no';
                $wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_others') == 'yes' ? 'yes' : 'no';
            } else {
                $wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_guest') == 'yes' ? 'yes' : 'no';
                $wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_guest') == 'yes' ? 'yes' : 'no';
            }
            /* END */

        $url = WPF_CRM_API.'wp-api/all/task-center-tasks';
        $sendtocloud=json_encode($args);
        $myposts = wpf_send_remote_post($url,$sendtocloud);
	    $wpf_orphans=array();
        //echo count($myposts);
        // echo json_encode($myposts); die;
        if ($myposts):
            // Loop the posts
//            $i = $myposts['count'];
//            $i = count($myposts);
            $output .= '<ul id="all_wpf_list" style="list-style-type: none; font-size:12px;">';
            
	    if(isset($myposts['data']) && !empty($myposts['data'])) {
		foreach ($myposts['data'] as $mypost){
            $user_atarim_type=get_user_meta($current_user_id, 'wpf_user_type',true);

            if($mypost['task']['is_internal']=='1'){
                if($user_atarim_type!='advisor'){
                    continue;
                }
            }
                $post_id = $mypost['task']['id'];
                $site_task_id = $mypost['task']['site_task_id'];
                $wpf_task_id = $mypost['task']['wpf_task_id'];
                $author_id = $mypost['task']['task_config_author_id'];
                $get_post_date = $mypost['task']['created_at'];
                $date = date_create($get_post_date);
                $post_date = date_format($date, "d/m/Y H:i");
                $post_title = $mypost['task']['task_title'];
                $task_page_url = $mypost['task']['task_page_url'];
                $wpf_task_screenshot = $mypost['task']['wpf_task_screenshot'];
                $task_page_title = ($mypost['task']['task_page_title'] != null) ? $mypost['task']['task_page_title'] : "";
                $task_config_author_name = $mypost['task']['task_config_author_name'];
                $task_notify_users = $mypost['task']['task_notify_users'];
                $task_config_author_resX = $mypost['task']['task_config_author_resX'];
                $task_config_author_resY = $mypost['task']['task_config_author_resY'];
                $get_task_type = $mypost['task']['task_type'];
                $is_internal = $mypost['task']['is_internal'];

                if ($get_task_type == 'general') {
                    $task_type = 'general';
                    $general = '<span class="wpf_task_type">'.__("General","wpfeedback").'</span>';
                } elseif ($get_task_type == 'email') { //!email
                    $task_type = 'email';
                    $general = '<span class="wpf_task_type">'.__("Email","wpfeedback").'</span>';
                } elseif($get_task_type == 'graphics'){
                    $task_type = 'graphics';
                    $general = '<span class="wpf_task_type">'.__("Graphics","wpfeedback").'</span>';
                } else {
                    $task_type = '';
                    $general = '';
                }
                
                if($is_internal=='1'){
                    $internal='<span class="wpf_task_type" title="Task type">' . __("Internal", "wpfeedback") . '</span>';
                    $internal_icon=$internal_icon_html='<span class="wpf_chevron_wrapper"><i class="gg-chevron-double-left"></i></span>';
                    $internal_class='wpfb-internal';
                }else{
                    $internal='';
                    $internal_icon='';
                    $internal_class='';
                }
                
                //create list of orphan tasks
                if($get_task_type=="general" && ($mypost['task']['wpfb_task_bubble'] !=NULL) ){
                    $wpf_orphans[]=$post_id;
                }

                if ($mypost['task']['is_admin_task'] == 1) {
                    $wpf_task_status = 'wpf_admin';
                    $admin_tag = '<span class="wpf_task_type">'.__("Admin","wpfeedback").'</span>';
                } else {
                    $wpf_task_status = 'public';
                    $admin_tag = '';
                }

                $task_config_author_browser = $mypost['task']['task_config_author_browser'];
                $task_config_author_browserVersion = $mypost['task']['task_config_author_browserVersion'];
                //$task_comment_id = $mypost['task']['task_comment_id'];
                $task_comment_id = $mypost['task']['wpf_task_id'];
                $task_priority = $mypost['task']['task_priority'];
                $task_status = $mypost['task']['task_status'];

                $task_tags = $mypost['task']['tags'];
//                $post_title = esc_html($mypost['task']['task_title']);
                $all_other_tag = '';
                $wpfb_tags_html = '';
		
                if($task_tags){
                    $tag_length = count($task_tags);
                    $wpfb_tags_html = '<div class="wpf_task_tags">';
                    $i = 1;

                    foreach ($task_tags as $task_tag => $task_tags_value) {
                        if($i == 1){
                            $wpfb_tags_html .=  '<span class="wpf_task_tag">'.$task_tags_value["tag"].'</span>';
                        }
                        else {
                            if($tag_length == $i){
                                $all_other_tag .=  $task_tags_value['tag'];
                            }else{
                                $all_other_tag .=  $task_tags_value["tag"].', ';
                            }
                        }
                        $i++;
                    }
                    if($tag_length > 1){
                        $wpfb_tags_html .= '<span class="wpf_task_tag_more" title="'.$all_other_tag.'">...</span>';
                    }
                    $wpfb_tags_html .= '</div>';
                }

                $task_date = $mypost['task']['created_at'];
                $task_date1 = date_create($task_date);
                //Old Logic to get current time. Was creating issues when displaying message
                //$task_date2 = new DateTime('now');

                //New Logic to get current time.
                $wpf_wp_current_timestamp = date('Y-m-d H:i:s', current_time('timestamp', 0));
                $task_date2 = date_create($wpf_wp_current_timestamp);

                $display_span = '';
                $custom_class = '';
                if($wpf_tab_permission_display_stickers == 'yes'){
                    $display_span = '<span class="'.$task_priority.'_custom  wpf_top_badge"></span> ';
                    $custom_class = $task_status."_custom";
                }

                $curr_task_time = wpfb_time_difference($task_date1, $task_date2);
                
                $display_check_mark = '';
                if($wpf_tab_permission_display_task_id != 'yes'){
                    $display_check_mark = '<i class="gg-check"></i>';
                }else{
                    $display_check_mark =  '<span class="wpf_bubble_num_wrapper">'.$mypost['task']['site_task_id'].'</span>'.$internal_icon;//$task_comment_id;
                }


                if ($task_status == 'complete') {
                    $bubble_label = $display_span.$display_check_mark;
                } else {
                    $bubble_label = $display_span. '<span class="wpf_bubble_num_wrapper">'.$mypost['task']['site_task_id'].'</span>'.$internal_icon;//$task_comment_id;
                }


                // if ($author_id) {

                //     $task_author = get_user_by('ID', intval($author_id));
                //     if ( $task_author ) {
                //         $task_author_meta = get_user_meta( intval($author_id) );
                //         if ( $task_author_meta ) {
                //             $author = $task_author_meta['first_name'][0] . ' ' . $task_author_meta['last_name'][0];
                //             if ( empty(trim($author)) ) {
                //                 $author = $mypost['task']['task_config_author_name'];
                //             } 
                //         }                     
                //     } else {
                //         if(gettype(get_user_by('login',$mypost['task']['task_config_author_name']))=="object"){
                //             $authr = get_user_by('login',$mypost['task']['task_config_author_name']);
                //             $author = $authr->display_name;
                //             $task_config_author_name = $authr->display_name;
                //         } else{
                //             $author = "Deleted User"; // $mypost['task']['task_config_author_name'];
                //             // $author = get_user_by('login',$mypost['task']['task_config_author_name'])->display_name;
                //         }
                //     }                   
                    
                // } else {
                //     $author = 'Guest';
                // }

                // if ( $mypost['task']['task_type'] === 'email' ) {
                //     $author = $mypost['task']['task_config_author_name'];
                // }

                $author = get_task_author( $mypost['task'] );

                $wpf_task_status_label= '<div class="wpf_task_label"><span class="task_status wpf_'.$task_status.'" title="Status: '.$task_status.'">'.get_wpf_status_icon().'</span>';
                $wpf_task_priority_label= '<span class="task_priority wpf_'.$task_priority.'" title="Priority: '.$task_priority.'">'. get_wpf_priority_icon() .'</span></div>';
                //$wpfb_tags_html = '<div class="wpf_task_tags"><span class="wpf_task_tag">Test tag</span><span class="wpf_task_tag_more">...</span></div>';

                
                $author_name = "'" . $author . "'";
                $output .= '<li class="post_' . $post_id . ' ' . $task_priority .' '.$task_status. ' wpf_list"><a href="javascript:void(0)" class="' .$internal_class.' '. $task_status.' '.$internal_class.'" id="wpf-task-' . $post_id . '" data-wpf_task_status="' . $wpf_task_status . '"" data-task_type="' . $task_type . '" data-task_author_name="' . $task_config_author_name . '" data-task_config_author_browserVersion="' . $task_config_author_browserVersion . '" data-task_config_author_res="' . $task_config_author_resX . ' X ' . $task_config_author_resY . '" data-task_config_author_browser="' . $task_config_author_browser . '" data-task_config_author_name="'.__('By ', 'wpfeedback') . $task_config_author_name . ' ' . $post_date . '" data-task_notify_users="' . $task_notify_users . '" data-task_page_url="' . $task_page_url . '" data-wpf_task_screenshot="'.$wpf_task_screenshot.'" data-task_page_title="' . $post_title . '"
    data-task_priority="' . $task_priority . '" data-task_status="' . $task_status . '" data-disp-id="'.$site_task_id.'" data-is-internal="'.$is_internal.'" onclick="get_wpf_chat(this,true,'.$author_name.')" data-postid="' . $post_id . '" data-uid="' . $author_id . '"  data-task_no="' . $task_comment_id . '"><div class="wpf_chat_top"><input type="checkbox" value="'.$post_id.'" name="wpf_task_id" data-disp-id="'.$site_task_id.'" id="wpf_'.$post_id.'" class="wpf_task_id" style="display:none;"><div class="wpf_task_num_top '.$custom_class.'">' . $bubble_label . '</div><div class="wpf_task_main_top"><div class="wpf_task_details_top">'. $author . ' <span>' . $curr_task_time['comment_time'] . '</span></div><div class="wpf_task_pagename">' . $task_page_title . ' </div><div class="wpf_task_title_top">' . $post_title . '</div></div>'.$internal.$general.'<div class="wpf_task_meta"><div class="wpf_task_meta_icon"><i class="gg-chevron-left"></i></div><div class="wpf_task_meta_details">'. ' ' . $admin_tag . ' ' . $general .$wpf_task_status_label.$wpf_task_priority_label.$wpfb_tags_html.'</div></div></div></a></li>';
//                $i--;
            }
	    }
            wp_reset_postdata();
            $output .= '</ul>';
        else:
            $output = '<div class="wpf_no_tasks_found"><i class="gg-info"></i> No tasks found</div>';
        endif;
       
        $response[0]=$output;
        $response[1]=$wpf_orphans;

        return $response;
    }
}

/*
 * This function is used to get the logo to be displayed on the backend and sidebar.
 *
 * @input NULL
 * @return String
 */
if (!function_exists('get_wpf_logo')) {
    function get_wpf_logo()
    {
        $wpf_global_settings = get_site_data_by_key('wpf_global_settings');
        $get_logoid = get_site_data_by_key('wpfeedback_logo');
	if ($wpf_global_settings == 'yes') {
            $get_logo_url = $get_logoid;
        } else {
            if($get_logoid!=''){
		$get_logo_url = $get_logoid;
            }
            else{
                $get_logo_url = esc_url(WPF_PLUGIN_URL . 'images/Atarim.svg');
            }
        }
        return $get_logo_url;
    }
}

if (!function_exists('get_wpf_favicon')) {
    function get_wpf_favicon(){
        $wpf_global_settings = get_site_data_by_key('wpf_global_settings');
        $get_faviconid = get_site_data_by_key('wpfeedback_favicon');
        if ($wpf_global_settings == 'yes') {
            $get_favicon_url = $get_faviconid;
        } else {
            if($get_faviconid!=''){
        		$get_favicon_url = $get_faviconid;
            }
            else{
                $get_favicon_url = esc_url(WPF_PLUGIN_URL . 'images/atarim_icon.svg');
            }
        }
        if($get_faviconid == '') {
            $get_favicon_url = esc_url(WPF_PLUGIN_URL . 'images/atarim_icon.svg');
        }
        return $get_favicon_url;
    }
}
/*
 * no image svg
 */
if (!function_exists('get_wpf_no_image')) {
    function get_wpf_no_image()
    {
        return '<svg id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m466.916 109.19h-42.077v-54.107c0-24.859-20.225-45.083-45.084-45.083h-334.671c-24.859 0-45.084 20.225-45.084 45.083v302.643c0 24.859 20.225 45.083 45.084 45.083h42.077v54.106c0 24.859 20.225 45.084 45.084 45.084h334.671c24.859.001 45.084-20.224 45.084-45.083v-302.642c0-24.859-20.225-45.084-45.084-45.084zm0 20c13.831 0 25.084 11.252 25.084 25.083v267.847l-127.451-144.08c-1.898-2.146-4.625-3.375-7.49-3.375s-5.592 1.229-7.49 3.375l-68.36 77.279-48.574-52.424c-1.893-2.042-4.551-3.204-7.335-3.204s-5.442 1.161-7.335 3.203l-110.804 119.585v-268.205c0-13.831 11.253-25.083 25.084-25.083h334.671zm-199.024 241.183-49.865 56.371h-87.552l94.825-102.338zm-180.731-216.099v228.536h-42.077c-13.831 0-25.084-11.253-25.084-25.084v-302.643c0-13.831 11.253-25.083 25.084-25.083h334.671c13.831 0 25.084 11.252 25.084 25.083v54.107h-272.594c-24.859 0-45.084 20.225-45.084 45.084zm379.755 327.726h-334.671c-13.831 0-25.084-11.252-25.084-25.084v-10.18c.138.006.276.009.415.009h216.454c5.522 0 10-4.477 10-10s-4.478-10-10-10h-79.301l112.33-126.986 112.33 126.986h-54.946c-5.522 0-10 4.477-10 10s4.478 10 10 10h77.557v10.171c0 13.832-11.253 25.084-25.084 25.084z"/><g><path d="m369.42 446.74c-4.174 0-7.985-2.681-9.402-6.604-1.378-3.815-.249-8.236 2.808-10.911 3.277-2.868 8.171-3.259 11.871-.97 3.518 2.176 5.36 6.477 4.51 10.525-.963 4.583-5.096 7.96-9.787 7.96z"/></g><path d="m261.916 269.413c-29.241 0-53.03-23.789-53.03-53.03s23.789-53.03 53.03-53.03 53.03 23.789 53.03 53.03-23.789 53.03-53.03 53.03zm0-86.06c-18.213 0-33.03 14.817-33.03 33.03s14.817 33.03 33.03 33.03 33.03-14.817 33.03-33.03-14.817-33.03-33.03-33.03z"/></g></g></svg>';
    }
}

/*
 * get user profile svg icon
 */
if (!function_exists('get_wpf_user_icon')) {
    function get_wpf_user_icon()
    {
        return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="200px" height="166.5px" viewBox="0 0 200 166.5" enable-background="new 0 0 200 166.5" xml:space="preserve"> <path fill="none" stroke="#4B5668" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" d="M141.4,158.021V141.46 c0-18.29-14.83-33.119-33.12-33.119H42.04c-18.292,0-33.121,14.829-33.121,33.119v16.561"/> <circle fill="none" stroke="#4B5668" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" cx="75.16" cy="42.099" r="33.12"/> <path fill="none" stroke="#4B5668" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" d="M191.082,158.021V141.46 c-0.018-15.088-10.221-28.269-24.841-32.044"/> <path fill="none" stroke="#4B5668" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" d="M133.122,10.054 c17.723,4.54,28.413,22.581,23.869,40.301c-2.993,11.725-12.146,20.875-23.869,23.87"/> </svg>';
    }
}

/*
 * get user screenshot svg icon
 */
if (!function_exists('get_wpf_screenshot_icon')) {
    function get_wpf_screenshot_icon()
    {
        return '<svg enable-background="new 0 0 24 24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 23h-18c-1.654 0-3-1.346-3-3v-12c0-1.654 1.346-3 3-3 .853 0 1.619-.474 2-1.236l.211-.422c.722-1.445 2.174-2.342 3.789-2.342h6c1.615 0 3.067.897 3.789 2.342l.211.422c.381.762 1.147 1.236 2 1.236 1.654 0 3 1.346 3 3v12c0 1.654-1.346 3-3 3zm-12-20c-.853 0-1.619.474-2 1.236l-.211.422c-.722 1.445-2.174 2.342-3.789 2.342-.551 0-1 .449-1 1v12c0 .552.449 1 1 1h18c.552 0 1-.448 1-1v-12c0-.551-.448-1-1-1-1.615 0-3.067-.897-3.789-2.342l-.211-.422c-.381-.762-1.147-1.236-2-1.236h-6zm3 16c-3.309 0-6-2.691-6-6s2.691-6 6-6 6 2.691 6 6-2.691 6-6 6zm0-10c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4z"/></svg>';
    }
}

/*
 * get share svg icon
 */
if (!function_exists('get_wpf_share_icon')) {
    function get_wpf_share_icon()
    {
        return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="200px" height="195px" viewBox="0 0 200 195" enable-background="new 0 0 200 195" xml:space="preserve"> <path fill="#4B5668" d="M128.002,174.893c-25.824,10.856-55.761,5.63-76.456-13.409c17.481-18.517,4.467-49.346-21.274-49.346 C14.131,112.138,1,125.27,1,141.411c0,19.861,19.495,34.034,38.408,27.805c24.83,24.902,62.124,31.979,94.016,18.573 c3.56-1.496,5.234-5.598,3.735-9.16C135.664,175.069,131.564,173.396,128.002,174.893L128.002,174.893z M14.989,141.411 c0-8.427,6.855-15.283,15.283-15.283c8.426,0,15.282,6.856,15.282,15.283s-6.855,15.282-15.282,15.282 C21.845,156.693,14.989,149.838,14.989,141.411z"/> <path fill="#4B5668" d="M23.708,88.273c3.644,1.325,7.648-0.566,8.965-4.181C39.639,64.94,54.739,49.47,73.56,41.929 c4.619,10.106,14.815,17.149,26.632,17.149c16.14,0,29.272-13.131,29.272-29.272c0-16.14-13.132-29.271-29.272-29.271 c-15.512,0-28.238,12.132-29.205,27.404c-23.95,8.684-42.817,27.608-51.461,51.371C18.206,82.939,20.077,86.953,23.708,88.273 L23.708,88.273z M100.193,14.523c8.426,0,15.281,6.855,15.281,15.282s-6.854,15.283-15.281,15.283 c-8.428,0-15.283-6.855-15.283-15.283C84.911,21.378,91.766,14.523,100.193,14.523z"/> <path fill="#4B5668" d="M185.615,116.597c0.248-1.387,0.393-5.853,0.393-7.949c0-24.25-10.332-47.48-28.349-63.734 c-2.868-2.587-7.29-2.36-9.879,0.508c-2.588,2.868-2.361,7.291,0.508,9.879c15.778,14.234,24.703,35.034,23.641,56.899 c-17.003-1.046-31.086,12.519-31.086,29.212c0,16.14,13.13,29.271,29.271,29.271c16.142,0,29.272-13.132,29.272-29.271 C199.386,130.96,193.877,121.776,185.615,116.597L185.615,116.597z M170.112,156.693c-8.425,0-15.281-6.855-15.281-15.282 c0-8.428,6.856-15.284,15.281-15.284c8.429,0,15.282,6.856,15.282,15.284C185.396,149.838,178.541,156.693,170.112,156.693z"/> </svg>';
    }
}

/*
 * get status svg icon
 */
if (!function_exists('get_wpf_status_icon')) {
    function get_wpf_status_icon()
    {
        return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="200px" height="110.836px" viewBox="0 0 200 110.836" enable-background="new 0 0 200 110.836" xml:space="preserve"> <g> <path fill="#4B5668" stroke="#FFFFFF" stroke-width="3" stroke-miterlimit="10" d="M96.199,84.264 c3.212-8.122,5.887-14.854,8.54-21.596c6.955-17.673,14.016-35.305,20.766-53.054c1.697-4.463,3.078-8.703,8.865-8.447 c5.82,0.257,7.804,4.332,9.179,9.283c4.447,16.01,9.085,31.967,13.669,47.941c0.518,1.799,1.149,3.571,1.829,5.669 c10.653,0,21.103,0.078,31.551-0.034c5.134-0.054,9.407,1.354,9.421,7.142c0.014,5.783-4.243,7.199-9.379,7.208 c-12.833,0.021-25.67,0.222-38.504,0.363c-5.496,0.062-7.919-3.141-9.272-8.057c-3.298-11.993-6.843-23.916-10.682-37.219 c-1.465,2.919-2.413,4.478-3.063,6.153c-8.467,21.917-16.763,43.902-25.501,65.708c-1.02,2.545-4.559,5.876-6.71,5.726 c-2.753-0.194-6.52-3-7.705-5.638c-7.184-16.025-13.744-32.325-20.544-48.52c-0.972-2.317-2.113-4.565-3.642-7.844 c-4.028,8.057-7.796,15.156-11.148,22.445c-2.247,4.89-5.341,7.185-11.01,6.967c-10.686-0.407-21.399-0.201-32.1-0.085 c-5.209,0.056-10.13-0.729-10.255-7.028c-0.124-6.294,4.633-7.399,9.939-7.321c8.323,0.124,16.667-0.326,24.963,0.168 c4.906,0.292,7.171-1.588,9.085-5.808c4.018-8.863,8.283-17.642,13.061-26.11c1.543-2.737,5.344-6.432,7.502-6.07 c3.262,0.548,7.29,3.667,8.725,6.747c6.71,14.412,12.632,29.188,18.867,43.823C93.527,78.845,94.57,80.845,96.199,84.264z"/> </g> </svg>';
    }
}

/*
 * get priority svg icon
 */
if (!function_exists('get_wpf_priority_icon')) {
    function get_wpf_priority_icon()
    {
        return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="200px" height="200px" viewBox="0 0 200 200" enable-background="new 0 0 200 200" xml:space="preserve"> <path fill="#4B5668" d="M197.058,61.695L157.311,5.664c-2.004-2.826-5.271-4.514-8.736-4.515c-0.002,0-0.002,0-0.002,0 c-3.467,0-6.733,1.687-8.738,4.515l-39.747,56.032c-2.328,3.281-2.628,7.542-0.781,11.118c1.847,3.575,5.496,5.796,9.52,5.796 h16.558v108.869c0,6.141,4.994,11.135,11.135,11.135h24.106c6.141,0,11.136-4.995,11.136-11.135V78.609h16.558 c4.024,0,7.671-2.222,9.519-5.796C199.683,69.237,199.385,64.976,197.058,61.695z M164.363,63.817c-4.087,0-7.396,3.311-7.396,7.396 v112.61h-16.794V71.214c0-4.086-3.31-7.396-7.395-7.396h-16.063l31.854-44.907l31.855,44.907L164.363,63.817L164.363,63.817z"/> <path fill="#4B5668" d="M91.176,121.152h-16.56V12.282c0-6.141-4.994-11.136-11.135-11.136H39.375 c-6.141,0-11.135,4.995-11.135,11.136V52.68c0,4.086,3.31,7.396,7.396,7.396c4.085,0,7.396-3.31,7.396-7.396V15.939h16.792v112.61 c0,4.085,3.31,7.396,7.396,7.396h16.062L51.428,180.85l-31.853-44.905h16.062c4.085,0,7.396-3.311,7.396-7.396V87.077 c0-4.085-3.311-7.395-7.396-7.395c-4.086,0-7.396,3.31-7.396,7.395v34.075H11.683c-4.026,0-7.674,2.222-9.521,5.797 c-1.847,3.576-1.546,7.836,0.782,11.115l39.747,56.032c2.006,2.827,5.272,4.517,8.738,4.517h0.002 c3.468-0.001,6.733-1.691,8.737-4.517l39.746-56.03c2.329-3.281,2.63-7.541,0.782-11.117 C98.848,123.374,95.2,121.152,91.176,121.152L91.176,121.152z"/> </svg>';
    }
}

/*
 * get info svg icon
 */
if (!function_exists('get_wpf_info_icon')) {
    function get_wpf_info_icon()
    {
        return '<svg height="100px" version="1.1" viewBox="0 0 100 100" width="100px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="MiMedia---iOS/Android" stroke="none" stroke-width="1"><g fill="#4B5668" id="icon32pt_info"><path d="M50,94 C74.300529,94 94,74.300529 94,50 C94,25.699471 74.300529,6 50,6 C25.699471,6 6,25.699471 6,50 C6,74.300529 25.699471,94 50,94 L50,94 Z M50,86 C69.882251,86 86,69.882251 86,50 C86,30.117749 69.882251,14 50,14 C30.117749,14 14,30.117749 14,50 C14,69.882251 30.117749,86 50,86 L50,86 Z M45,49.0044356 C45,46.2405621 47.2441952,44 50,44 C52.7614237,44 55,46.2303666 55,49.0044356 L55,68.9955644 C55,71.7594379 52.7558048,74 50,74 C47.2385763,74 45,71.7696334 45,68.9955644 L45,49.0044356 L45,49.0044356 Z M44,32 C44,28.6862915 46.6930342,26 50,26 C53.3137085,26 56,28.6930342 56,32 C56,35.3137085 53.3069658,38 50,38 C46.6862915,38 44,35.3069658 44,32 L44,32 Z" id="Oval-58"/></g></g></svg>';
    }
}

/*
 * get visibility svg icon
 */
if (!function_exists('get_wpf_visibility_icon')) {
    function get_wpf_visibility_icon()
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"> <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path> <circle cx="12" cy="12" r="3"></circle> </svg>';
    }
}

/*
 * get exclamation(?) svg icon
 */
if (!function_exists('get_wpf_exclamation_icon')) {

    function get_wpf_exclamation_icon() {
	    return '<svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M8.22766 9C8.77678 7.83481 10.2584 7 12.0001 7C14.2092 7 16.0001 8.34315 16.0001 10C16.0001 11.3994 14.7224 12.5751 12.9943 12.9066C12.4519 13.0106 12.0001 13.4477 12.0001 14M12 17H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#363d4d" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';
    }
}

/*
 * get plus svg icon
 */
if (!function_exists('get_wpf_plus_icon')) {

    function get_wpf_plus_icon() {
	    return '<svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="ph-transition-transform ph-transition-250 ph-mr-2"> <path d="M9 1.5V16.5M1.5 9H16.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path> </svg>';
    }
}

/*
 * get close svg icon
 */
if (!function_exists('get_wpf_close_icon')) {

    function get_wpf_close_icon() {
	return '<svg data-v-07452373="" xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x feather__content"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
    }
}

/*
 * get right svg icon
 */
if (!function_exists('get_wpf_right_icon')) {

    function get_wpf_right_icon() {
	return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"> <polyline points="20 6 9 17 4 12"></polyline> </svg>';
    }
}

/*
 * get pro svg icon
 */
if (!function_exists('get_wpf_pro_icon')) {

    function get_wpf_pro_icon() {
	return '<svg viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M19.0044 8.71143L19.0044 1.71143L12.0044 1.71143L12.0044 8.71143L19.0044 8.71143Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M19.0044 19.7114L19.0044 12.7114L12.0044 12.7114L12.0044 19.7114L19.0044 19.7114Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.00439 19.7114L8.00439 12.7114L1.00439 12.7114L1.00439 19.7114L8.00439 19.7114Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.00439 8.71143L8.00439 1.71143L1.00439 1.71143L1.00439 8.71143L8.00439 8.71143Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </svg>';
    }
}


/*
 * get atarim svg icon
 */
if (!function_exists('get_wpf_icon')) {

    function get_wpf_icon() {
	return '<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080"> <defs><style>.cls-1{fill:#052055}.cls-2{fill:#6d5df3}</style></defs><title>#9304 - New logo request</title> <g> <g> <polygon class="cls-1" points="937.344 785.955 746.1 856.215 851.972 1060.257 1080 1059.991 937.344 785.955"/> <polygon class="cls-1" points="539.938 19.669 0 1059.991 228.152 1059.991 539.873 458.766 652.263 675.369 843.507 605.108 539.938 19.669"/> </g> <polygon class="cls-2" points="227.659 1060.331 373.967 778.521 1055.074 519.371 227.659 1060.331"/> </g> </svg>';
    }
}

/*
 * get report svg icon
 */
if (!function_exists('get_wpf_report_icon')) {

    function get_wpf_report_icon() {
	return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
    }
}

/*
 * get download icon
 */
if (!function_exists('get_wpf_image_download_icon')) {
    function get_wpf_image_download_icon()
    {
        return '<svg viewBox="0 0 512 512" id="ion-android-download" width="18" height="18" fill="#fff"><path d="M403.002 217.001C388.998 148.002 328.998 96 256 96c-57.998 0-107.998 32.998-132.998 81.001C63.002 183.002 16 233.998 16 296c0 65.996 53.999 120 120 120h260c55 0 100-45 100-100 0-52.998-40.996-96.001-92.998-98.999zM224 268v-76h64v76h68L256 368 156 268h68z"></path></svg>';
    }
}

/*
 * get open icon
 */
if (!function_exists('get_wpf_image_open_icon')) {
    function get_wpf_image_open_icon()
    {
        return '<svg height="18" version="1.1" viewBox="0 0 100 100" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="MiMedia---Web" stroke="none" stroke-width="1"><g fill="#fff" id="icon24pt_new_window" transform="translate(2.000000, 2.000000)"><path d="M73.7883228,16 L44.56401,45.2243128 C42.8484762,46.9398466 42.8459918,49.728257 44.5642987,51.4465639 C46.2791092,53.1613744 49.0684023,53.1650001 50.7865498,51.4468526 L80,22.2334024 L80,32.0031611 C80,34.2058797 81.790861,36 84,36 C86.2046438,36 88,34.2105543 88,32.0031611 L88,11.9968389 C88,10.8960049 87.5527117,9.89722307 86.8294627,9.17343595 C86.1051125,8.44841019 85.1063303,8 84.0031611,8 L63.9968389,8 C61.7941203,8 60,9.790861 60,12 C60,14.2046438 61.7894457,16 63.9968389,16 L73.7883228,16 L73.7883228,16 Z M88,56 L88,36.9851507 L88,78.0296986 C88,83.536144 84.0327876,88 79.1329365,88 L16.8670635,88 C11.9699196,88 8,83.5274312 8,78.0296986 L8,17.9703014 C8,12.463856 11.9672124,8 16.8670635,8 L59.5664682,8 L40,8 C42.209139,8 44,9.790861 44,12 C44,14.209139 42.209139,16 40,16 L18.2777939,16 C17.0052872,16 16,17.1947367 16,18.668519 L16,77.331481 C16,78.7786636 17.0198031,80 18.2777939,80 L77.7222061,80 C78.9947128,80 80,78.8052633 80,77.331481 L80,56 C80,53.790861 81.790861,52 84,52 C86.209139,52 88,53.790861 88,56 L88,56 Z" id="Rectangle-2064"/></g></g></svg>';
    }
}

/*
 * push to media icon
 */
if (!function_exists('get_wpf_push_to_media_icon')) {
    function get_wpf_push_to_media_icon()
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32pt" height="31pt" viewBox="0 0 32 31" version="1.1"><g id="surface1"><path style=" stroke:none;fill-rule:nonzero;fill:#ffffff;fill-opacity:1;" d="M 16.234375 16.746094 L 13.558594 24.3125 L 13.550781 24.3125 L 11.476562 30.09375 C 11.621094 30.132812 11.761719 30.164062 11.910156 30.203125 C 11.917969 30.203125 11.925781 30.203125 11.933594 30.203125 C 13.222656 30.535156 14.578125 30.71875 15.972656 30.71875 C 16.667969 30.71875 17.34375 30.679688 18.007812 30.574219 C 18.921875 30.464844 19.800781 30.277344 20.660156 30.015625 C 20.871094 29.953125 21.082031 29.878906 21.296875 29.808594 C 21.066406 29.335938 20.578125 28.28125 20.554688 28.234375 Z M 16.234375 16.746094 "/><path style=" stroke:none;fill-rule:nonzero;fill:#ffffff;fill-opacity:1;" d="M 1.6875 9.5625 C 0.871094 11.351562 0.316406 13.550781 0.316406 15.535156 C 0.316406 16.03125 0.339844 16.53125 0.390625 17.019531 C 0.953125 22.652344 4.707031 27.382812 9.867188 29.507812 C 10.078125 29.59375 10.300781 29.683594 10.519531 29.761719 L 2.929688 9.570312 C 2.277344 9.546875 2.152344 9.585938 1.6875 9.5625 Z M 1.6875 9.5625 "/><path style=" stroke:none;fill-rule:nonzero;fill:#ffffff;fill-opacity:1;" d="M 30.210938 9.160156 C 29.859375 8.425781 29.441406 7.722656 28.976562 7.058594 C 28.847656 6.867188 28.699219 6.675781 28.5625 6.488281 C 26.804688 4.210938 24.414062 2.421875 21.628906 1.378906 C 19.882812 0.714844 17.972656 0.351562 15.980469 0.351562 C 11.058594 0.351562 6.660156 2.566406 3.785156 6.019531 C 3.253906 6.652344 2.78125 7.332031 2.355469 8.046875 C 3.515625 8.054688 4.953125 8.054688 5.117188 8.054688 C 6.59375 8.054688 8.871094 7.878906 8.871094 7.878906 C 9.640625 7.832031 9.71875 8.914062 8.960938 9.003906 C 8.960938 9.003906 8.195312 9.089844 7.34375 9.128906 L 12.480469 23.917969 L 15.566406 14.957031 L 13.378906 9.136719 C 12.609375 9.097656 11.898438 9.011719 11.898438 9.011719 C 11.132812 8.972656 11.230469 7.839844 11.980469 7.886719 C 11.980469 7.886719 14.308594 8.0625 15.695312 8.0625 C 17.171875 8.0625 19.453125 7.886719 19.453125 7.886719 C 20.210938 7.839844 20.308594 8.921875 19.539062 9.011719 C 19.539062 9.011719 18.78125 9.097656 17.933594 9.136719 L 23.019531 23.816406 L 24.429688 19.257812 C 25.140625 17.488281 25.5 16.023438 25.5 14.855469 C 25.5 13.171875 24.871094 12 24.332031 11.089844 C 23.621094 9.960938 22.953125 9.011719 22.953125 7.894531 C 22.953125 6.636719 23.933594 5.46875 25.320312 5.46875 C 25.378906 5.46875 25.441406 5.46875 25.5 5.46875 C 27.640625 5.414062 28.339844 7.46875 28.429688 8.867188 C 28.429688 8.867188 28.429688 8.898438 28.429688 8.914062 C 28.464844 9.484375 28.4375 9.902344 28.4375 10.402344 C 28.4375 11.777344 28.167969 13.335938 27.371094 15.289062 L 24.1875 24.210938 L 22.367188 29.40625 C 22.511719 29.34375 22.652344 29.277344 22.796875 29.207031 C 27.425781 27.042969 30.796875 22.722656 31.507812 17.605469 C 31.613281 16.933594 31.664062 16.246094 31.664062 15.550781 C 31.664062 13.265625 31.140625 11.097656 30.210938 9.160156 Z M 30.210938 9.160156 "/></g></svg>';
    }
}


/*
 * This function is used to display the success/error notice for settings saving. This function is not used currently.
 *
 * @input NULL
 * @return NULL
 */
if (!function_exists('wpf_admin_notice_success')) {
    function wpf_admin_notice_success()
    {
        if (isset($_GET['wpf_setting']) && $_GET['wpf_setting'] == 1) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e('Your settings have been saved!', 'wpfeedback'); ?></p>
            </div>
        <?php }

        if (isset($_GET['wpf_setting']) && $_GET['wpf_setting'] == 0) {
            ?>
            <div class="error notice is-dismissible">
                <p><?php _e('Your Settings not saved. ', 'wpfeedback'); ?></p>
            </div>
        <?php }
    }
}
//add_action( 'admin_notices', 'wpf_admin_notice_success' );

/*
 * This function is used to get the role of current logged in user. This function is not used currently.
 *
 * @input NULL
 * @return String
 */
if (!function_exists('wpf_get_current_user_roles')) {
    function wpf_get_current_user_roles()
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $roles = ( array )$user->roles;
            return $roles[0];
        } else {
            return 'Guest';
        }
    }
}

/*
 * This function is used to get the information of the requested user.
 *
 * @input Int
 * @return Array
 */
//if (!function_exists('wpf_get_current_user_information')) {
//    function wpf_get_current_user_information($author_id='')
//    {
//        $response = array();
//        if($author_id!=''){
//            $user = get_userdata($author_id);
//            $user_details = ( array )$user->data;
//            $roles = ( array )$user->roles;
//            $roles = array_values($roles);
//            $response['display_name'] = $user_details['display_name'];
//            $response['user_id'] = $user_details['ID'];
//            $response['role'] = $roles[0];
//            return $response;
//        }
//        elseif (is_user_logged_in() == true) {
//            $user = wp_get_current_user();
//            $user_details = ( array )$user->data;
//            $roles = ( array )$user->roles;
//            $roles = array_values($roles);
//            $response['display_name'] = $user_details['display_name'];
//            $response['user_id'] = $user_details['ID'];
//            $response['role'] = $roles[0];
//            return $response;
//        } else {
//            $response['display_name'] = 'Guest';
//            $response['user_id'] = 0;
//            $response['role'] = 'Guest';
//            return $response;
//        }
//    }
//}


if (!function_exists('wpf_get_current_user_information')) {
    function wpf_get_current_user_information($author_id='')
    {
        $response = array();
        if(!is_user_logged_in()){
            $wpfb_users_json = do_shortcode('[wpf_user_list_front]');
            $wpfb_users = json_decode($wpfb_users_json);
            $wpf_website_developer = !empty(get_site_data_by_key('wpf_website_client')) ? get_site_data_by_key('wpf_website_client') : 0;
        }

        if($author_id!=''){
            $user = get_userdata($author_id);
            $user_details = ( array )$user->data;
            $roles = ( array )$user->roles;
            $roles = array_values($roles);
            $response['first_name'] = $user_details['first_name'] ?? "";
            $response['last_name'] = $user_details['last_name'] ?? "";
            $response['display_name'] = $user_details['display_name'];
            $response['user_id'] = $user_details['ID'];
            $response['role'] = $roles[0];
            return $response;
        }
        elseif (is_user_logged_in() == true) {
            $user = wp_get_current_user();
            $user_details = ( array )$user->data;
            $roles = ( array )$user->roles;
            $roles = array_values($roles);

            // to ge the first and last name
            $user_meta = get_user_meta( $user_details['ID'] );
            $response['first_name'] = (!empty($user_meta['first_name'][0])) ? $user_meta['first_name'][0] : "";
            $response['last_name'] = (!empty($user_meta['last_name'][0])) ? $user_meta['last_name'][0] : "";
            
            $response['display_name'] = $user_details['display_name'];
            $response['user_id'] = $user_details['ID'];
            $response['role'] = $roles[0];
            return $response;
        } else if($wpf_website_developer!=0){
            foreach ($wpfb_users as $key => $val) {
                if ($wpf_website_developer == $key) {
                    //$val->username;

                    /**
                     * user id was not matching in graphics page when guest mode on and default user selected
                     * => v2.1.0
                     */
                    if ( intval($wpf_website_developer) > 0 ) {
                        $user = get_user_by('id', $val->username);
                        $response['first_name'] = $val->first_name ?? "";
                        $response['last_name'] = $val->last_name ?? "";
                        $response['display_name'] = $val->displayname;
                        $response['user_id'] = $wpf_website_developer;

                        if ( !empty($user->roles) ) {
                            $roles = array_values($user->roles);
                            $response['role'] = $roles[0];
                        } else {
                            $response['role'] = 'Guest';
                        }
                    } else {
                        $response['first_name'] = $val->first_name ?? "";
                        $response['last_name'] = $val->last_name ?? "";
                        $response['display_name'] = $val->displayname;
                        $response['user_id'] = 0;
                        $response['role'] = 'Guest';
                    }

                    return $response;
                }
            }

            $response['display_name'] = 'Guest';
            $response['user_id'] = 0;
            $response['role'] = 'Guest';
            return $response;
        } else {
            $response['display_name'] = 'Guest';
            $response['user_id'] = 0;
            $response['role'] = 'Guest';
            return $response;
        }
    }
}

/*
 * This function is used to get the time difference between two timestamps. It is basically used to get  the time difference between current time and the time when comment was posted.
 *
 * @input Timestamp, Timestamp
 * @return Array
 */
if (!function_exists('wpfb_time_difference')) {
    function wpfb_time_difference($datetime1, $datetime2)
    {
        $response = array();
        $interval = date_diff($datetime1, $datetime2);
        if ($interval->y == 0) {
            if ($interval->m == 0) {
                if ($interval->d == 0) {
                    if ($interval->h == 0) {
                        if ($interval->i == 0) {
                            $comment_time = $interval->s .__(' seconds ago','wpfeedback');
                        } else {
                            $comment_time = $interval->i .__(' minutes ago','wpfeedback');
                        }
                    } else {
                        $comment_time = $interval->h .__(' hours ago','wpfeedback');
                    }
                } else {
                    $comment_time = $interval->d . __(' days ago','wpfeedback');
                }
            } else {
                $comment_time = $interval->m . __(' months ago','wpfeedback');
            }
        } else {
            $comment_time = $interval->y . __(' years ago','wpfeedback');
        }
        $response['interval'] = $interval;
        $response['comment_time'] = $comment_time;
        return $response;
    }
}

/*
 * This function is used to get the listing of status and priorities inside the Tasks Center.
 *
 * @input String
 * @return String
 */
if (!function_exists('wp_feedback_get_texonomy_selectbox')) {
    function wp_feedback_get_texonomy_selectbox($my_term)
    {
	/*if (!session_id()) {
	    session_start();
	}*/
	
	//$wpf_site_id = get_option('wpf_site_id');
    $filter_data_db=get_option('filter_data')[$my_term];
    
    $disable = '';
    if($my_term == 'task_status' || $my_term == 'task_priority') {
        if ( !is_feature_enabled( 'task_center' ) ) {
            $disable = 'disabled';
        }
    }
	
	if(isset($filter_data_db) && !empty($filter_data_db)) {
	    echo '<select id="task_' . $my_term . '_attr" onchange="' . $my_term . '_changed(this);" '. $disable .'>';
            foreach ($filter_data_db as $term) {
                if($term['label']=='In Progress'){
                    $term['label'] = 'In Prog';
                }elseif ($term['label']=='Pending Review'){
                    $term['label'] = 'Pending';
                }else{
                    $term['label'] = $term['label'];
                }
                echo '<option name="' . $my_term . '" value="' . $term['value'] . '" class="wpf_task" id="wpf_' . $term['value'] . '"/>' . __($term['label'],'wpfeedback') . '</option>';
            }
            echo '</select>';
	}
    }
}

/*
 * This function is used to check if Atarim is enabled on the website.
 *
 * @input Int
 * @return Boolean
 */
if (!function_exists('wpf_check_if_enable')) {
    function wpf_check_if_enable($author_id='')
    {
        if($author_id==''){
            $current_user_information = wpf_get_current_user_information();
            $user = wp_get_current_user();
        }
        else{
            $current_user_information = wpf_get_current_user_information($author_id);
            $user = get_userdata($author_id);
        }
	
        $wpf_license = get_option('wpf_license');
        $wpf_enabled = get_site_data_by_key('enabled_wpfeedback');
    
	$wpf_selcted_role = get_site_data_by_key('wpf_selcted_role');
	
        $wpf_allow_guest = get_site_data_by_key('wpf_allow_guest');
        $selected_roles = explode(',', $wpf_selcted_role);
        
	$user_details = ( array )$user->data;
        $roles = ( array )$user->roles;
        $roles = array_values($roles);

        if ($wpf_license == 'valid' && $wpf_enabled == 'yes' && ( !empty(array_intersect($roles, $selected_roles) ) || $wpf_allow_guest == 'yes')) {
            $wpf_access_output = 1;
        }else {
            $wpf_access_output = 0;
        }
        return $wpf_access_output;
    }
}

/*
 * This function is used to get extra user settings for the Atarim.
 *
 * @input Object
 * @return NULL
 */
add_action( 'show_user_profile', 'wpf_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'wpf_show_extra_profile_fields' );
if (!function_exists('wpf_show_extra_profile_fields')) {
    function wpf_show_extra_profile_fields($user)
    {
//        $args = [];
//        $args['wpf_site_id'] = get_option('wpf_site_id');
//        $args['wpf_user_id'] = $user->ID;
//        $url = WPF_CRM_API."wp-api/wpfuser/getWpfUser";
//        $sendtocloud = json_encode($args);
//        $res = wpf_send_remote_post($url,$sendtocloud);
//        $wpf_get_user_type = "";
//        if(isset($res['data']) && $res['status'] == 200){
//            $wpf_get_user_type = $res['data']['user_type'];
//        }
	
	$wpf_get_user_type = get_user_meta($user->ID, 'wpf_user_type', true);
	
	$selected_roles = get_site_data_by_key('wpf_selcted_role');
	$selected_roles = explode(',', $selected_roles);
	
        $wpf_enabled = get_site_data_by_key('enabled_wpfeedback');
        
        if ((array_intersect($user->roles, $selected_roles) && $wpf_enabled == 'yes') || current_user_can('administrator'))       {
            $notifications_html = wpf_get_allowed_notification_list($user->ID, 'no');
            ?>
            <h3><?php _e('Collaborate Information', 'wpfeedback'); ?></h3>

            <table class="form-table wpf_fields">
                <tr>
                    <th><label for="wpf_user_type"><?php _e("User Type",'wpfeedback'); ?></label></th>
                    <td>
                        <select id="wpf_user_type" name="wpf_user_type">
                            <option value="" <?php if ($wpf_get_user_type == '') {
                                echo 'selected';
                            } ?>><?php _e('Select','wpfeedback') ?>
                            </option>
                            <option value="king" <?php if ($wpf_get_user_type == 'king') {
                                echo 'selected';
                            } ?>><?php echo !empty(get_site_data_by_key('wpf_customisations_client')) ? get_site_data_by_key('wpf_customisations_client') : 'Client (Website Owner)'; ?></option>
                            <option value="advisor" <?php if ($wpf_get_user_type == 'advisor') {
                                echo 'selected';
                            } ?>><?php echo !empty(get_site_data_by_key('wpf_customisations_webmaster')) ? get_site_data_by_key('wpf_customisations_webmaster') : 'Webmaster'; ?></option>
                            <option value="council" <?php if ($wpf_get_user_type == 'council') {
                                echo 'selected';
                            } ?>><?php echo !empty(get_site_data_by_key('wpf_customisations_others')) ? get_site_data_by_key('wpf_customisations_others') : 'Others'; ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="city"><?php _e("Email notifications",'wpfeedback'); ?></label></th>
                    <td>
                        <?php echo $notifications_html; ?>
                    </td>
                </tr>
            </table>
        <?php }
    }
}

/*
 * This function is used to save the extra user settings for the Atarim.
 *
 * @input Int
 * @return Boolean
 */
add_action( 'personal_options_update', 'wpf_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'wpf_save_user_profile_fields' );
if (!function_exists('wpf_save_user_profile_fields')) {
    function wpf_save_user_profile_fields($user_id)
    {
        syncUsers();
	
	// update_user_meta($user->ID, 'wpf_user_type', $_POST['wpf_user_type']);
	
        $user = get_userdata( $user_id );
	$selected_roles = get_site_data_by_key('wpf_selcted_role');
	$selected_roles = explode(',', $selected_roles);
	
        $wpf_enabled = get_site_data_by_key('enabled_wpfeedback');
        
        $args = array(
            'wpf_site_id' => get_option('wpf_site_id'),
            'wpf_id' => $_POST['user_id'],
            'username' => $user->data->user_login,
            'wpf_email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'wpf_user_type' => $_POST['wpf_user_type'],
            // 'role' => $_POST['wpf_user_type']
//            'role' => $_POST['role']
        );

        update_user_meta($user->ID, 'wpf_user_type', $_POST['wpf_user_type']);

        if ((array_intersect($user->roles, $selected_roles) && $wpf_enabled == 'yes' && current_user_can('edit_user', $user_id)) || current_user_can('administrator')) {
            /*if ($_POST['wpf_user_type'] && ($_POST['wpf_user_type']=='king' || $_POST['wpf_user_type']=='advisor' || $_POST['wpf_user_type']=='council')) {
                $args['user_type'] = $_POST['wpf_user_type'];
            }else{
                $args['user_type'] = "";
            }*/

            $options  = []; 
            $options['wpf_site_id'] = get_option('wpf_site_id');
            $options['wpf_every_new_task'] = isset($_POST['wpf_every_new_task']) ? 1 : 0;
            $options['wpf_every_new_comment'] = isset($_POST['wpf_every_new_comment']) ? 1 : 0;
            $options['wpf_every_new_complete'] = isset($_POST['wpf_every_new_complete']) ? 1 : 0;
            $options['wpf_every_status_change'] = isset($_POST['wpf_every_status_change']) ? 1 : 0;
            $options['wpf_daily_report'] = isset($_POST['wpf_daily_report']) ? 1 : 0;
            $options['wpf_weekly_report'] = isset($_POST['wpf_weekly_report']) ? 1 : 0;
            $options['wpf_auto_daily_report'] = isset($_POST['wpf_auto_daily_report']) ? 1 : 0;
            $options['wpf_auto_weekly_report'] = isset($_POST['wpf_auto_weekly_report']) ? 1 : 0;
	    
	    $args['notifications'] = $options;
	    
	    $url = WPF_CRM_API.'wp-api/wpfuser/update';
            $res = wpf_send_remote_post($url,json_encode($args));
        }else{
            return false;
        } 
    }
}

// sync users
function wpf_sync_users( $user_id ) {
    syncUsers();
}
add_action('user_register','wpf_sync_users');
add_action( 'deleted_user', 'wpf_sync_users', 10 );
add_action( 'profile_update', 'wpf_sync_users', 10 );

// sync pages
function wpf_sync_posts($post_id) {
    syncPages();
}
add_action('save_post', 'wpf_sync_posts');
add_action( 'delete_post', 'wpf_sync_posts', 10 );

/* update WP pages into API */
function syncPages() {
    $pages = wpf_get_page_list('api');
    $args = [];
    $args['wpf_site_id'] = get_option('wpf_site_id');
    $args['responseBody'] = json_decode($pages);
    $url = WPF_CRM_API."wp-api/sync/pages";
    $sendtocloud = json_encode($args);
    $res = wpf_send_remote_post($url,$sendtocloud);
}

/* update WP users into API */
function syncUsers() {
    $users = wpf_api_func_get_users();
    $args = [];
    $args['wpf_site_id'] = get_option('wpf_site_id');
    $args['responseBody'] = json_decode($users);
    
    // get all the user ID
    $wp_users = get_users( array( 'fields' => array( 'ID' ) ) );

    // flaten the data to a single array and added to the request
    $args['wpf_wp_user_ids'] = array_map( function($user) {
         return $user->ID;
     }, $wp_users );     

    $url = WPF_CRM_API."wp-api/sync/users";
    $sendtocloud = json_encode($args);
    $res = wpf_send_remote_post($url,$sendtocloud);
    if(isset($res['status']) == 200) {
        get_notif_sitedata_filterdata();
    }
}


function syncSite(){
    $url = WPF_CRM_API.'wp-api/wpfsite/update';
    $response = array();
    $response['wpf_site_id']=get_option('wpf_site_id');
    $response['url']=get_option('siteurl');
    $response['name']=get_option('blogname');
    $body = json_encode($response);
    $res = wpf_send_remote_post($url,$body);
}

add_action( 'update_option_blogname', function( $old_value, $new_value ) {
    syncSite();
}, 10, 2); 

add_action( 'update_option_siteurl', function( $old_value, $new_value ) {
    syncSite();
}, 10, 2); 

/*
 * This function is used to get the allowed notification list to be displayed on the users extra settings.
 *
 * @input Int, String
 * @return String
 */
if (!function_exists('wpf_get_allowed_notification_list')) {
    function wpf_get_allowed_notification_list($userid, $default = 'no')
    {
        /*global $current_user;
        $user = $current_user;*/
	/*if(!session_id()){
            session_start();    
        }*/
	
	$wpf_site_id = get_option('wpf_site_id');

        $args = [];
        $args['wpf_site_id'] = $wpf_site_id;
        $args['wpf_user_id'] = $userid;
        $url = WPF_CRM_API."wp-api/wpfuser/getWpfUser";
        $sendtocloud = json_encode($args);
        $res = wpf_send_remote_post($url,$sendtocloud);

	/*if($res['status'] = 200 && isset($res['data'])){
	    $_SESSION[$wpf_site_id]['user_data'] = $res['data'];
	} else {
	    if(isset($_SESSION[$wpf_site_id]["user_data"])){
		unset($_SESSION[$wpf_site_id]["user_data"]);
	    }
	}*/
        
        $response = '';
        $wpf_every_new_task = get_site_data_by_key('wpf_every_new_task');
        $wpf_every_new_comment = get_site_data_by_key('wpf_every_new_comment');
        $wpf_every_new_complete = get_site_data_by_key('wpf_every_new_complete');
        $wpf_every_status_change = get_site_data_by_key('wpf_every_status_change');
        $wpf_daily_report = get_site_data_by_key('wpf_daily_report');
        $wpf_weekly_report = get_site_data_by_key('wpf_weekly_report');
        $wpf_auto_daily_report = get_site_data_by_key('wpf_auto_daily_report');
        $wpf_auto_weekly_report = get_site_data_by_key('wpf_auto_weekly_report');
        //re($res);
        if ($wpf_every_new_task == 'yes') {
            if (isset($res['data']['preference']['every_new_task']) && $res['data']['preference']['every_new_task'] == 1) {
                $checked = 'checked';
            } else {
                $checked = '';
            }
            if ($default == 'yes') {
                $checked = 'checked';
            }
            $response .= '<div><input type="checkbox" name="wpf_every_new_task" value="yes" class="wpf_checkbox"
                           id="wpf_every_new_task" ' . $checked . ' /><label class="wpf_checkbox_label" for="wpf_every_new_task">' . __('Receive email notification for every new task', 'wpfeedback') . '</label></div>';
        }
        if ($wpf_every_new_comment == 'yes') {
            if (isset($res['data']['preference']['every_new_comment']) && $res['data']['preference']['every_new_comment'] == 1) {
                $checked = 'checked';
            } else {
                $checked = '';
            }
            if ($default == 'yes') {
                $checked = 'checked';
            }
            $response .= '<div><input type="checkbox" name="wpf_every_new_comment" value="yes" class="wpf_checkbox"
                           id="wpf_every_new_comment" ' . $checked . ' /><label class="wpf_checkbox_label" for="wpf_every_new_comment">' . __('Receive email notification for every new comment', 'wpfeedback') . '</label></div>';
        }
        if ($wpf_every_new_complete == 'yes') {
            if (isset($res['data']['preference']['wpf_every_new_complete']) && $res['data']['preference']['wpf_every_new_complete'] == 1) {
                $checked = 'checked';
            } else {
                $checked = '';
            }
            if ($default == 'yes') {
                $checked = 'checked';
            }
            $response .= '<div><input type="checkbox" name="wpf_every_new_complete" value="yes" class="wpf_checkbox"
                           id="wpf_every_new_complete" ' . $checked . ' /><label class="wpf_checkbox_label" for="wpf_every_new_complete">' . __('Receive email notification when a task is marked as complete', 'wpfeedback') . '</label></div>';
        }
        if ($wpf_every_status_change == 'yes') {
            if (isset($res['data']['preference']['every_status_change']) && $res['data']['preference']['every_status_change'] == 1) {
                $checked = 'checked';
            } else {
                $checked = '';
            }
            if ($default == 'yes') {
                $checked = 'checked';
            }
            $response .= '<div><input type="checkbox" name="wpf_every_status_change" value="yes" class="wpf_checkbox"
                           id="wpf_every_status_change" ' . $checked . ' /><label class="wpf_checkbox_label" for="wpf_every_status_change">' . __('Receive email notification for every status change', 'wpfeedback') . '</label></div>';
        }
        if($wpf_daily_report=='yes'){
            if (isset($res['data']['preference']['daily_report']) && $res['data']['preference']['daily_report'] == 1) {
                $checked='checked';
            }
            else{
                $checked='';
            }
            if($default=='yes'){
                $checked='checked';
            }
            $response.='<div><input type="checkbox" name="wpf_daily_report" value="yes" class="wpf_checkbox"
                               id="wpf_daily_report" '.$checked.' /><label class="wpf_checkbox_label" for="wpf_daily_report">'.__('Receive email notification for  daily report', 'wpfeedback').'</label></div>';
        }
        if($wpf_weekly_report=='yes'){
            if (isset($res['data']['preference']['weekly_report']) && $res['data']['preference']['weekly_report'] == 1) {
                $checked='checked';
            }
            else{
                $checked='';
            }
            if($default=='yes'){
                $checked='checked';
            }
            $response.='<div><input type="checkbox" name="wpf_weekly_report" value="yes" class="wpf_checkbox"
                            id="wpf_weekly_report" '.$checked.' /><label class="wpf_checkbox_label" for="wpf_weekly_report">'.__('Receive email notification for weekly report', 'wpfeedback').'</label></div>';
        }
        if ( is_feature_enabled( 'auto_reports' ) ) {
            
            if ($wpf_auto_daily_report == 'yes') {
                if (isset($res['data']['preference']['wpf_auto_daily_report']) && $res['data']['preference']['wpf_auto_daily_report'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                if ($default == 'yes') {
                    $checked = 'checked';
                }
                $response .= '<div><input type="checkbox" class="wpf_checkbox" name="wpf_auto_daily_report" value="yes" id="wpf_auto_daily_report" ' . $checked . ' /><label class="wpf_checkbox_label" for="wpf_auto_daily_report">' . __('Auto receive email notification for daily report', 'wpfeedback') . '</label></div>';
            }
            if ($wpf_auto_weekly_report == 'yes') {
                if (isset($res['data']['preference']['wpf_auto_weekly_report']) && $res['data']['preference']['wpf_auto_weekly_report'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                if ($default == 'yes') {
                    $checked = 'checked';
                }
                $response .= '<div><input type="checkbox" name="wpf_auto_weekly_report" value="yes" class="wpf_checkbox"
                            id="wpf_auto_weekly_report" ' . $checked . ' /><label class="wpf_checkbox_label" for="wpf_auto_weekly_report">' . __('Auto receive email notification for weekly report', 'wpfeedback') . '</label></div>';
            }
        }

        return $response;
    }
}

/*
 * This function is used to get the Atarim user type.
 *
 * @input NULL
 * @return String
 */
if (!function_exists('wpf_user_type')) {
    function wpf_user_type()
    {
	  global $current_user;
//        if(!session_id()){
//            session_start();    
//        }
//	
//	$wpf_site_id = get_option('wpf_site_id');
//        

//        $wpf_get_user_type = '';
//        if(isset($_SESSION[$wpf_site_id]['user_data']['user_type'])) {
//            $wpf_get_user_type = $_SESSION[$wpf_site_id]['user_data']['user_type'];
//        } else { // call the api to get user type
//            
//            $args = [];
//            $args['wpf_site_id'] = $wpf_site_id;
//            $args['wpf_user_id'] = $current_user->ID;
//            $url = WPF_CRM_API."wp-api/wpfuser/getWpfUser";
//            $sendtocloud = json_encode($args);
//            $res = wpf_send_remote_post($url,$sendtocloud);
//            if(isset($res['data']) && $res['status'] == 200){
//                $wpf_get_user_type = $res['data']['user_type'];
//            }
//        }
        return get_user_meta($current_user->ID, 'wpf_user_type', true);
    }
}

/*
 * This function is used to verify if the uploaded file is valid or not.
 *
 * @input File
 * @return Boolean
 */
if (!function_exists('wpf_verify_file_upload')) {
    function wpf_verify_file_upload($server, $file_data)
    {
        $allowed_file_types = array('image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain', 'application/octet-stream', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','video/webm','video/mp4','video/mov','video/wmv','video/avi','font/ttf','text/plain');
            if (function_exists('finfo_open')) {
                // $response=0;
                $imgdata = base64_decode($file_data);
                $f = finfo_open();

                $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
                if (in_array($mime_type, $allowed_file_types)) {
                    $response = 0;
                } else {
                    $response = 1;
                }
            } else {
                $response = 0;
            }
        return $response;
    }
}

/*
 * This function is used to verify if the uploaded file extension is proper or not.
 *
 * @input String
 * @return Boolean
 */
if (!function_exists('wpf_verify_file_upload_type')) {
    function wpf_verify_file_upload_type($server, $mime_type)
    {
        $allowed_file_types = array('application/msword','image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain', 'application/octet-stream', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','video/webm','video/mp4','video/quicktime','video/x-ms-wmv','video/avi','font/ttf','text/plain');
            if (!empty($mime_type)) {
                if (in_array($mime_type, $allowed_file_types)) {
                    $response = 0;
                } else {
                    $response = 1;
                }
            } else {
                $response = 0;
            }
        return $response;
    }
}

/*
 * This function is used to get the list of the pages on the website. It is called to get the dropdown list when creating a general task from backend.
 *
 * @input String
 * @return JSON
 */
if (!function_exists('wpf_get_page_list')) {
    function wpf_get_page_list($type='task_page')
    {
        $response = array();

        if ( class_exists( 'WooCommerce' ) ) {
            $wpf_default_wp_post_types = array("page" => "page", "post" => "post", "product" => "product");
        }else {
            $wpf_default_wp_post_types = array("page" => "page", "post" => "post");
        }

        $wpf_wp_cpts = get_post_types(array('public' => true, 'exclude_from_search' => true, '_builtin' => false));
        $wpf_post_types = array_merge($wpf_default_wp_post_types, $wpf_wp_cpts);


        foreach ($wpf_post_types as $wpf_post_type) {
            $objType = get_post_type_object($wpf_post_type);
            if($wpf_post_type == 'page'){
                $numberposts = -1;
            }
            else{
                $numberposts = 10;
            }
            $wpf_temp_arg = array(
                'post_type' => $wpf_post_type,
                'numberposts' => $numberposts,
            );
            $posts = get_posts($wpf_temp_arg);
            $wpf_count_post = count($posts);
            if ($wpf_count_post) {
                foreach ($posts as $post) {
                    if($type=='task_page') {
                        $response[$objType->labels->singular_name][$post->ID] = htmlspecialchars($post->post_title, ENT_QUOTES, 'UTF-8');
                    }
                    else{
                        $temp_res = array(
                            'id' => $post->ID,
                            'name' => htmlspecialchars($post->post_title, ENT_QUOTES, 'UTF-8'),
                            'type' => $objType->labels->singular_name,
                            'url'   => get_permalink($post->ID)
                        );
                        $response[] = $temp_res;
                    }
                }
            }
        }
        return json_encode($response);
    }
}

/*
 * This function is used to deregister the scripts of the plugins that are conflicting with the Atarim.
 *
 * @input NULL
 * @return NULL
 */
if (!function_exists('wpf_mootools_deregister_javascript')) {
    function wpf_mootools_deregister_javascript()
    {
        if (!is_admin()) {
            wp_deregister_script('mootools-local');
            wp_deregister_script('enlighter-local');
            wp_deregister_script('dct-carousel-jquery');
            wp_deregister_script('onepress-js-plugins');
        }
    }
}
add_action( 'wp_print_scripts', 'wpf_mootools_deregister_javascript', 99 );


/*
 * This function is used to strip all the code elements from the data.
 *
 * @input String
 * @return String
 */
if (!function_exists('wpf_test_input')) {
    function wpf_test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

/*
 * This function is used by all the ajax request to make user that they are coming from authentic source.
 *
 * @input NULL
 * @return String
 */
function wpf_security_check(){
    $roles = array();
    $user = wp_get_current_user();
    $user_details = ( array )$user->data;
    $roles = ( array )$user->roles;
    $roles = array_values($roles);
    if ( ! check_ajax_referer( 'wpfeedback-script-nonce', 'wpf_nonce' ) ) {
        echo 'Invalid security token sent.';
        wp_die();
    }
    else{
        $selected_roles = get_site_data_by_key('wpf_selcted_role');
        $selected_roles = explode(',', $selected_roles);
        if (!in_array("administrator", $selected_roles)) {
            array_push($selected_roles,"administrator");
        }
        $wpf_allow_guest = get_site_data_by_key('wpf_allow_guest');
        if($wpf_allow_guest=='yes'){
            $selected_roles[]='Guest';
            $roles[] = 'Guest';
        }

        if(empty(array_intersect($roles, $selected_roles))){
            echo 'Invalid user.';
            wp_die();
        }
/*        if(!in_array($currnet_user_information['role'], $selected_roles) ){
            echo 'Invalid user.';
            wp_die();
        }*/
    }
}

add_filter( 'get_comment_text', 'make_clickable', 99 );

/*
 * This function is to get the listing of status and priority for the sidebar filters.
 *
 * @input String
 * @return String
 */
if (!function_exists('wp_feedback_get_texonomy_filter')) {
    function wp_feedback_get_texonomy_filter($my_term)
    {
        $output = '';
	
	/*if (!session_id()) {
	    session_start();
	}*/
	
	//$wpf_site_id = get_option('wpf_site_id');
    $filter_data_db=get_option('filter_data')[$my_term];
	
	if(isset($filter_data_db) && !empty($filter_data_db)) {
	    $output .=  '<ul class="wpf_filter_checkbox" id="wpf_sidebar_filter_'.$my_term.'">';
            foreach ($filter_data_db as $term) {
                if($term['label']=='In Progress'){
                    $term['label'] = 'In Prog';
                }elseif ($term['label']=='Pending Review'){
                    $term['label'] = 'Pending';
                }else{
                    $term['label'] = $term['label'];
                }
                $output .= '<li><input type="checkbox" name="wpf_filter_' . $my_term . '" value="' . $term['value'] . '" class="wp_feedback_task wpf_checkbox" id="wpf_sidebar_filter_' . $term['value'] . '" /><label for="wpf_sidebar_filter_' . $term['value'] . '" class="wpf_checkbox_label">' . __($term['label'], 'wpfeedback') . '</label></li>';
            }
            $output .= '</ul><a class="wpf_sidebar_filter_reset_'.$my_term.'" href="javascript:void(0)">'.__('Reset', 'wpfeedback').'</a>';
            return $output;
	}
    }
}

/*
 * This function is used to encrypt and decrypt the license key.
 *
 * @input String, String
 * @return String
 */
function wpf_crypt_key( $string, $action = 'e' ) {
    //return $string;

    if(strlen($string)==32){
        return $string;
    }
    $wpf_decr_key = get_option('wpf_decr_key');
    $wpf_decr_checksum = get_option('wpf_decr_checksum');

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $wpf_decr_key );
    $iv = substr( hash( 'sha256', $wpf_decr_checksum ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        update_option('wpf_license_key',$output,'no');
    }

    return $output;
}

/*========WPF Login Form========*/
/*
 * This function is used to get the login form.
 *
 * @input NULL
 * @return String
 */
function wpf_login_form(){
    $output = '';
    $wpf_enabled = get_site_data_by_key('enabled_wpfeedback');
    if(!is_user_logged_in() && $wpf_enabled == 'yes'){
        $output = '<div id="login_form_content"><p><b>Dive straight into the feedback!</b></br>Login below and you can start commenting using your own user instantly</p><form id="wpf_login" method="post"><div class="wpf_user"><label for="username"></label><input id="username" placeholder="Username OR Email Address" type="text" name="username"></div><div class="wpf_password"><label for="password"></label><input id="password" placeholder="Password" type="password" name="password"></div>'. wp_nonce_field( 'wpfeedback-script-nonce', 'wpf_security', true, false ).'<input class="wpf_submit_button" type="submit" value="Login and start commenting" name="submit"><p class="wpf_status"></p></form></div>';
    }
    return $output;
}
add_shortcode('wpf_login_form','wpf_login_form');

/* function wpf_error_message(){
        $output = '<div id="curl_error_content"><p><b>CURL timeout error!</b></p></br><p>Please refresh the page</p></div>';
    return $output;
}
add_shortcode('wpf_error_message','wpf_error_message'); */


// used for tracking error messages
/*
 * This function is used to manage the errors generated while logging in from Atarim login modal.
 *
 * @input NULL
 * @return String
 */
function wpf_user_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

/*
 * This function is used to handle login from the Atarim login modal.
 *
 * @input NULL
 * @return JSON
 */
function wpf_ajax_login(){
    global $wpdb;
    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'wpfeedback-script-nonce', 'wpf_security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;
    $user_name = $_POST['username'];
    $user_login ='';
    $user = '';
    //$user = get_userdatabylogin($_POST['username']);
    $resultsap = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}users WHERE user_login = %s OR user_email = %s limit 1", $user_name, $user_name ) , ARRAY_A);
    if($resultsap){
        $user_login = isset($resultsap[0]['user_login']) ? $resultsap[0]['user_login'] : "";
        // this returns the user_login name and other info from the user name
        $user = get_user_by('login', $user_login);
    }
    if(!$user) {
        // if the user name doesn't exist
        wpf_user_errors()->add('empty_username', __('Invalid username'));
    }

    if(!isset($_POST['password']) || $_POST['password'] == '') {
        // if no password was entered
        wpf_user_errors()->add('empty_password', __('Please enter a password'));
    }

    // check the user's login with their password
    if(!wp_check_password($_POST['password'], $user->user_pass, $user->ID)) {
        // if the password is incorrect for the specified user
        wpf_user_errors()->add('empty_password', __('Incorrect password'));
    }
    // retrieve all error messages
    $errors = wpf_user_errors()->get_error_messages();
    //$user_signon = wp_signon( $info, false );
    if ( !empty($errors) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
    } else {
//        wp_setcookie($user_login, $_POST['password'], true);  
    wp_set_auth_cookie($user->ID, true);
    wp_set_current_user($user->ID, $_POST['username']);
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
    }
    die();
}
// Enable the user with no privileges to run ajax_login() in AJAX
add_action( 'wp_ajax_nopriv_wpf_ajaxlogin', 'wpf_ajax_login' );

/*
 * This function is used to start the color picker.
 *
 * @input NULL
 * @return NULL
 */
if(!(isset($_GET['ct_builder'])) && !(isset($_GET['ct_inner']))){	
    add_action( 'wp_enqueue_scripts', 'wpf_enqueue_color_picker' );
    function wpf_enqueue_color_picker( $hook_suffix ) {
        wp_enqueue_style( 'wp-color-picker' );
        if(!(isset($_GET['et_tb'])) && !(isset($_GET['et_fb']))){
            wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
        }
    }
}


/* add_action( 'wp_enqueue_scripts', 'wpf_enqueue_color_picker' );
function wpf_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
} */

/*=====================Graphics feature Start=====================*/
/*
 * This function is used to get the list of the graphics version on the graphics cpt.
 *
 * @input Int
 * @return String
 */
function wpf_grapgics_version_list($dataver,$post_id){
    //global $post;
        $data=$dataver;
    //$data = get_graphic_data($post_id);
    $output='';
    $i = '';
    $all_graphics_ids_array = array();
    //$get_current_graphics = $current_version;
    $output =  '<select id="wpf_graphics_version" onchange="change_graphics_version(this)">';
    
    if(isset($data['image']) && count($data['image']) > 0){
        $i = 0;
        foreach ($data['image'] as $all_graphics_id) {
            $selected = "";
            if($i == 0){
                $selected = "selected";
            }
            $output .= '<option name="wpf_graphics_id" value="' . $all_graphics_id['version'] . '" class="wpf_graphics_version"  data-src="'.$all_graphics_id['image'].'" '.$selected.'> ' .__("Version","wpfeedback") . " " .$all_graphics_id['version'].' </option>';
            $i++;
        }
    }
    
    $output .= '</select>';
    return $output;
}

/*
 * This function is used to define all the tags and theme color for Atarim.
 *
 * @input NULL
 * @return NULL
 */
function wpf_all_tags(){
    $task_list_tags_array = '';
    $url = WPF_CRM_API . 'wp-api/list/tagsBySiteID';

    $sendarr = array();
    $sendarr["wpf_site_id"] = get_option('wpf_site_id');
    $sendtocloud = json_encode($sendarr);
    $res = wpf_send_remote_post($url, $sendtocloud);
    if ( isset($res['data']) && $res['status'] == 200) {
        $task_list_tags_array .= implode('","',$res['data']);
    }?>
    <script type="text/javascript">var wpf_all_tags = ["<?php echo $task_list_tags_array; ?>"]; </script>
    <style type="text/css">
        :root {
            --main-wpf-color: #<?php echo (get_site_data_by_key('wpfeedback_color') != "") ? str_replace('#','',get_site_data_by_key('wpfeedback_color')) : "002157"; ?>;
        }
    </style>
    <?php 
}
add_action('wp_footer','wpf_all_tags');
add_action('admin_footer','wpf_all_tags');

/*
 * This function is used to get the unique elements from the object.
 *
 * @input Array, Boolean
 * @return Array
 */
function wpf_object_array_unique($array, $keep_key_assoc = false){
    $duplicate_keys = array();
    $tmp = array();

    foreach ($array as $key => $val){
        if (is_object($val))
            $val = (array)$val;

        if (!in_array($val, $tmp))
            $tmp[] = $val;
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key)
        unset($array[$key]);

    return $keep_key_assoc ? $array : array_values($array);
}

/*
 * This function is used to check if the caching plugin is active on the website and deregister the Atarim CSS and JS if found.
 *
 * @input NULL
 * @return NULL
 */
function wpf_check_for_caching_plugin() {
    if ( is_plugin_active('wp-rocket/wp-rocket.php') ) {
        $wp_rocket_settings = get_option('wp_rocket_settings');
        $wp_rocket_settings['exclude_css'][] = plugins_url().'/atarim-client-interface-plugin/css/(.*).css';
        $wp_rocket_settings['exclude_js'][] = plugins_url().'/atarim-client-interface-plugin/js/(.*).js';
        if(get_option('wpr_check')==""){
            update_option('wp_rocket_settings',$wp_rocket_settings);
            update_option('wpr_check','true');
        }
    }

    if ( is_plugin_active('fast-velocity-minify/fvm.php') ) {
        $wpf_fvm_options = get_option('fastvelocity_min_ignorelist');
        $wpf_update_fastvelocity_option = get_option('wpf_update_fastvelocity_option');
        $wpf_fvm_options = explode(PHP_EOL, $wpf_fvm_options);
        array_push($wpf_fvm_options,'/atarim-client-interface-plugin/');
        if($wpf_update_fastvelocity_option != 'yes'){
            update_option('wpf_update_fastvelocity_option', 'yes','no');
            update_option('fastvelocity_min_ignorelist', implode(PHP_EOL, $wpf_fvm_options));

        }
    }
    if ( is_plugin_active('breeze/breeze.php') ) {
        $get_breeze_advanced_settings = get_option('wpf_update_breeze_option');
        $breeze_advanced_settings = get_option('breeze_advanced_settings');
        $breeze_advanced_settings['breeze-exclude-css'][] = '/wp-content/plugins/atarim-client-interface-plugin/css/(.*).css';
        $breeze_advanced_settings['breeze-exclude-js'][] = '/wp-content/plugins/atarim-client-interface-plugin/js/(.*).js';
        if($get_breeze_advanced_settings != 'yes'){
        update_option('breeze_advanced_settings',$breeze_advanced_settings);
        update_option('wpf_update_breeze_option', 'yes','no');
        }
    }
    if (defined('WPFC_WP_PLUGIN_DIR')) {
        $rules_std = array();

        $new_rule1 = new stdClass;
        $new_rule2 = new stdClass;
        $new_rule1->prefix = "contain";
        $new_rule1->content = "wp-content/plugins/atarim-client-interface-plugin/css";
        $new_rule1->type = "css";
        $new_rule2->prefix = "contain";
        $new_rule2->content = "wp-content/plugins/atarim-client-interface-plugin/js";
        $new_rule2->type = "js";

        $wpfeedback_WpFastestCache_save = get_option("wpf_WpFastestCache_option");
        if($wpfeedback_WpFastestCache_save != 'true'){
            $get_rules_json = get_option("WpFastestCacheExclude");
            if($get_rules_json === false) {
                array_push($rules_std, $new_rule1);
                array_push($rules_std, $new_rule2);
                update_option("WpFastestCacheExclude", json_encode($rules_std), "yes");
                update_option("wpf_WpFastestCache_option", 'true', "no");
            }else{
                $rules_std = json_decode($get_rules_json);

                if(!is_array($rules_std)){
                    $rules_std = array();
                }
                array_push($rules_std, $new_rule1);
                array_push($rules_std, $new_rule2);
                update_option("WpFastestCacheExclude", json_encode($rules_std), "yes");
                update_option("wpf_WpFastestCache_option", 'true', "no");
            }
        }
    }
}
add_action( 'admin_init', 'wpf_check_for_caching_plugin' );

/*
 * This function is used to get the count of comments for post.
 *
 * @input Int, Int
 * @return Int
 */
add_action( 'wp_count_comments','wpfeedback_filter_comment_count', 20, 2 );
function wpfeedback_filter_comment_count( $stats, $post_id ) {
    global $wpdb;

    if ( 0 === $post_id ) {
        $stats = wpfeedback_get_comment_count();
    }

    return $stats;
}

/*
 * This function is used to get the count of the comment.
 *
 * @input NULL
 * @return Int
 */
function wpfeedback_get_comment_count() {
    global $wpdb;

    $stats = get_transient( 'wpfeedback_comment_count' );
    if ( ! $stats ) {
        $stats = array();

        $count = $wpdb->get_results( "SELECT comment_approved, COUNT( * ) AS num_comments FROM {$wpdb->comments} WHERE comment_type NOT IN('wp_feedback') GROUP BY comment_approved", ARRAY_A );

        $total = 0;
        $stats = array();
        $approved = array( '0' => 'moderated', '1' => 'approved', 'spam' => 'spam', 'trash' => 'trash', 'post-trashed' => 'post-trashed' );

        foreach ( (array) $count as $row ) {
            // Don't count post-trashed toward totals
            if ( 'post-trashed' != $row['comment_approved'] && 'trash' != $row['comment_approved'] ) {
                $total += $row['num_comments'];
            }
            if ( isset( $approved[ $row['comment_approved'] ] ) ) {
                $stats[ $approved[ $row['comment_approved'] ] ] = $row['num_comments'];
            }
        }

        $stats['total_comments'] = $total;
        $stats['all']            = $total;

        foreach ( $approved as $key ) {
            if ( empty( $stats[ $key ] ) ) {
                $stats[ $key ] = 0;
            }
        }

        $stats = (object) $stats;
        set_transient( 'wpfeedback_comment_count', $stats );
    }

    return $stats;
}

/*
 * This function is used create the options for the bulk updates of status and priority.
 *
 * @input String
 * @return String
 */
if (!function_exists('wpf_bulk_update_get_texonomy_selectbox')) {
    function wpf_bulk_update_get_texonomy_selectbox($my_term)
    {
	
	/*if (!session_id()) {
	    session_start();
	}*/
	
	//$wpf_site_id = get_option('wpf_site_id');
    $filter_data_db=get_option('filter_data')[$my_term];
	
	if(isset($filter_data_db) && !empty($filter_data_db)) {
	     echo '<select id="task_' . $my_term . '_attr" ><option name="' . $my_term . '" value="" class="wpf_task" id="wpf_critical">'. __("Select Option","wpfeedback").'</option>';
            foreach ($filter_data_db as $term) {
                if($term['label']=='In Progress'){
                    $term['label'] = 'In Prog';
                }elseif ($term['label']=='Pending Review'){
                    $term['label'] = 'Pending';
                }else{
                    $term['label'] = $term['label'];
                }
                echo '<option name="' . $my_term . '" value="' . $term['value'] . '" class="wpf_task" id="wpf_' . $term['value'] . '"/>' . __($term['label'],'wpfeedback') . '</option>';
            }
            echo '</select>';
	}
    }
}

/*
 * This function is used to remove the CSS of Atarim on Graphics Page if Astra Theme is detected.
 *
 * @input NULL
 * @return String
 */
add_filter( 'style_loader_src', 'wpf_remove_astra_css_on_grapgics_page',10,1);
function wpf_remove_astra_css_on_grapgics_page($href){
    if(get_query_var('is_graphic_page')){
        if (strpos($href, "style.min.css") == true) {
            return $href ='';
        }
    }
    return $href;
};

/*
 * this function is used to get
 * graphic data by id
 */

function get_graphic_data($id) {
    $url = WPF_CRM_API . 'wp-api/graphic/getDetails';

    $sendarr = array();
    $sendarr["wpf_site_id"] = get_option('wpf_site_id');
    $sendarr["graphic_id"] = $id;
    $sendtocloud = json_encode($sendarr);
    $response = wpf_send_remote_post($url, $sendtocloud);
    $data = [];
    if($response['status'] == 200) {
	   $data = $response['data'];
    }
    return $data;
}

/*
 * function is used to generate
 * bottom task panel
 */
add_action('wpf_generate_bottom_part_html', 'generate_bottom_part_html');
function generate_bottom_part_html() {

    global $wpf_task_status_filter_btn, $wpf_task_priority_filter_btn;

    if ( is_feature_enabled( 'bottom_bar_enabled' ) ) {

        $current_page_id = get_the_ID();
        if ($current_page_id == '') {
            if (isset($wp_query->post->ID)) {
                $current_page_id = $wp_query->post->ID;
            }
        }

        $current_user = wp_get_current_user();
        $wpf_user_name = $current_user->display_name; //['display_name']; //->user_nicename;
        $wpf_user_email = $current_user->user_email; //['user_email']; //->user_email;

        $url = WPF_CRM_API . 'wp-api/page/is-approved';
        $sendarr = array();
        $sendarr["wpf_site_id"] = get_option('wpf_site_id');
        $sendarr["page_id"] = $current_page_id;
        $sendtocloud = json_encode($sendarr);
        $response = wpf_send_remote_post($url, $sendtocloud);
        $is_approved = 0;
        $data = [];

        if(isset($response['status']) && $response['status']) {
        $is_approved = 1;
        }
        
        $currnet_user_information = wpf_get_current_user_information();
        $current_role = $currnet_user_information['role'];
        // $current_user_name = $currnet_user_information['display_name'];
        $current_user_name = (!empty($currnet_user_information['first_name'])) ? $currnet_user_information['first_name'] : $currnet_user_information['display_name'];
        $current_user_id = $currnet_user_information['user_id'];
        
        if ($current_user_name == 'Guest') {
        $wpf_current_role = 'guest';
        } else {
        $wpf_current_role = wpf_user_type();
        }
        $current_user_name = addslashes($current_user_name);
        
        $wpf_powered_class = '_blank';
        $wpf_powered_by = get_site_data_by_key('wpfeedback_powered_by');
        $wpf_powerbylink = WPF_MAIN_SITE_URL . '/reviews/?website=' . get_bloginfo('name').'&email='. $wpf_user_email.'&nameu='. $wpf_user_name;
        $wpf_powerbylogo = get_wpf_logo();
        if ($wpf_powered_by == 'yes') {
        $wpf_powered_class = '_self';
        $wpf_powered_link = get_site_data_by_key('wpf_powered_link');
        if ($wpf_powered_link != '') {
            $wpf_powerbylink = $wpf_powered_link;
            $wpf_powered_class = '_blank';
        } else {
            $wpf_powerbylink = "javascript:void(0)";
        }
        }
        
        $wpf_show_front_stikers = get_site_data_by_key('wpf_show_front_stikers');
        
        /* =====Start filter sidebar HTML Structure==== */
        if (/*get_query_var('is_graphic_page') && */$wpf_show_front_stikers == 'yes') {
        $checkbox_checked = "checked";
        } else {
        $checkbox_checked = "";
        }
        
        $wpf_current_page_url = get_permalink() . '?wpf_login=1';
        $backend_btn = '';
        $wpf_report_btn = '';
        $wpf_report_btn_tab = '';
        $wpf_go_to_cloud_dashboard_btn_tab = '';
        
        if ($current_user_id > 0) {
            $share_style = '';
            if ($wpf_current_role == 'advisor') {
                $wpf_go_to_cloud_dashboard_btn_tab = '<a href="' . WPF_APP_SITE_URL . '/login" target="_blank" class="wpf_filter_tab_btn cloud_dashboard_btn" title="' . __("Atarim Dashboard", "wpfeedback") . '">'.get_wpf_icon().'</a>';
            }
            $backend_btn = ' <button class="wpf_tab_sidebar wpf_backend"  onclick="openWPFTab(\'wpf_backend\')" >' . __('Backend', 'wpfeedback') . '</button>';
            $wpf_daily_report = get_site_data_by_key('wpf_daily_report');
            $wpf_weekly_report = get_site_data_by_key('wpf_weekly_report');

            if (get_query_var('is_graphic_page')) {
                $wpf_current_page_url = site_url() . '/collaborate/graphic?id=' . $_GET['id']. '&wpf_login=1';
            }/* else {
                $wpf_current_page_url = get_permalink() . '?wpf_login=1';
            }*/
            /* ================Go to dashboard Tabs HTML================ */
            $wpf_report_btn .= '<div class="wpf_report_trigger"><label class="wpf_reports_title">'.get_wpf_report_icon(). __("Send Reports:", "wpfeedback") . '</label>';
            if ($wpf_daily_report == 'yes') {
                /* ================Daily report btn HTML================ */
                $wpf_report_btn .= '<a href="javascript:wpf_send_report(\'daily_report\')">' . __('Last 24 Hours', 'wpfeedback') . '</a>';
            }
            if ($wpf_weekly_report == 'yes') {
                /* ================Weekly report btn HTML================ */
                $wpf_report_btn .= '<a href="javascript:wpf_send_report(\'weekly_report\')">' . __('Last 7 Days', 'wpfeedback') . '</a>';
            }
            $wpf_report_btn .= '<span id="wpf_front_report_sent_span" class="wpf_hide text-success">' . __('Your report was sent', 'wpfeedback') . '</span></div>';

            /* ================Report Tabs HTML================ */
            $wpf_report_btn_tab = '<li class="reports"><a class="wpf_filter_tab_btn_bottom" href="javascript:void(0);" data-tag="wpf_report_btn" title="'. __("Reports", "wpfeedback") .'">'. get_wpf_report_icon().' <span>'. __("Reports", "wpfeedback") .'</span></a></li>';
        } else {
            $share_style = 'style="margin-left: 90px;"';
        }



        /* ================visibility Tabs Content HTML================ */
        $wpf_task_visibility = '<label class="wpf_visibility_title">'.get_wpf_visibility_icon(). __("Tasks Visibility", "wpfeedback") . '</label><div class="wpf_sidebar_checkboxes"><input type="checkbox" name="wpfb_display_tasks" id="wpfb_display_tasks" class="wpf_checkbox" ' . $checkbox_checked . '/> <label for="wpfb_display_tasks" class="wpf_checkbox_label">' . __('Show Tasks', 'wpfeedback') . '</label></div>
                <div class="wpf_sidebar_checkboxes"><input type="checkbox" name="wpfb_display_completed_tasks" class="wpf_checkbox" id="wpfb_display_completed_tasks" /> <label for="wpfb_display_completed_tasks" class="wpf_checkbox_label">' . __('Show Completed', 'wpfeedback') . '</label></div>
                <div class="wpf_sidebar_checkboxes"><input type="checkbox" name="wpfb_display_internal_tasks" class="wpf_checkbox" id="wpfb_display_internal_tasks" checked /> <label for="wpfb_display_internal_tasks" class="wpf_checkbox_label">' . __('Show Internal', 'wpfeedback') . '</label></div>';

        $wpf_page_share = '<div class="wpf_icon_title">'.get_wpf_share_icon(). __("Share Page Link : ", "wpfeedback") . '</div><input type="text" id="wpf_share_page_link" value="' . $wpf_current_page_url . '" style="position: absolute; z-index: -999; opacity: 0;"><span class="wpf_share_task_link"><div class="wpf_task_link">' . $wpf_current_page_url . '</div><a href="javascript:void(0);" onclick="wpf_copy_to_clipboard(\'wpf_share_page_link\')" class="wpf_copy_task_icon" style="display: inline-block; color: var(--main-wpf-color) !important;"><i class="gg-copy"></i></a><span class="wpf_success_wpf_share_link" id="wpf_success_wpf_share_page_link" style="display: none;">The link was copied to your clipboard.</span></span><div class="wpf_remove_login_box"><input type="checkbox" id="wpf_remove_login_task_link" class="wpf_remove_login_task_link wpf_checkbox" onclick=\'wpf_remove_login_to_clipboard_sidebar("wpf_share_page_link","")\'><label class="wpf_remove_login_label wpf_checkbox_label" for="wpf_remove_login_task_link">' . __("Remove Login Parameter", "wpfeedback") . '</label></div>';
        /* ================Filter Tabs & Content HTML================ */
        
        $wpf_toggel_filter_tab = '<div id="wpf_bottom_filter">
            <div class="wpf_list wpf_hide" id="wpf_task_status_filter_btn">' . $wpf_task_status_filter_btn . '</div>
            <div class="wpf_list wpf_hide" id="wpf_task_priority_filter_btn">' . $wpf_task_priority_filter_btn . '</div>
            <div class="wpf_list wpf_hide" id="wpf_task_visibility">' . $wpf_task_visibility . '</div>
            <div class="wpf_list wpf_hide" id="wpf_report_btn">' . $wpf_report_btn . '</div>
            <div class="wpf_list wpf_hide" id="wpf_share_page_btn" '.$share_style.' >' . $wpf_page_share . '</div>
        </div>';
        /* =====END filter sidebar HTML Structure==== */
        if($is_approved == 1){
            $btn_title = __("Approved", "wpfeedback");
            $btn_approve_class="";
        }else{
            $btn_title = __("Approve Page", "wpfeedback");
            $btn_approve_class="wpf_not_approved";
        }
        
        
        $approve_btn = '<button class="wpf_green_btn approve-page '.$btn_approve_class.'" id="open_approve_modal" title="'.__("Approve Page", "wpfeedback").'" data-is-approve="'.$is_approved.'">'. get_wpf_right_icon().'<span>'.$btn_title.'</span> </button>';

            $responsive_btn = '<div class="wpf_responsive_icons_bar">
                <a href="javascript:void(0)" class="wpf_responsive_icon wpf_responsive_tablet" title="Tablet View"><span><i class="gg-tablet"></i></span></a>
                <a href="javascript:void(0)" class="wpf_responsive_icon wpf_responsive_mobile" title="Mobile View"><span><i class="gg-smartphone"></i></span></a>		
            </div>';
        
        if(is_admin() == 1){
            $approve_btn = "";
        }


        /*if (!session_id()) {
        session_start();
        }*/
        //r($_SESSION);
        //$wpf_site_id = get_option('wpf_site_id');
        $bottom_panel_db=get_option('bottom_panel');
        $bottom_style = "";
        $bottom_active_class = 'active';
        $agency_menu_item = '';
        if ($wpf_current_role == 'advisor' || ($wpf_current_role == '' && current_user_can('administrator') ) ) {
            $agency_menu_item='<li class="pro"> <a href="'.WPF_APP_SITE_URL.'/login" target="_blank" title="'.__("Atarim Dashboard", "wpfeedback").'">'.get_wpf_pro_icon().'<span>'.__("Agency", "wpfeedback").'</span> </a></li>';
        }
        if( (!get_query_var('is_graphic_page')) && (isset($bottom_panel_db) && $bottom_panel_db == '0') ) {
            $bottom_active_class = '';
            $bottom_style = 'bottom: -49px;';
        }

        if (get_query_var('is_graphic_page')) {
            $toggle_button = "";
        } else {
            $toggle_button = '<a href="javascript:expand_bottom_bar()" class="arrow-down" id="wpf_bottom_arrow" title="Hide Panel"> <img src="' . WPF_PLUGIN_URL . 'images/arrow-down.svg"> </a>';
        }

        // milestone UI
        $milestone = '<div id="site_milestone" data-toggle="milestone-popover"></div>';
        
        return '<section id="wpf_bottom_bar" class="'.$bottom_active_class.'" style="'.$bottom_style.'"><div class="wpf_progress_bar"><div class="red-pb" id="open_progress"></div><div class="orange-pb" id="inprogress_progress"></div><div class="yellow-pb" id="pending_progress"></div><div class="green-pb" id="completed_progress"></div></div><div id="wpf_panel" class="wpf_row"><div class="wpf_bottom_left"><div class="footer-logo" title="Logo"><a href="' . $wpf_powerbylink . '" target="' . $wpf_powered_class . '"><img src="' . $wpf_powerbylogo . '" /></a></div>  '.$toggle_button.$approve_btn.$responsive_btn.$milestone.'</div><div class="wpf_bottom_middle"><ul class="icons-block">'.$agency_menu_item.'<li class="visibility"> <a id="wpf_filter_btn_bottom_visibility" class="wpf_filter_tab_btn_bottom" data-tag="wpf_task_visibility" href="javascript:void(0);" title="'.__("Task Visibility", "wpfeedback").'">'. get_wpf_visibility_icon().' <span>'.__("Visibility", "wpfeedback").'</span> </a></li>'.$wpf_report_btn_tab.'<li class="share"><a class="wpf_filter_tab_btn_bottom" data-tag="wpf_share_page_btn" href="javascript:void(0);" title="'.__("Share", "wpfeedback").'">'. get_wpf_share_icon().'<span>'.__("Share", "wpfeedback").'</span></a></li></ul>'.$wpf_toggel_filter_tab.'</div><div class="wpf_bottom_right"> <a href="javascript:enable_comment();" class="wpf_green_btn wpf_comment_btn" title="Click to give your feedback!">'.get_wpf_plus_icon().' <span>'.__("Comment", "wpfeedback").'</span></a><a class="wpf_green_btn wpf_general_btn wpf_comment_mode_general_task" id="wpf_comment_mode_general_task" href="javascript:void(0)" onclick="wpf_new_general_task(0)" title="' . __('General', 'wpfeedback') . '">'.get_wpf_exclamation_icon().'<span>' . __('General', 'wpfeedback') . '</span></a><a href="javascript:expand_sidebar()" class="tasks-btn wpf_blue_btn" title="Sidebar"> <svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M20.7368 14.3781C20.7368 14.938 20.5144 15.475 20.1185 15.8709C19.7226 16.2668 19.1856 16.4892 18.6257 16.4892L5.95904 16.4892L1.73682 20.7114L1.73682 3.82254C1.73682 3.26264 1.95924 2.72567 2.35515 2.32976C2.75106 1.93385 3.28803 1.71143 3.84793 1.71143L18.6257 1.71143C19.1856 1.71143 19.7226 1.93385 20.1185 2.32976C20.5144 2.72567 20.7368 3.26264 20.7368 3.82254L20.7368 14.3781Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </svg><span class="title">'.__("Tasks", "wpfeedback").'</span> <span class="number wpf_hide" id="wpf_total_task_number"></span> </a></div></div><div id="wpf_enable_comment" class="wpf_row" style="display:none;"><div class="wpf_bottom_left"><div class="footer-logo" title="Logo"> <img src="' . $wpf_powerbylogo . '"></div> <span class="message">Choose a part of the page to add a message or, click the "General" button for a generic request</span></div><div class="wpf_bottom_right">  <a href="javascript:disable_comment();" id="disable_comment_a" class="tasks-btn wpf_red_btn" title="' . __('Cancel', 'wpfeedback') . '">'. get_wpf_close_icon() .'<span class="title">' . __('Cancel', 'wpfeedback') . '</span> </a></div></div><div class="wpf_page_loader"></div></section>';

        
        // <a href="javascript:void(0)" class="wpf_filter_tab_btn wpf_active" data-tag="wpf_share_page_btn" title="Share Page" style="cursor: pointer;">wpf_bottom_middle report
    }
    
}


/*
 * function is used to generate
 * side task panel
 */
function generate_side_part_html() {

    $current_page_id = get_the_ID();
    if ($current_page_id == '') {
        if (isset($wp_query->post->ID)) {
            $current_page_id = $wp_query->post->ID;
        }
    }

    $current_user = wp_get_current_user();
    $wpf_user_name = $current_user->display_name; //['display_name']; //->user_nicename;
    $wpf_user_email = $current_user->user_email; //['user_email']; //->user_email;

    $url = WPF_CRM_API . 'wp-api/page/is-approved';
    $sendarr = array();
    $sendarr["wpf_site_id"] = get_option('wpf_site_id');
    $sendarr["page_id"] = $current_page_id;
    $sendtocloud = json_encode($sendarr);
    $response = wpf_send_remote_post($url, $sendtocloud);
    $is_approved = 0;
    $data = [];

    if(isset($response['status']) && $response['status']) {
       $is_approved = 1;
    }

    $currnet_user_information = wpf_get_current_user_information();
    $current_role = $currnet_user_information['role'];
    // $current_user_name = $currnet_user_information['display_name'];
    $current_user_name = $currnet_user_information['display_name'];
    $current_user_id = $currnet_user_information['user_id'];

    if ($current_user_name == 'Guest') {
	$wpf_current_role = 'guest';
    } else {
	$wpf_current_role = wpf_user_type();
    }
    $current_user_name = addslashes($current_user_name);

    $wpf_powered_class = '_blank';
    $wpf_powered_by = get_site_data_by_key('wpfeedback_powered_by');
    $wpf_powerbylink = WPF_MAIN_SITE_URL . '/reviews/?website=' . get_bloginfo('name').'&email='. $wpf_user_email.'&nameu='. $wpf_user_name;
    $wpf_powerbylogo = get_wpf_logo();
    if ($wpf_powered_by == 'yes') {
	$wpf_powered_class = '_self';
	$wpf_powered_link = get_site_data_by_key('wpf_powered_link');
	if ($wpf_powered_link != '') {
	    $wpf_powerbylink = $wpf_powered_link;
	    $wpf_powered_class = '_blank';
	} else {
	    $wpf_powerbylink = "javascript:void(0)";
	}
    }

    $wpf_show_front_stikers = get_site_data_by_key('wpf_show_front_stikers');

    /* =====Start filter sidebar HTML Structure==== */
    if (/*get_query_var('is_graphic_page') && */$wpf_show_front_stikers == 'yes') {
	$checkbox_checked = "checked";
    } else {
	$checkbox_checked = "";
    }

    $wpf_current_page_url = get_permalink() . '?wpf_login=1';
    $backend_btn = '';
    $wpf_report_btn = '';
    $wpf_report_btn_tab = '';
    $wpf_go_to_cloud_dashboard_btn_tab = '';

    if ($current_user_id > 0) {
        $share_style = '';
        if ($wpf_current_role == 'advisor') {
            $wpf_go_to_cloud_dashboard_btn_tab = '<a href="' . WPF_APP_SITE_URL . '/login" target="_blank" class="wpf_filter_tab_btn cloud_dashboard_btn" title="' . __("Atarim Dashboard", "wpfeedback") . '">'.get_wpf_icon().'</a>';
        }
        $backend_btn = ' <button class="wpf_tab_sidebar wpf_backend"  onclick="openWPFTab(\'wpf_backend\')" >' . __('Backend', 'wpfeedback') . '</button>';
        $wpf_daily_report = get_site_data_by_key('wpf_daily_report');
        $wpf_weekly_report = get_site_data_by_key('wpf_weekly_report');

        if (get_query_var('is_graphic_page')) {
            $wpf_current_page_url = site_url() . '/collaborate/graphic?id=' . $_GET['id']. '?wpf_login=1';
        }/* else {
            $wpf_current_page_url = get_permalink() . '?wpf_login=1';
        }*/
        /* ================Go to dashboard Tabs HTML================ */
        $wpf_report_btn .= '<div class="wpf_report_trigger"><label class="wpf_reports_title">'.get_wpf_report_icon(). __("Send Reports:", "wpfeedback") . '</label>';
        if ($wpf_daily_report == 'yes') {
            /* ================Daily report btn HTML================ */
            $wpf_report_btn .= '<a href="javascript:wpf_send_report(\'daily_report\')">' . __('Last 24 Hours', 'wpfeedback') . '</a>';
        }
        if ($wpf_weekly_report == 'yes') {
            /* ================Weekly report btn HTML================ */
            $wpf_report_btn .= '<a href="javascript:wpf_send_report(\'weekly_report\')">' . __('Last 7 Days', 'wpfeedback') . '</a>';
        }
        $wpf_report_btn .= '<span id="wpf_front_report_sent_span" class="wpf_hide text-success">' . __('Your report was sent', 'wpfeedback') . '</span></div>';

        /* ================Report Tabs HTML================ */
        $wpf_report_btn_tab = '<li class="reports"><a class="wpf_filter_tab_btn_bottom" href="javascript:void(0);" data-tag="wpf_report_btn" title="'. __("Reports", "wpfeedback") .'">'. get_wpf_report_icon().' <span>'. __("Reports", "wpfeedback") .'</span></a></li>';
    } else {
	    $share_style = 'style="margin-left: 90px;"';
    }

    /* ================filter Tabs Content HTML================ */
    $wpf_task_status_filter_btn = '<div id="wpf_filter_taskstatus" class=""><label class="wpf_filter_title">' . get_wpf_status_icon() . ' ' . __('Filter by Status:', 'wpfeedback') . '</label>' . wp_feedback_get_texonomy_filter("task_status") . '</div>';

    $wpf_task_priority_filter_btn = '<div id="wpf_filter_taskpriority" class=""><label class="wpf_filter_title">' . get_wpf_priority_icon() . ' ' . __("Filter by Priority:", "wpfeedback") . '</label>' . wp_feedback_get_texonomy_filter("task_priority") . '</div>';

    /* ================visibility Tabs Content HTML================ */
    $wpf_task_visibility = '<label class="wpf_visibility_title">'.get_wpf_visibility_icon(). __("Tasks Visibility", "wpfeedback") . '</label><div class="wpf_sidebar_checkboxes"><input type="checkbox" name="wpfb_display_tasks" id="wpfb_display_tasks" class="wpf_checkbox" ' . $checkbox_checked . '/> <label for="wpfb_display_tasks" class="wpf_checkbox_label">' . __('Show Tasks', 'wpfeedback') . '</label></div>
            <div class="wpf_sidebar_checkboxes"><input type="checkbox" name="wpfb_display_completed_tasks" class="wpf_checkbox" id="wpfb_display_completed_tasks" /> <label for="wpfb_display_completed_tasks" class="wpf_checkbox_label">' . __('Show Completed', 'wpfeedback') . '</label></div>';

    $wpf_page_share = '<div class="wpf_icon_title">'.get_wpf_share_icon(). __("Share Page Link : ", "wpfeedback") . '</div><input type="text" id="wpf_share_page_link" value="' . $wpf_current_page_url . '" style="position: absolute; z-index: -999; opacity: 0;"><span class="wpf_share_task_link"><div class="wpf_task_link">' . $wpf_current_page_url . '</div><a href="javascript:void(0);" onclick="wpf_copy_to_clipboard(\'wpf_share_page_link\')" class="wpf_copy_task_icon" style="display: inline-block; color: var(--main-wpf-color) !important;"><i class="gg-copy"></i></a><span class="wpf_success_wpf_share_link" id="wpf_success_wpf_share_page_link" style="display: none;">The link was copied to your clipboard.</span></span><div class="wpf_remove_login_box"><input type="checkbox" id="wpf_remove_login_task_link" class="wpf_remove_login_task_link wpf_checkbox" onclick=\'wpf_remove_login_to_clipboard_sidebar("wpf_share_page_link","")\'><label class="wpf_remove_login_label wpf_checkbox_label" for="wpf_remove_login_task_link">' . __("Remove Login Parameter", "wpfeedback") . '</label></div>';
    /* ================Filter Tabs & Content HTML================ */

    $wpf_toggel_filter_tab = '<div id="wpf_bottom_filter">
        <div class="wpf_list wpf_hide" id="wpf_task_status_filter_btn">' . $wpf_task_status_filter_btn . '</div>
        <div class="wpf_list wpf_hide" id="wpf_task_priority_filter_btn">' . $wpf_task_priority_filter_btn . '</div>
        <div class="wpf_list wpf_hide" id="wpf_task_visibility">' . $wpf_task_visibility . '</div>
        <div class="wpf_list wpf_hide" id="wpf_report_btn">' . $wpf_report_btn . '</div>
        <div class="wpf_list wpf_hide" id="wpf_share_page_btn" '.$share_style.' >' . $wpf_page_share . '</div>
    </div>';
    /* =====END filter sidebar HTML Structure==== */
    if($is_approved == 1){
        $btn_title = __("Approved", "wpfeedback");
    }else{
        $btn_title = __("Approve Page", "wpfeedback");
    }


    $approve_btn = '<button class="wpf_green_btn approve-page" title="'.__("Approve Page", "wpfeedback").'" data-is-approve="'.$is_approved.'">'. get_wpf_right_icon().'<span>'.$btn_title.'</span> </button>';

    if(is_admin() == 1){
        $approve_btn = "";
    }


    /*if (!session_id()) {
	session_start();
    }*/
    //r($_SESSION);
    //$wpf_site_id = get_option('wpf_site_id');
    $bottom_panel_db=get_option('bottom_panel');
    $bottom_style = "";
    $bottom_active_class = 'active';
    $agency_menu_item = '';
    if ($wpf_current_role == 'advisor' || ($wpf_current_role == '' && current_user_can('administrator') ) ) {
        $agency_menu_item='<li class="pro"> <a href="'.WPF_APP_SITE_URL.'/login" target="_blank" title="'.__("Atarim Dashboard", "wpfeedback").'">'.get_wpf_pro_icon().'<span>'.__("Agency", "wpfeedback").'</span> </a></li>';
    }
    if(isset($bottom_panel_db) && $bottom_panel_db == '0') {
	$bottom_active_class = '';
	$bottom_style = 'bottom: -49px;';
    }

    return $wpf_toggel_filter_tab;

    // <a href="javascript:void(0)" class="wpf_filter_tab_btn wpf_active" data-tag="wpf_share_page_btn" title="Share Page" style="cursor: pointer;">wpf_bottom_middle report


}


/**
 * Get the author name of the tasks
 */
function get_task_author($mypost)
{
    /* assign the WP names (first & last name / nickname) if the author is on the site */

    // by default, we take name that is coming from the API
    $author = $mypost['task_config_author_name'] ?? "Guest";

    // list all the usere who's roles are allowed to create tasks
    $wpf_front_users = do_shortcode('[wpf_user_list_front]');
                
    if ( $wpf_front_users ) {

        // convert JSON to array
        $wpf_front_users_arr = json_decode( $wpf_front_users, true );

        // get the user data by filtering the roles allowed to create tasks
        $front_user_meta = array_filter( $wpf_front_users_arr, function( $user ) use( $mypost ) {
            return intval( $user['id'] ) === intval( $mypost['task_config_author_id'] );
        } );

        // if the user dats found, we will assign the appropriate name
        if ( is_array( $front_user_meta ) ) { // if ( !empty( $front_user_name ) && is_array( $front_user_meta ) )

            // as the filter returns the array with the task's id as key, we only need the data in the array by unpacking
            $front_user_metas = array_merge($front_user_meta);

            // return json_encode($front_user_metas);

            // assign the first and last name if present, nickname otherwise.
            return $front_user_metas[0]['displayname'] ?? $author;

        }
    }

    return $author;
}


/**
 * Checks if the feature has enabled by the plan or not
 */
function is_feature_enabled($feature_name)
{
    $wpf_user_plan = get_option('wpf_user_plan', false);
    if ( $wpf_user_plan )
        $wpf_user_plan = unserialize( $wpf_user_plan );

    // Enable the feature if the data not in the DB
    if ( empty($wpf_user_plan) )
        return true;

    return ( !empty( $wpf_user_plan[$feature_name] ) && $wpf_user_plan[$feature_name] === 'yes' );
}