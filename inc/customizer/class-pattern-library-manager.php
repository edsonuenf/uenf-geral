<?php
/**
 * Biblioteca de Padrões - Gerenciador Principal
 * 
 * Sistema completo de padrões de blocos incluindo:
 * - Seções FAQ (Perguntas Frequentes)
 * - Seções Pricing (Tabelas de Preços)
 * - Seções Team (Equipe)
 * - Seções Portfolio (Portfólio)
 * - Templates prontos para uso
 * - Configurações customizáveis
 * - Preview em tempo real
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
 * Classe principal da Biblioteca de Padrões
 */
class CCT_Pattern_Library_Manager {
    
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
    private $prefix = 'cct_patterns_';
    
    /**
     * ID do painel de design
     * 
     * @var string
     */
    private $panel_id = 'cct_design_panel';
    
    /**
     * Padrões de FAQ
     * 
     * @var array
     */
    private $faq_patterns;
    
    /**
     * Padrões de Pricing
     * 
     * @var array
     */
    private $pricing_patterns;
    
    /**
     * Padrões de Team
     * 
     * @var array
     */
    private $team_patterns;
    
    /**
     * Padrões de Portfolio
     * 
     * @var array
     */
    private $portfolio_patterns;
    
    /**
     * Configurações de estilo
     * 
     * @var array
     */
    private $style_settings;
    
    /**
     * Templates disponíveis
     * 
     * @var array
     */
    private $available_templates;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->init_faq_patterns();
        $this->init_pricing_patterns();
        $this->init_team_patterns();
        $this->init_portfolio_patterns();
        $this->init_style_settings();
        $this->init_available_templates();
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
        add_shortcode('cct_faq_section', array($this, 'faq_section_shortcode'));
        add_shortcode('cct_faq', array($this, 'faq_item_shortcode'));
        add_shortcode('cct_pricing_table', array($this, 'pricing_table_shortcode'));
        add_shortcode('cct_pricing_plan', array($this, 'pricing_plan_shortcode'));
        add_shortcode('cct_team_grid', array($this, 'team_grid_shortcode'));
        add_shortcode('cct_team_member', array($this, 'team_member_shortcode'));
        add_shortcode('cct_portfolio_gallery', array($this, 'portfolio_gallery_shortcode'));
        add_shortcode('cct_portfolio_item', array($this, 'portfolio_item_shortcode'));
        
        // AJAX handlers
        add_action('wp_ajax_cct_preview_pattern', array($this, 'ajax_preview_pattern'));
        add_action('wp_ajax_nopriv_cct_preview_pattern', array($this, 'ajax_preview_pattern'));
        add_action('wp_ajax_cct_export_pattern', array($this, 'ajax_export_pattern'));
        add_action('wp_ajax_cct_import_pattern', array($this, 'ajax_import_pattern'));
    }
    
    /**
     * Inicializa padrões de FAQ
     */
    private function init_faq_patterns() {
        $this->faq_patterns = array(
            'accordion' => array(
                'name' => __('Accordion FAQ', 'cct'),
                'description' => __('FAQ em formato accordion expansível', 'cct'),
                'template' => 'accordion',
                'features' => array(
                    __('Expansão suave', 'cct'),
                    __('Ícones personalizáveis', 'cct'),
                    __('Busca integrada', 'cct'),
                    __('Categorização', 'cct')
                ),
                'preview_image' => 'faq-accordion.svg',
                'settings' => array(
                    'animation_duration' => 0.3,
                    'expand_icon' => 'plus',
                    'collapse_icon' => 'minus',
                    'allow_multiple' => false,
                    'search_enabled' => true,
                    'categories_enabled' => true
                )
            ),
            'tabs' => array(
                'name' => __('Tabs FAQ', 'cct'),
                'description' => __('FAQ organizado em abas por categoria', 'cct'),
                'template' => 'tabs',
                'features' => array(
                    __('Navegação por abas', 'cct'),
                    __('Categorização automática', 'cct'),
                    __('Design responsivo', 'cct'),
                    __('Busca global', 'cct')
                ),
                'preview_image' => 'faq-tabs.svg',
                'settings' => array(
                    'tab_position' => 'top',
                    'tab_style' => 'pills',
                    'search_enabled' => true,
                    'auto_height' => true
                )
            ),
            'grid' => array(
                'name' => __('Grid FAQ', 'cct'),
                'description' => __('FAQ em layout de grid com cards', 'cct'),
                'template' => 'grid',
                'features' => array(
                    __('Layout em grid', 'cct'),
                    __('Cards interativos', 'cct'),
                    __('Filtros por categoria', 'cct'),
                    __('Modal para respostas longas', 'cct')
                ),
                'preview_image' => 'faq-grid.svg',
                'settings' => array(
                    'columns' => 3,
                    'card_style' => 'elevated',
                    'modal_enabled' => true,
                    'filters_enabled' => true
                )
            )
        );
    }
    
    /**
     * Inicializa padrões de Pricing
     */
    private function init_pricing_patterns() {
        $this->pricing_patterns = array(
            'cards' => array(
                'name' => __('Pricing Cards', 'cct'),
                'description' => __('Tabela de preços em formato de cards', 'cct'),
                'template' => 'cards',
                'features' => array(
                    __('Cards responsivos', 'cct'),
                    __('Destaque de plano popular', 'cct'),
                    __('Toggle mensal/anual', 'cct'),
                    __('Botões call-to-action', 'cct')
                ),
                'preview_image' => 'pricing-cards.svg',
                'settings' => array(
                    'columns' => 3,
                    'popular_highlight' => true,
                    'billing_toggle' => true,
                    'currency_symbol' => 'R$',
                    'currency_position' => 'before'
                )
            ),
            'table' => array(
                'name' => __('Pricing Table', 'cct'),
                'description' => __('Tabela comparativa de recursos', 'cct'),
                'template' => 'table',
                'features' => array(
                    __('Comparação de recursos', 'cct'),
                    __('Scroll horizontal', 'cct'),
                    __('Destaque de diferenças', 'cct'),
                    __('Tooltips explicativos', 'cct')
                ),
                'preview_image' => 'pricing-table.svg',
                'settings' => array(
                    'sticky_header' => true,
                    'highlight_differences' => true,
                    'tooltips_enabled' => true,
                    'mobile_scroll' => true
                )
            ),
            'slider' => array(
                'name' => __('Pricing Slider', 'cct'),
                'description' => __('Slider interativo de preços', 'cct'),
                'template' => 'slider',
                'features' => array(
                    __('Slider de quantidade', 'cct'),
                    __('Cálculo dinâmico', 'cct'),
                    __('Desconto por volume', 'cct'),
                    __('Preview em tempo real', 'cct')
                ),
                'preview_image' => 'pricing-slider.svg',
                'settings' => array(
                    'min_value' => 1,
                    'max_value' => 100,
                    'step' => 1,
                    'volume_discount' => true,
                    'real_time_calc' => true
                )
            )
        );
    }
    
    /**
     * Inicializa padrões de Team
     */
    private function init_team_patterns() {
        $this->team_patterns = array(
            'grid' => array(
                'name' => __('Team Grid', 'cct'),
                'description' => __('Grid de membros da equipe com cards', 'cct'),
                'template' => 'grid',
                'features' => array(
                    __('Cards de membros', 'cct'),
                    __('Hover effects', 'cct'),
                    __('Redes sociais', 'cct'),
                    __('Modal com biografia', 'cct')
                ),
                'preview_image' => 'team-grid.svg',
                'settings' => array(
                    'columns' => 4,
                    'card_style' => 'modern',
                    'hover_effect' => 'lift',
                    'social_links' => true,
                    'bio_modal' => true
                )
            ),
            'carousel' => array(
                'name' => __('Team Carousel', 'cct'),
                'description' => __('Carousel deslizante de membros', 'cct'),
                'template' => 'carousel',
                'features' => array(
                    __('Navegação por slides', 'cct'),
                    __('Auto-play opcional', 'cct'),
                    __('Indicadores de posição', 'cct'),
                    __('Touch/swipe support', 'cct')
                ),
                'preview_image' => 'team-carousel.svg',
                'settings' => array(
                    'slides_per_view' => 3,
                    'autoplay' => false,
                    'autoplay_delay' => 3000,
                    'navigation' => true,
                    'pagination' => true
                )
            ),
            'list' => array(
                'name' => __('Team List', 'cct'),
                'description' => __('Lista detalhada de membros', 'cct'),
                'template' => 'list',
                'features' => array(
                    __('Layout em lista', 'cct'),
                    __('Informações detalhadas', 'cct'),
                    __('Filtros por departamento', 'cct'),
                    __('Busca por nome', 'cct')
                ),
                'preview_image' => 'team-list.svg',
                'settings' => array(
                    'show_bio' => true,
                    'show_contact' => true,
                    'department_filter' => true,
                    'search_enabled' => true
                )
            )
        );
    }
    
    /**
     * Inicializa padrões de Portfolio
     */
    private function init_portfolio_patterns() {
        $this->portfolio_patterns = array(
            'masonry' => array(
                'name' => __('Masonry Portfolio', 'cct'),
                'description' => __('Grid masonry com filtros', 'cct'),
                'template' => 'masonry',
                'features' => array(
                    __('Layout masonry', 'cct'),
                    __('Filtros por categoria', 'cct'),
                    __('Lightbox integrado', 'cct'),
                    __('Lazy loading', 'cct')
                ),
                'preview_image' => 'portfolio-masonry.svg',
                'settings' => array(
                    'columns' => 3,
                    'gutter' => 20,
                    'filters_enabled' => true,
                    'lightbox_enabled' => true,
                    'lazy_load' => true
                )
            ),
            'slider' => array(
                'name' => __('Portfolio Slider', 'cct'),
                'description' => __('Slider de projetos com navegação', 'cct'),
                'template' => 'slider',
                'features' => array(
                    __('Slides de projetos', 'cct'),
                    __('Navegação por thumbnails', 'cct'),
                    __('Transições suaves', 'cct'),
                    __('Fullscreen mode', 'cct')
                ),
                'preview_image' => 'portfolio-slider.svg',
                'settings' => array(
                    'transition_effect' => 'slide',
                    'thumbnail_nav' => true,
                    'fullscreen_mode' => true,
                    'autoplay' => false
                )
            ),
            'showcase' => array(
                'name' => __('Portfolio Showcase', 'cct'),
                'description' => __('Showcase destacado de projetos', 'cct'),
                'template' => 'showcase',
                'features' => array(
                    __('Projeto em destaque', 'cct'),
                    __('Galeria lateral', 'cct'),
                    __('Informações detalhadas', 'cct'),
                    __('Links para projeto', 'cct')
                ),
                'preview_image' => 'portfolio-showcase.svg',
                'settings' => array(
                    'featured_size' => 'large',
                    'sidebar_position' => 'right',
                    'show_details' => true,
                    'external_links' => true
                )
            )
        );
    }
    
    /**
     * Inicializa configurações de estilo
     */
    private function init_style_settings() {
        $this->style_settings = array(
            'colors' => array(
                'primary' => '#0073aa',
                'secondary' => '#666666',
                'accent' => '#ff6b6b',
                'background' => '#ffffff',
                'text' => '#333333',
                'border' => '#e0e0e0'
            ),
            'typography' => array(
                'heading_font' => 'Roboto',
                'body_font' => 'Open Sans',
                'heading_weight' => '600',
                'body_weight' => '400',
                'heading_size' => '24px',
                'body_size' => '16px'
            ),
            'spacing' => array(
                'section_padding' => '60px',
                'element_margin' => '20px',
                'card_padding' => '30px',
                'border_radius' => '8px'
            ),
            'animations' => array(
                'enabled' => true,
                'duration' => '0.3s',
                'easing' => 'ease-in-out',
                'hover_effects' => true
            )
        );
    }
    
    /**
     * Inicializa templates disponíveis
     */
    private function init_available_templates() {
        $this->available_templates = array(
            'business' => array(
                'name' => __('Business', 'cct'),
                'description' => __('Template para sites corporativos', 'cct'),
                'sections' => array('faq-accordion', 'pricing-cards', 'team-grid'),
                'colors' => array(
                    'primary' => '#2c3e50',
                    'secondary' => '#34495e',
                    'accent' => '#3498db'
                )
            ),
            'creative' => array(
                'name' => __('Creative', 'cct'),
                'description' => __('Template para agências criativas', 'cct'),
                'sections' => array('portfolio-masonry', 'team-carousel', 'faq-tabs'),
                'colors' => array(
                    'primary' => '#e74c3c',
                    'secondary' => '#f39c12',
                    'accent' => '#9b59b6'
                )
            ),
            'startup' => array(
                'name' => __('Startup', 'cct'),
                'description' => __('Template para startups e SaaS', 'cct'),
                'sections' => array('pricing-slider', 'faq-grid', 'team-list'),
                'colors' => array(
                    'primary' => '#1abc9c',
                    'secondary' => '#16a085',
                    'accent' => '#f1c40f'
                )
            ),
            'portfolio' => array(
                'name' => __('Portfolio', 'cct'),
                'description' => __('Template para portfólios pessoais', 'cct'),
                'sections' => array('portfolio-showcase', 'faq-accordion'),
                'colors' => array(
                    'primary' => '#34495e',
                    'secondary' => '#7f8c8d',
                    'accent' => '#e67e22'
                )
            )
        );
    }
    
    /**
     * Registra configurações no Customizer
     */
    public function register_customizer() {
        $this->add_pattern_panel();
        $this->add_pattern_sections();
        $this->add_pattern_settings();
        $this->add_pattern_controls();
    }
    
    /**
     * Adiciona painel de padrões (agora como seção no painel Design)
     */
    private function add_pattern_panel() {
        // A biblioteca de padrões agora é uma seção dentro do painel Design
        // O painel Design é criado pelo Design_Panel_Manager
    }
    
    /**
     * Adiciona seções de padrões
     */
    private function add_pattern_sections() {
        // Seção principal de Padrões no painel Design
        $this->wp_customize->add_section($this->prefix . 'main', array(
            'title' => __('Padrões', 'cct'),
            'description' => __('Biblioteca de padrões com seções prontas para FAQ, Pricing, Team e Portfolio.', 'cct'),
            'panel' => 'cct_design_panel',
            'priority' => 30,
        ));
        
        // Seção de configurações gerais
        $this->wp_customize->add_section($this->prefix . 'general', array(
            'title' => __('Configurações Gerais', 'cct'),
            'description' => __('Configurações globais da biblioteca de padrões.', 'cct'),
            'panel' => 'cct_design_panel',
            'priority' => 31,
        ));
        
        // Seção FAQ
        $this->wp_customize->add_section($this->prefix . 'faq', array(
            'title' => __('Padrões FAQ', 'cct'),
            'description' => __('Seções de perguntas frequentes com diferentes layouts.', 'cct'),
            'panel' => 'cct_design_panel',
            'priority' => 32,
        ));
        
        // Seção Pricing
        $this->wp_customize->add_section($this->prefix . 'pricing', array(
            'title' => __('Padrões Pricing', 'cct'),
            'description' => __('Tabelas de preços e planos com layouts variados.', 'cct'),
            'panel' => 'cct_design_panel',
            'priority' => 33,
        ));
        
        // Seção Team
        $this->wp_customize->add_section($this->prefix . 'team', array(
            'title' => __('Padrões Team', 'cct'),
            'description' => __('Seções de equipe com diferentes apresentações.', 'cct'),
            'panel' => 'cct_design_panel',
            'priority' => 34,
        ));
        
        // Seção Portfolio
        $this->wp_customize->add_section($this->prefix . 'portfolio', array(
            'title' => __('Padrões Portfolio', 'cct'),
            'description' => __('Galerias de projetos com layouts profissionais.', 'cct'),
            'panel' => 'cct_design_panel',
            'priority' => 35,
        ));
        
        // Seção de estilos
        $this->wp_customize->add_section($this->prefix . 'styles', array(
            'title' => __('Estilos Globais', 'cct'),
            'description' => __('Configurações de cores, tipografia e espaçamentos.', 'cct'),
            'panel' => 'cct_design_panel',
            'priority' => 36,
        ));
        
        // Seção de templates
        $this->wp_customize->add_section($this->prefix . 'templates', array(
            'title' => __('Templates Prontos', 'cct'),
            'description' => __('Templates completos para diferentes tipos de site.', 'cct'),
            'panel' => 'cct_design_panel',
            'priority' => 37,
        ));
    }
    
    /**
     * Adiciona configurações de padrões
     */
    private function add_pattern_settings() {
        // Configurações gerais
        $this->add_setting('patterns_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('lazy_loading', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('animations_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Configurações de FAQ
        $this->add_setting('faq_active_pattern', array(
            'default' => 'accordion',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('faq_search_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('faq_categories_enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Configurações de Pricing
        $this->add_setting('pricing_active_pattern', array(
            'default' => 'cards',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('pricing_currency', array(
            'default' => 'R$',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('pricing_billing_toggle', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Configurações de Team
        $this->add_setting('team_active_pattern', array(
            'default' => 'grid',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('team_social_links', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('team_bio_modal', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Configurações de Portfolio
        $this->add_setting('portfolio_active_pattern', array(
            'default' => 'masonry',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('portfolio_lightbox', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('portfolio_filters', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        // Configurações de estilo
        foreach ($this->style_settings['colors'] as $color_key => $color_value) {
            $this->add_setting('color_' . $color_key, array(
                'default' => $color_value,
                'sanitize_callback' => 'sanitize_hex_color',
            ));
        }
        
        // Template ativo
        $this->add_setting('active_template', array(
            'default' => 'business',
            'sanitize_callback' => 'sanitize_text_field',
        ));
    }
    
    /**
     * Adiciona controles de padrões
     */
    private function add_pattern_controls() {
        // Controles gerais
        $this->add_control('patterns_enabled', array(
            'label' => __('Ativar Biblioteca de Padrões', 'cct'),
            'description' => __('Ativa ou desativa a biblioteca de padrões globalmente.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('lazy_loading', array(
            'label' => __('Lazy Loading', 'cct'),
            'description' => __('Carrega imagens e conteúdo sob demanda.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('animations_enabled', array(
            'label' => __('Ativar Animações', 'cct'),
            'description' => __('Ativa animações e transições nos padrões.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        // Controles FAQ
        $this->add_control('faq_active_pattern', array(
            'label' => __('Padrão FAQ Ativo', 'cct'),
            'description' => __('Escolha o layout para seções FAQ.', 'cct'),
            'section' => $this->prefix . 'faq',
            'type' => 'select',
            'choices' => $this->get_pattern_choices('faq'),
        ));
        
        $this->add_control('faq_search_enabled', array(
            'label' => __('Busca em FAQ', 'cct'),
            'description' => __('Adiciona campo de busca nas seções FAQ.', 'cct'),
            'section' => $this->prefix . 'faq',
            'type' => 'checkbox',
        ));
        
        $this->add_control('faq_categories_enabled', array(
            'label' => __('Categorias FAQ', 'cct'),
            'description' => __('Organiza FAQ por categorias.', 'cct'),
            'section' => $this->prefix . 'faq',
            'type' => 'checkbox',
        ));
        
        // Controles Pricing
        $this->add_control('pricing_active_pattern', array(
            'label' => __('Padrão Pricing Ativo', 'cct'),
            'description' => __('Escolha o layout para tabelas de preços.', 'cct'),
            'section' => $this->prefix . 'pricing',
            'type' => 'select',
            'choices' => $this->get_pattern_choices('pricing'),
        ));
        
        $this->add_control('pricing_currency', array(
            'label' => __('Símbolo da Moeda', 'cct'),
            'description' => __('Símbolo usado nos preços (ex: R$, $, €).', 'cct'),
            'section' => $this->prefix . 'pricing',
            'type' => 'text',
        ));
        
        $this->add_control('pricing_billing_toggle', array(
            'label' => __('Toggle Mensal/Anual', 'cct'),
            'description' => __('Adiciona toggle para alternar entre preços mensais e anuais.', 'cct'),
            'section' => $this->prefix . 'pricing',
            'type' => 'checkbox',
        ));
        
        // Controles Team
        $this->add_control('team_active_pattern', array(
            'label' => __('Padrão Team Ativo', 'cct'),
            'description' => __('Escolha o layout para seções de equipe.', 'cct'),
            'section' => $this->prefix . 'team',
            'type' => 'select',
            'choices' => $this->get_pattern_choices('team'),
        ));
        
        $this->add_control('team_social_links', array(
            'label' => __('Links de Redes Sociais', 'cct'),
            'description' => __('Mostra ícones de redes sociais nos cards de membros.', 'cct'),
            'section' => $this->prefix . 'team',
            'type' => 'checkbox',
        ));
        
        $this->add_control('team_bio_modal', array(
            'label' => __('Modal de Biografia', 'cct'),
            'description' => __('Abre biografia completa em modal ao clicar no membro.', 'cct'),
            'section' => $this->prefix . 'team',
            'type' => 'checkbox',
        ));
        
        // Controles Portfolio
        $this->add_control('portfolio_active_pattern', array(
            'label' => __('Padrão Portfolio Ativo', 'cct'),
            'description' => __('Escolha o layout para galerias de portfolio.', 'cct'),
            'section' => $this->prefix . 'portfolio',
            'type' => 'select',
            'choices' => $this->get_pattern_choices('portfolio'),
        ));
        
        $this->add_control('portfolio_lightbox', array(
            'label' => __('Lightbox', 'cct'),
            'description' => __('Abre imagens em lightbox ao clicar.', 'cct'),
            'section' => $this->prefix . 'portfolio',
            'type' => 'checkbox',
        ));
        
        $this->add_control('portfolio_filters', array(
            'label' => __('Filtros de Categoria', 'cct'),
            'description' => __('Adiciona filtros para categorizar projetos.', 'cct'),
            'section' => $this->prefix . 'portfolio',
            'type' => 'checkbox',
        ));
        
        // Controles de cores
        foreach ($this->style_settings['colors'] as $color_key => $color_value) {
            $this->add_control('color_' . $color_key, array(
                'label' => $this->get_color_label($color_key),
                'description' => $this->get_color_description($color_key),
                'section' => $this->prefix . 'styles',
                'type' => 'color',
            ));
        }
        
        // Controle de template
        $this->add_control('active_template', array(
            'label' => __('Template Ativo', 'cct'),
            'description' => __('Escolha um template completo para aplicar.', 'cct'),
            'section' => $this->prefix . 'templates',
            'type' => 'select',
            'choices' => $this->get_template_choices(),
        ));
        
        // Controles de conteúdo dos patterns
        $this->add_content_controls();
    }
    
    /**
     * Adiciona controles de edição de conteúdo dos patterns
     */
    private function add_content_controls() {
        // Seção para conteúdo dos patterns
        $this->wp_customize->add_section($this->prefix . 'content', array(
            'title' => __('Conteúdo dos Patterns', 'cct'),
            'description' => __('Edite o conteúdo dos patterns disponíveis.', 'cct'),
            'panel' => $this->panel_id,
            'priority' => 25,
        ));
        
        // Controles para FAQ
        $this->add_faq_content_controls();
        
        // Controles para Pricing
        $this->add_pricing_content_controls();
        
        // Controles para Team
        $this->add_team_content_controls();
        
        // Controles para Portfolio
        $this->add_portfolio_content_controls();
    }
    
    /**
     * Adiciona controles de conteúdo para FAQ
     */
    private function add_faq_content_controls() {
        // Número de FAQs
        $this->add_setting('faq_count', array(
            'default' => 5,
            'sanitize_callback' => 'absint',
        ));
        
        $this->add_control('faq_count', array(
            'label' => __('Número de FAQs', 'cct'),
            'description' => __('Quantas perguntas frequentes exibir.', 'cct'),
            'section' => $this->prefix . 'content',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 20,
                'step' => 1,
            ),
        ));
        
        // FAQs individuais
        for ($i = 1; $i <= 10; $i++) {
            // Pergunta
            $this->add_setting("faq_question_{$i}", array(
                'default' => sprintf(__('Pergunta %d', 'cct'), $i),
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $this->add_control("faq_question_{$i}", array(
                'label' => sprintf(__('Pergunta %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'text',
            ));
            
            // Resposta
            $this->add_setting("faq_answer_{$i}", array(
                'default' => sprintf(__('Resposta para a pergunta %d', 'cct'), $i),
                'sanitize_callback' => 'wp_kses_post',
            ));
            
            $this->add_control("faq_answer_{$i}", array(
                'label' => sprintf(__('Resposta %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'textarea',
            ));
            
            // Categoria
            $this->add_setting("faq_category_{$i}", array(
                'default' => __('Geral', 'cct'),
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $this->add_control("faq_category_{$i}", array(
                'label' => sprintf(__('Categoria %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'text',
            ));
        }
    }
    
    /**
     * Adiciona controles de conteúdo para Pricing
     */
    private function add_pricing_content_controls() {
        // Número de planos
        $this->add_setting('pricing_count', array(
            'default' => 3,
            'sanitize_callback' => 'absint',
        ));
        
        $this->add_control('pricing_count', array(
            'label' => __('Número de Planos', 'cct'),
            'description' => __('Quantos planos de preços exibir.', 'cct'),
            'section' => $this->prefix . 'content',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 6,
                'step' => 1,
            ),
        ));
        
        // Planos individuais
        for ($i = 1; $i <= 6; $i++) {
            // Nome do plano
            $this->add_setting("pricing_plan_name_{$i}", array(
                'default' => sprintf(__('Plano %d', 'cct'), $i),
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $this->add_control("pricing_plan_name_{$i}", array(
                'label' => sprintf(__('Nome do Plano %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'text',
            ));
            
            // Preço
            $this->add_setting("pricing_plan_price_{$i}", array(
                'default' => '99',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $this->add_control("pricing_plan_price_{$i}", array(
                'label' => sprintf(__('Preço do Plano %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'text',
            ));
            
            // Descrição
            $this->add_setting("pricing_plan_description_{$i}", array(
                'default' => sprintf(__('Descrição do plano %d', 'cct'), $i),
                'sanitize_callback' => 'wp_kses_post',
            ));
            
            $this->add_control("pricing_plan_description_{$i}", array(
                'label' => sprintf(__('Descrição do Plano %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'textarea',
            ));
        }
    }
    
    /**
     * Adiciona controles de conteúdo para Team
     */
    private function add_team_content_controls() {
        // Número de membros
        $this->add_setting('team_count', array(
            'default' => 4,
            'sanitize_callback' => 'absint',
        ));
        
        $this->add_control('team_count', array(
            'label' => __('Número de Membros', 'cct'),
            'description' => __('Quantos membros da equipe exibir.', 'cct'),
            'section' => $this->prefix . 'content',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 12,
                'step' => 1,
            ),
        ));
        
        // Membros individuais
        for ($i = 1; $i <= 12; $i++) {
            // Nome
            $this->add_setting("team_member_name_{$i}", array(
                'default' => sprintf(__('Membro %d', 'cct'), $i),
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $this->add_control("team_member_name_{$i}", array(
                'label' => sprintf(__('Nome do Membro %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'text',
            ));
            
            // Cargo
            $this->add_setting("team_member_position_{$i}", array(
                'default' => sprintf(__('Cargo %d', 'cct'), $i),
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $this->add_control("team_member_position_{$i}", array(
                'label' => sprintf(__('Cargo do Membro %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'text',
            ));
            
            // Biografia
            $this->add_setting("team_member_bio_{$i}", array(
                'default' => sprintf(__('Biografia do membro %d', 'cct'), $i),
                'sanitize_callback' => 'wp_kses_post',
            ));
            
            $this->add_control("team_member_bio_{$i}", array(
                'label' => sprintf(__('Biografia do Membro %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'textarea',
            ));
        }
    }
    
    /**
     * Adiciona controles de conteúdo para Portfolio
     */
    private function add_portfolio_content_controls() {
        // Número de projetos
        $this->add_setting('portfolio_count', array(
            'default' => 6,
            'sanitize_callback' => 'absint',
        ));
        
        $this->add_control('portfolio_count', array(
            'label' => __('Número de Projetos', 'cct'),
            'description' => __('Quantos projetos do portfólio exibir.', 'cct'),
            'section' => $this->prefix . 'content',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 20,
                'step' => 1,
            ),
        ));
        
        // Projetos individuais
        for ($i = 1; $i <= 20; $i++) {
            // Título
            $this->add_setting("portfolio_project_title_{$i}", array(
                'default' => sprintf(__('Projeto %d', 'cct'), $i),
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $this->add_control("portfolio_project_title_{$i}", array(
                'label' => sprintf(__('Título do Projeto %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'text',
            ));
            
            // Descrição
            $this->add_setting("portfolio_project_description_{$i}", array(
                'default' => sprintf(__('Descrição do projeto %d', 'cct'), $i),
                'sanitize_callback' => 'wp_kses_post',
            ));
            
            $this->add_control("portfolio_project_description_{$i}", array(
                'label' => sprintf(__('Descrição do Projeto %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'textarea',
            ));
            
            // Categoria
            $this->add_setting("portfolio_project_category_{$i}", array(
                'default' => __('Web Design', 'cct'),
                'sanitize_callback' => 'sanitize_text_field',
            ));
            
            $this->add_control("portfolio_project_category_{$i}", array(
                'label' => sprintf(__('Categoria do Projeto %d', 'cct'), $i),
                'section' => $this->prefix . 'content',
                'type' => 'text',
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
     * Obtém opções de padrões por tipo
     */
    private function get_pattern_choices($type) {
        $choices = array();
        $patterns = null;
        
        switch ($type) {
            case 'faq':
                $patterns = $this->faq_patterns;
                break;
            case 'pricing':
                $patterns = $this->pricing_patterns;
                break;
            case 'team':
                $patterns = $this->team_patterns;
                break;
            case 'portfolio':
                $patterns = $this->portfolio_patterns;
                break;
        }
        
        if ($patterns) {
            foreach ($patterns as $key => $pattern) {
                $choices[$key] = $pattern['name'];
            }
        }
        
        return $choices;
    }
    
    /**
     * Obtém opções de templates
     */
    private function get_template_choices() {
        $choices = array();
        
        foreach ($this->available_templates as $key => $template) {
            $choices[$key] = $template['name'];
        }
        
        return $choices;
    }
    
    /**
     * Obtém label para cores
     */
    private function get_color_label($color_key) {
        $labels = array(
            'primary' => __('Cor Primária', 'cct'),
            'secondary' => __('Cor Secundária', 'cct'),
            'accent' => __('Cor de Destaque', 'cct'),
            'background' => __('Cor de Fundo', 'cct'),
            'text' => __('Cor do Texto', 'cct'),
            'border' => __('Cor da Borda', 'cct'),
        );
        
        return isset($labels[$color_key]) ? $labels[$color_key] : ucfirst(str_replace('_', ' ', $color_key));
    }
    
    /**
     * Obtém descrição para cores
     */
    private function get_color_description($color_key) {
        $descriptions = array(
            'primary' => __('Cor principal usada em botões e destaques.', 'cct'),
            'secondary' => __('Cor secundária para elementos de apoio.', 'cct'),
            'accent' => __('Cor de destaque para call-to-actions.', 'cct'),
            'background' => __('Cor de fundo das seções.', 'cct'),
            'text' => __('Cor principal do texto.', 'cct'),
            'border' => __('Cor das bordas e divisores.', 'cct'),
        );
        
        return isset($descriptions[$color_key]) ? $descriptions[$color_key] : '';
    }
    
    /**
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        // CSS dos padrões
        wp_enqueue_style(
            'cct-patterns',
            get_template_directory_uri() . '/css/cct-patterns.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript dos padrões
        wp_enqueue_script(
            'cct-patterns',
            get_template_directory_uri() . '/js/cct-patterns.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localização do script
        wp_localize_script('cct-patterns', 'cctPatterns', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_patterns_nonce'),
            'settings' => $this->get_frontend_settings(),
            'patterns' => array(
                'faq' => $this->faq_patterns,
                'pricing' => $this->pricing_patterns,
                'team' => $this->team_patterns,
                'portfolio' => $this->portfolio_patterns
            ),
            'templates' => $this->available_templates,
        ));
    }
    
    /**
     * Obtém configurações para o frontend
     */
    private function get_frontend_settings() {
        return array(
            'patternsEnabled' => get_theme_mod($this->prefix . 'patterns_enabled', true),
            'lazyLoading' => get_theme_mod($this->prefix . 'lazy_loading', true),
            'animationsEnabled' => get_theme_mod($this->prefix . 'animations_enabled', true),
            'faqActivePattern' => get_theme_mod($this->prefix . 'faq_active_pattern', 'accordion'),
            'faqSearchEnabled' => get_theme_mod($this->prefix . 'faq_search_enabled', true),
            'faqCategoriesEnabled' => get_theme_mod($this->prefix . 'faq_categories_enabled', true),
            'pricingActivePattern' => get_theme_mod($this->prefix . 'pricing_active_pattern', 'cards'),
            'pricingCurrency' => get_theme_mod($this->prefix . 'pricing_currency', 'R$'),
            'pricingBillingToggle' => get_theme_mod($this->prefix . 'pricing_billing_toggle', true),
            'teamActivePattern' => get_theme_mod($this->prefix . 'team_active_pattern', 'grid'),
            'teamSocialLinks' => get_theme_mod($this->prefix . 'team_social_links', true),
            'teamBioModal' => get_theme_mod($this->prefix . 'team_bio_modal', true),
            'portfolioActivePattern' => get_theme_mod($this->prefix . 'portfolio_active_pattern', 'masonry'),
            'portfolioLightbox' => get_theme_mod($this->prefix . 'portfolio_lightbox', true),
            'portfolioFilters' => get_theme_mod($this->prefix . 'portfolio_filters', true),
            'activeTemplate' => get_theme_mod($this->prefix . 'active_template', 'business'),
            'colors' => $this->get_active_colors(),
        );
    }
    
    /**
     * Obtém cores ativas
     */
    private function get_active_colors() {
        $colors = array();
        
        foreach ($this->style_settings['colors'] as $color_key => $default_value) {
            $colors[$color_key] = get_theme_mod($this->prefix . 'color_' . $color_key, $default_value);
        }
        
        return $colors;
    }
    
    /**
     * Gera CSS customizado
     */
    public function output_custom_css() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['patternsEnabled']) {
            return;
        }
        
        echo "<style id='cct-patterns-custom-css'>\n";
        
        // Variáveis CSS para cores
        echo ":root {\n";
        foreach ($settings['colors'] as $color_key => $color_value) {
            echo "  --cct-pattern-color-{$color_key}: {$color_value};\n";
        }
        echo "}\n";
        
        // Estilos específicos por padrão ativo
        $this->output_pattern_specific_css($settings);
        
        echo "</style>\n";
    }
    
    /**
     * Gera CSS específico por padrão
     */
    private function output_pattern_specific_css($settings) {
        // CSS para FAQ
        if ($settings['faqActivePattern'] === 'accordion') {
            echo ".cct-faq-accordion .cct-faq-item { border-color: var(--cct-pattern-color-border); }\n";
            echo ".cct-faq-accordion .cct-faq-question { background: var(--cct-pattern-color-background); }\n";
        }
        
        // CSS para Pricing
        if ($settings['pricingActivePattern'] === 'cards') {
            echo ".cct-pricing-cards .cct-pricing-card { border-color: var(--cct-pattern-color-border); }\n";
            echo ".cct-pricing-cards .cct-pricing-popular { border-color: var(--cct-pattern-color-accent); }\n";
        }
        
        // CSS para Team
        if ($settings['teamActivePattern'] === 'grid') {
            echo ".cct-team-grid .cct-team-card { background: var(--cct-pattern-color-background); }\n";
            echo ".cct-team-grid .cct-team-card:hover { border-color: var(--cct-pattern-color-primary); }\n";
        }
        
        // CSS para Portfolio
        if ($settings['portfolioActivePattern'] === 'masonry') {
            echo ".cct-portfolio-masonry .cct-portfolio-item { background: var(--cct-pattern-color-background); }\n";
            echo ".cct-portfolio-masonry .cct-portfolio-overlay { background: var(--cct-pattern-color-primary); }\n";
        }
    }
    
    /**
     * Gera JavaScript customizado
     */
    public function output_custom_js() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['patternsEnabled']) {
            return;
        }
        
        echo "<script id='cct-patterns-custom-js'>\n";
        echo "document.addEventListener('DOMContentLoaded', function() {\n";
        echo "  if (typeof CCTPatterns !== 'undefined') {\n";
        echo "    CCTPatterns.init(" . wp_json_encode($settings) . ");\n";
        echo "  }\n";
        echo "});\n";
        echo "</script>\n";
    }
    
    /**
     * Shortcode para seção FAQ
     */
    public function faq_section_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'pattern' => get_theme_mod($this->prefix . 'faq_active_pattern', 'accordion'),
            'title' => __('Perguntas Frequentes', 'cct'),
            'search' => get_theme_mod($this->prefix . 'faq_search_enabled', true) ? 'true' : 'false',
            'categories' => get_theme_mod($this->prefix . 'faq_categories_enabled', true) ? 'true' : 'false',
            'class' => '',
        ), $atts, 'cct_faq_section');
        
        $classes = array('cct-faq-section', 'cct-faq-' . $atts['pattern']);
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '">';
        
        if (!empty($atts['title'])) {
            $output .= '<h2 class="cct-faq-title">' . esc_html($atts['title']) . '</h2>';
        }
        
        if ($atts['search'] === 'true') {
            $output .= '<div class="cct-faq-search">';
            $output .= '<input type="text" class="cct-faq-search-input" placeholder="' . esc_attr__('Buscar...', 'cct') . '">';
            $output .= '</div>';
        }
        
        $output .= '<div class="cct-faq-content">';
        
        // Se não há conteúdo personalizado, usar dados do customizer
        if (empty($content)) {
            $faq_count = get_theme_mod($this->prefix . 'faq_count', 5);
            
            for ($i = 1; $i <= $faq_count; $i++) {
                $question = get_theme_mod($this->prefix . "faq_question_{$i}", sprintf(__('Pergunta %d', 'cct'), $i));
                $answer = get_theme_mod($this->prefix . "faq_answer_{$i}", sprintf(__('Resposta para a pergunta %d', 'cct'), $i));
                $category = get_theme_mod($this->prefix . "faq_category_{$i}", __('Geral', 'cct'));
                
                if (!empty($question) && !empty($answer)) {
                    $item_id = 'faq-' . sanitize_title($question);
                    
                    $output .= '<div class="cct-faq-item" data-category="' . esc_attr($category) . '" id="' . esc_attr($item_id) . '">';
                    $output .= '<div class="cct-faq-question">';
                    $output .= '<h3>' . esc_html($question) . '</h3>';
                    $output .= '<span class="cct-faq-icon"></span>';
                    $output .= '</div>';
                    $output .= '<div class="cct-faq-answer">';
                    $output .= wpautop($answer);
                    $output .= '</div>';
                    $output .= '</div>';
                }
            }
        } else {
            $output .= do_shortcode($content);
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para item FAQ
     */
    public function faq_item_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'question' => '',
            'category' => 'geral',
            'id' => '',
        ), $atts, 'cct_faq');
        
        if (empty($atts['question'])) {
            return '';
        }
        
        $item_id = !empty($atts['id']) ? $atts['id'] : 'faq-' . sanitize_title($atts['question']);
        
        $output = '<div class="cct-faq-item" data-category="' . esc_attr($atts['category']) . '" id="' . esc_attr($item_id) . '">';
        $output .= '<div class="cct-faq-question">';
        $output .= '<h3>' . esc_html($atts['question']) . '</h3>';
        $output .= '<span class="cct-faq-icon"></span>';
        $output .= '</div>';
        $output .= '<div class="cct-faq-answer">';
        $output .= wpautop(do_shortcode($content));
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para tabela de preços
     */
    public function pricing_table_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'pattern' => get_theme_mod($this->prefix . 'pricing_active_pattern', 'cards'),
            'title' => __('Nossos Planos', 'cct'),
            'billing_toggle' => get_theme_mod($this->prefix . 'pricing_billing_toggle', true) ? 'true' : 'false',
            'currency' => get_theme_mod($this->prefix . 'pricing_currency', 'R$'),
            'class' => '',
        ), $atts, 'cct_pricing_table');
        
        $classes = array('cct-pricing-table', 'cct-pricing-' . $atts['pattern']);
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '">';
        
        if (!empty($atts['title'])) {
            $output .= '<h2 class="cct-pricing-title">' . esc_html($atts['title']) . '</h2>';
        }
        
        if ($atts['billing_toggle'] === 'true') {
            $output .= '<div class="cct-pricing-toggle">';
            $output .= '<label class="cct-toggle-label">';
            $output .= '<span>' . __('Mensal', 'cct') . '</span>';
            $output .= '<input type="checkbox" class="cct-billing-toggle">';
            $output .= '<span class="cct-toggle-slider"></span>';
            $output .= '<span>' . __('Anual', 'cct') . '</span>';
            $output .= '</label>';
            $output .= '</div>';
        }
        
        $output .= '<div class="cct-pricing-content" data-currency="' . esc_attr($atts['currency']) . '">';
        
        // Se não há conteúdo personalizado, usar dados do customizer
        if (empty($content)) {
            $pricing_count = get_theme_mod($this->prefix . 'pricing_count', 3);
            
            for ($i = 1; $i <= $pricing_count; $i++) {
                $plan_name = get_theme_mod($this->prefix . "pricing_plan_name_{$i}", sprintf(__('Plano %d', 'cct'), $i));
                $plan_price = get_theme_mod($this->prefix . "pricing_plan_price_{$i}", '99');
                $plan_description = get_theme_mod($this->prefix . "pricing_plan_description_{$i}", sprintf(__('Descrição do plano %d', 'cct'), $i));
                
                if (!empty($plan_name) && !empty($plan_price)) {
                    $classes = array('cct-pricing-plan');
                    
                    // Marcar o plano do meio como popular se houver 3 planos
                    if ($pricing_count == 3 && $i == 2) {
                        $classes[] = 'cct-pricing-popular';
                    }
                    
                    $output .= '<div class="' . implode(' ', $classes) . '">';
                    
                    if (in_array('cct-pricing-popular', $classes)) {
                        $output .= '<div class="cct-pricing-badge">' . __('Mais Popular', 'cct') . '</div>';
                    }
                    
                    $output .= '<div class="cct-pricing-header">';
                    $output .= '<h3 class="cct-pricing-name">' . esc_html($plan_name) . '</h3>';
                    $output .= '<div class="cct-pricing-price">';
                    $output .= '<span class="cct-price-amount">' . esc_html($atts['currency']) . esc_html($plan_price) . '</span>';
                    $output .= '<span class="cct-price-period">/' . __('mês', 'cct') . '</span>';
                    $output .= '</div>';
                    $output .= '</div>';
                    
                    $output .= '<div class="cct-pricing-body">';
                    $output .= '<div class="cct-pricing-description">' . wpautop($plan_description) . '</div>';
                    $output .= '</div>';
                    
                    $output .= '<div class="cct-pricing-footer">';
                    $output .= '<a href="#" class="cct-pricing-button">' . __('Escolher Plano', 'cct') . '</a>';
                    $output .= '</div>';
                    
                    $output .= '</div>';
                }
            }
        } else {
            $output .= do_shortcode($content);
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para plano de preços
     */
    public function pricing_plan_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'name' => '',
            'price' => '',
            'period' => 'mês',
            'annual_price' => '',
            'annual_period' => 'ano',
            'popular' => 'false',
            'button_text' => __('Escolher Plano', 'cct'),
            'button_url' => '#',
        ), $atts, 'cct_pricing_plan');
        
        if (empty($atts['name']) || empty($atts['price'])) {
            return '';
        }
        
        $classes = array('cct-pricing-plan');
        
        if ($atts['popular'] === 'true') {
            $classes[] = 'cct-pricing-popular';
        }
        
        $output = '<div class="' . implode(' ', $classes) . '">';
        
        if ($atts['popular'] === 'true') {
            $output .= '<div class="cct-pricing-badge">' . __('Mais Popular', 'cct') . '</div>';
        }
        
        $output .= '<div class="cct-pricing-header">';
        $output .= '<h3 class="cct-pricing-name">' . esc_html($atts['name']) . '</h3>';
        $output .= '<div class="cct-pricing-price">';
        $output .= '<span class="cct-price-monthly" data-price="' . esc_attr($atts['price']) . '" data-period="' . esc_attr($atts['period']) . '">';
        $output .= '<span class="cct-price-amount">' . esc_html($atts['price']) . '</span>';
        $output .= '<span class="cct-price-period">/' . esc_html($atts['period']) . '</span>';
        $output .= '</span>';
        
        if (!empty($atts['annual_price'])) {
            $output .= '<span class="cct-price-annual" data-price="' . esc_attr($atts['annual_price']) . '" data-period="' . esc_attr($atts['annual_period']) . '" style="display: none;">';
            $output .= '<span class="cct-price-amount">' . esc_html($atts['annual_price']) . '</span>';
            $output .= '<span class="cct-price-period">/' . esc_html($atts['annual_period']) . '</span>';
            $output .= '</span>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        $output .= '<div class="cct-pricing-features">';
        $output .= wpautop(do_shortcode($content));
        $output .= '</div>';
        
        $output .= '<div class="cct-pricing-footer">';
        $output .= '<a href="' . esc_url($atts['button_url']) . '" class="cct-pricing-button">' . esc_html($atts['button_text']) . '</a>';
        $output .= '</div>';
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para grid de equipe
     */
    public function team_grid_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'pattern' => get_theme_mod($this->prefix . 'team_active_pattern', 'grid'),
            'title' => __('Nossa Equipe', 'cct'),
            'columns' => '4',
            'class' => '',
        ), $atts, 'cct_team_grid');
        
        $classes = array('cct-team-grid', 'cct-team-' . $atts['pattern'], 'cct-columns-' . $atts['columns']);
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '">';
        
        if (!empty($atts['title'])) {
            $output .= '<h2 class="cct-team-title">' . esc_html($atts['title']) . '</h2>';
        }
        
        $output .= '<div class="cct-team-content">';
        $output .= do_shortcode($content);
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para membro da equipe
     */
    public function team_member_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'name' => '',
            'role' => '',
            'image' => '',
            'email' => '',
            'phone' => '',
            'linkedin' => '',
            'twitter' => '',
            'facebook' => '',
            'instagram' => '',
        ), $atts, 'cct_team_member');
        
        if (empty($atts['name'])) {
            return '';
        }
        
        $output = '<div class="cct-team-member">';
        
        if (!empty($atts['image'])) {
            $output .= '<div class="cct-team-image">';
            $output .= '<img src="' . esc_url($atts['image']) . '" alt="' . esc_attr($atts['name']) . '">';
            $output .= '</div>';
        }
        
        $output .= '<div class="cct-team-info">';
        $output .= '<h3 class="cct-team-name">' . esc_html($atts['name']) . '</h3>';
        
        if (!empty($atts['role'])) {
            $output .= '<p class="cct-team-role">' . esc_html($atts['role']) . '</p>';
        }
        
        if (!empty($content)) {
            $output .= '<div class="cct-team-bio">' . wpautop(do_shortcode($content)) . '</div>';
        }
        
        // Redes sociais
        $social_links = array();
        if (!empty($atts['email'])) $social_links['email'] = 'mailto:' . $atts['email'];
        if (!empty($atts['linkedin'])) $social_links['linkedin'] = $atts['linkedin'];
        if (!empty($atts['twitter'])) $social_links['twitter'] = $atts['twitter'];
        if (!empty($atts['facebook'])) $social_links['facebook'] = $atts['facebook'];
        if (!empty($atts['instagram'])) $social_links['instagram'] = $atts['instagram'];
        
        if (!empty($social_links) && get_theme_mod($this->prefix . 'team_social_links', true)) {
            $output .= '<div class="cct-team-social">';
            foreach ($social_links as $platform => $url) {
                $output .= '<a href="' . esc_url($url) . '" class="cct-social-' . $platform . '" target="_blank" rel="noopener">';
                $output .= '<span class="cct-icon cct-icon-' . $platform . '"></span>';
                $output .= '</a>';
            }
            $output .= '</div>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para galeria de portfolio
     */
    public function portfolio_gallery_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'pattern' => get_theme_mod($this->prefix . 'portfolio_active_pattern', 'masonry'),
            'title' => __('Nosso Portfolio', 'cct'),
            'columns' => '3',
            'filters' => get_theme_mod($this->prefix . 'portfolio_filters', true) ? 'true' : 'false',
            'class' => '',
        ), $atts, 'cct_portfolio_gallery');
        
        $classes = array('cct-portfolio-gallery', 'cct-portfolio-' . $atts['pattern'], 'cct-columns-' . $atts['columns']);
        
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '">';
        
        if (!empty($atts['title'])) {
            $output .= '<h2 class="cct-portfolio-title">' . esc_html($atts['title']) . '</h2>';
        }
        
        if ($atts['filters'] === 'true') {
            $output .= '<div class="cct-portfolio-filters">';
            $output .= '<button class="cct-filter-btn active" data-filter="*">' . __('Todos', 'cct') . '</button>';
            $output .= '</div>';
        }
        
        $output .= '<div class="cct-portfolio-content">';
        $output .= do_shortcode($content);
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para item de portfolio
     */
    public function portfolio_item_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'title' => '',
            'category' => 'geral',
            'image' => '',
            'url' => '',
            'description' => '',
        ), $atts, 'cct_portfolio_item');
        
        if (empty($atts['title']) || empty($atts['image'])) {
            return '';
        }
        
        $output = '<div class="cct-portfolio-item" data-category="' . esc_attr($atts['category']) . '">';
        
        $output .= '<div class="cct-portfolio-image">';
        $output .= '<img src="' . esc_url($atts['image']) . '" alt="' . esc_attr($atts['title']) . '">';
        $output .= '<div class="cct-portfolio-overlay">';
        $output .= '<div class="cct-portfolio-actions">';
        
        if (get_theme_mod($this->prefix . 'portfolio_lightbox', true)) {
            $output .= '<a href="' . esc_url($atts['image']) . '" class="cct-lightbox-btn" data-lightbox="portfolio">';
            $output .= '<span class="cct-icon cct-icon-zoom"></span>';
            $output .= '</a>';
        }
        
        if (!empty($atts['url'])) {
            $output .= '<a href="' . esc_url($atts['url']) . '" class="cct-external-btn" target="_blank" rel="noopener">';
            $output .= '<span class="cct-icon cct-icon-external"></span>';
            $output .= '</a>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        
        $output .= '<div class="cct-portfolio-info">';
        $output .= '<h3 class="cct-portfolio-title">' . esc_html($atts['title']) . '</h3>';
        
        if (!empty($atts['description'])) {
            $output .= '<p class="cct-portfolio-description">' . esc_html($atts['description']) . '</p>';
        }
        
        if (!empty($content)) {
            $output .= '<div class="cct-portfolio-content">' . wpautop(do_shortcode($content)) . '</div>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * AJAX handler para preview de padrão
     */
    public function ajax_preview_pattern() {
        check_ajax_referer('cct_patterns_nonce', 'nonce');
        
        $pattern_type = sanitize_text_field($_POST['pattern_type'] ?? '');
        $pattern_name = sanitize_text_field($_POST['pattern_name'] ?? '');
        
        if (empty($pattern_type) || empty($pattern_name)) {
            wp_die(__('Parâmetros inválidos.', 'cct'));
        }
        
        $pattern_data = $this->get_pattern_data($pattern_type, $pattern_name);
        
        if (!$pattern_data) {
            wp_die(__('Padrão não encontrado.', 'cct'));
        }
        
        $response = array(
            'success' => true,
            'data' => array(
                'pattern' => $pattern_data,
                'preview_html' => $this->generate_pattern_preview($pattern_type, $pattern_name),
                'css' => $this->generate_pattern_css($pattern_type, $pattern_name)
            )
        );
        
        wp_send_json($response);
    }
    
    /**
     * AJAX handler para export de padrão
     */
    public function ajax_export_pattern() {
        check_ajax_referer('cct_patterns_nonce', 'nonce');
        
        $pattern_type = sanitize_text_field($_POST['pattern_type'] ?? '');
        $pattern_name = sanitize_text_field($_POST['pattern_name'] ?? '');
        $export_format = sanitize_text_field($_POST['export_format'] ?? 'json');
        
        if (empty($pattern_type) || empty($pattern_name)) {
            wp_die(__('Parâmetros inválidos.', 'cct'));
        }
        
        $export_data = $this->export_pattern($pattern_type, $pattern_name, $export_format);
        
        $response = array(
            'success' => true,
            'data' => array(
                'export_data' => $export_data,
                'filename' => $pattern_type . '-' . $pattern_name . '.' . $export_format
            )
        );
        
        wp_send_json($response);
    }
    
    /**
     * AJAX handler para import de padrão
     */
    public function ajax_import_pattern() {
        check_ajax_referer('cct_patterns_nonce', 'nonce');
        
        $import_data = $this->sanitize_json_array($_POST['import_data'] ?? array());
        
        if (empty($import_data)) {
            wp_die(__('Dados de importação inválidos.', 'cct'));
        }
        
        $result = $this->import_pattern($import_data);
        
        $response = array(
            'success' => $result['success'],
            'message' => $result['message'],
            'data' => $result['data'] ?? array()
        );
        
        wp_send_json($response);
    }
    
    /**
     * Obtém dados de um padrão
     */
    private function get_pattern_data($pattern_type, $pattern_name) {
        switch ($pattern_type) {
            case 'faq':
                return isset($this->faq_patterns[$pattern_name]) ? $this->faq_patterns[$pattern_name] : null;
            case 'pricing':
                return isset($this->pricing_patterns[$pattern_name]) ? $this->pricing_patterns[$pattern_name] : null;
            case 'team':
                return isset($this->team_patterns[$pattern_name]) ? $this->team_patterns[$pattern_name] : null;
            case 'portfolio':
                return isset($this->portfolio_patterns[$pattern_name]) ? $this->portfolio_patterns[$pattern_name] : null;
            default:
                return null;
        }
    }
    
    /**
     * Gera preview HTML de um padrão
     */
    private function generate_pattern_preview($pattern_type, $pattern_name) {
        // Implementar geração de preview HTML
        return '<div class="cct-pattern-preview">Preview de ' . $pattern_type . ' - ' . $pattern_name . '</div>';
    }
    
    /**
     * Gera CSS de um padrão
     */
    private function generate_pattern_css($pattern_type, $pattern_name) {
        // Implementar geração de CSS específico
        return '.cct-' . $pattern_type . '-' . $pattern_name . ' { /* CSS específico */ }';
    }
    
    /**
     * Exporta um padrão
     */
    private function export_pattern($pattern_type, $pattern_name, $format) {
        $pattern_data = $this->get_pattern_data($pattern_type, $pattern_name);
        
        if (!$pattern_data) {
            return null;
        }
        
        switch ($format) {
            case 'json':
                return wp_json_encode($pattern_data, JSON_PRETTY_PRINT);
            case 'css':
                return $this->generate_pattern_css($pattern_type, $pattern_name);
            case 'html':
                return $this->generate_pattern_preview($pattern_type, $pattern_name);
            default:
                return wp_json_encode($pattern_data);
        }
    }
    
    /**
     * Importa um padrão
     */
    private function import_pattern($import_data) {
        // Implementar lógica de importação
        return array(
            'success' => true,
            'message' => __('Padrão importado com sucesso.', 'cct'),
            'data' => $import_data
        );
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
     * Obtém estatísticas da biblioteca
     */
    public function get_pattern_stats() {
        return array(
            'total_patterns' => count($this->faq_patterns) + count($this->pricing_patterns) + count($this->team_patterns) + count($this->portfolio_patterns),
            'faq_patterns' => count($this->faq_patterns),
            'pricing_patterns' => count($this->pricing_patterns),
            'team_patterns' => count($this->team_patterns),
            'portfolio_patterns' => count($this->portfolio_patterns),
            'available_templates' => count($this->available_templates),
            'patterns_enabled' => get_theme_mod($this->prefix . 'patterns_enabled', true),
        );
    }
}