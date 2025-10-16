/**
 * Customizer Social Media Reset
 * 
 * Gerencia o reset das configurações de redes sociais
 */
(function($) {
    'use strict';

    // Valores padrão das configurações
    var defaultValues = {
        'social_media_icon_size': 32,
        'social_media_icon_color': 'rgba(255, 255, 255, 0.6)',
        'social_media_bg_color': '#1d3771',
        'social_media_border_width': 0,
        'social_media_border_color': '#ffffff',
        'social_media_border_radius': 50,
        'social_media_icon_gap': 6,
        'social_media_alignment': 'right'
    };

    // Aguardar o customizer estar pronto
    wp.customize.bind('ready', function() {
        bindResetButton();
    });

    /**
     * Vincula o botão de reset
     */
    function bindResetButton() {
        // Aguardar o botão estar disponível
        setTimeout(function() {
            $('.cct-social-reset-button').on('click', function(e) {
                e.preventDefault();
                resetSocialMediaSettings();
            });
        }, 1000);
    }

    /**
     * Reseta todas as configurações de redes sociais
     */
    function resetSocialMediaSettings() {
        // Confirmar ação
        if (!confirm('Tem certeza que deseja restaurar todas as configurações de redes sociais para os valores padrão?')) {
            return;
        }

        // Resetar cada configuração
        $.each(defaultValues, function(setting, defaultValue) {
            if (wp.customize(setting)) {
                wp.customize(setting).set(defaultValue);
            }
        });

        // Mostrar mensagem de sucesso
        showResetMessage();
    }

    /**
     * Mostra mensagem de sucesso
     */
    function showResetMessage() {
        // Criar elemento de notificação
        var $notification = $('<div class="cct-reset-notification">');
        $notification.html('✅ Configurações de redes sociais restauradas para os valores padrão!');
        $notification.css({
            'position': 'fixed',
            'top': '50px',
            'right': '20px',
            'background': '#00a32a',
            'color': 'white',
            'padding': '15px 20px',
            'border-radius': '4px',
            'box-shadow': '0 2px 10px rgba(0,0,0,0.2)',
            'z-index': '999999',
            'font-size': '14px',
            'max-width': '300px'
        });

        // Adicionar ao body
        $('body').append($notification);

        // Remover após 3 segundos
        setTimeout(function() {
            $notification.fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }

    /**
     * Adiciona botões de reset individuais
     */
    function addIndividualResetButtons() {
        // Lista de configurações com botões individuais
        var settingsWithReset = [
            'social_media_icon_size',
            'social_media_icon_color',
            'social_media_bg_color',
            'social_media_border_width',
            'social_media_border_color',
            'social_media_border_radius',
            'social_media_icon_gap'
        ];

        // Adicionar botão para cada configuração
        $.each(settingsWithReset, function(index, setting) {
            setTimeout(function() {
                var $control = $('#customize-control-' + setting);
                if ($control.length) {
                    var $resetBtn = $('<button type="button" class="button button-link cct-individual-reset" data-setting="' + setting + '">↺ Padrão</button>');
                    $resetBtn.css({
                        'margin-left': '10px',
                        'font-size': '12px',
                        'text-decoration': 'none'
                    });
                    
                    $control.find('.customize-control-title').append($resetBtn);
                    
                    // Evento de clique
                    $resetBtn.on('click', function(e) {
                        e.preventDefault();
                        var settingName = $(this).data('setting');
                        if (wp.customize(settingName) && defaultValues[settingName] !== undefined) {
                            wp.customize(settingName).set(defaultValues[settingName]);
                            
                            // Feedback visual
                            $(this).text('✓ Restaurado').css('color', '#00a32a');
                            setTimeout(function() {
                                $resetBtn.text('↺ Padrão').css('color', '');
                            }, 2000);
                        }
                    });
                }
            }, 1500 + (index * 100));
        });
    }

    // Aguardar customizer estar totalmente carregado
    wp.customize.bind('ready', function() {
        setTimeout(addIndividualResetButtons, 2000);
    });

})(jQuery);