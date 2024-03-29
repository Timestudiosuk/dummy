<?php
/*
 * wpf_admin_functions.php
 * This file contains the functions to support actions related to the backend activities.
 */

/*
 * This function is used to send an email to support@wpfeedback.co from plugin "Supports" tab.
 *
 * @input NULL
 * @return Boolean
 */
add_action('wp_ajax_wpf_user_support', 'wpf_user_support');
if (!function_exists('wpf_user_support')) {
    function wpf_user_support(){
        global $current_user;
        wpf_security_check();
        /*$from = $current_user->user_email;
        $username = $current_user->user_login;*/
        $from = $_POST['wpf_support_email'];
        $username = $_POST['wpf_support_name'];
        $license_valid = get_option('wpf_license');
        if($license_valid=='valid'){
            $license = get_option('wpf_license_key');
            $license = wpf_crypt_key($license,'d');
        }
        else{
            $license = 'No license';
        }

        $site_url = get_site_url();
        $to = 'support@wpfeedback.co';
        $subject = "Atarim Support - ".stripslashes(html_entity_decode($_POST['wpf_support_subject'], ENT_QUOTES, 'UTF-8'));
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: '.$username.' <'.$from.'>';
        $headers[] = 'Bcc: vito@wpfeedback.co';
        // $headers[] = 'Bcc: diligentmirzaa@gmail.com';

        $body = '<table>
                    <tr>
                        <td>
                            <b>Message</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="display:block;white-space:pre-line">
                            '.stripslashes(html_entity_decode($_POST['wpf_support_message'], ENT_QUOTES, 'UTF-8')).'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Website</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            '.$site_url.'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Username</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            '.$username.'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Email</b>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            '.$from.'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>License</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            '.$license.'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Product ID</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            '.WPF_EDD_SL_ITEM_ID.'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Insert Site Health Info</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <code style="display:block;white-space:pre-wrap">'.$_POST['wpf_support_site_health_info'].'</code>
                        </td>
                    </tr>
                </table>';

        if(wp_mail($to, $subject, $body, $headers)){
            echo 1;
        }
        else{
            echo 0;
        }
        exit;
    }
}

/*
 * This function is used to register the website for the auto reports feature. The websites are registered on https://wpfeedback.co for auto updates.
 *
 * @input Array
 * @return Array OR Error
 */
if (!function_exists('wpf_register_auto_reports_cron')) {
    function wpf_register_auto_reports_cron($type){
        $wpf_site_url = site_url();
        $wpf_license = get_option('wpf_license');
        $wpf_license_key = get_option('wpf_license_key');
        $gmt_offset = get_option('gmt_offset');
        $timezone_string = get_option('timezone_string');

        $wpf_cron_register_url = WPF_MAIN_SITE_URL.'/wpf_register_auto_reports_cron.php';
        $wpf_curl_data = array(
            'daily' => $type['daily'],
            'weekly' => $type['weekly'],
            'wpf_site_url' => $wpf_site_url,
            'wpf_license' => $wpf_license,
            'wpf_license_key' => $wpf_license_key,
            'gmt_offset' => $gmt_offset,
            'timezone_string' => $timezone_string
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $wpf_cron_register_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $wpf_curl_data,
            CURLOPT_HTTPHEADER => array(
                "Content-type: multipart/form-data"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}

/*
 * This function is to handle the request and response to the main set global settings in wp_api.
 *
 * @input NULL
 * @return Int
 */
add_action('wp_ajax_wpf_global_settings', 'wpf_global_settings');
if (!function_exists('wpf_global_settings')) {
    function wpf_global_settings()
    {
        wpf_security_check();

        if($_POST['wpf_global_settings'] == 'yes') {

            //$response = wpf_get_global_settings();

            $url = WPF_CRM_API . 'update-global-settings';

            $wpf_license_key = get_option('wpf_license_key');
            $wpf_license_key=wpf_crypt_key($wpf_license_key,'d');

            $sendarr = array();
            $sendarr["wpf_site_id"] = get_option('wpf_site_id');
            $sendarr["wpf_license_key"] = $wpf_license_key;
            $sendtocloud = json_encode($sendarr);
            $response = wpf_send_remote_post($url, $sendtocloud);

            get_notif_sitedata_filterdata();

            echo (isset($response['status']) == 200) ? 1 : 3;	  
            
        } elseif ($_POST['wpf_global_settings'] == 'no') {

            $parms1 = [];
            array_push($parms1, ['name' => 'wpf_global_settings','value' => 'no']);
            update_site_data($parms1);
            
            echo 2;
        }
        else {

            get_notif_sitedata_filterdata();
            
            echo 0;
        }

        // sync the settings with agency dashboard => v2.1.0
        //get_notif_sitedata_filterdata();

        exit;
    }
}

/*
 * This function is to sync the global settings once a day.
 *
 * @input NULL
 * @return Int
 */
add_action('init', 'sync_global_settings');
if (!function_exists('sync_global_settings')) {

    function sync_global_settings()
    {
        $unix_time_now= time();
        $unix_time_last_sync=get_option('wpf_global_settings_resync_time');
        if($unix_time_now>$unix_time_last_sync){
        //wpf_security_check();
        $global_settings=get_option("wpf_global_settings");
        if($global_settings == 'yes'){
//            $response = wpf_get_global_settings();

	    $url = WPF_CRM_API . 'update-global-settings';

        $wpf_license_key = get_option('wpf_license_key');
        $wpf_license_key=wpf_crypt_key($wpf_license_key,'d');

	    $sendarr = array();
	    $sendarr["wpf_site_id"] = get_option('wpf_site_id');
        $sendarr["wpf_license_key"] = $wpf_license_key;
	    $sendtocloud = json_encode($sendarr);
	    $response = wpf_send_remote_post($url, $sendtocloud);
	    if(isset($response['status']) == 200) {
            get_notif_sitedata_filterdata();
            //print_r($response);
            //exit;
		//echo 1;
	    }else{
                get_notif_sitedata_filterdata();
                //echo 3;
            }
            
        }elseif ($global_settings == 'no'){
            $parms1 = [];
            array_push($parms1, ['name' => 'wpf_global_settings','value' => 'no']);
            update_site_data($parms1);
                //echo 2;
        }
        else{
            get_notif_sitedata_filterdata();
            //echo 0;
        }
        $unix_time= time();
        $unix_time+=86400;
        update_option("wpf_global_settings_resync_time",$unix_time,'no');

        // sync the settings with agency dashboard => v2.1.0
        //get_notif_sitedata_filterdata();

        //exit;
    }
    }

}