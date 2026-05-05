<?php
/**
 * Extensão: Sistema de Busca Personalizado
 * 
 * Permite habilitar/desabilitar o sistema de busca personalizado
 * que substitui o Ivory Search por uma solução nativa.
 * 
 * @package UENF_Theme
 * @subpackage Extensions
 * @since 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class UENF_Search_Customizer {
    
    /**
     * Instância única da classe
     */
    private static $instance = null;
    
    /**
     * ID da extensão
     */
    const EXTENSION_ID = 'search_customizer';
    
    /**
     * Construtor privado para Singleton
     */
    private function __construct() {
        $this->init();
    }
    
    /**
     * Obter instância única
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Inicializar a extensão
     */
    private function init() {
        // Verificar se a extensão está ativa
        if (!$this->is_extension_active()) {
            return;
        }
        
        // Hooks para carregar assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
        // Hook para modificar o header
        add_action('wp_head', array($this, 'add_search_styles'));
    }
    
    /**
     * Verificar se a extensão está ativa
     */
    private function is_extension_active() {
        // Verificar configuração global
        $global_enabled = get_theme_mod('uenf_extensions_global_enabled', true);
        if (!$global_enabled) {
            return false;
        }
        
        // Verificar configuração individual
        return get_theme_mod('uenf_extension_' . self::EXTENSION_ID . '_enabled', true);
    }
    
    /**
     * Enfileirar assets da busca personalizada
     */
    public function enqueue_assets() {
        // Verificar novamente se está ativa antes de carregar assets
        if (!$this->is_extension_active()) {
            return;
        }
        
        // CSS da busca personalizada
        wp_enqueue_style(
            'uenf-search-customizer',
            get_template_directory_uri() . '/css/components/search.css',
            array(),
            filemtime(get_template_directory() . '/css/components/search.css')
        );
        
        // JavaScript da busca personalizada
        wp_enqueue_script(
            'uenf-search-customizer-js',
            get_template_directory_uri() . '/js/custom-search.js',
            array(),
            filemtime(get_template_directory() . '/js/custom-search.js'),
            true
        );
    }
    
    /**
     * Adicionar estilos inline se necessário
     */
    public function add_search_styles() {
        echo "\n<!-- Sistema de Busca Personalizado Ativo -->\n";
    }
    
    /**
     * Obter informações da extensão
     */
    public static function get_extension_info() {
        return array(
            'id' => self::EXTENSION_ID,
            'name' => '🔍 Sistema de Busca Personalizado',
            'description' => 'Sistema de busca nativo que substitui plugins externos como Ivory Search. Inclui ícone moderno com identidade UENF e campo expansível de 200px.',
            'version' => '1.0.0',
            'author' => 'Tema UENF',
            'category' => 'interface',
            'dependencies' => array(),
            'assets' => array(
                'css' => array('css/components/search.css'),
                'js' => array('js/custom-search.js')
            ),
            'features' => array(
                'Ícone de busca com design UENF',
                'Campo expansível de 200px à esquerda',
                'Animações suaves de entrada/saída',
                'Foco automático no campo',
                'Fechamento com ESC ou clique fora',
                'Compatível com página de resultados',
                'Substitui Ivory Search'
            )
        );
    }
    
    /**
     * Ativar extensão
     */
    public static function activate() {
        // Adicionar à lista de extensões ativas
        $active_extensions = get_option('uenf_active_extensions', array());
        if (!in_array(self::EXTENSION_ID, $active_extensions)) {
            $active_extensions[] = self::EXTENSION_ID;
            update_option('uenf_active_extensions', $active_extensions);
        }
        
        // Log de ativação
        error_log('CCT Search Customizer: Extensão ativada');
    }
    
    /**
     * Desativar extensão
     */
    public static function deactivate() {
        // Remover da lista de extensões ativas
        $active_extensions = get_option('uenf_active_extensions', array());
        $key = array_search(self::EXTENSION_ID, $active_extensions);
        if ($key !== false) {
            unset($active_extensions[$key]);
            update_option('uenf_active_extensions', array_values($active_extensions));
        }
        
        // Log de desativação
        error_log('CCT Search Customizer: Extensão desativada');
    }
    
    /**
     * Verificar se deve mostrar o componente de busca
     */
    public static function should_show_search() {
        $active_extensions = get_option('uenf_active_extensions', array());
        return in_array(self::EXTENSION_ID, $active_extensions);
    }
}

// Inicializar a extensão
UENF_Search_Customizer::get_instance();