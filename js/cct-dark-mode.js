/**
 * Sistema de Modo Escuro/Claro CCT - JavaScript Frontend
 * 
 * Funcionalidades incluídas:
 * - Toggle automático baseado em horário
 * - Detecção de preferência do sistema
 * - Salvamento de preferências do usuário
 * - Transições suaves entre modos
 * - Toggle manual com múltiplos estilos
 * - Integração com todos os módulos
 * 
 * @package CCT_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Objeto principal do sistema de modo escuro
     */
    const CCTDarkMode = {
        
        // Configurações padrão
        settings: {
            enabled: true,
            defaultMode: 'auto',
            autoToggle: true,
            autoStartTime: '18:00',
            autoEndTime: '06:00',
            respectSystemPreference: true,
            rememberUserChoice: true,
            smoothTransitions: true,
            transitionDuration: 0.3,
            transitionEasing: 'ease-in-out',
            togglePosition: 'top-right',
            lightColors: {},
            darkColors: {}
        },
        
        // Estado interno
        state: {
            initialized: false,
            currentMode: 'light',
            userPreference: null,
            systemPreference: null,
            autoMode: null,
            isTransitioning: false
        },
        
        // Cache de elementos
        cache: {
            body: null,
            html: null,
            toggleButtons: null,
            themeIndicators: null,
            adminBarToggle: null
        },
        
        // Timers
        timers: {
            autoCheck: null,
            transitionEnd: null
        },
        
        /**
         * Inicializa o sistema de modo escuro
         */
        init: function(customSettings = {}) {
            // Merge configurações
            this.settings = { ...this.settings, ...customSettings };
            
            // Verificar se está habilitado
            if (!this.settings.enabled) {
                return;
            }
            
            // Cache de elementos
            this.cacheElements();
            
            // Detectar preferências
            this.detectSystemPreference();
            this.loadUserPreference();
            
            // Determinar modo inicial
            this.determineInitialMode();
            
            // Aplicar modo inicial
            this.applyMode(this.state.currentMode, false);
            
            // Inicializar componentes
            this.initToggleButtons();
            this.initAutoToggle();
            this.initSystemPreferenceListener();
            this.initKeyboardShortcuts();
            
            // Eventos
            this.bindEvents();
            
            // Marcar como inicializado
            this.state.initialized = true;
            
            // Debug
            this.debug('Sistema de modo escuro inicializado', {
                currentMode: this.state.currentMode,
                userPreference: this.state.userPreference,
                systemPreference: this.state.systemPreference,
                settings: this.settings
            });
        },
        
        /**
         * Cache de elementos DOM
         */
        cacheElements: function() {
            this.cache.body = $('body');
            this.cache.html = $('html');
            this.cache.toggleButtons = $('[data-cct-dark-mode-toggle]');
            this.cache.themeIndicators = $('[data-cct-theme-indicator]');
            this.cache.adminBarToggle = $('.cct-admin-bar-toggle');
        },
        
        /**
         * Detecta preferência do sistema
         */
        detectSystemPreference: function() {
            if (!this.settings.respectSystemPreference) {
                return;
            }
            
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                this.state.systemPreference = 'dark';
            } else {
                this.state.systemPreference = 'light';
            }
            
            this.debug('Preferência do sistema detectada:', this.state.systemPreference);
        },
        
        /**
         * Carrega preferência do usuário
         */
        loadUserPreference: function() {
            if (!this.settings.rememberUserChoice) {
                return;
            }
            
            // Tentar localStorage primeiro
            try {
                const stored = localStorage.getItem('cct_dark_mode_preference');
                if (stored && ['light', 'dark', 'auto'].includes(stored)) {
                    this.state.userPreference = stored;
                }
            } catch (e) {
                this.debug('Erro ao acessar localStorage:', e);
            }
            
            // Fallback para cookie
            if (!this.state.userPreference) {
                const cookie = this.getCookie('cct_dark_mode_preference');
                if (cookie && ['light', 'dark', 'auto'].includes(cookie)) {
                    this.state.userPreference = cookie;
                }
            }
            
            this.debug('Preferência do usuário carregada:', this.state.userPreference);
        },
        
        /**
         * Determina modo inicial
         */
        determineInitialMode: function() {
            // Prioridade: preferência do usuário > configuração padrão > sistema
            if (this.state.userPreference) {
                if (this.state.userPreference === 'auto') {
                    this.state.currentMode = this.calculateAutoMode();
                } else {
                    this.state.currentMode = this.state.userPreference;
                }
            } else if (this.settings.defaultMode === 'system' && this.state.systemPreference) {
                this.state.currentMode = this.state.systemPreference;
            } else if (this.settings.defaultMode === 'auto') {
                this.state.currentMode = this.calculateAutoMode();
            } else {
                this.state.currentMode = this.settings.defaultMode;
            }
            
            this.debug('Modo inicial determinado:', this.state.currentMode);
        },
        
        /**
         * Calcula modo automático baseado no horário
         */
        calculateAutoMode: function() {
            if (!this.settings.autoToggle) {
                return this.state.systemPreference || 'light';
            }
            
            const now = new Date();
            const currentTime = now.getHours() * 60 + now.getMinutes();
            
            const startTime = this.parseTime(this.settings.autoStartTime);
            const endTime = this.parseTime(this.settings.autoEndTime);
            
            let isDarkTime;
            
            if (startTime > endTime) {
                // Período noturno atravessa meia-noite (ex: 18:00 - 06:00)
                isDarkTime = currentTime >= startTime || currentTime < endTime;
            } else {
                // Período diurno (ex: 06:00 - 18:00)
                isDarkTime = currentTime >= startTime && currentTime < endTime;
            }
            
            this.state.autoMode = isDarkTime ? 'dark' : 'light';
            
            this.debug('Modo automático calculado:', {
                currentTime: `${Math.floor(currentTime / 60)}:${String(currentTime % 60).padStart(2, '0')}`,
                startTime: this.settings.autoStartTime,
                endTime: this.settings.autoEndTime,
                isDarkTime,
                autoMode: this.state.autoMode
            });
            
            return this.state.autoMode;
        },
        
        /**
         * Converte string de tempo para minutos
         */
        parseTime: function(timeString) {
            const [hours, minutes] = timeString.split(':').map(Number);
            return hours * 60 + minutes;
        },
        
        /**
         * Aplica um modo específico
         */
        applyMode: function(mode, animate = true) {
            if (this.state.isTransitioning) {
                return;
            }
            
            const previousMode = this.state.currentMode;
            this.state.currentMode = mode;
            
            // Marcar transição
            if (animate && this.settings.smoothTransitions) {
                this.state.isTransitioning = true;
                this.cache.body.addClass('cct-transitioning');
                
                // Limpar timer anterior
                if (this.timers.transitionEnd) {
                    clearTimeout(this.timers.transitionEnd);
                }
                
                // Timer para fim da transição
                this.timers.transitionEnd = setTimeout(() => {
                    this.state.isTransitioning = false;
                    this.cache.body.removeClass('cct-transitioning');
                }, this.settings.transitionDuration * 1000);
            }
            
            // Aplicar atributo de tema
            this.cache.html.attr('data-theme', mode);
            
            // Aplicar classes CSS
            this.cache.body
                .removeClass('cct-theme-light cct-theme-dark')
                .addClass(`cct-theme-${mode}`);
            
            // Atualizar variáveis CSS
            this.updateCSSVariables(mode);
            
            // Atualizar toggles
            this.updateToggleButtons(mode);
            
            // Atualizar indicadores
            this.updateThemeIndicators(mode);
            
            // Atualizar meta theme-color
            this.updateThemeColorMeta(mode);
            
            // Disparar evento customizado
            $(document).trigger('cct:darkModeChanged', {
                previousMode,
                currentMode: mode,
                isUserTriggered: animate
            });
            
            this.debug('Modo aplicado:', {
                previousMode,
                currentMode: mode,
                animate
            });
        },
        
        /**
         * Atualiza variáveis CSS
         */
        updateCSSVariables: function(mode) {
            const colors = mode === 'dark' ? this.settings.darkColors : this.settings.lightColors;
            const root = document.documentElement;
            
            Object.keys(colors).forEach(colorKey => {
                root.style.setProperty(`--cct-color-${colorKey}`, colors[colorKey]);
            });
        },
        
        /**
         * Atualiza botões de toggle
         */
        updateToggleButtons: function(mode) {
            this.cache.toggleButtons.each(function() {
                const $toggle = $(this);
                const $btn = $toggle.find('.cct-toggle-btn, .cct-toggle-icon-btn');
                const $input = $toggle.find('.cct-toggle-input');
                const $text = $toggle.find('.cct-toggle-text');
                const $icon = $toggle.find('.cct-toggle-icon');
                
                // Atualizar estado do input
                $input.prop('checked', mode === 'dark');
                
                // Atualizar texto
                if ($text.length) {
                    const text = mode === 'dark' ? 
                        cctDarkMode.strings.lightMode : 
                        cctDarkMode.strings.darkMode;
                    $text.text(text);
                }
                
                // Atualizar ícone
                if ($icon.length) {
                    $icon.removeClass('cct-icon-sun cct-icon-moon')
                          .addClass(mode === 'dark' ? 'cct-icon-sun' : 'cct-icon-moon');
                }
                
                // Atualizar aria-label
                const label = mode === 'dark' ? 
                    cctDarkMode.strings.lightMode : 
                    cctDarkMode.strings.darkMode;
                $btn.attr('aria-label', label);
                $input.attr('aria-label', label);
                
                // Atualizar classes
                $toggle.removeClass('cct-mode-light cct-mode-dark')
                       .addClass(`cct-mode-${mode}`);
            });
            
            // Atualizar admin bar
            if (this.cache.adminBarToggle.length) {
                const text = mode === 'dark' ? '☀️ Modo Claro' : '🌙 Modo Escuro';
                this.cache.adminBarToggle.text(text);
            }
        },
        
        /**
         * Atualiza indicadores de tema
         */
        updateThemeIndicators: function(mode) {
            this.cache.themeIndicators.each(function() {
                const $indicator = $(this);
                const $icon = $indicator.find('.cct-theme-icon');
                const $text = $indicator.find('.cct-theme-text');
                
                // Atualizar ícone
                if ($icon.length) {
                    $icon.removeClass('cct-icon-sun cct-icon-moon')
                         .addClass(mode === 'dark' ? 'cct-icon-moon' : 'cct-icon-sun');
                }
                
                // Atualizar texto
                if ($text.length) {
                    const text = mode === 'dark' ? 
                        cctDarkMode.strings.darkMode : 
                        cctDarkMode.strings.lightMode;
                    $text.text(text);
                }
                
                // Atualizar classes
                $indicator.removeClass('cct-mode-light cct-mode-dark')
                          .addClass(`cct-mode-${mode}`);
            });
        },
        
        /**
         * Atualiza meta theme-color
         */
        updateThemeColorMeta: function(mode) {
            const colors = mode === 'dark' ? this.settings.darkColors : this.settings.lightColors;
            const backgroundColor = colors.background || (mode === 'dark' ? '#1a1a1a' : '#ffffff');
            
            // Remover meta tags existentes
            $('meta[name="theme-color"]').remove();
            
            // Adicionar nova meta tag
            $('head').append(`<meta name="theme-color" content="${backgroundColor}">`);
        },
        
        /**
         * Inicializa botões de toggle
         */
        initToggleButtons: function() {
            // Criar toggle fixo se necessário
            if (this.settings.togglePosition !== 'custom' && this.settings.togglePosition !== 'header' && this.settings.togglePosition !== 'footer') {
                this.createFixedToggle();
            }
            
            // Eventos de clique
            $(document).on('click', '[data-cct-dark-mode-toggle] .cct-toggle-btn, [data-cct-dark-mode-toggle] .cct-toggle-icon-btn', (e) => {
                e.preventDefault();
                this.toggleMode();
            });
            
            // Eventos de mudança no switch
            $(document).on('change', '[data-cct-dark-mode-toggle] .cct-toggle-input', (e) => {
                const isChecked = e.target.checked;
                this.setMode(isChecked ? 'dark' : 'light', true);
            });
            
            // Admin bar toggle
            $(document).on('click', '.cct-admin-bar-toggle', (e) => {
                e.preventDefault();
                this.toggleMode();
            });
        },
        
        /**
         * Cria toggle fixo
         */
        createFixedToggle: function() {
            if ($('.cct-fixed-dark-mode-toggle').length) {
                return;
            }
            
            const position = this.settings.togglePosition;
            const positionClass = `cct-position-${position}`;
            
            const $toggle = $(`
                <div class="cct-fixed-dark-mode-toggle ${positionClass}" data-cct-dark-mode-toggle>
                    <button type="button" class="cct-toggle-btn" aria-label="${cctDarkMode.strings.toggleTooltip}">
                        <span class="cct-toggle-icon cct-icon-moon"></span>
                    </button>
                </div>
            `);
            
            $('body').append($toggle);
            
            // Atualizar cache
            this.cache.toggleButtons = $('[data-cct-dark-mode-toggle]');
        },
        
        /**
         * Inicializa toggle automático
         */
        initAutoToggle: function() {
            if (!this.settings.autoToggle) {
                return;
            }
            
            // Verificar a cada minuto
            this.timers.autoCheck = setInterval(() => {
                const newAutoMode = this.calculateAutoMode();
                
                // Só aplicar se não há preferência manual do usuário
                if (!this.state.userPreference || this.state.userPreference === 'auto') {
                    if (newAutoMode !== this.state.currentMode) {
                        this.applyMode(newAutoMode, true);
                    }
                }
            }, 60000); // 1 minuto
        },
        
        /**
         * Inicializa listener de preferência do sistema
         */
        initSystemPreferenceListener: function() {
            if (!this.settings.respectSystemPreference || !window.matchMedia) {
                return;
            }
            
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            const handleChange = (e) => {
                this.state.systemPreference = e.matches ? 'dark' : 'light';
                
                // Aplicar se modo padrão é 'system' e não há preferência do usuário
                if (this.settings.defaultMode === 'system' && !this.state.userPreference) {
                    this.applyMode(this.state.systemPreference, true);
                }
                
                this.debug('Preferência do sistema alterada:', this.state.systemPreference);
            };
            
            // Listener moderno
            if (mediaQuery.addEventListener) {
                mediaQuery.addEventListener('change', handleChange);
            } else {
                // Fallback para navegadores antigos
                mediaQuery.addListener(handleChange);
            }
        },
        
        /**
         * Inicializa atalhos de teclado
         */
        initKeyboardShortcuts: function() {
            $(document).on('keydown', (e) => {
                // Ctrl/Cmd + Shift + D
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                    e.preventDefault();
                    this.toggleMode();
                }
            });
        },
        
        /**
         * Vincula eventos gerais
         */
        bindEvents: function() {
            // Evento de visibilidade da página
            $(document).on('visibilitychange', () => {
                if (!document.hidden && this.settings.autoToggle) {
                    // Recalcular modo automático quando a página fica visível
                    const newAutoMode = this.calculateAutoMode();
                    if (!this.state.userPreference || this.state.userPreference === 'auto') {
                        if (newAutoMode !== this.state.currentMode) {
                            this.applyMode(newAutoMode, true);
                        }
                    }
                }
            });
            
            // Evento de redimensionamento
            $(window).on('resize', this.debounce(() => {
                this.handleResize();
            }, 250));
        },
        
        /**
         * Alterna entre modos
         */
        toggleMode: function() {
            const newMode = this.state.currentMode === 'dark' ? 'light' : 'dark';
            this.setMode(newMode, true);
        },
        
        /**
         * Define um modo específico
         */
        setMode: function(mode, savePreference = false) {
            if (!['light', 'dark', 'auto'].includes(mode)) {
                this.debug('Modo inválido:', mode);
                return;
            }
            
            let targetMode = mode;
            
            if (mode === 'auto') {
                targetMode = this.calculateAutoMode();
            }
            
            // Aplicar modo
            this.applyMode(targetMode, true);
            
            // Salvar preferência
            if (savePreference && this.settings.rememberUserChoice) {
                this.saveUserPreference(mode);
            }
        },
        
        /**
         * Salva preferência do usuário
         */
        saveUserPreference: function(preference) {
            this.state.userPreference = preference;
            
            // Salvar no localStorage
            try {
                localStorage.setItem('cct_dark_mode_preference', preference);
            } catch (e) {
                this.debug('Erro ao salvar no localStorage:', e);
            }
            
            // Salvar via AJAX se disponível
            if (typeof cctDarkMode !== 'undefined' && cctDarkMode.ajaxUrl) {
                $.ajax({
                    url: cctDarkMode.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'cct_save_dark_mode_preference',
                        preference: preference,
                        nonce: cctDarkMode.nonce
                    },
                    success: (response) => {
                        this.debug('Preferência salva via AJAX:', response);
                    },
                    error: (xhr, status, error) => {
                        this.debug('Erro ao salvar preferência via AJAX:', error);
                    }
                });
            }
            
            this.debug('Preferência do usuário salva:', preference);
        },
        
        /**
         * Obtém valor de cookie
         */
        getCookie: function(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        },
        
        /**
         * Manipula redimensionamento
         */
        handleResize: function() {
            // Reposicionar toggle fixo se necessário
            const $fixedToggle = $('.cct-fixed-dark-mode-toggle');
            if ($fixedToggle.length) {
                // Lógica de reposicionamento se necessário
            }
        },
        
        /**
         * Obtém modo atual
         */
        getCurrentMode: function() {
            return this.state.currentMode;
        },
        
        /**
         * Obtém preferência do usuário
         */
        getUserPreference: function() {
            return this.state.userPreference;
        },
        
        /**
         * Obtém preferência do sistema
         */
        getSystemPreference: function() {
            return this.state.systemPreference;
        },
        
        /**
         * Verifica se está em modo escuro
         */
        isDarkMode: function() {
            return this.state.currentMode === 'dark';
        },
        
        /**
         * Verifica se está em modo claro
         */
        isLightMode: function() {
            return this.state.currentMode === 'light';
        },
        
        /**
         * Força atualização
         */
        refresh: function() {
            this.detectSystemPreference();
            this.loadUserPreference();
            this.determineInitialMode();
            this.applyMode(this.state.currentMode, false);
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
            if (window.cctDarkModeDebug) {
                console.log('[CCT Dark Mode]', message, data);
            }
        },
        
        /**
         * Destroy - limpa recursos
         */
        destroy: function() {
            // Limpar timers
            if (this.timers.autoCheck) {
                clearInterval(this.timers.autoCheck);
            }
            if (this.timers.transitionEnd) {
                clearTimeout(this.timers.transitionEnd);
            }
            
            // Remover toggle fixo
            $('.cct-fixed-dark-mode-toggle').remove();
            
            // Limpar cache
            this.cache = {
                body: null,
                html: null,
                toggleButtons: null,
                themeIndicators: null,
                adminBarToggle: null
            };
            
            // Resetar estado
            this.state.initialized = false;
            
            this.debug('Sistema de modo escuro destruído');
        }
    };
    
    // Expor globalmente
    window.CCTDarkMode = CCTDarkMode;
    
    // Auto-inicializar se configurações estão disponíveis
    $(document).ready(function() {
        if (typeof cctDarkMode !== 'undefined') {
            CCTDarkMode.init(cctDarkMode.settings || {});
        }
    });
    
})(jQuery);

/**
 * Extensões para integração com outros módulos
 */
(function($) {
    'use strict';
    
    // Integração com sistema de animações
    $(document).on('cct:darkModeChanged', function(e, data) {
        if (typeof CCTAnimations !== 'undefined') {
            // Pausar animações durante transição
            if (data.isUserTriggered) {
                CCTAnimations.pauseAnimations();
                setTimeout(function() {
                    CCTAnimations.resumeAnimations();
                }, 300);
            }
        }
    });
    
    // Integração com sistema de sombras
    $(document).on('cct:darkModeChanged', function(e, data) {
        if (typeof CCTShadows !== 'undefined') {
            // Atualizar cores das sombras
            const shadowColor = data.currentMode === 'dark' ? '255, 255, 255' : '0, 0, 0';
            document.documentElement.style.setProperty('--cct-shadow-color', shadowColor);
        }
    });
    
    // Integração com sistema de gradientes
    $(document).on('cct:darkModeChanged', function(e, data) {
        if (typeof CCTGradients !== 'undefined' && typeof CCTGradients.updateGradientsForTheme === 'function') {
            // Atualizar gradientes para o novo modo
            CCTGradients.updateGradientsForTheme(data.currentMode);
        } else if (typeof CCTGradients !== 'undefined' && typeof CCTGradients.adjustGradientsForDarkMode === 'function') {
            // Fallback para método alternativo
            CCTGradients.adjustGradientsForDarkMode(data.currentMode === 'dark');
        }
    });
    
    // Integração com sistema de ícones
    $(document).on('cct:darkModeChanged', function(e, data) {
        if (typeof CCTIcons !== 'undefined') {
            // Atualizar cores dos ícones SVG
            CCTIcons.updateIconColors(data.currentMode);
        }
    });
    
})(jQuery);