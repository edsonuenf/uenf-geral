<?php
/**
 * Title: Pricing Table
 * Slug: uenf-geral/pricing-table
 * Description: Tabela de preços responsiva com planos e recursos destacados
 * Categories: uenf-pricing, call-to-action
 * Keywords: pricing, preços, planos, tabela, assinatura
 * Block Types: core/group, core/columns
 * Post Types: page, post
 * Viewport Width: 1400
 */
?>

<!-- wp:group {"metadata":{"name":"Pricing Table"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--20)">
    <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|10"}}}} -->
    <h2 class="wp-block-heading has-text-align-center has-x-large-font-size" style="margin-bottom:var(--wp--preset--spacing--10)">Escolha Seu Plano</h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"textColor":"contrast-2","fontSize":"medium"} -->
    <p class="has-text-align-center has-contrast-2-color has-text-color has-medium-font-size" style="margin-bottom:var(--wp--preset--spacing--50)">Planos flexíveis para atender suas necessidades</p>
    <!-- /wp:paragraph -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
    <div class="wp-block-columns">
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"metadata":{"name":"Plano Básico"},"style":{"border":{"radius":"12px","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"borderColor":"contrast-3","backgroundColor":"base-2","layout":{"type":"default"}} -->
            <div class="wp-block-group has-border-color has-contrast-3-border-color has-base-2-background-color has-background" style="border-width:1px;border-radius:12px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--30)">
                <!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|10"}}}} -->
                <h3 class="wp-block-heading has-text-align-center has-large-font-size" style="margin-bottom:var(--wp--preset--spacing--10)">Básico</h3>
                <!-- /wp:heading -->

                <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--30)">
                    <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"2.5rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"primary"} -->
                    <p class="has-text-align-center has-primary-color has-text-color" style="margin-bottom:0;font-size:2.5rem;font-weight:700">R$ 29</p>
                    <!-- /wp:paragraph -->

                    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"0"}}},"textColor":"contrast-2","fontSize":"small"} -->
                    <p class="has-text-align-center has-contrast-2-color has-text-color has-small-font-size" style="margin-top:0">/mês</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:list {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"className":"pricing-features"} -->
                <ul class="pricing-features" style="margin-bottom:var(--wp--preset--spacing--30)">
                    <!-- wp:list-item -->
                    <li>✓ Até 5 páginas</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ Design responsivo</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ Suporte por email</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ SSL gratuito</li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->

                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                <div class="wp-block-buttons">
                    <!-- wp:button {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"backgroundColor":"contrast","textColor":"base","width":100} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-base-color has-contrast-background-color has-text-color has-background" style="border-radius:8px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--30)">Escolher Plano</a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"metadata":{"name":"Plano Profissional"},"style":{"border":{"radius":"12px","width":"2px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"borderColor":"primary","backgroundColor":"base-2","layout":{"type":"default"}} -->
            <div class="wp-block-group has-border-color has-primary-border-color has-base-2-background-color has-background" style="border-width:2px;border-radius:12px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--30)">
                <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|10"}},"border":{"radius":"20px"},"typography":{"fontSize":"0.8rem","fontWeight":"600"}},"backgroundColor":"primary","textColor":"base"} -->
                <p class="has-text-align-center has-base-color has-primary-background-color has-text-color has-background" style="border-radius:20px;margin-bottom:var(--wp--preset--spacing--10);font-size:0.8rem;font-weight:600">MAIS POPULAR</p>
                <!-- /wp:paragraph -->

                <!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|10"}}}} -->
                <h3 class="wp-block-heading has-text-align-center has-large-font-size" style="margin-bottom:var(--wp--preset--spacing--10)">Profissional</h3>
                <!-- /wp:heading -->

                <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--30)">
                    <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"2.5rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"primary"} -->
                    <p class="has-text-align-center has-primary-color has-text-color" style="margin-bottom:0;font-size:2.5rem;font-weight:700">R$ 79</p>
                    <!-- /wp:paragraph -->

                    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"0"}}},"textColor":"contrast-2","fontSize":"small"} -->
                    <p class="has-text-align-center has-contrast-2-color has-text-color has-small-font-size" style="margin-top:0">/mês</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:list {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"className":"pricing-features"} -->
                <ul class="pricing-features" style="margin-bottom:var(--wp--preset--spacing--30)">
                    <!-- wp:list-item -->
                    <li>✓ Até 15 páginas</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ Design responsivo</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ Suporte prioritário</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ SSL gratuito</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ Analytics avançado</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ SEO otimizado</li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->

                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                <div class="wp-block-buttons">
                    <!-- wp:button {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"backgroundColor":"primary","textColor":"base","width":100} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-base-color has-primary-background-color has-text-color has-background" style="border-radius:8px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--30)">Escolher Plano</a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"metadata":{"name":"Plano Enterprise"},"style":{"border":{"radius":"12px","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"borderColor":"contrast-3","backgroundColor":"base-2","layout":{"type":"default"}} -->
            <div class="wp-block-group has-border-color has-contrast-3-border-color has-base-2-background-color has-background" style="border-width:1px;border-radius:12px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--30)">
                <!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|10"}}}} -->
                <h3 class="wp-block-heading has-text-align-center has-large-font-size" style="margin-bottom:var(--wp--preset--spacing--10)">Enterprise</h3>
                <!-- /wp:heading -->

                <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--30)">
                    <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"2.5rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"0"}}},"textColor":"primary"} -->
                    <p class="has-text-align-center has-primary-color has-text-color" style="margin-bottom:0;font-size:2.5rem;font-weight:700">R$ 149</p>
                    <!-- /wp:paragraph -->

                    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"0"}}},"textColor":"contrast-2","fontSize":"small"} -->
                    <p class="has-text-align-center has-contrast-2-color has-text-color has-small-font-size" style="margin-top:0">/mês</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:list {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"className":"pricing-features"} -->
                <ul class="pricing-features" style="margin-bottom:var(--wp--preset--spacing--30)">
                    <!-- wp:list-item -->
                    <li>✓ Páginas ilimitadas</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ Design responsivo</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ Suporte 24/7</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ SSL gratuito</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ Analytics avançado</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ SEO otimizado</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ Backup automático</li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li>✓ CDN global</li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->

                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                <div class="wp-block-buttons">
                    <!-- wp:button {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"backgroundColor":"contrast","textColor":"base","width":100} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-base-color has-contrast-background-color has-text-color has-background" style="border-radius:8px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--30)">Escolher Plano</a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->