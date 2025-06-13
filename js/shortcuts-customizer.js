jQuery(document).ready(function($) {
    'use strict';
    
    // Verifica se o objeto wp.customize existe e se o jQuery está disponível
    if (typeof jQuery === 'undefined' || typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
        return;
    }

    // Cache de elementos DOM
    let $document = $(document),
        $header = $('.shortcut-panel-header'),
        $headerTitle = $header.find('h3'),
        $shortcutItems = $('.shortcut-item');
    
    // Inicializa o script quando o DOM estiver pronto
    function init() {
        try {
            // Verifica se o Customizer está disponível
            if (typeof wp.customize !== 'undefined') {
                setupCustomizer();
            } else {
                // Tenta novamente após um curto atraso se o Customizer ainda não estiver pronto
                setTimeout(init, 100);
            }
        } catch (error) {
            // Erro ao inicializar o script
        }
    }
    
    // Função para atualizar o estilo do cabeçalho com maior especificidade
    function updateHeaderBg(color) {
        try {
            if (!color || color === 'default') {
                color = '#1D3771'; // Cor primária do tema como padrão
            }
            
            if ($header.length) {
                $header[0].style.setProperty('background-color', color, 'important');
                // Atualizando cor de fundo
            }
        } catch (error) {
            // Erro ao atualizar cor de fundo
        }
    }

    // Função para atualizar a cor do texto do cabeçalho com maior especificidade
    function updateHeaderTextColor(color) {
        try {
            if (!color || color === 'default') {
                color = '#ffffff';
            }
            
            if ($headerTitle.length) {
                $headerTitle[0].style.setProperty('color', color, 'important');
                // Atualizando cor do texto
            }
        } catch (error) {
            // Erro ao atualizar cor do texto
        }
    }
    
    // Função para atualizar a cor de fundo dos itens
    function updateItemBg(color) {
        try {
            // Define valores padrão e normaliza o valor de entrada
            if (!color || color === 'default' || color === '') {
                color = '#1d3771';
            }
            
            // Atualizando cor de fundo dos itens
            
            // Remove estilos inline que possam estar sobrescrevendo
            $shortcutItems.removeAttr('style');
            
            // Atualiza a variável CSS
            document.documentElement.style.setProperty('--shortcut-item-bg', color);
            
            // Aplica a cor de fundo aos itens
            if (color === 'transparent' || color === 'rgba(0, 0, 0, 0)') {
                $shortcutItems.addClass('shortcut-item-default');
            } else {
                $shortcutItems.removeClass('shortcut-item-default');
            }
            
            // Debug
            if (console && console.debug) {
                console.debug('Classe shortcut-item-default presente?', 
                    $shortcutItems.first().hasClass('shortcut-item-default'));
                console.debug('Variável CSS --shortcut-item-bg:', 
                    getComputedStyle(document.documentElement).getPropertyValue('--shortcut-item-bg'));
            }
        } catch (error) {
            // Erro ao atualizar cor de fundo dos itens
        }
    }
    
    // Função para atualizar a cor do texto dos itens
    function updateItemTextColor(color) {
        try {
            if (!color || color === 'default') {
                color = '#ffffff'; // Cor branca como padrão
            }
            // Atualiza a variável CSS para a cor do texto
            document.documentElement.style.setProperty('--shortcut-item-text-color', color);
            // Atualizando cor do texto dos itens
        } catch (error) {
            // Erro ao atualizar cor do texto dos itens
        }
    }
    
    // Função para atualizar a cor de fundo no hover dos itens
    function updateItemHoverBg(color) {
        try {
            if (!color || color === 'default') {
                color = 'rgba(255, 255, 255, 0.1)';
            }
            // Atualiza a variável CSS para o hover
            document.documentElement.style.setProperty('--shortcut-item-hover-bg', color);
            // Atualizando cor de fundo do hover dos itens
        } catch (error) {
            // Erro ao atualizar cor de fundo do hover
        }
    }

    // Função para atualizar a cor de fundo do botão de fechar
    function updateCloseButtonBg(color) {
        try {
            if (!color || color === 'default') {
                color = '#1d3771'; // Cor padrão
            }
            $('.close-panel').css('background-color', color);
            // Atualizando cor de fundo do botão de fechar
        } catch (error) {
            // Erro ao atualizar cor de fundo do botão de fechar
        }
    }
    
    // Função para configurar os bindings do Customizer
    function setupCustomizerBindings() {
        try {
            // Configura todos os bindings em um array
            var bindings = [
                { id: 'shortcut_header_bg', fn: updateHeaderBg },
                { id: 'shortcut_header_text_color', fn: updateHeaderTextColor },
                { id: 'shortcut_item_bg', fn: updateItemBg },
                { id: 'shortcut_item_text_color', fn: updateItemTextColor },
                { id: 'shortcut_item_hover_bg', fn: updateItemHoverBg },
                { id: 'shortcut_close_button_bg', fn: updateCloseButtonBg }
            ];

            // Configura cada binding
            bindings.forEach(function(binding) {
                if (wp.customize.control(binding.id)) {
                    wp.customize(binding.id, function(setting) {
                        if (setting) {
                            setting.bind(function(newval) {
                                // Mudança detectada em configuração do Customizer
                                binding.fn(newval);
                            });
                            // Força a atualização inicial
                            binding.fn(setting.get());
                        }
                    });
                } else {
                    // Controle não encontrado no Customizer
                }
            });
        } catch (error) {
            // Erro ao configurar bindings
        }
    }

    // Configura o Customizer quando estiver pronto
    function setupCustomizer() {
        try {
            // Verifica se o Customizer está disponível
            if (!wp.customize) {
                // wp.customize não está disponível
                return;
            }

            // Aguarda o Customizer estar pronto
            wp.customize.bind('ready', function() {
                try {
                    // Chama a função para configurar os bindings
                    setupCustomizerBindings();
                    
                    // Configura o color picker para aceitar valores transparentes
                    var itemBgControl = wp.customize.control('shortcut_item_bg');
                    if (itemBgControl && itemBgControl.colorPicker && wp.customize('shortcut_item_bg')) {
                        // Adiciona um atraso para garantir que o color picker foi carregado
                        setTimeout(function() {
                            // Configura o color picker
                            if (typeof wp.wpColorPicker !== 'undefined' && $.fn.wpColorPicker) {
                                // Função chamada quando a cor é alterada
                                function change(event, ui) {
                                    try {
                                        var color = ui.color.toString();
                                        // Se for transparente, envia 'transparent' em vez do valor rgba
                                        if (color === 'rgba(0, 0, 0, 0)') {
                                            color = 'transparent';
                                        }
                                        // Atualiza o valor do campo
                                        $(this).wpColorPicker('color', color);
                                    } catch (error) {
                                        // Erro ao atualizar cor
                                    }
                                }
                                // Configura o color picker
                                itemBgControl.colorPicker.on('change', change);
                                
                                // Atualiza o valor inicial se for transparente
                                var currentValue = itemBgControl.setting.get();
                                if (currentValue === 'transparent') {
                                    itemBgControl.colorPicker.colorpicker('val', 'transparent');
                                }
                            }
                        }, 100);
                    } else {
                        // Controle shortcut_item_bg não encontrado no Customizer
                    }

                    // Aguarda um pequeno momento para garantir que o painel esteja pronto
                    setTimeout(function() {
                        // Atualiza as cores iniciais apenas se as configurações existirem
                        if (wp.customize.control('shortcut_header_bg') && wp.customize('shortcut_header_bg')) {
                            updateHeaderBg(wp.customize('shortcut_header_bg').get());
                        }
                        
                        if (wp.customize.control('shortcut_header_text_color') && wp.customize('shortcut_header_text_color')) {
                            updateHeaderTextColor(wp.customize('shortcut_header_text_color').get());
                        }
                    }, 100);

                    // Adiciona um listener para o botão de reset
                    $document.on('click', '.customize-control-color .wp-picker-default', function(e) {
                        try {
                            e.preventDefault();
                            var $control = $(this).closest('.customize-control');
                            var setting = $control.data('customize-setting-link');
                            
                            if (!setting) return;
                            
                            // Mapeia as configurações para seus valores padrão e funções de atualização
                            var settingsMap = {
                                'shortcut_header_bg': {
                                    defaultValue: '#1d3771',
                                    updateFn: updateHeaderBg
                                },
                                'shortcut_header_text_color': {
                                    defaultValue: '#ffffff',
                                    updateFn: updateHeaderTextColor
                                },
                                'shortcut_item_bg': {
                                    defaultValue: '#1d3771',
                                    updateFn: updateItemBg
                                },
                                'shortcut_item_text_color': {
                                    defaultValue: '#ffffff',
                                    updateFn: updateItemTextColor
                                },
                                'shortcut_item_hover_bg': {
                                    defaultValue: 'rgba(255, 255, 255, 0.1)',
                                    updateFn: updateItemHoverBg
                                },
                                'shortcut_close_button_bg': {
                                    defaultValue: '#1d3771',
                                    updateFn: updateCloseButtonBg
                                }
                            };
                            
                            var settingConfig = settingsMap[setting];
                            if (settingConfig) {
                                // Resetando configuração para o padrão
                                // Atualiza o campo de cor visualmente
                                $control.find('.color-picker-hex').val('').trigger('change');
                                // Atualiza a prévia
                                settingConfig.updateFn(settingConfig.defaultValue);
                            }
                        } catch (error) {
                            // Erro ao redefinir configuração
                        }
                    });
                } catch (error) {
                    // Erro ao configurar Customizer
                }
            });
        } catch (error) {
            console.error('Erro ao configurar Customizer:', error);
        }
    }

    // Inicializa o script
    init();
});

// Fecha o jQuery(document).ready
