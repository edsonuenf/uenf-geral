// Log para confirmar que o script foi carregado
console.log('[Menu] Script uenf-menu-fixed.js carregado com sucesso!');

(function($) {
  'use strict';

  // Classe principal do menu
  class UENFMenu {
    constructor(menuElement) {
      console.log('[Menu] Inicializando menu para elemento:', menuElement);
      this.menu = menuElement;
      // Ajustando o breakpoint para 1921px para melhorar a experiência em telas grandes
      this.breakpoint = 1921;
      this.initialized = false;
      
      // Inicializa o menu
      this.init();
    }

    init() {
      if (this.initialized) return;
      
      console.log('[Menu] Inicializando menu...');
      this.addDropdownIndicators();
      this.setupEventListeners();
      this.handleResize();
      
      this.initialized = true;
      console.log(`[Menu] Menu inicializado. Modo: ${window.innerWidth >= this.breakpoint ? 'Desktop' : 'Mobile'}`);
    }

    addDropdownIndicators() {
      console.log('[Menu] Adicionando indicadores de dropdown...');
      $('.menu-item-has-children > a, .page_item_has_children > a', this.menu).each(function() {
        if (!$(this).find('.submenu-toggle').length) {
          console.log(`[Menu] Adicionando toggle para item: ${$(this).text().trim()}`);
          $(this).append('<i class="submenu-toggle fas fa-chevron-down"></i>');
        }
      });
    }

    /**
     * Alterna a visibilidade de um submenu
     * @param {jQuery} $parent - O elemento pai do submenu
     * @param {Event} [e] - O evento que disparou a ação (opcional)
     */
    toggleSubmenu($parent, e) {
      console.group('[Menu] toggleSubmenu chamado');
      console.log('$parent:', $parent[0]);
      console.log('Evento:', e);
      
      const $submenu = $parent.children('.sub-menu, .children');
      const $icon = $parent.find('> a .submenu-toggle');
      const isActive = $parent.hasClass('menu-item-active');
      const isDesktop = window.innerWidth >= this.breakpoint;
      
      console.log(`[Menu] Alternando submenu para: ${$parent.find('> a').text().trim()}`);
      console.log(`[Menu] Estado atual: ${isActive ? 'aberto' : 'fechado'}, Modo: ${isDesktop ? 'desktop' : 'mobile'}`);
      
      // Previne o comportamento padrão para evitar navegação indesejada
      if (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
      }
      
      // Se for clique em um link sem submenu, permite o comportamento padrão
      if ($submenu.length === 0) {
        console.log('[Menu] Nenhum submenu encontrado, navegando para o link...');
        return true;
      }
      
      // Se já está aberto, fecha
      if (isActive) {
        console.log('[Menu] Fechando submenu...');
        this.closeSubmenuAndChildren($parent);
        console.groupEnd();
        return;
      }
      
      console.log('[Menu] Abrindo submenu...');
      
      // Fecha outros submenus no mesmo nível
      const siblings = $parent.siblings('.menu-item-has-children, .page_item_has_children');
      console.log(`[Menu] Fechando ${siblings.length} irmãos...`);
      
      siblings.each((i, sibling) => {
        const $sib = $(sibling);
        if (!$sib.find($parent).length) { // Não fecha os ancestrais
          this.closeSubmenuAndChildren($sib);
        }
      });
      
      // Marca o item como ativo
      $parent.addClass('menu-item-active');
      $icon.addClass('rotated');
      
      // Prepara o submenu para animação
      $submenu
        .css({
          'display': 'block',
          'opacity': '0',
          'max-height': '0',
          'overflow': 'hidden',
          'transition': 'opacity 0.3s ease, max-height 0.3s ease'
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
      
      console.groupEnd();
    }
    
    closeSubmenuAndChildren($parent) {
      const $submenu = $parent.children('.sub-menu, .children');
      const $icon = $parent.find('> a .submenu-toggle');
      
      // Remove a classe ativa do item
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
      
      // Fecha os submenus filhos recursivamente
      $parent.find('.menu-item-active').each((i, el) => {
        this.closeSubmenuAndChildren($(el).parent());
      });
    }
    
    setupEventListeners() {
      console.log('[Menu] Configurando event listeners...');
      const self = this;
      
      // Remove event listeners antigos para evitar duplicação
      $(document).off('click.menu-item', '.menu-item-has-children > a, .page_item_has_children > a');
      $(document).off('click.menu-toggle', '.submenu-toggle');
      
      // Adiciona evento de clique nos itens de menu com submenu
      $(document).on('click.menu-item', '.menu-item-has-children > a, .page_item_has_children > a', function(e) {
        const $link = $(this);
        const $parent = $link.parent();
        
        // Verifica se o clique foi no link ou no ícone
        if (e.target !== this && !$(e.target).hasClass('submenu-toggle')) {
          console.log('[Menu] Clique no link, não no toggle - permitindo navegação');
          return true;
        }
        
        console.log('[Menu] Clique no item de menu com submenu');
        self.toggleSubmenu($parent, e);
        return false;
      });
      
      // Adiciona evento de clique nos toggles de submenu
      $(document).on('click.menu-toggle', '.submenu-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $toggle = $(this);
        const $parent = $toggle.parent().parent();
        
        console.log('[Menu] Clique no toggle de submenu');
        self.toggleSubmenu($parent, e);
        return false;
      });
      
      // Adiciona listener para fechar ao clicar fora
      $(document).on('click.menu-outside', function(e) {
        if (!$(e.target).closest('.new-menu').length) {
          console.log('[Menu] Clique fora do menu - fechando submenus abertos');
          $('.menu-item-active', self.menu).each(function() {
            self.closeSubmenuAndChildren($(this));
          });
        }
      });
      
      // Adiciona listener para tecla Esc
      $(document).on('keydown.menu', function(e) {
        if (e.key === 'Escape') {
          console.log('[Menu] Tecla Esc pressionada - fechando submenus abertos');
          $('.menu-item-active', self.menu).each(function() {
            self.closeSubmenuAndChildren($(this));
          });
        }
      });
    }
    
    handleResize() {
      console.log('[Menu] Configurando handler de resize');
      let resizeTimer;
      const self = this;
      
      // Remove event listeners antigos para evitar duplicação
      $(window).off('resize.menu-resize');
      
      $(window).on('resize.menu-resize', () => {
        console.log('[Menu] Evento de resize detectado');
        clearTimeout(resizeTimer);
        
        resizeTimer = setTimeout(() => {
          // Adiciona um pequeno offset para garantir consistência entre navegadores
          const viewportWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
          const isDesktop = viewportWidth >= self.breakpoint;
          
          console.log(`[Menu] Processando resize. Largura: ${viewportWidth}, Breakpoint: ${self.breakpoint}, Modo: ${isDesktop ? 'desktop' : 'mobile'}`);
          
          // Atualiza as classes do menu
          self.updateMobileClass();
          
          // Em desktop, garante que os submenus estejam visíveis
          if (isDesktop) {
            console.log('[Menu] Modo desktop ativado');
            $('.sub-menu, .children', self.menu).css({
              'display': 'block',
              'opacity': '1',
              'visibility': 'visible',
              'max-height': 'none'
            });
          } else {
            console.log('[Menu] Modo mobile ativado');
            // Em mobile, fecha todos os submenus
            const $menuItems = $('.menu-item-has-children, .page_item_has_children', self.menu);
            console.log(`[Menu] Fechando ${$menuItems.length} itens de menu`);
            
            $menuItems.each((i, el) => {
              const $el = $(el);
              console.log(`[Menu] Fechando item: ${$el.find('> a').text().trim()}`);
              
              $el.removeClass('menu-item-active');
              $el.children('.sub-menu, .children')
                .removeClass('show')
                .css({
                  'max-height': '0',
                  'opacity': '0',
                  'display': 'none'
                });
              $el.find('.submenu-toggle').removeClass('rotated');
            });
          }
        }, 250);
      });
    }
    
    // Atualiza a classe de mobile/desktop
    updateMobileClass() {
      const isMobile = window.innerWidth < this.breakpoint;
      if (isMobile) {
        $(this.menu).addClass('is-mobile').removeClass('is-desktop');
      } else {
        $(this.menu).addClass('is-desktop').removeClass('is-mobile');
      }
      console.log(`[Menu] Atualizando classes: ${isMobile ? 'mobile' : 'desktop'}`);
    }
  }

  // Inicializa os menus quando o DOM estiver pronto
  $(document).ready(function() {
    console.log('[Menu] DOM pronto, inicializando menu...');
    
    // Inicializa cada menu encontrado
    $('.new-menu').each(function() {
      try {
        new UENFMenu(this);
        console.log('[Menu] Menu inicializado com sucesso');
      } catch (error) {
        console.error('[Menu] Erro ao inicializar menu:', error);
      }
    });
  });
  
  // Expõe a classe para uso global se necessário
  window.UENFMenu = UENFMenu;
  
})(jQuery);
