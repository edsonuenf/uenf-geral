/**
 * Script para visualização em tempo real das alterações de tipografia
 */
(function($) {
    // Família de fonte
    wp.customize('typography_font_family', function(value) {
        value.bind(function(newval) {
            $('body, button, input, select, textarea').css('font-family', newval + ', sans-serif');
        });
    });
    
    // Tamanho da fonte do corpo
    wp.customize('typography_body_size', function(value) {
        value.bind(function(newval) {
            $('body, button, input, select, textarea').css('font-size', newval + 'px');
        });
    });
    
    // Peso da fonte do corpo
    wp.customize('typography_body_weight', function(value) {
        value.bind(function(newval) {
            $('body, button, input, select, textarea').css('font-weight', newval);
        });
    });
    
    // Altura da linha
    wp.customize('typography_line_height', function(value) {
        value.bind(function(newval) {
            $('body, button, input, select, textarea').css('line-height', newval);
        });
    });
    
    // Espaçamento entre letras
    wp.customize('typography_letter_spacing', function(value) {
        value.bind(function(newval) {
            $('body, button, input, select, textarea').css('letter-spacing', newval + 'px');
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
    
    // Você pode adicionar mais elementos como h2-h6 seguindo o mesmo padrão
    
})(jQuery);
