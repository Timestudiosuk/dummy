<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Hustle_Providers_Admin
 *
 * @package Hustle
 */

/**
 * Class Hustle_Providers_Admin
 * This class handles the global "Integrations" page view.
 *
 * @since 4.0
 */
class Hustle_Providers_Admin extends Hustle_Admin_Page_Abstract {

	/**
	 * Init
	 */
	public function init() {

		$this->page = 'hustle_integrations';

		/* translators: Plugin name */
		$this->page_title = sprintf( __( '%s Integrations', 'hustle' ), Opt_In_Utils::get_plugin_name() );

		$this->page_menu_title = __( 'Integrations', 'hustle' );

		$this->page_capability = 'hustle_edit_integrations';

		$this->page_template_path = 'admin/integrations';
	}

	/**
	 * Get the arguments used when rendering the main page.
	 *
	 * @since 4.0.1
	 * @return array
	 */
	public function get_page_template_args() {
		$accessibility = Hustle_Settings_Admin::get_hustle_settings( 'accessibility' );
		return array(
			'accessibility' => $accessibility,
			'sui'           => $this->get_sui_summary_config(),
		);
	}

	/**
	 * Register js variables.
	 * Used for when an integration comes back from an external redirect.
	 * For example, when doing oAuth with Hubspot.
	 *
	 * @since 4.3.1
	 *
	 * @return array
	 */
	protected function get_vars_to_localize() {
		$current_array = parent::get_vars_to_localize();

		$current_array['integration_redirect'] = $this->grab_integration_external_redirect();
		$current_array['integrations_url']     = add_query_arg( 'page', Hustle_Data::INTEGRATIONS_PAGE, admin_url( 'admin.php' ) );
		$current_array['integrations_migrate'] = $this->grab_integration_external_redirect_migration();

		// Also defined wizards.
		$current_array['providers_action_nonce'] = wp_create_nonce( 'hustle_provider_action' );
		$current_array['fetching_list']          = esc_html__( 'Fetching integration list…', 'hustle' );

		return $current_array;
	}

	/**
	 * Attach back the addon after its external redirect.
	 * Return an array provided by the provider for handling
	 * the user's experience after coming back from the redirect.
	 *
	 * @since 4.0.2
	 * @return array
	 */
	private function grab_integration_external_redirect() {

		$response  = array();
		$action    = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS );
		$migration = filter_input( INPUT_GET, 'migration', FILTER_VALIDATE_BOOLEAN );

		// handle migration elsewhere.
		if ( 'external-redirect' === $action && true !== $migration ) {

			$nonce = filter_input( INPUT_GET, 'nonce' );

			if ( $nonce && wp_verify_nonce( $nonce, 'hustle_provider_external_redirect' ) ) {

				$slug = filter_input( INPUT_GET, 'slug', FILTER_SANITIZE_SPECIAL_CHARS );

				$provider = Hustle_Provider_Utils::get_provider_by_slug( $slug );

				if ( $provider instanceof Hustle_Provider_Abstract ) {

					$response = $provider->process_external_redirect();
					if ( ! empty( $response ) ) {
						$response['slug'] = $slug;
					}
				}
			} else {

				$response = array(
					'action'  => 'notification',
					'status'  => 'error',
					'message' => esc_html__( "You're not allowed to do this request.", 'hustle' ),
				);
			}
		}

		return $response;
	}

	/**
	 * Attach back the addon after its external redirect for migration.
	 * Return an array provided by the provider for handling
	 * the user's experience after coming back from the redirect.
	 *
	 * @since 4.0.3
	 * @return array
	 */
	private function grab_integration_external_redirect_migration() {

		$response  = array();
		$action    = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS );
		$migration = filter_input( INPUT_GET, 'migration', FILTER_VALIDATE_BOOLEAN );
		$provider  = esc_html( filter_input( INPUT_GET, 'show_provider_migration', FILTER_SANITIZE_SPECIAL_CHARS ) );
		$multi_id  = esc_html( filter_input( INPUT_GET, 'integration_id', FILTER_SANITIZE_SPECIAL_CHARS ) );

		if ( ! empty( $provider ) ) {
			$response['provider_modal'] = $provider;
		}

		if ( ! empty( $multi_id ) ) {
			$response['integration_id'] = $multi_id;
		}

		if ( 'external-redirect' === $action && true === $migration ) {

			$nonce = filter_input( INPUT_GET, 'nonce' );

			if ( $nonce && wp_verify_nonce( $nonce, 'hustle_provider_external_redirect' ) ) {

				$slug = esc_html( filter_input( INPUT_GET, 'slug', FILTER_SANITIZE_SPECIAL_CHARS ) );

				$response['migration_notificaiton'] = array(
					'action' => 'notification',
					'status' => 'success',
					'slug'   => $slug,
				);

				if ( 'constantcontact' === $slug ) {
					$response['migration_notificaiton']['message'] = /* translators: integration type */ sprintf( esc_html__( '%s integration successfully migrated to the v3.0 API version.', 'hustle' ), '<strong>' . esc_html__( 'Constant Contact', 'hustle' ) . '</strong>' );
				}

				if ( 'infusionsoft' === $slug ) {
					$response['migration_notificaiton']['message'] = /* translators: integration type */ sprintf( esc_html__( '%s integration successfully migrated to use the REST API.', 'hustle' ), '<strong>' . esc_html__( 'Keap', 'hustle' ) . '</strong>' );
				}
			} else {

				$response = array(
					'action'  => 'notification',
					'status'  => 'error',
					'message' => esc_html__( "You're not allowed to do this request.", 'hustle' ),
				);
			}
		}

		return $response;
	}

}