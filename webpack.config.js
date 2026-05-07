process.env.WP_COPY_PHP_FILES_TO_DIST = true;
process.env.WP_BLOCKS_MANIFEST = true;
process.env.WP_SOURCE_PATH = './wp-content/plugins/template-plugin/src'; 

const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');
const CopyWebpackPlugin = require('copy-webpack-plugin');

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
	...defaultConfig,
	entry: {
		// Plugin blocks from src directory
		...getWebpackEntryPoints('script')(),

		plugin: path.resolve(__dirname, 'wp-content/plugins/template-plugin/src/index.js'),
	},
	output: {
		path: path.resolve(__dirname, 'wp-content/plugins/template-plugin/build'),
		filename: '[name].js',
		clean: true 
	},
	plugins: [
		...defaultConfig.plugins,
		copyPlugin,
	]
};

process.env.WP_SOURCE_PATH = './wp-content/themes/template-theme/src'; 
const themeConfig = {
	...defaultConfig,
	entry: {
		// Default script entry points (e.g. for blocks)
		...getWebpackEntryPoints('script')(),
	},
	output: {
		path: path.resolve(__dirname, 'wp-content/themes/template-theme/build'),
		filename: '[name].js',
		clean: true
	}	
};



module.exports = [themeConfig, pluginConfig];