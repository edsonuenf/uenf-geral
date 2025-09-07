<?php
/**
 * Sistema de Sombras - Gerenciador Principal
 * 
 * Sistema completo de elevação e profundidade incluindo:
 * - 8 níveis de elevação baseados no Material Design
 * - Sombras contextuais para diferentes elementos
 * - Animações de elevação em hover
 * - Customização de cores e intensidade
 * - Otimização de performance
 * - Responsividade adaptativa
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
 * Classe principal do Sistema de Sombras
 */
class CCT_Shadow_Manager {
    
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
    private $prefix = 'cct_shadow_';
    
    /**
     * Níveis de elevação
     * 
     * @var array
     */
    private $elevation_levels;
    
    /**
     * Configurações de sombras
     * 
     * @var array
     */
    private $shadow_settings;
    
    /**
     * Presets de sombras
     * 
     * @var array
     */
    private $shadow_presets;
    
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
        $this->init_elevation_levels();
        $this->init_shadow_settings();
        $this->init_shadow_presets();
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
        add_shortcode('cct_shadow', array($this, 'shadow_shortcode'));
        add_shortcode('cct_elevation', array($this, 'elevation_shortcode'));
        add_shortcode('cct_card', array($this, 'card_shortcode'));
        
        // AJAX handlers
        add_action('wp_ajax_cct_preview_shadow', array($this, 'ajax_preview_shadow'));
        add_action('wp_ajax_nopriv_cct_preview_shadow', array($this, 'ajax_preview_shadow'));
    }
    
    /**
     * Inicializa níveis de elevação
     */
    private function init_elevation_levels() {
        $this->elevation_levels = array(
            0 => array(
                'name' => __('Nível 0 - Plano', 'cct'),
                'description' => __('Sem elevação, elemento no mesmo plano', 'cct'),
                'shadow' => 'none',
                'use_cases' => array(__('Texto', 'cct'), __('Ícones planos', 'cct')),
                'z_index' => 0
            ),
            1 => array(
                'name' => __('Nível 1 - Muito Baixo', 'cct'),
                'description' => __('Elevação mínima para separação sutil', 'cct'),
                'shadow' => '0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24)',
                'use_cases' => array(__('Cards simples', 'cct'), __('Divisores', 'cct')),
                'z_index' => 1
            ),
            2 => array(
                'name' => __('Nível 2 - Baixo', 'cct'),
                'description' => __('Elevação baixa para elementos de conteúdo', 'cct'),
                'shadow' => '0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23)',
                'use_cases' => array(__('Cards de conteúdo', 'cct'), __('Imagens', 'cct')),
                'z_index' => 2
            ),
            4 => array(
                'name' => __('Nível 4 - Médio', 'cct'),
                'description' => __('Elevação média para elementos interativos', 'cct'),
                'shadow' => '0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23)',
                'use_cases' => array(__('Botões', 'cct'), __('Cards hover', 'cct')),
                'z_index' => 4
            ),
            6 => array(
                'name' => __('Nível 6 - Médio-Alto', 'cct'),
                'description' => __('Elevação para elementos em destaque', 'cct'),
                'shadow' => '0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22)',
                'use_cases' => array(__('Botões hover', 'cct'), __('Cards ativos', 'cct')),
                'z_index' => 6
            ),
            8 => array(
                'name' => __('Nível 8 - Alto', 'cct'),
                'description' => __('Elevação alta para elementos importantes', 'cct'),
                'shadow' => '0 19px 38px rgba(0, 0, 0, 0.30), 0 15px 12px rgba(0, 0, 0, 0.22)',
                'use_cases' => array(__('Modais', 'cct'), __('Sidebars flutuantes', 'cct')),
                'z_index' => 8
            ),
            12 => array(
                'name' => __('Nível 12 - Muito Alto', 'cct'),
                'description' => __('Elevação muito alta para overlays', 'cct'),
                'shadow' => '0 25px 50px rgba(0, 0, 0, 0.25), 0 12px 24px rgba(0, 0, 0, 0.22)',
                'use_cases' => array(__('Menus dropdown', 'cct'), __('Popups', 'cct')),
                'z_index' => 12
            ),
            16 => array(
                'name' => __('Nível 16 - Extremo', 'cct'),
                'description' => __('Elevação extrema para elementos críticos', 'cct'),
                'shadow' => '0 30px 60px rgba(0, 0, 0, 0.30), 0 18px 36px rgba(0, 0, 0, 0.22)',
                'use_cases' => array(__('Tooltips', 'cct'), __('Notificações', 'cct')),
                'z_index' => 16
            ),
            24 => array(
                'name' => __('Nível 24 - Máximo', 'cct'),
                'description' => __('Elevação máxima para elementos de sistema', 'cct'),
                'shadow' => '0 38px 76px rgba(0, 0, 0, 0.35), 0 24px 48px rgba(0, 0, 0, 0.22)',
                'use_cases' => array(__('Overlays de sistema', 'cct'), __('Modais críticos', 'cct')),
                'z_index' => 24
            )
        );
    }
    
    /**
     * Inicializa configurações de sombras
     */
    private function init_shadow_settings() {
        $this->shadow_settings = array(
            'color' => array(
                'default' => '#000000',
                'description' => __('Cor base das sombras', 'cct'),
                'type' => 'color'
            ),
            'opacity' => array(
                'default' => 0.25,
                'min' => 0.1,
                'max' => 1.0,
                'step' => 0.05,
                'description' => __('Opacidade global das sombras', 'cct'),
                'type' => 'range'
            ),
            'blur_intensity' => array(
                'default' => 1.0,
                'min' => 0.5,
                'max' => 2.0,
                'step' => 0.1,
                'description' => __('Intensidade do blur das sombras', 'cct'),
                'type' => 'range'
            ),
            'spread_intensity' => array(
                'default' => 1.0,
                'min' => 0.5,
                'max' => 2.0,
                'step' => 0.1,
                'description' => __('Intensidade do spread das sombras', 'cct'),
                'type' => 'range'
            ),
            'animation_duration' => array(
                'default' => 0.3,
                'min' => 0.1,
                'max' => 1.0,
                'step' => 0.1,
                'description' => __('Duração das animações de elevação', 'cct'),
                'type' => 'range'
            ),
            'animation_easing' => array(
                'default' => 'cubic-bezier(0.25, 0.8, 0.25, 1)',
                'options' => array(
                    'ease' => 'ease',
                    'ease-in' => 'ease-in',
                    'ease-out' => 'ease-out',
                    'ease-in-out' => 'ease-in-out',
                    'cubic-bezier(0.25, 0.8, 0.25, 1)' => __('Material Design', 'cct'),
                    'cubic-bezier(0.4, 0.0, 0.2, 1)' => __('Sharp', 'cct'),
                    'cubic-bezier(0.0, 0.0, 0.2, 1)' => __('Accelerate', 'cct'),
                    'cubic-bezier(0.4, 0.0, 1, 1)' => __('Decelerate', 'cct')
                ),
                'description' => __('Curva de animação para elevação', 'cct'),
                'type' => 'select'
            )
        );
    }
    
    /**
     * Inicializa presets de sombras
     */
    private function init_shadow_presets() {
        $this->shadow_presets = array(
            'material' => array(
                'name' => __('Material Design', 'cct'),
                'description' => __('Sombras baseadas no Material Design do Google', 'cct'),
                'color' => '#000000',
                'opacity' => 0.25,
                'style' => 'material'
            ),
            'soft' => array(
                'name' => __('Suave', 'cct'),
                'description' => __('Sombras suaves e difusas', 'cct'),
                'color' => '#000000',
                'opacity' => 0.15,
                'style' => 'soft'
            ),
            'sharp' => array(
                'name' => __('Nítida', 'cct'),
                'description' => __('Sombras nítidas e definidas', 'cct'),
                'color' => '#000000',
                'opacity' => 0.35,
                'style' => 'sharp'
            ),
            'colored' => array(
                'name' => __('Colorida', 'cct'),
                'description' => __('Sombras com cores personalizadas', 'cct'),
                'color' => '#667eea',
                'opacity' => 0.20,
                'style' => 'colored'
            ),
            'minimal' => array(
                'name' => __('Minimalista', 'cct'),
                'description' => __('Sombras muito sutis', 'cct'),
                'color' => '#000000',
                'opacity' => 0.08,
                'style' => 'minimal'
            ),
            'dramatic' => array(
                'name' => __('Dramática', 'cct'),
                'description' => __('Sombras intensas e dramáticas', 'cct'),
                'color' => '#000000',
                'opacity' => 0.45,
                'style' => 'dramatic'
            )
        );
    }
    
    /**
     * Inicializa configurações de performance
     */
    private function init_performance_settings() {
        $this->performance_settings = array(
            'gpu_acceleration' => array(
                'default' => true,
                'description' => __('Ativar aceleração GPU para sombras', 'cct')
            ),
            'will_change' => array(
                'default' => true,
                'description' => __('Usar will-change para otimização', 'cct')
            ),
            'reduce_motion_respect' => array(
                'default' => true,
                'description' => __('Respeitar preferência de movimento reduzido', 'cct')
            ),
            'mobile_optimization' => array(
                'default' => true,
                'description' => __('Otimizações específicas para mobile', 'cct')
            )
        );
    }
    
    /**
     * Registra configurações no Customizer
     */
    public function register_customizer() {
        $this->add_shadow_panel();
        $this->add_shadow_sections();
        $this->add_shadow_settings();
        $this->add_shadow_controls();
    }
    
    /**
     * Adiciona painel de sombras
     */
    private function add_shadow_panel() {
        $this->wp_customize->add_panel($this->prefix . 'panel', array(
            'title' => __('Sistema de Sombras', 'cct'),
            'description' => __('Configure elevação, profundidade e sombras para criar hierarquia visual moderna.', 'cct'),
            'priority' => 180,
            'capability' => 'edit_theme_options',
        ));
    }
    
    /**
     * Adiciona seções de sombras
     */
    private function add_shadow_sections() {
        // Seção de configurações gerais
        $this->wp_customize->add_section($this->prefix . 'general', array(
            'title' => __('Configurações Gerais', 'cct'),
            'description' => __('Configurações globais do sistema de sombras e elevação.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 10,
        ));
        
        // Seção de níveis de elevação
        $this->wp_customize->add_section($this->prefix . 'elevation', array(
            'title' => __('Níveis de Elevação', 'cct'),
            'description' => __('Configure os 8 níveis de elevação baseados no Material Design.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 20,
        ));
        
        // Seção de presets
        $this->wp_customize->add_section($this->prefix . 'presets', array(
            'title' => __('Presets de Sombras', 'cct'),
            'description' => __('Escolha entre diferentes estilos de sombras predefinidos.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 30,
        ));
        
        // Seção de animações
        $this->wp_customize->add_section($this->prefix . 'animations', array(
            'title' => __('Animações de Elevação', 'cct'),
            'description' => __('Configure animações de hover e transições de elevação.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 40,
        ));
        
        // Seção de performance
        $this->wp_customize->add_section($this->prefix . 'performance', array(
            'title' => __('Performance', 'cct'),
            'description' => __('Otimizações de performance para sombras e animações.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 50,
        ));
    }
    
    /**
     * Adiciona configurações de sombras
     */
    private function add_shadow_settings() {
        // Configurações gerais
        $this->add_setting('shadows_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('shadow_color', array(
            'default' => $this->shadow_settings['color']['default'],
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $this->add_setting('shadow_opacity', array(
            'default' => $this->shadow_settings['opacity']['default'],
            'sanitize_callback' => 'floatval',
        ));
        
        $this->add_setting('blur_intensity', array(
            'default' => $this->shadow_settings['blur_intensity']['default'],
            'sanitize_callback' => 'floatval',
        ));
        
        $this->add_setting('spread_intensity', array(
            'default' => $this->shadow_settings['spread_intensity']['default'],
            'sanitize_callback' => 'floatval',
        ));
        
        // Configurações de presets
        $this->add_setting('active_preset', array(
            'default' => 'material',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        // Configurações de animação
        $this->add_setting('animations_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('animation_duration', array(
            'default' => $this->shadow_settings['animation_duration']['default'],
            'sanitize_callback' => 'floatval',
        ));
        
        $this->add_setting('animation_easing', array(
            'default' => $this->shadow_settings['animation_easing']['default'],
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        // Configurações de performance
        foreach ($this->performance_settings as $key => $setting) {
            $this->add_setting($key, array(
                'default' => $setting['default'],
                'sanitize_callback' => 'rest_sanitize_boolean',
            ));
        }
    }
    
    /**
     * Adiciona controles de sombras
     */
    private function add_shadow_controls() {
        // Controles gerais
        $this->add_control('shadows_enabled', array(
            'label' => __('Ativar Sistema de Sombras', 'cct'),
            'description' => __('Ativa ou desativa o sistema de sombras globalmente.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('shadow_color', array(
            'label' => __('Cor das Sombras', 'cct'),
            'description' => __('Cor base para todas as sombras.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'color',
        ));
        
        $this->add_control('shadow_opacity', array(
            'label' => __('Opacidade das Sombras', 'cct'),
            'description' => __('Opacidade global das sombras (0.1 - 1.0).', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'range',
            'input_attrs' => array(
                'min' => $this->shadow_settings['opacity']['min'],
                'max' => $this->shadow_settings['opacity']['max'],
                'step' => $this->shadow_settings['opacity']['step'],
            ),
        ));
        
        $this->add_control('blur_intensity', array(
            'label' => __('Intensidade do Blur', 'cct'),
            'description' => __('Intensidade do desfoque das sombras.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'range',
            'input_attrs' => array(
                'min' => $this->shadow_settings['blur_intensity']['min'],
                'max' => $this->shadow_settings['blur_intensity']['max'],
                'step' => $this->shadow_settings['blur_intensity']['step'],
            ),
        ));
        
        $this->add_control('spread_intensity', array(
            'label' => __('Intensidade do Spread', 'cct'),
            'description' => __('Intensidade da expansão das sombras.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'range',
            'input_attrs' => array(
                'min' => $this->shadow_settings['spread_intensity']['min'],
                'max' => $this->shadow_settings['spread_intensity']['max'],
                'step' => $this->shadow_settings['spread_intensity']['step'],
            ),
        ));
        
        // Controles de presets
        $this->add_control('active_preset', array(
            'label' => __('Preset Ativo', 'cct'),
            'description' => __('Escolha um estilo de sombra predefinido.', 'cct'),
            'section' => $this->prefix . 'presets',
            'type' => 'select',
            'choices' => $this->get_preset_choices(),
        ));
        
        // Controles de animação
        $this->add_control('animations_enabled', array(
            'label' => __('Ativar Animações de Elevação', 'cct'),
            'description' => __('Ativa animações de hover e transições de elevação.', 'cct'),
            'section' => $this->prefix . 'animations',
            'type' => 'checkbox',
        ));
        
        $this->add_control('animation_duration', array(
            'label' => __('Duração da Animação (segundos)', 'cct'),
            'description' => __('Duração das animações de elevação.', 'cct'),
            'section' => $this->prefix . 'animations',
            'type' => 'range',
            'input_attrs' => array(
                'min' => $this->shadow_settings['animation_duration']['min'],
                'max' => $this->shadow_settings['animation_duration']['max'],
                'step' => $this->shadow_settings['animation_duration']['step'],
            ),
        ));
        
        $this->add_control('animation_easing', array(
            'label' => __('Curva de Animação', 'cct'),
            'description' => __('Tipo de curva para as animações de elevação.', 'cct'),
            'section' => $this->prefix . 'animations',
            'type' => 'select',
            'choices' => $this->shadow_settings['animation_easing']['options'],
        ));
        
        // Controles de performance
        foreach ($this->performance_settings as $key => $setting) {
            $this->add_control($key, array(
                'label' => $this->get_performance_label($key),
                'description' => $setting['description'],
                'section' => $this->prefix . 'performance',
                'type' => 'checkbox',
            ));
        }
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
     * Obtém opções de presets
     */
    private function get_preset_choices() {
        $choices = array();
        
        if (is_array($this->shadow_presets) && !empty($this->shadow_presets)) {
            foreach ($this->shadow_presets as $key => $preset) {
                $choices[$key] = isset($preset['name']) ? $preset['name'] : $key;
            }
        }
        
        return $choices;
    }
    
    /**
     * Obtém label para configurações de performance
     */
    private function get_performance_label($key) {
        $labels = array(
            'gpu_acceleration' => __('Aceleração GPU', 'cct'),
            'will_change' => __('Will-Change Optimization', 'cct'),
            'reduce_motion_respect' => __('Respeitar Movimento Reduzido', 'cct'),
            'mobile_optimization' => __('Otimização Mobile', 'cct'),
        );
        
        return isset($labels[$key]) ? $labels[$key] : ucfirst(str_replace('_', ' ', $key));
    }
    
    /**
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        // CSS das sombras
        wp_enqueue_style(
            'cct-shadows',
            get_template_directory_uri() . '/css/cct-shadows.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript das sombras
        wp_enqueue_script(
            'cct-shadows',
            get_template_directory_uri() . '/js/cct-shadows.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localização do script
        wp_localize_script('cct-shadows', 'cctShadows', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_shadows_nonce'),
            'settings' => $this->get_frontend_settings(),
            'elevationLevels' => $this->elevation_levels,
            'presets' => $this->shadow_presets,
        ));
    }
    
    /**
     * Obtém configurações para o frontend
     */
    private function get_frontend_settings() {
        return array(
            'shadowsEnabled' => get_theme_mod($this->prefix . 'shadows_enabled', true),
            'shadowColor' => get_theme_mod($this->prefix . 'shadow_color', '#000000'),
            'shadowOpacity' => get_theme_mod($this->prefix . 'shadow_opacity', 0.25),
            'blurIntensity' => get_theme_mod($this->prefix . 'blur_intensity', 1.0),
            'spreadIntensity' => get_theme_mod($this->prefix . 'spread_intensity', 1.0),
            'activePreset' => get_theme_mod($this->prefix . 'active_preset', 'material'),
            'animationsEnabled' => get_theme_mod($this->prefix . 'animations_enabled', true),
            'animationDuration' => get_theme_mod($this->prefix . 'animation_duration', 0.3),
            'animationEasing' => get_theme_mod($this->prefix . 'animation_easing', 'cubic-bezier(0.25, 0.8, 0.25, 1)'),
            'gpuAcceleration' => get_theme_mod($this->prefix . 'gpu_acceleration', true),
            'willChange' => get_theme_mod($this->prefix . 'will_change', true),
            'reduceMotionRespect' => get_theme_mod($this->prefix . 'reduce_motion_respect', true),
            'mobileOptimization' => get_theme_mod($this->prefix . 'mobile_optimization', true),
        );
    }
    
    /**
     * Gera CSS customizado
     */
    public function output_custom_css() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['shadowsEnabled']) {
            return;
        }
        
        echo "<style id='cct-shadows-custom-css'>\n";
        
        // Variáveis CSS para sombras
        echo ":root {\n";
        echo "  --cct-shadow-color: {$settings['shadowColor']};\n";
        echo "  --cct-shadow-opacity: {$settings['shadowOpacity']};\n";
        echo "  --cct-blur-intensity: {$settings['blurIntensity']};\n";
        echo "  --cct-spread-intensity: {$settings['spreadIntensity']};\n";
        echo "  --cct-animation-duration: {$settings['animationDuration']}s;\n";
        echo "  --cct-animation-easing: {$settings['animationEasing']};\n";
        
        // Gerar variáveis para cada nível de elevação
        foreach ($this->elevation_levels as $level => $data) {
            $shadow = $this->calculate_shadow($data['shadow'], $settings);
            echo "  --cct-elevation-{$level}: {$shadow};\n";
        }
        
        echo "}\n";
        
        // Classes de elevação
        foreach ($this->elevation_levels as $level => $data) {
            echo ".cct-elevation-{$level} {\n";
            echo "  box-shadow: var(--cct-elevation-{$level});\n";
            echo "  z-index: {$data['z_index']};\n";
            
            if ($settings['animationsEnabled']) {
                echo "  transition: box-shadow var(--cct-animation-duration) var(--cct-animation-easing);\n";
            }
            
            if ($settings['gpuAcceleration']) {
                echo "  transform: translateZ(0);\n";
            }
            
            if ($settings['willChange']) {
                echo "  will-change: box-shadow;\n";
            }
            
            echo "}\n";
        }
        
        // Animações de hover
        if ($settings['animationsEnabled']) {
            echo ".cct-elevation-hover-1:hover { box-shadow: var(--cct-elevation-2); }\n";
            echo ".cct-elevation-hover-2:hover { box-shadow: var(--cct-elevation-4); }\n";
            echo ".cct-elevation-hover-4:hover { box-shadow: var(--cct-elevation-6); }\n";
            echo ".cct-elevation-hover-6:hover { box-shadow: var(--cct-elevation-8); }\n";
            echo ".cct-elevation-hover-8:hover { box-shadow: var(--cct-elevation-12); }\n";
        }
        
        // Otimizações mobile
        if ($settings['mobileOptimization']) {
            echo "@media (max-width: 768px) {\n";
            echo "  .cct-elevation-12, .cct-elevation-16, .cct-elevation-24 {\n";
            echo "    box-shadow: var(--cct-elevation-8);\n";
            echo "  }\n";
            echo "}\n";
        }
        
        // Respeitar movimento reduzido
        if ($settings['reduceMotionRespect']) {
            echo "@media (prefers-reduced-motion: reduce) {\n";
            echo "  .cct-elevation-0, .cct-elevation-1, .cct-elevation-2, .cct-elevation-4,\n";
            echo "  .cct-elevation-6, .cct-elevation-8, .cct-elevation-12, .cct-elevation-16, .cct-elevation-24 {\n";
            echo "    transition: none;\n";
            echo "  }\n";
            echo "}\n";
        }
        
        echo "</style>\n";
    }
    
    /**
     * Calcula sombra com configurações personalizadas
     */
    private function calculate_shadow($base_shadow, $settings) {
        if ($base_shadow === 'none') {
            return 'none';
        }
        
        // Aplicar intensidades personalizadas
        $shadow = $base_shadow;
        
        // Substituir cor
        $shadow = preg_replace('/rgba\(0,\s*0,\s*0,\s*([0-9.]+)\)/', 
            'rgba(' . $this->hex_to_rgb($settings['shadowColor']) . ', $1)', $shadow);
        
        // Aplicar opacidade global
        $shadow = preg_replace_callback('/rgba\([^,]+,\s*[^,]+,\s*[^,]+,\s*([0-9.]+)\)/', 
            function($matches) use ($settings) {
                $original_opacity = floatval($matches[1]);
                $new_opacity = $original_opacity * $settings['shadowOpacity'];
                return str_replace($matches[1], $new_opacity, $matches[0]);
            }, $shadow);
        
        return $shadow;
    }
    
    /**
     * Converte hex para RGB
     */
    private function hex_to_rgb($hex) {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "{$r}, {$g}, {$b}";
    }
    
    /**
     * Gera JavaScript customizado
     */
    public function output_custom_js() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['shadowsEnabled']) {
            return;
        }
        
        echo "<script id='cct-shadows-custom-js'>\n";
        echo "document.addEventListener('DOMContentLoaded', function() {\n";
        echo "  if (typeof CCTShadows !== 'undefined') {\n";
        echo "    CCTShadows.init(" . wp_json_encode($settings) . ");\n";
        echo "  }\n";
        echo "});\n";
        echo "</script>\n";
    }
    
    /**
     * Shortcode para sombras
     */
    public function shadow_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'level' => '2',
            'hover' => 'false',
            'class' => '',
            'style' => '',
        ), $atts, 'cct_shadow');
        
        $level = intval($atts['level']);
        $classes = array('cct-shadow');
        
        // Validar nível
        if (!isset($this->elevation_levels[$level])) {
            $level = 2; // Fallback
        }
        
        $classes[] = "cct-elevation-{$level}";
        
        // Adicionar hover effect
        if ($atts['hover'] === 'true') {
            $classes[] = "cct-elevation-hover-{$level}";
        }
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '"';
        
        if (!empty($atts['style'])) {
            $output .= ' style="' . esc_attr($atts['style']) . '"';
        }
        
        $output .= '>';
        $output .= do_shortcode($content);
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para elevação
     */
    public function elevation_shortcode($atts, $content = '') {
        return $this->shadow_shortcode($atts, $content);
    }
    
    /**
     * Shortcode para card com sombra
     */
    public function card_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'elevation' => '2',
            'hover' => 'true',
            'padding' => '20px',
            'border_radius' => '8px',
            'background' => '#ffffff',
            'class' => '',
        ), $atts, 'cct_card');
        
        $classes = array('cct-card');
        $level = intval($atts['elevation']);
        
        if (!isset($this->elevation_levels[$level])) {
            $level = 2;
        }
        
        $classes[] = "cct-elevation-{$level}";
        
        if ($atts['hover'] === 'true') {
            $classes[] = "cct-elevation-hover-{$level}";
        }
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $style_attrs = array(
            "padding: {$atts['padding']}",
            "border-radius: {$atts['border_radius']}",
            "background: {$atts['background']}"
        );
        
        $output = '<div class="' . implode(' ', $classes) . '" style="' . implode('; ', $style_attrs) . '">';
        $output .= do_shortcode($content);
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * AJAX handler para preview de sombra
     */
    public function ajax_preview_shadow() {
        check_ajax_referer('cct_shadows_nonce', 'nonce');
        
        $level = intval($_POST['level'] ?? 2);
        $settings = $this->sanitize_json_array($_POST['settings'] ?? array());
        
        if (!isset($this->elevation_levels[$level])) {
            wp_die(__('Nível de elevação inválido.', 'cct'));
        }
        
        $shadow_data = $this->elevation_levels[$level];
        $calculated_shadow = $this->calculate_shadow($shadow_data['shadow'], $settings);
        
        $response = array(
            'success' => true,
            'data' => array(
                'level' => $level,
                'shadow' => $calculated_shadow,
                'name' => $shadow_data['name'],
                'description' => $shadow_data['description'],
                'use_cases' => $shadow_data['use_cases']
            )
        );
        
        wp_send_json($response);
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
     * Obtém nível de elevação por nome
     */
    public function get_elevation_level($level) {
        return isset($this->elevation_levels[$level]) ? $this->elevation_levels[$level] : null;
    }
    
    /**
     * Obtém todos os níveis de elevação
     */
    public function get_all_elevation_levels() {
        return $this->elevation_levels;
    }
    
    /**
     * Obtém preset por nome
     */
    public function get_shadow_preset($preset) {
        return isset($this->shadow_presets[$preset]) ? $this->shadow_presets[$preset] : null;
    }
    
    /**
     * Obtém estatísticas do sistema
     */
    public function get_shadow_stats() {
        return array(
            'total_levels' => count($this->elevation_levels),
            'total_presets' => count($this->shadow_presets),
            'active_preset' => get_theme_mod($this->prefix . 'active_preset', 'material'),
            'shadows_enabled' => get_theme_mod($this->prefix . 'shadows_enabled', true),
        );
    }
}