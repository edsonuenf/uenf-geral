<?php
/**
 * Controles Personalizados para Sistema de Animações
 * 
 * Controles avançados para o gerenciador de animações incluindo:
 * - Preview visual de animações
 * - Configurador de timing e easing
 * - Seletor de efeitos de hover
 * - Gerenciador de micro-interações
 * - Configurador de transições
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
 * Controle Preview de Animações
 */
class CCT_Animation_Preview_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_animation_preview';
    
    /**
     * Presets de animações
     * 
     * @var array
     */
    public $animation_presets = array();
    
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
        
        <div class="cct-animation-preview">
            <div class="cct-preview-stage">
                <div class="cct-preview-element" id="cct-preview-element">
                    <div class="cct-preview-content">
                        <span class="cct-preview-icon">✨</span>
                        <span class="cct-preview-text"><?php _e('Elemento de Teste', 'cct'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="cct-animation-selector">
                <h4><?php _e('Escolha uma Animação', 'cct'); ?></h4>
                
                <div class="cct-animation-grid">
                    <?php foreach ($this->animation_presets as $preset_id => $preset): ?>
                        <div class="cct-animation-option" data-animation="<?php echo esc_attr($preset_id); ?>">
                            <div class="cct-animation-thumbnail">
                                <div class="cct-thumbnail-element cct-<?php echo esc_attr($preset_id); ?>">
                                    <span class="cct-thumbnail-icon">⚡</span>
                                </div>
                            </div>
                            
                            <div class="cct-animation-info">
                                <h5><?php echo esc_html($preset['name']); ?></h5>
                                <p><?php echo esc_html($preset['description']); ?></p>
                                
                                <div class="cct-animation-meta">
                                    <span class="cct-duration">
                                        <strong><?php _e('Duração:', 'cct'); ?></strong> 
                                        <?php echo esc_html($preset['duration']); ?>
                                    </span>
                                    <span class="cct-easing">
                                        <strong><?php _e('Easing:', 'cct'); ?></strong> 
                                        <?php echo esc_html($preset['easing']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <button type="button" class="button cct-preview-btn" data-animation="<?php echo esc_attr($preset_id); ?>">
                                <?php _e('Preview', 'cct'); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="cct-animation-controls">
                <div class="cct-control-group">
                    <label><?php _e('Duração (s):', 'cct'); ?></label>
                    <input type="range" class="cct-duration-slider" min="0.1" max="3" step="0.1" value="0.3">
                    <span class="cct-duration-value">0.3s</span>
                </div>
                
                <div class="cct-control-group">
                    <label><?php _e('Delay (s):', 'cct'); ?></label>
                    <input type="range" class="cct-delay-slider" min="0" max="2" step="0.1" value="0">
                    <span class="cct-delay-value">0s</span>
                </div>
                
                <div class="cct-control-group">
                    <label><?php _e('Easing:', 'cct'); ?></label>
                    <select class="cct-easing-selector">
                        <option value="linear"><?php _e('Linear', 'cct'); ?></option>
                        <option value="ease"><?php _e('Ease', 'cct'); ?></option>
                        <option value="ease-in"><?php _e('Ease In', 'cct'); ?></option>
                        <option value="ease-out"><?php _e('Ease Out', 'cct'); ?></option>
                        <option value="ease-in-out" selected><?php _e('Ease In Out', 'cct'); ?></option>
                        <option value="cubic-bezier(0.68, -0.55, 0.265, 1.55)"><?php _e('Bounce', 'cct'); ?></option>
                    </select>
                </div>
                
                <div class="cct-control-actions">
                    <button type="button" class="button button-primary cct-play-animation">
                        <?php _e('▶ Reproduzir', 'cct'); ?>
                    </button>
                    <button type="button" class="button cct-reset-animation">
                        <?php _e('🔄 Reset', 'cct'); ?>
                    </button>
                </div>
            </div>
        </div>
        
        <style>
        .cct-animation-preview {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #f9f9f9;
        }
        
        .cct-preview-stage {
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 120px;
            position: relative;
            overflow: hidden;
        }
        
        .cct-preview-element {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transform-origin: center;
            transition: all 0.3s ease;
        }
        
        .cct-preview-content {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-preview-icon {
            font-size: 20px;
        }
        
        .cct-animation-selector {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-animation-selector h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-animation-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .cct-animation-option {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cct-animation-option:hover {
            border-color: #0073aa;
            background: #f0f8ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,115,170,0.1);
        }
        
        .cct-animation-option.selected {
            border-color: #0073aa;
            background: #e3f2fd;
        }
        
        .cct-animation-thumbnail {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .cct-thumbnail-element {
            width: 30px;
            height: 30px;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .cct-animation-option:hover .cct-thumbnail-element {
            animation: pulse 1s ease-in-out;
        }
        
        .cct-animation-info {
            flex: 1;
        }
        
        .cct-animation-info h5 {
            margin: 0 0 5px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-animation-info p {
            margin: 0 0 8px 0;
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }
        
        .cct-animation-meta {
            display: flex;
            gap: 15px;
        }
        
        .cct-animation-meta span {
            font-size: 10px;
            color: #888;
        }
        
        .cct-preview-btn {
            font-size: 11px;
            padding: 6px 12px;
            white-space: nowrap;
        }
        
        .cct-animation-controls {
            padding: 20px;
            background: white;
            border-top: 1px solid #ddd;
        }
        
        .cct-control-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .cct-control-group label {
            min-width: 80px;
            font-size: 12px;
            font-weight: 500;
            color: #333;
        }
        
        .cct-duration-slider,
        .cct-delay-slider {
            flex: 1;
            margin: 0;
        }
        
        .cct-duration-value,
        .cct-delay-value {
            min-width: 40px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .cct-easing-selector {
            flex: 1;
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .cct-control-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .cct-control-actions .button {
            font-size: 12px;
            padding: 8px 16px;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Animações de preview */
        .cct-fade { animation: fadeIn 0.3s ease-in-out; }
        .cct-slide { animation: slideInUp 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .cct-scale { animation: scaleIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        .cct-rotate { animation: rotateIn 0.6s ease-in-out; }
        .cct-bounce { animation: bounceIn 0.8s cubic-bezier(0.215, 0.610, 0.355, 1.000); }
        .cct-flip { animation: flipInX 0.7s ease-in-out; }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        @keyframes rotateIn {
            from { transform: rotate(-180deg); opacity: 0; }
            to { transform: rotate(0deg); opacity: 1; }
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
        
        @keyframes flipInX {
            0% { transform: perspective(400px) rotateX(90deg); opacity: 0; }
            40% { transform: perspective(400px) rotateX(-20deg); }
            60% { transform: perspective(400px) rotateX(10deg); opacity: 1; }
            80% { transform: perspective(400px) rotateX(-5deg); }
            100% { transform: perspective(400px) rotateX(0deg); opacity: 1; }
        }
        </style>
        <?php
    }
}

/**
 * Controle Configurador de Timing
 */
class CCT_Timing_Configurator_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_timing_configurator';
    
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
        
        <div class="cct-timing-configurator">
            <div class="cct-timing-presets">
                <h4><?php _e('Presets de Timing', 'cct'); ?></h4>
                
                <div class="cct-preset-buttons">
                    <button type="button" class="button cct-timing-preset" data-preset="fast">
                        <?php _e('Rápido', 'cct'); ?>
                    </button>
                    <button type="button" class="button cct-timing-preset" data-preset="normal">
                        <?php _e('Normal', 'cct'); ?>
                    </button>
                    <button type="button" class="button cct-timing-preset" data-preset="slow">
                        <?php _e('Lento', 'cct'); ?>
                    </button>
                    <button type="button" class="button cct-timing-preset" data-preset="custom">
                        <?php _e('Personalizado', 'cct'); ?>
                    </button>
                </div>
            </div>
            
            <div class="cct-timing-controls">
                <div class="cct-timing-group">
                    <label><?php _e('Duração:', 'cct'); ?></label>
                    <div class="cct-range-control">
                        <input type="range" class="cct-duration-range" min="0.1" max="3" step="0.1" value="0.3">
                        <input type="number" class="cct-duration-input" min="0.1" max="3" step="0.1" value="0.3">
                        <span class="cct-unit">s</span>
                    </div>
                </div>
                
                <div class="cct-timing-group">
                    <label><?php _e('Delay:', 'cct'); ?></label>
                    <div class="cct-range-control">
                        <input type="range" class="cct-delay-range" min="0" max="2" step="0.1" value="0">
                        <input type="number" class="cct-delay-input" min="0" max="2" step="0.1" value="0">
                        <span class="cct-unit">s</span>
                    </div>
                </div>
                
                <div class="cct-timing-group">
                    <label><?php _e('Easing:', 'cct'); ?></label>
                    <select class="cct-easing-select">
                        <option value="linear"><?php _e('Linear', 'cct'); ?></option>
                        <option value="ease"><?php _e('Ease', 'cct'); ?></option>
                        <option value="ease-in"><?php _e('Ease In', 'cct'); ?></option>
                        <option value="ease-out"><?php _e('Ease Out', 'cct'); ?></option>
                        <option value="ease-in-out" selected><?php _e('Ease In Out', 'cct'); ?></option>
                        <option value="cubic-bezier(0.68, -0.55, 0.265, 1.55)"><?php _e('Bounce', 'cct'); ?></option>
                        <option value="cubic-bezier(0.25, 0.46, 0.45, 0.94)"><?php _e('Smooth', 'cct'); ?></option>
                        <option value="cubic-bezier(0.4, 0.0, 0.2, 1)"><?php _e('Sharp', 'cct'); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="cct-timing-visualizer">
                <h4><?php _e('Visualizador de Curva', 'cct'); ?></h4>
                
                <div class="cct-curve-container">
                    <svg class="cct-curve-svg" viewBox="0 0 200 100">
                        <defs>
                            <linearGradient id="curveGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        
                        <!-- Grid -->
                        <g class="cct-grid">
                            <line x1="0" y1="0" x2="200" y2="0" stroke="#e1e1e1" stroke-width="1"/>
                            <line x1="0" y1="25" x2="200" y2="25" stroke="#f1f1f1" stroke-width="1"/>
                            <line x1="0" y1="50" x2="200" y2="50" stroke="#e1e1e1" stroke-width="1"/>
                            <line x1="0" y1="75" x2="200" y2="75" stroke="#f1f1f1" stroke-width="1"/>
                            <line x1="0" y1="100" x2="200" y2="100" stroke="#e1e1e1" stroke-width="1"/>
                            
                            <line x1="0" y1="0" x2="0" y2="100" stroke="#e1e1e1" stroke-width="1"/>
                            <line x1="50" y1="0" x2="50" y2="100" stroke="#f1f1f1" stroke-width="1"/>
                            <line x1="100" y1="0" x2="100" y2="100" stroke="#e1e1e1" stroke-width="1"/>
                            <line x1="150" y1="0" x2="150" y2="100" stroke="#f1f1f1" stroke-width="1"/>
                            <line x1="200" y1="0" x2="200" y2="100" stroke="#e1e1e1" stroke-width="1"/>
                        </g>
                        
                        <!-- Curve -->
                        <path class="cct-timing-curve" d="M0,100 Q50,50 200,0" stroke="url(#curveGradient)" stroke-width="3" fill="none"/>
                        
                        <!-- Points -->
                        <circle cx="0" cy="100" r="4" fill="#667eea"/>
                        <circle cx="200" cy="0" r="4" fill="#764ba2"/>
                    </svg>
                    
                    <div class="cct-curve-labels">
                        <span class="cct-start-label"><?php _e('Início', 'cct'); ?></span>
                        <span class="cct-end-label"><?php _e('Fim', 'cct'); ?></span>
                    </div>
                </div>
                
                <div class="cct-timing-info">
                    <div class="cct-info-item">
                        <span class="cct-info-label"><?php _e('Função:', 'cct'); ?></span>
                        <span class="cct-info-value" id="cct-easing-function">ease-in-out</span>
                    </div>
                    <div class="cct-info-item">
                        <span class="cct-info-label"><?php _e('Duração Total:', 'cct'); ?></span>
                        <span class="cct-info-value" id="cct-total-duration">0.3s</span>
                    </div>
                </div>
            </div>
            
            <div class="cct-timing-test">
                <button type="button" class="button button-primary cct-test-timing">
                    <?php _e('🎬 Testar Timing', 'cct'); ?>
                </button>
                
                <div class="cct-test-element">
                    <div class="cct-test-ball"></div>
                </div>
            </div>
        </div>
        
        <style>
        .cct-timing-configurator {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .cct-timing-presets {
            padding: 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-timing-presets h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-preset-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        
        .cct-timing-preset {
            font-size: 11px;
            padding: 6px 8px;
            text-align: center;
        }
        
        .cct-timing-preset.active {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        .cct-timing-controls {
            padding: 15px;
            background: white;
        }
        
        .cct-timing-group {
            margin-bottom: 15px;
        }
        
        .cct-timing-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }
        
        .cct-range-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .cct-duration-range,
        .cct-delay-range {
            flex: 1;
        }
        
        .cct-duration-input,
        .cct-delay-input {
            width: 60px;
            padding: 4px 6px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 11px;
            text-align: center;
        }
        
        .cct-unit {
            font-size: 11px;
            color: #666;
            min-width: 15px;
        }
        
        .cct-easing-select {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .cct-timing-visualizer {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-timing-visualizer h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-curve-container {
            position: relative;
            margin-bottom: 15px;
        }
        
        .cct-curve-svg {
            width: 100%;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }
        
        .cct-curve-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            font-size: 10px;
            color: #666;
        }
        
        .cct-timing-info {
            display: flex;
            gap: 20px;
        }
        
        .cct-info-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .cct-info-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .cct-info-value {
            font-size: 12px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .cct-timing-test {
            padding: 15px;
            background: white;
            text-align: center;
        }
        
        .cct-test-timing {
            margin-bottom: 15px;
        }
        
        .cct-test-element {
            height: 60px;
            background: linear-gradient(90deg, #f1f1f1 0%, #e1e1e1 100%);
            border-radius: 4px;
            position: relative;
            overflow: hidden;
        }
        
        .cct-test-ball {
            width: 20px;
            height: 20px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            transition: all 0.3s ease-in-out;
        }
        
        .cct-test-ball.animate {
            left: calc(100% - 30px);
        }
        </style>
        <?php
    }
}

/**
 * Controle Seletor de Efeitos de Hover
 */
class CCT_Hover_Effects_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_hover_effects';
    
    /**
     * Efeitos de hover disponíveis
     * 
     * @var array
     */
    public $hover_effects = array();
    
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
        
        <div class="cct-hover-effects">
            <div class="cct-effects-grid">
                <?php foreach ($this->hover_effects as $effect_id => $effect): ?>
                    <div class="cct-effect-item" data-effect="<?php echo esc_attr($effect_id); ?>">
                        <div class="cct-effect-preview">
                            <div class="cct-preview-card cct-hover-<?php echo esc_attr($effect_id); ?>">
                                <div class="cct-card-content">
                                    <span class="cct-card-icon">🎯</span>
                                    <span class="cct-card-text"><?php echo esc_html($effect['name']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="cct-effect-info">
                            <h5><?php echo esc_html($effect['name']); ?></h5>
                            <p><?php echo esc_html($effect['description']); ?></p>
                            
                            <div class="cct-effect-toggle">
                                <label class="cct-toggle-switch">
                                    <input type="checkbox" class="cct-effect-enabled" data-effect="<?php echo esc_attr($effect_id); ?>">
                                    <span class="cct-toggle-slider"></span>
                                </label>
                                <span class="cct-toggle-label"><?php _e('Ativar', 'cct'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cct-global-hover-settings">
                <h4><?php _e('Configurações Globais', 'cct'); ?></h4>
                
                <div class="cct-setting-group">
                    <label><?php _e('Duração dos Efeitos:', 'cct'); ?></label>
                    <div class="cct-duration-control">
                        <input type="range" class="cct-hover-duration" min="0.1" max="1" step="0.1" value="0.3">
                        <span class="cct-duration-display">0.3s</span>
                    </div>
                </div>
                
                <div class="cct-setting-group">
                    <label><?php _e('Intensidade:', 'cct'); ?></label>
                    <div class="cct-intensity-control">
                        <input type="range" class="cct-hover-intensity" min="0.5" max="2" step="0.1" value="1">
                        <span class="cct-intensity-display">1x</span>
                    </div>
                </div>
                
                <div class="cct-setting-group">
                    <label class="cct-checkbox-label">
                        <input type="checkbox" class="cct-hover-mobile" checked>
                        <?php _e('Ativar em dispositivos móveis', 'cct'); ?>
                    </label>
                </div>
            </div>
        </div>
        
        <style>
        .cct-hover-effects {
            margin-top: 10px;
        }
        
        .cct-effects-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .cct-effect-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        
        .cct-effect-preview {
            padding: 20px;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .cct-preview-card {
            background: white;
            border-radius: 6px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            transform-origin: center;
        }
        
        .cct-card-content {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 500;
            color: #333;
        }
        
        .cct-card-icon {
            font-size: 16px;
        }
        
        /* Efeitos de hover */
        .cct-hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        
        .cct-hover-glow:hover {
            box-shadow: 0 0 20px rgba(0,123,255,0.4);
            transform: scale(1.02);
        }
        
        .cct-hover-tilt:hover {
            transform: rotate(2deg) scale(1.02);
        }
        
        .cct-hover-zoom:hover {
            transform: scale(1.1);
        }
        
        .cct-hover-slide_up:hover {
            transform: translateY(-8px);
        }
        
        .cct-effect-info {
            padding: 15px;
        }
        
        .cct-effect-info h5 {
            margin: 0 0 5px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-effect-info p {
            margin: 0 0 10px 0;
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }
        
        .cct-effect-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .cct-toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }
        
        .cct-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .cct-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
        }
        
        .cct-toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .cct-toggle-slider {
            background-color: #0073aa;
        }
        
        input:checked + .cct-toggle-slider:before {
            transform: translateX(20px);
        }
        
        .cct-toggle-label {
            font-size: 11px;
            color: #666;
        }
        
        .cct-global-hover-settings {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .cct-global-hover-settings h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-setting-group {
            margin-bottom: 15px;
        }
        
        .cct-setting-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }
        
        .cct-duration-control,
        .cct-intensity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cct-hover-duration,
        .cct-hover-intensity {
            flex: 1;
        }
        
        .cct-duration-display,
        .cct-intensity-display {
            min-width: 40px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .cct-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 12px;
        }
        </style>
        <?php
    }
}

/**
 * Controle Gerenciador de Micro-interações
 */
class CCT_Micro_Interactions_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'cct_micro_interactions';
    
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
        
        <div class="cct-micro-interactions">
            <div class="cct-interaction-categories">
                <div class="cct-category-tabs">
                    <button type="button" class="cct-tab-btn active" data-category="buttons">
                        <?php _e('Botões', 'cct'); ?>
                    </button>
                    <button type="button" class="cct-tab-btn" data-category="forms">
                        <?php _e('Formulários', 'cct'); ?>
                    </button>
                    <button type="button" class="cct-tab-btn" data-category="navigation">
                        <?php _e('Navegação', 'cct'); ?>
                    </button>
                    <button type="button" class="cct-tab-btn" data-category="content">
                        <?php _e('Conteúdo', 'cct'); ?>
                    </button>
                </div>
                
                <div class="cct-category-content">
                    <!-- Botões -->
                    <div class="cct-category-panel active" data-category="buttons">
                        <div class="cct-interaction-item">
                            <div class="cct-interaction-demo">
                                <button class="cct-demo-button cct-button-ripple">
                                    <?php _e('Efeito Ripple', 'cct'); ?>
                                </button>
                            </div>
                            <div class="cct-interaction-controls">
                                <label class="cct-control-label">
                                    <input type="checkbox" class="cct-interaction-toggle" data-interaction="button-ripple">
                                    <?php _e('Ativar Efeito Ripple', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                        
                        <div class="cct-interaction-item">
                            <div class="cct-interaction-demo">
                                <button class="cct-demo-button cct-button-pulse">
                                    <?php _e('Pulsação', 'cct'); ?>
                                </button>
                            </div>
                            <div class="cct-interaction-controls">
                                <label class="cct-control-label">
                                    <input type="checkbox" class="cct-interaction-toggle" data-interaction="button-pulse">
                                    <?php _e('Ativar Pulsação', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulários -->
                    <div class="cct-category-panel" data-category="forms">
                        <div class="cct-interaction-item">
                            <div class="cct-interaction-demo">
                                <input type="text" class="cct-demo-input cct-input-focus" placeholder="<?php _e('Foco animado', 'cct'); ?>">
                            </div>
                            <div class="cct-interaction-controls">
                                <label class="cct-control-label">
                                    <input type="checkbox" class="cct-interaction-toggle" data-interaction="input-focus">
                                    <?php _e('Ativar Foco Animado', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                        
                        <div class="cct-interaction-item">
                            <div class="cct-interaction-demo">
                                <div class="cct-demo-checkbox">
                                    <input type="checkbox" id="demo-check" class="cct-checkbox-animated">
                                    <label for="demo-check"><?php _e('Checkbox Animado', 'cct'); ?></label>
                                </div>
                            </div>
                            <div class="cct-interaction-controls">
                                <label class="cct-control-label">
                                    <input type="checkbox" class="cct-interaction-toggle" data-interaction="checkbox-animation">
                                    <?php _e('Ativar Animação de Checkbox', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navegação -->
                    <div class="cct-category-panel" data-category="navigation">
                        <div class="cct-interaction-item">
                            <div class="cct-interaction-demo">
                                <nav class="cct-demo-nav">
                                    <a href="#" class="cct-nav-link cct-link-underline"><?php _e('Link 1', 'cct'); ?></a>
                                    <a href="#" class="cct-nav-link cct-link-underline"><?php _e('Link 2', 'cct'); ?></a>
                                    <a href="#" class="cct-nav-link cct-link-underline"><?php _e('Link 3', 'cct'); ?></a>
                                </nav>
                            </div>
                            <div class="cct-interaction-controls">
                                <label class="cct-control-label">
                                    <input type="checkbox" class="cct-interaction-toggle" data-interaction="link-underline">
                                    <?php _e('Ativar Sublinhado Animado', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Conteúdo -->
                    <div class="cct-category-panel" data-category="content">
                        <div class="cct-interaction-item">
                            <div class="cct-interaction-demo">
                                <div class="cct-demo-card cct-card-reveal">
                                    <div class="cct-card-image">🖼️</div>
                                    <div class="cct-card-overlay">
                                        <span><?php _e('Revelação no Hover', 'cct'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="cct-interaction-controls">
                                <label class="cct-control-label">
                                    <input type="checkbox" class="cct-interaction-toggle" data-interaction="card-reveal">
                                    <?php _e('Ativar Revelação de Card', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="cct-global-micro-settings">
                <h4><?php _e('Configurações Globais', 'cct'); ?></h4>
                
                <div class="cct-micro-setting">
                    <label><?php _e('Sensibilidade:', 'cct'); ?></label>
                    <input type="range" class="cct-micro-sensitivity" min="0.5" max="2" step="0.1" value="1">
                    <span class="cct-sensitivity-value">1x</span>
                </div>
                
                <div class="cct-micro-setting">
                    <label><?php _e('Velocidade:', 'cct'); ?></label>
                    <input type="range" class="cct-micro-speed" min="0.5" max="2" step="0.1" value="1">
                    <span class="cct-speed-value">1x</span>
                </div>
                
                <div class="cct-micro-setting">
                    <label class="cct-checkbox-label">
                        <input type="checkbox" class="cct-micro-accessibility">
                        <?php _e('Modo Acessibilidade (reduz movimento)', 'cct'); ?>
                    </label>
                </div>
            </div>
        </div>
        
        <style>
        .cct-micro-interactions {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .cct-category-tabs {
            display: flex;
            background: #f1f1f1;
            border-bottom: 1px solid #ddd;
        }
        
        .cct-tab-btn {
            flex: 1;
            padding: 10px 8px;
            border: none;
            background: transparent;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cct-tab-btn:hover {
            background: #e1e1e1;
        }
        
        .cct-tab-btn.active {
            background: white;
            border-bottom: 2px solid #0073aa;
            font-weight: 600;
        }
        
        .cct-category-content {
            background: white;
        }
        
        .cct-category-panel {
            display: none;
            padding: 15px;
        }
        
        .cct-category-panel.active {
            display: block;
        }
        
        .cct-interaction-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 1px solid #e1e1e1;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        
        .cct-interaction-demo {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 60px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        
        .cct-interaction-controls {
            min-width: 150px;
        }
        
        .cct-control-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            cursor: pointer;
        }
        
        /* Demos de interações */
        .cct-demo-button {
            padding: 8px 16px;
            border: 1px solid #0073aa;
            background: #0073aa;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .cct-button-ripple:active::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            transform: translate(-50%, -50%);
            animation: ripple 0.6s ease-out;
        }
        
        .cct-button-pulse:hover {
            animation: pulse 1s ease-in-out infinite;
        }
        
        .cct-demo-input {
            padding: 8px 12px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .cct-input-focus:focus {
            border-color: #0073aa;
            box-shadow: 0 0 0 3px rgba(0,115,170,0.1);
            transform: scale(1.02);
        }
        
        .cct-demo-nav {
            display: flex;
            gap: 15px;
        }
        
        .cct-nav-link {
            text-decoration: none;
            color: #333;
            font-size: 12px;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .cct-link-underline::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #0073aa;
            transition: width 0.3s ease;
        }
        
        .cct-link-underline:hover::after {
            width: 100%;
        }
        
        .cct-demo-card {
            width: 80px;
            height: 60px;
            background: #f1f1f1;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .cct-card-image {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 20px;
        }
        
        .cct-card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,115,170,0.9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .cct-card-reveal:hover .cct-card-overlay {
            opacity: 1;
        }
        
        .cct-global-micro-settings {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
        }
        
        .cct-global-micro-settings h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .cct-micro-setting {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .cct-micro-setting label {
            min-width: 80px;
            font-size: 12px;
            color: #333;
        }
        
        .cct-micro-sensitivity,
        .cct-micro-speed {
            flex: 1;
        }
        
        .cct-sensitivity-value,
        .cct-speed-value {
            min-width: 30px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
        }
        
        @keyframes ripple {
            to {
                width: 100px;
                height: 100px;
                opacity: 0;
            }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        </style>
        <?php
    }
}