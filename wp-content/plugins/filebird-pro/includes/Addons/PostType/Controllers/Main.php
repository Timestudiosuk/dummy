<?php
namespace FileBird\Addons\PostType\Controllers;

use FileBird\Addons\PostType\Models\Main as MainModel;

use FileBird\Classes\Helpers;
use FileBird\Classes\Config;
use wpdb;

class Main {
    public static function restGetFolders( \WP_REST_Request $request ) {

        $post_type = sanitize_text_field( $request->get_param( 'post_type' ) );
        $sort      = strtolower( sanitize_text_field( $request->get_param( 'sort' ) ) );

        $res = array(
			'folder_count' => array(
				'folders' => array(),
				'total'   => 0,
			),
			'tree'         => array(),
        );

        if ( in_array( $post_type, Config::getConfig( 'PostType.enabled_posttype' ) ) ) {
           $order_by = null;

            if ( \in_array( $sort, array( 'name_asc', 'name_desc', 'reset' ) ) ) {
               $order_by = $sort;
               update_option( Config::getConfig( 'PostType.saved_sort_folder_key' ), $order_by );
                if ( $order_by === 'reset' ) {
                   $order_by = null;
                }
            } else {
                $njt_fb_sort_folder_pt = get_option( Config::getConfig( 'PostType.saved_sort_folder_key' ), 'reset' );
                if ( $njt_fb_sort_folder_pt === 'reset' ) {
                   $order_by = null;
                } elseif ( $njt_fb_sort_folder_pt === 'name_asc' || $njt_fb_sort_folder_pt === 'name_desc' ) {
                   $order_by = $njt_fb_sort_folder_pt;
                }
            }

            $terms = MainModel::getFolders( $post_type, $order_by );

            $res['folder_count']['folders'] = self::getCountFromTerms( $post_type, $terms );
            $res['folder_count']['total']   = self::getCountOfPost( $post_type );

            $terms = array_map( array( MainModel::class, 'convertFormat' ), $terms );
            $tree  = array();
            MainModel::sortTerms( $terms, $tree, 0 );
            $res['tree'] = $tree;
        }
        wp_send_json_success( $res );
    }
    public static function restNewFolder( \WP_REST_Request $request ) {
        $post_type   = sanitize_text_field( $request->get_param( 'post_type' ) );
        $folder_name = sanitize_text_field( $request->get_param( 'folder_name' ) );
        $parent      = (int) $request->get_param( 'parent' );

       //TODO validate if parent is valid
        $res = wp_insert_term( $folder_name, MainModel::getTaxonomyName( $post_type ), array( 'parent' => $parent ) );
        if ( is_wp_error( $res ) ) {
            wp_send_json_error( array( 'res' => $res->errors ) );
        } else {
            update_term_meta( $res['term_id'], Config::getConfig( 'PostType.order_meta_key' ), '0' );
            if ( Config::getConfig( 'PostType.user_mode_enabled' ) ) {
                update_term_meta( $res['term_id'], Config::getConfig( 'PostType.author_key' ), get_current_user_id() );
            } else {

                update_term_meta( $res['term_id'], Config::getConfig( 'PostType.author_key' ), '0' );
            }
            wp_send_json_success( array( 'id' => $res['term_id'] ) );
        }
    }
    public static function restEditFolder( \WP_REST_Request $request ) {
        $post_type   = sanitize_text_field( $request->get_param( 'post_type' ) );
        $folder_name = sanitize_text_field( $request->get_param( 'folder_name' ) );
        $id          = (int) $request->get_param( 'id' );
        $parent      = (int) $request->get_param( 'parent' );

        //TODO validate if parent is valid
        if ( ! self::hasAccess( $id ) ) {
            wp_send_json_error( array( 'mess' => __( 'Could not edit this folder (author error)', 'filebird' ) ) );
        }

        $res = wp_update_term(
            $id,
            MainModel::getTaxonomyName( $post_type ),
            array(
                'name'   => $folder_name,
                'parent' => $parent,
            )
        );
        if ( is_wp_error( $res ) ) {
            wp_send_json_error(
                array(
                    'res'  => $res->errors,
                    'mess' => __( 'Could not edit this folder', 'filebird' ),
                )
            );
        } else {
            wp_send_json_success();
        }
    }
    public static function restDeleteFolder( \WP_REST_Request $request ) {
        $post_type = sanitize_text_field( $request->get_param( 'post_type' ) );
        $ids       = $request->get_param( 'ids' );
        $ids       = isset( $ids ) ? Helpers::sanitize_array( $ids ) : '';

        if ( $ids != '' ) {
            if ( ! is_array( $ids ) ) {
                $ids = array( $ids );
            }
            $ids = array_map( 'intval', $ids );

            foreach ( $ids as $id ) {
                if ( $id > 0 && self::hasAccess( $id ) ) {
                    wp_delete_term( $id, MainModel::getTaxonomyName( $post_type ) );
                }
            }
            wp_send_json_success( array( 'mess' => __( 'Success', 'filebird' ) ) );
        }
        wp_send_json_error(
            array(
                'mess' => __( 'Can\'t delete folder, please try again later', 'filebird' ),
            )
        );
    }
    public static function restSetFolder( $request ) {
        $ids       = $request->get_param( 'ids' );
        $folder    = (int) $request->get_param( 'folder' );
        $post_type = $request->get_param( 'post_type' );
        //TODO user mode
        $ids = isset( $ids ) ? Helpers::sanitize_array( $ids ) : '';
        if ( $folder > 0 && ! self::hasAccess( $folder ) ) {
            wp_send_json_error(
                array(
                    'mess' => __( 'Author Error', 'filebird' ),
                )
            );
        }
        if ( $ids != '' && is_array( $ids ) ) {
            foreach ( $ids as $id ) {
                if ( $folder > 0 ) {
                    wp_set_post_terms( (int) $id, array( $folder ), MainModel::getTaxonomyName( $post_type ) );
                } elseif ( $folder == 0 ) {
                    $old_folders = wp_get_post_terms( (int) $id, MainModel::getTaxonomyName( $post_type ), array( 'fields' => 'ids' ) );
                    if ( is_array( $old_folders ) && count( $old_folders ) > 0 ) {
                        wp_remove_object_terms( (int) $id, $old_folders, MainModel::getTaxonomyName( $post_type ) );
                    }
                }
            }
            wp_send_json_success( array( 'relations' => MainModel::get_post_type_relationship( $post_type ) ) );
        }
        wp_send_json_error(
            array(
                'mess' => __( 'Validation failed', 'filebird' ),
            )
        );
    }
    public static function restUpdateTree( $request ) {
        global $wpdb;

        $tree      = Helpers::sanitize_array( $request->get_param( 'tree' ) );
        $post_type = Helpers::sanitize_array( $request->get_param( 'post_type' ) );

        if ( in_array( $post_type, Config::getConfig( 'PostType.enabled_posttype' ) ) ) {
           // Update order
           // $update_order_query = preg_replace( '#\(([0-9]+),([0-9]+),([0-9]+)\)#', '($1,\'' . $this->order_meta_key . '\',$2)', $tree );
           // $wpdb->query( "INSERT INTO $wpdb->termmeta (term_id, meta_key, meta_value) VALUES $update_order_query ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value),meta_key=VALUES(meta_key)" );

           // Update parent
           // $update_parent_query = preg_replace( '#\(([0-9]+),([0-9]+),([0-9]+)\)#', '($1,$3,parent)', $tree );

            $tree    = ltrim( $tree, '(' );
            $tree    = rtrim( $tree, ')' );
            $folders = explode( '),(', $tree );
            foreach ( $folders as $folder ) {
               // [ $id, $order, $parent ] = explode( ',', $folder );
               $ex = explode( ',', $folder );
				if ( count( $ex ) === 3 ) {
					$id     = (int) $ex[0];
					$order  = (int) $ex[1];
					$parent = (int) $ex[2];
					//TODO check if current user has access to this folder
					//Update order
					update_term_meta( $id, 'fbv_tax_order', $order );
					//update parent
					wp_update_term( $id, MainModel::getTaxonomyName( $post_type ), array( 'parent' => $parent ) );
				}
            }
            wp_send_json_success( array( 'mess' => __( 'Success', 'filebird' ) ) );
        }
        wp_send_json_error(
            array(
                'mess' => __( 'Post Type not enabled.', 'filebird' ),
            )
        );
    }
    private static function getCountFromTerms( $post_type, $terms ) {
        $res = array();
        foreach ( $terms as $term ) {
            $count = Helpers::getCountPostsWithTerm( $post_type, $term->term_id, MainModel::getTaxonomyName( $post_type ) );
            if ( $count > 0 ) {
                $res[ $term->term_id ] = $count;
            }
        }
       return $res;
    }
    private static function getCountOfPost( $post_type ) {
        global $wpdb;
       $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = %s AND (post_status = 'publish' OR post_status = 'draft')", $post_type ) );
       return (int) $count;
    }

    private static function hasAccess( $folder_id ) {
        $has_access = false;
        $author     = get_term_meta( $folder_id, Config::getConfig( 'PostType.author_key' ), true );
        if ( Config::getConfig( 'PostType.user_mode_enabled' ) ) {
            $has_access = ( (int) $author == (int) get_current_user_id() );
        } else {
            $has_access = ( $author === '0' );
        }
        return $has_access;
    }
}
