<?php
/**
 * Title: Cartão de Contato UENF
 * Slug: uenf-geral/contact-card
 * Description: Bloco de contato com endereço e informações institucionais
 * Categories: uenf-patterns, contact
 * Keywords: contato, endereco, telefone, email
 * Block Types: core/group
 * Post Types: page
 * Viewport Width: 1200
 */
?>

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|grande","right":"var:preset|spacing|grande","bottom":"var:preset|spacing|grande","left":"var:preset|spacing|grande"}},"border":{"radius":"8px"}},"backgroundColor":"fundo-claro","layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group has-fundo-claro-background-color has-background"
    style="border-radius:8px;padding-top:var(--wp--preset--spacing--grande);padding-right:var(--wp--preset--spacing--grande);padding-bottom:var(--wp--preset--spacing--grande);padding-left:var(--wp--preset--spacing--grande)">

    <!-- wp:columns {"verticalAlignment":"center"} -->
    <div class="wp-block-columns are-vertically-aligned-center">
        <!-- wp:column {"width":"100%"} -->
        <div class="wp-block-column" style="flex-basis:100%">
            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center"><strong>Endereço:</strong><br>Av. Alberto Lamego, 2000 - Parque
                Califórnia<br>Campos dos Goytacazes - RJ, 28013-602</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center"><strong>Telefone:</strong><br>(22) 2739-7000</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center"><strong>Email:</strong><br>reitoria@uenf.br</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
    <div class="wp-block-buttons">
        <!-- wp:button {"backgroundColor":"primaria","textColor":"fundo-claro"} -->
        <div class="wp-block-button"><a
                class="wp-block-button__link has-fundo-claro-color has-primaria-background-color has-text-color has-background"
                href="mailto:reitoria@uenf.br">Enviar Email</a></div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->