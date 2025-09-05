/**
 * Sistema de Tooltips do Customizer CCT
 * 
 * Fornece ajuda contextual através de tooltips informativos
 * para todos os controles do customizer.
 * 
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Classe principal de tooltips
     */
    class CCTCustomizerTooltips {
        
        constructor() {
            this.tooltips = {};
            this.init();
        }
        
        /**
         * Inicializa o sistema de tooltips
         */
        init() {
            this.setupTooltipStyles();
            this.defineTooltipContent();
            this.bindTooltipEvents();
            this.createTooltipElements();
            console.log('CCT Customizer Tooltips: Initialized');
        }
        
        /**
         * Adiciona estilos CSS para tooltips
         */
        setupTooltipStyles() {
            const styles = `
                <style id="cct-tooltip-styles">
                    .cct-tooltip {
                        position: absolute;
                        background: #333;
                        color: #fff;
                        padding: 8px 12px;
                        border-radius: 4px;
                        font-size: 12px;
                        line-height: 1.4;
                        max-width: 250px;
                        z-index: 999999;
                        opacity: 0;
                        visibility: hidden;
                        transition: opacity 0.3s, visibility 0.3s;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                        word-wrap: break-word;
                    }
                    
                    .cct-tooltip.show {
                        opacity: 1;
                        visibility: visible;
                    }
                    
                    .cct-tooltip::before {
                        content: '';
                        position: absolute;
                        top: -5px;
                        left: 50%;
                        transform: translateX(-50%);
                        border-left: 5px solid transparent;
                        border-right: 5px solid transparent;
                        border-bottom: 5px solid #333;
                    }
                    
                    .cct-tooltip.bottom::before {
                        top: auto;
                        bottom: -5px;
                        border-bottom: none;
                        border-top: 5px solid #333;
                    }
                    
                    .cct-tooltip.left::before {
                        top: 50%;
                        left: -5px;
                        transform: translateY(-50%);
                        border-left: none;
                        border-right: 5px solid #333;
                        border-top: 5px solid transparent;
                        border-bottom: 5px solid transparent;
                    }
                    
                    .cct-tooltip.right::before {
                        top: 50%;
                        left: auto;
                        right: -5px;
                        transform: translateY(-50%);
                        border-right: none;
                        border-left: 5px solid #333;
                        border-top: 5px solid transparent;
                        border-bottom: 5px solid transparent;
                    }
                    
                    .cct-help-icon {
                        display: inline-block;
                        width: 16px;
                        height: 16px;
                        background: #666;
                        color: #fff;
                        border-radius: 50%;
                        text-align: center;
                        line-height: 16px;
                        font-size: 11px;
                        font-weight: bold;
                        margin-left: 5px;
                        cursor: help;
                        vertical-align: middle;
                    }
                    
                    .cct-help-icon:hover {
                        background: #0073aa;
                    }
                    
                    .customize-control:hover .cct-help-icon {
                        background: #0073aa;
                    }
                    
                    .cct-tooltip-content {
                        margin-bottom: 8px;
                    }
                    
                    .cct-tooltip-content:last-child {
                        margin-bottom: 0;
                    }
                    
                    .cct-tooltip-title {
                        font-weight: bold;
                        margin-bottom: 4px;
                        color: #fff;
                    }
                    
                    .cct-tooltip-description {
                        color: #ddd;
                        margin-bottom: 6px;
                    }
                    
                    .cct-tooltip-example {
                        color: #a8d8a8;
                        font-style: italic;
                        font-size: 11px;
                    }
                    
                    .cct-tooltip-tip {
                        color: #ffd700;
                        font-size: 11px;
                        border-top: 1px solid #555;
                        padding-top: 6px;
                        margin-top: 6px;
                    }
                </style>
            `;
            
            $('head').append(styles);
        }
        
        /**
         * Define o conteúdo dos tooltips para cada controle
         */
        defineTooltipContent() {
            this.tooltips = {
                'cct_menu_style': {
                    title: 'Estilo do Menu',
                    description: 'Escolha entre diferentes estilos visuais para o menu de navegação.',
                    example: 'Moderno: gradientes e efeitos | Clássico: cores sólidas | Minimalista: transparente',
                    tip: 'O estilo clássico é recomendado para sites corporativos.'
                },
                
                'cct_menu_show_hierarchy_icons': {
                    title: 'Ícones de Hierarquia',
                    description: 'Mostra setas e símbolos que indicam submenus e hierarquia de navegação.',
                    example: 'Ativado: ▶ Página Principal | Desativado: Página Principal',
                    tip: 'Melhora a usabilidade em menus com muitos níveis.'
                },
                
                'cct_shortcut_button_bg': {
                    title: 'Cor do Botão de Atalho',
                    description: 'Define a cor de fundo do botão que abre o painel de atalhos.',
                    example: 'Recomendado: cores que contrastem com o fundo da página',
                    tip: 'Use cores da identidade visual do site para consistência.'
                },
                
                'cct_shortcut_panel_width': {
                    title: 'Largura do Painel',
                    description: 'Controla a largura do painel de atalhos quando aberto.',
                    example: 'Valores aceitos: 300px, 25%, 20vw, 15em',
                    tip: 'Larguras entre 250px e 400px oferecem melhor usabilidade.'
                },
                
                'cct_shortcut_panel_bg': {
                    title: 'Fundo do Painel',
                    description: 'Cor de fundo do painel de atalhos.',
                    example: 'Sugestão: cores escuras para melhor legibilidade',
                    tip: 'Certifique-se de que há contraste suficiente com o texto.'
                },
                
                'cct_export_settings': {
                    title: 'Exportar Configurações',
                    description: 'Baixa um arquivo com todas as configurações atuais do customizer.',
                    example: 'Arquivo gerado: cct-customizer-backup-YYYY-MM-DD.txt',
                    tip: 'Faça backup antes de grandes alterações no tema.'
                },
                
                'cct_import_settings': {
                    title: 'Importar Configurações',
                    description: 'Cole aqui o conteúdo de um arquivo de backup para restaurar configurações.',
                    example: 'Abra o arquivo .txt do backup e cole todo o conteúdo',
                    tip: 'Sempre faça backup das configurações atuais antes de importar.'
                },
                
                'cct_reset_settings': {
                    title: 'Redefinir Configurações',
                    description: 'Restaura todas as configurações do customizer para os valores padrão do tema.',
                    example: 'ATENÇÃO: Esta ação não pode ser desfeita!',
                    tip: 'Exporte suas configurações antes de usar esta função.'
                }
            };
        }
        
        /**
         * Vincula eventos de tooltip aos controles
         */
        bindTooltipEvents() {
            // Aguardar carregamento dos controles
            setTimeout(() => {
                this.addHelpIcons();
                this.bindHoverEvents();
            }, 1000);
        }
        
        /**
         * Adiciona ícones de ajuda aos controles
         */
        addHelpIcons() {
            Object.keys(this.tooltips).forEach(controlId => {
                const $control = $(`#customize-control-${controlId}`);
                const $label = $control.find('.customize-control-title');
                
                if ($label.length && !$label.find('.cct-help-icon').length) {
                    $label.append('<span class="cct-help-icon" data-tooltip="' + controlId + '">?</span>');
                }
            });
        }
        
        /**
         * Vincula eventos de hover
         */
        bindHoverEvents() {
            $(document).on('mouseenter', '.cct-help-icon', (e) => {
                const tooltipId = $(e.target).data('tooltip');
                this.showTooltip(e.target, tooltipId);
            });
            
            $(document).on('mouseleave', '.cct-help-icon', () => {
                this.hideTooltip();
            });
        }
        
        /**
         * Cria elementos de tooltip
         */
        createTooltipElements() {
            if ($('#cct-tooltip').length === 0) {
                $('body').append('<div id="cct-tooltip" class="cct-tooltip"></div>');
            }
        }
        
        /**
         * Mostra tooltip
         */
        showTooltip(element, tooltipId) {
            const tooltipData = this.tooltips[tooltipId];
            if (!tooltipData) return;
            
            const $tooltip = $('#cct-tooltip');
            const $element = $(element);
            
            // Construir conteúdo do tooltip
            let content = '';
            
            if (tooltipData.title) {
                content += `<div class="cct-tooltip-title">${tooltipData.title}</div>`;
            }
            
            if (tooltipData.description) {
                content += `<div class="cct-tooltip-description">${tooltipData.description}</div>`;
            }
            
            if (tooltipData.example) {
                content += `<div class="cct-tooltip-example">Exemplo: ${tooltipData.example}</div>`;
            }
            
            if (tooltipData.tip) {
                content += `<div class="cct-tooltip-tip">💡 Dica: ${tooltipData.tip}</div>`;
            }
            
            $tooltip.html(content);
            
            // Posicionar tooltip
            this.positionTooltip($tooltip, $element);
            
            // Mostrar tooltip
            $tooltip.addClass('show');
        }
        
        /**
         * Posiciona o tooltip
         */
        positionTooltip($tooltip, $element) {
            const elementRect = $element[0].getBoundingClientRect();
            const tooltipWidth = $tooltip.outerWidth();
            const tooltipHeight = $tooltip.outerHeight();
            const windowWidth = $(window).width();
            const windowHeight = $(window).height();
            
            let top = elementRect.top - tooltipHeight - 10;
            let left = elementRect.left + (elementRect.width / 2) - (tooltipWidth / 2);
            let position = 'top';
            
            // Verificar se cabe acima
            if (top < 0) {
                top = elementRect.bottom + 10;
                position = 'bottom';
            }
            
            // Verificar se cabe horizontalmente
            if (left < 10) {
                left = 10;
            } else if (left + tooltipWidth > windowWidth - 10) {
                left = windowWidth - tooltipWidth - 10;
            }
            
            // Verificar se cabe na tela
            if (top + tooltipHeight > windowHeight - 10) {
                top = windowHeight - tooltipHeight - 10;
            }
            
            $tooltip
                .removeClass('top bottom left right')
                .addClass(position)
                .css({
                    top: top + 'px',
                    left: left + 'px'
                });
        }
        
        /**
         * Esconde tooltip
         */
        hideTooltip() {
            $('#cct-tooltip').removeClass('show');
        }
        
        /**
         * Adiciona tooltip personalizado
         */
        addCustomTooltip(controlId, tooltipData) {
            this.tooltips[controlId] = tooltipData;
            
            // Adicionar ícone se o controle já existir
            const $control = $(`#customize-control-${controlId}`);
            if ($control.length) {
                const $label = $control.find('.customize-control-title');
                if ($label.length && !$label.find('.cct-help-icon').length) {
                    $label.append('<span class="cct-help-icon" data-tooltip="' + controlId + '">?</span>');
                }
            }
        }
    }

    /**
     * Inicializar quando o customizer estiver pronto
     */
    $(document).ready(function() {
        if (typeof wp !== 'undefined' && wp.customize) {
            wp.customize.bind('ready', function() {
                window.cctTooltips = new CCTCustomizerTooltips();
            });
        } else {
            setTimeout(() => {
                window.cctTooltips = new CCTCustomizerTooltips();
            }, 1500);
        }
    });

})(jQuery);