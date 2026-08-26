<?php
/**
 * Title: Footer
 * Slug: template-theme/footer
 * Description: Site footer band.
 * Inserter: no
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|lg","bottom":"var:preset|spacing|lg"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--lg)">
	<!-- wp:paragraph {"align":"center","fontSize":"small","textColor":"text-muted"} -->
	<p class="has-text-align-center has-text-muted-color has-text-color has-small-font-size">© <?php echo esc_html( gmdate( 'Y' ) ); ?> · Built by Twenty Bellows</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
