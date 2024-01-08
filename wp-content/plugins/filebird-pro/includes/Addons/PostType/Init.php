<?php
namespace FileBird\Addons\PostType;

use FileBird\Classes\Config;

use FileBird\Addons\PostType\Routes as PostTypeRoutes;
use FileBird\Addons\PostType\Classes\MetaBox;
use FileBird\Addons\PostType\Models\Main;

class Init {
    public $enabled_posttype   = array( 'post', 'page' );
    private $user_mode_enabled = false;

    private static $instance = null;

    public function __construct() {
        $enabled_posttype        = get_option( 'fbv_enabled_posttype', '' );
        $this->enabled_posttype  = empty( $enabled_posttype ) ? array() : explode( ',', $enabled_posttype );
        $this->user_mode_enabled = get_option( 'njt_fbv_folder_per_user' ) === '1';

        Config::setConfig( 'PostType.enabled_posttype', $this->enabled_posttype );
        Config::setConfig( 'PostType.user_mode_enabled', $this->user_mode_enabled );

        PostTypeRoutes::getInstance();

        add_action( 'init', array( $this, 'registerTaxonomies' ) );
        add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ), 10, 2 );
        add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
        add_filter( 'fbv_data', array( $this, 'localize_script' ) );
        add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

        add_filter( 'filebird_load_jquery_resizable', array( $this, 'filebird_load_jquery_resizable' ), 10, 2 );
        // Make post_modified sortable
        foreach ( $this->enabled_posttype as $post_type ) {
            add_filter( 'manage_edit-' . $post_type . '_sortable_columns', array( $this, 'register_post_modified_sortable' ) );
        }

        //Metabox
        MetaBox::getInstance();
    }

    public function registerTaxonomies() {
        $labels = array(
            'name'              => __( 'Folders', 'filebird' ),
            'singular_name'     => __( 'Filebird Folder', 'filebird' ),
            'search_items'      => __( 'Search Folders', 'filebird' ),
            'all_items'         => __( 'All Folders', 'filebird' ),
            'parent_item'       => __( 'Parent Folder', 'filebird' ),
            'parent_item_colon' => __( 'Parent Folder:', 'filebird' ),
            'edit_item'         => __( 'Edit Folder', 'filebird' ),
            'update_item'       => __( 'Update Folder', 'filebird' ),
            'add_new_item'      => __( 'Add New Folder', 'filebird' ),
            'new_item_name'     => __( 'New Folder Name', 'filebird' ),
            'menu_name'         => __( 'Folder', 'filebird' ),
        );

        if ( is_array( $this->enabled_posttype ) ) {
            foreach ( $this->enabled_posttype as $post_type ) {
                $args = array(
                    'hierarchical'      => false,
                    'show_in_rest'      => false,
                    'show_ui'           => false,
                    'show_admin_column' => false,
                    'query_var'         => false,
                    'labels'            => $labels,
                    'show_tagcloud'     => false,
                    // 'update_count_callback' => '_update_post_term_count',
                );

                register_taxonomy( $this->getTaxonomyName( $post_type ), $post_type, $args );
            }
        }
    }
    public function restrict_manage_posts( $post_type, $which ) {
		if ( is_array( $this->enabled_posttype ) && in_array( $post_type, $this->enabled_posttype ) && $which == 'top' ) {
            $selected = '-1';
            if ( isset( $_GET[ $this->getTaxonomyName( $post_type ) ] ) ) {
                $selected = (int) $_GET[ $this->getTaxonomyName( $post_type ) ];
            }
            wp_dropdown_categories(
			   array(
				   'class'            => 'fbv-filter',
				   'show_option_all'  => esc_html__( 'Uncategorized', 'filebird' ),
				   'show_option_none' => sprintf( esc_html__( 'All folders', 'filebird' ) ),
				   'taxonomy'         => $this->getTaxonomyName( $post_type ),
				   //  'name'             => $this->getTaxonomyName( $post_type ),
				   'name'             => $this->getTaxonomyName( $post_type ),
				   'selected'         => $selected,
				   'hierarchical'     => true,
				   'hide_empty'       => false,
			   )
		   );
		}
    }
    public function pre_get_posts( $query ) {
       $post_type = $query->get( 'post_type' );
		if ( ! in_array( $post_type, $this->enabled_posttype ) ) {
			return;
		}
		if ( ! $query->is_main_query() ) {
			return;
		}

       $taxonomy = $this->getTaxonomyName( $post_type );
       $selected = isset( $_GET[ $taxonomy ] ) ? (int) $_GET[ $taxonomy ] : '-1';

		if ( $selected == -1 ) {
			return $query;
		}

       $tax_query = array();
		if ( $selected == 0 ) {
			$tax_query = array(
				array(
					'taxonomy' => $taxonomy,
					'operator' => 'NOT EXISTS',
				),
			);
		} else {
			$tax_query = array(
				array(
					'taxonomy'         => $taxonomy,
					'field'            => 'term_id',
					'terms'            => array( $selected ),
					'include_children' => false,
				),
			);
		}
       $query->set( 'tax_query', $tax_query );
    }
    public function register_post_modified_sortable( $columns ) {
        //orderby=post_modified&order=desc
       $columns['post_modified'] = 'post_modified';
       return $columns;
    }
    public function localize_script( $data ) {
        global $typenow;

        if ( in_array( $typenow, $this->enabled_posttype ) ) {
            $data['post_type_relationship'] = Main::get_post_type_relationship( $typenow );
        }

        $data['current_pt_folder']      = isset( $_GET[ "fbv_pt_tax_$typenow" ] ) ? intval( sanitize_text_field( $_GET[ "fbv_pt_tax_$typenow" ] ) ) : -1;
        $data['current_pt_sort_folder'] = get_option( Config::getConfig( 'PostType.saved_sort_folder_key' ), 'reset' );
        $data['current_pt_sort_folder'] = str_replace( array( 'reset', 'name_asc', 'name_desc' ), array( '', 'sort-asc', 'sort-desc' ), $data['current_pt_sort_folder'] );
        return $data;
    }

    public function admin_body_class( $classes ) {
        global $typenow;

        $screen = get_current_screen();

        if ( $screen->id === "edit-$typenow" && in_array( $typenow, $this->enabled_posttype ) ) {
            $classes .= ' filebird-post-type';
        }

        if ( $screen->id === 'upload' ) {
            $classes .= ' filebird-upload-php';
        }

       return $classes;
    }
    public function filebird_load_jquery_resizable( $result, $typenow ) {
        return in_array( $typenow, $this->enabled_posttype );
    }
    public static function getInstance() {
		if ( is_null( self::$instance ) ) {
            self::$instance = new self();
		}
        return self::$instance;
    }
    private function getTaxonomyName( $post_type ) {
        return Config::getConfig( 'PostType.taxonomy_prefix' ) . $post_type;
    }
}
