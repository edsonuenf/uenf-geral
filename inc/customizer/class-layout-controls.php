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
 * @package CCT_Theme
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
class CCT_Grid_Preview_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_grid_preview';
    
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
        
        <div class="cct-grid-preview">
            <div class="cct-grid-container" id="cct-grid-container">
                <div class="cct-grid-row" id="cct-grid-row">
                    <!-- Colunas serão geradas via JavaScript -->
                </div>
            </div>
            
            <div class="cct-grid-info">
                <div class="cct-grid-stats">
                    <div class="cct-stat">
                        <span class="cct-stat-label"><?php _e('Colunas:', 'cct'); ?></span>
                        <span class="cct-stat-value" id="cct-columns-count">12</span>
                    </div>
                    <div class="cct-stat">
                        <span class="cct-stat-label"><?php _e('Gutter:', 'cct'); ?></span>
                        <span class="cct-stat-value" id="cct-gutter-size">30px</span>
                    </div>
                    <div class="cct-stat">
                        <span class="cct-stat-label"><?php _e('Largura:', 'cct'); ?></span>
                        <span class="cct-stat-value" id="cct-max-width">1200px</span>
                    </div>
                </div>
                
                <div class="cct-grid-actions">
                    <button type="button" class="button cct-preview-responsive" data-device="desktop">
                        <?php _e('Desktop', 'cct'); ?>
                    </button>
                    <button type="button" class="button cct-preview-responsive" data-device="tablet">
                        <?php _e('Tablet', 'cct'); ?>
                    </button>
                    <button type="button" class="button cct-preview-responsive" data-device="mobile">
                        <?php _e('Mobile', 'cct'); ?>
                    </button>
                </div>
            </div>
        </div>
        
        <style>
        .cct-grid-preview {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .cct-grid-container {
            padding: 20px;
            background: #f9f9f9;
            overflow-x: auto;
        }
        
        .cct-grid-row {
            display: flex;
            flex-wrap: wrap;
            min-height: 60px;
            background: #fff;
            border: 1px dashed #ccc;
            border-radius: 4px;
        }
        
        .cct-grid-column {
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
        
        .cct-grid-column:hover {
            background: #bbdefb;
            transform: scale(1.02);
        }
        
        .cct-grid-info {
            padding: 15px;
            background: #fff;
            border-top: 1px solid #ddd;
        }
        
        .cct-grid-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .cct-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .cct-stat-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .cct-stat-value {
            font-size: 14px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .cct-grid-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        
        .cct-preview-responsive {
            font-size: 11px;
            padding: 4px 12px;
        }
        
        .cct-preview-responsive.active {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        /* Responsive preview modes */
        .cct-grid-preview.mobile .cct-grid-container {
            max-width: 375px;
            margin: 0 auto;
        }
        
        .cct-grid-preview.tablet .cct-grid-container {
            max-width: 768px;
            margin: 0 auto;
        }
        
        .cct-grid-preview.desktop .cct-grid-container {
            max-width: 100%;
        }
        </style>
        <?php
    }
}

/**
 * Controle Gerenciador de Containers
 */
class CCT_Container_Manager_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_container_manager';
    
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
        
        <div class="cct-container-manager">
            <div class="cct-container-presets">
                <h4><?php _e('Presets de Containers', 'cct'); ?></h4>
                
                <div class="cct-preset-grid">
                    <?php foreach ($this->container_presets as $preset_id => $preset): ?>
                        <div class="cct-preset-item" data-preset="<?php echo esc_attr($preset_id); ?>">
                            <div class="cct-preset-preview">
                                <div class="cct-preset-container" 
                                     style="max-width: <?php echo esc_attr($preset['max_width']); ?>; 
                                            padding: <?php echo esc_attr($preset['padding']); ?>; 
                                            margin: <?php echo esc_attr($preset['margin']); ?>;">
                                    <div class="cct-preset-content"></div>
                                </div>
                            </div>
                            
                            <div class="cct-preset-info">
                                <h5><?php echo esc_html($preset['name']); ?></h5>
                                <p><?php echo esc_html($preset['description']); ?></p>
                                
                                <div class="cct-preset-specs">
                                    <span class="cct-spec">
                                        <strong><?php _e('Largura:', 'cct'); ?></strong> 
                                        <?php echo esc_html($preset['max_width']); ?>
                                    </span>
                                    <span class="cct-spec">
                                        <strong><?php _e('Padding:', 'cct'); ?></strong> 
                                        <?php echo esc_html($preset['padding']); ?>
                                    </span>
                                    <?php if ($preset['responsive']): ?>
                                        <span class="cct-spec cct-responsive">
                                            <strong><?php _e('Responsivo', 'cct'); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <button type="button" class="button cct-apply-preset" data-preset="<?php echo esc_attr($preset_id); ?>">
                                <?php _e('Aplicar', 'cct'); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="cct-custom-container">
                <h4><?php _e('Container Personalizado', 'cct'); ?></h4>
                
                <div class="cct-container-settings">
                    <div class="cct-setting-group">
                        <label><?php _e('Largura Máxima:', 'cct'); ?></label>
                        <div class="cct-input-group">
                            <input type="number" class="cct-max-width" value="1200" min="320" max="1920" step="20">
                            <select class="cct-width-unit">
                                <option value="px">px</option>
                                <option value="%">%</option>
                                <option value="vw">vw</option>
                                <option value="rem">rem</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="cct-setting-group">
                        <label><?php _e('Padding:', 'cct'); ?></label>
                        <div class="cct-padding-controls">
                            <input type="number" class="cct-padding-top" placeholder="Top" min="0" max="100">
                            <input type="number" class="cct-padding-right" placeholder="Right" min="0" max="100">
                            <input type="number" class="cct-padding-bottom" placeholder="Bottom" min="0" max="100">
                            <input type="number" class="cct-padding-left" placeholder="Left" min="0" max="100">
                        </div>
                    </div>
                    
                    <div class="cct-setting-group">
                        <label><?php _e('Alinhamento:', 'cct'); ?></label>
                        <select class="cct-alignment">
                            <option value="left"><?php _e('Esquerda', 'cct'); ?></option>
                            <option value="center" selected><?php _e('Centro', 'cct'); ?></option>
                            <option value="right"><?php _e('Direita', 'cct'); ?></option>
                        </select>
                    </div>
                    
                    <div class="cct-setting-group">
                        <label class="cct-checkbox-label">
                            <input type="checkbox" class="cct-responsive-container">
                            <?php _e('Container Responsivo', 'cct'); ?>
                        </label>
                    </div>
                </div>
                
                <div class="cct-container-preview">
                    <h5><?php _e('Preview:', 'cct'); ?></h5>
                    <div class="cct-custom-preview">
                        <div class="cct-custom-container">
                            <div class="cct-custom-content"><?php _e('Conteúdo do Container', 'cct'); ?></div>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="button button-primary cct-save-custom-container">
                    <?php _e('Salvar Container Personalizado', 'cct'); ?>
                </button>
            </div>
        </div>
        
        <style>
        .cct-container-manager {
            margin-top: 10px;
        }
        
        .cct-container-manager h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }
        
        .cct-preset-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .cct-preset-item {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .cct-preset-item:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 8px rgba(0,115,170,0.1);
        }
        
        .cct-preset-item.selected {
            border-color: #0073aa;
            background-color: #f0f8ff;
        }
        
        .cct-preset-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        
        .cct-preset-container {
            background: #e3f2fd;
            border: 1px dashed #2196f3;
            border-radius: 2px;
            min-height: 30px;
            position: relative;
        }
        
        .cct-preset-content {
            background: #bbdefb;
            height: 20px;
            border-radius: 1px;
        }
        
        .cct-preset-info h5 {
            margin: 0 0 5px 0;
            font-size: 13px;
            font-weight: 600;
        }
        
        .cct-preset-info p {
            margin: 0 0 8px 0;
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }
        
        .cct-preset-specs {
            display: flex;
            flex-direction: column;
            gap: 2px;
            margin-bottom: 10px;
        }
        
        .cct-spec {
            font-size: 10px;
            color: #666;
        }
        
        .cct-spec.cct-responsive {
            color: #27ae60;
        }
        
        .cct-apply-preset {
            width: 100%;
            font-size: 11px;
        }
        
        .cct-custom-container {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .cct-container-settings {
            margin-bottom: 20px;
        }
        
        .cct-setting-group {
            margin-bottom: 15px;
        }
        
        .cct-setting-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #333;
        }
        
        .cct-input-group {
            display: flex;
            gap: 5px;
        }
        
        .cct-input-group input {
            flex: 1;
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .cct-input-group select {
            width: 60px;
            padding: 4px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .cct-padding-controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }
        
        .cct-padding-controls input {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .cct-alignment {
            width: 100%;
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .cct-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .cct-container-preview {
            margin-bottom: 15px;
        }
        
        .cct-container-preview h5 {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 600;
        }
        
        .cct-custom-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            overflow: hidden;
        }
        
        .cct-custom-container {
            background: #e3f2fd;
            border: 1px dashed #2196f3;
            border-radius: 2px;
            padding: 15px;
            text-align: center;
        }
        
        .cct-custom-content {
            background: #bbdefb;
            padding: 10px;
            border-radius: 2px;
            font-size: 11px;
            color: #1976d2;
        }
        
        .cct-save-custom-container {
            width: 100%;
        }
        </style>
        <?php
    }
}



/**
 * Controle Escala de Espaçamentos
 */
class CCT_Spacing_Scale_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_spacing_scale';
    
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
        
        <div class="cct-spacing-scale">
            <div class="cct-scale-presets">
                <h4><?php _e('Escalas Predefinidas', 'cct'); ?></h4>
                
                <div class="cct-preset-buttons">
                    <button type="button" class="button cct-scale-preset" data-scale="linear">
                        <?php _e('Linear', 'cct'); ?>
                    </button>
                    <button type="button" class="button cct-scale-preset" data-scale="fibonacci">
                        <?php _e('Fibonacci', 'cct'); ?>
                    </button>
                    <button type="button" class="button cct-scale-preset" data-scale="golden">
                        <?php _e('Proporção Áurea', 'cct'); ?>
                    </button>
                    <button type="button" class="button cct-scale-preset" data-scale="modular">
                        <?php _e('Escala Modular', 'cct'); ?>
                    </button>
                </div>
            </div>
            
            <div class="cct-spacing-values">
                <h4><?php _e('Valores de Espaçamento', 'cct'); ?></h4>
                
                <div class="cct-spacing-inputs">
                    <div class="cct-spacing-item">
                        <label>XS</label>
                        <input type="number" class="cct-spacing-input" data-size="xs" value="4" min="0" max="100" step="1">
                        <span class="cct-spacing-unit">px</span>
                    </div>
                    
                    <div class="cct-spacing-item">
                        <label>SM</label>
                        <input type="number" class="cct-spacing-input" data-size="sm" value="8" min="0" max="100" step="1">
                        <span class="cct-spacing-unit">px</span>
                    </div>
                    
                    <div class="cct-spacing-item">
                        <label>MD</label>
                        <input type="number" class="cct-spacing-input" data-size="md" value="16" min="0" max="100" step="1">
                        <span class="cct-spacing-unit">px</span>
                    </div>
                    
                    <div class="cct-spacing-item">
                        <label>LG</label>
                        <input type="number" class="cct-spacing-input" data-size="lg" value="24" min="0" max="100" step="1">
                        <span class="cct-spacing-unit">px</span>
                    </div>
                    
                    <div class="cct-spacing-item">
                        <label>XL</label>
                        <input type="number" class="cct-spacing-input" data-size="xl" value="32" min="0" max="100" step="1">
                        <span class="cct-spacing-unit">px</span>
                    </div>
                    
                    <div class="cct-spacing-item">
                        <label>XXL</label>
                        <input type="number" class="cct-spacing-input" data-size="xxl" value="48" min="0" max="100" step="1">
                        <span class="cct-spacing-unit">px</span>
                    </div>
                </div>
            </div>
            
            <div class="cct-spacing-preview">
                <h4><?php _e('Preview dos Espaçamentos', 'cct'); ?></h4>
                
                <div class="cct-preview-container">
                    <div class="cct-spacing-demo" data-size="xs">
                        <div class="cct-demo-box">XS</div>
                    </div>
                    <div class="cct-spacing-demo" data-size="sm">
                        <div class="cct-demo-box">SM</div>
                    </div>
                    <div class="cct-spacing-demo" data-size="md">
                        <div class="cct-demo-box">MD</div>
                    </div>
                    <div class="cct-spacing-demo" data-size="lg">
                        <div class="cct-demo-box">LG</div>
                    </div>
                    <div class="cct-spacing-demo" data-size="xl">
                        <div class="cct-demo-box">XL</div>
                    </div>
                    <div class="cct-spacing-demo" data-size="xxl">
                        <div class="cct-demo-box">XXL</div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .cct-spacing-scale {
            margin-top: 10px;
        }
        
        .cct-spacing-scale h4 {
            margin: 0 0 12px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
        }
        
        .cct-scale-presets {
            margin-bottom: 20px;
        }
        
        .cct-preset-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        
        .cct-scale-preset {
            font-size: 11px;
            padding: 6px 12px;
        }
        
        .cct-scale-preset.active {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        .cct-spacing-values {
            margin-bottom: 20px;
        }
        
        .cct-spacing-inputs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .cct-spacing-item {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
        }
        
        .cct-spacing-item label {
            font-size: 11px;
            font-weight: 600;
            color: #333;
            min-width: 25px;
        }
        
        .cct-spacing-input {
            flex: 1;
            padding: 2px 6px;
            border: 1px solid #ccc;
            border-radius: 2px;
            font-size: 11px;
            text-align: center;
        }
        
        .cct-spacing-unit {
            font-size: 10px;
            color: #666;
        }
        
        .cct-spacing-preview {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
        }
        
        .cct-preview-container {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .cct-spacing-demo {
            display: flex;
            align-items: center;
        }
        
        .cct-demo-box {
            background: #0073aa;
            color: white;
            padding: 4px 8px;
            border-radius: 2px;
            font-size: 10px;
            font-weight: 500;
            min-width: 30px;
            text-align: center;
        }
        
        .cct-spacing-demo[data-size="xs"] .cct-demo-box {
            margin-right: 4px;
        }
        
        .cct-spacing-demo[data-size="sm"] .cct-demo-box {
            margin-right: 8px;
        }
        
        .cct-spacing-demo[data-size="md"] .cct-demo-box {
            margin-right: 16px;
        }
        
        .cct-spacing-demo[data-size="lg"] .cct-demo-box {
            margin-right: 24px;
        }
        
        .cct-spacing-demo[data-size="xl"] .cct-demo-box {
            margin-right: 32px;
        }
        
        .cct-spacing-demo[data-size="xxl"] .cct-demo-box {
            margin-right: 48px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Layout Builder
 */
class CCT_Layout_Builder_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_layout_builder';
    
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
        
        <div class="cct-layout-builder">
            <div class="cct-builder-toolbar">
                <div class="cct-toolbar-group">
                    <button type="button" class="button cct-add-section">
                        <span class="dashicons dashicons-plus"></span>
                        <?php _e('Adicionar Seção', 'cct'); ?>
                    </button>
                    
                    <button type="button" class="button cct-save-layout">
                        <span class="dashicons dashicons-saved"></span>
                        <?php _e('Salvar Layout', 'cct'); ?>
                    </button>
                </div>
                
                <div class="cct-toolbar-group">
                    <select class="cct-layout-selector">
                        <option value="default"><?php _e('Layout Padrão', 'cct'); ?></option>
                    </select>
                    
                    <button type="button" class="button cct-preview-layout">
                        <span class="dashicons dashicons-visibility"></span>
                        <?php _e('Preview', 'cct'); ?>
                    </button>
                </div>
            </div>
            
            <div class="cct-builder-canvas">
                <div class="cct-canvas-container">
                    <div class="cct-layout-sections" id="cct-layout-sections">
                        <!-- Seções serão adicionadas via JavaScript -->
                    </div>
                </div>
                
                <div class="cct-canvas-sidebar">
                    <h4><?php _e('Componentes', 'cct'); ?></h4>
                    
                    <div class="cct-component-list">
                        <?php foreach ($this->layout_sections as $section_key => $section): ?>
                            <div class="cct-component-item" data-component="<?php echo esc_attr($section_key); ?>">
                                <div class="cct-component-icon">
                                    <span class="dashicons dashicons-admin-page"></span>
                                </div>
                                <div class="cct-component-info">
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
        .cct-layout-builder {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .cct-builder-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-toolbar-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .cct-toolbar-group .button {
            font-size: 11px;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .cct-layout-selector {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .cct-builder-canvas {
            display: flex;
            min-height: 400px;
        }
        
        .cct-canvas-container {
            flex: 1;
            padding: 20px;
            background: #fff;
        }
        
        .cct-layout-sections {
            min-height: 300px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            padding: 20px;
            position: relative;
        }
        
        .cct-layout-sections.empty::before {
            content: "Arraste componentes aqui para construir seu layout";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #999;
            font-size: 14px;
            text-align: center;
        }
        
        .cct-canvas-sidebar {
            width: 200px;
            background: #f9f9f9;
            border-left: 1px solid #ddd;
            padding: 15px;
        }
        
        .cct-canvas-sidebar h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-component-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .cct-component-item {
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
        
        .cct-component-item:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 4px rgba(0,115,170,0.1);
        }
        
        .cct-component-item:active {
            cursor: grabbing;
        }
        
        .cct-component-icon {
            color: #0073aa;
        }
        
        .cct-component-icon .dashicons {
            font-size: 16px;
            width: 16px;
            height: 16px;
        }
        
        .cct-component-info h5 {
            margin: 0 0 2px 0;
            font-size: 11px;
            font-weight: 600;
        }
        
        .cct-component-info p {
            margin: 0;
            font-size: 9px;
            color: #666;
            line-height: 1.3;
        }
        
        .cct-layout-section {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            background: #f9f9f9;
            position: relative;
        }
        
        .cct-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #fff;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-section-title {
            font-size: 12px;
            font-weight: 600;
        }
        
        .cct-section-actions {
            display: flex;
            gap: 4px;
        }
        
        .cct-section-action {
            padding: 2px 6px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 2px;
            cursor: pointer;
            font-size: 10px;
        }
        
        .cct-section-content {
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