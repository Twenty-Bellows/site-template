<?php
/**
 * Title: Blog Index
 * Slug: template-theme/index-page
 * Description: A pattern for the main blog index with a heading and post list.
 */
?>

<!-- wp:group  -->
<div class="wp-block-group">

	<!-- wp:query {"query":{"perPage":100,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date"},"displayLayout":{"type":"list"}} -->
		<div class="wp-block-query">
			<!-- wp:post-template -->
				<!-- wp:post-title {"isLink":true} /-->
				<!-- wp:post-excerpt /-->
			<!-- /wp:post-template -->
		</div>
	<!-- /wp:query -->

</div>
<!-- /wp:group -->