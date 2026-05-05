/**
 * Sistema de Tooltips e Hints para o Customizer
 * 
 * Fornece documentação contextual e hints informativos
 * para melhorar a experiência do usuário no customizer.
 * 
 * @package UENF_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Configuração dos tooltips
    const tooltipConfig = {
        // Tipografia
        'typography_font_family': {
            title: '🎨 Família da Fonte',
            content: 'Escolha a fonte principal do seu site. Google Fonts são carregadas automaticamente para melhor performance.',
            tips: [
                'Ubuntu: Moderna e legível, ideal para sites institucionais',
                'Roboto: Clean e profissional, perfeita para conteúdo técnico',
                'Open Sans: Versátil e amigável, ótima para blogs',
                'Montserrat: Elegante e impactante, ideal para títulos'
            ]
        },
        'typography_body_size': {
            title: '📏 Tamanho da Fonte',
            content: 'Define o tamanho base do texto. Recomendamos entre 14px e 18px para melhor legibilidade.',
            tips: [
                '16px: Tamanho padrão recomendado pela W3C',
                '14px: Para sites com muito conteúdo',
                '18px: Para melhor acessibilidade e leitura'
            ]
        },
        'typography_body_weight': {
            title: '💪 Peso da Fonte',
            content: 'Controla a espessura do texto. Valores maiores tornam o texto mais destacado.',
            tips: [
                '300: Texto leve, ideal para citações',
                '400: Peso normal, padrão para corpo de texto',
                '600-700: Texto em destaque, ideal para títulos'
            ]
        },
        'typography_line_height': {
            title: '📐 Altura da Linha',
            content: 'Espaçamento entre linhas. Valores entre 1.4 e 1.8 melhoram a legibilidade.',
            tips: [
                '1.4: Texto compacto, economiza espaço',
                '1.6: Valor ideal para a maioria dos casos',
                '1.8: Melhor para textos longos e acessibilidade'
            ]
        },
        'typography_letter_spacing': {
            title: '🔤 Espaçamento entre Letras',
            content: 'Ajusta o espaço entre caracteres. Use com moderação para não prejudicar a leitura.',
            tips: [
                '0px: Espaçamento padrão da fonte',
                '0.5-1px: Sutil melhoria na legibilidade',
                '2px+: Apenas para títulos e destaques'
            ]
        },
        
        // Cores
        'colors_primary': {
            title: '🎨 Cor Primária',
            content: 'Cor principal do seu site. Será usada em botões, links e elementos de destaque.',
            tips: [
                'Escolha uma cor que represente sua marca',
                'Teste o contraste com texto branco e preto',
                'Considere a psicologia das cores para seu público'
            ]
        },
        
        // Layout
        'layout_container_width': {
            title: '📐 Largura do Container',
            content: 'Define a largura máxima do conteúdo. Valores entre 1200px e 1400px são ideais.',
            tips: [
                '1200px: Padrão, funciona bem na maioria dos casos',
                '1400px: Para sites com muito conteúdo visual',
                '100%: Layout fluido, se adapta a qualquer tela'
            ]
        },
        
        // Header
        'header_height': {
            title: '📏 Altura do Cabeçalho',
            content: 'Altura do cabeçalho do site. Considere o espaço necessário para logo e menu.',
            tips: [
                '60-80px: Cabeçalho compacto',
                '80-120px: Altura padrão recomendada',
                '120px+: Para logos grandes ou múltiplas linhas'
            ]
        }
    };
    
    // Documentação avançada para popups
    const advancedDocs = {
        'typography_section': {
            title: '📚 Guia Completo de Tipografia',
            sections: [
                {
                    title: 'Princípios Básicos',
                    content: [
                        'A tipografia é fundamental para a experiência do usuário',
                        'Escolha no máximo 2-3 famílias de fonte diferentes',
                        'Mantenha consistência nos tamanhos e pesos',
                        'Priorize sempre a legibilidade sobre o estilo'
                    ]
                },
                {
                    title: 'Combinações Recomendadas',
                    content: [
                        'Títulos: Montserrat Bold + Corpo: Open Sans Regular',
                        'Títulos: Oswald Medium + Corpo: Lato Regular',
                        'Títulos: Roboto Bold + Corpo: Roboto Regular',
                        'Títulos: Ubuntu Bold + Corpo: Ubuntu Regular'
                    ]
                },
                {
                    title: 'Acessibilidade',
                    content: [
                        'Tamanho mínimo: 14px para texto principal',
                        'Contraste mínimo: 4.5:1 para texto normal',
                        'Contraste mínimo: 3:1 para texto grande (18px+)',
                        'Evite texto em itálico para grandes blocos'
                    ]
                }
            ]
        },
        
        'colors_section': {
            title: '🎨 Guia de Cores e Branding',
            sections: [
                {
                    title: 'Teoria das Cores',
                    content: [
                        'Azul: Confiança, profissionalismo, tecnologia',
                        'Verde: Natureza, crescimento, saúde',
                        'Vermelho: Energia, urgência, paixão',
                        'Laranja: Criatividade, entusiasmo, amigável'
                    ]
                },
                {
                    title: 'Paleta de Cores',
                    content: [
                        'Cor primária: Identidade principal da marca',
                        'Cor secundária: Complementa a primária',
                        'Cores neutras: Cinzas para texto e fundos',
                        'Cores de ação: Para botões e CTAs'
                    ]
                }
            ]
        }
    };
    
    // Classe principal do sistema de tooltips
    class CustomizerTooltips {
        constructor() {
            this.init();
        }
        
        init() {
            this.createTooltipStyles();
            this.bindEvents();
            this.addTooltipsToControls();
            this.addHelpButtons();
        }
        
        createTooltipStyles() {
            const styles = `
                <style id="uenf-tooltip-styles">
                    .uenf-tooltip {
                        position: relative;
                        display: inline-block;
                        margin-left: 5px;
                        cursor: help;
                    }
                    
                    .uenf-tooltip-icon {
                        width: 16px;
                        height: 16px;
                        background: #0073aa;
                        color: white;
                        border-radius: 50%;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 11px;
                        font-weight: bold;
                        transition: all 0.3s ease;
                    }
                    
                    .uenf-tooltip-icon:hover {
                        background: #005a87;
                        transform: scale(1.1);
                    }
                    
                    .uenf-tooltip-content {
                        position: absolute;
                        bottom: 100%;
                        left: 50%;
                        transform: translateX(-50%);
                        background: #2c3338;
                        color: white;
                        padding: 15px;
                        border-radius: 8px;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                        width: 300px;
                        z-index: 999999;
                        opacity: 0;
                        visibility: hidden;
                        transition: all 0.3s ease;
                        margin-bottom: 10px;
                    }
                    
                    .uenf-tooltip:hover .uenf-tooltip-content {
                        opacity: 1;
                        visibility: visible;
                    }
                    
                    .uenf-tooltip-content::after {
                        content: '';
                        position: absolute;
                        top: 100%;
                        left: 50%;
                        transform: translateX(-50%);
                        border: 8px solid transparent;
                        border-top-color: #2c3338;
                    }
                    
                    .uenf-tooltip-title {
                        font-weight: bold;
                        margin-bottom: 8px;
                        font-size: 13px;
                        color: #00a0d2;
                    }
                    
                    .uenf-tooltip-text {
                        font-size: 12px;
                        line-height: 1.5;
                        margin-bottom: 10px;
                    }
                    
                    .uenf-tooltip-tips {
                        border-top: 1px solid #444;
                        padding-top: 10px;
                    }
                    
                    .uenf-tooltip-tip {
                        font-size: 11px;
                        margin-bottom: 5px;
                        opacity: 0.9;
                        padding-left: 10px;
                        position: relative;
                    }
                    
                    .uenf-tooltip-tip::before {
                        content: '💡';
                        position: absolute;
                        left: -5px;
                        font-size: 10px;
                    }
                    
                    .uenf-help-button {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        border: none;
                        padding: 8px 12px;
                        border-radius: 6px;
                        cursor: pointer;
                        font-size: 11px;
                        font-weight: 600;
                        margin: 10px 0;
                        transition: all 0.3s ease;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                    }
                    
                    .uenf-help-button:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                    }
                    
                    .uenf-popup-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0,0,0,0.7);
                        z-index: 9999999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        opacity: 0;
                        visibility: hidden;
                        transition: all 0.3s ease;
                    }
                    
                    .uenf-popup-overlay.active {
                        opacity: 1;
                        visibility: visible;
                    }
                    
                    .uenf-popup {
                        background: white;
                        border-radius: 12px;
                        padding: 30px;
                        max-width: 600px;
                        max-height: 80vh;
                        overflow-y: auto;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                        transform: scale(0.9);
                        transition: all 0.3s ease;
                    }
                    
                    .uenf-popup-overlay.active .uenf-popup {
                        transform: scale(1);
                    }
                    
                    .uenf-popup-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 20px;
                        padding-bottom: 15px;
                        border-bottom: 2px solid #f0f0f0;
                    }
                    
                    .uenf-popup-title {
                        font-size: 20px;
                        font-weight: bold;
                        color: #2c3338;
                        margin: 0;
                    }
                    
                    .uenf-popup-close {
                        background: #ff4757;
                        color: white;
                        border: none;
                        width: 30px;
                        height: 30px;
                        border-radius: 50%;
                        cursor: pointer;
                        font-size: 16px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: all 0.3s ease;
                    }
                    
                    .uenf-popup-close:hover {
                        background: #ff3742;
                        transform: scale(1.1);
                    }
                    
                    .uenf-popup-section {
                        margin-bottom: 25px;
                    }
                    
                    .uenf-popup-section-title {
                        font-size: 16px;
                        font-weight: 600;
                        color: #0073aa;
                        margin-bottom: 10px;
                        padding: 8px 12px;
                        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
                        border-radius: 6px;
                        border-left: 4px solid #0073aa;
                    }
                    
                    .uenf-popup-content {
                        line-height: 1.6;
                        color: #555;
                    }
                    
                    .uenf-popup-content ul {
                        margin: 10px 0;
                        padding-left: 20px;
                    }
                    
                    .uenf-popup-content li {
                        margin-bottom: 8px;
                        position: relative;
                    }
                    
                    .uenf-popup-content li::before {
                        content: '✨';
                        position: absolute;
                        left: -20px;
                        font-size: 12px;
                    }
                </style>
            `;
            
            $('head').append(styles);
        }
        
        bindEvents() {
            // Fechar popup ao clicar no overlay
            $(document).on('click', '.uenf-popup-overlay', (e) => {
                if (e.target === e.currentTarget) {
                    this.closePopup();
                }
            });
            
            // Fechar popup com ESC
            $(document).on('keydown', (e) => {
                if (e.keyCode === 27) {
                    this.closePopup();
                }
            });
        }
        
        addTooltipsToControls() {
            // Aguarda o customizer estar pronto
            wp.customize.bind('ready', () => {
                setTimeout(() => {
                    Object.keys(tooltipConfig).forEach(controlId => {
                        this.addTooltipToControl(controlId);
                    });
                }, 1000);
            });
        }
        
        addTooltipToControl(controlId) {
            const control = $(`#customize-control-${controlId}`);
            if (control.length && tooltipConfig[controlId]) {
                const config = tooltipConfig[controlId];
                const label = control.find('label, .customize-control-title').first();
                
                if (label.length) {
                    const tooltip = this.createTooltip(config);
                    label.append(tooltip);
                }
            }
        }
        
        createTooltip(config) {
            let tipsHtml = '';
            if (config.tips && config.tips.length > 0) {
                tipsHtml = `
                    <div class="uenf-tooltip-tips">
                        ${config.tips.map(tip => `<div class="uenf-tooltip-tip">${tip}</div>`).join('')}
                    </div>
                `;
            }
            
            return $(`
                <span class="uenf-tooltip">
                    <span class="uenf-tooltip-icon">?</span>
                    <div class="uenf-tooltip-content">
                        <div class="uenf-tooltip-title">${config.title}</div>
                        <div class="uenf-tooltip-text">${config.content}</div>
                        ${tipsHtml}
                    </div>
                </span>
            `);
        }
        
        addHelpButtons() {
            wp.customize.bind('ready', () => {
                setTimeout(() => {
                    // Adiciona botões de ajuda para seções específicas
                    Object.keys(advancedDocs).forEach(sectionId => {
                        this.addHelpButtonToSection(sectionId);
                    });
                }, 1500);
            });
        }
        
        addHelpButtonToSection(sectionId) {
            const section = $(`#accordion-section-${sectionId}`);
            if (section.length && advancedDocs[sectionId]) {
                const button = $(`
                    <button class="uenf-help-button" data-section="${sectionId}">
                        📚 Guia Completo
                    </button>
                `);
                
                button.on('click', (e) => {
                    e.preventDefault();
                    this.showAdvancedHelp(sectionId);
                });
                
                section.find('.accordion-section-content').prepend(button);
            }
        }
        
        showAdvancedHelp(sectionId) {
            const doc = advancedDocs[sectionId];
            if (!doc) return;
            
            const sectionsHtml = doc.sections.map(section => `
                <div class="uenf-popup-section">
                    <h3 class="uenf-popup-section-title">${section.title}</h3>
                    <div class="uenf-popup-content">
                        <ul>
                            ${section.content.map(item => `<li>${item}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `).join('');
            
            const popup = $(`
                <div class="uenf-popup-overlay">
                    <div class="uenf-popup">
                        <div class="uenf-popup-header">
                            <h2 class="uenf-popup-title">${doc.title}</h2>
                            <button class="uenf-popup-close">×</button>
                        </div>
                        <div class="uenf-popup-body">
                            ${sectionsHtml}
                        </div>
                    </div>
                </div>
            `);
            
            popup.find('.uenf-popup-close').on('click', () => {
                this.closePopup();
            });
            
            $('body').append(popup);
            
            setTimeout(() => {
                popup.addClass('active');
            }, 10);
        }
        
        closePopup() {
            $('.uenf-popup-overlay').removeClass('active');
            setTimeout(() => {
                $('.uenf-popup-overlay').remove();
            }, 300);
        }
    }
    
    // Inicializa o sistema quando o customizer estiver pronto
    $(document).ready(() => {
        if (typeof wp !== 'undefined' && wp.customize) {
            new CustomizerTooltips();
        }
    });
    
})(jQuery);
