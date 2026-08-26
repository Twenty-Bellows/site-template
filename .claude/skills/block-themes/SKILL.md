---
name: block-themes
description: WordPress Full Site Editing (FSE) theme architecture. Use when generating theme.json, block templates, template parts, patterns, and functions.php for WordPress block themes.
---

# WordPress Block Theming Skill

Comprehensive knowledge for building WordPress block themes using Full Site Editing (FSE) architecture.

## Absolute Rules

- **NO EMOJIS**: Never use emojis anywhere in generated content - not in headings, paragraphs, button text, or any other text. This applies to all templates, patterns, and content.

## Current WordPress (7.1)

Written against WordPress 7.1. What that means for theme work:

- **theme.json is still `"version": 3`.** Point `$schema` at the current release
  (`https://schemas.wp.org/wp/7.1/theme.json`) rather than an old pinned one.
- **Blocks must use Block API version 3+** (`"apiVersion": 3` in `block.json`) — the
  editor always renders inside an iframe now, and older API versions misbehave there.
- **Element pseudo-classes are native**: `:hover`, `:focus`, and `:active` work in
  `styles.elements.link` / `styles.elements.button` in theme.json. Prefer them over CSS
  for link and button states.
- **Declare currency in `style.css`**: `Requires at least: 6.6`, `Tested up to: 7.1`,
  `Requires PHP: 8.0` are sane 2026 values for a new theme.

## Theme Architecture

### Directory Structure

```
theme-slug/
├── theme.json           # Central configuration file
├── style.css            # Theme metadata + custom CSS
├── functions.php        # Asset enqueuing, pattern registration
├── templates/           # Block templates
│   ├── index.html       # Main/fallback template
│   ├── single.html      # Single post
│   ├── page.html        # Single page
│   ├── archive.html     # Archive listings
│   ├── search.html      # Search results
│   └── 404.html         # Not found
├── parts/               # Reusable template parts
│   ├── header.html      # Site header
│   └── footer.html      # Site footer
└── patterns/            # Block patterns
    ├── hero.php
    ├── features.php
    └── cta.php
```

## theme.json Configuration

The `theme.json` file is the central configuration for block themes. It defines:

### Schema and Version

```json
{
  "$schema": "https://schemas.wp.org/wp/7.1/theme.json",
  "version": 3
}
```

### Settings

Define available options for the editor:

```json
{
  "settings": {
    "appearanceTools": true,
    "layout": { "contentSize": "800px", "wideSize": "1280px" },
    "color": {
      "palette": [ /* 5 colors: primary, secondary, accent, light, dark */ ],
      "defaultPalette": false,
      "defaultGradients": false
    },
    "typography": {
      "fontFamilies": [ /* heading + body font families */ ],
      "fontSizes": [ /* 5-6 step scale: small through huge */ ]
    },
    "spacing": {
      "units": ["px", "em", "rem", "%", "vw", "vh"],
      "spacingSizes": [ /* 6 steps from compact to spacious */ ]
    }
  }
}
```

### Styles

Define default styles for the site and blocks:

```json
{
  "styles": {
    "color": { /* background + text from palette */ },
    "typography": { /* body font family, medium size, line-height 1.5-1.65 */ },
    "elements": {
      "heading": { /* heading font family, appropriate weight, line-height 1.1-1.3 */ },
      "link": { /* accent color */ },
      "button": { /* accent background, light text, border-radius */ }
    }
  }
}
```
## Typography
- Choose fonts that are beautiful, unique, and interesting. Avoid generic fonts like Arial and Inter; opt instead for distinctive choices that elevate the frontend's aesthetics; unexpected, characterful font choices. Pair a distinctive display font with a refined body font.
- **Font size scale**: Keep sizes grounded and usable. Body: 1rem. Headings: scale modestly (h1 ≤ 2.5–3rem). Use `clamp()` for responsive display text, but cap at ~3.5rem max. Avoid "massive"/"gigantic" sizes above 4rem—they rarely improve design and often degrade it. A good 6-step scale: 0.875rem / 1rem / 1.25rem / 1.75rem / 2.25rem / clamp(2.5rem, 4vw, 3.5rem).
- **Line height**: Body text: 1.5–1.65. Headings: 1.1–1.3. Never go below 1.0 for any text. Apply via `styles.typography.lineHeight` and `styles.elements.heading.typography.lineHeight` in theme.json.

## Block Templates

Templates use WordPress block markup (HTML comments with JSON attributes).

### Template Structure

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
  <!-- Content blocks here -->
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

## Template Parts

### Header Requirements
- Constrained layout group with site-appropriate background
- Flex row: `site-title` (level:0 — renders `<p>` not `<h1>`) + `navigation`
- Appropriate padding using spacing presets

### Footer Requirements
- Constrained layout group, matching or complementing header style
- Content varies by site type (copyright, social links, contact info, etc.)
- Include footer margin reset in style.css

## Block Patterns

Patterns are PHP files that register reusable block content.

### Pattern Registration

```php
<?php
/**
 * Title: Hero Section
 * Slug: theme-slug/hero
 * Categories: featured
 */
?>
<!-- wp:group {"backgroundColor":"primary","textColor":"light","layout":{"type":"constrained"}} -->
...
<!-- /wp:group -->
```

## Fonts: self-hosted, declared in theme.json

**Never enqueue a font CDN** (Google Fonts, Adobe Fonts, Bunny) at runtime — it adds a
third-party request, a privacy exposure, and a point of failure. Download the font files
(woff2), ship them in the theme at `assets/fonts/<family>/`, and declare them with
`fontFace` so WordPress loads them on the front end and in the editor with no PHP at all:

```json
{
  "settings": {
    "typography": {
      "fontFamilies": [
        {
          "name": "Fraunces",
          "slug": "display",
          "fontFamily": "Fraunces, Georgia, serif",
          "fontFace": [
            {
              "fontFamily": "Fraunces",
              "fontStyle": "normal",
              "fontWeight": "400 700",
              "src": [ "file:./assets/fonts/fraunces/fraunces-variable.woff2" ]
            }
          ]
        }
      ]
    }
  }
}
```

Prefer variable fonts (one file, a weight range in `fontWeight`) and keep the fallback
stack real. Fonts chosen from an open catalog get downloaded once and vendored; licensed
fonts come from the client and ship the same way.

## functions.php

Keep functions.php minimal. Primary uses:

### Asset Enqueuing

Styles the editor should also see (block styles, animation classes) belong on the
`enqueue_block_assets` hook — it loads assets on the front end AND inside the editor
iframe. Front-end behavior scripts stay on `wp_enqueue_scripts` (they should not run in
the editor) and load deferred:

```php
<?php
/**
 * Theme functions and definitions
 */

// Stylesheet for both front-end and editor
add_action( 'enqueue_block_assets', function () {
    wp_enqueue_style(
        'theme-slug-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );
} );

// Front-end behavior only, deferred (WP 6.3+ script strategy)
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'theme-slug-script',
        get_template_directory_uri() . '/build/index.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        array(
            'in_footer' => true,
            'strategy'  => 'defer',
        )
    );
} );

// Register block patterns
add_action( 'init', function () {
    register_block_pattern_category(
        'theme-slug',
        array( 'label' => __( 'Theme Patterns', 'theme-slug' ) )
    );
} );
```

## Security in Generated Code

- When `functions.php` outputs any user-derived value, use WordPress escaping functions:
  - HTML context: `esc_html()`
  - Attribute context: `esc_attr()`
  - URL context: `esc_url()`
- Never use `eval()`, `create_function()`, `shell_exec()`, `exec()`, or `system()` in generated theme code
- Static block themes with hardcoded content (the default) do not need escaping — WordPress core blocks handle this. Escaping matters only if generating PHP that renders dynamic data.

## style.css

The style.css file contains theme metadata, and WordPress auto-enqueues it on the front
end and in the editor — which makes it the right home for small baseline CSS every theme
needs (the `prefers-reduced-motion` block, the footer margin reset).

**Where the rest of the CSS goes depends on the project.** In a repo with a build
pipeline (this one: `scss/` compiled to `build/index.css` by `npm run build`), custom CSS
that theme.json cannot express — animation, composition techniques critical to the
design — belongs in the SCSS, not style.css. In a standalone theme with no build, bring
that CSS across into style.css instead. Either way it must ship: a design direction that
lives only in a comp is not implemented.

```css
/*
Theme Name: Theme Name
Theme URI: https://example.com
Author: Author Name
Author URI: https://example.com
Description: A beautiful WordPress block theme
Version: 1.0.0
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 8.0
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: theme-slug
*/

```

## Animation & Motion in Block Themes

Animation brings life to block themes, but WordPress block markup requires a specific pattern to connect CSS animations to blocks.

### The className Pattern

Add animation classes to blocks via the `className` JSON attribute. WordPress renders this as a class on the wrapper div:

```html
<!-- wp:group {"className":"fade-up","align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull fade-up">
  <!-- content -->
</div>
<!-- /wp:group -->
```

This works on any block — groups, columns, headings, paragraphs, buttons, images:

```html
<!-- wp:heading {"className":"slide-in-left"} -->
<h2 class="wp-block-heading slide-in-left">Features</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"stagger-children","align":"wide"} -->
<div class="wp-block-columns alignwide stagger-children">
  ...
</div>
<!-- /wp:columns -->
```

### Animation Classes in the theme stylesheet

Generate and adapt these classes (these are examples only, do not limit yourself to
these) in the theme's stylesheet — the `scss/` build in a repo that has one, `style.css`
otherwise:

**Entrance animations:**
```css
.fade-up {
  opacity: 0;
  transform: translateY(30px);
  animation: fadeUp 0.6s ease forwards;
}
.fade-in {
  opacity: 0;
  animation: fadeIn 0.6s ease forwards;
}
.slide-in-left {
  opacity: 0;
  transform: translateX(-40px);
  animation: slideIn 0.7s ease forwards;
}
.slide-in-right {
  opacity: 0;
  transform: translateX(40px);
  animation: slideIn 0.7s ease forwards;
}

@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
@keyframes fadeIn { to { opacity: 1; } }
@keyframes slideIn { to { opacity: 1; transform: translateX(0); } }
```

**Staggered children** — delays applied via nth-child:
```css
.stagger-children > * {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeUp 0.5s ease forwards;
}
.stagger-children > *:nth-child(1) { animation-delay: 0.1s; }
.stagger-children > *:nth-child(2) { animation-delay: 0.2s; }
.stagger-children > *:nth-child(3) { animation-delay: 0.3s; }
.stagger-children > *:nth-child(4) { animation-delay: 0.4s; }
```

**Interactive transitions:**
```css
.hover-lift {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}
.hover-glow {
  transition: box-shadow 0.3s ease;
}
.hover-glow:hover {
  box-shadow: 0 0 20px rgba(var(--wp--preset--color--accent-rgb, 0,0,0), 0.3);
}
```

**Continuous ambient motion:**
```css
.float {
  animation: float 3s ease-in-out infinite;
}
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}
.pulse-subtle {
  animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}
```

### Scroll-Triggered Reveals

The most impactful animation pattern — sections revealing as the user scrolls — requires
a small IntersectionObserver script. **In a repo with a JS build (this one), put it in the
theme's `src/index.js`**, which compiles to the already-enqueued `build/index.js` — no
functions.php change needed. In a standalone theme with no build, add it to
`functions.php`:

```php
// Enqueue scroll animation observer
function theme_slug_scroll_animations() {
    wp_add_inline_script( 'theme-slug-style', "
        document.addEventListener('DOMContentLoaded', function() {
            var els = document.querySelectorAll('.animate-on-scroll');
            if (!els.length) return;
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });
            els.forEach(function(el) { observer.observe(el); });
        });
    " );
}
add_action( 'enqueue_block_assets', 'theme_slug_scroll_animations' );
```

**Note:** `wp_add_inline_script` requires an existing registered script handle. Since block themes may not always have a script registered, a more reliable approach is to output the script directly:

```php
// Scroll animation observer — outputs inline script
function theme_slug_scroll_animations() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var els = document.querySelectorAll('.animate-on-scroll');
        if (!els.length) return;
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        els.forEach(function(el) { observer.observe(el); });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'theme_slug_scroll_animations' );
```

Then in the theme stylesheet, pair with CSS that starts elements hidden and animates them when `.is-visible` is added:

```css
.animate-on-scroll {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}
.animate-on-scroll.is-visible {
  opacity: 1;
  transform: translateY(0);
}
```

Use `className: "animate-on-scroll"` on section-level Group blocks:

```html
<!-- wp:group {"className":"animate-on-scroll","align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull animate-on-scroll">
  <!-- section content -->
</div>
<!-- /wp:group -->
```

### prefers-reduced-motion (Required)

Every theme MUST include this in `style.css` (it ships there, auto-enqueued, even in
build-pipeline repos):

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
```

### How Much Animation

Not every element needs animation. Prioritize:
- **Hero section entrance** — the first impression (fade-up, scale, or slide)
- **Section reveals on scroll** — major content blocks with `animate-on-scroll`
- **Interactive elements** — cards with `hover-lift`, buttons with transitions
- **1-2 decorative ambient animations** — a floating shape, gradient shift, or pulsing accent

Avoid animating every heading, paragraph, and button individually — it creates visual noise rather than delight.

## Card layouts in rows

For equal-height, equal-width cards ( with optional bottom-aligned CTAs ), use this structure unless the user specifies otherwise:

```
Columns (className: "equal-cards")
  └── Column
        verticalAlignment: "stretch"
        width: "X%" where X = 100 / number_of_cards (e.g., 2 cards = 50%, 3 cards = 33.33%, 4 cards = 25%)
        └── Group [card wrapper]
              └── [content: headings, paragraphs, images*, lists]
              └── (optional) Buttons (className: "cta-bottom")
```

**Width rule**: All cards in a row MUST have equal width. Calculate each column's width as `100% / number_of_cards` (e.g., 3 cards = 33.33% each). The sum of all column widths must equal exactly 100% - never exceed the parent element width.

*Images in cards: `style="height:200px;object-fit:cover;width:100%"`

**Required CSS** (theme stylesheet):
```css
.equal-cards > .wp-block-column {
  display: flex;
  flex-direction: column;
  flex-grow: 0;
}
.equal-cards > .wp-block-column > .wp-block-group {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}
```
If present, ensure bottom-aligned CTAs unless otherwise specified:
```css
.equal-cards .cta-bottom {
  margin-top: auto;
  justify-content: center;
}
```
Always add the following CSS (in `style.css`, alongside the reduced-motion block) to reset the footer top margin:
```css
.wp-site-blocks > footer {
  margin-block-start: 0;
}
```

## Landing Page Composition

When generating homepage block markup, think like a landing page designer, not a template assembler. Every section should be a visually distinct, full-width band that creates rhythm and visual impact as the user scrolls.

### Section Architecture

- **Section margin reset**: Add `"style":{"spacing":{"margin":{"top":"0"}}}` to every top-level Group block that wraps a landing page section. This overrides WordPress's default top margin on direct children of `.wp-site-blocks` and can be easily adjusted by users in the editor.
- **Section layout widths**: Hero sections, header groups, cover blocks, and feature grids should use `"align":"wide"` or `"align":"full"` rather than defaulting to narrow content width. Only use default (content) alignment for text-heavy reading sections.
- Do **not** use `<inner-blocks>`; output the full expanded markup inside each block.
- **Columns alignment**: ALWAYS set `"align":"wide"` on `wp:columns` blocks (and add the `alignwide` class on the wrapper div) unless specifically instructed otherwise.
- **No decorative HTML comments**: Never insert section-labeling comments like `<!-- Hero Section -->` or `<!-- Services Section -->` in templates, template parts, or patterns. Only WordPress block comments (`<!-- wp:block-name -->`) are allowed.

**Every major homepage section must be `alignfull`** — edge-to-edge across the viewport. Content inside can be constrained, but the section wrapper fills the screen width. This is the **full-bleed wrapper, constrained content** pattern:

`<!-- wp:group {"align":"full","backgroundColor":"...","layout":{"type":"constrained"}} -->`

**Never use bare `{"layout":{"type":"constrained"}}` without `"align":"full"` for homepage sections.** Without `alignfull`, sections render at `contentSize` (800px) and the page looks narrow and lifeless.

**YOU DECIDE** which sections best serve this specific site. Do not follow a rigid template. Consider the site type, audience, and primary goal:

- A portfolio needs a full-bleed project gallery
- A SaaS needs feature grids with clear value props
- A restaurant needs appetizing imagery and menu sections
- An agency needs case study cards and social proof
- An escape room needs atmosphere and immersion

### Visual Rhythm

Alternate between visual treatments to create rhythm as the user scrolls:

| Technique | WordPress Implementation |
|-----------|------------------------|
| Alternating backgrounds | Alternate `backgroundColor` between `background` and `surface` (or `primary`/`secondary` for bold sections) |
| Full-bleed imagery | Cover blocks with `"align":"full"` and `overlayColor` from the brand palette |
| Edge-to-edge media-text | `wp:media-text` with `"align":"full"` for alternating image/content sides |
| Bold CTA bands | Full-width group with `primary` or `accent` background, centered text |
| Spacer breaks | `wp:spacer` between sections for breathing room |

Every section should feel visually distinct from its neighbors. If two adjacent sections have the same background color and layout pattern, the page feels monotonous — change the background, flip the image side, switch from grid to single-column, or add a cover block break.

## Image Handling

ONLY add user provided images/image URLs to the initial site build. Stock image urls often fail to load in the block editor and break the design.
Look at any user supplies images carefully and include them in the design if appropriate, but do not force them in if they do not fit the design. 

### Creating Visual Richness Without Images

Since only user provided images/image URLs can be used, if none are available convey atmosphere and visual interest through:

- **CSS Gradients**: Linear, radial, and conic gradients for depth and color
- **Color Blocks**: Bold use of background colors to create visual hierarchy
- **Typography as Design**: Large, distinctive headings; creative font pairing; varied text sizes and weights
- **CSS Patterns**: Repeating backgrounds using CSS gradients (stripes, dots, grids)
- **Shadows & Depth**: Box-shadow, text-shadow, and drop-shadow for dimension
- **Borders & Frames**: Creative use of borders, outlines, and decorative frames
- **Spacing & Layout**: Generous whitespace or controlled density to create mood
- **CSS Pseudo-elements**: ::before and ::after for decorative visual elements
- **Color Overlays**: Layered divs with transparency for atmospheric effects