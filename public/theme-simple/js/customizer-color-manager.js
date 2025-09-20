/**
 * Gerenciador de Cores - JavaScript do Customizer
 * 
 * Funcionalidades:
 * - Aplicação de paletas predefinidas
 * - Geração de cores harmoniosas
 * - Análise de contraste WCAG
 * - Preview em tempo real
 * - Interações dos controles
 * 
 * @package CCT_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Objeto principal do gerenciador de cores
    var CCTColorManager = {
        
        // Configurações
        settings: {
            palettes: {},
            accessibilityRules: {},
            currentPalette: null,
            generatedColors: []
        },
        
        /**
         * Inicializa o gerenciador
         */
        init: function() {
            this.settings.palettes = cctColorManager.palettes || {};
            this.settings.accessibilityRules = cctColorManager.accessibilityRules || {};
            
            this.bindEvents();
            this.initPalettePreview();
            this.initColorGenerator();
            this.initContrastAnalyzer();
            this.initSmartColorControls();
        },
        
        /**
         * Vincula eventos
         */
        bindEvents: function() {
            var self = this;
            
            // Aplicação de paletas
            $(document).on('click', '.cct-apply-palette', function(e) {
                e.preventDefault();
                var paletteId = $(this).data('palette');
                self.applyPalette(paletteId);
            });
            
            // Seleção de paleta no preview
            $(document).on('click', '.cct-palette-option', function() {
                $('.cct-palette-option').removeClass('selected');
                $(this).addClass('selected');
                
                var paletteId = $(this).data('palette');
                self.settings.currentPalette = paletteId;
            });
            
            // Geração de cores
            $(document).on('click', '.cct-generate-colors', function(e) {
                e.preventDefault();
                self.generateColors();
            });
            
            // Aplicação de cores geradas
            $(document).on('click', '.cct-apply-generated', function(e) {
                e.preventDefault();
                self.applyGeneratedColors();
            });
            
            // Randomização da cor base
            $(document).on('click', '.cct-randomize-base', function(e) {
                e.preventDefault();
                self.randomizeBaseColor();
            });
            
            // Análise de contraste
            $(document).on('input', '.cct-text-color, .cct-bg-color', function() {
                self.updateContrastAnalysis();
            });
            
            // Testes rápidos de contraste
            $(document).on('click', '.cct-test-primary', function(e) {
                e.preventDefault();
                self.testColorContrast('primary');
            });
            
            $(document).on('click', '.cct-test-secondary', function(e) {
                e.preventDefault();
                self.testColorContrast('secondary');
            });
            
            $(document).on('click', '.cct-test-all', function(e) {
                e.preventDefault();
                self.testAllColors();
            });
            
            // Sugestões de cores
            $(document).on('click', '.cct-suggestion-swatch', function() {
                var color = $(this).data('color');
                var $control = $(this).closest('.customize-control').find('input[type="color"]');
                $control.val(color).trigger('change');
            });
        },
        
        /**
         * Inicializa preview de paletas
         */
        initPalettePreview: function() {
            // Marca a paleta atual como selecionada
            var currentPalette = wp.customize('cct_selected_palette')();
            $('.cct-palette-option[data-palette="' + currentPalette + '"]').addClass('selected');
            this.settings.currentPalette = currentPalette;
        },
        
        /**
         * Aplica uma paleta predefinida
         */
        applyPalette: function(paletteId) {
            if (!this.settings.palettes[paletteId]) {
                return;
            }
            
            var palette = this.settings.palettes[paletteId];
            var colors = palette.colors;
            
            // Aplica cada cor da paleta
            Object.keys(colors).forEach(function(role) {
                var settingId = 'cct_palette_' + role;
                if (wp.customize(settingId)) {
                    wp.customize(settingId).set(colors[role]);
                }
            });
            
            // Atualiza o seletor de paleta
            wp.customize('cct_selected_palette').set(paletteId);
            
            // Feedback visual
            this.showNotification('Paleta "' + palette.name + '" aplicada com sucesso!', 'success');
        },
        
        /**
         * Inicializa o gerador de cores
         */
        initColorGenerator: function() {
            // Gera cores iniciais se não houver
            if ($('#cct-generated-colors').children().length === 0) {
                this.generateColors();
            }
        },
        
        /**
         * Gera cores harmoniosas
         */
        generateColors: function() {
            var baseColor = wp.customize('cct_generator_base_color')();
            var harmonyType = wp.customize('cct_generator_harmony_type')();
            
            if (!baseColor) {
                baseColor = '#1d3771';
            }
            
            var colors = this.generateHarmony(baseColor, harmonyType);
            this.settings.generatedColors = colors;
            
            this.displayGeneratedColors(colors);
            $('.cct-apply-generated').show();
        },
        
        /**
         * Exibe cores geradas
         */
        displayGeneratedColors: function(colors) {
            var $container = $('#cct-generated-colors');
            $container.empty();
            
            colors.forEach(function(color, index) {
                var $swatch = $('<div class="cct-generated-color">');
                $swatch.css('background-color', color);
                $swatch.attr('data-hex', color);
                $swatch.attr('title', 'Cor ' + (index + 1) + ': ' + color);
                
                $container.append($swatch);
            });
        },
        
        /**
         * Aplica cores geradas à paleta
         */
        applyGeneratedColors: function() {
            if (this.settings.generatedColors.length === 0) {
                return;
            }
            
            var colors = this.settings.generatedColors;
            var roles = ['primary', 'secondary', 'accent', 'success', 'warning', 'danger'];
            
            // Mapeia cores geradas para roles
            roles.forEach(function(role, index) {
                if (colors[index]) {
                    var settingId = 'cct_palette_' + role;
                    if (wp.customize(settingId)) {
                        wp.customize(settingId).set(colors[index]);
                    }
                }
            });
            
            this.showNotification('Paleta gerada aplicada com sucesso!', 'success');
        },
        
        /**
         * Randomiza cor base
         */
        randomizeBaseColor: function() {
            var randomColor = this.generateRandomColor();
            wp.customize('cct_generator_base_color').set(randomColor);
            
            // Gera nova paleta automaticamente
            setTimeout(() => {
                this.generateColors();
            }, 100);
        },
        
        /**
         * Gera cor aleatória
         */
        generateRandomColor: function() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        },
        
        /**
         * Gera harmonia de cores
         */
        generateHarmony: function(baseColor, harmonyType) {
            var hsl = this.hexToHsl(baseColor);
            var colors = [];
            
            switch (harmonyType) {
                case 'complementary':
                    colors.push(baseColor);
                    colors.push(this.hslToHex((hsl[0] + 180) % 360, hsl[1], hsl[2]));
                    break;
                    
                case 'analogous':
                    colors.push(this.hslToHex((hsl[0] - 30 + 360) % 360, hsl[1], hsl[2]));
                    colors.push(baseColor);
                    colors.push(this.hslToHex((hsl[0] + 30) % 360, hsl[1], hsl[2]));
                    break;
                    
                case 'triadic':
                    colors.push(baseColor);
                    colors.push(this.hslToHex((hsl[0] + 120) % 360, hsl[1], hsl[2]));
                    colors.push(this.hslToHex((hsl[0] + 240) % 360, hsl[1], hsl[2]));
                    break;
                    
                case 'tetradic':
                    colors.push(baseColor);
                    colors.push(this.hslToHex((hsl[0] + 90) % 360, hsl[1], hsl[2]));
                    colors.push(this.hslToHex((hsl[0] + 180) % 360, hsl[1], hsl[2]));
                    colors.push(this.hslToHex((hsl[0] + 270) % 360, hsl[1], hsl[2]));
                    break;
                    
                case 'monochromatic':
                    colors.push(this.hslToHex(hsl[0], hsl[1], Math.max(0, hsl[2] - 40)));
                    colors.push(this.hslToHex(hsl[0], hsl[1], Math.max(0, hsl[2] - 20)));
                    colors.push(baseColor);
                    colors.push(this.hslToHex(hsl[0], hsl[1], Math.min(100, hsl[2] + 20)));
                    colors.push(this.hslToHex(hsl[0], hsl[1], Math.min(100, hsl[2] + 40)));
                    break;
                    
                default:
                    colors.push(baseColor);
            }
            
            return colors;
        },
        
        /**
         * Inicializa analisador de contraste
         */
        initContrastAnalyzer: function() {
            // Análise inicial
            this.updateContrastAnalysis();
            
            // Atualiza cores do preview quando as cores da paleta mudam
            var self = this;
            wp.customize('cct_palette_primary', function(value) {
                value.bind(function(newval) {
                    $('.cct-text-color').val(newval);
                    self.updateContrastAnalysis();
                });
            });
        },
        
        /**
         * Atualiza análise de contraste
         */
        updateContrastAnalysis: function() {
            var textColor = $('.cct-text-color').val();
            var bgColor = $('.cct-bg-color').val();
            
            if (!textColor || !bgColor) {
                return;
            }
            
            // Atualiza preview
            $('.cct-preview-sample').css({
                'color': textColor,
                'background-color': bgColor
            });
            
            // Atualiza valores exibidos
            $('.cct-text-color').siblings('.cct-color-value').text(textColor);
            $('.cct-bg-color').siblings('.cct-color-value').text(bgColor);
            
            // Calcula contraste
            var contrast = this.calculateContrast(textColor, bgColor);
            $('.cct-ratio-value').text(contrast.toFixed(2) + ':1');
            
            // Verifica conformidade WCAG
            this.updateWCAGCompliance(contrast);
            
            // Gera recomendações
            this.generateRecommendations(contrast, textColor, bgColor);
        },
        
        /**
         * Atualiza status de conformidade WCAG
         */
        updateWCAGCompliance: function(contrast) {
            var rules = this.settings.accessibilityRules;
            
            // WCAG AA
            var aaPassNormal = contrast >= (rules.wcag_aa ? rules.wcag_aa.normal_text : 4.5);
            var aaPassLarge = contrast >= (rules.wcag_aa ? rules.wcag_aa.large_text : 3.0);
            
            // WCAG AAA
            var aaaPassNormal = contrast >= (rules.wcag_aaa ? rules.wcag_aaa.normal_text : 7.0);
            var aaaPassLarge = contrast >= (rules.wcag_aaa ? rules.wcag_aaa.large_text : 4.5);
            
            // Atualiza status visual
            this.updateComplianceStatus('.cct-aa-normal', aaPassNormal);
            this.updateComplianceStatus('.cct-aa-large', aaPassLarge);
            this.updateComplianceStatus('.cct-aaa-normal', aaaPassNormal);
            this.updateComplianceStatus('.cct-aaa-large', aaaPassLarge);
        },
        
        /**
         * Atualiza status de conformidade individual
         */
        updateComplianceStatus: function(selector, passed) {
            var $element = $(selector);
            $element.removeClass('pass fail');
            
            if (passed) {
                $element.addClass('pass').text('Aprovado');
            } else {
                $element.addClass('fail').text('Reprovado');
            }
        },
        
        /**
         * Gera recomendações de acessibilidade
         */
        generateRecommendations: function(contrast, textColor, bgColor) {
            var recommendations = [];
            
            if (contrast < 4.5) {
                recommendations.push('O contraste está abaixo do mínimo WCAG AA (4.5:1).');
                
                if (this.getLuminance(textColor) > this.getLuminance(bgColor)) {
                    recommendations.push('Considere escurecer a cor do texto ou clarear o fundo.');
                } else {
                    recommendations.push('Considere clarear a cor do texto ou escurecer o fundo.');
                }
            } else if (contrast < 7.0) {
                recommendations.push('O contraste atende WCAG AA mas não AAA. Para máxima acessibilidade, considere aumentar o contraste.');
            } else {
                recommendations.push('Excelente! O contraste atende todos os padrões WCAG.');
            }
            
            $('.cct-recommendation-text').html(recommendations.join('<br>'));
        },
        
        /**
         * Testa contraste de uma cor específica
         */
        testColorContrast: function(colorRole) {
            var color = wp.customize('cct_palette_' + colorRole)();
            if (color) {
                $('.cct-text-color').val(color);
                this.updateContrastAnalysis();
            }
        },
        
        /**
         * Testa todas as cores da paleta
         */
        testAllColors: function() {
            var self = this;
            var roles = ['primary', 'secondary', 'accent', 'success', 'warning', 'danger'];
            var results = [];
            
            roles.forEach(function(role) {
                var color = wp.customize('cct_palette_' + role)();
                if (color) {
                    var contrast = self.calculateContrast(color, '#ffffff');
                    var contrastDark = self.calculateContrast(color, '#000000');
                    
                    results.push({
                        role: role,
                        color: color,
                        contrastLight: contrast,
                        contrastDark: contrastDark,
                        bestBackground: contrast > contrastDark ? '#ffffff' : '#000000'
                    });
                }
            });
            
            this.displayAllColorResults(results);
        },
        
        /**
         * Exibe resultados de teste de todas as cores
         */
        displayAllColorResults: function(results) {
            var html = '<h5>Resultados do Teste Completo:</h5>';
            
            results.forEach(function(result) {
                var bestContrast = Math.max(result.contrastLight, result.contrastDark);
                var status = bestContrast >= 4.5 ? 'Aprovado' : 'Reprovado';
                var statusClass = bestContrast >= 4.5 ? 'pass' : 'fail';
                
                html += '<div class="cct-test-result">';
                html += '<strong>' + result.role.charAt(0).toUpperCase() + result.role.slice(1) + ':</strong> ';
                html += '<span class="cct-compliance-status ' + statusClass + '">' + status + '</span> ';
                html += '(Melhor contraste: ' + bestContrast.toFixed(2) + ':1)';
                html += '</div>';
            });
            
            $('.cct-recommendation-text').html(html);
        },
        
        /**
         * Inicializa controles inteligentes de cor
         */
        initSmartColorControls: function() {
            // Adiciona sugestões baseadas na paleta atual
            this.updateColorSuggestions();
            
            // Atualiza sugestões quando a paleta muda
            var self = this;
            wp.customize('cct_selected_palette', function(value) {
                value.bind(function() {
                    self.updateColorSuggestions();
                });
            });
        },
        
        /**
         * Atualiza sugestões de cores
         */
        updateColorSuggestions: function() {
            var currentPalette = wp.customize('cct_selected_palette')();
            if (!this.settings.palettes[currentPalette]) {
                return;
            }
            
            var colors = Object.values(this.settings.palettes[currentPalette].colors);
            
            // Adiciona sugestões a todos os controles de cor
            $('.wp-color-picker').each(function() {
                var $control = $(this).closest('.customize-control');
                var $suggestions = $control.find('.cct-color-suggestions');
                
                if ($suggestions.length === 0) {
                    var html = '<div class="cct-color-suggestions">';
                    html += '<h5>Cores da Paleta:</h5>';
                    html += '<div class="cct-suggestion-swatches">';
                    
                    colors.forEach(function(color) {
                        html += '<div class="cct-suggestion-swatch" style="background-color: ' + color + '" data-color="' + color + '" title="' + color + '"></div>';
                    });
                    
                    html += '</div></div>';
                    
                    $control.append(html);
                }
            });
        },
        
        /**
         * Calcula contraste entre duas cores
         */
        calculateContrast: function(color1, color2) {
            var luminance1 = this.getLuminance(color1);
            var luminance2 = this.getLuminance(color2);
            
            var lighter = Math.max(luminance1, luminance2);
            var darker = Math.min(luminance1, luminance2);
            
            return (lighter + 0.05) / (darker + 0.05);
        },
        
        /**
         * Calcula luminância de uma cor
         */
        getLuminance: function(hexColor) {
            hexColor = hexColor.replace('#', '');
            
            var r = parseInt(hexColor.substr(0, 2), 16) / 255;
            var g = parseInt(hexColor.substr(2, 2), 16) / 255;
            var b = parseInt(hexColor.substr(4, 2), 16) / 255;
            
            r = (r <= 0.03928) ? r / 12.92 : Math.pow((r + 0.055) / 1.055, 2.4);
            g = (g <= 0.03928) ? g / 12.92 : Math.pow((g + 0.055) / 1.055, 2.4);
            b = (b <= 0.03928) ? b / 12.92 : Math.pow((b + 0.055) / 1.055, 2.4);
            
            return 0.2126 * r + 0.7152 * g + 0.0722 * b;
        },
        
        /**
         * Converte hex para HSL
         */
        hexToHsl: function(hex) {
            hex = hex.replace('#', '');
            
            var r = parseInt(hex.substr(0, 2), 16) / 255;
            var g = parseInt(hex.substr(2, 2), 16) / 255;
            var b = parseInt(hex.substr(4, 2), 16) / 255;
            
            var max = Math.max(r, g, b);
            var min = Math.min(r, g, b);
            var diff = max - min;
            
            var l = (max + min) / 2;
            var h, s;
            
            if (diff === 0) {
                h = s = 0;
            } else {
                s = l > 0.5 ? diff / (2 - max - min) : diff / (max + min);
                
                switch (max) {
                    case r:
                        h = (g - b) / diff + (g < b ? 6 : 0);
                        break;
                    case g:
                        h = (b - r) / diff + 2;
                        break;
                    case b:
                        h = (r - g) / diff + 4;
                        break;
                }
                h /= 6;
            }
            
            return [Math.round(h * 360), Math.round(s * 100), Math.round(l * 100)];
        },
        
        /**
         * Converte HSL para hex
         */
        hslToHex: function(h, s, l) {
            h /= 360;
            s /= 100;
            l /= 100;
            
            var r, g, b;
            
            if (s === 0) {
                r = g = b = l;
            } else {
                var hueToRgb = function(p, q, t) {
                    if (t < 0) t += 1;
                    if (t > 1) t -= 1;
                    if (t < 1/6) return p + (q - p) * 6 * t;
                    if (t < 1/2) return q;
                    if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
                    return p;
                };
                
                var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
                var p = 2 * l - q;
                
                r = hueToRgb(p, q, h + 1/3);
                g = hueToRgb(p, q, h);
                b = hueToRgb(p, q, h - 1/3);
            }
            
            var toHex = function(c) {
                var hex = Math.round(c * 255).toString(16);
                return hex.length === 1 ? '0' + hex : hex;
            };
            
            return '#' + toHex(r) + toHex(g) + toHex(b);
        },
        
        /**
         * Exibe notificação
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
        CCTColorManager.init();
    });
    
})(jQuery);