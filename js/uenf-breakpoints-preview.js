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
        wp.customize('uenf_breakpoint_mobile', function(value) {
            value.bind(function(newval) {
                updateBreakpointCSS('mobile', newval);
                updateBreakpointIndicator('mobile', newval);
            });
        });

        // Tablet breakpoint
        wp.customize('uenf_breakpoint_tablet', function(value) {
            value.bind(function(newval) {
                updateBreakpointCSS('tablet', newval);
                updateBreakpointIndicator('tablet', newval);
            });
        });

        // Desktop breakpoint
        wp.customize('uenf_breakpoint_desktop', function(value) {
            value.bind(function(newval) {
                updateBreakpointCSS('desktop', newval);
                updateBreakpointIndicator('desktop', newval);
            });
        });

        // Wide breakpoint
        wp.customize('uenf_breakpoint_wide', function(value) {
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
                        .uenf-hide-mobile { display: none !important; }
                        .uenf-show-mobile { display: block !important; }
                        .uenf-grid { grid-template-columns: 1fr !important; }
                        .uenf-container { padding: 0 15px !important; }
                        .uenf-text-mobile-center { text-align: center !important; }
                        .uenf-text-mobile-left { text-align: left !important; }
                        .uenf-text-mobile-right { text-align: right !important; }
                    }
                `;
                break;
                
            case 'tablet':
                css = `
                    @media (min-width: ${parseInt(breakpoint) + 1}px) and (max-width: 1024px) {
                        .uenf-hide-tablet { display: none !important; }
                        .uenf-show-tablet { display: block !important; }
                        .uenf-grid { grid-template-columns: repeat(2, 1fr) !important; }
                        .uenf-container { padding: 0 20px !important; }
                    }
                `;
                break;
                
            case 'desktop':
                css = `
                    @media (min-width: ${breakpoint}px) {
                        .uenf-hide-desktop { display: none !important; }
                        .uenf-show-desktop { display: block !important; }
                        .uenf-container { max-width: ${breakpoint - 100}px !important; }
                    }
                `;
                break;
                
            case 'wide':
                css = `
                    @media (min-width: ${breakpoint}px) {
                        .uenf-hide-wide { display: none !important; }
                        .uenf-show-wide { display: block !important; }
                        .uenf-container { max-width: ${breakpoint - 200}px !important; }
                        .uenf-grid { grid-template-columns: repeat(4, 1fr) !important; }
                    }
                `;
                break;
        }

        // Remove CSS anterior
        $('#uenf-breakpoint-' + device + '-css').remove();
        
        // Adiciona novo CSS
        if (css) {
            $('head').append('<style id="uenf-breakpoint-' + device + '-css">' + css + '</style>');
        }
    }

    /**
     * Atualiza indicador visual do breakpoint
     */
    function updateBreakpointIndicator(device, breakpoint) {
        // Remove indicador anterior
        $('.uenf-breakpoint-indicator').remove();
        
        // Cria novo indicador
        var indicator = $('<div class="uenf-breakpoint-indicator">');
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
                mobile = wp.customize('uenf_breakpoint_mobile')() || defaultBreakpoints.mobile;
                tablet = wp.customize('uenf_breakpoint_tablet')() || defaultBreakpoints.tablet;
                desktop = wp.customize('uenf_breakpoint_desktop')() || defaultBreakpoints.desktop;
                wide = wp.customize('uenf_breakpoint_wide')() || defaultBreakpoints.wide;
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
            'uenf_show_mobile',
            'uenf_hide_mobile',
            'uenf_show_tablet',
            'uenf_hide_tablet',
            'uenf_show_desktop',
            'uenf_hide_desktop'
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
        var className = setting.replace('uenf_', 'uenf-');
        
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
        wp.customize('uenf_grid_columns_mobile', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('mobile', 'columns', newval);
            });
        });

        wp.customize('uenf_grid_columns_tablet', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('tablet', 'columns', newval);
            });
        });

        wp.customize('uenf_grid_columns_desktop', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('desktop', 'columns', newval);
            });
        });

        // Gap por dispositivo
        wp.customize('uenf_grid_gap_mobile', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('mobile', 'gap', newval);
            });
        });

        wp.customize('uenf_grid_gap_tablet', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('tablet', 'gap', newval);
            });
        });

        wp.customize('uenf_grid_gap_desktop', function(value) {
            value.bind(function(newval) {
                updateResponsiveGridCSS('desktop', 'gap', newval);
            });
        });
    }

    /**
     * Atualiza CSS do grid responsivo
     */
    function updateResponsiveGridCSS(device, property, value) {
        var breakpoint = wp.customize('uenf_breakpoint_' + device)() || defaultBreakpoints[device];
        var css = '';
        
        if (property === 'columns') {
            css = `
                @media (max-width: ${breakpoint}px) {
                    .uenf-grid {
                        grid-template-columns: repeat(${value}, 1fr) !important;
                    }
                }
            `;
        } else if (property === 'gap') {
            css = `
                @media (max-width: ${breakpoint}px) {
                    .uenf-grid {
                        gap: ${value}px !important;
                    }
                }
            `;
        }

        // Remove CSS anterior
        $('#uenf-responsive-grid-' + device + '-' + property + '-css').remove();
        
        // Adiciona novo CSS
        if (css) {
            $('head').append('<style id="uenf-responsive-grid-' + device + '-' + property + '-css">' + css + '</style>');
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
                if ($('.uenf-breakpoint-indicator').length) {
                    $('.uenf-breakpoint-indicator').html(`
                        <div>Largura: ${currentWidth}px</div>
                        <div>Breakpoint: ${activeBreakpoint}</div>
                    `);
                }
                
                // Trigger evento customizado
                $(document).trigger('uenf-breakpoint-change', {
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