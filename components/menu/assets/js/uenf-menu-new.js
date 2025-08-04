/**
 * Menu UENF - Controle completo via JavaScript
 * Este script gerencia a abertura/fechamento de submenus sem depender de CSS
 */

console.log('[Menu] Carregando script de menu otimizado...');

(function($) {
  'use strict';

  class UENFMenu {
    constructor(menuElement) {
      console.log('[Menu] Inicializando menu para elemento:', menuElement);
      this.menu = $(menuElement);
      this.breakpoint = 992; // Breakpoint para desktop/mobile
      this.initialized = false;
      
      // Inicializa o menu
      this.init();
    }

    /**
     * Inicializa o menu
     */
    init() {
      if (this.initialized) return;
      
      console.log('[Menu] Inicializando menu...');
      
      // Adiciona indicadores de submenu
      this.addDropdownIndicators();
      
      // Configura os ouvintes de evento
      this.setupEventListeners();
      
      // Fecha todos os submenus ao carregar
      this.closeAllSubmenus();
      
      // Registra o menu no roteador de eventos, se disponível
      this.registerWithEventRouter();
      
      this.initialized = true;
      console.log('[Menu] Menu inicializado com sucesso');
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
        const isDesktop = window.innerWidth >= self.breakpoint;
        
        // Se clicou no toggle ou em um link com submenu
        if ($clicked.hasClass('submenu-toggle') || $clicked.closest('.submenu-toggle').length || 
            ($clicked.is('a') && $parent.find('> .sub-menu, > .children').length)) {
          e.preventDefault();
          e.stopPropagation();
          
          // No mobile, fecha outros submenus abertos
          if (!isDesktop) {
            self.closeAllSubmenus();
          }
          
          // Alterna o submenu clicado
          self.toggleSubmenu($parent, e);
        }
      });
      
      // Fecha submenus ao clicar fora
      $(document).on('click', (e) => {
        if (!$(e.target).closest(this.menu).length) {
          this.closeAllSubmenus();
        }
      });
      
      // Trata redimensionamento da janela
      let resizeTimer;
      let lastWidth = window.innerWidth;
      
      $(window).on('resize', () => {
        const currentWidth = window.innerWidth;
        const crossedBreakpoint = 
          (lastWidth < this.breakpoint && currentWidth >= this.breakpoint) ||
          (lastWidth >= this.breakpoint && currentWidth < this.breakpoint);
        
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
      const isDesktop = window.innerWidth >= this.breakpoint;
      const $submenu = $parent.children('.sub-menu, .children');
      const $icon = $parent.find('> a .submenu-toggle');
      const isActive = $parent.hasClass('menu-item-active');
      
      console.log(`[Menu] Alternando submenu para: ${$parent.find('> a').text().trim()}`);
      console.log(`[Menu] Modo: ${isDesktop ? 'Desktop' : 'Mobile'}, Estado: ${isActive ? 'Aberto' : 'Fechado'}`);
      
      // Se já está aberto, fecha
      if (isActive) {
        this.closeSubmenu($parent);
      } else {
        // No mobile, fecha outros submenus abertos
        if (!isDesktop) {
          this.closeAllSubmenus();
        } else {
          // No desktop, fecha apenas submenus no mesmo nível
          this.closeSiblingSubmenus($parent, isDesktop);
        }
        
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
     * Fecha submenus irmãos
     * @param {jQuery} $parent - O elemento atual
     * @param {boolean} isDesktop - Se está em modo desktop
     */
    closeSiblingSubmenus($parent, isDesktop) {
      // No mobile, fecha todos os submenus abertos
      if (!isDesktop) {
        this.closeAllSubmenus();
      } else {
        // No desktop, fecha apenas submenus no mesmo nível
        $parent.siblings('.menu-item-active').each((i, el) => {
          this.closeSubmenu($(el));
        });
      }
    }

    /**
     * Manipula o redimensionamento da janela
     */
    handleResize() {
      const currentWidth = window.innerWidth;
      const isDesktop = currentWidth >= this.breakpoint;
      const wasDesktop = this.menu.hasClass('is-desktop');
      const crossedBreakpoint = 
        (currentWidth >= this.breakpoint && !wasDesktop) || 
        (currentWidth < this.breakpoint && wasDesktop);
      
      // Atualiza a classe do menu com base no breakpoint
      this.menu.toggleClass('is-desktop', isDesktop);
      
      // Se cruzou o breakpoint, ajusta o menu
      if (crossedBreakpoint) {
        console.log(`[Menu] Breakpoint cruzado: ${isDesktop ? 'Desktop' : 'Mobile'}`);
        
        // Fecha todos os submenus ao mudar entre mobile/desktop
        this.closeAllSubmenus();
        
        // Remove estilos inline que possam afetar a exibição
        this.menu.find('.sub-menu, .children').css({
          'display': '',
          'opacity': '',
          'max-height': '',
          'visibility': ''
        });
        
        // Remove classes de estado que podem afetar a exibição
        this.menu.find('.menu-item-has-children, .page_item_has_children')
          .removeClass('menu-item-active')
          .find('.submenu-toggle')
          .attr('aria-expanded', 'false');
      }
    }
  }

  /**
   * Registra o menu no roteador de eventos global
   */
  registerWithEventRouter() {
    console.group('[Menu] Registrando menu no roteador de eventos');
    
    try {
      console.log('[Menu] Verificando se o roteador de eventos está disponível...');
      
      if (typeof window.uenfEventRouter === 'undefined') {
        console.warn('[Menu] Roteador de eventos não encontrado. O menu funcionará, mas sem integração com o roteador.');
        console.groupEnd();
        return false;
      }
      
      console.log('[Menu] Roteador de eventos encontrado!', window.uenfEventRouter);
      
      // Cria um manipulador de eventos compatível com o roteador
      const menuHandler = function(event, component) {
        console.log('[Menu] Evento recebido pelo roteador:', event.type, 'em', event.target);
        
        // Encontra o item de menu mais próximo
        const $clicked = $(event.target);
        const $menuItem = $clicked.closest('.menu-item-has-children, .page_item_has_children');
        
        if ($menuItem.length) {
          event.preventDefault();
          event.stopPropagation();
          this.toggleSubmenu($menuItem, event);
        }
      }.bind(this);
      
      // Prepara os dados para registro
      const componentData = {
        element: this.menu[0], // Elemento DOM, não jQuery
        handler: menuHandler,
        classes: [
          'menu-item',
          'menu-item-has-children',
          'page_item_has_children',
          'submenu-toggle',
          'menu-item-active',
          'menu-item-has-children--active',
          'menu-item-object-category',
          'menu-item-type-taxonomy',
          'page_item',
          'page_item_has_children'
        ]
      };
      
      console.log('[Menu] Registrando componente de menu no roteador...');
      
      // Registra o componente
      const registered = window.uenfEventRouter.registerComponent('menu', componentData);
      
      if (registered) {
        console.log('[Menu] Menu registrado com sucesso no roteador de eventos');
      } else {
        console.error('[Menu] Falha ao registrar o menu no roteador de eventos');
      }
      
      console.groupEnd();
      return registered;
      
    } catch (error) {
      console.error('[Menu] Erro ao registrar no roteador de eventos:', error);
      console.groupEnd();
      return false;
    }
  }

  // Inicializa o menu quando o documento estiver pronto
  $(document).ready(function() {
    console.log('[Menu] Documento pronto, inicializando menu...');
    
    // Verifica se o jQuery está carregado
    if (typeof jQuery === 'undefined') {
      console.error('[Menu] jQuery não está carregado! O menu não funcionará corretamente.');
      return;
    }
    
    // Verifica se o roteador de eventos está disponível
    if (typeof window.uenfEventRouter === 'undefined') {
      console.warn('[Menu] Roteador de eventos não encontrado. O menu funcionará, mas sem integração com o roteador.');
    } else {
      console.log('[Menu] Roteador de eventos encontrado:', window.uenfEventRouter);
    }
    
    // Inicializa cada instância do menu
    const menuElements = $('.menu-principal, .new-menu');
    console.log(`[Menu] Encontrados ${menuElements.length} elementos de menu para inicializar`);
    
    if (menuElements.length === 0) {
      console.warn('[Menu] Nenhum elemento de menu encontrado com as classes .menu-principal ou .new-menu');
      return;
    }
    
    menuElements.each(function(index) {
      console.log(`[Menu] Inicializando menu ${index + 1}/${menuElements.length}:`, this);
      new UENFMenu(this);
    });
    
    console.log('[Menu] Menu inicializado com sucesso!');
    
    // Verifica se o menu foi registrado corretamente
    if (window.uenfEventRouter && window.uenfEventRouter.components) {
      const menuComponent = window.uenfEventRouter.components.get('menu');
      console.log('[Menu] Componente de menu registrado:', menuComponent);
      
      if (menuComponent) {
        console.log('[Menu] Classes registradas para o menu:', Array.from(menuComponent.classes || []).join(', '));
      } else {
        console.warn('[Menu] O componente de menu não foi registrado corretamente no roteador de eventos');
      }
    }
  });

})(jQuery);
