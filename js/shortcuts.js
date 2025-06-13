/**
 * Shortcuts.js - Melhorias de acessibilidade e feedback visual para o painel de atalhos
 * Complementa o shortcut-panel.js sem duplicar funcionalidades
 */
(function($) {
    'use strict';

    // Espera o DOM estar pronto
    $(document).ready(function() {
        const $shortcutPanel = $('.shortcut-panel');
        const $shortcutItems = $('.shortcut-item');
        
        // Sai se não encontrar o painel
        if (!$shortcutPanel.length) return;

        // Adiciona atributos ARIA para melhorar a acessibilidade
        $shortcutItems.each(function() {
            const $item = $(this);
            $item.attr({
                'role': 'link',
                'tabindex': '0',
                'aria-label': $item.text().trim()
            });
        });

        // Adiciona estilos dinâmicos para feedback visual
        const dynamicStyles = `
            .shortcut-item {
                transition: transform 0.1s ease, opacity 0.1s ease;
            }
            .shortcut-item:focus {
                outline: 2px solid var(--shortcut-item-hover-text-color, #ffffff);
                outline-offset: -2px;
                position: relative;
            }
            .shortcut-item:active {
                transform: scale(0.98);
                opacity: 0.9;
            }
            /* Removida a regra que escondia os itens quando o painel não está ativo */`;

        // Adiciona os estilos dinâmicos
        $('<style>').text(dynamicStyles).appendTo('head');

        // Observa mudanças no painel para gerenciar o foco
        const panelObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    const isActive = $shortcutPanel.hasClass('active');
                    const $firstItem = $shortcutItems.first();
                    
                    if (isActive && $firstItem.length) {
                        // Foca no primeiro item após a animação
                        setTimeout(() => {
                            $firstItem.trigger('focus');
                        }, 300);
                    }
                }
            });
        });

        // Inicia a observação do painel
        panelObserver.observe($shortcutPanel[0], { 
            attributes: true,
            attributeFilter: ['class']
        });

        // Navegação por teclado aprimorada
        $shortcutItems.on('keydown', function(e) {
            const $current = $(this);
            const $items = $shortcutItems;
            const currentIndex = $items.index($current);
            let nextIndex;

            switch(e.key) {
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    const url = $current.attr('href');
                    if (url) window.location.href = url;
                    break;

                case 'ArrowDown':
                    e.preventDefault();
                    nextIndex = (currentIndex + 1) % $items.length;
                    $items.eq(nextIndex).trigger('focus');
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    nextIndex = (currentIndex - 1 + $items.length) % $items.length;
                    $items.eq(nextIndex).trigger('focus');
                    break;

                case 'Escape':
                    e.preventDefault();
                    $shortcutPanel.removeClass('active');
                    $('.shortcut-icon').trigger('focus');
                    break;
            }
        });
    });
})(jQuery);
