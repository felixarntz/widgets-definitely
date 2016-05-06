<?php
/**
 * @package WPWD
 * @version 0.5.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! function_exists( 'wpwd_register_widget' ) ) {
	function wpwd_register_widget( $slug, $args ) {
		global $wp_widget_factory;

		$wp_widget_factory->widgets[ $slug ] = new WPWD\Widget( $slug, $args );
	}
}

if ( ! function_exists( 'wpwd_unregister_widget' ) ) {
	function wpwd_unregister_widget( $slug ) {
		global $wp_widget_factory;

		$wp_widget_factory->unregister( $slug );
	}
}
