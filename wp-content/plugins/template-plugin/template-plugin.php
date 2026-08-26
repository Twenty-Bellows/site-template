<?php

/**
 * Plugin Name:       Template Plugin Customizations
 * Description:       Customizations for the Template Site.
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      8.0
 * Author:            twentybellows
 * License:           GPL-2.0-or-later
 * Text Domain:       template-plugin
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 *
 * Site functionality: forms, custom post types, integrations. One file per
 * concern, loaded in alphabetical order.
 *
 */
foreach (glob(__DIR__ . '/imports/*.php') ?: array() as $template_plugin_import) {
	require_once $template_plugin_import;
}

/**
 *
 * Enqueue plugin styles — on enqueue_block_assets so the editor iframe gets
 * them too
 *
 */
add_action('enqueue_block_assets', function () {

	$asset_file = __DIR__ . '/build/plugin.asset.php';
	if (! file_exists($asset_file)) {
		return;
	}
	$script_asset = require $asset_file;

	wp_enqueue_style(
		'template-plugin-style',
		plugin_dir_url(__FILE__) . 'build/plugin.css',
		array(),
		$script_asset['version'],
	);

});

/**
 *
 * Enqueue plugin scripts — front end only, deferred
 *
 */
add_action('wp_enqueue_scripts', function () {

	$asset_file = __DIR__ . '/build/plugin.asset.php';
	if (! file_exists($asset_file)) {
		return;
	}
	$script_asset = require $asset_file;

	wp_enqueue_script(
		'template-plugin-script',
		plugin_dir_url(__FILE__) . 'build/plugin.js',
		$script_asset['dependencies'],
		$script_asset['version'],
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

});

/**
 *
 * Register custom blocks from the build manifest
 *
 */
add_action('init', function () {
	$manifest = __DIR__ . '/build/blocks-manifest.php';
	if (! file_exists($manifest)) {
		return;
	}

	if (function_exists('wp_register_block_types_from_metadata_collection')) {
		// WP 6.8+: one call registers every block in the manifest.
		wp_register_block_types_from_metadata_collection(__DIR__ . '/build', $manifest);
	} else {
		foreach (array_keys(require $manifest) as $block_type) {
			register_block_type(__DIR__ . "/build/blocks/{$block_type}");
		}
	}

	// Server-side hook code that block.json cannot express lives in a per-block
	// hooks.php, copied into the build by webpack.
	foreach (array_keys(require $manifest) as $block_type) {
		if (file_exists(__DIR__ . "/build/blocks/{$block_type}/hooks.php")) {
			require_once __DIR__ . "/build/blocks/{$block_type}/hooks.php";
		}
	}
});
