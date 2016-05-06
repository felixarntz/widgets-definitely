<?php
/**
 * @package WPWD
 * @version 0.5.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPWD;

use WPDLib\Components\Manager as ComponentManager;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'WPWD\WidgetHandler' ) ) {
	/**
	 * This class performs the necessary actions in the WordPress admin.
	 *
	 * This includes both registering widgets and managing their settings.
	 *
	 * @internal
	 * @since 0.5.0
	 */
	class WidgetHandler {

		/**
		 * @since 0.5.0
		 * @var WPWD\WidgetHandler|null Holds the instance of this class.
		 */
		private static $instance = null;

		/**
		 * Gets the instance of this class. If it does not exist, it will be created.
		 *
		 * @since 0.5.0
		 * @return WPWD\WidgetHandler
		 */
		public static function instance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Class constructor.
		 *
		 * @since 0.5.0
		 */
		private function __construct() {
			add_action( 'after_setup_theme', array( $this, 'add_hooks' ) );
		}

		/**
		 * Hooks in all the necessary actions and filters.
		 *
		 * This function should be executed after the plugin has been initialized.
		 *
		 * @since 0.5.0
		 */
		public function add_hooks() {
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );

			$widgets = ComponentManager::get( '*', 'WPWD\Components\Widget' );
			foreach ( $widgets as $widget ) {
				add_action( 'wpwd_widget_form_set_attributes_' . $widget->slug, array( $widget, 'set_attributes' ), 10, 1 );
				add_action( 'wpwd_widget_form_' . $widget->slug, array( $widget, 'render_form' ), 10, 1 );

				add_filter( 'wpwd_widget_validate_' . $widget->slug, array( $widget, 'validate_field_options' ), 10, 2 );
				add_filter( 'wpwd_widget_field_defaults_' . $widget->slug, array( $widget, 'get_field_defaults' ), 10, 1 );
			}

			//TODO: enqueue widget form assets
		}

		public function register_widgets() {
			$widgets = ComponentManager::get( '*', 'WPWD\Components\Widget' );
			foreach ( $widgets as $widget ) {
				$widget->register();
			}
		}
	}
}
