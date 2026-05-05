/**
 * Menu UENF - Versão Simplificada
 * Mesmo menu para mobile e desktop, sem roteamento de eventos
 */

(function($) {
  'use strict';

  class UENFSimpleMenu {
    constructor(menuElement) {
      this.menu = $(menuElement);
      this.breakpoint = 1920; // Breakpoint para desktop em 1920px
      this.initialized = false;
      
      // Inicializa o menu
      this.init();
      
      // Adiciona classe inicial baseada no tamanho da tela
      this.updateMobileClass();
    }

    /**
     * Inicializa o menu
     */
    init() {
      // Evita inicialização múltipla
      if (this.initialized) return;
      this.initialized = true;

      // Adiciona indicadores de dropdown
      this.addDropdownIndicators();
      
      // Configura os ouvintes de evento
      this.setupEventListeners();
      
      // Configura o menu baseado no tamanho da tela
      this.handleResize();
      
    }

    /**
     * Adiciona indicadores de dropdown aos itens com submenu
     */
    addDropdownIndicators() {
      this.menu.find('.menu-item-has-children > a, .page_item_has_children > a').each((i, el) => {
        const $link = $(el);
        if (!$link.find('.submenu-toggle').length) {
          $link.append('<span class="submenu-toggle" aria-expanded="false"><span class="screen-reader-text">Abrir submenu</span></span>');
        }
      });
    }

    /**
     * Configura os ouvintes de evento
     */
    setupEventListeners() {
      const self = this;
      
      // Clique no toggle ou link com submenu
      this.menu.on('click', '.menu-item-has-children > a, .page_item_has_children > a, .submenu-toggle', function(e) {
        const $clicked = $(this);
        const $parent = $clicked.closest('.menu-item-has-children, .page_item_has_children');
        const viewportWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
        const isDesktop = viewportWidth > self.breakpoint;
        
        // Se for desktop, permite o comportamento padrão para navegação
        if (isDesktop) {
          // Se clicou no toggle, previne o comportamento padrão
          if ($clicked.hasClass('submenu-toggle') || $clicked.closest('.submenu-toggle').length) {
            e.preventDefault();
            e.stopPropagation();
          }
          return;
        }
        
        // Se for mobile, trata o clique para mostrar/ocultar submenu
        if ($clicked.hasClass('submenu-toggle') || $clicked.closest('.submenu-toggle').length || 
            ($clicked.is('a') && $parent.find('> .sub-menu, > .children').length)) {
          e.preventDefault();
          e.stopPropagation();
          self.toggleSubmenu($parent, e);
        }
      });
      
      // Eventos de hover para desktop
      this.menu.on('mouseenter', '.menu-item-has-children, .page_item_has_children', function() {
        const viewportWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
        if (viewportWidth > self.breakpoint) {
          const $parent = $(this);
          const $submenu = $parent.children('.sub-menu, .children');
          
          // Fecha outros submenus abertos no mesmo nível
          $parent.siblings().each(function() {
            const $sibling = $(this);
            if ($sibling.hasClass('menu-item-has-children') || $sibling.hasClass('page_item_has_children')) {
              self.closeSubmenu($sibling);
            }
          });
          
          // Abre o submenu atual
          if ($submenu.length) {
            self.openSubmenu($parent);
          }
        }
      });
      
      // Fecha submenus ao clicar fora
      $(document).on('click', (e) => {
        const viewportWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
        if (!$(e.target).closest(this.menu).length) {
          this.closeAllSubmenus();
        } else if (viewportWidth > this.breakpoint) {
          // No desktop, fecha submenus ao clicar em um item de menu sem submenu
          const $clicked = $(e.target).closest('li');
          if ($clicked.length && !$clicked.hasClass('menu-item-has-children') && !$clicked.hasClass('page_item_has_children')) {
            this.closeAllSubmenus();
          }
        }
      });
      
      // Fecha submenus ao sair do menu no desktop
      this.menu.on('mouseleave', () => {
        const viewportWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
        if (viewportWidth > this.breakpoint) {
          this.closeAllSubmenus();
        }
      });
      
      // Trata redimensionamento da janela
      let resizeTimer;
      let lastWidth = window.innerWidth;
      
      $(window).on('resize', () => {
        const currentWidth = window.innerWidth;
        const crossedBreakpoint = 
          (lastWidth <= this.breakpoint && currentWidth > this.breakpoint) ||
          (lastWidth > this.breakpoint && currentWidth <= this.breakpoint);
        
        lastWidth = currentWidth;
        
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          if (crossedBreakpoint) {
            // Se cruzou o breakpoint, fecha todos os submenus
            this.closeAllSubmenus();
          }
          this.handleResize();
        }, 250);
      });
    }

    /**
     * Alterna a visibilidade de um submenu
     * @param {jQuery} $parent - O elemento pai do submenu
     * @param {Event} e - O evento que disparou a ação
     */
    toggleSubmenu($parent, e) {
      const $submenu = $parent.children('.sub-menu, .children');
      const $icon = $parent.find('> a .submenu-toggle');
      const isActive = $parent.hasClass('menu-item-active');
      
      // Verificação mais robusta para o breakpoint
      const viewportWidth = Math.max(
        document.documentElement.clientWidth || 0, 
        window.innerWidth || 0
      );
      const isDesktop = viewportWidth > this.breakpoint;
      
      // Em desktop, garante que o submenu está visível
      if (isDesktop) {
        if ($submenu.length) {
          $submenu.css({
            'display': 'block',
            'visibility': 'visible',
            'opacity': '1',
            'max-height': 'none',
            'overflow': 'visible'
          }).addClass('show');
        }
        return;
      }
      
      // Se já está aberto, fecha
      if (isActive) {
        this.closeSubmenu($parent);
      } else {
        // Fecha outros submenus abertos
        this.closeAllSubmenus();
        
        // Abre o submenu atual
        this.openSubmenu($parent);
      }
    }

    /**
     * Abre um submenu
     * @param {jQuery} $parent - O elemento pai do submenu
     */
    openSubmenu($parent) {
      const $submenu = $parent.children('.sub-menu, .children');
      const $icon = $parent.find('> a .submenu-toggle');
      
      // Marca o item como ativo
      $parent.addClass('menu-item-active');
      $icon.attr('aria-expanded', 'true');
      
      // Mostra o submenu com animação
      $submenu
        .css({
          'display': 'block',
          'opacity': '0',
          'max-height': '0',
          'overflow': 'hidden',
          'visibility': 'visible'
        })
        .stop()
        .animate({
          'opacity': '1',
          'max-height': $submenu[0].scrollHeight + 'px'
        }, 300, () => {
          // Remove a altura fixa após a animação para permitir redimensionamento
          $submenu.css('max-height', '');
        });
    }

    /**
     * Fecha um submenu
     * @param {jQuery} $parent - O elemento pai do submenu
     */
    closeSubmenu($parent) {
      const $submenu = $parent.children('.sub-menu, .children');
      const $icon = $parent.find('> a .submenu-toggle');
      
      // Remove a classe de ativo
      $parent.removeClass('menu-item-active');
      $icon.attr('aria-expanded', 'false');
      
      // Esconde o submenu com animação
      $submenu
        .stop()
        .css('max-height', $submenu[0].scrollHeight + 'px')
        .animate({
          'opacity': '0',
          'max-height': '0'
        }, 300, () => {
          $submenu.css({
            'display': 'none',
            'opacity': '',
            'max-height': ''
          });
        });
      
      // Fecha quaisquer submenus filhos
      $parent.find('.menu-item-active').each((i, el) => {
        this.closeSubmenu($(el));
      });
    }

    /**
     * Fecha todos os submenus
     */
    closeAllSubmenus() {
      this.menu.find('.menu-item-has-children, .page_item_has_children').each((i, el) => {
        this.closeSubmenu($(el));
      });
    }

    /**
     * Manipula o redimensionamento da janela
     */
    updateMobileClass() {
      // Verificação mais robusta para o viewport
      const viewportWidth = Math.max(
        document.documentElement.clientWidth || 0, 
        window.innerWidth || 0
      );
      const isMobile = viewportWidth <= this.breakpoint;

      if (isMobile) {
        this.menu.addClass('is-mobile').removeClass('is-desktop');
        // Garante que os submenus estejam ocultos no mobile
        this.menu.find('.sub-menu, .children').removeClass('show');
      } else {
        this.menu.addClass('is-desktop').removeClass('is-mobile');
        // Garante que os submenus estejam visíveis no desktop
        this.menu.find('.sub-menu, .children').addClass('show');
      }
    }
    
    handleResize() {
      // Verificação mais robusta para o viewport
      const viewportWidth = Math.max(
        document.documentElement.clientWidth || 0, 
        window.innerWidth || 0
      );
      const isDesktop = viewportWidth > this.breakpoint;

      // Atualiza as classes de estado
      this.updateMobileClass();
      
      // Ajusta os estilos dos submenus com base no modo
      this.menu.find('.sub-menu, .children').each((i, el) => {
        const $submenu = $(el);
        if (isDesktop) {
          // No desktop, garante que os submenus estejam visíveis
          $submenu.css({
            'display': 'block',
            'visibility': 'visible',
            'opacity': '1',
            'max-height': 'none',
            'overflow': 'visible'
          });
        } else {
          // No mobile, remove estilos inline para permitir que o CSS controle a exibição
          $submenu.css({
            'display': '',
            'opacity': '',
            'max-height': '',
            'visibility': '',
            'overflow': ''
          });
        }
      });
      
      // Fecha todos os submenus ao mudar para mobile
      if (!isDesktop) {
        this.closeAllSubmenus();
      }
      
      // Atualiza o estado dos toggles e garante a acessibilidade
      this.menu.find('.menu-item-has-children, .page_item_has_children').each((i, el) => {
        const $el = $(el);
        const $toggle = $el.find('> a .submenu-toggle');
        const $submenu = $el.children('.sub-menu, .children');
        
        if (isDesktop) {
          // No desktop, os submenus são controlados por hover
          $toggle.attr('aria-expanded', 'false');
          // Garante que os submenus estejam visíveis no desktop
          if ($submenu.length) {
            $submenu.addClass('show');
          }
        } else {
          // No mobile, mantém o estado atual
          const isActive = $el.hasClass('menu-item-active');
          $toggle.attr('aria-expanded', isActive ? 'true' : 'false');
        }
      });
    }
  }

  // Inicializa o menu quando o documento estiver pronto
  $(document).ready(function() {
    // Verifica se o jQuery está carregado
    if (typeof jQuery === 'undefined') {
      return;
    }

    // Inicializa cada instância do menu
    const menuElements = $('.menu-principal, .new-menu');

    if (menuElements.length === 0) {
      return;
    }

    menuElements.each(function(index) {
      new UENFSimpleMenu(this);
    });
  });

})(jQuery);
