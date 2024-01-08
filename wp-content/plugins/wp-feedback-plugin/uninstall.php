<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
// remove options when plugin uninstalled
delete_option('wpf_decr_checksum');
delete_option('wpf_decr_key');
delete_option('wpf_license_expires');
delete_option('wpf_license');
delete_option('wpf_license_key');
delete_option('wpf_check_license_date');
/*$wpf_delete_data = get_site_data_by_key('delete_data_wpfeedback');
if($wpf_delete_data == 'yes'){
	global $wpdb;
	$wpdb->query( "DELETE FROM {$wpdb->prefix}comments WHERE comment_type = 'wp_feedback'" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id IN (SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'wpfeedback')" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}term_relationships WHERE object_id IN (SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'wpfeedback')" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy IN ('	
	task_status','task_priority')" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'wpfeedback'" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id IN (SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'wpf_graphics')" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'wpf_graphics'" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '%wpf_%'" );
}*/
