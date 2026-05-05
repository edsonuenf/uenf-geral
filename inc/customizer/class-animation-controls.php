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
 * @package UENF_Theme
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
class UENF_Animation_Preview_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_animation_preview';
    
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
        
        <div class="uenf-animation-preview">
            <div class="uenf-preview-stage">
                <div class="uenf-preview-element" id="uenf-preview-element">
                    <div class="uenf-preview-content">
                        <span class="uenf-preview-icon">✨</span>
                        <span class="uenf-preview-text"><?php _e('Elemento de Teste', 'cct'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="uenf-animation-selector">
                <h4><?php _e('Escolha uma Animação', 'cct'); ?></h4>
                
                <div class="uenf-animation-grid">
                    <?php foreach ($this->animation_presets as $preset_id => $preset): ?>
                        <div class="uenf-animation-option" data-animation="<?php echo esc_attr($preset_id); ?>">
                            <div class="uenf-animation-thumbnail">
                                <div class="uenf-thumbnail-element uenf-<?php echo esc_attr($preset_id); ?>">
                                    <span class="uenf-thumbnail-icon">⚡</span>
                                </div>
                            </div>
                            
                            <div class="uenf-animation-info">
                                <h5><?php echo esc_html($preset['name']); ?></h5>
                                <p><?php echo esc_html($preset['description']); ?></p>
                                
                                <div class="uenf-animation-meta">
                                    <span class="uenf-duration">
                                        <strong><?php _e('Duração:', 'cct'); ?></strong> 
                                        <?php echo esc_html($preset['duration']); ?>
                                    </span>
                                    <span class="uenf-easing">
                                        <strong><?php _e('Easing:', 'cct'); ?></strong> 
                                        <?php echo esc_html($preset['easing']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <button type="button" class="button uenf-preview-btn" data-animation="<?php echo esc_attr($preset_id); ?>">
                                <?php _e('Preview', 'cct'); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="uenf-animation-controls">
                <div class="uenf-control-group">
                    <label><?php _e('Duração (s):', 'cct'); ?></label>
                    <input type="range" class="uenf-duration-slider" min="0.1" max="3" step="0.1" value="0.3">
                    <span class="uenf-duration-value">0.3s</span>
                </div>
                
                <div class="uenf-control-group">
                    <label><?php _e('Delay (s):', 'cct'); ?></label>
                    <input type="range" class="uenf-delay-slider" min="0" max="2" step="0.1" value="0">
                    <span class="uenf-delay-value">0s</span>
                </div>
                
                <div class="uenf-control-group">
                    <label><?php _e('Easing:', 'cct'); ?></label>
                    <select class="uenf-easing-selector">
                        <option value="linear"><?php _e('Linear', 'cct'); ?></option>
                        <option value="ease"><?php _e('Ease', 'cct'); ?></option>
                        <option value="ease-in"><?php _e('Ease In', 'cct'); ?></option>
                        <option value="ease-out"><?php _e('Ease Out', 'cct'); ?></option>
                        <option value="ease-in-out" selected><?php _e('Ease In Out', 'cct'); ?></option>
                        <option value="cubic-bezier(0.68, -0.55, 0.265, 1.55)"><?php _e('Bounce', 'cct'); ?></option>
                    </select>
                </div>
                
                <div class="uenf-control-actions">
                    <button type="button" class="button button-primary uenf-play-animation">
                        <?php _e('▶ Reproduzir', 'cct'); ?>
                    </button>
                    <button type="button" class="button uenf-reset-animation">
                        <?php _e('🔄 Reset', 'cct'); ?>
                    </button>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-animation-preview {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #f9f9f9;
        }
        
        .uenf-preview-stage {
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 120px;
            position: relative;
            overflow: hidden;
        }
        
        .uenf-preview-element {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transform-origin: center;
            transition: all 0.3s ease;
        }
        
        .uenf-preview-content {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-preview-icon {
            font-size: 20px;
        }
        
        .uenf-animation-selector {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-animation-selector h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-animation-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .uenf-animation-option {
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
        
        .uenf-animation-option:hover {
            border-color: #0073aa;
            background: #f0f8ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,115,170,0.1);
        }
        
        .uenf-animation-option.selected {
            border-color: #0073aa;
            background: #e3f2fd;
        }
        
        .uenf-animation-thumbnail {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .uenf-thumbnail-element {
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
        
        .uenf-animation-option:hover .uenf-thumbnail-element {
            animation: pulse 1s ease-in-out;
        }
        
        .uenf-animation-info {
            flex: 1;
        }
        
        .uenf-animation-info h5 {
            margin: 0 0 5px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-animation-info p {
            margin: 0 0 8px 0;
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }
        
        .uenf-animation-meta {
            display: flex;
            gap: 15px;
        }
        
        .uenf-animation-meta span {
            font-size: 10px;
            color: #888;
        }
        
        .uenf-preview-btn {
            font-size: 11px;
            padding: 6px 12px;
            white-space: nowrap;
        }
        
        .uenf-animation-controls {
            padding: 20px;
            background: white;
            border-top: 1px solid #ddd;
        }
        
        .uenf-control-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .uenf-control-group label {
            min-width: 80px;
            font-size: 12px;
            font-weight: 500;
            color: #333;
        }
        
        .uenf-duration-slider,
        .uenf-delay-slider {
            flex: 1;
            margin: 0;
        }
        
        .uenf-duration-value,
        .uenf-delay-value {
            min-width: 40px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-easing-selector {
            flex: 1;
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .uenf-control-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .uenf-control-actions .button {
            font-size: 12px;
            padding: 8px 16px;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Animações de preview */
        .uenf-fade { animation: fadeIn 0.3s ease-in-out; }
        .uenf-slide { animation: slideInUp 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .uenf-scale { animation: scaleIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        .uenf-rotate { animation: rotateIn 0.6s ease-in-out; }
        .uenf-bounce { animation: bounceIn 0.8s cubic-bezier(0.215, 0.610, 0.355, 1.000); }
        .uenf-flip { animation: flipInX 0.7s ease-in-out; }
        
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
class UENF_Timing_Configurator_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_timing_configurator';
    
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
        
        <div class="uenf-timing-configurator">
            <div class="uenf-timing-presets">
                <h4><?php _e('Presets de Timing', 'cct'); ?></h4>
                
                <div class="uenf-preset-buttons">
                    <button type="button" class="button uenf-timing-preset" data-preset="fast">
                        <?php _e('Rápido', 'cct'); ?>
                    </button>
                    <button type="button" class="button uenf-timing-preset" data-preset="normal">
                        <?php _e('Normal', 'cct'); ?>
                    </button>
                    <button type="button" class="button uenf-timing-preset" data-preset="slow">
                        <?php _e('Lento', 'cct'); ?>
                    </button>
                    <button type="button" class="button uenf-timing-preset" data-preset="custom">
                        <?php _e('Personalizado', 'cct'); ?>
                    </button>
                </div>
            </div>
            
            <div class="uenf-timing-controls">
                <div class="uenf-timing-group">
                    <label><?php _e('Duração:', 'cct'); ?></label>
                    <div class="uenf-range-control">
                        <input type="range" class="uenf-duration-range" min="0.1" max="3" step="0.1" value="0.3">
                        <input type="number" class="uenf-duration-input" min="0.1" max="3" step="0.1" value="0.3">
                        <span class="uenf-unit">s</span>
                    </div>
                </div>
                
                <div class="uenf-timing-group">
                    <label><?php _e('Delay:', 'cct'); ?></label>
                    <div class="uenf-range-control">
                        <input type="range" class="uenf-delay-range" min="0" max="2" step="0.1" value="0">
                        <input type="number" class="uenf-delay-input" min="0" max="2" step="0.1" value="0">
                        <span class="uenf-unit">s</span>
                    </div>
                </div>
                
                <div class="uenf-timing-group">
                    <label><?php _e('Easing:', 'cct'); ?></label>
                    <select class="uenf-easing-select">
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
            
            <div class="uenf-timing-visualizer">
                <h4><?php _e('Visualizador de Curva', 'cct'); ?></h4>
                
                <div class="uenf-curve-container">
                    <svg class="uenf-curve-svg" viewBox="0 0 200 100">
                        <defs>
                            <linearGradient id="curveGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        
                        <!-- Grid -->
                        <g class="uenf-grid">
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
                        <path class="uenf-timing-curve" d="M0,100 Q50,50 200,0" stroke="url(#curveGradient)" stroke-width="3" fill="none"/>
                        
                        <!-- Points -->
                        <circle cx="0" cy="100" r="4" fill="#667eea"/>
                        <circle cx="200" cy="0" r="4" fill="#764ba2"/>
                    </svg>
                    
                    <div class="uenf-curve-labels">
                        <span class="uenf-start-label"><?php _e('Início', 'cct'); ?></span>
                        <span class="uenf-end-label"><?php _e('Fim', 'cct'); ?></span>
                    </div>
                </div>
                
                <div class="uenf-timing-info">
                    <div class="uenf-info-item">
                        <span class="uenf-info-label"><?php _e('Função:', 'cct'); ?></span>
                        <span class="uenf-info-value" id="uenf-easing-function">ease-in-out</span>
                    </div>
                    <div class="uenf-info-item">
                        <span class="uenf-info-label"><?php _e('Duração Total:', 'cct'); ?></span>
                        <span class="uenf-info-value" id="uenf-total-duration">0.3s</span>
                    </div>
                </div>
            </div>
            
            <div class="uenf-timing-test">
                <button type="button" class="button button-primary uenf-test-timing">
                    <?php _e('🎬 Testar Timing', 'cct'); ?>
                </button>
                
                <div class="uenf-test-element">
                    <div class="uenf-test-ball"></div>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-timing-configurator {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .uenf-timing-presets {
            padding: 15px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-timing-presets h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-preset-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        
        .uenf-timing-preset {
            font-size: 11px;
            padding: 6px 8px;
            text-align: center;
        }
        
        .uenf-timing-preset.active {
            background: #0073aa;
            color: white;
            border-color: #005a87;
        }
        
        .uenf-timing-controls {
            padding: 15px;
            background: white;
        }
        
        .uenf-timing-group {
            margin-bottom: 15px;
        }
        
        .uenf-timing-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }
        
        .uenf-range-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .uenf-duration-range,
        .uenf-delay-range {
            flex: 1;
        }
        
        .uenf-duration-input,
        .uenf-delay-input {
            width: 60px;
            padding: 4px 6px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 11px;
            text-align: center;
        }
        
        .uenf-unit {
            font-size: 11px;
            color: #666;
            min-width: 15px;
        }
        
        .uenf-easing-select {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .uenf-timing-visualizer {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-timing-visualizer h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-curve-container {
            position: relative;
            margin-bottom: 15px;
        }
        
        .uenf-curve-svg {
            width: 100%;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }
        
        .uenf-curve-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            font-size: 10px;
            color: #666;
        }
        
        .uenf-timing-info {
            display: flex;
            gap: 20px;
        }
        
        .uenf-info-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .uenf-info-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .uenf-info-value {
            font-size: 12px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-timing-test {
            padding: 15px;
            background: white;
            text-align: center;
        }
        
        .uenf-test-timing {
            margin-bottom: 15px;
        }
        
        .uenf-test-element {
            height: 60px;
            background: linear-gradient(90deg, #f1f1f1 0%, #e1e1e1 100%);
            border-radius: 4px;
            position: relative;
            overflow: hidden;
        }
        
        .uenf-test-ball {
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
        
        .uenf-test-ball.animate {
            left: calc(100% - 30px);
        }
        </style>
        <?php
    }
}

/**
 * Controle Seletor de Efeitos de Hover
 */
class UENF_Hover_Effects_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_hover_effects';
    
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
        
        <div class="uenf-hover-effects">
            <div class="uenf-effects-grid">
                <?php foreach ($this->hover_effects as $effect_id => $effect): ?>
                    <div class="uenf-effect-item" data-effect="<?php echo esc_attr($effect_id); ?>">
                        <div class="uenf-effect-preview">
                            <div class="uenf-preview-card uenf-hover-<?php echo esc_attr($effect_id); ?>">
                                <div class="uenf-card-content">
                                    <span class="uenf-card-icon">🎯</span>
                                    <span class="uenf-card-text"><?php echo esc_html($effect['name']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="uenf-effect-info">
                            <h5><?php echo esc_html($effect['name']); ?></h5>
                            <p><?php echo esc_html($effect['description']); ?></p>
                            
                            <div class="uenf-effect-toggle">
                                <label class="uenf-toggle-switch">
                                    <input type="checkbox" class="uenf-effect-enabled" data-effect="<?php echo esc_attr($effect_id); ?>">
                                    <span class="uenf-toggle-slider"></span>
                                </label>
                                <span class="uenf-toggle-label"><?php _e('Ativar', 'cct'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="uenf-global-hover-settings">
                <h4><?php _e('Configurações Globais', 'cct'); ?></h4>
                
                <div class="uenf-setting-group">
                    <label><?php _e('Duração dos Efeitos:', 'cct'); ?></label>
                    <div class="uenf-duration-control">
                        <input type="range" class="uenf-hover-duration" min="0.1" max="1" step="0.1" value="0.3">
                        <span class="uenf-duration-display">0.3s</span>
                    </div>
                </div>
                
                <div class="uenf-setting-group">
                    <label><?php _e('Intensidade:', 'cct'); ?></label>
                    <div class="uenf-intensity-control">
                        <input type="range" class="uenf-hover-intensity" min="0.5" max="2" step="0.1" value="1">
                        <span class="uenf-intensity-display">1x</span>
                    </div>
                </div>
                
                <div class="uenf-setting-group">
                    <label class="uenf-checkbox-label">
                        <input type="checkbox" class="uenf-hover-mobile" checked>
                        <?php _e('Ativar em dispositivos móveis', 'cct'); ?>
                    </label>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-hover-effects {
            margin-top: 10px;
        }
        
        .uenf-effects-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .uenf-effect-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        
        .uenf-effect-preview {
            padding: 20px;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .uenf-preview-card {
            background: white;
            border-radius: 6px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            transform-origin: center;
        }
        
        .uenf-card-content {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 500;
            color: #333;
        }
        
        .uenf-card-icon {
            font-size: 16px;
        }
        
        /* Efeitos de hover */
        .uenf-hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        
        .uenf-hover-glow:hover {
            box-shadow: 0 0 20px rgba(0,123,255,0.4);
            transform: scale(1.02);
        }
        
        .uenf-hover-tilt:hover {
            transform: rotate(2deg) scale(1.02);
        }
        
        .uenf-hover-zoom:hover {
            transform: scale(1.1);
        }
        
        .uenf-hover-slide_up:hover {
            transform: translateY(-8px);
        }
        
        .uenf-effect-info {
            padding: 15px;
        }
        
        .uenf-effect-info h5 {
            margin: 0 0 5px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-effect-info p {
            margin: 0 0 10px 0;
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }
        
        .uenf-effect-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .uenf-toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }
        
        .uenf-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .uenf-toggle-slider {
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
        
        .uenf-toggle-slider:before {
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
        
        input:checked + .uenf-toggle-slider {
            background-color: #0073aa;
        }
        
        input:checked + .uenf-toggle-slider:before {
            transform: translateX(20px);
        }
        
        .uenf-toggle-label {
            font-size: 11px;
            color: #666;
        }
        
        .uenf-global-hover-settings {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .uenf-global-hover-settings h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-setting-group {
            margin-bottom: 15px;
        }
        
        .uenf-setting-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }
        
        .uenf-duration-control,
        .uenf-intensity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .uenf-hover-duration,
        .uenf-hover-intensity {
            flex: 1;
        }
        
        .uenf-duration-display,
        .uenf-intensity-display {
            min-width: 40px;
            font-size: 11px;
            font-weight: 600;
            color: #0073aa;
        }
        
        .uenf-checkbox-label {
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
class UENF_Micro_Interactions_Control extends WP_Customize_Control {
    
    /**
     * Tipo do controle
     * 
     * @var string
     */
    public $type = 'uenf_micro_interactions';
    
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
        
        <div class="uenf-micro-interactions">
            <div class="uenf-interaction-categories">
                <div class="uenf-category-tabs">
                    <button type="button" class="uenf-tab-btn active" data-category="buttons">
                        <?php _e('Botões', 'cct'); ?>
                    </button>
                    <button type="button" class="uenf-tab-btn" data-category="forms">
                        <?php _e('Formulários', 'cct'); ?>
                    </button>
                    <button type="button" class="uenf-tab-btn" data-category="navigation">
                        <?php _e('Navegação', 'cct'); ?>
                    </button>
                    <button type="button" class="uenf-tab-btn" data-category="content">
                        <?php _e('Conteúdo', 'cct'); ?>
                    </button>
                </div>
                
                <div class="uenf-category-content">
                    <!-- Botões -->
                    <div class="uenf-category-panel active" data-category="buttons">
                        <div class="uenf-interaction-item">
                            <div class="uenf-interaction-demo">
                                <button class="uenf-demo-button uenf-button-ripple">
                                    <?php _e('Efeito Ripple', 'cct'); ?>
                                </button>
                            </div>
                            <div class="uenf-interaction-controls">
                                <label class="uenf-control-label">
                                    <input type="checkbox" class="uenf-interaction-toggle" data-interaction="button-ripple">
                                    <?php _e('Ativar Efeito Ripple', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                        
                        <div class="uenf-interaction-item">
                            <div class="uenf-interaction-demo">
                                <button class="uenf-demo-button uenf-button-pulse">
                                    <?php _e('Pulsação', 'cct'); ?>
                                </button>
                            </div>
                            <div class="uenf-interaction-controls">
                                <label class="uenf-control-label">
                                    <input type="checkbox" class="uenf-interaction-toggle" data-interaction="button-pulse">
                                    <?php _e('Ativar Pulsação', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulários -->
                    <div class="uenf-category-panel" data-category="forms">
                        <div class="uenf-interaction-item">
                            <div class="uenf-interaction-demo">
                                <input type="text" class="uenf-demo-input uenf-input-focus" placeholder="<?php _e('Foco animado', 'cct'); ?>">
                            </div>
                            <div class="uenf-interaction-controls">
                                <label class="uenf-control-label">
                                    <input type="checkbox" class="uenf-interaction-toggle" data-interaction="input-focus">
                                    <?php _e('Ativar Foco Animado', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                        
                        <div class="uenf-interaction-item">
                            <div class="uenf-interaction-demo">
                                <div class="uenf-demo-checkbox">
                                    <input type="checkbox" id="demo-check" class="uenf-checkbox-animated">
                                    <label for="demo-check"><?php _e('Checkbox Animado', 'cct'); ?></label>
                                </div>
                            </div>
                            <div class="uenf-interaction-controls">
                                <label class="uenf-control-label">
                                    <input type="checkbox" class="uenf-interaction-toggle" data-interaction="checkbox-animation">
                                    <?php _e('Ativar Animação de Checkbox', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navegação -->
                    <div class="uenf-category-panel" data-category="navigation">
                        <div class="uenf-interaction-item">
                            <div class="uenf-interaction-demo">
                                <nav class="uenf-demo-nav">
                                    <a href="#" class="uenf-nav-link uenf-link-underline"><?php _e('Link 1', 'cct'); ?></a>
                                    <a href="#" class="uenf-nav-link uenf-link-underline"><?php _e('Link 2', 'cct'); ?></a>
                                    <a href="#" class="uenf-nav-link uenf-link-underline"><?php _e('Link 3', 'cct'); ?></a>
                                </nav>
                            </div>
                            <div class="uenf-interaction-controls">
                                <label class="uenf-control-label">
                                    <input type="checkbox" class="uenf-interaction-toggle" data-interaction="link-underline">
                                    <?php _e('Ativar Sublinhado Animado', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Conteúdo -->
                    <div class="uenf-category-panel" data-category="content">
                        <div class="uenf-interaction-item">
                            <div class="uenf-interaction-demo">
                                <div class="uenf-demo-card uenf-card-reveal">
                                    <div class="uenf-card-image">🖼️</div>
                                    <div class="uenf-card-overlay">
                                        <span><?php _e('Revelação no Hover', 'cct'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="uenf-interaction-controls">
                                <label class="uenf-control-label">
                                    <input type="checkbox" class="uenf-interaction-toggle" data-interaction="card-reveal">
                                    <?php _e('Ativar Revelação de Card', 'cct'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="uenf-global-micro-settings">
                <h4><?php _e('Configurações Globais', 'cct'); ?></h4>
                
                <div class="uenf-micro-setting">
                    <label><?php _e('Sensibilidade:', 'cct'); ?></label>
                    <input type="range" class="uenf-micro-sensitivity" min="0.5" max="2" step="0.1" value="1">
                    <span class="uenf-sensitivity-value">1x</span>
                </div>
                
                <div class="uenf-micro-setting">
                    <label><?php _e('Velocidade:', 'cct'); ?></label>
                    <input type="range" class="uenf-micro-speed" min="0.5" max="2" step="0.1" value="1">
                    <span class="uenf-speed-value">1x</span>
                </div>
                
                <div class="uenf-micro-setting">
                    <label class="uenf-checkbox-label">
                        <input type="checkbox" class="uenf-micro-accessibility">
                        <?php _e('Modo Acessibilidade (reduz movimento)', 'cct'); ?>
                    </label>
                </div>
            </div>
        </div>
        
        <style>
        .uenf-micro-interactions {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .uenf-category-tabs {
            display: flex;
            background: #f1f1f1;
            border-bottom: 1px solid #ddd;
        }
        
        .uenf-tab-btn {
            flex: 1;
            padding: 10px 8px;
            border: none;
            background: transparent;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .uenf-tab-btn:hover {
            background: #e1e1e1;
        }
        
        .uenf-tab-btn.active {
            background: white;
            border-bottom: 2px solid #0073aa;
            font-weight: 600;
        }
        
        .uenf-category-content {
            background: white;
        }
        
        .uenf-category-panel {
            display: none;
            padding: 15px;
        }
        
        .uenf-category-panel.active {
            display: block;
        }
        
        .uenf-interaction-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 1px solid #e1e1e1;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        
        .uenf-interaction-demo {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 60px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        
        .uenf-interaction-controls {
            min-width: 150px;
        }
        
        .uenf-control-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            cursor: pointer;
        }
        
        /* Demos de interações */
        .uenf-demo-button {
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
        
        .uenf-button-ripple:active::after {
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
        
        .uenf-button-pulse:hover {
            animation: pulse 1s ease-in-out infinite;
        }
        
        .uenf-demo-input {
            padding: 8px 12px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .uenf-input-focus:focus {
            border-color: #0073aa;
            box-shadow: 0 0 0 3px rgba(0,115,170,0.1);
            transform: scale(1.02);
        }
        
        .uenf-demo-nav {
            display: flex;
            gap: 15px;
        }
        
        .uenf-nav-link {
            text-decoration: none;
            color: #333;
            font-size: 12px;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .uenf-link-underline::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #0073aa;
            transition: width 0.3s ease;
        }
        
        .uenf-link-underline:hover::after {
            width: 100%;
        }
        
        .uenf-demo-card {
            width: 80px;
            height: 60px;
            background: #f1f1f1;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .uenf-card-image {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 20px;
        }
        
        .uenf-card-overlay {
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
        
        .uenf-card-reveal:hover .uenf-card-overlay {
            opacity: 1;
        }
        
        .uenf-global-micro-settings {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
        }
        
        .uenf-global-micro-settings h4 {
            margin: 0 0 15px 0;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        
        .uenf-micro-setting {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .uenf-micro-setting label {
            min-width: 80px;
            font-size: 12px;
            color: #333;
        }
        
        .uenf-micro-sensitivity,
        .uenf-micro-speed {
            flex: 1;
        }
        
        .uenf-sensitivity-value,
        .uenf-speed-value {
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