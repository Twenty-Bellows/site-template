# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress block theme and plugin development template using `@wordpress/env` for local development.

The local site ships two helper plugins, installed by `.wp-env.json` from wordpress.org:
**Pattern Builder** and **Create Block Theme**. Pattern Builder is central to how this
project is built — see [Pattern Builder](#pattern-builder).

## Commands

### Initial Setup
- `npm install` - Install dependencies
- `npm run init-project` - Initialize project with custom name/slug (one-time use, self-deletes)

### Development Environment
- `npm run server:start` - Start the WordPress development environment with Xdebug (http://localhost:8888)
- `npm run server:stop` - Stop the WordPress development environment
- `npm run wp-env <command>` - Run any wp-env command
- `npm run build` - Build/compile SCSS and JavaScript
- `npm run watch` - Watch and auto-compile SCSS and JavaScript on changes
- `npm run create-block` - Scaffold a new block in the plugin's `src/blocks/`
- `npm run package` - Create distributable theme and plugin zips in `deliverables/`
  (`src/` is excluded; run `npm run build` first)

### WordPress Admin Access
- URL: http://localhost:8888/wp-admin
- Username: admin
- Password: password

## Architecture

### Theme Structure (wp-content/themes/template-theme/)
- **Block Theme**: Uses Full Site Editing with templates and patterns
- **templates/**: Full page templates (index.html)
- **parts/**: Template parts (header.html, footer.html) — each is a one-line shell
  referencing a pattern (`<!-- wp:pattern {"slug":"template-theme/header"} /-->`) so the real
  markup lives in patterns/
- **patterns/**: Block patterns as PHP files — header.php and footer.php (the markup behind
  the part shells, registered with `Inserter: no`) and kitchen-sink.php (a showcase of core
  blocks and theme presets, not placed in any template)
- **src/**: Source files (JavaScript and SCSS entry)
- **scss/**: SCSS source compiled to build/ — including one partial per block style
  variation under scss/styles/, each imported from scss/theme.scss
- **styles/**: Block style variation registrations (`*.json` with `blockTypes`); their CSS
  lives in the matching scss/styles/ partial
- **build/**: Compiled CSS and JavaScript output
- **theme.json**: Global styles and settings configuration
- **functions.php**: Removes core block patterns, enqueues `build/index.css` (front end and
  editor, via `enqueue_block_assets`) and `build/index.js` (front end only, deferred), and
  allows SVG uploads for administrators
- **style.css**: Theme metadata, plus the baseline CSS every site ships (the
  `prefers-reduced-motion` block and the footer margin reset) — WordPress auto-enqueues it
  on the front end and in the editor

### Plugin Structure (wp-content/plugins/template-plugin/)
- **template-plugin.php**: Main plugin file — auto-loads `imports/`, enqueues the plugin
  build, and registers custom blocks from `build/blocks-manifest.php`
- **src/index.js** + **styles/plugin.scss**: Sitewide plugin JS/CSS entry, built to `build/plugin.*`
- **src/blocks/**: Custom blocks (created by `npm run create-block`); an optional
  per-block `hooks.php` is copied to the build and loaded on `init`
- **imports/**: Site functionality — forms, custom post types, integrations — one file per
  concern, auto-loaded in alphabetical order
- Intended for functionality that should persist regardless of theme changes

### Development Configuration
- **.wp-env.json**: Configures local WordPress environment
  - Maps local wp-content to container
  - Installs Pattern Builder and Create Block Theme from wordpress.org
  - Auto-activates the theme and plugin after start
  - Sets up test environment on port 8889
- **webpack.config.js**: Build configuration for SCSS and JavaScript
  - Exports two builds: the theme (`src/index.js`, which imports `scss/theme.scss`, to the
    theme's `build/`) and the plugin (`src/index.js` plus any `src/blocks/*/block.json`
    blocks, to the plugin's `build/`)
  - Based on @wordpress/scripts webpack configuration

## Pattern Builder

Pattern Builder (by Twenty Bellows) is installed in the local environment and is the
preferred way to author and inspect patterns, templates, and template parts. Theme pattern
files in `patterns/` are the single source of truth: nothing is mirrored into the database,
so edits made in the editor are written back to the files.

**Before building patterns, templates, or parts, look up how Pattern Builder works** rather
than guessing — its docs and agent guides are the authority:
- Source and docs: https://github.com/Twenty-Bellows/pattern-builder (`CLAUDE.md`,
  `docs/abilities.md`, `docs/architecture.md`)
- Plugin page: https://wordpress.org/plugins/pattern-builder/
- With the site running, the `get-authoring-guide` ability returns the `pattern-author`
  guide (block vocabulary, markup contract, validation) and the `design-reproduction`
  guide (building from an existing design)

Key points:
- **Abilities API / MCP**: Pattern Builder registers abilities (WordPress Abilities API,
  exposed as MCP tools when the MCP Adapter plugin is active) to list, read, create, and
  update patterns, templates, and parts; add design tokens and block style variations;
  and set layout and global styles. Prefer them over hand-writing files when available.
  Updates use hash-based conflict detection (`expectedHash`).
- **Validation**: Writes are refused for malformed block markup, unregistered blocks, and
  `core/pattern` references that resolve to nothing. Its validator (`get-validator`) runs
  the block editor's own checks, which catch markup that renders on the front end but
  shows "Block contains unexpected or invalid content" in the editor.
- **Patterns vs templates vs parts**: Patterns hold reusable content/section markup.
  Templates (`templates/*.html`) are the structural skeleton and may place parts and
  patterns; parts (`parts/*.html`) are reusable fragments with an area. Keep images in
  patterns, not templates; no PHP in templates; avoid database-ID references.
- **PHP in patterns**: Limited to localization calls and links/files of the site itself
  (`home_url()`, `get_theme_file_uri()`); Pattern Builder will not write other PHP.
- **Design tokens**: Use role-based slugs (`surface`, `text-muted`, `primary`), never
  hue-based names. Block style variations live in `styles/{slug}.json`.
- **Plugin patterns are read-only**; copy them into the theme to edit.

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
- Example fluid font size — `size` is the plain fallback, and WordPress derives the
  `clamp()` from `fluid.min`/`fluid.max` (never write the clamp yourself); sizes that
  should not scale declare `"fluid": false`:
  ```json
  {
    "size": "1.25rem",
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
- **Register as JSON**: Add a file under the theme's `styles/` directory with `slug`,
  `title`, and `blockTypes` (see [styles/video-rounded-corners.json](wp-content/themes/template-theme/styles/video-rounded-corners.json)).
  `register_block_style()` in PHP remains available for plugin-registered styles.
- **Style with CSS**: Keep the styling in a matching `scss/styles/_<slug>.scss` partial,
  imported from `scss/theme.scss` — a style variation whose partial is not imported ships
  with no CSS at all.
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
- **Remove Actions Carefully**: Document why core hooks are removed (as seen in [functions.php:9](wp-content/themes/template-theme/functions.php)).

### Block Development

#### Block Patterns
- **Location**: Store patterns as PHP files in [/patterns/](wp-content/themes/template-theme/patterns/) directory, authored through Pattern Builder where possible (see [Pattern Builder](#pattern-builder)).
- **Naming**: Use descriptive, kebab-case filenames (e.g., `hero-with-cta.php`, `testimonial-grid.php`).
- **Front Matter**: Include pattern metadata (title, description, categories) in PHP comment blocks; internal-only patterns (header, footer) add `Inserter: no`.
- **Internal links**: Build them with `home_url()` (`href="<?php echo esc_url( home_url( '/about/' ) ); ?>"`), never a hardcoded root-absolute path — `href="/about/"` breaks on any subdirectory install.
- **Validate**: Validate pattern, template, and part markup with Pattern Builder's validator after editing.

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