/**
 * Script para visualização em tempo real das alterações de tipografia no site
 */
(function($) {
    // Família de fonte
    wp.customize('typography_font_family', function(value) {
        value.bind(function(newval) {
            $('body, button, input, select, textarea').css('font-family', newval + ', sans-serif');
        });
    });
    
    // Tamanho da fonte do corpo (controle deslizante)
    wp.customize('typography_body_size', function(value) {
        value.bind(function(newval) {
            $('body, button, input, select, textarea').css('font-size', newval + 'px');
        });
    });
    
    // Tamanho da fonte do corpo (campo de entrada)
    wp.customize('typography_body_size_input', function(value) {
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
    
})(jQuery);