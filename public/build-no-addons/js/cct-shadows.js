/**
 * Sistema de Sombras CCT - JavaScript Frontend
 * 
 * Funcionalidades incluídas:
 * - Preview interativo de elevação
 * - Configurador dinâmico de sombras
 * - Aplicação automática de classes
 * - Animações de hover
 * - Otimizações de performance
 * - Acessibilidade
 * 
 * @package CCT_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Objeto principal do sistema de sombras
     */
    const CCTShadows = {
        
        // Configurações padrão
        settings: {
            shadowsEnabled: true,
            shadowColor: '#000000',
            shadowOpacity: 0.25,
            blurIntensity: 1.0,
            spreadIntensity: 1.0,
            activePreset: 'material',
            animationsEnabled: true,
            animationDuration: 0.3,
            animationEasing: 'cubic-bezier(0.25, 0.8, 0.25, 1)',
            gpuAcceleration: true,
            willChange: true,
            reduceMotionRespect: true,
            mobileOptimization: true
        },
        
        // Estado interno
        state: {
            initialized: false,
            currentLevel: 2,
            isHovering: false,
            shadowLayers: [],
            previewMode: false
        },
        
        // Cache de elementos
        cache: {
            demoElement: null,
            previewBox: null,
            cssOutput: null,
            levelSelect: null
        },
        
        // Níveis de elevação
        elevationLevels: {},
        
        // Presets de sombras
        presets: {},
        
        /**
         * Inicializa o sistema de sombras
         */
        init: function(customSettings = {}) {
            // Merge configurações
            this.settings = { ...this.settings, ...customSettings };
            
            // Verificar se está habilitado
            if (!this.settings.shadowsEnabled) {
                return;
            }
            
            // Inicializar componentes
            this.initElevationPreview();
            this.initShadowConfigurator();
            this.initPresetSelector();
            this.initUseCases();
            
            // Eventos
            this.bindEvents();
            
            // Aplicar configurações iniciais
            this.applyInitialSettings();
            
            // Marcar como inicializado
            this.state.initialized = true;
            
            // Debug
            this.debug('Sistema de sombras inicializado', this.settings);
        },
        
        /**
         * Inicializa preview de elevação
         */
        initElevationPreview: function() {
            // Cache de elementos
            this.cache.demoElement = $('#cct-demo-element');
            this.cache.levelSelect = $('#cct-demo-level-select');
            
            // Configurar demonstração interativa
            this.setupInteractiveDemo();
            
            // Configurar grid de elevação
            this.setupElevationGrid();
        },
        
        /**
         * Configura demonstração interativa
         */
        setupInteractiveDemo: function() {
            // Mudança de nível via select
            this.cache.levelSelect.on('change', (e) => {
                const level = parseInt(e.target.value);
                this.applyElevationLevel(level);
            });
            
            // Toggle de hover effect
            $('#cct-demo-hover').on('change', (e) => {
                const enabled = e.target.checked;
                this.toggleHoverEffect(enabled);
            });
            
            // Clique nos itens do grid
            $('.cct-elevation-demo').on('click', (e) => {
                const level = parseInt($(e.currentTarget).closest('.cct-elevation-item').data('level'));
                this.applyElevationLevel(level);
                this.cache.levelSelect.val(level);
            });
        },
        
        /**
         * Configura grid de elevação
         */
        setupElevationGrid: function() {
            // Botões de aplicar
            $('.cct-apply-elevation').on('click', (e) => {
                const level = parseInt($(e.target).data('level'));
                this.applyElevationToSite(level);
            });
            
            // Botões de copiar CSS
            $('.cct-copy-css').on('click', (e) => {
                const css = $(e.target).data('css');
                this.copyToClipboard(css);
                this.showNotification('CSS copiado para a área de transferência!', 'success');
            });
        },
        
        /**
         * Inicializa configurador de sombras
         */
        initShadowConfigurator: function() {
            // Cache de elementos
            this.cache.previewBox = $('#cct-shadow-preview');
            this.cache.cssOutput = $('#cct-shadow-css');
            
            // Configurar controles
            this.setupShadowControls();
            
            // Configurar múltiplas camadas
            this.setupMultipleLayers();
            
            // Configurar presets rápidos
            this.setupQuickPresets();
            
            // Preview inicial
            this.updateShadowPreview();
        },
        
        /**
         * Configura controles de sombra
         */
        setupShadowControls: function() {
            // Controles de range
            const rangeControls = {
                'cct-shadow-x': 'updateShadowX',
                'cct-shadow-y': 'updateShadowY',
                'cct-shadow-blur': 'updateShadowBlur',
                'cct-shadow-spread': 'updateShadowSpread',
                'cct-shadow-opacity': 'updateShadowOpacity'
            };
            
            Object.keys(rangeControls).forEach(id => {
                $(`#${id}`).on('input', (e) => {
                    const value = parseFloat(e.target.value);
                    this[rangeControls[id]](value);
                    this.updateShadowPreview();
                });
            });
            
            // Controle de cor
            $('#cct-shadow-color').on('input', (e) => {
                this.updateShadowColor(e.target.value);
                this.updateShadowPreview();
            });
            
            // Botão de copiar CSS
            $('.cct-copy-shadow-css').on('click', () => {
                this.copyToClipboard(this.cache.cssOutput.val());
                this.showNotification('CSS copiado!', 'success');
            });
        },
        
        /**
         * Configura múltiplas camadas
         */
        setupMultipleLayers: function() {
            // Adicionar camada
            $('.cct-add-layer').on('click', () => {
                this.addShadowLayer();
            });
            
            // Remover camada
            $(document).on('click', '.cct-remove-layer', (e) => {
                const layer = parseInt($(e.target).closest('.cct-shadow-layer').data('layer'));
                this.removeShadowLayer(layer);
            });
            
            // Reset sombras
            $('.cct-reset-shadows').on('click', () => {
                this.resetShadowLayers();
            });
        },
        
        /**
         * Configura presets rápidos
         */
        setupQuickPresets: function() {
            $('.cct-preset-btn').on('click', (e) => {
                const preset = $(e.currentTarget).data('preset');
                this.applyQuickPreset(preset);
            });
        },
        
        /**
         * Inicializa seletor de presets
         */
        initPresetSelector: function() {
            // Aplicar preset
            $('.cct-apply-preset').on('click', (e) => {
                const preset = $(e.target).data('preset');
                this.applyPreset(preset);
            });
            
            // Preview preset
            $('.cct-preview-preset').on('click', (e) => {
                const preset = $(e.target).data('preset');
                this.previewPreset(preset);
            });
            
            // Configurações do preset ativo
            this.setupPresetConfig();
        },
        
        /**
         * Configura configurações de preset
         */
        setupPresetConfig: function() {
            // Intensidade
            $('.cct-preset-intensity').on('input', (e) => {
                const intensity = parseFloat(e.target.value);
                $('.cct-intensity-value').text(Math.round(intensity * 100) + '%');
                this.updatePresetIntensity(intensity);
            });
            
            // Cor personalizada
            $('.cct-preset-color').on('input', (e) => {
                this.updatePresetColor(e.target.value);
            });
            
            // Reset cor
            $('.cct-reset-color').on('click', () => {
                this.resetPresetColor();
            });
            
            // Toggle customização
            $('.cct-enable-custom').on('change', (e) => {
                this.toggleCustomization(e.target.checked);
            });
        },
        
        /**
         * Inicializa casos de uso
         */
        initUseCases: function() {
            // Aplicar classes de elevação aos exemplos
            this.applyUseCaseStyles();
        },
        
        /**
         * Aplica estilos aos casos de uso
         */
        applyUseCaseStyles: function() {
            // Aplicar estilos baseados nas configurações atuais
            const customCSS = this.generateCustomCSS();
            
            if (!$('#cct-use-case-styles').length) {
                $('head').append(`<style id="cct-use-case-styles">${customCSS}</style>`);
            } else {
                $('#cct-use-case-styles').text(customCSS);
            }
        },
        
        /**
         * Vincula eventos
         */
        bindEvents: function() {
            // Eventos de redimensionamento
            $(window).on('resize', this.debounce(() => {
                this.handleResize();
            }, 250));
            
            // Eventos de visibilidade
            $(document).on('visibilitychange', () => {
                this.handleVisibilityChange();
            });
            
            // Eventos de movimento reduzido
            if (this.settings.reduceMotionRespect) {
                this.handleReducedMotion();
            }
        },
        
        /**
         * Aplica configurações iniciais
         */
        applyInitialSettings: function() {
            // Aplicar nível inicial
            this.applyElevationLevel(this.state.currentLevel);
            
            // Aplicar preset ativo
            this.applyPreset(this.settings.activePreset);
            
            // Configurar animações
            this.setupAnimations();
        },
        
        /**
         * Configura animações
         */
        setupAnimations: function() {
            if (!this.settings.animationsEnabled) {
                return;
            }
            
            // Aplicar configurações de animação
            const animationCSS = `
                .cct-elevation-0, .cct-elevation-1, .cct-elevation-2, .cct-elevation-4,
                .cct-elevation-6, .cct-elevation-8, .cct-elevation-12, .cct-elevation-16, .cct-elevation-24 {
                    transition: box-shadow ${this.settings.animationDuration}s ${this.settings.animationEasing};
                }
            `;
            
            if (!$('#cct-shadow-animations').length) {
                $('head').append(`<style id="cct-shadow-animations">${animationCSS}</style>`);
            }
        },
        
        /**
         * Aplica nível de elevação
         */
        applyElevationLevel: function(level) {
            if (!this.cache.demoElement.length) {
                return;
            }
            
            // Remover classes anteriores
            this.cache.demoElement.removeClass((index, className) => {
                return (className.match(/(^|\s)cct-elevation-\S+/g) || []).join(' ');
            });
            
            // Adicionar nova classe
            this.cache.demoElement.addClass(`cct-elevation-${level}`);
            
            // Atualizar estado
            this.state.currentLevel = level;
            
            // Debug
            this.debug(`Nível de elevação aplicado: ${level}`);
        },
        
        /**
         * Toggle efeito de hover
         */
        toggleHoverEffect: function(enabled) {
            if (!this.cache.demoElement.length) {
                return;
            }
            
            const level = this.state.currentLevel;
            
            if (enabled) {
                this.cache.demoElement.addClass(`cct-elevation-hover-${level}`);
            } else {
                this.cache.demoElement.removeClass(`cct-elevation-hover-${level}`);
            }
        },
        
        /**
         * Aplica elevação ao site
         */
        applyElevationToSite: function(level) {
            // Implementar aplicação ao site
            // Pode ser via AJAX para salvar configurações
            
            this.showNotification(`Elevação ${level} aplicada ao site!`, 'success');
        },
        
        /**
         * Atualiza valores de sombra
         */
        updateShadowX: function(value) {
            $('#cct-shadow-x-value').text(value + 'px');
            this.state.shadowX = value;
        },
        
        updateShadowY: function(value) {
            $('#cct-shadow-y-value').text(value + 'px');
            this.state.shadowY = value;
        },
        
        updateShadowBlur: function(value) {
            $('#cct-shadow-blur-value').text(value + 'px');
            this.state.shadowBlur = value;
        },
        
        updateShadowSpread: function(value) {
            $('#cct-shadow-spread-value').text(value + 'px');
            this.state.shadowSpread = value;
        },
        
        updateShadowOpacity: function(value) {
            $('#cct-shadow-opacity-value').text(Math.round(value * 100) + '%');
            this.state.shadowOpacity = value;
        },
        
        updateShadowColor: function(color) {
            this.state.shadowColor = color;
        },
        
        /**
         * Atualiza preview de sombra
         */
        updateShadowPreview: function() {
            const css = this.generateShadowCSS();
            
            // Atualizar preview visual
            if (this.cache.previewBox.length) {
                this.cache.previewBox.css('box-shadow', css);
            }
            
            // Atualizar código CSS
            if (this.cache.cssOutput.length) {
                this.cache.cssOutput.val(`box-shadow: ${css};`);
            }
        },
        
        /**
         * Gera CSS da sombra
         */
        generateShadowCSS: function() {
            const x = this.state.shadowX || 0;
            const y = this.state.shadowY || 4;
            const blur = this.state.shadowBlur || 8;
            const spread = this.state.shadowSpread || 0;
            const color = this.state.shadowColor || '#000000';
            const opacity = this.state.shadowOpacity || 0.25;
            
            // Converter hex para rgba
            const rgba = this.hexToRgba(color, opacity);
            
            return `${x}px ${y}px ${blur}px ${spread}px ${rgba}`;
        },
        
        /**
         * Adiciona camada de sombra
         */
        addShadowLayer: function() {
            const layerCount = this.state.shadowLayers.length;
            const newLayer = {
                id: layerCount,
                x: 0,
                y: 4,
                blur: 8,
                spread: 0,
                color: '#000000',
                opacity: 0.25
            };
            
            this.state.shadowLayers.push(newLayer);
            
            // Adicionar à UI
            const $layerEl = $(`
                <div class="cct-shadow-layer" data-layer="${newLayer.id}">
                    <span class="cct-layer-label">Camada ${layerCount + 1}</span>
                    <button type="button" class="cct-remove-layer">×</button>
                </div>
            `);
            
            $('#cct-shadow-layers').append($layerEl);
            
            // Atualizar preview
            this.updateMultiLayerPreview();
        },
        
        /**
         * Remove camada de sombra
         */
        removeShadowLayer: function(layerId) {
            this.state.shadowLayers = this.state.shadowLayers.filter(layer => layer.id !== layerId);
            $(`.cct-shadow-layer[data-layer="${layerId}"]`).remove();
            this.updateMultiLayerPreview();
        },
        
        /**
         * Reset camadas de sombra
         */
        resetShadowLayers: function() {
            this.state.shadowLayers = [];
            $('#cct-shadow-layers').empty();
            
            // Adicionar camada padrão
            this.addShadowLayer();
        },
        
        /**
         * Atualiza preview de múltiplas camadas
         */
        updateMultiLayerPreview: function() {
            const shadows = this.state.shadowLayers.map(layer => {
                const rgba = this.hexToRgba(layer.color, layer.opacity);
                return `${layer.x}px ${layer.y}px ${layer.blur}px ${layer.spread}px ${rgba}`;
            }).join(', ');
            
            if (this.cache.previewBox.length) {
                this.cache.previewBox.css('box-shadow', shadows);
            }
            
            if (this.cache.cssOutput.length) {
                this.cache.cssOutput.val(`box-shadow: ${shadows};`);
            }
        },
        
        /**
         * Aplica preset rápido
         */
        applyQuickPreset: function(preset) {
            const presets = {
                subtle: { x: 0, y: 1, blur: 3, spread: 0, opacity: 0.12 },
                soft: { x: 0, y: 4, blur: 6, spread: 0, opacity: 0.1 },
                medium: { x: 0, y: 10, blur: 15, spread: 0, opacity: 0.1 },
                large: { x: 0, y: 20, blur: 25, spread: 0, opacity: 0.15 },
                colored: { x: 0, y: 8, blur: 16, spread: 0, opacity: 0.3, color: '#3b82f6' },
                inset: { x: 0, y: 2, blur: 4, spread: 0, opacity: 0.1, inset: true }
            };
            
            if (presets[preset]) {
                const config = presets[preset];
                
                // Atualizar controles
                $('#cct-shadow-x').val(config.x).trigger('input');
                $('#cct-shadow-y').val(config.y).trigger('input');
                $('#cct-shadow-blur').val(config.blur).trigger('input');
                $('#cct-shadow-spread').val(config.spread).trigger('input');
                $('#cct-shadow-opacity').val(config.opacity).trigger('input');
                
                if (config.color) {
                    $('#cct-shadow-color').val(config.color).trigger('input');
                }
                
                this.showNotification(`Preset "${preset}" aplicado!`, 'success');
            }
        },
        
        /**
         * Aplica preset
         */
        applyPreset: function(presetName) {
            if (this.presets[presetName]) {
                const preset = this.presets[presetName];
                
                // Aplicar configurações do preset
                this.settings.activePreset = presetName;
                this.settings.shadowColor = preset.color;
                this.settings.shadowOpacity = preset.opacity;
                
                // Atualizar UI
                $('.cct-preset-option').removeClass('active');
                $(`.cct-preset-option[data-preset="${presetName}"]`).addClass('active');
                
                // Aplicar estilos
                this.applyPresetStyles(preset);
                
                this.debug(`Preset aplicado: ${presetName}`, preset);
            }
        },
        
        /**
         * Preview preset
         */
        previewPreset: function(presetName) {
            this.state.previewMode = true;
            this.applyPreset(presetName);
            
            // Reverter após 3 segundos
            setTimeout(() => {
                if (this.state.previewMode) {
                    this.applyPreset(this.settings.activePreset);
                    this.state.previewMode = false;
                }
            }, 3000);
        },
        
        /**
         * Aplica estilos do preset
         */
        applyPresetStyles: function(preset) {
            // Implementar aplicação de estilos específicos do preset
            const customCSS = this.generatePresetCSS(preset);
            
            if (!$('#cct-preset-styles').length) {
                $('head').append(`<style id="cct-preset-styles">${customCSS}</style>`);
            } else {
                $('#cct-preset-styles').text(customCSS);
            }
        },
        
        /**
         * Gera CSS do preset
         */
        generatePresetCSS: function(preset) {
            // Implementar geração de CSS baseado no preset
            return `
                :root {
                    --cct-shadow-color: ${preset.color};
                    --cct-shadow-opacity: ${preset.opacity};
                }
            `;
        },
        
        /**
         * Atualiza intensidade do preset
         */
        updatePresetIntensity: function(intensity) {
            // Implementar atualização de intensidade
            this.debug(`Intensidade atualizada: ${intensity}`);
        },
        
        /**
         * Atualiza cor do preset
         */
        updatePresetColor: function(color) {
            // Implementar atualização de cor
            this.debug(`Cor atualizada: ${color}`);
        },
        
        /**
         * Reset cor do preset
         */
        resetPresetColor: function() {
            const activePreset = this.presets[this.settings.activePreset];
            if (activePreset) {
                $('.cct-preset-color').val(activePreset.color);
                this.updatePresetColor(activePreset.color);
            }
        },
        
        /**
         * Toggle customização
         */
        toggleCustomization: function(enabled) {
            $('.cct-preset-intensity, .cct-preset-color').prop('disabled', !enabled);
        },
        
        /**
         * Gera CSS customizado
         */
        generateCustomCSS: function() {
            // Implementar geração de CSS customizado
            return '';
        },
        
        /**
         * Manipula redimensionamento
         */
        handleResize: function() {
            // Ajustar layout responsivo se necessário
            if (this.settings.mobileOptimization && window.innerWidth <= 768) {
                this.applyMobileOptimizations();
            }
        },
        
        /**
         * Aplica otimizações mobile
         */
        applyMobileOptimizations: function() {
            // Reduzir elevações altas em mobile
            const mobileCSS = `
                @media (max-width: 768px) {
                    .cct-elevation-12, .cct-elevation-16, .cct-elevation-24 {
                        box-shadow: var(--cct-elevation-8) !important;
                    }
                }
            `;
            
            if (!$('#cct-mobile-optimizations').length) {
                $('head').append(`<style id="cct-mobile-optimizations">${mobileCSS}</style>`);
            }
        },
        
        /**
         * Manipula mudança de visibilidade
         */
        handleVisibilityChange: function() {
            if (document.hidden && this.state.previewMode) {
                this.state.previewMode = false;
            }
        },
        
        /**
         * Manipula movimento reduzido
         */
        handleReducedMotion: function() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.settings.animationsEnabled = false;
                
                // Remover animações
                const noAnimationCSS = `
                    @media (prefers-reduced-motion: reduce) {
                        .cct-elevation-0, .cct-elevation-1, .cct-elevation-2, .cct-elevation-4,
                        .cct-elevation-6, .cct-elevation-8, .cct-elevation-12, .cct-elevation-16, .cct-elevation-24 {
                            transition: none !important;
                        }
                    }
                `;
                
                if (!$('#cct-reduced-motion').length) {
                    $('head').append(`<style id="cct-reduced-motion">${noAnimationCSS}</style>`);
                }
            }
        },
        
        /**
         * Converte hex para rgba
         */
        hexToRgba: function(hex, alpha = 1) {
            const r = parseInt(hex.slice(1, 3), 16);
            const g = parseInt(hex.slice(3, 5), 16);
            const b = parseInt(hex.slice(5, 7), 16);
            return `rgba(${r}, ${g}, ${b}, ${alpha})`;
        },
        
        /**
         * Copia para clipboard
         */
        copyToClipboard: function(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text);
            } else {
                // Fallback
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }
        },
        
        /**
         * Mostra notificação
         */
        showNotification: function(message, type = 'info') {
            // Criar elemento de notificação
            const $notification = $(`
                <div class="cct-shadow-notification cct-notification-${type}">
                    ${message}
                </div>
            `);
            
            // Adicionar ao DOM
            $('body').append($notification);
            
            // Animar entrada
            setTimeout(() => {
                $notification.addClass('show');
            }, 100);
            
            // Remover após delay
            setTimeout(() => {
                $notification.removeClass('show');
                setTimeout(() => {
                    $notification.remove();
                }, 300);
            }, 3000);
        },
        
        /**
         * Utilitário debounce
         */
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        /**
         * Debug helper
         */
        debug: function(message, data = null) {
            if (window.cctShadowsDebug) {
                console.log('[CCT Shadows]', message, data);
            }
        },
        
        /**
         * Destroy - limpa recursos
         */
        destroy: function() {
            // Limpar cache
            this.cache = {
                demoElement: null,
                previewBox: null,
                cssOutput: null,
                levelSelect: null
            };
            
            // Resetar estado
            this.state.initialized = false;
            
            // Remover estilos customizados
            $('#cct-shadow-animations, #cct-preset-styles, #cct-use-case-styles, #cct-mobile-optimizations, #cct-reduced-motion').remove();
            
            this.debug('Sistema de sombras destruído');
        }
    };
    
    // Expor globalmente
    window.CCTShadows = CCTShadows;
    
    // Auto-inicializar se configurações estão disponíveis
    $(document).ready(function() {
        if (typeof cctShadows !== 'undefined') {
            CCTShadows.elevationLevels = cctShadows.elevationLevels || {};
            CCTShadows.presets = cctShadows.presets || {};
            CCTShadows.init(cctShadows.settings || {});
        }
    });
    
})(jQuery);

/**
 * CSS para notificações de sombras
 * Injetado via JavaScript
 */
(function() {
    const shadowNotificationCSS = `
        .cct-shadow-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
            word-wrap: break-word;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .cct-shadow-notification.show {
            transform: translateX(0);
        }
        
        .cct-notification-success {
            background: #28a745;
        }
        
        .cct-notification-info {
            background: #17a2b8;
        }
        
        .cct-notification-warning {
            background: #ffc107;
            color: #333;
        }
        
        .cct-notification-error {
            background: #dc3545;
        }
    `;
    
    // Injetar CSS
    const style = document.createElement('style');
    style.textContent = shadowNotificationCSS;
    document.head.appendChild(style);
})();