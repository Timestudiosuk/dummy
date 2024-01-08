<?php
namespace FileBird\Addons\PostType;

use FileBird\Addons\PostType\Controllers\Main as MainController;

class Routes {
    private static $instance = null;

    public function __construct() {
         add_action( 'rest_api_init', array( $this, 'registerRestFields' ) );
    }

    public static function getInstance() {
		if ( is_null( self::$instance ) ) {
            self::$instance = new self();
		}
        return self::$instance;
    }
    public function registerRestFields() {
        register_rest_route(
            NJFB_REST_URL,
            'pt-folders',
            array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( MainController::class, 'restGetFolders' ),
				'permission_callback' => array( $this, 'resPermissionsCheck' ),
            )
        );
        register_rest_route(
            NJFB_REST_URL,
            'pt-new-folder',
            array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( MainController::class, 'restNewFolder' ),
				'permission_callback' => array( $this, 'resPermissionsCheck' ),
            )
        );
        register_rest_route(
            NJFB_REST_URL,
            'pt-edit-folder',
            array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( MainController::class, 'restEditFolder' ),
				'permission_callback' => array( $this, 'resPermissionsCheck' ),
            )
        );
        register_rest_route(
            NJFB_REST_URL,
            'pt-update-tree',
            array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( MainController::class, 'restUpdateTree' ),
				'permission_callback' => array( $this, 'resPermissionsCheck' ),
            )
        );
        register_rest_route(
            NJFB_REST_URL,
            'pt-delete-folder',
            array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( MainController::class, 'restDeleteFolder' ),
				'permission_callback' => array( $this, 'resPermissionsCheck' ),
            )
        );
        register_rest_route(
            NJFB_REST_URL,
            'pt-set-folder',
            array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( MainController::class, 'restSetFolder' ),
				'permission_callback' => array( $this, 'resPermissionsCheck' ),
            )
        );
    }
    public function resPermissionsCheck() {
        return current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' );
    }
}
