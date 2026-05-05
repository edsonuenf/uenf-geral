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
            if (!$('.uenf-layout-manager-control').length) {
                this.createManagerInterface();
            }
        },

        /**
         * Criar interface do gerenciador
         */
        createManagerInterface: function() {
            var managerHTML = `
                <div class="uenf-layout-manager-control">
                    <div class="uenf-layout-header">
                        <h3>Gerenciador de Layout</h3>
                        <div class="uenf-layout-actions">
                            <button class="button button-secondary uenf-add-section">Adicionar Seção</button>
                            <button class="button button-secondary uenf-save-layout">Salvar Layout</button>
                        </div>
                    </div>
                    
                    <div class="uenf-layout-types">
                        <h4>Tipos de Layout</h4>
                        <div class="uenf-layout-presets">
                            <button class="uenf-layout-preset active" data-layout="single-column">
                                <div class="preset-preview single-column"></div>
                                <span>Coluna Única</span>
                            </button>
                            <button class="uenf-layout-preset" data-layout="two-column-left">
                                <div class="preset-preview two-column-left"></div>
                                <span>Duas Colunas (Sidebar Esquerda)</span>
                            </button>
                            <button class="uenf-layout-preset" data-layout="two-column-right">
                                <div class="preset-preview two-column-right"></div>
                                <span>Duas Colunas (Sidebar Direita)</span>
                            </button>
                            <button class="uenf-layout-preset" data-layout="three-column">
                                <div class="preset-preview three-column"></div>
                                <span>Três Colunas</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="uenf-grid-settings">
                        <h4>Configurações de Grid</h4>
                        <div class="uenf-grid-controls">
                            <div class="uenf-control-group">
                                <label>Colunas:</label>
                                <input type="range" class="uenf-grid-columns" min="1" max="12" value="12">
                                <span class="uenf-value">12</span>
                            </div>
                            <div class="uenf-control-group">
                                <label>Gap:</label>
                                <input type="range" class="uenf-grid-gap" min="0" max="50" value="20">
                                <span class="uenf-value">20px</span>
                            </div>
                            <div class="uenf-control-group">
                                <label>Max Width:</label>
                                <input type="number" class="uenf-grid-max-width" min="800" max="2000" value="1200">
                                <span>px</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="uenf-container-settings">
                        <h4>Configurações de Container</h4>
                        <div class="uenf-container-types">
                            <button class="uenf-container-type active" data-type="boxed">Boxed</button>
                            <button class="uenf-container-type" data-type="fluid">Fluid</button>
                            <button class="uenf-container-type" data-type="full-width">Full Width</button>
                        </div>
                        <div class="uenf-container-controls">
                            <div class="uenf-control-group">
                                <label>Padding:</label>
                                <input type="range" class="uenf-container-padding" min="0" max="50" value="15">
                                <span class="uenf-value">15px</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="uenf-spacing-settings">
                        <h4>Configurações de Espaçamento</h4>
                        <div class="uenf-spacing-controls">
                            <div class="uenf-control-group">
                                <label>Seções:</label>
                                <input type="range" class="uenf-section-spacing" min="0" max="100" value="40">
                                <span class="uenf-value">40px</span>
                            </div>
                            <div class="uenf-control-group">
                                <label>Elementos:</label>
                                <input type="range" class="uenf-element-spacing" min="0" max="50" value="20">
                                <span class="uenf-value">20px</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="uenf-layout-preview">
                        <h4>Preview do Layout</h4>
                        <div class="uenf-preview-container">
                            <div class="uenf-preview-layout single-column">
                                <div class="uenf-preview-header">Header</div>
                                <div class="uenf-preview-content">
                                    <div class="uenf-preview-main">Main Content</div>
                                    <div class="uenf-preview-sidebar" style="display: none;">Sidebar</div>
                                    <div class="uenf-preview-sidebar-secondary" style="display: none;">Sidebar 2</div>
                                </div>
                                <div class="uenf-preview-footer">Footer</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Adicionar à interface do customizer
            $('.customize-control-uenf_layout_manager').append(managerHTML);
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
            var previewContainer = $('.uenf-preview-layout');
            var main = previewContainer.find('.uenf-preview-main');
            var sidebar = previewContainer.find('.uenf-preview-sidebar');
            var sidebarSecondary = previewContainer.find('.uenf-preview-sidebar-secondary');
            
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
            var columns = $('.uenf-grid-columns').val();
            var gap = $('.uenf-grid-gap').val();
            var maxWidth = $('.uenf-grid-max-width').val();
            
            // Atualizar valores exibidos
            $('.uenf-grid-columns').siblings('.uenf-value').text(columns);
            $('.uenf-grid-gap').siblings('.uenf-value').text(gap + 'px');
            
            // Aplicar CSS dinâmico
            this.applyGridCSS(columns, gap, maxWidth);
        },

        /**
         * Aplicar CSS do grid
         */
        applyGridCSS: function(columns, gap, maxWidth) {
            var css = `
                .uenf-grid {
                    display: grid;
                    grid-template-columns: repeat(${columns}, 1fr);
                    gap: ${gap}px;
                    max-width: ${maxWidth}px;
                    margin: 0 auto;
                }
                
                .uenf-container {
                    max-width: ${maxWidth}px;
                    margin: 0 auto;
                }
            `;
            
            // Remove CSS anterior
            $('#uenf-layout-grid-css').remove();
            
            // Adiciona novo CSS
            $('head').append('<style id="uenf-layout-grid-css">' + css + '</style>');
        },

        /**
         * Atualizar configurações de container
         */
        updateContainerSettings: function() {
            var padding = $('.uenf-container-padding').val();
            var type = $('.uenf-container-type.active').data('type');
            
            // Atualizar valor exibido
            $('.uenf-container-padding').siblings('.uenf-value').text(padding + 'px');
            
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
                        .uenf-container {
                            max-width: 1200px;
                            margin: 0 auto;
                            padding: 0 ${padding}px;
                        }
                    `;
                    break;
                    
                case 'fluid':
                    css = `
                        .uenf-container {
                            width: 100%;
                            padding: 0 ${padding}px;
                        }
                    `;
                    break;
                    
                case 'full-width':
                    css = `
                        .uenf-container {
                            width: 100%;
                            padding: 0;
                        }
                    `;
                    break;
            }
            
            // Remove CSS anterior
            $('#uenf-layout-container-css').remove();
            
            // Adiciona novo CSS
            $('head').append('<style id="uenf-layout-container-css">' + css + '</style>');
        },

        /**
         * Atualizar configurações de espaçamento
         */
        updateSpacingSettings: function() {
            var sectionSpacing = $('.uenf-section-spacing').val();
            var elementSpacing = $('.uenf-element-spacing').val();
            
            // Atualizar valores exibidos
            $('.uenf-section-spacing').siblings('.uenf-value').text(sectionSpacing + 'px');
            $('.uenf-element-spacing').siblings('.uenf-value').text(elementSpacing + 'px');
            
            // Aplicar CSS dinâmico
            this.applySpacingCSS(sectionSpacing, elementSpacing);
        },

        /**
         * Aplicar CSS de espaçamento
         */
        applySpacingCSS: function(sectionSpacing, elementSpacing) {
            var css = `
                .uenf-section {
                    margin-bottom: ${sectionSpacing}px;
                }
                
                .uenf-element {
                    margin-bottom: ${elementSpacing}px;
                }
                
                .uenf-preview-content > div {
                    margin-bottom: ${elementSpacing}px;
                }
            `;
            
            // Remove CSS anterior
            $('#uenf-layout-spacing-css').remove();
            
            // Adiciona novo CSS
            $('head').append('<style id="uenf-layout-spacing-css">' + css + '</style>');
        },

        /**
         * Salvar layout atual
         */
        saveLayout: function() {
            var layoutData = {
                type: $('.uenf-layout-preset.active').data('layout'),
                grid: {
                    columns: $('.uenf-grid-columns').val(),
                    gap: $('.uenf-grid-gap').val(),
                    maxWidth: $('.uenf-grid-max-width').val()
                },
                container: {
                    type: $('.uenf-container-type.active').data('type'),
                    padding: $('.uenf-container-padding').val()
                },
                spacing: {
                    section: $('.uenf-section-spacing').val(),
                    element: $('.uenf-element-spacing').val()
                }
            };
            
            // Trigger evento personalizado
            $(document).trigger('cct:layoutSaved', layoutData);
            
            // Feedback visual
            var button = $('.uenf-save-layout');
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
            $(document).on('click', '.uenf-layout-preset', function() {
                var layout = $(this).data('layout');
                
                $('.uenf-layout-preset').removeClass('active');
                $(this).addClass('active');
                
                self.updateLayoutPreview(layout);
            });
            
            // Trocar tipo de container
            $(document).on('click', '.uenf-container-type', function() {
                $('.uenf-container-type').removeClass('active');
                $(this).addClass('active');
                
                self.updateContainerSettings();
            });
            
            // Atualizar configurações de grid
            $(document).on('input change', '.uenf-grid-columns, .uenf-grid-gap, .uenf-grid-max-width', function() {
                self.updateGridSettings();
            });
            
            // Atualizar configurações de container
            $(document).on('input change', '.uenf-container-padding', function() {
                self.updateContainerSettings();
            });
            
            // Atualizar configurações de espaçamento
            $(document).on('input change', '.uenf-section-spacing, .uenf-element-spacing', function() {
                self.updateSpacingSettings();
            });
            
            // Salvar layout
            $(document).on('click', '.uenf-save-layout', function() {
                self.saveLayout();
            });
            
            // Adicionar seção
            $(document).on('click', '.uenf-add-section', function() {
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
