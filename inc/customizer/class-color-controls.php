<?php
/**
 * Controles Personalizados para Gerenciador de Cores
 * 
 * Controles avançados para o sistema de cores incluindo:
 * - Preview de paletas com cores interativas
 * - Gerador de cores harmoniosas
 * - Analisador de contraste WCAG
 * - Seletor de cores com sugestões
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
 * Controle de Preview de Paleta de Cores
 */
class CCT_Color_Palette_Preview_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_color_palette_preview';
    
    /**
     * Paletas disponíveis
     * 
     * @var array
     */
    public $palettes = array();
    
    /**
     * Renderiza o controle
     */
    public function render_content() {
        ?>
        <label>
            <?php if (!empty($this->label)): ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            
            <?php if (!empty($this->description)): ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>
        
        <div class="cct-palette-preview-container">
            <?php foreach ($this->palettes as $palette_id => $palette): ?>
                <div class="cct-palette-option" data-palette="<?php echo esc_attr($palette_id); ?>">
                    <div class="cct-palette-info">
                        <h4><?php echo esc_html($palette['name']); ?></h4>
                        <p><?php echo esc_html($palette['description']); ?></p>
                        <span class="cct-palette-category"><?php echo esc_html(ucfirst($palette['category'])); ?></span>
                    </div>
                    
                    <div class="cct-palette-colors">
                        <?php foreach ($palette['colors'] as $role => $color): ?>
                            <div class="cct-color-swatch" 
                                 style="background-color: <?php echo esc_attr($color); ?>" 
                                 data-color="<?php echo esc_attr($color); ?>"
                                 data-role="<?php echo esc_attr($role); ?>"
                                 title="<?php echo esc_attr(ucfirst($role) . ': ' . $color); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="button cct-apply-palette" data-palette="<?php echo esc_attr($palette_id); ?>">
                        <?php _e('Aplicar Paleta', 'cct'); ?>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        
        <style>
        .cct-palette-preview-container {
            margin-top: 10px;
        }
        
        .cct-palette-option {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .cct-palette-option:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 8px rgba(0,115,170,0.1);
        }
        
        .cct-palette-option.selected {
            border-color: #0073aa;
            background-color: #f0f8ff;
        }
        
        .cct-palette-info h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: 600;
        }
        
        .cct-palette-info p {
            margin: 0 0 8px 0;
            font-size: 12px;
            color: #666;
        }
        
        .cct-palette-category {
            display: inline-block;
            background: #0073aa;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 500;
        }
        
        .cct-palette-colors {
            display: flex;
            gap: 4px;
            margin: 12px 0;
            flex-wrap: wrap;
        }
        
        .cct-color-swatch {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .cct-color-swatch:hover {
            transform: scale(1.1);
        }
        
        .cct-apply-palette {
            width: 100%;
            margin-top: 8px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Gerador de Cores
 */
class CCT_Color_Generator_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_color_generator';
    
    /**
     * Renderiza o controle
     */
    public function render_content() {
        ?>
        <label>
            <?php if (!empty($this->label)): ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            
            <?php if (!empty($this->description)): ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>
        
        <div class="cct-color-generator">
            <div class="cct-generator-preview">
                <h4><?php _e('Paleta Gerada', 'cct'); ?></h4>
                <div class="cct-generated-colors" id="cct-generated-colors">
                    <!-- Cores geradas aparecerão aqui -->
                </div>
            </div>
            
            <div class="cct-generator-controls">
                <button type="button" class="button button-primary cct-generate-colors">
                    <?php _e('Gerar Nova Paleta', 'cct'); ?>
                </button>
                
                <button type="button" class="button cct-apply-generated" style="display: none;">
                    <?php _e('Aplicar Paleta Gerada', 'cct'); ?>
                </button>
                
                <button type="button" class="button cct-randomize-base">
                    <?php _e('Cor Base Aleatória', 'cct'); ?>
                </button>
            </div>
            
            <div class="cct-harmony-info">
                <h4><?php _e('Tipos de Harmonia', 'cct'); ?></h4>
                <ul>
                    <li><strong><?php _e('Complementar:', 'cct'); ?></strong> <?php _e('Cores opostas no círculo cromático', 'cct'); ?></li>
                    <li><strong><?php _e('Análoga:', 'cct'); ?></strong> <?php _e('Cores adjacentes, harmonia suave', 'cct'); ?></li>
                    <li><strong><?php _e('Tríade:', 'cct'); ?></strong> <?php _e('Três cores equidistantes', 'cct'); ?></li>
                    <li><strong><?php _e('Tétrade:', 'cct'); ?></strong> <?php _e('Quatro cores em retângulo', 'cct'); ?></li>
                    <li><strong><?php _e('Monocromática:', 'cct'); ?></strong> <?php _e('Variações da mesma cor', 'cct'); ?></li>
                </ul>
            </div>
        </div>
        
        <style>
        .cct-color-generator {
            margin-top: 10px;
        }
        
        .cct-generator-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .cct-generator-preview h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
        }
        
        .cct-generated-colors {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            min-height: 40px;
            align-items: center;
        }
        
        .cct-generated-color {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            position: relative;
            cursor: pointer;
        }
        
        .cct-generated-color::after {
            content: attr(data-hex);
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            color: #666;
            white-space: nowrap;
        }
        
        .cct-generator-controls {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .cct-generator-controls .button {
            flex: 1;
            min-width: 120px;
        }
        
        .cct-harmony-info {
            background: #f0f8ff;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 12px;
        }
        
        .cct-harmony-info h4 {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .cct-harmony-info ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .cct-harmony-info li {
            margin-bottom: 4px;
            font-size: 11px;
            line-height: 1.4;
        }
        
        .cct-harmony-info strong {
            color: #333;
        }
        </style>
        <?php
    }
}

/**
 * Controle Analisador de Contraste
 */
class CCT_Contrast_Analyzer_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_contrast_analyzer';
    
    /**
     * Regras de acessibilidade
     * 
     * @var array
     */
    public $accessibility_rules = array();
    
    /**
     * Renderiza o controle
     */
    public function render_content() {
        ?>
        <label>
            <?php if (!empty($this->label)): ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            
            <?php if (!empty($this->description)): ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>
        
        <div class="cct-contrast-analyzer">
            <div class="cct-contrast-test">
                <h4><?php _e('Teste de Contraste', 'cct'); ?></h4>
                
                <div class="cct-color-pair">
                    <div class="cct-color-input">
                        <label><?php _e('Cor do Texto:', 'cct'); ?></label>
                        <input type="color" class="cct-text-color" value="#333333">
                        <span class="cct-color-value">#333333</span>
                    </div>
                    
                    <div class="cct-color-input">
                        <label><?php _e('Cor de Fundo:', 'cct'); ?></label>
                        <input type="color" class="cct-bg-color" value="#ffffff">
                        <span class="cct-color-value">#ffffff</span>
                    </div>
                </div>
                
                <div class="cct-contrast-preview">
                    <div class="cct-preview-sample">
                        <h5><?php _e('Texto Normal', 'cct'); ?></h5>
                        <p><?php _e('Este é um exemplo de texto normal para testar a legibilidade.', 'cct'); ?></p>
                        
                        <h3><?php _e('Texto Grande', 'cct'); ?></h3>
                        <p style="font-size: 18px; font-weight: bold;"><?php _e('Este é um exemplo de texto grande e em negrito.', 'cct'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="cct-contrast-results">
                <h4><?php _e('Resultados da Análise', 'cct'); ?></h4>
                
                <div class="cct-contrast-ratio">
                    <span class="cct-ratio-label"><?php _e('Razão de Contraste:', 'cct'); ?></span>
                    <span class="cct-ratio-value">-</span>
                </div>
                
                <div class="cct-wcag-compliance">
                    <div class="cct-compliance-item">
                        <span class="cct-compliance-label"><?php _e('WCAG AA (Texto Normal):', 'cct'); ?></span>
                        <span class="cct-compliance-status cct-aa-normal">-</span>
                    </div>
                    
                    <div class="cct-compliance-item">
                        <span class="cct-compliance-label"><?php _e('WCAG AA (Texto Grande):', 'cct'); ?></span>
                        <span class="cct-compliance-status cct-aa-large">-</span>
                    </div>
                    
                    <div class="cct-compliance-item">
                        <span class="cct-compliance-label"><?php _e('WCAG AAA (Texto Normal):', 'cct'); ?></span>
                        <span class="cct-compliance-status cct-aaa-normal">-</span>
                    </div>
                    
                    <div class="cct-compliance-item">
                        <span class="cct-compliance-label"><?php _e('WCAG AAA (Texto Grande):', 'cct'); ?></span>
                        <span class="cct-compliance-status cct-aaa-large">-</span>
                    </div>
                </div>
                
                <div class="cct-recommendations">
                    <h5><?php _e('Recomendações:', 'cct'); ?></h5>
                    <div class="cct-recommendation-text"></div>
                </div>
            </div>
            
            <div class="cct-quick-tests">
                <h4><?php _e('Testes Rápidos', 'cct'); ?></h4>
                <button type="button" class="button cct-test-primary"><?php _e('Testar Cor Primária', 'cct'); ?></button>
                <button type="button" class="button cct-test-secondary"><?php _e('Testar Cor Secundária', 'cct'); ?></button>
                <button type="button" class="button cct-test-all"><?php _e('Testar Todas as Cores', 'cct'); ?></button>
            </div>
        </div>
        
        <style>
        .cct-contrast-analyzer {
            margin-top: 10px;
        }
        
        .cct-contrast-test {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .cct-contrast-test h4 {
            margin: 0 0 12px 0;
            font-size: 13px;
            font-weight: 600;
        }
        
        .cct-color-pair {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .cct-color-input {
            flex: 1;
        }
        
        .cct-color-input label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .cct-color-input input[type="color"] {
            width: 100%;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .cct-color-value {
            display: block;
            font-size: 11px;
            color: #666;
            margin-top: 3px;
            text-align: center;
        }
        
        .cct-contrast-preview {
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .cct-preview-sample {
            padding: 15px;
            background: #ffffff;
            color: #333333;
        }
        
        .cct-preview-sample h5 {
            margin: 0 0 8px 0;
            font-size: 14px;
        }
        
        .cct-preview-sample h3 {
            margin: 15px 0 8px 0;
            font-size: 18px;
        }
        
        .cct-preview-sample p {
            margin: 0;
            line-height: 1.5;
        }
        
        .cct-contrast-results {
            background: #f0f8ff;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .cct-contrast-results h4 {
            margin: 0 0 12px 0;
            font-size: 13px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .cct-contrast-ratio {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px;
            background: white;
            border-radius: 4px;
        }
        
        .cct-ratio-label {
            font-weight: 500;
            font-size: 12px;
        }
        
        .cct-ratio-value {
            font-weight: 600;
            font-size: 14px;
            color: #0073aa;
        }
        
        .cct-wcag-compliance {
            margin-bottom: 12px;
        }
        
        .cct-compliance-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 8px;
            margin-bottom: 4px;
            background: white;
            border-radius: 4px;
            font-size: 11px;
        }
        
        .cct-compliance-status {
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        .cct-compliance-status.pass {
            background: #d4edda;
            color: #155724;
        }
        
        .cct-compliance-status.fail {
            background: #f8d7da;
            color: #721c24;
        }
        
        .cct-recommendations {
            background: white;
            border-radius: 4px;
            padding: 10px;
        }
        
        .cct-recommendations h5 {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 600;
        }
        
        .cct-recommendation-text {
            font-size: 11px;
            line-height: 1.4;
            color: #666;
        }
        
        .cct-quick-tests {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .cct-quick-tests h4 {
            width: 100%;
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 600;
        }
        
        .cct-quick-tests .button {
            flex: 1;
            min-width: 100px;
            font-size: 11px;
        }
        </style>
        <?php
    }
}

/**
 * Controle de Seletor de Cores com Sugestões
 */
class CCT_Smart_Color_Control extends WP_Customize_Color_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_smart_color';
    
    /**
     * Cores sugeridas
     * 
     * @var array
     */
    public $suggested_colors = array();
    
    /**
     * Renderiza o controle
     */
    public function render_content() {
        parent::render_content();
        
        if (!empty($this->suggested_colors)) {
            ?>
            <div class="cct-color-suggestions">
                <h5><?php _e('Cores Sugeridas:', 'cct'); ?></h5>
                <div class="cct-suggestion-swatches">
                    <?php foreach ($this->suggested_colors as $color): ?>
                        <div class="cct-suggestion-swatch" 
                             style="background-color: <?php echo esc_attr($color); ?>" 
                             data-color="<?php echo esc_attr($color); ?>"
                             title="<?php echo esc_attr($color); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <style>
            .cct-color-suggestions {
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid #ddd;
            }
            
            .cct-color-suggestions h5 {
                margin: 0 0 8px 0;
                font-size: 12px;
                font-weight: 600;
            }
            
            .cct-suggestion-swatches {
                display: flex;
                gap: 4px;
                flex-wrap: wrap;
            }
            
            .cct-suggestion-swatch {
                width: 24px;
                height: 24px;
                border-radius: 4px;
                border: 2px solid #fff;
                box-shadow: 0 1px 2px rgba(0,0,0,0.2);
                cursor: pointer;
                transition: transform 0.2s ease;
            }
            
            .cct-suggestion-swatch:hover {
                transform: scale(1.2);
            }
            </style>
            <?php
        }
    }
}