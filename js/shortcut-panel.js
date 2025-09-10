/**
 * Painel de Atalhos - JavaScript
 * Componente de navegação rápida para o site da UENF
 * Versão: 1.1.0 - Atualizado para usar container dedicado
 */

(function($) {
    'use strict';

    // Verifica se o jQuery está disponível
    if (typeof jQuery === 'undefined') {
        console.warn('jQuery não está disponível. O painel de atalhos requer jQuery.');
        return;
    }

    // Configurações padrão
    const defaults = {
        containerSelector: '#uenf-shortcut-panel-container',
        panelSelector: '.shortcut-panel',
        iconSelector: '.shortcut-icon',
        closeButtonSelector: '.close-panel',
        expandedClass: 'expanded',
        animationDuration: 300,
        enableSmoothScroll: true
    };

    // Classe principal do painel de atalhos
    class ShortcutPanel {
        constructor(options = {}) {
            this.settings = { ...defaults, ...options };
            this.container = $(this.settings.containerSelector);
            this.panel = this.container.find(this.settings.panelSelector);
            this.icon = this.container.find(this.settings.iconSelector);
            this.closeButton = this.container.find(this.settings.closeButtonSelector);
            this.isOpen = false;
            
            // Inicializa o painel quando o DOM estiver pronto
            $(() => this.init());
        }

        // Inicializa o painel
        init() {
            // Verifica se os elementos necessários existem
            if (!this.container.length || !this.panel.length || !this.icon.length) {
                console.warn('Elementos do painel de atalhos não encontrados.');
                return;
            }

            // Garante que o container esteja no final do body
            this.moveContainerToBody();
            
            // Adiciona eventos
            this.addEvents();
            
            // Ativa o painel
            this.enable();
            
            // log removido
        }
        
        // Move o container para o final do body se necessário
        moveContainerToBody() {
            if (this.container.parent('body').length === 0) {
                this.container.appendTo('body');
            }
        }

        // Adiciona eventos
        addEvents() {
            const self = this;
            
            // Abre/fecha o painel ao clicar no ícone
            this.icon.off('click.shortcut').on('click.shortcut', function(e) {
                e.preventDefault();
                e.stopPropagation();
                self.toggle();
            });

            // Fecha o painel ao clicar no botão de fechar
            this.closeButton.off('click.shortcut').on('click.shortcut', function(e) {
                e.preventDefault();
                self.close();
            });

            // Fecha o painel ao clicar fora dele
            $(document).off('click.shortcut').on('click.shortcut', function(e) {
                if (self.isOpen && 
                    !$(e.target).closest(self.settings.panelSelector).length && 
                    !$(e.target).closest(self.settings.iconSelector).length) {
                    self.close();
                }
            });

            // Previne fechamento ao clicar dentro do painel
            this.panel.off('click.shortcut').on('click.shortcut', function(e) {
                e.stopPropagation();
            });

            // Adiciona rolagem suave para links internos
            if (this.settings.enableSmoothScroll) {
                $(document).off('click.shortcut-smooth').on('click.shortcut-smooth', 'a[data-scroll="smooth"]', function(e) {
                    const $link = $(this);
                    const target = $link.attr('href');
                    
                    if (target && target !== '#' && $(target).length) {
                        e.preventDefault();
                        
                        // Fecha o painel antes de rolar
                        self.close();
                        
                        // Rola suavemente até o alvo
                        $('html, body').animate({
                            scrollTop: $(target).offset().top - 100
                        }, 800, 'swing');
                    }
                });
            }

            // Fecha o painel ao pressionar a tecla ESC
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    e.preventDefault();
                    this.close();
                }
            });
        }

        // Abre o painel
        open() {
            if (this.isOpen) return;
            
            // Adiciona classe ao body para estilização
            $('body').addClass('shortcut-panel-open');
            
            // Expande o container usando largura
            this.container.addClass(this.settings.expandedClass);
            
            // Atualiza o estado
            this.isOpen = true;
            
            // Desativa a rolagem da página quando o painel está aberto
            $('body').css('overflow', 'hidden');
            
            // Foca no botão de fechar para acessibilidade
            this.closeButton.attr('tabindex', '0').focus();
            
            // Dispara evento personalizado
            $(document).trigger('shortcutPanel:opened');
            
            // log removido
        }

        // Fecha o painel
        close() {
            if (!this.isOpen) return;
            
            // Remove a classe do body
            $('body').removeClass('shortcut-panel-open');
            
            // Retrai o container removendo a classe expanded
            this.container.removeClass(this.settings.expandedClass);
            
            // Atualiza o estado
            this.isOpen = false;
            
            // Reativa a rolagem da página
            $('body').css('overflow', '');
            
            // Foca no ícone para acessibilidade
            this.icon.attr('tabindex', '-1');
            this.icon.focus();
            
            // Dispara evento personalizado
            $(document).trigger('shortcutPanel:closed');
            
            // log removido
        }

        // Alterna entre abrir e fechar o painel
        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        }

        // Desativa o painel temporariamente
        disable() {
            this.close();
            this.icon.hide();
            this.panel.hide();
            
            // Dispara evento personalizado
            $(document).trigger('shortcutPanel:disabled');
            
            // log removido
        }

        // Reativa o painel
        enable() {
            this.icon.show();
            this.panel.show();
            
            // Dispara evento personalizado
            $(document).trigger('shortcutPanel:enabled');
            
            // log removido
        }
    }

    // Inicializa o painel quando o DOM estiver pronto
    $(document).ready(function() {
        // Cria uma instância do painel
        const shortcutPanel = new ShortcutPanel();
        
        // Expõe a instância globalmente para uso futuro
        window.shortcutPanel = shortcutPanel;
        
    // log removido
    });

})(jQuery);
