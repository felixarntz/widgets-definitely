<?php
/**
 * @package WPWD
 * @version 0.5.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPWD;

use WPWD\App as App;
use WPWD\Utility as Utility;
use WP_Widget as WPWidget;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'WPWD\Widget' ) ) {
	/**
	 * This class is the general widget class.
	 *
	 * All widgets created by the plugin will be created as an instance of this class.
	 *
	 * @internal
	 * @since 0.5.0
	 */
	class Widget extends WPWidget {
		/**
		 * @since 0.5.0
		 * @var string Holds the widget's slug.
		 */
		public $slug = '';

		/**
		 * @since 0.5.0
		 * @var array Holds the widget's arguments.
		 */
		public $args = array();

		public function __construct( $slug, $args ) {
			$args = wp_parse_args( $args, array(
				'title'				=> __( 'Widget title', 'widgets-definitely' ),
				'description'		=> '',
				'template_file'		=> false,
				'template_callback'	=> false,
				'enqueue_callback'	=> false,
			) );

			$widget_options = array(
				'classname'		=> 'widget_' . str_replace( '-', '_', $slug ),
			);
			if ( ! empty( $args['description'] ) ) {
				$widget_options['description'] = $args['description'];
			}

			$this->slug = $slug;
			$this->args = $args;

			if ( $this->args['enqueue_callback'] ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_assets' ) );
			}

			parent::__construct( $this->slug, $this->args['title'], $widget_options );
		}

		public function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->get_defaults() );

			echo $args['before_widget'];

			if ( isset( $instance['title'] ) ) {
				$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

				if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}
			}

			$this->get_template_part( $args, $instance );

			echo $args['after_widget'];
		}

		public function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->get_defaults() );

			do_action( 'wpwd_widget_form_set_attributes_' . $this->slug, $this );

			do_action( 'wpwd_widget_form_' . $this->slug, $instance );
		}

		public function update( $new_instance, $old_instance ) {
			return apply_filters( 'wpwd_widget_validate_' . $this->slug, $new_instance, $old_instance );
		}

		public function maybe_enqueue_assets() {
			if ( is_active_widget( false, false, $this->id_base, true ) ) {
				if ( is_callable( $this->args['enqueue_callback'] ) ) {
					call_user_func( $this->args['enqueue_callback'] );
				} else {
					App::doing_it_wrong( __METHOD__, sprintf( __( 'The enqueue callback provided for the widget %s is invalid.', 'widgets-definitely' ), $this->slug ), '0.5.0' );
				}
			}
		}

		protected function get_defaults() {
			return apply_filters( 'wpwd_widget_field_defaults_' . $this->slug, array() );
		}

		protected function get_template_part( $args, $instance ) {
			$sidebar_slug = $args['id'];

			do_action( 'get_template_part_' . $this->slug, $this->slug, $sidebar_slug );

			$template_names = array(
				$this->slug . '-' . $sidebar_slug . '.php',
				$this->slug . '.php',
			);

			$template_paths = Utility::get_template_paths();

			$located = false;

			foreach ( $template_names as $template_name ) {
				foreach ( $template_paths as $template_path ) {
					if ( file_exists( $template_path . $template_name ) ) {
						$located = $template_path . $template_name;
						break;
					}
				}
				if ( $located ) {
					break;
				}
			}

			if ( $located ) {
				$this->load_template_file( $located, $instance );
			} elseif ( $this->args['template_file'] && file_exists( $this->args['template_file'] ) ) {
				$this->load_template_file( $this->args['template_file'], $instance );
			} elseif ( $this->args['template_callback'] && is_callable( $this->args['template_callback'] ) ) {
				$this->load_template_callback( $this->args['template_callback'], $instance );
			} else {
				App::doing_it_wrong( __METHOD__, sprintf( __( 'The widget %s is missing a valid template file or callback. You must provide either a template file or a callback function for it.', 'widgets-definitely' ), $this->slug ), '0.5.0' );
			}
		}

		protected function load_template_file( $file, $data ) {
			require $file;
		}

		protected function load_template_callback( $callback, $data ) {
			call_user_func( $callback, $data );
		}
	}

}
