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
 * @package UENF_Theme
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
class UENF_Color_Palette_Preview_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_color_palette_preview';
    
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
        
        <div class="uenf-palette-preview-container">
            <?php foreach ($this->palettes as $palette_id => $palette): ?>
                <div class="uenf-palette-option" data-palette="<?php echo esc_attr($palette_id); ?>">
                    <div class="uenf-palette-info">
                        <h4><?php echo esc_html($palette['name']); ?></h4>
                        <p><?php echo esc_html($palette['description']); ?></p>
                        <span class="uenf-palette-category"><?php echo esc_html(ucfirst($palette['category'])); ?></span>
                    </div>
                    
                    <div class="uenf-palette-colors">
                        <?php foreach ($palette['colors'] as $role => $color): ?>
                            <div class="uenf-color-swatch" 
                                 style="background-color: <?php echo esc_attr($color); ?>" 
                                 data-color="<?php echo esc_attr($color); ?>"
                                 data-role="<?php echo esc_attr($role); ?>"
                                 title="<?php echo esc_attr(ucfirst($role) . ': ' . $color); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="button uenf-apply-palette" data-palette="<?php echo esc_attr($palette_id); ?>">
                        <?php _e('Aplicar Paleta', 'cct'); ?>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        
        <style>
        .uenf-palette-preview-container {
            margin-top: 10px;
        }
        
        .uenf-palette-option {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .uenf-palette-option:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 8px rgba(0,115,170,0.1);
        }
        
        .uenf-palette-option.selected {
            border-color: #0073aa;
            background-color: #f0f8ff;
        }
        
        .uenf-palette-info h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: 600;
        }
        
        .uenf-palette-info p {
            margin: 0 0 8px 0;
            font-size: 12px;
            color: #666;
        }
        
        .uenf-palette-category {
            display: inline-block;
            background: #0073aa;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 500;
        }
        
        .uenf-palette-colors {
            display: flex;
            gap: 4px;
            margin: 12px 0;
            flex-wrap: wrap;
        }
        
        .uenf-color-swatch {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .uenf-color-swatch:hover {
            transform: scale(1.1);
        }
        
        .uenf-apply-palette {
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
class UENF_Color_Generator_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_color_generator';
    
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
        
        <div class="uenf-color-generator">
            <div class="uenf-generator-preview">
                <h4><?php _e('Paleta Gerada', 'cct'); ?></h4>
                <div class="uenf-generated-colors" id="uenf-generated-colors">
                    <!-- Cores geradas aparecerão aqui -->
                </div>
            </div>
            
            <div class="uenf-generator-controls">
                <button type="button" class="button button-primary uenf-generate-colors">
                    <?php _e('Gerar Nova Paleta', 'cct'); ?>
                </button>
                
                <button type="button" class="button uenf-apply-generated" style="display: none;">
                    <?php _e('Aplicar Paleta Gerada', 'cct'); ?>
                </button>
                
                <button type="button" class="button uenf-randomize-base">
                    <?php _e('Cor Base Aleatória', 'cct'); ?>
                </button>
            </div>
            
            <div class="uenf-harmony-info">
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
        .uenf-color-generator {
            margin-top: 10px;
        }
        
        .uenf-generator-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .uenf-generator-preview h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
        }
        
        .uenf-generated-colors {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            min-height: 40px;
            align-items: center;
        }
        
        .uenf-generated-color {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            position: relative;
            cursor: pointer;
        }
        
        .uenf-generated-color::after {
            content: attr(data-hex);
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            color: #666;
            white-space: nowrap;
        }
        
        .uenf-generator-controls {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .uenf-generator-controls .button {
            flex: 1;
            min-width: 120px;
        }
        
        .uenf-harmony-info {
            background: #f0f8ff;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 12px;
        }
        
        .uenf-harmony-info h4 {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-harmony-info ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .uenf-harmony-info li {
            margin-bottom: 4px;
            font-size: 11px;
            line-height: 1.4;
        }
        
        .uenf-harmony-info strong {
            color: #333;
        }
        </style>
        <?php
    }
}

/**
 * Controle Analisador de Contraste
 */
class UENF_Contrast_Analyzer_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_contrast_analyzer';
    
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
        
        <div class="uenf-contrast-analyzer">
            <div class="uenf-contrast-test">
                <h4><?php _e('Teste de Contraste', 'cct'); ?></h4>
                
                <div class="uenf-color-pair">
                    <div class="uenf-color-input">
                        <label><?php _e('Cor do Texto:', 'cct'); ?></label>
                        <input type="color" class="uenf-text-color" value="#333333">
                        <span class="uenf-color-value">#333333</span>
                    </div>
                    
                    <div class="uenf-color-input">
                        <label><?php _e('Cor de Fundo:', 'cct'); ?></label>
                        <input type="color" class="uenf-bg-color" value="#ffffff">
                        <span class="uenf-color-value">#ffffff</span>
                    </div>
                </div>
                
                <div class="uenf-contrast-preview">
                    <div class="uenf-preview-sample">
                        <h5><?php _e('Texto Normal', 'cct'); ?></h5>
                        <p><?php _e('Este é um exemplo de texto normal para testar a legibilidade.', 'cct'); ?></p>
                        
                        <h3><?php _e('Texto Grande', 'cct'); ?></h3>
                        <p style="font-size: 18px; font-weight: bold;"><?php _e('Este é um exemplo de texto grande e em negrito.', 'cct'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="uenf-contrast-results">
                <h4><?php _e('Resultados da Análise', 'cct'); ?></h4>
                
                <div class="uenf-contrast-ratio">
                    <span class="uenf-ratio-label"><?php _e('Razão de Contraste:', 'cct'); ?></span>
                    <span class="uenf-ratio-value">-</span>
                </div>
                
                <div class="uenf-wcag-compliance">
                    <div class="uenf-compliance-item">
                        <span class="uenf-compliance-label"><?php _e('WCAG AA (Texto Normal):', 'cct'); ?></span>
                        <span class="uenf-compliance-status uenf-aa-normal">-</span>
                    </div>
                    
                    <div class="uenf-compliance-item">
                        <span class="uenf-compliance-label"><?php _e('WCAG AA (Texto Grande):', 'cct'); ?></span>
                        <span class="uenf-compliance-status uenf-aa-large">-</span>
                    </div>
                    
                    <div class="uenf-compliance-item">
                        <span class="uenf-compliance-label"><?php _e('WCAG AAA (Texto Normal):', 'cct'); ?></span>
                        <span class="uenf-compliance-status uenf-aaa-normal">-</span>
                    </div>
                    
                    <div class="uenf-compliance-item">
                        <span class="uenf-compliance-label"><?php _e('WCAG AAA (Texto Grande):', 'cct'); ?></span>
                        <span class="uenf-compliance-status uenf-aaa-large">-</span>
                    </div>
                </div>
                
                <div class="uenf-recommendations">
                    <h5><?php _e('Recomendações:', 'cct'); ?></h5>
                    <div class="uenf-recommendation-text"></div>
                </div>
            </div>
            
            <div class="uenf-quick-tests">
                <h4><?php _e('Testes Rápidos', 'cct'); ?></h4>
                <button type="button" class="button uenf-test-primary"><?php _e('Testar Cor Primária', 'cct'); ?></button>
                <button type="button" class="button uenf-test-secondary"><?php _e('Testar Cor Secundária', 'cct'); ?></button>
                <button type="button" class="button uenf-test-all"><?php _e('Testar Todas as Cores', 'cct'); ?></button>
            </div>
        </div>
        
        <style>
        .uenf-contrast-analyzer {
            margin-top: 10px;
        }
        
        .uenf-contrast-test {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .uenf-contrast-test h4 {
            margin: 0 0 12px 0;
            font-size: 13px;
            font-weight: 600;
        }
        
        .uenf-color-pair {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .uenf-color-input {
            flex: 1;
        }
        
        .uenf-color-input label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .uenf-color-input input[type="color"] {
            width: 100%;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .uenf-color-value {
            display: block;
            font-size: 11px;
            color: #666;
            margin-top: 3px;
            text-align: center;
        }
        
        .uenf-contrast-preview {
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .uenf-preview-sample {
            padding: 15px;
            background: #ffffff;
            color: #333333;
        }
        
        .uenf-preview-sample h5 {
            margin: 0 0 8px 0;
            font-size: 14px;
        }
        
        .uenf-preview-sample h3 {
            margin: 15px 0 8px 0;
            font-size: 18px;
        }
        
        .uenf-preview-sample p {
            margin: 0;
            line-height: 1.5;
        }
        
        .uenf-contrast-results {
            background: #f0f8ff;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .uenf-contrast-results h4 {
            margin: 0 0 12px 0;
            font-size: 13px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-contrast-ratio {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px;
            background: white;
            border-radius: 4px;
        }
        
        .uenf-ratio-label {
            font-weight: 500;
            font-size: 12px;
        }
        
        .uenf-ratio-value {
            font-weight: 600;
            font-size: 14px;
            color: #0073aa;
        }
        
        .uenf-wcag-compliance {
            margin-bottom: 12px;
        }
        
        .uenf-compliance-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 8px;
            margin-bottom: 4px;
            background: white;
            border-radius: 4px;
            font-size: 11px;
        }
        
        .uenf-compliance-status {
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        .uenf-compliance-status.pass {
            background: #d4edda;
            color: #155724;
        }
        
        .uenf-compliance-status.fail {
            background: #f8d7da;
            color: #721c24;
        }
        
        .uenf-recommendations {
            background: white;
            border-radius: 4px;
            padding: 10px;
        }
        
        .uenf-recommendations h5 {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 600;
        }
        
        .uenf-recommendation-text {
            font-size: 11px;
            line-height: 1.4;
            color: #666;
        }
        
        .uenf-quick-tests {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .uenf-quick-tests h4 {
            width: 100%;
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 600;
        }
        
        .uenf-quick-tests .button {
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
class UENF_Smart_Color_Control extends WP_Customize_Color_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_smart_color';
    
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
            <div class="uenf-color-suggestions">
                <h5><?php _e('Cores Sugeridas:', 'cct'); ?></h5>
                <div class="uenf-suggestion-swatches">
                    <?php foreach ($this->suggested_colors as $color): ?>
                        <div class="uenf-suggestion-swatch" 
                             style="background-color: <?php echo esc_attr($color); ?>" 
                             data-color="<?php echo esc_attr($color); ?>"
                             title="<?php echo esc_attr($color); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <style>
            .uenf-color-suggestions {
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid #ddd;
            }
            
            .uenf-color-suggestions h5 {
                margin: 0 0 8px 0;
                font-size: 12px;
                font-weight: 600;
            }
            
            .uenf-suggestion-swatches {
                display: flex;
                gap: 4px;
                flex-wrap: wrap;
            }
            
            .uenf-suggestion-swatch {
                width: 24px;
                height: 24px;
                border-radius: 4px;
                border: 2px solid #fff;
                box-shadow: 0 1px 2px rgba(0,0,0,0.2);
                cursor: pointer;
                transition: transform 0.2s ease;
            }
            
            .uenf-suggestion-swatch:hover {
                transform: scale(1.2);
            }
            </style>
            <?php
        }
    }
}