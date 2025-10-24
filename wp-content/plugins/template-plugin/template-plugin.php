<?php

/**
 * Plugin Name:       Template Plugin Customizations
 * Description:       Customizations for the Template Site. 
 * Version:           1.0
 * Author:            twentybellows 
 * Text Domain:       template-plugin
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 *
 * Enqueue theme styles and scripts
 *
 */
add_action('wp_enqueue_scripts', function () {

	// Enqueue theme JavaScript
	$script_asset = require(get_template_directory() . '/build/plugin.asset.php');

	wp_enqueue_script(
		'template-plugin-script',
		get_template_directory_uri() . '/build/plugin.js',
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	wp_enqueue_style(
		'template-plugin-style',
		get_template_directory_uri() . '/build/plugin.css',
		array(),
		filemtime(get_template_directory() . '/build/plugin.css')
	);

});