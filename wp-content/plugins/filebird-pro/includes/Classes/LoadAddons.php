<?php
namespace FileBird\Classes;

use FileBird\Classes\Config;

defined( 'ABSPATH' ) || exit;

class LoadAddons {
    private $loaded_addons = array( 'PostType' );

    private static $instance = null;

    public function __construct() {
		foreach ( $this->loaded_addons as $addon ) {
            if ( is_dir( NJFB_PLUGIN_PATH . '/includes/Addons/' . $addon ) ) {
                $addon_dir = NJFB_PLUGIN_PATH . '/includes/Addons/' . $addon . '/';

                if ( file_exists( $addon_dir . 'Config.php' ) ) {
                    Config::setConfig( $addon, require_once $addon_dir . 'Config.php' );
                }

                $init_class = "\FileBird\Addons\\$addon\Init";
                if ( class_exists( $init_class ) ) {
                    $init_class::getInstance();
                }
            }
		}
    }
    public static function getInstance() {
		if ( is_null( self::$instance ) ) {
            self::$instance = new self();
		}
        return self::$instance;
    }
}
