<?php
/**
 * Template for the unsubscribe form in frontend.
 *
 * @package Hustle
 * @since 4.0.0
 */

if ( ! $ajax_step ) : ?>
	<?php $button_text = ! empty( $skip_confirmation ) ? $messages['submit_button_text'] : $messages['get_lists_button_text']; ?>
	<?php $step = ! empty( $skip_confirmation ) ? 'choose_list' : 'enter_email'; ?>
	<form class="hustle-unsubscribe-form">

		<span
			class="wpoi-submit-failure"
			style="display:none;"
			data-default-error="<?php esc_html_e( 'There was an error submitting the form', 'hustle' ); ?>"
		></span>

		<div class="hustle-form-body">

			<div class="hustle-email-section">
				<input type="email"
					name="email"
					class="required"
					placeholder="john@doe.com" >
			</div>

			<button type="submit" class="hustle-unsub-button">
				<span class="hustle-loading-text"><?php echo esc_html( $button_text ); ?></span>
				<span class="hustle-loading-icon"></span>
			</button>
			<input type="hidden" name="form_step" value="<?php echo esc_attr( $step ); ?>">

			<input type="hidden" name="form_module_id" value="<?php echo esc_attr( $shortcode_attr_id ); ?>">
			<input type="hidden" name="current_url" value="<?php echo esc_attr( Opt_In_Utils::get_current_url() ); ?>">

			<?php if ( ! empty( $skip_confirmation ) ) { ?>
				<input type="hidden" name="skip_confirmation" value="true">
			<?php } ?>

		</div>

	</form>

<?php else : ?>

	<div class="hustle-email-lists">

	<?php foreach ( $modules_id as $module_id ) : ?>
		<?php
			$current_module = new Hustle_Module_Model( $module_id );
			$list_name      = __( 'Undefined', 'hustle' );
		if ( ! is_wp_error( $current_module ) ) {
			$local_list = $current_module->get_provider_settings( 'local_list' );
			if ( ! empty( $local_list['local_list_name'] ) ) {
				$list_name = $local_list['local_list_name'];
			} elseif ( ! empty( $current_module->module_name ) ) {
				$list_name = $current_module->module_name;
			}
		}
		?>
		<label for="hustle-list-<?php echo esc_attr( $module_id ); ?>">
			<input type="checkbox" name="lists_id[]" value="<?php echo esc_attr( $module_id ); ?>" id="hustle-list-<?php echo esc_attr( $module_id ); ?>">
			<span><?php echo esc_html( $list_name ); ?></span>
		</label>

	<?php endforeach; ?>

	</div>
	<input type="hidden" name="form_step" value="choose_list">
	<input type="hidden" name="email" value="<?php echo esc_attr( $email ); ?>">
	<input type="hidden" name="current_url" value="<?php echo esc_attr( $current_url ); ?>">
	<button type="submit" class="hustle-unsub-button">
		<span class="hustle-loading-text"><?php echo esc_html( $messages['submit_button_text'] ); ?></span>
		<span class="hustle-loading-icon"></span>
	</button>

<?php endif; ?>