<?php
/**
 * Controles Personalizados para Sistema de Sombras
 * 
 * Controles avançados para o gerenciador de sombras incluindo:
 * - Preview visual de níveis de elevação
 * - Configurador interativo de sombras
 * - Seletor de presets com preview
 * - Editor de configurações avançadas
 * - Demonstração de casos de uso
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
 * Controle Preview de Elevação
 */
class CCT_Elevation_Preview_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_elevation_preview';
    
    /**
     * Níveis de elevação
     * 
     * @var array
     */
    public $elevation_levels = array();
    
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
        
        <div class="cct-elevation-preview">
            <!-- Grid de níveis de elevação -->
            <div class="cct-elevation-grid">
                <?php foreach ($this->elevation_levels as $level => $data): ?>
                    <div class="cct-elevation-item" data-level="<?php echo esc_attr($level); ?>">
                        <div class="cct-elevation-demo" 
                             style="box-shadow: <?php echo esc_attr($data['shadow']); ?>">
                            <div class="cct-elevation-content">
                                <span class="cct-elevation-level"><?php echo esc_html($level); ?></span>
                                <span class="cct-elevation-name"><?php echo esc_html($data['name']); ?></span>
                            </div>
                        </div>
                        
                        <div class="cct-elevation-info">
                            <h5><?php echo esc_html($data['name']); ?></h5>
                            <p class="cct-elevation-description"><?php echo esc_html($data['description']); ?></p>
                            
                            <div class="cct-elevation-use-cases">
                                <strong><?php _e('Casos de uso:', 'cct'); ?></strong>
                                <ul>
                                    <?php foreach ($data['use_cases'] as $use_case): ?>
                                        <li><?php echo esc_html($use_case); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <div class="cct-elevation-actions">
                                <button type="button" class="button cct-apply-elevation" 
                                        data-level="<?php echo esc_attr($level); ?>">
                                    <?php _e('Aplicar', 'cct'); ?>
                                </button>
                                <button type="button" class="button cct-copy-css" 
                                        data-css="<?php echo esc_attr($data['shadow']); ?>">
                                    <?php _e('📋 CSS', 'cct'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Demonstração interativa -->
            <div class="cct-elevation-demo-area">
                <h4><?php _e('Demonstração Interativa', 'cct'); ?></h4>
                
                <div class="cct-demo-stage">
                    <div class="cct-demo-element" id="cct-demo-element">
                        <h3><?php _e('Elemento de Demonstração', 'cct'); ?></h3>
                        <p><?php _e('Clique nos níveis acima para ver o efeito', 'cct'); ?></p>
                    </div>
                </div>
                
                <div class="cct-demo-controls">
                    <label><?php _e('Nível atual:', 'cct'); ?></label>
                    <select id="cct-demo-level-select">
                        <?php foreach ($this->elevation_levels as $level => $data): ?>
                            <option value="<?php echo esc_attr($level); ?>">
                                <?php echo esc_html($data['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <label>
                        <input type="checkbox" id="cct-demo-hover" checked>
                        <?php _e('Efeito de hover', 'cct'); ?>
                    </label>
                </div>
            </div>
        </div>
        
        <style>
        .cct-elevation-preview {
            margin-top: 10px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .cct-elevation-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .cct-elevation-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .cct-elevation-item:hover {
            transform: translateY(-2px);
        }
        
        .cct-elevation-demo {
            width: 100%;
            height: 80px;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .cct-elevation-demo:hover {
            transform: translateY(-1px);
        }
        
        .cct-elevation-content {
            text-align: center;
        }
        
        .cct-elevation-level {
            display: block;
            font-size: 18px;
            font-weight: bold;
            color: #0073aa;
            margin-bottom: 4px;
        }
        
        .cct-elevation-name {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .cct-elevation-info h5 {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #333;
        }
        
        .cct-elevation-description {
            font-size: 11px;
            color: #666;
            margin: 0 0 10px 0;
            line-height: 1.4;
        }
        
        .cct-elevation-use-cases {
            margin-bottom: 15px;
            font-size: 10px;
        }
        
        .cct-elevation-use-cases strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        
        .cct-elevation-use-cases ul {
            margin: 0;
            padding-left: 15px;
            color: #666;
        }
        
        .cct-elevation-use-cases li {
            margin-bottom: 2px;
        }
        
        .cct-elevation-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        
        .cct-elevation-actions .button {
            font-size: 10px;
            padding: 6px 12px;
            height: auto;
        }
        
        .cct-apply-elevation {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        .cct-copy-css {
            background: #f0f0f1;
            color: #333;
        }
        
        .cct-elevation-demo-area {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
        .cct-elevation-demo-area h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #333;
        }
        
        .cct-demo-stage {
            background: #f8f9fa;
            padding: 40px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .cct-demo-element {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            max-width: 250px;
        }
        
        .cct-demo-element h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #333;
        }
        
        .cct-demo-element p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        
        .cct-demo-element:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        }
        
        .cct-demo-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .cct-demo-controls label {
            font-size: 12px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .cct-demo-controls select {
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .cct-demo-controls input[type="checkbox"] {
            margin: 0;
        }
        </style>
        <?php
    }
}

/**
 * Controle Configurador de Sombras
 */
class CCT_Shadow_Configurator_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_shadow_configurator';
    
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
        
        <div class="cct-shadow-configurator">
            <!-- Preview da sombra -->
            <div class="cct-shadow-preview-area">
                <div class="cct-shadow-preview-box" id="cct-shadow-preview">
                    <div class="cct-preview-content">
                        <h3><?php _e('Preview da Sombra', 'cct'); ?></h3>
                        <p><?php _e('Ajuste as configurações abaixo', 'cct'); ?></p>
                    </div>
                </div>
                
                <div class="cct-shadow-code">
                    <label><?php _e('Código CSS:', 'cct'); ?></label>
                    <textarea id="cct-shadow-css" readonly></textarea>
                    <button type="button" class="button cct-copy-shadow-css"><?php _e('📋 Copiar CSS', 'cct'); ?></button>
                </div>
            </div>
            
            <!-- Configurações da sombra -->
            <div class="cct-shadow-settings">
                <div class="cct-setting-group">
                    <label><?php _e('Offset X:', 'cct'); ?></label>
                    <div class="cct-range-control">
                        <input type="range" id="cct-shadow-x" min="-50" max="50" value="0">
                        <span id="cct-shadow-x-value">0px</span>
                    </div>
                </div>
                
                <div class="cct-setting-group">
                    <label><?php _e('Offset Y:', 'cct'); ?></label>
                    <div class="cct-range-control">
                        <input type="range" id="cct-shadow-y" min="-50" max="50" value="4">
                        <span id="cct-shadow-y-value">4px</span>
                    </div>
                </div>
                
                <div class="cct-setting-group">
                    <label><?php _e('Blur:', 'cct'); ?></label>
                    <div class="cct-range-control">
                        <input type="range" id="cct-shadow-blur" min="0" max="100" value="8">
                        <span id="cct-shadow-blur-value">8px</span>
                    </div>
                </div>
                
                <div class="cct-setting-group">
                    <label><?php _e('Spread:', 'cct'); ?></label>
                    <div class="cct-range-control">
                        <input type="range" id="cct-shadow-spread" min="-20" max="20" value="0">
                        <span id="cct-shadow-spread-value">0px</span>
                    </div>
                </div>
                
                <div class="cct-setting-group">
                    <label><?php _e('Cor:', 'cct'); ?></label>
                    <input type="color" id="cct-shadow-color" value="#000000">
                </div>
                
                <div class="cct-setting-group">
                    <label><?php _e('Opacidade:', 'cct'); ?></label>
                    <div class="cct-range-control">
                        <input type="range" id="cct-shadow-opacity" min="0" max="1" step="0.01" value="0.25">
                        <span id="cct-shadow-opacity-value">25%</span>
                    </div>
                </div>
            </div>
            
            <!-- Múltiplas sombras -->
            <div class="cct-multiple-shadows">
                <h4><?php _e('Múltiplas Sombras', 'cct'); ?></h4>
                
                <div class="cct-shadow-layers" id="cct-shadow-layers">
                    <div class="cct-shadow-layer" data-layer="0">
                        <span class="cct-layer-label"><?php _e('Camada 1', 'cct'); ?></span>
                        <button type="button" class="cct-remove-layer" disabled>×</button>
                    </div>
                </div>
                
                <div class="cct-shadow-actions">
                    <button type="button" class="button cct-add-layer"><?php _e('+ Adicionar Camada', 'cct'); ?></button>
                    <button type="button" class="button cct-reset-shadows"><?php _e('🔄 Reset', 'cct'); ?></button>
                </div>
            </div>
            
            <!-- Presets rápidos -->
            <div class="cct-shadow-presets">
                <h4><?php _e('Presets Rápidos', 'cct'); ?></h4>
                
                <div class="cct-preset-grid">
                    <button type="button" class="cct-preset-btn" data-preset="subtle">
                        <div class="cct-preset-preview" style="box-shadow: 0 1px 3px rgba(0,0,0,0.12);"></div>
                        <span><?php _e('Sutil', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="cct-preset-btn" data-preset="soft">
                        <div class="cct-preset-preview" style="box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                        <span><?php _e('Suave', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="cct-preset-btn" data-preset="medium">
                        <div class="cct-preset-preview" style="box-shadow: 0 10px 15px rgba(0,0,0,0.1);"></div>
                        <span><?php _e('Médio', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="cct-preset-btn" data-preset="large">
                        <div class="cct-preset-preview" style="box-shadow: 0 20px 25px rgba(0,0,0,0.15);"></div>
                        <span><?php _e('Grande', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="cct-preset-btn" data-preset="colored">
                        <div class="cct-preset-preview" style="box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);"></div>
                        <span><?php _e('Colorido', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="cct-preset-btn" data-preset="inset">
                        <div class="cct-preset-preview" style="box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);"></div>
                        <span><?php _e('Interno', 'cct'); ?></span>
                    </button>
                </div>
            </div>
        </div>
        
        <style>
        .cct-shadow-configurator {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .cct-shadow-preview-area {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-shadow-preview-box {
            height: 150px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .cct-preview-content {
            text-align: center;
            color: #333;
        }
        
        .cct-preview-content h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        
        .cct-preview-content p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        
        .cct-shadow-code {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cct-shadow-code label {
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-shadow-code textarea {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            font-size: 11px;
            resize: none;
            height: 40px;
        }
        
        .cct-copy-shadow-css {
            font-size: 11px;
            padding: 8px 12px;
        }
        
        .cct-shadow-settings {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-setting-group {
            margin-bottom: 20px;
        }
        
        .cct-setting-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .cct-range-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cct-range-control input[type="range"] {
            flex: 1;
        }
        
        .cct-range-control span {
            min-width: 50px;
            font-size: 12px;
            font-weight: 600;
            color: #0073aa;
            text-align: right;
        }
        
        .cct-setting-group input[type="color"] {
            width: 50px;
            height: 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .cct-multiple-shadows {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-multiple-shadows h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-shadow-layers {
            margin-bottom: 15px;
        }
        
        .cct-shadow-layer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        
        .cct-layer-label {
            font-size: 12px;
            color: #333;
        }
        
        .cct-remove-layer {
            width: 20px;
            height: 20px;
            border: none;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .cct-remove-layer:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .cct-shadow-actions {
            display: flex;
            gap: 10px;
        }
        
        .cct-shadow-actions .button {
            font-size: 11px;
            padding: 8px 12px;
        }
        
        .cct-shadow-presets {
            padding: 20px;
            background: white;
        }
        
        .cct-shadow-presets h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-preset-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .cct-preset-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 10px;
            border: 1px solid #ddd;
            background: #f8f9fa;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cct-preset-btn:hover {
            border-color: #0073aa;
            background: #f0f8ff;
        }
        
        .cct-preset-preview {
            width: 40px;
            height: 25px;
            background: white;
            border-radius: 4px;
        }
        
        .cct-preset-btn span {
            font-size: 10px;
            color: #333;
            text-align: center;
        }
        </style>
        <?php
    }
}

/**
 * Controle Seletor de Presets
 */
class CCT_Shadow_Preset_Selector_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_shadow_preset_selector';
    
    /**
     * Presets de sombras
     * 
     * @var array
     */
    public $shadow_presets = array();
    
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
        
        <div class="cct-shadow-preset-selector">
            <div class="cct-preset-options">
                <?php foreach ($this->shadow_presets as $preset_key => $preset): ?>
                    <div class="cct-preset-option" data-preset="<?php echo esc_attr($preset_key); ?>">
                        <div class="cct-preset-demo" 
                             style="background: <?php echo esc_attr($preset['color']); ?>; opacity: <?php echo esc_attr($preset['opacity']); ?>">
                            <div class="cct-preset-sample"></div>
                        </div>
                        
                        <div class="cct-preset-info">
                            <h5><?php echo esc_html($preset['name']); ?></h5>
                            <p><?php echo esc_html($preset['description']); ?></p>
                            
                            <div class="cct-preset-meta">
                                <span class="cct-preset-style">
                                    <strong><?php _e('Estilo:', 'cct'); ?></strong> 
                                    <?php echo esc_html(ucfirst($preset['style'])); ?>
                                </span>
                                
                                <span class="cct-preset-opacity">
                                    <strong><?php _e('Opacidade:', 'cct'); ?></strong> 
                                    <?php echo esc_html(round($preset['opacity'] * 100)); ?>%
                                </span>
                            </div>
                            
                            <div class="cct-preset-actions">
                                <button type="button" class="button cct-apply-preset" 
                                        data-preset="<?php echo esc_attr($preset_key); ?>">
                                    <?php _e('Aplicar', 'cct'); ?>
                                </button>
                                <button type="button" class="button cct-preview-preset" 
                                        data-preset="<?php echo esc_attr($preset_key); ?>">
                                    <?php _e('👁️ Preview', 'cct'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Configurações do preset ativo -->
            <div class="cct-active-preset-config">
                <h4><?php _e('Configurações do Preset Ativo', 'cct'); ?></h4>
                
                <div class="cct-config-row">
                    <label><?php _e('Intensidade:', 'cct'); ?></label>
                    <input type="range" class="cct-preset-intensity" min="0.5" max="2" step="0.1" value="1">
                    <span class="cct-intensity-value">100%</span>
                </div>
                
                <div class="cct-config-row">
                    <label><?php _e('Cor personalizada:', 'cct'); ?></label>
                    <input type="color" class="cct-preset-color" value="#000000">
                    <button type="button" class="button cct-reset-color"><?php _e('Reset', 'cct'); ?></button>
                </div>
                
                <div class="cct-config-row">
                    <label class="cct-checkbox-label">
                        <input type="checkbox" class="cct-enable-custom" checked>
                        <?php _e('Permitir customização', 'cct'); ?>
                    </label>
                </div>
            </div>
        </div>
        
        <style>
        .cct-shadow-preset-selector {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .cct-preset-options {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            padding: 20px;
            background: #f9f9f9;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .cct-preset-option {
            display: flex;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .cct-preset-option:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 8px rgba(0, 115, 170, 0.1);
        }
        
        .cct-preset-option.active {
            border-color: #0073aa;
            background: #f0f8ff;
        }
        
        .cct-preset-demo {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            flex-shrink: 0;
        }
        
        .cct-preset-sample {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .cct-preset-info {
            flex: 1;
            padding: 15px;
        }
        
        .cct-preset-info h5 {
            margin: 0 0 5px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-preset-info p {
            margin: 0 0 10px 0;
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }
        
        .cct-preset-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 10px;
            color: #888;
        }
        
        .cct-preset-actions {
            display: flex;
            gap: 8px;
        }
        
        .cct-preset-actions .button {
            font-size: 10px;
            padding: 6px 10px;
            height: auto;
        }
        
        .cct-apply-preset {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        .cct-active-preset-config {
            padding: 20px;
            background: white;
            border-top: 1px solid #ddd;
        }
        
        .cct-active-preset-config h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-config-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .cct-config-row label {
            min-width: 120px;
            font-size: 12px;
            color: #333;
        }
        
        .cct-config-row input[type="range"] {
            flex: 1;
        }
        
        .cct-config-row input[type="color"] {
            width: 40px;
            height: 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .cct-intensity-value {
            min-width: 40px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .cct-reset-color {
            font-size: 10px;
            padding: 4px 8px;
        }
        
        .cct-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            cursor: pointer;
        }
        
        .cct-checkbox-label input[type="checkbox"] {
            margin: 0;
        }
        </style>
        <?php
    }
}

/**
 * Controle Demonstração de Casos de Uso
 */
class CCT_Shadow_Use_Cases_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_shadow_use_cases';
    
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
        
        <div class="cct-shadow-use-cases">
            <!-- Casos de uso por categoria -->
            <div class="cct-use-case-categories">
                <div class="cct-use-case-category">
                    <h4><?php _e('Interface de Usuário', 'cct'); ?></h4>
                    
                    <div class="cct-use-case-examples">
                        <div class="cct-use-case-example">
                            <div class="cct-example-demo cct-elevation-2">
                                <span><?php _e('Card de Conteúdo', 'cct'); ?></span>
                            </div>
                            <p><?php _e('Elevação 2 - Para separar conteúdo', 'cct'); ?></p>
                        </div>
                        
                        <div class="cct-use-case-example">
                            <button class="cct-example-demo cct-elevation-4">
                                <?php _e('Botão Primário', 'cct'); ?>
                            </button>
                            <p><?php _e('Elevação 4 - Para elementos interativos', 'cct'); ?></p>
                        </div>
                        
                        <div class="cct-use-case-example">
                            <div class="cct-example-demo cct-elevation-8">
                                <span><?php _e('Modal/Dialog', 'cct'); ?></span>
                            </div>
                            <p><?php _e('Elevação 8 - Para overlays importantes', 'cct'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="cct-use-case-category">
                    <h4><?php _e('Navegação', 'cct'); ?></h4>
                    
                    <div class="cct-use-case-examples">
                        <div class="cct-use-case-example">
                            <div class="cct-example-demo cct-elevation-12">
                                <span><?php _e('Menu Dropdown', 'cct'); ?></span>
                            </div>
                            <p><?php _e('Elevação 12 - Para menus suspensos', 'cct'); ?></p>
                        </div>
                        
                        <div class="cct-use-case-example">
                            <div class="cct-example-demo cct-elevation-16">
                                <span><?php _e('Tooltip', 'cct'); ?></span>
                            </div>
                            <p><?php _e('Elevação 16 - Para dicas contextuais', 'cct'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="cct-use-case-category">
                    <h4><?php _e('Estados Interativos', 'cct'); ?></h4>
                    
                    <div class="cct-use-case-examples">
                        <div class="cct-use-case-example">
                            <div class="cct-example-demo cct-elevation-2 cct-elevation-hover-2">
                                <span><?php _e('Card Hover', 'cct'); ?></span>
                            </div>
                            <p><?php _e('Hover: 2 → 4 - Feedback visual', 'cct'); ?></p>
                        </div>
                        
                        <div class="cct-use-case-example">
                            <button class="cct-example-demo cct-elevation-4 cct-elevation-hover-4">
                                <?php _e('Botão Hover', 'cct'); ?>
                            </button>
                            <p><?php _e('Hover: 4 → 6 - Interação clara', 'cct'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Guia de boas práticas -->
            <div class="cct-best-practices">
                <h4><?php _e('Boas Práticas', 'cct'); ?></h4>
                
                <div class="cct-practice-tips">
                    <div class="cct-tip">
                        <span class="cct-tip-icon">💡</span>
                        <div class="cct-tip-content">
                            <strong><?php _e('Hierarquia Visual', 'cct'); ?></strong>
                            <p><?php _e('Use elevações crescentes para criar hierarquia clara entre elementos.', 'cct'); ?></p>
                        </div>
                    </div>
                    
                    <div class="cct-tip">
                        <span class="cct-tip-icon">⚡</span>
                        <div class="cct-tip-content">
                            <strong><?php _e('Performance', 'cct'); ?></strong>
                            <p><?php _e('Evite usar muitas elevações altas simultaneamente para manter boa performance.', 'cct'); ?></p>
                        </div>
                    </div>
                    
                    <div class="cct-tip">
                        <span class="cct-tip-icon">🎨</span>
                        <div class="cct-tip-content">
                            <strong><?php _e('Consistência', 'cct'); ?></strong>
                            <p><?php _e('Mantenha o mesmo nível de elevação para elementos similares.', 'cct'); ?></p>
                        </div>
                    </div>
                    
                    <div class="cct-tip">
                        <span class="cct-tip-icon">📱</span>
                        <div class="cct-tip-content">
                            <strong><?php _e('Mobile', 'cct'); ?></strong>
                            <p><?php _e('Reduza elevações em dispositivos móveis para melhor performance.', 'cct'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .cct-shadow-use-cases {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .cct-use-case-categories {
            padding: 20px;
            background: #f9f9f9;
        }
        
        .cct-use-case-category {
            margin-bottom: 30px;
        }
        
        .cct-use-case-category:last-child {
            margin-bottom: 0;
        }
        
        .cct-use-case-category h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 5px;
        }
        
        .cct-use-case-examples {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .cct-use-case-example {
            text-align: center;
        }
        
        .cct-example-demo {
            width: 100%;
            height: 60px;
            background: white;
            border: none;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            cursor: pointer;
            font-size: 12px;
            color: #333;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .cct-use-case-example p {
            margin: 0;
            font-size: 10px;
            color: #666;
            line-height: 1.3;
        }
        
        .cct-best-practices {
            padding: 20px;
            background: white;
            border-top: 1px solid #ddd;
        }
        
        .cct-best-practices h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-practice-tips {
            display: grid;
            gap: 15px;
        }
        
        .cct-tip {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #0073aa;
        }
        
        .cct-tip-icon {
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .cct-tip-content strong {
            display: block;
            font-size: 12px;
            color: #333;
            margin-bottom: 4px;
        }
        
        .cct-tip-content p {
            margin: 0;
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }
        
        /* Aplicar classes de elevação */
        .cct-elevation-0 { box-shadow: none; }
        .cct-elevation-1 { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24); }
        .cct-elevation-2 { box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23); }
        .cct-elevation-4 { box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23); }
        .cct-elevation-6 { box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22); }
        .cct-elevation-8 { box-shadow: 0 19px 38px rgba(0, 0, 0, 0.30), 0 15px 12px rgba(0, 0, 0, 0.22); }
        .cct-elevation-12 { box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25), 0 12px 24px rgba(0, 0, 0, 0.22); }
        .cct-elevation-16 { box-shadow: 0 30px 60px rgba(0, 0, 0, 0.30), 0 18px 36px rgba(0, 0, 0, 0.22); }
        .cct-elevation-24 { box-shadow: 0 38px 76px rgba(0, 0, 0, 0.35), 0 24px 48px rgba(0, 0, 0, 0.22); }
        
        /* Hover effects */
        .cct-elevation-hover-1:hover { box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23); }
        .cct-elevation-hover-2:hover { box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23); }
        .cct-elevation-hover-4:hover { box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22); }
        .cct-elevation-hover-6:hover { box-shadow: 0 19px 38px rgba(0, 0, 0, 0.30), 0 15px 12px rgba(0, 0, 0, 0.22); }
        .cct-elevation-hover-8:hover { box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25), 0 12px 24px rgba(0, 0, 0, 0.22); }
        </style>
        <?php
    }
}