// Migração one-shot: renomeia chave cct_ → uenf_ no localStorage
(function () {
    var oldKey = 'cct_extensions_welcome_shown';
    var newKey = 'uenf_extensions_welcome_shown';
    var val = localStorage.getItem(oldKey);
    if (val !== null) {
        localStorage.setItem(newKey, val);
        localStorage.removeItem(oldKey);
    }
})();

/**
 * Gerenciador de Extensões - Interface JavaScript
 * Controla a interface do customizer para o sistema de extensões
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    const ExtensionsManager = {
        
        /**
         * Inicializa o gerenciador
         */
        init: function() {
            try {
                // Verificar se os objetos necessários estão disponíveis
                if (typeof $ === 'undefined') {
                    console.error('ExtensionsManager: jQuery não está disponível');
                    return;
                }
                
                if (typeof wp === 'undefined' || !wp.customize) {
                    console.warn('ExtensionsManager: wp.customize não está disponível, usando fallback');
                }
                
                this.bindEvents();
                this.updateUI();
                this.addStyles();
                this.showWelcomeMessage();
            } catch (error) {
                console.error('ExtensionsManager: Erro na inicialização:', error);
            }
        },
        
        /**
         * Vincula eventos
         */
        bindEvents: function() {
            // Toggle global
            $('#customize-control-uenf_extensions_global_enabled input').on('change', function() {
                const enabled = $(this).is(':checked');
                ExtensionsManager.toggleGlobalExtensions(enabled);
                ExtensionsManager.updatePerformanceIndicator();
            });
            
            // Toggle individual
            $('[id^="customize-control-uenf_extension_"][id$="_enabled"] input').on('change', function() {
                ExtensionsManager.updateUI();
                ExtensionsManager.updatePerformanceIndicator();
                ExtensionsManager.checkDependencies();
            });
            
            // Adicionar botão de reset
            this.addResetButton();
            
            // Adicionar tooltips
            this.addTooltips();
        },
        
        /**
         * Ativa todas as extensões
         */
        enableAllExtensions: function() {
            // Ativar controle global
            if (wp.customize.control('uenf_extensions_global_enabled')) {
                wp.customize.control('uenf_extensions_global_enabled').setting.set(true);
            }
            
            // Ativar todas as extensões individuais
            const extensionControls = [
                'uenf_extension_dark_mode_enabled',
                'uenf_extension_shadows_enabled',
                'uenf_extension_breakpoints_enabled',
                'uenf_extension_design_tokens_enabled',
                'uenf_extension_patterns_enabled',
                'uenf_extension_font_combinations_enabled',
                'uenf_extension_typography_enabled',
                'uenf_extension_gradients_enabled',
                'uenf_extension_animations_enabled',
                'uenf_extension_icons_enabled',
                'uenf_extension_colors_enabled'
            ];
            
            extensionControls.forEach(function(controlId) {
                if (wp.customize.control(controlId)) {
                    wp.customize.control(controlId).setting.set(true);
                }
            });
            
            this.updateUI();
            this.updatePerformanceIndicator();
            
            // Mostrar notificação
            this.showNotification('✅ Todas as extensões foram ativadas!', 'success');
        },
        
        /**
         * Desativa todas as extensões
         */
        disableAllExtensions: function() {
            if (confirm('⚠️ Tem certeza que deseja desativar todas as extensões?\n\nIsso pode afetar a aparência do seu site.')) {
                // Desativar todas as extensões individuais
                const extensionControls = [
                    'uenf_extension_dark_mode_enabled',
                    'uenf_extension_shadows_enabled',
                    'uenf_extension_breakpoints_enabled',
                    'uenf_extension_design_tokens_enabled',
                    'uenf_extension_patterns_enabled',
                    'uenf_extension_font_combinations_enabled',
                    'uenf_extension_typography_enabled',
                    'uenf_extension_gradients_enabled',
                    'uenf_extension_animations_enabled',
                    'uenf_extension_icons_enabled',
                    'uenf_extension_colors_enabled'
                ];
                
                extensionControls.forEach(function(controlId) {
                    if (wp.customize.control(controlId)) {
                        wp.customize.control(controlId).setting.set(false);
                    }
                });
                
                // Opcionalmente desativar o controle global também
                if (wp.customize.control('uenf_extensions_global_enabled')) {
                    wp.customize.control('uenf_extensions_global_enabled').setting.set(false);
                }
                
                this.updateUI();
                this.updatePerformanceIndicator();
                
                // Mostrar notificação
                this.showNotification('❌ Todas as extensões foram desativadas!', 'warning');
            }
        },
        
        /**
         * Mostra notificação
         */
        showNotification: function(message, type) {
            // SECURITY FIX (SEC-JS-005): .text() previne XSS se message vier de dados externos futuramente
            var safeType = String(type || 'info').replace(/[^a-z0-9-]/gi, '');
            const notification = $('<div></div>')
                .addClass('uenf-notification uenf-notification-' + safeType)
                .text(message);
            $('body').append(notification);
            
            notification.fadeIn(300).delay(3000).fadeOut(300, function() {
                $(this).remove();
            });
        },
        
        /**
         * Toggle global de extensões
         */
        toggleGlobalExtensions: function(enabled) {
            const $extensionControls = $('[id^="customize-control-uenf_extension_"][id$="_enabled"]');
            
            if (enabled) {
                $extensionControls.show().removeClass('disabled');
                this.hideGlobalDisabledNotice();
            } else {
                $extensionControls.hide().addClass('disabled');
                this.showGlobalDisabledNotice();
            }
            
            // Animar transição
            $extensionControls.each(function(index) {
                $(this).delay(index * 50).fadeToggle(200);
            });
        },
        
        /**
         * Atualiza a interface
         */
        updateUI: function() {
            const globalEnabled = $('#customize-control-uenf_extensions_global_enabled input').is(':checked');
            
            if (!globalEnabled) {
                this.toggleGlobalExtensions(false);
            } else {
                this.toggleGlobalExtensions(true);
            }
            
            this.updateCategoryCounters();
            this.highlightActiveExtensions();
        },
        
        /**
         * Mostra aviso de extensões desabilitadas
         */
        showGlobalDisabledNotice: function() {
            if (!$('#uenf-extensions-disabled-notice').length) {
                const notice = $(
                    '<div id="uenf-extensions-disabled-notice" class="uenf-notice uenf-notice-warning">' +
                    '<div class="notice-content">' +
                    '<span class="dashicons dashicons-warning"></span>' +
                    '<div class="notice-text">' +
                    '<strong>⚠️ Todas as extensões estão desabilitadas</strong><br>' +
                    '<small>Funcionalidades avançadas não estarão disponíveis. Ative para restaurar todas as funcionalidades.</small>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
                
                $('#customize-control-uenf_extensions_global_enabled').after(notice);
                notice.hide().slideDown(300);
            }
        },
        
        /**
         * Esconde aviso de extensões desabilitadas
         */
        hideGlobalDisabledNotice: function() {
            $('#uenf-extensions-disabled-notice').slideUp(300, function() {
                $(this).remove();
            });
        },
        
        /**
         * Atualiza indicador de performance
         */
        updatePerformanceIndicator: function() {
            try {
                // Verificar se cctExtensions está disponível
                if (typeof cctExtensions === 'undefined' || !cctExtensions.extensions) {
                    console.warn('ExtensionsManager: cctExtensions não está disponível');
                    return;
                }
                
                const totalExtensions = Object.keys(cctExtensions.extensions).length;
                let activeExtensions = 0;
            
            // Contar extensões ativas
            $('[id^="customize-control-uenf_extension_"][id$="_enabled"] input:checked').each(function() {
                if ($('#customize-control-uenf_extensions_global_enabled input').is(':checked')) {
                    activeExtensions++;
                }
            });
            
            const percentage = (activeExtensions / totalExtensions) * 100;
            let status, color, icon;
            
            if (percentage <= 30) {
                status = 'Excelente';
                color = '#4CAF50';
                icon = '🟢';
            } else if (percentage <= 60) {
                status = 'Boa';
                color = '#FF9800';
                icon = '🟡';
            } else if (percentage <= 80) {
                status = 'Moderada';
                color = '#FF5722';
                icon = '🟠';
            } else {
                status = 'Pesada';
                color = '#F44336';
                icon = '🔴';
            }
            
                // Atualizar display
                const $infoControl = $('#customize-control-uenf_extensions_info');
                if ($infoControl.length) {
                    // SECURITY FIX: JS-C02 — Substituído .html() com template literal interpolado
                    // por criação DOM segura. .text() escapa conteúdo; .css() aplica cor sem injeção HTML.
                    const $desc = $infoControl.find('.customize-control-description');
                    $desc.empty();
                    $('<span></span>').text('Extensões ativas: ').appendTo($desc);
                    $('<strong></strong>').text(activeExtensions + ' de ' + totalExtensions).appendTo($desc);
                    $('<br>').appendTo($desc);
                    $('<span></span>').text('Performance: ' + icon + ' ' + status)
                        .css('color', color).appendTo($desc);
                }
            } catch (error) {
                console.error('ExtensionsManager: Erro ao atualizar indicador de performance:', error);
            }
        },
        
        /**
         * Atualiza contadores por categoria
         */
        updateCategoryCounters: function() {
            const categories = ['design', 'layout', 'typography', 'content', 'effects'];
            
            categories.forEach(category => {
                const $section = $(`#accordion-section-uenf_extensions_${category}`);
                if ($section.length) {
                    const $controls = $section.find('[id^="customize-control-uenf_extension_"][id$="_enabled"]');
                    const total = $controls.length;
                    const active = $controls.find('input:checked').length;
                    
                    // Adicionar badge com contador
                    let $badge = $section.find('.uenf-category-badge');
                    if (!$badge.length) {
                        $badge = $('<span class="uenf-category-badge"></span>');
                        $section.find('.accordion-section-title').append($badge);
                    }
                    
                    $badge.text(`${active}/${total}`);
                    $badge.removeClass('badge-full badge-partial badge-empty');
                    
                    if (active === total) {
                        $badge.addClass('badge-full');
                    } else if (active > 0) {
                        $badge.addClass('badge-partial');
                    } else {
                        $badge.addClass('badge-empty');
                    }
                }
            });
        },
        
        /**
         * Destaca extensões ativas
         */
        highlightActiveExtensions: function() {
            $('[id^="customize-control-uenf_extension_"][id$="_enabled"]').each(function() {
                const $control = $(this);
                const $input = $control.find('input');
                
                if ($input.is(':checked')) {
                    $control.addClass('extension-active');
                } else {
                    $control.removeClass('extension-active');
                }
            });
        },
        
        /**
         * Verifica dependências
         */
        checkDependencies: function() {
            // Implementar verificação de dependências
            // Por enquanto, apenas um placeholder
            console.log('Verificando dependências das extensões...');
        },
        
        /**
         * Adiciona botão de reset
         */
        addResetButton: function() {
            if (!$('#uenf-extensions-reset-button').length) {
                const resetButton = $(
                    '<div class="uenf-reset-container">' +
                    '<button type="button" id="uenf-extensions-reset-button" class="button button-secondary">' +
                    '<span class="dashicons dashicons-update"></span> Restaurar Padrões' +
                    '</button>' +
                    '<p class="description">Restaura todas as extensões para as configurações padrão.</p>' +
                    '</div>'
                );
                
                $('#customize-control-uenf_extensions_info').after(resetButton);
                
                // Evento do botão
                $('#uenf-extensions-reset-button').on('click', function() {
                    ExtensionsManager.resetAllSettings();
                });
            }
        },
        
        /**
         * Reset todas as configurações
         */
        resetAllSettings: function() {
            if (confirm('Tem certeza que deseja restaurar todas as extensões para as configurações padrão?')) {
                try {
                    // Verificar se wp.customize está disponível
                    if (typeof wp !== 'undefined' && wp.customize) {
                        // Ativar configuração global usando wp.customize
                        if (wp.customize.control('uenf_extensions_global_enabled')) {
                            wp.customize.control('uenf_extensions_global_enabled').setting.set(true);
                        }
                        
                        // Ativar todas as extensões individuais
                        const extensionControls = [
                            'uenf_extension_dark_mode_enabled',
                            'uenf_extension_shadows_enabled',
                            'uenf_extension_breakpoints_enabled',
                            'uenf_extension_design_tokens_enabled',
                            'uenf_extension_patterns_enabled',
                            'uenf_extension_font_combinations_enabled',
                            'uenf_extension_typography_enabled',
                            'uenf_extension_gradients_enabled',
                            'uenf_extension_animations_enabled',
                            'uenf_extension_icons_enabled',
                            'uenf_extension_colors_enabled'
                        ];
                        
                        extensionControls.forEach(function(controlId) {
                            if (wp.customize.control(controlId)) {
                                wp.customize.control(controlId).setting.set(true);
                            }
                        });
                    } else {
                        // Fallback para jQuery se wp.customize não estiver disponível
                        $('#customize-control-uenf_extensions_global_enabled input').prop('checked', true).trigger('change');
                        $('[id^="customize-control-uenf_extension_"][id$="_enabled"] input').prop('checked', true).trigger('change');
                    }
                    
                    // Mostrar feedback
                    this.showSuccessMessage('Configurações restauradas com sucesso!');
                } catch (error) {
                    console.error('Erro ao resetar configurações:', error);
                    alert('Erro ao restaurar configurações. Verifique o console para mais detalhes.');
                }
            }
        },
        
        /**
         * Adiciona tooltips
         */
        addTooltips: function() {
            $('[id^="customize-control-uenf_extension_"][id$="_enabled"] .customize-control-title').each(function() {
                const $title = $(this);
                const $control = $title.closest('.customize-control');
                const description = $control.find('.customize-control-description').text();
                
                if (description) {
                    $title.attr('title', description);
                }
            });
        },
        
        /**
         * Adiciona estilos CSS
         */
        addStyles: function() {
            if (!$('#uenf-extensions-styles').length) {
                const styles = `
                    <style id="uenf-extensions-styles">
                        .uenf-notice {
                            margin: 10px 0;
                            padding: 12px;
                            border-radius: 4px;
                            border-left: 4px solid;
                        }
                        
                        .uenf-notice-warning {
                            background: #fff3cd;
                            border-left-color: #ffc107;
                            color: #856404;
                        }
                        
                        .notice-content {
                            display: flex;
                            align-items: flex-start;
                            gap: 8px;
                        }
                        
                        .notice-content .dashicons {
                            margin-top: 2px;
                            color: #ffc107;
                        }
                        
                        .uenf-category-badge {
                            background: #0073aa;
                            color: white;
                            padding: 2px 6px;
                            border-radius: 10px;
                            font-size: 11px;
                            margin-left: 8px;
                            font-weight: bold;
                        }
                        
                        .uenf-category-badge.badge-full {
                            background: #4CAF50;
                        }
                        
                        .uenf-category-badge.badge-partial {
                            background: #FF9800;
                        }
                        
                        .uenf-category-badge.badge-empty {
                            background: #9E9E9E;
                        }
                        
                        .extension-active {
                            background: rgba(76, 175, 80, 0.1);
                            border-left: 3px solid #4CAF50;
                            padding-left: 9px;
                        }
                        
                        .uenf-reset-container {
                            margin: 15px 0;
                            padding: 15px;
                            background: #f9f9f9;
                            border: 1px solid #ddd;
                            border-radius: 4px;
                        }
                        
                        #uenf-extensions-reset-button {
                            display: flex;
                            align-items: center;
                            gap: 5px;
                        }
                        
                        .uenf-success-message {
                            position: fixed;
                            top: 32px;
                            right: 20px;
                            background: #4CAF50;
                            color: white;
                            padding: 12px 20px;
                            border-radius: 4px;
                            z-index: 999999;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                        }
                        
                        #accordion-panel-uenf_extensions .panel-meta {
                            border-top: 1px solid #ddd;
                            padding: 10px 14px;
                            background: #f9f9f9;
                        }
                        
                        .customize-control-description small {
                            color: #666;
                            font-style: italic;
                        }
                    </style>
                `;
                
                $('head').append(styles);
            }
        },
        
        /**
         * Mostra mensagem de boas-vindas
         */
        showWelcomeMessage: function() {
            // Verificar se é a primeira vez
            if (!localStorage.getItem('uenf_extensions_welcome_shown')) {
                setTimeout(() => {
                    this.showSuccessMessage('🎉 Gerenciador de Extensões ativado! Controle todas as funcionalidades avançadas aqui.', 5000);
                    localStorage.setItem('uenf_extensions_welcome_shown', 'true');
                }, 1000);
            }
        },
        
        /**
         * Mostra mensagem de sucesso
         */
        showSuccessMessage: function(message, duration = 3000) {
            // SECURITY FIX (SEC-JS-005): .text() previne XSS via template literal com interpolação direta
            const $message = $('<div class="uenf-success-message"></div>').text(message);
            $('body').append($message);
            
            $message.hide().slideDown(300);
            
            setTimeout(() => {
                $message.slideUp(300, function() {
                    $(this).remove();
                });
            }, duration);
        },
        
        /**
         * Adiciona informações de debug
         */
        addDebugInfo: function() {
            if (typeof cctExtensions !== 'undefined') {
                console.group('🔧 CCT Extensions Manager');
                console.log('Total de extensões:', cctExtensions.totalCount);
                console.log('Extensões ativas:', cctExtensions.activeCount);
                console.log('Extensões disponíveis:', cctExtensions.extensions);
                console.groupEnd();
            }
        }
    };
    
    // Inicializar quando o customizer estiver pronto
    wp.customize.bind('ready', function() {
        ExtensionsManager.init();
        ExtensionsManager.addDebugInfo();
    });
    
    // Expor para uso global se necessário
    window.CCTExtensionsManager = ExtensionsManager;
    
})(jQuery);

// Funções globais para os botões onclick
function cctEnableAllExtensions() {
    if (window.CCTExtensionsManager) {
        window.CCTExtensionsManager.enableAllExtensions();
    }
}

function cctDisableAllExtensions() {
    if (window.CCTExtensionsManager) {
        window.CCTExtensionsManager.disableAllExtensions();
    }
}