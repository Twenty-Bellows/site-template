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

	$script_asset = require __DIR__ . '/build/plugin.asset.php';

	wp_enqueue_script(
		'template-plugin-script',
		plugin_dir_url(__FILE__) . 'build/plugin.js',
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	wp_enqueue_style(
		'template-plugin-style',
		plugin_dir_url(__FILE__) . 'build/plugin.css',
		array(),
		$script_asset['version'],
	);

});

/**
 *
 * Register Custom Blocks
 *
 */
add_action('init', function () {
	if ( ! file_exists( __DIR__ . '/build/blocks-manifest.php' ) ) {
		return;
	}
	$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
    	foreach ( array_keys( $manifest_data ) as $block_type ) {
        	register_block_type( __DIR__ . "/build/blocks/{$block_type}" );

		// Include assets for custom blocks that cannot be represented in block.json
		// check to see if the file get_template_directory() . "/build/blocks/{$block}/hooks.php" exists, if so, require it.
		if ( file_exists( __DIR__ . "/build/blocks/{$block_type}/hooks.php" ) ) {
			require_once __DIR__ . "/build/blocks/{$block_type}/hooks.php";
		}
    	}
});