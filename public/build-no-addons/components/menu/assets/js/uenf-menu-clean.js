/**
 * Menu responsivo para UENF
 * Versão limpa e simplificada
 */

(function($) {
  'use strict';

  class UENFMenu {
    constructor(menuElement) {
      this.menu = menuElement;
      this.breakpoint = 1920; // Breakpoint para desktop (acima de 1920px)
      this.initialized = false;
      
      console.log('[UENF Menu] Inicializando menu...');
      
      // Inicializa o menu
      this.init();
    }

    /**
     * Inicializa o menu
     */
    init() {
      if (this.initialized) return;
      
      console.log('[UENF Menu] Configurando menu...');
      
      // Adiciona indicadores de dropdown
      this.addDropdownIndicators();
      
      // Configura os listeners de eventos
      this.setupEventListeners();
      
      // Atualiza as classes iniciais
      this.updateMobileClass();
      
      this.initialized = true;
    }

    /**
     * Adiciona indicadores de dropdown aos itens de menu que têm submenus
     */
    addDropdownIndicators() {
      $('.menu-item-has-children > a, .page_item_has_children > a').each(function() {
        if (!$(this).find('.submenu-toggle').length) {
          $(this).append('<span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span>');
        }
      });
    }

    /**
     * Configura os listeners de eventos
     */
    setupEventListeners() {
      const self = this;
      const $menu = $(this.menu);
      
      // Clique no toggle do submenu
      $menu.on('click', '.submenu-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $parent = $(this).parent().parent();
        self.toggleSubmenu($parent, e);
      });
      
      // Clique no item de menu que tem submenu
      $menu.on('click', '.menu-item-has-children > a, .page_item_has_children > a', function(e) {
        // Se for desktop, não faz nada (o hover já cuida)
        if (window.innerWidth > self.breakpoint) {
          return;
        }
        
        // Se for mobile, previne o comportamento padrão e alterna o submenu
        e.preventDefault();
        const $parent = $(this).parent();
        self.toggleSubmenu($parent, e);
      });
      
      // Fecha submenus ao clicar fora
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.new-menu').length) {
          $('.menu-item-has-children, .page_item_has_children')
            .removeClass('menu-item-active')
            .find('> .sub-menu, > .children')
            .removeClass('show');
        }
      });
      
      // Atualiza as classes quando a janela for redimensionada
      let resizeTimer;
      $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
          self.updateMobileClass();
        }, 250);
      });
    }

    /**
     * Alterna a visibilidade de um submenu
     */
    toggleSubmenu($parent, e) {
      // Se for desktop, não faz nada (o hover já cuida)
      if (window.innerWidth > this.breakpoint) {
        return;
      }
      
      const $submenu = $parent.children('.sub-menu, .children');
      const isActive = $parent.hasClass('menu-item-active');
      
      // Fecha todos os outros submenus no mesmo nível
      if (!isActive) {
        $parent.siblings('.menu-item-has-children, .page_item_has_children')
          .removeClass('menu-item-active')
          .find('> .sub-menu, > .children')
          .removeClass('show');
      }
      
      // Alterna o submenu atual
      $parent.toggleClass('menu-item-active');
      $submenu.toggleClass('show');
      
      // Atualiza o ícone
      const $icon = $parent.find('> a .submenu-toggle i');
      if ($icon.length) {
        $icon.toggleClass('fa-chevron-down', !isActive);
        $icon.toggleClass('fa-chevron-up', isActive);
      }
    }

    /**
     * Atualiza as classes de mobile/desktop
     */
    updateMobileClass() {
      const isMobile = window.innerWidth <= this.breakpoint;
      
      if (isMobile) {
        $(this.menu).addClass('is-mobile').removeClass('is-desktop');
      } else {
        $(this.menu).addClass('is-desktop').removeClass('is-mobile');
        
        // Garante que todos os submenus estejam visíveis no desktop
        $('.menu-item-has-children, .page_item_has_children')
          .removeClass('menu-item-active')
          .find('> .sub-menu, > .children')
          .removeClass('show');
      }
      
      console.log(`[UENF Menu] Modo: ${isMobile ? 'mobile' : 'desktop'}`);
    }
  }

  // Inicializa o menu quando o documento estiver pronto
  $(document).ready(function() {
    console.log('[UENF Menu] Documento pronto, inicializando menu...');
    
    // Inicializa o menu principal
    const menu = new UENFMenu(document.querySelector('.new-menu'));
    
    // Expõe o menu globalmente para depuração
    window.uenfMenu = menu;
  });

})(jQuery);
