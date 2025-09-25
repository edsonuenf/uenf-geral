/**
 * Customizer Layout Preview JavaScript
 * Preview em tempo real para configurações de layout
 */

(function($) {
    'use strict';

    // Aguardar o carregamento do customizer
    wp.customize = wp.customize || {};

    /**
     * Preview em tempo real para configurações de grid
     */
    function bindGridPreview() {
        // Grid columns
        wp.customize('cct_grid_columns', function(value) {
            value.bind(function(newval) {
                $('body').attr('data-grid-columns', newval);
                updateGridCSS();
            });
        });

        // Grid gap
        wp.customize('cct_grid_gap', function(value) {
            value.bind(function(newval) {
                $('body').attr('data-grid-gap', newval);
                updateGridCSS();
            });
        });

        // Grid max width
        wp.customize('cct_grid_max_width', function(value) {
            value.bind(function(newval) {
                $('body').attr('data-grid-max-width', newval);
                updateGridCSS();
            });
        });
    }

    /**
     * Preview em tempo real para configurações de container
     */
    function bindContainerPreview() {
        // Container width
        wp.customize('cct_container_width', function(value) {
            value.bind(function(newval) {
                $('.container, .cct-container').css('max-width', newval + 'px');
            });
        });

        // Container padding
        wp.customize('cct_container_padding', function(value) {
            value.bind(function(newval) {
                $('.container, .cct-container').css('padding', '0 ' + newval + 'px');
            });
        });

        // Container type
        wp.customize('cct_container_type', function(value) {
            value.bind(function(newval) {
                $('body').removeClass('container-fluid container-boxed container-full-width')
                         .addClass('container-' + newval);
            });
        });
    }

    /**
     * Preview em tempo real para espaçamentos
     */
    function bindSpacingPreview() {
        // Section spacing
        wp.customize('cct_section_spacing', function(value) {
            value.bind(function(newval) {
                $('.cct-section, section').css('margin-bottom', newval + 'px');
            });
        });

        // Element spacing
        wp.customize('cct_element_spacing', function(value) {
            value.bind(function(newval) {
                $('.cct-element').css('margin-bottom', newval + 'px');
            });
        });

        // Paragraph spacing
        wp.customize('cct_paragraph_spacing', function(value) {
            value.bind(function(newval) {
                $('p').css('margin-bottom', newval + 'px');
            });
        });
    }

    /**
     * Atualiza CSS do grid dinamicamente
     */
    function updateGridCSS() {
        var columns = $('body').attr('data-grid-columns') || '12';
        var gap = $('body').attr('data-grid-gap') || '20';
        var maxWidth = $('body').attr('data-grid-max-width') || '1200';

        var css = `
            .cct-grid {
                display: grid;
                grid-template-columns: repeat(${columns}, 1fr);
                gap: ${gap}px;
                max-width: ${maxWidth}px;
                margin: 0 auto;
            }
            
            .cct-grid-item {
                grid-column: span 1;
            }
            
            @media (max-width: 768px) {
                .cct-grid {
                    grid-template-columns: 1fr;
                }
            }
        `;

        // Remove CSS anterior
        $('#cct-grid-preview-css').remove();
        
        // Adiciona novo CSS
        $('head').append('<style id="cct-grid-preview-css">' + css + '</style>');
    }

    /**
     * Preview em tempo real para layout builder
     */
    function bindLayoutBuilderPreview() {
        // Layout structure
        wp.customize('cct_layout_structure', function(value) {
            value.bind(function(newval) {
                updateLayoutStructure(newval);
            });
        });

        // Sidebar position
        wp.customize('cct_sidebar_position', function(value) {
            value.bind(function(newval) {
                $('body').removeClass('sidebar-left sidebar-right sidebar-none')
                         .addClass('sidebar-' + newval);
            });
        });

        // Sidebar width
        wp.customize('cct_sidebar_width', function(value) {
            value.bind(function(newval) {
                $('.sidebar').css('width', newval + '%');
                $('.main-content').css('width', (100 - parseInt(newval)) + '%');
            });
        });
    }

    /**
     * Atualiza estrutura do layout
     */
    function updateLayoutStructure(structure) {
        var layouts = {
            'single-column': {
                main: '100%',
                sidebar: '0%'
            },
            'two-column-left': {
                main: '70%',
                sidebar: '30%',
                order: 'sidebar-first'
            },
            'two-column-right': {
                main: '70%',
                sidebar: '30%',
                order: 'main-first'
            },
            'three-column': {
                main: '50%',
                sidebar: '25%',
                secondary: '25%'
            }
        };

        var layout = layouts[structure] || layouts['single-column'];
        
        $('.main-content').css('width', layout.main);
        $('.sidebar').css('width', layout.sidebar);
        
        if (layout.secondary) {
            $('.secondary-sidebar').css('width', layout.secondary).show();
        } else {
            $('.secondary-sidebar').hide();
        }

        $('body').removeClass('layout-single layout-two-left layout-two-right layout-three')
                 .addClass('layout-' + structure.replace('-column', '').replace('-', '-'));
    }

    /**
     * Preview para configurações responsivas
     */
    function bindResponsivePreview() {
        // Mobile breakpoint
        wp.customize('cct_mobile_breakpoint', function(value) {
            value.bind(function(newval) {
                updateResponsiveCSS('mobile', newval);
            });
        });

        // Tablet breakpoint
        wp.customize('cct_tablet_breakpoint', function(value) {
            value.bind(function(newval) {
                updateResponsiveCSS('tablet', newval);
            });
        });

        // Desktop breakpoint
        wp.customize('cct_desktop_breakpoint', function(value) {
            value.bind(function(newval) {
                updateResponsiveCSS('desktop', newval);
            });
        });
    }

    /**
     * Atualiza CSS responsivo
     */
    function updateResponsiveCSS(device, breakpoint) {
        var css = '';
        
        switch(device) {
            case 'mobile':
                css = `@media (max-width: ${breakpoint}px) {
                    .cct-hide-mobile { display: none !important; }
                    .cct-grid { grid-template-columns: 1fr !important; }
                }`;
                break;
            case 'tablet':
                css = `@media (min-width: ${breakpoint}px) and (max-width: 1024px) {
                    .cct-hide-tablet { display: none !important; }
                    .cct-grid { grid-template-columns: repeat(2, 1fr) !important; }
                }`;
                break;
            case 'desktop':
                css = `@media (min-width: ${breakpoint}px) {
                    .cct-hide-desktop { display: none !important; }
                }`;
                break;
        }

        // Remove CSS anterior
        $('#cct-responsive-' + device + '-css').remove();
        
        // Adiciona novo CSS
        $('head').append('<style id="cct-responsive-' + device + '-css">' + css + '</style>');
    }

    /**
     * Inicialização
     */
    $(document).ready(function() {
        // Verificar se estamos no preview do customizer
        if (typeof wp.customize !== 'undefined') {
            bindGridPreview();
            bindContainerPreview();
            bindSpacingPreview();
            bindLayoutBuilderPreview();
            bindResponsivePreview();
            
            console.log('CCT Layout Preview: Initialized');
        }
    });

})(jQuery);
