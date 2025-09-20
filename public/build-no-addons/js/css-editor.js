/**
 * Editor CSS Avançado - JavaScript
 * 
 * Implementa funcionalidades avançadas do editor CSS incluindo:
 * - Syntax highlighting com CodeMirror
 * - Backup automático
 * - Validação em tempo real
 * - Atalhos de teclado
 * - Ferramentas de formatação
 * 
 * @package CCT_Theme
 * @subpackage Design_Editor
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Classe principal do Editor CSS
     */
    class CCTCSSEditor {
        
        constructor() {
            this.editor = null;
            this.currentFile = window.cctEditorData?.currentFile || 'style.css';
            this.unsavedChanges = false;
            this.autoSaveInterval = null;
            this.validationTimeout = null;
            
            this.init();
        }
        
        /**
         * Inicializa o editor
         */
        init() {
            this.initCodeMirror();
            this.bindEvents();
            this.initAutoSave();
            this.initKeyboardShortcuts();
            this.updateStats();
            
            console.log('CCT CSS Editor: Initialized');
        }
        
        /**
         * Inicializa o CodeMirror
         */
        initCodeMirror() {
            const textarea = document.getElementById('cct-css-editor');
            
            if (!textarea) {
                console.error('CCT CSS Editor: Textarea not found');
                return;
            }
            
            this.editor = CodeMirror.fromTextArea(textarea, {
                mode: 'css',
                theme: 'material',
                lineNumbers: true,
                lineWrapping: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                indentUnit: 2,
                tabSize: 2,
                indentWithTabs: false,
                foldGutter: true,
                gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
                extraKeys: {
                    'Ctrl-S': () => this.saveCSS(),
                    'Cmd-S': () => this.saveCSS(),
                    'Ctrl-F': 'findPersistent',
                    'Cmd-F': 'findPersistent',
                    'F11': (cm) => {
                        cm.setOption('fullScreen', !cm.getOption('fullScreen'));
                    },
                    'Esc': (cm) => {
                        if (cm.getOption('fullScreen')) cm.setOption('fullScreen', false);
                    }
                },
                hintOptions: {
                    completeSingle: false
                }
            });
            
            // Eventos do editor
            this.editor.on('change', () => {
                this.onEditorChange();
            });
            
            this.editor.on('cursorActivity', () => {
                this.updateCursorPosition();
            });
            
            // Redimensionar editor
            this.editor.setSize('100%', '500px');
        }
        
        /**
         * Vincula eventos da interface
         */
        bindEvents() {
            // Seletor de arquivo
            $('#cct-file-select').on('change', (e) => {
                this.changeFile(e.target.value);
            });
            
            // Botões da toolbar
            $('#cct-save-css').on('click', () => this.saveCSS());
            $('#cct-backup-css').on('click', () => this.createBackup());
            $('#cct-validate-css').on('click', () => this.validateCSS());
            
            // Ferramentas
            $('#cct-format-css').on('click', () => this.formatCSS());
            $('#cct-minify-css').on('click', () => this.minifyCSS());
            $('#cct-add-prefixes').on('click', () => this.addPrefixes());
            
            // Restaurar backup
            $(document).on('click', '.cct-restore-backup', (e) => {
                const backup = $(e.target).closest('.cct-restore-backup').data('backup');
                this.confirmRestore(backup);
            });
            
            // Modal
            $('.cct-modal-close, #cct-confirm-cancel').on('click', () => {
                this.hideModal();
            });
            
            $('#cct-confirm-ok').on('click', () => {
                this.executeConfirmedAction();
            });
            
            // Aviso de saída sem salvar
            $(window).on('beforeunload', (e) => {
                if (this.unsavedChanges) {
                    const message = 'Você tem alterações não salvas. Deseja realmente sair?';
                    e.returnValue = message;
                    return message;
                }
            });
        }
        
        /**
         * Inicializa auto-save
         */
        initAutoSave() {
            // Auto-save a cada 2 minutos
            this.autoSaveInterval = setInterval(() => {
                if (this.unsavedChanges) {
                    this.autoSave();
                }
            }, 120000);
        }
        
        /**
         * Inicializa atalhos de teclado
         */
        initKeyboardShortcuts() {
            $(document).on('keydown', (e) => {
                // Ctrl/Cmd + S para salvar
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    this.saveCSS();
                }
                
                // Ctrl/Cmd + B para backup
                if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                    e.preventDefault();
                    this.createBackup();
                }
                
                // Ctrl/Cmd + E para validar
                if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                    e.preventDefault();
                    this.validateCSS();
                }
            });
        }
        
        /**
         * Evento de mudança no editor
         */
        onEditorChange() {
            this.unsavedChanges = true;
            this.updateSaveStatus('unsaved');
            this.updateStats();
            
            // Validação com debounce
            clearTimeout(this.validationTimeout);
            this.validationTimeout = setTimeout(() => {
                this.validateCSS(true); // Validação silenciosa
            }, 1000);
        }
        
        /**
         * Atualiza posição do cursor
         */
        updateCursorPosition() {
            if (!this.editor) return;
            
            const cursor = this.editor.getCursor();
            $('#cct-line-number').text(cursor.line + 1);
            $('#cct-column-number').text(cursor.ch + 1);
        }
        
        /**
         * Atualiza estatísticas
         */
        updateStats() {
            if (!this.editor) return;
            
            const content = this.editor.getValue();
            const lines = content.split('\n').length;
            const chars = content.length;
            const selectors = (content.match(/{/g) || []).length;
            const comments = (content.match(/\/\*/g) || []).length;
            
            $('#cct-lines-count').text(lines);
            $('#cct-chars-count').text(chars);
            $('#cct-selectors-count').text(selectors);
            $('#cct-comments-count').text(comments);
        }
        
        /**
         * Muda arquivo sendo editado
         */
        changeFile(fileKey) {
            if (this.unsavedChanges) {
                if (!confirm('Você tem alterações não salvas. Deseja continuar?')) {
                    $('#cct-file-select').val(this.currentFile);
                    return;
                }
            }
            
            window.location.href = `?page=cct-css-editor&file=${encodeURIComponent(fileKey)}`;
        }
        
        /**
         * Salva CSS
         */
        saveCSS() {
            if (!this.editor) return;
            
            this.updateSaveStatus('saving');
            
            const data = {
                action: 'cct_save_css',
                nonce: cctCssEditor.nonce,
                file: this.currentFile,
                content: this.editor.getValue()
            };
            
            $.post(cctCssEditor.ajaxUrl, data)
                .done((response) => {
                    if (response.success) {
                        this.unsavedChanges = false;
                        this.updateSaveStatus('saved');
                        this.showNotification('success', response.data.message);
                        this.refreshBackupsList();
                    } else {
                        this.updateSaveStatus('error');
                        this.showNotification('error', response.data.message || 'Erro ao salvar');
                        
                        if (response.data.errors) {
                            this.showValidationErrors(response.data.errors);
                        }
                    }
                })
                .fail(() => {
                    this.updateSaveStatus('error');
                    this.showNotification('error', 'Erro de conexão ao salvar');
                });
        }
        
        /**
         * Auto-save silencioso
         */
        autoSave() {
            if (!this.editor || !this.unsavedChanges) return;
            
            const data = {
                action: 'cct_save_css',
                nonce: cctCssEditor.nonce,
                file: this.currentFile,
                content: this.editor.getValue()
            };
            
            $.post(cctCssEditor.ajaxUrl, data)
                .done((response) => {
                    if (response.success) {
                        this.unsavedChanges = false;
                        this.updateSaveStatus('auto-saved');
                    }
                });
        }
        
        /**
         * Cria backup
         */
        createBackup() {
            const data = {
                action: 'cct_backup_css',
                nonce: cctCssEditor.nonce,
                file: this.currentFile
            };
            
            $.post(cctCssEditor.ajaxUrl, data)
                .done((response) => {
                    if (response.success) {
                        this.showNotification('success', response.data.message);
                        this.refreshBackupsList();
                    } else {
                        this.showNotification('error', response.data.message || 'Erro ao criar backup');
                    }
                })
                .fail(() => {
                    this.showNotification('error', 'Erro de conexão ao criar backup');
                });
        }
        
        /**
         * Valida CSS
         */
        validateCSS(silent = false) {
            if (!this.editor) return;
            
            const data = {
                action: 'cct_validate_css',
                nonce: cctCssEditor.nonce,
                content: this.editor.getValue()
            };
            
            $.post(cctCssEditor.ajaxUrl, data)
                .done((response) => {
                    if (response.success) {
                        const validation = response.data;
                        
                        if (validation.valid) {
                            this.updateValidationStatus('valid');
                            if (!silent) {
                                this.showNotification('success', cctCssEditor.messages.validation_success);
                            }
                        } else {
                            this.updateValidationStatus('invalid');
                            if (!silent) {
                                this.showValidationErrors(validation.errors);
                            }
                        }
                    }
                })
                .fail(() => {
                    this.updateValidationStatus('error');
                    if (!silent) {
                        this.showNotification('error', 'Erro ao validar CSS');
                    }
                });
        }
        
        /**
         * Formata CSS
         */
        formatCSS() {
            if (!this.editor) return;
            
            let css = this.editor.getValue();
            
            // Formatação básica de CSS
            css = css
                .replace(/\s*{\s*/g, ' {\n  ')
                .replace(/;\s*/g, ';\n  ')
                .replace(/\s*}\s*/g, '\n}\n\n')
                .replace(/,\s*/g, ',\n')
                .replace(/\n\s*\n\s*\n/g, '\n\n')
                .trim();
            
            this.editor.setValue(css);
            this.showNotification('success', 'CSS formatado com sucesso!');
        }
        
        /**
         * Minifica CSS
         */
        minifyCSS() {
            if (!this.editor) return;
            
            let css = this.editor.getValue();
            
            // Minificação básica
            css = css
                .replace(/\/\*[\s\S]*?\*\//g, '') // Remove comentários
                .replace(/\s+/g, ' ') // Múltiplos espaços em um
                .replace(/;\s*}/g, '}') // Remove ; antes de }
                .replace(/\s*{\s*/g, '{') // Remove espaços ao redor de {
                .replace(/;\s*/g, ';') // Remove espaços após ;
                .replace(/,\s*/g, ',') // Remove espaços após ,
                .trim();
            
            this.editor.setValue(css);
            this.showNotification('success', 'CSS minificado com sucesso!');
        }
        
        /**
         * Adiciona prefixos CSS
         */
        addPrefixes() {
            if (!this.editor) return;
            
            let css = this.editor.getValue();
            
            // Prefixos básicos para propriedades comuns
            const prefixes = {
                'transform': ['-webkit-transform', '-moz-transform', '-ms-transform'],
                'transition': ['-webkit-transition', '-moz-transition', '-ms-transition'],
                'border-radius': ['-webkit-border-radius', '-moz-border-radius'],
                'box-shadow': ['-webkit-box-shadow', '-moz-box-shadow'],
                'user-select': ['-webkit-user-select', '-moz-user-select', '-ms-user-select']
            };
            
            Object.keys(prefixes).forEach(property => {
                const regex = new RegExp(`(\\s+)${property}\\s*:`, 'g');
                css = css.replace(regex, (match, indent) => {
                    const prefixed = prefixes[property].map(prefix => `${indent}${prefix}:`).join('\n');
                    return `\n${prefixed}\n${indent}${property}:`;
                });
            });
            
            this.editor.setValue(css);
            this.showNotification('success', 'Prefixos CSS adicionados!');
        }
        
        /**
         * Confirma restauração de backup
         */
        confirmRestore(backup) {
            this.pendingAction = {
                type: 'restore',
                backup: backup
            };
            
            this.showModal(
                'Tem certeza que deseja restaurar este backup?\n\nAs alterações atuais serão perdidas.'
            );
        }
        
        /**
         * Restaura backup
         */
        restoreBackup(backup) {
            const data = {
                action: 'cct_restore_css',
                nonce: cctCssEditor.nonce,
                backup: backup
            };
            
            $.post(cctCssEditor.ajaxUrl, data)
                .done((response) => {
                    if (response.success) {
                        this.editor.setValue(response.data.content);
                        this.unsavedChanges = false;
                        this.updateSaveStatus('saved');
                        this.showNotification('success', response.data.message);
                        this.refreshBackupsList();
                    } else {
                        this.showNotification('error', response.data.message || 'Erro ao restaurar backup');
                    }
                })
                .fail(() => {
                    this.showNotification('error', 'Erro de conexão ao restaurar backup');
                });
        }
        
        /**
         * Atualiza status de salvamento
         */
        updateSaveStatus(status) {
            const $indicator = $('#cct-save-status');
            $indicator.removeClass('saved unsaved saving error auto-saved');
            $indicator.addClass(status);
            
            const messages = {
                'saved': 'Salvo',
                'unsaved': 'Não salvo',
                'saving': 'Salvando...',
                'error': 'Erro ao salvar',
                'auto-saved': 'Auto-salvo'
            };
            
            $indicator.text(messages[status] || '');
        }
        
        /**
         * Atualiza status de validação
         */
        updateValidationStatus(status) {
            const $indicator = $('#cct-validation-status');
            $indicator.removeClass('valid invalid error');
            $indicator.addClass(status);
            
            const messages = {
                'valid': 'CSS Válido',
                'invalid': 'CSS Inválido',
                'error': 'Erro na validação'
            };
            
            $indicator.text(messages[status] || '');
        }
        
        /**
         * Mostra erros de validação
         */
        showValidationErrors(errors) {
            let message = cctCssEditor.messages.validation_error + '\n\n';
            message += errors.join('\n');
            this.showNotification('error', message);
        }
        
        /**
         * Atualiza lista de backups
         */
        refreshBackupsList() {
            // Recarregar a página para atualizar a lista
            // Em uma implementação mais avançada, isso seria feito via AJAX
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
        
        /**
         * Mostra modal de confirmação
         */
        showModal(message) {
            $('#cct-confirm-message').text(message);
            $('#cct-confirm-modal').show();
        }
        
        /**
         * Esconde modal
         */
        hideModal() {
            $('#cct-confirm-modal').hide();
            this.pendingAction = null;
        }
        
        /**
         * Executa ação confirmada
         */
        executeConfirmedAction() {
            if (this.pendingAction) {
                switch (this.pendingAction.type) {
                    case 'restore':
                        this.restoreBackup(this.pendingAction.backup);
                        break;
                }
            }
            this.hideModal();
        }
        
        /**
         * Mostra notificação
         */
        showNotification(type, message) {
            const $notification = $(`
                <div class="cct-notification cct-notification-${type}">
                    <span class="cct-notification-message">${message}</span>
                    <button type="button" class="cct-notification-close">&times;</button>
                </div>
            `);
            
            $('#cct-notifications').append($notification);
            
            // Auto-remover após 5 segundos
            setTimeout(() => {
                $notification.fadeOut(() => {
                    $notification.remove();
                });
            }, 5000);
            
            // Remover ao clicar no X
            $notification.find('.cct-notification-close').on('click', () => {
                $notification.fadeOut(() => {
                    $notification.remove();
                });
            });
        }
    }

    /**
     * Inicializar quando o documento estiver pronto
     */
    $(document).ready(function() {
        // Verificar se CodeMirror está disponível
        if (typeof CodeMirror === 'undefined') {
            console.error('CCT CSS Editor: CodeMirror not loaded');
            return;
        }
        
        // Inicializar editor
        window.cctCSSEditor = new CCTCSSEditor();
    });

})(jQuery);