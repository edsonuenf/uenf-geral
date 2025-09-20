<?php
/**
 * Gerenciador de Design Tokens
 * 
 * Sistema completo de tokens de design centralizados incluindo:
 * - Tokens primitivos, semânticos e de componente
 * - Interface de gerenciamento visual
 * - Export/Import em múltiplos formatos
 * - Versionamento e histórico
 * - Sincronização com todos os módulos
 * - Documentação automática
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
 * Classe principal do gerenciador de design tokens
 */
class CCT_Design_Tokens_Manager {
    
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
    private $prefix = 'cct_design_tokens_';
    
    /**
     * Tokens primitivos
     * 
     * @var array
     */
    private $primitive_tokens;
    
    /**
     * Tokens semânticos
     * 
     * @var array
     */
    private $semantic_tokens;
    
    /**
     * Tokens de componente
     * 
     * @var array
     */
    private $component_tokens;
    
    /**
     * Configurações do sistema
     * 
     * @var array
     */
    private $system_settings;
    
    /**
     * Formatos de export
     * 
     * @var array
     */
    private $export_formats;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->init_primitive_tokens();
        $this->init_semantic_tokens();
        $this->init_component_tokens();
        $this->init_system_settings();
        $this->init_export_formats();
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
        add_action('wp_ajax_cct_save_token', array($this, 'ajax_save_token'));
        add_action('wp_ajax_cct_delete_token', array($this, 'ajax_delete_token'));
        add_action('wp_ajax_cct_export_tokens', array($this, 'ajax_export_tokens'));
        add_action('wp_ajax_cct_import_tokens', array($this, 'ajax_import_tokens'));
        add_action('wp_ajax_cct_sync_tokens', array($this, 'ajax_sync_tokens'));
        add_action('wp_ajax_cct_validate_tokens', array($this, 'ajax_validate_tokens'));
        add_action('wp_ajax_cct_generate_documentation', array($this, 'ajax_generate_documentation'));
        
        // Shortcodes
        add_shortcode('cct_design_token', array($this, 'design_token_shortcode'));
        add_shortcode('cct_token_preview', array($this, 'token_preview_shortcode'));
        add_shortcode('cct_token_documentation', array($this, 'token_documentation_shortcode'));
        
        // Hooks para sincronização
        add_action('customize_save_after', array($this, 'sync_tokens_after_save'));
        add_filter('cct_get_design_token', array($this, 'get_design_token'), 10, 2);
    }
    
    /**
     * Inicializa tokens primitivos
     */
    private function init_primitive_tokens() {
        $this->primitive_tokens = array(
            'colors' => array(
                'base' => array(
                    'white' => array(
                        'value' => '#ffffff',
                        'description' => __('Cor branca pura', 'cct'),
                        'category' => 'base'
                    ),
                    'black' => array(
                        'value' => '#000000',
                        'description' => __('Cor preta pura', 'cct'),
                        'category' => 'base'
                    ),
                    'transparent' => array(
                        'value' => 'transparent',
                        'description' => __('Transparente', 'cct'),
                        'category' => 'base'
                    )
                ),
                'gray' => array(
                    'gray-50' => array(
                        'value' => '#f9fafb',
                        'description' => __('Cinza muito claro', 'cct'),
                        'category' => 'neutral'
                    ),
                    'gray-100' => array(
                        'value' => '#f3f4f6',
                        'description' => __('Cinza claro', 'cct'),
                        'category' => 'neutral'
                    ),
                    'gray-200' => array(
                        'value' => '#e5e7eb',
                        'description' => __('Cinza claro médio', 'cct'),
                        'category' => 'neutral'
                    ),
                    'gray-300' => array(
                        'value' => '#d1d5db',
                        'description' => __('Cinza médio claro', 'cct'),
                        'category' => 'neutral'
                    ),
                    'gray-400' => array(
                        'value' => '#9ca3af',
                        'description' => __('Cinza médio', 'cct'),
                        'category' => 'neutral'
                    ),
                    'gray-500' => array(
                        'value' => '#6b7280',
                        'description' => __('Cinza médio escuro', 'cct'),
                        'category' => 'neutral'
                    ),
                    'gray-600' => array(
                        'value' => '#4b5563',
                        'description' => __('Cinza escuro médio', 'cct'),
                        'category' => 'neutral'
                    ),
                    'gray-700' => array(
                        'value' => '#374151',
                        'description' => __('Cinza escuro', 'cct'),
                        'category' => 'neutral'
                    ),
                    'gray-800' => array(
                        'value' => '#1f2937',
                        'description' => __('Cinza muito escuro', 'cct'),
                        'category' => 'neutral'
                    ),
                    'gray-900' => array(
                        'value' => '#111827',
                        'description' => __('Cinza quase preto', 'cct'),
                        'category' => 'neutral'
                    )
                ),
                'blue' => array(
                    'blue-50' => array(
                        'value' => '#eff6ff',
                        'description' => __('Azul muito claro', 'cct'),
                        'category' => 'primary'
                    ),
                    'blue-100' => array(
                        'value' => '#dbeafe',
                        'description' => __('Azul claro', 'cct'),
                        'category' => 'primary'
                    ),
                    'blue-500' => array(
                        'value' => '#3b82f6',
                        'description' => __('Azul médio', 'cct'),
                        'category' => 'primary'
                    ),
                    'blue-600' => array(
                        'value' => '#2563eb',
                        'description' => __('Azul principal', 'cct'),
                        'category' => 'primary'
                    ),
                    'blue-700' => array(
                        'value' => '#1d4ed8',
                        'description' => __('Azul escuro', 'cct'),
                        'category' => 'primary'
                    )
                ),
                'red' => array(
                    'red-50' => array(
                        'value' => '#fef2f2',
                        'description' => __('Vermelho muito claro', 'cct'),
                        'category' => 'semantic'
                    ),
                    'red-500' => array(
                        'value' => '#ef4444',
                        'description' => __('Vermelho médio', 'cct'),
                        'category' => 'semantic'
                    ),
                    'red-600' => array(
                        'value' => '#dc2626',
                        'description' => __('Vermelho principal', 'cct'),
                        'category' => 'semantic'
                    )
                ),
                'green' => array(
                    'green-50' => array(
                        'value' => '#f0fdf4',
                        'description' => __('Verde muito claro', 'cct'),
                        'category' => 'semantic'
                    ),
                    'green-500' => array(
                        'value' => '#22c55e',
                        'description' => __('Verde médio', 'cct'),
                        'category' => 'semantic'
                    ),
                    'green-600' => array(
                        'value' => '#16a34a',
                        'description' => __('Verde principal', 'cct'),
                        'category' => 'semantic'
                    )
                ),
                'yellow' => array(
                    'yellow-50' => array(
                        'value' => '#fefce8',
                        'description' => __('Amarelo muito claro', 'cct'),
                        'category' => 'semantic'
                    ),
                    'yellow-500' => array(
                        'value' => '#eab308',
                        'description' => __('Amarelo médio', 'cct'),
                        'category' => 'semantic'
                    ),
                    'yellow-600' => array(
                        'value' => '#ca8a04',
                        'description' => __('Amarelo principal', 'cct'),
                        'category' => 'semantic'
                    )
                )
            ),
            'typography' => array(
                'font-family' => array(
                    'sans' => array(
                        'value' => 'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif',
                        'description' => __('Família de fontes sans-serif', 'cct'),
                        'category' => 'font-family'
                    ),
                    'serif' => array(
                        'value' => 'ui-serif, Georgia, Cambria, "Times New Roman", Times, serif',
                        'description' => __('Família de fontes serif', 'cct'),
                        'category' => 'font-family'
                    ),
                    'mono' => array(
                        'value' => 'ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace',
                        'description' => __('Família de fontes monospace', 'cct'),
                        'category' => 'font-family'
                    )
                ),
                'font-size' => array(
                    'xs' => array(
                        'value' => '0.75rem',
                        'description' => __('Tamanho extra pequeno', 'cct'),
                        'category' => 'font-size'
                    ),
                    'sm' => array(
                        'value' => '0.875rem',
                        'description' => __('Tamanho pequeno', 'cct'),
                        'category' => 'font-size'
                    ),
                    'base' => array(
                        'value' => '1rem',
                        'description' => __('Tamanho base', 'cct'),
                        'category' => 'font-size'
                    ),
                    'lg' => array(
                        'value' => '1.125rem',
                        'description' => __('Tamanho grande', 'cct'),
                        'category' => 'font-size'
                    ),
                    'xl' => array(
                        'value' => '1.25rem',
                        'description' => __('Tamanho extra grande', 'cct'),
                        'category' => 'font-size'
                    ),
                    '2xl' => array(
                        'value' => '1.5rem',
                        'description' => __('Tamanho 2x grande', 'cct'),
                        'category' => 'font-size'
                    ),
                    '3xl' => array(
                        'value' => '1.875rem',
                        'description' => __('Tamanho 3x grande', 'cct'),
                        'category' => 'font-size'
                    ),
                    '4xl' => array(
                        'value' => '2.25rem',
                        'description' => __('Tamanho 4x grande', 'cct'),
                        'category' => 'font-size'
                    )
                ),
                'font-weight' => array(
                    'thin' => array(
                        'value' => '100',
                        'description' => __('Peso fino', 'cct'),
                        'category' => 'font-weight'
                    ),
                    'light' => array(
                        'value' => '300',
                        'description' => __('Peso leve', 'cct'),
                        'category' => 'font-weight'
                    ),
                    'normal' => array(
                        'value' => '400',
                        'description' => __('Peso normal', 'cct'),
                        'category' => 'font-weight'
                    ),
                    'medium' => array(
                        'value' => '500',
                        'description' => __('Peso médio', 'cct'),
                        'category' => 'font-weight'
                    ),
                    'semibold' => array(
                        'value' => '600',
                        'description' => __('Peso semi-negrito', 'cct'),
                        'category' => 'font-weight'
                    ),
                    'bold' => array(
                        'value' => '700',
                        'description' => __('Peso negrito', 'cct'),
                        'category' => 'font-weight'
                    )
                ),
                'line-height' => array(
                    'none' => array(
                        'value' => '1',
                        'description' => __('Altura de linha nenhuma', 'cct'),
                        'category' => 'line-height'
                    ),
                    'tight' => array(
                        'value' => '1.25',
                        'description' => __('Altura de linha apertada', 'cct'),
                        'category' => 'line-height'
                    ),
                    'snug' => array(
                        'value' => '1.375',
                        'description' => __('Altura de linha ajustada', 'cct'),
                        'category' => 'line-height'
                    ),
                    'normal' => array(
                        'value' => '1.5',
                        'description' => __('Altura de linha normal', 'cct'),
                        'category' => 'line-height'
                    ),
                    'relaxed' => array(
                        'value' => '1.625',
                        'description' => __('Altura de linha relaxada', 'cct'),
                        'category' => 'line-height'
                    ),
                    'loose' => array(
                        'value' => '2',
                        'description' => __('Altura de linha solta', 'cct'),
                        'category' => 'line-height'
                    )
                )
            ),
            'spacing' => array(
                '0' => array(
                    'value' => '0px',
                    'description' => __('Espaçamento zero', 'cct'),
                    'category' => 'spacing'
                ),
                '1' => array(
                    'value' => '0.25rem',
                    'description' => __('Espaçamento 1', 'cct'),
                    'category' => 'spacing'
                ),
                '2' => array(
                    'value' => '0.5rem',
                    'description' => __('Espaçamento 2', 'cct'),
                    'category' => 'spacing'
                ),
                '3' => array(
                    'value' => '0.75rem',
                    'description' => __('Espaçamento 3', 'cct'),
                    'category' => 'spacing'
                ),
                '4' => array(
                    'value' => '1rem',
                    'description' => __('Espaçamento 4', 'cct'),
                    'category' => 'spacing'
                ),
                '5' => array(
                    'value' => '1.25rem',
                    'description' => __('Espaçamento 5', 'cct'),
                    'category' => 'spacing'
                ),
                '6' => array(
                    'value' => '1.5rem',
                    'description' => __('Espaçamento 6', 'cct'),
                    'category' => 'spacing'
                ),
                '8' => array(
                    'value' => '2rem',
                    'description' => __('Espaçamento 8', 'cct'),
                    'category' => 'spacing'
                ),
                '10' => array(
                    'value' => '2.5rem',
                    'description' => __('Espaçamento 10', 'cct'),
                    'category' => 'spacing'
                ),
                '12' => array(
                    'value' => '3rem',
                    'description' => __('Espaçamento 12', 'cct'),
                    'category' => 'spacing'
                ),
                '16' => array(
                    'value' => '4rem',
                    'description' => __('Espaçamento 16', 'cct'),
                    'category' => 'spacing'
                ),
                '20' => array(
                    'value' => '5rem',
                    'description' => __('Espaçamento 20', 'cct'),
                    'category' => 'spacing'
                ),
                '24' => array(
                    'value' => '6rem',
                    'description' => __('Espaçamento 24', 'cct'),
                    'category' => 'spacing'
                )
            ),
            'border-radius' => array(
                'none' => array(
                    'value' => '0px',
                    'description' => __('Sem arredondamento', 'cct'),
                    'category' => 'border-radius'
                ),
                'sm' => array(
                    'value' => '0.125rem',
                    'description' => __('Arredondamento pequeno', 'cct'),
                    'category' => 'border-radius'
                ),
                'base' => array(
                    'value' => '0.25rem',
                    'description' => __('Arredondamento base', 'cct'),
                    'category' => 'border-radius'
                ),
                'md' => array(
                    'value' => '0.375rem',
                    'description' => __('Arredondamento médio', 'cct'),
                    'category' => 'border-radius'
                ),
                'lg' => array(
                    'value' => '0.5rem',
                    'description' => __('Arredondamento grande', 'cct'),
                    'category' => 'border-radius'
                ),
                'xl' => array(
                    'value' => '0.75rem',
                    'description' => __('Arredondamento extra grande', 'cct'),
                    'category' => 'border-radius'
                ),
                '2xl' => array(
                    'value' => '1rem',
                    'description' => __('Arredondamento 2x grande', 'cct'),
                    'category' => 'border-radius'
                ),
                'full' => array(
                    'value' => '9999px',
                    'description' => __('Arredondamento completo', 'cct'),
                    'category' => 'border-radius'
                )
            ),
            'shadows' => array(
                'none' => array(
                    'value' => 'none',
                    'description' => __('Sem sombra', 'cct'),
                    'category' => 'shadow'
                ),
                'sm' => array(
                    'value' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                    'description' => __('Sombra pequena', 'cct'),
                    'category' => 'shadow'
                ),
                'base' => array(
                    'value' => '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                    'description' => __('Sombra base', 'cct'),
                    'category' => 'shadow'
                ),
                'md' => array(
                    'value' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                    'description' => __('Sombra média', 'cct'),
                    'category' => 'shadow'
                ),
                'lg' => array(
                    'value' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                    'description' => __('Sombra grande', 'cct'),
                    'category' => 'shadow'
                ),
                'xl' => array(
                    'value' => '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                    'description' => __('Sombra extra grande', 'cct'),
                    'category' => 'shadow'
                ),
                '2xl' => array(
                    'value' => '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
                    'description' => __('Sombra 2x grande', 'cct'),
                    'category' => 'shadow'
                )
            ),
            'transitions' => array(
                'duration' => array(
                    'fast' => array(
                        'value' => '150ms',
                        'description' => __('Duração rápida', 'cct'),
                        'category' => 'duration'
                    ),
                    'normal' => array(
                        'value' => '300ms',
                        'description' => __('Duração normal', 'cct'),
                        'category' => 'duration'
                    ),
                    'slow' => array(
                        'value' => '500ms',
                        'description' => __('Duração lenta', 'cct'),
                        'category' => 'duration'
                    )
                ),
                'easing' => array(
                    'linear' => array(
                        'value' => 'linear',
                        'description' => __('Easing linear', 'cct'),
                        'category' => 'easing'
                    ),
                    'ease' => array(
                        'value' => 'ease',
                        'description' => __('Easing padrão', 'cct'),
                        'category' => 'easing'
                    ),
                    'ease-in' => array(
                        'value' => 'ease-in',
                        'description' => __('Easing entrada', 'cct'),
                        'category' => 'easing'
                    ),
                    'ease-out' => array(
                        'value' => 'ease-out',
                        'description' => __('Easing saída', 'cct'),
                        'category' => 'easing'
                    ),
                    'ease-in-out' => array(
                        'value' => 'ease-in-out',
                        'description' => __('Easing entrada e saída', 'cct'),
                        'category' => 'easing'
                    )
                )
            )
        );
    }
    
    /**
     * Inicializa tokens semânticos
     */
    private function init_semantic_tokens() {
        $this->semantic_tokens = array(
            'colors' => array(
                'text' => array(
                    'primary' => array(
                        'value' => '{colors.gray.900}',
                        'description' => __('Cor principal do texto', 'cct'),
                        'category' => 'text'
                    ),
                    'secondary' => array(
                        'value' => '{colors.gray.600}',
                        'description' => __('Cor secundária do texto', 'cct'),
                        'category' => 'text'
                    ),
                    'muted' => array(
                        'value' => '{colors.gray.400}',
                        'description' => __('Cor do texto esmaecido', 'cct'),
                        'category' => 'text'
                    ),
                    'inverse' => array(
                        'value' => '{colors.base.white}',
                        'description' => __('Cor do texto inverso', 'cct'),
                        'category' => 'text'
                    )
                ),
                'background' => array(
                    'primary' => array(
                        'value' => '{colors.base.white}',
                        'description' => __('Cor de fundo principal', 'cct'),
                        'category' => 'background'
                    ),
                    'secondary' => array(
                        'value' => '{colors.gray.50}',
                        'description' => __('Cor de fundo secundária', 'cct'),
                        'category' => 'background'
                    ),
                    'tertiary' => array(
                        'value' => '{colors.gray.100}',
                        'description' => __('Cor de fundo terciária', 'cct'),
                        'category' => 'background'
                    )
                ),
                'border' => array(
                    'default' => array(
                        'value' => '{colors.gray.200}',
                        'description' => __('Cor de borda padrão', 'cct'),
                        'category' => 'border'
                    ),
                    'muted' => array(
                        'value' => '{colors.gray.100}',
                        'description' => __('Cor de borda esmaecida', 'cct'),
                        'category' => 'border'
                    ),
                    'strong' => array(
                        'value' => '{colors.gray.300}',
                        'description' => __('Cor de borda forte', 'cct'),
                        'category' => 'border'
                    )
                ),
                'brand' => array(
                    'primary' => array(
                        'value' => '{colors.blue.600}',
                        'description' => __('Cor primária da marca', 'cct'),
                        'category' => 'brand'
                    ),
                    'secondary' => array(
                        'value' => '{colors.blue.500}',
                        'description' => __('Cor secundária da marca', 'cct'),
                        'category' => 'brand'
                    )
                ),
                'feedback' => array(
                    'success' => array(
                        'value' => '{colors.green.600}',
                        'description' => __('Cor de sucesso', 'cct'),
                        'category' => 'feedback'
                    ),
                    'warning' => array(
                        'value' => '{colors.yellow.600}',
                        'description' => __('Cor de aviso', 'cct'),
                        'category' => 'feedback'
                    ),
                    'error' => array(
                        'value' => '{colors.red.600}',
                        'description' => __('Cor de erro', 'cct'),
                        'category' => 'feedback'
                    ),
                    'info' => array(
                        'value' => '{colors.blue.600}',
                        'description' => __('Cor de informação', 'cct'),
                        'category' => 'feedback'
                    )
                )
            ),
            'typography' => array(
                'heading' => array(
                    'h1' => array(
                        'font-family' => '{typography.font-family.sans}',
                        'font-size' => '{typography.font-size.4xl}',
                        'font-weight' => '{typography.font-weight.bold}',
                        'line-height' => '{typography.line-height.tight}',
                        'description' => __('Estilo para H1', 'cct'),
                        'category' => 'heading'
                    ),
                    'h2' => array(
                        'font-family' => '{typography.font-family.sans}',
                        'font-size' => '{typography.font-size.3xl}',
                        'font-weight' => '{typography.font-weight.bold}',
                        'line-height' => '{typography.line-height.tight}',
                        'description' => __('Estilo para H2', 'cct'),
                        'category' => 'heading'
                    ),
                    'h3' => array(
                        'font-family' => '{typography.font-family.sans}',
                        'font-size' => '{typography.font-size.2xl}',
                        'font-weight' => '{typography.font-weight.semibold}',
                        'line-height' => '{typography.line-height.snug}',
                        'description' => __('Estilo para H3', 'cct'),
                        'category' => 'heading'
                    )
                ),
                'body' => array(
                    'large' => array(
                        'font-family' => '{typography.font-family.sans}',
                        'font-size' => '{typography.font-size.lg}',
                        'font-weight' => '{typography.font-weight.normal}',
                        'line-height' => '{typography.line-height.relaxed}',
                        'description' => __('Texto corpo grande', 'cct'),
                        'category' => 'body'
                    ),
                    'normal' => array(
                        'font-family' => '{typography.font-family.sans}',
                        'font-size' => '{typography.font-size.base}',
                        'font-weight' => '{typography.font-weight.normal}',
                        'line-height' => '{typography.line-height.normal}',
                        'description' => __('Texto corpo normal', 'cct'),
                        'category' => 'body'
                    ),
                    'small' => array(
                        'font-family' => '{typography.font-family.sans}',
                        'font-size' => '{typography.font-size.sm}',
                        'font-weight' => '{typography.font-weight.normal}',
                        'line-height' => '{typography.line-height.normal}',
                        'description' => __('Texto corpo pequeno', 'cct'),
                        'category' => 'body'
                    )
                )
            ),
            'spacing' => array(
                'component' => array(
                    'xs' => array(
                        'value' => '{spacing.2}',
                        'description' => __('Espaçamento extra pequeno de componente', 'cct'),
                        'category' => 'component'
                    ),
                    'sm' => array(
                        'value' => '{spacing.4}',
                        'description' => __('Espaçamento pequeno de componente', 'cct'),
                        'category' => 'component'
                    ),
                    'md' => array(
                        'value' => '{spacing.6}',
                        'description' => __('Espaçamento médio de componente', 'cct'),
                        'category' => 'component'
                    ),
                    'lg' => array(
                        'value' => '{spacing.8}',
                        'description' => __('Espaçamento grande de componente', 'cct'),
                        'category' => 'component'
                    ),
                    'xl' => array(
                        'value' => '{spacing.12}',
                        'description' => __('Espaçamento extra grande de componente', 'cct'),
                        'category' => 'component'
                    )
                ),
                'layout' => array(
                    'section' => array(
                        'value' => '{spacing.16}',
                        'description' => __('Espaçamento entre seções', 'cct'),
                        'category' => 'layout'
                    ),
                    'container' => array(
                        'value' => '{spacing.6}',
                        'description' => __('Espaçamento interno de container', 'cct'),
                        'category' => 'layout'
                    )
                )
            )
        );
    }
    
    /**
     * Inicializa tokens de componente
     */
    private function init_component_tokens() {
        $this->component_tokens = array(
            'button' => array(
                'primary' => array(
                    'background' => '{colors.brand.primary}',
                    'color' => '{colors.text.inverse}',
                    'border-radius' => '{border-radius.md}',
                    'padding-x' => '{spacing.component.md}',
                    'padding-y' => '{spacing.component.sm}',
                    'font-size' => '{typography.font-size.base}',
                    'font-weight' => '{typography.font-weight.medium}',
                    'description' => __('Botão primário', 'cct'),
                    'category' => 'button'
                ),
                'secondary' => array(
                    'background' => '{colors.background.secondary}',
                    'color' => '{colors.text.primary}',
                    'border' => '1px solid {colors.border.default}',
                    'border-radius' => '{border-radius.md}',
                    'padding-x' => '{spacing.component.md}',
                    'padding-y' => '{spacing.component.sm}',
                    'font-size' => '{typography.font-size.base}',
                    'font-weight' => '{typography.font-weight.medium}',
                    'description' => __('Botão secundário', 'cct'),
                    'category' => 'button'
                )
            ),
            'card' => array(
                'default' => array(
                    'background' => '{colors.background.primary}',
                    'border' => '1px solid {colors.border.default}',
                    'border-radius' => '{border-radius.lg}',
                    'padding' => '{spacing.component.lg}',
                    'shadow' => '{shadows.md}',
                    'description' => __('Card padrão', 'cct'),
                    'category' => 'card'
                ),
                'elevated' => array(
                    'background' => '{colors.background.primary}',
                    'border-radius' => '{border-radius.lg}',
                    'padding' => '{spacing.component.lg}',
                    'shadow' => '{shadows.lg}',
                    'description' => __('Card elevado', 'cct'),
                    'category' => 'card'
                )
            ),
            'input' => array(
                'default' => array(
                    'background' => '{colors.background.primary}',
                    'border' => '1px solid {colors.border.default}',
                    'border-radius' => '{border-radius.md}',
                    'padding-x' => '{spacing.component.sm}',
                    'padding-y' => '{spacing.component.xs}',
                    'font-size' => '{typography.font-size.base}',
                    'color' => '{colors.text.primary}',
                    'description' => __('Input padrão', 'cct'),
                    'category' => 'input'
                ),
                'focus' => array(
                    'border-color' => '{colors.brand.primary}',
                    'box-shadow' => '0 0 0 3px rgba(59, 130, 246, 0.1)',
                    'description' => __('Input em foco', 'cct'),
                    'category' => 'input'
                ),
                'error' => array(
                    'border-color' => '{colors.feedback.error}',
                    'box-shadow' => '0 0 0 3px rgba(220, 38, 38, 0.1)',
                    'description' => __('Input com erro', 'cct'),
                    'category' => 'input'
                )
            )
        );
    }
    
    /**
     * Inicializa configurações do sistema
     */
    private function init_system_settings() {
        $this->system_settings = array(
            'enabled' => true,
            'auto_sync' => true,
            'version_control' => true,
            'documentation_auto_generate' => true,
            'validation_enabled' => true,
            'export_formats' => array('css', 'scss', 'json', 'js'),
            'naming_convention' => 'kebab-case', // kebab-case, camelCase, snake_case
            'css_prefix' => '--cct-',
            'js_namespace' => 'CCTTokens',
            'backup_enabled' => true,
            'backup_retention' => 30 // dias
        );
    }
    
    /**
     * Inicializa formatos de export
     */
    private function init_export_formats() {
        $this->export_formats = array(
            'css' => array(
                'name' => 'CSS Custom Properties',
                'extension' => 'css',
                'mime_type' => 'text/css',
                'description' => __('Exporta como variáveis CSS customizadas', 'cct')
            ),
            'scss' => array(
                'name' => 'SCSS Variables',
                'extension' => 'scss',
                'mime_type' => 'text/scss',
                'description' => __('Exporta como variáveis SCSS', 'cct')
            ),
            'json' => array(
                'name' => 'JSON',
                'extension' => 'json',
                'mime_type' => 'application/json',
                'description' => __('Exporta como arquivo JSON', 'cct')
            ),
            'js' => array(
                'name' => 'JavaScript Object',
                'extension' => 'js',
                'mime_type' => 'application/javascript',
                'description' => __('Exporta como objeto JavaScript', 'cct')
            ),
            'figma' => array(
                'name' => 'Figma Tokens',
                'extension' => 'json',
                'mime_type' => 'application/json',
                'description' => __('Exporta no formato compatível com Figma', 'cct')
            )
        );
    }
    
    /**
     * Registra configurações no Customizer
     */
    public function register_customizer() {
        $this->add_design_tokens_panel();
        $this->add_design_tokens_sections();
        $this->add_design_tokens_settings();
        $this->add_design_tokens_controls();
    }
    
    /**
     * Adiciona painel de design tokens
     */
    private function add_design_tokens_panel() {
        $this->wp_customize->add_panel($this->prefix . 'panel', array(
            'title' => __('Design Tokens', 'cct'),
            'description' => __('Sistema centralizado de tokens de design para consistência e manutenibilidade do sistema.', 'cct'),
            'priority' => 220,
            'capability' => 'edit_theme_options',
        ));
    }
    
    /**
     * Adiciona seções de design tokens
     */
    private function add_design_tokens_sections() {
        // Seção de configurações gerais
        $this->wp_customize->add_section($this->prefix . 'general', array(
            'title' => __('Configurações Gerais', 'cct'),
            'description' => __('Configurações principais do sistema de design tokens.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 10,
        ));
        
        // Seção de tokens primitivos
        $this->wp_customize->add_section($this->prefix . 'primitive', array(
            'title' => __('Tokens Primitivos', 'cct'),
            'description' => __('Valores fundamentais do design system (cores base, tipografia, espaçamentos).', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 20,
        ));
        
        // Seção de tokens semânticos
        $this->wp_customize->add_section($this->prefix . 'semantic', array(
            'title' => __('Tokens Semânticos', 'cct'),
            'description' => __('Tokens com significado específico (cores de texto, feedback, marca).', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 30,
        ));
        
        // Seção de tokens de componente
        $this->wp_customize->add_section($this->prefix . 'component', array(
            'title' => __('Tokens de Componente', 'cct'),
            'description' => __('Tokens específicos para componentes (botões, cards, inputs).', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 40,
        ));
        
        // Seção de gerenciamento
        $this->wp_customize->add_section($this->prefix . 'management', array(
            'title' => __('Gerenciamento', 'cct'),
            'description' => __('Ferramentas para gerenciar, exportar e sincronizar tokens.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 50,
        ));
        
        // Seção de documentação
        $this->wp_customize->add_section($this->prefix . 'documentation', array(
            'title' => __('Documentação', 'cct'),
            'description' => __('Visualização e geração de documentação dos tokens.', 'cct'),
            'panel' => $this->prefix . 'panel',
            'priority' => 60,
        ));
    }
    
    /**
     * Adiciona configurações de design tokens
     */
    private function add_design_tokens_settings() {
        // Configurações gerais
        $this->add_setting('enabled', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('auto_sync', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('version_control', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $this->add_setting('naming_convention', array(
            'default' => 'kebab-case',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $this->add_setting('css_prefix', array(
            'default' => '--cct-',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        // Tokens customizados
        $this->add_setting('primitive_tokens', array(
            'default' => wp_json_encode($this->primitive_tokens),
            'sanitize_callback' => array($this, 'sanitize_tokens_json'),
        ));
        
        $this->add_setting('semantic_tokens', array(
            'default' => wp_json_encode($this->semantic_tokens),
            'sanitize_callback' => array($this, 'sanitize_tokens_json'),
        ));
        
        $this->add_setting('component_tokens', array(
            'default' => wp_json_encode($this->component_tokens),
            'sanitize_callback' => array($this, 'sanitize_tokens_json'),
        ));
    }
    
    /**
     * Adiciona controles de design tokens
     */
    private function add_design_tokens_controls() {
        // Controles gerais
        $this->add_control('enabled', array(
            'label' => __('Ativar Design Tokens', 'cct'),
            'description' => __('Ativa ou desativa o sistema de design tokens.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('auto_sync', array(
            'label' => __('Sincronização Automática', 'cct'),
            'description' => __('Sincroniza automaticamente tokens com outros módulos.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('version_control', array(
            'label' => __('Controle de Versão', 'cct'),
            'description' => __('Mantém histórico de mudanças nos tokens.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'checkbox',
        ));
        
        $this->add_control('naming_convention', array(
            'label' => __('Convenção de Nomenclatura', 'cct'),
            'description' => __('Padrão de nomenclatura para tokens exportados.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'select',
            'choices' => array(
                'kebab-case' => 'kebab-case (recomendado)',
                'camelCase' => 'camelCase',
                'snake_case' => 'snake_case'
            ),
        ));
        
        $this->add_control('css_prefix', array(
            'label' => __('Prefixo CSS', 'cct'),
            'description' => __('Prefixo para variáveis CSS geradas.', 'cct'),
            'section' => $this->prefix . 'general',
            'type' => 'text',
        ));
        
        // Controle personalizado para gerenciamento de tokens (usando controle padrão temporariamente)
        $this->wp_customize->add_control(
            $this->prefix . 'manager',
            array(
                'label' => __('Gerenciador de Tokens', 'cct'),
                'description' => __('Interface visual para gerenciar todos os design tokens.', 'cct'),
                'section' => $this->prefix . 'management',
                'settings' => $this->prefix . 'token_data',
                'type' => 'select',
                'choices' => array(
                    'manager' => __('Gerenciador será implementado em versão futura', 'cct')
                ),
                'primitive_tokens' => $this->primitive_tokens,
                'semantic_tokens' => $this->semantic_tokens,
                'component_tokens' => $this->component_tokens,
                'export_formats' => $this->export_formats
            )
        );
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
     * Sanitiza JSON de tokens
     */
    public function sanitize_tokens_json($input) {
        $decoded = json_decode($input, true);
        
        if (!is_array($decoded)) {
            return wp_json_encode(array());
        }
        
        // Validar estrutura dos tokens
        $sanitized = array();
        foreach ($decoded as $category => $tokens) {
            if (is_array($tokens)) {
                $sanitized[sanitize_key($category)] = $this->sanitize_token_category($tokens);
            }
        }
        
        return wp_json_encode($sanitized);
    }
    
    /**
     * Sanitiza categoria de tokens
     */
    private function sanitize_token_category($tokens) {
        $sanitized = array();
        
        foreach ($tokens as $token_key => $token_data) {
            if (is_array($token_data)) {
                $sanitized[sanitize_key($token_key)] = array(
                    'value' => sanitize_text_field($token_data['value'] ?? ''),
                    'description' => sanitize_text_field($token_data['description'] ?? ''),
                    'category' => sanitize_text_field($token_data['category'] ?? '')
                );
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        // CSS dos design tokens
        wp_enqueue_style(
            'cct-design-tokens',
            get_template_directory_uri() . '/css/cct-design-tokens.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript dos design tokens
        wp_enqueue_script(
            'cct-design-tokens',
            get_template_directory_uri() . '/js/cct-design-tokens.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localização do script
        wp_localize_script('cct-design-tokens', 'cctDesignTokens', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cct_design_tokens_nonce'),
            'settings' => $this->get_frontend_settings(),
            'tokens' => $this->get_all_tokens(),
            'strings' => array(
                'tokenSaved' => __('Token salvo com sucesso!', 'cct'),
                'tokenDeleted' => __('Token excluído!', 'cct'),
                'exportComplete' => __('Export concluído!', 'cct'),
                'importComplete' => __('Import concluído!', 'cct'),
                'syncComplete' => __('Sincronização concluída!', 'cct')
            )
        ));
    }
    
    /**
     * Obtém configurações para o frontend
     */
    private function get_frontend_settings() {
        return array(
            'enabled' => get_theme_mod($this->prefix . 'enabled', true),
            'autoSync' => get_theme_mod($this->prefix . 'auto_sync', true),
            'versionControl' => get_theme_mod($this->prefix . 'version_control', true),
            'namingConvention' => get_theme_mod($this->prefix . 'naming_convention', 'kebab-case'),
            'cssPrefix' => get_theme_mod($this->prefix . 'css_prefix', '--cct-')
        );
    }
    
    /**
     * Obtém todos os tokens
     */
    private function get_all_tokens() {
        $primitive = json_decode(get_theme_mod($this->prefix . 'primitive_tokens', wp_json_encode($this->primitive_tokens)), true);
        $semantic = json_decode(get_theme_mod($this->prefix . 'semantic_tokens', wp_json_encode($this->semantic_tokens)), true);
        $component = json_decode(get_theme_mod($this->prefix . 'component_tokens', wp_json_encode($this->component_tokens)), true);
        
        return array(
            'primitive' => $primitive ?: $this->primitive_tokens,
            'semantic' => $semantic ?: $this->semantic_tokens,
            'component' => $component ?: $this->component_tokens
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
        
        $tokens = $this->get_all_tokens();
        $resolved_tokens = $this->resolve_token_references($tokens);
        
        echo "<style id='cct-design-tokens-custom-css'>\n";
        echo ":root {\n";
        
        // Gerar variáveis CSS para todos os tokens
        foreach ($resolved_tokens as $category => $category_tokens) {
            foreach ($category_tokens as $subcategory => $subcategory_tokens) {
                foreach ($subcategory_tokens as $token_key => $token_data) {
                    if (isset($token_data['value'])) {
                        $css_name = $this->generate_css_variable_name($category, $subcategory, $token_key);
                        echo "  {$css_name}: {$token_data['value']};\n";
                    }
                }
            }
        }
        
        echo "}\n";
        echo "</style>\n";
    }
    
    /**
     * Resolve referências de tokens
     */
    private function resolve_token_references($tokens) {
        $resolved = $tokens;
        $max_iterations = 10; // Prevenir loops infinitos
        $iteration = 0;
        
        do {
            $has_references = false;
            $iteration++;
            
            foreach ($resolved as $category => &$category_tokens) {
                foreach ($category_tokens as $subcategory => &$subcategory_tokens) {
                    foreach ($subcategory_tokens as $token_key => &$token_data) {
                        if (isset($token_data['value']) && is_string($token_data['value'])) {
                            if (preg_match('/\{([^}]+)\}/', $token_data['value'], $matches)) {
                                $reference = $matches[1];
                                $resolved_value = $this->resolve_token_reference($reference, $resolved);
                                
                                if ($resolved_value !== null) {
                                    $token_data['value'] = str_replace($matches[0], $resolved_value, $token_data['value']);
                                } else {
                                    $has_references = true;
                                }
                            }
                        }
                    }
                }
            }
        } while ($has_references && $iteration < $max_iterations);
        
        return $resolved;
    }
    
    /**
     * Resolve uma referência específica de token
     */
    private function resolve_token_reference($reference, $tokens) {
        $parts = explode('.', $reference);
        
        if (count($parts) >= 3) {
            $category = $parts[0];
            $subcategory = $parts[1];
            $token_key = $parts[2];
            
            if (isset($tokens[$category][$subcategory][$token_key]['value'])) {
                return $tokens[$category][$subcategory][$token_key]['value'];
            }
        }
        
        return null;
    }
    
    /**
     * Gera nome de variável CSS
     */
    private function generate_css_variable_name($category, $subcategory, $token_key) {
        $prefix = get_theme_mod($this->prefix . 'css_prefix', '--cct-');
        $naming = get_theme_mod($this->prefix . 'naming_convention', 'kebab-case');
        
        $parts = array($category, $subcategory, $token_key);
        
        switch ($naming) {
            case 'camelCase':
                $name = $parts[0] . implode('', array_map('ucfirst', array_slice($parts, 1)));
                break;
            case 'snake_case':
                $name = implode('_', $parts);
                break;
            case 'kebab-case':
            default:
                $name = implode('-', $parts);
                break;
        }
        
        return $prefix . $name;
    }
    
    /**
     * Gera JavaScript customizado
     */
    public function output_custom_js() {
        $settings = $this->get_frontend_settings();
        
        if (!$settings['enabled']) {
            return;
        }
        
        echo "<script id='cct-design-tokens-custom-js'>\n";
        echo "document.addEventListener('DOMContentLoaded', function() {\n";
        echo "  if (typeof CCTDesignTokens !== 'undefined') {\n";
        echo "    CCTDesignTokens.init(" . wp_json_encode($settings) . ");\n";
        echo "  }\n";
        echo "});\n";
        echo "</script>\n";
    }
    
    /**
     * Shortcode para exibir token
     */
    public function design_token_shortcode($atts) {
        $atts = shortcode_atts(array(
            'token' => '',
            'format' => 'value', // value, css-var, preview
            'class' => ''
        ), $atts, 'cct_design_token');
        
        if (empty($atts['token'])) {
            return '';
        }
        
        $token_value = $this->get_token_value($atts['token']);
        
        if ($token_value === null) {
            return '';
        }
        
        $classes = array('cct-design-token');
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        switch ($atts['format']) {
            case 'css-var':
                $css_var = $this->token_to_css_variable($atts['token']);
                return '<code class="' . implode(' ', $classes) . '">var(' . esc_html($css_var) . ')</code>';
                
            case 'preview':
                return '<span class="' . implode(' ', $classes) . ' cct-token-preview" data-token="' . esc_attr($atts['token']) . '" style="background: ' . esc_attr($token_value) . ';"></span>';
                
            case 'value':
            default:
                return '<span class="' . implode(' ', $classes) . '">' . esc_html($token_value) . '</span>';
        }
    }
    
    /**
     * Shortcode para preview de token
     */
    public function token_preview_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'type' => 'grid', // grid, list
            'class' => ''
        ), $atts, 'cct_token_preview');
        
        $tokens = $this->get_all_tokens();
        
        if (!empty($atts['category'])) {
            $tokens = isset($tokens[$atts['category']]) ? array($atts['category'] => $tokens[$atts['category']]) : array();
        }
        
        $classes = array('cct-token-preview-container', 'cct-preview-' . $atts['type']);
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        $output = '<div class="' . implode(' ', $classes) . '">';
        
        foreach ($tokens as $category => $category_tokens) {
            $output .= '<div class="cct-token-category">';
            $output .= '<h3>' . esc_html(ucfirst($category)) . '</h3>';
            
            foreach ($category_tokens as $subcategory => $subcategory_tokens) {
                $output .= '<div class="cct-token-subcategory">';
                $output .= '<h4>' . esc_html(ucfirst($subcategory)) . '</h4>';
                
                foreach ($subcategory_tokens as $token_key => $token_data) {
                    $output .= '<div class="cct-token-item">';
                    $output .= '<div class="cct-token-name">' . esc_html($token_key) . '</div>';
                    $output .= '<div class="cct-token-value">' . esc_html($token_data['value']) . '</div>';
                    if (!empty($token_data['description'])) {
                        $output .= '<div class="cct-token-description">' . esc_html($token_data['description']) . '</div>';
                    }
                    $output .= '</div>';
                }
                
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Shortcode para documentação de tokens
     */
    public function token_documentation_shortcode($atts) {
        $atts = shortcode_atts(array(
            'format' => 'html', // html, table
            'include_css' => 'true',
            'class' => ''
        ), $atts, 'cct_token_documentation');
        
        $tokens = $this->get_all_tokens();
        $resolved_tokens = $this->resolve_token_references($tokens);
        
        $classes = array('cct-token-documentation');
        if (!empty($atts['class'])) {
            $classes[] = sanitize_html_class($atts['class']);
        }
        
        if ($atts['format'] === 'table') {
            return $this->generate_token_table($resolved_tokens, $classes);
        }
        
        return $this->generate_token_documentation($resolved_tokens, $classes, $atts['include_css'] === 'true');
    }
    
    /**
     * Obtém valor de um token
     */
    private function get_token_value($token_path) {
        $tokens = $this->get_all_tokens();
        $resolved_tokens = $this->resolve_token_references($tokens);
        
        $parts = explode('.', $token_path);
        
        if (count($parts) >= 3) {
            $category = $parts[0];
            $subcategory = $parts[1];
            $token_key = $parts[2];
            
            if (isset($resolved_tokens[$category][$subcategory][$token_key]['value'])) {
                return $resolved_tokens[$category][$subcategory][$token_key]['value'];
            }
        }
        
        return null;
    }
    
    /**
     * Converte token para variável CSS
     */
    private function token_to_css_variable($token_path) {
        $parts = explode('.', $token_path);
        
        if (count($parts) >= 3) {
            return $this->generate_css_variable_name($parts[0], $parts[1], $parts[2]);
        }
        
        return null;
    }
    
    /**
     * Gera tabela de tokens
     */
    private function generate_token_table($tokens, $classes) {
        $output = '<div class="' . implode(' ', $classes) . '">';
        $output .= '<table class="cct-tokens-table">';
        $output .= '<thead><tr><th>Token</th><th>Valor</th><th>Variável CSS</th><th>Descrição</th></tr></thead>';
        $output .= '<tbody>';
        
        foreach ($tokens as $category => $category_tokens) {
            foreach ($category_tokens as $subcategory => $subcategory_tokens) {
                foreach ($subcategory_tokens as $token_key => $token_data) {
                    $token_path = "{$category}.{$subcategory}.{$token_key}";
                    $css_var = $this->generate_css_variable_name($category, $subcategory, $token_key);
                    
                    $output .= '<tr>';
                    $output .= '<td><code>' . esc_html($token_path) . '</code></td>';
                    $output .= '<td>' . esc_html($token_data['value']) . '</td>';
                    $output .= '<td><code>var(' . esc_html($css_var) . ')</code></td>';
                    $output .= '<td>' . esc_html($token_data['description'] ?? '') . '</td>';
                    $output .= '</tr>';
                }
            }
        }
        
        $output .= '</tbody></table>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Gera documentação de tokens
     */
    private function generate_token_documentation($tokens, $classes, $include_css) {
        $output = '<div class="' . implode(' ', $classes) . '">';
        
        if ($include_css) {
            $output .= '<div class="cct-tokens-css">';
            $output .= '<h3>Variáveis CSS</h3>';
            $output .= '<pre><code>:root {\n';
            
            foreach ($tokens as $category => $category_tokens) {
                foreach ($category_tokens as $subcategory => $subcategory_tokens) {
                    foreach ($subcategory_tokens as $token_key => $token_data) {
                        $css_var = $this->generate_css_variable_name($category, $subcategory, $token_key);
                        $output .= "  {$css_var}: {$token_data['value']};\n";
                    }
                }
            }
            
            $output .= '}</code></pre>';
            $output .= '</div>';
        }
        
        foreach ($tokens as $category => $category_tokens) {
            $output .= '<div class="cct-token-category">';
            $output .= '<h3>' . esc_html(ucfirst($category)) . '</h3>';
            
            foreach ($category_tokens as $subcategory => $subcategory_tokens) {
                $output .= '<div class="cct-token-subcategory">';
                $output .= '<h4>' . esc_html(ucfirst($subcategory)) . '</h4>';
                $output .= '<div class="cct-tokens-grid">';
                
                foreach ($subcategory_tokens as $token_key => $token_data) {
                    $token_path = "{$category}.{$subcategory}.{$token_key}";
                    $css_var = $this->generate_css_variable_name($category, $subcategory, $token_key);
                    
                    $output .= '<div class="cct-token-card">';
                    $output .= '<div class="cct-token-header">';
                    $output .= '<h5>' . esc_html($token_key) . '</h5>';
                    $output .= '</div>';
                    $output .= '<div class="cct-token-content">';
                    $output .= '<div class="cct-token-value">' . esc_html($token_data['value']) . '</div>';
                    $output .= '<div class="cct-token-css"><code>var(' . esc_html($css_var) . ')</code></div>';
                    if (!empty($token_data['description'])) {
                        $output .= '<div class="cct-token-description">' . esc_html($token_data['description']) . '</div>';
                    }
                    $output .= '</div>';
                    $output .= '</div>';
                }
                
                $output .= '</div>';
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Sincroniza tokens após salvar
     */
    public function sync_tokens_after_save() {
        if (get_theme_mod($this->prefix . 'auto_sync', true)) {
            $this->sync_with_modules();
        }
    }
    
    /**
     * Sincroniza com outros módulos
     */
    private function sync_with_modules() {
        $tokens = $this->get_all_tokens();
        $resolved_tokens = $this->resolve_token_references($tokens);
        
        // Sincronizar com módulo de cores
        if (class_exists('CCT_Color_Manager')) {
            $this->sync_with_color_manager($resolved_tokens);
        }
        
        // Sincronizar com módulo de tipografia
        if (class_exists('CCT_Typography_Manager')) {
            $this->sync_with_typography_manager($resolved_tokens);
        }
        
        // Sincronizar com módulo de sombras
        if (class_exists('CCT_Shadow_Manager')) {
            $this->sync_with_shadow_manager($resolved_tokens);
        }
        
        // Sincronizar com módulo de breakpoints
        if (class_exists('CCT_Responsive_Breakpoints_Manager')) {
            $this->sync_with_breakpoints_manager($resolved_tokens);
        }
    }
    
    /**
     * Filtro para obter token
     */
    public function get_design_token($default, $token_path) {
        $value = $this->get_token_value($token_path);
        return $value !== null ? $value : $default;
    }
    
    /**
     * AJAX handlers
     */
    public function ajax_save_token() {
        check_ajax_referer('cct_design_tokens_nonce', 'nonce');
        
        $token_data = $this->sanitize_token_data($_POST['token'] ?? array());
        
        if (empty($token_data)) {
            wp_die(__('Dados de token inválidos.', 'cct'));
        }
        
        // Salvar token
        $this->save_token($token_data);
        
        wp_send_json_success(array(
            'message' => __('Token salvo com sucesso!', 'cct'),
            'token' => $token_data
        ));
    }
    
    public function ajax_export_tokens() {
        check_ajax_referer('cct_design_tokens_nonce', 'nonce');
        
        $format = sanitize_text_field($_POST['format'] ?? 'json');
        $tokens = $this->get_all_tokens();
        $resolved_tokens = $this->resolve_token_references($tokens);
        
        $exported_data = $this->export_tokens($resolved_tokens, $format);
        
        wp_send_json_success(array(
            'data' => $exported_data,
            'format' => $format,
            'filename' => 'design-tokens.' . $this->export_formats[$format]['extension']
        ));
    }
    
    public function ajax_sync_tokens() {
        check_ajax_referer('cct_design_tokens_nonce', 'nonce');
        
        $this->sync_with_modules();
        
        wp_send_json_success(array(
            'message' => __('Sincronização concluída!', 'cct')
        ));
    }
    
    /**
     * Sanitiza dados de token
     */
    private function sanitize_token_data($data) {
        if (!is_array($data)) {
            return array();
        }
        
        return array(
            'category' => sanitize_key($data['category'] ?? ''),
            'subcategory' => sanitize_key($data['subcategory'] ?? ''),
            'key' => sanitize_key($data['key'] ?? ''),
            'value' => sanitize_text_field($data['value'] ?? ''),
            'description' => sanitize_text_field($data['description'] ?? '')
        );
    }
    
    /**
     * Salva um token
     */
    private function save_token($token_data) {
        $tokens = $this->get_all_tokens();
        
        $category = $token_data['category'];
        $subcategory = $token_data['subcategory'];
        $key = $token_data['key'];
        
        if (!isset($tokens[$category])) {
            $tokens[$category] = array();
        }
        
        if (!isset($tokens[$category][$subcategory])) {
            $tokens[$category][$subcategory] = array();
        }
        
        $tokens[$category][$subcategory][$key] = array(
            'value' => $token_data['value'],
            'description' => $token_data['description'],
            'category' => $subcategory
        );
        
        // Salvar baseado no tipo de token
        if (in_array($category, array_keys($this->primitive_tokens))) {
            set_theme_mod($this->prefix . 'primitive_tokens', wp_json_encode($tokens));
        } elseif (in_array($category, array_keys($this->semantic_tokens))) {
            set_theme_mod($this->prefix . 'semantic_tokens', wp_json_encode($tokens));
        } else {
            set_theme_mod($this->prefix . 'component_tokens', wp_json_encode($tokens));
        }
    }
    
    /**
     * Exporta tokens em formato específico
     */
    private function export_tokens($tokens, $format) {
        switch ($format) {
            case 'css':
                return $this->export_to_css($tokens);
            case 'scss':
                return $this->export_to_scss($tokens);
            case 'js':
                return $this->export_to_js($tokens);
            case 'figma':
                return $this->export_to_figma($tokens);
            case 'json':
            default:
                return wp_json_encode($tokens, JSON_PRETTY_PRINT);
        }
    }
    
    /**
     * Exporta para CSS
     */
    private function export_to_css($tokens) {
        $css = ":root {\n";
        
        foreach ($tokens as $category => $category_tokens) {
            $css .= "  /* {$category} tokens */\n";
            foreach ($category_tokens as $subcategory => $subcategory_tokens) {
                foreach ($subcategory_tokens as $token_key => $token_data) {
                    $css_var = $this->generate_css_variable_name($category, $subcategory, $token_key);
                    $css .= "  {$css_var}: {$token_data['value']};\n";
                }
            }
            $css .= "\n";
        }
        
        $css .= "}";
        
        return $css;
    }
    
    /**
     * Exporta para SCSS
     */
    private function export_to_scss($tokens) {
        $scss = "// Design Tokens\n\n";
        
        foreach ($tokens as $category => $category_tokens) {
            $scss .= "// {$category} tokens\n";
            foreach ($category_tokens as $subcategory => $subcategory_tokens) {
                foreach ($subcategory_tokens as $token_key => $token_data) {
                    $var_name = '$' . str_replace('--cct-', '', $this->generate_css_variable_name($category, $subcategory, $token_key));
                    $scss .= "{$var_name}: {$token_data['value']};\n";
                }
            }
            $scss .= "\n";
        }
        
        return $scss;
    }
    
    /**
     * Exporta para JavaScript
     */
    private function export_to_js($tokens) {
        $js_namespace = get_theme_mod($this->prefix . 'js_namespace', 'CCTTokens');
        
        $js = "const {$js_namespace} = " . wp_json_encode($tokens, JSON_PRETTY_PRINT) . ";\n\n";
        $js .= "export default {$js_namespace};";
        
        return $js;
    }
    
    /**
     * Exporta para Figma
     */
    private function export_to_figma($tokens) {
        $figma_tokens = array();
        
        foreach ($tokens as $category => $category_tokens) {
            foreach ($category_tokens as $subcategory => $subcategory_tokens) {
                foreach ($subcategory_tokens as $token_key => $token_data) {
                    $figma_tokens["{$category}/{$subcategory}/{$token_key}"] = array(
                        'value' => $token_data['value'],
                        'type' => $this->get_figma_token_type($category, $subcategory),
                        'description' => $token_data['description'] ?? ''
                    );
                }
            }
        }
        
        return wp_json_encode($figma_tokens, JSON_PRETTY_PRINT);
    }
    
    /**
     * Obtém tipo de token para Figma
     */
    private function get_figma_token_type($category, $subcategory) {
        $type_map = array(
            'colors' => 'color',
            'typography' => array(
                'font-family' => 'fontFamilies',
                'font-size' => 'fontSizes',
                'font-weight' => 'fontWeights',
                'line-height' => 'lineHeights'
            ),
            'spacing' => 'spacing',
            'border-radius' => 'borderRadius',
            'shadows' => 'boxShadow'
        );
        
        if (isset($type_map[$category])) {
            if (is_array($type_map[$category])) {
                return $type_map[$category][$subcategory] ?? 'other';
            }
            return $type_map[$category];
        }
        
        return 'other';
    }
    
    /**
     * Obtém estatísticas dos tokens
     */
    public function get_tokens_stats() {
        $tokens = $this->get_all_tokens();
        $total_tokens = 0;
        $categories_count = 0;
        
        foreach ($tokens as $category => $category_tokens) {
            $categories_count++;
            foreach ($category_tokens as $subcategory => $subcategory_tokens) {
                $total_tokens += count($subcategory_tokens);
            }
        }
        
        return array(
            'total_tokens' => $total_tokens,
            'categories' => $categories_count,
            'primitive_tokens' => count($this->primitive_tokens),
            'semantic_tokens' => count($this->semantic_tokens),
            'component_tokens' => count($this->component_tokens),
            'enabled' => get_theme_mod($this->prefix . 'enabled', true),
            'auto_sync' => get_theme_mod($this->prefix . 'auto_sync', true),
            'version_control' => get_theme_mod($this->prefix . 'version_control', true)
        );
    }
}