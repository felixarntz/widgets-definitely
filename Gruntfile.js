'use strict';
module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		pluginheader:	'/*\n' +
						'Plugin Name: Widgets Definitely\n' +
						'Plugin URI: <%= pkg.homepage %>\n' +
						'Description: <%= pkg.description %>\n' +
						'Version: <%= pkg.version %>\n' +
						'Author: <%= pkg.author.name %>\n' +
						'Author URI: <%= pkg.author.url %>\n' +
						'License: <%= pkg.license.name %>\n' +
						'License URI: <%= pkg.license.url %>\n' +
						'Text Domain: widgets-definitely\n' +
						'Tags: <%= pkg.keywords.join(", ") %>\n' +
						'*/',
		fileheader:		'/**\n' +
						' * @package WPWD\n' +
						' * @version <%= pkg.version %>\n' +
						' * @author <%= pkg.author.name %> <<%= pkg.author.email %>>\n' +
						' */',

		replace: {
			header: {
				src: [
					'widgets-definitely.php'
				],
				overwrite: true,
				replacements: [{
					from: /((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/,
					to: '<%= pluginheader %>'
				}]
			},
			version: {
				src: [
					'widgets-definitely.php',
					'inc/**/*.php'
				],
				overwrite: true,
				replacements: [{
					from: /\/\*\*\s+\*\s@package\s[^*]+\s+\*\s@version\s[^*]+\s+\*\s@author\s[^*]+\s\*\//,
					to: '<%= fileheader %>'
				}]
			}
		}

 	});

	grunt.loadNpmTasks('grunt-text-replace');

	grunt.registerTask('plugin', [
		'replace:version',
		'replace:header'
	]);

	grunt.registerTask('build', [
		'plugin'
	]);
};
