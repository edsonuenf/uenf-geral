<?php
/**
 * Title: Últimas Notícias UENF
 * Slug: uenf-geral/news-list
 * Description: Lista das 3 últimas notícias com thumbnail e resumo
 * Categories: uenf-patterns, query
 * Keywords: noticias, posts, lista, recentes
 * Block Types: core/query
 * Post Types: page, post
 * Viewport Width: 1200
 */
?>

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">

    <!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"flex","columns":3}} -->
    <div class="wp-block-query">
        <!-- wp:post-template -->
        <!-- wp:group {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|normal","right":"var:preset|spacing|normal","bottom":"var:preset|spacing|normal","left":"var:preset|spacing|normal"}}},"borderColor":"fundo-claro","layout":{"type":"default"}} -->
        <div class="wp-block-group has-border-color has-fundo-claro-border-color"
            style="border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--normal);padding-right:var(--wp--preset--spacing--normal);padding-bottom:var(--wp--preset--spacing--normal);padding-left:var(--wp--preset--spacing--normal)">
            <!-- wp:post-featured-image {"isLink":true,"height":"200px","style":{"border":{"radius":"4px"}}} /-->

            <!-- wp:post-date {"style":{"typography":{"fontSize":"14px"}},"textColor":"secundaria"} /-->

            <!-- wp:post-title {"isLink":true,"style":{"typography":{"fontSize":"20px","fontWeight":"600"}}} /-->

            <!-- wp:post-excerpt {"moreText":"Ler mais"} /-->
        </div>
        <!-- /wp:group -->
        <!-- /wp:post-template -->
    </div>
    <!-- /wp:query -->
</div>
<!-- /wp:group -->