<?php
/**
 * WPWD\Components\Section class
 *
 * @package WPWD
 * @subpackage Components
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 0.5.0
 */

namespace WPWD\Components;

use WPWD\App as App;
use WPWD\Utility as Utility;
use WPDLib\Components\Base as Base;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'WPWD\Components\Section' ) ) {
	/**
	 * Class for a section component.
	 *
	 * A section denotes a section inside a widget form in the WordPress admin.
	 * It has no further meaning other than to visually group certain fields.
	 *
	 * @internal
	 * @since 0.5.0
	 */
	class Section extends Base {
		/**
		 * Class constructor.
		 *
		 * @since 0.5.0
		 * @param string $slug the section slug
		 * @param array $args array of section properties
		 */
		public function __construct( $slug, $args ) {
			parent::__construct( $slug, $args );
			$this->validate_filter = 'wpwd_section_validated';
		}

		/**
		 * Renders the section.
		 *
		 * It displays the title and description (if available) for the section.
		 * Then it shows the fields of this section or, if no fields are available, calls the callback function.
		 *
		 * @since 0.5.0
		 * @param array $widget_options the widget options with their current values
		 * @param WPWD\Components\Widget|null $parent_widget the parent widget component of this section or null
		 */
		public function render( $widget_options, $parent_widget = null ) {
			if ( null === $parent_widget ) {
				$parent_widget = $this->get_parent();
			}

			echo '<h3>' . $this->args['title'] . '</h3>';

			/**
			 * This action can be used to display additional content on top of this section.
			 *
			 * @since 0.5.0
			 * @param string the slug of the current section
			 * @param array the arguments array for the current section
			 * @param string the slug of the current widget
			 */
			do_action( 'wpwd_section_before', $this->slug, $this->args, $parent_widget->slug );

			if ( ! empty( $this->args['description'] ) ) {
				echo '<p class="description">' . $this->args['description'] . '</p>';
			}

			if ( count( $children = $this->get_children() ) > 0 ) {
				foreach ( $children as $field ) {
					$field->render( $widget_options[ $field->slug ], $parent_widget, $this );
				}
			} elseif ( $this->args['callback'] && is_callable( $this->args['callback'] ) ) {
				call_user_func( $this->args['callback'] );
			} else {
				App::doing_it_wrong( __METHOD__, sprintf( __( 'There are no fields to display for section %s. Either add some or provide a valid callback function instead.', 'widgets-definitely' ), $this->slug ), '0.5.0' );
			}

			/**
			 * This action can be used to display additional content at the bottom of this section.
			 *
			 * @since 0.5.0
			 * @param string the slug of the current section
			 * @param array the arguments array for the current section
			 * @param string the slug of the current widget
			 */
			do_action( 'wpwd_section_after', $this->slug, $this->args, $parent_widget->slug );
		}

		/**
		 * Validates the arguments array.
		 *
		 * @since 0.5.0
		 * @param WPWD\Components\Widget $parent the parent component
		 * @return bool|WPDLib\Util\Error an error object if an error occurred during validation, true if it was validated, false if it did not need to be validated
		 */
		public function validate( $parent = null ) {
			$status = parent::validate( $parent );

			if ( $status === true ) {
				$this->args = Utility::validate_position_args( $this->args );
			}

			return $status;
		}

		/**
		 * Returns the keys of the arguments array and their default values.
		 *
		 * Read the plugin guide for more information about the section arguments.
		 *
		 * @since 0.5.0
		 * @return array
		 */
		protected function get_defaults() {
			$defaults = array(
				'title'			=> __( 'Section title', 'widgets-definitely' ),
				'description'	=> '',
				'callback'		=> false, //only used if no fields are attached to this section
				'position'		=> null,
			);

			/**
			 * This filter can be used by the developer to modify the default values for each section component.
			 *
			 * @since 0.5.0
			 * @param array the associative array of default values
			 */
			return apply_filters( 'wpwd_section_defaults', $defaults );
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
			return 'WPWD\Components\Widget';
		}
	}
}
