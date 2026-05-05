<?php
/**
 * Controles Personalizados para Sistema de Layout
 * 
 * Controles avançados para o gerenciador de layout incluindo:
 * - Preview visual do grid system
 * - Gerenciador de containers responsivos
 * - Configurador de breakpoints
 * - Construtor visual de layout
 * - Escala de espaçamentos
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
 * Controle Preview do Grid System
 */
class UENF_Grid_Preview_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_grid_preview';
    
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
        
        <div class="uenf-grid-preview">
            <div class="uenf-grid-container" id="uenf-grid-container">
                <div class="uenf-grid-row" id="uenf-grid-row">
                    <!-- Colunas serão geradas via JavaScript -->
                </div>
            </div>
            
            <div class="uenf-grid-info">
                <div class="uenf-grid-stats">
                    <div class="uenf-stat">
                        <span class="uenf-stat-label"><?php _e('Colunas:', 'cct'); ?></span>
                        <span class="uenf-stat-value" id="uenf-columns-count">12</span>
                    </div>
                    <div class="uenf-stat">
                        <span class="uenf-stat-label"><?php _e('Gutter:', 'cct'); ?></span>
                        <span class="uenf-stat-value" id="uenf-gutter-size">30px</span>
                    </div>
                    <div class="uenf-stat">
                        <span class="uenf-stat-label"><?php _e('Largura:', 'cct'); ?></span>
                        <span class="uenf-stat-value" id="uenf-max-width">1200px</span>
                    </div>
                </div>
                
                <div class="uenf-grid-actions">
                    <button type="button" class="button uenf-preview-responsive" data-device="desktop">
                        <?php _e('Desktop', 'cct'); ?>
                    </button>
                    <button type="button" class="button uenf-preview-responsive" data-device="tablet">
                        <?php _e('Tablet', 'cct'); ?>
                    </button>
                    <button type="button" class="button uenf-preview-responsive" data-device="mobile">
                        <?php _e('Mobile', 'cct'); ?>
                    </button>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-grid-preview {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .uenf-grid-container {
            padding: 20px;
            background: #f9f9f9;
            overflow-x: auto;
        }
        
        .uenf-grid-row {
            display: flex;
            flex-wrap: wrap;
            min-height: 60px;
            background: #fff;
            border: 1px dashed #ccc;
            border-radius: 4px;
        }
        
        .uenf-grid-column {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 500;
            color: #1976d2;
            min-height: 40px;
            margin: 10px 0;
            transition: all 0.3s ease;
        }
        
        .uenf-grid-column:hover {
            background: #bbdefb;
            transform: scale(1.02);
        }
        
        .uenf-grid-info {
            padding: 15px;
            background: #fff;
            border-top: 1px solid #ddd;
        }
        
        .uenf-grid-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .uenf-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .uenf-stat-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .uenf-stat-value {
            font-size: 14px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-grid-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        
        .uenf-preview-responsive {
            font-size: 11px;
            padding: 4px 12px;
        }
        
        .uenf-preview-responsive.active {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        /* Responsive preview modes */
        .uenf-grid-preview.mobile .uenf-grid-container {
            max-width: 375px;
            margin: 0 auto;
        }
        
        .uenf-grid-preview.tablet .uenf-grid-container {
            max-width: 768px;
            margin: 0 auto;
        }
        
        .uenf-grid-preview.desktop .uenf-grid-container {
            max-width: 100%;
        }
        </style>
        <?php
    }
}

/**
 * Controle Gerenciador de Containers
 */
class UENF_Container_Manager_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_container_manager';
    
    /**
     * Presets de containers
     * 
     * @var array
     */
    public $container_presets = array();
    
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
        
        <div class="uenf-container-manager">
            <div class="uenf-container-presets">
                <h4><?php _e('Presets de Containers', 'cct'); ?></h4>
                
                <div class="uenf-preset-grid">
                    <?php foreach ($this->container_presets as $preset_id => $preset): ?>
                        <div class="uenf-preset-item" data-preset="<?php echo esc_attr($preset_id); ?>">
                            <div class="uenf-preset-preview">
                                <div class="uenf-preset-container" 
                                     style="max-width: <?php echo esc_attr($preset['max_width']); ?>; 
                                            padding: <?php echo esc_attr($preset['padding']); ?>; 
                                            margin: <?php echo esc_attr($preset['margin']); ?>;">
                                    <div class="uenf-preset-content"></div>
                                </div>
                            </div>
                            
                            <div class="uenf-preset-info">
                                <h5><?php echo esc_html($preset['name']); ?></h5>
                                <p><?php echo esc_html($preset['description']); ?></p>
                                
                                <div class="uenf-preset-specs">
                                    <span class="uenf-spec">
                                        <strong><?php _e('Largura:', 'cct'); ?></strong> 
                                        <?php echo esc_html($preset['max_width']); ?>
                                    </span>
                                    <span class="uenf-spec">
                                        <strong><?php _e('Padding:', 'cct'); ?></strong> 
                                        <?php echo esc_html($preset['padding']); ?>
                                    </span>
                                    <?php if ($preset['responsive']): ?>
                                        <span class="uenf-spec uenf-responsive">
                                            <strong><?php _e('Responsivo', 'cct'); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <button type="button" class="button uenf-apply-preset" data-preset="<?php echo esc_attr($preset_id); ?>">
                                <?php _e('Aplicar', 'cct'); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="uenf-custom-container">
                <h4><?php _e('Container Personalizado', 'cct'); ?></h4>
                
                <div class="uenf-container-settings">
                    <div class="uenf-setting-group">
                        <label><?php _e('Largura Máxima:', 'cct'); ?></label>
                        <div class="uenf-input-group">
                            <input type="number" class="uenf-max-width" value="1200" min="320" max="1920" step="20">
                            <select class="uenf-width-unit">
                                <option value="px">px</option>
                                <option value="%">%</option>
                                <option value="vw">vw</option>
                                <option value="rem">rem</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="uenf-setting-group">
                        <label><?php _e('Padding:', 'cct'); ?></label>
                        <div class="uenf-padding-controls">
                            <input type="number" class="uenf-padding-top" placeholder="Top" min="0" max="100">
                            <input type="number" class="uenf-padding-right" placeholder="Right" min="0" max="100">
                            <input type="number" class="uenf-padding-bottom" placeholder="Bottom" min="0" max="100">
                            <input type="number" class="uenf-padding-left" placeholder="Left" min="0" max="100">
                        </div>
                    </div>
                    
                    <div class="uenf-setting-group">
                        <label><?php _e('Alinhamento:', 'cct'); ?></label>
                        <select class="uenf-alignment">
                            <option value="left"><?php _e('Esquerda', 'cct'); ?></option>
                            <option value="center" selected><?php _e('Centro', 'cct'); ?></option>
                            <option value="right"><?php _e('Direita', 'cct'); ?></option>
                        </select>
                    </div>
                    
                    <div class="uenf-setting-group">
                        <label class="uenf-checkbox-label">
                            <input type="checkbox" class="uenf-responsive-container">
                            <?php _e('Container Responsivo', 'cct'); ?>
                        </label>
                    </div>
                </div>
                
                <div class="uenf-container-preview">
                    <h5><?php _e('Preview:', 'cct'); ?></h5>
                    <div class="uenf-custom-preview">
                        <div class="uenf-custom-container">
                            <div class="uenf-custom-content"><?php _e('Conteúdo do Container', 'cct'); ?></div>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="button button-primary uenf-save-custom-container">
                    <?php _e('Salvar Container Personalizado', 'cct'); ?>
                </button>
            </div>
        </div>
        
        <style>
        .uenf-container-manager {
            margin-top: 10px;
        }
        
        .uenf-container-manager h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }
        
        .uenf-preset-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .uenf-preset-item {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .uenf-preset-item:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 8px rgba(0,115,170,0.1);
        }
        
        .uenf-preset-item.selected {
            border-color: #0073aa;
            background-color: #f0f8ff;
        }
        
        .uenf-preset-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        
        .uenf-preset-container {
            background: #e3f2fd;
            border: 1px dashed #2196f3;
            border-radius: 2px;
            min-height: 30px;
            position: relative;
        }
        
        .uenf-preset-content {
            background: #bbdefb;
            height: 20px;
            border-radius: 1px;
        }
        
        .uenf-preset-info h5 {
            margin: 0 0 5px 0;
            font-size: 13px;
            font-weight: 600;
        }
        
        .uenf-preset-info p {
            margin: 0 0 8px 0;
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }
        
        .uenf-preset-specs {
            display: flex;
            flex-direction: column;
            gap: 2px;
            margin-bottom: 10px;
        }
        
        .uenf-spec {
            font-size: 10px;
            color: #666;
        }
        
        .uenf-spec.uenf-responsive {
            color: #27ae60;
        }
        
        .uenf-apply-preset {
            width: 100%;
            font-size: 11px;
        }
        
        .uenf-custom-container {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .uenf-container-settings {
            margin-bottom: 20px;
        }
        
        .uenf-setting-group {
            margin-bottom: 15px;
        }
        
        .uenf-setting-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #333;
        }
        
        .uenf-input-group {
            display: flex;
            gap: 5px;
        }
        
        .uenf-input-group input {
            flex: 1;
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .uenf-input-group select {
            width: 60px;
            padding: 4px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .uenf-padding-controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }
        
        .uenf-padding-controls input {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .uenf-alignment {
            width: 100%;
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .uenf-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .uenf-container-preview {
            margin-bottom: 15px;
        }
        
        .uenf-container-preview h5 {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 600;
        }
        
        .uenf-custom-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            overflow: hidden;
        }
        
        .uenf-custom-container {
            background: #e3f2fd;
            border: 1px dashed #2196f3;
            border-radius: 2px;
            padding: 15px;
            text-align: center;
        }
        
        .uenf-custom-content {
            background: #bbdefb;
            padding: 10px;
            border-radius: 2px;
            font-size: 11px;
            color: #1976d2;
        }
        
        .uenf-save-custom-container {
            width: 100%;
        }
        </style>
        <?php
    }
}



/**
 * Controle Escala de Espaçamentos
 */
class UENF_Spacing_Scale_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_spacing_scale';
    
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
        
        <div class="uenf-spacing-scale">
            <div class="uenf-scale-presets">
                <h4><?php _e('Escalas Predefinidas', 'cct'); ?></h4>
                
                <div class="uenf-preset-buttons">
                    <button type="button" class="button uenf-scale-preset" data-scale="linear">
                        <?php _e('Linear', 'cct'); ?>
                    </button>
                    <button type="button" class="button uenf-scale-preset" data-scale="fibonacci">
                        <?php _e('Fibonacci', 'cct'); ?>
                    </button>
                    <button type="button" class="button uenf-scale-preset" data-scale="golden">
                        <?php _e('Proporção Áurea', 'cct'); ?>
                    </button>
                    <button type="button" class="button uenf-scale-preset" data-scale="modular">
                        <?php _e('Escala Modular', 'cct'); ?>
                    </button>
                </div>
            </div>
            
            <div class="uenf-spacing-values">
                <h4><?php _e('Valores de Espaçamento', 'cct'); ?></h4>
                
                <div class="uenf-spacing-inputs">
                    <div class="uenf-spacing-item">
                        <label>XS</label>
                        <input type="number" class="uenf-spacing-input" data-size="xs" value="4" min="0" max="100" step="1">
                        <span class="uenf-spacing-unit">px</span>
                    </div>
                    
                    <div class="uenf-spacing-item">
                        <label>SM</label>
                        <input type="number" class="uenf-spacing-input" data-size="sm" value="8" min="0" max="100" step="1">
                        <span class="uenf-spacing-unit">px</span>
                    </div>
                    
                    <div class="uenf-spacing-item">
                        <label>MD</label>
                        <input type="number" class="uenf-spacing-input" data-size="md" value="16" min="0" max="100" step="1">
                        <span class="uenf-spacing-unit">px</span>
                    </div>
                    
                    <div class="uenf-spacing-item">
                        <label>LG</label>
                        <input type="number" class="uenf-spacing-input" data-size="lg" value="24" min="0" max="100" step="1">
                        <span class="uenf-spacing-unit">px</span>
                    </div>
                    
                    <div class="uenf-spacing-item">
                        <label>XL</label>
                        <input type="number" class="uenf-spacing-input" data-size="xl" value="32" min="0" max="100" step="1">
                        <span class="uenf-spacing-unit">px</span>
                    </div>
                    
                    <div class="uenf-spacing-item">
                        <label>XXL</label>
                        <input type="number" class="uenf-spacing-input" data-size="xxl" value="48" min="0" max="100" step="1">
                        <span class="uenf-spacing-unit">px</span>
                    </div>
                </div>
            </div>
            
            <div class="uenf-spacing-preview">
                <h4><?php _e('Preview dos Espaçamentos', 'cct'); ?></h4>
                
                <div class="uenf-preview-container">
                    <div class="uenf-spacing-demo" data-size="xs">
                        <div class="uenf-demo-box">XS</div>
                    </div>
                    <div class="uenf-spacing-demo" data-size="sm">
                        <div class="uenf-demo-box">SM</div>
                    </div>
                    <div class="uenf-spacing-demo" data-size="md">
                        <div class="uenf-demo-box">MD</div>
                    </div>
                    <div class="uenf-spacing-demo" data-size="lg">
                        <div class="uenf-demo-box">LG</div>
                    </div>
                    <div class="uenf-spacing-demo" data-size="xl">
                        <div class="uenf-demo-box">XL</div>
                    </div>
                    <div class="uenf-spacing-demo" data-size="xxl">
                        <div class="uenf-demo-box">XXL</div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-spacing-scale {
            margin-top: 10px;
        }
        
        .uenf-spacing-scale h4 {
            margin: 0 0 12px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
        }
        
        .uenf-scale-presets {
            margin-bottom: 20px;
        }
        
        .uenf-preset-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        
        .uenf-scale-preset {
            font-size: 11px;
            padding: 6px 12px;
        }
        
        .uenf-scale-preset.active {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        .uenf-spacing-values {
            margin-bottom: 20px;
        }
        
        .uenf-spacing-inputs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .uenf-spacing-item {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
        }
        
        .uenf-spacing-item label {
            font-size: 11px;
            font-weight: 600;
            color: #333;
            min-width: 25px;
        }
        
        .uenf-spacing-input {
            flex: 1;
            padding: 2px 6px;
            border: 1px solid #ccc;
            border-radius: 2px;
            font-size: 11px;
            text-align: center;
        }
        
        .uenf-spacing-unit {
            font-size: 10px;
            color: #666;
        }
        
        .uenf-spacing-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
        }
        
        .uenf-preview-container {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .uenf-spacing-demo {
            display: flex;
            align-items: center;
        }
        
        .uenf-demo-box {
            background: #0073aa;
            color: white;
            padding: 4px 8px;
            border-radius: 2px;
            font-size: 10px;
            font-weight: 500;
            min-width: 30px;
            text-align: center;
        }
        
        .uenf-spacing-demo[data-size="xs"] .uenf-demo-box {
            margin-right: 4px;
        }
        
        .uenf-spacing-demo[data-size="sm"] .uenf-demo-box {
            margin-right: 8px;
        }
        
        .uenf-spacing-demo[data-size="md"] .uenf-demo-box {
            margin-right: 16px;
        }
        
        .uenf-spacing-demo[data-size="lg"] .uenf-demo-box {
            margin-right: 24px;
        }
        
        .uenf-spacing-demo[data-size="xl"] .uenf-demo-box {
            margin-right: 32px;
        }
        
        .uenf-spacing-demo[data-size="xxl"] .uenf-demo-box {
            margin-right: 48px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Layout Builder
 */
class UENF_Layout_Builder_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_layout_builder';
    
    /**
     * Seções de layout
     * 
     * @var array
     */
    public $layout_sections = array();
    
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
        
        <div class="uenf-layout-builder">
            <div class="uenf-builder-toolbar">
                <div class="uenf-toolbar-group">
                    <button type="button" class="button uenf-add-section">
                        <span class="dashicons dashicons-plus"></span>
                        <?php _e('Adicionar Seção', 'cct'); ?>
                    </button>
                    
                    <button type="button" class="button uenf-save-layout">
                        <span class="dashicons dashicons-saved"></span>
                        <?php _e('Salvar Layout', 'cct'); ?>
                    </button>
                </div>
                
                <div class="uenf-toolbar-group">
                    <select class="uenf-layout-selector">
                        <option value="default"><?php _e('Layout Padrão', 'cct'); ?></option>
                    </select>
                    
                    <button type="button" class="button uenf-preview-layout">
                        <span class="dashicons dashicons-visibility"></span>
                        <?php _e('Preview', 'cct'); ?>
                    </button>
                </div>
            </div>
            
            <div class="uenf-builder-canvas">
                <div class="uenf-canvas-container">
                    <div class="uenf-layout-sections" id="uenf-layout-sections">
                        <!-- Seções serão adicionadas via JavaScript -->
                    </div>
                </div>
                
                <div class="uenf-canvas-sidebar">
                    <h4><?php _e('Componentes', 'cct'); ?></h4>
                    
                    <div class="uenf-component-list">
                        <?php foreach ($this->layout_sections as $section_key => $section): ?>
                            <div class="uenf-component-item" data-component="<?php echo esc_attr($section_key); ?>">
                                <div class="uenf-component-icon">
                                    <span class="dashicons dashicons-admin-page"></span>
                                </div>
                                <div class="uenf-component-info">
                                    <h5><?php echo esc_html($section['name']); ?></h5>
                                    <p><?php echo esc_html($section['description']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-layout-builder {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .uenf-builder-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-toolbar-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .uenf-toolbar-group .button {
            font-size: 11px;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .uenf-layout-selector {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .uenf-builder-canvas {
            display: flex;
            min-height: 400px;
        }
        
        .uenf-canvas-container {
            flex: 1;
            padding: 20px;
            background: #fff;
        }
        
        .uenf-layout-sections {
            min-height: 300px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            padding: 20px;
            position: relative;
        }
        
        .uenf-layout-sections.empty::before {
            content: "Arraste componentes aqui para construir seu layout";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #999;
            font-size: 14px;
            text-align: center;
        }
        
        .uenf-canvas-sidebar {
            width: 200px;
            background: #f9f9f9;
            border-left: 1px solid #ddd;
            padding: 15px;
        }
        
        .uenf-canvas-sidebar h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-component-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .uenf-component-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            cursor: grab;
            transition: all 0.3s ease;
        }
        
        .uenf-component-item:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 4px rgba(0,115,170,0.1);
        }
        
        .uenf-component-item:active {
            cursor: grabbing;
        }
        
        .uenf-component-icon {
            color: #0073aa;
        }
        
        .uenf-component-icon .dashicons {
            font-size: 16px;
            width: 16px;
            height: 16px;
        }
        
        .uenf-component-info h5 {
            margin: 0 0 2px 0;
            font-size: 11px;
            font-weight: 600;
        }
        
        .uenf-component-info p {
            margin: 0;
            font-size: 9px;
            color: #666;
            line-height: 1.3;
        }
        
        .uenf-layout-section {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            background: #f9f9f9;
            position: relative;
        }
        
        .uenf-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #fff;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-section-title {
            font-size: 12px;
            font-weight: 600;
        }
        
        .uenf-section-actions {
            display: flex;
            gap: 4px;
        }
        
        .uenf-section-action {
            padding: 2px 6px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 2px;
            cursor: pointer;
            font-size: 10px;
        }
        
        .uenf-section-content {
            padding: 15px;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 11px;
        }
        </style>
        <?php
    }
}