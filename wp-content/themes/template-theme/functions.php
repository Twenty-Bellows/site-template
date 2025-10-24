<?php

/**
 *
 * Remove core block patterns
 *
 */
add_action('after_setup_theme', function () {
	remove_theme_support('core-block-patterns');
});

/**
 *
 * Enqueue theme styles and scripts
 *
 */
add_action('wp_enqueue_scripts', function () {

	// Enqueue theme JavaScript
	$script_asset = require(get_template_directory() . '/build/index.asset.php');

	wp_enqueue_script(
		'template-theme-script',
		get_template_directory_uri() . '/build/index.js',
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	wp_enqueue_style(
		'template-theme-style',
		get_template_directory_uri() . '/build/index.css',
		array(),
		$script_asset['version'],
	);

});

/**
 * 
 * Allow SVG uploads
 * 
 */
add_filter('upload_mimes', function ($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
});