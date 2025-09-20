<?php
/**
 * Gerenciador de Responsive Breakpoints
 * 
 * Sistema completo de pontos de quebra responsivos incluindo:
 * - Gerenciador de breakpoints customizáveis
 * - Preview multi-dispositivo
 * - Breakpoints nomeados personalizados
 * - Configurações por breakpoint
 * - Detecção de dispositivo automática
 * - Media queries dinâmicas
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
 * Classe principal do gerenciador de breakpoints responsivos
 */
class CCT_Responsive_Breakpoints_Manager {
    
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
    private $prefix = 'cct_breakpoints_';
    
    /**
     * Breakpoints padrão
     * 
     * @var array
     */
    private $default_breakpoints;
    
    /**
     * Configurações de dispositivos
     * 
     * @var array
     */
    private $device_settings;
    
    /**
     * Templates de breakpoints
     * 
     * @var array
     */
    private $breakpoint_templates;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->init_default_breakpoints();
        $this->init_device_settings();
        $this->init_breakpoint_templates();
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
        
        // AJAX handlers
        add_action('wp_ajax_cct_save_breakpoint', array($this, 'ajax_save_breakpoint'));
        add_action('wp_ajax_cct_delete_breakpoint', array($this, 'ajax_delete_breakpoint'));
        add_action('wp_ajax_cct_reorder_breakpoints', array($this, 'ajax_reorder_breakpoints'));
        add_action('wp_ajax_cct_preview_breakpoint', array($this, 'ajax_preview_breakpoint'));
        add_action('wp_ajax_cct_export_breakpoints', array($this, 'ajax_export_breakpoints'));
        add_action('wp_ajax_cct_import_breakpoints', array($this, 'ajax_import_breakpoints'));
        
        // Shortcodes
        add_shortcode('cct_breakpoint_info', array($this, 'breakpoint_info_shortcode'));
        add_shortcode('cct_device_detector', array($this, 'device_detector_shortcode'));
        add_shortcode('cct_responsive_content', array($this, 'responsive_content_shortcode'));
        
        // Body class
        add_filter('body_class', array($this, 'add_body_classes'));
        
        // Customizer preview
        add_action('customize_preview_init', array($this, 'customize_preview_init'));
    }
    
    /**
     * Inicializa breakpoints padrão
     */
    private function init_default_breakpoints() {
        $this->default_breakpoints = array(
            'xs' => array(
                'name' => __('Extra Small', 'cct'),
                'label' => __('Smartphones', 'cct'),
                'min_width' => 0,
                'max_width' => 575,
                'enabled' => true,
                'icon' => '📱',
                'description' => __('Smartphones em modo retrato', 'cct'),
                'typical_devices' => array('iPhone SE', 'Galaxy S20', 'Pixel 5'),
                'container_width' => '100%',
                'gutter' => 16,
                'columns' => 1
            ),
            'sm' => array(
                'name' => __('Small', 'cct'),
                'label' => __('Smartphones Landscape', 'cct'),
                'min_width' => 576,
                'max_width' => 767,
                'enabled' => true,
                'icon' => '📱',
                'description' => __('Smartphones em modo paisagem', 'cct'),
                'typical_devices' => array('iPhone 12 Pro', 'Galaxy S21', 'Pixel 6'),
                'container_width' => '540px',
                'gutter' => 20,
                'columns' => 2
            ),
            'md' => array(
                'name' => __('Medium', 'cct'),
                'label' => __('Tablets', 'cct'),
                'min_width' => 768,
                'max_width' => 991,
                'enabled' => true,
                'icon' => '📱',
                'description' => __('Tablets e dispositivos médios', 'cct'),
                'typical_devices' => array('iPad', 'Galaxy Tab', 'Surface Go'),
                'container_width' => '720px',
                'gutter' => 24,
                'columns' => 3
            ),
            'lg' => array(
                'name' => __('Large', 'cct'),
                'label' => __('Desktops', 'cct'),
                'min_width' => 992,
                'max_width' => 1199,
                'enabled' => true,
                'icon' => '💻',
                'description' => __('Desktops e laptops', 'cct'),
                'typical_devices' => array('MacBook Air', 'ThinkPad', 'iMac 21"'),
                'container_width' => '960px',
                'gutter' => 30,
                'columns' => 4
            ),
            'xl' => array(
                'name' => __('Extra Large', 'cct'),
                'label' => __('Desktops Grandes', 'cct'),
                'min_width' => 1200,
                'max_width' => 1399,
                'enabled' => true,
                'icon' => '🖥️',
                'description' => __('Desktops grandes e monitores', 'cct'),
                'typical_devices' => array('iMac 27"', 'Monitor 24"', 'Ultrawide'),
                'container_width' => '1140px',
                'gutter' => 32,
                'columns' => 6
            ),
            'xxl' => array(
                'name' => __('Extra Extra Large', 'cct'),
                'label' => __('Monitores 4K', 'cct'),
                'min_width' => 1400,
                'max_width' => null,
                'enabled' => true,
                'icon' => '🖥️',
                'description' => __('Monitores 4K e ultra-wide', 'cct'),
                'typical_devices' => array('Monitor 32"', '4K Display', 'Ultrawide 34"'),
                'container_width' => '1320px',
                'gutter' => 40,
                'columns' => 12
            )
        );
    }
    
    /**
     * Inicializa configurações de dispositivos
     */
    private function init_device_settings() {
        $this->device_settings = array(
            'detection_enabled' => true,
            'auto_adjust_layout' => true,
            'touch_optimizations' => true,
            'retina_support' => true,
            'orientation_detection' => true,
            'viewport_meta_enabled' => true,
            'container_behavior' => 'fluid', // fluid, fixed, hybrid
            'grid_system' => 'flexbox', // flexbox, css-grid, bootstrap
            'debug_mode' => false,
            'performance_mode' => 'balanced' // fast, balanced, quality
        );
    }
    
    /**
     * Inicializa templates de breakpoints
     */
    private function init_breakpoint_templates() {
        $this->breakpoint_templates = array(
            'bootstrap' => array(
                'name' => 'Bootstrap 5',
                'description' => __('Breakpoints padrão do Bootstrap 5', 'cct'),
                'breakpoints' => array(
                    'xs' => array('min' => 0, 'max' => 575),
                    'sm' => array('min' => 576, 'max' => 767),
                    'md' => array('min' => 768, 'max' => 991),
                    'lg' => array('min' => 992, 'max' => 1199),
                    'xl' => array('min' => 1200, 'max' => 1399),
                    'xxl' => array('min' => 1400, 'max' => null)
                )
            ),
            'tailwind' => array(
                'name' => 'Tailwind CSS',
                'description' => __('Breakpoints padrão do Tailwind CSS', 'cct'),
                'breakpoints' => array(
                    'sm' => array('min' => 640, 'max' => 767),
                    'md' => array('min' => 768, 'max' => 1023),
                    'lg' => array('min' => 1024, 'max' => 1279),
                    'xl' => array('min' => 1280, 'max' => 1535),
                    '2xl' => array('min' => 1536, 'max' => null)
                )
            ),
            'material' => array(
                'name' => 'Material Design',
                'description' => __('Breakpoints do Material Design', 'cct'),
                'breakpoints' => array(
                    'xs' => array('min' => 0, 'max' => 599),
                    'sm' => array('min' => 600, 'max' => 959),
                    'md' => array('min' => 960, 'max' => 1279),
                    'lg' => array('min' => 1280, 'max' => 1919),
                    'xl' => array('min' => 1920, 'max' => null)
                )
            ),
            'custom' => array(
                'name' => __('Personalizado', 'cct'),
                'description' => __('Breakpoints totalmente personalizados', 'cct'),
                'breakpoints' => array()
            )
        );
    }
    
    /**
     * Registra configurações no Customizer
     */
    public function register_customizer() {
        $this->add_breakpoints_panel();
        $this->add_breakpoints_sections();
        $this->add_breakpoints_settings();
        $this->add_breakpoints_controls();
    }
    
    /**
     * Adiciona painel de breakpoints
     */
    private function add_breakpoints_panel() {
        $this->wp_customize->add_panel($this->prefix . 'panel', array(
            'title' => __('Responsive Breakpoints', 'cct'),
            'description' => __('Gerenciador completo de pontos de quebra responsivos com preview multi-dispositivo e configurações avançadas.', 'cct'),
            'priority' => 210,
            'capability' => 'edit_theme_options',
        ));
    }
    
    /**
     * Adiciona seções de breakpoints
     */
    private function add_breakpoints_sections() {
        // Seção de configurações gerais
        $this->wp_customize->add_section($this->prefix . 'general', array(
            'title' => __('Configurações Gerais', 'cct'),
            'description' => __('Configurações principais do sistema responsivo.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 10,
        ));
        
        // Seção de gerenciamento de breakpoints
        $this->wp_customize->add_section($this->prefix . 'management', array(
            'title' => __('Gerenciar Breakpoints', 'cct'),
            'description' => __('Adicionar, editar e organizar pontos de quebra.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 20,
        ));
        
        // Seção de templates
        $this->wp_customize->add_section($this->prefix . 'templates', array(
            'title' => __('Templates de Breakpoints', 'cct'),
            'description' => __('Templates predefinidos de frameworks populares.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 30,
        ));
        
        // Seção de preview
        $this->wp_customize->add_section($this->prefix . 'preview', array(
            'title' => __('Preview Multi-dispositivo', 'cct'),
            'description' => __('Visualização simultânea em diferentes dispositivos.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 40,
        ));
        
        // Seção de configurações por breakpoint
        foreach ($this->get_active_breakpoints() as $bp_key => $breakpoint) {
            $this->wp_customize->add_section($this->prefix . 'bp_' . $bp_key, array(
                'title' => sprintf(__('Breakpoint: %s', 'cct'), $breakpoint['name']),
                'description' => sprintf(__('Configurações específicas para %s (%dpx - %s)', 'cct'), 
                    $breakpoint['label'], 
                    $breakpoint['min_width'],
                    $breakpoint['max_width'] ? $breakpoint['max_width'] . 'px' : '∞'
                ),
                'panel' => $this->prefix . 'panel',
                'priority' => 50 + array_search($bp_key, array_keys($this->get_active_breakpoints())),
            ));
        }
    }
    
    /**
     * Adiciona configurações de breakpoints
     */
    private function add_breakpoints_settings() {
        // Configurações gerais
        $this->add_setting('enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('template', array(
            'default' => 'bootstrap',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('detection_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('auto_adjust_layout', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('container_behavior', array(
            'default' => 'fluid',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('grid_system', array(
            'default' => 'flexbox',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('debug_mode', array(
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Breakpoints customizados
        $this->add_setting('custom_breakpoints', array(
            'default' => wp_json_encode($this->default_breakpoints),
            'sanitize_callback' => array($this, 'sanitize_breakpoints_json'),
        ));
        
        // Configurações por breakpoint
        foreach ($this->get_active_breakpoints() as $bp_key => $breakpoint) {
            $this->add_setting('bp_' . $bp_key . '_enabled', array(
                'default' => $breakpoint['enabled'],
                'sanitize_callback' => 'rest_sanitize_boolean',
            ));
            
            $this->add_setting('bp_' . $bp_key . '_container_width', array(
                'default' => $breakpoint['container_width'],
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $this->add_setting('bp_' . $bp_key . '_gutter', array(
                'default' => $breakpoint['gutter'],
                'sanitize_callback' => 'absint',
            ));
            
            $this->add_setting('bp_' . $bp_key . '_columns', array(
                'default' => $breakpoint['columns'],
                'sanitize_callback' => 'absint',
            ));
        }
    }
    
    /**
     * Adiciona controles de breakpoints
     */
    private function add_breakpoints_controls() {
        // Controles gerais
        $this->add_control('enabled', array(
            'label' => __('Ativar Sistema Responsivo', 'cct'),
            'description' => __('Ativa ou desativa o sistema de breakpoints responsivos.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('template', array(
            'label' => __('Template de Breakpoints', 'cct'),
            'description' => __('Escolha um template predefinido ou use configurações personalizadas.', 'cct'),
            'section' => $this->prefix . 'templates',
            'type' => 'select',
            'choices' => $this->get_template_choices(),
        ));
        
        $this->add_control('detection_enabled', array(
            'label' => __('Detecção de Dispositivo', 'cct'),
            'description' => __('Detecta automaticamente o tipo de dispositivo do usuário.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('auto_adjust_layout', array(
            'label' => __('Ajuste Automático de Layout', 'cct'),
            'description' => __('Ajusta automaticamente o layout baseado no breakpoint ativo.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('container_behavior', array(
            'label' => __('Comportamento do Container', 'cct'),
            'description' => __('Como os containers se comportam em diferentes breakpoints.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'select',
            'choices' => array(
                'fluid' => __('Fluido (100% da largura)', 'cct'),
                'fixed' => __('Fixo (larguras específicas)', 'cct'),
                'hybrid' => __('Híbrido (fluido em mobile, fixo em desktop)', 'cct')
            ),
        ));
        
        $this->add_control('grid_system', array(
            'label' => __('Sistema de Grid', 'cct'),
            'description' => __('Tecnologia CSS usada para o sistema de grid.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'select',
            'choices' => array(
                'flexbox' => __('Flexbox (recomendado)', 'cct'),
                'css-grid' => __('CSS Grid (moderno)', 'cct'),
                'bootstrap' => __('Bootstrap Grid', 'cct')
            ),
        ));
        
        $this->add_control('debug_mode', array(
            'label' => __('Modo Debug', 'cct'),
            'description' => __('Mostra informações de debug sobre breakpoints ativos.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        // Controle personalizado para gerenciamento de breakpoints (usando controle padrão temporariamente)
        $this->wp_customize->add_control(
            $this->prefix . 'manager',
            array(
                'label' => __('Gerenciador de Breakpoints', 'cct'),
                'description' => __('Adicione, edite e organize seus breakpoints personalizados.', 'cct'),
                'section' => $this->prefix . 'management',
                'settings' => $this->prefix . 'custom_breakpoints',
                'type' => 'select',
                'choices' => array(
                    'manager' => __('Gerenciador será implementado em versão futura', 'cct')
                ),
                'breakpoints' => $this->get_active_breakpoints(),
                'templates' => $this->breakpoint_templates
            )
        );
        
        // Controles por breakpoint
        foreach ($this->get_active_breakpoints() as $bp_key => $breakpoint) {
            $this->add_control('bp_' . $bp_key . '_enabled', array(
                'label' => sprintf(__('Ativar %s', 'cct'), $breakpoint['name']),
                'description' => sprintf(__('Ativa ou desativa o breakpoint %s.', 'cct'), $breakpoint['label']),
                'section' => $this->prefix . 'bp_' . $bp_key,
                'type' => 'checkbox',
            ));
            
            $this->add_control('bp_' . $bp_key . '_container_width', array(
                'label' => __('Largura do Container', 'cct'),
                'description' => __('Largura máxima do container (ex: 1200px, 100%, auto).', 'cct'),
                'section' => $this->prefix . 'bp_' . $bp_key,
                'type' => 'text',
            ));
            
            $this->add_control('bp_' . $bp_key . '_gutter', array(
                'label' => __('Espaçamento (Gutter)', 'cct'),
                'description' => __('Espaçamento entre colunas em pixels.', 'cct'),
                'section' => $this->prefix . 'bp_' . $bp_key,
                'type' => 'range',
                'input_attrs' => array(
                    'min' => 0,
                    'max' => 100,
                    'step' => 2,
                ),
            ));
            
            $this->add_control('bp_' . $bp_key . '_columns', array(
                'label' => __('Número de Colunas', 'cct'),
                'description' => __('Número máximo de colunas no grid.', 'cct'),
                'section' => $this->prefix . 'bp_' . $bp_key,
                'type' => 'range',
                'input_attrs' => array(
                    'min' => 1,
                    'max' => 12,
                    'step' => 1,
                ),
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
     * Obtém breakpoints ativos
     */
    private function get_active_breakpoints() {
        $custom_breakpoints = get_theme_mod($this->prefix . 'custom_breakpoints');
        
        if ($custom_breakpoints) {
            $decoded = json_decode($custom_breakpoints, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        
        return $this->default_breakpoints;
    }
    
    /**
     * Obtém opções de templates
     */
    private function get_template_choices() {
        $choices = array();
        
        foreach ($this->breakpoint_templates as $key => $template) {
            $choices[$key] = $template['name'];
        }
        
        return $choices;
    }
    
    /**
     * Sanitiza JSON de breakpoints
     */
    public function sanitize_breakpoints_json($input) {
        $decoded = json_decode($input, true);
        
        if (!is_array($decoded)) {
            return wp_json_encode($this->default_breakpoints);
        }
        
        // Validar estrutura dos breakpoints
        foreach ($decoded as $key => $breakpoint) {
            if (!isset($breakpoint['min_width']) || !isset($breakpoint['max_width'])) {
                unset($decoded[$key]);
                continue;
            }
            
            // Sanitizar valores
            $decoded[$key]['min_width'] = absint($breakpoint['min_width']);
            $decoded[$key]['max_width'] = $breakpoint['max_width'] ? absint($breakpoint['max_width']) : null;
            $decoded[$key]['enabled'] = !empty($breakpoint['enabled']);
        }
        
        return wp_json_encode($decoded);
    }
    
    /**
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        // CSS dos breakpoints
        wp_enqueue_style(
            'cct-responsive-breakpoints',
            get_template_directory_uri() . '/css/cct-responsive-breakpoints.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript dos breakpoints
        wp_enqueue_script(
            'cct-responsive-breakpoints',
            get_template_directory_uri() . '/js/cct-responsive-breakpoints.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localização do script
        wp_localize_script('cct-responsive-breakpoints', 'cctBreakpoints', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_breakpoints_nonce'),
            'settings' => $this->get_frontend_settings(),
            'breakpoints' => $this->get_active_breakpoints(),
            'strings' => array(
                'currentBreakpoint' => __('Breakpoint Atual', 'cct'),
                'deviceType' => __('Tipo de Dispositivo', 'cct'),
                'screenSize' => __('Tamanho da Tela', 'cct')
            )
        ));
    }
    
    /**
     * Obtém configurações para o frontend
     */
    private function get_frontend_settings() {
        return array(
            'enabled' => get_theme_mod($this->prefix . 'enabled', true),
            'template' => get_theme_mod($this->prefix . 'template', 'bootstrap'),
            'detectionEnabled' => get_theme_mod($this->prefix . 'detection_enabled', true),
            'autoAdjustLayout' => get_theme_mod($this->prefix . 'auto_adjust_layout', true),
            'containerBehavior' => get_theme_mod($this->prefix . 'container_behavior', 'fluid'),
            'gridSystem' => get_theme_mod($this->prefix . 'grid_system', 'flexbox'),
            'debugMode' => get_theme_mod($this->prefix . 'debug_mode', false)
        );
    }
    
    /**
     * Gera CSS customizado
     */
    public function output_custom_css() {
        $settings = $this->get_frontend_settings();
        $breakpoints = $this->get_active_breakpoints();
        
        if (!$settings['enabled']) {
            return;
        }
        
        echo "<style id='cct-responsive-breakpoints-custom-css'>\n";
        
        // Variáveis CSS dos breakpoints
        echo ":root {\n";
        foreach ($breakpoints as $bp_key => $breakpoint) {
            if (!$breakpoint['enabled']) continue;
            
            echo "  --cct-bp-{$bp_key}-min: {$breakpoint['min_width']}px;\n";
            if ($breakpoint['max_width']) {
                echo "  --cct-bp-{$bp_key}-max: {$breakpoint['max_width']}px;\n";
            }
            echo "  --cct-bp-{$bp_key}-container: {$breakpoint['container_width']};\n";
            echo "  --cct-bp-{$bp_key}-gutter: {$breakpoint['gutter']}px;\n";
            echo "  --cct-bp-{$bp_key}-columns: {$breakpoint['columns']};\n";
        }
        echo "}\n";
        
        // Media queries para cada breakpoint
        foreach ($breakpoints as $bp_key => $breakpoint) {
            if (!$breakpoint['enabled']) continue;
            
            $min = $breakpoint['min_width'];
            $max = $breakpoint['max_width'];
            
            if ($max) {
                echo "@media (min-width: {$min}px) and (max-width: {$max}px) {\n";
            } else {
                echo "@media (min-width: {$min}px) {\n";
            }
            
            echo "  .cct-container {\n";
            echo "    max-width: var(--cct-bp-{$bp_key}-container);\n";
            echo "    padding-left: calc(var(--cct-bp-{$bp_key}-gutter) / 2);\n";
            echo "    padding-right: calc(var(--cct-bp-{$bp_key}-gutter) / 2);\n";
            echo "  }\n";
            
            echo "  .cct-row {\n";
            echo "    margin-left: calc(var(--cct-bp-{$bp_key}-gutter) / -2);\n";
            echo "    margin-right: calc(var(--cct-bp-{$bp_key}-gutter) / -2);\n";
            echo "  }\n";
            
            echo "  .cct-col {\n";
            echo "    padding-left: calc(var(--cct-bp-{$bp_key}-gutter) / 2);\n";
            echo "    padding-right: calc(var(--cct-bp-{$bp_key}-gutter) / 2);\n";
            echo "  }\n";
            
            // Classes de colunas específicas do breakpoint
            for ($i = 1; $i <= $breakpoint['columns']; $i++) {
                $width = ($i / $breakpoint['columns']) * 100;
                echo "  .cct-col-{$bp_key}-{$i} {\n";
                echo "    flex: 0 0 {$width}%;\n";
                echo "    max-width: {$width}%;\n";
                echo "  }\n";
            }
            
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
        
        echo "<script id='cct-responsive-breakpoints-custom-js'>\n";
        echo "document.addEventListener('DOMContentLoaded', function() {\n";
        echo "  if (typeof CCTBreakpoints !== 'undefined') {\n";
        echo "    CCTBreakpoints.init(" . wp_json_encode($settings) . ");\n";
        echo "  }\n";
        echo "});\n";
        echo "</script>\n";
    }
    
    /**
     * Shortcode para informações de breakpoint
     */
    public function breakpoint_info_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show' => 'all', // all, name, size, device
            'style' => 'inline', // inline, badge, card
            'class' => ''
        ), $atts, 'cct_breakpoint_info');
        
        $classes = array('cct-breakpoint-info', 'cct-info-' . $atts['style']);
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '" data-cct-breakpoint-info>';
        
        if ($atts['show'] === 'all' || $atts['show'] === 'name') {
            $output .= '<span class="cct-bp-name" data-info="name"></span>';
        }
        
        if ($atts['show'] === 'all' || $atts['show'] === 'size') {
            $output .= '<span class="cct-bp-size" data-info="size"></span>';
        }
        
        if ($atts['show'] === 'all' || $atts['show'] === 'device') {
            $output .= '<span class="cct-bp-device" data-info="device"></span>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para detector de dispositivo
     */
    public function device_detector_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show_icon' => 'true',
            'show_name' => 'true',
            'class' => ''
        ), $atts, 'cct_device_detector');
        
        $classes = array('cct-device-detector');
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '" data-cct-device-detector>';
        
        if ($atts['show_icon'] === 'true') {
            $output .= '<span class="cct-device-icon" data-device-icon></span>';
        }
        
        if ($atts['show_name'] === 'true') {
            $output .= '<span class="cct-device-name" data-device-name></span>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para conteúdo responsivo
     */
    public function responsive_content_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'breakpoint' => '',
            'min_width' => '',
            'max_width' => '',
            'device' => '', // mobile, tablet, desktop
            'class' => ''
        ), $atts, 'cct_responsive_content');
        
        $classes = array('cct-responsive-content');
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        // Adicionar classes baseadas nos atributos
        if (!empty($atts['breakpoint'])) {
            $classes[] = 'cct-show-' . sanitize_html_class($atts['breakpoint']);
        }
        
        if (!empty($atts['device'])) {
            $classes[] = 'cct-show-' . sanitize_html_class($atts['device']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '"';
        
        // Adicionar atributos de dados para JavaScript
        if (!empty($atts['min_width'])) {
            $output .= ' data-min-width="' . esc_attr($atts['min_width']) . '"';
        }
        
        if (!empty($atts['max_width'])) {
            $output .= ' data-max-width="' . esc_attr($atts['max_width']) . '"';
        }
        
        $output .= '>';
        $output .= do_shortcode($content);
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
        
        $classes[] = 'cct-responsive-enabled';
        $classes[] = 'cct-grid-' . $settings['gridSystem'];
        $classes[] = 'cct-container-' . $settings['containerBehavior'];
        
        if ($settings['debugMode']) {
            $classes[] = 'cct-breakpoints-debug';
        }
        
        return $classes;
    }
    
    /**
     * Inicializa preview do customizer
     */
    public function customize_preview_init() {
        wp_enqueue_script(
            'cct-breakpoints-preview',
            get_template_directory_uri() . '/js/cct-breakpoints-preview.js',
            array('jquery', 'customize-preview'),
            '1.0.0',
            true
        );
    }
    
    /**
     * AJAX handlers
     */
    public function ajax_save_breakpoint() {
        check_ajax_referer('cct_breakpoints_nonce', 'nonce');
        
        $breakpoint_data = $this->sanitize_breakpoint_data($_POST['breakpoint'] ?? array());
        
        if (empty($breakpoint_data)) {
            wp_die(__('Dados de breakpoint inválidos.', 'cct'));
        }
        
        // Salvar breakpoint
        $breakpoints = $this->get_active_breakpoints();
        $breakpoints[$breakpoint_data['key']] = $breakpoint_data;
        
        set_theme_mod($this->prefix . 'custom_breakpoints', wp_json_encode($breakpoints));
        
        wp_send_json_success(array(
            'message' => __('Breakpoint salvo com sucesso!', 'cct'),
            'breakpoint' => $breakpoint_data
        ));
    }
    
    public function ajax_delete_breakpoint() {
        check_ajax_referer('cct_breakpoints_nonce', 'nonce');
        
        $breakpoint_key = sanitize_text_field($_POST['breakpoint_key'] ?? '');
        
        if (empty($breakpoint_key)) {
            wp_die(__('Chave de breakpoint inválida.', 'cct'));
        }
        
        $breakpoints = $this->get_active_breakpoints();
        unset($breakpoints[$breakpoint_key]);
        
        set_theme_mod($this->prefix . 'custom_breakpoints', wp_json_encode($breakpoints));
        
        wp_send_json_success(array(
            'message' => __('Breakpoint removido com sucesso!', 'cct')
        ));
    }
    
    /**
     * Sanitiza dados de breakpoint
     */
    private function sanitize_breakpoint_data($data) {
        if (!is_array($data)) {
            return array();
        }
        
        return array(
            'key' => sanitize_key($data['key'] ?? ''),
            'name' => sanitize_text_field($data['name'] ?? ''),
            'label' => sanitize_text_field($data['label'] ?? ''),
            'min_width' => absint($data['min_width'] ?? 0),
            'max_width' => !empty($data['max_width']) ? absint($data['max_width']) : null,
            'enabled' => !empty($data['enabled']),
            'icon' => sanitize_text_field($data['icon'] ?? '📱'),
            'description' => sanitize_text_field($data['description'] ?? ''),
            'container_width' => sanitize_text_field($data['container_width'] ?? '100%'),
            'gutter' => absint($data['gutter'] ?? 16),
            'columns' => absint($data['columns'] ?? 12)
        );
    }
    
    /**
     * Obtém estatísticas dos breakpoints
     */
    public function get_breakpoints_stats() {
        $breakpoints = $this->get_active_breakpoints();
        $enabled_count = 0;
        
        foreach ($breakpoints as $breakpoint) {
            if ($breakpoint['enabled']) {
                $enabled_count++;
            }
        }
        
        return array(
            'total_breakpoints' => count($breakpoints),
            'enabled_breakpoints' => $enabled_count,
            'disabled_breakpoints' => count($breakpoints) - $enabled_count,
            'template' => get_theme_mod($this->prefix . 'template', 'bootstrap'),
            'grid_system' => get_theme_mod($this->prefix . 'grid_system', 'flexbox'),
            'container_behavior' => get_theme_mod($this->prefix . 'container_behavior', 'fluid')
        );
    }
}