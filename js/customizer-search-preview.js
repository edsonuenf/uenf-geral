/**
 * Preview em Tempo Real - Sistema de Busca
 * 
 * Atualiza os estilos da busca em tempo real no customizer
 * 
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Verificar se wp.customize está disponível
    if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
        console.warn('WordPress Customizer API not available');
        return;
    }
    
    // Aguardar carregamento do customizer
    // Indicador de tamanho do resumo nos resultados da busca
    function updateExcerptLengthIndicator(val) {
        var container = $('.search-stats');
        if (!container.length) {
            return; // Não estamos na página de resultados de busca
        }

        // Criar elemento indicador caso não exista
        var indicator = container.find('.excerpt-length-indicator');
        if (!indicator.length) {
            indicator = $('<div class="excerpt-length-indicator text-muted"></div>');
            // Posicionar ao lado das estatísticas existentes
            container.append(indicator);
        }

        var words = parseInt(val, 10);
        if (isNaN(words) || words < 1) {
            words = 1;
        }
        indicator.text('Resumo: ' + words + ' palavras');
    }

    // Re-trim dinâmico dos resumos dos resultados de busca
    function trimWords(text, count) {
        if (!text) { return ''; }
        var parts = String(text).trim().split(/\s+/);
        if (parts.length <= count) { return parts.join(' '); }
        return parts.slice(0, count).join(' ') + '…';
    }

    function updateResultExcerpts(val) {
        var words = parseInt(val, 10);
        if (isNaN(words) || words < 1) { words = 1; }

        var $results = $('.result-excerpt');
        if (!$results.length) { return; }

        $results.each(function() {
            var $el = $(this);
            var base = $el.attr('data-base-excerpt');
            if (!base) {
                base = $el.text();
            }
            var trimmed = trimWords(base, words);
            $el.text(trimmed);
        });
    }

    // Bindar ao setting do Customizer para atualização em tempo real
    wp.customize('cct_search_results_excerpt_length', function(value) {
        value.bind(function(newval) {
            updateExcerptLengthIndicator(newval);
            updateResultExcerpts(newval);
        });
    });

    wp.customize('cct_search_button_color', function(value) {
        value.bind(function(newval) {
            $('input[type="submit"].search-custom-uenf').css('background-color', newval);
            $('button[type="submit"].search-custom-uenf').css('background-color', newval);
            $('.search-submit.search-custom-uenf').css('background-color', newval);
        });
    });
    
    wp.customize('cct_search_button_border_color', function(value) {
        value.bind(function(newval) {
            $('input[type="submit"].search-custom-uenf').css('border-color', newval);
            $('button[type="submit"].search-custom-uenf').css('border-color', newval);
            $('.search-submit.search-custom-uenf').css('border-color', newval);
        });
    });
    
    wp.customize('cct_search_button_hover_color', function(value) {
        value.bind(function(newval) {
            // Criar/atualizar regra CSS para hover
            updateHoverCSS('input[type="submit"].search-custom-uenf:hover', {
                'background-color': newval
            });
            updateHoverCSS('button[type="submit"].search-custom-uenf:hover', {
                'background-color': newval
            });
            updateHoverCSS('.search-submit.search-custom-uenf:hover', {
                'background-color': newval
            });
        });
    });
    
    wp.customize('cct_search_border_color', function(value) {
        value.bind(function(newval) {
            $('input[type="search"].search-custom-uenf').css('border-color', newval);
        });
    });
    
    // Configurações do campo de texto
    wp.customize('cct_search_field_bg_color', function(value) {
        value.bind(function(newval) {
            $('input[type="search"].search-custom-uenf').css('background-color', newval);
        });
    });
    
    wp.customize('cct_search_field_text_color', function(value) {
        value.bind(function(newval) {
            $('input[type="search"].search-custom-uenf').css('color', newval);
        });
    });
    
    wp.customize('cct_search_field_placeholder_color', function(value) {
        value.bind(function(newval) {
            // Atualizar cor do placeholder via CSS
            updatePlaceholderCSS('input[type="search"].search-custom-uenf', newval);
        });
    });
    
    wp.customize('cct_search_field_focus_color', function(value) {
        value.bind(function(newval) {
            // Atualizar cor de foco via CSS
            updateFocusCSS('input[type="search"].search-custom-uenf', newval);
        });
    });
    
    // Variáveis para armazenar valores e unidades
    var paddingVerticalValue = 0;
    var paddingVerticalUnit = 'px';
    
    function initializePaddingValues() {
        paddingVerticalValue = wp.customize('cct_search_padding_vertical')() || 0;
        paddingVerticalUnit = wp.customize('cct_search_padding_vertical_unit')() || 'px';
    }
    
    function updatePaddingVertical() {
        var value = paddingVerticalValue + paddingVerticalUnit;
        $('input[type="search"].search-custom-uenf').css('padding-top', value);
        $('input[type="search"].search-custom-uenf').css('padding-bottom', value);
        $('input[type="submit"].search-custom-uenf').css('padding-top', value);
        $('input[type="submit"].search-custom-uenf').css('padding-bottom', value);
        $('.search-submit.search-custom-uenf').css('padding-top', value);
        $('.search-submit.search-custom-uenf').css('padding-bottom', value);
    }
    
    wp.customize('cct_search_padding_vertical', function(value) {
        value.bind(function(newval) {
            paddingVerticalValue = newval;
            updatePaddingVertical();
        });
    });
    
    wp.customize('cct_search_padding_vertical_unit', function(value) {
        value.bind(function(newval) {
            paddingVerticalUnit = newval;
            updatePaddingVertical();
        });
    });
    
    // Variáveis para largura máxima
    var maxWidthValue = 100;
    var maxWidthUnit = '%';
    
    function initializeMaxWidthValues() {
        maxWidthValue = wp.customize('cct_search_max_width')() || 100;
        maxWidthUnit = wp.customize('cct_search_max_width_unit')() || '%';
    }
    
    function updateMaxWidth() {
        var value = maxWidthValue + maxWidthUnit;
        $('.custom-search-form.search-custom-uenf').css('max-width', value);
    }
    
    wp.customize('cct_search_max_width', function(value) {
        value.bind(function(newval) {
            maxWidthValue = newval;
            updateMaxWidth();
        });
    });
    
    wp.customize('cct_search_max_width_unit', function(value) {
        value.bind(function(newval) {
            maxWidthUnit = newval;
            updateMaxWidth();
        });
    });
    
    wp.customize('cct_search_border_width', function(value) {
        value.bind(function(newval) {
            $('input[type="search"].search-custom-uenf').css('border-width', newval + 'px');
        });
    });
    
    wp.customize('cct_search_button_border_width', function(value) {
        value.bind(function(newval) {
            $('input[type="submit"].search-custom-uenf').css('border-width', newval + 'px');
            $('button[type="submit"].search-custom-uenf').css('border-width', newval + 'px');
            $('.search-submit.search-custom-uenf').css('border-width', newval + 'px');
        });
    });

    // ===== Botão "Nova Busca" nos resultados =====
    // Helper para injetar regras CSS dinâmicas
    function updateRuleCSS(styleId, cssText) {
        var $style = $('#' + styleId);
        if ($style.length) { $style.remove(); }
        $('head').append('<style id="' + styleId + '">' + cssText + '</style>');
    }

    function getCustomizerValue(id, fallback) {
        var v = (typeof wp.customize(id) === 'function') ? wp.customize(id)() : undefined;
        return (typeof v !== 'undefined' && v !== null && v !== '') ? v : fallback;
    }

    function updateNewSearchCSS() {
        var bg   = getCustomizerValue('cct_search_results_new_search_button_bg_color', '#1e73be');
        var text = getCustomizerValue('cct_search_results_new_search_button_text_color', '#ffffff');
        var bord = getCustomizerValue('cct_search_results_new_search_button_border_color', '#0d6efd');
        var bw   = getCustomizerValue('cct_search_results_new_search_button_border_width', 0);
        var br   = getCustomizerValue('cct_search_results_new_search_button_border_radius', 25);

        var css = '';
        css += '.search-actions .new-search-btn, .result-actions .new-search-btn{';
        css += 'background-color:' + bg + ' !important;';
        css += 'color:' + text + ' !important;';
        css += 'border-color:' + bord + ' !important;';
        css += 'border-width:' + bw + 'px !important;';
        css += 'border-radius:' + br + 'px !important;';
        css += '}';
        css += '.search-actions .new-search-btn i, .result-actions .new-search-btn i{';
        css += 'color:' + text + ' !important;';
        css += '}';

        updateRuleCSS('cct-search-new-search-css', css);
    }

    // Bindings (estado normal)
    wp.customize('cct_search_results_new_search_button_bg_color', function(value) {
        value.bind(function() { updateNewSearchCSS(); });
    });
    wp.customize('cct_search_results_new_search_button_text_color', function(value) {
        value.bind(function() { updateNewSearchCSS(); });
    });
    wp.customize('cct_search_results_new_search_button_border_color', function(value) {
        value.bind(function() { updateNewSearchCSS(); });
    });
    wp.customize('cct_search_results_new_search_button_border_width', function(value) {
        value.bind(function() { updateNewSearchCSS(); });
    });
    wp.customize('cct_search_results_new_search_button_border_radius', function(value) {
        value.bind(function() { updateNewSearchCSS(); });
    });

    // ===== Botão "Ler mais" nos resultados =====
    function updateReadMoreCSS() {
        var bg   = getCustomizerValue('cct_search_results_read_more_button_bg_color', '#152a5a');
        var text = getCustomizerValue('cct_search_results_read_more_button_text_color', '#ffffff');
        var bord = getCustomizerValue('cct_search_results_read_more_button_border_color', '#0d6efd');
        var bw   = getCustomizerValue('cct_search_results_read_more_button_border_width', 0);
        var br   = getCustomizerValue('cct_search_results_read_more_button_border_radius', 25);

        var css = '';
        css += '.result-actions .read-more-btn{';
        css += 'background-color:' + bg + ' !important;';
        css += 'color:' + text + ' !important;';
        css += 'border-color:' + bord + ' !important;';
        css += 'border-width:' + bw + 'px !important;';
        css += 'border-radius:' + br + 'px !important;';
        css += '}';
        css += '.result-actions .read-more-btn i{';
        css += 'color:' + text + ' !important;';
        css += '}';

        updateRuleCSS('cct-search-read-more-css', css);
    }

    wp.customize('cct_search_results_read_more_button_bg_color', function(value) {
        value.bind(function() { updateReadMoreCSS(); });
    });
    wp.customize('cct_search_results_read_more_button_text_color', function(value) {
        value.bind(function() { updateReadMoreCSS(); });
    });
    wp.customize('cct_search_results_read_more_button_border_color', function(value) {
        value.bind(function() { updateReadMoreCSS(); });
    });
    wp.customize('cct_search_results_read_more_button_border_width', function(value) {
        value.bind(function() { updateReadMoreCSS(); });
    });
    wp.customize('cct_search_results_read_more_button_border_radius', function(value) {
        value.bind(function() { updateReadMoreCSS(); });
    });

    function updateReadMoreHoverCSS() {
        var bg   = getCustomizerValue('cct_search_results_read_more_button_hover_bg_color', '#1e73be');
        var text = getCustomizerValue('cct_search_results_read_more_button_hover_text_color', '#ffffff');
        var bord = getCustomizerValue('cct_search_results_read_more_button_hover_border_color', '#0d6efd');
        var bw   = getCustomizerValue('cct_search_results_read_more_button_hover_border_width', 0);

        updateHoverCSS('.result-actions .read-more-btn:hover', {
            'background-color': bg,
            'color': text,
            'border-color': bord,
            'border-width': bw + 'px'
        });
    }

    wp.customize('cct_search_results_read_more_button_hover_bg_color', function(value) {
        value.bind(function() { updateReadMoreHoverCSS(); });
    });
    wp.customize('cct_search_results_read_more_button_hover_text_color', function(value) {
        value.bind(function() { updateReadMoreHoverCSS(); });
    });
    wp.customize('cct_search_results_read_more_button_hover_border_color', function(value) {
        value.bind(function() { updateReadMoreHoverCSS(); });
    });
    wp.customize('cct_search_results_read_more_button_hover_border_width', function(value) {
        value.bind(function() { updateReadMoreHoverCSS(); });
    });

    function updateNewSearchHoverCSS() {
        var bg   = (typeof wp.customize('cct_search_results_new_search_button_hover_bg_color') === 'function') ? (wp.customize('cct_search_results_new_search_button_hover_bg_color')() || '#152a5a') : '#152a5a';
        var text = (typeof wp.customize('cct_search_results_new_search_button_hover_text_color') === 'function') ? (wp.customize('cct_search_results_new_search_button_hover_text_color')() || '#ffffff') : '#ffffff';
        var bord = (typeof wp.customize('cct_search_results_new_search_button_hover_border_color') === 'function') ? (wp.customize('cct_search_results_new_search_button_hover_border_color')() || '#0d6efd') : '#0d6efd';
        var bw   = (typeof wp.customize('cct_search_results_new_search_button_hover_border_width') === 'function') ? (wp.customize('cct_search_results_new_search_button_hover_border_width')() || 0) : 0;

        updateHoverCSS('.search-actions .new-search-btn:hover', {
            'background-color': bg,
            'color': text,
            'border-color': bord,
            'border-width': bw + 'px'
        });
    }

    // Hover bindings
    wp.customize('cct_search_results_new_search_button_hover_bg_color', function(value) {
        value.bind(function() { updateNewSearchHoverCSS(); });
    });
    wp.customize('cct_search_results_new_search_button_hover_text_color', function(value) {
        value.bind(function() { updateNewSearchHoverCSS(); });
    });
    wp.customize('cct_search_results_new_search_button_hover_border_color', function(value) {
        value.bind(function() { updateNewSearchHoverCSS(); });
    });
    wp.customize('cct_search_results_new_search_button_hover_border_width', function(value) {
        value.bind(function() { updateNewSearchHoverCSS(); });
    });

    // ===== Destaque de termos nos resultados =====
    function updateHighlightCSS() {
        var bg   = getCustomizerValue('cct_search_results_highlight_bg_color', '#ededc7');
        var text = getCustomizerValue('cct_search_results_highlight_text_color', '');
        var wght = getCustomizerValue('cct_search_results_highlight_font_weight', '700');

        var css = 'mark.cct-highlight, .cct-highlight{';
        css += 'background-color:' + bg + ' !important;';
        css += 'font-weight:' + wght + ' !important;';
        if (text && text !== '') {
            css += 'color:' + text + ' !important;';
        } else {
            css += 'color: inherit;';
        }
        css += 'padding:0 .15em;border-radius:2px;';
        css += '}';

        updateRuleCSS('cct-search-highlight-css', css);
    }

    // ===== Botão de Link (copiar/abrir) nos resultados =====
    function updateLinkButtonCSS() {
        var bg   = getCustomizerValue('cct_search_results_link_button_bg_color', '#afb8bf');
        var icon = getCustomizerValue('cct_search_results_link_button_icon_color', '#000000');
        var bord = getCustomizerValue('cct_search_results_link_button_border_color', '#0d6efd');
        var bw   = getCustomizerValue('cct_search_results_link_button_border_width', 0);
        var br   = getCustomizerValue('cct_search_results_link_button_border_radius', 25);

        var css = '';
        css += '.result-actions .copy-link-btn{';
        css += 'background-color:' + bg + ' !important;';
        css += 'border-color:' + bord + ' !important;';
        css += 'border-width:' + bw + 'px !important;';
        css += 'border-radius:' + br + 'px !important;';
        css += '}';
        css += '.result-actions .copy-link-btn i{';
        css += 'color:' + icon + ' !important;';
        css += '}';
        // Foco sem borda por padrão
        css += '.result-actions .copy-link-btn:focus{outline:none !important;border-width:0 !important;border-color:transparent !important;box-shadow:none !important;}';

        updateRuleCSS('cct-search-link-button-css', css);
    }

    wp.customize('cct_search_results_link_button_bg_color', function(value) {
        value.bind(function() { updateLinkButtonCSS(); });
    });
    wp.customize('cct_search_results_link_button_icon_color', function(value) {
        value.bind(function() { updateLinkButtonCSS(); });
    });
    wp.customize('cct_search_results_link_button_border_color', function(value) {
        value.bind(function() { updateLinkButtonCSS(); });
    });
    wp.customize('cct_search_results_link_button_border_width', function(value) {
        value.bind(function() { updateLinkButtonCSS(); });
    });
    wp.customize('cct_search_results_link_button_border_radius', function(value) {
        value.bind(function() { updateLinkButtonCSS(); });
    });

    function updateLinkButtonHoverCSS() {
        var bg   = getCustomizerValue('cct_search_results_link_button_hover_bg_color', '#0d6efd');
        var icon = getCustomizerValue('cct_search_results_link_button_hover_icon_color', '#ffffff');
        var bord = getCustomizerValue('cct_search_results_link_button_hover_border_color', '#0d6efd');
        var bw   = getCustomizerValue('cct_search_results_link_button_hover_border_width', 0);

        updateHoverCSS('.result-actions .copy-link-btn:hover', {
            'background-color': bg,
            'border-color': bord,
            'border-width': bw + 'px'
        });

        // Atualizar ícone no hover
        updateHoverCSS('.result-actions .copy-link-btn:hover i', {
            'color': icon
        });
    }

    wp.customize('cct_search_results_link_button_hover_bg_color', function(value) {
        value.bind(function() { updateLinkButtonHoverCSS(); });
    });
    wp.customize('cct_search_results_link_button_hover_icon_color', function(value) {
        value.bind(function() { updateLinkButtonHoverCSS(); });
    });
    wp.customize('cct_search_results_link_button_hover_border_color', function(value) {
        value.bind(function() { updateLinkButtonHoverCSS(); });
    });
    wp.customize('cct_search_results_link_button_hover_border_width', function(value) {
        value.bind(function() { updateLinkButtonHoverCSS(); });
    });

    // Bindings de destaque
    wp.customize('cct_search_results_highlight_bg_color', function(value) {
        value.bind(function() { updateHighlightCSS(); });
    });
    wp.customize('cct_search_results_highlight_text_color', function(value) {
        value.bind(function() { updateHighlightCSS(); });
    });
    wp.customize('cct_search_results_highlight_font_weight', function(value) {
        value.bind(function() { updateHighlightCSS(); });
    });
    
    // Border radius individuais - Campo de busca
    var fieldBorderRadiusValues = {
        topLeft: 0,
        topRight: 0,
        bottomLeft: 0,
        bottomRight: 0
    };
    
    // Função para inicializar valores
    function initializeFieldBorderRadiusValues() {
        fieldBorderRadiusValues.topLeft = wp.customize('cct_search_border_radius_top_left')() || 0;
        fieldBorderRadiusValues.topRight = wp.customize('cct_search_border_radius_top_right')() || 0;
        fieldBorderRadiusValues.bottomLeft = wp.customize('cct_search_border_radius_bottom_left')() || 0;
        fieldBorderRadiusValues.bottomRight = wp.customize('cct_search_border_radius_bottom_right')() || 0;
    }
    
    function updateFieldBorderRadius() {
        var borderRadius = fieldBorderRadiusValues.topLeft + 'px ' + 
                          fieldBorderRadiusValues.topRight + 'px ' + 
                          fieldBorderRadiusValues.bottomRight + 'px ' + 
                          fieldBorderRadiusValues.bottomLeft + 'px';
        $('input[type="search"].search-custom-uenf').css('border-radius', borderRadius);
    }
    
    wp.customize('cct_search_border_radius_top_left', function(value) {
        value.bind(function(newval) {
            fieldBorderRadiusValues.topLeft = newval;
            updateFieldBorderRadius();
        });
    });
    
    wp.customize('cct_search_border_radius_top_right', function(value) {
        value.bind(function(newval) {
            fieldBorderRadiusValues.topRight = newval;
            updateFieldBorderRadius();
        });
    });
    
    wp.customize('cct_search_border_radius_bottom_left', function(value) {
        value.bind(function(newval) {
            fieldBorderRadiusValues.bottomLeft = newval;
            updateFieldBorderRadius();
        });
    });
    
    wp.customize('cct_search_border_radius_bottom_right', function(value) {
        value.bind(function(newval) {
            fieldBorderRadiusValues.bottomRight = newval;
            updateFieldBorderRadius();
        });
    });
    
    // Border radius individuais - Botão de busca
    var buttonBorderRadiusValues = {
        topLeft: 0,
        topRight: 4,
        bottomLeft: 0,
        bottomRight: 4
    };
    
    // Função para inicializar valores do botão
    function initializeButtonBorderRadiusValues() {
        buttonBorderRadiusValues.topLeft = wp.customize('cct_search_button_border_radius_top_left')() || 0;
        buttonBorderRadiusValues.topRight = wp.customize('cct_search_button_border_radius_top_right')() || 4;
        buttonBorderRadiusValues.bottomLeft = wp.customize('cct_search_button_border_radius_bottom_left')() || 0;
        buttonBorderRadiusValues.bottomRight = wp.customize('cct_search_button_border_radius_bottom_right')() || 4;
    }
    
    function updateButtonBorderRadius() {
        var borderRadius = buttonBorderRadiusValues.topLeft + 'px ' + 
                          buttonBorderRadiusValues.topRight + 'px ' + 
                          buttonBorderRadiusValues.bottomRight + 'px ' + 
                          buttonBorderRadiusValues.bottomLeft + 'px';
        $('input[type="submit"].search-custom-uenf').css('border-radius', borderRadius);
        $('button[type="submit"].search-custom-uenf').css('border-radius', borderRadius);
        $('.search-submit.search-custom-uenf').css('border-radius', borderRadius);
    }
    
    wp.customize('cct_search_button_border_radius_top_left', function(value) {
        value.bind(function(newval) {
            buttonBorderRadiusValues.topLeft = newval;
            updateButtonBorderRadius();
        });
    });
    
    wp.customize('cct_search_button_border_radius_top_right', function(value) {
        value.bind(function(newval) {
            buttonBorderRadiusValues.topRight = newval;
            updateButtonBorderRadius();
        });
    });
    
    wp.customize('cct_search_button_border_radius_bottom_left', function(value) {
        value.bind(function(newval) {
            buttonBorderRadiusValues.bottomLeft = newval;
            updateButtonBorderRadius();
        });
    });
    
    wp.customize('cct_search_button_border_radius_bottom_right', function(value) {
        value.bind(function(newval) {
            buttonBorderRadiusValues.bottomRight = newval;
            updateButtonBorderRadius();
        });
    });
    
    // Variáveis para tamanho da fonte
    var fontSizeValue = 16;
    var fontSizeUnit = 'px';
    
    function initializeFontSizeValues() {
        fontSizeValue = wp.customize('cct_search_font_size')() || 16;
        fontSizeUnit = wp.customize('cct_search_font_size_unit')() || 'px';
    }
    
    function updateFontSize() {
        var value = fontSizeValue + fontSizeUnit;
        $('input.search-custom-uenf').css('font-size', value);
        $('button[type="submit"].search-custom-uenf').css('font-size', value);
        $('.search-submit.search-custom-uenf').css('font-size', value);
    }
    
    wp.customize('cct_search_font_size', function(value) {
        value.bind(function(newval) {
            fontSizeValue = newval;
            updateFontSize();
        });
    });
    
    wp.customize('cct_search_font_size_unit', function(value) {
        value.bind(function(newval) {
            fontSizeUnit = newval;
            updateFontSize();
        });
    });
    
    wp.customize('cct_search_box_shadow', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('form.search-custom-uenf').css('box-shadow', '0 2px 10px rgba(0, 0, 0, 0.1)');
            } else {
                $('form.search-custom-uenf').css('box-shadow', 'none');
            }
        });
    });
    
    wp.customize('cct_search_transitions', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('input.search-custom-uenf').css('transition', 'all 0.3s ease');
                $('button[type="submit"].search-custom-uenf').css('transition', 'all 0.3s ease');
                $('.search-submit.search-custom-uenf').css('transition', 'all 0.3s ease');
            } else {
                $('input.search-custom-uenf').css('transition', 'none');
                $('button[type="submit"].search-custom-uenf').css('transition', 'none');
                $('.search-submit.search-custom-uenf').css('transition', 'none');
            }
        });
    });
    
    wp.customize('cct_search_custom_css', function(value) {
        value.bind(function(newval) {
            // Remover CSS personalizado anterior
            $('#cct-search-custom-css').remove();
            
            // Adicionar novo CSS personalizado
            if (newval) {
                $('head').append('<style id="cct-search-custom-css">' + newval + '</style>');
            }
        });
    });
    
    /**
     * Função auxiliar para atualizar CSS de placeholder
     */
    function updatePlaceholderCSS(selector, color) {
        // Remover regra anterior
        $('#cct-search-placeholder-css').remove();
        
        // Criar nova regra CSS para placeholder
        var css = selector + '::placeholder { color: ' + color + ' !important; }';
        css += selector + '::-webkit-input-placeholder { color: ' + color + ' !important; }';
        css += selector + '::-moz-placeholder { color: ' + color + ' !important; }';
        css += selector + ':-ms-input-placeholder { color: ' + color + ' !important; }';
        
        $('head').append('<style id="cct-search-placeholder-css">' + css + '</style>');
    }
    
    /**
     * Função auxiliar para atualizar CSS de foco
     */
    function updateFocusCSS(selector, color) {
        // Remover regra anterior
        $('#cct-search-focus-css').remove();
        
        // Converter cor hex para RGB
        var rgb = hexToRgb(color);
        var rgbaColor = rgb ? 'rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ', 0.2)' : 'rgba(29, 55, 113, 0.2)';
        
        // Criar nova regra CSS para foco
        var css = selector + ':focus { ';
        css += 'border-color: ' + color + ' !important; ';
        css += 'outline: none !important; ';
        css += 'box-shadow: 0 0 0 2px ' + rgbaColor + ' !important; ';
        css += '}';
        
        $('head').append('<style id="cct-search-focus-css">' + css + '</style>');
    }
    
    /**
     * Função auxiliar para converter hex para RGB
     */
    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
    
    /**
     * Função auxiliar para atualizar CSS de hover
     */
    function updateHoverCSS(selector, styles) {
        // Remover regra anterior
        $('#cct-search-hover-css').remove();
        
        // Criar nova regra CSS
        var css = selector + ' {';
        for (var property in styles) {
            css += property + ': ' + styles[property] + ' !important;';
        }
        css += '}';
        
        // Adicionar ao head
        $('head').append('<style id="cct-search-hover-css">' + css + '</style>');
    }
    
    /**
     * Inicialização
     */
    wp.customize.bind('ready', function() {
        // Adicionar classe para identificar preview
        $('body').addClass('customizer-search-preview');
        
        // Inicializar valores
        initializeFieldBorderRadiusValues();
        initializeButtonBorderRadiusValues();
        initializePaddingValues();
        initializeMaxWidthValues();
        initializeFontSizeValues();
        
        // Aplicar estilos iniciais
        updateFieldBorderRadius();
        updateButtonBorderRadius();
        updatePaddingVertical();
        updateMaxWidth();
        updateFontSize();
        // Aplicar CSS inicial do botão "Nova Busca" (normal e hover)
        updateNewSearchCSS();
        updateNewSearchHoverCSS();
        // Aplicar CSS inicial de destaque
        updateHighlightCSS();

        // Atualizar indicador e re-trim de resumos com valor inicial
        if (typeof wp.customize('cct_search_results_excerpt_length') === 'function') {
            var initialExcerptLen = wp.customize('cct_search_results_excerpt_length')();
            if (typeof initialExcerptLen !== 'undefined') {
                updateExcerptLengthIndicator(initialExcerptLen);
                updateResultExcerpts(initialExcerptLen);
            }
        }

        // Log para debug
        console.log('CCT Search Customizer Preview: Inicializado');
    });
    
    // Fallback para quando o customizer não estiver disponível
    $(document).ready(function() {
        if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
            console.log('Customizer não disponível, usando fallback');
            return;
        }
    });
    
})(jQuery);