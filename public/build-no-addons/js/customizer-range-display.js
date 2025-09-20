/**
 * Customizer Range Display
 * 
 * Exibe valores dinâmicos nos controles deslizantes
 */
(function($) {
    'use strict';

    // Lista de controles range que devem mostrar valores
    var rangeControls = {
        'social_media_icon_size': 'px',
        'social_media_border_width': 'px',
        'social_media_border_radius': '%',
        'social_media_icon_gap': 'px'
    };

    // Aguardar o customizer estar pronto
    wp.customize.bind('ready', function() {
        setTimeout(function() {
            initRangeDisplays();
        }, 1000);
    });

    /**
     * Inicializa displays de valores para controles range
     */
    function initRangeDisplays() {
        $.each(rangeControls, function(controlId, unit) {
            var $control = $('#customize-control-' + controlId);
            if ($control.length) {
                addValueDisplay($control, controlId, unit);
            }
        });
    }

    /**
     * Adiciona display de valor para um controle
     */
    function addValueDisplay($control, controlId, unit) {
        var $input = $control.find('input[type="range"]');
        if ($input.length) {
            // Criar elemento para mostrar o valor
            var $valueDisplay = $('<span class="range-value-display">');
            $valueDisplay.css({
                'display': 'inline-block',
                'margin-left': '10px',
                'padding': '2px 8px',
                'background': '#f0f0f1',
                'border': '1px solid #c3c4c7',
                'border-radius': '3px',
                'font-size': '12px',
                'font-weight': 'bold',
                'color': '#2c3338',
                'min-width': '45px',
                'text-align': 'center'
            });

            // Posicionar após o input
            $input.after($valueDisplay);

            // Função para atualizar o valor exibido
            function updateDisplay() {
                var currentValue = $input.val();
                $valueDisplay.text(currentValue + unit);
            }

            // Atualizar valor inicial
            updateDisplay();

            // Atualizar quando o valor mudar
            $input.on('input change', updateDisplay);

            // Também escutar mudanças via customizer API
            if (wp.customize(controlId)) {
                wp.customize(controlId, function(value) {
                    value.bind(function(newval) {
                        $valueDisplay.text(newval + unit);
                    });
                });
            }
        }
    }

    /**
     * Adiciona estilos CSS para melhorar a aparência
     */
    function addCustomStyles() {
        var styles = `
            <style id="range-display-styles">
                .range-value-display {
                    transition: all 0.2s ease;
                }
                
                .range-value-display:hover {
                    background: #e0e0e1 !important;
                }
                
                .customize-control input[type="range"] {
                    margin-right: 0 !important;
                }
                
                .customize-control .range-value-display {
                    vertical-align: middle;
                }
                
                /* Responsivo */
                @media (max-width: 782px) {
                    .range-value-display {
                        font-size: 11px !important;
                        padding: 1px 6px !important;
                        margin-left: 8px !important;
                        min-width: 40px !important;
                    }
                }
            </style>
        `;
        
        if (!$('#range-display-styles').length) {
            $('head').append(styles);
        }
    }

    // Adicionar estilos quando o customizer estiver pronto
    wp.customize.bind('ready', function() {
        addCustomStyles();
    });

    /**
     * Função para adicionar novos controles range dinamicamente
     */
    window.addRangeDisplay = function(controlId, unit) {
        rangeControls[controlId] = unit;
        var $control = $('#customize-control-' + controlId);
        if ($control.length) {
            addValueDisplay($control, controlId, unit);
        }
    };

})(jQuery);