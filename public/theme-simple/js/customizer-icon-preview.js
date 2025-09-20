/**
 * Customizer Icon Preview JavaScript
 * Preview em tempo real para configurações de ícones
 */

(function($) {
    'use strict';

    // Aguardar o carregamento do customizer
    wp.customize = wp.customize || {};

    /**
     * Objeto principal para preview de ícones
     */
    var CCTIconPreview = {
        
        /**
         * Inicialização
         */
        init: function() {
            this.bindIconSettings();
            this.bindIconLibrary();
            this.bindCustomIcons();
            this.setupIconPreview();
            console.log('CCT Icon Preview: Initialized');
        },

        /**
         * Preview para configurações de ícones
         */
        bindIconSettings: function() {
            // Tamanho dos ícones
            wp.customize('cct_icon_size', function(value) {
                value.bind(function(newval) {
                    $('.cct-icon, .icon').css('font-size', newval + 'px');
                    updateIconCSS('size', newval);
                });
            });

            // Cor dos ícones
            wp.customize('cct_icon_color', function(value) {
                value.bind(function(newval) {
                    $('.cct-icon, .icon').css('color', newval);
                    updateIconCSS('color', newval);
                });
            });

            // Cor de hover dos ícones
            wp.customize('cct_icon_hover_color', function(value) {
                value.bind(function(newval) {
                    updateIconCSS('hover-color', newval);
                });
            });

            // Espaçamento dos ícones
            wp.customize('cct_icon_spacing', function(value) {
                value.bind(function(newval) {
                    $('.cct-icon-list .cct-icon').css('margin-right', newval + 'px');
                    updateIconCSS('spacing', newval);
                });
            });

            // Estilo dos ícones
            wp.customize('cct_icon_style', function(value) {
                value.bind(function(newval) {
                    $('body').removeClass('icon-style-solid icon-style-outline icon-style-duotone')
                             .addClass('icon-style-' + newval);
                });
            });
        },

        /**
         * Preview para biblioteca de ícones
         */
        bindIconLibrary: function() {
            // Categoria de ícones selecionada
            wp.customize('cct_selected_icon_category', function(value) {
                value.bind(function(newval) {
                    filterIconsByCategory(newval);
                });
            });

            // Ícones favoritos
            wp.customize('cct_favorite_icons', function(value) {
                value.bind(function(newval) {
                    updateFavoriteIcons(newval);
                });
            });

            // Ícones recentes
            wp.customize('cct_recent_icons', function(value) {
                value.bind(function(newval) {
                    updateRecentIcons(newval);
                });
            });
        },

        /**
         * Preview para ícones personalizados
         */
        bindCustomIcons: function() {
            // Ícones SVG personalizados
            wp.customize('cct_custom_icons_data', function(value) {
                value.bind(function(newval) {
                    updateCustomIcons(newval);
                });
            });

            // Configurações de upload
            wp.customize('cct_icon_upload_settings', function(value) {
                value.bind(function(newval) {
                    updateUploadSettings(newval);
                });
            });
        },

        /**
         * Configuração do preview de ícones
         */
        setupIconPreview: function() {
            // Criar área de preview se não existir
            if (!$('.cct-icon-preview-area').length) {
                $('body').append('<div class="cct-icon-preview-area" style="display: none;"></div>');
            }

            // Adicionar estilos base
            this.addBaseStyles();
        },

        /**
         * Adiciona estilos base para ícones
         */
        addBaseStyles: function() {
            var baseCSS = `
                .cct-icon {
                    display: inline-block;
                    transition: all 0.3s ease;
                }
                
                .cct-icon-list {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                }
                
                .cct-icon-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 10px;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }
                
                .cct-icon-item:hover {
                    background-color: rgba(0, 0, 0, 0.05);
                }
                
                .cct-icon-item.selected {
                    background-color: rgba(0, 115, 170, 0.1);
                    border: 2px solid #0073aa;
                }
                
                .cct-custom-icon {
                    max-width: 100%;
                    height: auto;
                }
                
                .icon-style-solid .cct-icon {
                    font-weight: 900;
                }
                
                .icon-style-outline .cct-icon {
                    font-weight: 400;
                }
                
                .icon-style-duotone .cct-icon {
                    opacity: 0.8;
                }
            `;

            // Remove CSS anterior
            $('#cct-icon-preview-css').remove();
            
            // Adiciona novo CSS
            $('head').append('<style id="cct-icon-preview-css">' + baseCSS + '</style>');
        }
    };

    /**
     * Atualiza CSS dos ícones dinamicamente
     */
    function updateIconCSS(property, value) {
        var css = '';
        
        switch(property) {
            case 'size':
                css = `.cct-icon { font-size: ${value}px !important; }`;
                break;
            case 'color':
                css = `.cct-icon { color: ${value} !important; }`;
                break;
            case 'hover-color':
                css = `.cct-icon:hover { color: ${value} !important; }`;
                break;
            case 'spacing':
                css = `.cct-icon-list .cct-icon { margin-right: ${value}px !important; }`;
                break;
        }

        // Remove CSS anterior
        $('#cct-icon-' + property + '-css').remove();
        
        // Adiciona novo CSS
        if (css) {
            $('head').append('<style id="cct-icon-' + property + '-css">' + css + '</style>');
        }
    }

    /**
     * Filtra ícones por categoria
     */
    function filterIconsByCategory(category) {
        $('.cct-icon-item').hide();
        
        if (category === 'all') {
            $('.cct-icon-item').show();
        } else {
            $('.cct-icon-item[data-category="' + category + '"]').show();
        }
        
        // Trigger evento personalizado
        $(document).trigger('cct:iconCategoryChanged', {
            category: category,
            visibleIcons: $('.cct-icon-item:visible').length
        });
    }

    /**
     * Atualiza ícones favoritos
     */
    function updateFavoriteIcons(favorites) {
        $('.cct-icon-item').removeClass('favorite');
        
        if (favorites && favorites.length) {
            favorites.forEach(function(iconId) {
                $('.cct-icon-item[data-icon-id="' + iconId + '"]').addClass('favorite');
            });
        }
        
        // Atualizar contador de favoritos
        $('.cct-favorites-count').text(favorites ? favorites.length : 0);
    }

    /**
     * Atualiza ícones recentes
     */
    function updateRecentIcons(recent) {
        $('.cct-icon-item').removeClass('recent');
        
        if (recent && recent.length) {
            recent.forEach(function(iconId) {
                $('.cct-icon-item[data-icon-id="' + iconId + '"]').addClass('recent');
            });
        }
    }

    /**
     * Atualiza ícones personalizados
     */
    function updateCustomIcons(customIconsData) {
        var customContainer = $('.cct-custom-icons-container');
        
        if (!customContainer.length) {
            customContainer = $('<div class="cct-custom-icons-container"></div>');
            $('.cct-icon-library').append(customContainer);
        }
        
        customContainer.empty();
        
        if (customIconsData && customIconsData.length) {
            customIconsData.forEach(function(iconData) {
                var iconElement = $(`
                    <div class="cct-icon-item custom-icon" data-icon-id="${iconData.id}">
                        <img src="${iconData.url}" alt="${iconData.name}" class="cct-custom-icon">
                        <span class="icon-name">${iconData.name}</span>
                    </div>
                `);
                
                customContainer.append(iconElement);
            });
        }
    }

    /**
     * Atualiza configurações de upload
     */
    function updateUploadSettings(settings) {
        if (settings) {
            // Atualizar tamanho máximo de arquivo
            if (settings.maxFileSize) {
                $('.cct-upload-max-size').text(settings.maxFileSize);
            }
            
            // Atualizar tipos de arquivo permitidos
            if (settings.allowedTypes) {
                $('.cct-upload-allowed-types').text(settings.allowedTypes.join(', '));
            }
        }
    }

    /**
     * Manipuladores de eventos para interação com ícones
     */
    function bindIconEvents() {
        // Clique em ícone
        $(document).on('click', '.cct-icon-item', function() {
            var iconId = $(this).data('icon-id');
            var iconClass = $(this).find('.cct-icon').attr('class');
            
            // Marcar como selecionado
            $('.cct-icon-item').removeClass('selected');
            $(this).addClass('selected');
            
            // Trigger evento personalizado
            $(document).trigger('cct:iconSelected', {
                id: iconId,
                class: iconClass,
                element: this
            });
        });
        
        // Duplo clique para adicionar aos favoritos
        $(document).on('dblclick', '.cct-icon-item', function() {
            var iconId = $(this).data('icon-id');
            $(this).toggleClass('favorite');
            
            // Trigger evento personalizado
            $(document).trigger('cct:iconFavoriteToggled', {
                id: iconId,
                isFavorite: $(this).hasClass('favorite')
            });
        });
    }

    /**
     * Inicialização
     */
    $(document).ready(function() {
        // Verificar se estamos no preview do customizer
        if (typeof wp.customize !== 'undefined') {
            CCTIconPreview.init();
            bindIconEvents();
        }
    });

    // Expor objeto globalmente para outros scripts
    window.CCTIconPreview = CCTIconPreview;

})(jQuery);