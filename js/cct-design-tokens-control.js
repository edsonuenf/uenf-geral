/**
 * CCT Design Tokens Control JavaScript
 * Controle personalizado para gerenciamento de design tokens no customizer
 */

(function($) {
    'use strict';

    // Aguardar o carregamento do customizer
    wp.customize = wp.customize || {};

    /**
     * Controle personalizado para design tokens
     */
    var CCTDesignTokensControl = {
        
        /**
         * Inicialização
         */
        init: function() {
            this.setupTokenManager();
            this.bindEvents();
            this.loadDefaultTokens();
            console.log('CCT Design Tokens Control: Initialized');
        },

        /**
         * Configuração do gerenciador de tokens
         */
        setupTokenManager: function() {
            // Criar interface do gerenciador se não existir
            if (!$('.cct-design-tokens-control').length) {
                this.createManagerInterface();
            }
        },

        /**
         * Criar interface do gerenciador
         */
        createManagerInterface: function() {
            var managerHTML = `
                <div class="cct-design-tokens-control">
                    <div class="cct-tokens-header">
                        <h3>Gerenciador de Design Tokens</h3>
                        <div class="cct-tokens-actions">
                            <button class="button button-secondary cct-add-token">Adicionar Token</button>
                            <button class="button button-secondary cct-export-tokens">Exportar</button>
                            <button class="button button-secondary cct-import-tokens">Importar</button>
                        </div>
                    </div>
                    
                    <div class="cct-tokens-categories">
                        <div class="cct-category-tabs">
                            <button class="cct-tab active" data-category="colors">Cores</button>
                            <button class="cct-tab" data-category="typography">Tipografia</button>
                            <button class="cct-tab" data-category="spacing">Espaçamentos</button>
                            <button class="cct-tab" data-category="shadows">Sombras</button>
                            <button class="cct-tab" data-category="borders">Bordas</button>
                            <button class="cct-tab" data-category="custom">Personalizado</button>
                        </div>
                    </div>
                    
                    <div class="cct-tokens-content">
                        <div class="cct-tokens-list" data-category="colors">
                            <!-- Tokens de cores -->
                        </div>
                        <div class="cct-tokens-list" data-category="typography" style="display: none;">
                            <!-- Tokens de tipografia -->
                        </div>
                        <div class="cct-tokens-list" data-category="spacing" style="display: none;">
                            <!-- Tokens de espaçamentos -->
                        </div>
                        <div class="cct-tokens-list" data-category="shadows" style="display: none;">
                            <!-- Tokens de sombras -->
                        </div>
                        <div class="cct-tokens-list" data-category="borders" style="display: none;">
                            <!-- Tokens de bordas -->
                        </div>
                        <div class="cct-tokens-list" data-category="custom" style="display: none;">
                            <!-- Tokens personalizados -->
                        </div>
                    </div>
                    
                    <div class="cct-tokens-preview">
                        <h4>Preview</h4>
                        <div class="cct-preview-container">
                            <div class="cct-preview-element">Elemento de Preview</div>
                        </div>
                    </div>
                </div>
            `;
            
            // Adicionar à interface do customizer
            $('.customize-control-cct_design_tokens_manager').append(managerHTML);
        },

        /**
         * Carregar tokens padrão
         */
        loadDefaultTokens: function() {
            var defaultTokens = {
                colors: [
                    { name: 'primary', value: '#0073aa', description: 'Cor primária' },
                    { name: 'secondary', value: '#005177', description: 'Cor secundária' },
                    { name: 'accent', value: '#00a0d2', description: 'Cor de destaque' },
                    { name: 'text', value: '#333333', description: 'Cor do texto' },
                    { name: 'background', value: '#ffffff', description: 'Cor de fundo' },
                    { name: 'border', value: '#dddddd', description: 'Cor da borda' }
                ],
                typography: [
                    { name: 'font-family-primary', value: 'system-ui, sans-serif', description: 'Fonte primária' },
                    { name: 'font-family-secondary', value: 'Georgia, serif', description: 'Fonte secundária' },
                    { name: 'font-size-base', value: '16px', description: 'Tamanho base' },
                    { name: 'font-size-small', value: '14px', description: 'Tamanho pequeno' },
                    { name: 'font-size-large', value: '18px', description: 'Tamanho grande' },
                    { name: 'line-height-base', value: '1.6', description: 'Altura da linha base' }
                ],
                spacing: [
                    { name: 'space-xs', value: '4px', description: 'Espaçamento extra pequeno' },
                    { name: 'space-sm', value: '8px', description: 'Espaçamento pequeno' },
                    { name: 'space-md', value: '16px', description: 'Espaçamento médio' },
                    { name: 'space-lg', value: '24px', description: 'Espaçamento grande' },
                    { name: 'space-xl', value: '32px', description: 'Espaçamento extra grande' },
                    { name: 'space-xxl', value: '48px', description: 'Espaçamento extra extra grande' }
                ],
                shadows: [
                    { name: 'shadow-sm', value: '0 1px 3px rgba(0,0,0,0.12)', description: 'Sombra pequena' },
                    { name: 'shadow-md', value: '0 4px 6px rgba(0,0,0,0.1)', description: 'Sombra média' },
                    { name: 'shadow-lg', value: '0 10px 25px rgba(0,0,0,0.15)', description: 'Sombra grande' },
                    { name: 'shadow-xl', value: '0 20px 40px rgba(0,0,0,0.2)', description: 'Sombra extra grande' }
                ],
                borders: [
                    { name: 'border-width-thin', value: '1px', description: 'Borda fina' },
                    { name: 'border-width-medium', value: '2px', description: 'Borda média' },
                    { name: 'border-width-thick', value: '4px', description: 'Borda grossa' },
                    { name: 'border-radius-sm', value: '4px', description: 'Raio pequeno' },
                    { name: 'border-radius-md', value: '8px', description: 'Raio médio' },
                    { name: 'border-radius-lg', value: '12px', description: 'Raio grande' }
                ]
            };
            
            this.renderTokens('colors', defaultTokens.colors);
            this.renderTokens('typography', defaultTokens.typography);
            this.renderTokens('spacing', defaultTokens.spacing);
            this.renderTokens('shadows', defaultTokens.shadows);
            this.renderTokens('borders', defaultTokens.borders);
        },

        /**
         * Renderizar tokens por categoria
         */
        renderTokens: function(category, tokens) {
            var container = $(`.cct-tokens-list[data-category="${category}"]`);
            container.empty();
            
            tokens.forEach(function(token, index) {
                var tokenHTML = `
                    <div class="cct-token-item" data-category="${category}" data-index="${index}">
                        <div class="cct-token-info">
                            <div class="cct-token-name-group">
                                <input type="text" class="cct-token-name" value="${token.name}" placeholder="Nome do token">
                                <span class="cct-token-var">--cct-${token.name}</span>
                            </div>
                            <div class="cct-token-value-group">
                                ${this.getValueInput(category, token.value)}
                                <div class="cct-token-preview" style="${this.getPreviewStyle(category, token.value)}"></div>
                            </div>
                            <input type="text" class="cct-token-description" value="${token.description}" placeholder="Descrição">
                        </div>
                        <div class="cct-token-actions">
                            <button class="button button-small cct-duplicate-token" title="Duplicar">⧉</button>
                            <button class="button button-small cct-delete-token" title="Excluir">×</button>
                        </div>
                    </div>
                `;
                
                container.append(tokenHTML);
            }.bind(this));
        },

        /**
         * Obter input apropriado para o tipo de valor
         */
        getValueInput: function(category, value) {
            switch(category) {
                case 'colors':
                    return `<input type="color" class="cct-token-value color-input" value="${value}">`;
                case 'typography':
                    if (value.includes('px') || value.includes('em') || value.includes('rem')) {
                        return `<input type="text" class="cct-token-value" value="${value}" placeholder="ex: 16px, 1.2em">`;
                    }
                    return `<input type="text" class="cct-token-value" value="${value}" placeholder="ex: Arial, sans-serif">`;
                case 'spacing':
                    return `<input type="text" class="cct-token-value" value="${value}" placeholder="ex: 16px, 1rem">`;
                case 'shadows':
                    return `<input type="text" class="cct-token-value" value="${value}" placeholder="ex: 0 2px 4px rgba(0,0,0,0.1)">`;
                case 'borders':
                    return `<input type="text" class="cct-token-value" value="${value}" placeholder="ex: 1px, 4px">`;
                default:
                    return `<input type="text" class="cct-token-value" value="${value}" placeholder="Valor">`;
            }
        },

        /**
         * Obter estilo de preview
         */
        getPreviewStyle: function(category, value) {
            switch(category) {
                case 'colors':
                    return `background-color: ${value}; width: 30px; height: 30px; border: 1px solid #ddd; border-radius: 4px;`;
                case 'spacing':
                    return `width: ${value}; height: 20px; background-color: #0073aa; border-radius: 2px;`;
                case 'shadows':
                    return `width: 40px; height: 20px; background-color: #f0f0f0; box-shadow: ${value}; border-radius: 4px;`;
                case 'borders':
                    if (value.includes('radius')) {
                        return `width: 30px; height: 30px; background-color: #f0f0f0; border: 2px solid #0073aa; border-radius: ${value};`;
                    }
                    return `width: 40px; height: 20px; background-color: #f0f0f0; border: ${value} solid #0073aa;`;
                default:
                    return `width: 40px; height: 20px; background-color: #f0f0f0; border: 1px solid #ddd;`;
            }
        },

        /**
         * Adicionar novo token
         */
        addToken: function(category) {
            var newToken = {
                name: 'novo-token',
                value: this.getDefaultValue(category),
                description: 'Novo token'
            };
            
            var tokenHTML = `
                <div class="cct-token-item" data-category="${category}" data-index="new">
                    <div class="cct-token-info">
                        <div class="cct-token-name-group">
                            <input type="text" class="cct-token-name" value="${newToken.name}" placeholder="Nome do token">
                            <span class="cct-token-var">--cct-${newToken.name}</span>
                        </div>
                        <div class="cct-token-value-group">
                            ${this.getValueInput(category, newToken.value)}
                            <div class="cct-token-preview" style="${this.getPreviewStyle(category, newToken.value)}"></div>
                        </div>
                        <input type="text" class="cct-token-description" value="${newToken.description}" placeholder="Descrição">
                    </div>
                    <div class="cct-token-actions">
                        <button class="button button-small cct-duplicate-token" title="Duplicar">⧉</button>
                        <button class="button button-small cct-delete-token" title="Excluir">×</button>
                    </div>
                </div>
            `;
            
            $(`.cct-tokens-list[data-category="${category}"]`).append(tokenHTML);
        },

        /**
         * Obter valor padrão por categoria
         */
        getDefaultValue: function(category) {
            var defaults = {
                colors: '#0073aa',
                typography: '16px',
                spacing: '16px',
                shadows: '0 2px 4px rgba(0,0,0,0.1)',
                borders: '1px',
                custom: 'valor'
            };
            
            return defaults[category] || 'valor';
        },

        /**
         * Exportar tokens
         */
        exportTokens: function() {
            var tokens = this.getAllTokens();
            var exportData = {
                version: '1.0',
                timestamp: new Date().toISOString(),
                tokens: tokens
            };
            
            var dataStr = JSON.stringify(exportData, null, 2);
            var dataBlob = new Blob([dataStr], {type: 'application/json'});
            
            var link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = 'design-tokens-' + Date.now() + '.json';
            link.click();
        },

        /**
         * Obter todos os tokens
         */
        getAllTokens: function() {
            var tokens = {};
            
            $('.cct-tokens-list').each(function() {
                var category = $(this).data('category');
                tokens[category] = [];
                
                $(this).find('.cct-token-item').each(function() {
                    var item = $(this);
                    var token = {
                        name: item.find('.cct-token-name').val(),
                        value: item.find('.cct-token-value').val(),
                        description: item.find('.cct-token-description').val()
                    };
                    
                    tokens[category].push(token);
                });
            });
            
            return tokens;
        },

        /**
         * Vincular eventos
         */
        bindEvents: function() {
            var self = this;
            
            // Trocar categoria
            $(document).on('click', '.cct-tab', function() {
                var category = $(this).data('category');
                
                $('.cct-tab').removeClass('active');
                $(this).addClass('active');
                
                $('.cct-tokens-list').hide();
                $(`.cct-tokens-list[data-category="${category}"]`).show();
            });
            
            // Adicionar token
            $(document).on('click', '.cct-add-token', function() {
                var activeCategory = $('.cct-tab.active').data('category');
                self.addToken(activeCategory);
            });
            
            // Exportar tokens
            $(document).on('click', '.cct-export-tokens', function() {
                self.exportTokens();
            });
            
            // Excluir token
            $(document).on('click', '.cct-delete-token', function() {
                if (confirm('Tem certeza que deseja excluir este token?')) {
                    $(this).closest('.cct-token-item').remove();
                }
            });
            
            // Duplicar token
            $(document).on('click', '.cct-duplicate-token', function() {
                var item = $(this).closest('.cct-token-item');
                var clone = item.clone();
                clone.find('.cct-token-name').val(clone.find('.cct-token-name').val() + '-copy');
                item.after(clone);
            });
            
            // Atualizar preview quando valor mudar
            $(document).on('input change', '.cct-token-value', function() {
                var item = $(this).closest('.cct-token-item');
                var category = item.data('category');
                var value = $(this).val();
                var preview = item.find('.cct-token-preview');
                
                preview.attr('style', self.getPreviewStyle(category, value));
            });
            
            // Atualizar variável CSS quando nome mudar
            $(document).on('input', '.cct-token-name', function() {
                var item = $(this).closest('.cct-token-item');
                var name = $(this).val();
                item.find('.cct-token-var').text('--cct-' + name);
            });
        }
    };

    /**
     * Inicialização
     */
    $(document).ready(function() {
        // Verificar se estamos no customizer
        if (typeof wp.customize !== 'undefined') {
            CCTDesignTokensControl.init();
        }
    });

    // Expor objeto globalmente
    window.CCTDesignTokensControl = CCTDesignTokensControl;

})(jQuery);
