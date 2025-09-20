/**
 * Gerenciador de Reset de Configurações - Customizer
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    var UENFResetManager = {
        
        /**
         * Inicializar o gerenciador
         */
        init: function() {
            this.bindEvents();
            this.updateResetButtonState();
        },
        
        /**
         * Vincular eventos
         */
        bindEvents: function() {
            var self = this;
            
            // Eventos para reset de extensões individuais
            $(document).on('change', '.uenf-extension-checkbox', function() {
                self.updateResetButtonState();
            });
            
            $(document).on('click', '.uenf-select-all-extensions', function() {
                $('.uenf-extension-checkbox').prop('checked', true);
                self.updateResetButtonState();
            });
            
            $(document).on('click', '.uenf-deselect-all-extensions', function() {
                $('.uenf-extension-checkbox').prop('checked', false);
                self.updateResetButtonState();
            });
            
            $(document).on('click', '.uenf-reset-selected-extensions', function() {
                self.resetSelectedExtensions();
            });
            
            // Eventos para reset completo
            $(document).on('change', '.uenf-confirm-reset-all', function() {
                var isChecked = $(this).is(':checked');
                $('.uenf-reset-all-settings').prop('disabled', !isChecked);
            });
            
            $(document).on('click', '.uenf-reset-all-settings', function() {
                self.resetAllSettings();
            });
            
            // Eventos para backup
            $(document).on('click', '.uenf-create-backup', function() {
                self.createBackup();
            });
            
            $(document).on('click', '.uenf-restore-backup', function() {
                var backupKey = $(this).data('backup-key');
                self.restoreBackup(backupKey);
            });
        },
        
        /**
         * Atualizar estado do botão de reset
         */
        updateResetButtonState: function() {
            var selectedExtensions = $('.uenf-extension-checkbox:checked').length;
            $('.uenf-reset-selected-extensions').prop('disabled', selectedExtensions === 0);
        },
        
        /**
         * Reset de extensões selecionadas
         */
        resetSelectedExtensions: function() {
            var self = this;
            var selectedExtensions = [];
            
            $('.uenf-extension-checkbox:checked').each(function() {
                selectedExtensions.push($(this).val());
            });
            
            if (selectedExtensions.length === 0) {
                this.showMessage('Nenhuma extensão selecionada.', 'error');
                return;
            }
            
            var extensionNames = selectedExtensions.map(function(id) {
                return $('.uenf-extension-checkbox[value="' + id + '"]')
                    .closest('.uenf-extension-item')
                    .find('.uenf-extension-name')
                    .text();
            }).join(', ');
            
            var confirmMessage = uenfResetManager.messages.confirmReset + '\n\nExtensões: ' + extensionNames;
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            // Mostrar loading
            this.setLoadingState('.uenf-reset-selected-extensions', true);
            
            // Processar cada extensão
            var promises = selectedExtensions.map(function(extensionId) {
                return self.resetExtension(extensionId);
            });
            
            Promise.all(promises)
                .then(function(results) {
                    var successCount = results.filter(function(r) { return r.success; }).length;
                    var totalCount = results.length;
                    
                    if (successCount === totalCount) {
                        self.showMessage(uenfResetManager.messages.resetSuccess, 'success');
                        self.refreshCustomizer();
                    } else {
                        self.showMessage(
                            'Reset parcialmente concluído: ' + successCount + '/' + totalCount + ' extensões resetadas.',
                            'warning'
                        );
                    }
                })
                .catch(function(error) {
                    self.showMessage(uenfResetManager.messages.resetError, 'error');
                    console.error('Reset error:', error);
                })
                .finally(function() {
                    self.setLoadingState('.uenf-reset-selected-extensions', false);
                    $('.uenf-extension-checkbox').prop('checked', false);
                    self.updateResetButtonState();
                });
        },
        
        /**
         * Reset de uma extensão específica
         */
        resetExtension: function(extensionId) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: uenfResetManager.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'uenf_reset_extension_settings',
                        extension_id: extensionId,
                        nonce: uenfResetManager.nonce
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        },
        
        /**
         * Reset de todas as configurações
         */
        resetAllSettings: function() {
            var self = this;
            
            if (!confirm(uenfResetManager.messages.confirmResetAll)) {
                return;
            }
            
            // Segundo nível de confirmação
            var finalConfirm = prompt(
                'Para confirmar, digite "RESETAR" (em maiúsculas):'
            );
            
            if (finalConfirm !== 'RESETAR') {
                this.showMessage('Reset cancelado.', 'info');
                return;
            }
            
            // Mostrar loading
            this.setLoadingState('.uenf-reset-all-settings', true);
            
            // Criar backup antes do reset
            this.createBackup()
                .then(function() {
                    // Executar reset completo
                    return $.ajax({
                        url: uenfResetManager.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'uenf_reset_all_settings',
                            nonce: uenfResetManager.nonce
                        }
                    });
                })
                .then(function(response) {
                    if (response.success) {
                        self.showMessage(response.message, 'success');
                        // Recarregar a página após um breve delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        self.showMessage(response.message || uenfResetManager.messages.resetError, 'error');
                    }
                })
                .catch(function(error) {
                    self.showMessage(uenfResetManager.messages.resetError, 'error');
                    console.error('Reset all error:', error);
                })
                .finally(function() {
                    self.setLoadingState('.uenf-reset-all-settings', false);
                    $('.uenf-confirm-reset-all').prop('checked', false);
                    $('.uenf-reset-all-settings').prop('disabled', true);
                });
        },
        
        /**
         * Criar backup
         */
        createBackup: function() {
            var self = this;
            
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: uenfResetManager.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'uenf_create_backup',
                        nonce: uenfResetManager.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            self.showMessage('Backup criado com sucesso!', 'success');
                            resolve(response);
                        } else {
                            self.showMessage('Erro ao criar backup.', 'error');
                            reject(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        self.showMessage('Erro ao criar backup.', 'error');
                        reject(error);
                    }
                });
            });
        },
        
        /**
         * Restaurar backup
         */
        restoreBackup: function(backupKey) {
            var self = this;
            
            if (!confirm('Tem certeza que deseja restaurar este backup? As configurações atuais serão perdidas.')) {
                return;
            }
            
            $.ajax({
                url: uenfResetManager.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'uenf_restore_backup',
                    backup_key: backupKey,
                    nonce: uenfResetManager.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.showMessage('Backup restaurado com sucesso!', 'success');
                        self.refreshCustomizer();
                    } else {
                        self.showMessage('Erro ao restaurar backup.', 'error');
                    }
                },
                error: function() {
                    self.showMessage('Erro ao restaurar backup.', 'error');
                }
            });
        },
        
        /**
         * Definir estado de loading
         */
        setLoadingState: function(selector, isLoading) {
            var $button = $(selector);
            
            if (isLoading) {
                $button.prop('disabled', true)
                       .data('original-text', $button.text())
                       .text(uenfResetManager.messages.processing);
            } else {
                $button.prop('disabled', false)
                       .text($button.data('original-text') || $button.text());
            }
        },
        
        /**
         * Mostrar mensagem
         */
        showMessage: function(message, type) {
            type = type || 'info';
            
            // Remover mensagens anteriores
            $('.uenf-reset-message').remove();
            
            var $message = $('<div class="uenf-reset-message uenf-message-' + type + '">' + message + '</div>');
            
            // Adicionar estilos
            $message.css({
                'position': 'fixed',
                'top': '50px',
                'right': '20px',
                'padding': '15px 20px',
                'border-radius': '4px',
                'color': 'white',
                'font-weight': 'bold',
                'z-index': '999999',
                'max-width': '300px',
                'word-wrap': 'break-word'
            });
            
            // Cores por tipo
            var colors = {
                'success': '#28a745',
                'error': '#dc3545',
                'warning': '#ffc107',
                'info': '#17a2b8'
            };
            
            $message.css('background-color', colors[type] || colors.info);
            
            // Adicionar ao body
            $('body').append($message);
            
            // Remover após 5 segundos
            setTimeout(function() {
                $message.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        },
        
        /**
         * Atualizar customizer
         */
        refreshCustomizer: function() {
            if (typeof wp !== 'undefined' && wp.customize) {
                // Recarregar preview
                wp.customize.previewer.refresh();
                
                // Notificar que houve mudanças
                wp.customize.state('saved').set(false);
            }
        }
    };
    
    // Inicializar quando o customizer estiver pronto
    wp.customize.bind('ready', function() {
        UENFResetManager.init();
    });
    
    // Fallback para inicialização
    $(document).ready(function() {
        if (typeof wp === 'undefined' || !wp.customize) {
            UENFResetManager.init();
        }
    });
    
})(jQuery);