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
 * @package UENF_Theme
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
class UENF_Pattern_Browser_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_pattern_browser';
    
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
        
        <div class="uenf-pattern-browser" data-pattern-type="<?php echo esc_attr($this->pattern_type); ?>">
            <!-- Filtros e busca -->
            <div class="uenf-pattern-filters">
                <div class="uenf-pattern-search">
                    <input type="text" class="uenf-search-input" placeholder="<?php _e('Buscar padrões...', 'cct'); ?>">
                    <span class="uenf-search-icon">🔍</span>
                </div>
                
                <div class="uenf-pattern-view-toggle">
                    <button type="button" class="uenf-view-btn active" data-view="grid" title="<?php _e('Visualização em Grid', 'cct'); ?>">⊞</button>
                    <button type="button" class="uenf-view-btn" data-view="list" title="<?php _e('Visualização em Lista', 'cct'); ?>">☰</button>
                </div>
            </div>
            
            <!-- Grid de padrões -->
            <div class="uenf-pattern-grid" id="uenf-pattern-grid">
                <?php foreach ($this->patterns as $pattern_key => $pattern): ?>
                    <div class="uenf-pattern-item" data-pattern="<?php echo esc_attr($pattern_key); ?>">
                        <div class="uenf-pattern-preview">
                            <div class="uenf-pattern-image">
                                <?php if (!empty($pattern['preview_image'])): ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/images/patterns/' . $pattern['preview_image']); ?>" alt="<?php echo esc_attr($pattern['name']); ?>">
                                <?php else: ?>
                                    <div class="uenf-pattern-placeholder">
                                        <span class="uenf-pattern-icon">📄</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="uenf-pattern-overlay">
                                <div class="uenf-pattern-actions">
                                    <button type="button" class="uenf-action-btn uenf-preview-btn" data-pattern="<?php echo esc_attr($pattern_key); ?>" title="<?php _e('Preview', 'cct'); ?>">
                                        👁️
                                    </button>
                                    <button type="button" class="uenf-action-btn uenf-apply-btn" data-pattern="<?php echo esc_attr($pattern_key); ?>" title="<?php _e('Aplicar', 'cct'); ?>">
                                        ✓
                                    </button>
                                    <button type="button" class="uenf-action-btn uenf-copy-btn" data-pattern="<?php echo esc_attr($pattern_key); ?>" title="<?php _e('Copiar Código', 'cct'); ?>">
                                        📋
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="uenf-pattern-info">
                            <h4 class="uenf-pattern-name"><?php echo esc_html($pattern['name']); ?></h4>
                            <p class="uenf-pattern-description"><?php echo esc_html($pattern['description']); ?></p>
                            
                            <div class="uenf-pattern-features">
                                <strong><?php _e('Recursos:', 'cct'); ?></strong>
                                <ul>
                                    <?php foreach ($pattern['features'] as $feature): ?>
                                        <li><?php echo esc_html($feature); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <div class="uenf-pattern-meta">
                                <span class="uenf-pattern-template">
                                    <strong><?php _e('Template:', 'cct'); ?></strong> 
                                    <?php echo esc_html(ucfirst($pattern['template'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Preview modal -->
            <div class="uenf-pattern-modal" id="uenf-pattern-modal" style="display: none;">
                <div class="uenf-modal-backdrop"></div>
                <div class="uenf-modal-content">
                    <div class="uenf-modal-header">
                        <h3 class="uenf-modal-title"><?php _e('Preview do Padrão', 'cct'); ?></h3>
                        <button type="button" class="uenf-modal-close">×</button>
                    </div>
                    
                    <div class="uenf-modal-body">
                        <div class="uenf-preview-area" id="uenf-preview-area">
                            <!-- Preview será carregado aqui -->
                        </div>
                        
                        <div class="uenf-preview-code">
                            <h4><?php _e('Código do Shortcode:', 'cct'); ?></h4>
                            <textarea class="uenf-code-output" id="uenf-code-output" readonly></textarea>
                            <button type="button" class="button uenf-copy-code"><?php _e('📋 Copiar Código', 'cct'); ?></button>
                        </div>
                    </div>
                    
                    <div class="uenf-modal-footer">
                        <button type="button" class="button button-primary uenf-apply-pattern"><?php _e('Aplicar Padrão', 'cct'); ?></button>
                        <button type="button" class="button uenf-close-modal"><?php _e('Fechar', 'cct'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-pattern-browser {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .uenf-pattern-filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-pattern-search {
            position: relative;
            flex: 1;
            max-width: 300px;
        }
        
        .uenf-search-input {
            width: 100%;
            padding: 8px 35px 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }
        
        .uenf-search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            pointer-events: none;
        }
        
        .uenf-pattern-view-toggle {
            display: flex;
            gap: 5px;
        }
        
        .uenf-view-btn {
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
        
        .uenf-view-btn.active {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        .uenf-pattern-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .uenf-pattern-grid.list-view {
            grid-template-columns: 1fr;
        }
        
        .uenf-pattern-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .uenf-pattern-item:hover {
            border-color: #0073aa;
            box-shadow: 0 4px 12px rgba(0, 115, 170, 0.1);
        }
        
        .uenf-pattern-preview {
            position: relative;
            height: 150px;
            overflow: hidden;
        }
        
        .uenf-pattern-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }
        
        .uenf-pattern-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        
        .uenf-pattern-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: #999;
        }
        
        .uenf-pattern-icon {
            font-size: 48px;
        }
        
        .uenf-pattern-overlay {
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
        
        .uenf-pattern-item:hover .uenf-pattern-overlay {
            opacity: 1;
        }
        
        .uenf-pattern-actions {
            display: flex;
            gap: 10px;
        }
        
        .uenf-action-btn {
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
        
        .uenf-action-btn:hover {
            background: white;
            transform: scale(1.1);
        }
        
        .uenf-pattern-info {
            padding: 15px;
        }
        
        .uenf-pattern-name {
            margin: 0 0 8px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-pattern-description {
            margin: 0 0 12px 0;
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .uenf-pattern-features {
            margin-bottom: 12px;
            font-size: 11px;
        }
        
        .uenf-pattern-features strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        
        .uenf-pattern-features ul {
            margin: 0;
            padding-left: 15px;
            color: #666;
        }
        
        .uenf-pattern-features li {
            margin-bottom: 2px;
        }
        
        .uenf-pattern-meta {
            font-size: 10px;
            color: #888;
        }
        
        .uenf-pattern-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999999;
        }
        
        .uenf-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
        }
        
        .uenf-modal-content {
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
        
        .uenf-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .uenf-modal-title {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        
        .uenf-modal-close {
            width: 30px;
            height: 30px;
            border: none;
            background: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }
        
        .uenf-modal-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        
        .uenf-preview-area {
            min-height: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f8f9fa;
        }
        
        .uenf-preview-code h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #333;
        }
        
        .uenf-code-output {
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
        
        .uenf-copy-code {
            font-size: 11px;
            padding: 6px 12px;
        }
        
        .uenf-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px;
            border-top: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .uenf-modal-footer .button {
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
class UENF_Pattern_Configurator_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_pattern_configurator';
    
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
        
        <div class="uenf-pattern-configurator">
            <!-- Configurações de cores -->
            <div class="uenf-config-section">
                <h4><?php _e('Cores', 'cct'); ?></h4>
                
                <div class="uenf-color-grid">
                    <div class="uenf-color-item">
                        <label><?php _e('Primária:', 'cct'); ?></label>
                        <input type="color" class="uenf-color-input" data-setting="primary" value="#0073aa">
                    </div>
                    
                    <div class="uenf-color-item">
                        <label><?php _e('Secundária:', 'cct'); ?></label>
                        <input type="color" class="uenf-color-input" data-setting="secondary" value="#666666">
                    </div>
                    
                    <div class="uenf-color-item">
                        <label><?php _e('Destaque:', 'cct'); ?></label>
                        <input type="color" class="uenf-color-input" data-setting="accent" value="#ff6b6b">
                    </div>
                    
                    <div class="uenf-color-item">
                        <label><?php _e('Fundo:', 'cct'); ?></label>
                        <input type="color" class="uenf-color-input" data-setting="background" value="#ffffff">
                    </div>
                </div>
            </div>
            
            <!-- Configurações de tipografia -->
            <div class="uenf-config-section">
                <h4><?php _e('Tipografia', 'cct'); ?></h4>
                
                <div class="uenf-typography-controls">
                    <div class="uenf-control-row">
                        <label><?php _e('Fonte dos Títulos:', 'cct'); ?></label>
                        <select class="uenf-font-select" data-setting="heading_font">
                            <option value="Roboto">Roboto</option>
                            <option value="Open Sans">Open Sans</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Lato">Lato</option>
                        </select>
                    </div>
                    
                    <div class="uenf-control-row">
                        <label><?php _e('Fonte do Corpo:', 'cct'); ?></label>
                        <select class="uenf-font-select" data-setting="body_font">
                            <option value="Open Sans">Open Sans</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Source Sans Pro">Source Sans Pro</option>
                            <option value="Lato">Lato</option>
                        </select>
                    </div>
                    
                    <div class="uenf-control-row">
                        <label><?php _e('Tamanho Base:', 'cct'); ?></label>
                        <div class="uenf-range-control">
                            <input type="range" class="uenf-range-input" data-setting="base_size" min="12" max="20" value="16">
                            <span class="uenf-range-value">16px</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Configurações de espaçamento -->
            <div class="uenf-config-section">
                <h4><?php _e('Espaçamento', 'cct'); ?></h4>
                
                <div class="uenf-spacing-controls">
                    <div class="uenf-control-row">
                        <label><?php _e('Padding da Seção:', 'cct'); ?></label>
                        <div class="uenf-range-control">
                            <input type="range" class="uenf-range-input" data-setting="section_padding" min="20" max="100" value="60">
                            <span class="uenf-range-value">60px</span>
                        </div>
                    </div>
                    
                    <div class="uenf-control-row">
                        <label><?php _e('Margem dos Elementos:', 'cct'); ?></label>
                        <div class="uenf-range-control">
                            <input type="range" class="uenf-range-input" data-setting="element_margin" min="10" max="40" value="20">
                            <span class="uenf-range-value">20px</span>
                        </div>
                    </div>
                    
                    <div class="uenf-control-row">
                        <label><?php _e('Border Radius:', 'cct'); ?></label>
                        <div class="uenf-range-control">
                            <input type="range" class="uenf-range-input" data-setting="border_radius" min="0" max="20" value="8">
                            <span class="uenf-range-value">8px</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Configurações de animação -->
            <div class="uenf-config-section">
                <h4><?php _e('Animações', 'cct'); ?></h4>
                
                <div class="uenf-animation-controls">
                    <div class="uenf-control-row">
                        <label class="uenf-checkbox-label">
                            <input type="checkbox" class="uenf-checkbox-input" data-setting="animations_enabled" checked>
                            <?php _e('Ativar Animações', 'cct'); ?>
                        </label>
                    </div>
                    
                    <div class="uenf-control-row">
                        <label><?php _e('Duração:', 'cct'); ?></label>
                        <div class="uenf-range-control">
                            <input type="range" class="uenf-range-input" data-setting="animation_duration" min="0.1" max="1" step="0.1" value="0.3">
                            <span class="uenf-range-value">0.3s</span>
                        </div>
                    </div>
                    
                    <div class="uenf-control-row">
                        <label><?php _e('Easing:', 'cct'); ?></label>
                        <select class="uenf-easing-select" data-setting="animation_easing">
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
            <div class="uenf-config-section">
                <h4><?php _e('Preview', 'cct'); ?></h4>
                
                <div class="uenf-live-preview" id="uenf-live-preview">
                    <div class="uenf-preview-element">
                        <h3><?php _e('Título de Exemplo', 'cct'); ?></h3>
                        <p><?php _e('Este é um exemplo de como o padrão ficará com as configurações atuais.', 'cct'); ?></p>
                        <button class="uenf-preview-button"><?php _e('Botão de Exemplo', 'cct'); ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Ações -->
            <div class="uenf-config-actions">
                <button type="button" class="button button-primary uenf-apply-config"><?php _e('Aplicar Configurações', 'cct'); ?></button>
                <button type="button" class="button uenf-reset-config"><?php _e('🔄 Reset', 'cct'); ?></button>
                <button type="button" class="button uenf-export-config"><?php _e('📤 Exportar', 'cct'); ?></button>
            </div>
        </div>
        
        <style>
        .uenf-pattern-configurator {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .uenf-config-section {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .uenf-config-section:last-child {
            border-bottom: none;
        }
        
        .uenf-config-section h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 5px;
        }
        
        .uenf-color-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .uenf-color-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .uenf-color-item label {
            font-size: 12px;
            color: #333;
            min-width: 70px;
        }
        
        .uenf-color-input {
            width: 50px;
            height: 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .uenf-control-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .uenf-control-row:last-child {
            margin-bottom: 0;
        }
        
        .uenf-control-row label {
            font-size: 12px;
            color: #333;
            min-width: 120px;
        }
        
        .uenf-font-select,
        .uenf-easing-select {
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            min-width: 120px;
        }
        
        .uenf-range-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .uenf-range-input {
            flex: 1;
            min-width: 80px;
        }
        
        .uenf-range-value {
            min-width: 40px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
            text-align: right;
        }
        
        .uenf-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            cursor: pointer;
        }
        
        .uenf-checkbox-input {
            margin: 0;
        }
        
        .uenf-live-preview {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            min-height: 120px;
        }
        
        .uenf-preview-element {
            text-align: center;
        }
        
        .uenf-preview-element h3 {
            margin: 0 0 10px 0;
            color: var(--preview-primary, #0073aa);
            font-family: var(--preview-heading-font, 'Roboto');
        }
        
        .uenf-preview-element p {
            margin: 0 0 15px 0;
            color: var(--preview-text, #333);
            font-family: var(--preview-body-font, 'Open Sans');
            font-size: var(--preview-base-size, 16px);
        }
        
        .uenf-preview-button {
            padding: 10px 20px;
            background: var(--preview-accent, #ff6b6b);
            color: white;
            border: none;
            border-radius: var(--preview-border-radius, 8px);
            cursor: pointer;
            font-family: var(--preview-body-font, 'Open Sans');
            transition: all var(--preview-duration, 0.3s) var(--preview-easing, ease-in-out);
        }
        
        .uenf-preview-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .uenf-config-actions {
            display: flex;
            gap: 10px;
            padding: 20px;
            background: #f9f9f9;
            border-top: 1px solid #ddd;
        }
        
        .uenf-config-actions .button {
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
class UENF_Template_Selector_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_template_selector';
    
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
        
        <div class="uenf-template-selector">
            <div class="uenf-template-grid">
                <?php foreach ($this->templates as $template_key => $template): ?>
                    <div class="uenf-template-item" data-template="<?php echo esc_attr($template_key); ?>">
                        <div class="uenf-template-preview">
                            <div class="uenf-template-colors">
                                <?php foreach ($template['colors'] as $color): ?>
                                    <span class="uenf-color-dot" style="background-color: <?php echo esc_attr($color); ?>"></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="uenf-template-sections">
                                <?php foreach ($template['sections'] as $section): ?>
                                    <span class="uenf-section-tag"><?php echo esc_html($section); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="uenf-template-info">
                            <h4><?php echo esc_html($template['name']); ?></h4>
                            <p><?php echo esc_html($template['description']); ?></p>
                            
                            <div class="uenf-template-actions">
                                <button type="button" class="button uenf-preview-template" data-template="<?php echo esc_attr($template_key); ?>">
                                    <?php _e('👁️ Preview', 'cct'); ?>
                                </button>
                                <button type="button" class="button button-primary uenf-apply-template" data-template="<?php echo esc_attr($template_key); ?>">
                                    <?php _e('Aplicar', 'cct'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <style>
        .uenf-template-selector {
            margin-top: 10px;
        }
        
        .uenf-template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .uenf-template-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .uenf-template-item:hover {
            border-color: #0073aa;
            box-shadow: 0 4px 12px rgba(0, 115, 170, 0.1);
        }
        
        .uenf-template-preview {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-template-colors {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .uenf-color-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .uenf-template-sections {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .uenf-section-tag {
            padding: 4px 8px;
            background: #e0e0e0;
            border-radius: 12px;
            font-size: 10px;
            color: #666;
        }
        
        .uenf-template-info {
            padding: 15px;
        }
        
        .uenf-template-info h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #333;
        }
        
        .uenf-template-info p {
            margin: 0 0 15px 0;
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .uenf-template-actions {
            display: flex;
            gap: 8px;
        }
        
        .uenf-template-actions .button {
            font-size: 11px;
            padding: 6px 12px;
            flex: 1;
        }
        </style>
        <?php
    }
}