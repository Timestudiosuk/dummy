<?php
/**
 * Shipper package settings templates: safe-mode item body template
 *
 * @since v1.2.4
 * @package shipper
 */

$safe_mode_id = 'safe-mode';
$label        = 'label-' . $safe_mode_id;
$model        = new Shipper_Model_Stored_Options();
$safe_mode    = $model->get( Shipper_Model_Stored_Options::KEY_PACKAGE_SAFE_MODE, false );
?>
<div class="sui-form-field">
	<div class="sui-form-field">
		<label for="<?php echo esc_attr( $safe_mode_id ); ?>" class="sui-toggle">
			<input type="hidden" name="safe-mode" value="0" />
			<input
				type="checkbox" <?php checked( $safe_mode ); ?>
				name="safe-mode"
				value="1"
				id="<?php echo esc_attr( $safe_mode_id ); ?>"
				aria-labelledby="<?php echo esc_attr( $label ); ?>"
			/>
			<span class="sui-toggle-slider" aria-hidden="true"></span>
			<span id="<?php echo esc_attr( $label ); ?>" class="sui-toggle-label"><?php esc_html_e( 'Safe Mode Migration', 'shipper' ); ?></span>

			<span id="<?php echo esc_attr( $label . '-description' ); ?>" class="sui-description">
				<?php
				echo wp_kses_post(
					sprintf(
						__( 'By enabling this option, Shipper can track the processing time and make sure it never exceeds the max_execution_time set by the server. The smaller the max_execution_time value, the longer Shipper will take to complete the process. <br> Shipper will skip files that are larger than 50MB. These will be logged in Shipper > Tools > Logs so you can always import those files manually.', 'shipper' )
					)
				);
				?>
			</span>
		</label>
	</div>
</div><!-- sui-form-field -->