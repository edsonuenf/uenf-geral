<?php
/**
 * Title: Destaques UENF (3 Colunas)
 * Slug: uenf-geral/highlights-grid
 * Description: Grid de 3 colunas para destaques principais (Graduação, Pós, Extensão)
 * Categories: uenf-patterns, featured
 * Keywords: destaques, colunas, graduacao, pos, extensao
 * Block Types: core/columns
 * Post Types: page
 * Viewport Width: 1200
 */
?>

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|grande","left":"var:preset|spacing|grande"}}}} -->
    <div class="wp-block-columns">
        <!-- wp:column {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|medio","right":"var:preset|spacing|medio","bottom":"var:preset|spacing|medio","left":"var:preset|spacing|medio"}}},"borderColor":"fundo-claro"} -->
        <div class="wp-block-column has-border-color has-fundo-claro-border-color"
            style="border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--medio);padding-right:var(--wp--preset--spacing--medio);padding-bottom:var(--wp--preset--spacing--medio);padding-left:var(--wp--preset--spacing--medio)">
            <!-- wp:heading {"textAlign":"center","level":3,"textColor":"primaria"} -->
            <h3 class="wp-block-heading has-text-align-center has-primaria-color has-text-color">Graduação</h3>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">Conheça nossos cursos de graduação e ingresse no ensino superior de
                qualidade.</p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons">
                <!-- wp:button {"width":100,"variant":"outline","className":"is-style-outline"} -->
                <div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a
                        class="wp-block-button__link">Saiba Mais</a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|medio","right":"var:preset|spacing|medio","bottom":"var:preset|spacing|medio","left":"var:preset|spacing|medio"}}},"borderColor":"fundo-claro"} -->
        <div class="wp-block-column has-border-color has-fundo-claro-border-color"
            style="border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--medio);padding-right:var(--wp--preset--spacing--medio);padding-bottom:var(--wp--preset--spacing--medio);padding-left:var(--wp--preset--spacing--medio)">
            <!-- wp:heading {"textAlign":"center","level":3,"textColor":"primaria"} -->
            <h3 class="wp-block-heading has-text-align-center has-primaria-color has-text-color">Pós-Graduação</h3>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">Especialize-se com nossos programas de mestrado e doutorado reconhecidos.
            </p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons">
                <!-- wp:button {"width":100,"variant":"outline","className":"is-style-outline"} -->
                <div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a
                        class="wp-block-button__link">Saiba Mais</a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|medio","right":"var:preset|spacing|medio","bottom":"var:preset|spacing|medio","left":"var:preset|spacing|medio"}}},"borderColor":"fundo-claro"} -->
        <div class="wp-block-column has-border-color has-fundo-claro-border-color"
            style="border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--medio);padding-right:var(--wp--preset--spacing--medio);padding-bottom:var(--wp--preset--spacing--medio);padding-left:var(--wp--preset--spacing--medio)">
            <!-- wp:heading {"textAlign":"center","level":3,"textColor":"primaria"} -->
            <h3 class="wp-block-heading has-text-align-center has-primaria-color has-text-color">Extensão</h3>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">Projetos que conectam a universidade à comunidade e promovem o
                desenvolvimento.</p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons">
                <!-- wp:button {"width":100,"variant":"outline","className":"is-style-outline"} -->
                <div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a
                        class="wp-block-button__link">Saiba Mais</a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->