<?php
/**
 * Gerenciador de Cores Avançado
 * 
 * Sistema completo de gerenciamento de cores incluindo:
 * - Paletas predefinidas profissionais
 * - Gerador de cores harmoniosas
 * - Verificador de acessibilidade (WCAG)
 * - Análise de contraste
 * - Exportação de paletas
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
 * Classe para gerenciamento avançado de cores
 */
class CCT_Color_Manager {
    
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
    private $prefix = 'cct_colors_';
    
    /**
     * Paletas de cores predefinidas
     * 
     * @var array
     */
    private $color_palettes;
    
    /**
     * Regras de acessibilidade WCAG
     * 
     * @var array
     */
    private $accessibility_rules;
    
    /**
     * Construtor
     * 
     * @param WP_Customize_Manager $wp_customize
     */
    public function __construct($wp_customize) {
        $this->wp_customize = $wp_customize;
        $this->init_color_palettes();
        $this->init_accessibility_rules();
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
    }
    
    /**
     * Registra configurações no Customizer
     */
    public function register_customizer() {
        $this->add_color_sections();
        $this->add_color_settings();
        $this->add_color_controls();
    }
    
    /**
     * Inicializa o módulo
     */
    public function init() {
        $this->add_color_sections();
        $this->add_color_settings();
        $this->add_color_controls();
        $this->enqueue_color_scripts();
    }
    
    /**
     * Inicializa paletas de cores predefinidas
     */
    private function init_color_palettes() {
        $this->color_palettes = array(
            'corporate' => array(
                'name' => __('Corporativo', 'cct'),
                'description' => __('Paleta profissional para empresas', 'cct'),
                'colors' => array(
                    'primary' => '#1d3771',
                    'secondary' => '#2c5aa0',
                    'accent' => '#3498db',
                    'success' => '#27ae60',
                    'warning' => '#f39c12',
                    'danger' => '#e74c3c',
                    'light' => '#ecf0f1',
                    'dark' => '#2c3e50'
                ),
                'category' => 'professional'
            ),
            'creative' => array(
                'name' => __('Criativo', 'cct'),
                'description' => __('Cores vibrantes para projetos criativos', 'cct'),
                'colors' => array(
                    'primary' => '#9b59b6',
                    'secondary' => '#e91e63',
                    'accent' => '#ff5722',
                    'success' => '#4caf50',
                    'warning' => '#ff9800',
                    'danger' => '#f44336',
                    'light' => '#fafafa',
                    'dark' => '#212121'
                ),
                'category' => 'creative'
            ),
            'nature' => array(
                'name' => __('Natureza', 'cct'),
                'description' => __('Tons naturais e orgânicos', 'cct'),
                'colors' => array(
                    'primary' => '#2e7d32',
                    'secondary' => '#388e3c',
                    'accent' => '#66bb6a',
                    'success' => '#4caf50',
                    'warning' => '#ff8f00',
                    'danger' => '#d32f2f',
                    'light' => '#f1f8e9',
                    'dark' => '#1b5e20'
                ),
                'category' => 'organic'
            ),
            'minimal' => array(
                'name' => __('Minimalista', 'cct'),
                'description' => __('Paleta clean e minimalista', 'cct'),
                'colors' => array(
                    'primary' => '#424242',
                    'secondary' => '#616161',
                    'accent' => '#757575',
                    'success' => '#66bb6a',
                    'warning' => '#ffb74d',
                    'danger' => '#ef5350',
                    'light' => '#fafafa',
                    'dark' => '#212121'
                ),
                'category' => 'minimal'
            ),
            'ocean' => array(
                'name' => __('Oceano', 'cct'),
                'description' => __('Tons de azul inspirados no mar', 'cct'),
                'colors' => array(
                    'primary' => '#0277bd',
                    'secondary' => '#0288d1',
                    'accent' => '#29b6f6',
                    'success' => '#26a69a',
                    'warning' => '#ffa726',
                    'danger' => '#ef5350',
                    'light' => '#e1f5fe',
                    'dark' => '#01579b'
                ),
                'category' => 'thematic'
            ),
            'sunset' => array(
                'name' => __('Pôr do Sol', 'cct'),
                'description' => __('Cores quentes do entardecer', 'cct'),
                'colors' => array(
                    'primary' => '#ff5722',
                    'secondary' => '#ff7043',
                    'accent' => '#ff8a65',
                    'success' => '#66bb6a',
                    'warning' => '#ffb74d',
                    'danger' => '#e53935',
                    'light' => '#fff3e0',
                    'dark' => '#bf360c'
                ),
                'category' => 'thematic'
            )
        );
    }
    
    /**
     * Inicializa regras de acessibilidade
     */
    private function init_accessibility_rules() {
        $this->accessibility_rules = array(
            'wcag_aa' => array(
                'normal_text' => 4.5,
                'large_text' => 3.0,
                'name' => 'WCAG AA'
            ),
            'wcag_aaa' => array(
                'normal_text' => 7.0,
                'large_text' => 4.5,
                'name' => 'WCAG AAA'
            )
        );
    }
    
    /**
     * Adiciona seções de cores
     */
    private function add_color_sections() {
        // Verificar se a extensão está ativa antes de criar o painel
        $extension_manager = cct_extension_manager();
        if (!$extension_manager || !$extension_manager->is_extension_active('colors')) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('CCT Colors: Extensão desativada - painel não criado');
            }
            return; // Sair sem criar o painel
        }
        
        // Criar painel de cores (só se extensão estiver ativa)
        $this->wp_customize->add_panel($this->prefix . 'panel', array(
            'title' => __('🎨 Gerenciador de Cores', 'cct'),
            'description' => __('Sistema avançado de gerenciamento de cores com paletas e verificador de acessibilidade.', 'cct'),
            'priority' => 130,
            'capability' => 'edit_theme_options',
        ));
        
        // Debug log para verificar criação
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('CCT Colors: Painel de cores criado (extensão ativa)');
        }
        
        // Continuar com a criação das seções
        
        // Seção de paletas predefinidas
        $this->wp_customize->add_section($this->prefix . 'color_palettes', array(
            'title' => __('Paletas Predefinidas', 'cct'),
            'description' => __('Escolha entre paletas profissionais ou crie a sua própria.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 10,
        ));
        
        // Seção de cores personalizadas
        $this->wp_customize->add_section($this->prefix . 'custom_colors', array(
            'title' => __('Cores Personalizadas', 'cct'),
            'description' => __('Configure cores individuais para elementos específicos.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 20,
        ));
        
        // Seção de gerador de cores
        $this->wp_customize->add_section($this->prefix . 'color_generator', array(
            'title' => __('Gerador de Cores', 'cct'),
            'description' => __('Gere paletas harmoniosas automaticamente.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 30,
        ));
        
        // Seção de acessibilidade
        $this->wp_customize->add_section('color_accessibility', array(
            'title' => __('Verificador de Acessibilidade', 'cct'),
            'description' => __('Analise o contraste e conformidade WCAG das suas cores.', 'cct'),
            'panel' => 'cct_color_panel',
            'priority' => 40,
        ));
    }
    
    /**
     * Adiciona configurações de cores
     */
    private function add_color_settings() {
        // Paleta selecionada
        $this->wp_customize->add_setting('selected_palette', array(
            'default' => 'corporate',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        // Cores da paleta atual
        $color_roles = array('primary', 'secondary', 'accent', 'success', 'warning', 'danger', 'light', 'dark');
        
        // Cores padrão como fallback
        $default_colors = array(
            'primary' => '#1d3771',
            'secondary' => '#2c5aa0',
            'accent' => '#3498db',
            'success' => '#27ae60',
            'warning' => '#f39c12',
            'danger' => '#e74c3c',
            'light' => '#ecf0f1',
            'dark' => '#2c3e50'
        );
        
        foreach ($color_roles as $role) {
            // Usa cor da paleta corporativa se disponível, senão usa padrão
            $default_color = isset($this->color_palettes['corporate']['colors'][$role]) 
                ? $this->color_palettes['corporate']['colors'][$role] 
                : $default_colors[$role];
                
            $this->wp_customize->add_setting("palette_{$role}", array(
                'default' => $default_color,
                'sanitize_callback' => 'sanitize_hex_color',
                'transport' => 'postMessage',
            ));
        }
        
        // Configurações do gerador
        $this->wp_customize->add_setting('generator_base_color', array(
            'default' => '#1d3771',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $this->wp_customize->add_setting('generator_harmony_type', array(
            'default' => 'complementary',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        // Configurações de acessibilidade
        $this->wp_customize->add_setting('accessibility_standard', array(
            'default' => 'wcag_aa',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->wp_customize->add_setting('check_contrast', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
    }
    
    /**
     * Adiciona controles de cores
     */
    private function add_color_controls() {
        // Seletor de paleta
        $this->wp_customize->add_control('selected_palette', array(
            'label' => __('Paleta Predefinida', 'cct'),
            'description' => __('Escolha uma paleta profissional como ponto de partida.', 'cct'),
            'section' => $this->prefix . 'color_palettes',
            'type' => 'select',
            'choices' => $this->get_palette_choices(),
        ));
        
        // Preview da paleta
        $this->wp_customize->add_control(
            'cct_palette_preview',
            array(
                'label' => __('Preview das Paletas', 'cct'),
                'section' => $this->prefix . 'color_palettes',
                'settings' => $this->prefix . 'selected_palette',
                'type' => 'select',
                'choices' => array(
                    'preview' => __('Preview será implementado em versão futura', 'cct')
                ),
            )
        );
        
        // Controles de cores individuais
        $color_roles = array(
            'primary' => __('Cor Primária', 'cct'),
            'secondary' => __('Cor Secundária', 'cct'),
            'accent' => __('Cor de Destaque', 'cct'),
            'success' => __('Cor de Sucesso', 'cct'),
            'warning' => __('Cor de Aviso', 'cct'),
            'danger' => __('Cor de Perigo', 'cct'),
            'light' => __('Cor Clara', 'cct'),
            'dark' => __('Cor Escura', 'cct')
        );
        
        foreach ($color_roles as $role => $label) {
            $this->wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $this->wp_customize,
                    "cct_palette_{$role}",
                    array(
                        'label' => $label,
                        'section' => $this->prefix . 'custom_colors',
                        'settings' => $this->prefix . "palette_{$role}",
                    )
                )
            );
        }
        
        // Gerador de cores
        $this->wp_customize->add_control(
            new WP_Customize_Color_Control(
                $this->wp_customize,
                'cct_generator_base_color',
                array(
                    'label' => __('Cor Base', 'cct'),
                    'description' => __('Escolha uma cor base para gerar a paleta.', 'cct'),
                    'section' => $this->prefix . 'color_generator',
                    'settings' => $this->prefix . 'generator_base_color',
                )
            )
        );
        
        $this->wp_customize->add_control('generator_harmony_type', array(
            'label' => __('Tipo de Harmonia', 'cct'),
            'description' => __('Escolha o tipo de harmonia cromática.', 'cct'),
            'section' => $this->prefix . 'color_generator',
            'type' => 'select',
            'choices' => array(
                'complementary' => __('Complementar', 'cct'),
                'analogous' => __('Análoga', 'cct'),
                'triadic' => __('Tríade', 'cct'),
                'tetradic' => __('Tétrade', 'cct'),
                'monochromatic' => __('Monocromática', 'cct'),
            ),
        ));
        
        // Gerador de paleta
        $this->wp_customize->add_control(
            'cct_color_generator',
            array(
                'label' => __('Gerar Paleta', 'cct'),
                'section' => $this->prefix . 'color_generator',
                'settings' => $this->prefix . 'generator_base_color',
                'type' => 'select',
                'choices' => array(
                    'generator' => __('Gerador será implementado em versão futura', 'cct')
                ),
            )
        );
        
        // Verificador de acessibilidade
        $this->wp_customize->add_control('accessibility_standard', array(
            'label' => __('Padrão de Acessibilidade', 'cct'),
            'description' => __('Escolha o padrão WCAG para verificação.', 'cct'),
            'section' => $this->prefix . 'color_accessibility',
            'type' => 'select',
            'choices' => array(
                'wcag_aa' => __('WCAG AA (Recomendado)', 'cct'),
                'wcag_aaa' => __('WCAG AAA (Rigoroso)', 'cct'),
            ),
        ));
        
        $this->wp_customize->add_control('check_contrast', array(
            'label' => __('Verificar Contraste Automaticamente', 'cct'),
            'description' => __('Analisa automaticamente o contraste das cores.', 'cct'),
            'section' => $this->prefix . 'color_accessibility',
            'type' => 'checkbox',
        ));
        
        // Analisador de contraste
        $this->wp_customize->add_control(
            'cct_contrast_analyzer',
            array(
                'label' => __('Análise de Contraste', 'cct'),
                'section' => $this->prefix . 'color_accessibility',
                'settings' => $this->prefix . 'palette_primary',
                'type' => 'select',
                'choices' => array(
                    'analyzer' => __('Analisador será implementado em versão futura', 'cct')
                ),
            )
        );
    }
    
    /**
     * Enfileira scripts do gerenciador de cores
     */
    private function enqueue_color_scripts() {
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_controls_scripts'));
        add_action('customize_preview_init', array($this, 'enqueue_preview_scripts'));
    }
    
    /**
     * Enfileira scripts dos controles
     */
    public function enqueue_controls_scripts() {
        wp_enqueue_script(
            'cct-color-manager',
            get_template_directory_uri() . '/js/customizer-color-manager.js',
            array('jquery', 'customize-controls'),
            '1.0.0',
            true
        );
        
        wp_localize_script('cct-color-manager', 'cctColorManager', array(
            'palettes' => $this->color_palettes,
            'accessibilityRules' => $this->accessibility_rules,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_color_manager'),
        ));
    }
    
    /**
     * Enfileira scripts do preview
     */
    public function enqueue_preview_scripts() {
        wp_enqueue_script(
            'cct-color-preview',
            get_template_directory_uri() . '/js/customizer-color-preview.js',
            array('jquery', 'customize-preview'),
            '1.0.0',
            true
        );
    }
    
    /**
     * Obtém opções de paletas para select
     * 
     * @return array
     */
    private function get_palette_choices() {
        $choices = array();
        
        // Verifica se as paletas foram inicializadas
        if (is_array($this->color_palettes) && !empty($this->color_palettes)) {
            foreach ($this->color_palettes as $key => $palette) {
                if (isset($palette['name'])) {
                    $choices[$key] = $palette['name'];
                }
            }
        }
        
        // Fallback se não houver paletas
        if (empty($choices)) {
            $choices = array(
                'corporate' => __('Corporativo', 'cct'),
                'creative' => __('Criativo', 'cct'),
                'nature' => __('Natureza', 'cct'),
                'minimal' => __('Minimalista', 'cct'),
                'ocean' => __('Oceano', 'cct'),
                'sunset' => __('Pôr do Sol', 'cct')
            );
        }
        
        return $choices;
    }
    
    /**
     * Calcula contraste entre duas cores
     * 
     * @param string $color1 Cor em hexadecimal
     * @param string $color2 Cor em hexadecimal
     * @return float Razão de contraste
     */
    public static function calculate_contrast($color1, $color2) {
        $luminance1 = self::get_luminance($color1);
        $luminance2 = self::get_luminance($color2);
        
        $lighter = max($luminance1, $luminance2);
        $darker = min($luminance1, $luminance2);
        
        return ($lighter + 0.05) / ($darker + 0.05);
    }
    
    /**
     * Calcula luminância de uma cor
     * 
     * @param string $hex_color Cor em hexadecimal
     * @return float Luminância
     */
    private static function get_luminance($hex_color) {
        $hex_color = ltrim($hex_color, '#');
        
        $r = hexdec(substr($hex_color, 0, 2)) / 255;
        $g = hexdec(substr($hex_color, 2, 2)) / 255;
        $b = hexdec(substr($hex_color, 4, 2)) / 255;
        
        $r = ($r <= 0.03928) ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = ($g <= 0.03928) ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = ($b <= 0.03928) ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);
        
        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
    
    /**
     * Gera cores harmoniosas baseadas em uma cor base
     * 
     * @param string $base_color Cor base em hexadecimal
     * @param string $harmony_type Tipo de harmonia
     * @return array Array de cores geradas
     */
    public static function generate_harmony($base_color, $harmony_type = 'complementary') {
        $hsl = self::hex_to_hsl($base_color);
        $colors = array();
        
        switch ($harmony_type) {
            case 'complementary':
                $colors[] = $base_color;
                $colors[] = self::hsl_to_hex(($hsl[0] + 180) % 360, $hsl[1], $hsl[2]);
                break;
                
            case 'analogous':
                $colors[] = self::hsl_to_hex(($hsl[0] - 30 + 360) % 360, $hsl[1], $hsl[2]);
                $colors[] = $base_color;
                $colors[] = self::hsl_to_hex(($hsl[0] + 30) % 360, $hsl[1], $hsl[2]);
                break;
                
            case 'triadic':
                $colors[] = $base_color;
                $colors[] = self::hsl_to_hex(($hsl[0] + 120) % 360, $hsl[1], $hsl[2]);
                $colors[] = self::hsl_to_hex(($hsl[0] + 240) % 360, $hsl[1], $hsl[2]);
                break;
                
            case 'tetradic':
                $colors[] = $base_color;
                $colors[] = self::hsl_to_hex(($hsl[0] + 90) % 360, $hsl[1], $hsl[2]);
                $colors[] = self::hsl_to_hex(($hsl[0] + 180) % 360, $hsl[1], $hsl[2]);
                $colors[] = self::hsl_to_hex(($hsl[0] + 270) % 360, $hsl[1], $hsl[2]);
                break;
                
            case 'monochromatic':
                $colors[] = self::hsl_to_hex($hsl[0], $hsl[1], max(0, $hsl[2] - 40));
                $colors[] = self::hsl_to_hex($hsl[0], $hsl[1], max(0, $hsl[2] - 20));
                $colors[] = $base_color;
                $colors[] = self::hsl_to_hex($hsl[0], $hsl[1], min(100, $hsl[2] + 20));
                $colors[] = self::hsl_to_hex($hsl[0], $hsl[1], min(100, $hsl[2] + 40));
                break;
        }
        
        return $colors;
    }
    
    /**
     * Converte cor hexadecimal para HSL
     * 
     * @param string $hex_color
     * @return array [h, s, l]
     */
    private static function hex_to_hsl($hex_color) {
        $hex_color = ltrim($hex_color, '#');
        
        $r = hexdec(substr($hex_color, 0, 2)) / 255;
        $g = hexdec(substr($hex_color, 2, 2)) / 255;
        $b = hexdec(substr($hex_color, 4, 2)) / 255;
        
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $diff = $max - $min;
        
        $l = ($max + $min) / 2;
        
        if ($diff == 0) {
            $h = $s = 0;
        } else {
            $s = $l > 0.5 ? $diff / (2 - $max - $min) : $diff / ($max + $min);
            
            switch ($max) {
                case $r:
                    $h = (($g - $b) / $diff + ($g < $b ? 6 : 0)) / 6;
                    break;
                case $g:
                    $h = (($b - $r) / $diff + 2) / 6;
                    break;
                case $b:
                    $h = (($r - $g) / $diff + 4) / 6;
                    break;
            }
        }
        
        return array(round($h * 360), round($s * 100), round($l * 100));
    }
    
    /**
     * Converte HSL para cor hexadecimal
     * 
     * @param int $h Hue (0-360)
     * @param int $s Saturation (0-100)
     * @param int $l Lightness (0-100)
     * @return string Cor hexadecimal
     */
    private static function hsl_to_hex($h, $s, $l) {
        $h /= 360;
        $s /= 100;
        $l /= 100;
        
        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $hue_to_rgb = function($p, $q, $t) {
                if ($t < 0) $t += 1;
                if ($t > 1) $t -= 1;
                if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
                if ($t < 1/2) return $q;
                if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
                return $p;
            };
            
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            
            $r = $hue_to_rgb($p, $q, $h + 1/3);
            $g = $hue_to_rgb($p, $q, $h);
            $b = $hue_to_rgb($p, $q, $h - 1/3);
        }
        
        $r = round($r * 255);
        $g = round($g * 255);
        $b = round($b * 255);
        
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}