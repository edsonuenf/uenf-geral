<?php
/**
 * Controles Personalizados para Biblioteca de Padrões
 * 
 * Controles avançados para gerenciamento de padrões incluindo:
 * - Browser visual de padrões
 * - Configurador interativo
 * - Preview em tempo real
 * - Templates prontos
 * - Export/Import de configurações
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
 * Controle Browser de Padrões
 */
class CCT_Pattern_Browser_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_pattern_browser';
    
    /**
     * Padrões disponíveis
     * 
     * @var array
     */
    public $patterns = array();
    
    /**
     * Tipo de padrão (faq, pricing, team, portfolio)
     * 
     * @var string
     */
    public $pattern_type = 'faq';
    
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
        
        <div class="cct-pattern-browser" data-pattern-type="<?php echo esc_attr($this->pattern_type); ?>">
            <!-- Filtros e busca -->
            <div class="cct-pattern-filters">
                <div class="cct-pattern-search">
                    <input type="text" class="cct-search-input" placeholder="<?php _e('Buscar padrões...', 'cct'); ?>">
                    <span class="cct-search-icon">🔍</span>
                </div>
                
                <div class="cct-pattern-view-toggle">
                    <button type="button" class="cct-view-btn active" data-view="grid" title="<?php _e('Visualização em Grid', 'cct'); ?>">⊞</button>
                    <button type="button" class="cct-view-btn" data-view="list" title="<?php _e('Visualização em Lista', 'cct'); ?>">☰</button>
                </div>
            </div>
            
            <!-- Grid de padrões -->
            <div class="cct-pattern-grid" id="cct-pattern-grid">
                <?php foreach ($this->patterns as $pattern_key => $pattern): ?>
                    <div class="cct-pattern-item" data-pattern="<?php echo esc_attr($pattern_key); ?>">
                        <div class="cct-pattern-preview">
                            <div class="cct-pattern-image">
                                <?php if (!empty($pattern['preview_image'])): ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/images/patterns/' . $pattern['preview_image']); ?>" alt="<?php echo esc_attr($pattern['name']); ?>">
                                <?php else: ?>
                                    <div class="cct-pattern-placeholder">
                                        <span class="cct-pattern-icon">📄</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="cct-pattern-overlay">
                                <div class="cct-pattern-actions">
                                    <button type="button" class="cct-action-btn cct-preview-btn" data-pattern="<?php echo esc_attr($pattern_key); ?>" title="<?php _e('Preview', 'cct'); ?>">
                                        👁️
                                    </button>
                                    <button type="button" class="cct-action-btn cct-apply-btn" data-pattern="<?php echo esc_attr($pattern_key); ?>" title="<?php _e('Aplicar', 'cct'); ?>">
                                        ✓
                                    </button>
                                    <button type="button" class="cct-action-btn cct-copy-btn" data-pattern="<?php echo esc_attr($pattern_key); ?>" title="<?php _e('Copiar Código', 'cct'); ?>">
                                        📋
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="cct-pattern-info">
                            <h4 class="cct-pattern-name"><?php echo esc_html($pattern['name']); ?></h4>
                            <p class="cct-pattern-description"><?php echo esc_html($pattern['description']); ?></p>
                            
                            <div class="cct-pattern-features">
                                <strong><?php _e('Recursos:', 'cct'); ?></strong>
                                <ul>
                                    <?php foreach ($pattern['features'] as $feature): ?>
                                        <li><?php echo esc_html($feature); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <div class="cct-pattern-meta">
                                <span class="cct-pattern-template">
                                    <strong><?php _e('Template:', 'cct'); ?></strong> 
                                    <?php echo esc_html(ucfirst($pattern['template'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Preview modal -->
            <div class="cct-pattern-modal" id="cct-pattern-modal" style="display: none;">
                <div class="cct-modal-backdrop"></div>
                <div class="cct-modal-content">
                    <div class="cct-modal-header">
                        <h3 class="cct-modal-title"><?php _e('Preview do Padrão', 'cct'); ?></h3>
                        <button type="button" class="cct-modal-close">×</button>
                    </div>
                    
                    <div class="cct-modal-body">
                        <div class="cct-preview-area" id="cct-preview-area">
                            <!-- Preview será carregado aqui -->
                        </div>
                        
                        <div class="cct-preview-code">
                            <h4><?php _e('Código do Shortcode:', 'cct'); ?></h4>
                            <textarea class="cct-code-output" id="cct-code-output" readonly></textarea>
                            <button type="button" class="button cct-copy-code"><?php _e('📋 Copiar Código', 'cct'); ?></button>
                        </div>
                    </div>
                    
                    <div class="cct-modal-footer">
                        <button type="button" class="button button-primary cct-apply-pattern"><?php _e('Aplicar Padrão', 'cct'); ?></button>
                        <button type="button" class="button cct-close-modal"><?php _e('Fechar', 'cct'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .cct-pattern-browser {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .cct-pattern-filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-pattern-search {
            position: relative;
            flex: 1;
            max-width: 300px;
        }
        
        .cct-search-input {
            width: 100%;
            padding: 8px 35px 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }
        
        .cct-search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            pointer-events: none;
        }
        
        .cct-pattern-view-toggle {
            display: flex;
            gap: 5px;
        }
        
        .cct-view-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        .cct-view-btn.active {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        .cct-pattern-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .cct-pattern-grid.list-view {
            grid-template-columns: 1fr;
        }
        
        .cct-pattern-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .cct-pattern-item:hover {
            border-color: #0073aa;
            box-shadow: 0 4px 12px rgba(0, 115, 170, 0.1);
        }
        
        .cct-pattern-preview {
            position: relative;
            height: 150px;
            overflow: hidden;
        }
        
        .cct-pattern-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }
        
        .cct-pattern-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        
        .cct-pattern-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: #999;
        }
        
        .cct-pattern-icon {
            font-size: 48px;
        }
        
        .cct-pattern-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .cct-pattern-item:hover .cct-pattern-overlay {
            opacity: 1;
        }
        
        .cct-pattern-actions {
            display: flex;
            gap: 10px;
        }
        
        .cct-action-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .cct-action-btn:hover {
            background: white;
            transform: scale(1.1);
        }
        
        .cct-pattern-info {
            padding: 15px;
        }
        
        .cct-pattern-name {
            margin: 0 0 8px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-pattern-description {
            margin: 0 0 12px 0;
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .cct-pattern-features {
            margin-bottom: 12px;
            font-size: 11px;
        }
        
        .cct-pattern-features strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        
        .cct-pattern-features ul {
            margin: 0;
            padding-left: 15px;
            color: #666;
        }
        
        .cct-pattern-features li {
            margin-bottom: 2px;
        }
        
        .cct-pattern-meta {
            font-size: 10px;
            color: #888;
        }
        
        .cct-pattern-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999999;
        }
        
        .cct-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
        }
        
        .cct-modal-content {
            position: relative;
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            margin: 5vh auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .cct-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .cct-modal-title {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        
        .cct-modal-close {
            width: 30px;
            height: 30px;
            border: none;
            background: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }
        
        .cct-modal-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        
        .cct-preview-area {
            min-height: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f8f9fa;
        }
        
        .cct-preview-code h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #333;
        }
        
        .cct-code-output {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            font-size: 11px;
            resize: none;
            margin-bottom: 10px;
        }
        
        .cct-copy-code {
            font-size: 11px;
            padding: 6px 12px;
        }
        
        .cct-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px;
            border-top: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .cct-modal-footer .button {
            font-size: 12px;
            padding: 8px 16px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Configurador de Padrões
 */
class CCT_Pattern_Configurator_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_pattern_configurator';
    
    /**
     * Configurações do padrão
     * 
     * @var array
     */
    public $pattern_settings = array();
    
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
        
        <div class="cct-pattern-configurator">
            <!-- Configurações de cores -->
            <div class="cct-config-section">
                <h4><?php _e('Cores', 'cct'); ?></h4>
                
                <div class="cct-color-grid">
                    <div class="cct-color-item">
                        <label><?php _e('Primária:', 'cct'); ?></label>
                        <input type="color" class="cct-color-input" data-setting="primary" value="#0073aa">
                    </div>
                    
                    <div class="cct-color-item">
                        <label><?php _e('Secundária:', 'cct'); ?></label>
                        <input type="color" class="cct-color-input" data-setting="secondary" value="#666666">
                    </div>
                    
                    <div class="cct-color-item">
                        <label><?php _e('Destaque:', 'cct'); ?></label>
                        <input type="color" class="cct-color-input" data-setting="accent" value="#ff6b6b">
                    </div>
                    
                    <div class="cct-color-item">
                        <label><?php _e('Fundo:', 'cct'); ?></label>
                        <input type="color" class="cct-color-input" data-setting="background" value="#ffffff">
                    </div>
                </div>
            </div>
            
            <!-- Configurações de tipografia -->
            <div class="cct-config-section">
                <h4><?php _e('Tipografia', 'cct'); ?></h4>
                
                <div class="cct-typography-controls">
                    <div class="cct-control-row">
                        <label><?php _e('Fonte dos Títulos:', 'cct'); ?></label>
                        <select class="cct-font-select" data-setting="heading_font">
                            <option value="Roboto">Roboto</option>
                            <option value="Open Sans">Open Sans</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Lato">Lato</option>
                        </select>
                    </div>
                    
                    <div class="cct-control-row">
                        <label><?php _e('Fonte do Corpo:', 'cct'); ?></label>
                        <select class="cct-font-select" data-setting="body_font">
                            <option value="Open Sans">Open Sans</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Source Sans Pro">Source Sans Pro</option>
                            <option value="Lato">Lato</option>
                        </select>
                    </div>
                    
                    <div class="cct-control-row">
                        <label><?php _e('Tamanho Base:', 'cct'); ?></label>
                        <div class="cct-range-control">
                            <input type="range" class="cct-range-input" data-setting="base_size" min="12" max="20" value="16">
                            <span class="cct-range-value">16px</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Configurações de espaçamento -->
            <div class="cct-config-section">
                <h4><?php _e('Espaçamento', 'cct'); ?></h4>
                
                <div class="cct-spacing-controls">
                    <div class="cct-control-row">
                        <label><?php _e('Padding da Seção:', 'cct'); ?></label>
                        <div class="cct-range-control">
                            <input type="range" class="cct-range-input" data-setting="section_padding" min="20" max="100" value="60">
                            <span class="cct-range-value">60px</span>
                        </div>
                    </div>
                    
                    <div class="cct-control-row">
                        <label><?php _e('Margem dos Elementos:', 'cct'); ?></label>
                        <div class="cct-range-control">
                            <input type="range" class="cct-range-input" data-setting="element_margin" min="10" max="40" value="20">
                            <span class="cct-range-value">20px</span>
                        </div>
                    </div>
                    
                    <div class="cct-control-row">
                        <label><?php _e('Border Radius:', 'cct'); ?></label>
                        <div class="cct-range-control">
                            <input type="range" class="cct-range-input" data-setting="border_radius" min="0" max="20" value="8">
                            <span class="cct-range-value">8px</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Configurações de animação -->
            <div class="cct-config-section">
                <h4><?php _e('Animações', 'cct'); ?></h4>
                
                <div class="cct-animation-controls">
                    <div class="cct-control-row">
                        <label class="cct-checkbox-label">
                            <input type="checkbox" class="cct-checkbox-input" data-setting="animations_enabled" checked>
                            <?php _e('Ativar Animações', 'cct'); ?>
                        </label>
                    </div>
                    
                    <div class="cct-control-row">
                        <label><?php _e('Duração:', 'cct'); ?></label>
                        <div class="cct-range-control">
                            <input type="range" class="cct-range-input" data-setting="animation_duration" min="0.1" max="1" step="0.1" value="0.3">
                            <span class="cct-range-value">0.3s</span>
                        </div>
                    </div>
                    
                    <div class="cct-control-row">
                        <label><?php _e('Easing:', 'cct'); ?></label>
                        <select class="cct-easing-select" data-setting="animation_easing">
                            <option value="ease">Ease</option>
                            <option value="ease-in">Ease In</option>
                            <option value="ease-out">Ease Out</option>
                            <option value="ease-in-out" selected>Ease In Out</option>
                            <option value="linear">Linear</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Preview em tempo real -->
            <div class="cct-config-section">
                <h4><?php _e('Preview', 'cct'); ?></h4>
                
                <div class="cct-live-preview" id="cct-live-preview">
                    <div class="cct-preview-element">
                        <h3><?php _e('Título de Exemplo', 'cct'); ?></h3>
                        <p><?php _e('Este é um exemplo de como o padrão ficará com as configurações atuais.', 'cct'); ?></p>
                        <button class="cct-preview-button"><?php _e('Botão de Exemplo', 'cct'); ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Ações -->
            <div class="cct-config-actions">
                <button type="button" class="button button-primary cct-apply-config"><?php _e('Aplicar Configurações', 'cct'); ?></button>
                <button type="button" class="button cct-reset-config"><?php _e('🔄 Reset', 'cct'); ?></button>
                <button type="button" class="button cct-export-config"><?php _e('📤 Exportar', 'cct'); ?></button>
            </div>
        </div>
        
        <style>
        .cct-pattern-configurator {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .cct-config-section {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .cct-config-section:last-child {
            border-bottom: none;
        }
        
        .cct-config-section h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 5px;
        }
        
        .cct-color-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .cct-color-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cct-color-item label {
            font-size: 12px;
            color: #333;
            min-width: 70px;
        }
        
        .cct-color-input {
            width: 50px;
            height: 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .cct-control-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .cct-control-row:last-child {
            margin-bottom: 0;
        }
        
        .cct-control-row label {
            font-size: 12px;
            color: #333;
            min-width: 120px;
        }
        
        .cct-font-select,
        .cct-easing-select {
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            min-width: 120px;
        }
        
        .cct-range-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cct-range-input {
            flex: 1;
            min-width: 80px;
        }
        
        .cct-range-value {
            min-width: 40px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
            text-align: right;
        }
        
        .cct-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            cursor: pointer;
        }
        
        .cct-checkbox-input {
            margin: 0;
        }
        
        .cct-live-preview {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            min-height: 120px;
        }
        
        .cct-preview-element {
            text-align: center;
        }
        
        .cct-preview-element h3 {
            margin: 0 0 10px 0;
            color: var(--preview-primary, #0073aa);
            font-family: var(--preview-heading-font, 'Roboto');
        }
        
        .cct-preview-element p {
            margin: 0 0 15px 0;
            color: var(--preview-text, #333);
            font-family: var(--preview-body-font, 'Open Sans');
            font-size: var(--preview-base-size, 16px);
        }
        
        .cct-preview-button {
            padding: 10px 20px;
            background: var(--preview-accent, #ff6b6b);
            color: white;
            border: none;
            border-radius: var(--preview-border-radius, 8px);
            cursor: pointer;
            font-family: var(--preview-body-font, 'Open Sans');
            transition: all var(--preview-duration, 0.3s) var(--preview-easing, ease-in-out);
        }
        
        .cct-preview-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .cct-config-actions {
            display: flex;
            gap: 10px;
            padding: 20px;
            background: #f9f9f9;
            border-top: 1px solid #ddd;
        }
        
        .cct-config-actions .button {
            font-size: 11px;
            padding: 8px 16px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Template Selector
 */
class CCT_Template_Selector_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_template_selector';
    
    /**
     * Templates disponíveis
     * 
     * @var array
     */
    public $templates = array();
    
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
        
        <div class="cct-template-selector">
            <div class="cct-template-grid">
                <?php foreach ($this->templates as $template_key => $template): ?>
                    <div class="cct-template-item" data-template="<?php echo esc_attr($template_key); ?>">
                        <div class="cct-template-preview">
                            <div class="cct-template-colors">
                                <?php foreach ($template['colors'] as $color): ?>
                                    <span class="cct-color-dot" style="background-color: <?php echo esc_attr($color); ?>"></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="cct-template-sections">
                                <?php foreach ($template['sections'] as $section): ?>
                                    <span class="cct-section-tag"><?php echo esc_html($section); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="cct-template-info">
                            <h4><?php echo esc_html($template['name']); ?></h4>
                            <p><?php echo esc_html($template['description']); ?></p>
                            
                            <div class="cct-template-actions">
                                <button type="button" class="button cct-preview-template" data-template="<?php echo esc_attr($template_key); ?>">
                                    <?php _e('👁️ Preview', 'cct'); ?>
                                </button>
                                <button type="button" class="button button-primary cct-apply-template" data-template="<?php echo esc_attr($template_key); ?>">
                                    <?php _e('Aplicar', 'cct'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <style>
        .cct-template-selector {
            margin-top: 10px;
        }
        
        .cct-template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .cct-template-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .cct-template-item:hover {
            border-color: #0073aa;
            box-shadow: 0 4px 12px rgba(0, 115, 170, 0.1);
        }
        
        .cct-template-preview {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-template-colors {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .cct-color-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .cct-template-sections {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .cct-section-tag {
            padding: 4px 8px;
            background: #e0e0e0;
            border-radius: 12px;
            font-size: 10px;
            color: #666;
        }
        
        .cct-template-info {
            padding: 15px;
        }
        
        .cct-template-info h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #333;
        }
        
        .cct-template-info p {
            margin: 0 0 15px 0;
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .cct-template-actions {
            display: flex;
            gap: 8px;
        }
        
        .cct-template-actions .button {
            font-size: 11px;
            padding: 6px 12px;
            flex: 1;
        }
        </style>
        <?php
    }
}