/**
 * UENF Menu - Chrome Fix
 * Corrige problemas específicos do Chrome com eventos de clique em 1920px
 */

console.log('[UENF Menu] Carregando menu com correções para Chrome...');

(function($) {
    'use strict';

    // Configurações
    const CONFIG = {
        breakpoint: 1921, // Breakpoint para desktop (acima deste valor)
        transitionDuration: 300, // Duração da transição em ms
        useTouchEvents: false, // Se deve usar eventos de toque
        debug: true // Habilita logs de depuração
    };

    // Classe principal do menu
    class UENFMenuChromeFix {
        constructor(menuElement) {
            this.menu = $(menuElement);
            this.isDesktop = this.checkViewport();
            this.init();
            this.log('Menu inicializado');
        }

        // Inicialização do menu
        init() {
            this.addDropdownIndicators();
            this.setupEventListeners();
            this.setupViewportHandler();
            this.fixChromeIssues();
        }

        // Adiciona indicadores de dropdown
        addDropdownIndicators() {
            $('.menu-item-has-children > a, .page_item_has_children > a', this.menu).each(function() {
                if (!$(this).find('.submenu-toggle').length) {
                    $(this).append('<span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span>');
                }
            });
        }

        // Configura os listeners de evento
        setupEventListeners() {
            const self = this;
            
            // Remove event listeners antigos para evitar duplicação
            $(document).off('click.uenf-menu');
            $(document).off('touchstart.uenf-menu');
            
            // Usa eventos de toque se disponível, senão usa clique
            const eventType = CONFIG.useTouchEvents ? 'touchstart' : 'click';
            
            // Evento para itens de menu com submenu
            $(document).on(`${eventType}.uenf-menu`, '.menu-item-has-children > a, .page_item_has_children > a', function(e) {
                // Verifica se o clique foi no link ou no toggle
                const $target = $(e.target);
                const $link = $(this);
                
                // Se o clique foi no toggle ou no ícone dentro do toggle
                if ($target.hasClass('submenu-toggle') || $target.closest('.submenu-toggle').length) {
                    e.preventDefault();
                    e.stopPropagation();
                    self.toggleSubmenu($link.parent());
                    return false;
                }
                
                // Se for desktop, permite o comportamento padrão do link
                if (self.isDesktop) {
                    return true;
                }
                
                // Se for mobile, previne o comportamento padrão e alterna o submenu
                e.preventDefault();
                self.toggleSubmenu($link.parent());
                return false;
            });
            
            // Evento para o botão de toggle do submenu
            $(document).on(`${eventType}.uenf-menu`, '.submenu-toggle', function(e) {
                e.preventDefault();
                e.stopPropagation();
                self.toggleSubmenu($(this).parent().parent());
                return false;
            });
            
            // Fecha os submenus ao clicar fora
            $(document).on('click.uenf-menu', function(e) {
                if (!$(e.target).closest('.new-menu').length) {
                    self.closeAllSubmenus();
                }
            });
            
            // Fecha os submenus ao pressionar ESC
            $(document).on('keyup.uenf-menu', function(e) {
                if (e.key === 'Escape') {
                    self.closeAllSubmenus();
                }
            });
        }

        // Configura o handler de redimensionamento
        setupViewportHandler() {
            let resizeTimer;
            const self = this;
            
            $(window).off('resize.uenf-menu');
            
            $(window).on('resize.uenf-menu', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    const newIsDesktop = self.checkViewport();
                    
                    // Se o modo (mobile/desktop) mudou
                    if (newIsDesktop !== self.isDesktop) {
                        self.isDesktop = newIsDesktop;
                        self.handleViewportChange();
                    }
                }, 250);
            });
        }

        // Verifica se a viewport é desktop
        checkViewport() {
            // Usa window.innerWidth para evitar problemas com scrollbars
            return window.innerWidth >= CONFIG.breakpoint;
        }

        // Manipulador de mudança de viewport
        handleViewportChange() {
            this.log(`Mudança de viewport para: ${this.isDesktop ? 'Desktop' : 'Mobile'}`);
            
            if (this.isDesktop) {
                // Em desktop, garante que os submenus estejam visíveis
                $('.sub-menu, .children', this.menu).css({
                    'display': 'block',
                    'opacity': '1',
                    'visibility': 'visible',
                    'max-height': 'none',
                    'pointer-events': 'auto'
                });
                
                // Remove classes de mobile
                this.menu.removeClass('is-mobile').addClass('is-desktop');
            } else {
                // Em mobile, fecha todos os submenus
                this.closeAllSubmenus();
                
                // Adiciona classes de mobile
                this.menu.removeClass('is-desktop').addClass('is-mobile');
            }
        }

        // Alterna um submenu
        toggleSubmenu($parent) {
            this.log(`Alternando submenu para: ${$parent.find('> a').text().trim()}`);
            
            const $submenu = $parent.children('.sub-menu, .children');
            const $icon = $parent.find('> a .submenu-toggle i');
            const isActive = $parent.hasClass('menu-item-active');
            
            // Se já está aberto, fecha
            if (isActive) {
                this.closeSubmenu($parent);
                return;
            }
            
            // Fecha outros submenus no mesmo nível
            if (!this.isDesktop) {
                const siblings = $parent.siblings('.menu-item-has-children, .page_item_has_children');
                siblings.each((i, sibling) => {
                    this.closeSubmenu($(sibling));
                });
            }
            
            // Abre o submenu atual
            this.openSubmenu($parent);
        }

        // Abre um submenu
        openSubmenu($parent) {
            const $submenu = $parent.children('.sub-menu, .children');
            const $icon = $parent.find('> a .submenu-toggle i');
            
            // Adiciona classe ativa
            $parent.addClass('menu-item-active');
            $icon.addClass('rotated');
            
            // Prepara o submenu para animação
            $submenu
                .css({
                    'display': 'block',
                    'opacity': '0',
                    'max-height': '0',
                    'overflow': 'hidden',
                    'transition': `opacity ${CONFIG.transitionDuration}ms ease, max-height ${CONFIG.transitionDuration}ms ease`,
                    'pointer-events': 'auto'
                })
                .addClass('show')
                .on('transitionend', function() {
                    if ($(this).hasClass('show')) {
                        $(this).css({
                            'max-height': 'none',
                            'opacity': '1'
                        });
                    }
                });
            
            // Força o navegador a aplicar as propriedades iniciais
            $submenu[0].offsetHeight;
            
            // Inicia a animação
            $submenu.css({
                'opacity': '1',
                'max-height': $submenu[0].scrollHeight + 'px'
            });
            
            this.log('Submenu aberto');
        }
        
        // Fecha um submenu
        closeSubmenu($parent) {
            const $submenu = $parent.children('.sub-menu, .children');
            const $icon = $parent.find('> a .submenu-toggle i');
            
            // Remove a classe ativa
            $parent.removeClass('menu-item-active');
            $icon.removeClass('rotated');
            
            // Remove a classe show e redefine as propriedades de animação
            $submenu
                .removeClass('show')
                .css({
                    'max-height': $submenu[0].scrollHeight + 'px',
                    'opacity': '1'
                });
            
            // Força o navegador a aplicar as propriedades iniciais
            $submenu[0].offsetHeight;
            
            // Inicia a animação de fechamento
            $submenu.css({
                'max-height': '0',
                'opacity': '0'
            });
            
            // Remove o submenu do DOM após a animação
            $submenu.one('transitionend', function() {
                if (!$(this).hasClass('show')) {
                    $(this).css('display', 'none');
                }
            });
            
            this.log('Submenu fechado');
        }
        
        // Fecha todos os submenus
        closeAllSubmenus() {
            $('.menu-item-has-children, .page_item_has_children', this.menu).each((i, el) => {
                this.closeSubmenu($(el));
            });
        }
        
        // Aplica correções específicas para o Chrome
        fixChromeIssues() {
            // Força o recálculo de estilos para evitar problemas de renderização
            this.menu[0].offsetHeight;
            
            // Adiciona uma classe ao body para estilos específicos do Chrome
            if (navigator.userAgent.indexOf('Chrome') > -1) {
                $('body').addClass('browser-chrome');
                
                // Força o redesenho do menu para corrigir problemas de renderização
                setTimeout(() => {
                    this.menu.hide().show(0);
                }, 100);
            }
            
            // Verifica se há suporte a eventos de toque
            if ('ontouchstart' in window || navigator.maxTouchPoints) {
                CONFIG.useTouchEvents = true;
                this.log('Usando eventos de toque');
            }
        }
        
        // Função de log para depuração
        log(message, data) {
            if (CONFIG.debug) {
                console.log(`[UENF Menu] ${message}`, data || '');
            }
        }
    }
    
    // Inicializa o menu quando o DOM estiver pronto
    $(document).ready(function() {
        console.log('[UENF Menu] Inicializando menu com correções para Chrome...');
        
        // Inicializa cada menu encontrado
        $('.new-menu').each(function() {
            try {
                new UENFMenuChromeFix(this);
            } catch (error) {
                console.error('[UENF Menu] Erro ao inicializar menu:', error);
            }
        });
    });
    
    // Expõe a classe para uso global se necessário
    window.UENFMenuChromeFix = UENFMenuChromeFix;
    
})(jQuery);
