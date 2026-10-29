---
name: block-themes
description: WordPress Full Site Editing (FSE) theme architecture. Use when generating theme.json, block templates, template parts, patterns, and functions.php for WordPress block themes.
---

# WordPress Block Theming Skill

Conventions for building WordPress block themes with Full Site Editing (FSE).

## Working with Pattern Builder

Where the Pattern Builder (PB) plugin is installed (it is, in this project), its
`pattern-author` and `design-reproduction` guides are the authority on patterns,
templates, template parts and design tokens. Load them (or call
`get-authoring-guide`) before writing any of those. This skill covers the theme
around them: file layout, `functions.php`, `style.css`, fonts, motion and
composition. Where the two overlap, this skill states the rule in short and PB
holds the detail; if they ever disagree, PB wins.

The rules that matter most, all from PB:

- **Generate markup, don't type it.** Hand-written block markup can render
  perfectly and still show "unexpected or invalid content" in the editor. Build
  it with the block library (`createBlock` + `serialize`), then validate it
  (`get-validator`). Never hand-write a pattern and call it done.
- **Tokens are roles, not hues** (colors), **five font sizes**, **numeric
  spacing**. Details under theme.json below.
- **Width comes from the layout, never from padding.**
- **A look goes in the first place that can hold it.** No made-up classes.
- **Factor before you write.** Repeated shapes become element patterns.

## Absolute Rules

- **NO EMOJIS**: Never use emojis anywhere in generated content - not in headings, paragraphs, button text, or any other text. This applies to all templates, patterns, and content.
- **NO decorative HTML comments**: Never insert section-labeling comments like `<!-- Hero Section -->` in templates, template parts, or patterns. Only WordPress block comments (`<!-- wp:block-name -->`) are allowed.

## Current WordPress (7.1)

Written against WordPress 7.1. What that means for theme work:

- **theme.json is still `"version": 3`.** Point `$schema` at the current release
  (`https://schemas.wp.org/wp/7.1/theme.json`) rather than an old pinned one.
- **Blocks must use Block API version 3+** (`"apiVersion": 3` in `block.json`) — the
  editor always renders inside an iframe now, and older API versions misbehave there.
- **Element pseudo-classes are native**: `:hover`, `:focus`, and `:active` work in
  `styles.elements.link` / `styles.elements.button` in theme.json. Prefer them over CSS
  for link and button states.
- **Per-viewport block settings**: a block's `style` takes `@tablet` and `@mobile`
  values (breakpoints 480px / 782px unless `settings.viewport` says otherwise). A grid
  that drops to one column, or padding that shrinks, is a block setting, not a media query.
- **Declare currency in `style.css`**: `Requires at least: 6.6`, `Tested up to: 7.1`,
  `Requires PHP: 8.0` are sane 2026 values for a new theme.

## Theme Architecture

```
theme-slug/
├── theme.json           # Central configuration file
├── style.css            # Theme metadata + baseline CSS
├── functions.php        # Asset enqueuing, pattern categories
├── templates/           # Block templates (index.html is required)
├── parts/               # Template parts: one-line shells that place a pattern
├── patterns/            # Block patterns (PHP files with a header comment)
└── styles/              # Block style variations (JSON)
```

In a repo with a build pipeline (this one) there is also `src/` and `scss/`,
compiled to `build/`.

## theme.json Configuration

The central configuration file. Define the whole design system here, once.

```json
{
  "$schema": "https://schemas.wp.org/wp/7.1/theme.json",
  "version": 3,
  "settings": {
    "appearanceTools": true,
    "useRootPaddingAwareAlignments": true,
    "position": { "sticky": true },
    "layout": { "contentSize": "42rem", "wideSize": "64rem" },
    "color": { "palette": [], "defaultPalette": false, "defaultGradients": false },
    "typography": { "fontSizes": [], "defaultFontSizes": false },
    "spacing": { "spacingSizes": [], "defaultSpacingSizes": false }
  }
}
```

### Tokens (PB's design system)

- **Colors: a role, never a hue.** The slug says what the color does. PB accepts only
  these roles: `base`, `surface`, `surface-muted`, `surface-strong`, `surface-inverse`,
  `contrast`, `text-muted`, `text-subtle`, `text-inverse`, `on-primary`, `on-secondary`,
  `on-accent`, `primary`, `primary-hover`, `secondary`, `secondary-hover`, `tertiary`,
  `accent`, `accent-strong`, `accent-muted`, `hairline`, `hairline-strong`, `success`,
  `warning`, `error`, `info`, `overlay`, `highlight`. `base` is the page and `contrast`
  is the text on it. Define only the colors the design uses. Never inline a hex.
- **Font sizes: five, and no others.** `x-small`, `small`, `medium`, `large`,
  `x-large`, each larger than the last. A measured size between two takes the nearer.
  Fluid sizes give `fluid.min`/`fluid.max` and let WordPress derive the clamp; sizes
  that should not scale declare `"fluid": false`.
- **Heading sizes are not font sizes.** Set `h1`–`h6` on `styles.elements`; heading
  blocks then set no `fontSize`. Text that is not a heading never borrows a heading's size.
- **Spacing: numeric slugs** (`20`–`80`, `40`–`60` preferred), meaning in the `name`
  (`{ "slug": "40", "name": "Base", "size": "1.5rem" }`). Seven or fewer steps keeps the
  editor UI as buttons.
- **Font families: none on a pattern** unless the design needs a specific face.

### Styles

Define the site's defaults, which patterns inherit and must not restate:

```json
{
  "styles": {
    "color": { "background": "base", "text": "contrast" },
    "typography": { "fontFamily": "body", "fontSize": "small", "lineHeight": "1.5-1.65" },
    "spacing": { "padding": { "left": "…", "right": "…" } },
    "elements": {
      "h1": {}, "h2": {}, "h3": {}, "h4": {}, "h5": {}, "h6": {},
      "heading": { "typography": { "lineHeight": "1.1-1.3" } },
      "link": {}, "button": {}
    }
  }
}
```

(Values shown as prose are guidance; reference presets as `var(--wp--preset--…)`.)
Root `styles.spacing.padding` is the site's only side spacing; with
`useRootPaddingAwareAlignments` every `alignfull` band still reaches the window's edges.

### Typography choices

Choose type for the brief rather than defaulting to system fonts when the design
direction is yours to set. Body 1rem or larger, body line height 1.5–1.65, headings
1.1–1.3, never below 1.0. When reproducing an existing design the sizes are the
source's, not yours: map them to the five sizes and give the headings their exact
values on `styles.elements`.

## Block Templates

Templates are plain HTML (no PHP) and stay thin: a handful of references plus the
block that shows the content. Everything with a design lives in a pattern.

```html
<!-- wp:group {"style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group">
	<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

	<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
	<main class="wp-block-group">
		<!-- wp:post-content {"layout":{"type":"constrained"}} /-->
	</main>
	<!-- /wp:group -->

	<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
</div>
<!-- /wp:group -->
```

- The outer group with a zero `blockGap` keeps the site's block gap out from between
  header, content and footer.
- Content is a `main` group; header and footer parts take `header` / `footer` tagNames.
- Reference a part by slug alone (no `theme` attribute).
- A sticky header goes in its own group with `position: sticky` and needs
  `settings.position.sticky` in theme.json.
- Keep images in patterns, not templates. Leave `ref` off `core/navigation`.
- Archives and search results use `core/query` with `"inherit": true`.

## Template Parts

A part is a one-line shell that places a pattern, so the design can use the theme's
assets, localized text and the pattern tooling:

```html
<!-- wp:pattern {"slug":"theme-slug/header"} /-->
```

- Give every part its `area` (`header`, `footer`) in theme.json `templateParts`.
- The header and footer patterns carry `Inserter: no`.
- Header: a Wide or Regular group, `site-title` (`level: 0` so it renders `<p>`, not
  `<h1>`) and `navigation` in a flex row, padded top and bottom only.
- Footer: matching or complementing the header. Include the footer margin reset in
  `style.css`.

## Block Patterns

Patterns are PHP files in `patterns/` with a header comment.

```php
<?php
/**
 * Title: FAQ Entry
 * Slug: theme-slug/faq-entry
 * Description: One question and its answer above a hairline, repeated to build a list.
 * Categories: theme-slug_elements
 */
?>
```

- `Title` and `Slug` are required; the slug is namespaced. Write a real `Description`.
- `Categories` must be registered (core's, or the theme's via
  `register_block_pattern_category()`), or the pattern files under Uncategorized.
- PHP in a pattern is limited to localization calls, `home_url()` and
  `get_theme_file_uri()`, always inside `esc_url()` / `esc_html()` as appropriate.
  Internal links are `href="<?php echo esc_url( home_url( '/about/' ) ); ?>"`.
- Compose with `<!-- wp:pattern {"slug":"theme-slug/section-intro"} /-->` (self-closing)
  rather than copying markup. Build bottom-up: elements, sections, pages.
- Real placeholder copy, not lorem ipsum.
- Validate every pattern; an empty report is the only acceptable result.

## Fonts: declared in theme.json, installed with PB

**Never enqueue a font CDN** (Google Fonts, Adobe Fonts, Bunny) at runtime: it adds a
third-party request, a privacy exposure, and a point of failure. Use PB's `add-font`
(check `list-fonts` first), which installs the files in the theme and writes the preset
in one step. The collection serves fixed weights, one file per weight, not variable
axes, so a design leaning on a variable axis will set differently; say so.

If installing by hand, ship woff2 files at `assets/fonts/<family>/` and declare them
with `fontFace` so WordPress loads them in the front end and the editor:

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

Licensed fonts come from the client and ship the same way. Keep the fallback stack real.

## functions.php

Keep functions.php minimal. Styles the editor should also see belong on
`enqueue_block_assets` (front end AND editor iframe). Front-end behavior scripts stay on
`wp_enqueue_scripts` and load deferred.

```php
<?php
// Compiled stylesheet for both front end and editor
add_action( 'enqueue_block_assets', function () {
    wp_enqueue_style(
        'theme-slug-style',
        get_template_directory_uri() . '/build/index.css',
        array(),
        wp_get_theme()->get( 'Version' )
    );
} );

// Front-end behavior only, deferred
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'theme-slug-script',
        get_template_directory_uri() . '/build/index.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        array( 'in_footer' => true, 'strategy' => 'defer' )
    );
} );

// Pattern categories
add_action( 'init', function () {
    register_block_pattern_category(
        'theme-slug_elements',
        array( 'label' => __( 'Elements', 'theme-slug' ) )
    );
} );
```

In a theme with no build step, enqueue `get_stylesheet_uri()` instead (WordPress
already auto-enqueues `style.css`, so enqueue it only if the editor needs it too).

### Security

- Escape any user-derived value `functions.php` outputs: `esc_html()` (HTML),
  `esc_attr()` (attributes), `esc_url()` (URLs).
- Never use `eval()`, `create_function()`, `shell_exec()`, `exec()`, or `system()`.
- Static block themes with hardcoded content do not need escaping; core blocks handle it.

## style.css

Theme metadata, plus the small baseline CSS every theme ships: the
`prefers-reduced-motion` block and the footer margin reset. WordPress auto-enqueues it on
the front end and in the editor.

```css
/*
Theme Name: Theme Name
Author: Author Name
Description: A block theme
Version: 1.0.0
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 8.0
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: theme-slug
*/

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}

.wp-site-blocks > footer {
  margin-block-start: 0;
}
```

## Where a Look Goes

Put a look in the first place that can hold it:

1. **theme.json**: the palette, scales, and what every heading, link and button looks like.
2. **The block's own settings**, using the tokens (colors, spacing, one of the five
   sizes, gap, per-viewport values).
3. **A block style variation's styles**: a named look in `styles/{slug}.json`.
4. **The variation's `css`**: pseudo-elements, transforms, transitions, hover states,
   for its own block only.
5. **The theme stylesheet**, last, only for what none of the above can hold:
   `@keyframes`, `@media`, `::selection`, a focus ring. Scope each rule to a variation's
   `is-style-*` class or an element, and say in the handoff what went there and why.

A class you make up (`className: "fade-up"`) and style from a stylesheet is a hook the
editor cannot show and nobody can apply. PB's validator flags it. If a look needs a
name, it is a variation. Variation CSS: declarations first, then nested rules anchored
on `&`; no comments, no at-rules, no `url()`, no `,` in a selector; 2000 characters.
It styles one block, never the blocks placed inside it (use the variation's `blocks`
entries for those).

## Animation & Motion

Motion is a block style variation like any other look. The variation carries the
declarations in its JSON `css`; `@keyframes` (an at-rule a variation cannot hold) live
in the theme's SCSS under the same name. The editor then lists the animation in the
block's Styles panel, and anyone can apply it.

`styles/fade-up.json`:

```json
{
  "$schema": "https://schemas.wp.org/wp/7.1/theme.json",
  "version": 3,
  "slug": "fade-up",
  "title": "Fade Up",
  "blockTypes": [ "core/group", "core/heading", "core/columns", "core/image" ],
  "styles": {
    "css": "animation: fade-up 0.6s ease both;"
  }
}
```

`scss/styles/_fade-up.scss` (imported from `scss/theme.scss`):

```scss
@keyframes fade-up {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}
```

Apply it in a pattern as `{"className":"is-style-fade-up"}`.

**Hover transitions** need no stylesheet at all (`styles/hover-lift.json`):

```json
{
  "slug": "hover-lift",
  "title": "Hover Lift",
  "blockTypes": [ "core/group", "core/column" ],
  "styles": {
    "css": "transition: transform 0.2s ease, box-shadow 0.2s ease; &:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15); }"
  }
}
```

**Ambient motion** (a floating shape, a pulsing accent) is the same split: the variation
sets `animation: float 3s ease-in-out infinite;`, the SCSS holds the `@keyframes`.

**Scroll-triggered reveals** need a variation, a little SCSS and a small script. Keep
the content visible unless the script has run, so the editor and no-JS visitors never
see hidden sections. Script, in the theme's `src/index.js` (it compiles to the already
enqueued, deferred `build/index.js`; a standalone theme adds it through `functions.php`):

```js
const targets = document.querySelectorAll( '.is-style-reveal-on-scroll' );
if ( targets.length && 'IntersectionObserver' in window ) {
	document.documentElement.classList.add( 'has-reveal' );
	const observer = new IntersectionObserver( ( entries ) => {
		entries.forEach( ( entry ) => {
			if ( entry.isIntersecting ) {
				entry.target.classList.add( 'is-visible' );
				observer.unobserve( entry.target );
			}
		} );
	}, { threshold: 0.15 } );
	targets.forEach( ( el ) => observer.observe( el ) );
}
```

`styles/reveal-on-scroll.json` carries only `slug`, `title`, `blockTypes`. SCSS:

```scss
.has-reveal .is-style-reveal-on-scroll {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;

  &.is-visible {
    opacity: 1;
    transform: translateY(0);
  }
}
```

Respect `prefers-reduced-motion` (already in `style.css`). Not every element needs
motion. Prioritize the hero entrance, scroll reveals on major sections, interactive
cards and buttons, and one or two decorative ambient animations. Staggering children
means reaching into blocks inside a variation, which a variation cannot do; give each
child the same variation instead, or skip the stagger.

## Card layouts in rows

- A fixed number of cards across: `core/columns` with `align: wide`. A wrapping grid:
  `core/group` with `{"layout":{"type":"grid","minimumColumnWidth":"16rem"}}`. Not a flex
  group: flex children with no width size to their content.
- The card is an **element pattern**. It is a part, not a band, so it never carries a
  `constrained` layout at its root (give it the default layout) or it fills the whole track.
- Grid and column children stretch to equal height. For a bottom-aligned CTA, make the
  card a vertical flex group with `justifyContent: space-between` and the buttons as its
  last child; check it in `render-pattern`'s page preview.
- Card images use the image block's own settings: `aspectRatio`, `scale` (cover/contain),
  `width`. No inline `object-fit` or pixel heights.

## Landing Page Composition

Think like a landing page designer, not a template assembler. Each section is a visually
distinct band that creates rhythm as the user scrolls. **You decide** which sections serve
the site's type, audience and goal.

### Section Architecture

- A section is a group with `layout.type: constrained` that is **Wide or Regular**,
  padded **top and bottom only**. Its content is Wide or Regular too. Never pad the
  sides to place content: the space to the window's edge is the theme's root padding.
- Make a section `align: full` **only when its background must reach the window's
  edges** (a painted band, a cover). Its content is still Wide or Regular, with no side
  padding. A text-only band with no background is not full width.
- Use `align: wide` for hero content, feature grids and columns. Use Regular for
  text-heavy reading sections.
- Space comes from a group's `blockGap` and the band's top/bottom padding, not from
  spacer blocks between sections. The template's zero-gap wrapper handles the
  header/content/footer joins; no per-section margin resets are needed.
- Use the full block markup for each block; do not rely on `<inner-blocks>` shorthand.

### Visual Rhythm

Alternate treatments so adjacent sections never look alike:

| Technique | WordPress Implementation |
|-----------|------------------------|
| Alternating backgrounds | Alternate `backgroundColor` between `base` and `surface` (or `primary` / `surface-inverse` for bold sections) |
| Full-bleed imagery | `core/cover` with `"align":"full"` and an overlay from the palette |
| Image beside copy | `core/media-text`, not hand-built columns (it handles stacking and ratios) |
| Bold CTA bands | Full-width group with a `primary` or `accent` background, centered text, text color `on-primary` / `on-accent` |
| Breathing room | A larger top/bottom padding step on the band |

If two adjacent sections share a background and layout, change the background, flip the
image side, or switch from grid to a single column.

## Image Handling

Only use images the user supplied. Stock image URLs often fail in the editor. For
everything else use PB: `find-media` lists what exists (use the `reference` it returns
verbatim), `add-asset` uploads a file or URL, and `add-placeholder-image` makes a
placeholder. Never a remote placeholder service. Hand back the list of images the
person still needs to supply.

### Visual Richness Without Images

Convey atmosphere through the theme's own tools:

- **Color blocks and typography**: bold use of palette roles, large distinctive headings,
  varied weights and sizes (from the five sizes).
- **Gradients and shadows**: gradient backgrounds and shadows from block settings or a
  variation.
- **Borders and frames**: borders from block settings, a hairline color token.
- **Decorative details**: pseudo-elements (`::before`, `::after`) belong in a block style
  variation's `css`, scoped to its own block.
- **Whitespace**: generous space or controlled density, set with the spacing scale.
