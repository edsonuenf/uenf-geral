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
            wp.customize('uenf_icon_size', function(value) {
                value.bind(function(newval) {
                    $('.uenf-icon, .icon').css('font-size', newval + 'px');
                    updateIconCSS('size', newval);
                });
            });

            // Cor dos ícones
            wp.customize('uenf_icon_color', function(value) {
                value.bind(function(newval) {
                    $('.uenf-icon, .icon').css('color', newval);
                    updateIconCSS('color', newval);
                });
            });

            // Cor de hover dos ícones
            wp.customize('uenf_icon_hover_color', function(value) {
                value.bind(function(newval) {
                    updateIconCSS('hover-color', newval);
                });
            });

            // Espaçamento dos ícones
            wp.customize('uenf_icon_spacing', function(value) {
                value.bind(function(newval) {
                    $('.uenf-icon-list .uenf-icon').css('margin-right', newval + 'px');
                    updateIconCSS('spacing', newval);
                });
            });

            // Estilo dos ícones
            wp.customize('uenf_icon_style', function(value) {
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
            wp.customize('uenf_selected_icon_category', function(value) {
                value.bind(function(newval) {
                    filterIconsByCategory(newval);
                });
            });

            // Ícones favoritos
            wp.customize('uenf_favorite_icons', function(value) {
                value.bind(function(newval) {
                    updateFavoriteIcons(newval);
                });
            });

            // Ícones recentes
            wp.customize('uenf_recent_icons', function(value) {
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
            wp.customize('uenf_custom_icons_data', function(value) {
                value.bind(function(newval) {
                    updateCustomIcons(newval);
                });
            });

            // Configurações de upload
            wp.customize('uenf_icon_upload_settings', function(value) {
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
            if (!$('.uenf-icon-preview-area').length) {
                $('body').append('<div class="uenf-icon-preview-area" style="display: none;"></div>');
            }

            // Adicionar estilos base
            this.addBaseStyles();
        },

        /**
         * Adiciona estilos base para ícones
         */
        addBaseStyles: function() {
            var baseCSS = `
                .uenf-icon {
                    display: inline-block;
                    transition: all 0.3s ease;
                }
                
                .uenf-icon-list {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                }
                
                .uenf-icon-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 10px;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }
                
                .uenf-icon-item:hover {
                    background-color: rgba(0, 0, 0, 0.05);
                }
                
                .uenf-icon-item.selected {
                    background-color: rgba(0, 115, 170, 0.1);
                    border: 2px solid #0073aa;
                }
                
                .uenf-custom-icon {
                    max-width: 100%;
                    height: auto;
                }
                
                .icon-style-solid .uenf-icon {
                    font-weight: 900;
                }
                
                .icon-style-outline .uenf-icon {
                    font-weight: 400;
                }
                
                .icon-style-duotone .uenf-icon {
                    opacity: 0.8;
                }
            `;

            // Remove CSS anterior
            $('#uenf-icon-preview-css').remove();
            
            // Adiciona novo CSS
            $('head').append('<style id="uenf-icon-preview-css">' + baseCSS + '</style>');
        }
    };

    /**
     * Atualiza CSS dos ícones dinamicamente
     */
    function updateIconCSS(property, value) {
        var css = '';
        
        switch(property) {
            case 'size':
                css = `.uenf-icon { font-size: ${value}px !important; }`;
                break;
            case 'color':
                css = `.uenf-icon { color: ${value} !important; }`;
                break;
            case 'hover-color':
                css = `.uenf-icon:hover { color: ${value} !important; }`;
                break;
            case 'spacing':
                css = `.uenf-icon-list .uenf-icon { margin-right: ${value}px !important; }`;
                break;
        }

        // Remove CSS anterior
        $('#uenf-icon-' + property + '-css').remove();
        
        // Adiciona novo CSS
        if (css) {
            $('head').append('<style id="uenf-icon-' + property + '-css">' + css + '</style>');
        }
    }

    /**
     * Filtra ícones por categoria
     */
    function filterIconsByCategory(category) {
        $('.uenf-icon-item').hide();
        
        if (category === 'all') {
            $('.uenf-icon-item').show();
        } else {
            $('.uenf-icon-item[data-category="' + category + '"]').show();
        }
        
        // Trigger evento personalizado
        $(document).trigger('cct:iconCategoryChanged', {
            category: category,
            visibleIcons: $('.uenf-icon-item:visible').length
        });
    }

    /**
     * Atualiza ícones favoritos
     */
    function updateFavoriteIcons(favorites) {
        $('.uenf-icon-item').removeClass('favorite');
        
        if (favorites && favorites.length) {
            favorites.forEach(function(iconId) {
                $('.uenf-icon-item[data-icon-id="' + iconId + '"]').addClass('favorite');
            });
        }
        
        // Atualizar contador de favoritos
        $('.uenf-favorites-count').text(favorites ? favorites.length : 0);
    }

    /**
     * Atualiza ícones recentes
     */
    function updateRecentIcons(recent) {
        $('.uenf-icon-item').removeClass('recent');
        
        if (recent && recent.length) {
            recent.forEach(function(iconId) {
                $('.uenf-icon-item[data-icon-id="' + iconId + '"]').addClass('recent');
            });
        }
    }

    /**
     * Atualiza ícones personalizados
     */
    function updateCustomIcons(customIconsData) {
        var customContainer = $('.uenf-custom-icons-container');
        
        if (!customContainer.length) {
            customContainer = $('<div class="uenf-custom-icons-container"></div>');
            $('.uenf-icon-library').append(customContainer);
        }
        
        customContainer.empty();
        
        if (customIconsData && customIconsData.length) {
            customIconsData.forEach(function(iconData) {
                var iconElement = $(`
                    <div class="uenf-icon-item custom-icon" data-icon-id="${iconData.id}">
                        <img src="${iconData.url}" alt="${iconData.name}" class="uenf-custom-icon">
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
                $('.uenf-upload-max-size').text(settings.maxFileSize);
            }
            
            // Atualizar tipos de arquivo permitidos
            if (settings.allowedTypes) {
                $('.uenf-upload-allowed-types').text(settings.allowedTypes.join(', '));
            }
        }
    }

    /**
     * Manipuladores de eventos para interação com ícones
     */
    function bindIconEvents() {
        // Clique em ícone
        $(document).on('click', '.uenf-icon-item', function() {
            var iconId = $(this).data('icon-id');
            var iconClass = $(this).find('.uenf-icon').attr('class');
            
            // Marcar como selecionado
            $('.uenf-icon-item').removeClass('selected');
            $(this).addClass('selected');
            
            // Trigger evento personalizado
            $(document).trigger('cct:iconSelected', {
                id: iconId,
                class: iconClass,
                element: this
            });
        });
        
        // Duplo clique para adicionar aos favoritos
        $(document).on('dblclick', '.uenf-icon-item', function() {
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
