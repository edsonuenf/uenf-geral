<?php
/**
 * Controles Personalizados para Biblioteca de Gradientes
 * 
 * Controles avançados para o gerenciador de gradientes incluindo:
 * - Browser visual de gradientes
 * - Gerador interativo de gradientes
 * - Editor de cores e posições
 * - Preview em tempo real
 * - Gerenciador de favoritos
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
 * Controle Browser de Gradientes
 */
class UENF_Gradient_Browser_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_gradient_browser';
    
    /**
     * Biblioteca de gradientes
     * 
     * @var array
     */
    public $gradient_library = array();
    
    /**
     * Categorias de gradientes
     * 
     * @var array
     */
    public $gradient_categories = array();
    
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
        
        <div class="uenf-gradient-browser">
            <!-- Filtros de categoria -->
            <div class="uenf-gradient-filters">
                <h4><?php _e('Categorias', 'cct'); ?></h4>
                
                <div class="uenf-category-tabs">
                    <?php foreach ($this->gradient_categories as $cat_key => $category): ?>
                        <button type="button" class="uenf-category-tab <?php echo $cat_key === 'all' ? 'active' : ''; ?>" 
                                data-category="<?php echo esc_attr($cat_key); ?>">
                            <span class="uenf-category-icon"><?php echo esc_html($category['icon']); ?></span>
                            <span class="uenf-category-name"><?php echo esc_html($category['name']); ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Grid de gradientes -->
            <div class="uenf-gradient-grid">
                <?php foreach ($this->gradient_library as $gradient_key => $gradient): ?>
                    <div class="uenf-gradient-item" 
                         data-gradient="<?php echo esc_attr($gradient_key); ?>"
                         data-category="<?php echo esc_attr($gradient['category']); ?>">
                        
                        <div class="uenf-gradient-preview" 
                             style="background: <?php echo esc_attr($gradient['css']); ?>">
                            <div class="uenf-gradient-overlay">
                                <div class="uenf-gradient-actions">
                                    <button type="button" class="uenf-btn uenf-btn-apply" 
                                            data-gradient="<?php echo esc_attr($gradient_key); ?>">
                                        <?php _e('Aplicar', 'cct'); ?>
                                    </button>
                                    <button type="button" class="uenf-btn uenf-btn-favorite" 
                                            data-gradient="<?php echo esc_attr($gradient_key); ?>">
                                        ❤️
                                    </button>
                                    <button type="button" class="uenf-btn uenf-btn-copy" 
                                            data-gradient="<?php echo esc_attr($gradient_key); ?>">
                                        📋
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="uenf-gradient-info">
                            <h5 class="uenf-gradient-name"><?php echo esc_html($gradient['name']); ?></h5>
                            <p class="uenf-gradient-description"><?php echo esc_html($gradient['description']); ?></p>
                            
                            <div class="uenf-gradient-meta">
                                <span class="uenf-gradient-type">
                                    <strong><?php _e('Tipo:', 'cct'); ?></strong> 
                                    <?php echo esc_html(ucfirst($gradient['type'])); ?>
                                </span>
                                
                                <?php if (isset($gradient['popularity'])): ?>
                                    <span class="uenf-gradient-popularity">
                                        <strong><?php _e('Popular:', 'cct'); ?></strong> 
                                        <?php echo esc_html($gradient['popularity']); ?>%
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="uenf-gradient-css">
                                <code><?php echo esc_html($gradient['css']); ?></code>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Controles de busca e ordenação -->
            <div class="uenf-gradient-controls">
                <div class="uenf-search-box">
                    <input type="text" class="uenf-gradient-search" 
                           placeholder="<?php _e('Buscar gradientes...', 'cct'); ?>">
                    <span class="uenf-search-icon">🔍</span>
                </div>
                
                <div class="uenf-sort-controls">
                    <label><?php _e('Ordenar por:', 'cct'); ?></label>
                    <select class="uenf-gradient-sort">
                        <option value="name"><?php _e('Nome', 'cct'); ?></option>
                        <option value="popularity"><?php _e('Popularidade', 'cct'); ?></option>
                        <option value="type"><?php _e('Tipo', 'cct'); ?></option>
                        <option value="category"><?php _e('Categoria', 'cct'); ?></option>
                    </select>
                </div>
                
                <div class="uenf-view-controls">
                    <button type="button" class="uenf-view-btn uenf-view-grid active" data-view="grid">
                        ⊞
                    </button>
                    <button type="button" class="uenf-view-btn uenf-view-list" data-view="list">
                        ☰
                    </button>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-gradient-browser {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #f9f9f9;
        }
        
        .uenf-gradient-filters {
            padding: 15px;
            background: white;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-gradient-filters h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-category-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .uenf-category-tab {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 11px;
        }
        
        .uenf-category-tab:hover {
            border-color: #0073aa;
            background: #f0f8ff;
        }
        
        .uenf-category-tab.active {
            border-color: #0073aa;
            background: #0073aa;
            color: white;
        }
        
        .uenf-category-icon {
            font-size: 14px;
        }
        
        .uenf-gradient-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
            background: #f9f9f9;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .uenf-gradient-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .uenf-gradient-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .uenf-gradient-item.hidden {
            display: none;
        }
        
        .uenf-gradient-preview {
            height: 120px;
            position: relative;
            cursor: pointer;
        }
        
        .uenf-gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .uenf-gradient-preview:hover .uenf-gradient-overlay {
            opacity: 1;
        }
        
        .uenf-gradient-actions {
            display: flex;
            gap: 8px;
        }
        
        .uenf-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            background: white;
            color: #333;
            cursor: pointer;
            font-size: 11px;
            transition: all 0.3s ease;
        }
        
        .uenf-btn:hover {
            background: #0073aa;
            color: white;
        }
        
        .uenf-btn-apply {
            background: #0073aa;
            color: white;
        }
        
        .uenf-gradient-info {
            padding: 15px;
        }
        
        .uenf-gradient-name {
            margin: 0 0 5px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-gradient-description {
            margin: 0 0 10px 0;
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }
        
        .uenf-gradient-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 10px;
            color: #888;
        }
        
        .uenf-gradient-css {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #e1e1e1;
        }
        
        .uenf-gradient-css code {
            font-size: 9px;
            color: #333;
            word-break: break-all;
        }
        
        .uenf-gradient-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: white;
            border-top: 1px solid #ddd;
        }
        
        .uenf-search-box {
            position: relative;
            flex: 1;
        }
        
        .uenf-gradient-search {
            width: 100%;
            padding: 8px 35px 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .uenf-search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        
        .uenf-sort-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .uenf-sort-controls label {
            font-size: 11px;
            color: #666;
        }
        
        .uenf-gradient-sort {
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 11px;
        }
        
        .uenf-view-controls {
            display: flex;
            gap: 4px;
        }
        
        .uenf-view-btn {
            padding: 8px;
            border: 1px solid #ddd;
            background: #f8f9fa;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
        }
        
        .uenf-view-btn.active {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        /* View List */
        .uenf-gradient-grid.list-view {
            grid-template-columns: 1fr;
        }
        
        .uenf-gradient-grid.list-view .uenf-gradient-item {
            display: flex;
        }
        
        .uenf-gradient-grid.list-view .uenf-gradient-preview {
            width: 100px;
            height: 60px;
            flex-shrink: 0;
        }
        
        .uenf-gradient-grid.list-view .uenf-gradient-info {
            flex: 1;
            padding: 10px 15px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Gerador de Gradientes
 */
class UENF_Gradient_Generator_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_gradient_generator';
    
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
        
        <div class="uenf-gradient-generator">
            <!-- Preview do gradiente -->
            <div class="uenf-gradient-preview-area">
                <div class="uenf-gradient-preview-box" id="uenf-gradient-preview">
                    <div class="uenf-preview-content">
                        <h3><?php _e('Preview do Gradiente', 'cct'); ?></h3>
                        <p><?php _e('Seu gradiente personalizado aparecerá aqui', 'cct'); ?></p>
                    </div>
                </div>
                
                <div class="uenf-gradient-code">
                    <label><?php _e('Código CSS:', 'cct'); ?></label>
                    <textarea id="uenf-gradient-css" readonly></textarea>
                    <button type="button" class="button uenf-copy-css"><?php _e('📋 Copiar CSS', 'cct'); ?></button>
                </div>
            </div>
            
            <!-- Configurações do gradiente -->
            <div class="uenf-gradient-settings">
                <div class="uenf-setting-group">
                    <label><?php _e('Tipo de Gradiente:', 'cct'); ?></label>
                    <select id="uenf-gradient-type">
                        <option value="linear"><?php _e('Linear', 'cct'); ?></option>
                        <option value="radial"><?php _e('Radial', 'cct'); ?></option>
                        <option value="conic"><?php _e('Cônico', 'cct'); ?></option>
                    </select>
                </div>
                
                <div class="uenf-setting-group uenf-linear-settings">
                    <label><?php _e('Direção:', 'cct'); ?></label>
                    <div class="uenf-direction-control">
                        <input type="range" id="uenf-gradient-angle" min="0" max="360" value="45">
                        <span id="uenf-angle-value">45°</span>
                    </div>
                    
                    <div class="uenf-direction-presets">
                        <button type="button" class="uenf-direction-btn" data-angle="0">↑</button>
                        <button type="button" class="uenf-direction-btn" data-angle="45">↗</button>
                        <button type="button" class="uenf-direction-btn" data-angle="90">→</button>
                        <button type="button" class="uenf-direction-btn" data-angle="135">↘</button>
                        <button type="button" class="uenf-direction-btn" data-angle="180">↓</button>
                        <button type="button" class="uenf-direction-btn" data-angle="225">↙</button>
                        <button type="button" class="uenf-direction-btn" data-angle="270">←</button>
                        <button type="button" class="uenf-direction-btn" data-angle="315">↖</button>
                    </div>
                </div>
                
                <div class="uenf-setting-group uenf-radial-settings" style="display: none;">
                    <label><?php _e('Forma:', 'cct'); ?></label>
                    <select id="uenf-radial-shape">
                        <option value="circle"><?php _e('Círculo', 'cct'); ?></option>
                        <option value="ellipse"><?php _e('Elipse', 'cct'); ?></option>
                    </select>
                    
                    <label><?php _e('Posição:', 'cct'); ?></label>
                    <select id="uenf-radial-position">
                        <option value="center"><?php _e('Centro', 'cct'); ?></option>
                        <option value="top"><?php _e('Topo', 'cct'); ?></option>
                        <option value="bottom"><?php _e('Base', 'cct'); ?></option>
                        <option value="left"><?php _e('Esquerda', 'cct'); ?></option>
                        <option value="right"><?php _e('Direita', 'cct'); ?></option>
                    </select>
                </div>
                
                <div class="uenf-setting-group uenf-conic-settings" style="display: none;">
                    <label><?php _e('Ângulo Inicial:', 'cct'); ?></label>
                    <div class="uenf-angle-control">
                        <input type="range" id="uenf-conic-angle" min="0" max="360" value="0">
                        <span id="uenf-conic-value">0°</span>
                    </div>
                </div>
            </div>
            
            <!-- Editor de cores -->
            <div class="uenf-color-editor">
                <h4><?php _e('Cores do Gradiente', 'cct'); ?></h4>
                
                <div class="uenf-color-stops" id="uenf-color-stops">
                    <div class="uenf-color-stop" data-position="0">
                        <div class="uenf-color-preview" style="background: #ff7e5f;"></div>
                        <input type="color" class="uenf-color-input" value="#ff7e5f">
                        <input type="range" class="uenf-position-input" min="0" max="100" value="0">
                        <span class="uenf-position-value">0%</span>
                        <button type="button" class="uenf-remove-color" disabled>×</button>
                    </div>
                    
                    <div class="uenf-color-stop" data-position="100">
                        <div class="uenf-color-preview" style="background: #feb47b;"></div>
                        <input type="color" class="uenf-color-input" value="#feb47b">
                        <input type="range" class="uenf-position-input" min="0" max="100" value="100">
                        <span class="uenf-position-value">100%</span>
                        <button type="button" class="uenf-remove-color" disabled>×</button>
                    </div>
                </div>
                
                <div class="uenf-color-actions">
                    <button type="button" class="button uenf-add-color"><?php _e('+ Adicionar Cor', 'cct'); ?></button>
                    <button type="button" class="button uenf-random-colors"><?php _e('🎲 Cores Aleatórias', 'cct'); ?></button>
                    <button type="button" class="button uenf-reverse-colors"><?php _e('🔄 Inverter', 'cct'); ?></button>
                </div>
            </div>
            
            <!-- Presets rápidos -->
            <div class="uenf-quick-presets">
                <h4><?php _e('Presets Rápidos', 'cct'); ?></h4>
                
                <div class="uenf-preset-grid">
                    <button type="button" class="uenf-preset-btn" data-preset="sunset">
                        <div class="uenf-preset-preview" style="background: linear-gradient(45deg, #ff7e5f 0%, #feb47b 100%);"></div>
                        <span><?php _e('Pôr do Sol', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="uenf-preset-btn" data-preset="ocean">
                        <div class="uenf-preset-preview" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                        <span><?php _e('Oceano', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="uenf-preset-btn" data-preset="forest">
                        <div class="uenf-preset-preview" style="background: linear-gradient(90deg, #134e5e 0%, #71b280 100%);"></div>
                        <span><?php _e('Floresta', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="uenf-preset-btn" data-preset="fire">
                        <div class="uenf-preset-preview" style="background: linear-gradient(45deg, #f12711 0%, #f5af19 100%);"></div>
                        <span><?php _e('Fogo', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="uenf-preset-btn" data-preset="neon">
                        <div class="uenf-preset-preview" style="background: linear-gradient(45deg, #12c2e9 0%, #c471ed 50%, #f64f59 100%);"></div>
                        <span><?php _e('Neon', 'cct'); ?></span>
                    </button>
                    
                    <button type="button" class="uenf-preset-btn" data-preset="gold">
                        <div class="uenf-preset-preview" style="background: linear-gradient(45deg, #ffd700 0%, #ffed4e 50%, #ff9500 100%);"></div>
                        <span><?php _e('Ouro', 'cct'); ?></span>
                    </button>
                </div>
            </div>
            
            <!-- Ações do gerador -->
            <div class="uenf-generator-actions">
                <button type="button" class="button button-primary uenf-save-gradient">
                    <?php _e('💾 Salvar Gradiente', 'cct'); ?>
                </button>
                <button type="button" class="button uenf-export-gradient">
                    <?php _e('📤 Exportar', 'cct'); ?>
                </button>
                <button type="button" class="button uenf-reset-generator">
                    <?php _e('🔄 Reset', 'cct'); ?>
                </button>
            </div>
        </div>
        
        <style>
        .uenf-gradient-generator {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .uenf-gradient-preview-area {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-gradient-preview-box {
            height: 150px;
            border-radius: 8px;
            background: linear-gradient(45deg, #ff7e5f 0%, #feb47b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .uenf-preview-content {
            text-align: center;
            color: white;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }
        
        .uenf-preview-content h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        
        .uenf-preview-content p {
            margin: 0;
            font-size: 12px;
            opacity: 0.9;
        }
        
        .uenf-gradient-code {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .uenf-gradient-code label {
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-gradient-code textarea {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            font-size: 11px;
            resize: none;
            height: 40px;
        }
        
        .uenf-copy-css {
            font-size: 11px;
            padding: 8px 12px;
        }
        
        .uenf-gradient-settings {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-setting-group {
            margin-bottom: 20px;
        }
        
        .uenf-setting-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .uenf-setting-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .uenf-direction-control {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .uenf-direction-control input[type="range"] {
            flex: 1;
        }
        
        .uenf-direction-control span {
            min-width: 40px;
            font-size: 12px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-direction-presets {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
        }
        
        .uenf-direction-btn {
            padding: 8px;
            border: 1px solid #ddd;
            background: #f8f9fa;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .uenf-direction-btn:hover {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        .uenf-angle-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .uenf-angle-control input[type="range"] {
            flex: 1;
        }
        
        .uenf-color-editor {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-color-editor h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-color-stops {
            margin-bottom: 15px;
        }
        
        .uenf-color-stop {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        
        .uenf-color-preview {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 1px #ddd;
        }
        
        .uenf-color-input {
            width: 50px;
            height: 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .uenf-position-input {
            flex: 1;
        }
        
        .uenf-position-value {
            min-width: 35px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-remove-color {
            width: 25px;
            height: 25px;
            border: none;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .uenf-remove-color:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .uenf-color-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .uenf-color-actions .button {
            font-size: 11px;
            padding: 8px 12px;
        }
        
        .uenf-quick-presets {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-quick-presets h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-preset-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .uenf-preset-btn {
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
        
        .uenf-preset-btn:hover {
            border-color: #0073aa;
            background: #f0f8ff;
        }
        
        .uenf-preset-preview {
            width: 40px;
            height: 25px;
            border-radius: 4px;
            border: 1px solid rgba(0,0,0,0.1);
        }
        
        .uenf-preset-btn span {
            font-size: 10px;
            color: #333;
            text-align: center;
        }
        
        .uenf-generator-actions {
            display: flex;
            gap: 10px;
            padding: 20px;
            background: white;
            justify-content: center;
        }
        
        .uenf-generator-actions .button {
            font-size: 12px;
            padding: 10px 16px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Aplicador de Gradientes
 */
class UENF_Gradient_Applicator_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_gradient_applicator';
    
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
        
        <div class="uenf-gradient-applicator">
            <!-- Seletor de elementos -->
            <div class="uenf-element-selector">
                <h4><?php _e('Aplicar Gradiente a:', 'cct'); ?></h4>
                
                <div class="uenf-element-options">
                    <label class="uenf-element-option">
                        <input type="checkbox" class="uenf-apply-to" data-target="backgrounds" checked>
                        <span class="uenf-option-icon">🎨</span>
                        <span class="uenf-option-text"><?php _e('Fundos', 'cct'); ?></span>
                    </label>
                    
                    <label class="uenf-element-option">
                        <input type="checkbox" class="uenf-apply-to" data-target="buttons">
                        <span class="uenf-option-icon">🔘</span>
                        <span class="uenf-option-text"><?php _e('Botões', 'cct'); ?></span>
                    </label>
                    
                    <label class="uenf-element-option">
                        <input type="checkbox" class="uenf-apply-to" data-target="text">
                        <span class="uenf-option-icon">📝</span>
                        <span class="uenf-option-text"><?php _e('Texto', 'cct'); ?></span>
                    </label>
                    
                    <label class="uenf-element-option">
                        <input type="checkbox" class="uenf-apply-to" data-target="borders">
                        <span class="uenf-option-icon">🔲</span>
                        <span class="uenf-option-text"><?php _e('Bordas', 'cct'); ?></span>
                    </label>
                    
                    <label class="uenf-element-option">
                        <input type="checkbox" class="uenf-apply-to" data-target="headers">
                        <span class="uenf-option-icon">📰</span>
                        <span class="uenf-option-text"><?php _e('Cabeçalhos', 'cct'); ?></span>
                    </label>
                    
                    <label class="uenf-element-option">
                        <input type="checkbox" class="uenf-apply-to" data-target="cards">
                        <span class="uenf-option-icon">🃏</span>
                        <span class="uenf-option-text"><?php _e('Cards', 'cct'); ?></span>
                    </label>
                </div>
            </div>
            
            <!-- Preview de aplicação -->
            <div class="uenf-application-preview">
                <h4><?php _e('Preview da Aplicação', 'cct'); ?></h4>
                
                <div class="uenf-preview-samples">
                    <div class="uenf-sample uenf-sample-background">
                        <h5><?php _e('Fundo', 'cct'); ?></h5>
                        <div class="uenf-sample-element uenf-bg-sample">
                            <p><?php _e('Exemplo de fundo com gradiente', 'cct'); ?></p>
                        </div>
                    </div>
                    
                    <div class="uenf-sample uenf-sample-button">
                        <h5><?php _e('Botão', 'cct'); ?></h5>
                        <button class="uenf-sample-element uenf-btn-sample">
                            <?php _e('Botão com Gradiente', 'cct'); ?>
                        </button>
                    </div>
                    
                    <div class="uenf-sample uenf-sample-text">
                        <h5><?php _e('Texto', 'cct'); ?></h5>
                        <h3 class="uenf-sample-element uenf-text-sample">
                            <?php _e('Texto com Gradiente', 'cct'); ?>
                        </h3>
                    </div>
                    
                    <div class="uenf-sample uenf-sample-border">
                        <h5><?php _e('Borda', 'cct'); ?></h5>
                        <div class="uenf-sample-element uenf-border-sample">
                            <p><?php _e('Elemento com borda gradiente', 'cct'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Configurações de aplicação -->
            <div class="uenf-application-settings">
                <h4><?php _e('Configurações de Aplicação', 'cct'); ?></h4>
                
                <div class="uenf-setting-row">
                    <label><?php _e('Intensidade:', 'cct'); ?></label>
                    <input type="range" class="uenf-intensity-slider" min="0.1" max="1" step="0.1" value="1">
                    <span class="uenf-intensity-value">100%</span>
                </div>
                
                <div class="uenf-setting-row">
                    <label><?php _e('Opacidade:', 'cct'); ?></label>
                    <input type="range" class="uenf-opacity-slider" min="0.1" max="1" step="0.1" value="1">
                    <span class="uenf-opacity-value">100%</span>
                </div>
                
                <div class="uenf-setting-row">
                    <label class="uenf-checkbox-label">
                        <input type="checkbox" class="uenf-enable-hover" checked>
                        <?php _e('Ativar efeitos de hover', 'cct'); ?>
                    </label>
                </div>
                
                <div class="uenf-setting-row">
                    <label class="uenf-checkbox-label">
                        <input type="checkbox" class="uenf-enable-animation" checked>
                        <?php _e('Ativar animações suaves', 'cct'); ?>
                    </label>
                </div>
            </div>
            
            <!-- Ações de aplicação -->
            <div class="uenf-application-actions">
                <button type="button" class="button button-primary uenf-apply-gradient">
                    <?php _e('✨ Aplicar Gradiente', 'cct'); ?>
                </button>
                <button type="button" class="button uenf-preview-live">
                    <?php _e('👁️ Preview ao Vivo', 'cct'); ?>
                </button>
                <button type="button" class="button uenf-reset-application">
                    <?php _e('🔄 Reset', 'cct'); ?>
                </button>
            </div>
        </div>
        
        <style>
        .uenf-gradient-applicator {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .uenf-element-selector {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-element-selector h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-element-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .uenf-element-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .uenf-element-option:hover {
            border-color: #0073aa;
            background: #f0f8ff;
        }
        
        .uenf-element-option input[type="checkbox"] {
            margin: 0;
        }
        
        .uenf-option-icon {
            font-size: 16px;
        }
        
        .uenf-option-text {
            font-size: 12px;
            color: #333;
        }
        
        .uenf-application-preview {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-application-preview h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-preview-samples {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .uenf-sample {
            text-align: center;
        }
        
        .uenf-sample h5 {
            margin: 0 0 8px 0;
            font-size: 11px;
            color: #666;
        }
        
        .uenf-sample-element {
            transition: all 0.3s ease;
        }
        
        .uenf-bg-sample {
            padding: 15px;
            border-radius: 6px;
            background: linear-gradient(45deg, #ff7e5f 0%, #feb47b 100%);
            color: white;
            font-size: 11px;
        }
        
        .uenf-btn-sample {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background: linear-gradient(45deg, #ff7e5f 0%, #feb47b 100%);
            color: white;
            font-size: 11px;
            cursor: pointer;
        }
        
        .uenf-text-sample {
            margin: 0;
            font-size: 14px;
            background: linear-gradient(45deg, #ff7e5f 0%, #feb47b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .uenf-border-sample {
            padding: 10px;
            border: 2px solid;
            border-image: linear-gradient(45deg, #ff7e5f 0%, #feb47b 100%) 1;
            border-radius: 6px;
            font-size: 11px;
        }
        
        .uenf-application-settings {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-application-settings h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-setting-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .uenf-setting-row label {
            min-width: 80px;
            font-size: 12px;
            color: #333;
        }
        
        .uenf-setting-row input[type="range"] {
            flex: 1;
        }
        
        .uenf-intensity-value,
        .uenf-opacity-value {
            min-width: 40px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            cursor: pointer;
        }
        
        .uenf-application-actions {
            display: flex;
            gap: 10px;
            padding: 20px;
            background: white;
            justify-content: center;
        }
        
        .uenf-application-actions .button {
            font-size: 12px;
            padding: 10px 16px;
        }
        </style>
        <?php
    }
}