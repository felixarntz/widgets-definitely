<?php
/**
 * @package WPWD
 * @version 0.5.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPWD;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'WPWD\Utility' ) ) {
	/**
	 * This class contains some utility functions.
	 *
	 * @internal
	 * @since 0.5.0
	 */
	class Utility {

		/**
		 * Returns an array of template paths to look for widget templates, sorted by priority.
		 *
		 * All paths contain a trailing slash.
		 *
		 * @since 0.5.0
		 * @return array the array of template paths
		 */
		public static function get_template_paths() {
			/**
			 * This filter can be used to insert additional template paths.
			 *
			 * You must always append the paths to the array, never prepend them.
			 * Furthermore, you must not remove the existing paths from the array.
			 *
			 * @since 0.5.0
			 * @param array the array of template paths
			 */
			$template_paths = apply_filters( 'wpwd_template_paths', array(
				trailingslashit( get_stylesheet_directory() ) . 'widget_templates',
				trailingslashit( get_template_directory() ) . 'widget_templates',
			) );

			return array_map( 'trailingslashit', $template_paths );
		}

		/**
		 * Validates the position argument.
		 *
		 * @see WPWD\Components\Section
		 * @see WPWD\Components\Field
		 * @since 0.5.0
		 * @param array $args array of arguments
		 * @return array the validated arguments
		 */
		public static function validate_position_args( $args ) {
			if ( null !== $args['position'] ) {
				$args['position'] = floatval( $args['position'] );
			}

			return $args;
		}

	}
}
