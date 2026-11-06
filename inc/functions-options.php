<?php
/**
 * Functions for handling plugin options.
 *
 * @package   Series
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2009 - 2026, Justin Tadlock
 * @link      https://github.com/justintadlock/series
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Series;

/**
 * Returns the number of posts per page for series archives.
 *
 * @since  2.0.0
 * @access public
 * @return int
 */
function get_posts_per_page(): int
{
	return apply_filters( 'series/get_posts_per_page', get_setting( 'posts_per_page' ) );
}

/**
 * Returns the posts orderby on series archives.
 *
 * @since  2.0.0
 * @access public
 * @return string
 */
function get_posts_orderby(): string
{
	return apply_filters( 'series/get_posts_orderby', get_setting( 'posts_orderby' ) );
}

/**
 * Returns the posts order on series archives.
 *
 * @since  2.0.0
 * @access public
 * @return string
 */
function get_posts_order(): string
{
	return apply_filters( 'series/get_posts_order', get_setting( 'posts_order' ) );
}

/**
 * Returns the series rewrite base. Used for series archives.
 * @return string|false
 * @since  2.0.0
 * @access public
 */
function get_series_rewrite_base(): string|false
{
	return apply_filters( 'series/get_series_rewrite_base', get_setting( 'series_rewrite_base' ) );
}

/**
 * Gets a setting from the plugin settings in the database.
 * @param string $option
 * @return mixed
 * @since  2.0.0
 * @access public
 */
function get_setting( string $option = '' ): mixed
{
	$defaults = get_default_settings();
	$settings = wp_parse_args( get_option( 'series_settings', $defaults ), $defaults );

	return $settings[$option] ?? false;
}

/**
 * Returns an array of the default plugin settings.
 *
 * @since  2.0.0
 * @access public
 * @return array
 */
function get_default_settings(): array
{
	return array(
		// @since 2.0.0
		'series_rewrite_base' => 'series',
		'posts_per_page'      => 10,
		'posts_orderby'       => 'date',
		'posts_order'         => 'DESC'
	);
}
