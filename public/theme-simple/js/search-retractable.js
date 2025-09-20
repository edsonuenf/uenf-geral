/**
 * BUSCA RETRÁTIL - CONTROLE DE EXPANSÃO/RETRAÇÃO
 * 
 * Este script controla a funcionalidade de busca retrátil,
 * permitindo expandir e retrair o formulário de busca.
 */

(function($) {
    'use strict';
    
    // Classe principal para gerenciar a busca retrátil
    class SearchRetractable {
        constructor() {
            this.container = null;
            this.toggleButton = null;
            this.form = null;
            this.closeButton = null;
            this.overlay = null;
            this.isExpanded = false;
            this.searchField = null;
            
            this.init();
        }
        
        init() {
            // Aguardar o DOM estar pronto
            $(document).ready(() => {
                this.setupElements();
                this.bindEvents();
                this.createOverlay();
            });
        }
        
        setupElements() {
            // Verificar versão inline primeiro
            this.container = $('.search-retractable-inline');
            this.isInline = this.container.length > 0;
            
            // Se não for inline, verificar versão flutuante
            if (!this.isInline) {
                this.container = $('.search-retractable-container');
            }
            
            if (this.container.length === 0) {
                return; // Não há busca retrátil na página
            }
            
            // Configurar elementos baseado na versão
            if (this.isInline) {
                this.toggleButton = this.container.find('.search-retractable-toggle-inline');
                this.form = this.container.find('.search-retractable-form-inline');
                this.closeButton = this.container.find('.search-retractable-close-inline');
            } else {
                this.toggleButton = this.container.find('.search-retractable-toggle');
                this.form = this.container.find('.search-retractable-form');
                this.closeButton = this.container.find('.search-retractable-close');
            }
            
            this.searchField = this.form.find('.search-field');
            
            // Debug
            console.log('Search Retractable: Elementos configurados (' + (this.isInline ? 'inline' : 'flutuante') + ')');
        }
        
        bindEvents() {
            if (!this.container.length) return;
            
            // Evento do botão toggle
            this.toggleButton.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggle();
            });
            
            // Evento do botão fechar
            this.closeButton.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.close();
            });
            
            // Fechar ao clicar no overlay
            $(document).on('click', '.search-retractable-overlay', () => {
                this.close();
            });
            
            // Fechar com ESC
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && this.isExpanded) {
                    this.close();
                }
            });
            
            // Prevenir fechamento ao clicar dentro do formulário
            this.form.on('click', (e) => {
                e.stopPropagation();
            });
            
            // Auto-focus no campo de busca quando expandir
            this.form.on('transitionend', () => {
                if (this.isExpanded && this.searchField.length) {
                    setTimeout(() => {
                        this.searchField.focus();
                    }, 100);
                }
            });
        }
        
        createOverlay() {
            // Overlay apenas para versão flutuante
            if (!this.isInline) {
                if ($('.search-retractable-overlay').length === 0) {
                    this.overlay = $('<div class="search-retractable-overlay"></div>');
                    $('body').append(this.overlay);
                } else {
                    this.overlay = $('.search-retractable-overlay');
                }
            }
        }
        
        toggle() {
            if (this.isExpanded) {
                this.close();
            } else {
                this.open();
            }
        }
        
        open() {
            if (this.isExpanded) return;
            
            console.log('Search Retractable: Abrindo (' + (this.isInline ? 'inline' : 'flutuante') + ')');
            
            // Mostrar overlay apenas para versão flutuante
            if (!this.isInline && this.overlay) {
                this.overlay.addClass('active');
            }
            
            // Mostrar formulário com animação
            this.form.show();
            
            // Forçar reflow para garantir que o display: block seja aplicado
            this.form[0].offsetHeight;
            
            // Adicionar classes de animação
            if (this.isInline) {
                this.form.addClass('expanded');
            } else {
                this.form.addClass('expanded bounce-in');
                this.form.removeClass('bounce-out');
            }
            
            // Atualizar estado
            this.isExpanded = true;
            
            // Atualizar aria-label
            this.toggleButton.attr('aria-label', 'Fechar busca');
            
            // Adicionar classe ao body para possíveis ajustes de CSS
            $('body').addClass('search-retractable-open');
            
            // Evento personalizado
            $(document).trigger('searchRetractableOpened');
        }
        
        close() {
            if (!this.isExpanded) return;
            
            console.log('Search Retractable: Fechando (' + (this.isInline ? 'inline' : 'flutuante') + ')');
            
            // Esconder overlay apenas para versão flutuante
            if (!this.isInline && this.overlay) {
                this.overlay.removeClass('active');
            }
            
            // Animação de fechamento
            if (this.isInline) {
                // Versão inline: animação mais simples
                this.form.removeClass('expanded');
                setTimeout(() => {
                    this.form.hide();
                }, 300);
            } else {
                // Versão flutuante: animação bounce
                this.form.addClass('bounce-out');
                this.form.removeClass('bounce-in');
                setTimeout(() => {
                    this.form.hide();
                    this.form.removeClass('expanded bounce-out');
                }, 400);
            }
            
            // Atualizar estado
            this.isExpanded = false;
            
            // Atualizar aria-label
            this.toggleButton.attr('aria-label', 'Abrir busca');
            
            // Remover classe do body
            $('body').removeClass('search-retractable-open');
            
            // Limpar foco
            this.searchField.blur();
            
            // Evento personalizado
            $(document).trigger('searchRetractableClosed');
        }
        
        // Método público para abrir programaticamente
        forceOpen() {
            this.open();
        }
        
        // Método público para fechar programaticamente
        forceClose() {
            this.close();
        }
        
        // Método público para verificar estado
        isOpen() {
            return this.isExpanded;
        }
    }
    
    // Inicializar quando o documento estiver pronto
    $(document).ready(function() {
        // Verificar se existe busca retrátil na página (inline ou flutuante)
        if ($('.search-retractable-inline').length > 0 || $('.search-retractable-container').length > 0) {
            window.searchRetractable = new SearchRetractable();
            console.log('Search Retractable: Inicializado');
        }
    });
    
    // Expor classe globalmente para uso externo
    window.SearchRetractable = SearchRetractable;
    
    // Eventos personalizados para integração
    $(document).on('searchRetractableOpened', function() {
        console.log('Evento: Busca retrátil aberta');
    });
    
    $(document).on('searchRetractableClosed', function() {
        console.log('Evento: Busca retrátil fechada');
    });
    
    // Integração com outros sistemas do tema
    $(document).on('shortcutPanelOpened', function() {
        // Fechar busca retrátil quando painel de atalhos abrir
        if (window.searchRetractable && window.searchRetractable.isOpen()) {
            window.searchRetractable.forceClose();
        }
    });
    
    // Compatibilidade com mobile
    $(window).on('orientationchange resize', function() {
        if (window.searchRetractable && window.searchRetractable.isOpen()) {
            // Reposicionar se necessário
            setTimeout(() => {
                // Lógica de reposicionamento se necessário
            }, 300);
        }
    });
    
})(jQuery);

/**
 * API pública para controle externo
 */
window.SearchRetractableAPI = {
    open: function() {
        if (window.searchRetractable) {
            window.searchRetractable.forceOpen();
        }
    },
    
    close: function() {
        if (window.searchRetractable) {
            window.searchRetractable.forceClose();
        }
    },
    
    toggle: function() {
        if (window.searchRetractable) {
            window.searchRetractable.toggle();
        }
    },
    
    isOpen: function() {
        return window.searchRetractable ? window.searchRetractable.isOpen() : false;
    }
};

// Atalhos de teclado globais (opcional)
$(document).keydown(function(e) {
    // Ctrl + K ou Cmd + K para abrir busca
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        if (window.searchRetractable) {
            window.searchRetractable.toggle();
        }
    }
});