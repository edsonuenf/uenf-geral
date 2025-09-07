/**
 * Preview de Cores - JavaScript do Front-end
 * 
 * Atualiza cores em tempo real no preview do customizer
 * 
 * @package CCT_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Objeto para gerenciar preview de cores
    var CCTColorPreview = {
        
        // Mapeamento de cores para seletores CSS
        colorMap: {
            'cct_palette_primary': {
                selectors: [
                    'h1, h2, h3, h4, h5, h6',
                    '.btn-primary',
                    '.text-primary',
                    'a:hover',
                    '.navbar-brand',
                    '.menu-item.current-menu-item a'
                ],
                property: 'color'
            },
            'cct_palette_secondary': {
                selectors: [
                    '.btn-secondary',
                    '.text-secondary',
                    '.navbar-nav .nav-link:hover'
                ],
                property: 'color'
            },
            'cct_palette_accent': {
                selectors: [
                    '.btn-accent',
                    '.text-accent',
                    '.highlight',
                    '.badge-accent'
                ],
                property: 'color'
            },
            'cct_palette_success': {
                selectors: [
                    '.btn-success',
                    '.text-success',
                    '.alert-success',
                    '.badge-success'
                ],
                property: 'color'
            },
            'cct_palette_warning': {
                selectors: [
                    '.btn-warning',
                    '.text-warning',
                    '.alert-warning',
                    '.badge-warning'
                ],
                property: 'color'
            },
            'cct_palette_danger': {
                selectors: [
                    '.btn-danger',
                    '.text-danger',
                    '.alert-danger',
                    '.badge-danger'
                ],
                property: 'color'
            },
            'cct_palette_light': {
                selectors: [
                    'body',
                    '.bg-light',
                    '.card',
                    '.modal-content'
                ],
                property: 'background-color'
            },
            'cct_palette_dark': {
                selectors: [
                    '.bg-dark',
                    '.navbar-dark',
                    '.footer',
                    '.text-dark'
                ],
                property: 'color'
            }
        },
        
        // Mapeamento de cores para backgrounds
        backgroundMap: {
            'cct_palette_primary': [
                '.bg-primary',
                '.btn-primary',
                '.badge-primary',
                '.progress-bar'
            ],
            'cct_palette_secondary': [
                '.bg-secondary',
                '.btn-secondary',
                '.badge-secondary'
            ],
            'cct_palette_accent': [
                '.bg-accent',
                '.btn-accent',
                '.badge-accent'
            ],
            'cct_palette_success': [
                '.bg-success',
                '.btn-success',
                '.badge-success',
                '.alert-success'
            ],
            'cct_palette_warning': [
                '.bg-warning',
                '.btn-warning',
                '.badge-warning',
                '.alert-warning'
            ],
            'cct_palette_danger': [
                '.bg-danger',
                '.btn-danger',
                '.badge-danger',
                '.alert-danger'
            ],
            'cct_palette_light': [
                '.bg-light',
                '.card',
                '.modal-content',
                '.dropdown-menu'
            ],
            'cct_palette_dark': [
                '.bg-dark',
                '.navbar-dark',
                '.footer'
            ]
        },
        
        // Mapeamento de cores para bordas
        borderMap: {
            'cct_palette_primary': [
                '.border-primary',
                '.btn-outline-primary'
            ],
            'cct_palette_secondary': [
                '.border-secondary',
                '.btn-outline-secondary'
            ],
            'cct_palette_accent': [
                '.border-accent',
                '.btn-outline-accent'
            ],
            'cct_palette_success': [
                '.border-success',
                '.btn-outline-success'
            ],
            'cct_palette_warning': [
                '.border-warning',
                '.btn-outline-warning'
            ],
            'cct_palette_danger': [
                '.border-danger',
                '.btn-outline-danger'
            ]
        },
        
        /**
         * Inicializa o preview
         */
        init: function() {
            this.bindColorChanges();
            this.createDynamicStyles();
        },
        
        /**
         * Vincula mudanças de cores
         */
        bindColorChanges: function() {
            var self = this;
            
            // Monitora mudanças em todas as cores da paleta
            Object.keys(this.colorMap).forEach(function(settingId) {
                wp.customize(settingId, function(value) {
                    value.bind(function(newColor) {
                        self.updateColor(settingId, newColor);
                    });
                });
            });
            
            // Monitora mudanças na paleta selecionada
            wp.customize('cct_selected_palette', function(value) {
                value.bind(function(newPalette) {
                    self.updatePalette(newPalette);
                });
            });
        },
        
        /**
         * Cria folha de estilos dinâmica
         */
        createDynamicStyles: function() {
            if ($('#cct-dynamic-colors').length === 0) {
                $('<style id="cct-dynamic-colors"></style>').appendTo('head');
            }
        },
        
        /**
         * Atualiza uma cor específica
         */
        updateColor: function(settingId, color) {
            var css = '';
            
            // Atualiza cor do texto
            if (this.colorMap[settingId]) {
                var mapping = this.colorMap[settingId];
                mapping.selectors.forEach(function(selector) {
                    css += selector + ' { ' + mapping.property + ': ' + color + ' !important; }\n';
                });
            }
            
            // Atualiza cor de fundo
            if (this.backgroundMap[settingId]) {
                this.backgroundMap[settingId].forEach(function(selector) {
                    css += selector + ' { background-color: ' + color + ' !important; }\n';
                });
            }
            
            // Atualiza cor da borda
            if (this.borderMap[settingId]) {
                this.borderMap[settingId].forEach(function(selector) {
                    css += selector + ' { border-color: ' + color + ' !important; }\n';
                });
            }
            
            // Adiciona variações da cor
            css += this.generateColorVariations(settingId, color);
            
            // Atualiza CSS
            this.updateDynamicCSS(settingId, css);
            
            // Atualiza variáveis CSS customizadas
            this.updateCSSVariables(settingId, color);
        },
        
        /**
         * Gera variações de uma cor
         */
        generateColorVariations: function(settingId, color) {
            var css = '';
            var colorName = settingId.replace('cct_palette_', '');
            
            // Gera tons mais claros e escuros
            var lightColor = this.lightenColor(color, 20);
            var darkColor = this.darkenColor(color, 20);
            var veryLightColor = this.lightenColor(color, 40);
            var veryDarkColor = this.darkenColor(color, 40);
            
            // Hover states
            css += '.btn-' + colorName + ':hover { background-color: ' + darkColor + ' !important; }\n';
            css += '.btn-outline-' + colorName + ':hover { background-color: ' + color + ' !important; color: white !important; }\n';
            
            // Focus states
            css += '.btn-' + colorName + ':focus { box-shadow: 0 0 0 0.2rem ' + this.addAlpha(color, 0.25) + ' !important; }\n';
            
            // Light variations
            css += '.bg-' + colorName + '-light { background-color: ' + lightColor + ' !important; }\n';
            css += '.bg-' + colorName + '-very-light { background-color: ' + veryLightColor + ' !important; }\n';
            
            // Dark variations
            css += '.bg-' + colorName + '-dark { background-color: ' + darkColor + ' !important; }\n';
            css += '.bg-' + colorName + '-very-dark { background-color: ' + veryDarkColor + ' !important; }\n';
            
            return css;
        },
        
        /**
         * Atualiza CSS dinâmico
         */
        updateDynamicCSS: function(settingId, css) {
            var $style = $('#cct-dynamic-colors');
            var currentCSS = $style.html();
            
            // Remove CSS anterior desta cor
            var colorName = settingId.replace('cct_palette_', '');
            var regex = new RegExp('/\\* ' + colorName + ' \\*/[\\s\\S]*?/\\* end ' + colorName + ' \\*/', 'g');
            currentCSS = currentCSS.replace(regex, '');
            
            // Adiciona novo CSS
            currentCSS += '/* ' + colorName + ' */\n' + css + '/* end ' + colorName + ' */\n';
            
            $style.html(currentCSS);
        },
        
        /**
         * Atualiza variáveis CSS customizadas
         */
        updateCSSVariables: function(settingId, color) {
            var colorName = settingId.replace('cct_palette_', '');
            var root = document.documentElement;
            
            // Define variável CSS principal
            root.style.setProperty('--color-' + colorName, color);
            
            // Define variações
            root.style.setProperty('--color-' + colorName + '-light', this.lightenColor(color, 20));
            root.style.setProperty('--color-' + colorName + '-dark', this.darkenColor(color, 20));
            root.style.setProperty('--color-' + colorName + '-alpha-25', this.addAlpha(color, 0.25));
            root.style.setProperty('--color-' + colorName + '-alpha-50', this.addAlpha(color, 0.5));
            root.style.setProperty('--color-' + colorName + '-alpha-75', this.addAlpha(color, 0.75));
        },
        
        /**
         * Atualiza paleta completa
         */
        updatePalette: function(paletteId) {
            // Esta função seria chamada quando uma paleta predefinida é selecionada
            // As cores individuais já serão atualizadas pelos binds específicos
            
            // Adiciona classe CSS para identificar a paleta atual
            $('body').removeClass(function(index, className) {
                return (className.match(/(^|\s)palette-\S+/g) || []).join(' ');
            }).addClass('palette-' + paletteId);
        },
        
        /**
         * Clareia uma cor
         */
        lightenColor: function(color, percent) {
            var num = parseInt(color.replace('#', ''), 16);
            var amt = Math.round(2.55 * percent);
            var R = (num >> 16) + amt;
            var G = (num >> 8 & 0x00FF) + amt;
            var B = (num & 0x0000FF) + amt;
            
            return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
        },
        
        /**
         * Escurece uma cor
         */
        darkenColor: function(color, percent) {
            var num = parseInt(color.replace('#', ''), 16);
            var amt = Math.round(2.55 * percent);
            var R = (num >> 16) - amt;
            var G = (num >> 8 & 0x00FF) - amt;
            var B = (num & 0x0000FF) - amt;
            
            return '#' + (0x1000000 + (R > 255 ? 255 : R < 0 ? 0 : R) * 0x10000 +
                (G > 255 ? 255 : G < 0 ? 0 : G) * 0x100 +
                (B > 255 ? 255 : B < 0 ? 0 : B)).toString(16).slice(1);
        },
        
        /**
         * Adiciona transparência a uma cor
         */
        addAlpha: function(color, alpha) {
            var hex = color.replace('#', '');
            var r = parseInt(hex.substr(0, 2), 16);
            var g = parseInt(hex.substr(2, 2), 16);
            var b = parseInt(hex.substr(4, 2), 16);
            
            return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
        },
        
        /**
         * Converte hex para RGB
         */
        hexToRgb: function(hex) {
            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        },
        
        /**
         * Converte RGB para hex
         */
        rgbToHex: function(r, g, b) {
            return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
        }
    };
    
    // Inicializa quando o preview estiver pronto
    wp.customize.preview.bind('ready', function() {
        CCTColorPreview.init();
    });
    
    // Adiciona estilos base para o preview
    $('<style>').text(`
        /* Transições suaves para mudanças de cor */
        * {
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Indicador visual de preview ativo */
        body.customize-preview-active::before {
            content: "Preview de Cores Ativo";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #0073aa;
            color: white;
            text-align: center;
            padding: 5px;
            font-size: 12px;
            z-index: 999999;
            opacity: 0.8;
        }
        
        /* Destaque para elementos sendo modificados */
        .cct-color-highlight {
            outline: 2px dashed #0073aa !important;
            outline-offset: 2px !important;
        }
    `).appendTo('head');
    
})(jQuery);