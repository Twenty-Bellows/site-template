# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress block theme and plugin development template using `@wordpress/env` for local development.

## Commands

### Initial Setup
- `npm install` - Install dependencies
- `npm run init-project` - Initialize project with custom name/slug (one-time use, self-deletes)

### Development Environment
- `npm start` - Start the WordPress development environment (http://localhost:8888)
- `npm stop` - Stop the WordPress development environment
- `npm run wp-env <command>` - Run any wp-env command
- `npm run build` - Build/compile SCSS and JavaScript
- `npm run watch` - Watch and auto-compile SCSS and JavaScript on changes
- `npm run package` - Create a distributable theme zip file

### WordPress Admin Access
- URL: http://localhost:8888/wp-admin
- Username: admin
- Password: password

## Architecture

### Theme Structure (wp-content/themes/template-theme/)
- **Block Theme**: Uses Full Site Editing with templates and patterns
- **templates/**: Full page templates (index.html, singular.html)
- **parts/**: Reusable template parts (header.html)
- **patterns/**: Block patterns for content layouts
- **src/**: Source files (JavaScript and SCSS)
- **styles/**: SCSS source files for custom styling
- **build/**: Compiled CSS and JavaScript output
- **theme.json**: Global styles and settings configuration
- **functions.php**: Theme initialization and core pattern removal
- **style.css**: Theme metadata and information

### Plugin Structure (wp-content/plugins/template-plugin/)
- **template-plugin.php**: Main plugin file for site-specific customizations
- Intended for functionality that should persist regardless of theme changes

### Development Configuration
- **.wp-env.json**: Configures local WordPress environment
  - Maps local wp-content to container
  - Auto-activates theme and plugin
  - Sets up test environment on port 8889
- **webpack.config.js**: Build configuration for SCSS and JavaScript
  - Compiles theme assets from `/src/` and `/styles/` to `/build/`
  - Based on @wordpress/scripts webpack configuration

## Note on Folder Names

After running `npm run init-project`, the template folders will be renamed:
- `template-theme` becomes `[your-slug]-theme`
- `template-plugin` becomes `[your-slug]-plugin`

All file references are automatically updated to match.