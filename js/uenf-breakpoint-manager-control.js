/**
 * CCT Breakpoint Manager Control JavaScript
 * Controle personalizado para gerenciamento de breakpoints no customizer
 */

(function($) {
    'use strict';

    // Aguardar o carregamento do customizer
    wp.customize = wp.customize || {};

    /**
     * Controle personalizado para breakpoints
     */
    var CCTBreakpointManagerControl = {
        
        /**
         * Inicialização
         */
        init: function() {
            this.setupBreakpointManager();
            this.bindEvents();
            this.loadDefaultBreakpoints();
            console.log('CCT Breakpoint Manager Control: Initialized');
        },

        /**
         * Configuração do gerenciador de breakpoints
         */
        setupBreakpointManager: function() {
            // Criar interface do gerenciador se não existir
            if (!$('.uenf-breakpoint-manager-control').length) {
                this.createManagerInterface();
            }
        },

        /**
         * Criar interface do gerenciador
         */
        createManagerInterface: function() {
            var managerHTML = `
                <div class="uenf-breakpoint-manager-control">
                    <div class="uenf-breakpoint-header">
                        <h3>Gerenciador de Breakpoints</h3>
                        <button class="button button-secondary uenf-add-breakpoint">Adicionar Breakpoint</button>
                    </div>
                    
                    <div class="uenf-breakpoint-list">
                        <!-- Breakpoints serão adicionados aqui -->
                    </div>
                    
                    <div class="uenf-breakpoint-presets">
                        <h4>Presets Populares</h4>
                        <div class="uenf-preset-buttons">
                            <button class="button uenf-preset" data-preset="bootstrap">Bootstrap</button>
                            <button class="button uenf-preset" data-preset="tailwind">Tailwind</button>
                            <button class="button uenf-preset" data-preset="material">Material Design</button>
                        </div>
                    </div>
                    
                    <div class="uenf-breakpoint-preview">
                        <h4>Preview</h4>
                        <div class="uenf-preview-container">
                            <div class="uenf-preview-device mobile">Mobile</div>
                            <div class="uenf-preview-device tablet">Tablet</div>
                            <div class="uenf-preview-device desktop">Desktop</div>
                        </div>
                    </div>
                </div>
            `;
            
            // Adicionar à interface do customizer
            $('.customize-control-uenf_breakpoint_manager').append(managerHTML);
        },

        /**
         * Carregar breakpoints padrão
         */
        loadDefaultBreakpoints: function() {
            var defaultBreakpoints = [
                { name: 'Mobile', value: 768, unit: 'px', type: 'max-width' },
                { name: 'Tablet', value: 1024, unit: 'px', type: 'max-width' },
                { name: 'Desktop', value: 1200, unit: 'px', type: 'min-width' },
                { name: 'Wide', value: 1400, unit: 'px', type: 'min-width' }
            ];
            
            this.renderBreakpoints(defaultBreakpoints);
        },

        /**
         * Renderizar lista de breakpoints
         */
        renderBreakpoints: function(breakpoints) {
            var container = $('.uenf-breakpoint-list');
            container.empty();
            
            breakpoints.forEach(function(breakpoint, index) {
                var breakpointHTML = `
                    <div class="uenf-breakpoint-item" data-index="${index}">
                        <div class="uenf-breakpoint-info">
                            <input type="text" class="uenf-breakpoint-name" value="${breakpoint.name}" placeholder="Nome">
                            <div class="uenf-breakpoint-value-group">
                                <input type="number" class="uenf-breakpoint-value" value="${breakpoint.value}" min="0" max="9999">
                                <select class="uenf-breakpoint-unit">
                                    <option value="px" ${breakpoint.unit === 'px' ? 'selected' : ''}>px</option>
                                    <option value="em" ${breakpoint.unit === 'em' ? 'selected' : ''}>em</option>
                                    <option value="rem" ${breakpoint.unit === 'rem' ? 'selected' : ''}>rem</option>
                                    <option value="%" ${breakpoint.unit === '%' ? 'selected' : ''}>%</option>
                                </select>
                            </div>
                            <select class="uenf-breakpoint-type">
                                <option value="max-width" ${breakpoint.type === 'max-width' ? 'selected' : ''}>Max Width</option>
                                <option value="min-width" ${breakpoint.type === 'min-width' ? 'selected' : ''}>Min Width</option>
                            </select>
                        </div>
                        <div class="uenf-breakpoint-actions">
                            <button class="button button-small uenf-move-up" title="Mover para cima">↑</button>
                            <button class="button button-small uenf-move-down" title="Mover para baixo">↓</button>
                            <button class="button button-small uenf-duplicate" title="Duplicar">⧉</button>
                            <button class="button button-small uenf-delete" title="Excluir">×</button>
                        </div>
                    </div>
                `;
                
                container.append(breakpointHTML);
            });
        },

        /**
         * Adicionar novo breakpoint
         */
        addBreakpoint: function() {
            var newBreakpoint = {
                name: 'Novo Breakpoint',
                value: 768,
                unit: 'px',
                type: 'max-width'
            };
            
            var breakpointHTML = `
                <div class="uenf-breakpoint-item" data-index="new">
                    <div class="uenf-breakpoint-info">
                        <input type="text" class="uenf-breakpoint-name" value="${newBreakpoint.name}" placeholder="Nome">
                        <div class="uenf-breakpoint-value-group">
                            <input type="number" class="uenf-breakpoint-value" value="${newBreakpoint.value}" min="0" max="9999">
                            <select class="uenf-breakpoint-unit">
                                <option value="px" selected>px</option>
                                <option value="em">em</option>
                                <option value="rem">rem</option>
                                <option value="%">%</option>
                            </select>
                        </div>
                        <select class="uenf-breakpoint-type">
                            <option value="max-width" selected>Max Width</option>
                            <option value="min-width">Min Width</option>
                        </select>
                    </div>
                    <div class="uenf-breakpoint-actions">
                        <button class="button button-small uenf-move-up" title="Mover para cima">↑</button>
                        <button class="button button-small uenf-move-down" title="Mover para baixo">↓</button>
                        <button class="button button-small uenf-duplicate" title="Duplicar">⧉</button>
                        <button class="button button-small uenf-delete" title="Excluir">×</button>
                    </div>
                </div>
            `;
            
            $('.uenf-breakpoint-list').append(breakpointHTML);
            this.updateIndices();
        },

        /**
         * Aplicar preset de breakpoints
         */
        applyPreset: function(presetName) {
            var presets = {
                bootstrap: [
                    { name: 'XS', value: 576, unit: 'px', type: 'max-width' },
                    { name: 'SM', value: 768, unit: 'px', type: 'max-width' },
                    { name: 'MD', value: 992, unit: 'px', type: 'max-width' },
                    { name: 'LG', value: 1200, unit: 'px', type: 'max-width' },
                    { name: 'XL', value: 1400, unit: 'px', type: 'max-width' }
                ],
                tailwind: [
                    { name: 'SM', value: 640, unit: 'px', type: 'min-width' },
                    { name: 'MD', value: 768, unit: 'px', type: 'min-width' },
                    { name: 'LG', value: 1024, unit: 'px', type: 'min-width' },
                    { name: 'XL', value: 1280, unit: 'px', type: 'min-width' },
                    { name: '2XL', value: 1536, unit: 'px', type: 'min-width' }
                ],
                material: [
                    { name: 'Mobile', value: 600, unit: 'px', type: 'max-width' },
                    { name: 'Tablet', value: 960, unit: 'px', type: 'max-width' },
                    { name: 'Desktop', value: 1280, unit: 'px', type: 'max-width' },
                    { name: 'Large', value: 1920, unit: 'px', type: 'max-width' }
                ]
            };
            
            if (presets[presetName]) {
                this.renderBreakpoints(presets[presetName]);
                this.updatePreview();
            }
        },

        /**
         * Atualizar preview dos breakpoints
         */
        updatePreview: function() {
            var breakpoints = this.getBreakpoints();
            var previewContainer = $('.uenf-preview-container');
            
            // Atualizar indicadores visuais
            previewContainer.find('.uenf-preview-device').each(function(index) {
                var device = $(this);
                var breakpoint = breakpoints[index];
                
                if (breakpoint) {
                    device.text(`${breakpoint.name}: ${breakpoint.value}${breakpoint.unit}`);
                    device.show();
                } else {
                    device.hide();
                }
            });
        },

        /**
         * Obter lista de breakpoints atual
         */
        getBreakpoints: function() {
            var breakpoints = [];
            
            $('.uenf-breakpoint-item').each(function() {
                var item = $(this);
                var breakpoint = {
                    name: item.find('.uenf-breakpoint-name').val(),
                    value: parseInt(item.find('.uenf-breakpoint-value').val()),
                    unit: item.find('.uenf-breakpoint-unit').val(),
                    type: item.find('.uenf-breakpoint-type').val()
                };
                
                breakpoints.push(breakpoint);
            });
            
            return breakpoints;
        },

        /**
         * Atualizar índices dos breakpoints
         */
        updateIndices: function() {
            $('.uenf-breakpoint-item').each(function(index) {
                $(this).attr('data-index', index);
            });
        },

        /**
         * Vincular eventos
         */
        bindEvents: function() {
            var self = this;
            
            // Adicionar breakpoint
            $(document).on('click', '.uenf-add-breakpoint', function() {
                self.addBreakpoint();
            });
            
            // Aplicar preset
            $(document).on('click', '.uenf-preset', function() {
                var preset = $(this).data('preset');
                self.applyPreset(preset);
            });
            
            // Excluir breakpoint
            $(document).on('click', '.uenf-delete', function() {
                if (confirm('Tem certeza que deseja excluir este breakpoint?')) {
                    $(this).closest('.uenf-breakpoint-item').remove();
                    self.updateIndices();
                    self.updatePreview();
                }
            });
            
            // Duplicar breakpoint
            $(document).on('click', '.uenf-duplicate', function() {
                var item = $(this).closest('.uenf-breakpoint-item');
                var clone = item.clone();
                clone.find('.uenf-breakpoint-name').val(clone.find('.uenf-breakpoint-name').val() + ' (Cópia)');
                item.after(clone);
                self.updateIndices();
            });
            
            // Mover para cima
            $(document).on('click', '.uenf-move-up', function() {
                var item = $(this).closest('.uenf-breakpoint-item');
                var prev = item.prev('.uenf-breakpoint-item');
                if (prev.length) {
                    item.insertBefore(prev);
                    self.updateIndices();
                }
            });
            
            // Mover para baixo
            $(document).on('click', '.uenf-move-down', function() {
                var item = $(this).closest('.uenf-breakpoint-item');
                var next = item.next('.uenf-breakpoint-item');
                if (next.length) {
                    item.insertAfter(next);
                    self.updateIndices();
                }
            });
            
            // Atualizar preview quando valores mudarem
            $(document).on('input change', '.uenf-breakpoint-name, .uenf-breakpoint-value, .uenf-breakpoint-unit, .uenf-breakpoint-type', function() {
                self.updatePreview();
            });
        }
    };

    /**
     * Inicialização
     */
    $(document).ready(function() {
        // Verificar se estamos no customizer
        if (typeof wp.customize !== 'undefined') {
            CCTBreakpointManagerControl.init();
        }
    });

    // Expor objeto globalmente
    window.CCTBreakpointManagerControl = CCTBreakpointManagerControl;

})(jQuery);
