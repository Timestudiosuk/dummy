<?php

class WA_Bertha_AI {

	private $plugin_url;
    public $plugin_path;
    public $license_key;
    public $price_id;
    public $dashboard_url;
    public $item_id;
    public $theme;
    public $file;
	public $current_page;
	public $license_details;

	public function __construct($file) {
		global $pagenow;
        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->license_key = BTHAI_LICENSE_KEY;
        $this->price_id = BTHAI_LICENSE_PRICE_ID;
        $this->dashboard_url = BTHAI_STORE_URL;
        $this->item_id = BTHAI_ITEM_ID;
        $this->theme = wp_get_theme();
        $this->current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        $this->license_details = bthai_get_license_details('all');

        add_action('init', array(&$this, 'bthai_init'), 0);
        add_action('wp_head', array(&$this, 'bthai_noindex_for_companies'));
        add_filter( 'http_request_timeout', array(&$this, 'bthai_wp9838c_timeout_extend' ));
        add_action('admin_init', array(&$this, 'bthai_setup_wizard_callback'));
        add_filter('post_type_link', array(&$this, 'bthai_filter_post_type_link'), 10, 2);
        add_action('elementor/editor/after_enqueue_scripts', array(&$this, 'bthai_scripts'));
		add_action('elementor/editor/after_enqueue_scripts', array(&$this, 'bthai_admin_scripts')); 
		if ( 'Divi' == $this->theme->name || 'Divi' == $this->theme->parent_theme || isset($_GET['fl_builder']) || isset($_GET['ct_builder']) || isset($_GET['tve']) ) {
		    if ( isset($_GET['fl_builder']) || isset($_GET['ct_builder']) || isset( $_GET['et_fb'] ) || ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'et_fb_ajax_render_shortcode' ) || isset($_GET['tve']) ) {
		        add_action('wp_enqueue_scripts', array(&$this, 'bthai_admin_scripts'), 99999);
		        add_action('wp_enqueue_scripts', array(&$this, 'bthai_scripts'), 99999);
		    }
		}
		if( !in_array( $this->current_page, array( 'bertha-ai-backend-bertha', 'bertha-ai-license' ) ) ) {
		    add_action('wp_enqueue_scripts', array(&$this, 'bthai_admin_scripts'), 99999);
		    add_action('admin_enqueue_scripts', array(&$this, 'bthai_admin_scripts'), 99999);
		}
		add_action('wp_enqueue_scripts', array(&$this, 'bthai_scripts'), 99999);
		add_action('admin_enqueue_scripts', array(&$this, 'bthai_scripts'));
    }

    function bthai_init() {

    	if(isset($_GET['page']) && $_GET['page'] == 'bertha-ai-setting') {
    		wp_safe_redirect( esc_url(admin_url( 'admin.php?page=bertha-ai-license' )) );
	    	exit;
    	}
    	$labels = array(
	        'name'                => __( 'Idea', 'Post Type General Name', 'bertha-ai' ),
	        'singular_name'       => __( 'Idea', 'Post Type Singular Name', 'bertha-ai' ),
	        'menu_name'           => __( 'Idea', 'bertha-ai' ),
	        'parent_item_colon'   => __( 'Parent Idea', 'bertha-ai' ),
	        'all_items'           => __( 'Ideas', 'bertha-ai' ),
	        'view_item'           => __( 'View Idea', 'bertha-ai' ),
	        'add_new_item'        => __( 'Add New Idea', 'bertha-ai' ),
	        'add_new'             => __( 'Add New', 'bertha-ai' ),
	        'edit_item'           => __( 'Edit Idea', 'bertha-ai' ),
	        'update_item'         => __( 'Update Idea', 'bertha-ai' ),
	        'search_items'        => __( 'Search Idea', 'bertha-ai' ),
	        'not_found'           => __( 'Not Found', 'bertha-ai' ),
	        'not_found_in_trash'  => __( 'Not found in Trash', 'bertha-ai' ),
	    );
	          
	    $args = array(
	        'label'               => __( 'Idea', 'bertha-ai' ),
	        'description'         => __( 'Idea', 'bertha-ai' ),
	        'labels'              => $labels,
	        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ), 
	        'taxonomies' => array( 'idea_template' ),
	        'hierarchical'        => true,
	        'public'              => false,
	        'show_ui'             => true,
	        'show_in_menu'        => false,
	        'show_in_ber-nav_menus'   => true,
	        'show_in_admin_bar'   => true,
	        'menu_position'       => 5,
	        'rewrite' => false,
	        'can_export'          => true,
	        'has_archive'         => true,
	        'exclude_from_search' => true,
	        'capability_type'     => 'post',
	        'ber-show_in_rest' => true,
	 
	    );
        register_post_type( 'Idea', $args );

        $labels = array(
	        'name'                => __( 'Backedn', 'Post Type General Name', 'bertha-ai' ),
	        'singular_name'       => __( 'Backedn', 'Post Type Singular Name', 'bertha-ai' ),
	        'menu_name'           => __( 'Backedn', 'bertha-ai' ),
	        'parent_item_colon'   => __( 'Parent Backedn', 'bertha-ai' ),
	        'all_items'           => __( 'Backedn', 'bertha-ai' ),
	        'view_item'           => __( 'View Backedn', 'bertha-ai' ),
	        'add_new_item'        => __( 'Add New Backedn', 'bertha-ai' ),
	        'add_new'             => __( 'Add New', 'bertha-ai' ),
	        'edit_item'           => __( 'Edit Backedn', 'bertha-ai' ),
	        'update_item'         => __( 'Update Backedn', 'bertha-ai' ),
	        'search_items'        => __( 'Search Backedn', 'bertha-ai' ),
	        'not_found'           => __( 'Not Found', 'bertha-ai' ),
	        'not_found_in_trash'  => __( 'Not found in Trash', 'bertha-ai' ),
	    );
	          
	    $args = array(
	        'label'               => __( 'Backedn', 'bertha-ai' ),
	        'description'         => __( 'Backedn', 'bertha-ai' ),
	        'labels'              => $labels,
	        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ), 
	        'hierarchical'        => true,
	        'public'              => false,
	        'rewrite'             => false,
	        'show_ui'             => true,
	        'show_in_menu'        => false,
	        'show_in_ber-nav_menus'   => true,
	        'show_in_admin_bar'   => true,
	        'menu_position'       => 5,
	        'can_export'          => true,
	        'has_archive'         => true,
	        'exclude_from_search' => true,
	        'capability_type'     => 'post',
	        'ber-show_in_rest' => true,
	 
	    );
        register_post_type( 'Backedn', $args );

    	register_taxonomy(
	        'idea_template',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
	        'idea',             // post type name
	        array(
	            'hierarchical' => true,
	            'label' => 'Templates', // display name
	            'query_var' => true,
	            'public' => false,
	            'rewrite' => false
	        )
	    );

    	if(!term_exists('idea-usp', 'idea_template')) {
		    wp_insert_term( '🏆 Unique Value Proposition', 'idea_template', array(
		        'slug' => 'idea-usp',
		        'description' => 'That will make you stand out from the Crowd and used as the top sentence of your website.'
		    ) );
		}
		if(!term_exists('idea-paragraph', 'idea_template')) {
		    wp_insert_term( '💣 Blurb Generator', 'idea_template', array(
		        'slug' => 'idea-paragraph',
		        'description' => 'Great for getting over writers block: Craft creative short paragraphs from different areas of your website in blog posts and pages.'
		    ) );
		    $term = get_term_by('slug', 'idea-paragraph', 'idea_template');
	    	wp_update_term( $term->term_id, 'idea_template', array('name' => '💣 Paragraph Generator') );
		}
		if(!term_exists('section-title', 'idea_template')) {
		    wp_insert_term( '🏹 Section Title Generator', 'idea_template', array(
		        'slug' => 'section-title',
		        'description' => 'Creative titles for each section of your website. No more boring "About us" type of titles.'
		    ) );
		}
		if(!term_exists('product-service-description', 'idea_template')) {
		    wp_insert_term( '💲 Product/Service Description', 'idea_template', array(
		        'slug' => 'product-service-description',
		        'description' => 'Awesome product descriptions sell more products - Let Bertha help you by providing exceptional product descriptions.'
		    ) );
		}
		if(!term_exists('sub-headline', 'idea_template')) {
		    wp_insert_term( '🥈 Website Sub-Headline', 'idea_template', array(
		        'slug' => 'sub-headline',
		        'description' => 'A converting description that will go below your USP on the website - great for H2 Headings and SEO.'
		    ) );
		}
		if(!term_exists('company-bio', 'idea_template')) {
		    wp_insert_term( '🏭 Full-on About Us Page (Company Bio)', 'idea_template', array(
		        'slug' => 'company-bio',
		        'description' => 'Bertha already knows you. She will write an overview, history, mission and vision for your company.'
		    ) );
		}
		if(!term_exists('Company-mission', 'idea_template')) {
		    wp_insert_term( '🚀 Company Mission & Vision', 'idea_template', array(
		        'slug' => 'Company-mission',
		        'description' => "From your company description, Bertha will write inspiring Mission and Vision statements for your 'About Us' page."
		    ) );
		}
		if(!term_exists('idea-benefit', 'idea_template')) {
		    wp_insert_term( '🥰 Service/Product Benefit List', 'idea_template', array(
		        'slug' => 'idea-benefit',
		        'description' => 'Instantly generate a list of differentiators and benefits for your own company and brand.'
		    ) );
		}
		if(!term_exists('content-improver', 'idea_template')) {
		    wp_insert_term( '🎢 Content Rephraser', 'idea_template', array(
		        'slug' => 'content-improver',
		        'description' => 'Not confident with what you wrote? Paste it in and let Berthas magic make it all better.'
		    ) );
		}
		if(!term_exists('benefit-title', 'idea_template')) {
		    wp_insert_term( '🦚 Title to Benefit Sections', 'idea_template', array(
		        'slug' => 'benefit-title',
		        'description' => 'Take a benefit of your product/service and expand it to provide additional engaging details.'
		    ) );
		}
		if(!term_exists('bullet-points', 'idea_template')) {
		    wp_insert_term( '✔ Persuasive Bullet Points', 'idea_template', array(
		        'slug' => 'bullet-points',
		        'description' => 'Convince readers that your product is the best by listing all the reasons they should take action NOW.'
		    ) );
		}
		if(!term_exists('personal-bio', 'idea_template')) {
		    wp_insert_term( '😎 Personal Bio (About Me)', 'idea_template', array(
		        'slug' => 'personal-bio',
		        'description' => "Writing about ourselves is hard. It's not for Bertha - Let her do it for you and only fix what's needed."
		    ) );
		}
		if(!term_exists('blog-post-idea', 'idea_template')) {
		    wp_insert_term( '💡 Blog Post Topic Ideas', 'idea_template', array(
		        'slug' => 'blog-post-idea',
		        'description' => 'Trained with data from hundreds of thousands of blog posts, Bertha uses this data to generate a variety of creative blog post ideas.'
		    ) );
		}
		if(!term_exists('intro-para-idea', 'idea_template')) {
		    wp_insert_term( '🦅 Blog Post Intro Paragraph', 'idea_template', array(
		        'slug' => 'intro-para-idea',
		        'description' => 'Not sure how to start writing your next winning blog post? Bertha will get the ball rolling on taking your blog post topic and generate an intriguing intro paragraph.'
		    ) );
		}
		if(!term_exists('post-outline-idea', 'idea_template')) {
		    wp_insert_term( '🧐 Blog Post Outline', 'idea_template', array(
		        'slug' => 'post-outline-idea', 
		        'description' => "Map out your blog post's outline simply by adding the title or topic of the blog post you want to create. Bertha will take care of the rest."
		    ) );
		}
		if(!term_exists('conclusion-para-idea', 'idea_template')) {
		    wp_insert_term( '🦸‍♀️ Blog Post Conclusion Paragraph', 'idea_template', array(
		        'slug' => 'conclusion-para-idea',
		        'description' => 'Bertha can write a blog post conclusion paragraph that will help your visitors stick around to read the rest of your content.'
		    ) );
		}
		if(!term_exists('blog-action-idea', 'idea_template')) {
		    wp_insert_term( '🎯 Button Call to Action', 'idea_template', array(
		        'slug' => 'blog-action-idea',
		        'description' => "With Bertha, you can generate a call to action button that's guaranteed to convert. No more guessing what words will convert best!"
		    ) );
		}
		if(!term_exists('child-input', 'idea_template')) {
		    wp_insert_term( '👶 Explain It To a Child', 'idea_template', array(
		        'slug' => 'child-input',
		        'description' => 'Taking complex concepts and simplifying them. So that everyone can get it. Get it?'
		    ) );
		}
		if(!term_exists('bertha-seo-title', 'idea_template')) {
		    wp_insert_term( '⛩️ SEO Title Tag', 'idea_template', array(
		        'slug' => 'bertha-seo-title',
		        'description' => 'Get highly optimized title tags that will help you rank higher in search engines.'
		    ) );
		}
		if(!term_exists('bertha-seo-description', 'idea_template')) {
		    wp_insert_term( '✒️ SEO Description Tag', 'idea_template', array(
		        'slug' => 'bertha-seo-description',
		        'description' => 'You are serious about SEO, But this is a tedious task that can easily be automated with Bertha.'
		    ) );
		}
		if(!term_exists('bertha-aida-marketing', 'idea_template')) {
		    wp_insert_term( '🍬 AIDA Marketing Framework', 'idea_template', array(
		        'slug' => 'bertha-aida-marketing',
		        'description' => 'Awareness > Interest > Desire > Action - Structure your writing and create more compelling content.'
		    ) );
		}
		if(!term_exists('bertha-seo-city', 'idea_template')) {
		    wp_insert_term( '🏙 SEO City Based Pages', 'idea_template', array(
		        'slug' => 'bertha-seo-city',
		        'description' => 'Generate city page titles and descriptions for your city or town pages to help rank your website locally.'
		    ) );
		}
		if(!term_exists('bertha-buisiness-name', 'idea_template')) {
		    wp_insert_term( '⚓ Business or Product Name', 'idea_template', array(
		        'slug' => 'bertha-buisiness-name',
		        'description' => 'Create a new business or product name from scratch based on a keyword or phrase.'
		    ) );
		}
		if(!term_exists('bertha-bridge', 'idea_template')) {
		    wp_insert_term( '🌉 Before, After, and Bridge', 'idea_template', array(
		        'slug' => 'bertha-bridge',
		        'description' => 'Provide a short description to generate a pain point (before), the result (after), and the solution itself (bridge).'
		    ) );
		}
		if(!term_exists('bertha-pas-framework', 'idea_template')) {
		    wp_insert_term( '🚥 PAS Framework', 'idea_template', array(
		        'slug' => 'bertha-pas-framework',
		        'description' => 'Problem > Agitate > Solution - A framework for planning and evaluating your content marketing activities.'
		    ) );
		}
		if(!term_exists('bertha-faq-list', 'idea_template')) {
		    wp_insert_term( '🙋‍♀️ FAQs List', 'idea_template', array(
		        'slug' => 'bertha-faq-list',
		        'description' => 'Generate a list of frequently asked questions for a service or product.'
		    ) );
		}
		if(!term_exists('bertha-faq-answer', 'idea_template')) {
		    wp_insert_term( '😑 FAQ Answers', 'idea_template', array(
		        'slug' => 'bertha-faq-answer',
		        'description' => 'Get an anwser to a question.'
		    ) );
		}
		if(!term_exists('bertha-summary', 'idea_template')) {
		    wp_insert_term( '🪐 Content Summary', 'idea_template', array(
		        'slug' => 'bertha-summary',
		        'description' => 'Create a summary of an article/website/blog post. Great for SEO and to share on social media.'
		    ) );
		}
		if(!term_exists('bertha-quickwins', 'idea_template')) {
		    wp_insert_term( '🤓 Ask Me Anything', 'idea_template', array(
		        'slug' => 'bertha-quickwins',
		        'description' => 'In this prompt you can ask Bertha to write about anything from email subject lines to full on blog posts and even Facebook adverts'
		    ) );
		}
		if(!term_exists('bertha-contact-blurb', 'idea_template')) {
		    wp_insert_term( '🤝 Contact Form Blurb', 'idea_template', array(
		        'slug' => 'bertha-contact-blurb',
		        'description' => 'Create a short description & Call to Action that will be used as the final persuasion text next to a contact form.'
		    ) );
		}
		if(!term_exists('bertha-seo-keyword', 'idea_template')) {
		    wp_insert_term( '🔑 SEO Keyword Suggestions', 'idea_template', array(
		        'slug' => 'bertha-seo-keyword',
		        'description' => 'Generate suggestions of long-tail keywords that are related to your topic.'
		    ) );
		}
		if(!term_exists('bertha-evil-bertha', 'idea_template')) {
		    wp_insert_term( '😈 Evil Bertha', 'idea_template', array(
		        'slug' => 'bertha-evil-bertha',
		        'description' => 'Usaully Bertha is nice and friendly, but not always...'
		    ) );
		}
		if(!term_exists('bertha-real-eastate', 'idea_template')) {
		    wp_insert_term( '🏡 Real Estate Property Listing Description', 'idea_template', array(
		        'slug' => 'bertha-real-eastate',
		        'description' => 'Detailed and enticing property listings for your real estate websites. So you can focus on the sale.'
		    ) );
		}
		if(!term_exists('bertha-press-blurb', 'idea_template')) {
		    wp_insert_term( '📰 Press Mention Blurb', 'idea_template', array(
		        'slug' => 'bertha-press-blurb',
		        'description' => 'Provide the press mention title and publication to craft a press mention blurb.'
		    ) );
		}
		if(!term_exists('bertha-case-study', 'idea_template')) {
		    wp_insert_term( '👨‍🎓 Case Study Generator (STAR Method)', 'idea_template', array(
		        'slug' => 'bertha-case-study',
		        'description' => 'Generate a case study based on a client name and a problem they wanted to solve.'
		    ) );
		}
		if(!term_exists('bertha-image-prompt', 'idea_template')) {
		    wp_insert_term( 'Improved Image Prompt', 'idea_template', array(
		        'slug' => 'bertha-image-prompt',
		        'description' => 'Generate prompts to improve image generation.'
		    ) );
		}
    }

    function bthai_setup_wizard_callback() {
    	if(!get_option('bertha_setup_wizard_added')) {
	        update_option('bertha_setup_wizard_added', true);
	        wp_safe_redirect( esc_url(admin_url( 'index.php?page=wa-free-onboard-dashboard' )) );
	        exit;
	    }

	    if(isset($_GET['bertha_success_response']) && isset($_GET['bertha_key_expires'])) {
	    	$success_response = sanitize_text_field( base64_decode($_GET['bertha_success_response']) );
	    	$expire_key = sanitize_key( base64_decode($_GET['bertha_key_expires']) );
	        update_option('WEB_ACE_DASHBOARD_license_key', $success_response);
	        update_option('WEB_ACE_DASHBOARD_license_status', 'valid');
	        update_option('WEB_ACE_DASHBOARD_license_data', $expire_key);
	        if(!isset($_GET['status'])) {
		        if(isset($_GET['page']) && $_GET['page'] == 'bertha-ai-license') {
			        wp_redirect(get_admin_url().'admin.php?'.$_SERVER['QUERY_STRING'].'&status=1');
			        exit;
			    } else {
			    	wp_redirect(get_admin_url().'post-new.php?post_type=page&'.$_SERVER['QUERY_STRING'].'&status=1');
			        exit;
			    }
	        }
	    } 

	    if ( is_plugin_active('wp-rocket/wp-rocket.php') ) {
	        $bthai_rocket_settings = get_option('wp_rocket_settings');
	        $bthai_rocket_settings['exclude_css'][] = plugin_dir_url( $this->file ) . 'assets/css/(.*).css';
	        $bthai_rocket_settings['exclude_js'][] = plugin_dir_url( $this->file ) . 'assets/js/(.*).js';
	        if(get_option('wp_bthai_check')==""){
	            update_option('wp_rocket_settings',$bthai_rocket_settings);
	            update_option('wp_bthai_check','true');
	        }
	    }
	}

	function bthai_wp9838c_timeout_extend( $time ) {
	    return 35;
	}

	function bthai_noindex_for_companies() {
	    if ( is_singular( 'idea' ) || is_tax('idea_template') ) {
	        return '<meta name="robots" content="noindex, follow">';
	    }
	}

	function bthai_filter_post_type_link( $link, $post ) {
	    if ( $post->post_type !== 'idea' )
	        return $link;

	    if ( $cats = get_the_terms($post->ID, 'idea_template') )
	        $link = str_replace('%idea_template%', array_pop($cats)->slug, $link);

	    return $link;
	}

	function bthai_scripts() {
		global $typenow;
	    $current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : ''; 

	    $disabled = $ran_word1 = $ran_word2 = '';
        if(function_exists('get_site_data_by_key')){
            $wpf_global_settings = get_site_data_by_key('wpf_global_settings');
            $get_logoid = get_site_data_by_key('wpfeedback_logo');
            if ($wpf_global_settings == 'yes') {
                $logo_class = 'ber-atarim-head';
                $get_logo_url = $get_logoid;
            } else {
                if($get_logoid!=''){
                    $logo_class = 'ber-atarim-head';
                    $get_logo_url = $get_logoid;
                } else{
                    $logo_class = '';
                    $get_logo_url = plugin_dir_url( $this->file ).'assets/images/Bertha_logo_white_small.svg';
                }
            }
        } else {
            $logo_class = '';
            $get_logo_url = plugin_dir_url( $this->file ).'assets/images/Bertha_logo_white_small.svg';
        }

        $template = '<div class="ber-offcanvas ber-offcanvas-end bertha-ai" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" aria-labelledby="ber-offcanvasExampleLabel" id="bertha_canvas"><div class="ber-offcanvas-header bertha-header"><img class="ber_close_icon" src="'.plugin_dir_url( $this->file )."assets/images/close.svg".'" data-bs-toggle="offcanvas" aria-controls="offcanvasExample" data-bs-target="#bertha_canvas"><div class="bertha_logo_container"><img src="'.$get_logo_url.'" alt="Bertha Logo" class="bertha_logo" /></div><div class="bertha_sidebar_heading"><ul class="ber-nav ber-nav-tabs" id="myTab" role="tablist"><li class="ber-nav-item" role="presentation"><button class="ber-nav-link ber-active" id="chat-tab" data-bs-toggle="tab" data-bs-target="#chat" type="button" role="tab" aria-controls="chat" aria-selected="true">Chat</button></li><li class="ber-nav-item" role="presentation"><button class="ber-nav-link" id="ama-tab" data-bs-toggle="tab" data-bs-target="#ama" type="button" role="tab" aria-controls="ama" aria-selected="true">Write</button></li><li class="ber-nav-item" role="presentation"><button class="ber-nav-link" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates" type="button" role="tab" aria-controls="templates" aria-selected="true">Prompts</button></li><li class="ber-nav-item" role="presentation"><button class="ber-nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#image" type="button" role="tab" aria-controls="image" aria-selected="true">Image</button></li><li class="ber-more-tab"><nav role="navigation"><div id="ber-more-container"><input type="checkbox" />More<ul id="berMenu"><li class="history">History</li><li class="favourite">Favourite</li>';
        if(is_admin()) $template .= '<a href="'.esc_url(admin_url( 'admin.php?page=bertha-ai-content-setting' )).'"><li class="settings">Settings</li></a><a href="'.esc_url(admin_url( 'admin.php?page=bertha-ai-license' )).'"><li class="license">License</li></a>';
        $template .= '</ul></div></nav></li></ul></div></div><div class="ber-offcanvas-body"><div class="ber_icons_wrap"><button type="button" class="ber_icon bertha-back" style="display:none;"><img src="'.plugin_dir_url( $this->file )."assets/images/arrow-left.svg".'" /></button>';
        $template .= '</div><div class="ber-tab-content" id="myTabContent"><div class="ber-tab-pane ber-fade ber-show ber-active" id="chat" role="tabpanel" aria-labelledby="chat-tab"><div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div></div><div class="ber-tab-pane ber-fade" id="ama" role="tabpanel" aria-labelledby="ama-tab"><div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div></div><div class="ber-tab-pane ber-fade" id="image" role="tabpanel" aria-labelledby="image-tab"><div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div>';
        $template .= '</div><div class="ber-tab-pane ber-fade" id="templates" role="tabpanel" aria-labelledby="templates-tab"><div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div></div><div class="ber-tab-pane ber-fade" id="audio" role="tabpanel" aria-labelledby="audio-tab"><div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div></div>';
        $template .= '</div>';
	    if(function_exists('get_site_data_by_key')){
	    	$guest_mode_on = get_option('wpf_allow_guest');
	    } else { 
	    	$guest_mode_on = 'no';
	    }

	    global $pagenow, $post;
	    $posttype = false;
	    if(isset($_GET['et_fb'])) {
	    	$posttype = true;
	    	if($post && $post->ID) $posttype_id = $post->ID;
	    	else $posttype_id = isset($_GET['page_id']) ? $_GET['page_id'] : '';

	    } else {
		    $posttype = ($pagenow == 'post-new.php' || $pagenow == 'post.php') ? true : false;
		    $posttype_id = isset($post->ID) ? $post->ID : '';
		}

		$avatar = get_avatar_url( get_current_user_id(), array( 'size' => 36 ) );

		$current_user = is_user_logged_in() ? '<img src="'.$avatar.'">' : '';


	    $Setting = get_option('bertha_ai_options') ? (array) get_option('bertha_ai_options') : array();
		$berbrand = isset($Setting['brand_name']) ? esc_attr($Setting['brand_name']) : '';
		$berdescription = isset($Setting['customer_details']) ? esc_attr($Setting['customer_details']) : '';
		$beraudience = isset($Setting['ideal_customer']) ? esc_attr($Setting['ideal_customer']) : '';
		$bersentiment = isset($Setting['sentiment']) ? esc_attr($Setting['sentiment']) : '';

		$plugin_type = $image_credits = $image_credits_used = '';
        if(BTHAI_LICENSE_KEY) {
	        $plugin_type = $this->license_details->bertha_plugin_type ? $this->license_details->bertha_plugin_type : '';
	        $image_credits = $this->license_details->image_credits ? $this->license_details->image_credits : 0;
	        $image_credits_used = $this->license_details->image_credits_used ? $this->license_details->image_credits_used : 0;
	    }


	    if($this->current_page == 'bertha-ai-art-setting') {
			wp_enqueue_script( 'ber-grid', plugin_dir_url( $this->file ) . 'assets/js/ber.grid.js', array('jquery'), '1.11.7' );
		    wp_enqueue_script( 'ber-grid-img', plugin_dir_url( $this->file ) . 'assets/js/ber.grid.min.js', array('jquery'), '1.11.7', true );
		}

	    if(is_admin() || is_user_logged_in() || $guest_mode_on == 'yes') {
		    wp_enqueue_style( 'divi-ssa', plugin_dir_url( $this->file ) . 'assets/css/ss.css', array(), '1.11.7' );
		    wp_enqueue_style( 'bertha-icon', plugin_dir_url( $this->file ) . 'assets/css/bertha-icons.css', array(), '1.11.7' );
		    wp_enqueue_style( 'divi-side1', plugin_dir_url( $this->file ) . 'assets/css/bertha-admin.css', array(), '1.11.7' );
		    wp_enqueue_style( 'divi-side3', plugin_dir_url( $this->file ) . 'assets/css/offcanvas.css', array(), '1.11.7' );
		    wp_enqueue_script( 'divi-ssajs11111', plugin_dir_url( $this->file ) . 'assets/js/typewriter.js', array(), '1.11.7' );
		    wp_enqueue_script( 'divi-ssajs1', plugin_dir_url( $this->file ) . 'assets/js/bertha-admin-min.js', array(), '1.11.7' );
	    	wp_enqueue_script( 'bertha-modal', plugin_dir_url( $this->file ) . 'assets/js/bertha-frontend-min.js', array(), '1.11.7' );
		    wp_enqueue_script( 'divi-ssajs111', plugin_dir_url( $this->file ) . 'assets/js/sidebars.js', array('jquery'), '1.11.7' );
		    wp_enqueue_script( 'berthawriter', plugin_dir_url( $this->file ) . 'assets/js/berthawriter.js', array('jquery'), '1.11.7' );

		    wp_enqueue_script( 'bertha-setup', plugin_dir_url( $this->file ) . 'assets/js/setup.js', array('jquery', 'wp-editor', 'wp-data'), '1.11.7' );
		    wp_localize_script( 'bertha-setup', 'bertha_setup_object', array( 'ajax_url' => esc_url(admin_url( 'admin-ajax.php')), 'bertha_sound' => plugin_dir_url( $this->file ).'assets/js/Bertha_Typing.mp3', 'template_nonce' => wp_create_nonce('bertha_templates_nonce'), 'new_page' => esc_url(admin_url( 'post-new.php?post_type=page' )), 'admin_email' => get_bloginfo('admin_email'), 'nonce' => wp_create_nonce( 'wp_rest' ), 'wa_template' => $template, 'current_page' => $current_page, 'onboard_page' => esc_url(admin_url( 'index.php?page=wa-onboard-dashboard' )), 'ber_settings' => array('brand' => $berbrand, 'desc' => $berdescription, 'customer' => $beraudience, 'tone' => $bersentiment), 'posttype' => $posttype, 'posttype_id' => $posttype_id, 'bertha_start_img' => plugin_dir_url( $this->file ).'assets/images/Bertha_start.gif', 'plugin_type' => $plugin_type, 'bertha_hi_img' => plugin_dir_url( $this->file ).'assets/images/Bertha_hi_black.svg', 'image_credits' => $image_credits, 'image_credits_used' => $image_credits_used, 'ber_close_img' => plugin_dir_url( $this->file ).'assets/images/close-white.svg', 'current_user' => $current_user, 'current_page' => $this->current_page, 'bertha_avatar' => plugin_dir_url( $this->file ).'assets/images/ber_chat.png', 'ber_search_img' => plugin_dir_url( $this->file ).'assets/images/search.svg', 'ber_left_img' => plugin_dir_url( $this->file ).'assets/images/arrow-left.svg', 'ber_right_img' => plugin_dir_url( $this->file ).'assets/images/arrow-right.svg', 'ber_copy_img' => plugin_dir_url( $this->file ).'assets/images/copy.svg', 'ber_heart_img' => plugin_dir_url( $this->file ).'assets/images/heart.svg', 'ber_flag_img' => plugin_dir_url( $this->file ).'assets/images/flag.svg', 'free_options' => json_decode($this->license_details->free_templates), 'art_page' => esc_url(admin_url( 'admin.php?page=bertha-ai-art-setting' )) ) );
		}

		wp_enqueue_style( 'bertha-modal', plugin_dir_url( $this->file ) . 'assets/css/bertha-modal.css', array(), '1.11.7' );
		/*lazy loading*/
		wp_enqueue_script( 'bertha-modalsss', plugin_dir_url( $this->file ) . 'assets/js/jquery.lazyscrollloading.js', array(), '1.11.7' );

	    if ( function_exists( 'get_admin_page_title' ) && get_admin_page_title() == 'Long Form Content' ) {
	    	wp_enqueue_script( 'bertha-backed-in', plugin_dir_url( $this->file ) . 'assets/js/backed-bertha.js', array(), '1.11.7' );
	    	wp_localize_script( 'bertha-backed-in', 'bertha_backedn_object', array( 'ajax_url' => esc_url(admin_url( 'admin-ajax.php')), 'draft_sound' => plugin_dir_url( $this->file ).'assets/js/Bertha_Typing.mp3', 'draft_nonce' => wp_create_nonce('bertha_draft_nonce'), 'edit_darft_nonce' => wp_create_nonce('bertha_templates_nonce')));
	    }

	    if ( 'product' === $typenow || 'download' === $typenow ) {
	        wp_enqueue_script( 'bthai-inline-edit', plugin_dir_url( $this->file ) . 'assets/js/posttype-inline.js', array('jquery'), '1.11.7' );
	        wp_localize_script( 'bthai-inline-edit', 'bertha_posttype_object', array( 'ajax_url' => esc_url(admin_url( 'admin-ajax.php')), 'description_nonce' => wp_create_nonce('bertha_description_nonce'), 'admin_url' => get_admin_url()));
	    }

	}

	function bthai_admin_scripts() {
	    global $pagenow;
	    $current_user = wp_get_current_user();
	    $Setting = get_option('bertha_ai_license_options') ? (array) get_option('bertha_ai_license_options') : array();
	    $ber_everywhere = isset($Setting['bereverywhere']) ? esc_attr($Setting['bereverywhere']) : 'yes';

		$ber_select_users = isset($Setting['ber_select_users']) ? (array) ($Setting['ber_select_users']) : array('administrator');
		$ber_frontend_backend = isset($Setting['ber_frontend_backend']) ? (array) ($Setting['ber_frontend_backend']) : array('yes', 'no');

	    if(isset( $_GET['et_fb'] ) || isset( $_GET['vc_action'] )) {
	        $visual = 'true';
	    } else {
	        $visual = 'false';
	    }
	    if(function_exists('get_site_data_by_key')){
	    	$guest_mode_on = get_option('wpf_allow_guest');
	    } else { 
	    	$guest_mode_on = 'no';
	    }
	   	$is_divi = isset( $_GET['et_fb'] ) ?  'true' : 'false';
	    $is_beaver = isset($_GET['fl_builder']) ? 'true' : 'false';
	    $is_oxygen = isset($_GET['ct_builder']) ? 'true' : 'false';
	    $is_thrive = isset($_GET['tve']) ? 'true' :'flase';
	    $is_composer = isset($_GET['vcv-action']) ? 'true' :'flase';
	    $is_elementor = (isset($_GET['action']) && $_GET['action'] == 'elementor') ? 'true' :'flase';
	    $is_admin = is_admin() ? 'true' : 'false';
	    $is_woocommerce_page = (isset($_GET['post_type']) && $_GET['post_type'] == 'product') ? 'true' : 'false';
	    if(is_admin() || is_user_logged_in() || $guest_mode_on == 'yes') {
	    	if((in_array('yes', $ber_frontend_backend) && !is_admin()) || (in_array('no', $ber_frontend_backend) && is_admin())) {
		    	if( ($current_user->roles && !empty(array_intersect($current_user->roles, $ber_select_users)) && $pagenow != 'customize.php' && $this->current_page != 'bertha-ai-art-setting') ) {
			    	wp_enqueue_script( 'divi-ssajs', plugin_dir_url( $this->file ) . 'assets/js/ss.js', array('jquery'), '1.11.7' );
			    	wp_localize_script( 'divi-ssajs', 'bertha_object', array( 'ajax_url' => esc_url(admin_url( 'admin-ajax.php')), 'bertha_sound' => plugin_dir_url( $this->file ).'assets/js/Bertha_Typing.mp3', 'current_page' => $pagenow, 'is_visual' => $visual, 'is_divi' => $is_divi, 'is_beaver' => $is_beaver, 'is_oxygen' => $is_oxygen, 'is_thrive' => $is_thrive, 'is_composer' => $is_composer, 'ber_everywhere' => $ber_everywhere, 'is_elementor' => $is_elementor, 'is_admin' => $is_admin, 'guest_mode_on' => $guest_mode_on, 'is_user_logged_in' => is_user_logged_in(), 'is_woocommerce_page' => $is_woocommerce_page ) );
			    }
			}
		}
	}

}