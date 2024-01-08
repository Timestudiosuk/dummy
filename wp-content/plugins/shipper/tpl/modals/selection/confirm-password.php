<?php
/**
 * Shipper modal templates: site selection, confirm password template
 *
 * @since 1.2
 * @package shipper
 */

$get_data     = wp_unslash( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce is already verified.
$username     = shipper_get_dashboard_username();
$form_action  = shipper_get_dashboard_authentication_url();
$redirect_url = network_admin_url( 'admin.php?page=shipper-api&type=' . $type );
$auth_key     = ! empty( $get_data['set_apikey'] ) ? trim( $get_data['set_apikey'] ) : '';
$assets       = new Shipper_Helper_Assets();
$wpmudev_url  = shipper_get_wpmudev_custom_api_server();

$http_query   = array(
	'page'    => 'shipper-api',
	'type'    => filter_input( INPUT_GET, 'type' ),
	'referer' => 'google_login',
);
?>

	<figure class="sui-box-logo" aria-hidden="true">
		<img
			src="<?php echo esc_url( $assets->get_asset( 'img/wpmudev.png' ) ); ?>"
			srcset="<?php echo esc_url( $assets->get_asset( 'img/wpmudev@2x.png' ) ); ?> 2x"
		>

	</figure>

	<button class="sui-button-icon sui-button-float--right shipper-cancel">
		<i class="sui-icon-close sui-md" aria-hidden="true"></i>
		<span class="sui-screen-reader-text">
			<?php esc_attr_e( 'Close the modal', 'shipper' ); ?>
		</span>
	</button>

	<h3 class="sui-box-title sui-lg">
		<?php esc_html_e( 'Confirm WPMU DEV Password', 'shipper' ); ?>
	</h3>

	<p class="shipper-description">
		<?php
		echo wp_kses_post(
			sprintf(
				/* translators: %s: admin username. */
				__( 'As a security measure you\'ll need to confirm your WPMU DEV account to continue. Please enter the password for your connected account <strong>(%s)</strong> or authenticate with Google.', 'shipper' ),
				$username
			)
		);
		?>
	</p>
</div>

	<div class="sui-box-body sui-box-body-slim sui-block-content-center">
		<form action="<?php echo esc_url( $form_action ); ?>" method="POST">
			<div class="sui-form-field shipper-with-button-icon">
				<label for="shipper-wpmu-dev-password" id="shipper-wpmu-dev-password-label" class="sui-label">
					<?php esc_html_e( 'WPMU DEV Password', 'shipper' ); ?>
				</label>

				<input
					type="password"
					name="password"
					id="shipper-wpmu-dev-password"
					placeholder="<?php esc_attr_e( 'Enter your WPMU DEV password', 'shipper' ); ?>"
					class="sui-form-control"
					aria-labelledby="shipper-wpmu-dev-password-label"
				/>

				<button class="sui-button-icon shipper-password-eye" type="button" style="display: none">
					<i class="sui-icon-eye" aria-hidden="true"></i>
				</button>

				<input type="hidden" id="shipper-username" name="username" value="<?php echo esc_attr( $username ); ?>">
				<input type="hidden" id="shipper-redirect-url" name="redirect_url" value="<?php echo esc_url( $redirect_url ); ?>">

				<span id="shipper-wpmu-dev-password-error" class="sui-error-message" style="display: none;">
					<?php esc_html_e( 'The password you entered is incorrect. Please try again.', 'shipper' ); ?>
				</span>

				<span id="shipper-wpmu-dev-password-something-went-wrong" class="sui-error-message" style="display: none;">
					<?php esc_html_e( 'Something went wrong. Please try again.', 'shipper' ); ?>
				</span>
			</div>

			<div class="sui-form-field">
				<button
					type="submit"
					class="sui-button shipper-confirm-password"
					data-auth-key="<?php echo esc_attr( $auth_key ); ?>"
					data-confirm-password="<?php echo esc_attr( wp_create_nonce( 'shipper_confirm_wpmudev_password' ) ); ?>"
				>
					<span class="sui-loading-text">
						<?php esc_html_e( 'Continue', 'shipper' ); ?>
						<i class="sui-icon-chevron-right" aria-hidden="true"></i>
					</span>
					<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
				</button>
			</div>
		</form>

		<div class="shipper-google-login">
			<hr class="sui-separator">

			<div class="shipper-google-alt">
				<p style="font-size: 13px;"><?php esc_html_e( 'Don\'t have a WPMU DEV password?', 'shipper' ); ?> <strong><?php esc_html_e( 'Authenticate with Google instead.', 'shipper' ); ?></strong></p>
			</div>

			<form action="<?php echo esc_url( $wpmudev_url ); ?>api/dashboard/v2/google-auth" id="shipper-google-login" method="POST">
				<div class="sui-form-field"></div>

				<input type="hidden" name="redirect_url" value="<?php echo esc_url( shipper_get_site_url( 'redirect', $http_query ) ); ?>">
				<input type="hidden" name="domain" value="<?php echo esc_url( shipper_get_site_url( 'domain' ) ); ?>">
				<input type="hidden" name="context" value="shipper">

				<button class="sui-button shipper-google-login-button" type="submit">
					<span class="icon-google-color-svg" style="position: relative; top: 2px; margin-right: 5px;">
						<svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2.58694 6.00004C2.58694 5.61032 2.65163 5.23663 2.76722 4.8862L0.745031 3.34204C0.350906 4.1422 0.128906 5.04388 0.128906 6.00004C0.128906 6.95545 0.350719 7.85648 0.744187 8.65617L2.76525 7.10901C2.65078 6.76017 2.58694 6.38788 2.58694 6.00004Z" fill="#FBBC05"/>
							<path d="M6.1385 2.45456C6.98516 2.45456 7.74988 2.75456 8.35072 3.24544L10.0986 1.5C9.0335 0.572719 7.66794 0 6.1385 0C3.764 0 1.72325 1.35787 0.746094 3.342L2.76819 4.88616C3.23413 3.47184 4.56228 2.45456 6.1385 2.45456Z" fill="#EA4335"/>
							<path d="M6.1385 9.54536C4.56238 9.54536 3.23422 8.52808 2.76828 7.11377L0.746094 8.65764C1.72325 10.6421 3.764 11.9999 6.1385 11.9999C7.604 11.9999 9.00322 11.4795 10.0533 10.5045L8.13387 9.02064C7.59228 9.3618 6.91025 9.54536 6.1385 9.54536Z" fill="#34A853"/>
							<path d="M11.8721 6.00005C11.8721 5.64549 11.8174 5.26365 11.7355 4.90918H6.13672V7.22734H9.35947C9.19831 8.01774 8.75975 8.62534 8.13209 9.02077L10.0515 10.5046C11.1546 9.4809 11.8721 7.95577 11.8721 6.00005Z" fill="#4285F4"/>
						</svg>
					</span>
					<?php esc_html_e( 'Authenticate with Google', 'shipper' ); ?>
				</button>
			</form>
		</div>
	</div>