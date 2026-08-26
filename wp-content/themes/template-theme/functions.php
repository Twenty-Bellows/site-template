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
 * Enqueue theme styles — on enqueue_block_assets so the editor iframe gets
 * them too, keeping block styles and animation classes visible while editing
 *
 */
add_action('enqueue_block_assets', function () {

	$asset_file = get_template_directory() . '/build/index.asset.php';
	if (! file_exists($asset_file)) {
		return;
	}
	$script_asset = require $asset_file;

	wp_enqueue_style(
		'template-theme-style',
		get_template_directory_uri() . '/build/index.css',
		array(),
		$script_asset['version'],
	);

});

/**
 *
 * Enqueue theme scripts — front end only, deferred
 *
 */
add_action('wp_enqueue_scripts', function () {

	$asset_file = get_template_directory() . '/build/index.asset.php';
	if (! file_exists($asset_file)) {
		return;
	}
	$script_asset = require $asset_file;

	wp_enqueue_script(
		'template-theme-script',
		get_template_directory_uri() . '/build/index.js',
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
 * Allow SVG uploads — administrators only, since SVG can carry script
 *
 */
add_filter('upload_mimes', function ($mimes) {
	if (current_user_can('manage_options')) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
});
