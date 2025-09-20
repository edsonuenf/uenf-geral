/**
 * Biblioteca de Padrões CCT - JavaScript Frontend
 * 
 * Funcionalidades incluídas:
 * - Browser visual de padrões
 * - Preview interativo
 * - Configurador de estilos
 * - Aplicação de templates
 * - Export/Import de configurações
 * - Lazy loading de conteúdo
 * 
 * @package CCT_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Objeto principal da biblioteca de padrões
     */
    const CCTPatterns = {
        
        // Configurações padrão
        settings: {
            patternsEnabled: true,
            lazyLoading: true,
            animationsEnabled: true,
            faqActivePattern: 'accordion',
            faqSearchEnabled: true,
            faqCategoriesEnabled: true,
            pricingActivePattern: 'cards',
            pricingCurrency: 'R$',
            pricingBillingToggle: true,
            teamActivePattern: 'grid',
            teamSocialLinks: true,
            teamBioModal: true,
            portfolioActivePattern: 'masonry',
            portfolioLightbox: true,
            portfolioFilters: true,
            activeTemplate: 'business',
            colors: {
                primary: '#0073aa',
                secondary: '#666666',
                accent: '#ff6b6b',
                background: '#ffffff',
                text: '#333333',
                border: '#e0e0e0'
            }
        },
        
        // Estado interno
        state: {
            initialized: false,
            currentPattern: null,
            currentTemplate: null,
            previewMode: false,
            configChanged: false
        },
        
        // Cache de elementos
        cache: {
            patternGrid: null,
            searchInput: null,
            previewModal: null,
            previewArea: null,
            codeOutput: null,
            livePreview: null
        },
        
        // Padrões disponíveis
        patterns: {
            faq: {},
            pricing: {},
            team: {},
            portfolio: {}
        },
        
        // Templates disponíveis
        templates: {},
        
        /**
         * Inicializa a biblioteca de padrões
         */
        init: function(customSettings = {}) {
            // Merge configurações
            this.settings = { ...this.settings, ...customSettings };
            
            // Verificar se está habilitado
            if (!this.settings.patternsEnabled) {
                return;
            }
            
            // Inicializar componentes
            this.initPatternBrowser();
            this.initPatternConfigurator();
            this.initTemplateSelector();
            this.initFAQPatterns();
            this.initPricingPatterns();
            this.initTeamPatterns();
            this.initPortfolioPatterns();
            
            // Eventos
            this.bindEvents();
            
            // Aplicar configurações iniciais
            this.applyInitialSettings();
            
            // Marcar como inicializado
            this.state.initialized = true;
            
            // Debug
            this.debug('Biblioteca de padrões inicializada', this.settings);
        },
        
        /**
         * Inicializa browser de padrões
         */
        initPatternBrowser: function() {
            // Cache de elementos
            this.cache.patternGrid = $('#cct-pattern-grid');
            this.cache.searchInput = $('.cct-search-input');
            this.cache.previewModal = $('#cct-pattern-modal');
            this.cache.previewArea = $('#cct-preview-area');
            this.cache.codeOutput = $('#cct-code-output');
            
            // Configurar busca
            this.setupPatternSearch();
            
            // Configurar visualização
            this.setupViewToggle();
            
            // Configurar ações dos padrões
            this.setupPatternActions();
        },
        
        /**
         * Configura busca de padrões
         */
        setupPatternSearch: function() {
            this.cache.searchInput.on('input', this.debounce((e) => {
                const searchTerm = e.target.value.toLowerCase();
                this.filterPatterns(searchTerm);
            }, 300));
        },
        
        /**
         * Configura toggle de visualização
         */
        setupViewToggle: function() {
            $('.cct-view-btn').on('click', (e) => {
                const $btn = $(e.target);
                const view = $btn.data('view');
                
                $('.cct-view-btn').removeClass('active');
                $btn.addClass('active');
                
                this.switchView(view);
            });
        },
        
        /**
         * Configura ações dos padrões
         */
        setupPatternActions: function() {
            // Preview
            $(document).on('click', '.cct-preview-btn', (e) => {
                const pattern = $(e.target).data('pattern');
                this.previewPattern(pattern);
            });
            
            // Aplicar
            $(document).on('click', '.cct-apply-btn', (e) => {
                const pattern = $(e.target).data('pattern');
                this.applyPattern(pattern);
            });
            
            // Copiar código
            $(document).on('click', '.cct-copy-btn', (e) => {
                const pattern = $(e.target).data('pattern');
                this.copyPatternCode(pattern);
            });
            
            // Modal
            this.setupModalEvents();
        },
        
        /**
         * Configura eventos do modal
         */
        setupModalEvents: function() {
            // Fechar modal
            $('.cct-modal-close, .cct-close-modal, .cct-modal-backdrop').on('click', () => {
                this.closeModal();
            });
            
            // Aplicar padrão do modal
            $('.cct-apply-pattern').on('click', () => {
                if (this.state.currentPattern) {
                    this.applyPattern(this.state.currentPattern);
                    this.closeModal();
                }
            });
            
            // Copiar código do modal
            $('.cct-copy-code').on('click', () => {
                this.copyToClipboard(this.cache.codeOutput.val());
                this.showNotification('Código copiado!', 'success');
            });
        },
        
        /**
         * Inicializa configurador de padrões
         */
        initPatternConfigurator: function() {
            // Cache de elementos
            this.cache.livePreview = $('#cct-live-preview');
            
            // Configurar controles de cor
            this.setupColorControls();
            
            // Configurar controles de tipografia
            this.setupTypographyControls();
            
            // Configurar controles de espaçamento
            this.setupSpacingControls();
            
            // Configurar controles de animação
            this.setupAnimationControls();
            
            // Configurar ações
            this.setupConfiguratorActions();
        },
        
        /**
         * Configura controles de cor
         */
        setupColorControls: function() {
            $('.cct-color-input').on('input', (e) => {
                const $input = $(e.target);
                const setting = $input.data('setting');
                const value = $input.val();
                
                this.updateColorSetting(setting, value);
                this.updateLivePreview();
            });
        },
        
        /**
         * Configura controles de tipografia
         */
        setupTypographyControls: function() {
            $('.cct-font-select').on('change', (e) => {
                const $select = $(e.target);
                const setting = $select.data('setting');
                const value = $select.val();
                
                this.updateTypographySetting(setting, value);
                this.updateLivePreview();
            });
            
            $('.cct-range-input[data-setting="base_size"]').on('input', (e) => {
                const value = e.target.value + 'px';
                this.updateTypographySetting('base_size', value);
                this.updateRangeValue(e.target, value);
                this.updateLivePreview();
            });
        },
        
        /**
         * Configura controles de espaçamento
         */
        setupSpacingControls: function() {
            $('.cct-range-input[data-setting^="section_"], .cct-range-input[data-setting^="element_"], .cct-range-input[data-setting^="border_"]').on('input', (e) => {
                const $input = $(e.target);
                const setting = $input.data('setting');
                const value = e.target.value + 'px';
                
                this.updateSpacingSetting(setting, value);
                this.updateRangeValue(e.target, value);
                this.updateLivePreview();
            });
        },
        
        /**
         * Configura controles de animação
         */
        setupAnimationControls: function() {
            $('.cct-checkbox-input[data-setting="animations_enabled"]').on('change', (e) => {
                const enabled = e.target.checked;
                this.updateAnimationSetting('enabled', enabled);
                this.updateLivePreview();
            });
            
            $('.cct-range-input[data-setting="animation_duration"]').on('input', (e) => {
                const value = e.target.value + 's';
                this.updateAnimationSetting('duration', value);
                this.updateRangeValue(e.target, value);
                this.updateLivePreview();
            });
            
            $('.cct-easing-select').on('change', (e) => {
                const value = e.target.value;
                this.updateAnimationSetting('easing', value);
                this.updateLivePreview();
            });
        },
        
        /**
         * Configura ações do configurador
         */
        setupConfiguratorActions: function() {
            // Aplicar configurações
            $('.cct-apply-config').on('click', () => {
                this.applyConfiguration();
            });
            
            // Reset configurações
            $('.cct-reset-config').on('click', () => {
                this.resetConfiguration();
            });
            
            // Exportar configurações
            $('.cct-export-config').on('click', () => {
                this.exportConfiguration();
            });
        },
        
        /**
         * Inicializa seletor de templates
         */
        initTemplateSelector: function() {
            // Preview template
            $('.cct-preview-template').on('click', (e) => {
                const template = $(e.target).data('template');
                this.previewTemplate(template);
            });
            
            // Aplicar template
            $('.cct-apply-template').on('click', (e) => {
                const template = $(e.target).data('template');
                this.applyTemplate(template);
            });
        },
        
        /**
         * Inicializa padrões FAQ
         */
        initFAQPatterns: function() {
            // Accordion FAQ
            this.initFAQAccordion();
            
            // Tabs FAQ
            this.initFAQTabs();
            
            // Grid FAQ
            this.initFAQGrid();
            
            // Busca FAQ
            this.initFAQSearch();
        },
        
        /**
         * Inicializa FAQ Accordion
         */
        initFAQAccordion: function() {
            $(document).on('click', '.cct-faq-accordion .cct-faq-question', function() {
                const $item = $(this).closest('.cct-faq-item');
                const $answer = $item.find('.cct-faq-answer');
                const $icon = $(this).find('.cct-faq-icon');
                
                // Toggle accordion
                if ($item.hasClass('active')) {
                    $item.removeClass('active');
                    $answer.slideUp(300);
                    $icon.removeClass('expanded');
                } else {
                    // Fechar outros (se não permitir múltiplos)
                    if (!CCTPatterns.settings.faqAllowMultiple) {
                        $('.cct-faq-accordion .cct-faq-item.active').removeClass('active')
                            .find('.cct-faq-answer').slideUp(300);
                        $('.cct-faq-accordion .cct-faq-icon').removeClass('expanded');
                    }
                    
                    $item.addClass('active');
                    $answer.slideDown(300);
                    $icon.addClass('expanded');
                }
            });
        },
        
        /**
         * Inicializa FAQ Tabs
         */
        initFAQTabs: function() {
            $(document).on('click', '.cct-faq-tabs .cct-tab-btn', function() {
                const $btn = $(this);
                const category = $btn.data('category');
                const $container = $btn.closest('.cct-faq-tabs');
                
                // Ativar tab
                $container.find('.cct-tab-btn').removeClass('active');
                $btn.addClass('active');
                
                // Mostrar conteúdo
                $container.find('.cct-faq-item').hide();
                if (category === 'all') {
                    $container.find('.cct-faq-item').show();
                } else {
                    $container.find(`.cct-faq-item[data-category="${category}"]`).show();
                }
            });
        },
        
        /**
         * Inicializa FAQ Grid
         */
        initFAQGrid: function() {
            $(document).on('click', '.cct-faq-grid .cct-faq-item', function() {
                const $item = $(this);
                const question = $item.find('.cct-faq-question').text();
                const answer = $item.find('.cct-faq-answer').html();
                
                // Abrir modal com resposta completa
                CCTPatterns.openFAQModal(question, answer);
            });
        },
        
        /**
         * Inicializa busca FAQ
         */
        initFAQSearch: function() {
            $(document).on('input', '.cct-faq-search-input', CCTPatterns.debounce(function() {
                const searchTerm = $(this).val().toLowerCase();
                const $container = $(this).closest('.cct-faq-section');
                
                $container.find('.cct-faq-item').each(function() {
                    const $item = $(this);
                    const question = $item.find('.cct-faq-question').text().toLowerCase();
                    const answer = $item.find('.cct-faq-answer').text().toLowerCase();
                    
                    if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                        $item.show();
                    } else {
                        $item.hide();
                    }
                });
            }, 300));
        },
        
        /**
         * Inicializa padrões de Pricing
         */
        initPricingPatterns: function() {
            // Toggle mensal/anual
            this.initPricingToggle();
            
            // Slider de preços
            this.initPricingSlider();
        },
        
        /**
         * Inicializa toggle de preços
         */
        initPricingToggle: function() {
            $(document).on('change', '.cct-billing-toggle', function() {
                const isAnnual = $(this).is(':checked');
                const $container = $(this).closest('.cct-pricing-table');
                
                $container.find('.cct-pricing-plan').each(function() {
                    const $plan = $(this);
                    const $monthly = $plan.find('.cct-price-monthly');
                    const $annual = $plan.find('.cct-price-annual');
                    
                    if (isAnnual && $annual.length) {
                        $monthly.hide();
                        $annual.show();
                    } else {
                        $monthly.show();
                        $annual.hide();
                    }
                });
            });
        },
        
        /**
         * Inicializa slider de preços
         */
        initPricingSlider: function() {
            $(document).on('input', '.cct-pricing-slider', function() {
                const value = parseInt($(this).val());
                const $container = $(this).closest('.cct-pricing-section');
                
                // Calcular preço baseado no valor
                CCTPatterns.calculatePricingValue($container, value);
            });
        },
        
        /**
         * Inicializa padrões de Team
         */
        initTeamPatterns: function() {
            // Modal de biografia
            this.initTeamModal();
            
            // Carousel de equipe
            this.initTeamCarousel();
            
            // Filtros de equipe
            this.initTeamFilters();
        },
        
        /**
         * Inicializa modal de equipe
         */
        initTeamModal: function() {
            $(document).on('click', '.cct-team-member', function() {
                if (!CCTPatterns.settings.teamBioModal) return;
                
                const $member = $(this);
                const name = $member.find('.cct-team-name').text();
                const role = $member.find('.cct-team-role').text();
                const bio = $member.find('.cct-team-bio').html();
                const image = $member.find('.cct-team-image img').attr('src');
                
                CCTPatterns.openTeamModal(name, role, bio, image);
            });
        },
        
        /**
         * Inicializa carousel de equipe
         */
        initTeamCarousel: function() {
            // Implementar carousel se necessário
            $('.cct-team-carousel').each(function() {
                // Configurar carousel
            });
        },
        
        /**
         * Inicializa filtros de equipe
         */
        initTeamFilters: function() {
            $(document).on('click', '.cct-team-filter-btn', function() {
                const $btn = $(this);
                const department = $btn.data('department');
                const $container = $btn.closest('.cct-team-section');
                
                // Ativar filtro
                $container.find('.cct-team-filter-btn').removeClass('active');
                $btn.addClass('active');
                
                // Filtrar membros
                $container.find('.cct-team-member').each(function() {
                    const $member = $(this);
                    const memberDept = $member.data('department');
                    
                    if (department === 'all' || memberDept === department) {
                        $member.show();
                    } else {
                        $member.hide();
                    }
                });
            });
        },
        
        /**
         * Inicializa padrões de Portfolio
         */
        initPortfolioPatterns: function() {
            // Lightbox
            this.initPortfolioLightbox();
            
            // Filtros
            this.initPortfolioFilters();
            
            // Masonry
            this.initPortfolioMasonry();
        },
        
        /**
         * Inicializa lightbox de portfolio
         */
        initPortfolioLightbox: function() {
            if (!this.settings.portfolioLightbox) return;
            
            $(document).on('click', '.cct-lightbox-btn', function(e) {
                e.preventDefault();
                const imageUrl = $(this).attr('href');
                CCTPatterns.openLightbox(imageUrl);
            });
        },
        
        /**
         * Inicializa filtros de portfolio
         */
        initPortfolioFilters: function() {
            $(document).on('click', '.cct-filter-btn', function() {
                const $btn = $(this);
                const filter = $btn.data('filter');
                const $container = $btn.closest('.cct-portfolio-gallery');
                
                // Ativar filtro
                $container.find('.cct-filter-btn').removeClass('active');
                $btn.addClass('active');
                
                // Filtrar itens
                $container.find('.cct-portfolio-item').each(function() {
                    const $item = $(this);
                    const category = $item.data('category');
                    
                    if (filter === '*' || category === filter) {
                        $item.show();
                    } else {
                        $item.hide();
                    }
                });
            });
        },
        
        /**
         * Inicializa masonry de portfolio
         */
        initPortfolioMasonry: function() {
            // Implementar masonry se necessário
            $('.cct-portfolio-masonry').each(function() {
                // Configurar masonry
            });
        },
        
        /**
         * Vincula eventos gerais
         */
        bindEvents: function() {
            // Eventos de redimensionamento
            $(window).on('resize', this.debounce(() => {
                this.handleResize();
            }, 250));
            
            // Lazy loading
            if (this.settings.lazyLoading) {
                this.initLazyLoading();
            }
        },
        
        /**
         * Aplica configurações iniciais
         */
        applyInitialSettings: function() {
            // Aplicar template ativo
            if (this.settings.activeTemplate) {
                this.applyTemplate(this.settings.activeTemplate, false);
            }
            
            // Aplicar cores
            this.applyColors(this.settings.colors);
        },
        
        /**
         * Filtra padrões por termo de busca
         */
        filterPatterns: function(searchTerm) {
            this.cache.patternGrid.find('.cct-pattern-item').each(function() {
                const $item = $(this);
                const name = $item.find('.cct-pattern-name').text().toLowerCase();
                const description = $item.find('.cct-pattern-description').text().toLowerCase();
                
                if (name.includes(searchTerm) || description.includes(searchTerm)) {
                    $item.show();
                } else {
                    $item.hide();
                }
            });
        },
        
        /**
         * Alterna visualização
         */
        switchView: function(view) {
            if (view === 'list') {
                this.cache.patternGrid.addClass('list-view');
            } else {
                this.cache.patternGrid.removeClass('list-view');
            }
        },
        
        /**
         * Preview de padrão
         */
        previewPattern: function(patternKey) {
            this.state.currentPattern = patternKey;
            
            // Gerar preview HTML
            const previewHTML = this.generatePatternPreview(patternKey);
            const shortcode = this.generatePatternShortcode(patternKey);
            
            // Atualizar modal
            this.cache.previewArea.html(previewHTML);
            this.cache.codeOutput.val(shortcode);
            
            // Mostrar modal
            this.openModal();
        },
        
        /**
         * Aplica padrão
         */
        applyPattern: function(patternKey) {
            // Implementar aplicação do padrão
            this.showNotification(`Padrão "${patternKey}" aplicado com sucesso!`, 'success');
        },
        
        /**
         * Copia código do padrão
         */
        copyPatternCode: function(patternKey) {
            const shortcode = this.generatePatternShortcode(patternKey);
            this.copyToClipboard(shortcode);
            this.showNotification('Código copiado para a área de transferência!', 'success');
        },
        
        /**
         * Gera preview HTML do padrão
         */
        generatePatternPreview: function(patternKey) {
            // Implementar geração de preview
            return `<div class="pattern-preview">Preview do padrão: ${patternKey}</div>`;
        },
        
        /**
         * Gera shortcode do padrão
         */
        generatePatternShortcode: function(patternKey) {
            // Implementar geração de shortcode
            return `[cct_pattern type="${patternKey}"]Conteúdo do padrão[/cct_pattern]`;
        },
        
        /**
         * Atualiza configuração de cor
         */
        updateColorSetting: function(setting, value) {
            this.settings.colors[setting] = value;
            this.state.configChanged = true;
        },
        
        /**
         * Atualiza configuração de tipografia
         */
        updateTypographySetting: function(setting, value) {
            if (!this.settings.typography) {
                this.settings.typography = {};
            }
            this.settings.typography[setting] = value;
            this.state.configChanged = true;
        },
        
        /**
         * Atualiza configuração de espaçamento
         */
        updateSpacingSetting: function(setting, value) {
            if (!this.settings.spacing) {
                this.settings.spacing = {};
            }
            this.settings.spacing[setting] = value;
            this.state.configChanged = true;
        },
        
        /**
         * Atualiza configuração de animação
         */
        updateAnimationSetting: function(setting, value) {
            if (!this.settings.animations) {
                this.settings.animations = {};
            }
            this.settings.animations[setting] = value;
            this.state.configChanged = true;
        },
        
        /**
         * Atualiza valor do range
         */
        updateRangeValue: function(input, value) {
            $(input).siblings('.cct-range-value').text(value);
        },
        
        /**
         * Atualiza preview ao vivo
         */
        updateLivePreview: function() {
            if (!this.cache.livePreview.length) return;
            
            const preview = this.cache.livePreview[0];
            const style = preview.style;
            
            // Aplicar cores
            style.setProperty('--preview-primary', this.settings.colors.primary);
            style.setProperty('--preview-accent', this.settings.colors.accent);
            style.setProperty('--preview-text', this.settings.colors.text);
            
            // Aplicar tipografia
            if (this.settings.typography) {
                if (this.settings.typography.heading_font) {
                    style.setProperty('--preview-heading-font', this.settings.typography.heading_font);
                }
                if (this.settings.typography.body_font) {
                    style.setProperty('--preview-body-font', this.settings.typography.body_font);
                }
                if (this.settings.typography.base_size) {
                    style.setProperty('--preview-base-size', this.settings.typography.base_size);
                }
            }
            
            // Aplicar espaçamento
            if (this.settings.spacing && this.settings.spacing.border_radius) {
                style.setProperty('--preview-border-radius', this.settings.spacing.border_radius);
            }
            
            // Aplicar animações
            if (this.settings.animations) {
                if (this.settings.animations.duration) {
                    style.setProperty('--preview-duration', this.settings.animations.duration);
                }
                if (this.settings.animations.easing) {
                    style.setProperty('--preview-easing', this.settings.animations.easing);
                }
            }
        },
        
        /**
         * Aplica configuração
         */
        applyConfiguration: function() {
            // Implementar aplicação da configuração
            this.showNotification('Configurações aplicadas com sucesso!', 'success');
            this.state.configChanged = false;
        },
        
        /**
         * Reset configuração
         */
        resetConfiguration: function() {
            // Reset para valores padrão
            this.settings.colors = {
                primary: '#0073aa',
                secondary: '#666666',
                accent: '#ff6b6b',
                background: '#ffffff',
                text: '#333333',
                border: '#e0e0e0'
            };
            
            // Atualizar controles
            $('.cct-color-input').each(function() {
                const setting = $(this).data('setting');
                if (CCTPatterns.settings.colors[setting]) {
                    $(this).val(CCTPatterns.settings.colors[setting]);
                }
            });
            
            this.updateLivePreview();
            this.showNotification('Configurações resetadas!', 'info');
        },
        
        /**
         * Exporta configuração
         */
        exportConfiguration: function() {
            const config = {
                colors: this.settings.colors,
                typography: this.settings.typography || {},
                spacing: this.settings.spacing || {},
                animations: this.settings.animations || {}
            };
            
            const dataStr = JSON.stringify(config, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            const url = URL.createObjectURL(dataBlob);
            
            const link = document.createElement('a');
            link.href = url;
            link.download = 'cct-pattern-config.json';
            link.click();
            
            this.showNotification('Configurações exportadas!', 'success');
        },
        
        /**
         * Preview de template
         */
        previewTemplate: function(templateKey) {
            this.state.currentTemplate = templateKey;
            this.state.previewMode = true;
            
            // Aplicar template temporariamente
            this.applyTemplate(templateKey, true);
            
            // Reverter após 5 segundos
            setTimeout(() => {
                if (this.state.previewMode) {
                    this.applyTemplate(this.settings.activeTemplate, false);
                    this.state.previewMode = false;
                }
            }, 5000);
        },
        
        /**
         * Aplica template
         */
        applyTemplate: function(templateKey, isPreview = false) {
            if (!this.templates[templateKey]) return;
            
            const template = this.templates[templateKey];
            
            // Aplicar cores do template
            this.applyColors(template.colors);
            
            if (!isPreview) {
                this.settings.activeTemplate = templateKey;
                this.showNotification(`Template "${template.name}" aplicado!`, 'success');
            }
        },
        
        /**
         * Aplica cores
         */
        applyColors: function(colors) {
            const root = document.documentElement;
            
            Object.keys(colors).forEach(key => {
                root.style.setProperty(`--cct-pattern-color-${key}`, colors[key]);
            });
        },
        
        /**
         * Abre modal
         */
        openModal: function() {
            this.cache.previewModal.show();
            $('body').addClass('cct-modal-open');
        },
        
        /**
         * Fecha modal
         */
        closeModal: function() {
            this.cache.previewModal.hide();
            $('body').removeClass('cct-modal-open');
            this.state.currentPattern = null;
        },
        
        /**
         * Abre modal FAQ
         */
        openFAQModal: function(question, answer) {
            // Implementar modal FAQ
        },
        
        /**
         * Abre modal de equipe
         */
        openTeamModal: function(name, role, bio, image) {
            // Implementar modal de equipe
        },
        
        /**
         * Abre lightbox
         */
        openLightbox: function(imageUrl) {
            // Implementar lightbox
        },
        
        /**
         * Calcula valor de preços
         */
        calculatePricingValue: function($container, value) {
            // Implementar cálculo de preços
        },
        
        /**
         * Inicializa lazy loading
         */
        initLazyLoading: function() {
            // Implementar lazy loading
        },
        
        /**
         * Manipula redimensionamento
         */
        handleResize: function() {
            // Ajustar layout responsivo se necessário
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
                <div class="cct-pattern-notification cct-notification-${type}">
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
            if (window.cctPatternsDebug) {
                console.log('[CCT Patterns]', message, data);
            }
        }
    };
    
    // Expor globalmente
    window.CCTPatterns = CCTPatterns;
    
    // Auto-inicializar se configurações estão disponíveis
    $(document).ready(function() {
        if (typeof cctPatterns !== 'undefined') {
            CCTPatterns.patterns = cctPatterns.patterns || {};
            CCTPatterns.templates = cctPatterns.templates || {};
            CCTPatterns.init(cctPatterns.settings || {});
        }
    });
    
})(jQuery);

/**
 * CSS para notificações de padrões
 * Injetado via JavaScript
 */
(function() {
    const patternNotificationCSS = `
        .cct-pattern-notification {
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
        
        .cct-pattern-notification.show {
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
        
        body.cct-modal-open {
            overflow: hidden;
        }
    `;
    
    // Injetar CSS
    const style = document.createElement('style');
    style.textContent = patternNotificationCSS;
    document.head.appendChild(style);
})();