(function($) {
    'use strict';// Função para atualizar o estilo do cabeçalho com maior especificidade
    function updateHeaderBg(color) {
        if (!color || color === 'default') {
            // Usa a cor primária do tema como padrão (#1D3771)
            color = '#1D3771';
        }
        // Usando !important e um seletor mais específico
        // Apenas atualiza o background-color, mantendo outras propriedades
        $('.shortcut-panel-header').css('background-color', color + ' !important');
        console.log('Atualizando cor de fundo para:', color);
    }

    // Função para atualizar a cor do texto do cabeçalho com maior especificidade
    function updateHeaderTextColor(color) {
        if (!color || color === 'default') {
            color = '#ffffff';
        }
        // Usando !important e um seletor mais específico
        $('.shortcut-panel-header h3').css('cssText', 'color: ' + color + ' !important');
        console.log('Atualizando cor do texto para:', color);
    }
    
    // Função para atualizar a cor de fundo dos itens
    function updateItemBg(color) {
        // Define valores padrão e normaliza o valor de entrada
        if (!color || color === 'default' || color === '') {
            color = '#1d3771';
        }
        
        console.log('Atualizando cor de fundo dos itens para:', color);
        
        // Remove estilos inline que possam estar sobrescrevendo
        $('.shortcut-item').removeAttr('style');
        
        // Atualiza a variável CSS em todos os casos
        document.documentElement.style.setProperty('--shortcut-item-bg', color);
        
        // Aplica a cor de fundo aos itens
        if (color === 'transparent' || color === 'rgba(0, 0, 0, 0)') {
            // Para transparência, adiciona a classe padrão
            $('.shortcut-item').addClass('shortcut-item-default');
        } else {
            // Para cores sólidas, remove a classe padrão
            $('.shortcut-item').removeClass('shortcut-item-default');
        }
        
        console.log('Classe shortcut-item-default presente?', $('.shortcut-item').hasClass('shortcut-item-default'));
        console.log('Variável CSS --shortcut-item-bg:', getComputedStyle(document.documentElement).getPropertyValue('--shortcut-item-bg'));
    }
    
    // Função para atualizar a cor do texto dos itens
    function updateItemTextColor(color) {
        if (!color || color === 'default') {
            color = '#ffffff'; // Cor branca como padrão
        }
        // Atualiza a variável CSS para a cor do texto
        document.documentElement.style.setProperty('--shortcut-item-text-color', color);
        console.log('Atualizando cor do texto dos itens para:', color);
    }
    
    // Função para atualizar a cor de fundo no hover dos itens
    function updateItemHoverBg(color) {
        if (!color || color === 'default') {
            color = 'rgba(255, 255, 255, 0.1)';
        }
        // Atualiza a variável CSS para o hover
        document.documentElement.style.setProperty('--shortcut-item-hover-bg', color);
        console.log('Atualizando cor de fundo do hover dos itens para:', color);
    }
    


    // Configura o binding para a cor de fundo do cabeçalho
    wp.customize('shortcut_header_bg', function(setting) {
        setting.bind(function(newval) {
            console.log('Mudança detectada em shortcut_header_bg:', newval);
            updateHeaderBg(newval);
        });
        // Força a atualização inicial
        updateHeaderBg(setting.get());
    });

    // Configura o binding para a cor do texto do cabeçalho
    wp.customize('shortcut_header_text_color', function(setting) {
        setting.bind(function(newval) {
            console.log('Mudança detectada em shortcut_header_text_color:', newval);
            updateHeaderTextColor(newval);
        });
        
        // Força a atualização inicial
        updateHeaderTextColor(setting.get());
    });
    
    // Configura o binding para a cor de fundo dos itens
    wp.customize('shortcut_item_bg', function(setting) {
        setting.bind(function(newval) {
            console.log('Mudança detectada em shortcut_item_bg:', newval);
            updateItemBg(newval);
        });
        
        // Força a atualização inicial
        updateItemBg(setting.get());
    });
    
    // Configura o binding para a cor do texto dos itens
    wp.customize('shortcut_item_text_color', function(setting) {
        setting.bind(function(newval) {
            console.log('Mudança detectada em shortcut_item_text_color:', newval);
            updateItemTextColor(newval);
        });
        
        // Força a atualização inicial
        updateItemTextColor(setting.get());
    });
    
    // Configura o binding para a cor de fundo no hover dos itens
    wp.customize('shortcut_item_hover_bg', function(setting) {
        setting.bind(function(newval) {
            console.log('Mudança detectada em shortcut_item_hover_bg:', newval);
            updateItemHoverBg(newval);
        });
        
        // Força a atualização inicial
        updateItemHoverBg(setting.get());
    });
    
    // Configura o binding para a cor de fundo do botão de fechar
    wp.customize('shortcut_close_button_bg', function(setting) {
        setting.bind(function(newval) {
            console.log('Mudança detectada em shortcut_close_button_bg:', newval);
            // Atualiza o estilo do botão de fechar
            $('.close-panel').css('background-color', newval);
        });
        
        // Força a atualização inicial
        $('.close-panel').css('background-color', setting.get());
    });
    
    // Atualiza os estilos quando o painel é carregado
    wp.customize.bind('ready', function() {
        // Aguarda um pequeno momento para garantir que o painel esteja pronto
        setTimeout(function() {
            updateHeaderBg(wp.customize('shortcut_header_bg').get());
            updateHeaderTextColor(wp.customize('shortcut_header_text_color').get());
            
            // Configura o seletor de cores para aceitar o valor 'transparent'
            if (wp.customize.control('shortcut_item_bg') && wp.customize.control('shortcut_item_bg').colorPicker) {
                wp.customize.control('shortcut_item_bg').colorPicker.wpColorPicker({
                    // Permite que o valor seja vazio (transparente)
                    empty: true,
                    // Função chamada quando a cor é alterada
                    change: function(event, ui) {
                        var color = ui.color.toString();
                        if (color === '' || color === 'transparent' || color === 'rgba(0, 0, 0, 0)') {
                            updateItemBg('transparent');
                            wp.customize('shortcut_item_bg').set('transparent');
                        } else {
                            updateItemBg(color);
                        }
                    }
                });
            }
        }, 100);
        
        // Adiciona um listener para o botão de reset
        $(document).on('click', '.customize-control-color .wp-picker-default', function(e) {
            e.preventDefault();
            var control = $(this).closest('.customize-control');
            var setting = control.data('customize-setting-link');
            
            console.log('Botão de reset clicado para:', setting);
            
            if (setting === 'shortcut_header_bg') {
                console.log('Resetando cor de fundo para o padrão (#1D3771)');
                // Define a cor primária do tema como padrão
                var defaultColor = '#1D3771';
                updateHeaderBg(defaultColor);
                // Atualiza o campo de cor visualmente
                control.find('.color-picker-hex').val(defaultColor).trigger('change');
                // Força a atualização do valor no Customizer
                wp.customize.instance('shortcut_header_bg').set(defaultColor);
            } else if (setting === 'shortcut_header_text_color') {
                console.log('Resetando cor do texto para o padrão');
                var defaultTextColor = '#ffffff';
                updateHeaderTextColor(defaultTextColor);
                // Atualiza o campo de cor visualmente
                control.find('.color-picker-hex').val(defaultTextColor).trigger('change');
                // Força a atualização do valor no Customizer
                wp.customize.instance('shortcut_header_text_color').set(defaultTextColor);
            } else if (setting === 'shortcut_item_bg') {
                console.log('=== INÍCIO DO RESET ===');
                console.log('Resetando cor de fundo dos itens para o padrão (#1d3771)');
                
                // Define o valor padrão
                var defaultValue = '#1d3771';
                
                // Atualiza o valor no Customizer primeiro
                wp.customize('shortcut_item_bg').set(defaultValue);
                
                // Atualiza visualmente os itens usando a função updateItemBg
                updateItemBg(defaultValue);
                
                // Atualiza o controle de cor visualmente
                var control = wp.customize.control('shortcut_item_bg');
                if (control) {
                    // Define o valor do color picker para #1d3771
                    if (control.colorPicker) {
                        control.colorPicker.val(defaultValue).trigger('change');
                    }
                    // Atualiza o valor do controle
                    control.setting.set(defaultValue);
                }
                
                // Força a atualização do preview
                wp.customize.previewer.refresh();
                
                console.log('=== FIM DO RESET ===');
                console.log('Estado FINAL:');
                console.log('Classe shortcut-item-default presente?', $('.shortcut-item').hasClass('shortcut-item-default'));
                console.log('Variável CSS --shortcut-item-bg:', getComputedStyle(document.documentElement).getPropertyValue('--shortcut-item-bg'));
            } else if (setting === 'shortcut_item_text_color') {
                var defaultValue = (typeof CCT_WHITE !== 'undefined') ? CCT_WHITE : '#ffffff';
                console.log('Resetando cor do texto dos itens para o padrão:', defaultValue);
                // Atualiza a variável CSS
                document.documentElement.style.setProperty('--shortcut-item-text-color', defaultValue);
                // Atualiza o campo de cor visualmente
                control.find('.color-picker-hex').val(defaultValue).trigger('change');
                // Força a atualização do valor no Customizer
                wp.customize.instance('shortcut_item_text_color').set(defaultValue);
            } else if (setting === 'shortcut_item_hover_bg') {
                console.log('Resetando cor de fundo do hover dos itens para o padrão');
                var defaultValue = 'rgba(255, 255, 255, 0.1)';
                updateItemHoverBg(defaultValue);
                // Atualiza o campo de cor visualmente
                control.find('.color-picker-hex').val('').trigger('change');
                // Força a atualização do valor no Customizer
                wp.customize.instance('shortcut_item_hover_bg').set(defaultValue);
            } else if (setting === 'shortcut_close_button_bg') {
                console.log('Resetando cor de fundo do botão de fechar para o padrão');
                var defaultValue = '#1d3771';
                // Atualiza o campo de cor visualmente
                control.find('.color-picker-hex').val('').trigger('change');
                // Força a atualização do valor no Customizer
                wp.customize.instance('shortcut_close_button_bg').set(defaultValue);
                // Atualiza o estilo diretamente
                $('.close-panel').css('background-color', defaultValue);
            }
        });
    });
})(jQuery);
