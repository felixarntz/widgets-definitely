=== Widgets Definitely ===

Plugin Name:       Widgets Definitely
Plugin URI:        https://github.com/felixarntz/widgets-definitely
Author:            Felix Arntz
Author URI:        https://leaves-and-love.net
Contributors:      flixos90
Requires at least: 4.0
Tested up to:      4.5.2
Stable tag:        0.5.0
Version:           0.5.0
License:           GNU General Public License v3
License URI:       http://www.gnu.org/licenses/gpl-3.0.html
Tags:              definitely, framework, admin, widgets, sections, fields

This framework plugin makes adding widgets with automated sections and fields to WordPress very simple, yet flexible.

== Description ==

_Widgets Definitely_ is a framework for developers that allows them to easily add widgets with automated forms consisting of sections and fields to the WordPress admin so that a user can manage them. Furthermore all widgets created by using the plugin use the WordPress template hierarchy, so their generated output for the frontend can be overwritten by any theme or child theme.

The plugin belongs to the group of _Definitely_ plugins which aim at making adding backend components in WordPress easier and more standardized for developers. All _Definitely_ plugins bundle a custom library that handles functionality which is shared across all these plugins, for example handling the field types and their controls.

The library comes with several common field types and validation functions included, including repeatable fields, where you can group a few fields together and allow the user to add more and more of them. All the fields have a validation mechanism, so you can specify what the user is allowed to enter and print out custom error messages.

For an extensive list of features, please visit the [Features page in the _Widgets Definitely_ Wiki](https://github.com/felixarntz/widgets-definitely/wiki/Features).

> <strong>This plugin is a framework.</strong><br>
> When you activate the plugin, it will not change anything visible in your WordPress site. The plugin is a framework to make things easier for developers.<br>
> In order to benefit by this framework, you or your developer should use its functionality to do what the framework is supposed to help with.

= Usage =

_Widgets Definitely_ is very easy to use. Although you need to be able to write some PHP code to use the library, setting up settings pages with tabs, sections and fields should be quite straightforward. All you need to know is:

* how to hook into a WordPress action
* how to call a single class function
* how to handle an array

For a detailed guide and reference on how to use this framework, please read the [Wiki on Github](https://github.com/felixarntz/widgets-definitely/wiki). Once you get familiar with the options you have, you will be able to create complex options interfaces in just a few minutes.

**Note:** This plugin requires PHP 5.3.0 at least.

> _Widgets Definitely_ is just one among a group of _Definitely_ plugins which allow developers to build their admin interfaces more quickly. You might also wanna check out:<br>
> - [Post Types Definitely](https://wordpress.org/plugins/post-types-definitely/)<br>
> - [Options Definitely](https://wordpress.org/plugins/options-definitely/)

== Installation ==

1. Upload the entire `widgets-definitely` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add all the options you like, for example in your plugin or theme.

== Frequently Asked Questions ==

= How do I use the plugin? =

You can use the framework anywhere you like, for example in your theme's functions.php or somewhere in your own plugin or must-use plugin. For a detailed guide and reference on how to use this framework, please read the [Wiki on Github](https://github.com/felixarntz/widgets-definitely/wiki).

= Why don't I see any change after having activated the plugin? =

Widgets Definitely is a framework plugin which means it does nothing on its own, it just helps other developers getting things done way more quickly.

= Where should I submit my support request? =

I preferably take support requests as [issues on Github](https://github.com/felixarntz/widgets-definitely/issues), so I would appreciate if you created an issue for your request there. However, if you don't have an account there and do not want to sign up, you can of course use the [wordpress.org support forums](https://wordpress.org/support/plugin/widgets-definitely) as well.

= How can I contribute to the plugin? =

If you're a developer and you have some ideas to improve the plugin or to solve a bug, feel free to raise an issue or submit a pull request in the [Github repository for the plugin](https://github.com/felixarntz/widgets-definitely).

You can also contribute to the plugin by translating it. Simply visit [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/widgets-definitely) to get started. Note that you can help not only translating the plugin, but also the underlying library [_WPDLib_](https://github.com/felixarntz/wpdlib).

== Screenshots ==

1. a widget form created with the plugin
2. PHP code to create the widget form above

== Changelog ==

= 0.5.0 =
* First stable version
