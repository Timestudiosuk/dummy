<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Hustle_Module_Widget
 *
 * @package Hustle
 */

/**
 * Class Hustle_Module_Widget
 */
class Hustle_Module_Widget extends WP_Widget {

	/**
	 * Widget Id
	 *
	 * @var string
	 */
	const WIDGET_ID = 'hustle_module_widget';


	/**
	 * Registers the widget
	 */
	public function __construct() {
		parent::__construct(
			self::WIDGET_ID,
			Opt_In_Utils::get_plugin_name(),
			/* translators: Plugin name */
			array( 'description' => sprintf( __( 'A widget to add %s Embeds and Social Sharing.', 'hustle' ), Opt_In_Utils::get_plugin_name() ) )
		);
	}



	/**
	 *
	 * Front-end display of widget.
	 *
	 * @param array $args Args.
	 * @param array $instance Previously saved values from database.
	 * @return string
	 */
	public function widget( $args, $instance ) {
		if ( ! empty( $instance['module_id'] ) ) {
			$module = Hustle_Module_Collection::instance()->return_model_from_id( $instance['module_id'] );

			if ( is_wp_error( $module ) || ! $module || empty( $module->active ) || ! $module->is_display_type_active( Hustle_Module_Model::WIDGET_MODULE ) ) {
				return;
			}
		}

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
		}

		if ( ! empty( $instance['module_id'] ) ) {
			$custom_classes = apply_filters( 'hustle_widget_module_custom_classes', '', $module );
			echo $module->display( Hustle_Module_Model::WIDGET_MODULE, $custom_classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			esc_attr_e( 'Select Module', 'hustle' );
		}

		echo wp_kses_post( $args['after_widget'] );
	}


	/**
	 *
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'hustle' );
		if ( empty( $instance['module_id'] ) ) {
			$instance['module_id'] = -1; }
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_attr__( 'Title:', 'hustle' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'module_id' ) ); ?>"><?php echo esc_attr__( 'Select Module:', 'hustle' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'module_id' ) ); ?>" id="hustle_module_id">
				<option value=""><?php echo esc_attr__( 'Select Module', 'hustle' ); ?></option>
				<?php
				$types = array( 'embedded', 'social_sharing' );
				foreach ( Hustle_Module_Collection::instance()->get_embed_id_names( $types ) as $mod ) :
					$module = new Hustle_Module_Model( $mod->module_id );
					if ( is_wp_error( $module ) ) {
						continue;
					}
					?>
					<option <?php selected( $instance['module_id'], $mod->module_id ); ?> value="<?php echo esc_attr( $mod->module_id ); ?>"><?php echo esc_attr( $mod->module_name ); ?></option>

				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}


	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = array();
		$instance['title']     = ! empty( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['module_id'] = ! empty( $new_instance['module_id'] ) ? wp_strip_all_tags( $new_instance['module_id'] ) : '';

		return $instance;
	}
}