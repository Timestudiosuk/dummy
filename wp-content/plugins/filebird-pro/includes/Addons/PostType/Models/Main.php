<?php
namespace FileBird\Addons\PostType\Models;

use FileBird\Classes\Config;

class Main {
    public static function getFolders( $post_type, $order_by = null ) {
        $author_meta_query = array(
            'key'     => Config::getConfig( 'PostType.author_key' ),
            'value'   => '0',
            'compare' => '=',
        );
        if ( Config::getConfig( 'PostType.user_mode_enabled' ) ) {
            $author_meta_query['value'] = get_current_user_id();
        }
        $args = array(
            'taxonomy'   => self::getTaxonomyName( $post_type ),
            'hide_empty' => false,
            'meta_key'   => Config::getConfig( 'PostType.order_meta_key' ),
            'orderby'    => 'meta_value_num',
            'order'      => 'ASC',
            'meta_query' => array(
                $author_meta_query,
            ),
        );

        if ( ! is_null( $order_by ) ) {
            if ( $order_by === 'name_asc' ) {
                $args['orderby'] = 'name';
                $args['order']   = 'asc';
                unset( $args['meta_key'] );
            } elseif ( $order_by === 'name_desc' ) {
                $args['orderby'] = 'name';
                $args['order']   = 'desc';
                unset( $args['meta_key'] );
            }
        }
        $terms = get_terms( $args );
        return $terms;
    }
    public static function getFolderOfPost( $post_type, $post_id ) {
        $terms = wp_get_post_terms( (int) $post_id, self::getTaxonomyName( $post_type ), array( 'fields' => 'ids' ) );
        return isset( $terms[0] ) ? $terms[0] : null;
    }
    public static function convertFormat( $term ) {
        return json_decode(
            wp_json_encode(
                array(
					'id'      => $term->term_id,
					'text'    => $term->name,
					// 'parent' => $term->parent,
					// 'children' => array(),
					'li_attr' => array(
						'data-count'  => 0,
						'data-parent' => $term->parent,
						'real-count'  => 0,
						'style'       => '--color: #8f8f8f',
					),
                )
            )
        );
    }
    public static function sortTerms( &$terms, &$out_put, $parent_id = 0 ) {
		foreach ( $terms as $i => $cat ) {
            if ( $cat->li_attr->{'data-parent'} == $parent_id ) {
                $out_put[] = $cat;
                unset( $terms[ $i ] );
            }
		}

		foreach ( $out_put as $topCat ) {
			$topCat->children = array();
			self::sortTerms( $terms, $topCat->children, $topCat->id );
		}
    }
    public static function getTaxonomyName( $post_type ) {
        return Config::getConfig( 'PostType.taxonomy_prefix' ) . $post_type;
    }
	public static function get_post_type_relationship( $type ) {
        global $wpdb;

		$relations = $wpdb->get_results(
        $wpdb->prepare(
		 "SELECT `object_id`, GROUP_CONCAT(`term_id`) as terms
                FROM {$wpdb->prefix}term_relationships
                INNER JOIN {$wpdb->prefix}term_taxonomy 
                ON {$wpdb->prefix}term_taxonomy.term_taxonomy_id = {$wpdb->prefix}term_relationships.term_taxonomy_id
                WHERE {$wpdb->prefix}term_taxonomy.taxonomy = %s
                GROUP BY object_id",
            "fbv_pt_tax_$type"
            )
        );

		$res = array();

        if ( count( $relations ) === 1 && $relations[0]->object_id === null ) {
            return $res;
        }

		foreach ( $relations as $k => $v ) {
			$res[ $v->object_id ] = array_map( 'intval', explode( ',', $v->terms ) );
		}
		return $res;
    }
}
