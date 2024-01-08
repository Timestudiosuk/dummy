<?php
namespace FileBird\Addons\PostType\Classes;

use  FileBird\Addons\PostType\Models\Main as MainModel;
use FileBird\Classes\Config;

class MetaBox {

    private static $instance = null;

    private $enabled_posttype = array();
    public function __construct() {
        $this->enabled_posttype = Config::getConfig( 'PostType.enabled_posttype' );

        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
    }

    public function add_meta_box( $post_type ) {
        if ( in_array( $post_type, $this->enabled_posttype ) ) {
			add_meta_box(
				'filebird_folder_' . $post_type,
				__( 'Folder', 'filebird' ),
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'side',
				'default'
			);
		}
    }
    public function render_meta_box_content( $post ) {
        global $typenow;

        $terms = MainModel::getFolders( $typenow, null );

        $terms = array_map( array( MainModel::class, 'convertFormat' ), $terms );
        $tree  = array();

        MainModel::sortTerms( $terms, $tree, 0 );

        wp_nonce_field( 'fbv_pt', 'fbv_pt_nonce' );

        $old_val = MainModel::getFolderOfPost( $typenow, $post->ID );
        $this->printTree( $tree, $old_val, 0 );
    }
    public function save_post( $post_id ) {
        global $typenow;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

        if ( ! isset( $_POST['fbv_pt_nonce'] ) ) {
			return $post_id;
		}

		$nonce = sanitize_text_field( $_POST['fbv_pt_nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'fbv_pt' ) ) {
			return $post_id;
		}

        if ( isset( $_POST['fbv_pt_folder'] ) ) {
            $fbv_pt_folder = (int) $_POST['fbv_pt_folder'];
            wp_set_post_terms( (int) $post_id, array( $fbv_pt_folder ), MainModel::getTaxonomyName( $typenow ) );

        }
    }

    private function printTree( $tree, $old_val, $level = 0 ) {
        echo '<ul class="fbv_pt_mb_folders ' . ( $level > 0 ? 'editor-post-taxonomies__hierarchical-terms-subchoices' : '' ) . '">';
        foreach ( $tree as $node ) {
            echo '<li>';
            echo sprintf( '<label><input type="radio" name="fbv_pt_folder" value="%d" %s > %s</label>', esc_attr( $node->id ), checked( $node->id, $old_val, false ), esc_attr( $node->text ) );
            $this->printTree( $node->children, $old_val, $level + 1 );
            echo '</li>';
        }
        echo '</ul>';
    }
    public static function getInstance() {
		if ( is_null( self::$instance ) ) {
            self::$instance = new self();
		}
        return self::$instance;
    }
}
