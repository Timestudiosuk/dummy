<?php /*
Plugin Name: WP Admin Theme CD
Plugin URI: http://www.creative-dive.de
Description: A clean and modern WordPress Admin Theme.
Version: 1.4
Author: Martin Jost
Author URI: http://www.creative-dive.de
Text Domain: wp-admin-theme-cd
Domain Path: /languages
*/

/*****************************************************************/
/* CREATE THE PLUGIN */
/*****************************************************************/

class WP_Admin_Theme_CD_Options {
 
	/*****************************************************************/
    /* ATTRIBUTES */
    /*****************************************************************/
 
    // Refers to a single instance of this class.
    private static $instance = null;
 
    // Saved options
    public $options;
 	
	/*****************************************************************/
    /* CONSTRUCTOR */
    /*****************************************************************/
 
    // Creates or returns an instance of this class.
    // @return  WP_Admin_Theme_CD_Options A single instance of this class.
    public static function get_instance() {
 
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
 
        return self::$instance;
 
    } // end get_instance;
 
    // Initializes the plugin by setting localization, filters, and administration functions.
	private function __construct() {
 
		// Add the page to the admin menu
		add_action( 'admin_menu', array( &$this, 'add_page' ) );

		// Register page options
		add_action( 'admin_init', array( &$this, 'register_page_options') );   

		// Register javascript
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_js' ) );
		
		// add support for post thumbnails
		add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );

		// Get registered option
		$this->options = get_option( 'wp_admin_theme_settings_options' );
		
    	// Load textdomain for i18n
        load_plugin_textdomain( 'wp-admin-theme-cd', null, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
	}
	
	
	/*****************************************************************/
    /* FUNCTIONS */
    /*****************************************************************/
 
    // Function that will add the options page under Setting Menu
    public function add_page() {
 
		// $page_title, $menu_title, $capability, $menu_slug, $callback_function
		add_submenu_page( 'themes.php', esc_html__( 'WP Admin Theme', 'wp-admin-theme-cd' ), esc_html__( 'WPAT Options', 'wp-admin-theme-cd' ), 'manage_options', 'wp-admin-theme-cd', array( $this, 'display_page' ) );
		
	}
 
    // Function that will display the options page.
    public function display_page() { ?>
    
		<div class="wrap wpat">

			<h1><?php echo esc_html__( 'WP Admin Theme CD - Options', 'wp-admin-theme-cd' ); ?></h1>
            
			<form action="options.php" method="post" enctype="multipart/form-data">
				<?php if ( is_multisite() ) {				
                    global $blog_id;
                    $options = get_blog_option( $blog_id, 'wp_admin_theme_settings_options' );
                }

                // error message output			
                settings_errors('wp_admin_theme_settings_options');

                // fields output				
                settings_fields('__FILE__');
                do_settings_sections('__FILE__');

                echo '<table class="form-table"><tbody><tr><th scope="row"></th><td><p class="description">';

                // manage save button visibility			
                if( empty( $options['disable_theme_options'] ) || ! empty( $options['disable_theme_options'] ) && $blog_id == 1 ) {
                    submit_button( esc_html__( 'Save Changes', 'wp-admin-theme-cd' ), 'button button-primary', 'save', false );
                } else {
                    echo '<button class="button" disabled value="">' . esc_html__( 'You have no permissions to change this options!', 'wp-admin-theme-cd' ) . '</button>';
                }
                                    
                // manage restore button visibility
                if( empty( $options['disable_theme_options'] ) || ! empty( $options['disable_theme_options'] ) && $blog_id == 1 ) {
                    submit_button( esc_html__( 'Restore all', 'wp-admin-theme-cd' ), 'button restore', 'reset', false ); 
                }

                echo '</p></td></tr></tbody></table>'; ?>
			</form>
			
		</div>
		
	<?php }
 
    // Function that will register admin page options.
    public function register_page_options() {
 
		// Add Section for option fields
		add_settings_section( 'admin_theme_section', esc_html__( 'Theme Options', 'wp-admin-theme-cd' ), array( $this, 'display_section' ), '__FILE__' );
        
            add_settings_field( 'admin_theme_spacing',                  esc_html__( 'Spacing', 'wp-admin-theme-cd' ),                       array( $this, 'admin_theme_spacing_settings' ),                 '__FILE__', 'admin_theme_section' ); // Add Spacing Option
            add_settings_field( 'admin_theme_user_box',                 esc_html__( 'User Box', 'wp-admin-theme-cd' ),                      array( $this, 'admin_theme_user_box_settings' ),                '__FILE__', 'admin_theme_section' ); // Add User Box Option
            add_settings_field( 'admin_theme_company_box',              esc_html__( 'Company Box', 'wp-admin-theme-cd' ),                   array( $this, 'admin_theme_company_box_settings' ),             '__FILE__', 'admin_theme_section' ); // Add Company Box Option
            add_settings_field( 'admin_theme_thumbnail',                esc_html__( 'Thumbnails', 'wp-admin-theme-cd' ),                    array( $this, 'admin_theme_thumbnail_settings' ),               '__FILE__', 'admin_theme_section' ); // Add Thumbnail Option
            add_settings_field( 'admin_theme_post_page_id',             esc_html__( 'Post/Page IDs', 'wp-admin-theme-cd' ),                 array( $this, 'admin_theme_post_page_id_settings' ),            '__FILE__', 'admin_theme_section' ); // Add Post/Page ID Option
            add_settings_field( 'admin_theme_google_webfont',           esc_html__( 'Custom Web Font', 'wp-admin-theme-cd' ),               array( $this, 'admin_theme_google_webfont_settings' ),          '__FILE__', 'admin_theme_section' ); // Add Google Webfont Option
            add_settings_field( 'admin_theme_credits',                  esc_html__( 'Credits', 'wp-admin-theme-cd' ),                       array( $this, 'admin_theme_credits_settings' ),                 '__FILE__', 'admin_theme_section' ); // Add Credits Option
		
		// Add Section for Toolbar
		add_settings_section( 'admin_theme_section_toolbar', esc_html__( 'Toolbar', 'wp-admin-theme-cd' ), array( $this, 'display_section_toolbar' ), '__FILE__' );		
		
            add_settings_field( 'admin_theme_toolbar',                  esc_html__( 'Toolbar', 'wp-admin-theme-cd' ),                       array( $this, 'admin_theme_toolbar_settings' ),                 '__FILE__', 'admin_theme_section_toolbar' ); // Add Hide Toolbar Option            
            add_settings_field( 'admin_theme_toolbar_wp_icon',          esc_html__( 'Toolbar WP Icon', 'wp-admin-theme-cd' ),               array( $this, 'admin_theme_toolbar_wp_icon_settings' ),         '__FILE__', 'admin_theme_section_toolbar' ); // Add Hide Toolbar WP Icon            
            add_settings_field( 'admin_theme_toolbar_icon',             esc_html__( 'Custom Toolbar Icon', 'wp-admin-theme-cd' ),           array( $this, 'admin_theme_toolbar_icon_settings' ),            '__FILE__', 'admin_theme_section_toolbar' ); // Add custom Toolbar Icon
		
		// Add Section for Colors Option
		add_settings_section( 'admin_theme_section_color', esc_html__( 'Colors', 'wp-admin-theme-cd' ), array( $this, 'display_section_colors' ), '__FILE__' );		
            
            add_settings_field( 'admin_theme_color',                    esc_html__( 'Theme Color', 'wp-admin-theme-cd' ),                   array( $this, 'admin_theme_color_settings' ),                   '__FILE__', 'admin_theme_section_color' ); // Add custom Theme Color Field            
            add_settings_field( 'admin_theme_background',               esc_html__( 'Background Gradient Color', 'wp-admin-theme-cd' ),     array( $this, 'admin_theme_background_settings' ),              '__FILE__', 'admin_theme_section_color' ); // Add custom Theme Background Gradient Color Field
		
		// Add Section for Login Option
		add_settings_section( 'admin_theme_section_login', esc_html__( 'WP Login Page', 'wp-admin-theme-cd' ), array( $this, 'display_section_login' ), '__FILE__' );
		
            add_settings_field( 'admin_theme_login_disable',            esc_html__( 'Customized Login Page', 'wp-admin-theme-cd' ),         array( $this, 'admin_theme_login_disable_settings' ),           '__FILE__', 'admin_theme_section_login' ); // Add Login Disable Option            
            add_settings_field( 'admin_theme_login_title',              esc_html__( 'Login Title', 'wp-admin-theme-cd' ),                   array( $this, 'admin_theme_login_title_settings' ),             '__FILE__', 'admin_theme_section_login' ); // Add Title Field            
            add_settings_field( 'admin_theme_logo_upload',              esc_html__( 'Logo', 'wp-admin-theme-cd' ),                          array( $this, 'admin_theme_logo_upload_settings' ),             '__FILE__', 'admin_theme_section_login' ); // Add Logo Option            
            add_settings_field( 'admin_theme_login_bg',                 esc_html__( 'Background Image', 'wp-admin-theme-cd' ),              array( $this, 'admin_theme_login_bg_settings' ),                '__FILE__', 'admin_theme_section_login' ); // Add Login BG Image Option
		
		// Add Section for Footer Information Option
		add_settings_section( 'admin_theme_section_footer', esc_html__( 'Footer Information', 'wp-admin-theme-cd' ), array( $this, 'display_section_footer' ), '__FILE__' );		
            
            add_settings_field( 'admin_theme_memory_usage',             esc_html__( 'Memory Usage', 'wp-admin-theme-cd' ),                  array( $this, 'admin_theme_memory_usage_settings' ),            '__FILE__', 'admin_theme_section_footer' ); // Add Memory Usage Option            
            add_settings_field( 'admin_theme_memory_limit',             esc_html__( 'WP Memory Limit', 'wp-admin-theme-cd' ),               array( $this, 'admin_theme_memory_limit_settings' ),            '__FILE__', 'admin_theme_section_footer' ); // Add WP Memory Limit Option            
            add_settings_field( 'admin_theme_php_version',              esc_html__( 'PHP Version', 'wp-admin-theme-cd' ),                   array( $this, 'admin_theme_php_version_settings' ),             '__FILE__', 'admin_theme_section_footer' ); // Add PHP Version Option            
            add_settings_field( 'admin_theme_ip_address',               esc_html__( 'IP Address', 'wp-admin-theme-cd' ),                    array( $this, 'admin_theme_ip_address_settings' ),              '__FILE__', 'admin_theme_section_footer' ); // Add IP Address Option            
            add_settings_field( 'admin_theme_wp_version',               esc_html__( 'WP Version', 'wp-admin-theme-cd' ),                    array( $this, 'admin_theme_wp_version_settings' ),              '__FILE__', 'admin_theme_section_footer' ); // Add WP Version Option
		
		// Add Section for Custom CSS
		add_settings_section( 'admin_theme_section_css', esc_html__( 'Custom Stylesheets', 'wp-admin-theme-cd' ), array( $this, 'display_section_css' ), '__FILE__' );		
            
            add_settings_field( 'admin_theme_css_admin',                esc_html__( 'WP Admin CSS', 'wp-admin-theme-cd' ),                  array( $this, 'admin_theme_css_admin_settings' ),               '__FILE__', 'admin_theme_section_css' ); // Add Custom CSS for WP Admin Theme            
            add_settings_field( 'admin_theme_css_login',                esc_html__( 'WP Login CSS', 'wp-admin-theme-cd' ),                  array( $this, 'admin_theme_css_login_settings' ),               '__FILE__', 'admin_theme_section_css' ); // Add Custom CSS for WP Login
		
		// Add Section for Media Support
		add_settings_section( 'admin_theme_section_media', esc_html__( 'Media', 'wp-admin-theme-cd' ), array( $this, 'display_section_media' ), '__FILE__' );		
            
            add_settings_field( 'admin_theme_wp_svg',                   esc_html__( 'SVG Support', 'wp-admin-theme-cd' ),                   array( $this, 'admin_theme_wp_svg_settings' ),                  '__FILE__', 'admin_theme_section_media' ); // Add SVG Support            
            add_settings_field( 'admin_theme_wp_ico',                   esc_html__( 'ICO Support', 'wp-admin-theme-cd' ),                   array( $this, 'admin_theme_wp_ico_settings' ),                  '__FILE__', 'admin_theme_section_media' ); // Add ICO Support
		
		// Add Section for Multisite Support
		add_settings_section( 'admin_theme_section_multisite', esc_html__( 'Multisite Support', 'wp-admin-theme-cd' ), array( $this, 'display_section_multisite' ), '__FILE__' );		
            
            add_settings_field( 'admin_theme_disable_theme_options',    esc_html__( 'Network Theme Options', 'wp-admin-theme-cd' ),         array( $this, 'admin_theme_disable_theme_options_settings' ),   '__FILE__', 'admin_theme_section_multisite' ); // Add Disable Theme Options

		// Add Section for Optimization
		add_settings_section( 'admin_theme_section_optimization', esc_html__( 'WP Optimization & Security', 'wp-admin-theme-cd' ), array( $this, 'display_section_optimization' ), '__FILE__' );		
            
            add_settings_field( 'admin_theme_wp_version_tag',           esc_html__( 'WP Version Meta-Tag', 'wp-admin-theme-cd' ),           array( $this, 'admin_theme_wp_version_tag_settings' ),          '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP Version Tag            
            add_settings_field( 'admin_theme_wp_emoji',                 esc_html__( 'WP Emoji', 'wp-admin-theme-cd' ),                      array( $this, 'admin_theme_wp_emoji_settings' ),                '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP Emoticons            
            add_settings_field( 'admin_theme_wp_feed_links',            esc_html__( 'WP RSS Feed', 'wp-admin-theme-cd' ),                   array( $this, 'admin_theme_wp_feed_links_settings' ),           '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP Feed Links            
            add_settings_field( 'admin_theme_wp_rsd_link',              esc_html__( 'WP RSD', 'wp-admin-theme-cd' ),                        array( $this, 'admin_theme_wp_rsd_link_settings' ),             '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP RSD Link            
            add_settings_field( 'admin_theme_wp_wlwmanifest',           esc_html__( 'WP Wlwmanifest', 'wp-admin-theme-cd' ),                array( $this, 'admin_theme_wp_wlwmanifest_settings' ),          '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP Wlwmanifest            
            add_settings_field( 'admin_theme_wp_shortlink',             esc_html__( 'WP Shortlink', 'wp-admin-theme-cd' ),                  array( $this, 'admin_theme_wp_shortlink_settings' ),            '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP Shortlink            
            add_settings_field( 'admin_theme_wp_rest_api',              esc_html__( 'WP REST API', 'wp-admin-theme-cd' ),                   array( $this, 'admin_theme_wp_rest_api_settings' ),             '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP Rest API            
            add_settings_field( 'admin_theme_wp_oembed',                esc_html__( 'WP oEmbed', 'wp-admin-theme-cd' ),                     array( $this, 'admin_theme_wp_oembed_settings' ),               '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP oEmbed            
            add_settings_field( 'admin_theme_wp_xml_rpc',               esc_html__( 'WP XML-RPC / X-Pingback', 'wp-admin-theme-cd' ),       array( $this, 'admin_theme_wp_xml_rpc_settings' ),              '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP XML RPC            
            add_settings_field( 'admin_theme_wp_heartbeat',             esc_html__( 'WP Heartbeat', 'wp-admin-theme-cd' ),                  array( $this, 'admin_theme_wp_heartbeat_settings' ),            '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP Heartbeat            
            add_settings_field( 'admin_theme_wp_rel_link',              esc_html__( 'WP Rel Links', 'wp-admin-theme-cd' ),                  array( $this, 'admin_theme_wp_rel_link_settings' ),             '__FILE__', 'admin_theme_section_optimization' ); // Add Remove WP Rel Link
		
		// Register Settings
		register_setting( '__FILE__', 'wp_admin_theme_settings_options', array( $this, 'validate_options' ) );
		
	}
 
    // Function that will add javascript file for Color Piker.
    public function enqueue_admin_js() {
		
		// Add color picker css
		wp_enqueue_style( 'wp-color-picker' );
		
		// Add admin style css
		wp_enqueue_style( 'wp_admin_style_custom', plugins_url( 'style.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'style.css' ), 'all' );
		
		if( is_rtl() ) {
			
			// Add admin rtl style css
			wp_enqueue_style( 'wp_admin_style_rtl', plugins_url( 'rtl-style.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'rtl-style.css' ), 'all' );
		
		}
			
		// Add admin colors css
        wp_enqueue_style( 'wp_admin_style_color', plugins_url( 'colors.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'colors.css' ), 'all' );
		
		// Add media upload js
		wp_enqueue_media();
		
		// Add admin js		
		wp_enqueue_script( 'wp_admin_script_custom', plugins_url( 'jquery.custom.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), null, true );
		
		// Avoiding flickering to reorder the first menu item (User Box) for left toolbar
		$custom_css = "#adminmenu li:first-child { display:none }";
        wp_add_inline_style( 'wp_admin_style_custom', $custom_css );
		
	}
 
    // Function that will validate all fields.
    public function validate_options( $fields ) {
 
		$valid_fields = array();

		// Validate User Box Input/Checkbox Field
		$user_box = trim( $fields['user_box'] );
		$valid_fields['user_box'] = strip_tags( stripslashes( $user_box ) );

		// Validate Company Box Input/Checkbox Field
		$company_box = trim( $fields['company_box'] );
		$valid_fields['company_box'] = strip_tags( stripslashes( $company_box ) );

		// Validate Company Box Upload Field
		$company_box_logo = trim( $fields['company_box_logo'] );
		$valid_fields['company_box_logo'] = strip_tags( stripslashes( $company_box_logo ) );
		
		// Validate Company Box Size Input/Number Field
		$company_box_logo_size = trim( $fields['company_box_logo_size'] );
		$valid_fields['company_box_logo_size'] = strip_tags( stripslashes( $company_box_logo_size ) );
		
		// Validate Thumbnail Input/Checkbox Field
		$thumbnail = trim( $fields['thumbnail'] );
		$valid_fields['thumbnail'] = strip_tags( stripslashes( $thumbnail ) );
		
		// Validate Post/Page IDs Input/Checkbox Field
		$post_page_id = trim( $fields['post_page_id'] );
		$valid_fields['post_page_id'] = strip_tags( stripslashes( $post_page_id ) );
		
		// Validate Spacing Input/Checkbox Field
		$spacing = trim( $fields['spacing'] );
		$valid_fields['spacing'] = strip_tags( stripslashes( $spacing ) );
		
		// Validate Spacing Max Width Input/Number Field
		$spacing_max_width = trim( $fields['spacing_max_width'] );
		$valid_fields['spacing_max_width'] = strip_tags( stripslashes( $spacing_max_width ) );
		
		// Validate Credits Input/Checkbox Field
		$credits = trim( $fields['credits'] );
		$valid_fields['credits'] = strip_tags( stripslashes( $credits ) );
		
		// Validate Google Webfont Input/Checkbox Field
		$google_webfont = trim( $fields['google_webfont'] );
		$valid_fields['google_webfont'] = strip_tags( stripslashes( $google_webfont ) );
		
		// Validate Google Webfont Weight Input/Checkbox Field
		$google_webfont_weight = trim( $fields['google_webfont_weight'] );
		$valid_fields['google_webfont_weight'] = strip_tags( stripslashes( $google_webfont_weight ) );
		
		// Validate Toolbar Input/Checkbox Field
		$toolbar = trim( $fields['toolbar'] );
		$valid_fields['toolbar'] = strip_tags( stripslashes( $toolbar ) );
		
		// Validate Toolbar WP Icon Upload Field
		$toolbar_wp_icon = trim( $fields['toolbar_wp_icon'] );
		$valid_fields['toolbar_wp_icon'] = strip_tags( stripslashes( $toolbar_wp_icon ) );
		
		// Validate Custom Toolbar Icon Input/Checkbox Field
		$toolbar_icon = trim( $fields['toolbar_icon'] );
		$valid_fields['toolbar_icon'] = strip_tags( stripslashes( $toolbar_icon ) );
		
		// Validate Theme Color Field
		$theme_color = trim( $fields['theme_color'] );
		$theme_color = strip_tags( stripslashes( $theme_color ) );
		
		// Validate Background Gradient Start Color Field
		$theme_background = trim( $fields['theme_background'] );
		$theme_background = strip_tags( stripslashes( $theme_background ) );
		
		// Validate Background Gradient End Color Field
		$theme_background_end = trim( $fields['theme_background_end'] );
		$theme_background_end = strip_tags( stripslashes( $theme_background_end ) );
		
		// Check if is a valid hex color
		if( FALSE === $this->check_color( $theme_color ) && FALSE === $this->check_color( $theme_background ) && FALSE === $this->check_color( $theme_background_end ) ) {

			// Set the error message
			//add_settings_error( 'wp_admin_theme_settings_options', 'theme_color_error', esc_html__( 'Insert a valid color.', 'wp-admin-theme-cd' ), 'error' ); // $setting, $code, $message, $type

			// Get the previous valid value
			$valid_fields['theme_color'] = $this->options['theme_color'];
			$valid_fields['theme_background'] = $this->options['theme_background'];
			$valid_fields['theme_background_end'] = $this->options['theme_background_end'];
			
		} else {

			$valid_fields['theme_color'] = $theme_color;
			$valid_fields['theme_background'] = $theme_background;
			$valid_fields['theme_background_end'] = $theme_background_end;
			
		}
		
		// Validate Login Disable Option
		$login_disable = trim( $fields['login_disable'] );
		$valid_fields['login_disable'] = strip_tags( stripslashes( $login_disable ) );
		
		// Validate Login Title Input/Text Field
		$login_title = trim( $fields['login_title'] );
		$valid_fields['login_title'] = strip_tags( stripslashes( $login_title ) );

		// Validate Login Logo Upload Field
		$logo_upload = trim( $fields['logo_upload'] );
		$valid_fields['logo_upload'] = strip_tags( stripslashes( $logo_upload ) );
		
		// Validate Login Logo Size Input/Number Field
		$logo_size = trim( $fields['logo_size'] );
		$valid_fields['logo_size'] = strip_tags( stripslashes( $logo_size ) );

		// Validate Login BG Image Upload Field
		$login_bg = trim( $fields['login_bg'] );
		$valid_fields['login_bg'] = strip_tags( stripslashes( $login_bg ) );
		
		// Validate Memory Usage Input/Checkbox Field
		$memory_usage = trim( $fields['memory_usage'] );
		$valid_fields['memory_usage'] = strip_tags( stripslashes( $memory_usage ) );
		
		// Validate Memory Limit Input/Checkbox Field
		$memory_limit = trim( $fields['memory_limit'] );
		$valid_fields['memory_limit'] = strip_tags( stripslashes( $memory_limit ) );
		
		// Validate PHP Version Input/Checkbox Field
		$php_version = trim( $fields['php_version'] );
		$valid_fields['php_version'] = strip_tags( stripslashes( $php_version ) );
		
		// Validate IP Adress Input/Checkbox Field
		$ip_address = trim( $fields['ip_address'] );
		$valid_fields['ip_address'] = strip_tags( stripslashes( $ip_address ) );
		
		// Validate WP Version Input/Checkbox Field
		$wp_version = trim( $fields['wp_version'] );
		$valid_fields['wp_version'] = strip_tags( stripslashes( $wp_version ) );

		// Validate Theme CSS Textarea Field
		$css_admin = trim( $fields['css_admin'] );
		$valid_fields['css_admin'] = strip_tags( stripslashes( $css_admin ) );
		
		// Validate Login CSS Textarea Field
		$css_login = trim( $fields['css_login'] );
		$valid_fields['css_login'] = strip_tags( stripslashes( $css_login ) );
		
		// Validate SVG Support Input/Checkbox Field
		$wp_svg = trim( $fields['wp_svg'] );
		$valid_fields['wp_svg'] = strip_tags( stripslashes( $wp_svg ) );
		
		// Validate ICO Support Input/Checkbox Field
		$wp_ico = trim( $fields['wp_ico'] );
		$valid_fields['wp_ico'] = strip_tags( stripslashes( $wp_ico ) );
		
		// Validate Disable Theme Options Input/Checkbox Field
		$disable_theme_options = trim( $fields['disable_theme_options'] );
		$valid_fields['disable_theme_options'] = strip_tags( stripslashes( $disable_theme_options ) );
		
		// Validate Remove WP Version Tag Input/Checkbox Field
		$wp_version_tag = trim( $fields['wp_version_tag'] );
		$valid_fields['wp_version_tag'] = strip_tags( stripslashes( $wp_version_tag ) );
		
		// Validate Remove WP Emoji Input/Checkbox Field
		$wp_emoji = trim( $fields['wp_emoji'] );
		$valid_fields['wp_emoji'] = strip_tags( stripslashes( $wp_emoji ) );
		
		// Validate Remove WP Feed Links Input/Checkbox Field
		$wp_feed_links = trim( $fields['wp_feed_links'] );
		$valid_fields['wp_feed_links'] = strip_tags( stripslashes( $wp_feed_links ) );
		
		// Validate Remove WP RSD Link Input/Checkbox Field
		$wp_rsd_link = trim( $fields['wp_rsd_link'] );
		$valid_fields['wp_rsd_link'] = strip_tags( stripslashes( $wp_rsd_link ) );
		
		// Validate Remove WP Wlwmanifest Input/Checkbox Field
		$wp_wlwmanifest = trim( $fields['wp_wlwmanifest'] );
		$valid_fields['wp_wlwmanifest'] = strip_tags( stripslashes( $wp_wlwmanifest ) );
		
		// Validate Remove WP Shortlink Input/Checkbox Field
		$wp_shortlink = trim( $fields['wp_shortlink'] );
		$valid_fields['wp_shortlink'] = strip_tags( stripslashes( $wp_shortlink ) );
		
		// Validate Remove WP REST API Input/Checkbox Field
		$wp_rest_api = trim( $fields['wp_rest_api'] );
		$valid_fields['wp_rest_api'] = strip_tags( stripslashes( $wp_rest_api ) );
		
		// Validate Remove WP oEmbed Input/Checkbox Field
		$wp_oembed = trim( $fields['wp_oembed'] );
		$valid_fields['wp_oembed'] = strip_tags( stripslashes( $wp_oembed ) );
		
		// Validate Remove WP XML-RPC Input/Checkbox Field
		$wp_xml_rpc = trim( $fields['wp_xml_rpc'] );
		$valid_fields['wp_xml_rpc'] = strip_tags( stripslashes( $wp_xml_rpc ) );
		
		// Validate Remove WP Heartbeat Input/Checkbox Field
		$wp_heartbeat = trim( $fields['wp_heartbeat'] );
		$valid_fields['wp_heartbeat'] = strip_tags( stripslashes( $wp_heartbeat ) );
		
		// Validate Remove WP Rel Links Input/Checkbox Field
		$wp_rel_link = trim( $fields['wp_rel_link'] );
		$valid_fields['wp_rel_link'] = strip_tags( stripslashes( $wp_rel_link ) );
		
		// Reset all fields to default theme options
		if( isset( $_POST['reset'] ) ) {
			
        	add_settings_error('wp_admin_theme_settings_options', 'reset_error', esc_html__( 'All fields has been restored.', 'wp-admin-theme-cd' ), 'updated' );
			
			return array(
				'user_box' => '',
				'company_box' => '',
				'company_box_logo' => '',
				'company_box_logo_size' => '140',
				'thumbnail' => '',
				'post_page_id' => '',
				'spacing' => '',
				'spacing_max_width' => '2000',
				'credits' => '',
				'google_webfont' => '',
				'google_webfont_weight' => '',
				'toolbar' => '',
				'toolbar_wp_icon' => '',
				'toolbar_icon' => '',
				'theme_color' => '',
				'theme_background' => '',
				'theme_background_end' => '',
				'login_disable' => '',
				'login_title' => esc_html__( 'Welcome Back.', 'wp-admin-theme-cd' ),
				'logo_upload' => '',
				'logo_size' => '200',
				'login_bg' => '',
				'memory_usage' => '',
				'memory_limit' => '',
				'php_version' => '',
				'ip_address' => '',
				'wp_version' => '',
				'css_admin' => esc_html( $css_admin ),
				'css_login' => esc_html( $css_login ),
				'wp_svg' => '',
				'wp_ico' => '',
				'disable_theme_options' => '',
				'wp_version_tag' => '',
				'wp_emoji' => '',
				'wp_feed_links' => '',
				'wp_rsd_link' => '',
				'wp_wlwmanifest' => '',
				'wp_shortlink' => '',
				'wp_rest_api' => '',
				'wp_oembed' => '',
				'wp_xml_rpc' => '',
				'wp_heartbeat' => '',
				'wp_rel_link' => '',
			);
    	}
		
		add_settings_error('wp_admin_theme_settings_options', 'save_updated', esc_html__('Settings saved.', 'wp-admin-theme-cd'), 'updated' );
		
		// Validate all
		return apply_filters( 'validate_options', $valid_fields, $fields);
				
	}
 
    // Function that will check if value is a valid HEX color.
    public function check_color( $value ) {
 
		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
			return true;
		}

		return false;
	}
 
    // Callback function for main settings section
    public function display_section() { 
		/* Leave blank */ 
	}
	
	// Callback function for toolbar information section
    public function display_section_toolbar() { 
		/* Leave blank */ 
	}
	
	// Callback function for colors information section
    public function display_section_colors() { 
		/* Leave blank */ 
	}
	
	// Callback function for login information section
    public function display_section_login() { 
		/* Leave blank */ 
	}
	
	// Callback function for footer information section
    public function display_section_footer() { 
		/* Leave blank */ 
	}
	
	// Callback function for custom css information section
    public function display_section_css() { 
		/* Leave blank */ 
	}
	
	// Callback function for media section
    public function display_section_media() { 
		/* Leave blank */ 
	}
	
	// Callback function for multisite support section
    public function display_section_multisite() { 
		/* Leave blank */ 
	}
	
	// Callback function for optimization section
    public function display_section_optimization() { 
		/* Leave blank */ 
	}
	
    // Functions that display the fields.
    	
	// user box
	
	public function admin_theme_user_box_settings() {
		
		if ( ! isset( $this->options['user_box'] ) ) $this->options['user_box'] = null; // first call of option is undefined
        
		if( $this->options['user_box'] ) $checked = ' checked="checked" ';  
		else $checked = '';
        
		global $user_box_is_hidden;
        $user_box_is_hidden = $this->options['user_box'];
        
		if( ! $this->options['user_box'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="user_box" name="wp_admin_theme_settings_options[user_box]" />';
		
		echo '<label for="user_box">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Display the user avatar and name before the left wordpress admin menu', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
    	
	// company box
	
	public function admin_theme_company_box_settings() {
		
		if ( ! isset( $this->options['company_box'] ) ) $this->options['company_box'] = null; // first call of option is undefined
		
		if( $this->options['company_box'] ) $checked = ' checked="checked" ';  
		else $checked = '';
        
		global $user_box_is_hidden;
        
        if( $user_box_is_hidden ) { 
            echo '<div class="wpat-inactive-option">';
        }
        
            if( ! $this->options['company_box']) { 
                $field_status = '<span class="field-status hidden">' . esc_html__( 'Disabled', 'wp-admin-theme-cd' ) . '</span>';
            } else {
                $field_status = '<span class="field-status visible">' . esc_html__( 'Enabled', 'wp-admin-theme-cd' ) . '</span>';
            }

            echo '<input type="checkbox" ' . $checked . ' id="company_box" name="wp_admin_theme_settings_options[company_box]" />';

            echo '<label for="company_box">' . esc_html__( 'Enable', 'wp-admin-theme-cd' ) . $field_status . '</label>';

            echo '<p class="description">' . esc_html__( 'Show a company box with your logo instead of the user box. The user box must be visible', 'wp-admin-theme-cd' ) . '.</p>';

            /*******/
            echo '<br>';

            if ( ! isset( $this->options['company_box_logo'] ) ) $this->options['company_box_logo'] = null; // first call of option is undefined

            $val = ( isset( $this->options['company_box_logo'] ) ) ? $this->options['company_box_logo'] : '';
            $val2 = ( isset( $this->options['company_box_logo_size'] ) ) ? $this->options['company_box_logo_size'] : '';

            echo '<label for="company_box_logo">' . esc_html__( 'Company Logo', 'wp-admin-theme-cd' ) . ' </label>';
            echo '<input type="text" id="company_box_logo" name="wp_admin_theme_settings_options[company_box_logo]" value="' . $val . '" />'; 
            echo '<input id="company_box_logo_upload_button" class="button uploader" type="button" value="' . esc_html__( 'Upload Image', 'wp-admin-theme-cd' ) . '" /> ';

            echo '<label for="company_box_logo_size" style="margin-left: 30px">' . esc_html__( 'Logo Size', 'wp-admin-theme-cd' ) . ' </label>';
            echo '<input type="number" id="company_box_logo_size" name="wp_admin_theme_settings_options[company_box_logo_size]" value="' . $val2 . '" size="10" />';
            echo '<label for="company_box_logo_size"> ' . esc_html__( 'Pixel', 'wp-admin-theme-cd' ) . '</label>';

            if( $this->options['company_box_logo'] ) {
                $bg_image = $this->options['company_box_logo'];
            } else {
                $bg_image = plugins_url('/img/no-thumb.jpg', __FILE__);
            }

            echo '<div class="img-upload-container" style="background-image:url(' . $bg_image . ')"></div>';
		
        if( $user_box ) { 
            echo '</div>';
        }
        
	}
	
	// thumbnails
	
	public function admin_theme_thumbnail_settings() {
		
		if ( ! isset( $this->options['thumbnail'] ) ) $this->options['thumbnail'] = null; // first call of option is undefined
		
		if( $this->options['thumbnail'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['thumbnail']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="thumbnail" name="wp_admin_theme_settings_options[thumbnail]" />';
		
		echo '<label for="thumbnail">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Display a thumbnail column before the title for post and page table lists', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// post/page ids
	
	public function admin_theme_post_page_id_settings() {
		
		if ( ! isset( $this->options['post_page_id'] ) ) $this->options['post_page_id'] = null; // first call of option is undefined
		
		if( $this->options['post_page_id'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['post_page_id']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="post_page_id" name="wp_admin_theme_settings_options[post_page_id]" />';
		
		echo '<label for="post_page_id">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Display a IDs column for post and page table lists', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// spacing
	
	public function admin_theme_spacing_settings() {
		
		if ( ! isset( $this->options['spacing'] ) ) $this->options['spacing'] = null; // first call of option is undefined
		
		if( $this->options['spacing'] ) $checked = ' checked="checked" '; 
		else $checked = '';        
        
        $val = ( isset( $this->options['spacing_max_width'] ) ) ? $this->options['spacing_max_width'] : '';
		
        global $spacing_is_disabled;
        $spacing_is_disabled = $this->options['spacing'];
        
		if( ! $this->options['spacing']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Enabled', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Disabled', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="spacing" name="wp_admin_theme_settings_options[spacing]" />';
		
		echo '<label for="spacing">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Remove the spacing around the backend block', 'wp-admin-theme-cd' ) . '.</p>';

        if( $spacing_is_disabled ) { 
            echo '<div class="wpat-inactive-option">';
        }
        
            /*******/
            echo '<br>';

            echo '<label for="spacing_max_width">' . esc_html__( 'Max Width', 'wp-admin-theme-cd' ) . ' </label>';
            echo '<input type="number" id="spacing_max_width" name="wp_admin_theme_settings_options[spacing_max_width]" value="' . $val . '" size="10" placeholder="2000" />';
            echo '<label for="spacing_max_width"> ' . esc_html__( 'Pixel', 'wp-admin-theme-cd' ) . '</label>';
        
        if( $spacing_is_disabled ) { 
            echo '</div>';
        }
		
	}
	
	// credits
	
	public function admin_theme_credits_settings() {
		
		if ( ! isset( $this->options['credits'] ) ) $this->options['credits'] = null; // first call of option is undefined
		
		if( $this->options['credits'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['credits']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="credits" name="wp_admin_theme_settings_options[credits]" />';
		
		echo '<label for="credits">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Remove the credits note from the footer', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// google webfont
	
	public function admin_theme_google_webfont_settings() {

		$val = ( isset( $this->options['google_webfont'] ) ) ? $this->options['google_webfont'] : '';
		$val2 = ( isset( $this->options['google_webfont_weight'] ) ) ? $this->options['google_webfont_weight'] : '';
		
		echo '<p><input type="text" id="google_webfont" name="wp_admin_theme_settings_options[google_webfont]" value="' . $val . '" size="60" placeholder="Open+Sans" />';
		
		echo '&nbsp;&nbsp;<label for="google_webfont">' . esc_html__( 'Font-Family', 'wp-admin-theme-cd' ) . '</label></p>';
		
		echo '<p><input type="text" id="google_webfont_weight" name="wp_admin_theme_settings_options[google_webfont_weight]" value="' . $val2 . '" size="60" placeholder="300,400,400i,700" />';
		
		echo '&nbsp;&nbsp;<label for="google_webfont_weight">' . esc_html__( 'Font-Weight', 'wp-admin-theme-cd' ) . '</label></p>';
		
		echo '<p class="description">' . wp_kses( __( 'Embed a custom <a target="_blank" href="https://fonts.google.com/">Google Webfont</a> to your WordPress Admin', 'wp-admin-theme-cd' ), 
			array(  
				'a' => array( 
					'href' => array(),
					'target' => array(),
				) 
			)
		) . '.</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'Please separate in Font-Name and Font-Weight like this example: [Font-Family = "Roboto"] and [Font-Weight = "400,400i,700"]', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// toolbar
	
	public function admin_theme_toolbar_settings() {
		
		if ( ! isset( $this->options['toolbar'] ) ) $this->options['toolbar'] = null; // first call of option is undefined
		
		if( $this->options['toolbar'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
        global $toolbar_is_hidden;
        $toolbar_is_hidden = $this->options['toolbar'];
        
		if( ! $this->options['toolbar']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="toolbar" name="wp_admin_theme_settings_options[toolbar]" />';
		
		echo '<label for="toolbar">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Remove the upper toolbar', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// toolbar wp icon
	
	public function admin_theme_toolbar_wp_icon_settings() {
        
        if ( ! isset( $this->options['toolbar_wp_icon'] ) ) $this->options['toolbar_wp_icon'] = null; // first call of option is undefined

        if( $this->options['toolbar_wp_icon'] ) $checked = ' checked="checked" '; 
        else $checked = '';
		
        global $toolbar_is_hidden;
        
        if( $toolbar_is_hidden ) { 
            echo '<div class="wpat-inactive-option">';
        }

            if( ! $this->options['toolbar_wp_icon']) { 
                $field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
            } else {
                $field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
            }

            echo '<input type="checkbox" ' . $checked . ' id="toolbar_wp_icon" name="wp_admin_theme_settings_options[toolbar_wp_icon]" />';

            echo '<label for="toolbar_wp_icon">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';

            echo '<p class="description">' . esc_html__( 'Remove the WordPress Icon from the upper toolbar', 'wp-admin-theme-cd' ) . '.</p>';
		
        if( $toolbar_is_hidden ) { 
            echo '</div>';
        }
        
	}
	
	// toolbar custom icon
	
	public function admin_theme_toolbar_icon_settings() {

		if ( ! isset( $this->options['toolbar_icon'] ) ) $this->options['toolbar_icon'] = null; // first call of option is undefined
		
		$val = ( isset( $this->options['toolbar_icon'] ) ) ? $this->options['toolbar_icon'] : '';
		
        global $toolbar_is_hidden;
        
        if( $toolbar_is_hidden ) { 
            echo '<div class="wpat-inactive-option">';
        }
		
            echo '<input type="text" id="toolbar_icon" name="wp_admin_theme_settings_options[toolbar_icon]" value="' . $val . '" />'; 
            echo '<input id="toolbar_icon_upload_button" class="button uploader" type="button" value="' . esc_html__( 'Upload Image', 'wp-admin-theme-cd' ) . '" /> ';

            if( $this->options['toolbar_icon'] ) {
                $bg_image = $this->options['toolbar_icon'];
            } else {
                $bg_image = plugins_url('/img/no-thumb.jpg', __FILE__);
            }

            echo '<div class="img-upload-container" style="background-image:url(' . $bg_image . ')"></div>';
            echo '<p class="description">' . esc_html__( 'Upload a custom icon instead of the WordPress icon', 'wp-admin-theme-cd' ) . '.</p>';

            echo '<small class="wpat-info">' . esc_html__( 'Recommended image size is 26 x 26px.', 'wp-admin-theme-cd' ) . '</small>';
		
        if( $toolbar_is_hidden ) { 
            echo '</div>';
        }
		
	}
 
	// theme color
	
    public function admin_theme_color_settings() {

		$val = ( isset( $this->options['theme_color'] ) ) ? $this->options['theme_color'] : '';
		echo '<input type="text" name="wp_admin_theme_settings_options[theme_color]" value="' . $val . '" class="cpa-color-picker" >';
		echo '<p class="description">' . esc_html__( 'Select your custom wp admin theme color. Default value is #4777CD', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// theme gradient start + end color
	
    public function admin_theme_background_settings() {

		$val = ( isset( $this->options['theme_background'] ) ) ? $this->options['theme_background'] : '';
		echo '<input type="text" name="wp_admin_theme_settings_options[theme_background]" value="' . $val . '" class="cpa-color-picker" >';
		echo '<label for="theme_background" class="color-picker">' . esc_html__( 'Start Color', 'wp-admin-theme-cd' ) . '</label>';
		
		$val2 = ( isset( $this->options['theme_background_end'] ) ) ? $this->options['theme_background_end'] : '';
		echo '<input type="text" name="wp_admin_theme_settings_options[theme_background_end]" value="' . $val2 . '" class="cpa-color-picker" >';
		echo '<label for="theme_background_end" class="color-picker">' . esc_html__( 'End Color', 'wp-admin-theme-cd' ) . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Select your custom wp admin theme background gradient color. Default start value is #545c63 and end value is #32373c', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// login disable
	
	public function admin_theme_login_disable_settings() {
		
		if ( ! isset( $this->options['login_disable'] ) ) $this->options['login_disable'] = null; // first call of option is undefined
		
		if( $this->options['login_disable'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
        global $login_is_disabled;
        $login_is_disabled = $this->options['login_disable'];
		
		if( ! $this->options['login_disable']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Enabled', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Disabled', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="login_disable" name="wp_admin_theme_settings_options[login_disable]" />';
		
		echo '<label for="login_disable">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'It is useful if you have an other login plugin installed. This is preventing conflicts with other plugins', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// login title
	
	public function admin_theme_login_title_settings() {
		
        $val = ( isset( $this->options['login_title'] ) ) ? $this->options['login_title'] : '';
        
        global $login_is_disabled;
        
        if( $login_is_disabled ) { 
            echo '<div class="wpat-inactive-option">';
        }
        
            echo '<input type="text" name="wp_admin_theme_settings_options[login_title]" value="' . $val . '" size="60" />';
		
        if( $login_is_disabled ) { 
            echo '</div>';
        }
        
	}
 
	// login logo + size
	
    public function admin_theme_logo_upload_settings() {

		if ( ! isset( $this->options['logo_upload'] ) ) $this->options['logo_upload'] = null; // first call of option is undefined
		
		$val = ( isset( $this->options['logo_upload'] ) ) ? $this->options['logo_upload'] : '';
		$val2 = ( isset( $this->options['logo_size'] ) ) ? $this->options['logo_size'] : '';
		
        global $login_is_disabled;
        
        if( $login_is_disabled ) { 
            echo '<div class="wpat-inactive-option">';
        }
        
            echo '<input type="text" id="logo_upload" name="wp_admin_theme_settings_options[logo_upload]" value="' . $val . '" />'; 
            echo '<input id="logo_upload_button" class="button uploader" type="button" value="' . esc_html__( 'Upload Image', 'wp-admin-theme-cd' ) . '" /> ';
            echo '<label for="logo_size" class="logo-size">' . esc_html__( 'Logo Size (Pixel)', 'wp-admin-theme-cd' ) . '</label>';
            echo '<input type="number" id="logo_size" name="wp_admin_theme_settings_options[logo_size]" value="' . $val2 . '" size="10" />';

            if( $this->options['logo_upload'] ) {
                $logo_image = $this->options['logo_upload'];
            } else {
                $logo_image = plugins_url('/img/no-thumb.jpg', __FILE__);
            }

            echo '<div class="img-upload-container" style="background-image:url(' . $logo_image . ')"></div>';
            echo '<p class="description">' . esc_html__( 'Upload an image for your WordPress login page', 'wp-admin-theme-cd' ) . '.</p>';
		
        if( $login_is_disabled ) { 
            echo '</div>';
        }
		
	}
 
	// login background image
	
    public function admin_theme_login_bg_settings() {

		if ( ! isset( $this->options['login_bg'] ) ) $this->options['login_bg'] = null; // first call of option is undefined
		
		$val = ( isset( $this->options['login_bg'] ) ) ? $this->options['login_bg'] : '';
		
        global $login_is_disabled;
        
        if( $login_is_disabled ) { 
            echo '<div class="wpat-inactive-option">';
        }
		
            echo '<input type="text" id="login_bg" name="wp_admin_theme_settings_options[login_bg]" value="' . $val . '" />'; 
            echo '<input id="login_bg_upload_button" class="button uploader" type="button" value="' . esc_html__( 'Upload Image', 'wp-admin-theme-cd' ) . '" /> ';

            if( $this->options['login_bg'] ) {
                $bg_image = $this->options['login_bg'];
            } else {
                $bg_image = plugins_url('/img/no-thumb.jpg', __FILE__);
            }

            echo '<div class="img-upload-container" style="background-image:url(' . $bg_image . ')"></div>';
            echo '<p class="description">' . esc_html__( 'Upload a background image for your WordPress login page', 'wp-admin-theme-cd' ) . '.</p>';
		
        if( $login_is_disabled ) { 
            echo '</div>';
        }
		
	}
	
	// memory usage
	
	public function admin_theme_memory_usage_settings() {
		
		if ( ! isset( $this->options['memory_usage'] ) ) $this->options['memory_usage'] = null; // first call of option is undefined
		
		if( $this->options['memory_usage'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['memory_usage']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="memory_usage" name="wp_admin_theme_settings_options[memory_usage]" />';
		
		echo '<label for="memory_usage">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Display the currently memory usage of your WordPress installation', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// memory limit
	
	public function admin_theme_memory_limit_settings() {
		
		if ( ! isset( $this->options['memory_limit'] ) ) $this->options['memory_limit'] = null; // first call of option is undefined
		
		if( $this->options['memory_limit'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['memory_limit']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="memory_limit" name="wp_admin_theme_settings_options[memory_limit]" />';
		
		echo '<label for="memory_limit">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Display the memory limit of your WordPress installation', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// php version
	
	public function admin_theme_php_version_settings() {
		
		if ( ! isset( $this->options['php_version'] ) ) $this->options['php_version'] = null; // first call of option is undefined
		
		if( $this->options['php_version'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['php_version']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="php_version" name="wp_admin_theme_settings_options[php_version]" />';
		
		echo '<label for="php_version">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Display the PHP version of your server', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// ip address
	
	public function admin_theme_ip_address_settings() {
		
		if ( ! isset( $this->options['ip_address'] ) ) $this->options['ip_address'] = null; // first call of option is undefined
		
		if( $this->options['ip_address'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['ip_address']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="ip_address" name="wp_admin_theme_settings_options[ip_address]" />';
		
		echo '<label for="ip_address">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Display the IP address of your server', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// wp version
	
	public function admin_theme_wp_version_settings() {
		
		if ( ! isset( $this->options['wp_version'] ) ) $this->options['wp_version'] = null; // first call of option is undefined
		
		if( $this->options['wp_version'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_version']) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Visible', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Hidden', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_version" name="wp_admin_theme_settings_options[wp_version]" />';
		
		echo '<label for="wp_version">' . esc_html__( 'Hide', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Display the installed WordPress version', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// theme css
	
	public function admin_theme_css_admin_settings() {

		$val = ( isset( $this->options['css_admin'] ) ) ? $this->options['css_admin'] : '';
		echo '<textarea class="option-textarea" type="text" name="wp_admin_theme_settings_options[css_admin]" placeholder=".your-class { color: blue }" />' . $val . '</textarea>';
		
		echo '<p class="description">' . esc_html__( 'Add custom CSS for the Wordpress admin theme. To overwrite some classes, use "!important". Like this example "border-right: 3px!important"', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
		
	// login css
	
	public function admin_theme_css_login_settings() {

		$val = ( isset( $this->options['css_login'] ) ) ? $this->options['css_login'] : '';
		echo '<textarea class="option-textarea" type="text" name="wp_admin_theme_settings_options[css_login]" placeholder=".your-class { color: blue }" />' . $val . '</textarea>';
		
		echo '<p class="description">' . esc_html__( 'Add custom CSS for the Wordpress login page. To overwrite some classes, use "!important". Like this example "border-right: 3px!important"', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// wp svg
	
	public function admin_theme_wp_svg_settings() {
		
		if ( ! isset( $this->options['wp_svg'] ) ) $this->options['wp_svg'] = null; // first call of option is undefined
		
		if( $this->options['wp_svg'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_svg'] ) { 
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Deactivated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_svg" name="wp_admin_theme_settings_options[wp_svg]" />';
		
		echo '<label for="wp_svg">' . esc_html__( 'Enable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Allow the upload of SVG files', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// wp ico
	
	public function admin_theme_wp_ico_settings() {
		
		if ( ! isset( $this->options['wp_ico'] ) ) $this->options['wp_ico'] = null; // first call of option is undefined
		
		if( $this->options['wp_ico'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_ico'] ) { 
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Deactivated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_ico" name="wp_admin_theme_settings_options[wp_ico]" />';
		
		echo '<label for="wp_ico">' . esc_html__( 'Enable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Allow the upload of ICO files', 'wp-admin-theme-cd' ) . '.</p>';
		
	}
	
	// disable theme options (multisite)
	
	public function admin_theme_disable_theme_options_settings() {
		
		if ( ! isset( $this->options['disable_theme_options'] ) ) $this->options['disable_theme_options'] = null; // first call of option is undefined
		
		if( $this->options['disable_theme_options'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['disable_theme_options']) { 
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Deactivated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		global $blog_id;
		
		if ( is_multisite() && $blog_id == 1 ) {
			echo '<input type="checkbox" ' . $checked . ' id="disable_theme_options" name="wp_admin_theme_settings_options[disable_theme_options]" />';
		} else {
			echo '<input type="checkbox" ' . $checked . ' id="#" name="#" disabled="disabled" />';
		}
		
		echo '<label for="disable_theme_options">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Disable the permissions to change WP Admin Theme options for all other network sites', 'wp-admin-theme-cd' ) . '.</p>';
		
		if ( ! is_multisite() ) {
			echo '<small class="wpat-info">' . esc_html__( 'Activate multisite support for WordPress to use this option', 'wp-admin-theme-cd' ) . '.</small>';
		} 
		
	}
	
	// remove wp version meta tag
	
	public function admin_theme_wp_version_tag_settings() {
		
		if ( ! isset( $this->options['wp_version_tag'] ) ) $this->options['wp_version_tag'] = null; // first call of option is undefined
		
		if( $this->options['wp_version_tag'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_version_tag'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_version_tag" name="wp_admin_theme_settings_options[wp_version_tag]" />';
		
		echo '<label for="wp_version_tag">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Remove the WordPress Version Meta-Tag from wp head', 'wp-admin-theme-cd' ) . '.</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'Show the version number of your currently installed WordPress in the source code.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp emoticons
	
	public function admin_theme_wp_emoji_settings() {
		
		if ( ! isset( $this->options['wp_emoji'] ) ) $this->options['wp_emoji'] = null; // first call of option is undefined
		
		if( $this->options['wp_emoji'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_emoji'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_emoji" name="wp_admin_theme_settings_options[wp_emoji]" />';
		
		echo '<label for="wp_emoji">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Remove the WordPress Emoticons from your source code.', 'wp-admin-theme-cd' ) . '</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'Display a textual portrayals like ";-)" as a emoticon icon.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp feed links
	
	public function admin_theme_wp_feed_links_settings() {
		
		if ( ! isset( $this->options['wp_feed_links'] ) ) $this->options['wp_feed_links'] = null; // first call of option is undefined
		
		if( $this->options['wp_feed_links'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_feed_links'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_feed_links" name="wp_admin_theme_settings_options[wp_feed_links]" />';
		
		echo '<label for="wp_feed_links">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Disable the RSS feed functionality and remove the WordPress page and comments RSS feed links from wp head', 'wp-admin-theme-cd' ) . '.</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'RSS (Really Simple Syndication) is a type of web feed which allows users to access updates to online content in a standardized, computer-readable format.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp rsd link
	
	public function admin_theme_wp_rsd_link_settings() {
		
		if ( ! isset( $this->options['wp_rsd_link'] ) ) $this->options['wp_rsd_link'] = null; // first call of option is undefined
		
		if( $this->options['wp_rsd_link'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_rsd_link'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_rsd_link" name="wp_admin_theme_settings_options[wp_rsd_link]" />';
		
		echo '<label for="wp_rsd_link">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Remove the RSD link from wp head.', 'wp-admin-theme-cd' ) . '</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'Really Simple Discovery (RSD) is an XML format and a publishing convention for making services exposed by a blog, or other web software, discoverable by client software.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp wlwmanifest
	
	public function admin_theme_wp_wlwmanifest_settings() {
		
		if ( ! isset( $this->options['wp_wlwmanifest'] ) ) $this->options['wp_wlwmanifest'] = null; // first call of option is undefined
		
		if( $this->options['wp_wlwmanifest'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_wlwmanifest'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_wlwmanifest" name="wp_admin_theme_settings_options[wp_wlwmanifest]" />';
		
		echo '<label for="wp_wlwmanifest">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Remove the Wlwmanifest link from wp head.', 'wp-admin-theme-cd' ) . '</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'Needed to enable tagging support for Windows Live Writer.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp shortlink
	
	public function admin_theme_wp_shortlink_settings() {
		
		if ( ! isset( $this->options['wp_shortlink'] ) ) $this->options['wp_shortlink'] = null; // first call of option is undefined
		
		if( $this->options['wp_shortlink'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_shortlink'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_shortlink" name="wp_admin_theme_settings_options[wp_shortlink]" />';
		
		echo '<label for="wp_shortlink">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Remove the shortlink link from wp head.', 'wp-admin-theme-cd' ) . '</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'Shortlink is a shorten version of a web page’s URL.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp rest api
	
	public function admin_theme_wp_rest_api_settings() {
		
		if ( ! isset( $this->options['wp_rest_api'] ) ) $this->options['wp_rest_api'] = null; // first call of option is undefined
		
		if( $this->options['wp_rest_api'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_rest_api'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_rest_api" name="wp_admin_theme_settings_options[wp_rest_api]" />';
		
		echo '<label for="wp_rest_api">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Disable the REST API and remove the wp json link from wp head.', 'wp-admin-theme-cd' ) . '</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'The API makes it super easy to retrieve data using GET requests, which is useful for those building apps with WordPress.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp oembed
	
	public function admin_theme_wp_oembed_settings() {
		
		if ( ! isset( $this->options['wp_oembed'] ) ) $this->options['wp_oembed'] = null; // first call of option is undefined
		
		if( $this->options['wp_oembed'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_oembed'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_oembed" name="wp_admin_theme_settings_options[wp_oembed]" />';
		
		echo '<label for="wp_oembed">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Disable wp embed and remove the oEmbed links from wp head.', 'wp-admin-theme-cd' ) . '</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'oEmbed feature which allows others to embed your WordPress posts into their own site by adding the post URL.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp xml-rpc
	
	public function admin_theme_wp_xml_rpc_settings() {
		
		if ( ! isset( $this->options['wp_xml_rpc'] ) ) $this->options['wp_xml_rpc'] = null; // first call of option is undefined
		
		if( $this->options['wp_xml_rpc'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_xml_rpc'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_xml_rpc" name="wp_admin_theme_settings_options[wp_xml_rpc]" />';
		
		echo '<label for="wp_xml_rpc">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Disable remote access.', 'wp-admin-theme-cd' ) . '</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'XML-RPC is a remote procedure call which uses XML to encode its calls and HTTP as a transport mechanism. If you want to access and publish to your blog remotely, then you need XML-RPC enabled. XML-RPC protocol is used by WordPress as API for Pingbacks and third-party applications, such as mobile apps, inter-blog communication and popular plugins like JetPack.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp heartbeat
	
	public function admin_theme_wp_heartbeat_settings() {
		
		if ( ! isset( $this->options['wp_heartbeat'] ) ) $this->options['wp_heartbeat'] = null; // first call of option is undefined
		
		if( $this->options['wp_heartbeat'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_heartbeat'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_heartbeat" name="wp_admin_theme_settings_options[wp_heartbeat]" />';
		
		echo '<label for="wp_heartbeat">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Stop the heartbeat updates.', 'wp-admin-theme-cd' ) . '</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'The Heartbeat API is a simple server polling API built in to WordPress, allowing near-real-time frontend updates. The heartbeat API allows for regular communication between the users browser and the server. One of the original motivations was to allow for locking posts and warning users when more than one user is attempting to edit a post, or warning the user when their log-in has expired.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
	// remove wp rel_link
	
	public function admin_theme_wp_rel_link_settings() {
		
		if ( ! isset( $this->options['wp_rel_link'] ) ) $this->options['wp_rel_link'] = null; // first call of option is undefined
		
		if( $this->options['wp_rel_link'] ) $checked = ' checked="checked" '; 
		else $checked = '';
		
		if( ! $this->options['wp_rel_link'] ) { 
			$field_status = '<span class="field-status visible">' . esc_html__( 'Activated', 'wp-admin-theme-cd' ) . '</span>';
 		} else {
			$field_status = '<span class="field-status hidden">' . esc_html__( 'Removed', 'wp-admin-theme-cd' ) . '</span>';
		}
		
		echo '<input type="checkbox" ' . $checked . ' id="wp_rel_link" name="wp_admin_theme_settings_options[wp_rel_link]" />';
		
		echo '<label for="wp_rel_link">' . esc_html__( 'Disable', 'wp-admin-theme-cd' ) . $field_status . '</label>';
		
		echo '<p class="description">' . esc_html__( 'Remove the post rel index / start / parent / prev / next links from wp head.', 'wp-admin-theme-cd' ) . '</p>';
		
		echo '<small class="wpat-info">' . esc_html__( 'This feature display the URL of the index, start, parent, previous and next post in the source code.', 'wp-admin-theme-cd' ) . '</small>';
		
	}
	
 
} // end class
 
WP_Admin_Theme_CD_Options::get_instance(); 


/*****************************************************************/
/* GET OPTIONS */
/*****************************************************************/

$wp_options = get_option( 'wp_admin_theme_settings_options' );

$wpat_user_box = $wp_options['user_box'];
$wpat_company_box = $wp_options['company_box'];
$wpat_company_box_logo = $wp_options['company_box_logo'];
$wpat_company_box_logo_size = $wp_options['company_box_logo_size'];
$wpat_thumbnail = $wp_options['thumbnail'];
$wpat_post_page_id = $wp_options['post_page_id'];
$wpat_spacing = $wp_options['spacing'];
$wpat_spacing_max_width = $wp_options['spacing_max_width'];
$wpat_credits = $wp_options['credits'];
$wpat_google_webfont = $wp_options['google_webfont'];
$wpat_google_webfont_weight = $wp_options['google_webfont_weight'];
$wpat_toolbar = $wp_options['toolbar'];
$wpat_toolbar_wp_icon = $wp_options['toolbar_wp_icon'];
$wpat_toolbar_icon = $wp_options['toolbar_icon'];
$wpat_theme_color = $wp_options['theme_color'];
$wpat_theme_background = $wp_options['theme_background'];
$wpat_theme_background_end = $wp_options['theme_background_end'];
$wpat_login_disable = $wp_options['login_disable'];
$wpat_login_title = $wp_options['login_title'];
$wpat_logo_upload = $wp_options['logo_upload'];
$wpat_logo_size = $wp_options['logo_size'];
$wpat_login_bg = $wp_options['login_bg'];
$wpat_memory_usage = $wp_options['memory_usage'];
$wpat_memory_limit = $wp_options['memory_limit'];
$wpat_php_version = $wp_options['php_version'];
$wpat_ip_address = $wp_options['ip_address'];
$wpat_wp_version = $wp_options['wp_version'];
$wpat_css_admin = $wp_options['css_admin'];
$wpat_css_login = $wp_options['css_login'];
$wpat_wp_svg = $wp_options['wp_svg'];
$wpat_wp_ico = $wp_options['wp_ico'];
$wpat_disable_theme_options = $wp_options['disable_theme_options'];
$wpat_wp_version_tag = $wp_options['wp_version_tag'];
$wpat_wp_emoji = $wp_options['wp_emoji'];
$wpat_wp_feed_links = $wp_options['wp_feed_links'];
$wpat_wp_rsd_link = $wp_options['wp_rsd_link'];
$wpat_wp_wlwmanifest = $wp_options['wp_wlwmanifest'];
$wpat_wp_shortlink = $wp_options['wp_shortlink'];
$wpat_wp_rest_api = $wp_options['wp_rest_api'];
$wpat_wp_oembed = $wp_options['wp_oembed'];
$wpat_wp_xml_rpc = $wp_options['wp_xml_rpc'];
$wpat_wp_heartbeat = $wp_options['wp_heartbeat'];
$wpat_wp_rel_link = $wp_options['wp_rel_link'];

// first call of option is undefined
if ( ! isset( $wpat_user_box ) ) $wpat_user_box = null;
if ( ! isset( $wpat_company_box ) ) $wpat_company_box = null;
if ( ! isset( $wpat_company_box_logo ) ) $wpat_company_box_logo = null;
if ( ! isset( $wpat_company_box_logo_size ) ) $wpat_company_box_logo_size = null;
if ( ! isset( $wpat_thumbnail ) ) $wpat_thumbnail = null;
if ( ! isset( $wpat_post_page_id ) ) $wpat_post_page_id = null;
if ( ! isset( $wpat_spacing ) ) $wpat_spacing = null;
if ( ! isset( $wpat_spacing_max_width ) ) $wpat_spacing_max_width = null;
if ( ! isset( $wpat_credits ) ) $wpat_credits = null;
if ( ! isset( $wpat_google_webfont ) ) $wpat_google_webfont = null;
if ( ! isset( $wpat_google_webfont_weight ) ) $wpat_google_webfont_weight = null;
if ( ! isset( $wpat_toolbar ) ) $wpat_toolbar = null;
if ( ! isset( $wpat_toolbar_wp_icon ) ) $wpat_toolbar_wp_icon = null;
if ( ! isset( $wpat_toolbar_icon ) ) $wpat_toolbar_icon = null;
if ( ! isset( $wpat_theme_color ) ) $wpat_theme_color = null;
if ( ! isset( $wpat_theme_background ) ) $wpat_theme_background = null;
if ( ! isset( $wpat_theme_background_end ) ) $wpat_theme_background_end = null;
if ( ! isset( $wpat_login_disable ) ) $wpat_login_disable = null;
if ( ! isset( $wpat_login_title ) ) $wpat_login_title = null;
if ( ! isset( $wpat_logo_upload ) ) $wpat_logo_upload = null;
if ( ! isset( $wpat_logo_size ) ) $wpat_logo_size = null;
if ( ! isset( $wpat_login_bg ) ) $wpat_login_bg = null;
if ( ! isset( $wpat_memory_usage ) ) $wpat_memory_usage = null;
if ( ! isset( $wpat_memory_limit ) ) $wpat_memory_limit = null;
if ( ! isset( $wpat_php_version ) ) $wpat_php_version = null;
if ( ! isset( $wpat_ip_address ) ) $wpat_ip_address = null;
if ( ! isset( $wpat_wp_version ) ) $wpat_wp_version = null;
if ( ! isset( $wpat_css_admin ) ) $wpat_css_admin = null;
if ( ! isset( $wpat_css_login ) ) $wpat_css_login = null;
if ( ! isset( $wpat_wp_svg ) ) $wpat_wp_svg = null;
if ( ! isset( $wpat_wp_ico ) ) $wpat_wp_ico = null;
if ( ! isset( $wpat_disable_theme_options ) ) $wpat_disable_theme_options = null;
if ( ! isset( $wpat_wp_version_tag ) ) $wpat_wp_version_tag = null;
if ( ! isset( $wpat_wp_emoji ) ) $wpat_wp_emoji = null;
if ( ! isset( $wpat_wp_feed_links ) ) $wpat_wp_feed_links = null;
if ( ! isset( $wpat_wp_rsd_link ) ) $wpat_wp_rsd_link = null;
if ( ! isset( $wpat_wp_wlwmanifest ) ) $wpat_wp_wlwmanifest = null;
if ( ! isset( $wpat_wp_shortlink ) ) $wpat_wp_shortlink = null;
if ( ! isset( $wpat_wp_rest_api ) ) $wpat_wp_rest_api = null;
if ( ! isset( $wpat_wp_oembed ) ) $wpat_wp_oembed = null;
if ( ! isset( $wpat_wp_xml_rpc ) ) $wpat_wp_xml_rpc = null;
if ( ! isset( $wpat_wp_heartbeat ) ) $wpat_wp_heartbeat = null;
if ( ! isset( $wpat_wp_rel_link ) ) $wpat_wp_rel_link = null;


/*****************************************************************/
/* GENERATE THE CUSTOM CSS / JS FILE */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_generate_custom_css_js' ) ) :

	function wp_admin_theme_cd_generate_custom_css_js() {
		
		global $wp_filesystem;
		WP_Filesystem(); // Initial WP file system

		ob_start();
		require_once( dirname( __FILE__ ) . '/colors.php' );
		$css = ob_get_clean();
		$wp_filesystem->put_contents( dirname( __FILE__ ) . '/colors.css', $css, 0644 );
		
		ob_start();
		require_once( dirname( __FILE__ ) . '/login.php' );
		$css = ob_get_clean();
		$wp_filesystem->put_contents( dirname( __FILE__ ) . '/login.css', $css, 0644 );
		
	}

endif;

add_action( 'admin_init', 'wp_admin_theme_cd_generate_custom_css_js' );


/*****************************************************************/
/* ADD FRONTEND CSS */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_frontend_css' ) ) :

	function wp_admin_theme_cd_frontend_css()	{

		require_once(ABSPATH . '/wp-includes/pluggable.php');
		
		if( is_user_logged_in() ) {
			wp_register_style( 'wp-admin-theme-cd-style', plugins_url( 'frontend.css', __FILE__ ), array(), null, 'all' );
			wp_enqueue_style ( 'wp-admin-theme-cd-style' );
		}
	}

endif;

add_action( 'wp_enqueue_scripts', 'wp_admin_theme_cd_frontend_css', 30 );


/*****************************************************************/
/* LOADING GOOGLE WEB FONTS */
/*****************************************************************/

if( $wpat_google_webfont ) {

	if ( ! function_exists( 'wp_admin_theme_cd_webfonts_url' ) ) :

		function wp_admin_theme_cd_webfonts_url( $font_style = '' ) {

			global $wpat_google_webfont, $wpat_google_webfont_weight;
			
			$selected_fonts = '';
			
			// get custom font name
			$selected_fonts .= $wpat_google_webfont;
			
			// check if custom font weight exist
			if( ! empty( $wpat_google_webfont_weight ) ) {													
				$selected_fonts .= ':' . $wpat_google_webfont_weight;
			}
			
			$font_style = add_query_arg( 'family', esc_html( $selected_fonts ), "//fonts.googleapis.com/css" );

			return $font_style;
		}

	endif;


	if ( ! function_exists( 'wp_admin_theme_cd_webfonts_output' ) ) :

		function wp_admin_theme_cd_webfonts_output() {

			wp_enqueue_style( 'wp_admin_theme_cd_webfonts', wp_admin_theme_cd_webfonts_url(), array(), null, 'all' );

		}

	endif;

	add_action( 'admin_enqueue_scripts', 'wp_admin_theme_cd_webfonts_output', 30 );

}


/*****************************************************************/
/* ADD BODY CLASSES */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_body_class' ) ) :

	function wp_admin_theme_cd_body_class( $classes ) {

        global $wpat_spacing, $wpat_toolbar;
        
		if( ! $wpat_spacing ) { 
			$wp_admin_spacing = 'wp-admin-spacing ';
		} else {
            $wp_admin_spacing = false;
        }
        
        if( $wpat_toolbar ) { 
			$wpat_admin_toolbar = 'wp-admin-toolbar-hide ';
		} else {
            $wpat_admin_toolbar = false;
        }

		return $classes . $wp_admin_spacing . $wpat_admin_toolbar;

	}

endif;

add_filter( 'admin_body_class', 'wp_admin_theme_cd_body_class' );


/*****************************************************************/
/* MULTISITE - SET OPTIONS FOR ALL NETWORK BLOGS */
/*****************************************************************/

// add multisite options update

if ( ! function_exists( 'wp_admin_theme_cd_update_blog' ) ) :

	function wp_admin_theme_cd_update_blog( $blog_id = null ) {

		if ( is_multisite() ) {
			if ( $blog_id ) {
				switch_to_blog( $blog_id );
			}

			// get options from main blog (ID = 1)
			$blog_id = 1;
			$options = get_blog_option( $blog_id, 'wp_admin_theme_settings_options', array() );

			// manage options
			// options from all network sites = options from blog ID 1
			$options['user_box'] = $options['user_box'];
			$options['company_box'] = $options['company_box'];
			$options['company_box_logo'] = $options['company_box_logo'];
			$options['company_box_logo_size'] = $options['company_box_logo_size'];
			$options['thumbnail'] = $options['thumbnail'];
			$options['post_page_id'] = $options['post_page_id'];
			$options['spacing'] = $options['spacing'];
			$options['spacing_max_width'] = $options['spacing_max_width'];
			$options['credits'] = $options['credits'];
			$options['google_webfont'] = $options['google_webfont'];
			$options['google_webfont_weight'] = $options['google_webfont_weight'];
			$options['toolbar'] = $options['toolbar'];
			$options['toolbar_wp_icon'] = $options['toolbar_wp_icon'];
			$options['toolbar_icon'] = $options['toolbar_icon'];
			$options['theme_color'] = $options['theme_color'];
			$options['theme_background'] = $options['theme_background'];
			$options['theme_background_end'] = $options['theme_background_end'];
			$options['login_disable'] = $options['login_disable'];
			$options['login_title'] = $options['login_title'];
			$options['logo_upload'] = $options['logo_upload'];
			$options['logo_size'] = $options['logo_size'];
			$options['login_bg'] = $options['login_bg'];
			$options['memory_usage'] = $options['memory_usage'];
			$options['memory_limit'] = $options['memory_limit'];
			$options['php_version'] = $options['php_version'];
			$options['ip_address'] = $options['ip_address'];
			$options['wp_version'] = $options['wp_version'];
			$options['css_admin'] = $options['css_admin'];
			$options['css_login'] = $options['css_login'];
			$options['wp_svg'] = $options['wp_svg'];
			$options['wp_ico'] = $options['wp_ico'];
			$options['disable_theme_options'] = $options['disable_theme_options'];
			$options['wp_version_tag'] = $options['wp_version_tag'];		
			$options['wp_emoji'] = $options['wp_emoji'];		
			$options['wp_feed_links'] = $options['wp_feed_links'];	
			$options['wp_rsd_link'] = $options['wp_rsd_link'];		
			$options['wp_wlwmanifest'] = $options['wp_wlwmanifest'];	
			$options['wp_shortlink'] = $options['wp_shortlink'];		
			$options['wp_rest_api'] = $options['wp_rest_api'];		
			$options['wp_oembed'] = $options['wp_oembed'];		
			$options['wp_xml_rpc'] = $options['wp_xml_rpc'];	
			$options['wp_heartbeat'] = $options['wp_heartbeat'];
			$options['wp_rel_link'] = $options['wp_rel_link'];	

			// update options
			update_option('wp_admin_theme_settings_options', $options);

			if ( $blog_id ) {
				restore_current_blog();
			}
		}

	}

endif;


/*****************************************************************/
/* IMPORT / EXPORT */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_export_admin_menu' ) ) :

	function wp_admin_theme_cd_export_admin_menu() {
			
		add_submenu_page(
			'themes.php',
			esc_html__( 'WP Admin Theme CD - Import & Export', 'wp-admin-theme-cd' ),
			esc_html__( 'WPAT Im-/Export', 'wp-admin-theme-cd' ),
			'manage_options',
			'wp-admin-theme-cd-export',
			'wp_admin_theme_cd_export_page'
		);
		
	}

	add_action( 'admin_menu', 'wp_admin_theme_cd_export_admin_menu' );

	function wp_admin_theme_cd_export_page() { 
		if( ! isset($_POST['export']) ) { 
			global $message; ?>
	
			<div class="wrap">
				<h1><?php echo esc_html__( 'WP Admin Theme CD - Import & Export', 'wp-admin-theme-cd' ); ?></h1>

				<?php if ($message) { ?>
					<div class="updated"><p><strong><?php echo esc_html( $message ); ?></strong></p></div>
				<?php } ?>
			
				<h2><?php esc_html_e( 'Export', 'wp-admin-theme-cd' ); ?></h2>
				
				<p><?php esc_html_e( 'When you click the Export button, the system will generate a JSON file for you to save on your computer.', 'wp-admin-theme-cd' ); ?></p>
				<p><?php esc_html_e( 'This backup file contains all WP Admin Theme configution and setting options from this WordPress installation.', 'wp-admin-theme-cd' ); ?></p>
				<p><?php esc_html_e( 'After exporting, you can either use the JSON file to restore your settings on this site again or another WordPress site.', 'wp-admin-theme-cd' ); ?></p>
				
				<form method="post">
					<?php wp_nonce_field('wpat-export'); ?>
					<input class="button" type="submit" name="export" value="<?php esc_html_e( 'Export WP Admin Theme options', 'wp-admin-theme-cd' ); ?>">
				</form>
					
				<h2><?php esc_html_e( 'Import', 'wp-admin-theme-cd' ); ?></h2>
				<?php
					if( isset( $_FILES['import'] ) && check_admin_referer('wpat-import') ) {
						if( $_FILES['import']['error'] > 0 ) {
							
							echo '<div class="error"><p><strong>' . esc_html__( 'An error occurred.', 'wp-admin-theme-cd' ) . '</strong></p></div>';
							wp_die();
							
						} else {
							
							$file_name = $_FILES['import']['name']; // Get the name of file
							$file_ext = strtolower(end(explode(".", $file_name))); // Get extension of file
							$file_size = $_FILES['import']['size']; // Get size of file
							
							// Ensure uploaded file is JSON file type and the size not over 500000 bytes
							if( ($file_ext == "json") && ($file_size < 500000) ) {
								$encode_options = file_get_contents($_FILES['import']['tmp_name']);
								$options = json_decode($encode_options, true);
								foreach ($options as $key => $value) {
									update_option($key, $value);
								}
								$message = esc_html__( 'All options are restored successfully.', 'wp-admin-theme-cd' );
							} else {
								$message = esc_html__( 'Invalid file or file size too big.', 'wp-admin-theme-cd' );
							}
							
						}
					}
				?>
				
				<p><?php esc_html_e( 'Click the Browse button and choose a JSON file.', 'wp-admin-theme-cd' ); ?></p>
				<p><?php esc_html_e( 'Press the Import button restore all options.', 'wp-admin-theme-cd' ); ?></p>
				
				<form method="post" enctype="multipart/form-data">
					<p class="submit">
						<?php wp_nonce_field('wpat-import'); ?>
						<input type="file" name="import" />
						<input class="button button-primary" type="submit" name="submit" value="<?php esc_html_e( 'Import options', 'wp-admin-theme-cd' ); ?>">
					</p>
				</form>		

			</div>
		
		<?php } elseif( check_admin_referer('wpat-export') ) {
			
			$blogname = str_replace(" ", "", get_option('blogname'));
			$date = date("m-d-Y");
			$json_name = $blogname."-".$date; // Namming the filename will be generated.

			//$options = get_alloptions(); // Get all options data, return array
			$options = array( 'wp_admin_theme_settings_options' => get_option('wp_admin_theme_settings_options') ); // Get specific options data
			//$options = array( 'test' => get_option('test'), 'test2' => get_option('test2') ); // Get specific options data
			
			foreach ($options as $key => $value) {
				$value = maybe_unserialize($value);
				$need_options[$key] = $value;
			}

			$json_file = json_encode($need_options); // Encode data into json data

			ob_clean();
			echo $json_file;
			header("Content-Type: text/json; charset=" . get_option( 'blog_charset'));
			header("Content-Disposition: attachment; filename=$json_name.json");
			exit();
			
		}
			
	}

endif;


/*****************************************************************/
/* MULTISITE */
/*****************************************************************/

// create multisite update admin page

if ( ! function_exists( 'wp_admin_theme_cd_update_admin_menu' ) ) :

	function wp_admin_theme_cd_update_admin_menu() {
		
		if ( is_multisite() ) {
			
			global $blog_id;
			$options = get_blog_option( $blog_id, 'wp_admin_theme_settings_options' );

			// hide this page, if "disable theme options" is true
			// the page will be displayed for blog ID 1 only
			if( ! empty( $options['disable_theme_options'] ) && $blog_id == 1 || $blog_id == 1 ) {
			
				add_submenu_page(
					'themes.php',
					esc_html__( 'WP Admin Theme CD - Multisite Update', 'wp-admin-theme-cd' ),
					esc_html__( 'WPAT Multisite', 'wp-admin-theme-cd' ),
					'manage_network',
					'wp-admin-theme-cd-update-network',
					'wp_admin_theme_cd_update_page'
				);
				
			}
				
		}
		
	}

	add_action('admin_menu', 'wp_admin_theme_cd_update_admin_menu');

endif;

if ( ! function_exists( 'wp_admin_theme_cd_update_page' ) ) :

	function wp_admin_theme_cd_update_page() {
		
		if ( is_multisite() ) {
		
			global $wpdb, $message;

			// update this blog
			if ( ! empty( $_POST['update_current_blog'] ) ) {

				/*wp_admin_theme_cd_update_blog();
				$message = esc_html__( 'This blog has been updated.', 'wp-admin-theme-cd' );*/

			// update all network blogs
			} elseif ( ! empty( $_POST['update_all_blogs'] ) ) {

				$blogs = $wpdb->get_results("
					SELECT blog_id
					FROM {$wpdb->blogs}
					WHERE site_id = '{$wpdb->siteid}'
					AND archived = '0'
					AND spam = '0'
					AND deleted = '0'
				");

				foreach ( $blogs as $blog ) {
					wp_admin_theme_cd_update_blog( $blog->blog_id );
				}

				$message = esc_html__( 'All network sites has been updated.', 'wp-admin-theme-cd' );

			} 

            // get option values from blog ID 1			
            $blog_id = 1;
            $blog_name = get_blog_option( $blog_id, 'blogname' );
            $options = get_blog_option( $blog_id, 'wp_admin_theme_settings_options' ); ?>

			<div class="wrap">
				<h1><?php echo esc_html__( 'WP Admin Theme CD - Multisite Update', 'wp-admin-theme-cd' ); ?></h1>

				<?php if ($message) { ?>
					<div class="updated"><p><strong><?php echo esc_html( $message ); ?></strong></p></div>
				<?php } ?>

				<p><strong><?php echo esc_html__( 'Update your WP Admin Theme CD options for all network websites together.', 'wp-admin-theme-cd' ); ?></strong></p>

				<h2><?php echo esc_html__( 'Share Options', 'wp-admin-theme-cd' ); ?></h2>

				<p><?php echo esc_html__( 'You will share the following options from Blog ID', 'wp-admin-theme-cd' ); ?>: <strong><?php echo esc_html( $blog_id ); ?></strong> / <?php echo esc_html__( 'Blog Name', 'wp-admin-theme-cd' ); ?>: <strong><?php echo esc_html( $blog_name ); ?></strong> <?php echo esc_html__( 'for all network blogs', 'wp-admin-theme-cd' ); ?>.</p>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th style="width: 20%" class="manage-column"><?php echo esc_html__( 'Option', 'wp-admin-theme-cd' ); ?></th>
							<th class="manage-column"><?php echo esc_html__( 'Value', 'wp-admin-theme-cd' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php 

						$is_visible = esc_html__( 'Visible', 'wp-admin-theme-cd' );
						$is_hidden = esc_html__( 'Hidden', 'wp-admin-theme-cd' );
						$is_enabled = esc_html__( 'Enabled', 'wp-admin-theme-cd' );
						$is_disabled = esc_html__( 'Disabled', 'wp-admin-theme-cd' );
						$is_none = esc_html__( 'Not selected', 'wp-admin-theme-cd' );
						$is_not_added = esc_html__( 'Not added', 'wp-admin-theme-cd' );
						$is_activate = esc_html__( 'Activated', 'wp-admin-theme-cd' );
						$is_deactivate = esc_html__( 'Deactivated', 'wp-admin-theme-cd' );

						// user box
						if( empty($options['user_box']) ) $status_user_box = $is_visible;
						else $status_user_box = $is_hidden;
						echo '<tr><td>' . esc_html__( 'User Box', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_user_box ) . '</td></tr>';
			
						// company box
						if( empty($options['company_box']) ) $status_company_box = $is_visible;
						else $status_company_box = $is_hidden;
						echo '<tr><td>' . esc_html__( 'Company Box', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_company_box ) . '</td></tr>';

						// company box logo
						if( empty($options['company_box_logo']) ) $status_company_box_logo = $is_not_added;
						else $status_company_box_logo = $options['company_box_logo'];
						echo '<tr><td>' . esc_html__( 'Company Box Logo', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_company_box_logo ) . '</td></tr>';

						// company box logo size	
						if( empty($options['company_box_logo_size']) ) $status_company_box_logo_size = '140';
						else $status_company_box_logo_size = $options['company_box_logo_size'];
						echo '<tr><td>' . esc_html__( 'Company Box Logo Size', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_company_box_logo_size ) . ' ' . esc_html__( 'Pixel', 'wp-admin-theme-cd' ) . '</td></tr>';
			
						// thumbnails
						if( empty($options['thumbnail']) ) $status_thumbnail = $is_visible;
						else $status_thumbnail = $is_hidden;
						echo '<tr><td>' . esc_html__( 'Thumbnails', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_thumbnail ) . '</td></tr>';

						// post/page ids
						if( empty($options['post_page_id']) ) $status_post_page_id = $is_visible;
						else $status_post_page_id = $is_hidden;
						echo '<tr><td>' . esc_html__( 'Post/Page IDs', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_post_page_id ) . '</td></tr>';
						
						// spacing
						if( empty($options['spacing']) ) $status_spacing = $is_enabled;
						else $status_spacing = $is_disabled;
						echo '<tr><td>' . esc_html__( 'Spacing', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_spacing ) . '</td></tr>';
						
						// spacing max width
						if( empty($options['spacing_max_width']) ) $status_spacing_max_width = '2000' . ' ' . 'Pixel';
						else $status_spacing_max_width = $options['spacing_max_width'];
						echo '<tr><td>' . esc_html__( 'Spacing Max Width', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_spacing_max_width ) . ' ' . 'Pixel' . '</td></tr>';
						
						// credits
						if( empty($options['credits']) ) $status_credits = $is_visible;
						else $status_credits = $is_hidden;
						echo '<tr><td>' . esc_html__( 'Credits', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_credits ) . '</td></tr>';
						
						// google webfont
						if( empty($options['google_webfont']) ) $status_google_webfont = $is_not_added;
						else $status_google_webfont = $options['google_webfont'];
						echo '<tr><td>' . esc_html__( 'Custom Web Font', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_google_webfont ) . '</td></tr>';
						
						// google webfont weight
						if( empty($options['google_webfont_weight']) ) $status_google_webfont_weight = $is_not_added;
						else $status_google_webfont_weight = $options['google_webfont_weight'];
						echo '<tr><td>' . esc_html__( 'Custom Web Font Weight', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_google_webfont_weight ) . '</td></tr>';
			
						// toolbar
						if( empty($options['toolbar']) ) $status_toolbar = $is_visible;
						else $status_toolbar = $is_hidden;
						echo '<tr><td>' . esc_html__( 'Toolbar', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_toolbar ) . '</td></tr>';
						
						// toolbar wp icon
						if( empty($options['toolbar_wp_icon']) ) $status_toolbar_wp_icon = $is_visible;
						else $status_toolbar_wp_icon = $is_hidden;
						echo '<tr><td>' . esc_html__( 'Toolbar WP Icon', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_toolbar_wp_icon ) . '</td></tr>';
						
						// toolbar custom icon
						if( empty($options['toolbar_icon']) ) $status_toolbar_icon = $is_not_added;
						else $status_toolbar_icon = $options['toolbar_icon'];
						echo '<tr><td>' . esc_html__( 'Toolbar Custom Icon', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_toolbar_icon ) . '</td></tr>';

						// theme color
						if( empty($options['theme_color']) ) $status_theme_color = '#4777CD';
						else $status_theme_color = $options['theme_color'];
						echo '<tr><td>' . esc_html__( 'Theme Color', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_theme_color ) . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $status_theme_color ) . '"></span></td></tr>'; 

						// theme gradient start color
						if( empty($options['theme_background']) ) $status_theme_background = '#545c63';
						else $status_theme_background = $options['theme_background'];
						echo '<tr><td>' . esc_html__( 'Background Gradient Color', 'wp-admin-theme-cd' ) . ' / ' . esc_html__( 'Start Color', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_theme_background ) . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $status_theme_background ) . '"></span></td></tr>'; 

						// theme gradient end color
						if( empty($options['theme_background_end']) ) $status_theme_background_end = '#32373c';
						else $status_theme_background_end = $options['theme_background_end'];
						echo '<tr><td>' . esc_html__( 'Background Gradient Color', 'wp-admin-theme-cd' ) . ' / ' . esc_html__( 'End Color', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_theme_background_end ) . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $status_theme_background_end ) . '"></span></td></tr>'; 

						// login disable
						if( empty($options['login_disable']) ) $status_login_disable = $is_deactivate;
						else $status_login_disable = $is_activate;
						echo '<tr><td>' . esc_html__( 'Customized Login Page', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_login_disable ) . '</td></tr>';

						// login title
						if( empty($options['login_title']) ) $status_login_title = esc_html__( 'Welcome Back.', 'wp-admin-theme-cd' );
						else $status_login_title = $options['login_title'];
						echo '<tr><td>' . esc_html__( 'Login Title', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_login_title ) . '</td></tr>';

						// login logo
						if( empty($options['logo_upload']) ) $status_logo_upload = $is_none;
						else $status_logo_upload = $options['logo_upload'];
						echo '<tr><td>' . esc_html__( 'Login Logo', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_logo_upload ) . '</td></tr>';

						// login logo size	
						if( empty($options['logo_size']) ) $status_logo_size = '200';
						else $status_logo_size = $options['logo_size'];
						echo '<tr><td>' . esc_html__( 'Logo Size', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_logo_size ) . ' ' . esc_html__( 'Pixel', 'wp-admin-theme-cd' ) . '</td></tr>';

						// login background image
						if( empty($options['login_bg']) ) $status_login_bg = $is_none;
						else $status_login_bg = $options['login_bg'];
						echo '<tr><td>' . esc_html__( 'Login Background Image', 'wp-admin-theme-cd' ) . ':</td><td>' . $status_login_bg . '</td></tr>';

						// memory usage
						if( empty($options['memory_usage']) ) $status_memory_usage = $is_visible;
						else $status_memory_usage = $is_hidden;
						echo '<tr><td>' . esc_html__( 'Memory Usage', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_memory_usage ) . '</td></tr>';

						// memory limit
						if( empty($options['memory_limit']) ) $status_memory_limit = $is_visible;
						else $status_memory_limit = $is_hidden;
						echo '<tr><td>' . esc_html__( 'WP Memory Limit', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_memory_limit ) . '</td></tr>';

						// php version
						if( empty($options['php_version']) ) $status_php_version = $is_visible;
						else $status_php_version = $is_hidden;
						echo '<tr><td>' . esc_html__( 'PHP Version', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_php_version ) . '</td></tr>';

						// ip address
						if( empty($options['ip_address']) ) $status_ip_address = $is_visible;
						else $status_ip_address = $is_hidden;
						echo '<tr><td>' . esc_html__( 'IP Address', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_ip_address ) . '</td></tr>';

						// wp version
						if( empty($options['wp_version']) ) $status_wp_version = $is_visible;
						else $status_wp_version = $is_hidden;
						echo '<tr><td>' . esc_html__( 'WP Version', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_version ) . '</td></tr>';

						// theme css
						if( empty($options['css_admin']) ) $status_css_admin = $is_not_added;
						else $status_css_admin = $options['css_admin'];
						$textarea_start = '<textarea class="option-textarea" readonly>';
						$textarea_end = '</textarea>';
						echo '<tr><td>' . esc_html__( 'WP Admin CSS', 'wp-admin-theme-cd' ) . ':</td><td>' . $textarea_start . wp_kses( $status_css_admin, array() ) . $textarea_end . '</td></tr>';

						// login css
						if( empty($options['css_login']) ) $status_css_login = $is_not_added;
						else $status_css_login = $options['css_login'];
						$textarea_start = '<textarea class="option-textarea" readonly>';
						$textarea_end = '</textarea>';
						echo '<tr><td>' . esc_html__( 'WP Login CSS', 'wp-admin-theme-cd' ) . ':</td><td>' . $textarea_start . wp_kses( $status_css_login, array() ) . $textarea_end . '</td></tr>';

						// svg support
						if( empty($options['wp_svg']) ) $status_wp_svg = $is_deactivate;
						else $status_wp_svg = $is_activate;
						echo '<tr><td>' . esc_html__( 'SVG Support', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_svg ) . '</td></tr>';

						// ico support
						if( empty($options['wp_ico']) ) $status_wp_ico = $is_deactivate;
						else $status_wp_ico = $is_activate;
						echo '<tr><td>' . esc_html__( 'ICO Support', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_ico ) . '</td></tr>';

						// disable theme options
						if( empty($options['disable_theme_options']) ) $status_disable_theme_options = $is_deactivate;
						else $status_disable_theme_options = $is_activate;
						echo '<tr><td>' . esc_html__( 'Network Theme Options', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_disable_theme_options ) . '</td></tr>';

						// remove wp version tag
						if( empty($options['wp_version_tag']) ) $status_wp_version_tag = $is_deactivate;
						else $status_wp_version_tag = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP Version Meta-Tag', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_version_tag ) . '</td></tr>';

						// remove wp emoji
						if( empty($options['wp_emoji']) ) $status_wp_emoji = $is_deactivate;
						else $status_wp_emoji = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP Emoji', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_emoji ) . '</td></tr>';

						// remove wp feed links
						if( empty($options['wp_feed_links']) ) $status_wp_feed_links = $is_deactivate;
						else $status_wp_feed_links = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP RSS Feed', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_feed_links ) . '</td></tr>';

						// remove wp rsd link
						if( empty($options['wp_rsd_link']) ) $status_wp_rsd_link = $is_deactivate;
						else $status_wp_rsd_link = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP RSD', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_rsd_link ) . '</td></tr>';

						// remove wp wlwmanifest
						if( empty($options['wp_wlwmanifest']) ) $status_wp_wlwmanifest = $is_deactivate;
						else $status_wp_wlwmanifest = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP Wlwmanifest', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_wlwmanifest ) . '</td></tr>';

						// remove wp shortlink
						if( empty($options['wp_shortlink']) ) $status_wp_shortlink = $is_deactivate;
						else $status_wp_shortlink = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP Shortlink', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_shortlink ) . '</td></tr>';

						// remove wp rest api
						if( empty($options['wp_rest_api']) ) $status_wp_rest_api = $is_deactivate;
						else $status_wp_rest_api = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP REST API', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_rest_api ) . '</td></tr>';

						// remove wp oembed
						if( empty($options['wp_oembed']) ) $status_wp_oembed = $is_deactivate;
						else $status_wp_oembed = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP oEmbed', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_oembed ) . '</td></tr>';

						// remove wp xml-rpc
						if( empty($options['wp_xml_rpc']) ) $status_wp_xml_rpc = $is_deactivate;
						else $status_wp_xml_rpc = $is_activate;
						echo '<tr><td>' . esc_html__( 'XML-RPC / X-Pingback', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_xml_rpc ) . '</td></tr>';

						// remove wp heartbeat
						if( empty($options['wp_heartbeat']) ) $status_wp_heartbeat = $is_deactivate;
						else $status_wp_heartbeat = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP Heartbeat', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_heartbeat ) . '</td></tr>';
			
						// remove wp rel link
						if( empty($options['wp_rel_link']) ) $status_wp_rel_link = $is_deactivate;
						else $status_wp_rel_link = $is_activate;
						echo '<tr><td>' . esc_html__( 'WP Rel Links', 'wp-admin-theme-cd' ) . ':</td><td>' . esc_html( $status_wp_rel_link ) . '</td></tr>';
					?>
					</tbody>
				</table>        

				<form action="" method="post">

					<p style="margin-top: 40px">
						<?php echo esc_html__( 'You will update the following network sites', 'wp-admin-theme-cd' ) . ':'; ?>
					</p>

					<p>
						<?php $subsites = get_sites();
                        foreach( $subsites as $subsite ) {
                          $subsite_id = get_object_vars( $subsite )["blog_id"];
                          $subsite_name = get_blog_details( $subsite_id )->blogname;
                          echo esc_html__( 'Blog Name', 'wp-admin-theme-cd' ) . ': <strong>'. $subsite_name . '</strong> (' . esc_html__( 'ID', 'wp-admin-theme-cd' ) . ': ' . $subsite_id . ')<br/>';
                        } ?>
					</p>

					<p class="submit">      
						<?php /*<input type="submit" name="update_current_blog" class="button" value="<?php esc_attr__( 'Update This Blog', 'wp-admin-theme-cd' ); ?>" /> */ ?>
						<input type="submit" name="update_all_blogs" class="button-primary" value="<?php esc_attr_e( 'Update all network blogs', 'wp-admin-theme-cd' ); ?>" onclick="return confirm('<?php esc_html_e( 'Are you sure you want to run the update for all blogs?', 'wp-admin-theme-cd' ); ?>');" />
					</p>
				</form>

			</div>

		<?php }
	}

endif;


/*****************************************************************/
/* REMOVE USER THEME OPTION */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_remove_theme_option' ) ) :

	function wp_admin_theme_cd_remove_theme_option() {
        
		global $_wp_admin_css_colors;

		/* Get fresh color data */
		$fresh_color_data = $_wp_admin_css_colors['fresh'];

		/* Remove everything else */
		$_wp_admin_css_colors = array( 'fresh' => $fresh_color_data );
	}

endif;

add_action( 'admin_init', 'wp_admin_theme_cd_remove_theme_option', 1 );


/*****************************************************************/
/* SET ALL USER THEME OPTION TO DEFAULT */
/*****************************************************************/
 
if ( ! function_exists( 'wp_admin_theme_cd_set_default_theme' ) ) :

	function wp_admin_theme_cd__set_default_theme( $color ){
		return 'fresh';
	}

	add_filter( 'get_user_option_admin_color', 'wp_admin_theme_cd__set_default_theme' );

endif; 


/*****************************************************************/
/* CREATE LOGOUT BUTTON */
/*****************************************************************/

if( $wpat_toolbar ) {

	if ( ! function_exists( 'wp_admin_theme_cd_logout' ) ) :

		function wp_admin_theme_cd_logout() {
			echo '<div class="wpat-logout"><div class="wpat-logout-button"></div><div class="wpat-logout-content"><a target="_blank" class="btn home-btn" href="' . home_url() . '">' . esc_html__( 'Home', 'wp-admin-theme-cd' ) . '</a>';
            if( is_multisite() ) {
                echo '<a class="btn multisite-btn" href="' . network_admin_url() . '">' . esc_html__( 'My Sites', 'wp-admin-theme-cd' ) . '</a>';
            }
            echo '<a class="btn logout-btn" href="' . wp_logout_url() . '">' . esc_html__( 'Logout', 'wp-admin-theme-cd' ) . '</a></div></div>';
		}

	endif;

    add_action('admin_head', 'wp_admin_theme_cd_logout');
	
}


/*****************************************************************/
/* ADD LEFT FOOTER NOTICE */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_footer_notice' ) ) :

    global $wpat_credits;

	if( ! $wpat_credits ) {
		function wp_admin_theme_cd_footer_notice( $text ) {
			$text = 'This theme was designed by <a target="_blank" href="http://www.creative-dive.de">Creative Dive</a>';
			return $text;
		}
	} else {
		function wp_admin_theme_cd_footer_notice( $text ) {
			$text = '';
			return $text;
		}
	}	

endif;

add_filter('admin_footer_text', 'wp_admin_theme_cd_footer_notice');


/*****************************************************************/
/* WRAP THE WP ADMIN CONTENT */
/*****************************************************************/

if( ! $wpat_spacing ) {

	if ( ! function_exists( 'wp_admin_theme_cd_wrap_content' ) ) :

		function wp_admin_theme_cd_wrap_content() {
			ob_start( 'wp_admin_theme_cd_replace_content' );
		}

	endif;

	if ( ! function_exists( 'wp_admin_theme_cd_replace_content' ) ) :

        function wp_admin_theme_cd_replace_content( $output ) {

            $find = array('/<div id="wpwrap">/', '#</body>#');
            $replace = array('<div class="body-spacer"><div id="wpwrap">', '</div></body>');
            $result = preg_replace( $find, $replace, $output );

            return $result;
        }

	endif;

	add_action( 'init', 'wp_admin_theme_cd_wrap_content', 0, 0 );

}


/*****************************************************************/
/* CUSTOMIZED LOGIN PAGE */
/*****************************************************************/

if( ! $wpat_login_disable ) {

	/*****************************************************************/
	/* ADD LOGIN STYLE */
	/*****************************************************************/

	if ( ! function_exists( 'wp_admin_theme_cd_login_style' ) ) :

		function wp_admin_theme_cd_login_style() {

			wp_enqueue_style( 'custom-login', plugins_url('login.css', __FILE__), array(), filemtime( plugin_dir_path( __FILE__ ) . 'login.css' ), 'all' );
			//wp_enqueue_script( 'custom-login', plugins_url('login.js', __FILE__) );

		}

	endif;

    add_action('login_enqueue_scripts', 'wp_admin_theme_cd_login_style');
	
	
	/*****************************************************************/
	/* CHANGE LOGIN LOGO URL */
	/*****************************************************************/

	if ( ! function_exists( 'wp_admin_theme_cd_logo_url' ) ) :

		function wp_admin_theme_cd_logo_url() {
			return home_url();
		}

	endif;

    add_filter( 'login_headerurl', 'wp_admin_theme_cd_logo_url' );


	/*****************************************************************/
	/* ADD LOGIN MESSAGE */
	/*****************************************************************/

	global $wpat_login_title;
    
	if( $wpat_login_title ) {

		if ( ! function_exists( 'wp_admin_theme_cd_login_message' ) ) :

			function wp_admin_theme_cd_login_message( $message ) {

				global $wpat_login_title;

				if ( empty( $message ) ){
					return '<div class="login-message">' . esc_html( $wpat_login_title ) . '</div>';
				} else {
					return $message;
				}
			}

		endif;

        add_filter( 'login_message', 'wp_admin_theme_cd_login_message' );

	}

}


/*****************************************************************/
/* ADD USER BOX TO LEFT ADMIN MENU */
/*****************************************************************/
	
if ( ! function_exists( 'wp_admin_theme_cd_userbox' ) ) :

    function wp_admin_theme_cd_userbox() {
        
        global $wpat_user_box, $wpat_company_box;
        
        if( ! $wpat_user_box && ! $wpat_company_box ) {

            global $menu, $user_id, $scheme;

            // get user name and avatar
            $current_user = wp_get_current_user();
            $user_name = $current_user->display_name ;
            $user_avatar = get_avatar( $current_user->user_email, 74 );

            // get user profile link
            if ( is_user_admin() ) {
                $url = user_admin_url( 'profile.php', $scheme );
            } elseif ( is_network_admin() ) {
                $url = network_admin_url( 'profile.php', $scheme );
            } else {
                $url = get_dashboard_url( $user_id, 'profile.php', $scheme );
            }    

            if( is_rtl() ) {
                $html = '<div class="adminmenu-avatar">' . $user_avatar . '<div class="adminmenu-user-edit">' . esc_html__( 'Edit', 'wp-admin-theme-cd' ) . '</div></div><div class="adminmenu-user-name"><span>' . esc_html__( $user_name ) . ', ' . esc_html__('Howdy', 'wp-admin-theme-cd') . '</span></div>';
            } else {
                $html = '<div class="adminmenu-avatar">' . $user_avatar . '<div class="adminmenu-user-edit">' . esc_html__( 'Edit', 'wp-admin-theme-cd' ) . '</div></div><div class="adminmenu-user-name"><span>' . esc_html__('Howdy', 'wp-admin-theme-cd') . ', ' . esc_html__( $user_name ) . '</span></div>';
            }


            $menu[0] = array( $html, 'read', $url, 'user-box', 'adminmenu-container');

        }

    }

endif;

add_action('admin_menu', 'wp_admin_theme_cd_userbox');
	

/*****************************************************************/
/* ADD COMPANY BOX TO LEFT ADMIN MENU */
/*****************************************************************/
	
if ( ! function_exists( 'wp_admin_theme_cd_companybox' ) ) :

    function wp_admin_theme_cd_companybox() {
        
        global $wpat_user_box, $wpat_company_box;

        if( ! $wpat_user_box && $wpat_company_box ) {

            global $menu, $user_id, $scheme;

            $blog_name = get_bloginfo( 'name' );
            $site_url = get_bloginfo( 'wpurl' ) . '/';

            $get_company_logo = get_option( 'wp_admin_theme_settings_options' );
            $company_logo = $get_company_logo['company_box_logo'];
            $company_logo_size = $get_company_logo['company_box_logo_size'];

            if( ! empty( $company_logo ) ){
                $company_logo_output = '<img style="width:' . esc_html( $company_logo_size ) . 'px" class="company-box-logo" src="' . esc_url( $company_logo ) . '" alt="' . esc_attr( $blog_name ) . '">';
            } else {
                $company_logo_output = esc_html__( 'No image selected.', 'wp-admin-theme-cd' );
            }

            $html = '<div class="adminmenu-avatar">' . $company_logo_output . '<div class="adminmenu-user-edit">' . esc_html__( 'Home', 'wp-admin-theme-cd' ) . '</div></div><div class="adminmenu-user-name"><span>' . esc_html( $blog_name ) . '</span></div>';

            $menu[0] = array( $html, 'read', $site_url, 'user-box', 'adminmenu-container');

        }    

    }

endif;

add_action('admin_menu', 'wp_admin_theme_cd_companybox');


/*****************************************************************/
/* GET THE CURRENT POST TPYE */
/*****************************************************************/

if( ! function_exists('wp_admin_theme_cd_get_wp_admin_screen') ) :

	require_once(ABSPATH . 'wp-admin/includes/screen.php');

	function wp_admin_theme_cd_get_wp_admin_screen() {

		$currentScreen = get_current_screen();

		if( $currentScreen->post_type === 'post' || $currentScreen->post_type === 'page' ) {

            global $wpat_thumbnail;
            
			if( ! $wpat_thumbnail ) {

				/*****************************************************************/
				/* ADD IMAGE COL TO WP ADMIN POSTS AND PAGES */
				/*****************************************************************/

				if ( ! function_exists( 'wp_admin_theme_cd_featured_image' ) ) :

					// get the image
					function wp_admin_theme_cd_featured_image( $post_ID ) {
						$post_thumbnail_id = get_post_thumbnail_id( $post_ID );
						if ( $post_thumbnail_id ) {
							$post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, array(32,32) );
							return $post_thumbnail_img[0];
						}
					}

				endif;


				if ( ! function_exists( 'wp_admin_theme_cd_columns_head' ) ) :

					// add new col
					function wp_admin_theme_cd_columns_head( $defaults ) {
						$defaults['featured_image'] = esc_html__( 'Image', 'wp-admin-theme-cd' );
						return $defaults;
					}

				endif;	

				add_filter('manage_posts_columns', 'wp_admin_theme_cd_columns_head');
				add_filter('manage_pages_columns', 'wp_admin_theme_cd_columns_head');


				if ( ! function_exists( 'wp_admin_theme_cd_columns_content' ) ) :

					// output the image
					function wp_admin_theme_cd_columns_content( $column_name, $post_ID ) {
						if ( $column_name == 'featured_image' ) {
							$post_featured_image = wp_admin_theme_cd_featured_image( $post_ID );
							if ( $post_featured_image ) {
								echo '<img src="' . esc_url( $post_featured_image ) . '" />';
							} else {
								echo '<img style="width:55px;height:55px" src="' . plugins_url( '/img/no-thumb.jpg', __FILE__ ) . '" alt="' . esc_attr__( 'No Thumbnail', 'wp-admin-theme-cd' ) . '"/>';
							}
						}
					}

				endif;	

				add_action('manage_posts_custom_column', 'wp_admin_theme_cd_columns_content', 3, 2);
				add_action('manage_pages_custom_column', 'wp_admin_theme_cd_columns_content', 3, 2);


				/*****************************************************************/
				/* MOVE IMAGE COL BEFORE THE TITLE COL */
				/*****************************************************************/

				if ( ! function_exists( 'wp_admin_theme_cd_thumbnail_column' ) ) :

					function wp_admin_theme_cd_thumbnail_column($columns) {
						$new = array();
						foreach($columns as $key => $title) {
							if ($key=='title')
								$new['featured_image'] = 'Image';
							$new[$key] = $title;
						}
						return $new;
					}

				endif;

				add_filter('manage_posts_columns', 'wp_admin_theme_cd_thumbnail_column');
				add_filter('manage_pages_columns', 'wp_admin_theme_cd_thumbnail_column');

			}

		}

	}

endif;

add_action( 'current_screen', 'wp_admin_theme_cd_get_wp_admin_screen' );


/*****************************************************************/
/* ADD ID COL TO WP ADMIN PAGES AND POSTS */
/*****************************************************************/

if( ! $wpat_post_page_id ) {

	if( ! function_exists('wp_admin_theme_cd_posts_columns_id') ) :

		function wp_admin_theme_cd_posts_columns_id($defaults){
			$defaults['wps_post_id'] = esc_html__('ID', 'wp-admin-theme-cd');
			return $defaults;
		}

	endif;

    add_filter('manage_posts_columns', 'wp_admin_theme_cd_posts_columns_id', 99);
    add_filter('manage_pages_columns', 'wp_admin_theme_cd_posts_columns_id', 99);


	if( ! function_exists('wp_admin_theme_cd_posts_custom_id_columns') ) :

		function wp_admin_theme_cd_posts_custom_id_columns($column_name, $id){
			if($column_name === 'wps_post_id'){
				echo esc_html( $id );
			}
		}

	endif;

    add_action('manage_posts_custom_column', 'wp_admin_theme_cd_posts_custom_id_columns', 99, 2);
    add_action('manage_pages_custom_column', 'wp_admin_theme_cd_posts_custom_id_columns', 99, 2);

}

/*****************************************************************/
/* ADD RIGHT FOOTER MEMORY NOTICE */
/*****************************************************************/

if ( ! function_exists( 'wp_admin_theme_cd_memory_notice' ) ) :

	function wp_admin_theme_cd_memory_notice( $text ) {
		$text = wp_memory_data();
		return $text;
	}

endif;

add_filter('update_footer', 'wp_admin_theme_cd_memory_notice', 11);


/*****************************************************************/
/* ADD FOOTER INFORMATION */
/*****************************************************************/

// get wp memory usage

if ( ! function_exists( 'wp_memory_usage' ) ) : 

	function wp_memory_usage() {

		global $memory_limit, $memory_usage;

		$memory_limit = (int)ini_get( 'memory_limit' );
		$memory_usage = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage(true)/1024/1024 ) : 0;

		if ( ! empty( $memory_usage ) && ! empty( $memory_limit ) ) {

			global $memory_percent;

			$memory_percent = round( $memory_usage / $memory_limit * 100, 0 );

		}

	}

endif;

// get wp memory limit

if ( ! function_exists( 'wp_memory_limit' ) ) : 

	function wp_memory_limit( $size ) {

		global $wp_limit;

		$value  = substr( $size, -1 );
		$wp_limit = substr( $size, 0, -1 );

		$wp_limit = (int)$wp_limit;

		switch ( strtoupper( $value ) ) {
			case 'P' :
				$wp_limit*= 1024;
			case 'T' :
				$wp_limit*= 1024;
			case 'G' :
				$wp_limit*= 1024;
			case 'M' :
				$wp_limit*= 1024;
			case 'K' :
				$wp_limit*= 1024;
		}

		return $wp_limit;
	}  

endif;

// check memory limit

if ( ! function_exists( 'wp_check_memory_limit' ) ) : 

	function wp_check_memory_limit() {

		global $check_memory;

		$check_memory = wp_memory_limit( WP_MEMORY_LIMIT );
		$check_memory = size_format( $check_memory );

		return ($check_memory) ? $check_memory : esc_html__( 'N/A', 'wp-admin-theme-cd' );

	}

endif;

// output wp memory data

if ( ! function_exists( 'wp_memory_data' ) ) : 

	function wp_memory_data() {

		global $memory_limit, $memory_usage, $memory_percent, $check_memory, $wp_version;

		wp_memory_usage();
		wp_check_memory_limit();

		// get ip address
		$server_ip_address = ( ! empty( $_SERVER[ 'SERVER_ADDR' ] ) ? $_SERVER[ 'SERVER_ADDR' ] : '' );
		if( $server_ip_address == '' || $server_ip_address == false ) { 
			$server_ip_address = ( ! empty( $_SERVER[ 'LOCAL_ADDR' ] ) ? $_SERVER[ 'LOCAL_ADDR' ] : '' );
		}

        global $wpat_memory_usage, $wpat_memory_limit, $wpat_ip_address, $wpat_php_version, $wpat_wp_version;
        
		// memory usage
		if( ! $wpat_memory_usage ) {

			if ( $memory_percent <= 65 ) $memory_status = 'background:#46b450';
			if ( $memory_percent > 65 ) $memory_status = 'background:#daa92e';
			if ( $memory_percent > 90 ) $memory_status = 'background:#da2e2e';

			if( is_rtl() ) {
				echo '|<span class="memory-status" style="' . $memory_status . '"><strong>%' . $memory_percent . '</strong></span> ';
				echo ' MB ' . $memory_limit . esc_html__( ' of ', 'wp-admin-theme-cd' );
				echo $memory_usage . ': ' . esc_html__( 'Memory Usage', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'Memory Usage', 'wp-admin-theme-cd' ) . ': ' . $memory_usage;
				echo esc_html__( ' of', 'wp-admin-theme-cd' ) . ' ' . $memory_limit . ' MB';
				echo '<span class="memory-status" style="' . $memory_status . '"><strong>' . $memory_percent . '%</strong></span> | ';
			}

		}
		
		// wp memory limit
		if( ! $wpat_memory_limit ) {
			
			if( is_rtl() ) {
				echo ' | ' . $check_memory . ' :' . esc_html__( 'WP Memory Limit', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'WP Memory Limit', 'wp-admin-theme-cd' ) . ': ' . $check_memory . ' |';
			}
			
		}

		echo '<br>';

		// ip address
		if( ! $wpat_ip_address ) {
			
			if( is_rtl() ) {
				echo '| ' . $server_ip_address . ' :' . esc_html__( 'IP', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'IP', 'wp-admin-theme-cd' ) . ' ' . $server_ip_address . ' | ';
			}
			
		}

		// php version
		if( ! $wpat_php_version ) {
			
			if( is_rtl() ) {
				echo ' | ' . PHP_VERSION . ' :' . esc_html__( 'PHP', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'PHP', 'wp-admin-theme-cd' ) . ' ' . PHP_VERSION . ' | ';
			}
			
			
		}

		// wp version
		if( ! $wpat_wp_version ) {
			
			if( is_rtl() ) {
				echo ' | ' . $wp_version . ' :' . esc_html__( 'WP', 'wp-admin-theme-cd' );
			} else {
				echo esc_html__( 'WP', 'wp-admin-theme-cd' ) . ' ' . $wp_version . ' | ';
			}
			
		}

	}

endif;


/*****************************************************************/
/* SVG SUPPORT */
/*****************************************************************/

if( $wpat_wp_svg ) {
	
	function wp_admin_theme_cd_svg_support ( $svg_mime ) {
		$svg_mime['svg'] = 'image/svg+xml';		
		return $svg_mime;
	}

	add_filter('upload_mimes', 'wp_admin_theme_cd_svg_support', 10, 4);
	
}

/*****************************************************************/
/* ICO SUPPORT */
/*****************************************************************/

if( $wpat_wp_ico ) {
	
	function wp_admin_theme_cd_ico_support ( $ico_mime ) {
		$ico_mime['ico'] = 'image/x-icon';
		return $ico_mime;
	}

	add_filter('upload_mimes', 'wp_admin_theme_cd_ico_support', 10, 5);

}


/*****************************************************************/
/* REMOVE WP VERSION META TAG */
/*****************************************************************/

if( $wpat_wp_version_tag ) {

	remove_action('wp_head', 'wp_generator');

}


/*****************************************************************/
/* REMOVE WP EMOTICONS */
/*****************************************************************/

if( $wpat_wp_emoji ) {

	function remove_emoji() {
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		add_filter('tiny_mce_plugins', 'remove_tinymce_emoji');
	}

	add_action('init', 'remove_emoji');

	function remove_tinymce_emoji( $plugins ) {
		if (!is_array( $plugins )) {
			return array();
		}
		return array_diff( $plugins, array( 'wpemoji' ));
	}

}


/*****************************************************************/
/* REMOVE RSS FEED LINKS */
/*****************************************************************/

if( $wpat_wp_feed_links ) {
	
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	
	function wp_admin_theme_cd_disable_rss() {
		wp_die( 
			esc_html__( 'No feed available, please visit our', 'wp-admin-theme-cd' ) . ' <a href="'. esc_url( home_url( '/' ) ) .'">' . esc_html__( 'homepage', 'wp-admin-theme-cd' ) . '</a>!'
		);
	}

	add_action('do_feed', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_rdf', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_rss', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_rss2', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_atom', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_rss2_comments', 'wp_admin_theme_cd_disable_rss', 1);
	add_action('do_feed_atom_comments', 'wp_admin_theme_cd_disable_rss', 1);
		
}


/*****************************************************************/
/* REMOVE RSD LINK */
/*****************************************************************/

if( $wpat_wp_rsd_link ) {
	
	remove_action('wp_head', 'rsd_link');
	
}


/*****************************************************************/
/* REMOVE WLWMANIFEST LINK */
/*****************************************************************/

if( $wpat_wp_wlwmanifest ) {
	
	remove_action('wp_head', 'wlwmanifest_link');
	
}


/*****************************************************************/
/* REMOVE SHORTLINK */
/*****************************************************************/

if( $wpat_wp_shortlink ) {
	
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_header', 10, 0);
}


/*****************************************************************/
/* REMOVE REST API */
/*****************************************************************/

if( $wpat_wp_rest_api ) {
	
	remove_action('wp_head','rest_output_link_wp_head',10);
	add_filter('rest_enabled','_return_false');
	add_filter('rest_jsonp_enabled','_return_false'); 
	
}

/*****************************************************************/
/* REMOVE oEMBED */
/*****************************************************************/

if( $wpat_wp_oembed ) {

	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	
	function wp_admin_theme_cd_block_wp_embed() {
		wp_deregister_script('wp-embed'); 
	}

	add_action('init', 'wp_admin_theme_cd_block_wp_embed');

}

/*****************************************************************/
/* REMOVE XML-RPC */
/*****************************************************************/

if( $wpat_wp_xml_rpc ) {
	
	add_filter( 'xmlrpc_enabled', '__return_false' );
	
	function wp_admin_theme_cd_remove_x_pingback( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}

	add_filter( 'wp_headers', 'wp_admin_theme_cd_remove_x_pingback' );

}

/*****************************************************************/
/* STOP WP HEARTBEAT */
/*****************************************************************/

if( $wpat_wp_heartbeat ) {
	
	function wp_admin_theme_cd_stop_heartbeat() {
		wp_deregister_script('heartbeat');
	}

	add_action('init', 'wp_admin_theme_cd_stop_heartbeat', 1);

}

/*****************************************************************/
/* REMOVE REL LINKS PREV/NEXT  */
/*****************************************************************/

if( $wpat_wp_rel_link ) {
	
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'index_rel_link');
	
}

?>