/**
 * Sistema de Design Tokens CCT - JavaScript Frontend
 * 
 * Funcionalidades incluídas:
 * - Gerenciamento de tokens centralizados
 * - Resolução de referências de tokens
 * - API para acesso aos tokens
 * - Sincronização com módulos
 * - Export/Import de tokens
 * 
 * @package CCT_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Objeto principal do sistema de design tokens
     */
    const CCTDesignTokens = {
        
        // Configurações padrão
        settings: {
            enabled: true,
            autoSync: true,
            versionControl: true,
            namingConvention: 'kebab-case',
            cssPrefix: '--cct-'
        },
        
        // Estado interno
        state: {
            initialized: false,
            tokens: {
                primitive: {},
                semantic: {},
                component: {}
            },
            resolvedTokens: {},
            cssVariables: {},
            isLoading: false
        },
        
        // Cache de elementos
        cache: {
            body: null,
            root: null
        },
        
        // Callbacks registrados
        callbacks: {
            tokenChange: [],
            sync: []
        },
        
        /**
         * Inicializa o sistema de design tokens
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
            
            // Carregar tokens
            this.loadTokens();
            
            // Resolver referências
            this.resolveTokenReferences();
            
            // Aplicar tokens
            this.applyTokens();
            
            // Inicializar componentes
            this.initTokenWatcher();
            this.initSyncSystem();
            
            // Eventos
            this.bindEvents();
            
            // Marcar como inicializado
            this.state.initialized = true;
            
            // Debug
            this.debug('Sistema de design tokens inicializado', {
                totalTokens: this.getTotalTokensCount(),
                settings: this.settings
            });
        },
        
        /**
         * Cache de elementos DOM
         */
        cacheElements: function() {
            this.cache.body = $('body');
            this.cache.root = document.documentElement;
        },
        
        /**
         * Carrega tokens do servidor
         */
        loadTokens: function() {
            if (typeof cctDesignTokens !== 'undefined' && cctDesignTokens.tokens) {
                this.state.tokens = cctDesignTokens.tokens;
            }
        },
        
        /**
         * Resolve referências entre tokens
         */
        resolveTokenReferences: function() {
            this.state.resolvedTokens = this.deepClone(this.state.tokens);
            
            const maxIterations = 10; // Prevenir loops infinitos
            let iteration = 0;
            let hasReferences = true;
            
            while (hasReferences && iteration < maxIterations) {
                hasReferences = false;
                iteration++;
                
                for (const category in this.state.resolvedTokens) {
                    for (const subcategory in this.state.resolvedTokens[category]) {
                        for (const tokenKey in this.state.resolvedTokens[category][subcategory]) {
                            const token = this.state.resolvedTokens[category][subcategory][tokenKey];
                            
                            if (typeof token.value === 'string' && token.value.includes('{')) {
                                const resolvedValue = this.resolveTokenValue(token.value);
                                if (resolvedValue !== token.value) {
                                    token.value = resolvedValue;
                                } else if (token.value.includes('{')) {
                                    hasReferences = true;
                                }
                            }
                        }
                    }
                }
            }
            
            this.debug('Referências de tokens resolvidas', {
                iterations: iteration,
                hasUnresolved: hasReferences
            });
        },
        
        /**
         * Resolve valor de um token com referências
         */
        resolveTokenValue: function(value) {
            if (typeof value !== 'string') {
                return value;
            }
            
            return value.replace(/\{([^}]+)\}/g, (match, reference) => {
                const tokenValue = this.getTokenByPath(reference);
                return tokenValue !== null ? tokenValue : match;
            });
        },
        
        /**
         * Obtém token por caminho
         */
        getTokenByPath: function(path) {
            const parts = path.split('.');
            
            if (parts.length >= 3) {
                const category = parts[0];
                const subcategory = parts[1];
                const tokenKey = parts[2];
                
                if (this.state.resolvedTokens[category] &&
                    this.state.resolvedTokens[category][subcategory] &&
                    this.state.resolvedTokens[category][subcategory][tokenKey]) {
                    return this.state.resolvedTokens[category][subcategory][tokenKey].value;
                }
            }
            
            return null;
        },
        
        /**
         * Aplica tokens como variáveis CSS
         */
        applyTokens: function() {
            this.generateCSSVariables();
            this.injectCSSVariables();
        },
        
        /**
         * Gera variáveis CSS dos tokens
         */
        generateCSSVariables: function() {
            this.state.cssVariables = {};
            
            for (const category in this.state.resolvedTokens) {
                for (const subcategory in this.state.resolvedTokens[category]) {
                    for (const tokenKey in this.state.resolvedTokens[category][subcategory]) {
                        const token = this.state.resolvedTokens[category][subcategory][tokenKey];
                        const cssVarName = this.generateCSSVariableName(category, subcategory, tokenKey);
                        
                        this.state.cssVariables[cssVarName] = token.value;
                    }
                }
            }
        },
        
        /**
         * Gera nome de variável CSS
         */
        generateCSSVariableName: function(category, subcategory, tokenKey) {
            const parts = [category, subcategory, tokenKey];
            let name;
            
            switch (this.settings.namingConvention) {
                case 'camelCase':
                    name = parts[0] + parts.slice(1).map(part => 
                        part.charAt(0).toUpperCase() + part.slice(1)
                    ).join('');
                    break;
                case 'snake_case':
                    name = parts.join('_');
                    break;
                case 'kebab-case':
                default:
                    name = parts.join('-');
                    break;
            }
            
            return this.settings.cssPrefix + name;
        },
        
        /**
         * Injeta variáveis CSS no DOM
         */
        injectCSSVariables: function() {
            // Remover estilo anterior se existir
            $('#cct-design-tokens-vars').remove();
            
            let cssText = ':root {\n';
            
            for (const varName in this.state.cssVariables) {
                cssText += `  ${varName}: ${this.state.cssVariables[varName]};\n`;
            }
            
            cssText += '}';
            
            // Injetar novo estilo
            $('<style id="cct-design-tokens-vars">' + cssText + '</style>').appendTo('head');
            
            this.debug('Variáveis CSS injetadas', {
                totalVariables: Object.keys(this.state.cssVariables).length
            });
        },
        
        /**
         * Inicializa observador de mudanças
         */
        initTokenWatcher: function() {
            // Observar mudanças no DOM para tokens dinâmicos
            if (window.MutationObserver) {
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === 'childList') {
                            this.processNewElements(mutation.addedNodes);
                        }
                    });
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        },
        
        /**
         * Processa novos elementos adicionados ao DOM
         */
        processNewElements: function(nodes) {
            nodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    // Processar elementos com atributos de token
                    const tokenElements = $(node).find('[data-cct-token]').addBack('[data-cct-token]');
                    
                    tokenElements.each((index, element) => {
                        this.applyTokenToElement(element);
                    });
                }
            });
        },
        
        /**
         * Aplica token a um elemento específico
         */
        applyTokenToElement: function(element) {
            const $element = $(element);
            const tokenPath = $element.data('cct-token');
            const property = $element.data('cct-property') || 'color';
            
            if (tokenPath) {
                const tokenValue = this.getToken(tokenPath);
                if (tokenValue !== null) {
                    $element.css(property, tokenValue);
                }
            }
        },
        
        /**
         * Inicializa sistema de sincronização
         */
        initSyncSystem: function() {
            if (!this.settings.autoSync) {
                return;
            }
            
            // Sincronizar com outros módulos quando tokens mudarem
            this.onTokenChange(() => {
                this.syncWithModules();
            });
        },
        
        /**
         * Sincroniza com outros módulos
         */
        syncWithModules: function() {
            // Sincronizar com sistema de cores
            if (typeof CCTColorManager !== 'undefined') {
                this.syncWithColorManager();
            }
            
            // Sincronizar com sistema de tipografia
            if (typeof CCTTypography !== 'undefined') {
                this.syncWithTypography();
            }
            
            // Sincronizar com sistema de sombras
            if (typeof CCTShadows !== 'undefined') {
                this.syncWithShadows();
            }
            
            // Sincronizar com sistema de breakpoints
            if (typeof CCTBreakpoints !== 'undefined') {
                this.syncWithBreakpoints();
            }
            
            // Disparar callbacks de sincronização
            this.triggerSyncCallbacks();
        },
        
        /**
         * Sincroniza com gerenciador de cores
         */
        syncWithColorManager: function() {
            const colorTokens = this.getTokensByCategory('colors');
            
            for (const subcategory in colorTokens) {
                for (const tokenKey in colorTokens[subcategory]) {
                    const token = colorTokens[subcategory][tokenKey];
                    const cssVar = this.generateCSSVariableName('colors', subcategory, tokenKey);
                    
                    // Atualizar variável CSS no sistema de cores
                    document.documentElement.style.setProperty(cssVar, token.value);
                }
            }
        },
        
        /**
         * Sincroniza com sistema de tipografia
         */
        syncWithTypography: function() {
            const typographyTokens = this.getTokensByCategory('typography');
            
            for (const subcategory in typographyTokens) {
                for (const tokenKey in typographyTokens[subcategory]) {
                    const token = typographyTokens[subcategory][tokenKey];
                    const cssVar = this.generateCSSVariableName('typography', subcategory, tokenKey);
                    
                    document.documentElement.style.setProperty(cssVar, token.value);
                }
            }
        },
        
        /**
         * Vincula eventos
         */
        bindEvents: function() {
            // Evento de mudança de tema (modo escuro/claro)
            $(document).on('cct:darkModeChanged', (e, data) => {
                this.handleThemeChange(data.currentMode);
            });
            
            // Evento de mudança de breakpoint
            $(document).on('cct:breakpointChanged', (e, data) => {
                this.handleBreakpointChange(data.current);
            });
        },
        
        /**
         * Manipula mudança de tema
         */
        handleThemeChange: function(theme) {
            // Aplicar tokens específicos do tema se existirem
            const themeTokens = this.getTokensByTheme(theme);
            
            if (themeTokens) {
                this.applyThemeTokens(themeTokens);
            }
        },
        
        /**
         * Manipula mudança de breakpoint
         */
        handleBreakpointChange: function(breakpoint) {
            // Aplicar tokens específicos do breakpoint se existirem
            const breakpointTokens = this.getTokensByBreakpoint(breakpoint);
            
            if (breakpointTokens) {
                this.applyBreakpointTokens(breakpointTokens);
            }
        },
        
        /**
         * API Pública
         */
        
        /**
         * Obtém valor de um token
         */
        getToken: function(path) {
            return this.getTokenByPath(path);
        },
        
        /**
         * Obtém variável CSS de um token
         */
        getTokenCSSVar: function(path) {
            const parts = path.split('.');
            
            if (parts.length >= 3) {
                return this.generateCSSVariableName(parts[0], parts[1], parts[2]);
            }
            
            return null;
        },
        
        /**
         * Obtém todos os tokens de uma categoria
         */
        getTokensByCategory: function(category) {
            return this.state.resolvedTokens[category] || {};
        },
        
        /**
         * Obtém tokens por tema
         */
        getTokensByTheme: function(theme) {
            // Implementar lógica para tokens específicos de tema
            return null;
        },
        
        /**
         * Obtém tokens por breakpoint
         */
        getTokensByBreakpoint: function(breakpoint) {
            // Implementar lógica para tokens específicos de breakpoint
            return null;
        },
        
        /**
         * Define valor de um token
         */
        setToken: function(path, value, description = '') {
            const parts = path.split('.');
            
            if (parts.length >= 3) {
                const category = parts[0];
                const subcategory = parts[1];
                const tokenKey = parts[2];
                
                // Criar estrutura se não existir
                if (!this.state.tokens[category]) {
                    this.state.tokens[category] = {};
                }
                
                if (!this.state.tokens[category][subcategory]) {
                    this.state.tokens[category][subcategory] = {};
                }
                
                // Definir token
                this.state.tokens[category][subcategory][tokenKey] = {
                    value: value,
                    description: description,
                    category: subcategory
                };
                
                // Resolver referências e aplicar
                this.resolveTokenReferences();
                this.applyTokens();
                
                // Disparar evento de mudança
                this.triggerTokenChange(path, value);
                
                return true;
            }
            
            return false;
        },
        
        /**
         * Remove um token
         */
        removeToken: function(path) {
            const parts = path.split('.');
            
            if (parts.length >= 3) {
                const category = parts[0];
                const subcategory = parts[1];
                const tokenKey = parts[2];
                
                if (this.state.tokens[category] &&
                    this.state.tokens[category][subcategory] &&
                    this.state.tokens[category][subcategory][tokenKey]) {
                    
                    delete this.state.tokens[category][subcategory][tokenKey];
                    
                    // Resolver referências e aplicar
                    this.resolveTokenReferences();
                    this.applyTokens();
                    
                    // Disparar evento de mudança
                    this.triggerTokenChange(path, null);
                    
                    return true;
                }
            }
            
            return false;
        },
        
        /**
         * Exporta tokens em formato específico
         */
        exportTokens: function(format = 'json') {
            switch (format) {
                case 'css':
                    return this.exportToCSS();
                case 'scss':
                    return this.exportToSCSS();
                case 'js':
                    return this.exportToJS();
                case 'json':
                default:
                    return JSON.stringify(this.state.resolvedTokens, null, 2);
            }
        },
        
        /**
         * Exporta para CSS
         */
        exportToCSS: function() {
            let css = ':root {\n';
            
            for (const varName in this.state.cssVariables) {
                css += `  ${varName}: ${this.state.cssVariables[varName]};\n`;
            }
            
            css += '}';
            
            return css;
        },
        
        /**
         * Exporta para SCSS
         */
        exportToSCSS: function() {
            let scss = '// Design Tokens\n\n';
            
            for (const varName in this.state.cssVariables) {
                const scssVar = '$' + varName.replace(this.settings.cssPrefix, '').replace(/^-+/, '');
                scss += `${scssVar}: ${this.state.cssVariables[varName]};\n`;
            }
            
            return scss;
        },
        
        /**
         * Exporta para JavaScript
         */
        exportToJS: function() {
            const jsObject = {};
            
            for (const varName in this.state.cssVariables) {
                const jsKey = varName.replace(this.settings.cssPrefix, '').replace(/^-+/, '').replace(/-([a-z])/g, (g) => g[1].toUpperCase());
                jsObject[jsKey] = this.state.cssVariables[varName];
            }
            
            return `const designTokens = ${JSON.stringify(jsObject, null, 2)};\n\nexport default designTokens;`;
        },
        
        /**
         * Importa tokens
         */
        importTokens: function(data, merge = true) {
            try {
                const importedTokens = typeof data === 'string' ? JSON.parse(data) : data;
                
                if (merge) {
                    // Mesclar com tokens existentes
                    this.state.tokens = this.deepMerge(this.state.tokens, importedTokens);
                } else {
                    // Substituir tokens existentes
                    this.state.tokens = importedTokens;
                }
                
                // Resolver referências e aplicar
                this.resolveTokenReferences();
                this.applyTokens();
                
                // Disparar evento de mudança
                this.triggerTokenChange('*', null);
                
                return true;
            } catch (error) {
                this.debug('Erro ao importar tokens:', error);
                return false;
            }
        },
        
        /**
         * Obtém estatísticas dos tokens
         */
        getStats: function() {
            let totalTokens = 0;
            const categories = {};
            
            for (const category in this.state.tokens) {
                categories[category] = 0;
                
                for (const subcategory in this.state.tokens[category]) {
                    const count = Object.keys(this.state.tokens[category][subcategory]).length;
                    categories[category] += count;
                    totalTokens += count;
                }
            }
            
            return {
                totalTokens,
                categories,
                cssVariables: Object.keys(this.state.cssVariables).length,
                resolved: Object.keys(this.state.resolvedTokens).length > 0
            };
        },
        
        /**
         * Obtém contagem total de tokens
         */
        getTotalTokensCount: function() {
            return this.getStats().totalTokens;
        },
        
        /**
         * Registra callback para mudança de token
         */
        onTokenChange: function(callback) {
            if (typeof callback === 'function') {
                this.callbacks.tokenChange.push(callback);
            }
        },
        
        /**
         * Registra callback para sincronização
         */
        onSync: function(callback) {
            if (typeof callback === 'function') {
                this.callbacks.sync.push(callback);
            }
        },
        
        /**
         * Dispara callbacks de mudança de token
         */
        triggerTokenChange: function(path, value) {
            const eventData = {
                path,
                value,
                timestamp: Date.now()
            };
            
            // Evento jQuery
            $(document).trigger('cct:tokenChanged', eventData);
            
            // Callbacks registrados
            this.callbacks.tokenChange.forEach(callback => {
                if (typeof callback === 'function') {
                    callback(eventData);
                }
            });
        },
        
        /**
         * Dispara callbacks de sincronização
         */
        triggerSyncCallbacks: function() {
            const eventData = {
                timestamp: Date.now(),
                stats: this.getStats()
            };
            
            // Evento jQuery
            $(document).trigger('cct:tokensSync', eventData);
            
            // Callbacks registrados
            this.callbacks.sync.forEach(callback => {
                if (typeof callback === 'function') {
                    callback(eventData);
                }
            });
        },
        
        /**
         * Força atualização
         */
        refresh: function() {
            this.loadTokens();
            this.resolveTokenReferences();
            this.applyTokens();
            this.syncWithModules();
        },
        
        /**
         * Utilitários
         */
        
        /**
         * Clone profundo de objeto
         */
        deepClone: function(obj) {
            if (obj === null || typeof obj !== 'object') {
                return obj;
            }
            
            if (obj instanceof Date) {
                return new Date(obj.getTime());
            }
            
            if (obj instanceof Array) {
                return obj.map(item => this.deepClone(item));
            }
            
            const cloned = {};
            for (const key in obj) {
                if (obj.hasOwnProperty(key)) {
                    cloned[key] = this.deepClone(obj[key]);
                }
            }
            
            return cloned;
        },
        
        /**
         * Merge profundo de objetos
         */
        deepMerge: function(target, source) {
            const result = this.deepClone(target);
            
            for (const key in source) {
                if (source.hasOwnProperty(key)) {
                    if (typeof source[key] === 'object' && source[key] !== null && !Array.isArray(source[key])) {
                        result[key] = this.deepMerge(result[key] || {}, source[key]);
                    } else {
                        result[key] = source[key];
                    }
                }
            }
            
            return result;
        },
        
        /**
         * Debug helper
         */
        debug: function(message, data = null) {
            if (window.cctDesignTokensDebug) {
                console.log('[CCT Design Tokens]', message, data);
            }
        },
        
        /**
         * Destroy - limpa recursos
         */
        destroy: function() {
            // Remover variáveis CSS injetadas
            $('#cct-design-tokens-vars').remove();
            
            // Limpar callbacks
            this.callbacks = {
                tokenChange: [],
                sync: []
            };
            
            // Resetar estado
            this.state.initialized = false;
            
            this.debug('Sistema de design tokens destruído');
        }
    };
    
    // Expor globalmente
    window.CCTDesignTokens = CCTDesignTokens;
    
    // Auto-inicializar se configurações estão disponíveis
    $(document).ready(function() {
        if (typeof cctDesignTokens !== 'undefined') {
            CCTDesignTokens.init(cctDesignTokens.settings || {});
        }
    });
    
})(jQuery);

/**
 * Extensões para integração com outros módulos
 */
(function($) {
    // Integração com sistema de cores
    $(document).on('cct:tokenChanged', function(e, data) {
        if (data.path.startsWith('colors.') && typeof CCTColorManager !== 'undefined') {
            // Atualizar cores no gerenciador de cores
            CCTColorManager.updateFromTokens();
        }
    });
    
    // Integração com sistema de tipografia
    $(document).on('cct:tokenChanged', function(e, data) {
        if (data.path.startsWith('typography.') && typeof CCTTypography !== 'undefined') {
            // Atualizar tipografia
            CCTTypography.updateFromTokens();
        }
    });
    
    // Integração com sistema de sombras
    $(document).on('cct:tokenChanged', function(e, data) {
        if (data.path.startsWith('shadows.') && typeof CCTShadows !== 'undefined') {
            // Atualizar sombras
            CCTShadows.updateFromTokens();
        }
    });
    
    // Integração com sistema de espaçamentos
    $(document).on('cct:tokenChanged', function(e, data) {
        if (data.path.startsWith('spacing.') && typeof CCTLayout !== 'undefined') {
            // Atualizar espaçamentos
            CCTLayout.updateFromTokens();
        }
    });

})(jQuery);

/**
 * Utilitarios globais para tokens
 */
window.CCTTokenUtils = {
    
    /**
     * Obtém valor de token via CSS custom property
     */
    getCSSToken: function(tokenName) {
        return getComputedStyle(document.documentElement).getPropertyValue(tokenName).trim();
    },
    
    /**
     * Define valor de token via CSS custom property
     */
    setCSSToken: function(tokenName, value) {
        document.documentElement.style.setProperty(tokenName, value);
    },
    
    /**
     * Converte token path para CSS variable
     */
    pathToCSSVar: function(path, prefix = '--cct-') {
        return prefix + path.replace(/\./g, '-');
    },
    
    /**
     * Aplica token a elemento
     */
    applyTokenToElement: function(element, tokenPath, property = 'color') {
        if (typeof CCTDesignTokens !== 'undefined') {
            const value = CCTDesignTokens.getToken(tokenPath);
            if (value !== null) {
                element.style[property] = value;
            }
        }
    },
    
    /**
     * Cria elemento com token aplicado
     */
    createElement: function(tag, tokenPath, property = 'color') {
        const element = document.createElement(tag);
        this.applyTokenToElement(element, tokenPath, property);
        return element;
    }
};