<?php

class WA_Bertha_AI_Ajax {

    private $plugin_url;
    public $plugin_path;
    public $license_key;
    public $price_id;
    public $dashboard_url;
    public $item_id;
    public $strict_mode;
    public $license_details;

    public function __construct($file) {

        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->license_key = BTHAI_LICENSE_KEY;
        $this->price_id = BTHAI_LICENSE_PRICE_ID;
        $this->dashboard_url = BTHAI_STORE_URL;
        $this->item_id = BTHAI_ITEM_ID;
        $this->strict_mode = 0;
        $this->license_details = bthai_get_license_details('all');

        add_action('wp_ajax_bthai_generate_ideas', array(&$this, 'bthai_action_callback'));
        add_action('wp_ajax_nopriv_bthai_generate_ideas', array(&$this, 'bthai_action_callback'));
        add_action('wp_ajax_wa_ber_generate_playground', array(&$this, 'bthai_generate_playground_ai_action_callback'));
        add_action('wp_ajax_nopriv_wa_ber_generate_playground', array(&$this, 'bthai_generate_playground_ai_action_callback'));
        add_action('wp_ajax_wa_ber_generate_chat', array(&$this, 'bthai_generate_chat_ai_action_callback'));
        add_action('wp_ajax_nopriv_wa_ber_generate_chat', array(&$this, 'bthai_generate_chat_ai_action_callback'));
        add_action('wp_ajax_wa_ber_art_search', array(&$this, 'bthai_art_search_ai_action_callback'));
        add_action('wp_ajax_wa_ask_favourite_added', array(&$this, 'bthai_ask_favourite_added_callback'));
        add_action('wp_ajax_nopriv_wa_ask_favourite_added', array(&$this, 'bthai_ask_favourite_added_callback'));
        add_action('wp_ajax_long_form_ai_action', array(&$this, 'bthai_long_form_ai_action_callback'));
        add_action('wp_ajax_long_form_save_draft_ai_action', array(&$this, 'bthai_long_form_save_draft_ai_action_callback'));
        add_action('wp_ajax_long_form_edit_draft_ai_action', array(&$this, 'bthai_long_form_edit_draft_ai_action_callback'));
        add_action('wp_ajax_wa_ai_templates', array(&$this, 'bthai_wa_ai_templates_callback'));
        add_action('wp_ajax_nopriv_wa_ai_templates', array(&$this, 'bthai_wa_ai_templates_callback'));
        add_action('wp_ajax_set_wizzard_data', array(&$this, 'bthai_set_wizzard_data_callback'));
        add_action('wp_ajax_set_wizzard_setting_data', array(&$this, 'bthai_set_wizzard_setting_data_callback'));
        add_action('wp_ajax_wa_history_filter', array(&$this, 'bthai_history_filter_callback'));
        add_action('wp_ajax_nopriv_wa_history_filter', array(&$this, 'bthai_history_filter_callback'));
        add_action('wp_ajax_wa_bertha_load_more', array(&$this, 'bthai_wa_bertha_load_more_callback'));
        add_action('wp_ajax_nopriv_wa_bertha_load_more', array(&$this, 'bthai_wa_bertha_load_more_callback'));
        add_action('wp_ajax_wa_favourite_added', array(&$this, 'bthai_wa_favourite_added_callback'));
        add_action('wp_ajax_nopriv_wa_favourite_added', array(&$this, 'bthai_wa_favourite_added_callback'));
        add_action('wp_ajax_wa_idea_trash', array(&$this, 'bthai_wa_idea_trash_callback'));
        add_action('wp_ajax_nopriv_wa_idea_trash', array(&$this, 'bthai_wa_idea_trash_callback'));
        add_action('wp_ajax_wa_idea_report', array(&$this, 'bthai_wa_idea_report_callback'));
        add_action('wp_ajax_nopriv_wa_idea_report', array(&$this, 'bthai_wa_idea_report_callback'));
        add_action('wp_ajax_wa_bertha_load_history', array(&$this, 'bthai_wa_bertha_load_history_callback'));
        add_action('wp_ajax_nopriv_wa_bertha_load_history', array(&$this, 'bthai_wa_bertha_load_history_callback'));
        add_action('wp_ajax_wa_bertha_load_favourite', array(&$this, 'bthai_wa_bertha_load_favourite_callback'));
        add_action('wp_ajax_nopriv_wa_bertha_load_favourite', array(&$this, 'bthai_wa_bertha_load_favourite_callback'));
        add_action('wp_ajax_wa_bertha_load_draft', array(&$this, 'bthai_wa_bertha_load_draft_callback'));
        add_action('wp_ajax_nopriv_wa_bertha_load_draft', array(&$this, 'bthai_wa_bertha_load_draft_callback'));
        add_action('wp_ajax_wa_bertha_clear_transient', array(&$this, 'bthai_wa_bertha_clear_transient_callback'));
        add_action('wp_ajax_wa_bertha_get_art_view', array(&$this, 'bthai_wa_bertha_get_art_view_callback'));
        add_action('wp_ajax_nopriv_wa_bertha_get_art_view', array(&$this, 'bthai_wa_bertha_get_art_view_callback'));
        add_action('wp_ajax_wa_generate_image', array(&$this, 'bthai_wa_generate_image_callback'));
        add_action('wp_ajax_nopriv_wa_generate_image', array(&$this, 'bthai_wa_generate_image_callback'));
        add_action('wp_ajax_wa_save_media', array(&$this, 'bthai_wa_save_media_callback'));
        add_action('wp_ajax_nopriv_wa_save_media', array(&$this, 'bthai_wa_save_media_callback'));
        add_action('wp_ajax_wa_insert_featured', array(&$this, 'bthai_wa_insert_featured_callback'));
        add_action('wp_ajax_nopriv_wa_insert_featured', array(&$this, 'bthai_wa_insert_featured_callback'));
        add_action('wp_ajax_wa_ber_improve_img_prompt', array(&$this, 'bthai_wa_ber_improve_img_prompt_callback'));
        add_action('wp_ajax_nopriv_wa_ber_improve_img_prompt', array(&$this, 'bthai_wa_ber_improve_img_prompt_callback'));
        add_action('wp_ajax_wa_resize_media', array(&$this, 'bthai_wa_resize_media_callback'));
        add_action('wp_ajax_nopriv_wa_resize_media', array(&$this, 'bthai_wa_resize_media_callback'));
        add_action('wp_ajax_wa_bertha_edit_description', array(&$this, 'bthai_wa_edit_description_callback'));
        add_action('wp_ajax_wa_bertha_update_description', array(&$this, 'bthai_wa_update_description_callback'));
        add_action('wp_ajax_wa_ber_translate_content', array(&$this, 'bthai_translate_content_callback'));
        add_action('wp_ajax_nopriv_wa_ber_translate_content', array(&$this, 'bthai_translate_content_callback'));
        /* free */
        add_action('wp_ajax_wa_ber_free_create_purchase', array(&$this, 'bthai_free_create_purchase_callback'));
        add_action('wp_ajax_wa_ber_free_create_purchase_submit', array(&$this, 'bthai_free_create_purchase_submit_callback'));
    }

    function bthai_action_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_template_ideas_nonce' );
        
        $user_email = $this->bthai_get_customer_email();
        $idea_unique_id = md5(uniqid());
        $result_array = array();
        $block = isset($_POST['bertha_block']) ? sanitize_text_field($_POST['bertha_block']) : "";
        $options = get_option('bertha_ai_options') ? (array) get_option('bertha_ai_options') : array();
        $options1 = get_option('bertha_ai_license_options') ? (array) get_option('bertha_ai_license_options') : array();
        $language = isset($options['language']) ? $options['language'] : '';
        $berideas = isset($options1['berideas']) ? $options1['berideas'] : 4;

        switch($block) {
            case "USP":
                $idea_tax = get_term_by('slug', 'idea-usp', 'idea_template');
                $wp_ideas_option_term = 'wa_usp_ideas';
                $template_name = 'Unique Selling Proposition';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_ideal_cust = isset($_POST['bertha_ideal_cust']) ? sanitize_text_field($_POST['bertha_ideal_cust']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_ideal_cust']) && $_POST['data_ideal_cust']) $bertha_ideal_cust = sanitize_text_field($_POST['data_ideal_cust']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('brand' => $bertha_brand, 'cust' => $bertha_ideal_cust, 'desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'template' => 'USP');
                $generate_idea_html = 'data-brand="'.$bertha_brand.'" data-customer="'.$bertha_ideal_cust.'" data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'"';
                break;
            case "Paragraph":
                $idea_tax = get_term_by('slug', 'idea-paragraph', 'idea_template');
                $wp_ideas_option_term = 'wa_paragraph_ideas';
                $template_name = 'Paragraph';

                $bertha_ideal_cust = isset($_POST['bertha_ideal_cust']) ? sanitize_text_field($_POST['bertha_ideal_cust']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $para_title = isset($_POST['para_title']) ? sanitize_text_field($_POST['para_title']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_ideal_cust']) && $_POST['data_ideal_cust']) $bertha_ideal_cust = sanitize_text_field( $_POST['data_ideal_cust']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_title']) && $_POST['data_title']) $para_title = sanitize_text_field($_POST['data_title']);
                $body_arg = array('cust' => $bertha_ideal_cust, 'desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'title' =>  $para_title, 'template' => 'Paragraph');
                $generate_idea_html = 'data-customer="'.$bertha_ideal_cust.'" data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'" data-title="'.$para_title.'"';
                break;
            case "Title":
                $idea_tax = get_term_by('slug', 'section-title', 'idea_template');
                $wp_ideas_option_term = 'wa_section_title_ideas';
                $template_name = 'Section Title';

                $bertha_ideal_cust = isset($_POST['bertha_ideal_cust']) ? sanitize_text_field($_POST['bertha_ideal_cust']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $sec_title_type = isset($_POST['sec_title_type']) ? sanitize_text_field($_POST['sec_title_type']) : "";
                $sec_other_title = isset($_POST['sec_other_title']) ? sanitize_text_field($_POST['sec_other_title']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_ideal_cust']) && $_POST['data_ideal_cust']) $bertha_ideal_cust = sanitize_text_field($_POST['data_ideal_cust']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_title_type']) && $_POST['data_title_type']) $sec_title_type = sanitize_text_field($_POST['data_title_type']);
                if(isset($_POST['data_other_title']) && $_POST['data_other_title']) $sec_other_title = sanitize_text_field($_POST['data_other_title']);
                $body_arg = array('cust' => $bertha_ideal_cust, 'desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'title_type' =>  $sec_title_type, 'other_title' =>  $sec_other_title, 'template' => 'Title');
                $generate_idea_html = 'data-customer="'.$bertha_ideal_cust.'" data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'" data-title-type="'.$sec_title_type.'" data-other-title="'.$sec_other_title.'"';
                break;
            case "Headline":
                $idea_tax = get_term_by('slug', 'sub-headline', 'idea_template');
                $wp_ideas_option_term = 'wa_sub_headline_ideas';
                $template_name = 'website sub headline';

                $bertha_ideal_cust = isset($_POST['bertha_ideal_cust']) ? sanitize_text_field($_POST['bertha_ideal_cust']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $sub_headline_usp = isset($_POST['sub_headline_usp']) ? sanitize_text_field($_POST['sub_headline_usp']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_ideal_cust']) && $_POST['data_ideal_cust']) $bertha_ideal_cust = sanitize_text_field($_POST['data_ideal_cust']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_headline_usp']) && $_POST['data_headline_usp']) $sub_headline_usp = sanitize_text_field($_POST['data_headline_usp']);
                $body_arg = array('cust' => $bertha_ideal_cust, 'desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'usp' =>  $sub_headline_usp, 'template' => 'Headline');
                $generate_idea_html = 'data-customer="'.$bertha_ideal_cust.'" data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'" data-headline-usp="'.$sub_headline_usp.'"';
                break;
            case "Service":
                $idea_tax = get_term_by('slug', 'product-service-description', 'idea_template');
                $wp_ideas_option_term = 'wa_service_description_ideas';
                $template_name = 'Product Service Description';

                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $service_description_name = isset($_POST['service_description_name']) ? sanitize_textarea_field($_POST['service_description_name']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_desc_name']) && $_POST['data_desc_name']) $service_description_name = sanitize_textarea_field($_POST['data_desc_name']);
                $body_arg = array('desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'service_desc' =>  $service_description_name, 'template' => 'Service');
                $generate_idea_html = 'data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'" data-desc-name="'.$service_description_name.'"';
                break;
            case "Company":
                $idea_tax = get_term_by('slug', 'company-bio', 'idea_template');
                $wp_ideas_option_term = 'wa_company_bio_ideas';
                $template_name = 'Company Bio';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('brand' => $bertha_brand, 'desc' => $bertha_desc, 'template' => 'Company');
                $generate_idea_html = 'data-brand="'.$data_brand.'" data-desc="'.$bertha_desc.'"';
                break;
            case "Company-mission":
                $idea_tax = get_term_by('slug', 'Company-mission', 'idea_template');
                $wp_ideas_option_term = 'wa_company_mission_ideas';
                $template_name = 'Company Mission & Vision';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('brand' => $bertha_brand, 'desc' => $bertha_desc, 'template' => 'Company-mission');
                $generate_idea_html = 'data-brand="'.$data_brand.'" data-desc="'.$bertha_desc.'"';
                break;
            case "Benefit-List":
                $idea_tax = get_term_by('slug', 'idea-benefit', 'idea_template');
                $wp_ideas_option_term = 'wa_benefits_ideas';
                $template_name = 'Benefit Lists';

                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                if($_POST['data_index_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_index_desc']);
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                $body_arg = array('desc' => $bertha_desc, 'template' => 'Benefit-List');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'"';
                break;
            case "Content-Improver":
                $idea_tax = get_term_by('slug', 'content-improver', 'idea_template');
                $wp_ideas_option_term = 'wa_content_improver_ideas';
                $template_name = 'Content Improver';

                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('desc' => $bertha_desc, 'sentiment' => $bertha_sentiment,'template' => 'Content-Improver');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'" data-sentiment="'.$bertha_sentiment.'"';
                break;
            case "Benefit-Title":
                $idea_tax = get_term_by('slug', 'benefit-title', 'idea_template');
                $wp_ideas_option_term = 'wa_benefit_title_ideas';
                $template_name = 'Benefit Title';

                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $Benefit_title = isset($_POST['Benefit_title']) ? sanitize_text_field($_POST['Benefit_title']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_title']) && $_POST['data_title']) $Benefit_title = sanitize_text_field($_POST['data_title']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('desc' => $bertha_desc, 'title' => $Benefit_title, 'template' => 'Benefit-Title');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'" data-title="'.$Benefit_title.'"';
                break;
            case "bullet-points":
                $idea_tax = get_term_by('slug', 'bullet-points', 'idea_template');
                $wp_ideas_option_term = 'wa_bullet_ideas';
                $template_name = 'Persuasive Bullet Points';

                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                if($_POST['data_index_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_index_desc']);
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                $body_arg = array('desc' => $bertha_desc, 'template' => 'bullet-points');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'"';
                break;
            case "personal-bio":
                $idea_tax = get_term_by('slug', 'personal-bio', 'idea_template');
                $wp_ideas_option_term = 'wa_personal_bio_point_ideas';
                $template_name = 'Personal Bio';

                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $personal_bio_point = isset($_POST['personal_bio_point']) ? sanitize_text_field($_POST['personal_bio_point']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_point']) && $_POST['data_point']) $personal_bio_point = sanitize_text_field($_POST['data_point']);
                $body_arg = array('desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'personal_bio_point' =>  $personal_bio_point, 'template' => 'personal-bio');
                $generate_idea_html = 'data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'" data-point="'.$personal_bio_point.'"';
                break;
            case "blog-post-idea":
                $idea_tax = get_term_by('slug', 'blog-post-idea', 'idea_template');
                $wp_ideas_option_term = 'wa_blog_ideas';
                $template_name = 'Blog Post Topic Ideas';

                $bertha_ideal_cust = isset($_POST['bertha_ideal_cust']) ? sanitize_text_field($_POST['bertha_ideal_cust']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_ideal_cust']) && $_POST['data_ideal_cust']) $bertha_ideal_cust = sanitize_textarea_field($_POST['data_ideal_cust']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('cust' => $bertha_ideal_cust, 'desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'template' => 'blog-post-idea');
                $generate_idea_html = 'data-customer="'.$bertha_ideal_cust.'" data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'"';
                break;
            case "blog-post-intro-paragraph":
                $idea_tax = get_term_by('slug', 'intro-para-idea', 'idea_template');
                $wp_ideas_option_term = 'intro_para_ideas';
                $template_name = 'Blog Post Intro Paragraph';

                $bertha_ideal_cust = isset($_POST['bertha_ideal_cust']) ? sanitize_text_field($_POST['bertha_ideal_cust']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $intro_title = isset($_POST['intro_title']) ? sanitize_text_field($_POST['intro_title']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_ideal_cust']) && $_POST['data_ideal_cust']) $bertha_ideal_cust = sanitize_text_field($_POST['data_ideal_cust']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_title']) && $_POST['data_title']) $intro_title = sanitize_text_field($_POST['data_title']);
                $body_arg = array('cust' => $bertha_ideal_cust, 'title' => $intro_title, 'sentiment' => $bertha_sentiment, 'template' => 'blog-post-intro-paragraph');
                $generate_idea_html = 'data-customer="'.$bertha_ideal_cust.'" data-sentiment="'.$bertha_sentiment.'" data-title="'.$intro_title.'"';
                break;
            case "blog-post-outline":
                $idea_tax = get_term_by('slug', 'post-outline-idea', 'idea_template');
                $wp_ideas_option_term = 'post_outline_ideas';
                $template_name = 'Blog Post Outline';

                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_title = isset($_POST['bertha_title']) ? sanitize_text_field($_POST['bertha_title']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_title']) && $_POST['data_title']) $bertha_title = sanitize_text_field($_POST['data_title']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                $body_arg = array('sentiment' => $bertha_sentiment, 'title' => $bertha_title, 'template' => 'blog-post-outline');
                $generate_idea_html = 'data-sentiment="'.$bertha_sentiment.'" data-title="'.$bertha_title.'"';
                break;
            case "blog-post-conclusion":
                $idea_tax = get_term_by('slug', 'conclusion-para-idea', 'idea_template');
                $wp_ideas_option_term = 'conclusion_para_ideas';
                $template_name = 'Blog Post Conclusion Paragraph';

                $bertha_cta = isset($_POST['bertha_cta']) ? sanitize_text_field($_POST['bertha_cta']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_title = isset($_POST['bertha_title']) ? sanitize_text_field($_POST['bertha_title']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_cta']) && $_POST['data_cta']) $bertha_cta = sanitize_text_field($_POST['data_cta']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_title']) && $_POST['data_title']) $bertha_title = sanitize_text_field($_POST['data_title']);
                $body_arg = array('cta' => $bertha_cta, 'title' => $bertha_title, 'sentiment' => $bertha_sentiment, 'template' => 'blog-post-conclusion');
                $generate_idea_html = 'data-cta="'.$bertha_cta.'" data-sentiment="'.$bertha_sentiment.'" data-title="'.$bertha_title.'"';
                break;
            case "blog-action":
                $idea_tax = get_term_by('slug', 'blog-action-idea', 'idea_template');
                $wp_ideas_option_term = 'blog_action_ideas';
                $template_name = 'Button Call to Action';

                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_action = isset($_POST['bertha_action']) ? sanitize_text_field($_POST['bertha_action']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_action']) && $_POST['data_action']) $bertha_action = sanitize_text_field($_POST['data_action']);
                $body_arg = array('desc' => $bertha_desc, 'action' => $bertha_action, 'template' => 'blog-action');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'" data-action="'.$bertha_action.'"';
                break;
            case "child-explain":
                $idea_tax = get_term_by('slug', 'child-input', 'idea_template');
                $wp_ideas_option_term = 'child_input_ideas';
                $template_name = 'Explain It To a Child';

                $child_input = isset($_POST['child_input']) ? sanitize_text_field($_POST['child_input']) : "";
                if($_POST['data_input']) $child_input = sanitize_text_field($_POST['data_input']);
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                $body_arg = array('input' => $child_input, 'template' => 'child-explain');
                $generate_idea_html = 'data-input="'.$child_input.'"';
                break;
            case "seo-title":
                $idea_tax = get_term_by('slug', 'bertha-seo-title', 'idea_template');
                $wp_ideas_option_term = 'seo_title_ideas';
                $template_name = 'Seo Title';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_keyword = isset($_POST['bertha_keyword']) ? sanitize_text_field($_POST['bertha_keyword']) : "";
                $bertha_title = isset($_POST['bertha_title']) ? sanitize_text_field($_POST['bertha_title']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_title']) && $_POST['data_title']) $bertha_title = sanitize_text_field($_POST['data_title']);
                if(isset($_POST['data_keyword']) && $_POST['data_keyword']) $bertha_keyword = sanitize_text_field($_POST['data_keyword']);
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                $body_arg = array('brand' => $bertha_brand, 'keyword' => $bertha_keyword, 'title' => $bertha_title, 'template' => 'seo-title');
                $generate_idea_html = 'data-keyword="'.$bertha_keyword.'" data-title="'.$bertha_title.'" data-brand="'.$bertha_brand.'"';
                break;
            case "seo-description":
                $idea_tax = get_term_by('slug', 'bertha-seo-description', 'idea_template');
                $wp_ideas_option_term = 'seo_description_ideas';
                $template_name = 'Seo Description';

                $bertha_keyword = isset($_POST['bertha_keyword']) ? sanitize_text_field($_POST['bertha_keyword']) : "";
                $bertha_title = isset($_POST['bertha_title']) ? sanitize_text_field($_POST['bertha_title']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_title']) && $_POST['data_title']) $bertha_title = sanitize_text_field($_POST['data_title']);
                if(isset($_POST['data_keyword']) && $_POST['data_keyword']) $bertha_keyword = sanitize_text_field($_POST['data_keyword']);
                $body_arg = array('keyword' => $bertha_keyword, 'title' => $bertha_title, 'template' => 'seo-description');
                $generate_idea_html = 'data-keyword="'.$bertha_keyword.'" data-title="'.$bertha_title.'"';
                break;
            case "aida-marketing":
                $idea_tax = get_term_by('slug', 'bertha-aida-marketing', 'idea_template');
                $wp_ideas_option_term = 'wa_aida_marketing_ideas';
                $template_name = 'AIDA Marketing';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_ideal_cust = isset($_POST['bertha_ideal_cust']) ? sanitize_text_field($_POST['bertha_ideal_cust']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_ideal_cust']) && $_POST['data_ideal_cust']) $bertha_ideal_cust = sanitize_text_field($_POST['data_ideal_cust']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('brand' => $bertha_brand, 'cust' => $bertha_ideal_cust, 'desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'template' => 'aida-marketing');
                $generate_idea_html = 'data-brand="'.$bertha_brand.'" data-customer="'.$bertha_ideal_cust.'" data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'"';
                break;
            case "seo-city":
                $idea_tax = get_term_by('slug', 'bertha-seo-city', 'idea_template');
                $wp_ideas_option_term = 'wa_seo_city_ideas';
                $template_name = 'SEO City Based Pages';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_city = isset($_POST['bertha_city']) ? sanitize_text_field($_POST['bertha_city']) : "";
                $bertha_cta = isset($_POST['bertha_cta']) ? sanitize_text_field($_POST['bertha_cta']) : "";
                $bertha_keyword = isset($_POST['bertha_keyword']) ? sanitize_text_field($_POST['bertha_keyword']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_city']) && $_POST['data_city']) $bertha_city = sanitize_text_field($_POST['data_city']);
                if(isset($_POST['data_cta']) && $_POST['data_cta']) $bertha_cta = sanitize_text_field($_POST['data_cta']);
                if(isset($_POST['data_keyword']) && $_POST['data_keyword']) $bertha_keyword = sanitize_text_field($_POST['data_keyword']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('brand' => $bertha_brand, 'city' => $bertha_city, 'desc' => $bertha_desc, 'cta' => $bertha_cta, 'keyword' => $bertha_keyword, 'template' => 'seo-city');
                $generate_idea_html = 'data-brand="'.$bertha_brand.'" data-city="'.$bertha_city.'" data-cta="'.$bertha_cta.'" data-keyword="'.$bertha_keyword.'" data-desc="'.$bertha_desc.'"';
                break;
            case "buisiness-name":
                $idea_tax = get_term_by('slug', 'bertha-buisiness-name', 'idea_template');
                $wp_ideas_option_term = 'buisiness_name_ideas';
                $template_name = 'Business or Product Name';

                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                $body_arg = array('desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'template' => 'buisiness-name');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'" data-sentiment="'.$bertha_sentiment.'"';
                break;
            case "bridge":
                $idea_tax = get_term_by('slug', 'bertha-bridge', 'idea_template');
                $wp_ideas_option_term = 'wa_aida_marketing_ideas';
                $template_name = 'Before, After and Bridge';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_ideal_cust = isset($_POST['bertha_ideal_cust']) ? sanitize_text_field($_POST['bertha_ideal_cust']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_ideal_cust']) && $_POST['data_ideal_cust']) $bertha_ideal_cust = sanitize_text_field($_POST['data_ideal_cust']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('brand' => $bertha_brand, 'cust' => $bertha_ideal_cust, 'desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'template' => 'bridge');
                $generate_idea_html = 'data-brand="'.$bertha_brand.'" data-customer="'.$bertha_ideal_cust.'" data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'"';
                break;
            case "pas-framework":
                $idea_tax = get_term_by('slug', 'bertha-pas-framework', 'idea_template');
                $wp_ideas_option_term = 'wa_pas_framework_ideas';
                $template_name = 'PAS Framework';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_ideal_cust = isset($_POST['bertha_ideal_cust']) ? sanitize_text_field($_POST['bertha_ideal_cust']) : "";
                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_ideal_cust']) && $_POST['data_ideal_cust']) $bertha_ideal_cust = sanitize_text_field($_POST['data_ideal_cust']);
                if(isset($_POST['data_sentiment']) && $_POST['data_sentiment']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('brand' => $bertha_brand, 'cust' => $bertha_ideal_cust, 'desc' => $bertha_desc, 'sentiment' => $bertha_sentiment, 'template' => 'pas-framework');
                $generate_idea_html = 'data-brand="'.$bertha_brand.'" data-customer="'.$bertha_ideal_cust.'" data-sentiment="'.$bertha_sentiment.'" data-desc="'.$bertha_desc.'"';
                break;
            case "faq-list":
                $idea_tax = get_term_by('slug', 'bertha-faq-list', 'idea_template');
                $wp_ideas_option_term = 'faq_list_ideas';
                $template_name = 'FAQs List';

                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('desc' => $bertha_desc, 'template' => 'faq-list');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'"';
                break;
            case "faq-answer":
                $idea_tax = get_term_by('slug', 'bertha-faq-answer', 'idea_template');
                $wp_ideas_option_term = 'faq_answer_ideas';
                $template_name = 'FAQ Answers';

                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_question = isset($_POST['bertha_question']) ? sanitize_text_field($_POST['bertha_question']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_question']) && $_POST['data_question']) $bertha_question = sanitize_text_field($_POST['data_question']);
                $body_arg = array('desc' => $bertha_desc, 'question' => $bertha_question, 'template' => 'faq-answer');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'" data-question="'.$bertha_question.'"';
                break;
            case "summaries":
                $idea_tax = get_term_by('slug', 'bertha-summary', 'idea_template');
                $wp_ideas_option_term = 'summaries_ideas';
                $template_name = 'Summaries';

                $bertha_sentiment = isset($_POST['bertha_sentiment']) ? sanitize_text_field($_POST['bertha_sentiment']) : "";
                $bertha_summary = isset($_POST['bertha_summary']) ? sanitize_text_field($_POST['bertha_summary']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_sentiment = sanitize_text_field($_POST['data_sentiment']);
                if(isset($_POST['data_question']) && $_POST['data_question']) $bertha_summary = sanitize_text_field($_POST['data_summary']);
                $body_arg = array('sentiment' => $bertha_sentiment, 'summary' => $bertha_summary, 'template' => 'summaries');
                $generate_idea_html = 'data-summary="'.$bertha_summary.'" data-sentiment="'.$bertha_sentiment.'"';
                break;
            case "contact-blurb":
                $idea_tax = get_term_by('slug', 'bertha-contact-blurb', 'idea_template');
                $wp_ideas_option_term = 'contact_blurb_ideas';
                $template_name = 'Contact Form Blurb';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_cta = isset($_POST['bertha_cta']) ? sanitize_text_field($_POST['bertha_cta']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_cta']) && $_POST['data_cta']) $bertha_cta = sanitize_text_field($_POST['data_cta']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('brand' => $bertha_brand, 'cta' => $bertha_cta, 'desc' => $bertha_desc, 'template' => 'contact-blurb');
                $generate_idea_html = 'data-brand="'.$bertha_brand.'" data-cta="'.$bertha_cta.'" data-desc="'.$bertha_desc.'"';
                break;
            case "seo-keyword":
                $idea_tax = get_term_by('slug', 'bertha-seo-keyword', 'idea_template');
                $wp_ideas_option_term = 'seo_keyword_ideas';
                $template_name = 'SEO Keyword Suggestions';

                $bertha_desc = isset($_POST['bertha_desc']) ? $_POST['bertha_desc'] : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? $_POST['bertha_desc_index'] : 0;
                $data_block = isset($_POST['data_block']) ? $_POST['data_block'] : '';
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = $_POST['data_desc'];
                $body_arg = array('desc' => $bertha_desc, 'template' => 'seo-keyword');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'"';
                break;
            case "evil-bertha":
                $idea_tax = get_term_by('slug', 'bertha-evil-bertha', 'idea_template');
                $wp_ideas_option_term = 'evil_bertha_ideas';
                $template_name = 'Evil Bertha';

                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                $body_arg = array('desc' => $bertha_desc, 'template' => 'evil-bertha');
                $generate_idea_html = 'data-desc="'.$bertha_desc.'"';
                break;
            case "real-estate":
                $idea_tax = get_term_by('slug', 'bertha-real-eastate', 'idea_template');
                $wp_ideas_option_term = 'wa_real_estate_ideas';
                $template_name = 'Real Estate Property Listing Description';

                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_location = isset($_POST['bertha_location']) ? sanitize_text_field($_POST['bertha_location']) : "";
                $bertha_type = isset($_POST['bertha_type']) ? sanitize_textarea_field($_POST['bertha_type']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_text_field($_POST['bertha_desc']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_location']) && $_POST['data_location']) $bertha_location = sanitize_text_field($_POST['data_location']);
                if(isset($_POST['data_type']) && $_POST['data_type']) $bertha_type = sanitize_textarea_field($_POST['data_type']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_text_field($_POST['data_desc']);
                $body_arg = array('brand' => $bertha_brand, 'location' => $bertha_location, 'type' => $bertha_type, 'desc' => $bertha_desc, 'template' => 'real-estate');
                $generate_idea_html = 'data-brand="'.$bertha_brand.'" data-location="'.$bertha_location.'" data-type="'.$bertha_type.'" data-desc="'.$bertha_desc.'"';
                break;
            case "press-blurb":
                $idea_tax = get_term_by('slug', 'bertha-press-blurb', 'idea_template');
                $wp_ideas_option_term = 'wa_press_blurb_ideas';
                $template_name = 'Press Mention Blurb';

                $bertha_name = isset($_POST['bertha_name']) ? sanitize_text_field($_POST['bertha_name']) : "";
                $bertha_info = isset($_POST['bertha_info']) ? sanitize_text_field($_POST['bertha_info']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_keyword = isset($_POST['bertha_keyword']) ? sanitize_text_field($_POST['bertha_keyword']) : "";
                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_name']) && $_POST['data_name']) $bertha_name = sanitize_text_field($_POST['data_name']);
                if(isset($_POST['data_info']) && $_POST['data_info']) $bertha_info = sanitize_text_field($_POST['data_info']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_keyword']) && $_POST['data_keyword']) $bertha_keyword = sanitize_text_field($_POST['data_keyword']);
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                $body_arg = array('name' => $bertha_name, 'info' => $bertha_info, 'desc' => $bertha_desc, 'keyword' => $bertha_keyword, 'brand' => $bertha_brand, 'template' => 'press-blurb');
                $generate_idea_html = 'data-name="'.$bertha_name.'" data-info="'.$bertha_info.'" data-desc="'.$bertha_desc.'" data-keyword="'.$bertha_keyword.'" data-brand="'.$bertha_brand.'"';
                break;
            case "case-study":
                $idea_tax = get_term_by('slug', 'bertha-case-study', 'idea_template');
                $wp_ideas_option_term = 'wa_case_study_ideas';
                $template_name = 'Case Study Generator (STAR Method)';

                $bertha_subject = isset($_POST['bertha_subject']) ? sanitize_text_field($_POST['bertha_subject']) : "";
                $bertha_info = isset($_POST['bertha_info']) ? sanitize_text_field($_POST['bertha_info']) : "";
                $bertha_brand = isset($_POST['bertha_brand']) ? sanitize_text_field($_POST['bertha_brand']) : "";
                $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
                $bertha_keyword = isset($_POST['bertha_keyword']) ? sanitize_text_field($_POST['bertha_keyword']) : "";
                $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
                $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
                if(isset($_POST['data_subject']) && $_POST['data_subject']) $bertha_subject = sanitize_text_field($_POST['data_subject']);
                if(isset($_POST['data_info']) && $_POST['data_info']) $bertha_info = sanitize_text_field($_POST['data_info']);
                if(isset($_POST['data_brand']) && $_POST['data_brand']) $bertha_brand = sanitize_text_field($_POST['data_brand']);
                if(isset($_POST['data_desc']) && $_POST['data_desc']) $bertha_desc = sanitize_textarea_field($_POST['data_desc']);
                if(isset($_POST['data_keyword']) && $_POST['data_keyword']) $bertha_keyword = sanitize_text_field($_POST['data_keyword']);
                $body_arg = array('subject' => $bertha_subject, 'info' => $bertha_info, 'brand' => $bertha_brand, 'desc' => $bertha_desc, 'keyword' => $bertha_keyword, 'template' => 'case-study');
                $generate_idea_html = 'data-subject="'.$bertha_subject.'" data-info="'.$bertha_info.'" data-brand="'.$bertha_brand.'" data-desc="'.$bertha_desc.'" data-keyword="'.$bertha_keyword.'"';
                break;
        }

        $url = 'https://bertha.ai/wp-json/wa/implement';
        $args = array(
                'method' => 'POST',
                'body'   => json_encode( array_merge(array( 'language' => $language, 'strict_mode' => $this->strict_mode, 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url(), 'current_user' => $user_email, 'idea_unique_id' => $idea_unique_id, 'berideas' => $berideas ), $body_arg) ),
                'headers' => [
                                'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                'Content-Type' =>  'application/json',
                            ],
        );
        $response = wp_remote_post($url, $args); 
        if (!is_wp_error($response) && isset($response['body'])) {
            if(get_option($wp_ideas_option_term)) {
                $bthai_ideas = get_option($wp_ideas_option_term);
            } else {
                $bthai_ideas = 0;
            }
            $results = '<form  id="form3">
            <div class="ber_inner_title">'.__('Choose the idea you prefer', 'bertha-ai').' <span class="ber-dashicons ber-dashicons-arrow-right-alt2"></span></div>
                            <div class="ber_inner_p">'.__('Click the area you want the idea to be inserted to, then click the idea that works for you.', 'bertha-ai').'</div>';
            if(isset(json_decode($response['body'])->initial_token_covered)) {
                $result_array['initial_token_covered'] = true;
            } elseif(isset(json_decode($response['body'])->license_expired)) {
                $result_array['license_expired'] = true;
            } else {
                if(isset(json_decode($response['body'])->token_denied)) {
                    $result_array['token_denied'] = json_decode($response['body'])->token_denied;
                    $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                    $result_array['limit'] = json_decode($response['body'])->limit;
                } else {
                    foreach(json_decode($response['body'])->choices as $key => $choice) {
                        if(strlen($choice->text) > 1) {
                            $bthai_ideas++;
                            $new = array(
                                'post_title' => $template_name.' - Idea:'. $bthai_ideas,
                                'post_content' => $choice->text,
                                'post_type'   => 'idea',
                                'post_status' => 'publish',
                            );
                            $post_id = wp_insert_post( $new );
                            wp_set_object_terms($post_id, $idea_tax->term_id, 'idea_template');

                            if(get_post_meta($post_id, 'bertha_favourate_added', true)) {
                                $favourite =  __('Favourite added', 'bertha-ai');
                                $favourate_added = 'favourate_added';
                            } else {
                                $favourite = __('Add to favourite', 'bertha-ai');
                                $favourate_added = '';
                            }
                            update_post_meta($post_id, 'wa_idea_unique_id', $idea_unique_id);
                            $key_num = $key + 1 + $bertha_desc_index;
                            $results .= '<div class="ber-mb-3">
                                            <div class="ber-d-grid ber-gap-2">
                                                <div class="ber-action-icon-wrap">
                                                    <div class="bertha-copied-container ber-action-icon">
                                                        <button class="bertha_idea_copy" data-value="'.str_replace('"', "'", get_post($post_id)->post_content).'"><i class="ber-i-copy"></i></button>
                                                        <span class="bertha-copied-text" id="berthaCopied">'.__('Copy to clipboard', 'bertha-ai').'</span>
                                                    </div>
                                                    <div class="bertha-favourite-container ber-action-icon">
                                                        <button class="bertha_idea_favourite '.$favourate_added.'" data-value="'.$post_id.'"><i class="ber-i-heart"></i></button>
                                                        <span class="bertha-favourite-text" id="berthaFavourite">'.$favourite.'</span>
                                                    </div>
                                                    <div class="bertha-trash-container ber-action-icon">
                                                        <button class="bertha_idea_trash" data-value="'.$post_id.'"><i class="ber-i-trash"></i></button>
                                                        <span class="bertha-trash-text" id="berthaTrash">'.__('Delete', 'bertha-ai').'</span>
                                                    </div>
                                                    <div class="bertha-report-container ber-action-icon">
                                                        <button class="bertha_idea_report" data-value="'.$post_id.'"><i class="ber-i-flag-alt"></i></button>
                                                        <span class="bertha-report-text" id="berthaReport">'.__('Report', 'bertha-ai').'</span>
                                                    </div>
                                                </div>
                                               <input type="radio" class="ber-btn-check ber-idea-btn-check" name="options" id="option'.$key_num.'" autocomplete="off" data-block="'.$data_block.'">
                                                <label class="ber-btn bertha_idea" for="option'.$key_num.'"><span class="bertha_idea_number">'.$idea_tax->name.'</span><div class="bertha_idea_body"><pre>'.preg_replace('/\\\\/', '',wp_strip_all_tags($choice->text)).'</pre></div></label>
                                            </div>
                                        </div>';
                        }
                    }
                    $results .= '<div class="ber-gap-2 ber_half" id="more_idea_generate" style="margin-bottom: 15px;">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea" id="next7" data-id="'.$block.'" data-index="'.$key_num.'" '.$generate_idea_html.' data-block="'.$data_block.'">'.__('Generate More Ideas', 'bertha-ai').'</button>
                                </div>
                                <div class="ber-gap-2 ber_half">
                                    <button class="ber-btn bertha_sec_btn" id="next3">'.__('Choose Another Template', 'bertha-ai').'</button>
                                </div> 
                                
                                <div class="ber-more-new-ideas-here"></div>               
                            </form>';
                    update_option($wp_ideas_option_term, $bthai_ideas);
                }

                $result_array['html'] = $results;
                $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                $result_array['limit'] = json_decode($response['body'])->limit;
                $result_array['idea_history'] = get_bertha_history_ideas();
                if($key_num > 4) {
                    $result_array['more_html'] = 'true';
                }else {
                    $result_array['more_html'] = 'false';
                }
            }
            delete_transient(BTHAI_TRANSIENT_LICENSE_DETAILS);
            print_r(json_encode($result_array));die;
        }
    }

    function bthai_generate_playground_ai_action_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_ber_playground_nonce' );

        $result_array = array();
        if(BTHAI_LICENSE_KEY) {
            $user_email = $this->bthai_get_customer_email();
            $options = get_option('bertha_ai_options') ? (array) get_option('bertha_ai_options') : array();
            $options1 = get_option('bertha_ai_license_options') ? (array) get_option('bertha_ai_license_options') : array();
            $language = isset($options['language']) ? $options['language'] : '';
            $berideas = isset($options1['berideas']) ? $options1['berideas'] : 4;
            $prompt = isset($_POST['prompt']) ? str_replace("\'", "'", sanitize_textarea_field($_POST['prompt'])) : "";

            $idea_unique_id = md5(uniqid());
            $url = 'https://bertha.ai/wp-json/wa/implement';
            $args = array(
                    'method' => 'POST',
                    'body'   => json_encode( array( 'language' => $language, 'strict_mode' => $this->strict_mode, 'prompt' => $prompt, 'template' => 'quickwins', 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url(), 'current_user' => $user_email, 'idea_unique_id' => $idea_unique_id, 'berideas' => $berideas ) ),
                    'headers' => [
                                    'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                    'Content-Type' =>  'application/json',
                                ],
            );
            $response = wp_remote_post($url, $args); 
            if (!is_wp_error($response) && isset($response['body'])) {
                if(isset(json_decode($response['body'])->initial_token_covered)) {
                    $result_array['initial_token_covered'] = true;
                } elseif(isset(json_decode($response['body'])->license_expired)) {
                    $result_array['license_expired'] = true;
                } else {
                    if(isset(json_decode($response['body'])->token_denied)) {
                        $result_array['token_denied'] = json_decode($response['body'])->token_denied;
                        $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                        $result_array['limit'] = json_decode($response['body'])->limit;
                    } else {
                        $results = json_decode($response['body'])->choices[0]->text;
                    }

                    delete_transient(BTHAI_TRANSIENT_LICENSE_DETAILS);

                    $result_array['html'] = $results;
                    $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                    $result_array['limit'] = json_decode($response['body'])->limit;
                    if($key_num > 4) {
                        $result_array['more_html'] = 'true';
                    }else {
                        $result_array['more_html'] = 'false';
                    }
                }
                print_r(json_encode($result_array));die;
            }
        } else {
            $result_array['ber_license_required'] = true;
            print_r(json_encode($result_array));die;
        }
    }

    function bthai_generate_chat_ai_action_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_ber_chat_nonce' );

        $result_array = array();
        if(BTHAI_LICENSE_KEY) {
            $id = get_current_user_id();
            $user_email = $this->bthai_get_customer_email();
            $options = get_option('bertha_ai_options') ? (array) get_option('bertha_ai_options') : array();
            $options1 = get_option('bertha_ai_license_options') ? (array) get_option('bertha_ai_license_options') : array();
            $language = isset($options['language']) ? $options['language'] : '';
            $prompt = isset($_POST['prompt']) ? str_replace("\'", "'", sanitize_textarea_field($_POST['prompt'])) : "";
            $new_chat = isset($_POST['new_chat']) ? str_replace("\'", "'", sanitize_textarea_field($_POST['new_chat'])) : "";
            $base_prompt = json_decode($this->license_details->free_templates)->chat_prompt_prompt;
            if($new_chat == 'true') {
                if(!empty(get_user_meta($id, 'ber_user_chat', true))) {
                    delete_user_meta($id, 'ber_user_chat');
                }
                $chats = array( 
                    array('role' => 'system', 'content' => $base_prompt),
                    array('role' => 'user', 'content' => $prompt)
                );
            } else {
                $chats = get_user_meta($id, 'ber_user_chat', true) ? get_user_meta($id, 'ber_user_chat', true) : array();
                $chats[] = array('role' => 'user', 'content' => $prompt);
            }

            $idea_unique_id = md5(uniqid());
            $url = 'https://bertha.ai/wp-json/wa/implement';
            $args = array(
                    'method' => 'POST',
                    'body'   => json_encode( array( 'language' => $language, 'strict_mode' => $this->strict_mode, 'prompt' => $chats, 'template' => 'chat', 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url(), 'current_user' => $user_email, 'idea_unique_id' => $idea_unique_id, 'gpt_turbo' => true) ),
                    'headers' => [
                                    'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                    'Content-Type' =>  'application/json',
                                ],
            );
            $response = wp_remote_post($url, $args); 
            if (!is_wp_error($response) && isset($response['body'])) {
                if(isset(json_decode($response['body'])->initial_token_covered)) {
                    $result_array['initial_token_covered'] = true;
                } elseif(isset(json_decode($response['body'])->license_expired)) {
                    $result_array['license_expired'] = true;
                } else {
                    if(isset(json_decode($response['body'])->token_denied)) {
                        $result_array['token_denied'] = json_decode($response['body'])->token_denied;
                        $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                        $result_array['limit'] = json_decode($response['body'])->limit;
                    } else {
                        $results = json_decode($response['body'])->choices[0]->text;
                        $chats[] = array('role' => 'assistant', 'content' => $results);
                        update_user_meta($id, 'ber_user_chat', $chats);
                    }

                    delete_transient(BTHAI_TRANSIENT_LICENSE_DETAILS);

                    $result_array['html'] = $results;
                    $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                    $result_array['limit'] = json_decode($response['body'])->limit;
                    if($key_num > 4) {
                        $result_array['more_html'] = 'true';
                    }else {
                        $result_array['more_html'] = 'false';
                    }
                }
                print_r(json_encode($result_array));die;
            }
        } else {
            $result_array['ber_license_required'] = true;
            print_r(json_encode($result_array));die;
        }
    }

    function bthai_art_search_ai_action_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_art_search_nonce' );

        $term = isset($_POST['term']) ? $_POST['term'] : '';
        $count = isset($_POST['count']) ? $_POST['count'] : 0;
        $url = 'https://bertha.ai/wp-json/generate/image';
        $args = array(
                'method' => 'POST',
                'body'   => json_encode( array_merge(array( 'term' => $term, 'type' => 'search' ) ) ),
                'headers' => [
                                'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                'Content-Type' =>  'application/json',
                            ],
        );
        $response = wp_remote_post($url, $args); 
        $body = json_decode($response['body']);
        $html = '';
        if($body && $body->images && count($body->images) > 0) {
            $images = $body->images;
            foreach($images as $key => $image) {
                $key += $count;
                $html .= '<div class="ber-searched-images-col ber_img_'.$key.'" data-key="'.$key.'" data-src="'.$image->src.'?ext=.jpeg" data-prompt="'.$image->prompt.'"><div class="ber-searched-images-inner"><img src="'.$image->src.'" /><div class="bertha_art_image_options"></div></div></div>';
            }
        } else {
            $html .= 'Please try again.';
        }
        print_r($html);die;
    }

    function bthai_ask_favourite_added_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_favourite_ask_nonce' );

        $favourite_array = array();
        $body = isset($_POST['askBody']) ? sanitize_textarea_field($_POST['askBody']) : "";
        $request_type = isset($_POST['request_type']) ? sanitize_text_field($_POST['request_type']) : "";

        $idea_tax = get_term_by('slug', 'bertha-quickwins', 'idea_template');
        $new = array(
            'post_title' => 'Ask Me Anything',
            'post_content' => $body,
            'post_type'   => 'idea',
            'post_status' => 'publish',
        );
        $idea_id = wp_insert_post( $new );
        wp_set_object_terms($idea_id, $idea_tax->term_id, 'idea_template');
        if($idea_id) {
            if($request_type == 'add-favourate') update_post_meta($idea_id, 'bertha_favourate_added', true);
            else delete_post_meta($idea_id, 'bertha_favourate_added', true);
            $favourite_array['response'] = 'success';
            $favourite_array['favourite_ideas'] = get_bertha_favourite_ideas();
            $favourite_array['idea_history'] = get_bertha_history_ideas();
        } else {
            $favourite_array['response'] = 'failed';
        }
        print_r(json_encode($favourite_array));die();
    }

    function bthai_long_form_ai_action_callback() {
        if (!isset($_POST['bertha_long_form_nonce']) || !wp_verify_nonce($_POST['bertha_long_form_nonce'], 'bertha_long_form')) {
               die();
        }

        global $current_user;
        $result_array = array();
        $suffix_context = '';
        $bertha_keyword = isset($_POST['bertha_keyword']) ? sanitize_text_field($_POST['bertha_keyword']) : "";
        $bertha_title = isset($_POST['bertha_title']) ? sanitize_text_field($_POST['bertha_title']) : "";
        $bertha_desc = isset($_POST['bertha_desc']) ? sanitize_textarea_field($_POST['bertha_desc']) : "";
        $bertha_audience = isset($_POST['bertha_audience']) ? sanitize_text_field($_POST['bertha_audience']) : "";
        $bertha_tone = isset($_POST['bertha_tone']) ? sanitize_text_field($_POST['bertha_tone']) : "";
        $bertha_body_offset = isset($_POST['bertha_body_offset']) ? sanitize_text_field($_POST['bertha_body_offset']) : 0;
        $bertha_desc_index = isset($_POST['bertha_desc_index']) ? sanitize_text_field($_POST['bertha_desc_index']) : 0;
        $bertha_form_body = isset($_POST['bertha_form_body']) ? sanitize_text_field($_POST['bertha_form_body']) : '';
        if(isset($_POST['data_title']) && $_POST['data_title']) $bertha_title = sanitize_text_field($_POST['data_title']);
        if(isset($_POST['data_keyword']) && $_POST['data_keyword']) $bertha_keyword = sanitize_text_field($_POST['data_keyword']);
        if(isset($_POST['last_dec']) && $_POST['last_dec']) {
            $last_dec = sanitize_textarea_field($_POST['last_dec']);
            // if($bertha_body_offset && $bertha_body_offset > 1) $last_dec = substr($last_dec, 0, ($bertha_body_offset));
            // print_r($last_dec);die;
            $context = substr($last_dec, -3000);
        } else {
            $context = '';
        }
        if(strlen($bertha_body_offset) && strlen($bertha_body_offset) > 1 && strlen($bertha_form_body)) $suffix_context = substr($bertha_form_body, strlen($bertha_body_offset), 3000);
        if($_POST['data_audience']) $bertha_audience = sanitize_text_field($_POST['data_audience']);
        if($_POST['data_tone']) $bertha_tone = sanitize_text_field($_POST['data_tone']);
        
        $options = get_option('bertha_ai_options') ? (array) get_option('bertha_ai_options') : array();
        $options1 = get_option('bertha_ai_license_options') ? (array) get_option('bertha_ai_license_options') : array();
        $language = isset($options['language']) ? $options['language'] : '';
        $berideas = isset($options1['berideas']) ? $options1['berideas'] : 4;

        $url = 'https://bertha.ai/wp-json/wa/implement';
        if($context) {
            $args = array(
                    'method' => 'POST',
                    'body'   => json_encode( array( 'language' => $language, 'strict_mode' => $this->strict_mode, 'keyword' => $bertha_keyword, 'title' => $bertha_title, 'tone' => $bertha_tone, 'desc' => $bertha_desc, 'audience' => $bertha_audience, 'context' => $context, 'suffix_context' => $suffix_context, 'template' => 'long-form', 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url(), 'current_user' => $current_user->user_email, 'berideas' => $berideas ) ),
                    'headers' => [
                                    'Content-Type' =>  'application/json',
                                ],
            );
        } else {
            $args = array(
                'method' => 'POST',
                'body'   => json_encode( array( 'language' => $language, 'strict_mode' => $this->strict_mode, 'desc' => $bertha_desc, 'keyword' => $bertha_keyword, 'audience' => $bertha_audience, 'suffix_context' => $suffix_context, 'title' => $bertha_title, 'tone' => $bertha_tone, 'token' => true, 'template' => 'long-form', 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url(), 'current_user' => $current_user->user_email, 'berideas' => $berideas ) ),
                'headers' => [
                                'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                'Content-Type' =>  'application/json',
                            ],
        );
        }
        $response = wp_remote_post($url, $args);
        if (!is_wp_error($response) && isset($response['body'])) {
            if(isset(json_decode($response['body'])->initial_token_covered)) {
                $result_array['initial_token_covered'] = true;
            } elseif(isset(json_decode($response['body'])->license_expired)) {
                $result_array['license_expired'] = true;
            } else {
                if(isset(json_decode($response['body'])->token_denied)) {
                    $result_array['token_denied'] = json_decode($response['body'])->token_denied;
                } else {
                    foreach(json_decode($response['body'])->choices as $key => $choice) {
                        if(strlen($choice->text) > 1) {
                            $key_num = $key + 1 + $bertha_desc_index;
                            $results = preg_replace('/\\\\/', '', wp_strip_all_tags($choice->text));
                        }
                    }
                }  

                delete_transient(BTHAI_TRANSIENT_LICENSE_DETAILS);

                $result_array['html'] = $results;
                $result_array['data-index'] = $key_num;
                $result_array['data-keyword'] = $bertha_keyword;
                $result_array['data-title'] = $bertha_title;
                $result_array['data-desc'] = $bertha_desc;
                $result_array['data-audience'] = $bertha_audience;
                $result_array['data-tone'] = $bertha_tone;
                if($key_num > 1) {
                    $result_array['more_html'] = 'true';
                }else {
                    $result_array['more_html'] = 'false';
                }
            }
            print_r(json_encode($result_array));die;
        }
    }

    function bthai_long_form_save_draft_ai_action_callback() {

        if (!isset($_POST['bertha_long_form_nonce']) || !wp_verify_nonce($_POST['bertha_long_form_nonce'], 'bertha_long_form')) {
               die();
        }

        $user_email = $this->bthai_get_customer_email();
        $result_array = array();
        $bertha_title = isset($_POST['bertha_title']) ? sanitize_text_field($_POST['bertha_title']) : "";
        $bertha_body = isset($_POST['bertha_body']) ? $_POST['bertha_body'] : "";
        $bertha_draft = isset($_POST['bertha_draft']) ? sanitize_text_field($_POST['bertha_draft']) : "";
        $save_type = isset($_POST['save_type']) ? sanitize_text_field($_POST['save_type']) : "";

        if($save_type == 'post_draft') {
            wp_insert_post( array(
                'post_type' => 'post',
                'post_title' =>  $bertha_title,
                'post_content' =>  $bertha_body
            ) );
        } elseif($bertha_draft) {
            $new = array(
                    'ID' =>  $bertha_draft,
                    'post_title' => $bertha_title,
                    'post_content' => $bertha_body,
                    'post_type'   => 'backedn',
                    'post_status' => 'publish',
                );
            wp_update_post( $new );
        } else {
            $new = array(
                    'post_title' => $bertha_title,
                    'post_content' => $bertha_body,
                    'post_type'   => 'backedn',
                    'post_status' => 'publish',
                );
            wp_insert_post( $new );
        }
        $result_array['drafts'] = get_bertha_backedn_drafts();
        print_r(json_encode($result_array));die;
    }

    function bthai_long_form_edit_draft_ai_action_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_draft_edit_nonce' );

        $result = array();
        $draft_id = isset($_POST['draft_id']) ? sanitize_text_field($_POST['draft_id']) : '';
        $draft_post = get_post($draft_id); 
        if($draft_post) {
            $result['draft_body'] = $draft_post->post_content;
        } else {
            $result['draft_body'] = 'false';
        }
        print_r(json_encode($result));die;
    }

    function bthai_wa_ai_templates_callback() {
        check_ajax_referer( 'bertha_templates_nonce', 'bertha_template_list_nonce' );

        $result = array();
        $template = isset($_POST['wa_template']) ? sanitize_text_field($_POST['wa_template']) : '';
        $data_block = isset($_POST['data_block']) ? sanitize_text_field($_POST['data_block']) : '';
        $options = get_option('bertha_ai_options') ? (array) get_option('bertha_ai_options') : array();
        if(BTHAI_LICENSE_KEY) {
            $license_details = bthai_get_license_details('all');
            $plugin_type = $license_details->bertha_plugin_type ? $license_details->bertha_plugin_type : '';
            if($plugin_type) {
                $free_templates = json_decode($license_details->free_templates);
                $upgrade_message = '<div class="ber_notice"><p>'.__('This is a Premium Feature', 'bertha-ai').'</p><p><a class="bertha_premium_upgrade" href="https://bertha.ai/#doit" target="_blank">'.__('click to upgrade', 'bertha-ai').'</a></p></div>';
            }
            switch ($template) {
                case "USP":
                    $result['tax_name'] = __('🏆 Unique Value Proposition', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid gap-2">
                                    <label class="ber-btn bertha_template" for="option8" data-id="website"><span class="bertha_template_icon">🏆</span>'.__('Unique Value Proposition', 'bertha-ai').'<span class="bertha_template_desc">'.__('That will make you stand out from the Crowd and used as the top sentence of your website.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->usp_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <input type="hidden" class="ber-form-control bertha_brand" id="brand" value="'.esc_attr( $options['brand_name'] ).'">
                                <input type="hidden" class="ber-form-control bertha_ideal_cust" id="ideal_customer" value="'.esc_attr( $options['ideal_customer'] ).'">
                                <input type="hidden" class="ber-form-control bertha_sentiment" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Company Description/In your own words', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control bertha_desc" maxlength="800" id="bertha_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="USP" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="kdck5F2TwiU">Learn More about Unique Value Proposition</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "Headline":
                    $result['tax_name'] = __('🥈 Website Sub-Headline', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option9" data-id="website"><span class="bertha_template_icon">🥈</span>'.__('Website Sub-Headline', 'bertha-ai').'<span class="bertha_template_desc">'.__('A converting description that will go below your USP on the website - great for H2 Headings and SEO.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->heading_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <input type="hidden" class="ber-form-control sub_headline_ideal_cust" id="ideal_customer" value="'.esc_attr( $options['ideal_customer'] ).'">
                                <input type="hidden" class="ber-form-control sub_headline_sentiment" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                <div class="ber-mb-3">
                                    <label for="usp" class="ber-form-label">'.__('The main title of the website or page', 'bertha-ai').'/post</label>
                                    <input type="text" class="ber-form-control sub_headline_usp" maxlength="100" id="usp" placeholder="'.__('E.G Your unique selling proposition', 'bertha-ai').'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Description in your own words', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control sub_headline_desc" maxlength="800" id="bertha_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Headline" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="6HZzfGNbMOM">Learn More about Website Sub-Headline</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "Title":
                    $result['tax_name'] = __('🏹 Section Title Generator', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option10" data-id="website"><span class="bertha_template_icon">🏹</span>'.__('Section Title Generator', 'bertha-ai').'<span class="bertha_template_desc">'.__('Creative titles for each section of your website. No more boring "About us" type of titles.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->title_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <input type="hidden" class="ber-form-control sec_title_ideal_cust" id="ideal_customer" value="'.esc_attr( $options['ideal_customer'] ).'">
                                <input type="hidden" class="ber-form-control sec_title_sentiment" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                <div class="ber-mb-3">
                                    <label for="title_type" class="ber-form-label">'.__('Title Type', 'bertha-ai').'</label>
                                    <select class="ber-form-control sec_title_type" id="title_type">
                                    <option value="">'.__('Select from the drop down or choose your own', 'bertha-ai').'</option>
                                        <option>'.__('About Us', 'bertha-ai').'</option>
                                        <option>'.__('Client Recommendation', 'bertha-ai').'</option>
                                        <option>'.__('Our Services', 'bertha-ai').'</option>
                                        <option>'.__('How It Works', 'bertha-ai').'</option>
                                        <option>'.__('Call To Action', 'bertha-ai').'</option>
                                        <option>'.__('Contact Us', 'bertha-ai').'</option>
                                        <option>'.__('Latest Articles', 'bertha-ai').'</option>
                                        <option>'.__('From The Press', 'bertha-ai').'</option>
                                        <option>'.__('Our Partners', 'bertha-ai').'</option>
                                        <option>'.__('But Wait... There More', 'bertha-ai').'</option>
                                        <option>'.__('Price Guarantee', 'bertha-ai').'</option>
                                        <option>'.__('Our Features', 'bertha-ai').'</option>
                                        <option>'.__('Newsletter Sign Up', 'bertha-ai').'</option>
                                        <option>'.__('Urgency and Scarcity', 'bertha-ai').'</option>
                                        <option>'.__('Before and After', 'bertha-ai').'</option>
                                        <option value="other">'.__('Other...', 'bertha-ai').'</option>
                                    </select>
                                </div>
                                <div class="ber-mb-3 other-title" style="display:none;">
                                    <label for="other" class="ber-form-label">'.__('Insert Your Own Title Type', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control other_title" maxlength="100" id="other" placeholder="'.__('Insert Your Own Title Type', 'bertha-ai').'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Company Description or use your own words', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control sec_title_desc" maxlength="800" id="bertha_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Title" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="Z3zthDuzPKs">Learn More about Section Title Generator</div>
                                </div>
                            </form>';
                    }else {
                       $result['html'] = $upgrade_message; 
                    }
                    break;
                case "Paragraph":
                    $result['tax_name'] = __('💣 Paragraph Generator', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option6" data-id="website"><span class="bertha_template_icon">💣</span>'.__('Paragraph Generator', 'bertha-ai').'<span class="bertha_template_desc">'.__('Great for getting over writers block: Craft creative short paragraphs fro different areas of your website in blog posts and pages.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->paragraph_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <input type="hidden" class="ber-form-control para_ideal_cust" id="ideal_customer" value="'.esc_attr( $options['ideal_customer'] ).'">
                                <input type="hidden" class="ber-form-control para_sentiment" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                <div class="ber-mb-3">
                                    <label for="title" class="ber-form-label">'.__('Title', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control para_title" maxlength="100" id="title" placeholder="'.__('The title of the subject of the paragraph', 'bertha-ai').'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('In Your Own Words (If required)', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control para_desc" id="bertha_desc" rows="3" maxlength="800" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Paragraph" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="N50nUnub3gQ">Learn More about Paragraph Generator</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "Service":
                    $result['tax_name'] = __('💲 Product/Service Description', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option13" data-id="website"><span class="bertha_template_icon">💲</span>'.__('Product/Service Description', 'bertha-ai').'<span class="bertha_template_desc">'.__('Awesome product descriptions sell more products - Let Bertha help you by providing exceptional product descriptions.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->service_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <input type="hidden" class="ber-form-control service_description_sentiment" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                <div class="ber-mb-3">
                                    <label for="name" class="ber-form-label">'.__('Product/Service Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control service_description_name" maxlength="100" id="name" placeholder="'.__('Add a product, feature or service name or title', 'bertha-ai').'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Add a short description of your Product or Service', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control service_description_desc" maxlength="800" id="bertha_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Service" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="knc2AnzVXXs">Learn More about Product/Service Description</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "Company":
                    $result['tax_name'] = __('🏭 Full-on About Us Page (Company Bio)', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option14" data-id="website"><span class="bertha_template_icon">🏭</span>'.__('Full-on About Us Page', 'bertha-ai').'<span class="bertha_template_desc">'.__('Bertha already knows you. She will write an overview, history, mission and vision for your company.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->company_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="brand" class="ber-form-label">'.__('Brand Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control company_brand" id="brand" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Company Details or add your own text if you wish', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control company_bio_desc" maxlength="800" id="bertha_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Company" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="zWX67ClHQhQ">Learn More about Full-on About Us Page (Company Bio)</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "Company-mission":
                    $result['tax_name'] = __('🚀 Company Mission & Vision', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option23" data-id="website"><span class="bertha_template_icon">🚀</span>'.__('Company Mission & Vision', 'bertha-ai').'<span class="bertha_template_desc">'.__('From your company description, Bertha will write inspiring Mission and Vision statements for your "About Us" page.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->company_mission_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="brand" class="ber-form-label">Brand Name</label>
                                    <input type="text" class="ber-form-control company_mission_brand" id="brand" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Company Details', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control company_mission_desc" maxlength="800" id="bertha_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Company-mission" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>  
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video"data-id="J6iNkPeVAT0">Learn More about Company Mission & Vision</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "Testimonial":
                    $result['tax_name'] = __('🥂 Testimonial Generator', 'bertha-ai');
                    $result['tax_description'] = __('Chasing clients to write testimonials is a pain. Generate them for them and ask their approval to use them.', 'bertha-ai');
                    if(isset($free_templates->testimonial_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Company Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control testimonial_desc" maxlength="800" id="bertha_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Testimonial" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video"><iframe src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe></div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "Benefit-List":
                    $result['tax_name'] = __('🥰 Service/Product Benefit List', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option12" data-id="website"><span class="bertha_template_icon">🥰</span>'.__('Service/Product Benefit List', 'bertha-ai').'<span class="bertha_template_desc">'.__('Instantly generate a list of differentiators and benefits for your own company and brand.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->benefit_list_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Company Description or use your own words to get the best out of this model', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control benefit_desc" maxlength="800" id="bertha_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Benefit-List" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="Onz1IGBLBIw">Learn More about Service/Product Benefit List</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "Content-Improver":
                    $result['tax_name'] = __('🎢 Content Rephraser', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option7" data-id="website"><span class="bertha_template_icon">🎢</span>'.__('Content Rephraser', 'bertha-ai').'<span class="bertha_template_desc">'.__('Not confident with what you wrote? Paste it in and let Bertha\'s magic make it all better.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->content_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="sentiment" class="ber-form-label">'.__('Tone of voice. E.G. Professional or Witty', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control content_improver_sentiment" maxlength="100" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Text Origin', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control content_improver_desc" maxlength="2000" id="bertha_desc" rows="3" placeholder="'.__('Paste or type some text you would like to improve upon', 'bertha-ai').'"></textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Content-Improver" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="sy5EgKUK4KY">Learn More about Content Rephraser</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "Benefit-Title":
                    $result['tax_name'] = __('🦚 Title to Benefit Sections', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option5" data-id="website"><span class="bertha_template_icon">🦚</span>'.__('Title to Benefit Sections', 'bertha-ai').'<span class="bertha_template_desc">'.__('Take a benefit of your product/service and expand it to provide additional engaging details.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->benefit_title_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="title" class="ber-form-label">'.__('Title of benefit', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control Benefit_title" maxlength="100" id="title" placeholder="'.__('Add a benefit or your product or service', 'bertha-ai').'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bertha_desc" class="ber-form-label">'.__('Description in your own words', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control Benefit_title_desc" maxlength="800" id="bertha_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="Benefit-Title" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="Uk9SgrvE4d0">Learn More about Title to Benefit Sections</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "bullet-points":
                    $result['tax_name'] = __('✔ Persuasive Bullet Points', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option15" data-id="marketing"><span class="bertha_template_icon">✔</span>'.__('Persuasive Bullet Points', 'bertha-ai').'<span class="bertha_template_desc">'.__('Convince readers that your product is the best by listing all the reasons they should take action NOW.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->bullet_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Company Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control bullet_desc" maxlength="800" id="bullet_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="bullet-points" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="BqTgBP-fl0s">Learn More about Persuasive Bullet Points</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "personal-bio":
                    $result['tax_name'] = __('😎 Personal Bio (About Me)', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option16" data-id="website"><span class="bertha_template_icon">😎</span>'.__('Personal Bio (About Me)', 'bertha-ai').'<span class="bertha_template_desc">'.__('Writing about ourselves is hard. It\'s not for Bertha - Let her do it for you and only fix what\'s needed.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->personal_bio_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="info" class="ber-form-label">'.__('Personal Information', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control personal_bio_desc" maxlength="800" id="info" rows="1" placeholder="'.__('Personal Information', 'bertha-ai').'"></textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="sentiment" class="ber-form-label">'.__('Sentiment', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control personal_bio_sentiment" maxlength="100" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="point" class="ber-form-label">'.__('Point of view', 'bertha-ai').'</label>
                                    <select class="ber-form-control personal_bio_point" id="point">
                                        <option>'.__('First Person', 'bertha-ai').'</option>
                                        <option>'.__('Third Person', 'bertha-ai').'</option>
                                    </select>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="personal-bio" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="KJ5_Z8f_Mkk">Learn More about Personal Bio (About Me)</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "blog-post-idea":
                    $result['tax_name'] = __('💡 Blog Post Topic Ideas', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option17" data-id="blog"><span class="bertha_template_icon">💡</span>'.__('Blog Post Topic Ideas', 'bertha-ai').'<span class="bertha_template_desc">'.__('Trained with data from hundreds of thousands of blog posts, Bertha uses this data to generate a variety of creative blog post ideas.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->topic_ideas_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <input type="hidden" class="ber-form-control blog_idea_sentiment" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                <input type="hidden" class="ber-form-control blog_idea_cust" id="bertha_desc" rows="3" value="'.esc_attr( $options['ideal_customer'] ).'">
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Company Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control blog_idea_desc" maxlength="800" id="bullet_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="blog-post-idea" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="3SBsBh3uDY0">Learn More about Blog Post Topic Ideas</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "blog-post-intro-paragraph":
                    $result['tax_name'] = __('🦅 Blog Post Intro Paragraph', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option18" data-id="blog"><span class="bertha_template_icon">🦅</span>'.__('Blog Post Intro Paragraph', 'bertha-ai').'<span class="bertha_template_desc">'.__('Not sure how to start writing your next winning blog post? Bertha will get the ball rolling on taking your blog post topic and generate an intriguing intro paragraph.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->intro_para_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="sentiment" class="ber-form-label">'.__('Sentiment', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control intro_sentiment" maxlength="100" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="ideal_customer" class="ber-form-label">'.__('Ideal Customer', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control intro_ideal_cust" maxlength="100" id="ideal_customer" value="'.esc_attr( $options['ideal_customer'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="title" class="ber-form-label">'.__('The Title of the Article', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control intro_title" maxlength="100" id="title" placeholder="'.__('Add the subject of the title of the blog post', 'bertha-ai').'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea" id="wa-generate-idea" data-id="blog-post-intro-paragraph" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="QL0sSIoSLgM">Learn More about Blog Post Intro Paragraph</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "blog-post-outline":
                    $result['tax_name'] = __('🧐 Blog Post Outline', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option19" data-id="blog"><span class="bertha_template_icon">🧐</span>'.__('Blog Post Outline', 'bertha-ai').'<span class="bertha_template_desc">'.__('Map out your blog post\'s outline simply by adding the title or topic of the blog post you want to create. Bertha will take care of the rest.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->post_outline_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <input type="hidden" class="ber-form-control post_outline_sentiment" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                <div class="ber-mb-3">
                                    <label for="title" class="ber-form-label">'.__('The Title of the Article', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control post_outline_title" maxlength="100" id="title" placeholder="'.__('Add the subject of the title of the blog post', 'bertha-ai').'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea" id="wa-generate-idea" data-id="blog-post-outline" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="PGciuse1QXQ">Learn More about Blog Post Outline</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "blog-post-conclusion":
                    $result['tax_name'] = __('🦸‍♀️ Blog Post Conclusion Paragraph', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option20" data-id="blog"><span class="bertha_template_icon">🦸‍♀️</span>'.__('Blog Post Conclusion Paragraph', 'bertha-ai').'<span class="bertha_template_desc">'.__('Bertha can write a blog post conclusion paragraph that will help your visitors stick around to read the rest of your content.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->conclusion_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <input type="hidden" class="ber-form-control conclusion_sentiment" id="sentiment" value="'.esc_attr( $options['sentiment'] ).'">
                                <div class="ber-mb-3">
                                    <label for="title" class="ber-form-label">'.__('The Title of the Article', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control conclusion_title" maxlength="100" id="title" placeholder="'.__('Add the subject of the title of the blog post', 'bertha-ai').'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="cta" class="ber-form-label">'.__('Call to Action', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control conslusion_cta" maxlength="100" id="cta" placeholder="'.__('The action you would like the reader to take', 'bertha-ai').'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea" id="wa-generate-idea" data-id="blog-post-conclusion" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="wQ0iUu4s71g">Learn More about Blog Post Conclusion Paragraph</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "blog-action":
                    $result['tax_name'] = __('🎯 Button Call to Action', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option21" data-id="website"><span class="bertha_template_icon">🎯</span>'.__('Button Call to Action', 'bertha-ai').'<span class="bertha_template_desc">'.__('With Bertha, you can generate a call to action button that\'s guaranteed to convert. No more guessing what words will convert best!', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->action_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Description in your own words', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control blog_action_desc" maxlength="800" id="bullet_desc" rows="3" placeholder="'.__('Company Description', 'bertha-ai').'">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Let your users know  what to do when the button is clicked', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control blog_action" maxlength="100" id="action" placeholder="'.__('E.G Click here to find out more', 'bertha-ai').'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="blog-action" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="uqxQ7tBk2oc">Learn More about Button Call to Action</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "child-explain":
                    $result['tax_name'] = __('👶 Explain It To a Child', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option22" data-id="useful_extra"><span class="bertha_template_icon">👶</span>'.__('Explain It To a Child', 'bertha-ai').'<span class="bertha_template_desc">'.__('Taking complex concepts and simplifying them. So that everyone can get it. Get it?', 'bertha-ai').'</span></label>
                                </div>
                                </div>';
                    if(isset($free_templates->child_input_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="input" class="ber-form-label">'.__('Input Text', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control child_input" id="input" maxlength="800" rows="3" placeholder="'.__('Copy your complex text here.', 'bertha-ai').'"></textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="child-explain" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="rX1gT9_YwmM">Learn More about Explain It To a Child</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "seo-title":
                    $result['tax_name'] = __('⛩️ SEO Title Tag', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option23" data-id="seo"><span class="bertha_template_icon">⛩️</span>'.__('SEO Title Tag', 'bertha-ai').'<span class="bertha_template_desc">'.__('Get highly optimized title tags that will help you rank higher in search engines.', 'bertha-ai').'</span></label>
                                </div>
                                </div>';
                    if(isset($free_templates->seo_title_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Brand', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control seo_title_brand" maxlength="100" id="action" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('The Title in Your Own Words', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control seo_title" maxlength="100" id="action" placeholder="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Keyword', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control seo_keyword" maxlength="100" id="action" placeholder="">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea" id="wa-generate-idea" data-id="seo-title" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="pz-0752mxrQ">Learn More about SEO Title Tag</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "seo-description":
                    $result['tax_name'] = __('✒️ SEO Description Tag', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option24" data-id="seo"><span class="bertha_template_icon">✒️</span>'.__('SEO Description Tag', 'bertha-ai').'<span class="bertha_template_desc">'.__('You are serious about SEO, But this is a tedious task that can easily be automated with Bertha.', 'bertha-ai').'</span></label>
                                </div>
                                </div>';
                    if(isset($free_templates->seo_description_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('The Title in Your Own Words', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control seo_desc_title" maxlength="100" id="action" placeholder="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Keyword', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control seo_desc_keyword" maxlength="100" id="action" placeholder="">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea" id="wa-generate-idea" data-id="seo-description" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="yk44WBgUUMQ">Learn More about SEO Description Tag</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "aida-marketing":
                    $result['tax_name'] = __('✒️ AIDA Marketing Framework', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option25" data-id="marketing"><span class="bertha_template_icon">🍬</span>'.__('AIDA Marketing Framework', 'bertha-ai').'<span class="bertha_template_desc">'.__('Awareness > Interest > Desire > Action - Structure your writing and create more compelling content.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->aida_marketing_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Brand Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control aida_brand" maxlength="100" id="action" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Product Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control aida_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Ideal Customer', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control aida_cust" maxlength="100" id="action" value="'.esc_attr( $options['ideal_customer'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Tone of voice', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control aida_Sentiment" maxlength="100" id="action" value="'.esc_attr( $options['sentiment'] ).'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="aida-marketing" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="zQCwEVSXuJQ">Learn More about AIDA Marketing Framework</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "seo-city":
                    $result['tax_name'] = __('✒️ SEO City Based Pages', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option26" data-id="seo"><span class="bertha_template_icon">🏙</span>'.__('SEO City Based Pages', 'bertha-ai').'<span class="bertha_template_desc">'.__('Generate city page titles and descriptions for your city or town pages to help rank your website locally.', 'bertha-ai').'</span></label>
                                </div>
                                </div>';
                    if(isset($free_templates->seo_city_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Brand Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control seo_city_brand" maxlength="100" id="action" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('City', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control seo_city" maxlength="100" id="action" placeholder="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Service Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control seo_city_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Call To Action', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control seo_city_cta" maxlength="100" id="action" placeholder="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Keyword', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control seo_city_keyword" maxlength="100" id="action" placeholder="">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="seo-city" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="M6cBC6PkiyQ">Learn More about SEO City Based Pages</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "buisiness-name":
                    $result['tax_name'] = __('✒️ Business or Product Name', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option27" data-id="useful_extra"><span class="bertha_template_icon">⚓</span>'.__('Business or Product Name', 'bertha-ai').'<span class="bertha_template_desc">'.__('Create a new business or product name from scratch based on a keyword or phrase.', 'bertha-ai').'</span></label>
                                </div>
                             </div>';
                    if(isset($free_templates->buisiness_name_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Company Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control buisiness_name_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Tone of Voice', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control buisiness_name_vibe" maxlength="100" id="action" value="'.esc_attr( $options['sentiment'] ).'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="buisiness-name" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="1LI6zLrfGSY">Learn More about Business or Product Name</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "bridge":
                    $result['tax_name'] = __('✒️ Before, After and Bridge', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option28" data-id="marketing"><span class="bertha_template_icon">🌉</span>'.__('Before, After and Bridge', 'bertha-ai').'<span class="bertha_template_desc">'.__('Get a short description to build a page with a before and after look, with a transition in between.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->bridge_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Brand Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control bridge_brand" maxlength="100" id="action" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Product Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control bridge_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Ideal Customer', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control bridge_cust" maxlength="100" id="action" value="'.esc_attr( $options['ideal_customer'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Tone of voice', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control bridge_Sentiment" maxlength="100" id="action" value="'.esc_attr( $options['sentiment'] ).'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="bridge" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="NT5OeIf3xkY">Learn More about Before, After and Bridge</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "pas-framework":
                    $result['tax_name'] = __('✒️ PAS Framework', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option29" data-id="marketing"><span class="bertha_template_icon">🚥</span>'.__('PAS Framework', 'bertha-ai').'<span class="bertha_template_desc">'.__('Problem > Agitate > Solution - A framework for planning and evaluating your content marketing activities.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->pas_framework_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Brand Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control pas_brand" maxlength="100" id="action" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Product Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control pas_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Ideal Customer', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control pas_cust" maxlength="100" id="action" value="'.esc_attr( $options['ideal_customer'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Tone of voice', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control pas_Sentiment" maxlength="100" id="action" value="'.esc_attr( $options['sentiment'] ).'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="pas-framework" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="M99rpDP-lBs">Learn More about PAS Framework</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "faq-list":
                    $result['tax_name'] = __('✒️ FAQs List', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option30" data-id="website"><span class="bertha_template_icon">🙋‍♀️</span>'.__('FAQs List', 'bertha-ai').'<span class="bertha_template_desc">'.__('Generate a list of frequently asked questions for a service or product.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->faq_list_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Company/Service/Product Details', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control faq_list_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="faq-list" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="6EJwwiJYIi4">Learn More about FAQs List</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "faq-answer":
                    $result['tax_name'] = __('✒️ FAQ Answers', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option31" data-id="website"><span class="bertha_template_icon">😑</span>'.__('FAQ Answers', 'bertha-ai').'<span class="bertha_template_desc">'.__('Get an anwser to a question.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->faq_answer_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Question', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control faq_answer_question" maxlength="100" id="action" placeholder="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Company Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control faq_answer_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="faq-answer" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="hdKVKZegksk">Learn More about FAQ Answers</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "summaries":
                    $result['tax_name'] = __('✒️ Summaries', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option32" data-id="useful_extra"><span class="bertha_template_icon">🪐</span>'.__('Content Summary', 'bertha-ai').'<span class="bertha_template_desc">'.__('Create a summary of an article/website/blog post. Great for SEO and to share on social media.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->summary_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Content to summarise', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control content_summary" maxlength="800" id="bullet_desc" rows="3"></textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Tone of Voice', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control summary_sentiment" maxlength="100" id="action" value="'.esc_attr( $options['sentiment'] ).'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="summaries" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="hcnHWwnWFCw">Learn More about Summaries</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "contact-blurb":
                    $result['tax_name'] = __('✒️ Contact Form Blurb', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option33" data-id="website"><span class="bertha_template_icon">🤝</span>'.__('Contact Form Blurb', 'bertha-ai').'<span class="bertha_template_desc">'.__('Create a short description & Call to Action that will be used as the final persuasion text next to a contact form.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->contact_blurb_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Brand Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control contact_blurb_brand" maxlength="100" id="action" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Company Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control contact_blurb_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Call To Action', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control contact_blurb_cta" maxlength="100" id="action" placehlder="">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="contact-blurb" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="Ggrmnq-NHI4">Learn More about Contact Form Blurb</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "seo-keyword":
                    $result['tax_name'] = __('✒️ SEO Keyword Suggestions', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option34" data-id="seo"><span class="bertha_template_icon">🔑</span>'.__('SEO Keyword Suggestions', 'bertha-ai').'<span class="bertha_template_desc">'.__('Generate suggestions of long-tail keywords that are related to your topic.', 'bertha-ai').'</span></label>
                                </div>
                                </div>';
                    if(isset($free_templates->seo_keyword_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Company/Service/Product Details', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control seo_keyword_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="seo-keyword" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="DvBj7wgRETY">Learn More about SEO Keyword Suggestions</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "evil-bertha":
                    $result['tax_name'] = __('✒️ SEO Keyword Suggestions', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template ber_evil" for="option34" data-id="speciality"><span class="bertha_template_icon">😈</span>'.__('Evil Bertha', 'bertha-ai').'<span class="bertha_template_desc">'.__('Usually Bertha is nice and friendly, but not always...', 'bertha-ai').'</span></label>
                                </div>
                                </div>';
                    if(isset($free_templates->evil_bertha_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Bio', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control evil_bertha_bio" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="evil-bertha" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="X2vm7oic5-E">Learn More about Evil Bertha</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "real-estate":
                    $result['tax_name'] = __('🏡 Real Estate Property Listing Description', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option35" data-id="speciality"><span class="bertha_template_icon">🏡</span>'.__('Real Estate Property Listing Description', 'bertha-ai').'<span class="bertha_template_desc">'.__('Detailed and enticing property listings for your real estate websites. So you can focus on the sale.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->real_estate_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Brand', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control real_estate_brand" maxlength="100" id="action" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Location', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control real_estate_location" maxlength="100" id="action" placeholder="Location" value="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Type', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control real_estate_type" maxlength="100" id="action" placeholder="Tell Bertha how many rooms, kitchens, bathrooms, ensuite, etc.">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Property Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control real_estate_desc" maxlength="800" id="bullet_desc" rows="3" placeholder="Property Description"></textarea>
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="real-estate" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="wb6WXUcXQI4">Learn More about Real Estate Property Listing Description</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "press-blurb":
                    $result['tax_name'] = __('📰 Press Mention Blurb', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option36" data-id="speciality"><span class="bertha_template_icon">📰</span>'.__('Press Mention Blurb', 'bertha-ai').'<span class="bertha_template_desc">'.__('Provide the press mention title and publication to craft a press mention blurb.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->press_blurb_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Publication Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control press_blurb_pub_name" maxlength="100" id="action" placeholder="Publication Name" value="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('What is the article about?', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control press_blurb_article_info" maxlength="100" id="action" placeholder="What is the article about?" value="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Company Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control press_blurb_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Keyword', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control press_blurb_keyword" maxlength="100" id="action" placehlder="Keyword">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Brand Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control press_blurb_brand" maxlength="100" id="action" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="press-blurb" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="9i8bfl-pPcc">Learn More about Press Mention Blurb</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                case "case-study":
                    $result['tax_name'] = __('👨‍🎓 Case Study Generator (STAR Method)', 'bertha-ai');
                    $result['tax_description'] = '<div class="ber-mb-3">
                                <div class="ber-d-grid ber-gap-2">
                                    <label class="ber-btn bertha_template" for="option37" data-id="speciality"><span class="bertha_template_icon">👨‍🎓</span>'.__('Case Study Generator (STAR Method)', 'bertha-ai').'<span class="bertha_template_desc">'.__('Generate a case study based on a client name and a problem they wanted to solve.', 'bertha-ai').'</span></label>
                                </div>
                            </div>';
                    if(isset($free_templates->case_study_version) || $plugin_type == 'pro') {
                        $result['html'] = '<form  id="form2">
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Subject', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control case_study_subject" maxlength="100" id="action" placeholder="Who are we talking about?" value="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('What happened', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control case_study_info" maxlength="100" id="action" placeholder="What is the article about?" value="">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Brand Name', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control case_study_brand" maxlength="100" id="action" value="'.esc_attr( $options['brand_name'] ).'">
                                </div>
                                <div class="ber-mb-3">
                                    <label for="bullet_desc" class="ber-form-label">'.__('Company Description', 'bertha-ai').'</label>
                                    <textarea class="ber-form-control case_study_desc" maxlength="800" id="bullet_desc" rows="3">'.esc_attr( $options['customer_details'] ).'</textarea>
                                </div>
                                <div class="ber-mb-3">
                                    <label for="action" class="ber-form-label">'.__('Keyword', 'bertha-ai').'</label>
                                    <input type="text" class="ber-form-control case_study_keyword" maxlength="100" id="action" placehlder="Keyword">
                                </div>
                                <div class="ber-d-grid ber-gap-2">
                                    <button class="ber-btn ber-btn-primary wa-generate-idea bertha-desc-notice" id="wa-generate-idea" data-id="case-study" data-block="'.$data_block.'">'.__('Generate Ideas', 'bertha-ai').'</button>
                                    
                                </div>
                                
                                <div class="ber-mb-3 bertha-template-video-container">
                                    <label for="bertha-template-video-title" class="ber-form-label">'.__('How It Works', 'bertha-ai').'</label>
                                    <div class="bertha-template-description-video" data-id="0VDd302YMlU">Learn More about Case Study Generator (STAR Method)</div>
                                </div>
                            </form>';
                    }else {
                        $result['html'] = $upgrade_message;
                    }
                    break;
                default: 
                    $result['tax_name'] = '';
                    $result['tax_description'] = '';
                    $result['html'] = '<div class="ber_notice">'.__('You have selected wrong template.', 'bertha-ai').'</div>';
            }
        } else {
            $result['tax_name'] = '';
            $result['tax_description'] = '';
            $result['html'] = '<div class="ber_notice">'.__('Please activate your license.', 'bertha-ai').'<a href='.admin_url( 'admin.php?page=bertha-ai-license' ).'>'.__('Click Here', 'bertha-ai').'</a></div>';
        }
        print_r(json_encode($result));die;
    }

    function bthai_set_wizzard_data_callback() {

        if (!isset($_POST['bertha_set_wizzard_setup_nonce']) || !wp_verify_nonce($_POST['bertha_set_wizzard_setup_nonce'], 'bertha_set_wizzard_setup_form')) {
               die();
        }

        $setup_wizard = array();
        $setup_wizard['website_for'] = isset($_POST['website_for']) ? sanitize_text_field($_POST['website_for']) : "";
        $setup_wizard['about_client'] = isset($_POST['about_client']) ? sanitize_text_field($_POST['about_client']) : "";
        $setup_wizard['about_website'] = isset($_POST['about_website']) ? sanitize_text_field($_POST['about_website']) : "";
     
        update_option('bertha_setup_wizard_datas', $setup_wizard);
        die();
    }

    function bthai_set_wizzard_setting_data_callback() {

        if (!isset($_POST['bertha_wizzard_setup_nonce']) || !wp_verify_nonce($_POST['bertha_wizzard_setup_nonce'], 'bertha_wizzard_setup_form')) {
               die();
        }

        $setup_wizard = array();
        $setup_wizard['language'] = isset($_POST['language']) ? sanitize_text_field($_POST['language']) : "";
        $setup_wizard['brand_name'] = isset($_POST['brand']) ? sanitize_text_field(str_replace("\'", "'", $_POST['brand'])) : "";
        $setup_wizard['customer_details'] = isset($_POST['description']) ? sanitize_textarea_field(str_replace("\'", "'", $_POST['description'])) : "";
        $setup_wizard['ideal_customer'] = isset($_POST['ideal_cust']) ? sanitize_text_field(str_replace("\'", "'", $_POST['ideal_cust'])) : "";
        $setup_wizard['sentiment'] = isset($_POST['sentiment']) ? sanitize_text_field(str_replace("\'", "'", $_POST['sentiment'])) : "";
        $setup_wizard['berideas'] = isset($_POST['berideas']) ? sanitize_text_field($_POST['berideas']) : 4;
     
        update_option('bertha_ai_options', $setup_wizard);
        die();
    }

    function bthai_history_filter_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_history_filter_nonce' );

        $wa_template = isset($_POST['wa_template']) ? sanitize_text_field($_POST['wa_template']) : "";
        $idea_template = '<form  id="form4">';
        $args = array( 
          'numberposts'     => -1,
          'post_type'       => 'idea',
          'orderby'         => 'date',
          'order'       => 'DESC',
        );
        if($wa_template != 'all') {
            $args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'idea_template',
                                        'field'    => 'slug',
                                        'terms'    => $wa_template,
                                    )
                                );
        }
        $bertha_ideas = get_posts($args);
        if($bertha_ideas) {
            foreach($bertha_ideas as $key => $idea) {
                if(get_post_meta($idea->ID, 'bertha_favourate_added', true)) {
                    $favourite =  __('Favourite added', 'bertha-ai');
                    $favourate_added = 'favourate_added';
                } else {
                    $favourite = __('Add to favourite', 'bertha-ai');
                    $favourate_added = '';
                }
                $tax = get_the_terms( $idea->ID, 'idea_template' );
                $key += 1;
                $idea_template .= '<div class="ber-mb-3">
                                        <div class="ber-d-grid ber-gap-2">
                                            <div class="ber-action-icon-wrap">
                                                <div class="bertha-copied-container ber-action-icon">
                                                    <button class="bertha_idea_copy" data-value="'.str_replace('"', "'", $idea->post_content).'"><i class="ber-i-copy"></i></button>
                                                    <span class="bertha-copied-text" id="berthaCopied">'.__('Copy to clipboard', 'bertha-ai').'</span>
                                                </div>
                                                <div class="bertha-favourite-container ber-action-icon">
                                                    <button class="bertha_idea_favourite '.$favourate_added.'" data-value="'.$idea->ID.'"><i class="ber-i-heart"></i></button>
                                                    <span class="bertha-favourite-text" id="berthaFavourite">'.$favourite.'</span>
                                                </div>
                                                <div class="bertha-trash-container ber-action-icon">
                                                    <button class="bertha_idea_trash" data-value="'.$idea->ID.'"><i class="ber-i-trash"></i></button>
                                                    <span class="bertha-trash-text" id="berthaTrash">'.__('Delete', 'bertha-ai').'</span>
                                                </div>
                                                <div class="bertha-report-container ber-action-icon">
                                                    <button class="bertha_idea_report" data-value="'.$idea->ID.'"><i class="ber-i-flag-alt"></i></button>
                                                    <span class="bertha-report-text" id="berthaReport">'.__('Report', 'bertha-ai').'</span>
                                                </div>
                                            </div>
                                            <input type="radio" class="ber-btn-check ber-idea-btn-check" name="options" id="option'.$key.'" autocomplete="off" data-block="">
                                            <label class="ber-btn bertha_idea" for="option'.$key.'"><span class="bertha_idea_number">'.$tax[0]->name.'</span><div class="bertha_idea_body"><pre>'.preg_replace('/\\\\/', '',$idea->post_content).'</pre></div></label>
                                        </div>
                                    </div>';
                }
                $idea_template .= '';
        } else {
            $idea_template .= '<div class="ber_notice">'.__('No results found.', 'bertha-ai').'</div>';
        }
        $idea_template .= '</form>';
        print_r($idea_template);
        die();
    }

    function bthai_wa_bertha_load_more_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_load_more_nonce' );

        $tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : "";

        if($tab == 'history') {
            print_r(get_bertha_history_ideas());die;
        } else {
            print_r(get_bertha_favourite_ideas());die;
        }
    }

    function bthai_wa_favourite_added_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_favourite_idea_nonce' );

        $favourite_array = array();
        $idea_id = isset($_POST['idea_id']) ? sanitize_text_field($_POST['idea_id']) : "";
        $request_type = isset($_POST['request_type']) ? sanitize_text_field($_POST['request_type']) : "";
        if($idea_id) {
            if($request_type == 'add-favourate') update_post_meta($idea_id, 'bertha_favourate_added', true);
            else delete_post_meta($idea_id, 'bertha_favourate_added', true);
            $favourite_array['response'] = 'success';
            $favourite_array['favourite_ideas'] = get_bertha_favourite_ideas();
        } else {
            $favourite_array['response'] = 'failed';
        }
        print_r(json_encode($favourite_array));die();
    }

    function bthai_wa_idea_trash_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_idea_trash_nonce' );

        $favourite_array = array();
        $idea_id = isset($_POST['idea_id']) ? sanitize_text_field($_POST['idea_id']) : "";
        if($idea_id) {
            wp_delete_post($idea_id);
            $favourite_array['response'] = 'success';
            $favourite_array['idea_history'] = get_bertha_history_ideas();
            $favourite_array['favourite_ideas'] = get_bertha_favourite_ideas();
        } else {
            $favourite_array['response'] = 'failed';
        }
        print_r(json_encode($favourite_array));die();
    }

    function bthai_wa_idea_report_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_idea_report_nonce' );

        global $current_user;
        $return = array();
        $idea_id = isset($_POST['idea_id']) ? sanitize_text_field($_POST['idea_id']) : "";
        $report_body = isset($_POST['report_body']) ? sanitize_text_field($_POST['report_body']) : "";
        $ask_body = isset($_POST['ask_body']) ? sanitize_text_field($_POST['ask_body']) : "";

        $data = bertha_license_holder_details();
        if(empty( (array)$current_user->data )) {
            $user_email = $data['user_email'];
            $user_name = $data['user_name'];
        } else {
            $user_email = $current_user->user_email;
            $user_name = $current_user->display_name;
        }
        $account_email = $data['account_email'];

        if($idea_id || $ask_body) {
            if($ask_body) {
                $content = $ask_body;
                $idea_unique_id = md5(uniqid());
                $template_name = '🪐Ask Me Anything';
            } else {
                $idea_post = get_post($idea_id);
                $content = $idea_post->post_content;
                $idea_unique_id = get_post_meta($idea_id, 'wa_idea_unique_id', true) ? get_post_meta($idea_id, 'wa_idea_unique_id', true) : '';
                $term_obj_list = get_the_terms( $idea_id, 'idea_template' ); 
                $template_name = $term_obj_list[0]->name ? $term_obj_list[0]->name : '';
            }
            $url = 'https://bertha.ai/wp-json/reports/submit';
            $args = array(
                    'method' => 'POST',
                    'body'   => json_encode( array( 'idea' => $content, 'report_body' => $report_body, 'idea_unique_id' => $idea_unique_id, 'idea_name' => $template_name, 'home_url' => get_admin_url(), 'user_name' => $user_name, 'user_email' => $user_email, 'license' => BTHAI_LICENSE_KEY, 'account_email' => $account_email ) ),
                    'headers' => [
                                    'Content-Type' =>  'application/json',
                                ],
            );
            $response = wp_remote_post($url, $args); 
            if (!is_wp_error($response) && isset($response['body'])) {
                $return['response'] = 'success';
            } else {
                $return['response'] = 'failed';
            }
        } else {
            $return['response'] = 'failed';
        }
        print_r(json_encode($return));die();
    }

    function bthai_wa_bertha_load_history_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_load_history_nonce' );

        $history_count = isset($_POST['history_count']) ? sanitize_text_field( $_POST['history_count'] ) : 0;
        $history_count += 10;

        $args = array( 
      'numberposts'     => $history_count,
      'post_type'       => 'idea',
      'author'          => get_current_user_id(),
      'orderby'         => 'date',
      'order'       => 'DESC',
    );
    $bertha_ideas = get_posts($args);
    $idea_template =  array();
    $hjhgh = '';
    if($bertha_ideas) {
        foreach($bertha_ideas as $key => $idea) {
            if(get_post_meta($idea->ID, 'bertha_favourate_added', true)) {
                $favourite =  'Favourite added';
                $favourate_added = 'favourate_added';
            } else {
                $favourite = 'Add to favourite';
                $favourate_added = '';
            }
            $tax = get_the_terms( $idea->ID, 'idea_template' );
            $key += 1;
            $hjhgh .= '<div class="ber-mb-3 bertha-content-element">
                                    <div class="ber-d-grid ber-gap-2">
                                        <div class="ber-action-icon-wrap">
                                            <div class="bertha-copied-container ber-action-icon">
                                                <button class="bertha_idea_copy" data-value="'.str_replace('"', "'", $idea->post_content).'"><i class="ber-i-copy"></i></button>
                                                <span class="bertha-copied-text" id="berthaCopied">Copy to clipboard</span>
                                            </div>
                                            <div class="bertha-favourite-container ber-action-icon">
                                                <button class="bertha_idea_favourite '.$favourate_added.'" data-value="'.$idea->ID.'"><i class="ber-i-heart"></i></button>
                                                <span class="bertha-favourite-text" id="berthaFavourite">'.$favourite.'</span>
                                            </div>
                                            <div class="bertha-trash-container ber-action-icon">
                                                <button class="bertha_idea_trash" data-value="'.$idea->ID.'"><i class="ber-i-trash"></i></button>
                                                <span class="bertha-trash-text" id="berthaTrash">Delete</span>
                                            </div>
                                            <div class="bertha-report-container ber-action-icon">
                                                <button class="bertha_idea_report" data-value="'.$idea->ID.'"><i class="ber-i-flag-alt"></i></button>
                                                <span class="bertha-report-text" id="berthaReport">Report</span>
                                            </div>
                                        </div>
                                        <input type="radio" class="ber-btn-check ber-idea-btn-check" name="options" id="option'.$key.'" autocomplete="off" data-block="">
                                        <label class="bertha-btn bertha_idea" for="option'.$key.'"><span class="bertha_idea_number">'.$tax[0]->name.'</span><div class="bertha_idea_body"><pre>'.preg_replace('/\\\\/', '', wp_strip_all_tags($idea->post_content)).'</pre></div></label>
                                    </div>
                                </div>';
        }
        $hjhgh .= '';
        $idea_template['response'] = 'success';
    } else {
        $idea_template['response'] = 'failed';
    }
    $idea_template['ideas'] = $hjhgh;
    print_r(json_encode($idea_template));die();
    }

    function bthai_wa_bertha_load_favourite_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_load_favourite_nonce' );

        $favourite_count = isset($_POST['favourite_count']) ? sanitize_text_field( $_POST['favourite_count'] ) : 0;
        $favourite_count += 10;

        $args = array( 
      'numberposts'     => $favourite_count,
      'post_type'       => 'idea',
      'author'          => get_current_user_id(),
      'orderby'         => 'date',
      'order'           => 'DESC',
      'meta_query'      => array(
            array(
                'key'       => 'bertha_favourate_added',
                'value'     => 1
            )
        ),
    );
    $bertha_ideas = get_posts($args);
    $idea_template =  array();
    $hjhgh = '';
    if($bertha_ideas) {
        foreach($bertha_ideas as $key => $idea) {
            if(get_post_meta($idea->ID, 'bertha_favourate_added', true)) {
                $favourite =  'Favourite added';
                $favourate_added = 'favourate_added';
            } else {
                $favourite = 'Add to favourite';
                $favourate_added = '';
            }
            $tax = get_the_terms( $idea->ID, 'idea_template' );
            $key += 1;
            $hjhgh .= '<div class="ber-mb-3">
                                    <div class="ber-d-grid ber-gap-2 bertha-content-element">
                                        <div class="ber-action-icon-wrap">
                                            <div class="bertha-copied-container ber-action-icon">
                                                <button class="bertha_idea_copy" data-value="'.str_replace('"', "'", $idea->post_content).'"><i class="ber-i-copy"></i></button>
                                                <span class="bertha-copied-text" id="berthaCopied">Copy to clipboard</span>
                                            </div>
                                            <div class="bertha-favourite-container ber-action-icon">
                                                <button class="bertha_idea_favourite '.$favourate_added.'" data-value="'.$idea->ID.'"><i class="ber-i-heart"></i></button>
                                                <span class="bertha-favourite-text" id="berthaFavourite">'.$favourite.'</span>
                                            </div>
                                            <div class="bertha-trash-container ber-action-icon">
                                                <button class="bertha_idea_trash" data-value="'.$idea->ID.'"><i class="ber-i-trash"></i></button>
                                                <span class="bertha-trash-text" id="berthaTrash">Delete</span>
                                            </div>
                                            <div class="bertha-report-container ber-action-icon">
                                                <button class="bertha_idea_report" data-value="'.$idea->ID.'"><i class="ber-i-flag-alt"></i></button>
                                                <span class="bertha-report-text" id="berthaReport">Report</span>
                                            </div>
                                        </div>
                                        <input type="radio" class="ber-btn-check ber-idea-btn-check" name="options" id="option'.$key.'" autocomplete="off" data-block="">
                                        <label class="ber-btn bertha_idea" for="option'.$key.'"><span class="bertha_idea_number">'.$tax[0]->name.'</span><div class="bertha_idea_body"><pre>'.preg_replace('/\\\\/', '', wp_strip_all_tags($idea->post_content)).'</pre></div></label>
                                    </div>
                                </div>';
        }
        $hjhgh .= '';
        $idea_template['response'] = 'success';
    } else {
        $idea_template['response'] = 'failed';
    }
    $idea_template['ideas'] = $hjhgh;
    print_r(json_encode($idea_template));die();
    }

    function bthai_wa_bertha_load_draft_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_load_draft_nonce' );

        $drft_count = isset($_POST['drft_count']) ? sanitize_text_field( $_POST['drft_count'] ) : 0;
        $drft_count += 10;

        $args = array( 
          'numberposts'     => $drft_count,
          'post_type'       => 'backedn',
          'author'          => get_current_user_id(),
          'orderby'         => 'date',
          'order'           => 'DESC',
        );
        $backedn_drafts = get_posts($args);
        $idea_template =  array();
        $hjhgh = '';
        if($backedn_drafts) {
            foreach($backedn_drafts as $key => $draft) {
            $key += 1;
            $hjhgh .= '<div class="ber-mb-3 bertha-content-element">
                                    <div class="ber-d-grid ber-gap-2">
                                        <div class="ber-action-icon-wrap">
                                            <div class="bertha-copied-container ber-action-icon">
                                                <button class="bertha_draft_edit" data-title="'.$draft->post_title.'" data-id="'.$draft->ID.'"><i class="ber-i-pen"></i></button>
                                                <span class="bertha-copied-text" id="berthaCopied">Edit Draft</span>
                                            </div>
                                            <div class="bertha-trash-container ber-action-icon">
                                                <button class="bertha_idea_trash" data-value="'.$draft->ID.'"><i class="ber-i-trash"></i></button>
                                                <span class="bertha-trash-text" id="berthaTrash">Delete</span>
                                            </div>
                                        </div>
                                        <input type="radio" class="ber-btn-check ber-idea-btn-check" name="options" id="option'.$key.'" autocomplete="off" data-block="">
                                        <label class="ber-btn bertha_draft" for="option'.$key.'"><span class="bertha_draft_number">'.$draft->post_title.'</span><div class="bertha_draft_body"><pre>'.preg_replace('/\\\\/', '', wp_strip_all_tags($draft->post_content)).'</pre></div></label>
                                    </div>
                                </div>';
            }
            $hjhgh .= '';
            $idea_template['response'] = 'success';
        } else {
            $idea_template['response'] = 'failed';
        }
        $idea_template['ideas'] = $hjhgh;
        print_r(json_encode($idea_template));die();
    }

    function bthai_wa_bertha_clear_transient_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_clear_transient_nonce' );

        delete_transient(BTHAI_TRANSIENT_LICENSE_DETAILS);

        print_r('success');die;

    }

    function bthai_wa_bertha_get_art_view_callback() {
        check_ajax_referer( 'bertha_templates_nonce', 'bertha_art_view_nonce' );

        $image_credits = $this->license_details->image_credits ? $this->license_details->image_credits : 0;
        $image_credits_used = $this->license_details->image_credits_used ? $this->license_details->image_credits_used : 0;
        $search_img = is_admin() ? '<button type="button" class="ber_button ber_image_search_view" data-dismiss="ber-modal">Search Images</button>' : '';

        $imgContent = '<div class="ber-modal-content"><div class="ber-modal-header"><div class="ber-modal-title" id="berIdeaLongTitle"><div class="ber-report-primary-title">👩‍🎨 Ask Bertha to Create an Image</div></div></div>';
        if(intval($image_credits_used) < intval($image_credits)) {
            $imgContent .= '<div class="ber-modal-body ber_art_body"><div class="ber_art_form"><div class="ber_inner_title">Enter Text to Create Image</div><textarea id="ber_image_generate_body" class="ber_field" name="ber_image_generate_body" rows="6" cols="70" placeholder="Describe the image you would like to create."></textarea><input type="button" class="ber-btn ber-btn-primary ber-tooltip-element" data-toggle="tooltip" data-placement="top" title="Let Bertha enhance your description." id="ber_image_prompt_option_generate" value="Improve Description"><div class="ber_inner_title">Choose a Style</div><select id="ber_image_style" class="ber_field"><option value="">None</option><option value="painting">Painting</option><option value="drawing">Drawing</option><option value="animation">Animation</option><option value="screen">Screen</option><option value="photography">Photography (avoid people)</option><option value="material">Real Life Materials</option></select><div class="ber_image_sub_style"></div><div class="ber_inner_title">Choose a Trend</div><select id="ber_img_modifier" class="ber_field"><option value="">None</option><option value="in the style of steampunk">Steampunk</option><option value="synthwave">Synthwave</option><option value="in the style of cyberpunk">Cyberpunk</option><option value="insanely detailed and intricate, hypermaximalist, elegant, ornate, hyper realistic, super detailed">Detailed &amp; Intricate</option><option value="in a symbolic and meaningful style, insanely detailed and intricate, hypermaximalist, elegant, ornate, hyper realistic, super detailed">Symbolic &amp; Meaningful</option><option value="Cinematic Lighting">Cinematic Lighting</option><option value="Contre-Jour">Contre-Jour</option><option value="futuristic">Futuristic</option><option value="black and white">Black &amp; White</option><option value="technicolor">Technicolor</option><option value="warm color palette">Warm</option><option value="neon">Neon</option><option value="colorful">Colorful</option></select><div class="ber_inner_title">Choose an Artist</div><select id="ber_image_artist" class="ber_field"><option value="">None</option><option value="by Albert Bierstadt">Albert Bierstadt</option><option value="by Andy Warhol">Andy Warhol</option><option value="by Asaf Hanuka">Asaf Hanuka</option><option value="by Aubrey Beardsley">Aubrey Beardsley</option><option value="by Claude Monet">Claude Monet</option><option value="by Diego Rivera">Diego Rivera</option><option value="by Frida Kahlo">Frida Kahlo</option><option value="by Greg Rutkowski">Greg Rutkowski</option><option value="by Hayao Miyazaki">Hayao Miyazaki</option><option value="by Hieronymus Bosch">Hieronymus Bosch</option><option value="by Jackson Pollock">Jackson Pollock</option><option value="by Leonardo da Vinci">Leonardo da Vinci</option><option value="by Michelangelo">Michelangelo</option><option value="by Pablo Picasso">Pablo Picasso</option><option value="by Salvador Dali">Salvador Dali</option><option value="by artgerm, art germ">Stanley Artgerm</option><option value="by Thomas Kinkade">Thomas Kinkade</option><option value="by Vincent van Gogh">Vincent van Gogh</option></select><div class="ber-modal-footer"><button type="button" class="ber_button ber_image_generate_submit" data-dismiss="ber-modal">Create Images</button>'.$search_img.'</div>';
        } else {
            $imgContent .= '<div class="ber_notice">Buy More Image Credits, <a href="https://bertha.ai/#doit" target="_blank">Buy More</a></div>';
        }
        $imgContent .= '</div></div><div class="ber-art-display-options"></div><div class="ber-art-display-imgs"></div><div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div><div class="ber-img-progress ber-progress"><span></span></div></div>';
        print_r($imgContent);die;
    }

    function bthai_wa_generate_image_callback() {
        check_ajax_referer( 'bertha_templates_nonce', 'bertha_image_generate_nonce' );  
        
        $result_array = array();
        $prompt = isset($_POST['image_text']) ? sanitize_text_field($_POST['image_text']) : "Panda";
        $artist = isset($_POST['artist']) ? sanitize_text_field($_POST['artist']) : "";
        if($artist) $prompt .= ', '.$artist;
        $style = isset($_POST['style']) ? sanitize_text_field($_POST['style']) : "";
        if($style) $prompt .= ', '. $style;
        $modifier = isset($_POST['modifier']) ? sanitize_text_field($_POST['modifier']) : "";
        if($modifier) $prompt .= ', '. $modifier;
        $size = isset($_POST['size']) ? sanitize_text_field($_POST['size']) : "";
        //$height = isset($_POST['height']) ? sanitize_text_field($_POST['height']) : "";
        //$init_img = isset($_POST['init_img']) ? sanitize_text_field($_POST['init_img']) : "";
        //$mask_img = isset($_POST['mask_img']) ? sanitize_text_field($_POST['mask_img']) : "";
        //$prompt_strength = isset($_POST['prompt_strength']) ? sanitize_text_field($_POST['prompt_strength']) : "";
        //$num_steps = isset($_POST['num_steps']) ? sanitize_text_field($_POST['num_steps']) : "";
        //$guidance_Scale = isset($_POST['guidance_Scale']) ? sanitize_text_field($_POST['guidance_Scale']) : "";
        //$img_seed = isset($_POST['img_seed']) ? sanitize_text_field($_POST['img_seed']) : "";
        $posttype = isset($_POST['posttype']) ? sanitize_text_field($_POST['posttype']) : "";
        $posttype_id = isset($_POST['posttype_id']) ? sanitize_text_field($_POST['posttype_id']) : "";

        $url = 'https://bertha.ai/wp-json/generate/image';
        $args = array(
                'method' => 'POST',
                'body'   => json_encode( array_merge(array( 'prompt' => $prompt, 'size' => $size, 'posttype' => $posttype, 'posttype_id' => $posttype_id, 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url() ) ) ),
                'headers' => [
                                'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                'Content-Type' =>  'application/json',
                            ],
        );
        $response = wp_remote_post($url, $args); 
        if (!is_wp_error($response) && isset($response['body'])) {
            $json_body = json_decode($response['body']);
            if(isset($json_body->credit_denied)) {
                $result_array['credit_denied'] = $json_body->credit_denied;
            } else {
                $data = $json_body->data;
                $status = $json_body->status;
                $result_array['data'] = $data;
                $result_array['status'] = $status;
            }
            delete_transient(BTHAI_TRANSIENT_LICENSE_DETAILS);

            print_r(json_encode($result_array));die;
        }
    }

    function bthai_wa_save_media_callback() {
        check_ajax_referer( 'bertha_templates_nonce', 'bertha_image_save_media_nonce' );
        $download = isset($_POST['img_url']) ? $_POST['img_url'] : "";
        $delete_img = isset($_POST['delete_img']) ? $_POST['delete_img'] : "";
        if($delete_img) $img_url = isset($_POST['img_url']) ? sanitize_text_field($_POST['img_url']) : "";
        else $img_url = isset($_POST['img_url']) ? esc_url_raw($_POST['img_url']) : "";
        $data = array();
        $id = '';
        if($img_url && !$delete_img) {
            $id = media_sideload_image($img_url, '', '', 'id');
        }
        if($download && !$delete_img) {
            if($id) {
                $image_url = wp_get_attachment_url($id);
                $data['id'] = $id;
                $data['url'] = $image_url;
                $data['name'] = basename($image_url);
                print_r(json_encode($data));die;
            }
        } elseif($delete_img && $img_url) {
            wp_delete_attachment($img_url, true);
            $attachment_path = get_attached_file( $img_url);
            unlink($attachment_path);
           print_r('true');die;
        } else {
            print_r('true');die;
        }
    }

    function bthai_wa_insert_featured_callback() {
        check_ajax_referer( 'bertha_templates_nonce', 'bertha_image_featured' );
        $attch_id = $attch_url = '';
        $data = array();
        $img_url = isset($_POST['img_url']) ? esc_url_raw($_POST['img_url']) : "";
        $posttype = isset($_POST['posttype']) ? sanitize_text_field($_POST['posttype']) : "";
        $posttype_id = isset($_POST['posttype_id']) ? sanitize_text_field($_POST['posttype_id']) : "";

        if($img_url && $posttype) {
            $attch_id = media_sideload_image($img_url, '', '', 'id');
            if($attch_id) {
                $attch_url = wp_get_attachment_url($attch_id);
                if($posttype_id) {
                    update_post_meta($posttype_id, 'ber_featured_image', $attch_id);
                    $img = get_post_meta($posttype_id, 'ber_featured_image', true) ? get_post_meta($posttype_id, 'ber_featured_image', true) : '';
                }
                $data['attch_id'] = $attch_id;
                $data['attch_url'] = $attch_url;
            }
        }
        print_r(json_encode($data));die;
    }

    function bthai_wa_ber_improve_img_prompt_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_ber_improve_img_prompt_nonce' );

        $user_email = $this->bthai_get_customer_email();
        $result_array = array();
        $options = get_option('bertha_ai_options') ? (array) get_option('bertha_ai_options') : array();
        $options1 = get_option('bertha_ai_license_options') ? (array) get_option('bertha_ai_license_options') : array();
        $language = isset($options['language']) ? $options['language'] : '';
        $berideas = isset($options1['berideas']) ? $options1['berideas'] : 4;
        $prompt = isset($_POST['prompt']) ? str_replace("\'", "'", sanitize_textarea_field($_POST['prompt'])) : "";

        $idea_unique_id = md5(uniqid());
        $url = 'https://bertha.ai/wp-json/wa/implement';
        $args = array(
                'method' => 'POST',
                'body'   => json_encode( array( 'language' => $language, 'strict_mode' => $this->strict_mode, 'prompt' => $prompt, 'template' => 'img_prompt', 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url(), 'current_user' => $user_email, 'idea_unique_id' => $idea_unique_id, 'berideas' => $berideas ) ),
                'headers' => [
                                'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                'Content-Type' =>  'application/json',
                            ],
        );
        $response = wp_remote_post($url, $args);
        if (!is_wp_error($response) && isset($response['body'])) {
            if(isset(json_decode($response['body'])->initial_token_covered)) {
                $result_array['initial_token_covered'] = true;
            } elseif(isset(json_decode($response['body'])->license_expired)) {
                $result_array['license_expired'] = true;
            } else {
                if(isset(json_decode($response['body'])->token_denied)) {
                    $result_array['token_denied'] = json_decode($response['body'])->token_denied;
                    $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                    $result_array['limit'] = json_decode($response['body'])->limit;
                } else {
                    $results = '<div class="ber-text-promps-ideas-wrapper"><div class="ber_grid_text_parent"><div class="img-prompt-title"><p><strong>Click the improved prompt ideas below to add them as the image description.</strong></p></div>';
                    $bthai_ideas = 0;
                    $idea_tax = get_term_by('slug', 'bertha-image-prompt', 'idea_template');
                    foreach(json_decode($response['body'])->choices as $key => $choice) {
                        if(strlen($choice->text) > 1) {
                            $ctext = $choice->text;
                            $arr = explode(".",$ctext);
                            if($arr[count($arr) - 1] != '') {
                                array_pop($arr);
                                $ctext = implode('. ', $arr);
                                $ctext = $ctext.'.';
                            }
                            $bthai_ideas++;
                            $new = array(
                                'post_title' => 'Image Prompt - Idea:'. $bthai_ideas,
                                'post_content' => $choice->text,
                                'post_type'   => 'idea',
                                'post_status' => 'publish',
                            );
                            $post_id = wp_insert_post( $new );
                            wp_set_object_terms($post_id, $idea_tax->term_id, 'idea_template');

                            // if(get_post_meta($post_id, 'bertha_favourate_added', true)) {
                            //     $favourite =  __('Favourite added', 'bertha-ai');
                            //     $favourate_added = 'favourate_added';
                            // } else {
                            //     $favourite = __('Add to favourite', 'bertha-ai');
                            //     $favourate_added = '';
                            // }
                            update_post_meta($post_id, 'wa_idea_unique_id', $idea_unique_id);
                            $key_num = $key + 1 + $bthai_ideas;
                            $results .= '<div class="ber-mb-3">
                                            <div class="ber-d-grid ber-gap-2">
                                               <input type="radio" class="ber-btn-check ber-idea-btn-check" name="options" id="option'.$key_num.'" autocomplete="off" data-block="img_prompt">
                                                <label class="ber-btn bertha_img_prompt" for="option'.$key_num.'"><span class="bertha_idea_number">'.$idea_tax->name.'</span><div class="bertha_idea_body"><pre>'.preg_replace('/\\\\/', '',wp_strip_all_tags($choice->text)).'</pre></div></label>
                                            </div>
                                        </div>';
                        }
                    }
                    $results .= '</div></div>';
                }

                delete_transient(BTHAI_TRANSIENT_LICENSE_DETAILS);

                $result_array['html'] = $results;
                $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                $result_array['limit'] = json_decode($response['body'])->limit;
                if($key_num > 4) {
                    $result_array['more_html'] = 'true';
                }else {
                    $result_array['more_html'] = 'false';
                }
            }
            print_r(json_encode($result_array));die;
        }
    }

    function bthai_wa_resize_media_callback() {

        check_ajax_referer( 'bertha_templates_nonce', 'bertha_image_resize_media_nonce' );

        $img_url = isset($_POST['img_url']) ? $_POST['img_url'] : '';
        $posttype = isset($_POST['posttype']) ? sanitize_text_field($_POST['posttype']) : "";
        $posttype_id = isset($_POST['posttype_id']) ? sanitize_text_field($_POST['posttype_id']) : "";

        if($img_url) {
            $url = 'https://bertha.ai/wp-json/generate/image';
            $args = array(
                    'method' => 'POST',
                    'body'   => json_encode( array_merge(array( 'img_url' => $img_url, 'posttype' => $posttype, 'posttype_id' => $posttype_id, 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url() ) ) ),
                    'headers' => [
                                    'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                    'Content-Type' =>  'application/json',
                                ],
            );
            $response = wp_remote_post($url, $args); 
            if (!is_wp_error($response) && isset($response['body'])) {
                $json_body = json_decode($response['body']);
                if(isset($json_body->credit_denied)) {
                    $result_array['credit_denied'] = $json_body->credit_denied;
                } else {
                    $data = $json_body->data;
                    $status = $json_body->status;
                    $result_array['data'] = $data;
                    $result_array['status'] = $status;
                }
                delete_transient(BTHAI_TRANSIENT_LICENSE_DETAILS);

                print_r(json_encode($result_array));die;
            }
        }
    }

    function bthai_wa_edit_description_callback() {

        check_ajax_referer( 'bertha_description_nonce', 'bertha_edit_description_nonce' );

        $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : "";
        $post = get_post($post_id);
        print_r($post->post_content);die;

    }

    function bthai_wa_update_description_callback() {

        check_ajax_referer( 'bertha_description_nonce', 'bertha_update_description_nonce' );

        $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : "";
        $content = isset($_POST['content']) ? sanitize_text_field($_POST['content']) : "";
        $my_post = array('ID' => $post_id,'post_content' => $content, 'post_excerpt' => $content);
        wp_update_post( $my_post );
        print_r('true');die;

    }

    function bthai_translate_content_callback() {
        check_ajax_referer( 'bertha_templates_nonce', 'bertha_ber_translate_nonce' );

        $result_array = array();
        if(BTHAI_LICENSE_KEY) {
            $id = get_current_user_id();
            $user_email = $this->bthai_get_customer_email();
            $content = isset($_POST['content']) ? str_replace("\'", "'", sanitize_textarea_field($_POST['content'])) : "";
            $language = isset($_POST['language']) ? str_replace("\'", "'", sanitize_textarea_field($_POST['language'])) : "";
            $chats = array( 
                    array('role' => 'system', 'content' => 'Translate in '.$language.':'),
                    array('role' => 'user', 'content' => $content)
                );

            $idea_unique_id = md5(uniqid());
            $url = 'https://bertha.ai/wp-json/wa/implement';
            $args = array(
                    'method' => 'POST',
                    'body'   => json_encode( array( 'language' => $language, 'strict_mode' => $this->strict_mode, 'prompt' => $chats, 'template' => 'chat', 'key' => BTHAI_LICENSE_KEY, 'home_url' => get_admin_url(), 'current_user' => $user_email, 'idea_unique_id' => $idea_unique_id, 'gpt_turbo' => true) ),
                    'headers' => [
                                    'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                    'Content-Type' =>  'application/json',
                                ],
            );
            $response = wp_remote_post($url, $args); 
            if (!is_wp_error($response) && isset($response['body'])) {
                if(isset(json_decode($response['body'])->initial_token_covered)) {
                    $result_array['initial_token_covered'] = true;
                } elseif(isset(json_decode($response['body'])->license_expired)) {
                    $result_array['license_expired'] = true;
                } else {
                    if(isset(json_decode($response['body'])->token_denied)) {
                        $result_array['token_denied'] = json_decode($response['body'])->token_denied;
                        $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                        $result_array['limit'] = json_decode($response['body'])->limit;
                    } else {
                        $results = json_decode($response['body'])->choices[0]->text;
                    }

                    delete_transient(BTHAI_TRANSIENT_LICENSE_DETAILS);

                    $result_array['html'] = $results;
                    $result_array['left_limit'] = json_decode($response['body'])->left_limit;
                    $result_array['limit'] = json_decode($response['body'])->limit;
                    if($key_num > 4) {
                        $result_array['more_html'] = 'true';
                    }else {
                        $result_array['more_html'] = 'false';
                    }
                }
                print_r(json_encode($result_array));die;
            }
        } else {
            $result_array['ber_license_required'] = true;
            print_r(json_encode($result_array));die;
        }
    }

    function bthai_free_create_purchase_callback() {
        if (!isset($_POST['bertha_ber_create_nonce']) || !wp_verify_nonce($_POST['bertha_ber_create_nonce'], 'bertha_ber_create_form')) {
               die();
        }

        $ber_free_name = isset($_POST['ber_free_name']) ? sanitize_user( $_POST['ber_free_name'] ) : "";
        $ber_free_email = isset($_POST['ber_free_email']) ? sanitize_email( $_POST['ber_free_email'] ) : "";

        $url = 'https://bertha.ai/wp-json/free/purchase';
        $args = array(
                'method' => 'POST',
                'body'   => json_encode( array( 'ber_name' => $ber_free_name, 'ber_email' => $ber_free_email ) ),
                'headers' => [
                                'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                'Content-Type' =>  'application/json',
                            ],
        );
        $response = wp_remote_post($url, $args);
        if (!is_wp_error($response) && isset($response['body'])) {
            print_r(json_decode($response['body']));
        } else {
            print_r('failed');
        }
        die();
    }

    function bthai_free_create_purchase_submit_callback() {
        if (!isset($_POST['bertha_ber_free_create_nonce']) || !wp_verify_nonce($_POST['bertha_ber_free_create_nonce'], 'bertha_ber_free_create_form')) {
               die();
        }
        
        $website_for = isset($_POST['website_for']) ? sanitize_text_field( $_POST['website_for'] ) : "";
        $about_website = isset($_POST['about_website']) ? sanitize_text_field( $_POST['about_website'] ) : "";
        $free_user = isset($_POST['free_user']) ? sanitize_text_field( $_POST['free_user'] ) : "";
     
        $url = 'https://bertha.ai/wp-json/free/purchase_submit';
        $args = array(
                'method' => 'POST',
                'body'   => json_encode( array( 'website_for' => $website_for, 'about_website' => $about_website, 'free_user' => $free_user ) ),
                'headers' => [
                                'Authorization' => 'Bearer ' . BTHAI_LICENSE_KEY,
                                'Content-Type' =>  'application/json',
                            ],
        );
        $response = wp_remote_post($url, $args);
        if (!is_wp_error($response) && isset($response['body'])) {
            print_r('success');
        } else {
            print_r('failed');
        }
        die();
    }

    function bthai_get_customer_email() {
        global $current_user;
        
        $user_email = '';
        if(BTHAI_LICENSE_KEY) {

            if(empty( (array)$current_user->data )) {

                $user_email = '';

                $url = 'https://bertha.ai/wp-json/license/customer';
                $args = array(
                        'method' => 'POST',
                        'body'   => json_encode( array( 'license' => BTHAI_LICENSE_KEY ) ),
                        'headers' => [
                                        'Content-Type' =>  'application/json',
                                    ],
                );

                $response = wp_remote_post($url, $args); 

                if (!is_wp_error($response) && isset($response['body'])) {

                    $user_email = json_decode($response['body']);

                }

            } else {

                $user_email = $current_user->user_email;
            }

        }

        return $user_email;
    }

}
