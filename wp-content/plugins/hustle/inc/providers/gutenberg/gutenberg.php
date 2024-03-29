<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Hustle_Gutenberg class
 *
 * @package Hustle
 */

// Load Gutenberg module after Hustle is loaded.
add_action( 'init', array( 'Hustle_Gutenberg', 'init' ), 5 );
add_filter( 'block_categories_all', array( 'Hustle_Gutenberg', 'register_hustle_category' ), 10, 2 );

/**
 * Class Hustle_Gutenberg
 */
class Hustle_Gutenberg {

	/**
	 * Initialize addon
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public static function init() {
		add_action( 'current_screen', array( 'Hustle_Gutenberg', 'gutenberg_init_admin' ) );
		add_action( 'wp', array( 'Hustle_Gutenberg', 'gutenberg_init_frontend' ) );
	}

	/**
	 * Automatically include blocks files
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public static function load_blocks() {
		// Load blocks automatically.
		foreach ( glob( plugin_dir_path( __FILE__ ) . '/blocks/block-*.php' ) as $file ) {
			require_once $file;
		}
	}

	/**
	 * Automatically include blocks files in admin.
	 *
	 * @since 4.2.0
	 */
	public static function gutenberg_init_admin() {
		$current_screen = get_current_screen();

		if ( isset( $current_screen->is_block_editor ) && $current_screen->is_block_editor ) {
			// Load abstracts.
			require_once dirname( __FILE__ ) . '/abstract-block.php';

			// Load blocks.
			self::load_blocks();
		}
	}

	/**
	 * Automatically include blocks files in frontend.
	 *
	 * @since 4.2.0
	 */
	public static function gutenberg_init_frontend() {
		if ( function_exists( 'has_blocks' ) && has_blocks() ) {
			// Load abstracts.
			require_once dirname( __FILE__ ) . '/abstract-block.php';

			// Load blocks.
			self::load_blocks();
		}
	}

	/**
	 * Return Addon URL
	 *
	 * @since 1.0 Gutenberg Addon
	 *
	 * @return mixed
	 */
	public static function get_plugin_url() {
		return Opt_In::$plugin_url . 'inc/providers/gutenberg';
	}

	/**
	 * Return Addon DIR
	 *
	 * @since 1.0 Gutenberg Addon
	 *
	 * @return mixed
	 */
	public static function get_plugin_dir() {
		return trailingslashit( dirname( __FILE__ ) );
	}

	/**
	 * Register Hustle's gutenberg category.
	 *
	 * @since 1.0 Gutenberg Addon
	 * @param array  $categories Categories.
	 * @param string $block_editor_context Editor context.
	 * @return array
	 */
	public static function register_hustle_category( $categories, $block_editor_context ) {

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'hustle',
					/* translators: Plugin name */
					'title' => esc_html( sprintf( __( '%s Blocks', 'hustle' ), Opt_In_Utils::get_plugin_name() ) ),
					'icon'  => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M56.25 0c7.813 0 15.112 1.465 21.9 4.395 6.787 2.93 12.744 6.958 17.87 12.084 5.127 5.126 9.156 11.083 12.085 17.87 2.93 6.788 4.395 14.087 4.395 21.9 0 7.813-1.465 15.112-4.395 21.9-2.93 6.787-6.958 12.744-12.084 17.87-5.126 5.127-11.083 9.156-17.87 12.085-6.788 2.93-14.087 4.395-21.9 4.395-7.813 0-15.112-1.465-21.9-4.395-6.787-2.93-12.744-6.958-17.87-12.084-5.127-5.126-9.156-11.083-12.085-17.87C1.465 71.36 0 64.062 0 56.25c0-7.813 1.465-15.112 4.395-21.9 2.93-6.787 6.958-12.744 12.084-17.87 5.126-5.127 11.083-9.156 17.87-12.085C41.14 1.465 48.438 0 56.25 0zm38.525 59.473v.146c.098-.587.147-1.197.147-1.832v-1.83c0-.782-.025-1.563-.073-2.345-.05-.78-.123-1.562-.22-2.343-.392-3.614-1.368-7.056-2.93-10.328-1.563-3.27-3.516-6.176-5.86-8.715-2.54-2.832-5.444-5.298-8.716-7.398-3.27-2.1-6.81-3.834-10.62-5.2l-2.05 10.546c1.073.39 3.173 1.465 6.298 3.222 3.125 1.758 5.86 4.102 8.203 7.032 1.66 2.246 2.98 4.712 3.955 7.397.977 2.686 1.465 5.493 1.465 8.423v1.245c0 .44-.05.806-.146 1.1-.197 2.44-1.002 5.223-2.418 8.348-1.416 3.125-3.882 4.688-7.398 4.688h-.293c-.292 0-.61-.072-.95-.22-.343-.145-.66-.316-.953-.512-.196-.293-.342-.61-.44-.952-.097-.342-.146-.708-.146-1.098v-.586l1.612-8.643c.196-1.075.366-2.174.513-3.297.146-1.123.22-2.27.22-3.442 0-3.222-.88-5.76-2.637-7.616-1.758-1.856-4.2-2.784-7.324-2.784-1.465 0-2.906.22-4.322.66-1.416.44-2.71 1.05-3.88 1.83l-1.32.88.293-1.612 5.567-28.125H47.754l-.733 3.955-.73.147c-1.563.293-3.077.732-4.542 1.318-1.465.586-2.88 1.27-4.248 2.05-5.957 3.32-10.79 7.984-14.502 13.99-3.71 6.006-5.566 12.67-5.566 19.995 0 5.37 1.025 10.4 3.076 15.088 2.05 4.688 4.834 8.79 8.35 12.305 3.515 3.517 7.64 6.3 12.377 8.35 4.737 2.05 9.79 3.077 15.16 3.077 2.052 0 4.054-.147 6.007-.44 1.953-.293 3.76-.732 5.42-1.318l1.172-.294-3.955-9.815-.733.148c-1.27.39-2.564.684-3.882.88-1.32.194-2.612.292-3.882.292h-.147c-7.812 0-14.477-2.76-19.995-8.277-5.516-5.517-8.275-12.182-8.275-19.995 0-5.175 1.27-9.936 3.81-14.282 2.538-4.346 5.907-7.74 10.106-10.18l1.026-.586 1.32-.733-7.032 28.125c-.586 2.832-.757 5.225-.513 7.178.245 1.953.904 3.564 1.978 4.834.88 1.172 2.002 2.075 3.37 2.71 1.367.635 2.832.952 4.394.952h1.025l2.49-12.158c.587-3.223 1.758-5.786 3.516-7.69 1.758-1.905 3.418-2.857 4.98-2.857.587 0 1.173.147 1.76.44.585.293.877 1.075.877 2.344 0 .78-.048 1.538-.146 2.27-.098.733-.244 1.392-.44 1.978l-1.757 10.254v.147c-.098 2.343.61 4.443 2.124 6.298 1.514 1.857 3.2 3.175 5.054 3.956.88.39 1.832.684 2.857.88 1.026.195 2.076.292 3.15.292h.147c2.637 0 5.15-.44 7.544-1.318 2.393-.88 4.566-2.05 6.52-3.516 3.222-2.44 5.395-5.42 6.518-8.935 1.123-3.517 1.782-6.35 1.977-8.497z" /></svg>',
				),
			)
		);
	}

}