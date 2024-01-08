<?php
/**
 * Shipper helpers: API helper class
 *
 * Does the API related job
 *
 * @package shipper
 */

/**
 * API helper class.
 */
class Shipper_Helper_Api {

	/**
	 * Get the dashboard API.
	 *
	 * @return WPMUDEV_Dashboard_Api|null
	 */
	public static function get_dashboard_api() {
		if ( class_exists( 'WPMUDEV_Dashboard' ) && ! empty( \WPMUDEV_Dashboard::$api ) ) {
			return \WPMUDEV_Dashboard::$api;
		}
		return null;
	}

	/**
	 * Check if user can access Snapshot.
	 *
	 * @return boolean
	 */
	public static function user_can_access() {
		if ( class_exists( 'WPMUDEV_Dashboard' ) && method_exists( WPMUDEV_Dashboard::$upgrader, 'user_can_install' ) ) {
			return WPMUDEV_Dashboard::$upgrader->user_can_install( 2175128, true );
		}
		return false;
	}
}