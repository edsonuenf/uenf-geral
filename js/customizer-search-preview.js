/**
 * Preview em Tempo Real - Sistema de Busca
 * 
 * Atualiza os estilos da busca em tempo real no customizer
 * 
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Verificar se wp.customize está disponível
    if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
        console.warn('WordPress Customizer API not available');
        return;
    }
    
    // Aguardar carregamento do customizer
    wp.customize('cct_search_button_color', function(value) {
        value.bind(function(newval) {
            $('input[type="submit"].search-custom-uenf').css('background-color', newval);
            $('button[type="submit"].search-custom-uenf').css('background-color', newval);
            $('.search-submit.search-custom-uenf').css('background-color', newval);
        });
    });
    
    wp.customize('cct_search_button_border_color', function(value) {
        value.bind(function(newval) {
            $('input[type="submit"].search-custom-uenf').css('border-color', newval);
            $('button[type="submit"].search-custom-uenf').css('border-color', newval);
            $('.search-submit.search-custom-uenf').css('border-color', newval);
        });
    });
    
    wp.customize('cct_search_button_hover_color', function(value) {
        value.bind(function(newval) {
            // Criar/atualizar regra CSS para hover
            updateHoverCSS('input[type="submit"].search-custom-uenf:hover', {
                'background-color': newval
            });
            updateHoverCSS('button[type="submit"].search-custom-uenf:hover', {
                'background-color': newval
            });
            updateHoverCSS('.search-submit.search-custom-uenf:hover', {
                'background-color': newval
            });
        });
    });
    
    wp.customize('cct_search_border_color', function(value) {
        value.bind(function(newval) {
            $('input[type="search"].search-custom-uenf').css('border-color', newval);
        });
    });
    
    // Configurações do campo de texto
    wp.customize('cct_search_field_bg_color', function(value) {
        value.bind(function(newval) {
            $('input[type="search"].search-custom-uenf').css('background-color', newval);
        });
    });
    
    wp.customize('cct_search_field_text_color', function(value) {
        value.bind(function(newval) {
            $('input[type="search"].search-custom-uenf').css('color', newval);
        });
    });
    
    wp.customize('cct_search_field_placeholder_color', function(value) {
        value.bind(function(newval) {
            // Atualizar cor do placeholder via CSS
            updatePlaceholderCSS('input[type="search"].search-custom-uenf', newval);
        });
    });
    
    wp.customize('cct_search_field_focus_color', function(value) {
        value.bind(function(newval) {
            // Atualizar cor de foco via CSS
            updateFocusCSS('input[type="search"].search-custom-uenf', newval);
        });
    });
    
    // Variáveis para armazenar valores e unidades
    var paddingVerticalValue = wp.customize('cct_search_padding_vertical')();
    var paddingVerticalUnit = wp.customize('cct_search_padding_vertical_unit')();
    
    function updatePaddingVertical() {
        var value = paddingVerticalValue + paddingVerticalUnit;
        $('input[type="search"].search-custom-uenf').css('padding-top', value);
        $('input[type="search"].search-custom-uenf').css('padding-bottom', value);
        $('input[type="submit"].search-custom-uenf').css('padding-top', value);
        $('input[type="submit"].search-custom-uenf').css('padding-bottom', value);
        $('.search-submit.search-custom-uenf').css('padding-top', value);
        $('.search-submit.search-custom-uenf').css('padding-bottom', value);
    }
    
    wp.customize('cct_search_padding_vertical', function(value) {
        value.bind(function(newval) {
            paddingVerticalValue = newval;
            updatePaddingVertical();
        });
    });
    
    wp.customize('cct_search_padding_vertical_unit', function(value) {
        value.bind(function(newval) {
            paddingVerticalUnit = newval;
            updatePaddingVertical();
        });
    });
    
    // Variáveis para largura máxima
    var maxWidthValue = wp.customize('cct_search_max_width')();
    var maxWidthUnit = wp.customize('cct_search_max_width_unit')();
    
    function updateMaxWidth() {
        var value = maxWidthValue + maxWidthUnit;
        $('.custom-search-form.search-custom-uenf').css('max-width', value);
    }
    
    wp.customize('cct_search_max_width', function(value) {
        value.bind(function(newval) {
            maxWidthValue = newval;
            updateMaxWidth();
        });
    });
    
    wp.customize('cct_search_max_width_unit', function(value) {
        value.bind(function(newval) {
            maxWidthUnit = newval;
            updateMaxWidth();
        });
    });
    
    wp.customize('cct_search_border_width', function(value) {
        value.bind(function(newval) {
            $('input[type="search"].search-custom-uenf').css('border-width', newval + 'px');
        });
    });
    
    wp.customize('cct_search_button_border_width', function(value) {
        value.bind(function(newval) {
            $('input[type="submit"].search-custom-uenf').css('border-width', newval + 'px');
            $('button[type="submit"].search-custom-uenf').css('border-width', newval + 'px');
            $('.search-submit.search-custom-uenf').css('border-width', newval + 'px');
        });
    });
    
    // Border radius individuais - Campo de busca
    var fieldBorderRadiusValues = {
        topLeft: wp.customize('cct_search_border_radius_top_left')(),
        topRight: wp.customize('cct_search_border_radius_top_right')(),
        bottomLeft: wp.customize('cct_search_border_radius_bottom_left')(),
        bottomRight: wp.customize('cct_search_border_radius_bottom_right')()
    };
    
    function updateFieldBorderRadius() {
        var borderRadius = fieldBorderRadiusValues.topLeft + 'px ' + 
                          fieldBorderRadiusValues.topRight + 'px ' + 
                          fieldBorderRadiusValues.bottomRight + 'px ' + 
                          fieldBorderRadiusValues.bottomLeft + 'px';
        $('input[type="search"].search-custom-uenf').css('border-radius', borderRadius);
    }
    
    wp.customize('cct_search_border_radius_top_left', function(value) {
        value.bind(function(newval) {
            fieldBorderRadiusValues.topLeft = newval;
            updateFieldBorderRadius();
        });
    });
    
    wp.customize('cct_search_border_radius_top_right', function(value) {
        value.bind(function(newval) {
            fieldBorderRadiusValues.topRight = newval;
            updateFieldBorderRadius();
        });
    });
    
    wp.customize('cct_search_border_radius_bottom_left', function(value) {
        value.bind(function(newval) {
            fieldBorderRadiusValues.bottomLeft = newval;
            updateFieldBorderRadius();
        });
    });
    
    wp.customize('cct_search_border_radius_bottom_right', function(value) {
        value.bind(function(newval) {
            fieldBorderRadiusValues.bottomRight = newval;
            updateFieldBorderRadius();
        });
    });
    
    // Border radius individuais - Botão de busca
    var buttonBorderRadiusValues = {
        topLeft: wp.customize('cct_search_button_border_radius_top_left')(),
        topRight: wp.customize('cct_search_button_border_radius_top_right')(),
        bottomLeft: wp.customize('cct_search_button_border_radius_bottom_left')(),
        bottomRight: wp.customize('cct_search_button_border_radius_bottom_right')()
    };
    
    function updateButtonBorderRadius() {
        var borderRadius = buttonBorderRadiusValues.topLeft + 'px ' + 
                          buttonBorderRadiusValues.topRight + 'px ' + 
                          buttonBorderRadiusValues.bottomRight + 'px ' + 
                          buttonBorderRadiusValues.bottomLeft + 'px';
        $('input[type="submit"].search-custom-uenf').css('border-radius', borderRadius);
        $('button[type="submit"].search-custom-uenf').css('border-radius', borderRadius);
        $('.search-submit.search-custom-uenf').css('border-radius', borderRadius);
    }
    
    wp.customize('cct_search_button_border_radius_top_left', function(value) {
        value.bind(function(newval) {
            buttonBorderRadiusValues.topLeft = newval;
            updateButtonBorderRadius();
        });
    });
    
    wp.customize('cct_search_button_border_radius_top_right', function(value) {
        value.bind(function(newval) {
            buttonBorderRadiusValues.topRight = newval;
            updateButtonBorderRadius();
        });
    });
    
    wp.customize('cct_search_button_border_radius_bottom_left', function(value) {
        value.bind(function(newval) {
            buttonBorderRadiusValues.bottomLeft = newval;
            updateButtonBorderRadius();
        });
    });
    
    wp.customize('cct_search_button_border_radius_bottom_right', function(value) {
        value.bind(function(newval) {
            buttonBorderRadiusValues.bottomRight = newval;
            updateButtonBorderRadius();
        });
    });
    
    // Variáveis para tamanho da fonte
    var fontSizeValue = wp.customize('cct_search_font_size')();
    var fontSizeUnit = wp.customize('cct_search_font_size_unit')();
    
    function updateFontSize() {
        var value = fontSizeValue + fontSizeUnit;
        $('input.search-custom-uenf').css('font-size', value);
        $('button[type="submit"].search-custom-uenf').css('font-size', value);
        $('.search-submit.search-custom-uenf').css('font-size', value);
    }
    
    wp.customize('cct_search_font_size', function(value) {
        value.bind(function(newval) {
            fontSizeValue = newval;
            updateFontSize();
        });
    });
    
    wp.customize('cct_search_font_size_unit', function(value) {
        value.bind(function(newval) {
            fontSizeUnit = newval;
            updateFontSize();
        });
    });
    
    wp.customize('cct_search_box_shadow', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('form.search-custom-uenf').css('box-shadow', '0 2px 10px rgba(0, 0, 0, 0.1)');
            } else {
                $('form.search-custom-uenf').css('box-shadow', 'none');
            }
        });
    });
    
    wp.customize('cct_search_transitions', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('input.search-custom-uenf').css('transition', 'all 0.3s ease');
                $('button[type="submit"].search-custom-uenf').css('transition', 'all 0.3s ease');
                $('.search-submit.search-custom-uenf').css('transition', 'all 0.3s ease');
            } else {
                $('input.search-custom-uenf').css('transition', 'none');
                $('button[type="submit"].search-custom-uenf').css('transition', 'none');
                $('.search-submit.search-custom-uenf').css('transition', 'none');
            }
        });
    });
    
    wp.customize('cct_search_custom_css', function(value) {
        value.bind(function(newval) {
            // Remover CSS personalizado anterior
            $('#cct-search-custom-css').remove();
            
            // Adicionar novo CSS personalizado
            if (newval) {
                $('head').append('<style id="cct-search-custom-css">' + newval + '</style>');
            }
        });
    });
    
    /**
     * Função auxiliar para atualizar CSS de placeholder
     */
    function updatePlaceholderCSS(selector, color) {
        // Remover regra anterior
        $('#cct-search-placeholder-css').remove();
        
        // Criar nova regra CSS para placeholder
        var css = selector + '::placeholder { color: ' + color + ' !important; }';
        css += selector + '::-webkit-input-placeholder { color: ' + color + ' !important; }';
        css += selector + '::-moz-placeholder { color: ' + color + ' !important; }';
        css += selector + ':-ms-input-placeholder { color: ' + color + ' !important; }';
        
        $('head').append('<style id="cct-search-placeholder-css">' + css + '</style>');
    }
    
    /**
     * Função auxiliar para atualizar CSS de foco
     */
    function updateFocusCSS(selector, color) {
        // Remover regra anterior
        $('#cct-search-focus-css').remove();
        
        // Converter cor hex para RGB
        var rgb = hexToRgb(color);
        var rgbaColor = rgb ? 'rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ', 0.2)' : 'rgba(29, 55, 113, 0.2)';
        
        // Criar nova regra CSS para foco
        var css = selector + ':focus { ';
        css += 'border-color: ' + color + ' !important; ';
        css += 'outline: none !important; ';
        css += 'box-shadow: 0 0 0 2px ' + rgbaColor + ' !important; ';
        css += '}';
        
        $('head').append('<style id="cct-search-focus-css">' + css + '</style>');
    }
    
    /**
     * Função auxiliar para converter hex para RGB
     */
    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
    
    /**
     * Função auxiliar para atualizar CSS de hover
     */
    function updateHoverCSS(selector, styles) {
        // Remover regra anterior
        $('#cct-search-hover-css').remove();
        
        // Criar nova regra CSS
        var css = selector + ' {';
        for (var property in styles) {
            css += property + ': ' + styles[property] + ' !important;';
        }
        css += '}';
        
        // Adicionar ao head
        $('head').append('<style id="cct-search-hover-css">' + css + '</style>');
    }
    
    /**
     * Inicialização
     */
    $(document).ready(function() {
        // Adicionar classe para identificar preview
        $('body').addClass('customizer-search-preview');
        
        // Inicializar funções
        updateBorderRadius();
        updateButtonBorderRadius();
        
        // Log para debug
        console.log('CCT Search Customizer Preview: Inicializado');
    });
    
})(jQuery);