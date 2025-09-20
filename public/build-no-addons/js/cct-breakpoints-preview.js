/**
 * CCT Breakpoints Preview JavaScript
 * Preview em tempo real para configurações de breakpoints responsivos
 */

(function($) {
    'use strict';

    // Aguardar o carregamento do customizer
    wp.customize = wp.customize || {};

    /**
     * Configurações padrão de breakpoints
     */
    var defaultBreakpoints = {
        mobile: 768,
        tablet: 1024,
        desktop: 1200,
        wide: 1400
    };

    /**
     * Preview em tempo real para breakpoints customizados
     */
    function bindBreakpointPreview() {
        // Mobile breakpoint
        wp.customize('cct_breakpoint_mobile', function(value) {
            value.bind(function(newval) {
                updateBreakpointCSS('mobile', newval);
                updateBreakpointIndicator('mobile', newval);
            });
        });

        // Tablet breakpoint
        wp.customize('cct_breakpoint_tablet', function(value) {
            value.bind(function(newval) {
                updateBreakpointCSS('tablet', newval);
                updateBreakpointIndicator('tablet', newval);
            });
        });

        // Desktop breakpoint
        wp.customize('cct_breakpoint_desktop', function(value) {
            value.bind(function(newval) {
                updateBreakpointCSS('desktop', newval);
                updateBreakpointIndicator('desktop', newval);
            });
        });

        // Wide breakpoint
        wp.customize('cct_breakpoint_wide', function(value) {
            value.bind(function(newval) {
                updateBreakpointCSS('wide', newval);
                updateBreakpointIndicator('wide', newval);
            });
        });
    }

    /**
     * Atualiza CSS dos breakpoints
     */
    function updateBreakpointCSS(device, breakpoint) {
        var css = '';
        
        switch(device) {
            case 'mobile':
                css = `
                    @media (max-width: ${breakpoint}px) {
                        .cct-hide-mobile { display: none !important; }
                        .cct-show-mobile { display: block !important; }
                        .cct-grid { grid-template-columns: 1fr !important; }
                        .cct-container { padding: 0 15px !important; }
                        .cct-text-mobile-center { text-align: center !important; }
                        .cct-text-mobile-left { text-align: left !important; }
                        .cct-text-mobile-right { text-align: right !important; }
                    }
                `;
                break;
                
            case 'tablet':
                css = `
                    @media (min-width: ${parseInt(breakpoint) + 1}px) and (max-width: 1024px) {
                        .cct-hide-tablet { display: none !important; }
                        .cct-show-tablet { display: block !important; }
                        .cct-grid { grid-template-columns: repeat(2, 1fr) !important; }
                        .cct-container { padding: 0 20px !important; }
                    }
                `;
                break;
                
            case 'desktop':
                css = `
                    @media (min-width: ${breakpoint}px) {
                        .cct-hide-desktop { display: none !important; }
                        .cct-show-desktop { display: block !important; }
                        .cct-container { max-width: ${breakpoint - 100}px !important; }
                    }
                `;
                break;
                
            case 'wide':
                css = `
                    @media (min-width: ${breakpoint}px) {
                        .cct-hide-wide { display: none !important; }
                        .cct-show-wide { display: block !important; }
                        .cct-container { max-width: ${breakpoint - 200}px !important; }
                        .cct-grid { grid-template-columns: repeat(4, 1fr) !important; }
                    }
                `;
                break;
        }

        // Remove CSS anterior
        $('#cct-breakpoint-' + device + '-css').remove();
        
        // Adiciona novo CSS
        if (css) {
            $('head').append('<style id="cct-breakpoint-' + device + '-css">' + css + '</style>');
        }
    }

    /**
     * Atualiza indicador visual do breakpoint
     */
    function updateBreakpointIndicator(device, breakpoint) {
        // Remove indicador anterior
        $('.cct-breakpoint-indicator').remove();
        
        // Cria novo indicador
        var indicator = $('<div class="cct-breakpoint-indicator">');
        indicator.css({
            'position': 'fixed',
            'top': '10px',
            'right': '10px',
            'background': 'rgba(0, 0, 0, 0.8)',
            'color': 'white',
            'padding': '5px 10px',
            'border-radius': '3px',
            'font-size': '12px',
            'z-index': '9999',
            'font-family': 'monospace'
        });
        
        var currentWidth = $(window).width();
        var activeBreakpoint = getCurrentBreakpoint(currentWidth);
        
        indicator.html(`
            <div>Largura: ${currentWidth}px</div>
            <div>Breakpoint: ${activeBreakpoint}</div>
            <div>${device}: ${breakpoint}px</div>
        `);
        
        $('body').append(indicator);
        
        // Remove após 3 segundos
        setTimeout(function() {
            indicator.fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }

    /**
     * Determina o breakpoint atual baseado na largura
     */
    function getCurrentBreakpoint(width) {
        var mobile = defaultBreakpoints.mobile;
        var tablet = defaultBreakpoints.tablet;
        var desktop = defaultBreakpoints.desktop;
        var wide = defaultBreakpoints.wide;
        
        // Verificar se wp.customize está disponível e obter valores customizados
        if (typeof wp !== 'undefined' && wp.customize && typeof wp.customize === 'function') {
            try {
                mobile = wp.customize('cct_breakpoint_mobile')() || defaultBreakpoints.mobile;
                tablet = wp.customize('cct_breakpoint_tablet')() || defaultBreakpoints.tablet;
                desktop = wp.customize('cct_breakpoint_desktop')() || defaultBreakpoints.desktop;
                wide = wp.customize('cct_breakpoint_wide')() || defaultBreakpoints.wide;
            } catch(e) {
                // Usar valores padrão se houver erro
            }
        }
        
        if (width <= mobile) {
            return 'Mobile';
        } else if (width <= tablet) {
            return 'Tablet';
        } else if (width <= desktop) {
            return 'Desktop';
        } else if (width <= wide) {
            return 'Desktop Large';
        } else {
            return 'Wide Screen';
        }
    }

    /**
     * Preview para configurações de visibilidade responsiva
     */
    function bindVisibilityPreview() {
        // Show/hide em diferentes dispositivos
        var visibilitySettings = [
            'cct_show_mobile',
            'cct_hide_mobile',
            'cct_show_tablet',
            'cct_hide_tablet',
            'cct_show_desktop',
            'cct_hide_desktop'
        ];
        
        visibilitySettings.forEach(function(setting) {
            wp.customize(setting, function(value) {
                value.bind(function(newval) {
                    updateVisibilityClasses(setting, newval);
                });
            });
        });
    }

    /**
     * Atualiza classes de visibilidade
     */
    function updateVisibilityClasses(setting, enabled) {
        var className = setting.replace('cct_', 'cct-');
        
        if (enabled) {
            $('body').addClass(className + '-enabled');
        } else {
            $('body').removeClass(className + '-enabled');
        }
    }

    /**
     * Preview para configurações de grid responsivo
     */
    function bindResponsiveGridPreview() {
        // Colunas por dispositivo
        wp.customize('cct_grid_columns_mobile', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('mobile', 'columns', newval);
            });
        });

        wp.customize('cct_grid_columns_tablet', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('tablet', 'columns', newval);
            });
        });

        wp.customize('cct_grid_columns_desktop', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('desktop', 'columns', newval);
            });
        });

        // Gap por dispositivo
        wp.customize('cct_grid_gap_mobile', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('mobile', 'gap', newval);
            });
        });

        wp.customize('cct_grid_gap_tablet', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('tablet', 'gap', newval);
            });
        });

        wp.customize('cct_grid_gap_desktop', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('desktop', 'gap', newval);
            });
        });
    }

    /**
     * Atualiza CSS do grid responsivo
     */
    function updateResponsiveGridCSS(device, property, value) {
        var breakpoint = wp.customize('cct_breakpoint_' + device)() || defaultBreakpoints[device];
        var css = '';
        
        if (property === 'columns') {
            css = `
                @media (max-width: ${breakpoint}px) {
                    .cct-grid {
                        grid-template-columns: repeat(${value}, 1fr) !important;
                    }
                }
            `;
        } else if (property === 'gap') {
            css = `
                @media (max-width: ${breakpoint}px) {
                    .cct-grid {
                        gap: ${value}px !important;
                    }
                }
            `;
        }

        // Remove CSS anterior
        $('#cct-responsive-grid-' + device + '-' + property + '-css').remove();
        
        // Adiciona novo CSS
        if (css) {
            $('head').append('<style id="cct-responsive-grid-' + device + '-' + property + '-css">' + css + '</style>');
        }
    }

    /**
     * Monitora mudanças de tamanho da janela
     */
    function bindWindowResize() {
        var resizeTimer;
        
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                var currentWidth = $(window).width();
                var activeBreakpoint = getCurrentBreakpoint(currentWidth);
                
                // Atualiza indicador se existir
                if ($('.cct-breakpoint-indicator').length) {
                    $('.cct-breakpoint-indicator').html(`
                        <div>Largura: ${currentWidth}px</div>
                        <div>Breakpoint: ${activeBreakpoint}</div>
                    `);
                }
                
                // Trigger evento customizado
                $(document).trigger('cct-breakpoint-change', {
                    width: currentWidth,
                    breakpoint: activeBreakpoint
                });
            }, 250);
        });
    }

    /**
     * Inicialização
     */
    $(document).ready(function() {
        // Verificar se estamos no preview do customizer
        if (typeof wp.customize !== 'undefined') {
            bindBreakpointPreview();
            bindVisibilityPreview();
            bindResponsiveGridPreview();
            bindWindowResize();
            
            console.log('CCT Breakpoints Preview: Initialized');
        }
    });

})(jQuery);