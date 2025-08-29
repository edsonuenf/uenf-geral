/**
 * Main theme JavaScript file
 */

(function($) {
    'use strict';

    // Mobile menu toggle
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const mainNav = document.querySelector('.main-navigation');

        if (menuToggle && mainNav) {
            menuToggle.addEventListener('click', function() {
                mainNav.classList.toggle('toggled');
                if (mainNav.classList.contains('toggled')) {
                    menuToggle.setAttribute('aria-expanded', 'true');
                } else {
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    }


    // Função para fechar um submenu e todos os seus filhos
    function closeSubmenuAndChildren(element) {
        if (!element) return;
        
        // Remove a classe ativa do item
        element.classList.remove('menu-item-active');
        
        // Fecha o submenu direto
        const submenu = element.querySelector('.sub-menu') || element.querySelector('.children');
        if (submenu) {
            submenu.classList.remove('show');
        }
        
        // Remove a rotação da seta (seletor corrigido)
        const toggle = element.querySelector('.submenu-toggle');
        if (toggle) {
            toggle.classList.remove('rotated');
        }
        
        // Fecha todos os itens filhos recursivamente
        const children = element.querySelectorAll('.menu-item-has-children, .page_item_has_children');
        children.forEach(child => closeSubmenuAndChildren(child));
    }
    
    // Função para verificar se um elemento tem submenu aberto
    function hasOpenSubmenu(element) {
        if (!element) return false;
        const submenu = element.querySelector('.sub-menu.show') || element.querySelector('.children.show');
        return submenu !== null;
    }
    
    // Função para fechar todos os submenus
    function closeAllSubmenus() {
        document.querySelectorAll('.menu-item-has-children, .page_item_has_children').forEach(item => {
            item.classList.remove('menu-item-active');
            // Usando seletores separados para evitar problemas de sintaxe
            const submenu = item.querySelector('.sub-menu') || item.querySelector('.children');
            if (submenu) submenu.classList.remove('show');
            const toggle = item.querySelector('.submenu-toggle');
            if (toggle) {
                toggle.classList.remove('rotated');
                // Garante que a seta aponte para baixo
                toggle.classList.remove('fa-chevron-right');
                toggle.classList.add('fa-chevron-down');
            }
        });
    }
    
    // Função para implementar comportamento accordion (fecha outros itens do mesmo nível)
    function closeOtherMenuItems(currentItem, container) {
        const selector = container === 'top-level' ? 
            '.new-menu > .menu-item-has-children, .new-menu > .page_item_has_children' :
            '.menu-item-has-children, .page_item_has_children';
            
        const items = container === 'top-level' ? 
            document.querySelectorAll(selector) :
            Array.from(currentItem.parentElement.children);
            
        items.forEach(item => {
            if (item !== currentItem && (item.classList.contains('menu-item-has-children') || item.classList.contains('page_item_has_children'))) {
                closeSubmenuAndChildren(item);
            }
        });
    }
    
    // Submenu active state
    function initSubmenuActive() {
        // Remove ícones duplicados que possam ter sido adicionados pelo WordPress
        document.querySelectorAll('.menu-item-has-children a .submenu-toggle, .page_item_has_children a .submenu-toggle').forEach(icon => {
            icon.remove();
        });
        
        // Adiciona o ícone de seta para itens com submenu
        document.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a').forEach(link => {
            // Verifica se já tem um ícone
            if (!link.querySelector('.submenu-toggle')) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-chevron-down submenu-toggle';
                link.appendChild(icon);
            }
        });
        
        // Fecha todos os menus ao carregar a página
        closeAllSubmenus();    
        
        const menuItems = document.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');
        
        menuItems.forEach(item => {
            // Adiciona evento de clique
            item.addEventListener('click', function(e) {
                const submenu = this.nextElementSibling;
                const parentLi = this.parentElement;
                const isSubmenu = parentLi.parentElement.classList.contains('sub-menu') || 
                                 parentLi.parentElement.classList.contains('children');
                
                // Verifica se o clique foi no link ou na seta
                const isClickOnToggle = e.target.closest('.submenu-toggle') !== null;
                const isClickOnLink = e.target === this || e.target.tagName === 'A';
                
                // Só processa se foi clique válido
                if (isClickOnLink || isClickOnToggle) {
                    // Verifica se o item já está ativo
                    const isActive = parentLi.classList.contains('menu-item-active');
                    
                    if (!isSubmenu) {
                        // Para itens de primeiro nível: FECHA TUDO PRIMEIRO (abordagem radical)
                        document.querySelectorAll('.menu-item-active').forEach(activeItem => {
                            activeItem.classList.remove('menu-item-active');
                        });
                        document.querySelectorAll('.sub-menu.show, .children.show').forEach(openSubmenu => {
                            openSubmenu.classList.remove('show');
                        });
                        document.querySelectorAll('.submenu-toggle.rotated').forEach(rotatedToggle => {
                            rotatedToggle.classList.remove('rotated');
                        });
                        
                        // Se não estava ativo, abre este item
                        if (!isActive) {
                            parentLi.classList.add('menu-item-active');
                            if (submenu) submenu.classList.add('show');
                            const toggle = this.querySelector('.submenu-toggle');
                            if (toggle) toggle.classList.add('rotated');
                        }
                    } else {
                        // Para submenus, fecha apenas irmãos do mesmo nível
                        const parentMenu = parentLi.parentElement;
                        const siblings = parentMenu.querySelectorAll(':scope > .menu-item-has-children, :scope > .page_item_has_children');
                        siblings.forEach(item => {
                            if (item !== parentLi) {
                                closeSubmenuAndChildren(item);
                            }
                        });
                        
                        // Toggle do submenu
                        if (!isActive) {
                            parentLi.classList.add('menu-item-active');
                            if (submenu) submenu.classList.add('show');
                            const toggle = this.querySelector('.submenu-toggle');
                            if (toggle) toggle.classList.add('rotated');
                        } else {
                            closeSubmenuAndChildren(parentLi);
                        }
                    }
                }
                
                e.preventDefault();
                e.stopPropagation();
            });
        });
    }

    // Smooth scroll to anchors
    function initSmoothScroll() {
        $('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                    return false;
                }
            }
        });
    }

    // Sticky header
    function initStickyHeader() {
        const header = $('.site-header');
        const headerHeight = header.outerHeight();
        let lastScroll = 0;

        $(window).scroll(function() {
            const currentScroll = $(this).scrollTop();

            if (currentScroll > headerHeight) {
                if (currentScroll > lastScroll) {
                    header.addClass('header-hidden');
                } else {
                    header.removeClass('header-hidden').addClass('header-sticky');
                }
            } else {
                header.removeClass('header-sticky header-hidden');
            }

            lastScroll = currentScroll;
        });
    }

    // Back to top button
    function initBackToTop() {
        const backToTop = $('<button>', {
            class: 'back-to-top',
            html: '<span class="screen-reader-text">Back to top</span>'
        }).appendTo('body');

        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                backToTop.addClass('show');
            } else {
                backToTop.removeClass('show');
            }
        });

        backToTop.click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    }

    // Form validation
    function initFormValidation() {
        $('form').each(function() {
            $(this).on('submit', function(e) {
                let isValid = true;
                $(this).find('input[required], textarea[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('error');
                    } else {
                        $(this).removeClass('error');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    }

    // Responsive tables
    function initResponsiveTables() {
        $('table').wrap('<div class="table-responsive"></div>');
    }

    // Initialize all functions when DOM is ready
    $(document).ready(function() {
        initMobileMenu();
        initSmoothScroll();
        initStickyHeader();
        initBackToTop();
        initFormValidation();
        initResponsiveTables();
        initSubmenuActive(); // Inicializa o gerenciamento de submenus ativos
    });

    // Handle images loading
    $(window).on('load', function() {
        $('.entry-content img').each(function() {
            $(this).addClass('loaded');
        });
    });

})(jQuery);