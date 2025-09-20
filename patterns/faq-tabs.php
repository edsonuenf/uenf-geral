<?php
/**
 * Title: FAQ Tabs
 * Slug: uenf-geral/faq-tabs
 * Description: FAQ organizado em abas por categoria com navegação intuitiva
 * Categories: uenf-faq, text
 * Keywords: faq, perguntas, tabs, abas, categorias
 * Block Types: core/group, core/heading
 * Post Types: page, post
 * Viewport Width: 1400
 */
?>

<!-- wp:group {"metadata":{"name":"FAQ Tabs"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}}},"layout":{"type":"constrained","contentSize":"1000px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--20)">
    <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
    <h2 class="wp-block-heading has-text-align-center has-x-large-font-size" style="margin-bottom:var(--wp--preset--spacing--40)">Perguntas Frequentes</h2>
    <!-- /wp:heading -->

    <!-- wp:group {"metadata":{"name":"FAQ Tabs Container"},"className":"faq-tabs-container","layout":{"type":"default"}} -->
    <div class="wp-block-group faq-tabs-container">
        <!-- wp:group {"metadata":{"name":"Tab Navigation"},"className":"faq-tabs-nav","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}},"border":{"bottom":{"width":"1px","color":"var:preset|color|contrast-3"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
        <div class="wp-block-group faq-tabs-nav" style="border-bottom-color:var(--wp--preset--color--contrast-3);border-bottom-width:1px;margin-bottom:var(--wp--preset--spacing--30)">
            <!-- wp:button {"metadata":{"name":"Tab Geral"},"className":"faq-tab-button active","style":{"border":{"radius":"0px","bottom":{"width":"2px","color":"var:preset|color|primary"}},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}}},"backgroundColor":"transparent","textColor":"primary"} -->
            <div class="wp-block-button faq-tab-button active"><a class="wp-block-button__link has-primary-color has-transparent-background-color has-text-color has-background" style="border-radius:0px;border-bottom-color:var(--wp--preset--color--primary);border-bottom-width:2px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--20)">Geral</a></div>
            <!-- /wp:button -->

            <!-- wp:button {"metadata":{"name":"Tab Serviços"},"className":"faq-tab-button","style":{"border":{"radius":"0px"},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}}},"backgroundColor":"transparent","textColor":"contrast"} -->
            <div class="wp-block-button faq-tab-button"><a class="wp-block-button__link has-contrast-color has-transparent-background-color has-text-color has-background" style="border-radius:0px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--20)">Serviços</a></div>
            <!-- /wp:button -->

            <!-- wp:button {"metadata":{"name":"Tab Suporte"},"className":"faq-tab-button","style":{"border":{"radius":"0px"},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}}},"backgroundColor":"transparent","textColor":"contrast"} -->
            <div class="wp-block-button faq-tab-button"><a class="wp-block-button__link has-contrast-color has-transparent-background-color has-text-color has-background" style="border-radius:0px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--20)">Suporte</a></div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"metadata":{"name":"Tab Content Geral"},"className":"faq-tab-content active","layout":{"type":"default"}} -->
        <div class="wp-block-group faq-tab-content active">
            <!-- wp:details {"summary":"Como posso entrar em contato?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"base-2"} -->
            <details class="wp-block-details has-base-2-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
                <summary>Como posso entrar em contato?</summary>
                <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
                <p style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">Você pode entrar em contato conosco através do formulário de contato, email ou telefone.</p>
                <!-- /wp:paragraph -->
            </details>
            <!-- /wp:details -->

            <!-- wp:details {"summary":"Quais são os horários de funcionamento?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"base-2"} -->
            <details class="wp-block-details has-base-2-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
                <summary>Quais são os horários de funcionamento?</summary>
                <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
                <p style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">Funcionamos de segunda a sexta-feira, das 9h às 18h. Aos sábados, das 9h às 12h.</p>
                <!-- /wp:paragraph -->
            </details>
            <!-- /wp:details -->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"metadata":{"name":"Tab Content Serviços"},"className":"faq-tab-content","style":{"display":"none"},"layout":{"type":"default"}} -->
        <div class="wp-block-group faq-tab-content" style="display:none">
            <!-- wp:details {"summary":"Que tipos de serviços vocês oferecem?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"base-2"} -->
            <details class="wp-block-details has-base-2-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
                <summary>Que tipos de serviços vocês oferecem?</summary>
                <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
                <p style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">Oferecemos desenvolvimento web, design gráfico, consultoria digital e manutenção de sites.</p>
                <!-- /wp:paragraph -->
            </details>
            <!-- /wp:details -->

            <!-- wp:details {"summary":"Como funciona o processo de trabalho?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"base-2"} -->
            <details class="wp-block-details has-base-2-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
                <summary>Como funciona o processo de trabalho?</summary>
                <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
                <p style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">Nosso processo é dividido em etapas: análise, proposta, desenvolvimento, testes e entrega.</p>
                <!-- /wp:paragraph -->
            </details>
            <!-- /wp:details -->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"metadata":{"name":"Tab Content Suporte"},"className":"faq-tab-content","style":{"display":"none"},"layout":{"type":"default"}} -->
        <div class="wp-block-group faq-tab-content" style="display:none">
            <!-- wp:details {"summary":"Oferecem suporte pós-projeto?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"base-2"} -->
            <details class="wp-block-details has-base-2-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
                <summary>Oferecem suporte pós-projeto?</summary>
                <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
                <p style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">Sim, oferecemos suporte técnico por 30 dias após a entrega do projeto.</p>
                <!-- /wp:paragraph -->
            </details>
            <!-- /wp:details -->

            <!-- wp:details {"summary":"Como solicitar suporte técnico?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"base-2"} -->
            <details class="wp-block-details has-base-2-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
                <summary>Como solicitar suporte técnico?</summary>
                <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
                <p style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">Entre em contato através do nosso sistema de tickets ou email de suporte.</p>
                <!-- /wp:paragraph -->
            </details>
            <!-- /wp:details -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->