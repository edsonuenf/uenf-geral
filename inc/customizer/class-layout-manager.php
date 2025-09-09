<?php
/**
 * Sistema de Componentes de Layout Avançado
 * 
 * Sistema completo de gerenciamento de layout incluindo:
 * - Grid system flexível e responsivo
 * - Containers adaptativos
 * - Seções pré-definidas
 * - Breakpoints customizáveis
 * - Ferramentas de espaçamento
 * - Layout builder visual
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
 * Classe para gerenciamento de layout
 */
class CCT_Layout_Manager {
    
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
    private $prefix = 'cct_layout_';
    
    /**
     * Configurações de grid
     * 
     * @var array
     */
    private $grid_settings;
    
    /**
     * Breakpoints responsivos
     * 
     * @var array
     */
    private $breakpoints;
    
    /**
     * Containers predefinidos
     * 
     * @var array
     */
    private $container_presets;
    
    /**
     * Seções de layout
     * 
     * @var array
     */
    private $layout_sections;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->init_grid_settings();
        $this->init_breakpoints();
        $this->init_container_presets();
        $this->init_layout_sections();
    }
    
    /**
     * Registra o módulo no customizer
     * 
     * @param WP_Customize_Manager $wp_customize
     */
    public function register($wp_customize) {
        $this->wp_customize = $wp_customize;
        $this->init_hooks();
        $this->init();
    }
    
    /**
     * Inicializa os hooks
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_layout_scripts'));
        add_action('wp_head', array($this, 'output_custom_css'));
        add_action('wp_footer', array($this, 'output_custom_js'));
    }
    
    /**
     * Registra configurações no Customizer
     */
    public function register_customizer() {
        $this->add_layout_sections();
        $this->add_layout_settings();
        $this->add_layout_controls();
    }
    
    /**
     * Inicializa o módulo
     */
    private function init() {
        $this->register_customizer();
        $this->register_layout_hooks();
    }
    
    /**
     * Inicializa configurações de grid
     */
    private function init_grid_settings() {
        $this->grid_settings = array(
            'columns' => array(
                'default' => 12,
                'min' => 6,
                'max' => 24,
                'step' => 1
            ),
            'gutter' => array(
                'default' => 30,
                'min' => 0,
                'max' => 60,
                'step' => 5,
                'unit' => 'px'
            ),
            'max_width' => array(
                'default' => 1200,
                'min' => 960,
                'max' => 1920,
                'step' => 60,
                'unit' => 'px'
            ),
            'alignment' => array(
                'default' => 'center',
                'options' => array('left', 'center', 'right')
            )
        );
    }
    
    /**
     * Inicializa breakpoints responsivos
     */
    private function init_breakpoints() {
        $this->breakpoints = array(
            'xs' => array(
                'name' => __('Extra Small', 'cct'),
                'description' => __('Smartphones (portrait)', 'cct'),
                'min_width' => 0,
                'max_width' => 575,
                'default' => true,
                'icon' => 'smartphone'
            ),
            'sm' => array(
                'name' => __('Small', 'cct'),
                'description' => __('Smartphones (landscape)', 'cct'),
                'min_width' => 576,
                'max_width' => 767,
                'default' => true,
                'icon' => 'smartphone'
            ),
            'md' => array(
                'name' => __('Medium', 'cct'),
                'description' => __('Tablets', 'cct'),
                'min_width' => 768,
                'max_width' => 991,
                'default' => true,
                'icon' => 'tablet'
            ),
            'lg' => array(
                'name' => __('Large', 'cct'),
                'description' => __('Desktops', 'cct'),
                'min_width' => 992,
                'max_width' => 1199,
                'default' => true,
                'icon' => 'desktop'
            ),
            'xl' => array(
                'name' => __('Extra Large', 'cct'),
                'description' => __('Large Desktops', 'cct'),
                'min_width' => 1200,
                'max_width' => 1399,
                'default' => true,
                'icon' => 'desktop'
            ),
            'xxl' => array(
                'name' => __('Extra Extra Large', 'cct'),
                'description' => __('Ultra Wide Screens', 'cct'),
                'min_width' => 1400,
                'max_width' => null,
                'default' => false,
                'icon' => 'desktop'
            )
        );
    }
    
    /**
     * Inicializa presets de containers
     */
    private function init_container_presets() {
        $this->container_presets = array(
            'fluid' => array(
                'name' => __('Container Fluido', 'cct'),
                'description' => __('Largura 100% em todos os breakpoints', 'cct'),
                'max_width' => '100%',
                'padding' => '15px',
                'margin' => '0 auto',
                'responsive' => false
            ),
            'fixed' => array(
                'name' => __('Container Fixo', 'cct'),
                'description' => __('Largura fixa baseada no breakpoint', 'cct'),
                'max_width' => '1200px',
                'padding' => '0 15px',
                'margin' => '0 auto',
                'responsive' => true
            ),
            'narrow' => array(
                'name' => __('Container Estreito', 'cct'),
                'description' => __('Largura reduzida para conteúdo focado', 'cct'),
                'max_width' => '800px',
                'padding' => '0 20px',
                'margin' => '0 auto',
                'responsive' => true
            ),
            'wide' => array(
                'name' => __('Container Largo', 'cct'),
                'description' => __('Largura expandida para mais conteúdo', 'cct'),
                'max_width' => '1400px',
                'padding' => '0 30px',
                'margin' => '0 auto',
                'responsive' => true
            ),
            'full' => array(
                'name' => __('Largura Total', 'cct'),
                'description' => __('Sem limitação de largura', 'cct'),
                'max_width' => 'none',
                'padding' => '0',
                'margin' => '0',
                'responsive' => false
            )
        );
    }
    
    /**
     * Inicializa seções de layout
     */
    private function init_layout_sections() {
        $this->layout_sections = array(
            'header' => array(
                'name' => __('Cabeçalho', 'cct'),
                'description' => __('Área superior do site', 'cct'),
                'default_container' => 'fixed',
                'allow_full_width' => true,
                'sticky_options' => true
            ),
            'hero' => array(
                'name' => __('Seção Hero', 'cct'),
                'description' => __('Área de destaque principal', 'cct'),
                'default_container' => 'fluid',
                'allow_full_width' => true,
                'background_options' => true
            ),
            'content' => array(
                'name' => __('Conteúdo Principal', 'cct'),
                'description' => __('Área do conteúdo', 'cct'),
                'default_container' => 'fixed',
                'allow_sidebar' => true,
                'grid_options' => true
            ),
            'sidebar' => array(
                'name' => __('Barra Lateral', 'cct'),
                'description' => __('Área lateral complementar', 'cct'),
                'default_container' => 'none',
                'width_options' => true,
                'position_options' => true
            ),
            'footer' => array(
                'name' => __('Rodapé', 'cct'),
                'description' => __('Área inferior do site', 'cct'),
                'default_container' => 'fixed',
                'allow_full_width' => true,
                'columns_options' => true
            )
        );
    }
    
    /**
     * Adiciona seções de layout
     */
    private function add_layout_sections() {
        // Painel principal de layout
        $this->wp_customize->add_panel($this->prefix . 'panel', array(
            'title' => __('Componentes de Layout', 'cct'),
            'description' => __('Sistema avançado de grid e containers responsivos.', 'cct'),
            'priority' => 150,
            'capability' => 'edit_theme_options',
        ));
        
        // Seção de configurações de grid
        $this->wp_customize->add_section($this->prefix . 'grid_system', array(
            'title' => __('Sistema de Grid', 'cct'),
            'description' => __('Configure o grid responsivo e suas propriedades.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 10,
        ));
        
        // Seção de containers
        $this->wp_customize->add_section($this->prefix . 'containers', array(
            'title' => __('Containers', 'cct'),
            'description' => __('Gerencie containers e suas configurações.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 20,
        ));
        
        // Seção de breakpoints
        $this->wp_customize->add_section($this->prefix . 'breakpoints', array(
            'title' => __('Breakpoints Responsivos', 'cct'),
            'description' => __('Configure pontos de quebra para responsividade.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 30,
        ));
        
        // Seção de espaçamentos
        $this->wp_customize->add_section($this->prefix . 'spacing', array(
            'title' => __('Sistema de Espaçamentos', 'cct'),
            'description' => __('Configure margens, paddings e espaçamentos.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 40,
        ));
        
        // Seção de layout builder
        $this->wp_customize->add_section($this->prefix . 'layout_builder', array(
            'title' => __('Construtor de Layout', 'cct'),
            'description' => __('Ferramenta visual para criar layouts personalizados.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 50,
        ));
    }
    
    /**
     * Adiciona configurações de layout
     */
    private function add_layout_settings() {
        // Configurações do grid
        $grid_columns_default = isset($this->grid_settings['columns']['default']) ? $this->grid_settings['columns']['default'] : 12;
        $grid_gutter_default = isset($this->grid_settings['gutter']['default']) ? $this->grid_settings['gutter']['default'] : 30;
        
        $this->add_setting('grid_columns', array(
            'default' => $grid_columns_default,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        $this->add_setting('grid_gutter', array(
            'default' => $grid_gutter_default,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        $grid_max_width_default = isset($this->grid_settings['max_width']['default']) ? $this->grid_settings['max_width']['default'] : 1200;
        $grid_alignment_default = isset($this->grid_settings['alignment']['default']) ? $this->grid_settings['alignment']['default'] : 'center';
        
        $this->add_setting('grid_max_width', array(
            'default' => $grid_max_width_default,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        $this->add_setting('grid_alignment', array(
            'default' => $grid_alignment_default,
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        // Configurações de containers
        $this->add_setting('default_container', array(
            'default' => 'fixed',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        $this->add_setting('container_padding', array(
            'default' => 15,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        // Configurações de breakpoints
        if (is_array($this->breakpoints) && !empty($this->breakpoints)) {
            foreach ($this->breakpoints as $bp_key => $breakpoint) {
                $this->add_setting("breakpoint_{$bp_key}_enabled", array(
                    'default' => isset($breakpoint['default']) ? $breakpoint['default'] : true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ));
                
                $this->add_setting("breakpoint_{$bp_key}_width", array(
                    'default' => isset($breakpoint['min_width']) ? $breakpoint['min_width'] : 0,
                    'sanitize_callback' => 'absint',
                    'transport' => 'postMessage',
                ));
            }
        }
        
        // Configurações de espaçamentos
        $spacing_scales = array('xs', 'sm', 'md', 'lg', 'xl', 'xxl');
        $spacing_defaults = array(4, 8, 16, 24, 32, 48);
        
        foreach ($spacing_scales as $index => $scale) {
            $this->add_setting("spacing_{$scale}", array(
                'default' => $spacing_defaults[$index],
                'sanitize_callback' => 'absint',
                'transport' => 'postMessage',
            ));
        }
        
        // Configurações de seções de layout
        if (is_array($this->layout_sections) && !empty($this->layout_sections)) {
            foreach ($this->layout_sections as $section_key => $section) {
                $this->add_setting("layout_{$section_key}_container", array(
                    'default' => isset($section['default_container']) ? $section['default_container'] : 'fixed',
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport' => 'postMessage',
                ));
                
                $this->add_setting("layout_{$section_key}_full_width", array(
                    'default' => false,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                    'transport' => 'postMessage',
                ));
            }
        }
        
        // Configurações do layout builder
        $this->add_setting('custom_layouts', array(
            'default' => json_encode(array()),
            'sanitize_callback' => array($this, 'sanitize_json_array'),
        ));
        
        $this->add_setting('active_layout', array(
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
        ));
    }
    
    /**
     * Adiciona controles de layout
     */
    private function add_layout_controls() {
        // Controles do sistema de grid
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                'cct_grid_columns',
                array(
                    'label' => __('Número de Colunas', 'cct'),
                    'description' => __('Define quantas colunas o grid terá.', 'cct'),
                    'section' => $this->prefix . 'grid_system',
                    'settings' => $this->prefix . 'grid_columns',
                    'input_attrs' => array(
                        'min' => isset($this->grid_settings['columns']['min']) ? $this->grid_settings['columns']['min'] : 6,
                        'max' => isset($this->grid_settings['columns']['max']) ? $this->grid_settings['columns']['max'] : 24,
                        'step' => isset($this->grid_settings['columns']['step']) ? $this->grid_settings['columns']['step'] : 1,
                    ),
                )
            )
        );
        
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                'cct_grid_gutter',
                array(
                    'label' => __('Espaçamento entre Colunas (px)', 'cct'),
                    'description' => __('Espaço entre as colunas do grid.', 'cct'),
                    'section' => $this->prefix . 'grid_system',
                    'settings' => $this->prefix . 'grid_gutter',
                    'input_attrs' => array(
                        'min' => isset($this->grid_settings['gutter']['min']) ? $this->grid_settings['gutter']['min'] : 0,
                        'max' => isset($this->grid_settings['gutter']['max']) ? $this->grid_settings['gutter']['max'] : 60,
                        'step' => isset($this->grid_settings['gutter']['step']) ? $this->grid_settings['gutter']['step'] : 5,
                    ),
                )
            )
        );
        
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                'cct_grid_max_width',
                array(
                    'label' => __('Largura Máxima (px)', 'cct'),
                    'description' => __('Largura máxima do container principal.', 'cct'),
                    'section' => $this->prefix . 'grid_system',
                    'settings' => $this->prefix . 'grid_max_width',
                    'input_attrs' => array(
                        'min' => isset($this->grid_settings['max_width']['min']) ? $this->grid_settings['max_width']['min'] : 960,
                        'max' => isset($this->grid_settings['max_width']['max']) ? $this->grid_settings['max_width']['max'] : 1920,
                        'step' => isset($this->grid_settings['max_width']['step']) ? $this->grid_settings['max_width']['step'] : 20,
                    ),
                )
            )
        );
        
        $this->add_control('grid_alignment', array(
            'label' => __('Alinhamento do Grid', 'cct'),
            'description' => __('Como o grid será alinhado na página.', 'cct'),
            'section' => $this->prefix . 'grid_system',
            'type' => 'select',
            'choices' => array(
                'left' => __('Esquerda', 'cct'),
                'center' => __('Centro', 'cct'),
                'right' => __('Direita', 'cct'),
            ),
        ));
        
        // Preview do grid (usando controle padrão temporariamente)
        $this->wp_customize->add_control(
            'cct_grid_preview',
            array(
                'label' => __('Preview do Grid', 'cct'),
                'section' => $this->prefix . 'grid_system',
                'settings' => $this->prefix . 'grid_columns',
                'type' => 'select',
                'choices' => array(
                    'preview' => __('Preview será implementado em versão futura', 'cct')
                ),
            )
        );
        
        // Controles de containers
        $this->add_control('default_container', array(
            'label' => __('Container Padrão', 'cct'),
            'description' => __('Tipo de container usado por padrão.', 'cct'),
            'section' => $this->prefix . 'containers',
            'type' => 'select',
            'choices' => $this->get_container_choices(),
        ));
        
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                'cct_container_padding',
                array(
                    'label' => __('Padding dos Containers (px)', 'cct'),
                    'description' => __('Espaçamento interno dos containers.', 'cct'),
                    'section' => $this->prefix . 'containers',
                    'settings' => $this->prefix . 'container_padding',
                    'input_attrs' => array(
                        'min' => 0,
                        'max' => 60,
                        'step' => 5,
                    ),
                )
            )
        );
        
        // Gerenciador de containers (usando controle padrão temporariamente)
        $this->wp_customize->add_control(
            'cct_container_manager',
            array(
                'label' => __('Gerenciador de Containers', 'cct'),
                'section' => $this->prefix . 'containers',
                'type' => 'select',
                'choices' => array(
                    'manager' => __('Gerenciador será implementado em versão futura', 'cct')
                ),
            )
        );
        
        // Controles de breakpoints (usando controle padrão temporariamente)
        $this->wp_customize->add_control(
            'cct_breakpoint_manager',
            array(
                'label' => __('Gerenciador de Breakpoints', 'cct'),
                'section' => $this->prefix . 'breakpoints',
                'type' => 'select',
                'choices' => array(
                    'manager' => __('Gerenciador será implementado em versão futura', 'cct')
                ),
            )
        );
        
        // Controles de espaçamentos (usando controle padrão temporariamente)
        $this->wp_customize->add_control(
            'cct_spacing_scale',
            array(
                'label' => __('Escala de Espaçamentos', 'cct'),
                'description' => __('Configure os valores da escala de espaçamentos.', 'cct'),
                'section' => $this->prefix . 'spacing',
                'type' => 'select',
                'choices' => array(
                    'scale' => __('Escala será implementada em versão futura', 'cct')
                ),
            )
        );
        
        // Layout builder (usando controle padrão temporariamente)
        $this->wp_customize->add_control(
            'cct_layout_builder',
            array(
                'label' => __('Construtor Visual de Layout', 'cct'),
                'section' => $this->prefix . 'layout_builder',
                'settings' => $this->prefix . 'custom_layouts',
                'type' => 'select',
                'choices' => array(
                    'builder' => __('Construtor será implementado em versão futura', 'cct')
                ),
            )
        );
    }
    
    /**
     * Enfileira scripts do sistema de layout
     */
    private function enqueue_layout_scripts() {
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_controls_scripts'));
        add_action('customize_preview_init', array($this, 'enqueue_preview_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    }
    
    /**
     * Registra hooks do sistema
     */
    private function register_layout_hooks() {
        add_action('wp_ajax_cct_save_layout', array($this, 'handle_layout_save'));
        add_action('wp_ajax_cct_load_layout', array($this, 'handle_layout_load'));
        add_action('wp_ajax_cct_delete_layout', array($this, 'handle_layout_delete'));
        add_shortcode('cct_container', array($this, 'container_shortcode'));
        add_shortcode('cct_row', array($this, 'row_shortcode'));
        add_shortcode('cct_col', array($this, 'column_shortcode'));
    }
    
    /**
     * Enfileira scripts dos controles
     */
    public function enqueue_controls_scripts() {
        wp_enqueue_script(
            'cct-layout-manager',
            get_template_directory_uri() . '/js/customizer-layout-manager.js',
            array('jquery', 'customize-controls'),
            '1.0.0',
            true
        );
        
        wp_enqueue_style(
            'cct-layout-manager',
            get_template_directory_uri() . '/css/customizer-layout-manager.css',
            array(),
            '1.0.0'
        );
        
        wp_localize_script('cct-layout-manager', 'cctLayoutManager', array(
            'gridSettings' => $this->grid_settings,
            'breakpoints' => $this->breakpoints,
            'containerPresets' => $this->container_presets,
            'layoutSections' => $this->layout_sections,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_layout_manager'),
            'strings' => array(
                'saveSuccess' => __('Layout salvo com sucesso!', 'cct'),
                'saveError' => __('Erro ao salvar layout.', 'cct'),
                'deleteConfirm' => __('Tem certeza que deseja excluir este layout?', 'cct'),
                'previewMode' => __('Modo Preview', 'cct'),
                'editMode' => __('Modo Edição', 'cct'),
            )
        ));
    }
    
    /**
     * Enfileira scripts do preview
     */
    public function enqueue_preview_scripts() {
        wp_enqueue_script(
            'cct-layout-preview',
            get_template_directory_uri() . '/js/customizer-layout-preview.js',
            array('jquery', 'customize-preview'),
            '1.0.0',
            true
        );
    }
    
    /**
     * Enfileira scripts do frontend
     */
    public function enqueue_frontend_scripts() {
        wp_enqueue_style(
            'cct-layout-system',
            get_template_directory_uri() . '/css/cct-layout-system.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Obtém opções de containers para select
     * 
     * @return array
     */
    private function get_container_choices() {
        $choices = array();
        
        if (is_array($this->container_presets) && !empty($this->container_presets)) {
            foreach ($this->container_presets as $key => $preset) {
                $choices[$key] = isset($preset['name']) ? $preset['name'] : $key;
            }
        } else {
            // Fallback com containers padrão
            $choices = array(
                'fixed' => __('Container Fixo', 'cct'),
                'fluid' => __('Container Fluido', 'cct'),
                'narrow' => __('Container Estreito', 'cct'),
                'wide' => __('Container Largo', 'cct'),
                'full' => __('Largura Total', 'cct'),
            );
        }
        
        return $choices;
    }
    
    /**
     * Shortcode para container
     */
    public function container_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'type' => get_theme_mod('cct_default_container', 'fixed'),
            'class' => '',
            'fluid' => false
        ), $atts, 'cct_container');
        
        $classes = array('cct-container');
        
        if ($atts['fluid'] || $atts['type'] === 'fluid') {
            $classes[] = 'cct-container-fluid';
        } else {
            $classes[] = 'cct-container-' . $atts['type'];
        }
        
        if (!empty($atts['class'])) {
            $classes[] = $atts['class'];
        }
        
        return '<div class="' . esc_attr(implode(' ', $classes)) . '">' . do_shortcode($content) . '</div>';
    }
    
    /**
     * Shortcode para row
     */
    public function row_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'class' => '',
            'align' => '',
            'justify' => ''
        ), $atts, 'cct_row');
        
        $classes = array('cct-row');
        
        if (!empty($atts['align'])) {
            $classes[] = 'align-items-' . $atts['align'];
        }
        
        if (!empty($atts['justify'])) {
            $classes[] = 'justify-content-' . $atts['justify'];
        }
        
        if (!empty($atts['class'])) {
            $classes[] = $atts['class'];
        }
        
        return '<div class="' . esc_attr(implode(' ', $classes)) . '">' . do_shortcode($content) . '</div>';
    }
    
    /**
     * Shortcode para coluna
     */
    public function column_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'xs' => '',
            'sm' => '',
            'md' => '',
            'lg' => '',
            'xl' => '',
            'class' => ''
        ), $atts, 'cct_col');
        
        $classes = array('cct-col');
        
        foreach (array('xs', 'sm', 'md', 'lg', 'xl') as $breakpoint) {
            if (!empty($atts[$breakpoint])) {
                if ($breakpoint === 'xs') {
                    $classes[] = 'cct-col-' . $atts[$breakpoint];
                } else {
                    $classes[] = 'cct-col-' . $breakpoint . '-' . $atts[$breakpoint];
                }
            }
        }
        
        if (!empty($atts['class'])) {
            $classes[] = $atts['class'];
        }
        
        return '<div class="' . esc_attr(implode(' ', $classes)) . '">' . do_shortcode($content) . '</div>';
    }
    
    /**
     * Manipula salvamento de layout
     */
    public function handle_layout_save() {
        check_ajax_referer('cct_layout_manager', 'nonce');
        
        if (!current_user_can('customize')) {
            wp_die(__('Permissão negada.', 'cct'));
        }
        
        $layout_name = sanitize_text_field($_POST['layout_name']);
        $layout_data = wp_unslash($_POST['layout_data']);
        
        // Valida dados do layout
        $decoded_data = json_decode($layout_data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error(__('Dados de layout inválidos.', 'cct'));
        }
        
        // Salva layout
        $custom_layouts = json_decode(get_theme_mod('cct_custom_layouts', '[]'), true);
        $custom_layouts[$layout_name] = $decoded_data;
        
        set_theme_mod('cct_custom_layouts', json_encode($custom_layouts));
        
        wp_send_json_success(array(
            'message' => __('Layout salvo com sucesso!', 'cct'),
            'layout_name' => $layout_name
        ));
    }
    
    /**
     * Sanitiza array JSON
     */
    public function sanitize_json_array($input) {
        $decoded = json_decode($input, true);
        return is_array($decoded) ? json_encode($decoded) : json_encode(array());
    }
    
    /**
     * Gera CSS do grid system
     */
    public function generate_grid_css() {
        $columns = get_theme_mod('cct_grid_columns', 12);
        $gutter = get_theme_mod('cct_grid_gutter', 30);
        $max_width = get_theme_mod('cct_grid_max_width', 1200);
        $alignment = get_theme_mod('cct_grid_alignment', 'center');
        
        $css = "";
        
        // Container base
        $css .= ".cct-container { max-width: {$max_width}px; margin: 0 ";
        $css .= ($alignment === 'center') ? 'auto' : (($alignment === 'left') ? '0 auto 0 0' : '0 0 0 auto');
        $css .= "; padding: 0 " . ($gutter / 2) . "px; }\n";
        
        // Row
        $css .= ".cct-row { display: flex; flex-wrap: wrap; margin: 0 -" . ($gutter / 2) . "px; }\n";
        
        // Colunas
        for ($i = 1; $i <= $columns; $i++) {
            $width = ($i / $columns) * 100;
            $css .= ".cct-col-{$i} { flex: 0 0 {$width}%; max-width: {$width}%; padding: 0 " . ($gutter / 2) . "px; }\n";
        }
        
        // Breakpoints responsivos
        foreach ($this->breakpoints as $bp_key => $breakpoint) {
            if (!get_theme_mod("cct_breakpoint_{$bp_key}_enabled", $breakpoint['default'])) {
                continue;
            }
            
            $min_width = get_theme_mod("cct_breakpoint_{$bp_key}_width", $breakpoint['min_width']);
            
            if ($min_width > 0) {
                $css .= "@media (min-width: {$min_width}px) {\n";
                
                for ($i = 1; $i <= $columns; $i++) {
                    $width = ($i / $columns) * 100;
                    $css .= "  .cct-col-{$bp_key}-{$i} { flex: 0 0 {$width}%; max-width: {$width}%; }\n";
                }
                
                $css .= "}\n";
            }
        }
        
        return $css;
    }
}