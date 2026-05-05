/**
 * Sistema de Gradientes CCT - JavaScript Frontend
 * 
 * Funcionalidades incluídas:
 * - Browser visual de gradientes
 * - Gerador interativo de gradientes
 * - Preview em tempo real
 * - Aplicação dinâmica
 * - Gerenciamento de favoritos
 * - Export e import
 * 
 * @package UENF_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Objeto principal do sistema de gradientes
     */
    const CCTGradients = {
        
        // Configurações padrão
        settings: {
            libraryEnabled: true,
            generatorEnabled: true,
            selectedCategory: 'all',
            favoriteGradients: [],
            customGradients: [],
            currentGradient: '',
            applyToBackgrounds: true,
            applyToButtons: false,
            applyToText: false,
            applyToBorders: false,
            enableCssVariables: true,
            enableFallbackColors: true,
            optimizePerformance: true
        },
        
        // Estado interno
        state: {
            initialized: false,
            currentView: 'grid',
            activeCategory: 'all',
            searchQuery: '',
            sortBy: 'name',
            generatorData: {
                type: 'linear',
                angle: 45,
                colors: [
                    { color: '#ff7e5f', position: 0 },
                    { color: '#feb47b', position: 100 }
                ]
            },
            previewMode: false
        },
        
        // Cache de elementos
        cache: {
            gradientGrid: null,
            categoryTabs: null,
            searchInput: null,
            sortSelect: null,
            previewBox: null,
            cssOutput: null
        },
        
        // Biblioteca de gradientes
        library: {},
        
        // Categorias
        categories: {},
        
        /**
         * Inicializa o sistema de gradientes
         */
        init: function(customSettings = {}) {
            // Merge configurações
            this.settings = { ...this.settings, ...customSettings };
            
            // Verificar se está habilitado
            if (!this.settings.libraryEnabled) {
                return;
            }
            
            // Inicializar componentes
            this.initBrowser();
            this.initGenerator();
            this.initApplicator();
            
            // Eventos
            this.bindEvents();
            
            // Marcar como inicializado
            this.state.initialized = true;
            
            // Debug
            this.debug('Sistema de gradientes inicializado', this.settings);
        },
        
        /**
         * Inicializa o browser de gradientes
         */
        initBrowser: function() {
            // Cache de elementos
            this.cache.gradientGrid = $('.uenf-gradient-grid');
            this.cache.categoryTabs = $('.uenf-category-tab');
            this.cache.searchInput = $('.uenf-gradient-search');
            this.cache.sortSelect = $('.uenf-gradient-sort');
            
            // Configurar filtros
            this.setupFilters();
            
            // Configurar busca
            this.setupSearch();
            
            // Configurar ordenação
            this.setupSorting();
            
            // Configurar visualização
            this.setupViewToggle();
        },
        
        /**
         * Configura filtros de categoria
         */
        setupFilters: function() {
            this.cache.categoryTabs.on('click', (e) => {
                const $tab = $(e.currentTarget);
                const category = $tab.data('category');
                
                // Atualizar estado
                this.state.activeCategory = category;
                
                // Atualizar UI
                this.cache.categoryTabs.removeClass('active');
                $tab.addClass('active');
                
                // Filtrar gradientes
                this.filterGradients();
            });
        },
        
        /**
         * Configura busca
         */
        setupSearch: function() {
            this.cache.searchInput.on('input', this.debounce((e) => {
                this.state.searchQuery = e.target.value.toLowerCase();
                this.filterGradients();
            }, 300));
        },
        
        /**
         * Configura ordenação
         */
        setupSorting: function() {
            this.cache.sortSelect.on('change', (e) => {
                this.state.sortBy = e.target.value;
                this.sortGradients();
            });
        },
        
        /**
         * Configura toggle de visualização
         */
        setupViewToggle: function() {
            $('.uenf-view-btn').on('click', (e) => {
                const $btn = $(e.currentTarget);
                const view = $btn.data('view');
                
                // Atualizar estado
                this.state.currentView = view;
                
                // Atualizar UI
                $('.uenf-view-btn').removeClass('active');
                $btn.addClass('active');
                
                // Aplicar visualização
                this.applyView(view);
            });
        },
        
        /**
         * Aplica visualização
         */
        applyView: function(view) {
            if (view === 'list') {
                this.cache.gradientGrid.addClass('list-view');
            } else {
                this.cache.gradientGrid.removeClass('list-view');
            }
        },
        
        /**
         * Filtra gradientes
         */
        filterGradients: function() {
            const category = this.state.activeCategory;
            const query = this.state.searchQuery;
            
            $('.uenf-gradient-item').each((index, element) => {
                const $item = $(element);
                const itemCategory = $item.data('category');
                const itemName = $item.find('.uenf-gradient-name').text().toLowerCase();
                const itemDescription = $item.find('.uenf-gradient-description').text().toLowerCase();
                
                let visible = true;
                
                // Filtro por categoria
                if (category !== 'all' && itemCategory !== category) {
                    visible = false;
                }
                
                // Filtro por busca
                if (query && !itemName.includes(query) && !itemDescription.includes(query)) {
                    visible = false;
                }
                
                // Aplicar visibilidade
                if (visible) {
                    $item.removeClass('hidden');
                } else {
                    $item.addClass('hidden');
                }
            });
        },
        
        /**
         * Ordena gradientes
         */
        sortGradients: function() {
            const sortBy = this.state.sortBy;
            const $items = $('.uenf-gradient-item:not(.hidden)');
            
            const sortedItems = $items.sort((a, b) => {
                const $a = $(a);
                const $b = $(b);
                
                switch (sortBy) {
                    case 'name':
                        return $a.find('.uenf-gradient-name').text().localeCompare($b.find('.uenf-gradient-name').text());
                    case 'popularity':
                        const popA = parseInt($a.find('.uenf-gradient-popularity').text()) || 0;
                        const popB = parseInt($b.find('.uenf-gradient-popularity').text()) || 0;
                        return popB - popA;
                    case 'type':
                        return $a.find('.uenf-gradient-type').text().localeCompare($b.find('.uenf-gradient-type').text());
                    case 'category':
                        return $a.data('category').localeCompare($b.data('category'));
                    default:
                        return 0;
                }
            });
            
            // Reorganizar no DOM
            this.cache.gradientGrid.append(sortedItems);
        },
        
        /**
         * Inicializa o gerador de gradientes
         */
        initGenerator: function() {
            if (!this.settings.generatorEnabled) {
                return;
            }
            
            // Cache de elementos
            this.cache.previewBox = $('#uenf-gradient-preview');
            this.cache.cssOutput = $('#uenf-gradient-css');
            
            // Configurar controles
            this.setupGeneratorControls();
            
            // Configurar editor de cores
            this.setupColorEditor();
            
            // Configurar presets
            this.setupPresets();
            
            // Preview inicial
            this.updateGeneratorPreview();
        },
        
        /**
         * Configura controles do gerador
         */
        setupGeneratorControls: function() {
            // Tipo de gradiente
            $('#uenf-gradient-type').on('change', (e) => {
                this.state.generatorData.type = e.target.value;
                this.toggleGeneratorSettings();
                this.updateGeneratorPreview();
            });
            
            // Ângulo linear
            $('#uenf-gradient-angle').on('input', (e) => {
                this.state.generatorData.angle = parseInt(e.target.value);
                $('#uenf-angle-value').text(e.target.value + '°');
                this.updateGeneratorPreview();
            });
            
            // Botões de direção
            $('.uenf-direction-btn').on('click', (e) => {
                const angle = parseInt($(e.currentTarget).data('angle'));
                this.state.generatorData.angle = angle;
                $('#uenf-gradient-angle').val(angle);
                $('#uenf-angle-value').text(angle + '°');
                this.updateGeneratorPreview();
            });
            
            // Configurações radiais
            $('#uenf-radial-shape, #uenf-radial-position').on('change', () => {
                this.updateGeneratorPreview();
            });
            
            // Ângulo cônico
            $('#uenf-conic-angle').on('input', (e) => {
                $('#uenf-conic-value').text(e.target.value + '°');
                this.updateGeneratorPreview();
            });
        },
        
        /**
         * Alterna configurações do gerador
         */
        toggleGeneratorSettings: function() {
            const type = this.state.generatorData.type;
            
            $('.uenf-linear-settings').toggle(type === 'linear');
            $('.uenf-radial-settings').toggle(type === 'radial');
            $('.uenf-conic-settings').toggle(type === 'conic');
        },
        
        /**
         * Configura editor de cores
         */
        setupColorEditor: function() {
            // Mudança de cor
            $(document).on('input', '.uenf-color-input', (e) => {
                const $colorStop = $(e.target).closest('.uenf-color-stop');
                const position = parseInt($colorStop.data('position'));
                const color = e.target.value;
                
                // Atualizar preview da cor
                $colorStop.find('.uenf-color-preview').css('background', color);
                
                // Atualizar dados
                this.updateColorStop(position, { color });
                
                // Atualizar preview
                this.updateGeneratorPreview();
            });
            
            // Mudança de posição
            $(document).on('input', '.uenf-position-input', (e) => {
                const $colorStop = $(e.target).closest('.uenf-color-stop');
                const oldPosition = parseInt($colorStop.data('position'));
                const newPosition = parseInt(e.target.value);
                
                // Atualizar display
                $colorStop.find('.uenf-position-value').text(newPosition + '%');
                $colorStop.data('position', newPosition);
                
                // Atualizar dados
                this.updateColorStop(oldPosition, { position: newPosition });
                
                // Atualizar preview
                this.updateGeneratorPreview();
            });
            
            // Remover cor
            $(document).on('click', '.uenf-remove-color', (e) => {
                const $colorStop = $(e.target).closest('.uenf-color-stop');
                const position = parseInt($colorStop.data('position'));
                
                if ($('.uenf-color-stop').length > 2) {
                    this.removeColorStop(position);
                    $colorStop.remove();
                    this.updateGeneratorPreview();
                }
            });
            
            // Adicionar cor
            $('.uenf-add-color').on('click', () => {
                this.addColorStop();
            });
            
            // Cores aleatórias
            $('.uenf-random-colors').on('click', () => {
                this.generateRandomColors();
            });
            
            // Inverter cores
            $('.uenf-reverse-colors').on('click', () => {
                this.reverseColors();
            });
        },
        
        /**
         * Atualiza color stop
         */
        updateColorStop: function(position, updates) {
            const colorIndex = this.state.generatorData.colors.findIndex(c => c.position === position);
            
            if (colorIndex !== -1) {
                this.state.generatorData.colors[colorIndex] = {
                    ...this.state.generatorData.colors[colorIndex],
                    ...updates
                };
            }
        },
        
        /**
         * Remove color stop
         */
        removeColorStop: function(position) {
            this.state.generatorData.colors = this.state.generatorData.colors.filter(c => c.position !== position);
        },
        
        /**
         * Adiciona color stop
         */
        addColorStop: function() {
            const colors = this.state.generatorData.colors;
            const newPosition = Math.floor(Math.random() * 100);
            const newColor = this.generateRandomColor();
            
            colors.push({ color: newColor, position: newPosition });
            
            // Ordenar por posição
            colors.sort((a, b) => a.position - b.position);
            
            // Recriar UI
            this.rebuildColorStops();
            this.updateGeneratorPreview();
        },
        
        /**
         * Gera cores aleatórias
         */
        generateRandomColors: function() {
            this.state.generatorData.colors.forEach(colorStop => {
                colorStop.color = this.generateRandomColor();
            });
            
            this.rebuildColorStops();
            this.updateGeneratorPreview();
        },
        
        /**
         * Inverte cores
         */
        reverseColors: function() {
            const colors = this.state.generatorData.colors;
            
            // Inverter array
            colors.reverse();
            
            // Recalcular posições
            colors.forEach((color, index) => {
                color.position = Math.round((index / (colors.length - 1)) * 100);
            });
            
            this.rebuildColorStops();
            this.updateGeneratorPreview();
        },
        
        /**
         * Reconstrói color stops
         */
        rebuildColorStops: function() {
            const $container = $('#uenf-color-stops');
            $container.empty();
            
            this.state.generatorData.colors.forEach((colorStop, index) => {
                const $colorStopEl = this.createColorStopElement(colorStop, index);
                $container.append($colorStopEl);
            });
        },
        
        /**
         * Cria elemento de color stop
         */
        createColorStopElement: function(colorStop, index) {
            const canRemove = this.state.generatorData.colors.length > 2;
            
            return $(`
                <div class="uenf-color-stop" data-position="${colorStop.position}">
                    <div class="uenf-color-preview" style="background: ${colorStop.color};"></div>
                    <input type="color" class="uenf-color-input" value="${colorStop.color}">
                    <input type="range" class="uenf-position-input" min="0" max="100" value="${colorStop.position}">
                    <span class="uenf-position-value">${colorStop.position}%</span>
                    <button type="button" class="uenf-remove-color" ${!canRemove ? 'disabled' : ''}>×</button>
                </div>
            `);
        },
        
        /**
         * Gera cor aleatória
         */
        generateRandomColor: function() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        },
        
        /**
         * Configura presets
         */
        setupPresets: function() {
            $('.uenf-preset-btn').on('click', (e) => {
                const preset = $(e.currentTarget).data('preset');
                this.applyPreset(preset);
            });
        },
        
        /**
         * Aplica preset
         */
        applyPreset: function(presetName) {
            const presets = {
                sunset: {
                    type: 'linear',
                    angle: 45,
                    colors: [
                        { color: '#ff7e5f', position: 0 },
                        { color: '#feb47b', position: 100 }
                    ]
                },
                ocean: {
                    type: 'linear',
                    angle: 135,
                    colors: [
                        { color: '#667eea', position: 0 },
                        { color: '#764ba2', position: 100 }
                    ]
                },
                forest: {
                    type: 'linear',
                    angle: 90,
                    colors: [
                        { color: '#134e5e', position: 0 },
                        { color: '#71b280', position: 100 }
                    ]
                },
                fire: {
                    type: 'linear',
                    angle: 45,
                    colors: [
                        { color: '#f12711', position: 0 },
                        { color: '#f5af19', position: 100 }
                    ]
                },
                neon: {
                    type: 'linear',
                    angle: 45,
                    colors: [
                        { color: '#12c2e9', position: 0 },
                        { color: '#c471ed', position: 50 },
                        { color: '#f64f59', position: 100 }
                    ]
                },
                gold: {
                    type: 'linear',
                    angle: 45,
                    colors: [
                        { color: '#ffd700', position: 0 },
                        { color: '#ffed4e', position: 50 },
                        { color: '#ff9500', position: 100 }
                    ]
                }
            };
            
            if (presets[presetName]) {
                this.state.generatorData = { ...presets[presetName] };
                this.updateGeneratorUI();
                this.updateGeneratorPreview();
            }
        },
        
        /**
         * Atualiza UI do gerador
         */
        updateGeneratorUI: function() {
            const data = this.state.generatorData;
            
            // Tipo
            $('#uenf-gradient-type').val(data.type);
            this.toggleGeneratorSettings();
            
            // Ângulo
            $('#uenf-gradient-angle').val(data.angle);
            $('#uenf-angle-value').text(data.angle + '°');
            
            // Cores
            this.rebuildColorStops();
        },
        
        /**
         * Atualiza preview do gerador
         */
        updateGeneratorPreview: function() {
            const css = this.generateGradientCSS();
            
            // Atualizar preview visual
            if (this.cache.previewBox) {
                this.cache.previewBox.css('background', css);
            }
            
            // Atualizar código CSS
            if (this.cache.cssOutput) {
                this.cache.cssOutput.val(css);
            }
        },
        
        /**
         * Gera CSS do gradiente
         */
        generateGradientCSS: function() {
            const data = this.state.generatorData;
            const colorStops = data.colors
                .sort((a, b) => a.position - b.position)
                .map(c => `${c.color} ${c.position}%`)
                .join(', ');
            
            switch (data.type) {
                case 'radial':
                    const shape = $('#uenf-radial-shape').val() || 'circle';
                    const position = $('#uenf-radial-position').val() || 'center';
                    return `radial-gradient(${shape} at ${position}, ${colorStops})`;
                    
                case 'conic':
                    const conicAngle = $('#uenf-conic-angle').val() || 0;
                    return `conic-gradient(from ${conicAngle}deg at center, ${colorStops})`;
                    
                default:
                    return `linear-gradient(${data.angle}deg, ${colorStops})`;
            }
        },
        
        /**
         * Inicializa o aplicador
         */
        initApplicator: function() {
            this.setupApplicationControls();
            this.setupApplicationPreview();
        },
        
        /**
         * Configura controles de aplicação
         */
        setupApplicationControls: function() {
            // Checkboxes de aplicação
            $('.uenf-apply-to').on('change', (e) => {
                const target = $(e.target).data('target');
                const enabled = e.target.checked;
                
                this.settings[`applyTo${this.capitalize(target)}`] = enabled;
                this.updateApplicationPreview();
            });
            
            // Sliders de intensidade e opacidade
            $('.uenf-intensity-slider').on('input', (e) => {
                const value = parseFloat(e.target.value);
                $('.uenf-intensity-value').text(Math.round(value * 100) + '%');
                this.updateApplicationPreview();
            });
            
            $('.uenf-opacity-slider').on('input', (e) => {
                const value = parseFloat(e.target.value);
                $('.uenf-opacity-value').text(Math.round(value * 100) + '%');
                this.updateApplicationPreview();
            });
        },
        
        /**
         * Configura preview de aplicação
         */
        setupApplicationPreview: function() {
            this.updateApplicationPreview();
        },
        
        /**
         * Atualiza preview de aplicação
         */
        updateApplicationPreview: function() {
            const currentGradient = this.generateGradientCSS();
            const intensity = parseFloat($('.uenf-intensity-slider').val() || 1);
            const opacity = parseFloat($('.uenf-opacity-slider').val() || 1);
            
            // Aplicar aos samples
            if ($('.uenf-apply-to[data-target="backgrounds"]').is(':checked')) {
                $('.uenf-bg-sample').css({
                    'background': currentGradient,
                    'opacity': opacity
                });
            }
            
            if ($('.uenf-apply-to[data-target="buttons"]').is(':checked')) {
                $('.uenf-btn-sample').css({
                    'background': currentGradient,
                    'opacity': opacity
                });
            }
            
            if ($('.uenf-apply-to[data-target="text"]').is(':checked')) {
                $('.uenf-text-sample').css({
                    'background': currentGradient,
                    '-webkit-background-clip': 'text',
                    '-webkit-text-fill-color': 'transparent',
                    'background-clip': 'text',
                    'opacity': opacity
                });
            }
            
            if ($('.uenf-apply-to[data-target="borders"]').is(':checked')) {
                $('.uenf-border-sample').css({
                    'border-image': `${currentGradient} 1`,
                    'opacity': opacity
                });
            }
        },
        
        /**
         * Vincula eventos
         */
        bindEvents: function() {
            // Eventos do browser
            this.bindBrowserEvents();
            
            // Eventos do gerador
            this.bindGeneratorEvents();
            
            // Eventos do aplicador
            this.bindApplicationEvents();
            
            // Eventos globais
            this.bindGlobalEvents();
        },
        
        /**
         * Vincula eventos do browser
         */
        bindBrowserEvents: function() {
            // Aplicar gradiente
            $(document).on('click', '.uenf-btn-apply', (e) => {
                const gradientKey = $(e.target).data('gradient');
                this.applyGradient(gradientKey);
            });
            
            // Favoritar gradiente
            $(document).on('click', '.uenf-btn-favorite', (e) => {
                const gradientKey = $(e.target).data('gradient');
                this.toggleFavorite(gradientKey);
            });
            
            // Copiar CSS
            $(document).on('click', '.uenf-btn-copy', (e) => {
                const gradientKey = $(e.target).data('gradient');
                this.copyGradientCSS(gradientKey);
            });
        },
        
        /**
         * Vincula eventos do gerador
         */
        bindGeneratorEvents: function() {
            // Copiar CSS
            $('.uenf-copy-css').on('click', () => {
                this.copyToClipboard(this.cache.cssOutput.val());
            });
            
            // Salvar gradiente
            $('.uenf-save-gradient').on('click', () => {
                this.saveCustomGradient();
            });
            
            // Exportar gradiente
            $('.uenf-export-gradient').on('click', () => {
                this.exportGradient();
            });
            
            // Reset gerador
            $('.uenf-reset-generator').on('click', () => {
                this.resetGenerator();
            });
        },
        
        /**
         * Vincula eventos do aplicador
         */
        bindApplicationEvents: function() {
            // Aplicar gradiente
            $('.uenf-apply-gradient').on('click', () => {
                this.applyCurrentGradient();
            });
            
            // Preview ao vivo
            $('.uenf-preview-live').on('click', () => {
                this.toggleLivePreview();
            });
            
            // Reset aplicação
            $('.uenf-reset-application').on('click', () => {
                this.resetApplication();
            });
        },
        
        /**
         * Vincula eventos globais
         */
        bindGlobalEvents: function() {
            // Redimensionamento
            $(window).on('resize', this.debounce(() => {
                this.handleResize();
            }, 250));
            
            // Mudança de visibilidade
            $(document).on('visibilitychange', () => {
                this.handleVisibilityChange();
            });
        },
        
        /**
         * Aplica gradiente
         */
        applyGradient: function(gradientKey) {
            if (this.library[gradientKey]) {
                const gradient = this.library[gradientKey];
                this.settings.currentGradient = gradientKey;
                
                // Aplicar ao gerador
                this.loadGradientToGenerator(gradient);
                
                // Notificar
                this.showNotification(`Gradiente "${gradient.name}" aplicado!`, 'success');
            }
        },
        
        /**
         * Carrega gradiente no gerador
         */
        loadGradientToGenerator: function(gradient) {
            // Converter dados do gradiente
            this.state.generatorData = {
                type: gradient.type || 'linear',
                angle: this.parseAngle(gradient.direction || gradient.angle || '45deg'),
                colors: gradient.colors || [
                    { color: '#ff7e5f', position: 0 },
                    { color: '#feb47b', position: 100 }
                ]
            };
            
            // Atualizar UI
            this.updateGeneratorUI();
            this.updateGeneratorPreview();
        },
        
        /**
         * Parse ângulo
         */
        parseAngle: function(angleStr) {
            const match = angleStr.match(/\d+/);
            return match ? parseInt(match[0]) : 45;
        },
        
        /**
         * Toggle favorito
         */
        toggleFavorite: function(gradientKey) {
            const favorites = this.settings.favoriteGradients;
            const index = favorites.indexOf(gradientKey);
            
            if (index === -1) {
                favorites.push(gradientKey);
                this.showNotification('Adicionado aos favoritos!', 'success');
            } else {
                favorites.splice(index, 1);
                this.showNotification('Removido dos favoritos!', 'info');
            }
            
            // Salvar configuração
            this.saveSettings();
        },
        
        /**
         * Copia CSS do gradiente
         */
        copyGradientCSS: function(gradientKey) {
            if (this.library[gradientKey]) {
                const css = this.library[gradientKey].css;
                this.copyToClipboard(css);
                this.showNotification('CSS copiado para a área de transferência!', 'success');
            }
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
         * Salva gradiente personalizado
         */
        saveCustomGradient: function() {
            const name = prompt('Nome do gradiente:');
            
            if (name) {
                const gradientData = {
                    name: name,
                    type: this.state.generatorData.type,
                    css: this.generateGradientCSS(),
                    colors: [...this.state.generatorData.colors],
                    created: new Date().toISOString()
                };
                
                this.settings.customGradients[name] = gradientData;
                this.saveSettings();
                
                this.showNotification(`Gradiente "${name}" salvo!`, 'success');
            }
        },
        
        /**
         * Exporta gradiente
         */
        exportGradient: function() {
            const format = prompt('Formato de exportação (css, scss, json):', 'css');
            
            if (format) {
                const data = this.exportGradientData(format);
                this.downloadFile(`gradient.${format}`, data);
            }
        },
        
        /**
         * Exporta dados do gradiente
         */
        exportGradientData: function(format) {
            const css = this.generateGradientCSS();
            
            switch (format.toLowerCase()) {
                case 'scss':
                    return `$gradient: ${css};`;
                case 'json':
                    return JSON.stringify({
                        type: this.state.generatorData.type,
                        css: css,
                        colors: this.state.generatorData.colors
                    }, null, 2);
                default:
                    return `.gradient { background: ${css}; }`;
            }
        },
        
        /**
         * Download arquivo
         */
        downloadFile: function(filename, content) {
            const blob = new Blob([content], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            URL.revokeObjectURL(url);
        },
        
        /**
         * Reset gerador
         */
        resetGenerator: function() {
            this.state.generatorData = {
                type: 'linear',
                angle: 45,
                colors: [
                    { color: '#ff7e5f', position: 0 },
                    { color: '#feb47b', position: 100 }
                ]
            };
            
            this.updateGeneratorUI();
            this.updateGeneratorPreview();
        },
        
        /**
         * Aplica gradiente atual
         */
        applyCurrentGradient: function() {
            const css = this.generateGradientCSS();
            
            // Aplicar ao site (implementação específica)
            this.applyGradientToSite(css);
            
            this.showNotification('Gradiente aplicado ao site!', 'success');
        },
        
        /**
         * Aplica gradiente ao site
         */
        applyGradientToSite: function(css) {
            // Implementação específica para aplicar ao site
            // Pode enviar via AJAX para salvar nas configurações do tema
            
            if (this.settings.applyToBackgrounds) {
                $('body').css('background', css);
            }
            
            if (this.settings.applyToButtons) {
                $('.button, .btn').css('background', css);
            }
            
            // Etc...
        },
        
        /**
         * Toggle preview ao vivo
         */
        toggleLivePreview: function() {
            this.state.previewMode = !this.state.previewMode;
            
            if (this.state.previewMode) {
                this.startLivePreview();
                $('.uenf-preview-live').text('🚫 Parar Preview');
            } else {
                this.stopLivePreview();
                $('.uenf-preview-live').text('👁️ Preview ao Vivo');
            }
        },
        
        /**
         * Inicia preview ao vivo
         */
        startLivePreview: function() {
            // Implementar preview em tempo real
            this.livePreviewInterval = setInterval(() => {
                const css = this.generateGradientCSS();
                this.applyGradientToSite(css);
            }, 500);
        },
        
        /**
         * Para preview ao vivo
         */
        stopLivePreview: function() {
            if (this.livePreviewInterval) {
                clearInterval(this.livePreviewInterval);
                this.livePreviewInterval = null;
            }
        },
        
        /**
         * Reset aplicação
         */
        resetApplication: function() {
            // Reset configurações
            $('.uenf-apply-to').prop('checked', false);
            $('.uenf-apply-to[data-target="backgrounds"]').prop('checked', true);
            $('.uenf-intensity-slider').val(1);
            $('.uenf-opacity-slider').val(1);
            $('.uenf-intensity-value').text('100%');
            $('.uenf-opacity-value').text('100%');
            
            this.updateApplicationPreview();
        },
        
        /**
         * Manipula redimensionamento
         */
        handleResize: function() {
            // Ajustar layout responsivo se necessário
        },
        
        /**
         * Manipula mudança de visibilidade
         */
        handleVisibilityChange: function() {
            if (document.hidden && this.state.previewMode) {
                this.stopLivePreview();
            }
        },
        
        /**
         * Salva configurações
         */
        saveSettings: function() {
            // Implementar salvamento via AJAX
            $.post(cctGradients.ajaxUrl, {
                action: 'uenf_save_gradient_settings',
                nonce: cctGradients.nonce,
                settings: this.settings
            });
        },
        
        /**
         * Mostra notificação
         */
        showNotification: function(message, type = 'info') {
            // Criar elemento de notificação
            const $notification = $(`
                <div class="uenf-notification uenf-notification-${type}">
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
         * Capitaliza string
         */
        capitalize: function(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
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
            if (window.cctGradientsDebug) {
                console.log('[CCT Gradients]', message, data);
            }
        },
        
        /**
         * Destroy - limpa recursos
         */
        destroy: function() {
            // Parar preview ao vivo
            this.stopLivePreview();
            
            // Limpar cache
            this.cache = {
                gradientGrid: null,
                categoryTabs: null,
                searchInput: null,
                sortSelect: null,
                previewBox: null,
                cssOutput: null
            };
            
            // Resetar estado
            this.state.initialized = false;
            
            this.debug('Sistema de gradientes destruído');
        }
    };
    
    // Expor globalmente
    window.CCTGradients = CCTGradients;
    
    // Auto-inicializar se configurações estão disponíveis
    $(document).ready(function() {
        if (typeof cctGradients !== 'undefined') {
            CCTGradients.library = cctGradients.library || {};
            CCTGradients.categories = cctGradients.categories || {};
            CCTGradients.init(cctGradients.settings || {});
        }
    });
    
})(jQuery);

/**
 * CSS para notificações
 * Injetado via JavaScript
 */
(function() {
    const notificationCSS = `
        .uenf-notification {
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
        }
        
        .uenf-notification.show {
            transform: translateX(0);
        }
        
        .uenf-notification-success {
            background: #28a745;
        }
        
        .uenf-notification-info {
            background: #17a2b8;
        }
        
        .uenf-notification-warning {
            background: #ffc107;
            color: #333;
        }
        
        .uenf-notification-error {
            background: #dc3545;
        }
    `;
    
    // Injetar CSS
    const style = document.createElement('style');
    style.textContent = notificationCSS;
    document.head.appendChild(style);
})();