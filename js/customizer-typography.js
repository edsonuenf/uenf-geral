/**
 * Script para visualização em tempo real das alterações de tipografia no site
 * Inclui suporte a font pairing presets e preview avançado
 */
(function($) {
    'use strict';
    
    // Aguarda o customizer estar pronto
    $(document).ready(function() {
        if (typeof wp !== "undefined" && wp.customize) {
            
            // Função para aplicar tipografia no preview
            function applyTypographyPreview(newval) {
                console.log("Aplicando preview:", newval);
                
                // Remove estilos anteriores
                $("#cct-preview-typography").remove();
                
                var css = "";
                var fontLink = "";
                
                switch(newval) {
                    case "uenf_default":
                        fontLink = "<link href='https://fonts.googleapis.com/css2?family=Ubuntu:wght@500;700&family=Open+Sans:wght@400;600&display=swap' rel='stylesheet'>";
                        css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:'Ubuntu','Arial',sans-serif!important}body,.entry-content,p,.content-area{font-family:'Open Sans','Arial',sans-serif!important}";
                        break;
                    case "corporate":
                        fontLink = "<link href='https://fonts.googleapis.com/css2?family=Roboto:wght@500;700&family=Lato:wght@400;600&display=swap' rel='stylesheet'>";
                        css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:'Roboto','Arial',sans-serif!important}body,.entry-content,p,.content-area{font-family:'Lato','Arial',sans-serif!important}";
                        break;
                    case "editorial":
                        fontLink = "<link href='https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=PT+Sans:wght@400&display=swap' rel='stylesheet'>";
                        css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:'Merriweather','Times',serif!important}body,.entry-content,p,.content-area{font-family:'PT Sans','Arial',sans-serif!important}";
                        break;
                    case "creative":
                        fontLink = "<link href='https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Source+Sans+Pro:wght@400&display=swap' rel='stylesheet'>";
                        css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:'Playfair Display','Times',serif!important}body,.entry-content,p,.content-area{font-family:'Source Sans Pro','Arial',sans-serif!important}";
                        break;
                    case "academic":
                        fontLink = "<link href='https://fonts.googleapis.com/css2?family=Crimson+Text:wght@600&family=Open+Sans:wght@400&display=swap' rel='stylesheet'>";
                        css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:'Crimson Text','Times',serif!important}body,.entry-content,p,.content-area{font-family:'Open Sans','Arial',sans-serif!important}";
                        break;
                    case "tech":
                        fontLink = "<link href='https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400&display=swap' rel='stylesheet'>";
                        css = "h1,h2,h3,h4,h5,h6,.entry-title,.site-title{font-family:'Orbitron','Arial',sans-serif!important}body,.entry-content,p,.content-area{font-family:'Roboto','Arial',sans-serif!important}";
                        break;
                    case "theme_default":
                    default:
                        css = "/* Padrão do tema */";
                        break;
                }
                
                // Aplica as mudanças no preview
                if (fontLink) {
                    $("head").append(fontLink);
                }
                $("head").append("<style id='cct-preview-typography'>" + css + "</style>");
                
                // Força re-render
                $("body").addClass("cct-typography-updated").removeClass("cct-typography-updated");
            }
            
            // Bind para mudanças na configuração
            wp.customize("cct_font_pairing_preset", function(value) {
                value.bind(applyTypographyPreview);
            });
            
            // Aplica configuração inicial
            var initialValue = wp.customize("cct_font_pairing_preset").get();
            if (initialValue) {
                applyTypographyPreview(initialValue);
            }
            
            // Controles individuais de tipografia (mantidos para compatibilidade)
            
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
        }
    });
    
})(jQuery);