<?php 
/**
 * Atarim  - wp_feedback class.
 * Defines front end functionality
 *
 */
class WP_Feedback {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.2.0.1';

	/**
	 * Unique identifier for plugin.
	 *
	 * @since    1.2.0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'wpfeedback';

	/**
	 * Instance of this class.
	 *
	 * @since    1.2.0.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.2.0.1
	 */
	public function __construct() {

		// Activate plugin when new blog is added
		add_action( 'wpf_new_blog', array( $this, 'wpf_activate_new_site' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @return    Atarim slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param    boolean    $network_wide
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 * @param    boolean    $network_wide
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function wpf_activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpf_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 */
	private static function single_activate() {
	    /*global $current_user,$wpdb;

	    if(get_site_data_by_key_api('wpfeedback_color') != '') {

		update_user_meta($current_user->ID, 'wpf_user_type','advisor' );

		$args = array(
		    'wpf_site_id' => get_option('wpf_site_id'),
		    'wpf_id' => $current_user->ID,
		    'username' => $current_user->data->user_login,
		    'wpf_email' => $current_user->data->user_email,
		    'first_name' => get_user_meta($current_user->ID, 'first_name', true),
		    'last_name' => get_user_meta($current_user->ID, 'last_name', true),
		    'role' => $current_user->roles
		);

		$url = WPF_CRM_API.'wp-api/wpfuser/update';
		wpf_send_remote_post($url,json_encode($args));

		$parms1 = $options = [];

		$options['wpfeedback_color'] =  '002157';
		$options['wpf_selcted_role'] =  'administrator';
		$options['wpf_website_developer'] =  $current_user->ID;
		$options['wpf_show_front_stikers'] =  'yes';
		$options['wpf_customisations_client'] =  'Client (Website Owner)';
		$options['wpf_customisations_webmaster'] =  'Webmaster';
		$options['wpf_customisations_others'] =  'Others';
		$options['wpf_from_email'] =  get_option('admin_email');
		$options['wpf_tab_auto_screenshot_task_client'] =  'yes';

		foreach ($options as $key => $value) {
		    array_push($parms1, ['name' => $key,'value' => $value]);
		}
		update_site_data($parms1);
	    }*/
	    $wp_rocket_settings = get_option('wp_rocket_settings');
	    $wp_rocket_settings['exclude_css'][] = '/wp-content/plugins/wpfeedback/css/(.*).css';
	    $wp_rocket_settings['exclude_js'][] = '/wp-content/plugins/wpfeedback/js/(.*).js';
	    update_option('wp_rocket_settings',$wp_rocket_settings);
    //        $wpdb->query( "UPDATE {$wpdb->prefix}comments SET comment_approved = '1' WHERE {$wpdb->prefix}comments.comment_type = 'wp_feedback'" );
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 */
	private static function single_deactivate() {
	    //clear_session();
//        global $wpdb;
//        $wpdb->query( "UPDATE {$wpdb->prefix}comments SET comment_approved = '0' WHERE {$wpdb->prefix}comments.comment_type = 'wp_feedback'" );
	}
}