<?php
/**
 * Analytics tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

$settings   = Hustle_Settings_Admin::get_dashboard_analytics_settings();
$is_enabled = '1' === $settings['enabled'];
?>

<div id="analytics-box" class="sui-box hustle-settings-tab-analytics" data-tab="analytics"
<?php
if ( $section && 'analytics' !== $section ) {
	echo 'style="display: none;"';}
?>
>

	<div class="sui-box-header">
		<h2 class="sui-box-title"><?php esc_html_e( 'Dashboard Analytics Tracking', 'hustle' ); ?></h2>
	</div>

	<?php if ( ! Hustle_Settings_Admin::global_tracking() ) { ?>
	<div class="sui-box-body">
		<?php
		$this->get_html_for_options(
			array(
				array(
					'type'  => 'inline_notice',
					'class' => 'sui-notice-yellow',
					'icon'  => 'info',
					'value' => sprintf(
						/* translators: 1. Open link tag; 2. Close link tag. */
						esc_html__( 'Global Tracking is disabled for this site. Please enable %1$sGlobal Tracking%2$s to view the Dashboard Analytics tracking data.', 'hustle' ),
						'<a href="#" data-tab="general" class="hustle-open-tab">',
						'</a>'
					),
				),
			)
		);
		?>
	</div>
	<?php } else { ?>

	<form id="hustle-analytics-settings-form" class="sui-box-body">

		<p><?php /* translators: Plugin name */ echo esc_html( sprintf( __( "Add analytics tracking for your %s modules that doesn't require any third-party integration, and display the data in the WordPress Admin Dashboard area.", 'hustle' ), Opt_In_Utils::get_plugin_name() ) ); ?>

		<?php if ( $is_enabled ) { ?>

			<div class="sui-box-settings-row">

				<div class="sui-box-settings-col-2">

					<?php
					$this->get_html_for_options(
						array(
							array(
								'type'  => 'inline_notice',
								'class' => 'sui-notice-success',
								'icon'  => 'check-tick',
								'value' => esc_html__( 'Analytics tracking is enabled, and the widget is visible to the selected user roles in their dashboard.', 'hustle' ),
							),
						)
					);
					?>

				</div>

			</div>

			<?php
			/**
			 * Widget Title.
			 */
			$this->render(
				'admin/settings/analytics/widget-title',
				array(
					'value' => $settings['title'],
				)
			);

			/**
			 * User Role
			 */
			$this->render(
				'admin/settings/analytics/user-role',
				array(
					'value' => array_keys( $settings['role'] ),
				)
			);

			/**
			 * Modules
			 */
			$this->render(
				'admin/settings/analytics/modules',
				array(
					'values' => $settings['modules'],
				)
			);

		} else {
			?>

			<p>
				<button
					class="sui-button sui-button-blue hustle-settings-save"
					data-enabled="1"
					data-target="analytics"
				>
					<span class="sui-loading-text"><?php esc_html_e( 'Activate', 'hustle' ); ?></span>
					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</button>
			</p>

		<?php } ?>

		</form>

		<?php if ( $is_enabled ) { ?>

		<div class="sui-box-footer">

			<button
				class="sui-button sui-button-ghost hustle-settings-save"
				data-enabled="0"
				data-target="analytics"
			>
				<span class="sui-loading-text"><?php esc_html_e( 'Deactivate', 'hustle' ); ?></span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

			<div class="sui-actions-right">

				<button
					class="sui-button sui-button-blue hustle-settings-save"
					data-form-id="hustle-analytics-settings-form"
					data-target="analytics"
				>
					<span class="sui-loading-text"><?php esc_html_e( 'Save Settings', 'hustle' ); ?></span>
					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</button>

			</div>

		</div>

		<?php } ?>

	<?php } ?>

</div>