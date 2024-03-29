<?php
/**
 * Submissions behavior section.
 *
 * @package Hustle
 * @since 4.0.0
 */

// Tinymce editor styles.
ob_start();
require Opt_In::$plugin_path . 'assets/css/sui-editor.min.css';
$editor_css = ob_get_clean();
$editor_css = '<style>' . $editor_css . '</style>';

// Success message tab content.
ob_start();
?>
<div class="sui-form-field">

	<label class="sui-label sui-label-editor"><?php esc_html_e( 'Message', 'hustle' ); ?></label>

	<?php
	wp_editor(
		wp_kses_post( $settings['success_message'] ),
		'success_message',
		array(
			'media_buttons'    => false,
			'textarea_name'    => 'success_message',
			'editor_css'       => $editor_css,
			'tinymce'          => array(
				'content_css' => self::$plugin_url . 'assets/css/sui-editor.min.css',
			),
			// remove more tag from text tab.
			'quicktags'        => $this->tinymce_quicktags,
			'editor_height'    => 192,
			'drag_drop_upload' => false,
		)
	);
	?>

</div>

<div id="section-auto-close-success-message" class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Auto close success message', 'hustle' ); ?></label>

	<select name="auto_close_success_message" class="sui-select" data-attribute="auto_close_success_message">
		<option value="0" <?php selected( $settings['auto_close_success_message'], '0' ); ?>>
			<?php esc_html_e( 'Never', 'hustle' ); ?>
		</option>
		<option value="1" <?php selected( $settings['auto_close_success_message'], '1' ); ?>>
			<?php esc_html_e( 'After specified time', 'hustle' ); ?>
		</option>
	</select>

	<div class="sui-row <?php echo '0' === $settings['auto_close_success_message'] ? 'sui-hidden' : ''; ?>">

		<div class="sui-col-md-6">
			<input type="number"
				name="auto_close_time"
				data-attribute="auto_close_time"
				value="<?php echo esc_attr( $settings['auto_close_time'] ); ?>"
				placeholder="0"
				min="0"
				class="sui-form-control" />
		</div>

		<div class="sui-col-md-6">
			<select class="sui-select" name="auto_close_unit" data-attribute="auto_close_unit">
				<option value="seconds" <?php selected( $settings['auto_close_unit'], 'seconds' ); ?>>
					<?php esc_html_e( 'seconds', 'hustle' ); ?>
				</option>
				<option value="minutes" <?php selected( $settings['auto_close_unit'], 'minutes' ); ?>>
					<?php esc_html_e( 'minutes', 'hustle' ); ?>
				</option>
			</select>
		</div>

	</div>

</div>

<div class="sui-form-field">

	<label for="hustle-automated-file" class="sui-toggle hustle-toggle-with-container" data-toggle-on="automated-file">
		<input type="checkbox"
			name="automated_file"
			data-attribute="automated_file"
			id="hustle-automated-file"
			aria-labelledby="hustle-automated-file-label"
			<?php checked( $settings['automated_file'], '1' ); ?>
		/>
		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<span id="hustle-automated-file-label" class="sui-toggle-label"><?php esc_html_e( 'Enable auto downloadable file after submission', 'hustle' ); ?></span>
	</label>

	<div class="sui-border-frame sui-toggle-content" data-toggle-content="automated-file">

		<div class="sui-form-field">
			<?php
			$this->render(
				'admin/commons/sui-wizard/tab-content/image-uploader',
				array(
					'image_url'         => $settings['auto_download_file'],
					'attribute'         => 'auto_download_file',
					'button_text'       => __( 'Upload file', 'hustle' ),
					'field_title'       => __( 'Auto download file', 'hustle' ),
					'field_description' => __( 'This file will be downloaded along with showing the success message after successful submission.', 'hustle' ),
				)
			);
			?>
		</div>

	</div>

</div>
<?php
$show_success_content = ob_get_clean();

// Redirect tab content.
ob_start();
?>

<div class="sui-form-field">
	<label for="hustle-email-redirect_url" class="sui-label"><?php esc_html_e( 'Redirect URL', 'hustle' ); ?></label>

	<div class="sui-insert-variables">
		<input type="url"
			name="redirect_url"
			data-attribute="redirect_url"
			id="hustle-email-redirect_url"
			value="<?php echo esc_attr( $settings['redirect_url'] ); ?>"
			placeholder="<?php esc_html_e( 'E.g. http://website.com', 'hustle' ); ?>"
			class="sui-form-control" />

		<select
			class="sui-variables hustle-field-options hustle-select-variables"
			data-for="hustle-email-redirect_url"
			data-behavior="insert"
		></select>
	</div>

</div>

<div class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Redirection Option', 'hustle' ); ?></label>

	<select name="redirect_tab" class="sui-select" data-attribute="redirect_tab">
		<option value="sametab" <?php selected( $settings['redirect_tab'], 'sametab' ); ?>>
			<?php esc_html_e( 'Redirect on the same tab', 'hustle' ); ?>
		</option>
		<option value="newtab_thankyou" <?php selected( $settings['redirect_tab'], 'newtab_thankyou' ); ?>>
			<?php esc_html_e( 'Redirect on new tab and show thank you message on page', 'hustle' ); ?>
		</option>
		<option value="newtab_hide" <?php selected( $settings['redirect_tab'], 'newtab_hide' ); ?>>
			<?php esc_html_e( 'Redirect on new tab and hide module on page', 'hustle' ); ?>
		</option>
	</select>

</div>
<?php
$redirect_content = ob_get_clean();

$options = array(
	'show_success' => array(
		'value'   => 'show_success',
		'label'   => __( 'Success message', 'hustle' ),
		'boxed'   => true,
		'content' => $show_success_content,
	),
	'redirect'     => array(
		'value'   => 'redirect',
		'label'   => __( 'Redirect', 'hustle' ),
		'boxed'   => true,
		'content' => $redirect_content,
	),
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Submission Behavior', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Show a success message to the visitors or redirect them to another URL on successful form submission.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<?php
		$this->render(
			'admin/global/sui-components/sui-tabs',
			array(
				'name'        => 'after_successful_submission',
				'radio'       => true,
				'saved_value' => $settings['after_successful_submission'],
				'sidetabs'    => true,
				'content'     => true,
				'options'     => $options,
			)
		);
		?>

	</div>

</div>