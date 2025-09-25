/**
 * Customizer Layout Manager JavaScript
 * Gerenciador de layout para o customizer
 */

(function($) {
    'use strict';

    // Aguardar o carregamento do customizer
    wp.customize = wp.customize || {};

    /**
     * Gerenciador de layout do customizer
     */
    var CCTLayoutManager = {
        
        /**
         * Inicialização
         */
        init: function() {
            this.setupLayoutManager();
            this.bindEvents();
            this.loadDefaultLayouts();
            console.log('CCT Layout Manager: Initialized');
        },

        /**
         * Configuração do gerenciador de layout
         */
        setupLayoutManager: function() {
            // Criar interface do gerenciador se não existir
            if (!$('.cct-layout-manager-control').length) {
                this.createManagerInterface();
            }
        },

        /**
         * Criar interface do gerenciador
         */
        createManagerInterface: function() {
            var managerHTML = `
                <div class="cct-layout-manager-control">
                    <div class="cct-layout-header">
                        <h3>Gerenciador de Layout</h3>
                        <div class="cct-layout-actions">
                            <button class="button button-secondary cct-add-section">Adicionar Seção</button>
                            <button class="button button-secondary cct-save-layout">Salvar Layout</button>
                        </div>
                    </div>
                    
                    <div class="cct-layout-types">
                        <h4>Tipos de Layout</h4>
                        <div class="cct-layout-presets">
                            <button class="cct-layout-preset active" data-layout="single-column">
                                <div class="preset-preview single-column"></div>
                                <span>Coluna Única</span>
                            </button>
                            <button class="cct-layout-preset" data-layout="two-column-left">
                                <div class="preset-preview two-column-left"></div>
                                <span>Duas Colunas (Sidebar Esquerda)</span>
                            </button>
                            <button class="cct-layout-preset" data-layout="two-column-right">
                                <div class="preset-preview two-column-right"></div>
                                <span>Duas Colunas (Sidebar Direita)</span>
                            </button>
                            <button class="cct-layout-preset" data-layout="three-column">
                                <div class="preset-preview three-column"></div>
                                <span>Três Colunas</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="cct-grid-settings">
                        <h4>Configurações de Grid</h4>
                        <div class="cct-grid-controls">
                            <div class="cct-control-group">
                                <label>Colunas:</label>
                                <input type="range" class="cct-grid-columns" min="1" max="12" value="12">
                                <span class="cct-value">12</span>
                            </div>
                            <div class="cct-control-group">
                                <label>Gap:</label>
                                <input type="range" class="cct-grid-gap" min="0" max="50" value="20">
                                <span class="cct-value">20px</span>
                            </div>
                            <div class="cct-control-group">
                                <label>Max Width:</label>
                                <input type="number" class="cct-grid-max-width" min="800" max="2000" value="1200">
                                <span>px</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="cct-container-settings">
                        <h4>Configurações de Container</h4>
                        <div class="cct-container-types">
                            <button class="cct-container-type active" data-type="boxed">Boxed</button>
                            <button class="cct-container-type" data-type="fluid">Fluid</button>
                            <button class="cct-container-type" data-type="full-width">Full Width</button>
                        </div>
                        <div class="cct-container-controls">
                            <div class="cct-control-group">
                                <label>Padding:</label>
                                <input type="range" class="cct-container-padding" min="0" max="50" value="15">
                                <span class="cct-value">15px</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="cct-spacing-settings">
                        <h4>Configurações de Espaçamento</h4>
                        <div class="cct-spacing-controls">
                            <div class="cct-control-group">
                                <label>Seções:</label>
                                <input type="range" class="cct-section-spacing" min="0" max="100" value="40">
                                <span class="cct-value">40px</span>
                            </div>
                            <div class="cct-control-group">
                                <label>Elementos:</label>
                                <input type="range" class="cct-element-spacing" min="0" max="50" value="20">
                                <span class="cct-value">20px</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="cct-layout-preview">
                        <h4>Preview do Layout</h4>
                        <div class="cct-preview-container">
                            <div class="cct-preview-layout single-column">
                                <div class="cct-preview-header">Header</div>
                                <div class="cct-preview-content">
                                    <div class="cct-preview-main">Main Content</div>
                                    <div class="cct-preview-sidebar" style="display: none;">Sidebar</div>
                                    <div class="cct-preview-sidebar-secondary" style="display: none;">Sidebar 2</div>
                                </div>
                                <div class="cct-preview-footer">Footer</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Adicionar à interface do customizer
            $('.customize-control-cct_layout_manager').append(managerHTML);
        },

        /**
         * Carregar layouts padrão
         */
        loadDefaultLayouts: function() {
            this.updateLayoutPreview('single-column');
        },

        /**
         * Atualizar preview do layout
         */
        updateLayoutPreview: function(layoutType) {
            var previewContainer = $('.cct-preview-layout');
            var main = previewContainer.find('.cct-preview-main');
            var sidebar = previewContainer.find('.cct-preview-sidebar');
            var sidebarSecondary = previewContainer.find('.cct-preview-sidebar-secondary');
            
            // Reset
            previewContainer.removeClass('single-column two-column-left two-column-right three-column');
            sidebar.hide();
            sidebarSecondary.hide();
            
            // Aplicar layout
            previewContainer.addClass(layoutType);
            
            switch(layoutType) {
                case 'single-column':
                    main.css('width', '100%');
                    break;
                    
                case 'two-column-left':
                    main.css('width', '70%');
                    sidebar.css('width', '30%').show();
                    break;
                    
                case 'two-column-right':
                    main.css('width', '70%');
                    sidebar.css('width', '30%').show();
                    break;
                    
                case 'three-column':
                    main.css('width', '50%');
                    sidebar.css('width', '25%').show();
                    sidebarSecondary.css('width', '25%').show();
                    break;
            }
        },

        /**
         * Atualizar configurações de grid
         */
        updateGridSettings: function() {
            var columns = $('.cct-grid-columns').val();
            var gap = $('.cct-grid-gap').val();
            var maxWidth = $('.cct-grid-max-width').val();
            
            // Atualizar valores exibidos
            $('.cct-grid-columns').siblings('.cct-value').text(columns);
            $('.cct-grid-gap').siblings('.cct-value').text(gap + 'px');
            
            // Aplicar CSS dinâmico
            this.applyGridCSS(columns, gap, maxWidth);
        },

        /**
         * Aplicar CSS do grid
         */
        applyGridCSS: function(columns, gap, maxWidth) {
            var css = `
                .cct-grid {
                    display: grid;
                    grid-template-columns: repeat(${columns}, 1fr);
                    gap: ${gap}px;
                    max-width: ${maxWidth}px;
                    margin: 0 auto;
                }
                
                .cct-container {
                    max-width: ${maxWidth}px;
                    margin: 0 auto;
                }
            `;
            
            // Remove CSS anterior
            $('#cct-layout-grid-css').remove();
            
            // Adiciona novo CSS
            $('head').append('<style id="cct-layout-grid-css">' + css + '</style>');
        },

        /**
         * Atualizar configurações de container
         */
        updateContainerSettings: function() {
            var padding = $('.cct-container-padding').val();
            var type = $('.cct-container-type.active').data('type');
            
            // Atualizar valor exibido
            $('.cct-container-padding').siblings('.cct-value').text(padding + 'px');
            
            // Aplicar CSS dinâmico
            this.applyContainerCSS(type, padding);
        },

        /**
         * Aplicar CSS do container
         */
        applyContainerCSS: function(type, padding) {
            var css = '';
            
            switch(type) {
                case 'boxed':
                    css = `
                        .cct-container {
                            max-width: 1200px;
                            margin: 0 auto;
                            padding: 0 ${padding}px;
                        }
                    `;
                    break;
                    
                case 'fluid':
                    css = `
                        .cct-container {
                            width: 100%;
                            padding: 0 ${padding}px;
                        }
                    `;
                    break;
                    
                case 'full-width':
                    css = `
                        .cct-container {
                            width: 100%;
                            padding: 0;
                        }
                    `;
                    break;
            }
            
            // Remove CSS anterior
            $('#cct-layout-container-css').remove();
            
            // Adiciona novo CSS
            $('head').append('<style id="cct-layout-container-css">' + css + '</style>');
        },

        /**
         * Atualizar configurações de espaçamento
         */
        updateSpacingSettings: function() {
            var sectionSpacing = $('.cct-section-spacing').val();
            var elementSpacing = $('.cct-element-spacing').val();
            
            // Atualizar valores exibidos
            $('.cct-section-spacing').siblings('.cct-value').text(sectionSpacing + 'px');
            $('.cct-element-spacing').siblings('.cct-value').text(elementSpacing + 'px');
            
            // Aplicar CSS dinâmico
            this.applySpacingCSS(sectionSpacing, elementSpacing);
        },

        /**
         * Aplicar CSS de espaçamento
         */
        applySpacingCSS: function(sectionSpacing, elementSpacing) {
            var css = `
                .cct-section {
                    margin-bottom: ${sectionSpacing}px;
                }
                
                .cct-element {
                    margin-bottom: ${elementSpacing}px;
                }
                
                .cct-preview-content > div {
                    margin-bottom: ${elementSpacing}px;
                }
            `;
            
            // Remove CSS anterior
            $('#cct-layout-spacing-css').remove();
            
            // Adiciona novo CSS
            $('head').append('<style id="cct-layout-spacing-css">' + css + '</style>');
        },

        /**
         * Salvar layout atual
         */
        saveLayout: function() {
            var layoutData = {
                type: $('.cct-layout-preset.active').data('layout'),
                grid: {
                    columns: $('.cct-grid-columns').val(),
                    gap: $('.cct-grid-gap').val(),
                    maxWidth: $('.cct-grid-max-width').val()
                },
                container: {
                    type: $('.cct-container-type.active').data('type'),
                    padding: $('.cct-container-padding').val()
                },
                spacing: {
                    section: $('.cct-section-spacing').val(),
                    element: $('.cct-element-spacing').val()
                }
            };
            
            // Trigger evento personalizado
            $(document).trigger('cct:layoutSaved', layoutData);
            
            // Feedback visual
            var button = $('.cct-save-layout');
            var originalText = button.text();
            button.text('Salvo!').prop('disabled', true);
            
            setTimeout(function() {
                button.text(originalText).prop('disabled', false);
            }, 2000);
        },

        /**
         * Vincular eventos
         */
        bindEvents: function() {
            var self = this;
            
            // Trocar preset de layout
            $(document).on('click', '.cct-layout-preset', function() {
                var layout = $(this).data('layout');
                
                $('.cct-layout-preset').removeClass('active');
                $(this).addClass('active');
                
                self.updateLayoutPreview(layout);
            });
            
            // Trocar tipo de container
            $(document).on('click', '.cct-container-type', function() {
                $('.cct-container-type').removeClass('active');
                $(this).addClass('active');
                
                self.updateContainerSettings();
            });
            
            // Atualizar configurações de grid
            $(document).on('input change', '.cct-grid-columns, .cct-grid-gap, .cct-grid-max-width', function() {
                self.updateGridSettings();
            });
            
            // Atualizar configurações de container
            $(document).on('input change', '.cct-container-padding', function() {
                self.updateContainerSettings();
            });
            
            // Atualizar configurações de espaçamento
            $(document).on('input change', '.cct-section-spacing, .cct-element-spacing', function() {
                self.updateSpacingSettings();
            });
            
            // Salvar layout
            $(document).on('click', '.cct-save-layout', function() {
                self.saveLayout();
            });
            
            // Adicionar seção
            $(document).on('click', '.cct-add-section', function() {
                // Implementar funcionalidade de adicionar seção
                alert('Funcionalidade de adicionar seção será implementada em versão futura.');
            });
        }
    };

    /**
     * Inicialização
     */
    $(document).ready(function() {
        // Verificar se estamos no customizer
        if (typeof wp.customize !== 'undefined') {
            CCTLayoutManager.init();
        }
    });

    // Expor objeto globalmente
    window.CCTLayoutManager = CCTLayoutManager;

})(jQuery);
