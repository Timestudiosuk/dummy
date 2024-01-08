<?php

class WA_Bertha_AI_Admin {

	private $plugin_url;
    public $plugin_path;
    public $license_key;
    public $price_id;
    public $dashboard_url;
    public $item_id;
    public $file;
	public $license_details;

	public function __construct($file) {

        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->license_key = BTHAI_LICENSE_KEY;
        $this->price_id = BTHAI_LICENSE_PRICE_ID;
        $this->dashboard_url = BTHAI_STORE_URL;
        $this->item_id = BTHAI_ITEM_ID;
        $this->license_details = bthai_get_license_details('all');

        add_action('plugins_loaded', array(&$this, 'bthai_menu_init'));
    }

    function bthai_menu_init() {
		$plugin_type = isset($this->license_details->bertha_plugin_type) ? $this->license_details->bertha_plugin_type : '';            
		add_action('admin_menu', array(&$this, 'bthai_menu_submenu'));

		if($plugin_type && $plugin_type == 'pro') {
			add_filter('bulk_actions-edit-product', array(&$this, 'bthai_product_action_title'));
			add_filter('handle_bulk_actions-edit-product', array(&$this, 'bthai_handle_product_action'), 10, 3);
			add_filter('bulk_actions-edit-download', array(&$this, 'bthai_download_action_title'));
			add_filter('handle_bulk_actions-edit-download', array(&$this, 'bthai_handle_download_action'), 10, 3);
			add_filter('manage_product_posts_columns', array(&$this, 'bthai_product_column_header'));
			add_filter('edd_download_columns', array(&$this, 'bthai_download_column_header'));
			add_action('manage_product_posts_custom_column', array(&$this, 'bthai_posttype_column_content'), 10, 2);
			add_action('manage_download_posts_custom_column', array(&$this, 'bthai_posttype_column_content'), 10, 2);
			add_action('admin_notices', array(&$this, 'bthai_display_notices'));
			add_filter('http_request_timeout', array(&$this, 'bthai_revise_timeout'));
		}
	}

	function bthai_menu_submenu() {
		global $wp_roles;
		$plugin_type = '';
		if(BTHAI_LICENSE_KEY) {
            $plugin_type = $this->license_details->bertha_plugin_type;
            $free_option = json_decode($this->license_details->free_templates);
            $long_form_free = isset($free_option->long_form_version) ? true : false;

            if($plugin_type && $plugin_type != 'pro') {
	            $default_license_setting = array(
	                                            'bereverywhere' => 'yes',
	                                            'ber_frontend_backend' => array('yes', 'no'),
	                                            'ber_select_users' => array_keys($wp_roles->roles)
	                                        );
	            update_option('bertha_ai_license_options', $default_license_setting);
	        }
	    }

	    add_menu_page('Dashboard', __('Bertha AI', 'bertha-ai'), 'manage_options', 'bertha-ai-setting', 'bertha_ai_menu_redirect', plugin_dir_url( $this->file ).'assets/images/Bertha_icon_white.svg', 59);
	    add_submenu_page('bertha-ai-setting', __('Launch Bertha', 'bertha-ai'), __('Launch Bertha', 'bertha-ai'), 'manage_options', 'bertha-ai-setting', false);
	    add_submenu_page('bertha-ai-setting', __('Bertha Chat', 'bertha-ai'), __('Bertha Chat', 'bertha-ai'), 'manage_options', 'bertha-ai-chat-setting', array( $this, 'bthai_dashboard_chat_callback' ));
	    add_submenu_page('bertha-ai-setting', __('Bertha Art', 'bertha-ai'), __('Bertha Art', 'bertha-ai'), 'manage_options', 'bertha-ai-art-setting', array( $this, 'bthai_dashboard_art_callback' ));
	    add_submenu_page('bertha-ai-setting', __('Brand Settings', 'bertha-ai'), __('Brand Settings', 'bertha-ai'), 'manage_options', 'bertha-ai-content-setting', array( $this, 'bthai_dashboard_callback' ));
	    add_submenu_page('bertha-ai-setting', __('General Settings', 'bertha-ai'), __('General Settings', 'bertha-ai'), 'manage_options', 'bertha-ai-license-setting', array( $this, 'bthai_dashboard_license_callback' ));
	    add_submenu_page('bertha-ai-setting', __('License', 'bertha-ai'), __('License', 'bertha-ai'), 'manage_options', 'bertha-ai-license', array( $this, 'bthai_license_cb' ));
	    add_submenu_page(null, __('Onboarding Dashboard', 'bertha-ai'), __('Onboard Dashboard', 'bertha-ai'), 'manage_options', 'wa-onboard-dashboard', array( $this, 'bthai_onboard_dashboard_callback' ));

	    /*free */
	    add_submenu_page(null, __('Onboarding Free Dashboard', 'bertha-ai'), __('Onboard Free Dashboard', 'bertha-ai'), 'manage_options', 'wa-free-onboard-dashboard', array( $this, 'bthai_onboard_free_dashboard_callback' ));
	}

	function bertha_ai_menu_redirect() {
	    wp_safe_redirect( admin_url( 'admin.php?page=bertha-ai-license-setting' ) );
	    exit;
	}

	function bthai_dashboard_callback() {
		global $wp_roles;
		if(isset($_POST['bertha_generat_content_settings'])) {

			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'bertha-general-content-setting' ) ) return;

			$setup_wizard = array();
			$setup_wizard['language'] = isset($_POST['berlanguage']) ? sanitize_text_field($_POST['berlanguage']) : "";
		    $setup_wizard['brand_name'] = isset($_POST['berbrand']) ? sanitize_text_field(str_replace("\'", "'", $_POST['berbrand'])) : "";
		    $setup_wizard['customer_details'] = isset($_POST['berdescription']) ? sanitize_textarea_field(str_replace("\'", "'", $_POST['berdescription'])) : "";
		    $setup_wizard['ideal_customer'] = isset($_POST['beraudience']) ? sanitize_text_field(str_replace("\'", "'", $_POST['beraudience'])) : "";
		    $setup_wizard['sentiment'] = isset($_POST['bersentiment']) ? sanitize_text_field(str_replace("\'", "'", $_POST['bersentiment'])) : "";
		 
		    update_option('bertha_ai_options', $setup_wizard);
		}
		$Setting = get_option('bertha_ai_options') ? (array) get_option('bertha_ai_options') : array();
		$language = isset($Setting['language']) ? $Setting['language'] : '';
		$berbrand = isset($Setting['brand_name']) ? esc_attr($Setting['brand_name']) : '';
		$berdescription = isset($Setting['customer_details']) ? esc_attr($Setting['customer_details']) : '';
		$beraudience = isset($Setting['ideal_customer']) ? esc_attr($Setting['ideal_customer']) : '';
		$bersentiment = isset($Setting['sentiment']) ? esc_attr($Setting['sentiment']) : '';
		
   		$plugin_type = ($this->license_details && $this->license_details->bertha_plugin_type) ? $this->license_details->bertha_plugin_type : '';

	    $long_form_redirect = ($plugin_type && $plugin_type != 'pro') ? esc_url(admin_url( 'admin.php?page=bertha-ai-want-more' )) : esc_url(admin_url( 'admin.php?page=bertha-ai-backend-bertha' ));
		?>
		<div class="ber_page_header">
			<div class="ber_logo_head">
				<img src="<?php echo plugin_dir_url( $this->file ); ?>assets/images/Bertha_icon_purple_line.svg" alt="Bertha Logo" class="bertha_logo" />
			</div>
			<div class="ber_title_head">
				<div class="ber_title"><?php echo esc_html_e('Brand Settings', 'bertha-ai'); ?></div> 
			</div>
			<div class="ber_menu_head">
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-chat-setting' )); ?>"><?php echo esc_html_e('Chat', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-art-setting' )); ?>"><?php echo esc_html_e('Art', 'bertha-ai'); ?></a> <a href="#" class="ber_current_page"><?php echo esc_html_e('Brand', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-license-setting' )); ?>"><?php echo esc_html_e('Settings', 'bertha-ai'); ?></a><a target="_blank" href="https://bertha.ai/support/?plugin=1"><?php echo esc_html_e('Support', 'bertha-ai'); ?></a><a target="_blank" href="https://bertha.ai/#doit"><?php echo esc_html_e('Upgrade', 'bertha-ai'); ?></a>
			</div>
		</div>
		<div class="ber_page_wrap">
		<div class="ber_settings_form">
		<p class="ber_p_desc ber_page_info"><?php echo esc_html_e('Add the details of your brand below to help Bertha to know you better.', 'bertha-ai'); ?><br><?php echo esc_html_e('This will be used to help Bertha generate content ideas that are unique to your brand and preferences.', 'bertha-ai'); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'bertha-general-content-setting' ); ?>
			<div class="ber_form">				
			    <div class="ber_form_group ber_brand">
			        <label for="berbrand" class="ber_label"><?php echo esc_html_e('Brand Name', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element"  data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Your company or brand name', 'bertha-ai'); ?>">?</span></label>
			        <input type="text" class="ber_field" name="berbrand" access="false" maxlength="100" id="berbrand" title="<?php echo esc_html_e('Your company or brand name', 'bertha-ai'); ?>" required="required" aria-required="true" value="<?php echo esc_attr($berbrand); ?>">
			    </div>
			    <div class="ber_form_group ber_description">
			        <label for="berdescription" class="ber_label"><?php echo esc_html_e('Company Description', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Describe what you do, where you started, your products or services and the benefits you bring to the market', 'bertha-ai'); ?>">?</span></label>
			        <textarea type="textarea" class="ber_field" name="berdescription" access="false" maxlength="800" rows="10" id="berdescription" title="<?php echo esc_html_e('Describe what you do, where you started, your products or services and the benefits you bring to the market', 'bertha-ai'); ?>" required="required" aria-required="true"><?php echo esc_attr($berdescription); ?></textarea>
			    </div>
			    <div class="ber_form_group ber_audience">
			        <label for="beraudience" class="ber_label"><?php echo esc_html_e('Ideal Customer', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Describe the group/s of people you are serving or targeting', 'bertha-ai'); ?>">?</span></label>
			        <input type="text" class="ber_field" name="beraudience" access="false" maxlength="100" id="beraudience" title="<?php echo esc_html_e('Describe the group/s of people you are serving or targeting', 'bertha-ai'); ?>" required="required" aria-required="true" value="<?php echo esc_attr($beraudience); ?>">
			    </div>
			    <div class="ber_form_group ber-field-bersentiment">
			        <label for="bersentiment" class="ber_label"><?php echo esc_html_e('Tone of Voice', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('One word to describe the vibe you want Bertha to bring to your copy', 'bertha-ai'); ?>">?</span></label>
			        <input type="text" placeholder="Witty" class="ber_field" name="bersentiment" access="false" maxlength="20" id="bersentiment" title="<?php echo esc_html_e('One word to describe the vibe you want Bertha to bring to your copy', 'bertha-ai'); ?>" required="required" aria-required="true" value="<?php echo esc_attr($bersentiment); ?>">
			    </div>
			    <div class="ber_form_group ber_language">
	                <label for="berlanguage" class="ber_label"><?php echo esc_html_e('Language', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element"  data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Select language for prompt', 'bertha-ai'); ?>">?</span></label>
	                <select class="ber_field" name="berlanguage" access="false" id="berlanguage" title="<?php echo esc_html_e('Select language for prompt', 'bertha-ai'); ?>" required="required" aria-required="true">
	                	<option value="en" <?php if($language == "en") echo "selected"; ?>><?php echo esc_html_e('English', 'bertha-ai'); ?></option>
	                	<option value="fr" <?php if($language == "fr") echo "selected"; ?>><?php echo esc_html_e('French', 'bertha-ai'); ?></option>
	                	<option value="de" <?php if($language == "de") echo "selected"; ?>><?php echo esc_html_e('German', 'bertha-ai'); ?></option>
	                	<option value="nl" <?php if($language == "nl") echo "selected"; ?>><?php echo esc_html_e('Dutch', 'bertha-ai'); ?></option>
	                	<option value="es" <?php if($language == "es") echo "selected"; ?>><?php echo esc_html_e('Spanish', 'bertha-ai'); ?></option>
	                	<option value="iw" <?php if($language == "iw") echo "selected"; ?>><?php echo esc_html_e('Hebrew', 'bertha-ai'); ?></option>
	                	<option value="it" <?php if($language == "it") echo "selected"; ?>><?php echo esc_html_e('Italian', 'bertha-ai'); ?></option>
	                	<option value="pt-PT" <?php if($language == "pt-PT") echo "selected"; ?>><?php echo esc_html_e('Portuguese', 'bertha-ai'); ?></option>
	                	<option value="bg" <?php if($language == "bg") echo "selected"; ?>><?php echo esc_html_e('Bulgarian', 'bertha-ai'); ?></option>
	                	<option value="hr" <?php if($language == "hr") echo "selected"; ?>><?php echo esc_html_e('Croatian', 'bertha-ai'); ?></option>
	                	<option value="cs" <?php if($language == "cs") echo "selected"; ?>><?php echo esc_html_e('Czech', 'bertha-ai'); ?></option>
	                	<option value="da" <?php if($language == "da") echo "selected"; ?>><?php echo esc_html_e('Danish', 'bertha-ai'); ?></option>
	                	<option value="et" <?php if($language == "et") echo "selected"; ?>><?php echo esc_html_e('Estonian', 'bertha-ai'); ?></option>
	                	<option value="fi" <?php if($language == "fi") echo "selected"; ?>><?php echo esc_html_e('Finnish', 'bertha-ai'); ?></option>
	                	<option value="el" <?php if($language == "el") echo "selected"; ?>><?php echo esc_html_e('Greek', 'bertha-ai'); ?></option>
	                	<option value="hu" <?php if($language == "hu") echo "selected"; ?>><?php echo esc_html_e('Hungarian', 'bertha-ai'); ?></option>
	                	<option value="ga" <?php if($language == "ga") echo "selected"; ?>><?php echo esc_html_e('Irish', 'bertha-ai'); ?></option>
	                	<option value="lv" <?php if($language == "lv") echo "selected"; ?>><?php echo esc_html_e('Latvian', 'bertha-ai'); ?></option>
	                	<option value="lt" <?php if($language == "lt") echo "selected"; ?>><?php echo esc_html_e('Lithuanian', 'bertha-ai'); ?></option>
	                	<option value="mt" <?php if($language == "mt") echo "selected"; ?>><?php echo esc_html_e('Maltese', 'bertha-ai'); ?></option>
	                	<option value="pl" <?php if($language == "pl") echo "selected"; ?>><?php echo esc_html_e('Polish', 'bertha-ai'); ?></option>
	                	<option value="ro" <?php if($language == "ro") echo "selected"; ?>><?php echo esc_html_e('Romanian', 'bertha-ai'); ?></option>
	                	<option value="sk" <?php if($language == "sk") echo "selected"; ?>><?php echo esc_html_e('Slovak', 'bertha-ai'); ?></option>
	                	<option value="sl" <?php if($language == "sl") echo "selected"; ?>><?php echo esc_html_e('Slovenian', 'bertha-ai'); ?></option>
	                	<option value="sv" <?php if($language == "sv") echo "selected"; ?>><?php echo esc_html_e('Swedish', 'bertha-ai'); ?></option>
	                	<option value="ja" <?php if($language == "ja") echo "selected"; ?>><?php echo esc_html_e('Japanese', 'bertha-ai'); ?></option>
	                	<option value="no" <?php if($language == "no") echo "selected"; ?>><?php echo esc_html_e('Norwegian', 'bertha-ai'); ?></option>
	                </select>
	            </div>
			    <div class="ber_form_group ber_savechanges">
			        <input type="submit" class="ber_button bertha_generat_settings" name="bertha_generat_content_settings" access="false" id="bersavechanges" value="<?php echo esc_html_e('Save Changes', 'bertha-ai'); ?>">
			    </div>
			</div>
		</form>
		</div>
		<div class="ber_settings_sidebar">
		    <div class="ber_nicebox ber_metrix">
		        <div class="ber_title"><span class="ber_setting_icons">&#128640;</span> <?php echo esc_html_e('Usage Metrics', 'bertha-ai'); ?></div>
	            <?php
	            if($this->license_key) {
                    $license_limit = $this->license_details->limit;
                    $license_limit_used = $this->license_details->limit_used;
                    $limit_percentage = ( $license_limit_used * 100 ) / $license_limit;
                    $limit_percentage = $limit_percentage >= 0 ? $limit_percentage : 100;
                    if($limit_percentage < 50) {
                        $meter = 'success';
                    }elseif($limit_percentage >= 50 && $limit_percentage < 80) {
                        $meter = 'warning';
                    }elseif($limit_percentage >= 80) {
                        $meter = 'danger';
                    }
                    $bertha_limit_left =  ($license_limit_used.' / '. $license_limit);

                    $bertha_image_credits_left = '';
                    if(isset($this->license_details->image_credits)) {
	                    $image_credits = $this->license_details->image_credits;
	                    $image_credits_used = $this->license_details->image_credits_used;
	                    $image_credits_percentage = ( $image_credits_used * 100 ) / $image_credits;
	                    $image_credits_percentage = $image_credits_percentage >= 0 ? $image_credits_percentage : 100;
	                    if($image_credits_percentage < 50) {
	                        $img_meter = 'success';
	                    }elseif($image_credits_percentage >= 50 && $limit_percentage < 80) {
	                        $img_meter = 'warning';
	                    }elseif($image_credits_percentage >= 80) {
	                        $img_meter = 'danger';
	                    }
	                    $bertha_image_credits_left =  ($image_credits_used.' / '. $image_credits);
	                }
	                ?>
	                <style>
	                    .ber-progress-bar::after {
	                        content: "<?php echo esc_attr($bertha_limit_left);?>";
	                        position: absolute;
	                        left: 50%;
	                        color: black;
	                    }
	                    .ber-img-progress-bar::after {
	                        content: "<?php echo esc_attr($bertha_image_credits_left);?>";
	                        position: absolute;
	                        left: 50%;
	                        color: black;
	                    }
	                </style>
	                <div class="ber_metrix_bar">
	                	<p class="ber-text-progress-title"><?php echo esc_html_e('Text Credits', 'bertha-ai'); ?></p>
	                    <?php
	                    if($license_limit_used >= $license_limit) { ?>
	                        <a class="ber_btn" href="https://bertha.ai/ran-out-of-words/?plugin=1" target="_blank">Upgrade Now</a> <?php
	                    } else { ?>
	                        <div class="ber-progress">
	                            <div class="ber-progress-bar bg-<?php echo $meter; ?>" role="ber-progressbar" style="width: <?php echo esc_attr($limit_percentage); ?>%" aria-valuenow="<?php echo esc_attr($limit_percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
	                        </div> <?php
	                    }
	                    ?>
	                </div>
	                <?php if(isset($this->license_details->image_credits)) { ?>
	                <div class="ber_metrix_bar ber-img">
						<p class="ber-text-progress-title"><?php echo esc_html_e('Image Credits', 'bertha-ai'); ?></p>
	                    <?php
	                    if($image_credits_used >= $image_credits) { ?>
	                        <a class="ber_btn" href="https://bertha.ai/ran-out-of-words/?plugin=1" target="_blank">Upgrade Now</a> <?php
	                    } else { ?>
	                        <div class="ber-progress">
	                            <div class="ber-img-progress-bar bg-<?php echo $img_meter; ?>" role="ber-progressbar" style="width: <?php echo esc_attr($image_credits_percentage); ?>%" aria-valuenow="<?php echo esc_attr($image_credits_percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
	                        </div> <?php
	                    }
	                    ?>
	                </div>
	                <?php
	            	}
	            }
	            if($plugin_type != 'pro') {
		            ?>
			        <p><?php echo esc_html_e('Upgrade to Pro today to get the full benefit of Ask me anything, user control and enhanced settings.', 'bertha-ai'); ?></p>
			        <a class="ber_btn" href="https://bertha.ai/#doit" target="_blank"><?php echo esc_html_e('Upgrade Now', 'bertha-ai'); ?></a>
			    <?php } ?>
		    </div>
		    <div class="ber_nicebox ber_fbgroup">
		        <div class="ber_title"><span class="ber_setting_icons">&#129309;</span> <?php echo esc_html_e('Join Bertha\'s Community', 'bertha-ai'); ?></div>
		        <p><?php echo esc_html_e('Our community of business owners, writers and content marketers are constantly sharing their knowledge to help you become a better writer.', 'bertha-ai'); ?></p>
		        <a class="ber_btn" href="https://www.facebook.com/groups/340991974145634" target="_blank"><?php echo esc_html_e('Join The Facebook Community', 'bertha-ai'); ?></a>
		    </div>
		    <div class="ber_nicebox ber_review">
		        <div class="ber_title"><span class="ber_setting_icons">&#11088;</span> <?php echo esc_html_e('Show Bertha Some Love', 'bertha-ai'); ?></div>
		        <p><?php echo esc_html_e ('Use Bertha to write a review in just 3 clicks - This helps her spread the word of the work she is doing', 'bertha-ai'); ?> &#128588;</p>
		        <a class="ber_btn" href="https://wordpress.org/support/plugin/bertha-ai-free/reviews/#new-post" target="_blank"><?php echo esc_html_e('Post a Review', 'bertha-ai'); ?></a>
		    </div>
		    <div class="ber_nicebox ber_refer">
		        <div class="ber_title"><span class="ber_setting_icons">&#128075;</span> <?php echo esc_html_e('Refer Bertha to a Friend', 'bertha-ai'); ?></div>
		        <p><?php echo esc_html_e('Help your friends, clients and partners create better content for their website to', 'bertha-ai'); ?> <b><?php echo esc_html_e('generate substantial recurring revenue', 'bertha-ai'); ?></b>.</p>
		        <a class="ber_btn" href="https://bertha.ai/partners/?plugin=1" target="_blank"><?php echo esc_html_e('Become Bertha\'s Partner', 'bertha-ai'); ?></a>
		    </div>
		</div>
		    
		</div>
		<?php
	}

	function bthai_dashboard_license_callback() {
		global $wp_roles;
		if(isset($_POST['bertha_generat_license_settings'])) {

			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'bertha-general-license-setting' ) ) return;

			$setup_wizard = array();
		    $setup_wizard['bereverywhere'] = isset($_POST['bereverywhere']) ? sanitize_text_field($_POST['bereverywhere']) : "";
		    $setup_wizard['ber_frontend_backend'] = isset($_POST['ber_frontend_backend']) ? (array) ($_POST['ber_frontend_backend']) : "";
	 		$setup_wizard['ber_select_users'] = isset($_POST['berselect_users']) ? (array) ($_POST['berselect_users']) : array('administrator');
	 		$setup_wizard['berideas'] = isset($_POST['berideas']) ? sanitize_text_field($_POST['berideas']) : "";
		 
		    update_option('bertha_ai_license_options', $setup_wizard);
		}
		$Setting = get_option('bertha_ai_license_options') ? (array) get_option('bertha_ai_license_options') : array();
		$ber_everywhere = isset($Setting['bereverywhere']) ? esc_attr($Setting['bereverywhere']) : 'yes';
		$ber_frontend_backend = isset($Setting['ber_frontend_backend']) ? (array) ($Setting['ber_frontend_backend']) : array('yes', 'no');
		$ber_select_users = isset($Setting['ber_select_users']) ? (array) ($Setting['ber_select_users']) : array('administrator');
		$berideas = isset($Setting['berideas']) ? esc_attr($Setting['berideas']) : 4;
		
   		$plugin_type = $this->license_details->bertha_plugin_type ? $this->license_details->bertha_plugin_type : '';

	    $long_form_redirect = ($plugin_type && $plugin_type != 'pro') ? esc_url(admin_url( 'admin.php?page=bertha-ai-want-more' )) : esc_url(admin_url( 'admin.php?page=bertha-ai-backend-bertha' ));
		?>
			<div class="ber_page_header">
				<div class="ber_logo_head">
					<img src="<?php echo plugin_dir_url( $this->file ); ?>assets/images/Bertha_icon_purple_line.svg" alt="Bertha Logo" class="bertha_logo" />
				</div>
				<div class="ber_title_head">
					<div class="ber_title"><?php echo esc_html_e('General Settings', 'bertha-ai'); ?></div> 
				</div>
				<div class="ber_menu_head">
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-chat-setting' )); ?>"><?php echo esc_html_e('Chat', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-art-setting' )); ?>"><?php echo esc_html_e('Art', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-content-setting' )); ?>"><?php echo esc_html_e('Brand', 'bertha-ai'); ?></a> <a href="#" class="ber_current_page"><?php echo esc_html_e('Settings', 'bertha-ai'); ?></a> <a target="_blank" href="https://bertha.ai/support/?plugin=1"><?php echo esc_html_e('Support', 'bertha-ai'); ?></a><a target="_blank" href="https://bertha.ai/#doit"><?php echo esc_html_e('Upgrade', 'bertha-ai'); ?></a>
				</div>
			</div>
			<div class="ber_page_wrap">
				<div class="ber_settings_form">
					<?php if($plugin_type && $plugin_type == 'pro') { ?>
					<form method="post">
						<?php wp_nonce_field( 'bertha-general-license-setting' ); ?>
						<div class="ber_form">
							<div class="ber_form_group ber_everywhere">
						        <label for="bereverywhere" class="ber_label"><?php echo esc_html_e('Bertha Everywhere', 'bertha-ai'); ?><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Choose Bertha everywhere if you want Bertha to appear in each text field. If not - choose to only have Bertha available when you click on the side bar.', 'bertha-ai'); ?>">?</span></label>
						        <label>Everywhere</label><input type="radio" class="ber_field" name="bereverywhere" access="false" id="bereverywhere" required="required" aria-required="true" value="yes" <?php echo ($ber_everywhere == 'yes') ?  "checked" : "" ;  ?>/>
						        <label>Not Everywhere</label><input type="radio" class="ber_field" name="bereverywhere" access="false" id="bereverywhere_not" required="required" aria-required="true" value="no" <?php echo ($ber_everywhere == 'no') ?  "checked" : "" ;  ?>/>
						    </div>
						    <div class="ber_form_group ber_frontend_backend">
						        <label for="ber_frontend_backend" class="ber_label"><?php echo esc_html_e('Bertha Visibility', 'bertha-ai'); ?><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Choose whether to show Bertha on the Front-end of the website or only the wordpress admin dashboard.', 'bertha-ai'); ?>">?</span></label>
						        <label>Frontend</label><input type="checkbox" class="ber_field" name="ber_frontend_backend[]" access="false" id="ber_frontend" aria-required="true" value="yes" <?php echo (in_array('yes', $ber_frontend_backend)) ?  "checked" : "" ;  ?>/>
						        <label>Backend</label><input type="checkbox" class="ber_field" name="ber_frontend_backend[]" access="false" id="ber_Backend" aria-required="true" value="no" <?php echo (in_array('no', $ber_frontend_backend)) ?  "checked" : "" ;  ?>/>
						    </div>
						    <div class="ber_form_group ber_select_users">
						    	<label for="ber_select_users" class="ber_label"><?php echo esc_html_e("User Permissions", "bertha-ai"); ?><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Select all User Roles who should have access to Bertha. Shift+click to select multiple Roles or cmd/ctrl+A to select all.', 'bertha-ai'); ?>">?</span></label>
						    	<select multiple="true" name="berselect_users[]" id="berselect_users">
						        	<?php
						        	foreach($wp_roles->roles as $key => $role) {
						        		?><option value="<?php echo $key; ?>" <?php echo (in_array($key, $ber_select_users)) ?  "selected" : "" ;  ?>><?php echo $key; ?></option><?php
						        	}
						        	?>
						        </select>
						    </div>
						    <div class="ber_form_group ber-field-berideas">
						        <label for="berideas" class="ber_label"><?php echo esc_html_e('Number of Generated Ideas', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Choose how many text results Bertha displays to you.', 'bertha-ai'); ?>">?</span></label>
						        <select class="ber_field" name="berideas" access="false" id="berideas" title="<?php echo esc_html_e('Select Number of Ideas', 'bertha-ai'); ?>" required="required" aria-required="true">
				                	<option value="1" <?php if($berideas == "1") echo "selected"; ?>><?php echo esc_html_e('1', 'bertha-ai'); ?></option>
				                	<option value="2" <?php if($berideas == "2") echo "selected"; ?>><?php echo esc_html_e('2', 'bertha-ai'); ?></option>
				                	<option value="3" <?php if($berideas == "3") echo "selected"; ?>><?php echo esc_html_e('3', 'bertha-ai'); ?></option>
				                	<option value="4" <?php if($berideas == "4") echo "selected"; ?>><?php echo esc_html_e('4', 'bertha-ai'); ?></option>
				                </select>
						    </div>
						    <div class="ber_form_group ber_savechanges">
						        <input type="submit" class="ber_button bertha_generat_settings" name="bertha_generat_license_settings" access="false" id="bersavechanges" value="<?php echo esc_html_e('Save Changes', 'bertha-ai'); ?>">
						    </div>
						</div>
					</form>
					<?php } else { ?>
						<p class="ber_p_desc ber_page_info ber_user_role_info"><?php echo esc_html_e('Please upgrade to access this setting.', 'bertha-ai'); ?><a class="ber_btn" target="_blank" href="https://bertha.ai/#doit"><?php echo esc_html_e('Upgrade', 'bertha-ai'); ?></a></p>
					<?php } ?>
				</div>
				<div class="ber_settings_sidebar">
				    <div class="ber_nicebox ber_metrix">
				        <div class="ber_title"><span class="ber_setting_icons">&#128640;</span> <?php echo esc_html_e('Usage Metrics', 'bertha-ai'); ?></div>
			            <?php
			            if($this->license_key) {
		                    $license_limit = $this->license_details->limit;
		                    $license_limit_used = $this->license_details->limit_used;
		                    $limit_percentage = ( $license_limit_used * 100 ) / $license_limit;
		                    $limit_percentage = $limit_percentage >= 0 ? $limit_percentage : 100;
		                    if($limit_percentage < 50) {
		                        $meter = 'success';
		                    }elseif($limit_percentage >= 50 && $limit_percentage < 80) {
		                        $meter = 'warning';
		                    }elseif($limit_percentage >= 80) {
		                        $meter = 'danger';
		                    }
		                    $bertha_limit_left =  ($license_limit_used.' / '. $license_limit);

		                    $bertha_image_credits_left = '';
		                    if(isset($this->license_details->image_credits)) {
			                    $image_credits = $this->license_details->image_credits;
			                    $image_credits_used = $this->license_details->image_credits_used;
			                    $image_credits_percentage = ( $image_credits_used * 100 ) / $image_credits;
			                    $image_credits_percentage = $image_credits_percentage >= 0 ? $image_credits_percentage : 100;
			                    if($image_credits_percentage < 50) {
			                        $img_meter = 'success';
			                    }elseif($image_credits_percentage >= 50 && $limit_percentage < 80) {
			                        $img_meter = 'warning';
			                    }elseif($image_credits_percentage >= 80) {
			                        $img_meter = 'danger';
			                    }
			                    $bertha_image_credits_left =  ($image_credits_used.' / '. $image_credits);
			                }
			                ?>
			                <style>
			                    .ber-progress-bar::after {
			                        content: "<?php echo esc_attr($bertha_limit_left);?>";
			                        position: absolute;
			                        left: 50%;
			                        color: black;
			                    }
			                    .ber-img-progress-bar::after {
			                        content: "<?php echo esc_attr($bertha_image_credits_left);?>";
			                        position: absolute;
			                        left: 50%;
			                        color: black;
			                    }
			                </style>
			                <div class="ber_metrix_bar">
			                	<p class="ber-text-progress-title"><?php echo esc_html_e('Text Credits', 'bertha-ai'); ?></p>
			                    <?php
			                    if($license_limit_used >= $license_limit) { ?>
			                        <a class="ber_btn" href="https://bertha.ai/ran-out-of-words/?plugin=1" target="_blank">Upgrade Now</a> <?php
			                    } else { ?>
			                        <div class="ber-progress">
			                            <div class="ber-progress-bar bg-<?php echo $meter; ?>" role="ber-progressbar" style="width: <?php echo esc_attr($limit_percentage); ?>%" aria-valuenow="<?php echo esc_attr($limit_percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
			                        </div> <?php
			                    }
			                    ?>
			                </div>
			                <?php if(isset($this->license_details->image_credits)) { ?>
			                <div class="ber_metrix_bar ber-img">
								<p class="ber-text-progress-title"><?php echo esc_html_e('Image Credits', 'bertha-ai'); ?></p>
			                    <?php
			                    if($image_credits_used >= $image_credits) { ?>
			                        <a class="ber_btn" href="https://bertha.ai/ran-out-of-words/?plugin=1" target="_blank">Upgrade Now</a> <?php
			                    } else { ?>
			                        <div class="ber-progress">
			                            <div class="ber-img-progress-bar bg-<?php echo $img_meter; ?>" role="ber-progressbar" style="width: <?php echo esc_attr($image_credits_percentage); ?>%" aria-valuenow="<?php echo esc_attr($image_credits_percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
			                        </div> <?php
			                    }
			                    ?>
			                </div>
			                <?php
			            	}
			            }
			            if($plugin_type != 'pro') {
				            ?>
					        <p><?php echo esc_html_e('Upgrade to Pro today to get the full benefit of Ask me anything, user control and enhanced settings.', 'bertha-ai'); ?></p>
					        <a class="ber_btn" href="https://bertha.ai/#doit" target="_blank"><?php echo esc_html_e('Upgrade Now', 'bertha-ai'); ?></a>
					    <?php } ?>
				    </div>
				    <div class="ber_nicebox ber_fbgroup">
				        <div class="ber_title"><span class="ber_setting_icons">&#129309;</span> <?php echo esc_html_e('Join Bertha\'s Community', 'bertha-ai'); ?></div>
				        <p><?php echo esc_html_e('Our community of business owners, writers and content marketers are constantly sharing their knowledge to help you become a better writer.', 'bertha-ai'); ?></p>
				        <a class="ber_btn" href="https://www.facebook.com/groups/340991974145634" target="_blank"><?php echo esc_html_e('Join The Facebook Community', 'bertha-ai'); ?></a>
				    </div>
				    <div class="ber_nicebox ber_review">
				        <div class="ber_title"><span class="ber_setting_icons">&#11088;</span> <?php echo esc_html_e('Show Bertha Some Love', 'bertha-ai'); ?></div>
				        <p><?php echo esc_html_e ('Use Bertha to write a review in just 3 clicks - This helps her spread the word of the work she is doing', 'bertha-ai'); ?> &#128588;</p>
				        <a class="ber_btn" href="https://wordpress.org/support/plugin/bertha-ai-free/reviews/#new-post" target="_blank"><?php echo esc_html_e('Post a Review', 'bertha-ai'); ?></a>
				    </div>
				    <div class="ber_nicebox ber_refer">
				        <div class="ber_title"><span class="ber_setting_icons">&#128075;</span> <?php echo esc_html_e('Refer Bertha to a Friend', 'bertha-ai'); ?></div>
				        <p><?php echo esc_html_e('Help your friends, clients and partners create better content for their website to', 'bertha-ai'); ?> <b><?php echo esc_html_e('generate substantial recurring revenue', 'bertha-ai'); ?></b>.</p>
				        <a class="ber_btn" href="https://bertha.ai/partners/?plugin=1" target="_blank"><?php echo esc_html_e('Become Bertha\'s Partner', 'bertha-ai'); ?></a>
				    </div>
				</div>
			</div>
		<?php
	}

	function bthai_dashboard_chat_callback() {
		$plugin_type = $this->license_details->bertha_plugin_type;
        $free_option = json_decode($this->license_details->free_templates);
        $base_prompt = json_decode($this->license_details->free_templates)->chat_prompt_prompt;
		?>
		<div class="ber_page_header">
			<div class="ber_logo_head">
				<img src="<?php echo plugin_dir_url( $this->file ); ?>assets/images/Bertha_icon_purple_line.svg" alt="Bertha Logo" class="bertha_logo" />
			</div>
			<div class="ber_title_head">
				<div class="ber_title"><?php echo esc_html_e('Bertha Chat', 'bertha-ai'); ?></div> 
			</div>
			<div class="ber_menu_head">
				<a href="#" class="ber_current_page"><?php echo esc_html_e('Chat', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-art-setting' )); ?>"><?php echo esc_html_e('Art', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-content-setting' )); ?>"><?php echo esc_html_e('Brand', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-license-setting' )); ?>"><?php echo esc_html_e('Settings', 'bertha-ai'); ?></a><a target="_blank" href="https://bertha.ai/support/?plugin=1"><?php echo esc_html_e('Support', 'bertha-ai'); ?></a><a target="_blank" href="https://bertha.ai/#doit"><?php echo esc_html_e('Upgrade', 'bertha-ai'); ?></a>
			</div>
		</div>
		<div class="ber_page_wrap">
			<div class="ber_chat_page">
				<?php if(isset($free_option->chat_prompt_version) || $plugin_type == 'pro'){ ?>
					<div class="ber_art_title"><?php echo esc_html_e('Chat With Me', 'bertha-ai'); ?></div>
					<div class="ber_chat_top">
						<p class="ber_p_desc"><?php echo esc_html_e('You can ask me questions or have a conversation with me by typing below. I will do my best to understand and respond appropriately. Is there anything specific you would like to know or talk about?', 'bertha-ai'); ?></p>
						<button type="button" class="ber-btn bertha_sec_btn ber_chat_reset" data-dismiss="ber-modal"><?php echo esc_html_e('Reset The Chat', 'bertha-ai'); ?></button>
					</div>
					<form method="post" id="ber_chat_modal">
						<div class="ber_chat">
							<div class="ber_form_group ber-chat-body"></div>
		<!-- 			        	<label for="chatbody" class="ber_label"><?php echo esc_html_e('Chat with Bertha', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element"  data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('You can ask anything to Bertha', 'bertha-ai'); ?>">?</span></label> -->
							<div class="ber-chat-field">
								<textarea type="textarea" placeholder="<?php echo esc_html_e('Start typing to chat with Bertha...', 'bertha-ai'); ?>" class="ber_field" name="chatbody" access="false" id="ber_chat_body" required="required" aria-required="true" value=""></textarea>
								<input type="hidden" id="ber_chat_prompt" value="Bertha AI: <?php echo $base_prompt; ?>\nYou: " />
							</div>
							<div class="ber_form_group bertha_chat_buttons ber_generate ber-chat-submit">
								<button type="button" class="ber-btn ber-btn-primary ber_half ber_chat_generate" data-dismiss="ber-modal">Comment</button>
							</div>
						</div>
						<div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div>
					</form>
				<?php } else {
					echo '<div class="ber_notice"><p>'.__('This is a Premium Feature', 'bertha-ai').'</p><p><a class="bertha_premium_upgrade" href="https://bertha.ai/#doit" target="_blank">'.__('click to upgrade', 'bertha-ai').'</a></p></div>';

				} ?>
			</div>
		<?php
	}

	function bthai_dashboard_art_callback() {
		$image_credits = $this->license_details->image_credits ? $this->license_details->image_credits : 0;
	    $image_credits_used = $this->license_details->image_credits_used ? $this->license_details->image_credits_used : 0;
		?>
		<div class="ber_page_header">
			<div class="ber_logo_head">
				<img src="<?php echo plugin_dir_url( $this->file ); ?>assets/images/Bertha_icon_purple_line.svg" alt="Bertha Logo" class="bertha_logo" />
			</div>
			<div class="ber_title_head">
				<div class="ber_title"><?php echo esc_html_e('Bertha Art', 'bertha-ai'); ?></div> 
			</div>
			<div class="ber_menu_head">
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-chat-setting' )); ?>"><?php echo esc_html_e('Chat', 'bertha-ai'); ?></a> <a href="#" class="ber_current_page"><?php echo esc_html_e('Art', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-content-setting' )); ?>"><?php echo esc_html_e('Brand', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-license-setting' )); ?>"><?php echo esc_html_e('Settings', 'bertha-ai'); ?></a><a target="_blank" href="https://bertha.ai/support/?plugin=1"><?php echo esc_html_e('Support', 'bertha-ai'); ?></a><a target="_blank" href="https://bertha.ai/#doit"><?php echo esc_html_e('Upgrade', 'bertha-ai'); ?></a>
			</div>
		</div>
		<div class="ber_art_wrap" id="ber_image_generate_modal">
			<div class="ber-art-main-container">
				<div class="ber-art-options">
					<div class="ber-art-search">
						<div class="ber-art-wrap">
							<img src="<?php echo plugin_dir_url( $this->file ); ?>assets/images/search.svg">
							<div class="ber_inner_title"><?php echo esc_html_e('Search AI Generated Images', 'bertha-ai'); ?></div>
							<p><?php echo esc_html_e('Search community-shared AI-generated images; these don’t count towards your image total.', 'bertha-ai'); ?></p>
						</div>
					</div>
					<div class="ber-art-create">
						<div class="ber-art-wrap">
							<img src="<?php echo plugin_dir_url( $this->file ); ?>assets/images/ber-image.svg">
							<div class="ber_inner_title"><?php echo esc_html_e('Create Custom AI Images', 'bertha-ai'); ?></div>
							<p><?php echo esc_html_e('Describe what you would like to see and let the AI imagine new images and illustrations for you.', 'bertha-ai'); ?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div>
		</div>
		</form>
		</div>
		<?php
	}

	function bthai_license_cb() {
		$version = '';
		if(get_option('WEB_ACE_DASHBOARD_license_key')) {
			$license = get_option('WEB_ACE_DASHBOARD_license_key');
			$api_params = array(
				'edd_action' => 'get_version',
				'license'    => $license ? $license : '',
				'item_name'  => BTHAI_ITEM_NAME,
				'item_id'    => BTHAI_ITEM_ID,
				'version'    => BTHAI_VERSION,
				'slug'       => basename( __FILE__, '.php' ),
				'author'     => BTHAI_AUTHOR_NAME,
				'url'        => home_url(),
			);

			$request    = wp_remote_post( BTHAI_STORE_URL, array( 'timeout' => 15, 'sslverify' => true, 'body' => $api_params ) );

			if (!is_wp_error($request) && isset($request['body'])) {
				$version = json_decode($request['body']);
			}
		}

   		$plugin_type = $this->license_details->bertha_plugin_type ? $this->license_details->bertha_plugin_type : '';

	    $long_form_redirect = ($plugin_type && $plugin_type != 'pro') ? esc_url(admin_url( 'admin.php?page=bertha-ai-want-more' )) : esc_url(admin_url( 'admin.php?page=bertha-ai-backend-bertha' ));
		?>
		<div class="ber_page_header">
			<div class="ber_logo_head">
				<img src="<?php echo plugin_dir_url( $this->file ); ?>assets/images/Bertha_icon_purple_line.svg" alt="Bertha Logo" class="bertha_logo" />
			</div>
			<div class="ber_title_head">
				<div class="ber_title"><?php echo esc_html_e('License Verification', 'bertha-ai'); ?></div> 
			</div>
			<div class="ber_menu_head">
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-chat-setting' )); ?>"><?php echo esc_html_e('Chat', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-art-setting' )); ?>"><?php echo esc_html_e('Art', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-content-setting' )); ?>"><?php echo esc_html_e('Brand', 'bertha-ai'); ?></a> <a href="<?php echo esc_url(admin_url( 'admin.php?page=bertha-ai-license-setting' )); ?>"><?php echo esc_html_e('Settings', 'bertha-ai'); ?></a> <a target="_blank" href="https://bertha.ai/support/?plugin=1">Support</a> <a target="_blank" href="https://bertha.ai/#doit">Upgrade</a>
			</div>
		</div>
		<?php
	    echo '<div class="ber_page_wrap">';
	    echo '<div class="ber_license_container"><div id="icon-tools" class="icon32"></div>';
	    echo '<p class="ber_p_desc">'.__('Manage your license activation with 1 click.', 'bertha-ai').'</p>';

	    echo '<div>' . BTHAI_ITEM_NAME . ' - V' . BTHAI_VERSION . '</div>';
	    if ( $version && isset( $version->new_version ) ) {
	    	echo '<div>Latest Version : ' . esc_attr($version->new_version) . '</div>';
	    }

	    echo '<div id="poststuff">';

	    echo '<div id="post-body" class="metabox-holder columns-2">';

	    echo '<form method="POST">';

	    if( isset( $_POST['bertha_license_deactivate'] ) ) {

	    	if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'bertha-license-setup' ) ) return;

	        // retrieve the license from the database
	        $license = trim(get_option('WEB_ACE_DASHBOARD_license_key'));


	        // data to send in our API request
	        $api_params = array(
	            'edd_action'=> 'deactivate_license',
	            'license'   => $license,
	            'item_id' => BTHAI_ITEM_ID, // the name of our product in EDD
	            'url'       => get_admin_url()
	        );

	        // Call the custom API.
	        $response = wp_remote_post( BTHAI_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
	        
	        // make sure the response came back okay
	        if ( is_wp_error( $response ) )
	            return false;

	        // decode the license data
	        $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            delete_option( 'WEB_ACE_DASHBOARD_license_status' );
            delete_option( 'WEB_ACE_DASHBOARD_license_key' );
            delete_option('WEB_ACE_DASHBOARD_license_data');

            wp_clear_scheduled_hook( 'bertha_add_every_thirty_days' );
            delete_option('bertha_reniewal_start');	      
	    }

	    $license = get_option('WEB_ACE_DASHBOARD_license_key');
	    $status = get_option('WEB_ACE_DASHBOARD_license_status');
	    $expires = get_option('WEB_ACE_DASHBOARD_license_data');

	    wp_nonce_field( 'bertha-license-setup' );

	    echo '<div class="postbox bertha-license-container ber_nicebox">
                    <div class="ber_title">Plugin Licensing</div>
                    <div class="inside">';

	    if ($status !== false && $status == 'valid') {
	            echo '<p><span class="bertha_active" style="color:#0aaf3a;">' . __('License Active') . '</span></p>';
	            echo '<p>Expiry: ' . esc_attr($expires) . '</p>';
	            echo '<input type="submit" class="button-secondary bertha_deactivate_button" name="bertha_license_deactivate" value="'.__('Deactivate License', 'bertha-ai').'"/>';
	        } else {
	            $home_url = 'https://bertha.ai/activate?activation_callback='.Base64_encode(get_admin_url()).'&activation_item='.Base64_encode(BTHAI_ITEM_NAME).'&activation_item_id='.Base64_encode(BTHAI_ITEM_ID).'&activation_price_id='.Base64_encode(BTHAI_LICENSE_PRICE_ID).'&plugin=1&activation=true';
	            echo '<a href="'.$home_url.'"><button type="button" class="ber_button bersavechanges" name="bersavechanges" access="false" id="ber_page4_save">'.__('Activate', 'bertha-ai').'</button></a>';
	        }
	    echo '</div></div>';

	    echo '<div class="ber_form_group ber-field-transient-clear">
			        <button type="button" class="ber_button clear_transient" access="false">'.__('Clear Cache', 'bertha-ai').'</button>
			    </div>';

	    echo '</form>';

	    echo '</div>';
	    echo '</div>';

	    echo '</div>';
	    ?>
	    <div class="ber_settings_sidebar">
		    <div class="ber_nicebox ber_metrix">
		        <div class="ber_title"><span class="ber_setting_icons">&#128640;</span> <?php echo esc_html_e('Usage Metrics', 'bertha-ai'); ?></div>
	            <?php
	            if($this->license_key) {
                    $license_limit = $this->license_details->limit;
		    		$license_limit_used = $this->license_details->limit_used;
                    $limit_percentage = ( $license_limit_used * 100 ) / $license_limit;
                    $limit_percentage = $limit_percentage >= 0 ? $limit_percentage : 100;
                    if($limit_percentage < 50) {
                        $meter = 'success';
                    }elseif($limit_percentage >= 50 && $limit_percentage < 80) {
                        $meter = 'warning';
                    }elseif($limit_percentage >= 80) {
                        $meter = 'danger';
                    }
                    $bertha_limit_left =  ($license_limit_used.' / '. $license_limit);

                    $bertha_image_credits_left = '';
                    if(isset($this->license_details->image_credits)) {
	                    $image_credits = $this->license_details->image_credits;
	                    $image_credits_used = $this->license_details->image_credits_used;
	                    $image_credits_percentage = ( $image_credits_used * 100 ) / $image_credits;
	                    $image_credits_percentage = $image_credits_percentage >= 0 ? $image_credits_percentage : 100;
	                    if($image_credits_percentage < 50) {
	                        $img_meter = 'success';
	                    }elseif($image_credits_percentage >= 50 && $limit_percentage < 80) {
	                        $img_meter = 'warning';
	                    }elseif($image_credits_percentage >= 80) {
	                        $img_meter = 'danger';
	                    }
	                    $bertha_image_credits_left =  ($image_credits_used.' / '. $image_credits);
	                }
	                ?>
	                <style>
	                    .ber-progress-bar::after {
	                        content: "<?php echo esc_attr($bertha_limit_left);?>";
	                        position: absolute;
	                        left: 50%;
	                        color: black;
	                    }
	                    .ber-img-progress-bar::after {
	                        content: "<?php echo esc_attr($bertha_image_credits_left);?>";
	                        position: absolute;
	                        left: 50%;
	                        color: black;
	                    }
	                </style>
	                <div class="ber_metrix_bar">
	                	<p class="ber-text-progress-title"><?php echo esc_html_e('Text Credits', 'bertha-ai'); ?></p>
	                    <?php
	                    if($license_limit_used >= $license_limit) { ?>
	                        <a class="ber_btn" href="https://bertha.ai/ran-out-of-words/?plugin=1" target="_blank">Upgrade Now</a> <?php
	                    } else { ?>
	                        <div class="ber-progress">
	                            <div class="ber-progress-bar bg-<?php echo $meter; ?>" role="ber-progressbar" style="width: <?php echo esc_attr($limit_percentage); ?>%" aria-valuenow="<?php echo esc_attr($limit_percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
	                        </div> <?php
	                    }
	                    ?>
	                </div>
	                <?php if(isset($this->license_details->image_credits)) { ?>
	                <div class="ber_metrix_bar ber-img">
						<p class="ber-text-progress-title"><?php echo esc_html_e('Image Credits', 'bertha-ai'); ?></p>
	                    <?php
	                    if($image_credits_used >= $image_credits) { ?>
	                        <a class="ber_btn" href="https://bertha.ai/ran-out-of-words/?plugin=1" target="_blank">Upgrade Now</a> <?php
	                    } else { ?>
	                        <div class="ber-progress">
	                            <div class="ber-img-progress-bar bg-<?php echo $img_meter; ?>" role="ber-progressbar" style="width: <?php echo esc_attr($image_credits_percentage); ?>%" aria-valuenow="<?php echo esc_attr($image_credits_percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
	                        </div> <?php
	                    }
	                    ?>
	                </div>
	                <?php
	            	}
	            }
	            if($plugin_type != 'pro') {
		            ?>
			        <p><?php echo esc_html_e('Upgrade to Pro today to get the full benefit of Ask me anything, user control and enhanced settings.', 'bertha-ai'); ?></p>
			        <a class="ber_btn" href="https://bertha.ai/#doit" target="_blank"><?php echo esc_html_e('Upgrade Now', 'bertha-ai'); ?></a>
			    <?php } ?>
		    </div>
		    <div class="ber_nicebox ber_fbgroup">
		        <div class="ber_title"><span class="ber_setting_icons">&#129309;</span> <?php echo esc_html_e('Join Bertha\'s Community', 'bertha-ai'); ?></div>
		        <p><?php echo esc_html_e('Our community of business owners, writers and content marketers are constantly sharing their knowledge to help you become a better writer.', 'bertha-ai'); ?></p>
		        <a class="ber_btn" href="https://www.facebook.com/groups/340991974145634" target="_blank"><?php echo esc_html_e('Join The Facebook Community', 'bertha-ai'); ?></a>
		    </div>
		    <div class="ber_nicebox ber_review">
		        <div class="ber_title"><span class="ber_setting_icons">&#11088;</span> <?php echo esc_html_e('Show Bertha Some Love', 'bertha-ai'); ?></div>
		        <p><?php echo esc_html_e ('Use Bertha to write a review in just 3 clicks - This helps her spread the word of the work she is doing', 'bertha-ai'); ?> &#128588;</p>
		        <a class="ber_btn" href="https://wordpress.org/support/plugin/bertha-ai-free/reviews/#new-post" target="_blank"><?php echo esc_html_e('Post a Review', 'bertha-ai'); ?></a>
		    </div>
		    <div class="ber_nicebox ber_refer">
		        <div class="ber_title"><span class="ber_setting_icons">&#128075;</span> <?php echo esc_html_e('Refer Bertha to a Friend', 'bertha-ai'); ?></div>
		        <p><?php echo esc_html_e('Help your friends, clients and partners create better content for their website to', 'bertha-ai'); ?> <b><?php echo esc_html_e('generate substantial recurring revenue', 'bertha-ai'); ?></b>.</p>
		        <a class="ber_btn" href="https://bertha.ai/partners/?plugin=1" target="_blank"><?php echo esc_html_e('Become Bertha\'s Partner', 'bertha-ai'); ?></a>
		    </div>
		</div>
		    
		</div>

	   </div>
	   <?php
	}

	function bthai_onboard_dashboard_callback() {
	    global $current_user;
	    $user_email = isset($_GET['email']) ? $_GET['email'] : '';
	    if($user_email) {
	    	$home_url = 'https://bertha.ai/activate?activation_callback='.Base64_encode(get_admin_url()).'&activation_item='.Base64_encode(BTHAI_ITEM_NAME).'&activation_item_id='.Base64_encode(BTHAI_ITEM_ID).'&activation_price_id='.Base64_encode(BTHAI_LICENSE_PRICE_ID).'&plugin=1&email='.Base64_encode($user_email).'&activation=true';
	    } else {
    		$home_url = 'https://bertha.ai/activate?activation_callback='.Base64_encode(get_admin_url()).'&activation_item='.Base64_encode(BTHAI_ITEM_NAME).'&activation_item_id='.Base64_encode(BTHAI_ITEM_ID).'&activation_price_id='.Base64_encode(BTHAI_LICENSE_PRICE_ID).'&plugin=1&activation=true';
    	}
		?>
		<div class="ber_wizard_wrap">
		    <div id="ber_page1" class="ber_wizard_page">
		        <div class="ber_title">Hey, <?php echo esc_attr($current_user->display_name); ?>!<br><?php echo esc_html_e('I\'m Bertha, your new writing assistant', 'bertha-ai'); ?></div>
		        <p class="ber_p_desc"><?php echo esc_html_e('Watch this short video to learn what I can do for you and your writing experience.', 'bertha-ai'); ?><br><?php echo esc_html_e('The more we get to know each other, the better results I can provide for you and your business', 'bertha-ai'); ?></p>
		        <iframe src="https://player.vimeo.com/video/736630593?h=e8ffe857b3" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
		        <div class="ber_form">
		            <div class="ber_form_group ber_savechanges">
		                <button type="button" class="ber_button bersavechanges" name="bersavechanges" access="false" id="ber_page1_save"><?php echo esc_html_e('Next', 'bertha-ai'); ?></button>
		            </div>
		        </div>
		    </div>
		    <div id="ber_page3" class="ber_wizard_page" style="display:none;">
		        <div class="ber_title"><?php echo esc_html_e('Your Brand Settings', 'bertha-ai'); ?></div>
		        <p class="ber_p_desc"><?php echo esc_html_e('This will be used to help Bertha generate content ideas that are unique to your brand and preferences.', 'bertha-ai'); ?></p>
		        <div class="ber_form">
		        	<?php wp_nonce_field( 'bertha_wizzard_setup_form', 'bertha_wizzard_setup_nonce' ); ?>
		            <div class="ber_form_group ber_brand">
		                <label for="berbrand" class="ber_label"><?php echo esc_html_e('Brand Name', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element"  data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Your company or brand name', 'bertha-ai'); ?>">?</span></label>
		                <input type="text" class="ber_field" name="berbrand" access="false" id="berbrand" title="<?php echo esc_html_e('Your company or brand name', 'bertha-ai'); ?>" required="required" aria-required="true">
		            </div>
		            <div class="ber_form_group ber_description">
		                <label for="berdescription" class="ber_label"><?php echo  esc_html_e('Company Description', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Describe what you do, where you started, your products or services and the benefits you bring to the market', 'bertha-ai'); ?>">?</span></label>
		                <textarea type="textarea" class="ber_field" name="berdescription" access="false" rows="10" id="berdescription" title="<?php echo  esc_html_e('Describe what you do, where you started, your products or services and the benefits you bring to the market', 'bertha-ai'); ?>" required="required" aria-required="true"></textarea>
		            </div>
		            <div class="ber_form_group ber_audience">
		                <label for="beraudience" class="ber_label"><?php echo esc_html_e('Ideal Customer', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Describe the group/s of people you are serving or targeting', 'bertha-ai'); ?>">?</span></label>
		                <input type="text" class="ber_field" name="beraudience" access="false" id="beraudience" title="<?php echo esc_html_e('Describe the groups of people you are serving or targeting', 'bertha-ai'); ?>" required="required" aria-required="true">
		            </div>
		            <div class="ber_form_group ber-field-bersentiment">
		                <label for="bersentiment" class="ber_label"><?php echo esc_html_e('Tone of Voice', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('One word to describe the vibe you want Bertha to bring to your copy', 'bertha-ai'); ?>">?</span></label>
		                <input type="text" placeholder="Witty" class="ber_field" name="bersentiment" access="false" id="bersentiment" title="<?php echo esc_html_e('One word to describe the vibe you want Bertha to bring to your copy', 'bertha-ai'); ?>" required="required" aria-required="true">
		            </div>
		            <div class="ber_form_group ber_language">
		                <label for="berlanguage" class="ber_label"><?php echo esc_html_e('Language', 'bertha-ai'); ?><span class="ber_required">*</span><span class="ber-tooltip-element"  data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('Select language for prompt', 'bertha-ai'); ?>">?</span></label>
		                <select class="ber_field" name="berlanguage" access="false" id="berlanguage" title="<?php echo esc_html_e('Select language for prompt', 'bertha-ai'); ?>" required="required" aria-required="true">
		                	<option value="en"><?php echo esc_html_e('English', 'bertha-ai'); ?></option>
		                	<option value="fr"><?php echo esc_html_e('French', 'bertha-ai'); ?></option>
		                	<option value="de"><?php echo esc_html_e('German', 'bertha-ai'); ?></option>
		                	<option value="nl"><?php echo esc_html_e('Dutch', 'bertha-ai'); ?></option>
		                	<option value="es"><?php echo esc_html_e('Spanish', 'bertha-ai'); ?></option>
		                	<option value="iw"><?php echo esc_html_e('Hebrew', 'bertha-ai'); ?></option>
		                	<option value="it"><?php echo esc_html_e('Italian', 'bertha-ai'); ?></option>
		                	<option value="pt-PT"><?php echo esc_html_e('Portuguese', 'bertha-ai'); ?></option>
		                	<option value="bg"><?php echo esc_html_e('Bulgarian', 'bertha-ai'); ?></option>
		                	<option value="hr"><?php echo esc_html_e('Croatian', 'bertha-ai'); ?></option>
		                	<option value="cs"><?php echo esc_html_e('Czech', 'bertha-ai'); ?></option>
		                	<option value="da"><?php echo esc_html_e('Danish', 'bertha-ai'); ?></option>
		                	<option value="et"><?php echo esc_html_e('Estonian', 'bertha-ai'); ?></option>
		                	<option value="fi"><?php echo esc_html_e('Finnish', 'bertha-ai'); ?></option>
		                	<option value="el"><?php echo esc_html_e('Greek', 'bertha-ai'); ?></option>
		                	<option value="hu"><?php echo esc_html_e('Hungarian', 'bertha-ai'); ?></option>
		                	<option value="ga"><?php echo esc_html_e('Irish', 'bertha-ai'); ?></option>
		                	<option value="lv"><?php echo esc_html_e('Latvian', 'bertha-ai'); ?></option>
		                	<option value="lt"><?php echo esc_html_e('Lithuanian', 'bertha-ai'); ?></option>
		                	<option value="mt"><?php echo esc_html_e('Maltese', 'bertha-ai'); ?></option>
		                	<option value="pl"><?php echo esc_html_e('Polish', 'bertha-ai'); ?></option>
		                	<option value="ro"><?php echo esc_html_e('Romanian', 'bertha-ai'); ?></option>
		                	<option value="sk"><?php echo esc_html_e('Slovak', 'bertha-ai'); ?></option>
		                	<option value="sl"><?php echo esc_html_e('Slovenian', 'bertha-ai'); ?></option>
		                	<option value="sv"><?php echo esc_html_e('Swedish', 'bertha-ai'); ?></option>
		                	<option value="ja"><?php echo esc_html_e('Japanese', 'bertha-ai'); ?></option>
		                	<option value="no"><?php echo esc_html_e('Norwegian', 'bertha-ai'); ?></option>
		                </select>
		            </div>
		            <div class="ber_form_group ber_savechanges">
		                <button type="submit" class="ber_button bersavechanges" name="bersavechanges" access="false" id="ber_page3_save"><?php echo esc_html_e('Save Changes', 'bertha-ai'); ?></button>
		            </div>
		        </div>
		    </div>
		    <div id="ber_page4" class="ber_wizard_license ber_wizard_page" style="display:none;">
		        <div class="ber_title"><?php echo esc_html_e('Final Step, Let\'s Activate Your License', 'bertha-ai'); ?></div>
		        <p class="ber_p_desc"><?php echo esc_html_e('Click the button below to confirm your license and connect to Bertha\'s AI engine.', 'bertha-ai'); ?></p>
				<img src="<?php echo plugin_dir_url( $this->file ); ?>assets/images/Bertha_working.svg" alt="Bertha Working" class="bertha_working" />
		        <div class="ber_form">
		            <div class="ber_form_group ber_savechanges">
		                <a href="<?php echo esc_attr($home_url); ?>"><button type="button" class="ber_button bersavechanges" name="bersavechanges" access="false" id="ber_page4_save"><?php echo esc_html_e('Activate', 'bertha-ai'); ?></button></a>
		            </div>
		        </div>
			</div>
		</div>
		<?php
	}

	function bthai_onboard_free_dashboard_callback() {
		global $current_user;
	    $home_url = 'https://bertha.ai/activate?activation_callback='.Base64_encode(get_admin_url()).'&activation_item='.Base64_encode(BTHAI_ITEM_NAME).'&activation_item_id='.Base64_encode(BTHAI_ITEM_ID).'&activation_price_id='.Base64_encode(BTHAI_LICENSE_PRICE_ID).'&activation=true';
		?>
		<div class="ber_wizard_wrap">
		    <div id="ber_page1" class="ber_wizard_page">
		        <div class="ber_title"><?php printf( esc_html__('Hey, %s!', 'bertha-ai'), $current_user->display_name); ?><br><?php echo esc_html_e("Let's get you on board!", "bertha-ai"); ?> 👋</div>
		        <p class="ber_p_desc"><?php echo esc_html_e('Bertha is excited to start and to become your helper in generating quality content for your WordPress website.', 'bertha-ai'); ?></p>
		        <div class="ber_form">
		            <div class="ber_form_group ber_brand">
		                <label for="berbrand" class="ber_label"><?php echo esc_html_e('First Name', 'bertha-ai'); ?><span class="ber_required">*</span></label>
		                <input type="text" class="ber_field" name="ber_free_name" access="false" id="ber_free_name" title="<?php echo esc_html_e('Your company or brand name', 'bertha-ai'); ?>" required="required" aria-required="true">
		            </div>
		            <div class="ber_form_group ber_brand">
		                <label for="berbrand" class="ber_label"><?php echo esc_html_e('Email', 'bertha-ai'); ?><span class="ber_required">*</span></label>
		                <input type="text" class="ber_field" name="ber_free_email" access="false" id="ber_free_email" title="<?php echo esc_html_e('Your company or brand name', 'bertha-ai'); ?>" required="required" aria-required="true">
		            </div>
		            <div class="ber_form_group ber_brand">
		            	<p for="ber_already_account" class="ber_label"><a href="<?php echo esc_url(admin_url( 'index.php?page=wa-onboard-dashboard' )); ?>"><?php echo esc_html_e('Already have an account', 'bertha-ai'); ?></a><span class="ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html_e('If you already have an account, please log in using your credentials.', 'bertha-ai'); ?>">?</span></p>
		            </div>
		        </div>
		        <div class="ber_form">
		            <div class="ber_form_group ber_savechanges">
		            	<?php wp_nonce_field( 'bertha_ber_create_form', 'bertha_ber_create_nonce' ); ?>
		                <button type="button" class="ber_button bersavechanges" name="ber_create_user" access="false" id="ber-create-user"><?php echo esc_html_e('Next', 'bertha-ai'); ?></button>
		            </div>
		        </div>
		    </div>
		    <div id="ber_page2" class="ber_wizard_page" style="display:none;">
		        <div class="ber_title"><?php echo esc_html_e('Help me get to know you better', 'bertha-ai'); ?></div>
		        <p class="ber_p_desc"><?php echo esc_html_e('I have just a few questions for you that will calibrate my engines and help me improve our collaborative experience.', 'bertha-ai'); ?><br><?php echo esc_html_e('This should take about 2 minutes.', 'bertha-ai'); ?></p>
		        <div class="ber_form">
		            <div class="ber_form_group field_ber_where">
		                <label for="ber_where" class="ber_label">
		                    <div><?php echo esc_html_e("I'm installing Bertha on the website of...", "bertha-ai"); ?></div><span class="ber_required">*</span></label>
		                <div class="ber_radio_group">
		                    <div class="ber_radio">
		                        <input class="ber_field ber_where ber_wizzard_main" name="ber_where" access="false" id="ber_where-0" required="required" aria-required="true" value="mine" type="radio">
		                        <label for="ber_where-0"><?php echo esc_html_e("My business / Myself", "bertha-ai"); ?></label>
		                    </div>
		                    <div class="ber_radio">
		                        <input class="ber_field ber_where ber_wizzard_main" name="ber_where" access="false" id="ber_where-1" required="required" aria-required="true" value="client" type="radio">
		                        <label for="ber_where-1"><?php echo esc_html_e('A client', 'bertha-ai'); ?></label>
		                    </div>
		                    <div class="ber_radio">
		                        <input class="ber_field ber_where ber_wizzard_main" name="ber_where" access="false" id="ber_where-2" required="required" aria-required="true" value="my-work" type="radio">
		                        <label for="ber_where-2"><?php echo esc_html_e('A company I work for', 'bertha-ai'); ?></label>
		                    </div>
		                    <div class="ber_radio">
		                        <input class="ber_field ber_where ber_wizzard_main" name="ber_where" access="false" id="ber_where-3" required="required" aria-required="true" value="someone-else" type="radio">
		                        <label for="ber_where-3"><?php echo esc_html_e('For someone else', 'bertha-ai'); ?></label>
		                    </div>
		                </div>
		            </div>
		            <div class="ber_form_group field_ber_what" style="display:none;">
		                <label for="ber_what" class="ber_label">
		                    <div><?php echo esc_html_e('The website is a...', 'bertha-ai'); ?></div><span class="ber_required">*</span></label>
		                <div class="ber_radio_group">
		                <div class="ber_radio">
		                    <input class="ber_field ber_what ber_wizzard_main" name="ber_what" access="false" id="ber_what-0" required="required" aria-required="true" value="business" type="radio">
		                    <label for="ber_what-0"><?php echo esc_html_e('Business website', 'bertha-ai'); ?></label>
		                </div>
		                <div class="ber_radio">
		                    <input class="ber_field ber_what ber_wizzard_main" name="ber_what" access="false" id="ber_what-1" required="required" aria-required="true" value="blog" type="radio">
		                    <label for="ber_what-1"><?php echo esc_html_e('Content website (blog/articles)', 'bertha-ai'); ?></label>
		                </div>
		                <div class="ber_radio">
		                        <input class="ber_field ber_what ber_wizzard_main" name="ber_what" access="false" id="ber_what-2" required="required" aria-required="true" value="shop" type="radio">
		                        <label for="ber_what-2"><?php echo esc_html_e('Ecommerce platform (WooCommerce)', 'bertha-ai'); ?></label>
		                    </div>
		                    <div class="ber_radio">
		                        <input class="ber_field ber_what ber_wizzard_main" name="ber_what" access="false" id="ber_what-3" required="required" asria-required="true" value="soemething_else" type="radio">
		                        <label for="ber_what-3"><?php echo esc_html_e('Something else', 'bertha-ai'); ?></label>
		                    </div>
		                    <input type="hidden" class="bertha-free-user" value=''>
		                </div>
		            </div>
		            <div class="ber_form_group ber_what_savechanges" style="display:none;">
		            	<?php wp_nonce_field( 'bertha_ber_free_create_form', 'bertha_ber_free_create_nonce' ); ?>
		                <button type="submit" class="ber_button bersavechanges" name="bersavechanges" access="false" id="ber_page2_save"><?php echo esc_html_e('Next', 'bertha-ai'); ?></button>
		            </div>
		        </div>
		    </div>
		</div>
		<?php
	}

	function bthai_product_action_title($actions) {
		$actions['bthai-product-description'] = __('Product Description', 'bertha-ai');
		return $actions;
	}

	function bthai_download_action_title($actions) {
		$actions['bthai-download-description'] = __('Download Description', 'bertha-ai');
		return $actions;
	}

	function bthai_handle_product_action($redirect_url, $action, $post_ids) {
		if ($action == 'bthai-product-description') {
			$redirect_url = $this->bthai_handle_postype_action($redirect_url, $post_ids, 'product');
		}
		return $redirect_url;
	}

	function bthai_handle_download_action($redirect_url, $action, $post_ids) {
		if ($action == 'bthai-download-description') {
			$redirect_url = $this->bthai_handle_postype_action($redirect_url, $post_ids, 'download');
		}
		return $redirect_url;
	}

	function bthai_handle_postype_action($redirect_url, $post_ids, $action) {
		$idea_unique_id = md5(uniqid());
		$options = get_option('bertha_ai_options') ? (array) get_option('bertha_ai_options') : array();
        $language = isset($options['language']) ? $options['language'] : '';
        $count = count($post_ids);
        if($count <= 5) {
        	$titles = array();
        	foreach ($post_ids as $key => $post_id) {
        		$titles[$key]['id'] = $post_id;
				$titles[$key]['title'] = get_the_title($post_id);

			}
        	$url = 'https://bertha.ai/wp-json/generate/description';
            $args = array(
                    'method' => 'POST',
                    'body'   => json_encode( array( 'language' => $language, 'strict_mode' => 0, 'titles' => $titles, 'template' => 'quickwins', 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url(), 'current_user' => '', 'idea_unique_id' => $idea_unique_id, 'berideas' => 1 ) ),
                    'headers' => [
                                    'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                    'Content-Type' =>  'application/json',
                                ],
            );
            $response = wp_remote_post($url, $args); 
            if (!is_wp_error($response) && isset($response['body'])) {
                $datas = json_decode($response['body']);
            	if(!empty($datas)) {
            		foreach($datas as $data) {
            			if($data->id && $data->description) {
	            			$my_post = array('ID' => $data->id,'post_content' => $data->description, 'post_excerpt' => $data->description);
				 			wp_update_post( $my_post );
				 		}
            		}
            	}
            }
		} else {
			$redirect_url = add_query_arg('bertha_'.$action.'_limit_exceeded', true, $redirect_url);
		}
		return $redirect_url;
	}

	function bthai_product_column_header($columns) {
		$columns['bthai-product-header'] = __('Description', 'bertha-ai');
		return $columns;
	}

	function bthai_download_column_header($columns) {
		$columns['bthai-download-header'] = __('Description', 'bertha-ai');
		return $columns;
	}

	function bthai_posttype_column_content($column_key, $post_id) {
		if($column_key == 'bthai-product-header' || $column_key	== 'bthai-download-header') {
			$post = get_post($post_id);
			if($post->post_content) {
				$more_tags = (strlen($post->post_content) > 200) ? substr($post->post_content, 0, 200).' <a href="'.get_admin_url().'post.php?post='.$post_id.'&action=edit">[See More]</a>' : $post->post_content;
				echo $more_tags.' <a href="#"><span data-id="'.$post_id.'" class="ber-edit-descrition">Edit</span></a>';
			} else {
				echo ' ';
			}
		}
	}

	function bthai_display_notices() {
		if( (isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'product') || (isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'download') ) {
			printf('<div id="message" class="updated notice is-dismissable"><p><a href="#" class="bertha-template-description-video" data-id="t0ojo6Wjplo">'. __('Use Bertha to write your product descriptions.', 'bertha-ai') . '</a></p></div>');
		}
		if (isset($_REQUEST['bertha_product_limit_exceeded'])) {
			printf('<div id="message" class="error notice is-dismissable"><p>' . __('You can not select more than 5 products at a time.', 'bertha-ai') . '</p></div>');
		}
		if (isset($_REQUEST['bertha_download_limit_exceeded'])) {
			printf('<div id="message" class="error notice is-dismissable"><p>' . __('You can not select more than 5 downloads at a time.', 'bertha-ai') . '</p></div>');
		}
		if (isset($_REQUEST['bertha_token_limit_exceeded'])) {
			printf('<div id="message" class="error notice is-dismissable"><p>' . __('You have exceeded you limit.', 'bertha-ai') . '</p></div>');
		}
	}

	function bthai_revise_timeout( $time ) {
		return 60;
	}

}