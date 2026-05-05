<?php
/**
 * Controles Customizados Globais (Design System CCT)
 * 
 * Centraliza controles reutilizáveis para todos os módulos do Customizer.
 * 
 * @package UENF_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Controle de Range com Exibição de Valor em Tempo Real
 */
if (!class_exists('WP_Customize_Range_Value_Control')) {
    class WP_Customize_Range_Value_Control extends WP_Customize_Control {
        
        public $type = 'range_value';
        
        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
                
                <div class="range-value-wrapper">
                    <input type="range" 
                           <?php $this->input_attrs(); ?> 
                           value="<?php echo esc_attr($this->value()); ?>" 
                           <?php $this->link(); ?> 
                           class="range-input" />
                    <span class="range-value"><?php echo esc_html($this->value()); ?></span>
                </div>
            </label>
            
            <style>
            .range-value-wrapper {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-top: 5px;
            }
            .range-input { flex: 1; margin: 0; }
            .range-value {
                min-width: 40px;
                text-align: center;
                font-family: monospace;
                font-size: 13px;
                background: #f1f1f1;
                padding: 2px 6px;
                border-radius: 3px;
                border: 1px solid #ddd;
            }
            </style>
            
            <script>
            (function($) {
                var control = $(document).find('#customize-control-<?php echo $this->id; ?>');
                var rangeInput = control.find('.range-input');
                var valueDisplay = control.find('.range-value');
                
                rangeInput.on('input', function() {
                    valueDisplay.text($(this).val());
                });
            })(jQuery);
            </script>
            <?php
        }
    }
}
