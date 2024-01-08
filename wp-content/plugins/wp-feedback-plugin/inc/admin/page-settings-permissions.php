<div class="wrap wpfeedback-settings">
    <style>
        div#wpf_launcher {
            display: none !important;
        }
    </style>
    <?php
    global $current_user;
    $wpf_user_name = $current_user->user_nicename;
    $wpf_user_email = $current_user->user_email;
    $wpfeedback_font_awesome_script = get_site_data_by_key('wpfeedback_font_awesome_script');
    $wpf_user_type = wpf_user_type();
    $wpf_license_key = get_option('wpf_license_key');
    $wpf_license_key=wpf_crypt_key($wpf_license_key,'d');

    if ($wpfeedback_font_awesome_script == 'yes') { ?>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
              integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
              crossorigin="anonymous">
    <?php } ?>
     <script>
        jQuery(document).ready(function () {
            jQuery_WPF('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <div class="wpf_logo">
        <img src="<?php echo get_wpf_logo(); ?>" alt="Atarim">
    </div>

    <!-- ================= TOP TABS ================-->

    <div class="wpf_tabs_container" id="wpf_tabs_container">
        <button class="wpf_tab_item wpf_tasks active" onclick="location.href='admin.php?page=wpfeedback_page_tasks'"
                style="background-color: #efefef;"><?php _e('Tasks', 'wpfeedback'); ?>
        </button>

        <button class="wpf_tab_item wpf_graphics" onclick="location.href='admin.php?page=wpfeedback_page_graphics'"
                style="background-color: #efefef;"><?php _e('Graphics', 'wpfeedback'); ?>
        </button>

        <?php if ($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') ) ) { ?>
            <button class="wpf_tab_item wpf_settings" onclick="location.href='admin.php?page=wpfeedback_page_settings'"
                    style="background-color: #efefef;"><?php _e('Settings', 'wpfeedback'); ?>
            </button>
        <?php }
        if ($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') ) ) { ?>
            <button class="wpf_tab_item wpf_misc" onclick="openWPFTab('wpf_misc')"
                    style="background-color: #efefef;"><?php _e('Permissions', 'wpfeedback'); ?>
            </button>
        <?php }
        if ($wpf_user_type == 'advisor') { ?>
            <button class="wpf_tab_item wpf_addons" onclick="location.href='admin.php?page=wpfeedback_page_integrate'"
                    style="background-color: #efefef;">
                <?php _e('Integrate', 'wpfeedback'); ?>
            </button>
        <?php }
        if ($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') ) ) { ?>
            <a href="https://atarim.io/support-reachout" target="_blank" class="wpf_tab_item wpf_support" style="background-color: #efefef;">
                <button>
                    <?php _e('Support', 'wpfeedback'); ?>
                </button>
            </a>
            <a href="<?php echo WPF_MAIN_SITE_URL; ?>/upgrade" target="_blank" class="wpf_tab_item"
               style="background-color: #efefef;">
                <button>
                    <?php _e('Upgrade', 'wpfeedback'); ?>
                </button>
            </a>
        <?php } ?>
    </div>

    <!-- ================= SETTINGS PAGE ================-->
    <?php if ($wpf_user_type == 'advisor' || ($wpf_user_type == '' && current_user_can('administrator') ) ){ ?>
        <div id="wpf_misc" class="wpf_container" style="display:none">
         <!--edited by Pratap-->
        <?php require_once(WPF_PLUGIN_DIR . 'inc/admin/upgrade-subscription-popup.php'); ?>
            <div class="wpf_section_title">
                <?php _e('Permissions', 'wpfeedback'); ?>
            </div>
            <form method="post" action="admin-post.php" id="wpf_form_site_permission" >
                <input type="hidden" name="action" value="save_wpfeedback_misc_options"/>
                <?php wp_nonce_field('wpfeedback'); ?>
                <div class="wpf_settings_ctt_wrap">
					<div class="wpf_settings_sidebar"><div class="wpf_settings_inner_sidebar">

						<a href="#wpf_license_ver"><?php _e('Agency Dashboard Integration', 'wpfeedback'); ?></a>
						<a href="#wpf_general_perm"><?php _e('Permissions Settings', 'wpfeedback'); ?></a>
						<a href="#wpf_user_custom"><?php _e('Customisations', 'wpfeedback'); ?></a>
						<a href="#wpf_user_perm"><?php _e('User Permissions', 'wpfeedback'); ?></a>
						<a href="#wpf_default_users"><?php _e('Default Users', 'wpfeedback'); ?></a>
					<?php _e('Remember to Save Changes at the bottom of this screen to apply any changes.', 'wpfeedback'); ?>
						</div></div>					
					
                    <div class="wpf_settings_col"><div class="wpf_inner_settings_col">
						<div class="wpf_title_section" id="wpf_license_ver"><?php _e('Agency Dashboard Integration', 'wpfeedback'); ?></div>
						<p><?php _e('Click the button to activate and add this website to your Agency Dashboard. If for some reason the connection is not established, please contact support.', 'wpfeedback'); ?></p>
                        <div class="wpfeedback_licence_key">
                            <div class="wpf_title"><?php _e('Add this website to your Agency Dashboard account', 'wpfeedback'); ?></div>
                            <span><?php _e('The Client Interface Plugin will not work unless you click the following button and activate this website.', 'wpfeedback'); ?></span>
                            <div class="wpfeedback_licence_key_field">
                                <input type="password" name="wpfeedback_licence_key" id="wpfeedback_licence_key"
                                       value="00000000000000000000000000000000" autocomplete="off" disabled /><?php if (get_option('wpf_license') == 'valid') {
                                    echo '<b><span class="dashicons dashicons-yes" style="font-size:31px; width:28px; height:28px; color: green;"></span><a href="javascript:void(0)" onclick="wpf_edit_license()" class="dashicons"><i class="gg-pen"></i></a></b>';
                                } else {
                                    echo '<b><span class="dashicons dashicons-no-alt" style="font-size:31px; width:28px; height:28px; color: red;"></span><a href="javascript:void(0)" onclick="wpf_edit_license()" class="dashicons"><i class="gg-pen"></i></a></b>';
                                }
                                $status=get_option('wpf_license');
                                $expires=get_option('wpf_license_expires');
                                if ($status !== false && $status == 'valid') {
                                    echo '<div class="wpf_license_deactivate_wrap"><p><span class="wpf_active_license" style="color:#0aaf3a;">' . __('License Active') . '</span></p>';
                                    echo '<input type="submit" class="wpf_deactivate_button" name="edd_license_deactivate" value="'.__("Deactivate License").'"/></div>';
                                } else {
                                    $home_url = 'http://atarim.io/activate?activation_callback='.Base64_encode(site_url()).'&activation_item_id='.Base64_encode(WPF_EDD_SL_ITEM_ID);
                                    echo '<a href="'.$home_url.'"><button type="button" class="wpf_activate_btn" name="wpf_activate" access="false" id="ber_page4_save"><span class="dashicons dashicons-update"></span> Activate This Website</button></a>';
                                }
                                ?>
                                
                            </div>
                            <span><?php _e("If you ran out of website slots, <a href='https://atarim.io/upgrade/' target='_blank'>Please click here to increase your resources</a>", 'wpfeedback'); ?></span>
                            <?php $wpf_check_license_site = get_option('wpf_license');
                            if($wpf_check_license_site == 'site_inactive'){
                            ?>
                                <p style="color: red;"> <?php _e("Your license has been manually revoked from Atarim account. If you feel that this is a mistake, please contact the license owner to manually add the license to the site.","wpfeedabck"); ?></p>
                            <?php } ?>
                        </div>
						<div class="wpf_title_section" id="wpf_general_perm"><?php _e('Permissions Settings', 'wpfeedback'); ?></div>
                        <div class="wpf_settings_option wpfeedback_user_role_list">
							<div class="wpf_title"><?php _e('User roles allowed to create tasks', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('If a user role is not selected, they will not be able to see Atarim Client Interface on the front-end, they will still be able to access the plugin settings though unless you restrict this someone else.', 'wpfeedback'); ?> </div>
							<select multiple="true" id="wpfeedback_user_role_list" name="wpfeedback_selcted_role[]"><?php echo wpfeedback_dropdown_roles(); ?></select>
							<div class="wpf_description"><?php _e("Hold down the CTRL key to choose multiple options.", 'wpfeedback'); ?></div>
						</div>
						<div class="wpf_settings_option wpfeedback_guest_allowed">
							<div class="wpf_title"><?php _e('Guest Mode', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Enabling this will make Atarim Client Interface visible to everyone who visits your site and will allow them to create tasks and comment on existing ones.', 'wpfeedback'); ?></div>
							<label class="wpf_switch" for="wpfeedback_guest_allowed">
								<input type="checkbox" name="wpfeedback_guest_allowed" value="yes"
                                       id="wpfeedback_guest_allowed" <?php if (get_site_data_by_key('wpf_allow_guest') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>

						<div class="wpf_settings_option wpfeedback_disable_for_admin">
							<div class="wpf_title"><?php _e('Stop comments for admins', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Stop comments for admins on front end.', 'wpfeedback'); ?></div>
							<label class="wpf_switch" for="wpfeedback_disable_for_admin">
								<input type="checkbox" name="wpfeedback_disable_for_admin" value="yes"
                                       id="wpfeedback_disable_for_admin" <?php if (get_site_data_by_key('wpf_disable_for_admin') == 'yes') {
                                    echo 'checked';
                                } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div>

						<!--div class="wpf_settings_option wpfeedback_disable_for_app">
							<div class="wpf_title"><?php //_e('Do not add this website to your Dashboard', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php //_e('This setting will remove this website from your dashboard, if you\'d like to add it again untick this setting and save changes', 'wpfeedback'); ?></div>
							<label class="wpf_switch" for="wpfeedback_disable_for_app">
								<input type="checkbox" name="wpfeedback_disable_for_app" value="yes"
                                       id="wpfeedback_disable_for_app" <?php //if (get_site_data_by_key('wpf_disable_for_app') == 'yes') {
                                    //echo 'checked';
                               // } ?>/>
								<span class="wpf_switch_slider wpf_switch_round"></span>
							</label>
						</div-->
                        <div class="wpfeedback_customisations">
                            <div class="wpf_title_section" id="wpf_user_custom"><?php _e('Customisations ', 'wpfeedback'); ?> </div>
							<p><?php _e('These names are for the setup wizard when you first installed the plugin. Every user who comes to the website after installation will have to go through it and you can name the 3 options here.', 'wpfeedback'); ?></p>
                            <div class="wpf_settings_option">
							<div class="wpf_title"><?php _e('Client (Website Owner) ', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Can do everything except: Choose and change status, access the settings, support and upgrade screens. Can only delete their own tickets.', 'wpfeedback'); ?> </div>
							<input type="text" class="wpf_customise_field" name="wpf_customisations_client" value="<?php echo get_site_data_by_key('wpf_customisations_client'); ?>">
						</div>
<div class="wpf_settings_option">
							<div class="wpf_title"><?php _e('Webmaster', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Super admin – Has full capabilities for all the plugin’s functions. ', 'wpfeedback'); ?> </div>
							<input type="text" class="wpf_customise_field" name="wpf_customisations_webmaster" value="<?php echo get_site_data_by_key('wpf_customisations_webmaster'); ?>">
						</div>
<div class="wpf_settings_option">
							<div class="wpf_title"><?php _e('Others ', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Can do everything except: Choose and change status and urgency, Access the settings, support, integration and upgrade screens. Can only delete their own tickets. ', 'wpfeedback'); ?> </div>
							<input type="text" class="wpf_customise_field" name="wpf_customisations_others" value="<?php echo get_site_data_by_key('wpf_customisations_others'); ?>">
						</div>
                        </div>
                            <div class="wpf_title_section" id="wpf_user_perm"><?php _e('User Permissions', 'wpfeedback'); ?></div>
                            <!--edited by Pratap-->
                            <div class="wpf_user_permissions <?php if ( !is_feature_enabled( 'user_permissions' ) ) { ?> blocked <?php } ?>">
                                <?php if(get_option("wpf_global_settings")=='yes') { ?>
                                    <div class="at_feature_lock_wrap wpf_global_lock">
                                        <div class="at_feat_global">
                                            <img alt="Global Settings" src="<?php echo esc_url(WPF_PLUGIN_URL.'images/global-settings.png'); ?>"/>
                                        </div>
                                        <div class="at_feat_content">
                                            <div class="wpf_title"><?php _e('Global Settings', 'wpfeedback'); ?></div>
                                            <p><?php _e('User Permissions are pulled from your Agency dashboard', 'wpfeedback'); ?></p>
                                            <p><a href="<?php echo WPF_APP_SITE_URL; ?>/settings" target="_blank"><?php _e('Edit your global settings', 'wpfeedback'); ?></a></p>
                                        </div>
                                    </div>
                                <?php } else {?>
                                    <p>
                                        <?php _e('Enable or disable diffent functionality based on the Atarim user types to overwirte the default settings and customise your workflow.', 'wpfeedback'); ?>
                                    </p>
                                    <table class="wpf_perm_table">
                                        <tr>
                                            <td class="wpf_perm_top"></td>
                                            <td class="wpf_perm_top"><?php echo get_site_data_by_key('wpf_customisations_client') ? get_site_data_by_key('wpf_customisations_client') : _e('Client (Website Owner) ','wpfeedback'); ?></td>
                                            <td class="wpf_perm_top"><?php echo get_site_data_by_key('wpf_customisations_webmaster') ? get_site_data_by_key('wpf_customisations_webmaster') : _e('Webmaster','wpfeedback'); ?></td>
                                            <td class="wpf_perm_top"><?php echo get_site_data_by_key('wpf_customisations_others') ? get_site_data_by_key('wpf_customisations_others') : _e('Others ','wpfeedback'); ?></td>
                                            <td class="wpf_perm_top"><?php _e('Guest ', 'wpfeedback'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Add User','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Show/Hide the Users tab inside tasks to give you more control over who can assign users to tasks, so you can ensure tasks are assigned correctly.">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_permission_user_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_user_client') == 'yes') { echo 'checked'; } ?> ></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_user_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_user_webmaster') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_user_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_user_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_user_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_user_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Priority','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Show/Hide the Priority tab inside tasks, we recommend keeping this on for all so you are always aware of how urgent a task is (and the emotional state of your client).">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_permission_priority_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_priority_client') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_priority_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_priority_webmaster') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_priority_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_priority_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_priority_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_priority_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Status','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Show/Hide the Status tab inside tasks, so you can control which users dictate where a task currently stands.">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_permission_status_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_status_client') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_status_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_status_webmaster') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_status_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_status_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_status_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_status_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Screenshot','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Show/Hide the Screenshot tab inside tasks, which allows users to take a screenshot of their current view so you can see exactly what’s happening for that user.">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_permission_screenshot_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_screenshot_client') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_screenshot_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_screenshot_webmaster') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_screenshot_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_screenshot_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_screenshot_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_screenshot_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Information','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Show/Hide the Information tab inside tasks, giving you the resolution, browser and username of the user that created the task. All the info you need to get the task done.">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_permission_information_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_information_client') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_information_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_information_webmaster') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_information_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_information_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_information_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_information_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Delete','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Show/Hide the Delete button inside the Information tab on tasks. Allowing you full control over who is accountable for which tasks have been created, to stop confusion occurring.">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_permission_delete_task_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_delete_task_client') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_delete_task_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_delete_task_webmaster') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_delete_task_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_delete_task_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_delete_task_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_delete_task_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Auto Screenshot','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Enable automatic screenshots of the user’s current view when they create a task. Providing you the clarity you need to tackle the task.">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_auto_screenshot_task_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_auto_screenshot_task_client') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_auto_screenshot_task_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_auto_screenshot_task_webmaster') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_auto_screenshot_task_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_auto_screenshot_task_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_auto_screenshot_task_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_auto_screenshot_task_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>

                                        <!-- display stickers settings   -->
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Color Coded Stickers','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Show/Hide color-coded stickers on the task stickers. The color is determined by the status and urgency of a task, showing you the state of the task at a glance without needing to open it.">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_permission_display_stickers_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_display_stickers_client') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_display_stickers_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_display_stickers_webmaster') != 'no') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_display_stickers_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_display_stickers_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_display_stickers_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_display_stickers_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>
                                        <!-- END -->

                                        <!-- display task id on sticker    -->
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Display Number On Completed Task','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Replace the tick on completed tasks on the front-end with the task number to make it easier to see the ID of the completed task.">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_permission_display_task_id_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_display_task_id_client') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_display_task_id_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_display_task_id_webmaster') != 'no') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_display_task_id_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_display_task_id_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_display_task_id_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_display_task_id_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>

                                        <!-- enable keyboard shortcut => v2.1.0  -->
                                        <tr>
                                            <td class="wpf_left_cell"><?php _e('Keyboard Shortcuts','wpfeedback'); ?>
                                                <span class="dashicons dashicons-info"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-html="true"
                                                    title="Enable the use of keyboard shortcuts to make it quicker to collaborate on the front and back-end:<ul><li><b>Shift+F</b> - Go into Comment Mode</li><li><b>Shift+G</b> - Create a new General Task</li><li><b>Shift+S</b> - Open the sidebar</li><li><b>Shift+B</b> - Collapse bottom bar</li></ul>">
                                                </span>
                                            </td>
                                            <td><input type="checkbox" name="wpf_tab_permission_keyboard_shortcut_client" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_client') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_keyboard_shortcut_webmaster" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_webmaster') != 'no') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_keyboard_shortcut_others" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_others') == 'yes') { echo 'checked'; } ?>></td>
                                            <td><input type="checkbox" name="wpf_tab_permission_keyboard_shortcut_guest" value="yes" <?php if (get_site_data_by_key('wpf_tab_permission_keyboard_shortcut_guest') == 'yes') { echo 'checked'; } ?>></td>
                                        </tr>
                                        <!-- END -->
                                    </table>
                                <?php } ?>
                            </div>
                        <?php
                            $wpfb_users_json = do_shortcode('[wpf_user_list_front]');
                            $wpfb_users = json_decode($wpfb_users_json);
                            $wpf_website_developer = !empty(get_site_data_by_key('wpf_website_developer')) ? get_site_data_by_key('wpf_website_developer') : 0;
                            $wpf_website_client = !empty(get_site_data_by_key('wpf_website_client')) ? get_site_data_by_key('wpf_website_client') : 0;
                        ?>
						<div class="wpf_title_section" id="wpf_default_users"><?php _e('Default Users', 'wpfeedback'); ?></div>
                        <div class="wpf_settings_option wpf_website_developer">
							<div class="wpf_title"><?php _e('The website builder', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('The website builder will add this user to all tasks by default, allowing the client to skip the "choose a user" tab when creating a task. It will also be used for the Auto Login option when coming from the Agency Dashboard.', 'wpfeedback'); ?> </div>
							<select name="wpf_website_developer">
                                <option value="0"><?php _e('select', 'wpfeedback'); ?></option>
                                <?php
                                foreach ($wpfb_users as $key => $val) {
                                    ?>
                                    <option value="<?php echo $key; ?>" id="<?php echo $val->username; ?>" <?php if ($wpf_website_developer == $key) {
                                        echo "selected";
                                    } ?> ><?php echo $val->displayname; ?></option>
                                <?php } ?>
                            </select>
							<div class="wpf_description"><?php _e("This will probably be your own user, as the person or agency that is building the website.", 'wpfeedback'); ?></div>
						</div>

                        <div class="wpf_settings_option wpf_website_client">
							<div class="wpf_title"><?php _e('The client', 'wpfeedback'); ?></div>
							<div class="wpf_description"><?php _e('Create a user for the client and assign it here to allow the client to comment in guest mode but still be assigned to the tickets for replies and notifications.', 'wpfeedback'); ?> </div>
							<select name="wpf_website_client">
                                <option value="0"><?php _e('select', 'wpfeedback'); ?></option>
                                <?php
                                foreach ($wpfb_users as $key => $val) {
                                    /* $wpfbuser = get_user_by('login',$val);
                                    $wpfbuserdisplay = $wpfbuser -> display_name; */
                                    ?>
                                    <option value="<?php echo $key; ?>" id="<?php echo $val->username; ?>" <?php if ($wpf_website_client == $key) {
                                        echo "selected";
                                    } ?>><?php echo $val->displayname; ?></option>
                                <?php } ?>
                            </select>
							<div class="wpf_description"><?php _e("This will be the user you created for your main contact for this website.", 'wpfeedback'); ?></div>
						</div>

                        <input type="submit" value="<?php _e('Save Changes', 'wpfeedback'); ?>" class="wpf_button" id="wpf_save_setting"/>
                    </div></div>
                </div>
            </form>
        </div>
    <?php } ?>
</div>