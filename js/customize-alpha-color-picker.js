(function($) {
    'use strict';

    // Wait for the color picker to be initialized
    $(document).ready(function() {
        // Initialize color picker with alpha support
        $('.alpha-color-control').each(function() {
            var $input = $(this);
            var defaultColor = $input.data('default-color') || '';
            var palette = $input.data('palette') !== false ? true : false;
            
            // Initialize the color picker
            $input.wpColorPicker({
                // Enable alpha channel
                change: function(event, ui) {
                    var color = ui.color.toString();
                    
                    // If we have a valid color and it's not transparent
                    if (ui.color && ui.color._alpha !== 0) {
                        // If the color has transparency, use rgba
                        if (ui.color._alpha < 1) {
                            color = ui.color.toRgbString();
                        }
                    }
                    
                    // Update the input value
                    $input.val(color).trigger('change');
                },
                clear: function() {
                    $input.trigger('change');
                },
                palettes: palette,
                hide: true
            });
            
            // Add alpha slider
            var $container = $input.closest('.wp-picker-container');
            var $colorPicker = $container.find('.wp-picker-holder');
            
            // Only add the slider if it doesn't exist yet
            if ($container.find('.wp-picker-alpha').length === 0) {
                var $alpha = $('<div class="wp-picker-alpha"><div class="wp-picker-alpha-slider"></div></div>');
                $colorPicker.append($alpha);
                
                // Initialize the slider
                $alpha.slider({
                    min: 0,
                    max: 100,
                    value: 100,
                    slide: function(event, ui) {
                        var color = $input.wpColorPicker('color') || defaultColor;
                        var alpha = ui.value / 100;
                        
                        // Convert to RGBA
                        if (color) {
                            var rgba = $.wpColorPicker.color.toRgb(color);
                            rgba.a = alpha;
                            var rgbaString = 'rgba(' + rgba.r + ', ' + rgba.g + ', ' + rgba.b + ', ' + rgba.a + ')';
                            $input.wpColorPicker('color', rgbaString).trigger('change');
                        }
                    }
                });
                
                // Update slider when color changes
                $input.on('change', function() {
                    var color = $input.wpColorPicker('color') || defaultColor;
                    if (color) {
                        var rgba = $.wpColorPicker.color.toRgb(color);
                        $alpha.slider('value', Math.round(rgba.a * 100));
                    }
                });
                
                // Initialize slider with current value
                var initialColor = $input.val() || defaultColor;
                if (initialColor) {
                    var rgba = $.wpColorPicker.color.toRgb(initialColor);
                    if (rgba) {
                        $alpha.slider('value', Math.round(rgba.a * 100));
                    }
                }
            }
        });
    });
    
})(jQuery);
