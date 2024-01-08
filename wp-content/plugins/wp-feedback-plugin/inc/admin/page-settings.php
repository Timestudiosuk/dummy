<div class="wrap wpfeedback-settings">
    <style>
        div#wpf_launcher{
            display: none !important;
        }
    </style>
    <?php 
    global $current_user;
//    re($current_user);
    $wpf_user_name = $current_user->user_nicename;
    $wpf_user_email = $current_user->user_email;
    $wpfeedback_font_awesome_script = get_site_data_by_key('wpfeedback_font_awesome_script');
    $wpf_user_type = wpf_user_type();
    $wpf_license_key = get_option('wpf_license_key');
    $wpf_license_key=wpf_crypt_key($wpf_license_key,'d');

    if($wpf_user_type=='advisor'){
        $wpf_tab_permission_user = !empty(get_site_data_by_key('wpf_tab_permission_user_webmaster')) ? get_site_data_by_key('wpf_tab_permission_user_webmaster') : 'no';
        $wpf_tab_permission_priority = !empty(get_site_data_by_key('wpf_tab_permission_priority_webmaster')) ? get_site_data_by_key('wpf_tab_permission_priority_webmaster') : 'no';
        $wpf_tab_permission_status = !empty(get_site_data_by_key('wpf_tab_permission_status_webmaster')) ? get_site_data_by_key('wpf_tab_permission_status_webmaster') : 'no';
        $wpf_tab_permission_screenshot = !empty(get_site_data_by_key('wpf_tab_permission_screenshot_webmaster')) ? get_site_data_by_key('wpf_tab_permission_screenshot_webmaster') : 'no';
        $wpf_tab_permission_information = !empty(get_site_data_by_key('wpf_tab_permission_information_webmaster')) ? get_site_data_by_key('wpf_tab_permission_information_webmaster') : 'no';
        $wpf_tab_permission_delete_task = !empty(get_site_data_by_key('wpf_tab_permission_delete_task_webmaster')) ? get_site_data_by_key('wpf_tab_permission_delete_task_webmaster') : 'no';
        $wpf_tab_permission_display_stickers = get_site_data_by_key('wpf_tab_permission_display_stickers_webmaster') != 'no' ? get_site_data_by_key('wpf_tab_permission_display_stickers_webmaster') : 'no';
        $wpf_tab_permission_display_task_id = get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no' ? get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') : 'no';
        $wpf_tab_permission_keyboard_shortcut = get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_webmaster') != 'no' ? get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_webmaster') : 'no'; /* v2.1.0 */
    }
    elseif ($wpf_user_type=='king'){
        $wpf_tab_permission_user = !empty(get_site_data_by_key('wpf_tab_permission_user_client')) ? get_site_data_by_key('wpf_tab_permission_user_client') : 'no';
        $wpf_tab_permission_priority = !empty(get_site_data_by_key('wpf_tab_permission_priority_client')) ? get_site_data_by_key('wpf_tab_permission_priority_client') : 'no';
        $wpf_tab_permission_status = !empty(get_site_data_by_key('wpf_tab_permission_status_client')) ? get_site_data_by_key('wpf_tab_permission_status_client') : 'no';
        $wpf_tab_permission_screenshot = !empty(get_site_data_by_key('wpf_tab_permission_screenshot_client')) ? get_site_data_by_key('wpf_tab_permission_screenshot_client') : 'no';
        $wpf_tab_permission_information = !empty(get_site_data_by_key('wpf_tab_permission_information_client')) ? get_site_data_by_key('wpf_tab_permission_information_client') : 'no';
        $wpf_tab_permission_delete_task = !empty(get_site_data_by_key('wpf_tab_permission_delete_task_client')) ? get_site_data_by_key('wpf_tab_permission_delete_task_client') : 'no';
        $wpf_tab_permission_display_stickers = !empty(get_site_data_by_key('wpf_tab_permission_display_stickers_client')) ? get_site_data_by_key('wpf_tab_permission_display_stickers_client') : 'no';
        $wpf_tab_permission_display_task_id = !empty(get_site_data_by_key('wpf_tab_permission_display_task_id_client')) ? get_site_data_by_key('wpf_tab_permission_display_task_id_client') : 'no';
        $wpf_tab_permission_keyboard_shortcut = !empty(get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_client')) ? get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_client') : 'no'; /* v2.1.0 */
    }
    elseif ($wpf_user_type=='council'){
        $wpf_tab_permission_user = !empty(get_site_data_by_key('wpf_tab_permission_user_others')) ? get_site_data_by_key('wpf_tab_permission_user_others') : 'no';
        $wpf_tab_permission_priority = !empty(get_site_data_by_key('wpf_tab_permission_priority_others')) ? get_site_data_by_key('wpf_tab_permission_priority_others') : 'no';
        $wpf_tab_permission_status = !empty(get_site_data_by_key('wpf_tab_permission_status_others')) ? get_site_data_by_key('wpf_tab_permission_status_others') : 'no';
        $wpf_tab_permission_screenshot = !empty(get_site_data_by_key('wpf_tab_permission_screenshot_others')) ? get_site_data_by_key('wpf_tab_permission_screenshot_others') : 'no';
        $wpf_tab_permission_information = !empty(get_site_data_by_key('wpf_tab_permission_information_others')) ? get_site_data_by_key('wpf_tab_permission_information_others') : 'no';
        $wpf_tab_permission_delete_task = !empty(get_site_data_by_key('wpf_tab_permission_delete_task_others')) ? get_site_data_by_key('wpf_tab_permission_delete_task_others') : 'no';
        $wpf_tab_permission_display_stickers = !empty(get_site_data_by_key('wpf_tab_permission_display_stickers_others')) ? get_site_data_by_key('wpf_tab_permission_display_stickers_others') : 'no';
        $wpf_tab_permission_display_task_id = !empty(get_site_data_by_key('wpf_tab_permission_display_task_id_others')) ? get_site_data_by_key('wpf_tab_permission_display_task_id_others') : 'no';
        $wpf_tab_permission_keyboard_shortcut = !empty(get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_others')) ? get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_others') : 'no'; /* v2.1.0 */
    }
    else{
        $wpf_tab_permission_user = 'no';
        $wpf_tab_permission_priority = 'no';
        $wpf_tab_permission_status = 'no';
        $wpf_tab_permission_screenshot = 'yes';
        $wpf_tab_permission_information = 'yes';
        $wpf_tab_permission_delete_task = 'no';
        $wpf_tab_permission_display_stickers = 'no';
        $wpf_tab_permission_display_task_id =  'no';
        $wpf_tab_permission_keyboard_shortcut =  'no'; /* v2.1.0 */
    }


    if ($wpfeedback_font_awesome_script == 'yes') { ?>
        <link rel='stylesheet' id='wpf-font-awesome-all'  href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" media="all" crossorigin="anonymous"/>
    <?php } ?>
    <script>
        jQuery(document).ready(function () {
            jQuery_WPF('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <h1><?php //echo esc_html( get_admin_page_title() ); ?></h1>
    <div class="wpf_logo">
        <img src="<?php echo get_wpf_logo(); ?>" alt="Atarim">
    </div>

    <!-- ================= TOP TABS ================-->

    <div class="wpf_tabs_container" id="wpf_tabs_container">
        <button class="wpf_tab_item wpf_tasks active" onclick="openWPFTab('wpf_tasks')"
                ><?php _e('Tasks', 'wpfeedback'); ?>
        </button>
        <button class="wpf_tab_item wpf_graphics" onclick="location.href='admin.php?page=wpfeedback_page_graphics'"
                    ><?php _e('Graphics', 'wpfeedback'); ?>
        </button>
        <?php if($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') ) ){ ?>
            <button class="wpf_tab_item wpf_settings" onclick="openWPFTab('wpf_settings')"
                    ><?php _e('Settings', 'wpfeedback'); ?>
            </button>
        <?php }
        if($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') ) ){ ?>
            <button class="wpf_tab_item wpf_misc" onclick="location.href='admin.php?page=wpfeedback_page_permissions'"
                    ><?php _e('Permissions', 'wpfeedback'); ?>
            </button>
        <?php }
        if($wpf_user_type == 'advisor' ){ ?>
            <button class="wpf_tab_item wpf_addons" onclick="openWPFTab('wpf_addons')">
                <?php _e('Integrate', 'wpfeedback'); ?>
            </button>
        <?php } if($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') ) ){ ?>
            <a href="https://atarim.io/support-reachout" target="_blank" class="wpf_tab_item wpf_support">
                <button>
                    <?php _e('Support', 'wpfeedback'); ?>
                </button>
            </a>
            <a href="<?php echo WPF_MAIN_SITE_URL; ?>/upgrade" target="_blank" class="wpf_tab_item" >
                <button>
                    <?php _e('Upgrade', 'wpfeedback'); ?>
                </button>
            </a>
        <?php } ?>

    </div>
    
    <!-- ================= TASKS PAGE ================-->
     <!--edited by Pratap-->
    <?php require_once(WPF_PLUGIN_DIR . 'inc/admin/upgrade-subscription-popup.php'); ?>
    <?php
        $wpf_daily_report = get_site_data_by_key('wpf_daily_report');
        $wpf_weekly_report = get_site_data_by_key('wpf_weekly_report');
    ?>
    <div id="wpf_tasks" class="wpf_container <?php if ( !is_feature_enabled( 'task_center' ) ) { ?> blocked <?php } ?>">
        <?php
        $wpf_license = get_option('wpf_license');
        if($wpf_license !='valid'){
        ?>
		<div id="wpf_tasks_overlay"><div class="wpf_welcome_wrap"><div class="wpf_welcome_title">Welcome to Atarim </div><div class="wpf_welcome_note">It's good to have you here <?php _e($wpf_user_name, 'wpfeedback'); ?>! ❤</div>
			<div class="wpf_welcome_image"><img alt="" src="<?php echo WPF_PLUGIN_URL.'images/WPF-welcome_720.png'; ?>"/></div><div class="wpf_welcome_note">Please click on the <u onclick="location.href='admin.php?page=wpfeedback_page_permissions'">Permissions tab</u> and verify your license to start using the plugin</div></div></div>
        <?php } ?>
        <div class="wpf_section_title">
            <?php _e('Tasks Center', 'wpfeedback'); ?>

            <div class="wpf_report_buttons">
                <span class="wpf_search_box"><i class="gg-search"></i><input onchange="wp_feedback_filter()" type="text" name="wpf_search_title" class="wpf_search_title" value="" id="wpf_search_title" placeholder="<?php _e('Search by task title', 'wpfeedback'); ?>"></span>
                <span id="wpf_back_report_sent_span" class="wpf_hide text-success"><?php _e('Your report was sent', 'wpfeedback'); ?></span>
                <span id="wpf_restore_orphan_tasks_span" class="wpf_hide text-success"><?php _e('All Orphan tasks are restored', 'wpfeedback'); ?></span>
                <span id="wpf_no_orphan_tasks_span" class="wpf_hide text-success"><?php _e('There are no Orphan tasks', 'wpfeedback'); ?></span>
                <?php
                    if($wpf_daily_report=='yes') {
                        ?>
                        <a href="javascript:wpf_send_report('daily_report')"><i class="gg-mail"></i> <?php _e('Daily Report', 'wpfeedback'); ?></a>
                        <?php
                    }
                    if($wpf_weekly_report=='yes') {
                        ?>
                        <a href="javascript:wpf_send_report('weekly_report')"><i class="gg-mail"></i> <?php _e('Weekly Report', 'wpfeedback'); ?></a>
                        <?php
                    }
            	 ?>
                        <a href="javascript:wpf_restore_orphan()"><i class="gg-sync"></i> <?php _e('Restore Orphan Tasks', 'wpfeedback'); ?></a>
			</div>
        </div>
        <div class="wpf_flex_wrap">
            <div class="wpf_filter_col wpf_gen_col">
                <div class="wpf_filter_status wpf_icon_box">
                    <div class="wpf_title"><?php _e('Filter Tasks', 'wpfeedback'); ?></div>
                    <form method="post" action="#" id="wpf_filter_form">

                        <div class="wpf_task_status_title wpf_icon_title"><i class="gg-screen"></i> <?php _e('Task Types', 'wpfeedback'); ?>
                        </div>
                         <div>
                            <ul class="wp_feedback_filter_checkbox task_type">
                                <li><input onclick="wp_feedback_filter()" type="checkbox" name="task_types_meta" value="general" class="wp_feedback_task_type" id="wpf_task_type_general"><label for="wpf_task_type_general"><?php _e('General', 'wpfeedback'); ?></label></li>
                                
                                <li><input onclick="wp_feedback_filter()" type="checkbox" name="task_types" value="wpf_admin" class="wp_feedback_task_type" id="wpf_task_type_admin"><label for="wpf_task_type_admin"><?php _e('Admin', 'wpfeedback'); ?></label></li>

                                <li><input onclick="wp_feedback_filter()" type="checkbox" name="task_types" value="publish" class="wp_feedback_task_type" id="wpf_task_type_page"><label for="wpf_task_type_page"><?php _e('Page', 'wpfeedback'); ?></label></li>

                                <li><input onclick="wp_feedback_filter()" type="checkbox" name="task_types_meta" value="graphics" class="wp_feedback_task_type" id="wpf_task_type_graphics"><label for="wpf_task_type_graphics"><?php _e('Graphics', 'wpfeedback'); ?></label></li>

                                <li><input onclick="wp_feedback_filter()" type="checkbox" name="task_types_meta" value="email" class="wp_feedback_task_type" id="wpf_task_type_email"><label for="wpf_task_type_email"><?php _e('Email', 'wpfeedback'); ?></label></li>

                                <li><input onclick="wp_feedback_filter()" type="checkbox" name="task_types_meta" value="internal" class="wp_feedback_task_type" id="wpf_task_type_internal"><label for="wpf_task_type_internal"><?php _e('Internal', 'wpfeedback'); ?></label></li>
                            </ul>
                        </div>

                        <div class="wpf_task_status_title wpf_icon_title"><?php echo get_wpf_status_icon(); ?><?php _e('Task Status', 'wpfeedback'); ?>
                        </div>
                        <input type="hidden" name="page" value="wpfeedback_page_settings">
                        <div><?php echo wp_feedback_get_texonomy('task_status'); ?></div>
                        <div class="wpf_task_priority_title wpf_icon_title"><?php echo get_wpf_priority_icon(); ?></i></i>
                            <?php _e('Task Urgency', 'wpfeedback'); ?>
                        </div>
                        <div><?php echo wp_feedback_get_texonomy('task_priority'); ?></div>
                        <div class="wpf_user_title wpf_icon_title"><?php echo get_wpf_user_icon();?> <?php _e('By Users', 'wpfeedback'); ?></div>
                        <div><?php echo do_shortcode('[wpf_user_list]'); ?></div>
                        <!--<input type="button" name="wp_feedback_filter_btn" value="<?php /*_e('Filter', 'wpfeedback'); */?>" id="wp_feedback_filter_btn"
                               class="wpf_button" onclick="wp_feedback_cat_filter()">-->
                    </form>
                </div>
            </div>
            <div class="wpf_loader_admin hidden"></div>
            <div class="wpf_tasks_col wpf_gen_col">
				<div class="wpf_top_found"><div class="wpf_title" id="wpf_task_tab_title"><?php _e('Tasks Found', 'wpfeedback'); ?></div>
                    <a href="javascript:wpf_general_comment();" title="Click to give your feedback!" data-placement="left" class="wpf_general_comment_btn" id="wpf_add_general_task"><i class="gg-add"></i>  <?php _e('General', 'wpfeedback'); ?></a><div class="wpf_display_all_taskmeta_div"></div>
                </div>
                <div class="wpf_tasks_tabs_wrap">
                    <label><input type="checkbox" name="wpf_task_bulk_tab" class="wpf_task_bulk_tab" id="wpf_task_bulk_tab" onclick="wpf_tasks_tabs('bulk')" /><?php _e('Bulk Action', 'wpfeedback'); ?></label>
                </div>
                <div id="wpf_bulk_select_task_checkbox" style="display: none;"><label><input type="checkbox" name="wpf_select_all_task" id="wpf_select_all_task" class="wpf_select_all_task"><?php _e('Edit All', 'wpfeedback'); ?></label></div>
                <?php
                    $tasks=wpfeedback_get_post_list();

                    // $custom_class = '';
                    // $display_span = '';
                    // if($wpf_tab_permission_display_stickers == true){
                    //     $custom_class = '_custom';
                    //     $display_span = '';
                    // }
                ?>
                <div class="wpf_tasks-list"><?php echo $tasks[0]; ?></div>
            </div>
            <div class="wpf_chat_col wpf_gen_col" id="wpf_task_details">
                <div class="wpf_chat_top">
                    <div class="wpf_task_num_top"></div>
                    <div class="wpf_task_main_top">
                        <div class="wpf_task_title_top"></div><a href="javascript:void(0)" onclick="wpf_edit_title()" id="wpf_edit_title"><i class="gg-pen"></i></a>
                        <div id="wpf_edit_title_box" class="wpf_hide"><input type="text" name="wpf_edit_title" value="" id="wpf_title_val" > 
                        <button id="wpf_title_update_btn" onclick="wpf_update_title()" class="submit wpf_button submit"><i class="gg-check"></i></button>
                        </div>
                        <div class="wpf_task_details_top"></div>
                    </div>
					<a href="#" id="wpfb_attr_task_page_link" target="_blank" class="wpf_button"><i class="gg-external"></i>
                        <input type="button" name="wp_feedback_task_page" class="wpf_button_inner" value="<?php _e('Open Task Page', 'wpfeedback'); ?>"></a>
                </div>
				
                <?php if($tasks=='<div class="wpf_no_tasks_found"><i class="gg-info"></i> No tasks found</div>'){ ?>
                <div class="wpf_chat_box" id="wpf_message_content">
                    <p class="wpf_no_task_message"><b><?php _e('No Tasks found.', 'wpfeedback'); ?></b><br/><?php _e('Please have a look at the video to understand the process.', 'wpfeedback'); ?></p>
                    <?php
                        if(get_site_data_by_key('wpf_tutorial_video')=='') {
                            ?>
                            <script src="https://fast.wistia.com/embed/medias/cided37ieu.jsonp" async></script>
                            <script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
                            <div class="wistia_responsive_padding" style="padding:48.75% 0 0 0;position:relative;">
                                <div class="wistia_responsive_wrapper"
                                     style="height:100%;left:0;position:absolute;top:0;width:100%;">
                                    <div class="wistia_embed wistia_async_cided37ieu videoFoam=true"
                                         style="height:100%;position:relative;width:100%">&nbsp;
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        else{
			    echo html_entity_decode(get_site_data_by_key('wpf_tutorial_video'));
                        }
                     ?>
                </div>
                <?php } else{ ?>
                <div class="wpf_chat_box" id="wpf_message_content">
                    <ul id="wpf_message_list"></ul>
                </div>
                <?php } ?>
                <div class="wpf_chat_reply" id="wpf_message_form"></div>
            </div>
            <div class="wpf_attributes_col wpf_gen_col" id="wpf_attributes_content">
                <div class="wpf_task_attr wpf_task_title">
					<div class="wpf_title"><?php _e('Task Attributes', 'wpfeedback'); ?></div>
                    <div class="wpf_icon_title at_fill_color at_att_screenshot"><?php echo get_wpf_screenshot_icon();?> <?php _e('Auto Screenshot', 'wpfeedback'); ?></div>
					<a href="#" id="wpf_task_screenshot_link" target="_blank"> <img src="" id="wpf_task_screenshot"></a>
				</div>
                <div class="wpf_task_attr wpf_task_page">
                    <?php if($wpf_tab_permission_information == 'yes'){ ?>
                    <div class="wpf_icon_title at_fill_color at_att_info"><?php echo get_wpf_info_icon();?> <?php _e('Additional Information', 'wpfeedback'); ?></div>
                    <div id="additional_information">
                    </div>
                    <?php } ?>
                </div>
                <div class="wpf_task_attr">

                    <?php if($wpf_user_type=='advisor'){ ?>
                        <div class="wpf_task_tags">
                            <div class="wpf_icon_title at_fill_color at_att_tags"><i class="gg-tag"></i> <?php _e('Custom Tags', 'wpfeedback'); ?></div>
                            <div class="wpf_tag_autocomplete"><input type="text" name="wpfeedback_tags" class="wpf_tag" value="" id="wpf_tags" onkeydown="wpf_search_tags(this)" ><button class="wpf_tag_submit_btn" onclick="wpf_add_tag_admin(this)"><i class="gg-corner-down-left"></i></button></div>
    						<div id="all_tag_list"></div>
                        </div>
                    <?php } ?>
                    <?php if($wpf_tab_permission_status == 'yes'){ ?>
                        <div class="wpf_task_status at_fill_color at_att_status">
                            <div class="wpf_icon_title"><?php echo get_wpf_status_icon(); ?> <?php _e('Task Status', 'wpfeedback'); ?></div>
                            <?php echo wp_feedback_get_texonomy_selectbox('task_status'); ?>
                        </div>
                    <?php }
                    if($wpf_tab_permission_priority=='yes'){ ?>
                        <div class="wpf_task_urgency at_fill_color at_att_priority">
                            <div class="wpf_icon_title"><?php echo get_wpf_priority_icon(); ?> <?php _e('Task Urgency', 'wpfeedback'); ?></div>
                            <?php echo wp_feedback_get_texonomy_selectbox('task_priority'); ?>
                        </div>
                    <?php } ?>
                    <?php if($wpf_tab_permission_delete_task == 'yes'){ ?>
                    <div class="wpf_task_attr wpf_task_title" id="wpf_delete_task_container">
                    </div>
                    <?php } else{ ?>
                            <div class="wpf_task_attr wpf_task_title" id="wpf_delete_task_container"></div>
                    <?php } ?>
                </div>
                <div class="wpf_task_attr wpf_task_users at_att_users">
                    <?php if($wpf_tab_permission_user == 'yes'){ ?>
                    <div class="wpf_icon_title"><?php echo get_wpf_user_icon();?> <?php _e('Notify Users', 'wpfeedback'); ?></div>
                    <div class="wpf_att_users">
                        <?php echo do_shortcode('[wpf_user_list_task]'); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="wpf_bulk_update_col wpf_gen_col" id="wpf_bulk_update_content" style="display: none;">
                <div class="wpf_task_options">
                    <div class="wpf_task_status">
                        <div class="wpf_icon_title"><?php echo get_wpf_status_icon(); ?> <?php _e('Task Status', 'wpfeedback'); ?></div>
                        <?php echo wpf_bulk_update_get_texonomy_selectbox('task_status'); ?>
                    </div>
                    <div class="wpf_task_urgency">
                        <div class="wpf_icon_title"><?php echo get_wpf_priority_icon();?> <?php _e('Task Urgency', 'wpfeedback'); ?></div>
                        <?php echo wpf_bulk_update_get_texonomy_selectbox('task_priority'); ?>
                    </div>
                    <div class="wpf_task_attr wpf_task_title" id="wpf_bulk_delete_task_container">
                        <a href="javascript:void(0)" class="wpf_bulk_task_delete_btn">
                            <i class="gg-trash"></i> <?php _e('Delete ticket','wpfeedback'); ?>
                        </a>
                        <p class="wpf_hide" id="wpf_bulk_task_delete">Are you sure you want to delete? <a href="javascript:void(0);" class="wpf_bulk_task_delete">Yes</a></p>
                    </div>
                    <input type="button" value="<?php _e('Save Bulk Changes','wpfeedback'); ?>" class="wpf_button" onclick="wpf_bulk_update()">
                </div>
            </div>
        </div>
    </div>

    <!-- ================= SETTINGS PAGE ================-->
    <?php if($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') ) ){ ?>
        <div id="wpf_settings" class="wpf_container" style="display:none">
            <div class="wpf_loader_admin hidden"></div>
            <div id="wpf_global_settings_overlay" <?php if(get_site_data_by_key('wpf_global_settings') !='yes'){ echo "class='wpf_hide'"; }?>>
                <div class="wpf_welcome_wrap"><div class="wpf_welcome_title"><?php _e('Global Settings', 'wpfeedback'); ?></div>
                    <p>Update your settings from the Global Settings area within your Agency dashboard.</p>
                    <div class="wpf_golbalsettings_buttons">
                        <div class="wpf_settings_icon">Local <i class="gg-database"></i></div>
                        <label class="wpf_switch">
                            <input type="checkbox" name="wpf_global_settings" class="wpf_global_settings" <?php if(get_site_data_by_key('wpf_global_settings') == 'yes'){ echo "checked"; }?> >
                            <span class="wpf_switch_slider wpf_switch_round"></span>
                        </label>
						<div class="wpf_settings_icon"><i class="gg-cloud"></i> Dashboard</div>
                    </div><p><a href="<?php echo WPF_APP_SITE_URL; ?>/settings" target="_blank">Edit your global settings</a></p>
					<div class="wpf_welcome_image"><img alt="" src="<?php echo esc_url(WPF_PLUGIN_URL.'images/global-settings.png'); ?>"/></div>
                    <?php
                    /*$wpf_license = get_option('wpf_license');
                    $wpf_disable_for_app = get_site_data_by_key('wpf_disable_for_app');
                    if(WPF_EDD_SL_STORE_URL == WPF_MAIN_SITE_URL && $wpf_license == 'valid' && $wpf_disable_for_app !='yes'){ ?>
                        <div class="wpf_resync_dashboard">
                            <div class="wpf_title">
                                <input type="button" value="<?php _e('Resync dashboard', 'wpfeedback'); ?>"
                                       class="wpf_button" onclick="wpf_resync_dashboard()"/>
                                <?php if(isset($_GET['resync_dashboard']) && $_GET['resync_dashboard'] == 1){ ?>
                                    <span class="wpf_resync_dashboard_msg" style="color: green; font-size: 12px;"><?php _e('The website should now be resynced/added to the dashboard. Please contact support in case they are not.','wpfeedback') ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php }*/ ?>
                </div>
            </div>

            <div class="wpf_section_title"><?php _e('Main Settings', 'wpfeedback'); ?>

            </div>
            <p id="wpf_global_erro_msg" class="wpf_hide" style="color: red;"><?php _e("There seems to be some issue with enabling the global settings. Please contact support for help.","wpfeedabck"); ?></p>
            <form method="post" action="admin-post.php" id="wpf_form_site_setting" enctype="multipart/form-data" >
                <div class="wpf_settings_ctt_wrap">
					<div class="wpf_settings_sidebar"><div class="wpf_settings_inner_sidebar">

						<a href="#wpf_global"><?php _e('Global Settings', 'wpfeedback'); ?></a>
						<a href="#wpf_general_settings"><?php _e('General Settings', 'wpfeedback'); ?></a>
						<a href="#wpf_branding"><?php _e('White Label', 'wpfeedback'); ?></a>
						<a href="#wpf_notifications"><?php _e('Notification Settings', 'wpfeedback'); ?></a>
						<a href="#wpf_email"><?php _e('Email Notifications', 'wpfeedback'); ?></a>
						                        <?php
                            $wpf_license = get_option('wpf_license');
                            $wpf_disable_for_app = get_site_data_by_key('wpf_disable_for_app');
                            if(/*WPF_EDD_SL_STORE_URL == WPF_MAIN_SITE_URL &&*/ $wpf_license == 'valid' && $wpf_disable_for_app != 'yes'){ ?>
                                <div class="wpf_resync_dashboard">
                                    <div class="wpf_title">
                                        <input type="button" value="<?php _e('Resync the Agency Dashboard', 'wpfeedback'); ?>"
                                               class="wpf_button" onclick="wpf_resync_dashboard()"/>
                                               <?php if(isset($_GET['resync_dashboard']) && $_GET['resync_dashboard'] == 1){ ?>
                                               <span class="wpf_resync_dashboard_msg" style="color: green; font-size: 12px;"><?php _e('Websites should now be resync / added now to the dashboard. Please contact support in case if does not.','wpfeedback') ?></span>
                                           <?php } ?>
                                    </div>
                                </div>
                        <?php } ?>
					<?php _e('Remember to Save Changes at the bottom of this screen to apply any changes.', 'wpfeedback'); ?>
						</div></div>
                    <div class="wpf_settings_col"><div class="wpf_inner_settings_col">
						
<!--					<div class="atarim_notice"><b>--><?php //_e('Atarim Notice:', 'wpfeedback'); ?><!--</b> --><?php //_e('Version 2 is a significant update. Update at your own risk, we highly recommend updating on a staging version of this website first before doing so on a live website.','wpfeedback'); ?><!-- <br>-->
<!--                        --><?php //_e('You can find a stable version (ver 1.9) within','wpfeedback'); ?><!-- <a href="https://atarim.io/account/" target="_blank">--><?php //_e('your account','wpfeedback'); ?><!--</a>-->
<!--                    </div>-->
						
					<div class="wpf_title_section" id="wpf_global"><?php _e('Global Settings', 'wpfeedback'); ?></div>

						<div class="wpf_settings_option wpfeedback_enable_global">
							<div class="wpf_title"><?php _e('Enable Global Settings', 'wpfeedback'); ?></div>                            
                            <div class="wpf_description"><?php _e('Everything you see on this screen can be managed globally from within your Agency Dashboard. Enable this option to pull your General Settings, Branding options and Notification options from the Global Settings panel.', 'wpfeedback'); ?></div>
                            <label class="wpf_switch">
                                <!--edited by Pratap-->
                                <input type="checkbox" name="wpf_global_settings" class="wpf_global_settings <?php if ( !is_feature_enabled( 'client_interface_global_settings' ) ) { ?> blocked <?php } ?>" <?php if(get_site_data_by_key('wpf_global_settings')=='yes'){ echo "checked"; }?> >
                                <span class="wpf_switch_slider wpf_switch_round"></span>
                            </label>
						</div>
                    
                        <div class="wpf_title_section" id="wpf_general_settings"><?php _e('General Settings', 'wpfeedback'); ?></div>
						<p><?php _e('On this screen, you can manage different settings of the plugin. You can white label it to match your own branding, control which notifications are sent out to the users of this WordPress website and a few other options below this text.','wpfeedback'); ?></p>
                        <p><b><?php _e('You can also control the permissions of Atarim Client Interface:', 'wpfeedback'); ?></b><?php _e(' you can allow or disallow users to use certain functions, you can even turn on guest mode to allow any visitor to the website to use the tool without needing to login.' , 'wpfeedback'); ?><a href="admin.php?page=wpfeedback_page_permissions"><?php _e('To find these settings, go here.', 'wpfeedback'); ?></a><?php _e('You will also see your license settings on this page.', 'wpfeedback'); ?><br><br></p>

						<div class="wpf_settings_option enabled_wpfeedback">
							<div class="wpf_title"><?php _e('Enable Atarim\'s Client Interface Plugin on this website', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('This is used to enable and disable the collaboration functions on this website, to save you the trouble of having to deactivate it in your plugin settings.', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="enabled_wpfeedback" value="yes"
                                       id="enabled_wpfeedback" <?php if (get_site_data_by_key('enabled_wpfeedback') == 'yes') {
                                    echo 'checked';} ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>
                            <!-- COMPACT MODE => v2.1.0 -->
                            <div class="wpf_settings_option wpf_enabled_compact_mode">
                                <div class="wpf_title"><?php _e('Enable Atarim Compact Mode', 'wpfeedback'); ?></div>
                               
                                <div class="wpf_description"><?php _e('Compact mode removes the bottom bar from both the front-end & back-end, and moves the widget to the right side of your screen. You can still use comment mode and view the sidebar as normal, this mode is to give you the option for a more "compact 😉" experience.', 'wpfeedback'); ?></div>
                                    <label class="wpf_switch">
                                         <!--edited by Pratap-->
                                        <input type="checkbox" name="wpf_enabled_compact_mode" value="yes"
                                            id="wpf_enabled_compact_mode" <?php if ( !is_feature_enabled( 'bottom_bar_enabled' ) ) { ?> class="blocked" <?php } ?><?php if (get_site_data_by_key('wpf_enabled_compact_mode') == 'yes') {
                                            echo 'checked';} ?>/>
                                        <span class="wpf_switch_slider wpf_switch_round"></span>
                                    </label>
                            </div>
						<div class="wpf_settings_option wpf_enable_clear_cache">
							<div class="wpf_title"><?php _e('Clear object cache while commenting and creating tasks', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('If you have object caching enabled on this website, you can tick this on to clear the cache when a comment or task is created. This may affect the speed of the website.', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="wpf_enable_clear_cache" value="yes" id="wpf_enable_clear_cache" <?php if (get_site_data_by_key('wpf_enable_clear_cache') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>

                        <div class="wpf_settings_option wpf_show_front_stikers">
							<div class="wpf_title"><?php _e('Show task stickers by default', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('If this is switched off, you will not see stickers unless you open the sidebar while on the front-end', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="wpf_show_front_stikers" value="yes"
                                       id="wpf_show_front_stikers" <?php if (get_site_data_by_key('wpf_show_front_stikers') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>

                        <div class="wpf_settings_option wpf_allow_backend_commenting">
							<div class="wpf_title"><?php _e('Remove backend commenting', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('By default you can create tasks on the front end AND on the back end. By ticking this option on, users will not be able to create tasks on any of the WP admin screens.', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="wpf_allow_backend_commenting" value="yes"
                                       id="wpf_allow_backend_commenting" <?php if (get_site_data_by_key('wpf_allow_backend_commenting') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>
<!--
<div class="wpf_settings_option delete_data_wpfeedback">
							<div class="wpf_title"><?php _e('Remove the data when removing the plugin', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e(' If this is checked on (not recommended), all the tasks and comments will be deleted from the database once the plugin is deactivated and deleted.', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="delete_data_wpfeedback" value="yes" id="delete_data_wpfeedback" <?php if (get_site_data_by_key('delete_data_wpfeedback') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>-->
                         <!--edited by Pratap-->
                        <div class="wpf-whitelabel-parent <?php
                        if(get_option('wpf_allowed_whitelabel') != 'true') { ?> blocked <?php } ?>">
                            <div class="wpf_title_section" id="wpf_branding"><?php _e('White Label', 'wpfeedback'); ?></div>
                            <p><?php _e('Here you can rebrand Atarim Client Interface by changing the main color and the logo.','wpfeedback'); ?><br />
                            <?php _e('You can ', 'wpfeedback'); ?><strong><?php _e('manage Global Settings across all of your websites', 'wpfeedback'); ?></strong> <?php _e('where your license is activated by visiting the general settings screen on your', 'wpfeedback'); ?> <a href="<?php echo WPF_APP_SITE_URL; ?>/settings#whitelabel" target="_blank"><?php _e('Agency Dashboard', 'wpfeedback'); ?></a>.</p>
                            
                            <div class="wpf_settings_option wpfeedback_replace_logo">
                                <div class="wpf_title"><?php _e('Replace the Atarim logo', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('This will replace the logo in the top right of this page and the logo on the notification emails that are sent out.', 'wpfeedback'); ?></div>
                                <span class="img_desc"><?php _e('The image should be 180x45 px. Allowed jpg, png.', 'wpfeedback'); ?></span>
                                <div class="wpf_upload_image_button graphics_fields custom_image_upload"> 
                                        <div class="wpf_field_input"><div class="wpf_field_label">Upload Image</div><i class="gg-image"></i>
                                            <input id="wpf_logo_file" type="file" name="wpf_logo_file" class="button">
                                        </div>
                                        <span class="wpf_preview_graphics_img wpf_hide"></span>
                                        <span class="wpf_error graphics_img">Please select image</span>
                                    </div>
                    
                                <div class='wpfeedback_image-preview-wrapper'>
                                    <img id='wpfeedback_image-preview' src='<?php echo get_wpf_logo(); ?>' height='100' />
                                </div>
                            </div>
                            
                            <div class="wpf_settings_option wpfeedback_replace_logo wpf_replace_icon">
                                <div class="wpf_title"><?php _e('Replace the Atarim icon', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('This will replace the Atarim icon in the admin side menu.', 'wpfeedback'); ?></div>
                                <span class="img_desc"><?php _e('The image should be 50px X 50px. Allowed jpg, png.', 'wpfeedback'); ?></span>
                                <div class="wpf_upload_image_button graphics_fields custom_image_upload"> 
                                        <div class="wpf_field_input"><div class="wpf_field_label">Upload Image</div><i class="gg-image"></i>
                                            <input id="wpf_icon_file" type="file" name="wpf_favicon_file" class="button">
                                        </div>
                                        <span class="wpf_preview_graphics_img wpf_hide"></span>
                                        <span class="wpf_error graphics_img">Please select image</span>
                                    </div>
                    
                                <div class='wpfeedback_image-preview-wrapper'>
                                    <img id='wpfeedback_icon-preview' src='<?php echo get_wpf_favicon(); ?>' width='80' />
                                </div>
                            </div>

                            <div class="wpf_settings_option wpfeedback_more_emails">
                                <div class="wpf_title"><?php _e('Change the logo link', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('This will replace the &quot;Powered by Atarim&quot; link to your own.', 'wpfeedback'); ?>
                                <?php _e('This is great for upselling your clients or making them aware of additional services that you can provide.', 'wpfeedback'); ?></div>
                                <input type="text" name="wpf_powered_link" value="<?php echo get_site_data_by_key('wpf_powered_link'); ?>" class="" />
                            </div>
                            <!-- <div class="wpf_settings_option wpfeedback_main_color">
                                <div class="wpf_title"><?php _e('Change the main color', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('Where ever you see the blue, this option will change it to whatever color you want!', 'wpfeedback'); ?></div>
                                <input type="text" name="wpfeedback_color" value="<?php echo get_site_data_by_key('wpfeedback_color') != '' ? str_replace('#','',get_site_data_by_key('wpfeedback_color')) : '002157'; ?>"
                                class="jscolor" id="wpfeedback_color"/>
                            </div> -->
                            
                            <div class="wpf_settings_option wpfeedback_main_color">
                                <div class="wpf_title"><?php _e('Change the main color', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('Where ever you see the blue, this option will change it to whatever color you want!', 'wpfeedback'); ?></div>
                                <input type="hidden" name="wpfeedback_color" value="<?php echo get_site_data_by_key('wpfeedback_color') != '' ? str_replace('#','',get_site_data_by_key('wpfeedback_color')) : '002157'; ?>"
                                class="jscolor" id="wpfeedback_color"/>
                                <div class="color-picker"></div>
                            </div>
                            <div class="wpf_settings_option wpfeedback_youtube_url">
                                <div class="wpf_title"><?php _e('Change the tutorial video', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('Replace the Default Atarim client tutorial video with your own tutorial video.', 'wpfeedback'); ?>
                                <?php _e('The video will be replaced on the frontend wizard as well as tasks screen on backend when empty.', 'wpfeedback'); ?>
                                </div>
                                <textarea name="wpf_tutorial_video" id="wpf_tutorial_video"><?php echo get_site_data_by_key('wpf_tutorial_video'); ?></textarea>
                            </div>
                            <div class="wpf_settings_option wpfeedback_powered_by">
                                <div class="wpf_title"><?php _e('Remove mention of "Atarim" from the plugin', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('Tick this setting to remove the name Atarim. Add your own logo and change the logo link above to ensure that the Client Interface Plugin is white labelled entirely.', 'wpfeedback'); ?></div>
                                <label class="wpf_switch">
                                    <input type="checkbox" name="wpfeedback_powered_by" value="yes"
                                        id="wpfeedback_powered_by" <?php if (get_site_data_by_key('wpfeedback_powered_by') == 'yes') {
                                        echo 'checked';
                                    } ?>/>
                                    <span class="wpf_switch_slider wpf_switch_round"></span>
                                </label>
                            </div>
                            <div class="wpf_settings_option wpfeedback_reset_setting">
                                <div class="wpf_title"><?php _e('Reset White Label Settings', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('Click this button to revent the whitelabel options to their original state.', 'wpfeedback'); ?></div>
                                <input type="button" value="<?php _e('Reset White Label Settings', 'wpfeedback'); ?>" class="wpf_button" onclick="wpfeedback_reset_setting()"/>
                            </div>
                        </div>

                        <input type="hidden" name="action" value="save_wpfeedback_options"/>
                        <?php wp_nonce_field('wpfeedback'); ?>
                        <div class="wpf_title_section" id="wpf_notifications"><?php _e('Notifications Settings', 'wpfeedback'); ?></div>
                        <!-- <p>This section handles the Atarim email notifications for this website. You can use any email address, but some email providers will block incoming emails from 
                            unverified email addresses, we call this method "Unsafe". The "Safe" method is to 
                            <a href="<?php echo WPF_APP_SITE_URL; ?>wpf_from_email_mode/settings#inbox" target="_blank">verify your preferred email address domain within your Agency Dashboard</a>.</p> -->
                       
                        <div class="wpf_settings_option wpfeedback_more_emails">
							<div class="wpf_title"><?php _e('Send email notifications to the following address', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('This option is in addition to the user emails. Seperate with comma for multiple addresses.', 'wpfeedback'); ?></div>
							<input type="text" name="wpfeedback_more_emails"
                                      value="<?php echo get_site_data_by_key('wpfeedback_more_emails'); ?>"/>
						</div>
                        <div class="wpfeedback_email_notifications">
                            <div class="wpf_title_section" id="wpf_email"><?php _e('Email Notifications', 'wpfeedback'); ?></div>
                                <p><?php _e('Ticking these on will display <b>them as an option on the front-end wizard for users to choose</b>. For example, if you don\'t want users to choose the option to send 24 hour reports, tick that off and it will not display on the front-end wizard.', 'wpfeedback'); ?></p>
                                <p><?php _e('If a user <b>does not</b> choose to receive any notifications and you\'d like to change that, go to their user profile in the WordPress Admin and they can be ticked on there, you can view more info on notifications <a href="'.WPF_LEARN_SITE_URL.'/knowledge-base/faq/task-notifications/" target="_blank">here</a>.', 'wpfeedback'); ?></p>
                            <div class="wpf_settings_option wpf_checkbox_settings">
							<div class="wpf_title"><?php _e('Send email notification for every new task', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Allow users to choose this setting on the front-end wizard and inside their WordPress Profile.', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="wpf_every_new_task" value="yes"
                                       id="wpf_every_new_task" <?php if (get_site_data_by_key('wpf_every_new_task') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>
                            <div class="wpf_settings_option wpf_checkbox_settings">
							<div class="wpf_title"><?php _e('Send email notification for every new comment', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Allow users to choose this setting on the front-end wizard and inside their WordPress Profile.', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="wpf_every_new_comment" value="yes"
                                       id="wpf_every_new_comment" <?php if (get_site_data_by_key('wpf_every_new_comment') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>
                            <div class="wpf_settings_option wpf_checkbox_settings">
							<div class="wpf_title"><?php _e('Send email notification when a task is marked as complete', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Allow users to choose this setting on the front-end wizard and inside their WordPress Profile.', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="wpf_every_new_complete" value="yes"
                                       id="wpf_every_new_complete" <?php if (get_site_data_by_key('wpf_every_new_complete') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>
                            <div class="wpf_settings_option wpf_checkbox_settings">
							<div class="wpf_title"><?php _e('Send email notification for every status change', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Allow users to choose this setting on the front-end wizard and inside their WordPress Profile.', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="wpf_every_status_change" value="yes"
                                       id="wpf_every_status_change" <?php if (get_site_data_by_key('wpf_every_status_change') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>
                            <div class="wpf_settings_option wpf_checkbox_settings">
							<div class="wpf_title"><?php _e('Send email notification for last 24 hours report', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Allow users to choose this setting on the front-end wizard and inside their WordPress Profile.', 'wpfeedback'); ?></div>
							<label class="wpf_switch">
								<input type="checkbox" name="wpf_daily_report" value="yes"
                                       id="wpf_daily_report" <?php if (get_site_data_by_key('wpf_daily_report') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>
                            <div class="wpf_settings_option wpf_checkbox_settings">
                                <div class="wpf_title"><?php _e('Send email notification for last 7 days report', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('Allow users to choose this setting on the front-end wizard and inside their WordPress Profile.', 'wpfeedback'); ?></div>
                                <label class="wpf_switch">
                                    <input type="checkbox" name="wpf_weekly_report" value="yes"
                                        id="wpf_weekly_report" <?php if (get_site_data_by_key('wpf_weekly_report') == 'yes') {
                                        echo 'checked';
                                    } ?>/>
                                    <span class="wpf_switch_slider wpf_switch_round"></span>
                                </label>
						    </div>
                            
                            <div class="wpf_settings_option wpf_checkbox_settings">
                                <div class="wpf_title"><?php _e('Auto send email notification for daily report', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('Allow users to choose this setting on the front-end wizard and inside their WordPress Profile.', 'wpfeedback'); ?></div>
                                <label class="wpf_switch">
                                        <!--edited by Pratap-->
                                    <input type="checkbox" name="wpf_auto_daily_report" value="yes" class="auto-report <?php if ( !is_feature_enabled( 'auto_reports' ) ) { ?> blocked <?php } ?>"
                                        id="wpf_auto_daily_report" <?php if (get_site_data_by_key('wpf_auto_daily_report') == 'yes') {
                                        echo 'checked';
                                    } ?>/>
                                    <span class="wpf_switch_slider wpf_switch_round"></span>
                                </label>
						    </div>
                            <div class="wpf_settings_option wpf_checkbox_settings">
                                <div class="wpf_title"><?php _e('Auto send email notification for weekly report', 'wpfeedback'); ?></div>
                                <div class="wpf_description"><?php _e('Allow users to choose this setting on the front-end wizard and inside their WordPress Profile.', 'wpfeedback'); ?></div>
                                <label class="wpf_switch">
                                        <!--edited by Pratap-->
                                    <input type="checkbox" name="wpf_auto_weekly_report" value="yes" class="auto-report <?php if ( !is_feature_enabled( 'auto_reports' ) ) { ?> blocked <?php } ?>"
                                        id="wpf_auto_weekly_report" <?php if (get_site_data_by_key('wpf_auto_weekly_report') == 'yes') {
                                        echo 'checked';
                                    } ?>/>
                                    <span class="wpf_switch_slider wpf_switch_round"></span>
                                </label>
						    </div>
                            <br>
                        </div>

                        

                        <?php
                        $wpfb_users_json = do_shortcode('[wpf_user_list_front]');
                        $wpfb_users = json_decode($wpfb_users_json);
                        $wpf_website_client = get_site_data_by_key('wpf_website_client');
                        $wpf_website_developer = get_site_data_by_key('wpf_website_developer');
                        ?>
                        <input type="submit" value="<?php _e('Save Changes', 'wpfeedback'); ?>" class="wpf_button"
                               id="wpf_save_setting" />
<!--			<p style="display:none;color: green;" id="setting_succ_msg"></p>
			<p style="display:none;color: red;" id="setting_err_msg"></p>-->
                    </div>
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>
    <!-- ================= ADD-ONS PAGE ================-->
    <?php if($wpf_user_type == 'advisor' || $wpf_user_type == 'king' || ($wpf_user_type == '' && current_user_can('administrator') ) ){ ?>
        <div id="wpf_addons" class="wpf_container" style="display:none">
            <div class="wpf_section_title"><?php _e('Zapier Integration', 'wpfeedback'); ?></div>
            <div class="wpf_inner_container">
                <a href="<?php echo WPF_LEARN_SITE_URL; ?>/integrate-via-zapier/"
                   target="_blank"><img alt="Atarim and Zapier" class="wpf_integration_image" src="<?php echo WPF_PLUGIN_URL.'images/integrations-image.png'; ?>"/></a>
            </div>
        </div>
    <?php }
echo "<script>var wpf_orphan_tasks=".json_encode($tasks[1]).";</script>";
?>