<?php
/**
 * Controles Personalizados para Tipografia
 * 
 * Controles especializados para preview de font pairing,
 * escala tipográfica e outras funcionalidades avançadas.
 * 
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

// Verificação de segurança
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Controle de Preview de Font Pairing
 */
class CCT_Typography_Preview_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'typography_preview';
    
    /**
     * Font pairings disponíveis
     * 
     * @var array
     */
    public $font_pairings = array();
    
    /**
     * Renderiza o controle
     */
    public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
        </label>
        
        <div class="cct-typography-preview" id="cct-typography-preview">
            <div class="preview-container">
                <div class="preview-heading" id="preview-heading">
                    <h2>Exemplo de Título Principal</h2>
                    <h3>Subtítulo Secundário</h3>
                </div>
                <div class="preview-body" id="preview-body">
                    <p>Este é um exemplo de parágrafo usando a fonte do corpo do texto. A tipografia é fundamental para a legibilidade e a experiência do usuário. Uma boa combinação de fontes cria hierarquia visual e melhora a comunicação.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>
                </div>
            </div>
            
            <div class="pairing-info" id="pairing-info">
                <div class="font-details">
                    <div class="heading-font">
                        <strong>Títulos:</strong> <span id="heading-font-name">-</span>
                    </div>
                    <div class="body-font">
                        <strong>Corpo:</strong> <span id="body-font-name">-</span>
                    </div>
                </div>
                <div class="pairing-category">
                    <span class="category-badge" id="category-badge">-</span>
                </div>
            </div>
        </div>
        
        <style>
        .cct-typography-preview {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            margin-top: 10px;
            background: #fff;
        }
        
        .preview-container {
            margin-bottom: 15px;
        }
        
        .preview-heading h2 {
            margin: 0 0 10px 0;
            font-size: 28px;
            line-height: 1.2;
        }
        
        .preview-heading h3 {
            margin: 0 0 15px 0;
            font-size: 20px;
            line-height: 1.3;
            color: #666;
        }
        
        .preview-body p {
            margin: 0 0 12px 0;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        
        .pairing-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 13px;
        }
        
        .font-details div {
            margin-bottom: 5px;
        }
        
        .category-badge {
            background: #1d3771;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        </style>
        
        <script>
        (function($) {
            function updateTypographyPreview() {
                var pairingValue = $('#customize-control-cct_font_pairing_preset select').val();
                var pairings = <?php echo json_encode($this->font_pairings); ?>;
                
                if (pairings[pairingValue]) {
                    var pairing = pairings[pairingValue];
                    
                    // Atualizar fontes no preview
                    $('#preview-heading').css('font-family', '"' + pairing.heading + '", sans-serif');
                    $('#preview-body').css('font-family', '"' + pairing.body + '", sans-serif');
                    
                    // Atualizar informações
                    $('#heading-font-name').text(pairing.heading);
                    $('#body-font-name').text(pairing.body);
                    $('#category-badge').text(pairing.category);
                    
                    // Carregar fontes do Google
                    loadGoogleFont(pairing.heading);
                    loadGoogleFont(pairing.body);
                }
            }
            
            function loadGoogleFont(fontFamily) {
                var linkId = 'google-font-' + fontFamily.replace(/\s+/g, '-').toLowerCase();
                
                if (!document.getElementById(linkId)) {
                    var link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = 'https://fonts.googleapis.com/css2?family=' + fontFamily.replace(/\s+/g, '+') + ':wght@300;400;500;600;700&display=swap';
                    document.head.appendChild(link);
                }
            }
            
            // Atualizar preview quando o valor mudar
            $('#customize-control-cct_font_pairing_preset select').on('change', updateTypographyPreview);
            
            // Atualizar preview inicial
            setTimeout(updateTypographyPreview, 100);
            
        })(jQuery);
        </script>
        <?php
    }
}

/**
 * Controle de Preview de Escala Tipográfica
 */
class CCT_Typography_Scale_Preview_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'typography_scale_preview';
    
    /**
     * Escalas tipográficas disponíveis
     * 
     * @var array
     */
    public $typography_scales = array();
    
    /**
     * Renderiza o controle
     */
    public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
        </label>
        
        <div class="cct-scale-preview" id="cct-scale-preview">
            <div class="scale-info">
                <div class="scale-name" id="scale-name">Segunda Maior</div>
                <div class="scale-ratio" id="scale-ratio">Proporção: 1.125</div>
            </div>
            
            <div class="scale-demonstration">
                <div class="scale-item" data-level="h1">
                    <span class="level-label">H1</span>
                    <span class="level-text" id="h1-text">Título Principal</span>
                    <span class="level-size" id="h1-size">48px</span>
                </div>
                <div class="scale-item" data-level="h2">
                    <span class="level-label">H2</span>
                    <span class="level-text" id="h2-text">Título Secundário</span>
                    <span class="level-size" id="h2-size">43px</span>
                </div>
                <div class="scale-item" data-level="h3">
                    <span class="level-label">H3</span>
                    <span class="level-text" id="h3-text">Título Terciário</span>
                    <span class="level-size" id="h3-size">38px</span>
                </div>
                <div class="scale-item" data-level="h4">
                    <span class="level-label">H4</span>
                    <span class="level-text" id="h4-text">Subtítulo</span>
                    <span class="level-size" id="h4-size">34px</span>
                </div>
                <div class="scale-item" data-level="body">
                    <span class="level-label">Body</span>
                    <span class="level-text" id="body-text">Texto do corpo</span>
                    <span class="level-size" id="body-size">16px</span>
                </div>
                <div class="scale-item" data-level="small">
                    <span class="level-label">Small</span>
                    <span class="level-text" id="small-text">Texto pequeno</span>
                    <span class="level-size" id="small-size">14px</span>
                </div>
            </div>
        </div>
        
        <style>
        .cct-scale-preview {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            margin-top: 10px;
            background: #fff;
        }
        
        .scale-info {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .scale-name {
            font-size: 16px;
            font-weight: 600;
            color: #1d3771;
            margin-bottom: 5px;
        }
        
        .scale-ratio {
            font-size: 13px;
            color: #666;
        }
        
        .scale-demonstration {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .scale-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .scale-item:last-child {
            border-bottom: none;
        }
        
        .level-label {
            width: 50px;
            font-size: 11px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .level-text {
            flex: 1;
            margin: 0 15px;
            font-weight: 500;
        }
        
        .level-size {
            font-size: 11px;
            color: #999;
            font-family: monospace;
            min-width: 40px;
            text-align: right;
        }
        
        .scale-item[data-level="h1"] .level-text { font-size: 32px; }
        .scale-item[data-level="h2"] .level-text { font-size: 28px; }
        .scale-item[data-level="h3"] .level-text { font-size: 24px; }
        .scale-item[data-level="h4"] .level-text { font-size: 20px; }
        .scale-item[data-level="body"] .level-text { font-size: 16px; }
        .scale-item[data-level="small"] .level-text { font-size: 14px; }
        </style>
        
        <script>
        (function($) {
            function updateScalePreview() {
                var scaleValue = $('#customize-control-cct_typography_scale select').val();
                var baseSize = parseInt($('#customize-control-cct_base_font_size input[type="range"]').val()) || 16;
                var scales = <?php echo json_encode($this->typography_scales); ?>;
                
                if (scales[scaleValue]) {
                    var scale = scales[scaleValue];
                    var ratio = scale.ratio;
                    
                    // Atualizar informações da escala
                    $('#scale-name').text(scale.name);
                    $('#scale-ratio').text('Proporção: ' + ratio);
                    
                    // Calcular e atualizar tamanhos
                    var h6Size = baseSize;
                    var h5Size = Math.round(baseSize * ratio);
                    var h4Size = Math.round(baseSize * Math.pow(ratio, 2));
                    var h3Size = Math.round(baseSize * Math.pow(ratio, 3));
                    var h2Size = Math.round(baseSize * Math.pow(ratio, 4));
                    var h1Size = Math.round(baseSize * Math.pow(ratio, 5));
                    var smallSize = Math.round(baseSize * 0.875);
                    
                    // Atualizar preview visual
                    $('.scale-item[data-level="h1"] .level-text').css('font-size', h1Size + 'px');
                    $('.scale-item[data-level="h2"] .level-text').css('font-size', h2Size + 'px');
                    $('.scale-item[data-level="h3"] .level-text').css('font-size', h3Size + 'px');
                    $('.scale-item[data-level="h4"] .level-text').css('font-size', h4Size + 'px');
                    $('.scale-item[data-level="body"] .level-text').css('font-size', baseSize + 'px');
                    $('.scale-item[data-level="small"] .level-text').css('font-size', smallSize + 'px');
                    
                    // Atualizar labels de tamanho
                    $('#h1-size').text(h1Size + 'px');
                    $('#h2-size').text(h2Size + 'px');
                    $('#h3-size').text(h3Size + 'px');
                    $('#h4-size').text(h4Size + 'px');
                    $('#body-size').text(baseSize + 'px');
                    $('#small-size').text(smallSize + 'px');
                }
            }
            
            // Atualizar preview quando os valores mudarem
            $('#customize-control-cct_typography_scale select').on('change', updateScalePreview);
            $('#customize-control-cct_base_font_size input[type="range"]').on('input', updateScalePreview);
            
            // Atualizar preview inicial
            setTimeout(updateScalePreview, 100);
            
        })(jQuery);
        </script>
        <?php
    }
}

/**
 * Controle de Range com Valor
 */
if (!class_exists('WP_Customize_Range_Value_Control')) {
    class WP_Customize_Range_Value_Control extends WP_Customize_Control {
        
        /**
         * Tipo do controle
         * 
         * @var string
         */
        public $type = 'range_value';
        
        /**
         * Renderiza o controle
         */
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
            
            .range-input {
                flex: 1;
                margin: 0;
            }
            
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

/**
 * Controle de Upload de Fonte
 */
class CCT_Font_Upload_Control extends WP_Customize_Upload_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'font_upload';
    
    /**
     * Tipos de arquivo aceitos
     * 
     * @var array
     */
    public $mime_type = array(
        'font/woff2',
        'font/woff',
        'font/ttf',
        'font/otf',
        'application/font-woff2',
        'application/font-woff',
        'application/x-font-ttf',
        'application/x-font-otf'
    );
    
    /**
     * Extensões aceitas
     * 
     * @var string
     */
    public $extensions = array('woff2', 'woff', 'ttf', 'otf');
    
    /**
     * Renderiza o controle
     */
    public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
        </label>
        
        <div class="font-upload-container">
            <?php if ($this->value()) : ?>
                <div class="font-preview">
                    <div class="font-info">
                        <strong>Fonte carregada:</strong> <?php echo basename($this->value()); ?>
                    </div>
                    <div class="font-demo" style="font-family: 'CustomFont'; font-size: 18px; margin: 10px 0;">
                        Exemplo de texto com a fonte personalizada
                    </div>
                    <style>
                    @font-face {
                        font-family: 'CustomFont';
                        src: url('<?php echo esc_url($this->value()); ?>');
                        font-display: swap;
                    }
                    </style>
                </div>
            <?php endif; ?>
            
            <div class="upload-controls">
                <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" />
                <button type="button" class="button upload-button">
                    <?php echo $this->value() ? 'Alterar Fonte' : 'Selecionar Fonte'; ?>
                </button>
                <?php if ($this->value()) : ?>
                    <button type="button" class="button remove-button">Remover</button>
                <?php endif; ?>
            </div>
        </div>
        
        <script>
        (function($) {
            var control = $('#customize-control-<?php echo $this->id; ?>');
            var uploadButton = control.find('.upload-button');
            var removeButton = control.find('.remove-button');
            var hiddenInput = control.find('input[type="hidden"]');
            
            uploadButton.on('click', function(e) {
                e.preventDefault();
                
                var mediaUploader = wp.media({
                    title: 'Selecionar Fonte',
                    button: {
                        text: 'Usar esta fonte'
                    },
                    library: {
                        type: ['font/woff2', 'font/woff', 'font/ttf', 'font/otf']
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    hiddenInput.val(attachment.url).trigger('change');
                    location.reload(); // Recarregar para mostrar preview
                });
                
                mediaUploader.open();
            });
            
            removeButton.on('click', function(e) {
                e.preventDefault();
                hiddenInput.val('').trigger('change');
                location.reload();
            });
            
        })(jQuery);
        </script>
        
        <style>
        .font-upload-container {
            margin-top: 10px;
        }
        
        .font-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 10px;
        }
        
        .font-info {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .font-demo {
            border: 1px solid #eee;
            background: white;
            padding: 10px;
            border-radius: 3px;
        }
        
        .upload-controls {
            display: flex;
            gap: 10px;
        }
        
        .remove-button {
            color: #a00;
        }
        </style>
        <?php
    }
}