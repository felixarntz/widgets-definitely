<?php
/*
Plugin Name: Widgets Definitely
Plugin URI: https://wordpress.org/plugins/widgets-definitely/
Description: This framework plugin makes adding options screens with sections and fields to WordPress very simple, yet flexible.
Version: 0.5.0
Author: Felix Arntz
Author URI: http://leaves-and-love.net
License: GNU General Public License v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: widgets-definitely
Tags: wordpress, plugin, definitely, framework, library, developer, admin, backend, structured data, ui, api, cms, options, settings, settings screen, tabs, sections, metaboxes, fields, help tabs
*/
/**
 * @package WPWD
 * @version 0.5.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'WPWD\App' ) ) {
	if ( file_exists( dirname( __FILE__ ) . '/widgets-definitely/vendor/autoload.php' ) ) {
		if ( version_compare( phpversion(), '5.3.0' ) >= 0 ) {
			require_once dirname( __FILE__ ) . '/widgets-definitely/vendor/autoload.php';
		} else {
			require_once dirname( __FILE__ ) . '/widgets-definitely/vendor/felixarntz/leavesandlove-wp-plugin-util/leavesandlove-wp-plugin-loader.php';
		}
	} elseif ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
		if ( version_compare( phpversion(), '5.3.0' ) >= 0 ) {
			require_once dirname( __FILE__ ) . '/vendor/autoload.php';
		} else {
			require_once dirname( __FILE__ ) . '/vendor/felixarntz/leavesandlove-wp-plugin-util/leavesandlove-wp-plugin-loader.php';
		}
	}
}

LaL_WP_Plugin_Loader::load_plugin( array(
	'slug'					=> 'widgets-definitely',
	'name'					=> 'Widgets Definitely',
	'version'				=> '0.5.0',
	'main_file'				=> __FILE__,
	'namespace'				=> 'WPWD',
	'textdomain'			=> 'widgets-definitely',
	'use_language_packs'	=> true,
), array(
	'phpversion'			=> '5.3.0',
	'wpversion'				=> '4.0',
) );
