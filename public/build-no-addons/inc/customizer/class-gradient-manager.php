<?php
/**
 * Biblioteca de Gradientes - Gerenciador Principal
 * 
 * Sistema completo de gradientes incluindo:
 * - Biblioteca de gradientes predefinidos
 * - Gerador personalizado de gradientes
 * - Editor visual interativo
 * - Presets populares e tendências
 * - Export e import de gradientes
 * - Integração com sistema de cores
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
 * Classe principal da Biblioteca de Gradientes
 */
class CCT_Gradient_Manager {
    
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
    private $prefix = 'cct_gradient_';
    
    /**
     * Biblioteca de gradientes predefinidos
     * 
     * @var array
     */
    private $gradient_library;
    
    /**
     * Categorias de gradientes
     * 
     * @var array
     */
    private $gradient_categories;
    
    /**
     * Configurações do gerador
     * 
     * @var array
     */
    private $generator_settings;
    
    /**
     * Presets populares
     * 
     * @var array
     */
    private $popular_presets;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->init_gradient_library();
        $this->init_gradient_categories();
        $this->init_generator_settings();
        $this->init_popular_presets();
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
        add_shortcode('cct_gradient', array($this, 'gradient_shortcode'));
        add_shortcode('cct_gradient_text', array($this, 'gradient_text_shortcode'));
        add_shortcode('cct_gradient_button', array($this, 'gradient_button_shortcode'));
        
        // AJAX handlers
        add_action('wp_ajax_cct_generate_gradient', array($this, 'ajax_generate_gradient'));
        add_action('wp_ajax_nopriv_cct_generate_gradient', array($this, 'ajax_generate_gradient'));
        add_action('wp_ajax_cct_save_gradient', array($this, 'ajax_save_gradient'));
        add_action('wp_ajax_cct_export_gradients', array($this, 'ajax_export_gradients'));
    }
    
    /**
     * Inicializa biblioteca de gradientes
     */
    private function init_gradient_library() {
        $this->gradient_library = array(
            // Gradientes Lineares Clássicos
            'sunset' => array(
                'name' => __('Pôr do Sol', 'cct'),
                'description' => __('Gradiente quente inspirado no pôr do sol', 'cct'),
                'type' => 'linear',
                'direction' => '45deg',
                'colors' => array(
                    array('color' => '#ff7e5f', 'position' => '0%'),
                    array('color' => '#feb47b', 'position' => '100%')
                ),
                'css' => 'linear-gradient(45deg, #ff7e5f 0%, #feb47b 100%)',
                'category' => 'warm',
                'popularity' => 95
            ),
            'ocean' => array(
                'name' => __('Oceano', 'cct'),
                'description' => __('Gradiente azul profundo como o oceano', 'cct'),
                'type' => 'linear',
                'direction' => '135deg',
                'colors' => array(
                    array('color' => '#667eea', 'position' => '0%'),
                    array('color' => '#764ba2', 'position' => '100%')
                ),
                'css' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'category' => 'cool',
                'popularity' => 90
            ),
            'forest' => array(
                'name' => __('Floresta', 'cct'),
                'description' => __('Gradiente verde natural da floresta', 'cct'),
                'type' => 'linear',
                'direction' => '90deg',
                'colors' => array(
                    array('color' => '#134e5e', 'position' => '0%'),
                    array('color' => '#71b280', 'position' => '100%')
                ),
                'css' => 'linear-gradient(90deg, #134e5e 0%, #71b280 100%)',
                'category' => 'nature',
                'popularity' => 85
            ),
            'aurora' => array(
                'name' => __('Aurora Boreal', 'cct'),
                'description' => __('Gradiente mágico da aurora boreal', 'cct'),
                'type' => 'linear',
                'direction' => '180deg',
                'colors' => array(
                    array('color' => '#00c6ff', 'position' => '0%'),
                    array('color' => '#0072ff', 'position' => '100%')
                ),
                'css' => 'linear-gradient(180deg, #00c6ff 0%, #0072ff 100%)',
                'category' => 'cool',
                'popularity' => 88
            ),
            'fire' => array(
                'name' => __('Fogo', 'cct'),
                'description' => __('Gradiente intenso de fogo', 'cct'),
                'type' => 'linear',
                'direction' => '45deg',
                'colors' => array(
                    array('color' => '#f12711', 'position' => '0%'),
                    array('color' => '#f5af19', 'position' => '100%')
                ),
                'css' => 'linear-gradient(45deg, #f12711 0%, #f5af19 100%)',
                'category' => 'warm',
                'popularity' => 82
            ),
            
            // Gradientes Radiais
            'cosmic' => array(
                'name' => __('Cósmico', 'cct'),
                'description' => __('Gradiente radial cósmico', 'cct'),
                'type' => 'radial',
                'shape' => 'circle',
                'position' => 'center',
                'colors' => array(
                    array('color' => '#667eea', 'position' => '0%'),
                    array('color' => '#764ba2', 'position' => '50%'),
                    array('color' => '#f093fb', 'position' => '100%')
                ),
                'css' => 'radial-gradient(circle at center, #667eea 0%, #764ba2 50%, #f093fb 100%)',
                'category' => 'cosmic',
                'popularity' => 78
            ),
            'bubble' => array(
                'name' => __('Bolha', 'cct'),
                'description' => __('Gradiente radial suave como bolha', 'cct'),
                'type' => 'radial',
                'shape' => 'ellipse',
                'position' => 'center',
                'colors' => array(
                    array('color' => '#ffecd2', 'position' => '0%'),
                    array('color' => '#fcb69f', 'position' => '100%')
                ),
                'css' => 'radial-gradient(ellipse at center, #ffecd2 0%, #fcb69f 100%)',
                'category' => 'soft',
                'popularity' => 75
            ),
            
            // Gradientes Cônicos
            'rainbow' => array(
                'name' => __('Arco-íris', 'cct'),
                'description' => __('Gradiente cônico do arco-íris', 'cct'),
                'type' => 'conic',
                'angle' => '0deg',
                'position' => 'center',
                'colors' => array(
                    array('color' => '#ff0000', 'position' => '0deg'),
                    array('color' => '#ff8000', 'position' => '60deg'),
                    array('color' => '#ffff00', 'position' => '120deg'),
                    array('color' => '#80ff00', 'position' => '180deg'),
                    array('color' => '#00ff80', 'position' => '240deg'),
                    array('color' => '#0080ff', 'position' => '300deg'),
                    array('color' => '#ff0000', 'position' => '360deg')
                ),
                'css' => 'conic-gradient(from 0deg at center, #ff0000 0deg, #ff8000 60deg, #ffff00 120deg, #80ff00 180deg, #00ff80 240deg, #0080ff 300deg, #ff0000 360deg)',
                'category' => 'colorful',
                'popularity' => 70
            ),
            
            // Gradientes Modernos
            'neon' => array(
                'name' => __('Neon', 'cct'),
                'description' => __('Gradiente neon vibrante', 'cct'),
                'type' => 'linear',
                'direction' => '45deg',
                'colors' => array(
                    array('color' => '#12c2e9', 'position' => '0%'),
                    array('color' => '#c471ed', 'position' => '50%'),
                    array('color' => '#f64f59', 'position' => '100%')
                ),
                'css' => 'linear-gradient(45deg, #12c2e9 0%, #c471ed 50%, #f64f59 100%)',
                'category' => 'neon',
                'popularity' => 92
            ),
            'cyberpunk' => array(
                'name' => __('Cyberpunk', 'cct'),
                'description' => __('Gradiente futurista cyberpunk', 'cct'),
                'type' => 'linear',
                'direction' => '135deg',
                'colors' => array(
                    array('color' => '#0f0f23', 'position' => '0%'),
                    array('color' => '#ff006e', 'position' => '50%'),
                    array('color' => '#8338ec', 'position' => '100%')
                ),
                'css' => 'linear-gradient(135deg, #0f0f23 0%, #ff006e 50%, #8338ec 100%)',
                'category' => 'dark',
                'popularity' => 87
            ),
            
            // Gradientes Pastel
            'cotton_candy' => array(
                'name' => __('Algodão Doce', 'cct'),
                'description' => __('Gradiente pastel suave', 'cct'),
                'type' => 'linear',
                'direction' => '45deg',
                'colors' => array(
                    array('color' => '#ffeef8', 'position' => '0%'),
                    array('color' => '#f0e6ff', 'position' => '100%')
                ),
                'css' => 'linear-gradient(45deg, #ffeef8 0%, #f0e6ff 100%)',
                'category' => 'pastel',
                'popularity' => 73
            ),
            'mint' => array(
                'name' => __('Menta', 'cct'),
                'description' => __('Gradiente verde menta refrescante', 'cct'),
                'type' => 'linear',
                'direction' => '90deg',
                'colors' => array(
                    array('color' => '#e8f5e8', 'position' => '0%'),
                    array('color' => '#b8e6b8', 'position' => '100%')
                ),
                'css' => 'linear-gradient(90deg, #e8f5e8 0%, #b8e6b8 100%)',
                'category' => 'pastel',
                'popularity' => 68
            ),
            
            // Gradientes Metálicos
            'gold' => array(
                'name' => __('Ouro', 'cct'),
                'description' => __('Gradiente dourado luxuoso', 'cct'),
                'type' => 'linear',
                'direction' => '45deg',
                'colors' => array(
                    array('color' => '#ffd700', 'position' => '0%'),
                    array('color' => '#ffed4e', 'position' => '50%'),
                    array('color' => '#ff9500', 'position' => '100%')
                ),
                'css' => 'linear-gradient(45deg, #ffd700 0%, #ffed4e 50%, #ff9500 100%)',
                'category' => 'metallic',
                'popularity' => 80
            ),
            'silver' => array(
                'name' => __('Prata', 'cct'),
                'description' => __('Gradiente prateado elegante', 'cct'),
                'type' => 'linear',
                'direction' => '135deg',
                'colors' => array(
                    array('color' => '#c0c0c0', 'position' => '0%'),
                    array('color' => '#e8e8e8', 'position' => '50%'),
                    array('color' => '#a8a8a8', 'position' => '100%')
                ),
                'css' => 'linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 50%, #a8a8a8 100%)',
                'category' => 'metallic',
                'popularity' => 76
            )
        );
    }
    
    /**
     * Inicializa categorias de gradientes
     */
    private function init_gradient_categories() {
        $this->gradient_categories = array(
            'all' => array(
                'name' => __('Todos', 'cct'),
                'description' => __('Todos os gradientes disponíveis', 'cct'),
                'icon' => '🎨',
                'color' => '#667eea'
            ),
            'warm' => array(
                'name' => __('Quentes', 'cct'),
                'description' => __('Gradientes com tons quentes', 'cct'),
                'icon' => '🔥',
                'color' => '#ff7e5f'
            ),
            'cool' => array(
                'name' => __('Frios', 'cct'),
                'description' => __('Gradientes com tons frios', 'cct'),
                'icon' => '❄️',
                'color' => '#667eea'
            ),
            'nature' => array(
                'name' => __('Natureza', 'cct'),
                'description' => __('Gradientes inspirados na natureza', 'cct'),
                'icon' => '🌿',
                'color' => '#71b280'
            ),
            'cosmic' => array(
                'name' => __('Cósmico', 'cct'),
                'description' => __('Gradientes espaciais e cósmicos', 'cct'),
                'icon' => '🌌',
                'color' => '#764ba2'
            ),
            'neon' => array(
                'name' => __('Neon', 'cct'),
                'description' => __('Gradientes neon vibrantes', 'cct'),
                'icon' => '💡',
                'color' => '#c471ed'
            ),
            'pastel' => array(
                'name' => __('Pastel', 'cct'),
                'description' => __('Gradientes suaves e pastéis', 'cct'),
                'icon' => '🌸',
                'color' => '#ffeef8'
            ),
            'dark' => array(
                'name' => __('Escuro', 'cct'),
                'description' => __('Gradientes escuros e dramáticos', 'cct'),
                'icon' => '🌙',
                'color' => '#0f0f23'
            ),
            'metallic' => array(
                'name' => __('Metálico', 'cct'),
                'description' => __('Gradientes metálicos luxuosos', 'cct'),
                'icon' => '✨',
                'color' => '#ffd700'
            ),
            'colorful' => array(
                'name' => __('Colorido', 'cct'),
                'description' => __('Gradientes multicoloridos', 'cct'),
                'icon' => '🌈',
                'color' => '#ff8000'
            ),
            'soft' => array(
                'name' => __('Suave', 'cct'),
                'description' => __('Gradientes suaves e delicados', 'cct'),
                'icon' => '☁️',
                'color' => '#ffecd2'
            )
        );
    }
    
    /**
     * Inicializa configurações do gerador
     */
    private function init_generator_settings() {
        $this->generator_settings = array(
            'types' => array(
                'linear' => array(
                    'name' => __('Linear', 'cct'),
                    'description' => __('Gradiente linear em linha reta', 'cct'),
                    'directions' => array(
                        '0deg' => __('Para cima', 'cct'),
                        '45deg' => __('Diagonal superior direita', 'cct'),
                        '90deg' => __('Para direita', 'cct'),
                        '135deg' => __('Diagonal inferior direita', 'cct'),
                        '180deg' => __('Para baixo', 'cct'),
                        '225deg' => __('Diagonal inferior esquerda', 'cct'),
                        '270deg' => __('Para esquerda', 'cct'),
                        '315deg' => __('Diagonal superior esquerda', 'cct')
                    )
                ),
                'radial' => array(
                    'name' => __('Radial', 'cct'),
                    'description' => __('Gradiente radial do centro para fora', 'cct'),
                    'shapes' => array(
                        'circle' => __('Círculo', 'cct'),
                        'ellipse' => __('Elipse', 'cct')
                    ),
                    'positions' => array(
                        'center' => __('Centro', 'cct'),
                        'top' => __('Topo', 'cct'),
                        'bottom' => __('Base', 'cct'),
                        'left' => __('Esquerda', 'cct'),
                        'right' => __('Direita', 'cct'),
                        'top left' => __('Topo esquerda', 'cct'),
                        'top right' => __('Topo direita', 'cct'),
                        'bottom left' => __('Base esquerda', 'cct'),
                        'bottom right' => __('Base direita', 'cct')
                    )
                ),
                'conic' => array(
                    'name' => __('Cônico', 'cct'),
                    'description' => __('Gradiente cônico rotacional', 'cct'),
                    'angles' => array(
                        '0deg' => __('0 graus', 'cct'),
                        '45deg' => __('45 graus', 'cct'),
                        '90deg' => __('90 graus', 'cct'),
                        '135deg' => __('135 graus', 'cct'),
                        '180deg' => __('180 graus', 'cct'),
                        '225deg' => __('225 graus', 'cct'),
                        '270deg' => __('270 graus', 'cct'),
                        '315deg' => __('315 graus', 'cct')
                    )
                )
            ),
            'color_stops' => array(
                'min' => 2,
                'max' => 10,
                'default' => 2
            ),
            'export_formats' => array(
                'css' => __('CSS', 'cct'),
                'scss' => __('SCSS', 'cct'),
                'json' => __('JSON', 'cct'),
                'svg' => __('SVG', 'cct')
            )
        );
    }
    
    /**
     * Inicializa presets populares
     */
    private function init_popular_presets() {
        $this->popular_presets = array(
            'trending' => array(
                'name' => __('Tendências 2024', 'cct'),
                'gradients' => array('neon', 'cyberpunk', 'aurora', 'cosmic')
            ),
            'classic' => array(
                'name' => __('Clássicos', 'cct'),
                'gradients' => array('sunset', 'ocean', 'forest', 'fire')
            ),
            'business' => array(
                'name' => __('Profissional', 'cct'),
                'gradients' => array('gold', 'silver', 'ocean', 'forest')
            ),
            'creative' => array(
                'name' => __('Criativo', 'cct'),
                'gradients' => array('rainbow', 'neon', 'cosmic', 'fire')
            ),
            'minimal' => array(
                'name' => __('Minimalista', 'cct'),
                'gradients' => array('cotton_candy', 'mint', 'bubble', 'silver')
            )
        );
    }
    
    /**
     * Registra configurações no Customizer
     */
    public function register_customizer() {
        $this->add_gradient_panel();
        $this->add_gradient_sections();
        $this->add_gradient_settings();
        $this->add_gradient_controls();
    }
    
    /**
     * Adiciona painel de gradientes
     */
    private function add_gradient_panel() {
        $this->wp_customize->add_panel($this->prefix . 'panel', array(
            'title' => __('Biblioteca de Gradientes', 'cct'),
            'description' => __('Gerencie gradientes predefinidos e crie gradientes personalizados com o editor visual.', 'cct'),
            'priority' => 170,
            'capability' => 'edit_theme_options',
        ));
    }
    
    /**
     * Adiciona seções de gradientes
     */
    private function add_gradient_sections() {
        // Seção da biblioteca
        $this->wp_customize->add_section($this->prefix . 'library', array(
            'title' => __('Biblioteca de Gradientes', 'cct'),
            'description' => __('Explore e aplique gradientes predefinidos organizados por categoria.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 10,
        ));
        
        // Seção do gerador
        $this->wp_customize->add_section($this->prefix . 'generator', array(
            'title' => __('Gerador de Gradientes', 'cct'),
            'description' => __('Crie gradientes personalizados com o editor visual interativo.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 20,
        ));
        
        // Seção de aplicação
        $this->wp_customize->add_section($this->prefix . 'application', array(
            'title' => __('Aplicação de Gradientes', 'cct'),
            'description' => __('Configure onde e como os gradientes serão aplicados no site.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 30,
        ));
        
        // Seção de configurações
        $this->wp_customize->add_section($this->prefix . 'settings', array(
            'title' => __('Configurações', 'cct'),
            'description' => __('Configurações avançadas da biblioteca de gradientes.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 40,
        ));
    }
    
    /**
     * Adiciona configurações de gradientes
     */
    private function add_gradient_settings() {
        // Configurações da biblioteca
        $this->add_setting('library_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('selected_category', array(
            'default' => 'all',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('favorite_gradients', array(
            'default' => array(),
            'sanitize_callback' => array($this, 'sanitize_json_array'),
        ));
        
        // Configurações do gerador
        $this->add_setting('generator_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('custom_gradients', array(
            'default' => array(),
            'sanitize_callback' => array($this, 'sanitize_json_array'),
        ));
        
        $this->add_setting('current_gradient', array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        // Configurações de aplicação
        $this->add_setting('apply_to_backgrounds', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('apply_to_buttons', array(
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('apply_to_text', array(
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('apply_to_borders', array(
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Configurações avançadas
        $this->add_setting('enable_css_variables', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('enable_fallback_colors', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('optimize_performance', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
    }
    
    /**
     * Adiciona controles de gradientes
     */
    private function add_gradient_controls() {
        // Controles da biblioteca
        $this->add_control('library_enabled', array(
            'label' => __('Ativar Biblioteca de Gradientes', 'cct'),
            'description' => __('Ativa a biblioteca de gradientes predefinidos.', 'cct'),
            'section' => $this->prefix . 'library',
            'type' => 'checkbox',
        ));
        
        $this->add_control('selected_category', array(
            'label' => __('Categoria Ativa', 'cct'),
            'description' => __('Categoria de gradientes atualmente selecionada.', 'cct'),
            'section' => $this->prefix . 'library',
            'type' => 'select',
            'choices' => $this->get_category_choices(),
        ));
        
        // Controles do gerador
        $this->add_control('generator_enabled', array(
            'label' => __('Ativar Gerador de Gradientes', 'cct'),
            'description' => __('Ativa o gerador visual de gradientes personalizados.', 'cct'),
            'section' => $this->prefix . 'generator',
            'type' => 'checkbox',
        ));
        
        // Controles de aplicação
        $this->add_control('apply_to_backgrounds', array(
            'label' => __('Aplicar a Fundos', 'cct'),
            'description' => __('Permite aplicar gradientes como fundo de elementos.', 'cct'),
            'section' => $this->prefix . 'application',
            'type' => 'checkbox',
        ));
        
        $this->add_control('apply_to_buttons', array(
            'label' => __('Aplicar a Botões', 'cct'),
            'description' => __('Permite aplicar gradientes a botões.', 'cct'),
            'section' => $this->prefix . 'application',
            'type' => 'checkbox',
        ));
        
        $this->add_control('apply_to_text', array(
            'label' => __('Aplicar a Texto', 'cct'),
            'description' => __('Permite aplicar gradientes como cor de texto.', 'cct'),
            'section' => $this->prefix . 'application',
            'type' => 'checkbox',
        ));
        
        $this->add_control('apply_to_borders', array(
            'label' => __('Aplicar a Bordas', 'cct'),
            'description' => __('Permite aplicar gradientes a bordas de elementos.', 'cct'),
            'section' => $this->prefix . 'application',
            'type' => 'checkbox',
        ));
        
        // Controles de configurações
        $this->add_control('enable_css_variables', array(
            'label' => __('Ativar Variáveis CSS', 'cct'),
            'description' => __('Gera variáveis CSS para os gradientes.', 'cct'),
            'section' => $this->prefix . 'settings',
            'type' => 'checkbox',
        ));
        
        $this->add_control('enable_fallback_colors', array(
            'label' => __('Ativar Cores de Fallback', 'cct'),
            'description' => __('Gera cores sólidas como fallback para navegadores antigos.', 'cct'),
            'section' => $this->prefix . 'settings',
            'type' => 'checkbox',
        ));
        
        $this->add_control('optimize_performance', array(
            'label' => __('Otimizar Performance', 'cct'),
            'description' => __('Aplica otimizações de performance para gradientes.', 'cct'),
            'section' => $this->prefix . 'settings',
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
     * Obtém opções de categorias
     */
    private function get_category_choices() {
        $choices = array();
        
        if (is_array($this->gradient_categories) && !empty($this->gradient_categories)) {
            foreach ($this->gradient_categories as $key => $category) {
                $choices[$key] = isset($category['name']) ? $category['name'] : $key;
            }
        } else {
            // Fallback com categorias básicas
            $choices = array(
                'all' => __('Todos', 'cct'),
                'warm' => __('Quentes', 'cct'),
                'cool' => __('Frios', 'cct'),
                'nature' => __('Natureza', 'cct'),
                'modern' => __('Moderno', 'cct'),
            );
        }
        
        return $choices;
    }
    
    /**
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        // CSS dos gradientes
        wp_enqueue_style(
            'cct-gradients',
            get_template_directory_uri() . '/css/cct-gradients.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript dos gradientes
        wp_enqueue_script(
            'cct-gradients',
            get_template_directory_uri() . '/js/cct-gradients.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localização do script
        wp_localize_script('cct-gradients', 'cctGradients', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_gradients_nonce'),
            'settings' => $this->get_frontend_settings(),
            'library' => $this->gradient_library,
            'categories' => $this->gradient_categories,
        ));
    }
    
    /**
     * Obtém configurações para o frontend
     */
    private function get_frontend_settings() {
        return array(
            'libraryEnabled' => get_theme_mod($this->prefix . 'library_enabled', true),
            'generatorEnabled' => get_theme_mod($this->prefix . 'generator_enabled', true),
            'selectedCategory' => get_theme_mod($this->prefix . 'selected_category', 'all'),
            'favoriteGradients' => get_theme_mod($this->prefix . 'favorite_gradients', array()),
            'customGradients' => get_theme_mod($this->prefix . 'custom_gradients', array()),
            'currentGradient' => get_theme_mod($this->prefix . 'current_gradient', ''),
            'applyToBackgrounds' => get_theme_mod($this->prefix . 'apply_to_backgrounds', true),
            'applyToButtons' => get_theme_mod($this->prefix . 'apply_to_buttons', false),
            'applyToText' => get_theme_mod($this->prefix . 'apply_to_text', false),
            'applyToBorders' => get_theme_mod($this->prefix . 'apply_to_borders', false),
            'enableCssVariables' => get_theme_mod($this->prefix . 'enable_css_variables', true),
            'enableFallbackColors' => get_theme_mod($this->prefix . 'enable_fallback_colors', true),
            'optimizePerformance' => get_theme_mod($this->prefix . 'optimize_performance', true),
        );
    }
    
    /**
     * Gera CSS customizado
     */
    public function output_custom_css() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['libraryEnabled']) {
            return;
        }
        
        echo "<style id='cct-gradients-custom-css'>\n";
        
        // CSS base para gradientes
        echo ":root {\n";
        
        // Gerar variáveis CSS para gradientes
        if ($settings['enableCssVariables'] && is_array($this->gradient_library)) {
            foreach ($this->gradient_library as $key => $gradient) {
                echo "  --cct-gradient-{$key}: {$gradient['css']};\n";
                
                // Fallback colors
                if ($settings['enableFallbackColors'] && !empty($gradient['colors'])) {
                    $fallback_color = $gradient['colors'][0]['color'];
                    echo "  --cct-gradient-{$key}-fallback: {$fallback_color};\n";
                }
            }
        }
        
        echo "}\n";
        
        // Classes utilitárias para gradientes
        if (is_array($this->gradient_library)) {
            foreach ($this->gradient_library as $key => $gradient) {
                // Background gradients
                if ($settings['applyToBackgrounds']) {
                    echo ".cct-bg-gradient-{$key} {\n";
                    if ($settings['enableFallbackColors'] && !empty($gradient['colors'])) {
                        echo "  background-color: {$gradient['colors'][0]['color']};\n";
                    }
                    echo "  background: {$gradient['css']};\n";
                    echo "}\n";
                }
                
                // Text gradients
                if ($settings['applyToText']) {
                    echo ".cct-text-gradient-{$key} {\n";
                    if ($settings['enableFallbackColors'] && !empty($gradient['colors'])) {
                        echo "  color: {$gradient['colors'][0]['color']};\n";
                    }
                    echo "  background: {$gradient['css']};\n";
                    echo "  -webkit-background-clip: text;\n";
                    echo "  -webkit-text-fill-color: transparent;\n";
                    echo "  background-clip: text;\n";
                    echo "}\n";
                }
                
                // Border gradients
                if ($settings['applyToBorders']) {
                    echo ".cct-border-gradient-{$key} {\n";
                    echo "  border: 2px solid;\n";
                    if ($settings['enableFallbackColors'] && !empty($gradient['colors'])) {
                        echo "  border-color: {$gradient['colors'][0]['color']};\n";
                    }
                    echo "  border-image: {$gradient['css']} 1;\n";
                    echo "}\n";
                }
            }
        }
        
        // Otimizações de performance
        if ($settings['optimizePerformance']) {
            echo ".cct-gradient-optimized {\n";
            echo "  will-change: background;\n";
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
        
        if (!$settings['libraryEnabled']) {
            return;
        }
        
        echo "<script id='cct-gradients-custom-js'>\n";
        echo "document.addEventListener('DOMContentLoaded', function() {\n";
        echo "  if (typeof CCTGradients !== 'undefined') {\n";
        echo "    CCTGradients.init(" . wp_json_encode($settings) . ");\n";
        echo "  }\n";
        echo "});\n";
        echo "</script>\n";
    }
    
    /**
     * Shortcode para gradientes
     */
    public function gradient_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'name' => '',
            'type' => 'background',
            'class' => '',
            'style' => '',
        ), $atts, 'cct_gradient');
        
        if (empty($atts['name']) || !isset($this->gradient_library[$atts['name']])) {
            return $content;
        }
        
        $gradient = $this->gradient_library[$atts['name']];
        $classes = array('cct-gradient');
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $style_attrs = array();
        
        switch ($atts['type']) {
            case 'background':
                $style_attrs[] = "background: {$gradient['css']}";
                break;
            case 'text':
                $style_attrs[] = "background: {$gradient['css']}";
                $style_attrs[] = "-webkit-background-clip: text";
                $style_attrs[] = "-webkit-text-fill-color: transparent";
                $style_attrs[] = "background-clip: text";
                break;
            case 'border':
                $style_attrs[] = "border: 2px solid";
                $style_attrs[] = "border-image: {$gradient['css']} 1";
                break;
        }
        
        if (!empty($atts['style'])) {
            $style_attrs[] = esc_attr($atts['style']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '"';
        
        if (!empty($style_attrs)) {
            $output .= ' style="' . implode('; ', $style_attrs) . '"';
        }
        
        $output .= '>';
        $output .= do_shortcode($content);
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para texto com gradiente
     */
    public function gradient_text_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'gradient' => 'sunset',
            'class' => '',
        ), $atts, 'cct_gradient_text');
        
        if (!isset($this->gradient_library[$atts['gradient']])) {
            return $content;
        }
        
        $gradient = $this->gradient_library[$atts['gradient']];
        $classes = array('cct-gradient-text');
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $style = "background: {$gradient['css']}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;";
        
        return '<span class="' . implode(' ', $classes) . '" style="' . $style . '">' . do_shortcode($content) . '</span>';
    }
    
    /**
     * Shortcode para botão com gradiente
     */
    public function gradient_button_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'gradient' => 'sunset',
            'href' => '#',
            'target' => '_self',
            'class' => '',
        ), $atts, 'cct_gradient_button');
        
        if (!isset($this->gradient_library[$atts['gradient']])) {
            return $content;
        }
        
        $gradient = $this->gradient_library[$atts['gradient']];
        $classes = array('cct-gradient-button');
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $style = "background: {$gradient['css']}; border: none; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: inline-block; transition: all 0.3s ease;";
        
        return '<a href="' . esc_url($atts['href']) . '" target="' . esc_attr($atts['target']) . '" class="' . implode(' ', $classes) . '" style="' . $style . '">' . do_shortcode($content) . '</a>';
    }
    
    /**
     * AJAX handler para gerar gradiente
     */
    public function ajax_generate_gradient() {
        check_ajax_referer('cct_gradients_nonce', 'nonce');
        
        $type = sanitize_text_field($_POST['type'] ?? 'linear');
        $colors = $this->sanitize_json_array($_POST['colors'] ?? array());
        $direction = sanitize_text_field($_POST['direction'] ?? '45deg');
        
        if (empty($colors) || count($colors) < 2) {
            wp_die(__('Pelo menos duas cores são necessárias.', 'cct'));
        }
        
        $css = $this->generate_gradient_css($type, $colors, $direction);
        
        $response = array(
            'success' => true,
            'data' => array(
                'css' => $css,
                'type' => $type,
                'colors' => $colors,
                'direction' => $direction
            )
        );
        
        wp_send_json($response);
    }
    
    /**
     * AJAX handler para salvar gradiente
     */
    public function ajax_save_gradient() {
        check_ajax_referer('cct_gradients_nonce', 'nonce');
        
        $name = sanitize_text_field($_POST['name'] ?? '');
        $gradient_data = $this->sanitize_json_array($_POST['gradient'] ?? array());
        
        if (empty($name) || empty($gradient_data)) {
            wp_die(__('Nome e dados do gradiente são obrigatórios.', 'cct'));
        }
        
        $custom_gradients = get_theme_mod($this->prefix . 'custom_gradients', array());
        $custom_gradients[$name] = $gradient_data;
        
        set_theme_mod($this->prefix . 'custom_gradients', $custom_gradients);
        
        $response = array(
            'success' => true,
            'message' => __('Gradiente salvo com sucesso!', 'cct')
        );
        
        wp_send_json($response);
    }
    
    /**
     * AJAX handler para exportar gradientes
     */
    public function ajax_export_gradients() {
        check_ajax_referer('cct_gradients_nonce', 'nonce');
        
        $format = sanitize_text_field($_POST['format'] ?? 'css');
        $gradients = $_POST['gradients'] ?? array();
        
        if (empty($gradients)) {
            wp_die(__('Nenhum gradiente selecionado para exportar.', 'cct'));
        }
        
        $export_data = $this->export_gradients($gradients, $format);
        
        $response = array(
            'success' => true,
            'data' => $export_data,
            'format' => $format
        );
        
        wp_send_json($response);
    }
    
    /**
     * Gera CSS para gradiente
     */
    private function generate_gradient_css($type, $colors, $direction) {
        $color_stops = array();
        
        foreach ($colors as $color) {
            $color_value = sanitize_hex_color($color['color'] ?? '#000000');
            $position = intval($color['position'] ?? 0);
            $color_stops[] = "{$color_value} {$position}%";
        }
        
        $color_string = implode(', ', $color_stops);
        
        switch ($type) {
            case 'radial':
                return "radial-gradient(circle at center, {$color_string})";
            case 'conic':
                return "conic-gradient(from {$direction} at center, {$color_string})";
            default:
                return "linear-gradient({$direction}, {$color_string})";
        }
    }
    
    /**
     * Exporta gradientes em formato específico
     */
    private function export_gradients($gradient_names, $format) {
        $export_data = '';
        
        switch ($format) {
            case 'css':
                $export_data = $this->export_to_css($gradient_names);
                break;
            case 'scss':
                $export_data = $this->export_to_scss($gradient_names);
                break;
            case 'json':
                $export_data = $this->export_to_json($gradient_names);
                break;
            case 'svg':
                $export_data = $this->export_to_svg($gradient_names);
                break;
        }
        
        return $export_data;
    }
    
    /**
     * Exporta para CSS
     */
    private function export_to_css($gradient_names) {
        $css = "/* CCT Gradients Export */\n\n";
        $css .= ":root {\n";
        
        foreach ($gradient_names as $name) {
            if (isset($this->gradient_library[$name])) {
                $gradient = $this->gradient_library[$name];
                $css .= "  --gradient-{$name}: {$gradient['css']};\n";
            }
        }
        
        $css .= "}\n\n";
        
        foreach ($gradient_names as $name) {
            if (isset($this->gradient_library[$name])) {
                $gradient = $this->gradient_library[$name];
                $css .= ".bg-gradient-{$name} {\n";
                $css .= "  background: {$gradient['css']};\n";
                $css .= "}\n\n";
            }
        }
        
        return $css;
    }
    
    /**
     * Exporta para SCSS
     */
    private function export_to_scss($gradient_names) {
        $scss = "// CCT Gradients Export\n\n";
        
        foreach ($gradient_names as $name) {
            if (isset($this->gradient_library[$name])) {
                $gradient = $this->gradient_library[$name];
                $scss .= "\$gradient-{$name}: {$gradient['css']};\n";
            }
        }
        
        $scss .= "\n";
        
        foreach ($gradient_names as $name) {
            if (isset($this->gradient_library[$name])) {
                $scss .= ".bg-gradient-{$name} {\n";
                $scss .= "  background: \$gradient-{$name};\n";
                $scss .= "}\n\n";
            }
        }
        
        return $scss;
    }
    
    /**
     * Exporta para JSON
     */
    private function export_to_json($gradient_names) {
        $gradients = array();
        
        foreach ($gradient_names as $name) {
            if (isset($this->gradient_library[$name])) {
                $gradients[$name] = $this->gradient_library[$name];
            }
        }
        
        return wp_json_encode($gradients, JSON_PRETTY_PRINT);
    }
    
    /**
     * Exporta para SVG
     */
    private function export_to_svg($gradient_names) {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">' . "\n";
        $svg .= '  <defs>' . "\n";
        
        foreach ($gradient_names as $name) {
            if (isset($this->gradient_library[$name])) {
                $gradient = $this->gradient_library[$name];
                $svg .= "    <linearGradient id=\"gradient-{$name}\" x1=\"0%\" y1=\"0%\" x2=\"100%\" y2=\"100%\">\n";
                
                if (!empty($gradient['colors'])) {
                    foreach ($gradient['colors'] as $color) {
                        $svg .= "      <stop offset=\"{$color['position']}\" style=\"stop-color:{$color['color']};stop-opacity:1\" />\n";
                    }
                }
                
                $svg .= "    </linearGradient>\n";
            }
        }
        
        $svg .= '  </defs>' . "\n";
        $svg .= '</svg>';
        
        return $svg;
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
     * Obtém gradiente por nome
     */
    public function get_gradient($name) {
        return isset($this->gradient_library[$name]) ? $this->gradient_library[$name] : null;
    }
    
    /**
     * Obtém gradientes por categoria
     */
    public function get_gradients_by_category($category = 'all') {
        if ($category === 'all') {
            return $this->gradient_library;
        }
        
        $filtered = array();
        
        if (is_array($this->gradient_library)) {
            foreach ($this->gradient_library as $key => $gradient) {
                if (isset($gradient['category']) && $gradient['category'] === $category) {
                    $filtered[$key] = $gradient;
                }
            }
        }
        
        return $filtered;
    }
    
    /**
     * Obtém estatísticas dos gradientes
     */
    public function get_gradient_stats() {
        return array(
            'total_gradients' => is_array($this->gradient_library) ? count($this->gradient_library) : 0,
            'total_categories' => is_array($this->gradient_categories) ? count($this->gradient_categories) : 0,
            'custom_gradients' => count(get_theme_mod($this->prefix . 'custom_gradients', array())),
            'favorite_gradients' => count(get_theme_mod($this->prefix . 'favorite_gradients', array())),
        );
    }
    
    /**
     * Exporta configurações de gradientes
     */
    public function export_gradient_settings() {
        $settings = array();
        
        // Obter todas as configurações do tema relacionadas a gradientes
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
            'library' => $this->gradient_library,
            'categories' => $this->gradient_categories,
            'stats' => $this->get_gradient_stats(),
        );
    }
}