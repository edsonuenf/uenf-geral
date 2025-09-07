<?php
/**
 * Gerenciador de Modo Escuro/Claro
 * 
 * Sistema completo de modo escuro/claro incluindo:
 * - Toggle automático baseado em horário
 * - Preferências do usuário
 * - Detecção de prefers-color-scheme
 * - Transições suaves entre modos
 * - Cores específicas para cada modo
 * - Configurações avançadas
 * - Integração com todos os módulos
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
 * Classe principal do gerenciador de modo escuro/claro
 */
class CCT_Dark_Mode_Manager {
    
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
    private $prefix = 'cct_dark_mode_';
    
    /**
     * Configurações de modo escuro
     * 
     * @var array
     */
    private $dark_mode_settings;
    
    /**
     * Paletas de cores para cada modo
     * 
     * @var array
     */
    private $color_palettes;
    
    /**
     * Configurações de transição
     * 
     * @var array
     */
    private $transition_settings;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->init_dark_mode_settings();
        $this->init_color_palettes();
        $this->init_transition_settings();
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
        add_action('wp_head', array($this, 'output_theme_color_meta'), 1);
        
        // AJAX handlers
        add_action('wp_ajax_cct_toggle_dark_mode', array($this, 'ajax_toggle_dark_mode'));
        add_action('wp_ajax_nopriv_cct_toggle_dark_mode', array($this, 'ajax_toggle_dark_mode'));
        add_action('wp_ajax_cct_save_dark_mode_preference', array($this, 'ajax_save_preference'));
        add_action('wp_ajax_nopriv_cct_save_dark_mode_preference', array($this, 'ajax_save_preference'));
        
        // Shortcodes
        add_shortcode('cct_dark_mode_toggle', array($this, 'dark_mode_toggle_shortcode'));
        add_shortcode('cct_theme_indicator', array($this, 'theme_indicator_shortcode'));
        
        // Body class
        add_filter('body_class', array($this, 'add_body_classes'));
        
        // Admin bar
        add_action('admin_bar_menu', array($this, 'add_admin_bar_toggle'), 100);
    }
    
    /**
     * Inicializa configurações do modo escuro
     */
    private function init_dark_mode_settings() {
        $this->dark_mode_settings = array(
            'enabled' => true,
            'auto_toggle' => true,
            'auto_start_time' => '18:00',
            'auto_end_time' => '06:00',
            'respect_system_preference' => true,
            'remember_user_choice' => true,
            'smooth_transitions' => true,
            'transition_duration' => 0.3,
            'default_mode' => 'auto', // auto, light, dark
            'toggle_position' => 'top-right',
            'show_in_admin_bar' => true,
            'apply_to_admin' => false,
            'custom_toggle_text' => array(
                'light_mode' => __('Modo Claro', 'cct'),
                'dark_mode' => __('Modo Escuro', 'cct'),
                'auto_mode' => __('Automático', 'cct')
            )
        );
    }
    
    /**
     * Inicializa paletas de cores
     */
    private function init_color_palettes() {
        $this->color_palettes = array(
            'light' => array(
                'background' => '#ffffff',
                'surface' => '#f8f9fa',
                'primary' => '#0073aa',
                'secondary' => '#666666',
                'accent' => '#ff6b6b',
                'text' => '#333333',
                'text_secondary' => '#666666',
                'text_muted' => '#999999',
                'border' => '#e0e0e0',
                'shadow' => 'rgba(0, 0, 0, 0.1)',
                'overlay' => 'rgba(255, 255, 255, 0.9)',
                'success' => '#28a745',
                'warning' => '#ffc107',
                'error' => '#dc3545',
                'info' => '#17a2b8'
            ),
            'dark' => array(
                'background' => '#1a1a1a',
                'surface' => '#2a2a2a',
                'primary' => '#4a9eff',
                'secondary' => '#b0b0b0',
                'accent' => '#ff8a8a',
                'text' => '#ffffff',
                'text_secondary' => '#cccccc',
                'text_muted' => '#888888',
                'border' => '#404040',
                'shadow' => 'rgba(0, 0, 0, 0.3)',
                'overlay' => 'rgba(0, 0, 0, 0.9)',
                'success' => '#4caf50',
                'warning' => '#ff9800',
                'error' => '#f44336',
                'info' => '#2196f3'
            )
        );
    }
    
    /**
     * Inicializa configurações de transição
     */
    private function init_transition_settings() {
        $this->transition_settings = array(
            'duration' => 0.3,
            'easing' => 'ease-in-out',
            'properties' => array(
                'background-color',
                'color',
                'border-color',
                'box-shadow'
            ),
            'exclude_elements' => array(
                'img',
                'video',
                'iframe',
                '.no-transition'
            )
        );
    }
    
    /**
     * Registra configurações no Customizer
     */
    public function register_customizer() {
        $this->add_dark_mode_panel();
        $this->add_dark_mode_sections();
        $this->add_dark_mode_settings();
        $this->add_dark_mode_controls();
    }
    
    /**
     * Adiciona painel de modo escuro
     */
    private function add_dark_mode_panel() {
        $this->wp_customize->add_panel($this->prefix . 'panel', array(
            'title' => __('Modo Escuro/Claro', 'cct'),
            'description' => __('Configurações completas para modo escuro e claro com toggle automático e preferências do usuário.', 'cct'),
            'priority' => 200,
            'capability' => 'edit_theme_options',
        ));
    }
    
    /**
     * Adiciona seções de modo escuro
     */
    private function add_dark_mode_sections() {
        // Seção de configurações gerais
        $this->wp_customize->add_section($this->prefix . 'general', array(
            'title' => __('Configurações Gerais', 'cct'),
            'description' => __('Configurações principais do modo escuro/claro.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 10,
        ));
        
        // Seção de toggle automático
        $this->wp_customize->add_section($this->prefix . 'auto_toggle', array(
            'title' => __('Toggle Automático', 'cct'),
            'description' => __('Configurações para alternância automática baseada em horário.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 20,
        ));
        
        // Seção de cores modo claro
        $this->wp_customize->add_section($this->prefix . 'light_colors', array(
            'title' => __('Cores Modo Claro', 'cct'),
            'description' => __('Paleta de cores para o modo claro.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 30,
        ));
        
        // Seção de cores modo escuro
        $this->wp_customize->add_section($this->prefix . 'dark_colors', array(
            'title' => __('Cores Modo Escuro', 'cct'),
            'description' => __('Paleta de cores para o modo escuro.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 40,
        ));
        
        // Seção de transições
        $this->wp_customize->add_section($this->prefix . 'transitions', array(
            'title' => __('Transições', 'cct'),
            'description' => __('Configurações de animações e transições entre modos.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 50,
        ));
        
        // Seção de interface
        $this->wp_customize->add_section($this->prefix . 'interface', array(
            'title' => __('Interface', 'cct'),
            'description' => __('Configurações de exibição e posicionamento do toggle.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 60,
        ));
    }
    
    /**
     * Adiciona configurações de modo escuro
     */
    private function add_dark_mode_settings() {
        // Configurações gerais
        $this->add_setting('enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('default_mode', array(
            'default' => 'auto',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('respect_system_preference', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('remember_user_choice', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Toggle automático
        $this->add_setting('auto_toggle', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('auto_start_time', array(
            'default' => '18:00',
            'sanitize_callback' => array($this, 'sanitize_time'),
        ));
        
        $this->add_setting('auto_end_time', array(
            'default' => '06:00',
            'sanitize_callback' => array($this, 'sanitize_time'),
        ));
        
        // Cores modo claro
        foreach ($this->color_palettes['light'] as $color_key => $color_value) {
            $this->add_setting('light_' . $color_key, array(
                'default' => $color_value,
                'sanitize_callback' => 'sanitize_hex_color',
            ));
        }
        
        // Cores modo escuro
        foreach ($this->color_palettes['dark'] as $color_key => $color_value) {
            $this->add_setting('dark_' . $color_key, array(
                'default' => $color_value,
                'sanitize_callback' => 'sanitize_hex_color',
            ));
        }
        
        // Transições
        $this->add_setting('smooth_transitions', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('transition_duration', array(
            'default' => 0.3,
            'sanitize_callback' => array($this, 'sanitize_float'),
        ));
        
        $this->add_setting('transition_easing', array(
            'default' => 'ease-in-out',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        // Interface
        $this->add_setting('toggle_position', array(
            'default' => 'top-right',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('show_in_admin_bar', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('apply_to_admin', array(
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
    }
    
    /**
     * Adiciona controles de modo escuro
     */
    private function add_dark_mode_controls() {
        // Controles gerais
        $this->add_control('enabled', array(
            'label' => __('Ativar Modo Escuro/Claro', 'cct'),
            'description' => __('Ativa ou desativa o sistema de modo escuro/claro.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('default_mode', array(
            'label' => __('Modo Padrão', 'cct'),
            'description' => __('Modo inicial quando o usuário visita o site.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'select',
            'choices' => array(
                'auto' => __('Automático (baseado no horário)', 'cct'),
                'light' => __('Modo Claro', 'cct'),
                'dark' => __('Modo Escuro', 'cct'),
                'system' => __('Preferência do Sistema', 'cct')
            ),
        ));
        
        $this->add_control('respect_system_preference', array(
            'label' => __('Respeitar Preferência do Sistema', 'cct'),
            'description' => __('Detecta automaticamente a preferência de modo escuro do sistema operacional.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('remember_user_choice', array(
            'label' => __('Lembrar Escolha do Usuário', 'cct'),
            'description' => __('Salva a preferência do usuário no localStorage.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        // Controles de toggle automático
        $this->add_control('auto_toggle', array(
            'label' => __('Toggle Automático', 'cct'),
            'description' => __('Alterna automaticamente entre modos baseado no horário.', 'cct'),
            'section' => $this->prefix . 'auto_toggle',
            'type' => 'checkbox',
        ));
        
        $this->add_control('auto_start_time', array(
            'label' => __('Horário de Início (Modo Escuro)', 'cct'),
            'description' => __('Horário para ativar o modo escuro automaticamente.', 'cct'),
            'section' => $this->prefix . 'auto_toggle',
            'type' => 'time',
        ));
        
        $this->add_control('auto_end_time', array(
            'label' => __('Horário de Fim (Modo Escuro)', 'cct'),
            'description' => __('Horário para desativar o modo escuro automaticamente.', 'cct'),
            'section' => $this->prefix . 'auto_toggle',
            'type' => 'time',
        ));
        
        // Controles de cores modo claro
        foreach ($this->color_palettes['light'] as $color_key => $color_value) {
            $this->add_control('light_' . $color_key, array(
                'label' => $this->get_color_label($color_key),
                'description' => $this->get_color_description($color_key, 'light'),
                'section' => $this->prefix . 'light_colors',
                'type' => 'color',
            ));
        }
        
        // Controles de cores modo escuro
        foreach ($this->color_palettes['dark'] as $color_key => $color_value) {
            $this->add_control('dark_' . $color_key, array(
                'label' => $this->get_color_label($color_key),
                'description' => $this->get_color_description($color_key, 'dark'),
                'section' => $this->prefix . 'dark_colors',
                'type' => 'color',
            ));
        }
        
        // Controles de transições
        $this->add_control('smooth_transitions', array(
            'label' => __('Transições Suaves', 'cct'),
            'description' => __('Ativa animações suaves ao alternar entre modos.', 'cct'),
            'section' => $this->prefix . 'transitions',
            'type' => 'checkbox',
        ));
        
        $this->add_control('transition_duration', array(
            'label' => __('Duração da Transição', 'cct'),
            'description' => __('Duração em segundos das transições (0.1 - 2.0).', 'cct'),
            'section' => $this->prefix . 'transitions',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0.1,
                'max' => 2.0,
                'step' => 0.1,
            ),
        ));
        
        $this->add_control('transition_easing', array(
            'label' => __('Curva de Animação', 'cct'),
            'description' => __('Tipo de curva para as transições.', 'cct'),
            'section' => $this->prefix . 'transitions',
            'type' => 'select',
            'choices' => array(
                'ease' => 'Ease',
                'ease-in' => 'Ease In',
                'ease-out' => 'Ease Out',
                'ease-in-out' => 'Ease In Out',
                'linear' => 'Linear',
                'cubic-bezier(0.25, 0.8, 0.25, 1)' => 'Material Design'
            ),
        ));
        
        // Controles de interface
        $this->add_control('toggle_position', array(
            'label' => __('Posição do Toggle', 'cct'),
            'description' => __('Posição do botão de alternância na tela.', 'cct'),
            'section' => $this->prefix . 'interface',
            'type' => 'select',
            'choices' => array(
                'top-left' => __('Superior Esquerda', 'cct'),
                'top-right' => __('Superior Direita', 'cct'),
                'bottom-left' => __('Inferior Esquerda', 'cct'),
                'bottom-right' => __('Inferior Direita', 'cct'),
                'header' => __('No Header', 'cct'),
                'footer' => __('No Footer', 'cct'),
                'custom' => __('Posição Customizada', 'cct')
            ),
        ));
        
        $this->add_control('show_in_admin_bar', array(
            'label' => __('Mostrar na Barra de Admin', 'cct'),
            'description' => __('Adiciona toggle na barra de administração do WordPress.', 'cct'),
            'section' => $this->prefix . 'interface',
            'type' => 'checkbox',
        ));
        
        $this->add_control('apply_to_admin', array(
            'label' => __('Aplicar ao Admin', 'cct'),
            'description' => __('Aplica o modo escuro também ao painel administrativo.', 'cct'),
            'section' => $this->prefix . 'interface',
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
     * Obtém label para cores
     */
    private function get_color_label($color_key) {
        $labels = array(
            'background' => __('Cor de Fundo', 'cct'),
            'surface' => __('Cor de Superfície', 'cct'),
            'primary' => __('Cor Primária', 'cct'),
            'secondary' => __('Cor Secundária', 'cct'),
            'accent' => __('Cor de Destaque', 'cct'),
            'text' => __('Cor do Texto', 'cct'),
            'text_secondary' => __('Cor do Texto Secundário', 'cct'),
            'text_muted' => __('Cor do Texto Esmaecido', 'cct'),
            'border' => __('Cor da Borda', 'cct'),
            'shadow' => __('Cor da Sombra', 'cct'),
            'overlay' => __('Cor do Overlay', 'cct'),
            'success' => __('Cor de Sucesso', 'cct'),
            'warning' => __('Cor de Aviso', 'cct'),
            'error' => __('Cor de Erro', 'cct'),
            'info' => __('Cor de Informação', 'cct'),
        );
        
        return isset($labels[$color_key]) ? $labels[$color_key] : ucfirst(str_replace('_', ' ', $color_key));
    }
    
    /**
     * Obtém descrição para cores
     */
    private function get_color_description($color_key, $mode) {
        $mode_text = $mode === 'light' ? __('modo claro', 'cct') : __('modo escuro', 'cct');
        
        $descriptions = array(
            'background' => sprintf(__('Cor de fundo principal para %s.', 'cct'), $mode_text),
            'surface' => sprintf(__('Cor de superfícies elevadas para %s.', 'cct'), $mode_text),
            'primary' => sprintf(__('Cor primária da marca para %s.', 'cct'), $mode_text),
            'secondary' => sprintf(__('Cor secundária para %s.', 'cct'), $mode_text),
            'accent' => sprintf(__('Cor de destaque para %s.', 'cct'), $mode_text),
            'text' => sprintf(__('Cor principal do texto para %s.', 'cct'), $mode_text),
            'text_secondary' => sprintf(__('Cor do texto secundário para %s.', 'cct'), $mode_text),
            'text_muted' => sprintf(__('Cor do texto esmaecido para %s.', 'cct'), $mode_text),
            'border' => sprintf(__('Cor das bordas para %s.', 'cct'), $mode_text),
            'shadow' => sprintf(__('Cor das sombras para %s.', 'cct'), $mode_text),
            'overlay' => sprintf(__('Cor dos overlays para %s.', 'cct'), $mode_text),
            'success' => sprintf(__('Cor de sucesso para %s.', 'cct'), $mode_text),
            'warning' => sprintf(__('Cor de aviso para %s.', 'cct'), $mode_text),
            'error' => sprintf(__('Cor de erro para %s.', 'cct'), $mode_text),
            'info' => sprintf(__('Cor de informação para %s.', 'cct'), $mode_text),
        );
        
        return isset($descriptions[$color_key]) ? $descriptions[$color_key] : '';
    }
    
    /**
     * Sanitiza valores de tempo
     */
    public function sanitize_time($time) {
        if (preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
            return $time;
        }
        return '18:00';
    }
    
    /**
     * Sanitiza valores float
     */
    public function sanitize_float($value) {
        $value = floatval($value);
        return max(0.1, min(2.0, $value));
    }
    
    /**
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        // CSS do modo escuro
        wp_enqueue_style(
            'cct-dark-mode',
            get_template_directory_uri() . '/css/cct-dark-mode.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript do modo escuro
        wp_enqueue_script(
            'cct-dark-mode',
            get_template_directory_uri() . '/js/cct-dark-mode.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localização do script
        wp_localize_script('cct-dark-mode', 'cctDarkMode', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_dark_mode_nonce'),
            'settings' => $this->get_frontend_settings(),
            'strings' => array(
                'lightMode' => __('Modo Claro', 'cct'),
                'darkMode' => __('Modo Escuro', 'cct'),
                'autoMode' => __('Automático', 'cct'),
                'toggleTooltip' => __('Alternar modo escuro/claro', 'cct')
            )
        ));
    }
    
    /**
     * Obtém configurações para o frontend
     */
    private function get_frontend_settings() {
        return array(
            'enabled' => get_theme_mod($this->prefix . 'enabled', true),
            'defaultMode' => get_theme_mod($this->prefix . 'default_mode', 'auto'),
            'autoToggle' => get_theme_mod($this->prefix . 'auto_toggle', true),
            'autoStartTime' => get_theme_mod($this->prefix . 'auto_start_time', '18:00'),
            'autoEndTime' => get_theme_mod($this->prefix . 'auto_end_time', '06:00'),
            'respectSystemPreference' => get_theme_mod($this->prefix . 'respect_system_preference', true),
            'rememberUserChoice' => get_theme_mod($this->prefix . 'remember_user_choice', true),
            'smoothTransitions' => get_theme_mod($this->prefix . 'smooth_transitions', true),
            'transitionDuration' => get_theme_mod($this->prefix . 'transition_duration', 0.3),
            'transitionEasing' => get_theme_mod($this->prefix . 'transition_easing', 'ease-in-out'),
            'togglePosition' => get_theme_mod($this->prefix . 'toggle_position', 'top-right'),
            'lightColors' => $this->get_active_colors('light'),
            'darkColors' => $this->get_active_colors('dark')
        );
    }
    
    /**
     * Obtém cores ativas para um modo
     */
    private function get_active_colors($mode) {
        $colors = array();
        
        foreach ($this->color_palettes[$mode] as $color_key => $default_value) {
            $colors[$color_key] = get_theme_mod($this->prefix . $mode . '_' . $color_key, $default_value);
        }
        
        return $colors;
    }
    
    /**
     * Gera CSS customizado
     */
    public function output_custom_css() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['enabled']) {
            return;
        }
        
        echo "<style id='cct-dark-mode-custom-css'>\n";
        
        // Variáveis CSS para modo claro
        echo ":root {\n";
        foreach ($settings['lightColors'] as $color_key => $color_value) {
            echo "  --cct-light-{$color_key}: {$color_value};\n";
        }
        echo "}\n";
        
        // Variáveis CSS para modo escuro
        echo "[data-theme='dark'] {\n";
        foreach ($settings['darkColors'] as $color_key => $color_value) {
            echo "  --cct-color-{$color_key}: {$color_value};\n";
        }
        echo "}\n";
        
        // Configurações de transição
        if ($settings['smoothTransitions']) {
            echo "* {\n";
            echo "  transition: background-color {$settings['transitionDuration']}s {$settings['transitionEasing']}, ";
            echo "color {$settings['transitionDuration']}s {$settings['transitionEasing']}, ";
            echo "border-color {$settings['transitionDuration']}s {$settings['transitionEasing']}, ";
            echo "box-shadow {$settings['transitionDuration']}s {$settings['transitionEasing']};\n";
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
        
        echo "<script id='cct-dark-mode-custom-js'>\n";
        echo "document.addEventListener('DOMContentLoaded', function() {\n";
        echo "  if (typeof CCTDarkMode !== 'undefined') {\n";
        echo "    CCTDarkMode.init(" . wp_json_encode($settings) . ");\n";
        echo "  }\n";
        echo "});\n";
        echo "</script>\n";
    }
    
    /**
     * Gera meta tag theme-color
     */
    public function output_theme_color_meta() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['enabled']) {
            return;
        }
        
        $light_bg = $settings['lightColors']['background'];
        $dark_bg = $settings['darkColors']['background'];
        
        echo "<meta name='theme-color' content='{$light_bg}' media='(prefers-color-scheme: light)'>\n";
        echo "<meta name='theme-color' content='{$dark_bg}' media='(prefers-color-scheme: dark)'>\n";
    }
    
    /**
     * Shortcode para toggle de modo escuro
     */
    public function dark_mode_toggle_shortcode($atts) {
        $atts = shortcode_atts(array(
            'style' => 'button', // button, switch, icon
            'size' => 'medium', // small, medium, large
            'position' => 'inline', // inline, fixed
            'show_text' => 'true',
            'class' => ''
        ), $atts, 'cct_dark_mode_toggle');
        
        $classes = array('cct-dark-mode-toggle', 'cct-toggle-' . $atts['style'], 'cct-size-' . $atts['size']);
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        if ($atts['position'] === 'fixed') {
            $classes[] = 'cct-toggle-fixed';
        }
        
        $output = '<div class="' . implode(' ', $classes) . '" data-cct-dark-mode-toggle>';
        
        if ($atts['style'] === 'button') {
            $output .= '<button type="button" class="cct-toggle-btn" aria-label="' . esc_attr__('Alternar modo escuro/claro', 'cct') . '">';
            $output .= '<span class="cct-toggle-icon"></span>';
            if ($atts['show_text'] === 'true') {
                $output .= '<span class="cct-toggle-text">' . __('Modo Escuro', 'cct') . '</span>';
            }
            $output .= '</button>';
        } elseif ($atts['style'] === 'switch') {
            $output .= '<label class="cct-toggle-switch">';
            $output .= '<input type="checkbox" class="cct-toggle-input" aria-label="' . esc_attr__('Alternar modo escuro/claro', 'cct') . '">';
            $output .= '<span class="cct-toggle-slider"></span>';
            if ($atts['show_text'] === 'true') {
                $output .= '<span class="cct-toggle-text">' . __('Modo Escuro', 'cct') . '</span>';
            }
            $output .= '</label>';
        } else {
            $output .= '<button type="button" class="cct-toggle-icon-btn" aria-label="' . esc_attr__('Alternar modo escuro/claro', 'cct') . '">';
            $output .= '<span class="cct-toggle-icon"></span>';
            $output .= '</button>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para indicador de tema
     */
    public function theme_indicator_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show_icon' => 'true',
            'show_text' => 'true',
            'class' => ''
        ), $atts, 'cct_theme_indicator');
        
        $classes = array('cct-theme-indicator');
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '" data-cct-theme-indicator>';
        
        if ($atts['show_icon'] === 'true') {
            $output .= '<span class="cct-theme-icon"></span>';
        }
        
        if ($atts['show_text'] === 'true') {
            $output .= '<span class="cct-theme-text">' . __('Modo Claro', 'cct') . '</span>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Adiciona classes ao body
     */
    public function add_body_classes($classes) {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['enabled']) {
            return $classes;
        }
        
        $classes[] = 'cct-dark-mode-enabled';
        
        if ($settings['smoothTransitions']) {
            $classes[] = 'cct-smooth-transitions';
        }
        
        return $classes;
    }
    
    /**
     * Adiciona toggle na barra de admin
     */
    public function add_admin_bar_toggle($wp_admin_bar) {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['enabled'] || !$settings['showInAdminBar'] || !is_user_logged_in()) {
            return;
        }
        
        $wp_admin_bar->add_node(array(
            'id' => 'cct-dark-mode-toggle',
            'title' => '<span class="cct-admin-bar-toggle" data-cct-dark-mode-toggle>' . __('🌙 Modo Escuro', 'cct') . '</span>',
            'href' => '#',
            'meta' => array(
                'class' => 'cct-admin-bar-dark-mode'
            )
        ));
    }
    
    /**
     * AJAX handler para toggle de modo escuro
     */
    public function ajax_toggle_dark_mode() {
        check_ajax_referer('cct_dark_mode_nonce', 'nonce');
        
        $mode = sanitize_text_field($_POST['mode'] ?? 'auto');
        
        // Salvar preferência se habilitado
        if (get_theme_mod($this->prefix . 'remember_user_choice', true)) {
            setcookie('cct_dark_mode_preference', $mode, time() + (365 * 24 * 60 * 60), '/');
        }
        
        wp_send_json_success(array(
            'mode' => $mode,
            'message' => sprintf(__('Modo alterado para: %s', 'cct'), $mode)
        ));
    }
    
    /**
     * AJAX handler para salvar preferência
     */
    public function ajax_save_preference() {
        check_ajax_referer('cct_dark_mode_nonce', 'nonce');
        
        $preference = sanitize_text_field($_POST['preference'] ?? 'auto');
        
        setcookie('cct_dark_mode_preference', $preference, time() + (365 * 24 * 60 * 60), '/');
        
        wp_send_json_success(array(
            'preference' => $preference,
            'message' => __('Preferência salva com sucesso!', 'cct')
        ));
    }
    
    /**
     * Obtém estatísticas do modo escuro
     */
    public function get_dark_mode_stats() {
        return array(
            'enabled' => get_theme_mod($this->prefix . 'enabled', true),
            'default_mode' => get_theme_mod($this->prefix . 'default_mode', 'auto'),
            'auto_toggle' => get_theme_mod($this->prefix . 'auto_toggle', true),
            'smooth_transitions' => get_theme_mod($this->prefix . 'smooth_transitions', true),
            'total_colors' => count($this->color_palettes['light']) + count($this->color_palettes['dark']),
            'light_colors' => count($this->color_palettes['light']),
            'dark_colors' => count($this->color_palettes['dark'])
        );
    }
}