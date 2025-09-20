<?php
/**
 * Sistema de Animações - Gerenciador Principal
 * 
 * Sistema completo de animações e micro-interações incluindo:
 * - Presets de animações predefinidas
 * - Configurações de timing e easing
 * - Efeitos de hover e focus
 * - Transições de página
 * - Micro-interações para UX
 * - Performance otimizada
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
 * Classe principal do Sistema de Animações
 */
class CCT_Animation_Manager {
    
    /**
     * Instância do WP_Customize_Manager
     * 
     * @var WP_Customize_Manager
     */
    private $wp_customize;
    
    /**
     * Prefixo para configurações
     * 
     * @var string
     */
    private $prefix = 'cct_animation_';
    
    /**
     * Presets de animações
     * 
     * @var array
     */
    private $animation_presets;
    
    /**
     * Configurações de timing
     * 
     * @var array
     */
    private $timing_settings;
    
    /**
     * Efeitos de micro-interações
     * 
     * @var array
     */
    private $micro_interactions;
    
    /**
     * Configurações de performance
     * 
     * @var array
     */
    private $performance_settings;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->init_animation_presets();
        $this->init_timing_settings();
        $this->init_micro_interactions();
        $this->init_performance_settings();
    }
    
    /**
     * Registra o módulo no customizer
     * 
     * @param WP_Customize_Manager $wp_customize
     */
    public function register($wp_customize) {
        $this->wp_customize = $wp_customize;
        $this->init_hooks();
        $this->register_customizer();
    }
    
    /**
     * Inicializa os hooks
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'output_custom_css'));
        add_action('wp_footer', array($this, 'output_custom_js'));
        
        // Shortcodes
        add_shortcode('cct_animate', array($this, 'animate_shortcode'));
        add_shortcode('cct_hover_effect', array($this, 'hover_effect_shortcode'));
        
        // AJAX handlers
        add_action('wp_ajax_cct_preview_animation', array($this, 'ajax_preview_animation'));
        add_action('wp_ajax_nopriv_cct_preview_animation', array($this, 'ajax_preview_animation'));
    }
    
    /**
     * Inicializa presets de animações
     */
    private function init_animation_presets() {
        $this->animation_presets = array(
            'fade' => array(
                'name' => __('Fade', 'cct'),
                'description' => __('Transição suave de opacidade', 'cct'),
                'css_class' => 'cct-fade',
                'keyframes' => array(
                    'fadeIn' => array(
                        '0%' => array('opacity' => '0'),
                        '100%' => array('opacity' => '1')
                    ),
                    'fadeOut' => array(
                        '0%' => array('opacity' => '1'),
                        '100%' => array('opacity' => '0')
                    )
                ),
                'duration' => '0.3s',
                'easing' => 'ease-in-out'
            ),
            'slide' => array(
                'name' => __('Slide', 'cct'),
                'description' => __('Deslizamento suave', 'cct'),
                'css_class' => 'cct-slide',
                'keyframes' => array(
                    'slideInLeft' => array(
                        '0%' => array('transform' => 'translateX(-100%)', 'opacity' => '0'),
                        '100%' => array('transform' => 'translateX(0)', 'opacity' => '1')
                    ),
                    'slideInRight' => array(
                        '0%' => array('transform' => 'translateX(100%)', 'opacity' => '0'),
                        '100%' => array('transform' => 'translateX(0)', 'opacity' => '1')
                    ),
                    'slideInUp' => array(
                        '0%' => array('transform' => 'translateY(100%)', 'opacity' => '0'),
                        '100%' => array('transform' => 'translateY(0)', 'opacity' => '1')
                    ),
                    'slideInDown' => array(
                        '0%' => array('transform' => 'translateY(-100%)', 'opacity' => '0'),
                        '100%' => array('transform' => 'translateY(0)', 'opacity' => '1')
                    )
                ),
                'duration' => '0.5s',
                'easing' => 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
            ),
            'scale' => array(
                'name' => __('Scale', 'cct'),
                'description' => __('Efeito de escala suave', 'cct'),
                'css_class' => 'cct-scale',
                'keyframes' => array(
                    'scaleIn' => array(
                        '0%' => array('transform' => 'scale(0)', 'opacity' => '0'),
                        '100%' => array('transform' => 'scale(1)', 'opacity' => '1')
                    ),
                    'scaleOut' => array(
                        '0%' => array('transform' => 'scale(1)', 'opacity' => '1'),
                        '100%' => array('transform' => 'scale(0)', 'opacity' => '0')
                    ),
                    'pulse' => array(
                        '0%' => array('transform' => 'scale(1)'),
                        '50%' => array('transform' => 'scale(1.05)'),
                        '100%' => array('transform' => 'scale(1)')
                    )
                ),
                'duration' => '0.4s',
                'easing' => 'cubic-bezier(0.68, -0.55, 0.265, 1.55)'
            ),
            'rotate' => array(
                'name' => __('Rotate', 'cct'),
                'description' => __('Rotação suave', 'cct'),
                'css_class' => 'cct-rotate',
                'keyframes' => array(
                    'rotateIn' => array(
                        '0%' => array('transform' => 'rotate(-180deg)', 'opacity' => '0'),
                        '100%' => array('transform' => 'rotate(0deg)', 'opacity' => '1')
                    ),
                    'rotateOut' => array(
                        '0%' => array('transform' => 'rotate(0deg)', 'opacity' => '1'),
                        '100%' => array('transform' => 'rotate(180deg)', 'opacity' => '0')
                    ),
                    'spin' => array(
                        '0%' => array('transform' => 'rotate(0deg)'),
                        '100%' => array('transform' => 'rotate(360deg)')
                    )
                ),
                'duration' => '0.6s',
                'easing' => 'ease-in-out'
            ),
            'bounce' => array(
                'name' => __('Bounce', 'cct'),
                'description' => __('Efeito de salto', 'cct'),
                'css_class' => 'cct-bounce',
                'keyframes' => array(
                    'bounceIn' => array(
                        '0%' => array('transform' => 'scale(0.3)', 'opacity' => '0'),
                        '50%' => array('transform' => 'scale(1.05)', 'opacity' => '1'),
                        '70%' => array('transform' => 'scale(0.9)'),
                        '100%' => array('transform' => 'scale(1)')
                    ),
                    'bounceOut' => array(
                        '0%' => array('transform' => 'scale(1)', 'opacity' => '1'),
                        '25%' => array('transform' => 'scale(0.95)'),
                        '50%' => array('transform' => 'scale(1.1)', 'opacity' => '1'),
                        '100%' => array('transform' => 'scale(0.3)', 'opacity' => '0')
                    ),
                    'bounce' => array(
                        '0%, 20%, 53%, 80%, 100%' => array('transform' => 'translate3d(0,0,0)'),
                        '40%, 43%' => array('transform' => 'translate3d(0, -30px, 0)'),
                        '70%' => array('transform' => 'translate3d(0, -15px, 0)'),
                        '90%' => array('transform' => 'translate3d(0, -4px, 0)')
                    )
                ),
                'duration' => '0.8s',
                'easing' => 'cubic-bezier(0.215, 0.610, 0.355, 1.000)'
            ),
            'flip' => array(
                'name' => __('Flip', 'cct'),
                'description' => __('Efeito de virada', 'cct'),
                'css_class' => 'cct-flip',
                'keyframes' => array(
                    'flipInX' => array(
                        '0%' => array('transform' => 'perspective(400px) rotateX(90deg)', 'opacity' => '0'),
                        '40%' => array('transform' => 'perspective(400px) rotateX(-20deg)'),
                        '60%' => array('transform' => 'perspective(400px) rotateX(10deg)', 'opacity' => '1'),
                        '80%' => array('transform' => 'perspective(400px) rotateX(-5deg)'),
                        '100%' => array('transform' => 'perspective(400px) rotateX(0deg)', 'opacity' => '1')
                    ),
                    'flipInY' => array(
                        '0%' => array('transform' => 'perspective(400px) rotateY(90deg)', 'opacity' => '0'),
                        '40%' => array('transform' => 'perspective(400px) rotateY(-20deg)'),
                        '60%' => array('transform' => 'perspective(400px) rotateY(10deg)', 'opacity' => '1'),
                        '80%' => array('transform' => 'perspective(400px) rotateY(-5deg)'),
                        '100%' => array('transform' => 'perspective(400px) rotateY(0deg)', 'opacity' => '1')
                    )
                ),
                'duration' => '0.7s',
                'easing' => 'ease-in-out'
            )
        );
    }
    
    /**
     * Inicializa configurações de timing
     */
    private function init_timing_settings() {
        $this->timing_settings = array(
            'duration' => array(
                'min' => 0.1,
                'max' => 3.0,
                'step' => 0.1,
                'default' => 0.3,
                'unit' => 's'
            ),
            'delay' => array(
                'min' => 0,
                'max' => 2.0,
                'step' => 0.1,
                'default' => 0,
                'unit' => 's'
            ),
            'easing' => array(
                'linear' => array(
                    'name' => __('Linear', 'cct'),
                    'value' => 'linear',
                    'description' => __('Velocidade constante', 'cct')
                ),
                'ease' => array(
                    'name' => __('Ease', 'cct'),
                    'value' => 'ease',
                    'description' => __('Padrão suave', 'cct')
                ),
                'ease-in' => array(
                    'name' => __('Ease In', 'cct'),
                    'value' => 'ease-in',
                    'description' => __('Início lento', 'cct')
                ),
                'ease-out' => array(
                    'name' => __('Ease Out', 'cct'),
                    'value' => 'ease-out',
                    'description' => __('Final lento', 'cct')
                ),
                'ease-in-out' => array(
                    'name' => __('Ease In Out', 'cct'),
                    'value' => 'ease-in-out',
                    'description' => __('Início e final lentos', 'cct')
                ),
                'bounce' => array(
                    'name' => __('Bounce', 'cct'),
                    'value' => 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                    'description' => __('Efeito elástico', 'cct')
                ),
                'smooth' => array(
                    'name' => __('Smooth', 'cct'),
                    'value' => 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
                    'description' => __('Muito suave', 'cct')
                ),
                'sharp' => array(
                    'name' => __('Sharp', 'cct'),
                    'value' => 'cubic-bezier(0.4, 0.0, 0.2, 1)',
                    'description' => __('Transição rápida', 'cct')
                )
            )
        );
    }
    
    /**
     * Inicializa micro-interações
     */
    private function init_micro_interactions() {
        $this->micro_interactions = array(
            'hover_effects' => array(
                'lift' => array(
                    'name' => __('Lift', 'cct'),
                    'description' => __('Elevação suave no hover', 'cct'),
                    'css' => array(
                        'transform' => 'translateY(-2px)',
                        'box-shadow' => '0 4px 8px rgba(0,0,0,0.12)'
                    )
                ),
                'glow' => array(
                    'name' => __('Glow', 'cct'),
                    'description' => __('Brilho suave no hover', 'cct'),
                    'css' => array(
                        'box-shadow' => '0 0 20px rgba(0,123,255,0.3)',
                        'transform' => 'scale(1.02)'
                    )
                ),
                'tilt' => array(
                    'name' => __('Tilt', 'cct'),
                    'description' => __('Inclinação sutil no hover', 'cct'),
                    'css' => array(
                        'transform' => 'rotate(1deg) scale(1.02)'
                    )
                ),
                'zoom' => array(
                    'name' => __('Zoom', 'cct'),
                    'description' => __('Ampliação no hover', 'cct'),
                    'css' => array(
                        'transform' => 'scale(1.05)'
                    )
                ),
                'slide_up' => array(
                    'name' => __('Slide Up', 'cct'),
                    'description' => __('Deslizamento para cima', 'cct'),
                    'css' => array(
                        'transform' => 'translateY(-5px)'
                    )
                )
            ),
            'focus_effects' => array(
                'outline_glow' => array(
                    'name' => __('Outline Glow', 'cct'),
                    'description' => __('Contorno brilhante no foco', 'cct'),
                    'css' => array(
                        'outline' => '2px solid rgba(0,123,255,0.5)',
                        'outline-offset' => '2px'
                    )
                ),
                'scale_focus' => array(
                    'name' => __('Scale Focus', 'cct'),
                    'description' => __('Escala no foco', 'cct'),
                    'css' => array(
                        'transform' => 'scale(1.02)',
                        'outline' => '1px solid rgba(0,123,255,0.3)'
                    )
                )
            ),
            'loading_effects' => array(
                'spinner' => array(
                    'name' => __('Spinner', 'cct'),
                    'description' => __('Rotação contínua', 'cct'),
                    'animation' => 'spin 1s linear infinite'
                ),
                'pulse' => array(
                    'name' => __('Pulse', 'cct'),
                    'description' => __('Pulsação suave', 'cct'),
                    'animation' => 'pulse 2s ease-in-out infinite'
                ),
                'dots' => array(
                    'name' => __('Dots', 'cct'),
                    'description' => __('Pontos animados', 'cct'),
                    'animation' => 'dots 1.4s ease-in-out infinite'
                )
            )
        );
    }
    
    /**
     * Inicializa configurações de performance
     */
    private function init_performance_settings() {
        $this->performance_settings = array(
            'reduce_motion' => array(
                'enabled' => true,
                'media_query' => '@media (prefers-reduced-motion: reduce)'
            ),
            'gpu_acceleration' => array(
                'enabled' => true,
                'properties' => array('transform', 'opacity', 'filter')
            ),
            'will_change' => array(
                'enabled' => true,
                'auto_remove' => true
            ),
            'intersection_observer' => array(
                'enabled' => true,
                'threshold' => 0.1,
                'root_margin' => '50px'
            )
        );
    }
    
    /**
     * Registra configurações no Customizer
     */
    public function register_customizer() {
        $this->add_animation_panel();
        $this->add_animation_sections();
        $this->add_animation_settings();
        $this->add_animation_controls();
    }
    
    /**
     * Adiciona painel de animações
     */
    private function add_animation_panel() {
        $this->wp_customize->add_panel($this->prefix . 'panel', array(
            'title' => __('Sistema de Animações', 'cct'),
            'description' => __('Configure animações, micro-interações e transições para melhorar a experiência do usuário.', 'cct'),
            'priority' => 160,
            'capability' => 'edit_theme_options',
        ));
    }
    
    /**
     * Adiciona seções de animações
     */
    private function add_animation_sections() {
        // Seção de configurações gerais
        $this->wp_customize->add_section($this->prefix . 'general', array(
            'title' => __('Configurações Gerais', 'cct'),
            'description' => __('Configurações globais do sistema de animações.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 10,
        ));
        
        // Seção de presets
        $this->wp_customize->add_section($this->prefix . 'presets', array(
            'title' => __('Presets de Animações', 'cct'),
            'description' => __('Escolha e configure presets de animações predefinidas.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 20,
        ));
        
        // Seção de micro-interações
        $this->wp_customize->add_section($this->prefix . 'micro_interactions', array(
            'title' => __('Micro-interações', 'cct'),
            'description' => __('Configure efeitos de hover, focus e outras micro-interações.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 30,
        ));
        
        // Seção de transições de página
        $this->wp_customize->add_section($this->prefix . 'page_transitions', array(
            'title' => __('Transições de Página', 'cct'),
            'description' => __('Configure transições suaves entre páginas.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 40,
        ));
        
        // Seção de performance
        $this->wp_customize->add_section($this->prefix . 'performance', array(
            'title' => __('Performance', 'cct'),
            'description' => __('Otimizações de performance para animações.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 50,
        ));
    }
    
    /**
     * Adiciona configurações de animações
     */
    private function add_animation_settings() {
        // Configurações gerais
        $this->add_setting('animations_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('global_duration', array(
            'default' => isset($this->timing_settings['duration']['default']) ? $this->timing_settings['duration']['default'] : 0.3,
            'sanitize_callback' => 'floatval',
        ));
        
        $this->add_setting('global_easing', array(
            'default' => 'ease-in-out',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('reduce_motion_respect', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Configurações de presets
        if (is_array($this->animation_presets) && !empty($this->animation_presets)) {
            foreach ($this->animation_presets as $preset_key => $preset) {
                $this->add_setting("preset_{$preset_key}_enabled", array(
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ));
                
                $this->add_setting("preset_{$preset_key}_duration", array(
                    'default' => isset($preset['duration']) ? floatval(str_replace('s', '', $preset['duration'])) : 0.3,
                    'sanitize_callback' => 'floatval',
                ));
            }
        }
        
        // Configurações de micro-interações
        $this->add_setting('hover_effects_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('focus_effects_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('loading_effects_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Configurações de transições de página
        $this->add_setting('page_transitions_enabled', array(
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('page_transition_type', array(
            'default' => 'fade',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('page_transition_duration', array(
            'default' => 0.5,
            'sanitize_callback' => 'floatval',
        ));
        
        // Configurações de performance
        $this->add_setting('gpu_acceleration_enabled', array(
            'default' => isset($this->performance_settings['gpu_acceleration']['enabled']) ? $this->performance_settings['gpu_acceleration']['enabled'] : true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('intersection_observer_enabled', array(
            'default' => isset($this->performance_settings['intersection_observer']['enabled']) ? $this->performance_settings['intersection_observer']['enabled'] : true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
    }
    
    /**
     * Adiciona controles de animações
     */
    private function add_animation_controls() {
        // Controles gerais
        $this->add_control('animations_enabled', array(
            'label' => __('Ativar Animações', 'cct'),
            'description' => __('Ativa ou desativa o sistema de animações globalmente.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('global_duration', array(
            'label' => __('Duração Global (segundos)', 'cct'),
            'description' => __('Duração padrão para todas as animações.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'range',
            'input_attrs' => array(
                'min' => isset($this->timing_settings['duration']['min']) ? $this->timing_settings['duration']['min'] : 0.1,
                'max' => isset($this->timing_settings['duration']['max']) ? $this->timing_settings['duration']['max'] : 3.0,
                'step' => isset($this->timing_settings['duration']['step']) ? $this->timing_settings['duration']['step'] : 0.1,
            ),
        ));
        
        $this->add_control('global_easing', array(
            'label' => __('Easing Global', 'cct'),
            'description' => __('Curva de animação padrão.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'select',
            'choices' => $this->get_easing_choices(),
        ));
        
        $this->add_control('reduce_motion_respect', array(
            'label' => __('Respeitar Preferência de Movimento Reduzido', 'cct'),
            'description' => __('Desativa animações para usuários que preferem movimento reduzido.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        // Controles de micro-interações
        $this->add_control('hover_effects_enabled', array(
            'label' => __('Ativar Efeitos de Hover', 'cct'),
            'description' => __('Ativa efeitos visuais ao passar o mouse sobre elementos.', 'cct'),
            'section' => $this->prefix . 'micro_interactions',
            'type' => 'checkbox',
        ));
        
        $this->add_control('focus_effects_enabled', array(
            'label' => __('Ativar Efeitos de Focus', 'cct'),
            'description' => __('Ativa efeitos visuais ao focar elementos com teclado.', 'cct'),
            'section' => $this->prefix . 'micro_interactions',
            'type' => 'checkbox',
        ));
        
        $this->add_control('loading_effects_enabled', array(
            'label' => __('Ativar Efeitos de Carregamento', 'cct'),
            'description' => __('Ativa animações de carregamento.', 'cct'),
            'section' => $this->prefix . 'micro_interactions',
            'type' => 'checkbox',
        ));
        
        // Controles de transições de página
        $this->add_control('page_transitions_enabled', array(
            'label' => __('Ativar Transições de Página', 'cct'),
            'description' => __('Ativa transições suaves entre páginas.', 'cct'),
            'section' => $this->prefix . 'page_transitions',
            'type' => 'checkbox',
        ));
        
        $this->add_control('page_transition_type', array(
            'label' => __('Tipo de Transição', 'cct'),
            'description' => __('Escolha o tipo de transição entre páginas.', 'cct'),
            'section' => $this->prefix . 'page_transitions',
            'type' => 'select',
            'choices' => $this->get_transition_choices(),
        ));
        
        $this->add_control('page_transition_duration', array(
            'label' => __('Duração da Transição (segundos)', 'cct'),
            'description' => __('Duração das transições de página.', 'cct'),
            'section' => $this->prefix . 'page_transitions',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0.1,
                'max' => 2.0,
                'step' => 0.1,
            ),
        ));
        
        // Controles de performance
        $this->add_control('gpu_acceleration_enabled', array(
            'label' => __('Ativar Aceleração GPU', 'cct'),
            'description' => __('Usa aceleração por hardware para melhor performance.', 'cct'),
            'section' => $this->prefix . 'performance',
            'type' => 'checkbox',
        ));
        
        $this->add_control('intersection_observer_enabled', array(
            'label' => __('Ativar Intersection Observer', 'cct'),
            'description' => __('Otimiza animações baseadas na visibilidade dos elementos.', 'cct'),
            'section' => $this->prefix . 'performance',
            'type' => 'checkbox',
        ));
    }
    
    /**
     * Método auxiliar para adicionar configurações
     */
    private function add_setting($setting_id, $args = array()) {
        $this->wp_customize->add_setting($this->prefix . $setting_id, $args);
    }
    
    /**
     * Método auxiliar para adicionar controles
     */
    private function add_control($control_id, $args = array()) {
        $args['settings'] = $this->prefix . $control_id;
        $this->wp_customize->add_control($this->prefix . $control_id, $args);
    }
    
    /**
     * Obtém opções de easing
     */
    private function get_easing_choices() {
        $choices = array();
        
        if (is_array($this->timing_settings['easing']) && !empty($this->timing_settings['easing'])) {
            foreach ($this->timing_settings['easing'] as $key => $easing) {
                $choices[$easing['value']] = $easing['name'];
            }
        } else {
            // Fallback com opções básicas
            $choices = array(
                'linear' => __('Linear', 'cct'),
                'ease' => __('Ease', 'cct'),
                'ease-in' => __('Ease In', 'cct'),
                'ease-out' => __('Ease Out', 'cct'),
                'ease-in-out' => __('Ease In Out', 'cct'),
            );
        }
        
        return $choices;
    }
    
    /**
     * Obtém opções de transições
     */
    private function get_transition_choices() {
        $choices = array();
        
        if (is_array($this->animation_presets) && !empty($this->animation_presets)) {
            foreach ($this->animation_presets as $key => $preset) {
                $choices[$key] = isset($preset['name']) ? $preset['name'] : $key;
            }
        } else {
            // Fallback com transições básicas
            $choices = array(
                'fade' => __('Fade', 'cct'),
                'slide' => __('Slide', 'cct'),
                'scale' => __('Scale', 'cct'),
            );
        }
        
        return $choices;
    }
    
    /**
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        // CSS das animações
        wp_enqueue_style(
            'cct-animations',
            get_template_directory_uri() . '/css/cct-animations.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript das animações
        wp_enqueue_script(
            'cct-animations',
            get_template_directory_uri() . '/js/cct-animations.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localização do script
        wp_localize_script('cct-animations', 'cctAnimations', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_animations_nonce'),
            'settings' => $this->get_frontend_settings(),
        ));
    }
    
    /**
     * Obtém configurações para o frontend
     */
    private function get_frontend_settings() {
        return array(
            'enabled' => get_theme_mod($this->prefix . 'animations_enabled', true),
            'duration' => get_theme_mod($this->prefix . 'global_duration', 0.3),
            'easing' => get_theme_mod($this->prefix . 'global_easing', 'ease-in-out'),
            'reduceMotion' => get_theme_mod($this->prefix . 'reduce_motion_respect', true),
            'hoverEffects' => get_theme_mod($this->prefix . 'hover_effects_enabled', true),
            'focusEffects' => get_theme_mod($this->prefix . 'focus_effects_enabled', true),
            'loadingEffects' => get_theme_mod($this->prefix . 'loading_effects_enabled', true),
            'pageTransitions' => get_theme_mod($this->prefix . 'page_transitions_enabled', false),
            'pageTransitionType' => get_theme_mod($this->prefix . 'page_transition_type', 'fade'),
            'pageTransitionDuration' => get_theme_mod($this->prefix . 'page_transition_duration', 0.5),
            'gpuAcceleration' => get_theme_mod($this->prefix . 'gpu_acceleration_enabled', true),
            'intersectionObserver' => get_theme_mod($this->prefix . 'intersection_observer_enabled', true),
        );
    }
    
    /**
     * Gera CSS customizado
     */
    public function output_custom_css() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['enabled']) {
            return;
        }
        
        echo "<style id='cct-animations-custom-css'>\n";
        
        // CSS base para animações
        echo ":root {\n";
        echo "  --cct-animation-duration: {$settings['duration']}s;\n";
        echo "  --cct-animation-easing: {$settings['easing']};\n";
        echo "}\n";
        
        // Respeitar preferência de movimento reduzido
        if ($settings['reduceMotion']) {
            echo "@media (prefers-reduced-motion: reduce) {\n";
            echo "  *, *::before, *::after {\n";
            echo "    animation-duration: 0.01ms !important;\n";
            echo "    animation-iteration-count: 1 !important;\n";
            echo "    transition-duration: 0.01ms !important;\n";
            echo "  }\n";
            echo "}\n";
        }
        
        // CSS para aceleração GPU
        if ($settings['gpuAcceleration']) {
            echo ".cct-animate, .cct-hover-effect {\n";
            echo "  will-change: transform, opacity;\n";
            echo "  transform: translateZ(0);\n";
            echo "}\n";
        }
        
        echo "</style>\n";
    }
    
    /**
     * Gera JavaScript customizado
     */
    public function output_custom_js() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['enabled']) {
            return;
        }
        
        echo "<script id='cct-animations-custom-js'>\n";
        echo "document.addEventListener('DOMContentLoaded', function() {\n";
        echo "  if (typeof CCTAnimations !== 'undefined') {\n";
        echo "    CCTAnimations.init(" . wp_json_encode($settings) . ");\n";
        echo "  }\n";
        echo "});\n";
        echo "</script>\n";
    }
    
    /**
     * Shortcode para animações
     */
    public function animate_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'type' => 'fadeIn',
            'duration' => '',
            'delay' => '0',
            'easing' => '',
            'trigger' => 'scroll',
            'class' => '',
        ), $atts, 'cct_animate');
        
        $classes = array('cct-animate');
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $style_attrs = array();
        
        if (!empty($atts['duration'])) {
            $style_attrs[] = 'animation-duration: ' . floatval($atts['duration']) . 's';
        }
        
        if (!empty($atts['delay'])) {
            $style_attrs[] = 'animation-delay: ' . floatval($atts['delay']) . 's';
        }
        
        if (!empty($atts['easing'])) {
            $style_attrs[] = 'animation-timing-function: ' . esc_attr($atts['easing']);
        }
        
        $data_attrs = array(
            'data-animation' => esc_attr($atts['type']),
            'data-trigger' => esc_attr($atts['trigger']),
        );
        
        $output = '<div class="' . implode(' ', $classes) . '"';
        
        if (!empty($style_attrs)) {
            $output .= ' style="' . implode('; ', $style_attrs) . '"';
        }
        
        foreach ($data_attrs as $attr => $value) {
            $output .= ' ' . $attr . '="' . $value . '"';
        }
        
        $output .= '>';
        $output .= do_shortcode($content);
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para efeitos de hover
     */
    public function hover_effect_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'effect' => 'lift',
            'duration' => '0.3',
            'class' => '',
        ), $atts, 'cct_hover_effect');
        
        $classes = array('cct-hover-effect', 'cct-hover-' . sanitize_html_class($atts['effect']));
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $style = 'transition-duration: ' . floatval($atts['duration']) . 's';
        
        $output = '<div class="' . implode(' ', $classes) . '" style="' . $style . '">';
        $output .= do_shortcode($content);
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * AJAX handler para preview de animações
     */
    public function ajax_preview_animation() {
        check_ajax_referer('cct_animations_nonce', 'nonce');
        
        $animation_type = sanitize_text_field($_POST['animation_type'] ?? '');
        $duration = floatval($_POST['duration'] ?? 0.3);
        $easing = sanitize_text_field($_POST['easing'] ?? 'ease-in-out');
        
        if (empty($animation_type)) {
            wp_die(__('Tipo de animação não especificado.', 'cct'));
        }
        
        $response = array(
            'success' => true,
            'data' => array(
                'animation' => $animation_type,
                'duration' => $duration,
                'easing' => $easing,
                'css' => $this->generate_animation_css($animation_type, $duration, $easing)
            )
        );
        
        wp_send_json($response);
    }
    
    /**
     * Gera CSS para animação específica
     */
    private function generate_animation_css($animation_type, $duration, $easing) {
        $css = "";
        
        if (isset($this->animation_presets[$animation_type])) {
            $preset = $this->animation_presets[$animation_type];
            
            if (isset($preset['keyframes']) && is_array($preset['keyframes'])) {
                foreach ($preset['keyframes'] as $keyframe_name => $keyframe) {
                    $css .= "@keyframes {$keyframe_name} {\n";
                    
                    foreach ($keyframe as $percentage => $properties) {
                        $css .= "  {$percentage} {\n";
                        
                        foreach ($properties as $property => $value) {
                            $css .= "    {$property}: {$value};\n";
                        }
                        
                        $css .= "  }\n";
                    }
                    
                    $css .= "}\n";
                }
            }
            
            $css .= ".cct-animate-{$animation_type} {\n";
            $css .= "  animation: {$animation_type} {$duration}s {$easing};\n";
            $css .= "}\n";
        }
        
        return $css;
    }
    
    /**
     * Sanitiza array JSON
     */
    public function sanitize_json_array($input) {
        if (is_string($input)) {
            $decoded = json_decode($input, true);
            return is_array($decoded) ? $decoded : array();
        }
        
        return is_array($input) ? $input : array();
    }
    
    /**
     * Obtém estatísticas das animações
     */
    public function get_animation_stats() {
        return array(
            'total_presets' => is_array($this->animation_presets) ? count($this->animation_presets) : 0,
            'total_hover_effects' => is_array($this->micro_interactions['hover_effects']) ? count($this->micro_interactions['hover_effects']) : 0,
            'total_focus_effects' => is_array($this->micro_interactions['focus_effects']) ? count($this->micro_interactions['focus_effects']) : 0,
            'total_loading_effects' => is_array($this->micro_interactions['loading_effects']) ? count($this->micro_interactions['loading_effects']) : 0,
            'performance_optimized' => isset($this->performance_settings['gpu_acceleration']['enabled']) ? $this->performance_settings['gpu_acceleration']['enabled'] : false,
        );
    }
    
    /**
     * Exporta configurações de animações
     */
    public function export_animation_settings() {
        $settings = array();
        
        // Obter todas as configurações do tema relacionadas a animações
        $theme_mods = get_theme_mods();
        
        foreach ($theme_mods as $key => $value) {
            if (strpos($key, $this->prefix) === 0) {
                $settings[$key] = $value;
            }
        }
        
        return array(
            'version' => '1.0.0',
            'timestamp' => current_time('timestamp'),
            'settings' => $settings,
            'presets' => $this->animation_presets,
            'micro_interactions' => $this->micro_interactions,
        );
    }
}