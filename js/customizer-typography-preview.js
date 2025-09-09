/**
 * Customizer Typography Preview
 * 
 * Handles real-time preview of typography changes in the WordPress Customizer
 * 
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Wait for customizer to be ready
    wp.customize.bind('ready', function() {
        
        // Google Fonts settings
        wp.customize('cct_heading_font_family', function(value) {
            value.bind(function(newval) {
                if (newval && newval !== 'inherit') {
                    loadGoogleFont(newval);
                    $('h1, h2, h3, h4, h5, h6').css('font-family', newval + ', sans-serif');
                }
            });
        });
        
        wp.customize('cct_body_font_family', function(value) {
            value.bind(function(newval) {
                if (newval && newval !== 'inherit') {
                    loadGoogleFont(newval);
                    $('body, p, div, span').css('font-family', newval + ', sans-serif');
                }
            });
        });
        
        // Font pairing preset
        wp.customize('cct_font_pairing_preset', function(value) {
            value.bind(function(newval) {
                if (newval && newval !== 'theme_default') {
                    applyFontPairing(newval);
                }
            });
        });
        
        // Typography scale
        wp.customize('cct_typography_scale', function(value) {
            value.bind(function(newval) {
                if (newval) {
                    applyTypographyScale(newval);
                }
            });
        });
        
        // Base font size
        wp.customize('cct_base_font_size', function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('body').css('font-size', newval + 'px');
                    updateTypographyScale();
                }
            });
        });
        
        // Line height
        wp.customize('cct_line_height', function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('body, p').css('line-height', newval);
                }
            });
        });
        
        // Text max width
        wp.customize('cct_text_max_width', function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('.entry-content, .content-area p, .content-area li').css('max-width', newval + 'ch');
                }
            });
        });
    });
    
    /**
     * Load Google Font dynamically
     */
    function loadGoogleFont(fontFamily) {
        if (!fontFamily || fontFamily === 'inherit') {
            return;
        }
        
        var fontUrl = 'https://fonts.googleapis.com/css2?family=' + 
                     encodeURIComponent(fontFamily.replace(/\s+/g, '+')) + 
                     ':wght@300;400;500;600;700&display=swap';
        
        // Check if font is already loaded
        if (!$('link[href="' + fontUrl + '"]').length) {
            $('<link>', {
                rel: 'stylesheet',
                type: 'text/css',
                href: fontUrl
            }).appendTo('head');
        }
    }
    
    /**
     * Apply font pairing preset
     */
    function applyFontPairing(preset) {
        var pairings = {
            'classic': {
                heading: 'Playfair Display',
                body: 'Source Sans Pro'
            },
            'modern': {
                heading: 'Montserrat',
                body: 'Open Sans'
            },
            'elegant': {
                heading: 'Cormorant Garamond',
                body: 'Proza Libre'
            },
            'minimal': {
                heading: 'Work Sans',
                body: 'Work Sans'
            },
            'creative': {
                heading: 'Playfair Display',
                body: 'Source Sans Pro'
            },
            'editorial': {
                heading: 'Merriweather',
                body: 'PT Sans'
            },
            'tech': {
                heading: 'Orbitron',
                body: 'Roboto'
            }
        };
        
        if (pairings[preset]) {
            var pairing = pairings[preset];
            
            // Load fonts
            loadGoogleFont(pairing.heading);
            loadGoogleFont(pairing.body);
            
            // Apply fonts
            $('h1, h2, h3, h4, h5, h6').css('font-family', pairing.heading + ', serif');
            $('body, p, div, span').css('font-family', pairing.body + ', sans-serif');
        }
    }
    
    /**
     * Apply typography scale
     */
    function applyTypographyScale(scale) {
        var scales = {
            'minor_second': 1.067,
            'major_second': 1.125,
            'minor_third': 1.200,
            'major_third': 1.250,
            'perfect_fourth': 1.333,
            'augmented_fourth': 1.414,
            'perfect_fifth': 1.500,
            'golden_ratio': 1.618
        };
        
        if (scales[scale]) {
            var ratio = scales[scale];
            var baseSize = parseInt($('body').css('font-size')) || 16;
            
            $('h6').css('font-size', baseSize + 'px');
            $('h5').css('font-size', Math.round(baseSize * ratio) + 'px');
            $('h4').css('font-size', Math.round(baseSize * Math.pow(ratio, 2)) + 'px');
            $('h3').css('font-size', Math.round(baseSize * Math.pow(ratio, 3)) + 'px');
            $('h2').css('font-size', Math.round(baseSize * Math.pow(ratio, 4)) + 'px');
            $('h1').css('font-size', Math.round(baseSize * Math.pow(ratio, 5)) + 'px');
        }
    }
    
    /**
     * Update typography scale based on current base font size
     */
    function updateTypographyScale() {
        var currentScale = wp.customize('cct_typography_scale')();
        if (currentScale) {
            applyTypographyScale(currentScale);
        }
    }
    
})(jQuery);