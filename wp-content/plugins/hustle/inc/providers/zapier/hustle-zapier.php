<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Hustle_Zapier class
 *
 * @package Hustle
 */

if ( ! class_exists( 'Hustle_Zapier' ) ) :

	/**
	 * Class Hustle_Zapier
	 */
	class Hustle_Zapier extends Hustle_Provider_Abstract {

		const SLUG = 'zapier';

		/**
		 * Provider Instance
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
		protected $slug = 'zapier';

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
		protected $title = 'Zapier';

		/**
		 * Is multi on global
		 *
		 * @since 4.0
		 * @var bool
		 */
		protected $is_multi_on_global = false;

		/**
		 * Is multi on form
		 *
		 * @since 4.0
		 * @var bool
		 */
		protected $is_multi_on_form = true;

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $form_settings = 'Hustle_Zapier_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $form_hooks = 'Hustle_Zapier_Form_Hooks';

		/**
		 * Array of options which should exist for confirming that settings are completed
		 *
		 * @since 4.0
		 * @var array
		 */
		protected $completion_options = array( 'active' );

		/**
		 * Provider constructor.
		 */
		public function __construct() {
			$hide = apply_filters( 'wpmudev_branding_hide_doc_link', false );

			$this->icon_2x           = plugin_dir_url( __FILE__ ) . 'images/icon.png';
			$this->logo_2x           = plugin_dir_url( __FILE__ ) . 'images/logo.png';
			$this->banner_1x         = plugin_dir_url( __FILE__ ) . 'images/banner.png';
			$this->banner_2x         = plugin_dir_url( __FILE__ ) . 'images/banner@2x.png';
			$this->documentation_url = Opt_In_Utils::get_link( 'docs' ) . '#zapier';
			$this->short_description = sprintf(
				/* translators: 1. openning 'a' tag 2. closing 'a' tag 3. Documentation link 4. Plugin name */
				esc_html__( 'Zapier connects %4$s with %1$s1000+ apps%2$s. You can use it to send your leads to third-party apps not natively supported in %4$s and automate your after-submission workflows. %3$sHappy automating!', 'hustle' ),
				'<a href="https://zapier.com/apps" target="_blank">',
				'</a>',
				( ! $hide ? sprintf(
					/* translators: 1. openning 'a' tag 2. closing 'a' tag */
					esc_html__( 'Refer to this %1$sarticle%2$s for tips and tricks on using Zapier integration and creating automated workflows.', 'hustle' ),
					'<a href="' . esc_url( Opt_In_Utils::get_link( 'blog' ) ) . 'zapier-wordpress-form-integrations/" target="_blank">',
					'</a>'
				) : '' ),
				Opt_In_Utils::get_plugin_name()
			);
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
		 * Is active?
		 *
		 * @return bool
		 */
		public function active() {
			$setting_values = $this->get_settings_values();

			return ! empty( $setting_values['active'] );
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
					'callback'     => array( $this, 'configure_zapier' ),
					'is_completed' => array( $this, 'settings_are_completed' ),
				),
			);
		}


		/**
		 * Configure the Global settings.
		 *
		 * @since 4.0
		 *
		 * @param array $submitted_data Submitted data.
		 * @return array
		 */
		public function configure_zapier( $submitted_data ) {
			$has_errors = false;
			$active     = $this->active();
			$is_submit  = isset( $submitted_data['hustle_is_submit'] );

			if ( $is_submit ) {

				$active = ! empty( $submitted_data['active'] );
				// If not active, activate it.
				if ( ! Hustle_Provider_Utils::is_provider_active( $this->slug ) ) {
					// TODO: Wrap this in a friendlier method.
					$activated = Hustle_Providers::get_instance()->activate_addon( $this->slug );
					if ( ! $activated ) {
						$error_message = __( "Provider couldn't be activated.", 'hustle' );
						$has_errors    = true;
					} else {
						$this->save_settings_values( array( 'active' => $active ) );
					}
				} else {
					$this->save_settings_values( array( 'active' => $active ) );
				}

				if ( ! $has_errors ) {

					return array(
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Zapier Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
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
					'type'  => 'hidden',
					'name'  => 'active',
					'value' => 1,
				),
			);

			$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Configure Zapier', 'hustle' ), __( 'Activate Zapier to start using it on your forms.', 'hustle' ) );
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			if ( $has_errors ) {
				$step_html .= '<span class="sui-error-message">' . esc_html( $error_message ) . '</span>';
			}

			$is_edit = $this->is_connected() ? true : false;
			if ( $is_edit ) {
				$buttons = array(
					'disconnect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Disconnect', 'hustle' ),
							'sui-button-ghost sui-button-center',
							'disconnect',
							true
						),
					),
					'close'      => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Save', 'hustle' ),
							'sui-button-center',
							'close'
						),
					),
				);
			} else {
				$buttons = array(
					'connect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Activate', 'hustle' ),
							'sui-button-center',
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
		 * Is form connected?
		 *
		 * @param string $form_id Module ID.
		 * @return boolean
		 */
		public function is_form_connected( $form_id ) {

			$form_settings_instance = null;
			if ( ! $this->is_connected() ) {
				return false;
			}

			$form_settings_instance = $this->get_provider_form_settings( $form_id );
			if ( ! $form_settings_instance instanceof Hustle_Zapier_Form_Settings ) {
				return false;
			}

			// Mark as active when there's at least one active connection.
			if ( false === $form_settings_instance->find_one_active_connection() ) {
				return false;
			}

			return true;
		}

		/**
		 * Multiple Zapier hooks can be added to a single module which doesn't happen in any other module.
		 *
		 * Zapier data is structured differently so a custom implementation is necessary.
		 *
		 * @param Hustle_Module_Model $module Module.
		 * @param object              $old_module Old module.
		 *
		 * @return bool
		 */
		public function migrate_30( $module, $old_module ) {
			$v3_provider = ! empty( $old_module->meta['content']['email_services'][ $this->get_slug() ] )
			? $old_module->meta['content']['email_services'][ $this->get_slug() ]
			: false;

			if ( empty( $v3_provider ) ) {
				// Nothing to migrate.
				return false;
			}

			if ( '1' !== $v3_provider['enabled'] || empty( $v3_provider['api_key'] ) ) {
				return false;
			}

			// At provider level we need a single boolean.
			$this->save_settings_values( array( 'active' => true ) );
			// Activate the addon.
			Hustle_Providers::get_instance()->activate_addon( $this->get_slug() );

			// At module level.
			$module->set_provider_settings(
				$this->get_slug(),
				array(
					$this->generate_multi_id() => array(
						'name'    => '',
						'api_key' => $v3_provider['api_key'],
					),
				)
			);

			return true;
		}
	}

endif;