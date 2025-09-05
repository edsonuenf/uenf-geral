/**
 * Sistema de Validação Visual do Customizer CCT
 * 
 * Fornece feedback visual em tempo real durante alterações no customizer,
 * incluindo validação de valores, indicadores de status e mensagens de erro.
 * 
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Classe principal de validação visual
     */
    class CCTCustomizerValidation {
        
        constructor() {
            this.init();
        }
        
        /**
         * Inicializa o sistema de validação
         */
        init() {
            this.setupValidationStyles();
            this.bindValidationEvents();
            this.createNotificationSystem();
            console.log('CCT Customizer Validation: Initialized');
        }
        
        /**
         * Adiciona estilos CSS para validação
         */
        setupValidationStyles() {
            const styles = `
                <style id="cct-validation-styles">
                    .cct-validation-success {
                        border-left: 4px solid #46b450 !important;
                        background-color: rgba(70, 180, 80, 0.1) !important;
                    }
                    
                    .cct-validation-warning {
                        border-left: 4px solid #ffb900 !important;
                        background-color: rgba(255, 185, 0, 0.1) !important;
                    }
                    
                    .cct-validation-error {
                        border-left: 4px solid #dc3232 !important;
                        background-color: rgba(220, 50, 50, 0.1) !important;
                    }
                    
                    .cct-validation-message {
                        font-size: 12px;
                        margin-top: 5px;
                        padding: 5px 8px;
                        border-radius: 3px;
                        display: none;
                    }
                    
                    .cct-validation-message.success {
                        background-color: #d4edda;
                        color: #155724;
                        border: 1px solid #c3e6cb;
                    }
                    
                    .cct-validation-message.warning {
                        background-color: #fff3cd;
                        color: #856404;
                        border: 1px solid #ffeaa7;
                    }
                    
                    .cct-validation-message.error {
                        background-color: #f8d7da;
                        color: #721c24;
                        border: 1px solid #f5c6cb;
                    }
                    
                    .cct-notification {
                        position: fixed;
                        top: 32px;
                        right: 20px;
                        background: #fff;
                        border-left: 4px solid #46b450;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                        padding: 12px 16px;
                        margin-bottom: 10px;
                        z-index: 999999;
                        max-width: 300px;
                        opacity: 0;
                        transform: translateX(100%);
                        transition: all 0.3s ease;
                    }
                    
                    .cct-notification.show {
                        opacity: 1;
                        transform: translateX(0);
                    }
                    
                    .cct-notification.success {
                        border-left-color: #46b450;
                    }
                    
                    .cct-notification.warning {
                        border-left-color: #ffb900;
                    }
                    
                    .cct-notification.error {
                        border-left-color: #dc3232;
                    }
                    
                    .cct-notification-title {
                        font-weight: bold;
                        margin-bottom: 4px;
                    }
                    
                    .cct-notification-message {
                        font-size: 13px;
                        color: #666;
                    }
                    
                    .cct-validation-indicator {
                        display: inline-block;
                        width: 12px;
                        height: 12px;
                        border-radius: 50%;
                        margin-left: 8px;
                        vertical-align: middle;
                    }
                    
                    .cct-validation-indicator.success {
                        background-color: #46b450;
                    }
                    
                    .cct-validation-indicator.warning {
                        background-color: #ffb900;
                    }
                    
                    .cct-validation-indicator.error {
                        background-color: #dc3232;
                    }
                </style>
            `;
            
            $('head').append(styles);
        }
        
        /**
         * Vincula eventos de validação aos controles
         */
        bindValidationEvents() {
            // Validação de cores
            this.bindColorValidation();
            
            // Validação de dimensões
            this.bindDimensionValidation();
            
            // Validação de seletores
            this.bindSelectValidation();
            
            // Validação de texto
            this.bindTextValidation();
        }
        
        /**
         * Validação de controles de cor
         */
        bindColorValidation() {
            $(document).on('change', 'input[type="color"], .wp-color-picker', (e) => {
                const $input = $(e.target);
                const value = $input.val();
                const $control = $input.closest('.customize-control');
                
                if (this.isValidColor(value)) {
                    this.showValidation($control, 'success', 'Cor válida aplicada');
                } else {
                    this.showValidation($control, 'error', 'Formato de cor inválido');
                }
            });
        }
        
        /**
         * Validação de dimensões (largura, altura, etc.)
         */
        bindDimensionValidation() {
            $(document).on('input', 'input[id*="width"], input[id*="height"], input[id*="size"]', (e) => {
                const $input = $(e.target);
                const value = $input.val();
                const $control = $input.closest('.customize-control');
                
                if (this.isValidDimension(value)) {
                    this.showValidation($control, 'success', 'Dimensão válida');
                } else if (value && !this.isValidDimension(value)) {
                    this.showValidation($control, 'warning', 'Formato recomendado: 300px, 50%, 2em');
                }
            });
        }
        
        /**
         * Validação de seletores dropdown
         */
        bindSelectValidation() {
            $(document).on('change', 'select[id*="cct_"]', (e) => {
                const $select = $(e.target);
                const value = $select.val();
                const $control = $select.closest('.customize-control');
                
                if (value && value !== '') {
                    this.showValidation($control, 'success', 'Opção selecionada');
                    this.showNotification('success', 'Configuração Atualizada', `${$select.find('option:selected').text()} aplicado`);
                }
            });
        }
        
        /**
         * Validação de campos de texto
         */
        bindTextValidation() {
            $(document).on('input', 'input[type="text"][id*="cct_"], textarea[id*="cct_"]', (e) => {
                const $input = $(e.target);
                const value = $input.val();
                const $control = $input.closest('.customize-control');
                const maxLength = $input.attr('maxlength');
                
                if (maxLength && value.length > maxLength * 0.9) {
                    this.showValidation($control, 'warning', `Próximo do limite (${value.length}/${maxLength})`);
                } else if (value.length > 0) {
                    this.showValidation($control, 'success', 'Texto válido');
                }
            });
        }
        
        /**
         * Verifica se uma cor é válida
         */
        isValidColor(color) {
            // Hex colors
            if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color)) {
                return true;
            }
            
            // RGB/RGBA colors
            if (/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(,\s*[0-1]?(\.\d+)?)?\s*\)$/.test(color)) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Verifica se uma dimensão é válida
         */
        isValidDimension(dimension) {
            // Pixels, percentual, em, rem, vw, vh
            return /^\d+(\.\d+)?(px|%|em|rem|vw|vh)$/.test(dimension) || /^\d+$/.test(dimension);
        }
        
        /**
         * Mostra validação visual em um controle
         */
        showValidation($control, type, message) {
            // Remove classes anteriores
            $control.removeClass('cct-validation-success cct-validation-warning cct-validation-error');
            
            // Adiciona nova classe
            $control.addClass(`cct-validation-${type}`);
            
            // Adiciona indicador visual no label
            this.addValidationIndicator($control, type);
            
            // Mostra mensagem se fornecida
            if (message) {
                this.showValidationMessage($control, type, message);
            }
            
            // Remove validação após 3 segundos
            setTimeout(() => {
                $control.removeClass(`cct-validation-${type}`);
                this.removeValidationIndicator($control);
                this.hideValidationMessage($control);
            }, 3000);
        }
        
        /**
         * Adiciona indicador visual ao label
         */
        addValidationIndicator($control, type) {
            const $label = $control.find('.customize-control-title');
            $label.find('.cct-validation-indicator').remove();
            $label.append(`<span class="cct-validation-indicator ${type}"></span>`);
        }
        
        /**
         * Remove indicador visual
         */
        removeValidationIndicator($control) {
            $control.find('.cct-validation-indicator').remove();
        }
        
        /**
         * Mostra mensagem de validação
         */
        showValidationMessage($control, type, message) {
            let $message = $control.find('.cct-validation-message');
            
            if ($message.length === 0) {
                $message = $('<div class="cct-validation-message"></div>');
                $control.append($message);
            }
            
            $message
                .removeClass('success warning error')
                .addClass(type)
                .text(message)
                .fadeIn();
        }
        
        /**
         * Esconde mensagem de validação
         */
        hideValidationMessage($control) {
            $control.find('.cct-validation-message').fadeOut();
        }
        
        /**
         * Cria sistema de notificações
         */
        createNotificationSystem() {
            if ($('#cct-notification-container').length === 0) {
                $('body').append('<div id="cct-notification-container"></div>');
            }
        }
        
        /**
         * Mostra notificação
         */
        showNotification(type, title, message, duration = 4000) {
            const $notification = $(`
                <div class="cct-notification ${type}">
                    <div class="cct-notification-title">${title}</div>
                    <div class="cct-notification-message">${message}</div>
                </div>
            `);
            
            $('#cct-notification-container').append($notification);
            
            // Animar entrada
            setTimeout(() => {
                $notification.addClass('show');
            }, 100);
            
            // Remover após duração especificada
            setTimeout(() => {
                $notification.removeClass('show');
                setTimeout(() => {
                    $notification.remove();
                }, 300);
            }, duration);
        }
    }

    /**
     * Inicializar quando o customizer estiver pronto
     */
    $(document).ready(function() {
        // Aguardar carregamento do customizer
        if (typeof wp !== 'undefined' && wp.customize) {
            wp.customize.bind('ready', function() {
                new CCTCustomizerValidation();
            });
        } else {
            // Fallback para inicialização direta
            setTimeout(() => {
                new CCTCustomizerValidation();
            }, 1000);
        }
    });

})(jQuery);