<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Hustle_Mad_Mimi_Api class
 *
 * @package Hustle
 */

/**
 * Mad Mimi API implementation
 *
 * Class Hustle_Mad_Mimi_Api
 */
class Hustle_Mad_Mimi_Api {

	/**
	 * API Key
	 *
	 * @var string
	 */
	private $api_key;

	/**
	 * API Key
	 *
	 * @var string
	 */
	private $user_name;

	/**
	 * Madmimi API Url
	 *
	 * @var array
	 */
	private $endpoint = 'https://madmimi.com/api/v3/';

	/**
	 * Instances of madmimi
	 *
	 * @since 4.0.2
	 * @var array
	 */
	private static $instances = array();

	/**
	 * Version of madmimi API Wrapper
	 *
	 * @since 4.0.2
	 * @var string
	 */
	const HUSTLE_PROVIDER_MADMIMI_VERSION = '1.0';

	/**
	 * Construct the class
	 *
	 * Here constructor is private becase we
	 * want to force the `boot()` method to
	 * initate the class and maitain instances
	 *
	 * @param string $user_name User name.
	 * @param string $api_key Api key.
	 * @throws Exception Missing required API Credentials.
	 */
	private function __construct( $user_name, $api_key ) {
		if ( ! $api_key || ! $user_name ) {
			throw new Exception( __( 'Missing required API Credentials', 'hustle' ) );
		}
		$this->user_name = $user_name;
		$this->api_key   = $api_key;
	}

	/**
	 * Get singleton
	 *
	 * @since 4.0.2
	 *
	 * @param string $user_name User name.
	 * @param string $api_key Api key.
	 * @return Hustle_Madmimi_Api|null
	 */
	public static function boot( $user_name, $api_key ) {

		$instance_key = md5( $api_key );

		if ( ! isset( self::$instances[ $instance_key ] ) ) {
			self::$instances[ $instance_key ] = new static( $user_name, $api_key );
		}

		return self::$instances[ $instance_key ];
	}

	/**
	 * Sends request to the endpoint url with the provided $action
	 *
	 * @param string $action rest action.
	 * @param string $verb Verbs.
	 * @param array  $args Args.
	 * @return object|WP_Error
	 * @throws Exception Failed to process request.
	 */
	private function request( $action, $verb = 'GET', $args = array() ) {

		// Adding extra user agent for wp remote request.
		add_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		$url = esc_url( trailingslashit( $this->endpoint ) . $action );

		/**
		 * Filter madmimi url to be used on sending api request
		 *
		 * @since 1.1
		 *
		 * @param string $url  full url with scheme
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`
		 * @param array  $args argument sent to this function
		 */
		$url = apply_filters( 'hustle_provider_madmimi_api_url', $url, $verb, $args );

		$url = add_query_arg(
			array(
				'api_key'  => $this->api_key,
				'username' => rawurlencode( $this->user_name ),
			),
			$url
		);

		$headers = array(
			'Accept'       => 'application/json',
			'Content-Type' => 'application/json',
		);
		/**
		 * Filter madmimi headers to sent on api request
		 *
		 * @since 1.1
		 *
		 * @param array  $headers
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`
		 * @param string $url  full url with scheme
		 * @param array  $args argument sent to this function
		 */
		$headers = apply_filters( 'hustle_provider_madmimi_api_request_headers', $headers, $verb, $url, $args );

		$_args = array(
			'method'  => $verb,
			'headers' => $headers,
		);

		$request_data = $args;

		/**
		 * Filter madmimi request data to be used on sending api request
		 *
		 * @since 1.1
		 *
		 * @param array  $request_data it will be `http_build_query`-ed when `GET` or `wp_json_encode`-ed otherwise
		 * @param string $verb         `GET` `POST` `PUT` `DELETE` `PATCH`
		 * @param string $url         requested path resource
		 */
		$args = apply_filters( 'hustle_provider_madmimi_api_request_data', $request_data, $verb, $url );

		if ( 'GET' === $verb ) {
			$url .= ( '&' . http_build_query( $args ) );
		} else {
			$_args['body'] = wp_json_encode( $args );
		}

		$res = wp_remote_request( $url, $_args );

		// logging data.
		$utils                     = Hustle_Provider_Utils::get_instance();
		$utils->last_url_request   = $url;
		$utils->last_data_sent     = $args;
		$utils->last_data_received = $res;

		$wp_response = $res;

		remove_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		if ( is_wp_error( $res ) || ! $res ) {
			throw new Exception(
				__( 'Failed to process request, make sure your Webhook URL is correct and your server has internet connection.', 'hustle' )
			);
		}

		$body = wp_remote_retrieve_body( $res );

		// probably silent mode.
		if ( ! empty( $body ) ) {
			$res = json_decode( $body );
		}

		if ( isset( $wp_response['response']['code'] ) ) {
			$status_code = $wp_response['response']['code'];
			$msg         = '';
			if ( $status_code >= 400 ) {
				if ( isset( $wp_response['response']['message'] ) ) {
					$msg = $wp_response['response']['message'];
				}

				if ( ! is_null( $res ) && is_object( $res ) && isset( $res->message ) ) {
					$msg = $res->message;
				}

				/* translators: error message */
				throw new Exception( sprintf( __( 'Failed to processing request : %s', 'hustle' ), $msg ) );
			}
		}

		$response = $res;

		/**
		 * Filter madmimi api response returned to addon
		 *
		 * @since 4.0.2
		 *
		 * @param mixed          $response    original wp remote request response or decoded body if available
		 * @param string         $body        original content of http response's body
		 * @param array|WP_Error $wp_response original wp remote request response
		 */
		$res = apply_filters( 'hustle_madmimi_api_response', $response, $body, $wp_response );

		return $res;
	}

	/**
	 * Add custom user agent on request
	 *
	 * @since 4.0.1
	 *
	 * @param string $user_agent User agent.
	 *
	 * @return string
	 */
	public function filter_user_agent( $user_agent ) {
		$user_agent .= ' HustleMadMimi/' . self::HUSTLE_PROVIDER_MADMIMI_VERSION;

		/**
		 * Filter user agent to be used by madmimi api
		 *
		 * @since 1.1
		 *
		 * @param string $user_agent current user agent
		 */
		$user_agent = apply_filters( 'hustle_provider_madmimi_api_user_agent', $user_agent );

		return $user_agent;
	}

	/**
	 * Sends rest GET request
	 *
	 * @param string $action Action.
	 * @param array  $args Args.
	 * @return array|mixed|object|WP_Error
	 */
	private function get( $action, $args = array() ) {
		return $this->request( $action, 'GET', $args );
	}

	/**
	 * Sends rest POST request
	 *
	 * @param string $action Action.
	 * @param array  $args Args.
	 * @return array|mixed|object|WP_Error
	 */
	private function post( $action, $args = array() ) {
		return $this->request( $action, 'POST', $args );
	}

	/**
	 * Sends rest PUT request
	 *
	 * @param string $action Action.
	 * @param array  $args Args.
	 * @return array|mixed|object|WP_Error
	 */
	private function put( $action, $args = array() ) {
		return $this->request( $action, 'PUT', $args );
	}

	/**
	 * Retrieves lists as array of objects
	 *
	 * @param array $data Data.
	 * @return array|WP_Error
	 */
	public function get_lists( $data = array() ) {
		return $this->get( 'subscriberLists', $data );
	}

	/**
	 * Retrieves lists as array of objects
	 *
	 * @param array $data Data.
	 * @return array|WP_Error
	 */
	public function get_subscriber( $data = array() ) {
		return $this->get( 'subscribers', $data );
	}

	/**
	 * Add new contact
	 *
	 * @param string $list List.
	 * @param array  $data Data.
	 * @return array|mixed|object|WP_Error
	 */
	public function subscribe( $list, array $data ) {
		$res = $this->post( 'subscribers', $data );
		if ( isset( $res->subscriber->id ) ) {
			$id = $res->subscriber->id;
			$this->update_subscriber_list( $id, array( $list ) );
		}
		return $res;
	}

	/**
	 * Update a subscriber
	 *
	 * @param string $id ID.
	 * @param array  $data Data.
	 * @param array  $list List.
	 * @return array|mixed|object|WP_Error
	 */
	public function update_subscriber( $id, array $data, $list = array() ) {

		$action = 'subscribers/' . $id;
		$res    = $this->put( $action, $data );

		if ( ! empty( $list && isset( $res->subscriber->id ) ) ) {
			$id = $res->subscriber->id;
			$this->update_subscriber_list( $id, $list );
		}
		return $res;
	}

	/**
	 * Update subscriber list
	 *
	 * @since 4.0.2
	 *
	 * @param string $id ID.
	 * @param array  $list List.
	 */
	public function update_subscriber_list( $id, $list ) {
		$res = $this->put( 'subscribers/' . $id . '/memberships/', array( 'add' => $list ) );
		if ( isset( $res->subscriber->id ) ) {
			$id = $res->subscriber->id;
			$this->update_subscriber_list( $id, $list );
		}
	}

	/**
	 * Delete subscriber from the list
	 *
	 * @param string $list_id List ID.
	 * @param string $email Email.
	 *
	 * @return bool
	 */
	public function delete_email( $list_id, $email ) {
		$existing_member = $this->get_subscriber( array( 'query' => $email ) );
		if ( empty( $existing_member->subscribers[0]->id ) ) {
			return false;
		}
		$member_id = $existing_member->subscribers[0]->id;
		$res       = $this->put( 'subscribers/' . $member_id . '/memberships/', array( 'remove' => array( $list_id ) ) );

		return ! is_wp_error( $res );
	}
}