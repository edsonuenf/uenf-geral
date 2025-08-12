// IIFE para isolar o escopo do jQuery
(function($) {
// Função construtora ES5
function UENFMenu(menuElement) {
  this.menu = $(menuElement);
  this.breakpoint = 992;
  this.initialized = false;
  this.init();

UENFMenu.prototype.init = function() {
  if (this.initialized) return;
  this.addDropdownIndicators();
  this.setupEventListeners();
  this.closeAllSubmenus();
  this.registerWithEventRouter();
  this.initialized = true;
};

UENFMenu.prototype.addDropdownIndicators = function() {
  this.menu.find('.menu-item-has-children > a, .page_item_has_children > a').each(function(i, el) {
    var $link = $(el);
    if (!$link.find('.submenu-toggle').length) {
      $link.append('<span class="submenu-toggle" aria-expanded="false"><span class="screen-reader-text">Abrir submenu</span></span>');
    }
  });
};

UENFMenu.prototype.setupEventListeners = function() {
    var self = this;
    this.menu.on('click', '.menu-item-has-children > a, .page_item_has_children > a, .submenu-toggle', function(e) {
      var $clicked = $(this);
      var $parent = $clicked.closest('.menu-item-has-children, .page_item_has_children');
      var isDesktop = window.innerWidth >= self.breakpoint;
      if ($clicked.hasClass('submenu-toggle') || $clicked.closest('.submenu-toggle').length || ($clicked.is('a') && $parent.find('> .sub-menu, > .children').length)) {
        e.preventDefault();
        e.stopPropagation();
        if (!isDesktop) {
          self.closeAllSubmenus();
        }
        self.toggleSubmenu($parent, e);
      }
    });
    $(document).on('click', function(e) {
      if (!$(e.target).closest(self.menu).length) {
        self.closeAllSubmenus();
      }
    });
    var resizeTimer;
    var lastWidth = window.innerWidth;
    $(window).on('resize', function() {
      var currentWidth = window.innerWidth;
      var crossedBreakpoint = (lastWidth < self.breakpoint && currentWidth >= self.breakpoint) || (lastWidth >= self.breakpoint && currentWidth < self.breakpoint);
      lastWidth = currentWidth;
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
        if (crossedBreakpoint) {
          self.closeAllSubmenus();
        }
        self.handleResize();
      }, 250);
    });
  };

  UENFMenu.prototype.toggleSubmenu = function($parent, e) {
    var isDesktop = window.innerWidth >= this.breakpoint;
    var $submenu = $parent.children('.sub-menu, .children');
    var $icon = $parent.find('> a .submenu-toggle');
    var isActive = $parent.hasClass('menu-item-active');
    if (isActive) {
      this.closeSubmenu($parent);
    } else {
      if (!isDesktop) {
        this.closeAllSubmenus();
      } else {
        this.closeSiblingSubmenus($parent, isDesktop);
      }
      this.openSubmenu($parent);
    }
  };

  UENFMenu.prototype.openSubmenu = function($parent) {
    var $submenu = $parent.children('.sub-menu, .children');
    var $icon = $parent.find('> a .submenu-toggle');
    $parent.addClass('menu-item-active');
    $icon.attr('aria-expanded', 'true');
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
      }, 300, function() {
        $submenu.css('max-height', '');
      });
  };

  UENFMenu.prototype.closeSubmenu = function($parent) {
    var $submenu = $parent.children('.sub-menu, .children');
    var $icon = $parent.find('> a .submenu-toggle');
    $parent.removeClass('menu-item-active');
    $icon.attr('aria-expanded', 'false');
    $submenu
      .stop()
      .css('max-height', $submenu[0].scrollHeight + 'px')
      .animate({
        'opacity': '0',
        'max-height': '0'
      }, 300, function() {
        $submenu.css({
          'display': 'none',
          'opacity': '',
          'max-height': ''
        });
      });
    $parent.find('.menu-item-active').each(function(i, el) {
      this.closeSubmenu($(el));
    }.bind(this));
  };

  UENFMenu.prototype.closeAllSubmenus = function() {
    this.menu.find('.menu-item-has-children, .page_item_has_children').each(function(i, el) {
      this.closeSubmenu($(el));
    }.bind(this));
  };

  UENFMenu.prototype.closeSiblingSubmenus = function($parent, isDesktop) {
    if (!isDesktop) {
      this.closeAllSubmenus();
    } else {
      $parent.siblings('.menu-item-active').each(function(i, el) {
        this.closeSubmenu($(el));
      }.bind(this));
    }
  };

  UENFMenu.prototype.handleResize = function() {
    var currentWidth = window.innerWidth;
    var isDesktop = currentWidth >= this.breakpoint;
    var wasDesktop = this.menu.hasClass('is-desktop');
    var crossedBreakpoint = (currentWidth >= this.breakpoint && !wasDesktop) || (currentWidth < this.breakpoint && wasDesktop);
    this.menu.toggleClass('is-desktop', isDesktop);
    if (crossedBreakpoint) {
      this.closeAllSubmenus();
      this.menu.find('.sub-menu, .children').css({
        'display': '',
        'opacity': '',
        'max-height': '',
        'visibility': ''
      });
      this.menu.find('.menu-item-has-children, .page_item_has_children')
        .removeClass('menu-item-active')
        .find('.submenu-toggle')
        .attr('aria-expanded', 'false');
    }
  };

  UENFMenu.prototype.registerWithEventRouter = function() {
    console.group('[Menu] Registrando menu no roteador de eventos');
    try {
      if (typeof window.uenfEventRouter === 'undefined') {
        console.warn('[Menu] Roteador de eventos não encontrado. O menu funcionará, mas sem integração com o roteador.');
        console.groupEnd();
        return false;
      }
      var self = this;
      var menuHandler = function(event, component) {
        var $clicked = $(event.target);
        var $menuItem = $clicked.closest('.menu-item-has-children, .page_item_has_children');
        if ($menuItem.length) {
          event.preventDefault();
          event.stopPropagation();
          self.toggleSubmenu($menuItem, event);
        }
      };
      var componentData = {
        element: self.menu[0],
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
      var registered = window.uenfEventRouter.registerComponent('menu', componentData);
      if (registered) {
  // log removido
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
  };

  // Inicializa o menu quando o documento estiver pronto
  $(document).ready(function() {
    
    // Verifica se o jQuery está carregado
    if (typeof jQuery === 'undefined') {
      console.error('[Menu] jQuery não está carregado! O menu não funcionará corretamente.');
      return;
    }
    
    // Verifica se o roteador de eventos está disponível
    if (typeof window.uenfEventRouter === 'undefined') {
      console.warn('[Menu] Roteador de eventos não encontrado. O menu funcionará, mas sem integração com o roteador.');
    } else {
  // log removido
    }
    
    // Inicializa cada instância do menu
    const menuElements = $('.menu-principal, .new-menu');

    
    if (menuElements.length === 0) {
      console.warn('[Menu] Nenhum elemento de menu encontrado com as classes .menu-principal ou .new-menu');
      return;
    }
    
    menuElements.each(function(index) {
  // log removido
      new UENFMenu(this);
    });
    

    
    // Verifica se o menu foi registrado corretamente
    if (window.uenfEventRouter && window.uenfEventRouter.components) {
      const menuComponent = window.uenfEventRouter.components.get('menu');
      

      if (menuComponent) {
        // Menu registrado corretamente
      } else {
        console.warn('[Menu] O componente de menu não foi registrado corretamente no roteador de eventos');
      }
    }
  });
}
})(jQuery);
