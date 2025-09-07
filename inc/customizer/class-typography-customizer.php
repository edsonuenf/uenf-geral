<?php
/**
 * Módulo do Customizer para Sistema de Tipografia Avançado
 * 
 * Gerencia todas as configurações tipográficas incluindo:
 * - Google Fonts integration
 * - Font pairing suggestions
 * - Typography scales
 * - Reading optimization
 * - Custom font upload
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
 * Classe para configurações de tipografia no customizer
 */
class CCT_Typography_Customizer extends CCT_Customizer_Base {
    
    /**
     * Google Fonts API Key
     * 
     * @var string
     */
    private $google_fonts_api_key;
    
    /**
     * Cache de fontes do Google
     * 
     * @var array
     */
    private $google_fonts_cache;
    
    /**
     * Escalas tipográficas predefinidas
     * 
     * @var array
     */
    private $typography_scales;
    
    /**
     * Pairings de fontes recomendados
     * 
     * @var array
     */
    private $font_pairings;
    
    /**
     * Inicializa as configurações de tipografia
     */
    protected function init() {
        $this->init_google_fonts_api();
        $this->init_typography_scales();
        $this->init_font_pairings();
        $this->add_typography_sections();
        $this->add_typography_settings();
        $this->add_hooks();
    }
    
    /**
     * Inicializa a API do Google Fonts
     */
    private function init_google_fonts_api() {
        // API Key pode ser configurada via constante ou opção
        $this->google_fonts_api_key = defined('CCT_GOOGLE_FONTS_API_KEY') 
            ? CCT_GOOGLE_FONTS_API_KEY 
            : get_option('cct_google_fonts_api_key', '');
        
        // Cache de fontes (válido por 24 horas)
        $this->google_fonts_cache = get_transient('cct_google_fonts_cache');
        
        if (false === $this->google_fonts_cache) {
            $this->refresh_google_fonts_cache();
        }
    }
    
    /**
     * Inicializa escalas tipográficas
     */
    private function init_typography_scales() {
        $this->typography_scales = array(
            'minor_second' => array(
                'name' => 'Segunda Menor (1.067)',
                'ratio' => 1.067,
                'sizes' => array(12, 13, 14, 15, 16, 17, 18, 19, 21, 22, 24, 25, 27, 29, 31, 33)
            ),
            'major_second' => array(
                'name' => 'Segunda Maior (1.125)',
                'ratio' => 1.125,
                'sizes' => array(12, 13, 15, 17, 19, 21, 24, 27, 30, 34, 38, 43, 48, 54, 61, 68)
            ),
            'minor_third' => array(
                'name' => 'Terça Menor (1.200)',
                'ratio' => 1.200,
                'sizes' => array(12, 14, 17, 20, 24, 29, 35, 42, 50, 60, 72, 86, 104, 124, 149, 179)
            ),
            'major_third' => array(
                'name' => 'Terça Maior (1.250)',
                'ratio' => 1.250,
                'sizes' => array(12, 15, 19, 24, 30, 37, 46, 58, 72, 90, 113, 141, 176, 220, 275, 344)
            ),
            'perfect_fourth' => array(
                'name' => 'Quarta Perfeita (1.333)',
                'ratio' => 1.333,
                'sizes' => array(12, 16, 21, 28, 37, 50, 66, 88, 118, 157, 209, 279, 372, 496, 661, 881)
            ),
            'golden_ratio' => array(
                'name' => 'Proporção Áurea (1.618)',
                'ratio' => 1.618,
                'sizes' => array(12, 19, 31, 50, 81, 131, 212, 343, 555, 898, 1453, 2351, 3804, 6155, 9959, 16114)
            )
        );
    }
    
    /**
     * Inicializa pairings de fontes
     */
    private function init_font_pairings() {
        $this->font_pairings = array(
            'classic_serif' => array(
                'name' => 'Clássico Serif',
                'description' => 'Combinação elegante para textos formais',
                'heading' => 'Playfair Display',
                'body' => 'Source Sans Pro',
                'category' => 'elegant'
            ),
            'modern_sans' => array(
                'name' => 'Moderno Sans',
                'description' => 'Clean e contemporâneo',
                'heading' => 'Montserrat',
                'body' => 'Open Sans',
                'category' => 'modern'
            ),
            'humanist' => array(
                'name' => 'Humanista',
                'description' => 'Amigável e legível',
                'heading' => 'Merriweather',
                'body' => 'Lato',
                'category' => 'friendly'
            ),
            'geometric' => array(
                'name' => 'Geométrico',
                'description' => 'Preciso e minimalista',
                'heading' => 'Poppins',
                'body' => 'Nunito',
                'category' => 'minimal'
            ),
            'editorial' => array(
                'name' => 'Editorial',
                'description' => 'Perfeito para blogs e artigos',
                'heading' => 'Crimson Text',
                'body' => 'PT Sans',
                'category' => 'editorial'
            ),
            'tech' => array(
                'name' => 'Tecnológico',
                'description' => 'Moderno e futurista',
                'heading' => 'Orbitron',
                'body' => 'Roboto',
                'category' => 'tech'
            )
        );
    }
    
    /**
     * Adiciona seções de tipografia no customizer (Aparência → Personalizar)
     */
    private function add_typography_sections() {
        // Painel principal "Tipografia Avançada" no customizer
        $this->wp_customize->add_panel('cct_typography_panel', array(
            'title' => __('Tipografia Avançada', 'cct'),
            'description' => __('Configure fontes, escalas tipográficas e pairings profissionais.', 'cct'),
            'priority' => 160,
        ));
        
        // Seção de Google Fonts
        $this->add_section('typography_google_fonts', array(
            'title' => __('Google Fonts', 'cct'),
            'description' => __('Integração com a biblioteca do Google Fonts.', 'cct'),
            'panel' => 'cct_typography_panel',
            'priority' => 10,
        ));
        
        // Seção de Font Pairing
        $this->add_section('typography_font_pairing', array(
            'title' => __('Combinações de Fontes', 'cct'),
            'description' => __('Pairings profissionais de fontes.', 'cct'),
            'panel' => 'cct_typography_panel',
            'priority' => 20,
        ));
        
        // Seção de Escala Tipográfica
        $this->add_section('typography_scale', array(
            'title' => __('Escala Tipográfica', 'cct'),
            'description' => __('Escalas harmoniosas para hierarquia visual.', 'cct'),
            'panel' => 'cct_typography_panel',
            'priority' => 30,
        ));
        
        // Seção de Otimização de Leitura
        $this->add_section('typography_reading', array(
            'title' => __('Otimização de Leitura', 'cct'),
            'description' => __('Configurações para melhor legibilidade.', 'cct'),
            'panel' => 'cct_typography_panel',
            'priority' => 40,
        ));
        
        // Seção de Fontes Customizadas
        $this->add_section('typography_custom_fonts', array(
            'title' => __('Fontes Personalizadas', 'cct'),
            'description' => __('Upload e gerenciamento de fontes próprias.', 'cct'),
            'panel' => 'cct_typography_panel',
            'priority' => 50,
        ));
    }
    
    /**
     * Adiciona todas as configurações de tipografia
     */
    private function add_typography_settings() {
        $this->add_google_fonts_settings();
        $this->add_font_pairing_settings();
        $this->add_typography_scale_settings();
        $this->add_reading_optimization_settings();
        $this->add_custom_fonts_settings();
    }
    
    /**
     * Configurações do Google Fonts
     */
    private function add_google_fonts_settings() {
        // API Key do Google Fonts
        $this->add_setting('google_fonts_api_key', array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ));
        
        $this->add_control('google_fonts_api_key', array(
            'label' => __('Google Fonts API Key', 'cct'),
            'description' => __('Chave da API para acessar todas as fontes do Google. <a href="https://developers.google.com/fonts/docs/developer_api" target="_blank">Obter chave</a>', 'cct'),
            'section' => $this->prefix . 'typography_google_fonts',
            'type' => 'text',
        ));
        
        // Fonte para títulos
        $this->add_setting('heading_font_family', array(
            'default' => defined('CCT_DEFAULT_HEADING_FONT') ? CCT_DEFAULT_HEADING_FONT : 'Roboto',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        $this->wp_customize->add_control('cct_heading_font_family', array(
            'label' => __('Fonte dos Títulos', 'cct'),
            'section' => $this->prefix . 'typography_google_fonts',
            'settings' => $this->prefix . 'heading_font_family',
            'type' => 'select',
            'choices' => $this->get_google_fonts_choices(),
        ));
        
        // Fonte para corpo do texto
        $this->add_setting('body_font_family', array(
            'default' => defined('CCT_DEFAULT_BODY_FONT') ? CCT_DEFAULT_BODY_FONT : 'Open Sans',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        $this->wp_customize->add_control('cct_body_font_family', array(
            'label' => __('Fonte do Corpo', 'cct'),
            'section' => $this->prefix . 'typography_google_fonts',
            'settings' => $this->prefix . 'body_font_family',
            'type' => 'select',
            'choices' => $this->get_google_fonts_choices(),
        ));
        
        // Peso da fonte dos títulos
        $this->add_setting('heading_font_weight', array(
            'default' => '600',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        $this->add_control('heading_font_weight', array(
            'label' => __('Peso da Fonte - Títulos', 'cct'),
            'section' => $this->prefix . 'typography_google_fonts',
            'type' => 'select',
            'choices' => array(
                '300' => __('Light (300)', 'cct'),
                '400' => __('Regular (400)', 'cct'),
                '500' => __('Medium (500)', 'cct'),
                '600' => __('Semi Bold (600)', 'cct'),
                '700' => __('Bold (700)', 'cct'),
                '800' => __('Extra Bold (800)', 'cct'),
                '900' => __('Black (900)', 'cct'),
            ),
        ));
        
        // Peso da fonte do corpo
        $this->add_setting('body_font_weight', array(
            'default' => '400',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        $this->add_control('body_font_weight', array(
            'label' => __('Peso da Fonte - Corpo', 'cct'),
            'section' => $this->prefix . 'typography_google_fonts',
            'type' => 'select',
            'choices' => array(
                '300' => __('Light (300)', 'cct'),
                '400' => __('Regular (400)', 'cct'),
                '500' => __('Medium (500)', 'cct'),
                '600' => __('Semi Bold (600)', 'cct'),
            ),
        ));
    }
    
    /**
     * Configurações de Font Pairing
     */
    private function add_font_pairing_settings() {
        // Seletor de pairing predefinido
        $this->add_setting('font_pairing_preset', array(
            'default' => 'modern_sans',
            'sanitize_callback' => array($this, 'sanitize_font_pairing'),
            'transport' => 'refresh',
        ));
        
        $this->add_control('font_pairing_preset', array(
            'label' => __('Combinação Predefinida', 'cct'),
            'description' => __('Escolha uma combinação profissional de fontes.', 'cct'),
            'section' => $this->prefix . 'typography_font_pairing',
            'type' => 'select',
            'choices' => $this->get_font_pairing_choices(),
        ));
        
        // Preview do pairing
        $this->wp_customize->add_control(
            new CCT_Typography_Preview_Control(
                $this->wp_customize,
                'cct_font_pairing_preview',
                array(
                    'label' => __('Preview da Combinação', 'cct'),
                    'section' => $this->prefix . 'typography_font_pairing',
                    'settings' => $this->prefix . 'font_pairing_preset',
                    'font_pairings' => $this->font_pairings,
                )
            )
        );
    }
    
    /**
     * Configurações de escala tipográfica
     */
    private function add_typography_scale_settings() {
        // Seletor de escala
        $this->add_setting('typography_scale', array(
            'default' => 'major_second',
            'sanitize_callback' => array($this, 'sanitize_typography_scale'),
            'transport' => 'postMessage',
        ));
        
        $this->add_control('typography_scale', array(
            'label' => __('Escala Tipográfica', 'cct'),
            'description' => __('Escolha a proporção matemática para hierarquia de tamanhos.', 'cct'),
            'section' => $this->prefix . 'typography_scale',
            'type' => 'select',
            'choices' => $this->get_typography_scale_choices(),
        ));
        
        // Tamanho base
        $this->add_setting('base_font_size', array(
            'default' => '16',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                'cct_base_font_size',
                array(
                    'label' => __('Tamanho Base (px)', 'cct'),
                    'section' => $this->prefix . 'typography_scale',
                    'settings' => $this->prefix . 'base_font_size',
                    'input_attrs' => array(
                        'min' => 12,
                        'max' => 24,
                        'step' => 1,
                    ),
                )
            )
        );
        
        // Preview da escala
        $this->wp_customize->add_control(
            new CCT_Typography_Scale_Preview_Control(
                $this->wp_customize,
                'cct_typography_scale_preview',
                array(
                    'label' => __('Preview da Escala', 'cct'),
                    'section' => $this->prefix . 'typography_scale',
                    'settings' => array(
                        $this->prefix . 'typography_scale',
                        $this->prefix . 'base_font_size'
                    ),
                    'typography_scales' => $this->typography_scales,
                )
            )
        );
    }
    
    /**
     * Configurações de otimização de leitura
     */
    private function add_reading_optimization_settings() {
        // Altura da linha
        $this->add_setting('line_height', array(
            'default' => '1.6',
            'sanitize_callback' => array($this, 'sanitize_line_height'),
            'transport' => 'postMessage',
        ));
        
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                'cct_line_height',
                array(
                    'label' => __('Altura da Linha', 'cct'),
                    'description' => __('Espaçamento entre linhas para melhor legibilidade.', 'cct'),
                    'section' => $this->prefix . 'typography_reading',
                    'settings' => $this->prefix . 'line_height',
                    'input_attrs' => array(
                        'min' => 1.2,
                        'max' => 2.0,
                        'step' => 0.1,
                    ),
                )
            )
        );
        
        // Espaçamento entre letras
        $this->add_setting('letter_spacing', array(
            'default' => '0',
            'sanitize_callback' => array($this, 'sanitize_letter_spacing'),
            'transport' => 'postMessage',
        ));
        
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                'cct_letter_spacing',
                array(
                    'label' => __('Espaçamento entre Letras (px)', 'cct'),
                    'section' => $this->prefix . 'typography_reading',
                    'settings' => $this->prefix . 'letter_spacing',
                    'input_attrs' => array(
                        'min' => -2,
                        'max' => 5,
                        'step' => 0.1,
                    ),
                )
            )
        );
        
        // Largura máxima do texto
        $this->add_setting('text_max_width', array(
            'default' => '65',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        $this->wp_customize->add_control(
            new WP_Customize_Range_Value_Control(
                $this->wp_customize,
                'cct_text_max_width',
                array(
                    'label' => __('Largura Máxima do Texto (ch)', 'cct'),
                    'description' => __('Número ideal de caracteres por linha (45-75).', 'cct'),
                    'section' => $this->prefix . 'typography_reading',
                    'settings' => $this->prefix . 'text_max_width',
                    'input_attrs' => array(
                        'min' => 45,
                        'max' => 85,
                        'step' => 1,
                    ),
                )
            )
        );
    }
    
    /**
     * Configurações de fontes customizadas
     */
    private function add_custom_fonts_settings() {
        // Upload de fonte customizada
        $this->add_setting('custom_font_upload', array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'refresh',
        ));
        
        $this->wp_customize->add_control(
            new WP_Customize_Upload_Control(
                $this->wp_customize,
                'cct_custom_font_upload',
                array(
                    'label' => __('Upload de Fonte Personalizada', 'cct'),
                    'description' => __('Faça upload de arquivos .woff2, .woff, .ttf ou .otf', 'cct'),
                    'section' => $this->prefix . 'typography_custom_fonts',
                    'settings' => $this->prefix . 'custom_font_upload',
                )
            )
        );
        
        // Nome da fonte customizada
        $this->add_setting('custom_font_name', array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ));
        
        $this->add_control('custom_font_name', array(
            'label' => __('Nome da Fonte', 'cct'),
            'description' => __('Nome para identificar sua fonte personalizada.', 'cct'),
            'section' => $this->prefix . 'typography_custom_fonts',
            'type' => 'text',
        ));
    }
    
    /**
     * Adiciona hooks necessários
     */
    private function add_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_google_fonts'));
        add_action('wp_head', array($this, 'output_typography_css'), 999);
        add_action('wp_ajax_cct_refresh_google_fonts', array($this, 'ajax_refresh_google_fonts'));
        add_action('wp_ajax_cct_get_font_variants', array($this, 'ajax_get_font_variants'));
    }
    
    /**
     * Carrega fontes do Google
     */
    public function enqueue_google_fonts() {
        $heading_font = $this->get_theme_mod('heading_font_family', 'Roboto');
        $body_font = $this->get_theme_mod('body_font_family', 'Open Sans');
        $heading_weight = $this->get_theme_mod('heading_font_weight', '600');
        $body_weight = $this->get_theme_mod('body_font_weight', '400');
        
        $fonts = array();
        
        if ($heading_font && $heading_font !== 'inherit') {
            $fonts[] = $heading_font . ':' . $heading_weight;
        }
        
        if ($body_font && $body_font !== 'inherit' && $body_font !== $heading_font) {
            $fonts[] = $body_font . ':' . $body_weight;
        }
        
        if (!empty($fonts)) {
            $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $fonts) . '&display=swap';
            wp_enqueue_style('cct-google-fonts', $fonts_url, array(), null);
        }
    }
    
    /**
     * Gera CSS da tipografia
     */
    public function output_typography_css() {
        $css = $this->generate_typography_css();
        
        if (!empty($css)) {
            echo '<style type="text/css" id="cct-typography-css">';
            echo $this->minify_css($css);
            echo '</style>';
        }
    }
    
    /**
     * Gera CSS baseado nas configurações
     */
    public function generate_typography_css() {
        $css = '';
        
        // Fontes
        $heading_font = $this->get_theme_mod('heading_font_family', 'Roboto');
        $body_font = $this->get_theme_mod('body_font_family', 'Open Sans');
        $heading_weight = $this->get_theme_mod('heading_font_weight', '600');
        $body_weight = $this->get_theme_mod('body_font_weight', '400');
        
        // Configurações de leitura
        $line_height = $this->get_theme_mod('line_height', '1.6');
        $letter_spacing = $this->get_theme_mod('letter_spacing', '0');
        $text_max_width = $this->get_theme_mod('text_max_width', '65');
        
        // Escala tipográfica
        $scale = $this->get_theme_mod('typography_scale', 'major_second');
        $base_size = $this->get_theme_mod('base_font_size', '16');
        
        // CSS para corpo do texto
        $css .= 'body, .entry-content, p, .content-area {';
        if ($body_font && $body_font !== 'inherit') {
            $css .= 'font-family: "' . $body_font . '", sans-serif;';
        }
        $css .= 'font-weight: ' . $body_weight . ';';
        $css .= 'font-size: ' . $base_size . 'px;';
        $css .= 'line-height: ' . $line_height . ';';
        if ($letter_spacing != '0') {
            $css .= 'letter-spacing: ' . $letter_spacing . 'px;';
        }
        $css .= '}';
        
        // CSS para títulos
        $css .= 'h1, h2, h3, h4, h5, h6, .entry-title, .site-title {';
        if ($heading_font && $heading_font !== 'inherit') {
            $css .= 'font-family: "' . $heading_font . '", sans-serif;';
        }
        $css .= 'font-weight: ' . $heading_weight . ';';
        $css .= '}';
        
        // Escala tipográfica
        if (isset($this->typography_scales[$scale])) {
            $scale_data = $this->typography_scales[$scale];
            $ratio = $scale_data['ratio'];
            
            $css .= 'h6 { font-size: ' . $base_size . 'px; }';
            $css .= 'h5 { font-size: ' . round($base_size * $ratio) . 'px; }';
            $css .= 'h4 { font-size: ' . round($base_size * pow($ratio, 2)) . 'px; }';
            $css .= 'h3 { font-size: ' . round($base_size * pow($ratio, 3)) . 'px; }';
            $css .= 'h2 { font-size: ' . round($base_size * pow($ratio, 4)) . 'px; }';
            $css .= 'h1 { font-size: ' . round($base_size * pow($ratio, 5)) . 'px; }';
        }
        
        // Largura máxima do texto
        $css .= '.entry-content, .content-area p, .content-area li {';
        $css .= 'max-width: ' . $text_max_width . 'ch;';
        $css .= '}';
        
        return $css;
    }
    
    /**
     * Obtém escolhas do Google Fonts
     */
    private function get_google_fonts_choices() {
        $choices = array(
            'inherit' => __('Herdar do tema', 'cct'),
        );
        
        if (!empty($this->google_fonts_cache)) {
            foreach ($this->google_fonts_cache as $font) {
                $choices[$font['family']] = $font['family'];
            }
        } else {
            // Fontes populares como fallback
            $popular_fonts = array(
                'Open Sans', 'Roboto', 'Lato', 'Montserrat', 'Source Sans Pro',
                'Poppins', 'Nunito', 'PT Sans', 'Merriweather', 'Playfair Display'
            );
            
            foreach ($popular_fonts as $font) {
                $choices[$font] = $font;
            }
        }
        
        return $choices;
    }
    
    /**
     * Obtém escolhas de font pairing
     */
    private function get_font_pairing_choices() {
        $choices = array();
        
        foreach ($this->font_pairings as $key => $pairing) {
            $choices[$key] = $pairing['name'] . ' - ' . $pairing['description'];
        }
        
        return $choices;
    }
    
    /**
     * Obtém escolhas de escala tipográfica
     */
    private function get_typography_scale_choices() {
        $choices = array();
        
        foreach ($this->typography_scales as $key => $scale) {
            $choices[$key] = $scale['name'];
        }
        
        return $choices;
    }
    
    /**
     * Atualiza cache do Google Fonts
     */
    private function refresh_google_fonts_cache() {
        if (empty($this->google_fonts_api_key)) {
            return false;
        }
        
        $url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $this->google_fonts_api_key . '&sort=popularity';
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['items'])) {
            $fonts = array_slice($data['items'], 0, 100); // Top 100 fontes
            set_transient('cct_google_fonts_cache', $fonts, DAY_IN_SECONDS);
            $this->google_fonts_cache = $fonts;
            return true;
        }
        
        return false;
    }
    
    /**
     * Sanitiza font pairing
     */
    public function sanitize_font_pairing($input) {
        return array_key_exists($input, $this->font_pairings) ? $input : 'modern_sans';
    }
    
    /**
     * Sanitiza escala tipográfica
     */
    public function sanitize_typography_scale($input) {
        return array_key_exists($input, $this->typography_scales) ? $input : 'major_second';
    }
    
    /**
     * Sanitiza altura da linha
     */
    public function sanitize_line_height($input) {
        $value = floatval($input);
        return ($value >= 1.2 && $value <= 2.0) ? $value : 1.6;
    }
    
    /**
     * Sanitiza espaçamento entre letras
     */
    public function sanitize_letter_spacing($input) {
        $value = floatval($input);
        return ($value >= -2 && $value <= 5) ? $value : 0;
    }
    
    /**
     * Minifica CSS
     */
    private function minify_css($css) {
        $css = preg_replace('/\/\*[^*]*\*+([^/][^*]*\*+)*\//', '', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        return trim($css);
    }
    
    /**
     * AJAX: Atualizar cache do Google Fonts
     */
    public function ajax_refresh_google_fonts() {
        check_ajax_referer('cct_typography_nonce', 'nonce');
        
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissão negada');
        }
        
        $success = $this->refresh_google_fonts_cache();
        
        if ($success) {
            wp_send_json_success(array(
                'message' => 'Cache do Google Fonts atualizado com sucesso!',
                'fonts' => $this->get_google_fonts_choices()
            ));
        } else {
            wp_send_json_error('Erro ao atualizar cache do Google Fonts');
        }
    }
    
    /**
     * AJAX: Obter variantes de uma fonte
     */
    public function ajax_get_font_variants() {
        check_ajax_referer('cct_typography_nonce', 'nonce');
        
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissão negada');
        }
        
        $font_family = sanitize_text_field($_POST['font_family']);
        $variants = $this->get_font_variants($font_family);
        
        wp_send_json_success($variants);
    }
    
    /**
     * Obtém variantes de uma fonte
     */
    private function get_font_variants($font_family) {
        if (empty($this->google_fonts_cache)) {
            return array('400' => 'Regular');
        }
        
        foreach ($this->google_fonts_cache as $font) {
            if ($font['family'] === $font_family) {
                $variants = array();
                foreach ($font['variants'] as $variant) {
                    $variants[$variant] = $this->format_variant_name($variant);
                }
                return $variants;
            }
        }
        
        return array('400' => 'Regular');
    }
    
    /**
     * Formata nome da variante
     */
    private function format_variant_name($variant) {
        $names = array(
            '100' => 'Thin',
            '200' => 'Extra Light',
            '300' => 'Light',
            '400' => 'Regular',
            '500' => 'Medium',
            '600' => 'Semi Bold',
            '700' => 'Bold',
            '800' => 'Extra Bold',
            '900' => 'Black',
            '100italic' => 'Thin Italic',
            '200italic' => 'Extra Light Italic',
            '300italic' => 'Light Italic',
            '400italic' => 'Regular Italic',
            '500italic' => 'Medium Italic',
            '600italic' => 'Semi Bold Italic',
            '700italic' => 'Bold Italic',
            '800italic' => 'Extra Bold Italic',
            '900italic' => 'Black Italic',
        );
        
        return isset($names[$variant]) ? $names[$variant] : $variant;
    }
}