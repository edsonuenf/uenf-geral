// Log para confirmar que o script foi carregado
console.log('[Menu] Script uenf-menu.js carregado com sucesso!');

(function($) {
  'use strict';

  // Função para monitorar mudanças nas classes
  function setupMenuDebug() {
    console.log('[Menu] Iniciando depuração do menu...');
    
    // Verifica se o MutationObserver está disponível
    if (typeof MutationObserver !== 'undefined') {
      // Seleciona o nó que será observado
      const targetNode = document.querySelector('.new-menu');
      
      if (targetNode) {
        // Configurações do observador
        const config = { 
          attributes: true, 
          childList: true, 
          subtree: true,
          attributeFilter: ['class']
        };
        
        // Função de callback para quando ocorrerem mutações
        const callback = function(mutationsList, observer) {
          for(const mutation of mutationsList) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
              console.log('[Menu] Classe alterada:', mutation.target.className);
            } else if (mutation.type === 'childList') {
              console.log('[Menu] Conteúdo do menu alterado');
            }
          }
        };
        
        // Cria uma instância do observador
        const observer = new MutationObserver(callback);
        
        // Inicia a observação do nó com as configurações
        observer.observe(targetNode, config);
        
        console.log('[Menu] Observador de mutação ativado no menu');
      } else {
        console.warn('[Menu] Elemento .new-menu não encontrado para depuração');
      }
    } else {
      console.warn('[Menu] MutationObserver não suportado neste navegador');
    }
  }
  
  // Classe principal do menu
  class UENFMenu {
    constructor(menuElement) {
      console.log('[Menu] Inicializando menu para elemento:', menuElement);
      this.menu = menuElement;
      // Ajustando o breakpoint para 1921px para melhorar a experiência em telas grandes
      this.breakpoint = 1921;
      this.initialized = false;
      
      // Exibe informações sobre o roteador de eventos
      console.log('[Menu] Verificando uenfEventRouter:', typeof window.uenfEventRouter);
      
      // Registra este menu no roteador de eventos
      this.registerWithEventRouter();
      
      this.init();
    }
    
    init() {
      console.log('[Menu] Inicializando menu...');
      
      // Adiciona indicadores de dropdown
      this.addDropdownIndicators();
      
      // Configura os event listeners
      this.setupEventListeners();
      
      // Atualiza as classes iniciais
      this.updateMobileClass();
    }
    
    addDropdownIndicators() {
      console.log('[Menu] Adicionando indicadores de dropdown...');
      // Remove todas as setas existentes primeiro
      $('.submenu-toggle', this.menu).remove();
      
      // Adiciona setas indicadoras para itens com submenu (apenas a seta para a direita)
      $('.menu-item-has-children > a, .page_item_has_children > a', this.menu).each(function() {
        if (!$(this).find('.submenu-toggle').length) {
          console.log(`[Menu] Adicionando toggle para item: ${$(this).text().trim()}`);
          // Adiciona apenas a seta para a direita (>) sem ícone Font Awesome
          $(this).append('<span class="submenu-toggle">›</span>');
        }
      });
    }
    
    setupEventListeners() {
      const self = this;
      const $menu = $(this.menu);
      
      // Remove event listeners antigos para evitar duplicação
      $menu.off('click.menu');
      $(document).off('click.menu-click-outside');
      
      // Usando delegação de eventos para melhor performance e compatibilidade
      $menu.on('click.menu', '.menu-item-has-children > a, .page_item_has_children > a', function(e) {
        const $link = $(this);
        const $parent = $link.parent();
        const $submenu = $parent.children('.sub-menu, .children');
        
        // Se o clique foi no link e não no toggle, permite o comportamento padrão
        if (!$(e.target).hasClass('submenu-toggle') && !$(e.target).parent().hasClass('submenu-toggle')) {
          console.log('[Menu] Navegação para link principal');
          return true;
        }
        
        console.log('[Menu] Clique detectado em item do menu com submenu');
        
        if ($submenu.length) {
          console.log('[Menu] Submenu encontrado, alternando...');
          e.preventDefault();
          e.stopPropagation();
          
          // Usando requestAnimationFrame para melhor compatibilidade
          requestAnimationFrame(() => {
            self.toggleSubmenu($parent, e);
            
            // Adiciona listener para fechar ao clicar fora
            const clickHandler = function(e) {
              if (!$(e.target).closest($parent[0]).length) {
                self.closeSubmenuAndChildren($parent);
                $(document).off('click.menu-click-outside', clickHandler);
              }
            };
            
            // Usando um pequeno atraso para evitar que o clique atual dispare o fechamento
            setTimeout(() => {
              $(document).on('click.menu-click-outside', clickHandler);
            }, 10);
          });
        }
      });
      
      // Evento separado para o toggle para melhor compatibilidade
      $menu.on('click.menu', '.submenu-toggle', function(e) {
        console.log('[Menu] Clique no toggle de submenu');
        e.preventDefault();
        e.stopPropagation();
        
        const $toggle = $(this);
        const $parent = $toggle.closest('.menu-item-has-children, .page_item_has_children');
        
        if ($parent.length) {
          requestAnimationFrame(() => {
            self.toggleSubmenu($parent, e);
            
            // Adiciona listener para fechar ao clicar fora
            const clickHandler = function(e) {
              if (!$(e.target).closest($parent[0]).length) {
                self.closeSubmenuAndChildren($parent);
                $(document).off('click.menu-click-outside', clickHandler);
              }
            };
            
            // Usando um pequeno atraso para evitar que o clique atual dispare o fechamento
            setTimeout(() => {
              $(document).on('click.menu-click-outside', clickHandler);
            }, 10);
          });
        }
      });
      
      // Hover apenas para feedback visual
      $menu.on('mouseenter', 'li', function() {
        if (window.innerWidth >= self.breakpoint) {
          $(this).addClass('menu-item-hover');
        }
      });
      
      $menu.on('mouseleave', 'li', function() {
        if (window.innerWidth >= self.breakpoint) {
          $(this).removeClass('menu-item-hover');
        }
      });
      
      // Fecha todos os menus ao clicar fora
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.new-menu').length) {
          $('.menu-item-has-children, .page_item_has_children', self.menu).each(function() {
            self.closeSubmenuAndChildren($(this));
          });
        }
      });
      
      // Trata o redimensionamento da janela
      this.setupResizeHandler();
    }
    
    toggleSubmenu($parent, e) {
      console.group('[Menu] toggleSubmenu chamado');
      console.log('$parent:', $parent[0]);
      console.log('Evento:', e);
      
      const $submenu = $parent.children('.sub-menu, .children');
      const $icon = $parent.find('> a .submenu-toggle');
      const isActive = $parent.hasClass('menu-item-active');
      const isDesktop = window.innerWidth > this.breakpoint;
      
      console.log(`[Menu] Alternando submenu para: ${$parent.find('> a').text().trim()}`);
      console.log(`[Menu] Estado atual: ${isActive ? 'aberto' : 'fechado'}, Modo: ${isDesktop ? 'desktop' : 'mobile'}`);
      
      // Em desktop, não faz nada - o menu é controlado por hover
      if (isDesktop) {
        console.log('[Menu] Em desktop, controle por hover - ignorando clique');
        return;
      }
      
      // Previne o comportamento padrão para evitar navegação indesejada
      if (e) {
        e.preventDefault();
        e.stopPropagation();
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
      } else {
        console.log('[Menu] Abrindo submenu...');
        
        // No mobile, fecha outros submenus no mesmo nível
        if (!isDesktop) {
          const siblings = $parent.siblings('.menu-item-has-children, .page_item_has_children');
          console.log(`[Menu] Fechando ${siblings.length} irmãos...`);
          
          siblings.each((i, sibling) => {
            const $sib = $(sibling);
            if (!$sib.find($parent).length) { // Não fecha os ancestrais
              this.closeSubmenuAndChildren($sib);
            }
          });
        }
        
        // Fecha outros submenus no mesmo nível (em desktop)
        if (isDesktop) {
          $parent.siblings('.menu-item-active').each((i, el) => {
            this.closeSubmenuAndChildren($(el));
          });
        }
        
        // Marca o item como ativo
        $parent.addClass('menu-item-active');
        $icon.addClass('rotated');
        
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
      
      $submenu
        .css({
          'display': 'block',
          'visibility': 'visible',
          'opacity': '1',
          'max-height': '1000px', // Valor alto para a transição suave
          'height': 'auto',
          'overflow': 'visible',
          'pointer-events': isDesktop ? 'auto' : 'all',
          'padding': '0.5rem 0 0.5rem 1rem',
          'margin': '0'
        })
        .addClass('show');
      
      console.log('[Menu] Submenu aberto:', {
        'show': $submenu.hasClass('show'),
        'menu-item-active': $parent.hasClass('menu-item-active'),
        'rotated': $icon.hasClass('rotated'),
        'display': $submenu.css('display'),
        'visibility': $submenu.css('visibility'),
        'opacity': $submenu.css('opacity'),
        'max-height': $submenu.css('max-height')
      });
      
      console.groupEnd();
      return false;
    }
    
    closeSubmenuAndChildren($parent) {
      console.log('[Menu] Fechando submenu e filhos...');
      
      // Fecha os submenus filhos primeiro
      $parent.find('.menu-item-has-children, .page_item_has_children').each((i, el) => {
        const $el = $(el);
        $el.removeClass('menu-item-active');
        $el.children('.sub-menu, .children')
          .removeClass('show')
          .css('max-height', '0');
        $el.find('.submenu-toggle').removeClass('rotated');
      });
      
      // Depois fecha o próprio submenu
      $parent.removeClass('menu-item-active');
      $parent.children('.sub-menu, .children')
        .removeClass('show')
        .css('max-height', '0');
      $parent.find('> a .submenu-toggle').removeClass('rotated');
    }
    
    setupResizeHandler() {
      console.log('[Menu] Configurando handler de resize');
      let resizeTimer;
      const self = this;
      
      // Remove event listeners antigos para evitar duplicação
      $(window).off('resize.menu-resize');
      
      $(window).on('resize.menu-resize', () => {
        console.log('[Menu] Evento de resize detectado');
        clearTimeout(resizeTimer);
        
        resizeTimer = setTimeout(() => {
          console.log(`[Menu] Processando resize. Largura: ${window.innerWidth}, Breakpoint: ${self.breakpoint}`);
          
          if (window.innerWidth >= self.breakpoint) {
            console.log('[Menu] Modo desktop ativado');
            // Em desktop, remove as classes de ativo mas mantém a estrutura visível
            $('.menu-item-has-children, .page_item_has_children', self.menu)
              .removeClass('menu-item-active')
              .find('.submenu-toggle')
              .removeClass('rotated');
              
            $('.sub-menu, .children', self.menu)
              .removeClass('show')
              .css({
                'max-height': '',
                'opacity': '1',
                'visibility': 'visible',
                'display': 'none'
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
                .css('max-height', '0');
              $el.find('.submenu-toggle').removeClass('rotated');
            });
          }
          
          // Atualiza a classe de mobile/desktop
          self.updateMobileClass();
          
        }, 250);
      });
    }
    
    // Atualiza a classe de mobile/desktop
    updateMobileClass() {
      const viewportWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
      const isMobile = viewportWidth <= this.breakpoint;
      
      if (isMobile) {
        $(this.menu).addClass('is-mobile').removeClass('is-desktop');
        console.log('[Menu] Modo mobile ativado');
      } else {
        $(this.menu).addClass('is-desktop').removeClass('is-mobile');
        console.log('[Menu] Modo desktop ativado');
      }
      
      console.log(`[Menu] Viewport: ${viewportWidth}px, Breakpoint: ${this.breakpoint}px`);
      
      // Força a atualização dos estilos dos submenus
      if (!isMobile) {
        $(this.menu).find('.sub-menu, .children').each(function() {
          $(this).css({
            'display': 'block',
            'visibility': 'visible',
            'opacity': '1',
            'max-height': 'none',
            'overflow': 'visible'
          }).addClass('show');
        });
      }
    }
  }

  // Inicializa os menus quando o DOM estiver pronto
  $(document).ready(function() {
    // Verifica se o roteador de eventos está disponível
    if (typeof window.uenfEventRouter === 'undefined') {
      console.error('[Menu] ERRO: Roteador de eventos não encontrado!');
      console.error('[Menu] Verifique se o arquivo event-manager.js está sendo carregado antes de uenf-menu.js');
      
      var menus = $('.new-menu'); // Seleciona os menus pela classe .new-menu
      
      if (menus.length) {
        console.log(`[Menu] ${menus.length} menu(s) encontrado(s)`);
        
        // Inicializa cada menu encontrado
        menus.each(function(index, menu) {
          console.log(`[Menu] Inicializando menu #${index + 1}:`, menu);
          try {
            new UENFMenu(menu);
            console.log(`[Menu] Menu #${index + 1} inicializado com sucesso`);
          } catch (error) {
            console.error(`[Menu] Erro ao inicializar menu #${index + 1}:`, error);
          }
        });
      } else {
        console.warn('[Menu] Nenhum menu com a classe .new-menu encontrado!');
        console.warn('[Menu] Verifique se a classe está correta no HTML e no PHP do menu');
      }
    }
  });

  // Inicializa o debug do menu
  $(document).ready(function() {
    // Inicia o debug após um pequeno atraso
    setTimeout(function() {
      setupMenuDebug();
    }, 500);
  });

})(jQuery);

  class UENFMenu {
    constructor(menuElement) {
      console.log('[Menu] Inicializando menu para elemento:', menuElement);
      this.menu = menuElement;
      // Ajustando o breakpoint para 1921px para melhorar a experiência em telas grandes
      this.breakpoint = 1921;
      this.initialized = false;
      
      // Exibe informações sobre o roteador de eventos
      console.log('[Menu] Verificando uenfEventRouter:', typeof window.uenfEventRouter);
      
      // Registra este menu no roteador de eventos
      this.registerWithEventRouter();
      
      this.init();
    }
    
    /**
     * Manipulador de eventos do roteador
     */
    handleMenuEvent(event, component) {
      console.group('[Menu] handleMenuEvent chamado');
      console.log('Evento:', event.type, 'em', event.target);
      console.log('Elemento clicado:', event.target.outerHTML);
      console.log('Classes do elemento:', event.target.className);
      console.log('Componente:', component);
      
      // Verifica se o evento veio de um item de menu
      const $target = $(event.target);
      console.log('Elemento $target:', $target[0]?.outerHTML);
      
      // Verifica se o clique foi em um submenu-toggle ou em um link de menu
      const $menuItem = $target.closest('.menu-item-has-children, .page_item_has_children');
      const $submenuToggle = $target.closest('.submenu-toggle');
      
      console.log('Item de menu encontrado:', $menuItem.length ? 'Sim' : 'Não');
      console.log('Submenu toggle encontrado:', $submenuToggle.length ? 'Sim' : 'Não');
      
      if ($menuItem.length) {
        console.log('[Menu] Item de menu encontrado, alternando submenu...');
        console.log('Classes do item de menu:', $menuItem.attr('class'));
        console.log('HTML do item de menu:', $menuItem[0].outerHTML);
        
        // Verifica se o item tem submenu
        const hasSubmenu = $menuItem.children('.sub-menu, .children').length > 0;
        console.log('Tem submenu?', hasSubmenu ? 'Sim' : 'Não');
        
        this.toggleSubmenu($menuItem, event);
      } else {
        console.log('[Menu] Nenhum item de menu encontrado para o alvo do evento');
        // Tenta encontrar o item de menu de outra forma
        console.log('[Menu] Tentando encontrar o item de menu de forma alternativa...');
        const $altMenuItem = $target.closest('li');
        console.log('Item de menu alternativo encontrado:', $altMenuItem.length ? 'Sim' : 'Não');
        if ($altMenuItem.length) {
          console.log('Classes do item alternativo:', $altMenuItem.attr('class'));
          console.log('HTML do item alternativo:', $altMenuItem[0].outerHTML);
        }
      }
      
      console.groupEnd();
    }

    /**
     * Registra o menu no roteador de eventos global
     */
    registerWithEventRouter() {
      console.group('[Menu] Registrando menu no roteador de eventos');
      try {
        console.log('[Menu] Verificando se o roteador de eventos está disponível...');
        if (typeof window.uenfEventRouter === 'undefined') {
          console.error('[Menu] ERRO: Roteador de eventos não encontrado!');
          console.warn('[Menu] O menu pode não funcionar corretamente.');
          console.warn('[Menu] Verifique se o arquivo event-manager.js está sendo carregado corretamente.');
          console.groupEnd();
          return false;
        }
        
        console.log('[Menu] Roteador de eventos encontrado!', window.uenfEventRouter);
        console.log('[Menu] Registrando componente de menu...');
        
        // Verifica se o elemento do menu existe
        if (!this.menu) {
          console.error('[Menu] ERRO: Elemento do menu não encontrado!');
          console.groupEnd();
          return false;
        }
        
        console.log('[Menu] Elemento do menu:', this.menu);
        console.log('[Menu] Classes do elemento:', this.menu.className);
        
        // Garante que o handler está vinculado corretamente
        if (!this.handleMenuEvent) {
          console.error('[Menu] ERRO: Método handleMenuEvent não encontrado!');
          console.groupEnd();
          return false;
        }
        
        console.log('[Menu] Método handleMenuEvent encontrado:', typeof this.handleMenuEvent);
        
        // Cria uma referência estável para o handler
        const menuHandler = (event, component) => {
          console.log('[Menu] Handler de menu chamado para evento:', event.type, 'em', event.target);
          this.handleMenuEvent(event, component);
        };
        
        // Prepara os dados para registro
        const componentData = {
          element: this.menu,
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
            'menu-item-object-category',
            'menu-item-has-children',
            'page_item',
            'page-item-has-children'
          ]
        };
        
        console.log('[Menu] Registrando componente com os seguintes dados:', {
          element: this.menu,
          handler: 'menuHandler function',
          classes: componentData.classes
        });
        
        try {
          // Registra o componente no roteador de eventos
          console.log('[Menu] Chamando uenfEventRouter.registerComponent...');
          
          // Verifica se o método registerComponent existe
          if (typeof window.uenfEventRouter.registerComponent !== 'function') {
            console.error('[Menu] ERRO: uenfEventRouter.registerComponent não é uma função!');
            console.error('[Menu] uenfEventRouter:', window.uenfEventRouter);
            console.groupEnd();
            return false;
          }
          
          const registered = window.uenfEventRouter.registerComponent('menu', componentData);
          
          console.log('[Menu] Resultado do registro:', registered ? 'SUCESSO' : 'FALHA');
          
          if (registered) {
            console.log('[Menu] Componente registrado com sucesso no roteador de eventos!');
            // Verifica se as classes foram registradas corretamente
            if (window.uenfEventRouter.classHandlers) {
              console.log('[Menu] Classes registradas no roteador:');
              window.uenfEventRouter.classHandlers.forEach((handlers, className) => {
                console.log(`  - ${className}: ${handlers.size} manipulador(es)`);
              });
            } else {
              console.warn('[Menu] Nenhuma classe registrada no roteador!');
            }
          } else {
            console.error('[Menu] Falha ao registrar o componente no roteador de eventos!');
          }
        } catch (error) {
          console.error('[Menu] ERRO ao registrar componente:', error);
          console.groupEnd();
          return false;
        }
        
        console.log('[Menu] Registrado no roteador de eventos');
        return true;
      } catch (error) {
        console.error('[Menu] Erro ao registrar no roteador de eventos:', error);
        return false;
      }
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
      console.log('[Menu] Verificando indicadores de dropdown...');
      // Não adiciona novos toggles, apenas verifica se existem
      $('.menu-item-has-children > a, .page_item_has_children > a', this.menu).each(function() {
        console.log(`[Menu] Item com submenu encontrado: ${$(this).text().trim()}`);
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
      const isDesktop = window.innerWidth > this.breakpoint;
      
      console.log(`[Menu] Alternando submenu para: ${$parent.find('> a').text().trim()}`);
      console.log(`[Menu] Estado atual: ${isActive ? 'aberto' : 'fechado'}, Modo: ${isDesktop ? 'desktop' : 'mobile'}`);
      
      // Em desktop, não faz nada - o menu é controlado por hover
      if (isDesktop) {
        console.log('[Menu] Em desktop, controle por hover - ignorando clique');
        return;
      }
      
      // Previne o comportamento padrão para evitar navegação indesejada
      if (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation(); // Adicionado para garantir que o evento não seja tratado por outros manipuladores
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
      
      // No mobile, fecha outros submenus no mesmo nível
      if (!isDesktop) {
        const siblings = $parent.siblings('.menu-item-has-children, .page_item_has_children');
        console.log(`[Menu] Fechando ${siblings.length} irmãos...`);
        
        siblings.each((i, sibling) => {
          const $sib = $(sibling);
          if (!$sib.find($parent).length) { // Não fecha os ancestrais
            this.closeSubmenuAndChildren($sib);
          }
        });
      }
      
      // Marca o item como ativo
      $parent.addClass('menu-item-active');
      $icon.addClass('rotated');
      
      // Prepara o submenu para animação
      $submenu.css({
        'display': 'block',
        'visibility': 'visible',
        'opacity': '0',
        'max-height': '0',
        'overflow': 'hidden',
        'padding': '0.5rem 0 0.5rem 1rem',
        'margin': '0',
        'pointer-events': 'auto'
      });
      
      // Força o navegador a renderizar as mudanças
      $submenu[0].offsetHeight;
      
      // Anima a abertura do submenu
      $submenu.stop().animate({
        'opacity': '1',
        'max-height': $submenu[0].scrollHeight + 'px'
      }, {
        duration: 300,
        complete: () => {
          // Remove a altura fixa após a animação para permitir redimensionamento
          $submenu.css({
            'max-height': '',
            'overflow': 'visible'
          }).addClass('show');
          
          console.log('[Menu] Submenu aberto:', {
            'show': $submenu.hasClass('show'),
            'menu-item-active': $parent.hasClass('menu-item-active'),
            'rotated': $icon.hasClass('rotated'),
            'display': $submenu.css('display'),
            'visibility': $submenu.css('visibility'),
            'opacity': $submenu.css('opacity'),
            'max-height': $submenu.css('max-height'),
            'pointer-events': $submenu.css('pointer-events')
          });
        }
      });
      
      console.groupEnd();
    }

    closeSubmenuAndChildren($parent) {
      console.group(`[Menu] Fechando submenu para: ${$parent.find('> a').text().trim()}`);
      
      // Obtém o ícone de toggle
      const $icon = $parent.find('> a .submenu-toggle');
      
      // Remove as classes ativas
      $parent.removeClass('menu-item-active');
      $icon.removeClass('rotated');
      
      // Fecha todos os submenus filhos primeiro
      $parent.find('.menu-item-has-children, .page_item_has_children').each((i, el) => {
        const $el = $(el);
        const $childSubmenu = $el.children('.sub-menu, .children');
        
        $el.removeClass('menu-item-active');
        $el.find('> a .submenu-toggle').removeClass('rotated');
        
        // Anima o fechamento do submenu filho
        if ($childSubmenu.length) {
          $childSubmenu.stop().animate({
            'opacity': '0',
            'max-height': '0',
            'padding-top': '0',
            'padding-bottom': '0',
            'margin-top': '0',
            'margin-bottom': '0'
          }, {
            duration: 300,
            complete: function() {
              $(this).css({
                'display': 'none',
                'visibility': 'hidden',
                'pointer-events': 'none',
                'overflow': 'hidden'
              }).removeClass('show');
            }
          });
        }
      });
      
      // Fecha o submenu atual
      const $submenu = $parent.children('.sub-menu, .children');
      
      if ($submenu.length) {
        $submenu.stop().animate({
          'opacity': '0',
          'max-height': '0',
          'padding-top': '0',
          'padding-bottom': '0',
          'margin-top': '0',
          'margin-bottom': '0'
        }, {
          duration: 300,
          complete: function() {
            $(this).css({
              'display': 'none',
              'visibility': 'hidden',
              'pointer-events': 'none',
              'overflow': 'hidden'
            }).removeClass('show');
            
            console.log('[Menu] Submenu fechado com sucesso');
            console.groupEnd();
          }
        });
      } else {
        console.log('[Menu] Nenhum submenu encontrado para fechar');
        console.groupEnd();
      }
    }

    setupEventListeners() {
      const self = this;
      const $menu = $(this.menu);
      
      console.log('[Menu] Configurando listeners');
      console.log('[Menu] Largura da tela:', window.innerWidth, 'Breakpoint:', this.breakpoint);

      // Remove event listeners antigos para evitar duplicação
      $menu.off('click.menu');
      $(document).off('click.menu-click-outside');
      
      // Usando delegação de eventos para melhor performance e compatibilidade
      $menu.on('click.menu', '.menu-item-has-children > a, .page_item_has_children > a', function(e) {
        const $link = $(this);
        const $parent = $link.parent();
        const $submenu = $parent.children('.sub-menu, .children');
        
        // Se o clique foi no link e não no toggle, permite o comportamento padrão
        if (!$(e.target).hasClass('submenu-toggle') && !$(e.target).parent().hasClass('submenu-toggle')) {
          console.log('[Menu] Navegação para link principal');
          return true;
        }
        
        console.log('[Menu] Clique detectado em item do menu com submenu');
        
        if ($submenu.length) {
          console.log('[Menu] Submenu encontrado, alternando...');
          e.preventDefault();
          e.stopPropagation();
          
          // Usando requestAnimationFrame para melhor compatibilidade
          requestAnimationFrame(() => {
            self.toggleSubmenu($parent, e);
            
            // Adiciona listener para fechar ao clicar fora
            const clickHandler = function(e) {
              if (!$(e.target).closest($parent[0]).length) {
                self.closeSubmenuAndChildren($parent);
                $(document).off('click.menu-click-outside', clickHandler);
              }
            };
            
            // Usando um pequeno atraso para evitar que o clique atual dispare o fechamento
            setTimeout(() => {
              $(document).on('click.menu-click-outside', clickHandler);
            }, 10);
          });
        }
      });
      
      // Evento separado para o toggle para melhor compatibilidade
      $menu.on('click.menu', '.submenu-toggle', function(e) {
        console.log('[Menu] Clique no toggle de submenu');
        e.preventDefault();
        e.stopPropagation();
        
        const $toggle = $(this);
        const $parent = $toggle.closest('.menu-item-has-children, .page_item_has_children');
        
        if ($parent.length) {
          requestAnimationFrame(() => {
            self.toggleSubmenu($parent, e);
            
            // Adiciona listener para fechar ao clicar fora
            const clickHandler = function(e) {
              if (!$(e.target).closest($parent[0]).length) {
                self.closeSubmenuAndChildren($parent);
                $(document).off('click.menu-click-outside', clickHandler);
              }
            };
            
            // Usando um pequeno atraso para evitar que o clique atual dispare o fechamento
            setTimeout(() => {
              $(document).on('click.menu-click-outside', clickHandler);
            }, 10);
          });
        }
      });

      // Hover apenas para feedback visual (mudança de cor de fundo)
      console.log('[Menu] Configurando hover apenas para feedback visual');
      
      $menu.on('mouseenter', 'li', function() {
        if (window.innerWidth >= self.breakpoint) {
          $(this).addClass('menu-item-hover');
        }
      });
      
      $menu.on('mouseleave', 'li', function() {
        if (window.innerWidth >= self.breakpoint) {
          $(this).removeClass('menu-item-hover');
        }
      });

      // Fecha todos os menus ao clicar fora
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.new-menu').length) {
          $('.menu-item-has-children', $menu).each(function() {
            self.closeSubmenuAndChildren($(this));
          });
        }
      });

      // Navegação por teclado
      $menu.on('keydown', 'a', function(e) {
        const $this = $(this);
        const $parent = $this.parent();
        const $submenu = $parent.children('.sub-menu, .children');
        
        switch(e.key) {
          case 'Enter':
          case ' ':
            if ($submenu.length) {
              e.preventDefault();
              self.toggleSubmenu($parent);
            }
            break;
            
          case 'Escape':
            self.closeSubmenuAndChildren($parent);
            break;
        }
      });
    }

    handleResize() {
      console.log('[Menu] Configurando handler de resize');
      let resizeTimer;
      const self = this;
      
      // Atualiza a classe do body baseada no breakpoint
      this.updateMobileClass();
      
      // Remove event listeners antigos para evitar duplicação
      $(window).off('resize.menu-resize');
      
      $(window).on('resize.menu-resize', () => {
        console.log('[Menu] Evento de resize detectado');
        clearTimeout(resizeTimer);
        
        resizeTimer = setTimeout(() => {
          // Adiciona um pequeno offset para garantir consistência entre navegadores
          const viewportWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
          console.log(`[Menu] Processando resize. Largura: ${viewportWidth}, Breakpoint: ${self.breakpoint}`);
          
          if (viewportWidth >= self.breakpoint) {
            console.log('[Menu] Modo desktop ativado');
            // Em desktop, remove as classes de ativo mas mantém a estrutura visível
            $('.menu-item-has-children, .page_item_has_children', self.menu)
              .removeClass('menu-item-active')
              .find('.submenu-toggle')
              .removeClass('rotated');
              
            $('.sub-menu, .children', self.menu)
              .removeClass('show')
              .css({
                'max-height': '',
                'opacity': '1',
                'visibility': 'visible',
                'display': 'none'
              });
          } else {
            console.log('[Menu] Modo mobile ativado');
            // Em mobile, fecha todos os submenus
            const $menuItems = $('.menu-item-has-children, .page_item_has_children', this.menu);
            console.log(`[Menu] Fechando ${$menuItems.length} itens de menu`);
            
            $menuItems.each((i, el) => {
              const $el = $(el);
              console.log(`[Menu] Fechando item: ${$el.find('> a').text().trim()}`);
              
              $el.removeClass('menu-item-active');
              $el.children('.sub-menu, .children')
                .removeClass('show')
                .css('max-height', '0');
              $el.find('.submenu-toggle').removeClass('rotated');
            });
          }
        }, 250);
      });
    }
  }

  // Inicializa os menus quando o DOM estiver pronto
  $(document).ready(function() {
    // Verifica se o roteador de eventos está disponível
    if (typeof window.uenfEventRouter === 'undefined') {
      console.error('[Menu] ERRO: Roteador de eventos não encontrado!');
      console.error('[Menu] Verifique se o arquivo event-manager.js está sendo carregado antes de uenf-menu.js');
      
      var menus = $('.new-menu'); // Seleciona os menus pela classe .new-menu
      
      if (menus.length) {
        console.log(`[Menu] ${menus.length} menu(s) encontrado(s)`);
        
        // Inicializa cada menu encontrado
        menus.each(function(index, menu) {
          console.log(`[Menu] Inicializando menu #${index + 1}:`, menu);
          try {
            new UENFMenu(menu);
            console.log(`[Menu] Menu #${index + 1} inicializado com sucesso`);
          } catch (error) {
            console.error(`[Menu] Erro ao inicializar menu #${index + 1}:`, error);
          }
        });
      } else {
        console.warn('[Menu] Nenhum menu com a classe .new-menu encontrado!');
        console.warn('[Menu] Verifique se a classe está correta no HTML e no PHP do menu');
      }
    }
  });

  // Código de depuração adicionado após a inicialização do menu
  $(document).ready(function() {
    console.log('=== DEBUG MENU ===');
    console.log('Itens do menu encontrados:', $('.page_item_has_children, .menu-item-has-children').length);
    console.log('Itens ativos:', $('.menu-item-active').length);

    // Função para monitorar mudanças nas classes
    function setupMenuDebug() {
      console.log('[Menu] Configurando depuração do menu...');
      
      // Monitora cliques no menu
      $(document).off('click.menuDebug').on('click.menuDebug', '.page_item_has_children > a, .menu-item-has-children > a', function(e) {
        console.log('=== CLIQUE NO MENU ===');
        console.log('Item clicado:', this);
        console.log('Classes do item:', this.className);
        
        var $parent = $(this).parent();
        var $submenu = $parent.children('.sub-menu, .children');
        
        console.log('Submenu encontrado:', $submenu.length ? 'Sim' : 'Não');
        console.log('Estado atual:', $parent.hasClass('menu-item-active') ? 'Aberto' : 'Fechado');
        
        if ($submenu.length) {
          console.log('Estilos do submenu:', {
            display: $submenu.css('display'),
            visibility: $submenu.css('visibility'),
            opacity: $submenu.css('opacity'),
            maxHeight: $submenu.css('max-height'),
            position: $submenu.css('position')
          });
        }
        
        // Verifica se o evento está sendo propagado
        console.log('Evento:', {
          type: e.type,
          target: e.target,
          currentTarget: e.currentTarget,
          defaultPrevented: e.defaultPrevented || false,
          bubbles: e.bubbles,
          cancelable: e.cancelable
        });
      });
      
      // Monitora mudanças nas classes dos itens do menu
      if (typeof MutationObserver !== 'undefined') {
        console.log('[Menu] Configurando MutationObserver para monitorar mudanças nas classes...');
        
        var observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
              var $target = $(mutation.target);
              if ($target.is('.page_item_has_children, .menu-item-has-children')) {
                console.log('=== MUDANÇA DE CLASSE ===');
                console.log('Elemento:', mutation.target);
                console.log('Classes antigas:', mutation.oldValue || 'N/A');
                console.log('Classes novas:', mutation.target.className);
                
                var $submenu = $target.children('.sub-menu, .children');
                if ($submenu.length) {
                  console.log('Estado do submenu:', {
                    visivel: $submenu.is(':visible'),
                    display: $submenu.css('display'),
                    visibility: $submenu.css('visibility'),
                    opacity: $submenu.css('opacity')
                  });
                }
              }
            }
          });
        });
        
        // Inicia a observação
        var config = { attributes: true, attributeOldValue: true, subtree: true };
        observer.observe(document.body, config);
              }
            }
          });
        });
        
        // Aplica o observer a todos os itens de menu
        var $menuItems = $('.page_item_has_children, .menu-item-has-children');
        console.log(`[Menu] Monitorando ${$menuItems.length} itens de menu`);
        
        $menuItems.each(function() {
          observer.observe(this, {
            attributes: true,
            attributeFilter: ['class'],
            attributeOldValue: true
          });
        });
      } else {
        console.warn('[Menu] MutationObserver não suportado neste navegador');
      }
    }
    
    // Adiciona método para atualizar a classe de mobile
    updateMobileClass() {
      const isMobile = window.innerWidth <= this.breakpoint;
      if (isMobile) {
        $(this.menu).addClass('is-mobile');
        $(this.menu).removeClass('is-desktop');
      } else {
        $(this.menu).addClass('is-desktop');
        $(this.menu).removeClass('is-mobile');
      }
      console.log(`[Menu] Atualizando classes: ${isMobile ? 'mobile' : 'desktop'}`);
    }
  }

  // Inicializa os menus quando o DOM estiver pronto
  $(document).ready(function() {
    // Verifica se o roteador de eventos está disponível
    if (typeof window.uenfEventRouter === 'undefined') {
      console.error('[Menu] ERRO: Roteador de eventos não encontrado!');
      console.error('[Menu] Verifique se o arquivo event-manager.js está sendo carregado antes de uenf-menu.js');
      
      var menus = $('.new-menu'); // Seleciona os menus pela classe .new-menu
      
      if (menus.length) {
        console.log(`[Menu] ${menus.length} menu(s) encontrado(s)`);
        
        // Inicializa cada menu encontrado
        menus.each(function(index, menu) {
          console.log(`[Menu] Inicializando menu #${index + 1}:`, menu);
          try {
            new UENFMenu(menu);
            console.log(`[Menu] Menu #${index + 1} inicializado com sucesso`);
          } catch (error) {
            console.error(`[Menu] Erro ao inicializar menu #${index + 1}:`, error);
          }
        });
      } else {
        console.warn('[Menu] Nenhum menu com a classe .new-menu encontrado!');
        console.warn('[Menu] Verifique se a classe está correta no HTML e no PHP do menu');
      }
    }
  });

  // Inicializa o debug do menu
  $(document).ready(function() {
    // Inicia o debug após um pequeno atraso
    setTimeout(function() {
      setupMenuDebug();
    }, 500);
  });

})(jQuery);