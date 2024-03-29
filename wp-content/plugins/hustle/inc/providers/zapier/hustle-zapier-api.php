<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Hustle_Zapier_API class
 *
 * @package Hustle
 */

/**
 * Class Hustle_Zapier_API
 */
class Hustle_Zapier_API {

	/**
	 * Make requet
	 *
	 * @param string $url URL.
	 * @param array  $args Args.
	 * @return boolean
	 */
	public static function make_request( $url, $args = array() ) {
		$request  = apply_filters(
			'hustle_zapier_args',
			array(
				'timeout' => 10,
				'body'    => wp_json_encode( $args ),
				'headers' => array(
					'Accept'       => 'application/json',
					'Content-Type' => 'application/json',
				),
			)
		);
		$response = wp_remote_post( $url, $request );

		// logging data.
		$utils                     = Hustle_Provider_Utils::get_instance();
		$utils->last_url_request   = $url;
		$utils->last_data_received = $response;
		$utils->last_data_sent     = $request;

		if (
			is_wp_error( $response )
			|| wp_remote_retrieve_response_code( $response ) > 200
		) {
			return self::error();
		}

		$json = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $json['status'] ) || 'success' !== $json['status'] ) {
			return self::error();
		}

		return true;
	}

	/**
	 * Return error
	 *
	 * @return WP_Error
	 */
	private static function error() {
		return new WP_Error(
			'remote_zapier_error',
			esc_html__( 'Call to Zapier hook failed', 'hustle' )
		);
	}
}