<?php
/**
 * Plugin settings screen.
 * @package   Series
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2009 - 2026, Justin Tadlock
 * @link      https://github.com/justintadlock/series
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Series;

/**
 * Sets up and handles the plugin settings screen.
 * @since  2.0.0
 * @access public
 */
final class Settings_Page {

	/**
	 * Settings page name.
	 * @since  2.0.0
	 * @access public
	 */
	public ?string $settings_page = null;

	/**
	 * Sets up the needed actions for adding and saving the meta boxes.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	private function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
	}

	/**
	 * Sets up custom admin menus.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function admin_menu(): void {

		// Create the settings page.
		$this->settings_page = add_options_page(
			esc_html__( 'Series Settings', 'series' ),
			esc_html__( 'Series', 'series' ),
			apply_filters( 'series/settings_capability', 'manage_options' ),
			'series-settings',
			[ $this, 'settings_page' ]
		);

		if ( $this->settings_page ) {
			// Register settings.
			add_action( 'admin_init', [ $this, 'register_settings' ] );
		}
	}

	/**
	 * Registers the plugin settings.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function register_settings(): void {

		// Register the setting.
		register_setting( 'series_settings', 'series_settings', [ $this, 'validate_settings' ] );

		/* === Settings Sections === */

		add_settings_section( 'reading',     esc_html__( 'Reading',    'series' ), [ $this, 'section_reading'    ], $this->settings_page );
		add_settings_section( 'permalinks',  esc_html__( 'Permalinks', 'series' ), [ $this, 'section_permalinks' ], $this->settings_page );

		/* === Settings Fields === */

		// Reading section fields.
		add_settings_field( 'posts_per_page', esc_html__( 'Posts Per Page',  'series' ), [ $this, 'field_posts_per_page' ], $this->settings_page, 'reading' );
		add_settings_field( 'posts_orderby',  esc_html__( 'Sort By',         'series' ), [ $this, 'field_posts_orderby'  ], $this->settings_page, 'reading' );
		add_settings_field( 'posts_order',    esc_html__( 'Order',           'series' ), [ $this, 'field_posts_order'    ], $this->settings_page, 'reading' );

		// Permalinks section fields.
		add_settings_field( 'series_rewrite_base',  esc_html__( 'Series Slug', 'series' ), [ $this, 'field_series_rewrite_base' ], $this->settings_page, 'permalinks' );
	}

	/**
	 * Validates the plugin settings.
	 * @param  array  $settings
	 * @return array
	 * @since  2.0.0
	 * @access public
	 */
	public function validate_settings( array $settings ): array {

		// Text boxes.
		$settings['series_rewrite_base'] = $settings['series_rewrite_base'] ? trim( strip_tags( $settings['series_rewrite_base'] ), '/' ) : '';

		// Numbers.
		$posts_per_page = intval( $settings['posts_per_page'] );
		$settings['posts_per_page'] = -2 < $posts_per_page ? $posts_per_page : 10;

		// Select boxes.
		$settings['posts_orderby'] = isset( $settings['posts_orderby'] ) ? strip_tags( $settings['posts_orderby'] ) : 'date';
		$settings['posts_order']   = isset( $settings['posts_order'] )   ? strip_tags( $settings['posts_order']   ) : 'DESC';

		/* === Handle Permalink Conflicts ===*/

		// Return the validated/sanitized settings.
		return $settings;
	}

	/**
	 * Reading section callback.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function section_reading(): void { ?>

		<p class="description">
			<?php esc_html_e( 'Reading settings for the front end of your site.', 'series' ); ?>
		</p>
	<?php }

	/**
	 * Themes per page field callback.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function field_posts_per_page(): void { ?>

		<label>
			<input type="number" class="small-text" min="-1" name="series_settings[posts_per_page]" value="<?php echo esc_attr( get_posts_per_page() ); ?>" />
		</label>
	<?php }

	/**
	 * Themes orderby field callback.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function field_posts_orderby(): void {

		$orderby = [
			'author'   => __( 'Author',           'series' ),
			'date'     => __( 'Date (Published)', 'series' ),
			'modified' => __( 'Date (Modified)',  'series' ),
			'ID'       => __( 'ID',               'series' ),
			'rand'     => __( 'Random',           'series' ),
			'name'     => __( 'Slug',             'series' ),
			'title'    => __( 'Title',            'series' ),
		]; ?>

		<label>
			<select name="series_settings[posts_orderby]">

			<?php foreach ( $orderby as $option => $label ) : ?>
				<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $option, get_posts_orderby() ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>

			</select>
		<label>
	<?php }

	/**
	 * Themes order field callback.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function field_posts_order(): void {

		$order = [
			'ASC'  => __( 'Ascending',  'series' ),
			'DESC' => __( 'Descending', 'series' ),
		]; ?>

		<label>
			<select name="series_settings[posts_order]">

			<?php foreach ( $order as $option => $label ) : ?>
				<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $option, get_posts_order() ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>

			</select>
		<label>
	<?php }

	/**
	 * Permalinks section callback.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function section_permalinks(): void { ?>

		<p class="description">
			<?php esc_html_e( 'Set up custom permalinks for the series on your site.', 'series' ); ?>
		</p>
	<?php }

	/**
	 * Series ewrite base field callback.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function field_series_rewrite_base(): void { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="series_settings[series_rewrite_base]" value="<?php echo esc_attr( get_series_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Renders the settings page.
	 * @return void
	 * @since  2.0.0
	 * @access public
	 */
	public function settings_page(): void {

		// Flush the rewrite rules if the settings were updated.
		if ( isset( $_GET['settings-updated'] ) )
			{flush_rewrite_rules();} ?>

		<div class="wrap">
			<h1><?php esc_html_e( 'Series Settings', 'series' ); ?></h1>

			<form method="post" action="options.php">
				<?php settings_fields( 'series_settings' ); ?>
				<?php do_settings_sections( $this->settings_page ); ?>
				<?php submit_button( esc_attr__( 'Update Settings', 'series' ) ); ?>
			</form>

		</div><!-- wrap -->
	<?php }

	/**
	 * Returns the instance.
     * @return Settings_Page
	 * @since  2.0.0
	 * @access public
	 **/
	public static function get_instance(): self {

		static $instance = null;

		if ( is_null( $instance ) ) {
            $instance = new self;
        }

		return $instance;
	}
}

Settings_Page::get_instance();
