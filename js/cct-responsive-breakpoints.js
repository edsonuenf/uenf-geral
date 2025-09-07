/**
 * Sistema de Responsive Breakpoints CCT - JavaScript Frontend
 * 
 * Funcionalidades incluídas:
 * - Detecção automática de breakpoints
 * - Gerenciamento dinâmico de layout
 * - Preview multi-dispositivo
 * - Configurações por breakpoint
 * - Integração com todos os módulos
 * 
 * @package CCT_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Objeto principal do sistema de breakpoints
     */
    const CCTBreakpoints = {
        
        // Configurações padrão
        settings: {
            enabled: true,
            template: 'bootstrap',
            detectionEnabled: true,
            autoAdjustLayout: true,
            containerBehavior: 'fluid',
            gridSystem: 'flexbox',
            debugMode: false
        },
        
        // Estado interno
        state: {
            initialized: false,
            currentBreakpoint: null,
            previousBreakpoint: null,
            screenWidth: 0,
            screenHeight: 0,
            deviceType: null,
            orientation: null,
            isTouch: false,
            isRetina: false
        },
        
        // Cache de elementos
        cache: {
            window: null,
            body: null,
            containers: null,
            debugInfo: null
        },
        
        // Breakpoints ativos
        breakpoints: {},
        
        // Callbacks registrados
        callbacks: {
            breakpointChange: [],
            resize: [],
            orientationChange: []
        },
        
        // Timers
        timers: {
            resize: null,
            detection: null
        },
        
        /**
         * Inicializa o sistema de breakpoints
         */
        init: function(customSettings = {}, customBreakpoints = {}) {
            // Merge configurações
            this.settings = { ...this.settings, ...customSettings };
            this.breakpoints = { ...this.breakpoints, ...customBreakpoints };
            
            // Verificar se está habilitado
            if (!this.settings.enabled) {
                return;
            }
            
            // Cache de elementos
            this.cacheElements();
            
            // Detectar estado inicial
            this.detectInitialState();
            
            // Determinar breakpoint inicial
            this.determineCurrentBreakpoint();
            
            // Aplicar configurações iniciais
            this.applyBreakpointSettings();
            
            // Inicializar componentes
            this.initDeviceDetection();
            this.initLayoutAdjustment();
            this.initDebugMode();
            
            // Eventos
            this.bindEvents();
            
            // Marcar como inicializado
            this.state.initialized = true;
            
            // Debug
            this.debug('Sistema de breakpoints inicializado', {
                currentBreakpoint: this.state.currentBreakpoint,
                screenWidth: this.state.screenWidth,
                deviceType: this.state.deviceType,
                settings: this.settings
            });
        },
        
        /**
         * Cache de elementos DOM
         */
        cacheElements: function() {
            this.cache.window = $(window);
            this.cache.body = $('body');
            this.cache.containers = $('.cct-container, .container, .container-fluid');
        },
        
        /**
         * Detecta estado inicial
         */
        detectInitialState: function() {
            // Dimensões da tela
            this.state.screenWidth = this.cache.window.width();
            this.state.screenHeight = this.cache.window.height();
            
            // Orientação
            this.state.orientation = this.state.screenWidth > this.state.screenHeight ? 'landscape' : 'portrait';
            
            // Touch support
            this.state.isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
            
            // Retina display
            this.state.isRetina = window.devicePixelRatio > 1;
            
            // Tipo de dispositivo
            this.detectDeviceType();
        },
        
        /**
         * Detecta tipo de dispositivo
         */
        detectDeviceType: function() {
            const width = this.state.screenWidth;
            
            if (width < 768) {
                this.state.deviceType = 'mobile';
            } else if (width < 1024) {
                this.state.deviceType = 'tablet';
            } else {
                this.state.deviceType = 'desktop';
            }
            
            // Detecção mais específica baseada em user agent
            const userAgent = navigator.userAgent.toLowerCase();
            
            if (/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i.test(userAgent)) {
                this.state.deviceType = 'mobile';
            } else if (/tablet|ipad|playbook|silk/i.test(userAgent)) {
                this.state.deviceType = 'tablet';
            }
        },
        
        /**
         * Determina breakpoint atual
         */
        determineCurrentBreakpoint: function() {
            const width = this.state.screenWidth;
            let currentBp = null;
            
            // Ordenar breakpoints por min_width
            const sortedBreakpoints = Object.entries(this.breakpoints)
                .filter(([key, bp]) => bp.enabled)
                .sort(([, a], [, b]) => a.min_width - b.min_width);
            
            // Encontrar breakpoint ativo
            for (const [key, breakpoint] of sortedBreakpoints) {
                const minWidth = breakpoint.min_width;
                const maxWidth = breakpoint.max_width;
                
                if (width >= minWidth && (maxWidth === null || width <= maxWidth)) {
                    currentBp = key;
                    break;
                }
            }
            
            // Fallback para o menor breakpoint se nenhum for encontrado
            if (!currentBp && sortedBreakpoints.length > 0) {
                currentBp = sortedBreakpoints[0][0];
            }
            
            // Atualizar estado
            this.state.previousBreakpoint = this.state.currentBreakpoint;
            this.state.currentBreakpoint = currentBp;
            
            // Disparar callback se mudou
            if (this.state.previousBreakpoint !== this.state.currentBreakpoint) {
                this.triggerBreakpointChange();
            }
        },
        
        /**
         * Aplica configurações do breakpoint
         */
        applyBreakpointSettings: function() {
            if (!this.state.currentBreakpoint) {
                return;
            }
            
            const breakpoint = this.breakpoints[this.state.currentBreakpoint];
            
            if (!breakpoint) {
                return;
            }
            
            // Aplicar classes CSS
            this.updateBodyClasses();
            
            // Aplicar configurações de container
            this.updateContainerSettings(breakpoint);
            
            // Aplicar configurações de grid
            this.updateGridSettings(breakpoint);
            
            // Atualizar variáveis CSS
            this.updateCSSVariables(breakpoint);
        },
        
        /**
         * Atualiza classes do body
         */
        updateBodyClasses: function() {
            // Remover classes antigas
            this.cache.body.removeClass((index, className) => {
                return (className.match(/\bcct-bp-\S+/g) || []).join(' ');
            });
            
            // Adicionar novas classes
            if (this.state.currentBreakpoint) {
                this.cache.body.addClass(`cct-bp-${this.state.currentBreakpoint}`);
            }
            
            this.cache.body.addClass(`cct-device-${this.state.deviceType}`);
            this.cache.body.addClass(`cct-orientation-${this.state.orientation}`);
            
            if (this.state.isTouch) {
                this.cache.body.addClass('cct-touch');
            }
            
            if (this.state.isRetina) {
                this.cache.body.addClass('cct-retina');
            }
        },
        
        /**
         * Atualiza configurações de container
         */
        updateContainerSettings: function(breakpoint) {
            if (!this.settings.autoAdjustLayout) {
                return;
            }
            
            this.cache.containers.each(function() {
                const $container = $(this);
                
                // Aplicar largura do container
                if (breakpoint.container_width) {
                    $container.css('max-width', breakpoint.container_width);
                }
                
                // Aplicar padding baseado no gutter
                if (breakpoint.gutter) {
                    const padding = breakpoint.gutter / 2;
                    $container.css({
                        'padding-left': `${padding}px`,
                        'padding-right': `${padding}px`
                    });
                }
            });
        },
        
        /**
         * Atualiza configurações de grid
         */
        updateGridSettings: function(breakpoint) {
            // Atualizar variáveis CSS do grid
            const root = document.documentElement;
            
            if (breakpoint.columns) {
                root.style.setProperty('--cct-grid-columns', breakpoint.columns);
            }
            
            if (breakpoint.gutter) {
                root.style.setProperty('--cct-grid-gutter', `${breakpoint.gutter}px`);
            }
        },
        
        /**
         * Atualiza variáveis CSS
         */
        updateCSSVariables: function(breakpoint) {
            const root = document.documentElement;
            const bpKey = this.state.currentBreakpoint;
            
            // Variáveis do breakpoint atual
            root.style.setProperty('--cct-current-bp', `'${bpKey}'`);
            root.style.setProperty('--cct-current-min-width', `${breakpoint.min_width}px`);
            
            if (breakpoint.max_width) {
                root.style.setProperty('--cct-current-max-width', `${breakpoint.max_width}px`);
            }
            
            root.style.setProperty('--cct-current-container', breakpoint.container_width || '100%');
            root.style.setProperty('--cct-current-gutter', `${breakpoint.gutter || 16}px`);
            root.style.setProperty('--cct-current-columns', breakpoint.columns || 12);
            
            // Variáveis de dispositivo
            root.style.setProperty('--cct-screen-width', `${this.state.screenWidth}px`);
            root.style.setProperty('--cct-screen-height', `${this.state.screenHeight}px`);
            root.style.setProperty('--cct-device-type', `'${this.state.deviceType}'`);
            root.style.setProperty('--cct-orientation', `'${this.state.orientation}'`);
        },
        
        /**
         * Inicializa detecção de dispositivo
         */
        initDeviceDetection: function() {
            if (!this.settings.detectionEnabled) {
                return;
            }
            
            // Verificar periodicamente mudanças de orientação
            this.timers.detection = setInterval(() => {
                this.checkOrientationChange();
            }, 1000);
        },
        
        /**
         * Verifica mudança de orientação
         */
        checkOrientationChange: function() {
            const newOrientation = this.state.screenWidth > this.state.screenHeight ? 'landscape' : 'portrait';
            
            if (newOrientation !== this.state.orientation) {
                this.state.orientation = newOrientation;
                this.updateBodyClasses();
                this.updateCSSVariables(this.breakpoints[this.state.currentBreakpoint]);
                this.triggerOrientationChange();
            }
        },
        
        /**
         * Inicializa ajuste automático de layout
         */
        initLayoutAdjustment: function() {
            if (!this.settings.autoAdjustLayout) {
                return;
            }
            
            // Aplicar classes de grid system
            this.cache.body.addClass(`cct-grid-${this.settings.gridSystem}`);
            this.cache.body.addClass(`cct-container-${this.settings.containerBehavior}`);
        },
        
        /**
         * Inicializa modo debug
         */
        initDebugMode: function() {
            if (!this.settings.debugMode) {
                return;
            }
            
            this.createDebugInfo();
            this.updateDebugInfo();
        },
        
        /**
         * Cria elemento de debug
         */
        createDebugInfo: function() {
            if ($('.cct-breakpoint-debug').length) {
                return;
            }
            
            const $debug = $(`
                <div class="cct-breakpoint-debug">
                    <div class="cct-debug-item">
                        <strong>Breakpoint:</strong>
                        <span class="cct-debug-bp">-</span>
                    </div>
                    <div class="cct-debug-item">
                        <strong>Tela:</strong>
                        <span class="cct-debug-screen">-</span>
                    </div>
                    <div class="cct-debug-item">
                        <strong>Dispositivo:</strong>
                        <span class="cct-debug-device">-</span>
                    </div>
                </div>
            `);
            
            $('body').append($debug);
            this.cache.debugInfo = $debug;
        },
        
        /**
         * Atualiza informações de debug
         */
        updateDebugInfo: function() {
            if (!this.cache.debugInfo) {
                return;
            }
            
            const breakpoint = this.breakpoints[this.state.currentBreakpoint];
            
            this.cache.debugInfo.find('.cct-debug-bp').text(
                this.state.currentBreakpoint ? 
                `${this.state.currentBreakpoint} (${breakpoint?.name || 'N/A'})` : 
                'N/A'
            );
            
            this.cache.debugInfo.find('.cct-debug-screen').text(
                `${this.state.screenWidth}x${this.state.screenHeight} (${this.state.orientation})`
            );
            
            this.cache.debugInfo.find('.cct-debug-device').text(
                `${this.state.deviceType}${this.state.isTouch ? ' + Touch' : ''}${this.state.isRetina ? ' + Retina' : ''}`
            );
        },
        
        /**
         * Vincula eventos
         */
        bindEvents: function() {
            // Evento de redimensionamento
            this.cache.window.on('resize', this.debounce(() => {
                this.handleResize();
            }, 250));
            
            // Evento de orientação
            this.cache.window.on('orientationchange', () => {
                setTimeout(() => {
                    this.handleResize();
                }, 100);
            });
            
            // Evento de carregamento
            this.cache.window.on('load', () => {
                this.handleResize();
            });
        },
        
        /**
         * Manipula redimensionamento
         */
        handleResize: function() {
            // Atualizar dimensões
            this.state.screenWidth = this.cache.window.width();
            this.state.screenHeight = this.cache.window.height();
            
            // Detectar tipo de dispositivo
            this.detectDeviceType();
            
            // Determinar novo breakpoint
            this.determineCurrentBreakpoint();
            
            // Aplicar configurações
            this.applyBreakpointSettings();
            
            // Atualizar debug
            if (this.settings.debugMode) {
                this.updateDebugInfo();
            }
            
            // Disparar callbacks
            this.triggerResize();
        },
        
        /**
         * Dispara evento de mudança de breakpoint
         */
        triggerBreakpointChange: function() {
            const eventData = {
                previous: this.state.previousBreakpoint,
                current: this.state.currentBreakpoint,
                breakpoint: this.breakpoints[this.state.currentBreakpoint],
                screenWidth: this.state.screenWidth,
                deviceType: this.state.deviceType
            };
            
            // Evento jQuery
            $(document).trigger('cct:breakpointChanged', eventData);
            
            // Callbacks registrados
            this.callbacks.breakpointChange.forEach(callback => {
                if (typeof callback === 'function') {
                    callback(eventData);
                }
            });
            
            this.debug('Breakpoint alterado:', eventData);
        },
        
        /**
         * Dispara evento de redimensionamento
         */
        triggerResize: function() {
            const eventData = {
                screenWidth: this.state.screenWidth,
                screenHeight: this.state.screenHeight,
                breakpoint: this.state.currentBreakpoint,
                deviceType: this.state.deviceType,
                orientation: this.state.orientation
            };
            
            // Evento jQuery
            $(document).trigger('cct:breakpointResize', eventData);
            
            // Callbacks registrados
            this.callbacks.resize.forEach(callback => {
                if (typeof callback === 'function') {
                    callback(eventData);
                }
            });
        },
        
        /**
         * Dispara evento de mudança de orientação
         */
        triggerOrientationChange: function() {
            const eventData = {
                orientation: this.state.orientation,
                screenWidth: this.state.screenWidth,
                screenHeight: this.state.screenHeight,
                breakpoint: this.state.currentBreakpoint
            };
            
            // Evento jQuery
            $(document).trigger('cct:orientationChanged', eventData);
            
            // Callbacks registrados
            this.callbacks.orientationChange.forEach(callback => {
                if (typeof callback === 'function') {
                    callback(eventData);
                }
            });
            
            this.debug('Orientação alterada:', eventData);
        },
        
        /**
         * API Pública
         */
        
        /**
         * Obtém breakpoint atual
         */
        getCurrentBreakpoint: function() {
            return this.state.currentBreakpoint;
        },
        
        /**
         * Obtém dados do breakpoint atual
         */
        getCurrentBreakpointData: function() {
            return this.breakpoints[this.state.currentBreakpoint] || null;
        },
        
        /**
         * Verifica se está em um breakpoint específico
         */
        isBreakpoint: function(breakpointKey) {
            return this.state.currentBreakpoint === breakpointKey;
        },
        
        /**
         * Verifica se está em mobile
         */
        isMobile: function() {
            return this.state.deviceType === 'mobile';
        },
        
        /**
         * Verifica se está em tablet
         */
        isTablet: function() {
            return this.state.deviceType === 'tablet';
        },
        
        /**
         * Verifica se está em desktop
         */
        isDesktop: function() {
            return this.state.deviceType === 'desktop';
        },
        
        /**
         * Verifica se tem suporte a touch
         */
        isTouch: function() {
            return this.state.isTouch;
        },
        
        /**
         * Verifica se é tela retina
         */
        isRetina: function() {
            return this.state.isRetina;
        },
        
        /**
         * Obtém largura da tela
         */
        getScreenWidth: function() {
            return this.state.screenWidth;
        },
        
        /**
         * Obtém altura da tela
         */
        getScreenHeight: function() {
            return this.state.screenHeight;
        },
        
        /**
         * Obtém orientação
         */
        getOrientation: function() {
            return this.state.orientation;
        },
        
        /**
         * Registra callback para mudança de breakpoint
         */
        onBreakpointChange: function(callback) {
            if (typeof callback === 'function') {
                this.callbacks.breakpointChange.push(callback);
            }
        },
        
        /**
         * Registra callback para redimensionamento
         */
        onResize: function(callback) {
            if (typeof callback === 'function') {
                this.callbacks.resize.push(callback);
            }
        },
        
        /**
         * Registra callback para mudança de orientação
         */
        onOrientationChange: function(callback) {
            if (typeof callback === 'function') {
                this.callbacks.orientationChange.push(callback);
            }
        },
        
        /**
         * Força atualização
         */
        refresh: function() {
            this.detectInitialState();
            this.determineCurrentBreakpoint();
            this.applyBreakpointSettings();
            
            if (this.settings.debugMode) {
                this.updateDebugInfo();
            }
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
            if (this.settings.debugMode && window.console) {
                console.log('[CCT Breakpoints]', message, data);
            }
        },
        
        /**
         * Destroy - limpa recursos
         */
        destroy: function() {
            // Limpar timers
            if (this.timers.resize) {
                clearTimeout(this.timers.resize);
            }
            if (this.timers.detection) {
                clearInterval(this.timers.detection);
            }
            
            // Remover eventos
            this.cache.window.off('resize orientationchange load');
            
            // Remover debug info
            if (this.cache.debugInfo) {
                this.cache.debugInfo.remove();
            }
            
            // Limpar callbacks
            this.callbacks = {
                breakpointChange: [],
                resize: [],
                orientationChange: []
            };
            
            // Resetar estado
            this.state.initialized = false;
            
            this.debug('Sistema de breakpoints destruído');
        }
    };
    
    // Expor globalmente
    window.CCTBreakpoints = CCTBreakpoints;
    
    // Auto-inicializar se configurações estão disponíveis
    $(document).ready(function() {
        if (typeof cctBreakpoints !== 'undefined') {
            CCTBreakpoints.init(
                cctBreakpoints.settings || {},
                cctBreakpoints.breakpoints || {}
            );
        }
    });
    
})(jQuery);

/**
 * Extensões para integração com outros módulos
 */
(function() {
    'use strict';
    
    // Integração com sistema de modo escuro
    $(document).on('cct:breakpointChanged', function(e, data) {
        if (typeof CCTDarkMode !== 'undefined') {
            // Ajustar posição do toggle baseado no breakpoint
            const isMobile = data.deviceType === 'mobile';
            if (isMobile) {
                $('.cct-fixed-dark-mode-toggle').addClass('cct-mobile-position');
            } else {
                $('.cct-fixed-dark-mode-toggle').removeClass('cct-mobile-position');
            }
        }
    });
    
    // Integração com sistema de animações
    $(document).on('cct:breakpointChanged', function(e, data) {
        if (typeof CCTAnimations !== 'undefined') {
            // Ajustar animações baseado no dispositivo
            if (data.deviceType === 'mobile') {
                CCTAnimations.setPerformanceMode('fast');
            } else {
                CCTAnimations.setPerformanceMode('quality');
            }
        }
    });
    
    // Integração com biblioteca de padrões
    $(document).on('cct:breakpointChanged', function(e, data) {
        if (typeof CCTPatterns !== 'undefined') {
            // Ajustar layout dos padrões
            const columns = data.breakpoint ? data.breakpoint.columns : 12;
            document.documentElement.style.setProperty('--cct-pattern-columns', columns);
        }
    });
    
    // Integração com sistema de layout
    $(document).on('cct:breakpointChanged', function(e, data) {
        // Atualizar classes de layout responsivo
        $('[class*="cct-col-"]').each(function() {
            const $col = $(this);
            const classes = $col.attr('class').split(' ');
            
            // Encontrar classe de coluna para o breakpoint atual
            const currentBpClass = classes.find(cls => 
                cls.startsWith(`cct-col-${data.current}-`)
            );
            
            if (currentBpClass) {
                // Aplicar largura baseada na classe
                const colSize = currentBpClass.split('-').pop();
                const width = (parseInt(colSize) / (data.breakpoint?.columns || 12)) * 100;
                $col.css('flex-basis', `${width}%`);
            }
        });
    });
    
})();

/**
 * CSS para debug e elementos dinâmicos
 * Injetado via JavaScript
 */
(function() {
    const breakpointDebugCSS = `
        .cct-breakpoint-debug {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            border-radius: 6px;
            font-size: 11px;
            font-family: monospace;
            z-index: 10000;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        .cct-debug-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        
        .cct-debug-item:last-child {
            margin-bottom: 0;
        }
        
        .cct-debug-item strong {
            color: #4a9eff;
        }
        
        @media (max-width: 768px) {
            .cct-breakpoint-debug {
                top: 5px;
                right: 5px;
                font-size: 10px;
                padding: 8px;
                min-width: 150px;
            }
        }
        
        .cct-mobile-position {
            bottom: 20px !important;
            top: auto !important;
        }
    `;
    
    // Injetar CSS
    const style = document.createElement('style');
    style.textContent = breakpointDebugCSS;
    document.head.appendChild(style);
})();