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
            $('#customize-control-cct_extensions_global_enabled input').on('change', function() {
                const enabled = $(this).is(':checked');
                ExtensionsManager.toggleGlobalExtensions(enabled);
                ExtensionsManager.updatePerformanceIndicator();
            });
            
            // Toggle individual
            $('[id^="customize-control-cct_extension_"][id$="_enabled"] input').on('change', function() {
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
            if (wp.customize.control('cct_extensions_global_enabled')) {
                wp.customize.control('cct_extensions_global_enabled').setting.set(true);
            }
            
            // Ativar todas as extensões individuais
            const extensionControls = [
                'cct_extension_dark_mode_enabled',
                'cct_extension_shadows_enabled',
                'cct_extension_breakpoints_enabled',
                'cct_extension_design_tokens_enabled',
                'cct_extension_patterns_enabled',
                'cct_extension_font_combinations_enabled',
                'cct_extension_typography_enabled',
                'cct_extension_gradients_enabled',
                'cct_extension_animations_enabled',
                'cct_extension_icons_enabled',
                'cct_extension_colors_enabled'
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
                    'cct_extension_dark_mode_enabled',
                    'cct_extension_shadows_enabled',
                    'cct_extension_breakpoints_enabled',
                    'cct_extension_design_tokens_enabled',
                    'cct_extension_patterns_enabled',
                    'cct_extension_font_combinations_enabled',
                    'cct_extension_typography_enabled',
                    'cct_extension_gradients_enabled',
                    'cct_extension_animations_enabled',
                    'cct_extension_icons_enabled',
                    'cct_extension_colors_enabled'
                ];
                
                extensionControls.forEach(function(controlId) {
                    if (wp.customize.control(controlId)) {
                        wp.customize.control(controlId).setting.set(false);
                    }
                });
                
                // Opcionalmente desativar o controle global também
                if (wp.customize.control('cct_extensions_global_enabled')) {
                    wp.customize.control('cct_extensions_global_enabled').setting.set(false);
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
                .addClass('cct-notification cct-notification-' + safeType)
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
            const $extensionControls = $('[id^="customize-control-cct_extension_"][id$="_enabled"]');
            
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
            const globalEnabled = $('#customize-control-cct_extensions_global_enabled input').is(':checked');
            
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
            if (!$('#cct-extensions-disabled-notice').length) {
                const notice = $(
                    '<div id="cct-extensions-disabled-notice" class="cct-notice cct-notice-warning">' +
                    '<div class="notice-content">' +
                    '<span class="dashicons dashicons-warning"></span>' +
                    '<div class="notice-text">' +
                    '<strong>⚠️ Todas as extensões estão desabilitadas</strong><br>' +
                    '<small>Funcionalidades avançadas não estarão disponíveis. Ative para restaurar todas as funcionalidades.</small>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
                
                $('#customize-control-cct_extensions_global_enabled').after(notice);
                notice.hide().slideDown(300);
            }
        },
        
        /**
         * Esconde aviso de extensões desabilitadas
         */
        hideGlobalDisabledNotice: function() {
            $('#cct-extensions-disabled-notice').slideUp(300, function() {
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
            $('[id^="customize-control-cct_extension_"][id$="_enabled"] input:checked').each(function() {
                if ($('#customize-control-cct_extensions_global_enabled input').is(':checked')) {
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
                const $infoControl = $('#customize-control-cct_extensions_info');
                if ($infoControl.length) {
                    $infoControl.find('.customize-control-description').html(
                        `Extensões ativas: <strong>${activeExtensions}</strong> de ${totalExtensions}<br>` +
                        `Performance: <span style="color: ${color}">${icon} ${status}</span>`
                    );
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
                const $section = $(`#accordion-section-cct_extensions_${category}`);
                if ($section.length) {
                    const $controls = $section.find('[id^="customize-control-cct_extension_"][id$="_enabled"]');
                    const total = $controls.length;
                    const active = $controls.find('input:checked').length;
                    
                    // Adicionar badge com contador
                    let $badge = $section.find('.cct-category-badge');
                    if (!$badge.length) {
                        $badge = $('<span class="cct-category-badge"></span>');
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
            $('[id^="customize-control-cct_extension_"][id$="_enabled"]').each(function() {
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
            if (!$('#cct-extensions-reset-button').length) {
                const resetButton = $(
                    '<div class="cct-reset-container">' +
                    '<button type="button" id="cct-extensions-reset-button" class="button button-secondary">' +
                    '<span class="dashicons dashicons-update"></span> Restaurar Padrões' +
                    '</button>' +
                    '<p class="description">Restaura todas as extensões para as configurações padrão.</p>' +
                    '</div>'
                );
                
                $('#customize-control-cct_extensions_info').after(resetButton);
                
                // Evento do botão
                $('#cct-extensions-reset-button').on('click', function() {
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
                        if (wp.customize.control('cct_extensions_global_enabled')) {
                            wp.customize.control('cct_extensions_global_enabled').setting.set(true);
                        }
                        
                        // Ativar todas as extensões individuais
                        const extensionControls = [
                            'cct_extension_dark_mode_enabled',
                            'cct_extension_shadows_enabled',
                            'cct_extension_breakpoints_enabled',
                            'cct_extension_design_tokens_enabled',
                            'cct_extension_patterns_enabled',
                            'cct_extension_font_combinations_enabled',
                            'cct_extension_typography_enabled',
                            'cct_extension_gradients_enabled',
                            'cct_extension_animations_enabled',
                            'cct_extension_icons_enabled',
                            'cct_extension_colors_enabled'
                        ];
                        
                        extensionControls.forEach(function(controlId) {
                            if (wp.customize.control(controlId)) {
                                wp.customize.control(controlId).setting.set(true);
                            }
                        });
                    } else {
                        // Fallback para jQuery se wp.customize não estiver disponível
                        $('#customize-control-cct_extensions_global_enabled input').prop('checked', true).trigger('change');
                        $('[id^="customize-control-cct_extension_"][id$="_enabled"] input').prop('checked', true).trigger('change');
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
            $('[id^="customize-control-cct_extension_"][id$="_enabled"] .customize-control-title').each(function() {
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
            if (!$('#cct-extensions-styles').length) {
                const styles = `
                    <style id="cct-extensions-styles">
                        .cct-notice {
                            margin: 10px 0;
                            padding: 12px;
                            border-radius: 4px;
                            border-left: 4px solid;
                        }
                        
                        .cct-notice-warning {
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
                        
                        .cct-category-badge {
                            background: #0073aa;
                            color: white;
                            padding: 2px 6px;
                            border-radius: 10px;
                            font-size: 11px;
                            margin-left: 8px;
                            font-weight: bold;
                        }
                        
                        .cct-category-badge.badge-full {
                            background: #4CAF50;
                        }
                        
                        .cct-category-badge.badge-partial {
                            background: #FF9800;
                        }
                        
                        .cct-category-badge.badge-empty {
                            background: #9E9E9E;
                        }
                        
                        .extension-active {
                            background: rgba(76, 175, 80, 0.1);
                            border-left: 3px solid #4CAF50;
                            padding-left: 9px;
                        }
                        
                        .cct-reset-container {
                            margin: 15px 0;
                            padding: 15px;
                            background: #f9f9f9;
                            border: 1px solid #ddd;
                            border-radius: 4px;
                        }
                        
                        #cct-extensions-reset-button {
                            display: flex;
                            align-items: center;
                            gap: 5px;
                        }
                        
                        .cct-success-message {
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
                        
                        #accordion-panel-cct_extensions .panel-meta {
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
            if (!localStorage.getItem('cct_extensions_welcome_shown')) {
                setTimeout(() => {
                    this.showSuccessMessage('🎉 Gerenciador de Extensões ativado! Controle todas as funcionalidades avançadas aqui.', 5000);
                    localStorage.setItem('cct_extensions_welcome_shown', 'true');
                }, 1000);
            }
        },
        
        /**
         * Mostra mensagem de sucesso
         */
        showSuccessMessage: function(message, duration = 3000) {
            // SECURITY FIX (SEC-JS-005): .text() previne XSS via template literal com interpolação direta
            const $message = $('<div class="cct-success-message"></div>').text(message);
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