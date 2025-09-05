/**
 * Customizer Preview JavaScript
 * Melhora o preview em tempo real sem necessidade de reload
 */

(function($) {
    'use strict';

    // Aguardar o carregamento do customizer
    wp.customize = wp.customize || {};

    /**
     * Preview em tempo real para cores
     */
    function bindColorPreview(setting, selector, property = 'background-color') {
        wp.customize(setting, function(value) {
            value.bind(function(newval) {
                $(selector).css(property, newval);
            });
        });
    }

    /**
     * Preview em tempo real para texto
     */
    function bindTextPreview(setting, selector) {
        wp.customize(setting, function(value) {
            value.bind(function(newval) {
                $(selector).text(newval);
            });
        });
    }

    /**
     * Preview em tempo real para CSS customizado
     */
    function bindCSSPreview(setting, cssFunction) {
        wp.customize(setting, function(value) {
            value.bind(function(newval) {
                // Remove CSS anterior
                $('#cct-dynamic-css').remove();
                
                // Adiciona novo CSS
                var css = cssFunction(newval);
                if (css) {
                    $('head').append('<style id="cct-dynamic-css">' + css + '</style>');
                }
            });
        });
    }

    /**
     * Preview para estilo do menu
     */
    function bindMenuStylePreview() {
        wp.customize('menu_style', function(value) {
            value.bind(function(newval) {
                // Remove classes de estilo anteriores
                $('body').removeClass('menu-style-modern menu-style-classic menu-style-minimal');
                
                // Adiciona nova classe
                $('body').addClass('menu-style-' + newval);
                
                // Aplica CSS específico baseado no estilo
                var css = '';
                switch(newval) {
                    case 'classic':
                        css = `
                            .navbar-nav, .new-menu {
                                background: #1d3771 !important;
                            }
                            .navbar-nav .dropdown-menu, .new-menu .sub-menu {
                                background: #1d3771 !important;
                                border-left: 3px solid #ffffff !important;
                                box-shadow: none !important;
                                backdrop-filter: none !important;
                            }
                            .navbar-nav * {
                                transition: none !important;
                                animation: none !important;
                            }
                        `;
                        break;
                    case 'minimal':
                        css = `
                            .navbar-nav, .new-menu {
                                background: rgba(29, 55, 113, 0.8) !important;
                            }
                            .navbar-nav .dropdown-menu, .new-menu .sub-menu {
                                background: rgba(29, 55, 113, 0.9) !important;
                                border-left: 1px solid rgba(255, 255, 255, 0.3) !important;
                            }
                        `;
                        break;
                    default: // modern
                        css = '';
                        break;
                }
                
                // Remove CSS anterior e adiciona novo
                $('#cct-menu-style-css').remove();
                if (css) {
                    $('head').append('<style id="cct-menu-style-css">' + css + '</style>');
                }
            });
        });
    }

    /**
     * Preview para ícones de hierarquia
     */
    function bindHierarchyIconsPreview() {
        wp.customize('menu_show_hierarchy_icons', function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('.submenu-toggle').show();
                } else {
                    $('.submenu-toggle').hide();
                }
            });
        });
    }

    /**
     * Preview para largura do painel
     */
    function bindPanelWidthPreview() {
        wp.customize('shortcut_panel_width', function(value) {
            value.bind(function(newval) {
                $('.shortcut-panel, .offcanvas').css('width', newval);
            });
        });
    }

    /**
     * Inicializar todos os previews
     */
    function initializePreviews() {
        // Cores do painel de atalhos
        bindColorPreview('shortcut_button_bg', '.shortcut-button', 'background-color');
        bindColorPreview('shortcut_button_icon_color', '.shortcut-button i', 'color');
        bindColorPreview('shortcut_panel_bg', '.shortcut-panel, .offcanvas', 'background-color');
        bindColorPreview('shortcut_header_bg', '.shortcut-header, .offcanvas-header', 'background-color');
        bindColorPreview('shortcut_header_text_color', '.shortcut-header, .offcanvas-header', 'color');
        bindColorPreview('shortcut_close_button_bg', '.btn-close', 'background-color');
        bindColorPreview('shortcut_close_button_color', '.btn-close', 'color');
        
        // Funcionalidades específicas
        bindMenuStylePreview();
        bindHierarchyIconsPreview();
        bindPanelWidthPreview();
        
        // Log para debug
        console.log('CCT Customizer Preview: Initialized');
    }

    /**
     * Aguardar carregamento completo
     */
    $(document).ready(function() {
        // Aguardar um pouco para garantir que o customizer está carregado
        setTimeout(initializePreviews, 500);
    });

})(jQuery);