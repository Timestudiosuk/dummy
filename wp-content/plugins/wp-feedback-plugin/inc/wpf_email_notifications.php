<?php
/*
 * wpf_email_notifications.php
 * This file contains all the code related to sending the email notifications to the users for task related activities like Creating a new task, creating a new comment on the task, change the status of the task, mark task as complete, daily reports and weekly reports.
 */

/*
 * This function is used to send the daily and weekly reports to the users from Backend Tasks Center, Frontend Sidebar and Graphics Sidebar.
 *
 * @input NULL
 * @return NULL
 */
function wpf_send_email_report()
{
    if($_SERVER['REMOTE_ADDR']!='35.246.48.203'){
        wpf_security_check();
    }
    $type = $_REQUEST['type'];
    //$forced = $_REQUEST['forced'];

    $site_id = get_option("wpf_site_id");
    $response = [];
    $args = [];
    $args['wpf_site_id'] = $site_id;
    $sendtocloud = json_encode($args);
    if($type == 'daily_report'){
        $url = WPF_CRM_API.'wp-api/task/daily-reports';
        $response = wpf_send_remote_post($url,$sendtocloud);
    }else{
        $url = WPF_CRM_API.'wp-api/task/weekly-reports';
        $response = wpf_send_remote_post($url,$sendtocloud);
    }
    exit();
}
add_action('wp_ajax_wpf_send_email_report','wpf_send_email_report');
add_action('wp_ajax_nopriv_wpf_send_email_report','wpf_send_email_report');

/*
 * This function is used to send the daily and weekly reports to the users from Auto reports cron running on wpfeedback.co.
 *
 * @input String, String
 * @return NULL
 */
function wpf_send_email_report_cron($type,$forced)
{

    /*$type = $_REQUEST['type'];
    $forced = $_REQUEST['forced'];*/

    $site_id = get_option("wpf_site_id");
    $response = [];
    $args = [];
    $args['wpf_site_id'] = $site_id;
    $sendtocloud = json_encode($args);
    if($type == 'daily_report'){
        $url = WPF_CRM_API.'wp-api/task/daily-reports';
        $response = wpf_send_remote_post($url,$sendtocloud);
    }else{
        $url = WPF_CRM_API.'wp-api/task/weekly-reports';
        $response = wpf_send_remote_post($url,$sendtocloud);
    }
    exit();
}

/*
 * This function is used to remove the powered by text from the email if white labeling option is selected from settings.
 *
 * @input String, String
 * @return String
 */
function wpf_remove_powered_from_email($array_of_id_or_class, $text)
{
    $name = implode('|', $array_of_id_or_class);
    $regex = '#<(\w+)\s[^>]*(class|id)\s*=\s*[\'"](' . $name .
        ')[\'"][^>]*>.*</\\1>#isU';
    return(preg_replace($regex, '', $text));
}

/*
 * This function is used to translate the email to other language if other supported language is selected on website.
 *
 * @input String
 * @return String
 */
function wpf_translate_email($body){
    $domain = 'wpfeedback';
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
    if($locale == "he_IL"){
        $body = str_replace('direction: ltr', 'direction: rtl', $body);
        $body = str_replace('text-align:left', 'text-align:right', $body);
    }

    $body = str_replace('You have a new task on', __('You have a new task on', 'wpfeedback'), $body);
    $body = str_replace('You have a new reply on', __('You have a new reply on', 'wpfeedback'), $body);
    $body = str_replace('Your task on', __('Your task on', 'wpfeedback'), $body);
    $body = str_replace('is done', __('is done', 'wpfeedback'), $body);
    $body = str_replace('Your task status on', __('Your task status on', 'wpfeedback'), $body);
    $body = str_replace('has changed', __('has changed', 'wpfeedback'), $body);
    $body = str_replace('Your daily report for', __('Your daily report for', 'wpfeedback'), $body);
    $body = str_replace('Posted on', __('Posted on', 'wpfeedback'), $body);
    $body = str_replace('It is now marked as', __('It is now marked as', 'wpfeedback'), $body);

    $body = str_replace('Open task', __('Open task', 'wpfeedback'), $body);
    $body = str_replace('In progress', __('In progress', 'wpfeedback'), $body);
    $body = str_replace('In Progress', __('In Progress', 'wpfeedback'), $body);
    $body = str_replace('Pending Review', __('Pending Review', 'wpfeedback'), $body);
    $body = str_replace('Complete', __('Complete', 'wpfeedback'), $body);

    $body = str_replace('Tasks summary for the day', __('Tasks summary for the day', 'wpfeedback'), $body);
    $body = str_replace('Tasks completed today', __('Tasks completed today', 'wpfeedback'), $body);
    $body = str_replace('Tasks summary for the week', __('Tasks summary for the week', 'wpfeedback'), $body);
    $body = str_replace('Tasks completed this week', __('Tasks completed this week', 'wpfeedback'), $body);
    $body = str_replace('Task ID', __('Task ID', 'wpfeedback'), $body);
    $body = str_replace('Task', __('Task', 'wpfeedback'), $body);
    $body = str_replace('Post Name', __('Post Name', 'wpfeedback'), $body);
    $body = str_replace('Post name', __('Post name', 'wpfeedback'), $body);
    $body = str_replace('Post', __('Post', 'wpfeedback'), $body);
    $body = str_replace('Priority', __('Priority', 'wpfeedback'), $body);
    $body = str_replace('Status', __('Status', 'wpfeedback'), $body);
    $body = str_replace('View', __('View', 'wpfeedback'), $body);
    $body = str_replace('Open', __('Open', 'wpfeedback'), $body);
    $body = str_replace('Click here to reply', __('Click here to reply', 'wpfeedback'), $body);
    $body = str_replace('Direct URL', __('Direct URL', 'wpfeedback'), $body);
    $body = str_replace('Original Request', __('Original Request', 'wpfeedback'), $body);
    $body = str_replace('Users', __('Users', 'wpfeedback'), $body);

    $body = str_replace('DIV Handle', __('DIV Handle', 'wpfeedback'), $body);
    $body = str_replace('DIV handle', __('DIV handle', 'wpfeedback'), $body);
    $body = str_replace('Screensize', __('Screensize', 'wpfeedback'), $body);
    $body = str_replace('Browser', __('Browser', 'wpfeedback'), $body);
    $body = str_replace('User IP', __('User IP', 'wpfeedback'), $body);
    $body = str_replace('Powered by', __('Powered by', 'wpfeedback'), $body);
    $body = str_replace('Low', __('Low', 'wpfeedback'), $body);
    $body = str_replace('by ', __('by ', 'wpfeedback'), $body);
    $body = str_replace('By ', __('By ', 'wpfeedback'), $body);
    $body = str_replace('Click here to view the task', __('Click here to view the task', 'wpfeedback'), $body);




    $body = str_replace('This email and any attachments to it may be confidential and are intended solely for the use of the individual to whom it is addressed.', __('This email and any attachments to it may be confidential and are intended solely for the use of the individual to whom it is addressed.', 'wpfeedback'), $body);
    $body = str_replace('If you are not the intended recipient of this email, you must neither take any action based upon its contents nor copy or show it to anyone.', __('If you are not the intended recipient of this email, you must neither take any action based upon its contents nor copy or show it to anyone.', 'wpfeedback'), $body);
    $body = str_replace('Please contact the sender if you believe you have received this email in error.', __('Please contact the sender if you believe you have received this email in error.', 'wpfeedback'), $body);
    return $body;
}

/*
 * This function is used to send the auto reports from the cron on wpfeedback.co.
 *
 * @input Array
 * @return JSON
 */
function wpf_auto_send_email_report($request)
{
    $send_report = false;
    $wpf_license = $request['wpf_license'];
    $wpf_license_key = trim(get_option('wpf_license_key'));
    $wpf_decry_key = wpf_crypt_key($wpf_license_key,'d');
    if($wpf_license==$wpf_license_key || $wpf_license==$wpf_decry_key){
        $send_report = true;
    }
    if($send_report==true){
        $type = array();
        $type['report_type'] = $request["report_type"];
        if($request["report_type"] == 'daily_report' || $request['report_type'] == 'weekly_report'){
            wpf_send_email_report_cron($type['report_type'],'no');
            echo json_encode($type);
        }
    }
    exit;
}

/*
 * This function is used to register the API wpf-send-email-report for sending the auto reports.
 *
 * @input NULL
 * @return NULL
 */
add_action( 'rest_api_init', 'wpf_send_email_report_register_api_hooks' );
function wpf_send_email_report_register_api_hooks() {
  register_rest_route(
    'wpf-send-email-report', '/wpf-send-email-report/',
    array(
      'methods'  => 'GET',
      'callback' => 'wpf_auto_send_email_report',
      'permission_callback' => '__return_true'
    )
  );
}