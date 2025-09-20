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
            if (!$('.cct-breakpoint-manager-control').length) {
                this.createManagerInterface();
            }
        },

        /**
         * Criar interface do gerenciador
         */
        createManagerInterface: function() {
            var managerHTML = `
                <div class="cct-breakpoint-manager-control">
                    <div class="cct-breakpoint-header">
                        <h3>Gerenciador de Breakpoints</h3>
                        <button class="button button-secondary cct-add-breakpoint">Adicionar Breakpoint</button>
                    </div>
                    
                    <div class="cct-breakpoint-list">
                        <!-- Breakpoints serão adicionados aqui -->
                    </div>
                    
                    <div class="cct-breakpoint-presets">
                        <h4>Presets Populares</h4>
                        <div class="cct-preset-buttons">
                            <button class="button cct-preset" data-preset="bootstrap">Bootstrap</button>
                            <button class="button cct-preset" data-preset="tailwind">Tailwind</button>
                            <button class="button cct-preset" data-preset="material">Material Design</button>
                        </div>
                    </div>
                    
                    <div class="cct-breakpoint-preview">
                        <h4>Preview</h4>
                        <div class="cct-preview-container">
                            <div class="cct-preview-device mobile">Mobile</div>
                            <div class="cct-preview-device tablet">Tablet</div>
                            <div class="cct-preview-device desktop">Desktop</div>
                        </div>
                    </div>
                </div>
            `;
            
            // Adicionar à interface do customizer
            $('.customize-control-cct_breakpoint_manager').append(managerHTML);
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
            var container = $('.cct-breakpoint-list');
            container.empty();
            
            breakpoints.forEach(function(breakpoint, index) {
                var breakpointHTML = `
                    <div class="cct-breakpoint-item" data-index="${index}">
                        <div class="cct-breakpoint-info">
                            <input type="text" class="cct-breakpoint-name" value="${breakpoint.name}" placeholder="Nome">
                            <div class="cct-breakpoint-value-group">
                                <input type="number" class="cct-breakpoint-value" value="${breakpoint.value}" min="0" max="9999">
                                <select class="cct-breakpoint-unit">
                                    <option value="px" ${breakpoint.unit === 'px' ? 'selected' : ''}>px</option>
                                    <option value="em" ${breakpoint.unit === 'em' ? 'selected' : ''}>em</option>
                                    <option value="rem" ${breakpoint.unit === 'rem' ? 'selected' : ''}>rem</option>
                                    <option value="%" ${breakpoint.unit === '%' ? 'selected' : ''}>%</option>
                                </select>
                            </div>
                            <select class="cct-breakpoint-type">
                                <option value="max-width" ${breakpoint.type === 'max-width' ? 'selected' : ''}>Max Width</option>
                                <option value="min-width" ${breakpoint.type === 'min-width' ? 'selected' : ''}>Min Width</option>
                            </select>
                        </div>
                        <div class="cct-breakpoint-actions">
                            <button class="button button-small cct-move-up" title="Mover para cima">↑</button>
                            <button class="button button-small cct-move-down" title="Mover para baixo">↓</button>
                            <button class="button button-small cct-duplicate" title="Duplicar">⧉</button>
                            <button class="button button-small cct-delete" title="Excluir">×</button>
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
                <div class="cct-breakpoint-item" data-index="new">
                    <div class="cct-breakpoint-info">
                        <input type="text" class="cct-breakpoint-name" value="${newBreakpoint.name}" placeholder="Nome">
                        <div class="cct-breakpoint-value-group">
                            <input type="number" class="cct-breakpoint-value" value="${newBreakpoint.value}" min="0" max="9999">
                            <select class="cct-breakpoint-unit">
                                <option value="px" selected>px</option>
                                <option value="em">em</option>
                                <option value="rem">rem</option>
                                <option value="%">%</option>
                            </select>
                        </div>
                        <select class="cct-breakpoint-type">
                            <option value="max-width" selected>Max Width</option>
                            <option value="min-width">Min Width</option>
                        </select>
                    </div>
                    <div class="cct-breakpoint-actions">
                        <button class="button button-small cct-move-up" title="Mover para cima">↑</button>
                        <button class="button button-small cct-move-down" title="Mover para baixo">↓</button>
                        <button class="button button-small cct-duplicate" title="Duplicar">⧉</button>
                        <button class="button button-small cct-delete" title="Excluir">×</button>
                    </div>
                </div>
            `;
            
            $('.cct-breakpoint-list').append(breakpointHTML);
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
            var previewContainer = $('.cct-preview-container');
            
            // Atualizar indicadores visuais
            previewContainer.find('.cct-preview-device').each(function(index) {
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
            
            $('.cct-breakpoint-item').each(function() {
                var item = $(this);
                var breakpoint = {
                    name: item.find('.cct-breakpoint-name').val(),
                    value: parseInt(item.find('.cct-breakpoint-value').val()),
                    unit: item.find('.cct-breakpoint-unit').val(),
                    type: item.find('.cct-breakpoint-type').val()
                };
                
                breakpoints.push(breakpoint);
            });
            
            return breakpoints;
        },

        /**
         * Atualizar índices dos breakpoints
         */
        updateIndices: function() {
            $('.cct-breakpoint-item').each(function(index) {
                $(this).attr('data-index', index);
            });
        },

        /**
         * Vincular eventos
         */
        bindEvents: function() {
            var self = this;
            
            // Adicionar breakpoint
            $(document).on('click', '.cct-add-breakpoint', function() {
                self.addBreakpoint();
            });
            
            // Aplicar preset
            $(document).on('click', '.cct-preset', function() {
                var preset = $(this).data('preset');
                self.applyPreset(preset);
            });
            
            // Excluir breakpoint
            $(document).on('click', '.cct-delete', function() {
                if (confirm('Tem certeza que deseja excluir este breakpoint?')) {
                    $(this).closest('.cct-breakpoint-item').remove();
                    self.updateIndices();
                    self.updatePreview();
                }
            });
            
            // Duplicar breakpoint
            $(document).on('click', '.cct-duplicate', function() {
                var item = $(this).closest('.cct-breakpoint-item');
                var clone = item.clone();
                clone.find('.cct-breakpoint-name').val(clone.find('.cct-breakpoint-name').val() + ' (Cópia)');
                item.after(clone);
                self.updateIndices();
            });
            
            // Mover para cima
            $(document).on('click', '.cct-move-up', function() {
                var item = $(this).closest('.cct-breakpoint-item');
                var prev = item.prev('.cct-breakpoint-item');
                if (prev.length) {
                    item.insertBefore(prev);
                    self.updateIndices();
                }
            });
            
            // Mover para baixo
            $(document).on('click', '.cct-move-down', function() {
                var item = $(this).closest('.cct-breakpoint-item');
                var next = item.next('.cct-breakpoint-item');
                if (next.length) {
                    item.insertAfter(next);
                    self.updateIndices();
                }
            });
            
            // Atualizar preview quando valores mudarem
            $(document).on('input change', '.cct-breakpoint-name, .cct-breakpoint-value, .cct-breakpoint-unit, .cct-breakpoint-type', function() {
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