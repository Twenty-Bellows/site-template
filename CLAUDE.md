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

## WordPress Development Best Practices

### theme.json Configuration

#### Typography
- **Font Sizes**: Define **5 or fewer** custom font sizes for optimal editor UI (displays as buttons S/M/L/XL). More than 5 triggers a dropdown.
- **Fluid Typography**: Use fluid font sizes with `clamp()` for responsive scaling. WordPress default minimum is 14px.
- **Font Size Naming**: Use semantic slugs (e.g., `small`, `base`, `medium`, `large`, `x-large`) rather than specific size names.
- Example fluid font size:
  ```json
  {
    "size": "clamp(1rem, 0.9rem + 0.5vw, 1.25rem)",
    "fluid": {
      "min": "1rem",
      "max": "1.25rem"
    }
  }
  ```

#### Spacing
- **Spacing Presets**: Define **7 or fewer** spacing values for optimal editor UI (displays as buttons). More than 7 triggers a dropdown.
- **Spacing Scale**: Use a consistent scale system (e.g., 0.5rem, 1rem, 1.5rem, 2rem, 3rem, 4rem, 6rem).
- **Avoid WordPress Defaults**: Define custom spacing that fits the project's design system.

#### Colors
- **Semantic Organization**: Organize colors by purpose (primary, secondary, accent, neutral, etc.) not by hue.
- **Explicit Colors Only**: Define only the colors actually used in the design. No auto-generated variations.
- **Naming Convention**: Use clear, semantic names (e.g., `primary`, `primary-dark`, `surface`, `text`).

#### Custom Block Styles
- **Register in PHP**: Use `register_block_style()` in [functions.php](wp-content/themes/template-theme/functions.php) or plugin.
- **Style with CSS**: Keep styling in SCSS files, not inline in registration.
- **Naming**: Use kebab-case slugs that describe the visual treatment (e.g., `rounded-corners`, `bordered`, `highlight`).

### PHP Coding Standards

#### Escaping & Sanitization
- **Use Simplest Function**: Apply the most appropriate escaping function for the context:
  - `esc_html()` for plain text
  - `esc_attr()` for HTML attributes
  - `esc_url()` for URLs
  - `wp_kses_post()` for post content with allowed HTML
- **Translation Functions**: Use `__()` and `_e()` without additional escaping unless the output context requires it.

#### Security
- **Nonce Verification**: Always verify nonces for form submissions and AJAX requests.
- **Capability Checks**: Check user capabilities before allowing privileged operations.
- **SQL Queries**: Use `$wpdb->prepare()` for any dynamic SQL queries.
- **Sanitization**: Sanitize all user input with appropriate functions (`sanitize_text_field()`, `sanitize_email()`, etc.).

#### Performance
- **Avoid Queries in Loops**: Never run database queries inside loops. Batch queries when possible.
- **Use Transients**: Cache expensive operations (API calls, complex queries) with transients.
- **Conditional Loading**: Only enqueue scripts/styles on pages where they're needed.
- **Hook Performance**: Be aware of hooks that run on every page load vs admin-only.

#### Hooks & Filters
- **Default Priority**: Use default priority (10) unless there's a specific need to change it.
- **Hook Naming**: Prefix custom hooks with theme/plugin slug (e.g., `template_after_header`).
- **Remove Actions Carefully**: Document why core hooks are removed (as seen in [functions.php:12](wp-content/themes/template-theme/functions.php)).

### Block Development

#### Block Patterns
- **Location**: Store patterns as HTML files in [/patterns/](wp-content/themes/template-theme/patterns/) directory.
- **Naming**: Use descriptive, kebab-case filenames (e.g., `hero-with-cta.php`, `testimonial-grid.php`).
- **Front Matter**: Include pattern metadata (title, description, categories) in PHP comment blocks.

#### Custom Blocks
- **Creation**: Use `npm run create-block` script to scaffold new blocks in the plugin.
- **Default Type**: Create dynamic blocks by default. Simplify to static if dynamic rendering proves unnecessary.
- **Block Structure**: Follow `@wordpress/create-block` conventions for consistent structure.
- **Registration**: Register blocks in the plugin ([template-plugin.php](wp-content/plugins/template-plugin/template-plugin.php)), not the theme.

### Accessibility
- **Semantic HTML**: Use appropriate HTML elements (headings, landmarks, lists, etc.).
- **ARIA Labels**: Add ARIA labels where semantic HTML isn't sufficient.
- **Keyboard Navigation**: Ensure all interactive elements are keyboard accessible.
- **Color Contrast**: Verify color combinations meet WCAG AA standards (4.5:1 for normal text).
- **Focus Indicators**: Maintain visible focus indicators for keyboard navigation.

### General Conventions
- **WordPress Coding Standards**: Follow WordPress PHP, CSS, and JavaScript coding standards.
- **Code Organization**: Theme handles presentation and FSE features. Plugin handles site-specific functionality.
- **Comments**: Add comments for complex logic or non-obvious decisions. Avoid obvious comments.