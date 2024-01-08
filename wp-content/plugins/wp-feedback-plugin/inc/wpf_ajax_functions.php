<?php
/*
 * wpf_ajax_functions.php
 * This file contains the code for all methods that are been called from the frontend using ajax requests.
 */

/*
* Require admin functionality
*/
require_once(WPF_PLUGIN_DIR . 'inc/wpf_function.php');
require_once(WPF_PLUGIN_DIR . 'inc/wpf_api.php');

/*
 * This function is used to load the tasks on the "Tasks Center". It is been called from function wpf_backed_scripts() wpfeedback.php
 *
 * @input NULL
 * @return String ( Listing of Task Embedded with HTML Elements.)
 */
add_action('wp_ajax_wpfeedback_get_post_list_ajax', 'wpfeedback_get_post_list_ajax');
add_action('wp_ajax_nopriv_wpfeedback_get_post_list_ajax', 'wpfeedback_get_post_list_ajax');
if (!function_exists('wpfeedback_get_post_list_ajax')) {
    function wpfeedback_get_post_list_ajax()
    {
        global $wpdb, $current_user;
        $output = '';
        $post_title_query = '';
        if(is_user_logged_in() && is_admin()){
            wpf_security_check();
            $all_task_types_array = array('publish','wpf_admin');
            $all_task_types_meta = '';
            $all_task_types_meta_array = array();

            /* START */
            $currnet_user_information = wpf_get_current_user_information();
            $current_role = $currnet_user_information['role'];
            // $current_user_name = $currnet_user_information['display_name'];
            $current_user_name = $currnet_user_information['display_name'];
            $current_user_id = $currnet_user_information['user_id'];
            $wpf_website_builder = get_site_data_by_key('wpf_website_developer');
            if($current_user_name=='Guest'){
                $wpf_website_client = get_site_data_by_key('wpf_website_client');
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
    
            if($wpf_current_role=='advisor'){
                $wpf_tab_permission_display_stickers = (get_site_data_by_key('wpf_tab_permission_display_stickers_webmaster') != 'no') ? 'yes' : 'no';
                $wpf_tab_permission_display_task_id = (get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no') ? 'yes' : 'no';
            }
            elseif ($wpf_current_role=='king'){
                $wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_auto_screenshot_task_client') == 'yes' ? 'yes' : 'no';
                $wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_client') == 'yes' ? 'yes' : 'no';
            }
            elseif ($wpf_current_role=='council'){
                $wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_others') == 'yes' ? 'yes' : 'no';
                $wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_others') == 'yes' ? 'yes' : 'no';
            }
            else{
                $wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_guest') == 'yes' ? 'yes' : 'no';
                $wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_guest') == 'yes' ? 'yes' : 'no';
            } 

            $args = array(
                'numberposts' => -1,
                'post_type' => 'wpfeedback',
                'orderby' => 'title',
                'orderby' => 'date',
                'order' => 'DESC',
                'task_center' => 1,
                'wpf_site_id' => get_option('wpf_site_id')
            );
	    
	    if(isset($_POST['task_status'])) {
		    $args['status'] = $_POST['task_status'];
	    }
	    
	    if(isset($_POST['task_priority'])) {
		      $args['priority'] = $_POST['task_priority'];
	    }
	    
	    if(isset($_POST['task_title']) && $_POST['task_title'] != ''){
		$args['task_title'] = trim($_POST['task_title']);
	    }
	    
	    if(isset($_POST['task_types_meta'])) {
		  $args['task_types_meta'] = $_POST['task_types_meta'];
	    }
	    
	    if(isset($_POST['task_types'])) {
		  $args['task_types'] = $_POST['task_types'];
	    }
	    
	    if(isset($_POST['author_list'])) {
		  $args['task_notify_users'] = $_POST['author_list'];
	    }
	    
	    if( isset($_POST['internal']) && boolval($_POST['internal']) ) {
		  $args['internal'] = boolval($_POST['internal']);
	    }


            $url = WPF_CRM_API.'wp-api/all/task-center-tasks';
            $sendtocloud = json_encode($args);
            $myposts = wpf_send_remote_post($url,$sendtocloud);
            // echo json_encode($args); die;
            
            if ($myposts){//re($myposts);
                // Loop the posts
//                $i = count($myposts);
//                $total_records = $myposts['count'];

                if ( (!empty($_POST['search_type'])) && $_POST['search_type']==='sidebar' ) {
                    
                    foreach ($myposts['data'] as $key=>$mypost) {

                        $user_atarim_type=get_user_meta($current_user_id, 'wpf_user_type',true);

                        if($mypost['task']['is_internal']=='1'){
                            if($user_atarim_type!='advisor'){
                                continue;
                            }
                        }

                        $wpf_page_url=$mypost['task']['task_page_url'];
                        if( $wpf_page_url ){
                            $wpf_page_url_with_and=explode('&', $wpf_page_url)[1];
                            $wpf_page_url_question=explode('?', $wpf_page_url)[1];
                            if($wpf_page_url_with_and){
                                $saperater = '&';
                            }
                            if($wpf_page_url_question){
                                $saperater = '&';
                            }

                            else{
                                $saperater = '?';
                            }
                        }

                        $sticker_span = '';
                        $custom_status_class = '';
                        $custom_status_class = $mypost['task']['task_status'].'_custom';
                        $sticker_span = '<span class="sticker '.$mypost['task']['task_priority'].'_custom"></span> ';

                        $display_check_mark = '';
                        $display_check_mark = $mypost['task']['wpf_task_id'];


                        if($mypost['task']['task_status'] == 'complete'){
                            $bubble_label = $sticker_span . $display_check_mark;
                        }else{
                            $bubble_label = $sticker_span . $mypost['task']['wpf_task_id'];
                        }

                        $general_tag = '';
                        if($mypost['task']['task_type']=='general') {
                            $general_tag = '<span class="wpf_task_type" title="Task type">' .
                                addslashes($wpf_general_tag) . '</span>';
                        }

                        $wpf_task_status_label= '<div class="wpf_task_label">
                                                    <span class="task_status wpf_'.$mypost['task']['task_status'].
                            '" title="Status: '.$mypost['task']['task_status'].'">'. get_wpf_status_icon() .'</span>';

                        $wpf_task_priority_label= '<span class="priority wpf_'.$mypost['task']['task_priority'].
                            '" title="Priority: '.$mypost['task']['task_priority'].'">'.get_wpf_priority_icon().
                            '</span></div>';

                        $all_wpfb_metas = $mypost['task']['wpf_tags'];
                        if($all_wpfb_metas){
                            $tag_length = count($all_wpfb_metas);
                            $wpfb_tags_html = '<div class="wpf_task_tags">';
                            $all_tag = array_keys($all_wpfb_metas);
                            $i = 1;
                            foreach ( $all_tag as $k=>$value ) {
                                if($i == 1){
                                    $wpfb_tags_html .=  '<span class="wpf_task_tag">' .
                                        $all_wpfb_metas[$value]["name"].'</span>';
                                } else {
                                    if($tag_length == $i){
                                        $all_other_tag .=  $all_wpfb_metas['value']["name"];
                                    }else{
                                        $all_other_tag .=  $all_wpfb_metas['value']["name"].', ';
                                    }
                                }
                            }

                            if($tag_length > 1){
                                $wpfb_tags_html .= '<span class="wpf_task_tag_more" 
                                title="'.$all_other_tag.'">...</span>';
                            }
                            $wpfb_tags_html .= '</div>';
                        }

                        $output .= '<li class="current_page_task ' . $mypost['task']['task_status'] .
                            " " . $mypost['task']['task_status'] . '_custom' . " " . $mypost['task']['task_priority'] .
                            '" data-taskid="' . $mypost['task']['wpf_task_id'] . '" data-postid="' . $key .
                            '" data-task_url="' . $mypost['task']['task_page_url'] . $saperater .
                            'wpf_general_taskid=' . $key . '"><div class="wpf_task_number">' . $bubble_label .
                            '</div><div class="wpf_task_sum"><level class="task-author">' .
                            $mypost['task']['task_config_author_name'] . '<span>' . $mypost['task']['task_time'] .
                            '</span></level><div class="wpf_task_pagename">' . $mypost['task']['task_page_title'] .
                            '</div><div class="current_page_task_list">' . $mypost['task']['task_title'] .
                            '</div></div><div class="wpf_task_meta"><div class="wpf_task_meta_icon">
                            <i class="gg-chevron-left"></i></div><div class="wpf_task_meta_details">' . $general_tag .
                            $wpf_task_status_label . $wpf_task_priority_label . $wpfb_tags_html . '</div></div></li>';
                    }
                } else {

                    $output .= '<ul id="all_wpf_list" style="list-style-type: none; font-size:12px;">';
                    if ($myposts['status'] !== 404 && $myposts['status'] == 1) {
                        foreach ($myposts['data'] as $mypost) {

                            $user_atarim_type=get_user_meta($current_user_id, 'wpf_user_type',true);

                            if($mypost['task']['is_internal']=='1'){
                                if($user_atarim_type!='advisor'){
                                    continue;
                                }
                            }

                            $post_id = $mypost['task']['id'];
                            $wpf_task_id = $mypost['task']['wpf_task_id'];
                            $site_task_id = $mypost['task']['site_task_id'];
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
                                $general = '<span class="wpf_task_type">' . __("General", "wpfeedback") . '</span>';
                            } elseif ($get_task_type == 'graphics') {
                                $task_type = 'graphics';
                                $general = '<span class="wpf_task_type">' . __("Graphics", "wpfeedback") . '</span>';
                            } elseif ($get_task_type == 'email') {
                                $task_type = 'email';
                                $general = '<span class="wpf_task_type">' . __("Email", "wpfeedback") . '</span>';
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
                            if ($get_task_type == "general" && ($mypost['task']['wpfb_task_bubble'] != NULL)) {
                                $wpf_orphans[] = $post_id;
                            }

                            if ($mypost['task']['is_admin_task'] == 1) {
                                $wpf_task_status = 'wpf_admin';
                                $admin_tag = '<span class="wpf_task_type">' . __("Admin", "wpfeedback") . '</span>';
                            } else {
                                $wpf_task_status = 'public';
                                $admin_tag = '';
                            }

                            $task_config_author_browser = $mypost['task']['task_config_author_browser'];
                            $task_config_author_browserVersion = $mypost['task']['task_config_author_browserVersion'];
                            $task_comment_id = $mypost['task']['task_comment_id'];
                            $task_priority = $mypost['task']['task_priority'];
                            $task_status = $mypost['task']['task_status'];

                            $task_tags = $mypost['task']['tags'];
//                        $post_title = esc_html($mypost['task']['task_title']);
                            $all_other_tag = '';
                            $wpfb_tags_html = '';
                            if ($task_tags) {
                                $tag_length = count($task_tags);
                                $wpfb_tags_html = '<div class="wpf_task_tags">';
                                $i = 1;

                                foreach ($task_tags as $task_tag => $task_tags_value) {
                                    if ($i == 1) {
                                        $wpfb_tags_html .= '<span class="wpf_task_tag">' . $task_tags_value["tag"] . '</span>';
                                    } else {
                                        if ($tag_length == $i) {
                                            $all_other_tag .= $task_tags_value['tag'];
                                        } else {
                                            $all_other_tag .= $task_tags_value["tag"] . ', ';
                                        }
                                    }
                                    $i++;
                                }
                                if ($tag_length > 1) {
                                    $wpfb_tags_html .= '<span class="wpf_task_tag_more" title="' . $all_other_tag . '">...</span>';
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
                            if ($wpf_tab_permission_display_stickers == 'yes') {
                                $display_span = '<span class="' . $task_priority . '_custom wpf_top_badge"></span> ';
                                $custom_class = $task_status . "_custom";
                            }

                            $curr_task_time = wpfb_time_difference($task_date1, $task_date2);

                            $display_check_mark = '';
                            if ($wpf_tab_permission_display_task_id != 'yes') {
                                $display_check_mark = '<i class="gg-check"></i>';
                            } else {
                                $display_check_mark = '<span class="wpf_bubble_num_wrapper">'.$mypost['task']['site_task_id'].'</span>'.$internal_icon;//$task_comment_id;
                            }

                            if ($task_status == 'complete') {
                                $bubble_label = $display_span . $display_check_mark;
                            } else {
                                $bubble_label = $display_span .'<span class="wpf_bubble_num_wrapper">'. $mypost['task']['site_task_id'].'</span>'.$internal_icon;//$task_comment_id;
                            }

                            if ($author_id && $mypost['task']['task_type']!=='email') {
                                // $author = $mypost['task']['task_config_author_name'];
                                if (get_user_by('login', $mypost['wpf_author'])) {
                                    $authr = get_user_by('login', $mypost['wpf_author']);
                                    $author = $authr->display_name;
                                    $task_config_author_name = $authr->display_name;
                                } elseif (get_user_by('login', $mypost['wpf_author']['wpf_display_name'])) {
                                    $author = get_user_by('login', $mypost['wpf_author']['wpf_display_name'])->display_name;
                                    $task_config_author_name = get_user_by('login', $mypost['wpf_author']['wpf_display_name'])->display_name;
                                } else {
                                    $author = $mypost['task']['task_config_author_name'];
                                }
                            } elseif ( $mypost['task']['task_type'] === 'email' ) {
                                $author = $mypost['task']['task_config_author_name'];                            
                            } else {
                                $author = 'Guest';
                            }
                           
                            
                            $wpf_task_status_label = '<div class="wpf_task_label"><span class="task_status wpf_' . $task_status . '" title="Status: ' . $task_status . '">' . get_wpf_status_icon() . '</span>';
                            $wpf_task_priority_label = '<span class="task_priority wpf_' . $task_priority . '" title="Priority: ' . $task_priority . '">' . get_wpf_priority_icon() . '</i></span></div>';

                            $output .= '<li class="post_' . $post_id . ' ' . $task_priority . ' ' . $task_status . ' wpf_list"><a href="javascript:void(0)" class="' .$internal_class.' '. $task_status . ' '.$internal_class.'" id="wpf-task-' . $post_id . '" data-wpf_task_status="' . $wpf_task_status . '"" data-task_type="' . $task_type . '" data-task_author_name="' . $task_config_author_name . '" data-task_config_author_browserVersion="' . $task_config_author_browserVersion . '" data-task_config_author_res="' . $task_config_author_resX . ' X ' . $task_config_author_resY . '" data-task_config_author_browser="' . $task_config_author_browser . '" data-task_config_author_name="' . __('By ', 'wpfeedback') . $task_config_author_name . ' ' . $post_date . '" data-task_notify_users="' . $task_notify_users . '" data-task_page_url="' . $task_page_url . '" data-wpf_task_screenshot="' . $wpf_task_screenshot . '" data-task_page_title="' . $post_title . '"
                            data-task_priority="' . $task_priority . '" data-task_status="' . $task_status . '"  data-disp-id="' . $site_task_id . '" data-is-internal="'.$is_internal.'" onclick="get_wpf_chat(this,true)" data-postid="' . $post_id . '" data-uid="' . $author_id . '"  data-task_no="' . $task_comment_id . '"><div class="wpf_chat_top"><input type="checkbox" value="' . $post_id . '" data-disp-id="' . $site_task_id . '"  name="wpf_task_id" id="wpf_' . $post_id . '" class="wpf_task_id" style="display:none;"><div class="wpf_task_num_top ' . $custom_class . '">' . $bubble_label . '</div><div class="wpf_task_main_top"><div class="wpf_task_details_top">' . $author . ' <span>' . $curr_task_time['comment_time'] . '</span></div><div class="wpf_task_pagename">' . $task_page_title . ' </div><div class="wpf_task_title_top">' . $post_title . '</div></div>'.$internal.'<span class="wpf_task_type">'.$mypost['task']['task_type'].'</span><div class="wpf_task_meta"><div class="wpf_task_meta_icon"><i class="gg-chevron-left"></i></div><div class="wpf_task_meta_details">' . ' ' . $admin_tag . ' ' . $general . $wpf_task_status_label . $wpf_task_priority_label . $wpfb_tags_html . '</div></div></div></a></li>'; //! email
//                        $i--;
                        }
                    }
                    wp_reset_postdata();

                    if ($myposts['status'] == 404 && $myposts['status'] != 1) {
                        $output = '<div class="wpf_no_tasks_found"><i class="gg-info"></i> No tasks found</div>';
                    }

                    $output .= '</ul>';
                }
            }
            if ($_POST['task_status'] || $_POST['task_priority'] || $_POST['author_list']) {
                //Comment
                echo $output;
                exit;
            } else {
                //Comment
                echo $output;
                exit;
            }
        }else{
            echo $output;
            exit;
        }
    }
}

/*
 * This function is used to load the task comments on the "Tasks Center". It is been called from function wpf_backed_scripts() in wpfeedback.php
 *
 * @input Array ( $_POST )
 * @return String ( Listing of Comments in Task Embedded with HTML Elements.)
 */
add_action('wp_ajax_list_wpf_comment_func', 'list_wpf_comment_func');
if (!function_exists('list_wpf_comment_func')) {
    function list_wpf_comment_func()
    {
        if(is_user_logged_in() && is_admin()){
            global $wpdb, $current_user;
            wpf_security_check();
            $response = array();
            $response['data'] = '';
	    $response['wpf_tags'] = '';
            $comment = "";
            $post_id = $_POST['post_id'];
            $post_author_id = $_POST['post_author_id'];
            $click = $_POST['click'];
            $current_user_id = $current_user->ID;
            if ($post_id) {

                $args = array(
                    'task_id' => $post_id,
                    'type' => 'wp_feedback',
                    // 'orderby'  => 'comment_date',
                    'orderby'  => 'created_at',
                    'order' => 'ASC',
                );

                $wpf_license_key_enc = get_option('wpf_license_key');
                $wpf_license_key = wpf_crypt_key($wpf_license_key_enc,'d');
                $url = WPF_CRM_API.'wp-api/comment/list';
                $sendtocloud=json_encode($args);
                $comments_info = wpf_send_remote_post($url,$sendtocloud);
                // echo json_encode($comments_info); die();
		
                $comment_count = $comments_info['count'];
                $tags = $comments_info['tags'];
                if(!empty($tags) && count($tags) > 0){
                        foreach ($tags as $term ) {
                                $response['wpf_tags'] .= '<span class="wpf_tag_name '.$term['slug'].'">'.$term['name'].'<a href="javascript:void(0)" onclick="wpf_delete_tag_admin(\''.$term['name'].'\',\''.$term['slug'].'\','.$post_id.')" data-tag_slug="'.$term['slug'].'"><i class="gg-close-o"></i></a></span>';
                        }
                }
                // display the results
                if ($comment_count > 0) {
                    foreach ($comments_info['data'] as $key => $comment) {
                            $task_date = $comment['created_at'];
                            $task_date1 = date_create($task_date);
                            //Old Logic to get current time. Was creating issues when displaying message
                            //$task_date2 = new DateTime('now');

                            //New Logic to get current time.
                            $wpf_wp_current_timestamp = date('Y-m-d H:i:s', current_time('timestamp', 0));
                            $task_date2 = date_create($wpf_wp_current_timestamp);

                            $curr_comment_time = wpfb_time_difference($task_date1, $task_date2);
                            $author_id = $comment['wpf_user_id'];
                            // if ($author_id) {
                            //     $author = get_the_author_meta('display_name', $comment->user_id);
                            // } else {
                            //     $author = 'Guest';
                            // }

                            $author = "";
//			    re($comment);
                            if($comment['is_log'] != 1){
                                if(isset($comment['wpf_author']) && isset($comment['wpf_author']['wpf_display_name'])){ // wpf user
                                    // $author = $comment['wpf_author']['wpf_display_name'];
                                    $author = $comment['wpf_author']['wpf_display_name'];


                                    /*
                                     * On the old code $comment['wpf_author'] was giving the issue because it's an
                                     * array and we need a string for the get_user_by() function as a 2nd param.
                                     * PHP v8.0 is throwing an error for this.
                                     *
                                     * Fixed on version v2.1.1
                                     */
                                    $user = get_user_by('id', $comment['wpf_author']['wpf_id']);
                                    if($user){
                                        $authr = $user;
                                        // $author = $authr->display_name;
                                        $author = $authr->display_name;
                                    }elseif(get_user_by('login',$comment['wpf_author']['wpf_display_name'])){
                                        $author = get_user_by('login',$comment['wpf_author']['wpf_display_name']);
                                        $author = $author->display_name;
                                    }else{
                                        // $author = $comment['wpf_author']['wpf_display_name'];
                                        $author =  $comment['wpf_author']['wpf_display_name'];
                                    }
                                } elseif (isset($comment['author']) && isset($comment['author']['name']) && $comment['author']['name'] != '') { // app user
                                    $author = $comment['author']['name'];
                                } elseif (isset($comment['author']) && isset($comment['author']['username'])) { // app user
                                    $author = $comment['author']['username'];
                                } elseif(isset($comment['wpf_author']) && isset($comment['wpf_author']['first_name']) && $comment['wpf_author']['first_name'] != ''){ // wpf user
                                    $author = $comment['wpf_author']['first_name'] . ' '. $comment['wpf_author']['last_name'];
                                } else {
                                    $author = get_the_author_meta('first_name', $comment["wpf_user_id"]) . ' ' . get_the_author_meta('last_name', $comment["wpf_user_id"]);
                                    if ( empty(trim($author)) ) {
                                        $task_author = get_user_by('ID', intval($author_id));
                                        if ( !$task_author ) {
                                            $author = "Deleted User";                                            
                                        } else {
                                            if ( !is_array($comment['wpf_author']) && !is_null($comment['wpf_author']) ) {
                                                $author = $comment['wpf_author'];
                                            } else {
                                                $author = 'Guest';
                                            }
                                        }
                                    }
                                }
                                

                                if ( (!empty($comment['response'])) && !empty($comment['inbox']) ) {
                                    if ( $comment['response']['delivery_status'] === 'outgoing' ) {
                                        $author = (!empty($comment['inbox'])) ? $comment['inbox']['from_name'] : "Guest";
                                    } elseif ( $comment['response']['delivery_status'] === 'incoming' ) {
                                        $from_address = $comment['response']['from_address'];
                                        if ( strpos($from_address, '<') !== false ) {
                                            $parts1 = explode('<', $from_address);
                                            $parts2 = explode('>', $parts1[1]);
                                
                                            $from_address = $parts2[0];
                                        }
                                        $author = $from_address;
                                    }
                                }
                            }

                            $new_class = "";
                            $image_class = "";
                            $file_class = "";
                            if($comment['is_log'] == 1){
                                    $new_class = " is_info";
                            }
                            if ($current_user_id == $author_id) {
                                $class = "chat_author".$new_class;
                            } else {
                                $class =' '.$file_class.' '.$image_class."not_chat_author".$new_class;
                            }

                            $name = "<div class='wpf_initials'>" . $author . "</div>";
                            $image_dwn_icon = "<span id='wpf_push_media' class='wpf_push_media wpf_image_download'>" . get_wpf_push_to_media_icon() . "</span><span id='wpf_image_open' class='wpf_image_open' onclick='wpf_image_open_new_tab(this)'>" . get_wpf_image_open_icon();
                            // $comment_text = '<div class="meassage_area_main">';
                            if (strpos($comment['comment_content'], 'wpfeedback-image.s3') !== false) {
                                if ($comment['comment_type'] == 'image/png' || $comment['comment_type'] == 'image/gif' || $comment['comment_type'] == 'image/jpeg') {
                                    $comment_text = '<a href="' . $comment['comment_content'] . '" target=_blank><div class="tag_img" style="width: 275px;height: 183px;"><div class="meassage_area_main"><a href="'.$comment['comment_content'] .'" target="_blank"></a><img src="' . $comment['comment_content'] . '" style="width: 100%;" class="wpfb_task_screenshot">'.$image_dwn_icon.'</div></div></a>';
                                    $image_class = " is_image";
                                } else {
                                    $file_class = " is_file";
                                    $file_name = preg_replace('/\.[^.]$/', '', basename($comment['comment_content']));
                                    $comment_text = '<a href="' . $comment['comment_content'] . '" download><div class="tag_file""><i class="gg-software-download"></i> ' . $file_name . '</div></a>';
                                }
                            }
                            else if (wp_http_validate_url($comment['comment_content']) && !strpos($comment['comment_content'], 'wpfeedback-image.s3')) {
                                $idVideo = $comment['comment_content'];
                                $link = explode("?v=",$idVideo);
                                if ($link[0] == 'https://www.youtube.com/watch') {
                                    $youtubeUrl = "http://www.youtube.com/oembed?url=$idVideo&format=json";
                                    $docHead = get_headers($youtubeUrl);
                                    if (substr($docHead[0], 9, 3) !== "404") {
                                        //$comment_type=3;
                                        $comment_text = '<iframe width="100%" height="150" src="https://www.youtube.com/embed/'.$link[1].'" type="text/html" width="500" height="265" frameborder="0" allowfullscreen></iframe>';
                                    }
                                    else {
                                        $comment_text =$comment['comment_content'];
                                    }
                                }else{
                                    // $comment_text =preg_replace('/<a /','<a target="_blank" ', $comment['comment_content']);
                                    $comment_text = "<a target='_blank' href='".$comment['comment_content']."'>".$comment['comment_content']."</a>";
                                }
                            }
                            else {
                                $comment_text = stripslashes($comment['comment_content']);
                            }
                            // $comment_text .= '</div>';

                            $edit_delete_button_html = '';
                            $edited_html = '';

                            if($comment['is_edited'] && !$comment['is_deleted']){
                                $edited_html='<span class="wpf-is-edited">(edited)<span class="wpf_tooltiptext edit_tooltip_text">'.$comment['updated_at'].'</span></span>';
            
                            }else{
                                $edited_html='';
                            }

                            if ( (!$comment['is_log']) && (!$comment['is_deleted']) ) {
                                $edit_delete_button_html = '<div class="wpf-edit-delete-wrapper">';

                                $edit_delete_button_html .= '<a href="javascript:void(0)" style="display:none"></a><a href="javascript:void(0)" onclick="wpf_edit_box_active('.$comment['id'].')" class="wpf_edit_box_active" id="wpf_edit_box_active"><i class="gg-pen"></i></a><a href="javascript:void(0)" class="wpf_comment_delete_btn" onclick="wpf_delete_comment('.$comment['id'].')"><i class="gg-trash"></i></a></div>';
                            }

                            $response['data'] .= '<li class="' . $class .' '.$file_class.' '.$image_class.'" id="task_comments_'.intval($key + 1).'" data-comment_id="'.$comment['id'].'" title="' . $curr_comment_time['comment_time'] . '"><level class="wpf-author">' . $author . ' <span>' . 
                            $curr_comment_time['comment_time'] . '</span>'.$edit_delete_button_html.'</level><p class="task_text" id="wpf-chat-text-'.$comment['id'].'">' . nl2br($comment_text) . '</p>'.$edited_html.'</div><div id="wpfb-edit-comment-wrapper-'.$comment['id'].'" class="wpfb-edit-comment-wrapper"><textarea class="form-control wpfb-edit-comment" data-comment_id="'.$comment['id'].'" placeholder="Edit the comment..." spellcheck="false">' . nl2br($comment_text) . '</textarea><button class="wpf_edit_comment_btn" onclick="wpfb_edit_comment('.$comment['id'].')">Update</button><a class="wpf-cancel-edit-comment" onclick="wpfb_cancel_edit_comment('.$comment['id'].')" href="javascript:void(0)">Cancel</a></div></li>';
                        
                    }
                } else {
                    $response['data'] = '<li id="wpf_not_found">No comments found</li>';
                }
            } else {
                $response['data'] = '<li id="wpf_not_found">No comments found</li>';
            }
            echo json_encode($response);
            die();
        }
        else{
            $response = '<li id="wpf_not_found">Something wrong</li>';
            echo $response;
            die();
        }
    }
}

/*
 * This function is used to add new comments from the "Tasks Center". It is been called from function wpf_backed_scripts() in wpfeedback.php. The function hadles posting of all types of comments like text, images files etc.
 *
 * @input Array ( $_POST )
 * @return String (HTML to add comment to the chat)
 */
add_action('wp_ajax_insert_wpf_comment_func', 'insert_wpf_comment_func');
add_action('wp_ajax_nopriv_insert_wpf_comment_func', 'insert_wpf_comment_func');
if (!function_exists('insert_wpf_comment_func')) {
    function insert_wpf_comment_func() {
	global $wpdb;
	wpf_security_check();
	$wpf_comment = $_POST['wpf_comment'];
	$post_id = $_POST['post_id'];
	$author_id = $_POST['author_id'];

        try {
            if ($wpf_comment) {
                $url = WPF_CRM_API . 'wp-api/comment/create';
                $sendarr = array();
                $sendarr["task_id"] = $post_id;
                $sendarr["comment_content"] = stripslashes(html_entity_decode($wpf_comment, ENT_QUOTES, 'UTF-8'));
                $sendarr["wpf_user_id"] = $author_id;
                $sendarr["user_id"] = "";
                $sendarr["is_note"] = "0";
                $sendtocloud = json_encode($sendarr);
                $comment_info = wpf_send_remote_post($url, $sendtocloud);
                // echo json_encode($comment_info); die;
                if (isset($comment_info['status']) && isset($comment_info['data'])) {
                $comment_info = $comment_info['data'];
                //$comment_id = $comment_info["id"];
                if ($post_id) {
                    $url = WPF_CRM_API . 'wp-api/task/detail';
                    $sendarr = array();
                    $sendarr["task_id"] = $post_id;
                    $sendtocloud = json_encode($sendarr);
                    $task_status = wpf_send_remote_post($url, $sendtocloud)["data"]["task_status"];

                    if ($task_status == 'complete') {
                    $url = WPF_CRM_API . 'wp-api/assign/status';
                    $sendarr = array();
                    $sendarr["task_id"] = $post_id;
                    $sendarr["task_status"] = "open";
                    $sendtocloud = json_encode($sendarr);
                    wpf_send_remote_post($url, $sendtocloud);
                    }
                }

                // display the results
                //foreach ($comment_info as $info) {
                $class = "chat_author";
                //$task_date = get_comment_date('Y-m-d H:i:s', $info->comment_ID);
                $task_date = get_comment_date('Y-m-d H:i:s', $comment_info["comment_date"]);
                $task_date1 = date_create($task_date);
                //Old Logic to get current time. Was creating issues when displaying message
                //$task_date2 = new DateTime('now');
                //New Logic to get current time.
                $wpf_wp_current_timestamp = date('Y-m-d H:i:s', current_time('timestamp', 0));
                $task_date2 = date_create($wpf_wp_current_timestamp);

                $curr_comment_time = wpfb_time_difference($task_date1, $task_date2);
                $author_id = $comment_info["user_id"];
                if ($comment_info["wpf_user_id"]) {
                    $author = get_the_author_meta('first_name', $comment_info["wpf_user_id"]).' '.get_the_author_meta('last_name', $comment_info["wpf_user_id"]);
                    if(trim($author) == '') {
                        $author = get_the_author_meta('nickname', $comment_info["wpf_user_id"]);    
                    }
                    // if(trim($author) == '') {
                    //     $author = get_the_author_meta('first_name', $comment_info["wpf_user_id"]).' '.get_the_author_meta('last_name', $comment_info["wpf_user_id"]);
                    // }
                } else if ($author_id) {
                    //$author = get_the_author_meta('display_name', $info->user_id);
                    $author = get_the_author_meta('first_name', $comment_info["user_id"]);
                    if ( empty($author) ) {
                        $author = get_the_author_meta('display_name', $comment_info["user_id"]);
                    }
                } else {
                    $author = 'ME';
                }

                $image_dwn_icon = '';
                if (strpos($comment_info["comment_content"], 'wpfeedback-image.s3') !== false) {// all files which are uploaded on s3
                    if ($comment_info['comment_type'] == 'image/png' || $comment_info['comment_type'] == 'image/gif' || $comment_info['comment_type'] == 'image/jpeg') {
                    $image_dwn_icon = "<span class='wpf_image_download'>" . get_wpf_image_download_icon() . "</span><span id='wpf_image_open' class='wpf_image_open' onclick='wpf_image_open_new_tab(this)'>" . get_wpf_image_open_icon() . "</span><span class='wpf_image_delete'>" . get_wpf_close_icon() . "</span>";
                    $comment = '<a href="' . $comment_info["comment_content"] . '" target=_blank><div class="tag_img" style="width: 275px;height: 183px;"><img src="' . $comment_info["comment_content"] . '" style="width: 100%;" class="wpfb_task_screenshot"></div></a>';
                    } else {
                    $file_name = preg_replace('/\.[^.]$/', '', basename($comment_info["comment_content"]));
                    $comment = '<a href="' . $comment_info["comment_content"] . '" download><div class="tag_img" style="width: 275px;height: 183px;"><i class="gg-software-download"></i> ' . $file_name . '</div></a>';
                    }
                } else if (wp_http_validate_url($comment_info["comment_content"]) && !strpos($comment_info["comment_content"], 'wpfeedback-image.s3')) { // youtube
                    $idVideo = $comment_info["comment_content"];
                    $link = explode("?v=", $idVideo);
                    if ($link[0] == 'https://www.youtube.com/watch') {
                        $youtubeUrl = "http://www.youtube.com/oembed?url=$idVideo&format=json";
                        $docHead = get_headers($youtubeUrl);
                        if (substr($docHead[0], 9, 3) !== "404") {
                            //$comment_type=3;
                            $comment = '<iframe width="100%" height="150" src="https://www.youtube.com/embed/' . $link[1] . '" type="text/html" width="500" height="265" frameborder="0" allowfullscreen></iframe>';
                        } else {
                            $comment = $comment_info["comment_content"];
                        }
                    } else {
                        $comment = preg_replace('/<a /', '<a target="_blank" ', $comment_info["comment_content"]);
                    }
                } else { // text
                    $comment = $comment_info["comment_content"];
                }
                $profile_image = "<div class='chat_initials'>" . $author . " <span>" . __("just now", "wpfeedback") . "</span></div>";

                $edit_delete_button_html = '<div class="wpf-edit-delete-wrapper"><a href="javascript:void(0)" onclick="wpf_edit_box_active('.$comment_info['id'].')" class="wpf_edit_box_active" id="wpf_edit_box_active"><i class="gg-pen"></i></a><a href="javascript:void(0)" class="wpf_comment_delete_btn"  onclick="wpf_delete_comment('.$comment_info['id'].')" ><i class="gg-trash"></i></a></div>';

                $textarea_comment_edit = '<div id="wpfb-edit-comment-wrapper-'.$comment_info['id'].'" class="wpfb-edit-comment-wrapper"><textarea class="form-control wpfb-edit-comment" data-comment_id="'.$comment_info['id'].'" placeholder="Edit the comment..." spellcheck="false">'.$comment.'</textarea><button class="wpf_edit_comment_btn" onclick="wpfb_edit_comment('.$comment_info['id'].')">Update</button><a class="wpf-cancel-edit-comment" onclick="wpfb_cancel_edit_comment('.$comment_info['id'].')" href="javascript:void(0)">Cancel</a></div>';

                echo '<li class=' . $class . '  data-comment_id="'.$comment_info["id"].'"><level class="wpf-author">' . $profile_image . '</level>'.$edit_delete_button_html .'<div class="meassage_area_main"><a href="'. $comment .'" target="_blank"></a>' . $image_dwn_icon . '<p class="chat_text" id="wpf-chat-text-'.$comment_info["id"].'">' . nl2br($comment) . '</p></div>'.$textarea_comment_edit.'</li>';
                //}
                } else {
                    if ( !empty($comment_info['limit']) && $comment_info['limit']===true ) {
                        echo json_encode($comment_info);
                    } else {
                        echo 'Error saving comment. Please contact administrator.';
                    }
                }
                /*Clear cache if checkbox enabled in settings */
                if (get_option('wpf_enable_clear_cache') == 'yes') {
                    clearObjectCache();
                }
                die();
            }
        } catch (Exception $ex) {}
    }
}

/*
 * This function is used to get the email of current user. This function is not used currently.
 *
 * @input NULL
 * @return String
 */
add_action('wp_ajax_wpf_notify_admin_add_ons_func', 'wpf_notify_admin_add_ons_func');
if (!function_exists('wpf_notify_admin_add_ons_func')) {
    function wpf_notify_admin_add_ons_func()
    {
        wpf_security_check();
        $current_user_id = get_current_user_id();
        $user_info = get_userdata($current_user_id);
        echo $user_info->user_email;
        echo $add_ons_name = $_POST['add_ons'];
        exit;
    }
}

/*
 * This function is used to create a new task. This function is used to create task from Frontend Pages, Backend Tasks Center, Graphics and API.
 *
 * @input Array ( $_POST['new_task'] )
 * @return Int (Task ID)
 */
add_action('wp_ajax_wpf_add_new_task','wpf_add_new_task');
add_action('wp_ajax_nopriv_wpf_add_new_task','wpf_add_new_task');
if (!function_exists('wpf_add_new_task')) {
    function wpf_add_new_task()
    {
        global $wpdb, $current_user;
        wpf_security_check();
        if ($current_user->ID == 0) {
            $user_id = get_site_data_by_key('wpf_website_client');
        } else {
            $user_id = $current_user->ID;
        }
        

        $comment_count = get_last_task_id();
        
//        $wpf_every_new_task = get_option('wpf_every_new_task');
        $task_data = $_POST['new_task'];

        foreach ($task_data as $key=>$val){
            $task_data[$key]=filter_var($val,FILTER_SANITIZE_STRING);
        }

        if ($task_data['task_page_title'] == '' && !is_admin()) {
            $task_data['task_page_title'] = get_the_title($task_data['current_page_id']);
        }
        if ($task_data['task_page_url'] == '') {
            $task_data['task_page_url'] = $current_page_url = get_permalink($task_data['current_page_id']);
        }

        if (isset($task_data['wpf_current_screen']) && $task_data['wpf_current_screen'] != '') {
            $wpf_current_screen = $task_data['wpf_current_screen'];
//            $post_status = 'wpf_admin';
        } else {
            $wpf_current_screen = '';
//            $post_status = 'publish';
        }

	
        $task_data["wpf_site_id"]= get_option("wpf_site_id");
        $task_data['wpf_task_id'] = $comment_count;

        // $task_data['internal'] = $comment_count;
	
//	$task_data['task_element_path'] = '';
	if(isset($task_data['task_element_path'])) {
	    $task_data['task_element_path'] = str_replace(".active_comment", "", $task_data['task_element_path']);
	    $task_data['task_element_path'] = str_replace(".logged-in.admin-bar", "", $task_data['task_element_path']);
	    $task_data['task_element_path'] = str_replace(".no-customize-support", "", $task_data['task_element_path']);
	    $task_data['task_element_path'] = str_replace(".customize-support", "", $task_data['task_element_path']);
	    $task_data['task_element_path'] = str_replace(".gf_browser_chrome", "", $task_data['task_element_path']);
	    $task_data['task_element_path'] = str_replace(".gf_browser_gecko", "", $task_data['task_element_path']);
	    $task_data['task_element_path'] = str_replace(".wpfb_task_bubble", "", $task_data['task_element_path']);
	}

        //logic to create emails of users to be notified
        //$task_data['task_notify_users']=get_notify_users_emails($task_data['task_notify_users']);
         $task_data['task_comment_message']=wpf_test_input($task_data['task_comment_message']);
         $url = WPF_CRM_API.'wp-api/task/create';
         $sendtocloud=json_encode($task_data);

         $res =  wpf_send_remote_post($url,$sendtocloud);
         echo json_encode($res);exit();
    }
}

/*
 * This function is used to create a new comment for the task. This function is used to create comment from Frontend Pages, Backend Tasks Center And Graphics.
 *
 * @input Array ( $_POST['new_task'] )
 * @return String (Message)
 */
add_action('wp_ajax_wpfb_add_comment','wpfb_add_comment');
add_action('wp_ajax_nopriv_wpfb_add_comment','wpfb_add_comment');
if (!function_exists('wpfb_add_comment')) {
    function wpfb_add_comment()
    {
        global $wpdb;
        wpf_security_check();
        $enabled_wpfeedback = wpf_check_if_enable();
        $wpf_every_new_comment = get_site_data_by_key('wpf_every_new_comment');
        if($enabled_wpfeedback == 1){
            $task_data = $_POST['new_task'];
            
            $task_data['wpf_user_id'] = $task_data['user_id'];
            $task_data['user_id'] = "";
            $url = WPF_CRM_API.'wp-api/comment/create';
            $sendtocloud=json_encode($task_data);
            // print_r(wpf_send_remote_post($url,$sendtocloud));//["data"]["id"];
            echo json_encode(wpf_send_remote_post($url,$sendtocloud));//["data"]["id"];
	        /*Clear cache if checkbox enabled in settings */
            if (get_option('wpf_enable_clear_cache') == 'yes') {
		        clearObjectCache();
            }
            exit;
        } else {
            echo 0;
        }
        exit;
    }
}
/*clear object cache function */
function clearObjectCache() {
    wp_cache_flush();
}
/*
 * This function is used to load all the tasks. This function is used to load tasks on Frontend Sidebar, Backend Sidebar and Graphics Sidebar.
 *
 * @input Array ( $_POST )
 * @return JSON (Listing of all tasks)
 */
add_action('wp_ajax_load_wpfb_tasks','load_wpfb_tasks');
add_action('wp_ajax_nopriv_load_wpfb_tasks','load_wpfb_tasks');
if (!function_exists('load_wpfb_tasks')) {
    function load_wpfb_tasks(){
        wpf_security_check();
        ob_start();
        ob_clean();
        global $wpdb,$current_user;
        $current_user_id = $current_user->ID;
        $comment = "";
        $wpf_check_all_pages=0;
        $response = array();
//        $task_page_url = '';
//        if(isset($_POST['task_page_url']) && $_POST['task_page_url'] != "") {
//            $key = 'wpf_taskid';
//            $key1 = "wpf_general_taskid";
//            $task_page_url = preg_replace('~(\?|&)'.$key.'=[^&]*~', '$1', $_POST['task_page_url']);
//            $task_page_url = preg_replace('~(\?|&)'.$key1.'=[^&]*~', '$1', $task_page_url);
//            $task_page_url = str_replace('?', '', $task_page_url);
//        }
        
        if((isset($_POST['current_page_id']) && $_POST['current_page_id'] != '') || (isset($_POST["fallback_link_check"]) && $_POST["fallback_link_check"] == 1)){ // default current page task
                $post_data = array(
//                    'task_page_url' => $task_page_url,
                    'wpf_site_id' => get_option('wpf_site_id'),
//                    'task_page_title' => get_the_title($_POST['current_page_id']),
                    // 'task_types' => ['general','page'],
                    'url'   => home_url(),
                    'task_types' => ['general','email','page'],
                    "sort" =>  ["task_title", "created_at"],
                    "sort_by" => "asc",
                    "current_page_id" => $_POST['current_page_id'],
                    "url" => home_url()
                );

                $url = WPF_CRM_API.'wp-api/all/task';
                $sendtocloud=json_encode($post_data);
                $wpfb_tasks = wpf_send_remote_post($url,$sendtocloud);
                // echo json_encode($wpfb_tasks); die;
        } else if (isset($_POST['task_id']) && $_POST['task_id'] != ""){ // load by task id

                $post_data = array(
                    'task_id'=>$_POST['task_id'],
                    'post_type'=>'wpfeedback',
                    'wpf_site_id' => get_option('wpf_site_id'),
                    'url'   => home_url()
//                    'task_types' => ['general']
                );

                $url = WPF_CRM_API.'wp-api/all/task';
                $sendtocloud=json_encode($post_data);
                $wpfb_tasks = wpf_send_remote_post($url,$sendtocloud);
        } else{ // in backend, fronted tab
                $wpf_check_all_pages=1;
                $post_data = array(
                        //'task_page_url' => $task_page_url,
                        'wpf_site_id' => get_option('wpf_site_id'),
//                        'task_page_title' => '',
                        'task_types' => [],
                        "current_page_id" => '',
                        'post_type'   => 'wpfeedback',
                        'numberposts' => -1,
                        'post_status' => 'any',
                        'orderby'    => 'date',
                        'order'       => 'DESC',
                        'url'   => home_url(),
                        'from_admin' => isset($_POST['from_admin']) ? $_POST['from_admin'] : 0,
                        'is_admin_task' => 0,
                        "url" => home_url()
                );

                $url = WPF_CRM_API.'wp-api/all/task';
                $sendtocloud=json_encode($post_data);
                $wpfb_tasks = wpf_send_remote_post($url,$sendtocloud);
                //re($wpfb_tasks);
                $response = process_task_response($wpfb_tasks);
            }

            // echo json_encode($wpfb_tasks); die;
        

        if(!empty($wpfb_tasks) && $wpfb_tasks['status']!==false){
            $response = process_task_response($wpfb_tasks);
        }
        add_action('wp_ajax_wpf_initial_setup_done', 'wpf_initial_setup_done');
        
        // milestone
        // if ( is_feature_enabled( 'project_stages' ) ) {
        //     if( !empty($wpfb_tasks) && !is_null($wpfb_tasks['milestone']) ){
        //         $response['milestone'] = $wpfb_tasks['milestone'];
        //     }
        // }

        update_option('restrict_plugin',$wpfb_tasks['limit'],'no');

        ob_end_clean();
        echo json_encode($response);
        exit;
    }
}



/*
 * This function is used to load all graphics tasks.
 *
 * @input Array ( $_POST )
 * @return JSON (Listing of all tasks)
 */
add_action('wp_ajax_load_wpfb_graphic_tasks','load_wpfb_graphic_tasks');
add_action('wp_ajax_nopriv_load_wpfb_graphic_tasks','load_wpfb_graphic_tasks');
if (!function_exists('load_wpfb_graphic_tasks')) {
    function load_wpfb_graphic_tasks(){
        wpf_security_check();
        ob_start();
        ob_clean();
        $response = array();

        $post_data = array(
            'wpf_site_id' => get_option('wpf_site_id'),
            'graphic_id' => $_POST['graphic_id']
	    );
    
        $url = WPF_CRM_API.'wp-api/list/graphic/WPtasks';
        $sendtocloud=json_encode($post_data);
        $wpfb_tasks = wpf_send_remote_post($url,$sendtocloud);
        if(!empty($wpfb_tasks)){
            $response = process_task_response($wpfb_tasks);
        }
        ob_end_clean();
        echo json_encode($response);
        exit;
    }
}

/*
 * This function is used to set the priority of the tasks. This function is used to set priority from Frontend Pages, Backend Pages, Backend Tasks Center and Graphics Tasks.
 *
 * @input Array ( $_POST['task_info'] )
 * @return Boolean
 */
add_action('wp_ajax_wpfb_set_task_priority','wpfb_set_task_priority');
add_action('wp_ajax_nopriv_wpfb_set_task_priority','wpfb_set_task_priority');
if (!function_exists('wpfb_set_task_priority')) {
    function wpfb_set_task_priority()
    {

        wpf_security_check();
        $task_info = $_POST['task_info'];
        do_action('wpf_crm_update_task_priority_action',$task_info);
        $args = array(
            'task_id' => $task_info['task_id'],
            'type' => 'wp_feedback',
            'orderby'  => 'comment_date',
            'order' => 'DESC',
        );

        $url = WPF_CRM_API.'wp-api/comment/list';
        $sendtocloud=json_encode($args);
        $comments_info = wpf_send_remote_post($url,$sendtocloud);
        if($comments_info['status'] == 200) {
            echo $comments_info['data'][0]['comment_content'];
        }
        exit;
    }
}


/*
 * This function is used to set the status of the tasks and send notifications to the user based on the status. This function is used to set status from Frontend Pages, Backend Pages, Backend Tasks Center and Graphics Tasks.
 *
 * @input Array ( $_POST['task_info'] )
 * @return Boolean
 */
add_action('wp_ajax_wpfb_set_task_status','wpfb_set_task_status');
add_action('wp_ajax_nopriv_wpfb_set_task_status','wpfb_set_task_status');
if (!function_exists('wpfb_set_task_status')) {
    function wpfb_set_task_status()
    {

            wpf_security_check();

            $wpf_every_status_change = get_site_data_by_key('wpf_every_status_change');
            $wpf_every_new_complete = get_site_data_by_key('wpf_every_new_complete');
            $task_info = $_POST['task_info'];
            do_action('wpf_crm_update_task_status_action',$task_info);
            $args = array(
                'task_id' => $task_info['task_id'],
                'type' => 'wp_feedback',
                'orderby'  => 'comment_date',
                'order' => 'DESC',
            );

            $url = WPF_CRM_API.'wp-api/comment/list';
            $sendtocloud=json_encode($args);
            $comments_info = wpf_send_remote_post($url,$sendtocloud);
            if($comments_info['status'] == 200) {
                echo $comments_info['data'][0]['comment_content'];
            }
            exit;
    }
}

/*
 * This function is used to set the edit the comments
 * @input Array ( $_POST['task_info'] )
 * @return Boolean
 */
add_action('wp_ajax_wpfb_edit_comment','wpfb_edit_comment');
add_action('wp_ajax_nopriv_wpfb_edit_comment','wpfb_edit_comment');
if (!function_exists('wpfb_edit_comment')) {
    function wpfb_edit_comment()
    {

            wpf_security_check();
            $task_info = $_POST['task_info'];
            $args = array(
                'comment_id' => $task_info['comment_id'],
                'comment_content' => $task_info['comment_content'],
            );

            $url = WPF_CRM_API.'wp-api/comment/updateContent';
            $sendtocloud=json_encode($args);
            $comments_info = wpf_send_remote_post($url,$sendtocloud);
            
            if($comments_info['status'] == 200) {
                //echo $comments_info['data'][0]['comment_content'];
                //print_r($comments_info);
                echo "1";
            }
            exit;
    }
}

/*
 * This function is used to delete the comments
 * @input Array ( $_POST['task_info'] )
 * @return Boolean
 */
add_action('wp_ajax_wpf_delete_comment','wpf_delete_comment');
add_action('wp_ajax_nopriv_wpf_delete_comment','wpf_delete_comment');
if (!function_exists('wpf_delete_comment')) {
    function wpf_delete_comment()
    {

            wpf_security_check();
            $task_info = $_POST['task_info'];
            $args = array(
                'comment_id' => $task_info['comment_id'],
                // 'comment_content' => $task_info['comment_content'],
                'user_id' => $_POST['current_user_id'],
                'from_wp' => true,
            );

            $url = WPF_CRM_API.'wp-api/comment/trash';
            $sendtocloud=json_encode($args);
            $comments_info = wpf_send_remote_post($url,$sendtocloud);
            //print_r($comments_info['data']);
            if($comments_info['status'] == 200) {
                //echo $comments_info['data'][0]['comment_content'];
                // print_r($comments_info['data']);
                //echo "1";
                echo json_encode( $comments_info['data'] );
            }
            exit;
    }
}

/*
 * This function is used to mark the tasks as internal. This function is used to mark internal from Frontend Pages, Backend Pages, Backend Tasks Center and Graphics Tasks.
 *
 * @input Array ( $_POST['task_info'] )
 * @return Boolean
 */
add_action('wp_ajax_wpfb_mark_as_internal','wpfb_mark_as_internal');
add_action('wp_ajax_nopriv_wpfb_mark_as_internal','wpfb_mark_as_internal');
if (!function_exists('wpfb_mark_as_internal')) {
    function wpfb_mark_as_internal()
    {

            wpf_security_check();

            // $wpf_every_status_change = get_site_data_by_key('wpf_every_status_change');
            // $wpf_every_new_complete = get_site_data_by_key('wpf_every_new_complete');
            $task_info = $_POST['task_info'];
            ob_start();
            do_action('wpf_crm_mark_as_internal',$task_info);
            $response = ob_get_clean();
            echo $response;
            // $args = array(
            //     'task_id' => $task_info['task_id'],
            //     'type' => 'wp_feedback',
            //     'orderby'  => 'comment_date',
            //     'order' => 'DESC',
            // );

            // $url = WPF_CRM_API.'wp-api/comment/list';
            // $sendtocloud=json_encode($args);
            // $comments_info = wpf_send_remote_post($url,$sendtocloud);
            // if($comments_info['status'] == 200) {
            //     echo $comments_info['data'][0]['comment_content'];
            // }
            exit;
    }
}

add_action('wp_ajax_wpfb_approve_page','wpfb_approve_page');
add_action('wp_ajax_nopriv_wpfb_approve_page','wpfb_approve_page');
if (!function_exists('wpfb_approve_page')) {
    function wpfb_approve_page()
    {

            wpf_security_check();

            $task_info['page_id'] = $_POST['page_id'];
            $task_info['set_tasks_complete'] = $_POST['complete_tasks'];
            $task_info['user_id'] = $_POST['current_user_id'];
            $task_info['wpf_site_id'] = get_option('wpf_site_id');
            $url = WPF_CRM_API.'wp-api/approve-page';
            $sendtocloud=json_encode($task_info);
            $res = wpf_send_remote_post($url,$sendtocloud);

            if ($res['status']) {
                echo 1;
            } else {
                echo json_encode($res);
            }
            exit;
    }
}

/*
 * This function is used to set the notify users for the task. This function is used to set notify users from Frontend Pages, Backend Pages, Backend Tasks Center and Graphics Tasks.
 *
 * @input Array ( $_POST['task_info'] )
 * @return Boolean
 */
add_action('wp_ajax_wpfb_set_task_notify_users','wpfb_set_task_notify_users');
add_action('wp_ajax_nopriv_wpfb_set_task_notify_users','wpfb_set_task_notify_users');
if (!function_exists('wpfb_set_task_notify_users')) {
    function wpfb_set_task_notify_users()
    {
        wpf_security_check();
        $task_notify_users = filter_var($_POST['task_info']['task_notify_users'],FILTER_SANITIZE_STRING);
        $task_data['notify_users'] = $task_notify_users;
        $task_data['task_id'] = $_POST['task_info']['task_id'];
        $task_data['method'] = "notify_users";
        $task_data['from_wp'] = 1;
        $url = WPF_CRM_API.'wp-api/task/update-task-details';
        $sendtocloud=json_encode($task_data);
        $res = wpf_send_remote_post($url,$sendtocloud);
        if ($res['status'] == 1 || $res['status'] == 200) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }
}

/*
 * This function is used to take the screenshot and add to the task. This function is used to take screenshots from Frontend Pages, Backend Pages, Backend Tasks Center and Graphics Tasks.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
add_action('wp_ajax_wpfb_save_screenshot','wpfb_save_screenshot_function');
add_action('wp_ajax_nopriv_wpfb_save_screenshot','wpfb_save_screenshot_function');
if (!function_exists('wpfb_save_screenshot_function')) {
    function wpfb_save_screenshot_function()
    {

        global $wpdb, $current_user;

        wpf_security_check();

        if ($current_user->ID == 0) {
            $task_config_author_id = get_site_data_by_key('wpf_website_client');
        } else {
            $task_config_author_id = $current_user->ID;
        }

        $image = $_POST['image'];
        $task_screenshot = $_POST['task_screenshot'];
        $post_id = $task_screenshot['post_id'];

        if($_POST["autoscreen"]=="1"){
        
         $image_parts = explode("base64,", $image);

        //$invalid = wpf_verify_file_upload($_SERVER, $image_parts[1]);

        $url = WPF_CRM_API.'wp-api/task/image/upload';
        $sendarr=array();
        $sendarr["task_id"]=$post_id;
        $sendarr["task_screenshot"]=$image;
        $sendtocloud=json_encode($sendarr);
        //print_r(wpf_send_remote_post($url,$sendtocloud));
        print_r(wpf_send_remote_post($url,$sendtocloud)["data"]);
        exit;
    }else{
        $temp_wpf_file_type ="image/jpeg";
        $file = $_POST['image'];
			//$file['realpath'] = $_FILES['wpf_upload_file']->getRealPath();
			$base64_image = $file;
			$args = [];
			$args['task_id'] = $post_id;
			$args['comment_content'] = $base64_image;
			$args['user_id'] = "";
			$args['wpf_user_id'] = $task_config_author_id;
            $args['file_upload'] =  1;
			$args['file_name'] = "Screenshot";
			$args['type'] = $temp_wpf_file_type;
			$url = WPF_CRM_API . 'wp-api/comment/create';
			$sendtocloud = json_encode($args);
            $res = wpf_send_remote_post($url, $sendtocloud);

			if ($res['status'] == 200) {
                echo $res['data']['comment_content'];
                die();
                }
            }
    }
}

/*
 * This function is used to reset the White Labeling settings from the backend.
 *
 * @input NULL
 * @return NULL
 */
add_action('wp_ajax_wpfeedback_reset_setting', 'wpfeedback_reset_setting');
add_action('wp_ajax_nopriv_wpfeedback_reset_setting', 'wpfeedback_reset_setting');
if (!function_exists('wpfeedback_reset_setting')) {
    function wpfeedback_reset_setting()
    {
        wpf_security_check();

	$options['wpf_site_id'] = get_option('wpf_site_id');
	$options['wpfeedback_color'] = '002157';
	$options['wpfeedback_logo'] = 'https://api.atarim.io/Atarim.svg';
    $options['wpfeedback_favicon'] = 'https://api.atarim.io/atarim_icon.svg';
	$options['wpf_powered_link'] = '';
	$options['wpf_tutorial_video'] = '';
	$options['wpfeedback_powered_by'] = 'no';
	$parms = [];
	foreach ($options as $key => $value) {
	    array_push($parms, ['name' => $key,'value' => $value]);
	}

	update_site_data($parms);
	
        echo 1;
        exit;
    }
}

/*
 * This function is used to resync the website with the dashboard app. It will clear the "wpf_check_license_date" and "wpf_initial_sync" which in turn will call the Initial sync from function "wpf_license_key_check_item" in wpf_function.php
 *
 * @input NULL
 * @return NULL
 */
add_action('wp_ajax_wpf_resync_dashboard', 'wpf_resync_dashboard');
add_action('wp_ajax_nopriv_wpf_resync_dashboard', 'wpf_resync_dashboard');
if (!function_exists('wpf_resync_dashboard')) {
    function wpf_resync_dashboard()
    {
        wpf_security_check();
        delete_option( 'wpf_check_license_date' );
        delete_option( 'wpf_initial_sync' );

        get_notif_sitedata_filterdata();

        echo 1;
        exit;
    }
}

/*
 * This function is used to verify the license from the backend Permissions tab.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
add_action('wp_ajax_wpf_license_verify_and_store', 'wpf_license_verify_and_store');
add_action('wp_ajax_nopriv_wpf_license_verify_and_store', 'wpf_license_verify_and_store');
if (!function_exists('wpf_license_verify_and_store')) {
    function wpf_license_verify_and_store()
    {
        wpf_security_check();
        $response = 0;
        $wpf_license_key = $_POST['wpf_license_key'];
//        $wpf_license = get_option('wpf_license');
        /*if ($wpf_license == 'valid') {
            $response = 1;
        } else {*/
            $outputObject = wpf_license_key_license_item($wpf_license_key);
            if ($outputObject['license'] == 'valid') {
//                update_option('wpf_license_key', $wpf_license_key, 'no');
                update_option('wpf_license', $outputObject['license'], 'no');
                update_option('wpf_license_expires', $outputObject['expires'], 'no');
                //if(!get_option('wpf_decr_key')){
                    update_option('wpf_decr_key', $outputObject['payment_id'],'no');
                    update_option('wpf_decr_checksum', $outputObject['checksum'],'no');
                    $wpf_crypt_key = wpf_crypt_key($wpf_license_key,'e');
                    update_option('wpf_license_key',$wpf_crypt_key,'no');
                //}
//                if(get_option('wpf_initial_sync')!=1){
                    do_action('wpf_initial_sync',$wpf_license_key);
//                }


                // sync users with dashboard
                syncUsers();

                // syncPages();
		
                $response = 1;
            } else {
                update_option('wpf_license', $outputObject['license'], 'no');
            }
        //}

        echo $response;
        exit;
    }
}


/* load site data => v2.1.0  */
add_action('wp_ajax_load_site_metadata', 'load_site_metadata');
add_action('wp_ajax_nopriv_load_site_metadata', 'load_site_metadata');
function load_site_metadata() {
    get_notif_sitedata_filterdata();
    die();
}

/*
 * This function is used to update the roles to be allowed to use plugin from the Permissions tab.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
add_action('wp_ajax_wpf_update_roles', 'wpf_update_roles');
add_action('wp_ajax_nopriv_wpf_update_roles', 'wpf_update_roles');
if (!function_exists('wpf_update_roles')) {
    function wpf_update_roles()
    {
        wpf_security_check();

	
	$roles = $_POST['task_notify_roles'];
        $wpf_allow_guest = $_POST['wpf_allow_guest'];
	
        $options['wpf_site_id'] = get_option('wpf_site_id');
	$options['wpf_allow_guest'] = $wpf_allow_guest;
	$options['wpf_selcted_role'] = $roles;
	$parms = [];
	foreach ($options as $key => $value) {
	    array_push($parms, ['name' => $key,'value' => $value]);
	}

	update_site_data($parms);
        echo 1;
        exit;
    }
}

/*
 * This function is used to set the Email Notification from the Settings tab.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
add_action('wp_ajax_wpf_update_notifications', 'wpf_update_notifications');
add_action('wp_ajax_nopriv_wpf_update_notifications', 'wpf_update_notifications');
if (!function_exists('wpf_update_notifications')) {
    function wpf_update_notifications()
    {
        wpf_security_check();
	
	$options['wpf_site_id'] = get_option('wpf_site_id');
	$options['wpf_every_new_task'] =  isset($_POST['wpf_every_new_task']) ? 'yes' : 'no';
	$options['wpf_every_new_comment'] =  isset($_POST['wpf_every_new_comment']) ? 'yes' : 'no';
	$options['wpf_every_new_complete'] =  isset($_POST['wpf_every_new_complete']) ? 'yes' : 'no';
	$options['wpf_every_status_change'] =  isset($_POST['wpf_every_status_change']) ? 'yes' : 'no';
	$options['wpf_daily_report'] =  isset($_POST['wpf_daily_report']) ? 'yes' : 'no';
	$options['wpf_weekly_report'] =  isset($_POST['wpf_weekly_report']) ? 'yes' : 'no';
	$options['wpf_auto_daily_report'] =  isset($_POST['wpf_auto_daily_report']) ? 'yes' : 'no';
	$options['wpf_auto_weekly_report'] =  isset($_POST['wpf_auto_weekly_report']) ? 'yes' : 'no';
	
	$parms = [];
	foreach ($options as $key => $value) {
	    array_push($parms, ['name' => $key,'value' => $value]);
	}

	update_site_data($parms);
	
        echo 1;
        exit;
    }
}

/*
 * This function is used to set the option that initial setup of the plugin was done from the backend.
 *
 * @input NULL
 * @return Boolean
 */
add_action('wp_ajax_wpf_initial_setup_done', 'wpf_initial_setup_done');
add_action('wp_ajax_nopriv_wpf_initial_setup_done', 'wpf_initial_setup_done');
if (!function_exists('wpf_initial_setup_done')) {
    function wpf_initial_setup_done()
    {
        global $current_user;
        wpf_security_check();
        $user_id = $current_user->ID;
        if (is_user_logged_in() && $user_id != '') {    
            $options['wpf_site_id'] = get_option('wpf_site_id');
            $options['enabled_wpfeedback'] =  'yes';
            $options['wpf_initial_setup'] =  'yes';

            update_user_meta($user_id, 'wpf_user_type', 'advisor');
            if(get_option('wpf_website_developer') == '' || get_option('wpf_website_developer') == '0')
            {
                $options['wpf_website_developer'] = $user_id; //set install default user
            }
            
            $wpf_pre_website_client = get_option('wpf_pre_website_client') ? get_option('wpf_pre_website_client') : 0;
            update_option('wpf_website_client', $wpf_pre_website_client);

            $options['wpf_website_client'] = $wpf_pre_website_client;

            $parms = [];
            foreach ($options as $key => $value) {
                array_push($parms, ['name' => $key,'value' => $value]);
            }

            update_site_data($parms);
            
            echo 1;
        }

        exit;
    }
}

/*
 * This function is used to delete the task from the website. This function is used from Frontend Pages, Backend Pages, Backend Tasks Center and Graphics Tasks.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
add_action('wp_ajax_wpfb_delete_task','wpfb_delete_task');
add_action('wp_ajax_nopriv_wpfb_delete_task','wpfb_delete_task');
if (!function_exists('wpfb_delete_task')) {
    function wpfb_delete_task()
    {
        wpf_security_check();
        $ids = [];

        foreach ($_POST['task_info'] as  $value) {
            array_push($ids,$value);
        }

        $args = array(
                    'task_id' => $ids
                );

        $url = WPF_CRM_API.'wp-api/task/delete';
        $sendtocloud = json_encode($args);
        $response = wpf_send_remote_post($url,$sendtocloud);
        //re($response);
        if ($response['status'] == 200) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }
}

add_action('wp_ajax_wpfb_delete_task_image','wpfb_delete_task_image');
add_action('wp_ajax_nopriv_wpfb_delete_task_image','wpfb_delete_task_image');
if (!function_exists('wpfb_delete_task_image')) {
    function wpfb_delete_task_image()
    {
        wpf_security_check();
        $task_img_url = $_POST['task_img_url'];
        $newtask_img_url = str_replace('https://wpfeedback-image.s3.us-east-2.amazonaws.com/','',$task_img_url);

        $args = array(
            'comment_id' => $_POST['comment_id'],
            'filepath' => $newtask_img_url,
        );

        $url = WPF_CRM_API.'delete-s3-images';
        $sendtocloud = json_encode($args);
        $response = wpf_send_remote_post($url,$sendtocloud);
        if ($response['status'] == 200) {
            echo 1;
        } else {
            // print_r(json_encode($response));
            echo 0;
        }
        exit;
    }
}
/*
 * This function is used to set the step 1 of the user onboarding wizard.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
add_action('wp_ajax_wpf_update_current_user_first_step', 'wpf_update_current_user_first_step');
add_action('wp_ajax_nopriv_wpf_update_current_user_first_step', 'wpf_update_current_user_first_step');
if (!function_exists('wpf_update_current_user_first_step')) {
    function wpf_update_current_user_first_step()
    {
        global $current_user;
        wpf_security_check();
        $user_id = $current_user->ID;
        if (is_user_logged_in() && $user_id != '') {
	    
	    $wpf_user_type = $_POST['wpf_user_type'];
            if ($wpf_user_type && ($wpf_user_type=='king' || $wpf_user_type=='advisor' || $wpf_user_type=='council')) {
                update_user_meta($user_id, 'wpf_user_type', $wpf_user_type);
                update_user_meta($user_id, 'wpf_user_initial_setup', 'yes');
            }
	    
	    $args = array(
		'wpf_site_id' => get_option('wpf_site_id'),
		'wpf_id' => $user_id,
		'username' => $current_user->data->user_login,
		'wpf_email' => $current_user->user_email,
		'first_name' => get_user_meta($user_id, 'first_name', true),
		'last_name' => get_user_meta($user_id, 'last_name', true),
//		'user_type' => isset($_POST['wpf_user_type']) ? $_POST['wpf_user_type'] : "",
		'role' => implode(',', $current_user->roles),
//		'wpf_user_initial_setup' => 'yes'
	    );

            $options  = []; 
            $options['wpf_site_id'] = get_option('wpf_site_id');
            $options['wpf_every_new_task'] = 1;
            $options['wpf_every_new_comment'] = 1;
            $options['wpf_every_new_complete'] = 1;
            $options['wpf_every_status_change'] = 1;
            $options['wpf_daily_report'] = 1;
            $options['wpf_weekly_report'] = 1;
            $options['wpf_auto_daily_report'] = 1;
            $options['wpf_auto_weekly_report'] = 1;
	    
	    $args['notifications'] = $options;
	    
	    $url = WPF_CRM_API.'wp-api/wpfuser/update';
            $res = wpf_send_remote_post($url,json_encode($args));
	    
            update_user_meta($user_id, 'wpf_user_initial_setup', 'yes');

            echo 1;
            exit;
        }else{
            echo 0;
            exit;
        }
    }
}

/*
 * This function is used to set the step 2 of the user onboarding wizard.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
add_action('wp_ajax_wpf_update_current_user_sec_step', 'wpf_update_current_user_sec_step');
add_action('wp_ajax_wpf_nopriv_update_current_user_sec_step', 'wpf_update_current_user_sec_step');
if (!function_exists('wpf_update_current_user_sec_step')) {
    function wpf_update_current_user_sec_step()
    {
        if(get_option('wpf_app_auto_task')) delete_option('wpf_app_auto_task');
        update_user_meta(get_current_user_id(), 'wpf_user_initial_setup', 'yes');

        /*pass the headers to clear litespeed server cache*/
        header("X-LiteSpeed-Purge: *");
        return 1;
        exit;
    }
}

/*
 * This function is used to upload the files for the task. This function is used from Frontend Pages, Backend Pages, Backend Tasks Center and Graphics Tasks.
 *
 * @input Array ( $_POST and $_FILES)
 * @return JSON
 */
add_action('wp_ajax_wpf_upload_file', 'wpf_upload_file');
add_action('wp_ajax_nopriv_wpf_upload_file', 'wpf_upload_file');
if (!function_exists('wpf_upload_file')) {

    function wpf_upload_file() {
	global $wpdb, $current_user;
	wpf_security_check();
	if ($current_user->ID == 0) {
	    $user_id = get_site_data_by_key('wpf_website_client');
	} else {
	    $user_id = $current_user->ID;
	}

	$wpf_taskid = $_POST['wpf_taskid'];
	if (!empty($_FILES)) {
	    foreach ($_FILES as $file) {
		if (is_array($file)) {

		    $wpf_allowed_filetypes = array("jpg", "jpeg", "png", "gif", "pdf", "doc", "docx", "xlsx", "xls", "mp4", "webm", "mov", "wmv", "avi", "ttf", "txt", "psd", "svg");
		    $temp_wpf_file_name = $_FILES["wpf_upload_file"]["name"];
		    $temp_wpf_file_type = $_FILES["wpf_upload_file"]["type"];

		    $tmp_f = explode(".", $temp_wpf_file_name);
		    $temp_wpf_file_ext = strtolower(end($tmp_f));
		    $fname = explode(".", $temp_wpf_file_name);
		    $temp_name = $_FILES['wpf_upload_file']['tmp_name'];
		    $data = file_get_contents($temp_name);
		    if ($temp_wpf_file_ext == 'xlsx' || $temp_wpf_file_ext == 'xls' || $temp_wpf_file_ext == 'docx' || $temp_wpf_file_ext == 'doc' || $temp_wpf_file_ext == 'avi' || $temp_wpf_file_ext = "psd") {
			$invalid = 0;
		    } else {
			$invalid = wpf_verify_file_upload($_SERVER, $data);
			$invalid = wpf_verify_file_upload_type($_SERVER, $temp_wpf_file_type);
		    }

		    $file_path = '';
		    if ($invalid == 0) {
			if (!in_array($temp_wpf_file_ext, $wpf_allowed_filetypes)) {
			    $invalid = 1;
			}
		    }

		    if ($invalid == 0) {

			$file_type = 0;
			if (in_array($temp_wpf_file_type, array('image/jpeg', 'image/png', 'image/gif'))) {
			    $file_type = 1;
			} else {
			    $file_type = 2;
			}

			$file = $_FILES['wpf_upload_file'];
			//$file['realpath'] = $_FILES['wpf_upload_file']->getRealPath();
			$base64_image = 'data:' . $temp_wpf_file_type . ';base64,' . base64_encode($data);
			$args = [];
			$args['task_id'] = $wpf_taskid;
			$args['comment_content'] = $base64_image;
			$args['user_id'] = "";
			$args['wpf_user_id'] = $user_id;
			$args['file_upload'] = !empty($_FILES['wpf_upload_file']) ? 1 : 0;
			$args['file_name'] = str_replace(' ', '_', trim($fname[0]));
			$args['type'] = $temp_wpf_file_type;
			//re($args);
			$url = WPF_CRM_API . 'wp-api/comment/create';
			$sendtocloud = json_encode($args);
			$res = wpf_send_remote_post($url, $sendtocloud);
//			if(isset(json_decode($res)->code) && json_decode($res)->code == 504){
//			    $response = array(
//				'status' => -1,
//				'message' => 'Something went Wrong!',
//			    );
//			    echo json_encode($response);
//			    die();
//			}

			if ($res['status'] == 200) {
			    $response = array(
				'status' => 1,
				'type' => $file_type,
				'message' => $res['data']['comment_content'],
                'comment_id' => $res['data']['id']
			    );
			} else {
			    $response = array(
				'status' => 0,
				'message' => 'invalid'
			    );
			}

			echo json_encode($response);
			die();
		    } else {
			$response = array(
			    'status' => 0,
			    'message' => 'invalid'
			);
		    }
		}
	    }
	}

	echo json_encode($response);
	die();
    }

}

/*
 * This function is used to get ID of the page by URL. This function is used from Frontend Pages and Graphics Tasks.
 *
 * @input Array ( $_POST )
 * @return JSON
 */
add_action('wp_ajax_wpf_get_page_id_by_url', 'wpf_get_page_id_by_url');
add_action('wp_ajax_nopriv_wpf_get_page_id_by_url', 'wpf_get_page_id_by_url');
if (!function_exists('wpf_get_page_id_by_url')) {
    function wpf_get_page_id_by_url()
    {
        global $wpdb;
        wpf_security_check();
        $siteurl = get_option('siteurl');
        $response = array();
        $current_page_url = $_POST['current_page_url'];

        if (substr($current_page_url, -1) == '/') {
            $current_page_url = substr($current_page_url, 0, -1);
        }

        if (substr($siteurl, -1) == '/') {
            $siteurl = substr($siteurl, 0, -1);
        }

        if ($siteurl == $current_page_url) {
            $home_page_id = get_option('page_on_front');
            if ($home_page_id == 0) {
                $home_page_id = $blog_id = get_option('page_for_posts');
            }
            $query = "SELECT `post_title` FROM `{$wpdb->prefix}posts` WHERE `ID` = '" . $home_page_id . "'";
            $page_info = $wpdb->get_results($query, OBJECT);

            $response['id'] = $home_page_id;
            $response['post_title'] = $page_info[0]->post_title;
        } else {
            $link_array = explode('/', $current_page_url);
            if (end($link_array) != '') {
                $slug = end($link_array);
            } else {
                $slug = $link_array[count($link_array) - 2];
            }

            $query = "SELECT `ID`,`post_title` FROM `{$wpdb->prefix}posts` WHERE `post_name` = '" . $slug . "'";
            $page_info = $wpdb->get_results($query, OBJECT);
            if ($page_info) {
                $response['id'] = $page_info[0]->ID;
                $response['post_title'] = $page_info[0]->post_title;
            } else {
                $response['id'] = 0;
                $response['post_title'] = 0;
            }
        }
        echo json_encode($response);

        exit;
    }
}

/*
 * This function is used to set the normal page element tasks as general task in case if they are orphaned. This function is used from Frontend Pages.
 *
 * @input Array ( $_POST )
 * @return Int
 */
add_action('wp_ajax_wpfb_set_general_comment', 'wpfb_set_general_comment');
add_action('wp_ajax_nopriv_wpfb_set_general_comment', 'wpfb_set_general_comment');
function wpfb_set_general_comment(){
    wpf_security_check();

        $task_ids[0] = $_POST['wpfb_task_id']; 
        $args = array(
            'task_id' => $task_ids,
            'wpf_site_id' => get_option('wpf_site_id')
        );

        $url = WPF_CRM_API.'wp-api/task/update-task-type';
        $sendtocloud = json_encode($args);
        $response = wpf_send_remote_post($url,$sendtocloud);

        echo $wpfb_task_id = $_POST['wpfb_task_id'];
        exit;
}

/*
 * This function is used to set the general tasks (created by orphaned service) as normal page. This function is used from Frontend Pages.
 *
 * @input Array ( $_POST )
 * @return Int
 */
add_action('wp_ajax_wpf_set_task_element', 'wpf_set_task_element');
add_action('wp_ajax_nopriv_wpf_set_task_element', 'wpf_set_task_element');
function wpf_set_task_element(){
    wpf_security_check();
    $wpf_task_ids = $_POST['wpf_task_ids'];

    $task_ids = $_POST['wpf_task_ids']; 
    $args = array(
        'task_id' => $task_ids,
        'wpf_site_id' => get_option('wpf_site_id')
    );

    $url = WPF_CRM_API.'wp-api/task/update-task-type';
    $sendtocloud = json_encode($args);
    $response = wpf_send_remote_post($url,$sendtocloud);

    if (empty($wpf_task_ids)) {
        echo 0;
        exit;
    }
    echo 1;
    exit;
}

/*
 * This function is used to set change the title of the task. This function from Backend Tasks Center.
 *
 * @input Array ( $_POST )
 * @return JSON
 */
add_action('wp_ajax_wpf_update_title', 'wpf_update_title');
add_action('wp_ajax_nopriv_wpf_update_title', 'wpf_update_title');
if (!function_exists('wpf_update_title')) {
    function wpf_update_title()
    {
        $response = array();
        wpf_security_check();
        $wpf_new_task_title = trim($_POST['wpf_new_task_title']);
        $wpf_task_id = $_POST['wpf_task_id'];
        if (!empty($wpf_new_task_title) && $wpf_task_id !=''){
            $my_post = array(
               'task_id' =>  $wpf_task_id,
               'task_title'    => $wpf_new_task_title
            );


            $sendtocloud = json_encode($my_post);
            $url = WPF_CRM_API.'wp-api/task/title/update';
            $wpf_task_id = wpf_send_remote_post($url,$sendtocloud);

            if($wpf_task_id['status'] == 200){
                $response['wpf_msg'] = 1;
                $response['wpf_new_task_title'] = $wpf_new_task_title;
                $response['wpf_task_id'] = $wpf_task_id;
            }else{
                $response['wpf_msg'] = 0;
            }
        }
       
        echo json_encode($response);
        exit;
    }
}

/*
 * This function is used to set the tag to the task. This function is used from Frontend Pages, Backend Pages and Backend Tasks Center.
 *
 * @input Array ( $_POST )
 * @return JSON
 */
add_action('wp_ajax_wpfb_set_task_tag','wpfb_set_task_tag');
add_action('wp_ajax_nopriv_wpfb_set_task_tag','wpfb_set_task_tag');
if (!function_exists('wpfb_set_task_tag')) {
    function wpfb_set_task_tag(){
        wpf_security_check();
        $response = array();
        $task_list_tags_array = array();
        $response['wpf_tag_type'] = '';
        $response['wpf_task_tag_name'] = '';
        $response['wpf_task_tag_slug'] = '';
        $wpf_task_tag_info = $_POST['wpf_task_tag_info'];
        $wpf_tag_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]/', '-', $wpf_task_tag_info['wpf_task_tag_name'])));
        $wpf_task_tag_info['wpf_task_tag_slug'] = $wpf_tag_slug;
        
	if(isset($_POST['wpf_task_tag_info']['wpf_task_id'])) {

	    $post_data = [];
	    $post_data['task_id'] = $_POST['wpf_task_tag_info']['wpf_task_id'];
	    $post_data['from_wp'] = 1;
        $post_data['user_id'] = (is_user_logged_in()) ? get_current_user_id() : 0;
	    $post_data['method'] = 'wpf_tags';
	    $post_data['action'] = 'add_tags';
	    $post_data['wpf_task_tag_name'] = $wpf_task_tag_info['wpf_task_tag_name'];
	    $post_data['wpf_task_tag_slug'] = $wpf_tag_slug;
	    $url = WPF_CRM_API.'wp-api/task/update-task-details';
	    $sendtocloud=json_encode($post_data);
	    $wpf_task_tags_obj = wpf_send_remote_post($url,$sendtocloud);

	    if(isset($wpf_task_tags_obj['code']) && $wpf_task_tags_obj['code'] == 401){
		    $response['wpf_task_tag_name'] = $wpf_task_tag_info['wpf_task_tag_name'];
		    $response['wpf_msg'] = 0;
		    $response['wpf_tag_type'] = 'already_exit';
	    }else{
		    $response['wpf_task_tag_slug'] = $wpf_tag_slug;
		    $response['wpf_task_tag_name'] = $wpf_task_tag_info['wpf_task_tag_name'];
		    $response['wpf_msg'] = 1;
		    $response['wpf_term_id'] = $post_data['task_id'];
		    $response['wpf_tag_type'] = 'new';
	    }
	}
        
        echo json_encode($response); 
        exit;
    }
}

/*
 * This function is used to delete the tag from the task. This function is used from Frontend Pages, Backend Pages and Backend Tasks Center.
 *
 * @input Array ( $_POST )
 * @return JSON
 */
add_action('wp_ajax_wpfb_delete_task_tag','wpfb_delete_task_tag');
add_action('wp_ajax_nopriv_wpfb_delete_task_tag','wpfb_delete_task_tag');
if (!function_exists('wpfb_delete_task_tag')) {
    function wpfb_delete_task_tag(){
        wpf_security_check();
        $response = array();
        $task_list_tags_array = array();
        $wpf_task_tag_info = $_POST['wpf_task_tag_info'];

        $wpf_task_tag_slug = $wpf_task_tag_info['wpf_task_tag_slug'];
        $wpf_task_id = $wpf_task_tag_info['wpf_task_id']; 
        $wpf_task_tag_name = $wpf_task_tag_info['wpf_task_tag_name'];

        $post_data = [];
        $post_data['task_id'] = $wpf_task_tag_info['wpf_task_id'];
        $post_data['from_wp'] = 1;
        $post_data['method'] = 'wpf_tags';
        $post_data['action'] = 'remove_tag';
        $post_data['wpf_task_tag_name'] = $wpf_task_tag_name;
        $post_data['wpf_task_tag_slug'] = $wpf_task_tag_slug;
        $url = WPF_CRM_API.'wp-api/task/update-task-details';
        $sendtocloud=json_encode($post_data);
        $wpf_task_tags_obj = wpf_send_remote_post($url,$sendtocloud);

        if($wpf_task_tags_obj['status'] == 1){
                $response['wpf_task_tag_slug'] = $wpf_task_tag_slug;
                $response['wpf_task_tag_name'] = $wpf_task_tag_name;
                $response['wpf_task_id'] = $wpf_task_id;
                $response['wpf_msg'] = 1; 
        }else{
                $response['wpf_msg'] = 0; 
        }
        
        echo json_encode($response); 
        exit;
    }
}

/*=====================Graphics feature Start=====================*/

/*
 * This function is used to get all the child post of graphics post. This function is used is not used now.
 *
 * @input Int
 * @return Array
 */
if (!function_exists('wpf_get_all_child_post')) {
    function wpf_get_all_child_post($post_parent_id){
        $all_wpfeedback = array();
        $all_post = '';
        $args = array(
                'post_parent' => $post_parent_id,
                'post_type'   => 'wpfeedback',
                'numberposts' => -1,
                'post_status' => 'any',
                'orderby'    => 'date',
                'order'       => 'DESC'
                );
                $wpfb_tasks = get_children( $args );
                if($wpfb_tasks){
                    foreach ($wpfb_tasks as $wpfb_task) {
                    $all_wpfeedback[] = $wpfb_task->ID;
                    }
                    $all_post = implode(',',$all_wpfeedback);
                }
                return $all_post;    
    }
}

/*
 * This function is used to create a graphics. This function is used from Backend Graphics Tab.
 *
 * @input Int
 * @return String (HTML)
 */
if (!function_exists('wpf_create_graphics')) {
    function wpf_create_graphics(){
        global $wpdb, $current_user;
        $graphics_name = stripslashes(html_entity_decode($_POST['wpf_graphics_name'], ENT_QUOTES, 'UTF-8'));
        // $new_graphics = array(
        //     'post_excerpt' => $_POST['wpf_graphics_excerpt'],
        //     'post_status' => 'publish',
        //     'post_title' => $graphics_name,
        //     'post_type' => 'wpf_graphics',
        //     'post_author' => $current_user->ID
        // );

        // $new_post = array(
        //         'post_title' => $graphics_name,
        //         'post_content' => $_POST['wpf_graphics_excerpt'],
        //         'post_status' => 'publish',
        //         'post_author' => $current_user,
        //         'post_type' => 'wpf_graphics',
        //         'post_category' => array(0)
        //     );
        // $post_id = wp_insert_post($new_post);

        $post_data = [
            'wpf_site_id' => get_option("wpf_site_id"),
            'title' => $graphics_name,
            'description' => $_POST['wpf_graphics_excerpt'],
            'image' => ""
        ];


        $url = WPF_CRM_API.'wp-api/graphic/createTask';
        $sendtocloud=json_encode($post_data);
        $graphics = wpf_send_remote_post($url,$sendtocloud);
        //re($graphics);

        if($graphics['status'] == 200 && isset($graphics['graphics_id'])){
            //update_post_meta($graphics_id,'_thumbnail_id',$_POST['wpf_feature_id']);

            

            $graphics_id = $graphics['graphics_id'];

            echo $output .= '<div class="wpf-col-3" id="'.$graphics_id.'"><a href="javascript:wpf_delete_conformation('.$graphics_id.')" class="wpf_graphics_delete_btn"><i class="gg-trash"></i></a><a target="_blank" href="'.get_permalink($graphics_id).'"><div class="wpf_graphics_thumb" style="background-image:url('.get_the_post_thumbnail_url($graphics_id,'large').')"><div class="wpf_graphics_title">'.$graphics_name.'</div></div></a></div>';
        }
        exit;
    }
    add_action('wp_ajax_wpf_create_graphics','wpf_create_graphics');
    add_action('wp_ajax_nopriv_wpf_create_graphics','wpf_create_graphics');
}

/*
 * This function is used to change background color of graohics. This function is used from Frontend Graphics Post.
 *
 * @input Array ( $_POST )
 * @return Int
 */
if (!function_exists('wpf_update_graphics_color')) {
    function wpf_update_graphics_color(){
        $wpf_graphics_color_name = $_POST['wpf_graphics_color_name'];
        if(!empty($wpf_graphics_color_name)){
                $post_data = [
                    'wpf_site_id' => get_option('wpf_site_id'),
                    'graphic_id' => $_POST['graphic_id'],
                    'color' => $wpf_graphics_color_name
                ];


                $url = WPF_CRM_API.'wp-api/graphic/color/update';
                $sendtocloud=json_encode($post_data);
                $graphics = wpf_send_remote_post($url,$sendtocloud);
                if($graphics['status'] == 200){
                    echo 1;        
                }
            
        }else{
            echo 0;
        }
        exit;
    }
    add_action('wp_ajax_wpf_update_graphics_color','wpf_update_graphics_color');
    add_action('wp_ajax_nopriv_wpf_update_graphics_color','wpf_update_graphics_color');
}

/*
 * This function is used to upload a new image to the graphics. This function is used from Frontend Graphics Post.
 *
 * @input Array ( $_POST )
 * @return String
 */
if (!function_exists('wpf_update_graphics_image')) {
    function wpf_update_graphics_image(){
        wpf_security_check();
        $wpf_new_graphics_img_id = $_POST['wpf_graphics_img_id'];
        $current_page_id = $_POST['current_page_id'];
        $get_privious_all_graphics = get_post_meta($current_page_id,'_wpf_graphics_img_id',true);
        $get_current_graphics = get_post_meta($current_page_id,'_thumbnail_id',true);

        if($wpf_new_graphics_img_id && $current_page_id){
            if($get_privious_all_graphics){
                $all_graphics_version = $get_privious_all_graphics.','.$get_current_graphics;
                update_post_meta($current_page_id,'_wpf_graphics_img_id',$all_graphics_version);
            }else{
                update_post_meta($current_page_id,'_wpf_graphics_img_id',$get_current_graphics);
            }
            update_post_meta($current_page_id,'_thumbnail_id',$wpf_new_graphics_img_id);
            echo wpf_grapgics_version_list($current_page_id);
        }else{
            echo wpf_grapgics_version_list($current_page_id);
        }
        exit;
    }
    add_action('wp_ajax_wpf_update_graphics_image','wpf_update_graphics_image');
    add_action('wp_ajax_nopriv_wpf_update_graphics_image','wpf_update_graphics_image');
}

/*
 * This function is used to get image of the graphics. This function is used from Frontend Graphics Post.
 *
 * @input Array ( $_POST )
 * @return String
 */
if (!function_exists('wpf_get_graphics_version')) {
    function wpf_get_graphics_version(){
        $wpf_graphics_id = $_POST['wpf_graphics_id'];
        $current_page_id = $_POST['current_page_id'];
        if($wpf_graphics_id){
           $wpf_graphics_url =  wp_get_attachment_url($wpf_graphics_id);
           if($wpf_graphics_url){
            echo $wpf_graphics_img = $wpf_graphics_url;
           }else{
             $get_current_graphics = get_post_meta($current_page_id,'_thumbnail_id',true);
             echo $get_current_graphics_url =  wp_get_attachment_url($get_current_graphics);
           }
        }else{
            $get_current_graphics = get_post_meta($current_page_id,'_thumbnail_id',true);
            echo $get_current_graphics_url =  wp_get_attachment_url($get_current_graphics);
        }
        exit;
    }
    add_action('wp_ajax_wpf_get_graphics_version','wpf_get_graphics_version');
    add_action('wp_ajax_nopriv_wpf_get_graphics_version','wpf_get_graphics_version');
}

/*
 * This function is used to update the co-ordinates of the task. This function is used from Frontend Graphics Post.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
if (!function_exists('wpf_graphics_update_task_coordinates')) {
    function wpf_graphics_update_task_coordinates(){
        wpf_security_check();
        $new_reconnect_obj = $_POST['task_info'];
        $post_data = [
            'wpf_site_id' => get_option('wpf_site_id'),
            // 'task_id' => $new_reconnect_obj['wpf_reconnect_taskid'],
            'task_id' => $new_reconnect_obj['task_id'],
            'method' => 'reconnect_task',
            'task_left' => $new_reconnect_obj['task_left'],
            'task_top' => $new_reconnect_obj['task_top'],
            'task_element_height' => $new_reconnect_obj['task_element_height'],
            'task_element_width' => $new_reconnect_obj['task_element_width'],
            'from_wp' => 1
        ];

        $url = WPF_CRM_API.'wp-api/task/update-task-details';
        $sendtocloud=json_encode($post_data);
        $graphics = wpf_send_remote_post($url,$sendtocloud);
        if($graphics['status'] == 1){
            echo 1;
            echo '<BR>: '.$sendtocloud;
        }else{
            echo 0;
        }
        exit();

        
        // $task_info = $_POST['task_info'];
        // if($task_info['task_id']){
        //     update_post_meta($task_info['task_id'],'task_element_width',$task_info['task_element_width']);
        //     update_post_meta($task_info['task_id'],'task_element_height',$task_info['task_element_height']);
        //     update_post_meta($task_info['task_id'],'task_top',$task_info['task_top']);
        //     update_post_meta($task_info['task_id'],'task_left',$task_info['task_left']);
        //     echo 1;
        // }else{
        //     echo 0;
        // }
        // exit;
    }
    add_action('wp_ajax_wpf_graphics_update_task_coordinates','wpf_graphics_update_task_coordinates');
    add_action('wp_ajax_nopriv_wpf_graphics_update_task_coordinates','wpf_graphics_update_task_coordinates');
}

/*
 * This function is used to update the co-ordinates of the task. This function is used from Frontend Graphics Post.
 *
 * @input Array ( $_POST )
 * @return Int
 */
if (!function_exists('wpf_completed_graphics')) {
    function wpf_completed_graphics(){
        $wpf_graphics_status = $_POST['wpf_graphics_status'];
        $wpf_graphics_id = $_POST['wpf_graphics_id'];

         $post_data = [
            'wpf_site_id' => get_option('wpf_site_id'),
            'graphic_id' => $wpf_graphics_id,
            'status' => $wpf_graphics_status
        ];


        $url = WPF_CRM_API.'wp-api/graphic/status/update';
        $sendtocloud=json_encode($post_data);
        $graphics = wpf_send_remote_post($url,$sendtocloud);


        if($graphics['status'] == 200){
            // if($wpf_graphics_status == 1){
            //     echo 1;
            // }
            // else if($wpf_graphics_status == 2){
            //     echo 2;
            // }else{
            //     echo 0;
            // }

            echo 1;
        }else{
            echo 0;
        }
        exit;
    }
    add_action('wp_ajax_wpf_completed_graphics','wpf_completed_graphics');
    add_action('wp_ajax_nopriv_wpf_completed_graphics','wpf_completed_graphics');
}

/*
 * This function is used to reconnect the general task to the element. This function is used from Frontend Pages and Backend Pages.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
if (!function_exists('wpf_reconnect_task')) {
    function wpf_reconnect_task(){
        wpf_security_check();
        $new_reconnect_obj = $_POST['new_reconnect_obj'];
        $post_data = [
            'wpf_site_id' => get_option('wpf_site_id'),
            'task_id' => $new_reconnect_obj['wpf_reconnect_taskid'],
            'method' => 'reconnect_task',
            'task_element_path' => $new_reconnect_obj['rightArrowParents'],
            'wpfb_task_bubble' => $new_reconnect_obj['dompath'],
            'task_element_height' => $new_reconnect_obj['html_element_height'],
            'task_element_width' => $new_reconnect_obj['html_element_width'],
            'type' => 'element',
            'from_wp' => 1
        ];

        $url = WPF_CRM_API.'wp-api/task/update-task-details';
        $sendtocloud=json_encode($post_data);
        $graphics = wpf_send_remote_post($url,$sendtocloud);
        if($graphics['status'] == 1){
            echo 1;
        }else{
            echo 0;
        }
        exit();

        // if($new_reconnect_obj['wpf_reconnect_taskid'] != ''){
        //     update_post_meta($new_reconnect_obj['wpf_reconnect_taskid'],'task_element_path',$new_reconnect_obj['rightArrowParents']);
        //     update_post_meta($new_reconnect_obj['wpf_reconnect_taskid'],'wpfb_task_bubble',$new_reconnect_obj['dompath']);

        //     update_post_meta($new_reconnect_obj['wpf_reconnect_taskid'],'task_element_height',$new_reconnect_obj['html_element_height']);
        //     update_post_meta($new_reconnect_obj['wpf_reconnect_taskid'],'task_element_width',$new_reconnect_obj['html_element_width']);
        //     update_post_meta($new_reconnect_obj['wpf_reconnect_taskid'],'task_type','element');
        //     echo 1;
        // }else{
        //     echo 0;
        // }
        // exit;
    }
    add_action('wp_ajax_wpf_reconnect_task','wpf_reconnect_task');
    add_action('wp_ajax_nopriv_wpf_reconnect_task','wpf_reconnect_task');
}

/*
 * This function is used delete the graphics post. This function is used from Backend Graphics Post.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
add_action('wp_ajax_wpfb_delete_grapgics','wpfb_delete_grapgics');
add_action('wp_ajax_nopriv_wpfb_delete_grapgics','wpfb_delete_grapgics');
if (!function_exists('wpfb_delete_grapgics')) {
    function wpfb_delete_grapgics()
    {
        wpf_security_check();
	
	$data = ['wpf_site_id' => get_option('wpf_site_id'), 'graphic_id' => $_POST['wpfb_grapgics_id']];
        $url = WPF_CRM_API.'wp-api/graphic/delete';
        $sendtocloud = json_encode($data);
        $res = wpf_send_remote_post($url,$sendtocloud);
	if($res['status'] == 200){
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }
}
/*=====================Graphics feature End=====================*/

/*
 * This function is used to do the bulk updates. This function is used from Backend Tasks Center.
 *
 * @input Array ( $_POST )
 * @return JSON
 */
add_action('wp_ajax_wpf_bulk_update_tasks','wpf_bulk_update_tasks');
add_action('wp_ajax_nopriv_wpf_bulk_update_tasks','wpf_bulk_update_tasks');
if (!function_exists('wpf_bulk_update_tasks')) {
    function wpf_bulk_update_tasks()
    {
        wpf_security_check();
        $wpf_task_ids = [];

        if(isset($_POST['wpf_task_ids'])){
            foreach ($_POST['wpf_task_ids'] as  $value) {
                array_push($wpf_task_ids,$value);
            }
        }
        $wpf_task_priority_attr = $_POST['wpf_task_priority_attr'];
        $wpf_task_task_status_attr = $_POST['wpf_task_task_status_attr'];
        $response = array();
        $task_status_info = array();
        $task_priority_info = array();
        if($wpf_task_task_status_attr){
            $task_info['task_id'] = $wpf_task_ids;
            $task_info['task_status'] = $wpf_task_task_status_attr;
            $task_info['method'] = "status";
        }
        if($wpf_task_priority_attr){
            $task_info['task_id'] = $wpf_task_ids;
            $task_info['task_priority'] = $wpf_task_priority_attr;
            $task_info['method'] = "priority";
        }

        $url = WPF_CRM_API.'wp-api/task/status/bulk/edit';
        $sendtocloud = json_encode($task_info);
        $res = wpf_send_remote_post($url,$sendtocloud);
        if($res['status'] == 200){
            $response['wpf_msg'] = 1;    
        }
    
        echo json_encode($response);
        exit;
    }
}

/*
 * This function is used for make the array
 * of task list to display in sidebar
 */
function process_task_response($wpfb_tasks) {
    $current_user_id = get_current_user_id();
    $comment_attachments_count = 0;
    $comment_attachments_index = 0;
    $comment_attachment_name = 'Guest';
    $response=array();
    $user_atarim_type=get_user_meta($current_user_id, 'wpf_user_type',true);
    if (!empty($wpfb_tasks['data'])) {
	foreach ($wpfb_tasks['data'] as $wpfb_task) {

        if($wpfb_task['task']['is_internal']=='1'){
            if($user_atarim_type!='advisor'){
                continue;
            }
        }
	    //old tasks
	    $task_date = $wpfb_task['task']['created_at'];
	    $response[$wpfb_task['task']['id']]['class'] = formatTimeString((int)strtotime($task_date));
	    $task_priority = $wpfb_task['task']['task_priority'];
	    $task_status = $wpfb_task['task']['task_status'];
	    $task_tags = $wpfb_task['task']['tags'];
	    $post_title = $wpfb_task['task']['task_title'];
	    $temp_tag_counter = 0;
	    if (is_array($task_tags) && !empty($task_tags)) {
		foreach ($task_tags as $task_tag => $task_tags_value) {
		    $response[$wpfb_task['task']['id']]['wpf_tags'][$temp_tag_counter]['slug'] = $task_tags_value['slug'];
		    $response[$wpfb_task['task']['id']]['wpf_tags'][$temp_tag_counter]['name'] = $task_tags_value['tag'];
		    $response[$wpfb_task['task']['id']]['wpf_tags'][$temp_tag_counter]['id'] = $task_tags_value['id'];
		    $temp_tag_counter++;
		}
	    }

	    // foreach ($metas as $key => $value) {
	    $task_id = $wpfb_task['task']['id'];


	    $response[$wpfb_task['task']['id']]['current_user_id'] = $current_user_id;
	    $response[$wpfb_task['task']['id']]['task_type'] = $wpfb_task['task']['task_type'];
        $response[$wpfb_task['task']['id']]['site_task_id'] = $wpfb_task['task']['site_task_id'];
	    $response[$wpfb_task['task']['id']]['created_at'] = $wpfb_task['task']['created_at'];
	    $response[$wpfb_task['task']['id']]['updated_at'] = $wpfb_task['task']['updated_at'];
	    
	    $created_at = strtotime(date("Y-m-d", strtotime($wpfb_task['task']['created_at'])));
	    $task_time_type = get_task_time_type($created_at);
	    $response[$wpfb_task['task']['id']]['task_time_type'] = $task_time_type;

	    $response[$wpfb_task['task']['id']]['site_id'] = $wpfb_task['task']['site_id'];
	    $response[$wpfb_task['task']['id']]['graphic_id'] = $wpfb_task['task']['graphic_id'];
	    $response[$wpfb_task['task']['id']]['task_config_author_browser'] = $wpfb_task['task']['task_config_author_browser'];
	    $response[$wpfb_task['task']['id']]['task_priority'] = $task_priority;
	    $response[$wpfb_task['task']['id']]['task_status'] = $task_status;
	    $response[$wpfb_task['task']['id']]['task_notify_users'] = $wpfb_task['task']['task_notify_users'];
	    $task_date1 = date_create($task_date);
	    $wpf_wp_current_timestamp = date('Y-m-d H:i:s', current_time('timestamp', 0));
	    $task_date2 = date_create($wpf_wp_current_timestamp);
	    $curr_comment_time = wpfb_time_difference($task_date1, $task_date2);

	    $response[$wpfb_task['task']['id']]['task_time'] = $curr_comment_time['comment_time'];
	    $response[$wpfb_task['task']['id']]['task_config_author_browserVersion'] = $wpfb_task['task']['task_config_author_browserVersion'];
	    $response[$wpfb_task['task']['id']]['task_config_author_browserOS'] = $wpfb_task['task']['task_config_author_browserOS'];

        
        $response[$wpfb_task['task']['id']]['task_config_author_name'] = get_task_author( $wpfb_task['task'] );
        if ( is_null( $response[$wpfb_task['task']['id']]['task_config_author_name'] ) ) {
            // $response[$wpfb_task['task']['id']]['task_config_author_name'] = "Deleted User";
        }
    
        // $task_config_author_meta = get_user_meta( intval( $wpfb_task['task']['task_config_author_id'] ) );
        // if ( !empty( $task_config_author_meta ) ) {

        //     $response[$wpfb_task['task']['id']]['task_config_author_name'] = $task_config_author_meta['first_name'][0] 
        //     ? $task_config_author_meta['first_name'][0] . " " . $task_config_author_meta['last_name'][0]
        //     : $task_config_author_meta['nickname'][0];
        // } else {
        //     $response[$wpfb_task['task']['id']]['task_config_author_name'] = $wpfb_task['task']['task_config_author_name'];
        // }

	    // $response[$wpfb_task['task']['id']]['task_config_author_id'] = $wpfb_task['task']['task_config_author_id'];

	    $response[$wpfb_task['task']['id']]['task_config_author_resX'] = $wpfb_task['task']['task_config_author_resX'];
	    $response[$wpfb_task['task']['id']]['task_config_author_resY'] = $wpfb_task['task']['task_config_author_resY'];
	    $response[$task_id]['task_title'] = $post_title;
	    $response[$wpfb_task['task']['id']]['task_page_url'] = $wpfb_task['task']['task_page_url'];
	    $response[$wpfb_task['task']['id']]['task_page_title'] = $wpfb_task['task']['task_page_title'];
	    $response[$wpfb_task['task']['id']]['task_comment_message'] = $wpfb_task['task']['task_comment_message'];
	    $response[$wpfb_task['task']['id']]['task_element_path'] = $wpfb_task['task']['task_element_path'];
	    $response[$wpfb_task['task']['id']]['wpfb_task_bubble'] = $wpfb_task['task']['wpfb_task_bubble'];
	    $response[$wpfb_task['task']['id']]['task_element_html'] = $wpfb_task['task']['task_element_html'];
	    $response[$wpfb_task['task']['id']]['task_X'] = $wpfb_task['task']['task_X'];
	    $response[$wpfb_task['task']['id']]['task_Y'] = $wpfb_task['task']['task_Y'];

	    $response[$wpfb_task['task']['id']]['task_elementX'] = $wpfb_task['task']['task_elementX'];
	    $response[$wpfb_task['task']['id']]['task_elementY'] = $wpfb_task['task']['task_elementY'];
	    $response[$wpfb_task['task']['id']]['task_relativeX'] = $wpfb_task['task']['task_relativeX'];
	    $response[$wpfb_task['task']['id']]['task_relativeY'] = $wpfb_task['task']['task_relativeY'];

	    $response[$wpfb_task['task']['id']]['task_element_height'] = $wpfb_task['task']['task_element_height'];
	    $response[$wpfb_task['task']['id']]['task_element_width'] = $wpfb_task['task']['task_element_width'];
	    //$response[$wpfb_task['task']['id']]['task_comment_id'] = $wpfb_task['task']['task_comment_id'];
	    $response[$wpfb_task['task']['id']]['task_comment_id'] = $wpfb_task['task']['wpf_task_id'];
	    $response[$wpfb_task['task']['id']]['wpf_task_id'] = $wpfb_task['task']['wpf_task_id'];
	    $response[$wpfb_task['task']['id']]['task_type'] = $wpfb_task['task']['task_type'];
	    $response[$wpfb_task['task']['id']]['is_admin_task'] = $wpfb_task['task']['is_admin_task'];
	    $response[$wpfb_task['task']['id']]['is_internal'] = $wpfb_task['task']['is_internal'];
	    $response[$wpfb_task['task']['id']]['wpf_task_url'] = $wpfb_task['task']['wpf_task_url'];
	    $response[$wpfb_task['task']['id']]['estimated_time'] = $wpfb_task['task']['estimated_time'];

	    $response[$wpfb_task['task']['id']]['spent_time'] = $wpfb_task['task']['spent_time'];
	    $response[$wpfb_task['task']['id']]['notify_wp_feedback_users'] = $wpfb_task['task']['notify_wp_feedback_users'];
	    $response[$wpfb_task['task']['id']]['wpf_task_screenshot'] = $wpfb_task['task']['wpf_task_screenshot'];
	    $response[$wpfb_task['task']['id']]['task_top'] = $wpfb_task['task']['task_top'];
	    $response[$wpfb_task['task']['id']]['task_left'] = $wpfb_task['task']['task_left'];
	    $response[$wpfb_task['task']['id']]['page_type'] = $wpfb_task['task']['page_type'];
	    $response[$wpfb_task['task']['id']]['wpf_current_screen'] = $wpfb_task['task']['wpf_current_screen'];
	    //$response[$wpfb_task['task']['id']]['site'] = $wpfb_task['task']['site'];


	    $comments_info = $wpfb_task['task']['comments'];
	    if ($comments_info) {
		foreach ($comments_info as $cmnt_key=>$comment) {
		    $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['wpf_user_id'] = $comment['wpf_user_id'];
		    $comment_type = 0;
		    if (strpos($comment["comment_content"], 'wpfeedback-image.s3') !== false) {
			$response[$wpfb_task['task']['id']]['comments'][$comment['id']]['filetype'] = $comment['comment_type'];
			if ($comment['comment_type'] == 'image/png' || $comment['comment_type'] == 'image/gif' || $comment['comment_type'] == 'image/jpeg') {
			    $comment_type = 1;
			} else {
			    $comment_type = 2;
			}
			//$response[$wpfb_task['task']->ID]['comments'][$comment["comment_content"]]['message'] = $comment["comment_content"];
		    } else if (wp_http_validate_url($comment['comment_content']) && !strpos($comment['comment_content'], 'wpfeedback-image.s3')) {
			$idVideo = $comment['comment_content'];
			$link = explode("?v=", $idVideo);
			if ($link[0] == 'https://www.youtube.com/watch') {
			    $youtubeUrl = "http://www.youtube.com/oembed?url=$idVideo&format=json";
			    $docHead = get_headers($youtubeUrl);
			    if (substr($docHead[0], 9, 3) !== "404") {
				$comment_type = 3;
				$response[$wpfb_task['task']['id']]['comments'][$comment['id']]['message'] = $link[1];
			    } else {
				$response[$wpfb_task['task']['id']]['comments'][$comment['id']]['message'] = $comment['comment_content'];
			    }
			} else {
			    //$response[$wpfb_task['task']['id']]['comments'][$comment['id']]['message'] = $comment['comment_content'];
			}
		    } else {
			//$response[$wpfb_task['task']['id']]['comments'][$comment['id']]['message'] = $comment['comment_content'];
		    }

		    $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['message'] =  stripslashes($comment['comment_content']);
		    $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['comment_type'] = $comment_type;
		    $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['is_log'] = $comment['is_log'];

            if (isset($comment['author']) && !empty($comment['author'])) {
                $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $comment['author']['name'];
            } elseif (isset($comment['wpf_author']) && !empty($comment['wpf_author'])) {
                // $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $comment['wpf_author']['wpf_display_name'];
                $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $comment['wpf_author']['wpf_display_name'];
            } else {
                /* v2.1.0 */
                $userInfo = get_userdata($comment['wpf_user_id']);
                if ( !empty($userInfo) ) {
                    
                    $wpf_front_users        = do_shortcode('[wpf_user_list_front]');
                    $wpf_front_users_arr    = json_decode( $wpf_front_users, true );

                    $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = array_filter( 
                        $wpf_front_users_arr, function( $user ) use( $comment ) {
                            return intval( $user['id'] ) === intval( $comment['wpf_user_id'] );
                        } 
                    );

                    //wpf_author
                    if ( !is_null( $wpfb_task['task']['comments'][$cmnt_key]['wpf_author'] ) ) {
                        $wpf_author = $wpfb_task['task']['comments'][$cmnt_key]['wpf_author'];
                        $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $wpf_author['name'];
                    } else {
                        if ( empty($response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author']) ) {
                            $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = "Guest";
                        }
                    }

                    // $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = (!empty($userInfo->first_name)) 
                    //         ? $userInfo->first_name . ' ' . $userInfo->last_name : $userInfo->display_name;
                } else {

                    if ( (!empty($comment['response'])) && (!empty($wpfb_task['task']['inbox'])) ) {
                        $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['response'] = $comment['response'];
                        if ( $comment['response']['delivery_status'] === 'outgoing' ) {
                            $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $wpfb_task['task']['inbox']['from_name'];
                        } else if ( $comment['response']['delivery_status'] === 'incoming' ) {
        
                            $from_address = $comment['response']['from_address'];
                            if ( strpos($from_address, '<') !== false ) {
                                $parts1 = explode('<', $from_address);
                                $parts2 = explode('>', $parts1[1]);
                    
                                $from_address = $parts2[0];
                            }
                            $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $from_address;
                        }

                        // attachments
                        if ( !empty($comment['response']['attachments']) ) {

                            $comment_attachment_name = $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'];
                            $comment_attachments_count = intval($comment['response']['attachments']);
                        } else {
                            $comment_attachment_name = 'Guest';
                            $comment_attachments_count = 0;
                        }
                    } else {

                        // print_r($wpfb_task['task']['comments'][$cmnt_key]); die;

                        if ( ( is_null($wpfb_task['task']['comments'][$cmnt_key]['wpf_user_id']) )
                        && ( is_null($wpfb_task['task']['comments'][$cmnt_key]['user_id']) ) )  {

                            $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] =  "Guest"; 

                        } elseif ( ( !is_null($wpfb_task['task']['comments'][$cmnt_key]['wpf_user_id']) && intval($wpfb_task['task']['comments'][$cmnt_key]['wpf_user_id']) > 0 )
                        && ( is_null($wpfb_task['task']['comments'][$cmnt_key]['user_id']) 
                        || (intval(is_null($wpfb_task['task']['comments'][$cmnt_key]['user_id'])) === 0) ) ) {
                            
                            $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] =  "Deleted User"; 

                         } else {
                             
                            $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] =  "Guest";  
                        }

                        $wpfb_users_json = do_shortcode('[wpf_user_list_front]');
                        $wpfb_users = json_decode($wpfb_users_json, true);
                        $comment_type = $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['comment_type'];
                        // $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $comment_type;

                        if ( $comment_type === 1 ) {
                            if ( $comment_attachments_index < $comment_attachments_count ) {

                                $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $comment_attachment_name;
                                $comment_attachments_index++;
                            }
                            // $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $comment_attachment_name;
                        } else {
                            $comment_attachments_index = 0;
                        }

                        /* => v2.1.1 */
                        foreach ( $wpfb_users as $key=>$wfusers ) {
                            if ($key === $comment['wpf_user_id']) {
                                $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['author'] = $wfusers['displayname'];
                                break;
                            }
                        }
                    }
                }
            }
            

		    // $datetime1 = date_create($comment['comment_date']);
		    $datetime1 = date_create($comment['created_at']);

		    //              Old Logic to get current time. Was creating issues when displaying message
		    //              $datetime2 = new DateTime('now');
		    //              New Logic to get current time.
		    $wpf_wp_current_timestamp = date('Y-m-d H:i:s', current_time('timestamp', 0));
		    $datetime2 = date_create($wpf_wp_current_timestamp);

		    $curr_comment_time = wpfb_time_difference($datetime1, $datetime2);
		    $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['time'] = $curr_comment_time['comment_time'];
		    $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['time_full'] = $curr_comment_time['interval'];
		    $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['user_id'] = $comment['user_id'];

            $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['is_edited'] = $comment['is_edited'];

            $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['is_deleted'] = $comment['is_deleted'];
            $response[$wpfb_task['task']['id']]['comments'][$comment['id']]['updated_at'] = date('H:i:s M:Y',strtotime($comment['updated_at']));

            $response[$wpfb_task['task']['id']]['task_notify_wpf_users'] = $wpfb_task['task']['task_notify_wpf_users'];
		}
	    }

	    if (isset($_POST['task_id']) && $wpfb_tasks['task']['id'] == $_POST['task_id']) {
		return $response;
		exit();
	    }
	}
    }

    return $response;
}

add_action('wp_ajax_bottom_panel_session','bottom_panel_session');
add_action('wp_ajax_nopriv_bottom_panel_session','bottom_panel_session');
if (!function_exists('bottom_panel_session')) {
    function bottom_panel_session(){
        ob_clean();
        
	/*if (!session_id()) {
	    session_start();
	}*/

	//$wpf_site_id = get_option('wpf_site_id');
	//$_SESSION['site_'.$wpf_site_id]["bottom_panel"] = $_POST['is_expand'];
    update_option('bottom_panel',$_POST['is_expand'],'no');
	echo json_encode(['status' => 1]);
        exit;
    }
}

/*
 * This function is used to push the media to the wordpress media library. It is called from Agency dashboard.
 *
 * @input string
 * @return String
 */
add_action('wp_ajax_push_app_push_to_media','app_push_to_media');
add_action('wp_ajax_nopriv_app_push_to_media','app_push_to_media');
if (!function_exists('app_push_to_media')) {
    function app_push_to_media(){
        $valid = wpf_api_request_verification();
            if($valid==1){
			$input_json = file_get_contents('php://input');
        	$input = json_decode($input_json);
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $save_url=$input->url;
            $res=media_sideload_image( $save_url);

		if (!is_wp_error($res)) {
            $response=1;
        }else{
            $response=2;
        }
        }else{
            $response = 'invalid request';
        }
		$response_signature = wpf_generate_response_signature($response);
        header("response-signature: ".$response_signature);
		echo $response;
		exit;
    }
}
/*
 * This function is used to push the media to the wordpress media library. It is called from task popover.
 *
 * @input string
 * @return numeric
 */
add_action('wp_ajax_push_to_media','push_to_media');
add_action('wp_ajax_nopriv_push_to_media','push_to_media');
if (!function_exists('push_to_media')) {
    function push_to_media(){
        wpf_security_check();
        $save_url=$_POST['media_link'];
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $res=media_sideload_image( $save_url);
        if (!is_wp_error($res)) {
            echo 1;
        }
        else{
            echo 2;
        }
        exit;
    }
}


/*
 * This function is get the milestone of the site
 *
 * @input Array ( $_POST )
 * @return JSON
 */
add_action('wp_ajax_wpf_fetch_milestones','wpf_fetch_milestones');
add_action('wp_ajax_nopriv_wpf_fetch_milestones','wpf_fetch_milestones');
if (!function_exists('wpf_fetch_milestones')) {
    function wpf_fetch_milestones()
    {
        wpf_security_check();

        $post_data = [
            'wpf_site_id' => get_option('wpf_site_id'),            
            'from_wp' => 1
        ];

        $url = WPF_CRM_API.'wp-api/milestone/all/';
        $sendtocloud=json_encode($post_data);
        $milestones = wpf_send_remote_post($url,$sendtocloud);

        echo json_encode($milestones);
        exit();
    }
}

/*
 * This function is used to update the roles to be allowed to use plugin from the Permissions tab.
 *
 * @input Array ( $_POST )
 * @return Boolean
 */
add_action('wp_ajax_wpf_update_collaborator_user', 'wp_ajax_wpf_update_collaborator_user');
add_action('wp_ajax_nopriv_wpf_update_collaborator_user', 'wp_ajax_wpf_update_collaborator_user');
if (!function_exists('wp_ajax_wpf_update_collaborator_user')) {
    function wp_ajax_wpf_update_collaborator_user() {

        wpf_security_check();
        $options = array(); 
        $wpf_name = isset($_POST['wpf_name']) ? sanitize_text_field( $_POST['wpf_name'] ) : "";
        $wpf_email = isset($_POST['wpf_email']) ? sanitize_text_field( $_POST['wpf_email'] ) : "";
        $wpf_user = isset($_POST['wpf_user']) ? sanitize_text_field( $_POST['wpf_user'] ) : "";
        $wpf_allow_guest = isset($_POST['wpf_allow_guest']) ? sanitize_text_field( $_POST['wpf_allow_guest'] ) : "no";
        $collab = isset($_POST['collab']) ? sanitize_text_field( $_POST['collab'] ) : "";

        if($collab == 'new') {
            $user_id = wp_create_user($wpf_name, wp_generate_password(), $wpf_email);
            $user = get_user_by('id', $user_id);
            $user->remove_role('subscriber');
            $user->add_role('administrator');
            update_user_meta($user_id, 'wpf_user_type', 'king');
        } else {
            update_user_meta($wpf_user, 'wpf_user_type', 'king');
        }
        $options['wpf_site_id'] = get_option('wpf_site_id');
        $options['wpf_allow_guest'] = $wpf_allow_guest;
        $options['wpf_selcted_role'] = 'administrator';
        $parms = [];
        foreach ($options as $key => $value) {
            array_push($parms, ['name' => $key,'value' => $value]);
        }

        update_site_data($parms);
        update_option('wpf_allow_guest', $wpf_allow_guest);
        update_option('wpf_selcted_role', 'administrator');
        update_option('wpf_initial_setup_complete', 'yes');
        syncUsers();

        $wpfb_users_array = json_decode( 
            do_shortcode('[wpf_user_list_front]'),
            true
        );

        if(!empty($wpfb_users_array)) {
            foreach($wpfb_users_array as $dashboard_user_id => $dashboard_user) {
                if($dashboard_user['id'] == $user_id) {
                    update_option('wpf_pre_website_client', $dashboard_user_id);
                    break;
                }
            }
        }

        $url = WPF_CRM_API . 'update-global-settings';

        $wpf_license_key = get_option('wpf_license_key');
        $wpf_license_key = wpf_crypt_key($wpf_license_key,'d');

        $sendarr = array();
        $sendarr["wpf_site_id"] = get_option('wpf_site_id');
        $sendarr["wpf_license_key"] = $wpf_license_key;
        $sendtocloud = json_encode($sendarr);
        $response = wpf_send_remote_post($url, $sendtocloud);
        if(isset($response['status']) == 200) {
            get_notif_sitedata_filterdata();
            echo 1;
            exit;
        }else{
            get_notif_sitedata_filterdata();
            echo 3;
            exit;
        }
    }
}



/*
 * This function is to check if internal task is allowed by Pratap
 *
 */
add_action('wp_ajax_wpf_is_internal_allowed', 'wpf_is_internal_allowed');
add_action('wp_ajax_nopriv_wpf_is_internal_allowed', 'wpf_is_internal_allowed');
function wpf_is_internal_allowed() {
    if ( is_feature_enabled( 'internal_tasks' ) ) {
        echo 'true';
    } else {
        echo 'false';
    }
    exit();
}