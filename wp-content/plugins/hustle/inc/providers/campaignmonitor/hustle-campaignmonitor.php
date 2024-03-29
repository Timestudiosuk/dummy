<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Hustle_Campaignmonitor class
 *
 * @package Hustle
 */

if ( ! class_exists( 'Hustle_Campaignmonitor' ) ) :

	/**
	 * Class Hustle_Campaignmonitor
	 */
	class Hustle_Campaignmonitor extends Hustle_Provider_Abstract {

		const SLUG = 'campaignmonitor';

		/**
		 * Api
		 *
		 * @var AWeberAPI
		 */
		protected static $api;
		/**
		 * Errors
		 *
		 * @var array
		 */
		protected static $errors;

		/**
		 * Activecampaign Provider Instance
		 *
		 * @since 3.0.5
		 *
		 * @var self|null
		 */
		protected static $instance = null;

		/**
		 * Slug
		 *
		 * @since 3.0.5
		 * @var string
		 */
		protected $slug = 'campaignmonitor';

		/**
		 * Version
		 *
		 * @since 3.0.5
		 * @var string
		 */
		protected $version = '1.0';

		/**
		 * Class
		 *
		 * @since 3.0.5
		 * @var string
		 */
		protected $class = __CLASS__;

		/**
		 * Title
		 *
		 * @since 3.0.5
		 * @var string
		 */
		protected $title = 'Campaign Monitor';

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $form_settings = 'Hustle_Campaignmonitor_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $form_hooks = 'Hustle_Campaignmonitor_Form_Hooks';

		/**
		 * Provider constructor.
		 */
		public function __construct() {
			$this->icon_2x = plugin_dir_url( __FILE__ ) . 'images/icon.png';
			$this->logo_2x = plugin_dir_url( __FILE__ ) . 'images/logo.png';
		}

		/**
		 * Get Instance
		 *
		 * @return self|null
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get api
		 *
		 * @param string $api_key Api key.
		 * @return CS_REST_General
		 */
		public static function api( $api_key ) {
			if ( empty( self::$api ) ) {
				try {
					self::$api    = Hustle_Campaignmonitor_API::boot( $api_key );
					self::$errors = array();
				} catch ( Exception $e ) {
					self::$errors = array( 'api_error' => $e );
				}
			}

			return self::$api;
		}


		/**
		 * Get the wizard callbacks for the global settings.
		 *
		 * @since 4.0
		 *
		 * @return array
		 */
		public function settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'configure_api_key' ),
					'is_completed' => array( $this, 'is_connected' ),
				),
			);
		}


		/**
		 * Configure the API key settings. Global settings.
		 *
		 * @since 4.0
		 *
		 * @param array $submitted_data Submitted data.
		 * @return array
		 */
		public function configure_api_key( $submitted_data ) {
			$has_errors      = false;
			$default_data    = array(
				'api_key'   => '',
				'name'      => '',
				'client_id' => '',
			);
			$current_data    = $this->get_current_data( $default_data, $submitted_data );
			$is_submit       = isset( $submitted_data['api_key'] );
			$global_multi_id = $this->get_global_multi_id( $submitted_data );

			$api_key_validated    = true;
			$api_client_validated = true;
			if ( $is_submit ) {

				$api_key_validated = $this->validate_api_key( $submitted_data['api_key'] );
				$client_id         = isset( $current_data['client_id'] ) ? $current_data['client_id'] : '';
				if ( ! $api_key_validated ) {
					$error_message = $this->provider_connection_falied();
					$has_errors    = true;
				}

				if ( ! empty( $client_id ) ) {
					$api_client_validated = $this->validate_client( $submitted_data['api_key'], $client_id );
				}

				if ( ! $has_errors ) {
					$settings_to_save = array(
						'api_key' => $current_data['api_key'],
						'name'    => $current_data['name'],
					);
					$api_key          = $submitted_data['api_key'];

					$client_details = null;
					if ( ! empty( $client_id ) && $api_client_validated ) {
						$client_details = $this->get_client( $api_key, $client_id );
					} else {
						// find first client.
						$clients = self::api( $api_key )->get_clients();
						if ( is_array( $clients ) ) {
							if ( isset( $clients[0] ) ) {
								$client = $clients[0];
								if ( isset( $client->ClientID ) ) {// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
									$client_id      = $client->ClientID;// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
									$client_details = $this->get_client( $api_key, $client_id );
								}
							}
						}
					}

					if ( ! isset( $client_details->BasicDetails ) // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
							|| ! isset( $client_details->BasicDetails->ClientID ) // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
							|| ! isset( $client_details->BasicDetails->CompanyName ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						$error_message = __( 'Could not find client details, please try again.', 'hustle' );
						$has_errors    = true;
					}

					if ( ! $has_errors ) {
						$settings_to_save['client_id']   = $client_id;
						$settings_to_save['client_name'] = $client_details->BasicDetails->CompanyName;// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						$api_client_validated            = true;
					}
					// If not active, activate it.
					// TODO: Wrap this in a friendlier method.
					if ( Hustle_Provider_Utils::is_provider_active( $this->slug )
						|| Hustle_Providers::get_instance()->activate_addon( $this->slug ) ) {
						$this->save_multi_settings_values( $global_multi_id, $settings_to_save );
					} else {
						$error_message = __( "Provider couldn't be activated.", 'hustle' );
						$has_errors    = true;
					}
				}

				if ( ! $has_errors ) {

					return array(
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Campaign Monitor Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
						'buttons'      => array(
							'close' => array(
								'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'hustle' ), 'sui-button-ghost', 'close' ),
							),
						),
						'redirect'     => false,
						'has_errors'   => false,
						'notification' => array(
							'type' => 'success',
							'text' => '<strong>' . $this->get_title() . '</strong> ' . esc_html__( 'Successfully connected', 'hustle' ),
						),
					);

				}
			}

			$options = array(
				array(
					'type'     => 'wrapper',
					'class'    => $api_key_validated ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'   => array(
							'type'  => 'label',
							'for'   => 'api_key',
							'value' => __( 'API Key', 'hustle' ),
						),
						'api_key' => array(
							'type'        => 'text',
							'id'          => 'api_key',
							'name'        => 'api_key',
							'value'       => $current_data['api_key'],
							'placeholder' => __( 'Enter Key', 'hustle' ),
							'icon'        => 'key',
						),
						'error'   => array(
							'type'  => 'error',
							'class' => $api_key_validated ? 'sui-hidden' : '',
							'value' => __( 'Please enter a valid Campaign Monitor API key', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'class'    => $api_client_validated ? '' : 'sui-form-field-error',
					'elements' => array(
						'api_key_label'  => array(
							'type'  => 'label',
							'for'   => 'client_id',
							'value' => __( 'Client ID', 'hustle' ),
							'note'  => __( 'Required for Agency accounts only', 'hustle' ),
						),
						'client_id'      => array(
							'type'        => 'text',
							'id'          => 'client_id',
							'name'        => 'client_id',
							'value'       => $current_data['client_id'],
							'placeholder' => __( 'Enter Key', 'hustle' ),
							'icon'        => 'key',
						),
						'client_message' => array(
							'type'  => 'description',
							'value' => /* translators: 1. Open 'strong' tag. 2. Close 'strong' tag. */ sprintf( esc_html__( "If you have an agency account, enter the client id to connect to one of your clients' account. You can find client id on the %1\$sAccount Settings > API Keys%2\$s page.", 'hustle' ), '<strong>', '</strong>' ),
						),
						'client_error'   => array(
							'type'  => 'error',
							'class' => $api_client_validated ? 'sui-hidden' : '',
							'value' => __( 'Please, enter a valid Campaign Monitor client id.', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'style'    => 'margin-bottom: 0;',
					'elements' => array(
						'label'   => array(
							'type'  => 'label',
							'for'   => 'instance-name-input',
							'value' => __( 'Identifier', 'hustle' ),
						),
						'name'    => array(
							'type'        => 'text',
							'name'        => 'name',
							'value'       => $current_data['name'],
							'placeholder' => __( 'E.g. Business Account', 'hustle' ),
							'id'          => 'instance-name-input',
						),
						'message' => array(
							'type'  => 'description',
							'value' => __( 'Helps to distinguish your integrations if you have connected to the multiple accounts of this integration.', 'hustle' ),
						),
					),
				),
			);

			$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup(
				__( 'Configure Campaign Monitor', 'hustle' ),
				sprintf(
					/* translators: 1. link to Campaign Monitor account 2. 'Account Settings' text 3. 'API keys' text */
					esc_html__( 'To get your API key, log in to your %1$s, then click on your profile picture at the top-right corner to open a menu, then select %2$s and finally click on %3$s.', 'hustle' ),
					sprintf(
						'<a href="%1$s" target="_blank">%2$s</a>',
						'https://login.createsend.com/l/?ReturnUrl=%2Faccount%2Fapikeys',
						esc_html__( 'Campaign Monitor account' )
					),
					sprintf( '<strong>%s</strong>', esc_html__( 'Account Settings' ) ),
					sprintf( '<strong>%s</strong>', esc_html__( 'API keys' ) )
				)
			);
			if ( $has_errors ) {
				$error_notice = array(
					'type'  => 'notice',
					'icon'  => 'info',
					'class' => 'sui-notice-error',
					'value' => esc_html( $error_message ),
				);
				array_unshift( $options, $error_notice );
			}
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			$is_edit = $this->settings_are_completed( $global_multi_id );
			if ( $is_edit ) {
				$buttons = array(
					'disconnect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Disconnect', 'hustle' ),
							'sui-button-ghost',
							'disconnect',
							true
						),
					),
					'save'       => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Save', 'hustle' ),
							'',
							'connect',
							true
						),
					),
				);
			} else {
				$buttons = array(
					'connect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Connect', 'hustle' ),
							'sui-button-right',
							'connect',
							true
						),
					),
				);

			}

			$response = array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => $has_errors,
			);

			return $response;
		}

		/**
		 * Validate the provided API key.
		 *
		 * @since 4.0
		 *
		 * @param string $api_key Api key.
		 * @return bool
		 */
		private function validate_api_key( $api_key ) {
			if ( empty( trim( $api_key ) ) ) {
				return false;
			}

			// Check API Key by validating it on get_info request.
			try {
				// Check if API key is valid.
				$clients = self::api( $api_key )->get_system_date();

				if ( is_wp_error( $clients ) ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, __( 'Invalid Campaignmonitor API key.', 'hustle' ) );
					return false;
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
				return false;
			}

			return true;
		}

		/**
		 * Get client
		 *
		 * @since 1.0 Campaign Monitor Addon
		 *
		 * @param string $api_key Api key.
		 * @param string $client_id Client ID.
		 *
		 * @return array|mixed|object
		 */
		public function get_client( $api_key, $client_id ) {
			self::$api = null;
			$api       = self::api( $api_key );

			$client_details = $api->get_client( $client_id );

			return $client_details;
		}

		/**
		 * Validate Client
		 *
		 * @since 1.0 Campaign Monitor Addon
		 *
		 * @param string $api_key Api key.
		 * @param string $client_id Client ID.
		 *
		 * @return array|mixed|object
		 */
		public function validate_client( $api_key, $client_id ) {

			// Check API Key by validating it on get_info request.
			try {
				// Check if API key is valid.
				self::$api      = null;
				$api            = self::api( $api_key );
				$client_details = $api->get_client( $client_id );

				if ( is_wp_error( $client_details ) ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, __( 'Invalid Campaignmonitor Client ID key.', 'hustle' ) );
					return false;
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
				return false;
			}

			return true;
		}

		/**
		 * Get 3.0 provider mappings
		 *
		 * @return type
		 */
		public function get_30_provider_mappings() {
			return array(
				'api_key' => 'api_key',
			);
		}

	}
endif;