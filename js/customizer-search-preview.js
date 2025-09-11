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
    
    // Aguardar carregamento do customizer
    wp.customize('cct_search_button_color', function(value) {
        value.bind(function(newval) {
            $('.search-container input[type="submit"]').css('background-color', newval);
            $('.search-container input[type="submit"]').css('border-color', newval);
        });
    });
    
    wp.customize('cct_search_button_hover_color', function(value) {
        value.bind(function(newval) {
            // Criar/atualizar regra CSS para hover
            updateHoverCSS('.search-container input[type="submit"]:hover', {
                'background-color': newval,
                'border-color': newval
            });
        });
    });
    
    wp.customize('cct_search_border_color', function(value) {
        value.bind(function(newval) {
            $('.search-container input[type="search"]').css('border-color', newval);
        });
    });
    
    wp.customize('cct_search_padding_vertical', function(value) {
        value.bind(function(newval) {
            var padding = newval + 'px 12px';
            $('.search-container input[type="search"], .search-container input[type="submit"]').css('padding', padding);
        });
    });
    
    wp.customize('cct_search_max_width', function(value) {
        value.bind(function(newval) {
            $('.search-container').css('max-width', newval + 'px');
        });
    });
    
    wp.customize('cct_search_border_radius', function(value) {
        value.bind(function(newval) {
            $('.search-container input[type="search"]').css('border-radius', newval + 'px 0 0 ' + newval + 'px');
            $('.search-container input[type="submit"]').css('border-radius', '0 ' + newval + 'px ' + newval + 'px 0');
        });
    });
    
    wp.customize('cct_search_font_size', function(value) {
        value.bind(function(newval) {
            $('.search-container input').css('font-size', newval + 'px');
        });
    });
    
    wp.customize('cct_search_box_shadow', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.search-container form').css('box-shadow', '0 2px 10px rgba(0, 0, 0, 0.1)');
            } else {
                $('.search-container form').css('box-shadow', 'none');
            }
        });
    });
    
    wp.customize('cct_search_transitions', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.search-container input').css('transition', 'all 0.3s ease');
            } else {
                $('.search-container input').css('transition', 'none');
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
        
        // Log para debug
        console.log('CCT Search Customizer Preview: Inicializado');
    });
    
})(jQuery);
