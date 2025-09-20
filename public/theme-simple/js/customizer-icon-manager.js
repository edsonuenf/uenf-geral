/**
 * Sistema de Ícones - JavaScript do Customizer
 * 
 * Funcionalidades:
 * - Navegação por categorias
 * - Busca e filtros de ícones
 * - Upload de ícones SVG personalizados
 * - Gerenciamento de favoritos
 * - Otimização automática
 * - Preview e códigos de exemplo
 * 
 * @package CCT_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Objeto principal do gerenciador de ícones
    var CCTIconManager = {
        
        // Configurações
        settings: {
            iconLibrary: {},
            categories: {},
            favoriteIcons: [],
            customIcons: [],
            currentCategory: 'all',
            currentView: 'all',
            searchTerm: '',
            selectedIcons: []
        },
        
        // Cache de elementos
        cache: {
            $categoryBrowser: null,
            $iconGrid: null,
            $searchInput: null,
            $categoryFilter: null,
            $viewFilter: null,
            $iconModal: null,
            $uploadArea: null,
            $fileInput: null,
            $customIconsList: null
        },
        
        /**
         * Inicializa o gerenciador
         */
        init: function() {
            this.settings.iconLibrary = cctIconManager.iconLibrary || {};
            this.settings.categories = cctIconManager.categories || {};
            
            this.cacheElements();
            this.bindEvents();
            this.loadFavorites();
            this.loadCustomIcons();
            this.initIconLibrary();
            this.initUploadArea();
            this.updateStats();
        },
        
        /**
         * Cache elementos DOM
         */
        cacheElements: function() {
            this.cache.$categoryBrowser = $('.cct-category-browser');
            this.cache.$iconGrid = $('#cct-icon-grid');
            this.cache.$searchInput = $('.cct-icon-search-input');
            this.cache.$categoryFilter = $('.cct-category-filter');
            this.cache.$viewFilter = $('.cct-view-filter');
            this.cache.$iconModal = $('#cct-icon-modal');
            this.cache.$uploadArea = $('#cct-upload-area');
            this.cache.$fileInput = $('#cct-file-input');
            this.cache.$customIconsList = $('#cct-custom-icons-list');
        },
        
        /**
         * Vincula eventos
         */
        bindEvents: function() {
            var self = this;
            
            // Navegação por categorias
            $(document).on('click', '.cct-category-item', function() {
                var category = $(this).data('category');
                self.selectCategory(category);
            });
            
            // Busca de ícones
            this.cache.$searchInput.on('input', function() {
                self.settings.searchTerm = $(this).val().toLowerCase();
                self.filterIcons();
                self.toggleSearchClear();
            });
            
            // Limpar busca
            $(document).on('click', '.cct-search-clear', function() {
                self.cache.$searchInput.val('');
                self.settings.searchTerm = '';
                self.filterIcons();
                self.toggleSearchClear();
            });
            
            // Filtros
            this.cache.$categoryFilter.on('change', function() {
                self.settings.currentCategory = $(this).val();
                self.filterIcons();
            });
            
            this.cache.$viewFilter.on('change', function() {
                self.settings.currentView = $(this).val();
                self.filterIcons();
            });
            
            // Clique em ícone
            $(document).on('click', '.cct-icon-item', function() {
                var iconName = $(this).data('icon-name');
                var iconCategory = $(this).data('icon-category');
                var isCustom = $(this).data('is-custom') || false;
                
                self.showIconModal(iconName, iconCategory, isCustom);
            });
            
            // Modal de ícone
            $(document).on('click', '.cct-modal-close, .cct-modal-backdrop', function() {
                self.hideIconModal();
            });
            
            // Ações do modal
            $(document).on('click', '.cct-copy-shortcode', function() {
                self.copyShortcode();
            });
            
            $(document).on('click', '.cct-copy-svg', function() {
                self.copySvgCode();
            });
            
            $(document).on('click', '.cct-toggle-favorite', function() {
                self.toggleFavorite();
            });
            
            // Upload de arquivos
            this.cache.$fileInput.on('change', function() {
                self.handleFileSelection(this.files);
            });
            
            // Drag & Drop
            this.cache.$uploadArea.on('dragover dragenter', function(e) {
                e.preventDefault();
                $(this).addClass('dragover');
            });
            
            this.cache.$uploadArea.on('dragleave dragend drop', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
            });
            
            this.cache.$uploadArea.on('drop', function(e) {
                e.preventDefault();
                var files = e.originalEvent.dataTransfer.files;
                self.handleFileSelection(files);
            });
            
            // Clique na área de upload
            this.cache.$uploadArea.on('click', function() {
                self.cache.$fileInput.click();
            });
            
            // Gerenciamento de ícones personalizados
            $(document).on('change', '.cct-icon-checkbox', function() {
                self.updateSelectedIcons();
            });
            
            $(document).on('click', '.cct-apply-bulk', function() {
                self.applyBulkAction();
            });
            
            $(document).on('click', '.cct-icon-action', function() {
                var action = $(this).data('action');
                var iconIndex = $(this).closest('.cct-custom-icon-item').data('icon-index');
                self.handleIconAction(action, iconIndex);
            });
            
            // Limpar filtros
            $(document).on('click', '.cct-clear-filters', function() {
                self.clearFilters();
            });
        },
        
        /**
         * Carrega favoritos
         */
        loadFavorites: function() {
            var favorites = wp.customize('cct_favorite_icons')();
            try {
                this.settings.favoriteIcons = JSON.parse(favorites || '[]');
            } catch (e) {
                this.settings.favoriteIcons = [];
            }
        },
        
        /**
         * Carrega ícones personalizados
         */
        loadCustomIcons: function() {
            var customIcons = wp.customize('cct_custom_icons_data')();
            try {
                this.settings.customIcons = JSON.parse(customIcons || '[]');
            } catch (e) {
                this.settings.customIcons = [];
            }
            
            this.renderCustomIcons();
        },
        
        /**
         * Inicializa biblioteca de ícones
         */
        initIconLibrary: function() {
            this.updateCategoryStats();
            this.renderIcons();
        },
        
        /**
         * Atualiza estatísticas das categorias
         */
        updateCategoryStats: function() {
            var self = this;
            
            Object.keys(this.settings.categories).forEach(function(categoryId) {
                var count = 0;
                if (self.settings.iconLibrary[categoryId]) {
                    count = Object.keys(self.settings.iconLibrary[categoryId]).length;
                }
                
                $('.cct-icon-count[data-category="' + categoryId + '"]').text(count);
            });
        },
        
        /**
         * Seleciona categoria
         */
        selectCategory: function(category) {
            this.settings.currentCategory = category;
            
            // Atualiza UI
            $('.cct-category-item').removeClass('active');
            $('.cct-category-item[data-category="' + category + '"]').addClass('active');
            
            this.cache.$categoryFilter.val(category);
            this.filterIcons();
        },
        
        /**
         * Renderiza ícones
         */
        renderIcons: function() {
            var self = this;
            var iconsHtml = '';
            var totalIcons = 0;
            
            // Mostra loading
            this.showLoading();
            
            // Processa cada categoria
            Object.keys(this.settings.iconLibrary).forEach(function(categoryId) {
                var categoryIcons = self.settings.iconLibrary[categoryId];
                
                Object.keys(categoryIcons).forEach(function(iconName) {
                    var iconSvg = categoryIcons[iconName];
                    var isFavorite = self.settings.favoriteIcons.includes(categoryId + ':' + iconName);
                    
                    iconsHtml += self.createIconHtml(iconName, categoryId, iconSvg, isFavorite, false);
                    totalIcons++;
                });
            });
            
            // Adiciona ícones personalizados
            this.settings.customIcons.forEach(function(customIcon, index) {
                var isFavorite = self.settings.favoriteIcons.includes('custom:' + customIcon.name);
                iconsHtml += self.createIconHtml(customIcon.name, 'custom', customIcon.svg, isFavorite, true);
                totalIcons++;
            });
            
            // Atualiza grid
            setTimeout(function() {
                self.cache.$iconGrid.html(iconsHtml);
                self.hideLoading();
                self.updateStats();
                self.filterIcons();
            }, 300);
        },
        
        /**
         * Cria HTML do ícone
         */
        createIconHtml: function(iconName, category, iconSvg, isFavorite, isCustom) {
            var favoriteClass = isFavorite ? ' favorite' : '';
            var customAttr = isCustom ? ' data-is-custom="true"' : '';
            
            return '<div class="cct-icon-item' + favoriteClass + '" ' +
                   'data-icon-name="' + iconName + '" ' +
                   'data-icon-category="' + category + '"' + customAttr + '>' +
                   iconSvg +
                   '</div>';
        },
        
        /**
         * Filtra ícones
         */
        filterIcons: function() {
            var self = this;
            var $icons = this.cache.$iconGrid.find('.cct-icon-item');
            var visibleCount = 0;
            
            $icons.each(function() {
                var $icon = $(this);
                var iconName = $icon.data('icon-name');
                var iconCategory = $icon.data('icon-category');
                var isFavorite = $icon.hasClass('favorite');
                var isCustom = $icon.data('is-custom') || false;
                
                var showIcon = true;
                
                // Filtro por categoria
                if (self.settings.currentCategory !== 'all' && iconCategory !== self.settings.currentCategory) {
                    showIcon = false;
                }
                
                // Filtro por visualização
                if (self.settings.currentView === 'favorites' && !isFavorite) {
                    showIcon = false;
                } else if (self.settings.currentView === 'custom' && !isCustom) {
                    showIcon = false;
                }
                
                // Filtro por busca
                if (self.settings.searchTerm && iconName.toLowerCase().indexOf(self.settings.searchTerm) === -1) {
                    showIcon = false;
                }
                
                if (showIcon) {
                    $icon.show();
                    visibleCount++;
                } else {
                    $icon.hide();
                }
            });
            
            // Atualiza estatísticas
            $('.cct-filtered-icons').text(visibleCount);
            
            // Mostra/esconde mensagem vazia
            if (visibleCount === 0) {
                $('.cct-empty-message').show();
            } else {
                $('.cct-empty-message').hide();
            }
        },
        
        /**
         * Mostra/esconde botão de limpar busca
         */
        toggleSearchClear: function() {
            if (this.settings.searchTerm) {
                $('.cct-search-clear').show();
            } else {
                $('.cct-search-clear').hide();
            }
        },
        
        /**
         * Limpa filtros
         */
        clearFilters: function() {
            this.cache.$searchInput.val('');
            this.cache.$categoryFilter.val('all');
            this.cache.$viewFilter.val('all');
            
            this.settings.searchTerm = '';
            this.settings.currentCategory = 'all';
            this.settings.currentView = 'all';
            
            this.toggleSearchClear();
            this.filterIcons();
        },
        
        /**
         * Mostra modal do ícone
         */
        showIconModal: function(iconName, category, isCustom) {
            var self = this;
            var iconSvg = '';
            
            // Obtém SVG do ícone
            if (isCustom) {
                var customIcon = this.settings.customIcons.find(function(icon) {
                    return icon.name === iconName;
                });
                iconSvg = customIcon ? customIcon.svg : '';
            } else {
                iconSvg = this.settings.iconLibrary[category] ? this.settings.iconLibrary[category][iconName] : '';
            }
            
            if (!iconSvg) return;
            
            // Atualiza conteúdo do modal
            var categoryName = isCustom ? 'Personalizado' : (this.settings.categories[category] ? this.settings.categories[category].name : category);
            var isFavorite = this.settings.favoriteIcons.includes((isCustom ? 'custom' : category) + ':' + iconName);
            
            $('.cct-modal-title').text(iconName);
            $('.cct-icon-display').html(iconSvg);
            $('.cct-icon-category').text('Categoria: ' + categoryName);
            $('.cct-icon-size').text('Tamanho: ' + this.getSvgSize(iconSvg));
            
            // Atualiza exemplos de código
            this.updateCodeExamples(iconName, category, isCustom);
            
            // Atualiza botão de favorito
            $('.cct-toggle-favorite')
                .text(isFavorite ? 'Remover dos Favoritos' : 'Adicionar aos Favoritos')
                .data('icon-name', iconName)
                .data('icon-category', isCustom ? 'custom' : category)
                .data('is-favorite', isFavorite);
            
            // Armazena dados do ícone atual
            this.cache.$iconModal.data({
                'icon-name': iconName,
                'icon-category': isCustom ? 'custom' : category,
                'icon-svg': iconSvg,
                'is-custom': isCustom
            });
            
            // Mostra modal
            this.cache.$iconModal.fadeIn(300);
        },
        
        /**
         * Esconde modal do ícone
         */
        hideIconModal: function() {
            this.cache.$iconModal.fadeOut(300);
        },
        
        /**
         * Atualiza exemplos de código
         */
        updateCodeExamples: function(iconName, category, isCustom) {
            var shortcode = '[cct_icon name="' + iconName + '"';
            if (!isCustom) {
                shortcode += ' category="' + category + '"';
            } else {
                shortcode += ' custom="true"';
            }
            shortcode += ']';
            
            var phpCode = "<?php echo do_shortcode('" + shortcode + "'); ?>";
            
            $('.cct-shortcode-example').text(shortcode);
            $('.cct-php-example').text(phpCode);
        },
        
        /**
         * Copia shortcode
         */
        copyShortcode: function() {
            var shortcode = $('.cct-shortcode-example').text();
            this.copyToClipboard(shortcode);
            this.showNotification(cctIconManager.strings.codeCopied, 'success');
        },
        
        /**
         * Copia código SVG
         */
        copySvgCode: function() {
            var svgCode = this.cache.$iconModal.data('icon-svg');
            $('.cct-svg-code').val(svgCode);
            this.copyToClipboard(svgCode);
            this.showNotification(cctIconManager.strings.codeCopied, 'success');
        },
        
        /**
         * Alterna favorito
         */
        toggleFavorite: function() {
            var $button = $('.cct-toggle-favorite');
            var iconName = $button.data('icon-name');
            var iconCategory = $button.data('icon-category');
            var isFavorite = $button.data('is-favorite');
            
            var favoriteKey = iconCategory + ':' + iconName;
            
            if (isFavorite) {
                // Remove dos favoritos
                var index = this.settings.favoriteIcons.indexOf(favoriteKey);
                if (index > -1) {
                    this.settings.favoriteIcons.splice(index, 1);
                }
                $button.text(cctIconManager.strings.addToFavorites).data('is-favorite', false);
            } else {
                // Adiciona aos favoritos
                this.settings.favoriteIcons.push(favoriteKey);
                $button.text(cctIconManager.strings.removeFromFavorites).data('is-favorite', true);
            }
            
            // Salva favoritos
            wp.customize('cct_favorite_icons').set(JSON.stringify(this.settings.favoriteIcons));
            
            // Atualiza UI
            this.updateIconFavoriteStatus(iconName, iconCategory, !isFavorite);
            this.updateStats();
        },
        
        /**
         * Atualiza status de favorito do ícone
         */
        updateIconFavoriteStatus: function(iconName, iconCategory, isFavorite) {
            var $icon = $('.cct-icon-item[data-icon-name="' + iconName + '"][data-icon-category="' + iconCategory + '"]');
            
            if (isFavorite) {
                $icon.addClass('favorite');
            } else {
                $icon.removeClass('favorite');
            }
        },
        
        /**
         * Inicializa área de upload
         */
        initUploadArea: function() {
            // Previne comportamento padrão do navegador
            $(document).on('dragover drop', function(e) {
                e.preventDefault();
            });
        },
        
        /**
         * Manipula seleção de arquivos
         */
        handleFileSelection: function(files) {
            var self = this;
            
            if (!files || files.length === 0) return;
            
            // Valida arquivos
            var validFiles = [];
            
            Array.from(files).forEach(function(file) {
                if (self.validateFile(file)) {
                    validFiles.push(file);
                } else {
                    self.showNotification('Arquivo inválido: ' + file.name, 'error');
                }
            });
            
            if (validFiles.length === 0) return;
            
            // Processa arquivos
            this.uploadFiles(validFiles);
        },
        
        /**
         * Valida arquivo
         */
        validateFile: function(file) {
            // Verifica tipo
            if (file.type !== 'image/svg+xml') {
                return false;
            }
            
            // Verifica tamanho (1MB)
            if (file.size > 1024 * 1024) {
                return false;
            }
            
            return true;
        },
        
        /**
         * Faz upload dos arquivos
         */
        uploadFiles: function(files) {
            var self = this;
            var totalFiles = files.length;
            var uploadedFiles = 0;
            
            // Mostra progresso
            this.showUploadProgress();
            
            files.forEach(function(file, index) {
                self.uploadSingleFile(file, function(success) {
                    uploadedFiles++;
                    
                    var progress = Math.round((uploadedFiles / totalFiles) * 100);
                    self.updateUploadProgress(progress);
                    
                    if (uploadedFiles === totalFiles) {
                        setTimeout(function() {
                            self.hideUploadProgress();
                            self.loadCustomIcons();
                            self.renderIcons();
                        }, 500);
                    }
                });
            });
        },
        
        /**
         * Faz upload de um arquivo
         */
        uploadSingleFile: function(file, callback) {
            var self = this;
            var formData = new FormData();
            
            formData.append('action', 'cct_upload_icon');
            formData.append('nonce', cctIconManager.nonce);
            formData.append('icon_file', file);
            
            $.ajax({
                url: cctIconManager.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        self.showNotification(cctIconManager.strings.uploadSuccess, 'success');
                        callback(true);
                    } else {
                        self.showNotification(response.data || cctIconManager.strings.uploadError, 'error');
                        callback(false);
                    }
                },
                error: function() {
                    self.showNotification(cctIconManager.strings.uploadError, 'error');
                    callback(false);
                }
            });
        },
        
        /**
         * Mostra progresso do upload
         */
        showUploadProgress: function() {
            $('.cct-upload-progress').show();
            this.updateUploadProgress(0);
        },
        
        /**
         * Atualiza progresso do upload
         */
        updateUploadProgress: function(percent) {
            $('.cct-progress-fill').css('width', percent + '%');
            $('.cct-progress-text').text(percent + '%');
        },
        
        /**
         * Esconde progresso do upload
         */
        hideUploadProgress: function() {
            $('.cct-upload-progress').hide();
        },
        
        /**
         * Renderiza ícones personalizados
         */
        renderCustomIcons: function() {
            var self = this;
            var html = '';
            
            if (this.settings.customIcons.length === 0) {
                $('.cct-empty-custom-icons').show();
                this.cache.$customIconsList.hide();
                return;
            }
            
            $('.cct-empty-custom-icons').hide();
            this.cache.$customIconsList.show();
            
            this.settings.customIcons.forEach(function(icon, index) {
                html += self.createCustomIconHtml(icon, index);
            });
            
            this.cache.$customIconsList.html(html);
        },
        
        /**
         * Cria HTML do ícone personalizado
         */
        createCustomIconHtml: function(icon, index) {
            var uploadDate = new Date(icon.uploaded).toLocaleDateString();
            var fileSize = this.formatFileSize(icon.size);
            
            return '<div class="cct-custom-icon-item" data-icon-index="' + index + '">' +
                   '<input type="checkbox" class="cct-icon-checkbox">' +
                   '<div class="cct-custom-icon-preview">' + icon.svg + '</div>' +
                   '<div class="cct-custom-icon-details">' +
                   '<div class="cct-icon-name">' + icon.name + '</div>' +
                   '<div class="cct-icon-meta">' + fileSize + ' • ' + uploadDate + '</div>' +
                   '</div>' +
                   '<div class="cct-custom-icon-actions">' +
                   '<button class="cct-icon-action" data-action="edit">Editar</button>' +
                   '<button class="cct-icon-action" data-action="optimize">Otimizar</button>' +
                   '<button class="cct-icon-action danger" data-action="delete">Excluir</button>' +
                   '</div>' +
                   '</div>';
        },
        
        /**
         * Atualiza ícones selecionados
         */
        updateSelectedIcons: function() {
            var selectedCount = $('.cct-icon-checkbox:checked').length;
            $('.cct-apply-bulk').prop('disabled', selectedCount === 0);
        },
        
        /**
         * Aplica ação em lote
         */
        applyBulkAction: function() {
            var action = $('.cct-bulk-select').val();
            var selectedIndexes = [];
            
            $('.cct-icon-checkbox:checked').each(function() {
                var index = $(this).closest('.cct-custom-icon-item').data('icon-index');
                selectedIndexes.push(index);
            });
            
            if (selectedIndexes.length === 0 || !action) return;
            
            switch (action) {
                case 'optimize':
                    this.optimizeSelectedIcons(selectedIndexes);
                    break;
                case 'export':
                    this.exportSelectedIcons(selectedIndexes);
                    break;
                case 'delete':
                    this.deleteSelectedIcons(selectedIndexes);
                    break;
            }
        },
        
        /**
         * Manipula ação do ícone
         */
        handleIconAction: function(action, iconIndex) {
            switch (action) {
                case 'edit':
                    this.editCustomIcon(iconIndex);
                    break;
                case 'optimize':
                    this.optimizeCustomIcon(iconIndex);
                    break;
                case 'delete':
                    this.deleteCustomIcon(iconIndex);
                    break;
            }
        },
        
        /**
         * Exclui ícone personalizado
         */
        deleteCustomIcon: function(iconIndex) {
            var self = this;
            
            if (!confirm(cctIconManager.strings.deleteConfirm)) {
                return;
            }
            
            $.ajax({
                url: cctIconManager.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'cct_delete_custom_icon',
                    nonce: cctIconManager.nonce,
                    icon_index: iconIndex
                },
                success: function(response) {
                    if (response.success) {
                        self.loadCustomIcons();
                        self.renderIcons();
                        self.showNotification('Ícone excluído com sucesso!', 'success');
                    } else {
                        self.showNotification(response.data || 'Erro ao excluir ícone.', 'error');
                    }
                },
                error: function() {
                    self.showNotification('Erro ao excluir ícone.', 'error');
                }
            });
        },
        
        /**
         * Atualiza estatísticas
         */
        updateStats: function() {
            var totalIcons = 0;
            var favoriteCount = this.settings.favoriteIcons.length;
            var customCount = this.settings.customIcons.length;
            
            // Conta ícones da biblioteca
            Object.keys(this.settings.iconLibrary).forEach(function(category) {
                totalIcons += Object.keys(this.settings.iconLibrary[category]).length;
            }.bind(this));
            
            totalIcons += customCount;
            
            $('.cct-total-icons').text(totalIcons);
            $('.cct-favorite-count').text(favoriteCount);
            $('.cct-custom-count').text(customCount);
            
            // Calcula tamanho total dos ícones personalizados
            var totalSize = this.settings.customIcons.reduce(function(sum, icon) {
                return sum + (icon.size || 0);
            }, 0);
            
            $('.cct-total-size').text(this.formatFileSize(totalSize));
        },
        
        /**
         * Mostra loading
         */
        showLoading: function() {
            $('.cct-loading').show();
            this.cache.$iconGrid.hide();
        },
        
        /**
         * Esconde loading
         */
        hideLoading: function() {
            $('.cct-loading').hide();
            this.cache.$iconGrid.show();
        },
        
        /**
         * Obtém tamanho do SVG
         */
        getSvgSize: function(svgContent) {
            return this.formatFileSize(new Blob([svgContent]).size);
        },
        
        /**
         * Formata tamanho do arquivo
         */
        formatFileSize: function(bytes) {
            if (bytes === 0) return '0 B';
            
            var k = 1024;
            var sizes = ['B', 'KB', 'MB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },
        
        /**
         * Copia texto para clipboard
         */
        copyToClipboard: function(text) {
            var $temp = $('<textarea>');
            $('body').append($temp);
            $temp.val(text).select();
            document.execCommand('copy');
            $temp.remove();
        },
        
        /**
         * Mostra notificação
         */
        showNotification: function(message, type) {
            type = type || 'info';
            
            var $notification = $('<div class="cct-notification cct-notification-' + type + '">');
            $notification.text(message);
            
            $('body').append($notification);
            
            setTimeout(function() {
                $notification.addClass('show');
            }, 100);
            
            setTimeout(function() {
                $notification.removeClass('show');
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        }
    };
    
    // CSS para notificações
    $('<style>').text(`
        .cct-notification {
            position: fixed;
            top: 32px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 4px;
            color: white;
            font-weight: 500;
            z-index: 999999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        .cct-notification.show {
            transform: translateX(0);
        }
        
        .cct-notification-success {
            background: #46b450;
        }
        
        .cct-notification-error {
            background: #dc3232;
        }
        
        .cct-notification-info {
            background: #0073aa;
        }
    `).appendTo('head');
    
    // Inicializa quando o customizer estiver pronto
    wp.customize.bind('ready', function() {
        CCTIconManager.init();
    });
    
})(jQuery);