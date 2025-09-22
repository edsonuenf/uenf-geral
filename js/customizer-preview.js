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
                    // Mostrar ícones de hierarquia
                    $('<style id="hierarchy-icons-style">.new-menu .sub-menu a::before, .new-menu .children a::before { content: "→" !important; display: block !important; } .new-menu .sub-menu .sub-menu a::before, .new-menu .children .children a::before { content: "▸" !important; } .new-menu .sub-menu .sub-menu .sub-menu a::before, .new-menu .children .children .children a::before { content: "•" !important; }</style>').appendTo('head');
                } else {
                    // Ocultar ícones de hierarquia
                    $('#hierarchy-icons-style').remove();
                    $('<style id="hierarchy-icons-style">.new-menu .sub-menu a::before, .new-menu .children a::before { display: none !important; }</style>').appendTo('head');
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
     * Preview para botões de formulário
     */
    function bindFormButtonPreviews() {
        // Cor de fundo do botão
        wp.customize('form_button_bg_color', function(value) {
            value.bind(function(newval) {
                $('head').find('#cct-form-button-bg-color').remove();
                $('head').append('<style id="cct-form-button-bg-color">' +
                    '.btn-submit-uenf, .btn-form-uenf, button[type="submit"].btn-uenf, ' +
                    '.wp-block-button__link, .wpcf7-submit { ' +
                    'background-color: ' + newval + ' !important; }' +
                    '</style>');
            });
        });

        // Cor do texto do botão
        wp.customize('form_button_text_color', function(value) {
            value.bind(function(newval) {
                $('head').find('#cct-form-button-text-color').remove();
                $('head').append('<style id="cct-form-button-text-color">' +
                    '.btn-submit-uenf, .btn-form-uenf, button[type="submit"].btn-uenf, ' +
                    '.wp-block-button__link, .wpcf7-submit { ' +
                    'color: ' + newval + ' !important; }' +
                    '</style>');
            });
        });

        // Cor de fundo hover do botão
        wp.customize('form_button_bg_hover_color', function(value) {
            value.bind(function(newval) {
                $('head').find('#cct-form-button-bg-hover-color').remove();
                $('head').append('<style id="cct-form-button-bg-hover-color">' +
                    '.btn-submit-uenf:hover, .btn-form-uenf:hover, button[type="submit"].btn-uenf:hover, ' +
                    '.wp-block-button__link:hover, .wpcf7-submit:hover { ' +
                    'background-color: ' + newval + ' !important; }' +
                    '</style>');
            });
        });

        // Raio da borda do botão
        wp.customize('form_button_border_radius', function(value) {
            value.bind(function(newval) {
                $('head').find('#cct-form-button-border-radius').remove();
                $('head').append('<style id="cct-form-button-border-radius">' +
                    '.btn-submit-uenf, .btn-form-uenf, button[type="submit"].btn-uenf, ' +
                    '.wp-block-button__link, .wpcf7-submit { ' +
                    'border-radius: ' + newval + ' !important; }' +
                    '</style>');
            });
        });

        // Padding do botão
        wp.customize('form_button_padding', function(value) {
            value.bind(function(newval) {
                $('head').find('#cct-form-button-padding').remove();
                $('head').append('<style id="cct-form-button-padding">' +
                    '.btn-submit-uenf, .btn-form-uenf, button[type="submit"].btn-uenf, ' +
                    '.wp-block-button__link, .wpcf7-submit { ' +
                    'padding: ' + newval + ' !important; }' +
                    '</style>');
            });
        });
    }

    /**
     * Função para preview de botões do WordPress Block Editor
     */
    function bindBlockButtonPreviews() {
        // Background color
        wp.customize('form_button_bg_color', function(value) {
            value.bind(function(newval) {
                $('.wp-block-button .wp-block-button__link').css('background-color', newval);
            });
        });

        // Text color
        wp.customize('form_button_text_color', function(value) {
            value.bind(function(newval) {
                $('.wp-block-button .wp-block-button__link').css('color', newval);
            });
        });

        // Hover background color
        wp.customize('form_button_hover_bg_color', function(value) {
            value.bind(function(newval) {
                updateHoverStyles('.wp-block-button .wp-block-button__link', 'background-color', newval);
            });
        });

        // Hover text color
        wp.customize('form_button_hover_text_color', function(value) {
            value.bind(function(newval) {
                updateHoverStyles('.wp-block-button .wp-block-button__link', 'color', newval);
            });
        });

        // Border radius
        wp.customize('form_button_border_radius', function(value) {
            value.bind(function(newval) {
                $('.wp-block-button .wp-block-button__link').css('border-radius', newval + 'px');
            });
        });

        // Padding
        wp.customize('form_button_padding', function(value) {
            value.bind(function(newval) {
                $('.wp-block-button .wp-block-button__link').css('padding', newval);
            });
        });

        // Border width
        wp.customize('form_button_border_width', function(value) {
            value.bind(function(newval) {
                $('.wp-block-button .wp-block-button__link').css('border-width', newval + 'px');
            });
        });

        // Border color
        wp.customize('form_button_border_color', function(value) {
            value.bind(function(newval) {
                $('.wp-block-button .wp-block-button__link').css('border-color', newval);
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
        bindFormButtonPreviews();
        
        // Bind WordPress Block Editor button previews
        bindBlockButtonPreviews();
        
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