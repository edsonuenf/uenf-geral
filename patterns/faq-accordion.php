<?php
/**
 * Title: FAQ Accordion
 * Slug: uenf-geral/faq-accordion
 * Description: Seção FAQ em formato accordion expansível com conteúdo editável
 * Categories: uenf-faq, text
 * Keywords: faq, perguntas, accordion, expansível
 * Block Types: core/group, core/heading
 * Post Types: page, post
 * Viewport Width: 1400
 */
?>

<!-- wp:group {"metadata":{"name":"FAQ Accordion"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}}},"layout":{"type":"constrained","contentSize":"800px"}} -->
<div class="wp-block-group"
    style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--20)">
    <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
    <h2 class="wp-block-heading has-text-align-center has-x-large-font-size"
        style="margin-bottom:var(--wp--preset--spacing--40)">Perguntas Frequentes</h2>
    <!-- /wp:heading -->

    <!-- wp:group {"metadata":{"name":"FAQ Items"},"className":"faq-accordion-container","layout":{"type":"default"}} -->
    <div class="wp-block-group faq-accordion-container">
        <!-- wp:details {"summary":"Como posso entrar em contato?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"fundo-claro"} -->
        <details class="wp-block-details has-fundo-claro-background-color has-background"
            style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
            <summary>Como posso entrar em contato?</summary>
            <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
            <p
                style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">
                Você pode entrar em contato conosco através do formulário de contato, email ou telefone. Estamos
                disponíveis de segunda a sexta, das 9h às 18h.</p>
            <!-- /wp:paragraph -->
        </details>
        <!-- /wp:details -->

        <!-- wp:details {"summary":"Quais são os horários de funcionamento?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"fundo-claro"} -->
        <details class="wp-block-details has-fundo-claro-background-color has-background"
            style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
            <summary>Quais são os horários de funcionamento?</summary>
            <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
            <p
                style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">
                Funcionamos de segunda a sexta-feira, das 9h às 18h. Aos sábados, das 9h às 12h. Domingos e feriados
                estamos fechados.</p>
            <!-- /wp:paragraph -->
        </details>
        <!-- /wp:details -->

        <!-- wp:details {"summary":"Como funciona o processo de trabalho?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"fundo-claro"} -->
        <details class="wp-block-details has-fundo-claro-background-color has-background"
            style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
            <summary>Como funciona o processo de trabalho?</summary>
            <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
            <p
                style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">
                Nosso processo é dividido em etapas: análise inicial, proposta, desenvolvimento, testes e entrega.
                Mantemos comunicação constante durante todo o projeto.</p>
            <!-- /wp:paragraph -->
        </details>
        <!-- /wp:details -->

        <!-- wp:details {"summary":"Oferecem suporte pós-projeto?","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"8px"}},"backgroundColor":"fundo-claro"} -->
        <details class="wp-block-details has-fundo-claro-background-color has-background"
            style="margin-bottom:var(--wp--preset--spacing--20);border-radius:8px">
            <summary>Oferecem suporte pós-projeto?</summary>
            <!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
            <p
                style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">
                Sim, oferecemos suporte técnico por 30 dias após a entrega do projeto. Também temos planos de manutenção
                mensal para atualizações e melhorias contínuas.</p>
            <!-- /wp:paragraph -->
        </details>
        <!-- /wp:details -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->