<?php
global $current_user;
if ($current_user->display_name == '') {
    $wpf_user_name = $current_user->user_nicename;
} else {
    $wpf_user_name = $current_user->display_name;
}
$wpf_admin_users = get_users(array( 'role' => 'Administrator' ));
?>
<div class="wpf_backend_initial_setup">
    <div class="wpf_logo_wizard">
        <img src="<?php echo esc_url(WPF_PLUGIN_URL . 'images/Atarim-Wizzard.svg'); ?>" alt="Atarim">
    </div>
    <div class="wpf_backend_initial_setup_inner">
        <div class="wpf_loader_admin wpf_hide"></div>
        <form method="post" action="admin-post.php">
            <?php

            $first_step_display='';
            $second_step_display='wpf_hide';
            if(isset($_GET['step_one']) && $_GET['step_one']=='true'){
                $first_step_display='wpf_hide';
                $second_step_display='';

            }
            ?>
            <div id="wpf_initial_settings_first_step" class="wpf_initial_container <?php echo $first_step_display; ?>">
				<div class="wpf_wizard_progress_box">
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e("Install","wpfeedback"); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_current">
						<div class="wpf_wizard_progress_num">2</div>
						<div class="wpf_wizard_progress_desc"><?php _e("Connect","wpfeedback"); ?></div>
					</div>
					<div class="wpf_wizard_progress_step">
						<div class="wpf_wizard_progress_num">3</div>
						<div class="wpf_wizard_progress_desc"><?php _e("Collaborate","wpfeedback"); ?></div>
					</div>
					<div class="wpf_wizard_progress_step">
						<div class="wpf_wizard_progress_num">4</div>
						<div class="wpf_wizard_progress_desc"><?php _e("Complete","wpfeedback"); ?></div>
					</div>
				</div>
				<div class="wpf_wizard_content_box">
					<div class="wpf_title_wizard"><?php _e("Let's Get You Up and Running","wpfeedback"); ?></div>
					<p><?php printf( __("Good to have you here %s!","wpfeedback"),$wpf_user_name); ?></p>
					<input type="hidden" name="action" value="save_wpfeedback_options"/>
					<?php wp_nonce_field('wpfeedback'); ?>       
				<!-- new activation-->
								<img class="wpf_add_website_img" src="https://app.atarim.io/assets/Adding-Websites.png" alt="Add Website to Agency Dashboard" />
								<div class="wpf_title"><?php _e('Add this website to your Agency Dashboard', 'wpfeedback'); ?></div>
								<p><?php _e('The Client Interface Plugin will not work unless you click the following button and activate this website.', 'wpfeedback'); ?></p>
								<div class="wpfeedback_licence_key_field">
								<?php
										$home_url = 'http://atarim.io/activate?activation_callback='.Base64_encode(home_url()).'&activation_item_id='.Base64_encode(WPF_EDD_SL_ITEM_ID).'&page_redirect='.Base64_encode("wpfeedback_page_settings");
										echo '<a href="'.$home_url.'"><button type="button" class="wpf_activate_btn" name="wpf_activate" access="false" id="ber_page4_save"><span class="dashicons dashicons-update"></span> Activate This Website</button></a>';
									?>

								</div>
								<p><?php _e("Ran out of website slots? <a href='https://atarim.io/upgrade/' target='_blank'>Please click here to increase your resources</a>", 'wpfeedback'); ?></p>
				<!--End new activation-->
				</div>
            </div>

            <div id="wpf_initial_settings_second_step" class="wpf_initial_container <?php echo $second_step_display; ?>">
				<div class="wpf_wizard_progress_box">
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e("Install","wpfeedback"); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e("Connect","wpfeedback"); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_current">
						<div class="wpf_wizard_progress_num">3</div>
						<div class="wpf_wizard_progress_desc"><?php _e("Collaborate","wpfeedback"); ?></div>
					</div>
					<div class="wpf_wizard_progress_step">
						<div class="wpf_wizard_progress_num">4</div>
						<div class="wpf_wizard_progress_desc"><?php _e("Complete","wpfeedback"); ?></div>
					</div>
				</div>
				<div class="wpf_wizard_content_box">				
					<div class="wpf_title_wizard"> <?php _e("Connect Your Main Collaborator (Client)", "wpfeedback"); ?></div>
					<p><?php _e("Create OR assign an <u>admin</u> user for main collaborator (or client).","wpfeedback"); ?>
					<br><?php _e("This will allow them to recieve notifications & comment with their name.","wpfeedback"); ?></p>
					<div class="wpf_toggle_user_container">
						<div class="wpf_collaborator_setting_toggle">
						<label class="wpf_switch_collaborator_setting_toggle"><span class="wpf_toggle_left"><?php _e("<u>New</u> Wordpress Account","wpfeedback"); ?></span>
							<input type="checkbox" name="wpf_collaborator_setting" class="wpf_collaborator_setting" >
							<span class="wpf_switch_slider wpf_switch_round"></span><span class="wpf_toggle_right"><?php _e("<u>Assign</u> a Wordpress Account","wpfeedback"); ?></span>
						</label>
						</div>
						<div class="wpf_collaborator_user_container">
							<p><label for="wpf_collaborator_name" class="wpf_text_label"><?php _e("Main Collaborator Full Name","wpfeedback"); ?> <span>*</span></label>
							<input type="text" class="wpf_text wpf_collaborator_name" placeholder="<?php _e("Client Name","wpfeedback"); ?>"></p>
							<p><label for="wpf_collaborator_email" class="wpf_text_label"><?php _e("Main Collaborator Email Address","wpfeedback"); ?> <span>*</span></label>
							<input type="text" class="wpf_text wpf_collaborator_email" placeholder="<?php _e("client@yourdomain.com","wpfeedback"); ?>"></p>  
							<p class="wpf_wizard_note"><span>* </span><?php _e("The auto-generated password <b><u>will not</u></b> be shared with the new user.","wpfeedback"); ?> </p>
						</div>
						<div class="wpf_collaborator_user_assign_container">
							<p>
							<label class="wpf_text_label"><?php _e("Main Collaborator Account","wpfeedback"); ?></label>
							<select class="wp_feedback_filter_admin_user">
								<option value="select"><?php _e("Select a User","wpfeedback"); ?></option>
								<?php foreach ($wpf_admin_users as $user) { ?>
									<option value="<?php echo esc_attr($user->ID); ?>"><?php echo esc_html($user->display_name); ?></option>
								<?php } ?>
							</select>
							</p>
						</div>
					</div>
					<div class="wpf_guest_wrap">
							<label for="wpf_allow_guest" class="wpf_checkbox_label">
								<p><b><?php _e('Enable "Guest Mode"', 'wpfeedback'); ?></b><br>
									<?php _e("Allow guests to create tickets and leave comments, without the need to login to the site. ideal for staging sites or during the build, but not idea for live websites.","wpfeedback"); ?></p>
						</label>
							<label class="wpf_switch"><input type="checkbox" name="wpf_allow_guest" value="yes" class="wpf_checkbox"
								  id="wpf_allow_guest" <?php if (get_site_data_by_key('wpf_allow_guest') == 'yes') {
								echo 'checked';
							} ?>/><span class="wpf_switch_slider wpf_switch_round"></span></label>
					</div>
					<p id="wpf_global_erro_msg" class="wpf_hide" style="color: red;"><?php _e("There seems to be some issue with enabling the global settings. Please contact support for help.","wpfeedabck"); ?></p>
					<br>
					<div class="wpf_wizard_footer">
						<btn href="javascript:void(0);" class="wpf_button wpf_final_step"
							 id="wpf_initial_setup_second_step_button"><?php _e("Create an Account","wpfeedback"); ?>
						</btn>
					</div>
				</div>
            </div>

            <div id="wpf_initial_settings_third_step" class="wpf_initial_container wpf_hide">
                <div class="wpf_title_wizard"><?php _e("3. Choose notifications","wpfeedback"); ?></div>
                <p>
                    <b><?php _e("Which notifications would you like the plugin to send out?","wpfeedback"); ?></b><br>
                    <?php _e("These are global settings. Each user can then choose their own notifications out of the options selected here.","wpfeedback"); ?>
                </p>
                <div>
                    <input type="checkbox" name="wpf_every_new_task" value="yes" class="wpf_checkbox"
                           id="wpf_every_new_task" checked />
                    <label for="wpf_every_new_task" class="wpf_checkbox_label"><?php _e('Send email notification for every new task', 'wpfeedback'); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_every_new_comment" value="yes" class="wpf_checkbox"
                           id="wpf_every_new_comment" checked />
                    <label for="wpf_every_new_comment" class="wpf_checkbox_label"><?php _e('Send email notification for every new comment', 'wpfeedback'); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_every_new_complete" value="yes" class="wpf_checkbox"
                           id="wpf_every_new_complete" checked />
                    <label for="wpf_every_new_complete" class="wpf_checkbox_label"><?php _e('Send email notification when a task is marked as complete', 'wpfeedback'); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_every_status_change" value="yes" class="wpf_checkbox"
                           id="wpf_every_status_change" checked />
                    <label for="wpf_every_status_change" class="wpf_checkbox_label"><?php _e('Send email notification for every status change', 'wpfeedback'); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_daily_report" value="yes" class="wpf_checkbox"
                           id="wpf_daily_report" checked />
                    <label for="wpf_daily_report" class="wpf_checkbox_label"><?php _e('Send email notification for last 24 hours report', 'wpfeedback'); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_weekly_report" value="yes" class="wpf_checkbox"
                           id="wpf_weekly_report" checked />
                    <label for="wpf_weekly_report" class="wpf_checkbox_label"><?php _e('Send email notification for last 7 days report', 'wpfeedback'); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_auto_daily_report" value="yes" class="wpf_checkbox"
                           id="wpf_auto_daily_report" checked />
                    <label for="wpf_auto_daily_report" class="wpf_checkbox_label"><?php _e('Auto send email notification for daily report', 'wpfeedback'); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_auto_weekly_report" value="yes" class="wpf_checkbox"
                           id="wpf_auto_weekly_report" checked />
                    <label for="wpf_auto_weekly_report" class="wpf_checkbox_label"><?php _e('Auto send email notification for weekly report', 'wpfeedback'); ?></label>
                </div>
                <br>
                <hr>
                <br>
                <div class="wpf_wizard_footer">
                    <a href="javascript:void(0);" id="wpf_initial_setup_third_step_prev_button"><?php _e("<< Back","wpfeedback"); ?></a>
                    <btn href="javascript:void(0);" class="wpf_button wpf_next"
                         id="wpf_initial_setup_third_step_button"><?php _e("Next >>","wpfeedback"); ?>
                    </btn>
                </div>
            </div>
            <div id="wpf_initial_settings_fourth_step" class="wpf_initial_container wpf_hide">
				<div class="wpf_wizard_progress_box">
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e("Install","wpfeedback"); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e("Connect","wpfeedback"); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e("Collaborate","wpfeedback"); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_current">
						<div class="wpf_wizard_progress_num">4</div>
						<div class="wpf_wizard_progress_desc"><?php _e("Complete","wpfeedback"); ?></div>
					</div>
				</div>
				<div class="wpf_wizard_content_box">	
					<div class="wpf_title_wizard"><?php _e("Is This Your First Time with Atarim?","wpfeedback"); ?></div>
					<p><?php _e("Watch the short video so that we can get your solid results. Choose whether it's your first time or you've used Atarim before.","wpfeedback"); ?></p>
					<div class="wpf_wizard_video">
						<script src="https://fast.wistia.com/embed/medias/l18ga56xuh.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_l18ga56xuh videoFoam=true" style="height:100%;position:relative;width:100%">&nbsp;</div></div></div>
					</div>
					<div class="wpf_wizard_dual_btns">
						<btn class="wpf_button wpf_button_sec" onclick="wpf_initial_setup_done('<?php echo WPF_SITE_URL; ?>', 'wpf_existing_user')"><?php _e("I've Done This Before"); ?></btn>
						<btn class="wpf_button" onclick="wpf_initial_setup_done('<?php echo WPF_SITE_URL; ?>', 'wpf_new_user')"><?php _e("It's My First Time","wpfeedback"); ?></btn>
					</div>
				</div>
            </div>
        </form>
    </div>
<!--     <div class="wpf_skip_wizard"><a href="javascript:void(0)" onclick="wpf_initial_setup_done('<?php echo WPF_SITE_URL; ?>', '')"><?php _e("Skip Wizard","wpfeedback"); ?></a></div> -->
</div>