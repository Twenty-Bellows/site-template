process.env.WP_COPY_PHP_FILES_TO_DIST = true;
process.env.WP_BLOCKS_MANIFEST = true;

const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');

const themeConfig = {
	...defaultConfig,
	entry: {
		// Default script entry points (e.g. for blocks)
		...getWebpackEntryPoints('script', 'wp-content/themes/template-theme/src')(),

		theme: [
			path.resolve(__dirname, 'wp-content/themes/template-theme/src/index.js'),
			path.resolve(__dirname, 'wp-content/themes/template-theme/styles/theme.scss')
		],
	},
	output: {
		path: path.resolve(__dirname, 'wp-content/themes/template-theme/build'),
		filename: '[name].js',
		clean: false
	}
};

const pluginConfig = {
	...defaultConfig,
	entry: {
		// Plugin blocks from src directory
		...getWebpackEntryPoints('script', 'wp-content/plugins/template-plugin/src')(),

		plugin: path.resolve(__dirname, 'wp-content/plugins/template-plugin/src/index.js')
	},
	output: {
		path: path.resolve(__dirname, 'wp-content/plugins/template-plugin/build'),
		filename: '[name].js',
		clean: false
	}
};

module.exports = [themeConfig, pluginConfig];