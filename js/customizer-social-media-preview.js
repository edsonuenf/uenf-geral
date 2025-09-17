/**
 * Customizer Social Media Preview
 * 
 * Atualiza as redes sociais em tempo real no customizer
 */
(function($) {
    'use strict';

    // Aguardar o customizer estar pronto
    wp.customize.bind('ready', function() {
        bindSocialMediaPreview();
    });

    /**
     * Vincula os controles de preview das redes sociais
     */
    function bindSocialMediaPreview() {
        // Tamanho do ícone
        wp.customize('social_media_icon_size', function(value) {
            value.bind(function(newval) {
                updateSocialMediaStyles();
            });
        });

        // Cor do ícone
        wp.customize('social_media_icon_color', function(value) {
            value.bind(function(newval) {
                updateSocialMediaStyles();
            });
        });

        // Cor de fundo
        wp.customize('social_media_bg_color', function(value) {
            value.bind(function(newval) {
                updateSocialMediaStyles();
            });
        });

        // Largura da borda
        wp.customize('social_media_border_width', function(value) {
            value.bind(function(newval) {
                updateSocialMediaStyles();
            });
        });

        // Cor da borda
        wp.customize('social_media_border_color', function(value) {
            value.bind(function(newval) {
                updateSocialMediaStyles();
            });
        });

        // Raio da borda
        wp.customize('social_media_border_radius', function(value) {
            value.bind(function(newval) {
                updateSocialMediaStyles();
            });
        });

        // Alinhamento
        wp.customize('social_media_alignment', function(value) {
            value.bind(function(newval) {
                $('.social-media-links').css('text-align', newval);
            });
        });

        // Gap entre ícones
        wp.customize('social_media_icon_gap', function(value) {
            value.bind(function(newval) {
                $('.social-media-links').css('gap', newval + 'px');
            });
        });
    }

    /**
     * Atualiza os estilos das redes sociais
     */
    function updateSocialMediaStyles() {
        var iconSize = wp.customize('social_media_icon_size')();
        var iconColor = wp.customize('social_media_icon_color')();
        var bgColor = wp.customize('social_media_bg_color')();
        var borderWidth = wp.customize('social_media_border_width')();
        var borderColor = wp.customize('social_media_border_color')();
        var borderRadius = wp.customize('social_media_border_radius')();
        
        var fontSize = Math.round(iconSize * 0.45);
        
        // Aplicar estilos com !important para cor
        $('.social-media-links .social-link').each(function() {
            $(this).css({
                'width': iconSize + 'px',
                'height': iconSize + 'px',
                'font-size': fontSize + 'px',
                'background-color': bgColor,
                'border-width': borderWidth + 'px',
                'border-color': borderColor,
                'border-radius': borderRadius + '%'
            });
            
            // Aplicar cor com !important
            this.style.setProperty('color', iconColor, 'important');
        });
    }

})(jQuery);