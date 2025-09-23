const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,
	entry: {
		theme: [
			path.resolve(__dirname, 'wp-content/themes/template-theme/src/index.js'),
			path.resolve(__dirname, 'wp-content/themes/template-theme/styles/theme.scss')
		]
	},
	output: {
		path: path.resolve(__dirname, 'wp-content/themes/template-theme/build'),
		filename: '[name].js',
		clean: false
	}
};