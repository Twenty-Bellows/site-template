const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');

// @wordpress/scripts builds its config from env vars at require time, so each
// bundle needs its own fresh evaluation with the env it wants. Sharing one
// evaluation leaks the plugin's block-copy and manifest plugins into the theme
// compilation, which litters the theme build with blocks/ and
// blocks-manifest.php belonging to the plugin.
function freshWpScriptsConfig() {
	const id = require.resolve('@wordpress/scripts/config/webpack.config');
	delete require.cache[id];
	return require('@wordpress/scripts/config/webpack.config');
}

// ---------------------------------------------------------------------------
// Plugin bundle: custom blocks (block.json + PHP copied to build, manifest
// generated) plus the sitewide plugin.js/plugin.css pair.
process.env.WP_COPY_PHP_FILES_TO_DIST = true;
process.env.WP_BLOCKS_MANIFEST = true;
process.env.WP_SOURCE_PATH = './wp-content/plugins/template-plugin/src';

const pluginDefaults = freshWpScriptsConfig();

// With no blocks under src/blocks yet, the entry helper falls back to
// src/index.js under the bare name "index" — the same file the explicit
// `plugin` entry already bundles. Drop the duplicate.
const pluginEntries = getWebpackEntryPoints('script')();
delete pluginEntries.index;

const copyPlugin = new CopyWebpackPlugin({
	patterns: [{
		from: 'src/blocks/**/hooks.php',
		to: ({ context, absoluteFilename }) => {
			const blockName = absoluteFilename
				.replace(context + '/src/blocks/', '')
				.replace('/hooks.php', '');
			return `blocks/${blockName}/hooks.php`;
		},
		noErrorOnMissing: true,
	}],
});

const pluginConfig = {
	...pluginDefaults,
	entry: {
		// Plugin blocks from src directory
		...pluginEntries,

		plugin: path.resolve(__dirname, 'wp-content/plugins/template-plugin/src/index.js'),
	},
	output: {
		path: path.resolve(__dirname, 'wp-content/plugins/template-plugin/build'),
		filename: '[name].js',
		clean: true
	},
	plugins: [
		...pluginDefaults.plugins,
		copyPlugin,
	]
};

// ---------------------------------------------------------------------------
// Theme bundle: compiled SCSS + JS only — no blocks, no manifest.
delete process.env.WP_BLOCKS_MANIFEST;
process.env.WP_SOURCE_PATH = './wp-content/themes/template-theme/src';

const themeDefaults = freshWpScriptsConfig();

const themeConfig = {
	...themeDefaults,
	entry: {
		...getWebpackEntryPoints('script')(),
	},
	output: {
		path: path.resolve(__dirname, 'wp-content/themes/template-theme/build'),
		filename: '[name].js',
		clean: true
	}
};

// The plugin bundle's PHP-copy filter reads this env var again at compile
// time, after both configs are built — keep it set.
process.env.WP_COPY_PHP_FILES_TO_DIST = true;

module.exports = [themeConfig, pluginConfig];
