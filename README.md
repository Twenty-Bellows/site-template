# WordPress Site Template

A simple WordPress development template featuring a block theme and companion plugin

## Features

- **Block Theme**: Full Site Editing compatible WordPress theme
- **Companion Plugin**: Separate plugin for site-specific functionality
- **SCSS Processing**: Built-in Sass compilation for custom styles
- **Local Development**: WordPress environment via `@wordpress/env`
- **Project Initialization**: Script to customize project name and slug

## Requirements

- Node.js (v14 or higher)
- npm or yarn
- Docker (for wp-env)

## Quick Start

1. **Clone or download this template**

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Initialize your project**
   ```bash
   npm run init-project
   ```
   This will prompt you for:
   - Project name (e.g., "My Awesome Site")
   - Project slug (e.g., "my-awesome-site")

   The script will:
   - Replace all "Template" references with your project name
   - Rename theme and plugin folders to use your project slug
   - Update all configuration files
   - Clean up by removing the init script

4. **Start the development environment**
   ```bash
   npm start
   ```

5. **Access your site**
   - Frontend: http://localhost:8888
   - Admin: http://localhost:8888/wp-admin
   - Username: `admin`
   - Password: `password`

## Development

### Available Scripts

- `npm run init-project` - Initialize project with custom name and slug (one-time use)
- `npm start` - Start WordPress development environment
- `npm stop` - Stop WordPress development environment
- `npm run build` - Build/compile SCSS and JavaScript
- `npm run watch` - Watch files and auto-compile on changes
- `npm run package` - Create distributable theme zip file
- `npm run wp-env` - Run wp-env commands directly

### Project Structure

```
site-template/
├── wp-content/
│   ├── themes/
│   │   └── template-theme/        # Block theme
│   │       ├── build/             # Compiled CSS output
│   │       ├── parts/             # Template parts
│   │       ├── patterns/          # Block patterns
│   │       ├── styles/            # SCSS source files
│   │       ├── templates/         # Page templates
│   │       ├── functions.php      # Theme functions
│   │       ├── style.css          # Theme metadata
│   │       └── theme.json         # Theme configuration
│   └── plugins/
│       └── template-plugin/       # Companion plugin
│           └── template-plugin.php
├── .wp-env.json                   # WordPress environment config
├── webpack.config.js              # Webpack configuration for building
├── init-project.js                # Project initialization script (removed after init)
└── package.json                   # Node.js dependencies and scripts
```

### Theme Development

The theme uses WordPress's Full Site Editing features:

- **Templates**: Located in `/templates/`, define page layouts
- **Template Parts**: Located in `/parts/`, reusable components like headers
- **Patterns**: Located in `/patterns/`, pre-designed block layouts
- **Styles**: SCSS files in `/styles/` compile to `/build/`

### Plugin Development

The companion plugin (`template-plugin`) is for site-specific functionality that should persist regardless of theme changes.

### Building Assets

1. Edit SCSS files in `/wp-content/themes/template-theme/styles/`
2. Edit JavaScript in `/wp-content/themes/template-theme/src/`
3. Run `npm run build` to compile assets
4. Or use `npm run watch` for auto-compilation during development

The compiled CSS and JavaScript are output to `/build/` and automatically enqueued by the theme.

## Customization

After running `npm run init-project`, the following changes occur:

- All "Template" text is replaced with your project name
- Theme folder renamed from `template-theme` to `[your-slug]-theme`
- Plugin folder renamed from `template-plugin` to `[your-slug]-plugin`
- Plugin file renamed from `template-plugin.php` to `[your-slug]-plugin.php`
- Configuration files updated (package.json, .wp-env.json, webpack.config.js, .gitignore)
- The init-project script and npm command are removed (one-time use)
