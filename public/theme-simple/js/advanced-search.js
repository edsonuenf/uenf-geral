/**
 * Sistema de Busca Avançada - Frontend
 * 
 * Funcionalidades JavaScript para busca avançada
 * 
 * @package CCT_Theme
 * @subpackage Search
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Objeto principal da busca avançada
    const AdvancedSearch = {
        
        /**
         * Inicializar sistema
         */
        init: function() {
            this.bindEvents();
            this.setupFilters();
            this.highlightSearchTerms();
            this.setupInfiniteScroll();
            
            console.log('[CCT Advanced Search] Sistema inicializado');
        },
        
        /**
         * Vincular eventos
         */
        bindEvents: function() {
            // Filtros de busca
            $(document).on('change', '.search-filters select', this.applyFilters.bind(this));
            $(document).on('click', '.search-filters input[type="checkbox"]', this.applyFilters.bind(this));
            
            // Busca instantânea
            $(document).on('input', '.search-field', this.debounce(this.instantSearch.bind(this), 500));
            
            // Ordenação
            $(document).on('change', '.search-orderby', this.changeOrder.bind(this));
            
            // Limpar filtros
            $(document).on('click', '.clear-search-filters', this.clearFilters.bind(this));
        },
        
        /**
         * Configurar filtros de busca
         */
        setupFilters: function() {
            if (!$('.search-results').length) {
                return;
            }
            
            // Adicionar filtros se não existirem
            if (!$('.search-filters').length) {
                this.createFiltersHTML();
            }
            
            // Mostrar estatísticas
            this.showSearchStats();
        },
        
        /**
         * Criar HTML dos filtros
         */
        createFiltersHTML: function() {
            const filtersHTML = `
                <div class="search-filters">
                    <div class="search-filters-row">
                        <div class="filter-group">
                            <label for="search-post-type">Tipo de Conteúdo:</label>
                            <select id="search-post-type" name="post_type">
                                <option value="">Todos</option>
                                <option value="post">Posts</option>
                                <option value="page">Páginas</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="search-orderby">Ordenar por:</label>
                            <select id="search-orderby" name="orderby" class="search-orderby">
                                <option value="relevance">Relevância</option>
                                <option value="date">Data (mais recente)</option>
                                <option value="date_asc">Data (mais antigo)</option>
                                <option value="title">Título (A-Z)</option>
                                <option value="title_desc">Título (Z-A)</option>
                                <option value="modified">Última modificação</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <button type="button" class="clear-search-filters btn btn-secondary">
                                Limpar Filtros
                            </button>
                        </div>
                    </div>
                    
                    <div class="search-stats">
                        <span class="results-count"></span>
                        <span class="search-time"></span>
                    </div>
                </div>
            `;
            
            $('.search-results').before(filtersHTML);
        },
        
        /**
         * Aplicar filtros
         */
        applyFilters: function() {
            const filters = this.getActiveFilters();
            const searchTerm = this.getSearchTerm();
            
            if (!searchTerm) {
                return;
            }
            
            this.performSearch(searchTerm, filters);
        },
        
        /**
         * Obter filtros ativos
         */
        getActiveFilters: function() {
            const filters = {};
            
            // Tipo de post
            const postType = $('#search-post-type').val();
            if (postType) {
                filters.post_type = postType;
            }
            
            // Ordenação
            const orderby = $('#search-orderby').val();
            if (orderby) {
                filters.orderby = orderby;
            }
            
            return filters;
        },
        
        /**
         * Obter termo de busca
         */
        getSearchTerm: function() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('s') || '';
        },
        
        /**
         * Realizar busca
         */
        performSearch: function(searchTerm, filters = {}) {
            const startTime = Date.now();
            
            // Mostrar loading
            this.showLoading();
            
            const data = {
                action: 'cct_advanced_search',
                nonce: cctSearch.nonce,
                s: searchTerm,
                ...filters
            };
            
            $.ajax({
                url: cctSearch.ajaxurl,
                type: 'POST',
                data: data,
                success: (response) => {
                    if (response.success) {
                        this.displayResults(response.data.results);
                        this.updateStats(response.data.stats, Date.now() - startTime);
                    } else {
                        this.showError('Erro na busca: ' + response.data.message);
                    }
                },
                error: () => {
                    this.showError('Erro de conexão. Tente novamente.');
                },
                complete: () => {
                    this.hideLoading();
                }
            });
        },
        
        /**
         * Busca instantânea
         */
        instantSearch: function(event) {
            const searchTerm = $(event.target).val();
            
            if (searchTerm.length >= 3) {
                const filters = this.getActiveFilters();
                this.performSearch(searchTerm, filters);
            }
        },
        
        /**
         * Alterar ordenação
         */
        changeOrder: function(event) {
            const orderby = $(event.target).val();
            const searchTerm = this.getSearchTerm();
            
            if (searchTerm) {
                this.performSearch(searchTerm, { orderby: orderby });
            }
        },
        
        /**
         * Limpar filtros
         */
        clearFilters: function() {
            $('.search-filters select').val('');
            $('.search-filters input[type="checkbox"]').prop('checked', false);
            
            const searchTerm = this.getSearchTerm();
            if (searchTerm) {
                this.performSearch(searchTerm);
            }
        },
        
        /**
         * Destacar termos de busca
         */
        highlightSearchTerms: function() {
            const searchTerm = this.getSearchTerm();
            
            if (!searchTerm) {
                return;
            }
            
            // Destacar no título e conteúdo
            $('.search-results .entry-title, .search-results .entry-summary').each(function() {
                const $element = $(this);
                const content = $element.html();
                const highlighted = content.replace(
                    new RegExp('(' + searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi'),
                    '<mark class="search-highlight">$1</mark>'
                );
                $element.html(highlighted);
            });
        },
        
        /**
         * Mostrar estatísticas de busca
         */
        showSearchStats: function() {
            const searchTerm = this.getSearchTerm();
            const resultsCount = $('.search-results article').length;
            
            if (searchTerm) {
                $('.results-count').text(`${resultsCount} resultados para "${searchTerm}"`);
            }
        },
        
        /**
         * Atualizar estatísticas
         */
        updateStats: function(stats, searchTime) {
            $('.results-count').text(`${stats.total} resultados encontrados`);
            $('.search-time').text(`(${(searchTime / 1000).toFixed(2)}s)`);
        },
        
        /**
         * Exibir resultados
         */
        displayResults: function(results) {
            const $container = $('.search-results');
            $container.empty();
            
            if (results.length === 0) {
                $container.html('<p class="no-results">Nenhum resultado encontrado.</p>');
                return;
            }
            
            results.forEach(result => {
                const resultHTML = this.createResultHTML(result);
                $container.append(resultHTML);
            });
            
            // Destacar termos após adicionar resultados
            this.highlightSearchTerms();
        },
        
        /**
         * Criar HTML do resultado
         */
        createResultHTML: function(result) {
            const siteInfo = result.site_name ? 
                `<span class="result-site">${result.site_name}</span>` : '';
            
            return `
                <article class="search-result">
                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="${result.permalink}">${result.title}</a>
                            ${siteInfo}
                        </h2>
                        <div class="entry-meta">
                            <span class="post-type">${result.post_type}</span>
                            <span class="post-date">${result.date}</span>
                        </div>
                    </header>
                    <div class="entry-summary">
                        ${result.excerpt}
                    </div>
                </article>
            `;
        },
        
        /**
         * Configurar scroll infinito
         */
        setupInfiniteScroll: function() {
            if (!$('.search-results').length) {
                return;
            }
            
            let loading = false;
            let page = 2;
            
            $(window).on('scroll', () => {
                if (loading) return;
                
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();
                
                if (scrollTop + windowHeight >= documentHeight - 1000) {
                    loading = true;
                    this.loadMoreResults(page++).then(() => {
                        loading = false;
                    });
                }
            });
        },
        
        /**
         * Carregar mais resultados
         */
        loadMoreResults: function(page) {
            return new Promise((resolve) => {
                const searchTerm = this.getSearchTerm();
                const filters = this.getActiveFilters();
                
                const data = {
                    action: 'cct_load_more_search',
                    nonce: cctSearch.nonce,
                    s: searchTerm,
                    page: page,
                    ...filters
                };
                
                $.ajax({
                    url: cctSearch.ajaxurl,
                    type: 'POST',
                    data: data,
                    success: (response) => {
                        if (response.success && response.data.results.length > 0) {
                            response.data.results.forEach(result => {
                                const resultHTML = this.createResultHTML(result);
                                $('.search-results').append(resultHTML);
                            });
                            this.highlightSearchTerms();
                        }
                        resolve();
                    },
                    error: () => {
                        resolve();
                    }
                });
            });
        },
        
        /**
         * Mostrar loading
         */
        showLoading: function() {
            if (!$('.search-loading').length) {
                $('.search-results').before('<div class="search-loading">Buscando...</div>');
            }
        },
        
        /**
         * Ocultar loading
         */
        hideLoading: function() {
            $('.search-loading').remove();
        },
        
        /**
         * Mostrar erro
         */
        showError: function(message) {
            $('.search-results').html(`<div class="search-error">${message}</div>`);
        },
        
        /**
         * Debounce function
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
        }
    };
    
    // Inicializar quando documento estiver pronto
    $(document).ready(function() {
        AdvancedSearch.init();
    });
    
    // Expor para uso global
    window.CCTAdvancedSearch = AdvancedSearch;
    
})(jQuery);