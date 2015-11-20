<?php
/**
 * @package WPWD
 * @version 0.5.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPWD\Components;

use WPWD\App as App;
use WPDLib\Components\Base as Base;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'WPWD\Components\Widget' ) ) {
	/**
	 * Class for a widget component.
	 *
	 * A widget denotes just a normal WordPress widget.
	 * However, this component is not the actual widget class. The class `WPWD\Widget` handles all widgets created with the plugin.
	 * The slug of the widget will be used as the widget's `$id_base`.
	 *
	 * @internal
	 * @since 0.5.0
	 */
	class Widget extends Base {

		/**
		 * Class constructor.
		 *
		 * @since 0.5.0
		 * @param string $slug the widget slug
		 * @param array $args array of widget properties
		 */
		public function __construct( $slug, $args ) {
			parent::__construct( $slug, $args );
			$this->validate_filter = 'wpwd_widget_validated';
		}

		/**
		 * Registers the widget.
		 *
		 * @since 0.5.0
		 */
		public function register() {
			wpwd_register_widget( $this->slug, $this->args );
		}

		/**
		 * Sets the ID and name attributes for all fields of a widget.
		 *
		 * This function must be executed before any widget form is printed.
		 *
		 * @since 0.5.0
		 * @param WPWD\Widget $widget the widget class instance
		 */
		public function set_attributes( $widget ) {
			foreach ( $this->get_children() as $section ) {
				foreach ( $section->get_children() as $field ) {
					$field->_field->id = $widget->get_field_id( $field->slug );
					$field->_field->name = $widget->get_field_name( $field->slug );
				}
			}
		}

		/**
		 * Renders the widget form.
		 *
		 * It iterates through all the sections belonging to this widget and calls each one's `render()` function.
		 *
		 * If no sections are available for this widget, the function will try to call the widget callback function to generate the output.
		 *
		 * @since 0.5.0
		 * @param array $widget_options the widget's widget options with their current values
		 */
		public function render_form( $widget_options ) {
			//TODO: of course the following does not work; create manual errors here
			settings_errors( $this->slug );

			/**
			 * This action can be used to display additional content on top of this widget form.
			 *
			 * @since 0.5.0
			 * @param string the slug of the current widget
			 * @param array the arguments array for the current widget
			 */
			do_action( 'wpwd_widget_form_before', $this->slug, $this->args );

			if ( count( $children = $this->get_children() ) > 0 ) {
				foreach ( $children as $section ) {
					echo '<div class="wpwd-form-section">';
					$section->render( $widget_options, $this );
					echo '</div>';
				}
			} elseif ( $this->args['form_callback'] && is_callable( $this->args['form_callback'] ) ) {
				call_user_func( $this->args['form_callback'] );
			} else {
				App::doing_it_wrong( __METHOD__, sprintf( __( 'There are no sections to display for widget %s. Either add some or provide a valid callback function instead.', 'widgets-definitely' ), $this->slug ), '0.5.0' );
			}

			/**
			 * This action can be used to display additional content at the bottom of this widget form.
			 *
			 * @since 0.5.0
			 * @param string the slug of the current widget
			 * @param array the arguments array for the current widget
			 */
			do_action( 'wpwd_widget_form_after', $this->slug, $this->args );
		}

		/**
		 * Validates the widget options for this widget.
		 *
		 * It iterates through all the fields (i.e. widget options) of this widget and validates each one's value.
		 * If a field is not set for some reason, its default value is saved.
		 *
		 * Furthermore this function adds widget settings errors if any occur.
		 *
		 * @since 0.5.0
		 * @param array $widget_options the unvalidated widget options
		 * @param array $widget_options_old the currently stored widget options
		 * @return array the validated widget options
		 */
		public function validate_widget_options( $widget_options, $widget_options_old ) {
			$widget_options_validated = array();

			$errors = array();

			$changes = false;

			foreach ( $this->get_children() as $section ) {
				foreach ( $section->get_children() as $field ) {
					$widget_option_old = $field->default;
					if ( isset( $widget_options_old[ $field->slug ] ) ) {
						$widget_option_old = $widget_options_old[ $field->slug ];
					} else {
						$widget_options_old[ $field->slug ] = $widget_option_old;
					}

					$widget_option = null;
					if ( isset( $widget_options[ $field->slug ] ) ) {
						$widget_option = $widget_options[ $field->slug ];
					}

					list( $widget_option_validated, $error, $changed ) = $this->validate_widget_option( $field, $widget_option, $widget_option_old );

					$widget_options_validated[ $field->slug ] = $widget_option_validated;
					if ( $error ) {
						$errors[ $field->slug ] = $error;
					} elseif ( $changed ) {
						$changes = true;
					}
				}
			}

			if ( $changes ) {
				/**
				 * This action can be used to perform additional steps when the widget options of this widget were updated.
				 *
				 * @since 0.5.0
				 * @param array the updated widget option values as $field_slug => $value
				 * @param array the previous widget option values as $field_slug => $value
				 */
				do_action( 'wpwd_update_widget_options_' . $this->slug, $widget_options_validated, $widget_options_old );
			}

			/**
			 * This filter can be used by the developer to modify the validated widget options right before they are saved.
			 *
			 * @since 0.5.0
			 * @param array the associative array of widget options and their values
			 */
			$widget_options_validated = apply_filters( 'wpwd_validated_widget_options', $widget_options_validated );

			$this->add_settings_message( $errors );

			return $widget_options_validated;
		}

		/**
		 * Returns the field defaults for this widget.
		 *
		 * @since 0.5.0
		 * @param array $defaults the defaults array to be filtered
		 * @return array the field defaults
		 */
		public function get_field_defaults( $defaults = array() ) {
			foreach ( $this->get_children() as $section ) {
				foreach ( $section->get_children() as $field ) {
					$defaults[ $field->slug ] = $field->default;
				}
			}

			return $defaults;
		}

		/**
		 * Enqueues necessary stylesheets and scripts for this widget.
		 *
		 * @since 0.5.0
		 */
		public function enqueue_assets() {
			//TODO: remove this, it must happen in WidgetHandler
			$_fields = array();
			foreach ( $this->get_children() as $section ) {
				foreach ( $section->get_children() as $field ) {
					$_fields[] = $field->_field;
				}
			}

			FieldManager::enqueue_assets( $_fields );
		}

		/**
		 * Validates the arguments array.
		 *
		 * @since 0.5.0
		 * @param null $parent the parent component
		 * @return bool|WPDLib\Util\Error an error object if an error occurred during validation, true if it was validated, false if it did not need to be validated
		 */
		public function validate( $parent = null ) {
			$status = parent::validate( $parent );

			if ( $status === true ) {

			}

			return $status;
		}

		/**
		 * Returns the keys of the arguments array and their default values.
		 *
		 * Read the plugin guide for more information about the widget arguments.
		 *
		 * @since 0.5.0
		 * @return array
		 */
		protected function get_defaults() {
			$defaults = array(
				'title'				=> __( 'Widget title', 'widgets-definitely' ),
				'description'		=> '',
				'template_file'		=> false,
				'template_callback'	=> false,
				'enqueue_callback'	=> false,
				'form_callback'		=> false, //only used if no sections are attached to this widget
			);

			/**
			 * This filter can be used by the developer to modify the default values for each widget component.
			 *
			 * @since 0.5.0
			 * @param array the associative array of default values
			 */
			return apply_filters( 'wpwd_widget_defaults', $defaults );
		}

		/**
		 * Returns whether this component supports multiple parents.
		 *
		 * @since 0.5.0
		 * @return bool
		 */
		protected function supports_multiparents() {
			return false;
		}

		/**
		 * Returns whether this component supports global slugs.
		 *
		 * If it does not support global slugs, the function either returns false for the slug to be globally unique
		 * or the class name of a parent component to ensure the slug is unique within that parent's scope.
		 *
		 * @since 0.5.0
		 * @return bool|string
		 */
		protected function supports_globalslug() {
			return false;
		}

		/**
		 * Validates a widget option.
		 *
		 * @since 0.5.0
		 * @param WPWD\Components\Field $field field object to validate the option for
		 * @param mixed $widget_option the widget option value to validate
		 * @param mixed $widget_option_old the previous widget option value
		 * @return array an array containing the validated value, a variable possibly containing a WP_Error object and a boolean value whether the widget option value has changed
		 */
		protected function validate_widget_option( $field, $widget_option, $widget_option_old ) {
			$widget_option = $field->validate_widget_option( $widget_option );
			$error = false;
			$changed = false;

			if ( is_wp_error( $widget_option ) ) {
				$error = $widget_option;
				$widget_option = $widget_option_old;
			} elseif ( $widget_option != $widget_option_old ) {
				/**
				 * This action can be used to perform additional steps when the option for a specific field of this tab has been updated.
				 *
				 * @since 0.5.0
				 * @param mixed the updated option value
				 * @param mixed the previous option value
				 */
				do_action( 'wpwd_update_widget_option_' . $this->slug . '_' . $field->slug, $widget_option, $widget_option_old );
				$changed = true;
			}

			return array( $widget_option, $error, $changed );
		}

		/**
		 * Adds settings errors and/or updated messages for the current widget.
		 *
		 * @since 0.5.0
		 * @param array $errors an array (possibly) containing validation errors as $field_slug => $wp_error
		 */
		protected function add_settings_message( $errors ) {
			$status_text = __( 'Settings successfully saved.', 'widgets-definitely' );

			if ( count( $errors ) > 0 ) {
				$error_text = __( 'Some errors occurred while trying to save the following settings:', 'widgets-definitely' );
				foreach ( $errors as $field_slug => $error ) {
					$error_text .= '<br/><em>' . $field_slug . '</em>: ' . $error->get_error_message();
				}

				add_settings_error( $this->slug, $this->slug . '-error', $error_text, 'error' );

				$status_text = __( 'Other settings were successfully saved.', 'widgets-definitely' );
			}

			add_settings_error( $this->slug, $this->slug . '-updated', $status_text, 'updated' );
		}
	}
}
