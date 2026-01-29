<?php
/**
 * Title: Kitchen Sink
 * Slug: template-theme/kitchen-sink
 * Description: A comprehensive pattern showcasing all core blocks, colors, spacing, and typography options.
 * Categories: featured
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|base","bottom":"var:preset|spacing|base"}}},"layout":{"type":"default"}} -->
  <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--base);padding-bottom:var(--wp--preset--spacing--base)">
    <!-- wp:heading {"level":1} -->
      <h1 class="wp-block-heading">Kitchen Sink Pattern</h1>
    <!-- /wp:heading -->
    <!-- wp:paragraph -->
      <p>This pattern demonstrates all the core WordPress blocks, theme colors, spacing presets, and typography settings available in this theme.</p>
    <!-- /wp:paragraph -->
    <!-- wp:separator -->
      <hr class="wp-block-separator has-alpha-channel-opacity"/>
    <!-- /wp:separator -->
    <!-- wp:heading -->
      <h2 class="wp-block-heading">Typography</h2>
    <!-- /wp:heading -->
    <!-- wp:heading {"level":1,"fontSize":"x-large"} -->
      <h1 class="wp-block-heading has-x-large-font-size">Heading 1 (Extra Large)</h1>
    <!-- /wp:heading -->
    <!-- wp:heading {"fontSize":"large"} -->
      <h2 class="wp-block-heading has-large-font-size">Heading 2 (Large)</h2>
    <!-- /wp:heading -->
    <!-- wp:heading {"level":3,"fontSize":"medium"} -->
      <h3 class="wp-block-heading has-medium-font-size">Heading 3 (Medium)</h3>
    <!-- /wp:heading -->
    <!-- wp:heading {"level":4} -->
      <h4 class="wp-block-heading">Heading 4 (Base)</h4>
    <!-- /wp:heading -->
    <!-- wp:heading {"level":5,"fontSize":"small"} -->
      <h5 class="wp-block-heading has-small-font-size">Heading 5 (Small)</h5>
    <!-- /wp:heading -->
    <!-- wp:heading {"level":6,"fontSize":"small"} -->
      <h6 class="wp-block-heading has-small-font-size">Heading 6 (Small)</h6>
    <!-- /wp:heading -->
    <!-- wp:heading {"level":4} -->
      <h4 class="wp-block-heading">Paragraph Font Sizes</h4>
    <!-- /wp:heading -->
    <!-- wp:paragraph {"fontSize":"small"} -->
      <p class="has-small-font-size"><strong>Small:</strong> This is paragraph text at the small font size (0.875rem). Often used for captions, footnotes, or secondary information.</p>
    <!-- /wp:paragraph -->
    <!-- wp:paragraph {"fontSize":"base"} -->
      <p class="has-base-font-size"><strong>Base:</strong> This is paragraph text at the base font size (1rem). This is the default body text size with <strong>bold text</strong>, <em>italic text</em>, and <a href="#">a link</a>.</p>
    <!-- /wp:paragraph -->
    <!-- wp:paragraph {"fontSize":"medium"} -->
      <p class="has-medium-font-size"><strong>Medium:</strong> This is paragraph text at the medium font size (1.125-1.25rem fluid). Slightly larger for emphasis or readability.</p>
    <!-- /wp:paragraph -->
    <!-- wp:paragraph {"fontSize":"large"} -->
      <p class="has-large-font-size"><strong>Large:</strong> This is paragraph text at the large font size (1.5-2rem fluid). Great for introductory paragraphs or callouts.</p>
    <!-- /wp:paragraph -->
    <!-- wp:paragraph {"fontSize":"x-large"} -->
      <p class="has-x-large-font-size"><strong>Extra Large:</strong> This is paragraph text at the extra large font size (2-3rem fluid). Used for major statements or hero text.</p>
    <!-- /wp:paragraph -->
    <!-- wp:separator {"className":"is-style-wide"} -->
      <hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
    <!-- /wp:separator -->
    <!-- wp:heading -->
      <h2 class="wp-block-heading">Colors</h2>
    <!-- /wp:heading -->
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|sm","left":"var:preset|spacing|sm"}}}} -->
      <div class="wp-block-columns">
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
              <div class="wp-block-group has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
                <!-- wp:heading {"level":5} -->
                  <h5 class="wp-block-heading">Base</h5>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"fontSize":"small"} -->
                  <p class="has-small-font-size">#ffffff</p>
                <!-- /wp:paragraph -->
              </div>
            <!-- /wp:group -->
          </div>
        <!-- /wp:column -->
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
              <div class="wp-block-group has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
                <!-- wp:heading {"level":5} -->
                  <h5 class="wp-block-heading">Surface</h5>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"fontSize":"small"} -->
                  <p class="has-small-font-size">#f5f5f5</p>
                <!-- /wp:paragraph -->
              </div>
            <!-- /wp:group -->
          </div>
        <!-- /wp:column -->
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"}}},"backgroundColor":"surface-variant","layout":{"type":"constrained"}} -->
              <div class="wp-block-group has-surface-variant-background-color has-background" style="padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
                <!-- wp:heading {"level":5} -->
                  <h5 class="wp-block-heading">Surface Variant</h5>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"fontSize":"small"} -->
                  <p class="has-small-font-size">#e8e8e8</p>
                <!-- /wp:paragraph -->
              </div>
            <!-- /wp:group -->
          </div>
        <!-- /wp:column -->
      </div>
    <!-- /wp:columns -->
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|sm","left":"var:preset|spacing|sm"},"margin":{"top":"var:preset|spacing|sm"}}}} -->
      <div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--sm)">
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"}}},"backgroundColor":"primary","textColor":"base","layout":{"type":"constrained"}} -->
              <div class="wp-block-group has-base-color has-primary-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
                <!-- wp:heading {"level":5,"textColor":"base"} -->
                  <h5 class="wp-block-heading has-base-color has-text-color">Primary</h5>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"textColor":"base","fontSize":"small"} -->
                  <p class="has-base-color has-text-color has-small-font-size">#333333</p>
                <!-- /wp:paragraph -->
              </div>
            <!-- /wp:group -->
          </div>
        <!-- /wp:column -->
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"}}},"backgroundColor":"primary-hover","textColor":"base","layout":{"type":"constrained"}} -->
              <div class="wp-block-group has-base-color has-primary-hover-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
                <!-- wp:heading {"level":5,"textColor":"base"} -->
                  <h5 class="wp-block-heading has-base-color has-text-color">Primary Hover</h5>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"textColor":"base","fontSize":"small"} -->
                  <p class="has-base-color has-text-color has-small-font-size">#000000</p>
                <!-- /wp:paragraph -->
              </div>
            <!-- /wp:group -->
          </div>
        <!-- /wp:column -->
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"}}},"backgroundColor":"accent","textColor":"base","layout":{"type":"constrained"}} -->
              <div class="wp-block-group has-base-color has-accent-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
                <!-- wp:heading {"level":5,"textColor":"base"} -->
                  <h5 class="wp-block-heading has-base-color has-text-color">Accent</h5>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"textColor":"base","fontSize":"small"} -->
                  <p class="has-base-color has-text-color has-small-font-size">#4a4a4a</p>
                <!-- /wp:paragraph -->
              </div>
            <!-- /wp:group -->
          </div>
        <!-- /wp:column -->
      </div>
    <!-- /wp:columns -->
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|sm","left":"var:preset|spacing|sm"},"margin":{"top":"var:preset|spacing|sm"}}}} -->
      <div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--sm)">
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
              <div class="wp-block-group has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
                <!-- wp:heading {"level":5,"textColor":"text"} -->
                  <h5 class="wp-block-heading has-text-color">Text</h5>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"textColor":"text","fontSize":"small"} -->
                  <p class="has-text-color has-small-font-size">#1a1a1a</p>
                <!-- /wp:paragraph -->
              </div>
            <!-- /wp:group -->
          </div>
        <!-- /wp:column -->
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
              <div class="wp-block-group has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
                <!-- wp:heading {"level":5,"textColor":"text-muted"} -->
                  <h5 class="wp-block-heading has-text-muted-color has-text-color">Text Muted</h5>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"textColor":"text-muted","fontSize":"small"} -->
                  <p class="has-text-muted-color has-text-color has-small-font-size">#666666</p>
                <!-- /wp:paragraph -->
              </div>
            <!-- /wp:group -->
          </div>
        <!-- /wp:column -->
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"},"border":{"width":"1px"}},"borderColor":"border"},"backgroundColor":"base","layout":{"type":"constrained"}} -->
              <div class="wp-block-group has-border-color has-border-color has-base-background-color has-background" style="border-width:1px;padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
                <!-- wp:heading {"level":5} -->
                  <h5 class="wp-block-heading">Border</h5>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"fontSize":"small"} -->
                  <p class="has-small-font-size">#d4d4d4</p>
                <!-- /wp:paragraph -->
              </div>
            <!-- /wp:group -->
          </div>
        <!-- /wp:column -->
      </div>
    <!-- /wp:columns -->
    <!-- wp:separator -->
      <hr class="wp-block-separator has-alpha-channel-opacity"/>
    <!-- /wp:separator -->
    <!-- wp:heading -->
      <h2 class="wp-block-heading">Spacing</h2>
    <!-- /wp:heading -->
    <!-- wp:paragraph -->
      <p>The theme provides 7 spacing presets for consistent margins and padding:</p>
    <!-- /wp:paragraph -->
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|xs","bottom":"var:preset|spacing|xs","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"},"margin":{"bottom":"var:preset|spacing|xs"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--xs);padding-top:var(--wp--preset--spacing--xs);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--xs);padding-left:var(--wp--preset--spacing--sm)">
        <!-- wp:paragraph {"fontSize":"small"} -->
          <p class="has-small-font-size"><strong>Extra Small (xs)</strong>: 0.5rem padding</p>
        <!-- /wp:paragraph -->
      </div>
    <!-- /wp:group -->
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"},"margin":{"bottom":"var:preset|spacing|xs"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--xs);padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--sm)">
        <!-- wp:paragraph {"fontSize":"small"} -->
          <p class="has-small-font-size"><strong>Small (sm)</strong>: 1rem padding</p>
        <!-- /wp:paragraph -->
      </div>
    <!-- /wp:group -->
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|base","bottom":"var:preset|spacing|base","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"},"margin":{"bottom":"var:preset|spacing|xs"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--xs);padding-top:var(--wp--preset--spacing--base);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--base);padding-left:var(--wp--preset--spacing--sm)">
        <!-- wp:paragraph {"fontSize":"small"} -->
          <p class="has-small-font-size"><strong>Base</strong>: 1.5rem padding</p>
        <!-- /wp:paragraph -->
      </div>
    <!-- /wp:group -->
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"},"margin":{"bottom":"var:preset|spacing|xs"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--xs);padding-top:var(--wp--preset--spacing--md);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--md);padding-left:var(--wp--preset--spacing--sm)">
        <!-- wp:paragraph {"fontSize":"small"} -->
          <p class="has-small-font-size"><strong>Medium (md)</strong>: 2rem padding</p>
        <!-- /wp:paragraph -->
      </div>
    <!-- /wp:group -->
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|lg","bottom":"var:preset|spacing|lg","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"},"margin":{"bottom":"var:preset|spacing|xs"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--xs);padding-top:var(--wp--preset--spacing--lg);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--lg);padding-left:var(--wp--preset--spacing--sm)">
        <!-- wp:paragraph {"fontSize":"small"} -->
          <p class="has-small-font-size"><strong>Large (lg)</strong>: 3rem padding</p>
        <!-- /wp:paragraph -->
      </div>
    <!-- /wp:group -->
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"},"margin":{"bottom":"var:preset|spacing|xs"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--xs);padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--sm)">
        <!-- wp:paragraph {"fontSize":"small"} -->
          <p class="has-small-font-size"><strong>Extra Large (xl)</strong>: 4rem padding</p>
        <!-- /wp:paragraph -->
      </div>
    <!-- /wp:group -->
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|2-xl","bottom":"var:preset|spacing|2-xl","left":"var:preset|spacing|sm","right":"var:preset|spacing|sm"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--2-xl);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--2-xl);padding-left:var(--wp--preset--spacing--sm)">
        <!-- wp:paragraph {"fontSize":"small"} -->
          <p class="has-small-font-size"><strong>2X Large (2-xl)</strong>: 6rem padding</p>
        <!-- /wp:paragraph -->
      </div>
    <!-- /wp:group -->
    <!-- wp:separator -->
      <hr class="wp-block-separator has-alpha-channel-opacity"/>
    <!-- /wp:separator -->
    <!-- wp:heading -->
      <h2 class="wp-block-heading">Buttons</h2>
    <!-- /wp:heading -->
    <!-- wp:buttons -->
      <div class="wp-block-buttons">
        <!-- wp:button -->
          <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Primary Button</a></div>
        <!-- /wp:button -->
        <!-- wp:button {"className":"is-style-outline"} -->
          <div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Outline Button</a></div>
        <!-- /wp:button -->
      </div>
    <!-- /wp:buttons -->
    <!-- wp:separator -->
      <hr class="wp-block-separator has-alpha-channel-opacity"/>
    <!-- /wp:separator -->
    <!-- wp:heading -->
      <h2 class="wp-block-heading">Lists</h2>
    <!-- /wp:heading -->
    <!-- wp:columns -->
      <div class="wp-block-columns">
        <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:heading {"level":3} -->
              <h3 class="wp-block-heading">Unordered List</h3>
            <!-- /wp:heading -->
            <!-- wp:list {"className":"wp-block-list"} -->
              <ul class="wp-block-list">
                <!-- wp:list-item -->
                  <li>First item</li>
                <!-- /wp:list-item -->
                <!-- wp:list-item -->
                  <li>Second item</li>
                <!-- /wp:list-item -->
                <!-- wp:list-item -->
                  <li>Third item with nested items:
                  <!-- wp:list -->
                    <ul class="wp-block-list">
                      <!-- wp:list-item -->
                        <li>Nested item 1</li>
                      <!-- /wp:list-item -->
                      <!-- wp:list-item -->
                        <li>Nested item 2</li>
                      <!-- /wp:list-item -->
                    </ul>
                  <!-- /wp:list -->
                </li>
              <!-- /wp:list-item -->
            </ul>
          <!-- /wp:list -->
        </div>
      <!-- /wp:column -->
      <!-- wp:column -->
        <div class="wp-block-column">
          <!-- wp:heading {"level":3} -->
            <h3 class="wp-block-heading">Ordered List</h3>
          <!-- /wp:heading -->
          <!-- wp:list {"ordered":true,"className":"wp-block-list"} -->
            <ol class="wp-block-list">
              <!-- wp:list-item -->
                <li>First step</li>
              <!-- /wp:list-item -->
              <!-- wp:list-item -->
                <li>Second step</li>
              <!-- /wp:list-item -->
              <!-- wp:list-item -->
                <li>Third step with sub-steps:
                <!-- wp:list {"ordered":true} -->
                  <ol class="wp-block-list">
                    <!-- wp:list-item -->
                      <li>Sub-step A</li>
                    <!-- /wp:list-item -->
                    <!-- wp:list-item -->
                      <li>Sub-step B</li>
                    <!-- /wp:list-item -->
                  </ol>
                <!-- /wp:list -->
              </li>
            <!-- /wp:list-item -->
          </ol>
        <!-- /wp:list -->
      </div>
    <!-- /wp:column -->
  </div>
<!-- /wp:columns -->
<!-- wp:separator -->
  <hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->
<!-- wp:heading -->
  <h2 class="wp-block-heading">Quotes</h2>
<!-- /wp:heading -->
<!-- wp:quote -->
  <blockquote class="wp-block-quote">
    <!-- wp:paragraph -->
      <p>This is a standard quote block. It's perfect for highlighting important statements or testimonials.</p>
    <!-- /wp:paragraph -->
    <cite>— Citation Author</cite></blockquote>
  <!-- /wp:quote -->
  <!-- wp:pullquote -->
    <figure class="wp-block-pullquote"><blockquote><p>This is a pullquote, designed to stand out and draw attention to key content within your text.</p><cite>Notable Source</cite></blockquote></figure>
  <!-- /wp:pullquote -->
  <!-- wp:separator -->
    <hr class="wp-block-separator has-alpha-channel-opacity"/>
  <!-- /wp:separator -->
  <!-- wp:heading -->
    <h2 class="wp-block-heading">Code &amp; Preformatted</h2>
  <!-- /wp:heading -->
  <!-- wp:paragraph -->
    <p>Inline code can be shown like this: <code>const example = 'Hello World';</code></p>
  <!-- /wp:paragraph -->
  <!-- wp:code -->
    <pre class="wp-block-code"><code>function greet(name) {
    return `Hello, ${name}!`;
    }
    console.log(greet('World'));</code></pre>
  <!-- /wp:code -->
  <!-- wp:preformatted -->
    <pre class="wp-block-preformatted">This is preformatted text.
    It preserves    spacing    and
    line breaks exactly as typed.</pre>
  <!-- /wp:preformatted -->
  <!-- wp:separator -->
    <hr class="wp-block-separator has-alpha-channel-opacity"/>
  <!-- /wp:separator -->
  <!-- wp:heading -->
    <h2 class="wp-block-heading">Table</h2>
  <!-- /wp:heading -->
  <!-- wp:table {"hasFixedLayout":false} -->
    <figure class="wp-block-table"><table><thead><tr><th>Column 1</th><th>Column 2</th><th>Column 3</th></tr></thead><tbody><tr><td>Row 1, Cell 1</td><td>Row 1, Cell 2</td><td>Row 1, Cell 3</td></tr><tr><td>Row 2, Cell 1</td><td>Row 2, Cell 2</td><td>Row 2, Cell 3</td></tr><tr><td>Row 3, Cell 1</td><td>Row 3, Cell 2</td><td>Row 3, Cell 3</td></tr></tbody></table></figure>
  <!-- /wp:table -->
</div>
<!-- /wp:group -->