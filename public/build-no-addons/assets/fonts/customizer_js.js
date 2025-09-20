/**
 * Script para visualização em tempo real das alterações de tipografia
 * Versão melhorada com suporte a Google Fonts e CSS Variables
 */
(function($) {
    'use strict';
    
    // Função para construir stack de fontes
    function buildFontStack(fontFamily) {
        var systemFonts = ['system-ui', 'Arial', 'Helvetica', 'Georgia', 'Times New Roman'];
        
        if (systemFonts.indexOf(fontFamily) === -1) {
            return '"' + fontFamily + '", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        } else {
            return fontFamily + ', sans-serif';
        }
    }
    
    // Função para carregar Google Fonts dinamicamente
    function loadGoogleFont(fontFamily) {
        var googleFonts = {
            'Ubuntu': 'Ubuntu:300,400,500,600,700',
            'Roboto': 'Roboto:300,400,500,600,700',
            'Open Sans': 'Open+Sans:300,400,500,600,700',
            'Lato': 'Lato:300,400,500,600,700',
            'Montserrat': 'Montserrat:300,400,500,600,700',
            'Source Sans Pro': 'Source+Sans+Pro:300,400,500,600,700',
            'Oswald': 'Oswald:300,400,500,600,700',
            'Raleway': 'Raleway:300,400,500,600,700',
            'PT Sans': 'PT+Sans:400,700',
            'Nunito': 'Nunito:300,400,500,600,700'
        };
        
        if (googleFonts[fontFamily]) {
            // Remove fonte anterior se existir
            $('#cct-preview-google-font').remove();
            
            // Adiciona nova fonte
            var fontUrl = 'https://fonts.googleapis.com/css2?family=' + googleFonts[fontFamily] + '&display=swap';
            $('<link>', {
                id: 'cct-preview-google-font',
                rel: 'stylesheet',
                href: fontUrl
            }).appendTo('head');
        }
    }
    
    // Função para atualizar CSS Variables
    function updateCSSVariable(property, value, unit) {
        unit = unit || '';
        document.documentElement.style.setProperty('--cct-' + property, value + unit);
    }
    
    // Família de fonte
    wp.customize('typography_font_family', function(value) {
        value.bind(function(newval) {
            loadGoogleFont(newval);
            var fontStack = buildFontStack(newval);
            updateCSSVariable('font-family', fontStack);
            
            // Aplica diretamente também para compatibilidade
            var targets = 'body, button, input, select, textarea, .site-content, .entry-content, .page-content, .comment-content';
            $(targets).css('font-family', fontStack);
        });
    });
    
    // Tamanho da fonte do corpo
    wp.customize('typography_body_size', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('font-size', newval, 'px');
            
            var targets = 'body, button, input, select, textarea, .site-content, .entry-content, .page-content, .comment-content';
            $(targets).css('font-size', newval + 'px');
        });
    });
    
    // Peso da fonte do corpo
    wp.customize('typography_body_weight', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('font-weight', newval);
            
            var targets = 'body, button, input, select, textarea, .site-content, .entry-content, .page-content, .comment-content';
            $(targets).css('font-weight', newval);
        });
    });
    
    // Altura da linha
    wp.customize('typography_line_height', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('line-height', newval);
            
            var targets = 'body, button, input, select, textarea, .site-content, .entry-content, .page-content, .comment-content';
            $(targets).css('line-height', newval);
        });
    });
    
    // Espaçamento entre letras
    wp.customize('typography_letter_spacing', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('letter-spacing', newval, 'px');
            
            var targets = 'body, button, input, select, textarea, .site-content, .entry-content, .page-content, .comment-content';
            $(targets).css('letter-spacing', newval + 'px');
        });
    });
    
    // Sincronização entre controles de tamanho
    wp.customize('typography_body_size_input', function(value) {
        value.bind(function(newval) {
            if (wp.customize('typography_body_size')) {
                wp.customize('typography_body_size').set(newval);
            }
        });
    });
    
    // Sincronização entre controles de tamanho de fonte base
    wp.customize('typography_font_size_base_input', function(value) {
        value.bind(function(newval) {
            if (wp.customize('typography_font_size_base')) {
                wp.customize('typography_font_size_base').set(newval);
            }
        });
    });
    
    // Sincronização entre controles de espaçamento entre letras
    wp.customize('typography_letter_spacing_input', function(value) {
        value.bind(function(newval) {
            if (wp.customize('typography_letter_spacing')) {
                wp.customize('typography_letter_spacing').set(newval);
            }
        });
    });
    
    // H1 Tamanho
    wp.customize('typography_h1_size', function(value) {
        value.bind(function(newval) {
            $('h1, .h1').css('font-size', newval + 'px');
        });
    });
    
    // H1 Peso
    wp.customize('typography_h1_weight', function(value) {
        value.bind(function(newval) {
            $('h1, .h1').css('font-weight', newval);
        });
    });
    
    // Configurações de cabeçalhos adicionais
    var headings = ['h2', 'h3', 'h4', 'h5', 'h6'];
    headings.forEach(function(heading) {
        wp.customize('typography_' + heading + '_size', function(value) {
            value.bind(function(newval) {
                $(heading).css('font-size', newval + 'px');
            });
        });
    });
    
    // Melhora a renderização de fontes
    $('body').css({
        '-webkit-font-smoothing': 'antialiased',
        '-moz-osx-font-smoothing': 'grayscale',
        'text-rendering': 'optimizeLegibility'
    });
    
})(jQuery);
